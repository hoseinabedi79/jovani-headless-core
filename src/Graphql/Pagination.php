<?php

namespace Jovani\HeadlessCore\Graphql;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Pagination {

    public function init() {
        add_action( 'graphql_register_types', [ $this, 'register_offset_arg' ] );
        add_filter( 'graphql_product_connection_query_args', [ $this, 'apply_offset' ], 10, 5 );
    }

    public function register_offset_arg() {
        $types = [
            'RootQueryToProductConnectionWhereArgs',
            'ProductCategoryToProductConnectionWhereArgs',
        ];

        foreach ( $types as $type ) {
            register_graphql_field( $type, 'offset', [
                'type'        => 'Int',
                'description' => 'Number of products to skip for numeric pagination',
            ] );
        }
    }

    public function apply_offset( $query_args, $source, $args, $context, $info ) {
        if ( isset( $args['where']['offset'] ) ) {
            $query_args['offset'] = (int) $args['where']['offset'];
        }

        return $query_args;
    }
}