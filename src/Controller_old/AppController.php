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

use Cake\Controller\Controller;
use Cake\Event\Event;
use Cake\Utility\Security;
use Cake\ORM\TableRegistry;
use Cake\ORM\Table;
use Cake\Core\Configure;
use Cake\Mailer\Email;
use \Imagick;

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @link http://book.cakephp.org/3.0/en/controllers.html#the-app-controller
 */
class AppController extends Controller
{

    /**
     * Initialization hook method.
     *
     * Use this method to add common initialization code like loading components.
     *
     * @return void
     */
     public function initialize()
    {
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
		$this->loadModel('UserTypes');
		$this->loadModel('Users');
		$this->loadModel('Customers');
		$this->loadModel('Settings');
		$this->loadModel('Quotes');
		$this->loadModel('QuoteLineItems');
		$this->loadModel('QuoteLineItemMeta');
		$this->loadModel('Orders');
		$this->loadModel('CustomerPricingExceptions');
		$this->loadModel('Fabrics');
		$this->loadModel('QuiltingOps');
		
		
		$this->loadModel('OrderItems');
		$this->loadModel('OrderItemMeta');
		$this->loadModel('OrderItemStatus');
		/**PPSASCRUM-45 start */
		$this->loadModel('WorkOrderItems');
		$this->loadModel('WorkOrderItemMeta');
		$this->loadModel('WorkOrderItemStatus');
		$this->loadModel('WorkOrders');
		$this->loadModel('WorkOrderLineItems');
		/**PPSASCRUM-45 end */
		 
		$this->loadModel('MaterialPurchases');
		$this->loadModel('MaterialInventory');
		$this->loadModel('MaterialUsages');
		$this->loadModel('FabricAliases');
		$this->loadModel('QuoteBomRequirements');

		$this->loadModel('CubicleCurtains');
		$this->loadModel('Services');
		$this->loadModel('TrackSystems');
		$this->loadModel('Bedspreads');
		$this->loadModel('WindowTreatments');
        $this->loadModel('Shipments');
        $this->loadModel('SherryBatches');
        $this->loadModel('ShippingMethods');
        $this->loadModel('Projects');
        $this->loadModel('SherryCache');
        $this->loadModel('QuoteTypes');
    }

    public function beforeFilter(Event $event){
		$this->set('menuList',$this->getMenuList());
		
		
		
		if($this->request->params['controller'] == 'Cron' || ($this->request->params['controller'] == 'Dashboard' && $this->request->params['action'] == 'index') || ($this->request->params['controller'] == 'Users' && ($this->request->params['action'] == 'logout' || $this->request->params['action'] == 'login'))){
			//allow it

		}else{
			//check permissions list for this user type

			if($this->Auth->user()){
				$thisUserType=$this->UserTypes->get($this->Auth->user('user_type_id'))->toArray();

				//if($this->Auth->user('id')==1){
					if(isset($this->request->params['pass'][0]) && ($this->request->params['pass'][0] == 'add' || $this->request->params['pass'][0] == 'edit' || $this->request->params['pass'][0] == 'delete' || $this->request->params['pass'][0] == 'clone')){
						$permval=$this->request->params['action']."::".$this->request->params['pass'][0]."@".$this->request->params['controller'];
					}else{
						$permval=$this->request->params['action'].'@'.$this->request->params['controller'];
					}
					//echo "<pre>";print_r($this->request->params);echo "</pre>";exit;
				//}
			
				$groupPermissionSet=json_decode($thisUserType['permissions'],true);
			

				if(!isset($groupPermissionSet[$permval])){
					//allow it
				}else{
					if($groupPermissionSet[$permval]==0){
						$this->Flash->error('Permission denied for requested action.');
						return $this->redirect('/');
					}
				}
				
			}else{
				//allow it to get redirected
				
			}

		}
		
		
    }


    public function currentUserCan($controller,$action,$other=false){
        
        $thisUserType=$this->UserTypes->get($this->Auth->user('user_type_id'))->toArray();

        if($other){
			$permval=$action."::".$other."@".$controller;
		}else{
			$permval=$action.'@'.$controller;
		}
		
		$groupPermissionSet=json_decode($thisUserType['permissions'],true);
		
		if(!isset($groupPermissionSet[$permval])){
		    return true;
		}else{
		    if($groupPermissionSet[$permval]==0){
		        return false;
		    }else{
		        return true;
		    }
		}
		
    }


    public function cleanparameterreplacements($input){
        return str_replace('__question__','?',str_replace('__space__',' ',str_replace('__pound__','#',str_replace('__slash__','/',urldecode($input)))));
    }

    public function getorderscheduleditems($orderID){

        $thisOrder=$this->Orders->get($orderID)->toArray();
        $thisQuote=$this->Quotes->get($thisOrder['quote_id'])->toArray();
        $thisOrderItems=$this->OrderItems->find('all',['conditions'=>['order_id' => $orderID]])->toArray();
        $thisQuoteItems=$this->QuoteLineItems->find('all',['conditions'=>['quote_id'=>$thisQuote['id']],'order'=>['sortorder'=>'asc']])->toArray();

        $allBatches=$this->SherryBatches->find('all',['conditions'=>['work_order_id' => $orderID],'order'=>['date'=>'asc']])->toArray();

        $output=array();
        foreach($thisOrderItems as $itemRow){
            $allSchedulesOfThisItem=$this->OrderItemStatus->find('all',['conditions'=>['order_item_id' => $itemRow['id'],'status'=>'Scheduled']])->toArray();
            foreach($allSchedulesOfThisItem as $scheduleRow){
                if(isset($output[$itemRow['id']])){
                    $output[$itemRow['id']][$scheduleRow['sherry_batch_id']]=array('qty' => $scheduleRow['qty_involved'], 'date' => date('m/d/Y',$scheduleRow['time']));
                }else{
                    $output[$itemRow['id']]=array($scheduleRow['sherry_batch_id'] => array('qty' => $scheduleRow['qty_involved'], 'date' => date('m/d/Y',$scheduleRow['time'])));
                }
            }
        }

        return $output;

    }

    public function getordercompleteditems($orderID){
        $thisOrder=$this->Orders->get($orderID)->toArray();
        $thisQuote=$this->Quotes->get($thisOrder['quote_id'])->toArray();
        $thisOrderItems=$this->OrderItems->find('all',['conditions'=>['order_id' => $orderID]])->toArray();
        $thisQuoteItems=$this->QuoteLineItems->find('all',['conditions'=>['quote_id'=>$thisQuote['id']],'order'=>['sortorder'=>'asc']])->toArray();

        $allBatches=$this->SherryBatches->find('all',['conditions'=>['work_order_id' => $orderID],'order'=>['date'=>'asc']])->toArray();

        $output=array();
        foreach($thisOrderItems as $itemRow){
            $allSchedulesOfThisItem=$this->OrderItemStatus->find('all',['conditions'=>['order_item_id' => $itemRow['id'],'status'=>'Completed']])->toArray();
            foreach($allSchedulesOfThisItem as $scheduleRow){


                $thisSherryBatchCache=$this->SherryCache->find('all',['conditions' => ['batch_id' => $scheduleRow['sherry_batch_id']]])->toArray();
                $thisBatchShipDate='';
                $thisBatchCompletionDate='';
                foreach($thisSherryBatchCache as $cacheRow){
                    $thisBatchCompletionDate=$cacheRow['batch_completed_date'];
                    $thisBatchShipDate=$cacheRow['batch_shipped_date'];
                }

                if(isset($output[$itemRow['id']])){
                    $output[$itemRow['id']][$scheduleRow['sherry_batch_id']]=array('qty' => $scheduleRow['qty_involved'], 'date' => date('m/d/Y',$scheduleRow['time']),'completion_date'=>$thisBatchCompletionDate,'shipped_date' => $thisBatchShipDate);
                }else{
                    $output[$itemRow['id']]=array($scheduleRow['sherry_batch_id'] => array('qty' => $scheduleRow['qty_involved'], 'date' => date('m/d/Y',$scheduleRow['time']),'completion_date'=>$thisBatchCompletionDate,'shipped_date' => $thisBatchShipDate));
                }
            }
        }

        return $output;
    }

    public function getordershippeditems($orderID){
        $thisOrder=$this->Orders->get($orderID)->toArray();
        $thisQuote=$this->Quotes->get($thisOrder['quote_id'])->toArray();
        $thisOrderItems=$this->OrderItems->find('all',['conditions'=>['order_id' => $orderID]])->toArray();
        $thisQuoteItems=$this->QuoteLineItems->find('all',['conditions'=>['quote_id'=>$thisQuote['id']],'order'=>['sortorder'=>'asc']])->toArray();

        $allBatches=$this->SherryBatches->find('all',['conditions'=>['work_order_id' => $orderID],'order'=>['date'=>'asc']])->toArray();

        $output=array();
        foreach($thisOrderItems as $itemRow){
            $allSchedulesOfThisItem=$this->OrderItemStatus->find('all',['conditions'=>['order_item_id' => $itemRow['id'],'status'=>'Shipped']])->toArray();
            foreach($allSchedulesOfThisItem as $scheduleRow){

                //find this shipment
                if(!is_null($scheduleRow['shipment_id']) && $scheduleRow['shipment_id'] > 0){
                    $thisShipment=$this->Shipments->get($scheduleRow['shipment_id'])->toArray();
                    $thistracking=$thisShipment['tracking_number'];
                    $thiscarrier=$thisShipment['carrier'];
                }else{
                    $thistracking='';
                    $thiscarrier='';
                }


                $thisSherryBatchCache=$this->SherryCache->find('all',['conditions' => ['batch_id' => $scheduleRow['sherry_batch_id']]])->toArray();
                $thisBatchShipDate='';
                $thisBatchCompletionDate='';
                foreach($thisSherryBatchCache as $cacheRow){
                    $thisBatchCompletionDate=$cacheRow['batch_completed_date'];
                    $thisBatchShipDate=$cacheRow['batch_shipped_date'];
                }

                if(isset($output[$itemRow['id']])){
                    $output[$itemRow['id']][$scheduleRow['sherry_batch_id']]=array('qty' => $scheduleRow['qty_involved'], 'date' => date('m/d/Y',$scheduleRow['time']),"carrier"=>$thiscarrier, "tracking" => $thistracking,'completion_date'=>$thisBatchCompletionDate,'shipped_date' => $thisBatchShipDate);
                }else{
                    $output[$itemRow['id']]=array($scheduleRow['sherry_batch_id'] => array('qty' => $scheduleRow['qty_involved'], 'date' => date('m/d/Y',$scheduleRow['time']),"carrier"=>$thiscarrier, "tracking"=>$thistracking,'completion_date'=>$thisBatchCompletionDate,'shipped_date' => $thisBatchShipDate));
                }
            }
        }

        return $output;
    }
    
    
    public function getorderinvoiceditems($orderID){
        $thisOrder=$this->Orders->get($orderID)->toArray();
        $thisQuote=$this->Quotes->get($thisOrder['quote_id'])->toArray();
        $thisOrderItems=$this->OrderItems->find('all',['conditions'=>['order_id' => $orderID]])->toArray();
        $thisQuoteItems=$this->QuoteLineItems->find('all',['conditions'=>['quote_id'=>$thisQuote['id']],'order'=>['sortorder'=>'asc']])->toArray();

        $allBatches=$this->SherryBatches->find('all',['conditions'=>['work_order_id' => $orderID],'order'=>['date'=>'asc']])->toArray();

        $output=array();
        foreach($thisOrderItems as $itemRow){
            $allSchedulesOfThisItem=$this->OrderItemStatus->find('all',['conditions'=>['order_item_id' => $itemRow['id'],'status'=>'Invoiced']])->toArray();
            foreach($allSchedulesOfThisItem as $scheduleRow){

                $thisSherryBatchCache=$this->SherryCache->find('all',['conditions' => ['batch_id' => $scheduleRow['sherry_batch_id']]])->toArray();
                $thisBatchInvoicedDate='';
                
                foreach($thisSherryBatchCache as $cacheRow){
                    $thisBatchInvoicedDate=$cacheRow['batch_invoiced_date'];
                }

                if(isset($output[$itemRow['id']])){
                    $output[$itemRow['id']][$scheduleRow['sherry_batch_id']]=array('qty' => $scheduleRow['qty_involved'], 'date' => date('m/d/Y',$scheduleRow['time']),'invoiced_date' => $thisBatchInvoicedDate);
                }else{
                    $output[$itemRow['id']]=array($scheduleRow['sherry_batch_id'] => array('qty' => $scheduleRow['qty_involved'], 'date' => date('m/d/Y',$scheduleRow['time']),'invoiced_date' => $thisBatchInvoicedDate));
                }
            }
        }

        return $output;
    }
    

    public function getorderunscheduleditems($orderID){
        $thisOrder=$this->Orders->get($orderID)->toArray();
        $thisQuote=$this->Quotes->get($thisOrder['quote_id'])->toArray();
        $thisOrderItems=$this->OrderItems->find('all',['conditions'=>['order_id' => $orderID]])->toArray();
        $thisQuoteItems=$this->QuoteLineItems->find('all',['conditions'=>['quote_id'=>$thisQuote['id']],'order'=>['sortorder'=>'asc']])->toArray();

        $allBatches=$this->SherryBatches->find('all',['conditions'=>['work_order_id' => $orderID],'order'=>['date'=>'asc']])->toArray();

        $output=array();
        foreach($thisOrderItems as $itemRow){
            $allSchedulesOfThisItem=$this->OrderItemStatus->find('all',['conditions'=>['order_item_id' => $itemRow['id'],'status'=>'Not Started']])->toArray();
            foreach($allSchedulesOfThisItem as $scheduleRow){

                $originalNumber=$scheduleRow['qty_involved'];
                $numCompleted=0;
                $numShipped=0;
                $numScheduled=0;

                //determine if this same batch+item is partially scheduled, partially completed, or partially shipped in other status entries
                $checkOtherScheduleEntries=$this->OrderItemStatus->find('all',['conditions'=>['order_item_id' => $itemRow['id'],'status IN' => ['Scheduled','Completed','Shipped']]])->toArray();
                foreach($checkOtherScheduleEntries as $otherEntries){
                    switch($otherEntries['status']){
                        case 'Scheduled':
                            $numScheduled = ($numScheduled + $otherEntries['qty_involved']);
                        break;
                        case 'Completed':
                            $numCompleted = ($numCompleted + $otherEntries['qty_involved']);
                        break;
                        case 'Shipped':
                            $numShipped = ($numShipped + $otherEntries['qty_involved']);
                        break;
                    }
                }


                //$numScheduled=($numScheduled - $numShipped - $numCompleted);
                //$numCompleted = ($numCompleted - $numShipped);

                $outstandingCount = ($originalNumber - $numScheduled);

                $output[$itemRow['id']]=array(
                    'scheduled' => $numScheduled,
                    'completed' => $numCompleted,
                    'shipped' => $numShipped,
                    'outstanding_qty' => $outstandingCount
                );

            }
        }

        return $output;
    }

    public function getorderitemsoverall($orderID){
        $thisOrder=$this->Orders->get($orderID)->toArray();
        $thisQuote=$this->Quotes->get($thisOrder['quote_id'])->toArray();
        $thisOrderItems=$this->OrderItems->find('all',['conditions'=>['order_id' => $orderID]])->toArray();
        $thisQuoteItems=$this->QuoteLineItems->find('all',['conditions'=>['quote_id'=>$thisQuote['id']],'order'=>['sortorder'=>'asc']])->toArray();

        $output=array();
        foreach($thisQuoteItems as $itemRow){
            $thisOrderItemID=0;
            foreach($thisOrderItems as $orderItemRow){
                if($orderItemRow['quote_line_item_id'] == $itemRow['id']){
                    $thisOrderItemID=$orderItemRow['id'];
                }
            }

            $output[$thisOrderItemID]=array('qty'=>$itemRow['qty']);
        }

        return $output;
    }

   



    public function getQBItemCode($lineItemID){
    	$thisItem=$this->QuoteLineItems->get($lineItemID)->toArray();
    	$lineItemMetas=$this->QuoteLineItemMeta->find('all',['conditions'=>['quote_item_id' => $lineItemId]])->toArray();
    	$itemMetas=array();
    	foreach($lineItemMetas as $metaRow){
    		$itemMetas[$metaRow['meta_key']]=$metaRow['meta_value'];
    	}

    	switch($thisItem['product_type']){
    		case "calculator":
    			switch($thisItem['calculator_used']){
    				case "bedspread":
    				case "bedspread-manual":
    					return "Bedspread A";
    				break;
    				case "box-pleated":
    					return "Window Treatments A";
    				break;
    				case "cubicle-curtain":
    					return "Cubicle Curtain A";
    				break;
    				case "pinch-pleated":
						return "Window Treatments A";
    				break;
    				case "straight-cornice":
    					return "Window Treatments A";
    				break;
    			}
    		break;
    		case "cubicle_curtains":
    			$thisCC=$this->CubicleCurtains->get($thisItem['product_id'])->toArray();
    			return $thisCC['qb_item_code'];
    		break;
    		case "bedspreads":
    			$thisBS=$this->Bedspreads->get($thisItem['product_id'])->toArray();
    			return $thisBS['qb_item_code'];
    		break;
    		case "window_treatments":
    			$thisWT=$this->WindowTreatments->get($thisItem['product_id'])->toArray();
    			return $thisWT['qb_item_code'];
    		break;
    		case "track_systems":
    			$thisTrack=$this->TrackSystems->get($thisItem['product_id'])->toArray();
    			return $thisTrack['qb_item_code'];
    		break;
    		case "custom":
    			
    			switch($itemMetas['catchallcategory']){
    				/*
    				case "WT Hardware":
    					return "Hardware for WTs MOM";
    				break;
    				*/

    				/*
    				case "Blinds & Shades":
    					return "WTs Hard (blinds and shades)";
    				break;
    				*/

    				/*
    				case "Finished Product":
    					return "";
    				break;
    				*/

    				default:
    				//case "Catch-All":
    					return "CUSTOM ITEM";
    				break;
    			}
    			
    		break;
    		case "services":
    			$thisService=$this->Services->get($thisItem['product_id'])->toArray();
    			return $thisService['qb_item_code'];
    		break;
    		default:
    			return "CUSTOM ITEM";
    		break;
    	}
    }

