<?php
/**
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright (c) Cake Software Foundation, Inc. (http://gecakefoundation.org)
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
class ProductsController extends AppController
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
		$this->loadModel('CustomerPricingExceptions');
		$this->loadModel('WarehouseLocations');
		$this->loadModel('FabricAliases');
		$this->loadModel('ProductClasses');
		$this->loadModel('ProductSubclasses');
		$this->loadModel('QuoteLineItems');
	}
	public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
		$this->Auth->allow(['uploadimagefilefromclipboard']);
		//print_r($this->request);exit;
		if($this->request->action=="getproductclasses" || $this->request->action=="getproductsubclasses" || $this->request->action=="add" || $this->request->action=='getfabricsselectlist' || $this->request->action=="edit" || ($this->request->action=="windowTreatments" && $this->request->pass[0]=="edit") || ($this->request->action=="windowTreatments" && $this->request->pass[0]=="add") || ($this->request->action=="cubicleCurtains" && $this->request->pass[0]=="edit") || ($this->request->action=="cubicleCurtains" && $this->request->pass[0]=="add") || ($this->request->action=="fabrics" && $this->request->pass[0]=="add") || ($this->request->action=="cubicleCurtains" && $this->request->pass[0]=="clone") || ($this->request->action=="bedspreads" && $this->request->pass[0]=="add") || ($this->request->action=="bedspreads" && $this->request->pass[0]=="edit") || ($this->request->action=="bedspreads" && $this->request->pass[0]=="clone") || ($this->request->action=="services" && $this->request->pass[0]=="add") || ($this->request->action=="services" && $this->request->pass[0]=="edit") || ($this->request->action=="windowTreatments" && $this->request->pass[0]=="clone") || $this->request->action=='uploadimagefilefromclipboard' || $this->request->action=='newspecialpricing' || $this->request->action=='lookuprollresults' || $this->request->action=='fabricaliases' || $this->request->action == 'bulkeditfabric'){
			$this->Security->config('unlockedActions', ['add','edit','cubicleCurtains','fabrics','newspecialpricing','lookuprollresults','fabricaliases','bulkeditfabric','getproductclasses','getproductsubclasses']);
			$this->eventManager()->off($this->Csrf);
		}
		if($this->request->action=='getproducts' || $this->request->action=='getfabricsselectlist' || $this->request->action == 'getspecialpricinglist' || $this->request->action == 'newspecialpricing' || $this->request->action=='getserviceslist' || $this->request->action=='gettsslist' || $this->request->action=='getfabricsinventory' || $this->request->action=='getvendorslist' || $this->request->action=='getfabricslist' || $this->request->action == 'getfabricsaliaslist' || $this->request->action == 'getliningslist' || $this->request->action=='getccslist' || $this->request->action=='getbslist' || $this->request->action=='getwtslist' || ($this->request->action=="cubicleCurtains" && $this->request->pass[0]=="edit") || ($this->request->action=="cubicleCurtains" && $this->request->pass[0]=="add") || ($this->request->action=="cubicleCurtains" && $this->request->pass[0]=="clone") || ($this->request->action=="bedspreads" && $this->request->pass[0]=="edit") || ($this->request->action=="windowTreatments" && $this->request->pass[0]=="add") || ($this->request->action=="windowTreatments" && $this->request->pass[0]=="edit") || ($this->request->action=="bedspreads" && $this->request->pass[0]=="add") || ($this->request->action=="bedspreads" && $this->request->pass[0]=="clone") || ($this->request->action=="services" && $this->request->pass[0]=="add") || ($this->request->action=="services" && $this->request->pass[0]=="edit") || ($this->request->action=="windowTreatments" && $this->request->pass[0]=="clone") || $this->request->action=='uploadimagefilefromclipboard' || $this->request->action=='lookuprollresults' || $this->request->action=='fabricaliases' || $this->request->action == 'bulkeditfabric'){
			$this->eventManager()->off($this->Csrf);
			$this->eventManager()->off($this->Security);
		}
    }
	public function index(){
	}
	public function addimage($productID){
		$this->viewBuilder()->layout('ajax');
		if($this->request->data){
			$this->autoRender=false;
			$filename=$this->request->data['primary_image']['name'];
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
			if($productID=="new"){
				//move it to unassigned folder
				move_uploaded_file($this->request->data['primary_image']['tmp_name'],$_SERVER['DOCUMENT_ROOT']."/webroot/files/products/unassigned/".$filename);
				echo "<script>parent.$('ul#imagelist').append('<li><img src=\"/files/products/unassigned/".$filename."\" class=\"productimg\" /><input type=\"hidden\" name=\"assign_image[]\" value=\"".$filename."\" /></li>');parent.$.fancybox.close();parent.recountImages();</script>";
				exit;
			}else{
				//move it to this product's folder
				move_uploaded_file($this->request->data['primary_image']['tmp_name'],$_SERVER['DOCUMENT_ROOT']."/webroot/files/products/".$productID."/".$filename);
			}
		}
	}
	
	public function getproducts(){
		$products=array();
		$overallTotalRows=$this->Products->find()->count();
		$conditions=array();
		if(isset($this->request->data['search']['value']) && strlen(trim($this->request->data['search']['value'])) >0){
			//this is a search
			$conditions['OR']=array();
			$conditions['OR'] += array('product_name LIKE'=>'%'.trim($this->request->data['search']['value']).'%');
		}
		$productfind=$this->Products->find('all',['conditions'=>$conditions,'order'=>$order])->offset($this->request->data['start'])->limit($this->request->data['length'])->hydrate(false)->toArray();
		$totalFilteredRows=$this->Products->find('all',['conditions'=>$conditions])->count();
		foreach($productfind as $product){
			$cats=explode(",",$product['categories']);
			$categories='<ul class="categories">';
			foreach($cats as $cat){
				$thiscat=$this->ProductCategories->get($cat)->toArray();
				$categories .= '<li>'.$thiscat['category_name'].'</li>';
			}
			$categories .= '</ul>';
			$varLowPrice=0;
			$varHighPrice=0;
			$variations = '<div class="variationswrap">';
			$varatts=$this->ProductAttributes->find('all',['conditions'=>['product_id'=>$product['id'],'variation_base'=>1],'order'=>['sort'=>'asc']])->toArray();
			foreach($varatts as $varatt){
				$findVariations=$this->ProductVariations->find('all',['conditions'=>['product_id'=>$product['id'],'attribute_id'=>$varatt['id']],'order'=>['sort'=>'asc']])->toArray();
				$thesevars='<div class="varblock"><b>'.$varatt['title'].' Options:</b> ';;
				foreach($findVariations as $variation){
					if($variation['price'] > 0.00){
						if($variation['price'] > $varHighPrice){
							$varHighPrice=$variation['price'];
						}
						if($varLowPrice > 0.00 && $variation['price'] < $varLowPrice){
							$varLowPrice=$variation['price'];
						}elseif($varLowPrice==0.00){
							$varLowPrice=$variation['price'];
						}
					}
					if($variation['image_file'] != ''){
						$thesevars .= "<img src=\"/files/products/".$product['id']."/".$variation['image_file']."\" width=\"10\" height=\"10\" class=\"optionimg\" />";
					}
					$thesevars .= $variation['title'].", ";
				}
				$variations .= substr($thesevars,0,(strlen($thesevars)-2))."</div>";
			}
			$variations .= '</div>';
			$images='';
			if ($handle = opendir($_SERVER['DOCUMENT_ROOT']."/webroot/files/products/".$product['id'])) {
				while (false !== ($entry = readdir($handle))) {
					if ($entry != "." && $entry != "..") {
						$images .= "<img src=\"/files/products/".$product['id']."/".$entry."\" width=\"30\" height=\"30\" class=\"productimg\" />";
					}
				}
				closedir($handle);
			}
			if($product['price'] == 0.00 && $varLowPrice > 0.00 && $varHighPrice > 0.00){
				$price='$'.number_format($varLowPrice,2,'.',',').'&mdash;$'.number_format($varHighPrice,2,'.',',');
			}else{
				$price='$'.number_format($product['price'],2,'.',',');
			}
			$products[]=array(
				'DT_RowId'=>'row_'.$customer['id'],
				'DT_RowClass'=>'rowtest',
				'0' => $images,
				'1' => '<h3>'.$product['product_name'].'</h3>'.$variations,
				'2' => $categories,
				'3' => $price,
				'4' => $product['status'],
				'5' => '<a href="/products/edit/'.$product['id'].'/"><img src="/img/edit.png" alt="Edit This Product" title="Edit This Product" /></a> <a href="/products/cloneproduct/'.$product['id'].'/"><img src="/img/clone.png" alt="Clone This Product" title="Clone This Product" /></a> <a href="/products/delete/'.$product['id'].'/"><img src="/img/delete.png" alt="Delete This Product" title="Delete This Product" /></a>'
			);
		}
		$this->set('draw',$this->request->data['draw']);
		$this->set('recordsTotal',$overallTotalRows);
		$this->set('recordsFiltered',$totalFilteredRows);
		$this->set('data',$products);
		$this->set('_serialize',['draw','recordsTotal','recordsFiltered','data']);
	}
	
	public function cloneproduct($productID){
		$product=$this->Products->get($productID)->toArray();
		$this->set('productData',$product);
		if($this->request->data){
			//create new product
			$productTable=TableRegistry::get('Products');
			$newClone=$productTable->newEntity();
			$newClone->product_name=$this->request->data['product_name'];
			$newClone->price=$this->request->data['price'];
			$newClone->description=$product['description'];
			$newClone->status='Active';
			$newClone->primary_image=$product['primary_image'];
			$newClone->num_images=$product['num_images'];
			$newClone->categories=$product['categories'];
			if($productTable->save($newClone)){
				$newProductID=$newClone->id;
				@mkdir($_SERVER['DOCUMENT_ROOT']."/webroot/files/products/".$newProductID);
			}
			$attributeTable=TableRegistry::get('ProductAttributes');
			$variationsTable=TableRegistry::get('ProductVariations');
			$attributes=$this->ProductAttributes->find('all',['conditions'=>['product_id'=>$productID]])->toArray();
			foreach($attributes as $attribute){
				//copy for new Product
				$newAttribute=$attributeTable->newEntity();
				$newAttribute->title=$attribute['title'];
				$newAttribute->description=$attribute['description'];
				$newAttribute->product_id=$newProductID;
				$newAttribute->variation_base=$attribute['variation_base'];
				$newAttribute->sort=$attribute['sort'];
				if($attributeTable->save($newAttribute)){
					$newAttributeID=$newAttribute->id;
					//copy all variations in this attribute
					if($attribute['variation_base']==1){
						$variations=$this->ProductVariations->find('all',['conditions'=>['product_id'=>$productID,'attribute_id'=>$attribute['id']]])->toArray();
						foreach($variations as $variation){
							//copy for new Product
							$newVariation=$variationsTable->newEntity();
							$newVariation->title=$variation['title'];
							$newVariation->product_id=$newProductID;
							$newVariation->attribute_id=$newAttributeID;
							$newVariation->sku=$variation['sku'];
							$newVariation->price=$variation['price'];
							$newVariation->description=$variation['description'];
							$newVariation->image_file=$variation['image_file'];
							$newVariation->sort=$variation['sort'];
							if($variationsTable->save($newVariation)){
								$newVariationID=$newVariation->id;
							}
						}
					}
				}
			}
			//copy all images to new product
			if ($handle = opendir($_SERVER['DOCUMENT_ROOT']."/webroot/files/products/".$productID)) {
				while (false !== ($entry = readdir($handle))) {
					if ($entry != "." && $entry != "..") {
						copy($_SERVER['DOCUMENT_ROOT']."/webroot/files/products/".$productID."/".$entry,$_SERVER['DOCUMENT_ROOT']."/webroot/files/products/".$newProductID."/".$entry);
					}
				}
				closedir($handle);
			}
			$this->Flash->success('Successfully cloned product.');
			$this->logActivity($_SERVER['REQUEST_URI'],"Cloned product \"".$product['product_name']."\" to \"".$this->request->data['product_name']."\"");
			return $this->redirect("/products/manage");
		}else{
			//get new name for this product
		}
	}
	
	
	
	public function searchfabricsfield($type,$q){
	    $this->autoRender=false;
	    
	    switch($type){
	        case 'cubicle-curtain':
	           $fabricFind=$this->Fabrics->find('all',['conditions' => ['OR' => ['use_in_cc' => '1', 'use_in_sc' => '1'], 'fabric_name LIKE' => '%'.$q.'%','status'=>'Active'],'order'=>['fabric_name'=>'asc']])->group('fabric_name')->toArray();
	        break;
	        case 'bedspread':
	            $fabricFind=$this->Fabrics->find('all',['conditions' => ['use_in_bs' => '1', 'fabric_name LIKE' => '%'.$q.'%','status'=>'Active'],'order'=>['fabric_name'=>'asc']])->group('fabric_name')->toArray();
	        break;
	        case 'box-pleated':
	            $fabricFind=$this->Fabrics->find('all',['conditions' => ['use_in_window' => '1', 'fabric_name LIKE' => '%'.$q.'%','status'=>'Active'],'order'=>['fabric_name'=>'asc']])->group('fabric_name')->toArray();
	        break;
	        case 'straight-cornice':
	            $fabricFind=$this->Fabrics->find('all',['conditions' => ['use_in_window' => '1', 'fabric_name LIKE' => '%'.$q.'%','status'=>'Active'],'order'=>['fabric_name'=>'asc']])->group('fabric_name')->toArray();
	        break;
	        case 'pinch-pleated':
	        case 'pinch-pleated-new':
	            $fabricFind=$this->Fabrics->find('all',['conditions' => ['use_in_window' => '1', 'fabric_name LIKE' => '%'.$q.'%','status'=>'Active'],'order'=>['fabric_name'=>'asc']])->group('fabric_name')->toArray();
	        break;
	    }
	    
	    
	    $out=array();
	    foreach($fabricFind as $fabric){
	        $out[$fabric['id']]=$fabric['fabric_name'];
	    }
	    echo json_encode($out);
	    exit;
	}
	
	
	public function cubicleCurtains($cmdaction='',$itemid=0){
		$this->autoRender=false;
		$sizes=$this->CubicleCurtainSizes->find('all',['conditions'=>['status'=>'Active'],'order'=>['width'=>'asc','length'=>'asc']])->hydrate(false)->toArray();
		$this->set('sizes',$sizes);
		switch($cmdaction){
			case "default":
			default:
				$this->render('cubicle_curtains');
			break;
			case "add":
				$fabricImages=array();
				$allfabrics=$this->Fabrics->find('all',['conditions'=>['OR'=>['use_in_cc'=>1,'use_in_sc'=>1]]])->toArray();
				foreach($allfabrics as $fabric){
					$fabricImages[$fabric['id']]=$fabric['image_file'];
				}
				$this->set('fabricImages',$fabricImages);
				$fabricsfind=$this->Fabrics->find('all',['conditions'=>['status'=>'Active','OR'=>['use_in_cc'=>1,'use_in_sc'=>1]],'order'=>['fabric_name'=>'asc','color'=>'ASC']])->toArray();
				$thefabrics=array();
				$usedfabrics=array();
				foreach($fabricsfind as $fabric){
					if(!in_array($fabric['fabric_name'],$usedfabrics)){
						$thefabrics[$fabric['fabric_name']]=array();
						$usedfabrics[]=$fabric['fabric_name'];
					}
					$thefabrics[$fabric['fabric_name']][$fabric['id']]=array('color'=>$fabric['color'],'id'=>$fabric['id'],'image'=>$fabric['image_file']);
				}
				$this->set('thefabrics',$thefabrics);
				if($this->request->data){
					$colorsArr=array();
					foreach($this->request->data as $key => $val){
						if(substr($key,0,4)=="use_" && preg_match("#color_#i",$key)){
							$colorexp=explode("_color_",$key);
							$colorsArr[]=$colorexp[1];
						}
					}
					$ccTable=TableRegistry::get('CubicleCurtains');
					$newCC=$ccTable->newEntity();
					$newCC->title=$this->request->data['title'];
					$newCC->status='Active';
					$newCC->description=$this->request->data['description'];
					$newCC->fabric_name=$this->request->data['fabric_name'];
					$newCC->qb_item_code=$this->request->data['qb_item_code'];
					$newCC->available_colors=json_encode($colorsArr);
					$newCC->has_mesh=$this->request->data['has_mesh'];
					if(isset($this->request->data['primary_image']['name']) && strlen(trim($this->request->data['primary_image']['name'])) >0){
						$filename=$this->request->data['primary_image']['name'];
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
						$newfilename=time()."_".$filename;
						$newCC->image_file=$newfilename;
					}
					if($ccTable->save($newCC)){
						//handle image upload
						@mkdir($_SERVER['DOCUMENT_ROOT'].'/webroot/files/cubicle-curtains/'.$newCC->id);
						if(isset($this->request->data['primary_image']['name']) && strlen(trim($this->request->data['primary_image']['name'])) >0){
							move_uploaded_file($this->request->data['primary_image']['tmp_name'],$_SERVER['DOCUMENT_ROOT'].'/webroot/files/cubicle-curtains/'.$newCC->id.'/'.$newfilename);
						}
						$mapModel=TableRegistry::get('CcDataMap');
						foreach($sizes as $size){
							if($this->request->data["size_".$size['id']."_enabled"] == 1){
								//create ccdatamap entry for this enabled size
								$newMapEntry=$mapModel->newEntity();
								$newMapEntry->cc_id=$newCC->id;
								$newMapEntry->size_id=$size['id'];
								$newMapEntry->price=$this->request->data["size_".$size['id']."_price"];
								$newMapEntry->difficulty=$this->request->data["size_".$size['id']."_difficulty"];
								$newMapEntry->weight=$this->request->data["size_".$size['id']."_weight"];
								$newMapEntry->yards=$this->request->data["size_".$size['id']."_yards"];
								$newMapEntry->labor_lf=$this->request->data["size_".$size['id']."_laborlf"];
								$mapModel->save($newMapEntry);
							}
						}
						$this->Flash->success('Successfully added new Cubicle Curtain "'.$this->request->data['title'].'"');
						$this->logActivity($_SERVER['REQUEST_URI'],"Added Cubicle Curtain \"".$this->request->data['title']."\"");
						return $this->redirect('/products/cubicle-curtains/');
					}
				}else{
					$usedfabrics=array();
					$fabrics=array();
					$fabricsfind=$this->Fabrics->find('all',['conditions'=>['status'=>'Active','use_in_cc'=>1],['order'=>['fabric_name'=>'asc']]])->toArray();
					foreach($fabricsfind as $fabric){
						if(!in_array($fabric['fabric_name'],$usedfabrics)){
							$fabrics[$fabric['fabric_name']]=array();
							$usedfabrics[]=$fabric['fabric_name'];
						}
						$fabrics[$fabric['fabric_name']][$fabric['id']]=$fabric['color'];
					}
					$this->set('fabrics',$fabrics);
					$this->render('addcc');
				}
			break;
			case "edit":
				$fabricImages=array();
				$allfabrics=$this->Fabrics->find('all',['conditions'=>['OR'=>['use_in_cc'=>1,'use_in_sc'=>1]]])->toArray();
				foreach($allfabrics as $fabric){
					$fabricImages[$fabric['id']]=$fabric['image_file'];
				}
				$this->set('fabricImages',$fabricImages);
				$fabricsfind=$this->Fabrics->find('all',['conditions'=>['status'=>'Active','OR'=>['use_in_cc'=>1,'use_in_sc'=>1]],'order'=>['fabric_name'=>'ASC','color'=>'ASC']])->toArray();
				$thefabrics=array();
				$usedfabrics=array();
				foreach($fabricsfind as $fabric){
					if(!in_array($fabric['fabric_name'],$usedfabrics)){
						$thefabrics[$fabric['fabric_name']]=array();
						$usedfabrics[]=$fabric['fabric_name'];
					}
					$thefabrics[$fabric['fabric_name']][$fabric['id']]=array('color'=>$fabric['color'],'id'=>$fabric['id'],'image'=>$fabric['image_file']);
				}
				$this->set('thefabrics',$thefabrics);
				$thisCC=$this->CubicleCurtains->get($itemid)->toArray();
				$this->set('ccData',$thisCC);
				if($this->request->data){
					$oldCCData=$this->CubicleCurtains->get($itemid)->toArray();
					//print_r($this->request->data);exit;
					$ccTable=TableRegistry::get('CubicleCurtains');
					$thisCC=$ccTable->get($itemid);
					$mapModel=TableRegistry::get('CcDataMap');
					/*
					$sizesArr=array();
					foreach($sizes as $size){
						if($this->request->data["size_".$size['id']."_enabled"] == 1){
							$sizesArr[]=array('id'=>$size['id'],'price'=>$this->request->data["size_".$size['id']."_price"]);
						}
					}
					*/
					foreach($sizes as $size){
							if($this->request->data["size_".$size['id']."_enabled"] == 1){
								//see if this size combo exists in map model
								$maplookup=$this->CcDataMap->find('all',['conditions'=>['cc_id' => $itemid, 'size_id' => $size['id']]])->toArray();
								if(count($maplookup) == 0){
									//create it
									$newMapEntry=$mapModel->newEntity();
									$newMapEntry->cc_id=$itemid;
									$newMapEntry->size_id=$size['id'];
									$newMapEntry->price=$this->request->data["size_".$size['id']."_price"];
									$newMapEntry->difficulty=$this->request->data["size_".$size['id']."_difficulty"];
									$newMapEntry->weight=$this->request->data["size_".$size['id']."_weight"];
									$newMapEntry->yards=$this->request->data["size_".$size['id']."_yards"];
									$newMapEntry->labor_lf=$this->request->data["size_".$size['id']."_laborlf"];
									$mapModel->save($newMapEntry);
								}else{
									//update it
									foreach($maplookup as $mapFindRow){
										$mapEntry=$mapModel->get($mapFindRow['id']);
										$mapEntry->price=$this->request->data["size_".$size['id']."_price"];
										$mapEntry->difficulty=$this->request->data["size_".$size['id']."_difficulty"];
										$mapEntry->weight=$this->request->data["size_".$size['id']."_weight"];
										$mapEntry->yards=$this->request->data["size_".$size['id']."_yards"];
										$mapEntry->labor_lf=$this->request->data["size_".$size['id']."_laborlf"];
										$mapModel->save($mapEntry);
									}
								}
							}else{
								//if there's an entry in the data map, remove it
								$maplookup=$this->CcDataMap->find('all',['conditions'=>['cc_id' => $itemid, 'size_id' => $size['id']]])->toArray();
								if(count($maplookup) > 0){
									foreach($maplookup as $mapFindRow){
										$this->CcDataMap->delete($this->CcDataMap->get($mapFindRow['id']));
									}
								}
							}
					}
					/*
					$colorsArr=array();
					foreach($this->request->data as $key => $val){
						if(substr($key,0,4)=="use_" && preg_match("#color_#i",$key)){
							$colorexp=explode("_color_",$key);
							$colorsArr[]=$colorexp[1];
						}
					}
					*/
					$colorsArr=array();
					$thisFabricColors=$this->Fabrics->find('all',['conditions' => ['fabric_name' => $this->request->data['fabric_name']]])->toArray();
					foreach($thisFabricColors as $fabcolor){
						if(isset($this->request->data["use_".str_replace(" ","_",$fabcolor['fabric_name'])."_color_".str_replace(" ","_",$fabcolor['color'])]) && $this->request->data["use_".str_replace(" ","_",$fabcolor['fabric_name'])."_color_".str_replace(" ","_",$fabcolor['color'])] == "yes"){
							$colorsArr[]=$fabcolor['color'];
						}
					}
					$thisCC->title=$this->request->data['title'];
					//$thisCC->status='Active';
					$thisCC->qb_item_code=$this->request->data['qb_item_code'];
					$thisCC->description=$this->request->data['description'];
					$thisCC->fabric_id=$this->request->data['fabric_id'];
					//$thisCC->available_sizes=json_encode($sizesArr);
					$thisCC->available_colors=json_encode($colorsArr);
					$thisCC->has_mesh=$this->request->data['has_mesh'];
					if(isset($this->request->data['primary_image']['name']) && strlen(trim($this->request->data['primary_image']['name'])) >0){
						$filename=$this->request->data['primary_image']['name'];
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
						$newfilename=time()."_".$filename;
						@unlink($_SERVER['DOCUMENT_ROOT'].'/webroot/files/cubicle-curtains/'.$thisCC->id.'/'.$oldCCData['image_file']);
						move_uploaded_file($this->request->data['primary_image']['tmp_name'],$_SERVER['DOCUMENT_ROOT'].'/webroot/files/cubicle-curtains/'.$thisCC->id.'/'.$newfilename);
						$thisCC->image_file=$newfilename;
					}
					if($ccTable->save($thisCC)){
						$this->Flash->success('Successfully saved changes to Cubicle Curtain "'.$this->request->data['title'].'"');
						$this->logActivity($_SERVER['REQUEST_URI'],"Edited Cubicle Curtain \"".$this->request->data['title']."\"");
						return $this->redirect('/products/cubicle-curtains/');
					}
				}else{
					$availableSizes=array();
					$availableWidths=array();
					$availableLengths=array();
					$getsizes=$this->CcDataMap->find('all',['conditions'=>['cc_id'=>$itemid]])->toArray();
					foreach($getsizes as $size){
						$thisSize=$this->CubicleCurtainSizes->get($size['size_id'])->toArray();
						$availableSizes[$size['id']]=array(
							'id' => $size['size_id'],
							'price' => $size['price'],
							'difficulty' => $size['difficulty'],
							'labor_lf' => $size['labor_lf'],
							'weight' => $size['weight'],
							'yards' => $size['yards'],
							'width' => $thisSize['width'],
							'length' => $thisSize['length']
							);
						if(!in_array($thisSize['width'],$availableWidths)){
							$availableWidths[]=$thisSize['width'];
						}
						if(!in_array($thisSize['length'],$availableLengths)){
							$availableLengths[]=$thisSize['length'];
						}
					}
					$this->set('availableSizes',$availableSizes);
					$this->set('availableWidths',$availableWidths);
					$this->set('availableLengths',$availableLengths);
					$this->render('editcc');
				}
			break;
			case "delete":
				$thisCC=$this->CubicleCurtains->get($itemid)->toArray();
				$this->set('ccData',$thisCC);
				if($this->request->data){
					if($this->CubicleCurtains->delete($this->CubicleCurtains->get($itemid))){
						$this->Flash->success('Successfully deleted selected Cubicle Curtain');
						$this->logActivity($_SERVER['REQUEST_URI'],"Deleted Cubicle Curtain \"".$thisCC['title']."\"");
						$this->redirect('/products/cubicle-curtains/');
					}
				}else{
					$this->render('deletecc');
				}
			break;
			case "clone":
				$thisCC=$this->CubicleCurtains->get($itemid)->toArray();
				$this->set('ccData',$thisCC);
				$fabricImages=array();
				$allfabrics=$this->Fabrics->find('all')->toArray();
				foreach($allfabrics as $fabric){
					$fabricImages[$fabric['id']]=$fabric['image_file'];
				}
				$this->set('fabricImages',$fabricImages);
				$fabricsfind=$this->Fabrics->find('all',['conditions'=>['status'=>'Active',['OR'=>['use_in_cc'=>1,'use_in_sc'=>1]]],'order'=>['fabric_name'=>'asc']])->toArray();
				$thefabrics=array();
				$usedfabrics=array();
				foreach($fabricsfind as $fabric){
					if(!in_array($fabric['fabric_name'],$usedfabrics)){
						$thefabrics[$fabric['fabric_name']]=array();
						$usedfabrics[]=$fabric['fabric_name'];
					}
					$thefabrics[$fabric['fabric_name']][$fabric['id']]=array('color'=>$fabric['color'],'id'=>$fabric['id'],'image'=>$fabric['image_file']);
				}
				$this->set('thefabrics',$thefabrics);
				$sizes=$this->CubicleCurtainSizes->find('all')->toArray();
				$this->set('allSizes',$sizes);
				if($this->request->data){
					$colorsArr=array();
					foreach($this->request->data as $key => $val){
						if(substr($key,0,4)=="use_" && preg_match("#color_#i",$key)){
							$colorexp=explode("_color_",$key);
							$colorsArr[]=$colorexp[1];
						}
					}
					$ccTable=TableRegistry::get('CubicleCurtains');
					$newCC=$ccTable->newEntity();
					$newCC->title=$this->request->data['title'];
					$newCC->status='Active';
					$newCC->description=$this->request->data['description'];
					$newCC->fabric_name=$this->request->data['fabric_name'];
					$newCC->qb_item_code=$this->request->data['qb_item_code'];
					$docopy=false;
					$newCC->available_colors=json_encode($colorsArr);
					$newCC->has_mesh=$this->request->data['has_mesh'];
					if(isset($this->request->data['primary_image']['name']) && strlen(trim($this->request->data['primary_image']['name'])) >0){
						$filename=$this->request->data['primary_image']['name'];
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
						$newfilename=time()."_".$filename;
						$newCC->image_file=$newfilename;
					}else{
						$newCC->image_file=$thisCC['image_file'];
						$docopy=true;
					}
					if($ccTable->save($newCC)){
						//handle image upload
						@mkdir($_SERVER['DOCUMENT_ROOT'].'/webroot/files/cubicle-curtains/'.$newCC->id);
						if($docopy){
							@copy($_SERVER['DOCUMENT_ROOT'].'/webroot/files/cubicle-curtains/'.$thisCC['id'].'/'.$thisCC['image_file'],$_SERVER['DOCUMENT_ROOT'].'/webroot/files/cubicle-curtains/'.$newCC->id.'/'.$thisCC['image_file']);
						}else{
							if(isset($this->request->data['primary_image']['name']) && strlen(trim($this->request->data['primary_image']['name'])) >0){
								move_uploaded_file($this->request->data['primary_image']['tmp_name'],$_SERVER['DOCUMENT_ROOT'].'/webroot/files/cubicle-curtains/'.$newCC->id.'/'.$newfilename);
							}
						}
						$mapModel=TableRegistry::get('CcDataMap');
						foreach($sizes as $size){
							if($this->request->data["size_".$size['id']."_enabled"] == 1){
								//create ccdatamap entry for this enabled size
								$newMapEntry=$mapModel->newEntity();
								$newMapEntry->cc_id=$newCC->id;
								$newMapEntry->size_id=$size['id'];
								$newMapEntry->price=$this->request->data["size_".$size['id']."_price"];
								$newMapEntry->difficulty=$this->request->data["size_".$size['id']."_difficulty"];
								$newMapEntry->weight=$this->request->data["size_".$size['id']."_weight"];
								$newMapEntry->yards=$this->request->data["size_".$size['id']."_yards"];
								$newMapEntry->labor_lf=$this->request->data["size_".$size['id']."_laborlf"];
								$mapModel->save($newMapEntry);
							}
						}
						$this->Flash->success('Successfully added new Cubicle Curtain "'.$this->request->data['title'].'"');
						$this->logActivity($_SERVER['REQUEST_URI'],"Added Cubicle Curtain \"".$this->request->data['title']."\"");
						return $this->redirect('/products/cubicle-curtains/');
					}
				}else{
					$usedfabrics=array();
					$fabrics=array();
					$fabricsfind=$this->Fabrics->find('all',['conditions'=>['status'=>'Active','use_in_cc'=>1],'order'=>['fabric_name'=>'asc']])->toArray();
					foreach($fabricsfind as $fabric){
						if(!in_array($fabric['fabric_name'],$usedfabrics)){
							$fabrics[$fabric['fabric_name']]=array();
							$usedfabrics[]=$fabric['fabric_name'];
						}
						$fabrics[$fabric['fabric_name']][$fabric['id']]=$fabric['color'];
					}
					$this->set('fabrics',$fabrics);
					$availableSizes=array();
					$getsizes=$this->CcDataMap->find('all',['conditions'=>['cc_id'=>$itemid]])->toArray();
					foreach($getsizes as $size){
						$availableSizes[$size['id']]=array(
							'id' => $size['size_id'],
							'price' => $size['price'],
							'difficulty' => $size['difficulty'],
							'labor_lf' => $size['labor_lf'],
							'weight' => $size['weight'],
							'yards' => $size['yards']
							);
					}
					$this->set('availableSizes',$availableSizes);
					$this->render('clonecc');
				}
			break;
		}
	}
	public function getbslist(){
		$bss=array();
		$overallTotalRows=$this->Bedspreads->find()->count();
		$conditions=array();
		if(isset($this->request->data['search']['value']) && strlen(trim($this->request->data['search']['value'])) >0){
			//this is a search
			$conditions['OR']=array();
			$conditions['OR'] += array('fabric_name LIKE'=>'%'.trim($this->request->data['search']['value']).'%');
			$conditions['OR'] += array('available_colors LIKE'=>'%'.trim($this->request->data['search']['value']).'%');
			$conditions['OR'] += array('title LIKE'=>'%'.trim($this->request->data['search']['value']).'%');
		}
		$order=array();
		if(isset($this->request->data['order'][0]['column'])){
			switch($this->request->data['order'][0]['column']){
				case 1:
				default:
					$order += array('title'=>$this->request->data['order'][0]['dir']);
				break;
			}
		}
		$bsfind=$this->Bedspreads->find('all',['conditions'=>$conditions,'order'=>$order])->offset($this->request->data['start'])->limit($this->request->data['length'])->hydrate(false)->toArray();
		$totalFilteredRows=$this->Bedspreads->find('all',['conditions'=>$conditions])->count();
		foreach($bsfind as $bs){
			$images='';
			if ($handle = opendir($_SERVER['DOCUMENT_ROOT']."/webroot/files/bedspreads/".$bs['id'])) {
				while (false !== ($entry = readdir($handle))) {
					if ($entry != "." && $entry != "..") {
						$images .= "<img src=\"/files/bedspreads/".$bs['id']."/".$entry."\" width=\"75\" height=\"75\" class=\"bsimg\" />";
					}
				}
				closedir($handle);
			}
			//$sizes=json_decode($bs['available_sizes'],true);
			$sizes=$this->BsDataMap->find('all',['conditions'=>['bs_id' => $bs['id']]])->toArray();
			$numsizes=count($sizes);
			$colors=json_decode($bs['available_colors'],true);
			$numcolors=count($colors);
			$price='';
			$lowestPrice=50000;
			$highestPrice=0;
			foreach($sizes as $data){
				if(floatval($data['price']) > $highestPrice){
					$highestPrice=floatval($data['price']);
				}
				if(floatval($data['price']) < $lowestPrice){
					$lowestPrice=floatval($data['price']);
				}
			}
			if($lowestPrice==$highestPrice){
				$price='$'.number_format($lowestPrice,2,'.',',');
			}else{
				if($lowestPrice==50000 && $highestPrice==0){
					$price='N/A';
				}else{
					$price='$'.number_format($lowestPrice,2,'.',',').'&mdash;$'.number_format($highestPrice,2,'.',',');
				}
			}
			//any special pricing for this product?
			$specialPricing=$this->CustomerPricingExceptions->find('all',['conditions'=>['product_type'=>'bs','product_id'=>$bs['id']]])->group('customer_id')->toArray();
			if(count($specialPricing) >0){
				$price  .= "<br><br><img src=\"/img/specialpricing.png\" /><em>".count($specialPricing)." special customer price";
				if(count($specialPricing) > 1){
					$price .= "s";
				}
				$price .= "</em>";
			}
			if($bs['quilted']=='1'){
				$isquilted='<span style="width:16px;height:16px;text-indent:150%;overflow:hidden;display:block;white-space:nowrap; background-image:url(/img/Ok-icon.png); background-size:100% auto">Y</span>';
			}else{
				$isquilted='<span style="width:16px;height:16px;text-indent:150%;overflow:hidden;display:block;white-space:nowrap; background-image:url(/img/delete.png); background-size:100% auto">N</span>';
			}
			$bss[]=array(
				'DT_RowId'=>'row_'.$bs['id'],
				'DT_RowClass'=>'rowtest',
				'0' => $images,
				'1' => "<strong>".$bs['title']."</strong><br><em>".$numsizes." Available Sizes</em>",
				'2' => $bs['fabric_name']."<br><em>".$numcolors." Available Colors</em>",
				'3' => $isquilted,
				'4' => $bs['mattress_size'],
				'5' => $bs['qb_item_code'],
				'6' => $price,
				'7' => $bs['status'],
				'8' => '<a href="/products/bedspreads/edit/'.$bs['id'].'/"><img src="/img/edit.png" alt="Edit This Bedspread" title="Edit This Bedspread" /></a> <a href="/products/specialpricing/bs/'.$bs['id'].'"><img src="/img/specialpricing.png" alt="Manage Special Customer Pricing" title="Manage Special Customer Pricing" /></a> <a href="/products/bedspreads/clone/'.$bs['id'].'/"><img src="/img/clone.png" alt="Duplicate This Bedspread" title="Duplicate This Bedspread" /></a> <a href="/products/bedspreads/delete/'.$bs['id'].'/"><img src="/img/delete.png" alt="Delete This Bedspread" title="Delete This Bedspread" /></a>'
			);
		}
		$this->set('draw',$this->request->data['draw']);
		$this->set('recordsTotal',$overallTotalRows);
		$this->set('recordsFiltered',$totalFilteredRows);
		$this->set('data',$bss);
		$this->set('_serialize',['draw','recordsTotal','recordsFiltered','data']);
	}
	public function gettsslist(){
		$tss=array();
		$overallTotalRows=$this->TrackSystems->find()->count();
		$conditions=array();
		if(isset($this->request->data['search']['value']) && strlen(trim($this->request->data['search']['value'])) >0){
			//this is a search
			$conditions += array('title LIKE' => '%'.$this->request->data['search']['value'].'%');
		}
		$tsfind=$this->TrackSystems->find('all',['conditions'=>$conditions,'order'=>$order])->offset($this->request->data['start'])->limit($this->request->data['length'])->hydrate(false)->toArray();
		$totalFilteredRows=$this->TrackSystems->find('all',['conditions'=>$conditions])->count();
		foreach($tsfind as $ts){
			$images= "<img src=\"/files/track-systems/".$ts['id']."/".$ts['primary_image']."\" width=\"75\" height=\"75\" class=\"tsimg\" />";
			
			$price='$'.number_format($ts['price'],2,'.',',');
			$tss[]=array(
				'DT_RowId'=>'row_'.$ts['id'],
				'DT_RowClass'=>'rowtest',
				'0' => $images,
				'1' => "<strong>".$ts['title']."</strong>",
				'2' => ucfirst($ts['system_or_component']),
				'3' => $ts['description'],
				'4' => $price,
				'5' => $ts['unit'],
				'6' => $ts['status'],
				'7' => '<a href="/products/track-systems/edit/'.$ts['id'].'/"><img src="/img/edit.png" alt="Edit This Track System Component" title="Edit This Track System Component" /></a> <a href="/products/track-systems/clone/'.$ts['id'].'/"><img src="/img/clone.png" alt="Duplicate This Track System Component" title="Duplicate This Track System Component" /></a> <a href="/products/track-systems/delete/'.$ts['id'].'/"><img src="/img/delete.png" alt="Delete This Track System Component" title="Delete This Track System Component" /></a>'
			);
		}
		$this->set('draw',$this->request->data['draw']);
		$this->set('recordsTotal',$overallTotalRows);
		$this->set('recordsFiltered',$totalFilteredRows);
		$this->set('data',$tss);
		$this->set('_serialize',['draw','recordsTotal','recordsFiltered','data']);
	}
	public function getwtslist(){
		$wts=array();
		$overallTotalRows=$this->WindowTreatments->find()->count();
		$conditions=array();
		if(isset($this->request->data['search']['value']) && strlen(trim($this->request->data['search']['value'])) >0){
			//this is a search
			$conditions['OR']=array();
			$conditions['OR'] += array('title LIKE' => '%'.$this->request->data['search']['value'].'%');
			$conditions['OR'] += array('fabric_name LIKE' => '%'.$this->request->data['search']['value'].'%');
		}
		$order=array();
		if(isset($this->request->data['order'][0]['column'])){
			switch($this->request->data['order'][0]['column']){
				case 1:
					$order += array('title'=>$this->request->data['order'][0]['dir']);
				break;
				case 2:
					$order += array('wt_type'=>$this->request->data['order'][0]['dir']);
				break;
				default:
					$order += array('title' => 'asc');
				break;
			}
		}
		$wtfind=$this->WindowTreatments->find('all',['conditions'=>$conditions,'order'=>$order])->offset($this->request->data['start'])->limit($this->request->data['length'])->hydrate(false)->toArray();
		$totalFilteredRows=$this->WindowTreatments->find('all',['conditions'=>$conditions])->count();
		foreach($wtfind as $wt){
			$images='';
			if ($handle = opendir($_SERVER['DOCUMENT_ROOT']."/webroot/files/window-treatments/".$wt['id'])) {
				while (false !== ($entry = readdir($handle))) {
					if ($entry != "." && $entry != "..") {
						$images .= "<img src=\"/files/window-treatments/".$wt['id']."/".$entry."\" width=\"75\" height=\"75\" class=\"wtimg\" />";
					}
				}
				closedir($handle);
			}
			$sizes=$this->WtDataMap->find('all',['conditions'=>['wt_id'=>$wt['id']]])->toArray();
			$numsizes=count($sizes);
			$colors=json_decode($wt['available_colors'],true);
			$numcolors=count($colors);
			$price='';
			$lowestPrice=50000;
			$highestPrice=0;
			foreach($sizes as $size => $data){
				if(floatval($data['price']) > $highestPrice){
					$highestPrice=floatval($data['price']);
				}
				if(floatval($data['price']) < $lowestPrice){
					$lowestPrice=floatval($data['price']);
				}
			}
			if($lowestPrice==50000){
				$price='---';
			}else{
				$price='$'.number_format($lowestPrice,2,'.',',').'&mdash;$'.number_format($highestPrice,2,'.',',');
			}
			if($wt['has_welt']=='1'){
				$haswelts='<img src="/img/Ok-icon.png" width="16" height="16" />';
			}else{
				$haswelts='<img src="/img/delete.png" width="16" height="16" />';
			}
			if($wt['wt_type'] == 'Pinch Pleated Drapery'){
				$lining=$wt['lining_option'];
				$haswelts='---';
			}else{
				$lining='---';
			}
			$wts[]=array(
				'DT_RowId'=>'row_'.$wt['id'],
				'DT_RowClass'=>'rowtest',
				'0' => $images,
				'1' => "<strong>".$wt['title']."</strong><br><em>".$numsizes." Available Sizes</em>",
				'2' => $wt['wt_type'],
				'3' => $wt['fabric_name']."<br><em>".$numcolors." Available Colors</em>",
				'4' => $haswelts,
				'5' => $lining,
				'6' => $wt['qb_item_code'],
				'7' => $price,
				'8' => $wt['status'],
				'9' => '<a href="/products/window-treatments/edit/'.$wt['id'].'/"><img src="/img/edit.png" alt="Edit This Window Treatment" title="Edit This Window Treatment" /></a> <a href="/products/window-treatments/clone/'.$wt['id'].'/"><img src="/img/clone.png" alt="Duplicate This Window Treatment" title="Duplicate This Window Treatment" /></a> <a href="/products/window-treatments/delete/'.$wt['id'].'/"><img src="/img/delete.png" alt="Delete This Window Treatment" title="Delete This Window Treatment" /></a>'
			);
		}
		$this->set('draw',$this->request->data['draw']);
		$this->set('recordsTotal',$overallTotalRows);
		$this->set('recordsFiltered',$totalFilteredRows);
		$this->set('data',$wts);
		$this->set('_serialize',['draw','recordsTotal','recordsFiltered','data']);
	}
	public function getccslist(){
		$ccs=array();
		$overallTotalRows=$this->CubicleCurtains->find()->count();
		$conditions=array();
		if(isset($this->request->data['search']['value']) && strlen(trim($this->request->data['search']['value'])) >0){
			//this is a search
			$conditions['OR']=array();
			$conditions['OR'] += array('fabric_name LIKE'=>'%'.trim($this->request->data['search']['value']).'%');
			$conditions['OR'] += array('available_colors LIKE'=>'%'.trim($this->request->data['search']['value']).'%');
			$conditions['OR'] += array('title LIKE'=>'%'.trim($this->request->data['search']['value']).'%');
		}
		$orderby=array();
		if(isset($this->request->data['order'][0]['column'])){
			switch($this->request->data['order'][0]['column']){
				case 1:
					$orderby += array('fabric_name'=>$this->request->data['order'][0]['dir']);
				break;
				default:
					$orderby += array('id' => 'asc');
				break;
			}
		}
		
		
		
		$ccfind=$this->CubicleCurtains->find('all',['conditions'=>$conditions,'order'=>$orderby])->offset($this->request->data['start'])->limit($this->request->data['length'])->hydrate(false)->toArray();
		$totalFilteredRows=$this->CubicleCurtains->find('all',['conditions'=>$conditions])->count();
		foreach($ccfind as $cc){
			$images='';
			if ($handle = opendir($_SERVER['DOCUMENT_ROOT']."/webroot/files/cubicle-curtains/".$cc['id'])) {
				while (false !== ($entry = readdir($handle))) {
					if ($entry != "." && $entry != "..") {
						$images .= "<img src=\"/files/cubicle-curtains/".$cc['id']."/".$entry."\" width=\"75\" height=\"75\" class=\"ccimg\" />";
					}
				}
				closedir($handle);
			}
			//$sizes=json_decode($cc['available_sizes'],true);
			$sizes=$this->CcDataMap->find('all',['conditions'=>['cc_id'=>$cc['id']]])->toArray();
			$numsizes=count($sizes);
			$colors=json_decode($cc['available_colors'],true);
			$numcolors=count($colors);
			$price='';
			$lowestPrice=50000;
			$highestPrice=0;
			foreach($sizes as $size => $data){
				if(floatval($data['price']) > $highestPrice){
					$highestPrice=floatval($data['price']);
				}
				if(floatval($data['price']) < $lowestPrice){
					$lowestPrice=floatval($data['price']);
				}
			}
			$price='$'.number_format($lowestPrice,2,'.',',').'&mdash;$'.number_format($highestPrice,2,'.',',');
			//any special pricing for this product?
			$specialPricing=$this->CustomerPricingExceptions->find('all',['conditions'=>['product_type'=>'cc','product_id'=>$cc['id']]])->group('customer_id')->toArray();
			if(count($specialPricing) >0){
				$price  .= "<br><br><img src=\"/img/specialpricing.png\" /><em>".count($specialPricing)." special customer price";
				if(count($specialPricing) > 1){
					$price .= "s";
				}
				$price .= "</em>";
			}
			if($cc['has_mesh']=='1'){
				$hasmesh='<span style="width:16px;height:16px;text-indent:150%;overflow:hidden;display:block;white-space:nowrap; background-image:url(/img/Ok-icon.png); background-size:100% auto">Y</span>';
			}else{
				$hasmesh='<span style="width:16px;height:16px;text-indent:150%;overflow:hidden;display:block;white-space:nowrap; background-image:url(/img/delete.png); background-size:100% auto">N</span>';
			}
			$ccs[]=array(
				'DT_RowId'=>'row_'.$cc['id'],
				'DT_RowClass'=>'rowtest',
				'0' => $images,
				'1' => "<strong>".$cc['title']."</strong><br><em>".$numsizes." Available Sizes</em>",
				'2' => $cc['fabric_name']."<br><em>".$numcolors." Available Colors</em>",
				'3' => $hasmesh,
				'4' => $cc['qb_item_code'],
				'5' => $price,
				'6' => $cc['status'],
				'7' => '<a href="/products/cubicle-curtains/edit/'.$cc['id'].'/"><img src="/img/edit.png" alt="Edit This Cubicle Curtain" title="Edit This Cubicle Curtain" /></a> <a href="/products/cubicle-curtains/clone/'.$cc['id'].'/"><img src="/img/clone.png" alt="Duplicate This Cubicle Curtain" title="Duplicate This Cubicle Curtain" /></a> <a href="/products/cubicle-curtains/delete/'.$cc['id'].'/"><img src="/img/delete.png" alt="Delete This Cubicle Curtain" title="Delete This Cubicle Curtain" /></a>'
			);
		}
		$this->set('draw',$this->request->data['draw']);
		$this->set('recordsTotal',$overallTotalRows);
		$this->set('recordsFiltered',$totalFilteredRows);
		$this->set('data',$ccs);
		$this->set('_serialize',['draw','recordsTotal','recordsFiltered','data']);
	}
	public function windowTreatments($cmdaction='',$itemid=0){
		$this->autoRender=false;
		$sizes=$this->WindowTreatmentSizes->find('all',['conditions'=>['status'=>'Active'],'order'=>['length'=>'asc','width'=>'asc']])->hydrate(false)->toArray();
		$this->set('sizes',$sizes);
		switch($cmdaction){
			case "add":
				$fabricImages=array();
				$allfabrics=$this->Fabrics->find('all',['conditions'=>['use_in_window'=>1]])->toArray();
				foreach($allfabrics as $fabric){
					$fabricImages[$fabric['id']]=$fabric['image_file'];
				}
				$this->set('fabricImages',$fabricImages);
				$fabricsfind=$this->Fabrics->find('all',['conditions'=>['status'=>'Active','use_in_window'=>1],'order'=>['fabric_name'=>'asc','color'=>'ASC']])->toArray();
				$thefabrics=array();
				$usedfabrics=array();
				foreach($fabricsfind as $fabric){
					if(!in_array($fabric['fabric_name'],$usedfabrics)){
						$thefabrics[$fabric['fabric_name']]=array();
						$usedfabrics[]=$fabric['fabric_name'];
					}
					$thefabrics[$fabric['fabric_name']][$fabric['id']]=array('color'=>$fabric['color'],'id'=>$fabric['id'],'image'=>$fabric['image_file']);
				}
				$this->set('thefabrics',$thefabrics);
				if($this->request->data){
					$colorsArr=array();
					foreach($this->request->data as $key => $val){
						if(substr($key,0,4)=="use_" && preg_match("#color_#i",$key)){
							$colorexp=explode("_color_",$key);
							$colorsArr[]=$this->getRealColorName($this->request->data['fabric_name'],$colorexp[1]);
						}
					}
					$wtTable=TableRegistry::get('WindowTreatments');
					$newWT=$wtTable->newEntity();
					$newWT->title=$this->request->data['title'];
					$newWT->status='Active';
					$newWT->wt_type=$this->request->data['wt_type'];
					$newWT->description=$this->request->data['description'];
					$newWT->fabric_name=$this->request->data['fabric_name'];
					$newWT->qb_item_code=$this->request->data['qb_item_code'];
					$newWT->available_colors=json_encode($colorsArr);
					$newWT->has_welt=$this->request->data['has_welt'];
					$newWT->lining_option=$this->request->data['lining'];
					if(isset($this->request->data['primary_image']['name']) && strlen(trim($this->request->data['primary_image']['name'])) >0){
						$filename=$this->request->data['primary_image']['name'];
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
						$newfilename=time()."_".$filename;
						$newWT->image_file=$newfilename;
					}
					if($wtTable->save($newWT)){
						//handle image upload
						@mkdir($_SERVER['DOCUMENT_ROOT'].'/webroot/files/window-treatments/'.$newWT->id);
						if(isset($this->request->data['primary_image']['name']) && strlen(trim($this->request->data['primary_image']['name'])) >0){
							move_uploaded_file($this->request->data['primary_image']['tmp_name'],$_SERVER['DOCUMENT_ROOT'].'/webroot/files/window-treatments/'.$newWT->id.'/'.$newfilename);
						}
						$mapModel=TableRegistry::get('WtDataMap');
						foreach($sizes as $size){
							if($this->request->data["size_".$size['id']."_enabled"] == 1){
								//create ccdatamap entry for this enabled size
								$newMapEntry=$mapModel->newEntity();
								$newMapEntry->wt_id=$newWT->id;
								$newMapEntry->size_id=$size['id'];
								$newMapEntry->price=$this->request->data["size_".$size['id']."_price"];
								$newMapEntry->difficulty=$this->request->data["size_".$size['id']."_difficulty"];
								$newMapEntry->weight=$this->request->data["size_".$size['id']."_weight"];
								$newMapEntry->yards=$this->request->data["size_".$size['id']."_yards"];
								$newMapEntry->labor_lf=$this->request->data["size_".$size['id']."_laborlf"];
								$mapModel->save($newMapEntry);
							}
						}
						$this->Flash->success('Successfully added new Window Treatment "'.$this->request->data['title'].'"');
						$this->logActivity($_SERVER['REQUEST_URI'],"Added Window Treatment \"".$this->request->data['title']."\"");
						return $this->redirect('/products/window-treatments/');
					}
				}else{
					$this->render('addwt');
				}
			break;
			case "edit":
				$fabricImages=array();
				$allfabrics=$this->Fabrics->find('all',['conditions'=>['OR'=>['use_in_window'=>1]]])->toArray();
				foreach($allfabrics as $fabric){
					$fabricImages[$fabric['id']]=$fabric['image_file'];
				}
				$this->set('fabricImages',$fabricImages);
				$fabricsfind=$this->Fabrics->find('all',['conditions'=>['status'=>'Active','OR'=>['use_in_window'=>1]],'order'=>['fabric_name'=>'ASC','color'=>'ASC']])->toArray();
				$thefabrics=array();
				$usedfabrics=array();
				foreach($fabricsfind as $fabric){
					if(!in_array($fabric['fabric_name'],$usedfabrics)){
						$thefabrics[$fabric['fabric_name']]=array();
						$usedfabrics[]=$fabric['fabric_name'];
					}
					$thefabrics[$fabric['fabric_name']][$fabric['id']]=array('color'=>$fabric['color'],'id'=>$fabric['id'],'image'=>$fabric['image_file']);
				}
				$this->set('thefabrics',$thefabrics);
				$thisWT=$this->WindowTreatments->get($itemid)->toArray();
				$this->set('wtData',$thisWT);
				if($this->request->data){
					$oldWTData=$this->WindowTreatments->get($itemid)->toArray();
					$wtTable=TableRegistry::get('WindowTreatments');
					$thisWT=$wtTable->get($itemid);
					foreach($sizes as $size){
							if(isset($this->request->data["size_".$size['id']."_enabled"])){
								if($this->request->data["size_".$size['id']."_enabled"] == 1){
								$mapModel=TableRegistry::get('WtDataMap');
								//see if this size combo exists in map model
								$maplookup=$this->WtDataMap->find('all',['conditions'=>['wt_id' => $itemid, 'size_id' => $size['id']]])->toArray();
								if(count($maplookup) == 0){
									//create it
									$newMapEntry=$mapModel->newEntity();
									$newMapEntry->wt_id=$itemid;
									$newMapEntry->size_id=$size['id'];
									if(isset($this->request->data["size_".$size['id']."_price"])){
										$newMapEntry->price=$this->request->data["size_".$size['id']."_price"];
									}else{
										$newMapEntry->price=0;
									}
									if(isset($this->request->data["size_".$size['id']."_difficulty"])){
										$newMapEntry->difficulty=$this->request->data["size_".$size['id']."_difficulty"];
									}else{
										$newMapEntry->difficulty=0;
									}
									if(isset($this->request->data["size_".$size['id']."_weight"])){
										$newMapEntry->weight=$this->request->data["size_".$size['id']."_weight"];
									}else{
										$newMapEntry->weight=0;
									}
									if(isset($this->request->data["size_".$size['id']."_yards"])){
										$newMapEntry->yards=$this->request->data["size_".$size['id']."_yards"];
									}else{
										$newMapEntry->yards=0;
									}
									if(isset($this->request->data["size_".$size['id']."_laborlf"])){
										$newMapEntry->labor_lf=$this->request->data["size_".$size['id']."_laborlf"];
									}else{
										$newMapEntry->labor_lf=0;
									}
									$mapModel->save($newMapEntry);
								}else{
									//update it
									foreach($maplookup as $mapFindRow){
										$mapEntry=$mapModel->get($mapFindRow['id']);
										if(isset($this->request->data["size_".$size['id']."_price"])){
											$mapEntry->price=$this->request->data["size_".$size['id']."_price"];
										}else{
											$mapEntry->price=0;
										}
										if(isset($this->request->data["size_".$size['id']."_difficulty"])){
											$mapEntry->difficulty=$this->request->data["size_".$size['id']."_difficulty"];
										}else{
											$mapEntry->difficulty=0;
										}
										if(isset($this->request->data["size_".$size['id']."_weight"])){
											$mapEntry->weight=$this->request->data["size_".$size['id']."_weight"];
										}else{
											$mapEntry->weight=0;
										}
										if(isset($this->request->data["size_".$size['id']."_yards"])){
											$mapEntry->yards=$this->request->data["size_".$size['id']."_yards"];
										}else{
											$mapEntry->yards=0;
										}
										if(isset($this->request->data["size_".$size['id']."_laborlf"])){
											$mapEntry->labor_lf=$this->request->data["size_".$size['id']."_laborlf"];
										}else{
											$mapEntry->labor_lf=0;
										}
										$mapModel->save($mapEntry);
									}
								}
							}else{
								//if there's an entry in the data map, remove it
								$maplookup=$this->WtDataMap->find('all',['conditions'=>['wt_id' => $itemid, 'size_id' => $size['id']]])->toArray();
								if(count($maplookup) > 0){
									foreach($maplookup as $mapFindRow){
										$this->WtDataMap->delete($this->WtDataMap->get($mapFindRow['id']));
									}
								}
							}
						}else{
							//if there's an entry in the data map, remove it
							$maplookup=$this->WtDataMap->find('all',['conditions'=>['wt_id' => $itemid, 'size_id' => $size['id']]])->toArray();
							if(count($maplookup) > 0){
								foreach($maplookup as $mapFindRow){
									$this->WtDataMap->delete($this->WtDataMap->get($mapFindRow['id']));
								}
							}
						}
					}
					//echo "<pre>".print_r($this->request->data,1)."</pre>";exit;
					$colorsArr=array();
					$thisFabricColors=$this->Fabrics->find('all',['conditions' => ['fabric_name' => $this->request->data['fabric_name']],'order'=>['fabric_name'=>'ASC']])->toArray();
					foreach($thisFabricColors as $fabcolor){
						if(isset($this->request->data["use_".str_replace(" ","_",str_replace("'","",$fabcolor['fabric_name']))."_color_".str_replace(" ","_",$fabcolor['color'])]) && $this->request->data["use_".str_replace("'","",str_replace(" ","_",$fabcolor['fabric_name']))."_color_".str_replace(" ","_",$fabcolor['color'])] == "yes"){
							//$colorsArr[]=$this->getRealColorName($this->request->data['fabric_name'],$fabcolor['color']);
							$colorsArr[]=$fabcolor['color'];
						}
					}
					//print_r($colorsArr);exit;
					$thisWT->title=$this->request->data['title'];
					//$thisWT->status='Active';
					$thisWT->qb_item_code=$this->request->data['qb_item_code'];
					$thisWT->description=$this->request->data['description'];
					$thisWT->fabric_name=$this->request->data['fabric_name'];
					$thisWT->wt_type=$this->request->data['wt_type'];
					$thisWT->available_colors=json_encode($colorsArr);
					$thisWT->has_welt=$this->request->data['has_welt'];
					$thisWT->lining_option=$this->request->data['lining'];
					if(isset($this->request->data['new_image']['name']) && strlen(trim($this->request->data['new_image']['name'])) >0){
						$filename=$this->request->data['new_image']['name'];
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
						$newfilename=time()."_".$filename;
						@unlink($_SERVER['DOCUMENT_ROOT'].'/webroot/files/window-treatments/'.$thisWT->id.'/'.$oldWTData['image_file']);
						move_uploaded_file($this->request->data['new_image']['tmp_name'],$_SERVER['DOCUMENT_ROOT'].'/webroot/files/window-treatments/'.$thisWT->id.'/'.$newfilename);
						$thisWT->image_file=$newfilename;
					}
					if($wtTable->save($thisWT)){
						$this->Flash->success('Successfully saved changes to Window Treatment "'.$this->request->data['title'].'"');
						$this->logActivity($_SERVER['REQUEST_URI'],"Edited Window Treatment \"".$this->request->data['title']."\"");
						return $this->redirect('/products/window-treatments/');
					}
				}else{
					$availableSizes=array();
					$availableWidths=array();
					$availableLengths=array();
					$getsizes=$this->WtDataMap->find('all',['conditions'=>['wt_id'=>$itemid]])->toArray();
					foreach($getsizes as $size){
						$thisSize=$this->WindowTreatmentSizes->get($size['size_id'])->toArray();
						$availableSizes[$size['id']]=array(
							'id' => $size['size_id'],
							'price' => $size['price'],
							'difficulty' => $size['difficulty'],
							'labor_lf' => $size['labor_lf'],
							'weight' => $size['weight'],
							'yards' => $size['yards'],
							'width' => $thisSize['width'],
							'length' => $thisSize['length']
							);
						if(!in_array($thisSize['width'],$availableWidths)){
							$availableWidths[]=$thisSize['width'];
						}
						if(!in_array($thisSize['length'],$availableLengths)){
							$availableLengths[]=$thisSize['length'];
						}
					}
					$this->set('availableSizes',$availableSizes);
					$this->set('availableWidths',$availableWidths);
					$this->set('availableLengths',$availableLengths);
					$this->render('editwt');
				}
			break;
			case "delete":
				$thisWT=$this->WindowTreatments->get($itemid)->toArray();
				$this->set('wtData',$thisWT);
				if($this->request->data){
					if($this->WindowTreatments->delete($this->WindowTreatments->get($itemid))){
						$this->Flash->success('Successfully deleted selected Window Treatment');
						$this->logActivity($_SERVER['REQUEST_URI'],"Deleted Window Treatment \"".$thisWT['title']."\"");
						$this->redirect('/products/window-treatments/');
					}
				}else{
					$this->render('deletewt');
				}
			break;
			case "clone":
				$thisWT=$this->WindowTreatments->get($itemid)->toArray();
				$this->set('wtData',$thisWT);
				$thisWTSizes=array();
				$thisWTSizesLookup=$this->WtDataMap->find('all',['conditions'=>['wt_id'=>$itemid]])->toArray();
				foreach($thisWTSizesLookup as $wtSize){
					$wtsizedata=$this->WindowTreatmentSizes->get($wtSize['size_id'])->toArray();
					$thisWTSizes[]=array(
						'size_id' => $wtSize['id'],
						'price' => $wtSize['price'],
						'yards' => $wtSize['yards'],
						'weight' => $wtSize['weight'],
						'difficulty' => $wtSize['difficulty'],
						'labor_lf' => $wtSize['labor_lf']
					);
				}
				$this->set('thisWTSizes',$thisWTSizes);
				$fabricImages=array();
				$allfabrics=$this->Fabrics->find('all')->toArray();
				foreach($allfabrics as $fabric){
					$fabricImages[$fabric['id']]=$fabric['image_file'];
				}
				$this->set('fabricImages',$fabricImages);
				$fabricsfind=$this->Fabrics->find('all',['conditions'=>['status'=>'Active',['OR'=>['use_in_window'=>1]]],'order'=>['fabric_name'=>'ASC']])->toArray();
				$thefabrics=array();
				$usedfabrics=array();
				foreach($fabricsfind as $fabric){
					if(!in_array($fabric['fabric_name'],$usedfabrics)){
						$thefabrics[$fabric['fabric_name']]=array();
						$usedfabrics[]=$fabric['fabric_name'];
					}
					$thefabrics[$fabric['fabric_name']][$fabric['id']]=array('color'=>$fabric['color'],'id'=>$fabric['id'],'image'=>$fabric['image_file']);
				}
				$this->set('thefabrics',$thefabrics);
				$sizes=$this->WindowTreatmentSizes->find('all')->toArray();
				$this->set('allSizes',$sizes);
				if($this->request->data){
					$sizesArr=array();
					foreach($sizes as $size){
						if($this->request->data["size_".$size['id']."_enabled"] == 1){
							$sizesArr[]=array('id'=>$size['id'],'price'=>$this->request->data["size_".$size['id']."_price"]);
						}
					}
					$colorsArr=array();
					foreach($this->request->data as $key => $val){
						if(substr($key,0,4)=="use_" && preg_match("#color_#i",$key)){
							$colorexp=explode("_color_",$key);
							$colorsArr[]=$colorexp[1];
						}
					}
					$wtTable=TableRegistry::get('WindowTreatments');
					$newWT=$wtTable->newEntity();
					$newWT->title=$this->request->data['title'];
					$newWT->status='Active';
					$newWT->description=$this->request->data['description'];
					$newWT->fabric_name=$this->request->data['fabric_name'];
					$newWT->qb_item_code=$this->request->data['qb_item_code'];
					$newWT->lining_option = $thisWT['lining_option'];
					$newWT->available_sizes=json_encode($sizesArr);
					$docopy=false;
					$newWT->wt_type=$thisWT['wt_type'];
					$newWT->available_colors=json_encode($colorsArr);
					$newWT->has_welt=$this->request->data['has_welt'];
					if(isset($this->request->data['primary_image']['name']) && strlen(trim($this->request->data['primary_image']['name'])) >0){
						$filename=$this->request->data['primary_image']['name'];
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
						$newfilename=time()."_".$filename;
						$newWT->image_file=$newfilename;
					}else{
						$newWT->image_file=$thisWT['image_file'];
						$docopy=true;
					}
					if($wtTable->save($newWT)){
						//handle image upload
						@mkdir($_SERVER['DOCUMENT_ROOT'].'/webroot/files/window-treatments/'.$newWT->id);
						if($docopy){
							@copy($_SERVER['DOCUMENT_ROOT'].'/webroot/files/window-treatments/'.$thisWT['id'].'/'.$thisWT['image_file'],$_SERVER['DOCUMENT_ROOT'].'/webroot/files/window-treatments/'.$newWT->id.'/'.$thisWT['image_file']);
						}else{
							if(isset($this->request->data['primary_image']['name']) && strlen(trim($this->request->data['primary_image']['name'])) >0){
								move_uploaded_file($this->request->data['primary_image']['tmp_name'],$_SERVER['DOCUMENT_ROOT'].'/webroot/files/window-treatments/'.$newWT->id.'/'.$newfilename);
							}
						}
						$mapModel=TableRegistry::get('WtDataMap');
						foreach($sizes as $size){
							if($this->request->data["size_".$size['id']."_enabled"] == 1){
								//create ccdatamap entry for this enabled size
								$newMapEntry=$mapModel->newEntity();
								$newMapEntry->wt_id=$newWT->id;
								$newMapEntry->size_id=$size['id'];
								$newMapEntry->price=$this->request->data["size_".$size['id']."_price"];
								$newMapEntry->difficulty=$this->request->data["size_".$size['id']."_difficulty"];
								$newMapEntry->weight=$this->request->data["size_".$size['id']."_weight"];
								$newMapEntry->yards=$this->request->data["size_".$size['id']."_yards"];
								$newMapEntry->labor_lf=$this->request->data["size_".$size['id']."_laborlf"];
								$mapModel->save($newMapEntry);
							}
						}
						$this->Flash->success('Successfully added new Window Treatments "'.$this->request->data['title'].'"');
						$this->logActivity($_SERVER['REQUEST_URI'],"Added Window Treatment \"".$this->request->data['title']."\"");
						return $this->redirect('/products/window-treatments/');
					}
				}else{
					$usedfabrics=array();
					$fabrics=array();
					$fabricsfind=$this->Fabrics->find('all',['conditions'=>['status'=>'Active','use_in_window'=>1],'order'=>['fabric_name'=>'ASC']])->toArray();
					foreach($fabricsfind as $fabric){
						if(!in_array($fabric['fabric_name'],$usedfabrics)){
							$fabrics[$fabric['fabric_name']]=array();
							$usedfabrics[]=$fabric['fabric_name'];
						}
						$fabrics[$fabric['fabric_name']][$fabric['id']]=$fabric['color'];
					}
					$this->set('fabrics',$fabrics);
					$availableSizes=array();
					$availableWidths=array();
					$availableLengths=array();
					//lookup WT's with the selected fabric name
					$hasWelt=$thisWT['has_welt'];
					/*
					echo "<pre>";
					print_r($thisWT);
					echo "</pre>";
					exit;
					*/				
					$thisWTs=$this->WindowTreatments->find('all',['conditions' => ['fabric_name' => $thisWT['fabric_name'], 'wt_type' => $thisWT['wt_type'], 'has_welt' => $hasWelt, 'lining_option' => $thisWT['lining_option']]])->toArray();
					/*
					echo "<pre>";
					print_r($thisWTs);
					echo "</pre>";
					exit;
					*/
					foreach($thisWTs as $wtItem){
						$getsizes=$this->WtDataMap->find('all',['conditions'=>['wt_id'=>$wtItem['id']]])->toArray();
						foreach($getsizes as $size){
							$thisSize=$this->WindowTreatmentSizes->get($size['size_id'])->toArray();
							$availableSizes[$size['id']]=array(
								'id' => $size['size_id'],
								'price' => $size['price'],
								'difficulty' => $size['difficulty'],
								'labor_lf' => $size['labor_lf'],
								'weight' => $size['weight'],
								'yards' => $size['yards'],
								'width' => $thisSize['width'],
								'length' => $thisSize['length']
								);
							if(!in_array($thisSize['width'],$availableWidths)){
								$availableWidths[]=$thisSize['width'];
							}
							if(!in_array($thisSize['length'],$availableLengths)){
								$availableLengths[]=$thisSize['length'];
							}
						}
					}
					$this->set('availableSizes',$availableSizes);
					$this->set('availableWidths',$availableWidths);
					$this->set('availableLengths',$availableLengths);
					$this->render('clonewt');
				}
			break;
			case "default":
			default:
				$this->render('window_treatments');
			break;
		}
	}
	public function services($subaction='default',$itemID=0){
		$this->autoRender=false;
		
		switch($subaction){
			default:
			case "default":
				$this->render('services');
			break;
			case "add":
				if($this->request->data){
					//process new service
					
					$servicesTable=TableRegistry::get('Services');
					$newService=$servicesTable->newEntity();
					$newService->title = $this->request->data['title'];
					$newService->description = $this->request->data['description'];
					$newService->price=$this->request->data['price'];
					$newService->subclass=$this->request->data['subclass'];
					$newService->cost = $this->request->data['cost'];
					$newService->status = $this->request->data['status'];
					$newService->qb_item_code = $this->request->data['qb_item_code'];
					
					
					if($servicesTable->save($newService)){
						
						//handle image upload
						if(isset($this->request->data['primary_image']['tmp_name']) && strlen(trim($this->request->data['primary_image']['tmp_name'])) >0){
								$filename=$this->request->data['primary_image']['name'];
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
								$newfilename=time()."_".$filename;
								@mkdir($_SERVER['DOCUMENT_ROOT'].'/webroot/files/services/'.$newService->id);
								move_uploaded_file($this->request->data['primary_image']['tmp_name'],$_SERVER['DOCUMENT_ROOT'].'/webroot/files/services/'.$newService->id.'/'.$newfilename);
								$newService->image_file=$newfilename;
								$servicesTable->save($newService);
							}
						
						$this->Flash->success('Successfully added new service "'.$this->request->data['title'].'"');
						$this->logActivity($_SERVER['REQUEST_URI'],"Added service\"".$this->request->data['title']."\"");
						return $this->redirect('/products/services/');

					}
					
				}else{
				    $allServiceSubclasses=$this->ProductSubclasses->find('all',['conditions' => ['class_id'=>6],'order'=>['subclass_name'=>'asc']])->toArray();
		            $this->set('allServiceSubclasses',$allServiceSubclasses);
		
					$this->render('addservice');
				}
			break;
			case "edit":
				if($this->request->data){
					//save changes to selected service
					
					$servicesTable=TableRegistry::get('Services');
					$thisService=$servicesTable->get($itemID);
					$thisService->title=$this->request->data['title'];
					$thisService->description=$this->request->data['description'];
					$thisService->price = $this->request->data['price'];
					$thisService->subclass = $this->request->data['subclass'];
					$thisService->cost = $this->request->data['cost'];
					$thisService->status = $this->request->data['status'];
					$thisService->qb_item_code = $this->request->data['qb_item_code'];

					//image handler
					if($this->request->data['changeimage'] == 'yes'){
						
					}
					
					if($servicesTable->save($thisService)){
						
						$this->Flash->success('Successfully saved changes to service "'.$this->request->data['title'].'"');
						$this->logActivity($_SERVER['REQUEST_URI'],'Edited service "'.$this->request->data['title'].'"');
						return $this->redirect('/products/services/');
						
					}
					
				}else{
					$thisService=$this->Services->get($itemID)->toArray();
					$this->set('serviceData',$thisService);
					
					$allServiceSubclasses=$this->ProductSubclasses->find('all',['conditions' => ['class_id'=>6],'order'=>['subclass_name'=>'asc']])->toArray();
		            $this->set('allServiceSubclasses',$allServiceSubclasses);
					
					$this->render('editservice');
				}
			break;
			case "delete":
				if($this->request->data){
					//delete selected service
					$currentService=$this->Services->get($itemID)->toArray();
					
					if($this->Services->delete($this->Services->get($itemID))){
						$this->Flash->success('Successfully deleted service "'.$currentService['title'].'"');
						$this->logActivity($_SERVER['REQUEST_URI'],"Deleted service \"".$currentService['title']."\"");
						return $this->redirect('/products/services/');
					}
					
				}else{
					$thisService=$this->Services->get($itemID)->toArray();
					$this->set('serviceData',$thisService);
					$this->render('deleteserviceconfirm');
				}
			break;
		}
	}
	
	

	public function fabrics($subaction='default',$itemid=0,$fromtype='',$frominfo=''){
		$this->autoRender=false;
		switch($subaction){
			default:
			case "default":
				$this->render('fabrics');
			break;
			case "add":
				if($this->request->data){
					//print_r($this->request->data);exit;
					$fabricsTable=TableRegistry::get('Fabrics');
					$newFabric=$fabricsTable->newEntity();
					$newFabric->fabric_name=$this->request->data['fabric_name'];
					$newFabric->color=$this->request->data['color'];
					if($this->request->data['imagemethod']=='printscreencapture'){
						//image file is in Unassigned folder, filename is $this->request->data['croppedimagefilename']
						//file needs to be moved to this fabric's id folder
						$newFabric->image_file=$this->request->data['croppedimagefilename'];
					}elseif($this->request->data['imagemethod']=='fileupload'){
						//get the uploaded file name if it exists
						if(isset($this->request->data['imageuploadfile']['tmp_name']) && strlen(trim($this->request->data['imageuploadfile']['tmp_name'])) >0){
							$filename=$this->request->data['imageuploadfile']['name'];
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
							$newfilename=time()."_".$filename;
							$newFabric->image_file=$newfilename;
						}
					}
					$newFabric->status=$this->request->data['fabric_status'];
					if($this->request->data['ownership'] == 'roster-fabric'){
						$newFabric->is_hci_fabric=1;
						$newFabric->com_fabric=0;
					}elseif($this->request->data['ownership'] == 'mom-nonroster'){
						$newFabric->is_hci_fabric=0;
						$newFabric->com_fabric=0;
					}elseif($this->request->data['ownership'] == 'com-fabric'){
						$newFabric->is_hci_fabric=0;
						$newFabric->com_fabric=1;
					}
					$newFabric->cost_per_yard_cut=$this->request->data['cost_per_yard_cut'];
					$newFabric->cost_per_yard_bolt=$this->request->data['cost_per_yard_bolt'];
					$newFabric->cost_per_yard_case=$this->request->data['cost_per_yard_case'];

					$newFabric->bolt_price_last_updated = time();
					$newFabric->case_price_last_updated = time();
					$newFabric->cut_price_last_updated = time();

					$newFabric->yards_per_bolt=$this->request->data['yards_per_bolt'];
					$newFabric->yards_per_case=$this->request->data['yards_per_case'];
					$newFabric->use_in_cc=$this->request->data['use_in_cc'];
					$newFabric->use_in_bs=$this->request->data['use_in_bs'];
					$newFabric->use_in_window=$this->request->data['use_in_window'];
					$newFabric->use_in_sc=$this->request->data['use_in_sc'];
					$newFabric->antimicrobial=$this->request->data['antimicrobial'];
					$newFabric->railroaded=$this->request->data['railroaded'];
					$newFabric->quilted = $this->request->data['quilted'];
					$newFabric->collection = $this->request->data['collection'];
					$newFabric->vertical_repeat=$this->request->data['vertical_repeat'];
					$newFabric->horizontal_repeat=$this->request->data['horizontal_repeat'];
					$newFabric->finish=$this->request->data['finish'];
					$newFabric->flammability=$this->request->data['flammability'];
					$newFabric->material=$this->request->data['material'];
					$newFabric->fabric_width=$this->request->data['fabric_width'];
					$newFabric->description=$this->request->data['description'];
					$newFabric->print_or_dye=$this->request->data['print_or_dye'];
					$newFabric->weaves=$this->request->data['weaves'];
					$newFabric->laundering=$this->request->data['laundering'];
					if(isset($this->request->data['weight_per_sqin']) && $this->request->data['weight_per_sqin'] != ''){
						$newFabric->weight_per_sqin = $this->request->data['weight_per_sqin'];
					}
					if(isset($this->request->data['minimum']) && $this->request->data['minimum'] != ''){
						$newFabric->minimum = $this->request->data['minimum'];
					}
					$newFabric->sku = $this->request->data['sku'];
					$newFabric->country_of_origin = $this->request->data['country_of_origin'];
					$newFabric->bs_backing_material = $this->request->data['bs_backing_material'];
					$newFabric->status='Active';
					$newFabric->vendors_id=$this->request->data['vendor_id'];
					$newFabric->vendor_fabric_name = $this->request->data['vendor_fabric_name'];
					$newFabric->vendor_color_name = $this->request->data['vendor_color_name'];
					if($fabricsTable->save($newFabric)){
						//hooray!
						@mkdir($_SERVER['DOCUMENT_ROOT']."/webroot/files/fabrics/".$newFabric->id);
						if($this->request->data['imagemethod']=='printscreencapture'){
							//move the image from unassigned folder to new fabric id folder
							rename($_SERVER['DOCUMENT_ROOT'].'/webroot/files/fabrics/unassigned/'.$this->request->data['croppedimagefilename'],$_SERVER['DOCUMENT_ROOT']."/webroot/files/fabrics/".$newFabric->id.'/'.$this->request->data['croppedimagefilename']);
						}else{
							if(isset($this->request->data['imageuploadfile']['tmp_name']) && strlen(trim($this->request->data['imageuploadfile']['tmp_name'])) >0){
								//process image upload if it exists
								move_uploaded_file($this->request->data['imageuploadfile']['tmp_name'],$_SERVER['DOCUMENT_ROOT']."/webroot/files/fabrics/".$newFabric->id."/".$newfilename);
								
								
								$imagetype=strtolower(substr($newfilename,-4));
						
								switch($imagetype){
									case ".png":
										$newfile=$_SERVER['DOCUMENT_ROOT'].'/webroot/files/fabrics/'.$newFabric->id.'/'.str_replace('.png','-small.png',$newfilename);
									break;
									case ".jpg":
										$newfile=$_SERVER['DOCUMENT_ROOT'].'/webroot/files/fabrics/'.$newFabric->id.'/'.str_replace('.jpg','-small.jpg',$newfilename);
									break;
									case "jpeg";
										$newfile=$_SERVER['DOCUMENT_ROOT'].'/webroot/files/fabrics/'.$newFabric->id.'/'.str_replace('.jpeg','-small.jpg',$newfilename);
									break;
									case ".gif":
										$newfile=$_SERVER['DOCUMENT_ROOT'].'/webroot/files/fabrics/'.$newFabric->id.'/'.str_replace('.gif','-small.gif',$newfilename);
									break;
								}
								
								$this->resizeimage($_SERVER['DOCUMENT_ROOT']."/webroot/files/fabrics/".$newFabric->id."/".$newfilename,$newfile,200);
								
								
							}
						}
						
						
						
						
						$this->Flash->success('Successfully added new fabric "'.$this->request->data['fabric_name'].' ('.$this->request->data['color'].')"');
						$this->logActivity($_SERVER['REQUEST_URI'],"Added fabric \"".$this->request->data['fabric_name']." (".$this->request->data['color'].")\"");
						if($frominfo == 'addinventory'){
							return $this->redirect('/products/addinventory/'.$newFabric->id);
						}else{
							if($frominfo != ''){
								///quotes/newlineitem/14/calculator/bedspread/2
								return $this->redirect('/quotes/newlineitem/'.$itemid.'/'.$fromtype.'/'.$frominfo.'/'.$newFabric->id);
							}else{
								return $this->redirect('/products/fabrics/');
							}
						}
					}
				}else{
					//add new fabric form
					$vendoroptions=array();
					//$allvendors=$this->Vendors->find('all',['order'=>['vendor_name'=>'asc']])->toArray();
					$allvendors=$this->Vendors->find('all',['conditions'=>['status'=>'Active'],'order'=>['vendor_name'=>'asc']])->toArray();
					foreach($allvendors as $vendor){
						$vendoroptions[$vendor['id']]=$vendor['vendor_name'];
					}
					$this->set('vendoroptions',$vendoroptions);
					$this->render('addfabric');
				}
			break;
			case "edit":
				$fabricData=$this->Fabrics->get($itemid)->toArray();
				$this->set('fabricData',$fabricData);
				$vendoroptions=array();
				//$allvendors=$this->Vendors->find('all',['order'=>['vendor_name'=>'asc']])->toArray();
				$allvendors=$this->Vendors->find('all',['conditions'=>['status'=>'Active'],'order'=>['vendor_name'=>'asc']])->toArray();
				foreach($allvendors as $vendor){
					$vendoroptions[$vendor['id']]=$vendor['vendor_name'];
				}
				$this->set('vendoroptions',$vendoroptions);
				if($this->request->data){
					$fabricTable=TableRegistry::get('Fabrics');
					$thisFabric=$fabricTable->get($itemid);
					$thisFabric->fabric_name=$this->request->data['fabric_name'];
					$thisFabric->color=$this->request->data['color'];
					$thisFabric->vendors_id = $this->request->data['vendors_id'];
					$thisFabric->vendor_fabric_name = $this->request->data['vendor_fabric_name'];
					$thisFabric->vendor_color_name = $this->request->data['vendor_color_name'];
					/*if($this->request->data['ownership'] == 'roster-fabric'){
						$thisFabric->is_hci_fabric=1;
						$thisFabric->com_fabric=0;
					}elseif($this->request->data['ownership'] == 'mom-nonroster'){
						$thisFabric->is_hci_fabric=0;
						$thisFabric->com_fabric=0;
					}elseif($this->request->data['ownership'] == 'com-fabric'){
						$thisFabric->is_hci_fabric=0;
						$thisFabric->com_fabric=1;
					}*/
					$thisFabric->status = $this->request->data['fabric_status'];
					//$thisFabric->price_per_yard=$this->request->data['price_per_yard'];
					
					$thisFabric->cost_per_yard_cut=$this->request->data['cost_per_yard_cut'];
					$thisFabric->cost_per_yard_bolt=$this->request->data['cost_per_yard_bolt'];
					$thisFabric->cost_per_yard_case=$this->request->data['cost_per_yard_case'];
					

					if(floatval($fabricData['cost_per_yard_cut']) != floatval($this->request->data['cost_per_yard_cut'])){
						$thisFabric->cut_price_last_updated = time();
					}

					if(floatval($fabricData['cost_per_yard_bolt']) != floatval($this->request->data['cost_per_yard_bolt'])){
						$thisFabric->bolt_price_last_updated = time();
					}

					if(floatval($fabricData['cost_per_yard_case']) != floatval($this->request->data['cost_per_yard_case'])){
						$thisFabric->case_price_last_updated = time();
					}



					$thisFabric->yards_per_bolt=$this->request->data['yards_per_bolt'];
					$thisFabric->yards_per_case=$this->request->data['yards_per_case'];
					$thisFabric->material=$this->request->data['material'];
					$thisFabric->print_or_dye=$this->request->data['print_or_dye'];
					$thisFabric->weaves=$this->request->data['weaves'];
					$thisFabric->description=$this->request->data['description'];
					$thisFabric->antimicrobial=$this->request->data['antimicrobial'];
					$thisFabric->railroaded=$this->request->data['railroaded'];
					//$thisFabric->quilted = $this->request->data['quilted'];
					$thisFabric->collection = $this->request->data['collection'];
					$thisFabric->vertical_repeat=$this->request->data['vertical_repeat'];
					$thisFabric->horizontal_repeat=$this->request->data['horizontal_repeat'];
					$thisFabric->finish=$this->request->data['finish'];
					$thisFabric->flammability=$this->request->data['flammability'];
					$thisFabric->fabric_width=$this->request->data['fabric_width'];
					$thisFabric->laundering=$this->request->data['laundering'];
					//$thisFabric->weight_per_sqin = $this->request->data['weight_per_sqin'];
					$thisFabric->minimum = $this->request->data['minimum'];
					//$thisFabric->sku = $this->request->data['sku'];
					$thisFabric->country_of_origin = $this->request->data['country_of_origin'];
					$thisFabric->bs_backing_material = $this->request->data['bs_backing_material'];
					$thisFabric->use_in_bs=$this->request->data['use_in_bs'];
					$thisFabric->use_in_cc=$this->request->data['use_in_cc'];
					$thisFabric->use_in_window=$this->request->data['use_in_window'];
					$thisFabric->use_in_sc=$this->request->data['use_in_sc'];
					if(isset($this->request->data['new_image']['tmp_name']) && strlen(trim($this->request->data['new_image']['tmp_name'])) >0){
						$filename=$this->request->data['new_image']['name'];
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
						$newfilename=time()."_".$filename;
						if(isset($this->request->data['new_image']['tmp_name']) && strlen(trim($this->request->data['new_image']['tmp_name'])) >0){
							//erase old image
							@unlink($_SERVER['DOCUMENT_ROOT'].'/webroot/files/fabrics/'.$thisFabric['id'].'/'.$fabricData['image_file']);
							@mkdir($_SERVER['DOCUMENT_ROOT']."/webroot/files/fabrics/".$thisFabric['id']);
							//process image upload if it exists
							move_uploaded_file($this->request->data['new_image']['tmp_name'],$_SERVER['DOCUMENT_ROOT']."/webroot/files/fabrics/".$thisFabric->id."/".$newfilename);
							
							
							
							
							
							$imagetype=strtolower(substr($newfilename,-4));
						
							switch($imagetype){
								case ".png":
									$newfile=$_SERVER['DOCUMENT_ROOT'].'/webroot/files/fabrics/'.$thisFabric['id'].'/'.str_replace('.png','-small.png',$newfilename);
								break;
								case ".jpg":
									$newfile=$_SERVER['DOCUMENT_ROOT'].'/webroot/files/fabrics/'.$thisFabric['id'].'/'.str_replace('.jpg','-small.jpg',$newfilename);
								break;
								case "jpeg";
									$newfile=$_SERVER['DOCUMENT_ROOT'].'/webroot/files/fabrics/'.$thisFabric['id'].'/'.str_replace('.jpeg','-small.jpg',$newfilename);
								break;
								case ".gif":
									$newfile=$_SERVER['DOCUMENT_ROOT'].'/webroot/files/fabrics/'.$thisFabric['id'].'/'.str_replace('.gif','-small.gif',$newfilename);
								break;
							}
							
							//@unlink($newfile);
							$this->resizeimage($_SERVER['DOCUMENT_ROOT']."/webroot/files/fabrics/".$thisFabric['id']."/".$newfilename,$newfile,200);
							
							
							
							
							$thisFabric->image_file=$newfilename;
						}
					}
					if($fabricTable->save($thisFabric)){
					    
					    
					    
						$this->Flash->success('Successfully saved changes to fabric "'.$this->request->data['fabric_name'].' ('.$this->request->data['color'].')"');
						$this->logActivity($_SERVER['REQUEST_URI'],"Edited fabric \"".$this->request->data['fabric_name']." (".$this->request->data['color'].")\"");
						return $this->redirect('/products/fabrics/');
					}
				}else{
					$this->render('editfabric');
				}
			break;
			case "delete":
				
				if($this->request->data){
					//process the delete
					$fabricsTable=TableRegistry::get('Fabrics');
					$thisFabric=$fabricsTable->get($itemid);
					$thisFabric->status='deleted';
					if($fabricsTable->save($thisFabric)){
					    
					    
						
						$this->Flash->success('Successfully deleted fabric "'.$thisFabric->fabric_name.' ('.$thisFabric->color.')"');
						$this->logActivity($_SERVER['REQUEST_URI'],"Deleted fabric \"".$thisFabric->fabric_name." (".$thisFabric->color.")\"");
						return $this->redirect('/products/fabrics/');
					}
					
				}else{
					
					$this->render('deletefabricconfirm');
				}
				
			break;
			case "clone":
				$oldFabric=$this->Fabrics->get($itemid)->toArray();
				$this->set('oldFabric',$oldFabric);
				if($this->request->data){
					//begin cloning
					if(isset($this->request->data['primary_image']['tmp_name']) && strlen(trim($this->request->data['primary_image']['tmp_name'])) >0){
						$filename=$this->request->data['primary_image']['name'];
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
						$newfilename=time()."_".$filename;
					}
					$fabricsTable=TableRegistry::get('Fabrics');
					$newFabric=$fabricsTable->newEntity();
					$newFabric->fabric_name=$this->request->data['fabric_name'];
					$newFabric->color=$this->request->data['color'];
					$newFabric->cost_per_yard_cut=$oldFabric['cost_per_yard_cut'];
					$newFabric->cost_per_yard_bolt=$oldFabric['cost_per_yard_bolt'];
					$newFabric->cost_per_yard_case=$oldFabric['cost_per_yard_case'];
					$newFabric->yards_per_bolt=$oldFabric['yards_per_bolt'];
					$newFabric->yards_per_case=$oldFabric['yards_per_case'];
					$newFabric->fabric_width=$oldFabric['fabric_width'];
					$newFabric->laundering=$oldFabric['laundering'];
					$newFabric->antimicrobial=$oldFabric['antimicrobial'];
					$newFabric->railroaded=$oldFabric['railroaded'];
					$newFabric->vertical_repeat=$oldFabric['vertical_repeat'];
					$newFabric->horizontal_repeat=$oldFabric['horizontal_repeat'];
					$newFabric->weight_per_sqin=$oldFabric['weight_per_sqin'];
					$newFabric->is_hci_fabric=$oldFabric['is_hci_fabric'];
					$newFabric->quilted=$oldFabric['quilted'];
					$newFabric->vendors_id=$oldFabric['vendors_id'];
					$newFabric->vendor_fabric_name=$this->request->data['vendor_fabric_name'];
					$newFabric->vendor_color_name=$this->request->data['vendor_color_name'];
					$newFabric->country_of_origin=$oldFabric['country_of_origin'];
					$newFabric->minimum=$oldFabric['minimum'];
					$newFabric->collection=$oldFabric['collection'];
					$newFabric->sku=$oldFabric['sku'];
					$newFabric->com_fabric=$oldFabric['com_fabric'];
					$newFabric->bs_backing_material=$oldFabric['bs_backing_material'];
					$newFabric->use_in_cc=$oldFabric['use_in_cc'];
					$newFabric->use_in_bs=$oldFabric['use_in_bs'];
					$newFabric->use_in_window=$oldFabric['use_in_window'];
					$newFabric->use_in_sc=$oldFabric['use_in_sc'];
					$newFabric->finish=$oldFabric['finish'];
					$newFabric->flammability=$oldFabric['flammability'];
					$newFabric->material=$oldFabric['material'];
					$newFabric->description=$oldFabric['description'];
					$newFabric->print_or_dye=$oldFabric['print_or_dye'];
					$newFabric->weaves=$oldFabric['weaves'];
					$newFabric->status='Active';
					
					$newFabric->bolt_price_last_updated=$oldFabric['bolt_price_last_updated'];
					$newFabric->case_price_last_updated=$oldFabric['case_price_last_updated'];
					$newFabric->cut_price_last_updated=$oldFabric['cut_price_last_updated'];
					
					if(isset($this->request->data['primary_image']['tmp_name']) && strlen(trim($this->request->data['primary_image']['tmp_name'])) >0){
						$newFabric->image_file=$newfilename;
					}
					if($fabricsTable->save($newFabric)){
						//hooray!
						@mkdir($_SERVER['DOCUMENT_ROOT']."/webroot/files/fabrics/".$newFabric->id);
						if(isset($this->request->data['primary_image']['tmp_name']) && strlen(trim($this->request->data['primary_image']['tmp_name'])) >0){
							//process image upload if it exists
							move_uploaded_file($this->request->data['primary_image']['tmp_name'],$_SERVER['DOCUMENT_ROOT']."/webroot/files/fabrics/".$newFabric->id."/".$newfilename);
						}
						$this->Flash->success('Successfully added new fabric "'.$this->request->data['fabric_name'].' ('.$this->request->data['color'].')"');
						$this->logActivity($_SERVER['REQUEST_URI'],"Added fabric \"".$this->request->data['fabric_name']." (".$this->request->data['color'].")\"");
						return $this->redirect('/products/fabrics/');
					}
				}else{					
					$this->render('clonefabric');
				}
			break;
		}
	}

	
	
	
	public function vendors($subaction='default',$itemid=0){
		$this->autoRender=false;
		switch($subaction){
			default:
			case "default":
				$this->render('vendors');
			break;
			case "add":
				if($this->request->data){
					//print_r($this->request->data);exit;
					$vendorsTable=TableRegistry::get('Vendors');
					$newVendor=$vendorsTable->newEntity();
					$newVendor->vendor_name=$this->request->data['vendor_name'];
					if($vendorsTable->save($newVendor)){
						$this->Flash->success('Successfully added new vendor "'.$this->request->data['vendor_name'].'"');
						$this->logActivity($_SERVER['REQUEST_URI'],"Added vendor \"".$this->request->data['vendor_name']."\"");
						return $this->redirect('/products/vendors/');
					}
				}else{
					//add new vendor form
					$this->render('addvendor');
				}
			break;
			case "edit":
				$vendorData=$this->Vendors->get($itemid)->toArray();
				$this->set('vendorData',$vendorData);
				
				if($this->request->data){
					$vendorTable=TableRegistry::get('Vendors');
					$thisVendor=$vendorTable->get($itemid);
					$thisVendor->vendor_name=$this->request->data['vendor_name'];
					$thisVendor->status = $this->request->data['status'];
					if($vendorTable->save($thisVendor)){
						$this->Flash->success('Successfully saved changes to vendor "'.$this->request->data['vendor_name'].'"');
						$this->logActivity($_SERVER['REQUEST_URI'],"Edited vendor \"".$this->request->data['vendor_name']."\"");
						return $this->redirect('/products/vendors/');
					}
				}else{
					$this->render('editvendor');
				}
			break;
			/*case "delete":
				
				if($this->request->data){
					//process the delete
					$vendorsTable=TableRegistry::get('Vendors');
					$thisVendor=$vendorsTable->get($itemid);
					$thisVendorData=$thisVendor->toArray();
					if($vendorsTable->delete($thisVendor)){
						$this->Flash->success('Successfully deleted vendor "'.$thisVendorData->vendor_name.'"');
						$this->logActivity($_SERVER['REQUEST_URI'],"Deleted vendor \"".$thisVendorData->vendor_name."\"");
						return $this->redirect('/products/vendors/');
					}
					
				}else{
					
					$this->render('deletevendorconfirm');
				}
				
			break;*/	
		}
	}
	
	
	
	public function linings($subaction='default',$itemid=0,$fromtype='',$frominfo=''){
		$this->autoRender=false;
		switch($subaction){
			default:
			case "default":
				$this->render('linings');
			break;
			case "add":
				if($this->request->data){
					//print_r($this->request->data);exit;
					$liningsTable=TableRegistry::get('Linings');
					$newLining=$liningsTable->newEntity();
					$newLining->title=$this->request->data['title'];
					$newLining->short_title=$this->request->data['short_title'];
					$newLining->width=$this->request->data['width'];
					$newLining->price=$this->request->data['price'];
					$newLining->vendors_id=$this->request->data['vendors_id'];
					if($liningsTable->save($newLining)){
						//hooray!
						@mkdir($_SERVER['DOCUMENT_ROOT']."/webroot/files/linings/".$newLining->id);
						$this->Flash->success('Successfully added new lining "'.$this->request->data['title']);
						$this->logActivity($_SERVER['REQUEST_URI'],"Added lining \"".$this->request->data['title']);
						return $this->redirect('/products/linings/');
					}
				}else{
					//add new fabric form
					$vendoroptions=array();
					$allvendors=$this->Vendors->find('all',['conditions'=>['status'=>'Active'],'order'=>['vendor_name'=>'asc']])->toArray();
					foreach($allvendors as $vendor){
						$vendoroptions[$vendor['id']]=$vendor['vendor_name'];
					}
					$this->set('vendoroptions',$vendoroptions);
					$this->render('addlining');
				}
			break;
			case "edit":
				$liningData=$this->Linings->get($itemid)->toArray();
				$this->set('liningData',$liningData);
				$vendoroptions=array();
				$allvendors=$this->Vendors->find('all',['conditions'=>['status'=>'Active'],'order'=>['vendor_name'=>'asc']])->toArray();
				foreach($allvendors as $vendor){
					$vendoroptions[$vendor['id']]=$vendor['vendor_name'];
				}
				$this->set('vendoroptions',$vendoroptions);
				if($this->request->data){
					$liningTable=TableRegistry::get('Linings');
					$thisLining=$liningTable->get($itemid);
					$thisLining->title=$this->request->data['title'];
					$thisLining->short_title=$this->request->data['short_title'];
					$thisLining->width=$this->request->data['width'];
					$thisLining->price=$this->request->data['price'];
					$thisLining->vendors_id = $this->request->data['vendors_id'];
					if($liningTable->save($thisLining)){
						$this->Flash->success('Successfully saved changes to lining "'.$this->request->data['title'].'"');
						$this->logActivity($_SERVER['REQUEST_URI'],"Edited lining \"".$this->request->data['title']."\"");
						return $this->redirect('/products/linings/');
					}
				}else{
					$this->render('editlining');
				}
			break;
			case "delete":
			    $liningData=$this->Linings->get($itemid)->toArray();
			    $this->set('liningData',$liningData);
			    
			    if($this->request->data){
			        
			        $thisLining=$this->Linings->get($itemid);
			        if($this->Linings->delete($thisLining)){
			            $this->Flash->success('Successfully deleted lining "'.$liningData['title'].'"');
						$this->logActivity($_SERVER['REQUEST_URI'],"Deleted lining \"".$liningData['title']."\"");
						return $this->redirect('/products/linings/');
			        }
			    }else{
			        $this->render('confirmdeletelining');
			    }
			break;
			case "clone":
				$oldLining=$this->Linings->get($itemid)->toArray();
				$this->set('oldLining',$oldLining);
				if($this->request->data){
					//begin cloning
					$liningTable=TableRegistry::get('Linings');
					$newLining=$liningTable->newEntity();
					$newLining->title=$this->request->data['title'];
					$newLining->short_title=$this->request->data['short_title'];
					if($liningTable->save($newLining)){
						//hooray!
						@mkdir($_SERVER['DOCUMENT_ROOT']."/webroot/files/linings/".$newLining->id);
						$this->Flash->success('Successfully added new lining "'.$this->request->data['title']);
						$this->logActivity($_SERVER['REQUEST_URI'],"Added lining \"".$this->request->data['title']);
						return $this->redirect('/products/linings/');
					}
				}else{					
					$this->render('clonelining');
				}
			break;
		}
	}
	public function getserviceslist(){
		$services=array();
		$overallTotalRows=$this->Services->find()->count();
		$conditions=array();
		if(isset($this->request->data['search']['value']) && strlen(trim($this->request->data['search']['value'])) >0){
			//this is a search
			$conditions['OR']=array();
			$conditions['OR'] += array('title LIKE'=>'%'.trim($this->request->data['search']['value']).'%');
			$conditions['OR'] += array('description LIKE'=>'%'.trim($this->request->data['search']['value']).'%');
		}
		$servicefind=$this->Services->find('all',['conditions'=>$conditions,'order'=>$order])->offset($this->request->data['start'])->limit($this->request->data['length'])->hydrate(false)->toArray();
		$totalFilteredRows=$this->Services->find('all',['conditions'=>$conditions])->count();
		foreach($servicefind as $service){
			$images='';
			if ($handle = opendir($_SERVER['DOCUMENT_ROOT']."/webroot/files/services/".$service['id'])) {
				while (false !== ($entry = readdir($handle))) {
					if ($entry != "." && $entry != "..") {
						$images .= "<img src=\"/files/services/".$service['id']."/".$entry."\" width=\"145\" height=\"145\" class=\"fabricimg\" />";
					}
				}
				closedir($handle);
			}
			if($images==''){
				$images='<em>No Image</em>';
			}
			
			$thisSubclass=$this->ProductSubclasses->get($service['subclass'])->toArray();
			
			$price='$'.number_format($service['price'],2,'.',',');
			$services[]=array(
				'DT_RowId'=>'row_'.$service['id'],
				'DT_RowClass'=>'rowtest',
				'0' => $images,
				'1' => $service['title'],
				'2' => $price,
				'3' => $thisSubclass['subclass_name'],
				'4' => $service['status'],
				'5' => '<a href="/products/services/edit/'.$service['id'].'/"><img src="/img/edit.png" alt="Edit This Service" title="Edit This Service" /></a> <a href="/products/services/delete/'.$service['id'].'/"><img src="/img/delete.png" alt="Delete This Service" title="Delete This Service" /></a>'
			);
		}
		$this->set('draw',$this->request->data['draw']);
		$this->set('recordsTotal',$overallTotalRows);
		$this->set('recordsFiltered',$totalFilteredRows);
		$this->set('data',$services);
		$this->set('_serialize',['draw','recordsTotal','recordsFiltered','data']);
	}
	
	
	
	public function getvendorslist(){
		$vendors=array();
		$conditions=array();
		$overallTotalRows=$this->Vendors->find('all')->count();
		if(isset($this->request->data['search']['value']) && strlen(trim($this->request->data['search']['value'])) >0){
			//this is a search
			$conditions['OR']=array();
			$conditions['OR'] += array('vendor_name LIKE'=>'%'.trim($this->request->data['search']['value']).'%');
			$conditions['OR'] += array('id'=>trim($this->request->data['search']['value']));
		}
		$order=array('vendor_name'=>'ASC');
		$vendorfind=$this->Vendors->find('all',['conditions'=>$conditions,'order'=>$order])->offset($this->request->data['start'])->limit($this->request->data['length'])->hydrate(false)->toArray();
		$totalFilteredRows=$this->Vendors->find('all',['conditions'=>$conditions])->count();
		foreach($vendorfind as $vendor){
			
			$vendors[]=array(
				'DT_RowId'=>'row_'.$vendor['id'],
				'DT_RowClass'=>'rowtest',
				'0' => $vendor['id'],
				'1' => $vendor['vendor_name'],
				'2' => $vendor['status'],
				'3' => '<a href="/products/vendors/edit/'.$vendor['id'].'/"><img src="/img/edit.png" alt="Edit This Vendor" title="Edit This Vendor" /></a>'
			);
		}
		$this->set('draw',$this->request->data['draw']);
		$this->set('recordsTotal',$overallTotalRows);
		$this->set('recordsFiltered',$totalFilteredRows);
		$this->set('data',$vendors);
		$this->set('_serialize',['draw','recordsTotal','recordsFiltered','data']);
	}
	
	
	public function getfabricslist(){
		$fabrics=array();
		$overallTotalRows=$this->Fabrics->find('all',['conditions' => ['status !=' => 'deleted']])->count();
		$conditions=array('status !=' => 'deleted');
		if(isset($this->request->data['search']['value']) && strlen(trim($this->request->data['search']['value'])) >0){
			//this is a search
			$conditions['OR']=array();
			$conditions['OR'] += array('fabric_name LIKE'=>'%'.trim($this->request->data['search']['value']).'%');
			$conditions['OR'] += array('color LIKE'=>'%'.trim($this->request->data['search']['value']).'%');
			$conditions['OR'] += array('CONCAT(fabric_name,\' \',color) LIKE'=>'%'.trim($this->request->data['search']['value']).'%');
			$conditions['OR'] += array('CONCAT(color,\' \',fabric_name) LIKE'=>'%'.trim($this->request->data['search']['value']).'%');

			$conditions['OR'] += array('vendor_fabric_name LIKE'=>'%'.trim($this->request->data['search']['value']).'%');
			$conditions['OR'] += array('vendor_color_name LIKE'=>'%'.trim($this->request->data['search']['value']).'%');
			$conditions['OR'] += array('CONCAT(vendor_fabric_name,\' \',vendor_color_name) LIKE'=>'%'.trim($this->request->data['search']['value']).'%');
			$conditions['OR'] += array('CONCAT(vendor_color_name,\' \',vendor_fabric_name) LIKE'=>'%'.trim($this->request->data['search']['value']).'%');
			
		}
		
		$order=array();
		if(isset($this->request->data['order'][0]['column'])){
			switch($this->request->data['order'][0]['column']){
				case 2:
					$order += array('id' => $this->request->data['order'][0]['dir']);
				break;
				case 3:
			        $order=array('fabric_name'=> $this->request->data['order'][0]['dir']);
			    break;
			    case 4:
			        $order=array('color'=>$this->request->data['order'][0]['dir']);
			    break;
			    default:
			        $order=array('fabric_name'=> 'asc');
			    break;
			}
		}else{
		    $order=array('fabric_name'=>'ASC');
		}
		
		$fabricfind=$this->Fabrics->find('all',['conditions'=>$conditions,'order'=>$order])->offset($this->request->data['start'])->limit($this->request->data['length'])->hydrate(false)->toArray();
		$totalFilteredRows=$this->Fabrics->find('all',['conditions'=>$conditions])->count();
		foreach($fabricfind as $fabric){
			
			if(!file_exists($_SERVER['DOCUMENT_ROOT'].'/webroot/files/fabrics/'.$fabric['id'].'/'.$fabric['image_file'])){
				$images = '<img src="/img/noimg.jpg" width="75" height="75" class="fabricimg" />';
			}else{
				$images='<img src="/files/fabrics/'.$fabric['id'].'/'.$fabric['image_file'].'" width="75" height="75" class="fabricimg" />';
			}
			
			$price='$'.number_format($fabric['price_per_yard'],2,'.',',').' / yd';
			/*
			if($fabric['cost_per_yard_cut']==$fabric['cost_per_yard_bolt'] && $fabric['cost_per_yard_cut']==$fabric['cost_per_yard_case']){
				//just 1 price
				$cost='$'.number_format($fabric['cost_per_yard_cut'],2,'.',',').' / yd';
			}else{
				//determine if there's a case price
				if(floatval($fabric['cost_per_yard_bolt']) > floatval($fabric['cost_per_yard_case'])){
					$cost='$'.number_format($fabric['cost_per_yard_case'],2,'.',',').' &mdash; $'.number_format($fabric['cost_per_yard_cut'],2,'.',',').' / yd';
				}else{
					$cost='$'.number_format($fabric['cost_per_yard_bolt'],2,'.',',').' &mdash; $'.number_format($fabric['cost_per_yard_cut'],2,'.',',').' / yd';
				}
			}
			*/

			$cost='Cut: $'.number_format($fabric['cost_per_yard_cut'],2,'.',',')."<br><em>".date("n/j/Y",$fabric['cut_price_last_updated'])."</em><br><br>";
			$cost .= 'Bolt: $'.number_format($fabric['cost_per_yard_bolt'],2,'.',',')."<br><em>".date("n/j/Y",$fabric['bolt_price_last_updated'])."</em><br><br>";
			$cost .= 'Case: $'.number_format($fabric['cost_per_yard_case'],2,'.',',')."<br><em>".date("n/j/Y",$fabric['case_price_last_updated'])."</em>";

			$products='<ul>';
			if($fabric['use_in_cc']==1){
				$products .= '<li>Cubicle Curtains</li>';
			}
			if($fabric['use_in_bs']==1){
				$products .= '<li>Bedspreads</li>';
			}
			if($fabric['use_in_window']==1){
				$products .= '<li>Window Treatments</li>';
			}
			if($fabric['use_in_sc']==1){
				$products .= '<li>Shower Curtains</li>';
			}
			$products .= '</ul>';
			if($fabric['antimicrobial']==1){
				$antimicrobial='<img src="/img/Ok-icon.png" width="16" height="16" />';
			}else{
				$antimicrobial='<img src="/img/delete.png" width="16" height="16" />';
			}
			if($fabric['railroaded']==1){
				$railroaded='<img src="/img/Ok-icon.png" width="16" height="16" />';
			}else{
				$railroaded='<img src="/img/delete.png" width="16" height="16" />';
			}
			if($fabric['vendors_id'] >0){
				$thisVendor=$this->Vendors->get($fabric['vendors_id'])->toArray();
				$vendor=$thisVendor['vendor_name'];
			}else{
				$vendor='&nbsp;';
			}
			

			$flammability = $fabric['flammability'];
			

			$vendorname="<br><em>(".$fabric['vendor_fabric_name'].")</em>";


			$vendorcolor="<br><em>(".$fabric['vendor_color_name'].")</em>";


			if(strlen(trim($fabric['description'])) >0){
				$description='<div class="fabricdescription"><B>Description:</B><br>'.nl2br($fabric['description']).'</div>';
			}else{
				$description='';
			}
			

			$fabrics[]=array(
				'DT_RowId'=>'row_'.$fabric['id'],
				'DT_RowClass'=>'rowtest',
				'0'=>'<input type="checkbox" name="bulkedit_'.$fabric['id'].'" data-fabricid="'.$fabric['id'].'" value="yes" />',
				'1' => $images,
				'2' => $fabric['id'],
				'3' => $fabric['fabric_name'].$vendorname.$description,
				'4' => $fabric['color'].$vendorcolor,
				'5' => '<b>'.$flammability.'</b>',
				'6' => $fabric['fabric_width'],
				'7' => $railroaded,
				'8' => $antimicrobial,
				'9' => $vendor,
				'10' => $cost,
				'11' => $products,
				'12' => $fabric['status'],
				'13' => $fabric['collection'],
				'14' => '<a href="/products/fabrics/edit/'.$fabric['id'].'/"><img src="/img/edit.png" alt="Edit This Fabric" title="Edit This Fabric" /></a> <a href="/products/fabrics/clone/'.$fabric['id'].'/"><img src="/img/clone.png" alt="Duplicate This Fabric" title="Duplicate This Fabric" /></a> <a href="/products/fabrics/delete/'.$fabric['id'].'/"><img src="/img/delete.png" alt="Delete This Fabric" title="Delete This Fabric" /></a>'
			);
		}
		$this->set('draw',$this->request->data['draw']);
		$this->set('recordsTotal',$overallTotalRows);
		$this->set('recordsFiltered',$totalFilteredRows);
		$this->set('data',$fabrics);
		$this->set('_serialize',['draw','recordsTotal','recordsFiltered','data']);
	}
	
	public function getliningslist(){
		$linings=array();
		$overallTotalRows=$this->Linings->find()->count();
		$conditions=array();
		if(isset($this->request->data['search']['value']) && strlen(trim($this->request->data['search']['value'])) >0){
			//this is a search
			$conditions['OR']=array();
			$conditions['OR'] += array('title LIKE'=>'%'.trim($this->request->data['search']['value']).'%');
			$conditions['OR'] += array('short_title LIKE'=>'%'.trim($this->request->data['search']['value']).'%');
		}
		$liningfind=$this->Linings->find('all',['conditions'=>$conditions,'order'=>$order])->offset($this->request->data['start'])->limit($this->request->data['length'])->hydrate(false)->toArray();
		$totalFilteredRows=$this->Linings->find('all',['conditions'=>$conditions])->count();
		foreach($liningfind as $lining){
			$price='$'.number_format($lining['price'],2,'.',',');
			if($lining['vendors_id'] >0){
				$thisVendor=$this->Vendors->get($lining['vendors_id'])->toArray();
				$vendor=$thisVendor['vendor_name'];
			}else{
				$vendor='&nbsp;';
			}
			$linings[]=array(
				'DT_RowId'=>'row_'.$lining['id'],
				'DT_RowClass'=>'rowtest',
				'0' => $lining['title'],
				'1' => $lining['width']."&quot;",
				'2' => $price,
				'3' => '<a href="/products/linings/edit/'.$lining['id'].'/"><img src="/img/edit.png" alt="Edit This Lining" title="Edit This Lining" /></a> <a href="/products/linings/clone/'.$lining['id'].'/"><img src="/img/clone.png" alt="Duplicate This Lining" title="Duplicate This Lining" /></a> <a href="/products/linings/delete/'.$lining['id'].'/"><img src="/img/delete.png" alt="Delete This Lining" title="Delete This Lining" /></a>'
			);
		}
		$this->set('draw',$this->request->data['draw']);
		$this->set('recordsTotal',$overallTotalRows);
		$this->set('recordsFiltered',$totalFilteredRows);
		$this->set('data',$linings);
		$this->set('_serialize',['draw','recordsTotal','recordsFiltered','data']);
	}
	public function bedspreads($subaction='default',$bsid=0){
		$this->autoRender=false;
		$sizes=$this->BedspreadSizes->find('all',['conditions'=>['status'=>'Active'],'order'=>['id'=>'asc']]);
		$this->set('sizes',$sizes);
		switch($subaction){
			default:
			case "default":
				$this->render('bedspreads');
			break;
			case "add":
				$fabricImages=array();
				$allfabrics=$this->Fabrics->find('all',['conditions'=>['use_in_bs'=>1],'order'=>['fabric_name'=>'asc']])->toArray();
				foreach($allfabrics as $fabric){
					$fabricImages[$fabric['id']]=$fabric['image_file'];
				}
				$this->set('fabricImages',$fabricImages);
				$fabricsfind=$this->Fabrics->find('all',['conditions'=>['status'=>'Active','use_in_bs'=>1],'order'=>['fabric_name'=>'asc','color'=>'asc']])->toArray();
				$thefabrics=array();
				$usedfabrics=array();
				foreach($fabricsfind as $fabric){
					if(!in_array($fabric['fabric_name'],$usedfabrics)){
						$thefabrics[$fabric['fabric_name']]=array();
						$usedfabrics[]=$fabric['fabric_name'];
					}
					$thefabrics[$fabric['fabric_name']][$fabric['id']]=array('color'=>$fabric['color'],'id'=>$fabric['id'],'image'=>$fabric['image_file']);
				}
				$this->set('thefabrics',$thefabrics);
				if($this->request->data){
					$colorsArr=array();
					foreach($this->request->data as $key => $val){
						if(substr($key,0,4)=="use_" && preg_match("#color_#i",$key)){
							$colorexp=explode("_color_",$key);
							$colorsArr[]=$colorexp[1];
						}
					}
					$bsTable=TableRegistry::get('Bedspreads');
					$newBS=$bsTable->newEntity();
					$newBS->title=$this->request->data['title'];
					$newBS->status='Active';
					$newBS->description=$this->request->data['description'];
					$newBS->fabric_name=$this->request->data['fabric_name'];
					$newBS->qb_item_code=$this->request->data['qb_item_code'];
					$newBS->mattress_size=$this->request->data['mattress_size'];
					$newBS->available_colors=json_encode($colorsArr);
					if(isset($this->request->data['primary_image']['name']) && strlen(trim($this->request->data['primary_image']['name'])) >0){
						$filename=$this->request->data['primary_image']['name'];
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
						$newfilename=time()."_".$filename;
						$newBS->image_file=$newfilename;
					}
					if($bsTable->save($newBS)){
						$mapModel=TableRegistry::get('BsDataMap');
						foreach($sizes as $size){
							if($this->request->data["size_".$size['id']."_enabled"] == 1){
								//see if this size combo exists in map model
								$maplookup=$this->BsDataMap->find('all',['conditions'=>['bs_id' => $bsid, 'size_id' => $size['id']]])->toArray();
								if(count($maplookup) == 0){
									//create it
									$newMapEntry=$mapModel->newEntity();
									$newMapEntry->bs_id=$newBS->id;
									$newMapEntry->size_id=$size['id'];
									$newMapEntry->price=$this->request->data["size_".$size['id']."_price"];
									$newMapEntry->difficulty=$this->request->data["size_".$size['id']."_difficulty"];
									$newMapEntry->weight=$this->request->data["size_".$size['id']."_weight"];
									$newMapEntry->yards=$this->request->data["size_".$size['id']."_yards"];
									$mapModel->save($newMapEntry);
								}
							}
					}
						//handle image upload
						@mkdir($_SERVER['DOCUMENT_ROOT'].'/webroot/files/bedspreads/'.$newBS->id);
						if(isset($this->request->data['primary_image']['name']) && strlen(trim($this->request->data['primary_image']['name'])) >0){
							move_uploaded_file($this->request->data['primary_image']['tmp_name'],$_SERVER['DOCUMENT_ROOT'].'/webroot/files/bedspreads/'.$newBS->id.'/'.$newfilename);
						}
						$this->Flash->success('Successfully added new Bedspread "'.$this->request->data['title'].'"');
						$this->logActivity($_SERVER['REQUEST_URI'],"Added Bedspread \"".$this->request->data['title']."\"");
						return $this->redirect('/products/bedspreads/');
					}
				}else{
					$this->render('addbs');
				}
			break;
			case "edit":
				$this->set('settings',$this->getsettingsarray());
				if($this->request->data){
					$oldBSData=$this->Bedspreads->get($bsid)->toArray();
					//print_r($this->request->data);exit;
					$bsTable=TableRegistry::get('Bedspreads');
					$thisBS=$bsTable->get($bsid);
					foreach($sizes as $size){
							if($this->request->data["size_".$size['id']."_enabled"] == 1){
								$mapModel=TableRegistry::get('BsDataMap');
								//see if this size combo exists in map model
								$maplookup=$this->BsDataMap->find('all',['conditions'=>['bs_id' => $bsid, 'size_id' => $size['id']]])->toArray();
								if(count($maplookup) == 0){
									//create it
									$newMapEntry=$mapModel->newEntity();
									$newMapEntry->bs_id=$bsid;
									$newMapEntry->size_id=$size['id'];
									$newMapEntry->price=$this->request->data["size_".$size['id']."_price"];
									$newMapEntry->difficulty=$this->request->data["size_".$size['id']."_difficulty"];
									$newMapEntry->weight=$this->request->data["size_".$size['id']."_weight"];
									$newMapEntry->yards=$this->request->data["size_".$size['id']."_yards"];
									$newMapEntry->quilting_pattern = $this->request->data["size_".$size['id']."_quiltingpattern"];
									$newMapEntry->vertical_repeat = $this->request->data["size_".$size['id']."_vertrepeat"];
									$newMapEntry->top_width_l = $this->request->data["size_".$size['id']."_topwidthl"];
									$newMapEntry->drop_width_l = $this->request->data["size_".$size['id']."_dropwidthl"];
									$newMapEntry->top_cuts_w = $this->request->data["size_".$size['id']."_topcutsw"];
									$newMapEntry->drop_cuts_w = $this->request->data["size_".$size['id']."_dropcutsw"];
									$newMapEntry->layout = $this->request->data["size_".$size['id']."_layout"];
									$mapModel->save($newMapEntry);
								}else{
									//update it
									foreach($maplookup as $mapFindRow){
										$mapEntry=$mapModel->get($mapFindRow['id']);
										$mapEntry->price=$this->request->data["size_".$size['id']."_price"];
										$mapEntry->difficulty=$this->request->data["size_".$size['id']."_difficulty"];
										$mapEntry->weight=$this->request->data["size_".$size['id']."_weight"];
										$mapEntry->yards=$this->request->data["size_".$size['id']."_yards"];
										$mapEntry->quilting_pattern = $this->request->data["size_".$size['id']."_quiltingpattern"];
										$mapEntry->vertical_repeat = $this->request->data["size_".$size['id']."_vertrepeat"];
										$mapEntry->top_width_l = $this->request->data["size_".$size['id']."_topwidthl"];
										$mapEntry->drop_width_l = $this->request->data["size_".$size['id']."_dropwidthl"];
										$mapEntry->top_cuts_w = $this->request->data["size_".$size['id']."_topcutsw"];
										$mapEntry->drop_cuts_w = $this->request->data["size_".$size['id']."_dropcutsw"];
										$mapEntry->layout = $this->request->data["size_".$size['id']."_layout"];
										$mapModel->save($mapEntry);
									}
								}
							}else{
								//if there's an entry in the data map, remove it
								$maplookup=$this->BsDataMap->find('all',['conditions'=>['bs_id' => $bsid, 'size_id' => $size['id']]])->toArray();
								if(count($maplookup) > 0){
									foreach($maplookup as $mapFindRow){
										$this->BsDataMap->delete($this->CcDataMap->get($mapFindRow['id']));
									}
								}
							}
					}
					$colorsArr=array();
					$thisFabricColors=$this->Fabrics->find('all',['conditions' => ['fabric_name' => $this->request->data['fabric_name']]])->toArray();
					foreach($thisFabricColors as $fabcolor){
						if(isset($this->request->data["use_".str_replace(" ","_",$fabcolor['fabric_name'])."_color_".str_replace(" ","_",$fabcolor['color'])]) && $this->request->data["use_".str_replace(" ","_",$fabcolor['fabric_name'])."_color_".str_replace(" ","_",$fabcolor['color'])] == "yes"){
							$colorsArr[]=$fabcolor['color'];
						}
					}
					$thisBS->title=$this->request->data['title'];
					$thisBS->status=$this->request->data['status'];
					$thisBS->qb_item_code=$this->request->data['qb_item_code'];
					$thisBS->description=$this->request->data['description'];
					$thisBS->fabric_id=$this->request->data['fabric_id'];
					//$thisCC->available_sizes=json_encode($sizesArr);
					$thisBS->available_colors=json_encode($colorsArr);
					$thisBS->quilted=$this->request->data['quilted'];
					$thisBS->mattress_size = $this->request->data['mattress_size'];
					if(isset($this->request->data['new_image']['name']) && strlen(trim($this->request->data['new_image']['name'])) >0){
						$filename=$this->request->data['new_image']['name'];
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

						$newfilename=time()."_".$filename;
						@mkdir($_SERVER['DOCUMENT_ROOT'].'/webroot/files/bedspreads/'.$thisBS->id);
						@unlink($_SERVER['DOCUMENT_ROOT'].'/webroot/files/bedspreads/'.$thisBS->id.'/'.$oldCCData['image_file']);
						move_uploaded_file($this->request->data['new_image']['tmp_name'],$_SERVER['DOCUMENT_ROOT'].'/webroot/files/bedspreads/'.$thisBS->id.'/'.$newfilename);
						$thisBS->image_file=$newfilename;
					}
					if($bsTable->save($thisBS)){
						$this->Flash->success('Successfully saved changes to Bedspread "'.$this->request->data['title'].'"');
						$this->logActivity($_SERVER['REQUEST_URI'],"Edited Bedspread \"".$this->request->data['title']."\"");
						return $this->redirect('/products/bedspreads/');
					}
				}else{
					$fabricImages=array();
					$allfabrics=$this->Fabrics->find('all',['conditions'=>['use_in_bs'=>1]])->toArray();
					foreach($allfabrics as $fabric){
						$fabricImages[$fabric['id']]=$fabric['image_file'];
					}
					$this->set('fabricImages',$fabricImages);
					$fabricsfind=$this->Fabrics->find('all',['conditions'=>['status'=>'Active','use_in_bs'=>1],'order'=>['fabric_name'=>'asc','color'=>'asc']])->toArray();
					$thefabrics=array();
					$usedfabrics=array();
					foreach($fabricsfind as $fabric){
						if(!in_array($fabric['fabric_name'],$usedfabrics)){
							$thefabrics[$fabric['fabric_name']]=array();
							$usedfabrics[]=$fabric['fabric_name'];
						}
						$thefabrics[$fabric['fabric_name']][$fabric['id']]=array('color'=>$fabric['color'],'id'=>$fabric['id'],'image'=>$fabric['image_file']);
					}
					$this->set('thefabrics',$thefabrics);
					$bsData=$this->Bedspreads->get($bsid)->toArray();
					$this->set('bsData',$bsData);
					$availableSizes=array();
					$getsizes=$this->BsDataMap->find('all',['conditions'=>['bs_id'=>$bsid],'order'=>['size_id'=>'ASC']])->toArray();
					foreach($getsizes as $size){
						$availableSizes[$size['id']]=array(
							'id' => $size['size_id'],
							'price' => $size['price'],
							'difficulty' => $size['difficulty'],
							'weight' => $size['weight'],
							'yards' => $size['yards'],
							'quilting_pattern' => $size['quilting_pattern'],
							'vertical_repeat' => $size['vertical_repeat'],
							'top_width_l' => $size['top_width_l'],
							'drop_width_l' => $size['drop_width_l'],
							'top_cuts_w' => $size['top_cuts_w'],
							'drop_cuts_w' => $size['drop_cuts_w'],
							'layout' => $size['layout']
						);
					}
					$this->set('availableSizes',$availableSizes);
					$this->render('editbs');
				}
			break;
			case "delete":
				$thisBS=$this->Bedspreads->get($bsid)->toArray();
				$this->set('bsData',$thisBS);
				if($this->request->data){
					if($this->Bedspreads->delete($this->Bedspreads->get($bsid))){
						$this->Flash->success('Successfully deleted selected Bedspreads');
						$this->logActivity($_SERVER['REQUEST_URI'],"Deleted Bedspread \"".$thisBS['title']."\"");
						$this->redirect('/products/bedspreads/');
					}
				}else{
					$this->render('deletebs');
				}
			break;
			case "clone":
				$thisBS=$this->Bedspreads->get($bsid)->toArray();
				$this->set('bsData',$thisBS);
				$fabricImages=array();
				$allfabrics=$this->Fabrics->find('all')->toArray();
				foreach($allfabrics as $fabric){
					$fabricImages[$fabric['id']]=$fabric['image_file'];
				}
				$this->set('fabricImages',$fabricImages);
				$fabricsfind=$this->Fabrics->find('all',['conditions'=>['status'=>'Active',['OR'=>['use_in_bs'=>1]]],'order'=>['fabric_name'=>'asc','color'=>'asc']])->toArray();
				$thefabrics=array();
				$usedfabrics=array();
				foreach($fabricsfind as $fabric){
					if(!in_array($fabric['fabric_name'],$usedfabrics)){
						$thefabrics[$fabric['fabric_name']]=array();
						$usedfabrics[]=$fabric['fabric_name'];
					}
					$thefabrics[$fabric['fabric_name']][$fabric['id']]=array('color'=>$fabric['color'],'id'=>$fabric['id'],'image'=>$fabric['image_file']);
				}
				$this->set('thefabrics',$thefabrics);
				$sizes=$this->BedspreadSizes->find('all')->toArray();
				$this->set('allSizes',$sizes);
				$this->set('settings',$this->getsettingsarray());
				if($this->request->data){
					$colorsArr=array();
					foreach($this->request->data as $key => $val){
						if(substr($key,0,4)=="use_" && preg_match("#color_#i",$key)){
							$colorexp=explode("_color_",$key);
							$colorsArr[]=$colorexp[1];
						}
					}
					$bsTable=TableRegistry::get('Bedspreads');
					$newBS=$bsTable->newEntity();
					$newBS->title=$this->request->data['title'];
					$newBS->status='Active';
					$newBS->description=$this->request->data['description'];
					$newBS->fabric_name=$this->request->data['fabric_name'];
					$newBS->qb_item_code=$this->request->data['qb_item_code'];
					$newBS->mattress_size = $thisBS['mattress_size'];
					$docopy=false;
					$newBS->available_colors=json_encode($colorsArr);
					$newBS->quilted=$this->request->data['quilted'];
					if(isset($this->request->data['primary_image']['name']) && strlen(trim($this->request->data['primary_image']['name'])) >0){
						$filename=$this->request->data['primary_image']['name'];
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
						$newfilename=time()."_".$filename;
						$newBS->image_file=$newfilename;
					}else{
						$newBS->image_file=$thisBS['image_file'];
						$docopy=true;
					}
					if($bsTable->save($newBS)){
						//handle image upload
						@mkdir($_SERVER['DOCUMENT_ROOT'].'/webroot/files/bedspreads/'.$newBS->id);
						if($docopy){
							@copy($_SERVER['DOCUMENT_ROOT'].'/webroot/files/bedspreads/'.$thisBS['id'].'/'.$thisBS['image_file'],$_SERVER['DOCUMENT_ROOT'].'/webroot/files/bedspreads/'.$newBS->id.'/'.$thisBS['image_file']);
						}else{
							if(isset($this->request->data['primary_image']['name']) && strlen(trim($this->request->data['primary_image']['name'])) >0){
								move_uploaded_file($this->request->data['primary_image']['tmp_name'],$_SERVER['DOCUMENT_ROOT'].'/webroot/files/bedspreads/'.$newBS->id.'/'.$newfilename);
							}
						}
						$mapModel=TableRegistry::get('BsDataMap');
						foreach($sizes as $size){
							if($this->request->data["size_".$size['id']."_enabled"] == 1){
								//create ccdatamap entry for this enabled size
								$newMapEntry=$mapModel->newEntity();
								$newMapEntry->bs_id=$newBS->id;
								$newMapEntry->size_id=$size['id'];
								$newMapEntry->price=$this->request->data["size_".$size['id']."_price"];
								$newMapEntry->difficulty=$this->request->data["size_".$size['id']."_difficulty"];
								$newMapEntry->weight=$this->request->data["size_".$size['id']."_weight"];
								$newMapEntry->yards=$this->request->data["size_".$size['id']."_yards"];
								$newMapEntry->quilting_pattern = $this->request->data["size_".$size['id']."_quiltingpattern"];
								$newMapEntry->vertical_repeat = $this->request->data["size_".$size['id']."_vertrepeat"];
								$newMapEntry->top_width_l = $this->request->data["size_".$size['id']."_topwidthl"];
								$newMapEntry->drop_width_l = $this->request->data["size_".$size['id']."_dropwidthl"];
								$newMapEntry->top_cuts_w = $this->request->data["size_".$size['id']."_topcutsw"];
								$newMapEntry->drop_cuts_w = $this->request->data["size_".$size['id']."_dropcutsw"];
								$newMapEntry->layout = $this->request->data["size_".$size['id']."_layout"];
								$mapModel->save($newMapEntry);
							}
						}
						$this->Flash->success('Successfully added new Bedspread "'.$this->request->data['title'].'"');
						$this->logActivity($_SERVER['REQUEST_URI'],"Added Bedspread \"".$this->request->data['title']."\"");
						return $this->redirect('/products/bedspreads/');
					}
				}else{
					$availableSizes=array();
					//lookup CC's with the selected fabric name
					$isQuilted=$thisBS['quilted'];
					$getsizes=$this->BsDataMap->find('all',['conditions'=>['bs_id'=>$thisBS['id']]])->toArray();
					foreach($getsizes as $size){
						$thisSize=$this->BedspreadSizes->get($size['size_id'])->toArray();
						$availableSizes[$size['id']]=array(
						'id' => $size['size_id'],
						'price' => $size['price'],
						'difficulty' => $size['difficulty'],
						'weight' => $size['weight'],
						'yards' => $size['yards'],
						'quilting_pattern' => $size['quilting_pattern'],
						'vertical_repeat' => $size['vertical_repeat'],
						'top_width_l' => $size['top_width_l'],
						'drop_width_l' => $size['drop_width_l'],
						'top_cuts_w' => $size['top_cuts_w'],
						'drop_cuts_w' => $size['drop_cuts_w'],
						'layout' => $size['layout']
						);
					}
					/*
					echo "<pre>";
					print_r($availableSizes);
					echo "</pre>";
					exit;
					*/
					$this->set('availableSizes',$availableSizes);
					$this->render('clonebs');
				}
			break;
		}
	}
	public function trackSystems($cmdaction='',$itemid=false){
		$this->autoRender=false;
		switch($cmdaction){
			case "add":
				if($this->request->data){
					$tsTable=TableRegistry::get('TrackSystems');
					$newTS=$tsTable->newEntity();
					$newTS->title=$this->request->data['title'];
					$newTS->status=$this->request->data['status'];
					$newTS->description=$this->request->data['description'];
					$newTS->qb_item_code=$this->request->data['qb_item_code'];
					$newTS->price=$this->request->data['price'];
					$newTS->system_or_component=$this->request->data['system_or_component'];
					$newTS->unit=$this->request->data['unit'];
					$newTS->inches_equivalent = $this->request->data['inches_equivalent'];
					if(isset($this->request->data['primary_image']['name']) && strlen(trim($this->request->data['primary_image']['name'])) >0){
						$filename=$this->request->data['primary_image']['name'];
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
						$newfilename=time()."_".$filename;
						$newTS->primary_image=$newfilename;
					}
					if($tsTable->save($newTS)){
						//handle image upload
						@mkdir($_SERVER['DOCUMENT_ROOT'].'/webroot/files/track-systems/'.$newTS->id);
						if(isset($this->request->data['primary_image']['name']) && strlen(trim($this->request->data['primary_image']['name'])) >0){
							move_uploaded_file($this->request->data['primary_image']['tmp_name'],$_SERVER['DOCUMENT_ROOT'].'/webroot/files/track-systems/'.$newTS->id.'/'.$newfilename);
						}
						$this->Flash->success('Successfully added new Track System Component "'.$this->request->data['title'].'"');
						$this->logActivity($_SERVER['REQUEST_URI'],"Added Track System Component \"".$this->request->data['title']."\"");
						return $this->redirect('/products/track-systems/');
					}
				}else{
					$this->render('addts');
				}
			break;
			case "edit":
				$thisTS=$this->TrackSystems->get($itemid)->toArray();
				$this->set('tsData',$thisTS);
				if($this->request->data){
					$oldTSData=$thisTS;
					$tsTable=TableRegistry::get('TrackSystems');
					$editTS=$tsTable->get($itemid);
					$editTS->title=$this->request->data['title'];
					$editTS->description = $this->request->data['description'];
					$editTS->qb_item_code=$this->request->data['qb_item_code'];
					$editTS->price=$this->request->data['price'];
					$editTS->unit=$this->request->data['unit'];
					$editTS->system_or_component=$this->request->data['system_or_component'];
					$editTS->status=$this->request->data['status'];
					$editTS->inches_equivalent = $this->request->data['inches_equivalent'];
					if(isset($this->request->data['new_image']['name']) && strlen(trim($this->request->data['new_image']['name'])) >0){
						$filename=$this->request->data['new_image']['name'];
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
						$newfilename=time()."_".$filename;
						@mkdir($_SERVER['DOCUMENT_ROOT'].'/webroot/files/track-systems/'.$thisTS['id']);
						@unlink($_SERVER['DOCUMENT_ROOT'].'/webroot/files/track-systems/'.$thisTS['id'].'/'.$oldTSData['primary_image']);
						move_uploaded_file($this->request->data['new_image']['tmp_name'],$_SERVER['DOCUMENT_ROOT'].'/webroot/files/track-systems/'.$thisTS['id'].'/'.$newfilename);
						$editTS->primary_image=$newfilename;
					}
					if($tsTable->save($editTS)){
						$this->Flash->success('Successfully saved changes to Track Item "'.$this->request->data['title'].'"');
						$this->logActivity($_SERVER['REQUEST_URI'],"Saved Changes to Track Item \"".$this->request->data['title']."\"");
						return $this->redirect('/products/track-systems/');
					}
				}else{
					$this->render('editts');
				}
			break;
			case "delete":
			break;
			case "clone":
			break;
			default:
				$this->render('track-systems');
			break;
		}
	}
	public function miscellaneous($subaction='default'){
		switch($subaction){
			default:
			case "default":
			break;
			case "add":
			break;
			case "edit":
			break;
			case "delete":
			break;
		}
	}
	public function bulkeditfabric($editfabrics){
		//$this->autoRender=false;
		//$this->viewBuilder()->layout('iframeinner');
		$params = array();
		$fixedparams=array();
		parse_str(urldecode($editfabrics), $params);
		foreach($params as $key => $val){
			if($val=="yes"){
				$fixedparams[]=str_replace("bulkedit_","",$key);
			}
		}
		if($this->request->data['process']=="step2"){
			//review stuff
			$this->set('step','step2');
			$this->set('fabricids',$fixedparams);
			$theFabrics=$this->Fabrics->find('all',['conditions'=>['id IN'=>$fixedparams]])->toArray();
			$this->set('selectedfabrics',$theFabrics);
			$this->set('inputdata',$this->request->data);
			//$vendors=$this->Vendors->find('all')->toArray();
			$vendors=$this->Vendors->find('all',['conditions'=>['status'=>'Active'],'order'=>['vendor_name'=>'asc']])->toArray();
			$vendorslist=array();
			foreach($vendors as $vendor){
				$vendorslist[$vendor['id']]=$vendor['vendor_name'];
			}
			$this->set('vendorslist',$vendorslist);
		}elseif($this->request->data['process']=="step3"){
			$this->autoRender=false;
			$fabricTable=TableRegistry::get('Fabrics');
			foreach($fixedparams as $fabricid){
				$thisFabric=$fabricTable->get($fabricid);
				if($this->request->data['ownership'] == 'roster-fabric'){
					$thisFabric->is_hci_fabric=1;
					$thisFabric->com_fabric=0;
				}elseif($this->request->data['ownership'] == 'mom-nonroster'){
					$thisFabric->is_hci_fabric=0;
					$thisFabric->com_fabric=0;
				}elseif($this->request->data['ownership'] == 'com-fabric'){
					$thisFabric->is_hci_fabric=0;
					$thisFabric->com_fabric=1;
				}
				if($this->request->data['antimicrobial']=="Yes"){
					$thisFabric->antimicrobial=1;
				}elseif($this->request->data['antimicrobial']=="No"){
					$thisFabric->antimicrobial=0;
				}else{
					//do not update this field
				}
				if($this->request->data['railroaded']=="Yes"){
					$thisFabric->railroaded=1;
				}elseif($this->request->data['railroaded']=="No"){
					$thisFabric->railroaded=0;
				}else{
					//do not update this field
				}
				if($this->request->data['quilted']=="Yes"){
					$thisFabric->quilted=1;
				}elseif($this->request->data['quilted']=="No"){
					$thisFabric->quilted=0;
				}else{
					//do not update this field
				}
				if($this->request->data['vendors_id'] != '0' && $this->request->data['vendors_id'] != ''){
					$thisFabric->vendors_id=$this->request->data['vendors_id'];
				}
				if(strlen($this->request->data['vendor_fabric_name']) >0){
					$thisFabric->vendor_fabric_name=$this->request->data['vendor_fabric_name'];
				}
				if($this->request->data['country_of_origin'] != '0' && $this->request->data['country_of_origin'] != ''){
					$thisFabric->country_of_origin=$this->request->data['country_of_origin'];
				}
				if($this->request->data['used_in_cc'] == "Yes"){
					$thisFabric->use_in_cc=1;
				}elseif($this->request->data['used_in_cc'] == "No"){
					$thisFabric->use_in_cc=0;
				}else{
					//do not update this field
				}
				if($this->request->data['used_in_bs'] == "Yes"){
					$thisFabric->use_in_bs=1;
				}elseif($this->request->data['used_in_bs'] == "No"){
					$thisFabric->use_in_bs=0;
				}else{
					//do not update this field
				}
				if($this->request->data['used_in_wt'] == "Yes"){
					$thisFabric->use_in_window=1;
				}elseif($this->request->data['used_in_wt'] == "No"){
					$thisFabric->use_in_window=0;
				}else{
					//do not update this field
				}
				if($this->request->data['used_in_sc'] == "Yes"){
					$thisFabric->use_in_sc=1;
				}elseif($this->request->data['used_in_sc'] == "No"){
					$thisFabric->use_in_sc=0;
				}else{
					//do not update this field
				}
				
				if(strlen($this->request->data['cost_per_yard_cut']) >0){
					$thisFabric->cost_per_yard_cut=$this->request->data['cost_per_yard_cut'];
					$thisFabric->cut_price_last_updated=time();
				}
				if(strlen($this->request->data['cost_per_yard_bolt']) >0){
					$thisFabric->cost_per_yard_bolt=$this->request->data['cost_per_yard_bolt'];
					$thisFabric->bolt_price_last_updated=time();
				}
				if(strlen($this->request->data['cost_per_yard_case']) >0){
					$thisFabric->cost_per_yard_case=$this->request->data['cost_per_yard_case'];
					$thisFabric->case_price_last_updated=time();
				}
				
				
				if($this->request->data['fabric_status'] == 'Active'){
					$thisFabric->status='Active';
				}elseif($this->request->data['fabric_status']=='Discontinued'){
					$thisFabric->status='Discontinued';
				}else{
					//do not update this field
				}
				if(strlen($this->request->data['yards_per_bolt']) >0){
					$thisFabric->yards_per_bolt=$this->request->data['yards_per_bolt'];
				}
				if(strlen($this->request->data['yards_per_case']) >0){
					$thisFabric->yards_per_case=$this->request->data['yards_per_case'];
				}
				if(strlen($this->request->data['fabric_width']) >0){
					$thisFabric->fabric_width=$this->request->data['fabric_width'];
				}
				if(strlen($this->request->data['vertical_repeat']) >0){
					$thisFabric->vertical_repeat=$this->request->data['vertical_repeat'];
				}
				if(strlen($this->request->data['horizontal_repeat']) >0){
					$thisFabric->horizontal_repeat=$this->request->data['horizontal_repeat'];
				}
				if(strlen($this->request->data['weight_per_sqin']) >0){
					$thisFabric->weight_per_sqin=$this->request->data['weight_per_sqin'];
				}
				if(strlen($this->request->data['minimum']) >0){
					$thisFabric->minimum=$this->request->data['minimum'];
				}
				if(strlen($this->request->data['collection']) >0){
					$thisFabric->collection=$this->request->data['collection'];
				}
				if(strlen($this->request->data['material']) >0){
					$thisFabric->material=$this->request->data['material'];
				}
				if(isset($this->request->data['print_or_dye']) && strlen(trim($this->request->data['print_or_dye'])) >0){
					$thisFabric->print_or_dye=$this->request->data['print_or_dye'];
				}else{
					//do not update this field
				}
				if(strlen($this->request->data['weaves']) >0){
					$thisFabric->weaves=$this->request->data['weaves'];
				}
				if(strlen($this->request->data['finish']) >0){
					$thisFabric->finish=$this->request->data['finish'];
				}
				if(strlen($this->request->data['flammability']) >0){
					$thisFabric->flammability=$this->request->data['flammability'];
				}
				if(strlen($this->request->data['description']) >0){
					$thisFabric->description=$this->request->data['description'];
				}
				if(strlen($this->request->data['bs_backing_material']) >0){
					$thisFabric->bs_backing_material = $this->request->data['bs_backing_material'];
				}
				if(strlen($this->request->data['care_instructions']) >0){
					$thisFabric->care_instructions=$this->request->data['care_instructions'];
				}
				$fabricTable->save($thisFabric);
			}
			$this->Flash->success('Successfully saved changes to selected '.count($fixedparams).' fabrics');
			//echo "<html><body><script>parent.location.replace('/products/fabrics/');</script></body></html>";exit;
			return $this->redirect('/products/fabrics/');
		}else{
			$this->set('step','step1');
			$this->set('fabricids',$fixedparams);
			$theFabrics=$this->Fabrics->find('all',['conditions'=>['id IN'=>$fixedparams]])->toArray();
			$this->set('selectedfabrics',$theFabrics);
			//$vendors=$this->Vendors->find('all')->toArray();
			$vendors=$this->Vendors->find('all',['conditions'=>['status'=>'Active'],'order'=>['vendor_name'=>'asc']])->toArray();
			$vendorslist=array();
			foreach($vendors as $vendor){
				$vendorslist[$vendor['id']]=$vendor['vendor_name'];
			}
			$this->set('vendorslist',$vendorslist);
		}
	}
	public function uploadimagefilefromclipboard(){
		$this->autoRender=false;
		$time=time();
		$parts = explode(',',trim($this->request->data['imagefile']));
		$image = $parts[1];  
		// Decode
		$image = base64_decode($image);
		$file=fopen($_SERVER['DOCUMENT_ROOT'].'/webroot/files/fabrics/unassigned/'.$time.'.png','w');
		fwrite($file,$image);
		fclose($file);
		//crop it
		$img_r = imagecreatefrompng($_SERVER['DOCUMENT_ROOT'].'/webroot/files/fabrics/unassigned/'.$time.'.png');
		$dst_r = ImageCreateTrueColor( $this->request->data['w'], $this->request->data['h'] );
		list($imgwidth, $imgheight, $imgtype, $imgattr) = getimagesize($_SERVER['DOCUMENT_ROOT'].'/webroot/files/fabrics/unassigned/'.$time.'.png');
		imagecopyresampled($dst_r,$img_r,0,0,$this->request->data['x1'],$this->request->data['y1'],$this->request->data['w'],$this->request->data['h'],$this->request->data['w'],$this->request->data['h']);
		imagepng($dst_r,$_SERVER['DOCUMENT_ROOT'].'/webroot/files/fabrics/unassigned/'.$time.'_cropped.png');
		//echo "<img src=\"/files/fabrics/unassigned/".$time.".png\" width=\"225\"><hr>";
		//echo "IMG WIDTH: ".$imgwidth.", IMG HEIGHT: ".$imgheight."<hr>";
		echo "<input type=\"hidden\" name=\"croppedimagefilename\" value=\"".$time."_cropped.png\" /><img class=\"croppedimage\" src=\"/files/fabrics/unassigned/".$time."_cropped.png\">";
		//echo "<hr>";
		//echo "X1: ".$this->request->data['x1'].", X2: ".$this->request->data['x2']."<br>Y1: ".$this->request->data['y1'].", Y2:".$this->request->data['y2']."<br>W:".$this->request->data['w'].", H:".$this->request->data['h'];
		//echo "<hr>";
		//echo $parts[1];
	}
	public function assemblies($subaction=NULL,$subsubaction=NULL){
		$this->autoRender=false;
	}
	public function getRealColorName($fabricName,$strippedColor){
		$allcolors=$this->Fabrics->find('all',['conditions'=>['fabric_name' => $fabricName]])->toArray();
		foreach($allcolors as $color){
			if(str_replace(" ","_",str_replace("'","",$color['color'])) == $strippedColor){
				return $color['color'];
			}
		}
	}
	public function getspecialpricinglist($productType=false,$productID=false){
		$specialPrices=array();
		if($productType != 'all'){
			$conditions=array('product_type'=>$productType,'product_id'=>$productID);
			$overallTotalRows=$this->CustomerPricingExceptions->find('all',['conditions'=>$conditions])->count();
			
		}else{
			$overallTotalRows=$this->CustomerPricingExceptions->find('all')->count();
			$conditions=array();
		}
		
		if(isset($this->request->data['search']['value']) && strlen(trim($this->request->data['search']['value'])) >0){
			//this is a search
			$searchResults=0;
			
			//search Customers, search Product Name, search Fabrics+Fabric Colors
			$customersIN=array();
			$customerLookup=$this->Customers->find('all',['conditions'=>['company_name LIKE' => '%'.trim($this->request->data['search']['value']).'%']])->toArray();
			foreach($customerLookup as $customerRow){
				if(!in_array($customerRow['id'],$customersIN)){
					$customersIN[]=$customerRow['id'];
				}
			}
			if(count($customersIN) > 1){
				if(!isset($conditions['OR'])){
					$conditions['OR']=array();
				}
				$conditions['OR'] += array('customer_id IN' => $customersIN);
				$searchResults++;
			}elseif(count($customersIN) == 1){
				if(!isset($conditions['OR'])){
					$conditions['OR']=array();
				}
				$conditions['OR'] += array('customer_id' => $customersIN[0]);
				$searchResults++;
			}
			
					
			$CCproductsIN=array();
			$productLookupCC=$this->CubicleCurtains->find('all',['conditions'=>['title LIKE' => '%'.trim($this->request->data['search']['value']).'%']])->toArray();
			foreach($productLookupCC as $ccRow){
				if(!in_array($ccRow['id'],$CCproductIN)){
					$CCproductsIN[]=$ccRow['id'];
				}
			}
			if(count($CCproductsIN) > 1){
				if(!isset($conditions['OR'])){
					$conditions['OR']=array();
				}
				$conditions['OR'] += array('AND'=>array('product_type'=>'cc','product_id IN' => $CCproductsIN));
				$searchResults++;
			}elseif(count($CCproductsIN) == 1){
				if(!isset($conditions['OR'])){
					$conditions['OR']=array();
				}
				$conditions['OR'] += array('AND'=>array('product_type'=>'cc','product_id' => $CCproductsIN[0]));
				$searchResults++;
			}
			
			
			$BSproductsIN=array();
			$productLookupBS=$this->Bedspreads->find('all',['conditions'=>['title LIKE' => '%'.trim($this->request->data['search']['value']).'%']])->toArray();
			foreach($productLookupBS as $bsRow){
				if(!in_array($bsRow['id'],$BSproductsIN)){
					$BSproductsIN[]=$bsRow['id'];
				}
			}
			if(count($BSproductsIN) > 1){
				if(!isset($conditions['OR'])){
					$conditions['OR']=array();
				}
				$conditions['OR'] += array('AND'=>array('product_type'=>'bs','product_id IN' => $BSproductsIN));
				$searchResults++;
			}elseif(count($BSproductsIN) == 1){
				if(!isset($conditions['OR'])){
					$conditions['OR']=array();
				}
				$conditions['OR'] += array('AND'=>array('product_type'=>'bs','product_id' => $BSproductsIN[0]));
				$searchResults++;
			}
			
			
			$WTproductsIN=array();
			$productLookupWT=$this->WindowTreatments->find('all',['conditions'=>['title LIKE' => '%'.trim($this->request->data['search']['value']).'%']])->toArray();
			foreach($productLookupWT as $wtRow){
				if(!in_array($wtRow['id'],$WTproductsIN)){
					$WTproductsIN[]=$wtRow['id'];
				}
			}
			if(count($WTproductsIN) > 1){
				if(!isset($conditions['OR'])){
					$conditions['OR']=array();
				}
				$conditions['OR'] += array('AND'=>array('product_type'=>'wt','product_id IN' => $WTproductsIN));
				$searchResults++;
			}elseif(count($WTproductsIN) == 1){
				if(!isset($conditions['OR'])){
					$conditions['OR']=array();
				}
				$conditions['OR'] += array('AND'=>array('product_type'=>'wt','product_id' => $WTproductsIN[0]));
				$searchResults++;
			}
			
			
			if(count($conditions['OR']) == 1){
				$conditions=$conditions['OR'];
			}
			
			if($searchResults == 0){
				$this->set('draw',$this->request->data['draw']);
				$this->set('recordsTotal',0);
				$this->set('recordsFiltered',0);
				$this->set('data',array());
				$this->set('_serialize',['draw','recordsTotal','recordsFiltered','data']);
				return;
			}
			
			//print_r($conditions);exit;
			//$conditions['OR'] += array('fabric_name LIKE'=>'%'.trim($this->request->data['search']['value']).'%');
		}
		
		$orderby=array();
		if(isset($this->request->data['order'][0]['column'])){
			switch($this->request->data['order'][0]['column']){
				default:
					$orderby += array('id' => 'asc');
				break;
			}
		}
		$pricesfind=$this->CustomerPricingExceptions->find('all',['conditions'=>$conditions,'order'=>$orderby])->offset($this->request->data['start'])->limit($this->request->data['length'])->hydrate(false)->toArray();
		$totalFilteredRows=$this->CustomerPricingExceptions->find('all',['conditions'=>$conditions])->count();
		foreach($pricesfind as $price){
			switch($price['product_type']){
				case "cc":
					$product=$this->CubicleCurtains->get($price['product_id'])->toArray();
					$sizeData=$this->CubicleCurtainSizes->get($price['size_id'])->toArray();
				break;
				case "wt":
					$product=$this->WindowTreatments->get($price['product_id'])->toArray();
					$sizeData=$this->WindowTreatmentSizes->get($price['size_id'])->toArray();
				break;
				case "bs":
					$product=$this->Bedspreads->get($price['product_id'])->toArray();
					$sizeData=$this->BedspreadSizes->Get($price['size_id'])->toArray();
				break;
			}
			$customer=$this->Customers->get($price['customer_id'])->toArray();
			$colorsArr=json_decode($price['included_colors'],true);
			$colors='';
			foreach($colorsArr as $colorEntry){
				$colors .= $colorEntry.', ';
			}
			$colors=substr($colors,0,(strlen($colors)-2));
			$specialPrices[]=array(
				'DT_RowId'=>'row_'.$price['id'],
				'DT_RowClass'=>'rowtest',
				'0' => '<input type="checkbox" name="rule_'.$price['id'].'_checked" value="yes" />',
				'1' => $price['id'],
				'2' => $customer['company_name'],
				'3' => strtoupper($price['product_type']),
				'4' => "<strong>".$product['title']."</strong>",
				'5' => $colors,
				'6' => $sizeData['title'],
				'7' => '$'.number_format($price['price'],2,'.',','),
				'8' => '<a href="/products/editspecialprice/'.$price['id'].'/"><img src="/img/edit.png" /></a> <a href="/products/deletespecialprice/'.$price['id'].'"><img src="/img/delete.png" /></a>'
			);
		}
		$this->set('draw',$this->request->data['draw']);
		$this->set('recordsTotal',$overallTotalRows);
		$this->set('recordsFiltered',$totalFilteredRows);
		$this->set('data',$specialPrices);
		$this->set('_serialize',['draw','recordsTotal','recordsFiltered','data']);
	}
	
	public function specialpricing($productType,$productID){
		$this->autoRender=false;
		$this->set('productType',$productType);
		$this->set('productID',$productID);
		$this->render('specialpricing');
	}
	
	
	
	
	public function bulkdeletespecialprices($ruleIDs){
		$IDs=explode("&",urldecode($ruleIDs));
		$splitIDs=array();
		foreach($IDs as $num => $combined){
			$splitIDs[]=str_replace("=yes","",str_replace("rule_","",str_replace("_checked","",$combined)));
		}
		
		
		if($this->request->data){
			$priceExceptionTable=TableRegistry::get('CustomerPricingExceptions');
			
			$deletedIDs='';
			$deleted=0;
			foreach($splitIDs as $ruleID){
				$thisException=$this->CustomerPricingExceptions->get($ruleID);
				if($priceExceptionTable->delete($thisException)){
					$deleted++;
					$deletedIDs .= $ruleID.', ';
				}
			}
			$deletedIDs=substr($deletedIDs,0,(strlen($deletedIDs)-2));
			
			$this->Flash->success('Successfully deleted '.$deleted.' selected special pricing rules.');
			$this->logActivity($_SERVER['REQUEST_URI'],'Deleted '.$deleted.' special pricing rule IDs '.$deletedIDs);
			
			return $this->redirect('/products/specialpricing/');
			
		}else{
			
			//confirm deletion
			$theseExceptions=$this->CustomerPricingExceptions->find('all',['conditions' => ['id IN' => $splitIDs]])->toArray();
			$this->set('theseExceptions',$theseExceptions);
			
			$allCustomers=$this->Customers->find('all')->toArray();
			$this->set('allCustomers',$allCustomers);
			
			$thisProduct=array();
			
			foreach($theseExceptions as $thisException){
				switch($thisException['product_type']){
					case "cc":
						$thisProduct[$thisException['id']]=$this->CubicleCurtains->get($thisException['product_id'])->toArray();
						$allSizes=$this->CubicleCurtainSizes->find('all')->toArray();
					break;
					case "bs":
						$thisProduct[$thisException['id']]=$this->Bedspreads->get($thisException['product_id'])->toArray();
						$allSizes=$this->BedspreadSizes->find('all')->toArray();
					break;
					case "wt":
						$thisProduct[$thisException['id']]=$this->WindowTreatments->get($thisException['product_id'])->toArray();
						$allSizes=$this->WindowTreatmentSizes->find('all')->toArray();
					break;
				}
			}
			
			$this->set('thisProduct',$thisProduct);
			$this->set('allSizes',$allSizes);
			
		}
		
	}
	
	
	
	public function deletespecialprice($priceID){
		
		if($this->request->data){
			
			$priceExceptionTable=TableRegistry::get('CustomerPricingExceptions');
			$thisException=$this->CustomerPricingExceptions->get($priceID);
			if($priceExceptionTable->delete($thisException)){
				$this->Flash->success('Successfully deleted selected special pricing rule.');
				$this->logActivity($_SERVER['REQUEST_URI'],'Deleted special pricing rule #'.$priceID);
				return $this->redirect('/products/specialpricing/');
			}
			
		}else{
			//confirm deletion
			$thisException=$this->CustomerPricingExceptions->get($priceID)->toArray();
			$this->set('thisException',$thisException);
			
			$thisCustomer=$this->Customers->get($thisException['customer_id'])->toArray();
			$this->set('thisCustomer',$thisCustomer);
			
			switch($thisException['product_type']){
				case "cc":
					$thisProduct=$this->CubicleCurtains->get($thisException['product_id'])->toArray();
					$allSizes=$this->CubicleCurtainSizes->find('all')->toArray();
				break;
				case "bs":
					$thisProduct=$this->Bedspreads->get($thisException['product_id'])->toArray();
					$allSizes=$this->BedspreadSizes->find('all')->toArray();
				break;
				case "wt":
					$thisProduct=$this->WindowTreatments->get($thisException['product_id'])->toArray();
					$allSizes=$this->WindowTreatmentSizes->find('all')->toArray();
				break;
			}
			
			$this->set('thisProduct',$thisProduct);
			$this->set('allSizes',$allSizes);
		}
		
	}
	
	
	public function editspecialprice($priceID){
		if($this->request->data){
			$priceExceptionTable=TableRegistry::get('CustomerPricingExceptions');
			$thisException=$priceExceptionTable->get($priceID);
			$thisException->price=$this->request->data['newprice'];
			if($priceExceptionTable->save($thisException)){
				$this->Flash->success('Successfully saved changes to selected special pricing rule');
				return $this->redirect('/products/specialpricing/');
			}
		}else{
			$thisException=$this->CustomerPricingExceptions->get($priceID)->toArray();
			$this->set('thisException',$thisException);
			
			$thisCustomer=$this->Customers->get($thisException['customer_id'])->toArray();
			$this->set('thisCustomer',$thisCustomer);
			
			switch($thisException['product_type']){
				case "cc":
					$thisProduct=$this->CubicleCurtains->get($thisException['product_id'])->toArray();
					$allSizes=$this->CubicleCurtainSizes->find('all')->toArray();
				break;
				case "bs":
					$thisProduct=$this->Bedspreads->get($thisException['product_id'])->toArray();
					$allSizes=$this->BedspreadSizes->find('all')->toArray();
				break;
				case "wt":
					$thisProduct=$this->WindowTreatments->get($thisException['product_id'])->toArray();
					$allSizes=$this->WindowTreatmentSizes->find('all')->toArray();
				break;
			}
			
			$this->set('thisProduct',$thisProduct);
			$this->set('allSizes',$allSizes);
			
		}
	}
	
	public function newspecialpricing($customerID=false,$productType=false,$productID=false){
		$this->autoRender=false;
		//print_r($this->request->data);exit;
		
		switch($this->request->data['process']){
			case "step1":
			default:
				
				if(!$customerID){
					$customers=$this->Customers->find('list',['keyField' => 'id', 'valueField' => 'company_name', 'conditions'=>['status'=>'Active'],'order'=>['company_name'=>'asc']])->toArray();
					$this->set('customers',$customers);
				}else{
					$this->set('customerID',$customerID);
					$this->set('productType',$productType);
				}
				
				$this->render('newspecialpricing_step1');
				
			break;
			case "step2":
				
				$this->set('productType',$this->request->data['product_type']);
				
				switch($this->request->data['product_type']){
					case "cc":
						$fabricsfind=$this->Fabrics->find('all',['conditions'=>['OR'=>['use_in_cc'=>'1','use_in_sc' => '1']],'order'=>['fabric_name'=>'ASC']])->toArray();
					break;
					case "bs":
						$fabricsfind=$this->Fabrics->find('all',['conditions'=>['use_in_bs'=>'1'],'order'=>['fabric_name'=>'ASC']])->toArray();
					break;
					case "wt":
						$fabricsfind=$this->Fabrics->find('all',['conditions'=>['use_in_window'=>'1'],'order'=>['fabric_name'=>'ASC']])->toArray();
					break; 
				}

				$thefabrics=array();
				$usedfabrics=array();
				foreach($fabricsfind as $fabric){
					if(!in_array($fabric['fabric_name'],$usedfabrics)){
						$thefabrics[$fabric['fabric_name']]=array();
						$usedfabrics[]=$fabric['fabric_name'];
					}
					$thefabrics[$fabric['fabric_name']][$fabric['id']]=array('color'=>$fabric['color'],'id'=>$fabric['id'],'image'=>$fabric['image_file']);
				}
				$this->set('thefabrics',$thefabrics);
				
				$this->set('postdata',$this->request->data);
				$this->render('newspecialpricing_step2');
				
			break;
			case "step3":
				$this->set('postdata',$this->request->data);
				
				$thisCustomer=$this->Customers->get($this->request->data['customer_id'])->toArray();
				$this->set('thisCustomer',$thisCustomer);
				
				
				//look up this product with selected options
				switch($this->request->data['product_type']){
					case "cc":
						
						if($this->request->data['mesh'] == 'yes'){
							$hasmesh=1;
						}else{
							$hasmesh=0;
						}
						
						//use_Allegro_color_Cabernet
						$colorsIncluded=array();
						foreach($this->request->data as $key => $val){
							if(substr($key,0,4) == "use_" && $val=='yes'){
								$colorexp=explode("_color_",$key);
								$thisColor=$colorexp[1];
								
								$colorsIncluded[]=$thisColor;
							}
						}
						
						
						$findCC="SELECT * FROM `cubicle_curtains` WHERE (`fabric_name` = '".$this->request->data['fabric_name']."' AND `has_mesh`='".$hasmesh."') AND (";
						foreach($colorsIncluded as $num => $colorInc){
							$findCC .= "`available_colors` LIKE '%".$colorInc."%' OR ";
						}
						$findCC=substr($findCC,0,(strlen($findCC)-4));
						$findCC .= ")";
						
						$conn = ConnectionManager::get('default');
						$priceListCCProducts=array();
						
						$runquery=$conn->execute($findCC);
						$priceListResults=$runquery->fetchAll('assoc');
						
						foreach($priceListResults as $priceListCC){
							$priceListCCProducts[$priceListCC['id']]=array();
							//$availableSizes=$this->CcDataMap->find('all',['conditions'=>['cc_id'=>$priceListCC['id']],'order'=>['price'=>'asc']])->hydrate(false)->toArray();
							$getSizesWithPrices=$conn->execute("SELECT a.*,b.width,b.length FROM cc_data_map a, cubicle_curtain_sizes b WHERE b.id=a.size_id AND a.cc_id=".$priceListCC['id']." ORDER BY b.width ASC, b.length ASC");
							$availableSizes=$getSizesWithPrices->fetchAll('assoc');

							$priceListCCProducts[$priceListCC['id']]=$priceListCC;
							$priceListCCProducts[$priceListCC['id']]['sizes']=$availableSizes;
						}
				
						$allCCSizes=$this->CubicleCurtainSizes->find('all')->toArray();
						$this->set('allCCSizes',$allCCSizes);
				
						$this->set('priceListResults',$priceListCCProducts);
						
						$this->render('newspecialpricing_step3_cc');
						
					break;
					case "bs":
						
						if($this->request->data['quilted'] == 'yes'){
							$quilted=1;
						}else{
							$quilted=0;
						}
						
						//use_Allegro_color_Cabernet
						$colorsIncluded=array();
						foreach($this->request->data as $key => $val){
							if(substr($key,0,4) == "use_" && $val=='yes'){
								$colorexp=explode("_color_",$key);
								$thisColor=$colorexp[1];
								
								$colorsIncluded[]=$thisColor;
							}
						}
						
						
						$findBS="SELECT * FROM `bedspreads` WHERE (`fabric_name` = '".$this->request->data['fabric_name']."' AND `quilted`='".$quilted."') AND (";
						foreach($colorsIncluded as $num => $colorInc){
							$findBS .= "`available_colors` LIKE '%".$colorInc."%' OR ";
						}
						$findBS=substr($findBS,0,(strlen($findBS)-4));
						$findBS .= ")";
						
						$conn = ConnectionManager::get('default');
						$priceListBSProducts=array();
						
						$runquery=$conn->execute($findBS);
						$priceListResults=$runquery->fetchAll('assoc');
						
						foreach($priceListResults as $priceListBS){
							$priceListBSProducts[$priceListBS['id']]=array();
							//$availableSizes=$this->BsDataMap->find('all',['conditions'=>['bs_id'=>$priceListBS['id']],'order'=>['price'=>'asc']])->hydrate(false)->toArray();

							$getSizesWithPrices=$conn->execute("SELECT a.*,b.width,b.length FROM bs_data_map a, bedspread_sizes b WHERE b.id=a.size_id AND a.bs_id=".$priceListBS['id']." ORDER BY b.width ASC, b.length ASC");
							$availableSizes=$getSizesWithPrices->fetchAll('assoc');

							$priceListBSProducts[$priceListBS['id']]=$priceListBS;
							$priceListBSProducts[$priceListBS['id']]['sizes']=$availableSizes;
						}
				
						$allBSSizes=$this->BedspreadSizes->find('all')->toArray();
						$this->set('allBSSizes',$allBSSizes);
				
						$this->set('priceListResults',$priceListBSProducts);
						
						$this->render('newspecialpricing_step3_bs');
						
					break;
					case "wt":
						
						if($this->request->data['welts'] == 'yes'){
							$haswelts=1;
						}else{
							$haswelts=0;
						}
						
						//use_Allegro_color_Cabernet
						$colorsIncluded=array();
						foreach($this->request->data as $key => $val){
							if(substr($key,0,4) == "use_" && $val=='yes'){
								$colorexp=explode("_color_",$key);
								$thisColor=$colorexp[1];
								
								$colorsIncluded[]=$thisColor;
							}
						}
						
						
						$findWT="SELECT * FROM `window_treatments` WHERE (`wt_type`='".$this->request->data['wt_type']."' AND `lining_option`='".$this->request->data['lining']."' AND `fabric_name` = '".$this->request->data['fabric_name']."' AND `has_welt`='".$haswelts."') AND (";
						foreach($colorsIncluded as $num => $colorInc){
							$findWT .= "`available_colors` LIKE '%\"".$colorInc."\"%' OR ";
						}
						$findWT=substr($findWT,0,(strlen($findWT)-4));
						$findWT .= ")";
						
						$this->set('findwtquery',$findWT);
						
						$conn = ConnectionManager::get('default');
						$priceListWTProducts=array();
						
						$runquery=$conn->execute($findWT);
						$priceListResults=$runquery->fetchAll('assoc');
						
						foreach($priceListResults as $priceListWT){
							$priceListCCProducts[$priceListWT['id']]=array();
							//$availableSizes=$this->WtDataMap->find('all',['conditions'=>['wt_id'=>$priceListWT['id']],'order'=>['price'=>'asc']])->hydrate(false)->toArray();
							$getSizesWithPrices=$conn->execute("SELECT a.*,b.width,b.length FROM wt_data_map a, window_treatment_sizes b WHERE b.id=a.size_id AND a.wt_id=".$priceListWT['id']." ORDER BY b.width ASC, b.length ASC");
							$availableSizes=$getSizesWithPrices->fetchAll('assoc');

							$priceListWTProducts[$priceListWT['id']]=$priceListWT;
							$priceListWTProducts[$priceListWT['id']]['sizes']=$availableSizes;
						}
				
						$allWTSizes=$this->WindowTreatmentSizes->find('all')->toArray();
						$this->set('allWTSizes',$allWTSizes);
				
						$this->set('priceListResults',$priceListWTProducts);
						
						
						$this->render('newspecialpricing_step3_wt');
						
					break;
				}
				
				
				
				
			break;
			case "step4":
				
				switch($this->request->data['product_type']){
					case "cc":
						$allCCSizes=$this->CubicleCurtainSizes->find('all')->toArray();
						
						if($this->request->data['mesh'] == 'yes'){
							$hasmesh=1;
						}else{
							$hasmesh=0;
						}
						
						//use_Allegro_color_Cabernet
						$colorsIncluded=array();
						foreach($this->request->data as $key => $val){
							if(substr($key,0,4) == "use_" && $val=='yes'){
								$thiscolorexp=explode("_color_",$key);
								$thisColorItem=$thiscolorexp[1];
								
								$colorsIncluded[]=$thisColorItem;
							}
						}
						
						
						$findCC="SELECT * FROM `cubicle_curtains` WHERE (`fabric_name` = '".$this->request->data['fabric_name']."' AND `has_mesh`='".$hasmesh."') AND (";
						foreach($colorsIncluded as $num => $colorInc){
							$findCC .= "`available_colors` LIKE '%".$colorInc."%' OR ";
						}
						$findCC=substr($findCC,0,(strlen($findCC)-4));
						$findCC .= ")";
						
						$conn = ConnectionManager::get('default');
						$priceListCCProducts=array();
						
						$runquery=$conn->execute($findCC);
						$priceListResults=$runquery->fetchAll('assoc');
						
						
						foreach($priceListResults as $priceListCC){
							$priceListCCProducts[$priceListCC['id']]=array();
							$availableSizes=$this->CcDataMap->find('all',['conditions'=>['cc_id'=>$priceListCC['id']],'order'=>['price'=>'asc']])->hydrate(false)->toArray();
							$priceListCCProducts[$priceListCC['id']]=$priceListCC;
							$priceListCCProducts[$priceListCC['id']]['sizes']=$availableSizes;
						}
						
						$priceExceptionTable=TableRegistry::get('CustomerPricingExceptions');
						
						$checkColorsUsed=array();
						foreach($priceListCCProducts as $productid => $CCproduct){
							$checkColorsUsed[$productid]=array();
							$thisCCcolors=json_decode($CCproduct['available_colors'],true);
							foreach($thisCCcolors as $color){
								if($this->request->data["use_".str_replace(" ","_",$this->request->data['fabric_name'])."_color_".str_replace(" ","_",$color)] == "yes"){
									$checkColorsUsed[$productid][]=$color;
								}
							}
						}
												
						/*
						echo "<pre>";
						print_r($priceListResults);
						echo "</pre>";
						exit;
						*/
						$insertcount=0;
						$updatecount=0;
						
						foreach($priceListCCProducts as $productid => $CCproduct){
							$thisCCcolors=json_decode($CCproduct['available_colors'],true);
							foreach($thisCCcolors as $color){
								if($this->request->data["use_".str_replace(" ","_",$this->request->data['fabric_name'])."_color_".str_replace(" ","_",$color)] == "yes"){
									//we are looking for changes to this exact color/cc combination
									foreach($CCproduct['sizes'] as $num => $size){
										foreach($allCCSizes as $sizeRow){
											if($sizeRow['id'] == $size['size_id']){
												if(isset($this->request->data["cc_".$productid."_size_".$sizeRow['id']."_price"]) && strlen($this->request->data["cc_".$productid."_size_".$sizeRow['id']."_price"]) >0){
												
													//make sure this exact product+customer+size combination doesnt already exist
													$exceptionCheck=$this->CustomerPricingExceptions->find('all',['conditions'=>['customer_id'=>$this->request->data['customer_id'],'product_type'=>'cc','product_id'=>$productid,'size_id'=>$sizeRow['id'],'included_colors'=>json_encode($checkColorsUsed[$productid])]])->toArray();
													if(count($exceptionCheck) >0){
														//we need to replace an existing value
														foreach($exceptionCheck as $exceptionRow){
															$thisException=$priceExceptionTable->get($exceptionRow['id']);
															$thisException->price=$this->request->data["cc_".$productid."_size_".$sizeRow['id']."_price"];
															if($priceExceptionTable->save($thisException)){
																$updatecount++;
															}
														}

													}else{
														//we need to insert this new pricing row
														$newException=$priceExceptionTable->newEntity();
														$newException->product_type='cc';
														$newException->product_id=$productid;
														$newException->customer_id=$this->request->data['customer_id'];
														$newException->size_id=$sizeRow['id'];
														$newException->price=$this->request->data["cc_".$productid."_size_".$sizeRow['id']."_price"];
														$newException->included_colors=json_encode($checkColorsUsed[$productid]);

														if($priceExceptionTable->save($newException)){
															$insertcount++;
														}

													}
												}
											}
										}
									}
								}
							}
	
						}
						
						
						if($insertcount >0 || $updatecount >0){
							$langout='';
							if($insertcount >0){
								$langout .= 'added '.$insertcount;
							}
							
							if($insertcount >0  && $updatecount > 0){
								$langout .= ' and ';
							}
							
							if($updatecount >0){
								$langout .= 'updated '.$updatecount;
							}
							
							$this->logActivity($_SERVER['REQUEST_URI'],ucfirst($langout).' Cubicle Curtain special pricing rules');
							$this->Flash->success('Successfully '.$langout.' special pricing rules');
							
						}
					break;
					case "wt":
						$allWTSizes=$this->WindowTreatmentSizes->find('all')->toArray();
						
						if($this->request->data['welts'] == 'yes'){
							$haswelts=1;
						}else{
							$haswelts=0;
						}
						
						//use_Allegro_color_Cabernet
						$colorsIncluded=array();
						foreach($this->request->data as $key => $val){
							if(substr($key,0,4) == "use_" && $val=='yes'){
								$thiscolorexp=explode("_color_",$key);
								$thisColorItem=$thiscolorexp[1];
								
								$colorsIncluded[]=$thisColorItem;
							}
						}
						
						
						$findWT="SELECT * FROM `window_treatments` WHERE (`wt_type`='".$this->request->data['wt_type']."' AND `lining_option`='".$this->request->data['lining']."' AND `fabric_name` = '".$this->request->data['fabric_name']."' AND `has_welt`='".$haswelts."') AND (";
						foreach($colorsIncluded as $num => $colorInc){
							$findWT .= "`available_colors` LIKE '%\"".$colorInc."\"%' OR ";
						}
						$findWT=substr($findWT,0,(strlen($findWT)-4));
						$findWT .= ")";
						
						$conn = ConnectionManager::get('default');
						$priceListWTProducts=array();
						
						$runquery=$conn->execute($findWT);
						$priceListResults=$runquery->fetchAll('assoc');
						
						
						foreach($priceListResults as $priceListWT){
							$priceListWTProducts[$priceListWT['id']]=array();
							$availableSizes=$this->WtDataMap->find('all',['conditions'=>['wt_id'=>$priceListWT['id']],'order'=>['price'=>'asc']])->hydrate(false)->toArray();
							$priceListWTProducts[$priceListWT['id']]=$priceListWT;
							$priceListWTProducts[$priceListWT['id']]['sizes']=$availableSizes;
						}
						
						$priceExceptionTable=TableRegistry::get('CustomerPricingExceptions');
						
						$checkColorsUsed=array();
						foreach($priceListWTProducts as $productid => $WTproduct){
							$checkColorsUsed[$productid]=array();
							$thisWTcolors=json_decode($WTproduct['available_colors'],true);
							foreach($thisWTcolors as $color){
								if($this->request->data["use_".str_replace(" ","_",$this->request->data['fabric_name'])."_color_".str_replace(" ","_",$color)] == "yes"){
									$checkColorsUsed[$productid][]=$color;
								}
							}
						}
												
						/*
						echo "<pre>";
						print_r($priceListResults);
						echo "</pre>";
						exit;
						*/
						$insertcount=0;
						$updatecount=0;
						
						foreach($priceListWTProducts as $productid => $WTproduct){
							$thisWTcolors=json_decode($WTproduct['available_colors'],true);
							foreach($thisWTcolors as $color){
								if($this->request->data["use_".str_replace(" ","_",$this->request->data['fabric_name'])."_color_".str_replace(" ","_",$color)] == "yes"){
									//we are looking for changes to this exact color/cc combination
									foreach($WTproduct['sizes'] as $num => $size){
										foreach($allWTSizes as $sizeRow){
											if($sizeRow['id'] == $size['size_id']){
												if(isset($this->request->data["wt_".$productid."_size_".$sizeRow['id']."_price"]) && strlen($this->request->data["wt_".$productid."_size_".$sizeRow['id']."_price"]) >0){
												
													//make sure this exact product+customer+size combination doesnt already exist
													$exceptionCheck=$this->CustomerPricingExceptions->find('all',['conditions'=>['customer_id'=>$this->request->data['customer_id'],'product_type'=>'wt','product_id'=>$productid,'size_id'=>$sizeRow['id'],'included_colors'=>json_encode($checkColorsUsed[$productid])]])->toArray();
													if(count($exceptionCheck) >0){
														//we need to replace an existing value
														foreach($exceptionCheck as $exceptionRow){
															$thisException=$priceExceptionTable->get($exceptionRow['id']);
															$thisException->price=$this->request->data["wt_".$productid."_size_".$sizeRow['id']."_price"];
															if($priceExceptionTable->save($thisException)){
																$updatecount++;
															}
														}

													}else{
														//we need to insert this new pricing row
														$newException=$priceExceptionTable->newEntity();
														$newException->product_type='wt';
														$newException->product_id=$productid;
														$newException->customer_id=$this->request->data['customer_id'];
														$newException->size_id=$sizeRow['id'];
														$newException->price=$this->request->data["wt_".$productid."_size_".$sizeRow['id']."_price"];
														$newException->included_colors=json_encode($checkColorsUsed[$productid]);

														if($priceExceptionTable->save($newException)){
															$insertcount++;
														}

													}
												}
											}
										}
									}
								}
							}
	
						}
						
						
						if($insertcount >0 || $updatecount >0){
							$langout='';
							if($insertcount >0){
								$langout .= 'added '.$insertcount;
							}
							
							if($insertcount >0  && $updatecount > 0){
								$langout .= ' and ';
							}
							
							if($updatecount >0){
								$langout .= 'updated '.$updatecount;
							}
							
							$this->logActivity($_SERVER['REQUEST_URI'],ucfirst($langout).' Window Treatment special pricing rules');
							$this->Flash->success('Successfully '.$langout.' special pricing rules');
							
						}
					break;
						
					case "bs":
						$allBSSizes=$this->BedspreadSizes->find('all')->toArray();
						
						if($this->request->data['quilted'] == 'yes'){
							$quilted=1;
						}else{
							$quilted=0;
						}
						
						//use_Allegro_color_Cabernet
						$colorsIncluded=array();
						foreach($this->request->data as $key => $val){
							if(substr($key,0,4) == "use_" && $val=='yes'){
								$thiscolorexp=explode("_color_",$key);
								$thisColorItem=$thiscolorexp[1];
								
								$colorsIncluded[]=$thisColorItem;
							}
						}
						
						
						$findBS="SELECT * FROM `bedspreads` WHERE (`fabric_name` = '".$this->request->data['fabric_name']."' AND `quilted`='".$quilted."') AND (";
						foreach($colorsIncluded as $num => $colorInc){
							$findBS .= "`available_colors` LIKE '%".$colorInc."%' OR ";
						}
						$findBS=substr($findBS,0,(strlen($findBS)-4));
						$findBS .= ")";
						
						$conn = ConnectionManager::get('default');
						$priceListBSProducts=array();
						
						$runquery=$conn->execute($findBS);
						$priceListResults=$runquery->fetchAll('assoc');
						
						
						foreach($priceListResults as $priceListBS){
							$priceListBSProducts[$priceListBS['id']]=array();
							$availableSizes=$this->BsDataMap->find('all',['conditions'=>['bs_id'=>$priceListBS['id']],'order'=>['price'=>'asc']])->hydrate(false)->toArray();
							$priceListBSProducts[$priceListBS['id']]=$priceListBS;
							$priceListBSProducts[$priceListBS['id']]['sizes']=$availableSizes;
						}
						
						$priceExceptionTable=TableRegistry::get('CustomerPricingExceptions');
						
						$checkColorsUsed=array();
						foreach($priceListBSProducts as $productid => $BSproduct){
							$checkColorsUsed[$productid]=array();
							$thisBScolors=json_decode($BSproduct['available_colors'],true);
							foreach($thisBScolors as $color){
								if($this->request->data["use_".str_replace(" ","_",$this->request->data['fabric_name'])."_color_".str_replace(" ","_",$color)] == "yes"){
									$checkColorsUsed[$productid][]=$color;
								}
							}
						}
												
						/*
						echo "<pre>";
						print_r($priceListResults);
						echo "</pre>";
						exit;
						*/
						
						$insertcount=0;
						$updatecount=0;
						$updateIDs=array();
						
						foreach($priceListBSProducts as $productid => $BSproduct){
							$thisBScolors=json_decode($BSproduct['available_colors'],true);
							foreach($thisBScolors as $color){
								if($this->request->data["use_".str_replace(" ","_",$this->request->data['fabric_name'])."_color_".str_replace(" ","_",$color)] == "yes"){
									//we are looking for changes to this exact color/cc combination
									foreach($BSproduct['sizes'] as $num => $size){
										foreach($allBSSizes as $sizeRow){
											if($sizeRow['id'] == $size['size_id']){
												if(isset($this->request->data["bs_".$productid."_size_".$sizeRow['id']."_price"]) && strlen($this->request->data["bs_".$productid."_size_".$sizeRow['id']."_price"]) >0){
												
													//make sure this exact product+customer+size combination doesnt already exist
													$exceptionCheck=$this->CustomerPricingExceptions->find('all',['conditions'=>['customer_id'=>$this->request->data['customer_id'],'product_type'=>'bs','product_id'=>$productid,'size_id'=>$sizeRow['id'],'included_colors'=>json_encode($checkColorsUsed[$productid])]])->toArray();
													if(count($exceptionCheck) >0){
														//we need to replace an existing value
														foreach($exceptionCheck as $exceptionRow){
															if(!in_array($exceptionRow['id'],$updateIDs)){
																$thisException=$priceExceptionTable->get($exceptionRow['id']);
																$thisException->price=$this->request->data["bs_".$productid."_size_".$sizeRow['id']."_price"];
																if($priceExceptionTable->save($thisException)){
																	$updatecount++;
																	$updateIDs[]=$exceptionRow['id'];
																}
															}
														}

													}else{
														//we need to insert this new pricing row
														$newException=$priceExceptionTable->newEntity();
														$newException->product_type='bs';
														$newException->product_id=$productid;
														$newException->customer_id=$this->request->data['customer_id'];
														$newException->size_id=$sizeRow['id'];
														$newException->price=$this->request->data["bs_".$productid."_size_".$sizeRow['id']."_price"];
														$newException->included_colors=json_encode($checkColorsUsed[$productid]);

														if($priceExceptionTable->save($newException)){
															$insertcount++;
															$updateIDs[]=$newException->id;
														}

													}
												}
											}
										}
									}
								}
							}
	
						}
						
						
						if($insertcount >0 || $updatecount >0){
							$langout='';
							if($insertcount >0){
								$langout .= 'added '.$insertcount;
							}
							
							if($insertcount >0  && $updatecount > 0){
								$langout .= ' and ';
							}
							
							if($updatecount >0){
								$langout .= 'updated '.$updatecount;
							}
							
							$this->logActivity($_SERVER['REQUEST_URI'],ucfirst($langout).' Bedspread special pricing rules');
							$this->Flash->success('Successfully '.$langout.' special pricing rules');
							
						}
			break;
				}
				
				
				
				//look up whether this exact special pricing already exists
				
				//nope, create/save everything
				
				$redir='/products/specialpricing/';
				if($customerID){ $redir .= $customerID.'/'; }
				if($productType){ $redir .= $productType.'/'; }
				if($productID){ $redir .= $productID.'/'; }
				
				return $this->redirect($redir);
				
			break;
			
		}
	}
	
	
	public function addinventory($fabricID=0){
		$getlocations=$this->WarehouseLocations->find('all',['order'=>['location'=>'ASC']])->toArray();
		$warehouselocations=array();
		foreach($getlocations as $location){
			$warehouselocations[$location['id']]=$location['location'];
		}
		$this->set('warehouselocations',$warehouselocations);
		$this->set('settings',$this->getsettingsarray());
		if($fabricID==0){
			$this->set('doaction','selectfabric');
			$fabrics=$this->Fabrics->find('all',['conditions'=>['status'=>'Active']])->group('fabric_name')->toArray();
			$this->set('fabrics',$fabrics);
			$linings=$this->Linings->find('all')->toArray();
			$this->set('linings',$linings);
		}else{
			$thisFabric=$this->Fabrics->get($fabricID)->toArray();
			if($this->request->data){
				//create new roll
				$inventoryTable=TableRegistry::get('MaterialInventory');
				$newRoll=$inventoryTable->newEntity();
				$newRoll->material_type='Fabrics';
				$newRoll->material_id = $fabricID;
				$newRoll->yards_received=$this->request->data['yards'];
				$newRoll->roll_number=$this->getnextrolltagnumber();
				$newRoll->date_received=time();
				$newRoll->date_modified = time();
				$newRoll->user_id=$this->Auth->user('id');
				if($thisFabric['com_fabric'] == 1){
					$newRoll->com_or_mom='COM';
					$newRoll->customer_id = $thisFabric['customer_id'];
				}else{
					$newRoll->com_or_mom='MOM';
					$newRoll->customer_id=0;
				}
				$newRoll->carrier=$this->request->data['carrier'];
				$newRoll->tracking_number=$this->request->data['tracking_number'];
				$newRoll->quilted=$this->request->data['quilted'];
				if($this->request->data['quilted'] == '1'){
					$newRoll->quilting_pattern = $this->request->data['quilting_pattern'];
					$newRoll->backing_material = $this->request->data['backing_material'];
				}
				$newRoll->warehouse_location = $this->request->data['location'];
				$newRoll->notes = $this->request->data['notes'];
				$newRoll->work_order = $this->request->data['work_order'];
				if($inventoryTable->save($newRoll)){
					$newRoll->bitly=$this->getbitlyurl('https://orders.hcinteriors.com/products/inventory/'.$newRoll->id);
					$inventoryTable->save($newRoll);
					$this->set('newrollinfo',$newRoll->toArray());
					$warehouseLocation=$this->WarehouseLocations->get($this->request->data['location'])->toArray();
					$this->logActivity($_SERVER['REQUEST_URI'],"Added new inventory roll ".$newRoll->roll_number." to ".$warehouseLocation['location']);
					$this->set('doaction','reviewnewroll');
				}
			}else{
				$this->set('doaction','newinventory');
				$this->set('fabricData',$thisFabric);
			}
		}
	}
	public function getfabricrolltag($rollID){
		//generate a PDF
		$thisRoll=$this->MaterialInventory->get($rollID)->toArray();
		$thisFabric=$this->Fabrics->get($thisRoll['material_id'])->toArray();
		$this->set('thisuser',$this->Auth->user());
		if($thisRoll['com_or_mom'] == 'COM'){
			$owner=$this->Customers->get($thisRoll['customer_id'])->toArray();
			$this->set('ownerCompany',$owner['company_name']);
		}else{
			$this->set('ownerCompany','HCI');
		}
		$thisWarehouseLocation=$this->WarehouseLocations->get($thisRoll['warehouse_location'])->toArray();
		$this->set('warehouseLocation',$thisWarehouseLocation['location']);
		if($thisRoll['bitly']==''){
			$newURL=$this->getbitlyurl('https://orders.hcinteriors.com/products/inventory/'.$rollID);
			$invtable=TableRegistry::get('MaterialInventory');
			$updateRoll=$invtable->get($rollID);
			$updateRoll->bitly=$newURL;
			$invtable->save($updateRoll);
			$thisRoll['bitly']=$newURL;
		}
		$this->set('rollData',$thisRoll);
		$this->set('fabricData',$thisFabric);
		$GLOBALS['labelpdf']=1;
		$currentyards=$this->getfabricyardsonroll($rollID);
		if($currentyards < 1 || $rollData['status'] == 'Exhausted'){
			$GLOBALS['expiredoverlay']=1;
		}
		$this->set('currentRollYards',$currentyards);
		$this->viewBuilder()->options([
                'pdfConfig' => [
                    'orientation' => 'portrait'
                ]
            ]);
	}
	public function inventory($inventoryID){
		$this->autoRender=false;
		$this->viewBuilder()->layout('iframeinner');
		$thisInventory=$this->MaterialInventory->get($inventoryID)->toArray();
		$this->set('inventoryData',$thisInventory);
		$fabricData=$this->Fabrics->get($thisInventory['material_id'])->toArray();
		$this->set('fabricData',$fabricData);
		$this->set('currentYards',$this->getfabricyardsonroll($inventoryID));
		$warehouseLocation=$this->WarehouseLocations->get($thisInventory['warehouse_location'])->toArray();
		$this->set('warehouseLocation',$warehouseLocation['location']);
		if($thisInventory['com_or_mom'] == 'COM'){
			$customerData=$this->Customers->get($thisInventory['customer_id'])->toArray();
			$this->set('belongsTo',$customerData['company_name']);
		}else{
			$this->set('belongsTo','HCI');
		}
		$this->render('inventory');
	}
	public function fabricinventory($fabricID,$fromtype){
		$thisFabric=$this->Fabrics->get($fabricID)->toArray();
		$fabrics=array();
		$totalQuiltedYards=0;
		$totalUnquiltedYards=0;
		$inventoryRows=$this->MaterialInventory->find('all',['conditions' => ['material_id' => $fabricID]])->toArray();
		foreach($inventoryRows as $inventoryRow){
			$yards=$this->getfabricyardsonroll($inventoryRow['id']);
			if($yards > 0){
				$fabric=$thisFabric;
				$images='';
				if ($handle = opendir($_SERVER['DOCUMENT_ROOT']."/webroot/files/fabrics/".$fabric['id'])) {
					while (false !== ($entry = readdir($handle))) {
						if ($entry != "." && $entry != "..") {
							$images .= "<img src=\"/files/fabrics/".$fabric['id']."/".$entry."\" width=\"75\" height=\"75\" class=\"fabricimg\" />";
						}
					}
					closedir($handle);
				}
				if($inventoryRow['quilted'] == '1'){
					$quilted='<span style="font-size:11px;">PATTERN: <b>'.$inventoryRow['quilting_pattern'].'</b><br>BACKING: <b>'.$inventoryRow['backing_material'].'</b></span>';
				}else{
					$quilted='<img src="/img/delete.png" width="16" height="16" />';
				}
				$warehouseLocation=$this->WarehouseLocations->get($inventoryRow['warehouse_location'])->toArray();
				if($inventoryRow['in_workroom'] == '1'){
					$location='WORK ROOM';
				}else{
					$location=$warehouseLocation['location'];
				}
				if($inventoryRow['quilted'] == '1'){
					$unquiltedyards='';
					$quiltedyards=$yards;
					$totalQuiltedYards=($totalQuiltedYards+$yards);
				}else{
					$unquiltedyards=$yards;
					$quiltedyards='';
					$totalUnquiltedYards=($totalUnquiltedYards+$yards);
				}
				$fabrics[$inventoryRow['id']]=array(
					'roll_number' => $inventoryRow['roll_number'],
					'location' => $location,
					'unquilted_yards' => $unquiltedyards,
					'quilted_yards' => $quiltedyards,
					'date_received' => date('n/j/y',$inventoryRow['date_received']),
					'notes' => $inventoryRow['notes'],
					'work_order' => $inventoryRow['work_order'],
					'quote_id' => $this->getQuoteIDFromWONumber($inventoryRow['work_order']),
					'fabric_id' => $inventoryRow['material_id'],
					'quilting_pattern' => $inventoryRow['quilting_pattern'],
					'backing_material'=>$inventoryRow['backing_material'],
					'com_or_mom'=>$inventoryRow['com_or_mom'],
					'carrier'=>$inventoryRow['carrier'],
					'tracking_number'=>$inventoryRow['tracking_number'],
					'quilted' => $inventoryRow['quilted'],
					'in_workroom'=>$inventoryRow['in_workroom'],
					'status' => $inventoryRow['status'],
					'bitlyURL'=>$inventoryRow['bitly'],
					'customer_id'=>$inventoryRow['customer_id'],
					'user_id' => $inventoryRow['user_id'],
					'yards_received'=>$inventoryRow['yards_received']
				);
			}else{
				//this roll is exhausted... mark it as such
				$rollTable=TableRegistry::get('MaterialInventory');
				$thisRoll=$rollTable->get($inventoryRow['id']);
				$thisRoll->status='Exhausted';
				$rollTable->save($thisRoll);
			}
		}
		//tally COMMITTEDs
		$committedTallyUnquilted=array();
		$committedTallyQuilted=array();
		$materialOrders=$this->Orders->find('all',['conditions'=>['status IN' => ['Needs Line Items','Pre-Production']]])->toArray();
		foreach($materialOrders as $materialOrder){
			//get all line item data
			$thisQuote=$this->Quotes->get($materialOrder['quote_id'])->toArray();
			if(!in_array($materialOrder['quote_id'],$usedQuotes)){
				$usedQuotes[]=$materialOrder['quote_id'];
			}	
			$lines=$this->QuoteLineItems->find('all',['conditions' => ['quote_id' => $materialOrder['quote_id']]])->toArray();
			foreach($lines as $lineitem){
				//gather all line item metadata
				$lineItemMetas=$this->QuoteLineItemMeta->find('all',['conditions' => ['quote_item_id' => $lineitem['id']]])->toArray();
				$lineItemMetaArray=array();
				foreach($lineItemMetas as $lineItemMetaRow){
					$lineItemMetaArray[$lineItemMetaRow['meta_key']]=$lineItemMetaRow['meta_value'];
				}
				if($lineItemMetaArray['fabricid'] == $thisFabric['id']){
					if(isset($lineItemMetaArray['quilted']) && $lineItemMetaArray['quilted'] == '1'){
						$committedTallyQuilted['yardages'][]=array(
							'order_number' => $materialOrder['order_number'],
							'order_id' => $materialOrder['id'],
							'quote_id' => $materialOrder['quote_id'],
							'po_number' => $materialOrder['po_number'],
							'quote_number' => $thisQuote['quote_number'],
							'line_number' => $lineitem['line_number'],
							'yards' => (floatval($lineItemMetaArray['yds-per-unit']) * floatval($lineitem['qty']) ),
							'available' => $this->fabricyardsonhand($lineItemMetaArray['fabricid'],'com')
						);
					}else{
						$committedTallyUnquilted['yardages'][]=array(
							'order_number' => $materialOrder['order_number'],
							'order_id' => $materialOrder['id'],
							'quote_id' => $materialOrder['quote_id'],
							'po_number' => $materialOrder['po_number'],
							'quote_number' => $thisQuote['quote_number'],
							'line_number' => $lineitem['line_number'],
							'yards' => (floatval($lineItemMetaArray['yds-per-unit']) * floatval($lineitem['qty']) ),
							'available' => $this->fabricyardsonhand($lineItemMetaArray['fabricid'],'com')
						);
					}
				}
			}
		}
		$this->set('committedTallyQuilted',$committedTallyQuilted);
		$this->set('committedTallyUnquilted',$committedTallyUnquilted);
		//end COMMITTED tally loops
		$mergedcommittedTallyUnquilted=array();
		foreach($committedTallyUnquilted['yardages'] as $linedata){
			if(!isset($mergedcommittedTallyUnquilted[$linedata['order_number']])){
				$mergedcommittedTallyUnquilted[$linedata['order_number']]=floatval($linedata['yards']);
			}else{
				$mergedcommittedTallyUnquilted[$linedata['order_number']] = number_format(($mergedcommittedTallyUnquilted[$linedata['order_number']] + floatval($linedata['yards'])),2,'.','');
			}
		}
		$mergedcommittedTallyQuilted=array();
		foreach($committedTallyQuilted['yardages'] as $linedata){
			if(!isset($mergedcommittedTallyQuilted[$linedata['order_number']])){
				$mergedcommittedTallyQuilted[$linedata['order_number']]=floatval($linedata['yards']);
			}else{
				$mergedcommittedTallyQuilted[$linedata['order_number']] = number_format(($mergedcommittedTallyQuilted[$linedata['order_number']] + floatval($linedata['yards'])),2,'.','');
			}
		}
		$this->set('fromtype',$fromtype);
		$this->set('thisFabric',$thisFabric);
		$this->set('fabricRolls',$fabrics);
		$this->set('unquiltedYardsOnHand',$totalUnquiltedYards);
		$this->set('quiltedYardsOnHand',$totalQuiltedYards);
		$this->set('orderYardsUnquilted',$mergedcommittedTallyUnquilted);
		$this->set('orderYardsQuilted',$mergedcommittedTallyQuilted);
	}
	public function lookuprollresults(){
		$this->autoRender=false;
		$this->viewBuilder()->layout('ajax');
		$allWarehouseLocations=$this->WarehouseLocations->find('all')->toArray();
		$this->set('allWarehouseLocations',$allWarehouseLocations);
			$conditions=array();
			$defaultQuery=1;
			//figure out which type of search we are doing
			switch($this->request->data['activesection']){
				case "rollnumber":
					$conditions += array('roll_number LIKE' => '%'.$this->request->data['rollnumber'].'%');
				break;
				case "wonumber":
					$conditions += array('work_order LIKE' => '%'.$this->request->data['wonumber'].'%');
				break;
				case "yardages":
					//print_r($this->request->data);exit;
					$defaultQuery=0;
					//build different query with conditions
					$yardconfigs=array('status' => 'Active');
					$rollLiveYards=array();
					if($this->request->data['yardageconfig'] == 'any'){
						$matches=array();
						if($this->request->data['momrosterfabrics'] == 'yes' && $this->request->data['nonrostermom'] != 'yes' && $this->request->data['comfabrics'] != 'yes'){
							//we are only looking for MOM roster fabrics
							$allFabrics=$this->Fabrics->find('all',['conditions' => ['is_hci_fabric' => 1, 'com_fabric' => 0]])->toArray();
						}elseif($this->request->data['momrosterfabrics'] == 'yes' && $this->request->data['nonrostermom'] == 'yes' && $this->request->data['comfabrics'] != 'yes'){
							//we are looking for MOM roster and non-roster fabrics
							$allFabrics=$this->Fabrics->find('all',['conditions' => ['com_fabric' => 0]])->toArray();
						}elseif($this->request->data['momrosterfabrics'] != 'yes' && $this->request->data['nonrostermom'] == 'yes' && $this->request->data['comfabrics'] != 'yes'){
							//we are looking for only non-roster MOM fabrics
							$allFabrics=$this->Fabrics->find('all',['conditions' => ['is_hci_fabric'=>0,'com_fabric' => 0]])->toArray();
						}elseif($this->request->data['momrosterfabrics'] != 'yes' && $this->request->data['nonrostermom'] != 'yes' && $this->request->data['comfabrics'] == 'yes'){
							//we are looking for COM fabrics only
							$allFabrics=$this->Fabrics->find('all',['conditions' => ['com_fabric' => 1]])->toArray();
						}elseif($this->request->data['momrosterfabrics'] == 'yes' && $this->request->data['nonrostermom'] != 'yes' && $this->request->data['comfabrics'] == 'yes'){
							//we are looking for COM and HCI Roster fabrics
							$allFabrics=$this->Fabrics->find('all',['conditions' => ['is_hci_fabric'=>1,'com_fabric' => 1]])->toArray();
						}elseif($this->request->data['momrosterfabrics'] != 'yes' && $this->request->data['nonrostermom'] == 'yes' && $this->request->data['comfabrics'] == 'yes'){
							//we are looking for COM and Non-roster Fabrics
							$allFabrics=$this->Fabrics->find('all',['conditions' => ['is_hci_fabric'=>0,'com_fabric' => 1]])->toArray();
						}
						foreach($allFabrics as $fabric){
							$totalyardsthisfabric=0;
							//find all rolls with this fabric id
							if($this->request->data['totaloption'] == 'total'){
							}elseif($this->request->data['totaloption'] == 'onhand'){
							}elseif($this->request->data['totaloption'] == 'available'){
								$allrolls=$this->MaterialInventory->find('all',['conditions' => ['material_type'=>'Fabrics','material_id'=>$fabric['id'],'status'=>'Active']])->toArray();
								foreach($allrolls as $roll){
									$thisrollyards=$this->getfabricyardsonroll($roll['id']);
									$rollLiveYards[$roll['id']]=$thisrollyards;
									$totalyardsthisfabric = ($totalyardsthisfabric + $thisrollyards);
								}
								$committedYardsThisFabricQuilted=$this->getfabriccommittedyards($fabric['id'],1);
								$committedYardsThisFabricUnquilted=$this->getfabriccommittedyards($fabric['id'],0);
								$availableYardsThisFabric = ($totalyardsthisfabric - ($committedYardsThisFabricQuilted + $committedYardsThisFabricUnquilted));
								if($availableYardsThisFabric >= floatval($this->request->data['totalyards'])){
									//meets this request
									$matches[]=array('fabric_id'=>$fabric['id'],'fabric_name'=>$fabric['fabric_name'],'color'=>$fabric['color'],'total_yards'=>$totalyardsthisfabric,'committed_yards_quilted'=>$committedYardsThisFabricQuilted, 'committed_yards_unquilted' => $committedYardsThisFabricUnquilted, 'num_rolls'=>count($allrolls),'allrolls'=>$allrolls);
								}
							}
							/*
							$allrolls=$this->MaterialInventory->find('all',['conditions' => ['material_type'=>'Fabrics','material_id'=>$fabric['id'],'status'=>'Active']])->toArray();
							foreach($allrolls as $roll){
								$thisrollyards=$this->getfabricyardsonroll($roll['id']);
								$rollLiveYards[$roll['id']]=$thisrollyards;
								$totalyardsthisfabric = ($totalyardsthisfabric + $thisrollyards);
							}
							$committedYardsThisFabricQuilted=$this->getfabriccommittedyards($fabric['id'],1);
							$committedYardsThisFabricUnquilted=$this->getfabriccommittedyards($fabric['id'],0);
							if($totalyardsthisfabric >= floatval($this->request->data['totalyards'])){
								//meets this request
								$matches[]=array('fabric_id'=>$fabric['id'],'fabric_name'=>$fabric['fabric_name'],'color'=>$fabric['color'],'total_yards'=>$totalyardsthisfabric,'committed_yards_quilted'=>$committedYardsThisFabricQuilted, 'committed_yards_unquilted' => $committedYardsThisFabricUnquilted, 'num_rolls'=>count($allrolls),'allrolls'=>$allrolls);
							}
							*/
						}
						$this->set('yardageconfig','any');
						$this->set('rollLiveYards',$rollLiveYards);
						$this->set('yardagematches',$matches);
					}elseif($this->request->data['yardageconfig'] == 'specific'){
						$matches=array();
						if($this->request->data['momrosterfabrics'] == 'yes' && $this->request->data['nonrostermom'] != 'yes' && $this->request->data['comfabrics'] != 'yes'){
							//we are only looking for MOM roster fabrics
							$allFabrics=$this->Fabrics->find('all',['conditions' => ['is_hci_fabric' => 1, 'com_fabric' => 0]])->toArray();
						}elseif($this->request->data['momrosterfabrics'] == 'yes' && $this->request->data['nonrostermom'] == 'yes' && $this->request->data['comfabrics'] != 'yes'){
							//we are looking for MOM roster and non-roster fabrics
							$allFabrics=$this->Fabrics->find('all',['conditions' => ['com_fabric' => 0]])->toArray();
						}elseif($this->request->data['momrosterfabrics'] != 'yes' && $this->request->data['nonrostermom'] == 'yes' && $this->request->data['comfabrics'] != 'yes'){
							//we are looking for only non-roster MOM fabrics
							$allFabrics=$this->Fabrics->find('all',['conditions' => ['is_hci_fabric'=>0,'com_fabric' => 0]])->toArray();
						}elseif($this->request->data['momrosterfabrics'] != 'yes' && $this->request->data['nonrostermom'] != 'yes' && $this->request->data['comfabrics'] == 'yes'){
							//we are looking for COM fabrics only
							$allFabrics=$this->Fabrics->find('all',['conditions' => ['com_fabric' => 1]])->toArray();
						}elseif($this->request->data['momrosterfabrics'] == 'yes' && $this->request->data['nonrostermom'] != 'yes' && $this->request->data['comfabrics'] == 'yes'){
							//we are looking for COM and HCI Roster fabrics
							$allFabrics=$this->Fabrics->find('all',['conditions' => ['is_hci_fabric'=>1,'com_fabric' => 1]])->toArray();
						}elseif($this->request->data['momrosterfabrics'] != 'yes' && $this->request->data['nonrostermom'] == 'yes' && $this->request->data['comfabrics'] == 'yes'){
							//we are looking for COM and Non-roster Fabrics
							$allFabrics=$this->Fabrics->find('all',['conditions' => ['is_hci_fabric'=>0,'com_fabric' => 1]])->toArray();
						}
						$matchingRolls=array();
						foreach($allFabrics as $fabric){
							$numrollsthisfabric=0;
							$numrollsmatchingrequirements=0;
							$totalyardsthisfabric=0;
							//find all rolls with this fabric id
							$allrolls=$this->MaterialInventory->find('all',['conditions' => ['material_type'=>'Fabrics','material_id'=>$fabric['id'],'status'=>'Active']])->toArray();
							foreach($allrolls as $roll){
								$thisrollyards=$this->getfabricyardsonroll($roll['id']);
								$rollLiveYards[$roll['id']]=$thisrollyards;
								$totalyardsthisfabric = ($totalyardsthisfabric + $thisrollyards);
								$numrollsthisfabric++;
								if($thisrollyards >= floatval($this->request->data['config-yardsperroll'])){
									$numrollsmatchingrequirements++;
									$matchingRolls[]=$roll['id'];
								}
							}
							$committedYardsThisFabricQuilted=$this->getfabriccommittedyards($fabric['id'],1);
							$committedYardsThisFabricUnquilted=$this->getfabriccommittedyards($fabric['id'],0);
							if($totalyardsthisfabric >= floatval($this->request->data['totalyards']) && $numrollsmatchingrequirements >= floatval($this->request->data['config-numrolls'])){
								//meets this request
								$matches[]=array('fabric_id'=>$fabric['id'],'fabric_name'=>$fabric['fabric_name'],'color'=>$fabric['color'],'total_yards'=>$totalyardsthisfabric,'allrolls'=>$allrolls,'committed_yards_quilted'=>$committedYardsThisFabricQuilted, 'committed_yards_unquilted' => $committedYardsThisFabricUnquilted,'num_rolls'=>count($allrolls));
							}
						}
						$this->set('yardageconfig','specific');
						$this->set('matchingRolls',$matchingRolls);
						$this->set('rollLiveYards',$rollLiveYards);
						$this->set('yardagematches',$matches);
					}
				break;
				case "namecollectionvendor":
					$defaultQuery=0;
					//build different query with conditions
					if(strlen(trim($this->request->data['fabricname'])) >0){
						$foundFabrics=array();
						$fabricLookup=$this->Fabrics->find('all',['conditions'=>['OR' => ['fabric_name LIKE' => '%'.$this->request->data['fabricname'].'%'],['CONCAT(fabric_name,\' \',color) LIKE' => '%'.$this->request->data['fabricname'].'%', ['color LIKE' => '%'.$this->request->data['fabricname'].'%']]]])->toArray();
						foreach($fabricLookup as $fabrow){
							$foundFabrics[]=$fabrow['id'];
						}
						if(count($foundFabrics) >1){
							$findRolls=$this->MaterialInventory->find('all',['conditions'=>['material_type'=>'Fabrics','material_id IN' => $foundFabrics]])->toArray();
						}elseif(count($foundFabrics) == 1){
							$findRolls=$this->MaterialInventory->find('all',['conditions'=>['material_type'=>'Fabrics','material_id' => $foundFabrics[0]]])->toArray();
						}
					}elseif($this->request->data['collection_name'] != '' && $this->request->data['collection_name'] != '0'){
					}elseif($this->request->data['vendor_id'] != '' && $this->request->data['vendor_id'] != '0'){
					}
				break;
				case "comowner":
					$conditions += array('customer_id' => $this->request->data['comowners']);
				break;
			}
			if($defaultQuery==1){
				$findRolls=$this->MaterialInventory->find('all',['conditions'=>$conditions])->toArray();
				$this->set('resultRows',$findRolls);
			}
			$this->render('lookuprollresults');
	}
	public function lookuproll(){
		$allVendors=$this->Vendors->find('all',['conditions'=>['status'=>'Active'],'order'=>['vendor_name'=>'asc']])->toArray();
	    $this->set('allVendors',$allVendors);
	    
		$allCOMs=$this->Customers->find('all')->toArray();
		$this->set('allCOMs',$allCOMs);
		$collections=array();
		$allCollections=$this->Fabrics->find('all',['conditions'=>['collection !=' => ''],'order'=>['collection'=>'asc']])->group('collection')->toArray();
		foreach($allCollections as $collection){
			$collections[]=$collection['collection'];
		}
		$this->set('allCollections',$collections);
	}
	public function gotowo($wonumber,$fabric=0){
		$wolookup=$this->Orders->find('all',['conditions'=>['order_number'=>$wonumber]])->toArray();
		if($fabric > 0){
			$ifhighlightfabric='?highlightFabric='.$fabric;
		}else{
			$ifhighlightfabric='';
		}
		foreach($wolookup as $wo){
			return $this->redirect('/orders/editlines/'.$wo['id'].$ifhighlightfabric);
		}
	}
	public function fabricaliases($action='default',$aliasID=0){
		switch($action){
			case 'default';
			default:
				//auto render allowed
			break;
			case 'add':
				$this->autoRender=false;
				if($this->request->data){
					//save the new alias
					//check if there's already an alias for this fabric id and customer
					$lookupAlias=$this->FabricAliases->find('all',['conditions'=>['fabric_id'=>$this->request->data['fabric_color'],'customer_id'=>$this->request->data['customer_id']]])->toArray();
					if(count($lookupAlias) >0){
						$this->Flash->error('There is already an alias in the system for the selected fabric, color, and customer. Please edit existing alias.');
						return $this->redirect('/products/fabricaliases/');
					}
					$aliasTable=TableRegistry::get('FabricAliases');
					$newAlias=$aliasTable->newEntity();
					$newAlias->fabric_id = $this->request->data['fabric_color'];
					$newAlias->customer_id = $this->request->data['customer_id'];
					$newAlias->fabric_name = $this->request->data['alias_fabric_name'];
					$newAlias->color = $this->request->data['alias_color'];
					if($aliasTable->save($newAlias)){
						//log it, flash it, redirect it
						$thisCustomer=$this->Customers->get($this->request->data['customer_id'])->toArray();
						$this->Flash->success('Successfully added fabric alias.');
						$this->logActivity($_SERVER['REQUEST_URI'],"Added fabric alias \"".$this->request->data['alias_fabric_name']." - ".$this->request->data['alias_color']."\" to customer \"".$thisCustomer['company_name']."\"");
						return $this->redirect("/products/fabricaliases/");
					}
				}else{
					//show Add Alias form
					$allCustomers=$this->Customers->find('all',['conditions'=>['status'=>'Active'],'order'=>['company_name'=>'ASC']])->toArray();
					$this->set('allCustomers',$allCustomers);
					$allFabrics=$this->Fabrics->find('all',['fields'=>['id','fabric_name','color'],'conditions'=>['is_hci_fabric'=>1,'com_fabric'=>0],'order'=>['fabric_name'=>'ASC','color'=>'ASC']])->toArray();
					$this->set('allFabrics',$allFabrics);
					$this->render('addalias');
				}
			break;
			case 'edit':
				$this->autoRender=false;
				if($this->request->data){
					//save changes to alias
					$oldValues=$this->FabricAliases->get($aliasID)->toArray();
					$thisCustomer=$this->Customers->get($oldValues['customer_id'])->toArray();
					$aliasTable=TableRegistry::get('FabricAliases');
					$thisAlias=$aliasTable->get($aliasID);
					$thisAlias->fabric_name = $this->request->data['alias_fabric_name'];
					$thisAlias->color = $this->request->data['alias_color'];
					if($aliasTable->save($thisAlias)){
						$this->Flash->success('Successfully saved changes to selected Fabric Alias');
						$this->logActivity($_SERVER['REQUEST_URI'],"Changed Fabric Alias \"".$oldValues['fabric_name']." - ".$oldValues['color']."\" to \"".$this->request->data['alias_fabric_name']." ".$this->request->data['alias_color']."\" for customer \"".$thisCustomer['company_name']."\"");
						$this->redirect('/products/fabricaliases/');
					}
				}else{
					//show edit form
					$thisAlias=$this->FabricAliases->get($aliasID)->toArray();
					$this->set('thisAlias',$thisAlias);
					$thisCustomer=$this->Customers->get($thisAlias['customer_id'])->toArray();
					$this->set('thisCustomer',$thisCustomer);
					$thisFabric=$this->Fabrics->get($thisAlias['fabric_id'])->toArray();
					$this->set('thisFabric',$thisFabric);
					$allCustomers=$this->Customers->find('all',['conditions'=>['status'=>'Active'],'order'=>['company_name'=>'ASC']])->toArray();
					$this->set('allCustomers',$allCustomers);
					$allFabrics=$this->Fabrics->find('all',['fields'=>['id','fabric_name','color'],'conditions'=>['is_hci_fabric'=>1,'com_fabric'=>0],'order'=>['fabric_name'=>'ASC','color'=>'ASC']])->toArray();
					$this->set('allFabrics',$allFabrics);
					$this->render('editalias');
				}
			break;
			case 'delete':
				$this->autoRender=false;
				if($this->request->data){
					//process the delete
					$thisAlias=$this->FabricAliases->get($aliasID)->toArray();
					$thisCustomer=$this->Customers->get($thisAlias['customer_id'])->toArray();
					$aliasTable=TableRegistry::get('FabricAliases');
					$aliasRow=$aliasTable->get($aliasID);
					if($aliasTable->delete($aliasRow)){
						$this->Flash->success('Successfully deleted selected Fabric Alias');
						$this->logActivity($_SERVER['REQUEST_URI'],"Deleted Fabric Alias \"".$thisAlias['fabric_name']." - ".$thisAlias['color']."\" for customer \"".$thisCustomer['company_name']."\"");
						$this->redirect('/products/fabricaliases/');
					}
				}else{
					//show a warning
					$this->render('deletealias');
				}
			break;
		}
	}
	public function getfabricsaliaslist(){
		$fabrics=array();
		//$this->autoRender=false;
		$conditions=array();
		if(isset($this->request->data['search']['value']) && strlen(trim($this->request->data['search']['value'])) >0){
			//this is a search
			$conditions['OR']=array();
			//search Customers db
			$lookupCustomers=$this->Customers->find('all',['conditions'=>['company_name LIKE' => '%'.trim($this->request->data['search']['value']).'%']])->toArray();
			if(count($lookupCustomers) > 1){
				$customerIDsArr=array();
				foreach($lookupCustomers as $customerRow){
					$customerIDsArr[]=$customerRow['id'];
				}
				$conditions['OR'] += array('customer_id IN' => $customerIDsArr);
			}elseif(count($lookupCustomers) == 1){
				$customerIDsArr=array();
				foreach($lookupCustomers as $customerRow){
					$customerIDsArr[]=$customerRow['id'];
				}
				$conditions['OR'] += array('customer_id' => $customerIDsArr[0]);
			}
			//search Fabrics db
			$lookupFabrics=$this->Fabrics->find('all',['conditions'=>['OR' => [
				'fabric_name LIKE' => '%'.trim($this->request->data['search']['value']).'%',
				'color LIKE' => '%'.trim($this->request->data['search']['value']).'%',
				'CONCAT(fabric_name,\' \',color) LIKE'=>'%'.trim($this->request->data['search']['value']).'%'
			]
			]])->toArray();
			if(count($lookupFabrics) > 1){
				$fabricIDsArr=array();
				foreach($lookupFabrics as $fabricRow){
					$fabricIDsArr[]=$fabricRow['id'];
				}
				$conditions['OR'] += array('fabric_id IN' => $fabricIDsArr);
			}elseif(count($lookupFabrics) == 1){
				$fabricIDsArr=array();
				foreach($lookupFabrics as $fabricRow){
					$fabricIDsArr[]=$fabricRow['id'];
				}
				$conditions['OR'] += array('fabric_id' => $fabricIDsArr[0]);
			}
			$conditions['OR'] += array('fabric_name LIKE'=>'%'.trim($this->request->data['search']['value']).'%');
			$conditions['OR'] += array('color LIKE'=>'%'.trim($this->request->data['search']['value']).'%');
			$conditions['OR'] += array('CONCAT(fabric_name,\' \',color) LIKE'=>'%'.trim($this->request->data['search']['value']).'%');
		}
		//print_r($conditions);exit;
		$overallTotalRows=$this->FabricAliases->find()->count();
		$allAliases=$this->FabricAliases->find('all',['conditions'=>$conditions])->offset($this->request->data['start'])->limit($this->request->data['length'])->hydrate(false)->toArray();		
		$totalFilteredRows=$this->FabricAliases->find('all',['conditions'=>$conditions])->count();
		foreach($allAliases as $alias){
			$realFabric=$this->Fabrics->get($alias['fabric_id'])->toArray();
			
			$images="<img src=\"/files/fabrics/".$realFabric['id']."/".$realFabric['image_file']."\" width=\"75\" height=\"75\" class=\"fabricimg\" />";

			$thisCustomer=$this->Customers->get($alias['customer_id'])->toArray();
			$fabrics[]=array(
				'DT_RowId'=>'row_'.$alias['id'],
				'DT_RowClass'=>'rowtest',
				'0' => $images,
				'1' => $realFabric['id'],
				'2' => $realFabric['fabric_name'],
				'3' => $realFabric['color'],
				'4' => $thisCustomer['company_name'],
				'5' => $alias['fabric_name'],
				'6' => $alias['color'],
				'7' => '<a href="/products/fabricaliases/edit/'.$alias['id'].'/"><img src="/img/edit.png" alt="Edit This Alias" title="Edit This Alias" /></a> <a href="/products/fabricaliases/delete/'.$alias['id'].'/"><img src="/img/delete.png" alt="Delete This Alias" title="Delete This Alias" /></a>'
			);
		}
		$this->set('draw',$this->request->data['draw']);
		$this->set('recordsTotal',$overallTotalRows);
		$this->set('recordsFiltered',$totalFilteredRows);
		$this->set('data',$fabrics);
		$this->set('_serialize',['draw','recordsTotal','recordsFiltered','data']);
	}
	public function getwtfabriccolorcheckboxes($wtID,$fabricname){
		$this->autoRender=false;
		$allColors=$this->Fabrics->find('all',['conditions'=>['fabric_name'=>urldecode($fabricname)]])->toArray();
		echo "<div id=\"colorselections_".str_replace("'","",str_replace(" ","_",$fabricname))."\" class=\"coloroptionsblock\">
		<h4>Select Which <em>".$fabricname."</em> Colors You Want Available For This Window Treatment <a href=\"javascript:checkallcolors('".str_replace("'","\'",$fabricname)."');\">Check All</a> <a href=\"javascript:uncheckallcolors('".str_replace("'","\'",$fabricname)."');\">Uncheck All</a></h4>";
		foreach($allColors as $thiscolor){
			echo "<div class=\"colorselection\"><label><input type=\"checkbox\" name=\"use_".str_replace("'","",str_replace(" ","_",$fabricname))."_color_".str_replace(" ","_",$thiscolor['color'])."\" value=\"yes\" /> <img src=\"/files/fabrics/".$thiscolor['id']."/".$thiscolor['image_file']."\" height=\"14\" width=\"14\" /> ".$thiscolor['color']."</label></div>";
		}
		echo "<div style=\"clear:both;\"></div></div>";
	}

	public function getfabriccolorscheckboxes($product=false,$fabricName){
		$this->autoRender=false;
		$conditions=array();

		if($product){
			switch($product){
				case "wt":
					$conditions += array('use_in_window'=>1);
				break;
				case "cc":
					$conditions += array('OR'=>array('use_in_cc' => 1,'use_in_sc'=>1));
				break;
				case "bs":
					$conditions += array('use_in_bs' => 1);
				break;
			}

			$conditions += array('fabric_name' => urldecode($fabricName));

			$getColors=$this->Fabrics->find('all',['conditions' => $conditions, 'order' => ['fabric_name' => 'ASC', 'color'=>'ASC']]);
			echo "<div id=\"colorselections_".str_replace("'","",str_replace(" ","_",$fabricName))."\" class=\"coloroptionsblock\">
			<h4>Select Which <em>".$fabricName."</em> Colors You Want Available For This Window Treatment <a href=\"javascript:checkallcolors('".str_replace("'","\'",$fabricName)."');\">Check All</a> <a href=\"javascript:uncheckallcolors('".str_replace("'","\'",$fabricName)."');\">Uncheck All</a></h4>";
			foreach($getColors as $thiscolor){
				echo "<div class=\"colorselection\"><label><input type=\"checkbox\" name=\"use_".str_replace("'","",str_replace(" ","_",$fabricName))."_color_".str_replace(" ","_",$thiscolor['color'])."\" value=\"yes\" /> <img src=\"/files/fabrics/".$thiscolor['id']."/".$thiscolor['image_file']."\" height=\"14\" width=\"14\" /> ".$thiscolor['color']."</label></div>";
			}
			echo "<div style=\"clear:both;\"></div></div>";
		}
	}


    public function manageclasses(){
        
    }
    
    public function managesubclasses(){
        
    }
    
    public function getproductclasses(){
		$classes=array();
		$overallTotalRows=$this->ProductClasses->find()->count();
		$conditions=array();
		if(isset($this->request->data['search']['value']) && strlen(trim($this->request->data['search']['value'])) >0){
			//this is a search
			$conditions['OR']=array();
			$conditions['OR'] += array('class_name LIKE'=>'%'.trim($this->request->data['search']['value']).'%');
		}
		
		
		$conn = ConnectionManager::get('default');
			
		$productfind=$this->ProductClasses->find('all',['conditions'=>$conditions,'order'=>['class_name'=>'asc']])->offset($this->request->data['start'])->limit($this->request->data['length'])->hydrate(false)->toArray();
		$totalFilteredRows=$this->ProductClasses->find('all',['conditions'=>$conditions])->count();
		foreach($productfind as $class){
			
			$thisActions='<a href="/products/editclass/'.$class['id'].'"><img src="/img/edit.png" /></a>';
			
			$lookupClassUsageQ="SELECT `id` FROM `quote_line_items` WHERE `product_class` = '".$class['id']."'";
			$runquery=$conn->execute($lookupClassUsageQ);
			$lookupClassUsage=$runquery->fetchAll('assoc');
			
			if(count($lookupClassUsage) == 0){
			    $thisActions .= ' <a href="/products/deleteclass/'.$class['id'].'"><img src="/img/delete.png" /></a>';
			}
			
			
			$classes[]=array(
				'DT_RowId'=>'row_'.$class['id'],
				'DT_RowClass'=>'rowtest',
				'0' => $class['class_name'],
				'1' => $class['status'],
				'2' => $thisActions
			);
		}
		$this->set('draw',$this->request->data['draw']);
		$this->set('recordsTotal',$overallTotalRows);
		$this->set('recordsFiltered',$totalFilteredRows);
		$this->set('data',$classes);
		$this->set('_serialize',['draw','recordsTotal','recordsFiltered','data']);
	}
	
	public function getproductsubclasses(){
		$subclasses=array();
		$overallTotalRows=$this->ProductSubclasses->find()->count();
		$conditions=array();
		if(isset($this->request->data['search']['value']) && strlen(trim($this->request->data['search']['value'])) >0){
			//this is a search
			$conditions['OR']=array();
			$conditions['OR'] += array('subclass_name LIKE'=>'%'.trim($this->request->data['search']['value']).'%');
		}
		
		$conn = ConnectionManager::get('default');
		
		$productfind=$this->ProductSubclasses->find('all',['conditions'=>$conditions,'order'=>['subclass_name'=>'asc']])->offset($this->request->data['start'])->limit($this->request->data['length'])->hydrate(false)->toArray();
		$totalFilteredRows=$this->ProductSubclasses->find('all',['conditions'=>$conditions])->count();
		foreach($productfind as $subclass){
			
			$thisClass=$this->ProductClasses->get($subclass['class_id'])->toArray();
			
			if($subclass['tally'] == '1'){
			    $thisTallyIcon='<img src="/img/completed.png" />';
			}else{
			    $thisTallyIcon='<img src="/img/delete.png" />';
			}
			
			$thisActions='<a href="/products/editsubclass/'.$subclass['id'].'"><img src="/img/edit.png" /></a>';
			//$lookupClassUsage=$this->QuoteLineItems->find('count',['conditions' => ['product_subclass' => $subclass['id']]]);
			
			
			$lookupClassUsageQ="SELECT `id` FROM `quote_line_items` WHERE `product_subclass` = '".$subclass['id']."'";
			$runquery=$conn->execute($lookupClassUsageQ);
			$lookupClassUsage=$runquery->fetchAll('assoc');
			
			if(count($lookupClassUsage) == 0){
			    $thisActions .= ' <a href="/products/deletesubclass/'.$subclass['id'].'"><img src="/img/delete.png" /></a>';
			}
			
			$subclasses[]=array(
				'DT_RowId'=>'row_'.$subclass['id'],
				'DT_RowClass'=>'rowtest',
				'0' => $subclass['subclass_name'],
				'1' => $thisClass['class_name'],
				'2' => $subclass['status'],
				'3' => $thisTallyIcon,
				'4' => $thisActions
			);
		}
		$this->set('draw',$this->request->data['draw']);
		$this->set('recordsTotal',$overallTotalRows);
		$this->set('recordsFiltered',$totalFilteredRows);
		$this->set('data',$subclasses);
		$this->set('_serialize',['draw','recordsTotal','recordsFiltered','data']);
	}
    
    public function newclass(){
        if($this->request->data){
            $newClass=$this->ProductClasses->newEntity();
            $newClass->class_name=$this->request->data['class_name'];
            $newClass->status=$this->request->data['status'];
            $this->ProductClasses->save($newClass);
            $this->Flash->success('Created new product class "'.$this->request->data['class_name'].'"');
            $this->logActivity($_SERVER['REQUEST_URI'],'Created new product class "'.$this->request->data['class_name'].'"');
            return $this->redirect('/products/manageclasses/');
        }else{
            
        }
    }
    
    public function newsubclass(){
        if($this->request->data){
            $newSubclass=$this->ProductSubclasses->newEntity();
            $newSubclass->subclass_name = $this->request->data['subclass_name'];
            $newSubclass->class_id = $this->request->data['class_id'];
            $newSubclass->status=$this->request->data['status'];
            $newSubclass->tally=$this->request->data['tally'];
            $this->ProductSubclasses->save($newSubclass);
            $this->Flash->success('Created new product sub-class "'.$this->request->data['subclass_name'].'"');
            $this->logActivity($_SERVER['REQUEST_URI'],'Created new product sub-class "'.$this->request->data['subclass_name'].'"');
            return $this->redirect('/products/managesubclasses/');
        }else{
            $availableClasses=array();
            $allClasses=$this->ProductClasses->find('all',['order'=>['class_name'=>'asc']])->toArray();
            foreach($allClasses as $class){
                $availableClasses[$class['id']]=$class['class_name'];
            }
            $this->set('availableClasses',$availableClasses);
        }
    }
    
    
    public function deletesubclass($subclassID){
        $subclassData=$this->ProductSubclasses->get($subclassID)->toArray();
        $this->set('subclassData',$subclassData);
        
        $thisClass=$this->ProductClasses->get($subclassData['class_id'])->toArray();
        $this->set('thisClass',$thisClass);
        
        if($this->request->data){
            $thisSubclass=$this->ProductSubclasses->get($subclassID);
            $this->ProductSubclasses->delete($thisSubclass);
            
            $this->Flash->success('Deleted product sub-class "'.$subclassData['subclass_name'].'"');
            $this->logActivity($_SERVER['REQUEST_URI'],'Deleted product sub-class "'.$subclassData['subclass_name'].'"');
            return $this->redirect('/products/managesubclasses/');
            
        }else{
            
        }
    }
    
    public function deleteclass($classID){
        $classData=$this->ProductClasses->get($classID)->toArray();
        $this->set('classData',$classData);
        
        if($this->request->data){

            $thisClass=$this->ProductClasses->get($classID);
            $this->ProductClasses->delete($thisClass);
            
            $this->Flash->success('Deleted product class "'.$classData['class_name'].'"');
            $this->logActivity($_SERVER['REQUEST_URI'],'Deleted product class "'.$classData['class_name'].'"');
            return $this->redirect('/products/manageclasses/');
        }else{
            
        }
    }
    
    public function editclass($classID){
        if($this->request->data){
            $thisClass=$this->ProductClasses->get($classID);
            $thisClass->class_name = $this->request->data['class_name'];
            $thisClass->status = $this->request->data['status'];
            $this->ProductClasses->save($thisClass);
            
            $this->Flash->success('Edited product class "'.$this->request->data['class_name'].'"');
            $this->logActivity($_SERVER['REQUEST_URI'],'Edited product class "'.$this->request->data['class_name'].'"');
            return $this->redirect('/products/manageclasses/');
            
        }else{
            $thisClass=$this->ProductClasses->get($classID)->toArray();
            $this->set('thisClass',$thisClass);
        }
    }
    
    public function editsubclass($subclassID){
        if($this->request->data){
            
            $thisSubclass=$this->ProductSubclasses->get($subclassID);
            $thisSubclass->subclass_name = $this->request->data['subclass_name'];
            $thisSubclass->status = $this->request->data['status'];
            $thisSubclass->class_id = $this->request->data['class_id'];
            $thisSubclass->tally = $this->request->data['tally'];
            $this->ProductSubclasses->save($thisSubclass);
            
            $this->Flash->success('Edited product sub-class "'.$this->request->data['subclass_name'].'"');
            $this->logActivity($_SERVER['REQUEST_URI'],'Edited product sub-class "'.$this->request->data['subclass_name'].'"');
            return $this->redirect('/products/managesubclasses/');
            
            
        }else{
            $thisSubclass=$this->ProductSubclasses->get($subclassID)->toArray();
            $this->set('thisSubclass',$thisSubclass);
            
            $availableClasses=array();
            $allClasses=$this->ProductClasses->find('all',['order'=>['class_name'=>'asc']])->toArray();
            foreach($allClasses as $class){
                $availableClasses[$class['id']]=$class['class_name'];
            }
            $this->set('availableClasses',$availableClasses);
        }
    }

}