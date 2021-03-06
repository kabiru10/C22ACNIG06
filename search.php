<?php get_header(); ?>
	<?php
	$sidebar_exists = true;
	$container_class = '';
	$post_class = '';
	$content_class = '';
	if($zdata['search_sidebar'] == 'None') {
		$content_css = 'width:100%';
		$sidebar_css = 'display:none';
		$content_class= 'full-width';
		$sidebar_exists = false;
	} elseif($zdata['search_sidebar_position'] == 'Left') {
		$content_css = 'float:right;';
		$sidebar_css = 'float:left;';
	} elseif($zdata['search_sidebar_position'] == 'Right') {
		$content_css = 'float:left;';
		$sidebar_css = 'float:right;';
	}

	if($zdata['search_layout'] == 'Large Alternate') {
		$post_class = 'large-alternate';
	} elseif($zdata['search_layout'] == 'Medium Alternate') {
		$post_class = 'medium-alternate';
	} elseif($zdata['search_layout'] == 'Grid') {
		$post_class = 'grid-post';
		$container_class = sprintf( 'grid-layout grid-layout-%s', $zdata['blog_grid_columns'] );
	} elseif($zdata['search_layout'] == 'Timeline') {
		$post_class = 'timeline-post';
		$container_class = 'timeline-layout';
		if($zdata['search_sidebar'] != 'None') {
			$container_class = 'timeline-layout timeline-sidebar-layout';
		}
	}
	?>
	<div id="content" class="<?php echo $content_class; ?>" style="<?php echo $content_css; ?>">
		<?php
		if($zdata['search_results_per_page']) {
			$page_num = $paged;
			if ($pagenum='') { $pagenum = 1; }
				global $query_string;
				query_posts($query_string.'&posts_per_page='.$zdata['search_results_per_page'].'&paged='.$page_num);
		} ?>

		<?php if ( have_posts() && strlen( trim(get_search_query()) ) != 0 ) : ?>
		<div class="search-page-search-form">
			<h2><?php echo __('Need a new search?', 'Zhane'); ?></h2>
			<p><?php echo __('If you didn\'t find what you were looking for, try a new search!', 'Zhane'); ?></p>
			<form id="searchform" class="seach-form" role="search" method="get" action="<?php echo home_url( '/' ); ?>">
				<div class="search-table">
					<div class="search-field">
						<input type="text" value="" name="s" id="s" placeholder="<?php _e( 'Search ...', 'Zhane' ); ?>"/>
					</div>
					<div class="search-button">
						<input type="submit" id="searchsubmit" value="&#xf002;" />
					</div>
				</div>
			</form>		
		</div>		
		<?php if($zdata['search_layout'] == 'Timeline'): ?>
			<div class="timeline-icon"><i class="icon-comments-alt"></i></div>
			<?php endif; ?>
			<div id="posts-container" class="<?php echo $container_class; ?> clearfix">
				<?php
				$post_count = 1;

				$prev_post_timestamp = null;
				$prev_post_month = null;
				$first_timeline_loop = false;

				while(have_posts()): the_post();
					$post_timestamp = strtotime($post->post_date);
					$post_month = date('n', $post_timestamp);
					$post_year = get_the_date('o');
					$current_date = get_the_date('o-n');
				?>
				<?php if($zdata['search_layout'] == 'Timeline'): ?>
				<?php if($prev_post_month != $post_month): ?>
					<div class="timeline-date"><h3 class="timeline-title"><?php echo get_the_date($zdata['timeline_date_format']); ?></h3></div>
				<?php endif; ?>
				<?php endif; ?>
				<div id="post-<?php the_ID(); ?>" <?php post_class($post_class.getClassAlign($post_count).' post clearfix'); ?>>
					<?php if($zdata['search_layout'] == 'Medium Alternate'): ?>
					<div class="date-and-formats">
						<div class="date-box">
							<span class="date"><?php the_time($zdata['alternate_date_format_day']); ?></span>
							<span class="month-year"><?php the_time($zdata['alternate_date_format_month_year']); ?></span>
						</div>
						<div class="format-box">
							<?php
							switch(get_post_format()) {
								case 'gallery':
									$format_class = 'images';
									break;
								case 'link':
									$format_class = 'link';
									break;
								case 'image':
									$format_class = 'image';
									break;
								case 'quote':
									$format_class = 'quotes-left';
									break;
								case 'video':
									$format_class = 'film';
									break;
								case 'audio':
									$format_class = 'headphones';
									break;
								case 'chat':
									$format_class = 'bubbles';
									break;
								default:
									$format_class = 'pen';
									break;
							}
							?>
							<i class="icon-<?php echo $format_class; ?>"></i>
						</div>
					</div>
					<?php endif; ?>
					<?php
					if(!$zdata['search_featured_images']):
					if($zdata['legacy_posts_slideshow']) {
						get_template_part('legacy-slideshow');
					} else {
						get_template_part('new-slideshow');
					}
					endif;
					?>
					<div class="post-content-container">
						<?php if($zdata['search_layout'] == 'Timeline'): ?>
						<div class="timeline-circle"></div>
						<div class="timeline-arrow"></div>
						<?php endif; ?>
						<?php if($zdata['search_layout'] != 'Large Alternate' && $zdata['search_layout'] != 'Medium Alternate' && $zdata['search_layout'] != 'Grid'  && $zdata['search_layout'] != 'Timeline'): ?>
						<h2 class="entry-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
						<?php endif; ?>
						<?php if($zdata['search_layout'] == 'Large Alternate'): ?>
						<div class="date-and-formats">
							<div class="date-box">
								<span class="date"><?php the_time($zdata['alternate_date_format_day']); ?></span>
								<span class="month-year"><?php the_time($zdata['alternate_date_format_month_year']); ?></span>
							</div>
							<div class="format-box">
								<?php
								switch(get_post_format()) {
									case 'gallery':
										$format_class = 'images';
										break;
									case 'link':
										$format_class = 'link';
										break;
									case 'image':
										$format_class = 'image';
										break;
									case 'quote':
										$format_class = 'quotes-left';
										break;
									case 'video':
										$format_class = 'film';
										break;
									case 'audio':
										$format_class = 'headphones';
										break;
									case 'chat':
										$format_class = 'bubbles';
										break;
									default:
										$format_class = 'pen';
										break;
								}
								?>
								<i class="icon-<?php echo $format_class; ?>"></i>
							</div>
						</div>
						<?php endif; ?>
						<div class="post-content">
							<?php if($zdata['search_layout'] == 'Large Alternate' || $zdata['search_layout'] == 'Medium Alternate'  || $zdata['search_layout'] == 'Grid' || $zdata['search_layout'] == 'Timeline'): ?>
							<h2 class="post-title entry-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
							<?php if($zdata['post_meta'] && ( ! $zdata['post_meta_author'] || ! $zdata['post_meta_date'] || ! $zdata['post_meta_cats'] || ! $zdata['post_meta_tags'] || ! $zdata['post_meta_comments'] ) ): ?>
							<?php if($zdata['search_layout'] == 'Grid' || $zdata['search_layout'] == 'Timeline'): ?>
							<p class="single-line-meta vcard"><?php if(!$zdata['post_meta_author']): ?><?php echo __('By', 'Zhane'); ?> <span class="fn"><?php the_author_posts_link(); ?></span><span class="sep">|</span><?php endif; ?><?php if(!$zdata['post_meta_date']): ?><span class="updated" style="display:none;"><?php the_modified_time( 'c' ); ?></span><span class="published"><?php the_time($zdata['date_format']); ?></span><span class="sep">|</span><?php endif; ?></p>
							<?php else: ?>
							<p class="single-line-meta vcard"><?php if(!$zdata['post_meta_author']): ?><?php echo __('By', 'Zhane'); ?> <span class="fn"><?php the_author_posts_link(); ?></span><span class="sep">|</span><?php endif; ?><?php if(!$zdata['post_meta_date']): ?><span class="updated" style="display:none;"><?php the_modified_time( 'c' ); ?></span><span class="published"><?php the_time($zdata['date_format']); ?></span><span class="sep">|</span><?php endif; ?><?php if(!$zdata['post_meta_cats']): ?><?php if(!$zdata['post_meta_tags']){ echo __('Categories:', 'Zhane') . ' '; } ?><?php the_category(', '); ?><span class="sep">|</span><?php endif; ?><?php if(!$zdata['post_meta_tags']): ?><span class="meta-tags"><?php echo __('Tags:', 'Zhane') . ' '; the_tags( '' ); ?></span><span class="sep">|</span><?php endif; ?><?php if(!$zdata['post_meta_comments']): ?><?php comments_popup_link(__('0 Comments', 'Zhane'), __('1 Comment', 'Zhane'), '% '.__('Comments', 'Zhane')); ?><?php endif; ?></p>
							<?php endif; ?>
							<?php endif; ?>
							<?php endif; ?>
							<?php if( ( ! $zdata['post_meta'] && $zdata['excerpt_length_blog'] == '0' ) || ( $zdata['post_meta_author'] && $zdata['post_meta_date'] && $zdata['post_meta_cats'] && $zdata['post_meta_tags'] && $zdata['post_meta_comments'] && $zdata['post_meta_read'] && $zdata['excerpt_length_blog'] == '0' ) ): ?>
							<?php if( ! $zdata['post_meta'] ): ?> 
							<div class="no-content-sep"></div>
							<?php endif; ?>
							<?php else: ?>
							<div class="content-sep"></div>
							<?php endif; ?>	
							<?php if(!$zdata['search_excerpt']): ?>
							<?php
							if(get_post_type( get_the_ID() ) != 'page') {

								$stripped_content = tf_content( $zdata['excerpt_length_blog'], $zdata['strip_html_excerpt'] );
								echo $stripped_content;
							} else {
								the_excerpt();
							}
							?>
							<?php endif; ?>
							<?php if($zdata['post_meta'] && !$zdata['post_meta_tags'] && ($zdata['search_layout'] == 'Large' || $zdata['search_layout'] == 'Medium' || $zdata['search_layout'] == 'Grid' || $zdata['search_layout'] == 'Timeline')): ?>
							<div class="meta-tags bottom"><?php the_tags( ); ?></div>
							<?php endif; ?>
						</div>
						<div class="fusion-clearfix"></div>
						<?php if($zdata['post_meta']): ?>
						<div class="meta-info">
							<?php if($zdata['search_layout'] == 'Grid' || $zdata['search_layout'] == 'Timeline'): ?>
							<?php if($zdata['search_layout'] != 'Large Alternate' && $zdata['search_layout'] != 'Medium Alternate'): ?>
							<div class="alignleft">
								<?php if(!$zdata['post_meta_read']): ?><a href="<?php the_permalink(); ?>" class="read-more"><?php echo __('Read More', 'Zhane'); ?></a><?php endif; ?>
							</div>
							<?php endif; ?>
							<div class="alignright">
								<?php if(!$zdata['post_meta_comments']): ?><?php comments_popup_link('<i class="icon-bubbles"></i>&nbsp;'.__('0', 'Zhane'), '<i class="icon-bubbles"></i>&nbsp;'.__('1', 'Zhane'), '<i class="icon-bubbles"></i>&nbsp;'.'%'); ?><?php endif; ?>
							</div>
							<?php else: ?>
							<?php if($zdata['search_layout'] != 'Large Alternate' && $zdata['search_layout'] != 'Medium Alternate'): ?>
							<div class="alignleft vcard">
							<?php if(!$zdata['post_meta_author']): ?><?php echo __('By', 'Zhane'); ?> <span class="fn"><?php the_author_posts_link(); ?></span><span class="sep">|</span><?php endif; ?><?php if(!$zdata['post_meta_date']): ?><span class="updated" style="display:none;"><?php the_modified_time( 'c' ); ?></span><span class="published"><?php the_time($zdata['date_format']); ?></span><span class="sep">|</span><?php endif; ?><?php if(!$zdata['post_meta_cats']): ?><?php if(!$zdata['post_meta_tags']){ echo __('Categories:', 'Zhane') . ' '; } ?><?php the_category(', '); ?><span class="sep">|</span><?php endif; ?><?php if(!$zdata['post_meta_tags']): ?><span class="meta-tags"><?php echo __('Tags:', 'Zhane') . ' '; the_tags( '' ); ?></span><span class="sep">|</span><?php endif; ?><?php if(!$zdata['post_meta_comments']): ?><?php comments_popup_link(__('0 Comments', 'Zhane'), __('1 Comment', 'Zhane'), '% '.__('Comments', 'Zhane')); ?><?php endif; ?>
							</div>
							<?php endif; ?>
							<div class="alignright">
								<?php if(!$zdata['post_meta_read']): ?><a href="<?php the_permalink(); ?>" class="read-more"><?php echo __('Read More', 'Zhane'); ?></a><?php endif; ?>
							</div>
							<?php endif; ?>
						</div>
						<?php endif; ?>
					</div>
				</div>
				<?php
				$prev_post_timestamp = $post_timestamp;
				$prev_post_month = $post_month;
				$post_count++;
				endwhile;
				?>
			</div>
			<?php idh_pagination($pages = '', $range = 2); ?>
		<?php wp_reset_query(); ?>
	<?php else: ?>
	<div class="post-content">
		<div class="fusion-title title">
			<h2 class="title-heading-left"><?php echo __('Couldn\'t find what you\'re looking for!', 'Zhane'); ?></h2><div class="title-sep-container"><div class="title-sep sep-double"></div></div>			
		</div>
		<div class="error_page">
			<div class="one_third">
				<h1 class="oops <?php echo ($sidebar_css != 'display:none') ? 'sidebar-oops' : ''; ?>"><?php echo __('Oops!', 'Zhane'); ?></h1>
			</div>
			<div class="one_third useful_links">
				<h3><?php echo __('Here are some useful links:', 'Zhane'); ?></h3>
				<?php $iconcolor = strtolower($zdata['checklist_icons_color']); ?>

				<style type='text/css'>
					.post-content #checklist-1 li:before{color:<?php echo $iconcolor; ?> !important;}
					.rtl .post-content #checklist-1 li:after{color:<?php echo $iconcolor; ?> !important;}
				</style>

				<?php wp_nav_menu(array('theme_location' => '404_pages', 'depth' => 1, 'container' => false, 'menu_id' => 'checklist-1', 'menu_class' => 'list-icon circle-yes list-icon-arrow')); ?>
			</div>
			<div class="one_third last">
				<h3><?php echo __('Try again!', 'Zhane'); ?></a></h3>
				<p><?php echo __('If you want to rephrase your query, here is your chance:', 'Zhane'); ?></p>
				<form id="searchform" class="seach-form" role="search" method="get" action="<?php echo home_url( '/' ); ?>">
					<div class="search-table">
						<div class="search-field">
							<input type="text" value="" name="s" id="s" placeholder="<?php _e( 'Search ...', 'Zhane' ); ?>"/>
						</div>
						<div class="search-button">
							<input type="submit" id="searchsubmit" value="&#xf002;" />
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
	<?php endif; ?>
	</div>
	<?php if( $sidebar_exists == true ): ?>
	<?php wp_reset_query(); ?>
	<div id="sidebar" style="<?php echo $sidebar_css; ?>">
	<?php
	if ($zdata['search_sidebar'] != 'None' && function_exists('dynamic_sidebar')) {
		generated_dynamic_sidebar($zdata['search_sidebar']);
	}
	?>
	</div>
	<?php endif; ?>
<?php get_footer(); ?>