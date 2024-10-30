<?php

class HWJB_Admin_Services_AjaxHandler{
    public function __construct(){
        add_action( 'wp_ajax_hwjb_update_jobs_by_group', array($this, 'update_job_by_group') );
    }
    public function update_job_by_group(){
        HelloworkJobs::validateAdmin();

        $group_id = $_POST[ 'group_id' ];

        check_ajax_referer( 'hwjb_update_jobs_by_group_' . $group_id );
        // error_log($group_id);
        $service = new HWJB_Admin_Services_JobService();
        $service->updateByGroup(
            HelloworkJobs::$san->int($group_id)
        );

        $result = array('success'=>true);
        echo json_encode($result);
        wp_die();
    }
}
