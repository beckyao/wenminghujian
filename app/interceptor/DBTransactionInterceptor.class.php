<?php

class DBTransactionInterceptor extends Interceptor{
    public function __construct($transactionActions=[]){
        if(is_string($transactionActions)){
            $transactionActions=[$transactionActions];
        }
        $this->transactionActions=$transactionActions;
    }
    public function beforeAction(){
        $methodName=WinRequest::getModel("executeInfo")['methodName'];
        if(in_array($methodName,$this->transactionActions)){
            try{
                DB::beginTransaction();
            }catch(Exception $e){
                Logger::error("commit error $e");
            }
        }
    }
    public function afterAction(){
        $methodName=WinRequest::getModel("executeInfo")['methodName'];
        if(in_array($methodName,$this->transactionActions)){
            try{
                DB::commit();
            }catch(Exception $e){
                Logger::error("commit error $e");
            }
        }
    }
    public function failAction(){
        $methodName=WinRequest::getModel("executeInfo")['methodName'];
        if(in_array($methodName,$this->transactionActions)){
            try{
                DB::rollBack();
            }catch(Exception $e){}
        }
    }
}
