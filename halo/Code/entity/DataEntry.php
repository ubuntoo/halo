<?php
namespace entity;

/**
 * DataEntry类
 * 
 * @author wei.wang
 */
class DataEntry extends EntityBase
{
    /**
     * 主表
     *
     * @var
     */
    const MAIN_TABLE = 'mall_goods';

    /**
     * 主键
     *
     * @var 
     */
    const PRIMARY_KEY = 'id';

    /**
     * 商品ID
     *
     * @var int(11)
     */
    public $id;

    /**
     * 商城ID
     *
     * @var tinyint(4)
     */
    public $mallId;

    /**
     * 商城分类ID
     *
     * @var tinyint(4)
     */
    public $mallClassifyId;

    /**
     * 商城商品ID
     *
     * @var int(11)
     */
    public $mallGoodsId;

    /**
     * 商品标题
     *
     * @var varchar(255)
     */
    public $title;

    /**
     * 商品主页
     *
     * @var varchar(255)
     */
    public $url;

    /**
     * 图片地址
     *
     * @var varchar(255)
     */
    public $pictureUrl;

    /**
     * 价格
     *
     * @var double
     */
    public $price;

    /**
     * 折扣
     *
     * @var double
     */
    public $discount;

    /**
     * 赠品
     *
     * @var varchar(255)
     */
    public $gift;

    /**
     * 走势
     *
     * @var tinyint(1)
     */
    public $trend;

    /**
     * 库存
     *
     * @var int(11)
     */
    public $stockBalance;

    /**
     * 卖方
     *
     * @var varchar(255)
     */
    public $seller;

    /**
     * 好评
     *
     * @var int(11)
     */
    public $highOpinion;

    /**
     * 中评
     *
     * @var int(11)
     */
    public $secondaryOpinion;

    /**
     * 差评
     *
     * @var int(11)
     */
    public $negativeOpinion;

    /**
     * 销量
     *
     * @var int(11)
     */
    public $salesVolume;

    /**
     * 运费
     *
     * @var double
     */
    public $freight;

    /**
     * 官网差异
     *
     * @var text
     */
    public $diversity;

    /**
     * 更新时间
     *
     * @var int(11)
     */
    public $updateTime;

    /**
     * 参数描述
     *
     * @var text
     */
    public $parameterDesc;

}