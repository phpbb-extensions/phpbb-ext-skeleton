(function($) {  // Avoid conflicts with other libraries

	'use strict';

	var authorTpl,
		$elem = {
			form: $('#postform'),
			author: $('.skeleton-author'),
			addAuthor: $('#skeleton-new-author'),
			components: $('.components'),
			marklist: $('.skeleton-marklist')
		};

	$(function() {
		authorTpl = $elem.author.first().clone();
	});

	// Add Authors button
	$elem.addAuthor.on('click', function() {
		var count = $elem.author.length,
			$author = authorTpl.clone();

		$author.find('label').each(function() {
			var $this = $(this);
			$this.attr('for', $this.attr('for') + count);
		});
		$author.find('input').each(function() {
			var $this = $(this);
			$this.attr('id', $this.attr('id') + count);
		});
		$(this).before($('<hr />')).before($author);
	});

	// Mark all / unmark all components
	$elem.marklist.on('click', function(e) {
		e.preventDefault();
		$elem.components.prop('checked', $(this).hasClass('markall'));
	});

	// Auto-mark components that are dependencies
	$elem.components.each(function() {
		const $component = $(this);
		const depends = $component.data('depends');

		if (!depends) {
			return;
		}

		const dependIds = depends.split(',').map(id => id.trim());

		$component.on('change', function() {
			if (this.checked) {
				dependIds.forEach(dependId => {
					const $dependency = $('#' + dependId);
					if ($dependency.length && !$dependency.prop('checked')) {
						$dependency.prop('checked', true);
					}
				});
			}
		});
	});

	// Validate vendor/extension names on field blur
	$elem.form.on('blur', '#vendor_name, #extension_name', function() {
		$(this).checkNames();
	});

	// Validate vendor/extension names on document ready
	$(function(){
		$('#vendor_name, #extension_name').checkNames();
	});

	/*global warningMsg */
	$.fn.checkNames = function() {
		return this.each(function() {
			var $value = $(this).val(),
				$warning = $('<div/>').css('color', 'red').text(warningMsg);

			$(this).next($warning).remove();

			if ($value === 'phpbb' || $value === 'core') {
				$(this).after($warning);
			}
		});
	};

})(jQuery); // Avoid conflicts with other libraries
