<?php
/**
 * Created: 2014-04-11
 * Last Revised: 2014-04-11
 *
 * CHANGELOG:
 * 2014-04-11
 *      - Initial Class Creation
 */

class WPMeetupAdmin {
    
    /**
     *
     * @var WPMeetup
     */
    var $core;
    
    /**
     *
     * @var array 
     */
    var $page_array;

    /**
     * 
     * @param WPMeetup $core
     */
    public function __construct($core) {
        $this->core = $core;
        $this->build_page_array();
        add_action('admin_menu', array( &$this, 'add_admin_pages' ));
        add_action('admin_enqueue_scripts', array(&$this, 'load_settings_styles'), 100);
    }

    /**
     * Creates each new admin page.
     */
    public function add_admin_pages() {
        new WPMeetupMainAdmin($this->core);
        new WPMeetupOptionsAdmin($this->core);
        new WPMeetupGroupsAdmin($this->core);
        new WPMeetupEventsAdmin($this->core);
        new WPMeetupDebugAdmin($this->core);
    }
    
    public function build_page_array() {
        
    }
    
    public function load_settings_styles() {
        $pluginDirectory = trailingslashit(plugins_url(basename(dirname(__FILE__))));
        wp_register_style('wpm-admin-styles', $pluginDirectory . 'css/admin-styles.css');
        wp_enqueue_style('wpm-admin-styles');
        wp_register_script('wpm-admin-script', $pluginDirectory . 'js/coffee/nm-dashboard-script.js');
        wp_enqueue_script('wpm-admin-script');
    }
    
}