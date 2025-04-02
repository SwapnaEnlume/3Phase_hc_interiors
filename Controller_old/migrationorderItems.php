<?php
$connect = mysqli_connect("localhost", "enlhcinteriors", "Enlume@12345%", "enlhcinteriors_enlhcint_hcidev_cphp1_back");  

$qutoesSql = "SELECT * FROM 'quotes' WHERE created < '1672358400' and created > '1669982400'";  
$quotesResult = mysqli_query($connect, $qutoesSql);  
if($quotesResult->num_rows > 0) {
   while($row = $quotesResult->fetch_assoc()) {
    $orderSql = "SELECT id FROM orders where quote_id=".$row['quote_id'];  
    $orderResult = mysqli_query($connect, $orderSql);  
    $orderID = $orderResult[0];
    
    $query = "insert into order_line_items('type'  'lineitem','quote_id','order_id','status', 'product_type' 'product_class','product_subclass  'product_id' , 'title','description'','best_price','qty' ,'lineitemtype' ,'room_number' , 'line_number' ,'internal_line' ,
    'enable_tally','sortorder' ,'unit' , 'tier_adjusted_price','install_adjusted_price' ,'rebate_adjusted_price' , 'extended_price', 'override_active' ,  'override_price' ,'parent_line', 'calculator_used','revised_from_line' ,'pmi_adjusted' ,'project_management_surcharge_adjusted' ) values($row['type']  $row['lineitem’],$row['quote_id'],$orderID,$row['status'], $row['product_type'], $row['product_class'],$row['product_subclass’]$row['product_id'] , $row['title'],$row['description']’,$row['best_price'],$row['qty'] ,$row['lineitemtype' ],$row['room_number'], $row['line_number'] ,$row['internal_line'] , $row['enable_tally'],$row['sortorder'] ,$row['unit' , $row['tier_adjusted_price'],$row['install_adjusted_price'] ,$row['rebate_adjusted_price'] , $row['extended_price'],$row[ 'override_active'] , $row[ 'override_price'] ,$row['parent_line'], $row['calculator_used'],$row['revised_from_line'] ,$row['pmi_adjusted'] ,$row['project_management_surcharge_adjusted']
    )";
    mysqli_query($connect, $query);
  } 
}
?>