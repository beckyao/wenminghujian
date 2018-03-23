<?php

class AdminAuthInterceptor extends Interceptor{
    public function beforeAction(){
        $model=WinRequest::getModel();
        $executeInfo=$model['executeInfo'];

        $controllers=ACL::getPermissionControllers();
		WinRequest::mergeModel(array('controllers'=>$controllers));

        $this->checkAuth($executeInfo);
    }

    public function checkAuth($executeInfo){
        $controllerName = $executeInfo['controllerName'];
        if($controllerName != 'Index'){
            $requestActionName = $executeInfo['requestActionName'];
            $methodName = $executeInfo['methodName'];
            //ACL::checkPermission(TableUtils::getAuth($this->genAuth($controllerName,$requestActionName)));
            ACL::checkPermission($this->genAuth($controllerName,$requestActionName,$methodName));
        }
    }

    /**
     * 使用 模块名称_函数名称 的小写形式作为权限表达式
     */
    public function genAuth($controllerName,$requestActionName,$methodName='indexAction'){
        /*if($methodName=='indexAction') {
            return strtolower($controllerName.'_'.$requestActionName); 
        } else {
            $method = substr($methodName, 0, strlen($methodName) - strlen('Action'));
            return strtolower($controllerName.'_'.$method.'_'.$requestActionName); 
        }*/
        return strtolower($controllerName.'_'.$requestActionName); 
    }

    #public function checkAuth($executeInfo){
    #    $class = $executeInfo['controllerName'].'Controller';
    #    $methodName = $executeInfo['methodName'];
    #    $c = new REFMethod($class,$methodName);
    #    $docCommentMap = $c->getDocCommentMap();
    #    # 注释 @auth ***** 代表运营人员权限
    #    $auth = $docCommentMap['@auth'];
    #    if(is_null($auth)){
    #        throw new ModelAndViewException("no permission", 1, "json:",AppUtils::returnValue(['msg'=>'no permission'], '90001'));
    #    }
    #    ACL::checkPermission($auth);
    #}
}

