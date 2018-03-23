<?php
define('SELLER_EMAIL', 'info@aimeizhuyi.com');
define('SUBJECT', '爱美主义');
define('NOTIFY_URL',SITE.'/user/order/notify');
define('CALLBACK_URL',SITE.'/user/order/callback');
define('MERCHANT_URL',SITE.'/user/order/alipayFail');
define('REFUND_NOTIFY_URL',SITE.'/user/order/notifyRefund');
define('V','2.0');
define('FORMAT','xml');
define('DIRECT_SERVICE','alipay.wap.trade.create.direct');
define('REFUND_SERVICE','refund_fastpay_by_platform_pwd');
define('REFUND_SYNC_SERVICE','refund_fastpay_by_platform_nopwd');
define('AUTHANDEXECUTE_SERVICE','alipay.wap.auth.authAndExecute');
date_default_timezone_set('Asia/Shanghai');

require_once(ROOT_PATH."/lib/alipay/alipay.config.php");
require_once(ROOT_PATH."/lib/alipay/lib/alipay_submit.class.php"); 
require_once(ROOT_PATH."/lib/alipay/lib/alipay_notify.class.php");
require_once(ROOT_PATH."/lib/alipay/lib/alipay_notify_client.class.php");

class Alipay{
    public static $alipay_config = [
        'partner'		=> '2088111840451775',
        'key'			=> 'w144h61n8b1hht2yyb5nyy5iakicgltv',
        'private_key_path'	=> '/lib/alipay/key/rsa_private_key.pem',
        'ali_public_key_path'=> '/lib/alipay/key/alipay_public_key.pem',
        'sign_type'    => 'MD5',
        'input_charset'=> 'utf-8',
        'cacert'    => 'lib/alipay/cacert.pem',
        'transport'    => 'http'
    ];

    public static $alipay_config_client = [
        'partner'		=> '2088111840451775',
        'key'			=> 'w144h61n8b1hht2yyb5nyy5iakicgltv',
        'private_key_path'	=> '/lib/alipay/key/rsa_private_key.pem',
        'ali_public_key_path'=> '/lib/alipay/key/alipay_public_key.pem',
        'sign_type'    => 'RSA',
        'input_charset'=> 'utf-8',
        'cacert'    => '/lib/alipay/cacert.pem',
        'transport'    => 'http'
    ];

    public static function init(){
        self::$alipay_config['partner']		= '2088111840451775';
        self::$alipay_config['key']			= 'w144h61n8b1hht2yyb5nyy5iakicgltv';
        self::$alipay_config['private_key_path']	= ROOT_PATH.'/lib/alipay/key/rsa_private_key.pem';
        self::$alipay_config['ali_public_key_path']= ROOT_PATH.'/lib/alipay/key/alipay_public_key.pem';
        self::$alipay_config['sign_type']    = 'MD5';
        self::$alipay_config['input_charset']= 'utf-8';
        self::$alipay_config['cacert']    = ROOT_PATH.'/lib/alipay/cacert.pem';
        self::$alipay_config['transport']    = 'http';
    }

    public static function pay($out_trade_no,$total_fee, $callback = null){
        # gen new trade id
        $req_id = date('Ymdhis');
        $request_token = self::direct($req_id,$out_trade_no,$total_fee, $callback);
        $html_text = self::authAndExecute($req_id,$request_token);
        echo $html_text;
        exit;
    }

    public static function direct($req_id,$out_trade_no,$total_fee, $callback = null){
        if(!$callback) {
            $callback = CALLBACK_URL;
        }
        $req_data = '<direct_trade_create_req><notify_url>' . NOTIFY_URL. '</notify_url><call_back_url>' . $callback . '</call_back_url><seller_account_name>' . SELLER_EMAIL. '</seller_account_name><out_trade_no>' . $out_trade_no . '</out_trade_no><subject>' . SUBJECT . '</subject><total_fee>' . $total_fee . '</total_fee><merchant_url>' . MERCHANT_URL . '</merchant_url></direct_trade_create_req>';

        $para_token = array(
        		"service" => DIRECT_SERVICE, 
        		"partner" => trim(self::$alipay_config['partner']),
        		"sec_id" => trim(self::$alipay_config['sign_type']),
        		"format"	=> FORMAT,
        		"v"	=> V,
        		"req_id"	=> $req_id,
        		"req_data"	=> $req_data,
        		"_input_charset"	=> trim(strtolower(self::$alipay_config['input_charset']))
        );

        $alipaySubmit = new AlipaySubmit(self::$alipay_config);
        $html_text = $alipaySubmit->buildRequestHttp($para_token);
        $html_text = urldecode($html_text);
        $para_html_text = $alipaySubmit->parseResponse($html_text);
        return $para_html_text['request_token'];
    }

