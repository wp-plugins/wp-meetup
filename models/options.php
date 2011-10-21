<?php
class WP_Meetup_Options extends WP_Meetup_Model {
    
    private $option_key = 'wp_meetup_options';
    private $default_value = array(
	'api_key' => NULL,
	'category_id' => NULL,
	'publish_buffer' => '2 weeks',
	'publish_option' => 'post',
	'show_plug' => FALSE,
	'show_plug_probability' => 0.1
    );
    private $default_category = 'events';
    
    function __construct() {

	parent::__construct();
	
	if (!$this->get('category_id')) {
	    $this->set_category($this->default_category);
	}
	
	//print_r(get_option($this->option_key, $this->default_value));
	
    }
    
    function get($option_key) {
	$options = get_option($this->option_key, $this->default_value);
	if (array_key_exists($option_key, $options)) {
	    return $options[$option_key];
	} else return $this->default_value[$option_key];
    }
    
    function set($key, $value) {
	$options = get_option($this->option_key, $this->default_value);
	$options[$key] = $value;
	update_option($this->option_key, $options);
    }
    
    function get_category() {
	$category_id = $this->get('category_id');
	return get_cat_name($category_id);
    }
    
    function set_category($cat_name) {
	$cat_id = FALSE;
	if ($cat_id = get_cat_ID($cat_name)) {
	    $this->set('category_id', $cat_id);
	} else {
	    $result = wp_insert_term($cat_name, 'category');
	    $cat_id = $result['term_taxonomy_id'];
	    $this->set('category_id', $cat_id);
	}
	return $cat_id;
    }
    
    function delete_all() {
	delete_option($this->option_key);
    }
    
}