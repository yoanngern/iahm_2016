</main>

<footer>


	<div class="content">


		<nav class="sitemap">

			<?php
			wp_nav_menu( array(
				'theme_location' => 'footer-menu'
			) );
			?>

		</nav>
		<?php

		if ( get_locale() == "fr_FR" ) { ?>
			<div class="info">
				<div class="logo"><a id="logo_aimg" target="_blank" href="http://healing-ministries.org/fr/"></a></div>
				<div class="address"><h1>AIMG</h1>
					<p>Route du Flon 28<br/>
						1610 Oron-La-Ville<br/>
						Suisse</p></div>
				<ul class="phone"><h1>Téléphone</h1>
					<li><a href="">+41 (0) 21 907 44 44</a></li>
					<p>Heures téléphone :<br/>
						lu-ve de 14h à 17h30</p></ul>
				<ul class="languages"><h1>Changer de langue</h1>
					<?php
					wp_nav_menu( array(
						'theme_location' => 'language-menu'
					) );
					?>
				</ul>
			</div>
		<?php } else { ?>

			<div class="info">
				<div class="logo"><a id="logo_iahm" target="_blank" href="http://healing-ministries.org/en/"></a></div>
				<div class="address"><h1>IAHM</h1>
					<p>Route du Flon 28<br/>
						1610 Oron-La-Ville<br/>
						Switzerland</p></div>
				<ul class="phone"><h1>Phone</h1>
					<li><a href="">+41 (0) 21 907 44 44</a></li>
					<p>Phone schedules :<br/>
						Mo-Fr from 2pm to 5:30pm</p></ul>
				<ul class="languages"><h1>Change language</h1>
					<?php
					wp_nav_menu( array(
						'theme_location' => 'language-menu'
					) );
					?>
				</ul>

			</div>

		<?php } ?>

	</div>


</footer>

<?php wp_footer(); ?>


</body>
</html>
