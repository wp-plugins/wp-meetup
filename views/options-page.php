
<div class="wrap <?php echo ($show_plug) ? 'good-person' : 'bad-person' ?>">
<?php
    //$this->pr($events);
    
?>
<h2>WP Meetup Options</h2>
<p class="description">
    Options for Meetup.com integration. <a href="http://wordpress.org/extend/plugins/wp-meetup/">Visit plugin page</a>.
</p>
<?php
//$this->pr($groups);
?>


<?php foreach ($this->feedback as $message_type => $messages): ?>

<?php foreach ($messages as $message): ?>
<div class="<?php echo $message_type == 'error' ? 'error' : 'updated'; ?>"><p><?php echo $message; ?></p></div>
<?php endforeach; ?>

<?php endforeach; ?>



<?php
ob_start();
?>
<div id="wp-meetup-support-us">
<h3>Support the Developers</h3>
<?php
$show_plug_options = "";

$show_plug_options .= $this->element('option', 'good person and', array('value' => 'true', 'selected' => $show_plug == TRUE));
$show_plug_options .= $this->element('option', 'bad person and do not', array('value' => 'false', 'selected' => $show_plug == FALSE));
?>
<p>I am a <select name="show_plug"><?php echo $show_plug_options; ?></select>support the open-source community.</p>

<?php
$probability_select_content = "";
foreach (range(1, 50) as $chance_in_fifty) {
    $probability_select_content .= $this->element('option', $chance_in_fifty, array('value' => 1/$chance_in_fifty, 'selected' => $show_plug_probability == number_format(1/$chance_in_fifty, 13)));
}
$probability_select = $this->element('select', $probability_select_content, array('name' => 'show_plug_probability'));
?>
<p>By selecting "Good Person" you will have a 1 and <?php echo $probability_select; ?> chance of linking to our website Meetup event posts that are posted to your blog.</p>

<p>By selecting "BAD Person" you are not a good person ;| (Angry face)</p>

<?php if (!$show_plug): ?>
<div class="wp-meetup-caption">
<img src="<?php echo $this->plugin_url . "images/starving_dev.jpg"; ?>" alt="We're starving!" />
<p>Please support us, we need to eat!!!!</p>
</div>
<?php endif; ?>
</div>
<?php
$options_div = ob_get_clean();
?>


<div id="wp-meetup-container"<?php if(count($events) == 0) echo " class=\"no-events\""; ?>>
<div id="wp-meetup-options">
<form action="<?php echo $this->admin_page_url; ?>" method="post">

<?php if (count($groups) > 0 && !$show_plug): ?>
<?php echo $options_div; ?>
<?php endif; ?>

<h3>API Key</h3>
<p>
    To use WP Meetup, you need to provide your <a href="http://www.meetup.com/meetup_api/key/">Meetup.com API key</a>.  Just paste that key here:
</p>

<p>
    <label>Meetup.com API Key: </label>
    <input type="text" name="api_key" size="30" value="<?php echo $this->options->get('api_key'); ?>" />
</p>



<h3>Group Information</h3>
<?php
if (count($groups) > 0) :
    
    $rows = array();
    foreach ($groups as $group) {
        $rows[] = array(
            $this->element('a', $group->name, array('href' => $group->link)),
            $this->element('a', 'Remove Group', array('href' => $this->admin_page_url . '&remove_group_id=' . $group->id))
        );
    }
    echo $this->data_table(array('Group Name', 'Remove Group'), $rows, array('id' => 'groups-table'));
    
?>
<p>
    <label>New Group URL</label>
    <input type="text" name="group_url" size="30" value="http://www.meetup.com/" />
</p>
<?php else: ?>
<p>
    To pull in your Meetup.com events, provide your group's Meetup.com URL, e.g. "http://www.meetup.com/tucsonhiking"
</p>
<p>
    <label>Meetup.com Group URL: </label>
    <input type="text" name="group_url" size="30" value="http://www.meetup.com/" />
</p>
<?php endif; ?>




