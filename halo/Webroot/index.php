<?php
/**
 * web入口
 * 
 * @version v1.0
 * @author	wei.wang <ubunto@sina.cn>
 */
require realpath(dirname(__FILE__) . DIRECTORY_SEPARATOR . '..') . DIRECTORY_SEPARATOR . 'Dispatch' . DIRECTORY_SEPARATOR . 'Halo.php';
Halo::run(Halo::HALO_WEB);