
<form class="tutor_lesson_modal_form">
    <input type="hidden" name="action" value="tutor_modal_create_or_update_lesson">
    <input type="hidden" name="lesson_id" value="<?php echo $post->ID; ?>">
    <input type="hidden" name="current_topic_id" value="<?php echo $topic_id; ?>">

    <div class="lesson-modal-form-wrap">

	    <?php do_action('tutor_lesson_edit_modal_form_before', $post); ?>


        <div class="tutor-option-field-row">
            <div class="tutor-option-field tutor-lesson-modal-title-wrap">
                <input type="text" name="lesson_title" value="<?php echo $post->post_title; ?>" placeholder="<?php _e('Lesson title', 'tutor'); ?>">
            </div>
        </div>

        <div class="tutor-option-field-row">
            <div class="tutor-option-field">
				<?php
                wp_editor($post->post_content, 'tutor_lesson_modal_editor', array( 'editor_height' => 150));
				?>
            </div>
        </div>


        <div class="tutor-option-field-row">
            <div class="tutor-option-field-label">
                <label for=""><?php _e('Feature Image'); ?></label>
            </div>
            <div class="tutor-option-field">
                <div class="tutor-option-gorup-fields-wrap">
                    <div class="tutor-thumbnail-wrap">
                        <p class="thumbnail-img">
							<?php
							$thumbnail_upload_text = __('Upload Feature Image', 'tutor');
							if (has_post_thumbnail($post->ID)){
								echo get_the_post_thumbnail($post->ID);
								$thumbnail_upload_text = __('Update Feature Image', 'tutor');
							}

							?>
                        </p>
                        <input type="hidden" class="_lesson_thumbnail_id" name="_lesson_thumbnail_id" value="">
                        <button type="button" class="lesson_thumbnail_upload_btn tutor-btn"><?php echo $thumbnail_upload_text; ?></button>
                    </div>
                </div>
            </div>
        </div>

		<?php
		include tutor()->path.'views/metabox/video-metabox.php';
		include tutor()->path.'views/metabox/lesson-attachments-metabox.php';
		?>


        <?php do_action('tutor_lesson_edit_modal_form_after', $post); ?>

    </div>

    <div class="modal-footer">
        <button type="button" class="tutor-btn active update_lesson_modal_btn"><?php _e('Update Lesson', 'tutor'); ?></button>
    </div>
</form>