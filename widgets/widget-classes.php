<?php
class gb_classes_widget extends WP_Widget 
{
	/** constructor */
	function __construct() 
	{
		$widget_options = array(
			'classname' => 'gb-classes-widget',
			'description' => __('Displays Classes Accordion', 'gymbase')
		);
        parent::__construct('gymbase_classes', __('Classes Accordion', 'gymbase'), $widget_options);
    }
	
	/** @see WP_Widget::widget */
    function widget($args, $instance) 
	{
        extract($args);

		//these are our widget options
		$title = isset($instance['title']) ? $instance['title'] : "";
		$animation = isset($instance['animation']) ? $instance['animation'] : "";
		$order = isset($instance['order']) ? $instance['order'] : "";
		$categories = isset($instance['categories']) ? $instance['categories'] : "";
		$classes_page = isset($instance['classes_page']) ? $instance['classes_page'] : "";
		$timetable_page = isset($instance['timetable_page']) ? $instance['timetable_page'] : "";

		echo $before_widget;
		
		query_posts(array( 
			'post_type' => 'classes',
			'posts_per_page' => '-1',
			'post_status' => 'publish',
			'orderby' => 'menu_order', 
			'order' => $order,
			'classes_category' => implode(",", (array)$categories),
		));

		$output = '';
		if($title) 
		{
			$output .= ((int)$animation ? str_replace("box-header", "box-header animation-slide", $before_title) : str_replace("animation-slide", "", $before_title)) . $title . $after_title;
		}
		if(have_posts()):
			$output .= '<ul class="accordion widget-classes">';
			while(have_posts()): the_post();
				global $post;
				$output .= '<li>
					<div id="accordion-' . urldecode($post->post_name) . '" class="template-arrow-vertical-4-after">
						<h5>' . get_the_title() . '</h3>
						<p class="gb-subtitle">' . esc_attr(get_post_meta(get_the_ID(), "gymbase_subtitle", true)) . '</p>
					</div>
					<div class="clearfix">
						<div class="item-content clearfix">';
							if(has_post_thumbnail())
								$output .= '<a class="thumb-image" href="' . esc_url(get_permalink($classes_page)) . '#' . urldecode($post->post_name) . '" title="' . esc_attr(get_the_title()) . '">
									' . get_the_post_thumbnail(get_the_ID(), "thumbnail", array("alt" => get_the_title(), "title" => "")) . '
								</a>';
				$output .= '<p class="text alternate">
								' . get_the_excerpt() . '
								<span class="item-footer clearfix">
									<a class="gb-button more" href="' . esc_url(get_permalink($classes_page)) . '#' . urldecode($post->post_name) . '" title="' . esc_attr__("Details", 'gymbase') . '">' . __("DETAILS", 'gymbase') . '</a>
									<a class="gb-button more" href="' . esc_url(get_permalink($timetable_page)) . '#' . urldecode($post->post_name) . '" title="' . esc_attr__("Timetable", 'gymbase') . '">' . __("TIMETABLE", 'gymbase') . '</a>
								</span>
							</p>
							
						</div>
						
					</div>
				</li>';
			endwhile;
			$output .= '</ul>';
		endif;
		wp_reset_query();
		echo do_shortcode($output);
        echo $after_widget;
		
    }
	
	/** @see WP_Widget::update */
    function update($new_instance, $old_instance) 
	{
		$instance = $old_instance;
		$instance['title'] = isset($new_instance['title']) ? strip_tags($new_instance['title']) : '';
		$instance['animation'] = isset($new_instance['animation']) ? strip_tags($new_instance['animation']) : "";
		$instance['order'] = isset($new_instance['order']) ? strip_tags($new_instance['order']) : '';
		$instance['categories'] = isset($new_instance['categories']) ? $new_instance['categories'] : '';
		$instance['classes_page'] = isset($new_instance['classes_page']) ? strip_tags($new_instance['classes_page']) : '';
		$instance['timetable_page'] = isset($new_instance['timetable_page']) ? strip_tags($new_instance['timetable_page']) : '';
		return $instance;
    }
	
