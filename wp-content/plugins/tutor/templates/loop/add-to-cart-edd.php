<?php
$product_id = tutor_utils()->get_course_product_id();
$download = new EDD_Download( $product_id );

if ($download->ID) {
	echo edd_get_purchase_link( array( 'download_id' => $download->ID ) );
}