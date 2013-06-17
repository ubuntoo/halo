<?php /* Smarty version Smarty-3.0.6, created on 2012-12-23 18:35:06
         compiled from "/data/web/halo/Code/template/html5/systemError.html" */ ?>
<?php /*%%SmartyHeaderCode:48672077150d6de5a52ae54-87530448%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '22e5a358edc7614ae734c152fa32d8443cee6db3' => 
    array (
      0 => '/data/web/halo/Code/template/html5/systemError.html',
      1 => 1356258904,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '48672077150d6de5a52ae54-87530448',
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