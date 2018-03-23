<?php
class AppBaseController extends BaseController{
    public function __construct(){
    }
    public function _POST($paramName,$defaultValue=null,$errorCode=0){
        return AppUtils::POST($paramName,$defaultValue,$errorCode);
    }
    public function _GET($paramName,$defaultValue=null,$errorCode=0){
        return AppUtils::GET($paramName,$defaultValue,$errorCode);
    }
    public function _PAGE($totalNum,$pageId,$count)
    {
        $maxPage = ceil($totalNum/$count);
        $pageId<$maxPage?$hasNext=true:$hasNext=false;
        $pageId>1?$hasPrev=true:$hasPrev=false;
        $pageInfo = array("totalNum"=>(int)$totalNum,"page"=>$pageId,"maxPage"=>$maxPage,"hasNext"=>$hasNext,"hasPrev"=>$hasPrev,"num"=>($pageId==$maxPage?$totalNum-($maxPage-1)*$count:$count));
        return $pageInfo;
    }

    /**
     * 重载了_PAGE(因为每次获取用count的聚合方法会成为危险的地方，所以就不这么干了)
     * @param $pageId
     * @param $num
     * @param $count
     * @return array
     */
    public function _PAGEV2($pageId,$num,$count){
        $num>=$count?$hasNext=true:$hasNext=false;
        $pageId>1?$hasPrev=true:$hasPrev=false;
        $pageInfo = array("page"=>$pageId,"hasNext"=>$hasNext,"hasPrev"=>$hasPrev,"num"=>$num);
        return $pageInfo;
    }

    /**
     * 瀑布流的页标记数据格式
     * @param $lastId
     * @param $num
     * @param $count
     * @return array
     */
    public function _FLOW($preLastId,$curLastId,$num,$count){
        $num>=$count?$hasNext=true:$hasNext=false;
        $preLastId >1 ? $hasPrev=true:$hasPrev=false;
        $flowInfo = array("lastId" => $curLastId, "hasNext"=>$hasNext, "hasPrev"=>$hasPrev,"num"=>$num );
        return $flowInfo;
    }

}
