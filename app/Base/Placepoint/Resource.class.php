<?php
class Base_Placepoint_Resource extends DBModel{

    public function getFieldList(){
        static $FIELD_LIST=[
            ['name'=>'id','type'=>"int",'key'=>true,'defalut'=>NULL,'null'=>false,],
            ['name'=>'pid','type'=>"string",'key'=>false,'defalut'=>'0','null'=>false,],
            ['name'=>'cid','type'=>"int",'key'=>false,'defalut'=>'0','null'=>false,],
            ['name'=>'res_type','type'=>"int",'key'=>false,'defalut'=>'1','null'=>false,],
            ['name'=>'res_index','type'=>"int",'key'=>false,'defalut'=>'1','null'=>false,],
            ['name'=>'res_name','type'=>"string",'key'=>false,'defalut'=>NULL,'null'=>true,],
            ['name'=>'res_subject','type'=>"string",'key'=>false,'defalut'=>NULL,'null'=>true,],
            ['name'=>'res_photo','type'=>"string",'key'=>false,'defalut'=>'0','null'=>true,],
            ['name'=>'res_coverphoto','type'=>"string",'key'=>false,'defalut'=>'0','null'=>true,],
            ['name'=>'res_url','type'=>"string",'key'=>false,'defalut'=>'0','null'=>true,],
                    ];
        return $FIELD_LIST;
    }
}