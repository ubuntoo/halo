<?php
namespace entity;

/**
 * Seller 实体类
 * 
 * @author wei.wang
 */
class Seller extends EntityBase
{
    /**
     * 主表
     *
     * @var string
     */
    const MAIN_TABLE = 'mall_seller';

    /**
     * 主键
     *
     * @var int
     */
    const PRIMARY_KEY = 'sellerId';

    /**
     * 卖方ID
     *
     * @var int
     */
    protected $sellerId;

    /**
     * 卖方名
     *
     * @var varchar
     */
    protected $name;

    /**
     * 商城ID
     *
     * @var int
     */
    protected $mallId;

    /**
     *  卖方信用
     *
     * @var varchar
     */
    protected $credit;

    /**
     * 卖方主页
     *
     * @var varchar
     */
    protected $homepage;

}