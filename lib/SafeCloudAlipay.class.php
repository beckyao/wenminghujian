<?php
define('SAFE_CLOUD_SCENE_CODE', 'PAYMENT');
define('SAFE_CLOUD_SERVICE', 'alipay.security.risk.detect');
define('SAFE_CLOUD_CACERT', ROOT_PATH.'/lib/safeCloudAlipay/cacert.pem');
define('SAFE_CLOUD_ALIPAY_GATEWAY', 'https://mapi.alipay.com/gateway.do?');
require_once(ROOT_PATH."/lib/alipay/lib/alipay_submit.class.php"); 

class SafeCloudAlipay{
    public static $alipay_config = [
        'partner'		=> '2088111840451775',
        'key'			=> 'w144h61n8b1hht2yyb5nyy5iakicgltv',
        'sign_type'    => 'MD5',
        'input_charset'=> 'utf-8',
        'cacert'    => SAFE_CLOUD_CACERT,
        'transport'    => 'http'
    ];

    public static function checkSafeCloud($userInfo, $payment){
        # 买家信息
        $buyer_account_no = $userInfo->mName;
        $buyer_reg_mobile = $userInfo->mPhone;
        # 订单信息
        $order_no = GlobalMethod::genOrderId($payment->mId);
        $order_credate_time = date('Y-m-d H:i:s',$payment->mCreateTime);
        $order_amount = $payment->mAmount; 
        $order_item_name = "";
        $order=new Order();
        $order=$order->addWhere("id",$payment->mOrderId)->select();
        if($order){
            $stock=new Stock();
            $stock=$stock->addWhere("id",$order->mStockId)->select();
            if($stock){
                $order_item_name = $stock->mName;
            }
        }
        $order_category = "海外^专柜^服饰";
        $parameter = array(
                "service" => SAFE_CLOUD_SERVICE,
                "partner" => trim(self::$alipay_config['partner']),
                "scene_code"=> SAFE_CLOUD_SCENE_CODE,
                "buyer_account_no"=>$buyer_account_no,
                "buyer_reg_mobile"=>$buyer_reg_mobile,
                "order_no"=>$order_no,
                "order_credate_time"=>$order_credate_time,
                "order_amount"=>$order_amount,
                "order_item_name"=>$order_item_name,
                "order_category"=>$order_category,
        );
        $alipaySubmit = new AlipaySubmit(self::$alipay_config, SAFE_CLOUD_ALIPAY_GATEWAY);
        $res = $alipaySubmit->buildRequestHttp($parameter);
        #var_dump($parameter);
        #var_dump($res);
        Logger::info("SafeCloudAlipay get:\t".$res);
        return self::isSuccess($res);
    }
    
    public static function isSuccess($res){
        if(!$res)   return false;
        $xml = simplexml_load_string($res);
        if(strval($xml->is_success) == 'T') return true;
        return false;
    }
}
?>
