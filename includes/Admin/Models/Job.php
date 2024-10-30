<?php 

final class HWJB_Admin_Models_Job extends HWJB_Admin_Models_BaseModel{
    protected $post;
    protected $offer;
    public $group;
    public function __construct($post){
        $this->post = $post;
        $repo = new HWJB_Admin_Repositories_JobGroupRepository();
        $this->group = $repo->getByPostID($this->post->post_parent);
        $this->offer = json_decode($post->post_content);
    }
    public function getPostId(){
        return $this->post->ID;
    }
    public function getGroup(){
    }
    public function salary($min=true){
        if($min){
            return number_format($this->total_monthly_pay_min);
        }
        return number_format($this->total_monthly_pay_max);
    }
    public function baseMonthlyPay($min=true){
        if($min){
            return number_format($this->base_monthly_pay_min);
        }
        return number_format($this->base_monthly_pay_max);
    }
    public function hasExtraMonthlyPay(){
        return $this->extraMonthlyPay() + $this->extraMonthlyPay(false);
    }
    public function extraMonthlyPay($min=true){
        if($min){
            return number_format($this->total_monthly_pay_min - $this->base_monthly_pay_min);
        }
        return number_format($this->total_monthly_pay_max - $this->base_monthly_pay_max);
    }
    public function getPayUnit(){
        // var_dump($this->pay_unit);
        // exit();
        // 1:時給 2:日給 3:月給 4:年俸 9:その他
        switch ($this->pay_unit) {
            case 1:
                return "時給";
            case 2:
                return "日給";
            case 3:
                return "月給";
            case 4:
                return "年俸";
            case 9:
                return "その他";
            
            default:
                return "エラー";
                // code...
                break;
        }
    }
    public function getEmployeeType(){
        switch ($this->employee_type_id) {
            case 1:
                return "正社員";
            case 2:
                return "有期派遣";
            case 3:
                return "無期派遣";
            case 4:
                return "請負";
            case 5:
                return "その他";
            case 6:
                return "パート";
            default:
                return "不明";
        }
    }
    public function getAgeRequirement(){
        if( !$this->age_min && !$this->age_max){
            return "不問";
        }
        return $this->age_min . "〜" . $this->age_max;
    }
    public function getTransportAllowance(){
        //0:なし1:実費2:定額
        switch ($this->transport_allowance) {
            case 0:
                return "なし";
            case 1:
                $val = "実費";
                if($this->tr_unit != 0){
                    $val .="(上限あり)";
                }
                return $val;
            case 2:
                return "定額";
            default:
                return "不明";
        }
    }
    public function getTransportAllowanceUnit(){
        switch ($this->tr_unit) {
            case 0:
                return "上限なし";
            case 1:
                return "月";
            case 2:
                return "日";
            default:
                break;
        }
    }
    public function getRoom(){
        //なし:0 単身用:1, 世帯用:2, 単身世帯用両方:3
        switch ($this->room) {
            case 0:
                return false;
            case 1:
                return "単身用";
            case 2:
                return "世帯用";
            case 3:
                return "単身世帯両方";        
            default:
                return "不明";
        }
    }
    public function getChildCareCenter(){
        if($this->child_center){
            return  "あり";
        }
        return false;
    }
    public function getRetirement(){
        $str = "";
        if($this->retirement){
            $str .= "あり";
        }
        if($this->retirement_age){
            $str .= " 一律" . $this->retirement_age . "歳"; 
        }
        return $str;
    }
    public function getReemployment(){
        $str = "";
        if($this->reemployment){
            $str .= "あり";
        }
        if($this->reemployment_age){
            $str .= " " . $this->reemployment_age . "歳まで"; 
        }
        return $str;
    }
    public function updateDetails($contents){
        if(!is_string($contents)){
            $contents = json_encode($contents, JSON_UNESCAPED_UNICODE);
        }
        // print_r(str_replace("\\n", "<br />", $contents ));
        // print_r(nl2br($contents ));
        // exit();
        $my_post = array(
          'ID'           => $this->post->ID,
          'post_content' => str_replace(["\n","\\n"], "<br />", $contents )
        );
        wp_update_post($my_post);
    }
    public function delete(){
        wp_delete_post( $this->post->ID); 
    }
    public function trash(){
        $my_post = array(
          'ID'           => $this->post->ID,
          'post_status' => "trash",
        );
        wp_update_post($my_post);
    }
    public function trashed(){
        return $this->post->post_status == "trash";
    }
    /** ハローワークインターネットサービスのURLを取得する */
    public function getOfficialUrl(){
        list($no1, $no2) = explode('-', $this->hellowork_id);
        return "https://www.hellowork.go.jp/servicef/130050.do?screenId=130050&action=commonDetailInfo&kyujinNumber1={$no1}&kyujinNumber2=%0A{$no2}&kyushokuUmuHidden=&kyushokuNumber1Hidden=&kyushokuNumber2Hidden=";
    }
    public function getHwMobileUrl(){
        $domain = ($this->isFulltime())?"https://next.hellowork-jobs.info":"https://part.hellowork-jobs.info";
        return $domain . "/offers/detail/{$this->hellowork_id}";
    }
    public function __get($name){
        if(isset($this->offer->$name)){
            if(is_string($this->offer->$name)){
                // return utf8_encode($this->offer->$name);
                return $this->offer->$name;
            }
            return $this->offer->$name;
        }
    }
    private function isParttime(){
        return $this->employee_type_id == 6;
    }
    private function isFulltime(){
        return !$this->isParttime();
    }
}