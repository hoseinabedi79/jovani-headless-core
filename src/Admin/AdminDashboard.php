<?php
namespace Jovani\HeadlessCore\Admin;

class AdminDashboard {
    /**
     * Initialize Admin-related hooks.
     */
    public function init() {
        // Clean up the main WordPress admin sidebar menus
        add_action( 'admin_menu', [ $this, 'jovani_clean_admin_sidebar' ], 999 );

        // Clean up product data tabs in WooCommerce edit screen
        add_filter( 'woocommerce_product_data_tabs', [ $this, 'jovani_clean_product_tabs' ], 999 );

        // Hide price fields and admin notices using inline CSS
        add_action( 'admin_head', [ $this, 'jovani_hide_price_and_fields_cleanly' ] );
    }

    /**
     * Remove heavy and unnecessary WooCommerce menus from the sidebar.
     */
    public function jovani_clean_admin_sidebar() {
        remove_menu_page( 'wc-admin&path=/payments' );
        remove_menu_page( 'woocommerce-marketing' );
        remove_menu_page( 'wc-admin&path=/analytics/overview' );
    }

    /**
     * Remove unused WooCommerce tabs since we use ACF for custom relations.
     */
    public function jovani_clean_product_tabs( $tabs ) {
        unset( $tabs['shipping'] );
        unset( $tabs['linked_product'] );
        unset( $tabs['advanced'] );

        return $tabs;
    }

    /**
     * Inject custom CSS to hide prices and setup wizard notices.
     */
    public function jovani_hide_price_and_fields_cleanly() {
        echo '<style>
            /* Hide pricing fields in general options tab */
            .general_options .options_group:first-of-type,
            /* Hide price column in the products list view */
            .column-price,
            /* Hide unnecessary WooCommerce setup/vendor notices */
            .woocommerce-layout__notice-list,
            #vc_vendor-settings-notice { 
                display: none !important; 
            }
        </style>';
    }
}