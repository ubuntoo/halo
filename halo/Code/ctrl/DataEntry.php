<?php
namespace ctrl;

/**
 * 数据录入接口
 * 
 * @author
 */
class DataEntry extends CtrlBase
{	
	/**
	 * 主函数
	 * 
	 * @return 
	 */
	public function main() {
//		makeClass('GoodsOfficial', 'kkk');
		createEntity('mall_goods', 'Goods');
		createEntity('mall_seller', 'Seller');

		exit;
		$dataEntrySv = $this->locator->getService('DataEntry');
		$dataEntrySv->main();

	}
	
	
}