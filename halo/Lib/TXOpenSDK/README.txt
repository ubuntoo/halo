
====================当前版本信息====================
当前版本：V3.0.4

发布日期：2012-09-07

文件大小：11.5 K 


====================修改历史====================
V3.0.4  2012-09-07,支持部署在CEE_V2上的应用调用支付类OpenAPI。
                   支持以multipart/form-data格式发post请求的接口，例如上传文件类OpenAPI。

V3.0.3  2012-08-28,支持发货回调的签名验证，并修正了当参数中含有“~”符号时会签名验证会失败的bug。

V3.0.2  2012-03-06,添加接口统计上报功能，开发者使用本SDK以后，腾讯后台服务器可以统计OpenAPI各接口的访问延时、访问量等信息，用于后续的信息挖掘和OpenAPI优化。

V3.0.1  2012-02-14,修复了一个小bug，在OpenApiV3.php文件的第108行，将参数值'post'改为变量$method. 

V3.0.0  2011-12-14, 腾讯开放平台V3版OpenAPI的PHP SDK第一版发布，3.0表示OpenAPI版本，后一位0表示SDK版本。
        本SDK基于V3版OpenAPI，适用于腾讯开放平台上所有应用接入时使用：
        -V3版OpenAPI是老OpenAPI的升级版，支持全平台统一接入，即对于同一功能（例如获取用户信息），第三方应用不再需要根据不同的平台调用不同的接口。
        -V3版OpenAPI采用新的接入协议，请求中必须包含签名值，更加安全。
        -V3版OpenAPI在参数和返回值上尽量和老版本OpenAPI接口兼容，开发者如果想升级到新版本OpenAPI，代码改造工作量较小。
        -开发者可以自由选择是否升级到OpenAPI V3.0。由于OpenAPI V3.0的上述优点，以及后续新开放的接口都将采用OpenAPI V3.0的协议，我们推荐开发者进行升级。与此同时，老版本的OpenAPI将继续提供技术支持直至2012年06月30日。
        


====================文件结构信息====================
lib文件夹：        
	SnsNetwork.php：发送HTTP网络请求类
        SnsSigCheck.php：请求参数签名生成类
	SnsStat.php: 统计上报类
OpenApiV3.php：OpenAPI访问类
	
Test_OpenApiV3.php： 示例代码
Test_UploadFile.php：上传文件类接口示例代码。适用于所有需要发送multipart/form-data格式的post请求的OpenAPI。


本SDk示例代码中并没有列出所有的OpenAPI，腾讯开放平台V3版OpenAPI正在不断增加中，详见API列表：
http://wiki.open.qq.com/wiki/API3.0%E6%96%87%E6%A1%A3


====================联系我们====================
腾讯开放平台官网：http://open.qq.com/
您可以访问我们的资料库获得详尽的技术文档：http://wiki.open.qq.com/wiki/%E9%A6%96%E9%A1%B5
您可以使用联调工具集来进行OpenAPI的联调和sig验证：http://open.qq.com/tools
此外，你也可以通过企业QQ（号码：800013811；直接在QQ的“查找联系人”中输入号码即可开始对话）咨询。

