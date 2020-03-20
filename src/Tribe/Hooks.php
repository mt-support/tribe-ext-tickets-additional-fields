<?php
/**
 * Handles hooking all the actions and filters used by the module.
 *
 * To remove a filter:
 * remove_filter( 'some_filter', [ tribe( Tribe\Extensions\Tickets\Additional_Fields\Hooks::class ), 'some_filtering_method' ] );
 * remove_filter( 'some_filter', [ tribe( 'tickets.additional-fields.hooks' ), 'some_filtering_method' ] );
 *
 * To remove an action:
 * remove_action( 'some_action', [ tribe( Tribe\Extensions\Tickets\Additional_Fields\Hooks::class ), 'some_method' ] );
 * remove_action( 'some_action', [ tribe( 'tickets.additional-fields.hooks' ), 'some_method' ] );
 *
 * @since 1.0.0
 *
 * @package Tribe\Extensions\Tickets\Additional_Fields
 */

namespace Tribe\Extensions\Tickets\Additional_Fields;

/**
 * Class Hooks
 *
 * @since 1.0.0
 *
 * @package Tribe\Extensions\Tickets\Additional_Fields
 */
class Hooks extends \tad_DI52_ServiceProvider {

	/**
	 * Binds and sets up implementations.
	 *
	 * @since 1.0.0
	 */
	public function register() {

		$this->add_actions();
		$this->add_filters();
	}

	/**
	 * Adds the required actions.
	 *
	 * @since 1.0.0
	 */
	protected function add_actions() {
		add_action( 'tribe_events_tickets_metabox_edit_accordion_content', [ $this, 'action_tickets_fields_add_fields' ], 10, 2 );
		add_action( 'tribe_tickets_ticket_add', [ $this, 'action_tickets_fields_save_fields' ], 10, 3 );
	}

	/**
	 * Adds the required filters.
	 *
	 * @since 1.0.0
	 */
	protected function add_filters() {}

	/**
	 * Add the additional fields to the tickets/rsvp metabox.
	 *
	 * @since 1.0.0
	 *
	 * @param int $post_id   The ID of the post associated with the ticket.
	 * @param int $ticket_id The ID of the ticket.
	 */
	public function action_tickets_fields_add_fields( $post_id, $ticket_id ) {
		$this->container->make( Fields::class )->add_fields( $post_id, $ticket_id );
	}

	/**
	 * Hook into the ticket save/update so we can add the meta fields.
	 *
	 * @since 1.0.0
	 *
	 * @param int $post_id The ID of the post associated to the ticket.
	 * @todo add params
	 */
	public function action_tickets_fields_save_fields( $post_id, $ticket, $data ) {
		$this->container->make( Fields::class )->save_fields( $post_id, $ticket, $data );
	}
}
