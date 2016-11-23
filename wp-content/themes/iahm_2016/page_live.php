<?php /* Template Name: Page live */ ?>

<?php get_header(); ?>

<script type="text/javascript" src="<?php echo get_stylesheet_directory_uri(); ?>/js/live.min.js"></script>
<script type="text/javascript" src="<?php echo get_stylesheet_directory_uri(); ?>/js/events.min.js"></script>

<main class="main" data-template="live">


	<section id="video_header">

		<div class="content">


			<article id="fbinsert" class="video" data-video="<?php echo get_field( 'video_id' ); ?>"></article>


		</div>

	</section>

	<section id="title">
		<div class="desc">
			<h1><?php echo get_field( 'title' ); ?></h1>
			<h2><?php echo get_field( 'subtitle' ); ?></h2>
		</div>
	</section>


	<?php if ( have_rows( 'speakers' ) ):

		EE_Registry::instance()->load_helper( 'People_View' );

		?>
		<section id="speakers">
			<article class="content-page espresso">


				<?php

				$speakers = get_field( 'speakers' );


				foreach ( $speakers as $speaker ) :


					$person = EEH_People_View::get_person( $speaker );

					if ( $person instanceof EE_Person ) :
						$feature_image = get_the_post_thumbnail( $person->ID() ); ?>

						<section class="speaker">

							<div class="picture">
								<?php if ( ! empty( $feature_image ) ) :
									echo $feature_image;
								endif; ?>
							</div>


							<?php

							$lang = 'en';
							$bios = get_field( 'bio', $person->ID() );

							$best_lang = $bios[0]['language'];

							if ( $best_lang != null ):

								foreach ( $bios as $bio ) {

									$lang = $bio['language'];

									if ( $lang == substr( get_bloginfo( 'language' ), 0, 2 ) ) {
										$best_lang = $lang;
										break;
									}

									if ( $lang == 'en' ) {
										$best_lang = 'en';
									}
								}

								foreach ( $bios as $bio ) {

									if ( $bio['language'] == $best_lang ) : ?>
										<div class="bio">
											<h2><?php echo $person->full_name(); ?><?php
												if ( $person->country_ID() != null ) {
													echo " (" . $person->country_ID() . ")";
												}
												?></h2>
											<p><?php echo $bio['text'] ?></p>
											<p>
												<a target="_blank"
												   href="http://<?php the_field( 'url', $person->ID() ) ?>"><?php the_field( 'url', $person->ID() ) ?></a>
											</p>
										</div>
									<?php endif; ?>
								<?php }
							endif; ?>
						</section>


					<?php endif; ?>
				<?php endforeach; ?>
			</article>
		</section>
	<?php endif; ?>

	<section id="ask_pray" class="small">

		<article class="content-page">
			<h1><?php echo get_field( 'comment_title' ); ?></h1>

			<div class="fb-comments"
			     data-href="http://healing-ministries.org/live"
			     data-width="560" data-numposts="5"></div>
		</article>
	</section>

	<section id="testimony_fb" class="small">

		<article class="content-page">

			<h1><?php echo get_field( 'quote_title' ); ?></h1>

			<?php while ( have_rows( 'quotes' ) ): the_row(); ?>

				<?php if ( get_sub_field( 'url' ) != null ): ?>

					<div class="fb-comment-embed"
					     data-href="<?php the_sub_field( 'url' ); ?>"
					     data-width="560" data-include-parent="false"></div>

				<?php endif; ?>

			<?php endwhile; ?>

			<a href="<?php echo get_field( 'button_link' ); ?>" class="button"><span><?php echo get_field( 'button_label' ); ?></span></a>

		</article>
	</section>

	<section id="follow">

		<article class="content-page">

			<h1><?php echo get_field( 'follow_us' ); ?></h1>

			<div class="fb-follow" data-href="https://www.facebook.com/iahm.international/" data-layout="button"
			     data-size="large" data-show-faces="true"></div>
		</article>
	</section>


	<section id="listOfEvents" class="small" data-nb="3">
		<article class="content-page">
			<h1>Prochaines soirées...</h1>
			<div class="insert"></div>
		</article>
	</section>

	<script id="eventsList" type="text/x-handlebars-template">

		{{#each events}}
		<div class="event">
			<div>
				<a id="{{ id }}" class="image" href="{{link}}" style="background-image: url('{{image}}')"></a>

				<h2>{{title}}</h2>
				<h3>{{formatDate date_start day="numeric" month="long" year="numeric"}}</h3>

				<a href="{{link}}">Plus d'infos</a>
			</div>
		</div>
		{{/each}}
	</script>


	<?php get_footer(); ?>
