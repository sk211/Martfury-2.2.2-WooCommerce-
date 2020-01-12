/**
 * jQuery Tabs plugin 1.0.0
 *
 * @author Drfuri
 */
(function($) {
	$.fn.mrtabs = function() {
		return this.each(function() {
			var $element = $(this),
				$nav = $element.find('.tabs-nav'),
				$tabs = $nav.find('a'),
				$panels = $element.find('.tabs-panel');


			$tabs.filter(':first').addClass('active');
			$nav.find('li').filter(':first').addClass('active');
			$panels.filter(':first').addClass('active');

			$tabs.on('click', function(e) {
				e.preventDefault();

				var $tab = $(this),
					index = $tab.parent().index(),
					$panels = $element.find('.tabs-panel');

				if ( $tab.hasClass('active') ) {
					return;
				}

				$tabs.removeClass('active');
				$tab.addClass('active');
				$nav.find('li').removeClass('active');
				$tab.closest('li').addClass('active');
				$panels.removeClass('active');
				$panels.filter(':eq(' + index + ')').addClass('active');

                $(document.body).trigger('martfury_after_tab_clicked');

			});
		});
	};

	/* Init tabs */
	$(function() {
		$('.martfury-tabs').mrtabs();

		$(document.body).on('martfury_get_tabs_ajax_success', function() {
			$('.martfury-tabs').mrtabs();
		});
	});
})(jQuery);