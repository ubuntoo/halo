<?php
namespace dao;
use framework\core\Context;

use framework\exception\FrameException;

use Dispatch\FrameBase;

/**
 * 数据层抽象基类
 * 
 * @author
 */
abstract class DaoBase extends FrameBase 
{
	/**
     * 实体       默认为stdClass
     *
     * @var string
     */
    protected $entity = 'stdClass';
    
	/**
     * 主表名
     *
     * @var string
     */
    protected $mainTable = null;
    
    /**
     * 主键       默认为id
     *
     * @var string
     */
    protected $primaryKey = 'id';
	
	/**
     * 构造函数
     * 
     * @return void
     */
    public function __construct() 
    {
        parent::__construct(__NAMESPACE__);
    	$entityClass = str_replace('dao', 'entity', get_class($this));
    	if (class_exists($entityClass)) {
    		$this->entity     = $entityClass;
    	    $this->mainTable  = $entityClass::MAIN_TABLE;
            $this->primaryKey = $entityClass::PRIMARY_KEY;      
            $this->daoHelper->setEntityClass($entityClass);
    	}
    }
    
    /**
     * 获取新的实体对象
     * 
     * @return entity
     */
    public function getNewEntity()
    {
        return new $this->entity;
    }
    
 	/**
     * 获取一个对象实体
     * 
     * @param   $id         int      主键值
     * @param   $fields     array    要获取的属性列表
     * 
     * @return  entity
     */
    public function read($id, $fields = null)
    {
    	
    	$where = "`{$this->mainTable}` . `{$this->primaryKey}` = '{$id}'";
    	$result = self::readList($where, $fields);
        $info = (!empty($result) && is_array($result)) ? reset($result) : null;	
        return $info;
    }
    
    /**
     * 获取实体对象集合
     *
     * @param  string   $where      用于查询的SQL条件
     * @param  array    $fields     要获取的属性列表
     * @param  string   $selfTable  自定义的数据表
     *
     * @return array
     */
    public function readList($where = null, $fields = array(), $selfTable = null)
    {
    	$tables = is_null($selfTable) ? $this->mainTable : $selfTable;
        // 处理表
        $tableArr = array();
        if (is_array($tables)) foreach ($tables as $table) {
            $tableArr[] = "`$table`";
        }
        $tableStr = !empty($tableArr) ? implode(",", $tableArr) : (is_string($tables) ? $tables : null);      
        if (empty($tableStr)) {
            // 未定义数据表
            $this->exception->thow('e.dao.undefinedTable');
        }
        
        // 处理字段
        $fieldArr = array();
        if (is_array($fields)) foreach ($fields as $field => $as ) {
        	if (is_numeric($field)) {
        	   $fieldArr[] = "`$as`";
        	} else {
        	   $fieldArr[] = "`$field` as `$as`";
        	}
        }   
        $fieldStr = !empty($fieldArr) ? implode(",", $fieldArr) : (is_string($fields) ? $fields : '*');
	
        // 处理where条件
        $where = $this->getWhere($where);      
		$this->daoHelper->setTable($tableStr);
        $list = $this->daoHelper->fetchAll($fieldStr, $where);     
        return $list;
    }
    
    /**
     * 删除
     * 
     * @param $obj   实体对像
     * 
     * @return bool
     */
    public function remove($obj)
    {
    	if (is_object($obj)) {
    	    $entityClass = get_class($obj);
            $primaryKey  = $entityClass::PRIMARY_KEY;
            $primaryVal  = $obj->$primaryKey;
            $where = "`{$primaryKey}` = '{$primaryVal}'";
            $tables = $entityClass::MAIN_TABLE;
            $tableStr = !empty($tableArr) ? implode(",", $tableArr) : (is_string($tables) ? $tables : null);
            $this->daoHelper->setTable($tableStr);
            $this->daoHelper->remove($where);
    	}
        return true;
    }
    
    /**
     * 创建一个对象
     * 
     * @param  $object \entity\* 对象实体
     * 
     * @return bool
     */
    public function create($object)
    {
        $this->createList(array($object));
        return true;
    }
    
    /**
     * 批量创建对象
     * 
     * @param $objList array 实体对象数组
     * 
     * @return bool
     */
    public function createList($objList)
    {	
    	$tableMap = $this->getTableMapByObject($objList);
    	$data = array();
        if ($tableMap) foreach ($objList as $obj) {
            if (is_object($obj)) {
                $entityClass = get_class($obj);
                $tables = $entityClass::MAIN_TABLE;
                $fields = $tableMap[$tables];
                if (!isset($data[$tables])) $data[$tables]['field'] = $fields;
                $value = array();
                $objArr = (array)$obj;
                foreach ($fields as $field) {
                	$value[] = isset($objArr[$field]) ? $objArr[$field] : '';
                }
                $data[$tables]['value'][] = $value;
            }
        }      
        if ($data) foreach ($data as $key => $val) {      
        	$this->daoHelper->setTable($key);
            $this->daoHelper->addBat($val['field'], $val['value']);    
        }
        return true;
    }
    
