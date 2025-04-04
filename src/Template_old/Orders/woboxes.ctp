<style>

table#boxeslist thead tr{ background:#26337A; }
table#boxeslist tbody tr:nth-of-type(odd){ background:rgba(0,0,0,0.01); }
table#boxeslist tbody tr:nth-of-type(even){ background:rgba(0,0,138,0.05); }

table#boxeslist thead tr th{ color:#FFF !important; font-size:medium; }


table#wolines thead tr{ background:#26337A; }
table#wolines thead tr th{ color:#FFF !important; font-size:medium; }
table#wolines tbody tr td{ font-size:medium; line-height:1.4; border-bottom:1px solid #111 !important; border-left:1px solid #111 !important; }

table#wolines tbody tr:nth-of-type(odd){ background:rgba(0,0,0,0.01); }
table#wolines tbody tr:nth-of-type(even){ background:rgba(102,76,0,0.05); }

table#wolines thead tr{ background:#26337A; }
table#wolines thead tr th{ color:#FFF !important; font-size:medium; }
table#wolines tbody tr td{ font-size:medium; line-height:1.4; border-bottom:1px solid #111 !important; border-left:1px solid #111 !important; }

table#wolines tbody tr:nth-of-type(odd){ background:rgba(0,0,0,0.01); }
table#wolines tbody tr:nth-of-type(even){ background:rgba(0,0,138,0.05); }



.boxnum{ width:7%; text-align:center; vertical-align:middle; }
.batchnumber{ width:7%; text-align:center; vertical-align:middle; }
.itemcount{width:7%; text-align:center; vertical-align:middle; }
.boxlength{width:7%; text-align:center; vertical-align:middle; }
.boxwidth{width:7%; text-align:center; vertical-align:middle; }
.boxheight{width:7%; text-align:center; vertical-align:middle; }
.boxweight{width:9%; text-align:center; vertical-align:middle; }
.boxstatus{width:10%; text-align:center; vertical-align:middle; }
.boxuser{width:10%; text-align:center; vertical-align:middle; }
.boxdate{width:14%; text-align:center; vertical-align:middle; }
.boxactions{width:15%; text-align:center; vertical-align:middle; }
</style>
<div>
    <h2 style="float:left; width:30%; text-align:left;">Workorder Packaging</h2>
    <h3 style="width:40%; float:left; text-align:center;">Work Order: <?php echo $thisWO['order_number']; ?>
    <?php /**PPSASCRUM-3 start **/
        echo ($customerData['on_credit_hold']) ? '     <span style="color: red;"> On Credit Hold</span>  ' : ' ';
    /**PPSASCRUM-3 end **/ ?>
    </h3>
    <div style="clear:both;"></div>
</div>

<?php //echo "<pre>"; print_r($orderBoxes); echo "</pre>"; ?>

<table id="wolines" width="100%" cellpadding="5" cellspacing="0" border="1" bordercolor="#000000">
<thead>
<tr>
<th class="linenumber">Line#</th>
<th class="lineitem">Item</th>
<th class="location">Location</th>
<th class="orderqty">Order Qty</th>
<th class="boxedqty">Boxed Qty</th>
<th class="nonboxedqty">Non-Boxed Qty</th>
</tr>
</thead>
<tbody>
<?php
foreach($lineItems as $idnum => $line){
    if($line->enable_tally == 1){
        echo "<tr>
        <td class=\"linenumber\">".$line->line_number."</td>
        <td class=\"lineitem\">".$line->title."</td>
        <td class=\"location\">".$line->room_number."</td>
        <td class=\"orderqty\">".$line->qty."</td>
        <td class=\"boxedqty\">";
        $boxedTally=0;
        foreach($orderBoxes as $box){
            foreach($box['contents'] as $boxitem){
                if($boxitem['line_number'] == $line->line_number){
                    echo "<div><B>(".$boxitem['qty'].")</B> Box# ".$box['boxdata']['box_number']."</div>";
                    $boxedTally=($boxedTally+intval($boxitem['qty']));
                }
            }
        }
        echo "</td>
        <td class=\"nonboxedqty\">";
        echo (intval($line->qty) - $boxedTally );
        echo "</td>
        </tr>";
    }
}
?>
</tbody>
</table>



<br>
<h3>Existing Boxes for this Work Order:</h3>
<table width="100%" id="boxeslist" cellpadding="5" cellspacing="0" border="1">
<thead>
    <tr>
        <th class="boxnum">Box #</th>
        <th class="batchnumber">Batch ID#</th>
        <th class="itemcount">Item Count</th>
        <th class="boxlength">Length</th>
        <th class="boxwidth">Width</th>
        <th class="boxheight">Height</th>
        <th class="boxweight">Weight</th>
        <th class="boxstatus">Status</th>
        <th class="boxuser">User</th>
        <th class="boxdate">Date/Time</th>
        <th class="boxactions">Actions</th>
    </tr>
</thead>
<tbody>
    <?php
    foreach($orderBoxes as $box){
      echo "<tr>
      <td class=\"boxnum\">".$box['boxdata']['box_number']."</td>
      <td class=\"batchnumber\">".$box['boxdata']['batch_id']."</td>
      <td class=\"itemcount\">";
      $itemCount=0;
      foreach($box['contents'] as $content){
          $itemCount=($itemCount + intval($content['qty']));
      }
      echo $itemCount;
      echo "</td>
      <td class=\"boxlength\">".$box['boxdata']['length']." in</td>
      <td class=\"boxwidth\">".$box['boxdata']['width']." in</td>
      <td class=\"boxheight\">".$box['boxdata']['height']." in</td>
      <td class=\"boxweight\">".$box['boxdata']['weight']." lbs</td>
      <td class=\"boxstatus\">".$box['boxdata']['status']."</td>
      <td class=\"boxuser\">";
      foreach($allusers as $userData){
          if($userData['id'] == $box['boxdata']['user_id']){
              echo $userData['first_name']." ".substr($userData['last_name'],0,1);
          }
      }
      echo "</td>
      <td class=\"boxdate\">".date('M j - g:iA',$box['boxdata']['created'])."</td>
      <td class=\"boxactions\"><a href=\"/orders/editbox/".$box['boxdata']['batch_id']."/".$box['bodata']['id']."\"><img src=\"/img/edit.png\" width=\"22\" alt=\"Edit Box\" title=\"Edit Box\" /></a> &nbsp; <a href=\"/orders/boxlabel/".$box['boxdata']['id'].".pdf\" target=\"_blank\"><img src=\"/img/printlabel.png\" width=\"22\" alt=\"Box Label\" title=\"Box Label\" /></a> &nbsp; <a href=\"/orders/deletebox/".$box['boxdata']['id']."\"><img src=\"/img/delete.png\" alt=\"Delete This Box\" width=\"22\" title=\"Delete This Box\" /></a></td>
      </tr>";  
    }
    ?>
</tbody>
</table>
<Br><br><Br><Br>