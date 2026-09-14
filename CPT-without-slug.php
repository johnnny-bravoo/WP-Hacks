<?php
// List ALL post types you want without slug
function flat_cpts_list() {
    return array('city', 'motorcycle-accident', 'areas-we-serve', 'car-accident', 'bus-accident', 'truck-accident', 'brain-injury', 'defective-product', 'dog-bite-injury', 'drowning-accident', 'medical-malpractice', 'slip-fall-accident', 'wrongful-death');
}


/**
 * Remove slug from permalink
 */
function flat_cpt_remove_slug($post_link, $post, $leavename) {

    $post_types = flat_cpts_list();

    if (in_array($post->post_type, $post_types) && $post->post_status === 'publish') {
        return home_url('/' . $post->post_name . '/');
    }

    return $post_link;
}
add_filter('post_type_link', 'flat_cpt_remove_slug', 10, 3);


function flat_cpt_parse_request($wp) {

    if (is_admin()) {
        return;
    }

    $request = trim($wp->request, '/');
    if (empty($request)) {
        return;
    }

    // Real page? Let WP handle it.
    if (get_page_by_path($request, OBJECT, 'page')) {
        return;
    }

    // --- Single-segment flat CPT URLs (your existing behavior) ---
    if (strpos($request, '/') === false) {
        foreach (flat_cpts_list() as $post_type) {
            $post = get_page_by_path($request, OBJECT, $post_type);
            if ($post) {
                $wp->query_vars['post_type'] = $post_type;
                $wp->query_vars['name']      = $request;
                $wp->is_single = true;
                $wp->is_page   = false;
                $wp->is_404    = false;
                return;
            }
        }
        return;
    }

    // --- Two-segment category-post URLs: /{category}/{post}/ ---
    $parts = explode('/', $request);
    if (count($parts) === 2) {
        list($cat_slug, $post_slug) = $parts;

        // Only intervene when the first segment is a real category
        $category = get_category_by_slug($cat_slug);
        if ($category) {
            // Resolve the post by slug within the default 'post' type
            $post = get_page_by_path($post_slug, OBJECT, 'post');
            if ($post) {
                $wp->query_vars['post_type'] = 'post';
                $wp->query_vars['name']      = $post_slug;
                $wp->query_vars['category_name'] = $cat_slug;
                $wp->is_single = true;
                $wp->is_page   = false;
                $wp->is_404    = false;
                return;
            }
        }
        // Not a category-post match → let WP continue normally
    }
}
add_action('parse_request', 'flat_cpt_parse_request', 5);



/**
 * Parse request for flat CPT URLs
 
function flat_cpt_parse_request($wp) {

    if (is_admin()) {
        return;
    }

    $request = trim($wp->request, '/');

    // Ignore empty or nested URLs
    if (empty($request) || strpos($request, '/') !== false) {
        return;
    }

    // If it's a real page, let WP handle it
    if (get_page_by_path($request, OBJECT, 'page')) {
        return;
    }

    foreach (flat_cpts_list() as $post_type) {

        $post = get_page_by_path($request, OBJECT, $post_type);

        if ($post) {
            $wp->query_vars['post_type'] = $post_type;
            $wp->query_vars['name'] = $request;
            $wp->is_single = true;
            $wp->is_page = false;
            $wp->is_404 = false;
            return;
        }
    }
}
add_action('parse_request', 'flat_cpt_parse_request', 5);
*/


/*
add_action( 'add_attachment', function( $post_ID ) {

    // Prevent REST imports if needed
    if ( defined('REST_REQUEST') && REST_REQUEST ) {
        return;
    }

    // Make sure it's an image
    if ( wp_attachment_is_image( $post_ID ) ) {

        // Get image caption (stored in post_excerpt)
        $caption = get_post_field( 'post_excerpt', $post_ID );

        if ( ! empty( $caption ) ) {
            update_post_meta( $post_ID, '_wp_attachment_image_alt', $caption );
        }
    }

});



add_action('init', function () {
    global $wp_rewrite;

    if (is_admin()) {
        $wp_rewrite->flush_rules(false);
    }
});

add_action('init', function () {

    add_rewrite_rule(
        '^medical-malpractice/([^/]+)/?$',
        'index.php?name=$matches[1]',
        'top'
    );

    add_rewrite_rule(
        '^wrongful-death/([^/]+)/?$',
        'index.php?name=$matches[1]',
        'top'
    );

});
*/

?>
