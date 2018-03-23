<?php
class REF{
    private $class;

    function __construct($class){
        $this->class = new ReflectionClass($class);
    }

    function getProperties(){
        return $this->class->getProperties();
    }

    function getPropertiesDocCommentMap(){
        $methods = $this->getProperties();
        $map = array();
        foreach ($properties as &$property){
            $map[$property->getName()] = $property->getDocComment();
        }
        return $map;
    }

    function getMethods(){
        return $this->class->getMethods(); 
    }

    function getMethodsDocCommentMap(){
        $methods = $this->getMethods();
        $map = array();
        foreach ($methods as &$method){
            $map[$method->getName()] = $method->getDocComment();
        }
        return $map;
    }
}

