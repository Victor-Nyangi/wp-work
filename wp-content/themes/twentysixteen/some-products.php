<?php
/*
Template Name: Some Products Page
*/
?>

<?php get_header(); ?>
<div id="primary" class="site-content">
	<main id="main" class="site-main" role="main">
		<div id="primary" class="site-content">
			<div id="content" role="main">

				<?php
				$args = array();
				$number = 3;
				$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
				$offset = ($paged - 1) * $number;
				$products  = wc_get_products($args);
				$args2 = array(
					'limit' => $number,
					'offset' => $offset
				);
				$query 	= wc_get_products($args2);
				$total_products = count($products);
				$total_query = count($query);
				$total_pages = intval($total_products / $number) + 1;

				// echo '<ul id="products" class="clearfix">';

				?>
				<div class="products container mx-auto my-24 px-4 xl:px-0">
					<h2 class="products-main-title main-title mb-5 text-xl text-center uppercase">
						<span class="main-title-inner">Products</span>
					</h2>
					<?php
					foreach ($query as $q) { ?>

						<li class="user clearfix">
							<div class="user-avatar">
								<a href="<?php echo get_the_permalink($q->ID); ?>">
									<img src="<?php echo get_the_post_thumbnail_url($q->ID, 'medium'); ?>" alt="<?php echo get_the_title($q->ID); ?>">
								</a>
							</div>
							<div class="user-data">

								<h4 class="user-name">
									<a href="<?php echo get_the_permalink($q->ID); ?>">
										<?php echo get_the_title($q->ID); ?>
									</a>
								</h4>
							</div>
						</li>
					<?php }

					// echo '</ul>';

					?>
				</div>

				<?php
				if ($total_products > $total_query) {
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
				?>

				<?php
				if (function_exists('pagination_plugin')) :
					pagination_plugin('posts', 'product');
				endif;
				?>
			</div><!-- #content -->
		</div><!-- #primary -->

	</main><!-- #content -->
</div><!-- #primary -->

<?php get_sidebar(); ?>

<?php get_footer(); ?>