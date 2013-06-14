<?php
namespace entity;

/**
 * Test类
 * 
 * @author
 */
class Test extends EntityBase
{
    /**
     * 主表
     *
     * @var
     */
    const MAIN_TABLE = 'frame_test';

    /**
     * 主键
     *
     * @var 
     */
    const PRIMARY_KEY = 'testId';

    /**
     * 主键ID
     *
     * @var int(11)
     */
    public $testId;

    /**
     * 第一个字段
     *
     * @var varchar(255)
     */
    public $testFieldOne;

    /**
     * 第二个字段
     *
     * @var tinyint(4)
     */
    public $testFieldtwo;

    /**
     * 第三个字段
     *
     * @var tinyint(4)
     */
    public $testFieldthree;

    /**
     * 第四个字段
     *
     * @var varchar(255)
     */
    public $testFieldfour;

    /**
     * 第五个字段
     *
     * @var double
     */
    public $testFieldFive;
}