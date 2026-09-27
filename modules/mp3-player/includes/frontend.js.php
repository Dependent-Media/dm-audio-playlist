<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>
(function () {
	var wrapper = document.getElementById('<?php echo esc_js( 'dmap-' . $id ); ?>');
	if (!wrapper) return;

	var audio        = wrapper.querySelector('.dmap-audio');
	var trackEls     = wrapper.querySelectorAll('.dmap-track');
	var playBtn      = wrapper.querySelector('.dmap-play');
	var prevBtn      = wrapper.querySelector('.dmap-prev');
	var nextBtn      = wrapper.querySelector('.dmap-next');
	var shuffleBtn   = wrapper.querySelector('.dmap-shuffle');
	var repeatBtn    = wrapper.querySelector('.dmap-repeat');
	var repeatBadge  = wrapper.querySelector('.dmap-repeat-badge');
	var progressBar  = wrapper.querySelector('.dmap-progress-bar');
	var progressFill = wrapper.querySelector('.dmap-progress-fill');
	var timeCurrent  = wrapper.querySelector('.dmap-time-current');
	var timeDuration = wrapper.querySelector('.dmap-time-duration');
	var volumeSlider = wrapper.querySelector('.dmap-volume');
	var nowTitle     = wrapper.querySelector('.dmap-track-title');
	var nowArtist    = wrapper.querySelector('.dmap-track-artist');
	var nowArtWrap   = wrapper.querySelector('.dmap-now-art-wrap');
	var defaultArtSvg = nowArtWrap ? nowArtWrap.innerHTML : '';
	var iconPlay     = wrapper.querySelector('.dmap-icon-play');
	var iconPause    = wrapper.querySelector('.dmap-icon-pause');

	var current      = -1;
	var shuffle      = false;
	var repeat       = 0;
	var tracks       = [];
	var shuffleOrder = [];

	trackEls.forEach(function (el) {
		tracks.push({
			src:         el.getAttribute('data-src'),
			title:       el.getAttribute('data-title'),
			artist:      el.getAttribute('data-artist'),
			artwork:     el.getAttribute('data-artwork'),
			artworkFull: el.getAttribute('data-artwork-full')
		});
	});

	if (tracks.length === 0) return;

	/* ---------------------------------------------------------------
	 * Artwork lightbox
	 * ------------------------------------------------------------- */

	var lightboxOn    = wrapper.getAttribute('data-lightbox') === 'true';
	var lb            = null;
	var lbImg, lbTitle, lbArtist, lbClose;
	var lbReturnFocus = null;
	var htmlOverflow  = '';
	var bodyOverflow  = '';

	function buildLightbox() {
		lb = document.createElement('div');
		lb.className = 'dmap-lightbox';
		lb.setAttribute('role', 'dialog');
		lb.setAttribute('aria-modal', 'true');
		lb.setAttribute('aria-label', '<?php echo esc_js( __( 'Track artwork', 'dependent-media-audio-playlist-for-beaver-builder' ) ); ?>');
		lb.hidden = true;

		lbClose = document.createElement('button');
		lbClose.type = 'button';
		lbClose.className = 'dmap-lightbox-close';
		lbClose.setAttribute('aria-label', '<?php echo esc_js( __( 'Close', 'dependent-media-audio-playlist-for-beaver-builder' ) ); ?>');
		lbClose.innerHTML = '<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>';

		var fig = document.createElement('figure');
		fig.className = 'dmap-lightbox-figure';

		lbImg = document.createElement('img');
		lbImg.className = 'dmap-lightbox-img';
		lbImg.alt = '';

		var cap = document.createElement('figcaption');
		cap.className = 'dmap-lightbox-caption';

		lbTitle = document.createElement('span');
		lbTitle.className = 'dmap-lightbox-title';
		lbArtist = document.createElement('span');
		lbArtist.className = 'dmap-lightbox-artist';

		cap.appendChild(lbTitle);
		cap.appendChild(lbArtist);
		fig.appendChild(lbImg);
		fig.appendChild(cap);
		lb.appendChild(lbClose);
		lb.appendChild(fig);

		// Body-level so builder rows with overflow/transform can't clip it.
		document.body.appendChild(lb);

		lbClose.addEventListener('click', closeLightbox);
		lb.addEventListener('click', function (e) {
			// Backdrop only — clicking the artwork itself shouldn't dismiss it.
			if (e.target === lb) closeLightbox();
		});
	}

	// Keeps an open lightbox in step when playback rolls to the next track.
	function syncLightbox() {
		if (!lb || current === -1) return;
		var t = tracks[current];
		if (!t || !t.artwork) return;

		var src = t.artworkFull || t.artwork;
		if (lbImg.getAttribute('src') !== src) lbImg.setAttribute('src', src);
		lbImg.alt = t.title
			? '<?php echo esc_js( __( 'Artwork for %s', 'dependent-media-audio-playlist-for-beaver-builder' ) ); ?>'.replace('%s', t.title)
			: '';
		lbTitle.textContent = t.title || '';
		lbArtist.textContent = t.artist || '';
	}

	function openLightbox() {
		if (!lightboxOn || current === -1) return;
		if (!tracks[current] || !tracks[current].artwork) return;

		if (!lb) buildLightbox();
		syncLightbox();

		lbReturnFocus = document.activeElement;

		htmlOverflow = document.documentElement.style.overflow;
		bodyOverflow = document.body.style.overflow;
		document.documentElement.style.overflow = 'hidden';
		document.body.style.overflow = 'hidden';

		lb.hidden = false;
		void lb.offsetWidth; // Flush layout so the fade-in starts from 0.
		lb.classList.add('is-open');

		document.addEventListener('keydown', onLightboxKeydown, true);
		lbClose.focus();
	}

	function closeLightbox() {
		if (!lb || lb.hidden) return;

		lb.classList.remove('is-open');
		document.removeEventListener('keydown', onLightboxKeydown, true);
		document.documentElement.style.overflow = htmlOverflow;
		document.body.style.overflow = bodyOverflow;

		var finish = function (e) {
			if (e && e.target !== lb) return;      // Ignore descendants' transitions.
			if (lb.classList.contains('is-open')) return; // Reopened mid-fade.
			lb.hidden = true;
			lb.removeEventListener('transitionend', finish);
		};
		lb.addEventListener('transitionend', finish);
		setTimeout(function () { finish(); }, 300); // Fallback if no transition runs.

		if (lbReturnFocus && typeof lbReturnFocus.focus === 'function') {
			lbReturnFocus.focus();
		}
		lbReturnFocus = null;
	}

	function onLightboxKeydown(e) {
		if (e.key === 'Escape' || e.key === 'Esc') {
			e.preventDefault();
			closeLightbox();
			return;
		}
		// Close is the only focusable control in here, so trapping is trivial.
		if (e.key === 'Tab') {
			e.preventDefault();
			lbClose.focus();
		}
	}

	if (lightboxOn && nowArtWrap) {
		nowArtWrap.addEventListener('click', function (e) {
			e.preventDefault();
			openLightbox();
		});
	}

	function formatTime(s) {
		if (isNaN(s)) return '0:00';
		var m = Math.floor(s / 60);
		var sec = Math.floor(s % 60);
		return m + ':' + (sec < 10 ? '0' : '') + sec;
	}

	function generateShuffleOrder() {
		shuffleOrder = [];
		for (var i = 0; i < tracks.length; i++) shuffleOrder.push(i);
		for (var k = shuffleOrder.length - 1; k > 0; k--) {
			var j = Math.floor(Math.random() * (k + 1));
			var tmp = shuffleOrder[k];
			shuffleOrder[k] = shuffleOrder[j];
			shuffleOrder[j] = tmp;
		}
	}

	function setActiveTrack(idx) {
		trackEls.forEach(function (el) { el.classList.remove('active'); });
		if (trackEls[idx]) trackEls[idx].classList.add('active');
	}

	function updateArtwork(artwork) {
		if (!nowArtWrap) return;
		while (nowArtWrap.firstChild) nowArtWrap.removeChild(nowArtWrap.firstChild);
		if (artwork) {
			var img = document.createElement('img');
			img.className = 'dmap-artwork';
			img.alt = '';
			img.src = artwork;
			nowArtWrap.appendChild(img);
			nowArtWrap.classList.remove('dmap-artwork-placeholder');
		} else {
			nowArtWrap.classList.add('dmap-artwork-placeholder');
			var temp = document.createElement('div');
			temp.innerHTML = defaultArtSvg;
			while (temp.firstChild) nowArtWrap.appendChild(temp.firstChild);
		}

		if (!lightboxOn) return;

		// Only offer the zoom on tracks that actually have artwork.
		if ('disabled' in nowArtWrap) nowArtWrap.disabled = !artwork;

		if (artwork) {
			syncLightbox();
		} else {
			closeLightbox();
		}
	}

	function loadTrack(idx) {
		if (idx < 0 || idx >= tracks.length) return;
		current = idx;
		audio.src = tracks[idx].src;
		nowTitle.textContent = tracks[idx].title;
		nowArtist.textContent = tracks[idx].artist;
		updateArtwork(tracks[idx].artwork);
		setActiveTrack(idx);
		progressFill.style.width = '0%';
		timeCurrent.textContent = '0:00';
		timeDuration.textContent = '0:00';
	}

	function playTrack(idx) {
		loadTrack(idx);
		audio.play();
	}

	function togglePlay() {
		if (current === -1) {
			playTrack(0);
			return;
		}
		if (audio.paused) {
			audio.play();
		} else {
			audio.pause();
		}
	}

	function getNextIndex() {
		if (repeat === 2) return current;
		if (shuffle) {
			var pos = shuffleOrder.indexOf(current);
			var next = pos + 1;
			if (next >= shuffleOrder.length) {
				if (repeat === 1) {
					generateShuffleOrder();
					return shuffleOrder[0];
				}
				return -1;
			}
			return shuffleOrder[next];
		}
		var nextSeq = current + 1;
		if (nextSeq >= tracks.length) {
			return repeat === 1 ? 0 : -1;
		}
		return nextSeq;
	}

	function getPrevIndex() {
		if (shuffle) {
			var pos = shuffleOrder.indexOf(current);
			return pos > 0 ? shuffleOrder[pos - 1] : shuffleOrder[shuffleOrder.length - 1];
		}
		return current > 0 ? current - 1 : tracks.length - 1;
	}

	playBtn.addEventListener('click', togglePlay);

	nextBtn.addEventListener('click', function () {
		var idx = getNextIndex();
		if (idx === -1) idx = 0;
		playTrack(idx);
	});

	prevBtn.addEventListener('click', function () {
		if (audio.currentTime > 3) {
			audio.currentTime = 0;
			return;
		}
		playTrack(getPrevIndex());
	});

	shuffleBtn.addEventListener('click', function () {
		shuffle = !shuffle;
		shuffleBtn.classList.toggle('active', shuffle);
		if (shuffle) generateShuffleOrder();
	});

	repeatBtn.addEventListener('click', function () {
		repeat = (repeat + 1) % 3;
		repeatBtn.classList.toggle('active', repeat > 0);
		repeatBadge.style.display = (repeat === 2) ? '' : 'none';
	});

	trackEls.forEach(function (el, i) {
		el.addEventListener('click', function () {
			playTrack(i);
		});
	});

	audio.addEventListener('play', function () {
		iconPlay.style.display = 'none';
		iconPause.style.display = '';
	});

	audio.addEventListener('pause', function () {
		iconPlay.style.display = '';
		iconPause.style.display = 'none';
	});

	audio.addEventListener('timeupdate', function () {
		if (audio.duration) {
			progressFill.style.width = (audio.currentTime / audio.duration) * 100 + '%';
			timeCurrent.textContent = formatTime(audio.currentTime);
		}
	});

	audio.addEventListener('loadedmetadata', function () {
		timeDuration.textContent = formatTime(audio.duration);
	});

	audio.addEventListener('ended', function () {
		var idx = getNextIndex();
		if (idx === -1) {
			iconPlay.style.display = '';
			iconPause.style.display = 'none';
			return;
		}
		playTrack(idx);
	});

	progressBar.addEventListener('click', function (e) {
		var rect = progressBar.getBoundingClientRect();
		var pct = (e.clientX - rect.left) / rect.width;
		if (audio.duration) {
			audio.currentTime = pct * audio.duration;
		}
	});

	volumeSlider.addEventListener('input', function () {
		audio.volume = this.value;
	});

	// Initial volume from settings.
	var initVol = parseInt(wrapper.getAttribute('data-initial-volume'), 10);
	if (isNaN(initVol)) initVol = 80;
	initVol = Math.min(100, Math.max(0, initVol)) / 100;
	audio.volume = initVol;
	volumeSlider.value = initVol;

	generateShuffleOrder();

	// Autoplay from settings (browser may block).
	if (wrapper.getAttribute('data-autoplay') === 'true' && tracks.length > 0) {
		loadTrack(0);
		var playPromise = audio.play();
		if (playPromise !== undefined) {
			playPromise.catch(function () {
				// Browser blocked autoplay — fail silently, user can click play.
			});
		}
	}
})();