    public function getQBItemDescription($lineItemID){

    	$thisItem=$this->QuoteLineItems->get($lineItemID)->toArray();
    	
    	$thisQuote=$this->Quotes->get($thisItem['quote_id'])->toArray();

    	$lineItemMetas=$this->QuoteLineItemMeta->find('all',['conditions'=>['quote_item_id' => $lineItemID]])->toArray();
    	
    	$itemMetas=array();
    	foreach($lineItemMetas as $metaRow){
    		$itemMetas[$metaRow['meta_key']]=$metaRow['meta_value'];
    	}

    	if(isset($itemMetas['fabricid']) && intval($itemMetas['fabricid']) >0){
    		$thisFabric=$this->Fabrics->get($itemMetas['fabricid'])->toArray();
			if($fabricAlias=$this->getFabricAlias($itemMetas['fabricid'],$thisQuote['customer_id'])){
				if(isset($itemMetas['usealias']) && $itemMetas['usealias']=='yes'){
					$fabricName=$fabricAlias['fabric_name'];
					$fabricColor=$fabricAlias['color'];
				}else{
					$fabricName=$thisFabric['fabric_name'];
					$fabricColor=$thisFabric['color'];
				}			
			}else{
				$fabricName=$thisFabric['fabric_name'];
				$fabricColor=$thisFabric['color'];
			}
		}else{
			$fabricName='';
			$fabricColor='';
		}


		if($itemMetas['railroaded'] == '1'){
			$ifRailroaded='RR ';
		}else{
			$ifRailroaded='';
		}

    	switch($thisItem['product_type']){
    		case "calculator":

    			switch($thisItem['calculator_used']){
    				case "bedspread":
    				case "bedspread-manual":
    					return 'Bedspread - '.$ifRailroaded.$fabricName.' '.$fabricColor.' '.$itemMetas['width'].'x'.$itemMetas['length'];
    				break;
    				case "box-pleated":
    					return 'Window Treatments - '.$itemMetas['valance-type'].' Valance '.$ifRailroaded.$fabricName.' '.$fabricColor.' '.$itemMetas['face'].'x'.$itemMetas['height'];
    				break;
    				case "cubicle-curtain":
    					return 'Cubicle Curtain - '.$ifRailroaded.$fabricName.' '.$fabricColor.' '.$itemMetas['width'].'x'.$itemMetas['length'];
    				break;
    				case "pinch-pleated":
						return 'Pinch Pleated Drapery - '.$ifRailroaded.$fabricName.' '.$fabricColor.' '.$itemMetas['width'].'x'.$itemMetas['length'];
    				break;
    				case "straight-cornice":
    					return 'Window Treatments - '.$itemMetas['wttype'].' Cornice '.$ifRailroaded.$fabricName.' '.$fabricColor.' '.$itemMetas['width'].'x'.$itemMetas['length'];
    				break;
    			}
    		break;
    		case "cubicle_curtains":
    			return 'Cubicle Curtain - '.$ifRailroaded.$fabricName.' '.$fabricColor.' '.$itemMetas['width'].'x'.$itemMetas['length'];
    		break;
    		case "bedspreads":
     			return 'Bedspread - '.$ifRailroaded.$fabricName.' '.$fabricColor.' '.$itemMetas['width'].'x'.$itemMetas['length'];
    		break;
    		case "window_treatments":
    			return 'Window Treatments - '.$itemMetas['wttype'].' '.$ifRailroaded.$fabricName.' '.$fabricColor.' '.$itemMetas['width'].'x'.$itemMetas['length'];
    		break;
    		case "track_systems":
    			$thisTrack=$this->TrackSystems->get($thisItem['product_id'])->toArray();
    			return $thisTrack['title'].' - '.$thisTrack['description'];
    		break;
    		case "custom":
    			return $thisItem['title'].' - '.$thisItem['description'];
    		break;
    		case "services":
    			$thisService=$this->Services->get($thisItem['product_id'])->toArray();
    			return $thisService['title'];
    		break;
    	}
    }

	
	public function getMenuList(){
		 if($this->Auth->user()) {
			 
			 $userType=$this->UserTypes->get($this->Auth->user('user_type_id'))->toArray();
			 $this->set('accountType',$userType['user_type_name']);
			 
				$menulist='<ul>';
				$menulist .= '<li><a href="/">Dashboard</a></li>';
				$menulist .= '<li><a href="#">Estimation</a>
					<ul>
						<li><a href="/quotes/">Quotes</a></li>
						<li><a href="#">Calculators</a>
							<ul>
								<li><a href="/quotes/newlineitem/0/calculator/bedspread">Bedspread</a></li>
								<li><a href="/quotes/newlineitem/0/calculator/box-pleated/">Valance</a></li>
								<li><a href="/quotes/newlineitem/0/calculator/cubicle-curtain/">Cubicle Curtain</a></li>
								<li><a href="/quotes/newlineitem/0/calculator/straight-cornice/">Cornice</a></li>
								<li><a href="/quotes/newlineitem/0/calculator/pinch-pleated-new/">Pinch Pleated Drapery</a></li>
							</ul>
						</li>
						<li><a href="#">Price List Lookup</a>
							<ul>
								<li><a href="/quotes/pricelistcheck/bedspreads">Bedspreads</a></li>
								<li><a href="/quotes/pricelistcheck/cubicle-curtains">Cubicle Curtains</a></li>
								<li><a href="/quotes/pricelistcheck/window-treatments">Window Treatments</a></li>
							</ul>
						</li>
					</ul>
				</li>';
				$menulist .= '<li><a href="/orders/">Order Management</a>
					<ul>
						<li><a href="/orders/">Orders List</a>
						<ul>
								<li><a href="/orders/workOrder/">WorkOrder List</a></li>
						</ul>
						</li>
						<li><a href="/orders/materials/">Materials Management</a>
							<ul>
								<li><a href="/orders/materials/overview/">Overview</a></li>
								<li><a href="/orders/materials/purchases/">Purchases</a></li>
								<li><a href="/orders/materials/flagged/">Flagged</a></li>
								<li><a href="/orders/materials/inventory/">Inventory</a></li>
								<li><a href="/reports/msr/">MSR</a></li>
							</ul>
						</li>
                        </li>
                        <li><a href="/orders/schedule/">Sherry Schedule</a>
                        <ul>
                        <li><a href="/orders/pendingschedule/">Sh. Sched: Pending Only</a></li>
                        <li><a href="/orders/completedschedule/">Sh. Sched: Completed Only</a></li>

                        </ul>
                        </li>
						<li><a href="/orders/schedule/">Ship To</a>
                        <ul>
                        <li><a href="/orders/facility/">Facility</a></li>
                        <li><a href="/orders/shipto/">Ship-To Address</a></li>
                        </ul>
                        </li>	
						<li><a href="/customers/projects/">Projects</a></li>
						<li><a href="/orders/boxes/">Box Inventory</a></li>
					</ul>
				</li>';
					
				//if($this->Auth->user('user_type_id') == 1 || $this->Auth->user('user_type_id') == 2){
					$menulist .= "<li><a href=\"/products/index\">Products</a>
					<ul>
						<li><a href=\"/products/fabrics/\">Fabrics</a>
							<ul>
								<li><a href=\"/products/fabricaliases/\">Fabric Aliases</a></li>
								<li><a href=\"/products/vendors/\">Fabric Vendors</a></li>
							</ul>
						</li>
						<li><a href=\"/products/linings/\">Linings</a></li>
						<li><a href=\"/products/cubicle-curtains/\">Curtains</a></li>
						<li><a href=\"/products/bedspreads/\">Bedspreads</a></li>
						<li><a href=\"/products/services/\">Services</a></li>
						<li><a href=\"/products/window-treatments/\">Window Treatments</a></li>
						<li><a href=\"/products/track-systems/\">Track Systems</a></li>
						<li><a href=\"/products/vendors/\">Vendors</a></li>
					</ul></li>";
				//}
			 	
			 	$menulist .= '<li><a href="/libraries/">Libraries</a>
					<ul>
					<li><a href="/libraries/image/">Image Library</a></li>
					<li><a href="/libraries/file/">File Library</a></li>
					</ul>
					</li>';
				
				$menulist .= '<li><a href="/customers/">Customers</a>';
				if($this->Auth->user('user_type_id') == 1 || $this->Auth->user('user_type_id') == 2){
					$menulist .= '<ul><li><a href="/customers/">Browse Customers</a></li><li><a href="/customers/add/">Add New Customer</a></li><li><a href="/products/specialpricing/all">Special Pricing</a></li></ul>';
				}
				$menulist .= '</li>';
				
				
				if($this->currentUserCan('Reports','*') || ($this->Auth->user('user_type_id') == 1 || $this->Auth->user('user_type_id') == 2)){
    			    $menulist .= '<li><a href="/reports/">Reports</a>';
    				$menulist .= "<ul>
    					<li><a href=\"/reports/activeorders/\">Active Orders Report</a></li>
    					<li><a href=\"/reports/consolidated/\">Consolidated Bookings Report</a></li>
    					<li><a href=\"/reports/consolidatedquotes\">Consolidated Quotes Report</a></li>
    					<li><a href=\"/reports/completedorders\">Completed Orders Report</a></li>
    					<li><a href=\"/reports/bigquotes\">Big Quotes Report</a></li>
    					<li><a href=\"/reports/quotesnewlines\">Quotes – New Lines Created</a></li> 
    					<li><a href=\"/reports/producedorders/\">Production Report</a></li>
    					<li><a href=\"/reports/shippedorders/\">Shipping Report</a></li>
    					<li><a href=\"/reports/invoicedorders/\">Invoiced Report</a></li>
    					<li><a href=\"/orders/schedulesummary/\">Schedule Summary</a></li>
    					<li><a href=\"/orders/unscheduledreport/\">Unscheduled Report</a></li>
    					<li><a href=\"/reports/backlogreport/\">Backlog Report</a></li>
    					<li><a href=\"/reports/productiondetailbacklog/\">Production Detail Backlog</a></li>
    					<li><a href=\"/reports/backlogreport/booking/\">Bookings Report</a></li>
    					<li><a href=\"/reports/backlogsummary/booking/\">Bookings Summary</a></li>
    					<li><a href=\"/reports/backlogsummary/\">Backlog Summary</a></li>
    					<li><a href=\"/reports/qbreconciliation/\">QB Reconciliation Report</a></li>
    					<li><a href=\"/reports/shipaddressreport\">Ship-To Address Report</a></li>
    				</ul>";
    				$menulist .= '</li>';
				}
				
				
				
				if($this->Auth->user('user_type_id') == 1 || $this->Auth->user('user_type_id') == 2){
					$menulist .= '<li><a href="#">Admin</a>
						<ul>
							<li><a href="/logs/">Activity Logs</a></li>
							<li><a href="/users/">User Management</a>';
								if($this->Auth->user('user_type_id') == 1){
									$menulist .= '<ul>
										<li><a href="/users/roles/">Roles &amp; Permissions</a></li>
									</ul>';
								}
							$menulist .= '</li>
							<li><a href="/settings/">Settings</a>
								<ul>
									<li><a href="/settings/">Settings Management</a></li>
									<li><a href="/settings/markuprules/">Markup Rules</a></li>
									<li><a href="/settings/quotetypes/">Order/Quote Types</a></li>
									<li><a href="/products/manageclasses/">Product Classes</a></li>
									<li><a href="/products/managesubclasses/">Product Sub-Classes</a></li>
								</ul>
							</li>';
							
							if($this->Auth->user('user_type_id') == 1){
								$menulist .= '<li><a href="/admin/bluesheettemplates/">Blue Sheet Templates</a></li>';	
							}
							
					
						$menulist .= '</ul>
					</li>';
				}
			 
				$menulist .= '</ul>';
				return $menulist;
		 }
	}
	
	
	
	public function updatesherrycachefordate($date){
    	$this->autoRender=false;
		$sherryCacheTable=TableRegistry::get('SherryCache');

    	$sherryBatchesLookup=$this->SherryBatches->find('all',['conditions'=>['date'=>$date]])->toArray();
    	foreach($sherryBatchesLookup as $batch){

    		$thisOrder=$this->Orders->get($batch['work_order_id'])->toArray();
    		$thisCustomer=$this->Customers->get($thisOrder['customer_id'])->toArray();
    		$thisQuote=$this->Quotes->get($thisOrder['quote_id'])->toArray();

    		if($thisOrder['shipping_method_id'] > 0){
    			$thisShipMethod=$this->ShippingMethods->get($thisOrder['shipping_method_id'])->toArray();
    		}else{
    			$thisShipMethod=false;
    		}

    		if($thisQuote['project_id'] == 0){
				$thisProject=array();
				$projectName='';
			}else{
				$thisProject=$this->Projects->get($thisQuote['project_id'])->toArray();
				$projectName=$thisProject['title'];
			}


			$thisOrderTotals=array(
				'dollars' => 0,
				'cc' => 0,
				'cclf' => 0,
				'bs' => 0,
				'val' => 0,
				'vallf' => 0,
				'corn' => 0,
				'cornlf' => 0,
				'drape' => 0,
				'drape_widths' => 0,
				'wthw' => 0,
				'blinds' => 0,
				'trklf' => 0
			);

			$thisOrderThisBatchItems=array();


			//$quoteLineItems=$this->QuoteLineItems->find('all',['conditions'=>['enable_tally'=>1,'quote_id'=>$thisOrder['quote_id']],'order'=>['sortorder'=>'asc']])->toArray();
			$quoteLineItems=$this->QuoteLineItems->find('all',['conditions'=>['quote_id'=>$thisOrder['quote_id']],'order'=>['sortorder'=>'asc']])->toArray();

			foreach($quoteLineItems as $quoteItem){

				$thisitemtype='';
				$lineItemMetas=$this->QuoteLineItemMeta->find('all',['conditions' => ['quote_item_id' => $quoteItem['id']]])->toArray();
				$itemMeta=array();
				foreach($lineItemMetas as $lineItemMetaRow){
					$itemMeta[$lineItemMetaRow['meta_key']]=$lineItemMetaRow['meta_value'];
				}

				//determine this quote line item's corresponding order line item
				
				$thisOrderItemLookup=$this->OrderItems->find('all',['conditions' => ['quote_line_item_id' => $quoteItem['id']]])->toArray();
				foreach($thisOrderItemLookup as $orderItemRow){
					$thisOrderItem=$orderItemRow;
				}


				$conditions=['work_order_id' => $thisOrder['id'], 'order_item_id' => $thisOrderItem['id'], 'sherry_batch_id'=>$batch['id'],'status NOT IN' => ['Not Started','Completed','Shipped','Invoiced','Completion Voided','Shipment Voided','Schedule Voided']];

				$statuses=$this->OrderItemStatus->find('all',['conditions'=>$conditions, 'order'=>['time'=>'desc']])->hydrate(false)->toArray();
				
				
				foreach($statuses as $status){


					if($quoteItem['override_active'] == 1){
						$thisOrderTotals['dollars'] = ($thisOrderTotals['dollars'] + (floatval($quoteItem['override_price']) * $status['qty_involved']));
					}else{
						$thisOrderTotals['dollars'] = ($thisOrderTotals['dollars'] + (floatval($quoteItem['pmi_adjusted']) * $status['qty_involved']));
					}

						switch($quoteItem['product_type']){
							case "bedspreads":
							case "newcatchall-bedding":
								$thisOrderTotals['bs'] = ($thisOrderTotals['bs'] + $status['qty_involved']);
							break;
							case "cubicle_curtains":
							case "newcatchall-cubicle":
								$thisOrderTotals['cc'] = ($thisOrderTotals['cc'] + $status['qty_involved']);
								$thisOrderTotals['cclf'] = ($thisOrderTotals['cclf'] + (floatval($itemMeta['labor-billable']) * $status['qty_involved']));
							break;
							case "newcatchall-drapery":
							    $thisOrderTotals['drape'] = ($thisOrderTotals['drape'] + $status['qty_involved']);
								$thisOrderTotals['drape_widths'] = ($thisOrderTotals['drape_widths'] + (floatval($itemMeta['labor-billable-widths']) * $status['qty_involved']));
							break;
							case "newcatchall-valance":
							    $thisOrderTotals['val'] = ($thisOrderTotals['val'] + $status['qty_involved']);
								$thisOrderTotals['vallf'] = ($thisOrderTotals['vallf'] + (floatval($itemMeta['labor-billable']) * $status['qty_involved']));
							break;
							case "newcatchall-cornice":
							    $thisOrderTotals['corn'] = ($thisOrderTotals['corn'] + $status['qty_involved']);
								$thisOrderTotals['cornlf'] = ($thisOrderTotals['cornlf'] + (floatval($itemMeta['labor-billable']) * $status['qty_involved']));
							break;
							case "newcatchall-swtmisc":
							    /*$thisOrderTotals['wt'] = ($thisOrderTotals['wt'] + $status['qty_involved']);
								$thisOrderTotals['wtlf'] = ($thisOrderTotals['wtlf'] + (floatval($itemMeta['labor-billable']) * $status['qty_involved']));*/
							break;
							case "window_treatments":
								$thisitemtypelabel=$itemMeta['wttype'];
								switch($itemMeta['wttype']){
									case "Pinch Pleated Drapery":
										$thisOrderTotals['drape'] = ($thisOrderTotals['drape'] + $status['qty_involved']);
										$thisOrderTotals['drape_widths'] = ($thisOrderTotals['drape_widths'] + (floatval($itemMeta['labor-widths']) * $status['qty_involved']));
									break;
									case "Box Pleated Valance":
									    $thisOrderTotals['val'] = ($thisOrderTotals['val'] + $status['qty_involved']);
									    $thisOrderTotals['vallf'] = ($thisOrderTotals['vallf'] + (floatval($itemMeta['labor-billable']) * $status['qty_involved']));
									break;
									case "Straight Cornice":
									case "Shaped Cornice":
									    $thisOrderTotals['corn'] = ($thisOrderTotals['corn'] + $status['qty_involved']);
									    $thisOrderTotals['cornlf'] = ($thisOrderTotals['cornlf'] + (floatval($itemMeta['labor-billable']) * $status['qty_involved']));
									break;
									/*default:
										$thisOrderTotals['wt'] = ($thisOrderTotals['wt'] + $status['qty_involved']);
										$thisOrderTotals['wtlf'] = ($thisOrderTotals['wtlf'] + (floatval($itemMeta['labor-billable']) * $status['qty_involved']));
									break;*/
								}

							break;
							case "calculator":
								switch($quoteItem['calculator_used']){
									case "pinch-pleated":
									case "pinch-pleated-new":
										$thisOrderTotals['drape'] = ($thisOrderTotals['drape'] + $status['qty_involved']);
										//$thisOrderTotals['drape_widths'] = ($thisOrderTotals['drape_widths'] + (floatval($itemMeta['total-widths']) * $status['qty_involved']));
										$thisOrderTotals['drape_widths'] = ($thisOrderTotals['drape_widths'] + (floatval($itemMeta['labor-widths']) * $status['qty_involved']));
									break;
									case "bedspread":
										$thisOrderTotals['bs'] = ($thisOrderTotals['bs'] + $status['qty_involved']);
									break;
									case "cubicle-curtain":
										$thisOrderTotals['cc'] = ($thisOrderTotals['cc'] + $status['qty_involved']);
										$thisOrderTotals['cclf'] = ($thisOrderTotals['cclf'] + (floatval($itemMeta['labor-billable']) * $status['qty_involved']));
									break;
									case "box-pleated":
										$thisOrderTotals['val'] = ($thisOrderTotals['val'] + $status['qty_involved']);
										$thisOrderTotals['vallf'] = ($thisOrderTotals['vallf'] + (floatval($itemMeta['labor-billable']) * $status['qty_involved']));
									break;
									case "straight-cornice":
										$thisOrderTotals['corn'] = ($thisOrderTotals['corn'] + $status['qty_involved']);
										$thisOrderTotals['cornlf'] = ($thisOrderTotals['cornlf'] + (floatval($itemMeta['labor-billable']) * $status['qty_involved']));
									break;
								}
							break;
							case "track_systems":
							case "newcatchall-hardware":
							    if($quoteItem['product_type'] == 'newcatchall-hardware'){
							        
							        if($quoteItem['product_subclass'] == 3){
							            $thisOrderTotals['trklf'] = ($thisOrderTotals['trklf'] + $status['qty_involved']);
							        }elseif($quoteItem['product_subclass'] == 8){
							            $thisOrderTotals['blinds'] = ($thisOrderTotals['blinds'] + $status['qty_involved']);
							        }else{
							            $thisOrderTotals['wthw'] = ($thisOrderTotals['wthw'] + $status['qty_involved']);
							        }
							        
							    }else{
								    $thisOrderTotals['trklf'] = ($thisOrderTotals['trklf'] + $status['qty_involved']);
							    }
							break;
							case "custom":
								switch($itemMeta['catchallcategory']){
									case "WT Hardware":
										$thisOrderTotals['wthw'] = ($thisOrderTotals['wthw'] + $status['qty_involved']);
									break;
									case "Blinds & Shades":
										$thisOrderTotals['blinds'] = ($thisOrderTotals['blinds'] + $status['qty_involved']);
									break;
								}
							break;
            				case "newcatchall-blinds":
            				case "newcatchall-shades":
            				case "newcatchall-shutters":
            				case "newcatchall-hwtmisc":
            				    $thisOrderTotals['blinds'] = ($thisOrderTotals['blinds'] + $status['qty_involved']);
            			    break;
						}



						$thisOrderThisBatchItems[]=array(
							'line_number'=>$status['order_line_number'],
							'order_item_id' => $status['order_item_id'],
							'qty_involved' => $status['qty_involved']
						);

				}

			}

            //echo "ORDER ".$thisOrder['id']."<hr><pre>".print_r($thisOrderTotals,1)."</pre><hr>";
			
			if(count($thisOrderThisBatchItems) >0){
				//perform the update/insert
		
				//let's see if a cache entry already exists for this date
				$cacheLookup=$this->SherryCache->find('all',['conditions' => ['order_id' => $thisOrder['id'],'batch_id' => $batch['id']]])->toArray();

				if(count($cacheLookup) == 1){
					//yes it does, we need to update it
					foreach($cacheLookup as $cacheRow){
						$thisCacheRow=$sherryCacheTable->get($cacheRow['id']);
						$thisCacheRow->date=date('Y-m-d',strtotime($batch['date'].' 08:00:00'));
						$thisCacheRow->order_date = date('Y-m-d',$thisOrder['created']);
						$thisCacheRow->facility = $thisOrder['facility'];
						$thisCacheRow->dollars = $thisOrderTotals['dollars'];
						$thisCacheRow->cc = $thisOrderTotals['cc'];
						$thisCacheRow->cclf = $thisOrderTotals['cclf'];
						$thisCacheRow->bs = $thisOrderTotals['bs'];
						
						$thisCacheRow->val = $thisOrderTotals['val'];
						$thisCacheRow->vallf = $thisOrderTotals['vallf'];
						
						$thisCacheRow->corn = $thisOrderTotals['corn'];
						$thisCacheRow->cornlf = $thisOrderTotals['cornlf'];
						
						$thisCacheRow->drape = $thisOrderTotals['drape'];
						$thisCacheRow->drape_widths = $thisOrderTotals['drape_widths'];
						$thisCacheRow->wthw = $thisOrderTotals['wthw'];
						$thisCacheRow->blinds = $thisOrderTotals['blinds'];
						$thisCacheRow->trklf = $thisOrderTotals['trklf'];

						if($thisShipMethod){
							$thisCacheRow->shipping_method = $thisShipMethod['name'];
						}
						
						if(!is_null($thisOrder['due']) && intval($thisOrder['due']) > 1000 && intval($thisOrder['due']) != intval($cacheRow['order_ship_date'])){
						    $thisCacheRow->order_ship_date=$thisOrder['due'];
						}elseif(intval($thisOrder['due']) == intval($cacheRow['order_ship_date'])){
							//no change
						}else{
						    $thisCacheRow->order_ship_date=0;
						}

						$sherryCacheTable->save($thisCacheRow);
					}
				}else{
					//no it doesnt, we need to create it
					$newCacheRow=$sherryCacheTable->newEntity();
					$newCacheRow->date = $date;
					$newCacheRow->order_date = date('Y-m-d',$thisOrder['created']);
					$newCacheRow->batch_id = $batch['id'];
					$newCacheRow->order_id = $thisOrder['id'];
					$newCacheRow->order_number = $thisOrder['order_number'];
					$newCacheRow->quote_id = $thisOrder['quote_id'];
					$newCacheRow->quote_number = $thisQuote['quote_number'];
					$newCacheRow->customer_id = $thisOrder['customer_id'];
					$newCacheRow->company_name = $thisCustomer['company_name'];
					$newCacheRow->customer_po_number = $thisOrder['po_number'];
					
					if(!is_null($thisQuote['type_id'])){
					    $thisQuoteType=$this->QuoteTypes->get($thisQuote['type_id'])->toArray();
					    $newCacheRow->quote_type=$thisQuoteType['type_label'];
					}else{
					    $newCacheRow->quote_type=null;
					}
					
					if(!is_null($thisOrder['type_id'])){
					    $thisOrderType=$this->QuoteTypes->get($thisOrder['type_id'])->toArray();
					    $newCacheRow->order_type=$thisOrderType['type_label'];
					}else{
					    $newCacheRow->order_type=null;
					}
					
					
					$newCacheRow->order_ship_date = $thisOrder['due'];
					$newCacheRow->facility = $thisOrder['facility'];
					$newCacheRow->project = $projectName;					
					
					$newCacheRow->dollars = $thisOrderTotals['dollars'];
					$newCacheRow->cc = $thisOrderTotals['cc'];
					$newCacheRow->cclf = $thisOrderTotals['cclf'];
					$newCacheRow->bs = $thisOrderTotals['bs'];
					//$newCacheRow->wt = $thisOrderTotals['wt'];
					//$newCacheRow->wtlf = $thisOrderTotals['wtlf'];
					
					$newCacheRow->val = $thisOrderTotals['val'];
					$newCacheRow->vallf = $thisOrderTotals['vallf'];
					
					$newCacheRow->corn = $thisOrderTotals['corn'];
					$newCacheRow->cornlf = $thisOrderTotals['cornlf'];
					
					$newCacheRow->drape = $thisOrderTotals['drape'];
					$newCacheRow->drape_widths = $thisOrderTotals['drape_widths'];
					$newCacheRow->wthw = $thisOrderTotals['wthw'];
					$newCacheRow->blinds = $thisOrderTotals['blinds'];
					$newCacheRow->trklf = $thisOrderTotals['trklf'];

					if($thisShipMethod){
						$newCacheRow->shipping_method = $thisShipMethod['name'];
					}

					$sherryCacheTable->save($newCacheRow);
				}
				
			}else{
				//let's delete this batch entry in sherry schedule cache, it has no items anymore
				$cacheLookup=$this->SherryCache->find('all',['conditions' => ['batch_id' => $batch['id']]])->toArray();
				foreach($cacheLookup as $cacheRow){
					$thisCacheRow=$sherryCacheTable->get($cacheRow['id']);
					$sherryCacheTable->delete($thisCacheRow);
				}
			}



    	}

    }
	
	
	
	
	
