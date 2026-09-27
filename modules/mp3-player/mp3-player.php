<?php
/**
 * Beaver Builder module registration for the Audio Playlist player.
 *
 * @package DM_Audio_Playlist
 */

defined( 'ABSPATH' ) || exit;

class DM_Audio_Playlist_Module extends FLBuilderModule {

	public function __construct() {
		parent::__construct(
			array(
				'name'          => __( 'Audio Playlist', 'dependent-media-audio-playlist-for-beaver-builder' ),
				'description'   => __( 'A playlist-style audio player with shuffle, repeat, and artwork.', 'dependent-media-audio-playlist-for-beaver-builder' ),
				'group'         => __( 'Media', 'dependent-media-audio-playlist-for-beaver-builder' ),
				'category'      => __( 'Media', 'dependent-media-audio-playlist-for-beaver-builder' ),
				'dir'           => DM_AUDIO_PLAYLIST_DIR . 'modules/mp3-player/',
				'url'           => DM_AUDIO_PLAYLIST_URL . 'modules/mp3-player/',
				'icon'          => 'format-audio.svg',
				'editor_export' => true,
				'enabled'       => true,
			)
		);
	}
}

/*
 * Backwards-compat shim for the prior internal "BB MP3 Player" 1.x builds.
 *
 * Beaver Builder stores each module instance in the page's saved layout data
 * by PHP class name. Sites that ran the 1.x builds have layouts referencing
 * the old class name (BBMp3PlayerModule) and the old track form key
 * (bb_mp3_track_form). We alias the class and re-register the module + form
 * under both names so existing layouts resolve cleanly after upgrade.
 *
 * New instances added in 2.x layouts use the new names (DM_Audio_Playlist_Module
 * and dm_audio_playlist_track_form). The aliases can be removed once we're
 * confident no live site is still rendering legacy 1.x BB layout data — but
 * the cost of leaving them in place indefinitely is negligible.
 */
if ( ! class_exists( 'BBMp3PlayerModule' ) ) {
	class_alias( 'DM_Audio_Playlist_Module', 'BBMp3PlayerModule' );
}

