<?php

namespace CodeForceInfiniteScroll\App\AjaxActions;

use CodeForceInfiniteScroll\App\PostQuery;

class LoadMorePosts
{
    private $allowed_args = ['include', 'exclude', 'roles', 'orderby', 'order', 'post_status', 'taxonomy', 'taxonomy_terms', 'tag', 'category', 'search', 'author'];

    private $custom_order_modes = array('comments_count');

    public function execute() 
    {
        $response = [
            'status' => 'error', 
            'message' => '',
        ];

        // Check nonce
        if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'cfis_nonce')) {
            echo json_encode($response);
            wp_die();
        }

        // Set additional params to response
        $response['status'] = 'success';
        $response['data'] = [
            'done' => false,
        ];

        // Get current page and other query args
        $PostsQueryWrapper = new PostQuery\Wrapper();
        
        $posts_per_page = $PostsQueryWrapper::POSTS_PER_PAGE;
        if (isset($_POST['per_page'])) {
            $posts_per_page = (int)$_POST['per_page'];
        }

        $current_page = $_POST['current_page'];
        if (!is_numeric($current_page)) {
            $current_page = 2;
        }
        
        $offset = ($current_page - 1) * $posts_per_page;
        
        // Build default and user arguments
        $default_args = array(
            'number' => $posts_per_page,
            'offset' => $offset,
            'paged' => $current_page,

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
        $post_args = $this->fill_arguments_from($_POST);

        // Build query arguments array
        $query_args = array_merge($default_args, $post_args);

        // Get Wp_User_Query via Wrapper
        $PostQuery = $PostsQueryWrapper->getPostQuery($query_args);

        // Create layout and set it to response
        ob_start();
        foreach ($PostQuery->posts as $post) {
            $PostQuery->the_post();
            set_query_var('post', $post);
            get_template_part('templates/partials/post', 'entry');
        }
        $response['data']['layout'] = ob_get_clean();

        // Get total pages count
        $total_posts = $PostQuery->found_posts;
        $total_pages = ceil($total_posts / $posts_per_page);

        // If all users are grabbed or no results more
        if ($current_page >= $total_pages || !count($PostQuery->posts)) {
            $response['data']['done'] = true;
        }

        echo json_encode($response);
        wp_die();
    }

    /**
     * Set existing arguments up from passed array, 
     * and returns it. Non-allowed arguments are ignored.
     * 
     * @param array $user_arguments 
     * @return array
     */
    private function fill_arguments_from(Array $user_arguments) 
    {
        $args = [];
        
        foreach ($user_arguments as $arg_alias => $arg_value) {
            if (!in_array($arg_alias, $this->allowed_args) || !$arg_value) {
                continue;
            }

            $sanitized_value = filter_var($arg_value, FILTER_SANITIZE_STRING);
            $args[$arg_alias] = $sanitized_value;
        }

        return $args;
    }
}
