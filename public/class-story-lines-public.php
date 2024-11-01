<?php
/**
 * Holds all of the public side functions.
 *
 * PHP version 7.3
 *
 * @link       https://jacobmartella.com
 * @since      1.0.0
 *
 * @package    Story_Lines
 * @subpackage Story_Lines/public
 */

namespace Story_Lines;

/**
 * Runs the public side.
 *
 * This class defines all code necessary to run on the public side of the plugin.
 *
 * @since      1.0.0
 * @package    Story_Lines
 * @subpackage Story_Lines/public
 */
class Story_Lines_Public {

	/**
	 * Version of the plugin.
	 *
	 * @since 1.0.0
	 * @var string $version Description.
	 */
	private $version;

	/**
	 * Builds the Story_Lines_Public object.
	 *
	 * @since 1.0.0
	 *
	 * @param string $version Version of the plugin.
	 */
	public function __construct( $version ) {
		$this->version = $version;
	}

	/**
	 * Enqueues the styles for the admin side of the plugin.
	 *
	 * @since 1.0.0
	 */
	public function enqueue_styles() {
		wp_enqueue_style( 'story-lines-public', plugin_dir_url( __FILE__ ) . 'css/story-lines.min.css', [], $this->version, 'all' );
	}

	/**
	 * Enqueues the scripts for the admin side of the plugin.
	 *
	 * @since 1.0.0
	 */
	public function enqueue_scripts() { }

	/**
	 * Registers the Story Lines shortcode.
	 *
	 * @since 2.0.0
	 */
	public function register_shortcode() {
		add_shortcode( 'story-lines', [ $this, 'story_lines_shortcode' ] );
	}

	/**
	 * Renders the Story Lines shortcode.
	 *
	 * @since 2.0.0
	 *
	 * @param array $atts      The attributes for the shortcode.
	 * @return string          The HTML for the read more about shortcode.
	 */
	public function story_lines_shortcode( $atts ) {
		extract(
			shortcode_atts(
				[],
				$atts
			)
		);
		$the_post_id = get_the_ID();

		if ( get_post_meta( $the_post_id, 'story_lines_title', true ) ) {
			$title = get_post_meta( $the_post_id, 'story_lines_title', true );
		} else {
			$title = __( 'Story Lines', 'story-lines' );
		}

		if ( get_post_meta( $the_post_id, 'story_lines_size', true ) ) {
			$size = 'width: ' . esc_attr( get_post_meta( $the_post_id, 'story_lines_size', true ) ) . '%;';
		} else {
			$size = 'width: 25%;';
		}

		if ( get_post_meta( $the_post_id, 'story_lines_float', true ) ) {
			$float = esc_attr( get_post_meta( $the_post_id, 'story_lines_float', true ) );
		} else {
			$float = 'left';
		}

		if ( get_post_meta( $the_post_id, 'story_lines_highlights', true ) ) {
			$highlights = get_post_meta( $the_post_id, 'story_lines_highlights', true );
		} else {
			$highlights = '';
		}

		if ( get_post_meta( $the_post_id, 'story_lines_title_background', true ) ) {
			$title_bg_color = 'background-color: ' . esc_attr( get_post_meta( $the_post_id, 'story_lines_title_background', true ) ) . ';';
		} else {
			$title_bg_color = '';
		}

		if ( get_post_meta( $the_post_id, 'story_lines_main_background', true ) ) {
			$main_bg_color = 'background-color: ' . esc_attr( get_post_meta( $the_post_id, 'story_lines_main_background', true ) ) . ';';
		} else {
			$main_bg_color = '';
		}

		if ( get_post_meta( $the_post_id, 'story_lines_title_color', true ) ) {
			$title_color = 'color: ' . esc_attr( get_post_meta( $the_post_id, 'story_lines_title_color', true ) ) . ';';
		} else {
			$title_color = '';
		}

		if ( get_post_meta( $the_post_id, 'story_lines_main_color', true ) ) {
			$main_color = 'color: ' . esc_attr( get_post_meta( $the_post_id, 'story_lines_main_color', true ) ) . ';';
		} else {
			$main_color = '';
		}

		$html = '';

		if ( $highlights ) {
			$html .= '<aside class="story-lines ' . esc_attr( $float ) . '" style="' . esc_attr( $size ) . esc_attr( $main_bg_color ) . '">';
			$html .= '<h2 class="title" style="' . esc_attr( $title_bg_color . $title_color ) . '">' . esc_html( $title ) . '</h2>';
			$html .= '<ul>';
			foreach ( $highlights as $highlight ) {
				if ( isset( $highlight['story_lines_anchor_id'] ) ) {
					$html .= '<li><a href="#' . esc_attr( $highlight['story_lines_anchor_id'] ) . '" style="' . esc_attr( $main_color ) . '">' . esc_html( $highlight['story_lines_highlight'] ) . '</a></li>';
				} else {
					$html .= '<li style="' . esc_attr( $main_color ) . '">' . esc_html( $highlight['story_lines_highlight'] ) . '</li>';
				}
			}
			$html .= '</ul>';
			$html .= '</aside>';
		}

		return $html;
	}

	/**
	 * Loads and registers the read more about widget.
	 *
	 * @since 2.0.0
	 */
	public function register_widget() {
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/partials/story-lines-widget.php';

		register_widget( 'Story_Lines_Widget' );
	}

}
