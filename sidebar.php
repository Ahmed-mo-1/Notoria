<aside id="secondary" class="widget-area" style="">
    <section class="widget">
        <h2 class="widget-title" style="width: 100%; display: flex; align-items: center; justify-content: center; padding: 10px;width: 100%; background: var(--sec-background-color);
border-radius: 20px; cursor: pointer; text-align: center" onclick="toggleCat(this)">Categories</h2>
		<div class="sub-menu">
			<ul class="menu">
				<?php wp_list_categories(array(
					'orderby'    => 'name',
					'show_count' => true,
					'title_li'   => '',
					'hierarchical'=> false,
				)); ?>
			</ul>
		</div>
    </section>
</aside>