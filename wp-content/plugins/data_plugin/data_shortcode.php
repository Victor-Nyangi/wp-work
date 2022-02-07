<?php

/**
 * Plugin Name: Data Plugin
 * Description: This is a custom data plugin.
 * Version: 1.0
 * Author: Victor
 **/

add_action('wp_enqueue_script', 'stylesheet');

// function stylesheet() {
// 	wp_register_style('style',plugins_url('style.css',__FILE__), array()); // points to the file location of the CSS file and gives it a short name.
// 	wp_enqueue_style('stylesheet'); // registers the style with WordPress
// }

// add_filter('widget_text','do_shortcode');
function data_shortcode($atts)
{
	ob_start();
	global $wpdb;

	wp_enqueue_script('exportscript', plugins_url('/exporttocsv.js', __FILE__), array('jquery'), '1.0.0.', true);

	// Shortcodes RETURN content, so store in a variable to return
	$number = 5;
	// Getting current page pagination number
	$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
	$offset = ($paged - 1) * $number;

	// $val = do_shortcode($atts['drpdwn']);
	// echo $val;
	// $val = $_POST['valu'];
	var_dump($_POST);
	// echo $val;
	$args = shortcode_atts(array(
		'order_by' => '',
		'pars' => null,
		'table' => ''
	), $atts);
	// echo $args['drpdwn'];

	if (is_null($args['pars'])) {
		$total = $wpdb->get_var("SELECT COUNT(`id`) FROM {$wpdb->prefix}$args[table]");
	} else {
		$total = $wpdb->get_var("SELECT COUNT(`id`) FROM {$wpdb->prefix}$args[table] WHERE post_type in('$args[pars]')");
	}

	if (intval($total % $number) != 0) {
		$total_pages = intval($total / $number) + 1;
	} else {
		$total_pages = intval($total / $number);
	}

	$results = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}$args[table] ORDER BY $args[order_by] DESC LIMIT $offset, $number");
?>

	<div id="primary" class="site-content">
	<!-- <a id="view_site_description">View Our Site Description</a> -->
	<a id="sort_option">Sort Option</a>
	<!-- <button style='margin-top:12px;' class='btn_csv_export'>Export</button> -->

	<main id="main" class="site-main" role="main">
			<table id="dataTable">
				<tbody>
					<tr>
						<th class="tak">ID</th>
						<th class="tak">ORDER</th>
						<th class="tak">CUSTOMER</th>
						<th class="tak">EMAIL</th>
					</tr>
					<?php
					foreach ($results as $row) {

						// Modify these to match the database structure
						$id = $row->id;
						$ord_id = $row->order_id;
						$cust_id = $row->customer_id;
						$email = $row->email;
						echo '
				<tr>
				<td class="tako">' . $id . '</td>
				<td class="tako">' . $ord_id . '</td>
				<td class="tako">' . $cust_id . '</td>
				<td class="tako">' . $email . '</td></tr>';
					}
					?>
				</tbody>
			</table>
		</main>
		<div id="pagination" class="clearfix">
			<span class="pages">Pages:</span>
			<?php
			$current_page = max(1, get_query_var('paged'));
			echo paginate_links(array(
				'base' => get_pagenum_link(1) . '%_%',
				'format' => 'page/%#%/',
				'current' => $current_page,
				'total' => $total_pages,
				'prev_next'    => false,
				'type'         => 'list',
			));
			?>
		</div>
		<button style='margin-top:12px;' id='btn_csv_export'>Export</button>
	</div>
<?php
	// return the table
	return ob_get_clean();
}

add_shortcode('data-table', 'data_shortcode');