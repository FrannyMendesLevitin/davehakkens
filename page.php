<?php
	get_header();
	get_template_part( 'navbar' );
?>

<div id="content">

	<div id="topbar">

		<?php
			if( function_exists( 'yoast_breadcrumb' ) ) {
				yoast_breadcrumb( '<p id="breadcrumbs">','</p>' );
			}
		?>

	</div>

	<div class="post">

		<?php
			if( have_posts() ) : while ( have_posts() ) : the_post();
			$post_meta = get_post_meta( $post->ID );
		?>

			<?php $thumbnail_url = wp_get_attachment_url( get_post_thumbnail_id( $post->ID ) ); ?>

			<div style="background-image: url('<?php echo $thumbnail_url; ?>');" class="thumbnail">
				<div class="shadow"></div>
				<div class="meta">

					<h1><?php the_title(); ?></h1>

					<?php if (isset($post_meta['subtitle'][0])): ?>
						<h3><?php echo $post_meta['subtitle'][0]; ?></h3>
					<?php endif; ?>

				</div>
			</div>

			<div class="post-content">
				<?php the_content(); ?>
				<?php edit_post_link(); ?>
			</div>

		<?php endwhile; endif; ?>

	</div>
</div>

<?php get_footer(); ?>
