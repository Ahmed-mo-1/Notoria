<?php 
//logo 
function add_logo_management_menu() {
    add_menu_page(
        'Site Branding',
        'Site Branding',
        'manage_options',
        'site-branding',
        'site_branding_page',
        'dashicons-format-image',
        60
    );
}
add_action('admin_menu', 'add_logo_management_menu');

// Add theme support for custom logo
function theme_custom_logo_setup() {
    add_theme_support('custom-logo', array(
        'height'      => 100,
        'width'       => 400,
        'flex-height' => true,
        'flex-width'  => true,
    ));
}
add_action('after_setup_theme', 'theme_custom_logo_setup');

// Register favicon setting
function register_favicon_setting() {
    register_setting('site-branding-group', 'site_favicon');
}
add_action('admin_init', 'register_favicon_setting');

// Create the settings page
function site_branding_page() {
    wp_enqueue_media();
    
    // Get current logo ID
    $custom_logo_id = get_theme_mod('custom_logo');
    $logo_url = wp_get_attachment_image_url($custom_logo_id, 'full');
    
    // Get current favicon
    $favicon_url = get_option('site_favicon');
    
    // Handle form submission
    if (isset($_POST['update_branding'])) {
        // Update logo
        if (isset($_POST['logo_id'])) {
            if (!empty($_POST['logo_id'])) {
                set_theme_mod('custom_logo', intval($_POST['logo_id']));
            } else {
                remove_theme_mod('custom_logo');
            }
        }
        
        // Update favicon
        if (isset($_POST['favicon_url'])) {
            update_option('site_favicon', sanitize_url($_POST['favicon_url']));
        }
        
        echo '<div class="notice notice-success"><p>Branding settings updated successfully!</p></div>';
        
        // Refresh the URLs after update
        $custom_logo_id = get_theme_mod('custom_logo');
        $logo_url = wp_get_attachment_image_url($custom_logo_id, 'full');
        $favicon_url = get_option('site_favicon');
    }
    ?>
    <div class="wrap">
        <h1>Site Branding Settings</h1>
        <form method="post" action="">
            <table class="form-table">
                <tr>
                    <th scope="row">Site Logo</th>
                    <td>
                        <div class="logo-preview-wrapper" style="margin-bottom: 1em;">
                            <img id="logo-preview" src="<?php echo esc_url($logo_url); ?>" style="max-width: 300px; max-height: 150px; <?php echo $logo_url ? '' : 'display: none;'; ?>">
                        </div>
                        <input type="hidden" name="logo_id" id="logo-id" value="<?php echo esc_attr($custom_logo_id); ?>">
                        <input type="button" class="button-secondary" value="Choose Logo" id="upload-logo-button">
                        <input type="button" class="button-secondary" value="Remove Logo" id="remove-logo-button" style="<?php echo $custom_logo_id ? '' : 'display: none;'; ?>">
                        <p class="description">Choose your site logo. This will be used throughout your site including Elementor.</p>
                    </td>
                </tr>
                <tr>
                    <th scope="row">Favicon</th>
                    <td>
                        <div class="favicon-preview-wrapper" style="margin-bottom: 1em;">
                            <img id="favicon-preview" src="<?php echo esc_url($favicon_url); ?>" style="max-width: 64px; max-height: 64px; <?php echo $favicon_url ? '' : 'display: none;'; ?>">
                        </div>
                        <input type="hidden" name="favicon_url" id="favicon-url" value="<?php echo esc_attr($favicon_url); ?>">
                        <input type="button" class="button-secondary" value="Choose Favicon" id="upload-favicon-button">
                        <input type="button" class="button-secondary" value="Remove Favicon" id="remove-favicon-button" style="<?php echo $favicon_url ? '' : 'display: none;'; ?>">
                        <p class="description">Choose your favicon (recommended size: 32x32 pixels). This will appear in browser tabs.</p>
                    </td>
                </tr>
            </table>
            <input type="submit" name="update_branding" class="button-primary" value="Save Changes">
        </form>
    </div>

    <script>
    jQuery(document).ready(function($) {
        // Logo Upload
        var logoUploader;
        $('#upload-logo-button').click(function(e) {
            e.preventDefault();

            if (logoUploader) {
                logoUploader.open();
                return;
            }

            logoUploader = wp.media({
                title: 'Choose Site Logo',
                button: {
                    text: 'Set as site logo'
                },
                multiple: false
            });

            logoUploader.on('select', function() {
                var attachment = logoUploader.state().get('selection').first().toJSON();
                $('#logo-preview').attr('src', attachment.url).show();
                $('#logo-id').val(attachment.id);
                $('#remove-logo-button').show();
            });

            logoUploader.open();
        });

        $('#remove-logo-button').click(function() {
            $('#logo-preview').attr('src', '').hide();
            $('#logo-id').val('');
            $(this).hide();
        });

        // Favicon Upload
        var faviconUploader;
        $('#upload-favicon-button').click(function(e) {
            e.preventDefault();

            if (faviconUploader) {
                faviconUploader.open();
                return;
            }

            faviconUploader = wp.media({
                title: 'Choose Favicon',
                button: {
                    text: 'Set as favicon'
                },
                multiple: false
            });

            faviconUploader.on('select', function() {
                var attachment = faviconUploader.state().get('selection').first().toJSON();
                $('#favicon-preview').attr('src', attachment.url).show();
                $('#favicon-url').val(attachment.url);
                $('#remove-favicon-button').show();
            });

            faviconUploader.open();
        });

        $('#remove-favicon-button').click(function() {
            $('#favicon-preview').attr('src', '').hide();
            $('#favicon-url').val('');
            $(this).hide();
        });
    });
    </script>
    <?php
}

// Add favicon to front end
function add_site_favicon() {
    $favicon_url = get_option('site_favicon');
    if ($favicon_url) {
        echo '<link rel="icon" href="' . esc_url($favicon_url) . '">';
    }
}
add_action('wp_head', 'add_site_favicon');



