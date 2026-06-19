<?php
namespace Jovani\HeadlessCore\Graphql;

class GraphqlConfig {
    /**
     * Register WPGraphQL hooks and general configurations.
     */
    public function init() {
        // Remove Add to Cart buttons from single product and archives
        add_action( 'init', [ $this, 'jovani_remove_add_to_cart_buttons' ] );

        // Make all products unpurchasable (Catalog Mode)
        add_filter( 'woocommerce_is_purchasable', '__return_false' );

        // Redirect cart and checkout pages to homepage
        add_action( 'template_redirect', [ $this, 'jovani_redirect_cart_checkout' ] );
    }

    /**
     * Remove WooCommerce Add to Cart buttons.
     */
    public function jovani_remove_add_to_cart_buttons() {
        remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10 );
        remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30 );
    }

    /**
     * Redirect users trying to access checkout or cart directly.
     */
    public function jovani_redirect_cart_checkout() {
        if ( is_cart() || is_checkout() ) {
            wp_redirect( home_url() );
            exit;
        }
    }
}