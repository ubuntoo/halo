<?php
namespace entity;

/**
 * GoodsOfficial 实体类
 * 
 * @author wei.wang
 */
class GoodsOfficial extends EntityBase
{
    /**
     * 主表
     *
     * @var string
     */
    const MAIN_TABLE = 'mall_goodsOfficial';

    /**
     * 主键
     *
     * @var int
     */
    const PRIMARY_KEY = 'goodsId';

    /**
     * 商品ID
     *
     * @var int
     */
    protected $goodsId;

    /**
     * 官方图片ID
     *
     * @var tinyint
     */
    protected $icon;

    /**
     * 官方url
     *
     * @var varchar
     */
    protected $url;

    /**
     * 官方商品描述
     *
     * @var text
     */
    protected $info;

    /**
     * 官方评测url
     *
     * @var varchar
     */
    protected $evaluateUrl;

}