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

use App\Controller\AppController;
use Cake\Event\Event;
use Cake\Utility\Security;
use Cake\ORM\TableRegistry;
use Cake\ORM\Table;
use Cake\Mailer\Email;
use Cake\Core\Plugin;

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @link http://book.cakephp.org/3.0/en/controllers.html#the-app-controller
 */
class AdminController extends AppController
{

    /**
     * Initialization hook method.
     *
     * Use this method to add common initialization code like loading components.
     *
     * @return void
     */
    public function initialize() {

        parent::initialize();
		$this->loadComponent('RequestHandler');
		$this->loadComponent('Security');
		
		$this->loadComponent('Flash');
        $this->loadComponent('Auth', [
            'loginRedirect' => [
                'controller' => 'Admin',
                'action' => 'index'
            ],
            'logoutRedirect' => [
                'controller' => 'Users',
                'action' => 'login',
                'home'
            ]
        ]);
		
		$this->loadModel('BluesheetTemplates');
		$this->loadModel('LibraryImages');
		
	}
	
    public function beforeFilter(Event $event)
    {
		parent::beforeFilter($event);
		if($this->request->action=='getbluesheettemplatelist' || $this->request->action == 'bluesheettemplates'){

			$this->Security->config('unlockedActions', ['getbluesheettemplatelist']);

			$this->eventManager()->off($this->Csrf);

			$this->eventManager()->off($this->Security);

		}
    }
	
	public function index(){
		return $this->redirect('/');
	}
	
	
	public function bluesheettemplates($action=false,$subaction=false,$subsubaction=false){

		
		switch($action){
			case "add":
				
				$this->autoRender=false;
				
				if($this->request->data){
					
					$templateTable=TableRegistry::get('BluesheetTemplates');
					$newTemplate=$templateTable->newEntity();
					$newTemplate->template_name = $this->request->data['template_title'];
					$newTemplate->json_preload_layers = $this->request->data['rawjson'];
					$newTemplate->status='Active';
					$newTemplate->createdby=$this->Auth->user('id');
					$newTemplate->createdtime=time();
					$newTemplate->lastmodifiedby=$this->Auth->user('id');
					$newTemplate->lastmodified=time();
					$newTemplate->status=$this->request->data['status'];
					
					$newTemplate->pngdata = $this->request->data['pngdata'];
					
					if($templateTable->save($newTemplate)){
						//create template and save JSON to db
						$this->Flash->success('Successfully added new Blue Sheet Template');
						return $this->redirect('/admin/bluesheettemplates/');
					}
					
				}else{
					$this->render('addbluesheettemplate');
				}
			break;
			case "edit":
				
				$this->autoRender=false;
				
				if($this->request->data){
					
					$templateTable=TableRegistry::get('BluesheetTemplates');
					$thisTemplate=$templateTable->get($subaction);
					
					$thisTemplate->json_preload_layers = $this->request->data['rawjson'];
					$thisTemplate->template_name = $this->request->data['template_title'];
					$thisTemplate->lastmodifiedby = $this->Auth->user('id');
					$thisTemplate->lastmodified=time();
					$thisTemplate->status=$this->request->data['status'];
										
					
					$thisTemplate->pngdata = $this->request->data['pngdata'];
					
					if($templateTable->save($thisTemplate)){
						//save the new raw JSON to the db
						$this->Flash->success('Successfully saved changes to selected Blue Sheet Template');
						return $this->redirect('/admin/bluesheettemplates/');
					}
					
				}else{
					$thisTemplate=$this->BluesheetTemplates->get($subaction)->toArray();
					$this->set('thisTemplate',$thisTemplate);
					$this->render('editbluesheettemplate');	
				}
			break;
			case "delete":
				
				$this->autoRender=false;
				
				if($this->request->data){
					$templateTable=TableRegistry::get('BluesheetTemplates');
					$thisTemplate=$templateTable->get($subaction);
					if($templateTable->delete($thisTemplate)){
						$this->Flash->success('Successfully deleted selected blue sheet template');
						return $this->redirect('/admin/bluesheettemplates/');
					}
				}else{
					$thisTemplate=$this->BluesheetTemplates->get($subaction)->toArray();
					$this->set('thisTemplate',$thisTemplate);
					
					//$pngdata=stream_get_contents($thisTemplate['pngdata']);
					$this->set('pngdata',$thisTemplate['pngdata']);
					
					$this->render('deletebluesheet');
				}
			break;
			default:
				
			break;
		}
		
		
	}

	
	public function getbluesheettemplatelist(){
		
		$templates=array();
		
		$allTemplates=$this->BluesheetTemplates->find('all')->toArray();
		$overallTotalRows=count($allTemplates);
		$totalFilteredRows=$overallTotalRows;
		
		foreach($allTemplates as $template){
			$templates[]=array(
				'DT_RowId'=>'row_'.$template['id'],
				'DT_RowClass'=>'rowtest',
				'0' => $template['template_name'],
				'1' => $template['status'],
				'2' => date('n/j/Y g:iA',$template['lastmodified']),
				'3' => '<a href="/admin/bluesheettemplates/edit/'.$template['id'].'"><img src="/img/edit.png" /></a> <a href="/admin/bluesheettemplates/delete/'.$template['id'].'"><img src="/img/delete.png" /></a>'
			);
		}
		
		if(isset($this->request->data['draw'])){
			$draw=$this->request->data['draw'];
		}else{
			$draw=1;
		}
		
		$this->set('draw',$draw);
		$this->set('recordsTotal',$overallTotalRows);
		$this->set('recordsFiltered',$totalFilteredRows);
		$this->set('data',$templates);
		$this->set('_serialize',['draw','recordsTotal','recordsFiltered','data']);
		
	}
	
	
	public function addimagetotemplate(){
		$this->viewBuilder()->layout('iframeinner');
		$allImages=$this->LibraryImages->find('all')->toArray();
		$this->set('libraryImages',$allImages);
	}
	
	
	
	public function addtexttotemplate(){
		$this->viewBuilder()->layout('iframeinner');
		
		$metaKeys=$this->QuoteLineItemMeta->find('all',['order'=>['meta_key'=>'asc']])->group('meta_key')->toArray();
		$this->set('metaKeys',$metaKeys);
		
	}
}
