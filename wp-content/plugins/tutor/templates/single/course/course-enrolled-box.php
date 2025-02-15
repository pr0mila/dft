<?php
/**
 * Template for displaying course content
 *
 * @since v.1.0.0
 *
 * @author Themeum
 * @url https://themeum.com
 */

if ( ! defined( 'ABSPATH' ) )
    exit;

global $wp_query;

?>

<div class="tutor-price-preview-box">
    <div class="tutor-price-box-thumbnail">
        <?php
        if(tutor_utils()->has_video_in_single()){
            tutor_course_video();
        } else{
            get_tutor_course_thumbnail();
        }
        ?>
    </div>

    <div class="tutor-lead-info-btn-group">
        <?php

        if ( $wp_query->query['post_type'] !== 'lesson') {
            $lesson_url = tutor_utils()->get_course_first_lesson();
            $completed_lessons = tutor_utils()->get_completed_lesson_count_by_course();
            if ( $lesson_url ) { ?>
                <a href="<?php echo $lesson_url; ?>" class="tutor-button tutor-success">
                    <?php
                        if($completed_lessons){
                            _e( 'Continue to lesson', 'tutor' );
                        }else{
                            _e( 'Start Course', 'tutor' );
                        }
                    ?>
                </a>
            <?php }
        }
        ?>
        <?php tutor_course_mark_complete_html(); ?>
    </div>


	<?php tutor_course_price(); ?>
    <?php tutor_course_material_includes_html(); ?>

    <div class="tutor-single-course-segment  tutor-course-enrolled-wrap">
        <p>
            <i class="tutor-icon-purchase"></i>
            <?php
                $enrolled = tutor_utils()->is_enrolled();
                _e(sprintf("You have been enrolled on %s.", "<span>". date(get_option('date_format'), strtotime($enrolled->post_date) )."</span>" ), 'tutor');
            ?>
        </p>
        <?php do_action('tutor_enrolled_box_after') ?>

    </div>

</div> <!-- tutor-price-preview-box -->

