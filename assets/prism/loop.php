<?php
    $title = get_the_title();
	$date = get_the_date();
    $dir = contains_arabic($title) ? 'rtl' : 'ltr';
    $text_align = contains_arabic($title) ? 'right' : 'left';
    $font_family = contains_arabic($title) ? 'cairo' : 'geist mono';
?>

<article id="post-<?php the_ID(); ?>" class="post-item">
<h2 dir="<?php echo $dir; ?>" style="cursor: pointer; color: var(--main-color); text-align: <?php echo $text_align; ?>; font-family: <?php echo $font_family; ?>; margin: 0" >
<a href="<?php the_permalink(); ?>"><?php echo esc_html($title); ?></a>
</h2>
<p dir="<?php echo $dir; ?>" style="margin: 0 0 20px 0 ; font-size: 12px"><?php echo $date; ?></p>

</article>