<?php
class Base_Placepoint extends DBModel{

    public function getFieldList(){
        static $FIELD_LIST=[
            ['name'=>'cid','type'=>"int",'key'=>true,'defalut'=>NULL,'null'=>false,],
            ['name'=>'pid','type'=>"string",'key'=>false,'defalut'=>NULL,'null'=>false,],
            ['name'=>'placename','type'=>"string",'key'=>false,'defalut'=>NULL,'null'=>false,],
            ['name'=>'placeindex','type'=>"int",'key'=>false,'defalut'=>NULL,'null'=>true,],
            ['name'=>'placeaddress','type'=>"string",'key'=>false,'defalut'=>NULL,'null'=>true,],
            ['name'=>'placelatitude','type'=>"string",'key'=>false,'defalut'=>NULL,'null'=>true,],
            ['name'=>'placelongitude','type'=>"string",'key'=>false,'defalut'=>NULL,'null'=>true,],
            ['name'=>'subject','type'=>"string",'key'=>false,'defalut'=>NULL,'null'=>true,],
            ['name'=>'price','type'=>"string",'key'=>false,'defalut'=>NULL,'null'=>true,],
            ['name'=>'radius','type'=>"int",'key'=>false,'defalut'=>NULL,'null'=>true,],
                    ];
        return $FIELD_LIST;
    }
}