$(document).ready(function() {
    var droppedDown = false;
    var fileType = false;
		
    if(typeof FileActions !== 'undefined') {
        var infoIconPath = OC.imagePath('convert','convert.svg');

        FileActions.register('file', 'Convert', OC.PERMISSION_UPDATE, infoIconPath, function(fileName) {
            var extension = fileName.substring(fileName.length - 5);
			
            if(scanFiles.scanning) { return; } 

            var directory = $('#dir').val();
            directory = (directory === "/") ? directory : directory + "/";

            var filePath = directory + fileName; 
            var message = t('convert', "Select file type:");

            var html = '<div id="dropdown" class="drop">\n\
                            <p id="message">' + message + '</p>\n\
                            <div id="submit">\n\
                                <input id="fileType" style="width:220px" type="text" />\n\
                                <input id="execute" type="button" value="' + 'Convert' + '" />\n\
                            </div>\n\
                        </div>';

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

                if(type === '') {
                    $("#fileType").css("background-color", "red");
                    fileType = false;
                } else {
                    $("#fileType").css("background-color", "white");
                    fileType = true;
                    
                    if(eventData.keyCode == 13) {
                        doConvert(filePath, outPath);
                    }                    
                }
            });
        });
        
        droppedDown = true;
    }

    $(document).on('click', function(event) {
        var target = $(event.target);
        var clickOut = !(target.is('#fileType') || target.is('#execute'));

        if(droppedDown && clickOut) {
            hideDropDown();
        }
    });
	
	function getProgress() {
		$.get(OC.linkTo('convert', 'ajax/getprogress.php'), function( result ) {
			if (result >= 100) {
				alert("Conversion complete.");
				document.location.reload();
			}
			else {
				updateProgress(result);
				setTimeout(getProgress(), 2000);
			}
		});
	}
	
	
	function updateProgress(result) {
		var progress = result;
		$('#submit').html('<div style="margin-top: 5px;"><img src="' + OC.imagePath('convert','russell.gif') + '"/><div id = "progressBar" style="margin-left:5px;">' + "Percent Complete: " + progress + '</div></div>'); 
	}
	
    function doConvert(filePath, outPath) {
		
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
