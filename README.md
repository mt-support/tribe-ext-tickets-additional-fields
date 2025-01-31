## What and Why?

This is an extension to add additional fields for Tickets and RSVPs. Event Tickets and Event Tickets Plus are required.

## How?

You can add the following code to another plugin or in your theme `functions.php` file:

```php
<?php

add_filter( 'tribe_ext_tickets_additional_fields', 'custom_tickets_additional_fields' );

function custom_tickets_additional_fields( $ticket_fields ) {

		$ticket_fields['teacher_name'] = [
			'type'        => 'text',
			'label'       => esc_html__( 'Teacher name' ),
			'description' => esc_html__( 'Insert the name of the teacher or person hosting.' ),
		];

		$ticket_fields['teacher_email'] = [
			'type'        => 'email',
			'label'       => esc_html__( 'Teacher email' ),
			'description' => esc_html__( 'Insert the email of the teacher or person hosting.' ),
		];
		
		$ticket_fields['teacher_id'] = [
			'type'        => 'number',
			'label'       => esc_html__( 'Teacher ID' ),
			'description' => esc_html__( 'Insert the Teacher ID number.' ),
		];

		$ticket_fields['is_mandatory'] = [
			'type'        => 'checkbox',
			'label'       => esc_html__( 'Is mandatory' ),
			'description' => esc_html__( 'Check box if attendance is mandatory' ),
		];

		$ticket_fields['zoom_link'] = [
			'type'        => 'url',
			'label'       => esc_html__( 'Zoom link' ),
			'description' => esc_html__( 'Insert the zoom link to share a link of the meeting.' ),
		];

		$ticket_fields['youtube_embed'] = [
			'type'        => 'textarea',
			'label'       => esc_html__( 'Youtube Embed' ),
			'description' => esc_html__( 'Insert the youtube embed of the video they have access to when they purchase the ticket.' ),
		];

		return $ticket_fields;
}

```

This way the additional fields will be added on the ticket box or the RSVP box under the "Additional Fields" section.

On a code basis, there's a template tag called `tribe_ext_tickets_additional_fields_get_meta()` that will allow you to get the value wherever you need it, like this:

```php
// Replace `123` with your ticket ID.
tribe_ext_tickets_additional_fields_get_meta( 123, 'zoom_link' );
```

## Printing the fields

You can either use a template tag, or you can also use the the `[tribe_tickets_additional_field]` shortcode if you also have the [Event Tickets Shortcodes Extension](https://theeventscalendar.com/extensions/event-tickets-shortcodes/).

### Using the template tag

You can use the function `tribe_ext_tickets_additional_fields_get_meta()` in your code, like this:

```php
// Replace `123` with your ticket ID.
tribe_ext_tickets_additional_fields_get_meta( 123, 'zoom_link' );
```

### Using the Shortcode

As mentioned above. For this to work you'll need [Event Tickets Plus](https://theeventscalendar.com/products/wordpress-event-tickets/) installed and active on your site.

The shortcode has two required parameters, the `ticket_id` and the `field`. The `ticket_id` parameter is the ID of the ticket (post or page. The `field` parameter is the ID you use when defining the field. In the previous example: `zoom_link`, `teacher_name`, `teacher_email` and `youtube_embed`.

*Example usage:*

```
[tribe_tickets_additional_field ticket_id="123" field="zoom_link"]
```

_Where 123 is the ID of the ticket_

Finally, if by any chance you want to modify the output of the shortcode you can hook into the `tribe_tickets_additional_field_shortcode_html` filter, which parameters are the `$html`, `$ticket_id` and `$field`.

```php
add_filter( 'tribe_tickets_additional_field_shortcode_html', 'my_custom_additional_field_shortcode_printing', 10, 3 );

function my_custom_additional_field_shortcode_printing( $html, $ticket_id, $field ) {

	if ( 'zoom_link' === $field ) {
		$html = 'I do not want to print any zoom link!';
	}

	if ( 123 === $ticket_id ) {
		$html = 'I do not want to print any field for the ticket 123';
	}

	return $html;
}
```

## Supported fields

* `text` - Text.
* `textarea` - Textarea.
* `url` - URL (when being printed for the shortcode will add the HTML for the link).
* `email` - Email (when being printed for the shortcode will add the HTML for the email link).
* `number` - Number.
* `checkbox` - Checkbox.
