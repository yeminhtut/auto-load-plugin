<?php
/*
 * Plugin Name:       Magazine Auto Load Next
 * Plugin URI:        http://magdev.tripzilla.com
 * Description:       Auto loads the next post as you scroll down to the end of a post. Replaces the URL in the address bar and the page title when viewing the next post.
 * Version:           2.0
 * Author:            Ye Min Htut
 * Author URI:        http://magdev.tripzilla.com
 */
//if(! defined('ABSPATH')) exit;  Exit if accessed directly

// function alnp_enqueue_scripts() {
// 	wp_enqueue_script( 'scrollspy', plugins_url() . '/autoloadpost/js/scrollspy.js', array('jquery'), null, true );
// 	wp_enqueue_script( 'history' , plugins_url() . '/autoloadpost/js/jquery.history.js', array('jquery'), null, true );
// 	wp_enqueue_script( 'autoloadpost', get_stylesheet_directory_uri() . '/js/autoloadpost.js', array('scrollspy'), null, true );
// }

// if ( is_singular() ) {
// 	//add_action( 'wp_enqueue_scripts', 'alnp_enqueue_scripts', 10 );
// 	echo 'calling from plugin';
// }

function alnp_add_endpoint() {
	add_rewrite_endpoint( 'partial', EP_PERMALINK );
}

add_action( 'init', 'alnp_add_endpoint' );

function partial_endpoints_activate() {

	// ensure our endpoint is added before flushing rewrite rules
	alnp_add_endpoint();

	// flush rewrite rules - only do this on activation as anything more frequent is bad!
	flush_rewrite_rules();
}

register_activation_hook( __FILE__, 'partial_endpoints_activate' );

function partial_endpoints_deactivate() {
	// flush rules on deactivate as well so they're not left hanging around uselessly
	flush_rewrite_rules();
}

register_deactivation_hook( __FILE__, 'partial_endpoints_deactivate' );


function alnp_template_redirect() {
	global $wp_query;

	// if this is not a request for partial or a singular object then bail
	if ( ! isset( $wp_query->query_vars['partial'] ) || ! is_singular() )
	return;

	// include custom template
	include get_stylesheet_directory() . '/content-partial.php';

	exit;
}

add_action( 'template_redirect', 'alnp_template_redirect' );