    public static function refund($batch_no, $batch_num, $refund_date, $detail_data) {
        $para_token = array(
        		"service" => REFUND_SERVICE, 
        		"partner" => trim(self::$alipay_config['partner']),
                "notify_url" => REFUND_NOTIFY_URL,
                "seller_email" => SELLER_EMAIL,
                "refund_date" => $refund_date, //date("Y-m-d H:i:s"),
                "batch_no" => $batch_no, //date("YmdHis").$timeStruct['usec']."100503",
                "batch_num" => $batch_num, //2,
                "detail_data" => $detail_data, //"2014100775823794^0.01^test#2014100775820994^0.01^test",
        		"_input_charset" => trim(strtolower(self::$alipay_config['input_charset']))
        );
        $alipay_config = self::$alipay_config;
        $alipay_config['transport'] = 'https';
        $alipaySubmit = new AlipaySubmit($alipay_config, 'https://mapi.alipay.com/gateway.do?');
        $html_text = $alipaySubmit->buildRequestForm($para_token, "get", "waiting");
        return $html_text;
    }

    public static function syncRefund($batch_no, $batch_num, $refund_date, $detail_data) {
        $alipay_batch_refund_config = array(
            'service'           => "refund_fastpay_by_platform_nopwd",
            'input_charset'     => "utf-8",
            'sign_type'         => "MD5",
            'notify_url'        => REFUND_NOTIFY_URL,
            'partner'           => trim(self::$alipay_config['partner']),
            'key'               => trim(self::$alipay_config['key']),
        );
        $parameter = array (
            "service"           => $alipay_batch_refund_config['service'],          //接口名称
            "partner"           => $alipay_batch_refund_config['partner'],                 //合作身份者ID
            "_input_charset"    => $alipay_batch_refund_config['input_charset'],           //参数编码字 符集
            "sign_type"         => $alipay_batch_refund_config['sign_type'],               //签名方式  DSA、RSA、MD5 三个值可选,必须大写。
            "notify_url"        => $alipay_batch_refund_config['notify_url'],      //回调url
            "batch_no"          => $batch_no,
            "refund_date"       => $refund_date,
            "batch_num"         => $batch_num,
            "detail_data"       => $detail_data,
        );

        $alipaySubmit = new AlipaySubmit($alipay_batch_refund_config);
        $reqParams = $alipaySubmit->buildRequestPara($parameter);
        $parameter['sign'] = $reqParams['sign'];

        //open connection
        $ch = curl_init() ;
        //set the url, number of POST vars, POST data
        curl_setopt($ch, CURLOPT_URL, "https://mapi.alipay.com/gateway.do?" . '_input_charset=utf-8') ;
        curl_setopt($ch, CURLOPT_POST, count($parameter)) ; // 启用时会发送一个常规的POST请求，类型为：application/x-www-form-urlencoded，就像表单提交的一样。
        curl_setopt($ch, CURLOPT_POSTFIELDS, $parameter); // 在HTTP中的“POST”操作。如果要传送一个文件，需要一个@开头的文件名
        ob_start();
        curl_exec($ch);
        $curlResult = ob_get_contents() ;
        ob_end_clean();
        curl_close($ch) ;

        $xml = simplexml_load_string($curlResult);
        $ret = (string)$xml->is_success;

        $error = "";
        if($ret == 'T'){
        } else if($ret == 'F'){
            $error = (string)$xml->error;
        } else if($ret == 'P') {      //处理中或银行卡充退中
        }
        $result = array();
        $result['errCode'] = $ret;
        $result['errDesc'] = $error;
        return $result;
    }

    public static function authAndExecute($req_id,$request_token){
        $req_data = '<auth_and_execute_req><request_token>' . $request_token . '</request_token></auth_and_execute_req>';
        $parameter = array(
        		"service" => AUTHANDEXECUTE_SERVICE,
        		"partner" => trim(self::$alipay_config['partner']),
        		"sec_id" => trim(self::$alipay_config['sign_type']),
        		"format"	=> FORMAT,
        		"v"	=> V,
        		"req_id"	=> $req_id,
        		"req_data"	=> $req_data,
        		"_input_charset"	=> trim(strtolower(self::$alipay_config['input_charset']))
        );

        $alipaySubmit = new AlipaySubmit(self::$alipay_config);
        $html_text = $alipaySubmit->buildRequestForm($parameter, 'get', '&#27491;&#22312;&#36716;&#21521;&#25903;&#20184;&#23453;&#65292;&#35831;&#31245;&#20505;');
        return $html_text;
    } 

