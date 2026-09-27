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

/*
 * The now-playing artwork container. Rendered as a <button> when the artwork
 * lightbox is enabled, so this doubles as a button reset.
 *
 * Must stay ABOVE .dmap-artwork-placeholder: when no artwork is loaded both
 * classes sit on the same element at equal specificity, and the placeholder's
 * background/display need to win.
 */
.dmap-player .dmap-now-art-wrap {
	position: relative;
	display: block;
	flex: 0 0 auto;
	width: 80px;
	height: 80px;
	margin: 0;
	padding: 0;
	border: 0;
	border-radius: 6px;
	background: none;
	overflow: hidden;
	color: inherit;
	font: inherit;
	line-height: 0;
}

.dmap-player button.dmap-now-art-wrap:not([disabled]) {
	cursor: zoom-in;
}

/*
 * Hover/focus affordance. A pseudo-element rather than real markup because the
 * script replaces this element's children wholesale on every track change.
 */
.dmap-player button.dmap-now-art-wrap::after {
	content: "";
	position: absolute;
	top: 0;
	right: 0;
	bottom: 0;
	left: 0;
	border-radius: inherit;
	background-color: rgba(0, 0, 0, 0.45);
	background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%23ffffff' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='15 3 21 3 21 9'/%3E%3Cpolyline points='9 21 3 21 3 15'/%3E%3Cline x1='21' y1='3' x2='14' y2='10'/%3E%3Cline x1='3' y1='21' x2='10' y2='14'/%3E%3C/svg%3E");
	background-repeat: no-repeat;
	background-position: center;
	background-size: 22px 22px;
	opacity: 0;
	transition: opacity 0.18s ease;
	pointer-events: none;
}

.dmap-player button.dmap-now-art-wrap:not([disabled]):hover::after,
.dmap-player button.dmap-now-art-wrap:not([disabled]):focus-visible::after {
	opacity: 1;
}

.dmap-player button.dmap-now-art-wrap:focus-visible {
	outline: 2px solid var(--dmap-accent);
	outline-offset: 2px;
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

/*
 * Artwork lightbox.
 *
 * Deliberately NOT scoped under .dmap-player: the script moves the overlay to
 * <body> so a builder row with overflow:hidden, a transform, or its own
 * stacking context can't clip it. It also stays visually neutral rather than
 * inheriting the player's palette — a lightbox should read as a lightbox.
 */
.dmap-lightbox {
	position: fixed;
	top: 0;
	right: 0;
	bottom: 0;
	left: 0;
	z-index: 100000; /* Above the WP admin bar (99999). */
	display: flex;
	flex-direction: column;
	align-items: center;
	justify-content: center;
	gap: 18px;
	padding: 24px;
	box-sizing: border-box;
	background: rgba(0, 0, 0, 0.9);
	cursor: zoom-out;
	opacity: 0;
	transition: opacity 0.2s ease;
	font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
}

/* Beats the bare .dmap-lightbox display:flex on specificity. */
.dmap-lightbox[hidden] {
	display: none;
}

.dmap-lightbox.is-open {
	opacity: 1;
}

.dmap-lightbox * {
	box-sizing: border-box;
}

.dmap-lightbox .dmap-lightbox-figure {
	display: flex;
	flex-direction: column;
	align-items: center;
	gap: 14px;
	margin: 0;
	max-width: 100%;
	cursor: default;
}

.dmap-lightbox .dmap-lightbox-img {
	display: block;
	width: auto;
	height: auto;
	max-width: 100%;
	max-height: 76vh;
	border-radius: 8px;
	box-shadow: 0 20px 60px rgba(0, 0, 0, 0.55);
}

.dmap-lightbox .dmap-lightbox-caption {
	color: #ffffff;
	text-align: center;
	line-height: 1.35;
}

.dmap-lightbox .dmap-lightbox-title {
	display: block;
	font-size: 18px;
	font-weight: 600;
}

.dmap-lightbox .dmap-lightbox-artist {
	display: block;
	font-size: 14px;
	opacity: 0.7;
	margin-top: 4px;
}

.dmap-lightbox .dmap-lightbox-artist:empty {
	display: none;
}

.dmap-lightbox .dmap-lightbox-close {
	position: absolute;
	top: 12px;
	right: 12px;
	left: auto;
	bottom: auto;
	display: flex;
	align-items: center;
	justify-content: center;
	width: 44px;
	height: 44px;
	padding: 0;
	margin: 0;
	border: 0;
	border-radius: 50%;
	background: rgba(255, 255, 255, 0.12);
	color: #ffffff;
	cursor: pointer;
	transition: background 0.15s ease;
}

/*
 * Themes routinely restyle bare `button:hover` / `:focus` / `:active`. The
 * Beaver Builder theme skin, for one, sets a blue background, a border, and
 * `position: relative` on those states — and `button:focus` (0,1,1) outranks a
 * lone `.dmap-lightbox-close` (0,1,0). Since the script focuses this button
 * when the lightbox opens, that dragged it out of the corner and into the flex
 * column above the image for as long as it held focus. Restate the essentials
 * per state, one level deeper, so no theme can move or recolor it.
 */
.dmap-lightbox .dmap-lightbox-close:hover,
.dmap-lightbox .dmap-lightbox-close:focus,
.dmap-lightbox .dmap-lightbox-close:active {
	position: absolute;
	top: 12px;
	right: 12px;
	left: auto;
	bottom: auto;
	border: 0;
	color: #ffffff;
	background: rgba(255, 255, 255, 0.25);
}

.dmap-lightbox .dmap-lightbox-close:focus-visible {
	outline: 2px solid #ffffff;
	outline-offset: 2px;
}

@media (max-width: 480px) {
	.dmap-lightbox {
		padding: 16px;
	}

	.dmap-lightbox .dmap-lightbox-img {
		max-height: 68vh;
	}
}

@media (prefers-reduced-motion: reduce) {
	.dmap-lightbox,
	.dmap-player button.dmap-now-art-wrap::after {
		transition: none;
	}
}
