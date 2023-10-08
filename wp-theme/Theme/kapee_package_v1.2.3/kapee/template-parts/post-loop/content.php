<?php
/**
 * Template part for displaying posts content
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @author 	PressLayouts
 * @package kapee/template-parts/post-loop
 * @since 1.0
 */
 
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$content_type 	= kapee_get_loop_prop( 'blog-post-content');
$excerpt_length = kapee_get_loop_prop('blog-excerpt-length');
?>
<div class="entry-content">
		
	<?php
	
	if( $content_type == 'full-content' && kapee_get_loop_prop('name') != 'related-posts' ) {
		$content = get_the_content();
	} elseif( $content_type == 'excerpt-content' || kapee_get_loop_prop('name') == 'related-posts') {
		if( has_excerpt() && ! empty( $post->post_excerpt )){
			$content = get_the_excerpt();
		}else{
			$content = get_the_content('');
		}
		$content = preg_replace("/\[caption(.*)\[\/caption\]/i", '', $content);
		$content = preg_replace('`\[[^\]]*\]`','',$content);
		$content = stripslashes( wp_filter_nohtml_kses( $content ) );
		$content = wp_trim_words( $content, $excerpt_length );			
	}
	echo apply_filters( 'the_content', $content );	
	
	if( $content_type == 'full-content' ) {
		wp_link_pages(
			array(
				'before'      => '<div class="page-links">' . esc_html__( 'Pages:', 'kapee' ),
				'after'       => '</div>',
				'link_before' => '<span class="page-number">',
				'link_after'  => '</span>',
			)
		);
	}
	?>
</div>