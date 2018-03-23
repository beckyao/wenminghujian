<?php

class SystemLogInterceptor extends Interceptor{
    public function afterAction(){
        $executeInfo=WinRequest::getModel("executeInfo");

        $info=[
            'log'=>$_SERVER['REQUEST_URI']." ".$this->getPostStr(),
        ];

        $admin=Admin::getCurrentAdmin();
        if($admin){
            $info['admin_id']=$admin->mId;
        }
        $buyer=Buyer::getCurrentBuyer();
        if($buyer){
            $info['buyer_id']=$buyer->mId;
        }
        $user=User::getCurrentUser();
        if($user){
            $info['user_id']=$user->mId;
        }

        SystemLog::add($info);
    }
    public function getPostStr(){
        $str="";
        foreach($_POST as $k=>$v){
            $str.="&$k=".mb_strimwidth($v,0,100,'...');
        }
        return $str;
    }

}
