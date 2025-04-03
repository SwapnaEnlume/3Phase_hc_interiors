<style>
div.row{ max-width:100% !important; width:100% !important; padding:1% 3%;  }
table#boxeslist thead tr{ background:#664C00; }
table#boxeslist thead tr th{ color:#FFF !important; font-size:large; }
table#boxeslist tbody tr td{ font-size:x-large; line-height:1.4; border-bottom:1px solid #111 !important; border-left:1px solid #111 !important; }

table#boxeslist tbody tr:nth-of-type(odd){ background:rgba(0,0,0,0.01); }
table#boxeslist tbody tr:nth-of-type(even){ background:rgba(102,76,0,0.05); }

table#batchlines thead tr{ background:#26337A; }
table#batchlines thead tr th{ color:#FFF !important; font-size:large; }
table#batchlines tbody tr td{ font-size:x-large; line-height:1.4; border-bottom:1px solid #111 !important; border-left:1px solid #111 !important; }

table#batchlines tbody tr:nth-of-type(odd){ background:rgba(0,0,0,0.01); }
table#batchlines tbody tr:nth-of-type(even){ background:rgba(0,0,138,0.05); }

table#boxeslist .boxnum{ width:8%; text-align:center; vertical-align:middle; }
table#boxeslist .itemcount{width:8%; text-align:center; vertical-align:middle; }
table#boxeslist .boxlength{width:6%; text-align:center; vertical-align:middle; }
table#boxeslist .boxwidth{width:6%; text-align:center; vertical-align:middle; }
table#boxeslist .boxheight{width:6%; text-align:center; vertical-align:middle; }
table#boxeslist .boxweight{width:8%; text-align:center; vertical-align:middle; }
table#boxeslist .boxstatus{width:12%; text-align:center; vertical-align:middle; }
table#boxeslist .boxuser{width:14%; text-align:center; vertical-align:middle; }
table#boxeslist .boxdate{width:18%; text-align:center; vertical-align:middle; }
table#boxeslist .warehouselocation{ width:14%; text-align:center; vertical-align:middle; }
</style>
<div>
    <h2 style="float:left; width:30%; text-align:left;">Workorder Packaging</h2>
    <h2 style="width:40%; float:left; text-align:center;">Batch #<?php echo $thisBatch['id']; ?></h3>
    <h2 style="width:30%; float:right; text-align:right;">Work Order: <?php echo $thisWO['order_number']; ?></h4>
    <div style="clear:both;"></div>
</div>
<div style="text-align:right;"><button type="button" onclick="location.href='/orders/editbox/<?php echo $thisBatch['id']; ?>';">Manage Boxes</button>&nbsp;&nbsp;&nbsp;<button type="button" onclick="location.href='/orders/newbox/batch/<?php echo $thisBatch['id']; ?>';">+ Create Box</button></div>

<?php //echo "<pre>"; print_r($lineItems); echo "</pre>"; ?>

<table id="batchlines" width="100%" cellpadding="5" cellspacing="0" border="1" bordercolor="#000000">
<thead>
<tr>
<th class="wolinenumber">Wo Line#</th>
<th class="linenumber">So Line#</th>
<th class="lineitem">Item</th>
<th class="location">Location</th>
<th class="orderqty">Order Qty</th>
<th class="batchqty">Batch Qty</th>
<th class="boxedqty">Boxed Batch Qty</th>
<th class="nonboxedqty">Non-Boxed Batch Qty</th>
</tr>
</thead>
<tbody>
<?php
foreach($lineItems as $idnum => $line){
    if($line['data']->enable_tally == 1 && intval($line['this_batch']) > 0){
        echo "<tr data-batchitemid=\"".$line['data']->id."\">
        <td class=\"linenumber\">".$line['data']->wo_line_number."</td>
        <td class=\"linenumber\">".$line['data']->line_number."</td>
        <td class=\"lineitem\">".$line['data']->title."</td>
        <td class=\"location\">".$line['data']->room_number."</td>
        <td class=\"orderqty\">".($line['this_batch']+$line['other_batches']+$line['remaining_unscheduled'])."</td>
        <td class=\"batchqty\">".$line['this_batch']."</td>
        <td class=\"boxedqty\">";
        //echo $line['this_batch_other_boxes'];
        foreach($batchBoxes as $box){
            foreach($box['item_data'] as $boxitem){
                if($boxitem['line_number'] == $line['data']->line_number && ($boxitem['quote_item_id'] == $line['data']->id) ){
                    echo "<div><B>(".$boxitem['qty'].")</B> Box# ".$box['box_number']."</div>";
                }
            }
        }
        echo "</td>
        <td class=\"nonboxedqty\">";
            echo (intval($line['this_batch']) - intval($line['this_batch_other_boxes']) );
        echo "</td>
        </tr>";
    }
}
?>
</tbody>
</table>
