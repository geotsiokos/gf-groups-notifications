# Groups Forums groups notifications
This is an addon for Groups and Groups Forums plugins.
It will send a notification email to forum members when a new topic is published.
The addon requires the <a href="http://www.itthinx.com/plugins/groups-forums/" target="_blank">Groups Forums</a> and 
 <a href="https://www.wordpress.org/plugins/groups/" target="_blank">Groups</a> plugins to be installed and activated.

## Default email subject
The default email subject is 
'A new topic has been published on ' . $forum->name . '.';
and can be modified with the filter gf_groups_notifications_subject like this:

Example
add_filter( 'gf_groups_notifications_subject', 'example_gf_groups_notifications_subject', 10, 2 );
function example_gf_groups_notifications_subject( $subject, $forum ) {
	$subject = 'You can customize the subject and use the $forum WP_Term Object to get the forum name, link, etc...';
	return $subject;
}

## Default email message
The default email message is
$message = 'Hi, <p>A new topic has been published on ' . $forum->name . '.';
$message .= esc_html( ' You can check it out by following ' );
$message .= '<a href= "' . esc_attr( get_term_link( $forum ) ) . '" target="_blank" >';
$message .= esc_html( 'this link' );
$message .= '</a></p>';
$message .= 'Cheers';
and can be modified with the filter gf_groups_notifications_message like this:

Example
add_filter( 'gf_groups_notifications_message', 'example_gf_groups_notifications_message', 10, 2 );
function example_gf_groups_notifications_subject( $message, $forum ) {
        $message = 'You can customize the message and use the $forum WP_Term Object to get the forum name, link, etc.$
        return $message;
}

