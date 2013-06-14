<?php
/**
 * web入口
 * 
 * @version
 * @author  wei.wang <ubunto@sina.cn>
 */
require realpath(dirname(__FILE__) . DIRECTORY_SEPARATOR . '..') . DIRECTORY_SEPARATOR . 'Dispatch' . DIRECTORY_SEPARATOR . 'Halo.php';
Halo::run(Halo::HALO_WEB);