<?php
/**
 * 获取价格
 * 
 * @author
 */
class price
{	
	/**
     * 商城id：京东商城
     * 
     * @var int
     */
    const MALL_ID_360BUY = 1;
    /**
     * 商城id：淘宝
     * 
     * @var int
     */
    const MALL_ID_TAOBAO = 2;
    /**
     * 商城id：一淘
     * 
     * @var int
     */
    const MALL_ID_ETAO = 3;
    /**
     * 商城id：淘宝商城
     * 
     * @var int
     */
    const MALL_ID_TMALL = 4;
    
	/**
	 * 商城搜索链接
	 * 
	 * @var array
	 */
	private static $searchUrlList = array(
		self::MALL_ID_360BUY => 'http://search.360buy.com/Search?keyword=[KEYWORDS]&enc=utf-8&area=15',
		self::MALL_ID_TAOBAO => '',
		self::MALL_ID_ETAO   => '',
		self::MALL_ID_TMALL  => '',
	);
	
	/**
	 * 商城域名列表
	 * 
	 * @var array
	 */
	private static $mallList = array(
		self::MALL_ID_360BUY => 'http://www.360buy.com/',
		self::MALL_ID_TAOBAO => 'http://www.taobao.com/',
		self::MALL_ID_ETAO   => 'http://buy.etao.com/',
		self::MALL_ID_TMALL  => 'http://www.tmall.com/',
	);
	
	/**
	 * 商城url匹配-正则表达式
	 * 
	 * @var array
	 */
	private static $urlRegularList = array(
		self::MALL_ID_360BUY => "!href=\"(http:\/\/www.360buy.com\/product\/\d+\.html)\"!",
		self::MALL_ID_TAOBAO => '',
		self::MALL_ID_ETAO   => '',
		self::MALL_ID_TMALL  => '',
	);
	
	/**
	 * 商品详情匹配-正则表达式
	 * 
	 * @var array
	 */
	private static $goodsRegularList = array(
		self::MALL_ID_360BUY => array(
			'priceLink'   => '', // 价格链接
			'name'        => '', // 商品名
			'grade'       => '', // 评分
			'introduce'   => '', // 介绍
		),
		self::MALL_ID_TAOBAO => array(
			'priceLink'   => '', // 价格链接
			'name'        => '', // 商品名
			'grade'       => '', // 评分
			'introduce'   => '', // 介绍
		),
		self::MALL_ID_ETAO   => array(
			'priceLink'   => '', // 价格链接
			'name'        => '', // 商品名
			'grade'       => '', // 评分
			'introduce'   => '', // 介绍
		),
		self::MALL_ID_TMALL  => array(
			'priceLink'   => '', // 价格链接
			'name'        => '', // 商品名
			'grade'       => '', // 评分
			'introduce'   => '', // 介绍
		),
	);
	
	/**
	 * 内容列表
	 * 
	 * @var array
	 */
	private $contentList = array();
	/**
	 * header列表
	 * 
	 * @var array
	 */
	private $headerList = array();
	/**
	 * 页面url列表
	 * 
	 * @var array
	 */
	private $urlList = array();
	/**
	 * 连接超时时间
	 * 
	 * int
	 */
	public $timeout = 3000;
	
	/**
	 * 获取物品详情
	 * 
	 * @param string $keywords 搜索关键字
	 * 
	 * 
	 * @return array
	 */
	public function getGoodsDetails($keywords)  //诺基亚900
	{			
		$mallList = self::$mallList;
		$searchUrls = array();
		foreach ($mallList as $mallId => $host) {
			switch ($mallId) {
            	case self::MALL_ID_360BUY:
            		
            		break;
            	case self::MALL_ID_TAOBAO: 
                	
                	break;
            	case self::MALL_ID_ETAO: 
                	
                	break;
            	case self::MALL_ID_TMALL: 
                	
                	break;
                default:
                	break;
        	}
        	$searchUrls[$mallId][] = str_replace('[KEYWORDS]', $keywords, self::$searchUrlList[$mallId]);
		}
			
		$contentList = $this->getContents($searchUrls); // 根据搜索url获取页面内容
		$urlList = $this->getUrls($contentList); // 根据页面内容获取页面的url列表
		$contentList = $this->getContents($urlList); // 根据url获取内容列表
print_r($contentList);exit;
        $goodsDetails = $this->getDetails($contentList); // 获取物品详情
        return $goodsDetails;
	}
	
