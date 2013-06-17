<?php
namespace entity;

/**
 * Goods 实体类
 * 
 * @author wei.wang
 */
class Goods extends EntityBase
{
    /**
     * 主表
     *
     * @var string
     */
    const MAIN_TABLE = 'mall_goods';

    /**
     * 主键
     *
     * @var int
     */
    const PRIMARY_KEY = 'id';

    /**
     * 商品ID
     *
     * @var int
     */
    protected $id;

    /**
     * 商品标题
     *
     * @var varchar
     */
    protected $title;

    /**
     * 官方图片ID
     *
     * @var tinyint
     */
    protected $icon;

    /**
     * 商城ID
     *
     * @var tinyint
     */
    protected $mallId;

    /**
     * 商城分类ID
     *
     * @var tinyint
     */
    protected $mallClassifyId;

    /**
     * 商品链接
     *
     * @var varchar
     */
    protected $url;

    /**
     * 价格
     *
     * @var double
     */
    protected $price;

    /**
     * 折扣
     *
     * @var double
     */
    protected $discount;

    /**
     * 赠品
     *
     * @var varchar
     */
    protected $gift;

    /**
     * 走势
     *
     * @var tinyint
     */
    protected $trend;

    /**
     * 库存
     *
     * @var int
     */
    protected $stockBalance;

    /**
     * 卖方
     *
     * @var int
     */
    protected $sellerId;

    /**
     * 好评
     *
     * @var tinyint
     */
    protected $highOpinion;

    /**
     * 中评
     *
     * @var tinyint
     */
    protected $secondaryOpinion;

    /**
     * 差评
     *
     * @var tinyint
     */
    protected $negativeOpinion;

    /**
     * 销量
     *
     * @var int
     */
    protected $salesVolume;

    /**
     * 运费
     *
     * @var double
     */
    protected $freight;

    /**
     * 官网差异
     *
     * @var text
     */
    protected $diversity;

    /**
     * 更新时间
     *
     * @var int
     */
    protected $updateTime;

    /**
     * 参数描述
     *
     * @var text
     */
    protected $parameterDesc;

}