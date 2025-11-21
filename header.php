<!DOCTYPE html>
<html <?php language_attributes(); ?>>

<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>" />
	<meta name="viewport" content="width=device-width, initial-scale=1"/>
	<meta name="description" content="<?php bloginfo('description');?>">
	<meta name="robots" content="index, follow">
	<title><?php wp_title('|', true, 'right'); bloginfo('name'); ?></title>
	<?php wp_head(); ?>

<style>
@font-face {
  font-family: 'Geist Mono';
  src: url('<?php echo get_template_directory_uri(); ?>/assets/fonts/GeistMono-Regular.woff2') format('woff2');
  font-weight: 100 900;
  font-style: normal;
  font-display: swap;
}

@font-face {
  font-family: 'Cairo';
  src: url('<?php echo get_template_directory_uri(); ?>/assets/fonts/Cairo-Regular.woff2') format('woff2');
  font-weight: 200 1000;
  font-style: normal;
  font-display: swap;
}
</style>

</head>

<body <?php body_class(); ?>>

	<header>
		<a href="<?php bloginfo('home'); ?>"><?php bloginfo('name'); ?></a>
		<button id="theme-toggle" aria-label="Toggle dark and light mode"></button>
	</header>


<div class="page" style="">
<?php get_sidebar(); ?>
	<div class="container">