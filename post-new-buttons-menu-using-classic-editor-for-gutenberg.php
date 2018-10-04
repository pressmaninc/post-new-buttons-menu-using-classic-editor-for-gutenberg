<?php
/*
Plugin Name: Post-new buttons/menu using classic editor for Gutenberg
Plugin URI:
Description: Add post-new button and menu using classic editor for Gutenberg.
Version:1.0
Author: Hiroshi Sekiguchi
Author URI:
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/

if ( ! defined( 'ABSPATH' ) ) exit;

add_action( 'admin_menu', 'add_sub_post_new_classic_menu_and_button', 9999 );
function add_sub_post_new_classic_menu_and_button(){

	// Is gutenberg active?
	if( ! function_exists( 'the_gutenberg_project' ) ){
		return;
	}

	// Add 'Classic Editor' option under 'Add New' button in post.php screen.
	if( function_exists( 'gutenberg_replace_default_add_new_button' ) ){
		add_action( 'admin_print_scripts-post.php', 'gutenberg_replace_default_add_new_button' );
	}

	// Add 'Add new (classic)' link to admin menu.
	global $menu, $submenu;

	$sub = $submenu['edit.php'];
	foreach ( $sub as $k => $v ) {
		if ( is_array( $v ) && 'post-new.php' === $v['2'] ) {
			$where_is_post_new = (int) $k;
			break;
		}
	}

	$key_big_to_small = array_reverse( array_keys( $submenu['edit.php'] ) );
	foreach ( $key_big_to_small as $key ) {
		if ( ! isset( $sub[ $key ] ) ){
			continue;
		}
		if ( $key > $where_is_post_new ) {
			$sub[ $key + 1 ] = $sub[ $key ];
			unset( $sub[ $key ] );
		} else if( $key === $where_is_post_new ){
			$sub[ $key + 1 ] = $sub[ $key ];
			$sub[ $key + 1 ][0] = apply_filters( 'add_sub_post_new_classic_menu_text', $sub[ $key ][0] . ' (classic)' );
			$sub[ $key + 1 ][2] = $sub[ $key ][2] . '?classic-editor';
			break;
		}
	}

	ksort( $sub );
	$submenu['edit.php'] = $sub;

}

