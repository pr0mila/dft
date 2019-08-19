<?php
namespace Tutor;

if ( ! defined( 'ABSPATH' ) )
	exit;

class Options {

	public $option;
	public $options_attr;

	public function __construct() {
		$this->option = (array) maybe_unserialize(get_option('tutor_option'));
		$this->options_attr = $this->options_attr();

		//Saving option
		add_action('wp_ajax_tutor_option_save', array($this, 'tutor_option_save'));
	}

	private function get($key = null, $default = false){
		$option = $this->option;
		if (empty($option) || ! is_array($option)){
			return $default;
		}
		if ( ! $key){
			return $option;
		}
		if (array_key_exists($key, $option)){
			return apply_filters($key, $option[$key]);
		}
		//Access array value via dot notation, such as option->get('value.subvalue')
		if (strpos($key, '.')){
			$option_key_array = explode('.', $key);
			$new_option = $option;
			foreach ($option_key_array as $dotKey){
				if (isset($new_option[$dotKey])){
					$new_option = $new_option[$dotKey];
				}else{
					return $default;
				}
			}
			return apply_filters($key, $new_option);
		}

		return $default;
	}

	public function tutor_option_save(){
		if ( ! isset($_POST['_wpnonce']) || ! wp_verify_nonce( $_POST['_wpnonce'], 'tutor_option_save' ) ){
			exit();
		}

		do_action('tutor_option_save_before');

		$option = (array) isset($_POST['tutor_option']) ? $_POST['tutor_option'] : array();
		$option = apply_filters('tutor_option_input', $option);
		update_option('tutor_option', $option);

		do_action('tutor_option_save_after');
		//re-sync settings
		//init::tutor_activate();

		wp_send_json_success( array('msg' => __('Option Updated', 'tutor') ) );
	}

