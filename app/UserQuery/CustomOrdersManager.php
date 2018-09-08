<?php

namespace CodeForceInfiniteScroll\App\UserQuery;

/**
 * This class similar to Decorator pattern.
 * Take an array with query args and, if required,
 * calculate a custom order, then put in args array
 * required ids.
 */
class CustomOrdersManager
{
    private $custom_order_modes = array('comments_count');

    /**
     * If required order mode is supported, applies it.
     * 
     * @param array $query_args 
     * @return array
     */
    public function apply_custom_order($query_args) 
    {
        if
            (
                isset($query_args['orderby']) 
                && 
                in_array($query_args['orderby'], $this->custom_order_modes)
            ) 
        {
            switch ($query_args['orderby']) {
                case 'comments_count':
                    $query_args = $this->orderByCommentsCount($query_args);
                    break;
            }
        }

        return $query_args;
    }

    /**
     * Ordering users by comments count.
     * 
     * @param Array $query_args 
     * @return array
     */
    private function order_by_comments_count(Array $query_args)
    {
        global $wpdb;

        $table_comments = $wpdb->prefix . 'comments';
        $table_posts = $wpdb->prefix . 'posts';
        $table_users = $wpdb->prefix . 'users';

        $include_only = null;
        if(isset($query_args['include']) && $query_args['include']) {
            if(is_array($query_args['include'])) {
                $include_only = implode(',', $query_args['include']);
            } else {
                $include_only = $query_args['include'];
            }
            
            unset($query_args['include']);
        }

        $exclude = null;
        if(isset($query_args['exclude']) && $query_args['exclude']) {
            if(is_array($query_args['exclude'])) {
                $exclude = implode(',', $query_args['exclude']);
            } else {
                $exclude = $query_args['exclude'];
            }

            unset($query_args['exclude']);
        }

        $order_by_count = 'DESC';
        $order_by_id = 'ASC';
        if (isset($query_args['order']) && strtoupper($query_args['order']) === 'ASC') 
        {
            $order_by_count = 'ASC';
            $order_by_id = 'DESC';
        }

        $query = 'SELECT users.ID, comments_approved.count as comments_count FROM ' . $table_users . ' as users
        
            LEFT JOIN (
                SELECT count(comments.user_id) as count, comments.user_id FROM ' . $table_comments . ' as comments
                
                LEFT JOIN ' . $table_posts . ' as posts
                ON posts.post_status = "publish"

                WHERE comments.comment_approved = 1 AND comments.comment_post_ID = posts.ID

                GROUP BY comments.user_id
            ) as comments_approved

            ON comments_approved.user_id = users.ID

            WHERE 1 = 1 ';

        if(!is_null($include_only)) {
            $query .= ' AND users.ID IN (' . $include_only . ')';
        }
        if(!is_null($exclude)) {
            $query .= ' AND users.ID NOT IN (' . $exclude . ')';
        }

        $query .= ' ORDER BY comments_count ' . $order_by_count . ', users.ID ' . $order_by_id;


        $user_ids_by_comments_count = $wpdb->get_col($query, 0);

        $query_args['include'] = $user_ids_by_comments_count;
        $query_args['orderby'] = 'include';
        $query_args['order'] = 'ASC'; // need to use asc because we need follow array order

        return $query_args;
    }
}