<h3><?php _e('Dashboard', 'tutor') ?></h3>

<div class="tutor-dashboard-content-inner">

    <?php
        $enrolled_course = tutor_utils()->get_enrolled_courses_by_user();
        $completed_courses = tutor_utils()->get_completed_courses_ids_by_user();
        $total_students = tutor_utils()->get_total_students_by_instructor(get_current_user_id());
        $my_courses = tutor_utils()->get_courses_by_instructor(get_current_user_id(), 'any');
        $earning_sum = tutor_utils()->get_earning_sum();

        $enrolled_course_count = $enrolled_course ? $enrolled_course->post_count : 0;
        $completed_course_count = count($completed_courses);
        $active_course_count = $enrolled_course_count - $completed_course_count;
    ?>

    <div class="tutor-dashboard-info-cards">
        <div class="tutor-dashboard-info-card">
            <p>
                <span><?php _e('Enrolled Course', 'tutor'); ?></span>
                <span class="tutor-dashboard-info-val"><?php echo esc_html($enrolled_course_count); ?></span>
            </p>
        </div>
        <div class="tutor-dashboard-info-card">
            <p>
                <span><?php _e('Active Course', 'tutor'); ?></span>
                <span class="tutor-dashboard-info-val"><?php echo esc_html($active_course_count); ?></span>
            </p>
        </div>
        <div class="tutor-dashboard-info-card">
            <p>
                <span><?php _e('Completed Course', 'tutor'); ?></span>
                <span class="tutor-dashboard-info-val"><?php echo esc_html($completed_course_count); ?></span>
            </p>
        </div>

        <?php
            if(current_user_can(tutor()->instructor_role)) :
        ?>

        <div class="tutor-dashboard-info-card">
            <p>
                <span><?php _e('Total Students', 'tutor'); ?></span>
                <span class="tutor-dashboard-info-val"><?php echo esc_html($total_students); ?></span>
            </p>
        </div>
        <div class="tutor-dashboard-info-card">
            <p>
                <span><?php _e('Total Courses', 'tutor'); ?></span>
                <span class="tutor-dashboard-info-val"><?php echo esc_html(count($my_courses)); ?></span>
            </p>
        </div>
        <div class="tutor-dashboard-info-card">
            <p>
                <span><?php _e('Total Earning', 'tutor'); ?></span>
                <span class="tutor-dashboard-info-val"><?php echo tutor_utils()->tutor_price($earning_sum->instructor_amount); ?></span>
            </p>
        </div>
        <?php
            endif;
        ?>
    </div>

    <?php
        $instructor_course = tutor_utils()->get_courses_for_instructors(get_current_user_id());
        if(count($instructor_course)) {
    ?>

    <div class="tutor-dashboard-info-table-wrap">
        <h3><?php _e('Most Popular Courses', 'tutor'); ?></h3>
        <table class="tutor-dashboard-info-table">
            <thead>
            <tr>
                <td><?php _e('Course Name', 'tutor'); ?></td>
                <td><?php _e('Enrolled', 'tutor'); ?></td>
            </tr>
            </thead>
            <tbody>
            <?php
                $instructor_course = tutor_utils()->get_courses_for_instructors(get_current_user_id());
                foreach ($instructor_course as $course){
                    $enrolled = tutor_utils()->count_enrolled_users_by_course($course->ID);
                    echo "<tr><td>$course->post_title</td><td>$enrolled</td></tr>";
                }
            ?>
            </tbody>
        </table>
    </div>
    <?php } ?>

</div>