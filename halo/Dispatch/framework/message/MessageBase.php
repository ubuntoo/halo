<?php
namespace framework\message;

/**
 * 消息处理基类
 * 
 * @package framework\core
 */
abstract class MessageBase
{
	/**
     * 消息类型：直接输出字符串
     * @var int
     */
	const TYPE_STRING = 1;
	    
    /**
     * 传输类型：中断 - 输出
     * @var int
     */
    const TYPE_INTERRUPT = 1;
    
    /**
     * 传输类型：续传  - 输出
     * @var int
     */
    const TYPE_FTP = 2;
    
    /**
     * 传输类型：过滤  - 不输出
     * @var int
     */
    const TYPE_FILTER = 3;
    
    /**
     * 接收对象：自己
     * @VAR INT
     */
    CONST TYPE_TARGET_SELF = 1;
    
    /**
     * 接收对象：根据角色id自定对象
     * @VAR INT
     */
    CONST TYPE_TARGET_CUSTOM = 2;
    
    /**
     * 接收对象：除自己之外的其他人
     * @VAR INT
     */
    CONST TYPE_TARGET_OTHER = 3;
    
    /**
     * 接收对象：系统 所有人
     * @VAR INT
     */
    CONST TYPE_TARGET_ALL = 4;
    
    /**
     * 接收对象：管理员
     * @VAR INT
     */
    CONST TYPE_TARGET_ADMIN = 5;
    
    /**
     * 接收对象：系统框架
     * @VAR INT
     */
    CONST TYPE_TARGET_FRAME = 6;
    
    /**
     * 发送时间：立刻
     * @VAR INT
     */
    CONST TYPE_SENDTIME_ATONCE = 1;
    
    /**
     * 发送时间：定时
     * @VAR INT
     */
    CONST TYPE_SENDTIME_TIMING = 2;
	
    
    static private $passLocational = true; // 是否经过语言包
    /**
     * 信息
     * 
     * @var mixed
     */
    private $message;
    
    /**
     * 接受消息
     */
    abstract function receive();

    /**
     * 推送消息
     */
    abstract function send($arg);
    
    /**
     * 刷新消息池
     */
    abstract function flush();
}