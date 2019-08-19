<?php
/**
 * Woocommerce Function
 */
remove_action('woocommerce_before_main_content','woocommerce_output_content_wrapper',10);
remove_action('woocommerce_after_main_content','woocommerce_output_content_wrapper_end',10);
remove_action('woocommerce_before_main_content','woocommerce_breadcrumb',20);
add_action('woocommerce_before_main_content','enlighten_woocommerce_wrap_start',22);
add_action('woocommerce_after_main_content','enlighten_woocommerce_wrap_end',12);

function enlighten_woocommerce_wrap_start(){
    ?>
    <div class="ak-container-right ak-container">
    	<div id="primary" class="content-area right">
            <div class="theiaStickySidebar">
                <main id="main" class="site-main" role="main">
    <?php
}
function enlighten_woocommerce_wrap_end(){
    ?>
            </main>
        </div>
    </div>
        <div id="secondary" class="right_right">
            <?php
                get_sidebar();
            ?>
        </div>
    </div>
    <?php
}