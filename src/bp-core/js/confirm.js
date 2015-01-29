/* jshint devel: true */
/* global BP_Confirm */

jQuery( document ).ready( function() {
	jQuery('a.confirm').not('.acomment-delete, .delete-activity').click( function() {
		if ( confirm( BP_Confirm.are_you_sure ) ) {
			return true;
		} else {
			return false;
		}
	});
});
