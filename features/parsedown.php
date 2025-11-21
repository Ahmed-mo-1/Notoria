<?php
function obsidian_custom_markdown_parser( $content ) {
    $parsedown = new Parsedown();
    $parsedown->setSafeMode(true);

    // Convert markdown to HTML
    $html = $parsedown->text( $content );

    // Convert task list checkboxes manually after markdown parsing
/*
    $html = preg_replace_callback('/<li>\s*\[([ xX])\]\s*(.*?)<\/li>/', function ($matches) {
        $checked = strtolower($matches[1]) === 'x' ? 'checked' : '';
        $text = $matches[2];

        return '<li><input type="checkbox" disabled ' . $checked . '> ' . esc_html($text) . '</li>';
    }, $html);
*/


// Add copy button to <pre><code> blocks
$html = preg_replace_callback('/<pre><code( class="[^"]*")?>(.*?)<\/code><\/pre>/s', function ($matches) {
    $class = isset($matches[1]) ? $matches[1] : '';
    $code = htmlspecialchars_decode($matches[2]);

    // Wrap in a container for styling and JS targeting
    return '<div class="code-container"><button class="copy-button">Copy</button><pre><code' . $class . '>' . esc_html($code) . '</code></pre></div>';
}, $html);


    return $html;
}

function obsidian_minimal_editor_display_content( $content ) {
    if ( in_the_loop() && is_main_query() && get_post_type() === 'post' ) {
        $custom_content = get_post_meta( get_the_ID(), '_obsidian_minimal_content', true );
        if ( ! empty( $custom_content ) ) {
            $html = obsidian_custom_markdown_parser( $custom_content );

            // Run our RTL paragraph fixer
            $html = obsidian_add_rtl_to_arabic_content( $html );

            return $html;
        }
    }
    return $content;
}
add_filter( 'the_content', 'obsidian_minimal_editor_display_content', 20 );
