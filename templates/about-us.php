<?php
get_header(); ?>

<div id="primary" class="content-area">
	<main id="main" class="site-main" role="main">
		<?php
		// Start the loop.
		while ( have_posts() ) : the_post();
			?>

			<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
				<header class="entry-header">
					<h1 class="entry-title">
						<h1 class="entry-title">
							<ftb-title>About us</ftb-title>
						</h1>
					</h1>
				</header><!-- .entry-header -->

				<div style="padding-bottom: 1em;margin-bottom: 1em;border-bottom: solid lightgrey 1px;">
					<div style="padding: 12px;">
						<img src="<?php echo get_the_post_thumbnail_url( get_the_ID(), 'large' ) ?>"
						     alt="<?php echo get_post_meta( get_the_ID(), '_featured_image_caption', true ) ?>">
					</div>
					<div>
						<p style="text-align: center; font-size: small;">
							<?php echo get_post_meta( get_the_ID(), '_featured_image_caption', true ) ?>
						</p>
					</div>
				</div>

				<div class="entry-content">
					<?php
					the_content();

					wp_link_pages( array(
						'before'      => '<div class="page-links"><span class="page-links-title">' . __( 'Pages:', 'twentysixteen' ) . '</span>',
						'after'       => '</div>',
						'link_before' => '<span>',
						'link_after'  => '</span>',
						'pagelink'    => '<span class="screen-reader-text">' . __( 'Page', 'twentysixteen' ) . ' </span>%',
						'separator'   => '<span class="screen-reader-text">, </span>',
					) );
					?>
				</div><!-- .entry-content -->

				<?php
				edit_post_link( sprintf( /* translators: %s: Name of current post */
					__( 'Edit<span class="screen-reader-text"> "%s"</span>', 'twentysixteen' ),
					get_the_title() ),
					'<footer class="entry-footer"><span class="edit-link">',
					'</span></footer><!-- .entry-footer -->' );
				?>

			</article><!-- #post-## -->

			<?php
			// If comments are open or we have at least one comment, load up the comment template.
			if ( comments_open() || get_comments_number() ) {
				comments_template();
			}

			// End of the loop.
		endwhile;
		?>

	</main><!-- .site-main -->

	<?php get_sidebar( 'content-bottom' ); ?>

</div><!-- .content-area -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>

