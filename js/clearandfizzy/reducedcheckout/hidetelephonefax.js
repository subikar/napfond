/**
 * 
 * @author Gareth Price gareth@clearandfizzy.com
 */


var clearandfizzy_reducedcheckout_hidetelephonefax = {
		
	/* Hide the form elements and default to 0 (zero) to prevent any problems with validation on the admin panel  */
	init: function() {
		this.hideField('telephone');
		this.hideField('billing\:telephone');
		this.hideField('shipping\:telephone');
	}, // end 

	hideField: function(name) {
		
		if ( $(name) ) {
			$(name).up('li').hide();
			
			if ( $(name).value == '') {
				$(name).value = '00';
			} // end if
		} // end if		
		
	},
	
	
};

document.observe("dom:loaded", function() {
	clearandfizzy_reducedcheckout_hidetelephonefax.init();
}); // end 