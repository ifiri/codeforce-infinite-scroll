<div class="cfis-btn-wrap">
    <button id="cfis-load-more" class="cfis-load-more-btn more"<?php
        if($roles) :
            echo ' data-roles="' . filter_var($roles, FILTER_SANITIZE_STRING) . '"';
        endif; 

        if($include) :
            echo ' data-include="' . filter_var($include, FILTER_SANITIZE_STRING) . '"';
        endif;

        if($exclude) :
            echo ' data-exclude="' . filter_var($exclude, FILTER_SANITIZE_STRING) . '"';
        endif; 

        if($order) :
            echo ' data-order="' . filter_var($order, FILTER_SANITIZE_STRING) . '"';
        endif; 

        if($orderby) :
            echo ' data-orderby="' . filter_var($orderby, FILTER_SANITIZE_STRING) . '"';
        endif; 

        if($per_page) :
            echo ' data-per-page="' . filter_var($per_page, FILTER_SANITIZE_NUMBER_INT) . '"';
        endif; 

        if($mode) :
            echo ' data-mode="' . filter_var($mode, FILTER_SANITIZE_STRING) . '"';
        endif; 

        if($category) :
            echo ' data-category="' . filter_var($category, FILTER_SANITIZE_STRING) . '"';
        endif; 

        if($taxonomy) :
            echo ' data-taxonomy="' . filter_var($taxonomy, FILTER_SANITIZE_STRING) . '"';
        endif; 

        if($taxonomy_terms) :
            echo ' data-taxonomy_terms="' . filter_var($taxonomy_terms, FILTER_SANITIZE_STRING) . '"';
        endif; 

        if($post_status) :
            echo ' data-post_status="' . filter_var($post_status, FILTER_SANITIZE_STRING) . '"';
        endif; 

        if($tag) :
            echo ' data-tag="' . filter_var($tag, FILTER_SANITIZE_STRING) . '"';
        endif; 

        if($search) :
            echo ' data-search="' . filter_var($search, FILTER_SANITIZE_STRING) . '"';
        endif; 

        if($author) :
            echo ' data-author="' . filter_var($author, FILTER_SANITIZE_NUMBER_INT) . '"';
        endif; 

        if($container) :
            echo ' data-container="' . filter_var($container, FILTER_SANITIZE_STRING) . '"';
        endif; 
    ?>><?php echo $button_text ?></button>
</div>