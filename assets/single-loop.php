<?php
    $title = get_the_title();
	$date = get_the_date();
    $dir = contains_arabic($title) ? 'rtl' : 'ltr';
    $text_align = contains_arabic($title) ? 'right' : 'left';
    $font_family = contains_arabic($title) ? 'cairo' : 'geist mono';

    $categories = get_the_category();
    $cat_classes = '';
    if ( ! empty($categories) ) {
        foreach ( $categories as $category ) {
            $cat_classes .= ' cat-' . esc_attr($category->slug);
        }
    }
?>
<article id="post-<?php the_ID(); ?>" class="post-item<?php echo $cat_classes; ?>">
<h2 dir="<?php echo $dir; ?>" style="cursor: pointer; color: var(--main-color); text-align: <?php echo $text_align; ?>; font-family: <?php echo $font_family; ?>; margin: 0" >
<?php echo esc_html($title); ?>
</h2>
<p dir="<?php echo $dir; ?>" style="margin: 0 0 20px 0 ; font-size: 12px"><?php echo $date; ?></p>
<div class="post-content">
<?php the_content(); ?>
</div>

</article>