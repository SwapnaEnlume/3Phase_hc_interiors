<?php
echo "<h1 style=\"margin:0;\">BOM Breakdown - Work Order ".$orderData['order_number']."</h1>";

$typesArr=array('catchall'=>array(),'bedspread'=>array(),'cubiclecurtain'=>array(),'boxpleatedvalance'=>array(),'straightcornice'=>array(),'shapedcornice'=>array(),'pinchpleated'=>array());

//echo "<pre>"; print_r($lineItems); echo "</pre>"; exit;

foreach($lineItems as $lineItem){
    switch($lineItem['data']->product_type){
        case 'custom':
		case 'newcatchall-blinds':
        case 'newcatchall-hardware':
        case 'newcatchall-hwtmisc':
        case 'newcatchall-misc':
        case 'newcatchall-service':
        case 'newcatchall-shades':
        case 'newcatchall-shutters':
            $typesArr['catchall'][$lineItem['fabricdata']['fabric_name']][$lineItem['fabricdata']['color']][]=array('line_number' => $lineItem['data']['line_number'], 'total_yards' => (floatval($lineItem['metadata']['yds-per-unit']) * floatval($lineItem['data']->qty) ) );
        break;
        
        case 'newcatchall-swtmisc':
            $typesArr['swtmisc'][$lineItem['fabricdata']['fabric_name']][$lineItem['fabricdata']['color']][]=array('line_number' => $lineItem['data']['line_number'], 'total_yards' => (floatval($lineItem['metadata']['yds-per-unit']) * floatval($lineItem['data']->qty) ) );
        break;
        
        case 'newcatchall-cubicle':
        case 'cubicle_curtains':
            $typesArr['cubiclecurtain'][$lineItem['fabricdata']['fabric_name']][$lineItem['fabricdata']['color']][]=array('line_number' => $lineItem['data']['line_number'], 'total_yards' => $lineItem['metadata']['total-yds'] );
        break;
        
        case 'newcatchall-bedding':
        case 'bedspreads':
            $typesArr['bedspread'][$lineItem['fabricdata']['fabric_name']][$lineItem['fabricdata']['color']][]=array('line_number' => $lineItem['data']['line_number'], 'total_yards' => (floatval($lineItem['metadata']['yds-per-unit']) * floatval($lineItem['data']->qty) ) );
        break;
        
        case 'newcatchall-drapery':
            $typesArr['pinchpleated'][$lineItem['fabricdata']['fabric_name']][$lineItem['fabricdata']['color']][]=array('line_number' => $lineItem['data']['line_number'], 'total_yards' => $lineItem['metadata']['total-yds'] );
        break;
        
        case 'newcatchall-valance':
            $typesArr['boxpleatedvalance'][$lineItem['fabricdata']['fabric_name']][$lineItem['fabricdata']['color']][]=array('line_number' => $lineItem['data']['line_number'], 'total_yards' => $lineItem['metadata']['total-yds'] );
        break;
        
        case 'newcatchall-cornice':
            $typesArr['cornice'][$lineItem['fabricdata']['fabric_name']][$lineItem['fabricdata']['color']][]=array('line_number' => $lineItem['data']['line_number'], 'total_yards' => $lineItem['metadata']['total-yds'] );
        break;
        
        case 'window_treatments':
            switch($lineItem['metadata']['wttype']){
                case 'Box Pleated Valance':
                    $typesArr['boxpleatedvalance'][$lineItem['fabricdata']['fabric_name']][$lineItem['fabricdata']['color']][]=array('line_number' => $lineItem['data']['line_number'], 'total_yards' => $lineItem['metadata']['total-yds'] );
                break;
                case 'Straight Cornice':
                case 'Shaped Cornice':
                    $typesArr['cornice'][$lineItem['fabricdata']['fabric_name']][$lineItem['fabricdata']['color']][]=array('line_number' => $lineItem['data']['line_number'], 'total_yards' => $lineItem['metadata']['total-yds'] );
                break;
                case 'Pinch Pleated Drapery':
                    $typesArr['pinchpleated'][$lineItem['fabricdata']['fabric_name']][$lineItem['fabricdata']['color']][]=array('line_number' => $lineItem['data']['line_number'], 'total_yards' => $lineItem['metadata']['total-yds'] );
                break;
            }
        break;
        case 'calculator':
            switch($lineItem['data']->calculator_used){
                case 'cubicle-curtain':
                    $typesArr['cubiclecurtain'][$lineItem['fabricdata']['fabric_name']][$lineItem['fabricdata']['color']][]=array('line_number' => $lineItem['data']->line_number, 'total_yards' => $lineItem['metadata']['total-yds'] );
                break;
                case 'bedspread':
                    $typesArr['bedspread'][$lineItem['fabricdata']['fabric_name']][$lineItem['fabricdata']['color']][]=array('line_number' => $lineItem['data']->line_number, 'total_yards' => $lineItem['metadata']['total-yds'] );
                break;
                case 'box-pleated':
                    $typesArr['boxpleatedvalance'][$lineItem['fabricdata']['fabric_name']][$lineItem['fabricdata']['color']][]=array('line_number' => $lineItem['data']->line_number, 'total_yards' => $lineItem['metadata']['total-yds'] );
                break;
                case 'straight-cornice':
                    switch($lineItem['metadata']['cornice-type']){
                        case 'Shaped':
                        case 'Shaped Style A':
                        case 'Shaped Style B':
                        case 'Shaped Style C':
                        case 'Straight':
                            $typesArr['cornice'][$lineItem['fabricdata']['fabric_name']][$lineItem['fabricdata']['color']][]=array('line_number' => $lineItem['data']->line_number, 'total_yards' => $lineItem['metadata']['total-yds'] );
                        break;
                    }
                break;
                case 'pinch-pleated':
                    $typesArr['pinchpleated'][$lineItem['fabricdata']['fabric_name']][$lineItem['fabricdata']['color']][]=array('line_number' => $lineItem['data']->line_number, 'total_yards' => $lineItem['metadata']['total-yds'] );
                break;
            }
        break;
    }
    
}

