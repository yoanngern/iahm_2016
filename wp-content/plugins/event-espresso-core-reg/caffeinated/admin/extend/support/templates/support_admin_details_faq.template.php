<div class="padding">
    <p>This page highlights frequently asked questions for Event Espresso.</p>
	<dl id="faqs">
		<dt>
			<strong><?php _e('Why do the events and registrations pages say "Not found"?', 'event_espresso'); ?></strong>
		</dt>
		<dd>
            <p>
			<?php _e('Usually, this means the WordPress Permalink rewrite rules need to be flushed. You can flush the rules by going to Settings --> Permalinks. Then click on the Save Changes button.', 'event_espresso'); ?>
            <br />
			<?php _e('Quick Link:', 'event_espresso'); ?>
			<a href="<?php echo site_url('/wp-admin/options-permalink.php'); ?>"><?php _e('Permalinks Settings', 'event_espresso'); ?></a>
            </p>
		</dd>

		<dt>
			<strong><?php _e('How do I change the part of the URL on the events pages to something other than "events"?', 'event_espresso'); ?></strong>
		</dt>
		<dd>
            <p>
			<?php _e('You can change this by going to Event Espresso > Events > Templates > Event Slug.', 'event_espresso'); ?>
			<br />
			<?php _e('Quick Link:', 'event_espresso'); ?>
			<a href="<?php echo site_url('/wp-admin/?page=espresso_events&action=template_settings'); ?>"><?php _e('Templates Manager', 'event_espresso'); ?></a>
            </p>

		</dd>
		<dt>
			<strong><?php _e('How do I add the events page to my website\'s navigation menu?', 'event_espresso'); ?></strong>
		</dt>
		<dd>
            <p>
            <?php printf( __('You can add the events or venues page as items to the navigation menu by going to Appearance --> Menus and check the boxes found under the %sEvent Espresso Pages%s section. Then click the Add to Menu button and finally click on the Save Menu button.', 'event_espresso'), '<strong>', '</strong>' ); ?>
            <br />
			<?php _e('Quick Link:', 'event_espresso'); ?>
			<a href="<?php echo site_url('/wp-admin/nav-menus.php'); ?>"><?php _e('Menu Manager', 'event_espresso'); ?></a>
		</dd>	
            </p>
		<dt>
			<strong><?php _e('I see payments for tickets in my PayPal account, but they are not marked as paid in Event Espresso. How can I fix this issue?', 'event_espresso'); ?></strong>
		</dt>
		<dd>
			<p>
				<?php _e('Here are four things you can check in your PayPal account and Event Espresso settings when payments notifications are not being sent to Event Espresso:', 'event_espresso'); ?>
			</p>
			<ol>
				<li>
					<?php _e('Make sure you have a standard or a business PayPal account, personal accounts don\'t work.', 'event_espresso'); ?>
				</li>
				<li>
					<?php _e('Turn on your IPN.', 'event_espresso'); ?>
				</li>
				<li>
					<?php _e('Make sure your PayPal account is verified.', 'event_espresso'); ?>
				</li>
				<li>
					<?php _e('Make sure your Event Espresso pages are not protected or private.', 'event_espresso'); ?>
				</li>
			</ol>
			<p class="more-info">
				<?php _e('More information can be found here:', 'event_espresso'); ?>
				<a href="https://eventespresso.com/wiki/how-to-set-up-paypal-ipn/" target="_blank"><?php _e('How to set up the PayPal IPN', 'event_espresso'); ?></a></p>
		</dd>
		<dt>
			<strong><?php _e('Only Canada and United States are appearing in the country dropdown menus. How can I change this?', 'event_espresso'); ?></strong>
		</dt>
		<dd>
            <p>
			<?php _e('The countries that appear in Event Espresso dropdown menus are set through the Countries page for Event Espresso. Locate Event Espresso in the WordPress admin menus. Then click on General Settings and click on the Countries tab. Select a country in the primary dropdown menu. Then adjust the option for Country Appears in Dropdown Select Lists and scroll down to the bottom of the page and save changes. Repeat this process for any additional countries that you would like to make changes to.', 'event_espresso'); ?>
            <br />
            <?php _e('Quick Link:', 'event_espresso'); ?>
			<a href="<?php echo site_url('/wp-admin/admin.php?page=espresso_general_settings&action=country_settings'); ?>"><?php   _e('Countries', 'event_espresso'); ?></a>
            </p>
        </dd>
	</dl>
</div>