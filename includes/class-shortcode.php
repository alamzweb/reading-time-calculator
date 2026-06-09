<?php
namespace RTC\Includes;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Shortcode {

	public function __construct() {
		add_shortcode( 'reading_time', [ $this, 'render_shortcode' ] );
	}

	public function render_shortcode( array|string $atts ): string {
		$attributes = shortcode_atts( [
			'post_id' => null,
		], $atts, 'reading_time' );

		$post_id = $attributes['post_id'] ? absint( $attributes['post_id'] ) : get_the_ID();

		if ( ! $post_id ) {
			return '';
		}

		$reading_time_obj = new Reading_Time();
		return $reading_time_obj->get_reading_time_markup( $post_id );
	}
}