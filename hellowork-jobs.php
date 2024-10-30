<?php
/*
Plugin Name: Job Generator For Hellowork Jobs
Description: ハローワークインターネットサービスに登録されている求人情報から、採用ページを簡単に作成
Version: 0.0.0

Copyright 2017 HelloworkNext.
*/

if ( class_exists( 'HelloworkJobs' ) ){

}else{
   final class HelloworkJobs{
        private static $instance;
        private static $jobService;
        private static $jobRepository;
        private static $jobGroupRepository;
        private static $shortcodes;
        private static $session;
        public static $dir = '';
        public static $url = '';
        public static $errors = '';
        public static $jobs = array();
        public static $params = array();
        public static $san;
        public $version = '0.0.0';


        public static function instance(){
            if ( ! isset( self::$instance ) && ! ( self::$instance instanceof HelloworkJobs ) ) {
                self::$instance = new HelloworkJobs;
                self::$dir = plugin_dir_path( __FILE__ );
                self::$url = plugin_dir_url( __FILE__ );
                spl_autoload_register( array( self::$instance, 'autoloader' ) );
                register_activation_hook( __FILE__, array( self::$instance, 'activation' ) );
                register_deactivation_hook( __FILE__, array( self::$instance, 'deactivation' ) );
                // add_action('init', [self::$instance, 'register_session']);
                self::$instance->register_session();

                self::$instance->menus[ 'main' ] = new HWJB_Admin_Menus_MainMenu();
                self::$jobService                = new HWJB_Admin_Services_JobService();
                self::$jobRepository             = new HWJB_Admin_Repositories_JobRepository();
                self::$jobGroupRepository        = new HWJB_Admin_Repositories_JobGroupRepository();
                self::$shortcodes                = new HWJB_Display_Shortcodes();
                self::$params                    = new HWJB_Admin_Services_Params();
                self::$session                   = new HWJB_Admin_Services_Session();
                $validator                       = new HWJB_Admin_Validators_GenerateJobValidator(self::$session);
                self::$san                       = new HWJB_Admin_Services_Sanitizer();
                $ajaxHandler                     = new HWJB_Admin_Services_AjaxHandler();
                $jobCategoryService              = new HWJB_Admin_Services_JobCategoryService();
                $jobCategoryService->refreshCategories();
                
                $cron = new HWJB_Admin_Crons_UpdateJobs();
                $cron->setUp();

                // $api = new HWJB_Admin_Apis_HelloworkApi();
                // $categories = $api->getJobCategories();

                // add_action( 'hwjb_cron_autoupdatejob', [self::$cron, 'run']);

                if(isset($_POST['action'])&& $_POST['action'] == "gen-jobs"){
                    self::validateAdmin();
                    check_admin_referer('gen-jobs');

                    if($validator->check($_POST)){
                        self::$jobService->generateJobs(
                            self::$san->text($_POST['title']),
                            self::$san->text($_POST['tell']),
                            self::$san->text($_POST['emp_fmt']),
                            isset($_POST['hwjtm'])?self::$san->intArray($_POST['hwjtm']):array(),
                            isset($_POST['locations'])?self::$san->textArray($_POST['locations']):array()
                          );
                    }
                    wp_redirect("?page=hellowork-jobs");
                    exit;
                }
                if(isset($_POST['action'])&& $_POST['action'] == "upd-grp-config"){
                    self::validateAdmin();
                    $group_id = self::$san->int($_POST['group']);
                    check_admin_referer('upd-grp-config_' . $group_id);

                    $group = new HWJB_Admin_Models_JobGroup(get_post($group_id));
                    $group->setAutoUpdate(isset($_POST['auto_update']));
                    $group->setShowHwLink(isset($_POST['show_hw_link']));
                    wp_redirect("?page=hellowork-jobs&group={$group_id}");
                    exit;
                }
                if(isset($_POST['action'])&& $_POST['action'] == "upd-grp"){
                    self::validateAdmin();
                    $group_id = self::$san->int($_POST['group']);
                    check_admin_referer('upd-grp_' . $group_id);

                    self::$jobService->updateByGroup($group_id);
                    wp_redirect("?page=hellowork-jobs&group={$group_id}");
                    exit;
                }
                if(isset($_POST['action'])&& $_POST['action'] == "del-job"){
                    self::validateAdmin();
                    $group_id = self::$san->int($_POST['group']);

                    check_admin_referer('del-job_' . $_POST['job']);

                    self::$jobRepository->deleteJobByPostId(self::$san->int($_POST['job']));
                    wp_redirect("?page=hellowork-jobs&group={$group_id}");
                    exit;
                }
                if(isset($_POST['action'])&& $_POST['action'] == "del-grp"){
                    self::$instance->register_session();
                    self::validateAdmin();
                    check_admin_referer('del-grp_' . $_POST['group']);

                    self::$jobService->removeJobGroup(self::$san->int($_POST['group']));
                    self::$session->addSuccess("削除が完了しました。");
                    wp_redirect("?page=hellowork-jobs");
                    exit;
                }
                if(isset($_GET['group'])){
                    $jobs = self::$jobRepository->getJobsByGroup(self::$san->int($_GET['group']));
                    $group = new HWJB_Admin_Models_JobGroup(get_post(self::$san->int($_GET['group'])));
                    self::$params->put('jobs', $jobs);
                    self::$params->put('group', $group);
                }else{
                    //現在登録されている求人タイトルを配列に追加する
                    $jobGroups = self::$jobRepository->getJobGroups();
                    self::$params->put('jobGroups', $jobGroups);
                }
            }
            return self::$instance;
        }
        public static function param($key, $val=null){
            if($val){
                self::$params->put($key, $val);
                return;
            }
            return self::$params->get($key);
        }
        public static function validateAdmin(){
            if(!current_user_can( "administrator" )){
                die();
            }
        }
        public function activation(){
            // $role = get_role( 'editor' );
            // $role->add_cap( 'manage_options' ); 
        }
        public function deactivation(){
            // $role = get_role( 'editor' );
            // $role->remove_cap( 'manage_options' );
        }
        public static function template( $file_name = '', array $data = array(), $return = FALSE )
        {
            if( ! $file_name ) return FALSE;

            extract( $data );

            $path = self::$dir . 'includes/Templates/' . $file_name;

            if( ! file_exists( $path ) ) return FALSE;

            if( $return ) return file_get_contents( $path );

            include $path;
        }
        public function autoloader( $class_name )
        {
            if( class_exists( $class_name ) ) return;

            if (false !== strpos($class_name, 'HWJB_')) {
                $class_name = str_replace('HWJB_', '', $class_name);
                $classes_dir = realpath(plugin_dir_path(__FILE__)) . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR;
                $class_file = str_replace('_', DIRECTORY_SEPARATOR, $class_name) . '.php';
                if (file_exists($classes_dir . $class_file)) {
                    require_once $classes_dir . $class_file;
                }
            }
        }
        public function register_session() {
            if (!session_id())
                session_start();
        }
    } 
    function HelloworkJobs()
    {
        return HelloworkJobs::instance();
    }
    // HelloworkJobs();
    add_action('init', 'HelloworkJobs');
}
