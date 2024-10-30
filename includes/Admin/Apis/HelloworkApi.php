<?php

class HWJB_Admin_Apis_HelloworkApi{
    protected $protocol = "https://";
    protected $site_prefix = "next";
    protected $base_url = ".hellowork-jobs.info"; 
    public function getJobs($params){
        $params['empFmt'] = 2;
        $query = http_build_query($params);
        $url = $this->getUrl("/rest/offers/search?{$query}");
        // var_dump($url);
        $response = wp_remote_get($url);
        $status = wp_remote_retrieve_response_code( $response );
        if($status != 200){
            var_dump($status);
            var_dump($url);
            exit();
            throw new \Exception("failed to get jobs");
        }
        // var_dump($status);
        // exit($status);
        $body = wp_remote_retrieve_body( $response );
        return json_decode($body, true);
    }
    public function getJobsByPhone($phone){
        $url = $this->getUrl("/rest/offers/search/by_phone/{$phone}");
        $response = wp_remote_get($url);
        $body = wp_remote_retrieve_body( $response );
        return json_decode($body, true);
    }
    public function getJobById($helloworkId){
        $url = $this->getUrl("/rest/offers/hellowork_id/{$helloworkId}");
        $response = wp_remote_get($url);
        $body = wp_remote_retrieve_body( $response );
        return json_decode($body, true);
    }
    public function getJobCategories(){
        $url = $this->getUrl("/rest/hw/job_types?type=selbox");
        $response = wp_remote_get($url);
        $body = wp_remote_retrieve_body( $response );
        return json_decode($body, true);
    }
    public function getUrl($path=""){
        $url = $this->protocol . $this->site_prefix . $this->base_url;
        return $url . $path;
    }
    public function setType($type){
        $this->site_prefix = $type;
    }
} 