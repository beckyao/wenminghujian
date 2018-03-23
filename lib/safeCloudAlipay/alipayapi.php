<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<title>支付宝即时到账交易接口接口</title>
</head>
<?php
/* *
 * 功能：即时到账交易接口接入页
 * 版本：3.3
 * 修改日期：2012-07-23
 * 说明：
 * 以下代码只是为了方便商户测试而提供的样例代码，商户可以根据自己网站的需要，按照技术文档编写,并非一定要使用该代码。
 * 该代码仅供学习和研究支付宝接口使用，只是提供一个参考。

 *************************注意*************************
 * 如果您在接口集成过程中遇到问题，可以按照下面的途径来解决
 * 1、商户服务中心（https://b.alipay.com/support/helperApply.htm?action=consultationApply），提交申请集成协助，我们会有专业的技术工程师主动联系您协助解决
 * 2、商户帮助中心（http://help.alipay.com/support/232511-16307/0-16307.htm?sh=Y&info_type=9）
 * 3、支付宝论坛（http://club.alipay.com/read-htm-tid-8681712.html）
 * 如果不想使用扩展功能请把扩展功能参数赋空值。
 */

require_once("alipay.config.php");
require_once("lib/alipay_submit.class.php");

/**************************请求参数**************************/

      
        //页面跳转同步通知页面路径
        $return_url = "http://127.0.0.1:8080/create_direct_pay_by_user-PHP-UTF-8/return_url.php";
        //需http://格式的完整路径，不能加?id=123这类自定义参数，不能写成http://localhost/
        //卖家支付宝帐户
        $seller_email = $_POST['WIDseller_email'];
         //请求时间
		 $timestamp=$_POST['WIDtimestamp'];
		 //终端类型
		 $terminal_type=$_POST['WIDterminal_type'];
		 //终端详情
		 $terminal_info=$_POST['WIDterminal_info'];
		 //异步地址
		 $notify_url="http://10.13.63.172:8080/alipay.security.risk.detect/notify_url.php";
		 //订单号
		 $order_no=$_POST['WIDorder_no'];
		 //下单时间
		 $order_credate_time=$_POST['WIDorder_credate_time'];
		 //订单商品所在类目
		 $order_category=$_POST['WIDorder_category'];
		 //订单商品名称
		 $order_item_name=$_POST['WIDorder_item_name'];
		 //价格
		 $order_amount=$_POST['WIDorder_amount'];
		 //场景编码
		 $scene_code=$_POST['WIDscene_code'];
		 //买家支付宝账号
		 $buyer_account_no=$_POST['WIDbuyer_account_no'];
		 //买家注册时间
		 $buyer_reg_date=$_POST['WIDbuyer_reg_date'];
        


/************************************************************/

//构造要请求的参数数组，无需改动
$parameter = array(
		"service" => "alipay.security.risk.detect",
		"partner" => trim($alipay_config['partner']),
		"payment_type"	=> $payment_type,
		"notify_url"	=> $notify_url,
		"return_url"	=> $return_url,
		"seller_email"	=> $seller_email,
		"timestamp"	=> $timestamp,
		"terminal_type"	=> $terminal_type,
		"terminal_info"	=> $terminal_info,
		"order_no"	=> $order_no,
		"order_credate_time"	=> $order_credate_time,
		"order_category"	=> $order_category,
		"order_item_name"	=> $order_item_name,
		"order_amount"=>$order_amount,
        "scene_code"=>$scene_code,
		"buyer_account_no"=>$buyer_account_no,
		"buyer_reg_date"=>$buyer_reg_date,
		"_input_charset"	=> trim(strtolower($alipay_config['input_charset']))
);

//建立请求
$alipaySubmit = new AlipaySubmit($alipay_config);
$html_text = $alipaySubmit->buildRequestForm($parameter,"get", "确认");
echo $html_text;

?>
</body>
</html>