
<?php
/**
 * File that displays the custom meta box for the story lines.
 *
 * PHP version 7.3
 *
 * @link       https://jacobmartella.com
 * @since      2.0.0
 *
 * @package    Story_Lines
 * @subpackage Story_Lines/admin/partials
 */

global $post;
$float_array = [
	'left'   => __( 'Left', 'story-lines' ),
	'center' => __( 'Center', 'story-lines' ),
	'right'  => __( 'Right', 'story-lines' ),
];

if ( get_post_meta( $post->ID, 'story_lines_title', true ) ) {
	$story_title = get_post_meta( $post->ID, 'story_lines_title', true );
} else {
	$story_title = '';
}

if ( get_post_meta( $post->ID, 'story_lines_size', true ) ) {
	$size = get_post_meta( $post->ID, 'story_lines_size', true );
} else {
	$size = '';
}

if ( get_post_meta( $post->ID, 'story_lines_float', true ) ) {
	$float = get_post_meta( $post->ID, 'story_lines_float', true );
} else {
	$float = '';
}

if ( get_post_meta( $post->ID, 'story_lines_title_background', true ) ) {
	$title_bg_color = get_post_meta( $post->ID, 'story_lines_title_background', true );
} else {
	$title_bg_color = '';
}

if ( get_post_meta( $post->ID, 'story_lines_main_background', true ) ) {
	$main_bg_color = get_post_meta( $post->ID, 'story_lines_main_background', true );
} else {
	$main_bg_color = '';
}

if ( get_post_meta( $post->ID, 'story_lines_title_color', true ) ) {
	$title_color = get_post_meta( $post->ID, 'story_lines_title_color', true );
} else {
	$title_color = '';
}

if ( get_post_meta( $post->ID, 'story_lines_main_color', true ) ) {
	$main_color = get_post_meta( $post->ID, 'story_lines_main_color', true );
} else {
	$main_color = '';
}

$highlights = get_post_meta( $post->ID, 'story_lines_highlights', true );
wp_nonce_field( 'story_lines_meta_box_nonce', 'story_lines_meta_box_nonce' );

echo '<div id="story-lines-repeatable-fieldset-one" width="100%">';

echo '<table class="story-lines-options">';
echo '<tr>';
echo '<td><label for="story_lines_title">' . esc_html__( 'Title:', 'story-lines' ) . '</label></td>';
echo '<td><input type="text" name="story_lines_title" id="story_lines_title" value="' . esc_attr( $story_title ) . '" /></td>';
echo '</tr>';

echo '<tr>';
echo '<td><label for="story_lines_size">' . esc_html__( 'Size (as a percentage): ', 'story-lines' ) . '</label></td>';
echo '<td><input type="number" name="story_lines_size" id="story_lines_size" value="' . esc_attr( $size ) . '" max="100" min="1" /></td>';
echo '</tr>';

echo '<tr>';
echo '<td><label for="story_lines_float">' . esc_html__( 'Float:', 'story-lines' ) . '</label></td>';
echo '<td><select name="story_lines_float" id="story_lines_float">';
foreach ( $float_array as $key => $name ) {
	if ( $key === $float ) {
		$selected = 'selected="selected"';
	} else {
		$selected = '';
	}
	echo '<option value="' . esc_attr( $key ) . '" ' . esc_attr( $selected ) . '>' . esc_html( $name ) . '</option>';
}
echo '</select></td>';
echo '</tr>';

echo '<tr>';
echo '<td><label for="story_lines_title_background">' . esc_html__( 'Background Color for title section', 'story-lines' ) . '</label></td>';
echo '<td><input type="text" name="story_lines_title_background" id="story_lines_title_background" value="' . esc_attr( $title_bg_color ) . '" /></td>';
echo '</tr>';

echo '<tr>';
echo '<td><label for="story_lines_main_background">' . esc_html__( 'Background Color for main section', 'story-lines' ) . '</label></td>';
echo '<td><input type="text" name="story_lines_main_background" id="story_lines_main_background" value="' . esc_attr( $main_bg_color ) . '" /></td>';
echo '</tr>';

