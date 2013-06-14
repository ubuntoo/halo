<?php
namespace ctrl;

/**
 * 价格
 * 
 * @author
 */

use service\Snoopy;

class Price extends CtrlBase
{
	/**
	 * 获取物品价格
	 * 
	 * @return
	 */
	public function getPriceList() {
		$keywords = "诺基亚900";
		$priceSv = $this->locator->getService('Price');
		$result = $priceSv->getGoodsDetails($keywords);
		print_r($result);exit;
	}
	
	
	/**
	 * 主函数
	 * 
	 * @return 
	 */
	public function main() {
		$this->getPriceList();
	}
}