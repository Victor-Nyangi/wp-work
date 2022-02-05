<?php

// this function generates the shortcode output

// function dropdown_shortcode($atts)
// {
// 	ob_start();
// 	$args = shortcode_atts(array(
// 		'text' => '',
// 		'value' => ''
// 	), $atts);
// 	echo '<option>' . $args['text'] . '</option>';
// 	return $atts['value'];
// }

// add_shortcode('dropdown-code', 'dropdown_shortcode');

function list_shortcode($atts)
{
	ob_start();
	global $wpdb;
	// Shortcodes RETURN content, so store in a variable to return
	$number = 3;
	// Getting current page pagination number
	$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
	$offset = ($paged - 1) * $number;

	// $val = do_shortcode($atts['drpdwn']);
	// echo $val;
	$val = $_POST['valu'];
	var_dump($_POST);
	// echo $val;
	$args = shortcode_atts(array(
		'order_by' => '',
		'drpdwn' => ''
	), $atts);
	echo $args['drpdwn'];

	$results = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}orders_report ORDER BY $args[order_by] DESC LIMIT $offset, $number");

?>
	<table>
		<tbody>
			<tr>
				<td class="tak">ID</td>
				<td class="tak">ORDER</td>
				<td class="tak">CUSTOMER</td>
				<td class="tak">EMAIL</td>
			</tr>
		<?php
		foreach ($results as $row) {

			// Modify these to match the database structure
			$id = $row->id . '</br>';
			$ord_id = $row->order_id . '</br>';
			$cust_id = $row->customer_id . '</br>';
			$email = $row->email . '</br>';

			echo '
				<tr>
				<td class="tako">' . $id . '</td>
				<td class="tako">' . $ord_id . '</td>
				<td class="tako">' . $cust_id . '</td>
				<td class="tako">' . $email . '</td></tr>';
		}

		echo '
	</tbody>
	</table>';
		// return the table
		return ob_get_clean();
	}
