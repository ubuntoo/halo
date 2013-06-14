<?php
namespace framework\db;

/**
 *  数据库操作接口
 *  
 *  1. 定义查询模式 
 *  2. 执行sql
 *  3. 执行查询sql
 */
interface IDaoHelper
{
	/**
	 * 查询模式：获取一条
	 * 
	 * @var int
	 */
    const FETCH_MODE_ONE = 1;
    /**
	 * 查询模式：获取一列
	 * 
	 * @var int
	 */
    const FETCH_MODE_COL = 2;
    /**
	 * 查询模式：以数组的方式获取一列
	 * 
	 * @var int
	 */
    const FETCH_MODE_ARR_ROW = 3;
    /**
	 * 查询模式：获取所有
	 * 
	 * @var int
	 */
    const FETCH_MODE_ARR_ALL = 4;
    /**
	 * 查询模式：一条
	 * 
	 * @var int
	 */
    const FETCH_MODE_ROW = 5;
    /**
	 * 查询模式：所有
	 * 
	 * @var int
	 */
    const FETCH_MODE_ALL = 6;
    
    /**
     * 执行一个SQL语句，并返回影响的行数
     * 
     * @param string $sql sql语句
     * 
     * @return int 影响的行数
     */
    public function execBySql($sql);

    /**
     * 执行一个查询SQL语句，并返回结果
     * 
     * @param string $sql sql语句
     * 
     * @return object 查询的结果
     */
    public function fetchBySql($sql);
}