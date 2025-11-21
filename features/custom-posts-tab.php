


// posts cat

add_action('admin_menu', 'my_custom_posts_menu');
function my_custom_posts_menu() {
    add_menu_page(
        'My Posts',
        'My Posts',
        'manage_options',
        'my-posts-all',
        'my_posts_all_callback',
        'dashicons-admin-post',
        5
    );

    $categories = get_categories(array('hide_empty' => false));

    foreach ($categories as $category) {
        add_submenu_page(
            'my-posts-all',
            $category->name . ' Posts',
            $category->name,
            'manage_options',
            'my-posts-cat-' . $category->slug,
            function() use ($category) {
                my_posts_category_callback($category);
            }
        );
    }
}



add_action('admin_init', 'redirect_to_my_custom_menu');
function redirect_to_my_custom_menu() {
    global $pagenow;

    // Check if user is in admin and has permission
    if (!is_admin() || !current_user_can('manage_options')) {
        return;
    }

    // Redirect dashboard
    if ($pagenow === 'index.php' && !isset($_GET['redirected'])) {
        wp_redirect(admin_url('admin.php?page=my-posts-all&redirected=true'));
        exit;
    }

    // Redirect About page
    if ($pagenow === 'admin.php' && isset($_GET['page']) && $_GET['page'] === 'my-posts-about' && !isset($_GET['redirected'])) {
        wp_redirect(admin_url('admin.php?page=my-posts-all&redirected=true'));
        exit;
    }
}

add_action('admin_init', 'redirect_about_php_to_my_posts');
function redirect_about_php_to_my_posts() {
    global $pagenow;

    if (
        is_admin() &&
        $pagenow === 'about.php' &&
        current_user_can('manage_options') &&
        !isset($_GET['redirected'])
    ) {
        wp_redirect(admin_url('admin.php?page=my-posts-all&redirected=true'));
        exit;
    }
}




function my_posts_all_callback() {
    ?>
    <div class="wrap">
        <h1>All Posts</h1>
        <?php display_posts_table(); ?>
    </div>
    <?php
}

function my_posts_category_callback($category) {
    ?>
    <div class="wrap">
        <h1>Posts in Category: <?php echo esc_html($category->name); ?></h1>
        <?php display_posts_table($category->term_id); ?>
    </div>
    <?php
}

function display_posts_table($category_id = 0) {
    $args = array(
        'posts_per_page' => -1,
        'post_type'      => 'post',
        'post_status'    => 'publish',
    );

    if ($category_id) {
        $args['cat'] = $category_id;
    }

    $posts = get_posts($args);

    $all_categories = get_categories(array('hide_empty' => false)); // include empty categories

    if ($posts) {
        echo '<form method="post" action="">';

        // Security nonce for bulk update
        wp_nonce_field('bulk_update_post_categories', 'bulk_update_nonce');

        echo '<a href="' . admin_url('post-new.php') . '" class="page-title-action" style="margin-bottom:10px;display:inline-block;">Add New Post</a> ';
        echo '<a href="' . admin_url('edit-tags.php?taxonomy=category') . '" class="page-title-action" style="margin-bottom:10px;display:inline-block;">Add / Edit Categories</a>';

        echo '<table class="wp-list-table widefat fixed striped">';
        echo '<thead><tr>';
        echo '<th style="width:30px;"><input type="checkbox" id="cb-select-all" /></th>';
        echo '<th>Post Title</th>';
        echo '<th>Categories</th>';
        echo '<th>Edit</th>';
        echo '</tr></thead><tbody>';

        foreach ($posts as $post) {
            $categories = get_the_category($post->ID);
            $cat_names = array();
            if ($categories) {
                foreach ($categories as $cat) {
                    $cat_names[] = esc_html($cat->name);
                }
            }

            echo '<tr>';
            echo '<td><input type="checkbox" name="post_ids[]" value="' . $post->ID . '" class="cb-post" /></td>';
            echo '<td>' . esc_html($post->post_title) . '</td>';
            echo '<td>' . implode(', ', $cat_names) . '</td>';
            echo '<td><a href="' . get_edit_post_link($post->ID) . '">Edit</a></td>';
            echo '</tr>';
        }

        echo '</tbody></table>';

        // Category dropdown to bulk assign
        echo '<p><label for="bulk_category">Change category of selected posts to: </label>';


echo '<div style="max-height:150px; overflow-y:auto; border:1px solid #ccc; padding:8px; margin-bottom:10px;">';

foreach ($all_categories as $cat) {
    $cat_id = $cat->term_id;
    $cat_name = esc_html($cat->name);
    echo '<label style="display:block; margin-bottom:4px;">';
    echo '<input type="checkbox" name="bulk_categories[]" value="' . $cat_id . '" > ' . $cat_name;
    echo '</label>';
}

echo '</div>';



        echo '<input type="submit" class="button button-primary" value="Update Categories"></p>';

        echo '</form>';

        // Add JS for select all checkbox
        ?>
        <script>
        document.getElementById('cb-select-all').addEventListener('change', function() {
            var checked = this.checked;
            document.querySelectorAll('.cb-post').forEach(function(checkbox){
                checkbox.checked = checked;
            });
        });
        </script>
        <?php
    } else {
        echo '<p>No posts found.</p>';
    }
}


add_action('admin_init', 'handle_post_category_update');

function handle_post_category_update() {
    if (!isset($_POST['post_id']) || !isset($_POST['post_categories']) || !isset($_POST['update_post_cats_nonce'])) {
        return;
    }

    $post_id = intval($_POST['post_id']);
    $categories = array_map('intval', (array) $_POST['post_categories']);

    if (!wp_verify_nonce($_POST['update_post_cats_nonce'], 'update_post_cats_' . $post_id)) {
        wp_die('Nonce verification failed.');
    }

    if (!current_user_can('edit_post', $post_id)) {
        wp_die('You do not have permission to edit this post.');
    }

    // Update the post categories
    wp_set_post_categories($post_id, $categories);

    // Redirect to avoid resubmission
    wp_redirect(remove_query_arg(array('post_id', 'post_categories', 'update_post_cats_nonce')));
    exit;
}


add_action('admin_init', 'handle_bulk_category_update');

function handle_bulk_category_update() {
    if ( ! isset($_POST['bulk_update_nonce']) || ! wp_verify_nonce($_POST['bulk_update_nonce'], 'bulk_update_post_categories') ) {
        return;
    }

if ( empty($_POST['post_ids']) || empty($_POST['bulk_categories']) || !is_array($_POST['bulk_categories']) ) {
    return;
}

    if ( ! current_user_can('edit_posts') ) {
        wp_die('You do not have permission to edit posts.');
    }


$post_ids = array_map('intval', $_POST['post_ids']);
$category_ids = array_map('intval', $_POST['bulk_categories']);

foreach ($post_ids as $post_id) {
    if ( current_user_can('edit_post', $post_id) ) {
        // Replace all categories of post with selected categories (multiple)
        wp_set_post_categories($post_id, $category_ids);
    }
}

    // Redirect back to avoid resubmission and to current admin page
$redirect_url = wp_get_referer();
if ( ! $redirect_url ) {
    $redirect_url = admin_url('admin.php?page=my-posts-all');
}
// Add query arg to show notice
$redirect_url = add_query_arg('bulk_update', 'success', $redirect_url);
wp_safe_redirect( $redirect_url );
exit;

}


add_action('admin_notices', function() {
    if ( isset($_GET['bulk_update']) && $_GET['bulk_update'] == 'success' ) {
        echo '<div class="notice notice-success is-dismissible"><p>Posts categories updated successfully.</p></div>';
    }
});
