<?php

namespace CodeForceInfiniteScroll\App\UserQuery;

/**
 * Just wrapper over native WP_User_Query class.
 * Generate query args and create new WP_User_Query object.
 */
class Wrapper 
{
    const USERS_PER_PAGE = 10;

    public function get_users_query(Array $args = array()) 
    {
        $users_per_page = self::USERS_PER_PAGE;
        
        if (isset($args['number'])) {
            $users_per_page = (int)$args['number'];
        }

        $current_page = max(get_query_var('paged'), 1);
        $offset = $users_per_page * ($current_page - 1);

        $default_args  = array(
            'number' => $users_per_page,
            'paged' => $current_page,
            'offset' => $offset,

            'orderby' => 'post_count',
            'order' => 'DESC',
        );

        $query_args = array_merge($default_args, $args);

        $CustomOrdersManager = new CustomOrdersManager();
        $query_args = $CustomOrdersManager->applyCustomOrder($query_args);

        // Create the WP_User_Query object
        $UserQuery = new \WP_User_Query($query_args);

        return $UserQuery;
    }
}
