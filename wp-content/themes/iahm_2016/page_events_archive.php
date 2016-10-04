<?php /* Template Name: Archive events */ ?>

<?php get_header( 'intro' ); ?>

<script type="text/javascript" src="<?php echo get_stylesheet_directory_uri(); ?>/js/events.min.js"></script>

<section id="content">

	<div class="platter">

		<article class="content-page">

			<h1>Prochaines soirées</h1>
			<section id="listOfEvents" data-nb="9">
				<div class="insert"></div>
			</section>

		</article>

	</div>

</section>

<script id="eventsList" type="text/x-handlebars-template">


	{{#each events}}
	<div class="event">
		<div>
			<a id="{{ id }}" class="image" href="{{link}}" style="background-image: url('{{image}}')"></a>

			<h2>{{title}}</h2>
			<h3>{{formatDate date_start day="numeric" month="long" year="numeric"}}</h3>

			<a href="{{link}}" class="button">Plus d'infos</a>
		</div>
	</div>
	{{/each}}
</script>

<?php get_footer(); ?>

