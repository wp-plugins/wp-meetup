<?php
/**
 * Created: 2014-04-11
 * Last Revised: 2014-04-11
 *
 * CHANGELOG:
 * 2014-04-11
 *      - Initial Class Creation
 */

class WPMeetupEventList {
    
    /**
     *
     * @var WPMeetup 
     */
    var $core;
    
    /**
     *
     * @var array 
     */
    var $atts;
    
    /**
     * Since this is used for both the shortcode as the widget, this differentiates the uses. 
     * @var BOOL
     */
    var $is_widget;
    
    /**
     * 
     * @param WPMeetup $core
     * @param ARRAY $atts
     */
    public function __construct($core, $atts, $widget = FALSE) {
        $this->core = $core;
        $this->is_widget = $widget;
        $defaults = array(
            'max' => NULL,
            'show' => NULL,
        );
        if (is_array($atts)) {
            $this->atts = array_merge($defaults, $atts);
        } else {
            $this->atts = $defaults;
        }
    }
    
    public function execute() {
        $output = '';
        if ($this->is_widget) {
            $output .= '<div class="meetup-widget-event-list">';
        }
        $use_events = array();
        
        // The user only wants future events shown
        if ($this->atts['show'] == 'future') {
            foreach ($this->core->events as $event) {
                if ($event->event_time > date('U')) {
                        $use_events[] = $event;
                    }
            }
        } 
        // User doesn't limit the show attribute
        else {
            foreach ($this->core->events as $event) {            
                $use_events[] = $event;
            }
        }
        

        // Check for a count limit, if no limit exists
        if (is_null($this->atts['max'])) {
            foreach ($use_events as $event) {
                $output .= WPMeetupEventView::list_view($event);
            }
        } 
        // Count limit exists, execute while loop
        else {
            $i = 0;
            $c = 0;
            while ($c <= $this->atts['max']) {
                if (isset($use_events[$i])) {
                    $output .= WPMeetupEventView::list_view($use_events[$i], $this->is_widget);
                }
                $c++;
                $i++;
            }
        }
        if ($this->is_widget) {
            $output .= '</div>';
        }
        $this->group_color_styles();
        $output .= $this->core->return_nm_credit();
        return $output;
    }
    
    public function group_color_styles() {
        
        ?> <style> <?php
        foreach ($this->core->groups as $group) {
            ?>
.group<?php echo $group->group_id;?> {
    background-color: <?php echo $group->color; ?>;
}
            <?php
        }
        if ($this->core->options->get_option('link_color')) {
            ?>
.wp-meetup-calendar a {
    color: #ffffff !important;
}

.wpm-legend-item {
    color: #ffffff !important;
}

.wpm-date-display {
    color: #ffffff !important;
}
            <?php
        }
        ?> </style> <?php
    }

}