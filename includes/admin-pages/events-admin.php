<?php
/**
 * Created: 2014-04-11
 * Last Revised: 2014-04-11
 *
 * CHANGELOG:
 * 2014-04-11
 *      - Initial Class Creation
 * 2014-04-21
 *      - Added Extention of WPMAdminPage
 */

class EventsAdmin extends WPMAdminPage{

    /**
     *
     * @var WP_Meetup 
     */
    var $core;
    
    var $name;

    public function __construct($core) {
        $this->core = $core;
        $this->update_name();
        add_submenu_page(
            'wp_meetup_settings',
            ucfirst($this->name),
            ucfirst($this->name),
            'administrator',
            'wp_meetup_events',// . $this->name,
            array($this, 'create_page')
        );

    }
    
    public  function display_page() {
        $this->render_postbox_open('Events');
        $output = '';
        ?>
<table class="wpm-events-table">
    <thead>
        <tr>
        <th> </th>
        <th class="padding">Event Name</th>
        <th class="padding">Event Date</th>
        <th class="padding">Group</th>
        <th class="padding">Group ID</th>
        <th class="padding">WP Post ID</th>
    </tr>
    </thead>
        <?php
        $events = $this->core->event_db->get();
        if (is_array($events)) {
            foreach ($events as $event) {
                ?> <tr> <?php
                $this->display_event($event);
                ?> </tr> <?php
            }
        }
        ?>
</table>
        <?php
        $this->render_postbox_close();
    }
    
    public function display_event($event) {
        $event_raw = unserialize($event->event);
        ?>
<td>
    <div class="group<?php echo $event->group_id ?>"> </div>
</td>
<td>
    <a href="<?php echo get_permalink($event->wp_post_id) ?>"><?php echo substr($event_raw->name , 0 , 20) ?></a>
</td>
<td>
    <?php echo date('Y-m-d g:iA', $event->event_time) ?>
</td>
<td>
    <?php if(strlen($event_raw->group->name) > 25) {echo substr($event_raw->group->name, 0, 25) . '...';} else {echo $event_raw->group->name;} ?>
</td>
<td>
    <?php echo $event_raw->group->id ?>
</td>
<td>
    <?php echo $event->wp_post_id ?>
</td>

        <?php
        $this->group_color_styles();
    }
    
    private function group_color_styles() {
        
        ?> <style> <?php
        foreach ($this->core->groups as $group) {
            ?>
.group<?php echo $group->group_id;?> {
    background-color: <?php echo $group->color; ?>;
    width: 5px;
    height: 5px;
}
            <?php
        }
        ?> </style> <?php
    }

    public function update_name() {
        $this->name = $this->core->post_type;
    }
}