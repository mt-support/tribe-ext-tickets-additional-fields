<?php
/**
* Functions and template tags dedicated to tickets additional fields.
*
* @since 1.0.0
*/

if ( ! function_exists( 'tribe_ext_tickets_additional_fields_get_meta' ) ) {

	function tribe_ext_tickets_additional_fields_get_meta( $ticket_id, $meta_field ) {

		$additional_fields = tribe( 'tickets.additional-fields.fields' );

		return $additional_fields->get_field_value( $ticket_id, $additional_fields->get_field_id( $meta_field ), true );
	}
}