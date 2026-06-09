<?php
namespace RTC\Includes;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Reading_Time {

	public function __construct() {
		add_filter( 'the_content', [ $this, 'filter_content' ] );
		add_action( 'save_post', [ $this, 'clear_reading_time_transient' ] );
	}

	public function filter_content( string $content ): string {
		if ( ! is_main_query() || ! in_the_loop() ) {
			return $content;
		}

		$options = get_option( 'rtc_settings' );
		if ( empty( $options['enable_plugin'] ) ) {
			return $content;
		}

		$post_type = get_post_type();
		$allowed_types = $options['post_types'] ?? [];
		if ( ! in_array( $post_type, $allowed_types, true ) ) {
			return $content;
		}

		$position = $options['display_position'] ?? 'before';
		if ( 'disabled' === $position ) {
			return $content;
		}

		$reading_time_html = $this->get_reading_time_markup( get_the_ID() );

		if ( 'before' === $position ) {
			return $reading_time_html . $content;
		} elseif ( 'after' === $position ) {
			return $content . $reading_time_html;
		} elseif ( 'both' === $position ) {
			return $reading_time_html . $content . $reading_time_html;
		}

		return $content;
	}

	public function calculate_reading_time( int $post_id ): int {
		$transient_key = 'rtc_rt_' . $post_id;
		$reading_time  = get_transient( $transient_key );

		if ( false !== $reading_time ) {
			return (int) $reading_time;
		}

		$post = get_post( $post_id );
		if ( ! $post ) {
			return 0;
		}

		$content    = strip_shortcodes( $post->post_content );
		$clean_text = wp_strip_all_tags( $content );
		$word_count = str_word_count( $clean_text );

		$options = get_option( 'rtc_settings' );
		$wpm     = isset( $options['wpm'] ) ? absint( $options['wpm'] ) : 200;
		if ( $wpm <= 0 ) {
			$wpm = 200;
		}

		$reading_time = (int) ceil( $word_count / $wpm );
		if ( $reading_time < 1 ) {
			$reading_time = 1;
		}

		set_transient( $transient_key, $reading_time, DAY_IN_SECONDS );

		return $reading_time;
	}

	public function get_reading_time_markup( int $post_id ): string {
		$options      = get_option( 'rtc_settings' );
		$prefix       = $options['prefix'] ?? __( 'Reading Time:', 'reading-time-calculator' );
		$reading_time = $this->calculate_reading_time( $post_id );

		if ( 0 === $reading_time ) {
			return '';
		}

		$label = sprintf(
			/* translators: %d: number of minutes */
			_n( '%d min read', '%d min read', $reading_time, 'reading-time-calculator' ),
			$reading_time
		);

		ob_start();
		$template_path = RTC_PLUGIN_DIR . 'templates/reading-time-output.php';
		if ( file_exists( $template_path ) ) {
			include $template_path;
		}
		return ob_get_clean();
	}

	public function clear_reading_time_transient( int $post_id ): void {
		delete_transient( 'rtc_rt_' . $post_id );
	}
}