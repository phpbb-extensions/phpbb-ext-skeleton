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

	$elem.marklist.on('click', function(e) {
		e.preventDefault();
		$elem.components.prop('checked', $(this).hasClass('markall'));
	});

	// Validate vendor/extension names
	$elem.form.on('blur', '#vendor_name, #extension_name', function() {
		var $value = $(this).val(),
			$warning = $('<div/>').css('color', 'red').text(warningMsg);

		$(this).next($warning).remove();

		if ($value === 'phpbb' || $value === 'core') {
			$(this).after($warning);
		}
	});

})(jQuery); // Avoid conflicts with other libraries
