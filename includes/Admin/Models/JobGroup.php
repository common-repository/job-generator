<?php 

final class HWJB_Admin_Models_JobGroup extends HWJB_Admin_Models_BaseModel{
    protected $post;
    protected $detail;
    const META_KEY = "hwjb_query";
    const CONFIG_KEY = "hwjb_grp_config";
    public function __construct($post){
        $this->post = $post;
        // $this->detail= json_decode($post->post_content);
        $this->meta = json_decode(get_post_meta( $post->ID, self::META_KEY, true));
        $this->config = json_decode(get_post_meta( $post->ID, self::CONFIG_KEY, true));
    }
    // public static fun
    // public function getContent(){
    //     return $this->
    // }
    // public function getMetaKey(){
    //     return $this->metaKey;
    // }
    public static function save($groupName, $phone, $type, $job_types, $locations){
        // $metaKey = (new self)->getMetaKey();
        $post_id = wp_insert_post([
            'post_content'=>"",
            'post_title'=>$groupName,
            'post_type'=>'hwjob',
            'comment_status'=>'closed',
            'ping_status'=>'closed',
            'post_status'=>'publish',
        ]);

        $query = json_encode(compact('groupName', 'phone', 'type', 'job_types','locations'),JSON_UNESCAPED_UNICODE);
        $config = json_encode(array('auto_update'=>0, 'show_hw_link'=>1));
        add_post_meta($post_id, self::META_KEY, $query, true); 
        add_post_meta($post_id, self::CONFIG_KEY, $config, true); 
        return new self(get_post($post_id));
    }
    public function addJob(HWJB_Admin_Models_Job $job){
        // $content = !empty(json_decode($this->post_content, true)?:array();
        $content = $this->post->post_content ? json_decode($this->post->post_content, true):array();
        // echo 'hello';
        // var_dump($this->post);
        // error_log(print_r($content, true));
        // exit();

        $post_id = $job->getPostId();
        // error_log(print_r($post_id, true));
        if(in_array($post_id, $content)){
            // error_log('in array return');
            return;
        }
        // error_log('in array not return');
        $content[] = $post_id;
        // error_log(print_r($content, true));

        $my_post = array(
          'ID'           => $this->post->ID,
          'post_content' => json_encode($content),
        );

        // error_log(print_r($my_post, true));
        wp_update_post($my_post);
        $this->refreshPost();
    }
    public function refreshPost(){
        $this->post = get_post($this->post->ID);
    }
    // public function getGroupName(){
    //     return $this->meta->groupName;
    // }
    public function getQueryKey($key){
        return $this->meta->$key;
    }
    public function delete(){
        wp_delete_post( $this->post->ID); 
    }
    public function name(){
        return $this->post->post_title;
    }
    public function phone(){
        return $this->getQueryKey('phone');
    }
    public function empFmt(){
        $type = $this->getQueryKey('type');
        if($type=="next"){
            return "フルタイム";
        }
        return "パートタイム";
    }
    public function jobTypes(){
        $types = array();

        foreach($this->getQueryKey('job_types') as $type){
            $types[$type] = HWJB_Admin_Models_JobCategory::getNameById($type);
        }
        return $types;
    }
    public function jobTypeIds(){
        return $this->getQueryKey('job_types');
    }
    public function locationIds(){
        return $this->getQueryKey('locations');
    }
    public function locations(){
        $locs = array();

        foreach($this->getQueryKey('locations') as $loc){
            $loc = str_replace("-all", "", $loc);
            $locs[$loc] = HWJB_Admin_Models_Todofuken::getNameById($loc);
        }
        return $locs;

    }
    public function setAutoUpdate($bool){
        $num = ($bool)?1:0;
        $this->config->auto_update = $num;

        $config = json_encode($this->config);

        update_post_meta($this->post->ID, self::CONFIG_KEY, $config);

    }
    public function isAutoupdate(){
        return $this->config->auto_update;
    }
    public function setShowHwLink($bool){
        $num = ($bool)?1:0;
        $this->config->show_hw_link = $num;

        $config = json_encode($this->config);

        update_post_meta($this->post->ID, self::CONFIG_KEY, $config);
    }
    public function showHwLink(){
        return $this->config->show_hw_link;
    }
    public function shouldAutoUpdate(){
        return $this->isAutoupdate();
    }
    public function __get($name){
        if(isset($this->post->$name)){
            return $this->post->$name;
        }
    }
}