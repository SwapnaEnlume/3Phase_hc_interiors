<h3>Create New Facility:</h3>
<?php
echo $this->Form->create(null);
echo $this->Form->input('facility_name',['label'=>'Facility Name','required'=>true]);

echo $this->Form->input('facility_code',['label'=>'Facility Code','required'=>true]);
echo "<legend>Default Address </legend>";
echo $this->Form->select('default_address',$shipTooptions,['required'=>false,'empty'=>'--Select address--','id'=>'default_address']);
echo $this->Form->input('attention',['label'=>'(Facility)Attention','required'=>true]);

echo "</div>";

echo "<br><BR><div id=\"buttonsbottom\">";

echo $this->Form->button('Add This Facility',['type'=>'submit']);
echo "<button type=\"button\" onclick=\"parent.$.fancybox.close();\">Cancel</button>";
echo "</div><br><br>";
echo $this->Form->end();

?><script>
    

$("#new_address").on('click', function() {
        $.fancybox({
			'href':'/orders/markshipto',
			'type':'iframe',
			'autoSize':false,
			'width':680,
			'height':480,
			'modal':true,
			helpers: {
				overlay: {
					locked: false
				}
			}
		});
    });

    
</script>
<style>
body{ font-family:Arial,Helvetica,sans-serif; }
form div.input{ margin-bottom:10px; }
form div.input label{ display:block; font-weight:bold; }
form div.input input[type=text]{ width:95%; padding:6px; }
form div.input textarea{ width:95%; padding:6px; }

#buttonsbottom div.submit{ float:left; }
#buttonsbottom button[type=button]{ float:right; }

tr.Mfgnote{ color:orange; }
tr.Shipnote{ color:purple; }

table thead tr{ background:#26337A; }
table thead tr th{ color:#FFF; }	
table{ border-bottom:2px solid #26337A; }
	
form div.input.date select{ padding-right:30px !important; }
	
table tbody tr:nth-of-type(even){ background:#f8f8f8; }
table tbody tr:nth-of-type(odd){ background:#e8e8e8; }
	
form h2{ font-size:large; font-weight:bold; color:#26337A; }
form{ max-width:600px; margin:15px auto; }
form dl dd{ padding-left:20px; }
form input[type=submit]{ background:#26337A; color:#FFF; font-weight:bold; padding:15px 25px; font-size:large; border:0; }
.required label:after {
    color: #d00;
    content: " *"
}
</style>