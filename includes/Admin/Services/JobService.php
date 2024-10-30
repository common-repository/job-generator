<?php

class HWJB_Admin_Services_JobService{
    public function generateJobs($title, $tell, $emp_fmt, array $job_types = array(), array $locations = array()){
            $api = new HWJB_Admin_Apis_HelloworkApi();
            $api->setType($emp_fmt);

            $params = array(
                'phone'=>$tell,
            );
            if(!empty($job_types)){
                $params['hwjtm'] = $job_types;
            }
            if(!empty($locations)){
                $params['locations'] = $locations;
            }

            $jobs = $api->getJobs($params);
            // var_dump($jobs);
            // var_dump($jobs);
            // exit();
            $repo = new HWJB_Admin_Repositories_JobRepository();
            $repo->saveWithGroup($title, $tell, $emp_fmt, $jobs, $job_types, $locations);
            return true;
    }
    public function updateByGroup($post_id){
            $repo = new HWJB_Admin_Repositories_JobRepository();
            $group = $repo->getJobGroupByPostID($post_id);

            $api = new HWJB_Admin_Apis_HelloworkApi();
            $api->setType($group->getQueryKey('type'));


            $params = array(
                'phone'=>$group->getQueryKey('phone'),
            );
            if(!empty($types = $group->jobTypeIds())){
                $params['hwjtm'] = $types;
            }
            if(!empty($locations = $group->locationIds())){
                $params['locations'] = $locations;
            }

            $jobs = $api->getJobs($params);
            $hellowork_ids = array_column($jobs, 'hellowork_id');
            $repo->updateByGroup($group->ID, $jobs);
            $repo->trashJobsOfGroupExcepts($group->ID, $hellowork_ids);
            return true;
    }
    public function removeJobGroup($groupId){
            $repo = new HWJB_Admin_Repositories_JobRepository();
            $jobs = $repo->getJobsByGroup($groupId);
            foreach($jobs as $job){
                $job->delete();
            }
            $repo =  new HWJB_Admin_Repositories_JobGroupRepository();
            $group = $repo->getByPostID($groupId);
            $group->delete();
    }
    // public function updateJobs($groupId){
    //         $api = new HWJB_Admin_Apis_HelloworkApi();

    //         $repo = new HWJB_Admin_Repositories_JobRepository();
    //         $jobs = $repo->getJobByGroup($groupId);

    //         foreach($jobs as $job){
    //             $latest = $api->getJobById($job->hellowork_id);
    //             if($latest){
    //                 $job->updateDetails($latest);
    //             }
    //             //案件が終了していた場合
    //             else{
    //                 /**
    //                  * 案件が修了した場合は、終了しましたと表示するほうがいいかも
    //                  */
    //                 $job->delete();
    //             }
    //         }


    // }
}