	public function logactivity($location='',$memo='',$addedby=0,$rawdata=''){
		$activityLogsTable = TableRegistry::get('ActivityLogs');
		$newlog = $activityLogsTable->newEntity();
	
		if($addedby != 0){
			$userid=$addedby;
		}else{
			$userid=$this->Auth->user('id');
		}
	
		$newlog->ip_address=$_SERVER['REMOTE_ADDR'];
		$newlog->user_agent=$_SERVER['HTTP_USER_AGENT'];
		$newlog->time=time();
		$newlog->user_id=$userid;
		$newlog->location=$location;
		$newlog->action_label=$memo;
        
		
		if($rawdata==''){
    		$rawdata=array();
    		foreach($_REQUEST as $key => $val){
    			switch($key){
    				case "ssn_1":
    				case "new_ssn_1":
    				case "dob_1":
    				case "new_dob_1":
    				case "drivers_license_1":
    				case "new_drivers_license_1":
    				case "ssn_2":
    				case "new_ssn_2":
    				case "dob_2":
    				case "new_dob_2":
    				case "drivers_license_2":
    				case "new_drivers_license_2":
    				case "taxid":
    				case "new_taxid":
    				case "provider_account_number":
    				case "security_answer":
    				case "provider_password":
    				case "cardnum":
    				case "cc_expire_month":
    				case "cc_expire_year":
    				case "cc_cvv_code":
    				case "ssn":
    				case "dlnumber":
    					//ignore
    				break;
    				default:
    					$rawdata[$key]=$val;
    				break;
    			}
    		}
    		
    		$newlog->data=json_encode($rawdata);
		}else{
		    $newlog->data = $rawdata;
		}
		
		$newlog->session_id=$this->request->session()->id();
		
		if($activityLogsTable->save($newlog)){
			return true;
		}
	}
	

	public function parse_user_agent( $u_agent = null ) {
		if( is_null($u_agent) ) {
			if( isset($_SERVER['HTTP_USER_AGENT']) ) {
				$u_agent = $_SERVER['HTTP_USER_AGENT'];
			} else {
				throw new \InvalidArgumentException('parse_user_agent requires a user agent');
			}
		}
	
		$platform = null;
		$browser  = null;
		$version  = null;
	
		$empty = array( 'platform' => $platform, 'browser' => $browser, 'version' => $version );
	
		if( !$u_agent ) return $empty;
	
		if( preg_match('/\((.*?)\)/im', $u_agent, $parent_matches) ) {
	
			preg_match_all('/(?P<platform>BB\d+;|Android|CrOS|Tizen|iPhone|iPad|iPod|Linux|Macintosh|Windows(\ Phone)?|Silk|linux-gnu|BlackBerry|PlayBook|(New\ )?Nintendo\ (WiiU?|3?DS)|Xbox(\ One)?)
					(?:\ [^;]*)?
					(?:;|$)/imx', $parent_matches[1], $result, PREG_PATTERN_ORDER);
	
			$priority           = array( 'Xbox One', 'Xbox', 'Windows Phone', 'Tizen', 'Android' );
			$result['platform'] = array_unique($result['platform']);
			if( count($result['platform']) > 1 ) {
				if( $keys = array_intersect($priority, $result['platform']) ) {
					$platform = reset($keys);
				} else {
					$platform = $result['platform'][0];
				}
			} elseif( isset($result['platform'][0]) ) {
				$platform = $result['platform'][0];
			}
		}
	
		if( $platform == 'linux-gnu' ) {
			$platform = 'Linux';
		} elseif( $platform == 'CrOS' ) {
			$platform = 'Chrome OS';
		}
	
		preg_match_all('%(?P<browser>Camino|Kindle(\ Fire)?|Firefox|Iceweasel|Safari|MSIE|Trident|AppleWebKit|TizenBrowser|Chrome|
				Vivaldi|IEMobile|Opera|OPR|Silk|Midori|Edge|CriOS|
				Baiduspider|Googlebot|YandexBot|bingbot|Lynx|Version|Wget|curl|
				NintendoBrowser|PLAYSTATION\ (\d|Vita)+)
				(?:\)?;?)
				(?:(?:[:/ ])(?P<version>[0-9A-Z.]+)|/(?:[A-Z]*))%ix',
			$u_agent, $result, PREG_PATTERN_ORDER);
	
		// If nothing matched, return null (to avoid undefined index errors)
		if( !isset($result['browser'][0]) || !isset($result['version'][0]) ) {
			if( preg_match('%^(?!Mozilla)(?P<browser>[A-Z0-9\-]+)(/(?P<version>[0-9A-Z.]+))?%ix', $u_agent, $result) ) {
				return array( 'platform' => $platform ?: null, 'browser' => $result['browser'], 'version' => isset($result['version']) ? $result['version'] ?: null : null );
			}
	
			return $empty;
		}
	
		if( preg_match('/rv:(?P<version>[0-9A-Z.]+)/si', $u_agent, $rv_result) ) {
			$rv_result = $rv_result['version'];
		}
	
		$browser = $result['browser'][0];
		$version = $result['version'][0];
	
		$lowerBrowser = array_map('strtolower', $result['browser']);
	
		$find = function ( $search, &$key ) use ( $lowerBrowser ) {
			$xkey = array_search(strtolower($search), $lowerBrowser);
			if( $xkey !== false ) {
				$key = $xkey;
	
				return true;
			}
	
			return false;
		};
	
		$key  = 0;
		$ekey = 0;
		if( $browser == 'Iceweasel' ) {
			$browser = 'Firefox';
		} elseif( $find('Playstation Vita', $key) ) {
			$platform = 'PlayStation Vita';
			$browser  = 'Browser';
		} elseif( $find('Kindle Fire', $key) || $find('Silk', $key) ) {
			$browser  = $result['browser'][$key] == 'Silk' ? 'Silk' : 'Kindle';
			$platform = 'Kindle Fire';
			if( !($version = $result['version'][$key]) || !is_numeric($version[0]) ) {
				$version = $result['version'][array_search('Version', $result['browser'])];
			}
		} elseif( $find('NintendoBrowser', $key) || $platform == 'Nintendo 3DS' ) {
			$browser = 'NintendoBrowser';
			$version = $result['version'][$key];
		} elseif( $find('Kindle', $key) ) {
			$browser  = $result['browser'][$key];
			$platform = 'Kindle';
			$version  = $result['version'][$key];
		} elseif( $find('OPR', $key) ) {
			$browser = 'Opera Next';
			$version = $result['version'][$key];
		} elseif( $find('Opera', $key) ) {
			$browser = 'Opera';
			$find('Version', $key);
			$version = $result['version'][$key];
		} elseif( $find('Midori', $key) ) {
			$browser = 'Midori';
			$version = $result['version'][$key];
		} elseif( $browser == 'MSIE' || ($rv_result && $find('Trident', $key)) || $find('Edge', $ekey) ) {
			$browser = 'MSIE';
			if( $find('IEMobile', $key) ) {
				$browser = 'IEMobile';
				$version = $result['version'][$key];
			} elseif( $ekey ) {
				$version = $result['version'][$ekey];
			} else {
				$version = $rv_result ?: $result['version'][$key];
			}
	
			if( version_compare($version, '12', '>=') ) {
				$browser = 'Edge';
			}
		} elseif( $find('Vivaldi', $key) ) {
			$browser = 'Vivaldi';
			$version = $result['version'][$key];
		} elseif( $find('Chrome', $key) || $find('CriOS', $key) ) {
			$browser = 'Chrome';
			$version = $result['version'][$key];
		} elseif( $browser == 'AppleWebKit' ) {
			if( ($platform == 'Android' && !($key = 0)) ) {
				$browser = 'Android Browser';
			} elseif( strpos($platform, 'BB') === 0 ) {
				$browser  = 'BlackBerry Browser';
				$platform = 'BlackBerry';
			} elseif( $platform == 'BlackBerry' || $platform == 'PlayBook' ) {
				$browser = 'BlackBerry Browser';
			} elseif( $find('Safari', $key) ) {
				$browser = 'Safari';
			} elseif( $find('TizenBrowser', $key) ) {
				$browser = 'TizenBrowser';
			}
	
			$find('Version', $key);
	
			$version = $result['version'][$key];
		} elseif( $key = preg_grep('/playstation \d/i', array_map('strtolower', $result['browser'])) ) {
			$key = reset($key);
	
			$platform = 'PlayStation ' . preg_replace('/[^\d]/i', '', $key);
			$browser  = 'NetFront';
		}
	
