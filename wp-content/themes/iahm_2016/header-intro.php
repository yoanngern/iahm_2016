<?php get_header( 'subnav' ); ?>


<section id="bigimage">


	<?php

	$banner = get_field( 'banner' );

	$frontpage_id = get_option( 'page_on_front' );


	while ( empty( $banner ) ) {

		$id = get_ancestors( get_the_ID(), 'page' )[0];

		$banner = get_field( 'banner', $id );

		if ( $id == 0 ) {

			$id     = $frontpage_id;
			$banner = get_field( 'banner', $id );

			break;
		}
	}

	if ( ! empty( $banner ) ): ?>

		<div class="banner"
		   style="background-image: url('<?php echo $banner; ?>')"></div>
	<?php endif;

	$title = get_field( 'title' );

	while ( empty( $title ) ) {

		$id = get_ancestors( get_the_ID(), 'page' )[0];

		$title = get_field( 'title', $id );

		if ( $id == 0 ) {

			$id    = $frontpage_id;
			$title = get_field( 'title', $id );

			break;
		}
	}

	if ( ! empty( $title ) ): ?>

		<a href="<?php echo get_permalink(); ?>" class="header_title"
		   style="background-image: url('<?php echo $title; ?>')"></a>
	<?php endif; ?>

</section>