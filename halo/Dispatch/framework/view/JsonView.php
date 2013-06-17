<?php
namespace framework\view;

/**
 * Json视图
 */
class JsonView extends ViewBase {
	const TYPENAME = 'json';
	private $cout = array();
    private $bout = array();
    
    private $argument = null;

	public  function __construct( $args = array()) {	
		$this->model = $model;
		//$this->cout = &Framework::$cout;
        //$this->bout = &Framework::$broadcast;
	}
	
	public function display() {
    	if ($this->argument) { // 用户已经赋值
    		return json_encode($arg);
    	}
		return json_encode($this->model);
        
        if (!empty($this->cout)) {
            return json_encode($this->cout);
        }
        $this->broadcast();
	}
	
	public function __call($mathod, $argument) {
		$this->argument = $argument;
		if ($mathod=='display') {
			$this->display();	
		}
	}
	
	private function broadcast()
    {
        if (!empty($this->bout) && is_iteratable($this->bout)) {
            foreach ($this->bout as $b) {
                echo "\0".json_encode($b);
            }
        }
    }
	
}