$dmap_module_config = array(
	'general' => array(
		'title'    => __( 'Tracks', 'dependent-media-audio-playlist-for-beaver-builder' ),
		'sections' => array(
			'tracks'   => array(
				'title'  => __( 'Playlist', 'dependent-media-audio-playlist-for-beaver-builder' ),
				'fields' => array(
					'tracks' => array(
						'type'         => 'form',
						'label'        => __( 'Track', 'dependent-media-audio-playlist-for-beaver-builder' ),
						'form'         => 'dm_audio_playlist_track_form',
						'preview_text' => 'title',
						'multiple'     => true,
					),
				),
			),
			'playback' => array(
				'title'  => __( 'Playback', 'dependent-media-audio-playlist-for-beaver-builder' ),
				'fields' => array(
					'initial_volume' => array(
						'type'    => 'unit',
						'label'   => __( 'Initial Volume (%)', 'dependent-media-audio-playlist-for-beaver-builder' ),
						'default' => '80',
						'slider'  => array(
							'min'  => 0,
							'max'  => 100,
							'step' => 5,
						),
						'help'    => __( 'Set the starting volume level (0-100).', 'dependent-media-audio-playlist-for-beaver-builder' ),
					),
					'autoplay'       => array(
						'type'    => 'select',
						'label'   => __( 'Autoplay', 'dependent-media-audio-playlist-for-beaver-builder' ),
						'default' => 'no',
						'options' => array(
							'no'  => __( 'Off', 'dependent-media-audio-playlist-for-beaver-builder' ),
							'yes' => __( 'On', 'dependent-media-audio-playlist-for-beaver-builder' ),
						),
						'help'    => __( 'Autoplay the first track when the page loads. Note: most browsers block autoplay with sound — the player will attempt to play and fall back gracefully if blocked.', 'dependent-media-audio-playlist-for-beaver-builder' ),
					),
				),
			),
			'artwork'  => array(
				'title'  => __( 'Artwork', 'dependent-media-audio-playlist-for-beaver-builder' ),
				'fields' => array(
					'artwork_lightbox' => array(
						'type'    => 'select',
						'label'   => __( 'Click Artwork to Enlarge', 'dependent-media-audio-playlist-for-beaver-builder' ),
						'default' => 'yes',
						'options' => array(
							'yes' => __( 'On', 'dependent-media-audio-playlist-for-beaver-builder' ),
							'no'  => __( 'Off', 'dependent-media-audio-playlist-for-beaver-builder' ),
						),
						'help'    => __( 'When on, clicking the now-playing artwork opens it full size in a lightbox. Tracks with no artwork stay non-clickable.', 'dependent-media-audio-playlist-for-beaver-builder' ),
					),
				),
			),
		),
	),
	'style'   => array(
		'title'    => __( 'Style', 'dependent-media-audio-playlist-for-beaver-builder' ),
		'sections' => array(
			'colors' => array(
				'title'  => __( 'Base Colors', 'dependent-media-audio-playlist-for-beaver-builder' ),
				'fields' => array(
					'bg_color' => array(
						'type'       => 'color',
						'label'      => __( 'Background Color', 'dependent-media-audio-playlist-for-beaver-builder' ),
						'default'    => '1a1a2e',
						'show_reset' => true,
						'show_alpha' => true,
					),
					'text_color' => array(
						'type'       => 'color',
						'label'      => __( 'Text Color', 'dependent-media-audio-playlist-for-beaver-builder' ),
						'default'    => 'ffffff',
						'show_reset' => true,
						'help'       => __( 'Default for all text and icons. The options in the sections below override it for individual parts.', 'dependent-media-audio-playlist-for-beaver-builder' ),
					),
					'accent_color' => array(
						'type'       => 'color',
						'label'      => __( 'Accent Color', 'dependent-media-audio-playlist-for-beaver-builder' ),
						'default'    => 'e94560',
						'show_reset' => true,
						'help'       => __( 'Default for the play button, progress fill, active track, and shuffle/repeat when on. The options in the sections below override it for individual parts.', 'dependent-media-audio-playlist-for-beaver-builder' ),
					),
				),
			),
			'colors_now_playing' => array(
				'title'  => __( 'Now Playing Colors', 'dependent-media-audio-playlist-for-beaver-builder' ),
				'fields' => array(
					'title_color' => array(
						'type'       => 'color',
						'label'      => __( 'Song Title', 'dependent-media-audio-playlist-for-beaver-builder' ),
						'default'    => '',
						'show_reset' => true,
						'help'       => __( 'The large title of the current song. Leave blank to use the Text Color.', 'dependent-media-audio-playlist-for-beaver-builder' ),
					),
					'artist_color' => array(
						'type'       => 'color',
						'label'      => __( 'Artist Name', 'dependent-media-audio-playlist-for-beaver-builder' ),
						'default'    => '',
						'show_reset' => true,
						'help'       => __( 'Leave blank for a faded Text Color.', 'dependent-media-audio-playlist-for-beaver-builder' ),
					),
				),
			),
			'colors_controls' => array(
				'title'  => __( 'Button Colors', 'dependent-media-audio-playlist-for-beaver-builder' ),
				'fields' => array(
					'play_bg_color' => array(
						'type'       => 'color',
						'label'      => __( 'Play Button Circle', 'dependent-media-audio-playlist-for-beaver-builder' ),
						'default'    => '',
						'show_reset' => true,
						'show_alpha' => true,
						'help'       => __( 'Leave blank to use the Accent Color.', 'dependent-media-audio-playlist-for-beaver-builder' ),
					),
					'play_icon_color' => array(
						'type'       => 'color',
						'label'      => __( 'Play / Pause Icon', 'dependent-media-audio-playlist-for-beaver-builder' ),
						'default'    => '',
						'show_reset' => true,
						'help'       => __( 'Color of the play triangle and pause bars. Leave blank to pick automatically: white on a dark button, the background color (or near-black) on a light one.', 'dependent-media-audio-playlist-for-beaver-builder' ),
					),
					'control_color' => array(
						'type'       => 'color',
						'label'      => __( 'Other Buttons', 'dependent-media-audio-playlist-for-beaver-builder' ),
						'default'    => '',
						'show_reset' => true,
						'help'       => __( 'Shuffle, previous, next and repeat. Leave blank to use the Text Color.', 'dependent-media-audio-playlist-for-beaver-builder' ),
					),
					'control_hover_bg_color' => array(
						'type'       => 'color',
						'label'      => __( 'Button Hover Circle', 'dependent-media-audio-playlist-for-beaver-builder' ),
						'default'    => '',
						'show_reset' => true,
						'show_alpha' => true,
						'help'       => __( 'The faint circle behind a button when the mouse is over it. Leave blank for a light tint.', 'dependent-media-audio-playlist-for-beaver-builder' ),
					),
					'control_active_color' => array(
						'type'       => 'color',
						'label'      => __( 'Shuffle / Repeat When On', 'dependent-media-audio-playlist-for-beaver-builder' ),
						'default'    => '',
						'show_reset' => true,
						'help'       => __( 'Leave blank to use the Accent Color.', 'dependent-media-audio-playlist-for-beaver-builder' ),
					),
				),
			),
			'colors_progress' => array(
				'title'  => __( 'Progress & Volume Colors', 'dependent-media-audio-playlist-for-beaver-builder' ),
				'fields' => array(
					'time_color' => array(
						'type'       => 'color',
						'label'      => __( 'Time Numbers', 'dependent-media-audio-playlist-for-beaver-builder' ),
						'default'    => '',
						'show_reset' => true,
						'help'       => __( 'The elapsed and total time. Leave blank to use the Text Color.', 'dependent-media-audio-playlist-for-beaver-builder' ),
					),
					'progress_bg_color' => array(
						'type'       => 'color',
						'label'      => __( 'Progress Bar Background', 'dependent-media-audio-playlist-for-beaver-builder' ),
						'default'    => '333333',
						'show_reset' => true,
						'show_alpha' => true,
						'help'       => __( 'Also the default for the volume bar, scrollbar and empty artwork boxes.', 'dependent-media-audio-playlist-for-beaver-builder' ),
					),
					'progress_fill_color' => array(
						'type'       => 'color',
						'label'      => __( 'Progress Bar Fill', 'dependent-media-audio-playlist-for-beaver-builder' ),
						'default'    => '',
						'show_reset' => true,
						'help'       => __( 'Leave blank to use the Accent Color.', 'dependent-media-audio-playlist-for-beaver-builder' ),
					),
					'volume_icon_color' => array(
						'type'       => 'color',
						'label'      => __( 'Volume Icon', 'dependent-media-audio-playlist-for-beaver-builder' ),
						'default'    => '',
						'show_reset' => true,
						'help'       => __( 'Leave blank to use the Text Color.', 'dependent-media-audio-playlist-for-beaver-builder' ),
					),
					'volume_track_color' => array(
						'type'       => 'color',
						'label'      => __( 'Volume Bar', 'dependent-media-audio-playlist-for-beaver-builder' ),
						'default'    => '',
						'show_reset' => true,
						'show_alpha' => true,
						'help'       => __( 'Leave blank to use the Progress Bar Background.', 'dependent-media-audio-playlist-for-beaver-builder' ),
					),
					'volume_thumb_color' => array(
						'type'       => 'color',
						'label'      => __( 'Volume Knob', 'dependent-media-audio-playlist-for-beaver-builder' ),
						'default'    => '',
						'show_reset' => true,
						'help'       => __( 'Leave blank to use the Text Color.', 'dependent-media-audio-playlist-for-beaver-builder' ),
					),
				),
			),
			'colors_tracklist' => array(
				'title'  => __( 'Track List Colors', 'dependent-media-audio-playlist-for-beaver-builder' ),
				'fields' => array(
					'track_text_color' => array(
						'type'       => 'color',
						'label'      => __( 'Song Names', 'dependent-media-audio-playlist-for-beaver-builder' ),
						'default'    => '',
						'show_reset' => true,
						'help'       => __( 'Leave blank to use the Text Color.', 'dependent-media-audio-playlist-for-beaver-builder' ),
					),
					'track_meta_color' => array(
						'type'       => 'color',
						'label'      => __( 'Track Numbers & Artists', 'dependent-media-audio-playlist-for-beaver-builder' ),
						'default'    => '',
						'show_reset' => true,
						'help'       => __( 'Leave blank for a faded Text Color.', 'dependent-media-audio-playlist-for-beaver-builder' ),
					),
					'track_hover_color' => array(
						'type'       => 'color',
						'label'      => __( 'Row Hover Background', 'dependent-media-audio-playlist-for-beaver-builder' ),
						'default'    => '16213e',
						'show_reset' => true,
						'show_alpha' => true,
					),
					'active_track_bg_color' => array(
						'type'       => 'color',
						'label'      => __( 'Playing Row Background', 'dependent-media-audio-playlist-for-beaver-builder' ),
						'default'    => '',
						'show_reset' => true,
						'show_alpha' => true,
						'help'       => __( 'Leave blank to use the Row Hover Background.', 'dependent-media-audio-playlist-for-beaver-builder' ),
					),
					'active_track_text_color' => array(
						'type'       => 'color',
						'label'      => __( 'Playing Row Text', 'dependent-media-audio-playlist-for-beaver-builder' ),
						'default'    => '',
						'show_reset' => true,
						'help'       => __( 'Leave blank to use the Accent Color.', 'dependent-media-audio-playlist-for-beaver-builder' ),
					),
					'scrollbar_color' => array(
						'type'       => 'color',
						'label'      => __( 'Scrollbar', 'dependent-media-audio-playlist-for-beaver-builder' ),
						'default'    => '',
						'show_reset' => true,
						'help'       => __( 'Leave blank to use the Progress Bar Background.', 'dependent-media-audio-playlist-for-beaver-builder' ),
					),
				),
			),
			'dimensions' => array(
				'title'  => __( 'Dimensions', 'dependent-media-audio-playlist-for-beaver-builder' ),
				'fields' => array(
					'border_radius' => array(
						'type'    => 'unit',
						'label'   => __( 'Border Radius', 'dependent-media-audio-playlist-for-beaver-builder' ),
						'default' => '8',
						'units'   => array( 'px' ),
						'slider'  => true,
					),
					'max_width'     => array(
						'type'    => 'unit',
						'label'   => __( 'Max Width', 'dependent-media-audio-playlist-for-beaver-builder' ),
						'default' => '600',
						'units'   => array( 'px', '%' ),
						'slider'  => true,
					),
				),
			),
		),
	),
);

