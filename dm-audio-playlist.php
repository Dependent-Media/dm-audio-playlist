<?php
/**
 * Plugin Name:       Dependent Media Audio Playlist for Beaver Builder
 * Plugin URI:        https://github.com/Dependent-Media/dm-audio-playlist
 * Description:       A Beaver Builder module that adds a customizable audio playlist player with shuffle, repeat, artwork, and full playback controls. Tracks live in your Media Library; nothing is sent to any external service.
 * Version:           2.2.0
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

define( 'DM_AUDIO_PLAYLIST_VERSION', '2.2.0' );
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

/**
 * Whether a sanitized color is "light" — i.e. white text/icons on it would
 * be hard to read.
 *
 * Uses perceived brightness (0.299R + 0.587G + 0.114B) with a threshold of
 * 160 out of 255. Alpha is ignored: a translucent color is judged by its
 * channels alone, which is the right call for the common near-opaque case.
 *
 * @param string $color Output of dm_audio_playlist_sanitize_color().
 * @return bool|null True if light, false if dark, null if unparseable.
 */
function dm_audio_playlist_is_light_color( $color ) {
	if ( ! is_string( $color ) || '' === $color ) {
		return null;
	}

	if ( preg_match( '/^rgba?\(\s*(\d{1,3})\s*,\s*(\d{1,3})\s*,\s*(\d{1,3})/', $color, $m ) ) {
		$r = min( 255, (int) $m[1] );
		$g = min( 255, (int) $m[2] );
		$b = min( 255, (int) $m[3] );
	} else {
		$hex = ltrim( $color, '#' );
		$len = strlen( $hex );
		if ( ! ctype_xdigit( $hex ) ) {
			return null;
		}
		if ( 3 === $len || 4 === $len ) {
			$r = hexdec( str_repeat( $hex[0], 2 ) );
			$g = hexdec( str_repeat( $hex[1], 2 ) );
			$b = hexdec( str_repeat( $hex[2], 2 ) );
		} elseif ( 6 === $len || 8 === $len ) {
			$r = hexdec( substr( $hex, 0, 2 ) );
			$g = hexdec( substr( $hex, 2, 2 ) );
			$b = hexdec( substr( $hex, 4, 2 ) );
		} else {
			return null;
		}
	}

	return ( 0.299 * $r + 0.587 * $g + 0.114 * $b ) > 160;
}

/**
 * Pick a play/pause icon color that stays visible on the accent-colored
 * play button.
 *
 * Dark (or unparseable) accent keeps the historic white icon. A light accent
 * uses the player background, which ties the icon to the rest of the palette,
 * unless the background is light or mostly transparent (the background field
 * allows alpha, and a see-through icon is no better) — then near-black.
 *
 * @param string $accent Sanitized accent color.
 * @param string $bg     Sanitized player background color.
 * @return string A safe CSS color value.
 */
function dm_audio_playlist_play_icon_color( $accent, $bg ) {
	if ( true !== dm_audio_playlist_is_light_color( $accent ) ) {
		return '#ffffff';
	}
	$bg_alpha = 1.0;
	if ( preg_match( '/^rgba\(.*,\s*([\d.]+)\s*\)$/', $bg, $m ) ) {
		$bg_alpha = (float) $m[1];
	} elseif ( preg_match( '/^#(?:[0-9a-f]{3}([0-9a-f])|[0-9a-f]{6}([0-9a-f]{2}))$/i', $bg, $m ) ) {
		$bg_alpha = hexdec( ! empty( $m[2] ) ? $m[2] : str_repeat( $m[1], 2 ) ) / 255;
	}
	if ( $bg_alpha < 0.5 || false !== dm_audio_playlist_is_light_color( $bg ) ) {
		return '#111111';
	}
	return $bg;
}

/**
 * Resolve an intermediate-size image URL back to its original upload.
 *
 * The artwork field stores whatever URL the media picker handed back, which is
 * often a resized copy ("cover-300x300.jpg"). That's the right size for the
 * 80px thumbnail but far too small for the lightbox, so we strip WordPress's
 * "-<width>x<height>" size suffix to recover the original.
 *
 * Deliberately conservative — the rewritten URL is only returned when:
 *   - the URL is inside this site's uploads directory (we can't reason about
 *     the naming scheme of an arbitrary remote host), and
 *   - the un-suffixed file actually exists on disk, since a user is perfectly
 *     free to upload a file genuinely named "cover-300x300.jpg".
 * Anything else falls through to the original URL untouched.
 *
 * "-scaled" images are intentionally left alone: that variant _is_ the version
 * WordPress serves, and the true original behind it can be enormous.
 *
 * @param string $url Artwork URL as stored in the module settings.
 * @return string Full-size URL when one can be resolved, otherwise $url.
 */
function dm_audio_playlist_full_size_url( $url ) {
	if ( ! is_string( $url ) || '' === $url ) {
		return '';
	}

	$uploads = wp_get_upload_dir();
	if ( empty( $uploads['baseurl'] ) || empty( $uploads['basedir'] ) ) {
		return $url;
	}

	// Compare scheme-agnostically; stored URLs predate any http->https move.
	$base_rel = preg_replace( '#^https?:#i', '', $uploads['baseurl'] );
	$url_rel  = preg_replace( '#^https?:#i', '', $url );
	if ( 0 !== strpos( $url_rel, $base_rel ) ) {
		return $url;
	}

	$full_rel = preg_replace( '/-\d+x\d+(\.[a-zA-Z0-9]+)$/', '$1', $url_rel );
	if ( null === $full_rel || $full_rel === $url_rel ) {
		return $url;
	}

	$path = $uploads['basedir'] . substr( $full_rel, strlen( $base_rel ) );
	if ( ! file_exists( $path ) ) {
		return $url;
	}

	// Rebuild on the original URL's scheme rather than the uploads baseurl's.
	$scheme = ( 0 === strpos( $url, 'https:' ) ) ? 'https:' : ( ( 0 === strpos( $url, 'http:' ) ) ? 'http:' : '' );

	return $scheme . $full_rel;
}
