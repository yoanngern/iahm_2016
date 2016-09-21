<?php get_header( 'default' ); ?>

	<section id="content">
		<div class="platter">

			<article class="content-page espresso">
				<?php espresso_get_template_part( 'loop', 'espresso_events' ); ?>
			</article>

		</div>
	</section>

<?php get_footer();
