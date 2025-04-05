<h2 style="text-align:center;">Edit Box# <?php echo $thisBox['box_number']; ?></h2>
<?php
echo $this->Form->create('null');

echo "<table id=\"fieldscols\" width=\"100%\" cellpadding=\"10\" cellspacing=\"0\" border=\"0\">
<tr>
<td width=\"20%\">";
echo $this->Form->input('length',['label' => 'Length (in)','type'=>'number','required'=>true,'autocomplete'=>'off', 'value' => $thisBox['length'] ]);
echo "</td>
<td width=\"20%\">";
echo $this->Form->input('width',['label' => 'Width (in)','type'=>'number','required'=>true,'autocomplete'=>'off', 'value' => $thisBox['width'] ]);
echo "</td>
<td width=\"20%\">";
echo $this->Form->input('height',['label' => 'Height (in)','type'=>'number','required'=>true,'autocomplete'=>'off', 'value' => $thisBox['height'] ]);
echo "</td>
<td width=\"20%\">";
echo $this->Form->input('weight',['label' => 'Weight (lbs)','type'=>'number','required'=>true,'autocomplete'=>'off','step'=>'0.01','value' => $thisBox['weight'] ]);
echo "</td>
<td width=\"20%\">";

$warehouseLocationsArr=array();
foreach($warehouseLocations as $whLoc){
    $warehouseLocationsArr[$whLoc['id']]=$whLoc['location'];
}

echo "<div class=\"selectbox\"><label>Warehouse Location</label>";
$selectArgs=array('required'=>false,'empty'=>'--Select Location--');
if(!is_null($thisBox['warehouse_location_id'])){
    $selectArgs['value']=$thisBox['warehouse_location_id'];
}
echo $this->Form->select('warehouse_location',$warehouseLocationsArr,$selectArgs);
echo "</div>";


echo "</td>
</tr>
</table>";


echo "<h4>Batch ".$thisBatch['id']." Line Items</h4>";
echo "<table id=\"batchlines\" width=\"100%\" cellpadding=\"5\" cellspacing=\"0\" border=\"1\" bordercolor=\"#000000\">
<thead>
<tr>
<th class=\"linenumber\">Line#</th>
<th class=\"lineitem\">Item</th>
<th class=\"location\">Location</th>
<th class=\"orderqty\">Order Qty</th>
<th class=\"batchqty\">Batch Qty</th>
<th class=\"boxqty\">Qty This Box</th>
<th class=\"otherboxqty\">Qty Other Boxes (".$this_batch_existing_box_count.")</th>
<th class=\"nonboxedqty\">Non-Boxed Batch Qty</th>
</tr>
</thead>
<tbody>";

foreach($lineItems as $idnum => $line){
    //print_r($line);
    if($line['data']->enable_tally == 1){
        echo "<tr data-batchitemid=\"".$line['data']->id."\" data-qtyotherboxes=\"".$line['this_batch_other_boxes']."\">
        <td class=\"linenumber\">".$line['data']->line_number."</td>
        <td class=\"lineitem\">".$line['data']->title."</td>
        <td class=\"location\">".$line['data']->room_number."</td>
        <td class=\"orderqty\">".($line['this_batch']+$line['other_batches']+$line['remaining_unscheduled'])."</td>
        <td class=\"batchqty\">".$line['this_batch']."</td>
        <td class=\"boxqty\"><button type=\"button\" onclick=\"subtractOne('".$line['data']->id."')\">-</button><input type=\"number\" name=\"boxcontent_lineitem_".$line['data']->id."\" value=\"".$line['this_box_qty']."\" autocomplete=\"off\" min=\"0\" max=\"".$line['this_batch']."\" value=\"0\" /><button type=\"button\" onclick=\"addOne('".$line['data']->id."')\">+</button></td>
        <td class=\"otherboxqty\">".$line['this_batch_other_boxes']."</td>
        <td class=\"nonboxedqty\"><span class=\"calculated_remaining\"></span></td>
        </tr>";
    }
}

echo "</tbody>
</table>";




echo "<div id=\"formactionsrow\">";
//echo "<button type=\"button\" id=\"deletebutton\" onclick=\"location.href='/orders/deletebox/".$thisBox['id']."';\">Delete This Box</button>";
//echo "<button type=\"button\" id=\"printlabelbutton\" onclick=\"window.open('/orders/boxlabel/".$thisBox['id'].".pdf')\">Print Label</button>";
echo "<button type=\"submit\" id=\"savechanges\">Save Changes</button>";
echo "<div style=\"clear:both;\"></div>";
echo "</div>";


echo $this->Form->end();
?>
<br><br><br>
<style>
#formactionsrow{ margin-top:30px; }
button#printlabelbutton{ float:left; margin-left:25px; padding:8px 12px !important; background:#222 !important; margin-top:15px; }
button#deletebutton{ background:red !important; float:left; padding:8px 12px !important; margin-top:15px; }
button#savechanges{ float:right; font-weight:bold; background:green; font-size:xx-large; }

