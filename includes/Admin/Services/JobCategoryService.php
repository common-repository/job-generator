<?php

class HWJB_Admin_Services_JobCategoryService{
    // $api = new HWJB_Admin_Apis_HelloworkApi();
                // $categories = $api->getJobCategories();
    protected $api; 
    protected $repo;
    public function __construct(){
        $this->api = new HWJB_Admin_Apis_HelloworkApi(); 
        $this->repo = new HWJB_Admin_Repositories_JobCategoryRepository();
    }
    public function refreshCategories(){
        if($this->repo->hasCreated() && !$this->repo->hasBeen(24)){
            return;
        }

        $categories = $this->api->getJobCategories();
        $this->repo->refresh($categories);
    }

}