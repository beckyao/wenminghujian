<?php
class Page_Admin_TimeRangeFilter extends Page_Admin_IFilter{

    public function setFilter(DBModel $model){
        parse_str($_GET['__filter'],$params);
        $start_time=$params[$this->getParamName()."__start"];
        $end_time=$params[$this->getParamName()."__end"];
        $paramName = $this->getParamName();
        if($this->foreignTable && $this->inKey) {
            list($fKey, $key) = explode('|', $paramName);
            $finder = new $this->foreignTable;
            if($start_time){
                $finder->addWhere($fKey,strtotime($start_time),">=");
            }
            if($end_time){
                $finder->addWhere($fKey,strtotime($end_time),"<=");
            }
            $objs = $finder->setCols([$key])->findMap($key);
            $model->addWhere($this->inKey, array_keys($objs), 'IN');
        } else {
            if($start_time){
                $model->addWhere($this->getParamName(),strtotime($start_time),">=");
            }
            if($end_time){
                $model->addWhere($this->getParamName(),strtotime($end_time),"<=");
            }
        }
    }

    public function toHtml(){
        $html='';
        parse_str($_GET['__filter'],$params);
        $reqVal=$params[$this->getParamName()];
        $html.='<ul style="margin:0;" class="nav nav-pills filter">'.
            '<li class="span1">'.htmlspecialchars($this->getName()).'</li>'.
            '<li>start:<label class="radio-inline"><input class="datetimepicker" value="'.htmlspecialchars($params[$this->getParamName()."__start"]).'" type="text" name="'.$this->getParamName().'__start"></label></li>'."\n".
            '<li>end:<label class="radio-inline"><input class="datetimepicker" value="'.htmlspecialchars($params[$this->getParamName()."__end"]).'" type="text" name="'.$this->getParamName().'__end"></label></li>'."\n".
            '<li>示例：'.date("Y-m-d H:i:s").'</li>'."\n";
        $html.='</ul>';

        return $html;
    }

}


