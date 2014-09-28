$(document).ready(function() {
    var droppedDown = false;
    var fileType = true;
	var converting = false;
	
	var fileTypes = ["'wmv'", "'m4v'", "'mp4'", "'mp3'"];

		
    if(typeof FileActions !== 'undefined') {
        var infoIconPath = OC.imagePath('convert','convert.svg');

        FileActions.register('file', 'Convert', OC.PERMISSION_UPDATE, infoIconPath, function(fileName) {			
            if(scanFiles.scanning) { return; } 

            var directory = $('#dir').val();
            directory = (directory === "/") ? directory : directory + "/";

            var filePath = directory + fileName; 
            var message = t('convert', "Select file type:");

			//Build dropdown
			var html =  "<div id='dropdown' class='drop'>";
			html += "<p id='message'>" + message + "</p>" +
					"<div id='submit'>" +
					"<select id='fileType'>";
	    			for (var i = 0 ; i < fileTypes.length ; i++) {
	    				html += "<option value=" + fileTypes[i] + ">" + fileTypes[i] + "</option>";
	    		}
	    	html += "</select>" +
                    "<input id='execute' type='button'" +
					"value='Convert'/>" +
                    "</div>" +
					"</div>";

            if(fileName) {
                $('tr').filterAttr('data-file',fileName).addClass('mouseOver');
                $(html).appendTo($('tr').filterAttr('data-file', fileName).find('td.filename'));
            }

            $('#dropdown').show('blind');
            $('#convert_sel').chosen();
            $('#execute').bind('click',function() {
                if(fileType) {
                    var type = $('#fileType').val();
					var outPath = filePath.substr(0, filePath.lastIndexOf(".")) + "." + type;
                    doConvert(filePath, outPath);
                }
            });
            
            $('#fileType').bind('keyup', function(eventData) {
                var type = $('#fileType').val();
				var outPath = filePath.substr(0, filePath.lastIndexOf(".")) + "." + type;

                    fileType = true;
                    
                    if(eventData.keyCode == 13) {
                        doConvert(filePath, outPath);
                    }                    
                
            });
        });
        
        droppedDown = true;
    }

    $(document).on('click', function(event) {
        var target = $(event.target);
        var clickOut = !(target.is('#fileType') || target.is('#execute'));

        if(droppedDown && clickOut && !converting) {
            hideDropDown();
        }
    });
	
	function getProgress(filePath) {
		$.get(OC.linkTo('convert', 'ajax/getprogress.php'), {filePath : filePath}, function( result ) {
			if (result == -1) {
				alert("Unable to read log file. No progress information available.");
				document.location.reload();
			}
			else if (result >= 100) {
				alert("Conversion complete.");
				document.location.reload();
			}
			else {
				updateProgress(result);
				setTimeout(getProgress(), 1000);
			}
		});
	}
	
	
	function updateProgress(result) {
		var progress = result;
		progress += "%";
		$('#submit').html('<div style="margin-top: 5px;"><img src="' + OC.imagePath('convert','russell.gif') + '"/><div id = "progressBar" style="margin-left:5px;">' + progress + '</div></div>'); 
	}
	
    function doConvert(filePath, outPath) {
		converting = true;
		
		$.ajax({
            type    : 'POST',
            url     : OC.linkTo('convert', 'ajax/convert.php'),
            timeout : 0,
			async   : false,
            data    : {
                filePath    : filePath,
                outPath     : outPath	
			}
        }); 
		
		getProgress();
    }
    	
    function hideDropDown() {
        $('#dropdown').hide('blind',function(){
            $('#dropdown').remove();
            $('tr').removeClass('mouseOver');
        });
    }        
});
