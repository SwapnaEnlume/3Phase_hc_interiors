<!-- src/Template/Products/addcc.ctp -->
<h3>Add New Cubicle Curtain:</h3>
<?php
echo $this->Form->create(null,['type'=>'file']);
$this->Form->unlockField('primary_image');

echo $this->Form->input('title',['label'=>'Name of this CC Product','required'=>true]);

echo $this->Form->label('Does this product have Mesh?');
echo $this->Form->radio('has_mesh',[1=>'Yes',0=>'No']);


echo $this->Form->input('qb_item_code',['label'=>'QuickBooks Item Code/Number','required'=>true]);

echo "<div id=\"fabricselector\"><h3>Select a Fabric</h3>";
/* PPSASCRUM-100: start */
// $fabricVendorNameSpacing = str_repeat("&nbsp;", 20);
// echo "<select id=\"fabricid\" name=\"fabric_name\" onchange=\"changeFabricColorOptions(this.value)\">
// echo "<select id=\"fabricid\" name=\"fabric_name\" onchange=\"changeFabricColorOptions(this)\">
// <!-- PPSASCRUM-100: end -->
// <option value=\"0\" selected disabled>--Select A Fabric--</option>";
// foreach($thefabrics as $fabricname => $fabricdata){
	/* PPSASCRUM-100: start */
// 	$fabricName = trim(explode(',',$fabricname)[0]);
// 	echo "<option value=\"".$fabricname."\">".$fabricname."</option>\n";
// 	echo "<option value=\"".$fabricName.";;".$fabricdata['vendorId']."\">".$fabricName .$fabricVendorNameSpacing." [".$fabricdata['vendorName']."]</option>\n";
	/* PPSASCRUM-100: end */
// }
// echo "</select>";

/* PPSASCRUM-100: start */
echo "
    <div class=\"custom-fabric-selection-dropdown\">
        <div class=\"current-custom-fabric-selection\" value=\"0\">
            --Select A Fabric--
            <span style=\"padding-bottom: 5px;\">⌵</span>
        </div>
        <div class=\"custom-fabric-dropdown-options\" id=\"custom-fabric-dropdown-options\">
        <div class=\"scroll-button up\" style=\"position: sticky; top: 0; z-index: 1; justify-content: center; height: 25px; padding-bottom: 23px; display: none\">⌃</div>
        <div id=\"0\" style=\"display: flex; justify-content: center; font-weight: 900; color: #A19FA4\"
            onclick=\"changeFabricColorOptions(event, '', 0, '')\">
            --Select A Fabric--
        </div>
        ";
foreach($thefabrics as $fabricName => $fabricdata) {
	$vendorId = $fabricdata['vendorId'];
	$fabricName = trim(explode(',', $fabricName)[0]);
	echo "
		<div
			id=\"" . $fabricdata['id'] . "\" vendorId=\"" . $vendorId . "\"
			onclick=\"changeFabricColorOptions(event,'" . str_replace("'", "\'", $fabricName) . "', " . $vendorId . ", '" . str_replace("'", "\'", $fabricdata['vendorName']) . "')\"
		>
			<span class=\"fabric-name\">" . $fabricName . "</span>
			<span class=\"vendor-name\">[" . $fabricdata['vendorName'] . "]</span>
		</div>";
}

echo "<div class=\"scroll-button down\" style=\"position: sticky; bottom: 0; z-index: 1; justify-content: center; height: 25px; padding-bottom: 16px;\">⌵</div>
        </div>
    </div>
	";
/* PPSASCRUM-100: end */


