<?php

$DEBUG_ = false;
//财付通商户号
$PARTNER = "1217927701";
//财付通密钥
$PARTNER_KEY = "e409822f8be0130e1de473f55a97c4b6";
//paysignkey(非appkey)
$APP_KEY="2wzavy0bLezCd2N4yzJMQ6SACevWhNlctreovsKX7tHO7J3Jn0AjyK6hXWFb0VL28T0Fvw2QsDCuD5OsUwyBtbXshGCwqAGHzycDBEvfllUqpy0TqzPFv3Cj2J0pvGUw";
//appid
$APP_ID="wx83340c7304564474";
//appsecret
$APP_SECRET= "140dd6d5699bddda3b49caeaa3b332e1";

//支付完成后的回调处理页面,*替换成notify_url.asp所在路径
$notify_url = SITE."/user/order/wxNotify";

