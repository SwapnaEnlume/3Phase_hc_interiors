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
class LibrariesController extends AppController
{
	
	public function initialize()
    {
        parent::initialize();
        $this->loadComponent('RequestHandler');
		$this->loadComponent('Security');
		$this->loadModel('UserTypes');
		$this->loadModel('Users');
		$this->loadModel('Customers');
		$this->loadModel('Fabrics');
		$this->loadModel('Calculators');
		$this->loadModel('Assemblies');
		$this->loadModel('Bedspreads');
		$this->loadModel('BedspreadSizes');
		$this->loadModel('BsDataMap');
		$this->loadModel('CcDataMap');
		$this->loadModel('CubicleCurtains');
		$this->loadModel('CubicleCurtainSizes');
		$this->loadModel('MiscellaneousProducts');
		$this->loadModel('Services');
		$this->loadModel('TrackSystems');
		$this->loadModel('WindowTreatments');
		$this->loadModel('WindowTreatmentSizes');
		$this->loadModel('WtDataMap');
		$this->loadModel('Vendors');
		$this->loadModel('Linings');
		$this->loadModel('LibraryImages');
		$this->loadModel('LibraryCategories');
	}
	
		
	public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
		
		
		if($this->request->action=='getimglist'){
			$this->eventManager()->off($this->Csrf);
			$this->eventManager()->off($this->Security);
		}
		
    }
	
	
	public function index(){
		
	}
	
	
	public function image($cmd='',$itemid=''){
		$this->autoRender=false;
		
		$allcategories=array();
		$lookupCategories=$this->LibraryCategories->find('all',['order'=>['category_title'=>'asc']])->toArray();
		foreach($lookupCategories as $cat){
			$allcategories[$cat['category_title']]=$cat['category_title'];
		}
		$this->set('allcategories',$allcategories);
		
		switch($cmd){
			case "add":
				if($this->request->data){
					
					
					$filename=$this->request->data['imagefile']['name'];
					$filename=str_replace(",","",$filename);
					$filename=str_replace("!","",$filename);
					$filename=str_replace("?","",$filename);
					$filename=str_replace("#","",$filename);
					$filename=str_replace("@","",$filename);
					$filename=str_replace('$',"",$filename);
					$filename=str_replace('%',"",$filename);
					$filename=str_replace("^","",$filename);
					$filename=str_replace("&","",$filename);
					$filename=str_replace("*","",$filename);
					$filename=str_replace("+","",$filename);
					$filename=str_replace("`","",$filename);
					$filename=str_replace("~","",$filename);
					$filename=str_replace("<","",$filename);
					$filename=str_replace(">","",$filename);
					$filename=str_replace("/","",$filename);
					$filename=str_replace(";","",$filename);
					$filename=str_replace(":","",$filename);
					$filename=str_replace("'","",$filename);
					$filename=str_replace('"',"",$filename);
					$filename=str_replace("{","",$filename);
					$filename=str_replace("}","",$filename);
					$filename=str_replace("[","",$filename);
					$filename=str_replace("]","",$filename);
					$filename=str_replace(" ","_",$filename);
					$filename=time()."_".$filename;
					
					
					$allowedTypes=array('.jpg','jpeg','.png','.gif');
					if(!in_array(strtolower(substr($this->request->data['imagefile']['name'],-4)),$allowedTypes)){
					    $this->autoRender=false;
					    echo '<h3><span style="color:red;">ERROR:</span> This filetype is not allowed.</h3>
                        <p><button type="button" onclick="history.go(-1);">Go Back</button></p>';
					    exit;
					}
					
					
					move_uploaded_file($this->request->data['imagefile']['tmp_name'],$_SERVER['DOCUMENT_ROOT']."/webroot/img/library/".$filename);
					
					
					$imgsTable=TableRegistry::get('LibraryImages');
					$newImage=$imgsTable->newEntity();
					$newImage->image_title = $this->request->data['image_title'];
					$newImage->categories=$this->request->data['categories'];
					$newImage->filename=$filename;
					$newImage->time=time();
					$newImage->added_by = $this->Auth->user('id');
					$newImage->tags=$this->request->data['tags'];
					$newImage->status=$this->request->data['status'];
					if($imgsTable->save($newImage)){
						
						$this->logActivity($_SERVER['REQUEST_URI'],'Added new image "'.$this->request->data['image_title'].'" to library.');
						$this->Flash->success('Successfully added image to library');
						return $this->redirect('/libraries/image/');
						
					}
					
				}else{
					
					$this->render('addimage');
				}
			break;
			case "edit":
				$thisImage=$this->LibraryImages->get($itemid)->toArray();
				$this->set('imageData',$thisImage);
				if($this->request->data){
					//save changes
					
					$imgTable=TableRegistry::get('LibraryImages');
					$imgChange=$imgTable->get($thisImage['id']);
					$imgChange->image_title = $this->request->data['image_title'];
					$imgChange->categories = $this->request->data['categories'];
					$imgChange->tags = $this->request->data['tags'];
					$imgChange->status = $this->request->data['status'];
					if($imgTable->save($imgChange)){
						$this->logActivity($_SERVER['REQUEST_URI'],'Saved changes to image "'.$this->request->data['image_title'].'" in library.');
						$this->Flash->success('Successfully saved changes to selected library image');
						return $this->redirect('/libraries/image/');
					}
					
				}else{
					$this->render('editimage');
				}
			break;
			case "delete":
				$thisImage=$this->LibraryImages->get($itemid)->toArray();
				$this->set('imageData',$thisImage);
				if($this->request->data){
					//do the delete
					
					@unlink($_SERVER['DOCUMENT_ROOT']."/webroot/img/library/".$thisImage['filename']);
					$imgTable=TableRegistry::get('LibraryImages');
					$imgDelete=$imgTable->get($thisImage['id']);
					if($imgTable->delete($imgDelete)){
						$this->logActivity($_SERVER['REQUEST_URI'],'Deleted image "'.$thisImage['image_title'].'" from library.');
						$this->Flash->success('Successfully deleted selected image from library');
						return $this->redirect('/libraries/image/');
					}
					
				}else{
					$this->render('deleteimage');
				}
			break;
			default:
				$this->render('image');
			break;
		}
	}
	
	
	public function getimglist($status='active'){
		$images=array();
		$overallTotalRows=$this->LibraryImages->find()->count();
		
		if($status == 'all'){
		    $conditions=array('status !=' => 'Single Use','OR'=>array());
		}elseif($status=='active'){
		    $conditions=array('status NOT IN' => array('Inactive','Single Use'),'OR'=>array());
		}
		
		if(isset($this->request->data['search']['value']) && strlen(trim($this->request->data['search']['value'])) >0){
			//this is a search
			$conditions['OR']=array();
			$conditions['OR'] += array('image_title LIKE'=>'%'.trim($this->request->data['search']['value']).'%');
			$conditions['OR'] += array('filename LIKE'=>'%'.trim($this->request->data['search']['value']).'%');
			$conditions['OR'] += array('tags LIKE'=>'%'.trim($this->request->data['search']['value']).'%');
			$conditions['OR'] += array('categories LIKE'=>'%'.trim($this->request->data['search']['value']).'%');
		}
		
		
		if($status=='all'){
		    $imagefind=$this->LibraryImages->find('all',['conditions'=>$conditions,'order'=>['id'=>'asc']])->hydrate(false)->toArray();
		}else{
		    $imagefind=$this->LibraryImages->find('all',['conditions'=>$conditions,'order'=>['id'=>'asc']])->offset($this->request->data['start'])->limit($this->request->data['length'])->hydrate(false)->toArray();
		}
		$totalFilteredRows=$this->LibraryImages->find('all',['conditions'=>$conditions])->count();
		
		foreach($imagefind as $image){
			
			$thumb="<a href=\"/img/library/".$image['filename']."\" class=\"fancybox\"><img src=\"/img/library/".$image['filename']."\" width=\"85\" /></a>";
			$mime=mime_content_type($_SERVER['DOCUMENT_ROOT']."/webroot/img/library/".$image['filename']);
			$categories=$image['categories'];
			$tags=str_replace(",","</li><li>",$image['tags']);
			
			$images[]=array(
				'DT_RowId'=>'row_'.$image['id'],
				'DT_RowClass'=>'rowtest',
				'0' => $image['id'],
				'1' => $thumb,
				'2' => '<h3>'.$image['image_title'].'</h3>',
				'3' => strtoupper(str_replace("image/","",$mime)),
				'4' => $categories,
				'5' => "<ul><li>".$tags."</li></ul>",
				'6' => $image['status'],
				'7' => '<a href="/libraries/image/edit/'.$image['id'].'/"><img src="/img/edit.png" alt="Edit This Image" title="Edit This Image" /></a> <a href="/libraries/image/delete/'.$image['id'].'/"><img src="/img/delete.png" alt="Delete This Image" title="Delete This Image" /></a>'
			);
		}
		
		$this->set('draw',$this->request->data['draw']);
		$this->set('recordsTotal',$overallTotalRows);
		$this->set('recordsFiltered',$totalFilteredRows);
		$this->set('data',$images);
		$this->set('_serialize',['draw','recordsTotal','recordsFiltered','data']);
	}
}