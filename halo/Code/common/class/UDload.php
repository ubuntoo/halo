<?php
namespace ctrl;

/**
 * 上传 && 下载类的封装
 * 
 * @author
 */
class UDload extends CtrlBase
{
	private static $savedPath;  // 文件保存路径
	private static $shieldword = array('毛片', 'A片'); // 文件名屏蔽字
	
	/**
	 * 文件上传大小限制 （MB）
	 * 
	 * @var int
	 */
	const FILE_UPLOAD_SIZE_LIMIT = 100;
	
	/**
     * 上传总量大小限制 （GB）
     * 
     * @var int
     */
    const UPLOAD_TOTALS_SIZE_LIMIT = 2;
	
    /**
     * 构造方法
     * 
     * @return void
     */
    public function __construct()
    {
        self::$savedPath = CACHE_PATH . "upload";
        if (!is_readable(self::$savedPath)) {  
            mkdir(self::$savedPath, 0777);  
        }
    	self::$savedPath .= DS;
    	parent::__construct();
    	return ;
    }

    /**
     * 上传
     * 
     * @param 
     * 
     * @return void
     */
    public function upload() 
    {
//$this->download(1,1,1);exit;     
    	if (empty($_FILES)) {
    	   $return['error'] = $this->message->send('#7');
    	}
    	$fileName = $_FILES['file']['name'];
    	$postfix  = strrchr($fileName, '.');
    	$name     = substr($fileName, 0, -strlen($postfix));
    	$nameInfo = $this->checkName($name);
    	$type     = $_FILES['file']['type'];
        $typeInfo = $this->checkType(ltrim($postfix, '.'));      
        $size     = $_FILES['file']['size'];      
        // 检查目录总量
        if ((getDirSize(self::$savedPath) + $size) > self::UPLOAD_TOTALS_SIZE_LIMIT * 1073741824) {
        	$this->message->send('#11', array('MAX' => self::UPLOAD_TOTALS_SIZE_LIMIT));     
        }
        
        $sizeInfo = $this->checkSize($size);
        $tmp_name = $_FILES['file']['tmp_name'];
        $error    = $_FILES['file']['error'];
        if ($nameInfo['ok'] && $typeInfo['ok'] && $sizeInfo['ok']) {
        	if (move_uploaded_file($tmp_name, self::$savedPath . $fileName)) {
                $this->message->send('#8');
            } else {
                $this->message->send('#9');
            }    
        } else {
        	$info = null;
        	if (isset($nameInfo['error'])) {
        		$info .= 'fileName:' . $nameInfo['error'].'\n';
        	}
            if (isset($typeInfo['error'])) {
                $info .= 'type:' . $typeInfo['error'].'\n';
            }
            if (isset($sizeInfo['error'])) {
                $info .= 'size:' . $sizeInfo['error'].'\n';
            }
            $this->message->send('#10', array('INFO' => $info));
        }
        return ;
    }
    
   /**
     * 下载
     * 
     * 
     * 
Array
(
    [name] => {0D7E0B58-8533-4C5E-9F97-E5C9174CDDA8}
    [type] => gif
    [mime] => image/gif
    [size] => 7320
    [time] => 1344401971
    [last] => 1344416992
    [down] => 1
    [info] => none
    [ip] => 127.0.0.1
    [id] => QM8F777
    [pw] => 516244
)
     * 
     * 
     * 
     * @param  $fileInfo        string      文件信息
     * @param  $speed           int         下载速度
     * @param  $disposition     bool        是否在线预览
     * 
     * @return void
     */
    public function download($fileInfo, $speed, $disposition = false)
    {
$fileInfo = '{0F5F8406-C2BD-424F-B61A-8C0CC7C85E26}.jpg';    	
    	// 判断文件是否存在
    	if (!(is_string($fileInfo) && file_exists(self::$savedPath . $fileInfo))) {
    	    echo "文件不存在";
    	}
        $isHttpRange = false;  // 断点续传
        if (isset($_SERVER['HTTP_RANGE']) && ($_SERVER['HTTP_RANGE'] != '')) {
            $isHttpRange = true;
        }   
        @set_time_limit(86400);
        header('Content-type: application/octet-stream');
        header('Accept-Ranges: bytes');
        header('Pragma: no-cache');
        header('Cache-Control: max-age=0');
        header('Expires: -1');
        $filepath = self::$savedPath . $fileInfo; 
        $seeName = iconv("UTF-8", "GBK", $fileInfo); // 下载时所看到的文件名
        if ($disposition) {
            header('Content-Disposition: inline; filename="' . $seeName . '"');
        } else {
            header('Content-Disposition: attachment; filename="' . $seeName . '"');
        }
        $fileSize = filesize($filepath);
        $file = @fopen($filepath, 'rb');
        if ($isHttpRange) { // 断点
            $lowerHTTPRange = str_replace('bytes=', '', trim(strtolower($_SERVER['HTTP_RANGE'])));
            list($HTTPRangeMin, $HTTPRangeMax) = explode('-', $lowerHTTPRange);
            if($HTTPRangeMin == 0) {
                $isHttpRange = false; // 从断点0开始
            } else {
                fseek($file, $HTTPRangeMin);
                header('Content-Length: ' . ($fileSize - 1 - $HTTPRangeMin));
                header('Content-Range: bytes ' . $HTTPRangeMin . '-' . ($fileSize - 1) . '/' . $fileSize);
                header('HTTP/1.1 206 Partial Content');
            }
        }
        if (false === $isHttpRange) { // 没断点
            // 下载计数
            header('Content-Range: bytes 0-' . ($fileSize - 1) . '/' . $fileSize);
            header("Content-Length: " . $fileSize);
        }
        // 开始下载
        while (!feof($file)) {
            // 有速度限制
            $speed *= 1024;
            if (connection_aborted()) {
                exit();
            } else {
                if ($speed == '0') {
                    echo fread($file, 8192);
                    ob_flush();
                    flush();
                } else {
                    if ($speed <= 8192) {
                        echo fread($file, $speed);
                    } else {
                        $echoSize = 0;
                        for(; $echoSize < $speed;) {
                            if (($speed - $echoSize) > 8192) {
                                echo fread($file,8192);
                                $echoSize += 8192;
                            } else {
                                echo fread($file, $speed - $echoSize);
                                $echoSize += $speed - $echoSize;
                            }
                        }
                    }
                    ob_flush();
                    flush();
                    sleep(1);
                }
            }
        }
        @fclose($file);
        return ;
    }
    
