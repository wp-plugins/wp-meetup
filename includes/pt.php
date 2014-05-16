<?php
/**
 * Created: 2014-04-11
 * Last Revised: 2014-04-11
 *
 * CHANGELOG:
 * 2014-04-11
 *      - Initial Class Creation
 */

class PostType extends NMCustomPost {

    /**
     *
     * @var WP_Meetup
     */
    var $core;
    
    /**
     *
     * @var STRING 
     */
    var $pt;
    
    /**
     *
     * @var PostsDB
     */
    var $post_db;

    /**
     * 
     * @param WP_Meetup $core
     */
    public function __construct($core) {
        $this->core = $core;
        $this->pt = $core->post_type;
        $this->post_db = $this->core->post_db;
        parent::__construct();
    }
}