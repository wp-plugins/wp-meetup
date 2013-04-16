<?php

// This file (and these commands) are called when a user clicks the "uninstall" link
global $wpdb;
$temp_table_events = $wpdb->prefix . 'wpmetup_' . 'events';
$temp_table_groups = $wpdb->prefix . 'wpmetup_' . 'groups';
$sql = "DROP TABLE $temp_table_events";
$wpdb->query($sql);
$wpdb->query("DELETE FROM {$wpdb->posts} WHERE `post_type` = 'wp_meetup_event';");
$sql = "DROP TABLE $temp_table_groups";
$wpdb->query($sql);
delete_option('wp_meetup_options');
wp_clear_scheduled_hook('update_events_hook');

?>