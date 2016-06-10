<?php

/*
Plugin Name: Front Page Custom Post Type
Plugin URI: https://github.com/leymannx/wordpress-custom-post-type.git
Description: This WordPress plugin creates a custom post type Front Page with some custom fields. Additionally it makes Front Page posts available to choose as static WordPress front page under <a href='/wp-admin/options-reading.php'>/wp-admin/options-reading.php</a>. Multi-language support enabled.
Version: 1.0
Author: Norman Kämper-Leymann
Author URI: http://berlin-coding.de
Text Domain: frontpage-cpt
Domain Path: /lang
*/

/**
 * Creates new custom post type Front Page.
 */
function frontpage_cpt_create() {

	$machine_name = 'frontpage_cpt';
	$slug = 'frontpage';

	$text_domain = 'frontpage-cpt';
	$name = __( 'Front Pages', $text_domain );
	$singular_name = __( 'Front Page', $text_domain );

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

	// rabbit hole = no single view
	$args = array(
		'labels'              => $labels,
		'public'              => TRUE,
		'exclude_from_search' => TRUE, // rabbit hole
		'publicly_queryable'  => FALSE, // rabbit hole
		'show_ui'             => TRUE,
		'show_in_nav_menus'   => FALSE, // rabbit hole
		'show_in_menu'        => TRUE,
		'show_in_admin_bar'   => FALSE, // rabbit hole
		'menu_position'       => 5,
		'menu_icon'           => 'dashicons-welcome-view-site',
		'capability_type'     => 'page',
		'hierarchical'        => FALSE,
		'supports'            => array( 'title', 'editor', 'thumbnail', /*'custom-fields', 'revisions'*/ ),
		'has_archive'         => FALSE,
		'rewrite'             => array( 'slug' => $slug ),
		'query_var'           => FALSE, // rabbit hole
	);

	register_post_type( $machine_name, $args );
}

add_action( 'init', 'frontpage_cpt_create' );

/**
 * Loads plugin translation.
 */
function frontpage_cpt_textdomain() {

	load_plugin_textdomain( 'frontpage-cpt', FALSE, dirname( plugin_basename( __FILE__ ) ) . '/lang' );
}

add_action( 'plugins_loaded', 'frontpage_cpt_textdomain' );

/**
 * Merges Front Page posts into array of all page posts.
 *
 * @param $pages
 * @return array
 */
function frontpage_cpt_frontpage_select( $pages ) {

	$args = array(
		'post_type' => 'frontpage_cpt',
	);

	$items = get_posts( $args );

	$pages = array_merge( $pages, $items );

	return $pages;
}

add_filter( 'get_pages', 'frontpage_cpt_frontpage_select' );

/**
 * Adds Front Page posts as options for static front page select list.
 *
 * @param $query
 */
function frontpage_cpt_enable_frontpage( $query ) {

	if ( '' == $query->query_vars['post_type'] && 0 != $query->query_vars['page_id'] )
		$query->query_vars['post_type'] = array( 'page', 'frontpage_cpt' );
}

add_action( 'pre_get_posts', 'frontpage_cpt_enable_frontpage' );

function admin_init() {

	add_meta_box( $id = 'year_completed-meta', $title = 'Year Completed', $callback = 'year_completed', $screen = 'frontpage_cpt', $context = 'side', $priority = 'low', $callback_args = NULL );
	add_meta_box( $id = 'credits_meta', $title = 'Design & Build Credits', $callback = 'credits_meta', $screen = 'frontpage_cpt', $context = 'normal', $priority = 'low', $callback_args = NULL );
//	add_meta_box( $id, $title, $callback, $page, $context, $priority );
//	add_meta_box ( $id, $title, $callback, $screen = null, $context = 'advanced', $priority = 'default', $callback_args = null );
//	add_meta_box( 'hello', 'Hello Hello', 'hello_callback', $screen = NULL, $context = 'advanced', $priority = 'default', $callback_args = NULL );
}

function hello_callback() {

}

function year_completed() {

	global $post;
	$custom = get_post_custom( $post->ID );
	$year_completed = $custom['year_completed'][0];
	?>
	<label>Year:</label>
	<input name='year_completed' value='<?php echo $year_completed; ?>'/>
	<?php
}

function credits_meta() {

	global $post;
	$custom = get_post_custom( $post->ID );
	$designers = $custom['designers'][0];
	$developers = $custom['developers'][0];
	$producers = $custom['producers'][0];
	?>
	<p><label>Designed By:</label><br/>
		<?php wp_editor( $content = $designers, $editor_id = 'mettaabox_ID_stylee', $settings = array(
			'textarea_name' => 'designers',
			'media_buttons' => FALSE,
			'textarea_rows' => 5,

		) ); ?></p>
	<p><label>Built By:</label><br/>
		<textarea cols='50' rows='5' name='developers'><?php echo $developers; ?></textarea></p>
	<p><label>Produced By:</label><br/>
		<textarea cols='50' rows='5' name='producers'><?php echo $producers; ?></textarea></p>
	<?php
}

add_action( 'admin_init', 'admin_init' );

function save_details() {

	global $post;

	update_post_meta( $post->ID, 'year_completed', $_POST['year_completed'] );
	update_post_meta( $post->ID, 'designers', $_POST['designers'] );
	update_post_meta( $post->ID, 'developers', $_POST['developers'] );
	update_post_meta( $post->ID, 'producers', $_POST['producers'] );
}

add_action( 'save_post', 'save_details' );