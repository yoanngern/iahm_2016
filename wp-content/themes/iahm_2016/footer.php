</main>

<footer>
	<?php

	if ( get_locale() == "fr_FR" ) { ?>


		<div class="content">
			<nav class="sitemap">
				<ul class="activities"><h1>Activités</h1>
					<li><a href="/evenements/calendar/">Soirées Miracles et
							Guérisons</a></li>
					<li><a href="/priere/espaces-guerison/">Espaces
							guérison</a></li>
					<li><a href="/evenements/institut/presentation/">Institut de guérison</a></li>
					<li><a href="/priere/telephone/"/priere/telephone/">Ligne de prière</a></li>
					<li><a href="/priere/online/">Demande de prière</a></li>
				</ul>
				<ul class="events"><h1>Events</h1>
					<li><a href="/evenements/calendar/">Soirées Miracles et Guérisons</a>
					</li>
					<li><a href="/evenements/netherlands/presentation/">Conférences
							AIMG</a></li>
					<li><a href="https://www.facebook.com/JLTministries/events/"
					       target="_blank">Itinéraire de Jean-Luc Trachsel</a></li>
				</ul>
				<ul class="resources"><h1>Ressources</h1>
					<li><a href="/ressources/jesus/">Connaître
							Jésus</a></li>
					<li><a href="/ressources/videos/">Nos vidéos</a></li>
					<li><a href="/ressources/temoignages/">Témoignages</a></li>
					<li><a href="/ressources/garder-sa-guerison/">Garder sa guérison</a></li>
				</ul>
				<ul class="join_us"><h1>Rejoignez-nous</h1>
					<li><a href="/partenaire/">Partenaire AIMG</a></li>
					<li><a href="/aimg/intercesseur/">Intercesseur</a></li>
					<li><a href="/aimg/benevole/">Bénévole</a></li>
				</ul>
				<ul class="about"><h1>A propos de l’AIMG</h1>
					<li><a href="/aimg/vision/">Notre vision</a></li>
					<li><a href="/aimg/confession-de-foi/">Confession de foi</a></li>
					<li><a href="/don/">Faire un don</a></li>
					<!--<li><a href="http://www.laguerison.org/contact">Contactez-nous</a></li>-->
				</ul>
				<ul class="social"><h1>Suivre l’AIMG</h1>
					<li><a href="https://www.facebook.com/iahm.international/">Facebook</a></li>
					<li><a href="https://twitter.com/aimg_iahm">Twitter</a></li>
				</ul>
			</nav>
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
						'theme_location' => 'footer-menu'
					) );
					?>
				</ul>
			</div>
		</div>


	<?php } else { ?>

		<div class="content">
			<nav class="sitemap">
				<ul class="activities"><h1>Activities</h1>
					<!--
					<li><a href="http://www.laguerison.org/activites/soiree-miracles-et-guerisons">Miracles and Healings
							nights</a></li>
					<li><a href="http://www.laguerison.org/activites/espaces-guerison/qu-est-ce-qu-un-espace-guerison">Healing
							Rooms</a></li>
					<li><a href="/institut">Healing Institute</a></li>
					<li><a href="http://www.laguerison.org/activites/priere-par-telephone">Prayer Line</a></li>-->
					<li><a href="http://healing-ministries.org/en/prayer-request">Prayer Request</a></li>
				</ul>
				<ul class="events"><h1>Events</h1>
					<!--<li><a href="http://www.laguerison.org/soirees-miracles-guerisons">Miracles and Healings nights</a>
					</li>-->
					<li><a href="http://healing-ministries.org/en/events/">Upcoming events</a></li>
					<li><a href="https://www.facebook.com/JLTministries/events/"
					       target="_blank">Jean-Luc Trachsel’s itinerary</a></li>
				</ul>
				<ul class="resources"><h1>Resources</h1>
					<li><a href="http://www.laguerison.org/presentation/connaitre-jesus/comment-devenir-un-chretien">To
							Know
							Jesus</a></li>
					<li><a href="http://healing-ministries.org/en/resources/videos/">Our videos</a></li>
					<li><a href="http://healing-ministries.org/en/resources/testimonies/">Testimonies</a></li>
					<li><a href="http://healing-ministries.org/en/resources/how-to-keep-healing/">Teachings</a></li>
					<li><a href="http://healing-ministries.org/en/resources/jesus/">How To Know Jesus</a></li>
				</ul>
				<ul class="join_us"><h1>Join us</h1>
					<li><a href="/partner">IAHM Partner</a></li>
					<li><a href="/intercessor">Intercessor</a></li>
					<li><a href="/volunteer">Volunteer</a></li>
				</ul>
				<ul class="about"><h1>About IAHM</h1>
					<li><a href="http://healing-ministries.org/en/iahm/vision/">Who are we?</a></li>
					<li><a href="http://healing-ministries.org/en/iahm/vision/">Our vision</a></li>
					<li><a href="http://healing-ministries.org/en/iahm/vision/">Our mission</a></li>
					<li><a href="http://healing-ministries.org/en/iahm/vision/">Our purposes</a></li>
					<li><a href="http://healing-ministries.org/en/partner/">Make a donation</a></li>
					<!--<li><a href="http://www.laguerison.org/contact">Contact us</a></li>-->
				</ul>
				<ul class="social"><h1>Follow IAHM</h1>
					<li><a href="https://www.facebook.com/iahm.international/">Facebook</a></li>
					<li><a href="https://twitter.com/aimg_iahm">Twitter</a></li>
				</ul>
			</nav>
			<div class="info">
				<div class="logo"><a id="logo_iahm" target="_blank" href="http://www.healing-ministries.org"></a></div>
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
						'theme_location' => 'footer-menu'
					) );
					?>
				</ul>

			</div>
		</div>


	<?php }

	?>


</footer>

<?php wp_footer(); ?>


</body>
</html>
