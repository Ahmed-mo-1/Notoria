<?php

add_action('template_redirect', 'redirect_all_to_home');

function redirect_all_to_home() {
    if (is_admin() || is_front_page()) {
        return;
    }

    if (is_singular() || is_page()) {
        wp_redirect(home_url(), 301);
        exit;
    }
}