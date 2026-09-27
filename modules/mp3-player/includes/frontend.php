<?php
/**
 * Frontend HTML for a single Audio Playlist module instance.
 *
 * Per-instance colors are emitted as CSS custom properties on the wrapper's
 * inline style attribute; the actual CSS rules live in frontend.css.php and
 * reference those properties via var(). All color values pass through
 * dm_audio_playlist_sanitize_color() before being written, so an attacker
 * who tampers with saved settings cannot inject arbitrary CSS.
 *
 * BB provides $settings and $id in scope when rendering modules.
 *
 * @package DM_Audio_Playlist
 */

defined( 'ABSPATH' ) || exit;

$tracks = isset( $settings->tracks ) ? $settings->tracks : array();
if ( empty( $tracks ) ) {
	return;
}

$player_id = 'dmap-' . $id;

// Sanitize per-instance colors (defaults match the BB color-field defaults).
$bg      = dm_audio_playlist_sanitize_color( $settings->bg_color          ?? '', '#1a1a2e' );
$text    = dm_audio_playlist_sanitize_color( $settings->text_color        ?? '', '#ffffff' );
$accent  = dm_audio_playlist_sanitize_color( $settings->accent_color      ?? '', '#e94560' );
$prog_bg = dm_audio_playlist_sanitize_color( $settings->progress_bg_color ?? '', '#333333' );
$hover   = dm_audio_playlist_sanitize_color( $settings->track_hover_color ?? '', '#16213e' );

// Dimension values are integer-bounded, then suffixed with the relevant unit.
$radius = ! empty( $settings->border_radius ) ? absint( $settings->border_radius ) . 'px' : '8px';
$max_w  = ! empty( $settings->max_width )     ? absint( $settings->max_width ) . 'px'     : '600px';

$style_vars = sprintf(
	'--dmap-bg:%s;--dmap-text:%s;--dmap-accent:%s;--dmap-prog-bg:%s;--dmap-hover:%s;--dmap-radius:%s;--dmap-max-w:%s;',
	$bg,
	$text,
	$accent,
	$prog_bg,
	$hover,
	$radius,
	$max_w
);

$init_vol = isset( $settings->initial_volume ) && $settings->initial_volume !== '' ? absint( $settings->initial_volume ) : 80;
$init_vol = min( 100, max( 0, $init_vol ) );
$autoplay = ( isset( $settings->autoplay ) && $settings->autoplay === 'yes' ) ? 'true' : 'false';

// Artwork lightbox defaults to on, including for layouts saved before the
// setting existed — those have no artwork_lightbox key at all.
$lightbox_on = ! isset( $settings->artwork_lightbox ) || $settings->artwork_lightbox !== 'no';

