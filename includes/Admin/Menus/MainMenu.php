<?php 

final class HWJB_Admin_Menus_MainMenu extends HWJB_Admin_Menus_AbstractMenu
{
    public $page_title = 'Hellowork Jobs';

    public $menu_slug = 'hellowork-jobs';

    public $icon_url = 'dashicons-feedback';

    public $position = '35.1337';

    public function __construct()
    {
        parent::__construct();

        add_action( 'admin_body_class', array( $this, 'body_class' ) );

    }

    public function body_class( $classes )
    {
        
        $classes = "$classes hwjb";

        return $classes;
    }

    public function get_page_title()
    {
        return "ハローワーク求人";
    }

    public function admin_init()
    {
        /*
         * If we aren't on the Ninja Forms menu page, don't admin_init.
         */
        if ( empty( $_GET[ 'page' ] ) || 'hellowork' !== $_GET[ 'page' ] ) {
            return false;
        }
        
    }

    public function display()
    {

        // wp_deregister_script('jquery');
        // $this->loadJs('jquery', 'jquery.js', array('jquery'));
        if(isset($_GET['group'])){
            $this->loadCss('grp','admin-group.css');
            $this->loadJs('grp','admin-group.js');
            HelloworkJobs::template( 'admin-menu-group.html.php' );
        }else{
            $this->loadCss('st','libs/selectize.default.css');
            $this->loadJs('st', 'libs/selectize.min.js');

            $this->loadCss('mn','admin-main.css');
            $this->loadJs('mn', 'admin-main.js');
            

            $api = new HWJB_Admin_Apis_HelloworkApi();
            $categories = $api->getJobCategories();
            // var_dump($categories);
            // exit();
            HelloworkJobs::$params->put('categories', $categories);
            HelloworkJobs::$params->put('todofuken', HWJB_Admin_Models_Todofuken::getList());
            //現在登録されている求人タイトルを配列に追加する
            HelloworkJobs::template( 'admin-menu-main.html.php' );
        }
    }
    public function get_capability()
    {
        return apply_filters( 'hwjb_forms_admin_parent_menu_capabilities', $this->capability );
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
           array(),
           $version
       );
    }

}
