<?php
// src/Model/Table/QuotesTable.php
namespace App\Model\Table;

use Cake\ORM\Table;

class QuotesTable extends Table
{

    public function initialize(array $config)
    {
        $this->belongsTo('Customers',['foreignKey'=> 'customer_id']);
		$this->hasMany('QuoteLineItems',['foreignKey' => 'quote_id','bindingKey' => 'id']);
		
	}
}