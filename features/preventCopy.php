<?php
function prevent_copying_scripts() {
    ?>
    <script type="text/javascript">
        // Disable right-click
        document.addEventListener('contextmenu', function(e) {
            e.preventDefault();
        });

        // Disable text selection
        document.addEventListener('selectstart', function(e) {
            e.preventDefault();
        });

        // Disable copy keyboard shortcuts
        document.addEventListener('keydown', function(e) {
            if (
                (e.ctrlKey && (e.key === 'c' || e.key === 'u' || e.key === 'a' || e.key === 's')) || 
                (e.metaKey && (e.key === 'c' || e.key === 'u' || e.key === 'a' || e.key === 's'))
            ) {
                e.preventDefault();
            }
        });
    </script>
    <style>
        /* Optional: Disable text selection via CSS */
        body {
            -webkit-user-select: none; /* Safari */
            -moz-user-select: none;    /* Firefox */
            -ms-user-select: none;     /* IE10+/Edge */
            user-select: none;         /* Standard */
        }
    </style>
    <?php
}
add_action('wp_footer', 'prevent_copying_scripts');