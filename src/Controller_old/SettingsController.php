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
use Cake\Core\Plugin;

/**
 * Static content controller
 *
 * This controller will render views from Template/Pages/
 *
 * @link http://book.cakephp.org/3.0/en/controllers/pages-controller.html
 */
class SettingsController extends AppController{
	public function initialize(){
        parent::initialize();
        $this->loadComponent('RequestHandler');
		
		$this->loadModel('Settings');
		$this->loadModel('FabricMarkupRules');
		$this->loadModel('QuoteTypes');
		
	}
	
		
	public function beforeFilter(Event $event){
        parent::beforeFilter($event);		
		if($this->request->action=='getquotetypeslist'){
			$this->eventManager()->off($this->Csrf);
			$this->eventManager()->off($this->Security);
		}
    }
	
	public function index(){
		$allsettings=$this->Settings->find('all')->toArray();
		
		if($this->request->data){
			$settingTable=TableRegistry::get('Settings');
			foreach($allsettings as $setting){
				if(isset($this->request->data[$setting['setting_key']]) && $this->request->data[$setting['setting_key']] != $setting['setting_value']){
					$thisSetting=$settingTable->get($setting['id']);
					$thisSetting->setting_value=$this->request->data[$setting['setting_key']];
					$settingTable->save($thisSetting);
				}
			}
			$this->Flash->success('Successfully updated Settings');
			return $this->redirect('/settings/?t='.time());
			
		}else{
			
			$this->set('allsettings',$allsettings);
		}
		
	}


	public function markuprules(){
		$rules=$this->FabricMarkupRules->find('all',['order'=>['yds_range_low' =>'asc', 'price_range_low' => 'asc']])->toArray();
		$calcs=$this->FabricMarkupRules->find('all')->group('calculator')->toArray();
		$this->set('rulecalculators',$calcs);
		
		$this->set('allrules',$rules);
		if($this->request->data){
			//save changes
			$ruleTable=TableRegistry::get('FabricMarkupRules');

			$this->autoRender=false;
			foreach($rules as $rulerow){
				$ruleEntry=$ruleTable->get($rulerow['id']);
				$ruleEntry->price_range_low=$this->request->data["rulerow_".$rulerow['id']."_price_range_low"];
				$ruleEntry->price_range_high=$this->request->data["rulerow_".$rulerow['id']."_price_range_high"];
				$ruleEntry->yds_range_low=$this->request->data["rulerow_".$rulerow['id']."_yds_range_low"];
				$ruleEntry->yds_range_high=$this->request->data["rulerow_".$rulerow['id']."_yds_range_high"];
				$ruleEntry->range_markup=$this->request->data["rulerow_".$rulerow['id']."_markup"];
				$ruleTable->save($ruleEntry);
			}
			$this->Flash->success('Saved changes to Markup Rules');
			return $this->redirect('/settings/markuprules/'.time().'/');

		}else{
			//load form
		}
	}
	
	
	public function quotetypes($action,$sub=false){
	    switch($action){
	       case 'add':
	            $this->autoRender=false;
	            if($this->request->data){
	                $newType=$this->QuoteTypes->newEntity();
	                $newType->type_label = $this->request->data['type_label'];
	                if($this->QuoteTypes->save($newType)){
	                    $this->Flash->success('Successfully created new Order/Quote Type "'.$this->request->data['type_label'].'"');
	                    $this->logActivity($_SERVER['REQUEST_URI'],'Created order/quote type "'.$this->request->data['type_label'].'"');
	                    return $this->redirect('/settings/quotetypes/');
	                }
	            }else{
	                $this->render('addquotetype');
	            }
	       break;
	       case 'edit':
	           $this->autoRender=false;
	           if($this->request->data){
	               
	               $oldType=$this->QuoteTypes->get($sub)->toArray();
	               
	               $thisType=$this->QuoteTypes->get($sub);
	               $thisType->type_label = $this->request->data['type_label'];
	               if($this->QuoteTypes->save($thisType)){
	                   $this->Flash->success('Successfully saved changes to Order/Quote Type "'.$this->request->data['type_label'].'"');
	                   $this->logActivity($_SERVER['REQUEST_URI'],'Changed order/quote type "'.$oldType['type_label'].'" to "'.$this->request->data['type_label'].'"');
	                   return $this->redirect('/settings/quotetypes/');
	               }
	               
	           }else{
	               $thisType=$this->QuoteTypes->get($sub)->toArray();
	               $this->set('thisType',$thisType);
	               $this->render('editquotetype');
	           }
	       break;
	       case 'delete':
	           $this->autoRender=false;
	           if($this->request->data){
	               $oldType=$this->QuoteTypes->get($sub)->toArray();
	               
	               $thisType=$this->QuoteTypes->get($sub);
	               if($this->QuoteTypes->delete($thisType)){
	                   $this->Flash->success('Successfully deleted Order/Quote Type "'.$oldType['type_label'].'"');
	                   $this->logActivity($_SERVER['REQUEST_URI'],'Deleted order/quote type "'.$oldType['type_label'].'"');
	                   return $this->redirect('/settings/quotetypes/');
	               }
	               
	           }else{
	               $thisType=$this->QuoteTypes->get($sub)->toArray();
	               $this->set('thisType',$thisType);
	               $this->render('confirmdeletequotetype');
	           }
	       break;
	       default:
	           
	       break;
	    }
	}
	
	public function getquotetypeslist(){
	    $types=array();
		$overallTotalRows=$this->QuoteTypes->find()->count();
		
		$conditions=array();
		
		if(isset($this->request->data['search']['value']) && strlen(trim($this->request->data['search']['value'])) >0){
			//this is a search
			$conditions += array('type_label LIKE' => '%'.$this->request->data['search']['value'].'%');
		}
		
		
		$typefind=$this->QuoteTypes->find('all',['conditions'=>$conditions,'order'=>['type_label'=>'asc']])->hydrate(false)->toArray();
		
		$totalFilteredRows=$this->QuoteTypes->find('all',['conditions'=>$conditions])->count();
		
		foreach($typefind as $type){
			
			$deleteCheckOne=$this->Quotes->find('all',['conditions' => ['type_id' => $type['id']]])->toArray();
			$deleteCheckTwo=$this->Orders->find('all',['conditions' => ['type_id' => $type['id']]])->toArray();
			if(count($deleteCheckOne) == 0 && count($deleteCheckTwo) == 0){
			    $canDelete=' <a href="/settings/quotetypes/delete/'.$type['id'].'/"><img src="/img/delete.png" alt="Delete This Order/Quote Type" title="Delete This Order/Quote Type" /></a>';
			}else{
			    $canDelete='';
			}
			
			$types[]=array(
				'DT_RowId'=>'row_'.$type['id'],
				'DT_RowClass'=>'rowtest',
				'0' => $type['type_label'],
				'1' => '<a href="/settings/quotetypes/edit/'.$type['id'].'/"><img src="/img/edit.png" alt="Edit This Order/Quote Type" title="Edit This Order/Quote Type" /></a>'.$canDelete
			);
		}
		
		$this->set('draw',$this->request->data['draw']);
		$this->set('recordsTotal',$overallTotalRows);
		$this->set('recordsFiltered',$totalFilteredRows);
		$this->set('data',$types);
		$this->set('_serialize',['draw','recordsTotal','recordsFiltered','data']);
	}
		
}