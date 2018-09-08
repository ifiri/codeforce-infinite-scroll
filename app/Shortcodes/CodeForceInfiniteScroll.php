<?php

namespace CodeForceInfiniteScroll\App\Shortcodes;

use CodeForceInfiniteScroll\App\Templates;

class CodeForceInfiniteScroll 
{
    /**
     * Render "Load More" button and return rendered content
     * 
     * @param mixed $user_attrs 
     * @return string
     */
    public function shortcode($user_attrs = null) 
    {
        $TemplatesRenderer = new Templates\Renderer;

        $default_attrs = array(
            'button_text' => __('Load More', 'codeforce-infinite-scroll'),

            'roles' => array(),
            'include' => null,
            'exclude' => null,
            'orderby' => null,
            'order' => null,
            'per_page' => null,

            'mode' => 'users',

            'category' => null,
            'tag' => null,
            'taxonomy' => null,
            'taxonomy_terms' => null,
            'post_status' => null,

            'container' => null,
            'search' => null,
            'author' => null,
        );

        $template_attrs = shortcode_atts($default_attrs, $user_attrs);

        return $TemplatesRenderer->render('load-more-button', $template_attrs);
    }
}
