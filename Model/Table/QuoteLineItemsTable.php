<?php
// src/Model/Table/QuoteLineItemsTable.php
namespace App\Model\Table;

use Cake\ORM\Table;

class QuoteLineItemsTable extends Table
{

    public function initialize(array $config)
    {
        $this->belongsTo('Quotes',['foreignKey'=>'quote_id']);
		$this->hasMany('QuoteLineItemMeta',['foreignKey' => 'quote_item_id','bindingKey' => 'id']);
	}
}