form label{ font-size:x-large; }
form div.input input{ font-size:xx-large; height:auto; padding:10px; }

form div.selectbox label{ font-size:x-large; }
form div.selectbox select{ font-size:x-large; height:auto; }

div.row{ max-width:100% !important; width:100% !important; padding:1% 3%;  }
table#fieldscols tr,
table#fieldscols td{ border-bottom:0 !important; }

table#fieldscols td{ padding:20px !important; }
table#fieldscols td input,
table#fieldscols td select{ margin-bottom:0 !important; }

table#fieldscols{ background:#FFFFBF; border:2px solid #F8EC83; }

table#batchlines thead{ background:#26337A; }
table#batchlines thead tr th{ color:#FFF; font-size:large !important; }

table#batchlines tbody tr td{ border-bottom:1px solid #111 !important; font-size:x-large !important; line-height:1.4; }
table#batchlines tbody tr:nth-of-type(even){ background:rgba(0,0,138,0.05); }
table#batchlines tbody tr:nth-of-type(odd){ background:rgba(0,0,0,0.01); }

.linenumber{ width:9% !important; text-align:center;   vertical-align:middle;}
.lineitem{ width:25% !important;  vertical-align:middle;}
.location{ width:12% !important;  vertical-align:middle;}
.orderqty{ width:8% !important; text-align:center;  vertical-align:middle;}
.batchqty{ width:8% !important; text-align:center;  vertical-align:middle;}
.boxqty{ width:18% !important; background:rgba(0,255,0,0.22); vertical-align:middle; text-align:center; }
.boxqty button{ display:inline; font-size:x-large; padding:10px; border:0; margin-bottom:0 !important; }
.boxqty input{ margin-bottom:0 !important; width:75px !important; padding:22px 5px !important; display:inline; text-align:center; font-size:xx-large; }

.otherboxqty{ width:10% !important; text-align:center; vertical-align:middle; line-height:1.0 !important; }
.nonboxedqty{ width:10% !important;  text-align:center;  vertical-align:middle;}

div.submit{ margin-top:30px; text-align:center; }
div.submit input{ background:#006600; color:#FFF; font-size:xx-large; padding:15px 35px; border:0; font-weight:bold; }

div.submit input.notallowed{ cursor:not-allowed; background:#888 !important; color:#CCC !important; }
</style>
<script>
function subtractOne(lineID){
    var existing=parseInt($('tr[data-batchitemid=\''+lineID+'\'] td.boxqty input').val());
    if(existing == 0){
        $('tr[data-batchitemid=\''+lineID+'\'] td.boxqty input').val('0');
    }else{
        $('tr[data-batchitemid=\''+lineID+'\'] td.boxqty input').val( (existing-1) );
    }
}

function addOne(lineID){
    var existing=parseInt($('tr[data-batchitemid=\''+lineID+'\'] td.boxqty input').val());
    var thismaxval=parseInt($('tr[data-batchitemid=\''+lineID+'\'] td.boxqty input').attr('max'));
    if(existing == thismaxval){
        $('tr[data-batchitemid=\''+lineID+'\'] td.boxqty input').val(existing);
    }else{
        $('tr[data-batchitemid=\''+lineID+'\'] td.boxqty input').val( (existing+1) );
    }
}

function calculateremainingcol(){
    var totalitems=0;
    $('#batchlines tbody tr').each(function(){
       var remaining=( parseInt($(this).find('td.batchqty').html()) - parseInt($(this).find('td.boxqty input').val()) - parseInt($(this).attr('data-qtyotherboxes')) );
       console.log($(this).find('td.batchqty').html());
       console.log($(this).find('td.boxqty input').val());
       console.log($(this).attr('data-qtyotherboxes'));
       var newmax=( parseInt($(this).find('td.batchqty').html()) - parseInt($(this).attr('data-qtyotherboxes')) );
       
       $(this).find('td.boxqty input').attr('max',newmax);
       
       $(this).find('span.calculated_remaining').html(remaining);
       
       totalitems=(totalitems + parseInt($(this).find('td.boxqty input').val()) );
       
    });
    
    if(totalitems == 0){
        if(!$('div.submit input').hasClass('notallowed')){
            $('div.submit input').prop('disabled',true).addClass('notallowed');
        }
    }else{
        if($('div.submit input').hasClass('notallowed')){
            $('div.submit input').prop('disabled',false).removeClass('notallowed');
        }
    }
    $('div.submit input').val('Create Box of '+totalitems+' Items');
}
setInterval('calculateremainingcol()',300);

$(function(){
   $('form').submit(function(){
      var stotalitems=0;
      $('#batchlines tbody tr').each(function(){
        stotalitems=(stotalitems + parseInt($(this).find('td.boxqty input').val()) );
      });
      if(stotalitems == 0){
          alert('ERROR: You cannot create an empty box.');
          return false;
      }
   });
});
</script>