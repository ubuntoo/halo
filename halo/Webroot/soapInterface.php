<?php
/**
 * soap 的接口入口
 */
define('ROOT_PATH', realpath(dirname(__FILE__) . DIRECTORY_SEPARATOR . '..') . DIRECTORY_SEPARATOR);
define('DISPATCH_PATH', ROOT_PATH . 'Dispatch' . DIRECTORY_SEPARATOR);
require_once DISPATCH_PATH . 'dispatch.php';
$soapSv = Framework::$Locator->getService("Soap");
$soapSv->register(
    "CustomerCity.cityList",
    "获取服务器城市列表|array('id'=>'城市ID', name'=>'城市名称', 'x'=>'所在城市X坐标')",
    array('name'=>'城市名称', 'page'=>'当前页数', 'perpage'=>'每页显示的数量'), // 参数
    array("return"=>"xsd:Array") // 返回格式
);
$soapSv->startService();