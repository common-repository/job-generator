<?php

class HWJB_Admin_Validators_GenerateJobValidator{
    protected $messages;
    public function __construct(HWJB_Admin_Services_Session $params){
        $this->messages = $params;
    }
    public function check($post){

        if(empty($_POST['title'])){
            $this->messages->addError("タイトルを入力してください。");
            return false;
        } 
        if(empty($_POST['tell'])){
            $this->messages->addError("電話番号を入力してください。");
            return false;
        }
        if(empty($_POST['emp_fmt'])){
            $this->messages->addError("求人の種類を選択してください。");
            return false;
        }
        return true;
    }
}