$dmap_track_form = array(
	'title' => __( 'Track', 'dependent-media-audio-playlist-for-beaver-builder' ),
	'tabs'  => array(
		'general' => array(
			'title'    => __( 'General', 'dependent-media-audio-playlist-for-beaver-builder' ),
			'sections' => array(
				'details' => array(
					'title'  => '',
					'fields' => array(
						'audio_url'   => array(
							'type'        => 'text',
							'label'       => __( 'Audio File URL', 'dependent-media-audio-playlist-for-beaver-builder' ),
							'placeholder' => 'https://yoursite.com/wp-content/uploads/.../file.mp3',
							'help'        => __( 'Paste an audio URL or click Browse Audio Files below.', 'dependent-media-audio-playlist-for-beaver-builder' ),
							'sanitize'    => 'esc_url_raw',
						),
						'title'       => array(
							'type'     => 'text',
							'label'    => __( 'Track Title', 'dependent-media-audio-playlist-for-beaver-builder' ),
							'sanitize' => 'sanitize_text_field',
						),
						'artist'      => array(
							'type'     => 'text',
							'label'    => __( 'Artist', 'dependent-media-audio-playlist-for-beaver-builder' ),
							'sanitize' => 'sanitize_text_field',
						),
						'artwork_url' => array(
							'type'        => 'text',
							'label'       => __( 'Artwork Image URL', 'dependent-media-audio-playlist-for-beaver-builder' ),
							'placeholder' => 'https://yoursite.com/wp-content/uploads/.../cover.jpg',
							'help'        => __( 'Optional. Paste an image URL or click Browse Images below.', 'dependent-media-audio-playlist-for-beaver-builder' ),
							'sanitize'    => 'esc_url_raw',
						),
					),
				),
			),
		),
	),
);

FLBuilder::register_module( 'DM_Audio_Playlist_Module', $dmap_module_config );
FLBuilder::register_module( 'BBMp3PlayerModule',        $dmap_module_config );

FLBuilder::register_settings_form( 'dm_audio_playlist_track_form', $dmap_track_form );
FLBuilder::register_settings_form( 'bb_mp3_track_form',            $dmap_track_form );