		return array( 'platform' => $platform ?: null, 'browser' => $browser ?: null, 'version' => $version ?: null );
	}	
	

    public function getSettingValue($key){
        $settingLookup=$this->Settings->find('all',['conditions'=>['setting_key' => $key]])->toArray();
        if(count($settingLookup) == 1){
            foreach($settingLookup as $settingRow){
                return $settingRow['setting_value'];
            }
        }else{
            return false;
        }
    }
	
	public function getsettingsarray(){
		$resultarr=array();
		$allSettings=$this->Settings->find('all')->toArray();
		foreach($allSettings as $setting){
			$resultarr[$setting['setting_key']]=$setting['setting_value'];
		}
		return $resultarr;
	}



	public function getTypeQty($quoteID,$type){
		$total=0;
		$conditions=array();

		switch($type){
			case "catchall":
				$total=0;
			break;
			case "swtmisc":
			    $catchalls=$this->QuoteLineItems->find('all',['conditions' => ['quote_id' => $quoteID, 'product_subclass' => 12]])->toArray();
			
			    foreach($catchalls as $catchallRow){
				    $total=($total+floatval($catchallRow['qty']));
			    }
			break;
			case "cc":
				//look up all Price List  and  Calculated  cubicle and shower curtains and sum their qty's for output here
				$conditions += array('quote_id' => $quoteID);
				$conditions['OR'] = array(
					'product_type IN' => array('cubicle_curtains','newcatchall-cubicle'),
					'AND' => 
						array(
							'product_type' => 'calculator',
							'calculator_used' => 'cubicle-curtain'
						)
				);

				$lineItemLookup=$this->QuoteLineItems->find('all',['conditions' => $conditions])->toArray();
				foreach($lineItemLookup as $item){
					$total=($total+floatval($item['qty']));
				}
			break;
			case "bs":
				//look up all Price List  and  calculated Bedspreads and sum their qty's for output here
				$conditions += array('quote_id' => $quoteID);
				$conditions['OR'] = array(
					'product_type IN' => array('bedspreads','newcatchall-bedding'),
					'calculator_used' => 'bedspread'
				);

				$lineItemLookup=$this->QuoteLineItems->find('all',['conditions' => $conditions])->toArray();
				foreach($lineItemLookup as $item){
					$total=($total+floatval($item['qty']));
				}
			break;
			case "drapes":
				//look up all Price List  and  Calculated  draperies and sum their qty's for output here
				$conditions += array('quote_id' => $quoteID);
				$conditions['OR'] = array(
					'product_type IN' => array('draperies','newcatchall-drapery'),
					'calculator_used IN' => array('pinch-pleated','accordion-fold','ripple-fold','grommet-top','rod-pocket')
				);

				$lineItemLookup=$this->QuoteLineItems->find('all',['conditions' => $conditions])->toArray();
				foreach($lineItemLookup as $item){
					$total=($total+floatval($item['qty']));
				}
			break;
			case "val":
				$conditions += array('quote_id' => $quoteID);
				$conditions['OR'] = array(
					'product_type IN' => array('newcatchall-valance'),
					'calculator_used IN' => array('box-pleated','rod-pocket-valance')
				);
				$lineItemLookup=$this->QuoteLineItems->find('all',['conditions' => $conditions])->toArray();
				foreach($lineItemLookup as $item){
					$total=($total+floatval($item['qty']));
				}
			break;
			case "corn":
				$conditions += array('quote_id' => $quoteID);
				$conditions['OR'] = array(
					'product_type' => 'newcatchall-cornice',
					'calculator_used' => 'straight-cornice'
				);
				$lineItemLookup=$this->QuoteLineItems->find('all',['conditions' => $conditions])->toArray();
				foreach($lineItemLookup as $item){
					$total=($total+floatval($item['qty']));
				}
			break;
			case "tt":
				//lookup all Price list and Calculated "Top Treatments" and sum their qty's for output here
				$conditions += array('quote_id' => $quoteID);
				$conditions['OR'] = array(
					'product_type IN' => array('window_treatments','newcatchall-valance','newcatchall-cornice','newcatchall-swtmisc'),
					'calculator_used IN' => array('box-pleated','straight-cornice','rod-pocket-valance','swags-and-cascades')
				);
				$lineItemLookup=$this->QuoteLineItems->find('all',['conditions' => $conditions])->toArray();
				foreach($lineItemLookup as $item){
					$total=($total+floatval($item['qty']));
				}
			break;
			case "blinds":
				//loop through all the catch-all lines and find any with the BLINDS metadata,  include in the $conditions
				//$catchalls=$this->QuoteLineItems->find('all',['conditions' => ['quote_id' => $quoteID, 'product_type' => 'custom' ]])->toArray();
				$catchalls=$this->QuoteLineItems->find('all',['conditions' => ['quote_id' => $quoteID, 'OR' => [
				        'product_type'=>'custom',
				        'product_subclass IN' => [5,6,7,8]
				    ] ]])->toArray();
				    
				foreach($catchalls as $catchallRow){
					//determine if this catch-all is indeed a BLINDS entry
					if(($catchallRow['product_subclass'] == 5 || $catchallRow['product_subclass'] == 6 || $catchallRow['product_subclass'] == 7 || $catchallRow['product_subclass'] == 8) && $catchallRow['product_type'] != 'custom'){
					    $total = ($total + floatval($catchallRow['qty']));
					}else{
					    $metaCheck=$this->QuoteLineItemMeta->find('all',['conditions' => ['quote_item_id' => $catchallRow['id'], 'meta_key' => 'catchallcategory']])->toArray();
					    foreach($metaCheck as $metaCheckRow){
						    if($metaCheckRow['meta_value'] == 'Blinds & Shades'){
							    $total = ($total + floatval($catchallRow['qty']));
						    }
					    }
					}
				}
				
			break;
			
			
			
			case "wthw":
				//loop through all the catch-all lines and find any with the BLINDS metadata,  include in the $conditions
				//$catchalls=$this->QuoteLineItems->find('all',['conditions' => ['quote_id' => $quoteID, 'product_type IN' => ['custom','newcatchall-hardware'] ]])->toArray();
				
				$catchalls=$this->QuoteLineItems->find('all',['conditions' => ['quote_id' => $quoteID, 'OR' => [
				        'product_type'=>'custom',
				        'product_subclass IN' => [1,2,4]
				    ] ]])->toArray();
				
				
				foreach($catchalls as $catchallRow){
					//determine if this catch-all is indeed a BLINDS entry
					if($catchallRow['product_type'] == 'custom'){
    					$metaCheck=$this->QuoteLineItemMeta->find('all',['conditions' => ['quote_item_id' => $catchallRow['id'], 'meta_key' => 'catchallcategory']])->toArray();
    					foreach($metaCheck as $metaCheckRow){
    						if($metaCheckRow['meta_value'] == 'WT Hardware'){
    							$total = ($total + floatval($catchallRow['qty']));
    						}
    					}
					}else{
					    $total = ($total + floatval($catchallRow['qty']));
					}
				}
			break;

		}
		return $total;
	}

	public function getTypeLF($quoteID,$type){
		$total=0;
		$conditions=array();

		switch($type){
			case "cc":
				//look up all Price List  and  Calculated  cubicle and shower curtains and sum their LF totals for output here
				$conditions += array('quote_id' => $quoteID);
				$conditions['OR'] = array(
					'product_type IN' => ['cubicle_curtains','newcatchall-cubicle'],
					'AND' => 
						array(
							'product_type' => 'calculator',
							'calculator_used' => 'cubicle-curtain'
						)
				);

				$lineItemLookup=$this->QuoteLineItems->find('all',['conditions' => $conditions])->toArray();
				foreach($lineItemLookup as $item){
					$lfLookup=$this->QuoteLineItemMeta->find('all',['conditions'=>['quote_item_id' => $item['id'], 'meta_key' => 'labor-billable']])->toArray();
					foreach($lfLookup as $lf){
						$total=($total+(floatval($lf['meta_value']) * floatval($item['qty'])));
					}
				}
			break;
			case "bs":
				//look up all Price List  and  Calculated  cubicle and shower curtains and sum their LF totals for output here
				$conditions += array('quote_id' => $quoteID);
				$conditions['OR'] = array(
					'product_type IN' => ['bedspreads','newcatchall-bedding'],
					'AND' => 
						array(
							'product_type' => 'calculator',
							'calculator_used' => 'bedspread'
						)
				);

				$lineItemLookup=$this->QuoteLineItems->find('all',['conditions' => $conditions])->toArray();
				foreach($lineItemLookup as $item){
					$lfLookup=$this->QuoteLineItemMeta->find('all',['conditions'=>['quote_item_id' => $item['id'], 'meta_key' => 'labor-billable']])->toArray();
					foreach($lfLookup as $lf){
						$total=($total+(floatval($lf['meta_value']) * floatval($item['qty'])));
					}
				}
			break;
			case "val":
			    $conditions += array('quote_id' => $quoteID);
				$conditions['OR'] = array(
					'product_type IN' => array('valances','newcatchall-valance'),
					'calculator_used IN' => array('box-pleated','rod-pocket-valance')
				);

				$lineItemLookup=$this->QuoteLineItems->find('all',['conditions' => $conditions])->toArray();
				foreach($lineItemLookup as $item){
					$lfLookup=$this->QuoteLineItemMeta->find('all',['conditions'=>['quote_item_id' => $item['id'], 'meta_key' => 'labor-billable']])->toArray();
					foreach($lfLookup as $lf){
						$total=($total+(floatval($lf['meta_value']) * floatval($item['qty'])));
					}
				}
			break;
			case "corn":
			    $conditions += array('quote_id' => $quoteID);
				$conditions['OR'] = array(
					'product_type IN' => array('cornices','newcatchall-cornice'),
					'calculator_used' => 'straight-cornice'
				);

				$lineItemLookup=$this->QuoteLineItems->find('all',['conditions' => $conditions])->toArray();
				foreach($lineItemLookup as $item){
					$lfLookup=$this->QuoteLineItemMeta->find('all',['conditions'=>['quote_item_id' => $item['id'], 'meta_key' => 'labor-billable']])->toArray();
					foreach($lfLookup as $lf){
						$total=($total+(floatval($lf['meta_value']) * floatval($item['qty'])));
					}
				}
			break;
			case "tt":
				//look up all Price List  and  Calculated  "top treatments" and sum their LF totals for output here
				$conditions += array('quote_id' => $quoteID);
				$conditions['OR'] = array(
					'product_type IN' => array('valances','cornices','newcatchall-valance','newcatchall-cornice','newcatchall-swtmisc'),
					'calculator_used IN' => array('box-pleated','straight-cornice','rod-pocket-valance','swags-and-cascades')
				);

				$lineItemLookup=$this->QuoteLineItems->find('all',['conditions' => $conditions])->toArray();
				foreach($lineItemLookup as $item){
					$lfLookup=$this->QuoteLineItemMeta->find('all',['conditions'=>['quote_item_id' => $item['id'], 'meta_key' => 'labor-billable']])->toArray();
					foreach($lfLookup as $lf){
						$total=($total+(floatval($lf['meta_value']) * floatval($item['qty'])));
					}
				}
			break;
			case "track":
				$conditions += array('quote_id' => $quoteID);
				$conditions['OR'] = array('product_type' => 'track_systems','product_subclass' => 3);

				$lineItemLookup=$this->QuoteLineItems->find('all',['conditions' => $conditions])->toArray();
				foreach($lineItemLookup as $item){
					$total=($total+floatval($item['qty']));
				}
			break;
		}
		return $total;
	}

	public function getTypeDiff($quoteID,$type){
		$total=0;
		$conditions=array();

		switch($type){
			case "cc":
				//look up all Price List  and  Calculated  cubicle and shower curtains and sum their difficulty totals for output here
				$conditions += array('quote_id' => $quoteID);
				$conditions['OR'] = array(
					'product_type' => 'cubicle_curtains',
					'AND' => 
						array(
							'product_type' => 'calculator',
							'calculator_used' => 'cubicle-curtain'
						)
				);

				$lineItemLookup=$this->QuoteLineItems->find('all',['conditions' => $conditions])->toArray();
				foreach($lineItemLookup as $item){
					$difficultyLookup=$this->QuoteLineItemMeta->find('all',['conditions'=>['quote_item_id' => $item['id'], 'meta_key' => 'difficulty-rating']])->toArray();
					foreach($difficultyLookup as $diff){
						$total=($total+floatval($diff['meta_value']));
					}
				}
			break;
			case "bs":
				//look up all Price List  and  Calculated bedspread and sum their difficulty totals for output here
				$conditions += array('quote_id' => $quoteID);
				$conditions['OR'] = array(
					'product_type' => 'bedspreads',
					'AND' => 
						array(
							'product_type' => 'calculator',
							'calculator_used' => 'bedspread'
						)
				);

				$lineItemLookup=$this->QuoteLineItems->find('all',['conditions' => $conditions])->toArray();
				foreach($lineItemLookup as $item){
					$difficultyLookup=$this->QuoteLineItemMeta->find('all',['conditions'=>['quote_item_id' => $item['id'], 'meta_key' => 'difficulty-rating']])->toArray();
					foreach($difficultyLookup as $diff){
						$total=($total+floatval($diff['meta_value']));
					}
				}
			break;
			case "tt":
				//look up all Price List  and  Calculated bedspread and sum their difficulty totals for output here
				$conditions += array('quote_id' => $quoteID);
				$conditions['OR'] = array(
					'product_type' => 'window_treatments',
					'calculator_used IN' => array('box-pleated','straight-cornice','rod-pocket-valance','swags-and-cascades')
				);

				$lineItemLookup=$this->QuoteLineItems->find('all',['conditions' => $conditions])->toArray();
				foreach($lineItemLookup as $item){
					$difficultyLookup=$this->QuoteLineItemMeta->find('all',['conditions'=>['quote_item_id' => $item['id'], 'meta_key' => 'difficulty-rating']])->toArray();
					foreach($difficultyLookup as $diff){
						$total=($total+floatval($diff['meta_value']));
					}
				}
			break;
		}
		return $total;
	}


	public function getTypeDollars($quoteID,$type){
		$total=0;
		$conditions=array();

		switch($type){
			case "cc":
				//look up all Price List  and  Calculated  cubicle and shower curtains and sum their dollar totals for output here
				$conditions += array('quote_id' => $quoteID);
				$conditions['OR'] = array(
					'product_type IN' => array('cubicle_curtains','newcatchall-cubicle'),
					'AND' => 
						array(
							'product_type' => 'calculator',
							'calculator_used' => 'cubicle-curtain'
						)
				);

				$lineItemLookup=$this->QuoteLineItems->find('all',['conditions' => $conditions])->toArray();
				foreach($lineItemLookup as $item){
					$total=($total+floatval($item['extended_price']));
				}
			break;
			case "bs":
				//look up all Price List  and  Calculated  bedspreads and sum their dollar totals for output here
				$conditions += array('quote_id' => $quoteID);
				$conditions['OR'] = array(
					'product_type IN' => array('bedspreads','newcatchall-bedding'),
					'AND' => 
						array(
							'product_type' => 'calculator',
							'calculator_used' => 'bedspread'
						)
				);

				$lineItemLookup=$this->QuoteLineItems->find('all',['conditions' => $conditions])->toArray();
				foreach($lineItemLookup as $item){
					$total=($total+floatval($item['extended_price']));
				}
			break;
			case "drapes":
				//look up all Price List  and  Calculated  bedspreads and sum their dollar totals for output here
				$conditions += array('quote_id' => $quoteID);
				$conditions['OR'] = array(
					'product_type IN' => array('draperies','newcatchall-drapery'),
					'calculator_used IN' => array('pinch-pleated','accordion-fold','ripple-fold','grommet-top','rod-pocket')
				);

				$lineItemLookup=$this->QuoteLineItems->find('all',['conditions' => $conditions])->toArray();
				foreach($lineItemLookup as $item){
					$total=($total+floatval($item['extended_price']));
				}
			break;
			case "corn":
				//look up all Price List  and  Calculated  Cornices and sum their dollar totals for output here
				$conditions += array('quote_id' => $quoteID);
				$conditions['OR'] = array(
					'product_type IN' => array('cornices','newcatchall-cornice'),
					'calculator_used' => 'straight-cornice'
				);

				$lineItemLookup=$this->QuoteLineItems->find('all',['conditions' => $conditions])->toArray();
				foreach($lineItemLookup as $item){
					$total=($total+floatval($item['extended_price']));
				}
			break;
			case "val":
				//look up all Price List  and  Calculated  Valances and sum their dollar totals for output here
				$conditions += array('quote_id' => $quoteID);
				$conditions['OR'] = array(
					'product_type IN' => array('valances','newcatchall-valance'),
					'calculator_used IN' => array('box-pleated','rod-pocket-valance','swags-and-cascades')
				);

				$lineItemLookup=$this->QuoteLineItems->find('all',['conditions' => $conditions])->toArray();
				foreach($lineItemLookup as $item){
					$total=($total+floatval($item['extended_price']));
				}
			break;
			case "tt":
				
			break;
			case "track":
				$conditions += array('quote_id' => $quoteID);
				$conditions['OR'] = array('product_type' => 'track_systems', 'product_subclass' => 3);

				$lineItemLookup=$this->QuoteLineItems->find('all',['conditions' => $conditions])->toArray();
				foreach($lineItemLookup as $item){
					$total=($total+floatval($item['extended_price']));
				}
			break;
			case "blinds":
				//loop through all the catch-all lines and find any with the BLINDS metadata,  include in the $conditions
				//$catchalls=$this->QuoteLineItems->find('all',['conditions' => ['quote_id' => $quoteID, 'product_type' => 'custom']])->toArray();
				
				$catchalls=$this->QuoteLineItems->find('all',['conditions' => ['quote_id' => $quoteID, 'OR' => [
				        'product_type'=>'custom',
				        'product_subclass IN' => [5,6,7,8]
				    ] ]])->toArray();
				
				foreach($catchalls as $catchallRow){
					//determine if this catch-all is indeed a BLINDS entry
					if(($catchallRow['product_subclass'] == 5 || $catchallRow['product_subclass'] == 6 || $catchallRow['product_subclass'] == 7 || $catchallRow['product_subclass'] == 8) && $catchallRow['product_type'] != 'custom'){
					    $total = ($total + floatval($catchallRow['extended_price']));
					}else{
					    $metaCheck=$this->QuoteLineItemMeta->find('all',['conditions' => ['quote_item_id' => $catchallRow['id'], 'meta_key' => 'catchallcategory']])->toArray();
					    foreach($metaCheck as $metaCheckRow){
    						if($metaCheckRow['meta_value'] == 'Blinds & Shades'){
    							$total = ($total + floatval($catchallRow['extended_price']));
    						}
    					}
					}
					
				}
				
			break;
			case "wthw":
				//loop through all the catch-all lines and find any with the BLINDS metadata,  include in the $conditions
				
				//$catchalls=$this->QuoteLineItems->find('all',['conditions' => ['quote_id' => $quoteID, 'product_type' => 'custom']])->toArray();
				
				$catchalls=$this->QuoteLineItems->find('all',['conditions' => ['quote_id' => $quoteID, 'OR' => [
				        'product_type'=>'custom',
				        'product_subclass IN' => [1,2,4]
				    ] ]])->toArray();
				
				foreach($catchalls as $catchallRow){
					//determine if this catch-all is indeed a BLINDS entry
					if(($catchallRow['product_subclass'] == 1 || $catchallRow['product_subclass'] == 2 || $catchallRow['product_subclass'] == 4) && $catchallRow['product_type'] != 'custom'){
					    
					    $total = ($total + floatval($catchallRow['extended_price']));
					    
					}else{
					    $metaCheck=$this->QuoteLineItemMeta->find('all',['conditions' => ['quote_item_id' => $catchallRow['id'], 'meta_key' => 'catchallcategory']])->toArray();
					    foreach($metaCheck as $metaCheckRow){
    						if($metaCheckRow['meta_value'] == 'WT Hardware'){
    							$total = ($total + floatval($catchallRow['extended_price']));
    						}
    					}
					}
					
				}
			break;
			case "swtmisc":
			    
			    $catchalls=$this->QuoteLineItems->find('all',['conditions' => ['quote_id' => $quoteID, 'product_subclass' => 12]])->toArray();
				
				foreach($catchalls as $catchallRow){
					$total = ($total + floatval($catchallRow['extended_price']));
				}
			    
			break;
			case "misc":
			    
			    $catchalls=$this->QuoteLineItems->find('all',['conditions' => ['quote_id' => $quoteID, 'product_subclass IN' => [24,28,29] ]])->toArray();
				
				foreach($catchalls as $catchallRow){
					$total = ($total + floatval($catchallRow['extended_price']));
				}
			    
			break;
			case "measure":
			    $catchalls=$this->QuoteLineItems->find('all',['conditions' => ['quote_id' => $quoteID, 'OR' => [
				        'product_type'=>'services',
				        'product_subclass' => 22
				    ] ]])->toArray();
				
				foreach($catchalls as $catchallRow){
					//determine if this catch-all is indeed a BLINDS entry
					if($catchallRow['product_subclass'] == 22 && $catchallRow['product_type'] != 'services'){
					    
					    $total = ($total + floatval($catchallRow['extended_price']));
					    
					}else{
					    if($catchallRow['title'] == 'Measurements'){
    						$total = ($total + floatval($catchallRow['extended_price']));
    					}
					}
					
				}
			break;
			case "install":
			    $catchalls=$this->QuoteLineItems->find('all',['conditions' => ['quote_id' => $quoteID, 'OR' => [
				        'product_type'=>'services',
				        'product_subclass' => 21
				    ] ]])->toArray();
				
				foreach($catchalls as $catchallRow){
					//determine if this catch-all is indeed a BLINDS entry
					if($catchallRow['product_subclass'] == 21 && $catchallRow['product_type'] != 'services'){
					    
					    $total = ($total + floatval($catchallRow['extended_price']));
					    
					}else{
					    if($catchallRow['title'] == 'Installation'){
    						$total = ($total + floatval($catchallRow['extended_price']));
    					}
					}
					
				}
			break;
		}
		return $total;
	}

	public function getTypeWidths($quoteID,$type){
		$total=0;
		$conditions=array();

		switch($type){
			case "drapes":
				//look up all Price List  and  Calculated  draperies and sum their Width totals for output here
				$conditions += array('quote_id' => $quoteID);
				$conditions['OR'] = array(
					'product_type IN' => ['draperies','newcatchall-drapery'],
					'calculator_used IN' => array('pinch-pleated','pinch-pleated-new','accordion-fold','ripple-fold','grommet-top','rod-pocket')
				);

				$lineItemLookup=$this->QuoteLineItems->find('all',['conditions' => $conditions])->toArray();
				foreach($lineItemLookup as $item){
				    if($item['product_type'] == 'newcatchall-drapery'){
					    $widthsLookup=$this->QuoteLineItemMeta->find('all',['conditions'=>['quote_item_id' => $item['id'], 'meta_key' => 'labor-billable-widths']])->toArray();
				    }else{
				        $widthsLookup=$this->QuoteLineItemMeta->find('all',['conditions'=>['quote_item_id' => $item['id'], 'meta_key' => 'labor-widths']])->toArray();
				    }
					foreach($widthsLookup as $widths){
						$total=($total+(floatval($widths['meta_value']) * floatval($item['qty'])));
					}
				}
			break;
		}
		return $total;
	}
		

	public function getNextAvailableOrderNumber(){
		$latestOrder=$this->Orders->find('all',['order'=>['order_number'=>'DESC']])->limit(1)->toArray();
		if(count($latestOrder) == 0){
			return 80000;
		}else{
			foreach($latestOrder as $order){
				return (floatval($order['order_number'])+1);
			}
		}
	}
	
	public function getnewquotenumber(){
		$latest=$this->Quotes->find('all',['order'=>['quote_number'=>'desc']])->limit(1)->toArray();
		if(count($latest)==0){
			return 1000000;
		}
		foreach($latest as $latestrow){
			return (floatval($latestrow['quote_number'])+1);
		}
	}


	public function customerHasPriceListOverride($customerID,$productType,$productID,$sizeID=false,$color=false){
		$conditions=array(
				'customer_id'=>$customerID,
				'product_type'=>$productType,
				'product_id' => $productID
			);
		
		if($sizeID){
			$conditions += array('size_id'=>$sizeID);
		}
		
		if($color){
			$conditions += array("included_colors LIKE" => '%"'.str_replace(" ","_",$color).'"%');
		}
		
		//print_r($conditions);exit;
		$overrideLookup=$this->CustomerPricingExceptions->find('all',['conditions'=>$conditions])->toArray();
		if(count($overrideLookup) == 0){
			return false;
		}else{
			foreach($overrideLookup as $override){
				return $override['price'];
			}
		}
	}
	
	
	public function quoteHasOrder($quoteID){
		//query to see if an order exists with this quote id
		$orderLookup=$this->Orders->find('all',['conditions' => ['quote_id' => $quoteID]])->toArray();
		if(count($orderLookup) == 0){
			return false;
		}else{
			foreach($orderLookup as $order){
				return $order['id'];
			}
		}
	}
	
	
	
	public function isLatestRevision($quoteID){
		//let's check if this parent quote has newer revisions than this quote id
		$thisQuote=$this->Quotes->get($quoteID)->toArray();
		/*
		if($thisQuote['parent_quote'] == 0){
			//this is the original/parent quote
			$childrenLookup=$this->Quotes->find('all',['conditions' => ['parent_quote' => $quoteID]])->toArray();
			if(count($childrenLookup) == 0){
				//no children, this is the latest revision
				return true;
			}
		}
		*/
		$revisionLookup=$this->Quotes->find('all',['conditions' => ['quote_number' => $thisQuote['quote_number']],'order'=>['revision'=>'desc']])->limit(1)->toArray();
		foreach($revisionLookup as $revision){
			if($revision['id'] != $quoteID){
				return false;
			}else{
				return true;
			}
		}
		
	}
	
	
	
	public function fabricyardsonhand($fabricID=0,$momcom='MOM'){
		//calculate the actual yards on hand
		$yards=0;
		
		//find all purchase orders of this fabric
		/*
		$fabricPOs=$this->MaterialPurchases->find('all',['conditions' => ['material_type'=>'Fabrics', 'material_id' => $fabricID,'actual_arrival >' => 0]])->toArray();
		foreach($fabricPOs as $po){
			$yards = ($yards + floatval($po['amount_ordered']));
		}
		*/

		
		//now subtract all usages of this material
		
		
		return $yards;
	}


	public function getfabricyardsonroll($rollID){
		
		$inventory=$this->MaterialInventory->get($rollID)->toArray();
		$yards=floatval($inventory['yards_received']);

		$usages=$this->MaterialUsages->find('all',['conditions' => ['roll_id' => $rollID]])->toArray();
		foreach($usages as $usage){
			$yards = ($yards - floatval($usage['yards_used']));
		}
		
		return $yards;
	}



	public function getfabriccommittedyards($fabricID,$quilted=0){

		$thisFabric=$this->Fabrics->get($fabricID)->toArray();

		$totalQuiltedYards=0;
		$totalUnquiltedYards=0;


		$materialOrders=$this->Orders->find('all',['conditions'=>['status IN' => ['Needs Line Items','Pre-Production']]])->toArray();

		foreach($materialOrders as $materialOrder){
			//get all line item data
			$thisQuote=$this->Quotes->get($materialOrder['quote_id'])->toArray();	
			
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
						$totalQuiltedYards=($totalQuiltedYards+(floatval($lineItemMetaArray['yds-per-unit']) * floatval($lineitem['qty']) ));
					}else{
						$totalUnquiltedYards=($totalUnquiltedYards+(floatval($lineItemMetaArray['yds-per-unit']) * floatval($lineitem['qty']) ));
					}
					
				}
				
			}
		}
		

		if($quilted==1){
			return $totalQuiltedYards;
		}else{
			return $totalUnquiltedYards;
		}

	}



	public function getrollcountbyfabric($fabricID){
		return $this->MaterialInventory->find('all',['conditions'=>['material_type'=>'Fabrics','material_id'=>$fabricID,'status'=>'Active']])->count();
	}



	public function getnextrolltagnumber(){
		//find latest
		$latest=0;
		$latestRoll=$this->MaterialInventory->find('all',['order' => ['roll_number' => 'DESC']])->limit(1)->toArray();
		foreach($latestRoll as $roll){
			$latest=$roll['roll_number'];
		}
		return ($latest+1);
	}
	
	
	
	
	public function getbitlyurl($shorten_url){
		//onecallservice
		/*
		$version='2.0.1';
		$login='onecallservice';
		$appkey='R_1c99869f42d248b3a697762cd2431393';
		$format='json';
		
		//create the URL
		$bitly = 'http://api.bit.ly/shorten?version='.$version.'&longUrl='.urlencode($url).'&login='.$login.'&apiKey='.$appkey.'&format='.$format;
	
		//get the url
		//could also use cURL here
		$response = file_get_contents($bitly);
        return $response;
        
		//parse depending on desired format
		if(strtolower($format) == 'json'){
			$json = @json_decode($response,true);
			return $json['results'][$url]['shortUrl'];
		}else{
			$xml = simplexml_load_string($response);
			return 'http://bit.ly/'.$xml->results->nodeKeyVal->hash;
		}
		*/
		
        $group_guid='Bh9lf1vpqCj';
        $shorten_domain='bit.ly';
        
        $access_token='1232e2b0db479370ab21b853bdfe214e6cc684ab';
        
  # setup the JSON payload
  $json_payload = @json_encode(Array(
    "group_guid"=>"".$group_guid."",
    "domain"=>"".$shorten_domain."",
    "long_url"=>"".$shorten_url.""
  ));

  # initialise cURL handle
  $curl_handle = @curl_init();

  # define cURL parameters
  @curl_setopt_array($curl_handle,Array(
    CURLOPT_URL => "https://api-ssl.bitly.com/v4/shorten",
    CURLOPT_RETURNTRANSFER => TRUE,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => TRUE,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "POST",
    CURLOPT_POSTFIELDS => $json_payload,
    CURLOPT_HTTPHEADER => Array (
      "Host: api-ssl.bitly.com",
      "Authorization: Bearer ".$access_token."",
      "Content-Type: application/json",
    ),
  ));

  # execute the cURL request
  $json_output = @curl_exec($curl_handle);

  # decode the output and get the HTTP response code
  $json_decoded = @json_decode($json_output);
  $http_code = "".@curl_getinfo($curl_handle,CURLINFO_HTTP_CODE)."";

  # return results for further processing
  //return(Array("json"=>$json_decoded,"http"=>$http_code));


		return $json_decoded->link;
		
		
	}
	


	public function getQuoteIDFromWONumber($wonumber){
		$quoteID=0;
		$lookupwo=$this->Orders->find('all',['conditions'=>['order_number' => $wonumber]])->toArray();
		foreach($lookupwo as $wo){
			$quoteID=$wo['quote_id'];
		}
		return $quoteID;
	}



	public function getFabricAlias($fabricID,$customerID){
		$findAlias=$this->FabricAliases->find('all',['conditions' => ['fabric_id' => $fabricID, 'customer_id' => $customerID]])->toArray();
		if(count($findAlias) == 0){
			return false;
		}else{
			foreach($findAlias as $alias){
				return $alias;
			}
		}
	}


	public function getproducttypeslist($quoteID){
		$usedTypes=array();
		$items=$this->QuoteLineItems->find('all',['conditions' => ['quote_id' => $quoteID]])->toArray();
		foreach($items as $item){
			switch($item['product_type']){
				case 'track_systems':
					if(!in_array('TRK',$usedTypes)){
						$usedTypes[]='TRK';
					}
				break;
				case 'services':
					if($item['product_id'] == '1'){
						if(!in_array('INST',$usedTypes)){
							$usedTypes[] = 'INST';
						}
					}
					if($item['product_id'] == '2'){
						if(!in_array('MEA',$usedTypes)){
							$usedTypes[] = 'MEA';
						}	
					}
				break;
				case 'cubicle_curtains':
					if(!in_array('CC',$usedTypes)){
						$usedTypes[]='CC';
					}
				break;
				case 'bedspreads':
					if(!in_array('BS',$usedTypes)){
						$usedTypes[]='BS';
					}
				break;
				case 'window_treatments':
					if(!in_array('WT',$usedTypes)){
						$usedTypes[]='WT';
					}
				break;
				case 'calculator':
					switch($item['calculator_used']){
						case 'bedspread':
						case 'bedspread-manual':
							if(!in_array('BS',$usedTypes)){
								$usedTypes[]='BS';
							}
						break;
						case 'cubicle':
						case 'cubicle-manual':
							if(!in_array('CC',$usedTypes)){
								$usedTypes[]='CC';
							}
						break;
						case 'box-pleated':
						case 'straight-cornice':
						case 'pinch-pleated':
							if(!in_array('WT',$usedTypes)){
								$usedTypes[]='WT';
							}
						break;
					}
				break;
				case 'custom':
					switch($item['calculator_used']){
						case "WT Hardware":
							if(!in_array('WTHW',$usedTypes)){
								$usedTypes[]='WTHW';
							}
						break;
						case "Blinds & Shades":
							if(!in_array('Blinds',$usedTypes)){
								$usedTypes[]='Blinds';
							}
						break;
					}	
				break;
			}
		}

		return $usedTypes;
	}


	public function getOrderFabricRequirementNote($quoteID,$fabricID){
		$requirementsLookup=$this->QuoteBomRequirements->find('all',['conditions' => ['quote_id' => $quoteID,'material_type'=>'Fabrics','material_id' => $fabricID]])->toArray();
		foreach($requirementsLookup as $requirement){
			return $requirement['requirement'];
		}
	}

	public function getNewItemSortOrderNumber($quoteID){
		//find highest sortorder number for lines in this quote
		$newsortnum=0;
		$lookup=$this->QuoteLineItems->find('all',['conditions'=>['quote_id'=>$quoteID],'order'=>['sortorder'=>'DESC']])->limit(1)->toArray();
		foreach($lookup as $latestrow){
			$newsortnum=($latestrow['sortorder']+1);
		}
		return $newsortnum;
	}



	public function getfabricsselectlist($product=false,$forceRebuildCache=false){
		$this->autoRender=false;
		$conditions=array();
		switch($product){
			case "bedspread":
			case "bedspread-manual":
				$conditions += array('use_in_bs' => 1);
			break;
			case "cubicle-curtain":
			case "cubicle-curtain-manual":
				$conditions += array('use_in_cc' => 1);
			break;
			case "straight-cornice":
			case "box-pleated":
				$conditions += array('use_in_window' => 1);
			break;
		}

        if(file_exists($_SERVER['DOCUMENT_ROOT'].'/tmp/cache/'.$product.'_fabrics.json') && !$forceRebuildCache){
            return file_get_contents($_SERVER['DOCUMENT_ROOT'].'/tmp/cache/'.$product.'_fabrics.json');
        }else{
            $cacheFile=fopen($_SERVER['DOCUMENT_ROOT'].'/tmp/cache/'.$product.'_fabrics.json','w+');
            
    		$fabricsArray=array();
    		$fabrics=$this->Fabrics->find('all',['conditions'=>$conditions,'order'=>['fabric_name' => 'asc']])->group('fabric_name')->toArray();
    		foreach($fabrics as $fabric){
    			$fabricsArray[$fabric['fabric_name']]=array();
    			//find colors in this fabric
    			$colors=$this->Fabrics->find('all',['conditions'=>['fabric_name'=>$fabric['fabric_name']],'order'=>['color'=>'ASC']])->toArray();
    			foreach($colors as $color){
    				$fabricsArray[$fabric['fabric_name']][$color['id']]=$color['color'];
    			}
    		}
            
            fwrite($cacheFile,json_encode($fabricsArray));
            fclose($cacheFile);
            
    		return json_encode($fabricsArray);
        }
	}
	
	
	
	public function fixOrderStatus($orderID){
		
		$this->autRender=false;
		
		$thisOrder=$this->Orders->get($orderID)->toArray();
		$quoteItems=$this->QuoteLineItems->find('all',['conditions'=>['quote_id' => $thisOrder['quote_id']],'order'=>['sortorder'=>'asc']])->toArray();
		foreach($quoteItems as $lineItem){
			echo "Line ".$lineItem['line_number']."<br>";
			$thisOrderLineItemID=0;
			
			$findOrderItem=$this->OrderItems->find('all',['conditions'=>['order_id' => $orderID, 'quote_id' => $thisOrder['quote_id'], 'quote_line_item_id' => $lineItem['id']]])->toArray();
			foreach($findOrderItem as $orderItemQuoteItem){
				$thisOrderLineItemID=$orderItemQuoteItem['id'];
			}
			
			$numUnscheduled=floatval($lineItem['qty']);
			$numScheduled=0;
			$numCompleted=0;
			
			$allStatuses=$this->OrderItemStatus->find('all',['conditions'=>['order_item_id' => $thisOrderLineItemID],'order'=>['id'=>'desc']])->toArray();
			foreach($allStatuses as $status){
				switch($status['status']){
					case "Not Started":
						$numUnscheduled=($numUnscheduled+floatval($status['qty_involved']));
					break;
					case "Scheduled":
						$numScheduled=($numScheduled+floatval($status['qty_involved']));
						$numUnscheduled=($numUnscheduled - floatval($status['qty_involved']));
					break;
					case "Completed":
					case "Warehoused":
					case "Shipped":
						$numScheduled=($numScheduled - floatval($status['qty_involved']));
						$numUnscheduled=($numUnscheduled - floatval($status['qty_involved']));
						$numCompleted=($numCompleted + floatval($status['qty_involved']));
					break;
				}
			}
			
			echo "Unscheduled: ".$numUnscheduled."<br>Scheduled: ".$numScheduled."<br>Completed: ".$numCompleted;
			echo "<hr>";
			
		}
		
		exit;
		
	}

	
	
	
	
	public function auditOrderItemStatuses($orderID,$verbose=false){
		$order=$this->Orders->get($orderID)->toArray();
		$outstandingOrderItems=array();
		


		$orderItems=$this->OrderItems->find('all',['conditions'=>['order_id' => $order['id']]])->toArray();
		foreach($orderItems as $orderLineItem){
			$thisQuoteItem=$this->QuoteLineItems->get($orderLineItem['quote_line_item_id'])->toArray();
			
			if($thisQuoteItem['parent_line'] == 0){
				
				$qtyNotStarted=$thisquoteItem['qty'];
				$qtyScheduled=0;
				$qtyCompleted=0;
				$qtyShipped=0;
				$qtyInvoiced=0;
				
				//loop through all schedules on this order, this line number
				$thisOrderLineItemStatuses=$this->OrderItemStatus->find('all',['conditions'=>['work_order_id' => $order['id'],'order_line_number' => $thisQuoteItem['line_number'],'status IN' => ['Scheduled','In Progress','Completed','Warehoused','Shipped']],'order'=>['time'=>'desc']])->toArray();
				foreach($thisOrderLineItemStatuses as $lineStatus){
					//$totalQTY=($totalQTY - floatval($lineStatus['qty_involved']));
					switch($lineStatus['status']){
					    case "Invoiced":
					        
					        $qtyInvoiced=($qtyInvoiced+intval($lineStatus['qty_involved']));
					        
					    break;
						case "Scheduled":
						case "In Progress":

							//find out if these same items are also SHIPPED, if so, ignore this
							$checkThisBatchLineForCompletedStatus = $this->OrderItemStatus->find('all',['conditions' => ['sherry_batch_id' => $lineStatus['sherry_batch_id'], 'order_line_number' => $lineStatus['order_line_number'], 'id !=' => $lineStatus['id'], 'status IN' => ['Completed','Warehoused','Shipped','Invoiced'] ]])->toArray();
							if(count($checkThisBatchLineForCompletedStatus) >0){
								//yes already completed or shipped, ignore this line
							}else{
								$qtyScheduled=($qtyScheduled+intval($lineStatus['qty_involved']));
							}

						break;
						case "Completed":
						case "Warehoused":
							//find out if these same items are also SHIPPED, if so, ignore this
							$checkThisBatchLineForShippedStatus = $this->OrderItemStatus->find('all',['conditions' => ['sherry_batch_id' => $lineStatus['sherry_batch_id'], 'order_line_number' => $lineStatus['order_line_number'], 'id !=' => $lineStatus['id'], 'status IN' => ['Shipped','Invoiced'] ]])->toArray();
							if(count($checkThisBatchLineForShippedStatus) >0){
								//yes already shipped, ignore this line
							}else{
								$qtyCompleted=($qtyCompleted+intval($lineStatus['qty_involved']));
							}
						break;
						case "Shipped":
							$qtyShipped=($qtyShipped+intval($lineStatus['qty_involved']));
						break;
						case "Not Started":
							$qtyNotStarted=($qtyNotStarted+intval($lineStatus['qty_involved']));
						break;
					}
				}
					
					
				switch($thisQuoteItem['product_type']){
					case "window_treatments":
						if(preg_match("#drapery#i",$thisQuoteItem['title'])){
							$thisitemtype='drape';
						}elseif(preg_match("#valance#i",$thisQuoteItem['title'])){
						    $thisitemtype='val';
						}elseif(preg_match("#cornice#i",$thisQuoteItem['title'])){
							$thisitemtype='corn';
						}
					break;
					case 'newcatchall-valance':
					    $thisitemtype='val';
					break;
					case 'newcatchall-cornice':
					    $thisitemtype='corn';
					break;
					case "calculator":
						switch($thisQuoteItem['calculator_used']){
							case "bedspread":
								$thisitemtype='bs';
							break;
							case "cubicle-curtain":
								$thisitemtype='cc';
							break;
							case "box-pleated":
								$thisitemtype='val';
							break;
							case "pinch-pleated":
								$thisitemtype='drape';
							break;
							case "straight-cornice":
								$thisitemtype='corn';
							break;
						}
					break;
					case "cubicle_curtains":
					case 'newcatchall-cubicle':
						$thisitemtype='cc';
					break;
					case "bedspreads":
					case 'newcatchall-bedding':
						$thisitemtype='bs';
					break;
					case "track_systems":
						$thisitemtype='track';
					break;
					case 'newcatchall-hardware':
					    $thisitemtype='wthw';
					break;
					case 'newcatchall-blinds':
					case 'newcatchall-shades':
					case 'newcatchall-shutters':
					    $thisitemtype='blinds';
					break;
					case "custom":
						$metaLookup=$this->QuoteLineItemMeta->find('all',['conditions' => ['quote_item_id' => $thisQuoteItem['id']]])->toArray();
						foreach($metaLookup as $metaRow){
							if($metaRow['meta_key'] == 'catchallcategory'){
								if($metaRow['meta_value'] == 'WT Hardware'){
									$thisitemtype='wthw';
								}elseif($metaRow['meta_value'] == 'Blinds & Shades'){
									$thisitemtype='blinds';
								}else{
									$thisitemtype='none';
								}
							}
						}						
					break;
					default:
						$thisitemtype='none';
					break;
				}
					

				$outstandingOrderItems[$thisQuoteItem['id']]=array(
						'lineNumber' => $thisQuoteItem['line_number'],
						'itemType' => $thisitemtype,
						'completed' => floatval($qtyCompleted),
						'shipped' => floatval($qtyShipped),
						'invoiced' => floatval($qtyInvoiced),
						'scheduled'=>floatval($qtyScheduled),
						'unscheduled' => (floatval($thisQuoteItem['qty']) - floatval($qtyShipped) - floatval($qtyCompleted) - floatval($qtyScheduled))
				);

				if($verbose){
					echo "<pre>";
					print_r($outstandingOrderItems);
					echo "</pre>";
				}
			}
				
		}
			
		
		$cc_qty_unscheduled=0;
		$cc_qty_scheduled=0;
		
		$bs_qty_unscheduled=0;
		$bs_qty_scheduled=0;
		
		$drape_qty_unscheduled=0;
		$drape_qty_scheduled=0;
		
		//$wt_qty_unscheduled=0;
		//$wt_qty_scheduled=0;
		$corn_qty_unscheduled=0;
		$corn_qty_scheduled=0;
		
		$val_qty_unscheduled=0;
		$val_qty_scheduled=0;
		
		$wthw_qty_unscheduled=0;
		$wthw_qty_scheduled=0;
		
		$blinds_qty_unscheduled=0;
		$blinds_qty_scheduled=0;

		$track_lf_unscheduled=0;
		$track_lf_scheduled=0;

		$catchall_qty_unscheduled=0;
		$catchall_qty_scheduled=0;

		
		foreach($outstandingOrderItems as $lineItemID => $totals){
			$lineNumber=$totals['lineNumber'];
			switch($totals['itemType']){
				case "cc":
					$cc_qty_unscheduled = ($cc_qty_unscheduled + $totals['unscheduled']);
					$cc_qty_scheduled = ($cc_qty_scheduled + $totals['scheduled']);
				break;
				case "track":
					$track_lf_unscheduled = ($track_lf_unscheduled + $totals['unscheduled']);
					$track_lf_scheduled = ($track_lf_scheduled + $totals['scheduled']);
				break;
				case "corn":
				    $corn_qty_unscheduled=($corn_qty_unscheduled + $totals['unscheduled']);
				    $corn_qty_scheduled = ($corn_qty_scheduled + $totals['scheduled']);
			    break;
			    case "val":
			        $val_qty_unscheduled=($val_qty_unscheduled + $totals['unscheduled']);
			        $val_qty_scheduled=($val_qty_scheduled + $totals['scheduled']);
			    break;
				case "wt":
					$wt_qty_unscheduled = ($wt_qty_unscheduled + $totals['unscheduled']);
					$wt_qty_scheduled = ($wt_qty_scheduled + $totals['scheduled']);
				break;
				case "drape":
					$drape_qty_unscheduled = ($drape_qty_unscheduled + $totals['unscheduled']);
					$drape_qty_scheduled = ($drape_qty_scheduled + $totals['scheduled']);
				break;
				case "bs":
					$bs_qty_unscheduled = ($bs_qty_unscheduled + floatval($totals['unscheduled']));
					$bs_qty_scheduled = ($bs_qty_scheduled + $totals['scheduled']);
				break;
				case "wthw":
					$wthw_qty_unscheduled = ($wthw_qty_unscheduled + $totals['unscheduled']);
					$wthw_qty_scheduled = ($wthw_qty_scheduled + $totals['scheduled']);
				break;
				case "blinds":
					$blinds_qty_unscheduled = ($blinds_qty_unscheduled + $totals['unscheduled']);
					$blinds_qty_scheduled = ($blinds_qty_scheduled + $totals['scheduled']);
				break;
				default:
					if($verbose){
						echo "<br>TYPE=".$totals['itemType']."<br>";
					}
					$catchall_qty_unscheduled = ($catchall_qty_unscheduled + $totals['unscheduled']);
					$catchall_qty_scheduled = ($catchall_qty_scheduled + $totals['scheduled']);
				break;
			}
			
		}
			
		
		if($verbose){
			echo "<hr>VARS:<br>cc_qty_unscheduled = ".$cc_qty_unscheduled."<br>";
			//echo "wt_qty_unscheduled = ".$wt_qty_unscheduled."<br>";
			echo "corn_qty_unscheduled = ".$corn_qty_unscheduled."<br>";
			echo "val_qty_unscheduled = ".$val_qty_unscheduled."<br>";
			echo "drape_qty_unscheduled = ".$drape_qty_unscheduled."<br>";
			echo "bs_qty_unscheduled = ".$bs_qty_unscheduled."<br>";
			echo "wthw_qty_unscheduled = ".$wthw_qty_unscheduled."<br>";
			echo "blinds_qty_unscheduled = ".$blinds_qty_unscheduled."<br>";
			echo "track_lf_unscheduled = ".$track_lf_unscheduled."<br>";
			echo "catchall_qty_unscheduled = ".$catchall_qty_unscheduled."<hr>";
		}

		if($cc_qty_unscheduled == 0 && $val_qty_unscheduled == 0 && $corn_qty_unscheduled == 0 && $drape_qty_unscheduled == 0 && $bs_qty_unscheduled == 0 && $wthw_qty_unscheduled == 0 && $blinds_qty_unscheduled == 0 && $track_lf_unscheduled == 0 && $catchall_qty_unscheduled == 0){
			$newSherryStatus="Fully Scheduled";

			if($verbose){
				echo "FULL SCHEDULE<hr><pre>";
				print_r($outstandingOrderItems);
				echo "</pre>";exit;
			}

		}elseif($cc_qty_unscheduled > 0 || $val_qty_unscheduled > 0 || $corn_qty_unscheduled > 0 || $drape_qty_unscheduled > 0 || $bs_qty_unscheduled >0 || $wthw_qty_unscheduled > 0 || $blinds_qty_unscheduled > 0 || $track_lf_unscheduled > 0 || $catchall_qty_unscheduled > 0){
			
			if($cc_qty_scheduled >0 || $val_qty_scheduled > 0 || $corn_qty_scheduled > 0 || $drape_qty_scheduled > 0 || $bs_qty_scheduled > 0 || $wthw_qty_scheduled > 0 || $blinds_qty_scheduled > 0 || $track_lf_scheduled > 0 || $catchall_qty_scheduled > 0){
				$newSherryStatus="Partially Scheduled";
			}else{
				$newSherryStatus="Not Scheduled";
			}	

			if($verbose){
				echo strtoupper($newSherryStatus)."<hr><pre>";
				print_r($outstandingOrderItems);
				echo "</pre>";exit;
			}


		}else{
			if($verbose){
				echo "NOOOOO<hr><pre>";
				print_r($outstandingOrderItems);
				echo "</pre>";exit;
			}
		}
		
		/*
		print_r($outstandingOrderItems);
		echo "<hr>";
		echo $newSherryStatus;
		exit;
		*/
		
		if(strlen(trim($newSherryStatus)) == 0){
			if($verbose){
			    echo "Could not determine a sherry status for order <b>".$orderID."</b><br>";
			}
		}else{
			$ordersTable=TableRegistry::get('Orders');
			$thisOrderRow=$ordersTable->get($orderID);
			$thisOrderRow->sherry_status=$newSherryStatus;
			if($ordersTable->save($thisOrderRow)){
			    if($verbose){
				    echo "Saved new status <b>".$newSherryStatus."</b> for order <b>".$orderID."</b><Br>";
			    }
			}
		}

		
		return $newSherryStatus;
	}
	
	
	public function resizeimage($originalsrc,$newsrc,$newwidth){
		
		$imagick = new \Imagick($originalsrc);
		$imagick->resizeImage($newwidth, 0, imagick::FILTER_LANCZOS,1);
		$imagick->writeImage($newsrc);
		
		$imagick->clear();
		$imagick->destroy();
		
	}


	public function aspectratiofix($imgid,$maxheight=false){

		$imageData=$this->LibraryImages->get($imgid)->toArray();

		$imagePath=$_SERVER['DOCUMENT_ROOT']."/webroot/img/library/".$imageData['filename'];

		$originalImagePath=$_SERVER['DOCUMENT_ROOT']."/webroot/img/library/original_".$imageData['filename'];

		list($width, $height, $type, $attr) = getimagesize($imagePath);

		if($maxheight){
			if($height > $maxheight){
				$height=$maxheight;
			}
		}
		
		copy($_SERVER['DOCUMENT_ROOT']."/webroot/img/library/".$imageData['filename'],$_SERVER['DOCUMENT_ROOT']."/webroot/img/library/original_".$imageData['filename']);
		unlink($_SERVER['DOCUMENT_ROOT']."/webroot/img/library/".$imageData['filename']);

		$doupdateDB=false;



		//convert it to PNG if it is not already a png
		if(strtolower(substr($imageData['filename'],-4)) != '.png'){
			$imagick = new \Imagick($_SERVER['DOCUMENT_ROOT']."/webroot/img/library/original_".$imageData['filename']);
			$imagick->setImageFormat('png');

			

			$ext=strtolower(substr($imageData['filename'],-4));
			switch($ext){
				case ".jpg":
					$doupdateDB=true;
					$newsrc=substr($imageData['filename'],0,(strlen($imageData['filename'])-4)).".png";
				break;
				case "jpeg":
					$doupdateDB=true;
					$newsrc=substr($imageData['filename'],0,(strlen($imageData['filename'])-5)).".png";
				break;
				case ".gif":
					$doupdateDB=true;
					$newsrc=substr($imageData['filename'],0,(strlen($imageData['filename'])-4)).".png";
				break;
				case ".bmp":
					$doupdateDB=true;
					$newsrc=substr($imageData['filename'],0,(strlen($imageData['filename'])-4)).".png";
				break;
				case "tiff":
					$doupdateDB=true;
					$newsrc=substr($imageData['filename'],0,(strlen($imageData['filename'])-5)).".png";
				break;
			}

			$newsrcpath=$_SERVER['DOCUMENT_ROOT']."/webroot/img/library/converted_".$newsrc;

			$imagick->writeImage($newsrcpath);

			$imagick->clear();
			$imagick->destroy();

            
            $url = "https://".$_SERVER['HTTP_HOST']."/img/mthumb.php?ct=1&src=".urlencode($newsrcpath)."&w=".round(($height*2.5))."&h=".$height."&zc=2";
            $curl = curl_init();
            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_HEADER, false);
            if($data = curl_exec($curl)){
                curl_close($curl);
            
		        @unlink($_SERVER['DOCUMENT_ROOT']."/webroot/img/library/".$newsrc);
		        @unlink($_SERVER['DOCUMENT_ROOT']."/webroot/img/library/converted_".$newsrc);
		        $newfile=fopen($_SERVER['DOCUMENT_ROOT']."/webroot/img/library/converted_".$newsrc,"w");
		        fwrite($newfile,$data,strlen($data));
		        fclose($newfile);
            }
		    
		}else{
			$newsrc=$imageData['filename'];

            $doupdateDB=true;
            
            $url = "https://".$_SERVER['HTTP_HOST']."/img/mthumb.php?ct=1&src=".urlencode($originalImagePath)."&w=".round(($height*2.5))."&h=".$height."&zc=2";
            $curl = curl_init();
            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_HEADER, false);
            if($data = curl_exec($curl)){
                curl_close($curl);
            
		        @unlink($_SERVER['DOCUMENT_ROOT']."/webroot/img/library/".$newsrc);
		        @unlink($_SERVER['DOCUMENT_ROOT']."/webroot/img/library/converted_".$newsrc);
		        $newfile=fopen($_SERVER['DOCUMENT_ROOT']."/webroot/img/library/converted_".$newsrc,"w");
		        fwrite($newfile,$data,strlen($data));
		        fclose($newfile);
            }
	    	
		}



	    if($doupdateDB){
	    	$imgLibraryTable=TableRegistry::get('LibraryImages');
	    	$thisImgEntry=$imgLibraryTable->get($imgid);
	    	$thisImgEntry->filename="converted_".$newsrc;
	    	$imgLibraryTable->save($thisImgEntry);
	    }


	}
	
	
	public function add_business_days($startdate,$businessdays,$holidays,$dateformat){
        $i=1;
        $dayx = strtotime($startdate);
        while($i < $businessdays){
            $day = date('N',$dayx);
            $date = date('Y-m-d',$dayx);
            if($day < 6 && !in_array($date,$holidays))$i++;
            $dayx = strtotime($date.' +1 day');
        }
        return date($dateformat,$dayx);
	}
	
	
	
	public function orderchangerulecheck($orderID,$newQuoteID){
	    //gather all the line items from the new quote
	    $newQuoteItems=$this->QuoteLineItems->find('all',['conditions'=>['quote_id' => $newQuoteID,'parent_line'=>0 ], 'order'=>['line_number'=>'asc']])->toArray();
	   
	    //compare and populate error array
	    $errors=array();
	    foreach($newQuoteItems as $item){
	        
	        //determine if this new line qty is ok or not
	        $orderBatchesThisLine=$this->OrderItemStatus->find('all',['conditions' => ['work_order_id' => $orderID, 'status'=>'Scheduled', 'order_line_number' => $item['line_number']]])->toArray();
	        $totalBatchedThisLine=0;
	        foreach($orderBatchesThisLine as $batch){
	            $totalBatchedThisLine=($totalBatchedThisLine + $batch['qty_involved']);
	        }
	        
	        if($totalBatchedThisLine > $item['qty']){
	            $errors[]=array('line'=>$item['line_number'],'errorMsg'=>'Cannot modify QTY to less than '.$totalBatchedThisLine);
	        }
	        
	    }
	    
	    
	    if(count($errors) == 0){
	        return true;
	    }else{
	        return $errors;
	    }
	    
	    
	}
	

    
    
    public function getOrderProgressPercent($orderID){

		$itemCount=0;

		$completedCount=0;
		$shippedCount=0;
		

		$orderItems=$this->OrderItems->find('all',['conditions'=>['order_id'=>$orderID]])->toArray();

		foreach($orderItems as $item){

			$itemStatus=$this->OrderItemStatus->find('all',['conditions' => ['order_item_id' => $item['id'],'status' => 'Not Started']])->toArray();

			foreach($itemStatus as $status){

				$itemCount=($itemCount+floatval($status['qty_involved']));

			}

			

			$completedItemCheck=$this->OrderItemStatus->find('all',['conditions'=>['order_item_id'=>$item['id'],'status'=>'Completed']])->toArray();

			foreach($completedItemCheck as $completed){

				$completedCount=($completedCount+floatval($completed['qty_involved']));

			}

			$shippedItemCheck=$this->OrderItemStatus->find('all',['conditions'=>['order_item_id'=>$item['id'],'status'=>'Shipped']])->toArray();

			foreach($shippedItemCheck as $shipped){

				$shippedCount=($shippedCount+floatval($shipped['qty_involved']));

			}

		}

		

		return array('completed'=>$completedCount,'shipped'=>$shippedCount,'total'=>$itemCount,'percent'=>round((($completedCount/$itemCount)*100)));

		

	}
	/**PPSA-1 start  */
	public function getWorkOrderProgressPercent($orderID){

		$itemCount=0;

		$completedCount=0;
		$shippedCount=0;
		

		$orderItems=$this->WorkOrderItems->find('all',['conditions'=>['order_id'=>$orderID]])->toArray();

		foreach($orderItems as $item){

			$itemStatus=$this->WorkOrderItemStatus->find('all',['conditions' => ['order_item_id' => $item['id'],'status' => 'Not Started']])->toArray();

			foreach($itemStatus as $status){

				$itemCount=($itemCount+floatval($status['qty_involved']));

			}

			

			$completedItemCheck=$this->WorkOrderItemStatus->find('all',['conditions'=>['order_item_id'=>$item['id'],'status'=>'Completed']])->toArray();

			foreach($completedItemCheck as $completed){

				$completedCount=($completedCount+floatval($completed['qty_involved']));

			}

			$shippedItemCheck=$this->WorkOrderItemStatus->find('all',['conditions'=>['order_item_id'=>$item['id'],'status'=>'Shipped']])->toArray();

			foreach($shippedItemCheck as $shipped){

				$shippedCount=($shippedCount+floatval($shipped['qty_involved']));

			}

		}

		

		return array('completed'=>$completedCount,'shipped'=>$shippedCount,'total'=>$itemCount,'percent'=>round((($completedCount/$itemCount)*100)));

		

	}
