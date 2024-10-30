<?php

class HWJB_Display_Shortcodes{

    protected $repos;
    public function __construct()
    {
        $this->repos = new HWJB_Admin_Repositories_JobRepository();
        add_shortcode( 'hwjb',  array( $this, 'display_job' ) );
        // add_shortcode( 'hwjb-mini',  array( $this, 'display_job_mini' ) );
        add_shortcode( 'hwjbgrp',  array( $this, 'display_jobs_by_group' ) );
    }
    public function display_job( $atts = array() ){
        $job = $this->repos->getJobByPostId($atts['id']);
        $this->loadCss('job-details', 'job-detail.css');
        HelloworkJobs::$params->put('job', $job);

        ob_start();
        if(isset($atts['type']) && $atts['type'] == "mini"){
            $this->loadCss('job-details-mini', 'job-detail-mini.css');
            $this->loadJs('job-details-mini', 'job-detail-mini.js', ['jquery','jquery-ui-accordion']);
            HelloworkJobs::template( 'job-detail.html.php');
        }else{
            HelloworkJobs::template( 'job-detail.html.php');
        }
        return ob_get_clean();
    }
    public function display_jobs_by_group( $atts = array() ){
        $this->loadCss('job-details', 'job-detail.css');
        $group = $this->repos->getJobGroupByPostId($atts['id']);
        // return $group->post_conten;
        $jobIds = json_decode($group->post_content,true)?:array();
        $jobs   = $this->repos->getJobsByPostId($jobIds);

        HelloworkJobs::$params->put('jobs', $jobs);

        ob_start();
        if(isset($atts['type']) && $atts['type'] == "mini"){
            $this->loadCss('job-details-mini', 'job-detail-mini.css');
            $this->loadJs('job-details-mini', 'job-detail-mini.js', ['jquery','jquery-ui-accordion']);
            HelloworkJobs::template( 'job-detail-all.html.php');
        }else{
            HelloworkJobs::template( 'job-detail-all.html.php');
        }
        return ob_get_clean();
    }
    protected function loadCss($key, $path, $version=0.0){
        wp_enqueue_style(
            'hwjb-admin-' . $key,
            HelloworkJobs::$url . 'assets/css/' . $path,
            array(),
            $version 
        );

    }
    protected function loadJs($key, $path, $deps=array(),$version=0.0){
        wp_enqueue_script(
           'hwjb-admin-' . $key,
           HelloworkJobs::$url . 'assets/js/' . $path,
           $deps,
           $version
       );
    }
}