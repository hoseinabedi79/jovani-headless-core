<?php
namespace Jovani\HeadlessCore\Admin;

class Acf {
    /**
     * Initialize ACF-related hooks.
     */
    public function init() {
        // Dynamically populate choices for the product_color ACF field
        add_filter( 'acf/load_field/name=product_color', [ $this, 'jovani_populate_product_color_field' ] );
    }

    /**
     * Dynamically filter product_color choices based on WooCommerce product attributes.
     *
     * @param array $field The ACF field configuration array.
     * @return array Modified ACF field with dynamic choices.
     */
    public function jovani_populate_product_color_field( $field ) {
        // 1. Clear default choices to avoid mixing data
        $field['choices'] = array();
        $product_id = false;

        // 2. Identify the current product ID from the admin request
        if ( isset( $_REQUEST['post_id'] ) ) {
            $product_id = intval( $_REQUEST['post_id'] );
        } elseif ( isset( $_REQUEST['post'] ) ) {
            $product_id = intval( $_REQUEST['post'] );
        }

        // 3. If we are inside a product edit page, fetch its specific color attributes
        if ( $product_id && get_post_type( $product_id ) === 'product' ) {
            $product = wc_get_product( $product_id );

            if ( $product ) {
                // Get the raw assigned terms for 'pa_color' taxonomy from this specific product
                $product_colors = $product->get_attribute( 'pa_color' );

                if ( ! empty( $product_colors ) ) {
                    // Convert the comma-separated string into a clean array
                    $color_names = array_map( 'trim', explode( ',', $product_colors ) );

                    foreach ( $color_names as $color_name ) {
                        // Try to find the actual term object to use the slug as the key
                        $term = get_term_by( 'name', $color_name, 'pa_color' );
                        if ( $term ) {
                            $field['choices'][ $term->slug ] = $term->name;
                        } else {
                            // Fallback to the raw name if the term object is not found
                            $field['choices'][ $color_name ] = $color_name;
                        }
                    }
                }
            }
        } else {
            // 4. Global fallback: If accessed via Media Library directly, show all available global colors
            $colors = get_terms( array( 'taxonomy' => 'pa_color', 'hide_empty' => false ) );
            if ( ! is_wp_error( $colors ) && ! empty( $colors ) ) {
                foreach ( $colors as $color ) {
                    $field['choices'][ $color->slug ] = $color->name;
                }
            }
        }

        return $field;
    }
}