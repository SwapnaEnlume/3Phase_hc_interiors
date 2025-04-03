<?php
// src/Model/Table/QuoteLineItemMetaTable.php
namespace App\Model\Table;

use Cake\ORM\Table;

class QuoteLineItemMetaTable extends Table
{

    public function initialize(array $config)
    {
        $this->belongsTo('QuoteLineItems',['foreignKey'=>'quote_item_id']);
	}
}