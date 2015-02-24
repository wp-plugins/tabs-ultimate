<?php


class TabsUltimateCustomPostType{

private $post_type = 'tabsultimate';
private $post_label = 'Tabs Ultimate';
private $prefix = '_tabs_ultimate_';
function __construct() {
	
	
	add_action("init", array(&$this,"create_post_type"));
	add_action( 'init', array(&$this, 'tabs_ultimate_register_shortcodes'));
	add_action( 'wp_footer', array(&$this, 'enqueue_styles'));
	add_action( 'wp_footer', array(&$this, 'enqueue_scripts'));
	add_action( 'cmb2_init', array(&$this,'tabsultimate_register_repeatable_group_field_metabox' ));
	
	register_activation_hook( __FILE__, array(&$this,'activate' ));
}

function create_post_type(){
	register_post_type($this->post_type, array(
	         'label' => _x($this->post_label, $this->post_type.' label'), 
	         'singular_label' => _x('All '.$this->post_label, $this->post_type.' singular label'), 
	         'public' => true, // These will be public
	         'show_ui' => true, // Show the UI in admin panel
	         '_builtin' => false, // This is a custom post type, not a built in post type
	         '_edit_link' => 'post.php?post=%d',
	         'capability_type' => 'page',
	         'hierarchical' => false,
	         'rewrite' => array("slug" => $this->post_type), // This is for the permalinks
	         'query_var' => $this->post_type, // This goes to the WP_Query schema
	         //'supports' =>array('title', 'editor', 'custom-fields', 'revisions', 'excerpt'),
	         'supports' =>array('title', 'author'),
	         'add_new' => _x('Add New', 'Event')
	         ));
}



/**
 * Hook in and add a metabox - repeatable grouped fields
 */
function tabsultimate_register_repeatable_group_field_metabox() {

	// Start with an underscore to hide fields from custom fields list
	//$prefix = '_tabsultimate_group_';

	/**
	 * Repeatable Field Groups
	 */
	$cmb_group = new_cmb2_box( array(
		'id'           => $this->prefix . 'metabox',
		'title'        => __( 'Tabs', 'cmb2' ),
		'object_types' => array( $this->post_type, ),
	) );

	// $group_field_id is the field id string, so in this case: $prefix . 'demo'
	$group_field_id = $cmb_group->add_field( array(
		'id'          => $this->prefix . 'tabGroup',
		'type'        => 'group',
		'description' => __( 'Generates reusable tabs', 'cmb2' ),
		'options'     => array(
			'group_title'   => __( 'Tab {#}', 'cmb2' ), // {#} gets replaced by row number
			'add_button'    => __( 'Add Another Tab', 'cmb2' ),
			'remove_button' => __( 'Remove Tab', 'cmb2' ),
			'sortable'      => true, // beta
		),
	) );

	/**
	 * Group fields works the same, except ids only need
	 * to be unique to the group. Prefix is not needed.
	 *
	 * The parent field's id needs to be passed as the first argument.
	 */
	$cmb_group->add_group_field( $group_field_id, array(
		'name'       => __( 'Table Title', 'cmb2' ),
		'id'         => 'title',
		'type'       => 'text',
		// 'repeatable' => true, // Repeatable fields are supported w/in repeatable groups (for most types)
	) );

	$cmb_group->add_group_field( $group_field_id, array(
		'name'        => __( 'Description', 'cmb2' ),
		'description' => __( 'Enter the description for this tab', 'cmb2' ),
		'id'          => 'description',
		'type'        => 'textarea_small',
	) );


}


function tabs_ultimate_shortcode($atts){
		extract( shortcode_atts( array(
			'id' => '',
		), $atts ) );
		$dir = plugin_dir_path( __FILE__ );

		/*
		$background_color = get_post_meta($id, $this->prefix . 'background_color', true);
		$tab1_title = get_post_meta($id, $this->prefix . 'tab1_title', true);
		$tab1_message = get_post_meta($id, $this->prefix . 'tab1_message', true);
		$tab2_title = get_post_meta($id, $this->prefix . 'tab2_title', true);
		$tab2_message = get_post_meta($id, $this->prefix . 'tab2_message', true);
		*/
		$tabGroup = get_post_meta($id, $this->prefix . 'tabGroup', true);
		
		ob_start();
		include $dir.'template/tabsUltimateTemplate.php';
		return ob_get_clean();
}



function tabs_ultimate_register_shortcodes(){
		add_shortcode( 'tabs_ultimate', array(&$this,'tabs_ultimate_shortcode' ));
	}


function activate() {
	// register taxonomies/post types here
	$this->create_post_type();
	global $wp_rewrite;
	$wp_rewrite->flush_rules();
}

function enqueue_styles(){
	wp_register_style( 'tabs-ultimate-css', plugin_dir_url(__FILE__).'css/tabsUltimate.css' );
	wp_enqueue_style('tabs-ultimate-css');
	wp_enqueue_style('tabs-ultimate-jqueryui-css', 'https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.3/themes/smoothness/jquery-ui.css');
	
}

function enqueue_scripts(){
	wp_enqueue_script('tabs-ultimate-jqueryui-js', 'https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.3/jquery-ui.min.js', array('jquery'));
	wp_enqueue_script('tabs-ultimate-js', plugin_dir_url(__FILE__).'js/tabsUltimate.js', array('jquery'));
	
}



}// end TabsUltimateCustomPostType class

new TabsUltimateCustomPostType();


?>