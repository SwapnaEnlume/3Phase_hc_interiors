<?php
// src/Model/Table/OrderItemStatusTable.php
namespace App\Model\Table;

use Cake\ORM\Table;

class WorkOrderItemStatusTable extends Table
{

    public function initialize(array $config)
    {
        $this->belongsTo('OrderItems',['foreignKey'=>'order_item_id']);
	}
}