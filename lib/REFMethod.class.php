<?php
class REFMethod{
    private $class;
    private $methodName;

    function __construct($class, $methodName){
        $this->methodName = $methodName;
        $this->class = new ReflectionMethod($class, $methodName);
    }

    function getDocComment(){
        return $this->class->getDocComment();
    }

    /**
     * 注释拦截器标准示例
     * @auth logistic_show
     * @info balabala
     */
    function getDocCommentMap(){
        $docComment = $this->getDocComment();        
        $map = array();
        if(!is_null($docComment)){
            preg_match_all('/@.*/',$docComment,$matches);
            if(!is_null($matches) && count($matches)>0){
                foreach($matches[0] as $annotation){
                    $infos = preg_split('/\s+/',$annotation);
                    if(count($infos) == 2){
                        $map[trim($infos[0])] = trim($infos[1]);
                    }
                } 
            }
        }
        return $map;
    }
}
#class A{
#    /**
#     * 注释拦截器标准示例
#     * @auth logistic_show
#     * @info balabala
#     */
#    function m(){
#    }
#}

#$c = new REFMethod('A','m');
#var_dump($c->getDocCommentMap());
