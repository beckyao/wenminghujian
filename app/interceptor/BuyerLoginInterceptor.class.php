<?php

class BuyerLoginInterceptor extends Interceptor{
    public function afterAction(){
        $model=WinRequest::getModel();
        $executeInfo=$model['executeInfo'];
        PLogger::get("buyer_api",['file_prefix'=>'buyerapi_','level'=>PLogger::INFO,'path'=> ROOT_PATH."/log/"])
            ->info(implode("\t",["buyer_call_api",
            Utils::getClientIP(),
            Buyer::getCurrentBuyer()?Buyer::getCurrentBuyer()->mId:"_N",
            Buyer::getCurrentBuyer()?Buyer::getCurrentBuyer()->mName:"_N",
            $executeInfo['controllerName'],
            $executeInfo['methodName'],
            ]));
    }
    public function beforeAction(){
        $model=WinRequest::getModel();
        $executeInfo=$model['executeInfo'];
        if(!$_SESSION['buyer'] && 
            $this->needLogin($executeInfo)
        ){
            throw new ModelAndViewException("not login",1,"json:",AppUtils::returnValue(['msg'=>'not login'],99999));
        }elseif($_SESSION['buyer']&&$_SESSION['buyer']['status']!='be'&&$this->needVerify($executeInfo)){
            //没验证
            throw new ModelAndViewException("not login",1,"json:",AppUtils::returnValue(['msg'=>'not verified'],99999));
        } else{
        	WinRequest::mergeModel(array('buyer'=>Buyer::getCurrentBuyer()));
        }
    }
    public function needLogin($executeInfo){
        $notAuthActions=[
            ['Login','loginAction'],
            ['Login','registerAction'],
            ['Login','checkNameAction'],
            ['Login','checkEmailAction'],
            ['Index','loginAction'],
            ['Stock','unitsAction'],
            ['Login','getBuyerNumAction'],
        ];
        foreach($notAuthActions as $notAuthAction){
            if($executeInfo['controllerName']==$notAuthAction[0]
                &&$executeInfo['methodName']==$notAuthAction[1]){
                return false;
            }
        }
        return true;
    }
    public function needVerify($executeInfo){
        $notAuthActions=[
            ['Login','showAction'],
            ['Login','updateAction'],
            ['Login','uploadIdPicsAction'],
            ['Login','uploadHeadPicAction'],
            ['Login','showIdPicAction'],
            ['Login','loginAction'],
            ['Login','registerAction'],
            ['Login','checkNameAction'],
            ['Login','checkEmailAction'],
            ['Login','applyAction'],
            ['Index','loginAction'],
        ];
        foreach($notAuthActions as $notAuthAction){
            if($executeInfo['controllerName']==$notAuthAction[0]
                &&$executeInfo['methodName']==$notAuthAction[1]){
                return false;
            }
        }
        return true;
    }

}
