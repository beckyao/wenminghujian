<?php
class Base_Place extends DBModel{

    public function getFieldList(){
        static $FIELD_LIST=[
            ['name'=>'id','type'=>"int",'key'=>true,'defalut'=>NULL,'null'=>false,],
            ['name'=>'pid','type'=>"string",'key'=>false,'defalut'=>NULL,'null'=>true,],
            ['name'=>'icon','type'=>"string",'key'=>false,'defalut'=>NULL,'null'=>true,],
            ['name'=>'title','type'=>"string",'key'=>false,'defalut'=>NULL,'null'=>true,],
                    ];
        return $FIELD_LIST;
    }
}
