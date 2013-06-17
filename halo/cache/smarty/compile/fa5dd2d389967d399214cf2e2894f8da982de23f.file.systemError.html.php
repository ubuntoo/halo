<?php /* Smarty version Smarty-3.0.6, created on 2013-01-07 13:23:28
         compiled from "/data/developer/wangwei/space/web/halo/Code/template/html5/systemError.html" */ ?>
<?php /*%%SmartyHeaderCode:125102971850ea5bd06eac93-60967957%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'fa5dd2d389967d399214cf2e2894f8da982de23f' => 
    array (
      0 => '/data/developer/wangwei/space/web/halo/Code/template/html5/systemError.html',
      1 => 1356258904,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '125102971850ea5bd06eac93-60967957',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
)); /*/%%SmartyHeaderCode%%*/?>
<html>
<head>
<title>The page is temporarily unavailable</title>
<style>
body { font-family: Tahoma, Verdana, Arial, sans-serif; }
</style>
</head>
<body bgcolor="white" text="black">
<table width="100%" height="50%">
<tr>
<td align="center" valign="middle">
<strong><?php echo $_smarty_tpl->getVariable('act')->value;?>
</strong>
<strong><?php echo $_smarty_tpl->getVariable('errstr')->value;?>
</strong>
</td>
</tr>
</table>
</body>
</html>