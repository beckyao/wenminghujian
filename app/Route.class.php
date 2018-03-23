<?php
class Route extends Base_Route{

    public static function getRoutes($pid) {
        $res = DB::query("select * from route where pid=$pid order by route_index asc");
        return $res;
    }

    public static function getRouteAll() {
        $res = DB::query("select * from route order by route_index asc");
        $output = [];
        array_map(function($v) use (&$output){
            $output[$v['pid']][] = $v;
        },$res);
        return $output;
    }
}