    public static function notify(){
        PLogger::get("pay_notify",['file_prefix'=>'pay_notify_','level'=>PLogger::INFO,'path'=> ROOT_PATH."/log/"])
            ->info(implode("\t",[
                "pay_notify",
                json_encode($_POST),
            ])
        );
        #$fp = fopen("/var/www/html/aimei_backend/lib/xxxx",'w');
        #fwrite($fp,$_POST['notify_data']."\n");
        #
        //计算得出通知验证结果
        $alipayNotify = new AlipayNotify(self::$alipay_config);
        $verify_result = $alipayNotify->verifyNotify();
        if($verify_result) {//验证成功
        	//解密（如果是RSA签名需要解密，如果是MD5签名则下面一行清注释掉）
        	$notify_data = $_POST['notify_data'];
        	
        	$doc = new DOMDocument();
        	$doc->loadXML($notify_data);
        	
        	if( ! empty($doc->getElementsByTagName( "notify" )->item(0)->nodeValue) ) {
        		//商户订单号
        		$out_trade_no = $doc->getElementsByTagName( "out_trade_no" )->item(0)->nodeValue;
                $out_trade_no_tmp = $out_trade_no;
                $out_trade_no = GlobalMethod::genPaymentId($out_trade_no); 
        		//支付宝交易号
        		$trade_no = $doc->getElementsByTagName( "trade_no" )->item(0)->nodeValue;
        		//交易状态
        		$trade_status = $doc->getElementsByTagName( "trade_status" )->item(0)->nodeValue;
                $buyer_account = $doc->getElementsByTagName( "buyer_email" )->item(0)->nodeValue;
        		
                # $_POST['trade_status'] is null
        		if($trade_status == 'TRADE_FINISHED' || $trade_status == 'TRADE_SUCCESS') {
                    self::_changeOrderStatus($out_trade_no, $out_trade_no_tmp, $trade_no, $trade_status, $buyer_account);
        			echo "success";		//请不要修改或删除
        		}else{
                    echo "fail";
                }
        		#else if ($_POST['trade_status'] == 'TRADE_SUCCESS') {
        		#	
        		#	echo "success";		//请不要修改或删除
        		#}
        	}
        }
        else {
            //验证失败
            echo "fail";
        
            //调试用，写文本函数记录程序运行情况是否正常
            //logResult("这里写入想要调试的代码变量值，或其他运行的结果记录");
        }
        #fclose($fp);
    }