/** PPSASCRUM-29 Start **/

public function savetoOrderLineItemTables($itemId, $newQuoteID,$orderID, $table='orders' ) {
	$orderTable=TableRegistry::get('Orders');
		if($table == 'orders') {
			$tableName = 'orders';
			$orderLinesTable=TableRegistry::get('OrderLineItems');
		}else {
			$tableName = 'WorkOrder';
			$orderLinesTable=TableRegistry::get('WorkOrderLineItems');
		}
		$lineItems=$this->QuoteLineItems->find('all',['conditions' => ['id' => $itemId]])->toArray();
		foreach($lineItems as $line){
			$newline = $orderLinesTable->newEntity();
			$copy = $orderLinesTable->patchEntity($newline, $line);
			$copy['order_id'] = $orderID;
			$copy['quote_id'] = $newQuoteID;
			$copy['quote_line_item_id'] = $line->id;
			$copy['product_id'] = $line->product_id;
			$copy['status'] = $line->status;
			$copy['product_type'] = $line->product_type;
			$copy['product_subclass'] = $line->product_subclass;
			$copy['title'] = $line->title;
			$copy['description'] = $line->description;
			$copy['best_price'] = $line->best_price;
			$copy['qty'] = $line->qty;
			$copy['lineitemtype'] = $line->lineitemtype;
			$copy['room_number'] = $line->room_number;
			$copy['internal_line'] = $line->internal_line;
			$copy['line_number'] = $line->line_number;
			$copy['enable_tally'] = $line->enable_tally;
			$copy['sortorder'] = $line->sortorder;
			$copy['tier_adjusted_price'] = $line->tier_adjusted_price;
			$copy['install_adjusted_price'] = $line->install_adjusted_price;
			$copy['extended_price'] = $line->extended_price;
			$copy['override_active'] = $line->override_active;
			$copy['override_price'] = $line->override_price;
			$copy['parent_line'] = $line->parent_line;
			$copy['calculator_used'] = $line->calculator_used;
			$copy['revised_from_line'] = $line->revised_from_line;
			$copy['pmi_adjusted'] = $line->pmi_adjusted;
			$copy['project_management_surcharge_adjusted'] = $line->project_management_surcharge_adjusted;
			
			//unset($copy['id']);
			// Unset or modify all others what you need
			$orderLinesTable->save($copy);
			$this->savetoOrderWorkOrderLineMetaTables($line->id,$newQuoteID,$copy->id,$table);
		}

}
public function UpdatetoOrderLineItemTables($quoteId, $lineItemId,$orderID, $table ='orders'){
	$orderTable=TableRegistry::get('Orders');

	if($table == 'orders') {
		$tableName = 'orders';
		$orderLinesTable=TableRegistry::get('OrderLineItems');
	}else {
		$tableName = 'WorkOrder';
		$orderLinesTable=TableRegistry::get('WorkOrderLineItems');
	}
	$lineItems=$this->QuoteLineItems->find('all',['conditions' => ['id' => $lineItemId]])->first();
	$thisorderItemRow=$orderLinesTable->find('all',['conditions'=>['order_id' => $orderID, 'quote_line_item_id'=>$lineItemId]])->first();

	if(empty($thisorderItemRow)){
		$this->savetoOrderLineItemTables($lineItemId, $quoteId,$orderID, $table='orders' );
		$this->savetoOrderLineItemTables($lineItemId, $quoteId,$orderID, $table='work_orders' );

	}else {
		foreach($lineItems as $line){
					$thisorderItemRow->title=$line['title'];
        			$thisorderItemRow->description = $line['description'];
        			$thisorderItemRow->qty = $line['qty'];
        			$thisorderItemRow->unit=$line['unit'];
        			$thisorderItemRow->best_price = $line['price'];
        			$thisorderItemRow->room_number = $line['room_number'];
					$thisorderItemRow->tier_adjusted_price = $line['tier_adjusted_price'];
        			$thisorderItemRow->install_adjusted_price = $line['install_adjusted_price'];
        			$thisorderItemRow->rebate_adjusted_price = $line['rebate_adjusted_price'];
        			$thisorderItemRow->pmi_adjusted = $line['pmi_adjusted'];
					$thisorderItemRow['override_price']=$line['override_price'];
        			$thisorderItemRow['override_active']=$line['override_active'];
					$thisorderItemRow['extended_price']=$line['extended_price'];
        			$orderLinesTable->save($thisorderItemRow);
					if($table == 'orders') {
						$this->updatetoOrderLineItemMeta($orderID, $line['id'],'orders');      			

					}else {
						$tableName = 'WorkOrder';
						$this->updatetoOrderLineItemMeta($orderID, $line['id'],'work_orders');      			
					}
		}
	}
				$this->logActivity($_SERVER['REQUEST_URI'],'Update OrderLineItems meta saved '.$orderID.'----'.$lineItemId.' to Order '.$tableName);

}

