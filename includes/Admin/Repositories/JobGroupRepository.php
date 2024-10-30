<?php

class HWJB_Admin_Repositories_JobGroupRepository{

    public function getAll(){
        $posts =  get_posts([
            'post_type'=>'hwjob',
            'post_parent'=>0,
            'posts_per_page'=>-1
        ]);
        foreach($posts as $post){
            $groups[] = new HWJB_Admin_Models_JobGroup($post);
        }
        return $groups;
    }
    public function getByPostID($post_id){
        $post = get_post($post_id);
        if(!$post){
            return;
        }
        return new HWJB_Admin_Models_JobGroup($post);
    }
}