<?php get_header(); ?><div id="primary" class="content-area">
	<main id="main" class="site-main" role="main">
		<?php // Start the loop.
		while ( have_posts() ) : the_post();
			?>

			<article>
				<header class="entry-header">
					<h1 class="entry-title">
						<h1 class="entry-title">
							<?php the_title() ?>
						</h1>
					</h1>
				</header><!-- .entry-header -->
			</article><!-- #post-## -->

			<?php // If comments are open or we have at least one comment, load up the comment template.
			if ( comments_open() || get_comments_number() ) {
				comments_template();
			}

			// End of the loop.
		endwhile;
		?>

	</main><!-- .site-main -->

	<?php get_sidebar( 'content-bottom' ); ?>

</div><!-- .content-area --><?php get_sidebar(); ?><?php get_footer(); ?>