public function updatetoOrderLineItemMeta($orderId,$quoteItemID, $tableName='orders'){
	$quoteLineMetaTable=TableRegistry::get('QuoteLineItemMeta');

	if($tableName == 'orders') {
		$orderLinesMetaTable=TableRegistry::get('OrderLineItemMeta');
	}else {
		$orderLinesMetaTable=TableRegistry::get('WorkOrderLineItemMeta');
	}
	
	$thisQuoteLineMeta=$quoteLineMetaTable->find('all',['conditions' => ['quote_item_id'=>$quoteItemID]])->toArray();
	$this->logActivity($_SERVER['REQUEST_URI'],'Converted Update Quote meta before saved '.$orderId.' to Order '.$tableName);
//delete all lineItems before insertions
if($tableName == 'orders')
$orderLinesMetaTable->delete('all',['conditions' => ['order_item_id'=>$orderId]]);
else 
$orderLinesMetaTable->delete('all',['conditions' => ['worder_item_id'=>$orderId]]);


	foreach($thisQuoteLineMeta as $line){
		

		$newWorkMeta=$orderLinesMetaTable->newEntity();
		$newWorkMeta['meta_key']=$line['meta_key'];
		$newWorkMeta['meta_value']=$line['meta_value'];
		if($tableName == 'orders') {
		$newWorkMeta['order_item_id'] =$orderId; }
		else {
		$newWorkMeta['worder_item_id'] = $orderId; }
		//print_r($newWorkMeta);
		
		if($orderLinesMetaTable->save($newWorkMeta)){
			$this->logActivity($_SERVER['REQUEST_URI'],'ConvertedUpdate Quote meta saved '.$orderId.' to Order '.$tableName);

		}else {
			$this->logActivity($_SERVER['REQUEST_URI'],'ConvertedUpdate Quote meta not saved '.$orderId.' to Order '.$tableName);

		}
		$this->logActivity($_SERVER['REQUEST_URI'],'Converted Quote meta after saved '.$quoteItemID.' to Order '.$tableName);

	}
}
public function savetoOrderWorkOrderLineMetaTables($newId, $newQuoteID,$itemID,$tableName='orders') {
	
	//$orderLinesMetaTable=TableRegistry::get($tableName);
	//$workOrderLineMetaTable=TableRegistry::get('WorkOrderLineItemMeta');
	$quoteLineMetaTable=TableRegistry::get('QuoteLineItemMeta');

	if($tableName == 'orders') {
		$orderLinesMetaTable=TableRegistry::get('OrderLineItemMeta');
	}else {
		$orderLinesMetaTable=TableRegistry::get('WorkOrderLineItemMeta');
	}
	
	$thisQuoteLineMeta=$quoteLineMetaTable->find('all',['conditions' => ['quote_item_id'=>$newId]])->toArray();
	$this->logActivity($_SERVER['REQUEST_URI'],'Converted Quote meta before saved '.$newId.' to Order '.$tableName);

	foreach($thisQuoteLineMeta as $line){
		

		$newWorkMeta=$orderLinesMetaTable->newEntity();
		$newWorkMeta['meta_key']=$line['meta_key'];
		$newWorkMeta['meta_value']=$line['meta_value'];
		if($tableName == 'orders') {
		$newWorkMeta['order_item_id'] =$itemID; }
		else {
		$newWorkMeta['worder_item_id'] = $itemID; }
		//print_r($newWorkMeta);
		
		if($orderLinesMetaTable->save($newWorkMeta)){
			$this->logActivity($_SERVER['REQUEST_URI'],'Converted Quote meta saved '.$itemID.' to Order '.$tableName);

		}else {
			$this->logActivity($_SERVER['REQUEST_URI'],'Converted Quote meta not saved '.$itemID.' to Order '.$tableName);

		}
		$this->logActivity($_SERVER['REQUEST_URI'],'Converted Quote meta after saved '.$newId.' to Order '.$tableName);

	}

	
}


	public function savetoOrderWorkOrderLineItemTables($newId, $newQuoteID,$orderID, $table='orders' ) {
		$orderTable=TableRegistry::get('Orders');
		if($table == 'orders') {
			$tableName = 'OrderLineItemMeta';
			$orderLinesTable=TableRegistry::get('OrderLineItems');
		}else {
			$tableName = 'WorkOrderLineItemsMeta';
			$orderLinesTable=TableRegistry::get('WorkOrderLineItems');
		}
			//OrderandWorkOrder Line Items insertions
			$lineItems=$this->QuoteLineItems->find('all',['conditions' => ['quote_id' => $newQuoteID]])->toArray();
			foreach($lineItems as $line){
				$newline = $orderLinesTable->newEntity();
				$copy = $orderLinesTable->patchEntity($newline, $line);
            	$copy['order_id'] = $orderID;
				$copy['quote_id'] = $newQuoteID;
				$copy['quote_line_item_id'] = $line->id;
				$copy['product_id'] = $line->product_id;
				$copy['status'] = $line->status;
				$copy['product_type'] = $line->product_type;
				$copy['product_subclass'] = $line->product_subclass;
				$copy['title'] = $line->title;
				$copy['description'] = $line->description;
				$copy['best_price'] = $line->best_price;
				$copy['qty'] = $line->qty;
				$copy['lineitemtype'] = $line->lineitemtype;
				$copy['room_number'] = $line->room_number;
				$copy['internal_line'] = $line->internal_line;
				$copy['line_number'] = $line->line_number;
				$copy['enable_tally'] = $line->enable_tally;
				$copy['sortorder'] = $line->sortorder;
				$copy['tier_adjusted_price'] = $line->tier_adjusted_price;
				$copy['install_adjusted_price'] = $line->install_adjusted_price;
				$copy['extended_price'] = $line->extended_price;
				$copy['override_active'] = $line->override_active;
				$copy['override_price'] = $line->override_price;
				$copy['parent_line'] = $line->parent_line;
				$copy['calculator_used'] = $line->calculator_used;
				$copy['revised_from_line'] = $line->revised_from_line;
				$copy['pmi_adjusted'] = $line->pmi_adjusted;
				$copy['project_management_surcharge_adjusted'] = $line->project_management_surcharge_adjusted;
				
            	unset($copy['id']);
            	// Unset or modify all others what you need
				$orderLinesTable->save($copy);
				$this->savetoOrderWorkOrderLineMetaTables($line->id,$newQuoteID,$tableName);
			}
	}

	public function savetoOrderWorkOrderLineNotesTables($newId, $newQuoteID,$orderID, $table='orders' ){
			if($table=='orders'){
				$OrderLineItemNotesTable=TableRegistry::get('OrderLineItemNotes');
				$newOrderLineItemNotesTable=$OrderLineItemNotesTable->newEntity();
			
			}else {
				$OrderLineItemNotesTable=TableRegistry::get('WorkOrderLineItemNotes');
				$newOrderLineItemNotesTable=$OrderLineItemNotesTable->newEntity();
						
			}
			$lineItems=$this->QuoteLineItems->find('all',['conditions' => ['quote_id' => $newQuoteID]])->toArray();
			foreach($lineItems as $line){
				$thisQuoteLineNotes=$this->QuoteLineItemNotes->find('all',['conditions'=>['quote_item_id' => $line['id']]])->toArray();
		
					foreach($thisQuoteLineNotes as $lineNote){
					    	
						$newOrderLineItemNotesTable->user_id=$lineNote['user_id'];
						$newOrderLineItemNotesTable->time=$lineNote['time'];
						$newOrderLineItemNotesTable->message=$lineNote['message'];
						$newOrderLineItemNotesTable->quote_id=$thisQuoteRow['id'];
						$newOrderLineItemNotesTable->quote_item_id=$itemData['id'];
						$newOrderLineItemNotesTable->visible_to_customer=$lineNote['visible_to_customer'];
						$OrderLineItemNotesTable->save($newOrderLineItemNotesTable);
						
					} 
				}
	}

	public function updateLineItemsDetails($orderId,$itemId, $quoteID,$tieradjusted, $installadjusted, $rebateadjusted,$pmiadjusted,$extended_price){
		$lineItems=$this->QuoteLineItems->find('all',['conditions' => ['id' => $itemId]])->first()->toArray();
					$workorderTable=TableRegistry::get('WorkOrderLineItems');
					echo $orderId. "====>".$itemId;
					$thisworkorderItemRow=$this->WorkOrderLineItems->find('all',['conditions'=>['order_id' => $orderId, 'quote_line_item_id'=>$itemId]])->first();
					if(empty($thisworkorderItemRow)) {
						$this->savetoOrderWorkOrderLineItemTables($lineItems['id'], $lineItems['quote_id'],$orderId, 'workorders' );
					} else {
					//print_r($thisworkorderItemRow); echo "----->";
					$thisworkorderItemRow->tier_adjusted_price=$tieradjusted;
					$thisworkorderItemRow->install_adjusted_price=$installadjusted;
					$thisworkorderItemRow->rebate_adjusted_price=$rebateadjusted;
					$thisworkorderItemRow->pmi_adjusted = $pmiadjusted;
					$thisworkorderItemRow->extended_price=$extended_price;
					print_r($thisworkorderItemRow);
					$workorderTable->save($thisworkorderItemRow);
				}
					
					
					$orderTable=TableRegistry::get('OrderLineItems');
					$thisorderItemRow=$orderTable->find('all',['conditions'=>['order_id' => $orderId, 'quote_line_item_id'=>$itemId]])->first();
					if(empty($thisworkorderItemRow)) {
						$this->savetoOrderWorkOrderLineItemTables($lineItems['id'], $lineItems['quote_id'],$orderId, 'orders' );
					} else {
					$thisorderItemRow->tier_adjusted_price=$tieradjusted;
					$thisorderItemRow->install_adjusted_price=$installadjusted;
					$thisorderItemRow->rebate_adjusted_price=$rebateadjusted;
					$thisorderItemRow->pmi_adjusted = $pmiadjusted;
					$thisorderItemRow->extended_price=$extended_price;
					$orderTable->save($thisorderItemRow);
				}
					$this->logActivity($_SERVER['REQUEST_URI'],"Updated WorkOrder and Order lineItem in recalculatequoteadjustments method ".$itemId ." Order ID :" .$orderId );

	}
	
	public function auditWorkOrderItemStatuses($quoteID,$verbose=false){
		$order = $this->WorkOrders->find('all',['conditions'=>['quote_id' => $quoteID]])->first();
		//$order=$this->WorkOrders->get($orderID)->toArray();
		$orderID = $order['id'];
		$outstandingOrderItems=array();
		


		$orderItems=$this->WorkOrderItems->find('all',['conditions'=>['quote_line_item_id' => $order['id']]])->toArray();

		foreach($orderItems as $orderLineItem){
			$thisQuoteItem=$this->WorkOrderLineItems->find('all',['conditions'=>['id' => $orderLineItem['quote_line_item_id']]])->toArray();
			
			if($thisQuoteItem['parent_line'] == 0){
				
				$qtyNotStarted=$thisQuoteItem['qty'];
				$qtyScheduled=0;
				$qtyCompleted=0;
				$qtyShipped=0;
				$qtyInvoiced=0;
				
				//loop through all schedules on this order, this line number
				$thisOrderLineItemStatuses=$this->WorkOrderItemStatus->find('all',['conditions'=>['order_id' => $order['id'],'order_line_number' => $thisQuoteItem['line_number'],'status IN' => ['Scheduled','In Progress','Completed','Warehoused','Shipped']],'order'=>['time'=>'desc']])->toArray();
				foreach($thisOrderLineItemStatuses as $lineStatus){
					//$totalQTY=($totalQTY - floatval($lineStatus['qty_involved']));
					switch($lineStatus['status']){
					    case "Invoiced":
					        
					        $qtyInvoiced=($qtyInvoiced+intval($lineStatus['qty_involved']));
					        
					    break;
						case "Scheduled":
						case "In Progress":

							//find out if these same items are also SHIPPED, if so, ignore this
							$checkThisBatchLineForCompletedStatus = $this->WorkOrderItemStatus->find('all',['conditions' => ['sherry_batch_id' => $lineStatus['sherry_batch_id'], 'order_line_number' => $lineStatus['order_line_number'], 'id !=' => $lineStatus['id'], 'status IN' => ['Completed','Warehoused','Shipped','Invoiced'] ]])->toArray();
							if(count($checkThisBatchLineForCompletedStatus) >0){
								//yes already completed or shipped, ignore this line
							}else{
								$qtyScheduled=($qtyScheduled+intval($lineStatus['qty_involved']));
							}

						break;
						case "Completed":
						case "Warehoused":
							//find out if these same items are also SHIPPED, if so, ignore this
							$checkThisBatchLineForShippedStatus = $this->WorkOrderItemStatus->find('all',['conditions' => ['sherry_batch_id' => $lineStatus['sherry_batch_id'], 'order_line_number' => $lineStatus['order_line_number'], 'id !=' => $lineStatus['id'], 'status IN' => ['Shipped','Invoiced'] ]])->toArray();
							if(count($checkThisBatchLineForShippedStatus) >0){
								//yes already shipped, ignore this line
							}else{
								$qtyCompleted=($qtyCompleted+intval($lineStatus['qty_involved']));
							}
						break;
						case "Shipped":
							$qtyShipped=($qtyShipped+intval($lineStatus['qty_involved']));
						break;
						case "Not Started":
							$qtyNotStarted=($qtyNotStarted+intval($lineStatus['qty_involved']));
						break;
					}
				}
					
					
				switch($thisQuoteItem['product_type']){
					case "window_treatments":
						if(preg_match("#drapery#i",$thisQuoteItem['title'])){
							$thisitemtype='drape';
						}elseif(preg_match("#valance#i",$thisQuoteItem['title'])){
						    $thisitemtype='val';
						}elseif(preg_match("#cornice#i",$thisQuoteItem['title'])){
							$thisitemtype='corn';
						}
					break;
					case 'newcatchall-valance':
					    $thisitemtype='val';
					break;
					case 'newcatchall-cornice':
					    $thisitemtype='corn';
					break;
					case "calculator":
						switch($thisQuoteItem['calculator_used']){
							case "bedspread":
								$thisitemtype='bs';
							break;
							case "cubicle-curtain":
								$thisitemtype='cc';
							break;
							case "box-pleated":
								$thisitemtype='val';
							break;
							case "pinch-pleated":
								$thisitemtype='drape';
							break;
							case "straight-cornice":
								$thisitemtype='corn';
							break;
						}
					break;
					case "cubicle_curtains":
					case 'newcatchall-cubicle':
						$thisitemtype='cc';
					break;
					case "bedspreads":
					case 'newcatchall-bedding':
						$thisitemtype='bs';
					break;
					case "track_systems":
						$thisitemtype='track';
					break;
					case 'newcatchall-hardware':
					    $thisitemtype='wthw';
					break;
					case 'newcatchall-blinds':
					case 'newcatchall-shades':
					case 'newcatchall-shutters':
					    $thisitemtype='blinds';
					break;
					case "custom":
						$metaLookup=$this->WorkOrderLineItemMeta->find('all',['conditions' => ['quote_item_id' => $thisQuoteItem['id']]])->toArray();
						foreach($metaLookup as $metaRow){
							if($metaRow['meta_key'] == 'catchallcategory'){
								if($metaRow['meta_value'] == 'WT Hardware'){
									$thisitemtype='wthw';
								}elseif($metaRow['meta_value'] == 'Blinds & Shades'){
									$thisitemtype='blinds';
								}else{
									$thisitemtype='none';
								}
							}
						}						
					break;
					default:
						$thisitemtype='none';
					break;
				}
					

				$outstandingOrderItems[$thisQuoteItem['id']]=array(
						'lineNumber' => $thisQuoteItem['line_number'],
						'itemType' => $thisitemtype,
						'completed' => floatval($qtyCompleted),
						'shipped' => floatval($qtyShipped),
						'invoiced' => floatval($qtyInvoiced),
						'scheduled'=>floatval($qtyScheduled),
						'unscheduled' => (floatval($thisQuoteItem['qty']) - floatval($qtyShipped) - floatval($qtyCompleted) - floatval($qtyScheduled))
				);

				if($verbose){
					echo "<pre>";
					print_r($outstandingOrderItems);
					echo "</pre>";
				}
			}
				
		}
			
		
		$cc_qty_unscheduled=0;
		$cc_qty_scheduled=0;
		
		$bs_qty_unscheduled=0;
		$bs_qty_scheduled=0;
		
		$drape_qty_unscheduled=0;
		$drape_qty_scheduled=0;
		
		//$wt_qty_unscheduled=0;
		//$wt_qty_scheduled=0;
		$corn_qty_unscheduled=0;
		$corn_qty_scheduled=0;
		
		$val_qty_unscheduled=0;
		$val_qty_scheduled=0;
		
		$wthw_qty_unscheduled=0;
		$wthw_qty_scheduled=0;
		
		$blinds_qty_unscheduled=0;
		$blinds_qty_scheduled=0;

		$track_lf_unscheduled=0;
		$track_lf_scheduled=0;

		$catchall_qty_unscheduled=0;
		$catchall_qty_scheduled=0;

		
		foreach($outstandingOrderItems as $lineItemID => $totals){
			$lineNumber=$totals['lineNumber'];
			switch($totals['itemType']){
				case "cc":
					$cc_qty_unscheduled = ($cc_qty_unscheduled + $totals['unscheduled']);
					$cc_qty_scheduled = ($cc_qty_scheduled + $totals['scheduled']);
				break;
				case "track":
					$track_lf_unscheduled = ($track_lf_unscheduled + $totals['unscheduled']);
					$track_lf_scheduled = ($track_lf_scheduled + $totals['scheduled']);
				break;
				case "corn":
				    $corn_qty_unscheduled=($corn_qty_unscheduled + $totals['unscheduled']);
				    $corn_qty_scheduled = ($corn_qty_scheduled + $totals['scheduled']);
			    break;
			    case "val":
			        $val_qty_unscheduled=($val_qty_unscheduled + $totals['unscheduled']);
			        $val_qty_scheduled=($val_qty_scheduled + $totals['scheduled']);
			    break;
				case "wt":
					$wt_qty_unscheduled = ($wt_qty_unscheduled + $totals['unscheduled']);
					$wt_qty_scheduled = ($wt_qty_scheduled + $totals['scheduled']);
				break;
				case "drape":
					$drape_qty_unscheduled = ($drape_qty_unscheduled + $totals['unscheduled']);
					$drape_qty_scheduled = ($drape_qty_scheduled + $totals['scheduled']);
				break;
				case "bs":
					$bs_qty_unscheduled = ($bs_qty_unscheduled + floatval($totals['unscheduled']));
					$bs_qty_scheduled = ($bs_qty_scheduled + $totals['scheduled']);
				break;
				case "wthw":
					$wthw_qty_unscheduled = ($wthw_qty_unscheduled + $totals['unscheduled']);
					$wthw_qty_scheduled = ($wthw_qty_scheduled + $totals['scheduled']);
				break;
				case "blinds":
					$blinds_qty_unscheduled = ($blinds_qty_unscheduled + $totals['unscheduled']);
					$blinds_qty_scheduled = ($blinds_qty_scheduled + $totals['scheduled']);
				break;
				default:
					if($verbose){
						echo "<br>TYPE=".$totals['itemType']."<br>";
					}
					$catchall_qty_unscheduled = ($catchall_qty_unscheduled + $totals['unscheduled']);
					$catchall_qty_scheduled = ($catchall_qty_scheduled + $totals['scheduled']);
				break;
			}
			
		}
			
		if($verbose){
			echo "<hr>VARS:<br>cc_qty_unscheduled = ".$cc_qty_unscheduled."<br>";
			//echo "wt_qty_unscheduled = ".$wt_qty_unscheduled."<br>";
			echo "corn_qty_unscheduled = ".$corn_qty_unscheduled."<br>";
			echo "val_qty_unscheduled = ".$val_qty_unscheduled."<br>";
			echo "drape_qty_unscheduled = ".$drape_qty_unscheduled."<br>";
			echo "bs_qty_unscheduled = ".$bs_qty_unscheduled."<br>";
			echo "wthw_qty_unscheduled = ".$wthw_qty_unscheduled."<br>";
			echo "blinds_qty_unscheduled = ".$blinds_qty_unscheduled."<br>";
			echo "track_lf_unscheduled = ".$track_lf_unscheduled."<br>";
			echo "catchall_qty_unscheduled = ".$catchall_qty_unscheduled."<hr>";
		}

		if($cc_qty_unscheduled == 0 && $val_qty_unscheduled == 0 && $corn_qty_unscheduled == 0 && $drape_qty_unscheduled == 0 && $bs_qty_unscheduled == 0 && $wthw_qty_unscheduled == 0 && $blinds_qty_unscheduled == 0 && $track_lf_unscheduled == 0 && $catchall_qty_unscheduled == 0){
			$newSherryStatus="Fully Scheduled";

			if($verbose){
				echo "FULL SCHEDULE<hr><pre>";
				print_r($outstandingOrderItems);
				echo "</pre>";exit;
			}

		}elseif($cc_qty_unscheduled > 0 || $val_qty_unscheduled > 0 || $corn_qty_unscheduled > 0 || $drape_qty_unscheduled > 0 || $bs_qty_unscheduled >0 || $wthw_qty_unscheduled > 0 || $blinds_qty_unscheduled > 0 || $track_lf_unscheduled > 0 || $catchall_qty_unscheduled > 0){
			
			if($cc_qty_scheduled >0 || $val_qty_scheduled > 0 || $corn_qty_scheduled > 0 || $drape_qty_scheduled > 0 || $bs_qty_scheduled > 0 || $wthw_qty_scheduled > 0 || $blinds_qty_scheduled > 0 || $track_lf_scheduled > 0 || $catchall_qty_scheduled > 0){
				$newSherryStatus="Partially Scheduled";
			}else{
				$newSherryStatus="Not Scheduled";
			}	

			if($verbose){
				echo strtoupper($newSherryStatus)."<hr><pre>";
				print_r($outstandingOrderItems);
				echo "</pre>";exit;
			}


		}else{
			if($verbose){
				echo "NOOOOO<hr><pre>";
				print_r($outstandingOrderItems);
				echo "</pre>";exit;
			}
		}
		
		/*
		print_r($outstandingOrderItems);
		echo "<hr>";
		echo $newSherryStatus;
		exit;
		*/

		if(strlen(trim($newSherryStatus)) == 0){
			if($verbose){
			    echo "Could not determine a sherry status for order <b>".$orderID."</b><br>";
			}
		}else{
			
			$ordersTable=TableRegistry::get('WorkOrders');
			$thisOrderRow=$ordersTable->find('all',['conditions' => ['id' => $quoteID]])->first();

			//$thisOrderRow=$ordersTable->get($orderID);
			$thisOrderRow->sherry_status=$newSherryStatus;
				if($ordersTable->save($thisOrderRow)){
			    if($verbose){
				    echo "Saved new status <b>".$newSherryStatus."</b> for order <b>".$orderID."</b><Br>";
			    }
			}
		}

		
		return $newSherryStatus;
	}
	//($thisLineItem['quote_id'],$lineItemID,$thisLineItem['order_id'],'orders')
	/*public function updateLineItemOrderWorkOrder(,$quoteID,$lineItemId,$orderId,$table='orders'){

		$lineItems=$this->QuoteLineItems->find('all',['conditions' => ['id' => $lineItemId]])->first()->toArray();
		$workorderTable=TableRegistry::get('WorkOrderLineItems');
		$thisworkorderItemRow=$workorderTable->find('all',['conditions'=>['quote_line_item_id'=>$orderId]])->first()->toArray();
		$thisworkorderItemRow['qty']=$lineItems->qty;
		$thisworkorderItemRow->cost=$lineItems->cost;
		$thisworkorderItemRow->best_price=$lineItems->best_price;
		$workorderTable->save($thisworkorderItemRow);

		
		$orderTable=TableRegistry::get('OrderLineItems');
		$thisorderItemRow=$orderTable->find('all',['conditions'=>[ 'quote_line_item_id'=>$lineItemId]])->first()->toArray();
		$thisorderItemRow->qty=$lineItems->qty;
		$thisorderItemRow->cost=$lineItems->cost;
		$thisorderItemRow->best_price=$lineItems->best_price;
		$orderTable->save($thisorderItemRow);
		$this->logActivity($_SERVER['REQUEST_URI'],"Updated WorkOrder and Order lineItem in recalculatequoteadjustments method ".$itemId ." Order ID :" .$orderId );


	} */
	public function updatefabricinventoryfororder($orderID,$debug=false){
        
        $fabricsToUpdate=array();
        
        $order=$this->Orders->get($orderID)->toArray();
        
        //find its corresponding quote
        $quoteData=$this->Quotes->get($order['quote_id'])->toArray();
            
        //find line items
        $lineItems=$this->QuoteLineItems->find('all',['conditions' => ['quote_id' => $quoteData['id']]])->toArray();
        foreach($lineItems as $item){
                
            $yardsThisItem=0;
            $secondaryYardsThisItem=0;
            
            $thisFabricID=0;
            $isQuilted=false;
            
            $allMeta=$this->QuoteLineItemMeta->find('all',['conditions' => ['quote_item_id' => $item['id']]])->toArray();
            foreach($allMeta as $meta){
                
                switch($meta['meta_key']){
                    case 'fabricid':
                        $thisFabricID=$meta['meta_value'];
                    break;
                    case 'total-yards':
                    case 'total-yds':
                        $yardsThisItem=($yardsThisItem + floatval($meta['meta_value']));
                    break;
                    case 'yards-per-unit':
                    case 'yards-pu':
                    case 'yds-per-unit':
                        $secondaryYardsThisItem=($secondaryYardsThisItem + (floatval($meta['meta_value']) * floatval($item['qty'])) );
                    break;
                    case 'quilted':
                        if($meta['meta_value'] == '0'){
                            $isQuilted=false;
                        }elseif($meta['meta_value'] == '1'){
                            $isQuilted=true;
                        }
                        
                    break;
                }
                
            }
            
            
            if($thisFabricID > 0){
                
                if(!in_array($thisFabricID,$fabricsToUpdate)){
                    $fabricsToUpdate[]=$thisFabricID;
                }
                
            }
            
        }
        
        if($debug){
            return $fabricsToUpdate;
        }
        
        foreach($fabricsToUpdate as $updateFab){

            $newVals=$this->updatefabricinventorytotals($updateFab);
            
            $onHandTotal=(floatval($newVals['assumedOnHandQuilted'])+floatval($newVals['assumedOnHandUnquilted']));
            
            $availableTotal=((floatval($newVals['rollYardsQuilted']) + floatval($newVals['rollYardsUnquilted'])) - (floatval($newVals['deductionsQuilted']) + floatval($newVals['deductionsUnquilted'])) );
            
            $thisFabricUpdate=$this->Fabrics->get($updateFab);
            $thisFabricUpdate->committed_unquilted=$newVals['deductionsUnquilted'];
            $thisFabricUpdate->committed_quilted=$newVals['deductionsQuilted'];
            $thisFabricUpdate->on_hand_quilted=$newVals['assumedOnHandQuilted'];
            $thisFabricUpdate->on_hand_unquilted=$newVals['assumedOnHandUnquilted'];
            $thisFabricUpdate->on_hand_total=$onHandTotal;
            $thisFabricUpdate->available_total=$availableTotal;
            $thisFabricUpdate->last_inventory_recalc=time();
            
            $this->Fabrics->save($thisFabricUpdate);
            
            
        }
        
        
    }

	public function savetoWorkOrderItemsTables($newId) {
		$originalOrderItem = TableRegistry::get('OrderItems');
		$originalOrderItemData = $originalOrderItem->find('all',['conditions' => ['id'=>$newId]])->first()->toArray();
		$workOrderItemsTable=TableRegistry::get('WorkOrderItems');
		//$copyOrderItem = $workOrderItemsTable->newEntity();
       // $copyOrderItem = $workOrderItemsTable->patchEntity($copyOrderItem, $originalOrderItemData);
		$newOrderLineItem=$workOrderItemsTable->newEntity();
		//$newOrderLineItem->id=$originalOrderItemData['id'];
				$newOrderLineItem->order_id=$originalOrderItemData['order_id'];
				$newOrderLineItem->quote_line_item_id=$originalOrderItemData['quote_line_item_id'];
				$newOrderLineItem->quote_id=$originalOrderItemData['quote_id'];
        $workOrderItemsTable->save($newOrderLineItem);
	}
	public function savetoWorkOrderItemStatusTables($newId) {
		$orderItemStatusTable=TableRegistry::get('OrderItemStatus');

		$workOrderItemStatusTable=TableRegistry::get('WorkOrderItemStatus');
		
		$originalOrderItemStatus = $orderItemStatusTable->find('all',['conditions' => ['id'=>$newId]])->first()->toArray();
		$WorkOrderItemStatusTable=TableRegistry::get('WorkOrderItemStatus');
		$copyOrderItemStatus = $workOrderItemStatusTable->newEntity();
        $copyOrderItemStatus = $workOrderItemStatusTable->patchEntity($copyOrderItemStatus, $originalOrderItemStatus);
         ///unset($originalOrderItemStatus['id']);
         // Unset or modify all others what you need
		 $WorkOrderItemStatusTable->save($copyOrderItemStatus);
	}

	public function updatefabricinventorytotals($fabricID,$verbose=false){
        
        
        //read all Active rolls
        $rollYardsQuilted=0;
        $rollYardsUnquilted=0;
        
        $activeRolls=$this->MaterialInventory->find('all',['conditions' => ['material_type' => 'Fabrics', 'material_id' => $fabricID, 'status' => 'Active']])->toArray();
        foreach($activeRolls as $roll){
            if($roll['quilted']==1){
                $rollYardsQuilted=($rollYardsQuilted + floatval($roll['yards_received']));
            }else{
                $rollYardsUnquilted=($rollYardsUnquilted + floatval($roll['yards_received']));
            }
        }
        
        //read the Cutting Log
        
        
        //read all Active orders
        $deductionsQuilted=0;
        $deductionsUnquilted=0;
        
        //'Needs Line Items','Pre-Production','Production','On Hold','Complete','Returned','Canceled','Shipped','Editing'
        $activeOrders=$this->Orders->find('all',['conditions' => ['status NOT IN' => ['Canceled','Complete','Returned','On Hold','Shipped']]])->toArray();
        foreach($activeOrders as $order){
            
            //find its corresponding quote
            $quoteData=$this->Quotes->get($order['quote_id'])->toArray();
            
            //find line items
            $lineItems=$this->QuoteLineItems->find('all',['conditions' => ['quote_id' => $quoteData['id']]])->toArray();
            foreach($lineItems as $item){
                
                $yardsThisItem=0;
                $secondaryYardsThisItem=0;
                
                $thisFabricID=0;
                $isQuilted=false;
                
                $allMeta=$this->QuoteLineItemMeta->find('all',['conditions' => ['quote_item_id' => $item['id']]])->toArray();
                foreach($allMeta as $meta){
                    
                    switch($meta['meta_key']){
                        case 'fabricid':
                            $thisFabricID=$meta['meta_value'];
                        break;
                        case 'total-yards':
                        case 'total-yds':
                            $yardsThisItem=($yardsThisItem + floatval($meta['meta_value']));
                        break;
                        case 'yards-per-unit':
                        case 'yards-pu':
                        case 'yds-per-unit':
                            $secondaryYardsThisItem=($secondaryYardsThisItem + (floatval($meta['meta_value']) * floatval($item['qty'])) );
                        break;
                        case 'quilted':
                            if($meta['meta_value'] == '0'){
                                $isQuilted=false;
                            }elseif($meta['meta_value'] == '1'){
                                $isQuilted=true;
                            }
                            
                        break;
                    }
                    
                }
                
                /*
                if($yardsThisItem != $secondaryYardsThisItem){
                    //Yards Per Unit X Qty doesnt match  TOTAL YARDS value
                    
                }
                */
                if($thisFabricID == $fabricID && $thisFabricID > 0){
                    
                    if($isQuilted){
                        $deductionsQuilted = ($deductionsQuilted + $yardsThisItem);
                    }else{
                        $deductionsUnquilted = ($deductionsUnquilted + $yardsThisItem);
                    }
                }
                
            }

            
        }
        
        return array(
            'rollYardsQuilted'=>$rollYardsQuilted,
            'rollYardsUnquilted' => $rollYardsUnquilted,
            'deductionsQuilted'=>$deductionsQuilted,
            'deductionsUnquilted' => $deductionsUnquilted,
            'assumedOnHandQuilted'=>($rollYardsQuilted-$deductionsQuilted),
            'assumedOnHandUnquilted' => ($rollYardsUnquilted-$deductionsQuilted)
            );
        
    }


	public function saveReleasedToManufacture($orderItemID,$newval=false){
		$this->autoRender=false;
		//Quotes line item update
		$currentQuoteItem=$this->QuoteLineItems->find('all',['conditions'=>['id'=>$orderItemID]])->first()->toArray();
		
		$currentQuoteItem['released_to_manufacture']=$newval;
	    $currentQuoteItem->save();


		//Orders Update for released_to_manufacture
	    $currentItem=$this->OrderItems->find('all',['conditions'=>['quote_line_item_id'=>$orderItemID]])->first()->toArray();
		 
	    $thisOrder=$this->Orders->find('all',['conditions'=>['id'=>$currentItem['order_id']]])->first()->toArray();
	    $quoteItem=$this->OrderLineItems->find('all',['conditions'=>['quote_line_item_id'=>$currentItem['quote_line_item_id']]])->first()->toArray();
	    
	    if(!$newval){
	        if($currentItem['released_to_manufacture'] == 0){
	            $newval=1;
	        }else{
	            $newval=0;
	        }
	    }
	    
	    $update=$this->OrderItems->find('all',['conditions'=>['quote_line_item_id'=>$orderItemID]])->first();
	    $update->released_to_manufacture=$newval;
	    $this->OrderItems->save($update);
	    
	    if($newval==1){
	        $this->logActivity($_SERVER['REQUEST_URI'],'Released Line '.$quoteItem['line_number'].' of WorkOrder# '.$thisOrder['order_number'].' to Manufacture');
	    }else{
	        $this->logActivity($_SERVER['REQUEST_URI'],'Unreleased Line '.$quoteItem['line_number'].' of WorkOrder# '.$thisOrder['order_number'].' from Manufacture');
	    }
	    
	    echo "OK";exit;
	}
	/*** PPSASCRUM-29 end */
	/**PPSA-1 end */
}