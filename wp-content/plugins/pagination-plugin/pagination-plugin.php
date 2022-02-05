<?php

/**
 * Plugin Name: Pagination Plugin
 * Description: This is a pagination plugin.
 * Version: 1.0
 * Author: Victor
 **/

add_action('pagination_plugin', 'pagination_plugin');

add_action('wp_enqueue_script', 'stylesheet');

// add_shortcode('list-table', 'list_shortcode');

// function stylesheet() {
// 	wp_register_style('style',plugins_url('style.css',__FILE__), array()); // points to the file location of the CSS file and gives it a short name.
// 	wp_enqueue_style('stylesheet'); // registers the style with WordPress
// }


// include( plugin_dir_path( __FILE__ ) . ‘pagination_plugin.php’);

// add_filter('widget_text','do_shortcode');

function pagination_plugin($table, $pars, $total = '')
{
	global $wpdb;
	$number = 3;
	
	if (is_null($pars)) {
		$total   = $wpdb->get_var("SELECT COUNT(`id`) FROM {$wpdb->prefix}$table");
	} else {
		$total   = $wpdb->get_var("SELECT COUNT(`id`) FROM {$wpdb->prefix}$table WHERE post_type in('$pars')");
	}

	if (intval($total / $number) % 2 == 0) {
		$total_pages = intval($total / $number);
	} else {
		$total_pages = intval($total / $number) + 1;
	}

	echo '<div id="pagination" class="clearfix">';
	echo '<span class="pages">Pages:</span>';
	$current_page = max(1, get_query_var('paged'));
	echo paginate_links(array(
		'base' => get_pagenum_link(1) . '%_%',
		'format' => 'page/%#%/',
		'current' => $current_page,
		'total' => $total_pages,
		'prev_next'    => false,
		'type'         => 'list',
	));
	echo '</div>';
}

// function sorting($args)
// {
// 	global $wpdb;

// 	$args = array(
// 		‘numberposts’ => 9999,
// 		‘post_type’ => ‘portfolio’,
// 		‘order’ => ‘DESC’,
// 		‘orderby’ => ‘post_date’,
// 		‘post_status’ => ‘publish’
// 		);
		
// 		$portfolio = wp_get_recent_posts( $args );
// 		$total   = $wpdb->get_var("SELECT COUNT(`id`) FROM {$wpdb->prefix}$table WHERE post_type in('$pars')");

// }

// $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}posts LIMIT $offset, $limit" );