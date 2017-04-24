<?php

namespace Roots\Sage\Titles;

/**
 * Page titles
 */
function title() {
    if (is_home()) {
        if (get_option('page_for_posts', true)) {
            return get_the_title(get_option('page_for_posts', true));
        } else {
            return __('Neueste Beiträge', 'sage');
        }
    } elseif (is_archive()) {
        return get_the_archive_title();
    } elseif (is_search()) {
        return sprintf(__('Suchergebnisse für %s', 'sage'), get_search_query());
    } elseif (is_404()) {
        return __('Nicht gefunden', 'sage');
    } else {
        return get_the_title();
    }
}
