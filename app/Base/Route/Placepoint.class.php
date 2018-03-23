<?php
class Base_Route_Placepoint extends DBModel{

    public function getFieldList(){
        static $FIELD_LIST=[
            ['name'=>'id','type'=>"int",'key'=>true,'defalut'=>NULL,'null'=>false,],
            ['name'=>'pid','type'=>"string",'key'=>true,'defalut'=>NULL,'null'=>false,],
            ['name'=>'cid','type'=>"int",'key'=>true,'defalut'=>NULL,'null'=>false,],
            ['name'=>'cid_index','type'=>"int",'key'=>false,'defalut'=>NULL,'null'=>false,],
                    ];
        return $FIELD_LIST;
    }
}