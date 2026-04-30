# Dependent Media Audio Playlist for Beaver Builder

A Beaver Builder module that adds a customizable audio playlist player with shuffle, repeat, artwork, and full playback controls.

This is a WordPress plugin. For user-facing install and usage docs, see [readme.txt](readme.txt).

The repo directory is `dm-audio-playlist/` for brevity. The WordPress.org slug is `dependent-media-audio-playlist-for-beaver-builder`, which is what the plugin uses for its text domain and what the install path on a live site will be.

## Development

### Project layout

```
dm-audio-playlist.php             # bootstrap: headers, constants, module loader, editor enqueue
js/
  editor.js                       # media picker buttons injected into the BB editor
modules/
  mp3-player/
    mp3-player.php                # FLBuilderModule subclass + settings form registration
    includes/
      frontend.php                # rendered HTML for each player instance
      frontend.css.php            # rendered CSS per instance (color sanitization lives here)
      frontend.js.php             # rendered JS per instance (playback logic)
languages/
  index.php
```

### Beaver Builder dependency

The plugin requires Beaver Builder (Lite or Pro) to do anything useful. It checks `class_exists( 'FLBuilder' )` at runtime and shows an admin notice if BB isn't loaded. The `Requires Plugins:` header declares `beaver-builder-lite-version` since that's the only canonical wp.org slug for BB; users with the Pro version will still need to install/activate it (the runtime check accepts either).

### CSS color sanitization

Color values from BB's color picker are passed through `dm_audio_playlist_sanitize_color()` (defined in `frontend.css.php`) before being interpolated into CSS. The function accepts `#hex`, `hex`, `rgb()`, and `rgba()` patterns; anything else falls back to the per-field default. This is the only safe pattern for outputting user-controlled values into a `<style>` block — the WordPress core `sanitize_hex_color()` doesn't cover rgba.