echo "<div id=\"fabricselectionwrap\">";
foreach($thefabrics as $fabricname => $fabriccolors){
	/* PPSASCRUM-100: start */
	$fabricName = trim(explode(',',$fabricname)[0]);
// 	if($fabricname == $ccData['fabric_name']){
	if($fabricName == $ccData['fabric_name']){
		echo "<div id=\"colorselections_".str_replace("'","",str_replace(" ","_",$fabricName))."\" class=\"coloroptionsblock\">
		<h4>Select Which <em>".$fabricName."</em> Colors You Want Available For This Window Treatment <a href=\"javascript:checkallcolors('".str_replace("'","\'",$fabricName)."');\">Check All</a> <a href=\"javascript:uncheckallcolors('".str_replace("'","\'",$fabricName)."');\">Uncheck All</a></h4>";
		/* PPSASCRUM-100: end */
		foreach($fabriccolors as $num => $color){
			/* PPSASCRUM-100: start */
// 			echo "<div class=\"colorselection\"><label><input type=\"checkbox\" name=\"use_".str_replace("'","",str_replace(" ","_",$fabricname))."_color_".str_replace(" ","_",$color['color'])."\" value=\"yes\" /> <img src=\"/files/fabrics/".$color['id']."/".$color['image']."\" height=\"14\" width=\"14\" /> ".$color['color']."</label></div>";
			echo "<div class=\"colorselection\"><label><input type=\"checkbox\" name=\"use_".str_replace("'","",str_replace(" ","_",$fabricName))."_color_".str_replace(" ","_",$color['color'])."\" value=\"yes\" /> <img src=\"/files/fabrics/".$color['id']."/".$color['image']."\" height=\"14\" width=\"14\" /> ".$color['color']."</label></div>";
			/* PPSASCRUM-100: end */
		}
		echo "<div style=\"clear:both;\"></div></div>";
	}
}
echo "</div>";

echo $this->Form->input('description',['label'=>'Cubicle Curtain Description','type'=>'textarea','required'=>false]);

echo $this->Form->input('primary_image',['label'=>'Cubicle Curtain Image','type'=>'file','required'=>false]);




echo "<fieldset><legend>Which of these sizes are applicable to this curtain? <a href=\"javascript:checkallsizes();\">Check All</a> <a href=\"javascript:uncheckallsizes();\">Uncheck All</a></legend>";

//$availableSizes=json_decode($ccData['available_sizes'],true);

foreach($sizes as $size){
	
	//determine if this size is Checked
	$ifchecked=false;
	$thisprice=0;
	$thisdifficulty=0;
	$thisyards=0;
	$thisweight=0;
	$thislaborlf=0;
	
	foreach($availableSizes as $sizerow => $sizedata){
		if($size['id'] == $sizedata['id']){
			$ifchecked=true;
			if(floatval($sizedata['price']) >0){
				$thisprice=$sizedata['price'];
				$thisdifficulty=$sizedata['difficulty'];
				$thisyards=$sizedata['yards'];
				$thisweight=$sizedata['weight'];
				$thislaborlf=$sizedata['labor_lf'];
			}
			
		}
	}
	
	echo "<div class=\"sizeblock\">";
	echo $this->Form->input('size_'.$size['id'].'_enabled',['type'=>'checkbox','value'=>1,'label'=>$size['title'],'data-sizeid'=>$size['id'],'checked'=>$ifchecked]);
	echo "<div class=\"sizedatarow\" id=\"size-".$size['id']."-price-wrap\"";
	
	if(!$ifchecked){
		//if unchecked
		//echo " style=\"visibility:hidden;height:0px;\"";
		echo " style=\"opacity:0.2;\"";
		$readonly=true;
	}else{
		$readonly=false;
	}
	
	echo ">".$this->Form->input('size_'.$size['id'].'_price',['readonly'=>$readonly,'label'=>'Price','value'=>number_format($thisprice,2,'.','')])."</div>";
	
	
	
	echo "<div class=\"sizedatarow\" id=\"size-".$size['id']."-weight-wrap\"";
	if(!$ifchecked){
		//echo " style=\"visibility:hidden;height:0px;\"";
		echo " style=\"opacity:0.2;\"";
		$readonly=true;
	}else{
		$readonly=false;
	}
	echo ">".$this->Form->input('size_'.$size['id'].'_weight',['readonly'=>$readonly,'label'=>'Weight','value'=>$thisweight]);
	echo "</div>";
	
	
	
	echo "<div class=\"sizedatarow\" id=\"size-".$size['id']."-yards-wrap\"";
	if(!$ifchecked){
		//echo " style=\"visibility:hidden;height:0px;\"";
		echo " style=\"opacity:0.2;\"";
		$readonly=true;
	}else{
		$readonly=false;
	}
	echo ">".$this->Form->input('size_'.$size['id'].'_yards',['readonly'=>$readonly,'label'=>'Yards','value'=>$thisyards]);
	echo "</div>";
	
	
	
	echo "<div class=\"sizedatarow\" id=\"size-".$size['id']."-difficulty-wrap\"";
	if(!$ifchecked){
		//echo " style=\"visibility:hidden;height:0px;\"";
		echo " style=\"opacity:0.2;\"";
		$readonly=true;
	}else{
		$readonly=false;
	}
	echo ">".$this->Form->input('size_'.$size['id'].'_difficulty',['readonly'=>$readonly,'label'=>'Difficulty','value'=>$thisdifficulty]);
	echo "</div>";
	
	
	
	echo "<div class=\"sizedatarow\" id=\"size-".$size['id']."-laborlf-wrap\"";
	if(!$ifchecked){
		//echo " style=\"visibility:hidden;height:0px;\"";
		echo " style=\"opacity:0.2;\"";
		$readonly=true;
	}else{
		$readonly=false;
	}
	echo ">".$this->Form->input('size_'.$size['id'].'_laborlf',['readonly'=>$readonly,'label'=>'Labor LF','value'=>$thislaborlf]);
	echo "</div>";
	
	
	
	echo "</div>";
}

