<?php
/**
 * socket客户端
 * 
 * @author
 */
class socketClient
{
	private $socket; // socket 实例
	
	/**
     * 构造函数
     * 
     * @param	string	$address  	地址
     * @param	int		$port  		端口
     * 
     * @return void
     */
    public function __construct($address, $port)
    {
    	$this->socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);    	
    	if ($this->socket < 0) {
   			die('socket_create() failed: reason:' . socket_strerror($this->socket) . '\n');
		}
$address = "h4.qjp.g.1360.com";
$port = 443;
		$result = socket_connect($this->socket, $address, $port);		

		if ($result < 0) {
    		die('socket_connect() failed.\nReason: ($result) ' . socket_strerror($result) . '\n');
   		}

   		fputs($fp, "POST /main.php ? param=1\r\n"); #请求的资源 URL 一定要写对
   		$this->send("dasfd");
   	$this->receive();	
   		exit;
    }
    
    /**
     * 发送
     * 
     * @param  mini	$msg 信息
     * 
     * @return
     */
    public function send($msg) {	
    	if (!socket_write($this->socket, $msg, strlen($msg))) {
    		echo 'socket_write() failed: reason: ' . socket_strerror($this->socket) . '\n';
   		} else {
    		echo 'Sucess\n';
   		}
   		return ;
    }
    
	/**
     * 接收
     * 
     * @param  mini	$msg 信息
     * 
     * @return
     */
    public function receive() {	
    	while ($out = socket_read($this->socket, 443)) {
    		echo "Server say:<br>";
    		echo $out;
   		}
   		print_r($out);exit;
   		return $out;
    }
    
	/**
     * 析构函数函数
     *
     * @return void
     */
    public function __destruct()
    {
    	socket_close($this->socket);
    	return;
    }
}