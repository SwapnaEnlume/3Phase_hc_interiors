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
            $typesArr['catchall'][$lineItem['fabricdata']['fabric_name']][$lineItem['fabricdata']['color']][]=array('line_number' => $lineItem['data']['line_number'], 'total_yards' => (floatval($lineItem['metadata']['yds-per-unit']) * floatval($lineItem['data']->qty) ),'wo_line_number' => $lineItem['data']['wo_line_number'] ,'com'=>$lineItem['metadata']['com-fabric'], 'mom'=>$lineItem['metadata']['mom-fabric'] );
        break;
        
        case 'newcatchall-swtmisc':
            $typesArr['swtmisc'][$lineItem['fabricdata']['fabric_name']][$lineItem['fabricdata']['color']][]=array('line_number' => $lineItem['data']['line_number'], 'total_yards' => (floatval($lineItem['metadata']['yds-per-unit']) * floatval($lineItem['data']->qty) ),'wo_line_number' => $lineItem['data']['wo_line_number'] ,'com'=>$lineItem['metadata']['com-fabric'], 'mom'=>$lineItem['metadata']['mom-fabric'] );
        break;
        
        case 'newcatchall-cubicle':
        case 'cubicle_curtains':
            $typesArr['cubiclecurtain'][$lineItem['fabricdata']['fabric_name']][$lineItem['fabricdata']['color']][]=array('line_number' => $lineItem['data']['line_number'], 'total_yards' => $lineItem['metadata']['total-yds'] ,'wo_line_number' => $lineItem['data']['wo_line_number'],'com'=>$lineItem['metadata']['com-fabric'], 'mom'=>$lineItem['metadata']['mom-fabric'] );
        break;
        
        case 'newcatchall-bedding':
        case 'bedspreads':
            $typesArr['bedspread'][$lineItem['fabricdata']['fabric_name']][$lineItem['fabricdata']['color']][]=array('line_number' => $lineItem['data']['line_number'], 'total_yards' => (floatval($lineItem['metadata']['yds-per-unit']) * floatval($lineItem['data']->qty) ),'wo_line_number' => $lineItem['data']['wo_line_number'] ,'com'=>$lineItem['metadata']['com-fabric'], 'mom'=>$lineItem['metadata']['mom-fabric'] );
        break;
        
        case 'newcatchall-drapery':
            $typesArr['pinchpleated'][$lineItem['fabricdata']['fabric_name']][$lineItem['fabricdata']['color']][]=array('line_number' => $lineItem['data']['line_number'], 'total_yards' => $lineItem['metadata']['total-yds'] ,'wo_line_number' => $lineItem['data']['wo_line_number'],'com'=>$lineItem['metadata']['com-fabric'], 'mom'=>$lineItem['metadata']['mom-fabric'] );
        break;
        
        case 'newcatchall-valance':
            $typesArr['boxpleatedvalance'][$lineItem['fabricdata']['fabric_name']][$lineItem['fabricdata']['color']][]=array('line_number' => $lineItem['data']['line_number'], 'total_yards' => $lineItem['metadata']['total-yds'] ,'wo_line_number' => $lineItem['data']['wo_line_number'],'com'=>$lineItem['metadata']['com-fabric'], 'mom'=>$lineItem['metadata']['mom-fabric'] );
        break;
        
        case 'newcatchall-cornice':
            $typesArr['cornice'][$lineItem['fabricdata']['fabric_name']][$lineItem['fabricdata']['color']][]=array('line_number' => $lineItem['data']['line_number'], 'total_yards' => $lineItem['metadata']['total-yds'],'wo_line_number' => $lineItem['data']['wo_line_number'] ,'com'=>$lineItem['metadata']['com-fabric'], 'mom'=>$lineItem['metadata']['mom-fabric'] );
        break;
        
        case 'window_treatments':
            switch($lineItem['metadata']['wttype']){
                case 'Box Pleated Valance':
                    $typesArr['boxpleatedvalance'][$lineItem['fabricdata']['fabric_name']][$lineItem['fabricdata']['color']][]=array('line_number' => $lineItem['data']['line_number'],'wo_line_number' => $lineItem['data']['wo_line_number'], 'total_yards' => $lineItem['metadata']['total-yds'] ,'com'=>$lineItem['metadata']['com-fabric'], 'mom'=>$lineItem['metadata']['mom-fabric'] );
                break;
                case 'Straight Cornice':
                case 'Shaped Cornice':
                    $typesArr['cornice'][$lineItem['fabricdata']['fabric_name']][$lineItem['fabricdata']['color']][]=array('line_number' => $lineItem['data']['line_number'], 'total_yards' => $lineItem['metadata']['total-yds'],'wo_line_number' => $lineItem['data']['wo_line_number'] ,'com'=>$lineItem['metadata']['com-fabric'], 'mom'=>$lineItem['metadata']['mom-fabric'] );
                break;
                case 'Pinch Pleated Drapery':
                    $typesArr['pinchpleated'][$lineItem['fabricdata']['fabric_name']][$lineItem['fabricdata']['color']][]=array('line_number' => $lineItem['data']['line_number'], 'total_yards' => $lineItem['metadata']['total-yds'] ,'wo_line_number' => $lineItem['data']['wo_line_number'],'com'=>$lineItem['metadata']['com-fabric'], 'mom'=>$lineItem['metadata']['mom-fabric'] );
                break;
            }
        break;
        case 'calculator':
            switch($lineItem['data']->calculator_used){
                case 'cubicle-curtain':
                    $typesArr['cubiclecurtain'][$lineItem['fabricdata']['fabric_name']][$lineItem['fabricdata']['color']][]=array('line_number' => $lineItem['data']->line_number, 'total_yards' => $lineItem['metadata']['total-yds'] ,'wo_line_number' => $lineItem['data']['wo_line_number'],'com'=>$lineItem['metadata']['com-fabric'], 'mom'=>$lineItem['metadata']['mom-fabric'] );
                break;
                case 'bedspread':
                    $typesArr['bedspread'][$lineItem['fabricdata']['fabric_name']][$lineItem['fabricdata']['color']][]=array('line_number' => $lineItem['data']->line_number, 'total_yards' => $lineItem['metadata']['total-yds'] ,'wo_line_number' => $lineItem['data']['wo_line_number'],'com'=>$lineItem['metadata']['com-fabric'], 'mom'=>$lineItem['metadata']['mom-fabric'] );
                break;
                case 'box-pleated':
                    $typesArr['boxpleatedvalance'][$lineItem['fabricdata']['fabric_name']][$lineItem['fabricdata']['color']][]=array('line_number' => $lineItem['data']->line_number, 'total_yards' => $lineItem['metadata']['total-yds'] ,'wo_line_number' => $lineItem['data']['wo_line_number'],'com'=>$lineItem['metadata']['com-fabric'], 'mom'=>$lineItem['metadata']['mom-fabric'] );
                break;
                case 'straight-cornice':
                    switch($lineItem['metadata']['cornice-type']){
                        case 'Shaped':
                        case 'Shaped Style A':
                        case 'Shaped Style B':
                        case 'Shaped Style C':
                        case 'Straight':
                            $typesArr['cornice'][$lineItem['fabricdata']['fabric_name']][$lineItem['fabricdata']['color']][]=array('line_number' => $lineItem['data']->line_number, 'total_yards' => $lineItem['metadata']['total-yds'] ,'wo_line_number' => $lineItem['data']['wo_line_number'],'com'=>$lineItem['metadata']['com-fabric'], 'mom'=>$lineItem['metadata']['mom-fabric'] );
                        break;
                    }
                break;
                case 'pinch-pleated':
                /* PPSASCRUM-56: start */
                case 'new-pinch-pleated':
                /* PPSASCRUM-56: end */
                /* PPSASCRUM-384: start */
                case 'ripplefold-drapery':
                case 'accordiafold-drapery':
                    $typesArr['pinchpleated'][$lineItem['fabricdata']['fabric_name']][$lineItem['fabricdata']['color']][]=array('line_number' => $lineItem['data']->line_number, 'total_yards' => $lineItem['metadata']['total-yds'], 'wo_line_number' => $lineItem['data']['wo_line_number']);
                /* PPSASCRUM-384: end */
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
            <th style=\"color:#FFFFFF;text-align:center;\">WO Line #</th>
            <th style=\"color:#FFFFFF;text-align:center;\">SO Line #</th>
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
                            <td style=\"text-align:center;\">".$lineData['wo_line_number']."</td>
                            <td style=\"text-align:center;\">".$lineData['line_number']."</td>
                             <td style=\"text-align:left;\">";
                            if($lineData['mom'] ==1 && $lineData['com'] ==1){ echo "(M & C) ";} elseif($lineData['com'] ==1) {echo "COM ";} //PPSASCRUM-160
                                echo  $fabricName.
                            "</td>
                            <td style=\"text-align:left;\">".$colorName."</td>
                            <td style=\"text-align:center;\">".round(floatval($lineData['total_yards']),2)."</td>
                            </tr>";
                        }
                    }
                }
            }
            /**PPSASCRUM-13 start **/
            //print_r($lineLiningItems);die();
            foreach($lineLiningItems as $fabricName => $colorLines){
                foreach($colorLines as $colorName => $lines){
                  
                    if($lines['type'] == 'custom' ||$lines['type'] == 'newcatchall-blinds' || $lines['type'] == 'newcatchall-hardware' || $lines['type'] == 'newcatchall-hwtmisc'|| $lines['type'] == 'newcatchall-misc'|| $lines['type'] == 'newcatchall-service'|| $lines['type'] == 'newcatchall-shades'|| $lines['type'] == 'newcatchall-shutters'){
                        $linetype = 'catchall';
                    }
                    if($lines['type'] == 'newcatchall-swtmisc' ){
                        $linetype = 'swtmisc';
                    }
                    if($lines['type'] == 'newcatchall-cubicle' || $lines['type'] =='cubicle_curtains'){
                        $linetype = 'cubicle_curtains';
                    }
                    if($lines['type'] == 'newcatchall-bedding'  || $lines['type'] == 'bedspread'){
                        $linetype = 'bedspread';
                    }
                    if($lines['type'] == 'newcatchall-drapery' ){
                        $linetype = 'pinchpleated';
                    }
                    if($lines['type'] == 'newcatchall-valance' ){
                        $linetype = 'boxpleatedvalance';
                    }
                    if($lines['type'] == 'newcatchall-cornice' ){
                        $linetype = 'cornice';
                    }
                     if($lines['type'] == 'window_treatments' ){
                         if($lines['wttype']=='Box Pleated Valance'){
                             $linetype ='boxpleatedvalance';
                         }elseif($lines['wttype']=='Straight Cornice' || $lines['wttype']=='Shaped Cornice'){
                             $linetype ='cornice';
                         }elseif($lines['wttype']=='Pinch Pleated Drapery'){
                             $linetype ='pinchpleated';
                         }
                    }
                    if($lines['type'] == 'calculator'){
                        if($lines['calculator-used'] == 'cubicle-curtain'){
                            $linetype ='cubiclecurtain';
                        }elseif($lines['calculator-used'] == 'bedspread'){
                            $linetype ='bedspread';
                        }
                        elseif($lines['calculator-used'] == 'box-pleated'){
                            $linetype ='boxpleatedvalance';
                        }
                        elseif($lines['calculator-used'] == 'straight-cornice'){
                            $linetype ='cornice';
                        }
                        /* PPSASCRUM-56: start */
                        // elseif($lines['calculator-used'] == 'pinch-pleated'){
                        /* PPSASCRUM-384: start */
                        elseif($lines['calculator-used'] == 'pinch-pleated' || $lines['calculator-used'] == 'new-pinch-pleated' ||
                               $lines['calculator-used'] == 'ripplefold-drapery' || $lines['calculator-used'] == 'accordiafold-drapery'){
                        /* PPSASCRUM-384: end */
                        /* PPSASCRUM-56: end */
                            $linetype ='pinchpleated';
                        }
                    }
                    
                    if($type == $linetype){
                    if(strlen(trim($lines['fabric_name'])) > 0){
                            echo "<tr>
                            <td style=\"text-align:center;\">".$lines['wo_line_number']."</td>
                            <td style=\"text-align:center;\">".$lines['line_number']."</td>
                            <td style=\"text-align:left;\">".$lines['fabric_name']."</td>
                            <td style=\"text-align:left;\">".$lines['color']."</td>";
                                echo "<td style=\"text-align:center;\">".round(floatval($lines['total'] ),2)."</td>";
                            echo "</tr>";
                    }
                    }
                }
            }
            /**PPSASCRUM-13 end **/
            echo "</tbody>
            </table></div>";
        }
    }

?>