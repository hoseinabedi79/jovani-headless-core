<?php
namespace Jovani\HeadlessCore;

use Jovani\HeadlessCore\Admin\AdminDashboard;
use Jovani\HeadlessCore\Admin\Acf;
use Jovani\HeadlessCore\Admin\Sync;
use Jovani\HeadlessCore\Graphql\GraphqlConfig;
use Jovani\HeadlessCore\Graphql\Fields;
use Jovani\HeadlessCore\Graphql\Pagination;
use Jovani\HeadlessCore\Graphql\Search;

class Core {
    /**
     * Initialize all plugin components.
     */
    public function init() {
        // Load admin-specific components only
        if ( is_admin() ) {
            (new AdminDashboard())->init();
            (new Acf())->init();
            (new Sync())->init();
        }

        // Load components for both frontend (GraphQL) and backend
        (new GraphqlConfig())->init();
        (new Fields())->init();
        (new Pagination())->init();
        ( new Search() )->init();
    }
}