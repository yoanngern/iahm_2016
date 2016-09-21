<?php get_header(); ?>

<?php

$parent_id = get_ancestors( get_the_ID(), 'page' )[0];
$children  = get_pages( array( 'child_of' => $parent_id ) );


if ( count( $children ) != 0 && $parent_id != null ) : ?>

	<?php if ( has_nav_menu( 'principal' ) && ! is_child( 'events' ) ) : ?>
		<section id="subnav" class="dark main">

			<div class="container">

				<div class="block">


					<?php

					wp_nav_menu( array(
						'theme_location' => 'principal',
						'sub_menu'       => true,
						'show_parent'    => true,
						'direct_parent'  => true,
					) );


					?>
				</div>

			</div>

		</section>
	<?php endif; ?>

	<?php if ( is_child( 'events' ) ) :

		$nav = wp_nav_menu( array(
			'theme_location' => 'events',
			'sub_menu'       => true,
			'show_parent'    => true,
			'direct_parent'  => true,
			'echo'           => false,
		) );

		if ( $nav != null ):;

			?>


			<section id="subnav" class="dark events">

				<div class="container">

					<div class="block">


						<?php

						wp_nav_menu( array(
							'theme_location' => 'events',
							'sub_menu'       => true,
							'show_parent'    => true,
							'direct_parent'  => true,
						) );


						?>
					</div>

				</div>

			</section>
		<?php endif; ?>

	<?php endif; ?>


<?php endif; ?>

<main class="main">