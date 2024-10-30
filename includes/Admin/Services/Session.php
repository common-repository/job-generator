<?php

class HWJB_Admin_Services_Session{
    public function put($key, $val){
        $_SESSION[$key] = $val;
    }
    public function get($key, $shouldForget=true){
        if(!$this->has($key)){
            return;
        }
        $value = $_SESSION[$key];
        if($shouldForget){
            $this->remove($key);
        }
        return $value;
    }
    public function has($key){
        return isset($_SESSION[$key]);
    }
    public function remove($key){
        unset($_SESSION[$key]);
    }
    public function addSuccess($val){
        $this->put('success', $val);
    }
    public function hasSuccess(){
        return $this->has('success');
    }
    public function getSuccess(){
        return $this->get('success');
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