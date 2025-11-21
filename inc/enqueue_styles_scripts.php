<?php 

function obsidian_enqueue_styles() {
	$theme_version = wp_get_theme()->get('Version');

/*
	wp_enqueue_style("progressbar-css", get_template_directory_uri(). "/assets/css/progressbar.css");
	wp_enqueue_script("progressbar-js", get_template_directory_uri(). "/assets/js/progressbar.js");
*/

	wp_enqueue_style('Notoria-style', get_stylesheet_uri());
	wp_enqueue_style("light-dark-css", get_template_directory_uri(). "/assets/css/00.light-dark.css");
	wp_enqueue_style("app-css", get_template_directory_uri(). "/assets/css/01.app.css");
	wp_enqueue_style("header-footer-css", get_template_directory_uri(). "/assets/css/02.header-footer.css");
	wp_enqueue_style("page-css", get_template_directory_uri(). "/assets/css/03.page.css");
	wp_enqueue_style("04container-css", get_template_directory_uri(). "/assets/css/04.container.css");
	wp_enqueue_style("note-highlight", get_template_directory_uri(). "/assets/css/note-highlight.css");
	wp_enqueue_style("copy-btn-css", get_template_directory_uri(). "/assets/css/06.copy-btn.css");

	wp_enqueue_style("post-navigation-css", get_template_directory_uri(). "/assets/css/10.post-navigation.css");



	wp_enqueue_style("sidebar-css", get_template_directory_uri(). "/assets/css/07.sidebar.css");
	wp_enqueue_script("sidebar-js", get_template_directory_uri(). "/assets/js/01.sidebar.js");


	wp_enqueue_script("nav-js", get_template_directory_uri(). "/assets/js/nav.js");

	wp_enqueue_script("dark-light-js", get_template_directory_uri(). "/assets/js/02.dark-light.js", array(), $theme_version, true);

/*
	wp_enqueue_style("style-css", get_template_directory_uri(). "/assets/css/style.css", array(), $theme_version);
	wp_enqueue_script("script-js", get_template_directory_uri(). "/assets/js/script.js", array(), $theme_version, true);
*/

	wp_enqueue_style("prism-css", get_template_directory_uri(). "/assets/prism/prism.css", array(), $theme_version);
	wp_enqueue_script("prism-js", get_template_directory_uri(). "/assets/prism/prism.js", array(), $theme_version, true);
	wp_enqueue_script("html2canvas.min", get_template_directory_uri(). "/assets/js/html2canvas.min.js", array(), $theme_version, true);

}

add_action('wp_enqueue_scripts', 'obsidian_enqueue_styles');
