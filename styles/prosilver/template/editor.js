(function($) {  // Avoid conflicts with other libraries

	'use strict';

	var authorTpl,
		$elem = {
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

})(jQuery); // Avoid conflicts with other libraries
