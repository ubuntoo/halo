<?php
namespace service;
loadFile('reader.php', LIB_PATH . 'Excel' . DS); // 引入第三方库

/**
 * Excel文档操作类的 封装
 * 
 * @author
 */
class Read
{
    /**
     * @return void
     */
    public function __construct() {}
    
    /**
     * 导入
     * 
     * @param
     */
    public function import($parameter)
    {
        if (!isset($parameter["tableId"]) || !isset($parameter["content"])
            || !isset(self::$TABLE_MAPPING[$parameter["tableId"]])
        ) return array(0, 'ParameterError');
        $truncate = empty($parameter["truncate"]) ? false : true;
        $tableId = $parameter["tableId"];
        $tableName = self::$TABLE_MAPPING[$parameter["tableId"]];
        $content = base64_decode($parameter["content"]);
        
        $tmpPath = CACHE_PATH . "importData" . DS;
        $filename = "{$tableName}.xls";
        file_put_contents($tmpPath . $filename, $content);

        // 读取EXCEL并写入相应的表
        $installSv = $this->locator->getService("Installer");
        $installSv->setDir($tmpPath);
        $kvDao = $this->locator->getDao("KeyValue");
        try {
            $installSv->initStaticData($tableName, $filename, $truncate);
            // 处理导入数据
            if ($tableId == 100) { 
                $commonDao = $this->locator->getDao('Common');
                $createTable = <<<EOT
CREATE TABLE IF NOT EXISTS `game_updateData` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '数据编号',
  `ordinal` varchar(256) NOT NULL COMMENT '序列号',
  `data` longtext NOT NULL COMMENT '数据',
  PRIMARY KEY (`id`),
  KEY `ordinal` (`ordinal`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='数据更新临时表' AUTO_INCREMENT=1 ;
EOT;
                $commonDao->execBySql($createTable); // 创建数据表
                
                $commonDao->setTable('game_updateData');
                $where = "1 order by `id` asc";
                $resultList = $commonDao->fetchAll('*', $where);
                if (empty($resultList)) {
                    return array(0, 'dataError');
                }
                $ordinal = isset($resultList['0']->ordinal) ? $resultList['0']->ordinal : null; // 序列号
                if (count($resultList) != substr($ordinal, 20)) {
                    return array(0, 'dataError');
                }
                $afterData = null;
                foreach ($resultList as $result) {
                    if (!preg_match('/^{.*}$/', $result->data)) {
                        return array(0, 'dataError');
                    }
                    $afterData .= substr($result->data, 1, -1);
                }           
                // 解密 解压
                $data = bzdecompress(base64_decode($afterData));
                $result = $commonDao->execBySql($data); // 数据更新
                if (is_numeric($result) && $result) {
                    $kvDao->set('UPATE_DATA_ID', $ordinal); 
                } else {
                    return array(0, 'dataError');
                }
                // 插入数据序列号用于对比使用
            }   
            // 处理多web服务器缓存清空
            $key = $this->system['date']['mktm']."clearConstcache";
            $kvDao->set('cache_key', $key);
            // 清除memcache缓存
            $this->cache->flush();
            $cc = new \ConstCache;
            $cc->flush();
        } catch (\Exception $e) {
            return array(0, $e->getMessage());
        }

        return array(1, 'ok');
    }

    /**
     * 导出数据表
     * @param string $token 验证令牌
     * @param array $parameter 参数表
     */
    public function exportData($token, $parameter)
    {
        $return = check_token($token, array($parameter));
        if($return[0] == false) return array(0, 'tokenIsError');

        if (!isset($parameter["tableId"])
            || !isset(self::$TABLE_MAPPING[$parameter["tableId"]])
        ) return array(0, 'ParameterError');
        $tableId = $parameter["tableId"];

        // 读取数据表
        $tableName = self::$TABLE_MAPPING[$tableId];
        $commonDao = $this->locator->getDao('Common');
        $columns = $commonDao->fetchBySql("SHOW FULL COLUMNS FROM `{$tableName}`");
        $data = $commonDao->fetchBySql("SELECT * FROM `{$tableName}`");

        if (!is_iteratable($columns) || !is_iteratable($data)) {
            return array(0, "数据表 {$tableName} 的数据获取失败！");
        }

        // 构造Excel文件
        $content = <<<XMLHEADER
<?xml version="1.0"?>
<?mso-application progid="Excel.Sheet"?>
<Workbook
  xmlns="urn:schemas-microsoft-com:office:spreadsheet"
  xmlns:o="urn:schemas-microsoft-com:office:office"
  xmlns:x="urn:schemas-microsoft-com:office:excel"
  xmlns:ss="urn:schemas-microsoft-com:office:spreadsheet"
  xmlns:html="http://www.w3.org/TR/REC-html40">

XMLHEADER;

        // 数据：sheet1
        $content .= "  <Worksheet ss:Name=\"{$tableName}\">\n";
        $content .= "    <Table>\n";
        $content .= "      <Row ss:AutoFitHeight=\"0\">\n";
        $fields = array();
        $comments = array();
        foreach ($columns as $column) {
            $fields[] = $column->Field;
            $comments[$column->Field] = $column->Comment;
            $content .= '        <Cell>';
            $content .= "<Data ss:Type=\"String\">{$column->Comment}</Data>";
            // $content .= "<Data ss:Type=\"String\">{$column->Field}</Data>";
            // $content .= "<Comment><ss:Data>{$column->Comment}</ss:Data></Comment>";
            $content .= "</Cell>\n";
        }
        $content .= "      </Row>\n";
        foreach ($data as $d) {
            $content .= "      <Row ss:AutoFitHeight=\"0\">\n";
            foreach ($fields as $f) {
                $cellData = $d->$f;
                $dataType = is_numeric($cellData) ? "Number" : "String";
                $content .= "        <Cell>";
                $content .= "<Data ss:Type=\"{$dataType}\">{$cellData}</Data>";
                $content .= "</Cell>\n";
            }
            $content .= "      </Row>\n";
        }
        $content .= "    </Table>\n";
        $content .= "  </Worksheet>\n";

        // 字段映射：sheet2
        $content .= "  <Worksheet ss:Name=\"FieldsMap\">\n";
        $content .= "    <Table>\n";
        foreach ($comments as $field => $comment) {
            $content .= "      <Row ss:AutoFitHeight=\"0\">\n";
            $content .= "        <Cell>";
            $content .= "<Data ss:Type=\"String\">{$comment}</Data>";
            $content .= "</Cell>\n";
            $content .= "        <Cell>";
            $content .= "<Data ss:Type=\"String\">{$field}</Data>";
            $content .= "</Cell>\n";
            $content .= "      </Row>\n";
        }
        $content .= "    </Table>\n";
        $content .= "  </Worksheet>\n";

        $content .= "</Workbook>";

        $content = base64_encode($content);
        $result = array("content" => $content);
        return array(1, $result);
    }
}