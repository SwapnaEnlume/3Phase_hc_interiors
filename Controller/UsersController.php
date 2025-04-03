<?php
// src/Controller/UsersController.php

namespace App\Controller;

use App\Controller\AppController;
use Cake\Event\Event;
use Cake\Core\Configure;
use Cake\ORM\TableRegistry;

class UsersController extends AppController
{

	public function initialize()
    {
        parent::initialize();
        $this->loadComponent('RequestHandler');
		$this->loadComponent('Security');
		
		$this->loadModel('Users');
		$this->loadModel('UserTypes');
		$this->loadModel('Permissions');
    }
	
    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
		if($this->request->action=='getusers' || $this->request->action=='getuserroles' || $this->request->action=='edit'){
			$this->eventManager()->off($this->Csrf);
			$this->eventManager()->off($this->Security);
		}
        $this->Auth->allow(['logout','versiontest']);
    }

     public function index()
     {
        $this->set('users', $this->Users->find('all')->toArray());
    }

     public function versiontest(){
         $this->autoRender=false;
         echo Configure::version();
         exit;
     }

    public function view($id)
    {
        $user = $this->Users->get($id);
        $this->set(compact('user'));
    }

	public function getusers(){
			//regular request
			$conditions=array();
			
			
			if(isset($this->request->data['search']['value']) && strlen(trim($this->request->data['search']['value'])) >0){
				$conditions['OR']=array();
				
				$conditions['OR'] += array("CONCAT(first_name,' ',last_name) LIKE" => '%'.$this->request->data['search']['value'].'%');
				$conditions['OR'] += array('email LIKE' => '%'.$this->request->data['search']['value'].'%');
			}
			
		
			$orderby=array();
			if(isset($this->request->data['order'][0]['column'])){
				switch($this->request->data['order'][0]['column']){
					case 0:
						$orderby += array('last_name'=>$this->request->data['order'][0]['dir'],'first_name' => $this->request->data['order'][0]['dir']);
					break;
					case 1:
						$orderby += array('email' => $this->request->data['order'][0]['dir']);
					break;
					case 4:
						$orderby += array('created' => $this->request->data['order'][0]['dir']);
					break;
					default:
						$orderby += array('last_name' => 'asc', 'first_name' => 'asc');
					break;
				}
			}
		
			$users=array();
			
			$getUsers=$this->Users->find('all',['conditions'=>$conditions,'order'=>$orderby])->offset($this->request->query['start'])->limit($this->request->query['length'])->hydrate(false)->toArray();
			
			$totalRows=$this->Users->find('all',['conditions'=>$conditions])->count();
			foreach($getUsers as $thisuser){
				
				$thisRole=$this->UserTypes->get($thisuser['user_type_id'])->toArray();
				$role=$thisRole['user_type_name'];
				
				$users[]=array(
				'DT_RowId'=>'row_'.$thisuser['id'],
				'DT_RowClass'=>'rowtest',
				'0' => $thisuser['last_name'].', '.$thisuser['first_name'],
				'1' => $thisuser['email'],
				'2' => $role,
				'3' => $thisuser['status'],
				'4' => date('n/j/Y',$thisuser['created']),
				'5' => '<a href="/users/edit/'.$thisuser['id'].'"><img src="/img/edit.png" alt="Edit This User" title="Edit This User" /></a>'
				);
			}
			$this->set('draw',$this->request->query['draw']);
			$this->set('recordsTotal',$totalRows);
			$this->set('recordsFiltered',$totalRows);
			$this->set('data',$users);
			$this->set('_serialize',['draw','recordsTotal','recordsFiltered','data']);
			//"draw":1,"recordsTotal":533,"recordsFiltered":533,"data"
	}	

    public function add()
    {
		$allroles=array();
		$findroles=$this->UserTypes->find('all')->toArray();
		foreach($findroles as $role){
			$allroles[$role['id']]=$role['user_type_name'];
		}
		$this->set('allroles',$allroles);
		
        $user = $this->Users->newEntity();
        if ($this->request->is('post')) {
            $user = $this->Users->patchEntity($user, $this->request->data);
			$user->created=time();
			
            if ($this->Users->save($user)) {
                $this->Flash->success('Successfully added new user "'.$this->request->data['first_name']." ".$this->request->data['last_name'].'"');
				$this->logActivity($_SERVER['REQUEST_URI'],"Added User \"".$this->request->data['first_name']." ".$this->request->data['last_name']."\"");
                return $this->redirect('/users/');
            }
            $this->Flash->error(__('Unable to add the user.'));
        }
        $this->set('user', $user);
    }

	
	
	public function edit($userID){
	
		$allroles=array();
		$findroles=$this->UserTypes->find('all')->toArray();
		foreach($findroles as $role){
			$allroles[$role['id']]=$role['user_type_name'];
		}
		$this->set('allroles',$allroles);
		
		if($this->request->data){
			
			$userTable=TableRegistry::get('Users');
			$thisUser=$userTable->get($userID);
			$thisUser->username=$this->request->data['username'];
			$thisUser->email=$this->request->data['email'];
			
			
			if($this->request->data['changeimage'] == 'yes'){
				$fixedfilename=time()."_".$this->request->data['newimagefile']['name'];
				move_uploaded_file($this->request->data['newimagefile']['tmp_name'],$_SERVER['DOCUMENT_ROOT']."/webroot/img/signatures/".$fixedfilename);
				$thisUser->signature_image = $fixedfilename;
			}
			
			
			if($this->request->data['password'] != ''){
				$thisUser->password=$this->request->data['password'];
			}
			$thisUser->first_name=$this->request->data['first_name'];
			$thisUser->last_name=$this->request->data['last_name'];
			$thisUser->status=$this->request->data['status'];
			$thisUser->user_type_id=$this->request->data['user_type_id'];
			if($userTable->save($thisUser)){
				$this->Flash->success('Successfully saved changes to selected user');
				$this->logActivity($_SERVER['REQUEST_URI'],"Edited User \"".$this->request->data['first_name']." ".$this->request->data['last_name']."\"");
				return $this->redirect('/users/');
			}
			
		}else{
			$thisUser=$this->Users->get($userID)->toArray();
			$this->set('userData',$thisUser);
		}
		
	}
	
	

	public function login(){
		if($this->Auth->user()){
			return $this->redirect('/');
		}
		if ($this->request->is('post')) {
			$user = $this->Auth->identify();
			if ($user) {
				$this->Auth->setUser($user);
				$this->logActivity($_SERVER['REQUEST_URI'],"Logged In");
				return $this->redirect($this->Auth->redirectUrl());
			}
			$this->Flash->error(__('Invalid username or password, try again'));
		}
	}
	
	public function logout()
	{
		$this->logActivity($_SERVER['REQUEST_URI'],"Logged Out");
		return $this->redirect($this->Auth->logout());
	}


	public function setscrolloption($newoption){
		$userTable=TableRegistry::get('Users');
		$thisUser=$userTable->get($this->Auth->user('id'));
		$thisUser->scroll_calcs=$newoption;
		if($userTable->save($thisUser)){
			return $this->redirect($_SERVER['HTTP_REFERER']);
		}
	}

	
	public function roles($subaction=false,$subsubaction=false){
		$this->autoRender=false;
		switch($subaction){
			case "add":
				if($this->request->data){
					
					$roleTable=TableRegistry::get('UserTypes');
					$newRole=$roleTable->newEntity();
					$newRole->user_type_name = $this->request->data['role_name'];
					if($roleTable->save($newRole)){
						$this->Flash->success('Created new role '.$newRole->user_type_name);
						$this->logActivity($_SERVER['REQUEST_URI'],"Added User Role \"".$this->request->data['role_name']."\"");
						return $this->redirect('/users/roles/permissions/'.$newRole->id);
					}
					
				}else{
					$this->render('addrole');
				}
			break;
			case "delete":
				if($this->request->data){
					
				}else{
					
				}
			break;
			case "permissions":
				if($this->request->data){
					//save changes
					$allPermissions=$this->Permissions->find('all',['order'=>['controller'=>'asc','action'=>'asc']])->toArray();
					$newpermissionvalue=array();
					
					foreach($allPermissions as $permission){
						$newpermissionvalue[$permission['slug']] = $this->request->data["permission_".$permission['id']];
					}
					
					$roleTable=TableRegistry::get('UserTypes');
					$thisRole=$roleTable->get($subsubaction);
					$thisRole->permissions=json_encode($newpermissionvalue);
					if($roleTable->save($thisRole)){
						$this->Flash->success('Successfully saved permissions for '.$thisRole->user_type_name);
						$this->logActivity($_SERVER['REQUEST_URI'],"Changed Role Permissions for role \"".$thisRole->user_type_name."\"");
						return $this->redirect('/users/roles/');
					}
					
				}else{
					$allPermissions=$this->Permissions->find('all',['order'=>['controller'=>'asc','action'=>'asc']])->toArray();
					$this->set('allPermissions',$allPermissions);
					
					$thisRole=$this->UserTypes->get($subsubaction)->toArray();
					$this->set('thisRole',$thisRole);
					
					$this->render('permissions');
				}
			break;				
			default:
				$this->render('roles');
			break;
		}
		
	}

	
	
	public function xlspermissions($roleID){
	    
	    $this->autoRender=false;
	    require($_SERVER['DOCUMENT_ROOT']."/vendor/phpoffice/phpexcel/Classes/PHPExcel.php");
        require($_SERVER['DOCUMENT_ROOT']."/vendor/phpoffice/phpexcel/Classes/PHPExcel/Writer/Excel2007.php");
            
    	$objPHPExcel = new \PHPExcel();
        $objPHPExcel->setActiveSheetIndex(0);
        
        $objPHPExcel->getActiveSheet()->SetCellValue('A1', 'ID#');
        $objPHPExcel->getActiveSheet()->SetCellValue('B1', 'Controller');
		$objPHPExcel->getActiveSheet()->SetCellValue('C1', 'Action');
		$objPHPExcel->getActiveSheet()->SetCellValue('D1', 'Description');
		$objPHPExcel->getActiveSheet()->SetCellValue('E1', 'Granted?');
			
	    $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(15);
	    $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(15);
		$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(25);
		$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(80);
		$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(15);
		
		$rowCount=2;
		
	    $allPermissions=$this->Permissions->find('all',['order'=>['controller'=>'asc','action'=>'asc']])->toArray();
		
		$thisRole=$this->UserTypes->get($roleID)->toArray();
		
		foreach($allPermissions as $permission){
		    
		    $thisRolePerms=json_decode($thisRole['permissions'],true);
		    
		    $objPHPExcel->getActiveSheet()->SetCellValue('A'.$rowCount, $permission['id']);
		    $objPHPExcel->getActiveSheet()->SetCellValue('B'.$rowCount, $permission['controller']);
			$objPHPExcel->getActiveSheet()->SetCellValue('C'.$rowCount, $permission['action']);
			$objPHPExcel->getActiveSheet()->SetCellValue('D'.$rowCount, $permission['description']);
			    
			if(isset($thisRolePerms[$permission['slug']])){
			    if($thisRolePerms[$permission['slug']] == '1'){
			        $objPHPExcel->getActiveSheet()->SetCellValue('E'.$rowCount, 'YES');
			    }elseif($thisRolePerms[$permission['slug']] == '0'){
			        $objPHPExcel->getActiveSheet()->SetCellValue('E'.$rowCount, 'NO');
			    }
		    }else{
		        $objPHPExcel->getActiveSheet()->SetCellValue('E'.$rowCount, 'NOT SET');
		    }
		    
		    $rowCount++;
		}
		
		// Redirect output to a client’s web browser (Excel5)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Role Permissions - '.$thisRole['user_type_name'].'.xlsx"');
        header('Cache-Control: max-age=0');
        // If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');
        
        // If you're serving to IE over SSL, then the following may be needed
        header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
        header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header ('Pragma: public'); // HTTP/1.0
        
        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save('php://output');
	   
	}
	
	
	
	
	public function getuserroles(){
		
		$usertypes=array();
			
		$getUserTypes=$this->UserTypes->find('all')->hydrate(false)->toArray();

		$totalRows=$this->UserTypes->find('all')->count();
		
		foreach($getUserTypes as $thisusertype){

			$usersThisRole=$this->Users->find('all',['conditions'=>['user_type_id' => $thisusertype['id']]])->count();
			
			if($usersThisRole == 0){
				$ifdelete = '';//' &nbsp; <a href="/users/roles/delete/'.$thisusertype['id'].'/"><img src="/img/delete.png" alt="Delete Role" title="Delete Role" /> Delete Role</a>';
			}else{
				$ifdelete = '';
			}
			
			$usertypes[]=array(
			'DT_RowId'=>'row_'.$thisusertype['id'],
			'DT_RowClass'=>'rowtest',
			'0' => $thisusertype['user_type_name'],
			'1' => $usersThisRole,
			'2' => '<a href="/users/roles/permissions/'.$thisusertype['id'].'"><img src="/img/permissions.png" alt="Edit Permissions" title="Edit Permissions" /> Edit Permissions</a>'.$ifdelete
			);
		}
		$this->set('draw',$this->request->query['draw']);
		$this->set('recordsTotal',$totalRows);
		$this->set('recordsFiltered',$totalRows);
		$this->set('data',$usertypes);
		$this->set('_serialize',['draw','recordsTotal','recordsFiltered','data']);
		
	}
	
}