echo "<div style=\"clear:both;\"></div>";
echo "</fieldset>";


echo $this->Form->button('Add This Cubicle Curtain',['type'=>'submit']);

echo $this->Form->end();
?>
<script>
function checkallcolors(fabricname){
	var block=fabricname.replace(/ /g,"_");
	$('#colorselections_'+block).find('input[type=checkbox]').prop('checked',true);
}
	
function uncheckallcolors(fabricname){
	var block=fabricname.replace(/ /g,"_");
	$('#colorselections_'+block).find('input[type=checkbox]').prop('checked',false);
}
	
function checkallsizes(){
	$('fieldset input[type=checkbox]').each(function(){
		$(this).prop('checked',true);
		$('#size-'+$(this).attr('data-sizeid')+'-price').css({'visibility':'visible','height':'auto'});
	})
}
	
function uncheckallsizes(){
	$('fieldset input[type=checkbox]').each(function(){
		$(this).prop('checked',false);
		$('#size-'+$(this).attr('data-sizeid')+'-price').css({'visibility':'hidden','height':'0px'});
	})
}
	
$(function(){
	$('fieldset input[type=checkbox]').change(function(){
		if($(this).prop('checked')){
			
			$('#size-'+$(this).attr('data-sizeid')+'-price-wrap,#size-'+$(this).attr('data-sizeid')+'-weight-wrap,#size-'+$(this).attr('data-sizeid')+'-yards-wrap,#size-'+$(this).attr('data-sizeid')+'-difficulty-wrap,#size-'+$(this).attr('data-sizeid')+'-laborlf-wrap').css({'opacity':'1.0','height':'auto'});
			$('#size-'+$(this).attr('data-sizeid')+'-price-wrap input,#size-'+$(this).attr('data-sizeid')+'-weight-wrap input,#size-'+$(this).attr('data-sizeid')+'-yards-wrap input,#size-'+$(this).attr('data-sizeid')+'-difficulty-wrap input,#size-'+$(this).attr('data-sizeid')+'-laborlf-wrap input').removeAttr('readonly');
			
		}else{
			
			$('#size-'+$(this).attr('data-sizeid')+'-price-wrap,#size-'+$(this).attr('data-sizeid')+'-weight-wrap,#size-'+$(this).attr('data-sizeid')+'-yards-wrap,#size-'+$(this).attr('data-sizeid')+'-difficulty-wrap,#size-'+$(this).attr('data-sizeid')+'-laborlf-wrap').css({'opacity':'0.2'});
			$('#size-'+$(this).attr('data-sizeid')+'-price-wrap input,#size-'+$(this).attr('data-sizeid')+'-weight-wrap input,#size-'+$(this).attr('data-sizeid')+'-yards-wrap input,#size-'+$(this).attr('data-sizeid')+'-difficulty-wrap input,#size-'+$(this).attr('data-sizeid')+'-laborlf-wrap input').attr('readonly','readonly');
			
		}
	});
	
	/* PPSASCRUM-100: start */

    const fabricSelectionDropdown = $('div.current-custom-fabric-selection');
    const fabricDropdownOptions = $('div.custom-fabric-dropdown-options');
    
    $(window).click(function(event) {
        if (!fabricSelectionDropdown.is(event.target) && fabricSelectionDropdown.has(event.target).length === 0) {
            fabricDropdownOptions.hide();
        } else {
            fabricDropdownOptions.toggle();
        }
    });

    const dropdownOptions = document.getElementById('custom-fabric-dropdown-options');
    const scrollUp = document.querySelector('.scroll-button.up');
    const scrollDown = document.querySelector('.scroll-button.down');

    scrollUp.addEventListener('mouseenter', () => {
        scrollInterval = setInterval(() => {
            dropdownOptions.scrollTop -= 32; // Scroll up

            if (dropdownOptions.scrollTop > 60) {
                $('div.scroll-button.up').css('display','block');
            } else {
                $('div.scroll-button.up').css('display','none');
            }

        }, 100);
    });

    scrollUp.addEventListener('mouseleave', () => {
        clearInterval(scrollInterval);
    });

    scrollDown.addEventListener('mouseenter', () => {
        scrollInterval = setInterval(() => {
            dropdownOptions.scrollTop += 32; // Scroll down

            if (dropdownOptions.scrollTop > 60) {
                $('div.scroll-button.up').css('display','block');
            } else {
                $('div.scroll-button.up').css('display','none');
            }

        }, 100);
    });

    scrollDown.addEventListener('mouseleave', () => {
        clearInterval(scrollInterval);
    });

    $('div.current-custom-fabric-selection').click(function() {
        setTimeout(function() {
            if ($('div.custom-fabric-dropdown-options').is(':visible')) {
                document.getElementById('custom-fabric-dropdown-options').scrollTop = document.getElementById($('div.current-custom-fabric-selection').attr('value')).offsetTop - 36;
            }   
        }, 1200);
    });
    
    window.addEventListener('keydown', (event) => {
          let eventObj = event;
          if (
            $('div.custom-fabric-dropdown-options').is(':visible')
          ) {
            const currentTime = Date.now();
            const timeSinceLastTyped = currentTime - lastTypedTime;

            if (
              lastTypedTime == 0 ||
              (searchTerm.length < 4 && timeSinceLastTyped < 1500)
            ) {
              if (event.key.length == 1) {
                searchTerm += event.key.toLowerCase();
              }
              console.log('Current searchTerm: ', searchTerm);
              search(searchTerm, eventObj);
              lastTypedTime = currentTime; // Update last typed time
              console.log('Done search for: ', searchTerm);
            } else {
              if (searchTerm != '' && searchTerm.length == 4) {
                // searchTerm += event.key.toLowerCase();
                searchTerm = '';
                if (event.key.length == 1) {
                    searchTerm = event.key.toLowerCase();
                }
              }
              else {
                if (event.key.length == 1) {
                    searchTerm += event.key.toLowerCase();
                }
              }
              console.log('Searching in else for: ', searchTerm);
              search(searchTerm, eventObj);
              lastTypedTime = currentTime; // Update last typed time
              console.log('Done search in else for: ', searchTerm);
            }
          }
          setInterval(function() {
            let currentTime = Date.now();
            if ((currentTime - lastTypedTime) > 1500) { searchTerm = ''; }
          }, 100);
        });
    /* PPSASCRUM-100: end */
});
	
