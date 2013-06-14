<?php
namespace service;

/**
 * 获取价格  逻辑 类 
 * 
 * @author
 */
class Price extends ServiceBase
{	
	private static $mallList = array(
		'京东商城' => 'http://www.360buy.com/',
		'淘宝'     => 'http://www.taobao.com/',
		'一淘'     => 'http://buy.etao.com/',
		'淘宝商城' => 'http://www.tmall.com/',
	);
	
	/*
	 * 连接超时时间
	 * 
	 * int
	 */
	public $timeout = 5;
	
	/**
	 * 获取物品价格
	 * 
	 * @param string $keywords 搜索关键字
	 * 
	 * @return
	 */
	public function getGoodsDetails($keywords) 
	{		
		$mallList = self::$mallList;
		foreach ($mallList as $mallName => $host) {
		//	$searchUrl = array();
			switch ($mallName) {//诺基亚900
            	case '京东商城':        		
                	//$searchUrl[$mallName] = 'http://search.360buy.com/Search?keyword=' . $keywords . '&enc=utf-8&area=15'; 
                	$searchUrl = 'http://search.360buy.com/Search?keyword=' . $keywords . '&enc=utf-8&area=15';    	   	
                	break;
            	case '淘宝':
                	$searchUrl = '';
                	break;
            	case '一淘':
                	$searchUrl = '';
                	break;
            	case '淘宝商城':
                	$searchUrl = '';
                	break;
                default:
                	break;
        	}
        	$content = $this->getContents($searchUrl);
print_r($content);exit;
        	$urls = $this->getUrlList($content);
			foreach ($urls as $url) {
				$price = $this->getPrice($url);
				
				print_r($price);exit;
			}
			print_r($urls);exit;
		}
	}
	
	/**
	 * 获取页面的价格
	 * 
	 * @param string $url 页面url地址
	 * 
	 * @param string
	 */
	private function getPrice($url) {
		$content = $this->getContent($url);
		
		print_r($content);exit;
		
		$ch = curl_init(); 
		$timeout = 5;
		curl_setopt($ch, CURLOPT_URL, $url); 
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout); 
		$content = curl_exec($ch); 
	//	$content = iconv('gb2312', 'utf-8', $content);
		curl_close($ch); 
		return $content;
	}
	
	
	/**
	 * 获取页面的内容
	 * 
	 * @param array $urls 页面url地址
	 * 
	 * @param string
	 */
	private function getContents($urls) {
/*
		$urls = array('京东商城'=>'http://search.360buy.com/Search?keyword=诺基亚900&enc=utf-8&area=15');	
		$multiHandle = curl_multi_init(); // 创建多个curl语柄
		$connects = array();
		foreach ($urls as $name => $url) {
			$connects[$name] = curl_init($url);
        	curl_setopt($connects[$name], CURLOPT_TIMEOUT, $this->timeout); // 设置超时时间
        	curl_setopt($connects[$name], CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 5.01; Windows NT 5.0)');
        	curl_setopt($connects[$name], CURLOPT_MAXREDIRS, 7); // HTTP定向级别
			curl_setopt($connects[$name], CURLOPT_HEADER, 0); // 这里不要header，加块效率
			curl_setopt($connects[$name], CURLOPT_FOLLOWLOCATION, 1); // 302redirect
        	curl_setopt($connects[$name], CURLOPT_RETURNTRANSFER, 1);   	
        //	curl_multi_add_handle($multiHandle, $connects[$name]);
    	}
print_r($urls);exit;
	//	$url = 'http://search.360buy.com/Search?keyword=诺基亚900&enc=utf-8&area=15';*/
    	$ch = curl_init(); 
		curl_setopt($ch, CURLOPT_URL, $urls); 
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $this->timeout); 
		$content = curl_exec($ch);
		$content = iconv('gb2312', 'utf-8', $content);
		curl_close($ch); 
print_r($content);exit;
		return $content;
	}
	
	/**
	 * 根据内容获取页面url列表
	 * 
	 * @param string $contents 页面内容
	 * 
	 * @return 
	 */
	private function getUrlList($content) {
		preg_match_all("'<\s*a\s.*?href\s*=\s*([\"\'])?(?(1)(.*?)\\1|([^\s\>]+))[^>]*>?(.*?)</a>'isx", $content, $links); 
		$match = array();
		while(list($key, $val) = each($links[2])) { 
			if (!empty($val)) {
				$match['link'][] = $val; 
			}	
		}
		while(list($key,$val) = each($links[3])) { 
			if (!empty($val)) {
				$match['link'][] = $val; 
			}
		}
		while(list($key,$val) = each($links[4])) { 
			if(!empty($val)) {
				$match['content'][] = $val; 
			}
		}
		while(list($key,$val) = each($links[0])) { 
			if(!empty($val)) {
				$match['all'][] = $val;
			}
		}	
		$urls = $match['link'];
		$result = array();
		foreach ($urls as $url) {
			if (preg_match('/www.360buy.com\/product/', $url)) {
				$result[] = $url;
			}
		}
print_r($result);exit;		
		return $result; 
	}
}