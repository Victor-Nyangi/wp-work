<?php
/*
Template Name: PageProducts
*/
?>

<?php get_header(); ?>

<?php
		if (function_exists('pagination_plugin')) :
			pagination_plugin('posts','product');

		endif;

		?>
<div id="primary" class="site-content">
    <main id="main" class="site-main" role="main">

</main><!-- #content -->
    <?php get_sidebar('content-bottom'); ?>
</div><!-- #primary -->

<?php get_sidebar(); ?>

<?php get_footer(); ?>