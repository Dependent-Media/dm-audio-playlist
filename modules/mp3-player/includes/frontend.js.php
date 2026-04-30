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
			src:     el.getAttribute('data-src'),
			title:   el.getAttribute('data-title'),
			artist:  el.getAttribute('data-artist'),
			artwork: el.getAttribute('data-artwork')
		});
	});

	if (tracks.length === 0) return;

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
			nowArtWrap.className = 'dmap-now-art-wrap';
		} else {
			nowArtWrap.className = 'dmap-artwork-placeholder dmap-now-art-wrap';
			var temp = document.createElement('div');
			temp.innerHTML = defaultArtSvg;
			while (temp.firstChild) nowArtWrap.appendChild(temp.firstChild);
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
