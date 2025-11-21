<?php get_header(); ?>
<?php //include 'assets/progress-bar.php'; ?>
<?php if ( have_posts() ) : ?>

<?php
function contains_arabic($text) {
    return preg_match('/[\x{0600}-\x{06FF}\x{0750}-\x{077F}]/u', $text);
}
?>

<div class="articles">
	<?php while ( have_posts() ) : the_post(); ?>
	<?php include 'assets/loop.php'; ?>
	<?php endwhile; ?>
</div>

<?php
	the_posts_pagination([
		'mid_size' => 1,
		'prev_text' => __(''),
		'next_text' => __(''),
		'screen_reader_text' => __(' '),
	]);
?>

<?php else : ?>
  <p>No posts found.</p>
<?php endif; ?>


<?php get_footer(); ?>
