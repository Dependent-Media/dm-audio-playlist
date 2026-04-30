/**
 * Beaver Builder editor enhancements for the Dependent Media Audio Playlist module.
 *
 * Injects "Browse Audio Files" and "Browse Images" buttons next to the
 * audio_url and artwork_url text inputs in the BB module settings, opening
 * the WordPress media library frame so the user can pick from existing
 * uploads instead of pasting URLs by hand.
 *
 * Loaded only when the BB editor is active and the user can edit_posts.
 */
( function () {
	function addPickerButtons() {
		var audioInputs = document.querySelectorAll( 'input[name="audio_url"]' );
		for ( var i = 0; i < audioInputs.length; i++ ) {
			var input = audioInputs[ i ];
			if ( input.getAttribute( 'data-picker-added' ) ) continue;
			input.setAttribute( 'data-picker-added', '1' );

			var btn = document.createElement( 'button' );
			btn.type = 'button';
			btn.textContent = 'Browse Audio Files';
			btn.style.cssText = 'display:block;margin-top:8px;padding:6px 14px;font-size:13px;cursor:pointer;background:#0073aa;color:#fff;border:none;border-radius:3px;';
			( function ( theInput ) {
				btn.addEventListener( 'click', function ( e ) {
					e.preventDefault();
					e.stopPropagation();
					var frame = wp.media( {
						title: 'Select Audio File',
						library: { type: 'audio' },
						multiple: false,
					} );
					frame.on( 'select', function () {
						var a = frame.state().get( 'selection' ).first().toJSON();
						theInput.value = a.url;
						jQuery( theInput ).trigger( 'change' ).trigger( 'input' );
					} );
					frame.open();
				} );
			} )( input );
			input.parentNode.appendChild( btn );
		}

		var artInputs = document.querySelectorAll( 'input[name="artwork_url"]' );
		for ( var j = 0; j < artInputs.length; j++ ) {
			var artInput = artInputs[ j ];
			if ( artInput.getAttribute( 'data-picker-added' ) ) continue;
			artInput.setAttribute( 'data-picker-added', '1' );

			var artBtn = document.createElement( 'button' );
			artBtn.type = 'button';
			artBtn.textContent = 'Browse Images';
			artBtn.style.cssText = 'display:block;margin-top:8px;padding:6px 14px;font-size:13px;cursor:pointer;background:#0073aa;color:#fff;border:none;border-radius:3px;';
			( function ( theInput ) {
				artBtn.addEventListener( 'click', function ( e ) {
					e.preventDefault();
					e.stopPropagation();
					var frame = wp.media( {
						title: 'Select Artwork Image',
						library: { type: 'image' },
						multiple: false,
					} );
					frame.on( 'select', function () {
						var a = frame.state().get( 'selection' ).first().toJSON();
						var url = a.sizes && a.sizes.medium ? a.sizes.medium.url : a.url;
						theInput.value = url;
						jQuery( theInput ).trigger( 'change' ).trigger( 'input' );
					} );
					frame.open();
				} );
			} )( artInput );
			artInput.parentNode.appendChild( artBtn );
		}
	}

	var observer = new MutationObserver( function () {
		setTimeout( addPickerButtons, 300 );
	} );
	observer.observe( document.body, { childList: true, subtree: true } );
	setInterval( addPickerButtons, 2000 );
} )();
