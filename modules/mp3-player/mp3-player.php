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
			'colors'     => array(
				'title'  => __( 'Colors', 'dependent-media-audio-playlist-for-beaver-builder' ),
				'fields' => array(
					'bg_color'          => array(
						'type'       => 'color',
						'label'      => __( 'Background Color', 'dependent-media-audio-playlist-for-beaver-builder' ),
						'default'    => '1a1a2e',
						'show_reset' => true,
						'show_alpha' => true,
					),
					'text_color'        => array(
						'type'       => 'color',
						'label'      => __( 'Text Color', 'dependent-media-audio-playlist-for-beaver-builder' ),
						'default'    => 'ffffff',
						'show_reset' => true,
					),
					'accent_color'      => array(
						'type'       => 'color',
						'label'      => __( 'Accent Color', 'dependent-media-audio-playlist-for-beaver-builder' ),
						'default'    => 'e94560',
						'show_reset' => true,
					),
					'play_icon_color'   => array(
						'type'       => 'color',
						'label'      => __( 'Play Icon Color', 'dependent-media-audio-playlist-for-beaver-builder' ),
						'default'    => '',
						'show_reset' => true,
						'help'       => __( 'Color of the play/pause icon on the accent-colored button. Leave blank to pick automatically: white on a dark accent, the background color (or near-black) on a light one.', 'dependent-media-audio-playlist-for-beaver-builder' ),
					),
					'progress_bg_color' => array(
						'type'       => 'color',
						'label'      => __( 'Progress Bar Background', 'dependent-media-audio-playlist-for-beaver-builder' ),
						'default'    => '333333',
						'show_reset' => true,
					),
					'track_hover_color' => array(
						'type'       => 'color',
						'label'      => __( 'Track Hover Color', 'dependent-media-audio-playlist-for-beaver-builder' ),
						'default'    => '16213e',
						'show_reset' => true,
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
