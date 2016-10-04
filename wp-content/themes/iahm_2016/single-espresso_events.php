

<?php get_header('event'); ?>

<section id="content">

	<div class="platter">

		<?php
		// Start the Loop.
		while ( have_posts() ) : the_post(); ?>

			<article class="content-page espresso">

				<?php espresso_get_template_part( 'content', 'espresso_events' ); ?>

			</article>

			<?php

		endwhile;
		?>

	</div>

</section>


<?php get_footer(); ?>

