<?php
/**
 * socket服务器端
 * 
 * @author
 */
class socketServer
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
    	// 创建一个SOCKET
		if (($this->socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP)) <0 ) {
 	 		die('socket_create() 失败的原因是:' . socket_strerror($this->socket) . '\n');
		}
		// 绑定到socket端口
		if (($ret = socket_bind($this->socket, $address, $port)) <0 ) {
   			die('socket_bind() 失败的原因是:' . socket_strerror($ret) . '\n');
		}
    	// 开始监听
		if (($ret = socket_listen($this->socket, 4)) < 0) {
  			die('socket_listen() 失败的原因是:' . socket_strerror($ret) . '\n');
		}
        return;
    }
    
	/**
     * 接收
     * 
     * @param  mini	$msg 信息
     * 
     * @return
     */
    public function receive() {	
    	while (($msgsock = socket_accept($this->socket)) > 0) {
     		// 发到客户端
     		$msg = "<font color=red>服务器端发送:欢迎进入服务器！</font><br>";
     		socket_write($msgsock, $msg, strlen($msg));
     		$buf = socket_read($msgsock, 8192);
     		if ($buf != '') {
     			echo '客户端' . $_SERVER['REMOTE_ADDR'] . '登陆\r\n:传送信息:'. $buf .'\r\n';
     		}
	 		socket_close($msgsock);
		}
		return ;
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