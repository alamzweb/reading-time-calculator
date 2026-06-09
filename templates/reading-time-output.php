<?php
/**
 * Template for displaying estimated reading time.
 *
 * Variables accessible:
 * @var string $prefix
 * @var string $label
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<div class="rtc-reading-time-container">
	<span class="rtc-icon">📖</span>
	<?php if ( ! empty( $prefix ) ) : ?>
		<span class="rtc-prefix"><?php echo esc_html( $prefix ); ?></span>
	<?php endif; ?>
	<span class="rtc-label"><?php echo esc_html( $label ); ?></span>
</div>