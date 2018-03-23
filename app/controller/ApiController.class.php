<?php
class ApiController extends AppBaseController {

    public function __construct() {

    }

    //banner
    public function bannersAction() {
        $Banner = new Banner();
        $res = $Banner->getBanners();
        return [$this->_GET('data_type', 'json').":",AppUtils::returnValue($res)];
    }
    //景区
    public function placeAction() {
        $Place = new Place();
        $res = $Place->getPlace();
        return [$this->_GET('data_type', 'json').":",AppUtils::returnValue($res)];
    }

    //返回所有景点
    //public function placepointsAction() {
    //    $PlacePoint = new Placepoint();
    //    $res = $PlacePoint->getPlacePoint();
    //    return [$this->_GET('data_type', 'json').":",AppUtils::returnValue($res)];
    //}

    //返回景点详情 包括placepoint 和reource表
    public function pointInfoAction() {
        $cid = $this->_GET('cid', null, 10001);
        $PlacePoint = new Placepoint();
        $res = $PlacePoint->getPointInfo($cid);
        return [$this->_GET('data_type', 'json').":",AppUtils::returnValue($res)];
    }

    //根据景区id返回景点 
    public function placepointAction() {
        $pid = $this->_GET('pid', null, 10001);
        $PlacePoint = new Placepoint();
        $res = $PlacePoint->getPlacePointByPid($pid);
        return [$this->_GET('data_type', 'json').":",AppUtils::returnValue($res)];
    }

    //根据pid cid 返回景点资源
    public function placepointResourceAction() {
        $pid = $this->_GET('pid', null, 10001);
        $cid = $this->_GET('cid', null, 10001);
        $source = new PlacepointResource();
        $res = $source->getResource($pid, $cid);
        return [$this->_GET('data_type', 'json').":",AppUtils::returnValue($res)];
    }
    
    //根据pid获取线路
    public function routeAction() {
        $pid = $this->_GET('pid', null, 10001);
        $Route = new Route();
        $res = $Route->getRoutes($pid);
        return [$this->_GET('data_type', 'json').":",AppUtils::returnValue($res)];
    }

    //直接获取所有线路（已根据pid格式化）
    public function routeallAction() {
        $Route = new Route();
        $res = $Route->getRouteAll();
        return [$this->_GET('data_type', 'json').":",AppUtils::returnValue($res)];
    }

    //路线 景区 获取景点
    public function routeinfoAction() {
        $pid = $this->_GET('pid', null, 10001);
        $rid = $this->_GET('rid', null, 10001);
        $Route = new RoutePlacepoint();
        $res = $Route->getRouteInfo($pid, $rid);

        //根据cids取景点资源
        $source = new PlacepointResource();
        $sources = $source->getResourceByCids(array_keys($res));
        foreach($res as $k=>&$v) {
            $v['source'] = $sources[$v['cid']];
        }
        return [$this->_GET('data_type', 'json').":",AppUtils::returnValue($res)];
    }

    //根据经纬度获取最近景点
    public function nearPointAction() {
        $lat = $this->_GET('lat', null, 10001);
        $lng = $this->_GET('lng', null, 10001);
        $res = [];
        return [$this->_GET('data_type', 'json').":",AppUtils::returnValue($res)];
    }

}