// Show the artwork column only if at least one track has artwork.
$has_any_art = false;
foreach ( $tracks as $track ) {
	if ( ! empty( $track->artwork_url ) ) {
		$has_any_art = true;
		break;
	}
}
?>
<div class="dmap-player"
	id="<?php echo esc_attr( $player_id ); ?>"
	style="<?php echo esc_attr( $style_vars ); ?>"
	data-initial-volume="<?php echo esc_attr( $init_vol ); ?>"
	data-autoplay="<?php echo esc_attr( $autoplay ); ?>"
	data-lightbox="<?php echo $lightbox_on ? 'true' : 'false'; ?>">

	<div class="dmap-now-playing">
		<?php if ( $lightbox_on ) : ?>
			<?php
			/*
			 * A real <button> so the artwork is keyboard-reachable and announced
			 * as actionable. It ships disabled — nothing is loaded yet, so the
			 * placeholder is showing — and the script enables it whenever the
			 * track it switches to actually has artwork.
			 */
			?>
			<button type="button"
				class="dmap-artwork-placeholder dmap-now-art-wrap"
				aria-label="<?php esc_attr_e( 'View larger artwork', 'dependent-media-audio-playlist-for-beaver-builder' ); ?>"
				disabled>
				<svg width="32" height="32" viewBox="0 0 24 24" fill="currentColor"><path d="M12 3v10.55c-.59-.34-1.27-.55-2-.55C7.79 13 6 14.79 6 17s1.79 4 4 4 4-1.79 4-4V7h4V3h-6z"/></svg>
			</button>
		<?php else : ?>
			<div class="dmap-artwork-placeholder dmap-now-art-wrap">
				<svg width="32" height="32" viewBox="0 0 24 24" fill="currentColor"><path d="M12 3v10.55c-.59-.34-1.27-.55-2-.55C7.79 13 6 14.79 6 17s1.79 4 4 4 4-1.79 4-4V7h4V3h-6z"/></svg>
			</div>
		<?php endif; ?>
		<div class="dmap-track-info">
			<div class="dmap-track-title">&mdash;</div>
			<div class="dmap-track-artist"></div>
		</div>
	</div>

	<div class="dmap-controls">
		<button class="dmap-btn dmap-shuffle" title="<?php esc_attr_e( 'Shuffle', 'dependent-media-audio-playlist-for-beaver-builder' ); ?>" aria-label="<?php esc_attr_e( 'Shuffle', 'dependent-media-audio-playlist-for-beaver-builder' ); ?>">
			<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
				<polyline points="16 3 21 3 21 8"/><line x1="4" y1="20" x2="21" y2="3"/>
				<polyline points="21 16 21 21 16 21"/><line x1="15" y1="15" x2="21" y2="21"/>
				<line x1="4" y1="4" x2="9" y2="9"/>
			</svg>
		</button>
		<button class="dmap-btn dmap-prev" title="<?php esc_attr_e( 'Previous', 'dependent-media-audio-playlist-for-beaver-builder' ); ?>" aria-label="<?php esc_attr_e( 'Previous', 'dependent-media-audio-playlist-for-beaver-builder' ); ?>">
			<svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
				<path d="M6 6h2v12H6zm3.5 6l8.5 6V6z"/>
			</svg>
		</button>
		<button class="dmap-btn dmap-play" title="<?php esc_attr_e( 'Play', 'dependent-media-audio-playlist-for-beaver-builder' ); ?>" aria-label="<?php esc_attr_e( 'Play', 'dependent-media-audio-playlist-for-beaver-builder' ); ?>">
			<svg class="dmap-icon-play" width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
				<path d="M8 5v14l11-7z"/>
			</svg>
			<svg class="dmap-icon-pause" width="24" height="24" viewBox="0 0 24 24" fill="currentColor" style="display:none;">
				<path d="M6 19h4V5H6v14zm8-14v14h4V5h-4z"/>
			</svg>
		</button>
		<button class="dmap-btn dmap-next" title="<?php esc_attr_e( 'Next', 'dependent-media-audio-playlist-for-beaver-builder' ); ?>" aria-label="<?php esc_attr_e( 'Next', 'dependent-media-audio-playlist-for-beaver-builder' ); ?>">
			<svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
				<path d="M6 18l8.5-6L6 6v12zM16 6v12h2V6h-2z"/>
			</svg>
		</button>
		<button class="dmap-btn dmap-repeat" title="<?php esc_attr_e( 'Repeat', 'dependent-media-audio-playlist-for-beaver-builder' ); ?>" aria-label="<?php esc_attr_e( 'Repeat', 'dependent-media-audio-playlist-for-beaver-builder' ); ?>">
			<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
				<polyline points="17 1 21 5 17 9"/><path d="M3 11V9a4 4 0 0 1 4-4h14"/>
				<polyline points="7 23 3 19 7 15"/><path d="M21 13v2a4 4 0 0 1-4 4H3"/>
			</svg>
			<span class="dmap-repeat-badge" style="display:none;">1</span>
		</button>
	</div>

	<div class="dmap-progress-wrap">
		<span class="dmap-time-current">0:00</span>
		<div class="dmap-progress-bar">
			<div class="dmap-progress-fill"></div>
		</div>
		<span class="dmap-time-duration">0:00</span>
	</div>

	<div class="dmap-volume-wrap">
		<svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
			<path d="M3 9v6h4l5 5V4L7 9H3zm13.5 3c0-1.77-1.02-3.29-2.5-4.03v8.05c1.48-.73 2.5-2.25 2.5-4.02z"/>
		</svg>
		<input type="range" class="dmap-volume" min="0" max="1" step="0.05" value="<?php echo esc_attr( $init_vol / 100 ); ?>" aria-label="<?php esc_attr_e( 'Volume', 'dependent-media-audio-playlist-for-beaver-builder' ); ?>">
	</div>

	<ul class="dmap-tracklist">
		<?php
		foreach ( $tracks as $i => $track ) :
			$audio_url = ! empty( $track->audio_url ) ? $track->audio_url : '';
			if ( empty( $audio_url ) ) {
				continue;
			}

			$title       = ! empty( $track->title )       ? $track->title       : sprintf( /* translators: %d: track number */ __( 'Track %d', 'dependent-media-audio-playlist-for-beaver-builder' ), $i + 1 );
			$artist      = ! empty( $track->artist )      ? $track->artist      : '';
			$artwork_url = ! empty( $track->artwork_url ) ? $track->artwork_url : '';
			// The list/now-playing thumbnails keep whatever size was picked;
			// the lightbox gets the original so enlarging it is worth doing.
			$artwork_full = $artwork_url ? dm_audio_playlist_full_size_url( $artwork_url ) : '';
			?>
			<li class="dmap-track"
				data-src="<?php echo esc_url( $audio_url ); ?>"
				data-title="<?php echo esc_attr( $title ); ?>"
				data-artist="<?php echo esc_attr( $artist ); ?>"
				data-artwork="<?php echo esc_url( $artwork_url ); ?>"
				data-artwork-full="<?php echo esc_url( $artwork_full ); ?>">
				<?php if ( $has_any_art ) : ?>
					<?php if ( $artwork_url ) : ?>
						<img class="dmap-track-thumb" src="<?php echo esc_url( $artwork_url ); ?>" alt="" loading="lazy">
					<?php else : ?>
						<span class="dmap-track-thumb-placeholder">
							<svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M12 3v10.55c-.59-.34-1.27-.55-2-.55C7.79 13 6 14.79 6 17s1.79 4 4 4 4-1.79 4-4V7h4V3h-6z"/></svg>
						</span>
					<?php endif; ?>
				<?php endif; ?>
				<span class="dmap-track-num"><?php echo esc_html( $i + 1 ); ?></span>
				<span class="dmap-track-playing-icon">
					<svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor"><path d="M3 9v6h4l5 5V4L7 9H3z"/></svg>
				</span>
				<span class="dmap-track-details">
					<span class="dmap-track-name"><?php echo esc_html( $title ); ?></span>
					<?php if ( $artist ) : ?>
						<span class="dmap-track-artist-name"><?php echo esc_html( $artist ); ?></span>
					<?php endif; ?>
				</span>
			</li>
		<?php endforeach; ?>
	</ul>

	<audio class="dmap-audio" preload="metadata"></audio>
</div>
