/**
 * @author tuxedo
 */

$(document).ready(function(){
	// change the type to every form input that has a name of email
	$('form input[name="email"]').each(function(){
		// change the type to email
		this.setAttribute("type","email");
	});
	
	// build the div for the lightbox
	if($('form input')){
		// add the div for the lightbox
		var lightBox = document.createElement("div");
		lightBox.setAttribute("style","display:none");
		lightBox.setAttribute("id","lightboxDiv");
		
		var data = document.createElement("div");
		data.setAttribute("id","data");
		
		var text = document.createElement("textarea");
		
		data.appendChild(text);
		
		lightBox.appendChild(data);
		
		document.body.appendChild(lightBox);
	}
	
	// check if the value for input exceeds what the input holds
	$('form input').focus(function(){
		// get the length of the input
		var inputWidth = $(this).width();
		
		var value = $(this).val();
		
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
			$("#lightboxDiv").fancybox();	
		}
		
		console.log("iWidth: "+inputWidth+" | uWidth: "+valueWidth);
		
	});
	
});
