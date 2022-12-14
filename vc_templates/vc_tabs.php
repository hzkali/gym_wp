<?php
$output = $title = $interval = $el_class = '';
extract(shortcode_atts(array(
    'title' => '',
    'interval' => 0,
    'el_class' => '',
	'type' => 'vertical',
	'top_margin' => 'none'
), $atts));

wp_enqueue_script('jquery-ui-tabs');

$el_class = $this->getExtraClass($el_class);

$element = 'wpb_tabs';
if ( 'vc_tour' == $this->shortcode) $element = 'wpb_tour';

// Extract tab titles
//preg_match_all( '/vc_tab title="([^\"]+)"(\stab_id\=\"([^\"]+)\")(\sicon\=\"([^\"]+)\"){0,1}/i', $content, $matches, PREG_OFFSET_CAPTURE );
preg_match_all( '/vc_tab(\sicon\=\"([^\"]+)\"){0,1} title="([^\"]+)"(\stab_id\=\"([^\"]+)\"){0,1}/i', $content, $matches, PREG_OFFSET_CAPTURE );
$tab_titles = array();
/**
 * vc_tabs
 *
 */
/*if ( isset($matches[0]) ) { $tab_titles = $matches[0]; }
$tabs_nav = '';
$tabs_nav .= '<ul class="wpb_tabs_nav ui-tabs-nav clearfix">';
foreach ( $tab_titles as $tab ) {
    preg_match('/title="([^\"]+)"(\stab_id\=\"([^\"]+)\"){0,1}/i', $tab[0], $tab_matches, PREG_OFFSET_CAPTURE );
    if(isset($tab_matches[1][0])) {
        $tabs_nav .= '<li><a href="#tab-'. (isset($tab_matches[3][0]) ? $tab_matches[3][0] : sanitize_title( $tab_matches[1][0] ) ) .'">' . $tab_matches[1][0] . '</a></li>';

    }
}
$tabs_nav .= '</ul>'."\n";*/

if ( isset($matches[0]) ) { $tab_titles = $matches[0]; }
$tabs_nav = '';
$tabs_nav .= '<ul class="clearfix">';
foreach ( $tab_titles as $tab ) {
	//preg_match('/title="([^\"]+)"(\stab_id\=\"([^\"]+)\")(\sicon\=\"([^\"]+)\"){0,1}/i', $tab[0], $tab_matches, PREG_OFFSET_CAPTURE );
	preg_match('/(\sicon\=\"([^\"]+)\"){0,1} title="([^\"]+)"(\stab_id\=\"([^\"]+)\"){0,1}/i', $tab[0], $tab_matches, PREG_OFFSET_CAPTURE );

	if(isset($tab_matches[3][0])) {
		$href = (isset($tab_matches[5][0]) ? $tab_matches[5][0] : sanitize_title( $tab_matches[3][0] ) );
		if(substr($href, 0, 4)!="http" && substr($href, 0, 5)!="https")
			$href = "#" . $href;
		$tabs_nav .= '<li><a href="'. esc_url($href) .'"' . (isset($tab_matches[2][0]) ? ' class="' . esc_attr($tab_matches[2][0]) . '"' : '') . '>' . $tab_matches[3][0] . '</a></li>';

	}
}
$tabs_nav .= '</ul>'."\n";

/*$css_class =  apply_filters(VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, trim($element.' wpb_content_element '.$el_class), $this->settings['base']);

$output .= "\n\t".'<div class="'.$css_class.'" data-interval="'.$interval.'">';
$output .= "\n\t\t".'<div class="wpb_wrapper wpb_tour_tabs_wrapper ui-tabs clearfix">';
$output .= wpb_widget_title(array('title' => $title, 'extraclass' => $element.'_heading'));
$output .= "\n\t\t\t".$tabs_nav;
$output .= "\n\t\t\t".wpb_js_remove_wpautop($content);
if ( 'vc_tour' == $this->shortcode) {
    $output .= "\n\t\t\t" . '<div class="wpb_tour_next_prev_nav clearfix"> <span class="wpb_prev_slide"><a href="#prev" title="'.__('Previous slide', 'gymbase').'">'.__('Previous slide', 'gymbase').'</a></span> <span class="wpb_next_slide"><a href="#next" title="'.__('Next slide', 'gymbase').'">'.__('Next slide', 'gymbase').'</a></span></div>';
}
$output .= "\n\t\t".'</div> '.$this->endBlockComment('.wpb_wrapper');
$output .= "\n\t".'</div> '.$this->endBlockComment($element);*/

$output .= '<div class="clearfix tabs'.(isset($width) ? esc_attr($width) : '').esc_attr($el_class).($top_margin!="none" ? ' ' . esc_attr($top_margin) : '').($type!="vertical" ? ' tabs-' . esc_attr($type) : '').'" data-interval="'.esc_attr($interval).'">';
$output .= "\n\t\t\t".$tabs_nav;
$output .= "\n\t\t\t".wpb_js_remove_wpautop($content);
$output .= "\n\t\t".'</div>'.$this->endBlockComment('.wpb_wrapper').$this->endBlockComment((isset($width) ? $width : ''));

echo $output;