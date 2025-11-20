/*
 * This file is part of the t3monitoring extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

$(function () {
  $('.dependency-toggle-js').click(function () {
    $('.dependency-info-js').hide();

    if (!$(this).hasClass('active')) {
      $('#' + $(this).attr('data-toggle')).toggle();
    }

    $(this).toggleClass('active');
  });

  new DataTable('.client-list',
    {
      "order": [[0, "desc"]],
      paging: false,
      lengthChange: false,
      stateSave: true,
      searching: false,
      dom: 'tir',
      ordering: true
    }
  );

  new DataTable('.extension-list',
    {
      "order": [[5, "asc"], [6, "asc"]],
      paging: false,
      lengthChange: false,
      stateSave: false,
      searching: false,
      dom: 'tir',
      ordering: true
    }
  );
});
