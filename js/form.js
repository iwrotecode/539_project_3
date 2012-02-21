/**
 * @author tuxedo
 */

$(document).ready(function(){
	// change the type to every form input that has a name of email
	$('form input[name="email"]').each(function(){
		// change the type to email
		this.setAttribute("type","email");
	});
	
	// check if the value for input exceeds what the input holds
	$('form input').focus(function(){
		// get the length of the input
		var inputWidth = $(this).width();
		
		var value = $(this).val();s
		
		// temporarily create a span to mesure the width
		var temp = document.createElement("span");
		// append the value to the width
		temp.appendChild(document.createTextNode(value)); 
		
		// append span to the body
		document.body.appendChild(temp);
		
		// grab the width - minus an offset 
		var valueWidth = $(temp).width()-27;
		
		// remove the span
		document.body.removeChild(temp);
	
		// check if the width is bigger than the input
		if(inputWidth < valueWidth){
			// do the lightbox 
			
			
		}
		
		console.log("iWidth: "+inputWidth+" | uWidth: "+valueWidth);
		
	});
	
});
