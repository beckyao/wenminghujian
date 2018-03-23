<?php
class RoutePlacepoint extends Base_Route_Placepoint{

    public static function getRouteInfo($pid, $rid) {
        $res = DB::query("select a.id,a.pid,a.cid, a.cid_index,b.placename,b.placeaddress,b.placelatitude,b.placelongitude from route_placepoint a left join placepoint b on a.cid=b.cid where a.id=$rid and a.pid=$pid order by a.cid_index asc;");
        $output = [];
        array_map(function($v)use(&$output){
            $output[$v['cid']] = $v;
        },$res);
        return $output;
    }

}
