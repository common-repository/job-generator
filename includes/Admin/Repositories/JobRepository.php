<?php

class HWJB_Admin_Repositories_JobRepository{
    // public function saveParent($title, $jobs)
    public function save(array $job, $group){
        foreach($job as $key=>$val){
            $job[$key] = str_replace(["\\n","\n"], "<br />", $val);
        }
        // var_dump($job);
        // exit();
        $post_id = wp_insert_post([
            'post_content'=>json_encode($job, JSON_UNESCAPED_UNICODE),
            'post_title'=>'',
            'post_type'=>'hwjob',
            'comment_status'=>'closed',
            'ping_status'=>'closed',
            'post_parent'=>$group->ID,
        ]);
        $job = $this->getJobByPostID($post_id);
        $group->addJob($job);
        return $job;
    }
    // public function saveWithGroup($title, $jobs){
    //     return $this->saveMany($title, $jobs);
    // }
    /**
     * [saveWithGroup description]
     * @param  [type] $title [description]
     * @param  [type] $tell  [description]
     * @param  [type] $emp_fmt  [description]
     * @param  Object $jobs  Apiから帰ってきたオブジェクト
     * @return [type]        [description]
     */
    public function saveWithGroup($title, $tell, $emp_fmt, array $jobs, array $job_types, array $locations){
        $group =$this->saveJobGroup($title, $tell, $emp_fmt, $job_types, $locations);

        foreach($jobs as $job){
            $job = $this->save($job, $group);
        }
    }
    public function updateByGroup($group_id, array $newJobs){
        $group = $this->getJobGroupByPostID($group_id);
        foreach($newJobs as $newJob){
            if($job = $this->getJobOfGroupByHelloworkId($group, $newJob['hellowork_id'])){
                $job->updateDetails($newJob);
                continue;
            }
            $this->save($newJob, $group);
        }
    }
    /**
     * 指定したグループの求人を削除する
     * @param  [type] $group_id      [description]
     * @param  array  $excepts       削除対象から除外する求人のハローワークID
     * @return [type]                [description]
     */
    public function removeJobsOfGroupExcepts($group_id, $excepts){
        $jobs = $this->getJobsByGroup($group_id);
        foreach($jobs as $job){
            if(in_array($job->hellowork_id, $excepts)){
                continue;
            }
            $job->delete();
        }
    }
    /**
     * 指定したグループの求人を終了にする
     * @param  [type] $group_id      [description]
     * @param  array  $excepts       終了にするから除外する求人のハローワークID
     * @return [type]                [description]
     */
    public function trashJobsOfGroupExcepts($group_id, $excepts){
        $jobs = $this->getJobsByGroup($group_id);
        foreach($jobs as $job){
            if(in_array($job->hellowork_id, $excepts)){
                continue;
            }
            $job->trash();
        }
    }
    public function getJobsByGroup($group){
        return $this->getJobs([
            'post_type'=>'hwjob',
            'post_parent'=>$group,
            'post_status'=>['publish', 'trash', 'draft'],
        ]);
    }
    /**
     * @return HWJB_Admin_Models_JobGroup            
     */
    public function saveJobGroup($groupName, $phone, $emp_fmt, $job_types, $locations){
        return HWJB_Admin_Models_JobGroup::save($groupName,$phone, $emp_fmt, $job_types, $locations);
    }
    /**
     * すべての求人を取得
     */
    public function getAll(){
        return $this->getJobs([
            'post_type'=>'hwjob',
            'post_parent__not_in'=>array(0),
            'post_status'=>['publish', 'trash', 'draft']
        ]);

    }
    public function getJobByPostID($post_id){
        $post = get_post($post_id);
        if(!$post){
            return;
        }
        return new HWJB_Admin_Models_Job($post);
    }
    public function getJobsByPostID(array $post_ids){
        $jobs = array();
        foreach($post_ids as $post_id){
            $job = $this->getJobByPostID($post_id);
            if($job){
                $jobs[] = $job;
            }
        }
        return $jobs;
    }
    public function getJobByHelloworkId($id){
        $jobs = $this->getAll();
        foreach($jobs as $job){
           if($job->hellowork_id == $id){
            return $job;
           } 
        }
        return;
    }
    public function getJobOfGroupByHelloworkId($group, $id){
        $jobs = $this->getJobsByGroup($group->ID);
        foreach($jobs as $job){
           if($job->hellowork_id == $id){
            return $job;
           } 
        }
        return;
    }
    public function getJobGroupByPostID($post_id){
        $post = get_post($post_id);
        if(!$post){
            return;
        }
        return new HWJB_Admin_Models_JobGroup($post);
    }
    public function getJobGroups(){
        return get_posts([
            'post_type'=>'hwjob',
            'post_parent'=>0,
            'posts_per_page'=>-1,
        ]);
    }
    public function getJobs($params, $perPage=null){
        $perPage = $perPage?:-1;
        $params = array_merge($params, ['posts_per_page'=> $perPage]);
        $posts = get_posts($params);
        $jobs = array();
        foreach($posts as $post){
            $jobs[] = new HWJB_Admin_Models_Job($post);
        }
        return $jobs;
    }
    public function deleteJobByPostId($post_id){
        $job = $this->getJobByPostId($post_id);
        $job->delete();
    }
    
}