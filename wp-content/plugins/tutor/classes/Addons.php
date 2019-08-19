<?php
/**
 * Addons class
 *
 * @author: themeum
 * @author_uri: https://themeum.com
 * @package Tutor
 * @since v.1.0.0
 */


namespace TUTOR;

if ( ! defined( 'ABSPATH' ) )
	exit;

class Addons {

	public function __construct() {
		add_filter('tutor_pro_addons_lists_for_display', array($this, 'tutor_addons_lists_to_show'));
	}

	public function tutor_addons_lists_to_show(){
		$addons = array(
			'wc-subscriptions' => array(
				'name'          => __('WooCommerce Subscriptions', 'tutor-pmpro'),
				'description'   => 'Capture Residual Revenue with Recurring Payments.',
			),
			'pmpro'             => array(
				'name'          => __('Paid Memberships Pro', 'tutor-pmpro'),
				'description'   => 'Maximize revenue by selling membership access to all of your courses.',
			),
			'tutor-assignments' => array(
				'name'          => __('Tutor Assignments', 'tutor-certificate'),
				'description'   => 'Tutor assignments is a great way to assign tasks to students.',
			),
			'tutor-certificate' => array(
				'name'          => __('Tutor Certificate', 'tutor-certificate'),
				'description'   => 'Student will able to download certificate of completed course',
			),
			'tutor-course-attachments' => array(
				'name'          => __('Tutor Course Attachments', 'tutor-certificate'),
				'description'   => 'Add unlimited attachments/ private files to any Tutor course',
			),
			'tutor-course-preview' => array(
				'name'          => __('Tutor Course Preview', 'tutor-certificate'),
				'description'   => 'Open some lesson to check course overview for guest',
			),
			'tutor-email' => array(
				'name'          => __('Tutor E-Mail', 'tutor-certificate'),
				'description'   => 'Send email on various tutor events',
			),
			'tutor-multi-instructors' => array(
				'name'          => __('Tutor Multi Instructors', 'tutor-certificate'),
				'description'   => 'Start a course with multiple instructors by Tutor Multi Instructors',
			),
			'tutor-prerequisites' => array(
				'name'          => __('Tutor Prerequisites', 'tutor-certificate'),
				'description'   => 'Specific course you must complete before you can enroll new course by Tutor Prerequisites',
			),
			'tutor-report' => array(
				'name'          => __('Tutor Report', 'tutor-certificate'),
				'description'   => 'Check your tutor assets performance through tutor report',
			),
		);

		return $addons;
	}


	/**
	 * @deprecated from alpha version
	 */

	public function addons_page(){
		
		if ( false === ( $addons_themes_data = get_transient( 'tutor_addons_themes_data' ) ) ) {
			//Request New
			$api_endpoint = 'https://www.themeum.com/wp-json/addon-serve/v2/get-products';
			$response = wp_remote_post( $api_endpoint, array(
					'method' => 'POST',
					'timeout' => 45,
					'user-agent' => 'Tutor/'.TUTOR_VERSION.'; '.home_url( '/' ),
					'headers' => array(
						'wp_blog' => home_url( '/' )
					),
					'body' => array('plugin_slug' => 'tutor', 'wp_blog' => home_url( '/' )),
				)
			);

			if ( is_wp_error( $response ) ) {
				$error_message = $response->get_error_message();
				echo "Something went wrong: $error_message";
			} else {
				if (tutor_utils()->avalue_dot('body', $response) && tutor_utils()->avalue_dot('response.code', $response) == 200 ){
					$api_data = tutor_utils()->avalue_dot('body', $response);

					$addons_themes_data = array(
						'last_checked_time' => time(),
						'data' => $api_data,
					);
				}
			}

			//Save the Final api call result on the database
			set_transient( 'tutor_addons_themes_data', $addons_themes_data, 6 * HOUR_IN_SECONDS );
		}


		//Finally Show the View Page
		include tutor()->path.'views/pages/addons.php';
	}

}