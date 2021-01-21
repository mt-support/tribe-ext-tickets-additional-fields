<?php
/**
 * The main service provider for Additional fields support
 *
 * @since   1.0.0
 * @package Tribe\Extensions\Tickets\Additional_Fields
 */

namespace Tribe\Extensions\Tickets\Additional_Fields;

/**
 * Class Service_Provider
 *
 * @since   1.0.0
 * @package Tribe\Extensions\Tickets\Additional_Fields
 */
class Service_Provider extends \tad_DI52_ServiceProvider {

	/**
	 * Binds and sets up implementations.
	 *
	 * @since 1.0.0
	 */
	public function register() {
		$fields = new Fields( $this->container );
		$this->container->singleton( Fields::class, $fields );
		$this->container->singleton( 'tickets.additional-fields.fields', $fields );

		$this->register_hooks();

		// Register the SP on the container.
		$this->container->singleton( 'tickets.additional-fields', $this );
		$this->container->singleton( static::class, $this );
	}

	/**
	 * Registers the provider handling all the 1st level filters and actions for the extension
	 *
	 * @since 1.0.0
	 */
	protected function register_hooks() {
		$hooks = new Hooks( $this->container );
		$hooks->register();

		// Allow Hooks to be removed, by having the them registered to the container.
		$this->container->singleton( Hooks::class, $hooks );
		$this->container->singleton( 'tickets.additional-fields.hooks', $hooks );
	}
}
