    function ShowHide(divId) {
        if(document.getElementById('tl_homepage_stream_handle_hide_in').style.display == 'none') {
             document.getElementById('thnk-region-thnk-stream').style.marginLeft = '0px';

             document.getElementById('tl_homepage_stream_handle_hide_in').style.display = 'block';
             document.getElementById('tl_homepage_stream_handle_hide_uit').style.display = 'none';
             document.getElementById('tl_stream_system').style.display = 'block';
             document.getElementById('tl_stream_personal').style.display = 'block';
        } else {
             document.getElementById('thnk-region-thnk-stream').style.marginLeft = '222px';
             document.getElementById('tl_homepage_stream_handle_hide_in').style.display = 'none';
             document.getElementById('tl_homepage_stream_handle_hide_uit').style.display = 'block';
             document.getElementById('tl_stream_system').style.display = 'none';
             document.getElementById('tl_stream_personal').style.display = 'none';
             
        }
    }