    private static function _changeOrderStatus($out_trade_no, $out_trade_no_tmp, $trade_no, $trade_status, $pay_account, $source='zfb') {
        return (new Payment())->rechargeDone($out_trade_no_tmp,$source,array(
            'outId' => $trade_no,
            'outStatus'=> $trade_status,
            'accountDetail'=> $pay_account
        ));
        /* 2015.01.11 dingping note
        $payment = new Payment();
        $payment = $payment->addWhere("id",$out_trade_no)->select();
        if($payment && $payment->mStatus == 'wait_pay'){
            $payment->mStatus = 'payed';
            $payment->mUpdateTime = time();
            $payment->mTradeNo = $out_trade_no_tmp; 
            $payment->mPayAccount = $pay_account;
            $payment->mPlatformTradeNo = $trade_no;
            $payment->mSource = $source;
            if($payment->save()){
                if ($payment->mOrderType == 0) {
                    $order=new Order();
                    $order=$order->addWhere("id",$payment->mOrderId)->select();
                    $stock = new Stock();
                    $stock = $stock->addWhere('id', $order->mStockId)->select();
                    if($order){
                        if($payment->mType=='prepay'){
                            $order->mStatus='prepayed';
                            $order->mPrePaymentId = $payment->mId;
                            # 预付之后将锁定数量写入销售数量
                            $num = $order->mNum;
                            $stock_amount_id = $order->mStockAmountId;
                            $stock_amount_tbl=new DBTable('stock_amount');
                            $res=$stock_amount_tbl->addWhere('id',$stock_amount_id)->update(['locked_amount'=>["`locked_amount`-$num",DBTable::NO_ESCAPE],'sold_amount'=>["`sold_amount`+$num",DBTable::NO_ESCAPE]]);
                            $notifyTpl = "您的%stockName%已成功预定，（2小时~5个工作日）买手买到后会通知您补款，买手没能成功采购，定金会退回给您，客服咨询4008766388";
                        }else{
                            $order->mStatus='payed';
                            $order->mPaymentId = $payment->mId;
                            $notifyTpl = "您%stockName%的余款已经补齐，买手将会在3个工作日内对商品进行打包，并从海外寄出。客服咨询4008766388";
                            // 已支付全款订单加入库存表
                            $storage = new Storage;
                            $storage->mOrderId = $order->mId;
                            $storage->mBuyerId = $order->mBuyerId;
                            $storage->mUserId = $order->mUserId;
                            $storage->mCreateTime = time();
                            if(!$storage->save()) {  //添加库存出错
                                PLogger::get("service_exception",['file_prefix'=>'service_exception_','level'=>PLogger::INFO,'path'=> ROOT_PATH."/log/"])->info(implode("\t",[
                                    "STORAGE:CREATE",
                                    "orderId=".$order->mId,
                                ])
                            );
                            }

                            //如果有代金券使用需要把代金券置为已使用
                            if ($order->mCouponId) {
                                Coupon::changeCouponStatus($order->mCouponId, 'used');
                            }
                        }
                        $order->mUpdateTime = time();
                        $order->save();
                        $notifyStr = str_replace('%stockName%', $stock ? "“".$stock->mName."”" : "", $notifyTpl);
                        Notification::sendNotification($order->mUserId,['title'=>$notifyStr,'type'=>'trade','from'=>'trade',
                            'data'=>[
                                'order_id'=>$order->mId,
                                'trade_title'=>$order->statusDesc(),
                                'stock_imageUrl'=>$stock->mImgs?json_decode($stock->mImgs,true)[0]:[],
                            ]
                        ]);
                        GlobalMethod::orderLog($order, '', 'user', $order->mUserId);

                        //状态同步到pay_order add by hongjie
                        if ($order->mStatus=='prepayed') {
                            $payOrderInfo['mPrePaymentId'] = $payment->mId;
                            $payOrderInfo['mStatus'] = 'prepayed';
                        } else if($order->mStatus=='payed') {
                            $payOrderInfo['mPaymentId'] = $payment->mId;
                            $payOrderInfo['mStatus'] = 'payed';
                        }
                        $payOrderInfo['id'] = $order->mPayOrderId;
                        $payOrder = PayOrder::updatePayOrder($payOrderInfo); 
                        if ($payOrder) {
                            GlobalMethod::orderLog($payOrder, '', 'user',  $order->mUserId, 1);
                        }
                    }
                } elseif ($payment->mOrderType == 1) {
                    $PayOrder = new PayOrder();
                    $PayOrder = $PayOrder->addWhere('id', $payment->mOrderId)->select();

                    //$stock = new Stock();
                    //$stock = $stock->addWhere('id', $order->mStockId)->select();
                    if ($PayOrder) {
                        if ($payment->mType == 'prepay') {
                            $PayOrder->mStatus = 'prepayed';

                            #付100%定金之后将锁定数量写入销售数量
                            $order = new Order();
                            $orders = $order->addWhere('pay_order_id', $PayOrder->mId)->find();
                            $stockNum = [];
                            foreach ($orders as $order) {
                                $stockNum[$order->mStockAmountId] += $order->mNum;
                            }
                            foreach ($stockNum as $stockAmountId => $num) {
                                $stock_amount_tbl = new DBTable('stock_amount');
                                $res = $stock_amount_tbl->addWhere('id', $stockAmountId)->update(['locked_amount'=>["`locked_amount`-$num",DBTable::NO_ESCAPE],'sold_amount'=>["`sold_amount`+$num",DBTable::NO_ESCAPE]]);
                            }
                            // 已支付全款订单加入库存表
                            foreach ($orders as $order) {
                                $storage = new Storage;
                                $storage->mOrderId = $order->mId;
                                $storage->mBuyerId = $order->mBuyerId;
                                $storage->mUserId = $order->mUserId;
                                $storage->mCreateTime = time();
                                if (!$storage->save()) {  //添加库存出错
                                    PLogger::get("service_exception",['file_prefix'=>'service_exception_','level'=>PLogger::INFO,'path'=> ROOT_PATH."/log/"])->info(implode("\t",["STORAGE:CREATE", "orderId=".$order->mId]));
                                }
                            }
                            //如果有代金券使用需要把代金券置为已使用
                            if ($PayOrder->mCouponId) {
                                Coupon::changeCouponStatus($PayOrder->mCouponId, 'used');
                            }
                            //$notifyTpl = "您的%stockName%已成功预定，（2小时~5个工作日）买手买到后会通知您补款，买手没能成功采购，定金会退回给您，客服咨询4008766388";
                        }
                        $PayOrder->mUpdateTime = time();
                        $PayOrder->mPrePaymentId = $payment->mId;
                        $PayOrder->save();
                        GlobalMethod::orderLog($PayOrder, '', 'user', $PayOrder->mUserId, 1);

                        //状态同步到order add by hongjie
                        foreach ($orders as $order) {
                            $order->mStatus = 'prepayed';
                            $order->mUpdateTime = time();
                            $order->mPrePaymentId = $payment->mId;
                            $order->save();
                            GlobalMethod::orderLog($order, '', 'user',  $order->mUserId);
                            //买家通知
                            $stock = (new Stock())->addWhere('id', $order->mStockId)->select();
                            $notifyTpl = "你有新订单啦！客户已成功支付全款。请尽快采购备货[%stockName%]，并确认备货结果、通知顾客，不要让顾客等太久哦～";
                            $notifyStr = str_replace('%stockName%', $stock ? "“".$stock->mName."”" : "", $notifyTpl);
                            Notification::sendNotification4Buyer($order->mBuyerId,['title'=>$order->statusDesc(),'type'=>'Prepayed','from'=>'trade',
                                'data'=>[
                                    'title' => $order->statusDesc(),
                                    'order_id' => $order->mId,
                                    'stock_id' => $order->mStockId,
                                    'trade_title' => $order->statusDesc(),
                                    'content' => $notifyStr,
                                    'stock_imageUrl' => $stock->mImgs?json_decode($stock->mImgs,true)[0]:[],
                                ]
                            ]);
                        }

                        //买家通知最后一个商品
                        $stock = (new Stock())->addWhere('id', $order->mStockId)->select();
                        $notifyTpl = "您的%stockName%已经预定成功，买手会在五个工作日内为您备货完毕。如果买手没能成功采购，会全额退款给您；如果买手备货超时（超出5天），您可以选择继续等待或者申请全额退款。";
                        $notifyStr = str_replace('%stockName%', $stock ? "“".$stock->mName."”" : "", $notifyTpl);
                        Notification::sendNotification($order->mUserId,['title'=>$notifyStr,'type'=>'trade','from'=>'trade',
                            'data'=>[
                                'order_id'=>$order->mId,
                                'trade_title'=>$order->statusDesc(),
                                'stock_imageUrl'=>$stock->mImgs?json_decode($stock->mImgs,true)[0]:[],
                                'jumpURL'=>'AMCustomerURL://showorderdetail?id='.$order->mId,
                            ]
                        ],0);
                    }
                }
            }
        }*/
    }

