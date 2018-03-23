<?php
class SMS{
    private static $sn;
    private static $pwd;
    private static $base_url;
    private static $multi_url;

    public static function init($sn, $password, $base_url, $multi_url = null) {
        self::$sn  = $sn;
        self::$pwd = strtoupper(md5($sn.$password));
        self::$base_url = $base_url;
        self::$multi_url = $multi_url;
    }

    # 群发phone=15810540853,15810540854...
    public static function sendSMS($phone, $msg){
        $data = implode("&", array("sn=".self::$sn, "pwd=".self::$pwd, "mobile=".$phone, "content=".Utils::toGBK($msg), "ext=&stime=&rrid="));
        #var_dump($data);
        $content=Utils::curlPost(self::$base_url, $data); 
        #var_dump($content);
    }

    public static function multiSendSMS(array $phones, array $msgs) {
        if(count($phones) != count($msgs) || empty(self::$multi_url)) {
            return false;
        }
        $phone = implode(",", $phones);
        $msgs = array_map(function($msg) {
            return Utils::toGBK($msg);
        }, $msgs);
        $msg = implode(",", $msgs);
        $data = implode("&", array("sn=".self::$sn, "pwd=".self::$pwd, "mobile=".$phone, "content=".$msg, "ext=1&stime=&rrid="));
        return $content=Utils::curlPost(self::$multi_url, $data); 
    }
}
