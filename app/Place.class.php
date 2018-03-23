<?php
class Place extends Base_Place{

    /**
     * 返回景区列表
     * return array
    */
    public static function getPlace() {
        $res = DB::query("select * from place");
        return $res;
    }
}
