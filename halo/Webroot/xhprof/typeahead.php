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
define('ROOT_PATH', realpath(dirname(__FILE__) . DIRECTORY_SEPARATOR . '/../..') . DIRECTORY_SEPARATOR);
define('DISPATCH_PATH', ROOT_PATH . 'Dispatch' . DIRECTORY_SEPARATOR);
require_once DISPATCH_PATH . 'dispatch.php';

$savedPath = CACHE_PATH . "xhprofRuns" . DS;

/**
 * AJAX endpoint for XHProf function name typeahead.
 *
 * @author(s)  Kannan Muthukkaruppan
 *             Changhao Jiang
 */

// by default assume that xhprof_html & xhprof_lib directories
// are at the same level.
$GLOBALS['XHPROF_LIB_ROOT'] = LIB_PATH . '/xhprof/xhprof_lib';

include_once $GLOBALS['XHPROF_LIB_ROOT'].'/display/xhprof.php';

$xhprof_runs_impl = new XHProfRuns_Default($savedPath);

include_once $GLOBALS['XHPROF_LIB_ROOT'].'/display/typeahead_common.php';