/* PPSASCRUM-100: start */
let searchTerm = ''; // Initialize an empty string to store typed characters
let lastTypedTime = 0; // Store the last time a character was typed
let searchTimeout;

function search(searchTerm, event) {
    console.log('Searching for: ', searchTerm);

    const listItems = Array.from($('div.custom-fabric-dropdown-options div')).slice(1,-1);

    for (let item of listItems) {
        const itemText = item.textContent.toLowerCase().trim();
        if (itemText.startsWith(searchTerm.toLowerCase())) {
            console.log('This is the matching option: ', itemText);
            // item.scrollIntoView(true);
			item.parentNode.scrollTop = item.offsetTop;
            event.preventDefault(); // Prevent default scrolling behavior
            break;
        }
    }
}

// function changeFabricColorOptions(fabricname){
function changeFabricColorOptions(event, fabricName, vendorId, vendorName){
	console.log('Selected fabric: ', event.target);
    if (event.target.id == '0' && fabricName == '' && vendorId == 0) {
        console.log('label option clicked');
        event.preventDefault();
        event.stopPropagation();
        return;
    }

    const selectedFabricHtml = event.currentTarget.innerHTML;
    console.log('Clicked: ', selectedFabricHtml);

    Array.from(
        document.querySelector('div#custom-fabric-dropdown-options.custom-fabric-dropdown-options').children
    ).forEach((arg) => (arg.innerHTML = arg.innerHTML.replaceAll('✓', '').replaceAll('&nbsp;', '')));
    
    event.currentTarget.children[0].innerHTML =
        '✓' +
        '&nbsp;'.repeat(5) +
        event.currentTarget.children[0].innerHTML.replaceAll('✓', '').replaceAll('&nbsp;', '');

    const selected = document.querySelector('.current-custom-fabric-selection');
     
    selected.innerHTML = selectedFabricHtml.replaceAll('✓', '')
        .replaceAll('&nbsp;', '').replace(/\\n\s+$/gm, '').concat('<span>⌵</span>');

    document.getElementById('custom-fabric-dropdown-options').style.display = 'none';

    console.log('This is fabric name: ', fabricName);
    console.log('This is vendor id: ', vendorId);

    $('div.current-custom-fabric-selection').attr('value', fabricName);
	//fabricname=fabricname.replace(/ /g,"_");
	//fabricname=fabricname.replace("'","");
	/* PPSASCRUM-100: start */
// 	var fabricNameVendorId = fabricname.value.split(';;');
// 	var fabricName = fabricNameVendorId[0];
// 	var vendorId = parseInt(fabricNameVendorId[1]);
	/* PPSASCRUM-100: end */
// 	$('div.coloroptionsblock input[type=checkbox]').removeProp('checked');
	//$.get('/products/getwtfabriccolorcheckboxes/<?php echo $ccData['id']; ?>/'+encodeURIComponent(fabricname),
	/* PPSASCRUM-100: start */
// 	$.get('/products/getfabriccolorscheckboxes/cc/'+encodeURIComponent(fabricname),
	$.get('/products/getfabriccolorscheckboxes/cc/'+encodeURIComponent(fabricName)+'/'+vendorId,
	/* PPSASCRUM-100: end */
	function(result){
		$('#fabricselectionwrap').html(result);
	});
}
</script>
<style>
#fabricselector h3{ font-size:16px; margin:0; font-weight:bold; }
#fabricselector label{ display:inline-block; margin-right:20px; }
div.input > label{ font-weight:bold; font-size:16px; }
/*fieldset label{ font-weight:normal !important; font-size:14px !important; display:inline-block; width:135px; float:left; height: 22px; margin-bottom: 8px; }*/

