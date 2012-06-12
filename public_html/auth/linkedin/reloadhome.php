<?php ?>

<HTML>
<HEAD>
<script type="text/javascript">
	function reloadParentAndClose() {
        // reload the opener or the parent window
        window.opener.location.reload();
        // then close this pop-up window
        window.close();
    } 
               
</script>
</HEAD>
<BODY onLoad="reloadParentAndClose();">
<input type=button value="Close" onClick="reloadParentAndClose();" />
</BODY>
</HTML>

