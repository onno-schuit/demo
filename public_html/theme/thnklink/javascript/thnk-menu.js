
$(document).ready(function() {
	
	var highestCol = Math.max($('#thnk-region-post').height(),$('#thnk-region-thnk-stream').height(),$('#course-post-wrapper').height());
	
	$('#thnk-region-post').css("min-height",highestCol);
	$('#thnk-region-thnk-stream').css("min-height",highestCol);
	$('#course-post-wrapper').css("min-height",highestCol);
	$('.block_tl_stream #tl_stream_wrapper').css("min-height",highestCol);
	
	$('#thnk-admin .block_navigation').css('display','none');
	$('#thnk-admin .block_thnkhelp').css('display','none');
	$('#thnk-admin .block_settings').css('display','none');
	$('#thnk-admin').css('display','none');



	$('#thnk-buttonhelp').click(function() {
		var itemstate = $('.block_thnkhelp').css('display');

		if (itemstate == 'none') {
			$('#thnk-buttonhelp').css('background-color','#87CAFA');
			$('#thnk-buttonnavigation').css('background-color','#D6D6D6');
			$('#thnk-buttonsettings').css('background-color','#D6D6D6');
			$('#thnk-admin').css('display','block');
			$('#thnk-admin .block_navigation').css('display','none');
			$('#thnk-admin .block_thnkhelp').css('display','block');
			$('#thnk-admin .block_settings').css('display','none');
		} else {
			$('#thnk-buttonhelp').css('background-color','#D6D6D6');
			$('#thnk-buttonnavigation').css('background-color','#D6D6D6');
			$('#thnk-buttonsettings').css('background-color','#D6D6D6');
			$('#thnk-admin').css('display','none');
			$('#thnk-admin .block_navigation').css('display','none');
			$('#thnk-admin .block_thnkhelp').css('display','none');
			$('#thnk-admin .block_settings').css('display','none');
		}
	});

	$('#thnk-buttonnavigation').click(function() {
		var itemstate = $('.block_navigation').css('display');
		if (itemstate == 'none') {
			$('#thnk-buttonhelp').css('background-color','#D6D6D6');
			$('#thnk-buttonnavigation').css('background-color','#87CAFA');
			$('#thnk-buttonsettings').css('background-color','#D6D6D6');
			$('#thnk-admin').css('display','block');
			$('#thnk-admin .block_navigation').css('display','block');
			$('#thnk-admin .block_thnkhelp').css('display','none');
			$('#thnk-admin .block_settings').css('display','none');
		} else {
			$('#thnk-buttonhelp').css('background-color','#D6D6D6');
			$('#thnk-buttonnavigation').css('background-color','#D6D6D6');
			$('#thnk-buttonsettings').css('background-color','#D6D6D6');
			$('#thnk-admin').css('display','none');
			$('#thnk-admin .block_navigation').css('display','none');
			$('#thnk-admin .block_thnkhelp').css('display','none');
			$('#thnk-admin .block_settings').css('display','none');
		}


	});

	$('#thnk-buttonsettings').click(function() {
		var itemstate = $('.block_settings').css('display');
		if (itemstate == 'none') {
			$('#thnk-buttonhelp').css('background-color','#D6D6D6');
			$('#thnk-buttonnavigation').css('background-color','#D6D6D6');
			$('#thnk-buttonsettings').css('background-color','#87CAFA');
			$('#thnk-admin').css('display','block');
			$('#thnk-admin .block_navigation').css('display','none');
			$('#thnk-admin .block_thnkhelp').css('display','none');
			$('#thnk-admin .block_settings').css('display','block');
		} else {
			$('#thnk-buttonhelp').css('background-color','#D6D6D6');
			$('#thnk-buttonnavigation').css('background-color','#D6D6D6');
			$('#thnk-buttonsettings').css('background-color','#D6D6D6');
			$('#thnk-admin').css('display','none');
			$('#thnk-admin .block_navigation').css('display','none');
			$('#thnk-admin .block_thnkhelp').css('display','none');
			$('#thnk-admin .block_settings').css('display','none');
		}

	});

	$('#thnkhelpbuttontext').mbFlipText(true);
	$('#thnkhelpbuttontext').css('display','block');

	$('#thnknavbuttontext').mbFlipText(true);
	$('#thnknavbuttontext').css('display','block');

	$('#thnksettingbuttontext').mbFlipText(true);
	$('#thnksettingbuttontext').css('display','block');

});