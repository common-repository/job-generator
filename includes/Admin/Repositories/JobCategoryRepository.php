<?php

class HWJB_Admin_Repositories_JobCategoryRepository{
    public function hasCreated(){
        return get_option(HWJB_Admin_Models_JobCategory::OPTION_KEY) !== false;
    }
    public function hasBeen($hours){
        $option = json_decode(get_option(HWJB_Admin_Models_JobCategory::OPTION_KEY));
        return strtotime($option->updated_at) < strtotime("-{$hours} hour");
    }
    public function refresh($categories){
        $data = array(
            'list'=>$categories,
            'updated_at'=>date("Y-m-d H:i:s")
        );
        update_option(HWJB_Admin_Models_JobCategory::OPTION_KEY, json_encode($data,JSON_UNESCAPED_UNICODE));
    }
}