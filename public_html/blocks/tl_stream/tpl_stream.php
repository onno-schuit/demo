<?php
if (!isloggedin() || isguestuser()) {
    // empty stream if guest_user or not logged in
    return;
}
?>
<div id="tl_stream_wrapper">
<div id="tl_homepage_stream_handle">
    <a title="Hide" class="handle_button" style="DISPLAY: none;" id="tl_homepage_stream_handle_hide_in" onclick ="javascript:ShowHide('thnk-region-thnk-stream')" href="javascript:;"><div id='tl_stream_fold_in' class='tl_stream_images'></div></a>
    <a title="Show" class="handle_button" style="DISPLAY: block;" id="tl_homepage_stream_handle_hide_uit" onclick ="javascript:ShowHide('thnk-region-thnk-stream')" href="javascript:;"><div id='tl_stream_fold_out' class='tl_stream_images'></div></a>
    <img src="<?php echo $CFG->wwwroot; ?>/blocks/tl_stream/images/stream-text.png">
    <a href="<?php echo helper_moodle_system::url('tlstream=personal'); ?>" title="Personal stream" class="handle_button<?php if (tl_streams_controller::get_display_stream() == 'personal'): echo ' selected'; endif; ?>" id="tl_homepage_stream_handle_personal"><div id='tl_stream_system'  class='tl_stream_images'></div></a>
    <a href="<?php echo helper_moodle_system::url('tlstream=system'); ?>" title="System stream" class="handle_button<?php if (tl_streams_controller::get_display_stream() == 'system'): echo ' selected'; endif; ?>" id="tl_homepage_stream_handle_system"><div id='tl_stream_personal'  class='tl_stream_images'></div></a>
</div>
<div id="tl_homepage_stream">

    <form id="tl_stream_new_message" action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="post">
        <textarea name="tl_stream_new_message"></textarea>
        <input type="submit" value="send" id="tl_stream_new_message_send" style="float: left;"/>
        <input type="hidden" name="_nonce" value="<?php echo helper_moodle_system::getNonce('update_stream'); ?>" id="tl_nonce" />
        <span id="tl_homepage_stream_loading" style="display: none; width: 100px; float: left; margin-left: 20px;">
        </span>
        <div id="tl_stream_items" style="">
        &nbsp;
        </div>
        <a id="tl_stream_get_more"><?php print_string('get_more', 'block_tl_stream'); ?></a>
    </form>
</div>
<div style="clear: both;"></div>
</div>

<script type="text/javascript">
function array_max(elements) {
    var max = -1;
    for (i=0; i<elements.length; i++) {
    	if (parseInt(elements[i]) > max) { max = parseInt(elements[i]); }
    }
    return max;
};

var tl_stream_item_ids = new Array();
var tl_stream_is_personal =  <?php if(tl_streams_controller::get_display_stream() == 'personal'): ?>true<?php else: ?>false;<?php endif; ?>;
var tl_setinterval_handler;
var tl_ajax_handler = <?php echo "'" . $CFG->wwwroot . "/blocks/tl_stream/ajax_handler.php'";?>;
var tl_stream_new_message_text = '<?php print_string('new_message', 'block_tl_stream'); ?>';

tl_stream_update_messages = function(data) {
    // hide 'loading...' div
//     $('#tl_homepage_stream_loading').fadeOut(1000);
    var add_to = 'top';
    for(var i=0; i<data.length; i++) {
        if (!data[i].meta.id) {
            if (data[i].meta._nonce) {
                // received new _nonce
                $('#tl_nonce').val(data[i].meta._nonce); // store nonce in hidden form field            
            } 
            if (data[i].meta._add_to) {
                add_to = data[i].meta._add_to;
            }
            if (data[i].meta._no_more_messages == 1) {
                $('#tl_stream_get_more')
                    .html('<?php print_string('no_more_messages', 'block_tl_stream'); ?>')
                    .css('color', '#DDD')
                    .css('width', '150px')
                    .css('border-color', '#DDD')
                    .css('cursor', 'default')
                    .click(function(e) {
                        e.preventDefault();
                        return false;
                    });
            }
        } else if ($.inArray(data[i].meta.id, tl_stream_item_ids) == -1) {
    	   // received stream item
    		tl_stream_item_ids.push(data[i].meta.id); // store item id so we don't receive it again
	        var elm = $(data[i].html); // create new element from html-data
            beautify_delete_link(elm);
	        $(elm).data('meta', data[i].meta).hide(); // add some meta data to the new element, like item id and is-personal
            if (add_to == 'top') {
	           $('#tl_stream_items').prepend(elm); // add the item at the beginning of our stream div
            } else {
	           $('#tl_stream_items').append(elm); // add the item at the end of our stream div
            }
    	}
    }
    tl_stream_toggle_items();
}