	 /** @see WP_Widget::form */
	function form($instance) 
	{
		$title = isset($instance['title']) ? esc_attr($instance['title']) : '';
		$animation = isset($instance['animation']) ? esc_attr($instance['animation']) : "";
		$order = isset($instance['order']) ? esc_attr($instance['order']) : '';
		$categories = isset($instance['categories']) ? $instance['categories'] : '';
		$classes_page = isset($instance['classes_page']) ? esc_attr($instance['classes_page']) : '';
		$timetable_page = isset($instance['timetable_page']) ? esc_attr($instance['timetable_page']) : '';
		?>
		<p>
			<label for="<?php echo esc_attr($this->get_field_id('title')); ?>"><?php _e('Title', 'gymbase'); ?></label>
			<input class="widefat" id="<?php echo esc_attr($this->get_field_id('title')); ?>" name="<?php echo esc_attr($this->get_field_name('title')); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
		</p>
		<p>
			<label for="<?php echo esc_attr($this->get_field_id('animation')); ?>"><?php _e('Title border animation', 'gymbase'); ?></label>
			<select id="<?php echo esc_attr($this->get_field_id('animation')); ?>" name="<?php echo esc_attr($this->get_field_name('animation')); ?>">
				<option value="0"><?php _e('no', 'gymbase'); ?></option>
				<option value="1"<?php echo ((int)$animation==1 ? ' selected="selected"' : ''); ?>><?php _e('yes', 'gymbase'); ?></option>
			</select>
		</p>
		<p>
			<label for="<?php echo esc_attr($this->get_field_id('order')); ?>"><?php _e('Order', 'gymbase'); ?></label>
			<select id="<?php echo esc_attr($this->get_field_id('order')); ?>" name="<?php echo esc_attr($this->get_field_name('order')); ?>">
				<option <?php echo ($order=='ASC' ? ' selected="selected"':'');?> value='ASC'><?php _e("ASC", 'gymbase'); ?></option>
				<option <?php echo ($order=='DESC' ? ' selected="selected"':'');?> value='DESC'><?php _e("DESC", 'gymbase'); ?></option>
			</select>
		</p>
		<p>
			<label for="<?php echo esc_attr($this->get_field_id('categories')); ?>"><?php _e('Categories', 'gymbase'); ?></label>
			<select multiple="multiple" id="<?php echo esc_attr($this->get_field_id('categories')); ?>" name="<?php echo esc_attr($this->get_field_name('categories')); ?>[]">
			<?php
			$classes_categories = get_terms("classes_category");
			foreach($classes_categories as $classes_category)
			{
			?>
				<option <?php echo (is_array($categories) && in_array($classes_category->slug, $categories) ? ' selected="selected"':'');?> value='<?php echo esc_attr($classes_category->slug);?>'><?php echo $classes_category->name; ?></option>
			<?php
			}
			?>
			</select>
		</p>
		<p>
			<label for="<?php echo esc_attr($this->get_field_id('classes_page')); ?>"><?php _e('Classes Page', 'gymbase'); ?></label>
			<select id="<?php echo esc_attr($this->get_field_id('classes_page')); ?>" name="<?php echo esc_attr($this->get_field_name('classes_page')); ?>">
				<?php
				$args = array(
					'post_type' => 'page',
					'post_status' => 'publish',
					'posts_per_page' => -1,
					'orderby' => 'title', 
					'order' => 'ASC',
					'suppress_filters' => true
				);
				query_posts($args);
				if(have_posts()) : while (have_posts()) : the_post();
					global $post;
				?>
					<option <?php echo ($classes_page==get_the_ID() ? ' selected="selected"':'');?> value='<?php echo esc_attr(get_the_ID()); ?>'><?php the_title(); ?></option>
				<?php
				endwhile;
				endif;
				?>
			</select>
		</p>
		<p>
			<label for="<?php echo esc_attr($this->get_field_id('timetable_page')); ?>"><?php _e('Timetable Page', 'gymbase'); ?></label>
			<select id="<?php echo esc_attr($this->get_field_id('timetable_page')); ?>" name="<?php echo esc_attr($this->get_field_name('timetable_page')); ?>">
				<?php
				if(have_posts()) : while (have_posts()) : the_post();
				?>
					<option <?php echo ($timetable_page==get_the_ID() ? ' selected="selected"':'');?> value='<?php echo esc_attr(get_the_ID()); ?>'><?php the_title(); ?></option>
				<?php
				endwhile;
				endif;
				?>
			</select>
		</p>
		<?php
	}
}
?>