<!-- src/Template/Products/newspecialpricing.ctp -->
<h2>Add New Customer Special Product Pricing:</h2>
<hr />
<?php
echo $this->Form->create(null,['type'=>'file']);
//select fabric(s) and size(s) for this special pricing
echo $this->Form->input('process',['type'=>'hidden','value'=>'step3']);

echo $this->Form->input('customer_id',['type'=>'hidden','value'=>$postdata['customer_id']]);
echo $this->Form->input('product_type',['type'=>'hidden','value'=>$postata['product_type']]);		


echo "<div id=\"fabricselector\"><h3>Select a Fabric</h3>";
/* PPSASCRUM-100: start */
// $fabricVendorNameSpacing = str_repeat("&nbsp;", 20);
// echo "<select id=\"fabricid\" name=\"fabric_name\" onchange=\"changeFabricColorOptions(this.value)\">
// echo "<select id=\"fabricid\" name=\"fabric_name\" onchange=\"changeFabricColorOptions(this)\">
// <!-- PPSASCRUM-100: end -->
// <option value=\"0\" selected disabled>--Select A Fabric--</option>";
// foreach($thefabrics as $fabricname => $fabricdata){
	/* PPSASCRUM-100: start */
	// echo "<option value=\"".$fabricname."\">".$fabricname."</option>\n";
// 	$fabricName = trim(explode(',',$fabricname)[0]);
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
	$fabricName = trim(explode(',', $fabricName)[0]);
	$vendorId = $fabricdata['vendorId'];
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
	</div>";
/* PPSASCRUM-100: end */

echo "<div id=\"fabricColorSelectionWrap\"></div>";

switch($productType){
	case "cc":
		echo "<p><label><input type=\"radio\" name=\"mesh\" value=\"yes\" /> With Mesh</label> <label><input type=\"radio\" name=\"mesh\" value=\"no\" /> No Mesh</label></p>";
	break;
	case "bs":
		echo "<p><label><input type=\"radio\" name=\"quilted\" value=\"yes\" /> Quilted</label> <label><input type=\"radio\" name=\"quilted\" value=\"no\" /> Unquilted</label></p>";
	break;
	case "wt":
		echo "<h3>WT Type</h3>
		<p id=\"wttypeoptions\">
		<label><input type=\"radio\" onchange=\"wttypechange()\" name=\"wt_type\" id=\"wttype_straightcornice\" value=\"Straight Cornice\" /> Straight Cornice</label> 
		<label><input type=\"radio\" onchange=\"wttypechange()\" name=\"wt_type\" id=\"wttype_shapedcornice\" value=\"Shaped Cornice\" /> Shaped Cornice</label> 
		<label><input type=\"radio\" onchange=\"wttypechange()\" name=\"wt_type\" id=\"wttype_boxpleatedvalance\" value=\"Box Pleated Valance\" /> Box Pleated Valance</label> 
		<label><input type=\"radio\" onchange=\"wttypechange()\" name=\"wt_type\" id=\"wttype_tailoredvalance\" value=\"Tailored Valance\" /> Tailored Valance</label> 
		<label><input type=\"radio\" onchange=\"wttypechange()\" name=\"wt_type\" id=\"wttype_pinchpleateddrapery\" value=\"Pinch Pleated Drapery\"> Pinch Pleated Drapery</label>
		</p>";

		echo "<div id=\"weltoptions\"><h3>Welts</h3><p><label><input type=\"radio\" name=\"welts\" value=\"yes\" /> Has Welts</label> <label><input type=\"radio\" name=\"welts\" value=\"no\" /> No Welts</label></p></div>";

		echo "<div id=\"liningoptions\"><h3>Lining</h3><p><label><input type=\"radio\" name=\"lining\" value=\"Unlined\" /> Unlined</label> <label><input type=\"radio\" name=\"lining\" value=\"BO Lining\" /> BO Lining</label> <label><input type=\"radio\" name=\"lining\" value=\"FR Lining\" /> FR Lining</label></p></div>";
		
		echo "<script>
		function wttypechange(){
			$('#wttypeoptions input[type=radio]').each(function(){
				if($(this).is(':checked')){
					if($(this).val() == 'Straight Cornice' || $(this).val() == 'Shaped Cornice'){
						$('#weltoptions').show('fast');
						$('#liningoptions').hide('fast');
					}else if($(this).val() == 'Pinch Pleated Drapery'){
						$('#weltoptions').hide('fast');
						$('#liningoptions').show('fast');
					}else{
						$('#weltoptions').hide('fast');
						$('#liningoptions').hide('fast');
					}
					
				}
			});
		}
		</script>";
		
	break;
}

echo $this->Form->submit('Continue');

echo "<style>
#fabricselections label{ width:20%; float:left; margin:5px; }
#sizeselections label{ width:20%; float:left; margin:5px; }
div.coloroptionsblock{ padding:20px 20px 35px 20px; }
div.coloroptionsblock h4{ line-height: 2rem; margin:0 0 10px 0; font-size:16px; font-weight:bold; color:#26337A; border-bottom:2px solid #26337A; }

div.coloroptionsblock h4 a{ font-size:12px; font-weight:normal; color:blue; margin-left:20px; }

div.colorselection{ width:185px; float:left; height:35px; }

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
	
/* PPSASCRUM-100: start */
// function changeFabricColorOptions(newcolor){
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
    
// 	var fabricNameVendorId = fabricname.value.split(';;');
// 	var fabricName = fabricNameVendorId[0];
// 	var vendorId = parseInt(fabricNameVendorId[1]);
// 	console.log('Fabric name: ', fabricName, ' && vendor id: ', vendorId);
// 	$.get('/products/getfabriccolorscheckboxes/".$productType."/'+newcolor,function(result){
	$.get('/products/getfabriccolorscheckboxes/".$productType."/'+encodeURIComponent(fabricName)+'/'+vendorId,function(result){
		/* PPSASCRUM-100: end */
		$('#fabricColorSelectionWrap').html(result);
	});
}

function checkallcolors(){
	$('#fabricColorSelectionWrap input[type=checkbox]').each(function(){
		$(this).prop('checked',true);
	});
}

function uncheckallcolors(){
	$('#fabricColorSelectionWrap input[type=checkbox]').each(function(){
		$(this).prop('checked',false);
	});
}

/* PPSASCRUM-100: start */
$(function() {

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
});

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
/* PPSASCRUM-100: end */
</script>";

echo $this->Form->end();
?>