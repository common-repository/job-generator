<?php

class HWJB_Admin_Services_Sanitizer{
    public function text($val){
        return sanitize_text_field($val);
    }
    public function int($val){
        return (int)$val;
    }
    public function textArray(array $data){
        $newData = array();
        foreach($data as $key => $val){
            $newData[$key] = $this->text($val);
        }
        return $newData;
    }
    public function intArray(array $data){
        $newData = array();
        foreach($data as $key => $val){
            $newData[$key] = $this->int($val);
        }
        return $newData;
    }
}