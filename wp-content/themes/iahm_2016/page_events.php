<?php /* Template Name: Page events */ ?>

<?php get_header(); ?>

<script type="text/javascript" src="<?php echo get_stylesheet_directory_uri(); ?>/js/events.min.js"></script>

<script src="https://cdn.polyfill.io/v2/polyfill.min.js?features=Intl.~locale.fr"></script>
<script src="<?php echo get_stylesheet_directory_uri(); ?>/js/vendor/handlebars-v4.0.5.min.js"></script>
<script src="<?php echo get_stylesheet_directory_uri(); ?>/js/vendor/handlebars-intl/handlebars-intl.min.js"></script>
<script src="<?php echo get_stylesheet_directory_uri(); ?>/js/vendor/handlebars-intl/locale-data/fr.js"></script>


<?php

if ( has_nav_menu( 'principal' ) ) : ?>
	<section id="subnav" class="dark">

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

				<a href="/" id="toggle"></a>
			</div>

		</div>

	</section>
<?php endif; ?>

<main class="main" data-template="events">

	<section id="title" style="background-image: url('<?php echo get_field( 'banner' ); ?>')">

		<div class="content">
			<h1><?php echo get_field( 'title' ); ?></h1>
			<h2><?php echo get_field( 'subtitle' ); ?></h2>
		</div>
	</section>

	<?php if ( have_rows( 'testimonies' ) ):

		$rows = get_field( 'testimonies' ); ?>

		<section id="testimony">
			<div class="content">
				<blockquote><?php echo $rows[0]['testimony']; ?></blockquote>
				<h2><?php echo $rows[0]['name']; ?></h2>
			</div>
		</section>

	<?php endif; ?>


	<?php if ( have_rows( 'events' ) ): ?>

		<section id="next-events">

			<ul>

				<?php while ( have_rows( 'events' ) ): the_row(); ?>

					<li>
						<?php if ( get_sub_field( 'page' ) != null ): ?>
						<a href="<?php the_sub_field( 'page' ); ?>">
							<?php endif; ?>
							<img src="<?php the_sub_field( 'image' ); ?>" alt=""/>
							<h2><?php the_sub_field( 'text' ); ?></h2>
							<?php if ( get_sub_field( 'page' ) != null ): ?>
						</a>
					<?php endif; ?>
					</li>

				<?php endwhile; ?>

			</ul>

		</section>

	<?php endif; ?>

	<?php if ( have_rows( 'testimonies' ) ):

		$rows = get_field( 'testimonies' ); ?>

		<section id="testimony">
			<div class="content">
				<blockquote><?php echo $rows[1]['testimony']; ?></blockquote>
				<h2><?php echo $rows[1]['name']; ?></h2>
			</div>
		</section>

	<?php endif; ?>

	<section id="listOfEvents" class="small" data-nb="3">
		<article class="content-page">
			<h1>Prochaines soirées Miracles et Guérisons</h1>
			<div class="insert"></div>
		</article>
	</section>

	<script id="eventsList" type="text/x-handlebars-template">


		{{#each events}}
		<div class="event">
			<a href="{{link}}">
				<div id="{{ id }}" class="image" style="background-image: url('{{image}}')"></div>

				<h2>{{title}}</h2>
				<h3>{{formatDate date_start day="numeric" month="long" year="numeric"}}</h3>
			</a>
		</div>
		{{/each}}
	</script>


	<?php get_footer(); ?>

