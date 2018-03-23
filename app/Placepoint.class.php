<?php
class Placepoint extends Base_Placepoint{

    /**
     * 返回景点列表
     * return array
    */
    public static function getPlacePoint() {
        $res = DB::query("select * from placepoint");
        $b = [];
        array_map(function($v) use (&$b){
            $b[$v['pid']][] = $v;
        }, $res); 
        return $b;
    }

    /**
     * 根据pid返回景点列表
     * return array
    */
    public static function getPlacePointByPid($pid) {
        $res = DB::query("select * from placepoint where pid=$pid order by placeindex asc");
        return $res;
    }

    /**
     * 返回一个景区的详情数据
     * return array
    */
    public static function getPointInfo($cid) {
        $res = DB::query("select a.cid,a.pid,a.placename,a.placeaddress,a.placelatitude,a.placelongitude,a.subject,a.price,a.radius,b.res_type,b.res_index,b.res_name,b.res_subject,b.res_photo,b.res_coverphoto,b.res_url from placepoint a  join placepoint_resource b on a.cid=b.cid where a.cid=$cid");
        $output = [];
        array_map(function($v) use (&$output){
            $output['cid'] = $v['cid']; 
            $output['pid'] = $v['pid']; 
            $output['placename'] = $v['placename']; 
            $output['placeaddress'] = $v['placeaddress']; 
            $output['placelatitude'] = $v['placelatitude']; 
            $output['placelongitude'] = $v['placelongitude']; 
            $output['subject'] = $v['subject']; 
            $output['price'] = $v['price']; 
            $output['radius'] = $v['radius']; 
            $tmp['res_type'] = $v['res_type']; 
            $tmp['res_index'] = $v['res_index']; 
            $tmp['res_name'] = $v['res_name']; 
            $tmp['res_subject'] = $v['res_subject']; 
            $tmp['res_photo'] = $v['res_photo']; 
            $tmp['res_coverphoto'] = $v['res_coverphoto']; 
            $tmp['res_url'] = $v['res_url']; 
            $output['res_type'][$tmp['res_type']][] = $tmp;
        }, $res);
        return $output;
    }
}
