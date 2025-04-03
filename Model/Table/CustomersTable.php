<?php
// src/Model/Table/CusotmersTable.php
namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;

class CustomersTable extends Table
{

    public function validationDefault(Validator $validator)
    {
        return $validator
            ->notEmpty('company_name', 'Company Name is required')
            ->notEmpty('company_city', 'Company City is required')
            ->notEmpty('company_phone', 'Company Phone is required')
			->notEmpty('contact_email','Contact Email Address is required');
    }
	
	public function initialize(array $config)
    {
        $this->hasMany('Quotes');
	}

}