<?php

class HWJB_Admin_Services_Params{
    private $params = array();
    public function put($key, $val){
        $this->params[$key] = $val;
    }
    public function get($key){
        if(!$this->has($key)){
            return;
        }
        return $this->params[$key];
    }
    public function has($key){
        return isset($this->params[$key]);
    }
    public function remove($key){
        unset($this->params[$key]);
    }
    public function addError($val){
        $this->put('error', $val);
    }
    public function hasError(){
        return $this->has('error');
    }
    public function getError(){
        return $this->get('error');
    }
}