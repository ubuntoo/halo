<?php
namespace service;

/**
 * 客户端工具
 * 
 * 获取客户端IP、操作系统、浏览器，以及HTTP操作等功能
 * 
 * @author 
 */
class Client
{		
    /**
     * 跳转网址
     * 
     * @param	string	$url	url地址
     * @param   int		$mode   模式
     * 
     * @return
     */
    public static function redirect($url, $mode = 302)
    {
        header("Location: " . $url, $mode);
        header("Connection: close");
        exit;
    }
    
    /**
     * 发送下载声明
     * 
     * @param   string  $mime   文件类型
     * @param   string	$mode	 文件名
     * 
     * @return 
     */
    public static function download($mime, $filename)
    {
        header("Content-type: $mime");
        header("Content-Disposition: attachment; filename=$filename");
    }
    
    /**
     * 获取客户端IP
     * 
     * @return
     */
    public static function getIP()
    {
        if (getenv("HTTP_CLIENT_IP") && strcasecmp(getenv("HTTP_CLIENT_IP"), "unknown")) {
            $ip = getenv("HTTP_CLIENT_IP");
        } elseif (getenv("HTTP_X_FORWARDED_FOR") && strcasecmp(getenv("HTTP_X_FORWARDED_FOR"), "unknown")) {
            $ip = getenv("HTTP_X_FORWARDED_FOR");    
        } elseif (getenv("REMOTE_ADDR") && strcasecmp(getenv("REMOTE_ADDR"), "unknown")) {
            $ip = getenv("REMOTE_ADDR");
        } elseif (isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], "unknown")) {
            $ip = $_SERVER['REMOTE_ADDR'];
        } else {
            $ip = "unknown";    
        }
        return($ip);
    }
    
   /**
     * 获取请求方法
     * 
     * @return string
     */
    public static function request_method()
    {
        return $_SERVER['REQUEST_METHOD'];
    }
    
    /**
     * 获取客户端浏览器信息
     * 
     * @return
     */
    public static function getBrowser()
    {
        if ($browser = self::matchbrowser($_SERVER["HTTP_USER_AGENT"], "|(myie[^;^)^(]*)|i" ));
        elseif ($browser = self::matchbrowser($_SERVER["HTTP_USER_AGENT"], "|(Netscape[^;^)^(]*)|i" ));
        elseif ($browser = self::matchbrowser($_SERVER["HTTP_USER_AGENT"], "|(Opera[^;^)^(]*)|i" ));
        elseif ($browser = self::matchbrowser($_SERVER["HTTP_USER_AGENT"], "|(NetCaptor[^;^^()]*)|i" ));
        elseif ($browser = self::matchbrowser($_SERVER["HTTP_USER_AGENT"], "|(TencentTraveler)|i" ) );
        elseif ($browser = self::matchbrowser($_SERVER["HTTP_USER_AGENT"], "|(Firefox[0-9/\.^)^(]*)|i" ));
        elseif ($browser = self::matchbrowser($_SERVER["HTTP_USER_AGENT"], "|(MSN[^;^)^(]*)|i" ));
        elseif ($browser = self::matchbrowser($_SERVER["HTTP_USER_AGENT"], "|(Lynx[^;^)^(]*)|i" ));
        elseif ($browser = self::matchbrowser($_SERVER["HTTP_USER_AGENT"], "|(Konqueror[^;^)^(]*)|i" ));
        elseif ($browser = self::matchbrowser($_SERVER["HTTP_USER_AGENT"], "|(WebTV[^;^)^(]*)|i" ));
        elseif ($browser = self::matchbrowser($_SERVER["HTTP_USER_AGENT"], "|(msie[^;^)^(]*)|i" ));
        elseif ($browser = self::matchbrowser($_SERVER["HTTP_USER_AGENT"], "|(Maxthon[^;^)^(]*)|i" ));
        else $browser = "unknown";
        return $browser;
    }
    
    /**
     * 获取客户端操作系统信息
     * 
     * @return stringh
     */
    public static function getOS()
    {
        $os = "";
        $agent = $_SERVER["HTTP_USER_AGENT"];
        if (eregi('win', $agent) && strpos($agent, '95')) {
            $os = "Windows 95";
        } elseif (eregi('win 9x', $agent) && strpos($agent, '4.90')) {
            $os = "Windows ME";
        } elseif (eregi('win', $agent) && ereg('98', $agent)) {
        	$os = "Windows 98";
        } elseif (eregi('win', $agent) && eregi('nt 5.0', $agent)) {
            $os = "Windows 2000";
        } elseif (eregi('win', $agent) && eregi('nt 5.1', $agent)) {
        	$os = "Windows XP";
        } elseif (eregi('win', $agent) && eregi('nt 5.2', $agent)) {
        	$os = "Windows 2003";
        } elseif (eregi('win', $agent) && eregi('nt', $agent)) {
        	$os = "Windows NT";
        } elseif (eregi('win',$agent) && ereg('32', $agent)) {
        	$os = "Windows 32";
        } elseif (eregi('linux', $agent)) {
        	$os = "Linux";
        } elseif (eregi('unix', $agent)) {
        	$os = "Unix";
        } elseif (eregi('sun', $agent) && eregi('os', $agent)) {
        	$os = "SunOS";
        } elseif (eregi('ibm', $agent) && eregi('os', $agent)) {
        	$os = "IBM OS/2";
        } elseif (eregi('Mac', $agent) && eregi('PC', $agent)) {
        	$os = "Macintosh";
        } elseif (eregi('PowerPC', $agent)) {
        	$os = "PowerPC";
        } elseif (eregi('AIX', $agent)) {
        	$os = "AIX";
        } elseif (eregi('HPUX', $agent)) {
        	$os = "HPUX";
        } elseif(eregi('NetBSD',$agent)) {
        	$os = "NetBSD";
        } elseif (eregi('BSD', $agent)) {
        	$os = "BSD";
        } elseif (ereg('OSF1', $agent)) {
        	$os = "OSF1";
        } elseif (ereg('IRIX', $agent)) {
        	$os = "IRIX";
        } elseif (eregi('FreeBSD', $agent)) {
        	$os = "FreeBSD";
        } elseif ($os == '') {$os = "Unknown";}
        return $os;
    }
    
    /**
     * 信息匹配
     * 
     * @param  string	$agent      信息
     * @param  string	$patten     匹配符
     * 
     * @return string
     */
    private static function matchbrowser($agent, $patten)
    {
        if (preg_match($patten, $agent, $item )) {
            return array_shift($item);
        }
        return false;
    }
}