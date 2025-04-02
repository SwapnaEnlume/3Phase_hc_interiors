<table width="100%" cellpadding="1" border="1" bordercolor="#000000" cellspacing="0" style="margin-top:8px;">
    <thead>
<tr>
    <th width="10%" style="font-size:10px; font-weight:bold;" align="center">LINE</th>
    <th width="12%" style="font-size:10px; font-weight:bold;" align="center">QTY</th>
    <th width="50%" style="font-size:10px; font-weight:bold;" align="center">ITEM DESCRIPTION</th>
    <th width="28%" style="font-size:10px; font-weight:bold;;" align="center">LOCATION</th>
</tr>
</thead>
<tbody>
    <?php
    foreach($thisBoxContents as $boxItem){
        
        
        echo "<tr>
        <td width=\"10%\" style=\"font-size:10px;\" align=\"center\">".$boxItem['line_number']."</td>
        <td width=\"12%\" style=\"font-size:10px;\" align=\"center\">".$boxItem['qty']."</td>
        <td width=\"50%\" style=\"font-size:10px;\" align=\"center\">";
        foreach($lineItems as $lineItem){
            if($lineItem['data']->line_number == $boxItem['line_number']){
                echo $lineItem['data']->title;
            }
        }
        echo "</td>
        <td width=\"28%\" style=\"font-size:10px;\" align=\"center\">".$boxItem['room_number']."</td>
        </tr>";
    }
    ?>
    </tbody>
</table>