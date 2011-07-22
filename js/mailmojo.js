/*  Copyright 2011  Eliksir AS  (email : post@e5r.no)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

/**
 * jQuery function hooked on the submit event for the MailMojo widget.
 * Makes the submit button send an ajax request and displays success/error
 * messages.
 */
jQuery(document).ready(function () {
	var noticeHtml = '<div class="notice"><strong></strong><br/><a href="#">' +  MailMojoWidget.linkText + '</a></div>';

	jQuery('form.mailmojo_form').each(function () {
		var
			$container = jQuery(this).parent().append(noticeHtml),
			$loader = $container.find('.loader'),
			$notice = $container.find('.notice');

		jQuery(this).submit(function (e) {
			var $this = jQuery(this);

			e.preventDefault();

			$loader.show();

			function setMessage (msg, success) {
				$notice.show();
				$notice.find('strong').html(msg);
				if (success) {
					$notice
						.removeClass('error')
						.find('a')
							.click(function (e) {
								e.preventDefault();
								$notice.hide();
								$container.find('form').slideDown();
							})
							.show();
				} else {
					$notice
						.addClass('error')
						.find('a').hide();
				}
			}

			jQuery.post('/', $this.serializeArray(), function (data, resStatus) {
				if (resStatus == 'success') {
					$loader.hide();
					if (data.success) {
						$container
							.find('form').slideUp()
							.find('input.text')
								.val('')
								.end()
							.find('input:checked').attr('checked', '');
						setMessage(data.msg, true);
					}
					else {
						setMessage(data.msg, false);
					}
				}
			});
		});
	});
});