//echo "<pre>"; print_r($typesArr); echo "</pre>";exit;
    
    foreach($typesArr as $type => $data){
        if(count($data) > 0){
            echo "<div nobr=\"true\" style=\"margin:0px 0px 0px 0px; padding:0px 0px 0px 0px; line-height:1;\"><h2 style=\"font-size:16px;font-weight:bold;margin:0px 0px 0px 0px;\">";
            switch($type){
                case 'catchall':
                    echo 'Miscellaneous';
                break;
                case 'bedspread':
                    echo 'Bedding';
                break;
                case 'cubiclecurtain':
                    echo 'Cubicle Curtains';
                break;
                case 'boxpleatedvalance':
                    echo 'SWT Valance';
                break;
                case 'cornice':
                    echo 'SWT Cornice';
                break;
                case 'pinchpleated':
                    echo 'SWT Drapery';
                break;
                case 'swtmisc':
                    echo "SWT Misc";
                break;
            }
            echo "</h2><table width=\"100%\" cellpadding=\"3\" cellspacing=\"0\" border=\"1\" bordercolor=\"#000000\" style=\"margin:0px 0px 0px 0px;\">
            <thead>
            <tr bgcolor=\"#000000\">
            <th style=\"color:#FFFFFF;text-align:center;\">Line #</th>
            <th style=\"color:#FFFFFF;text-align:left;\">Fabric</th>
            <th style=\"color:#FFFFFF;text-align:left;\">Color</th>
            <th style=\"color:#FFFFFF;text-align:center;\">Total Yards</th>
            </tr>
            </thead>
            <tbody>";
            foreach($data as $fabricName => $colorLines){
                foreach($colorLines as $colorName => $lines){
                    if(strlen(trim($fabricName)) > 0){
                        foreach($lines as $lineData){
                            echo "<tr>
                            <td style=\"text-align:center;\">".$lineData['line_number']."</td>
                            <td style=\"text-align:left;\">".$fabricName."</td>
                            <td style=\"text-align:left;\">".$colorName."</td>
                            <td style=\"text-align:center;\">".round(floatval($lineData['total_yards']),2)."</td>
                            </tr>";
                        }
                    }
                }
            }
            echo "</tbody>
            </table></div>";
        }
    }

?>