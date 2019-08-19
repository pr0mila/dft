<?php
$attempt_id = (int) sanitize_text_field($_GET['attempt_id']);
$attempt = tutor_utils()->get_attempt($attempt_id);

if ( ! $attempt){
	?>
    <h1><?php _e('Attempt not found', 'tutor'); ?></h1>
	<?php
	return;
}

$quiz_attempt_info = tutor_utils()->quiz_attempt_info($attempt->attempt_info);
$answers = tutor_utils()->get_quiz_answers_by_attempt_id($attempt->attempt_id);

$user_id = tutor_utils()->avalue_dot('user_id', $attempt);
$user = get_userdata($user_id);
?>

<div class="wrap">
    <h2 class="attempt-review-title"> <i class="tutor-icon-list"></i> <?php _e('View Attempts', 'tutor'); ?></h2>

    <div class="tutor-quiz-attempt-info-row">
        <div class="attempt-view-top">
            <div class="attempt-info-col">
				<?php echo '<p class="attempt-property-name">'.__('Attempt By', 'tutor').' </p><h4>'. $user->display_name.'</h4>'; ?>
            </div>

            <div class="attempt-info-col">
				<?php
				echo '<p class="attempt-property-name">'. __('Quiz', 'tutor')." </p> <p class='attempt-property-value'><a href='" .admin_url("post.php?post={$attempt->quiz_id}&action=edit")."'>".get_the_title($attempt->quiz_id)."</a> </p> ";
				?>
            </div>

            <div class="attempt-info-col">
				<?php echo '<p class="attempt-property-name">'.__('Attempt At', 'tutor').'</p><p class="attempt-property-value">'. date_i18n(get_option('date_format'), strtotime($attempt->attempt_started_at)).' '.date_i18n(get_option('time_format'), strtotime($attempt->attempt_started_at)).'</p>'; ?>
            </div>

            <div class="attempt-info-col">
                <p class="attempt-property-name">
					<?php echo __('Status', 'tutor'); ?>
                </p>
                <p class="attempt-property-value">
					<?php
					$status = ucwords(str_replace('quiz_', '', $attempt->attempt_status));
					echo "<span class='attempt-status-{$attempt->attempt_status}'>{$status}</span>";
					?>
                </p>
            </div>
        </div>

        <div class="attempt-view-bottom">

            <div class="attempt-info-col">
				<?php
				$quiz = tutor_utils()->get_course_by_quiz($attempt->quiz_id);
				if ($quiz) {
					echo '<p class="attempt-property-name">' . __( 'Course', 'tutor' ) . '</p> <p class="attempt-property-value"> ' . "<a href='".admin_url( "post.php?post={$quiz->ID}&action=edit" ) . "'><strong>". get_the_title( $quiz->ID )."</strong></a> </p>";
				}
				?>
            </div>

            <div class="attempt-info-col">
                <div class="attempt-property-name"><?php _e('Result', 'tutor'); ?></div>
                <div class="attempt-property-value value-display-flex">
					<?php
					$pass_mark_percent = tutor_utils()->get_quiz_option($attempt->quiz_id, 'passing_grade', 0);
					$earned_percentage = $attempt->earned_marks > 0 ? ( number_format(($attempt->earned_marks * 100) / $attempt->total_marks)) : 0;

					$output = '';
					if ($earned_percentage >= $pass_mark_percent){
						$output .= '<p><span class="result-pass">'.__('Pass', 'tutor').'</span></p>';
					}else{
						$output .= '<p> <span class="result-fail">'.__('Fail', 'tutor').'</span></p>';
					}

					$output .= "<p class='result-in-write'>".$attempt->earned_marks." out of {$attempt->total_marks} <br />";
					$output .= "Marks earned ({$earned_percentage}%)</p>";
					echo $output;
					?>
                </div>
            </div>

            <div class="attempt-info-col">
                <p class="attempt-property-name"><?php _e('Quiz Time', 'tutor'); ?></p>
                <p class="attempt-property-value">
					<?php
					$time_limit_seconds = tutor_utils()->avalue_dot('time_limit.time_limit_seconds', $quiz_attempt_info);
					echo tutor_utils()->seconds_to_time_context($time_limit_seconds);
					?>
                </p>
            </div>

            <div class="attempt-info-col">
                <p class="attempt-property-name"><?php _e('Attempt Time', 'tutor'); ?></p>
                <p class="attempt-property-value">
					<?php
					$attempt_time_sec = strtotime($attempt->attempt_ended_at) - strtotime($attempt->attempt_started_at);
					echo tutor_utils()->seconds_to_time_context($attempt_time_sec);
					?>
                </p>
            </div>

        </div>

    </div>


    <div class="attempt-review-notice-wrap">
		<?php
		if (is_array($answers) && count($answers)) {
			$question_no = 0;
			$required_review = array();

			foreach ($answers as $answer){
				$question_no++;
				if ($answer->question_type === 'open_ended' || $answer->question_type === 'short_answer'){
					$required_review[] = $question_no;
				}
			}

			if (count($required_review)){
				echo '<p class="attempt-review-notice"> <span class="notice-excl">&excl;</span>  <strong>Reminder:</strong> Please review answers for question no. 
'.implode
					(', ',
						$required_review).' </p>';
			}
		}


		?>


	    <?php if ((bool) $attempt->is_manually_reviewed ){
		    ?>
            <p class="attempt-review-at">
                <span class="circle-arrow"> &circlearrowright; </span>
	            <?php _e('Manually reviewed at :', 'tutor'); ?>
                <strong>
		            <?php echo date_i18n(get_option('date_format'), strtotime($attempt->manually_reviewed_at)).' '.date_i18n(get_option('time_format'), strtotime($attempt->manually_reviewed_at)); ?>
                </strong>
            </p>
		    <?php
	    } ?>

    </div>
	<?php
	if (is_array($answers) && count($answers)){

		?>
        <div class="quiz-attempt-answers-wrap">

            <div class="attempt-answers-header">
                <h3><?php _e('Quiz Overview', 'tutor'); ?></h3>
            </div>

            <table class="wp-list-table">
                <tr>
                    <th><?php _e('Type', 'tutor'); ?></th>
                    <th><?php _e('No.', 'tutor'); ?></th>
                    <th width="200"><?php _e('Question', 'tutor'); ?></th>
                    <th><?php _e('Given Answers', 'tutor'); ?></th>
                    <th width="80"><?php _e('Correct/Incorrect', 'tutor'); ?></th>
                    <th width="100"><?php _e('Review', 'tutor'); ?></th>
                </tr>
				<?php
				$answer_i = 0;
				foreach ($answers as $answer){
					$answer_i++;
					$question_type = tutor_utils()->get_question_types($answer->question_type);
					?>

                    <tr>
                        <td><?php echo $question_type['icon']; ?></td>
                        <td><?php echo $answer_i; ?></td>
                        <td><?php echo $answer->question_title; ?></td>
                        <td>
							<?php
							if ($answer->question_type === 'true_false' || $answer->question_type === 'single_choice' ){

								$get_answers = tutor_utils()->get_answer_by_id($answer->given_answer);
								$answer_titles = wp_list_pluck($get_answers, 'answer_title');
								echo '<p>'.implode('</p><p>', $answer_titles).'</p>';

							}elseif ($answer->question_type === 'multiple_choice'){

								$get_answers = tutor_utils()->get_answer_by_id(maybe_unserialize($answer->given_answer));
								$answer_titles = wp_list_pluck($get_answers, 'answer_title');
								echo '<p>'.implode('</p><p>', $answer_titles).'</p>';

							}elseif ($answer->question_type === 'fill_in_the_blank'){

								$answer_titles = maybe_unserialize($answer->given_answer);

								$get_db_answers_by_question = tutor_utils()->get_answers_by_quiz_question($answer->question_id);
								foreach ($get_db_answers_by_question as $db_answer);
								$count_dash_fields = substr_count($db_answer->answer_title, '{dash}');
								if ($count_dash_fields){
									$dash_string = array();
									$input_data = array();

									for($i=0; $i<$count_dash_fields; $i++){
										//$dash_string[] = '{dash}';
										$input_data[] =  isset($answer_titles[$i]) ? "<span class='filled_dash_unser'>{$answer_titles[$i]}</span>" : "______";
									}

									$answer_title = $db_answer->answer_title;
									foreach($input_data as $replace){
										$answer_title = preg_replace('/{dash}/i', $replace, $answer_title, 1);
									}

									echo str_replace('{dash}', '_____', $answer_title);
								}

							}elseif ($answer->question_type === 'open_ended' || $answer->question_type === 'short_answer'){

								if ($answer->given_answer){
									echo wpautop(stripslashes($answer->given_answer));
								}

							}elseif ($answer->question_type === 'ordering'){

								$ordering_ids = maybe_unserialize($answer->given_answer);
								foreach ($ordering_ids as $ordering_id){
									$get_answers = tutor_utils()->get_answer_by_id($ordering_id);
									$answer_titles = wp_list_pluck($get_answers, 'answer_title');
									echo '<p>'.implode('</p><p>', $answer_titles).'</p>';
								}

							}elseif ($answer->question_type === 'matching'){

								$ordering_ids = maybe_unserialize($answer->given_answer);
								$original_saved_answers = tutor_utils()->get_answers_by_quiz_question($answer->question_id);

								foreach ($original_saved_answers as $key => $original_saved_answer){
									$provided_answer_order_id = isset($ordering_ids[$key]) ? $ordering_ids[$key] : 0;
									$provided_answer_order = tutor_utils()->get_answer_by_id($provided_answer_order_id);
									foreach ($provided_answer_order as $provided_answer_order);
									echo $original_saved_answer->answer_title  ." - {$provided_answer_order->answer_two_gap_match} <br />";
								}

							}elseif ($answer->question_type === 'image_matching'){

								$ordering_ids = maybe_unserialize($answer->given_answer);
								$original_saved_answers = tutor_utils()->get_answers_by_quiz_question($answer->question_id);

								echo '<div class="answer-image-matched-wrap">';
								foreach ($original_saved_answers as $key => $original_saved_answer){
									$provided_answer_order_id = isset($ordering_ids[$key]) ? $ordering_ids[$key] : 0;
									$provided_answer_order = tutor_utils()->get_answer_by_id($provided_answer_order_id);
									foreach ($provided_answer_order as $provided_answer_order);
									?>
                                    <div class="image-matching-item">
                                        <p class="dragged-img-rap"><img src="<?php echo wp_get_attachment_image_url( $original_saved_answer->image_id); ?>" /> </p>
                                        <p class="dragged-caption"><?php echo $provided_answer_order->answer_title; ?></p>
                                    </div>
									<?php
								}
								echo '</div>';
							}elseif ($answer->question_type === 'image_answering'){

								$ordering_ids = maybe_unserialize($answer->given_answer);

								echo '<div class="answer-image-matched-wrap">';
								foreach ($ordering_ids as $answer_id => $image_answer){
									$db_answers = tutor_utils()->get_answer_by_id($answer_id);
									foreach ($db_answers as $db_answer);
									?>
                                    <div class="image-matching-item">
                                        <p class="dragged-img-rap"><img src="<?php echo wp_get_attachment_image_url( $db_answer->image_id); ?>" /> </p>
                                        <p class="dragged-caption"><?php echo $image_answer; ?></p>
                                    </div>
									<?php
								}
								echo '</div>';
							}

							?>
                        </td>

                        <td>
							<?php

							if ( (bool) isset( $answer->is_correct ) ? $answer->is_correct : '' ) {
								echo '<span class="tutor-status-approved-context"><i class="tutor-icon-mark"></i> </span>';
							} else {

								if ($answer->question_type === 'open_ended' || $answer->question_type === 'short_answer'){
									echo '<p style="color: #878A8F;"><span style="color: #ff282a;">&ast;</span> '.__('Review Required', 'tutor').'</p>';
								}else {
									echo '<span class="tutor-status-blocked-context"><i class="tutor-icon-line-cross"></i> </span>';
								}
							}


							?>
                        </td>

                        <td>
                            <a href="<?php echo admin_url("admin.php?action=review_quiz_answer&attempt_id={$attempt_id}&attempt_answer_id={$answer->attempt_answer_id}&mark_as=correct"); ?>" title="<?php _e('Mark as correct', 'tutor'); ?>" class="attempt-mark-correct-btn"><i class="tutor-icon-mark"></i> </a>

                            <a href="<?php echo admin_url("admin.php?action=review_quiz_answer&attempt_id={$attempt_id}&attempt_answer_id={$answer->attempt_answer_id}&mark_as=incorrect"); ?>" title="<?php _e('Mark as In correct', 'tutor'); ?>" class="attempt-mark-incorrect-btn"><i class="tutor-icon-line-cross"></i></a>
                        </td>
                    </tr>
					<?php
				}
				?>
            </table>
        </div>

		<?php
	}
	?>
</div>