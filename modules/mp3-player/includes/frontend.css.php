<?php defined( 'ABSPATH' ) || exit; ?>
/*
 * Static CSS for the Audio Playlist module.
 *
 * Per-instance colors are passed in as CSS custom properties via the wrapper's
 * inline style attribute (set by frontend.php using sanitized values), so this
 * file contains no PHP interpolation. Multiple module instances on the same
 * page share these rules; each gets its own color values via its own scope.
 */

.dmap-player {
	--dmap-bg: #1a1a2e;
	--dmap-text: #ffffff;
	--dmap-accent: #e94560;
	--dmap-prog-bg: #333333;
	--dmap-hover: #16213e;
	--dmap-radius: 8px;
	--dmap-max-w: 600px;

	background: var(--dmap-bg);
	color: var(--dmap-text);
	border-radius: var(--dmap-radius);
	max-width: var(--dmap-max-w);
	margin: 0 auto;
	padding: 20px;
	font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
	box-sizing: border-box;
}

.dmap-player * {
	box-sizing: border-box;
}

.dmap-player .dmap-now-playing {
	display: flex;
	align-items: center;
	gap: 15px;
	padding: 10px 0 15px;
}

.dmap-player .dmap-artwork {
	width: 80px;
	height: 80px;
	border-radius: 6px;
	object-fit: cover;
	flex-shrink: 0;
	background: var(--dmap-prog-bg);
}

.dmap-player .dmap-artwork-placeholder {
	width: 80px;
	height: 80px;
	border-radius: 6px;
	flex-shrink: 0;
	background: var(--dmap-prog-bg);
	display: flex;
	align-items: center;
	justify-content: center;
	color: var(--dmap-text);
	opacity: 0.3;
}

.dmap-player .dmap-track-info {
	flex: 1;
	min-width: 0;
}

.dmap-player .dmap-track-title {
	font-size: 18px;
	font-weight: 600;
	white-space: nowrap;
	overflow: hidden;
	text-overflow: ellipsis;
}

.dmap-player .dmap-track-artist {
	font-size: 13px;
	opacity: 0.7;
	margin-top: 4px;
}

.dmap-player .dmap-controls {
	display: flex;
	align-items: center;
	justify-content: center;
	gap: 12px;
	padding: 10px 0;
}

.dmap-player .dmap-btn {
	background: none;
	border: none;
	color: var(--dmap-text);
	cursor: pointer;
	padding: 8px;
	border-radius: 50%;
	display: flex;
	align-items: center;
	justify-content: center;
	transition: background 0.2s, color 0.2s;
	position: relative;
	line-height: 1;
}

.dmap-player .dmap-btn:hover {
	background: rgba(255, 255, 255, 0.1);
}

.dmap-player .dmap-btn.active {
	color: var(--dmap-accent);
}

.dmap-player .dmap-play {
	background: var(--dmap-accent);
	width: 48px;
	height: 48px;
	color: #fff;
}

.dmap-player .dmap-play:hover {
	opacity: 0.85;
}

.dmap-player .dmap-repeat-badge {
	position: absolute;
	top: 2px;
	right: 2px;
	font-size: 9px;
	font-weight: bold;
	color: var(--dmap-accent);
}

.dmap-player .dmap-progress-wrap {
	display: flex;
	align-items: center;
	gap: 10px;
	padding: 8px 0;
	font-size: 12px;
	font-variant-numeric: tabular-nums;
}

.dmap-player .dmap-progress-bar {
	flex: 1;
	height: 6px;
	background: var(--dmap-prog-bg);
	border-radius: 3px;
	cursor: pointer;
	position: relative;
	overflow: hidden;
}

.dmap-player .dmap-progress-fill {
	height: 100%;
	width: 0%;
	background: var(--dmap-accent);
	border-radius: 3px;
	transition: width 0.1s linear;
}

.dmap-player .dmap-volume-wrap {
	display: flex;
	align-items: center;
	gap: 8px;
	padding: 4px 0 12px;
	opacity: 0.7;
}

.dmap-player .dmap-volume-wrap:hover {
	opacity: 1;
}

.dmap-player .dmap-volume {
	-webkit-appearance: none;
	appearance: none;
	width: 100px;
	height: 4px;
	background: var(--dmap-prog-bg);
	border-radius: 2px;
	outline: none;
	cursor: pointer;
}

.dmap-player .dmap-volume::-webkit-slider-thumb {
	-webkit-appearance: none;
	width: 14px;
	height: 14px;
	background: var(--dmap-text);
	border-radius: 50%;
	cursor: pointer;
}

.dmap-player .dmap-volume::-moz-range-thumb {
	width: 14px;
	height: 14px;
	background: var(--dmap-text);
	border-radius: 50%;
	cursor: pointer;
	border: none;
}

.dmap-player .dmap-tracklist {
	list-style: none;
	margin: 0;
	padding: 0;
	max-height: 300px;
	overflow-y: auto;
}

.dmap-player .dmap-track {
	display: flex;
	align-items: center;
	gap: 10px;
	padding: 8px 12px;
	cursor: pointer;
	border-radius: 4px;
	transition: background 0.15s;
}

.dmap-player .dmap-track:hover {
	background: var(--dmap-hover);
}

.dmap-player .dmap-track.active {
	background: var(--dmap-hover);
	color: var(--dmap-accent);
}

.dmap-player .dmap-track-thumb {
	width: 36px;
	height: 36px;
	border-radius: 4px;
	object-fit: cover;
	flex-shrink: 0;
}

.dmap-player .dmap-track-thumb-placeholder {
	width: 36px;
	height: 36px;
	border-radius: 4px;
	flex-shrink: 0;
	background: var(--dmap-prog-bg);
	display: flex;
	align-items: center;
	justify-content: center;
	opacity: 0.3;
}

.dmap-player .dmap-track-num {
	font-size: 12px;
	opacity: 0.5;
	min-width: 20px;
	text-align: right;
}

.dmap-player .dmap-track.active .dmap-track-num {
	opacity: 0;
	width: 0;
	min-width: 0;
	overflow: hidden;
	padding: 0;
}

.dmap-player .dmap-track-playing-icon {
	display: none;
}

.dmap-player .dmap-track.active .dmap-track-playing-icon {
	display: inline-flex;
	min-width: 20px;
	justify-content: center;
	color: var(--dmap-accent);
}

.dmap-player .dmap-track-details {
	flex: 1;
	min-width: 0;
}

.dmap-player .dmap-track-name {
	font-size: 14px;
	display: block;
	white-space: nowrap;
	overflow: hidden;
	text-overflow: ellipsis;
}

.dmap-player .dmap-track-artist-name {
	font-size: 12px;
	opacity: 0.5;
	display: block;
}

.dmap-player .dmap-tracklist::-webkit-scrollbar {
	width: 6px;
}

.dmap-player .dmap-tracklist::-webkit-scrollbar-track {
	background: transparent;
}

.dmap-player .dmap-tracklist::-webkit-scrollbar-thumb {
	background: var(--dmap-prog-bg);
	border-radius: 3px;
}
