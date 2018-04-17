# Groups Forums groups notifications
This is an addon for Groups and Groups Forums plugins.
It will send a notification email to forum members when a new topic is published.
The addon requires the <a href="http://www.itthinx.com/plugins/groups-forums/" target="_blank">Groups Forums</a> and 
 <a href="https://www.wordpress.org/plugins/groups/" target="_blank">Groups</a> plugins to be installed and activated.

## Default email subject
The default email subject can be modified with the filter gf_groups_notifications_subject like this:<br/>

Example<br/>
add_filter( 'gf_groups_notifications_subject', 'example_gf_groups_notifications_subject', 10, 2 );<br/>
function example_gf_groups_notifications_subject( $subject, $forum ) {<br/>
	$subject = 'You can customize the subject and use the $forum WP_Term Object to get the forum name, link, etc...';<br/>
	return $subject;<br/>
}

## Default email message
The default email message can be modified with the filter gf_groups_notifications_message like this:<br/>

Example<br/>
add_filter( 'gf_groups_notifications_message', 'example_gf_groups_notifications_message', 10, 2 );<br/>
function example_gf_groups_notifications_subject( $message, $forum ) {<br/>
        $message = 'You can customize the message and use the $forum WP_Term Object to get the forum name, link, etc...';<br/>
        return $message;<br/>
}

