<?php

class HWJB_Admin_Crons_UpdateJobs{
    const HOOK = 'hwjb_cron_autoupdatejob';
    public function setUp(){
        add_action( self::HOOK, array($this, 'run'));
        if ( ! wp_next_scheduled(self::HOOK) ) {
            wp_schedule_event( time(), 'daily', self::HOOK);
        }
    }
    public function run(){
        $repo = new HWJB_Admin_Repositories_JobGroupRepository();
        $service = new HWJB_Admin_Services_JobService();
        $groups = $repo->getAll();
        foreach($groups as $group){
            if($group->shouldAutoUpdate()){
                $service->updateByGroup($group->ID);
            }
        }
    }
}
