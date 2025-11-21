<?php


function add_schema_markup() {
    if (is_single()) {
        ?>
        <script type="application/ld+json">
        {
          "@context": "https://schema.org",
          "@type": "Article",
          "mainEntityOfPage": {
            "@type": "WebPage",
            "@id": "<?php the_permalink(); ?>"
          },
          "headline": "<?php the_title(); ?>",
          "datePublished": "<?php echo get_the_date('c'); ?>",
          "dateModified": "<?php echo get_the_modified_date('c'); ?>",
          "author": {
            "@type": "Person",
            "name": "<?php the_author(); ?>"
          },
          "publisher": {
            "@type": "Organization",
            "name": "<?php bloginfo('name'); ?>",
            "logo": {
              "@type": "ImageObject",
              "url": "<?php echo get_site_icon_url(); ?>"
            }
          }
        }
        </script>
        <?php
    }
}
add_action('wp_head', 'add_schema_markup');
