function retrieveImageFromClipboardAsBlob(pasteEvent, callback){
	if(pasteEvent.clipboardData == false){
        if(typeof(callback) == "function"){
            callback(undefined);
        }
    };

    var items = pasteEvent.clipboardData.items;

    if(items == undefined){
        if(typeof(callback) == "function"){
            callback(undefined);
        }
    };

    for (var i = 0; i < items.length; i++) {
        // Skip content if not image
        if (items[i].type.indexOf("image") == -1) continue;
        // Retrieve image on clipboard as blob
        var blob = items[i].getAsFile();

        if(typeof(callback) == "function"){
            callback(blob);
        }
    }
}

window.addEventListener("paste", function(e){

    // Handle the event
    retrieveImageFromClipboardAsBlob(e, function(imageBlob){
        // If there's an image, display it in the canvas
        if(imageBlob){
            var blobUrl = window.webkitURL.createObjectURL(imageBlob);
			$("#past-preview").attr("src",blobUrl);
			$(".past-fields").hide();
			$("#past-preview").show();
			
			
			 var reader = new FileReader();
			 reader.readAsDataURL(imageBlob); 
			 reader.onloadend = function() {
				 var base64data = reader.result;                
				 $("#cost_detail_img").val(base64data);
			 }
        }
    });
}, false);






