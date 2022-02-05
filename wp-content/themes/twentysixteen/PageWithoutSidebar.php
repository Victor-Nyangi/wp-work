<?php /* Template Name: PageWithoutSidebar */ ?>

<?php get_header(); ?>

<div id="primary" class="content-area">
	<main id="main" class="site-main" role="main">
		<?php
		while ( have_posts() ) :
			the_post();
			get_template_part( 'template-parts/content/content-page' );

			// If comments are open or there is at least one comment, load up the comment template.
			if ( comments_open() || get_comments_number() ) {
				comments_template();
			}
		endwhile; ?>

    </main> <!-- .site-main -->
    <?php get_sidebar('content-bottom'); ?>

</div> <!-- .content-area -->
	
<?php get_sidebar(); ?>

<?php get_footer(); ?>

