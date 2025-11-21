<?php
function replace_default_editor_with_minimal_editor() {
    $screen = get_current_screen();
    if ( $screen && $screen->base === 'post' && $screen->post_type === 'post' ) {
        // Remove the default editor metabox
        remove_post_type_support( 'post', 'editor' );

        // Add your custom editor metabox
        add_meta_box(
            'obsidian_minimal_editor_metabox',
            'Minimal Editor',
            'obsidian_minimal_editor_metabox_callback',
            'post',
            'normal',
            'high'
        );
    }
}
add_action( 'current_screen', 'replace_default_editor_with_minimal_editor' );

function obsidian_minimal_editor_metabox_callback( $post ) {
    // Use nonce for verification
    wp_nonce_field( 'obsidian_minimal_editor_save', 'obsidian_minimal_editor_nonce' );

    // Get existing content
    $content = get_post_meta( $post->ID, '_obsidian_minimal_content', true );

    // Output your custom textarea styled like Obsidian
    ?>
    <textarea name="obsidian_minimal_content" style="width:100%; height:600px; background:#121212; color:#e0e0e0; border:none; padding:1rem; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; font-size:16px; line-height:1.6; border-radius: 15px;
    resize: none;"><?php echo esc_textarea( $content ); ?></textarea>
    <?php
}

// Save your custom editor content when post saves
function obsidian_minimal_editor_save_post( $post_id ) {
    // Verify nonce
    if ( ! isset( $_POST['obsidian_minimal_editor_nonce'] ) ||
         ! wp_verify_nonce( $_POST['obsidian_minimal_editor_nonce'], 'obsidian_minimal_editor_save' ) ) {
        return;
    }

    // Check autosave
    if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) {
        return;
    }

    // Check user permission
    if ( ! current_user_can( 'edit_post', $post_id ) ) {
        return;
    }

    /*if ( isset( $_POST['obsidian_minimal_content'] ) ) {
        update_post_meta( $post_id, '_obsidian_minimal_content', sanitize_textarea_field( $_POST['obsidian_minimal_content'] ) );
    }*/

	if ( isset( $_POST['obsidian_minimal_content'] ) ) {
		update_post_meta( 
			$post_id, 
			'_obsidian_minimal_content', 
			wp_unslash( $_POST['obsidian_minimal_content'] ) 
		);
	}


}
add_action( 'save_post', 'obsidian_minimal_editor_save_post' );


add_action('wp_footer', function() {
    if ( is_singular('post') ) {
        $custom_content = get_post_meta( get_the_ID(), '_obsidian_minimal_content', true );
        echo '<!-- Custom content saved: ' . esc_html( $custom_content ) . ' -->';
    }
});

