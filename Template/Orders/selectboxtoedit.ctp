<style>
#content div.message{ font-size:xx-large !important; }
</style>
<h2 style="margin-top:30px; text-align:center;">Manage Boxes for Batch <?php echo $thisBatch['id']; ?></h2>
<?php
/*
foreach($allBoxes as $box){
    echo "<a class=\"boxbutton\" href=\"/orders/editbox/".$thisBatch['id']."/".$box['boxdata']['id']."\"><b>Box# ".$box['boxdata']['box_number']."</b></a>";
}
*/
?>

<table width="100%" id="boxeslist" cellpadding="5" cellspacing="0" border="1">
<thead>
    <tr>
        <th class="boxnum">Box #</th>
        <th class="itemcount">Item Count</th>
        <th class="boxdims">Dimensions</th>
        <th class="boxweight">Weight</th>
        <th class="boxstatus">Status</th>
        <th class="boxuser">User</th>
        <th class="boxdate">Date/Time</th>
        <th class="warehouselocation">Warehouse Location</th>
        <th class="boxactions">Actions</th>
    </tr>
</thead>
<tbody>
    <?php
    foreach($allBoxes as $box){
      echo "<tr>
      <td class=\"boxnum\">".$box['boxdata']->box_number."</td>
      <td class=\"itemcount\">";
      $itemCount=0;
      foreach($box['boxcontents'] as $contentEntry){
          $itemCount=($itemCount + intval($contentEntry['qty']));
      }
      echo $itemCount;
      echo "</td>
      <td class=\"boxdims\">".$box['boxdata']->length." X ".$box['boxdata']->width." X ".$box['boxdata']->height."</td>
      <td class=\"boxweight\">".$box['boxdata']->weight." lbs</td>
      <td class=\"boxstatus\">".$box['boxdata']->status."</td>
      <td class=\"boxuser\">";
      foreach($allusers as $userData){
          if($userData['id'] == $box['boxdata']->user_id){
              echo $userData['first_name']." ".substr($userData['last_name'],0,1);
          }
      }
      echo "</td>
      <td class=\"boxdate\">".date('M j - g:iA',$box['boxdata']->created)."</td>
      <td class=\"warehouselocation\">";
      foreach($warehouseLocations as $whLoc){
          if($whLoc->id==$box['boxdata']->warehouse_location_id){
              echo $whLoc['location'];
          }
      }
      echo "</td><td class=\"boxactions\">
      <a href=\"/orders/editbox/".$thisBatch['id']."/".$box['boxdata']['id']."\"><img src=\"/img/edit.png\" width=\"32\" alt=\"Edit Box\" title=\"Edit Box\" /></a> &nbsp; 
      <a href=\"/orders/boxlabel/".$box['boxdata']['id'].".pdf\" target=\"_blank\"><img src=\"/img/printlabel.png\" width=\"32\" alt=\"Box Label\" title=\"Box Label\" /></a> &nbsp; 
      <a href=\"/orders/deletebox/".$box['boxdata']['id']."\"><img src=\"/img/delete.png\" width=\"32\" alt=\"Delete Box\" title=\"Delete Box\" /></a>";
      
      echo "</td>
      </tr>";  
    }
    ?>
</tbody>
</table>
<br><p style="text-align:right;"><button type="button" onclick="location.href='/orders/batchboxes/<?php echo $thisBatch['id']; ?>';">Packaging Batch View</button>&nbsp;&nbsp;&nbsp;<button type="button" onclick="location.href='/orders/newbox/batch/<?php echo $thisBatch['id']; ?>';">+ Create Box</button></p>
<style>
a.boxbutton{ border:1px solid #f8f8f8; padding:10px; margin:1%; display:block; font-size:xx-large; width:30%; float:left; background:rgba(0,0,0,0.08); }
a.boxbutton b{ display:block; text-align:center;}
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

table#boxeslist .boxnum{ width:10%; text-align:center; vertical-align:middle; }
table#boxeslist .itemcount{width:6%; text-align:center; vertical-align:middle; }
table#boxeslist .boxdims{width:14%; text-align:center; vertical-align:middle; }
table#boxeslist .boxweight{width:8%; text-align:center; vertical-align:middle; }
table#boxeslist .boxstatus{width:10%; text-align:center; vertical-align:middle; }
table#boxeslist .boxuser{width:12%; text-align:center; vertical-align:middle; }
table#boxeslist .boxdate{width:14%; text-align:center; vertical-align:middle; }
table#boxeslist .warehouselocation{ width:10%; text-align:center; vertical-align:middle; }
table#boxeslist .boxactions{width:16%; text-align:center; vertical-align:middle; }

</style>