   /**
     * 根据映射关系构造对象改变值数组
     * @param $obj \entity\* 实体对象
     * @param $map array 映射关系数组
     * @return stdClass 返回包含setFields和arrFields属性的stdClass对象
     */
    final protected function getChangeByMap(&$obj, $map)
    {

        // 获取对象改变的内容
        $setArr = $obj->setArr;
        $addArr = $obj->addArr;
        $minArr = $obj->minArr;
        $maxArr = $obj->maxArr;

        $setFields = array();
        $addFields = array();
        $minFields = array();
        $maxFields = array();
        foreach ($map as $field) {
        	if (isset($setArr[$field])) {
                $setFields[$field] = $setArr[$field];
            }
            if (isset($addArr[$field])) {
                $addFields[$field] = $addArr[$field];
            }
            if (isset($minArr[$field])) {
                $minFields[$field] = $minArr[$field];
            }
            if (isset($maxArr[$field])) {
                $maxFields[$field] = $maxArr[$field];
            }
        }

        $change = new \stdClass();
        $change->setFields = $setFields;
        $change->addFields = $addFields;
        $change->minFields = $minFields;
        $change->maxFields = $maxFields;
        return $change;
    }
    
    /**
     * 根据主键更新持久化数据
     * 
     * @param $obj entity 实体对象
     * 
     * @return bool
     */
    public function update($obj)
    {
    	if (!is_object($obj)) return ;
    	$tableMap = $this->getTableMapByObject(array($obj));
    	
    	foreach ($tableMap as $table => $value) {
    		$change = $this->getChangeByMap($obj, $value);     		
    		if (empty($change->setFields) && empty($change->addFields)) continue; 
    		// 获取主键字段
            $entityClass = get_class($obj);
            $primaryKey  = $entityClass::PRIMARY_KEY;
            $primaryVal  = $obj->$primaryKey;
            // 更新条件
            $where = "`{$primaryKey}`='{$primaryVal}'";
            // 执行更新      
            $this->daoHelper->setTable($table);
            $this->daoHelper->update($change->setFields, $change->addFields, $where, $change->minFields, $change->maxFields);
    	    // 清除实体对象的数据改变状态
            $obj->clearChange();
    	}
        return true;
    }
    
   /**
     * 根据一个对象获取对象的表结构
     * 
     * @param  $objList  实体对象数组
     * 
     * @return 结构数组
     */
    private static function getTableMapByObject($objList)
    {
        $tableMap = array();
        if (is_array($objList)) foreach ($objList as $obj) {
            if (is_object($obj)) {
                $entityClass = get_class($obj);
                $tables = $entityClass::MAIN_TABLE; // 获取对象数据表 
                if (!isset($tableMap[$tables])) $tableMap[$tables] = array();
                $objArr = (array)$obj;
                foreach ($objArr as $key => $val) {
                    //$key = str_replace('*', '', $key);            
                    if (!in_array($key, $tableMap[$tables])) {  
                        $tableMap[$tables][] = $key;   
                    }
                }
            }
        }  
        return $tableMap;
    }
    
   /**
     * 处理where
     * 
     * @param  $where
     * 
     * @return $where string
     */
    private static function getWhere($where)
    {
        // 处理where条件
        $whereArr = array();  
        if (is_string($where) && !is_numeric($where)) {
            $where = explode(',', $where);
            foreach ($where as $val) {
                if (is_numeric($val)) {
                    $whereArr[] = "`{$this->primaryKey}` = '{$val}'";
                } else {
                    $whereArr[] = $val;
                }
            }
        } elseif (is_numeric($where)) {
            $whereArr[] = "`{$this->primaryKey}` = '{$where}'";
        } elseif (is_array($where)) {
            foreach ($where as $key => $val) {
                if(is_numeric($key) && !is_string($val)) {
                    $whereArr[] = "`{$this->primaryKey}` = '{$val}'";
                } elseif(is_string($val)) {
                    $whereArr[] = $val;
                } else {
                    $whereArr[] = "`{$key}` = '{$val}'";
                }
            }        
        } else {
            $whereArr = array("1");
        }       
        return implode(" and ", $whereArr);
    }
}