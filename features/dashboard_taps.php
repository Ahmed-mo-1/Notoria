<?php

add_action('admin_menu', function() {
    add_menu_page('Menu Visibility', 'Menu Visibility', 'manage_options', 'menu-visibility', 'menu_visibility_page_callback', 'dashicons-visibility', 60);
});


function menu_visibility_page_callback() {
    if ( ! current_user_can('manage_options') ) {
        wp_die('Unauthorized');
    }

    $menu_items = [
        'edit.php' => 'Posts',
        'upload.php' => 'Media',
        'edit.php?post_type=page' => 'Pages',
        'edit-comments.php' => 'Comments',
        'themes.php' => 'Appearance',
        'plugins.php' => 'Plugins',
        'users.php' => 'Users',
        'tools.php' => 'Tools',
        'options-general.php' => 'Settings',
        'index.php' => 'Dashboard',
        // add more if you want
    ];

    // Handle form submission
    if ( isset($_POST['menu_visibility_nonce']) && wp_verify_nonce($_POST['menu_visibility_nonce'], 'save_menu_visibility') ) {
        $visible_menus = isset($_POST['visible_menus']) ? array_map('sanitize_text_field', $_POST['visible_menus']) : [];
        update_option('custom_menu_visibility', $visible_menus);
        echo '<div class="updated"><p>Settings saved. Refresh the Page</p></div>';
    }

    $saved = get_option('custom_menu_visibility', array_keys($menu_items)); // default all visible

    ?>
    <div class="wrap">
        <h1>Menu Visibility Settings</h1>
        <form method="post">
            <?php wp_nonce_field('save_menu_visibility', 'menu_visibility_nonce'); ?>
            <table class="form-table">
                <tbody>
                    <?php foreach ($menu_items as $slug => $label): ?>
                    <tr>
                        <th scope="row"><?php echo esc_html($label); ?></th>
                        <td>
                            <input type="checkbox" name="visible_menus[]" value="<?php echo esc_attr($slug); ?>" id="menu_<?php echo esc_attr(sanitize_title($slug)); ?>"
                                <?php checked(in_array($slug, $saved)); ?> />
                            <label for="menu_<?php echo esc_attr(sanitize_title($slug)); ?>">Show</label>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <?php submit_button('Save Settings'); ?>
        </form>
    </div>
    <?php
}


add_action('admin_menu', function() {
    $visible_menus = get_option('custom_menu_visibility', []);

    if (empty($visible_menus)) {
        // If none selected, hide all default menus
        $menus_to_hide = [
            'edit.php',
            'upload.php',
            'edit.php?post_type=page',
            'edit-comments.php',
            'themes.php',
            'plugins.php',
            'users.php',
            'tools.php',
            'options-general.php',
            'index.php',
        ];
    } else {
        $menus_to_hide = [
            'edit.php',
            'upload.php',
            'edit.php?post_type=page',
            'edit-comments.php',
            'themes.php',
            'plugins.php',
            'users.php',
            'tools.php',
            'options-general.php',
            'index.php',
        ];
        // Remove from hide list those that user wants visible
        $menus_to_hide = array_diff($menus_to_hide, $visible_menus);
    }

    foreach ($menus_to_hide as $menu_slug) {
        remove_menu_page($menu_slug);
    }
}, 999);


