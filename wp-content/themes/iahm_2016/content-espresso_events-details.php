<?php
//echo '<br/><h6 style="color:#2EA2CC;">'. __FILE__ . ' &nbsp; <span style="font-weight:normal;color:#E76700"> Line #: ' . __LINE__ . '</span></h6>';
global $post;
?>



<?php
if ( apply_filters( 'FHEE__content_espresso_events_details_template__display_the_content', true ) ) {
	do_action( 'AHEE_event_details_before_the_content', $post );
	apply_filters( 'FHEE__content_espresso_events_details_template__the_content', espresso_event_content_or_excerpt() );
	do_action( 'AHEE_event_details_after_the_content', $post );
}
?>
