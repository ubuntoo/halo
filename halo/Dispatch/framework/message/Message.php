<?php
namespace framework\message;
use Framework;
use dao\DaoBase as DaoBase;
/**
 *  消息处理类
 *  
 *  @package framework\message\Message
 */
class Message extends MessageBase {
    static public $pattern = null; // 消息模式
    private $daoHelper = null;     // 数据库操作对象
    private $frame = null;         // 框架标准
	
    /**
     * 获取消息模式
     * 
     * @return obj
     */
    static private function getPattern() {
        $model = new \stdClass();
        $model->type      = null;  // 发送类型
        $model->target    = null;  // 发送对象
        $model->content   = null;  // 内容
        $model->time      = null;  // 发送时间 
        $model->interrupt = null;  // 是否中断
        return $model;
    }
    
    /**
     * 构造函数，构造消息模式
     * 
     * @return void
     */
    public function __construct() {
    	self::$pattern = self::getPattern();
    	$this->daoHelper = &Framework::$DaoHelper;
    	$this->frame = &Framework::$Frame;
    	return ;
    }
    
    /**
     * 析构函数，销毁消息模式
     * 
     * @return void
     */
    public function __destruct() {
    	self::$pattern = null;
    	return ;
    }
    
    /**
     * 接受消息
     * 
     * @return
     */
    public function receive() {
    
    }
    
    /**
     * 推送消息
     * 
     * @param $mode  消息模式
     * @param $arg   消息参数
     * 
     * @return;
     */
    public function send($mode, $param = null) {
    	
/*    $mode ='#1';
    $param = array(
        'DADF'=>1,
        'DF'=>1,
        'message' => (object)array(
            'receiver'=> array(1,2,3,5),
            'sender' => 1,
            'sendtime' => '* 23-7/1 * * *',
            'createTime' => $this->frame->now,
            'overtime' => 0,
        ),  
        
    );*/
        if (is_string($mode) && preg_match("/^#(\d+)([f|c]?)$/", $mode, $matches)) { // 消息
            switch (array_pop($matches)) {
                case 'f' : $type = self::TYPE_FILTER;    break;
                case 'c' : $type = self::TYPE_FTP;       break;
                default  : $type = self::TYPE_INTERRUPT; break;
            }                
            $langKey = intval(array_pop($matches));      
            if (isset($GLOBALS['lang'][$langKey])) {
                $langStr = $GLOBALS['lang'][$langKey];
            } else {
                // 编号为[NUMBER]的提示消息在语言包中不存在
                Framework::$Exception->error("e.number'[NUMBER] not found in lang'", array('NUMBER' => $langKey));
            }
            $messageArg = isset($param['message']) ? $param['message'] : null;
            unset($param['message']);
            $mode = _v($langStr, $param);          
            
            if ($type == self::TYPE_INTERRUPT) { // 中断
                
            } elseif ($type == self::TYPE_FTP) { // 续传 
                // 推送   到消息监控接口
                $count = array(
                    'message' => $result,
                    'receiver' => isset($messageArg->receiver) ? $messageArg->receiver : array(),
                    'receiver' => isset($messageArg->receiver) ? $messageArg->receiver : array(),
                );
            }
        }
    	return $mode;
    }
    
    public function __toString()
    {
        return parent::__toString();
    }
    
    /**
     * 直接输出字符串
     * 
     * @param $arg obj 消息模式
     *  
     * @return 
     */
    private function sendString ($arg) {
    	$content = $arg->content;
    	$message = null;
    	if ($arg->interrupt == self::TYPE_INTERRUPT) { // 中断
    		die($content);
    	} else { // 续传
    		// 将消息推送到消息池
    	    $id = $this->push($arg);
    	    $messageObj = $this->pull($id);
    	    $message = $messageObj->message;
    	    
    	    $this->flush();
    	}
    	return $message;
    }
    
   /**
     * 刷新消息池
     */
    public function flush($userId = null) {
    	$this->daoHelper->setTable('frame_messagePool');
    	$where = "`overtime` <= '{$this->frame->now}'";
    	if (!is_null($userId)) {
    	   $where .= " and `sender` = '{$userId}'";
    	}
    	return $this->daoHelper->remove($where);
    }
    
   /**
     * 推    -   推送消息到消息池
     * 
     * @return int
     */
    private function push($arg) {
        $arrFields = array(
            'type'       => $arg->type,
            'message'    => $arg->content,
            'sender'     => 1,
            'receiver'   => $arg->target,
            'sendtime'   => $arg->time,
            'createTime' => $this->frame->now,
            'overtime'   => 0,
        );       	
    	$this->daoHelper->setTable('frame_messagePool');
        return $this->daoHelper->add($arrFields);
    }
    
    /**
     * 拉    -   从消息池中提取消息
     * 
     * @param $id  消息id
     * 
     * @return int
     */
    private function pull($id = null) {         
        $this->daoHelper->setTable('frame_messagePool');
        $where = "`id` = '{$id}'";
        return $this->daoHelper->fetchObj('*', $where);
    }
}