<?php

/*
Plugin Name: Front Page Custom Post Type
Plugin URI: https://github.com/leymannx/wordpress-custom-post-type.git
Description: This WordPress plugin creates a custom post type "Front Page" with some custom fields. Additionally it makes "Front Page" posts available to choose as actual WordPress front page under <a href="/wp-admin/options-reading.php">/wp-admin/options-reading.php</a>. Multi-language support enabled.
Version: 1.0
Author: Norman Kämper-Leymann
Author URI: http://berlin-coding.de
Text Domain: frontpage-cpt
Domain Path: /sprachen
*/

add_action( 'init', 'frontpage_cpt_create' );

add_action( 'plugins_loaded', 'frontpage_cpt_textdomain' );

function frontpage_cpt_textdomain() {

	load_plugin_textdomain( 'frontpage-cpt', FALSE, dirname( plugin_basename( __FILE__ ) ) . '/lang/' );
}

function frontpage_cpt_create() {

	$text_domain = 'frontpage-cpt';

	$name = __( 'Front Pages', $text_domain );
	$singular_name = __( 'Front Page', $text_domain );

	$slug = 'frontpage';
	$machine_name = 'custom_front_page';

	$labels = array(
		'name'               => $name,
		'singular_name'      => $singular_name,
		'menu_name'          => $name,
		'name_admin_bar'     => $singular_name,
		'add_new'            => __( 'Add New', $text_domain ),
		'add_new_item'       => sprintf( __( 'Add New %s', $text_domain ), $singular_name ),
		'new_item'           => sprintf( __( 'New %s', $text_domain ), $singular_name ),
		'edit_item'          => sprintf( __( 'Edit %s', $text_domain ), $singular_name ),
		'view_item'          => sprintf( __( 'View %s', $text_domain ), $singular_name ),
		'all_items'          => sprintf( __( 'All %s', $text_domain ), $name ),
		'search_items'       => sprintf( __( 'Search %s', $text_domain ), $name ),
		'parent_item_colon'  => sprintf( __( 'Parent %s', $text_domain ), $singular_name ),
		'not_found'          => sprintf( __( 'No %s Found', $text_domain ), $name ),
		'not_found_in_trash' => sprintf( __( 'No %s Found in Trash', $text_domain ), $name ),
	);

	$args = array(
		'labels'              => $labels,
		'public'              => TRUE,
		'exclude_from_search' => FALSE,
		'publicly_queryable'  => TRUE,
		'show_ui'             => TRUE,
		'show_in_nav_menus'   => TRUE,
		'show_in_menu'        => TRUE,
		'show_in_admin_bar'   => TRUE,
		'menu_position'       => 5,
		'menu_icon'           => 'dashicons-welcome-view-site',
		'capability_type'     => 'page',
		'hierarchical'        => FALSE,
		'supports'            => array( 'title', 'editor', 'author', 'thumbnail', 'trackbacks', 'custom-fields', 'revisions' ),
		'has_archive'         => FALSE,
		'rewrite'             => array( 'slug' => $slug ),
		'query_var'           => TRUE,
	);

	register_post_type( $machine_name, $args );
}
