<?php ?><!DOCTYPE html>
<html <?php language_attributes(); ?> class="no-js">
<head>

	<title><?php echo get_bloginfo( 'title'); ?></title>


	<meta name="description" content="<?php echo get_bloginfo( 'description'); ?>">

	<meta charset="<?php bloginfo( 'charset' ); ?>">

	<meta name="viewport"
	      content="initial-scale=1, width=device-width, minimum-scale=1, user-scalable=no, maximum-scale=1, width=device-width, minimal-ui">
	<link rel="profile" href="http://gmpg.org/xfn/11">


	<link rel="shortcut icon" href="<?php echo get_stylesheet_directory_uri(); ?>/favicon.ico" type="image/x-icon"/>
	<link rel="apple-touch-icon" href="<?php echo get_stylesheet_directory_uri(); ?>/apple-touch-icon.png"/>
	<link rel="apple-touch-icon" sizes="57x57"
	      href="<?php echo get_stylesheet_directory_uri(); ?>/apple-touch-icon_57.png"/>
	<link rel="apple-touch-icon" sizes="72x72"
	      href="<?php echo get_stylesheet_directory_uri(); ?>/apple-touch-icon_72.png"/>
	<link rel="apple-touch-icon" sizes="76x76"
	      href="<?php echo get_stylesheet_directory_uri(); ?>/apple-touch-icon_76.png"/>
	<link rel="apple-touch-icon" sizes="114x114"
	      href="<?php echo get_stylesheet_directory_uri(); ?>/apple-touch-icon_114.png"/>
	<link rel="apple-touch-icon" sizes="120x120"
	      href="<?php echo get_stylesheet_directory_uri(); ?>/apple-touch-icon_120.png"/>
	<link rel="apple-touch-icon" sizes="144x144"
	      href="<?php echo get_stylesheet_directory_uri(); ?>/apple-touch-icon_144.png"/>
	<link rel="apple-touch-icon" sizes="152x152"
	      href="<?php echo get_stylesheet_directory_uri(); ?>/apple-touch-icon_152.png"/>

	<?php if ( is_singular() && pings_open( get_queried_object() ) ) : ?>
		<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
	<?php endif; ?>


	<script>

	</script>
	<?php wp_head(); ?>


</head>

<body <?php body_class(); ?>>

<header>

	<?php
	wp_nav_menu( array(
		'theme_location' => 'principal'
	) );
	?>

</header>