echo '<tr>';
echo '<td><label for="story_lines_title_color">' . esc_html__( 'Color for the title text', 'story-lines' ) . '</label></td>';
echo '<td><input type="text" name="story_lines_title_color" id="story_lines_title_color" value="' . esc_attr( $title_color ) . '" /></td>';
echo '</tr>';

echo '<tr>';
echo '<td><label for="story_lines_main_color">' . esc_html__( 'Color for text of the main section', 'story-lines' ) . '</label></td>';
echo '<td><input type="text" name="story_lines_main_color" id="story_lines_main_color" value="' . esc_attr( $main_color ) . '" /></td>';
echo '</tr>';
echo '</table>';

if ( $highlights ) {
	foreach ( $highlights as $highlight ) {
		echo '<table class="story-lines-link-fields">';
		echo '<tr>';
		echo '<td><label for="story_lines_highlight">' . esc_html__( 'Story Line:', 'story-lines' ) . '</label></td>';
		echo '<td><input type="text" name="story_lines_highlight[]" id="story_lines_highlight" value="' . esc_attr( $highlight['story_lines_highlight'] ) . '" /></td>';
		echo '</tr>';
		echo '<tr>';
		if ( isset( $highlight['story_lines_anchor_id'] ) ) {
			$anchor = $highlight['story_lines_anchor_id'];
		} else {
			$anchor = '';
		}
		echo '<td><label for="story_lines_anchor_id">' . esc_html__( 'Anchor ID:', 'story-lines' ) . '</label></td>';
		echo '<td><input type="text" name="story_lines_anchor_id[]" id="story_lines_anchor_id" value="' . esc_attr( $anchor ) . '" /></td>';
		echo '</tr>';
		echo '<tr><td><a class="button story-lines-remove-row" href="#">' . esc_html__( 'Remove Line', 'story-lines' ) . '</a></td></tr>';
		echo '</table>';
	}
} else {
	echo '<table class="story-lines-link-fields">';
	echo '<tr>';
	echo '<td><label for="story_lines_highlight">' . esc_html__( 'Story Line:', 'story-lines' ) . '</label></td>';
	echo '<td><input type="text" name="story_lines_highlight[]" id="story_lines_highlight" value="" /></td>';
	echo '</tr>';

	echo '<tr>';
	echo '<td><label for="story_lines_anchor_id">' . esc_html__( 'Anchor ID:', 'story-lines' ) . '</label><br /></td>';
	echo '<td><input type="text" name="story_lines_anchor_id[]" id="story_lines_anchor_id" value="" /></td>';
	echo '</tr>';

	echo '<tr><td><a class="button story-lines-remove-row" href="#">' . esc_html__( 'Remove Line', 'story-lines' ) . '</a></td></tr>';
	echo '</table>';
}

echo '<table class="story-lines-empty-row screen-reader-text">';
echo '<tr>';
echo '<td><label for="story_lines_highlight">' . esc_html__( 'Story Line:', 'story-lines' ) . '</label></td>';
echo '<td><input class="new-field" type="text" name="story_lines_highlight[]" id="story_lines_highlight" value="" disabled="disabled" /></td>';
echo '</tr>';

echo '<tr>';
echo '<td><label for="story_lines_anchor_id">' . esc_html__( 'Anchor ID:', 'story-lines' ) . '</label></td>';
echo '<td><input class="new-field" type="text" name="story_lines_anchor_id[]" id="story_lines_anchor_id" value="" disabled="disabled" /></td>';
echo '</tr>';

echo '<tr><td><a class="button story-lines-remove-row" href="#">' . esc_html__( 'Remove Line', 'story-lines' ) . '</a></td></tr>';
echo '</table>';

echo '</div>';
echo '<p><a id="story-lines-add-row" class="button" href="#">' . esc_html__( 'Add Story Line', 'story-lines' ) . '</a></p>';

echo '</div>';
