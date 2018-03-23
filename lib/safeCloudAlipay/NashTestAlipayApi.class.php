<?php
define('SELLER_EMAIL', 'info@aimeizhuyi.com');
define('SUBJECT', '爱美主义');
define('NOTIFY_URL',SITE.'/user/order/notify');
define('CALLBACK_URL',SITE.'/user/order/callback');
define('MERCHANT_URL',SITE.'/user/order/alipayFail');
define('V','2.0');
define('FORMAT','xml');
define('DIRECT_SERVICE','alipay.wap.trade.create.direct');
define('AUTHANDEXECUTE_SERVICE','alipay.wap.auth.authAndExecute');
date_default_timezone_set('Asia/Shanghai');

#require_once(ROOT_PATH."/lib/safeCloudAlipay/alipay.config.php");
require_once(ROOT_PATH."/lib/safeCloudAlipay/lib/alipay_submit.class.php"); 
require_once(ROOT_PATH."/lib/safeCloudAlipay/lib/alipay_notify.class.php");

class NashTestAlipayApi{
    public static $alipay_config = [
        'partner'		=> '2088111840451775',
        'key'			=> 'w144h61n8b1hht2yyb5nyy5iakicgltv',
        'sign_type'    => 'MD5',
        'input_charset'=> 'utf-8',
        'cacert'    => 'lib/safeCloudAlipay/cacert.pem',
        'transport'    => 'http'
    ];
    public static function checkSafeCloud(){
    }
    public static function payTest(){
        //页面跳转同步通知页面路径
        $return_url = CALLBACK_URL;
        //需http://格式的完整路径，不能加?id=123这类自定义参数，不能写成http://localhost/
        //卖家支付宝帐户
        $seller_email = SELLER_EMAIL;
        //请求时间
        $timestamp= '2014-08-12 18:51:27';
        //终端类型
        $terminal_type= 'wap';
        //终端详情
        #$terminal_info='wap info';
        //异步地址
        $notify_url=NOTIFY_URL;
        //订单号
        $order_no='ORDER10000';
        //下单时间
        $order_credate_time='2014-08-12 18:41:27';
        //订单商品所在类目
        $order_category='女装';
        //订单商品名称
        $order_item_name='女式连衣裙';
        //价格
        $order_amount=10000;
        //场景编码
        $scene_code='PAYMENT';
        //买家支付宝账号
        $buyer_account_no='nash';
        //买家注册时间
        $buyer_reg_date='2014-08-13 18:41:27';
        
        /************************************************************/
        
        //构造要请求的参数数组，无需改动
        $parameter = array(
        		"service" => "alipay.security.risk.detect",
        		"partner" => trim(self::$alipay_config['partner']),
        		#"payment_type"	=> $payment_type,
        		#"notify_url"	=> $notify_url,
        		#"return_url"	=> $return_url,
        		#"seller_email"	=> $seller_email,
        		#"timestamp"	=> $timestamp,
        		#"terminal_type"	=> $terminal_type,
        		#"terminal_info"	=> $terminal_info,
        		#"order_no"	=> $order_no,
        		#"order_credate_time"	=> $order_credate_time,
        		#"order_category"	=> $order_category,
        		#"order_item_name"	=> $order_item_name,
        		#"order_amount"=>$order_amount,
                "scene_code"=>$scene_code,
        		"buyer_account_no"=>$buyer_account_no,
        		#"buyer_reg_date"=>$buyer_reg_date,
        		#"_input_charset"	=> trim(strtolower(self::$alipay_config['input_charset']))
        );
        //建立请求
        $alipaySubmit = new AlipaySubmit(self::$alipay_config);
        $html_text = $alipaySubmit->buildRequestForm($parameter,"get", "确认");
        echo $html_text;
    }
}
?>