    public static function notify_client(){
        PLogger::get("pay_notify",['file_prefix'=>'pay_notify_','level'=>PLogger::INFO,'path'=> ROOT_PATH."/log/"])
            ->info(implode("\t",[
                "pay_notify",
                json_encode($_POST),
            ])
        );
        self::$alipay_config_client['private_key_path']	= ROOT_PATH.self::$alipay_config_client['private_key_path'];
        self::$alipay_config_client['ali_public_key_path']= ROOT_PATH.self::$alipay_config_client['ali_public_key_path'];
        self::$alipay_config_client['cacert']    = ROOT_PATH.self::$alipay_config_client['cacert'];
        //计算得出通知验证结果
        $alipayNotify = new AlipayNotifyClient(self::$alipay_config_client);
        $verify_result = $alipayNotify->verifyNotify();
        if($verify_result) {//验证成功
        	//解密（如果是RSA签名需要解密，如果是MD5签名则下面一行清注释掉）
            //商户订单号
            $out_trade_no = $_POST['out_trade_no'];
            $out_trade_no_tmp = $out_trade_no;
            $out_trade_no = GlobalMethod::genPaymentId($out_trade_no); 
            //支付宝交易号
            $trade_no = $_POST['trade_no'];
            //交易状态
            $trade_status = $_POST['trade_status'];
            //买家支付账户
            $buyer_account = $_POST['buyer_email'];
        	
            if($trade_status == 'TRADE_FINISHED' || $trade_status == 'TRADE_SUCCESS') {
                self::_changeOrderStatus($out_trade_no, $out_trade_no_tmp, $trade_no, $trade_status, $buyer_account, 'zfb_client');
                echo "success";		//请不要修改或删除
            }else{
                echo "fail";
            }
        }
        else {
            //验证失败
            echo "fail";
        
            //调试用，写文本函数记录程序运行情况是否正常
            //logResult("这里写入想要调试的代码变量值，或其他运行的结果记录");
        }
    }

