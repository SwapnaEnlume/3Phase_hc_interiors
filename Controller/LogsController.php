<?php
/**
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link      http://cakephp.org CakePHP(tm) Project
 * @since     0.2.9
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 */
namespace App\Controller;

use Cake\Datasource\ConnectionManager;
use App\Controller\AppController;
use Cake\Event\Event;
use Cake\Utility\Security;
use Cake\Core\Configure;
use Cake\ORM\TableRegistry;
use Cake\Mailer\Email;
use Cake\ORM\Table;

/**
 * Static content controller
 *
 * This controller will render views from Template/Pages/
 *
 * @link http://book.cakephp.org/3.0/en/controllers/pages-controller.html
 */
class LogsController extends AppController
{
	
	public function initialize()
    {
        parent::initialize();
        $this->loadComponent('RequestHandler');
		$this->loadComponent('Security');
		$this->loadModel('UserTypes');
		$this->loadModel('Users');
		$this->loadModel('Customers');
		$this->loadModel('Products');
		$this->loadModel('Patterns');
		$this->loadModel('Fabrics');
		$this->loadModel('Calculators');
		$this->loadModel('ActivityLogs');
	}
	
		
	public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
		if($this->request->action=='getactivitylog'){
			$this->eventManager()->off($this->Csrf);
			$this->eventManager()->off($this->Security);
		}
    }
	
	
	public function index(){
		
	}
	
	public function getactivitylog($tab){
		$activities=array();
		
		
		$conditions=array();
		if(isset($this->request->query['search']['value']) && strlen(trim($this->request->query['search']['value'])) >0){
			//this is a search
			$conditions['OR']=array();
			$conditions['OR'] += array('action_label LIKE'=>'%'.trim($this->request->query['search']['value']).'%');
			
			$userIDs=array();
			$userCheck=$this->Users->find('all',['conditions'=>['OR' => ['username LIKE' => '%'.trim($this->request->query['search']['value']).'%', 'CONCAT(first_name,\' \',last_name) LIKE' => '%'.trim($this->request->query['search']['value']).'%', 'email LIKE' => '%'.trim($this->request->query['search']['value']).'%']]])->toArray();
			if(count($userCheck) >0){
				foreach($userCheck as $userRow){
					if(!in_array($userRow['id'],$userIDs)){
						$userIDs[]=$userRow['id'];
					}
				}
			}
			
			if(count($userIDs) > 1){
				$conditions['OR'] += array('user_id IN' => $userIDs);
			}elseif(count($userIDs) == 1){
				$conditions['OR'] += array('user_id' => $userIDs[0]);
			}
			
		}
		
		switch($tab){
			case "all":
			default:
				$getActivity=$this->ActivityLogs->find('all',['conditions' => $conditions,'order'=>['time'=>$this->request->query['order'][0]['dir']]])->offset($this->request->query['start'])->limit($this->request->query['length'])->hydrate(false)->toArray();
				
				$filteredRows=$this->ActivityLogs->find('all',['conditions' => $conditions,'order'=>['time'=>$this->request->query['order'][0]['dir']]])->offset($this->request->query['start'])->count();
				
				$totalRows=$this->ActivityLogs->find()->count();
			break;
			case "memos":
				$getActivity=$this->ActivityLogs->find('all',['conditions'=>['action_label LIKE'=>'%Sent Email Memo%'],'order'=>['time'=>$this->request->query['order'][0]['dir']]])->offset($this->request->query['start'])->limit($this->request->query['length'])->hydrate(false)->toArray();
				$totalRows=$this->ActivityLogs->find('all',['conditions'=>['action_label LIKE'=>'%Sent Email Memo%']])->count();
			break;
			case "transactions":
				$getActivity=$this->ActivityLogs->find('all',['conditions'=>['action_label LIKE'=>'%charge%'],'order'=>['time'=>$this->request->query['order'][0]['dir']]])->offset($this->request->query['start'])->limit($this->request->query['length'])->hydrate(false)->toArray();
				$totalRows=$this->ActivityLogs->find('all',['conditions'=>['action_label LIKE'=>'%charge%']])->count();
			break;
		}
		
		
		
		foreach($getActivity as $activity){
			if($activity['user_id'] >0){
				$thisuser=$this->Users->get($activity['user_id'])->toArray();
				$username=$thisuser['first_name'].' '.$thisuser['last_name'];
			}elseif($activity['user_id']==-1){
				$username='System';
			}
			
			$browserData=$browserData=$this->parse_user_agent($activity['user_agent']);
			
			
			$activities[]=array(
				'DT_RowId'=>'row_'.$activity['id'],
				'DT_RowClass'=>'rowtest',
				'0' => $activity['action_label'],
				'1' => $username,
				'2' => date('n/j/Y - g:i:sA',$activity['time']),
				'3' => $activity['ip_address'],
				'4' => $browserData['browser'].' '.$browserData['version'],
			
				);
		}
		
		$this->set('draw',$this->request->query['draw']);
		$this->set('recordsTotal',$totalRows);
		$this->set('recordsFiltered',$filteredRows);
		$this->set('data',$activities);
		$this->set('_serialize',['draw','recordsTotal','recordsFiltered','data']);
	}
	
}
