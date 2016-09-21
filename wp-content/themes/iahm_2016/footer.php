</main>

<footer>
	<?php

	if ( get_locale() == "fr_FR" ) {
		$nav = file_get_contents( 'http://healing-ministries.org/fr/footer_nav' );
	} else {
		$nav = file_get_contents( 'http://healing-ministries.org/en/footer_nav' );
	}

	echo $nav;
	?>
</footer>

<?php wp_footer(); ?>


</body>
</html>
