<?php
namespace framework\dispatcher;

/**
 * 请求调度接口
 */
interface IDispatcher {
	public function distribute();
}