    public static function notify_refund(){
        PLogger::get("refund_notify",['file_prefix'=>'refund_notify_','level'=>PLogger::INFO,'path'=> ROOT_PATH."/log/"])
            ->info(implode("\t",[
                "refund_notify",
                json_encode($_POST),
            ])
        );
        //计算得出通知验证结果
        $alipayNotify = new AlipayNotifyClient(self::$alipay_config);
        $verify_result = $alipayNotify->verifyNotify();
        if($verify_result) {
            echo "success";
            exit;
            //验证成功
            //解密（如果是RSA签名需要解密，如果是MD5签名则下面一行清注释掉）
            //请在这里加上商户的业务逻辑程序代

            //——请根据您的业务逻辑来编写程序（以下代码仅作参考）——

            //获取支付宝的通知返回参数，可参考技术文档中服务器异步通知参数列表

            //批次号

            $batch_no = $_POST['batch_no'];

            //批量退款数据中转账成功的笔数

            $success_num = $_POST['success_num'];

            //批量退款数据中的详细信息
            $result_details = $_POST['result_details'];

            $rets = [];

            if(!empty($result_details)) {
                $results = explode('#', $result_details);
                foreach($results as $res) {
                    list($platformTradeNo, $amount, $ret) = explode('^', $res);
                    $rets[$ret][$platformTradeNo] = $amount;
                    $payment = new Payment;
                    $payment = $payment->addWhere('platform_trade_no', $platformTradeNo)->select();
                    $payment->mRefundMemo .= date('Ymd H:i:s').": $ret\n";
                    if($ret == 'SUCCESS') {
                        $payment->mRefundAmount += $amount;
                    }
                    $payment->save();
                    $userRefund = new UserRefund;
                    //根据paymentId作为order内部的paymentId进行查询orderId
                    $order = new Order;
                    $order = $order->addWhere('pre_payment_id',$payment->mId)->select();
                    $userRefund = $userRefund->addWhere('order_id', $order->mId)->update(['status' => '2', 'update_time' => time()]);
                    if($payment->mType == 'prepay' && $order->mStatus != 'full_refund') {
                        if($order->mPayType == 1){
                            $order->mStatus = 'full_refund';
                        }else if($order->mPayType == 0){
                            $order->mStatus = 'refund';
                        }
                    } elseif($payment->mType == 'pay') {
                        $order->mStatus = 'full_refund';
                    }
                    $order->mUpdateTime = time();
                    $order->save();
                    GlobalMethod::orderLog($order, '', 'system', 0);
                }
            }

            //判断是否在商户网站中已经做过了这次通知返回的处理
            //如果没有做过处理，那么执行商户的业务程序
            //如果有做过处理，那么不执行商户的业务程序

            echo "success";     //请不要修改或删除

            //调试用，写文本函数记录程序运行情况是否正常
            //logResult("这里写入想要调试的代码变量值，或其他运行的结果记录");

            //——请根据您的业务逻辑来编写程序（以上代码仅作参考）——
        }
        else {
            //验证失败
            echo "fail";
        
            //调试用，写文本函数记录程序运行情况是否正常
            //logResult("这里写入想要调试的代码变量值，或其他运行的结果记录");
        }
        #fclose($fp);
    }
    public static function callback(){
        $alipayNotify = new AlipayNotify(self::$alipay_config);
        $verify_result = $alipayNotify->verifyReturn();
        if($verify_result) {
        	//商户订单号
        	#$out_trade_no = $_GET['out_trade_no'];
        
        	//支付宝交易号
        	#$trade_no = $_GET['trade_no'];
        
        	//交易状态
        	#$result = $_GET['result'];
            header("Location: /user/order/alipaySuccess");
        	#echo "验证成功";
        }
        else {
            header("Location: /user/order/alipayFail");
            #echo "验证失败";
        }
    }
}
?>
