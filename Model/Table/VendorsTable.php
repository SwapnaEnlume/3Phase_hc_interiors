<?php namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;

class VendorsTable extends Table
{
    public function initialize(array $config)
    {
        parent::initialize($config);

        //$this->setTable('vendors');
        $this->primaryKey('id');

        // Define the inverse association with Fabrics
        $this->hasMany('Fabrics', [
            'foreignKey' => 'vendor_id',
        ]);
    }
}
?>
