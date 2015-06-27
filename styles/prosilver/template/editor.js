(function($) {  // Avoid conflicts with other libraries

	'use strict';

	var authorTpl;

	$(function() {
		authorTpl = $('.skeleton-author').clone();
	});

	$('#skeleton-new-author').click(function() {
		var count = $('.skeleton-author').length,
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

})(jQuery); // Avoid conflicts with other libraries
