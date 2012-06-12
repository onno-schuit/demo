$(document).ready(function() {
	var loginerrors = $('.error').html();	
	if (loginerrors) {
		$('#thnk-login-main').css('display','block');
	}
	$('.locallogin a').click(function() {
		var itemstate = $('#thnk-login-main').css('display');
		

		
		if (itemstate == 'none') {
			$('#thnk-login-main').css('display','block');
		} else {
			$('#thnk-login-main').css('display','none');
		}
	});
});