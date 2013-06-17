<?php
namespace service;

/**
 * 数据录入  逻辑 类 
 * 
 * @author
 */
class DataEntry extends ServiceBase
{
	
	/**
	 * 数据录入
	 * 
	 * @return 
	 */
	public function main() {
		$dataEntryDao = $this->locator->getDao('DataEntry');
		$dataEntryEntity = $dataEntryDao->getNewEntity();
		$dataEntryEntity->mallId  = 1;
        $dataEntryEntity->title   = '诺基亚800';     
        $dataEntryDao->create($dataEntryEntity);
		
		print_r($dataEntryEntity);exit;
	}
}