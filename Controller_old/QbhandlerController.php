<?php
// src/Controller/UsersController.php

namespace App\Controller;

use Cake\Datasource\ConnectionManager;
use App\Controller\AppController;
use Cake\Event\Event;
use Cake\Utility\Security;
use Cake\Core\Configure;
use Cake\ORM\TableRegistry;
use Cake\Mailer\Email;
use Cake\ORM\Table;
use Cewi\Excel;
use \Imagick;
use Tinify\Tinify;


class QbhandlerController extends AppController
{

	public function initialize()
    {
        parent::initialize();
        $this->loadComponent('RequestHandler');
		$this->loadComponent('Security');
		$this->loadComponent('Cewi/Excel.Import');
		
		$this->loadModel('Settings');
		$this->loadModel('Users');
		$this->loadModel('UserTypes');
		$this->loadModel('Tiervalues');
		$this->loadModel('Customers');
		$this->loadModel('CustomerContacts');
		$this->loadModel('CcDataMap');
		$this->loadModel('Fabrics');
		$this->loadModel('CubicleCurtainSizes');
		$this->loadModel('CustomerPricingExceptions');
		$this->loadModel('Imageprocessing');
		$this->loadModel('Orders');
		$this->loadModel('Quotes');
		$this->loadModel('QuoteLineItems');
		$this->loadModel('OrderItems');
		$this->loadModel('OrderItemStatus');
		$this->loadModel('SherryCache');
		$this->loadModel('LibraryImages');
    }
	
    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
		$this->Auth->allow(['healthcheck']);
    }


	public function testqbsdk(){
		$this->autoRender=false;
		mail("b747fp@gmail.com","data in",print_r($_REQUEST,true));
	}
	
	public function support(){
		$this->autoRender=false;
		mail("b747fp@gmail.com","data in",print_r($_REQUEST,true));	
	}
}