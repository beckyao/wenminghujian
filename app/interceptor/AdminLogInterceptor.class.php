<?php

class AdminLogInterceptor extends Interceptor{
    public function afterAction(){
        if(!isset($_REQUEST['action'])){
            return;
        }
        $action = $_REQUEST['action'];
        if(!in_array($action,['create','update','delete'])){
            return;
        }
        $admin=Admin::getCurrentAdmin();
        $info=[
            'admin_id'=>$admin->mId,
            'log'=>$_SERVER['REQUEST_URI']." ".$this->getPostStr(),
        ];
        $this->addBuyerId($info);
        $this->addUserId($info);
        SystemLog::add($info);
    }
    public function getPostStr(){
        $str="";
        foreach($_POST as $k=>$v){
            $str.="&$k=$v";
        }
        return $str;
    }
    public function addBuyerId(&$info){
        $controllerName=WinRequest::getModel("executeInfo")['controllerName'];
        $buyer_table=[
            ['Live','buyer_id'],
            ['Pack','buyer_id'],
            ['Buyer','id'],
            ['BuyerVerify','id'],
        ];
        foreach($buyer_table as $r){
            
            if($r[0]==$controllerName&&isset($_REQUEST[$r[1]])){
                $info['buyer_id']=$_REQUEST[$r[1]];
            }
        }
    }
    public function addUserId(&$info){
        $controllerName=WinRequest::getModel("executeInfo")['controllerName'];
        $buyer_table=[
            ['User','id'],
            ['Order','user_id'],
        ];
        foreach($buyer_table as $r){
            if($r[0]==$controllerName&&isset($_REQUEST[$r[1]])){
                $info['user_id']=$_REQUEST[$r[1]];
            }
        }
    }
}

