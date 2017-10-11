/*
 * This file is part of the t3monitoring extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

define([
	'jquery',
	'TYPO3/CMS/Backend/Modal',
	'TYPO3/CMS/Backend/Tooltip',
	'datatables'
], function ($, Modal, Tooltip) {
	'use strict';

	$(document).ready(function () {
		var clientList = $('.client-list');
		if (clientList.length > 0) {
			clientList.DataTable({
				"order": [[0, "desc"]],
				paging: false,
				lengthChange: false,
				stateSave: true,
				searching: false,
				dom: 'tir',
				ordering: true
			});
		}
		var extensionList = $('.extension-list');
		if (extensionList.length > 0) {
				extensionList.DataTable({
				"order": [[5, "asc"], [6, "asc"]],
				paging: false,
				lengthChange: false,
				stateSave: false,
				searching: false,
				dom: 'tir',
				ordering: true
			});
		}
		var userList = $('.user-list');
		if (userList.length > 0) {
			userList.DataTable({
				"order": [0, "desc"],
				paging: false,
				lengthChange: false,
				stateSave: false,
				searching: false,
				dom: 'tir',
				ordering: true
			});
		}
	});

});
