<?php
class Base_Route extends DBModel{

    public function getFieldList(){
        static $FIELD_LIST=[
            ['name'=>'id','type'=>"int",'key'=>true,'defalut'=>NULL,'null'=>false,],
            ['name'=>'pid','type'=>"string",'key'=>false,'defalut'=>NULL,'null'=>false,],
            ['name'=>'route_index','type'=>"int",'key'=>false,'defalut'=>NULL,'null'=>false,],
            ['name'=>'content','type'=>"string",'key'=>false,'defalut'=>NULL,'null'=>false,],
            ['name'=>'message','type'=>"string",'key'=>false,'defalut'=>NULL,'null'=>true,],
            ['name'=>'coverphoto','type'=>"string",'key'=>false,'defalut'=>NULL,'null'=>true,],
                    ];
        return $FIELD_LIST;
    }
}