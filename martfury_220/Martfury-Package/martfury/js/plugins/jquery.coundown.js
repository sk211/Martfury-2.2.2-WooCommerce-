/**
 * jQuery Tabs plugin 1.0.0
 *
 * @author Drfuri
 */
(function($) {
	$.fn.mf_countdown = function() {
		return this.each(function() {
			var $this = $(this),
				diff = $this.data('expire');

			var updateClock = function(distance) {
				var days = Math.floor(distance / (60 * 60 * 24));
				var hours = Math.floor((distance % (60 * 60 * 24)) / (60 * 60));
				var minutes = Math.floor((distance % (60 * 60)) / (60));
				var seconds = Math.floor(distance % 60);

				$this.html(
					'<span class="days timer"><span class="digits">' + (days < 10 ? '0' : '') + days + '</span><span class="text">' + martfuryData.days + '</span></span>' +
					'<span class="divider">:</span>' +
					'<span class="hours timer"><span class="digits">' + (hours < 10 ? '0' : '') + hours + '</span><span class="text">' + martfuryData.hours + '</span></span>' +
					'<span class="divider">:</span>' +
					'<span class="minutes timer"><span class="digits">' + (minutes < 10 ? '0' : '') + minutes + '</span><span class="text">' + martfuryData.minutes + '</span></span>' +
					'<span class="divider">:</span>' +
					'<span class="seconds timer"><span class="digits">' + (seconds < 10 ? '0' : '') + seconds + '</span><span class="text">' + martfuryData.seconds + '</span></span>'
				);
			};

			updateClock(diff);

			var countdown = setInterval(function() {
				diff = diff - 1;

				updateClock(diff);

				if ( diff < 0 ) {
					clearInterval(countdown);
				}
			}, 1000);
		});
	};

	/* Init tabs */
	$(function() {
		$('.martfury-countdown').mf_countdown();
	});
})(jQuery);