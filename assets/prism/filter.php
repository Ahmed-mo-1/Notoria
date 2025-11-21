<?php
// Only get categories if on home page to show filters
if ( is_home() ) {
    $all_categories = get_categories();
}
?>

<?php if ( is_home() ) : ?>


<div id="backButton" class="hide" style="margin-bottom:20px; display: flex; justify-content: space-between; gap: 10px; flex-wrap: wrap;">
    <button class="filter-btn" onclick="backToAll()">Back</button>
</div>

<!-- Category filter buttons -->
<div id="category-filters" style="margin-bottom:20px; display: flex; justify-content: space-between; gap: 10px; flex-wrap: wrap;">
    <button data-cat="all" class="cat-filter-btn filter-btn">All</button>
    <?php foreach($all_categories as $cat): ?>
        <button data-cat="<?php echo esc_attr($cat->slug); ?>" class="cat-filter-btn filter-btn">
            <?php echo esc_html($cat->name); ?>
        </button>
    <?php endforeach; ?>
</div>
<?php endif; ?>


<script>
document.addEventListener('DOMContentLoaded', function() {
    const buttons = document.querySelectorAll('.cat-filter-btn');
    const posts = document.querySelectorAll('.post-item');

    buttons.forEach(button => {
        button.addEventListener('click', () => {
            const selectedCat = button.getAttribute('data-cat');

            // Highlight active button
            buttons.forEach(btn => {btn.style.fontWeight = 'normal'; btn.classList.remove("selected-filter-btn");});
            button.style.fontWeight = 'bold'; button.classList.add("selected-filter-btn");

            posts.forEach(post => {
                if (selectedCat === 'all') {
                    post.style.display = 'block';
                } else {
                    if (post.classList.contains('cat-' + selectedCat)) {
                        post.style.display = 'block';
                    } else {
                        post.style.display = 'none';
                    }
                }
            });
        });
    });
});
</script>


