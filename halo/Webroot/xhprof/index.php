<?php
//  Copyright (c) 2009 Facebook
//
//  Licensed under the Apache License, Version 2.0 (the "License");
//  you may not use this file except in compliance with the License.
//  You may obtain a copy of the License at
//
//      http://www.apache.org/licenses/LICENSE-2.0
//
//  Unless required by applicable law or agreed to in writing, software
//  distributed under the License is distributed on an "AS IS" BASIS,
//  WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
//  See the License for the specific language governing permissions and
//  limitations under the License.
//

//
// XHProf: A Hierarchical Profiler for PHP
//
// XHProf has two components:
//
//  * This module is the UI/reporting component, used
//    for viewing results of XHProf runs from a browser.
//
//  * Data collection component: This is implemented
//    as a PHP extension (XHProf).
//
//
//
// @author(s)  Kannan Muthukkaruppan
//             Changhao Jiang
//
/**
 * 性能分析查看入口
 * 
 */
define('ROOT_PATH', realpath(dirname(__FILE__) . DIRECTORY_SEPARATOR . '/../..') . DIRECTORY_SEPARATOR);
define('DISPATCH_PATH', ROOT_PATH . 'Dispatch' . DIRECTORY_SEPARATOR);
require_once DISPATCH_PATH . 'dispatch.php';
$savedPath = CACHE_PATH . "xhprofRuns" . DS;
// 列表页
if (!isset($_GET['run'])) {
    clearstatcache();
    $expiredTime = time() - 86400;
    if (!is_readable($savedPath)) {
        echo "Unreadable savedpath：{$savedPath}";
        return;
    }
    $dh = opendir($savedPath);
    $finals = array();
    for ($i = 1; ($file = readdir($dh)) !== false; $i ++) {
        if (in_array($file, array('.', '..'))) continue;
        $fileinfo = stat($savedPath . $file);
        if ($fileinfo['mtime'] < $expiredTime &&
            is_writable($savedPath . $file)
        ) {
            unlink($savedPath . $file);
            continue;
        }
        $fileNames = explode(".", $file); //seperate filename
        $run_id = array_shift($fileNames);
        $nameSpace = implode(".", $fileNames);
        $finals[$nameSpace][$fileinfo['mtime']] = $run_id . '|||' . $fileinfo['mtime'];
    }
    closedir($dh);
    foreach ($finals as $nameSpace => $runs) {
        krsort($runs);
        echo "<b>{$nameSpace}:</b><br />";
        foreach ($runs as $row) {
            list($run_id, $time) = explode('|||', $row);
            $linkUrl = "?run={$run_id}&source={$nameSpace}";
            echo date('Y-m-d H:i:s', $time) . ": <a href='{$linkUrl}' target='_blank' >{$linkUrl}</a><br />\n";
        }
        echo "<hr /><br />";
    }

    return;
}

// by default assume that xhprof_html & xhprof_lib directories
// are at the same level.

// 详情页
$GLOBALS['XHPROF_LIB_ROOT'] = LIB_PATH . '/xhprof/xhprof_lib';

include_once $GLOBALS['XHPROF_LIB_ROOT'].'/display/xhprof.php';

// param name, its type, and default value
$params = array('run'        => array(XHPROF_STRING_PARAM, ''),
                'wts'        => array(XHPROF_STRING_PARAM, ''),
                'symbol'     => array(XHPROF_STRING_PARAM, ''),
                'sort'       => array(XHPROF_STRING_PARAM, 'wt'), // wall time
                'run1'       => array(XHPROF_STRING_PARAM, ''),
                'run2'       => array(XHPROF_STRING_PARAM, ''),
                'source'     => array(XHPROF_STRING_PARAM, 'xhprof'),
                'all'        => array(XHPROF_UINT_PARAM, 0),
                );

// pull values of these params, and create named globals for each param
xhprof_param_init($params);

/* reset params to be a array of variable names to values
   by the end of this page, param should only contain values that need
   to be preserved for the next page. unset all unwanted keys in $params.
 */
foreach ($params as $k => $v) {
  $params[$k] = $$k;

  // unset key from params that are using default values. So URLs aren't
  // ridiculously long.
  if ($params[$k] == $v[1]) {
    unset($params[$k]);
  }
}

echo "<html>";

echo "<head><title>XHProf:性能分析报告</title>";
xhprof_include_js_css("");
echo "</head>";

echo "<body>";

$vbar  = ' class="vbar"';
$vwbar = ' class="vwbar"';
$vwlbar = ' class="vwlbar"';
$vbbar = ' class="vbbar"';
$vrbar = ' class="vrbar"';
$vgbar = ' class="vgbar"';

$xhprof_runs_impl = new XHProfRuns_Default($savedPath);

displayXHProfReport($xhprof_runs_impl, $params, $source, $run, $wts,
                    $symbol, $sort, $run1, $run2);
echo "</body>";
echo "</html>";
