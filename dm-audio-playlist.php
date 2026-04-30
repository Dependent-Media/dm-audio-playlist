<?php
/**
 * Plugin Name:       Dependent Media Audio Playlist for Beaver Builder
 * Plugin URI:        https://github.com/Dependent-Media/dm-audio-playlist
 * Description:       A Beaver Builder module that adds a customizable audio playlist player with shuffle, repeat, artwork, and full playback controls. Tracks live in your Media Library; nothing is sent to any external service.
 * Version:           2.0.2
 * Requires at least: 6.2
 * Requires PHP:      7.4
 * Author:            Dependent Media
 * Author URI:        https://dependentmedia.com
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       dependent-media-audio-playlist-for-beaver-builder
 * Domain Path:       /languages
 *
 * @package DM_Audio_Playlist
 */

defined( 'ABSPATH' ) || exit;

define( 'DM_AUDIO_PLAYLIST_VERSION', '2.0.2' );
define( 'DM_AUDIO_PLAYLIST_FILE', __FILE__ );
define( 'DM_AUDIO_PLAYLIST_DIR', plugin_dir_path( __FILE__ ) );
define( 'DM_AUDIO_PLAYLIST_URL', plugin_dir_url( __FILE__ ) );

/**
 * Register the Beaver Builder module on init, but only if BB is loaded.
 */
function dm_audio_playlist_load_module() {
	if ( class_exists( 'FLBuilder' ) ) {
		require_once DM_AUDIO_PLAYLIST_DIR . 'modules/mp3-player/mp3-player.php';
	}
}
add_action( 'init', 'dm_audio_playlist_load_module' );

/**
 * Show an admin notice if Beaver Builder isn't installed/active.
 */
function dm_audio_playlist_beaver_builder_missing_notice() {
	if ( class_exists( 'FLBuilder' ) ) {
		return;
	}
	echo '<div class="notice notice-error"><p>';
	echo esc_html__(
		'Dependent Media Audio Playlist for Beaver Builder requires Beaver Builder (Lite or Pro) to be installed and active.',
		'dependent-media-audio-playlist-for-beaver-builder'
	);
	echo '</p></div>';
}
add_action( 'admin_notices', 'dm_audio_playlist_beaver_builder_missing_notice' );

/**
 * Enqueue the BB editor enhancement script (Media Library picker buttons).
 *
 * The BB editor renders on the frontend with an editor overlay, so
 * wp_enqueue_scripts is the correct hook (not admin_enqueue_scripts).
 */
function dm_audio_playlist_enqueue_editor_assets() {
	if ( ! class_exists( 'FLBuilderModel' ) || ! FLBuilderModel::is_builder_active() ) {
		return;
	}
	if ( ! current_user_can( 'edit_posts' ) ) {
		return;
	}
	wp_enqueue_media();
	wp_enqueue_script(
		'dm-audio-playlist-editor',
		DM_AUDIO_PLAYLIST_URL . 'js/editor.js',
		array( 'jquery' ),
		DM_AUDIO_PLAYLIST_VERSION,
		true
	);
}
add_action( 'wp_enqueue_scripts', 'dm_audio_playlist_enqueue_editor_assets' );

/**
 * Strict allowlist sanitizer for color values pulled from BB's color picker.
 *
 * Used wherever a setting value is interpolated into a CSS context (e.g. as
 * a CSS custom property). Accepts:
 *   - hex with or without leading "#" (3, 4, 6, or 8 chars)
 *   - rgb()  / rgba() with integer channels and optional decimal alpha
 * Anything else returns the per-call default. Pairing this with esc_attr()
 * on the eventual output is defense-in-depth.
 *
 * @param string $val     Raw value from BB settings.
 * @param string $default Fallback to use if $val is empty or doesn't match.
 * @return string A safe CSS color value (always with leading "#" for hex).
 */
function dm_audio_playlist_sanitize_color( $val, $default ) {
	if ( ! is_string( $val ) || $val === '' ) {
		return $default;
	}
	$val = trim( $val );

	// rgb() / rgba() — strict pattern, integer channels, optional decimal alpha.
	if ( preg_match( '/^rgba?\(\s*\d{1,3}\s*,\s*\d{1,3}\s*,\s*\d{1,3}\s*(,\s*[\d.]+\s*)?\)$/', $val ) ) {
		return $val;
	}

	// Hex with or without leading "#".
	$hex = ltrim( $val, '#' );
	if ( preg_match( '/^[0-9a-fA-F]{3,8}$/', $hex ) ) {
		return '#' . $hex;
	}

	return $default;
}
