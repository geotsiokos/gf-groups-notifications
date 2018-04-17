<?php
/**
 * gf-groups-notifications.php
 *
 * Plugin Name: Groups Forums Notifications
 * Plugin URI: http://www.itthinx.com/plugins/groups-forums
 * Description: Notify forum group members when a topic is published
 * Author: gtsiokos
 * Author URI: http://www.netpad.gr
 *
 * This code is provided subject to the license granted.
 * Unauthorized use and distribution is prohibited.
 * See COPYRIGHT.txt and LICENSE.txt
 *
 * This code is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 * This header and all notices must be kept intact.
 *
 * @author gtsiokos
 * @package gf-groups-notifications
 * Version: 1.0.0
 */

if ( !defined( 'ABSPATH' ) ) {
	exit;
}

Class Gf_Groups_Notifications {
	/**
	 * Initializes the plugin
	 */
	public static function init() {
		if ( self::check_dependencies() ) {
			add_filter( 'transition_post_status', array( __CLASS__, 'transition_post_status' ), 10, 3 );
		}
	}

	/**
	 * Checks if Groups Forums is activated.
	 * @return boolean if Groups Forums is there, false otherwise
	 */
	public static function check_dependencies() {
		$active_plugins = get_option( 'active_plugins', array() );
		//$groups_is_active = in_array( 'groups/groups.php', $active_plugins );
		$groups_forums_is_active = in_array( 'groups/groups.php', $active_plugins );
		if ( !$groups_forums_is_active ) {
			self::$admin_messages[] =
				'<div class="error">' .
				__( '<strong>Groups Forums Notifications</strong> requires the <a href="http://www.itthinx.com/plugins/groups/">Groups Forums</a> plugin. Please install and activate it.', GROUPS_FORUMS_PLUGIN_DOMAIN ) .
				'</div>';
		}
		return $groups_forums_is_active;
	}

	/**
	 * Prints admin notices.
	 */
	public static function admin_notices() {
		if ( !empty( self::$admin_messages ) ) {
			foreach ( self::$admin_messages as $msg ) {
				echo wp_kses(
					$msg,
					array(
						'strong' => array(),
						'div' => array( 'class' ),
						'a' => array( 
							'href' => array()
						),
						'div' => array(
							'class' => array()
						),
					)
				);
			}
		}
	}

	/**
	 * Checks topic status for new forum topics
	 * @param string $new_status
	 * @param string $old_status
	 * @param string $post
	 * @return string $new_status
	 */
	public static function transition_post_status( $new_status, $old_status, $post ) {
		if ( $old_status != 'publish' && $new_status == 'publish' ) {
			if ( $post->post_type == 'topic' ) {
				// First we need to retrieve the forum(s)
				// where the topic belongs to
				$topic_terms = wp_get_post_terms( $post->ID, 'forum' );
				if( !is_wp_error( $topic_terms ) && count( $topic_terms ) > 0 ) {
					$gf_options = get_option( 'groups_forums_options' );
					if ( is_array( $gf_options ) ) {
						foreach ( $topic_terms as $topic_term ) {
							if ( isset( $gf_options['terms'][$topic_term->term_id] ) ) {
								foreach( $gf_options['terms'][$topic_term->term_id] as $group_id ) {
									self::send_notification( $group_id, $topic_term );
								}
							}
						}
					}
				}
			}
		}

		return $new_status;
	}

	/**
	 * Send the notification email to group members
	 *
	 * @param int $group_id
	 * @param WP_Post_Type $forum
	 */
	public static function send_notification( $group_id, $forum ) {
		$members = self::get_group_users( $group_id );
		if ( $members ) {
			$default_subject = '';
			$default_message = '';

			$default_subject = esc_html( 'A new topic has been published on ' );
			$default_subject .= $forum->name;

			$default_message .= 'Hi, <p>A new topic has been published on ' . $forum->name . '.';
			$default_message .= esc_html( ' You can check it out by following ' );
			$default_message .= '<a href= "' . esc_attr( get_term_link( $forum ) ) . '" target="_blank" >';
			$default_message .= esc_html( 'this link' );
			$default_message .= '</a></p>';
			$default_message .= 'Cheers';

			$headers = array('Content-Type: text/html; charset=UTF-8');

			$subject = apply_filters( 'gf_groups_notifications_subject', $default_subject, $forum );
			$message = apply_filters( 'gf_groups_notifications_message', $default_message, $forum );
			
			foreach ( $members as $member ) {
				wp_mail(
					$member->user_email,
					$subject,
					$message,
					$headers
				);
			}
		}
	}

	/**
	 * Get the users of a group
	 *
	 * @param int $group_id
	 * @return array of Groups_User objects
	 */
	public static function get_group_users( $group_id ) {
		$group = new Groups_Group( $group_id );

		return $group->users;
	}
} Gf_Groups_Notifications::init();