fieldset div.sizeblock{ font-weight:normal !important; font-size:14px !important; display:inline-block; width:185px; float:left; height:205px; margin-bottom: 8px; }

div.coloroptionsblock{ padding:20px 20px 35px 20px; }
div.coloroptionsblock h4{ line-height: 2rem; margin:0 0 10px 0; font-size:16px; font-weight:bold; color:#26337A; border-bottom:2px solid #26337A; }

div.coloroptionsblock h4 a{ font-size:12px; font-weight:normal; color:blue; margin-left:20px; }

div.colorselection{ width:185px; float:left; height:35px; }

	
fieldset input[type=text]{ width:90px; height:22px; font-size:12px; padding:2px; }
fieldset label{ font-weight:normal !important; font-size:12px !important; }
fieldset legend a{ font-size:12px; font-weight:normal; color:blue; margin-left:20px; }
div.sizeblock input[type=checkbox]{ margin:0 10px 0 0 !important; }

	
#currentimage img{ max-width:250px; height:auto; }

div.sizedatarow{ padding:4px 2px 4px 18px; }
div.sizeblock div div.input.text label{ display:inline-block; margin-right:5px; width:70px; }
div.sizeblock div div.input.text input[type=text]{ display:inline-block; width:55px; padding:2px; margin-bottom:0; }
div.sizeblock div.checkbox label{ font-weight:bold !important; font-size:16px !important; }

/* PPSASCRUM-100: start */
.custom-fabric-selection-dropdown { position: relative; width: inherit; background-color: #FAFAFA; border-color: #ccc; color: rgba(0, 0, 0, 0.75); font-family: 'inherit'; font-size: 0.875rem; line-height: normal; height: 2.3125rem; cursor: pointer; justify-content: flex-start; align-items: center; margin-bottom: 16px; }

div.custom-fabric-selection-dropdown:not(:-internal-list-box) { overflow: visible !important; }

.current-custom-fabric-selection { background-color: #FAFAFA; border-radius: 0; border-style: solid; border-width: 1px; color: rgba(0, 0, 0, 0.60); font-family: 'Helvetica Neue'; font-size: 0.875rem; line-height: normal; letter-spacing: 0.4px; word-spacing: 0.5px; padding: 0.5rem; height: 2.3125rem; cursor: pointer; border: 1px solid #ccc; width: inherit; display: flex; justify-content: space-between; align-items: center; }

.custom-fabric-dropdown-options { display: none; border: 1px solid #ccc; background-color: #646267; opacity: 0.95; background-attachment: scroll; position: absolute; width: 1280px; z-index: 1; align-items: center; font-size: 14px; font-family: 'metropolislight'; font: 1px 800 #000000 !important; font-weight: 900; text-decoration-color: rgba(0, 0, 0, 0.75); max-height: 1500%; overflow-x: hidden; overflow-y: auto; box-sizing: inherit; border-radius: 0.4rem; }

.scroll-button { padding: 10px; text-align: center; background-color: #646267; cursor: pointer; font-weight: bold; }

.custom-fabric-dropdown-options div { display: flex; justify-content: space-between; padding: 10px; cursor: pointer; width: inherit; align-items: center; font-size: 14px; font-family: 'metropolislight'; color: #F0EFF0; }

.custom-fabric-dropdown-options div:hover { background-color: #548BEB !important; }

div.custom-fabric-dropdown-options > div#label:hover { background-color: transparent !important; }

.fabric-name { flex: 1; }

.vendor-name { flex: 1; white-space: nowrap; justify-content: flex-start; }
/* PPSASCRUM-100: end */
</style>
<script>
//on enter keydown treat it as tab
$(function(){
    $(document).on('keydown', 'input:visible, select:visible, button:visible,feildset:visible, [type="radio"]:visible, [type="checkbox"]:visible, [tabindex]', function(e) {
            if (e.which === 13) { // 13 is the Enter key code
                e.preventDefault(); // Prevent default action, which is form submission
                moveToNextFocusableElement(this);
            }
        });
});
</script>