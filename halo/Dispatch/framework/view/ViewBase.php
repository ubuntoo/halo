<?php
namespace framework\view;

/**
 * 视图基类
 */
abstract class ViewBase 
{
    /**
     * 视图数据
     * 
     * @var mixed
     */
    private $model;

    /**
     * 展示视图
     */
    abstract function display();
}