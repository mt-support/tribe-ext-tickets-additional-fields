## What and Why?

This is an extension to add additional fields for Tickets and RSVPs. Event Tickets is required.

## How?

You can add the following code to another plugin or in your theme `functions.php` file:

```php
<?php

add_filter( 'tribe_ext_tickets_additional_fields', 'custom_tickets_additional_fields' );

function custom_tickets_additional_fields( $ticket_fields ) {

		$ticket_fields['zoom_link'] = [
			'type'        => 'text',
			'label'       => esc_html__( 'Zoom link' ),
			'description' => esc_html__( 'Insert the zoom link to share a link of the meeting.' ),
		];

		$ticket_fields['another_link'] = [
			'type'        => 'text',
			'label'       => esc_html__( 'Another link' ),
			'description' => esc_html__( 'Insert another link to share a link of whatever.' ),
		];

		return $ticket_fields;
}

```

This way the additional fields will be added on the ticket box or the RSVP box under the "Additional Fields" section.

On a code basis, there's a template tag called `tribe_ext_tickets_additional_fields_get_meta()` that will allow you to get the value wherever you need it, like this:

```php
// Replace `123` with your ticket ID.
tribe_ext_tickets_additional_fields_get_meta( 123, 'zoom_link' )
```

#### Supported fields

* Text.
