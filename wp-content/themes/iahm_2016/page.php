<?php get_header( 'default' ); ?>


<section id="content">

	<div class="platter">

		<?php
		// TO SHOW THE PAGE CONTENTS
		while ( have_posts() ) : the_post(); ?> <!--Because the_content() works only inside a WP Loop -->
			<article class="content-page">
				<?php the_title( '<h1 class="page-title">', '</h1>' ); ?>
				<?php the_content(); ?> <!-- Page Content -->

				<?php if ( get_field( 'author' ) ):

					EE_Registry::instance()->load_helper( 'People_View' );

					?>
					<div class="author">

						<?php

						$author = get_field( 'author' );


						$person = EEH_People_View::get_person( $author );

						if ( $person instanceof EE_Person ) :
							$feature_image = get_the_post_thumbnail( $person->ID() ); ?>

							<div class="picture">
								<?php if ( ! empty( $feature_image ) ) :
									echo $feature_image;
								endif; ?>
							</div>

							<div class="text">
								<h2><?php echo $person->full_name(); ?></h2>
								<?php
								$title = get_field( 'title', $person->ID() );
								?>
								<p><?php echo $title; ?></p>
							</div>




						<?php endif; ?>
					</div>
				<?php endif; ?>

				<?php if ( have_rows( 'cards' ) ): ?>
					<div class="cards">
						<?php while ( have_rows( 'cards' ) ): the_row(); ?>

							<div class="card">
								<a href="<?php the_sub_field( 'url' ); ?>">
									<div class="image"
									     style="background-image: url('<?php the_sub_field( 'image' ); ?>')"></div>

									<h2><?php the_sub_field( 'title' ); ?></h2>
									<h3><?php the_sub_field( 'text' ); ?></h3>
								</a>
							</div>

						<?php endwhile; ?>
					</div>
				<?php endif; ?>

			</article><!-- .entry-content-page -->


			<?php
		endwhile; //resetting the page loop
		wp_reset_query(); //resetting the page query
		?>

	</div>


</section>


<?php get_footer(); ?>

