<!-- src/Template/Products/addalias.ctp -->

<script>
var allFabrics=<?php echo json_encode($allFabrics); ?>;

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
<h3>Add New Fabric Alias:</h3>
<?php
/* PPSASCRUM-100: start */
$fabricVendorNameSpacing = str_repeat("&nbsp;", 20);
/* PPSASCRUM-100: end */
$fabricNames=array();
foreach($allFabrics as $fabric){
	/* PPSASCRUM-100: start */
	if(!array_key_exists($fabric['fabric_name'] . ";;" . $fabric['vendors_id'], $fabricNames)){
// 		$fabricNames[$fabric['fabric_name']]=$fabric['fabric_name'];
		$fabricNames[$fabric['fabric_name'] . ";;". $fabric['vendors_id']] = $fabric['fabric_name']  . "_" . $fabric['vendors_id'] . " [" . $fabric['vendor_name'] . "]";
		/* PPSASCRUM-100: end */
	}
}

echo $this->Form->create(null,['type'=>'file']);


echo "<div class=\"input selectbox required\"><label>Select a Customer:</label>";
echo "<select name=\"customer_id\" id=\"customer-id\"><option value=\"0\" selected disabled>--Select a Customer--</option>";
foreach($allCustomers as $customer){
	echo "<option value=\"".$customer['id']."\">".$customer['company_name']."</option>";
}
echo "</select>";
echo "</div>";

echo "<div class=\"input selectbox required\"><label>Select a Fabric Pattern:</label>";
/* PPSASCRUM-100: start */
// echo $this->Form->select('fabric_name',$fabricNames,['empty'=>'--Select Fabric Pattern--','required'=>true]);
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
foreach($fabricNames as $fabricMeta => $fabricData) {
	$fabricMetaArr = explode(';;', $fabricMeta);
	$fabricName = trim($fabricMetaArr[0]);
	$fabricId = trim($fabricMetaArr[1]);
	$fabricDataArr = explode('[', $fabricData);
	$vendorId = trim(explode('_', $fabricDataArr[0])[1]);
	$vendorName = substr(trim($fabricDataArr[1]), 0, strlen(trim($fabricDataArr[1])) - 1);
	echo "
		<div
			id=\"" . $fabricId . "\" vendorId=\"" . $vendorId . "\"
			onclick=\"changeFabricColorOptions(event,'" . str_replace("'", "\'", $fabricName) . "', " . $vendorId . ", '" . str_replace("'", "\'", $vendorName) . "')\"
		>
			<span class=\"fabric-name\">" . $fabricName . "</span>
			<span class=\"vendor-name\">[" . $vendorName . "]</span>
		</div>";
}

echo "<div class=\"scroll-button down\" style=\"position: sticky; bottom: 0; z-index: 1; justify-content: center; height: 25px; padding-bottom: 16px;\">⌵</div>
        </div>
	</div>";
/* PPSASCRUM-100: end */
echo "</div>";

echo "<div class=\"input selectbox required\"><label>Select a Color:</label>";
echo "<select name=\"fabric_color\" id=\"fabric-color\"><option value=\"0\" disabled selected>--Select a Color--</option></select>";
echo "</div>";


echo $this->Form->input('alias_fabric_name',['label'=>'Alias Name','required'=>true]);
echo $this->Form->input('alias_color',['label'=>'Alias Color','required'=>true]);



echo $this->Form->button('Add Alias',['type'=>'submit']);

echo $this->Form->end();
?>

<script>
$(function(){
    /* PPSASCRUM-100: start */
    
// 	$('select[name=\'fabric_name\']').change(function(){
		//fill the Colors list
// 		$('#fabric-color').html('<option value="0" selected disabled>--Select a Color--</option>');
// 		$.each(allFabrics,function(num,fabdata){
		    /* PPSASCRUM-100: start */
// 			if(fabdata.fabric_name==$('select[name=\'fabric_name\']').val()){
// 			if(fabdata.fabric_name==$('select[name=\'fabric_name\']').val().split(';;')[0].trim() &&
// 				fabdata.vendor_name == $('select[name=\'fabric_name\'] option:selected').text().split('[')[1].split(']')[0].trim()){
			/* PPSASCRUM-100: end */
				// $('select#fabric-color').append('<option value="'+fabdata.id+'">'+fabdata.color+'</option>');
// 			}
// 		});
// 	});

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
	$('div.current-custom-fabric-selection').attr('vendorName', vendorName);

	$('#fabric-color').html('<option value="0" selected disabled>--Select a Color--</option>');
	$.each(allFabrics,function(num,fabdata){
		/* PPSASCRUM-100: start */
		if(fabdata.fabric_name==$('div.current-custom-fabric-selection').attr('value').trim() &&
			fabdata.vendor_name == $('div.current-custom-fabric-selection').attr('vendorName').trim()){
		/* PPSASCRUM-100: end */
			$('select#fabric-color').append('<option value="'+fabdata.id+'">'+fabdata.color+'</option>');
		}
	});
}
/* PPSASCRUM-100: end */
</script>
<style>
div.input > label{ font-weight:bold; font-size:16px; }
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