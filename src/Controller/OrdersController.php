<?php

namespace App\Controller;

use Cake\Datasource\ConnectionManager;

use App\Controller\AppController;

use Cake\Event\Event;
    
use Cake\Utility\Security;

use Cake\Core\Configure;

use Cake\ORM\TableRegistry;

use Cake\Mailer\Email;

use Cake\ORM\Table;
use Cake\Datasource\Exception\RecordNotFoundException;


/**

 * Static content controller

 *

 * This controller will render views from Template/Pages/

 *

 * @link http://book.cakephp.org/3.0/en/controllers/pages-controller.html

 */

class OrdersController extends AppController{

		

	public function initialize() {

        parent::initialize();

        $this->loadComponent('RequestHandler');
		$this->loadComponent('Security');
		$this->loadModel('UserTypes');
		$this->loadModel('Users');
		$this->loadModel('Customers');
		$this->loadModel('CustomerContacts');
		$this->loadModel('Products');
		$this->loadModel('Quotes');
		$this->loadModel('QuoteLineItems');
		$this->loadModel('QuoteLineItemMeta');
		$this->loadModel('QuoteLineItemNotes');
		$this->loadModel('Orders');
		$this->loadModel('OrderItems');
		$this->loadModel('OrderItemStatus');
		$this->loadModel('Calculators');
		$this->loadModel('Fabrics');
		$this->loadModel('MaterialPurchases');
		$this->loadModel('MaterialInventory');
		$this->loadModel('MaterialUsages');
		$this->loadModel('QuiltingOps');
		$this->loadModel('QuoteTypes');
		$this->loadModel('Vendors');
		$this->loadModel('BluesheetTemplates');
		$this->loadModel('SherryCache');
		$this->loadModel('Projects');
		$this->loadModel('Linings');
		$this->loadModel('SherryBatches');
		$this->loadModel('ShippingMethods');
		$this->loadModel('SherryBatchNotes');
		$this->loadModel('QbImportLog');
		$this->loadModel('Shipments');
		$this->loadModel('WarehouseLocations');
		
		$this->loadModel('InventoryCache');
		$this->loadModel('InventoryRollsCache');
	    $this->loadModel('InventoryActiveorderCache');
	    $this->loadModel('InventoryQuoteCache');
	    
	    $this->loadModel('SherryBatchBoxes');
	    $this->loadModel('SherryBatchBoxContents');
	    $this->loadModel('QuoteBomPurchasingNotes');
	    
	    	/**PPSASCRUM-7 start **/
		$this->loadModel('ShipTo');
		$this->loadModel('Facility');
		/**PPSACRUM-7 end **/

		/**PPSA-1 start */	
		$this->loadModel('WorkOrders');	
		$this->loadModel('WorkOrderItems');	
		$this->loadModel('WorkOrderItemStatus');	
		$this->loadModel('WorkOrderLineItems');	
		$this->loadModel('WorkOrderLineItemMeta');	
		$this->loadModel('OrderLineItemMeta');	
		$this->loadModel('OrderLineItems');	
		$this->loadModel('OrderLineItemNotes');
		$this->loadModel('QuoteNotes');	
		$this->loadModel('LibraryImages');
		$this->loadModel('WorkOrderLineItemNotes');
		/**PPSA-1 end */
	}

	

		

	public function beforeFilter(Event $event) {

        parent::beforeFilter($event);
/**PPSACRUM-7 Start **/
		if($this->request->action=='getorderslist' || $this->request->action='getunscheduled' || $this->request->action=='getsherryschedule' || $this->request->action=='packingslipform' || $this->request->action=='addnew' || $this->request->action=='ordermaterial'|| $this->request->action=='shipto' ||  $this->request->action=='facility'|| $this->request->action=='getshiptoslist'|| $this->request->action=='getfacilitylist'){

			$this->Security->config('unlockedActions', ['getorderslist','addnew','ordermaterial','getsherryschedule','getunscheduled','facility','shipto','getshiptoslist','getfacilitylist']);
			$this->eventManager()->off($this->Csrf);
			$this->eventManager()->off($this->Security);
		}
/**PPSACRUM-7 end **/

    }




   

	public function index(){

		//get counts for all tabs

		$allOrders=$this->Orders->find('all')->toArray();

		$statusCounts=array(
		    
		    'All Active' => 0,
		    
		    'All Orders' => 0,

			'Complete' => 0,

			'Returned' => 0,

			'Canceled' => 0,

			'Shipped' => 0,

			'Due Soon' => 0,

			'Past Due' => 0,

			'Needs Line Items' => 0

		);

		

		$allcompanies=$this->Customers->find('all',['order'=>['company_name'=>'asc']])->toArray();

		$this->set('allcompanies',$allcompanies);

		

		$allfabrics=$this->Fabrics->find('all',['order'=>['fabric_name'=>'asc','color'=>'asc']])->toArray();

		$this->set('allfabrics',$allfabrics);

		

		$allusers=$this->Users->find('all',['order'=>['last_name'=>'asc','first_name'=>'asc']])->toArray();

		$this->set('allusers',$allusers);

		

		foreach($allOrders as $order){

			$statusCounts[$order['status']]++;
			$statusCounts['All Orders']++;

            if($order['status'] == 'Pre-Production' || $order['status'] == 'Production'){
                $statusCounts['All Active']++;
            }

			if(($order['status'] == 'Pre-Production' || $order['status'] == 'Production') && $order['due'] < time() && $order['due'] >0){

				$statusCounts['Past Due']++;

			}

			if(($order['status'] == 'Pre-Production' || $order['status'] == 'Production') && $order['due'] > time() && $order['due'] <= (time()+432000)){

				$statusCounts['Due Soon']++;

			}
			

		}

		

		$this->set('statusCounts',$statusCounts);

		

	}

	

	public function add(){

		

	}

	

	

	public function getorderslist($tab='allactive'){

		$orders=array();

		$overallTotalRows=$this->Orders->find('all',['conditions'=>['status NOT IN' => ['Shipped','Needs Line Items','Canceled']]])->count();

		$conditions=array();

		

		if(isset($this->request->data['start'])){

			$start=$this->request->data['start'];

		}else{

			$start=0;

		}

		

		if(isset($this->request->data['length'])){

			$limit=$this->request->data['length'];

		}else{

			$limit=25;

		}

		

		if(isset($this->request->data['draw'])){

			$draw=$this->request->data['draw'];

		}else{

			$draw=1;

		}

		

		

		if(isset($this->request->data['search']['value']) && strlen(trim($this->request->data['search']['value'])) >0){

			//this is a search

			$conditions['OR']=array();

			

			//look up customers

			$customerSearch=$this->Customers->find('all',['conditions'=>['company_name LIKE' => '%'.$this->request->data['search']['value'].'%']])->toArray();

			if(count($customerSearch) == 1){

				$conditions['OR'] += array('customer_id' => $customerSearch[0]['id']);

			}elseif(count($customerSearch) >1){

				$customerIDs=array();

				foreach($customerSearch as $customerRes){

					$customerIDs[]=$customerRes['id'];

				}

				$conditions['OR'] += array('customer_id IN' => $customerIDs);

			}

			

			

			$quoteIDfilter=array();

			

			//search quote titles

			$quoteSearch=$this->Quotes->find('all',['conditions' => ['title LIKE' => '%'.$this->request->data['search']['value'].'%']])->toArray();

			foreach($quoteSearch as $quoteRes){

				$quoteIDfilter[]=$quoteRes['id'];

			}

			

			//search quote numbers

			$quoteNumSearch=$this->Quotes->find('all',['conditions' => ['quote_number LIKE' => '%'.$this->request->data['search']['value'].'%']])->toArray();

			foreach($quoteNumSearch as $quoteRes){

				$quoteIDfilter[]=$quoteRes['id'];

			}

			

			if(count($quoteIDfilter) == 1){

				$conditions['OR'] += array('quote_id'=>$quoteIDfilter[0]);

			}elseif(count($quoteIDfilter) > 1){

				$conditions['OR'] += array('quote_id IN' => $quoteIDfilter);

			}

			

			

			$conditions['OR'] += array('po_number LIKE' => '%'.$this->request->data['search']['value'].'%');

			//$conditions['OR'] += array('order_number LIKE' => '%'.$this->request->data['search']['value'].'%');
			$conditions['OR'] += array('CAST(order_number AS CHAR(100)) LIKE' => '%'.$this->request->data['search']['value'].'%');

			$conditions['OR'] += array('facility LIKE' => '%'.$this->request->data['search']['value'].'%');

				

			//$conditions['OR'] += array('product_name LIKE'=>'%'.trim($this->request->data['search']['value']).'%');

		}

		

		

		switch($tab){

            case 'allactive':
                $conditions += array('status IN' => array('Pre-Production','Production','On Hold'));
            break;
            
            case 'allorders':
                
            break;
            
			case "canceled":
				$conditions += array('status' => 'Canceled');
			break;

			case "completed":
				$conditions += array('status' => 'Shipped');
			break;

			case "preproduction":
				$conditions += array('status' => 'Pre-Production');
			break;

			case "production":
				$conditions += array('status' => 'Production');
			break;

			case "duesoon":
				$conditions += array('status IN' => array('Pre-Production','Production'), 'due <=' => (time()+432000), 'due >' => time());
			break;

			case "pastdue":
				$conditions += array('status IN' => array('Pre-Production','Production'), 'due <=' => time(), 'due >' => 1000);
			break;

			case "postproduction":
				$conditions += array('status' => 'Complete');
			break;

			case "needlineitems":
				$conditions += array('status' => 'Needs Line Items');
			break;

			case "advancedsearch":


				$totalactiveparams=0;
				if(strlen(trim($this->request->data['identifiers'])) >0){
					//we are looking for identifiers
					$totalactiveparams++;
				}


				if(strlen(trim($this->request->data['quotenumber'])) > 0){
					//we are looking for a quote number
					
					//lookup quote numbers
					$quoteLookup=$this->Quotes->find('all',['conditions' => ['quote_number LIKE' => '%'.$this->request->data['quotenumber'].'%']])->toArray();
					$quoteIDs=array();
					foreach($quoteLookup as $quoteRow){
						$quoteIDs[]=$quoteRow['id'];
					}

					if(count($quoteIDs) > 1){
						$conditions += array('quote_id IN' => $quoteIDs);
						$totalactiveparams++;
					}elseif(count($quoteIDs) == 1){
						$conditions += array('quote_id' => $quoteIDs[0]);
						$totalactiveparams++;
					}
				}
				

				if(strlen(trim($this->request->data['ordernumber'])) >0){
					//we are looking for an order number
					$conditions += array('order_number LIKE' => '%'.$this->request->data['ordernumber'].'%');
					$totalactiveparams++;
				}
				

				if(strlen(trim($this->request->data['ponumber'])) >0){
					//we are looking for a PO Number
					$conditions += array('po_number LIKE' => '%'.$this->request->data['ponumber'].'%');
					$totalactiveparams++;
				}


				if(strlen(trim($this->request->data['daterangestart'])) >0){
					//we are looking for a date
					if(strlen(trim($this->request->data['daterangeend'])) >0){
						//we are looking for a date range
						$conditions += array('created >=' => strtotime($this->request->data['daterangestart'].' 00:00:00'));
						$conditions += array('created <=' => strtotime($this->request->data['daterangeend'].' 23:59:59'));
						$totalactiveparams++;
						$totalactiveparams++;
					}else{
						//we are just looking for a specific date
						$conditions += array('created >=' => strtotime($this->request->data['daterangestart'].' 00:00:00'));
						$conditions += array('created <=' => strtotime($this->request->data['daterangestart'].' 23:59:59'));
						$totalactiveparams++;
					}
				}

				
				if(is_array($this->request->data['customerid']) && count($this->request->data['customerid']) >1 ){
					//we are looking for a specific customer
					$conditions += array('customer_id IN' => $this->request->data['customerid']);
					$totalactiveparams++;
				}elseif(is_array($this->request->data['customerid']) && count($this->request->data['customerid']) ==1 ){
					//we are looking for a specific customer
					$conditions += array('customer_id' => $this->request->data['customerid'][0]);
					$totalactiveparams++;
				}


				if(strlen(trim($this->request->data['contactname'])) >0){
					//we are looking for items attributed to a specific contact within a customer
					$contactLookup=$this->CustomerContacts->find('all',['conditions' => ['CONCAT(first_name,\' \',last_name) LIKE' => '%'.$this->request->data['contactname'].'%']])->toArray();
					$contactIDs=array();
					foreach($contactLookup as $contactRow){
						$contactIDs[]=$contactRow['id'];
					}
					if(count($contactIDs) > 1){
						$conditions += array('contact_id IN' => $contactIDs);
						$totalactiveparams++;
					}elseif(count($contactIDs) == 1){
						$conditions += array('contact_id' => $contactIDs[0]);
						$totalactiveparams++;
					}
					
				}

				
				if(strlen(trim($this->request->data['phonenumber'])) >0){
					//we are looking for items attributed to a specific phone number
					//search Customers db
					$customerPhoneLookup=$this->Customers->find('all',['conditions' => ['phone LIKE' => '%'.$this->request->data['phonenumber'].'%']])->toArray();
					if(count($customerPhoneLookup) >0){
						//lets do conditions by customer id
						$customerIDs=array();
						foreach($customerPhoneLookup as $customerRow){
							$customerIDs[]=$customerRow['id'];
						}

						if(count($customerIDs) >1){
							$conditions += array('customer_id IN' => $customerIDs);
							$totalactiveparams++;
						}elseif(count($customerIDs) == 1){
							$conditions += array('customer_id' => $customerIDs[0]);
							$totalactiveparams++;
						}
						
					}else{

						//search Customer Contacts  db
						$contactPhoneLookup=$this->CustomerContacts->find('all',['conditions' => ['phone LIKE' => '%'.$this->request->data['phonenumber'].'%']])->toArray();
						if(count($contactPhoneLookup) > 0){
							//lets do conditions by contact id
							$contactIDs=array();
							foreach($contactPhoneLookup as $contactRow){
								$contactIDs[]=$contactRow['id'];
							}

							if(count($contactIDs) >1){
								$conditions += array("contact_id IN" => $contactIDs);
								$totalactiveparams++;
							}elseif(count($contactIDs) == 1){
								$conditions += array("contact_id" => $contactIDs[0]);
								$totalactiveparams++;
							}
							
						}
					}

					
				}

				

				if(strlen(trim($this->request->data['fabricids'])) >0){
					//we are looking for orders containing items with a specific fabric
					$totalactiveparams++;
				}

				
				if(strlen(trim($this->request->data['lineitemtype'])) >0){
					//we are looking for orders containing a specific type of line item
					
					if(strlen(trim($this->request->data['lineitemkeywords'])) >0){
						//we are looking for specific keywords within this specific type of line item
						$totalactiveparams++;
						$totalactiveparams++;
					}else{
						//we are just globally looking for this type of item
						$totalactiveparams++;
					}
				}

				
				if(strlen(trim($this->request->data['ordertotalmin'])) >0){
					//we are looking for orders with a specific total or total within a range
					if(strlen(trim($this->request->data['ordertotalmax'])) >0){
						//we are looking for a range
						$conditions += array('order_total >=' => floatval($this->request->data['ordertotalmin']));
						$totalactiveparams++;
						$conditions += array('order_total <=' => floatval($this->request->data['ordertotalmax']));
						$totalactiveparams++;
					}else{
						//we are looking for a specific total
						$conditions += array('order_total >=' => floatval($this->request->data['ordertotalmin']));
						$totalactiveparams++;
					}
				}

				

				if(is_array($this->request->data['userid']) && count($this->request->data['userid']) >1){
					//we ar elooking for orders processed by a specific HCI user
					$conditions += array('user_id IN' => $this->request->data['userid']);
					$totalactiveparams++;
				}elseif(is_array($this->request->data['userid']) && count($this->request->data['userid']) == 1){
					//we ar elooking for orders processed by a specific HCI user
					$conditions += array('user_id' => $this->request->data['userid'][0]);
					$totalactiveparams++;
				}



				if(count($this->request->data['orderstatus']) > 1){
					//filter the results to only include the selected Status(es)
					$conditions += array('status IN' => $this->request->data['orderstatus']);
					$totalactiveparams++;
				}elseif(count($this->request->data['orderstatus']) == 1){
					//filter the results to only include the selected Status(es)
					$conditions += array('status' => $this->request->data['orderstatus'][0]);
					$totalactiveparams++;
				}

				
				if(count($this->request->data['orderstage']) > 1){
				    $conditions += array('stage IN' => $this->request->data['orderstage']);
				    $totalactiveparams++;
				}elseif(count($this->request->data['orderstage']) == 1){
				    $conditions += array('stage' => $this->request->data['orderstage'][0]);
				    $totalactiveparams++;
				}
				
				//mail("dallasisp@gmail.com","raw data",print_r($conditions,1)."\n\n\n----------------------------------\n\n\n".print_r($_REQUEST,1));

			break;

			default:

				//$conditions += array('status NOT IN' => array('Canceled','Shipped','Needs Line Items'));
				//$conditions += array('status NOT IN' => array('Canceled','Shipped','Needs Line Items'));

			break;

		}

		

		

		if($tab=='advancedsearch' && $totalactiveparams==0){

			$getOrders=array();

			$totalFilteredRows=0;

		}else{
		    
		    
		    switch($this->request->data['order'][0]['column']){
		        case 1:
		        default:
		            $orderby=array('order_number' => $this->request->data['order'][0]['dir']);
		        break;
		        /*case 2:
		            $orderby=array('customer' => $this->request->data['order'][0]['dir']);
		        break;
		        */
		        case 3:
		            $orderby=array('po_number' => $this->request->data['order'][0]['dir']);
		        break;
		        case 4:
		            $orderby=array('created' => $this->request->data['order'][0]['dir']);
		        break;
		        case 9:
		            $orderby=array('due' => $this->request->data['order'][0]['dir']);
		        break;
		    }
		    

			$getOrders=$this->Orders->find('all',['conditions'=>$conditions, 'order' => $orderby])->offset($start)->limit($limit)->hydrate(false)->toArray();

			$totalFilteredRows=$this->Orders->find('all',['conditions'=>$conditions])->count();

		}


		foreach($getOrders as $order){


			$customerData=$this->Customers->get($order['customer_id'])->toArray();

			$userData=$this->Users->get($order['user_id'])->toArray();

// 			$quoteData=$this->Quotes->get($order['quote_id'])->toArray();

// 			PPSASCRUM - 284 for datatable issue, no quote id in table
            try {
                    $quoteData = $this->Quotes->get($order['quote_id'])->toArray();
                } catch (\Cake\Datasource\Exception\RecordNotFoundException $e) {
                    $this->Flash->error(__('Quote not found for ID: ') . h($order['quote_id']));
                    // continue; 
                }
			

			$orderTitle='';

			if(strlen(trim($quote['title'])) >0){

				$orderTitle .= "<b>".$quote['title']."</b><br>";

			}

			$orderTitle .= $customerData['company_name'];
            /**PPSASCRUM-3 start **/
			if($order['due'] < time() && $order['due'] > 1000) {
                $orderTitle .=  ($customerData['on_credit_hold']) ? '<div><span style="color: white;"> On Credit Hold</span></div> ' : '';
            } else {
                $orderTitle .=  ($customerData['on_credit_hold']) ? '<div><span style="color: red;"> On Credit Hold</span></div> ' : '';
}
            /**PPSASCRUM-3 end **/
			

			

			$numitems=0;

			//$quoteitems=$this->QuoteLineItems->find('all',['conditions'=>['quote_id'=>$order['quote_id']]])->toArray();
			$quoteitems=$this->OrderLineItems->find('all',['conditions'=>['quote_id'=>$order['quote_id']]])->toArray();

			foreach($quoteitems as $quoteitem){

				$numitems=($numitems+$quoteitem['qty']);

			}

			

			$workorders='<a href="/orders/workorder/create/'.$order['id'].'/">+ Create W/O</a>';

			$shipments='';

			$invoices='';

			

			$orderstatus="<div class=\"orderlabel\">".ucfirst($order['status']);

			if($order['status'] == "Canceled"){
				$orderstatus .= "<br><em>REASON:<br>".$order['cancelreason']."</em>";
			}

			$addclasses='';
			
			if($order['status'] == 'Pre-Production' || $order['status'] == 'Production' || $order['status'] == 'On Hold'){
			    $addclasses .= " activeorder";
			}

			

			if($order['due'] > time() && $order['due'] <= (time()+432000)){

				$addclasses .= " duesoon";

				$orderstatus .= " (DUE SOON)";

			}

			if($order['due'] < time() && $order['due'] > 1000){

				$addclasses .= " pastdue";

				$orderstatus .= " (PAST DUE)";

			}

			if($order['status']=="On Hold"){

				$addclasses .= " onhold";

			}

			if($order['status']=="Complete" || $order['status'] == 'Shipped'){

				$addclasses .= " completed";

			}

			if($order['status'] == 'Canceled'){

				$addclasses .= " canceled";

			}

            if($order['status'] == 'Editing'){
                $addclasses .= ' editinglock';
            }
			

			$orderstatus .= "</div>";

			$completedpercent=$this->getOrderProgressPercent($order['id']);

			$orderstatus .= "<div class=\"progressbar\"><div class=\"progresscompleted\" style=\"width:".$completedpercent['percent']."%;\"></div></div>

			<div class=\"percentlabel\">".$completedpercent['completed']." / ".$completedpercent['total']." (".$completedpercent['percent']."%) Completed</div>";

			

			$totals='';

		/*	if($order['cc_dollar'] >0){

				$totals .= 'CC: $'.number_format($order['cc_dollar'],2,'.',',').'<br>';

			}

			if($order['track_dollar'] > 0){

				$totals .= 'TRK: $'.number_format($order['track_dollar'],2,'.',',').'<br>';

			}

			if($order['bs_dollar'] > 0){

				$totals .= 'BS: $'.number_format($order['bs_dollar'],2,'.',',').'<br>';

			}

			if($order['drape_dollar'] >0){

				$totals .= 'DRAPERIES: $'.number_format($order['drape_dollar'],2,'.',',').'<br>';

			}

			if($order['val_dollar'] >0){

				$totals .= 'VAL: $'.number_format($order['val_dollar'],2,'.',',').'<br>';

			}

			if($order['corn_dollar'] >0){

				$totals .= 'CORN: $'.number_format($order['corn_dollar'],2,'.',',').'<br>';

			}

			if($order['wt_hw_dollar'] >0){

				$totals .= 'WTHW: $'.number_format($order['wt_hw_dollar'],2,'.',',').'<br>';

			}

			if($order['blinds_dollar'] >0){

				$totals .= 'B&amp;S: $'.number_format($order['blinds_dollar'],2,'.',',').'<br>';

			}

			if($order['fab_dollars'] >0){

				$totals .= 'FAB: $'.number_format($order['fab_dollars'],2,'.',',').'<br>';

			}

			if($order['measure_dollars'] >0){

				$totals .= 'MEAS: $'.number_format($order['measure_dollars'],2,'.',',').'<br>';

			}

			if($order['install_dollars'] >0){

				$totals .= 'INST: $'.number_format($order['install_dollars'],2,'.',',').'';

			}*/
			
           // PPSASCRUM-183 start 
            if($order['hw_dollar_total'] >0){

				$totals .= 'HW : $'.number_format($order['hw_dollar_total'],2,'.',',').'<br>';

			}
            if($order['hwt_dollar_total'] >0){

				$totals .= 'HWT (B & S)  : $'.number_format($order['hwt_dollar_total'],2,'.',',').'<br>';

			}
           if($order['swt_dollar_total'] >0){

				$totals .= 'SWT : $'.number_format($order['swt_dollar_total'],2,'.',',').'<br>';

			}
             if($order['cc_dollar_total'] >0){

				$totals .= 'CC : $'.number_format($order['cc_dollar_total'],2,'.',',').'<br>';

			}
           if($order['bedding_dollar_total'] >0){

				$totals .= 'BEDDING : $'.number_format($order['bedding_dollar_total'],2,'.',',').'<br>';

			}
            if($order['services_dollar_total'] >0){

				$totals .= 'SERVICES : $'.number_format($order['services_dollar_total'],2,'.',',').'<br>';

			}
            if($order['misc_dollar_total'] >0){

				$totals .= 'MISC : $'.number_format($order['misc_dollar_total'],2,'.',',').'';

			}
			
			 // PPSASCRUM-183 end 
			$totals .= "<hr style=\"margin:0; height:4px;\" />";

			$totals .= '<strong>$'.number_format($order['order_total'],2,'.',',').'</strong>';

			

			if($order['due'] > 1000){

				$duedate=date('n/j/Y',$order['due']);

			}else{

				$duedate='---';

			}

			$projectname='';

			if($quoteData['project_id'] > 0){
				$projectData=$this->Projects->get($quoteData['project_id'])->toArray();
				$projectname=$quoteData['title'].'<br><span style="font-size:10px; color:blue;"><b>Project: '.$projectData['title'].'</b></span>';
			}else{
				$projectname=$quoteData['title'];
			}

			
			if($order['shipping_method_id'] > 0){
				$thisShipMethodData=$this->ShippingMethods->get($order['shipping_method_id'])->toArray();
				$thisShipMethod=$thisShipMethodData['name'];
			}else{
				$thisShipMethod='---';
			}
			
			
			$thisProjectManagerName='';
			
			if($order['project_manager_id'] > 0){
			    $thisPMUser=$this->Users->get($order['project_manager_id'])->toArray();
			    $thisProjectManagerName='<br>'.$thisPMUser['first_name'].' '.$thisPMUser['last_name'];
			}


			if($order['status'] == 'Needs Line Items'){

                if(!is_null($order['type_id']) && $order['type_id'] > 0){
				    $thisType=$this->QuoteTypes->get($order['type_id'])->toArray();
				    $thisOrderType='<br>'.$thisType['type_label'];
				}else{
				    $thisOrderType='';
				}

				$orders[]=array(

					'DT_RowId'=>'row_'.$order['id'],

					'DT_RowClass'=>'rowtest needlineitems'.$addclasses,

					'0' => '<a href="/orders/editlines/'.$order['id'].'/order"/><img src="/img/vieworder.png" alt="Line Items" title="Line Items" /></a> <a href="/orders/edit/'.$order['id'].'"><img src="/img/edit.png" alt="Edit Order Details" title="Edit Order Details" /></a> <a href="/orders/cancelorder/'.$order['id'].'"><img src="/img/cancel.png" alt="Cancel This Order" title="Cancel This Order" /></a> <a href="/quotes/clonequote/'.$order['quote_id'].'/0/1/"><img src="/img/clone.png" alt="Clone This Order to New Draft Quote" title="Clone This Order to New Draft Quote" /></a> ',

					'1' => $order['order_number'],

					'2' => $orderTitle,

					'3' => $order['po_number'].$thisProjectManagerName,

					'4' => date('n/j/Y - g:iA',$order['created']),

					'5' => $projectname,

					'6' => $order['facility'].$thisOrderType,

					'7' => 'No Line Items Entered',
					
                    '8' => $order['stage'],

					'9' => $duedate,

					'10' => $thisShipMethod,

					'11' => '$'.number_format($order['order_total'],2,'.',','),

					'12' => '---',

					'13' => '---',

					'14' => '---',

					'15' => '---',

					'16' => '---',

					'17' => '---',

					'18' => '---',
					
					'19' => '---',
					
					'20' => '---',

					'21' => '---',

					'22' => '---',

					'23' => '---',

					'24' => '---'

				);

			}else{

				if($order['status'] == 'Canceled'){
					$actionsout='<em>N/A</em>';
				}else{

					/*see if this has already been imported to qb
					$qblogcheck=$this->QbImportLog->find('all',['conditions'=>['order_id' => $order['id'],'result'=>'success']])->toArray();

					if(count($qblogcheck) == 0){
						$ifsendtoqb=' <a href="/orders/sendtoquickbooks/'.$order['id'].'"><img src="/img/qb3.png" width="16" height="16" title="Export to Quickbooks" alt="Export to Quickbooks" /></a>';
					}else{
						$ifsendtoqb='';
					}
                    */
                    
                    if($order['status'] == 'Shipped'){
                        $ifmarkcompleted=' <a href="/orders/revertcompletion/'.$order['id'].'"><img src="/img/Undo-icon.png" alt="Revert Order to In-Production" title="Revert Order to In-Production" /></a>';
                    }else{
                        $ifmarkcompleted=' <a href="/orders/markcomplete/'.$order['id'].'"><img src="/img/completed.png" alt="Mark Order Completed" title="Mark Order Completed" /></a>';
                    }
                    
					$actionsout = '<a href="/orders/editlines/'.$order['id'].'/order"/><img src="/img/vieworder.png" alt="Line Items" title="Line Items" /></a> <a href="/orders/edit/'.$order['id'].'"><img src="/img/edit.png" alt="Edit Order Details" title="Edit Order Details" /></a> <a href="/orders/cancelorder/'.$order['id'].'"><img src="/img/cancel.png" alt="Cancel This Order" title="Cancel This Order" /></a> <a href="/orders/vieworderschedule/'.$order['id'].'" target="_blank"><a href="/quotes/clonequote/'.$order['quote_id'].'/0/1/"><img src="/img/clone.png" alt="Clone This Order to New Draft Quote" title="Clone This Order to New Draft Quote" /></a>'.$ifsendtoqb.$ifmarkcompleted;
				}


                if(!is_null($order['type_id']) && $order['type_id'] > 0){
				    $thisType=$this->QuoteTypes->get($order['type_id'])->toArray();
				    $thisOrderType='<br>'.$thisType['type_label'];
				}else{
				    $thisOrderType='';
				}
                
				
				$orders[]=array(

					'DT_RowId'=>'row_'.$order['id'],

					'DT_RowClass'=>'rowtest'.$addclasses,

					'0' => $actionsout,

					'1' => $order['order_number'],

					'2' => $orderTitle,

					'3' => $order['po_number'].$thisProjectManagerName,

					'4' => date('n/j/Y - g:iA',$order['created']),

					'5' => $projectname,

					'6' => $order['facility'].$thisOrderType,

					'7' => $orderstatus,
					
                    '8' => $order['stage'],
					'9' => $duedate,

					'10' => $thisShipMethod,

					'11' => $totals,

					'12' => $order['cc_qty'],

					'13' => $order['cc_lf'],

					'14' => $order['track_lf'],

					'15' => $order['bs_qty'],

					'16' => $order['drape_qty'],

					'17' => $order['drape_widths'],

					'18' => $order['val_qty'],

					'19' => $order['val_lf'],
					
					'20' => $order['corn_qty'],
					
					'21' => $order['corn_lf'],

					'22' => $order['wt_hw_qty'],

					'23' => $order['blinds_qty'],

					'24' => $shipments

				);

			}

		}

		

		$this->set('draw',$draw);

		$this->set('recordsTotal',$overallTotalRows);

		$this->set('recordsFiltered',$totalFilteredRows);

		$this->set('data',$orders);

		$this->set('_serialize',['draw','recordsTotal','recordsFiltered','data']);

		

	}

	
	

	public function getOrderItemWorkOrders($orderItemID){

		$workorders=array();

		$orderItemStatus=$this->OrderItemStatus->find('all',['conditions'=>['order_item_id'=>$orderItemID,'work_order_id !=' => 0]])->toArray();

		foreach($orderItemStatus as $itemstatus){

			if(!in_array($itemstatus['work_order_id'],$workorders)){

				$workorders[]=array('id'=>$itemstatus['work_order_id'],'qty_involved'=>$itemstatus['qty_involved']);

			}

		}

		return $workorders;

	}

	

	public function getOrderItemShipments($orderItemID){

		$shipments=array();

		$orderItemStatus=$this->OrderItemStatus->find('all',['conditions'=>['order_item_id'=>$orderItemID,'shipment_id !=' => 0]])->toArray();

		foreach($orderItemStatus as $itemstatus){

			if(!in_array($itemstatus['shipment_id'],$shipments)){

				$shipments[]=array('id'=>$itemstatus['shipment_id'],'qty_involved'=>$itemstatus['qty_involved']);

			}

		}

		return $shipments;

	}

	

	public function addnew(){

		if($this->request->data){

			

			$customerData=$this->Customers->get($this->request->data['customer_id'])->toArray();

			

			//create a silent quote to attach new order to

			$quoteTable=TableRegistry::get('Quotes');

			$newQuote=$quoteTable->newEntity();

			$newQuote->created=strtotime($this->request->data['created'].' 11:00:00');

			$newQuote->modified=time();

			$newQuote->customer_id=$this->request->data['customer_id'];
			
			$newQuote->type_id = $this->request->data['type_id'];

			if($this->request->data['contact_id'] != ''){

				$newQuote->contact_id=$this->request->data['contact_id'];

			}else{

				$newQuote->contact_id=0;

			}

			$newQuote->status='emptyorder';

			$newQuote->created_by=$this->Auth->user('id');

			$newQuote->parent_quote=0;

			$newQuote->quote_subtotal=$this->request->data['order_total'];

			$newQuote->quote_surcharge=0;

			$newQuote->quote_total=$this->request->data['order_total'];

			$newQuote->quote_number=$this->getnewquotenumber();

			$newQuote->revision=0;

			$newQuote->title=$customerData['company_name']." PO #".$this->request->data['po_number'];

			

			$quoteTable->save($newQuote);

			$newQuoteID=$newQuote->id;

			

			$orderTable=TableRegistry::get('Orders');

			$newOrder=$orderTable->newEntity();

			$newOrder->status="Needs Line Items";

			$newOrder->customer_id=$this->request->data['customer_id'];
			
			$newOrder->type_id = $this->request->data['type_id'];

			if($this->request->data['contact_id'] != ''){

				$newOrder->contact_id=$this->request->data['contact_id'];

			}else{

				$newOrder->contact_id=0;

			}

			$newOrder->quote_id=$newQuoteID;

			$newOrder->order_total=$this->request->data['order_total'];

			$newOrder->po_number=$this->request->data['po_number'];

			$newOrder->created=strtotime($this->request->data['created'].' 11:00:00');

			$newOrder->user_id=$this->Auth->user('id');
			
			$newOrder->project_manager_id = $this->request->data['project_manager_id'];

			$newOrder->facility=$this->request->data['allfacility'];

			$newOrder->shipping_address_1=$this->request->data['shipping_address_1'];

			$newOrder->shipping_address_2=$this->request->data['shipping_address_2'];

			$newOrder->shipping_city=$this->request->data['shipping_city'];

			$newOrder->shipping_state=$this->request->data['shipping_state'];

			$newOrder->shipping_zipcode=$this->request->data['shipping_zipcode'];

			$newOrder->attention=$this->request->data['attention'];

			$newOrder->shipping_instructions=$this->request->data['shipping_instructions'];

			$newOrder->order_number=$this->getNextAvailableOrderNumber();

			$newOrder->shipping_method_id = $this->request->data['shipping_method_id'];
			
			
			/**PPSASCRUM-7 Start **/
			$newOrder->facility_id = $this->request->data['facilitySelected'];

			$newOrder->userType = ($this->request->data['userAddressesSelection'] == 'new') ? 'exisiting': $this->request->data['userAddressesSelection'] ;
			if($this->request->data['userAddressesSelection'] == 'default') {
				$facilityData=$this->Facility->find('all', ['conditions'=>['id'=>$this->request->data['facilitySelected']]])->first()->toArray();

				$newOrder->shipto_id = $facilityData['default_address'];//$this->request->data['default_address'];
			}else if($this->request->data['userAddressesSelection'] == 'exisiting') {
				$newOrder->shipto_id = $this->request->data['addressSelected'];
			} else if($this->request->data['userAddressesSelection'] ==  "customer") {
			    $thisCustomer=$this->Customers->find('all',['conditions'=>['id'=>$this->request->data['customer_id']]])->first()->toArray();
            	$newOrder->shipping_address_1=$thisCustomer['shipping_address'];
    
    			$newOrder->shipping_address_2='';
    
    			$newOrder->shipping_city=$thisCustomer['shipping_address_city'];
    
    			$newOrder->shipping_state=$thisCustomer['shipping_address_state'];
    
    			$newOrder->shipping_zipcode=$thisCustomer['shipping_address_zipcode'];
    
    			$newOrder->attention=$this->request->data['attention'];
    			$newOrder->shipping_country=$thisCustomer['shipping_address_country'];
			}
			
			if(!empty($newOrder->shipto_id)) {
    			$shipToTable = TableRegistry::get('ShipTo');
    			$shipToDetails = $shipToTable->get($newOrder->shipto_id);
    			$newOrder->shipping_address_1=$shipToDetails->shipping_address_1;
    
    			$newOrder->shipping_address_2=$shipToDetails->shipping_address_2;
    
    			$newOrder->shipping_city=$shipToDetails->shipping_city;
    
    			$newOrder->shipping_state=$shipToDetails->shipping_state;
    
    			$newOrder->shipping_zipcode=$shipToDetails->shipping_zipcode;
    			$newOrder->shipping_country=$shipToDetails->shipping_country;
    
    			$newOrder->attention=$this->request->data['attention'];
			}
			if(!empty($this->request->data['facilityAttention']) && !empty($this->request->data['facilitySelected'])) {
				$faciltiyTable=TableRegistry::get('Facility');
				$thisfaciltiy=$faciltiyTable->get($this->request->data['facilitySelected']);
				$thisfaciltiy->attention=$this->request->data['facilityAttention'];
				$faciltiyTable->save($thisfaciltiy);
							$newOrder->facility=$thisfaciltiy->facility_name;


			}
		/*	if(!empty($this->request->data['attentionship']) && !empty($newOrder->shipto_id)) {
				$shipToTable=TableRegistry::get('ShipTo');
				$thisshiptp=$shipToTable->get($newOrder->shipto_id);
				$thisshiptp->attention=$this->request->data['attentionship'];
				$shipToTable->save($thisshiptp);

			} */

			/** PPSASCRUM-7 ENd **/
			

			if($this->request->data['hasduedate'] == '1'){

				$newOrder->due=strtotime($this->request->data['due'].' 15:30:00');

			}

			

			$orderTable->save($newOrder);
			/**PPSASCRUM-29 start */
			$workOrderTable=TableRegistry::get('WorkOrders');

			$newWorkOrder=$workOrderTable->newEntity();
			$originalOrder = $newOrder->toArray();
			$newWorkOrder = $this->WorkOrders->patchEntity($newWorkOrder, $originalOrder);

			$workOrderTable->save($newWorkOrder);
			/**PPSASCRUM-29 end */
            $newQuote->order_id=$newOrder->id;
			$newQuote->order_number=$newOrder->order_number;
			$this->Quotes->save($newQuote);
			

			$this->logActivity($_SERVER['REQUEST_URI'],'Created new pre-approved Order '.$newOrder->order_number);

			$this->Flash->success('Successfully created pre-approved order #'.$newOrder->order_number);

			return $this->redirect('/orders/');

			

		}else{

            $allTypes=$this->QuoteTypes->find('all')->toArray();
            $this->set('allTypes',$allTypes);

			$allShippingMethods = $this->ShippingMethods->find('all')->toArray();
			$availableShippingMethods=array();
			foreach($allShippingMethods as $method){
				$availableShippingMethods[$method['id']]=$method['name'];
			}
			$this->set('availableShippingMethods',$availableShippingMethods);


			$customers=$this->Customers->find('list',['keyField' => 'id', 'valueField' => 'company_name', 'conditions'=>['status IN'=>['OnHold','Active']],'order'=>['company_name'=>'asc']])->toArray();

			$this->set('customers',$customers);
			
            $allUsers=$this->Users->find('all',['conditions' => ['status'=>'Active'],'order'=>['last_name'=>'asc','first_name'=>'asc']])->toArray();
			$this->set('allUsers',$allUsers);
			/**PPSASCRUM-7 Start **/
			$allFaclity=$this->Facility->find('list',['keyField' => 'id', 'valueField' => 'facility_name', 'conditions' => [],'order'=>['facility_name'=>'asc']])->toArray();
			$this->set('allFacility',$allFaclity);

			$shipTooptions=$this->ShipTo->find('list',['keyField' => 'id', 'valueField' => 'shipping_address_1', 'conditions' => [],'order'=>['shipping_address_1'=>'asc']])->toArray();
			$this->set('shipTooptions',$shipTooptions);
			/** PPSASCRUM-7 End **/
		}

	}

	

	public function checkponumber($ponumber,$customerid){

		$this->autoRender=false;

		$orders=$this->Orders->find('all',['conditions'=>['customer_id'=>$customerid,'po_number'=>$ponumber,'status !=' => 'Canceled']])->toArray();

		if(count($orders) >0){
			echo "NO";
		}else{
			echo "YES";
		}

		die;

	}

	

	
    
	public function editlines($orderid, $ordermode='order'){

		$this->autoRender=false;
		$this->set('urlparams',$this->request);
		if($ordermode == 'workorder')
			$thisOrder=$this->WorkOrders->get($orderid)->toArray();
		else
			$thisOrder=$this->Orders->get($orderid)->toArray();
		

		$this->set('quoteID',$thisOrder['quote_id']);
		if($thisOrder['status'] == 'Editing'){
		
		    $this->set('mode','editorderlines');
			$this->set('ordermode',$ordermode);
		    
		    /* PPSASCRUM-313: start */
		    $lookupNewQuote=$this->Quotes->find('all',['conditions' => ['order_id' => $orderid, 'status' => 'editorder']])->toArray();
			/* PPSASCRUM-313: end */
		    $this->set('orderData',$thisOrder);
		    
		    foreach($lookupNewQuote as $thisQuoteData){
		        $thisOrderQuote=$thisQuoteData;
		        $this->set('quoteData',$thisQuoteData);
		        $newQuote=$thisQuoteData;
		        $this->set('quoteID',$thisQuoteData['id']);
		    }
		    
		    
		    //$customerData=$this->Customers->get($thisOrderQuote['customer_id'])->toArray();
			$customerData=$this->Customers->find('all',['conditions' => ['id' => $thisOrderQuote['customer_id']]])->toArray();

    		$this->set('customerData',$customerData[0]);
    		
    		
    		
    		$availableProjects=$this->Projects->find('all',['conditions'=>['customer_id' => $thisOrderQuote['customer_id']]])->toArray();
    		$this->set('availableProjects',$availableProjects);
    	
    		if($thisOrderQuote['contact_id'] == '0'){
    			$customerContact=false;
    		}else{
				$customerContact=$this->CustomerContacts->find('all',['conditions' => ['id' => $thisOrderQuote['contact_id']]])->toArray();

    			//$customerContact=$this->CustomerContacts->get($thisOrderQuote['contact_id'])->toArray();
    		}
    
    		$this->set('customerContact',$customerContact);
    
    		$allcalculators=$this->Calculators->find('all')->toArray();
    
    		$this->set('allcalculators',$allcalculators);
    
    		$allTypes=$this->QuoteTypes->find('all')->toArray();
            $this->set('allTypes',$allTypes);
            
            
            
            //determine if the changes are within the rules
            $ruleErrors=array();
            
            $oldQuote=$this->Quotes->get($thisOrder['quote_id'])->toArray();
            if(!empty($ordermode)){
                if($ordermode =='workorder'){
                     $oldQuoteLines=$this->WorkOrderLineItems->find('all',['conditions' => ['quote_id' => $oldQuote['id'], 'enable_tally'=>1],'order'=>['sortorder'=>'asc']])->toArray();
           
                }else{
                     $oldQuoteLines=$this->OrderLineItems->find('all',['conditions' => ['quote_id' => $oldQuote['id'], 'enable_tally'=>1],'order'=>['sortorder'=>'asc']])->toArray();
           
                }
            }else{
            
            $oldQuoteLines=$this->QuoteLineItems->find('all',['conditions' => ['quote_id' => $oldQuote['id'], 'enable_tally'=>1],'order'=>['sortorder'=>'asc']])->toArray();
            
            $newQuoteLines=$this->QuoteLineItems->find('all',['conditions' => ['quote_id' => $newQuote['id'], 'enable_tally'=>1],'order'=>['sortorder'=>'asc']])->toArray();
            }
            if($ordermode == 'workorder')
				$orderLines=$this->WorkOrderItems->find('all',['conditions' => ['order_id' =>  $thisOrder['id']]])->toArray();
			else 
				$orderLines=$this->OrderItems->find('all',['conditions' => ['order_id' =>  $thisOrder['id']]])->toArray();
            
            $totals=array();
            
            foreach($orderLines as $orderLine){
                $totals[$orderLine['id']]=array('LineNumber' => '','oldItemID'=>0,'newItemID'=>0, 'Scheduled'=>0,'Produced'=>0,'Shipped'=>0,'Invoiced'=>0);
                
                //get the QTYs Scheduled
                $totalScheduledThisLine=0;
				if($ordermode == 'workorder')
				 $numScheduledLookup=$this->WorkOrderItemStatus->find('all',['conditions' => ['order_item_id' => $orderLine['id'], 'status' => 'Scheduled']])->toArray();  
				else
                $numScheduledLookup=$this->OrderItemStatus->find('all',['conditions' => ['order_item_id' => $orderLine['id'], 'status' => 'Scheduled']])->toArray();
                foreach($numScheduledLookup as $scheduledEntry){
                    $totalScheduledThisLine=($totalScheduledThisLine + intval($scheduledEntry['qty_involved']));
                }
                $totals[$orderLine['id']]['Scheduled']=$totalScheduledThisLine;
                
                //get the QTYs Produced
                $totalProducedThisLine=0;
				if($ordermode == 'workorder')
				 $numScheduledLookup=$this->WorkOrderItemStatus->find('all',['conditions' => ['order_item_id' => $orderLine['id'], 'status' => 'Completed']])->toArray();  
				else
                $numProducedLookup=$this->OrderItemStatus->find('all',['conditions' => ['order_item_id' => $orderLine['id'], 'status' => 'Completed']])->toArray();
                foreach($numProducedLookup as $producedEntry){
                    $totalProducedThisLine=($totalProducedThisLine + intval($producedEntry['qty_involved']));
                }
                $totals[$orderLine['id']]['Produced']=$totalProducedThisLine;
                
                
                //get the QTYs Shipped
                $totalShippedThisLine=0;
                if($ordermode == 'workorder')
				 $numScheduledLookup=$this->WorkOrderItemStatus->find('all',['conditions' => ['order_item_id' => $orderLine['id'], 'status' => 'Shipped']])->toArray();  
				else
				$numShippedLookup=$this->OrderItemStatus->find('all',['conditions' => ['order_item_id' => $orderLine['id'], 'status' => 'Shipped']])->toArray();
                foreach($numShippedLookup as $shippedEntry){
                    $totalShippedThisLine=($totalShippedThisLine + intval($shippedEntry['qty_involved']));
                }
                $totals[$orderLine['id']]['Shipped']=$totalShippedThisLine;
                
                //get the QTYs Invoiced
                $totalInvoicedThisLine=0;
				if($ordermode == 'workorder')
				 $numScheduledLookup=$this->WorkOrderItemStatus->find('all',['conditions' => ['order_item_id' => $orderLine['id'], 'status' => 'Invoiced']])->toArray();  
				else
                $numInvoicedLookup=$this->OrderItemStatus->find('all',['conditions' => ['order_item_id' => $orderLine['id'], 'status' => 'Invoiced']])->toArray();
                foreach($numInvoicedLookup as $invoicedEntry){
                    $totalInvoicedThisLine=($totalInvoicedThisLine + intval($invoicedEntry['qty_involved']));
                }
                $totals[$orderLine['id']]['Invoiced']=$totalInvoicedThisLine;
            
                //determine the QTYs not scheduled
              /*	$thisOldQuoteLine=$this->QuoteLineItems->get($orderLine['quote_line_item_id'])->toArray();
                $oldLineItemMetas=$this->QuoteLineItemMeta->find('all',['conditions' => ['quote_item_id' => $orderLine['quote_line_item_id']]])->toArray();*/
                
                try{
                	$thisOldQuoteLine=$this->OrderLineItems->get($orderLine['id'])->toArray();
                $oldLineItemMetas=$this->OrderLineItemMeta->find('all',['conditions' => ['order_item_id' => $orderLine['id']]])->toArray();}catch (RecordNotFoundException $e) { }
                    
                
                $oldLineItemMetaArray=array();
    			foreach($oldLineItemMetas as $lineItemMetaRow){
    				$oldLineItemMetaArray[$lineItemMetaRow['meta_key']]=$lineItemMetaRow['meta_value'];
    			}
                
                
                $totals[$orderLine['id']]['LineNumber']=$thisOldQuoteLine['line_number'];
                $totals[$orderLine['id']]['oldItemID']=$thisOldQuoteLine['id'];
                
                if(empty($ordermode)){
                foreach($newQuoteLines as $newLine){
                    //if($newLine['line_number'] == $thisOldQuoteLine['line_number']){
                    if($newLine['revised_from_line'] == $thisOldQuoteLine['id']){
                        
                        $newLineItemMetas=$this->QuoteLineItemMeta->find('all',['conditions' => ['quote_item_id' => $newLine['id']]])->toArray();
                        
                        
                        $newLineItemMetaArray=array();
            			foreach($newLineItemMetas as $lineItemMetaRow){
            				$newLineItemMetaArray[$lineItemMetaRow['meta_key']]=$lineItemMetaRow['meta_value'];
            			}
                        
                        $totals[$orderLine['id']]['newItemID']=$newLine['id'];
                        
                        if($thisOldQuoteLine['qty'] == $totals[$orderLine['id']]['Produced'] && $newLine['qty'] < $thisOldQuoteLine['qty']){
                            $ruleErrors[]='<img src="/img/delete.png" /> <em>Line '.$newLine['line_number'].'</em> CANNOT PROCEED: Line fully produced. Qty cannot be decreased below '.$thisOldQuoteLine['qty'].'<br><small>Consider altering the Price Ea to obtain the expected Ext Price and adding a line note detailing the reason</small>';
                        }else{
                        
                            if($newLine['qty'] < $thisOldQuoteLine['qty'] && $newLine['qty'] < $totalScheduledThisLine){
                                $ruleErrors[]='<img src="/img/delete.png" /> <em>Line '.$newLine['line_number'].'</em> CANNOT PROCEED: New Qty ('.$newLine['qty'].') smaller than Already Batched Qty ('.$totalScheduledThisLine.')';
                            }
                        }
                        
                        if(isset($oldLineItemMetaArray['fabricid']) && strlen(trim($oldLineItemMetaArray['fabricid'])) > 0){
                            if($oldLineItemMetaArray['fabricid'] != $newLineItemMetaArray['fabricid'] && ($totalProducedThisLine > 0 || $totalShippedThisLine > 0 || $totalInvoicedThisLine > 0)){
                                $ruleErrors[]='<img src="/img/delete.png" /> CANNOT SAVE. LINE '.$newLine['line_number'].' HAS ALREADY BEEN PRODUCED/SHIPPED and CANNOT GET ITS FABRIC/COLOR CHANGED';
                            }elseif($oldLineItemMetaArray['fabricid'] != $newLineItemMetaArray['fabricid'] && $totalScheduledThisLine > 0){
                                $ruleErrors[]='<img src="/img/delete.png" /> FABRIC CHANGE ERROR. CANNOT SAVE AS IS. LINE '.$newLine['line_number'].' HAS ALREADY BEEN BATCHED. MUST EDIT/REMOVE THE BATCH BEFORE SAVING';
                            }
                        }
                        
                        
                    }
                }
                }
                
            }
            
            /** PPSASCRUM-3 Start **/
    		$thisShipByDate='';
    		if($thisOrder['due'] > 1000){

				$duedate=date('n/j/Y',$thisOrder['due']);

			}else{

				$duedate='---';

			}
			$this->set('thisShipByDate',$duedate);
			
    		$thisProjectManagerName='';
			
			if($thisOrder['project_manager_id'] > 0){
			    $thisPMUser=$this->Users->get($thisOrder['project_manager_id'])->toArray();
			    $thisProjectManagerName=$thisPMUser['first_name'].' '.$thisPMUser['last_name'];
			}
			
			$this->set('thisProjectManagerName',$thisProjectManagerName);
			/** PPSASCRUM-3 End **/
			
            $this->set('totals',$totals);
            $this->set('ruleErrors',$ruleErrors);
    		$this->render('/Quotes/addstep2/');
		    
		}else{
		   if($ordermode == 'workorder' || $ordermode == 'order'){
				//$this->set('mode',$ordermode);
				$this->set('ordermode',$ordermode);}
			else 
			$this->set('mode','');
		    
    		$thisOrderQuote=$this->Quotes->get($thisOrder['quote_id'])->toArray();
    		$this->set('orderData',$thisOrder);
    		$this->set('quoteData',$thisOrderQuote);
    		
    		/** PPSASCRUM-3 Start **/
    		$thisShipByDate='';
    		if($thisOrder['due'] > 1000){

				$duedate=date('n/j/Y',$thisOrder['due']);

			}else{

				$duedate='---';

			}
			$this->set('thisShipByDate',$duedate);
			
    		$thisProjectManagerName='';
			
			if($thisOrder['project_manager_id'] > 0){
			    $thisPMUser=$this->Users->get($thisOrder['project_manager_id'])->toArray();
			    $thisProjectManagerName=$thisPMUser['first_name'].' '.$thisPMUser['last_name'];
			}
			
			$this->set('thisProjectManagerName',$thisProjectManagerName);
			/** PPSASCRUM-3 End **/
			
    		$customerData=$this->Customers->get($thisOrderQuote['customer_id'])->toArray();
    		$this->set('customerData',$customerData);
    		$this->set('quoteID',$thisOrder['quote_id']);
    		$availableProjects=$this->Projects->find('all',['conditions'=>['customer_id' => $thisOrderQuote['customer_id']]])->toArray();
    		$this->set('availableProjects',$availableProjects);
    	
    		if($thisOrderQuote['contact_id'] == '0'){
    			$customerContact=false;
    		}else{
    			$customerContact=$this->CustomerContacts->get($thisOrderQuote['contact_id'])->toArray();
    		}
    
            
    		$this->set('customerContact',$customerContact);
    
    		$allcalculators=$this->Calculators->find('all')->toArray();
    
    		$this->set('allcalculators',$allcalculators);
    
            $allTypes=$this->QuoteTypes->find('all')->toArray();
            $this->set('allTypes',$allTypes);
    		
		
    		$this->render('/Quotes/addstep2');
		}
	}
	

	

	public function completeorder($quoteID){

		$orderLookup=$this->Orders->find('all',['conditions'=>['quote_id'=>$quoteID]])->toArray();

		foreach($orderLookup as $order){

			//update this Order to Pre-Production status

			$orderTable=TableRegistry::get('Orders');

			$thisOrder=$orderTable->get($order['id']);

			

			$quoteTable=TableRegistry::get('Quotes');

			$thisQuote=$quoteTable->get($quoteID);

			

			$orderItemsTable=TableRegistry::get('OrderItems');

			$orderItemStatusTable=TableRegistry::get('OrderItemStatus');
			
		    $workorderItemsTable=TableRegistry::get('WorkOrderItems');

			$workorderItemStatusTable=TableRegistry::get('WorkOrderItemStatus');

			

			$thisOrder->status='Pre-Production';

			

			//update the order total

			$thisOrder->order_total = $thisQuote->quote_total;
			$orderTable->save($thisOrder);
            
			
			//update this Quote to "published"

			

			//import "order items" from quote

			$thisQuoteItems=$this->OrderLineItems->find('all',['conditions'=>['quote_id'=>$quoteID]])->toArray();
			
			$this->saveQuoteLineItem($quoteID,$thisOrder->id);
		
 $this->logActivity(
                        $_SERVER["REQUEST_URI"],"Count of Orders items " . count(count($thisQuoteItems))."" );
			foreach($thisQuoteItems as $itemData){
			    //UPDATING THE WORKORDER LINE ITEM AND META TABLES
			   $this->logActivity(
                        $_SERVER["REQUEST_URI"],"Inside the workorderloop  " . count($itemData['id'])."" );
			    $woId= $this->savetoOrderLineItemTables(
                                $itemData["id"],
                                $itemData["quote_id"],
                                $itemData["order_id"],
                                "WorkOrder",
                                "0",
                                false);
			   
			    
			     //LineNotes saving to WorkOrders
                $lineNotes = $this->OrderLineItemNotes->find("all", [ "conditions" => ["quote_item_id" => $itemData["id"]] ])->toArray();
                    if(count($lineNotes) > 0){
                         foreach($lineNotes as $note){
                                $this->logActivity($_SERVER["REQUEST_URI"], count($lineNotes). "Test for line notes update to workorder".$note['user_id'].$note['message']);
                            
                                $newWorkMeta=$this->WorkOrderLineItemNotes->newEntity();
                                $newWorkMeta->user_id = $note['user_id'];
                                $newWorkMeta->time = $note['time'];
                                $newWorkMeta->message = $note['message'];
                                $newWorkMeta->quote_id =  $itemData["quote_id"];
                                $newWorkMeta->quote_item_id =  $woId->id;
                                $newWorkMeta->visible_to_customer = $note['visible_to_customer'];
                                $this->WorkOrderLineItemNotes->save($newWorkMeta);
                                
                            }
                    }
                    
	        
				//create as Order Line Items

				$newOrderLineItem=$orderItemsTable->newEntity();

				$newOrderLineItem->order_id=$thisOrder->id;

				$newOrderLineItem->quote_line_item_id=$itemData['id'];

				$newOrderLineItem->quote_id=$quoteID;

				if($orderItemsTable->save($newOrderLineItem)){

					//saved
                    
					//create a Status entry for this line item

					$newOrderItemStatus=$orderItemStatusTable->newEntity();

					$newOrderItemStatus->order_line_number=$itemData['line_number'];

					$newOrderItemStatus->order_item_id=$newOrderLineItem->id;

					$newOrderItemStatus->status='Not Started';

					$newOrderItemStatus->time=time();

					$newOrderItemStatus->user_id=$this->Auth->user('id');

					$newOrderItemStatus->qty_involved=$itemData['qty'];

					$newOrderItemStatus->work_order_id = $thisOrder->id;

					$orderItemStatusTable->save($newOrderItemStatus);
					
					/**PPSASCRUM-29 start **/
                    //$this->savetoWorkOrderItemsTables($newOrderLineItem->id);
            
            
            //PPSASCRUM-29 start
                $newWorkOrderLineItem=$workorderItemsTable->newEntity();

				$newWorkOrderLineItem->order_id=$thisOrder->id;

				//$newWorkOrderLineItem->quote_line_item_id=$itemData['id'];
				$newWorkOrderLineItem->quote_line_item_id=$woId;

				$newWorkOrderLineItem->quote_id=$quoteID;
                $workorderItemsTable->save($newWorkOrderLineItem);
                    
                    
                    //create a Status entry for this line item

				$newWorkOrderItemStatus=$workorderItemStatusTable->newEntity();

				$newWorkOrderItemStatus->order_line_number=$itemData['line_number'];

				$newWorkOrderItemStatus->order_item_id=$newWorkOrderLineItem->id;

					$newWorkOrderItemStatus->status='Not Started';

					$newWorkOrderItemStatus->time=time();

					$newWorkOrderItemStatus->user_id=$this->Auth->user('id');

					$newWorkOrderItemStatus->qty_involved=$itemData['qty'];

					$newWorkOrderItemStatus->work_order_id = $thisOrder->id;

					$workorderItemStatusTable->save($newWorkOrderItemStatus);
                    
                    /**PPSASCRUM-29 end **/
                    /**PPSASCRUM-29 start **/
                  //  $this->savetoWorkOrderItemStatusTables($newOrderItemStatus->id);
                    /**PPSASCRUM-29 end **/
				}

			}
			
		
            $this->updateOrdersTable($order['id'],$quoteID);
            $this->updateWorkOrdersTable($order['id'],$quoteID);
                    
          
			/* PPSASCRUM-248: start [updating function with arguments as per complete set of function parameters] */
			$this->auditOrderItemStatuses($order['id'], false, 'workorder');
			/* PPSASCRUM-248: end */
			

			$thisQuote->status='orderplaced';

			$quoteTable->save($thisQuote);

			$this->Flash->success('Successfully marked order '.$order['order_number'].' as Order Placed');

			return $this->redirect('/orders/');

		}

	}

	public function materials($tab='overview',$action=''){

		

		$this->set('thisAction',$tab);

		


		$allFabrics=$this->InventoryCache->find('all',['order'=>['fabric_name'=>'asc','fabric_color'=>'asc']])->toArray();

		$this->set('allfabrics',$allFabrics);

		$allVendors=$this->Vendors->find('all')->toArray();

		$this->set('allVendors',$allVendors);

		switch($tab){

			case "overview":

				

			break;

			case "purchases":

			break;

			case "flagged":

			break;

			case "inventory":

			break;

		}

		

		

	}

	

	public function ordermaterial($materialID,$materialType='Fabrics'){

		$this->autoRender=false;

		//$this->viewBuilder()->layout('iframeinner');

		

		if($this->request->data){

			$thisFabric=$this->Fabrics->get($materialID)->toArray();

			

			$checkedOrders=array();

			$purchaseNotes=array();

			

			foreach($this->request->data as $key => $value){

				if(preg_match("#include_order_#i",$key) && $value=='1'){

					$exp=explode("include_order_",$key);

					$checkedOrders[]=$exp[1];

					$purchaseNotes[]=array('order'=>$exp[1],'note'=>$this->request->data["wo_note_".$exp[1]]);

				}

			}

			

			$matPurchModel=TableRegistry::get('MaterialPurchases');

			$newPurchase=$matPurchModel->newEntity();

			$newPurchase->material_type='Fabrics';

			$newPurchase->material_id = $materialID;

			$newPurchase->material_action = 'order';

			$newPurchase->unit='yards';

			$newPurchase->amount_ordered = $this->request->data['totalyardstoorder'];

			$newPurchase->vendor_id=$thisFabric['vendors_id'];

			$newPurchase->created=time();

			$newPurchase->expected_arrival=strtotime($this->request->data['shipdate'].' 12:00:00');

			$newPurchase->orders_affected = json_encode($checkedOrders);

			$newPurchase->user_id = $this->Auth->user('id');

			$newPurchase->status='ordered';

			$newPurchase->purchaser_notes = json_encode($purchaseNotes);

			if($matPurchModel->save($newPurchase)){

				$this->Flash->success('Successfully added new Fabric Purchase Order');

				$this->logActivity($_SERVER['REQUEST_URI'],'New fabric order '.$newPurchase->id);

				$this->redirect('/orders/materials/purchases');

			}

			

		}else{

		

			switch($materialType){

				case "Fabrics":

					$thisFabric=$this->Fabrics->get($materialID)->toArray();

					$this->set('materialData',$thisFabric);

					$thisVendor=$this->Vendors->get($thisFabric['vendors_id'])->toArray();

					$this->set('materialVendor',$thisVendor);

				break;

			}

			$this->set('numyards',$numyards);

			$this->set('numquoteyards',$numquoteyards);

			//build a live dataset for the template to build a form from

			$requiredMaterialsCOM=array();

			$requiredMaterialsMOM=array();

			$maybeMaterialsCOM=array();

			$maybeMaterialsMOM=array();

			$allFabrics=$this->Fabrics->find('all',['order'=>['fabric_name'=>'asc','color'=>'asc']])->toArray();

			$this->set('allfabrics',$allFabrics);

			//$this->autoRender=false;

			$usedQuotes=array();

			//query all Pre-Production + Processed orders

			$materialOrders=$this->Orders->find('all',['conditions'=>['status IN' => ['Needs Line Items','Pre-Production']]])->toArray();

			//$materialOrders=$this->Orders->find('all',['conditions' => ['id' => 36]])->toArray();

			$this->set('materialOrders',$materialOrders);

			foreach($materialOrders as $materialOrder){

				//get all line item data

				$thisQuote=$this->Quotes->get($materialOrder['quote_id'])->toArray();

				if(!in_array($materialOrder['quote_id'],$usedQuotes)){

					$usedQuotes[]=$materialOrder['quote_id'];

				}

				$lines=$this->QuoteLineItems->find('all',['conditions' => ['quote_id' => $materialOrder['quote_id']]])->toArray();

				foreach($lines as $lineitem){

					//print_r($lineitem);

					//echo "\n\n\n\n\n";

					//gather all line item metadata

					$lineItemMetas=$this->QuoteLineItemMeta->find('all',['conditions' => ['quote_item_id' => $lineitem['id']]])->toArray();

					$lineItemMetaArray=array();

					foreach($lineItemMetas as $lineItemMetaRow){

						$lineItemMetaArray[$lineItemMetaRow['meta_key']]=$lineItemMetaRow['meta_value'];

					}

					if(isset($lineItemMetaArray['com-fabric']) && $lineItemMetaArray['com-fabric'] == '1' && floatval($lineItemMetaArray['yds-per-unit']) >0){

						//this is COM

						if(!in_array($materialOrder['id'],$requiredMaterialsCOM[$lineItemMetaArray['fabricid']]['orders'])){

							$requiredMaterialsCOM[$lineItemMetaArray['fabricid']]['orders'][]=$materialOrder['order_number'];

						}

						if(!isset($requiredMaterialsCOM[$lineItemMetaArray['fabricid']]['total-yards'])){

							$requiredMaterialsCOM[$lineItemMetaArray['fabricid']]['total-yards'] = (floatval($lineItemMetaArray['yds-per-unit']) * floatval($lineitem['qty']) );

						}else{

							$requiredMaterialsCOM[$lineItemMetaArray['fabricid']]['total-yards'] = ($requiredMaterialsCOM[$lineItemMetaArray['fabricid']]['total-yards'] + (floatval($lineItemMetaArray['yds-per-unit']) * floatval($lineitem['qty']) ));

						}

						$requiredMaterialsCOM[$lineItemMetaArray['fabricid']]['yardages'][]=array(

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

					if(!isset($lineItemMetaArray['com-fabric']) || (isset($lineItemMetaArray['com-fabric']) && $lineItemMetaArray['com-fabric'] == '0') && floatval($lineItemMetaArray['yds-per-unit']) >0){

						//this is MOM

						if(!in_array($materialOrder['id'],$requiredMaterialsMOM[$lineItemMetaArray['fabricid']]['orders']) && floatval($lineItemMetaArray['yds-per-unit']) >0){

							$requiredMaterialsMOM[$lineItemMetaArray['fabricid']]['orders'][]=$materialOrder['order_number'];

						}

						if(!isset($requiredMaterialsMOM[$lineItemMetaArray['fabricid']]['total-yards'])){

							$requiredMaterialsMOM[$lineItemMetaArray['fabricid']]['total-yards'] = (floatval($lineItemMetaArray['yds-per-unit']) * floatval($lineitem['qty']) );

						}else{

							$requiredMaterialsMOM[$lineItemMetaArray['fabricid']]['total-yards'] = ($requiredMaterialsMOM[$lineItemMetaArray['fabricid']]['total-yards'] + (floatval($lineItemMetaArray['yds-per-unit']) * floatval($lineitem['qty']) ));

						}

						$requiredMaterialsMOM[$lineItemMetaArray['fabricid']]['yardages'][] = array(

							'order_number' => $materialOrder['order_number'],

							'order_id' => $materialOrder['id'],

							'quote_id' => $materialOrder['quote_id'],

							'po_number' => $materialOrder['po_number'],

							'quote_number' => $thisQuote['quote_number'],

							'line_number' => $lineitem['line_number'],

							'requirements' => $this->getOrderFabricRequirementNote($materialOrder['quote_id'],$materialID),

							'yards' => (floatval($lineItemMetaArray['yds-per-unit']) * floatval($lineitem['qty']) ),

							'available' => $this->fabricyardsonhand($lineItemMetaArray['fabricid'],'mom')

						);

					}

				}

			}

			$this->set('requiredMaterialsCOM',$requiredMaterialsCOM);

			$this->set('requiredMaterialsMOM',$requiredMaterialsMOM);



			//query all Published quotes

			$materialQuotes=$this->Quotes->find('all',['conditions'=>['created >' => (time()-5184000), 'status' => 'published', 'id NOT IN' => $usedQuotes]])->toArray();

			foreach($materialQuotes as $materialQuote){

				//latest revisions only

				if(!$this->isLatestRevision($materialQuote['id'])){

					continue;

				}

				//exclude quotes already converted to orders

				if(!$this->quoteHasOrder($materialQuote['id'])){

					continue;

				}

				//get all line item data

				$lines=$this->QuoteLineItems->find('all',['conditions' => ['quote_id' => $materialQuote['id']]])->toArray();

				foreach($lines as $lineitem){

					//gather all line item metadata

					$lineItemMetas=$this->QuoteLineItemMeta->find('all',['conditions' => ['quote_item_id' => $lineitem['id']]])->toArray();

					$lineItemMetaArray=array();

					foreach($lineItemMetas as $lineItemMetaRow){

						$lineItemMetaArray[$lineItemMetaRow['meta_key']]=$lineItemMetaRow['meta_value'];

					}

					if(isset($lineItemMetaArray['com-fabric']) && $lineItemMetaArray['com-fabric'] == '1' && floatval($lineItemMetaArray['yds-per-unit']) >0){

						//this is COM

						if(!in_array($materialOrder['id'],$maybeMaterialsCOM[$lineItemMetaArray['fabricid']]['orders'])){

							$maybeMaterialsCOM[$lineItemMetaArray['fabricid']]['quotes'][]=$materialQuote['quote_number'];

						}

						if(!isset($maybeMaterialsCOM[$lineItemMetaArray['fabricid']]['total-yards'])){

							$maybeMaterialsCOM[$lineItemMetaArray['fabricid']]['total-yards'] = (floatval($lineItemMetaArray['yds-per-unit']) * floatval($lineitem['qty']) );

						}else{

							$maybeMaterialsCOM[$lineItemMetaArray['fabricid']]['total-yards'] = ($maybeMaterialsCOM[$lineItemMetaArray['fabricid']]['total-yards'] + (floatval($lineItemMetaArray['yds-per-unit']) * floatval($lineitem['qty']) ));

						}

						$maybeMaterialsCOM[$lineItemMetaArray['fabricid']]['yardages'][]=array(

							'quote_number' => $materialQuote['quote_number'],

							'line_number' => $lineitem['line_number'],

							'yards' => (floatval($lineItemMetaArray['yds-per-unit']) * floatval($lineitem['qty']) )

						);

					}

					if(!isset($lineItemMetaArray['com-fabric']) || (isset($lineItemMetaArray['com-fabric']) && $lineItemMetaArray['com-fabric'] == '0') && floatval($lineItemMetaArray['yds-per-unit']) >0){

						//this is MOM

						if(!in_array($materialOrder['id'],$maybeMaterialsMOM[$lineItemMetaArray['fabricid']]['orders'])){

							$maybeMaterialsMOM[$lineItemMetaArray['fabricid']]['quotes'][]=$materialQuote['quote_number'];

						}

						if(!isset($maybeMaterialsMOM[$lineItemMetaArray['fabricid']]['total-yards'])){

							$maybeMaterialsMOM[$lineItemMetaArray['fabricid']]['total-yards'] = (floatval($lineItemMetaArray['yds-per-unit']) * floatval($lineitem['qty']) );

						}else{

							$maybeMaterialsMOM[$lineItemMetaArray['fabricid']]['total-yards'] = ($maybeMaterialsMOM[$lineItemMetaArray['fabricid']]['total-yards'] + (floatval($lineItemMetaArray['yds-per-unit']) * floatval($lineitem['qty']) ));

						}

						$maybeMaterialsMOM[$lineItemMetaArray['fabricid']]['yardages'][] = array(

							'quote_number' => $materialQuote['quote_number'],

							'line_number' => $lineitem['line_number'],

							'yards' => (floatval($lineItemMetaArray['yds-per-unit']) * floatval($lineitem['qty']) )

						);

					}

				}

			}

			$this->set('maybeMaterialsCOM',$maybeMaterialsCOM);

			$this->set('maybeMaterialsMOM',$maybeMaterialsMOM);

			$this->render('ordermaterial');

		}

	}

	

	

	public function buildbluesheet($quoteLineID,$step='step1',$other=false){

		

		$this->autoRender=false;

		
		
		$allFabrics=$this->Fabrics->find('all')->toArray();
		$this->set('allFabrics',$allFabrics);

		$allLinings=$this->Linings->find('all')->toArray();
		$this->set('allLinings',$allLinings);
		
		switch($step){

			case "step1":

				//select a template

				

				$blueSheetTemplates=$this->BluesheetTemplates->find('all')->toArray();

				$this->set('bluesheetTemplates',$blueSheetTemplates);

				

				$this->set('lineItemID',$quoteLineID);

				

				$this->render('selectbluesheettemplate');

				

			break;

			case "step2":

				

				$thisTemplate=$this->BluesheetTemplates->get($other)->toArray();

				$this->set('template',$thisTemplate);

				

				

				//$this->viewBuilder()->layout('iframeinner');

				$lineItem=$this->QuoteLineItems->get($quoteLineID)->toArray();

				$thisQuote=$this->Quotes->get($lineItem['quote_id'])->toArray();

				$this->set('thisQuote',$thisQuote);

				$this->set('lineItemData',$lineItem);

				$thisOrderFind=$this->Orders->find('all',['conditions'=>['quote_id' => $thisQuote['id']]])->toArray();

				foreach($thisOrderFind as $thisOrderRow){

					$this->set('thisOrder',$thisOrderRow);

					$thisCustomer=$this->Customers->get($thisOrderRow['customer_id'])->toArray();

					$this->set('thisCustomer',$thisCustomer);

				}

				$this->set('thisUser',$this->Auth->user());

				$lineItemMetas=$this->QuoteLineItemMeta->find('all',['conditions'=>['quote_item_id'=>$quoteLineID]])->toArray();

				$this->set('lineItemMetas',$lineItemMetas);
				

				$this->render('buildbluesheet');

				

			break;

		}

		

	}

	

	

	public function materialusageform($orderID){

		$thisOrder=$this->Orders->get($orderID)->toArray();

		$this->set('orderData',$thisOrder);

		$GLOBALS['pdfmargins']='custom';

		$GLOBALS['pdfmarginscustom']=array('left'=>6,'top'=>4,'right'=>6,'header'=>6);

		$thisCustomer=$this->Customers->get($thisOrder['customer_id'])->toArray();

		$this->set('customerData',$thisCustomer);

		//find all the line items in this order

		$lineItems=$this->QuoteLineItems->find('all',['conditions'=>['quote_id'=>$thisOrder['quote_id']],'order'=>['sortorder'=>'asc']])->toArray();

		$this->set('lineItems',$lineItems);

		$lineItemsArray=array();

		foreach($lineItems as $lineitem){

			$lineItemsArray[$lineitem['id']]=array();

			$lineItemsArray[$lineitem['id']]['data']=$lineitem;

			$lineItemsArray[$lineitem['id']]['metadata']=array();

			$lineItemsArray[$lineitem['id']]['fabricdata']=array();

			$lineItemMetas=$this->QuoteLineItemMeta->find('all',['conditions'=>['quote_item_id'=>$lineitem['id']]])->toArray();

			$lineItemMetaArray=array();
			foreach($lineItemMetas as $lineitemmeta){
				$lineItemMetaArray[$lineitemmeta['meta_key']]=$lineitemmeta['meta_value'];
			}
			
			foreach($lineItemMetas as $lineitemmeta){

				$lineItemsArray[$lineitem['id']]['metadata'][$lineitemmeta['meta_key']]=$lineitemmeta['meta_value'];	

				if($lineitemmeta['meta_key']=='fabricid' && $lineitemmeta['meta_value'] != '0'){

					$thisFabric=$this->Fabrics->get($lineitemmeta['meta_value'])->toArray();

					$lineItemsArray[$lineitem['id']]['fabricdata']=$thisFabric;

				}elseif($lineitemmeta['meta_key']=='fabricid' && $lineitemmeta['meta_value'] == '0'){
					
					$thisFabric=array('fabric_name' => $lineItemMetaArray['fabric_name'], 'color' => $lineItemMetaArray['fabric_color']);
					$lineItemsArray[$lineitem['id']]['fabricdata']=$thisFabric;
					
				}

			}

		}

		$this->set('productTypeCounts',array('cc'=>$numCC,'bs'=>$numBS,'wt'=>$numWT));

		$this->set('lineItems',$lineItemsArray);

		

	}

	

	public function packingslipform($orderID){

		
/**PPSASCRUM-29 start */
		//$thisOrder=$this->Orders->get($orderID)->toArray();
		$thisOrder=$this->WorkOrders->get($orderID)->toArray();	
/**PPSASCRUM-29 end */
		$this->set('orderData',$thisOrder);

		
		$allShipMethods=array();
		$shipMethodsLookup=$this->ShippingMethods->find('all')->toArray();
		foreach($shipMethodsLookup as $shipMethod){
			$allShipMethods[$shipMethod['id']]=$shipMethod['name'];
		}
		$this->set('allShipMethods',$allShipMethods);



		$thisCustomer=$this->Customers->get($thisOrder['customer_id'])->toArray();

		$this->set('customerData',$thisCustomer);
		/**PPSASCRUM-7 start **/
		if($thisOrder['userType'] != "customer"){
		    if($thisOrder['shipto_id'] != NULL && $thisOrder['userType'] != 'default') {
		    $thisShipTo=$this->ShipTo->get($thisOrder['shipto_id'])->toArray();
		    
		        $this->set('shipToName',$thisShipTo['ship_to_name']);
		    }
		    else if($thisOrder['facility_id'] != NULL) {
		    $thisFacilityDetails= $this->Facility->get($thisOrder['facility_id'])->toArray();
		    $thisShipTo=$this->ShipTo->get($thisFacilityDetails['default_address'])->toArray();
		    $this->set('shipToName',$thisShipTo['ship_to_name']);
		        
		    }
		    else 
		     $this->set('shipToName',$thisCustomer['shipping_name']);

		//$this->set('shipToName',$thisShipTo['ship_to_name']);
		}else 
		{
		    $this->set('shipToName',$thisCustomer['shipping_name']);
		}

		/**PPSASCRUM-7 end **/

		//find all the line items in this order
		/**PPSASCRUM-29 start */
		//$lineItems=$this->QuoteLineItems->find('all',['conditions'=>['quote_id'=>$thisOrder['quote_id']],'order'=>['sortorder'=>'asc']])->toArray();
		$lineItems=$this->WorkOrderLineItems->find('all',['conditions'=>['quote_id'=>$thisOrder['quote_id']],'order'=>['sortorder'=>'asc']])->toArray();
		/**PPSASCRUM-29 end */
		$this->set('lineItems',$lineItems);

		$lineItemsArray=array();

		foreach($lineItems as $lineitem){

			$lineItemsArray[$lineitem['id']]=array();

			$lineItemsArray[$lineitem['id']]['data']=$lineitem;

			$lineItemsArray[$lineitem['id']]['metadata']=array();

			$lineItemsArray[$lineitem['id']]['fabricdata']=array();

			/**PPSASCRUM-29 start */
			//$lineItemMetas=$this->QuoteLineItemMeta->find('all',['conditions'=>['quote_item_id'=>$lineitem['id']]])->toArray();

			$lineItemMetas=$this->WorkOrderLineItemMeta->find('all',['conditions'=>['worder_item_id'=>$lineitem['id']]])->toArray();
			/**PPSASCRUM-29 end */
			$lineItemMetaArray=array();
			foreach($lineItemMetas as $lineitemmeta){
				$lineItemMetaArray[$lineitemmeta['meta_key']]=$lineitemmeta['meta_value'];
			}
			
			foreach($lineItemMetas as $lineitemmeta){

				$lineItemsArray[$lineitem['id']]['metadata'][$lineitemmeta['meta_key']]=$lineitemmeta['meta_value'];	

				if($lineitemmeta['meta_key']=='fabricid' && $lineitemmeta['meta_value'] != '0'){

					$thisFabric=$this->Fabrics->get($lineitemmeta['meta_value'])->toArray();

					$lineItemsArray[$lineitem['id']]['fabricdata']=$thisFabric;

				}elseif($lineitemmeta['meta_key']=='fabricid' && $lineitemmeta['meta_value'] == '0'){
					$thisFabric=array('fabric_name' => $lineItemMetaArray['fabric_name'], 'color' => $lineItemMetaArray['fabric_color']);
					$lineItemsArray[$lineitem['id']]['fabricdata']=$thisFabric;
				}

				if($fabricAlias=$this->getFabricAlias($thisFabric['id'],$thisOrder['customer_id'])){

					$usealias=false;

					foreach($lineItemMetas as $metaloopentry){

						if($metaloopentry['meta_key'] == "usealias" && $metaloopentry['meta_value'] == "yes"){

							$usealias=true;

						}

					}

					if($usealias){

						$lineItemsArray[$lineitem['id']]['fabricdata']['fabric_name']=$fabricAlias['fabric_name'];

						$lineItemsArray[$lineitem['id']]['fabricdata']['color'] = $fabricAlias['color'];

					}

				}

			}

			

			

			//find the matching order item id# for this quote item id#

			$orderItemID=0;

			$lookupOrderItem=$this->OrderItems->find('all',['conditions'=>['quote_line_item_id'=>$lineitem['id']]])->toArray();

			foreach($lookupOrderItem as $orderitemrow){

				$orderItemID=$orderitemrow['id'];

			}

			

			$lineItemsArray[$lineitem['id']]['order_item_id']=$orderItemID;

			

			$alreadyPackagedCount=0;

			//find all previously shipped items in this line

			$shippedItemsLookup=$this->OrderItemStatus->find('all',['conditions'=>['order_line_number'=>$lineitem['line_number'],'order_item_id'=>$orderItemID,'work_order_id'=>$orderID,'status IN' => ['Shipped','Warehoused']]]);

			foreach($shippedItemsLookup as $shippedItemRow){

				$alreadyPackagedCount=($alreadyPackagedCount + floatval($shippedItemRow['qty_involved']));

			}

			

			$lineItemsArray[$lineitem['id']]['previously_shipped']=$alreadyPackagedCount;

		}

		$this->set('productTypeCounts',array('cc'=>$numCC,'bs'=>$numBS,'wt'=>$numWT));

		$this->set('lineItems',$lineItemsArray);

		

		/*if($this->request->data){

			

			$this->set('postdata',$_POST);*/

			

			$this->set('allSettings',$this->getsettingsarray());

			$GLOBALS['pdforientation']='P';

			$GLOBALS['pdfmargins']='custom';

			$GLOBALS['pdfmarginscustom']=array('left'=>6,'top'=>15,'right'=>6,'header'=>20);

		

			foreach($lineItemsArray as $itemID => $itemData){

				if($this->request->data["useline_".$itemID]=="1"){

					//mark this line and qty as warehoused

					$orderItemStatusTable=TableRegistry::get('OrderItemStatus');

					$newItemStatus=$orderItemStatusTable->newEntity();

					$newItemStatus->order_line_number=$itemData['data']['line_number'];

					$newItemStatus->order_item_id=$itemData['order_item_id'];

					$newItemStatus->time=time();

					$newItemStatus->status='Warehoused';

					$newItemStatus->user_id=$this->Auth->user('id');

					$newItemStatus->qty_involved=$this->request->data["qty_in_package_".$itemID];

					$newItemStatus->work_order_id=$orderID;

					$newItemStatus->shipment_id=0;

					$orderItemStatusTable->save($newItemStatus);

					

					

				}

			}
			/* PPSASCRUM-248: start [updating function with arguments as per complete set of function parameters] */
			$this->auditOrderItemStatuses($orderID, false, 'workorder');
			/* PPSASCRUM-248: end */
			

			

			/*

			$packSlipTable=TableRegistry::get('PackingSlips');

			$newPackSlip=$packSlipTable->newEntity();

			$newPackSlip->time=time();

			$newPackSlip->order_id=$orderID;

			$newPackSlip->user_id=$this->Auth->user('id');

			$newPackSlip->packing_slip_number=$this->request->data['packslip_number'];

			$newPackSlip->formdata=json_encode($this->request->data);

			$newPackSlip->shipment_id=0;

			$packSlipTable->save($newPackSlip);

			

		}else{

			//show the form to build this packing slip

			$this->autoRender=false;

			$this->viewBuilder()->layout('default');

			

			

			$this->render('packingslipform');

		}*/

		

	}

	

	

	public function buildbompdf($orderID){

		
		/**PPSASCRUM-29 start */
		//$thisOrder=$this->Orders->get($orderID)->toArray();

		$thisOrder=$this->WorkOrders->get($orderID)->toArray();
		/**PPSASCRUM-29 end */

		$this->set('orderData',$thisOrder);

		

		$fabricsDataArray=array();

		$allFabrics=$this->Fabrics->find('all')->toArray();

		foreach($allFabrics as $fabric){

			$fabricsDataArray[$fabric['id']]=$fabric;

		}

		

		$this->set('fabricsDataArray',$fabricsDataArray);

		

		

		$GLOBALS['pdfmargins']='custom';

		$GLOBALS['pdfmarginscustom']=array('left'=>6,'top'=>4,'right'=>6,'header'=>6);

		

		$thisCustomer=$this->Customers->get($thisOrder['customer_id'])->toArray();

		$this->set('customerData',$thisCustomer);

		

		//find all the line items in this order
		/**PPSASCRUM-29 start */
		//$lineItems=$this->QuoteLineItems->find('all',['conditions'=>['quote_id'=>$thisOrder['quote_id']],'order'=>['sortorder'=>'asc']])->toArray();
		$lineItems=$this->WorkOrderLineItems->find('all',['conditions'=>['quote_id'=>$thisOrder['quote_id']],'order'=>['sortorder'=>'asc']])->toArray();
		/**PPSASCRUM-29 end */

		$this->set('lineItems',$lineItems);

		

		$lineItemsArray=array();

		$COMs=array();

		foreach($lineItems as $lineitem){

			

			

			$lineItemsArray[$lineitem['id']]=array();

			$lineItemsArray[$lineitem['id']]['data']=$lineitem;

			

			$lineItemsArray[$lineitem['id']]['metadata']=array();

			

			$lineItemsArray[$lineitem['id']]['fabricdata']=array();
				$lineItemsArray[$lineitem['id']]['liningsdata']=array();
			
			$lineItemMetaArray=array();

			/**PPSASCRUM-29 start */
			//$lineItemMetas=$this->QuoteLineItemMeta->find('all',['conditions'=>['quote_item_id'=>$lineitem['id']]])->toArray();

			$lineItemMetas=$this->WorkOrderLineItemMeta->find('all',['conditions'=>['worder_item_id'=>$lineitem['id']]])->toArray();
			/**PPSASCRUM-29 end */

			foreach($lineItemMetas as $lineitemmeta){
				$lineItemMetaArray[$lineitemmeta['meta_key']] = $lineitemmeta['meta_value'];
			}
			
			foreach($lineItemMetas as $lineitemmeta){

				$lineItemsArray[$lineitem['id']]['metadata'][$lineitemmeta['meta_key']]=$lineitemmeta['meta_value'];	

				if($lineitemmeta['meta_key']=='fabricid' && $lineitemmeta['meta_value'] != '0'){
if(!empty($lineitemmeta['meta_value'])){
    	$thisFabric=$this->Fabrics->get($lineitemmeta['meta_value'])->toArray();

					$lineItemsArray[$lineitem['id']]['fabricdata']=$thisFabric;
				
}
					

				}elseif($lineitemmeta['meta_key']=='fabricid' && $lineitemmeta['meta_value'] == '0'){
					$thisFabric=array('fabric_name'=>$lineItemMetaArray['fabric_name'], 'color' => $lineItemMetaArray['fabric_color']);
					$lineItemsArray[$lineitem['id']]['fabricdata']=$thisFabric;
				}
			

				if(isset($lineItemMetas['com-fabric']) && $lineItemMetas['com-fabric'] == '1'){
					$lineItemsArray[$lineitem['id']]['fabricdata']['isCOM']=true;
				}else{
					$lineItemsArray[$lineitem['id']]['fabricdata']['isCOM']=false;
				}
				
				/**PPSASCRUM-13 start**/
			/* PPSASCRUM-56: start */
    // 		if(isset($lineItemsArray[$lineitem['id']]['metadata']['linings_id']) && ($lineItemsArray[$lineitem['id']]['metadata']['linings_id']!= 'none' &&  !empty($lineItemsArray[$lineitem['id']]['metadata']['linings_id']))){
		    if(isset($lineItemsArray[$lineitem['id']]['metadata']['linings_id']) && ($lineItemsArray[$lineitem['id']]['metadata']['linings_id']!= 'none' && $lineItemsArray[$lineitem['id']]['metadata']['linings_id']!= 'default' &&  !empty($lineItemsArray[$lineitem['id']]['metadata']['linings_id']))){
	        /* PPSASCRUM-56: end */
                $liningData=$this->Linings->get($lineItemsArray[$lineitem['id']]['metadata']['linings_id'])->toArray();
                $qty = $lineItemsArray[$lineitem['id']]['metadata']['qty'];//$lineItemMetaArray['qty'];
                
                if($lineItemsArray[$lineitem['id']]['metadata']['lineitemtype'] == 'calculator'){
                    //$liningPricePerYd =$lineItemsArray[$lineitem['id']]['metadata']['lining-yds-per-unit']; 
                    if($lineItemsArray[$lineitem['id']]['metadata']['calculator-used']=='box-pleated'){
                         $liningPricePerYd =$lineItemsArray[$lineitem['id']]['metadata']['lining-yards']; 
                    /* PPSASCRUM-56: start */
                    /* PPSASCRUM-384: start */
                    // }elseif($lineItemsArray[$lineitem['id']]['metadata']['calculator-used']=='pinch-pleated'){
                    } elseif (
                        $lineItemsArray[$lineitem['id']]['metadata']['calculator-used'] == 'pinch-pleated' || $lineItemsArray[$lineitem['id']]['metadata']['calculator-used'] == 'new-pinch-pleated' ||
                        $lineItemsArray[$lineitem['id']]['metadata']['calculator-used'] == 'ripplefold-drapery' || $lineItemsArray[$lineitem['id']]['metadata']['calculator-used'] == 'accordiafold-drapery'
                    ) {
                    /* PPSASCRUM-384: end */
                    /* PPSASCRUM-56: end */
                         $liningPricePerYd =$lineItemsArray[$lineitem['id']]['metadata']['lining-yds-per-unit']; 
                    }elseif($lineItemsArray[$lineitem['id']]['metadata']['calculator-used']=='straight-cornice'){
                         $liningPricePerYd =$lineItemsArray[$lineitem['id']]['metadata']['yds-of-lining']; 
                    }

                }else
                $liningPricePerYd =$lineItemsArray[$lineitem['id']]['metadata']['lining-per-unit'];// (float)$lineItemsArray[$lineitem['id']]['metadata']["lining-price-per-yd"];//$lineItemMetaArray['lining-price-per-yd'];
                    
                $total =  ($liningPricePerYd* $qty);
                $thisFabric=array('fabric_name'=>'Lining', 'color' => $liningData['short_title'],'wo_line_number'=>$lineitem['wo_line_number'],'line_number'=>$lineitem['line_number'],'total'=>$total,'qty'=>$qty,'liningPricePerYd'=>$liningPricePerYd,'test'=>$lineitemmeta);
                $lineItemsLiningsArray[$lineitem['id']]['liningdata']=$thisFabric;
            }
    
    		/**PPSASCRUM-13 end**/
				
			}

		}

		
        $allUsers=$this->Users->find('all')->toArray();
        $this->set('allUsers',$allUsers);
        	/**PPSASCRUM-13 Start**/	$this->set('lineLiningItems',$lineItemsLiningsArray);/**PPSASCRUM-13 end**/

		

		//all purchasing notes
		$pNotes=$this->QuoteBomPurchasingNotes->find('all',['conditions' => ['quote_id' => $thisOrder['quote_id']]])->toArray();
		$this->set('pNotes',$pNotes);
		

		$this->set('productTypeCounts',array('cc'=>$numCC,'bs'=>$numBS,'wt'=>$numWT));

		$this->set('lineItems',$lineItemsArray);

		

		$GLOBALS['pdforientation']='P';

		$GLOBALS['pdfmargins']='custom';

		$GLOBALS['pdfmarginscustom']=array('left'=>6,'top'=>15,'right'=>6,'header'=>20);

	}

	

	

	public function exportorder($orderid){

		$this->autoRender=false;

		//$this->viewBuilder()->layout('textfile');

		

		$orderData=$this->Orders->get($orderid)->toArray();

		

		$orderContactEmail='';

		$contactFirstname='';

		$contactLastname='';

		

		if($orderData['contact_id'] > 0){

			$orderContact=$this->CustomerContacts->get($orderData['contact_id'])->toArray();

			$orderContactEmail=$orderContact['email_address'];

			$contactFirstname=$orderContact['first_name'];

			$contactLastname=$orderContact['last_name'];

		}

		

		$orderCustomer=$this->Customers->get($orderData['customer_id'])->toArray();

		

		

		//qb account line(s)

		echo "!ACCNT	NAME	ACCNTTYPE	DESC	ACCNUM	EXTRA\n";

		echo "ACCNT	Accounts Receivable	AR		1200	\n";

		echo "ACCNT	Cubicle Curtains	INC		4100	\n";

		echo "ACCNT	Bedspreads	INC	4105	\n";

		echo "ACCNT	Inventory Asset	OCASSET	1120	INVENTORYASSET\n";

		echo "ACCNT	Cost of Goods Sold	COGS	Cost of Goods Sold	5000	COGS\n";

		//invoice item(s)
		echo "!INVITEM	NAME	INVITEMTYPE	DESC	PURCHASEDESC	ACCNT	ASSETACCNT	COGSACCNT	PRICE	COST	TAXABLE	PAYMETH	TAXVEND	TAXDIST	PREFVEND	REORDERPOINT	EXTRA\n";

		

		

		echo "!CLASS	NAME\n";

		echo "CLASS	class\n";

		

		//customer line(s)

		echo "!CUST	NAME	BADDR1	BADDR2	BADD3	BADDR4	BADDR5	SADDR1	SADDR2	SADDR3	SADDR4	SADDR5	PHONE1	PHONE2	FAXNUM	EMAIL	NOTE	CONT1	CONT2	CTYPE	TERMS	TAXABLE	LIMIT	RESALENUM	REP	TAXITEM	NOTEPAD	SALUTATION	COMPANYNAME	FIRSTNAME	MIDINIT	LASTNAME\n";

		echo "CUST	".$orderCustomer['company_name']."	".$orderCustomer['billing_address']."	".$orderCustomer['billing_city'].", ".$orderCustomer['billing_state']." ".$orderCustomer['billing_zipcode']."				".$orderData['shipping_address_1']."	".$orderData['shipping_address_2']."	".$orderData['shipping_city'].", ".$orderData['shipping_state']." ".$orderData['shipping_zipcode']."			".$orderCustomer['phone']."		".$orderCustomer['fax']."	".$orderContactEmail."	NOTE	CONT1	CONT2	CTYPE	TERMS	TAXABLE	LIMIT	RESALENUM	REP	TAXITEM	NOTEPAD	SALUTATION	".$orderCustomer['company_name']."	".$contactFirstname."		".$contactLastname."\n";

		

		//vendor line(s)

		echo "!VEND	NAME	PRINTAS	ADDR1	ADDR2	ADDR3	ADDR4	ADDR5	VTYPE	CONT1	CONT2	PHONE1	PHONE2	FAXNUM	EMAIL	NOTE	TAXID	LIMIT	TERMS	NOTEPAD	SALUTATION	COMPANYNAME	FIRSTNAME	MIDINIT	LASTNAME\n";

	

		

		//transaction line(2)

		

		

		//invoice line item(s)

		

		

	}

	

	

	public function schedule(){

	/*	$lineSchedule=array();

		

		$scheduledOrders=$this->Orders->find('all',['conditions'=>['completed'=>0,'status !=' => 'Needs Line Items','sherry_status !='=>'Not Scheduled']])->toArray();

		foreach($scheduledOrders as $order){

			

			$thisCustomer=$this->Customers->get($order['customer_id'])->toArray();

			

			$quoteLineItems=$this->QuoteLineItems->find('all',['conditions'=>['quote_id'=>$order['quote_id']],'order'=>['sortorder'=>'asc']])->toArray();

			foreach($quoteLineItems as $quoteItem){

				$thisitemtype='';

				

				$lineItemMetas=$this->QuoteLineItemMeta->find('all',['conditions' => ['quote_item_id' => $quoteItem['id']]])->toArray();

				$itemMeta=array();

				foreach($lineItemMetas as $lineItemMetaRow){

					$itemMeta[$lineItemMetaRow['meta_key']]=$lineItemMetaRow['meta_value'];

				}

				

				switch($quoteItem['product_type']){

					case "bedspreads":

						$thisitemtypelabel="Bedspread";

						$thisitemtype='BS';

					break;

					case "cubicle_curtains":

						$thisitemtypelabel="Cubicle Curtain";

						$thisitemtype='CC';

					break;

					case "window_treatments":

						$thisitemtypelabel=$itemMeta['wttype'];

						switch($itemMeta['wttype']){

							case "Pinch Pleated Drapery":

								$thisitemtype='DRAPE';

							break;

							default:

								$thisitemtype='WT';

							break;

						}

						

					break;

					case "calculator":

						switch($quoteItem['calculator_used']){

							case "pinch-pleated":
							// PPSASCRUM-56: start
							case "new-pinch-pleated":
							// PPSASCRUM-56: end

								$thisitemtypelabel="Pinch Pleated Drapery";

								$thisitemtype='DRAPE';

							break;

							case "bedspread":

								$thisitemtypelabel="Bedspread";

								$thisitemtype='BS';

							break;

							case "cubicle-curtain":

								$thisitemtypelabel="Cubicle Curtain";

								$thisitemtype='CC';

							break;

							case "box-pleated":

								$thisitemtypelabel=$itemMeta['valance-type']." Valance";

								$thisitemtype='WT';

							break;

							case "straight-cornice":

								$thisitemtypelabel=$itemMeta['cornice-type']." Cornice";

								$thisitemtype='WT';

							break;

						}

					break;

				}

				

			

				$statuses=$this->OrderItemStatus->find('all',['conditions'=>['work_order_id'=>$order['id'],'order_line_number'=>$quoteItem['line_number'],'status !=' => 'Not Started'], 'order'=>['time'=>'desc']])->hydrate(false)->toArray();

			

				foreach($statuses as $status){

					$lineSchedule[]=array(

						'statusid'=>$status['id'],

						'statustext'=>$status['status'],

						'wo_number'=>$order['order_number'],

						'customer'=>$thisCustomer['company_name'],

						'timestamp'=>$status['time'],

						'line_number'=>$status['order_line_number'],

						'orderid'=>$order['id'],

						'qty_involved'=>$status['qty_involved'],

						'total_line_qty'=>$quoteItem['qty'],

						'itemtypelabel'=>$thisitemtypelabel,

						'itemtype'=>$thisitemtype,

						'difficulty'=>(floatval($itemMeta['difficulty-rating'])*floatval($quoteItem['qty'])),

						'laborlf'=>(floatval($itemMeta['labor-billable']) * floatval($quoteItem['qty']))

						);

				}

			}

		}

		$this->set('lineSchedule',$lineSchedule);*/

	}

	

	

	

	

	public function getunscheduled(){

		

		$orders=array();

		

		$overallTotalRows=0;

		

		//lookup schedules

		$allUnscheduledOrders=$this->Orders->find('all',['conditions'=>['completed'=>0,'status !=' => 'Needs Line Items','sherry_status'=>'Not Scheduled']])->toArray();

		foreach($allUnscheduledOrders as $order){

			

				$customerData=$this->Customers->get($order['customer_id'])->toArray();

				$userData=$this->Users->get($order['user_id'])->toArray();

				$quoteData=$this->Quotes->get($order['quote_id'])->toArray();

				if($quoteData['project_id'] == '0'){

					$projectName='';

				}else{

					$projectData=$this->Projects->get($quoteData['project_id'])->toArray();

					$projectName=$projectData['title'];

				}

			

				$overallTotalRows++;

				$orderTitle='';

				if(strlen(trim($quote['title'])) >0){

					$orderTitle .= "<b>".$quote['title']."</b><br>";

				}

				$orderTitle .= $customerData['company_name'];

				$orderstatus="<div class=\"orderlabel\">".ucfirst($order['status']);

				$addclasses='';

				if($order['due'] > time() && $order['due'] <= (time()+432000)){

					$addclasses .= " duesoon";

					$orderstatus .= " (DUE SOON)";

				}

				if($order['due'] < time() && $order['due'] > 1000){

					$addclasses .= " pastdue";

					$orderstatus .= " (PAST DUE)";

				}

				if($order['status']=="On Hold"){

					$addclasses .= " onhold";

				}

				if($order['status']=="Complete"){

					$addclasses .= " complete";

				}

				if($order['status'] == 'Canceled'){

					$addclasses .= " canceled";

				}

				$orderstatus .= "</div>";

				/*

				$completedpercent=$this->getOrderProgressPercent($order['id']);

				$orderstatus .= "<div class=\"progressbar\"><div class=\"progresscompleted\" style=\"width:".$completedpercent['percent']."%;\"></div></div>

				<div class=\"percentlabel\">".$completedpercent['completed']." / ".$completedpercent['total']." (".$completedpercent['percent']."%) Completed</div>";

				*/

			

				$totals='';

				if($order['cc_dollar'] >0){

					$totals .= 'CC: $'.number_format($order['cc_dollar'],2,'.',',').'<br>';

				}

				if($order['track_dollar'] > 0){

					$totals .= 'TRK: $'.number_format($order['track_dollar'],2,'.',',').'<br>';

				}

				if($order['bs_dollar'] > 0){

					$totals .= 'BS: $'.number_format($order['bs_dollar'],2,'.',',').'<br>';

				}

				if($order['drape_dollar'] >0){

					$totals .= 'DRAPERIES: $'.number_format($order['drape_dollar'],2,'.',',').'<br>';

				}

				if($order['val_dollar'] >0){

					$totals .= 'VAL: $'.number_format($order['val_dollar'],2,'.',',').'<br>';

				}

				if($order['corn_dollar'] >0){

					$totals .= 'CORN: $'.number_format($order['corn_dollar'],2,'.',',').'<br>';

				}

				if($order['wt_hw_dollar'] >0){

					$totals .= 'WTHW: $'.number_format($order['wt_hw_dollar'],2,'.',',').'<br>';

				}

				if($order['blinds_dollar'] >0){

					$totals .= 'B&amp;S: $'.number_format($order['blinds_dollar'],2,'.',',').'<br>';

				}

				if($order['fab_dollars'] >0){

					$totals .= 'FAB: $'.number_format($order['fab_dollars'],2,'.',',').'<br>';

				}

				if($order['measure_dollars'] >0){

					$totals .= 'MEAS: $'.number_format($order['measure_dollars'],2,'.',',').'<br>';

				}

				if($order['install_dollars'] >0){

					$totals .= 'INST: $'.number_format($order['install_dollars'],2,'.',',').'';

				}

				$totals .= "<hr style=\"margin:0; height:4px;\" />";

				$totals .= '<strong>$'.number_format($order['order_total'],2,'.',',').'</strong>';

				if($order['due'] > 1000){

					$duedate=date('n/j/Y',$order['due']);

				}else{

					$duedate='---';

				}

				$orders[]=array(

					'DT_RowId'=>'row_'.$order['id'],

					'DT_RowClass'=>'rowtest'.$addclasses,

					'0' => '<a href="/orders/scheduleorder/'.$order['id'].'"/><img src="/img/calendar.png" /></a>',

					'1' => $order['order_number'],

					'2' => $orderTitle,

					'3' => $order['po_number'],

					'4' => date('n/j/Y - g:iA',$order['created']),

					'5' => $projectName,

					'6' => $order['facility'],

					'7' => $duedate,

					'8' => $totals,

					'9' => $order['cc_qty'],

					'10' => $order['cc_lf'],

					'11' => $order['cc_diff'],

					'12' => $order['track_qty'],

					'13' => $order['bs_qty'],

					'14' => $order['bs_diff'],

					'15' => $order['drape_qty'],

					'16' => $order['drape_widths'],

					'17' => $order['drape_diff'],

					'18' => $order['tt_qty'],

					'19' => $order['tt_lf'],

					'20' => $order['tt_diff'],

					'21' => $order['wt_hw_qty'],

					'22' => $order['blinds_qty'],

					'23' => ''

				);

			

		}

	

		$totalFilteredRows=$overallTotalRows;

		

		$this->set('draw',$draw);

		$this->set('recordsTotal',$overallTotalRows);

		$this->set('recordsFiltered',$totalFilteredRows);

		$this->set('data',$orders);

		$this->set('_serialize',['draw','recordsTotal','recordsFiltered','data']);

		

	}

	

	

	

	public function getscheduled(){

		

		$orders=array();

		

		$overallTotalRows=0;

		

		//lookup schedules

		$allScheduledOrders=$this->Orders->find('all',['conditions'=>['completed'=>0,'status !=' => 'Needs Line Items','sherry_status IN'=>['Partially Scheduled','Fully Scheduled']]])->toArray();

		foreach($allScheduledOrders as $order){

			

				$customerData=$this->Customers->get($order['customer_id'])->toArray();

				$userData=$this->Users->get($order['user_id'])->toArray();

				$quoteData=$this->Quotes->get($order['quote_id'])->toArray();

				if($quoteData['project_id'] == '0'){

					$projectName='';

				}else{

					$projectData=$this->Projects->get($quoteData['project_id'])->toArray();

					$projectName=$projectData['title'];

				}

			

				$overallTotalRows++;

				$orderTitle='';

				if(strlen(trim($quote['title'])) >0){

					$orderTitle .= "<b>".$quote['title']."</b><br>";

				}

				$orderTitle .= $customerData['company_name'];

				$orderstatus="<div class=\"orderlabel\">".ucfirst($order['status']);

				$addclasses='';

				if($order['due'] > time() && $order['due'] <= (time()+432000)){

					$addclasses .= " duesoon";

					$orderstatus .= " (DUE SOON)";

				}

				if($order['due'] < time() && $order['due'] > 1000){

					$addclasses .= " pastdue";

					$orderstatus .= " (PAST DUE)";

				}

				if($order['status']=="On Hold"){

					$addclasses .= " onhold";

				}

				if($order['status']=="Complete"){

					$addclasses .= " complete";

				}

				if($order['status'] == 'Canceled'){

					$addclasses .= " canceled";

				}

				$orderstatus .= "</div>";

				/*

				$completedpercent=$this->getOrderProgressPercent($order['id']);

				$orderstatus .= "<div class=\"progressbar\"><div class=\"progresscompleted\" style=\"width:".$completedpercent['percent']."%;\"></div></div>

				<div class=\"percentlabel\">".$completedpercent['completed']." / ".$completedpercent['total']." (".$completedpercent['percent']."%) Completed</div>";

				*/

			

				$totals='';

				if($order['cc_dollar'] >0){

					$totals .= 'CC: $'.number_format($order['cc_dollar'],2,'.',',').'<br>';

				}

				if($order['track_dollar'] > 0){

					$totals .= 'TRK: $'.number_format($order['track_dollar'],2,'.',',').'<br>';

				}

				if($order['bs_dollar'] > 0){

					$totals .= 'BS: $'.number_format($order['bs_dollar'],2,'.',',').'<br>';

				}

				if($order['drape_dollar'] >0){

					$totals .= 'DRAPERIES: $'.number_format($order['drape_dollar'],2,'.',',').'<br>';

				}

				if($order['val_dollar'] >0){

					$totals .= 'VAL: $'.number_format($order['val_dollar'],2,'.',',').'<br>';

				}

				if($order['corn_dollar'] >0){

					$totals .= 'CORN: $'.number_format($order['corn_dollar'],2,'.',',').'<br>';

				}

				if($order['wt_hw_dollar'] >0){

					$totals .= 'WTHW: $'.number_format($order['wt_hw_dollar'],2,'.',',').'<br>';

				}

				if($order['blinds_dollar'] >0){

					$totals .= 'B&amp;S: $'.number_format($order['blinds_dollar'],2,'.',',').'<br>';

				}

				if($order['fab_dollars'] >0){

					$totals .= 'FAB: $'.number_format($order['fab_dollars'],2,'.',',').'<br>';

				}

				if($order['measure_dollars'] >0){

					$totals .= 'MEAS: $'.number_format($order['measure_dollars'],2,'.',',').'<br>';

				}

				if($order['install_dollars'] >0){

					$totals .= 'INST: $'.number_format($order['install_dollars'],2,'.',',').'';

				}

				$totals .= "<hr style=\"margin:0; height:4px;\" />";

				$totals .= '<strong>$'.number_format($order['order_total'],2,'.',',').'</strong>';

				if($order['due'] > 1000){

					$duedate=date('n/j/Y',$order['due']);

				}else{

					$duedate='---';

				}

				$orders[]=array(

					'DT_RowId'=>'row_'.$order['id'],

					'DT_RowClass'=>'rowtest'.$addclasses,

					'0' => '<a href="/orders/vieworderschedule/'.$order['id'].'"><img src="/img/viewschedule.png" title="Batch Breakdown" alt="Batch Breakdown" /></a>',

					'1' => $order['order_number'],

					'2' => $orderTitle,

					'3' => $order['po_number'],

					'4' => date('n/j/Y - g:iA',$order['created']),

					'5' => $projectName,

					'6' => $order['facility'],

					'7' => $duedate,

					'8' => $totals,

					'9' => $order['cc_qty'],

					'10' => $order['cc_lf'],

					'11' => $order['cc_diff'],

					'12' => $order['track_qty'],

					'13' => $order['bs_qty'],

					'14' => $order['bs_diff'],

					'15' => $order['drape_qty'],

					'16' => $order['drape_widths'],

					'17' => $order['drape_diff'],

					'18' => $order['tt_qty'],

					'19' => $order['tt_lf'],

					'20' => $order['tt_diff'],

					'21' => $order['wt_hw_qty'],

					'22' => $order['blinds_qty'],

					'23' => ''

				);

			

		}

	

		$totalFilteredRows=$overallTotalRows;

		

		$this->set('draw',$draw);

		$this->set('recordsTotal',$overallTotalRows);

		$this->set('recordsFiltered',$totalFilteredRows);

		$this->set('data',$orders);

		$this->set('_serialize',['draw','recordsTotal','recordsFiltered','data']);

		

	}

	

	public function deletesherrybatch($batchID){
		if($this->request->data){
		    //confirm there are no Boxes before proceeding...
		    
		    $boxLookup=$this->SherryBatchBoxes->find('all',['conditions' => ['batch_id' => $batchID]])->toArray();
		    if(count($boxLookup) > 0){
		        $this->Flash->error('You cannot delete a batch that still contains Boxes');
		        return $this->redirect('/orders/schedule/');
		    }else{
		    
    			//do the delete
    			$batchTable=TableRegistry::get('SherryBatches');
    			$thisBatch=$batchTable->get($batchID);
    			$thisOrder=$this->Orders->get($thisBatch->work_order_id)->toArray();
    
    			$thisDate=date('Y-m-d',strtotime($thisBatch->date.' 09:00:00'));
    
    			if($batchTable->delete($thisBatch)){
    
    				$orderitemstatustable=TableRegistry::get('WorkOrderItemStatus');
    				$allItemStatuses=$this->WorkOrderItemStatus->find('all',['conditions'=>['sherry_batch_id' => $batchID]])->toArray();
    				foreach($allItemStatuses as $itemStatusRow){
    					$thisItemStatus=$orderitemstatustable->get($itemStatusRow['id']);
    					$orderitemstatustable->delete($thisItemStatus);
    				}
    
    				$sherryCacheTable=TableRegistry::get('SherryCache');
    				$sherryFind=$this->SherryCache->find('all',['conditions'=>['batch_id'=>$batchID]])->toArray();
    				foreach($sherryFind as $sherryCacheRow){
    					$thisSCRow=$sherryCacheTable->get($sherryCacheRow['id']);
    					$sherryCacheTable->delete($thisSCRow);
    				}
    
    				$this->updatesherrycachefordate($thisDate);
    				/* PPSASCRUM-248: start [updating function with arguments as per complete set of function parameters] */
					$this->auditOrderItemStatuses($thisOrder['id'], false, 'workorder');
					/* PPSASCRUM-248: end */
    
    				$this->logActivity($_SERVER['REQUEST_URI'],'Deleted Sherry Batch ID '.$batchID);
    				$this->Flash->success('Successfully deleted selected sherry schedule batch');
    				return $this->redirect('/orders/schedule/');
    			}
		    }

		}else{
			
			$boxLookup=$this->SherryBatchBoxes->find('all',['conditions' => ['batch_id' => $batchID]])->toArray();
		    if(count($boxLookup) > 0){
		        $this->autoRender=false;
		        $this->render('batchcontainsboxeserror');
		    }else{
		        //confirm the batch deletion
    			$thisBatch=$this->SherryBatches->get($batchID)->toArray();
    			$thisOrder=$this->WorkOrders->get($thisBatch['work_order_id'])->toArray();
    			$thisQuote=$this->Quotes->get($thisOrder['quote_id'])->toArray();
    			$quoteLineItems=$this->WorkOrderLineItems->find('all',['conditions'=>['quote_id'=>$thisQuote['id']]])->toArray();
    			$orderLineItems=$this->WorkOrderItems->find('all',['conditions'=>['order_id'=>$thisOrder['id']]])->toArray();
    
    			$quoteItemMetas=array();
    			foreach($quoteLineItems as $lineItem){
    				$quoteItemMetas[$lineItem['id']]=array();
    				$itemMetas=$this->WorkOrderLineItemMeta->find('all',['conditions'=>['worder_item_id' => $lineItem['id']]])->toArray();
    				foreach($itemMetas as $metaRow){
    					$quoteItemMetas[$lineItem['id']][$metaRow['meta_key']] = $metaRow['meta_value'];
    				}
    			}
    
    			$otherBatches=$this->SherryBatches->find('all',['conditions'=>['work_order_id'=>$thisOrder['id'],'id !='=>$batchID]])->toArray();
    
    
    			$this->set('thisBatch',$thisBatch);
    			$this->set('thisOrder',$thisOrder);
    			$this->set('thisQuote',$thisQuote);
    			$this->set('quoteLineItems',$quoteLineItems);
    			$this->set('orderLineItems',$orderLineItems);
    			$this->set('quoteItemMetas',$quoteItemMetas);
    			$this->set('otherBatches',$otherBatches);
		    }

		}
	}

	

	public function scheduleorder($orderID,$ignoreTallyFlag=0){

		$this->autoRender=false;
		$thisOrder=$this->WorkOrders->get($orderID)->toArray();
	//	$thisOrder=$this->WorkOrders->find('all',['conditions'=>['id'=>$orderID]])->toArray();
		$this->set('orderData',$thisOrder);
		if($this->request->data){
        
			$lineItems=$this->WorkOrderLineItems->find('all',['conditions'=>['quote_id'=>$thisOrder['quote_id'],'order_id'=>$thisOrder['id']],'order'=>['sortorder'=>'asc']])->toArray();

			$this->set('lineItems',$lineItems);

			$numScheduled=0;

			//create a new BATCH to assign these line items to
			$batchTable=TableRegistry::get('SherryBatches');
			$newBatch=$batchTable->newEntity();

			$providedDate=$this->request->data['dateselection']; //mm/dd/yyyy
			$dateSplit=explode("/",$providedDate);
			$batchdate=$dateSplit[2].'-'.$dateSplit[0].'-'.$dateSplit[1];

			$newBatch->date=$batchdate;
			$newBatch->work_order_id=$orderID;
			$newBatch->work_order_number=$thisOrder['order_number'];
			$newBatch->completed_date=0;
			$newBatch->shipped_date=0;

			$batchTable->save($newBatch);

			$batchID=$newBatch->id;
			
    //             print_r($lineItems);
    // 			die();  
			
			foreach($lineItems as $lineitem){

    //             print_r($lineitem);
				// die();  
				$orderItemID=0;
		        $conditions=array();
                $conditions += array('quote_line_item_id' => $lineitem['id']);
                // $conditions += array('quote_line_item_id' => $lineitem['quote_line_item_id']);
                // $conditions += array('355270');
                // print_r($conditions);
                // print_r("===========");
                // print_r($lineitem['quote_line_item_id']);
				
				$lookupOrderItem=$this->WorkOrderItems->find('all',['conditions'=>$conditions])->toArray();

    //             print_r($lookupOrderItem);
				// die();
				foreach($lookupOrderItem as $orderitemrow){

					$orderItemID=$orderitemrow['id'];

				}
				// print_r($orderItemID);
				// die();
				if($this->request->data["useline_".$lineitem['id']] == "1"){

					$scheduleTable=TableRegistry::get('WorkOrderItemStatus');

					
					$newSchedule=$scheduleTable->newEntity();

					$newSchedule->order_line_number=$lineitem['line_number'];

					$newSchedule->order_item_id=$orderItemID;

					$newSchedule->time=strtotime($this->request->data['dateselection'].' 18:00:00');

					$newSchedule->status='Scheduled';

					$newSchedule->user_id=$this->Auth->user('id');

					$newSchedule->qty_involved=$this->request->data["qty_line_".$lineitem['id']];

					$newSchedule->work_order_id=$orderID;

					$newSchedule->sherry_batch_id=$batchID;

					$newSchedule->shipment_id=0;
				// 	print_r($newSchedule); 
				//     die();
					if($scheduleTable->save($newSchedule)){
						
						$numScheduled++;

						$this->logActivity($_SERVER['REQUEST_URI'],'Scheduled '.$this->request->data["qty_line_".$lineitem['id']].' items of Line '.$lineitem['line_number'].' on Order '.$thisOrder['order_number'].' (Batch #'.$batchID.', Date: '.$this->request->data['dateselection'].')');

					}

				}

			}
            // die();
			

// 			$this->auditOrderItemStatuses($orderID);
			//using workorder paramaeter to fetch workorder data inseted of orders data. PPSA-248 413
			$this->auditOrderItemStatuses($orderID, 0, 'workorder');

			$correctedDate=date('Y-m-d',strtotime($this->request->data['dateselection'].' 08:00:00'));
			$this->updatesherrycachefordate($correctedDate);
			
			//return $this->redirect('/orders/schedule/#unscheduled');
			echo "Processing...
			<script>
			function createCookie(name, value, days) {
                var expires;
            
                if (days) {
                    var date = new Date();
                    date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
                    expires = \"; expires=\" + date.toGMTString();
                } else {
                    expires = \"\";
                }
                document.cookie = encodeURIComponent(name) + \"=\" + encodeURIComponent(value) + expires + \"; path=/\";
            }
            
			createCookie('newbatchsuccess_".$this->request->data['batch_cookie_id']."','doredirect',1);
			window.location.replace('/orders/viewbatchschedule/".$batchID."');
			</script>";
			
		}else{
			//$thisCustomer=$this->Customers->get($thisOrder['customer_id'])->toArray();
			$thisCustomer=$this->Customers->find('all',['conditions'=>['id'=>$thisOrder['customer_id']]])->toArray();
			$this->set('customerData',$thisCustomer);
			//find all the line items in this order
			$lineItems=$this->WorkOrderLineItems->find('all',['conditions'=>['order_id'=>$orderID],'order'=>['sortorder'=>'asc']])->toArray();

			$this->set('lineItems',$lineItems);
			$lineItemsArray=array();
			foreach($lineItems as $lineitem){

				$lineItemsArray[$lineitem['id']]=array();

				$lineItemsArray[$lineitem['id']]['data']=$lineitem;

				$lineItemsArray[$lineitem['id']]['metadata']=array();

				$lineItemsArray[$lineitem['id']]['fabricdata']=array();

				$lineItemMetas=$this->WorkOrderLineItemMeta->find('all',['conditions'=>['worder_item_id'=>$lineitem['id']]])->toArray();

				foreach($lineItemMetas as $lineitemmeta){

					$lineItemsArray[$lineitem['id']]['metadata'][$lineitemmeta['meta_key']]=$lineitemmeta['meta_value'];	

					if($lineitemmeta['meta_key']=='fabricid' && $lineitemmeta['meta_value'] != '' && $lineitemmeta['meta_value'] != '0'){

						$thisFabric=$this->Fabrics->get($lineitemmeta['meta_value'])->toArray();

						$lineItemsArray[$lineitem['id']]['fabricdata']=$thisFabric;

					}elseif($lineitemmeta['meta_key']=='fabricid' && ($lineitemmeta['meta_value'] == '' || $lineitemmeta['meta_value'] == '0')){
						$thisFabric=array();
						foreach($lineItemMetas as $submetaloop){
							if($submetaloop['meta_key'] == 'fabric_name'){
								$thisFabric['fabric_name']=$submetaloop['meta_value'];
							}elseif($submetaloop['meta_key'] == 'fabric_color'){
								$thisFabric['fabric_color'] = $submetaloop['meta_value'];
							}
						}
						$lineItemsArray[$lineitem['id']]['fabricdata']=$thisFabric;
					}

				}

				//find the matching order item id# for this quote item id#
				$orderItemID=0;

				$lookupOrderItem=$this->WorkOrderItems->find('all',['conditions'=>['quote_line_item_id'=>$lineitem['id']]])->toArray();

				foreach($lookupOrderItem as $orderitemrow){
					$orderItemID=$orderitemrow['id'];

				}
				$lineItemsArray[$lineitem['id']]['order_item_id']=$orderItemID;

				$alreadyScheduledCount=0;
				//find all previously shipped items in this line
				$scheduledItemsLookup=$this->WorkOrderItemStatus->find('all',['conditions'=>['order_line_number'=>$lineitem['line_number'],'order_item_id'=>$orderItemID,'work_order_id'=>$orderID,'status IN' => ['Scheduled']]]);
				foreach($scheduledItemsLookup as $scheduledItemRow){

					$alreadyScheduledCount=($alreadyScheduledCount + floatval($scheduledItemRow['qty_involved']));
					

				}
				$lineItemsArray[$lineitem['id']]['previously_scheduled']=$alreadyScheduledCount;
			}
			$this->set('productTypeCounts',array('cc'=>$numCC,'bs'=>$numBS,'wt'=>$numWT));

			$this->set('lineItems',$lineItemsArray);

			$this->set('allSettings',$this->getsettingsarray());
			
			$this->set('ignoreTallyFlag',$ignoreTallyFlag);

			$this->render('scheduleform');
		}

	}



	public function vieworderschedule($orderID){
		$thisOrder=$this->WorkOrders->get($orderID)->toArray();
// 		print_r($thisOrder); die();
		//$thisOrderQuote=$this->Quotes->find('all',['conditions'=>['id'=>$thisOrder['quote_id']]])->toArray();
		$thisOrderQuote=$this->Quotes->get($thisOrder['quote_id'])->toArray();
// 		print_r($thisOrderQuote); die();
		$this->set('thisOrder',$thisOrder);
		$this->set('thisOrderQuote',$thisOrderQuote);
        /**PPSASCRUM-3 start **/ 
        $customerData=$this->Customers->get($thisOrder['customer_id'])->toArray();
        $this->set('thisCustomer',$customerData);
        /**PPSASCRUM-3 end **/
        
		$orderLines=$this->WorkOrderItems->find('all',['conditions'=>['quote_id'=>$thisOrder['quote_id']]])->toArray();
		$quoteLines=$this->WorkOrderLineItems->find('all',['conditions'=>['quote_id'=>$thisOrder['quote_id']],'order'=>['sortorder'=>'asc']])->toArray();
		
		$quoteLinesData=array();
    // print_r($quoteLines);
    // die();
        $orderBoxes=$this->SherryBatchBoxes->find('all',['conditions' => ['order_id' => $orderID]])->toArray();
// print_r($orderBoxes);
		foreach($quoteLines as $quoteLineData){
			//load in the meta and build the array
			$quoteLinesData[$quoteLineData['id']]=$quoteLineData;
			$quoteLinesData[$quoteLineData['id']]['metadata']=array();
			
					$conditions=array();

				$conditions += array('worder_item_id' => $quoteLineData['id']);
				
				
			$lineItemMetas=$this->WorkOrderLineItemMeta->find('all',['conditions'=>[$conditions]])->toArray();
			foreach($lineItemMetas as $metaRow){
				$quoteLinesData[$quoteLineData['id']]['metadata'][$metaRow['meta_key']]=$metaRow['meta_value'];
			}

			//get fabric data and add it to the array
			$quoteLinesData[$quoteLineData['id']]['fabricdata']=array();

			if(isset($quoteLinesData[$quoteLineData['id']]['metadata']['fabricid']) && intval($quoteLinesData[$quoteLineData['id']]['metadata']['fabricid']) >0){
				$thisFabric=$this->Fabrics->get($quoteLinesData[$quoteLineData['id']]['metadata']['fabricid'])->toArray();
				$quoteLinesData[$quoteLineData['id']]['fabricdata']=$thisFabric;
			}else{
				$quoteLinesData[$quoteLineData['id']]['fabricdata']['fabric_name']='';
				$quoteLinesData[$quoteLineData['id']]['fabricdata']['color']='';
			}

			//find line notes and add them to the array
			$quoteLinesData[$quoteLineData['id']]['linenotes']=array();
			$lineNotes=$this->WorkOrderLineItemNotes->find('all',['conditions'=>['quote_item_id' => $quoteLineData['id']],'order'=>['time'=>'asc']]);
			if(count($lineNotes) >0 ){
				$quoteLinesData[$quoteLineData['id']]['linenotes']=$lineNotes->toArray();
			}
			
			$thisLineBoxes=array();
// 			print_r($orderBoxes);
// 			die();
			//find boxes that have this line item in them, and add them to the array
			foreach($orderBoxes as $box){
			    
			    $thisBoxContents=$this->SherryBatchBoxContents->find('all',['conditions' => ['box_id' => $box['id']]])->toArray();
			    foreach($thisBoxContents as $content){
			        if($content['quote_item_id'] == $quoteLineData['id']){
			            $thisLineBoxes[]=array('box_number' => $box['box_number'],'qty'=>$content['qty'], 'batch_id' => $box['batch_id']);
			        }
			    }
			}
			
			$quoteLinesData[$quoteLineData['id']]['lineboxes']=$thisLineBoxes;

		}

		$allUsers=$this->Users->find('all')->toArray();
		$this->set('allUsers',$allUsers);

		$this->set('orderLines',$orderLines);
		$this->set('quoteLines',$quoteLinesData);


        // print_r($orderID);
		$this->set('allOrderItems',$this->getorderitemsoverall($orderID));
		$this->set('allScheduledItems',$this->getorderscheduleditems($orderID));
		$this->set('allCompletedItems',$this->getordercompleteditems($orderID));
		$this->set('allShippedItems',$this->getordershippeditems($orderID));
		$this->set('allUnscheduledItems',$this->getorderunscheduleditems($thisOrder['id']));
		$this->set('allInvoicedItems',$this->getorderinvoiceditems($orderID));
		//print_r($this->getorderunscheduleditems($orderID));echo "\n";
		
        // print_r("this is allUnscheduledItems list:   ");
        // pr($allUnscheduledItems); 
        
        // var_dump($allOrderItems);
        
        // var_dump($allUnscheduledItems);
        // exit;
		
		$allShipMethods=$this->ShippingMethods->find('all')->toArray();
		$this->set('allShipMethods',$allShipMethods);

		$allBatchesThisWO=array();
		$allBatchesThisWOLookup=$this->SherryBatches->find('all',['conditions'=>['work_order_id' => $orderID],'order'=>['date' => 'asc']])->toArray();

		foreach($allBatchesThisWOLookup as $batch){
			//find notes
			$allBatchesThisWO[$batch['id']]=$batch;
			$allBatchesThisWO[$batch['id']]['notes']=array();
			$batchNotes=$this->SherryBatchNotes->find('all',['conditions' => ['batch_id' => $batch['id']],'order'=>['time'=>'asc']])->toArray();
			foreach($batchNotes as $batchNote){
				$allBatchesThisWO[$batch['id']]['notes'][]=strtoupper($batchNote['note_type']).' NOTE: '.$batchNote['message'];
			}
		}

		$this->set('allBatchesThisWO',$allBatchesThisWO);

		
		$orderBoxes=array();
		$orderBoxesData=$this->SherryBatchBoxes->find('all',['conditions' => ['order_id' => $orderID]])->toArray();
// 		print_r($orderBoxesData);
// 		die();
		foreach($orderBoxesData as $orderBox){
		    $contents=$this->SherryBatchBoxContents->find('all',['conditions' => ['box_id' => $orderBox['id']]])->toArray();
		    $orderBoxes[$orderBox['id']]['boxdata']=$orderBox;
		    $orderBoxes[$orderBox['id']]['contents']=$contents;
		}
// 		print_r($orderBoxes);
// 		die();
		
		$this->set('orderBoxes',$orderBoxes);
	}
 
	

	public function unscheduledorderslist(){

		$orders=array();

		//$this->autoRender=false;

		$db=ConnectionManager::get('default');

		if(isset($this->request->data['start'])){
			$start=$this->request->data['start'];
		}else{
			$start=0;
		}

	
		if(isset($this->request->data['length'])){
			$limit=$this->request->data['length'];
		}else{
			$limit=25;
		}

		
		if(isset($this->request->data['draw'])){
			$draw=$this->request->data['draw'];
		}else{
			$draw=1;
		}
		
		$searchwhere='';
		//let's search customers if necessary
		if(isset($this->request->data['search']['value']) && strlen(trim($this->request->data['search']['value'])) >0){
			
			$customerSearch=$this->Customers->find('all',['conditions'=>['company_name LIKE' => '%'.$this->request->data['search']['value'].'%']])->toArray();
			if(count($customerSearch) > 1){
				$searchwhere = ' AND (d.id IN(';
				foreach($customerSearch as $customerRow){
					$searchwhere .= $customerRow['id'].',';
				}
				$searchwhere=substr($searchwhere,0,(strlen($searchwhere)-1)).')';
				$searchwhere .= ' OR (a.po_number LIKE \'%'.$this->request->data['search']['value'].'%\') OR (a.facility LIKE \'%'.$this->request->data['search']['value'].'%\') OR (a.order_number LIKE \'%'.$this->request->data['search']['value'].'%\') )';
			}elseif(count($customerSearch) == 1){
				$searchwhere = ' AND (d.id=';
				foreach($customerSearch as $customerRow){
					$searchwhere .= $customerRow['id'];
				}
				
				
				$searchwhere .= ' OR (a.po_number LIKE \'%'.$this->request->data['search']['value'].'%\') OR (a.facility LIKE \'%'.$this->request->data['search']['value'].'%\') OR (a.order_number LIKE \'%'.$this->request->data['search']['value'].'%\') )';
			}elseif(count($customerSearch) == 0){
				$searchwhere = ' AND ((a.po_number LIKE \'%'.$this->request->data['search']['value'].'%\') OR (a.facility LIKE \'%'.$this->request->data['search']['value'].'%\') OR (a.order_number LIKE \'%'.$this->request->data['search']['value'].'%\') )';
			}
			
			
		}
		
		//$this->request->data['order'][0]['column']=1;
		//$this->request->data['order'][0]['dir']='desc';
		
		$orderby="a.order_number ASC";
		if(isset($this->request->data['order'][0]['column'])){
			$orderby='';
			switch($this->request->data['order'][0]['column']){
				case 3:
					$orderby="a.po_number ".$this->request->data['order'][0]['dir'];
				break;
				case 6:
					$orderby="a.facility ".$this->request->data['order'][0]['dir'];
				break;
				case 7:
					$orderby="a.due ".$this->request->data['order'][0]['dir'];
				break;
				case 1:
				default:
					$orderby="a.order_number ".$this->request->data['order'][0]['dir'];
				break;
			}
		}

        $overallquery="SELECT a.id,a.status,a.type_id,a.facility,a.customer_id,a.created,a.shipping_method_id,a.order_number,a.due,a.po_number,a.sherry_status, c.type,c.status,c.product_type,c.product_id,c.qty,c.line_number,c.calculator_used, d.company_name FROM orders a, order_items b, quote_line_items c, customers d WHERE (a.status NOT IN ('Canceled','Shipped') AND d.id=a.customer_id AND b.order_id=a.id AND c.id=b.quote_line_item_id AND (a.sherry_status = 'Partially Scheduled' OR a.sherry_status = 'Not Scheduled'))".$searchwhere." GROUP BY a.id ORDER BY ".$orderby;
		
		$query="SELECT a.id,a.status,a.type_id,a.facility,a.customer_id,a.created,a.shipping_method_id,a.order_number,a.due,a.po_number,a.sherry_status, c.type,c.status,c.product_type,c.product_id,c.qty,c.line_number,c.calculator_used, d.company_name FROM orders a, order_items b, quote_line_items c, customers d WHERE a.status NOT IN ('Canceled','Shipped') AND d.id=a.customer_id AND b.order_id=a.id AND c.id=b.quote_line_item_id AND (a.sherry_status = 'Partially Scheduled' OR a.sherry_status = 'Not Scheduled')".$searchwhere." GROUP BY a.id ORDER BY ".$orderby." LIMIT ".$start.",".$limit;

		
		$queryRun=$db->execute($query);
		$unscheduledOrders=$queryRun->fetchAll('assoc');
		$grouped=array();

		$overallQueryRun=$db->execute($overallquery);
		$overallUnscheduledOrders=$overallQueryRun->fetchAll('assoc');

		foreach($unscheduledOrders as $order){

			$orderScheduledDiff=0;
			/**PPSASCRUM-3 start **/ 
		$customerData=$this->Customers->get($order['customer_id'])->toArray();
        /**PPSASCRUM-3 end **/
			

			$outstandingOrderItems=array();
			$orderItems=$this->OrderItems->find('all',['conditions'=>['order_id' => $order['id']]])->toArray();

			foreach($orderItems as $orderLineItem){

				$itemTotalLF=0;
				$itemTotalWidths=0;
                $itemScheduledLF=0;
                $itemScheduledWidths=0;
                try{
                    	$thisQuoteItem=$this->QuoteLineItems->get($orderLineItem['quote_line_item_id'])->toArray();
                    		$itemMetas=$this->QuoteLineItemMeta->find('all',['conditions'=>['quote_item_id' => $thisQuoteItem['id']]])->toArray();

                }  catch (RecordNotFoundException $e) { }
			//	$thisQuoteItem=$this->QuoteLineItems->get($orderLineItem['quote_line_item_id'])->toArray();
			
				if($thisQuoteItem['parent_line'] == 0){

                    if($thisQuoteItem['product_type'] == 'newcatchall-hardware' && $thisQuoteItem['product_subclass'] == 3){
					    $itemTotalLF = ($itemTotalLF + floatval($thisQuoteItem['qty']));
				    /* PPSASCRUM-56: start */
					}elseif($thisQuoteItem['product_type'] == 'newcatchall-drapery' || ($thisQuoteItem['calculator_used'] == 'pinch-pleated' || $thisQuoteItem['calculator_used'] == 'pinch-pleated-new' || $thisQuoteItem['calculator_used'] == 'new-pinch-pleated') ){
					/* PPSASCRUM-56: end */
					    
					    foreach($itemMetas as $itemMetaRow){
						    if($thisQuoteItem['product_type'] == 'newcatchall-drapery'){
						        if($itemMetaRow['meta_key'] == 'labor-billable-widths'){
							        $itemTotalWidths = ($itemTotalWidths + (floatval($itemMetaRow['meta_value']) * floatval($thisQuoteItem['qty'])));
						        }
						    }else{
						        if($itemMetaRow['meta_key'] == 'labor-widths'){
							        $itemTotalWidths = ($itemTotalWidths + (floatval($itemMetaRow['meta_value']) * floatval($thisQuoteItem['qty'])));
						        }
						    }
						
					    }
					    
					}else{

					    foreach($itemMetas as $itemMetaRow){
						
						    if($itemMetaRow['meta_key'] == 'labor-billable'){
							    //$itemTotalLF = ($itemTotalLF + floatval($itemMetaRow['meta_value']));
							    $itemTotalLF = ($itemTotalLF + (floatval($itemMetaRow['meta_value']) * floatval($thisQuoteItem['qty'])));
						    }
						
					    }
					}

					$totalQTY=$thisQuoteItem['qty'];

					//loop through all schedules on this order, this line number
					$usedBatches=array();

					$thisOrderLineItemStatuses=$this->OrderItemStatus->find('all',['conditions'=>['work_order_id' => $order['id'],'order_line_number' => $thisQuoteItem['line_number'],'status IN' => ['Scheduled','In Progress','Completed','Warehoused','Shipped','Invoiced']],'order'=>['time'=>'desc']])->toArray();
					foreach($thisOrderLineItemStatuses as $lineStatus){
						if(!in_array($lineStatus['sherry_batch_id'],$usedBatches)){
							$totalQTY=($totalQTY - floatval($lineStatus['qty_involved']));
							$usedBatches[]=$lineStatus['sherry_batch_id'];
                            
                            if($thisQuoteItem['product_type'] == 'newcatchall-hardware' && $thisQuoteItem['product_subclass'] == 3){
                                $itemScheduledLF = ($itemScheduledLF + floatval($lineStatus['qty_involved']));
                            /* PPSASCRUM-56: start */
                            }elseif($thisQuoteItem['product_type'] == 'newcatchall-drapery' || ($thisQuoteItem['calculator_used'] == 'pinch-pleated' || $thisQuoteItem['calculator_used'] == 'pinch-pleated-new' || $thisQuoteItem['calculator_used'] == 'new-pinch-pleated') ){
							/* PPSASCRUM-56: end */
                                
                                if($thisQuoteItem['product_type'] == 'newcatchall-drapery'){
                                    foreach($itemMetas as $itemMetaRow){
    									if($itemMetaRow['meta_key'] == 'labor-billable-widths'){
    								    	//$itemScheduledLF = floatval($itemMetaRow['meta_value']);
    									    $itemScheduledWidths = ($itemScheduledWidths + (floatval($itemMetaRow['meta_value']) * floatval($lineStatus['qty_involved']) ));
    								    }
    							    }
                                }else{
                                    foreach($itemMetas as $itemMetaRow){
    									if($itemMetaRow['meta_key'] == 'labor-widths'){
    								    	//$itemScheduledLF = floatval($itemMetaRow['meta_value']);
    									    $itemScheduledWidths = ($itemScheduledWidths + (floatval($itemMetaRow['meta_value']) * floatval($lineStatus['qty_involved']) ));
    								    }
    							    }
                                }
                                
                            }else{
							    foreach($itemMetas as $itemMetaRow){
									if($itemMetaRow['meta_key'] == 'labor-billable'){
								    	//$itemScheduledLF = floatval($itemMetaRow['meta_value']);
									    $itemScheduledLF = ($itemScheduledLF + (floatval($itemMetaRow['meta_value']) * floatval($lineStatus['qty_involved']) ));
								    }
							    }
                            }
							
						}

					}

					switch($thisQuoteItem['product_type']){
						case "window_treatments":
							
							//determine VAL, CORN, DRAPE
							/*
							if(count($itemMetas) == 0){
								$thisitemtype='wt';
							}else{
								foreach($itemMetas as $itemMeta){
									switch($itemMeta['meta_value']){
										case 'Pinch Pleated Drapery':
											$thisitemtype='drape';
										break;
										default:
											$thisitemtype='wt';
										break;
									}
								}
							}
							*/
							
							foreach($itemMetas as $itemMeta){
							    if($itemMeta['meta_key'] == 'wttype'){
							        if($itemMeta['meta_value'] == 'Straight Cornice' || $itemMeta['meta_value'] == 'Shaped Cornice'){
							            $thisitemtype='corn';
							        }elseif($itemMeta['meta_value'] == 'Box Pleated Valance'){
							            $thisitemtype='val';
							        }elseif($itemMeta['meta_value'] == 'Pinch Pleated Drapery'){
							            $thisitemtype='drape';
							        }else{
							            $thisitemtype='none';
							        }
							    }
							}
							
							
						break;
						case "newcatchall-swtmisc":
						    $thisitemtype='none';
						break;
						case "newcatchall-cubicle":
						    $thisitemtype='cc';
						break;
						case 'newcatchall-bedding':
						    $thisitemtype='bs';
						break;
						case 'newcatchall-valance':
						    $thisitemtype='val';
						break;
						case 'newcatchall-cornice':
						    $thisitemtype='corn';
						break;
						case 'newcatchall-drapery':
						    $thisitemtype='drape';
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
							    /* PPSASCRUM-56: start */
								case 'new-pinch-pleated':
								/* PPSASCRUM-56: end */
									$thisitemtype='drape';
								break;
								case "straight-cornice":
									$thisitemtype='corn';
								break;
							}

						break;
						case "cubicle_curtains":
							$thisitemtype='cc';
						break;
						case "bedspreads":
							$thisitemtype='bs';
						break;
						case "track_systems":
						    $thisitemtype='track';
						break;
						case 'newcatchall-hardware':
						    if($thisQuoteItem['product_subclass'] == 3){
							    $thisitemtype='track';
						    }elseif($thisQuoteItem['product_subclass'] == 1 || $thisQuoteItem['product_subclass'] == 2 || $thisQuoteItem['product_subclass'] == 4){
							    $thisitemtype='wthw';
							}elseif($thisQuoteItem['product_subclass'] == 5 || $thisQuoteItem['product_subclass'] == 6 || $thisQuoteItem['product_subclass'] == 7 || $thisQuoteItem['product_subclass'] == 8){
							    $thisitemtype='blinds';
							}else{
						        $thisitemtype='none';
						    }
						break;
						case "custom":
							//figure out if this is a BLINDS  or  HARDWARE item
							if($thisQuoteItem['calculator_used'] == 'WT Hardware'){
								$thisitemtype='wthw';
							}elseif($thisQuoteItem['calculator_used'] == 'Blinds & Shades'){
								$thisitemtype='blinds';
							}else{
								$thisitemtype='none';
							}
						break;
						default:
							if($thisQuoteItem['product_subclass'] == 1 || $thisQuoteItem['product_subclass'] == 2 || $thisQuoteItem['product_subclass'] == 4){
							    $thisitemtype='wthw';
							}elseif($thisQuoteItem['product_subclass'] == 5 || $thisQuoteItem['product_subclass'] == 6 || $thisQuoteItem['product_subclass'] == 7 || $thisQuoteItem['product_subclass'] == 8){
							    $thisitemtype='blinds';
							}else{
							    $thisitemtype='none';
							}
						break;
					}

					$outstandingOrderItems[$thisQuoteItem['id']]=array(
						'lineNumber' => $thisQuoteItem['line_number'],
						'itemType'=>$thisitemtype,
						'unscheduled'=>$totalQTY,
						'unscheduled_lf' => ($itemTotalLF - $itemScheduledLF),
						'unscheduled_widths' => ($itemTotalWidths - $itemScheduledWidths),
						'scheduled'=>(floatval($thisQuoteItem['qty']) - floatval($totalQTY)),
						'scheduled_lf' => $itemScheduledLF,
						'scheduled_widths' => $itemScheduledWidths,
						'totalLineQty' =>$thisQuoteItem['qty']
					);

				}

			}
			
			
			
			if(!is_null($order['type_id'])){
			    $thisOrderType=$this->QuoteTypes->get($order['type_id'])->toArray();
			    $orderTypeValue='<br>'.$thisOrderType['type_label'];
			}else{
			    $orderTypeValue='';
			}
					
			$grouped[$order['id']]=array(
					"order_number" => $order['order_number'],
					"company_name" => $order['company_name'],
					"po_number" => $order['po_number'].$orderTypeValue,
					"due" => $order['due'],
					"created" => $order['created'],
					"facility" => $order['facility'],
					"linesStatus"=>$outstandingOrderItems,
					"shipping_method_id" => $order['shipping_method_id']
				);

		}

		//echo "<pre>";print_r($grouped);echo "</pre>";exit;

		foreach($grouped as $orderid => $orderdata){
			$addclasses='';
			
			$projectname='';
			$totals='';
			$cc_qty_unscheduled=0;
			$cc_lf_unscheduled=0;
			$trk_lf_unscheduled=0;
			$bs_qty_unscheduled=0;
			$drape_qty_unscheduled=0;
			$drape_widths_unscheduled=0;
			
			$val_qty_unscheduled=0;
			$val_lf_unscheduled=0;
			
			$corn_qty_unscheduled=0;
			$corn_lf_unscheduled=0;
			
			$wthw_qty_unscheduled=0;
			$blinds_qty_unscheduled=0;
			
			foreach($orderdata['linesStatus'] as $lineItemID => $thisrowtotals){
				$lineNumber=$thisrowtotals['lineNumber'];
				switch($thisrowtotals['itemType']){
					case "cc":
						$cc_qty_unscheduled = ($cc_qty_unscheduled + $thisrowtotals['unscheduled']);
						$cc_lf_unscheduled = ($cc_lf_unscheduled + $thisrowtotals['unscheduled_lf'] );
					break;
					case "val":
					    $val_qty_unscheduled = ($val_qty_unscheduled + $thisrowtotals['unscheduled']);
					    $val_lf_unscheduled = ($val_lf_unscheduled + $thisrowtotals['unscheduled_lf']);
					break;
					case "corn":
					    $corn_qty_unscheduled = ($corn_qty_unscheduled + $thisrowtotals['unscheduled']);
					    $corn_lf_unscheduled = ($corn_lf_unscheduled + $thisrowtotals['unscheduled_lf']);
					break;
					case "drape":
						$drape_qty_unscheduled = ($drape_qty_unscheduled + $thisrowtotals['unscheduled']);
						$drape_widths_unscheduled = ($drape_widths_unscheduled + $thisrowtotals['unscheduled_widths']);
					break;
					case "bs":
						$bs_qty_unscheduled = ($bs_qty_unscheduled + $thisrowtotals['unscheduled']);
					break;
					case "wthw":
						$wthw_qty_unscheduled = ($wthw_qty_unscheduled + $thisrowtotals['unscheduled']);
					break;
					case "blinds":
						$blinds_qty_unscheduled = ($blinds_qty_unscheduled + $thisrowtotals['unscheduled']);
					break;
					case "track":
						$trk_lf_unscheduled = ($trk_lf_unscheduled + $thisrowtotals['unscheduled']);
					break;
				}

			}
			if(is_null($orderdata['due']) || $orderdata['due'] < 1000){
				$duedate='N/A';
			}else{
				$duedate=date('n/j/Y',$orderdata['due']);

				if($orderdata['due'] < time()){
					$addclasses .= " pastdue";
				}
			}


			if(intval($orderdata['shipping_method_id']) > 0){
				$thisShipMethod=$this->ShippingMethods->get($orderdata['shipping_method_id'])->toArray();
				$duedate .= "<br>".$thisShipMethod['name'];
			}
			
//$ignoreTallyFlag
/**PPSASCRUM-3 start **/


			$on_credit_hold =  ($customerData['on_credit_hold']) ? '<div><span style="color: red;"> On Credit Hold</span></div> ' : ' ';
			if($orderdata['due'] < time())
		    	$on_credit_hold =  ($customerData['on_credit_hold']) ? '<div><span style="color: white;"> On Credit Hold</span></div> ' : ' ';
		    else 
		    	$on_credit_hold =  ($customerData['on_credit_hold']) ? '<div><span style="color: red;"> On Credit Hold</span></div> ' : ' ';

            /**PPSASCRUM-3 end **/

			$orders[]=array(
				'DT_RowId'=>'row_'.$order['id'],
				'DT_RowClass'=>'rowtest needlineitems'.$addclasses,
				'0' => '<a href="/orders/vieworderschedule/'.$orderid.'"><img src="/img/view.png" title="Batch Breakdown" alt="Batch Breakdown" /></a> <a href="/orders/scheduleorder/'.$orderid.'/0"><img src="/img/new_batch_MFG.png" title="New Batch (MFG)" alt="New Batch (MFG)" width="16" /></a> <a href="/orders/scheduleorder/'.$orderid.'/1"><img src="/img/new_batch_ANY.png" title="New Batch (ANY)" alt="New Batch (ANY)" width="16" /></a>',
				'1' => "<a href=\"/orders/editlines/".$orderid."/\" target=\"_blank\">".$orderdata['order_number']."</a>",
				'2' => $orderdata['company_name'].$on_credit_hold,
				'3' => $orderdata['po_number'],
				'4' => date('n/j/Y',$orderdata['created']),
				'5' => $projectname,
				'6' => $orderdata['facility'],
				'7' => $duedate,
				'8' => ($cc_qty_unscheduled > 0 ? $cc_qty_unscheduled : ''),
				'9' => ($cc_lf_unscheduled > 0 ? $cc_lf_unscheduled : ''),
				'10' => ($trk_lf_unscheduled > 0 ? $trk_lf_unscheduled : ''),
				'11' => ($bs_qty_unscheduled > 0 ? $bs_qty_unscheduled : ''),
				'12' => ($drape_qty_unscheduled > 0 ? $drape_qty_unscheduled : ''),
				'13' => ($drape_widths_unscheduled > 0 ? $drape_widths_unscheduled : ''),
				'14' => ($val_qty_unscheduled > 0 ? $val_qty_unscheduled : ''),
				'15' => ($val_lf_unscheduled > 0 ? $val_lf_unscheduled : ''),
				'16' => ($corn_qty_unscheduled > 0 ? $corn_qty_unscheduled : ''),
				'17' => ($corn_lf_unscheduled > 0 ? $corn_lf_unscheduled : ''),
				'18' => ($wthw_qty_unscheduled > 0 ? $wthw_qty_unscheduled : ''),
				'19' => ($blinds_qty_unscheduled > 0 ? $blinds_qty_unscheduled : '')
			);

		}

		$this->set('draw',$draw);
		$this->set('recordsTotal',count($overallUnscheduledOrders));
		$this->set('recordsFiltered',count($overallUnscheduledOrders));
		$this->set('data',$orders);
		$this->set('_serialize',['draw','recordsTotal','recordsFiltered','data']);

	}

	

	

		public function editschedule($batchID,$mode='default'){
		
		
		
		$thisBatch=$this->SherryBatches->get($batchID)->toArray();
		$date=date("m/d/Y",strtotime($thisBatch['date'].' 08:00:00'));
		$ymdDate=date("Y-m-d",strtotime($thisBatch['date'].' 08:00:00'));

		$orderID=$thisBatch['work_order_id'];

		$this->set('thisBatch',$thisBatch);

		$this->set('date',$date);
		
		$thisOrder=$this->WorkOrders->get($orderID)->toArray();
		$this->set('orderData',$thisOrder);
		
		
		
		
		
		$correctedOLDDate=$ymdDate;
		$correctedNEWDate=date("Y-m-d",strtotime($this->request->data['dateselection'].' 08:00:00'));
		

		if($this->request->data){		
			
		
		    $logLabel='Batch # '.$batchID.' Schedule Changes Saved<ul>';
    		//compare old batch data to new (form data) and build a verbose log entry
    		
    		$checkBatchItems=$this->WorkOrderItemStatus->find('all',['conditions' => ['status' => 'Scheduled', 'sherry_batch_id' => $batchID]])->toArray();
    		
    		$usedLines=array();
    		foreach($checkBatchItems as $batchContents){
    		    if($batchContents['order_item_id'] > 0){
    		        $thisOrderItem=$this->WorkOrderItems->get($batchContents['order_item_id'])->toArray();
    		    
    		    
    		    $usedLines[]=$thisOrderItem['quote_line_item_id'];
    		    if(isset($this->request->data["qty_line_".$thisOrderItem['quote_line_item_id']])){
    		        
    		        if(intval($this->request->data["qty_line_".$thisOrderItem['quote_line_item_id']]) != intval($batchContents['qty_involved'])){
    		            $logLabel .= '<li>Changed QTY of Line '.$batchContents['order_line_number'].' from '.$batchContents['qty_involved'].' to '.$this->request->data["qty_line_".$thisOrderItem['quote_line_item_id']].'</li>';
    		        }
    		        
    		    }else{
    		        //seems to be deleted?
    		        $logLabel .= '<li>Removed Line '.$batchContents['order_line_number'].'</li>';
    		    }
    		    }
    		    
    		    
    		}
    		
    		
    		//loop through the POST data and see if a new line item id has a QTY that the schedule db doesnt have
    		foreach($this->request->data as $key => $val){
    		    if(substr($key,0,9) == "qty_line_"){
    				$lineID=str_replace("qty_line_","",$key);
    				if(!in_array($lineID,$usedLines) && intval($val) > 0){
    				   $thisLineItemData=$this->WorkOrderLineItems->get($lineID)->toArray();
    				  
    				    $logLabel .= "<li>Added ".$val." of Line ".$thisLineItemData['line_number'].'</li>';
    				}
    		    }
    		}
    		
    		
    		
    		if($logLabel != 'Batch # '.$batchID.' Schedule Changes Saved<ul>'){
    		    $logLabel .= "</ul> for Order ".$thisOrder['order_number'];
    		}else{
    		    $logLabel = '';
    		}
		    
		
			
			if($this->request->data['dateselection'] != $ymdDate){
				//change the date in the sherry batch model
				$batchTable=TableRegistry::get('SherryBatches');
				$thisBatch=$batchTable->get($batchID);
				
				$dateexp=explode("/",$this->request->data['dateselection']);
				$sqlDate=$dateexp[2].'-'.$dateexp[0].'-'.$dateexp[1];
				$thisBatch->date=$sqlDate;
				$batchTable->save($thisBatch);
				
				$this->logActivity($_SERVER['REQUEST_URI'],'Changed Scheduled Date on Batch #'.$batchID.' from '.date("m/d/Y",strtotime($thisBatch['date'].' 08:00:00')).' to '.date("m/d/Y",strtotime($this->request->data['dateselection'].' 08:00:00')).' for Order #'.$thisOrder['order_number']);
			}

			$sherryTable=TableRegistry::get('WorkOrderItemStatus');
			
			foreach($this->request->data as $key => $val){
				if(substr($key,0,9) == "qty_line_"){
					
					$thisQuoteLineID=str_replace("qty_line_","",$key);
					$lookupOrderItem=$this->WorkOrderItems->find('all',['conditions'=>['quote_line_item_id' => $thisQuoteLineID]])->toArray();
					foreach($lookupOrderItem as $orderItemRow){
						$thisOrderItemID=$orderItemRow['id'];
					}

					$thisQuoteItem=$this->WorkOrderLineItems->get($thisQuoteLineID)->toArray();
					
					$thisdatesplit=explode("-",$ymdDate);
					$fixedDate=$thisdatesplit[1].'/'.$thisdatesplit[2].'/'.$thisdatesplit[0];
					$thisdatets=mktime(18,0,0,intval($thisdatesplit[1]),intval($thisdatesplit[2]),intval($thisdatesplit[0]));
					
					$thisDateBeginTS=mktime(0,0,0,intval($thisdatesplit[1]),intval($thisdatesplit[2]),intval($thisdatesplit[0]));
					$thisDateEndTS=mktime(23,59,59,intval($thisdatesplit[1]),intval($thisdatesplit[2]),intval($thisdatesplit[0]));
					
					//look for existing schedule entries for this order, this line, status "scheduled"
					$scheduleCheck=$this->WorkOrderItemStatus->find('all',['conditions' => ['sherry_batch_id' => $batchID, 'status' => 'Scheduled', 'order_item_id' => $thisOrderItemID, 'order_line_number' => $thisQuoteItem['line_number']]])->toArray();
					if(count($scheduleCheck) == 1){
						if(intval($val) == 0){
							//delete existing
							foreach($scheduleCheck as $scheduleRow){
								$thisScheduleEntry=$sherryTable->get($scheduleRow['id']);
								$sherryTable->delete($thisScheduleEntry);
							}
							
						}else{
							//update it
							foreach($scheduleCheck as $scheduleRow){
								$thisScheduleEntry=$sherryTable->get($scheduleRow['id']);
								$thisScheduleEntry->qty_involved = $val;
								$thisScheduleEntry->time=strtotime($this->request->data['dateselection'].' 18:00:00');
								$sherryTable->save($thisScheduleEntry);
							}
						}
						
					}elseif(count($scheduleCheck) == 0){
						
						if(intval($val) >0){
							//insert it
							$thisScheduleEntry=$sherryTable->newEntity();
							$thisScheduleEntry->order_line_number=$thisQuoteItem['line_number'];
							$thisScheduleEntry->order_item_id = $thisOrderItemID;
							$thisScheduleEntry->sherry_batch_id = $batchID;
							$thisScheduleEntry->time = strtotime($this->request->data['dateselection'].' 18:00:00');
							$thisScheduleEntry->status='Scheduled';
							$thisScheduleEntry->user_id=$this->Auth->user('id');
							$thisScheduleEntry->qty_involved = $val;
							$thisScheduleEntry->work_order_id = $orderID;
							$thisScheduleEntry->shipment_id=0;
							$thisScheduleEntry->parent_status_id=0;
							$sherryTable->save($thisScheduleEntry);
							
						}
					}
				}
			}
			
			//rebuild the sherry cache for this date
			/* PPSASCRUM-248: start [updating function with arguments as per complete set of function parameters] */
			$this->auditOrderItemStatuses($orderID, false, 'workorder');
			/* PPSASCRUM-248: end */
			
			$correctedOLDDate=$date;
			$this->updatesherrycachefordate($correctedOLDDate);
			
			$correctedNEWDate=date('Y-m-d',strtotime($this->request->data['dateselection'].' 08:00:00'));
			
			if($correctedOLDDate != $correctedNEWDate){
				$this->updatesherrycachefordate($correctedNEWDate);
			}
			
			//$this->logActivity($_SERVER['REQUEST_URI'],'Edited Batch #'.$batchID);
			if($logLabel != ''){
			    $this->logActivity($_SERVER['REQUEST_URI'],$logLabel);
			}
			
			return $this->redirect('/orders/schedule/');
			
		}else{
			$this->autoRender=false;
		
			
			//load all the data for the form
			$thisCustomer=$this->Customers->get($thisOrder['customer_id'])->toArray();

			$this->set('customerData',$thisCustomer);
			//find all the line items in this order

			$lineItems=$this->WorkOrderLineItems->find('all',['conditions'=>['quote_id'=>$thisOrder['quote_id']],'order'=>['sortorder'=>'asc']])->toArray();

			$this->set('lineItems',$lineItems);

			$lineItemsArray=array();
			foreach($lineItems as $lineitem){

				$lineItemsArray[$lineitem['id']]=array();
				$lineItemsArray[$lineitem['id']]['data']=$lineitem;
				$lineItemsArray[$lineitem['id']]['metadata']=array();
				$lineItemsArray[$lineitem['id']]['fabricdata']=array();
				$lineItemMetas=$this->WorkOrderLineItemMeta->find('all',['conditions'=>['worder_item_id'=>$lineitem['id']]])->toArray();
				foreach($lineItemMetas as $lineitemmeta){
					$lineItemsArray[$lineitem['id']]['metadata'][$lineitemmeta['meta_key']]=$lineitemmeta['meta_value'];	
					if($lineitemmeta['meta_key']=='fabricid' && intval($lineitemmeta['meta_value']) > 0){
						$thisFabric=$this->Fabrics->get($lineitemmeta['meta_value'])->toArray();
						$lineItemsArray[$lineitem['id']]['fabricdata']=$thisFabric;
					}
				}
				
				//find the matching order item id# for this quote item id#
				$orderItemID=0;

				$lookupOrderItem=$this->WorkOrderItems->find('all',['conditions'=>['quote_line_item_id'=>$lineitem['id']]])->toArray();

				foreach($lookupOrderItem as $orderitemrow){
					$orderItemID=$orderitemrow['id'];
				}
				$lineItemsArray[$lineitem['id']]['order_item_id']=$orderItemID;

				$thisBatchCount=0;
				$otherBatchesCount=0;
				
				//find all previously shipped items in this line
				$scheduledItemsLookup=$this->WorkOrderItemStatus->find('all',['conditions'=>['order_line_number'=>$lineitem['line_number'],'order_item_id'=>$orderItemID,'work_order_id'=>$orderID,'status' => 'Scheduled']]);
				foreach($scheduledItemsLookup as $scheduledItemRow){
					
					if($scheduledItemRow['sherry_batch_id'] != $batchID){
						$otherBatchesCount=($otherBatchesCount + floatval($scheduledItemRow['qty_involved']));
					}else{
						$thisBatchCount=($thisBatchCount + floatval($scheduledItemRow['qty_involved']));
					}

				}
				$lineItemsArray[$lineitem['id']]['this_batch']=$thisBatchCount;
				$lineItemsArray[$lineitem['id']]['other_batches'] = $otherBatchesCount;
				$lineItemsArray[$lineitem['id']]['remaining_unscheduled'] = ($lineitem['qty'] - $thisBatchCount - $otherBatchesCount);
			}
			$this->set('productTypeCounts',array('cc'=>$numCC,'bs'=>$numBS,'wt'=>$numWT));

			$this->set('lineItems',$lineItemsArray);

			$this->set('allSettings',$this->getsettingsarray());



            //find all BOXES for this batch and load its data for the template new rule ticket 279782
			$batchBoxes=array();
			$boxesThisBatch=$this->SherryBatchBoxes->find('all',['conditions' => ['batch_id' => $batchID]])->toArray();
			
			foreach($boxesThisBatch as $box){
			    //find box contents
			    $batchBoxes[$box['id']]=array(
			        'contents'=>array(),
			        'box_number' => $box['box_number'],
			        'order_id' => $box['order_id'],
			        'status' => $box['status'],
			        'warehouse_location_id' => $box['warehouse_location_id']
			    );
			    
			    $thisBoxContents=$this->SherryBatchBoxContents->find('all',['conditions' => ['box_id' => $box['id']], 'order' => ['line_number' => 'asc']])->toArray();
			    foreach($thisBoxContents as $content){
			        $batchBoxes[$box['id']]['contents'][$content['id']]=array(
			            'quote_item_id' => $content['quote_item_id'],
			            'line_number' => $content['line_number'],
			            'qty' => $content['qty']
			        );
			    }
			    
			}
			
			$this->set('batchBoxes',$batchBoxes);
            $this->set('mode','edit');


			$this->render('editscheduleform');
			
			
			
			
		}

	}
	
	
	
	
	public function viewbatchschedule($batchID){
		
		$thisBatch=$this->SherryBatches->get($batchID)->toArray();
		$date=date("m/d/Y",strtotime($thisBatch['date'].' 08:00:00'));
		$ymdDate=date("Y-m-d",strtotime($thisBatch['date'].' 08:00:00'));

		$orderID=$thisBatch['work_order_id'];

		$this->set('thisBatch',$thisBatch);

		$this->set('date',$date);
		$thisOrder=$this->WorkOrders->get($orderID)->toArray();
		//$thisOrder=$this->WorkOrders->find('all',['conditions'=>['id'=>$orderID]])->toArray();
		$this->set('orderData',$thisOrder);
		
		$correctedOLDDate=$ymdDate;
		$correctedNEWDate=date("Y-m-d",strtotime($this->request->data['dateselection'].' 08:00:00'));
		

	
		
			
		//load all the data for the form
		//$thisCustomer=$this->Customers->get($thisOrder['customer_id'])->toArray();
		$thisCustomer=$this->Customers->find('all',['conditions'=>['id'=>$thisOrder[0]['customer_id']]])->toArray();
		$this->set('customerData',$thisCustomer);
		//find all the line items in this order

		$lineItems=$this->WorkOrderLineItems->find('all',['conditions'=>['order_id'=>$orderID],'order'=>['sortorder'=>'asc']])->toArray();
			$this->set('lineItems',$lineItems[0]);
		$lineItemsArray=array();
		foreach($lineItems as $lineitem){
			$lineItemsArray[$lineitem['id']]=array();
			$lineItemsArray[$lineitem['id']]['data']=$lineitem;
			$lineItemsArray[$lineitem['id']]['metadata']=array();
			$lineItemsArray[$lineitem['id']]['fabricdata']=array();
			$lineItemMetas=$this->WorkOrderLineItemMeta->find('all',['conditions'=>['worder_item_id'=>$lineitem['id']]])->toArray();

			foreach($lineItemMetas as $lineitemmeta){
				$lineItemsArray[$lineitem['id']]['metadata'][$lineitemmeta['meta_key']]=$lineitemmeta['meta_value'];	
				if($lineitemmeta['meta_key']=='fabricid' && intval($lineitemmeta['meta_value']) > 0){
					$thisFabric=$this->Fabrics->get($lineitemmeta['meta_value'])->toArray();
					$lineItemsArray[$lineitem['id']]['fabricdata']=$thisFabric;
				}
			}
		//	die(print_r($lineItemsArray));
	
			//find the matching order item id# for this quote item id#
			$orderItemID=0;
					$conditions=array();
					$conditions += array('quote_line_item_id' => $lineitem['id']);

			$lookupOrderItem=$this->WorkOrderItems->find('all',['conditions'=>$conditions])->toArray();
			foreach($lookupOrderItem as $orderitemrow){
				$orderItemID=$orderitemrow['id'];
			}
			$lineItemsArray[$lineitem['id']]['order_item_id']=$orderItemID;
			//echo $lineitem['line_number']."==>".$orderItemID;
			$thisBatchCount=0;
			$otherBatchesCount=0;
			//find all previously shipped items in this line
			$scheduledItemsLookup=$this->WorkOrderItemStatus->find('all',['conditions'=>['order_line_number'=>$lineitem['line_number'],'order_item_id'=>$orderItemID,'work_order_id'=>$orderID,'status' => 'Scheduled']]);
			
			foreach($scheduledItemsLookup as $scheduledItemRow){
				if($scheduledItemRow['sherry_batch_id'] != $batchID){
					$otherBatchesCount=($otherBatchesCount + floatval($scheduledItemRow['qty_involved']));
				}else{
					$thisBatchCount=($thisBatchCount + floatval($scheduledItemRow['qty_involved']));
				}

			}
			$lineItemsArray[$lineitem['id']]['this_batch']=$thisBatchCount;
			$lineItemsArray[$lineitem['id']]['other_batches'] = $otherBatchesCount;
			$lineItemsArray[$lineitem['id']]['remaining_unscheduled'] = ($lineitem['qty'] - $thisBatchCount - $otherBatchesCount);
		}
		$this->set('productTypeCounts',array('cc'=>$numCC,'bs'=>$numBS,'wt'=>$numWT));

		$this->set('lineItems',$lineItemsArray);

		$this->set('allSettings',$this->getsettingsarray());


	}

	

	
	public function markscheduleditemcompleted($batchID,$frombreakdown=0){
		$this->viewBuilder()->layout('iframeinner');
		$thisBatch=$this->SherryBatches->get($batchID)->toArray();
		$orderID=$thisBatch['work_order_id'];

		if($this->request->data){
			
			$thisDateTS=strtotime($this->request->data['date'].' 12:00:00');
            
            $thisWorkOrder=$this->WorkOrders->get($orderID)->toArray();
            
			$thisOrderDayStatuses=$this->WorkOrderItemStatus->find('all',['conditions'=>['sherry_batch_id' => $batchID, 'status' => 'Scheduled'],'order'=>['order_line_number'=>'asc']])->toArray();
			
			$statusTable=TableRegistry::get('WorkOrderItemStatus');
			
			foreach($thisOrderDayStatuses as $itemStatusRow){
				
				$newStatus=$statusTable->newEntity();
				$newStatus->order_line_number = $itemStatusRow['order_line_number'];
				$newStatus->order_item_id = $itemStatusRow['order_item_id'];
				$newStatus->time=$thisDateTS;
				$newStatus->status='Completed';
				$newStatus->qty_involved=$itemStatusRow['qty_involved'];
				$newStatus->sherry_batch_id = $batchID;
				$newStatus->work_order_id=$orderID;
				$newStatus->parent_status_id=$itemStatusRow['id'];
				$newStatus->user_id=$this->Auth->user('id');
				$statusTable->save($newStatus);
			
			}
						
			

			//rebuild the sherry cache for this date
			/* PPSASCRUM-248: start [updating function with arguments as per complete set of function parameters] */
			$this->auditOrderItemStatuses($orderID, false, 'workorder');
			/* PPSASCRUM-248: end */
			
			$this->updatesherrycachefordate(date('Y-m-d',$thisDateTS));
			
			$sherryCacheTable=TableRegistry::get('SherryCache');
			$findCacheEntry=$this->SherryCache->find('all',['conditions'=>['batch_id' => $batchID]])->toArray();
			//$this->logActivity($_SERVER['REQUEST_URI'],$batchID. '$batchIDoutside SheeryCache '.print_r($thisCacheEntry)); 
			foreach($findCacheEntry as $cacheEntry){
				$thisCacheEntry=$sherryCacheTable->get($cacheEntry['id']);
				$thisCacheEntry->batch_completed_date=$thisDateTS;
				$sherryCacheTable->save($thisCacheEntry);
			}
			

			$sherryBatchesTable=TableRegistry::get('SherryBatches');
			$thisBatchEdit=$sherryBatchesTable->get($batchID);
			$thisBatchEdit->completed_date = $thisDateTS;
			$sherryBatchesTable->save($thisBatchEdit);

            //log it
            $this->logActivity($_SERVER['REQUEST_URI'],'Marked batch '.$batchID.' as Completed (WO#'.$thisWorkOrder['order_number'].', Date: '.$this->request->data['date'].')');

			if($frombreakdown == 1){
					echo "<html><head><script>parent.window.location.reload(true);</script></head><body></body></html>";exit;
			}else{
				echo "<script>parent.reloadSchedule(); parent.$.fancybox.close();</script>";exit;
			}
			
			
		}else{

			$thisOrderDayStatuses=$this->WorkOrderItemStatus->find('all',['conditions'=>['sherry_batch_id'=>$batchID, 'status' => 'Scheduled'],'order'=>['order_line_number'=>'asc']])->toArray();

			$this->set('ThisDayThisOrderStatuses',$thisOrderDayStatuses);			
			
			$boxedItems=array();
			$batchBoxes=$this->SherryBatchBoxes->find('all',['conditions' => ['batch_id' => $batchID]])->toArray();
			foreach($batchBoxes as $box){
			    
			    $boxedItems[$box['id']]=array();
			    
			    //box items
			    $thisBoxItems=$this->SherryBatchBoxContents->find('all',['conditions' => ['box_id' => $box['id']]])->toArray();
			    foreach($thisBoxItems as $item){
			        $boxedItems[$box['id']][]=$item;
			    }
			    
			}
			
			$this->set('boxedItems',$boxedItems);
			
			$thisWorkOrder=$this->WorkOrders->get($orderID)->toArray();
			$this->set('thisWorkOrder',$thisWorkOrder);


			$thisOrderItems=$this->WorkOrderItems->find('all',['conditions'=>['order_id'=>$orderID]])->toArray();
			$this->set('thisOrderItems',$thisOrderItems);
			

			$thisQuoteItems=$this->WorkOrderLineItems->find('all',['conditions'=>['quote_id'=>$thisWorkOrder['quote_id']]])->toArray(); 
			$this->set('thisQuoteItems',$thisQuoteItems);			

			$itemMeta=array();

			foreach($thisQuoteItems as $quoteItem){
				$itemMeta[$quoteItem['id']]=array();
				$lineItemMetas=$this->WorkOrderLineItemMeta->find('all',['conditions'=>['worder_item_id'=>$quoteItem['id']]])->toArray();
				foreach($lineItemMetas as $meta){
					$itemMeta[$quoteItem['id']][$meta['meta_key']] = $meta['meta_value'];
				}
			}

			$this->set('itemMeta',$itemMeta);


			$thisBatchNotes=$this->SherryBatchNotes->find('all',['conditions'=>['batch_id' => $batchID],'order'=>['id'=>'asc']])->toArray();
			$this->set('batchNotes',$thisBatchNotes);



			$allUsers=array();
			$userLookup=$this->Users->find('all')->toArray();
			foreach($userLookup as $userRow){
				$allUsers[$userRow['id']]=$userRow;
			}
			$this->set('allUsers',$allUsers);

		}

	}

	

	public function markscheduleditemshipped($batchID,$frombreakdown=0){
		$this->viewBuilder()->layout('iframeinner');

		$thisBatch=$this->SherryBatches->get($batchID)->toArray();
		$orderID=$thisBatch['work_order_id'];

		if($this->request->data){
			
			$thisWorkOrder=$this->WorkOrders->get($orderID)->toArray();
			
			$thisDateTS=strtotime($this->request->data['date_shipped'].' 12:00:00');

			$thisOrderDayStatuses=$this->WorkOrderItemStatus->find('all',['conditions'=>['sherry_batch_id' => $batchID, 'status' => 'Scheduled'],'order'=>['order_line_number'=>'asc']])->toArray();
			
			
			$shipmentTable=TableRegistry::get('Shipments');
			$newShipment=$shipmentTable->newEntity();
			$newShipment->work_order_id=$orderID;
			$newShipment->tracking_number=$this->request->data['tracking_number'];
			$newShipment->carrier=$this->request->data['carrier'];
			$newShipment->delivery_address=$this->request->data['delivery_address'];
			$newShipment->estimated_delivery=mktime(12,0,0,$this->request->data['date']['month'],$this->request->data['date']['day'],$this->request->data['date']['year']);
			
			if($shipmentTable->save($newShipment)){
			
			
				$statusTable=TableRegistry::get('WorkOrderItemStatus');
				foreach($thisOrderDayStatuses as $itemStatusRow){

					$newStatus=$statusTable->newEntity();
					$newStatus->order_line_number = $itemStatusRow['order_line_number'];
					$newStatus->order_item_id = $itemStatusRow['order_item_id'];
					$newStatus->time=$thisDateTS;
					$newStatus->status='Shipped';
					$newStatus->qty_involved=$itemStatusRow['qty_involved'];
					$newStatus->work_order_id=$orderID;
					$newStatus->parent_status_id=$itemStatusRow['id'];
					$newStatus->sherry_batch_id = $batchID;
					$newStatus->user_id=$this->Auth->user('id');
					$newStatus->shipment_id=$newShipment->id;
					$statusTable->save($newStatus);

				}
				
				//rebuild the sherry cache for this date
				/* PPSASCRUM-248: start [updating function with arguments as per complete set of function parameters] */
				$this->auditOrderItemStatuses($orderID, false, 'workorder');
				/* PPSASCRUM-248: end */
			
				$this->updatesherrycachefordate(date('Y-m-d',$thisDateTS));
				
				
				

				$sherryCacheTable=TableRegistry::get('SherryCache');
				$findCacheEntry=$this->SherryCache->find('all',['conditions'=>['batch_id'=>$batchID ]])->toArray();
				foreach($findCacheEntry as $cacheEntry){
					$thisCacheEntry=$sherryCacheTable->get($cacheEntry['id']);
					if($cacheEntry['batch_completed_date'] == 0){
						$thisCacheEntry->batch_completed_date=$thisDateTS;
					}
					$thisCacheEntry->batch_shipped_date=$thisDateTS;
					$sherryCacheTable->save($thisCacheEntry);
				}
				
				$sherryBatchesTable=TableRegistry::get('SherryBatches');
				$thisBatchEdit=$sherryBatchesTable->get($batchID);
				$thisBatchEdit->shipped_date = $thisDateTS;
				if($thisBatchEdit->completed_date == 0){
					$thisBatchEdit->completed_date=$thisDateTS;
				}
				$sherryBatchesTable->save($thisBatchEdit);
				
				$this->logActivity($_SERVER['REQUEST_URI'],'Marked batch '.$batchID.' as Shipped (WO#'.$thisWorkOrder['order_number'].', Date:'.$this->request->data['date_shipped'].')');

				if($frombreakdown == 1){
					echo "<html><head><script>parent.window.location.reload(true);</script></head><body></body></html>";exit;
				}else{
					echo "<script>parent.reloadSchedule(); parent.$.fancybox.close();</script>";exit;
				}
			
			}
			
		}else{

			$thisOrderDayStatuses=$this->WorkOrderItemStatus->find('all',['conditions'=>['sherry_batch_id' => $batchID, 'status' => 'Scheduled'],'order'=>['order_line_number'=>'asc']])->toArray();

			

			$this->set('ThisDayThisOrderStatuses',$thisOrderDayStatuses);

			

			$thisWorkOrder=$this->WorkOrders->get($orderID)->toArray();

			$this->set('thisWorkOrder',$thisWorkOrder);

			

			$thisOrderItems=$this->WorkOrderItems->find('all',['conditions'=>['order_id'=>$orderID]])->toArray();

			$this->set('thisOrderItems',$thisOrderItems);


            $thisCustomer=$this->Customers->get($thisWorkOrder['customer_id'])->toArray();
            $this->set('thisCustomer',$thisCustomer);
            
			
            /*
			$thisQuoteItems=$this->QuoteLineItems->find('all',['conditions'=>['quote_id'=>$thisWorkOrder['quote_id']]])->toArray(); 

			$this->set('thisQuoteItems',$thisQuoteItems);

			

			$itemMeta=array();
            
            foreach($thisQuoteItems as $quoteItem){

				$itemMeta[$quoteItem['id']]=array();

				$lineItemMetas=$this->QuoteLineItemMeta->find('all',['conditions'=>['quote_item_id'=>$quoteItem['id']]])->toArray();

				foreach($lineItemMetas as $meta){

					$itemMeta[$quoteItem['id']][$meta['meta_key']] = $meta['meta_value'];

				}

			}

			

			$this->set('itemMeta',$itemMeta);
            */

			$shippingMethod=$this->ShippingMethods->get($thisWorkOrder['shipping_method_id'])->toArray();
			$this->set('thisShippingMethod',$shippingMethod['name']);

			
			$thisBatchNotes=$this->SherryBatchNotes->find('all',['conditions'=>['batch_id' => $batchID],'order'=>['id'=>'asc']])->toArray();
			$this->set('batchNotes',$thisBatchNotes);
            
            /*
			$allUsers=array();
			$userLookup=$this->Users->find('all')->toArray();
			foreach($userLookup as $userRow){
				$allUsers[$userRow['id']]=$userRow;
			}
			$this->set('allUsers',$allUsers);

            
            //load box details for this batch
            $allBoxesThisBatch=$this->SherryBatchBoxes->find('all',['conditions' => ['batch_id' => $batchID], 'order' => ['created' => 'asc']])->toArray();
            $allBoxes=array();
            foreach($allBoxesThisBatch as $box){
                $allBoxes[$box['id']]=array();
                $boxContents=$this->SherryBatchBoxContents->find('all',['conditions' => ['box_id' => $box['id']],'order'=>['created'=>'asc']])->toArray();
                $allBoxes[$box['id']]['boxdata']=$box;
                $allBoxes[$box['id']]['boxcontents']=$boxContents;
            }
            $this->set('allBoxes',$allBoxes);
            */
            
            
            
            
            
            
            
            
            
            
            
            $boxes=$this->SherryBatchBoxes->find('all',['conditions' => ['batch_id' => $batchID],'order'=>['created'=>'asc']])->toArray();
    	    $batchBoxes=array();
    	    foreach($boxes as $box){
    	        $boxContentCheck=$this->SherryBatchBoxContents->find('all',['conditions' => ['box_id' => $box['id']]])->toArray();
    	        $thisItemCount=0;
    	        $thisItemData=array();
    	        foreach($boxContentCheck as $contentEntry){
    	            $thisItemCount=($thisItemCount + intval($contentEntry['qty']));
    	            $thisItemData[]=$contentEntry;
    	        }
    	        
    	       $batchBoxes[]=array(
    	        'id'=>$box['id'],
    	        'box_number' => $box['box_number'],
    	        'batch_id' => $box['batch_id'],
    	        'order_id' => $box['order_id'],
    	        'warehouse_location_id' => $box['warehouse_location_id'],
    	        'length' => $box['length'],
    	        'width' => $box['width'],
    	        'height' => $box['height'],
    	        'weight' => $box['weight'],
    	        'wo_facility' => $box['wo_facility'],
    	        'status' => $box['status'],
    	        'shipment_id' => $box['shipment_id'],
    	        'created' => $box['created'],
    	        'updated' => $box['updated'],
    	        'user_id' => $box['user_id'],
    	        'item_count'=>$thisItemCount,
    	        'item_data' => $thisItemData
    	       );
    	    }
    	    
    	    $this->set('batchBoxes',$batchBoxes);
    	    
    	    
    	    
    	    $lineItems=$this->WorkOrderLineItems->find('all',['conditions'=>['quote_id'=>$thisWorkOrder['quote_id']],'order'=>['sortorder'=>'asc']])->toArray();
            
            $this->set('lineItems',$lineItems);
    	    
    	    
    	    $otherBoxesThisBatch=$this->SherryBatchBoxes->find('all',['conditions' => ['batch_id' => $batchID]])->toArray();
            $this->set('this_batch_existing_box_count',count($otherBoxesThisBatch));
    	    
    	    $lineItemsArray=array();
    		foreach($lineItems as $lineitem){
    
    			$lineItemsArray[$lineitem['id']]=array();
    			$lineItemsArray[$lineitem['id']]['data']=$lineitem;
    			$lineItemsArray[$lineitem['id']]['metadata']=array();
    			$lineItemsArray[$lineitem['id']]['fabricdata']=array();
    			$lineItemMetas=$this->WorkOrderLineItemMeta->find('all',['conditions'=>['worder_item_id'=>$lineitem['id']]])->toArray();
    			foreach($lineItemMetas as $lineitemmeta){
    				$lineItemsArray[$lineitem['id']]['metadata'][$lineitemmeta['meta_key']]=$lineitemmeta['meta_value'];	
    				if($lineitemmeta['meta_key']=='fabricid' && intval($lineitemmeta['meta_value']) > 0){
    					$thisFabric=$this->Fabrics->get($lineitemmeta['meta_value'])->toArray();
    					$lineItemsArray[$lineitem['id']]['fabricdata']=$thisFabric;
    				}
    			}
    			
    			//find the matching order item id# for this quote item id#
    			$orderItemID=0;
    
    			$lookupOrderItem=$this->WorkOrderItems->find('all',['conditions'=>['quote_line_item_id'=>$lineitem['id']]])->toArray();
    
    			foreach($lookupOrderItem as $orderitemrow){
    				$orderItemID=$orderitemrow['id'];
    			}
    			$lineItemsArray[$lineitem['id']]['order_item_id']=$orderItemID;
    
    			$thisBatchCount=0;
    			$otherBatchesCount=0;
    			
    			//find all previously shipped items in this line
    			$scheduledItemsLookup=$this->WorkOrderItemStatus->find('all',['conditions'=>['order_line_number'=>$lineitem['line_number'],'order_item_id'=>$orderItemID,'work_order_id'=>$thisWorkOrder['id'],'status' => 'Scheduled']]);
    			foreach($scheduledItemsLookup as $scheduledItemRow){
    				
    				if($scheduledItemRow['sherry_batch_id'] != $batchID){
    					$otherBatchesCount=($otherBatchesCount + floatval($scheduledItemRow['qty_involved']));
    				}else{
    					$thisBatchCount=($thisBatchCount + floatval($scheduledItemRow['qty_involved']));
    				}
    
    			}
    			$lineItemsArray[$lineitem['id']]['this_batch']=$thisBatchCount;
    			$lineItemsArray[$lineitem['id']]['other_batches'] = $otherBatchesCount;
    			$lineItemsArray[$lineitem['id']]['remaining_unscheduled'] = ($lineitem['qty'] - $thisBatchCount - $otherBatchesCount);
    			
    			$thisBatchOtherBoxed=0;
    			
    			foreach($otherBoxesThisBatch as $box){
    			    $boxContentCheck=$this->SherryBatchBoxContents->find('all',['conditions' => ['box_id' => $box['id'], 'line_number' => $lineitem['line_number']]])->toArray();
    			    foreach($boxContentCheck as $boxContentRow){
    			        $thisBatchOtherBoxed=($thisBatchOtherBoxed + $boxContentRow['qty']);
    			    }
    			}
    			
    			$lineItemsArray[$lineitem['id']]['this_batch_other_boxes'] = $thisBatchOtherBoxed;
    		}
    		
    		
            
    		$this->set('lineItems',$lineItemsArray);
    	    
    	    
    	    
    	    $allusers=$this->Users->find('all',['order'=>['last_name'=>'asc','first_name'=>'asc']])->toArray();
    		$this->set('allusers',$allusers);
            
            
            $this->set('batchID',$batchID);
            
            
            $warehouseLocations=$this->WarehouseLocations->find('all')->toArray();
            $this->set('warehouseLocations',$warehouseLocations);

		}
	}
	
	
	
	public function markschedulediteminvoiced($batchID,$frombreakdown=0){
	    $this->viewBuilder()->layout('iframeinner');
		if($this->request->data){
    		$this->autoRender=false;
            
    		$thisBatch=$this->SherryBatches->get($batchID)->toArray();
    		$orderID=$thisBatch['work_order_id'];
    		$thisOrderDayStatuses=$this->WorkOrderItemStatus->find('all',['conditions'=>['sherry_batch_id' => $batchID, 'status' => 'Scheduled'],'order'=>['order_line_number'=>'asc']])->toArray();
    		
    		$thisWorkOrder=$this->WorkOrders->get($orderID)->toArray();
    		
    		$statusTable=TableRegistry::get('WorkOrderItemStatus');
    		foreach($thisOrderDayStatuses as $itemStatusRow){
    			$newStatus=$statusTable->newEntity();
    			$newStatus->order_line_number = $itemStatusRow['order_line_number'];
    			$newStatus->order_item_id = $itemStatusRow['order_item_id'];
    			$newStatus->time=strtotime($this->request->data['date_invoiced'].' 12:00:00');
    			$newStatus->status='Invoiced';
    			$newStatus->qty_involved=$itemStatusRow['qty_involved'];
    			$newStatus->work_order_id=$orderID;
    			$newStatus->parent_status_id=$itemStatusRow['id'];
    			$newStatus->sherry_batch_id = $batchID;
    			$newStatus->user_id=$this->Auth->user('id');
    			$newStatus->invoice_number=$this->request->data['invoice_number'];
    			$statusTable->save($newStatus);
    		}
    				
    		//rebuild the sherry cache for this date
    		/* PPSASCRUM-248: start [updating function with arguments as per complete set of function parameters] */
			$this->auditOrderItemStatuses($orderID, false, 'workorder');
			/* PPSASCRUM-248: end */
    			
    		$this->updatesherrycachefordate(date('Y-m-d',strtotime($this->request->data['date_invoiced'].' 12:00:00')));
    				
        	$sherryCacheTable=TableRegistry::get('SherryCache');
    		$findCacheEntry=$this->SherryCache->find('all',['conditions'=>['batch_id'=>$batchID ]])->toArray();
    		foreach($findCacheEntry as $cacheEntry){
    			$thisCacheEntry=$sherryCacheTable->get($cacheEntry['id']);
    			$thisCacheEntry->batch_invoiced_date=strtotime($this->request->data['date_invoiced'].' 12:00:00');
    			$sherryCacheTable->save($thisCacheEntry);
    		}
    				
    		$sherryBatchesTable=TableRegistry::get('SherryBatches');
    		$thisBatchEdit=$sherryBatchesTable->get($batchID);
    		$thisBatchEdit->invoiced_date = strtotime($this->request->data['date_invoiced'].' 12:00:00');
    		$sherryBatchesTable->save($thisBatchEdit);
    		
    		$this->logActivity($_SERVER['REQUEST_URI'],'Marked batch '.$batchID.' as Invoiced for WO# '.$thisWorkOrder['order_number'].', Date: '.$this->request->data['date_invoiced'].', Invoice #'.$this->request->data['invoice_number']);

    		echo "<script>parent.reloadSchedule(); parent.$.fancybox.close();</script>";exit;
		}else{
		    
		}
			
	}
	
	
	
	public function markscheduleditemuninvoiced($batchID,$frombreakdown=0){
		
		$this->autoRender=false;

        //update SHERRY CACHE row to zero out the invoiced timestamp
        $sherryCacheTable=TableRegistry::get('SherryCache');
        $lookupCacheRow=$this->SherryCache->find('all',['conditions' => ['batch_id' => $batchID]])->toArray();
        foreach($lookupCacheRow as $sherryCacheRow){
            
            $thisCacheRow=$sherryCacheTable->get($sherryCacheRow['id']);
            $thisCacheRow->batch_invoiced_date=0;
            $sherryCacheTable->save($thisCacheRow);
            
        }
        
        //update the Sherry Batch row to zero out the invoiced timestamp
        $sherryBatchTable=TableRegistry::get('SherryBatches');
        $thisBatch=$sherryBatchTable->get($batchID);
        $thisBatch->invoiced_date=0;
        $sherryBatchTable->save($thisBatch);
        
        $thisWorkOrder=$this->Orders->get($thisBatch->work_order_id);
        
        $orderItemStatusTable=TableRegistry::get('WorkOrderItemStatus');
        //delete the Order Item Status rows for this batch that contain status INVOICED and this timestamp
        $lookupItemStatusMarkedInvoicedThisBatch=$this->WorkOrderItemStatus->find('all',['conditions'=>['sherry_batch_id'=>$batchID,'status'=>'Invoiced']])->toArray();
        foreach($lookupItemStatusMarkedInvoicedThisBatch as $statusRow){
            $thisItemStatusRow=$orderItemStatusTable->get($statusRow['id']);
            $orderItemStatusTable->delete($thisItemStatusRow);
        }
        
        $this->logActivity($_SERVER['REQUEST_URI'],'Marked batch '.$batchID.' as Not Invoiced for WO# '.$thisWorkOrder['order_number']);
		
		echo "OK";exit;
		
	}
	


	
	public function cancelorder($orderID){
		
		if($this->request->data){
			$ordersTable=TableRegistry::get('Orders');
			$thisOrder=$ordersTable->get($orderID);
			$thisOrder->cancelreason=$this->request->data['cancel_reason'];
			$thisOrder->status='Canceled';
			if($ordersTable->save($thisOrder)){
			    
			$workordersTable=TableRegistry::get('WorkOrders');
			$thisWorkOrder=$workordersTable->get($orderID);
			$thisWorkOrder->cancelreason=$this->request->data['cancel_reason'];
			$thisWorkOrder->status='Canceled';
			$workordersTable->save($thisWorkOrder);

				//remove Sherry Schedule items for this order
				$batchFind=$this->SherryBatches->find('all',['conditions'=>['work_order_id' => $orderID]])->toArray();
				foreach($batchFind as $batchRow){

					$batchStatusRowTable=TableRegistry::get('OrderItemStatus');
					//delete ORDERITEMSTATUS rows for this batch
					$batchitemstatusrows=$this->OrderItemStatus->find('all',['conditions' => ['sherry_batch_id'=>$batchRow['id']]])->toArray();
					foreach($batchitemstatusrows as $batchstatusrow){
						$thisBatchStatusEntry=$batchStatusRowTable->get($batchstatusrow['id']);
						$batchStatusRowTable->delete($thisBatchStatusEntry);
					}
					
					$batchStatusRowWorkTable=TableRegistry::get('WorkOrderItemStatus');
					//delete ORDERITEMSTATUS rows for this batch
					$batchitemworkstatusrows=$this->WorkOrderItemStatus->find('all',['conditions' => ['sherry_batch_id'=>$batchRow['id']]])->toArray();
					foreach($batchitemworkstatusrows as $batchstatusrow){
						$thisBatchStatusEntry=$batchStatusRowWorkTable->get($batchstatusrow['id']);
						$batchStatusRowWorkTable->delete($thisBatchStatusEntry);
					}


					$batchSherryCacheTable=TableRegistry::get('SherryCache');
					//delete SHERRY CACHE  rows for this batch
					$batchsherrycacherows=$this->SherryCache->find('all',['conditions'=>['batch_id' => $batchRow['id']]])->toArray();
					foreach($batchsherrycacherows as $batchcacherow){
						$thisSherryCacheBatchEntry=$batchSherryCacheTable->get($batchcacherow['id']);
						$batchSherryCacheTable->delete($thisSherryCacheBatchEntry);
					}


				}

				$this->Flash->success('Successfully voided/canceled Order '.$thisOrder->order_number);
				$this->logActivity($_SERVER['REQUEST_URI'],'Canceled/Voided Order '.$thisOrder->order_number.', Reason: '.$this->request->data['cancel_reason']);
				return $this->redirect('/orders/');
			}
		}else{
			$thisOrder=$this->Orders->get($orderID)->toArray();
			$this->set('orderData',$thisOrder);
		}
		
	}
	

	
	public function edit($orderID){
		
		if($this->request->data){
			
			$ordersTable=TableRegistry::get('Orders');
			$thisOrder=$ordersTable->get($orderID);
			$thisOrder->po_number = $this->request->data['po_number'];
			
			$thisOrder->type_id = $this->request->data['type_id'];
			$thisOrder->shipping_instructions = $this->request->data['shipping_instructions'];
			$thisOrder->shipping_method_id = $this->request->data['shipping_method_id'];
			/**PPSASCRUM-7 Start **/
			/*
			$thisOrder->facility = $this->request->data['facility'];
			$thisOrder->attention = $this->request->data['attention'];
			$thisOrder->shipping_address_1 = $this->request->data['shipping_address_1'];
			$thisOrder->shipping_address_2 = $this->request->data['shipping_address_2'];
			$thisOrder->shipping_city = $this->request->data['shipping_city'];
			$thisOrder->shipping_state = $this->request->data['shipping_state'];
			$thisOrder->shipping_zipcode = $this->request->data['shipping_zipcode'];
			$thisOrder->shipping_instructions = $this->request->data['shipping_instructions'];
			$thisOrder->shipping_method_id = $this->request->data['shipping_method_id'];
			*/
			$thisOrder->attention=$this->request->data['attention'];

			$thisOrder->facility = $this->request->data['allfacility'];
				$thisOrder->shipto_id = $this->request->data['addressSelected'];
			$thisOrder->facility_id = $this->request->data['facilitySelected'];
			$thisOrder->userType = $this->request->data['userAddressesSelection'];
			if($this->request->data['userAddressesSelection'] == 'default') {
				$faciltiyTable=TableRegistry::get('Facility');
				$thisfaciltiy=$faciltiyTable->get($this->request->data['facilitySelected'])->toArray();;
				$thisOrder->shipto_id = $thisfaciltiy['default_address'];
				$shipID= $thisfaciltiy['default_address'];
				
			}else if($this->request->data['userAddressesSelection'] == 'exisiting') {
				$thisOrder->shipto_id = $this->request->data['addressSelected'];
				$shipID= $this->request->data['addressSelected'];
			}else if($this->request->data['userAddressesSelection'] == 'customer') {
			    $thisCustomer=$this->Customers->find('all',['conditions'=>['id'=>$thisOrder['customer_id']]])->first()->toArray();
            	$thisOrder->shipping_address_1=$thisCustomer['shipping_address'];
    
    			$thisOrder->shipping_address_2='';
    
    			$thisOrder->shipping_city=$thisCustomer['shipping_address_city'];
    
    			$thisOrder->shipping_state=$thisCustomer['shipping_address_state'];
    
    			$thisOrder->shipping_zipcode=$thisCustomer['shipping_address_zipcode'];
    
    			$thisOrder->attention=$this->request->data['attention'];
    			$thisOrder->shipping_country=$thisCustomer['shipping_address_country'];
    			$shipID ='';
    			//die(print_r($thisOrder));
			}

            if(!empty($shipID)) {
                $shipToTable = TableRegistry::get('ShipTo');
    			$shipToDetails = $shipToTable->get($thisOrder->shipto_id);
    			$thisOrder->shipping_address_1=$shipToDetails->shipping_address_1;
    
    			$thisOrder->shipping_address_2=$shipToDetails->shipping_address_2;
    
    			$thisOrder->shipping_city=$shipToDetails->shipping_city;
    
    			$thisOrder->shipping_state=$shipToDetails->shipping_state;
    
    			$thisOrder->shipping_zipcode=$shipToDetails->shipping_zipcode;
    
    			$thisOrder->attention=$this->request->data['attention'];
    			$thisOrder->shipping_country=$shipToDetails->shipping_country;
    			//die(print_r($shipToDetails));
           }

			if(!empty($this->request->data['facilityAttention']) && !empty($this->request->data['facilitySelected'])) {
				$faciltiyTable=TableRegistry::get('Facility');
				$thisfaciltiy=$faciltiyTable->get($this->request->data['facilitySelected']);
				$thisfaciltiy->attention=$this->request->data['facilityAttention'];
				$faciltiyTable->save($thisfaciltiy);
				$thisOrder->facility = $thisfaciltiy->facility_name;
			    //$thisOrder->attention = $this->request->data['facilityAttention'];

			}
			/*if(!empty($this->request->data['attentionship'])  && !empty($shipID)) {
				$shipToTable=TableRegistry::get('ShipTo');
				$thisshiptp=$shipToTable->get($shipID);
				$thisshiptp->attention=$this->request->data['attentionship'];
				$shipToTable->save($thisshiptp);

			}*/
			/**PPSASCRUM-7 end **/
			$thisOrder->stage = $this->request->data['order_stage'];
			
			$thisOrder->project_manager_id = $this->request->data['project_manager_id'];
			
			if($this->request->data['hasduedate'] == '1'){

				$thisOrder->due=strtotime($this->request->data['due'].' 15:30:00');

			}else{
			    $thisOrder->due=0;
			}
			
			$thisOrder->created=strtotime($this->request->data['created'].' 11:00:00');
			
			
			
			if($ordersTable->save($thisOrder)){
			    /**PPSASCRUm-29 start */
				$this->saveWorkOrderTable($orderID);
				/**PPSASCRUM-29 end */
			    
			    //find any sherry batches for this order and update their cache dates
    			$batchLookup=$this->SherryCache->find('all',['conditions'=>['order_id' => $orderID]])->toArray();
    			foreach($batchLookup as $batchCacheRow){
    			    
    			    $this->updatesherrycachefordate($batchCacheRow['date']);
    			    
    			}
			    
			    
				$this->Flash->success('Successfully saved changes to Order '.$thisOrder->order_number.' Details');
				$this->logActivity($_SERVER['REQUEST_URI'],'Edited order '.$thisOrder->order_number.' details');
				return $this->redirect('/orders/');
			}
			
		}else{
			$thisOrder=$this->Orders->get($orderID)->toArray();
			$this->set('orderData',$thisOrder);

			$allShippingMethods = $this->ShippingMethods->find('all')->toArray();
			$availableShippingMethods=array();
			foreach($allShippingMethods as $method){
				$availableShippingMethods[$method['id']]=$method['name'];
			}
			$this->set('availableShippingMethods',$availableShippingMethods);
			
			$allUsers=$this->Users->find('all',['conditions' => ['status'=>'Active'],'order'=>['last_name'=>'asc','first_name'=>'asc']])->toArray();
			$this->set('allUsers',$allUsers);
			
			$allTypes=$this->QuoteTypes->find('all')->toArray();
            $this->set('allTypes',$allTypes);
             /**PPSASCRUM-7 start **/
            $thisCustomer=$this->Customers->find('all',['condtions'=>['id'=>$thisOrder['customer_id']]])->first()->toArray();
			$this->set('customerData',$thisCustomer);
			
			
            $allFaclity=$this->Facility->find('list',['keyField' => 'id', 'valueField' => 'facility_name', 'conditions' => [],'order'=>['facility_name'=>'asc']])->toArray();
			$this->set('allFacility',$allFaclity);

			$shipTooptions=$this->ShipTo->find('list',['keyField' => 'id', 'valueField' => 'shipping_address_1', 'conditions' => [],'order'=>['shipping_address_1'=>'asc']])->toArray();
			$this->set('shipTooptions',$shipTooptions);
            /**PPSASCRUM-7 end **/

		}
		
	}
	
	
	//public function getsherryschedulerows($startDate,$endDate,$highlightIDs=false,$jsout=false){
	  public function getsherryschedulerows($startDate,$endDate,$path="schedule",$highlightIDs=false,$jsout=false){
	
		if(!$highlightIDs){
		    $highlightRows=array();
		}else{
		    $highlightRows=explode(',',$highlightIDs);
		}

		
		$output='';
		
		$startTS=strtotime($startDate.' 00:00:00');
		$endTS = strtotime($endDate.' 23:59:59');
		
		$overallTotals=array(
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
			'trklf' => 0,
			'total_items' => 0
		);
		
		$days = ceil((($endTS-$startTS)/86400));
		
		for($i = 1; $i <= $days; $i++){
			
			$thisDayTS=($startTS + (($i-1) * 86400));
			$thisDayDate = date('Y-m-d',$thisDayTS);
			
			$dayTotals=array(
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
				'trklf' => 0,
				'total_items' => 0
			);
			if($path == 'pendingschedule') 
				$conditions=['date' => $thisDayDate, 'batch_shipped_date' => 0, 'batch_completed_date'=>0];
			elseif($path == 'completedschedule')
			    $conditions=[ 'batch_completed_date >=' => strtotime($thisDayDate.' 00:00:00'),
				'batch_completed_date <' => strtotime($thisDayDate.' 23:59:59'),'batch_completed_date !='=>0];
			else 
				$conditions=['date' => $thisDayDate];
			//$conditions=['date' => $thisDayDate];
			//print_r($conditions);
			$thisRowOutput='';
			$thisDayOutput='';
			
			$scheduleEntries=$this->SherryCache->find('all',['conditions' => $conditions])->toArray();
			//print_r($scheduleEntries);exit;
			
			foreach($scheduleEntries as $scheduleItem){
				

				foreach($dayTotals as $key => $val){
					$dayTotals[$key] = ($dayTotals[$key] + floatval($scheduleItem[$key]));
				}
				
				foreach($overallTotals as $key => $val){
					$overallTotals[$key] = ($overallTotals[$key] + floatval($scheduleItem[$key]));
				}
			
				$batchNoteClass="";

				$thisRowOutput .= "<tr data-batch-id=\"".$scheduleItem['batch_id']."\" data-workorder-number=\"".$scheduleItem['order_number']."\" class=\"entryrow";

				if(in_array($scheduleItem['order_number'],$highlightRows)){
					$thisRowOutput .= " searchhighlighted";
				}
				
				if($scheduleItem['batch_completed_date'] > 0 && $scheduleItem['batch_shipped_date'] == 0){
					$thisRowOutput .= " completednotshipped\">";

					$batchNoteClass .= "batchnotecompletednotshipped";

					$thisRowOutput .= "<td data-colnum=\"1\">";
					
					if($scheduleItem['batch_invoiced_date'] == 0){
					    $thisRowOutput .= "<a href=\"javascript:markiteminvoiced(".$scheduleItem['batch_id'].");\"><img src=\"/img/invoiced.png\" alt=\"Mark Batch as Invoiced\" title=\"Mark Batch as Invoiced\" /></a> ";
					}elseif($scheduleItem['batch_invoiced_date'] > 0){
					    $thisRowOutput .= "<a href=\"javascript:markitemuninvoiced(".$scheduleItem['batch_id'].");\"><img src=\"/img/uninvoiced.png\" alt=\"Revert batch to Not Invoiced\" title=\"Revert batch to Not Invoiced\" /></a> ";
					}

					
					$thisRowOutput .= "<a href=\"/orders/viewbatchschedule/".$scheduleItem['batch_id']."\" target=\"_blank\"><img src=\"/img/view.png\" alt=\"View This Batch\" title=\"View This Batch\" /></a> ";
					
					$thisRowOutput .= "<a href=\"/orders/editbatchdate/".$scheduleItem['batch_id']."\"><img src=\"/img/changedateicon.png\" width=\"22\" alt=\"Change Batch Date\" title=\"Change Batch Date\" /></a> ";
					
					$thisRowOutput .= "<a href=\"javascript:voidBatchCompletion(".$scheduleItem['batch_id'].");\"><img src=\"/img/void.png\" alt=\"Void This Completion, Revert to Scheduled\" title=\"Void This Completion, Revert to Scheduled\" /></a> <a href=\"javascript:markitemshipped(".$scheduleItem['batch_id'].");\"><img src=\"/img/Transport-Truck-icon.png\" alt=\"Mark This Batch As Shipped\" title=\"Mark This Batch As Shipped\" /></a>";
					// <a href=\"/orders/deletesherrybatch/".$scheduleItem['batch_id']."\"><img src=\"/img/delete.png\" alt=\"Delete This Batch\" title=\"Delete This Batch\" /></a>
				}elseif($scheduleItem['batch_completed_date'] > 0 && $scheduleItem['batch_shipped_date'] > 0){
					$thisRowOutput .= " completedandshipped\">";

					$batchNoteClass .= "batchnotecompletedandshipped";

					$thisRowOutput .= "<td data-colnum=\"1\">";
					
					if($scheduleItem['batch_invoiced_date'] == 0){
					    $thisRowOutput .= "<a href=\"javascript:markiteminvoiced(".$scheduleItem['batch_id'].");\"><img src=\"/img/invoiced.png\" alt=\"Mark Batch as Invoiced\" title=\"Mark Batch as Invoiced\" /></a> ";
					}elseif($scheduleItem['batch_invoiced_date'] > 0){
					    $thisRowOutput .= "<a href=\"javascript:markitemuninvoiced(".$scheduleItem['batch_id'].");\"><img src=\"/img/uninvoiced.png\" alt=\"Revert batch to Not Invoiced\" title=\"Revert batch to Not Invoiced\" /></a> ";
					}
					
					$thisRowOutput .= "<a href=\"/orders/viewbatchschedule/".$scheduleItem['batch_id']."\" target=\"_blank\"><img src=\"/img/view.png\" alt=\"View This Batch\" title=\"View This Batch\" /></a> ";
					
					$thisRowOutput .= "<a href=\"javascript:voidShipmentBatch(".$scheduleItem['batch_id'].");\"><img src=\"/img/void.png\" alt=\"Void This Shipment, Revert to Completed\" title=\"Void This Shipment, Revert to Completed\" /></a>";
					
					$thisRowOutput .= "<a href=\"/orders/editbatchdate/".$scheduleItem['batch_id']."\"><img src=\"/img/changedateicon.png\" width=\"22\" alt=\"Change Batch Date\" title=\"Change Batch Date\" /></a> ";
					
					// <a href=\"/orders/deletesherrybatch/".$scheduleItem['batch_id']."\"><img src=\"/img/delete.png\" alt=\"Delete This Batch\" title=\"Delete This Batch\" /></a>
				}else{
					if(strtotime($scheduleItem['date'].' 17:00:00') < time()){
						$thisRowOutput .= " pastduebatch";
						$batchNoteClass .= "batchnotepastduebatch ";
					}

					$thisRowOutput .= " notcompletednotshipped\">";

					$batchNoteClass .= "batchnotenotcompletednotshipped";

					$thisRowOutput .= "<td data-colnum=\"1\">";
					
					if($scheduleItem['batch_invoiced_date'] == 0){
					    $thisRowOutput .= "<a href=\"javascript:markiteminvoiced(".$scheduleItem['batch_id'].");\"><img src=\"/img/invoiced.png\" alt=\"Mark Batch as Invoiced\" title=\"Mark Batch as Invoiced\" /></a> ";
					}elseif($scheduleItem['batch_invoiced_date'] > 0){
					    $thisRowOutput .= "<a href=\"javascript:markitemuninvoiced(".$scheduleItem['batch_id'].");\"><img src=\"/img/uninvoiced.png\" alt=\"Revert batch to Not Invoiced\" title=\"Revert batch to Not Invoiced\" /></a> ";
					}
					
					$thisRowOutput .= "<a href=\"/orders/viewbatchschedule/".$scheduleItem['batch_id']."\" target=\"_blank\"><img src=\"/img/view.png\" alt=\"View This Batch\" title=\"View This Batch\" /></a> <a href=\"/orders/editschedule/".$scheduleItem['batch_id']."\"><img src=\"/img/edit.png\" alt=\"Edit This Batch\" title=\"Edit This Batch\" /></a> <a href=\"javascript:markitemcompleted(".$scheduleItem['batch_id'].");\"><img src=\"/img/completed.png\" alt=\"Mark This Batch As Producted (Not Yet Shipped)\" title=\"Mark This Batch As Produced (Not Yet Shipped)\" /></a> <a href=\"/orders/deletesherrybatch/".$scheduleItem['batch_id']."\"><img src=\"/img/delete.png\" alt=\"Delete This Batch\" title=\"Delete This Batch\" /></a>";
                    
				}
				
				// <a href=\"/orders/editschedule/".$scheduleItem['batch_id']."/datechange\"><img src=\"/img/changedateicon.png\" alt=\"Change Batch Date\" width=\"22\" title=\"Change Batch Date\" /></a>

				$thisRowOutput .= " <a href=\"javascript:addBatchNote(".$scheduleItem['batch_id'].");\"><img src=\"/img/stickynote.png\" alt=\"Add Note To This Batch\" title=\"Add Note To This Batch\" /></a>";
				$thisRowOutput .= " <a href=\"/orders/batchboxes/".$scheduleItem['batch_id']."\" target=\"_blank\"><img src=\"/img/box-icon.png\" width=\"24\" alt=\"Box Inventory for this Batch\" title=\"Box Inventory for This Batch\" /></a>";
				$thisRowOutput .= "</td>";
				$thisRowOutput .= "<td data-colnum=\"2\">".$scheduleItem['batch_id'];

				if(!is_null($scheduleItem['order_ship_date']) && intval($scheduleItem['order_ship_date']) > 1000){
					if(strtotime($scheduleItem['date'].' 18:00:00') > strtotime(date('Y-m-d',$scheduleItem['order_ship_date']).' 18:00:00') ){
						$thisRowOutput .= "<br><br><p class=\"latescheduled\" style=\"font-size:11px; vertical-align:middle; line-height:16px; text-align:center;color:red;font-weight:bold;\"><img src=\"/img/alert.png\" style=\"vertical-align:middle;\" /> SCHED PAST SHIP-BY DATE</p>";
					}

				}

				$thisRowOutput .= "</td>";
				$thisRowOutput .= "<td data-colnum=\"3\"><a href=\"/orders/editlines/".$scheduleItem['order_id']."\" target=\"_blank\">".$scheduleItem['order_number']."</a></td>";
				
				
				//$thisRowOutput .= "<td data-colnum=\"4\">".str_replace("'","\'",$scheduleItem['company_name'])."</td>";
				
				/*PPSASCRUM-3 start **/
				$customerData=$this->Customers->get($scheduleItem['customer_id'])->toArray();
				//$on_credit_hold =  ($customerData['on_credit_hold']) ? '<div><span style="color: red;"> On Credit Hold</span></div> ' : '';
					if(strtotime($scheduleItem['date'].' 17:00:00') < time()) {
				    	$on_credit_hold =  ($customerData['on_credit_hold']) ? '<div><span style="color: white;"> On Credit Hold</span></div> ' : '';
				}else {
					$on_credit_hold =  ($customerData['on_credit_hold']) ? '<div><span style="color: red;"> On Credit Hold</span></div> ' : '';

				}
				$thisRowOutput .= "<td data-colnum=\"4\">".str_replace("'","\'",$scheduleItem['company_name']).$on_credit_hold."</td>";
				/*PPSASCRUM-3 end **/
				
				$thisRowOutput .= "<td data-colnum=\"5\">".str_replace("'","\'",$scheduleItem['customer_po_number']);
				
				if(!is_null($scheduleItem['order_type'])){
				    $thisRowOutput .= '<br>'.$scheduleItem['order_type'];
				}
				
				$thisRowOutput .= "</td>";
				$thisRowOutput .= "<td data-colnum=\"6\">".$scheduleItem['order_date']."</td>";
				$thisRowOutput .= "<td data-colnum=\"7\">".str_replace("'","\'",$scheduleItem['project'])."</td>";
				$thisRowOutput .= "<td data-colnum=\"8\">".str_replace("'","\'",$scheduleItem['facility'])."</td>";
				$thisRowOutput .= "<td data-colnum=\"9\">";
				if($scheduleItem['order_ship_date'] > 10000){
					$thisRowOutput .= date('n/j/Y',$scheduleItem['order_ship_date'])."<br>".$scheduleItem['shipping_method'];
				}else{
					$thisRowOutput .= '---';
				}


				if(!is_null($scheduleItem['order_ship_date']) && intval($scheduleItem['order_ship_date']) > 1000 && time() > strtotime(date('Y-m-d',intval($scheduleItem['order_ship_date'])).' 18:00:00') ){

					if(strtotime(date('Y-m-d',$scheduleItem['batch_shipped_date']).' 18:00:00') > strtotime(date('Y-m-d',$scheduleItem['order_ship_date']).' 18:00:00') ){
						$thisRowOutput .= "<br /><br /><p class=\"latescheduled\" style=\"font-size:11px; vertical-align:middle; line-height:16px; text-align:center;color:red;font-weight:bold;\"><img src=\"/img/alert.png\" style=\"vertical-align:middle;\" /> PAST DUE</p>";
					}
				}

				$thisRowOutput .= "</td>";
				$thisRowOutput .= "<td data-colnum=\"10\">\$".number_format($scheduleItem['dollars'],2,'.',',')."</td>";
				$thisRowOutput .= "<td data-colnum=\"11\">".($scheduleItem['cc'] > 0 ? $scheduleItem['cc'] : '')."</td>";
				$thisRowOutput .= "<td data-colnum=\"12\">".($scheduleItem['cclf'] > 0 ? $scheduleItem['cclf'] : '')."</td>";
				$thisRowOutput .= "<td data-colnum=\"13\">".($scheduleItem['trklf'] > 0 ? $scheduleItem['trklf'] : '')."</td>";
				$thisRowOutput .= "<td data-colnum=\"14\">".($scheduleItem['bs'] > 0 ? $scheduleItem['bs'] : '')."</td>";
				$thisRowOutput .= "<td data-colnum=\"15\">".($scheduleItem['drape'] > 0 ? $scheduleItem['drape'] : '')."</td>";
				$thisRowOutput .= "<td data-colnum=\"16\">".($scheduleItem['drape_widths'] > 0 ? $scheduleItem['drape_widths'] : '')."</td>";
				
				$thisRowOutput .= "<td data-colnum=\"17\">".($scheduleItem['val'] > 0 ? $scheduleItem['val'] : '')."</td>";
				$thisRowOutput .= "<td data-colnum=\"18\">".($scheduleItem['vallf'] > 0 ? $scheduleItem['vallf'] : '')."</td>";
				
				$thisRowOutput .= "<td data-colnum=\"19\">".($scheduleItem['corn'] > 0 ? $scheduleItem['corn'] : '')."</td>";
				$thisRowOutput .= "<td data-colnum=\"20\">".($scheduleItem['cornlf'] > 0 ? $scheduleItem['cornlf'] : '')."</td>";
				
				$thisRowOutput .= "<td data-colnum=\"21\">".($scheduleItem['wthw'] > 0 ? $scheduleItem['wthw'] : '')."</td>";
				$thisRowOutput .= "<td data-colnum=\"22\">".($scheduleItem['blinds'] > 0 ? $scheduleItem['blinds'] : '')."</td>";
				$thisRowOutput .= "</tr>";

				//check for any NOTES on this batch item
				$batchNotes=$this->SherryBatchNotes->find('all',['conditions'=>['batch_id' => $scheduleItem['batch_id']]])->toArray();
				foreach($batchNotes as $batchNote){
				    
				    $batchnotetypeclass = strtolower($batchNote['note_type']).'note';
					$thisRowOutput .= "<tr class=\"batchnoterow ".$batchNoteClass." ".$batchnotetypeclass."\"><td colspan=\"2\" class=\"noterowlabel\"><b>".strtoupper($batchNote['note_type'])." NOTE:</b></td><td colspan=\"20\" class=\"notecontent\">".str_replace("'","\'",preg_replace("/\r\n|\r|\n/",'<br/>',$batchNote['message']))."</td></tr>";
				}
				
			}
			
			
			$thisDayOutput = "<tr class=\"daterow\">";
			$thisDayOutput .= "<td colspan=\"9\">".date("l, F jS, Y",$thisDayTS)."</td>";
			$thisDayOutput .= "<td data-colnum=\"10\">\$".number_format($dayTotals['dollars'],2,'.',',')."</td>";
			$thisDayOutput .= "<td data-colnum=\"11\">".($dayTotals['cc'] > 0 ? $dayTotals['cc'] : '')."</td>";
			$thisDayOutput .= "<td data-colnum=\"12\">".($dayTotals['cclf'] > 0 ? $dayTotals['cclf'] : '')."</td>";
			$thisDayOutput .= "<td data-colnum=\"13\">".($dayTotals['trklf'] > 0 ? $dayTotals['trklf'] : '')."</td>";
			$thisDayOutput .= "<td data-colnum=\"14\">".($dayTotals['bs'] > 0 ? $dayTotals['bs'] : '')."</td>";

			$thisDayOutput .= "<td data-colnum=\"15\">".($dayTotals['drape'] > 0 ? $dayTotals['drape'] : '')."</td>";
			$thisDayOutput .= "<td data-colnum=\"16\">".($dayTotals['drape_widths'] > 0 ? $dayTotals['drape_widths'] : '')."</td>";

			$thisDayOutput .= "<td data-colnum=\"17\">".($dayTotals['val'] > 0 ? $dayTotals['val'] : '')."</td>";
			$thisDayOutput .= "<td data-colnum=\"18\">".($dayTotals['vallf'] > 0 ? $dayTotals['vallf'] : '')."</td>";
			
			$thisDayOutput .= "<td data-colnum=\"19\">".($dayTotals['corn'] > 0 ? $dayTotals['corn'] : '')."</td>";
			$thisDayOutput .= "<td data-colnum=\"20\">".($dayTotals['cornlf'] > 0 ? $dayTotals['cornlf'] : '')."</td>";

			$thisDayOutput .= "<td data-colnum=\"21\">".($dayTotals['wthw'] > 0 ? $dayTotals['wthw'] : '')."</td>";
			$thisDayOutput .= "<td data-colnum=\"22\">".($dayTotals['blinds'] > 0 ? $dayTotals['blinds'] : '')."</td>";
			$thisDayOutput .= "</tr>";
			
			$output .= $thisDayOutput.$thisRowOutput;
		}
		
			
		$totalsoutput = "<tr>";
		$totalsoutput .= "<td colspan=\"9\">&nbsp;</td>";
		$totalsoutput .= "<td data-colnum=\"10\" class=\"totalslabel\">\$".number_format($overallTotals['dollars'],2,'.',',')."</td>";
		$totalsoutput .= "<td data-colnum=\"11\">".$overallTotals['cc']."</td>";
		$totalsoutput .= "<td data-colnum=\"12\">".$overallTotals['cclf']."</td>";
		$totalsoutput .= "<td data-colnum=\"13\">".$overallTotals['trklf']."</td>";
		$totalsoutput .= "<td data-colnum=\"14\">".$overallTotals['bs']."</td>";
		$totalsoutput .= "<td data-colnum=\"15\">".$overallTotals['drape']."</td>";
		$totalsoutput .= "<td data-colnum=\"16\">".$overallTotals['drape_widths']."</td>";
		$totalsoutput .= "<td data-colnum=\"17\">".$overallTotals['val']."</td>";
		$totalsoutput .= "<td data-colnum=\"18\">".$overallTotals['vallf']."</td>";
		$totalsoutput .= "<td data-colnum=\"19\">".$overallTotals['corn']."</td>";
		$totalsoutput .= "<td data-colnum=\"20\">".$overallTotals['cornlf']."</td>";
		$totalsoutput .= "<td data-colnum=\"21\">".$overallTotals['wthw']."</td>";
		$totalsoutput .= "<td data-colnum=\"22\">".$overallTotals['blinds']."</td>";
		$totalsoutput .= "</tr>";
	
		
		if(!$jsout){
			$this->set('rawoutput',"<script>
			$(function(){
				$('table#sherrytable tbody').html('".$output."');
				$('table#sherrytable tfoot').html('".$totalsoutput."');

				$('#sherrytable tbody tr.notcompletednotshipped td').hover(function(){
					//add hover column class
					$('#sherrytable thead tr th[data-colnum='+$(this).attr('data-colnum')+'],#sherrytable tbody tr.notcompletednotshipped td[data-colnum='+$(this).attr('data-colnum')+'],#sherrytable tfoot tr td[data-colnum='+$(this).attr('data-colnum')+']').addClass('hovercol');
					
					if($(this).attr('data-colnum') == '11' || $(this).attr('data-colnum') == '12'){
						$('#sherrytable thead th.cubicles').addClass('hovercol');
					}else if($(this).attr('data-colnum') == '14'){
						$('#sherrytable thead th.bedspreads').addClass('hovercol');
					}else if($(this).attr('data-colnum') == '15' || $(this).attr('data-colnum') == '16'){
						$('#sherrytable thead th.draperies').addClass('hovercol');
					}else if($(this).attr('data-colnum') == '17' || $(this).attr('data-colnum') == '18'){
						$('#sherrytable thead th.valances').addClass('hovercol');
					}else if($(this).attr('data-colnum') == '19' || $(this).attr('data-colnum') == '20'){
						$('#sherrytable thead th.cornices').addClass('hovercol');
					}
					
				},function(){
					//remove the class
					$('#sherrytable thead tr th[data-colnum='+$(this).attr('data-colnum')+'],#sherrytable tbody tr.notcompletednotshipped td[data-colnum='+$(this).attr('data-colnum')+'],#sherrytable tfoot tr td[data-colnum='+$(this).attr('data-colnum')+']').removeClass('hovercol');
					
					if($(this).attr('data-colnum') == '11' || $(this).attr('data-colnum') == '12'){
						$('#sherrytable thead th.cubicles').removeClass('hovercol');
					}else if($(this).attr('data-colnum') == '14'){
						$('#sherrytable thead th.bedspreads').removeClass('hovercol');
					}else if($(this).attr('data-colnum') == '15' || $(this).attr('data-colnum') == '16'){
						$('#sherrytable thead th.draperies').removeClass('hovercol');
					}else if($(this).attr('data-colnum') == '17' || $(this).attr('data-colnum') == '18'){
						$('#sherrytable thead th.valances').removeClass('hovercol');
					}else if($(this).attr('data-colnum') == '19' || $(this).attr('data-colnum') == '20'){
						$('#sherrytable thead th.cornices').removeClass('hovercol');
					}
				});
			});
			</script>");
		}else{
			$this->set('rawoutput','<table id="sherrytable" cellpadding="3" cellspacing="0" border="1" bordercolor="#000000">
			<thead>
				<tr>
					<th class="actions" rowspan="2">Actions</th>
					<th class="wonumber" rowspan="2">WO#</th>
					<th class="customer" rowspan="2">Customer</th>
					<th class="ponumber" rowspan="2">PO#</th>
					<th class="wodate" rowspan="2">Date</th>
					<th class="project" rowspan="2">Project</th>
					<th class="facility" rowspan="2">Facility</th>
					<th class="woshipdate" rowspan="2">WO Ship Date</th>
					<th class="totals" rowspan="2">Totals</th>
					<th class="cubicles" colspan="2">CC</th>
					<th class="trk">TRK</th>
					<th class="bedspreads">BS</th>
					<th class="draperies" colspan="2">DRAPERIES</th>
					<th class="valances" colspan="2">VALANCES</th>
                    <th class="cornices" colspan="2">CORNICES</th>
					<th class="wthw">WT HW</th>
					<th class="blinds">B&amp;S</th>
				</tr>
				<tr>
					<th class="ccqty">QTY</th>
					<th class="cclf">LF</th>
					<th class="trklf">LF</th>
					<th class="bsqty">QTY</th>
					<th class="drapeqty">QTY</th>
					<th class="drapewidths">WIDTHS</th>
					<th class="valqty">QTY</th>
					<th class="vallf">LF</th>
					<th class="cornqty">QTY</th>
					<th class="cornlf">LF</th>
					<th class="wthwqty">QTY</th>
					<th class="blindqty">QTY</th>
				</tr>
			</thead>
			<tbody>
			'.$output.'
			</tbody>
			<tfoot>
				'.$totalsoutput.'
			</tfoot>
			</table>');
			
		}
		
	}
	
	

	public function searchsherryschedule($searchterm){
		$this->autoRender=false;
		$numres=0;
		$batches=$this->SherryBatches->find('all',['conditions' => ['work_order_number LIKE' => '%'.$searchterm.'%'],'order'=>['date'=>'asc']])->toArray();
		echo "<table style=\"margin:0 0 0 0;\" width=\"100%\" cellpadding=\"5\" cellspacing=\"0\" border=\"0\"><thead><tr><th>WO#</th><th>BATCH #</th><th>DATE</th><th>ACTION</th></tr></thead><tbody>";

		foreach($batches as $batch){
			if($numres==0){
				$firstDate=date('Y-m-d',strtotime($batch['date']));
				$firstDatePretty=$batch['date'];
			}
			echo "<tr><td>".$batch['work_order_number']."</td><td>".$batch['id']."</td><td>".$batch['date']."</td><td><button type=\"button\" onclick=\"changeCalendarDateRange('".date('Y-m-d',strtotime($batch['date']))."','".date('Y-m-d',strtotime($batch['date']))."','".$batch['work_order_number']."',false)\" style=\"font-size:11px; padding:5px 5px 5px 5px !important; border:0;margin:0 0 0 0;\">Go To Date</button></td></tr>";
			$numres++;
			if($numres==count($batches)){
				$lastDate=date('Y-m-d',strtotime($batch['date']));
				$lastDatePretty=$batch['date'];
			}
		}
		echo "</tbody></table>";
		if($numres > 1){
			echo "<div style=\"margin:0 0 0 0; padding:0px 0; text-align:center;\"><button type=\"button\" style=\"display:block; width:100%; font-size:14px; margin:0 0 0 0; padding:8px 0px 8px 0px !important; border:0;\" onclick=\"changeCalendarDateRange('".$firstDate."','".$lastDate."','".$searchterm."',false)\">Go To Date Range ".$firstDatePretty." - ".$lastDatePretty."</button></div>";
		}
		exit;
	}


	public function addbatchnote($batchid,$frombreakdown=0){
	
		if($this->request->data){

			$this->autoRender=false;
			$batchNoteTable=TableRegistry::get('SherryBatchNotes');
			$newNote=$batchNoteTable->newEntity();
			$newNote->user_id=$this->Auth->user('id');
			$newNote->time=time();
			$newNote->message=$this->request->data['message'];
			$newNote->batch_id=$batchid;
			$newNote->note_type = $this->request->data['note_type'];

			if($batchNoteTable->save($newNote)){
				$this->logActivity($_SERVER['REQUEST_URI'],'Added '.$this->request->data['note_type'].' note to batch# '.$batchid);
				if($frombreakdown == 1){
					echo "<html><head><script>parent.window.location.reload(true);</script></head><body></body></html>";exit;
				}else{
					echo "<html><head><script>parent.$.fancybox.close();parent.reloadSchedule();parent.$('#lastrefreshcounter b').html('10');</script></head><body></body></html>";exit;
				}
			}
		}else{
			$this->viewBuilder()->layout('iframeinner');
		}

	}


	public function voidshipmentbatch($batchID,$frombreakdown=0){
		$thisBatch=$this->SherryBatches->get($batchID)->toArray();
		$thisOrderID=$thisBatch['work_order_id'];

        $thisWorkOrder=$this->WorkOrders->get($thisOrderID)->toArray();
        
		$thisDate=$thisBatch['date'];

		if($this->request->data){
			$this->autoRender=false;

			$orderitemstatustable=TableRegistry::get('WorkOrderItemStatus');

			$shipmentStatusLines=$this->WorkOrderItemStatus->find('all',['conditions'=>['sherry_batch_id' => $batchID, 'status' => 'Shipped']])->toArray();
			foreach($shipmentStatusLines as $shipmentStatus){
				$thisStatus=$orderitemstatustable->get($shipmentStatus['id']);
				$thisStatus->status = 'Shipment Voided';
				$orderitemstatustable->save($thisStatus);
			}

			$sherryCacheTable=TableRegistry::get('SherryCache');
			$sherryCacheLookup=$this->SherryCache->find('all',['conditions'=>['batch_id' => $batchID]])->toArray();
			foreach($sherryCacheLookup as $sherryCacheRow){
				$thisSherryCache=$sherryCacheTable->get($sherryCacheRow['id']);
				$thisSherryCache->batch_shipped_date = 0;
				$sherryCacheTable->save($thisSherryCache);
			}

			$this->updatesherrycachefordate($thisDate);
			/* PPSASCRUM-248: start [updating function with arguments as per complete set of function parameters] */
			$this->auditOrderItemStatuses($thisOrderID, false, 'workorder');
			/* PPSASCRUM-248: end */

			$this->logActivity($_SERVER['REQUEST_URI'],'Voided "Shipped" batch status for batch #'.$batchID.' (WO# '.$thisWorkOrder['order_number'].')');

			if($frombreakdown == 1){
					echo "<html><head><script>parent.window.location.reload(true);</script></head><body></body></html>";exit;
			}else{
				echo "<html><head><script>parent.$.fancybox.close();parent.reloadSchedule();parent.$('#lastrefreshcounter b').html('10');</script></head><body></body></html>";exit;
			}

		}else{
			$this->viewBuilder()->layout('iframeinner');
		}
	}


	public function voidbatchcompletion($batchID,$frombreakdown=0){
		$thisBatch=$this->SherryBatches->get($batchID)->toArray();
		$thisOrderID=$thisBatch['work_order_id'];

        $thisWorkOrder=$this->Orders->get($thisOrderID)->toArray();
        
		$thisDate=$thisBatch['date'];

		if($this->request->data){
			$this->autoRender=false;

			$orderitemstatustable=TableRegistry::get('WorkOrderItemStatus');

			$shipmentStatusLines=$this->WorkOrderItemStatus->find('all',['conditions'=>['sherry_batch_id' => $batchID, 'status' => 'Completed']])->toArray();
			foreach($shipmentStatusLines as $shipmentStatus){
				$thisStatus=$orderitemstatustable->get($shipmentStatus['id']);
				$thisStatus->status = 'Completion Voided';
				$orderitemstatustable->save($thisStatus);
			}

			$sherryCacheTable=TableRegistry::get('SherryCache');
			$sherryCacheLookup=$this->SherryCache->find('all',['conditions'=>['batch_id' => $batchID]])->toArray();
			foreach($sherryCacheLookup as $sherryCacheRow){
				$thisSherryCache=$sherryCacheTable->get($sherryCacheRow['id']);
				$thisSherryCache->batch_completed_date = 0;
				$sherryCacheTable->save($thisSherryCache);
			}
			
			$this->updatesherrycachefordate($thisDate);
			/* PPSASCRUM-248: start [updating function with arguments as per complete set of function parameters] */
			$this->auditOrderItemStatuses($thisOrderID, false, 'workorder');
			/* PPSASCRUM-248: end */

			$this->logActivity($_SERVER['REQUEST_URI'],'Voided "Completion" batch status for batch #'.$batchID.' (WO#'.$thisWorkOrder['order_number'].')');

			if($frombreakdown == 1){
					echo "<html><head><script>parent.window.location.reload(true);</script></head><body></body></html>";exit;
			}else{
				echo "<html><head><script>parent.$.fancybox.close();parent.reloadSchedule();parent.$('#lastrefreshcounter b').html('10');</script></head><body></body></html>";exit;
			}

		}else{
			$this->viewBuilder()->layout('iframeinner');
		}
	}


    	/*public function printschedule($startDate,$endDate,$path="schedule",$cubicles,$bedspreads,$draperies,$toptreatments,$tracks,$hardware,$blinds){

	//public function printschedule($startDate,$endDate,$cubicles,$bedspreads,$draperies,$toptreatments,$tracks,$hardware,$blinds){

		$output='';

		$GLOBALS['pdfheader']='<h1>Sherry Schedule for <u>'.$startDate.'</u> through <u>'.$endDate.'</u></h1>';
		
		$startTS=strtotime($startDate,' 00:00:00');
		$endTS = strtotime($endDate.' 23:59:59');
		
		$overallTotals=array(
			'dollars' => 0,
			'cc' => 0,
			'cclf' => 0,
			'ccdiff' => 0,
			'bs' => 0,
			'bsdiff' => 0,
			'wt' => 0,
			'wtlf' => 0,
			'drape' => 0,
			'drape_widths' => 0,
			'wthw' => 0,
			'blinds' => 0,
			'trklf' => 0,
			'drapediff' => 0,
			'wtdiff' => 0,
			'total_items' => 0
		);
		
		$days = ceil((($endTS-$startTS)/86400));

		$itemRows=0;
		
		for($i = 1; $i <= $days; $i++){
			
			$thisDayTS=($startTS + (($i-1) * 86400));
			$thisDayDate = date('Y-m-d',$thisDayTS);
			
			$dayTotals=array(
				'dollars' => 0,
				'cc' => 0,
				'cclf' => 0,
				'ccdiff' => 0,
				'bs' => 0,
				'bsdiff' => 0,
				'wt' => 0,
				'wtlf' => 0,
				'drape' => 0,
				'drape_widths' => 0,
				'wthw' => 0,
				'blinds' => 0,
				'trklf' => 0,
				'drapediff' => 0,
				'wtdiff' => 0,
				'total_items' => 0
			);
			if($path == 'pendingschedule') 
				$conditions=['date' => $thisDayDate, 'batch_shipped_date' => 0, 'batch_completed_date'=>0];
			else 
				$conditions=['date' => $thisDayDate];
		//	$conditions=['date' => $thisDayDate];
			//print_r($conditions);
			$thisRowOutput='';
			$thisDayOutput='';
			
			$scheduleEntries=$this->SherryCache->find('all',['conditions' => $conditions])->toArray();
			//print_r($scheduleEntries);exit;
			
			foreach($scheduleEntries as $scheduleItem){
				
				foreach($dayTotals as $key => $val){
					$dayTotals[$key] = ($dayTotals[$key] + floatval($scheduleItem[$key]));
				}
				
				foreach($overallTotals as $key => $val){
					$overallTotals[$key] = ($overallTotals[$key] + floatval($scheduleItem[$key]));
				}


				if($itemRows % 2 == 0){
					$bgcolor="#E5E5E5";
				}else{
					$bgcolor="#FFFFFF";
				}

			
				$batchNoteClass="";

				$thisRowOutput .= "<tr style=\"background-color:".$bgcolor.";\">";
				$thisRowOutput .= "<td style=\"font-size:5px; text-align:center;\">".$scheduleItem['batch_id']."</td>";
				$thisRowOutput .= "<td style=\"font-size:5px; text-align:center;\">".$scheduleItem['order_number']."</td>";
				$thisRowOutput .= "<td style=\"font-size:5px;\">".str_replace("'","\'",$scheduleItem['company_name'])."</td>";
				$thisRowOutput .= "<td style=\"font-size:5px;\">".str_replace("'","\'",$scheduleItem['customer_po_number'])."</td>";
				$thisRowOutput .= "<td style=\"font-size:5px;\">".$scheduleItem['order_date']."</td>";
				$thisRowOutput .= "<td style=\"font-size:5px;\">".str_replace("'","\'",$scheduleItem['project'])."</td>";
				$thisRowOutput .= "<td style=\"font-size:5px;\">".str_replace("'","\'",$scheduleItem['facility'])."</td>";
				$thisRowOutput .= "<td style=\"font-size:5px; text-align:center;\">";
				if($scheduleItem['order_ship_date'] > 10000){
					$thisRowOutput .= date('n/j/Y',$scheduleItem['order_ship_date']);
				}else{
					$thisRowOutput .= '---';
				}
				$thisRowOutput .= "</td>";
				$thisRowOutput .= "<td style=\"font-size:5px; text-align:center;\">\$".number_format($scheduleItem['dollars'],2,'.',',')."</td>";

				$numCols=7;

				if($cubicles == 1){
					$thisRowOutput .= "<td style=\"font-size:5px; text-align:center;\">".($scheduleItem['cc'] > 0 ? $scheduleItem['cc'] : '')."</td>";
					$thisRowOutput .= "<td style=\"font-size:5px; text-align:center;\">".($scheduleItem['cclf'] > 0 ? $scheduleItem['cclf'] : '')."</td>";
					$thisRowOutput .= "<td style=\"font-size:5px; text-align:center;\">".(ceil(floatval($scheduleItem['ccdiff'])) > 0 ? ceil(floatval($scheduleItem['ccdiff'])) : '')."</td>";

					$numCols=($numCols + 3);
				}

				if($tracks == 1){
					$thisRowOutput .= "<td style=\"font-size:5px; text-align:center;\">".($scheduleItem['trklf'] > 0 ? $scheduleItem['trklf'] : '')."</td>";
					$numCols=($numCols + 1);
				}

				if($bedspreads == 1){
					$thisRowOutput .= "<td style=\"font-size:5px; text-align:center;\">".($scheduleItem['bs'] > 0 ? $scheduleItem['bs'] : '')."</td>";
					$thisRowOutput .= "<td style=\"font-size:5px; text-align:center;\">&nbsp;</td>";
					$numCols=($numCols + 2);
				}

				if($draperies == 1){
					$thisRowOutput .= "<td style=\"font-size:5px; text-align:center;\">".($scheduleItem['drape'] > 0 ? $scheduleItem['drape'] : '')."</td>";
					$thisRowOutput .= "<td style=\"font-size:5px; text-align:center;\">".($scheduleItem['drape_widths'] > 0 ? $scheduleItem['drape_widths'] : '')."</td>";
					$thisRowOutput .= "<td style=\"font-size:5px; text-align:center;\">&nbsp;</td>";
					$numCols=($numCols + 3);
				}

				if($toptreatments == 1){
					$thisRowOutput .= "<td style=\"font-size:5px; text-align:center;\">".($scheduleItem['wt'] > 0 ? $scheduleItem['wt'] : '')."</td>";
					$thisRowOutput .= "<td style=\"font-size:5px; text-align:center;\">".($scheduleItem['wtlf'] > 0 ? $scheduleItem['wtlf'] : '')."</td>";
					$thisRowOutput .= "<td style=\"font-size:5px; text-align:center;\">&nbsp;</td>";
					$numCols=($numCols + 3);
				}

				if($hardware == 1){
					$thisRowOutput .= "<td style=\"font-size:5px; text-align:center;\">".($scheduleItem['wthw'] > 0 ? $scheduleItem['wthw'] : '')."</td>";
					$numCols=($numCols + 1);
				}

				if($blinds == 1){
					$thisRowOutput .= "<td style=\"font-size:5px; text-align:center;\">".($scheduleItem['blinds'] > 0 ? $scheduleItem['blinds'] : '')."</td>";
					$numCols=($numCols + 1);
				}

				$thisRowOutput .= "</tr>";

				//check for any NOTES on this batch item
				$batchNotes=$this->SherryBatchNotes->find('all',['conditions'=>['batch_id' => $scheduleItem['batch_id']]])->toArray();
				foreach($batchNotes as $batchNote){
					$thisRowOutput .= "<tr style=\"background-color:".$bgcolor.";\"><td colspan=\"2\" style=\"font-size:7px; text-align:right;\"><b>".strtoupper($batchNote['note_type'])." NOTE:</b></td><td colspan=\"".$numCols."\" style=\"font-size:7px;\">".$batchNote['message']."</td></tr>";
				}
				
				$itemRows++;
			}
			
			
			$thisDayOutput = "<tr style=\"background-color:#444444;\">";
			$thisDayOutput .= "<td colspan=\"8\" style=\"font-size:5px; text-align:left; font-weight:bold; color:#FFFFFF;\">".date("l, F jS, Y",$thisDayTS)."</td>";
			$thisDayOutput .= "<td style=\"font-size:5px; text-align:center; color:#FFFFFF; font-weight:bold;\">\$".number_format($dayTotals['dollars'],2,'.',',')."</td>";

			if($cubicles == 1){
				$thisDayOutput .= "<td style=\"font-size:5px; color:#FFFFFF; text-align:center; font-weight:bold;\">".($dayTotals['cc'] > 0 ? $dayTotals['cc'] : '')."</td>";
				$thisDayOutput .= "<td style=\"font-size:5px; color:#FFFFFF; text-align:center; font-weight:bold;\">".($dayTotals['cclf'] > 0 ? $dayTotals['cclf'] : '')."</td>";
				$thisDayOutput .= "<td style=\"font-size:5px; color:#FFFFFF; text-align:center; font-weight:bold;\">".(ceil(floatval($dayTotals['ccdiff'])) > 0 ? ceil(floatval($dayTotals['ccdiff'])) : '')."</td>";
			}

			if($tracks == 1){
				$thisDayOutput .= "<td style=\"font-size:5px; color:#FFFFFF; text-align:center; font-weight:bold;\">".($dayTotals['trklf'] > 0 ? $dayTotals['trklf'] : '')."</td>";
			}

			if($bedspreads == 1){
				$thisDayOutput .= "<td style=\"font-size:5px; color:#FFFFFF; text-align:center; font-weight:bold;\">".($dayTotals['bs'] > 0 ? $dayTotals['bs'] : '')."</td>";
				$thisDayOutput .= "<td style=\"font-size:5px; color:#FFFFFF; text-align:center; font-weight:bold;\">&nbsp;</td>";
			}

			if($draperies == 1){
				$thisDayOutput .= "<td style=\"font-size:5px; color:#FFFFFF; text-align:center; font-weight:bold;\">".($dayTotals['drape'] > 0 ? $dayTotals['drape'] : '')."</td>";
				$thisDayOutput .= "<td style=\"font-size:5px; color:#FFFFFF; text-align:center; font-weight:bold;\">".($dayTotals['drape_widths'] > 0 ? $dayTotals['drape_widths'] : '')."</td>";
				$thisDayOutput .= "<td style=\"font-size:5px; color:#FFFFFF; text-align:center; font-weight:bold;\">&nbsp;</td>";
			}

			if($toptreatments == 1){
				$thisDayOutput .= "<td style=\"font-size:5px; color:#FFFFFF; text-align:center; font-weight:bold;\">".($dayTotals['wt'] > 0 ? $dayTotals['wt'] : '')."</td>";
				$thisDayOutput .= "<td style=\"font-size:5px; color:#FFFFFF; text-align:center; font-weight:bold;\">".($dayTotals['wtlf'] > 0 ? $dayTotals['wtlf'] : '')."</td>";
				$thisDayOutput .= "<td style=\"font-size:5px; color:#FFFFFF; text-align:center; font-weight:bold;\">&nbsp;</td>";
			}

			if($hardware == 1){
				$thisDayOutput .= "<td style=\"font-size:5px; color:#FFFFFF; text-align:center; font-weight:bold;\">".($dayTotals['wthw'] > 0 ? $dayTotals['wthw'] : '')."</td>";
			}

			if($blinds == 1){
				$thisDayOutput .= "<td style=\"font-size:5px; color:#FFFFFF; text-align:center; font-weight:bold;\">".($dayTotals['blinds'] > 0 ? $dayTotals['blinds'] : '')."</td>";
			}

			$thisDayOutput .= "</tr>";
			
			$output .= $thisDayOutput.$thisRowOutput;
		}
		
			
		$totalsoutput = "<tr style=\"background-color:#000000;\">";
		$totalsoutput .= "<td colspan=\"8\">&nbsp;</td>";
		$totalsoutput .= "<td style=\"font-size:5px; color:#FFFFFF; text-align:center;\">\$".number_format($overallTotals['dollars'],2,'.',',')."</td>";

		if($cubicles == 1){
			$totalsoutput .= "<td style=\"font-size:5px; color:#FFFFFF; text-align:center;\">".$overallTotals['cc']."</td>";
			$totalsoutput .= "<td style=\"font-size:5px; color:#FFFFFF; text-align:center;\">".$overallTotals['cclf']."</td>";
			$totalsoutput .= "<td style=\"font-size:5px; color:#FFFFFF; text-align:center;\">".ceil(floatval($overallTotals['ccdiff']))."</td>";
		}

		if($tracks == 1){
			$totalsoutput .= "<td style=\"font-size:5px; color:#FFFFFF; text-align:center;\">".$overallTotals['trklf']."</td>";
		}

		if($bedspreads == 1){
			$totalsoutput .= "<td style=\"font-size:5px; color:#FFFFFF; text-align:center;\">".$overallTotals['bs']."</td>";
			$totalsoutput .= "<td style=\"font-size:5px; color:#FFFFFF; text-align:center;\">&nbsp;</td>";
		}

		if($draperies == 1){
			$totalsoutput .= "<td style=\"font-size:5px; color:#FFFFFF; text-align:center;\">".$overallTotals['drape']."</td>";
			$totalsoutput .= "<td style=\"font-size:5px; color:#FFFFFF; text-align:center;\">".$overallTotals['drape_widths']."</td>";
			$totalsoutput .= "<td style=\"font-size:5px; color:#FFFFFF; text-align:center;\">&nbsp;</td>";
		}

		if($toptreatments == 1){
			$totalsoutput .= "<td style=\"font-size:5px; color:#FFFFFF; text-align:center;\">".$overallTotals['wt']."</td>";
			$totalsoutput .= "<td style=\"font-size:5px; color:#FFFFFF; text-align:center;\">".$overallTotals['wtlf']."</td>";
			$totalsoutput .= "<td style=\"font-size:5px; color:#FFFFFF; text-align:center;\">&nbsp;</td>";
		}

		if($hardware == 1){
			$totalsoutput .= "<td style=\"font-size:5px; color:#FFFFFF; text-align:center;\">".$overallTotals['wthw']."</td>";
		}

		if($blinds == 1){
			$totalsoutput .= "<td style=\"font-size:5px; color:#FFFFFF; text-align:center;\">".$overallTotals['blinds']."</td>";
		}

		$totalsoutput .= "</tr>";
	
		
		$tableoutput='<table width="94%" cellpadding="2" cellspacing="0" border="1" bordercolor="#000000">
			<thead>
				<tr style="background-color:#000000;">';

					if($cubicles==0 && $tracks==0 && $bedspreads == 0 && $draperies == 0 && $toptreatments == 0 && $hardware == 0 && $blinds == 0){
						$tableoutput .= '<th class="wonumber" style="font-size:5px; text-align:center; color:#FFFFFF;">BATCH#</th>
						<th class="wonumber" style="font-size:5px; text-align:center; color:#FFFFFF;">WO#</th>
						<th class="customer" style="font-size:5px; text-align:center; color:#FFFFFF;">Customer</th>
						<th class="ponumber" style="font-size:5px; text-align:center; color:#FFFFFF;">PO#</th>
						<th class="wodate" style="font-size:5px; text-align:center; color:#FFFFFF;">Date</th>
						<th class="project" style="font-size:5px; text-align:center; color:#FFFFFF;">Project</th>
						<th class="facility" style="font-size:5px; text-align:center; color:#FFFFFF;">Facility</th>
						<th class="woshipdate" style="font-size:5px; text-align:center; color:#FFFFFF;">WO Ship Date</th>
						<th class="totals" style="font-size:5px; text-align:center; color:#FFFFFF;">Totals</th>
						</tr>';
					}else{
						$tableoutput .= '<th class="wonumber" rowspan="2" style="font-size:5px; text-align:center; color:#FFFFFF;">BATCH#</th>
						<th class="wonumber" rowspan="2" style="font-size:5px; text-align:center; color:#FFFFFF;">WO#</th>
						<th class="customer" rowspan="2" style="font-size:5px; text-align:center; color:#FFFFFF;">Customer</th>
						<th class="ponumber" rowspan="2" style="font-size:5px; text-align:center; color:#FFFFFF;">PO#</th>
						<th class="wodate" rowspan="2" style="font-size:5px; text-align:center; color:#FFFFFF;">Date</th>
						<th class="project" rowspan="2" style="font-size:5px; text-align:center; color:#FFFFFF;">Project</th>
						<th class="facility" rowspan="2" style="font-size:5px; text-align:center; color:#FFFFFF;">Facility</th>
						<th class="woshipdate" rowspan="2" style="font-size:5px; text-align:center; color:#FFFFFF;">WO Ship Date</th>
						<th class="totals" rowspan="2" style="font-size:5px; text-align:center; color:#FFFFFF;">Totals</th>';
					
						if($cubicles == 1){
							$tableoutput .= '<th class="cubicles" colspan="3" style="font-size:5px; text-align:center; color:#FFFFFF;">CC</th>';
						}

						if($tracks == 1){
							$tableoutput .= '<th class="trk" style="font-size:5px; text-align:center; color:#FFFFFF;">TRK</th>';
						}
						
						if($bedspreads == 1){
							$tableoutput .= '<th class="bedspreads" colspan="2" style="font-size:5px; text-align:center; color:#FFFFFF;">BS</th>';
						}

						if($draperies == 1){
							$tableoutput .= '<th class="draperies" colspan="3" style="font-size:5px; text-align:center; color:#FFFFFF;">DRAPERIES</th>';
						}

						if($toptreatments == 1){
							$tableoutput .= '<th class="toptreatments" colspan="3" style="font-size:5px; text-align:center; color:#FFFFFF;">TOP TREATMENTS</th>';
						}
						
						if($hardware == 1){
							$tableoutput .= '<th class="wthw" style="font-size:5px; text-align:center; color:#FFFFFF;">WT HW</th>';
						}

						if($blinds == 1){
							$tableoutput .= '<th class="blinds" style="font-size:5px; text-align:center; color:#FFFFFF;">B&amp;S</th>';
						}

					$tableoutput .= '</tr>
					<tr style="background-color:#000000;">';
					if($cubicles == 1){
						$tableoutput .= '<th class="ccqty" style="font-size:5px; text-align:center; color:#FFFFFF;">QTY</th>';
						$tableoutput .= '<th class="cclf" style="font-size:5px; text-align:center; color:#FFFFFF;">LF</th>';
						$tableoutput .= '<th class="ccdiff" style="font-size:5px; text-align:center; color:#FFFFFF;">DIFF</th>';
					}

					if($tracks == 1){
						$tableoutput .= '<th class="trklf" style="font-size:5px; text-align:center; color:#FFFFFF;">LF</th>';
					}

					if($bedspreads == 1){
						$tableoutput .= '<th class="bsqty" style="font-size:5px; text-align:center; color:#FFFFFF;">QTY</th>';
						$tableoutput .= '<th class="bsdiff" style="font-size:5px; text-align:center; color:#FFFFFF;">DIFF</th>';
					}

					if($draperies == 1){
						$tableoutput .= '<th class="drapeqty" style="font-size:5px; text-align:center; color:#FFFFFF;">QTY</th>';
						$tableoutput .= '<th class="drapewidths" style="font-size:5px; text-align:center; color:#FFFFFF;">WIDTHS</th>';
						$tableoutput .= '<th class="drapediff" style="font-size:5px; text-align:center; color:#FFFFFF;">DIFF</th>';
					}

					if($toptreatments == 1){
						$tableoutput .= '<th class="topqty" style="font-size:5px; text-align:center; color:#FFFFFF;">QTY</th>';
						$tableoutput .= '<th class="toplf" style="font-size:5px; text-align:center; color:#FFFFFF;">LF</th>';
						$tableoutput .= '<th class="topdiff" style="font-size:5px; text-align:center; color:#FFFFFF;">DIFF</th>';
					}

					if($hardware == 1){
						$tableoutput .= '<th class="wthwqty" style="font-size:5px; text-align:center; color:#FFFFFF;">QTY</th>';
					}

					if($blinds == 1){
						$tableoutput .= '<th class="blindqty" style="font-size:5px; text-align:center; color:#FFFFFF;">QTY</th>';
					}
					$tableoutput .= '</tr>';
				}
			$tableoutput .= '</thead>
			<tbody>
			'.$output.'
			</tbody>
			<tfoot>
			'.$totalsoutput.'
			</tfoot>
			</table>';

			//echo $tableoutput;exit;

			$this->set('htmloutput',$tableoutput);

			$this->viewBuilder()->options([
                'pdfConfig' => [
                    'orientation' => 'landscape',
                    'filename' => 'Sherry Schedule '.$startDate.'-'.$endDate.'.pdf',
					'title' => 'Sherry Schedule '.$startDate.'-'.$endDate
                ]
            ]);

			
		

	}*/
	public function printschedule($startDate,$endDate,$path="schedule",$cubicles,$tracks,$bedspreads,$draperies,$toptreatments,$hardware,$blinds){

		$output='';
        if($path == 'pendingschedule') 
		        $GLOBALS['pdfheader']='<h1>Sh.Sched: Pending Schedule for <u>'.$startDate.'</u> through <u>'.$endDate.'</u></h1>';
		elseif($path == 'completedschedule') 
		         $GLOBALS['pdfheader']='<h1>Sh.Sched: Completed Schedule for <u>'.$startDate.'</u> through <u>'.$endDate.'</u></h1>';
			else 
        		$GLOBALS['pdfheader']='<h1>Sherry Schedule for <u>'.$startDate.'</u> through <u>'.$endDate.'</u></h1>';
		
		$startTS=strtotime($startDate,' 00:00:00');
		$endTS = strtotime($endDate.' 23:59:59');
		
		$overallTotals=array(
			'dollars' => 0,
			'cc' => 0,
			'cclf' => 0,
			'ccdiff' => 0,
			'bs' => 0,
			'bsdiff' => 0,
			'wt' => 0,
			'wtlf' => 0,
			'drape' => 0,
			'drape_widths' => 0,
			'wthw' => 0,
			'blinds' => 0,
			'trklf' => 0,
			'drapediff' => 0,
			'wtdiff' => 0,
			'total_items' => 0,
			'val' => 0,
			'vallf' => 0,
			'corn' => 0,
			'cornlf' => 0
		);
		
		$days = ceil((($endTS-$startTS)/86400));

		$itemRows=0;
		
		for($i = 1; $i <= $days; $i++){
			
			$thisDayTS=($startTS + (($i-1) * 86400));
			$thisDayDate = date('Y-m-d',$thisDayTS);
			
			$dayTotals=array(
				'dollars' => 0,
				'cc' => 0,
				'cclf' => 0,
				'ccdiff' => 0,
				'bs' => 0,
				'bsdiff' => 0,
				'wt' => 0,
				'wtlf' => 0,
				'drape' => 0,
				'drape_widths' => 0,
				'wthw' => 0,
				'blinds' => 0,
				'trklf' => 0,
				'drapediff' => 0,
				'wtdiff' => 0,
				'total_items' => 0,
				'val' => 0,
			    'vallf' => 0,
			    'corn' => 0,
			    'cornlf' => 0
			);
			
			$conditions=['date' => $thisDayDate];
			
			if($path == 'pendingschedule') 
				$conditions=['date' => $thisDayDate, 'batch_shipped_date' => 0, 'batch_completed_date'=>0];
			elseif($path == 'completedschedule')
			    $conditions=[ 'batch_completed_date >=' => strtotime($thisDayDate.' 00:00:00'),
				'batch_completed_date <' => strtotime($thisDayDate.' 23:59:59'),'batch_completed_date !='=>0];
			else 
				$conditions=['date' => $thisDayDate];
			//print_r($conditions);
			$thisRowOutput='';
			$thisDayOutput='';
			
			$scheduleEntries=$this->SherryCache->find('all',['conditions' => $conditions])->toArray();
			//print_r($scheduleEntries);exit;
			
			foreach($scheduleEntries as $scheduleItem){
				
				foreach($dayTotals as $key => $val){
					$dayTotals[$key] = ($dayTotals[$key] + floatval($scheduleItem[$key]));
				}
				
				foreach($overallTotals as $key => $val){
					$overallTotals[$key] = ($overallTotals[$key] + floatval($scheduleItem[$key]));
				}


				if($itemRows % 2 == 0){
					$bgcolor="#E5E5E5";
				}else{
					$bgcolor="#FFFFFF";
				}

			
				$batchNoteClass="";

				$thisRowOutput .= "<tr style=\"background-color:".$bgcolor.";\">";
				$thisRowOutput .= "<td style=\"font-size:5px; text-align:center;\">".$scheduleItem['batch_id']."</td>";
				$thisRowOutput .= "<td style=\"font-size:5px; text-align:center;\">".$scheduleItem['order_number']."</td>";
				$thisRowOutput .= "<td style=\"font-size:5px;\">".str_replace("'","\'",$scheduleItem['company_name'])."</td>";
				$thisRowOutput .= "<td style=\"font-size:5px;\">".str_replace("'","\'",$scheduleItem['customer_po_number'])."</td>";
				$thisRowOutput .= "<td style=\"font-size:5px;\">".$scheduleItem['order_date']."</td>";
				$thisRowOutput .= "<td style=\"font-size:5px;\">".str_replace("'","\'",$scheduleItem['project'])."</td>";
				$thisRowOutput .= "<td style=\"font-size:5px;\">".str_replace("'","\'",$scheduleItem['facility'])."</td>";
				$thisRowOutput .= "<td style=\"font-size:5px; text-align:center;\">";
				if($scheduleItem['order_ship_date'] > 10000){
					$thisRowOutput .= date('n/j/Y',$scheduleItem['order_ship_date']);
				}else{
					$thisRowOutput .= '---';
				}
				$thisRowOutput .= "</td>";
				$thisRowOutput .= "<td style=\"font-size:5px; text-align:center;\">\$".number_format($scheduleItem['dollars'],2,'.',',')."</td>";

				$numCols=7;

				if($cubicles == 1){
					$thisRowOutput .= "<td style=\"font-size:5px; text-align:center;\">".($scheduleItem['cc'] > 0 ? $scheduleItem['cc'] : '')."</td>";
					$thisRowOutput .= "<td style=\"font-size:5px; text-align:center;\">".($scheduleItem['cclf'] > 0 ? $scheduleItem['cclf'] : '')."</td>";
					//$thisRowOutput .= "<td style=\"font-size:5px; text-align:center;\">".(ceil(floatval($scheduleItem['ccdiff'])) > 0 ? ceil(floatval($scheduleItem['ccdiff'])) : '')."</td>";

					$numCols=($numCols + 2);
				}

				if($tracks == 1){
					$thisRowOutput .= "<td style=\"font-size:5px; text-align:center;\">".($scheduleItem['trklf'] > 0 ? $scheduleItem['trklf'] : '')."</td>";
					$numCols=($numCols + 1);
				}

				if($bedspreads == 1){
					$thisRowOutput .= "<td style=\"font-size:5px; text-align:center;\">".($scheduleItem['bs'] > 0 ? $scheduleItem['bs'] : '')."</td>";
					//$thisRowOutput .= "<td style=\"font-size:5px; text-align:center;\">&nbsp;</td>";
					$numCols=($numCols + 1);
				}

				if($draperies == 1){
					$thisRowOutput .= "<td style=\"font-size:5px; text-align:center;\">".($scheduleItem['drape'] > 0 ? $scheduleItem['drape'] : '')."</td>";
					$thisRowOutput .= "<td style=\"font-size:5px; text-align:center;\">".($scheduleItem['drape_widths'] > 0 ? $scheduleItem['drape_widths'] : '')."</td>";
					//$thisRowOutput .= "<td style=\"font-size:5px; text-align:center;\">&nbsp;</td>";
					$numCols=($numCols + 2);
				}

				if($toptreatments == 1){
					$thisRowOutput .= "<td style=\"font-size:5px; text-align:center;\">".($scheduleItem['val'] > 0 ? $scheduleItem['val'] : '')."</td>";
					$thisRowOutput .= "<td style=\"font-size:5px; text-align:center;\">".($scheduleItem['vallf'] > 0 ? $scheduleItem['vallf'] : '')."</td>";
					//$thisRowOutput .= "<td style=\"font-size:5px; text-align:center;\">&nbsp;</td>";
					$numCols=($numCols + 2);
				}
				if($toptreatments == 1){
					$thisRowOutput .= "<td style=\"font-size:5px; text-align:center;\">".($scheduleItem['corn'] > 0 ? $scheduleItem['corn'] : '')."</td>";
					$thisRowOutput .= "<td style=\"font-size:5px; text-align:center;\">".($scheduleItem['cornlf'] > 0 ? $scheduleItem['cornlf'] : '')."</td>";
					//$thisRowOutput .= "<td style=\"font-size:5px; text-align:center;\">&nbsp;</td>";
					$numCols=($numCols + 2);
				}

				if($hardware == 1){
					$thisRowOutput .= "<td style=\"font-size:5px; text-align:center;\">".($scheduleItem['wthw'] > 0 ? $scheduleItem['wthw'] : '')."</td>";
					$numCols=($numCols + 1);
				}

				if($blinds == 1){
					$thisRowOutput .= "<td style=\"font-size:5px; text-align:center;\">".($scheduleItem['blinds'] > 0 ? $scheduleItem['blinds'] : '')."</td>";
					$numCols=($numCols + 1);
				}

				$thisRowOutput .= "</tr>";

				//check for any NOTES on this batch item
				$batchNotes=$this->SherryBatchNotes->find('all',['conditions'=>['batch_id' => $scheduleItem['batch_id']]])->toArray();
				foreach($batchNotes as $batchNote){
					$thisRowOutput .= "<tr style=\"background-color:".$bgcolor.";\"><td colspan=\"2\" style=\"font-size:7px; text-align:right;\"><b>".strtoupper($batchNote['note_type'])." NOTE:</b></td><td colspan=\"".$numCols."\" style=\"font-size:7px;\">".$batchNote['message']."</td></tr>";
				}
				
				$itemRows++;
			}

			$thisDayOutput = "<tr style=\"background-color:#444444;\">";
			$thisDayOutput .= "<td colspan=\"8\" style=\"font-size:5px; text-align:left; font-weight:bold; color:#FFFFFF;\">".date("l, F jS, Y",$thisDayTS)."</td>";
			$thisDayOutput .= "<td style=\"font-size:5px; text-align:center; color:#FFFFFF; font-weight:bold;\">\$".number_format($dayTotals['dollars'],2,'.',',')."</td>";

			if($cubicles == 1){
				$thisDayOutput .= "<td style=\"font-size:5px; color:#FFFFFF; text-align:center; font-weight:bold;\">".($dayTotals['cc'] > 0 ? $dayTotals['cc'] : '')."</td>";
				$thisDayOutput .= "<td style=\"font-size:5px; color:#FFFFFF; text-align:center; font-weight:bold;\">".($dayTotals['cclf'] > 0 ? $dayTotals['cclf'] : '')."</td>";
				//$thisDayOutput .= "<td style=\"font-size:5px; color:#FFFFFF; text-align:center; font-weight:bold;\">".(ceil(floatval($dayTotals['ccdiff'])) > 0 ? ceil(floatval($dayTotals['ccdiff'])) : '')."</td>";
			}

			if($tracks == 1){
				$thisDayOutput .= "<td style=\"font-size:5px; color:#FFFFFF; text-align:center; font-weight:bold;\">".($dayTotals['trklf'] > 0 ? $dayTotals['trklf'] : '')."</td>";
			}

			if($bedspreads == 1){
				$thisDayOutput .= "<td style=\"font-size:5px; color:#FFFFFF; text-align:center; font-weight:bold;\">".($dayTotals['bs'] > 0 ? $dayTotals['bs'] : '')."</td>";
				//$thisDayOutput .= "<td style=\"font-size:5px; color:#FFFFFF; text-align:center; font-weight:bold;\">&nbsp;</td>";
			}

			if($draperies == 1){
				$thisDayOutput .= "<td style=\"font-size:5px; color:#FFFFFF; text-align:center; font-weight:bold;\">".($dayTotals['drape'] > 0 ? $dayTotals['drape'] : '')."</td>";
				$thisDayOutput .= "<td style=\"font-size:5px; color:#FFFFFF; text-align:center; font-weight:bold;\">".($dayTotals['drape_widths'] > 0 ? $dayTotals['drape_widths'] : '')."</td>";
				//$thisDayOutput .= "<td style=\"font-size:5px; color:#FFFFFF; text-align:center; font-weight:bold;\">&nbsp;</td>";
			}

			if($toptreatments == 1){
				$thisDayOutput .= "<td style=\"font-size:5px; color:#FFFFFF; text-align:center; font-weight:bold;\">".($dayTotals['val'] > 0 ? $dayTotals['val'] : '')."</td>";
				$thisDayOutput .= "<td style=\"font-size:5px; color:#FFFFFF; text-align:center; font-weight:bold;\">".($dayTotals['vallf'] > 0 ? $dayTotals['vallf'] : '')."</td>";
				//$thisDayOutput .= "<td style=\"font-size:5px; color:#FFFFFF; text-align:center; font-weight:bold;\">&nbsp;</td>";
			}

            if($toptreatments == 1){
				$thisDayOutput .= "<td style=\"font-size:5px; color:#FFFFFF; text-align:center; font-weight:bold;\">".($dayTotals['corn'] > 0 ? $dayTotals['corn'] : '')."</td>";
				$thisDayOutput .= "<td style=\"font-size:5px; color:#FFFFFF; text-align:center; font-weight:bold;\">".($dayTotals['cornlf'] > 0 ? $dayTotals['cornlf'] : '')."</td>";
				//$thisDayOutput .= "<td style=\"font-size:5px; color:#FFFFFF; text-align:center; font-weight:bold;\">&nbsp;</td>";
			}
			if($hardware == 1){
				$thisDayOutput .= "<td style=\"font-size:5px; color:#FFFFFF; text-align:center; font-weight:bold;\">".($dayTotals['wthw'] > 0 ? $dayTotals['wthw'] : '')."</td>";
			}

			if($blinds == 1){
				$thisDayOutput .= "<td style=\"font-size:5px; color:#FFFFFF; text-align:center; font-weight:bold;\">".($dayTotals['blinds'] > 0 ? $dayTotals['blinds'] : '')."</td>";
			}

			$thisDayOutput .= "</tr>";
			
			$output .= $thisDayOutput.$thisRowOutput;
		}
		
			
		$totalsoutput = "<tr style=\"background-color:#000000;\">";
		$totalsoutput .= "<td colspan=\"8\">&nbsp;</td>";
		$totalsoutput .= "<td style=\"font-size:5px; color:#FFFFFF; text-align:center;\">\$".number_format($overallTotals['dollars'],2,'.',',')."</td>";

		if($cubicles == 1){
			$totalsoutput .= "<td style=\"font-size:5px; color:#FFFFFF; text-align:center;\">".$overallTotals['cc']."</td>";
			$totalsoutput .= "<td style=\"font-size:5px; color:#FFFFFF; text-align:center;\">".$overallTotals['cclf']."</td>";
			//$totalsoutput .= "<td style=\"font-size:5px; color:#FFFFFF; text-align:center;\">".ceil(floatval($overallTotals['ccdiff']))."</td>";
		}

		if($tracks == 1){
			$totalsoutput .= "<td style=\"font-size:5px; color:#FFFFFF; text-align:center;\">".$overallTotals['trklf']."</td>";
		}

		if($bedspreads == 1){
			$totalsoutput .= "<td style=\"font-size:5px; color:#FFFFFF; text-align:center;\">".$overallTotals['bs']."</td>";
			//$totalsoutput .= "<td style=\"font-size:5px; color:#FFFFFF; text-align:center;\">&nbsp;</td>";
		}

		if($draperies == 1){
			$totalsoutput .= "<td style=\"font-size:5px; color:#FFFFFF; text-align:center;\">".$overallTotals['drape']."</td>";
			$totalsoutput .= "<td style=\"font-size:5px; color:#FFFFFF; text-align:center;\">".$overallTotals['drape_widths']."</td>";
		//	$totalsoutput .= "<td style=\"font-size:5px; color:#FFFFFF; text-align:center;\">&nbsp;</td>";
		}

		if($toptreatments == 1){
			$totalsoutput .= "<td style=\"font-size:5px; color:#FFFFFF; text-align:center;\">".$overallTotals['val']."</td>";
			$totalsoutput .= "<td style=\"font-size:5px; color:#FFFFFF; text-align:center;\">".$overallTotals['vallf']."</td>";
		//	$totalsoutput .= "<td style=\"font-size:5px; color:#FFFFFF; text-align:center;\">&nbsp;</td>";
		}
		if($toptreatments == 1){
			$totalsoutput .= "<td style=\"font-size:5px; color:#FFFFFF; text-align:center;\">".$overallTotals['corn']."</td>";
			$totalsoutput .= "<td style=\"font-size:5px; color:#FFFFFF; text-align:center;\">".$overallTotals['cornlf']."</td>";
		//	$totalsoutput .= "<td style=\"font-size:5px; color:#FFFFFF; text-align:center;\">&nbsp;</td>";
		}

		if($hardware == 1){
			$totalsoutput .= "<td style=\"font-size:5px; color:#FFFFFF; text-align:center;\">".$overallTotals['wthw']."</td>";
		}

		if($blinds == 1){
			$totalsoutput .= "<td style=\"font-size:5px; color:#FFFFFF; text-align:center;\">".$overallTotals['blinds']."</td>";
		}

		$totalsoutput .= "</tr>";
	
		
		$tableoutput='<table width="94%" cellpadding="2" cellspacing="0" border="1" bordercolor="#000000">
			<thead>
				<tr style="background-color:#000000;">';

					if($cubicles==0 && $tracks==0 && $bedspreads == 0 && $draperies == 0 && $toptreatments == 0 && $hardware == 0 && $blinds == 0){
						$tableoutput .= '<th class="wonumber" style="font-size:5px; text-align:center; color:#FFFFFF;">BATCH#</th>
						<th class="wonumber" style="font-size:5px; text-align:center; color:#FFFFFF;">WO#</th>
						<th class="customer" style="font-size:5px; text-align:center; color:#FFFFFF;">Customer</th>
						<th class="ponumber" style="font-size:5px; text-align:center; color:#FFFFFF;">PO#</th>
						<th class="wodate" style="font-size:5px; text-align:center; color:#FFFFFF;">Date</th>
						<th class="project" style="font-size:5px; text-align:center; color:#FFFFFF;">Project</th>
						<th class="facility" style="font-size:5px; text-align:center; color:#FFFFFF;">Facility</th>
						<th class="woshipdate" style="font-size:5px; text-align:center; color:#FFFFFF;">WO Ship Date</th>
						<th class="totals" style="font-size:5px; text-align:center; color:#FFFFFF;">Totals</th>
						</tr>';
					}else{
						$tableoutput .= '<th class="wonumber" rowspan="2" style="font-size:5px; text-align:center; color:#FFFFFF;">BATCH#</th>
						<th class="wonumber" rowspan="2" style="font-size:5px; text-align:center; color:#FFFFFF;">WO#</th>
						<th class="customer" rowspan="2" style="font-size:5px; text-align:center; color:#FFFFFF;">Customer</th>
						<th class="ponumber" rowspan="2" style="font-size:5px; text-align:center; color:#FFFFFF;">PO#</th>
						<th class="wodate" rowspan="2" style="font-size:5px; text-align:center; color:#FFFFFF;">Date</th>
						<th class="project" rowspan="2" style="font-size:5px; text-align:center; color:#FFFFFF;">Project</th>
						<th class="facility" rowspan="2" style="font-size:5px; text-align:center; color:#FFFFFF;">Facility</th>
						<th class="woshipdate" rowspan="2" style="font-size:5px; text-align:center; color:#FFFFFF;">WO Ship Date</th>
						<th class="totals" rowspan="2" style="font-size:5px; text-align:center; color:#FFFFFF;">Totals</th>';
					
						if($cubicles == 1){
							$tableoutput .= '<th class="cubicles" colspan="2" style="font-size:5px; text-align:center; color:#FFFFFF;">CC</th>';
						}

						if($tracks == 1){
							$tableoutput .= '<th class="trk" style="font-size:5px; text-align:center; color:#FFFFFF;">TRK</th>';
						}
						
						if($bedspreads == 1){
							$tableoutput .= '<th class="bedspreads" colspan="1" style="font-size:5px; text-align:center; color:#FFFFFF;">BS</th>';
						}

						if($draperies == 1){
							$tableoutput .= '<th class="draperies" colspan="2" style="font-size:5px; text-align:center; color:#FFFFFF;">DRAPERIES</th>';
						}

						if($toptreatments == 1){
							$tableoutput .= '<th class="toptreatments" colspan="2" style="font-size:5px; text-align:center; color:#FFFFFF;">Valances</th>';
						}
						if($toptreatments == 1){
							$tableoutput .= '<th class="toptreatments" colspan="2" style="font-size:5px; text-align:center; color:#FFFFFF;">Cornices</th>';
						}
						
						if($hardware == 1){
							$tableoutput .= '<th class="wthw" style="font-size:5px; text-align:center; color:#FFFFFF;">WT HW</th>';
						}

						if($blinds == 1){
							$tableoutput .= '<th class="blinds" style="font-size:5px; text-align:center; color:#FFFFFF;">B&amp;S</th>';
						}

					$tableoutput .= '</tr>
					<tr style="background-color:#000000;">';
					if($cubicles == 1){
						$tableoutput .= '<th class="ccqty" style="font-size:5px; text-align:center; color:#FFFFFF;">QTY</th>';
						$tableoutput .= '<th class="cclf" style="font-size:5px; text-align:center; color:#FFFFFF;">LF</th>';
						//$tableoutput .= '<th class="ccdiff" style="font-size:5px; text-align:center; color:#FFFFFF;">DIFF</th>';
					}

					if($tracks == 1){
						$tableoutput .= '<th class="trklf" style="font-size:5px; text-align:center; color:#FFFFFF;">LF</th>';
					}

					if($bedspreads == 1){
						$tableoutput .= '<th class="bsqty" style="font-size:5px; text-align:center; color:#FFFFFF;">QTY</th>';
						//$tableoutput .= '<th class="bsdiff" style="font-size:5px; text-align:center; color:#FFFFFF;">DIFF</th>';
					}

					if($draperies == 1){
						$tableoutput .= '<th class="drapeqty" style="font-size:5px; text-align:center; color:#FFFFFF;">QTY</th>';
						$tableoutput .= '<th class="drapewidths" style="font-size:5px; text-align:center; color:#FFFFFF;">WIDTHS</th>';
					//	$tableoutput .= '<th class="drapediff" style="font-size:5px; text-align:center; color:#FFFFFF;">DIFF</th>';
					}

					if($toptreatments == 1){
						$tableoutput .= '<th class="topqty" style="font-size:5px; text-align:center; color:#FFFFFF;">QTY</th>';
						$tableoutput .= '<th class="toplf" style="font-size:5px; text-align:center; color:#FFFFFF;">LF</th>';
					//	$tableoutput .= '<th class="topdiff" style="font-size:5px; text-align:center; color:#FFFFFF;">DIFF</th>';
					}
					if($toptreatments == 1){
						$tableoutput .= '<th class="topqty" style="font-size:5px; text-align:center; color:#FFFFFF;">QTY</th>';
						$tableoutput .= '<th class="toplf" style="font-size:5px; text-align:center; color:#FFFFFF;">LF</th>';
					//	$tableoutput .= '<th class="topdiff" style="font-size:5px; text-align:center; color:#FFFFFF;">DIFF</th>';
					}

					if($hardware == 1){
						$tableoutput .= '<th class="wthwqty" style="font-size:5px; text-align:center; color:#FFFFFF;">QTY</th>';
					}

					if($blinds == 1){
						$tableoutput .= '<th class="blindqty" style="font-size:5px; text-align:center; color:#FFFFFF;">QTY</th>';
					}
					$tableoutput .= '</tr>';
				}
			$tableoutput .= '</thead>
			<tbody>
			'.$output.'
			</tbody>
			<tfoot>
			'.$totalsoutput.'
			</tfoot>
			</table>';

			//echo $tableoutput;exit;

			$this->set('htmloutput',$tableoutput);

			$this->viewBuilder()->options([
                'pdfConfig' => [
                    'orientation' => 'landscape',
                    'filename' => 'Sherry Schedule '.$startDate.'-'.$endDate.'.pdf',
					'title' => 'Sherry Schedule '.$startDate.'-'.$endDate
                ]
            ]);

			
		

	}


	public function ordercsv($orderID){
		$this->autoRender=false;
		$thisOrder=$this->Orders->get($orderID)->toArray();
		$thisCustomer=$this->Customers->get($thisOrder['customer_id'])->toArray();

		if($thisOrder['contact_id'] == 0){
			$thisContact=array('first_name'=>'','last_name'=>'');
		}else{
			$thisContact=$this->CustomerContacts->get($thisOrder['contact_id'])->toArray();
		}

		$list = array (
			0 => array(
				'Order Number',
				'PO Number',
				'Order Date',
				'Due Date',
				'Customer Name',
				'Contact',
				'Billing Address',
				'Billing City',
				'Billing State',
				'Billing Zipcode',
				'Quantity',
				'Item',
				'Description',
				'Price Each',
				'Extended Price',
				'Shipping Address Line 1',
				'Shipping Address Line 2',
				'Shipping City',
				'Shipping State',
				'Shipping Zipcode'
			)
		);
		

		$orderItems=$this->OrderItems->find('all',['conditions' => ['order_id' => $orderID]])->toArray();

		$quoteItems=$this->QuoteLineItems->find('all',['conditions' => ['quote_id' => $thisOrder['quote_id']], 'order' => ['sortorder'=>'asc']])->toArray();

		foreach($quoteItems as $item){
			$thisitemmeta=array();
			
			$allmeta=$this->QuoteLineItemMeta->find('all',['conditions'=>['quote_item_id' => $item['id']]])->toArray();
			foreach($allmeta as $metarow){
				$thisitemmeta[$metarow['meta_key']]=$metarow['meta_value'];
			}


			if($fabricAlias=$this->getFabricAlias($thisitemmeta['fabricid'],$thisOrder['customer_id'])){
				$fabricName=$fabricAlias['fabric_name'];
				$fabricColor=$fabricAlias['color'];
			}else{
				$thisFabric=$this->Fabrics->get($thisitemmeta['fabricid'])->toArray();
				$fabricName=$thisFabric['fabric_name'];
				$fabricColor=$thisFabric['color'];
			}

			switch($item['product_type']){
				case "bedspreads":
					$thisitem='Bedspread';
					$thisdescription='';
				break;
				case "calculator":
					switch($item['calculator_used']){
						case "bedspread":
						case "bedspread-manual":
							$thisitem="Bedspread";
							$thisdescription=$fabricName." ".$fabricColor." ".$thisitemmeta['width']."x".$thisitemmeta['length'];
						break;
						case "box-pleated":
							$thisitem=$thisitemmeta['valance-type']." Valance";
							$thisdescription=$fabricName." ".$fabricColor." ".$thisitemmeta['width']."x".$thisitemmeta['length'];
						break;
						case "cubicle-curtain":
							$thisitem="Cubicle Curtain";
							$thisdescription=$fabricName." ".$fabricColor." ".$thisitemmeta['width']."x".$thisitemmeta['length'];
						break;
						case "pinch-pleated":
					    /* PPSASCRUM-56: start */
						case 'new-pinch-pleated':
						/* PPSASCRUM-56: end */
							$thisitem="Pinch Pleated Drapery";
							$thisdescription=$fabricName." ".$fabricColor." ".$thisitemmeta['width']."x".$thisitemmeta['length'];
						break;
						case "straight-cornice":
							$thisitem=$thisitemmeta['cornice-type']." Cornice";
							$thisdescription=$fabricName." ".$fabricColor." ".$thisitemmeta['width']."x".$thisitemmeta['length'];
						break;
					}
				break;
				case "cubicle_curtains":
					$thisitem="Cubicle Curtain";
					$thisdescription=$fabricName." ".$fabricColor." ".$thisitemmeta['width']."x".$thisitemmeta['length'];
				break;
				case "custom":
					$thisitem=$item['title'];
					$thisdescription=$item['description'];
				break;
				case "window_treatments":
					$thisitem=$thisitemmeta['wttype'];
					$thisdescription=$fabricName." ".$fabricColor." ".$thisitemmeta['width']."x".$thisitemmeta['length'];;
				break;
				case "track_systems":
					$thisitem='TRACK';
					$thisdescription='';
				break;
				case "services":
					$thisitem=$item['title'];
					$thisdescription='';
				break;
			}

			$list[]=array(
				$thisOrder['order_number'],
				$thisOrder['po_number'],
				date('m/d/Y',$thisOrder['created']),
				date('m/d/Y',$thisOrder['due']),
				$thisCustomer['company_name'],
				$thisContact['first_name'].' '.$thisContact['last_name'],
				$thisCustomer['billing_address'],
				$thisCustomer['billing_address_city'],
				$thisCustomer['billing_address_state'],
				$thisCustomer['billing_address_zipcode'],
				$item['qty'],
				$thisitem,
				$thisdescription,
				$item['pmi_adjusted'],
				$item['extended_price'],
				$thisOrder['shipping_address_1'],
				$thisOrder['shipping_address_2'],
				$thisOrder['shipping_city'],
				$thisOrder['shipping_state'],
				$thisOrder['shipping_zipcode']
			);

		}
		$orderNum=$thisOrder['order_number'];

		$fp = fopen($_SERVER['DOCUMENT_ROOT'].'/tmp/Order '.$orderNum.'.csv', 'w');

		foreach ($list as $fields) {
		    fputcsv($fp, $fields);
		}

		fclose($fp);

		
		header('Content-Description: File Transfer');
		header('Content-Type: application/force-download');
		header('Content-Disposition: attachment; filename="Order '.$orderNum.'.csv"');
		readfile($_SERVER['DOCUMENT_ROOT'].'/tmp/Order '.$orderNum.'.csv');
		unlink($_SERVER['DOCUMENT_ROOT'].'/tmp/Order '.$orderNum.'.csv');
	}


	public function schedulesummary($dateStart=false,$dateEnd=false){
		if(!$dateStart || !$dateEnd){
			//show date range form
			$this->autoRender=false;
			$this->render('schedulesummaryform');
		}else{

			$this->viewBuilder()->options([

                'pdfConfig' => [
                	'pageSize' => 'LETTER',
                    'orientation' => 'landscape',
                    'filename' => 'Schedule Summary ' . $dateStart.' through '.$dateEnd.'.pdf',
					'title' => 'Schedule Summary ' . $dateStart.' through '.$dateEnd
                ]

            ]);

			$GLOBALS['pdfmargins']='custom';

			$GLOBALS['pdfmarginscustom']=array('left'=>10,'top'=>10,'right'=>10,'header'=>10);

			$GLOBALS['pdfheader']="<h3>Sherry Schedule <u>".$dateStart." through ".$dateEnd."</u><br>Summary</h3>";

			//render it (PDF)
			$this->set('dateStart',$dateStart);
			$this->set('dateEnd',$dateEnd);

			$output='';
		
			$startTS=strtotime($dateStart,' 00:00:00');
			$endTS = strtotime($dateEnd.' 23:59:59');
			
			$overallTotals=array(
				'dollars' => 0,
				'cc' => 0,
				'cclf' => 0,
				'ccdiff' => 0,
				'bs' => 0,
				'bsdiff' => 0,
				//'wt' => 0,
				//'wtlf' => 0,
				'val' => 0,
				'vallf' => 0,
				'corn' => 0,
				'cornlf' => 0,
				'drape' => 0,
				'drape_widths' => 0,
				'wthw' => 0,
				'blinds' => 0,
				'trklf' => 0,
				'drapediff' => 0,
				'wtdiff' => 0,
				'total_items' => 0
			);
			
			$days = ceil((($endTS-$startTS)/86400));
			
			for($i = 1; $i <= $days; $i++){
				
				$thisDayTS=($startTS + (($i-1) * 86400));
				$thisDayDate = date('Y-m-d',$thisDayTS);
				
				$dayTotals=array(
					'dollars' => 0,
					'cc' => 0,
					'cclf' => 0,
					'ccdiff' => 0,
					'bs' => 0,
					'bsdiff' => 0,
					//'wt' => 0,
					//'wtlf' => 0,
					'val' => 0,
					'vallf' => 0,
					'corn' => 0,
					'cornlf' => 0,
					'drape' => 0,
					'drape_widths' => 0,
					'wthw' => 0,
					'blinds' => 0,
					'trklf' => 0,
					'drapediff' => 0,
					'wtdiff' => 0,
					'total_items' => 0
				);
				
				$conditions=['date' => $thisDayDate];
				//print_r($conditions);
				$thisRowOutput='';
				$thisDayOutput='';
				
				$scheduleEntries=$this->SherryCache->find('all',['conditions' => $conditions])->toArray();
				//print_r($scheduleEntries);exit;
				
				foreach($scheduleEntries as $scheduleItem){
					
					foreach($dayTotals as $key => $val){
						$dayTotals[$key] = ($dayTotals[$key] + floatval($scheduleItem[$key]));
					}
					
					foreach($overallTotals as $key => $val){
						$overallTotals[$key] = ($overallTotals[$key] + floatval($scheduleItem[$key]));
					}
				
					
				}
				
				
				$thisDayOutput = "<tr class=\"daterow\">";
				$thisDayOutput .= "<td style=\"font-size:7px; text-align:left; color:#000;\">".date("n/j/Y",$thisDayTS)."</td>";
				$thisDayOutput .= "<td style=\"font-size:7px; text-align:center; color:#000;\">\$".number_format($dayTotals['dollars'],2,'.',',')."</td>";
				$thisDayOutput .= "<td style=\"font-size:7px; text-align:center; color:#000;\">".($dayTotals['cc'] > 0 ? $dayTotals['cc'] : '')."</td>";
				$thisDayOutput .= "<td style=\"font-size:7px; text-align:center; color:#000;\">".($dayTotals['cclf'] > 0 ? $dayTotals['cclf'] : '')."</td>";
				$thisDayOutput .= "<td style=\"font-size:7px; text-align:center; color:#000;\">".($dayTotals['trklf'] > 0 ? $dayTotals['trklf'] : '')."</td>";
				$thisDayOutput .= "<td style=\"font-size:7px; text-align:center; color:#000;\">".($dayTotals['bs'] > 0 ? $dayTotals['bs'] : '')."</td>";
				$thisDayOutput .= "<td style=\"font-size:7px; text-align:center; color:#000;\">".($dayTotals['drape'] > 0 ? $dayTotals['drape'] : '')."</td>";
				$thisDayOutput .= "<td style=\"font-size:7px; text-align:center; color:#000;\">".($dayTotals['drape_widths'] > 0 ? $dayTotals['drape_widths'] : '')."</td>";
				$thisDayOutput .= "<td style=\"font-size:7px; text-align:center; color:#000;\">".($dayTotals['val'] > 0 ? $dayTotals['val'] : '')."</td>";
				$thisDayOutput .= "<td style=\"font-size:7px; text-align:center; color:#000;\">".($dayTotals['vallf'] > 0 ? $dayTotals['vallf'] : '')."</td>";
				$thisDayOutput .= "<td style=\"font-size:7px; text-align:center; color:#000;\">".($dayTotals['corn'] > 0 ? $dayTotals['corn'] : '')."</td>";
				$thisDayOutput .= "<td style=\"font-size:7px; text-align:center; color:#000;\">".($dayTotals['cornlf'] > 0 ? $dayTotals['cornlf'] : '')."</td>";
				$thisDayOutput .= "<td style=\"font-size:7px; text-align:center; color:#000;\">".($dayTotals['wthw'] > 0 ? $dayTotals['wthw'] : '')."</td>";
				$thisDayOutput .= "<td style=\"font-size:7px; text-align:center; color:#000;\">".($dayTotals['blinds'] > 0 ? $dayTotals['blinds'] : '')."</td>";
				$thisDayOutput .= "</tr>";
				
				$output .= $thisDayOutput.$thisRowOutput;
			}
			
				
			$totalsoutput = "<tr bgcolor=\"#555555\">";
			$totalsoutput .= "<td style=\"font-size:7px; color:#FFF; font-weight:bold;\">TOTALS:</td>";
			$totalsoutput .= "<td style=\"font-size:7px; text-align:center; color:#FFF; font-weight:bold;\">\$".number_format($overallTotals['dollars'],2,'.',',')."</td>";
			$totalsoutput .= "<td style=\"font-size:7px; text-align:center; color:#FFF; font-weight:bold;\">".$overallTotals['cc']."</td>";
			$totalsoutput .= "<td style=\"font-size:7px; text-align:center; color:#FFF; font-weight:bold;\">".$overallTotals['cclf']."</td>";
			$totalsoutput .= "<td style=\"font-size:7px; text-align:center; color:#FFF; font-weight:bold;\">".$overallTotals['trklf']."</td>";
			$totalsoutput .= "<td style=\"font-size:7px; text-align:center; color:#FFF; font-weight:bold;\">".$overallTotals['bs']."</td>";
			$totalsoutput .= "<td style=\"font-size:7px; text-align:center; color:#FFF; font-weight:bold;\">".$overallTotals['drape']."</td>";
			$totalsoutput .= "<td style=\"font-size:7px; text-align:center; color:#FFF; font-weight:bold;\">".$overallTotals['drape_widths']."</td>";
			$totalsoutput .= "<td style=\"font-size:7px; text-align:center; color:#FFF; font-weight:bold;\">".$overallTotals['val']."</td>";
			$totalsoutput .= "<td style=\"font-size:7px; text-align:center; color:#FFF; font-weight:bold;\">".$overallTotals['vallf']."</td>";
			$totalsoutput .= "<td style=\"font-size:7px; text-align:center; color:#FFF; font-weight:bold;\">".$overallTotals['corn']."</td>";
			$totalsoutput .= "<td style=\"font-size:7px; text-align:center; color:#FFF; font-weight:bold;\">".$overallTotals['cornlf']."</td>";
			$totalsoutput .= "<td style=\"font-size:7px; text-align:center; color:#FFF; font-weight:bold;\">".$overallTotals['wthw']."</td>";
			$totalsoutput .= "<td style=\"font-size:7px; text-align:center; color:#FFF; font-weight:bold;\">".$overallTotals['blinds']."</td>";
			$totalsoutput .= "</tr>";
	
		
		
			$this->set('rawoutput','<table id="sherrytable" cellpadding="3" cellspacing="0" border="1" bordercolor="#000000">
			<thead>
				<tr bgcolor="#555555">
					<th class="date" rowspan="2" style="font-size:7px; text-align:center; color:#FFF; font-weight:bold;">Date</th>
					<th class="dollars" rowspan="2" style="font-size:7px; text-align:center; color:#FFF; font-weight:bold;">Dollars</th>
					<th class="cubicles" colspan="2" style="font-size:7px; text-align:center; color:#FFF; font-weight:bold;">CC</th>
					<th class="trk" style="font-size:7px; text-align:center; color:#FFF; font-weight:bold;">TRK</th>
					<th class="bedspreads" style="font-size:7px; text-align:center; color:#FFF; font-weight:bold;">BS</th>
					<th class="draperies" colspan="2" style="font-size:7px; text-align:center; color:#FFF; font-weight:bold;">DRAPERIES</th>
					<th class="valances" colspan="2" style="font-size:7px; text-align:center; color:#FFF; font-weight:bold;">VALANCES</th>
					<th class="cornices" colspan="2" style="font-size:7px; text-align:center; color:#FFF; font-weight:bold;">CORNICES</th>
					<th class="wthw" style="font-size:7px; text-align:center; color:#FFF; font-weight:bold;">WT HW</th>
					<th class="blinds" style="font-size:7px; text-align:center; color:#FFF; font-weight:bold;">B&amp;S</th>
				</tr>
				<tr bgcolor="#555555">
					<th class="ccqty" style="font-size:7px; text-align:center; color:#FFF; font-weight:bold;">QTY</th>
					<th class="cclf" style="font-size:7px; text-align:center; color:#FFF; font-weight:bold;">LF</th>
					<th class="trklf" style="font-size:7px; text-align:center; color:#FFF; font-weight:bold;">LF</th>
					<th class="bsqty" style="font-size:7px; text-align:center; color:#FFF; font-weight:bold;">QTY</th>
					<th class="drapeqty" style="font-size:7px; text-align:center; color:#FFF; font-weight:bold;">QTY</th>
					<th class="drapewidths" style="font-size:7px; text-align:center; color:#FFF; font-weight:bold;">WIDTHS</th>
					<th class="valqty" style="font-size:7px; text-align:center; color:#FFF; font-weight:bold;">QTY</th>
					<th class="vallf" style="font-size:7px; text-align:center; color:#FFF; font-weight:bold;">LF</th>
					<th class="cornqty" style="font-size:7px; text-align:center; color:#FFF; font-weight:bold;">QTY</th>
					<th class="cornlf" style="font-size:7px; text-align:center; color:#FFF; font-weight:bold;">LF</th>
					<th class="wthwqty" style="font-size:7px; text-align:center; color:#FFF; font-weight:bold;">QTY</th>
					<th class="blindqty" style="font-size:7px; text-align:center; color:#FFF; font-weight:bold;">QTY</th>
				</tr>
			</thead>
			<tbody>
			'.$output.'
			</tbody>
			<tfoot>
				'.$totalsoutput.'
			</tfoot>
			</table>');
			

		}
	}



	public function sendtoquickbooks($orderID){

		$this->autoRender=false;
		$thisOrder=$this->Orders->get($orderID)->toArray();
		$thisQuote=$this->Quotes->get($thisOrder['quote_id'])->toArray();

		$thisCustomer=$this->Customers->Get($thisOrder['customer_id'])->toArray();

		if($thisOrder['contact_id'] >0){
			$thisContact=$this->CustomerContacts->get($thisOrder['contact_id'])->toArray();
		}else{
			$thisContact=array('first_name' => '', 'last_name' => '');
		}

		$thisQuoteLineItems=$this->QuoteLineItems->find('all',['conditions' => ['quote_id' => $thisQuote['id']],'order' => ['sortorder' => 'asc']])->toArray();

		$lineItemsArray=array();

		foreach($thisQuoteLineItems as $lineItemRow){

			if(floatval($lineItemRow['override_price']) > 0.00){
				$thisItemPrice=floatval($lineItemRow['override_price']);
			}else{
				$thisItemPrice=floatval($lineItemRow['pmi_adjusted']);
			}

			$lineItemsArray[]=array(
				"Item" => array(
					"Name" => $this->getQBItemCode($lineItemRow['id']),
					"ItemCost" => 0,
					"ItemPrice" => $thisItemPrice
				),
				"Rate" => $thisItemPrice,
				"Amount" => $lineItemRow['extended_price'],
				"Description" => $this->getQBItemDescription($lineItemRow['id']),
				"Quantity" => $lineItemRow['qty'],
				"IsTaxable" => false,
				//"Class" => $thisItemClass
			);

		}


		$data = array(
			"RefNumber" => $thisOrder['order_number'],
			"PONumber" => "",//$thisOrder['po_number'],
			"DueDate" => date('Y-m-d',$thisOrder['due']).'T'.date('H:i:s',$thisOrder['due']),
			"TxnDate" => date('Y-m-d',$thisOrder['created']).'T'.date('H:i:s',$thisOrder['created']),
			"DateShipped" => date('Y-m-d',$thisOrder['due']),
			"SubTotal" => $thisOrder['order_total'],
			"LineItems" => $lineItemsArray,
			/*"TermsRef" => array(
				"ID" => "",
				"FullName" => "Net 30",
			),*/
			"Customer" => array(
				"Name" => $thisCustomer['company_name'],
				"FullName" => $thisCustomer['company_name'],
				"Contact" => $thisContact['first_name'].' '.$thisContact['last_name'],
				"Phone" => $thisCustomer['phone'],
				"BillAddress" => array(
					"Addr1" => $thisCompany['billing_address'],
					"City" => $thisCompany['billing_address_city'],
					"State" => $thisCompany['billing_address_state'],
					"PostalCode" => $thisCompany['billing_address_zipcode'],
					"Country" => $thisCompany['billing_address_country']
				),
				"ShipAddress" => array(
					"Addr1" => $thisOrder['facility'],
					"Addr2" => $thisOrder['shipping_address_1'],
					"Addr3" => $thisOrder['shipping_address_2'],
					"City" => $thisOrder['shipping_city'],
					"State" => $thisOrder['shipping_state'],
					"PostalCode" => $thisOrder['shipping_zipcode']
				)
			)
		);

		$data_string = json_encode($data);

		$ch = curl_init('https://api.propelware.com/v1/endpoint/53ca6f5b097a415490fa346a8f2eb066/process/SalesOrder');
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array( 
			'Authorization: 4992FD913BF74CC5A8D89FEBA11B162F',        
		    'Content-Type: application/json')
		);

		$result = curl_exec($ch);
		$resultParse=json_decode($result,true);

		$activityLogsTable = TableRegistry::get('ActivityLogs');
		$newlog = $activityLogsTable->newEntity();
	
		$userid=$this->Auth->user('id');
	
		$newlog->ip_address=$_SERVER['REMOTE_ADDR'];
		$newlog->user_agent=$_SERVER['HTTP_USER_AGENT'];
		$newlog->time=time();
		$newlog->user_id=$userid;
		$newlog->location=$_SERVER['REQUEST_URI'];
		if($resultParse['Meta']['StatusCode'] == '0'){
			$newlog->action_label='Sent Order '.$thisOrder['order_number'].' to Quickbooks';
		}else{
			$newlog->action_label='Attempted to send Order '.$thisOrder['order_number'].' to Quickbooks -- ERROR OCCURRED: '.$resultParse['Meta']['Message'];
		}
		
		$newlog->data=$result;
		$newlog->session_id=$this->request->session()->id();
		
		$activityLogsTable->save($newlog);


		$newQbImportLogEntryTable=TableRegistry::get('QbImportLog');
		$newQbImportLogEntry=$newQbImportLogEntryTable->newEntity();
		$newQbImportLogEntry->order_id = $thisOrder['id'];
		$newQbImportLogEntry->order_number = $thisOrder['order_number'];
		$newQbImportLogEntry->import_time=time();
		$newQbImportLogEntry->user_id = $userid;

		if($resultParse['Meta']['StatusCode'] == '0'){
			//success
			$newQbImportLogEntry->result = 'success';
			$this->Flash->success('Successfully imported order '.$thisOrder['order_number'].' into Quickbooks');
		}else{
			$newQbImportLogEntry->result = 'error';
			$this->Flash->error('An error occurred while attempting to import order '.$thisOrder['order_number'].' into Quickbooks... '.$resultParse['Meta']['Message']);
		}

		$newQbImportLogEntry->raw_result=$result;

		$newQbImportLogEntryTable->save($newQbImportLogEntry);


		$this->redirect('/orders/');
	}
	
	
	public function woboxes($orderID){
	    $thisWO=$this->WorkOrders->get($orderID)->toArray();
	    $this->set('thisWO',$thisWO);

	    $warehouseLocations=$this->WarehouseLocations->find('all')->toArray();
        $this->set('warehouseLocations',$warehouseLocations);
        
        $lineItems=$this->WorkOrderLineItems->find('all',['conditions'=>['order_id'=>$thisWO['id']],'order'=>['sortorder'=>'asc']])->toArray();
        $this->set('lineItems',$lineItems);
        $allusers=$this->Users->find('all',['order'=>['last_name'=>'asc','first_name'=>'asc']])->toArray();
		$this->set('allusers',$allusers);
		
		$orderBoxes=array();
		$orderBoxesData=$this->SherryBatchBoxes->find('all',['conditions' => ['order_id' => $thisWO['id']]])->toArray();
		foreach($orderBoxesData as $orderBox){
		    $contents=$this->SherryBatchBoxContents->find('all',['conditions' => ['box_id' => $orderBox['id']]])->toArray();
		    $orderBoxes[$orderBox['id']]['boxdata']=$orderBox;
		    $orderBoxes[$orderBox['id']]['contents']=$contents;
		}
		$this->set('orderBoxes',$orderBoxes);
		
	}
	
	
	public function batchboxes($batchID){
		try {
	    $thisBatch=$this->SherryBatches->get($batchID)->toArray();
	    $this->set('thisBatch',$thisBatch);
	    $thisWO=$this->WorkOrders->get($thisBatch['work_order_id'])->toArray();
	    $this->set('thisWO',$thisWO);
	    
	    $warehouseLocations=$this->WarehouseLocations->find('all')->toArray();
        $this->set('warehouseLocations',$warehouseLocations);

	    $boxes=$this->SherryBatchBoxes->find('all',['conditions' => ['batch_id' => $thisBatch['id']],'order'=>['created'=>'asc']])->toArray();
	    $batchBoxes=array();
       
	    foreach($boxes as $box){
	        $boxContentCheck=$this->SherryBatchBoxContents->find('all',['conditions' => ['box_id' => $box['id']]])->toArray();
	        $thisItemCount=0;
	        $thisItemData=array();
	        foreach($boxContentCheck as $contentEntry){
	            $thisItemCount=($thisItemCount + intval($contentEntry['qty']));
	            $thisItemData[]=$contentEntry;
	        }
	        // print_r($boxContentCheck);
	       $batchBoxes[]=array(
	        'id'=>$box['id'],
	        'box_number' => $box['box_number'],
	        'batch_id' => $box['batch_id'],
	        'order_id' => $box['order_id'],
	        'warehouse_location_id' => $box['warehouse_location_id'],
	        'length' => $box['length'],
	        'width' => $box['width'],
	        'height' => $box['height'],
	        'weight' => $box['weight'],
	        'wo_facility' => $box['wo_facility'],
	        'status' => $box['status'],
	        'shipment_id' => $box['shipment_id'],
	        'created' => $box['created'],
	        'updated' => $box['updated'],
	        'user_id' => $box['user_id'],
	        'item_count'=>$thisItemCount,
	        'item_data' => $thisItemData,
	       );
	    }
	    
	    $this->set('batchBoxes',$batchBoxes);
	   
	    $lineItems=$this->WorkOrderLineItems->find('all',['conditions'=>['order_id'=>$thisWO['id']],'order'=>['sortorder'=>'asc']])->toArray();
        
        $this->set('lineItems',$lineItems);
	    

	   
	    $otherBoxesThisBatch=$this->SherryBatchBoxes->find('all',['conditions' => ['batch_id' => $batchID]])->toArray();
        $this->set('this_batch_existing_box_count',count($otherBoxesThisBatch));
	    
	    $lineItemsArray=array();
		foreach($lineItems as $lineitem){

			$lineItemsArray[$lineitem['id']]=array();
			$lineItemsArray[$lineitem['id']]['data']=$lineitem;
			$lineItemsArray[$lineitem['id']]['metadata']=array();
			$lineItemsArray[$lineitem['id']]['fabricdata']=array();
			$lineItemMetas=$this->WorkOrderLineItemMeta->find('all',['conditions'=>['worder_item_id'=>$lineitem['id']]])->toArray();
			foreach($lineItemMetas as $lineitemmeta){
				$lineItemsArray[$lineitem['id']]['metadata'][$lineitemmeta['meta_key']]=$lineitemmeta['meta_value'];	
				if($lineitemmeta['meta_key']=='fabricid' && intval($lineitemmeta['meta_value']) > 0){
					$thisFabric=$this->Fabrics->get($lineitemmeta['meta_value'])->toArray();
					$lineItemsArray[$lineitem['id']]['fabricdata']=$thisFabric;
				}
			}
			
			//find the matching order item id# for this quote item id#
			$orderItemID=0;

			$lookupOrderItem=$this->WorkOrderItems->find('all',['conditions'=>['quote_line_item_id'=>$lineitem['id']]])->toArray();

			foreach($lookupOrderItem as $orderitemrow){
				$orderItemID=$orderitemrow['id'];
			}
			$lineItemsArray[$lineitem['id']]['order_item_id']=$orderItemID;

			$thisBatchCount=0;
			$otherBatchesCount=0;
			
			//find all previously shipped items in this line
			$scheduledItemsLookup=$this->WorkOrderItemStatus->find('all',['conditions'=>['order_line_number'=>$lineitem['line_number'],'order_item_id'=>$orderItemID,'work_order_id'=>$thisWO['id'],'status' => 'Scheduled']]);
			foreach($scheduledItemsLookup as $scheduledItemRow){
				
				if($scheduledItemRow['sherry_batch_id'] != $batchID){
					$otherBatchesCount=($otherBatchesCount + floatval($scheduledItemRow['qty_involved']));
				}else{
					$thisBatchCount=($thisBatchCount + floatval($scheduledItemRow['qty_involved']));
				}

			}
			$lineItemsArray[$lineitem['id']]['this_batch']=$thisBatchCount;
			$lineItemsArray[$lineitem['id']]['other_batches'] = $otherBatchesCount;
			$lineItemsArray[$lineitem['id']]['remaining_unscheduled'] = ($lineitem['qty'] - $thisBatchCount - $otherBatchesCount);
			
			$thisBatchOtherBoxed=0;
			
			foreach($otherBoxesThisBatch as $box){
			    $boxContentCheck=$this->SherryBatchBoxContents->find('all',['conditions' => ['box_id' => $box['id'], 'line_number' => $lineitem['line_number'], 'quote_item_id'=>$lineitem['id']]])->toArray();
			    foreach($boxContentCheck as $boxContentRow){
			        $thisBatchOtherBoxed=($thisBatchOtherBoxed + $boxContentRow['qty']);
			    }
			}
			
			$lineItemsArray[$lineitem['id']]['this_batch_other_boxes'] = $thisBatchOtherBoxed;
		}
		
		
        
		$this->set('lineItems',$lineItemsArray);
	    
	    
	    
	    $allusers=$this->Users->find('all',['order'=>['last_name'=>'asc','first_name'=>'asc']])->toArray();
		$this->set('allusers',$allusers);
	} catch (RecordNotFoundException $e) { }
		
	}

    
    public function newbox($type,$id){
        
        if($this->request->data){
            
            switch($type){
                case 'wo':
                    
                break;
                case 'batch':
                    try {                                            
                    $thisBatch=$this->SherryBatches->get($id)->toArray();
					//$thisBatch=$this->SherryBatches->find('all',['conditions' => ['id' => $id]])->toArray();
                    $thisOrder=$this->WorkOrders->get($thisBatch['work_order_id'])->toArray();
				   //$thisOrder=$this->WorkOrders->find('all',['conditions' => ['id' => $thisBatch['work_order_id']]])->toArray();
                    //determine the next box number for this batch
                    $existingBoxesThisBatch=$this->SherryBatchBoxes->find('all',['conditions' => ['order_id' => $thisOrder['id']]])->toArray();
                    $highestBoxNum=0;
                    foreach($existingBoxesThisBatch as $existingBox){
                        $thisBoxNumber=intval(str_replace($thisOrder['order_number'].'-','',$existingBox['box_number']));
                        if($thisBoxNumber > $highestBoxNum){
                            $highestBoxNum=$thisBoxNumber;
                        }
                    }
                    
                    //$newboxnumber=$thisOrder['order_number'].'-'.(count($existingBoxesThisBatch)+1);
                    $newboxnumber=$thisOrder['order_number'].'-'.($highestBoxNum+1);
                    
                    $boxTable=TableRegistry::get('SherryBatchBoxes');
                    $newBox=$boxTable->newEntity();
                    $newBox->box_number=$newboxnumber;
                    $newBox->batch_id=$id;
                    $newBox->order_id=$thisBatch['work_order_id'];
                    
                    if($this->request->data['warehouse_location'] == ''){
                        $newBox->warehouse_location_id=0;
                    }else{
                        $newBox->warehouse_location_id = $this->request->data['warehouse_location'];
                    }
                    
                    $newBox->length = $this->request->data['length'];
                    $newBox->width = $this->request->data['width'];
                    $newBox->height = $this->request->data['height'];
                    $newBox->weight = $this->request->data['weight'];
                    $newBox->wo_facility = $thisOrder['facility'];
                    $newBox->status = 'Not Shipped';
                    $newBox->shipment_id=0;
                    $newBox->user_id=$this->Auth->user('id');
                    $newBox->created=time();
                    $newBox->updated=time();
                    if($boxTable->save($newBox)){
                        
                        //insert the line contents into this box from the form
                        
                        $boxContentTable=TableRegistry::get('SherryBatchBoxContents');
                        
                        $lineItems=$this->WorkOrderLineItems->find('all',['conditions'=>['quote_id'=>$thisOrder['quote_id']],'order'=>['sortorder'=>'asc']])->toArray();
        
            			foreach($lineItems as $lineitem){
            
                            if(isset($this->request->data["boxcontent_lineitem_".$lineitem['id']]) && intval($this->request->data["boxcontent_lineitem_".$lineitem['id']]) > 0){
                                
                                $newContentEntry=$boxContentTable->newEntity();
                                $newContentEntry->box_id=$newBox->id;
                                $newContentEntry->order_id=$thisBatch['work_order_id'];
                                $newContentEntry->quote_item_id = $lineitem['id'];
                                $newContentEntry->line_number=$lineitem['wo_line_number'];
                                $newContentEntry->room_number=$lineitem['room_number'];
                                $newContentEntry->qty=$this->request->data["boxcontent_lineitem_".$lineitem['id']];
                                $newContentEntry->created=time();
                                $newContentEntry->updated=time();
                                
                                $boxContentTable->save($newContentEntry);
                                
                            }
            				
            			}
                        

                        $this->logActivity($_SERVER['REQUEST_URI'],'Created new Box for sherry batch '.$id);
                        
                        $this->Flash->success('Created Box #'.$newboxnumber.' for Sherry Batch ID# '.$id);
                        //return $this->redirect('/orders/batchboxes/'.$id);
                        return $this->redirect('/orders/editbox/'.$id);
                    }
				} catch (RecordNotFoundException $e) { }

                break;
            }
            
        }else{
            $warehouseLocations=$this->WarehouseLocations->find('all')->toArray();
            $this->set('warehouseLocations',$warehouseLocations);
            
            switch($type){
                case 'wo':
                
                break;
                case 'batch':
					try{
                    $batchID=$id;
                    
                    $thisBatch=$this->SherryBatches->get($id)->toArray();
					//$thisBatch=$this->SherryBatches->find('all',['conditions'=>['id'=>$id]])->toArray();
			//	print_r($thisBatch);echo $id;
                    $thisOrder=$this->WorkOrders->get($thisBatch['work_order_id'])->toArray();
				//	$thisOrder=$this->WorkOrders->find('all',['conditions'=>['id'=>$thisBatch['work_order_id']]])->toArray();

                    $this->set('thisBatch',$thisBatch);
                    
                    $orderID=$thisOrder['id'];
                    
                    //load all the data for the form
        			//$thisCustomer=$this->Customers->get($thisOrder['customer_id'])->toArray();
					$thisCustomer=$this->Customers->find('all',['conditions'=>['id'=>$thisOrder['customer_id']]])->toArray();
        
        			$this->set('customerData',$thisCustomer);
        			
        
        			$lineItems=$this->WorkOrderLineItems->find('all',['conditions'=>['quote_id'=>$thisOrder['quote_id']],'order'=>['sortorder'=>'asc']])->toArray();

                    $otherBoxesThisBatch=$this->SherryBatchBoxes->find('all',['conditions' => ['batch_id' => $batchID]])->toArray();
                    $this->set('this_batch_existing_box_count',count($otherBoxesThisBatch));
        
        			$lineItemsArray=array();
        			foreach($lineItems as $lineitem){
        
        				$lineItemsArray[$lineitem['id']]=array();
        				$lineItemsArray[$lineitem['id']]['data']=$lineitem;
        				$lineItemsArray[$lineitem['id']]['metadata']=array();
        				$lineItemsArray[$lineitem['id']]['fabricdata']=array();
        				$lineItemMetas=$this->WorkOrderLineItemMeta->find('all',['conditions'=>['worder_item_id'=>$lineitem['id']]])->toArray();
        				foreach($lineItemMetas as $lineitemmeta){
        					$lineItemsArray[$lineitem['id']]['metadata'][$lineitemmeta['meta_key']]=$lineitemmeta['meta_value'];	
        					if($lineitemmeta['meta_key']=='fabricid' && intval($lineitemmeta['meta_value']) > 0){
        						$thisFabric=$this->Fabrics->get($lineitemmeta['meta_value'])->toArray();
        						$lineItemsArray[$lineitem['id']]['fabricdata']=$thisFabric;
        					}
        				}
        				
        				//find the matching order item id# for this quote item id#
        				$orderItemID=0;
        
        				$lookupOrderItem=$this->WorkOrderItems->find('all',['conditions'=>['quote_line_item_id'=>$lineitem['id']]])->toArray();
        
        				foreach($lookupOrderItem as $orderitemrow){
        					$orderItemID=$orderitemrow['id'];
        				}
        				$lineItemsArray[$lineitem['id']]['order_item_id']=$orderItemID;
        
        				$thisBatchCount=0;
        				$otherBatchesCount=0;
        				
        				//find all previously shipped items in this line
        				$scheduledItemsLookup=$this->WorkOrderItemStatus->find('all',['conditions'=>['order_line_number'=>$lineitem['line_number'],'order_item_id'=>$orderItemID,'work_order_id'=>$orderID,'status' => 'Scheduled']]);
        				foreach($scheduledItemsLookup as $scheduledItemRow){
        					
        					if($scheduledItemRow['sherry_batch_id'] != $batchID){
        						$otherBatchesCount=($otherBatchesCount + floatval($scheduledItemRow['qty_involved']));
        					}else{
        						$thisBatchCount=($thisBatchCount + floatval($scheduledItemRow['qty_involved']));
        					}
        
        				}
        				$lineItemsArray[$lineitem['id']]['this_batch']=$thisBatchCount;
        				$lineItemsArray[$lineitem['id']]['other_batches'] = $otherBatchesCount;
        				$lineItemsArray[$lineitem['id']]['remaining_unscheduled'] = ($lineitem['qty'] - $thisBatchCount - $otherBatchesCount);
        				
        				$thisBatchOtherBoxed=0;
        				
        				foreach($otherBoxesThisBatch as $box){
        				    $boxContentCheck=$this->SherryBatchBoxContents->find('all',['conditions' => ['box_id' => $box['id'], 'line_number' => $lineitem['line_number']]])->toArray();
        				    foreach($boxContentCheck as $boxContentRow){
        				        $thisBatchOtherBoxed=($thisBatchOtherBoxed + $boxContentRow['qty']);
        				    }
        				}
        				
        				$lineItemsArray[$lineitem['id']]['this_batch_other_boxes'] = $thisBatchOtherBoxed;
        			}
        			
        			
                    
        			$this->set('lineItems',$lineItemsArray);
				} catch (RecordNotFoundException $e) { }
                break;
            }
        }
        
    }
    
    
    public function boxlabel($boxID){
        $GLOBALS['boxlabelpdf']=1;
        $thisBox=$this->SherryBatchBoxes->get($boxID)->toArray();
        
        $thisBatch=$this->SherryBatches->get($thisBox['batch_id'])->toArray();
        $thisOrder=$this->WorkOrders->get($thisBatch['work_order_id'])->toArray();
        
        $this->set('thisOrder',$thisOrder);
        
        $this->set('thisBatch',$thisBatch);
        
        $this->set('thisBox',$thisBox);
        
        
        //load all the data for the form
		//$thisCustomer=$this->Customers->get($thisOrder['customer_id'])->toArray();
	    /* PPSASCRUM-405: start */
		$thisCustomer=$this->Customers->find('all',['conditions'=>['id'=>$thisOrder['customer_id']]])->first()->toArray();
		/* PPSASCRUM-405: end */

		$this->set('customerData',$thisCustomer);
		
		
		$thisBoxContents=$this->SherryBatchBoxContents->find('all',['conditions' => ['box_id' => $boxID],'order'=>['created'=>'asc']])->toArray();
		$this->set('thisBoxContents',$thisBoxContents);

	//	$lineItems=$this->QuoteLineItems->find('all',['conditions'=>['quote_id'=>$thisOrder['quote_id']],'order'=>['sortorder'=>'asc']])->toArray();
        $lineItems=$this->WorkOrderLineItems->find('all',['conditions'=>['quote_id'=>$thisOrder['quote_id']],'order'=>['sortorder'=>'asc']])->toArray();

		$this->set('lineItems',$lineItems);

    	$lineItemsArray=array();
		foreach($lineItems as $lineitem){

			$lineItemsArray[$lineitem['id']]=array();
			$lineItemsArray[$lineitem['id']]['data']=$lineitem;
			$lineItemsArray[$lineitem['id']]['metadata']=array();
			$lineItemsArray[$lineitem['id']]['fabricdata']=array();
		//	$lineItemMetas=$this->QuoteLineItemMeta->find('all',['conditions'=>['quote_item_id'=>$lineitem['id']]])->toArray();
			$lineItemMetas=$this->WorkOrderLineItemMeta->find('all',['conditions'=>['worder_item_id'=>$lineitem['id']]])->toArray();
			foreach($lineItemMetas as $lineitemmeta){
				$lineItemsArray[$lineitem['id']]['metadata'][$lineitemmeta['meta_key']]=$lineitemmeta['meta_value'];	
				if($lineitemmeta['meta_key']=='fabricid' && intval($lineitemmeta['meta_value']) > 0){
					$thisFabric=$this->Fabrics->get($lineitemmeta['meta_value'])->toArray();
					$lineItemsArray[$lineitem['id']]['fabricdata']=$thisFabric;
				}
			}
			
			//find the matching order item id# for this quote item id#
			$orderItemID=0;

			$lookupOrderItem=$this->WorkOrderItems->find('all',['conditions'=>['quote_line_item_id'=>$lineitem['id']]])->toArray();

			foreach($lookupOrderItem as $orderitemrow){
				$orderItemID=$orderitemrow['id'];
			}
			$lineItemsArray[$lineitem['id']]['order_item_id']=$orderItemID;

			$thisBatchCount=0;
			$otherBatchesCount=0;
			
			//find all previously shipped items in this line
			$scheduledItemsLookup=$this->WorkOrderItemStatus->find('all',['conditions'=>['order_line_number'=>$lineitem['line_number'],'order_item_id'=>$orderItemID,'work_order_id'=>$thisOrder['id'],'status' => 'Scheduled']]);
			foreach($scheduledItemsLookup as $scheduledItemRow){
				
				if($scheduledItemRow['sherry_batch_id'] != $thisBatch['id']){
					$otherBatchesCount=($otherBatchesCount + floatval($scheduledItemRow['qty_involved']));
				}else{
					$thisBatchCount=($thisBatchCount + floatval($scheduledItemRow['qty_involved']));
				}

			}
			$lineItemsArray[$lineitem['id']]['this_batch']=$thisBatchCount;
			$lineItemsArray[$lineitem['id']]['other_batches'] = $otherBatchesCount;
			$lineItemsArray[$lineitem['id']]['remaining_unscheduled'] = ($lineitem['qty'] - $thisBatchCount - $otherBatchesCount);
			
		}
		
		$GLOBALS['pdfheader']='<table width="100%" cellpadding="0" cellspacing="0" border="0">
    <tr>
    	<td width="30%" align="left"style="line-height:93%;" valign="top">
    	    <img src="https://orders.hcinteriors.com/barcodes/genbarcode.php?value='.urlencode($thisOrder['order_number']).'" width="80" height="20" /><br>
    	    <font size="13"><b>'.$thisOrder['order_number'].'</b></font>
    	</td>
    	<td width="40%" align="center" valign="top">
    	    <font size="9">'.$thisCustomer['company_name'].'</font>
    	    <br>
    	    <font size="11"></font>
    	</td>
    	<td width="30%" align="center" valign="top">
    	    <font size="9">DATE</font>
    	    <br>
    	    <font size="11">'.date('n/j/y g:iA',$thisBox['created']).'</font><br><font size="11">BOX '.str_replace($thisOrder['order_number'].'-','',$thisBox['box_number']).'</font>
    	</td>
    </tr>
</table>';
		
		$GLOBALS['pdffooter']='<table width="100%" cellpadding="0" cellspacing="0" border="0">
    <tr>
    	<td width="100%" align="center" style="line-height:93%; font-size:9px;">
    	    PO# <font size="12">'.$thisOrder['po_number'].'</font>
    	</td>
    </tr>
</table>
<div style="height:3px;"></div>
<table width="100%" cellpadding="0" cellspacing="0" border="0">
    <tr>
        <!-- PPSASCRUM-405: start -->
    	<!-- <td width="30%" align="left" style="line-height:93%;"> -->
    	<td width="40%" align="left" style="line-height:93%;">
    	<!-- PPSASCRUM-405: end -->
    	    <font size="2"><br></font>
    	    <!-- PPSASCRUM-405: start -->
    	    <!-- <font size="20"><b>'.$thisBox['box_number'].'</b></font> -->
    	    <font size="15"><b>'.$thisBox['box_number'].'</b></font>
    	    <!-- PPSASCRUM-405: end -->
    	</td>
    	<!-- PPSASCRUM-405: start -->
    	<!-- <td width="30%" align="center" style="line-height:93%; font-size:9px;"> -->
    	<td width="20%" align="center" style="line-height:93%; font-size:9px;">
    	<!-- PPSASCRUM-405: end -->
    	    WT (LBS) &nbsp;&nbsp; <font size="12">'.$thisBox['weight'].'</font>
    	</td>
    	<td width="40%" align="center" style="line-height:93%; font-size:9px;">
    	    <!-- PPSASCRUM-405: start -->
    	    <!-- BOX DIMS &nbsp;&nbsp; <font size="12">'.$thisBox['length'].' X '.$thisBox['width'].' X '.$thisBox['height'].'</font> -->
    	    BOX DIMS &nbsp;&nbsp; <font size="8">'.$thisBox['length'].' X '.$thisBox['width'].' X '.$thisBox['height'].'</font>
    	    <!-- PPSASCRUM-405: end -->
    	</td>
    </tr>
</table>';

	$GLOBALS['labelfooterbarcodeimgurl']='https://orders.hcinteriors.com/barcodes/genbarcode.php?value='.urlencode($thisBox['box_number']);
	
        
		$this->set('lineItems',$lineItemsArray);
        
        
        $this->viewBuilder()->options([
                'pdfConfig' => [
                    'orientation' => 'portrait'
                ]
            ]);
    }
    
    
    public function deletebox($boxID){
        if($this->request->data){
            
            $boxData=$this->SherryBatchBoxes->get($boxID)->toArray();
            
            $boxTable=TableRegistry::get('SherryBatchBoxes');
            $thisBox=$boxTable->get($boxID);
            $boxTable->delete($thisBox);
            
            $boxContentsTable=TableRegistry::get('SherryBatchBoxContents');
            $allBoxContentLookup=$this->SherryBatchBoxContents->find('all',['conditions' => ['box_id' => $boxID]])->toArray();
            foreach($allBoxContentLookup as $boxEntry){
                $thisBoxEntry=$boxContentsTable->get($boxEntry['id']);
                $boxContentsTable->delete($thisBoxEntry);
            }
            
            $this->logActivity($_SERVER['REQUEST_URI'],'Deleted Box# '.$boxData['box_number']);

			$this->Flash->success('Deleted Box #'.$boxData['box_number']);

			return $this->redirect('/orders/batchboxes/'.$boxData['batch_id']);
            
            
        }else{
            $this->autoRender=false;
            $thisBox=$this->SherryBatchBoxes->get($boxID)->toArray();
            $this->set('thisBox',$thisBox);
            
            $this->render('confirmdeletebox');
        }
    }
    
    public function editbox($batchID,$boxID=false){
        $thisBatch=$this->SherryBatches->get($batchID)->toArray();
        $this->set('thisBatch',$thisBatch);
        
        $thisOrder=$this->Orders->get($thisBatch['work_order_id'])->toArray();
        $this->set('thisOrder',$thisOrder);
        
        if($this->request->data){
            if(!$boxID){ 
                exit("ERROR"); 
            }else{ 
                $thisBox=$this->SherryBatchBoxes->get($boxID)->toArray(); 
            }
            
            $batchBoxTable=TableRegistry::get('SherryBatchBoxes');
            $batchBoxContentTable=TableRegistry::get('SherryBatchBoxContents');
            
            $thisBox=$batchBoxTable->get($boxID);
            $thisBox->length = $this->request->data['length'];
            $thisBox->width = $this->request->data['width'];
            $thisBox->height = $this->request->data['height'];
            $thisBox->weight = $this->request->data['weight'];
            $thisBox->warehouse_location_id = $this->request->data['warehouse_location'];
            $thisBox->updated=time();
            $batchBoxTable->save($thisBox);
            
            //save box contents changes
            $lineItems=$this->WorkOrderLineItems->find('all',['conditions'=>['quote_id'=>$thisOrder['quote_id']],'order'=>['sortorder'=>'asc']])->toArray();
            foreach($lineItems as $lineitem){
                
                if(isset($this->request->data["boxcontent_lineitem_".$lineitem['id']])){
                   
                    if(intval($this->request->data["boxcontent_lineitem_".$lineitem['id']]) == 0){
                        //see if a content row exists, if so, delete it
                        //$boxEntryCheck=$this->SherryBatchBoxContents->find('all',['conditions' => ['box_id' => $boxID, 'line_number' => $lineitem['line_number'],'quote_item_id'=>$lineitem['id'] ] ]);
                        $boxEntryCheck=$this->SherryBatchBoxContents->find('all',['conditions' => ['box_id' => $boxID, 'quote_item_id'=>$lineitem['id'] ] ]);
                        if(count($boxEntryCheck) > 0){
                            //we need to delete these, because they've been zeroed out
                            foreach($boxEntryCheck as $entry){
                                $batchBoxContentTable->delete($entry);
                            }
                        }
                    }else{
                        //see if a content row exists, if so, update it
                        $boxEntryCheck=$this->SherryBatchBoxContents->find('all',['conditions' => ['box_id' => $boxID,'quote_item_id'=> $lineitem['id']] ])->toArray();//PPSASCRUM-123

                       // $boxEntryCheck=$this->SherryBatchBoxContents->find('all',['conditions' => ['box_id' => $boxID, 'line_number' => $lineitem['line_number'],'quote_item_id'=> $lineitem['id']] ])->toArray();//PPSASCRUM-123
                        if(count($boxEntryCheck) > 0){
                            foreach($boxEntryCheck as $entry){
                                if($entry['qty'] != $this->request->data["boxcontent_lineitem_".$lineitem['id']]){
                                    //submitted data is different than existing db value... update it
                                    $thisEntry=$batchBoxContentTable->get($entry['id']);
                                    $thisEntry->qty=$this->request->data["boxcontent_lineitem_".$lineitem['id']];
                                    $thisEntry->updated=time();
                                    $batchBoxContentTable->save($thisEntry);
                                }
                            }
                        }else{
                            //it doesnt... insert it
                            $newContentEntry=$batchBoxContentTable->newEntity();
                            $newContentEntry->box_id=$boxID;
                            $newContentEntry->order_id=$thisBatch['work_order_id'];
                            $newContentEntry->quote_item_id = $lineitem['id'];
                            $newContentEntry->line_number=$lineitem['line_number'];
                            $newContentEntry->room_number=$lineitem['room_number'];
                            $newContentEntry->qty=$this->request->data["boxcontent_lineitem_".$lineitem['id']];
                            $newContentEntry->created=time();
                            $newContentEntry->updated=time();
                            
                            $batchBoxContentTable->save($newContentEntry);
                        }
                    }
                    
                }else{
                    //see if a content row exists, if so, delete it
                    $boxEntryCheck=$this->SherryBatchBoxContents->find('all',['conditions' => ['box_id' => $boxID,'quote_item_id'=> $lineitem['id'] ] ]);
                    //$boxEntryCheck=$this->SherryBatchBoxContents->find('all',['conditions' => ['box_id' => $boxID, 'line_number' => $lineitem['line_number'],'quote_item_id'=> $lineitem['id'] ] ]);
                    if(count($boxEntryCheck) > 0){
                        //we need to delete these, because they've been zeroed out
                        foreach($boxEntryCheck as $entry){
                            $batchBoxContentTable->delete($entry);
                        }
                    }
                }
                
            }
            
            $this->logActivity($_SERVER['REQUEST_URI'],'Made changes to Box# '.$thisBox['box_number']);

			$this->Flash->success('Successfully saved changes to Box #'.$thisBox['box_number']);

			//return $this->redirect('/orders/batchboxes/'.$batchID);
			return $this->redirect('/orders/editbox/'.$batchID);
            
        }else{
            
            //$thisCustomer=$this->Customers->get($thisOrder['customer_id'])->toArray();
			$thisCustomer=$this->Customers->find('all',['conditions' => ['id' => $thisOrder['customer_id']]])->toArray();
            $this->set('thisCustomer',$thisCustomer);    
            
            if(!$boxID){
                //make user select a box to edit
                $this->autoRender=false;
                
                $warehouseLocations=$this->WarehouseLocations->find('all')->toArray();
                $this->set('warehouseLocations',$warehouseLocations);
                
                $allusers=$this->Users->find('all',['order'=>['last_name'=>'asc','first_name'=>'asc']])->toArray();
		        $this->set('allusers',$allusers);
		
                $allBoxesThisBatch=$this->SherryBatchBoxes->find('all',['conditions' => ['batch_id' => $batchID], 'order' => ['created' => 'asc']])->toArray();
                $allBoxes=array();
                foreach($allBoxesThisBatch as $box){
                    $allBoxes[$box['id']]=array();
                    $boxContents=$this->SherryBatchBoxContents->find('all',['conditions' => ['box_id' => $box['id']],'order'=>['created'=>'asc']])->toArray();
                    $allBoxes[$box['id']]['boxdata']=$box;
                    $allBoxes[$box['id']]['boxcontents']=$boxContents;
                }
                $this->set('allBoxes',$allBoxes);
                
                $this->render('selectboxtoedit');
            }else{
                $thisBox=$this->SherryBatchBoxes->get($boxID)->toArray();
                $this->set('thisBox',$thisBox);
                
                $thisBoxContents=$this->SherryBatchBoxContents->find('all',['conditions' => ['box_id' => $thisBox['box_id']],'order'=>['created'=>'asc']])->toArray();
                $this->set('thisBoxContents',$thisBoxContents);
                
                $warehouseLocations=$this->WarehouseLocations->find('all')->toArray();
                $this->set('warehouseLocations',$warehouseLocations);
                
                
                
                $lineItems=$this->WorkOrderLineItems->find('all',['conditions'=>['quote_id'=>$thisOrder['quote_id']],'order'=>['sortorder'=>'asc']])->toArray();

                $otherBoxesThisBatch=$this->SherryBatchBoxes->find('all',['conditions' => ['batch_id' => $batchID, 'id !=' => $boxID]])->toArray();
                $this->set('this_batch_existing_box_count',count($otherBoxesThisBatch));
    
    			$lineItemsArray=array();
    			foreach($lineItems as $lineitem){
    
    				$lineItemsArray[$lineitem['id']]=array();
    				$lineItemsArray[$lineitem['id']]['data']=$lineitem;
    				$lineItemsArray[$lineitem['id']]['metadata']=array();
    				$lineItemsArray[$lineitem['id']]['fabricdata']=array();
    				$lineItemMetas=$this->WorkOrderLineItemMeta->find('all',['conditions'=>['worder_item_id'=>$lineitem['id']]])->toArray();
    				foreach($lineItemMetas as $lineitemmeta){
    					$lineItemsArray[$lineitem['id']]['metadata'][$lineitemmeta['meta_key']]=$lineitemmeta['meta_value'];	
    					if($lineitemmeta['meta_key']=='fabricid' && intval($lineitemmeta['meta_value']) > 0){
    						$thisFabric=$this->Fabrics->get($lineitemmeta['meta_value'])->toArray();
    						$lineItemsArray[$lineitem['id']]['fabricdata']=$thisFabric;
    					}
    				}
    				
    				//find the matching order item id# for this quote item id#
    				$orderItemID=0;
    
    				$lookupOrderItem=$this->WorkOrderItems->find('all',['conditions'=>['quote_line_item_id'=>$lineitem['id']]])->toArray();
    
    				foreach($lookupOrderItem as $orderitemrow){
    					$orderItemID=$orderitemrow['id'];
    				}
    				$lineItemsArray[$lineitem['id']]['order_item_id']=$orderItemID;
    
    				$thisBatchCount=0;
    				$otherBatchesCount=0;
    				
    				//find all previously shipped items in this line
    				$scheduledItemsLookup=$this->WorkOrderItemStatus->find('all',['conditions'=>['order_item_id'=>$orderItemID,'work_order_id'=>$thisOrder['id'],'status' => 'Scheduled']]);
    				
    			//	$scheduledItemsLookup=$this->WorkOrderItemStatus->find('all',['conditions'=>['order_line_number'=>$lineitem['line_number'],'order_item_id'=>$orderItemID,'work_order_id'=>$thisOrder['id'],'status' => 'Scheduled']]);
    				foreach($scheduledItemsLookup as $scheduledItemRow){
    					
    					if($scheduledItemRow['sherry_batch_id'] != $batchID){
    						$otherBatchesCount=($otherBatchesCount + floatval($scheduledItemRow['qty_involved']));
    					}else{
    						$thisBatchCount=($thisBatchCount + floatval($scheduledItemRow['qty_involved']));
    					}
    
    				}
    				$lineItemsArray[$lineitem['id']]['this_batch']=$thisBatchCount;
    				$lineItemsArray[$lineitem['id']]['other_batches'] = $otherBatchesCount;
    				$lineItemsArray[$lineitem['id']]['remaining_unscheduled'] = ($lineitem['qty'] - $thisBatchCount - $otherBatchesCount);
    				
    				$thisBatchOtherBoxed=0;
    				
    				foreach($otherBoxesThisBatch as $box){
    				    //$boxContentCheck=$this->SherryBatchBoxContents->find('all',['conditions' => ['box_id' => $box['id'], 'line_number' => $lineitem['line_number'],'quote_item_id'=>$lineitem['id']]])->toArray();
    				   
    				    $boxContentCheck=$this->SherryBatchBoxContents->find('all',['conditions' => ['box_id' => $box['id'],'quote_item_id'=>$lineitem['id']]])->toArray();
    				    foreach($boxContentCheck as $boxContentRow){
    				        $thisBatchOtherBoxed=($thisBatchOtherBoxed + $boxContentRow['qty']);
    				    }
    				}
    				
    				$lineItemsArray[$lineitem['id']]['this_batch_other_boxes'] = $thisBatchOtherBoxed;
    			//$thisBoxContents=$this->SherryBatchBoxContents->find('all',['conditions' => ['box_id' => $thisBox['id'], 'line_number' => $lineitem['line_number'],'quote_item_id'=>$lineitem['id']]])->toArray();

    				$thisBoxContents=$this->SherryBatchBoxContents->find('all',['conditions' => ['box_id' => $thisBox['id'],'quote_item_id'=>$lineitem['id']]])->toArray();
    				$lineItemsArray[$lineitem['id']]['this_box_qty']=0;
    				foreach($thisBoxContents as $contentEntry){
    				    $lineItemsArray[$lineitem['id']]['this_box_qty'] = $contentEntry['qty'];
    				}
    			}
    			
    			
                
    			$this->set('lineItems',$lineItemsArray);
                
                
            }
        }
    }
    
    /*
    public function markboxshipped($boxID){
        $thisBox=$this->SherryBatchBoxes->get($boxID);
        $thisBox->status='Shipped';
        if($this->SherryBatchBoxes->save($thisBox)){
            $this->logActivity($_SERVER['REQUEST_URI'],'Marked Box# '.$thisBox->box_number.' as Shipped');

			$this->Flash->success('Successfully marked Box # '.$thisBox->box_number.' as Shipped');
            return $this->redirect('/orders/editbox/'.$thisBox->batch_id);
        }
    }
    */
    
    public function togglebatchshipstatus($batchID,$newStatus){
        $this->autoRender=false;
        if($newStatus=='yes'){
            //mark all boxes as SHIPPED
            $allBoxes=$this->SherryBatchBoxes->find('all',['conditions' => ['batch_id' => $batchID]])->toArray();
            foreach($allBoxes as $box){
                $thisBox=$this->SherryBatchBoxes->get($box['id']);
                $thisBox->status='Shipped';
                $thisBox->updated=time();
                $this->SherryBatchBoxes->save($thisBox);
            }
            
            $this->logActivity($_SERVER['REQUEST_URI'],'Marked all boxes in Batch '.$batchID.' as Shipped');
            echo "Successfully marked all boxes in batch ".$batchID." as Shipped";exit;
        }elseif($newStatus=='no'){
            //mark all boxes as NOT SHIPPED
            $allBoxes=$this->SherryBatchBoxes->find('all',['conditions' => ['batch_id' => $batchID]])->toArray();
            foreach($allBoxes as $box){
                $thisBox=$this->SherryBatchBoxes->get($box['id']);
                $thisBox->status='Not Shipped';
                $thisBox->updated=time();
                $this->SherryBatchBoxes->save($thisBox);
            }
            
            $this->logActivity($_SERVER['REQUEST_URI'],'Marked all boxes in Batch '.$batchID.' as Not Shipped');
            echo "Successfully marked all boxes in batch ".$batchID." as Not Shipped";exit;
        }
    }
    
    public function toggleboxshipstatus($boxID,$newStatus){
        $this->autoRender=false;
        $thisBox=$this->SherryBatchBoxes->get($boxID);
        if($newStatus=='yes'){
            $thisBox->status='Shipped';
        }elseif($newStatus=='no'){
            $thisBox->status='Not Shipped';
        }
        
        if($this->SherryBatchBoxes->save($thisBox)){
            $this->logActivity($_SERVER['REQUEST_URI'],'Marked Box# '.$thisBox->box_number.' as '.$thisBox->status);

            echo "Successfully marked box# ".$thisBox->box_number." as ".$thisBox->status;exit;
        }
    }
    
    
    public function markcomplete($orderID){
        $thisOrder=$this->Orders->get($orderID);
        $thisOrder->status='Shipped';
        $thisOrder->completed=time();
        if($this->Orders->save($thisOrder)){
            $thisWorkOrder = $this->WorkOrders->get($orderID);
            $thisWorkOrder->status='Shipped';
            $thisWorkOrder->completed=time();
            $this->WorkOrders->save($thisWorkOrder);
            $this->logActivity($_SERVER['REQUEST_URI'],'Marked order# '.$thisOrder->order_number.' as Completed');
            $this->logActivity($_SERVER['REQUEST_URI'],'Marked workorder# '.$thisWorkOrder->order_number.' as Completed');
            $this->Flash->success('Successfully marked order# '.$thisOrder->order_number.' as Completed');
            return $this->redirect('/orders/');
        }
    }
    
    
    public function revertcompletion($orderID){
        $thisOrder=$this->Orders->get($orderID);
        $thisOrder->status='Pre-Production';
        $thisOrder->completed=null;
        if($this->Orders->save($thisOrder)){
            $this->logActivity($_SERVER['REQUEST_URI'],'Reverted order# '.$thisOrder->order_number.' to In-Production');
            $this->Flash->success('Successfully reverted order# '.$thisOrder->order_number.' to In-Production');
            return $this->redirect('/orders/');
        }
    }
    
    
    public function unscheduledreport($dateStart=false,$dateEnd=false,$filename){
        $this->autoRender=false;
        
		if(!$dateStart || !$dateEnd){
			//show date range form
			$this->render('unscheduledreportform');
		}else{
		    require($_SERVER['DOCUMENT_ROOT']."/vendor/phpoffice/phpexcel/Classes/PHPExcel.php");
            require($_SERVER['DOCUMENT_ROOT']."/vendor/phpoffice/phpexcel/Classes/PHPExcel/Writer/Excel2007.php");
            
    	    $objPHPExcel = new \PHPExcel();
            $objPHPExcel->setActiveSheetIndex(0);
            
            
            $objPHPExcel->getActiveSheet()->SetCellValue('A1','Work Order #');
            $objPHPExcel->getActiveSheet()->SetCellValue('B1','Customer');
            $objPHPExcel->getActiveSheet()->SetCellValue('C1','PO#');
            $objPHPExcel->getActiveSheet()->SetCellValue('D1','Date');
            $objPHPExcel->getActiveSheet()->SetCellValue('E1','Project');
            $objPHPExcel->getActiveSheet()->SetCellValue('F1','Facility');
            $objPHPExcel->getActiveSheet()->SetCellValue('G1','Ship Date');
            $objPHPExcel->getActiveSheet()->SetCellValue('H1','Ship via');
            
            
            $objPHPExcel->getActiveSheet()->SetCellValue('I1','CC - QTY');
            $objPHPExcel->getActiveSheet()->SetCellValue('J1','CC - LF');
            //$objPHPExcel->getActiveSheet()->SetCellValue('K1','CC - DIFF');
            $objPHPExcel->getActiveSheet()->SetCellValue('K1','TRK - LF');
            $objPHPExcel->getActiveSheet()->SetCellValue('L1','BS - QTY');
            //$objPHPExcel->getActiveSheet()->SetCellValue('N1','BS - DIFF');
            $objPHPExcel->getActiveSheet()->SetCellValue('M1','DRAPERIES - QTY');
            
            $objPHPExcel->getActiveSheet()->SetCellValue('N1','DRAPERIES - WIDTHS');
            
            //$objPHPExcel->getActiveSheet()->SetCellValue('Q1','DRAPERIES - DIFF');
            
            $objPHPExcel->getActiveSheet()->SetCellValue('O1','VALANCES - QTY');
            $objPHPExcel->getActiveSheet()->SetCellValue('P1','VALANCES - LF');
            
            $objPHPExcel->getActiveSheet()->SetCellValue('Q1','CORNICES - QTY');
            $objPHPExcel->getActiveSheet()->SetCellValue('R1','CORNICES - LF');
            
            //$objPHPExcel->getActiveSheet()->SetCellValue('R1','TOP TREATMENTS - QTY');
            //$objPHPExcel->getActiveSheet()->SetCellValue('S1','TOP TREATMENTS - LF');
            //$objPHPExcel->getActiveSheet()->SetCellValue('T1','TOP TREATMENTS - DIFF');
            
            
            
            $objPHPExcel->getActiveSheet()->SetCellValue('S1','WT HW - QTY');
            
            $objPHPExcel->getActiveSheet()->SetCellValue('T1','B&S - QTY');
            
            
            $startDue=strtotime($dateStart.' 00:00:01');
            $endDue=strtotime($dateEnd.' 23:59:59');
            
            $thisRow=2;
            $db=ConnectionManager::get('default');
            $query="SELECT 
                a.id,
                a.status,
                a.facility,
                a.customer_id,
                a.created,
                a.shipping_method_id,
                a.order_number,
                a.due,
                a.po_number,
                a.sherry_status,
                c.type,
                c.status,
                c.product_type,
                c.product_id,
                c.qty,
                c.line_number,
                c.calculator_used,
                d.company_name
            FROM 
                orders a,
                order_items b,
                quote_line_items c,
                customers d
            WHERE
                a.status NOT IN ('Canceled','Shipped') 
                AND 
                d.id=a.customer_id 
                AND 
                b.order_id=a.id 
                AND 
                c.id=b.quote_line_item_id 
                AND 
                (a.sherry_status = 'Partially Scheduled' OR a.sherry_status = 'Not Scheduled') 
                AND 
                a.due >= ".$startDue." 
                AND 
                a.due <= ".$endDue." 
                AND 
                a.due IS NOT NULL 
            GROUP BY 
                a.id 
            ORDER BY 
                a.created ASC";
            
            //echo $query;exit;
            
            $queryRun=$db->execute($query);
    		$unscheduledOrders=$queryRun->fetchAll('assoc');
    		$grouped=array();
    
    		foreach($unscheduledOrders as $order){
    
    			$orderScheduledDiff=0;
    			
    			$outstandingOrderItems=array();
    			$orderItems=$this->OrderItems->find('all',['conditions'=>['order_id' => $order['id']]])->toArray();
    
    			foreach($orderItems as $orderLineItem){
    
    				$itemTotalDiff=0;
    				$itemTotalLF=0;
                    $itemScheduledLF=0;
                    
    				$thisQuoteItem=$this->QuoteLineItems->get($orderLineItem['quote_line_item_id'])->toArray();
    				$itemMetas=$this->QuoteLineItemMeta->find('all',['conditions'=>['quote_item_id' => $thisQuoteItem['id']]])->toArray();
    
    				if($thisQuoteItem['parent_line'] == 0){
    
    					foreach($itemMetas as $itemMetaRow){
    						if($itemMetaRow['meta_key'] == 'difficulty-rating'){
    							$itemTotalDiff = ($itemTotalDiff + floatval($itemMetaRow['meta_value']));
    						}
    						if($itemMetaRow['meta_key'] == 'labor-billable'){
    							$itemTotalLF = ($itemTotalLF + (floatval($itemMetaRow['meta_value']) * floatval($thisQuoteItem['qty']) ) );
    						}
    					}
    
    					$totalQTY=$thisQuoteItem['qty'];
    
    					//loop through all schedules on this order, this line number
    					$usedBatches=array();
    
    					$thisOrderLineItemStatuses=$this->OrderItemStatus->find('all',['conditions'=>['work_order_id' => $order['id'],'order_line_number' => $thisQuoteItem['line_number'],'status IN' => ['Scheduled','In Progress','Completed','Warehoused','Shipped','Invoiced']],'order'=>['time'=>'desc']])->toArray();
    					foreach($thisOrderLineItemStatuses as $lineStatus){
    						if(!in_array($lineStatus['sherry_batch_id'],$usedBatches)){
    							$totalQTY=($totalQTY - floatval($lineStatus['qty_involved']));
    							$usedBatches[]=$lineStatus['sherry_batch_id'];
    
    							foreach($itemMetas as $itemMetaRow){
    								if($itemMetaRow['meta_key'] == 'difficulty-rating'){
    									$itemScheduledDiff = floatval($itemMetaRow['meta_value']);
    								}
    								if($itemMetaRow['meta_key'] == 'labor-billable'){
    									$itemScheduledLF = (floatval($itemMetaRow['meta_value']) * floatval($lineStatus['qty_involved']));
    								}
    							}
    							
    						}
    
    					}
    
    					switch($thisQuoteItem['product_type']){
    						case "window_treatments":
    							
    							if(count($itemMetas) == 0){
    								$thisitemtype='wt';
    							}else{
    								/*
    								foreach($itemMetas as $itemMeta){
    									switch($itemMeta['meta_value']){
    										case 'Pinch Pleated Drapery':
    											$thisitemtype='drape';
    										break;
    										default:
    											$thisitemtype='wt';
    										break;
    									}
    								}
    								*/
    								
    								foreach($itemMetas as $itemMeta){
        							    if($itemMeta['meta_key'] == 'wttype'){
        							        if($itemMeta['meta_value'] == 'Straight Cornice' || $itemMeta['meta_value'] == 'Shaped Cornice'){
        							            $thisitemtype='corn';
        							        }elseif($itemMeta['meta_value'] == 'Box Pleated Valance'){
        							            $thisitemtype='val';
        							        }elseif($itemMeta['meta_value'] == 'Pinch Pleated Drapery'){
        							            $thisitemtype='drape';
        							        }else{
        							            $thisitemtype='none';
        							        }
        							    }
        							}
    							}
    
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
								    /* PPSASCRUM-56: start */
									case 'new-pinch-pleated':
									/* PPSASCRUM-56: end */
    									$thisitemtype='drape';
    								break;
    								case "straight-cornice":
    									$thisitemtype='corn';
    								break;
    							}
    
    						break;
    						case "cubicle_curtains":
    							$thisitemtype='cc';
    						break;
    						case "bedspreads":
    							$thisitemtype='bs';
    						break;
    						case "track_systems":
    							$thisitemtype='track';
    						break;
    						
    						case "newcatchall-swtmisc":
    						    $thisitemtype='none';
    						break;
    						case "newcatchall-cubicle":
    						    $thisitemtype='cc';
    						break;
    						case 'newcatchall-bedding':
    						    $thisitemtype='bs';
    						break;
    						case 'newcatchall-valance':
    						    $thisitemtype='val';
    						break;
    						case 'newcatchall-cornice':
    						    $thisitemtype='corn';
    						break;
    						case 'newcatchall-drapery':
    						    $thisitemtype='drape';
    						break;
    						
    						case 'newcatchall-hardware':
    						    if($thisQuoteItem['product_subclass'] == 3){
    							    $thisitemtype='track';
    						    }elseif($thisQuoteItem['product_subclass'] == 1 || $thisQuoteItem['product_subclass'] == 2 || $thisQuoteItem['product_subclass'] == 4){
    							    $thisitemtype='wthw';
    							}elseif($thisQuoteItem['product_subclass'] == 5 || $thisQuoteItem['product_subclass'] == 6 || $thisQuoteItem['product_subclass'] == 7 || $thisQuoteItem['product_subclass'] == 8){
    							    $thisitemtype='blinds';
    							}else{
    						        $thisitemtype='none';
    						    }
    						break;
    						
    						case "custom":
    							//figure out if this is a BLINDS  or  HARDWARE item
    							if($thisQuoteItem['calculator_used'] == 'WT Hardware'){
    								$thisitemtype='wthw';
    							}elseif($thisQuoteItem['calculator_used'] == 'Blinds & Shades'){
    								$thisitemtype='blinds';
    							}else{
    								$thisitemtype='none';
    							}
    						break;
    						default:
    							$thisitemtype='none';
    						break;
    					}
    
    					$outstandingOrderItems[$thisQuoteItem['id']]=array(
    						'lineNumber' => $thisQuoteItem['line_number'],
    						'itemType'=>$thisitemtype,
    						'unscheduled'=>$totalQTY,
    						'unscheduled_diff' => ($itemTotalDiff - $itemScheduledDiff),
    						'unscheduled_lf' => ($itemTotalLF - $itemScheduledLF),
    						'scheduled'=>(floatval($thisQuoteItem['qty']) - floatval($totalQTY)),
    						'scheduled_diff' => $itemScheduledDiff,
    						'scheduled_lf' => $itemScheduledLF,
    						'totalLineQty' =>$thisQuoteItem['qty']
    					);
    
    				}
    
    			}
    			$grouped[$order['id']]=array(
    					"order_number" => $order['order_number'],
    					"company_name" => $order['company_name'],
    					"po_number" => $order['po_number'],
    					"due" => $order['due'],
    					"created" => $order['created'],
    					"facility" => $order['facility'],
    					"linesStatus"=>$outstandingOrderItems,
    					"shipping_method_id" => $order['shipping_method_id']
    				);
    
    		}
    
    
            //echo "<pre>"; print_r($grouped); echo "</pre>"; exit;
            
    
    		foreach($grouped as $orderid => $orderdata){
    		    
    		    $projectname='';
    			$totals='';
    			$cc_qty_unscheduled=0;
    			$cc_lf_unscheduled=0;
    			//$cc_diff_unscheduled=0;
    			$trk_lf_unscheduled=0;
    			$bs_qty_unscheduled=0;
    			//$bs_diff_unscheduled=0;
    			$drape_qty_unscheduled=0;
    			$drape_widths_unscheduled=0;
    			//$drape_diff_unscheduled=0;
    			//$wt_qty_unscheduled=0;
    			//$wt_lf_unscheduled=0;
    			//$wt_diff_unscheduled=0;
    			
    			$val_qty_unscheduled=0;
    			$val_lf_unscheduled=0;
    			
    			$corn_qty_unscheduled=0;
    			$corn_lf_unscheduled=0;
    			
    			$wthw_qty_unscheduled=0;
    			$blinds_qty_unscheduled=0;
    			
    			foreach($orderdata['linesStatus'] as $lineItemID => $thisrowtotals){
    				$lineNumber=$thisrowtotals['lineNumber'];
    				switch($thisrowtotals['itemType']){
    					case "cc":
    						$cc_qty_unscheduled = ($cc_qty_unscheduled + $thisrowtotals['unscheduled']);
    						$cc_lf_unscheduled = ($cc_lf_unscheduled + $thisrowtotals['unscheduled_lf'] );
    						//$cc_diff_unscheduled = ($cc_diff_unscheduled + $thisrowtotals['unscheduled_diff']);
    					break;
    					case "wt":
    						$wt_qty_unscheduled = ($wt_qty_unscheduled + $thisrowtotals['unscheduled']);
    						//$wt_diff_unscheduled = ($wt_diff_unscheduled + $thisrowtotals['unscheduled_diff']);
    					break;
    					case "corn":
    					    $corn_qty_unscheduled = ($corn_qty_unscheduled + $thisrowtotals['unscheduled']);
    					    $corn_lf_unscheduled = ($corn_lf_unscheduled + $thisrowtotals['unscheduled_lf']);
    					break;
    					case "val":
    					    $val_qty_unscheduled = ($val_qty_unscheduled + $thisrowtotals['unscheduled']);
    					    $val_lf_unscheduled = ($val_lf_unscheduled + $thisrowtotals['unscheduled_lf']);
    					break;
    					case "drape":
    						$drape_qty_unscheduled = ($drape_qty_unscheduled + $thisrowtotals['unscheduled']);
    						//$drape_diff_unscheduled = ($drape_diff_unscheduled + $thisrowtotals['unscheduled_diff']);
    					break;
    					case "bs":
    						$bs_qty_unscheduled = ($bs_qty_unscheduled + $thisrowtotals['unscheduled']);
    						//$bs_diff_unscheduled = ($bs_diff_unscheduled + $thisrowtotals['unscheduled_diff']);
    					break;
    					case "wthw":
    						$wthw_qty_unscheduled = ($wthw_qty_unscheduled + $thisrowtotals['unscheduled']);
    					break;
    					case "blinds":
    						$blinds_qty_unscheduled = ($blinds_qty_unscheduled + $thisrowtotals['unscheduled']);
    					break;
    					case "track":
    						$trk_lf_unscheduled = ($trk_lf_unscheduled + $thisrowtotals['unscheduled']);
    					break;
    				}
    
    			}
    			if(is_null($orderdata['due']) || $orderdata['due'] < 1000){
    				$duedate='N/A';
    			}else{
    				$duedate=date('n/j/Y',$orderdata['due']);
    			}
    
    
    			if(intval($orderdata['shipping_method_id']) > 0){
    				$thisShipMethod=$this->ShippingMethods->get($orderdata['shipping_method_id'])->toArray();
    				$shipVia = $thisShipMethod['name'];
    			}else{
    			    $shipVia='';
    			}
    		    
    		    $objPHPExcel->getActiveSheet()->SetCellValue('A'.$thisRow,$orderdata['order_number']);
    		    $objPHPExcel->getActiveSheet()->SetCellValue('B'.$thisRow,$orderdata['company_name']);
    		    $objPHPExcel->getActiveSheet()->SetCellValue('C'.$thisRow,$orderdata['po_number']);
    		    $objPHPExcel->getActiveSheet()->SetCellValue('D'.$thisRow,date('n/j/Y',$orderdata['created']));
    		    $objPHPExcel->getActiveSheet()->SetCellValue('E'.$thisRow,$projectname);
    		    $objPHPExcel->getActiveSheet()->SetCellValue('F'.$thisRow,$orderdata['facility']);
    		    $objPHPExcel->getActiveSheet()->SetCellValue('G'.$thisRow,$duedate);
    		    
    		    $objPHPExcel->getActiveSheet()->SetCellValue('H'.$thisRow,$shipVia);
    		    
    		    $objPHPExcel->getActiveSheet()->SetCellValue('I'.$thisRow,($cc_qty_unscheduled > 0 ? $cc_qty_unscheduled : ''));
    		    $objPHPExcel->getActiveSheet()->SetCellValue('J'.$thisRow,($cc_lf_unscheduled > 0 ? $cc_lf_unscheduled : ''));
    		    //$objPHPExcel->getActiveSheet()->SetCellValue('K'.$thisRow,($cc_diff_unscheduled > 0 ? $cc_diff_unscheduled : ''));
    		    $objPHPExcel->getActiveSheet()->SetCellValue('K'.$thisRow,($trk_lf_unscheduled > 0 ? $trk_lf_unscheduled : ''));
    		    $objPHPExcel->getActiveSheet()->SetCellValue('L'.$thisRow,($bs_qty_unscheduled > 0 ? $bs_qty_unscheduled : ''));
    		    //$objPHPExcel->getActiveSheet()->SetCellValue('N'.$thisRow,($bs_diff_unscheduled > 0 ? $bs_diff_unscheduled : ''));
    		    $objPHPExcel->getActiveSheet()->SetCellValue('M'.$thisRow,($drape_qty_unscheduled > 0 ? $drape_qty_unscheduled : ''));
    		    $objPHPExcel->getActiveSheet()->SetCellValue('N'.$thisRow,($drape_widths_unscheduled > 0 ? $drape_widths_unscheduled : ''));
    		    //$objPHPExcel->getActiveSheet()->SetCellValue('Q'.$thisRow,($drape_diff_unscheduled > 0 ? $drape_diff_unscheduled : ''));
    		    //$objPHPExcel->getActiveSheet()->SetCellValue('R'.$thisRow,($wt_qty_unscheduled > 0 ? $wt_qty_unscheduled : ''));
    		    //$objPHPExcel->getActiveSheet()->SetCellValue('S'.$thisRow,($wt_lf_unscheduled > 0 ? $wt_lf_unscheduled : ''));
    		    //$objPHPExcel->getActiveSheet()->SetCellValue('T'.$thisRow,($wt_diff_unscheduled > 0 ? $wt_diff_unscheduled : ''));
    		    
    		    $objPHPExcel->getActiveSheet()->SetCellValue('O'.$thisRow,($val_qty_unscheduled > 0 ? $val_qty_unscheduled : ''));
    		    $objPHPExcel->getActiveSheet()->SetCellValue('P'.$thisRow,($val_lf_unscheduled > 0 ? $val_lf_unscheduled : ''));
    		    
    		    $objPHPExcel->getActiveSheet()->SetCellValue('Q'.$thisRow,($corn_qty_unscheduled > 0 ? $corn_qty_unscheduled : ''));
    		    $objPHPExcel->getActiveSheet()->SetCellValue('R'.$thisRow,($corn_lf_unscheduled > 0 ? $corn_lf_unscheduled : ''));
    		    
    		    $objPHPExcel->getActiveSheet()->SetCellValue('S'.$thisRow,($wthw_qty_unscheduled > 0 ? $wthw_qty_unscheduled : ''));
    		    $objPHPExcel->getActiveSheet()->SetCellValue('T'.$thisRow,($blinds_qty_unscheduled > 0 ? $blinds_qty_unscheduled : ''));
    		    
    		    
    		    
    		    /*
    		    if($orderdata['due'] < time()){
    			    $objPHPExcel->getActiveSheet()->getStyle('A'.$thisRow.':V'.$thisRow)->applyFromArray(
        				array(
        					'fill' => array(
        						'type' => \PHPExcel_Style_Fill::FILL_SOLID,
        						'color' => array('rgb' => 'B20000')
        					),
        					'font' => array(
        					    'bold'=>true,
        					    'color' => array('rgb' => 'FFFFFF')
        					)
        				)
        			);
    			}
    		    */
    		    
    		    $thisRow++;
    		}
            
            
            $objPHPExcel->getActiveSheet()->getStyle('A2:T'.($thisRow-1))->getAlignment()->setWrapText(true);
            
            
            $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
			$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(27);
			$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(24);
			$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
			$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(30);
			$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(30);
			$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(17);
			$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(17);
			$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(14);
			$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(14);
			$objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(14);
			$objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(14);
			$objPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth(23);
			$objPHPExcel->getActiveSheet()->getColumnDimension('N')->setWidth(23);
			$objPHPExcel->getActiveSheet()->getColumnDimension('O')->setWidth(21);
			$objPHPExcel->getActiveSheet()->getColumnDimension('P')->setWidth(21);
			$objPHPExcel->getActiveSheet()->getColumnDimension('Q')->setWidth(21);
			$objPHPExcel->getActiveSheet()->getColumnDimension('R')->setWidth(25);
			$objPHPExcel->getActiveSheet()->getColumnDimension('S')->setWidth(25);
			$objPHPExcel->getActiveSheet()->getColumnDimension('T')->setWidth(25);
			
			$objPHPExcel->getActiveSheet()->getStyle("A1:T".($thisRow-1))->applyFromArray(
				array(
					'borders' => array(
						'allborders' => array(
							'style' => \PHPExcel_Style_Border::BORDER_THIN,
							'color' => array('rgb' => '111111')
						)
					)
				)
			);
			

			$objPHPExcel->getActiveSheet()->getStyle('A2:T'.($thisRow-1))->getAlignment()->setVertical(\PHPExcel_Style_Alignment::VERTICAL_TOP);
			$objPHPExcel->getActiveSheet()->getStyle('A2:T'.($thisRow-1))->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			
			$objPHPExcel->getActiveSheet()->getStyle('A1:T1')->applyFromArray(
				array(
					'fill' => array(
						'type' => \PHPExcel_Style_Fill::FILL_SOLID,
						'color' => array('rgb' => '000000')
					),
					'font' => array(
					    'bold'=>true,
					    'color' => array('rgb' => 'FFFFFF')
					)
				)
			);
			

            $objPHPExcel->getActiveSheet()->getStyle('A1:T1')->getAlignment()->setHorizontal('center');
            
            //header('Content-Type: application/vnd.ms-excel; charset=UTF-8');
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="'.$filename.'"');
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
    }
    
    
    public function boxes(){
        
    }
    
    public function getboxeslist($shippedOnly=0){
        $boxes=array();
        
        $whereAdd='';
        
        if($shippedOnly > 0){
            $conditions=array('status !=' => 'Shipped');
            $whereAdd .= " AND a.status != 'Shipped'";
        }else{
            $conditions=array();
        }
        
        
		$overallTotalRows=$this->SherryBatchBoxes->find('all',['conditions' => $conditions])->count();
		
		if(isset($this->request->data['search']['value']) && strlen(trim($this->request->data['search']['value'])) >0){
			//this is a search
			$conditions['box_number LIKE']='%'.trim($this->request->data['search']['value']).'%';
			
			$whereAdd .= " AND a.box_number LIKE '%".trim($this->request->data['search']['value'])."%'";
		}
		
		if($this->request->data['order'][0]['column'] == 1){
		    $orderby="a.box_number";
		    $order=$this->request->data['order'][0]['dir'];
		}elseif($this->request->data['order'][0]['column'] == 7){
		    $orderby="b.location";
		    $order=$this->request->data['order'][0]['dir'];
		}
		
		$db=ConnectionManager::get('default');
        $query="SELECT a.*, b.location AS whlocation FROM sherry_batch_boxes a, warehouse_locations b WHERE b.id=a.warehouse_location_id".$whereAdd." ORDER BY ".$orderby." ".$order." LIMIT ".$this->request->data['start'].",".$this->request->data['length'];
            
        $queryRun=$db->execute($query);
    	$boxfind=$queryRun->fetchAll('assoc');
    	
		$totalFilteredRows=$this->SherryBatchBoxes->find('all',['conditions'=>$conditions])->count();
		
		foreach($boxfind as $box){
			$itemCount=0;
			$description='';
			$thisBoxContents=$this->SherryBatchBoxContents->find('all',['conditions' => ['box_id' => $box['id']], 'order' => ['line_number' => 'asc']])->toArray();
		
			foreach($thisBoxContents as $content){
			 
			    $description .= '<b>L #'.$content['line_number'].' ('.$content['qty'].')</b> ';

			 //Fix for 301 ticket start. added where cond quote_line_item_id
			    $thisLineItem=$this->WorkOrderLineItems->find('all',['conditions' => ['quote_line_item_id' => $content['quote_item_id']]])->toArray();
			 //301 fix end 
			    //$thisLineItem=$this->QuoteLineItems->get($content['quote_item_id'])->toArray();
			    $description .= $thisLineItem['title'];
			 //   $description .= "test_title";
			    $description .= '<br>';
			    $itemCount=($itemCount + $content['qty']);
			    
			}
			
			$thisUser=$this->Users->get($box['user_id'])->toArray();
            	/**PPSASCRUM-3 start **/

			$orderTable=TableRegistry::get('Orders');
	    	$orderItem=$orderTable->get($box['order_id']);
	    	//$thisCustomer=$this->Customers->get($orderItem['customer_id'])->toArray();
			$thisCustomer=$this->Customers->find('all',['conditions' => ['id' => $orderItem['customer_id']]])->toArray();


            $on_credit_hold = ($thisCustomer['on_credit_hold']) ? '<div><span style="color: red;"> On Credit Hold</span> </div> ' : '';

			$boxes[]=array(
				'DT_RowId'=>'row_'.$box['id'],
				'DT_RowClass'=>'rowtest',
				'0'=>'<input type="checkbox" name="bulktransfer_'.$box['id'].'" data-boxid="'.$box['id'].'" value="yes" />',
				'1' => $box['box_number'].' '.$on_credit_hold,
				'2' => $itemCount,
				'3' => $box['weight'],
				'4' => $box['length'].' x '.$box['width'].' x '.$box['height'],
				'5' => $box['status'],
				'6' => substr($description,0,(strlen($description)-4)),
				'7' => $box['whlocation'],
				'8' => $thisUser['first_name'].' '.substr($thisUser['last_name'],0,1),
				'9' => date('n/j/Y - g:iA',$box['created']),
				'10' => '<a href="/orders/editbox/'.$box['batch_id'].'/'.$box['id'].'"><img src="/img/edit.png" width="22" alt="Edit Box" title="Edit Box" /></a> &nbsp; 
      <a href="/orders/boxlabel/'.$box['id'].'.pdf" target="_blank"><img src="/img/printlabel.png" width="22" alt="Box Label" title="Box Label" /></a> &nbsp; 
      <a href="/orders/deletebox/'.$box['id'].'"><img src="/img/delete.png" width="22" alt="Delete Box" title="Delete Box" /></a> &nbsp; <a href="javascript:doTransferBox(\''.$box['id'].'\')"><img src="/img/transferbox.png" width="22" alt="Transfer Box" title="Transfer Box" /></a>'
			);
		}
		
		$this->set('draw',$this->request->data['draw']);
		$this->set('recordsTotal',$overallTotalRows);
		$this->set('recordsFiltered',$totalFilteredRows);
		$this->set('data',$boxes);
		$this->set('_serialize',['draw','recordsTotal','recordsFiltered','data']);
    }
    
    
    public function transferbox($boxID){
        $this->viewBuilder()->layout('iframeinner');
        
        $thisBox=$this->SherryBatchBoxes->get($boxID)->toArray();
        $warehouseLocations=$this->WarehouseLocations->find('all')->toArray();
        
        if($this->request->data){
            $this->autoRender=false;
            
            if($thisBox['status'] == 'Shipped'){
                echo "<h2 style=\"color:red;\">CANNOT TRANSFER BOX</h2><h3>Only Non Shipped Boxes can be transferred.</h3><p><button type=\"button\" onclick=\"parent.$.fancybox.close();\">Cancel</button>";exit;
            }else{
            
                $oldLoc=$this->WarehouseLocations->get($thisBox['warehouse_location_id'])->toArray();
                $newLoc=$this->WarehouseLocations->get($this->request->data['warehouse_location'])->toArray();
                
                $modify=$this->SherryBatchBoxes->get($boxID);
                $modify->warehouse_location_id=$this->request->data['warehouse_location'];
                if($this->SherryBatchBoxes->save($modify)){
                    $this->logActivity($_SERVER['REQUEST_URI'],'Transferred Box# '.$thisBox['box_number'].' from '.$oldLoc['location'].' to '.$newLoc['location']);
                    
                    echo "<script>parent.boxesDataTable.ajax.reload();parent.$.fancybox.close();</script>";
                }
            }
            
        }else{
            $this->set('thisBox',$thisBox);
        
            $this->set('warehouseLocations',$warehouseLocations);
        }
    }
    
    public function bulktransferboxes($transferboxes){
        $this->viewBuilder()->layout('iframeinner');
        $params = array();
		$fixedparams=array();
		parse_str(urldecode($transferboxes), $params);
		
		$warehouseLocations=$this->WarehouseLocations->find('all')->toArray();
		$this->set('warehouseLocations',$warehouseLocations);
		
		foreach($params as $key => $val){
			if($val=="yes"){
				$fixedparams[]=str_replace("bulktransfer_","",$key);
			}
		}
		
		$theseBoxes=$this->SherryBatchBoxes->find('all',['conditions' => ['id IN' => $fixedparams]])->toArray();
		$this->set('theseBoxes',$theseBoxes);
		
		if($this->request->data['process']=="step2"){
		    //make sure none of these boxes are already Shipped status
		    $fail=0;
		    foreach($theseBoxes as $box){
		        if($box['status']=='Shipped'){
		            $fail++;
		        }
		    }
		    
		    if($fail > 0){
		        echo "<h2 style=\"color:red;\">CANNOT TRANSFER BOX</h2><h3>One or more selected boxes are Shipped.<br>Only Non Shipped Boxes can be transferred.</h3><p><button type=\"button\" onclick=\"parent.$.fancybox.close();\">Cancel</button>";exit;
		    }else{
		        //do the updates and record the activity log
		        foreach($theseBoxes as $box){
		            $oldLoc=$this->WarehouseLocations->get($box['warehouse_location_id'])->toArray();
		            $newLoc=$this->WarehouseLocations->get($this->request->data['warehouse_location'])->toArray();
		            
		            $modify=$this->SherryBatchBoxes->get($box['id']);
                    $modify->warehouse_location_id=$this->request->data['warehouse_location'];
                    if($this->SherryBatchBoxes->save($modify)){
                        $this->logActivity($_SERVER['REQUEST_URI'],'Transferred Box# '.$box['box_number'].' from '.$oldLoc['location'].' to '.$newLoc['location']);
                    }
                    
		        }
		        echo "<script>parent.boxesDataTable.ajax.reload();parent.$.fancybox.close();</script>";
		    }
		}
    }
    
    
    public function boxinventoryxls($searchVal,$start,$length,$nonshippedOnly,$sortCol,$sortDir){
        // \PHPExcel_Shared_File::setUseUploadTempDirectory(true);
        $this->autoRender=false;
	    
	    require($_SERVER['DOCUMENT_ROOT']."/vendor/phpoffice/phpexcel/Classes/PHPExcel.php");
        require($_SERVER['DOCUMENT_ROOT']."/vendor/phpoffice/phpexcel/Classes/PHPExcel/Writer/Excel2007.php");
        
	    $objPHPExcel = new \PHPExcel();
        $objPHPExcel->setActiveSheetIndex(0);
		
		
		$objPHPExcel->getActiveSheet()->SetCellValue('A1','BOX #');
        $objPHPExcel->getActiveSheet()->SetCellValue('B1','ITEM COUNT');
        $objPHPExcel->getActiveSheet()->SetCellValue('C1','WEIGHT');
        $objPHPExcel->getActiveSheet()->SetCellValue('D1','L');
        $objPHPExcel->getActiveSheet()->SetCellValue('E1','W');
        $objPHPExcel->getActiveSheet()->SetCellValue('F1','H');
        $objPHPExcel->getActiveSheet()->SetCellValue('G1','STATUS');
        $objPHPExcel->getActiveSheet()->SetCellValue('H1','CONTENT DESCRIPTION');
        $objPHPExcel->getActiveSheet()->SetCellValue('I1','LOCATION');
        $objPHPExcel->getActiveSheet()->SetCellValue('J1','USER');
        $objPHPExcel->getActiveSheet()->SetCellValue('K1','DATE/TIME');
		
		
        $whereAdd='';
        
        if($nonshippedOnly == 'yes'){
            $conditions=array('status !=' => 'Shipped');
            $whereAdd .= " AND a.status != 'Shipped'";
        }else{
            $conditions=array();
        }

		if($searchVal != 'none'){
			//this is a search
			$conditions['box_number LIKE']='%'.trim($searchVal).'%';
			$whereAdd .= " AND a.box_number LIKE '%".trim($searchVal)."%'";
		}
		
		
		if($sortCol == 1){
		    $orderby="a.box_number";
		    $order=$sortDir;
		}elseif($sortCol == 7){
		    $orderby="b.location";
		    $order=$sortDir;
		}
		
		$db=ConnectionManager::get('default');
        $query="SELECT a.*, b.location AS whlocation FROM sherry_batch_boxes a, warehouse_locations b WHERE b.id=a.warehouse_location_id".$whereAdd." ORDER BY ".$orderby." ".$order." LIMIT ".$start.",".$length;
        
        //echo $query;exit;
            
        $queryRun=$db->execute($query);
    	$boxfind=$queryRun->fetchAll('assoc');
        
        $thisRow=2;
        foreach($boxfind as $box){
            
            $itemCount=0;
			$description='';
			$thisBoxContents=$this->SherryBatchBoxContents->find('all',['conditions' => ['box_id' => $box['id']], 'order' => ['line_number' => 'asc']])->toArray();
			foreach($thisBoxContents as $content){
			    $description .= 'L #'.$content['line_number'].' ('.$content['qty'].') ';
			    $thisLineItem=$this->QuoteLineItems->get($content['quote_item_id'])->toArray();
			    $description .= $thisLineItem['title'];
			    $description .= "\n";
			    $itemCount=($itemCount + $content['qty']);
			}
			
			$thisUser=$this->Users->get($box['user_id'])->toArray();
			
            $objPHPExcel->getActiveSheet()->SetCellValue('A'.$thisRow,$box['box_number']);
            $objPHPExcel->getActiveSheet()->SetCellValue('B'.$thisRow,$itemCount);
            $objPHPExcel->getActiveSheet()->SetCellValue('C'.$thisRow,$box['weight']);
            $objPHPExcel->getActiveSheet()->SetCellValue('D'.$thisRow,$box['length']);
            $objPHPExcel->getActiveSheet()->SetCellValue('E'.$thisRow,$box['width']);
            $objPHPExcel->getActiveSheet()->SetCellValue('F'.$thisRow,$box['height']);
            $objPHPExcel->getActiveSheet()->SetCellValue('G'.$thisRow,$box['status']);
            $objPHPExcel->getActiveSheet()->SetCellValue('H'.$thisRow,substr($description,0,(strlen($description)-4)));
            $objPHPExcel->getActiveSheet()->SetCellValue('I'.$thisRow,$box['whlocation']);
            $objPHPExcel->getActiveSheet()->SetCellValue('J'.$thisRow,$thisUser['first_name'].' '.substr($thisUser['last_name'],0,1));
            $objPHPExcel->getActiveSheet()->SetCellValue('K'.$thisRow,date('n/j/Y - g:iA',$box['created']));
            
            $thisRow++;
        }
        
        $objPHPExcel->getActiveSheet()->getStyle('A1:K1')->applyFromArray(
				array(
					'fill' => array(
						'type' => \PHPExcel_Style_Fill::FILL_SOLID,
						'color' => array('rgb' => '000000')
					),
					'font' => array(
					    'bold'=>true,
					    'color' => array('rgb' => 'FFFFFF')
					)
				)
			);
			
        $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(15);
        $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(15);
        $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(15);
        $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(8);
        $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(8);
        $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(8);
        $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(15);
        $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(65);
        $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(15);
        $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(15);
        $objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(24);
        
        
        $objPHPExcel->getActiveSheet()->getStyle('A1:A'.($thisRow-1))->getAlignment()->setHorizontal('center');
        $objPHPExcel->getActiveSheet()->getStyle('B1:B'.($thisRow-1))->getAlignment()->setHorizontal('center');
        $objPHPExcel->getActiveSheet()->getStyle('C1:C'.($thisRow-1))->getAlignment()->setHorizontal('center');
        $objPHPExcel->getActiveSheet()->getStyle('D1:D'.($thisRow-1))->getAlignment()->setHorizontal('center');
        $objPHPExcel->getActiveSheet()->getStyle('E1:E'.($thisRow-1))->getAlignment()->setHorizontal('center');
        $objPHPExcel->getActiveSheet()->getStyle('F1:F'.($thisRow-1))->getAlignment()->setHorizontal('center');
        $objPHPExcel->getActiveSheet()->getStyle('G1:G'.($thisRow-1))->getAlignment()->setHorizontal('center');
        $objPHPExcel->getActiveSheet()->getStyle('I1:I'.($thisRow-1))->getAlignment()->setHorizontal('center');
        $objPHPExcel->getActiveSheet()->getStyle('J1:J'.($thisRow-1))->getAlignment()->setHorizontal('center');
        $objPHPExcel->getActiveSheet()->getStyle('K1:K'.($thisRow-1))->getAlignment()->setHorizontal('center');
        
        $objPHPExcel->getActiveSheet()->getStyle("A2:K".($thisRow-1))->applyFromArray(
				array(
					'borders' => array(
						'allborders' => array(
							'style' => \PHPExcel_Style_Border::BORDER_THIN,
							'color' => array('rgb' => '111111')
						)
					)
				)
			);
        
        $objPHPExcel->getActiveSheet()->getStyle('A2:K'.($thisRow-1))->getAlignment()->setVertical(\PHPExcel_Style_Alignment::VERTICAL_TOP);
        
        $objPHPExcel->getActiveSheet()->getStyle('H2:H'.($thisRow-1))->getAlignment()->setWrapText(true);
        
        // Redirect output to a client’s web browser (Excel5)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Box Inventory Export.xlsx"');
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
    
    
    public function bombreakdown($orderID){
        $thisOrder=$this->WorkOrders->get($orderID)->toArray();

		$this->set('orderData',$thisOrder);

// 		print_r($thisOrder);

		$fabricsDataArray=array();

		$allFabrics=$this->Fabrics->find('all')->toArray();
		
// 		print_r($allFabrics);

		foreach($allFabrics as $fabric){

			$fabricsDataArray[$fabric['id']]=$fabric;

		}

// 		print_r($fabricsDataArray);

		$this->set('fabricsDataArray',$fabricsDataArray);

		

		

		$GLOBALS['pdfmargins']='custom';

		$GLOBALS['pdfmarginscustom']=array('left'=>6,'top'=>1,'right'=>6,'header'=>3);

		

		$thisCustomer=$this->Customers->get($thisOrder['customer_id'])->toArray();

		$this->set('customerData',$thisCustomer);

		

		//find all the line items in this order

		$lineItems=$this->WorkOrderLineItems->find('all',['conditions'=>['quote_id'=>$thisOrder['quote_id']],'order'=>['sortorder'=>'asc']])->toArray();

		$this->set('lineItems',$lineItems);

		

		$lineItemsArray=array();

		$COMs=array();

		foreach($lineItems as $lineitem){

			

			

			$lineItemsArray[$lineitem['id']]=array();

			$lineItemsArray[$lineitem['id']]['data']=$lineitem;

			

			$lineItemsArray[$lineitem['id']]['metadata']=array();

			

			$lineItemsArray[$lineitem['id']]['fabricdata']=array();
			$lineItemsArray[$lineitem['id']]['liningsdata']=array();
			
			$lineItemMetaArray=array();

			$lineItemMetas=$this->WorkOrderLineItemMeta->find('all',['conditions'=>['worder_item_id'=>$lineitem['id']]])->toArray();

			foreach($lineItemMetas as $lineitemmeta){
				$lineItemMetaArray[$lineitemmeta['meta_key']] = $lineitemmeta['meta_value'];
			}
			
			foreach($lineItemMetas as $lineitemmeta){

				$lineItemsArray[$lineitem['id']]['metadata'][$lineitemmeta['meta_key']]=$lineitemmeta['meta_value'];	

				if($lineitemmeta['meta_key']=='fabricid' && $lineitemmeta['meta_value'] != '0' && !empty($lineitemmeta['meta_value'])){

					$thisFabric=$this->Fabrics->get($lineitemmeta['meta_value'])->toArray();

					$lineItemsArray[$lineitem['id']]['fabricdata']=$thisFabric;
					

				}elseif($lineitemmeta['meta_key']=='fabricid' && $lineitemmeta['meta_value'] == '0'){
					$thisFabric=array('fabric_name'=>$lineItemMetaArray['fabric_name'], 'color' => $lineItemMetaArray['fabric_color']);
					$lineItemsArray[$lineitem['id']]['fabricdata']=$thisFabric;
				}


				if(isset($lineItemMetas['com-fabric']) && $lineItemMetas['com-fabric'] == '1'){
					$lineItemsArray[$lineitem['id']]['fabricdata']['isCOM']=true;
				}else{
					$lineItemsArray[$lineitem['id']]['fabricdata']['isCOM']=false;
				}
				
					/**PPSASCRUM-13 start**/
			/* PPSASCRUM-56: start */
    		if(isset($lineItemsArray[$lineitem['id']]['metadata']['linings_id']) && $lineItemsArray[$lineitem['id']]['metadata']['linings_id']!= 'none' && $lineItemsArray[$lineitem['id']]['metadata']['linings_id']!= 'default' && !empty($lineItemsArray[$lineitem['id']]['metadata']['linings_id'])){
		    /* PPSASCRUM-56: end */
                if(strcmp($lineItemsArray[$lineitem['id']]['metadata']['linings_id'],'none') != 0){
                $liningData=$this->Linings->get($lineItemsArray[$lineitem['id']]['metadata']['linings_id'])->toArray();
                $qty = $lineItemsArray[$lineitem['id']]['metadata']['qty'];//$lineItemMetaArray['qty'];
                
                if($lineItemsArray[$lineitem['id']]['metadata']['lineitemtype'] == 'calculator'){
                    
                    if($lineItemsArray[$lineitem['id']]['metadata']['calculator-used']=='box-pleated'){
                         $liningPricePerYd =$lineItemsArray[$lineitem['id']]['metadata']['lining-yards']; 
                    /* PPSASCRUM-56: start */
                    // }elseif($lineItemsArray[$lineitem['id']]['metadata']['calculator-used']=='pinch-pleated'){
                    /* PPSASCRUM-384: start */
                    } elseif (
                        $lineItemsArray[$lineitem['id']]['metadata']['calculator-used'] == 'pinch-pleated' || $lineItemsArray[$lineitem['id']]['metadata']['calculator-used'] == 'new-pinch-pleated' ||
                        $lineItemsArray[$lineitem['id']]['metadata']['calculator-used'] == 'ripplefold-drapery' || $lineItemsArray[$lineitem['id']]['metadata']['calculator-used'] == 'accordiafold-drapery'
                    ) {
                    /* PPSASCRUM-384: end */
                    /* PPSASCRUM-56: end */
                         $liningPricePerYd =$lineItemsArray[$lineitem['id']]['metadata']['lining-yds-per-unit']; 
                    }elseif($lineItemsArray[$lineitem['id']]['metadata']['calculator-used']=='straight-cornice'){
                         $liningPricePerYd =$lineItemsArray[$lineitem['id']]['metadata']['yds-of-lining']; 
                    }
                   

                }else 
                    $liningPricePerYd =$lineItemsArray[$lineitem['id']]['metadata']['lining-per-unit'];// (float)$lineItemsArray[$lineitem['id']]['metadata']["lining-price-per-yd"];//$lineItemMetaArray['lining-price-per-yd'];
                    
                $total =  ($liningPricePerYd* $qty);
                $thisFabric=array('fabric_name'=>'Lining', 'color' => $liningData['short_title'],'line_number'=>$lineitem['line_number'],'wo_line_number'=>$lineitem['wo_line_number'],'total'=>$total,'qty'=>$qty,'liningPricePerYd'=>$liningPricePerYd,'test'=>$lineItemsArray[$lineitem['id']]['metadata']['linings_id'],'type'=>$lineitem->product_type,'calculator-used'=>$lineItemsArray[$lineitem['id']]['metadata']['calculator-used'],'wttype'=>$lineItemsArray[$lineitem['id']]['metadata']['wttype']);
                $lineItemsLiningsArray[$lineitem['id']]['liningdata']=$thisFabric;
                }
              
            }
    
    		/**PPSASCRUM-13 end**/
				
			}

		}


		$this->set('productTypeCounts',array('cc'=>$numCC,'bs'=>$numBS,'wt'=>$numWT));

		$this->set('lineItems',$lineItemsArray);

		/**PPSASCRUM-13 start**/	$this->set('lineLiningItems',$lineItemsLiningsArray);	/**PPSASCRUM-13 end**/


		$GLOBALS['pdforientation']='P';

		$GLOBALS['pdfmargins']='custom';

		$GLOBALS['pdfmarginscustom']=array('left'=>6,'top'=>5,'right'=>6,'header'=>10);
    }
    
    
    
    public function changeordertype($orderID,$newValue){
	    $this->autoRender=false;

		$orderTable=TableRegistry::get('Orders');

		$orderItem=$orderTable->get($orderID);

		$orderItem->type_id=$newValue;

		if($orderTable->save($orderItem)){

			echo "OK";

		}

		exit;
	}
	
	
	public function editbatchdate($batchID){
	    $this->autoRender=false;
	    
	    $thisBatch=$this->SherryBatches->get($batchID)->toArray();
		$date=date("m/d/Y",strtotime($thisBatch['date'].' 08:00:00'));
		$ymdDate=date("Y-m-d",strtotime($thisBatch['date'].' 08:00:00'));

		$orderID=$thisBatch['work_order_id'];

		$this->set('thisBatch',$thisBatch);

		$this->set('date',$date);
		
		$thisOrder=$this->Orders->get($orderID)->toArray();
		$this->set('orderData',$thisOrder);
		
		
	    if($this->request->data){
	        
	        //change the date for this batch
	        $batchEdit=$this->SherryBatches->get($batchID);
	        
	        $providedDate=$this->request->data['dateselection']; //mm/dd/yyyy
			$dateSplit=explode("/",$providedDate);
			$batchdate=$dateSplit[2].'-'.$dateSplit[0].'-'.$dateSplit[1];
	        
	        $batchEdit->date = $batchdate;
	        $this->SherryBatches->save($batchEdit);
	        
	        //update order item status lines
	        $statusLinesLookup=$this->WorkOrderItemStatus->find('all',['conditions' => ['sherry_batch_id' => $batchID, 'status' => 'Scheduled']])->toArray();
	        foreach($statusLinesLookup as $statusLine){
	            $statusLineEdit=$this->WorkOrderItemStatus->get($statusLine['id']);
	            $statusLineEdit->time=strtotime($this->request->data['dateselection'].' 18:00:00');
	            $this->WorkOrderItemStatus->save($statusLineEdit);
	        }
	        
	        //update sherry cache line
	        $cacheLookup=$this->SherryCache->find('all',['conditions' => ['batch_id' => $batchID]])->toArray();
	        foreach($cacheLookup as $cacheRow){
	            $cacheRowEdit=$this->SherryCache->get($cacheRow['id']);
	            $cacheRowEdit->date=$batchdate;
	            $this->SherryCache->save($cacheRowEdit);
	        }
	        
	        $this->logActivity($_SERVER['REQUEST_URI'],'Changed Batch '.$batchID.' Date from '.$ymdDate.' to '.$batchdate.' (WO# '.$thisOrder['order_number'].')');
	        return $this->redirect('/orders/schedule/');
	        
	    }else{
	        
	        //load all the data for the form
			$thisCustomer=$this->Customers->get($thisOrder['customer_id'])->toArray();

			$this->set('customerData',$thisCustomer);
			//find all the line items in this order

		//	$lineItems=$this->QuoteLineItems->find('all',['conditions'=>['quote_id'=>$thisOrder['quote_id']],'order'=>['sortorder'=>'asc']])->toArray();
			$lineItems=$this->WorkOrderLineItems->find('all',['conditions'=>['quote_id'=>$thisOrder['quote_id']],'order'=>['sortorder'=>'asc']])->toArray();

			$this->set('lineItems',$lineItems);

			$lineItemsArray=array();
			foreach($lineItems as $lineitem){

				$lineItemsArray[$lineitem['id']]=array();
				$lineItemsArray[$lineitem['id']]['data']=$lineitem;
				$lineItemsArray[$lineitem['id']]['metadata']=array();
				$lineItemsArray[$lineitem['id']]['fabricdata']=array();
				$lineItemMetas=$this->QuoteLineItemMeta->find('all',['conditions'=>['quote_item_id'=>$lineitem['id']]])->toArray();
				foreach($lineItemMetas as $lineitemmeta){
					$lineItemsArray[$lineitem['id']]['metadata'][$lineitemmeta['meta_key']]=$lineitemmeta['meta_value'];	
					if($lineitemmeta['meta_key']=='fabricid' && intval($lineitemmeta['meta_value']) > 0){
						$thisFabric=$this->Fabrics->get($lineitemmeta['meta_value'])->toArray();
						$lineItemsArray[$lineitem['id']]['fabricdata']=$thisFabric;
					}
				}
				
				//find the matching order item id# for this quote item id#
				$orderItemID=0;

				$lookupOrderItem=$this->WorkOrderItems->find('all',['conditions'=>['quote_line_item_id'=>$lineitem['id']]])->toArray();

				foreach($lookupOrderItem as $orderitemrow){
					$orderItemID=$orderitemrow['id'];
				}
				$lineItemsArray[$lineitem['id']]['order_item_id']=$orderItemID;

				$thisBatchCount=0;
				$otherBatchesCount=0;
				
				//find all previously shipped items in this line
				$scheduledItemsLookup=$this->WorkOrderItemStatus->find('all',['conditions'=>['order_line_number'=>$lineitem['line_number'],'order_item_id'=>$orderItemID,'work_order_id'=>$orderID,'status' => 'Scheduled']]);
				foreach($scheduledItemsLookup as $scheduledItemRow){
					
					if($scheduledItemRow['sherry_batch_id'] != $batchID){
						$otherBatchesCount=($otherBatchesCount + floatval($scheduledItemRow['qty_involved']));
					}else{
						$thisBatchCount=($thisBatchCount + floatval($scheduledItemRow['qty_involved']));
					}

				}
				$lineItemsArray[$lineitem['id']]['this_batch']=$thisBatchCount;
				$lineItemsArray[$lineitem['id']]['other_batches'] = $otherBatchesCount;
				$lineItemsArray[$lineitem['id']]['remaining_unscheduled'] = ($lineitem['qty'] - $thisBatchCount - $otherBatchesCount);
			}
			$this->set('productTypeCounts',array('cc'=>$numCC,'bs'=>$numBS,'wt'=>$numWT));

			$this->set('lineItems',$lineItemsArray);

			$this->set('allSettings',$this->getsettingsarray());



            //find all BOXES for this batch and load its data for the template new rule ticket 279782
			$batchBoxes=array();
			$boxesThisBatch=$this->SherryBatchBoxes->find('all',['conditions' => ['batch_id' => $batchID]])->toArray();
			
			foreach($boxesThisBatch as $box){
			    //find box contents
			    $batchBoxes[$box['id']]=array(
			        'contents'=>array(),
			        'box_number' => $box['box_number'],
			        'order_id' => $box['order_id'],
			        'status' => $box['status'],
			        'warehouse_location_id' => $box['warehouse_location_id']
			    );
			    
			    $thisBoxContents=$this->SherryBatchBoxContents->find('all',['conditions' => ['box_id' => $box['id']], 'order' => ['line_number' => 'asc']])->toArray();
			    foreach($thisBoxContents as $content){
			        $batchBoxes[$box['id']]['contents'][$content['id']]=array(
			            'quote_item_id' => $content['quote_item_id'],
			            'line_number' => $content['line_number'],
			            'qty' => $content['qty']
			        );
			    }
			    
			}
			
			$this->set('batchBoxes',$batchBoxes);
			
	        $this->set('mode','editdateonly');
	        $this->render('editscheduleform');
	    }
	}
	
	 public function pendingschedule($startDate,$endDate,$highlightIDs=false,$jsout=false){
    }	
    
    public function completedschedule($startDate,$endDate,$highlightIDs=false,$jsout=false){
    }
    
    public function searchsherryschedulebatches($searchterm){
		$this->autoRender=false;
		$numres=0;
	    $batches = $this->SherryBatches->find()->select(['work_order_number', 'id', 'date'])->where(['id LIKE' => ('%'.$searchterm . '%')],['id' => 'string'])->order(['date' => 'asc'])->toArray();
		echo "<table style=\"margin:0 0 0 0;\" width=\"100%\" cellpadding=\"5\" cellspacing=\"0\" border=\"0\"><thead><tr><th>WO#</th><th>BATCH #</th><th>DATE</th><th>ACTION</th></tr></thead><tbody>";
		foreach($batches as $batch){
			if($numres==0){
				$firstDate=date('Y-m-d',strtotime($batch['date']));
				$firstDatePretty=$batch['date'];
			}
			echo "<tr><td>".$batch['work_order_number']."</td><td>".$batch['id']."</td><td>".$batch['date']."</td><td><button type=\"button\" onclick=\"changeCalendarDateRange('".date('Y-m-d',strtotime($batch['date']))."','".date('Y-m-d',strtotime($batch['date']))."',false,'".$batch['id']."')\" style=\"font-size:11px; padding:5px 5px 5px 5px !important; border:0;margin:0 0 0 0;\">Go To Date</button></td></tr>";
			$numres++;
			if($numres==count($batches)){
				$lastDate=date('Y-m-d',strtotime($batch['date']));
				$lastDatePretty=$batch['date'];
			}
		}
	
		echo "</tbody></table>";
		if($numres > 1){
			echo "<div style=\"margin:0 0 0 0; padding:0px 0; text-align:center;\"><button type=\"button\" style=\"display:block; width:100%; font-size:14px; margin:0 0 0 0; padding:8px 0px 8px 0px !important; border:0;\" onclick=\"changeCalendarDateRange('".$firstDate."','".$lastDate."',false,'".$searchterm."')\">Go To Date Range ".$firstDatePretty." - ".$lastDatePretty."</button></div>";
		}
		exit;
	}
	
	
	
    /**PPSACRUM-7 Start **/
    public function facility($subaction='default',$itemid=0,$fromtype='',$frominfo=''){
		$this->autoRender=false;
		switch($subaction){
			default:
			case "default":
				$this->render('facility');
			break;
			case "add":
				if($this->request->data){
					//print_r($this->request->data);exit;
					$faciltiyTable=TableRegistry::get('Facility');
					$newfaciltiy=$faciltiyTable->newEntity();
					$newfaciltiy->facility_code=$this->request->data['facility_code'];
					$newfaciltiy->facility_name=$this->request->data['facility_name'];
					$newfaciltiy->default_address=$this->request->data['addressSelected'];//$this->request->data['default_address'];
					$newfaciltiy->user_id = $this->request->data['user_id'];
					$newfaciltiy->attention=$this->request->data['attention'];
					$newfaciltiy->created = time();
					$newfaciltiy->user = $this->Auth->user('id');

					if($faciltiyTable->save($newfaciltiy)){
						//hooray!
						$this->Flash->success('Successfully added Recipient "'.$this->request->data['facility_name']);
						$this->logActivity($_SERVER['REQUEST_URI'],"AddedFacility \"".$this->request->data['facility_name']);
						return $this->redirect('/orders/facility/');
					}
				}else{
					//add new Ship To Address form
					$shipTooptions=array();
				$allShipTo=$this->ShipTo->find('all',['order'=>['shipping_address_1'=>'asc']])->toArray();
				foreach($allShipTo as $ship){
					$shipTooptions[$ship['id']]=$ship['shipping_address_1'];
				}
				$this->set('shipTooptions',$shipTooptions);
					$this->render('addfacility');
				}
			break;
			case "edit":
				$faciltiyData=$this->Facility->get($itemid)->toArray();
				$this->set('faciltiyData',$faciltiyData);
				$shipTooptions=array();
				$allShipTo=$this->ShipTo->find('all',['order'=>['shipping_address_1'=>'asc']])->toArray();
				foreach($allShipTo as $ship){
					$shipTooptions[$ship['id']]=$ship['shipping_address_1'];
				}
				$this->set('shipTooptions',$shipTooptions);
				if($this->request->data){
					$faciltiyTable=TableRegistry::get('Facility');
					$thisfaciltiy=$faciltiyTable->get($itemid);
					$thisfaciltiy->facility_code=$this->request->data['facility_code'];
					$thisfaciltiy->facility_name=$this->request->data['facility_name'];
					
					$thisfaciltiy->default_address=$this->request->data['addressSelected'];//$this->request->data['default_address'];
					$thisfaciltiy->attention=$this->request->data['attention'];
					$thisfaciltiy->modified = time();
					$thisfaciltiy->user_id = $this->Auth->user('id');
					if($faciltiyTable->save($thisfaciltiy)){
						$this->Flash->success('Successfully saved changes to Facility "'.$this->request->data['facility_name'].'"');
						$this->logActivity($_SERVER['REQUEST_URI'],"Edited Facility \"".$this->request->data['facility_name']."\"");
						return $this->redirect('/orders/facility/');
					}
				}else{
					$this->render('editfacility');
				}
			break;
			case "delete":
			    $faciltiyData=$this->Facility->get($itemid)->toArray();
			    $this->set('faciltiyData',$faciltiyData);
			    
			    if($this->request->data){
			        
			        $thisFacility=$this->Facility->get($itemid);
			        if($this->Facility->delete($thisFacility)){
			            $this->Flash->success('Successfully deleted Faciltiy "'.$faciltiyData['facility_name'].'"');
						$this->logActivity($_SERVER['REQUEST_URI'],"Deleted Faciltiy \"".$faciltiyData['facility_name']."\"");
						return $this->redirect('/orders/facility/');
			        }
			    }else{
			        $this->render('confirmdeletefaciltiy');
			    }
			break;
		}
	}
	public function getfacilitylist(){
		$facilitys=array();

		$overallTotalRows=$this->Facility->find('all')->count();
		$conditions=array();
		if(isset($this->request->data['search']['value']) && strlen(trim($this->request->data['search']['value'])) >0){
			//this is a search
			$conditions['OR']=array();
			$conditions['OR'] += array('facility_name LIKE'=>'%'.trim($this->request->data['search']['value']).'%');
			$conditions['OR'] += array('facility_code LIKE'=>'%'.trim($this->request->data['search']['value']).'%');
			$conditions['OR'] += array('attention LIKE'=>'%'.trim($this->request->data['search']['value']).'%');			
		}
		
		if(isset($this->request->data['start'])){
			$start=$this->request->data['start'];
		}else{
			$start=0;
		}

		if(isset($this->request->data['length'])){
			$limit=$this->request->data['length'];
		}else{
			$limit=25;
		}


		if(isset($this->request->data['draw'])){
			$draw=$this->request->data['draw'];
		}else{
			$draw=1;
		}
	
		$orderby=array();
		if(isset($this->request->data['order'][0]['column'])){
			switch($this->request->data['order'][0]['column']){
				case 1:
					$orderby=array('facility_code' => $this->request->data['order'][0]['dir']);
					break;
					default:
					$orderby += array('id' => 'asc');
					break;
					case 2:
						$orderby=array('facility_name' => $this->request->data['order'][0]['dir']);
					break;
			}
		}
		$getFacility=$this->Facility->find('all',['conditions'=>$conditions,'order'=>$orderby])->offset($this->request->data['start'])->limit($this->request->data['length'])->hydrate(false)->toArray();
		$totalFilteredRows=$this->Facility->find('all',['conditions'=>$conditions])->count();


		foreach($getFacility as $facility){

			$default_address = '';
			$allShipTo=$this->ShipTo->find('all',['conditions'=>['id'=>$facility['default_address']],'order'=>['shipping_address_1'=>'asc']])->toArray();
			foreach($allShipTo as $ship){
				$default_address=$ship['shipping_address_1'].",".$ship['shipping_address_1'].",".$ship['shipping_city'].",".$ship['shipping_state'].",".$ship['shipping_country'].",".$ship['shipping_zipcode'];
			}

		$facilitys[]=array(
			'DT_RowId'=>'row_'.$facility['id'],
			'DT_RowClass'=>'rowtest',
			'0' => '<a href="/orders/facility/edit/'.$facility['id'].'/"><img src="/img/edit.png" alt="Edit This Facility" title="Edit This Facility" /></a>  <a href="/orders/facility/delete/'.$facility['id'].'/"><img src="/img/delete.png" alt="Delete This Facility" title="Delete This Facility" /></a> ',
			'1' => $facility['facility_code'],
			'2' => $facility['facility_name'],
			'3' => $default_address,
			'4' => $facility['attention'],
			'5' => date('n/j/Y - g:iA',$facility['created'])
		);
	}

		$this->set('draw',$draw);

		$this->set('recordsTotal',$overallTotalRows);

		$this->set('recordsFiltered',$totalFilteredRows);

		$this->set('data',$facilitys);
		
		$this->set('_serialize',['draw','recordsTotal','recordsFiltered','data']);

		
	}

	public function getshiptoslist(){
		$shipTo=array();

		$overallTotalRows=$this->ShipTo->find('all')->count();
		$conditions=array();
		if(isset($this->request->data['search']['value']) && strlen(trim($this->request->data['search']['value'])) >0){
			//this is a search
			$conditions['OR']=array();
			$conditions['OR'] += array('ship_to_name LIKE'=>'%'.trim($this->request->data['search']['value']).'%');
			$conditions['OR'] += array('shipping_address_1 LIKE'=>'%'.trim($this->request->data['search']['value']).'%');
			$conditions['OR'] += array('shipping_address_2 LIKE'=>'%'.trim($this->request->data['search']['value']).'%');
			$conditions['OR'] += array('shipping_city LIKE'=>'%'.trim($this->request->data['search']['value']).'%');
			$conditions['OR'] += array('shipping_state LIKE'=>'%'.trim($this->request->data['search']['value']).'%');
			
		}
		
		$orderby=array();
		if(isset($this->request->data['order'][0]['column'])){
			switch($this->request->data['order'][0]['column']){
			    case 1:
					$orderby=array('ship_to_name' => $this->request->data['order'][0]['dir']);
					break;
				case 2:
					$orderby=array('shipping_address_1' => $this->request->data['order'][0]['dir']);
					break;
					default:
					$orderby += array('id' => 'asc');
					break;
					case 3:
						$orderby=array('shipping_address_2' => $this->request->data['order'][0]['dir']);
					break;
					case 4:
						$orderby=array('shipping_city' => $this->request->data['order'][0]['dir']);
					break;
					case 5:
						$orderby=array('shipping_state' => $this->request->data['order'][0]['dir']);
					break;
			}
		}

		if(isset($this->request->data['start'])){
			$start=$this->request->data['start'];
		}else{
			$start=0;
		}

		if(isset($this->request->data['length'])){
			$limit=$this->request->data['length'];
		}else{
			$limit=25;
		}


		if(isset($this->request->data['draw'])){
			$draw=$this->request->data['draw'];
		}else{
			$draw=1;
		}
		$getShipto=$this->ShipTo->find('all',['conditions'=>$conditions,'order'=>$orderby])->offset($start)->limit($limit)->hydrate(false)->toArray();

		$totalFilteredRows=$this->ShipTo->find('all',['conditions'=>$conditions,'order'=>$orderby])->count();
		foreach($getShipto as $shipTos){
		$shipTo[]=array(

			'DT_RowId'=>'row_'.$shipTos['id'],
			'DT_RowClass'=>'rowtest',
			'0' => '<a href="/orders/shipto/edit/'.$shipTos['id'].'/"><img src="/img/edit.png" alt="Edit This ShipTo" title="Edit This ShipTo" /></a>  <a href="/orders/shipto/delete/'.$shipTos['id'].'/"><img src="/img/delete.png" alt="Delete This Lining" title="Delete This ShipTo" /></a> ',
			 '1' => $shipTos['ship_to_name'], 
			'2' => $shipTos['shipping_address_1'],
			'3' => $shipTos['shipping_address_2'],
			'4' => $shipTos['shipping_city'],
			'5' => $shipTos['shipping_state'],
			'6' => $shipTos['shipping_country'],
			'7' => $shipTos['shipping_zipcode'],
			'8' => date('n/j/Y - g:iA',$shipTos['created'])
		);
	}

		$this->set('draw',$draw);

		$this->set('recordsTotal',$overallTotalRows);

		$this->set('recordsFiltered',$totalFilteredRows);

		$this->set('data',$shipTo);
		
		$this->set('_serialize',['draw','recordsTotal','recordsFiltered','data']);

		
	}
	public function shipto($subaction='default',$itemid=0,$fromtype='',$frominfo=''){
		$this->autoRender=false;
		switch($subaction){
			default:
			case "default":
				$this->render('shipto');
			break;
			case "add":
				if($this->request->data){
				
					$shipToTable=TableRegistry::get('ShipTo');
					$newShipTo=$shipToTable->newEntity();
					$newShipTo->shipping_address_1=$this->request->data['shipping_address_1'];
					$newShipTo->ship_to_name=$this->request->data['ship_to_name'];
					$newShipTo->shipping_address_2=$this->request->data['shipping_address_2'];
					$newShipTo->shipping_city=$this->request->data['shipping_city'];
					$newShipTo->shipping_state=$this->request->data['shipping_state'];
					$newShipTo->shipping_country=$this->request->data['shipping_country'];
					$newShipTo->shipping_zipcode=$this->request->data['shipping_zipcode'];
					$newShipTo->created = time();
					$newShipTo->user_id = $this->Auth->user('id');

					if($shipToTable->save($newShipTo)){
						$this->Flash->success('Successfully added ShipTo Address "'.$this->request->data['shipping_address_1'] );
						$this->logActivity($_SERVER['REQUEST_URI'],"Added ShipTo Address \"".$this->request->data['shipping_address_1']);
						return $this->redirect('/orders/shipto/');
					}
				}else{
					//add new Ship To Address form
					$users=array();
					$this->set('users',$users);
					$this->render('addshipto');
				}
			break;
			case "edit":
				$shiptoData=$this->ShipTo->get($itemid)->toArray();
				$this->set('shiptoData',$shiptoData);
				
				if($this->request->data){
					$shipToTable=TableRegistry::get('ShipTo');
					$thisshipto=$shipToTable->get($itemid);
					$thisshipto->ship_to_name=$this->request->data['ship_to_name'];
					$thisshipto->shipping_address_1=$this->request->data['shipping_address_1'];
					$thisshipto->shipping_address_2=$this->request->data['shipping_address_2'];
					$thisshipto->shipping_city=$this->request->data['shipping_city'];
					$thisshipto->shipping_state=$this->request->data['shipping_state'];
					$thisshipto->shipping_country=$this->request->data['shipping_country'];
					$thisshipto->shipping_zipcode=$this->request->data['shipping_zipcode'];
					$thisshipto->user_id = $this->request->data['user_id'];
					$thisshipto->modified = time();
					if($shipToTable->save($thisshipto)){
						$this->Flash->success('Successfully saved changes to ShipTo "'.$this->request->data['shipping_address_1'].'"');
						$this->logActivity($_SERVER['REQUEST_URI'],"Edited ShipTo \"".$this->request->data['shipping_address_1']."\"");
						return $this->redirect('/orders/shipto/');
					}
				}else{
					$this->render('editshipto');
				}
			break;
			case "delete":
			    $shipToData=$this->ShipTo->get($itemid)->toArray();
			    $this->set('shiptoData',$shipToData);
			    
			    if($this->request->data){
			        
			        $thisShipTo=$this->ShipTo->get($itemid);
			        if($this->ShipTo->delete($thisShipTo)){
			            $this->Flash->success('Successfully deleted shipto "'.$shipToData['title'].'"');
						$this->logActivity($_SERVER['REQUEST_URI'],"Deleted shipto \"".$shipToData['title']."\"");
						return $this->redirect('orders/shipto/');
			        }
			    }else{
			        $this->render('confirmdeleteshipto');
			    }
			break;
		}
	}
	
	public function markFacilityshipto($frombreakdown=0) {
		$this->viewBuilder()->layout('iframeinner');
		if($this->request->data){
		//print_r($this->request->data);exit;
		$shipToTable=TableRegistry::get('ShipTo');
		$newShipTo=$shipToTable->newEntity();
		
		$newShipTo->shipping_address_1=$this->request->data['shipping_address_1'];
		$newShipTo->shipping_address_2=$this->request->data['shipping_address_2'];
		$newShipTo->shipping_city=$this->request->data['shipping_city'];
		$newShipTo->shipping_state=$this->request->data['shipping_state'];
		$newShipTo->shipping_country=$this->request->data['shipping_country'];
		$newShipTo->shipping_zipcode=$this->request->data['shipping_zipcode'];
		$newShipTo->ship_to_name=$this->request->data['ship_to_name'];

		$newShipTo->created = time();
		$newShipTo->user_id = $this->Auth->user('id');

		if($shipToTable->save($newShipTo)){
		//	$this->Flash->success('Successfully added ShipTo Address "'.$this->request->data['shipping_address_1']);
			$this->logActivity($_SERVER['REQUEST_URI'],"Added ShipTo Address \"".$this->request->data['shipping_address_1']);
			$lastCreated = $this->ShipTo->find('all', array('order' => array('id' =>'desc')))->first()->toArray();

		}
		if($frombreakdown == 1){
			echo "<html><head><script>parent.window.location.reload(true);</script></head><body></body></html>";exit;
		}else{
			$shipTooptions=array();
			$allShipTo=$this->ShipTo->find('all',['order'=>['shipping_address_1'=>'asc']])->toArray();
			//$options = '<option value="">--Select address-- </option>';
			foreach($allShipTo as $ship){
				$id=$ship['id'];
				$address = $ship['shipping_address_1'];
				$options .=$address.":".$id.",";//"<option value=".$id.">".$address."</option>";

			}
			$this->set('shipTooptions',$shipTooptions);
		$options =substr($options, 0, -1);
			echo "<script>";
		   // echo "var array = \"$options;\"; ";
			echo "var ship1=\"".$lastCreated['shipping_address_1']."\";";
			echo "var ship2=\"".$lastCreated['shipping_address_2']."\";";
			echo "var city=\"".$lastCreated['shipping_city']."\";";
			echo "var state=\"".$lastCreated['shipping_state']."\";";
			echo "var country=\"".$lastCreated['shipping_country']."\";";
			echo "var zipcode=\"".$lastCreated['shipping_zipcode']."\";";
			echo "var ship_to_name=\"".$lastCreated['ship_to_name']."\";";
			echo "var id=\"".$lastCreated['id']."\";";
			echo "localStorage.setItem(\"shipping_address_1\", \"".$lastCreated['shipping_address_1']."\");";
			echo "localStorage.setItem(\"shipping_address_2\", \"".$lastCreated['shipping_address_2']."\");";
			echo "localStorage.setItem(\"shipping_city\",  \"".$lastCreated['shipping_city']."\");";
			echo "localStorage.setItem(\"shipping_state\",  \"".$lastCreated['shipping_state']."\");";
			echo "localStorage.setItem(\"shipping_country\", \"".$lastCreated['shipping_country']."\");";
			echo "localStorage.setItem(\"shipping_zipcode\", \"".$lastCreated['shipping_zipcode']."\");";
			echo "localStorage.setItem(\"ship_to_name\", \"".$lastCreated['ship_to_name']."\");";
			echo "localStorage.setItem(\"default_address\", \"".$lastCreated['id']."\");";
		   // echo "localStorage.setItem(\"options\", \"".$options."\");";
			echo "localStorage.setItem(\"lastHref\", \""."facilityHref"."\");";
			echo "localStorage.setItem(\"facilityDetails\",  \"".$this->request->query['params']."\");";
			echo "parent.$(\"#new_facility\").trigger(\"click\")";
			  echo "</script>";exit;
		}
		
	}
}
	public function markshipto($frombreakdown=0){
		$this->viewBuilder()->layout('iframeinner');

		if($this->request->data){
			//print_r($this->request->data);exit;
			$shipToTable=TableRegistry::get('ShipTo');
			$newShipTo=$shipToTable->newEntity();
			
			$newShipTo->shipping_address_1=$this->request->data['shipping_address_1'];
			$newShipTo->shipping_address_2=$this->request->data['shipping_address_2'];
			$newShipTo->shipping_city=$this->request->data['shipping_city'];
			$newShipTo->shipping_state=$this->request->data['shipping_state'];
			$newShipTo->shipping_country=$this->request->data['shipping_country'];
			$newShipTo->shipping_zipcode=$this->request->data['shipping_zipcode'];
			$newShipTo->ship_to_name=$this->request->data['ship_to_name'];

			$newShipTo->created = time();
			$newShipTo->user_id = $this->Auth->user('id');

			if($shipToTable->save($newShipTo)){
				//hooray!
				$this->Flash->success('Successfully added ShipTo Address "'.$this->request->data['shipping_address_1']);
				$this->logActivity($_SERVER['REQUEST_URI'],"Added ShipTo Address \"".$this->request->data['shipping_address_1']);
				$lastCreated = $this->ShipTo->find('all', array('order' => array('id' =>'desc')))->first()->toArray();

			}
			if($frombreakdown == 1){
				echo "<html><head><script>parent.window.location.reload(true);</script></head><body></body></html>";exit;
			}else{
				$shipTooptions=array();
				$allShipTo=$this->ShipTo->find('all',['order'=>['shipping_address_1'=>'asc']])->toArray();
				//$options = '<option value="">--Select address-- </option>';
				$options ='';
				foreach($allShipTo as $ship){
					$id=$ship['id'];
					$address = $ship['shipping_address_1'];
					$options .="\"".$address."\"".":"."\"".$id."\"".",";//"<option value=".$id.">".$address."</option>";

				}
				$this->set('shipTooptions',$shipTooptions);
			    //$options =substr($options, 0, -1);
				
				echo "<script>parent.$('#default_address').show();
				parent.$('#new_address').hide();parent.$('#new_address').hide();parent.$('input[type=radio][name=new_or_existing][value=\"exisiting\"]').prop('checked', true);  
				";
				/*echo "parent.$('#default_address').html(' ');
				parent.$.each({".$options."}, function(key, value) {
					parent.$('#default_address').append(parent.$('<option></option>')
					.attr(\"value\", value).text(key));
				});";*/

				echo "parent.$('#shipping_address_1').val('".$lastCreated['shipping_address_1']."');";
				echo "parent.$('#shipping_address_2').val('".$lastCreated['shipping_address_2']."');";
				echo "parent.$('#shipping_city').val('".$lastCreated['shipping_city']."');";
				echo "parent.$('#shipping_state').val('".$lastCreated['shipping_state']."');";
				echo "parent.$('#shipping_country').val('".$lastCreated['shipping_country']."');";
				echo "parent.$('#shipping_zipcode').val('".$lastCreated['shipping_zipcode']."');";
				echo "parent.$('#ship_to_name').val('".$lastCreated['ship_to_name']."');";
				echo "parent.$('#addressSelected').val('".$lastCreated['id']."');";
					echo "parent.$('#default_address').val('".$lastCreated['ship_to_name']."');";

				echo "parent.$.fancybox.close(); </script>";exit;
			}
			
		}
	}

	public function  markfacility($frombreakdown=0){
		$this->viewBuilder()->layout('iframeinner');
		$shipTooptions=array();
		$allShipTo=$this->ShipTo->find('all',['order'=>['shipping_address_1'=>'asc']])->toArray();
		foreach($allShipTo as $ship){
			$shipTooptions[$ship['id']]=$ship['shipping_address_1'];
		}
		$this->set('shipTooptions',$shipTooptions);	
		
		$allFaclity=$this->Facility->find('list',['keyField' => 'id', 'valueField' => 'facility_name', 'conditions' => [],'order'=>['facility_name'=>'asc']])->toArray();
		//$this->set('allFacility',$allFaclity);
		if($this->request->data){
			$facilityTable=TableRegistry::get('Facility');
			$newfacility=$facilityTable->newEntity();
			
			$newfacility->facility_code=$this->request->data['facility_code'];
			$newfacility->facility_name=$this->request->data['facility_name'];
			$newfacility->default_address=$this->request->data['addressSelected'];
			$newfacility->user_id = $this->Auth->user('id');
			$newfacility->attention=$this->request->data['attention'];
			$newfacility->created = time();

			if($facilityTable->save($newfacility)){
				$this->Flash->success('Successfully added Recipient "'.$this->request->data['facility_name']);
				$this->logActivity($_SERVER['REQUEST_URI'],"Added Facility \"".$this->request->data['facility_name']);
				
				$allFacility=$this->Facility->find('all',['order'=>['facility_name'=>'asc']])->toArray();
				$options ='';
				foreach($allFacility as $facility){
					$id=$facility['id'];
					$address = $facility['facility_name'];
					$options .="'".str_replace("'", "\\'", $address)."'".":"."'".$id."'".",";//"<option value=".$id.">".$address."</option>";

				}
				$lastCreated = $this->Facility->find('all', array('order' => array('id' =>'desc')))->first()->toArray();
			} 
			if($frombreakdown == 1){
				echo "<html><head><script>parent.window.location.reload(true);</script></head><body></body></html>";exit;
			}else{
				//print_r($options);echo $lastCreated['id'];
					$thisAddress = $this->ShipTo->get($lastCreated['default_address'])->toArray();;
			
				echo "<script>";
				echo "localStorage.setItem(\"shipping_address_1\",''); localStorage.setItem(\"shipping_address_2\",''); localStorage.setItem(\"shipping_city\",'');
			     localStorage.setItem(\"shipping_state\",''); localStorage.setItem(\"shipping_country\",'');localStorage.setItem(\"shipping_zipcode\",'');localStorage.setItem(\"facilityDetails\",''); localStorage.setItem('options', '');localStorage.setItem('lastHref',''); localStorage.setItem('default_address', '');
				localStorage.setItem(\"ship_to_name\",'');";
				echo "parent.$('#facilitySelected').html('');";
				/*echo "parent.$.each({".$options."}, function(key, value) {
					parent.$('#facilitySelected').append(parent.$('<option></option>')
					.attr(\"value\", value).text(key));
				});";*/
				echo "parent.$('#shipping_address_1').val('".$thisAddress['shipping_address_1']."');";
				echo "parent.$('#shipping_address_2').val('".$thisAddress['shipping_address_2']."');";
				echo "parent.$('#shipping_city').val('".$thisAddress['shipping_city']."');";
				echo "parent.$('#shipping_state').val('".$thisAddress['shipping_state']."');";
				echo "parent.$('#shipping_country').val('".$thisAddress['shipping_country']."');";
				echo "parent.$('#shipping_zipcode').val('".$thisAddress['shipping_zipcode']."');";
				echo "parent.$('#ship_to_name').val('".$thisAddress['ship_to_name']."');";
				
				echo " parent.$('#allfacility').val('".$lastCreated['facility_name']."');";
				echo " parent.$('#facilitySelected').val('".$lastCreated['id']."');";
				echo " parent.$('#facilityCode').val('".$lastCreated['facility_code']."');";
				echo " parent.$('#facilityAttention').val('".$lastCreated['attention']."');";
				echo " parent.$('#addressSelected').val('".$lastCreated['default_address']."');";
				echo "parent.$('#userAddressesSelection').val('default');";
				 echo "parent.$('input[type=radio][name=userAddressesSelection][value=\"default\"]').prop('checked', true); parent.$('#userAddressesSelection').val('default');";
			     echo "parent.$('#default_address').hide();";
				 echo "parent.$.fancybox.close(); </script>";exit;
			}
			
		}
		

	}

	public function getFacilityDetails($facilityId){
	    if(!empty($facilityId) && $facilityId != 'undefined') {
    		$this->autoRender=false;
    		$fac=$this->Facility->get($facilityId)->toArray();
    		echo $fac['facility_code'] . " :|: " . $fac['attention']." :|: ".$fac['facility_name']." :|: ".$facilityId;
	    }
		exit;
	}

	public function getFacilityAddressDetails($facilityId){
	    if(!empty($facilityId)) {
	    	$this->autoRender=false;
	    	$fac=$this->Facility->get($facilityId)->toArray();
	    	$shipto = $this->ShipTo->get($fac['default_address'])->toArray();
	    	echo $shipto['shipping_address_1']." :|: ".$shipto['shipping_address_2'] ." :|: ".$shipto['shipping_city']." :|: ".$shipto['shipping_state']." :|: ".$shipto['shipping_country']." :|: ".$shipto['shipping_zipcode']." :|: ".$shipto['attention']." :|: ".$shipto['ship_to_name']." :|: ".$shipto['id'];
	    }
		exit;
	}

	public function getShipAddressDetails($shipId){
		$this->autoRender=false;
		$shipto = $this->ShipTo->get($shipId)->toArray();
		echo $shipto['shipping_address_1']." :|: ".$shipto['shipping_address_2'] ." :|: ".$shipto['shipping_city']." :|: ".$shipto['shipping_state']." :|: ".$shipto['shipping_country']." :|: ".$shipto['shipping_zipcode']." :|: ".$shipto['attention']." :|: ".$shipto['ship_to_name']." :|: ".$shipto['id'];
		exit;
	}

	public function getShipTodetails($shipsearch,$feild ='shipping_address_1') {
		$this->autoRender=false;
		$conditions = array();
		$shipsearch =$this->request->query['search'];
		$feild = $this->request->query['type'];
		if($feild == 'shipping_address_1'){
			$conditions['OR']=array();
			$conditions['OR'] += array('shipping_address_1 LIKE'=>'%'.trim($shipsearch).'%');
		}else if($feild == 'shipping_address_2'){
			$conditions['OR']=array();
			$conditions['OR'] += array('shipping_address_2 LIKE'=>'%'.trim($shipsearch).'%');
		} else if($feild == 'shipping_city'){
			$conditions['OR']=array();
			$conditions['OR'] += array('shipping_city LIKE'=>'%'.trim($shipsearch).'%');
		}else if($feild == 'shipping_state'){
			$conditions['OR']=array();
			$conditions['OR'] += array('shipping_state LIKE'=>'%'.trim($shipsearch).'%');
		}else if($feild == 'shipping_zipcode'){
			$conditions['OR']=array();
			$conditions['OR'] += array('shipping_zipcode LIKE'=>'%'.trim($shipsearch).'%');
		}
		$shipto = $this->ShipTo->find('all', ['conditions'=>$conditions])->first();
		if($shipto != null) {
			$shipto = $shipto->toArray();
		echo $shipto['shipping_address_1']." :|: ".$shipto['shipping_address_2'] ." :|: ".$shipto['shipping_city']." :|: ".$shipto['shipping_state']." :|: ".$shipto['shipping_country']." :|: ".$shipto['shipping_zipcode']." :|: ".$shipto['attention']." :|: ".$shipto['id']." :|: ".$shipto['ship_to_name'];
		
		}
		exit;
	}
	
	public function getCustomerAddressDetails($id){
	    $this->autoRender=false;
		$shipto = $this->Customers->get($id)->toArray();
		$country =!empty($shipto['shipping_address_country']) ? $shipto['shipping_address_country']: 'US';
		echo $shipto['shipping_address']." :|: ".'' ." :|: ".$shipto['shipping_address_city']." :|: ".$shipto['shipping_address_state']." :|: ".$country." :|: ".$shipto['shipping_address_zipcode']." :|: ".$shipto['shipping_name'];
		exit;
	}
	
	public function  getFacilityByCode($id){
	    $codesearch =$this->request->query['search'];
	    $this->autoRender=false;
	    $conditions['OR']=array();
		$conditions['OR'] += array('facility_code LIKE'=>'%'.trim($codesearch).'%');
		$facility= $this->Facility->find('all', ['conditions'=>$conditions])->first();
		echo $facility['id']." :|: ".''.$facility['facility_code']." :|: ".$facility['attention']." :|: ".''.$facility['facility_code'];
		exit;
	}
	public function  getFacilityByName($name){
	    $codesearch =$this->request->query['search'];
	    $this->autoRender=false;
	    $conditions['OR']=array();
	//	$conditions['OR'] += array('facility_name LIKE'=>'%'.trim($name).'%');
			$conditions['OR'] += array('facility_name LIKE'=>'%'.trim($this->cleanfacilityparameterreplacements($name)).'%');

		$facility= $this->Facility->find('all', ['conditions'=>$conditions])->first();

		echo $facility['id']." :|: ".$facility['facility_name']." :|: ".$facility['facility_code']." :|: ".$facility['default_address']." :|: ".$facility['attention'];
		exit;
	}
	public function searchFacilityfield($q, $type){
		$this->autoRender=false;
	
		$db = ConnectionManager::get('default');
		$query = "SELECT *,facility.*,ship_to.id as shipToId,  ship_to.ship_to_name as shipName, ship_to.shipping_address_1 as shipaddress1 , ship_to.shipping_address_2 as shipaddress2, ship_to.shipping_city as shipcity,  ship_to.shipping_country as shipcountry,ship_to.shipping_state as shipstate, ship_to.shipping_zipcode as shipzipcode FROM  facility INNER JOIN ship_to ON facility.default_address = ship_to.id where facility_name like '".'%'.$q.'%'. "' or shipping_address_1 like '". '%'.$q.'%'  . "' or shipping_zipcode like '". '%'.$q.'%' ."' or shipping_city like '".'%'.$q.'%'."'" ;
		//echo $query;die();
		$queryRun = $db->execute($query);
		$facilityFind = $queryRun->fetchAll('assoc');
	   
	   $out=array();
	   foreach($facilityFind as $facility){
		   $out[$facility['id']]=$facility['facility_name']." :,: ".$facility['ship_to_name']." :,: ".$facility['shipping_address_1']." :,: ".$facility['shipping_city']." :,: ".$facility['shipping_state']." :,: ".$facility['shipping_country']." :,: ".$facility['shipping_zipcode']." :,: ".$facility['id'];
	   }
	   echo json_encode($out);
	   exit;
}

public function searchAddressfield($q, $type){
	$this->autoRender=false;
	$conditions['OR']=array();
//	$conditions['OR'] += array('ship_to_name LIKE'=>'%'.trim($q).'%');
	$conditions['OR'] += array('ship_to_name LIKE'=>'%'.trim($this->cleanfacilityparameterreplacements($q)).'%');

	$conditions['OR'] += array('shipping_address_1 LIKE'=>'%'.trim($q).'%');
	$conditions['OR'] += array('shipping_city LIKE'=>'%'.trim($q).'%');
	$conditions['OR'] += array('shipping_state LIKE'=>'%'.trim($q).'%');
	$conditions['OR'] += array('shipping_zipcode LIKE'=>'%'.trim($q).'%');
//	print_r($conditions);
	$shipTo= $this->ShipTo->find('all', ['conditions'=>$conditions])->toArray();
	$out=array();
foreach($shipTo as $facility){
	$out[$facility['id']]=$facility['ship_to_name']." :,: ".$facility['shipping_address_1']." :,: ".$facility['shipping_city']." :,: ".$facility['shipping_state']." :,: ".$facility['shipping_country']." :,: ".$facility['shipping_zipcode']." :,: ".$facility['id'];
}
echo json_encode($out);
exit;

}
	public function getShipAddressNameDetails($name){
		$this->autoRender=false;
		$conditions['OR']=array();
		//$conditions['OR'] += array('ship_to_name LIKE'=>'%'.trim($name).'%');
	    $conditions['OR'] += array('ship_to_name LIKE'=>'%'.trim($this->cleanparameterreplacements($name)).'%');
		$shipto= $this->ShipTo->find('all', ['conditions'=>$conditions])->first();

		echo $shipto['shipping_address_1']." :|: ".$shipto['shipping_address_2'] ." :|: ".$shipto['shipping_city']." :|: ".$shipto['shipping_state']." :|: ".$shipto['shipping_country']." :|: ".$shipto['shipping_zipcode']." :|: ".$shipto['attention']." :|: ".$shipto['ship_to_name']." :|: ".$shipto['id'];
		exit;
	}

	public function getFacilityByID($id){
	    $codesearch =$this->request->query['search'];
	    $this->autoRender=false;
	    $conditions['OR']=array();
		$conditions['OR'] += array('id ='=>$id);
		$facility= $this->Facility->find('all', ['conditions'=>$conditions])->first();

		echo $facility['id']." :|: ".$facility['facility_name']." :|: ".$facility['facility_code']." :|: ".$facility['default_address']." :|: ".$facility['attention'];
		exit;
	}
    /**PPSACRUM-7 End **/
	

	/***PPSA-1 start */
	public function workOrder(){

		//get counts for all tabs

		$allOrders=$this->WorkOrders->find('all')->toArray();

		$statusCounts=array(
		    
		    'All Active' => 0,
		    
		    'All Orders' => 0,

			'Complete' => 0,

			'Returned' => 0,

			'Canceled' => 0,

			'Shipped' => 0,

			'Due Soon' => 0,

			'Past Due' => 0,

			'Needs Line Items' => 0

		);

		

		$allcompanies=$this->Customers->find('all',['order'=>['company_name'=>'asc']])->toArray();

		$this->set('allcompanies',$allcompanies);

		

		$allfabrics=$this->Fabrics->find('all',['order'=>['fabric_name'=>'asc','color'=>'asc']])->toArray();

		$this->set('allfabrics',$allfabrics);

		

		$allusers=$this->Users->find('all',['order'=>['last_name'=>'asc','first_name'=>'asc']])->toArray();

		$this->set('allusers',$allusers);

		

		foreach($allOrders as $order){

			$statusCounts[$order['status']]++;
			$statusCounts['All Orders']++;

            if($order['status'] == 'Pre-Production' || $order['status'] == 'Production'){
                $statusCounts['All Active']++;
            }

			if(($order['status'] == 'Pre-Production' || $order['status'] == 'Production') && $order['due'] < time() && $order['due'] >0){

				$statusCounts['Past Due']++;

			}

			if(($order['status'] == 'Pre-Production' || $order['status'] == 'Production') && $order['due'] > time() && $order['due'] <= (time()+432000)){

				$statusCounts['Due Soon']++;

			}
			

		}

		

		$this->set('statusCounts',$statusCounts);

	}

	public function getworkorderslist($tab='allactive'){

		$orders=array();

		$overallTotalRows=$this->WorkOrders->find('all',['conditions'=>['status NOT IN' => ['Shipped','Needs Line Items','Canceled']]])->count();

		$conditions=array();

		

		if(isset($this->request->data['start'])){

			$start=$this->request->data['start'];

		}else{

			$start=0;

		}

		

		if(isset($this->request->data['length'])){

			$limit=$this->request->data['length'];

		}else{

			$limit=25;

		}

		

		if(isset($this->request->data['draw'])){

			$draw=$this->request->data['draw'];

		}else{

			$draw=1;

		}

		

		

		if(isset($this->request->data['search']['value']) && strlen(trim($this->request->data['search']['value'])) >0){

			//this is a search

			$conditions['OR']=array();

			

			//look up customers

			$customerSearch=$this->Customers->find('all',['conditions'=>['company_name LIKE' => '%'.$this->request->data['search']['value'].'%']])->toArray();

			if(count($customerSearch) == 1){

				$conditions['OR'] += array('customer_id' => $customerSearch[0]['id']);

			}elseif(count($customerSearch) >1){

				$customerIDs=array();

				foreach($customerSearch as $customerRes){

					$customerIDs[]=$customerRes['id'];

				}

				$conditions['OR'] += array('customer_id IN' => $customerIDs);

			}

			

			

			$quoteIDfilter=array();

			

			//search quote titles

			$quoteSearch=$this->Quotes->find('all',['conditions' => ['title LIKE' => '%'.$this->request->data['search']['value'].'%']])->toArray();

			foreach($quoteSearch as $quoteRes){

				$quoteIDfilter[]=$quoteRes['id'];

			}

			

			//search quote numbers

			$quoteNumSearch=$this->Quotes->find('all',['conditions' => ['quote_number LIKE' => '%'.$this->request->data['search']['value'].'%']])->toArray();

			foreach($quoteNumSearch as $quoteRes){

				$quoteIDfilter[]=$quoteRes['id'];

			}

			

			if(count($quoteIDfilter) == 1){

				$conditions['OR'] += array('quote_id'=>$quoteIDfilter[0]);

			}elseif(count($quoteIDfilter) > 1){

				$conditions['OR'] += array('quote_id IN' => $quoteIDfilter);

			}

			

			

			$conditions['OR'] += array('po_number LIKE' => '%'.$this->request->data['search']['value'].'%');

			//$conditions['OR'] += array('order_number LIKE' => '%'.$this->request->data['search']['value'].'%');
			$conditions['OR'] += array('CAST(order_number AS CHAR(100)) LIKE' => '%'.$this->request->data['search']['value'].'%');

			$conditions['OR'] += array('facility LIKE' => '%'.$this->request->data['search']['value'].'%');

				

			//$conditions['OR'] += array('product_name LIKE'=>'%'.trim($this->request->data['search']['value']).'%');

		}

		

		

		switch($tab){

            case 'allactive':
                $conditions += array('status IN' => array('Pre-Production','Production','On Hold'));
            break;
            
            case 'allorders':
                
            break;
            
			case "canceled":
				$conditions += array('status' => 'Canceled');
			break;

			case "completed":
				$conditions += array('status' => 'Shipped');
			break;

			case "preproduction":
				$conditions += array('status' => 'Pre-Production');
			break;

			case "production":
				$conditions += array('status' => 'Production');
			break;

			case "duesoon":
				$conditions += array('status IN' => array('Pre-Production','Production'), 'due <=' => (time()+432000), 'due >' => time());
			break;

			case "pastdue":
				$conditions += array('status IN' => array('Pre-Production','Production'), 'due <=' => time(), 'due >' => 1000);
			break;

			case "postproduction":
				$conditions += array('status' => 'Complete');
			break;

			case "needlineitems":
				$conditions += array('status' => 'Needs Line Items');
			break;

			case "advancedsearch":


				$totalactiveparams=0;
				if(strlen(trim($this->request->data['identifiers'])) >0){
					//we are looking for identifiers
					$totalactiveparams++;
				}


				if(strlen(trim($this->request->data['quotenumber'])) > 0){
					//we are looking for a quote number
					
					//lookup quote numbers
					$quoteLookup=$this->Quotes->find('all',['conditions' => ['quote_number LIKE' => '%'.$this->request->data['quotenumber'].'%']])->toArray();
					$quoteIDs=array();
					foreach($quoteLookup as $quoteRow){
						$quoteIDs[]=$quoteRow['id'];
					}

					if(count($quoteIDs) > 1){
						$conditions += array('quote_id IN' => $quoteIDs);
						$totalactiveparams++;
					}elseif(count($quoteIDs) == 1){
						$conditions += array('quote_id' => $quoteIDs[0]);
						$totalactiveparams++;
					}
				}
				

				if(strlen(trim($this->request->data['ordernumber'])) >0){
					//we are looking for an order number
					$conditions += array('order_number LIKE' => '%'.$this->request->data['ordernumber'].'%');
					$totalactiveparams++;
				}
				

				if(strlen(trim($this->request->data['ponumber'])) >0){
					//we are looking for a PO Number
					$conditions += array('po_number LIKE' => '%'.$this->request->data['ponumber'].'%');
					$totalactiveparams++;
				}


				if(strlen(trim($this->request->data['daterangestart'])) >0){
					//we are looking for a date
					if(strlen(trim($this->request->data['daterangeend'])) >0){
						//we are looking for a date range
						$conditions += array('created >=' => strtotime($this->request->data['daterangestart'].' 00:00:00'));
						$conditions += array('created <=' => strtotime($this->request->data['daterangeend'].' 23:59:59'));
						$totalactiveparams++;
						$totalactiveparams++;
					}else{
						//we are just looking for a specific date
						$conditions += array('created >=' => strtotime($this->request->data['daterangestart'].' 00:00:00'));
						$conditions += array('created <=' => strtotime($this->request->data['daterangestart'].' 23:59:59'));
						$totalactiveparams++;
					}
				}

				
				if(is_array($this->request->data['customerid']) && count($this->request->data['customerid']) >1 ){
					//we are looking for a specific customer
					$conditions += array('customer_id IN' => $this->request->data['customerid']);
					$totalactiveparams++;
				}elseif(is_array($this->request->data['customerid']) && count($this->request->data['customerid']) ==1 ){
					//we are looking for a specific customer
					$conditions += array('customer_id' => $this->request->data['customerid'][0]);
					$totalactiveparams++;
				}


				if(strlen(trim($this->request->data['contactname'])) >0){
					//we are looking for items attributed to a specific contact within a customer
					$contactLookup=$this->CustomerContacts->find('all',['conditions' => ['CONCAT(first_name,\' \',last_name) LIKE' => '%'.$this->request->data['contactname'].'%']])->toArray();
					$contactIDs=array();
					foreach($contactLookup as $contactRow){
						$contactIDs[]=$contactRow['id'];
					}
					if(count($contactIDs) > 1){
						$conditions += array('contact_id IN' => $contactIDs);
						$totalactiveparams++;
					}elseif(count($contactIDs) == 1){
						$conditions += array('contact_id' => $contactIDs[0]);
						$totalactiveparams++;
					}
					
				}

				
				if(strlen(trim($this->request->data['phonenumber'])) >0){
					//we are looking for items attributed to a specific phone number
					//search Customers db
					$customerPhoneLookup=$this->Customers->find('all',['conditions' => ['phone LIKE' => '%'.$this->request->data['phonenumber'].'%']])->toArray();
					if(count($customerPhoneLookup) >0){
						//lets do conditions by customer id
						$customerIDs=array();
						foreach($customerPhoneLookup as $customerRow){
							$customerIDs[]=$customerRow['id'];
						}

						if(count($customerIDs) >1){
							$conditions += array('customer_id IN' => $customerIDs);
							$totalactiveparams++;
						}elseif(count($customerIDs) == 1){
							$conditions += array('customer_id' => $customerIDs[0]);
							$totalactiveparams++;
						}
						
					}else{

						//search Customer Contacts  db
						$contactPhoneLookup=$this->CustomerContacts->find('all',['conditions' => ['phone LIKE' => '%'.$this->request->data['phonenumber'].'%']])->toArray();
						if(count($contactPhoneLookup) > 0){
							//lets do conditions by contact id
							$contactIDs=array();
							foreach($contactPhoneLookup as $contactRow){
								$contactIDs[]=$contactRow['id'];
							}

							if(count($contactIDs) >1){
								$conditions += array("contact_id IN" => $contactIDs);
								$totalactiveparams++;
							}elseif(count($contactIDs) == 1){
								$conditions += array("contact_id" => $contactIDs[0]);
								$totalactiveparams++;
							}
							
						}
					}

					
				}

				

				if(strlen(trim($this->request->data['fabricids'])) >0){
					//we are looking for orders containing items with a specific fabric
					$totalactiveparams++;
				}

				
				if(strlen(trim($this->request->data['lineitemtype'])) >0){
					//we are looking for orders containing a specific type of line item
					
					if(strlen(trim($this->request->data['lineitemkeywords'])) >0){
						//we are looking for specific keywords within this specific type of line item
						$totalactiveparams++;
						$totalactiveparams++;
					}else{
						//we are just globally looking for this type of item
						$totalactiveparams++;
					}
				}

				
				if(strlen(trim($this->request->data['ordertotalmin'])) >0){
					//we are looking for orders with a specific total or total within a range
					if(strlen(trim($this->request->data['ordertotalmax'])) >0){
						//we are looking for a range
						$conditions += array('order_total >=' => floatval($this->request->data['ordertotalmin']));
						$totalactiveparams++;
						$conditions += array('order_total <=' => floatval($this->request->data['ordertotalmax']));
						$totalactiveparams++;
					}else{
						//we are looking for a specific total
						$conditions += array('order_total >=' => floatval($this->request->data['ordertotalmin']));
						$totalactiveparams++;
					}
				}

				

				if(is_array($this->request->data['userid']) && count($this->request->data['userid']) >1){
					//we ar elooking for orders processed by a specific HCI user
					$conditions += array('user_id IN' => $this->request->data['userid']);
					$totalactiveparams++;
				}elseif(is_array($this->request->data['userid']) && count($this->request->data['userid']) == 1){
					//we ar elooking for orders processed by a specific HCI user
					$conditions += array('user_id' => $this->request->data['userid'][0]);
					$totalactiveparams++;
				}



				if(count($this->request->data['orderstatus']) > 1){
					//filter the results to only include the selected Status(es)
					$conditions += array('status IN' => $this->request->data['orderstatus']);
					$totalactiveparams++;
				}elseif(count($this->request->data['orderstatus']) == 1){
					//filter the results to only include the selected Status(es)
					$conditions += array('status' => $this->request->data['orderstatus'][0]);
					$totalactiveparams++;
				}

				
				if(count($this->request->data['orderstage']) > 1){
				    $conditions += array('stage IN' => $this->request->data['orderstage']);
				    $totalactiveparams++;
				}elseif(count($this->request->data['orderstage']) == 1){
				    $conditions += array('stage' => $this->request->data['orderstage'][0]);
				    $totalactiveparams++;
				}
				
				//mail("dallasisp@gmail.com","raw data",print_r($conditions,1)."\n\n\n----------------------------------\n\n\n".print_r($_REQUEST,1));

			break;

			default:

				//$conditions += array('status NOT IN' => array('Canceled','Shipped','Needs Line Items'));
				//$conditions += array('status NOT IN' => array('Canceled','Shipped','Needs Line Items'));

			break;

		}

		

		

		if($tab=='advancedsearch' && $totalactiveparams==0){

			$getOrders=array();

			$totalFilteredRows=0;

		}else{
		    
		    
		    switch($this->request->data['order'][0]['column']){
		        case 1:
		        default:
		            $orderby=array('order_number' => $this->request->data['order'][0]['dir']);
		        break;
		        /*case 2:
		            $orderby=array('customer' => $this->request->data['order'][0]['dir']);
		        break;
		        */
		        case 3:
		            $orderby=array('po_number' => $this->request->data['order'][0]['dir']);
		        break;
		        case 4:
		            $orderby=array('created' => $this->request->data['order'][0]['dir']);
		        break;
		        case 9:
		            $orderby=array('due' => $this->request->data['order'][0]['dir']);
		        break;
		    }
		    

			$getOrders=$this->WorkOrders->find('all',['conditions'=>$conditions, 'order' => $orderby])->offset($start)->limit($limit)->hydrate(false)->toArray();

			$totalFilteredRows=$this->WorkOrders->find('all',['conditions'=>$conditions])->count();

		}

		

		foreach($getOrders as $order){

			

			

			//$customerData=$this->Customers->get($order['customer_id'])->toArray();
			$customerData=$this->Customers->find('all',['conditions'=>['id'=>$order['customer_id']]])->toArray();

			$userData=$this->Users->get($order['user_id'])->toArray();

			//$quoteData=$this->Quotes->get($order['quote_id'])->toArray();

			$quoteData=$this->Quotes->find('all',['conditions'=>['id'=>$order['quote_id']]])->toArray();

			
			

			

			$orderTitle='';

			if(strlen(trim($quote['title'])) >0){

				$orderTitle .= "<b>".$quote['title']."</b><br>";

			}

			$orderTitle .= $customerData[0]['company_name'];

			

			

			$numitems=0;

			$quoteitems=$this->QuoteLineItems->find('all',['conditions'=>['quote_id'=>$order['quote_id']]])->toArray();

			foreach($quoteitems as $quoteitem){

				$numitems=($numitems+$quoteitem['qty']);

			}

			

			$workorders='<a href="/orders/workorder/create/'.$order['id'].'/">+ Create W/O</a>';

			$shipments='';

			$invoices='';

			

			$orderstatus="<div class=\"orderlabel\">".ucfirst($order['status']);

			if($order['status'] == "Canceled"){
				$orderstatus .= "<br><em>REASON:<br>".$order['cancelreason']."</em>";
			}

			$addclasses='';
			
			if($order['status'] == 'Pre-Production' || $order['status'] == 'Production' || $order['status'] == 'On Hold'){
			    $addclasses .= " activeorder";
			}

			

			if($order['due'] > time() && $order['due'] <= (time()+432000)){

				$addclasses .= " duesoon";

				$orderstatus .= " (DUE SOON)";

			}

			if($order['due'] < time() && $order['due'] > 1000){

				$addclasses .= " pastdue";

				$orderstatus .= " (PAST DUE)";

			}

			if($order['status']=="On Hold"){

				$addclasses .= " onhold";

			}

			if($order['status']=="Complete" || $order['status'] == 'Shipped'){

				$addclasses .= " completed";

			}

			if($order['status'] == 'Canceled'){

				$addclasses .= " canceled";

			}

            if($order['status'] == 'Editing'){
                $addclasses .= ' editinglock';
            }
			

			$orderstatus .= "</div>";

			$completedpercent=$this->getOrderProgressPercent($order['id'],'workorder');

			$orderstatus .= "<div class=\"progressbar\"><div class=\"progresscompleted\" style=\"width:".$completedpercent['percent']."%;\"></div></div>

			<div class=\"percentlabel\">".$completedpercent['completed']." / ".$completedpercent['total']." (".$completedpercent['percent']."%) Completed</div>";

			

			$totals='';
            /*
			if($order['cc_dollar'] >0){

				$totals .= 'CC: $'.number_format($order['cc_dollar'],2,'.',',').'<br>';

			}

			if($order['track_dollar'] > 0){

				$totals .= 'TRK: $'.number_format($order['track_dollar'],2,'.',',').'<br>';

			}

			if($order['bs_dollar'] > 0){

				$totals .= 'BS: $'.number_format($order['bs_dollar'],2,'.',',').'<br>';

			}

			if($order['drape_dollar'] >0){

				$totals .= 'DRAPERIES: $'.number_format($order['drape_dollar'],2,'.',',').'<br>';

			}

			if($order['val_dollar'] >0){

				$totals .= 'VAL: $'.number_format($order['val_dollar'],2,'.',',').'<br>';

			}

			if($order['corn_dollar'] >0){

				$totals .= 'CORN: $'.number_format($order['corn_dollar'],2,'.',',').'<br>';

			}

			if($order['wt_hw_dollar'] >0){

				$totals .= 'WTHW: $'.number_format($order['wt_hw_dollar'],2,'.',',').'<br>';

			}

			if($order['blinds_dollar'] >0){

				$totals .= 'B&amp;S: $'.number_format($order['blinds_dollar'],2,'.',',').'<br>';

			}

			if($order['fab_dollars'] >0){

				$totals .= 'FAB: $'.number_format($order['fab_dollars'],2,'.',',').'<br>';

			}

			if($order['measure_dollars'] >0){

				$totals .= 'MEAS: $'.number_format($order['measure_dollars'],2,'.',',').'<br>';

			}

			if($order['install_dollars'] >0){

				$totals .= 'INST: $'.number_format($order['install_dollars'],2,'.',',').'';

			}
			*/
			// PPSASCRUM-183 start 
            if($order['hw_dollar_total'] >0){

				$totals .= 'HW : $'.number_format($order['hw_dollar_total'],2,'.',',').'<br>';

			}
            if($order['hwt_dollar_total'] >0){

				$totals .= 'HWT (B & S)  : $'.number_format($order['hwt_dollar_total'],2,'.',',').'<br>';

			}
           if($order['swt_dollar_total'] >0){

				$totals .= 'SWT : $'.number_format($order['swt_dollar_total'],2,'.',',').'<br>';

			}
             if($order['cc_dollar_total'] >0){

				$totals .= 'CC : $'.number_format($order['cc_dollar_total'],2,'.',',').'<br>';

			}
           if($order['bedding_dollar_total'] >0){

				$totals .= 'BEDDING : $'.number_format($order['bedding_dollar_total'],2,'.',',').'<br>';

			}
            if($order['services_dollar_total'] >0){

				$totals .= 'SERVICES : $'.number_format($order['services_dollar_total'],2,'.',',').'<br>';

			}
            if($order['misc_dollar_total'] >0){

				$totals .= 'MISC : $'.number_format($order['misc_dollar_total'],2,'.',',').'';

			}
			
			 // PPSASCRUM-183 end 

			$totals .= "<hr style=\"margin:0; height:4px;\" />";

			$totals .= '<strong>$'.number_format($order['order_total'],2,'.',',').'</strong>';

			

			if($order['due'] > 1000){

				$duedate=date('n/j/Y',$order['due']);

			}else{

				$duedate='---';

			}

			$projectname='';

			if($quoteData['project_id'] > 0){
				$projectData=$this->Projects->get($quoteData['project_id'])->toArray();
				$projectname=$quoteData['title'].'<br><span style="font-size:10px; color:blue;"><b>Project: '.$projectData['title'].'</b></span>';
			}else{
				$projectname=$quoteData['title'];
			}

			
			if($order['shipping_method_id'] > 0){
				$thisShipMethodData=$this->ShippingMethods->get($order['shipping_method_id'])->toArray();
				$thisShipMethod=$thisShipMethodData['name'];
			}else{
				$thisShipMethod='---';
			}
			
			
			$thisProjectManagerName='';
			
			if($order['project_manager_id'] > 0){
			    $thisPMUser=$this->Users->get($order['project_manager_id'])->toArray();
			    $thisProjectManagerName='<br>'.$thisPMUser['first_name'].' '.$thisPMUser['last_name'];
			}


			if($order['status'] == 'Needs Line Items'){

                if(!is_null($order['type_id']) && $order['type_id'] > 0){
				    $thisType=$this->QuoteTypes->get($order['type_id'])->toArray();
				    $thisOrderType='<br>'.$thisType['type_label'];
				}else{
				    $thisOrderType='';
				}

				$orders[]=array(

					'DT_RowId'=>'row_'.$order['id'],

					'DT_RowClass'=>'rowtest needlineitems'.$addclasses,

					'0' => '<a href="/orders/editlines/'.$order['id'].'/workorder"/><img src="/img/vieworder.png" alt="Line Items" title="Line Items" /></a><a href="/orders/vieworderschedule/'.$order['id'].'" target="_blank"><img src="/img/view.png" alt="Batch Breakdown" title="Batch Breakdown" /></a><a href="/orders/woboxes/'.$order['id'].'" target="_blank"><img src="/img/box-icon.png" width="24" alt="Work Order Boxes" title="Work Order Boxes" /></a>',

					'1' => $order['order_number'],

					'2' => $orderTitle,

					'3' => $order['po_number'].$thisProjectManagerName,

					'4' => date('n/j/Y - g:iA',$order['created']),

					'5' => $projectname,

					'6' => $order['facility'].$thisOrderType,

					'7' => 'No Line Items Entered',
					
					'8' => $order['stage'],

					'9' => $duedate,

					'10' => $thisShipMethod,

					'11' => '---',

					'12' => '---',

					'13' => '---',

					'14' => '---',

					'15' => '---',

					'16' => '---',

					'17' => '---',
					
					'18' => '---',
					
					'19' => '---',

					'20' => '---',

					'21' => '---',

					'22' => '---',

					'23' => '---'

				);

			}else{

				if($order['status'] == 'Canceled'){
					$actionsout='<em>N/A</em>';
				}else{

					/*see if this has already been imported to qb
					$qblogcheck=$this->QbImportLog->find('all',['conditions'=>['order_id' => $order['id'],'result'=>'success']])->toArray();

					if(count($qblogcheck) == 0){
						$ifsendtoqb=' <a href="/orders/sendtoquickbooks/'.$order['id'].'"><img src="/img/qb3.png" width="16" height="16" title="Export to Quickbooks" alt="Export to Quickbooks" /></a>';
					}else{
						$ifsendtoqb='';
					}
                    
                    
                    if($order['status'] == 'Shipped'){
                        $ifmarkcompleted=' <a href="/orders/revertcompletion/'.$order['id'].'"><img src="/img/Undo-icon.png" alt="Revert Order to In-Production" title="Revert Order to In-Production" /></a>';
                    }else{
                        $ifmarkcompleted=' <a href="/orders/markcomplete/'.$order['id'].'"><img src="/img/completed.png" alt="Mark Order Completed" title="Mark Order Completed" /></a>';
                    }*/
                    
					$actionsout = '<a href="/orders/woeditlines/'.$order['id'].'"/><img src="/img/vieworder.png" alt="Line Items" title="Line Items" /></a><a href="/orders/vieworderschedule/'.$order['id'].'" target="_blank"><img src="/img/view.png" alt="Batch Breakdown" title="Batch Breakdown" /></a>  <a href="/orders/woboxes/'.$order['id'].'" target="_blank"><img src="/img/box-icon.png" width="24" alt="Work Order Boxes" title="Work Order Boxes" /></a>';
				}


                if(!is_null($order['type_id']) && $order['type_id'] > 0){
				    $thisType=$this->QuoteTypes->get($order['type_id'])->toArray();
				    $thisOrderType='<br>'.$thisType['type_label'];
				}else{
				    $thisOrderType='';
				}
                
				
				$orders[]=array(

					'DT_RowId'=>'row_'.$order['id'],

					'DT_RowClass'=>'rowtest'.$addclasses,

					'0' => $actionsout,

					'1' => $order['order_number'],

					'2' => $orderTitle,

					'3' => $order['po_number'].$thisProjectManagerName,

					'4' => date('n/j/Y - g:iA',$order['created']),

					'5' => $projectname,

					'6' => $order['facility'].$thisOrderType,

					'7' => $orderstatus,
					
					'8' => $order['stage'],

					'9' => $duedate,

					'10' => $thisShipMethod,


					'11' => $order['cc_qty'],

					'12' => $order['cc_lf'],

					'13' => $order['track_lf'],

					'14' => $order['bs_qty'],

					'15' => $order['drape_qty'],

					'16' => $order['drape_widths'],

					'17' => $order['val_qty'],

					'18' => $order['val_lf'],
					
					'19' => $order['corn_qty'],
					
					'20' => $order['corn_lf'],

					'21' => $order['wt_hw_qty'],

					'22' => $order['blinds_qty'],

					'23' => $shipments

				);

			}

		}

		$this->set('draw',$draw);

		$this->set('recordsTotal',$overallTotalRows);

		$this->set('recordsFiltered',$totalFilteredRows);

		$this->set('data',$orders);

		$this->set('_serialize',['draw','recordsTotal','recordsFiltered','data']);
	}

	public function woview($orderID){
	    $thisOrder=$this->WorkOrders->find('all', ['conditions'=>['id'=>$orderID]])->first()->toArray();
		$this->set('orderData',$thisOrder);
		
		$thisOrderQuote=$this->Quotes->find('all', ['conditions'=>['id'=>$thisOrder['quote_id']]])->first()->toArray();
		$this->set('quoteData',$thisOrderQuote);
		
		$customerData=$this->Customers->find('all', ['conditions'=>['id'=>$thisOrder['customer_id']]])->first()->toArray();

		$this->set('customerData',$customerData);
		$this->set('quoteID',$thisOrder['quote_id']);
		
		$availableProjects=$this->Projects->find('all',['conditions'=>['customer_id' => $thisOrderQuote['customer_id']]])->toArray();
		$this->set('availableProjects',$availableProjects);
	
		if($thisOrderQuote['contact_id'] == '0'){
			$customerContact=false;
		}else{
			$customerContact=$this->CustomerContacts->find('all',['conditions'=>['id' => $thisOrder['contact_id']]])->first()->toArray();
		}
    
		$this->set('customerContact',$customerContact);
    
		$allcalculators=$this->Calculators->find('all')->toArray();

		$this->set('allcalculators',$allcalculators);

        $allTypes=$this->QuoteTypes->find('all')->toArray();
        $this->set('allTypes',$allTypes);
        
	}
	
	public function woedit($orderID){
		
		if($this->request->data){
			
			$ordersTable=TableRegistry::get('WorkOrders');
			$thisOrder=$this->WorkOrders->find('all', ['conditions' => ['id'=>$orderID]])->first();

			$thisOrder->po_number = $this->request->data['po_number'];
			
			$thisOrder->type_id = $this->request->data['type_id'];
			$thisOrder->shipping_instructions = $this->request->data['shipping_instructions'];
			$thisOrder->shipping_method_id = $this->request->data['shipping_method_id'];
			/**PPSASCRUM-7 Start **/
			/*
			$thisOrder->facility = $this->request->data['facility'];
			$thisOrder->attention = $this->request->data['attention'];
			$thisOrder->shipping_address_1 = $this->request->data['shipping_address_1'];
			$thisOrder->shipping_address_2 = $this->request->data['shipping_address_2'];
			$thisOrder->shipping_city = $this->request->data['shipping_city'];
			$thisOrder->shipping_state = $this->request->data['shipping_state'];
			$thisOrder->shipping_zipcode = $this->request->data['shipping_zipcode'];
			$thisOrder->shipping_instructions = $this->request->data['shipping_instructions'];
			$thisOrder->shipping_method_id = $this->request->data['shipping_method_id'];
			*/
			$thisOrder->attention=$this->request->data['attention'];

			$thisOrder->facility = $this->request->data['allfacility'];
			$thisOrder->shipto_id = $this->request->data['addressSelected'];
			$thisOrder->facility_id = $this->request->data['facilitySelected'];
			$thisOrder->userType = $this->request->data['userAddressesSelection'];
			if($this->request->data['userAddressesSelection'] == 'default') {
				$faciltiyTable=TableRegistry::get('Facility');
				$thisfaciltiy=$faciltiyTable->get($this->request->data['facilitySelected'])->toArray();;
				$thisOrder->shipto_id = $thisfaciltiy['default_address'];
				$shipID= $thisfaciltiy['default_address'];
				
			}else if($this->request->data['userAddressesSelection'] == 'exisiting') {
				$thisOrder->shipto_id = $this->request->data['addressSelected'];
				$shipID= $this->request->data['addressSelected'];
			}else if($this->request->data['userAddressesSelection'] == 'customer') {
			    $thisCustomer=$this->Customers->find('all',['conditions'=>['id'=>$thisOrder['customer_id']]])->first()->toArray();
            	$thisOrder->shipping_address_1=$thisCustomer['shipping_address'];
    
    			$thisOrder->shipping_address_2='';
    
    			$thisOrder->shipping_city=$thisCustomer['shipping_address_city'];
    
    			$thisOrder->shipping_state=$thisCustomer['shipping_address_state'];
    
    			$thisOrder->shipping_zipcode=$thisCustomer['shipping_address_zipcode'];
    
    			$thisOrder->attention=$this->request->data['attention'];
    			$thisOrder->shipping_country=$thisCustomer['shipping_address_country'];
    			$shipID ='';
			}

            if(!empty($shipID)) {
                $shipToTable = TableRegistry::get('ShipTo');
    			$shipToDetails = $shipToTable->get($thisOrder->shipto_id);
    			$thisOrder->shipping_address_1=$shipToDetails->shipping_address_1;
    
    			$thisOrder->shipping_address_2=$shipToDetails->shipping_address_2;
    
    			$thisOrder->shipping_city=$shipToDetails->shipping_city;
    
    			$thisOrder->shipping_state=$shipToDetails->shipping_state;
    
    			$thisOrder->shipping_zipcode=$shipToDetails->shipping_zipcode;
    
    			$thisOrder->attention=$this->request->data['attention'];
    			$thisOrder->shipping_country=$shipToDetails->shipping_country;
    			//die(print_r($shipToDetails));
           }

			if(!empty($this->request->data['facilityAttention']) && !empty($this->request->data['facilitySelected'])) {
				$faciltiyTable=TableRegistry::get('Facility');
				$thisfaciltiy=$faciltiyTable->get($this->request->data['facilitySelected']);
				$thisfaciltiy->attention=$this->request->data['facilityAttention'];
				$faciltiyTable->save($thisfaciltiy);
				$thisOrder->facility = $thisfaciltiy->facility_name;
			    //$thisOrder->attention = $this->request->data['facilityAttention'];

			}
			/*if(!empty($this->request->data['attentionship'])  && !empty($shipID)) {
				$shipToTable=TableRegistry::get('ShipTo');
				$thisshiptp=$shipToTable->get($shipID);
				$thisshiptp->attention=$this->request->data['attentionship'];
				$shipToTable->save($thisshiptp);

			}*/
			/**PPSASCRUM-7 end **/
			$thisOrder->stage = $this->request->data['order_stage'];
			
			$thisOrder->project_manager_id = $this->request->data['project_manager_id'];
			
			if($this->request->data['hasduedate'] == '1'){

				$thisOrder->due=strtotime($this->request->data['due'].' 15:30:00');

			}else{
			    $thisOrder->due=0;
			}
			
			$thisOrder->created=strtotime($this->request->data['created'].' 11:00:00');
			
			
			
			if($ordersTable->save($thisOrder)){
			    
			    
			    //find any sherry batches for this order and update their cache dates
    			$batchLookup=$this->SherryCache->find('all',['conditions'=>['order_id' => $orderID]])->toArray();
    			foreach($batchLookup as $batchCacheRow){
    			    
    			    $this->updatesherrycachefordate($batchCacheRow['date']);
    			    
    			}
			    
			    
				$this->Flash->success('Successfully saved changes to WorkOrder '.$thisOrder->order_number.' Details');
				$this->logActivity($_SERVER['REQUEST_URI'],'Edited Workorder '.$thisOrder->order_number.' details');
				return $this->redirect('/orders/workOrder/');
			}
			
		}else{
			$thisOrder=$this->WorkOrders->get($orderID)->toArray();
			$this->set('orderData',$thisOrder);

			$allShippingMethods = $this->ShippingMethods->find('all')->toArray();
			$availableShippingMethods=array();
			foreach($allShippingMethods as $method){
				$availableShippingMethods[$method['id']]=$method['name'];
			}
			$this->set('availableShippingMethods',$availableShippingMethods);
			
			$allUsers=$this->Users->find('all',['conditions' => ['status'=>'Active'],'order'=>['last_name'=>'asc','first_name'=>'asc']])->toArray();
			$this->set('allUsers',$allUsers);
			
			$allTypes=$this->QuoteTypes->find('all')->toArray();
            $this->set('allTypes',$allTypes);
             /**PPSASCRUM-7 start **/
            $thisCustomer=$this->Customers->find('all',['condtions'=>['id'=>$thisOrder['customer_id']]])->first()->toArray();
			$this->set('customerData',$thisCustomer);
			
			
            $allFaclity=$this->Facility->find('list',['keyField' => 'id', 'valueField' => 'facility_name', 'conditions' => [],'order'=>['facility_name'=>'asc']])->toArray();
			$this->set('allFacility',$allFaclity);

			$shipTooptions=$this->ShipTo->find('list',['keyField' => 'id', 'valueField' => 'shipping_address_1', 'conditions' => [],'order'=>['shipping_address_1'=>'asc']])->toArray();
			$this->set('shipTooptions',$shipTooptions);
            /**PPSASCRUM-7 end **/

		}
		
	}
	
	
	public function generateworkorderform($orderID){
		$thisOrder=$this->WorkOrders->get($orderID)->toArray();
		$this->set('orderData',$thisOrder);
		
		$this->set('quoteID',$thisOrder['quote_id']);
		
		$this->viewBuilder()->layout('iframeinner');		
	}
	
	public function generatequoteform($quoteID,$ordermode,$quote_number,$revision){
		$this->set('quoteID',$quoteID);
		
		$this->set('ordermode',$ordermode);
		$this->set('quote_number',$quote_number);
		
		$this->set('revision',$revision);
		
		$this->viewBuilder()->layout('iframeinner');		
	}
	
	
	
	public function buildworkorderpdf($orderID, $types = "all")
    {
        $this->set("types", $types);

//        $thisOrder = $this->WorkOrders->get($orderID)->toArray();
		$thisOrder = $this->WorkOrders->find("all", [ "conditions" => ["id" => $orderID],"order" => ["id" => "asc"],])->first()->toArray();
        $quoteID = $thisOrder["quote_id"];

        $this->set("orderData", $thisOrder);
$thisProjectManagerName='';
			
			if($thisOrder['project_manager_id'] > 0){
			    $thisPMUser=$this->Users->get($thisOrder['project_manager_id'])->toArray();
			    $thisProjectManagerName='<br>'.$thisPMUser['first_name'].' '.$thisPMUser['last_name'];
			}

        $settings = $this->getsettingsarray();

        $this->set("allsettings", $settings);

        $allFabrics = $this->Fabrics->find("all")->toArray();

        $this->set("allFabrics", $allFabrics);

        //$thisQuote = $this->Quotes->get($quoteID)->toArray();
		//echo $quoteID;
		$thisQuote = $this->Quotes->find("all", [ "conditions" => ["id" => $quoteID],"order" => ["id" => "asc"],])->first()->toArray();
		//print_r($thisQuote);

        $quoteAuthorData = $this->Users
            ->get($thisQuote["created_by"])
            ->toArray();

        $this->set("quoteAuthorData", $quoteAuthorData);

        $allUsers = [];
        $getAllUsers = $this->Users->find()->toArray();
        foreach ($getAllUsers as $userRow) {
            $allUsers[$userRow["id"]] = $userRow;
        }

        $this->set("allUsers", $allUsers);
		$globalNotes=[];
		$globalNotes=$this->QuoteNotes->find("all",["conditions" => ["quote_id" => $quoteID]])->toArray();

	   //die(print_r($globalNotes));	
	   if(!empty($globalNotes ))
         $this->set("globalNotes", $globalNotes); 
		else 
		 $this->set("globalNotes", []); 


        $allTrackComponents = [];

        $allTrackComponentsFind = $this->TrackSystems
            ->find("all", ["conditions" => ["status" => "Active"]])
            ->toArray();

        foreach ($allTrackComponentsFind as $componentRow) {
            $allTrackComponents[$componentRow["id"]] = [
                "title" => $componentRow["title"],

                "description" => $componentRow["description"],

                "price" => $componentRow["price"],

                "unit" => $componentRow["unit"],

                "inches_equivalent" => $componentRow["inches_equivalent"],

                "system_or_component" => $componentRow["system_or_component"],
            ];
        }

        $this->set("componentsList", $allTrackComponents);

        $allimages = [];
		$db = ConnectionManager::get('default');
		$query = "SELECT * from library_images ";
		$queryRun = $db->execute($query);
            $allimageslookup = $queryRun->fetchAll('assoc');
		//$allimageslookup=$this->LibraryImages->find('all');

		if(!empty($allimageslookup)){
        foreach ($allimageslookup as $imagedata) {
            $allimages[$imagedata["id"]] = $imagedata;
        }
	}

        $this->set("allimages", $allimages);

        $allLinings = [];

        $allLiningLookups = $this->Linings->find("all")->toArray();

        foreach ($allLiningLookups as $lining) {
            $allLinings[$lining["id"]] = $lining["short_title"];
        }

        $this->set("alllinings", $allLinings);

        $GLOBALS["pdfheader"] =
            "<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\">

<tr>

<td width=\"11%\" valign=\"top\" align=\"left\"><img src=\"" .
            $_SERVER["DOCUMENT_ROOT"] .
            "/webroot/img/android-chrome-144x144.png\" width=\"65\" /></td>
<td width=\"34%\" valign=\"top\" algin=\"left\"><span style=\"font-size:9px; font-family:'Metropolis'; color:#004A87;\"><span style=\"font-family:'Metropolis Semi Bold';\">HC INTERIORS</span><br>" .
            $settings["hci_address_line_1"] .
            ". " .
            $settings["hci_address_line_2"] .
            "<br>" .
            $settings["hci_address_city"] .
            ", " .
            $settings["hci_address_state"] .
            " " .
            $settings["hci_address_zipcode"] .
            "<Br>Phone: " .
            $settings["hci_phone_number"] .
            "  <span style=\"font-family:'Helvetica';\">&middot;</span>  Fax: " .
            $settings["hci_fax"] .
            "<br>E-mail: <span style=\"color:blue;\">" .
            $quoteAuthorData["email"] .
            "</span></span></td>

<td width=\"20%\" valign=\"top\" align=\"center\" style=\"font-size:9px;\">";

        if ($thisQuote["status"] == "draft") {
            $GLOBALS["pdfheader"] .=
                "<img src=\"" .
                $_SERVER["DOCUMENT_ROOT"] .
                "/webroot/img/pdf-preview.png\" width=\"200\" />";
        } else {
            $GLOBALS["pdfheader"] .= "&nbsp;";
        }

        $GLOBALS["pdfheader"] .=
            "</td>

<td width=\"35%\" valign=\"top\" align=\"right\" style=\"font-size:9px;\">

<table width=\"100%\" cellpadding=\"3\" border=\"1\" bordercolor=\"#000000\" cellspacing=\"0\" align=\"right\">

	<tr>

		<td style=\"font-size:9px;\" width=\"35%\" align=\"left\" bgcolor=\"#E4EEFA\" style=\"font-size:9px;\">Date:</td>

		<td style=\"font-size:9px;\" width=\"65%\" align=\"left\" style=\"font-size:9px;\">" .
            date("n/j/Y", $thisOrder["created"]) .
            "</td>

	</tr>

	<tr>

		<td style=\"font-size:8px;\" width=\"35%\" align=\"left\" bgcolor=\"#E4EEFA\" style=\"font-size:9px;\">Work Order #:</td>

		<td style=\"font-size:8px;\" width=\"65%\" align=\"left\" style=\"font-size:9px;\">" .
            $thisOrder["order_number"] .
            "</td>

	</tr>
<tr>

		<td width=\"35%\" align=\"left\" bgcolor=\"#E4EEFA\" style=\"font-size:9px;\">Project Manger:</td>

		<td width=\"65%\" align=\"left\" style=\"font-size:9px;\">" .
            strtoupper($thisProjectManagerName) .
            "</td>

	</tr>
	<tr>

		<td width=\"35%\" align=\"left\" bgcolor=\"#E4EEFA\" style=\"font-size:9px;\">Project:</td>

		<td width=\"65%\" align=\"left\" style=\"font-size:9px;\">" .
            strtoupper($thisQuote["title"]) .
            "</td>

	</tr>

	<tr>

		<td width=\"35%\" align=\"left\" bgcolor=\"#E4EEFA\" style=\"font-size:9px;\">Contains:</td>

		<td width=\"65%\" align=\"left\" style=\"font-size:9px;\">";

        $thisQuoteTypes = $this->getproducttypeslist($thisQuote["id"]);

        $contains = "";

        foreach ($thisQuoteTypes as $type) {
            $contains .= $type . ", ";
        }

        $GLOBALS["pdfheader"] .= substr($contains, 0, strlen($contains) - 2);

        $GLOBALS["pdfheader"] .= "</td>

	</tr>

</table>

</td></tr></table>";

        $thisQuoteItems = $this->WorkOrderLineItems
            ->find("all", [
                "conditions" => ["quote_id" => $quoteID],
                "order" => ["sortorder" => "asc", "id" => "asc"],
            ])
            ->toArray();
            

        $customerData = $this->Customers
            ->get($thisQuote["customer_id"])
            ->toArray();

        if ($thisQuote["contact_id"] > 0) {
            $contactData = $this->CustomerContacts
                ->get($thisQuote["contact_id"])
                ->toArray();
        } else {
            $contactData = [];
        }

        $quoteItems = [];

        foreach ($thisQuoteItems as $quoteItem) {
            
            
            $lineNotes = [];
            $notes = $this->WorkOrderLineItemNotes->find('all', [
                "conditions" => ["quote_item_id" => $quoteItem['id']],
            
            ]);
            foreach($notes as $note){
                $lineNotes[$note['id']] =$note;
            }
            
            $quoteItems[$quoteItem["id"]] = [
                "title" => $quoteItem["title"],

                "description" => $quoteItem["description"],

                "best_price" => $quoteItem["best_price"],

                "tier_adjusted_price" => $quoteItem["tier_adjusted_price"],

                "install_adjusted_price" =>
                    $quoteItem["install_adjusted_price"],

                "rebate_adjusted_price" => $quoteItem["rebate_adjusted_price"],

                "override_price" => $quoteItem["override_price"],

                "extended_price" => $quoteItem["extended_price"],

                "qty" => $quoteItem["qty"],

                "line_item_type" => $quoteItem["line_item_type"],

                "room_number" => $quoteItem["room_number"],

                "line_number" => $quoteItem["line_number"],
                 "wo_line_number" => $quoteItem["wo_line_number"],

                "parent_line" => $quoteItem["parent_line"],

                "sortorder" => $quoteItem["sortorder"],

                "internal_line" => $quoteItem["internal_line"],

                "product_type" => $quoteItem["product_type"],
                 "product_subclass" => $quoteItem["product_subclass"],

                "status" => $quoteItem["status"],

                "unit" => ucfirst($quoteItem["unit"]),

                "primarykey" => $quoteItem["id"],

                "metadata" => [],

                "imagesrc" => "",

                "notesdata" => $lineNotes,
            ];
		
			
			$thisItemMetas = $this->WorkOrderLineItemMeta
            ->find("all", [
                "conditions" => ["worder_item_id" => $quoteItem["id"]],
				"order" => [],
            ])
            ->toArray();
           $thisOrderItemLookup = $this->WorkOrderItems
                ->find("all", [
                    "conditions" => ["order_id" => $quoteItem["work_id"]],
                ])
                ->toArray();
            foreach ($thisItemMetas as $itemMeta) {
                //see if this Order Item has overriding meta
                if (count($thisOrderItemLookup) == 0) {
                    $quoteItems[$quoteItem["id"]]["metadata"][
                        $itemMeta["meta_key"]
                    ] = $itemMeta["meta_value"];
                } else {
                    foreach ($thisOrderItemLookup as $thisOrderItem) {
                        //lookup matching meta key
                        $orderItemMetaLookup = $this->WorkOrderItemMeta
                            ->find("all", [
                                "conditions" => [
                                    "order_item_id" => $thisOrderItem["id"],
                                    "meta_key" => $itemMeta["meta_key"],
                                ],
                            ])
                            ->toArray();
                        if (count($orderItemMetaLookup) == 0) {
                            $quoteItems[$quoteItem["id"]]["metadata"][
                                $itemMeta["meta_key"]
                            ] = $itemMeta["meta_value"];
                        } else {
                            foreach ($orderItemMetaLookup as $orderItemMeta) {
                                $quoteItems[$quoteItem["id"]]["metadata"][
                                    $itemMeta["meta_key"]
                                ] = $orderItemMeta["meta_value"];
                            }
                        }
                    }
                }
            }

            if (
                !isset(
                    $quoteItems[$quoteItem["id"]]["metadata"]["libraryimageid"]
                ) || 
                    $quoteItems[$quoteItem["id"]]["metadata"]["libraryimageid"].length== 0 ||
                $quoteItems[$quoteItem["id"]]["metadata"]["libraryimageid"] == 0
            ) {
                $quoteItems[$quoteItem["id"]]["imagesrc"] =
                    $_SERVER["DOCUMENT_ROOT"] . "/webroot/img/noimage.png";
            } else {
              
                $libraryImage = $this->LibraryImages
                    ->get(
                        $quoteItems[$quoteItem["id"]]["metadata"][
                            "libraryimageid"
                        ]
                    )
                    ->toArray();
                   

                $quoteItems[$quoteItem["id"]]["imagesrc"] =
                    $_SERVER["DOCUMENT_ROOT"] .
                    "/webroot/img/library/" .
                    $libraryImage["filename"];
            
            }

            if (
                isset($quoteItems[$quoteItem["id"]]["metadata"]["fabricid"]) &&
                floatval(
                    $quoteItems[$quoteItem["id"]]["metadata"]["fabricid"]
                ) > 0
            ) {
                $thisFabric = $this->Fabrics
                    ->get($quoteItems[$quoteItem["id"]]["metadata"]["fabricid"])
                    ->toArray();

                $quoteItems[$quoteItem["id"]]["fabricdata"] = $thisFabric;
            }
        }
        $this->set("quoteData", $thisQuote);

        $this->set("quoteItems", $quoteItems);

        $this->set("customerData", $customerData);

        $this->set("contactData", $contactData);

        $this->viewBuilder()->options([
            "pdfConfig" => [
                "orientation" => "landscape",

                "filename" => "HCI_Quote_" . $quoteID,

                "title" => "HCI Quote " . $quoteID,
            ],
        ]);
    }

	public function changelinetally($lineid,$newvalue, $mode='order'){

		$this->autoRender=false;
		if($mode == 'workorder'){
			$lineTable=TableRegistry::get('WorkOrderLineItems');
			$thisLine=$lineTable->get($lineid);//find('all', ['conditions' => ['id'=>$lineid]])->first() ;

		}
else{
	$lineTable=TableRegistry::get('OrderLineItems');

		$thisLine=$lineTable->get($lineid);//->find('all', ['conditions' => ['id'=>$lineid]])->first() ;
	}
		$thisLine->enable_tally=$newvalue;

		$lineTable->save($thisLine);

		echo "OK";

	}
	public function woeditlines($orderid){

		$this->autoRender=false;

		$this->set('urlparams',$this->request);
		$thisOrder=$this->Orders->get($orderid)->toArray();

		//$thisOrder=$this->WorkOrders->get($orderid)->toArray();
		$thisOrder=$this->WorkOrders->find('all', ['conditions' => ['id'=>$orderid]])->first()->toArray();
		if($thisOrder['status'] == 'Editing'){
		
		    $this->set('mode','editorderlines');
		    
		    $lookupNewQuote=$this->Quotes->find('all',['conditions' => ['order_id' => $orderid, 'status' => 'editorder']])->toArray();
		    $this->set('orderData',$thisOrder);
		    foreach($lookupNewQuote as $thisQuoteData){
		        $thisOrderQuote=$thisQuoteData;
		        $this->set('quoteData',$thisQuoteData);
		        $newQuote=$thisQuoteData;
		        $this->set('quoteID',$thisQuoteData['id']);
		    }

		    
		    $customerData=$this->Customers->get($thisOrderQuote['customer_id'])->toArray();
    		$this->set('customerData',$customerData);
    		
    		
    		
    		$availableProjects=$this->Projects->find('all',['conditions'=>['customer_id' => $thisOrderQuote['customer_id']]])->toArray();
    		$this->set('availableProjects',$availableProjects);
    		if($thisOrderQuote['contact_id'] == '0'){
    			$customerContact=false;
    		}else{
				$customerContact = $this->CustomerContacts->find('all',['conditions'=>['id' => $thisOrderQuote['contact_id']]])->toArray();
    			//$customerContact=$this->CustomerContacts->get($thisOrderQuote['contact_id'])->toArray();
    		}
    
    		$this->set('customerContact',$customerContact);
    
    		$allcalculators=$this->Calculators->find('all')->toArray();
    
    		$this->set('allcalculators',$allcalculators);
    
    		$allTypes=$this->QuoteTypes->find('all')->toArray();
            $this->set('allTypes',$allTypes);
            
            
            
            //determine if the changes are within the rules
            $ruleErrors=array();
            
            $oldQuote=$this->Quotes->get($thisOrder['quote_id'])->toArray();
            
            $oldQuoteLines=$this->QuoteLineItems->find('all',['conditions' => ['quote_id' => $oldQuote['id'], 'enable_tally'=>1],'order'=>['sortorder'=>'asc']])->toArray();
            
            $newQuoteLines=$this->QuoteLineItems->find('all',['conditions' => ['quote_id' => $newQuote['id'], 'enable_tally'=>1],'order'=>['sortorder'=>'asc']])->toArray();
            
            $orderLines=$this->WorkOrderItems->find('all',['conditions' => ['order_id' => $orderid]])->toArray();
            
            $totals=array();
            
            foreach($orderLines as $orderLine){
                $totals[$orderLine['id']]=array('LineNumber' => '','oldItemID'=>0,'newItemID'=>0, 'Scheduled'=>0,'Produced'=>0,'Shipped'=>0,'Invoiced'=>0);
                
                //get the QTYs Scheduled
                $totalScheduledThisLine=0;
                $numScheduledLookup=$this->WorkOrderItemStatus->find('all',['conditions' => ['order_item_id' => $orderLine['id'], 'status' => 'Scheduled']])->toArray();
                foreach($numScheduledLookup as $scheduledEntry){
                    $totalScheduledThisLine=($totalScheduledThisLine + intval($scheduledEntry['qty_involved']));
                }
                $totals[$orderLine['id']]['Scheduled']=$totalScheduledThisLine;
                
                //get the QTYs Produced
                $totalProducedThisLine=0;
                $numProducedLookup=$this->WorkOrderItemStatus->find('all',['conditions' => ['order_item_id' => $orderLine['id'], 'status' => 'Completed']])->toArray();
                foreach($numProducedLookup as $producedEntry){
                    $totalProducedThisLine=($totalProducedThisLine + intval($producedEntry['qty_involved']));
                }
                $totals[$orderLine['id']]['Produced']=$totalProducedThisLine;
                
                
                //get the QTYs Shipped
                $totalShippedThisLine=0;
                $numShippedLookup=$this->WorkOrderItemStatus->find('all',['conditions' => ['order_item_id' => $orderLine['id'], 'status' => 'Shipped']])->toArray();
                foreach($numShippedLookup as $shippedEntry){
                    $totalShippedThisLine=($totalShippedThisLine + intval($shippedEntry['qty_involved']));
                }
                $totals[$orderLine['id']]['Shipped']=$totalShippedThisLine;
                
                //get the QTYs Invoiced
                $totalInvoicedThisLine=0;
                $numInvoicedLookup=$this->WorkOrderItemStatus->find('all',['conditions' => ['order_item_id' => $orderLine['id'], 'status' => 'Invoiced']])->toArray();
                foreach($numInvoicedLookup as $invoicedEntry){
                    $totalInvoicedThisLine=($totalInvoicedThisLine + intval($invoicedEntry['qty_involved']));
                }
                $totals[$orderLine['id']]['Invoiced']=$totalInvoicedThisLine;
            
                //determine the QTYs not scheduled
                
                $thisOldQuoteLine=$this->QuoteLineItems->get($orderLine['quote_line_item_id'])->toArray();
                $oldLineItemMetas=$this->QuoteLineItemMeta->find('all',['conditions' => ['quote_item_id' => $orderLine['quote_line_item_id']]])->toArray();
                $oldLineItemMetaArray=array();
    			foreach($oldLineItemMetas as $lineItemMetaRow){
    				$oldLineItemMetaArray[$lineItemMetaRow['meta_key']]=$lineItemMetaRow['meta_value'];
    			}
                
                
                $totals[$orderLine['id']]['LineNumber']=$thisOldQuoteLine['line_number'];
                $totals[$orderLine['id']]['oldItemID']=$thisOldQuoteLine['id'];
                
                
                foreach($newQuoteLines as $newLine){
                    //if($newLine['line_number'] == $thisOldQuoteLine['line_number']){
                    if($newLine['revised_from_line'] == $thisOldQuoteLine['id']){
                        
                        $newLineItemMetas=$this->QuoteLineItemMeta->find('all',['conditions' => ['quote_item_id' => $newLine['id']]])->toArray();
                        $newLineItemMetaArray=array();
            			foreach($newLineItemMetas as $lineItemMetaRow){
            				$newLineItemMetaArray[$lineItemMetaRow['meta_key']]=$lineItemMetaRow['meta_value'];
            			}
                        
                        $totals[$orderLine['id']]['newItemID']=$newLine['id'];
                        
                        if($thisOldQuoteLine['qty'] == $totals[$orderLine['id']]['Produced'] && $newLine['qty'] < $thisOldQuoteLine['qty']){
                            $ruleErrors[]='<img src="/img/delete.png" /> <em>Line '.$newLine['line_number'].'</em> CANNOT PROCEED: Line fully produced. Qty cannot be decreased below '.$thisOldQuoteLine['qty'].'<br><small>Consider altering the Price Ea to obtain the expected Ext Price and adding a line note detailing the reason</small>';
                        }else{
                        
                            if($newLine['qty'] < $thisOldQuoteLine['qty'] && $newLine['qty'] < $totalScheduledThisLine){
                                $ruleErrors[]='<img src="/img/delete.png" /> <em>Line '.$newLine['line_number'].'</em> CANNOT PROCEED: New Qty ('.$newLine['qty'].') smaller than Already Batched Qty ('.$totalScheduledThisLine.')';
                            }
                        }
                        
                        if(isset($oldLineItemMetaArray['fabricid']) && strlen(trim($oldLineItemMetaArray['fabricid'])) > 0){
                            if($oldLineItemMetaArray['fabricid'] != $newLineItemMetaArray['fabricid'] && ($totalProducedThisLine > 0 || $totalShippedThisLine > 0 || $totalInvoicedThisLine > 0)){
                                $ruleErrors[]='<img src="/img/delete.png" /> CANNOT SAVE. LINE '.$newLine['line_number'].' HAS ALREADY BEEN PRODUCED/SHIPPED and CANNOT GET ITS FABRIC/COLOR CHANGED';
                            }elseif($oldLineItemMetaArray['fabricid'] != $newLineItemMetaArray['fabricid'] && $totalScheduledThisLine > 0){
                                $ruleErrors[]='<img src="/img/delete.png" /> FABRIC CHANGE ERROR. CANNOT SAVE AS IS. LINE '.$newLine['line_number'].' HAS ALREADY BEEN BATCHED. MUST EDIT/REMOVE THE BATCH BEFORE SAVING';
                            }
                        }
                        
                        
                    }
                }
                
            }
            
            $this->set('totals',$totals);
            $this->set('ruleErrors',$ruleErrors);
    
    		//$this->render('/Quotes/addstep2');
	        return $this->redirect('/orders/woview/'.$thisOrder['id']);

		    
		}else{
		    $this->set('mode','workorder');
		    
    		//$thisOrderQuote=$this->Quotes->get($thisOrder['quote_id'])->toArray();
			$thisOrderQuote=$this->Quotes->find('all', ['conditions' => ['id'=>$thisOrder['quote_id']]])->first();

    		$this->set('orderData',$thisOrder);
    		$this->set('quoteData',$thisOrderQuote);
    	
    		$customerData=$this->Customers->find('all', ['conditions'=>['id'=>$thisOrderQuote['customer_id']]])->first()->toArray();
    		$this->set('customerData',$customerData);
    		$this->set('quoteID',$thisOrder['quote_id']);
    		$availableProjects=$this->Projects->find('all',['conditions'=>['customer_id' => $thisOrderQuote['customer_id']]])->toArray();
    		$this->set('availableProjects',$availableProjects);
    	
    		if($thisOrderQuote['contact_id'] == '0'){
    			$customerContact=false;
    		}else{
    			$customerContact=$this->CustomerContacts->find('all',['conditions'=>['id'=>$thisOrderQuote['contact_id']]])->first();
    		}
    
    		$this->set('customerContact',$customerContact);
    
    		$allcalculators=$this->Calculators->find('all')->toArray();
    
    		$this->set('allcalculators',$allcalculators);
    
            $allTypes=$this->QuoteTypes->find('all')->toArray();
            $this->set('allTypes',$allTypes);
	        return $this->redirect('/orders/woview/'.$thisOrder['id']);

    	//$this->render('/Quotes/addstep2');
		}
	}

	public function togglertmline($orderItemID,$newval=false){
	    $this->autoRender=false;
	    $currentItem=$this->WorkOrderItems->find('all',['conditions'=>['quote_line_item_id'=>$orderItemID]])->first()->toArray();
		 
	    $thisOrder=$this->WorkOrders->find('all',['conditions'=>['id'=>$currentItem['order_id']]])->first()->toArray();
	    $quoteItem=$this->WorkOrderLineItems->find('all',['conditions'=>['quote_line_item_id'=>$currentItem['quote_line_item_id']]])->first()->toArray();
	    
	    if(!$newval){
	        if($currentItem['released_to_manufacture'] == 0){
	            $newval=1;
	        }else{
	            $newval=0;
	        }
	    }
	    
	    $update=$this->WorkOrderItems->find('all',['conditions'=>['quote_line_item_id'=>$orderItemID]])->first();
	    $update->released_to_manufacture=$newval;
	    $this->WorkOrderItems->save($update);
	    
	    if($newval==1){
	        $this->logActivity($_SERVER['REQUEST_URI'],'Released Line '.$quoteItem['line_number'].' of WorkOrder# '.$thisOrder['order_number'].' to Manufacture');
	    }else{
	        $this->logActivity($_SERVER['REQUEST_URI'],'Unreleased Line '.$quoteItem['line_number'].' of WorkOrder# '.$thisOrder['order_number'].' from Manufacture');
	    }
	    
	    echo "OK";exit;
	}
	
	
	/**PPSA-1 end */
	/**PPSASCRUM-29 end */

}