function tl_stream_load_from_server(get_more) {
    // show 'loading...' div
    // $('#tl_homepage_stream_loading').fadeIn('slow');
    // add _nonce to the post, or we can't post to the ajax_handler.php
	var params = {'_nonce': $('#tl_nonce').val()};
    if (get_more) {
        var message_count = $('.tl_stream_item').length;
        params.get_more = message_count;
    }
	if (tl_stream_item_ids.length > 0) {
	   // add lastid to the post if we already retrieved some items, so we only get new ones
		params.downloaded_ids =  tl_stream_item_ids;
	}
    // make the call and pass the data to tl_stream_update_messages()
    $.post(tl_ajax_handler, params, tl_stream_update_messages, 'json');
}

function tl_stream_toggle_items() {
    // filter the stream for personal messages or system messages
    $('.tl_stream_item').each(function() {
    	if (tl_stream_is_personal && $(this).data('meta').is_personal != 1) {
    		$(this).slideUp(1000);
    	} else {
    		$(this).slideDown(1000);
    	}
    });
}

function tl_get_nonce() {
   return $('#tl_nonce').val();   
}

function beautify_delete_link(elm) {
    var id = elm.find('input').val();
    if (!id) { return; }
    var new_link = $('<a class="tl_stream_delete" href="">x</a>')
        .data('id', id)
        .click(function(evnt) {
            evnt.preventDefault();
            if (!confirm('<?php print_string('delete_message', 'block_tl_stream'); ?>')) { return; }
            $.post(tl_ajax_handler, {'tl_stream_delete': $(this).data('id'), '_nonce': tl_get_nonce()});
            $(this).closest('.tl_stream_item').slideUp(1000, function(){$(this).remove()}); 
        });
    var label = elm.find('label');
    new_link.insertBefore(label); 
    label.css('position', 'relative').css('left', '-10000px').css('display', 'none');
}

function submit_new_message() {
    if ($('#tl_stream_new_message textarea').val() == '') { return; } // empty message
    
	var form = $('#tl_stream_new_message'); 
	$.post(tl_ajax_handler, form.serialize(), tl_stream_update_messages, 'json');
	$('#tl_stream_new_message textarea').val('').blur(); // empty with default string
}

$(document).ready(function() {
    // first time: load all messages from server
	tl_stream_load_from_server();
    // refresh stream every x seconds
	tl_setinterval_handler = self.setInterval("tl_stream_load_from_server()", 5 * 1000); // 5 seconds

    // personal-button clicked
    $('#tl_homepage_stream_handle_personal').click(function(evnt) {
        evnt.preventDefault(); // prevent user from leaving this page
        
		tl_stream_is_personal = true;
        tl_stream_toggle_items();
        
        // change 'icons' state 
        $('#tl_homepage_stream_handle_personal').addClass('selected');
        $('#tl_homepage_stream_handle_system').removeClass('selected');
        $.post(tl_ajax_handler, {'tlstream': 'personal', 'action': 'none', '_nonce': tl_get_nonce()});
        return false;
    });
    // system-button clicked
    $('#tl_homepage_stream_handle_system').click(function(evnt) {
        evnt.preventDefault();// prevent user from leaving this page
		tl_stream_is_personal = false;
        tl_stream_toggle_items();
        // change 'icons' state 
        $('#tl_homepage_stream_handle_personal').removeClass('selected');
        $('#tl_homepage_stream_handle_system').addClass('selected');
        $.post(tl_ajax_handler, {'tlstream': 'system', 'action': 'none', '_nonce': tl_get_nonce()});
        return false;
    });
    // new message submit
    $('#tl_stream_new_message_send').click(function(evnt){
    	evnt.preventDefault();
        submit_new_message();
    });
    $('#tl_stream_new_message textarea').val(tl_stream_new_message_text).css('color', 'gray') /* default message + gray */
        .focus(function() {
            // remove default message and set color to black
            if ($(this).val() == tl_stream_new_message_text) {
                $(this).val('');
                $(this).css('color', 'black');
            }
        })
        .blur(function() {
            // add default message when field is empty and set color to gray
            if ($(this).val() == '') {
                $(this).val(tl_stream_new_message_text);
                $(this).css('color', 'gray');
            }
        })
        .bind('keypress', function(e) {
            // look for the Enter-keypress and submit form if user pressed it
            var code = (e.keyCode ? e.keyCode : e.which);
            if(code == 13) { //Enter keycode
                submit_new_message();
                return false;
            }
            return true;
        }); 
    // hide submit button if javascript is enabled
    $('#tl_stream_new_message_send').hide();  
    $('#tl_stream_get_more').click(function(){
        tl_stream_load_from_server(true); // get more
    }); 
});

</script>
