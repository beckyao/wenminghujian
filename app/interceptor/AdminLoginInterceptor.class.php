<?php

class AdminLoginInterceptor extends Interceptor{
    public function beforeAction(){
        $model=WinRequest::getModel();
        $executeInfo=$model['executeInfo'];
        if(!$_SESSION['admin'] && 
            $this->needLogin($executeInfo)
        ){
            throw new ModelAndViewException("not login",1,"redirect:/admin/index/login?url=".urlencode($_SERVER['REQUEST_URI']));
        } else{
        	//WinRequest::mergeModel(array('user'=>$_SESSION['user']));
        	WinRequest::mergeModel(array('user'=>Admin::getCurrentAdmin()));
        }
    }
    public function needLogin($executeInfo){
        $notAuthActions=[
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

