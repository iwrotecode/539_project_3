/**
 * @author tuxedo
 */

$(document).ready(function(){
	// change the type to every form input that has a name of email
	$('form input[name="email"]').each(function(){
		// change the type to email
		this.setAttribute("type","email");
	});
});
