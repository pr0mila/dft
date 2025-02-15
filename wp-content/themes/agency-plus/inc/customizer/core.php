<?php
/**
 * Core functions.
 *
 * @package Agency_Plus
 */

if ( ! function_exists( 'agency_plus_get_option' ) ) :

    /**
     * Get theme option.
     *
     * @since 1.0.0
     *
     * @param string $key Option key.
     * @return mixed Option value.
     */
    function agency_plus_get_option( $key ) {

        if ( empty( $key ) ) {

            return;

        }

        $agency_plus_default = agency_plus_get_default_theme_options();

        $default = ( isset( $agency_plus_default[ $key ] ) ) ? $agency_plus_default[ $key ] : '';
        $theme_options = get_theme_mod( 'theme_options', $agency_plus_default );
        $theme_options = array_merge( $agency_plus_default, $theme_options );
        $value = '';

        if ( isset( $theme_options[ $key ] ) ) {
            $value = $theme_options[ $key ];
        }

        return $value;

    }

endif;

if ( ! function_exists( 'agency_plus_get_default_theme_options' ) ) :

    /**
     * Get default theme options.
     *
     * @since 1.0.0
     *
     * @return array Default theme options.
     */
    function agency_plus_get_default_theme_options() {

        $defaults = array();

        $defaults['site_identity']          = 'title-text';
        $defaults['show_social_icons']      = true;
        $defaults['nav_button_text']        = esc_html__( 'Contact Now', 'agency-plus' );
        $defaults['nav_button_link']        = '';

        $defaults['global_layout']          = 'right-sidebar';
        $defaults['excerpt_length']         = 40;
        $defaults['readmore_text']          = esc_html__( 'Read More', 'agency-plus' );

        $defaults['copyright_text']         = esc_html__( 'Copyright &copy; [the-year] [the-site-title]. All rights reserved.', 'agency-plus' );
        $defaults['enable_social_icons']      = true;
        $defaults['enable_goto_top']          = true;

        // Shop page
        $defaults['show_cart_icon']         = true;
        $defaults['shop_layout']            = 'right-sidebar';
        $defaults['product_per_page']       = 9;
        $defaults['product_number']         = 3;
        $defaults['hide_product_sorting']   = false;
        $defaults['enable_gallery_zoom']    = false;
        $defaults['disable_related_products']= false;

        return $defaults;
    }

endif;

//=============================================================
// Get all options in array
//=============================================================
if ( ! function_exists( 'agency_plus_get_options' ) ) :

    /**
     * Get all theme options in array.
     *
     * @since 1.0.0
     *
     * @return array Theme options.
     */
    function agency_plus_get_options() {

        $value = array();

        $value = get_theme_mods();

        return $value;

    }

endif;