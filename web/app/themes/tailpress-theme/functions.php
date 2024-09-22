<?php

/**
 * Theme setup.
 */
function tailpress_setup() {
	add_theme_support( 'title-tag' );

	register_nav_menus(
		array(
			'primary' => __( 'Primary Menu', 'tailpress' ),
		)
	);

	add_theme_support(
		'html5',
		array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
		)
	);

    add_theme_support( 'custom-logo' );
	add_theme_support( 'post-thumbnails' );

	add_theme_support( 'align-wide' );
	add_theme_support( 'wp-block-styles' );

	add_theme_support( 'responsive-embeds' );

	add_theme_support( 'editor-styles' );
	add_editor_style( 'css/editor-style.css' );
}

add_action( 'after_setup_theme', 'tailpress_setup' );

/**
 * Enqueue theme assets.
 */
function tailpress_enqueue_scripts() {
	$theme = wp_get_theme();

	wp_enqueue_style( 'tailpress', tailpress_asset( 'css/app.css' ), array(), $theme->get( 'Version' ) );
	wp_enqueue_script( 'tailpress', tailpress_asset( 'js/app.js' ), array(), $theme->get( 'Version' ) );
}

add_action( 'wp_enqueue_scripts', 'tailpress_enqueue_scripts' );

/**
 * Get asset path.
 *
 * @param string  $path Path to asset.
 *
 * @return string
 */
function tailpress_asset( $path ) {
	if ( wp_get_environment_type() === 'production' ) {
		return get_stylesheet_directory_uri() . '/' . $path;
	}

	return add_query_arg( 'time', time(),  get_stylesheet_directory_uri() . '/' . $path );
}

/**
 * Adds option 'li_class' to 'wp_nav_menu'.
 *
 * @param string  $classes String of classes.
 * @param mixed   $item The current item.
 * @param WP_Term $args Holds the nav menu arguments.
 *
 * @return array
 */
function tailpress_nav_menu_add_li_class( $classes, $item, $args, $depth ) {
	if ( isset( $args->li_class ) ) {
		$classes[] = $args->li_class;
	}

	if ( isset( $args->{"li_class_$depth"} ) ) {
		$classes[] = $args->{"li_class_$depth"};
	}

	return $classes;
}

add_filter( 'nav_menu_css_class', 'tailpress_nav_menu_add_li_class', 10, 4 );

/**
 * Adds option 'submenu_class' to 'wp_nav_menu'.
 *
 * @param string  $classes String of classes.
 * @param mixed   $item The current item.
 * @param WP_Term $args Holds the nav menu arguments.
 *
 * @return array
 */
function tailpress_nav_menu_add_submenu_class( $classes, $args, $depth ) {
	if ( isset( $args->submenu_class ) ) {
		$classes[] = $args->submenu_class;
	}

	if ( isset( $args->{"submenu_class_$depth"} ) ) {
		$classes[] = $args->{"submenu_class_$depth"};
	}

	return $classes;
}

add_filter( 'nav_menu_submenu_css_class', 'tailpress_nav_menu_add_submenu_class', 10, 3 );


// Records Custom Post Type
function create_records_post_type() {
    register_post_type('records', array(
        'labels' => array(
            'name' => 'Records',
            'singular_name' => 'Record',
        ),
        'public' => true,
        'has_archive' => true,
        'supports' => array('title', 'editor', 'thumbnail'),
        'menu_position' => 5,
        'show_in_rest' => true,
    ));
}
add_action('init', 'create_records_post_type');

// Organize the records
function add_records_columns( $columns ) {
	unset( $columns['title'] );
	unset( $columns['date'] );
	$columns['name'] = 'Name';
	$columns['city'] = 'City';
	$columns['birthday'] = 'Birthday';

	return $columns;
}
add_filter ('manage_edit-records_columns', 'add_records_columns');

function make_records_sortable($columns) {
	$columns['name'] = 'Name';
	$columns['city'] = 'City';
	$columns['birthday'] = 'Birthday';

	return $columns;
}
add_filter('manage_edit-records_sortable_columns', 'make_records_sortable');

function populate_records_columns ($column_name, $post_id) {
	
	switch ($column_name) {
		case 'name':
			echo '<strong><a href="' . get_edit_post_link( $post_id ) . '" class="row-title">'.get_field('rec_name', $post_id).'</a></strong>';
			echo '<div class="row-actions">
					<span class="edit"><a href="' . get_edit_post_link( $post_id ) . '">Edit</a></span> |
					<span class="trash"><a href="' . get_delete_post_link( $post_id ) . '">Trash</a></span> |
					<span class="view"><a href="' . get_post_permalink( $post_id ) . '">View</a></span>
				</div>';
		break;

		case 'city' :
			echo get_field('rec_city');
		break;
		
		case 'birthday' :
			echo get_field('rec_birthday');
		break;
}

}
add_action('manage_records_posts_custom_column', 'populate_records_columns', 10, 2);
