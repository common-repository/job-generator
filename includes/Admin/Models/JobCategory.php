<?php 

final class HWJB_Admin_Models_JobCategory extends HWJB_Admin_Models_BaseModel{
    const OPTION_KEY = "hwjb_categories";

    public static function getList(){
        $option = json_decode(get_option(self::OPTION_KEY),true);
        return $option['list'];
    }
    public static function getNameById($id){
        $list = self::getList();
        return $list[$id];
    }
    public static function updateAll($categories){
        update_option(self::OPTION_KEY, $categories);
    }
}