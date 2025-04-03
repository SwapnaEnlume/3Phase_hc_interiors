<?php 
namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;

class FabricsTable extends Table
{
    public function initialize(array $config)
    {
        parent::initialize($config);

        //$this->setTable('fabrics');
        $this->primaryKey('id');

        // Define the association with Vendors
        
        $this->belongsTo('Vendors', [
            'foreignKey' => 'vendor_id',
            'joinType' => 'INNER',
        ]);
    }
}
?>