    /**
     * 检测文件名
     * 
     * @param $fileName string 文件名
     * 
     * @param  array
     */
    private function checkName($fileName) 
    {    
        $return = null; 
        if (in_array($fileName, self::$shieldword)) {
            $return['error'] = $this->message->send('#4f');
            $return['ok'] = false;
        } else {
            $return['ok'] = true;
        }
        return $return;
    }
    
   /**
     * 检测文件类型
     * 
     * @param $type string 文件后缀名
     * 
     * @param  string
     */
    private function checkType($type) 
    {
    	switch ($type) {
	        case 'avi':
	            $mime = 'video/x-msvideo';
	            break;
	        case 'bmp':
	            $mime = 'image/bmp';
	            break;
	        case 'css':
	            $mime = 'text/css';
	            break;
	        case 'dll':
	            $mime = 'application/x-msdownload';
	            break;
	        case 'doc':
	            $mime = 'application/msword';
	            break;
	        case 'dot':
	            $mime = 'application/msword';
	            break;
	        case 'gif':
	            $mime = 'image/gif';
	            break;
	        case 'gz':
	            $mime = 'application/x-gzip';
	            break;
	        case 'htm':
	            $mime = 'text/html';
	            break;
	        case 'html':
	            $mime = 'text/html';
	            break;
	        case 'ico':
	            $mime = 'image/x-icon';
	            break;
	        case 'jpeg':
	            $mime = 'image/jpeg';
	            break;
	        case 'jpg':
	            $mime = 'image/jpeg';
	            break;
	        case 'gif':
	            $mime = 'image/gif';
	            break;
	        case 'png':
	            $mime = 'image/png';
	            break;
	        case 'js':
	            $mime = 'application/x-javascript';
	            break;
	        case 'mdb':
	            $mime = 'application/x-msaccess';
	            break;
	        case 'mid':
	            $mime = 'audio/mid';
	            break;
	        case 'mp3':
	            $mime = 'audio/mpeg';
	            break;
	        case 'mpeg':
	            $mime = 'video/mpeg';
	            break;
	        case 'mvb':
	            $mime = 'application/x-msmediaview';
	            break;
	        case 'pps':
	            $mime = 'application/vnd.ms-powerpoint';
	            break;
	        case 'ppt':
	            $mime = 'application/vnd.ms-powerpoint';
	            break;
	        case 'txt':
	            $mime = 'text/plain';
	            break;
	        case 'wav':
	            $mime = 'audio/x-wav';
	            break;
	        case 'xls':
	            $mime = 'application/vnd.ms-excel';
	            break;
	        case 'zip':
	            $mime = 'application/zip';
	            break;
	        case 'rar':
	            $mime = 'application/x-rar-compressed';
	            break;
	        case 'swf':
	            $mime = 'application/x-shockwave-flash';
	            break;
	        case '7z':
	            $mime = 'application/x-7z-compressed';
	            break;
	        default:
	            $mime = 'application/object-stream';
	            break;
	    }
        return $mime;
    }
    
   /**
     * 检测文件大小
     * 
     * @param  $size int 文件大小
     * 
     * @param  array
     */
    private function checkSize($size) 
    {	
        if ($size > self::FILE_UPLOAD_SIZE_LIMIT * 1048576) {
            $return['error'] = $this->message->send('#5f', array('MAX' => self::FILE_UPLOAD_SIZE_LIMIT));
            $return['ok'] = false;
        } elseif(empty($size)) {
            $return['error'] = $this->message->send('#6f');
            $return['ok'] = false;
        } else {
            $return['ok'] = true; 
        }  
        return $return;
    }
    
}