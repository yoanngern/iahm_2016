<?php /* Template Name: Archive events */ ?>

<?php get_header( 'intro' ); ?>

<script type="text/javascript" src="<?php echo get_stylesheet_directory_uri(); ?>/js/events.min.js"></script>

<script src="https://cdn.polyfill.io/v2/polyfill.min.js?features=Intl.~locale.fr"></script>
<script src="<?php echo get_stylesheet_directory_uri(); ?>/js/vendor/handlebars-v4.0.5.min.js"></script>
<script src="<?php echo get_stylesheet_directory_uri(); ?>/js/vendor/handlebars-intl/handlebars-intl.min.js"></script>
<script src="<?php echo get_stylesheet_directory_uri(); ?>/js/vendor/handlebars-intl/locale-data/fr.js"></script>


<section id="content">

	<div class="platter">

		<article class="content-page">

			<h1>Prochaines soirées</h1>
			<section id="listOfEvents" data-nb="999">
				<div class="insert"></div>
			</section>

		</article>

	</div>

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

