=== Dependent Media Audio Playlist for Beaver Builder ===
Contributors: hsojhsoj
Tags: beaver-builder, audio, playlist, mp3, music
Requires at least: 6.2
Tested up to: 6.9
Requires PHP: 7.4
Stable tag: 2.1.2
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

A Beaver Builder module: customizable audio playlist player with shuffle, repeat, artwork, volume, and full playback controls.

== Description ==

Dependent Media Audio Playlist for Beaver Builder adds a self-contained audio playlist module to the Beaver Builder page editor. Drop it onto a page, paste in audio URLs (or pick files from your media library), and you get a full playlist player with play/pause, previous/next, shuffle, repeat, scrubbable progress, and a volume slider — all styleable from the standard Beaver Builder color and dimension settings.

This plugin is **not affiliated with or endorsed by Beaver Builder**. "Beaver Builder" is a trademark of Beaver Builder LLC and is used in this plugin's name and description solely to describe what page builder this module integrates with.

= Features =

* Full playlist UI with track titles, artists, and optional artwork
* Play, pause, previous, next, shuffle, repeat (off / repeat-all / repeat-one)
* Scrubbable progress bar with current time and duration
* Volume slider with configurable initial volume
* Optional autoplay (browser-permitting)
* Click the now-playing artwork to open it full size in a lightbox
* Per-instance color customization: background, text, accent, progress bar, hover highlight
* Border radius and max-width controls
* Native HTML5 audio — no external libraries, no third-party services
* Works with whatever audio formats the visitor's browser supports (MP3, AAC, OGG, WAV)
* Built-in media library picker buttons in the editor for both audio files and artwork

= You bring your own audio =

Tracks are stored in your WordPress Media Library (or any reachable URL). The plugin just references them and plays them in the browser. No data leaves the visitor's device — there are no analytics, no telemetry, and no third-party scripts.

= Requires Beaver Builder =

