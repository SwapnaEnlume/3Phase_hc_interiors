<?php
foreach($libraryImages as $image){
	
	list($width, $height, $type, $attr) = getimagesize($_SERVER['DOCUMENT_ROOT']."/webroot/img/library/".$image['filename']);
	
	if($width > 600 || $height > 600){
		
		if($width > $height){
			//landscape
			$fixedwidth=600;
			
			$proportion=($height/$width);
			$fixedheight=round($fixedwidth*$proportion);
			
		}elseif($height > $width){
			//portrait
			$fixedheight=600;
			
			$proportion=($width/$height);
			$fixedwidth=round($fixedheight*$proportion);
			
		}else{
			//square
			$fixedwidth=600;
			$fixedheight=600;
		}
		
	}else{
		$fixedwidth=$width;
		$fixedheight=$height;
	}
	
	echo "<p><img src=\"/img/library/".$image['filename']."\" onclick=\"parent.addimagelayer('/img/library/".$image['filename']."',".$fixedwidth.",".$fixedheight.");\" /></p>";
}
?>
<div style="clear:both;"></div>
<p><button type="button" onclick="parent.$.fancybox.close();">Cancel</button></p>
<style>
p img{ width:37%; height:auto; float:left; margin:1%; padding:2%; cursor:pointer; }
p img:hover{ background:#CCC; }
</style>