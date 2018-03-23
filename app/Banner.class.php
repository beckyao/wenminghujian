<?php
class Banner extends Base_Banner{

    /**
     * 返回bannerlist
     * @param $userId
     * @param $status
     * return array
    */
    public static function getBanners() {
        $res = DB::query("select * from banner");
        return $res;
    }
}
