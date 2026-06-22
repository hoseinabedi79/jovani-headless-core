<?php

namespace Jovani\HeadlessCore\Graphql;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Search {

    public function init() {
        add_filter( 'posts_search', [ $this, 'title_only_search' ], 10, 2 );
    }

    /**
     * Restrict product search to post_title only — GraphQL product queries.
     */
    public function title_only_search( $search, $wp_query ) {

        // Only touch GraphQL requests; leave admin/normal searches untouched.
        if ( ! function_exists( 'is_graphql_request' ) || ! is_graphql_request() ) {
            return $search;
        }

        // Only product queries.
        $post_type  = $wp_query->get( 'post_type' );
        $is_product = ( 'product' === $post_type )
            || ( is_array( $post_type ) && in_array( 'product', $post_type, true ) );

        if ( ! $is_product ) {
            return $search;
        }

        // The words the user typed, already split into an array by WP.
        $search_terms = $wp_query->get( 'search_terms' );

        // Nothing to search? Return the original clause unchanged.
        if ( empty( $search ) || empty( $search_terms ) ) {
            return $search;
        }

        global $wpdb;

        $exact     = $wp_query->get( 'exact' );
        $search    = '';   // Rebuild the search SQL from scratch.
        $searchand = '';   // Becomes ' AND ' between terms.

        foreach ( (array) $search_terms as $term ) {
            // Exact match = whole term; otherwise wildcard match anywhere in the title.
            $like = $exact
                ? $wpdb->esc_like( $term )
                : '%' . $wpdb->esc_like( $term ) . '%';

            // Add a safe "title LIKE ..." condition for this term.
            $search   .= $wpdb->prepare( "{$searchand}({$wpdb->posts}.post_title LIKE %s)", $like );
            $searchand = ' AND ';
        }

        // Wrap our conditions so they slot into WP's larger query.
        if ( ! empty( $search ) ) {
            $search = " AND ({$search}) ";
        }

        return $search;
    }
}