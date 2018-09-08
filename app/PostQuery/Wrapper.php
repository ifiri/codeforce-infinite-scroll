<?php

namespace CodeForceInfiniteScroll\App\PostQuery;

/**
 * Just wrapper over native WP_User_Query class.
 * Generate query args and create new WP_User_Query object.
 */
class Wrapper 
{
    const POSTS_PER_PAGE = 10;

    public function get_post_query(Array $args = array()) 
    {
        $posts_per_page = self::POSTS_PER_PAGE;
        
        if (isset($args['number'])) {
            $posts_per_page = (int)$args['number'];
        }

        $current_page = max(get_query_var('paged'), 1);
        $offset = $posts_per_page * ($current_page - 1);

        $default_args  = array(
            'number' => $posts_per_page,
            // 'paged' => $current_page,
            // 'offset' => $offset,

            'orderby' => 'post_date',
            'order' => 'DESC',
            'include' => null,
            'exclude' => null,
            'per_page' => null,

            'category' => null,
            'tag' => null,
            'taxonomy' => null,
            'taxonomy_terms' => null,
            'post_status' => null,
            'search' => null,
            'author' => null,
        );

        $query_args = array_merge($default_args, $args);

        // Build correct query params
        if($query_args['taxonomy']) {
            $query_args['tax_query'] = array(
                array(
                    'taxonomy' => $query_args['taxonomy'],
                    'terms' => array(
                        $query_args['taxonomy_terms'],
                    ),
                    'field' => 'slug',
                    'operator' => 'IN'
                ),
            );

            unset($query_args['taxonomy']);
            unset($query_args['taxonomy_terms']);
        }

        if($query_args['category']) {
            if(!is_numeric($query_args['category'])) {
                $query_args['category_name'] = $query_args['category'];
            } else {
                $query_args['cat'] = $query_args['category'];
            }

            unset($query_args['category']);
        }

        if($query_args['search']) {
            $query_args['s'] = $query_args['search'];
            $query_args['orderby'] = 'relevance';

            unset($query_args['search']);
        }

        // Check for logged or unauthorized users
        if(is_user_logged_in() && $query_args['post_status'] === 'any') {
            global $wpdb;

            $user_id = get_current_user_id();

            $sql = '
            SELECT ID FROM wp_posts
            WHERE post_status = "private" AND post_author != %d';

            $excluded_ids = $wpdb->get_col($wpdb->prepare($sql, [$user_id]), 0);


            $query_args['post__not_in'] = $excluded_ids;
        }

        // Create the WP_User_Query object
        $UserQuery = new \WP_Query($query_args);

        return $UserQuery;
    }
}
