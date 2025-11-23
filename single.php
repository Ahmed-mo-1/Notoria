<?php
// Redirect single post to home
//wp_redirect(home_url(), 301);
//exit;

get_header(); ?>
<?php //include 'assets/progress-bar.php'; ?>
<?php if ( have_posts() ) : ?>

<?php
function contains_arabic($text) {
    return preg_match('/[\x{0600}-\x{06FF}\x{0750}-\x{077F}]/u', $text);
}
?>
<!--<button onclick="takeScreenshot()" style="width: 10px; height: 10px; background: #151515 ; border-radius: 50px; border: none; position: absolute"></button>-->
<div id="screenshot-target" class="articles" style="">
	<?php while ( have_posts() ) : the_post(); ?>
	<?php include 'assets/single-loop.php'; ?>
	<?php endwhile; ?>
</div>

<script>
/*
function takeScreenshot() {
  const element = document.getElementById("screenshot-target");

  html2canvas(element, {
	scale: 3, 
	backgroundColor: null,
	useCORS: true
  }).then(canvas => {
	const link = document.createElement("a");
	link.download = "screenshot-black-bg.png";
	link.href = canvas.toDataURL("image/png");
	link.click();
  });
}
*/
</script>

  <script>
    function takeScreenshot() {
      const target = document.getElementById("screenshot-target");

      html2canvas(target, {
        scale: 3, // Increase resolution of the capture
        backgroundColor: null, // Use actual container background
        useCORS: true
      }).then(containerCanvas => {
        const finalSize = 2000;
        const padding = 100;

        // Create a new canvas of size 2000x2000
        const finalCanvas = document.createElement("canvas");
        finalCanvas.width = finalSize;
        finalCanvas.height = finalSize;

        const ctx = finalCanvas.getContext("2d");

        // Fill background with black
        ctx.fillStyle = "#101010";
        ctx.fillRect(0, 0, finalSize, finalSize);

        // Calculate where to position the captured content to center it
        const scaledWidth = containerCanvas.width;
        const scaledHeight = containerCanvas.height;

        const maxContentWidth = finalSize - 2 * padding;
        const maxContentHeight = finalSize - 2 * padding;

        // Calculate scale to fit within padding box
        const scale = Math.min(
          maxContentWidth / scaledWidth,
          maxContentHeight / scaledHeight,
          1 // don't upscale
        );

        const finalWidth = scaledWidth * scale;
        const finalHeight = scaledHeight * scale;

        const x = (finalSize - finalWidth) / 2;
        const y = (finalSize - finalHeight) / 2;

        // Draw the container screenshot into the final canvas
        ctx.drawImage(containerCanvas, 0, 0, scaledWidth, scaledHeight, x, y, finalWidth, finalHeight);

        // Create and trigger download
        const link = document.createElement("a");
        link.download = "screenshot-2000x2000.png";
        link.href = finalCanvas.toDataURL("image/png");
        link.click();
      });
    }
  </script>



<?php
 

// Get previous and next post objects
$prev_post = get_previous_post(true);
$next_post = get_next_post(true);

function get_styled_post_link($post, $type = 'prev') {
    if (!$post) return '';

    $title = get_the_title($post);
    $url = get_permalink($post);
    
    $dir = contains_arabic($title) ? 'rtl' : 'ltr';
    $font_family = contains_arabic($title) ? 'Cairo, sans-serif' : 'Geist Mono, monospace';


    return sprintf(
        '<a href="%s" style="display:block; direction:%s; text-align:center; font-family:%s;">%s</a>',
        esc_url($url),
        esc_attr($dir),
        esc_attr($font_family),
        esc_html($title)
    );
}
?>

<div class="post-navigation">
<?php if($prev_post){ ?>
    <div class="prev-post">
        <?php echo get_styled_post_link($prev_post, 'prev'); ?>
    </div>
 <?php } ?>
<?php if($next_post){ ?>
    <div class="next-post">
        <?php echo get_styled_post_link($next_post, 'next'); ?>
    </div>
<?php } ?>
</div>


<?php else : ?>
  <p>No posts found.</p>
<?php endif; ?>


<?php get_footer(); ?>