This module requires the Beaver Builder page builder (Lite or Pro). The free [Beaver Builder Lite](https://wordpress.org/plugins/beaver-builder-lite-version/) version is sufficient. If Beaver Builder is not installed or activated, the module simply won't appear in the editor and an admin notice will be shown.

== Installation ==

1. Install Beaver Builder (Lite or Pro) and activate it.
2. Upload this plugin through the **Plugins → Add New** screen, or upload the ZIP via **Plugins → Add New → Upload Plugin**.
3. Activate the plugin through the **Plugins** screen.
4. Edit any page with Beaver Builder. Open the modules panel, scroll to the **Media** group, and drag in the **MP3 Player** module.
5. In the module's **Tracks** section, add one track at a time: paste an audio URL or click **Browse Audio Files** to pick from the media library. Optionally add a title, artist, and artwork URL.
6. Tweak playback (initial volume, autoplay) and styling (colors, border radius, max width) in the other tabs.
7. Save the page and view it on the frontend.

== Frequently Asked Questions ==

= Do I need Beaver Builder to use this? =

Yes. This plugin adds a module to the Beaver Builder page editor. Without Beaver Builder installed and active, the module has nothing to attach to. The free [Beaver Builder Lite](https://wordpress.org/plugins/beaver-builder-lite-version/) version is sufficient.

= What audio formats are supported? =

Whatever the visitor's browser supports through the native HTML5 `<audio>` element. In practice that's MP3, AAC, OGG, and WAV across modern browsers. The plugin doesn't transcode — you serve whatever file you upload.

= Where are tracks stored? =

Wherever you put them. The module just stores URLs. Most people upload audio files to their WordPress Media Library and use those URLs, but any publicly reachable URL works.

= Does autoplay work? =

It tries. The plugin will call `audio.play()` on page load if autoplay is enabled, but most browsers block autoplay with sound unless the user has interacted with the page first. If the browser blocks it, the player just sits ready and waits for the user to click play — no error is shown.

= Can I have multiple players on one page? =

Yes. Each module instance is fully independent — its own tracks, its own playback state, its own styling. They don't interfere with each other.

= Does the player track listening data or report anything? =

No. There are no analytics calls, no third-party scripts, no telemetry. The browser plays the file directly from wherever the URL points.

= Will this slow down my pages? =

The module enqueues a small per-instance CSS + JS pair only on pages that contain the module. The total weight per instance is on the order of a few kilobytes; the audio file itself is fetched only when the user starts playback (preload is set to `metadata` so only the duration is fetched up front).

= Where can I report bugs or request features? =

Open an issue at the GitHub repository: [https://github.com/Dependent-Media/dm-audio-playlist](https://github.com/Dependent-Media/dm-audio-playlist)

== Changelog ==

= 2.1.2 =
* Fixes the play/pause icon disappearing when a light Accent Color (e.g. white) is chosen. The icon was hard-coded white on the accent-colored button; it now switches automatically to the player background color — or near-black if the background is light too — whenever the accent is light.
* New **Style → Colors → Play Icon Color** setting to choose the icon color yourself. Leave it blank for the automatic choice.

= 2.1.1 =
* Fixes the lightbox close button drifting out of the top-right corner. Themes commonly restyle bare `button:hover` / `:focus` / `:active` — the Beaver Builder theme skin sets a blue background, a border, and `position: relative` on those states — and `button:focus` (specificity 0,1,1) outranked the plugin's lone `.dmap-lightbox-close` class (0,1,0). Because the script focuses the close button when the lightbox opens, it immediately picked up `position: relative` and moved into the centered flex column above the artwork, only snapping back to the corner once it lost focus on the way out. All lightbox rules are now scoped one level deeper (`.dmap-lightbox .dmap-lightbox-close`), and the close button restates its position, border, and colors for the hover, focus, and active states.

= 2.1.0 =
* Adds an artwork lightbox: clicking the now-playing artwork opens it full size over a dimmed backdrop, with the track title and artist beneath it. Previously the artwork was purely decorative and clicking it did nothing.
* The lightbox loads the *original* upload rather than the resized copy stored in the track settings. Artwork picked from the media library is frequently an intermediate size (e.g. `cover-300x300.jpg`), which is right for the 80px thumbnail but far too small to enlarge. The size suffix is stripped to recover the original, but only for URLs inside this site's uploads directory and only when that file actually exists on disk — otherwise the stored URL is used unchanged. `-scaled` images are left alone.
* The lightbox image is only fetched when the lightbox is opened, so pages carry no extra weight up front.
* Artwork is now a real `button` when the lightbox is enabled: keyboard focusable, activates with Enter/Space, closes with Escape, the backdrop, or the close button, and returns focus to the artwork afterwards. It is automatically disabled on tracks that have no artwork.
* The overlay is appended to `body` so builder rows with `overflow: hidden`, a transform, or their own stacking context cannot clip it.
* An open lightbox follows playback — when a track change swaps the artwork, the enlarged image and caption update with it, and it closes if the incoming track has no artwork.
* New **Tracks → Artwork → Click Artwork to Enlarge** setting (default On) to turn the behavior off. Existing layouts saved before this release get the lightbox without needing to be re-saved.

= 2.0.2 =
* First public release on WordPress.org.
* Renamed the plugin from "BB MP3 Player" to "Dependent Media Audio Playlist for Beaver Builder" to comply with WordPress.org Plugin Directory naming guidance (third-party plugins cannot use a brand name as a prefix in a way that implies official affiliation).
* Updated the text domain to `dependent-media-audio-playlist-for-beaver-builder`.
* Fixes a CSS-injection issue in the per-instance frontend styles: color values from the Beaver Builder color picker are now passed through a strict allowlist sanitizer (`dm_audio_playlist_sanitize_color`) that accepts only valid `#hex`, `hex`, `rgb()`, and `rgba()` patterns; everything else falls back to the per-field default.
* Removes the duplicate inline `<style>` block that was being emitted from `frontend.php` in addition to the framework-rendered `frontend.css.php`. Styles are now rendered through a single source.
* Refactors the Beaver Builder editor's media-picker injection script: the inline `<script>` previously echoed into `wp_footer` is now an enqueued JavaScript file (`js/editor.js`) loaded only when the BB editor is active and the user has `edit_posts`.
* Adds the GPLv2 `License`, `License URI`, `Requires at least`, and `Requires PHP` plugin headers required by the WordPress.org Plugin Directory.
* Backwards-compatibility shim for sites that ran the prior internal 1.x "BB MP3 Player" builds: the `BBMp3PlayerModule` PHP class is registered as an alias of `DM_Audio_Playlist_Module`, and both the module and its track settings form are registered under their old and new names. Existing Beaver Builder layouts that reference the old class continue to render after upgrade with all settings preserved (track URLs, titles, artwork, colors, volume, autoplay).
* Drops the `Requires Plugins: beaver-builder-lite-version` header. Beaver Builder Pro has a different slug than Beaver Builder Lite, so that header would block users on Pro from activating the plugin even though Pro fully satisfies the actual dependency. The plugin still checks for `FLBuilder` at runtime and shows an admin notice if neither Lite nor Pro is active.

== Upgrade Notice ==

= 2.1.2 =
The play/pause icon stays visible on light accent colors. Optional new Play Icon Color setting.

= 2.1.1 =
Fixes the lightbox close button sitting above the artwork instead of in the top-right corner on themes that restyle `button:focus` (including the Beaver Builder theme).

= 2.1.0 =
Clicking the now-playing artwork now opens it full size in a lightbox. Existing players pick this up automatically — no need to re-save your layouts. Turn it off under Tracks → Artwork if you'd rather the artwork stayed decorative.

= 2.0.2 =
First public WordPress.org release. If you were running a prior internal 1.x "BB MP3 Player" build, your existing module instances and their settings will continue to work after upgrade — no manual migration needed. Works with both Beaver Builder Lite and Beaver Builder Pro.
