<?php
// src/Model/Table/OrdersTable.php
namespace App\Model\Table;

use Cake\ORM\Table;

class OrdersTable extends Table
{

    public function initialize(array $config)
    {
		$this->primaryKey('id');
        $this->hasMany('OrderItems');
		$this->belongsTo('Quotes',['className'=>'Orders.Quotes',
					'foreignKey'=> 'quote_id']);
		$this->belongsTo('Customers',['className'=>'Quotes.Customers',
					'foreignKey'=> 'customer_id']);
	}
}