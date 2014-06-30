<?php
/**
 * Created: 2014-04-11
 * Last Revised: 2014-04-11
 *
 * CHANGELOG:
 * 2014-04-11
 *      - Initial Class Creation
 */

class WPMeetupTrigger {

    /**
     *
     * @var BOOL
     */
    var $update = FALSE;

    /**
     *
     * @var WPMeetup
     */
    var $core;

    public function __construct($core) {
        $this->core = $core;
        if ( ! wp_next_scheduled( 'wpm-event-update' ) ) {
            wp_schedule_event( time(), 'daily', 'wpm-event-update' );
        }
        add_action( 'wpm-event-update', array(&$this, 'execute_update') );

    }

    public function execute_update() {
        $this->update_events();
        $this->cleanse_old_events();
        $this->update_posts();
    }

    public function update_events() {
        $this->core->factory->query();
    }

    public function cleanse_old_events() {
        $this->core->factory->filter_old_events();
        //$this->core->factory->clean_old_posts();
    }

    public function update_posts() {
        $this->core->factory->update_posts();

    }

}
