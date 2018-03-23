<?php
class PlacepointResource extends Base_Placepoint_Resource{

    /**
     * 返回景点资源
     * return array
    */
    public static function getResource($pid, $cid) {
        $res = DB::query("select * from placepoint_resource where pid=$pid and cid=$cid order by res_type, res_index asc");
        $output = [];
        array_map(function($v) use (&$output){
           $output[$v['res_type']][] = $v; 
        }, $res);
        return $output;
    }

    /**
     * 根据cids返回景点资源
     * return array
    */
    public static function getResourceByCids($cids) {
        $fmt = implode(",", $cids);
        $res = DB::query("select * from placepoint_resource where cid in ($fmt) ");
        $output = [];
        array_map(function($v) use (&$output){
           $output[$v['cid']][$v['res_type']][] = $v; 
        }, $res);
        return $output;
    }
}
