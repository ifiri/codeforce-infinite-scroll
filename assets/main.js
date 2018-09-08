(function($) {
    // Starts from 2nd page every time because the first already loaded earlier
    var current_page = 2;

    var $document = $(document);

    // $document.on('ready', function() {
        var $window = $(window);

        var $load_more_button = $document.find('#cfis-load-more');
        
        if(!$load_more_button.length) {
            return;
        }

        var $load_more_button_wrap = $load_more_button.parent();

        var roles = $load_more_button.attr('data-roles') || null;
        var include = $load_more_button.attr('data-include') || null;
        var exclude = $load_more_button.attr('data-exclude') || null;
        var orderby = $load_more_button.attr('data-orderby') || null;
        var order = $load_more_button.attr('data-order') || null;
        var per_page = $load_more_button.attr('data-per-page') || null;
        var mode = $load_more_button.attr('data-mode') || null;

        var category = $load_more_button.attr('data-category') || null;
        var taxonomy = $load_more_button.attr('data-taxonomy') || null;
        var taxonomy_terms = $load_more_button.attr('data-taxonomy_terms') || null;
        var post_status = $load_more_button.attr('data-post_status') || null;
        var tag = $load_more_button.attr('data-tag') || null;
        var search = $load_more_button.attr('data-search') || null;
        var author = $load_more_button.attr('data-author') || null;

        var container = $load_more_button.attr('data-container') || null;

        var $content_container = container? $(container) : $document.find('.all-users');


        // Main action, in this handler we load more users by ajax
        $window.on('scroll', function() {
            if(!$content_container.length || !$load_more_button.length) {
                return;
            }

            var current_scroll_position = $document.scrollTop() + $window.height();
            var button_position = $load_more_button.offset().top;

            // Check current scroll position and button state
            if(
                button_position <= current_scroll_position + 100 
                && 
                !$load_more_button.is(':disabled')
                &&
                !$load_more_button.hasClass('done')
            ) {
                if(typeof l10n === 'undefined') {
                    return;
                }

                var action = '';

                switch(mode) {
                    case 'posts':
                        action = 'cfis_load_more_posts';
                        break;

                    default:
                        action = 'cfis_load_more_users';
                        break;
                }

                var data = {
                    'action': action,
                    'current_page': current_page,
                    'roles': roles,
                    'include': include,
                    'exclude': exclude,
                    'orderby': orderby,
                    'order': order,
                    'per_page': per_page,
                    'nonce': l10n.nonce,

                    'category': category,
                    'taxonomy': taxonomy,
                    'taxonomy_terms': taxonomy_terms,
                    'post_status': post_status,
                    'tag': tag,
                    'search': search,
                    'author': author,
                };

                // Send the ajax request and handle responses
                $.post(l10n.ajax_url, data).done(function(response) {
                    response = JSON.parse(response);

                    set_load_more_button_state('active');

                    // Do something depends of response
                    if(response.data && typeof response.data === 'object') {
                        if(response.data.done) {
                            set_load_more_button_state('done');
                        }

                        if(response.data.layout) {
                            console.log($content_container);
                            $content_container.append(response.data.layout);
                        }
                    }

                    current_page++;
                }).fail(function(response) {
                    response = JSON.parse(response);

                    set_load_more_button_state('active');

                    // Do something depends of response
                    if(response.data && typeof response.data === 'object') {
                        if(response.data.done) {
                            set_load_more_button_state('done');
                        }
                    }
                });

                // On scroll, change button state
                set_load_more_button_state('loading');
            }
        }).trigger('scroll');

        function set_load_more_button_state(state) {
            switch(state) {
                case 'loading':
                    $load_more_button.addClass('loading');
                    $load_more_button.prop('disabled', true);
                    break;

                case 'active':
                    $load_more_button.removeClass('loading');
                    $load_more_button.removeProp('disabled');
                    break;

                case 'done':
                    $load_more_button.addClass('done');
                    break;
            }
        }
    // });
})(jQuery);