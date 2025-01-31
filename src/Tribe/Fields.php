<?php
namespace Tribe\Extensions\Tickets\Additional_Fields;

use Tribe__Utils__Array as Arr;

/**
 * Class Fields
 *
 * @since 1.0.0
 *
 * @package Tribe\Extensions\Tickets\Additional_Fields
 */
class Fields {
	/**
	 * Prefix for the meta fields
	 *
	 * @since 1.0.0
	 *
	 * @var   string
	 */
	protected $meta_prefix = '_tribe_tickets_meta_';

	/**
	 * Get fields from the filter
	 */
	private function get_fields() {
		/**
		 * Filter `tribe_ext_tickets_additional_fields`
		 *
		 * @since 1.0.0
		 *
		 * @param array The ticket additional fields.
		 */
		return apply_filters( 'tribe_ext_tickets_additional_fields', [] );
	}

	/**
	 *
	 */
	public function add_fields( $post_id, $ticket_id ) {

		$additional_fields = $this->get_fields();

		// Bail if there are no additional fields.
		if ( empty( $additional_fields ) ) {
			return;
		}

		?>
		<button class="accordion-header tribe-tickets-editor-additional">
			<?php esc_html_e( 'Additional Fields', 'tribe-ext-tickets-additional-fields' ); ?>
		</button>

		<section id="tribe-tickets-editor-additional" class="additional accordion-content">

			<h4 class="accordion-label screen_reader_text">
				<?php esc_html_e( 'Additional Fields', 'tribe-ext-tickets-additional-fields' ); ?>
			</h4>

			<div class="input_block">

				<?php
					foreach ( $additional_fields as $field_id => $field_data ) :
						$this->do_field( $ticket_id, $field_id, $field_data );
					endforeach;
				?>

			</div>
		</section>
		<?php
	}

	/**
	 * Do the ticket admin field.
	 *
	 * @since 1.0.0
	 *
	 * @param int    $ticket_id  The ID of the ticket for that meta.
	 * @param string $field_id   The identifier of the meta key.
	 * @param array  $field_data The field data.
	 */
	public function do_field( $ticket_id, $field_id, $field_data ) {

		// Bail if no field ID or type.
		if (
			empty( $field_id )
			|| empty( $field_data['type'] )
		) {
			return;
		}

		// We include the admin field template according to the field type.
		$field_path = Main::PATH . '/src/admin-views/editor/input-' . $field_data['type'] . '.php';

		// Bail if there's no field for that type.
		if ( ! file_exists( $field_path ) ) {
			return;
		}

		$field_id    = $this->get_field_id( $field_id );
		$field_value = $this->get_field_value( $ticket_id, $field_id, true );

		// Include the admin field template.
		require( $field_path );
	}

	/**
	 * Get the field value. Wrapper for get_post_meta()
	 *
	 * @since 1.0.0
	 *
	 * @param int    $ticket_id The ID of the ticket for that meta.
	 * @param string $meta_key  The identifier of the meta key.
	 * @param bool   $single    If true, returns only the first value for the specified meta key.
	 *
	 * @return string
	 */
	public function get_field_value( $ticket_id, $meta_key = '', $single = false ) {
		$value = get_post_meta( $ticket_id, $meta_key, $single );

		/**
		 * Filter `tribe_ext_tickets_additional_fields_field_value`
		 *
		 * @since 1.0.0
		 *
		 * @param string $meta_key  The identifier of the meta key.
		 * @param int    $ticket_id The ID of the ticket for that meta.
		 */
		return apply_filters( 'tribe_ext_tickets_additional_fields_field_value', $value, $meta_key, $ticket_id );
	}

	/**
	 * Intercept the ticket save and save the additional fields.
	 *
	 * @since 1.0.0
	 * @since 1.1.0 Add support for checkbox and number fields.
	 *
	 * @param int    $post_id The post ID.
	 * @param object $ticket The ticket.
	 * @param array  $data the data being saved for the ticket.
	 */
	public function save_fields( $post_id, $ticket, $data ) {
		$additional_fields = $this->get_fields();

		// Bail if empty, nothing to save/update.
		if ( empty( $additional_fields ) ) {
			return;
		}

		if ( empty( $ticket->ID ) ) {
			return;
		}

		foreach ( $additional_fields as $field_id => $field_data ) {

			// Bail if no field ID or type.
			if (
				empty( $field_id )
				|| empty( $field_data['type'] )
			) {
				continue;
			}

			$field_id = $this->get_field_id( $field_id );

			// Handle number fields
			if ( $field_data['type'] === 'number' ) {
				$data[ $field_id ] = isset( $data[ $field_id ] ) ? floatval( $data[ $field_id ] ) : 0;
			}

			// Handle checkbox fields
			if ( $field_data['type'] === 'checkbox' ) {
				$data[ $field_id ] = isset( $data[ $field_id ] ) ? '1' : '0';
			}

			update_post_meta( $ticket->ID, $field_id, $data[ $field_id ] );
		}
	}

	/**
	 * Let's set a unique ID with our private meta prefix.
	 *
	 * @since 1.0.0
	 *
	 * @param string $meta_key The identifier of the meta key.
	 *
	 * @return string
	 */
	public function get_field_id( $meta_key ) {
		return $this->meta_prefix . $meta_key;
	}

	/**
	 * Return the field type
	 *
	 * @since 1.0.0
	 *
	 * @param string $meta_key The identifier of the meta key.
	 *
	 * @return bool|string
	 */
	public function get_field_type( $meta_key ) {
		$additional_fields = $this->get_fields();

		$field = Arr::get( $additional_fields, $meta_key, false );

		if ( empty( $field ) ) {
			return false;
		}

		return $field['type'];
	}

	/**
	 * Let's check if the field meta key exists.
	 *
	 * @since 1.0.0
	 *
	 * @param string $meta_key The identifier of the meta key.
	 *
	 * @return bool
	 */
	public function field_exist( $meta_key ) {
		$additional_fields = $this->get_fields();

		return ! empty( Arr::get( $additional_fields, $meta_key, false ) );

	}
}