<?php
$date_select = "<select name=\"publish_buffer\">";
$options = array(
    '1 week' => '1 weeks',
    '2 weeks' => '2 weeks',
    '1 month' => '1 month'
);
foreach ($options as $label => $value) {
    $date_select .= "<option value=\"{$value}\"" . ($this->options->get('publish_buffer') == $value ? ' selected="selected"' : "") . ">$label</option>";
}
$date_select .= "</select>";
?>


<h3>Publishing Options</h3>
<div id="publishing-options">
    <?php //echo $publish_option; ?>
    <label><input type="radio" name="publish_option" value="post" <?php if ($publish_option == 'post') {echo " checked=\"checked\" ";} ?>/>Publish as standard posts (recommended for non-developers)</label>
    
    <div class="publish_option_info">
        <p>
            <label>Categorize each event post as <input type="text" name="category" value="<?php echo $category; ?>" /></label>
        </p>
        
    </div>
    
    <label><input type="radio" name="publish_option" value="cpt" <?php if ($publish_option == 'cpt') {echo " checked=\"checked\" ";} ?>/>Publish as custom post type</label>
    
    <div class="publish_options_info">
        <p>
            The name of the custom post type is <code>wp_meetup_event</code>.  The archive is accessible from <a href="<?php echo home_urL('events'); ?>"><?php echo home_urL('events'); ?></a>.  The posts have a taxonomy called <code>wp_meetup_group</code>, which holds the name of the group.  The following custom fields are available: <code>time</code>, <code>utc_offset</code>, <code>event_url</code>, <code>venue</code> (as a serialized array), <code>rsvp_limit</code>, <code>yes_rsvp_count</code>, <code>maybe_rsvp_count</code>.
        </p>
    </div>
    
   
</div>
<p>
    <label>Publish event posts <?php echo $date_select; ?> before the event date.</label>
</p>

<?php if (count($groups) > 0 && $show_plug): ?>
<?php echo $options_div; ?>
<?php endif; ?>




<p>
    <input type="submit" value="Update Options" class="button-primary" />
</p>





<?php if (count($groups) > 0): ?>
<h3>Update Events Posts</h3>
<p>
    WP Meetup fetches the latest updates to your meetup events every hour and updates your event posts accordingly.  However, if you want recent changes to be reflected immediately, you can force an update by clicking "Update Event Posts."
</p>
<p>
    <input type="submit" name="update_events" value="Update Event Posts" class="button-secondary" />
</p>
<?php endif; ?>

</form>
</div><!--#wp-meetup-options-->



<div id="wp-meetup-events">
<?php if ($events): ?>
<h3>Events (Upcoming in the next month)</h3>
<pre>
<?php //var_dump($events); ?>
</pre>

<?php
$post_status_map = array(
    'publish' => 'Published',
    'pending' => 'Pending',
    'draft' => 'Draft',
    'future' => 'Scheduled',
    'private' => 'Private',
    'trash' => 'Trashed'
);

$headings = array(
    'Group',
    'Event Name',
    'Event Date',
    'Date Posted',
    'RSVP Count'
);
$rows = array();
//$this->pr($events);
foreach ($events as $event) {
    $rows[] = array(
        $this->element('a', $event->group->name, array('href' => $event->group->link)),
        $this->element('a', $event->name, array('href' => get_permalink($event->post_id))),
        date('D M j, Y, g:i A', $event->time + $event->utc_offset),
        date('Y/m/d', strtotime($event->post->post_date)) . "<br />" . $post_status_map[$event->post->post_status],
        $event->yes_rsvp_count . " going"
    );
}
echo $this->data_table($headings, $rows);

?>

<?php elseif(count($groups) > 0): ?>

<p>There are no available events listed for this group.</p>

<?php endif; ?>
</div><!--#wp-meetup-events-->
</div><!--#wp-meetup-container-->






<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) {return;}
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/en_US/all.js#xfbml=1";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>

<p>Powered by <a href="http://nuancedmedia.com/" title="Website design, Marketing and Online Business Consulting">Nuanced Media</a> <span class="fb-like" data-href="http://www.facebook.com/NuancedMedia" data-send="false" data-layout="button_count" data-width="450" data-show-faces="false"></span></p>

</div><!--.wrap-->

