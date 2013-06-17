<?php /* Smarty version Smarty-3.0.6, created on 2013-06-05 13:48:41
         compiled from "/data/developer/wangwei/halo/Code/template/html5/systemError.html" */ ?>
<?php /*%%SmartyHeaderCode:151828457351aed1394f5fe5-06383481%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '5cf2bd4e4fe86434115533329ec0bb96fd052a10' => 
    array (
      0 => '/data/developer/wangwei/halo/Code/template/html5/systemError.html',
      1 => 1356258904,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '151828457351aed1394f5fe5-06383481',
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