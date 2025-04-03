<style>
body{ font-family:'Helvetica',Arial,sans-serif; }
button{ background:#2345A0; color:#FFF; border:1px solid #000; padding:10px 20px; font-size:large; cursor:pointer; }
</style>
<h3 style="text-align: center;">Generate Quote PDF:</h3>
<ul style="list-style:none;">
    	<li>
		<label><input type="radio" name="quote"  value="default" checked="checked" /> Quote.PDF</label>
	</li>
		<li>
		<label><input type="radio" name="quote" value="scope"  /> Install Scope.PDF</label>
	</li>
	</ul>

<p style="text-align: center; "><button type="button" onclick="gotoquotepdf()">Generate PDF</button></p>

<script>
function gotoquotepdf(){
	if(	$('input[name="quote"]:checked').val() == 'default'){
	    parent.$.fancybox.close();
    
        /* PPSASCRUM-373: start */
    	// window.open('/quotes/buildquotepdf/<?php echo $quoteID; ?>/quote/<?php echo "HCI%20Quote%20".$quote_number.$rev.'.pdf'; ?>', '_blank')
    	window.open('/quotes/buildquotepdf/<?php echo $quoteID; ?>/<?php echo $ordermode; ?>/quote/<?php echo "HCI%20Quote%20".$quote_number.$rev.'.pdf'; ?>', '_blank');
    	/* PPSASCRUM-373: end */

	}
	if($('input[name="quote"]:checked').val() == 'scope'){
	    parent.$.fancybox.close();
	    
	   /* PPSASCRUM-369: start */
	   // window.open('/quotes/buildquotepdf/<?php echo $quoteID; ?>/scope/<?php echo "HCI%20Install%20Scope%20".$quote_number.$rev.'.pdf'; ?>', '_blank')
	    window.open('/quotes/buildquotepdf/<?php echo $quoteID; ?>/<?php echo $ordermode; ?>/scope/<?php echo "HCI%20Install%20Scope%20".$quote_number.$rev.'.pdf'; ?>', '_blank');
	    /* PPSASCRUM-369: end */

	}

	
}
</script>