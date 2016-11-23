<?php
//echo '<br/><h6 style="color:#2EA2CC;">'. __FILE__ . ' &nbsp; <span style="font-weight:normal;color:#E76700"> Line #: ' . __LINE__ . '</span></h6>';
if (
	( is_single() && espresso_display_venue_in_event_details() )
	|| ( is_archive() && espresso_display_venue_in_event_list() )
) :
	global $post;
	do_action( 'AHEE_event_details_before_venue_details', $post );
	$venue_name = espresso_venue_name( 0, 'details', false );
	if ( empty( $venue_name ) && espresso_is_venue_private() ) {
		do_action( 'AHEE_event_details_after_venue_details', $post );

		return '';
	}
	?>


	<h4>Adresse</h4>
	<div class="address">
		<?php espresso_venue_address( 'multiline' ); ?>
	</div>

<?php endif; ?>