	public function options_attr(){
		$pages = tutor_utils()->get_pages();

		//$course_base = tutor_utils()->course_archive_page_url();
		$lesson_url = site_url().'/course/'.'sample-course/<code>lessons</code>/sample-lesson/';
		$student_url = tutor_utils()->profile_url();
		$attempts_allowed = array();
		$attempts_allowed['unlimited'] = __('Unlimited' , 'tutor');
		$attempts_allowed = array_merge($attempts_allowed, array_combine(range(1,20), range(1,20)));

		$attr = array(
			'general' => array(
				'label'     => __('General', 'tutor'),
				'sections'    => array(
					'general' => array(
						'label' => __('General', 'tutor'),
						'desc' => __('General Settings', 'tutor'),
						'fields' => array(
							'tutor_dashboard_page_id' => array(
								'type'          => 'select',
								'label'         => __('Dashboard Page', 'tutor'),
								'default'       => '0',
								'options'       => $pages,
								'desc'          => __('This page will be used for student and instructor dashboard', 'tutor'),
							),
							'enable_public_profile' => array(
								'type'      => 'checkbox',
								'label'     => __('Public Profile', 'tutor'),
								'label_title' => __('Enable', 'tutor'),
								'default' => '0',
								'desc'      => __('Enable this to make a profile publicly visible',	'tutor')."<br />" .$student_url,
							),
							'load_tutor_css' => array(
								'type'      => 'checkbox',
								'label'     => __('Load Tutor CSS', 'tutor'),
								'label_title' => __('Enable', 'tutor'),
								'default' => '1',
								'desc'      => __('If your theme has its own styling, then you can turn it off to load CSS from the plugin directory', 'tutor'),
							),
							'load_tutor_js' => array(
								'type'      => 'checkbox',
								'label'     => __('Load Tutor JavaScript', 'tutor'),
								'label_title' => __('Enable', 'tutor'),
								'default' => '1',
								'desc'      => __('If you have put required script in your theme javascript file, then you can turn it off to load JavaScript from the plugin directory', 'tutor'),
							),
							'student_must_login_to_view_course' => array(
								'type'      => 'checkbox',
								'label'     => __('Course Visibility', 'tutor'),
								'label_title' => __('Logged in only', 'tutor'),
								'desc'      => __('Students must be logged in to view course', 'tutor'),
							),
							'delete_on_uninstall' => array(
								'type'      => 'checkbox',
								'label'     => __('Erase upon uninstallation', 'tutor'),
								'label_title' => __('Enable', 'tutor'),
								'desc'      => __('Delete all data during uninstall', 'tutor'),
							),

							'enable_spotlight_mode' => array(
								'type'      => 'checkbox',
								'label'     => __('Spotlight mode', 'tutor'),
								'label_title' => __('Enable', 'tutor'),
								'default' => '0',
								'desc'      => __('This will hide the header & footer and enable Spotlight (full-screen) mode for the course learning interface.',	'tutor'),
							),
							'disable_default_player_youtube' => array(
								'type'      => 'checkbox',
								'label'     => __('YouTube Player', 'tutor'),
								'label_title' => __('Disable default video player on the youtube video', 'tutor'),
								'default' => '0',
								'desc'      => __('',	'tutor'),
							),
							'disable_default_player_vimeo' => array(
								'type'      => 'checkbox',
								'label'     => __('Vimeo Player', 'tutor'),
								'label_title' => __('Disable default video player on the vimeo video', 'tutor'),
								'default' => '0',
								'desc'      => __('',	'tutor'),
							),
						)
					)
				),
			),
			'course' => array(
				'label'     => __('Course', 'tutor'),
				'sections'    => array(
					'general' => array(
						'label' => __('General', 'tutor'),
						'desc' => __('Course Settings', 'tutor'),
						'fields' => array(
							'enable_gutenberg_course_edit' => array(
								'type'      => 'checkbox',
								'label'     => __('Gutenberg Editor', 'tutor'),
								'label_title'   => __('Enable', 'tutor'),
								'desc' => __('Use Gutenberg editor on course description area.', 'tutor'),
							),
							'display_course_instructors' => array(
								'type'      => 'checkbox',
								'label'     => __('Display Instructor Info', 'tutor'),
								'label_title'   => __('Enable', 'tutor'),
								'desc' => __('Show tutor bio on each course page.', 'tutor'),
							),
							'enable_q_and_a_on_course' => array(
								'type'      => 'checkbox',
								'label'     => __('Question and Answer', 'tutor'),
								'label_title' => __('Enable','tutor'),
								'default'   => '0',
								'desc'      => __('Enabling this feature will add a Q&amp;A section on every course.',	'tutor'),
							),
						),
					),
					'archive' => array(
						'label' => __('Archive', 'tutor'),
						'desc' => __('Course Archive Settings', 'tutor'),
						'fields' => array(
							'course_archive_page' => array(
								'type'      => 'select',
								'label'     => __('Course Archive Page', 'tutor'),
								'default'   => '0',
								'options'   => $pages,
								'desc'      => __('This page will be used to list all the published courses.',	'tutor'),
							),
							'courses_col_per_row' => array(
								'type'      => 'slider',
								'label'     => __('Column per row', 'tutor'),
								'default'   => '4',
								'options'   => array('min'=> 1, 'max' => 6),
								'desc'      => __('Define how many column you want to show on the course single page', 'tutor'),
							),
							'courses_per_page' => array(
								'type'      => 'slider',
								'label'     => __('Courses Per Page', 'tutor'),
								'default'   => '12',
								'options'   => array('min'=> 1, 'max' => 20),
								'desc'      => __('Define how many courses you want to show per page', 'tutor'),
							),
						),
					),
				),
			),
			'lesson' => array(
				'label' => __('Lessons', 'tutor'),
				'sections'    => array(
					'lesson_settings' => array(
						'label' => __('Lesson Settings', 'tutor'),
						'desc' => __('Lesson settings will be here', 'tutor'),
						'fields' => array(
							'lesson_permalink_base' => array(
								'type'      => 'text',
								'label'     => __('Lesson Permalink Base', 'tutor'),
								'default'   => 'lessons',
								'desc'      => $lesson_url,
							),

						),
					),

				),
			),
			'quiz' => array(
				'label' => __('Quiz', 'tutor'),
				'sections'    => array(
					'general' => array(
						'label' => __('Quiz', 'tutor'),
						'desc' => __('The values you set here define the default values that are used in the settings form when you create a new quiz.', 'tutor'),
						'fields' => array(
							'quiz_time_limit' => array(
								'type'      => 'group_fields',
								'label'     => __('Time Limit', 'tutor'),
								'desc'      => __('Default time limit for quizzes. 0 means no time limit.', 'tutor'),
								'group_fields'  => array(
									'value' => array(
										'type'      => 'text',
										'default'   => '0',
									),
									'time' => array(
										'type'      => 'select',
										'default'   => 'minutes',
										'select_options'   => false,
										'options'   => array(
											'weeks'     =>  __('Weeks', 'tutor'),
											'days'      =>  __('Days', 'tutor'),
											'hours'     =>  __('Hours', 'tutor'),
											'minutes'   =>  __('Minutes', 'tutor'),
											'seconds'   =>  __('Seconds', 'tutor'),
										),
									),
								),
							),
							'quiz_when_time_expires' => array(
								'type'      => 'radio',
								'label'      => __('When time expires', 'tutor'),
								'default'   => 'minutes',
								'select_options'   => false,
								'options'   => array(
									'autosubmit'    =>  __('Current attempts are submitted automatically', 'tutor'),
									'graceperiod'   =>  __('There is a grace period when current attempts can be submitted, but no more questions answered', 'tutor'),
									'autoabandon'   =>  __('Attempts must be submitted before time expires, otherwise they will not be counted', 'tutor'),
								),
								'desc'  => __('What should happen by default if a student does not submit the quiz before time expires.', 'tutor'),
							),
							'quiz_attempts_allowed' => array(
								'type'      => 'number',
								'label'      => __('Attempts allowed', 'tutor'),
								'default'   => '10',
								'desc'  => __('Restriction on the number of attempts students are allowed to take for a quiz. 0 for no limit', 'tutor'),
							),
							'quiz_grade_method' => array(
								'type'      => 'select',
								'label'      => __('Grading method', 'tutor'),
								'default'   => 'minutes',
								'select_options'   => false,
								'options'   => array(
									'highest_grade' => __('Highest Grade', 'tutor'),
									'average_grade' => __('Average Grade', 'tutor'),
									'first_attempt' => __('First Attempt', 'tutor'),
									'last_attempt' => __('Last Attempt', 'tutor'),
								),
								'desc'  => __('When multiple attempts are allowed, which method should be used to calculate a student\'s final grade for the quiz.', 'tutor'),
							),
						)
					)
				),
			),
			'instructors' => array(
				'label'     => __('Instructors', 'tutor'),
				'sections'    => array(
					'general' => array(
						'label' => __('Instructor Profile Settings', 'tutor'),
						'desc' => __('Enable Disable Option to on/off notification on various event', 'tutor'),
						'fields' => array(
							'enable_course_marketplace' => array(
								'type'      => 'checkbox',
								'label'     => __('Course Marketplace', 'tutor'),
								'label_title' => __('Enable', 'tutor'),
								'default' => '0',
								'desc'      => __('By enabling this settings will allow multiple instructors can upload their course.',	'tutor'),
							),
							'instructor_register_page' => array(
								'type'      => 'select',
								'label'     => __('Instructor Registration Page', 'tutor'),
								'default'   => '0',
								'options'   => $pages,
								'desc'      => __('This page will be used to sign up new instructors.', 'tutor'),
							),
							'instructor_can_publish_course' => array(
								'type'      => 'checkbox',
								'label'     => __('Can publish course', 'tutor'),
								'default' => '0',
								'desc'      => __('Define if a instructor can publish his courses directly or not, if unchecked, they can still add courses, but it will go to admin for review',	'tutor'),
							),
							'enable_become_instructor_btn' => array(
								'type'      => 'checkbox',
								'label'     => __('Become Instructor Button', 'tutor'),
								'label_title' => __('Enable', 'tutor'),
								'default' => '0',
								'desc'      => __('Uncheck this option to hide the button from student dashboard.',	'tutor'),
							),
						),
					),
				),
			),
			'students' => array(
				'label'     => __('Students', 'tutor'),
				'sections'    => array(
					'general' => array(
						'label' => __('Student Profile settings', 'tutor'),
						'desc' => __('Enable Disable Option to on/off notification on various event', 'tutor'),
						'fields' => array(
							'student_register_page' => array(
								'type'          => 'select',
								'label'         => __('Student Registration Page', 'tutor'),
								'default'       => '0',
								'options'       => $pages,
								'desc'          => __('Choose the page for student registration page', 'tutor'),
							),
							'students_own_review_show_at_profile' => array(
								'type'          => 'checkbox',
								'label'         => __('Show reviews on profile', 'tutor'),
								'label_title'   => __('Enable', 'tutor'),
								'default'       => '0',
								'desc'          => __('Enabling this will show the reviews written by each student on their profile', 'tutor')."<br />" .$student_url,
							),
							'show_courses_completed_by_student' => array(
								'type'          => 'checkbox',
								'label'         => __('Show Completed Courses', 'tutor'),
								'label_title'   => __('Enable', 'tutor'),
								'default'       => '0',
								'desc'          => __('Completed courses will be show on student profile',	'tutor')."<br />".$student_url,
							),
						),
					),
				),
			),
			'tutor_earning' => array(
				'label'     => __('Earning', 'tutor'),
				'sections'    => array(
					'general' => array(
						'label' => __('Earning and commission allocation', 'tutor'),
						'desc' => __('Enable Disable Option to on/off notification on various event', 'tutor'),
						'fields' => array(
							'enable_tutor_earning' => array(
								'type'          => 'checkbox',
								'label'         => __('Earning', 'tutor'),
								'label_title'   => __('Enable', 'tutor'),
								'default'       => '0',
								'desc'          => __('If disabled, the Admin will receive 100% of the earning',	'tutor'),
							),
							'earning_admin_commission' => array(
								'type'      => 'number',
								'label'      => __('Admin Commission Percentage', 'tutor'),
								'default'   => '20',
								'desc'  => __('Define the commission of the Admin from each sale.(after deducting fees)', 'tutor'),
							),
							'earning_instructor_commission' => array(
								'type'      => 'number',
								'label'      => __('Instructor Commission Percentage', 'tutor'),
								'default'   => '80',
								'desc'  => __('Define the commission for instructors from each sale.(after deducting fees)', 'tutor'),
							),
							'tutor_earning_fees' => array(
								'type'      => 'group_fields',
								'label'     => __('Fee Deduction', 'tutor'),
								'desc'      => __('Fees are charged from the entire sales amount. The remaining amount will be divided among admin and instructors.',	'tutor'),
								'group_fields'  => array(

									'enable_fees_deducting' => array(
										'type'          => 'checkbox',
										'label'         => __('Enable', 'tutor'),
										'default'       => '0',
									),
									'fees_name' => array(
										'type'      => 'text',
										'label'         => __('Fee Name', 'tutor'),
										'default'   => '',
									),
									'fees_amount' => array(
										'type'      => 'number',
										'label'         => __('Fee Amount', 'tutor'),
										'default'   => '',
									),
									'fees_type' => array(
										'type'      => 'select',
										'default'   => 'minutes',
										'select_options'   => false,
										'options'   => array(
											''     =>  __('Select Fees Type', 'tutor'),
											'percent'     =>  __('Percent', 'tutor'),
											'fixed'      =>  __('Fixed', 'tutor'),
										),
									),

								),
							),
							'statement_show_per_page' => array(
								'type'      => 'number',
								'label'      => __('Show Statement Per Page', 'tutor'),
								'default'   => '20',
								'desc'  => __('Define the number of statement should show.', 'tutor'),
							),
						),
					),
				),
			),
			'tutor_withdraw' => array(
				'label'     => __('Withdraw', 'tutor'),
				'sections'    => array(
					'general' => array(
						'label' => __('Withdrawal Settings', 'tutor'),
						'fields' => array(
							'min_withdraw_amount' => array(
								'type'      => 'number',
								'label'     => __('Minimum Withdraw Amount', 'tutor'),
								'default'   => '80',
								'desc'      => __('Define the withdraw amount, anyone can make withdraw request if their earning above or equal this amount.',	'tutor'),
							),
						),
					),

					'withdraw_methods' => array(
						'label' => __('Withdraw Methods', 'tutor'),
						'desc' => __('Set withdraw settings', 'tutor'),
					),
				),
			),

			'tutor_style' => array(
				'label'     => __('Style', 'tutor'),
				'sections'    => array(
					'general' => array(
						'label' => __('Color Style', 'tutor'),
						'fields' => array(
							'tutor_primary_color' => array(
								'type'      => 'color',
								'label'     => __('Primary Color', 'tutor'),
								'default'   => '',
							),
							'tutor_primary_hover_color' => array(
								'type'      => 'color',
								'label'     => __('Primary Hover Color', 'tutor'),
								'default'   => '',
							),
							'tutor_text_color' => array(
								'type'      => 'color',
								'label'     => __('Text color', 'tutor'),
								'default'   => '',
							),
							'tutor_light_color' => array(
								'type'      => 'color',
								'label'     => __('Light color', 'tutor'),
								'default'   => '',
							),
						),
					),

				),
			),

			'monetization' => array(
				'label' => __('Monetization', 'tutor'),
				'sections'    => array(
					'general' => array(
						'label' => __('Monetization', 'tutor'),
						'desc' => __('You can monetize your LMS website by selling courses in a various way.', 'tutor'),
						'fields' => array(

							'monetize_by' => array(
								'type'      => 'radio',
								'label'      => __('Monetize Option', 'tutor'),
								'default'   => 'free',
								'select_options'   => false,
								'options'   => apply_filters('tutor_monetization_options', array(
									'free'          =>  __('Disable Monetization', 'tutor'),
								)),
								'desc'  => __('Select a monetization option to generate revenue by selling courses. Supports: WooCommerce, Easy Digital Downloads, Paid Memberships Pro',	'tutor'),
							),

						)
					)
				),
			),


		);

		return apply_filters('tutor/options/attr', $attr);
	}

	/**
	 * @param array $field
	 *
	 * @return string
	 *
	 * Generate Option Field
	 */
	public function generate_field($field = array()){
		ob_start();
		include tutor()->path.'views/options/option_field.php';
		return ob_get_clean();
	}

	public function field_type($field = array()){
		ob_start();
		include tutor()->path."views/options/field-types/{$field['type']}.php";
		return ob_get_clean();
	}

	public function generate(){
		ob_start();
		include tutor()->path.'views/options/options_generator.php';
		return ob_get_clean();
	}

}