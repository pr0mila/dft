<?php
$user_name = sanitize_text_field(get_query_var('tutor_student_username'));
$get_user = tutor_utils()->get_user_by_login($user_name);
$user_id = $get_user->ID;



$pageposts = tutor_utils()->get_courses_by_instructor($user_id);
?>


<div class="tutor-courses">
	<?php if ($pageposts):
		global $post;
		foreach ($pageposts as $post):
			setup_postdata($post);

			/**
			 * @hook tutor_course/archive/before_loop_course
			 * @type action
			 * Usage Idea, you may keep a loop within a wrap, such as bootstrap col
			 */
			do_action('tutor_course/archive/before_loop_course');

			tutor_load_template('loop.course');

			/**
			 * @hook tutor_course/archive/after_loop_course
			 * @type action
			 * Usage Idea, If you start any div before course loop, you can end it here, such as </div>
			 */
			do_action('tutor_course/archive/after_loop_course');

		endforeach;
	else : ?>
    <div>
		<h2><?php _e("Not Found" , 'tutor'); ?></h2>
		<p><?php _e("Sorry, but you are looking for something that isn't here." , 'tutor'); ?></p>
    </div>
	<?php endif; ?>
</div>