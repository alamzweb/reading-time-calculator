<?php
namespace RTC\Includes;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Settings {

	public function __construct() {
		add_action( 'admin_menu', [ $this, 'add_settings_page' ] );
		add_action( 'admin_init', [ $this, 'register_settings' ] );
	}

	public function add_settings_page(): void {
		add_options_page(
			__( 'Reading Time Calculator Settings', 'reading-time-calculator' ),
			__( 'Reading Time Calculator', 'reading-time-calculator' ),
			'manage_options',
			'reading-time-calculator',
			[ $this, 'render_settings_page' ]
		);
	}

	public function render_settings_page(): void {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}
		?>
		<div class="wrap">
			<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
			<form action="options.php" method="post">
				<?php
				settings_fields( 'rtc_settings_group' );
				do_settings_sections( 'reading-time-calculator' );
				submit_button();
				?>
			</form>
		</div>
		<?php
	}

	public function register_settings(): void {
		register_setting( 'rtc_settings_group', 'rtc_settings', [
			'sanitize_callback' => [ $this, 'sanitize_settings' ],
			'default'           => [],
		] );

		add_settings_section(
			'rtc_main_section',
			__( 'Configuration Settings', 'reading-time-calculator' ),
			'__return_false',
			'reading-time-calculator'
		);

		add_settings_field(
			'enable_plugin',
			__( 'Enable Plugin', 'reading-time-calculator' ),
			[ $this, 'render_toggle_field' ],
			'reading-time-calculator',
			'rtc_main_section',
			[ 'label_for' => 'enable_plugin' ]
		);

		add_settings_field(
			'wpm',
			__( 'Reading Speed (WPM)', 'reading-time-calculator' ),
			[ $this, 'render_number_field' ],
			'reading-time-calculator',
			'rtc_main_section',
			[ 'label_for' => 'wpm' ]
		);

		add_settings_field(
			'prefix',
			__( 'Prefix Text', 'reading-time-calculator' ),
			[ $this, 'render_text_field' ],
			'reading-time-calculator',
			'rtc_main_section',
			[ 'label_for' => 'prefix' ]
		);

		add_settings_field(
			'display_position',
			__( 'Display Position', 'reading-time-calculator' ),
			[ $this, 'render_select_field' ],
			'reading-time-calculator',
			'rtc_main_section',
			[ 'label_for' => 'display_position' ]
		);

		add_settings_field(
			'post_types',
			__( 'Supported Post Types', 'reading-time-calculator' ),
			[ $this, 'render_post_types_field' ],
			'reading-time-calculator',
			'rtc_main_section'
		);
	}

	public function sanitize_settings( array $input ): array {
		$output = [];
		$output['enable_plugin']    = isset( $input['enable_plugin'] ) ? 1 : 0;
		$output['wpm']              = isset( $input['wpm'] ) ? absint( $input['wpm'] ) : 200;
		if ( $output['wpm'] <= 0 ) {
			$output['wpm'] = 200;
		}
		$output['prefix']           = isset( $input['prefix'] ) ? sanitize_text_field( $input['prefix'] ) : '';
		$output['display_position'] = isset( $input['display_position'] ) && in_array( $input['display_position'], [ 'before', 'after', 'both', 'disabled' ], true ) ? $input['display_position'] : 'before';

		$output['post_types'] = [];
		if ( isset( $input['post_types'] ) && is_array( $input['post_types'] ) ) {
			foreach ( $input['post_types'] as $type ) {
				$output['post_types'][] = sanitize_key( $type );
			}
		}

		// Delete reading time transients to refresh calculation instantly upon settings change.
		global $wpdb;
		$wpdb->query( "DELETE FROM {$wpdb->options} WHERE option_name LIKE '_transient_rtc_rt_%'" );

		return $output;
	}

	public function render_toggle_field( array $args ): void {
		$options = get_option( 'rtc_settings' );
		$checked = isset( $options['enable_plugin'] ) ? (int) $options['enable_plugin'] : 0;
		echo '<input type="checkbox" id="' . esc_attr( $args['label_for'] ) . '" name="rtc_settings[enable_plugin]" value="1" ' . checked( 1, $checked, false ) . ' />';
	}

	public function render_number_field( array $args ): void {
		$options = get_option( 'rtc_settings' );
		$value   = $options['wpm'] ?? 200;
		echo '<input type="number" class="small-text" id="' . esc_attr( $args['label_for'] ) . '" name="rtc_settings[wpm]" value="' . esc_attr( $value ) . '" min="1" step="1" />';
	}

	public function render_text_field( array $args ): void {
		$options = get_option( 'rtc_settings' );
		$value   = $options['prefix'] ?? '';
		echo '<input type="text" class="regular-text" id="' . esc_attr( $args['label_for'] ) . '" name="rtc_settings[prefix]" value="' . esc_attr( $value ) . '" />';
	}

	public function render_select_field( array $args ): void {
		$options  = get_option( 'rtc_settings' );
		$selected = $options['display_position'] ?? 'before';
		?>
		<select id="<?php echo esc_attr( $args['label_for'] ); ?>" name="rtc_settings[display_position]">
			<option value="before" <?php selected( $selected, 'before' ); ?>><?php esc_html_e( 'Before Content', 'reading-time-calculator' ); ?></option>
			<option value="after" <?php selected( $selected, 'after' ); ?>><?php esc_html_e( 'After Content', 'reading-time-calculator' ); ?></option>
			<option value="both" <?php selected( $selected, 'both' ); ?>><?php esc_html_e( 'Both Before and After', 'reading-time-calculator' ); ?></option>
			<option value="disabled" <?php selected( $selected, 'disabled' ); ?>><?php esc_html_e( 'Disabled (Shortcode/Block Only)', 'reading-time-calculator' ); ?></option>
		</select>
		<?php
	}

	public function render_post_types_field(): void {
		$options      = get_option( 'rtc_settings' );
		$saved_types  = $options['post_types'] ?? [];
		$post_types   = get_post_types( [ 'public' => true ], 'objects' );

		foreach ( $post_types as $post_type ) {
			if ( 'attachment' === $post_type->name ) {
				continue;
			}
			$checked = in_array( $post_type->name, $saved_types, true ) ? 'checked' : '';
			echo '<label style="display:block; margin-bottom:5px;">';
			echo '<input type="checkbox" name="rtc_settings[post_types][]" value="' . esc_attr( $post_type->name ) . '" ' . esc_attr( $checked ) . ' /> ';
			echo esc_html( $post_type->label );
			echo '</label>';
		}
	}
}