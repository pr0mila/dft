<?php
$user_name = sanitize_text_field(get_query_var('tutor_student_username'));
$get_user = tutor_utils()->get_user_by_login($user_name);
$user_id = $get_user->ID;


$profile_bio = get_user_meta($user_id, '_tutor_profile_bio', true);
if ($profile_bio){
	?>
	<h3><?php _e('About Me:', 'tutor'); ?></h3>
	<?php echo wpautop($profile_bio) ?>
<?php } else{
    _e('Bio data is empty', 'tutor');
} ?>