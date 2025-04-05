<?php
// src/Model/Table/OrderItemsTable.php
namespace App\Model\Table;

use Cake\ORM\Table;

class WorkOrderItemsTable extends Table
{

    public function initialize(array $config)
    {
        $this->belongsTo('Orders',['foreignKey'=>'order_id']);
		$this->hasMany('OrderItemStatus',['foreignKey' => 'order_item_id','bindingKey' => 'id']);
	}
}