	/**
	 * 获取内容获取的商品详情
	 * 
	 * @param array $contents 页面列表 
	 * 
	 * @return array
	 */
	private function getDetails($contents) {
		foreach ($contents as $id => $content) {
		  	if (preg_match_all(self::$urlRegularList[$id], $content, $matches)) {
        		$urlList[$name] = $matches['1'];
        	}
    	}
		return $goodsDetails;
	}
	
	/**
	 * 获取页面的内容
	 * 
	 * @param array $urls 页面url地址列表
	 * 
	 * @param array
	 */
	private function getContents($urls) {
		$multiHandle = curl_multi_init(); // 创建多个curl语柄
		$connects = array();
		foreach ($urls as $id => $urlList) {
			if ($urlList && is_array($urlList)) foreach ($urlList as $key => $url) {
				if (empty($url)) continue;			
				$connects[$id][$key] = curl_init($url);
        		curl_setopt($connects[$id][$key], CURLOPT_TIMEOUT, $this->timeout); // 设置超时时间
        		curl_setopt($connects[$id][$key], CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 5.01; Windows NT 5.0)');
        		curl_setopt($connects[$id][$key], CURLOPT_MAXREDIRS, 7); // HTTP定向级别
				curl_setopt($connects[$id][$key], CURLOPT_HEADER, 0); // 这里不要header，加块效率
				curl_setopt($connects[$id][$key], CURLOPT_FOLLOWLOCATION, 1); // 302redirect
        		curl_setopt($connects[$id][$key], CURLOPT_RETURNTRANSFER, 1);   	
        		curl_multi_add_handle($multiHandle, $connects[$id][$key]);	
			}			
    	}	
    	do {
            $mrc = curl_multi_exec($multiHandle, $active); // 当无数据，active=true
        } while ($mrc == CURLM_CALL_MULTI_PERFORM); // 当正在接受数据时
        while ($active && $mrc == CURLM_OK) { // 当无数据时或请求暂停时，active=true
            if (curl_multi_select($multiHandle) != -1) {
                do {
                    $mrc = curl_multi_exec($multiHandle, $active);
                } while ($mrc == CURLM_CALL_MULTI_PERFORM);
            }
        }
        $contents = array();
        foreach ($urls as $id => $urlList) {
			if ($urlList && is_array($urlList)) foreach ($urlList as $key => $url) {
				if (empty($url)) continue;			
				curl_error($connects[$id][$key]);
            	$contents[$id][$key] = curl_multi_getcontent($connects[$id][$key]); // 获得返回信息
            	$this->headerList[$id][$key] = curl_getinfo($connects[$id][$key]); // 返回头信息
          		curl_close($connects[$id][$key]); // 关闭句柄
          		curl_multi_remove_handle($multiHandle, $connects[$id][$key]); // 释放资源 	
			}			
    	}	    	
        $this->contentList += $contents;
        return $contents;
	}
	
	/**
	 * 根据内容获取页面匹配的url列表
	 * 
	 * @param array $contents 页面列表
	 * 
	 * @return array
	 */
	private function getUrls($contents) {
		$urlList = array();
		foreach ($contents as $id => $contentList) {
			$tmpUrls = array();
			foreach ($contentList as $key => $content) {
				if (preg_match_all(self::$urlRegularList[$id], $content, $matches)) {
        			$tmpUrls = array_merge($tmpUrls, array_unique($matches['1']));
        		}
			}
			$urlList[$id] = $tmpUrls;
		}
    	$this->urlList += $urlList;
    	return $urlList;
	}
	
}