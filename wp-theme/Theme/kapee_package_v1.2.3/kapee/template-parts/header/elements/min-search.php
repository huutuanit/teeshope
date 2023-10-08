<?php
/**
 * Template part for displaying mini search in header.php
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @author 	PressLayouts
 * @package kapee/template-parts/header
 * @since 1.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>			

<div class="header-mini-search">
	<a class="search-icon-text"><span class="search-text"><?php esc_html_e('Search','kapee');?></span></a>
	<?php get_search_form();?>
</div>