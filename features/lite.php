<?php
// =====================
// PERFORMANCE OPTIMIZATION
// =====================

// ✅ Disable Emojis
function disable_emojis() {
    remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
    remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
    remove_action( 'wp_print_styles', 'print_emoji_styles' );
    remove_action( 'admin_print_styles', 'print_emoji_styles' );
    remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
    remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
    remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
    add_filter( 'tiny_mce_plugins', function( $plugins ) {
        return is_array( $plugins ) ? array_diff( $plugins, [ 'wpemoji' ] ) : [];
    } );
    add_filter( 'wp_resource_hints', function( $urls, $relation_type ) {
        if ( 'dns-prefetch' === $relation_type ) {
            $emoji_url = apply_filters( 'emoji_svg_url', 'https://s.w.org/images/core/emoji/2/svg/' );
            $urls = array_diff( $urls, [ $emoji_url ] );
        }
        return $urls;
    }, 10, 2 );
}
add_action( 'init', 'disable_emojis' );

// ✅ Remove Unnecessary Head Output
function clean_wp_head() {
    remove_action( 'wp_head', 'wp_generator' );
	remove_filter('wp_robots', 'wp_robots_max_image_preview_large');
    remove_action( 'wp_head', 'rsd_link' );
    remove_action( 'wp_head', 'wlwmanifest_link' );
    remove_action( 'wp_head', 'rest_output_link_wp_head', 10 );
    remove_action( 'template_redirect', 'rest_output_link_header', 11 );
    remove_action( 'wp_head', 'wp_oembed_add_discovery_links', 10 );
    remove_action( 'wp_head', 'wp_shortlink_wp_head', 10 );
    remove_action( 'wp_head', 'wp_oembed_add_host_js' );
}
add_action( 'init', 'clean_wp_head' );

// ✅ Disable XML-RPC
add_filter( 'xmlrpc_enabled', '__return_false' );

// ✅ Disable Heartbeat API
add_action( 'init', function() {
    if ( ! is_admin() ) {
        wp_deregister_script( 'heartbeat' );
    }
}, 1 );

// ✅ Disable Self Pingbacks
add_action( 'pre_ping', function( &$links ) {
    foreach ( $links as $key => $link ) {
        if ( 0 === strpos( $link, get_home_url() ) ) {
            unset( $links[$key] );
        }
    }
});

// ✅ Dequeue jQuery (only if not needed!)
function dequeue_jquery_for_guests() {
    if ( ! is_admin() && ! is_user_logged_in() ) {
        wp_dequeue_script( 'jquery' );
        wp_dequeue_script( 'jquery-core' );
        wp_dequeue_script( 'jquery-migrate' );
    }
}
add_action( 'wp_enqueue_scripts', 'dequeue_jquery_for_guests', 999 );

// ✅ Dequeue Block Editor Styles
function dequeue_block_styles() {
    if ( ! is_admin() ) {
        wp_dequeue_style( 'wp-block-library' );
        wp_dequeue_style( 'wp-block-library-theme' );
        wp_dequeue_style( 'global-styles' ); // WP 5.9+
    }
}
add_action( 'wp_enqueue_scripts', 'dequeue_block_styles', 999 );

// ✅ Disable Dashicons for non-logged-in users
function dequeue_dashicons() {
    if ( ! is_user_logged_in() ) {
        wp_deregister_style( 'dashicons' );
    }
}
add_action( 'wp_enqueue_scripts', 'dequeue_dashicons' );

// ✅ Remove wp-embed.js
function dequeue_wp_embed() {
    wp_deregister_script( 'wp-embed' );
}
add_action( 'wp_footer', 'dequeue_wp_embed' );

// ✅ Disable oEmbed functionality
function disable_embeds_code_init() {
    remove_action( 'rest_api_init', 'wp_oembed_register_route' );
    remove_filter( 'oembed_dataparse', 'wp_filter_oembed_result', 10 );
    remove_action( 'wp_head', 'wp_oembed_add_discovery_links' );
    remove_action( 'wp_head', 'wp_oembed_add_host_js' );
    add_filter( 'embed_oembed_discover', '__return_false' );
    add_filter( 'rewrite_rules_array', function( $rules ) {
        foreach ( $rules as $key => $rule ) {
            if ( false !== strpos( $key, 'embed=true' ) ) {
                unset( $rules[ $key ] );
            }
        }
        return $rules;
    } );
}
add_action( 'init', 'disable_embeds_code_init', 9999 );

// ✅ Remove version query strings from scripts and styles
function remove_version_from_assets( $src ) {
    return ( strpos( $src, '?ver=' ) !== false ) ? remove_query_arg( 'ver', $src ) : $src;
}
add_filter( 'style_loader_src', 'remove_version_from_assets', 15, 1 );
add_filter( 'script_loader_src', 'remove_version_from_assets', 15, 1 );
