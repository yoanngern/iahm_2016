<?php

global $post;
$event_class = has_excerpt( $post->ID ) ? ' has-excerpt' : '';
$event_class = apply_filters( 'FHEE__content_espresso_events__event_class', $event_class );


?>
<?php do_action( 'AHEE_event_details_before_post', $post ); ?>

<?php if ( is_single() ) :

	EE_Registry::instance()->load_helper( 'People_View' );
	$people = EEH_People_View::get_people_for_event();

	?>

	<h1>Soirée Miracles et Guérisons</h1>


	<time><?php espresso_event_date( 'j F Y', 'à H\hi' ); ?></time>

	<p>Nous désirons transmettre l’Evangile de Jésus-Christ d’une manière accessible avec une démonstration de la
		puissance de Dieu au travers de signes, miracles et prodiges.</p>


	<?php foreach ( $people as $type => $persons ) :


		if ( $type == "Orateur" ) :?>
			<?php foreach ( $persons as $person ) : ?>




				<?php if ( $person instanceof EE_Person ) :

					$feature_image = get_the_post_thumbnail( $person->ID() ); ?>

					<section class="speaker">
						<h2>Orateur: <?php echo $person->full_name(); ?><?php
							if($person->country_ID() != null) {
								echo " (" . $person->country_ID() . ")";
							}
							?></h2>
						<div class="picture">
							<?php if ( ! empty( $feature_image ) ) :
								echo $feature_image;
							endif; ?>
						</div>


						<?php

						$lang = 'en';
						$bios = get_field( 'bio', $person->ID() );

						//var_dump($bios);


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


		<?php endif; ?>
	<?php endforeach; ?>


	<h2>Se rendre à la soirée</h2>
	<p>Entrée libre (participation libre à l’offrande)</p>


	<?php espresso_get_template_part( 'content', 'espresso_events-venues' ); ?>
	<?php espresso_get_template_part( 'content', 'espresso_events-details' ); ?>

	<?php do_action( 'AHEE_event_details_after_post', $post ); ?>

<?php elseif ( is_archive() ) : ?>
<h2>test</h2>
<?php endif; ?>
