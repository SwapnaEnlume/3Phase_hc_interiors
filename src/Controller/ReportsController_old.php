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
use Cake\Datasource\Exception\RecordNotFoundException;


/**
 * Static content controller
 *
 * This controller will render views from Template/Pages/
 *
 * @link http://book.cakephp.org/3.0/en/controllers/pages-controller.html
 */
class ReportsController extends AppController
{
	
	
		
	public function initialize()
    {
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
		$this->loadModel('Orders');
		$this->loadModel('OrderItems');
		$this->loadModel('OrderItemStatus');
		$this->loadModel('Calculators');
		$this->loadModel('Vendors');
		$this->loadModel('Shipments');
		$this->loadModel('SherryCache');
		$this->loadModel('SherryBatches');
		$this->loadModel('Fabrics');
		$this->loadModel('Linings');
		$this->loadModel('FabricMarkupRules');
		$this->loadModel('Assemblies');
		$this->loadModel('Bedspreads');
		$this->loadModel('BedspreadSizes');
		$this->loadModel('BsDataMap');
		$this->loadModel('CubicleCurtains');
		$this->loadModel('CubicleCurtainSizes');
		$this->loadModel('MiscellaneousProducts');
		$this->loadModel('Services');
		$this->loadModel('TrackSystems');
		$this->loadModel('WindowTreatments');
		$this->loadModel('QuoteNotes');
		$this->loadModel('QuoteDiscounts');
		$this->loadModel('QuoteLineItemNotes');
		$this->loadModel('LibraryImages');
		$this->loadModel('LibraryCategories');		
		$this->loadModel('CcDataMap');
		$this->loadModel('WtDataMap');
		$this->loadModel('WindowTreatmentSizes');
		$this->loadModel('QuoteBomRequirements');
		$this->loadModel('MaterialPurchases');
		$this->loadModel('MaterialInventory');
		$this->loadModel('MaterialUsages');
		$this->loadModel('Projects');
		$this->loadModel('ShippingMethods');
		$this->loadModel('Msr');
		$this->loadModel('QuoteTypes');
		$this->loadModel('ProductClasses');
		$this->loadModel('ProductSubclasses');
		$this->loadModel('OrderLineItems');
		
		/** PPSASCRUM-62 start **/
		$this->loadModel('ShipTo');
		$this->loadModel('Facility');
		/** PPSASCRUM-62 end **/
	}
	
		
	public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
        	/**PPSA-40 start **/
		if($this->request->action=='msr' || $this->request->action=='bulkeditmsr' || $this->request->action == 'productiondetailbacklog' || $this->request->action=='backlogreport' || $this->request->action == 'bookingreport' || $this->request->action == 'backlogsummary' || $this->request->action == 'getmsrlist' || $this->request->action == 'consolidatedquotes'|| $this->request->action == 'consolidateddraftquotes' || $this->request->action=='bigquotes'|| $this->request->action=='quotesnewlines'){

			$this->Security->config('unlockedActions', ['msr','getmsrlist','backlogreport','bookingreport','backlogsummary','productiondetailbacklog','bulkeditmsr','consolidatedquotes','consolidateddraftquotes','bigquotes','quotesnewlines']);
			$this->eventManager()->off($this->Csrf);
			$this->eventManager()->off($this->Security);
		}
			/**PPSA-40 end **/
    }
	
	
	public function index(){
		
	}
	
	public function productiondetailbacklog(){
	    $productMap=array(
			'cubicle-curtain'=>'Cubicle Curtain',
			'box-pleated'=>'Box Pleated Valance',
			'straight-cornice'=>'Straight Cornice',
			'bedspread'=>'Calculated Bedspread',
			'bedspread-manual'=>'Manually Entered Bedspread',
			'pinch-pleated' => 'Pinch Pleated Drapery'
		);
	    
	    $allsettings=$this->getsettingsarray();
	    
	    if($this->request->data){
	        $this->autoRender=false;
	    
    	    require($_SERVER['DOCUMENT_ROOT']."/vendor/phpoffice/phpexcel/Classes/PHPExcel.php");
            require($_SERVER['DOCUMENT_ROOT']."/vendor/phpoffice/phpexcel/Classes/PHPExcel/Writer/Excel2007.php");
            
    	    $objPHPExcel = new \PHPExcel();
            $objPHPExcel->setActiveSheetIndex(0);
			
			
			$objPHPExcel->getActiveSheet()->SetCellValue('A1', 'ORDER #');
            $objPHPExcel->getActiveSheet()->SetCellValue('B1', 'QUOTE #');
            
            $objPHPExcel->getActiveSheet()->SetCellValue('C1','STAGE');
            
            $objPHPExcel->getActiveSheet()->SetCellValue('D1','PM');
			$objPHPExcel->getActiveSheet()->SetCellValue('E1', 'CUSTOMER');
			$objPHPExcel->getActiveSheet()->SetCellValue('F1', 'CUSTOMER PO');
			$objPHPExcel->getActiveSheet()->SetCellValue('G1', 'RECIPIENT');
			$objPHPExcel->getActiveSheet()->SetCellValue('H1', 'SHIP DATE');
			
			$objPHPExcel->getActiveSheet()->SetCellValue('I1', 'SCHEDULED'); //moved from AB
			
			$objPHPExcel->getActiveSheet()->SetCellValue('J1', 'BOOK DATE');
			$objPHPExcel->getActiveSheet()->SetCellValue('K1', 'BATCH');
			$objPHPExcel->getActiveSheet()->SetCellValue('L1', 'LINE');
			
			$objPHPExcel->getActiveSheet()->SetCellValue('M1', 'LOCATION');
		
			$objPHPExcel->getActiveSheet()->SetCellValue('N1', 'QTY');
			$objPHPExcel->getActiveSheet()->SetCellValue('O1', 'UNIT');
			$objPHPExcel->getActiveSheet()->SetCellValue('P1', 'LINE ITEM');
			$objPHPExcel->getActiveSheet()->SetCellValue('Q1', 'FABRIC');
			$objPHPExcel->getActiveSheet()->SetCellValue('R1', 'COLOR');
			
			$objPHPExcel->getActiveSheet()->SetCellValue('S1', 'CUT WIDTH');
			$objPHPExcel->getActiveSheet()->SetCellValue('T1', 'FIN WIDTH');
			
			$objPHPExcel->getActiveSheet()->SetCellValue('U1', 'LENGTH');
			
			$objPHPExcel->getActiveSheet()->SetCellValue('V1', 'TOTAL LF');
			$objPHPExcel->getActiveSheet()->SetCellValue('W1', 'YDS / UNIT');
			$objPHPExcel->getActiveSheet()->SetCellValue('X1', 'TOTAL YARDS');
			$objPHPExcel->getActiveSheet()->SetCellValue('Y1', 'BASE');
			$objPHPExcel->getActiveSheet()->SetCellValue('Z1', 'TIERS');
			$objPHPExcel->getActiveSheet()->SetCellValue('AA1', 'ADJ PRICE');
			$objPHPExcel->getActiveSheet()->SetCellValue('AB1', 'EXT PRICE');

            
            
            $objPHPExcel->getActiveSheet()->SetCellValue('AC1', 'PRODUCED');
			$objPHPExcel->getActiveSheet()->SetCellValue('AD1', 'SHIPPED');
			
			$objPHPExcel->getActiveSheet()->SetCellValue('AE1', 'TRACKING #');
			
			$objPHPExcel->getActiveSheet()->SetCellValue('AF1', 'INVOICED');
			
			$objPHPExcel->getActiveSheet()->SetCellValue('AG1', 'LINE NOTES');
			
			$objPHPExcel->getActiveSheet()->SetCellValue('AH1', 'BS QUILT');
			$objPHPExcel->getActiveSheet()->SetCellValue('AI1', 'BS MAT SIZE');
			$objPHPExcel->getActiveSheet()->SetCellValue('AJ1', 'BS DROP');
			$objPHPExcel->getActiveSheet()->SetCellValue('AK1', 'BS WIDTHS EA');
			$objPHPExcel->getActiveSheet()->SetCellValue('AL1', 'BS TOP WIDTHS EA');
			$objPHPExcel->getActiveSheet()->SetCellValue('AM1', 'BS TOP WIDTHS CL');
			$objPHPExcel->getActiveSheet()->SetCellValue('AN1', 'BS TOP CUT SIZE W');
			$objPHPExcel->getActiveSheet()->SetCellValue('AO1', 'BS TOP CUT SIZE L');
			$objPHPExcel->getActiveSheet()->SetCellValue('AP1', 'BS DROP CUT SIZE W');
			$objPHPExcel->getActiveSheet()->SetCellValue('AQ1', 'BS DROP CUT SIZE L');
			$objPHPExcel->getActiveSheet()->SetCellValue('AR1', 'CC MESH SIZE');
			$objPHPExcel->getActiveSheet()->SetCellValue('AS1', 'CC MESH COLOR');
			$objPHPExcel->getActiveSheet()->SetCellValue('AT1', 'CC WIDTHS EA');
			$objPHPExcel->getActiveSheet()->SetCellValue('AU1', 'CC CUT LENGTH');
			$objPHPExcel->getActiveSheet()->SetCellValue('AV1', 'CC ACTUAL LF');
			
			
			$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(15);
			$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(15);
			$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(25);
			$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(15);
			$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(40);
			$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(25);
			$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(55);
			$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(15);
			
			$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(15); //SCHEDULED moved from AB
			
			$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(15);
			$objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(15);
			$objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(15);
			
			$objPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth(20);
			
			$objPHPExcel->getActiveSheet()->getColumnDimension('N')->setWidth(15);
			$objPHPExcel->getActiveSheet()->getColumnDimension('O')->setWidth(15);
			$objPHPExcel->getActiveSheet()->getColumnDimension('P')->setWidth(75);
			
			$objPHPExcel->getActiveSheet()->getColumnDimension('Q')->setWidth(20);
			$objPHPExcel->getActiveSheet()->getColumnDimension('R')->setWidth(20);
			$objPHPExcel->getActiveSheet()->getColumnDimension('S')->setWidth(20);
			$objPHPExcel->getActiveSheet()->getColumnDimension('T')->setWidth(20);
			
			$objPHPExcel->getActiveSheet()->getColumnDimension('U')->setWidth(20);
			$objPHPExcel->getActiveSheet()->getColumnDimension('V')->setWidth(20);
			$objPHPExcel->getActiveSheet()->getColumnDimension('W')->setWidth(20);
			
			$objPHPExcel->getActiveSheet()->getColumnDimension('X')->setWidth(20);
			$objPHPExcel->getActiveSheet()->getColumnDimension('Y')->setWidth(20);
			$objPHPExcel->getActiveSheet()->getColumnDimension('Z')->setWidth(30);
		
			$objPHPExcel->getActiveSheet()->getColumnDimension('AA')->setWidth(15);
			$objPHPExcel->getActiveSheet()->getColumnDimension('AB')->setWidth(15);
			
			
			$objPHPExcel->getActiveSheet()->getColumnDimension('AC')->setWidth(15);
			
			$objPHPExcel->getActiveSheet()->getColumnDimension('AD')->setWidth(15);
			$objPHPExcel->getActiveSheet()->getColumnDimension('AE')->setWidth(29);
			$objPHPExcel->getActiveSheet()->getColumnDimension('AF')->setWidth(15);
			
			$objPHPExcel->getActiveSheet()->getColumnDimension('AG')->setWidth(70);
			
			$objPHPExcel->getActiveSheet()->getColumnDimension('AH')->setWidth(32);
			$objPHPExcel->getActiveSheet()->getColumnDimension('AI')->setWidth(16);
			$objPHPExcel->getActiveSheet()->getColumnDimension('AJ')->setWidth(16);
			$objPHPExcel->getActiveSheet()->getColumnDimension('AK')->setWidth(16);
			$objPHPExcel->getActiveSheet()->getColumnDimension('AL')->setWidth(20);
			$objPHPExcel->getActiveSheet()->getColumnDimension('AM')->setWidth(20);
			$objPHPExcel->getActiveSheet()->getColumnDimension('AN')->setWidth(20);
			$objPHPExcel->getActiveSheet()->getColumnDimension('AO')->setWidth(20);
			$objPHPExcel->getActiveSheet()->getColumnDimension('AP')->setWidth(20);
			$objPHPExcel->getActiveSheet()->getColumnDimension('AQ')->setWidth(20);
			$objPHPExcel->getActiveSheet()->getColumnDimension('AR')->setWidth(16);
			$objPHPExcel->getActiveSheet()->getColumnDimension('AS')->setWidth(16);
			$objPHPExcel->getActiveSheet()->getColumnDimension('AT')->setWidth(16);
			$objPHPExcel->getActiveSheet()->getColumnDimension('AU')->setWidth(16);
			$objPHPExcel->getActiveSheet()->getColumnDimension('AV')->setWidth(16);
			
			
			$objPHPExcel->getActiveSheet()->getRowDimension('1')->setRowHeight(22);
			$objPHPExcel->getActiveSheet()->getStyle('A1:AV1')->getAlignment()->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);
			
			$objPHPExcel->getActiveSheet()->getStyle('A1:AV1')->applyFromArray(
				array(
					'fill' => array(
						'type' => \PHPExcel_Style_Fill::FILL_SOLID,
						'color' => array('rgb' => 'F8CBAD')
					)
				)
			);
			
			
            $rowCount = 2;
			
			$conditions=array();
			
			$conditions += array('status !=' => 'Canceled');
			
			if((isset($this->request->data['datestart']) && strlen(trim($this->request->data['datestart'])) > 0) && (isset($this->request->data['dateend']) && strlen(trim($this->request->data['dateend'])) > 0)){
				$conditions += array('created >=' => strtotime($this->request->data['datestart'].' 00:00:00'));
				$conditions += array('created <=' => strtotime($this->request->data['dateend'].' 23:59:59'));
			}
			
			if(isset($this->request->data['quotenumber']) && strlen(trim($this->request->data['quotenumber'])) > 0){
				
			}
			
			if(isset($this->request->data['ordernumber']) && strlen(trim($this->request->data['ordernumber'])) > 0){
				
			}
			
			if(isset($this->request->data['clientponumber']) && strlen(trim($this->request->data['clientponumber'])) > 0){
				
			}
			
			
			if(isset($this->request->data['customer']) && count($this->request->data['customer']) > 0){
				if(count($this->request->data['customer']) == 1){
					$conditions += array('customer_id' => $this->request->data['customer'][0]);
				}elseif(count($this->request->data['customer']) > 1){
					$conditions += array('customer_id IN' => $this->request->data['customer']);
				}
			}
			
			if(isset($this->request->data['hciagent']) && count($this->request->data['hciagent']) > 0){
				if(count($this->request->data['hciagent']) == 1){
					$conditions += array('user_id' => $this->request->data['hciagent'][0]);
				}elseif(count($this->request->data['hciagent']) > 1){
					$conditions += array('user_id IN' => $this->request->data['hciagent']);
				}
			}
			
			$orderLookup=$this->Orders->find('all',['conditions' => $conditions,'order'=>['order_number'=>'asc']])->toArray();
			
			foreach($orderLookup as $orderRow){
				
				$quoteItemsLoop=$this->QuoteLineItems->find('all',['conditions'=>['quote_id' => $orderRow['quote_id']],'order'=>['line_number'=>'asc']])->toArray();
				
				$quoteData=$this->Quotes->get($orderRow['quote_id'])->toArray();
				
				$customer=$this->Customers->get($orderRow['customer_id'])->toArray();
				$thisCustomer=$this->Customers->get($orderRow['customer_id'])->toArray();
				
				foreach($quoteItemsLoop as $quoteItem){
					if($quoteItem['parent_line'] == 0){
					    
					$orderItemFind=$this->OrderItems->find('all',['conditions' => ['quote_line_item_id' => $quoteItem['id']]])->toArray();
					foreach($orderItemFind as $orderItem){
					    
    						//look for BATCHES, if found, loop through those, then loop through the remainders or unbatched items
    						$batchesScheduled=$this->OrderItemStatus->find('all',['conditions' => ['order_item_id' => $orderItem['id'], 'status IN' => ['Scheduled','Completed','Invoiced','Shipped']]])->toArray();
    						$qtyShipped=0;
    						$qtyInvoiced=0;
    						$qtyCompleted=0;
    						$qtyScheduled=0;
    						
    						$loop=array(
    							'batches' => array(),
    							'unbatched' => 0
    						);
    						
    						$scheduledBatchesThisLine=0;
    						
    						foreach($batchesScheduled as $batchData){
    							switch($batchData['status']){
    								case 'Shipped':
    									$qtyShipped=($qtyShipped + intval($batchData['qty_involved']));
    								break;
    								case 'Invoiced':
    									$qtyInvoiced=($qtyInvoiced + intval($batchData['qty_involved']));
    								break;
    								case 'Scheduled':
    									$qtyScheduled=($qtyScheduled + intval($batchData['qty_involved']));
    									$loop['batches'][]=array('batchid'=>$batchData['sherry_batch_id'],'qty'=>$batchData['qty_involved'],'scheduledTS'=>$batchData['time']);
    									$scheduledBatchesThisLine++;
    								break;
    								case 'Completed':
    									$qtyCompleted=($qtyCompleted + intval($batchData['qty_involved']));
    								break;
    							}
    						}
    						
    						//loop back through again to add Shipped or Invoiced timestamps to batches that have them
    						foreach($batchesScheduled as $batchData){
    							switch($batchData['status']){
    								case 'Shipped':
    									foreach($loop['batches'] as $num => $batchloopitem){
    										if($batchloopitem['batchid'] == $batchData['sherry_batch_id']){
    											$loop['batches'][$num]['shippedTS'] = $batchData['time'];
    										}
    									}
    								break;
    								case 'Invoiced':
    									foreach($loop['batches'] as $num => $batchloopitem){
    										if($batchloopitem['batchid'] == $batchData['sherry_batch_id']){
    											$loop['batches'][$num]['invoicedTS'] = $batchData['time'];
    										}
    									}
    								break;
    							}
    						}
    						
    						$remainingUnscheduled=(intval($quoteItem['qty']) - $qtyScheduled);
    						$remainingUninvoiced=(intval($quoteItem['qty']) - $qtyInvoiced);
    						$remainingUnshipped = (intval($quoteItem['qty']) - $qtyShipped);
    						$remainingIncompleteScheduled= (intval($quoteItem['qty']) - $qtyCompleted);
    						
    						$loop['unbatched']=$remainingUnscheduled;
    					
    						
    						$itemMetas=$this->QuoteLineItemMeta->find('all',['conditions'=>['quote_item_id'=>$quoteItem['id']]])->toArray();
    						$metaArray=array();
    						foreach($itemMetas as $meta){
    							$metaArray[$meta['meta_key']]=$meta['meta_value'];
    						}
    						
    						if($quoteItem['product_type'] != 'custom' && $quoteItem['product_type'] != 'services' && $quoteItem['product_type'] != 'track_systems'){
    							
    							
    							if($metaArray['fabrictype'] != 'none' && $metaArray['fabrictype'] != 'typein'){
    								
    								if(isset($metaArray['fabricid']) && strlen(trim($metaArray['fabricid'])) >0 && $metaArray['fabricid'] != '0'){
    									$thisFabric=$this->Fabrics->get($metaArray['fabricid'])->toArray();
    								}else{
    									$thisFabric=array('fabric_name'=>'','color'=>'');
    								}
    								
    								//$thisFabric=$this->Fabrics->get($metaArray['fabricid'])->toArray();
    							}elseif($metaArray['fabrictype'] == 'typein'){
    							    $thisFabric=array('fabric_name'=>$metaArray['fabric_name'],'color'=>$metaArray['fabric_color']);
    							}else{
    								$thisFabric=array('fabric_name'=>'','color'=>'');
    							}
    						}else{
    							if($metaArray['fabrictype'] != 'none' && $metaArray['fabrictype'] != 'typein'){
    								if(isset($metaArray['fabricid']) && strlen(trim($metaArray['fabricid'])) >0 && $metaArray['fabricid'] != '0'){
    									$thisFabric=$this->Fabrics->get($metaArray['fabricid'])->toArray();
    								}else{
    									$thisFabric=array('fabric_name'=>'','color'=>'');
    								}
    							}elseif($metaArray['fabrictype'] == 'typein'){
    							    $thisFabric=array('fabric_name'=>$metaArray['fabric_name'],'color'=>$metaArray['fabric_color']);
    							}else{
    								$thisFabric=array('fabric_name'=>'','color'=>'');
    							}
    						}
    						
    						
    						
    						$thisbookingdate='';
    
    						//lookup the original Order Item Status for this line item and get the date value
    						$itemstatuslookup=$this->OrderItemStatus->find('all',['conditions' => ['order_item_id' => $orderItem['id'],'status' => 'Not Started']])->toArray();
    						foreach($itemstatuslookup as $itemstatusrow){
    							//$thisbookingdate=date('n/j/Y',$itemstatusrow['time']);
    							$bookDateTimeObj=new \DateTime();
    							$bookDateTimeObj->setTimestamp($itemstatusrow['time']);
    							
    							$thisbookingdate=\PHPExcel_Shared_Date::PHPToExcel($bookDateTimeObj);
    						}
    
    
    						$customerValue=$thisCustomer['company_name'];
    						$customerPOValue=$orderRow['po_number'];
    						$facilityValue=$orderRow['facility'];
    						
    						
    						$quoteNumberValue='';
    						if($quoteData['revision'] > 0){
    							$quoteNumberValue=$quoteData['quote_number']."\n[REV ".$quoteData['revision']."]";
    						}else{
    							$quoteNumberValue=$quoteData['quote_number'];
    						}
    						
    						
    						
    						if($orderRow['due'] < 1000){
    							$shipDateValue='';
    						}else{
    							//$shipDateValue=date('n/d/Y',$orderRow['due']);
    							$dueDateTimeObj=new \DateTime();
            					$dueDateTimeObj->setTimestamp($orderRow['due']);
            					$shipDateValue = \PHPExcel_Shared_Date::PHPToExcel($dueDateTimeObj);
    						}
    
    						$lineNumberValue=$quoteItem['line_number'];
    						$locationValue=$quoteItem['room_number'];
    						$unitValue=$quoteItem['unit'];
    						$itemDescription='';
    						
    						///////////////////////////////////////////////////////////////////////////////////////////////////////////////
    						
    						if($metaArray['lineitemtype']=='calculator'){
    							if($metaArray['calculator-used']=="straight-cornice"){
    								$itemDescription .= $metaArray['cornice-type']." Cornice";
    								if($metaArray['railroaded'] == '1'){
    									$itemDescription .= "<br>Fabric Railroaded";
    								}else{
    									$itemDescription .= "<br>Fabric Vertically Seamed";
    								}
    								$thisLining=$this->Linings->get($metaArray['linings_id'])->toArray();
    								$itemDescription .= "<br><b>Lining:</b> ".$thisLining['short_title'];
    								$welts='';
    								if($metaArray['welt-top']=='1' && $metaArray['welt-bottom']=='1'){
    									$welts = "Top + Bottom";
    								}else{
    									if($metaArray['welt-top']=='1'){
    										$welts = "Top Only";
    									}elseif($metaArray['welt-bottom'] == '1'){
    										$welts = "Bottom Only";
    									}
    								}
    								if($welts != ''){
    									$itemDescription .= "<br><b>Welts:</b> ".$welts;
    								}else{
    									$itemDescription .= "<br><b>Welts:</b> None";
    								}
    								if($metaArray['individual-nailheads'] == '1'){
    									$itemDescription .= "<br>Individual Nailheads";
    								}
    								if($metaArray['nailhead-trim'] == '1'){
    									$itemDescription .= "<br>Nailhead Trim";
    								}
    								if($metaArray['covered-buttons'] == '1'){
    									$itemDescription .= "<br>".$metaArray['covered-buttons-count']." Covered Buttons";
    								}
    
    								if($metaArray['horizontal-straight-banding'] == '1'){
    									$itemDescription .= "<br>".$metaArray['horizontal-straight-banding-count']." H Straight Banding";
    								}	
    								if($metaArray['horizontal-shaped-banding'] == '1'){
    									$itemDescription .= "<br>".$metaArray['horizontal-shaped-banding-count']." H Shaped Banding";
    								}
    								if($metaArray['extra-welts'] == '1'){
    									$itemDescription .= "<br>".$metaArray['extra-welts-count']." Extra Welts";
    								}	
    								if($metaArray['trim-sewn-on'] == '1'){
    									$itemDescription .= "<br>".$metaArray['trim-lf']." LF Sewn-On Trim";
    								}
    								if($metaArray['tassels'] == '1'){
    									$itemDescription .= "<br>".$metaArray['tassels-count']." Tassels";
    								}
    								if($metaArray['drill-holes'] == '1'){
    									$itemDescription .= "<br>".$metaArray['drill-hole-count']." Drill Holes";
    								}
    								if(isset($metaArray['vertical-repeat']) && floatval($metaArray['vertical-repeat']) >0){
    									$itemDescription .= "<br>Matched Repeat (".$metaArray['vertical-repeat']."&quot;)";
    								}
    							}elseif($metaArray['calculator-used']=="bedspread" || $metaArray['calculator-used'] == "bedspread-manual"){
    								$itemDescription .= $productMap[$metaArray['calculator-used']];
    								if($metaArray['railroaded'] == '1'){
    									$itemDescription .= "<br>Fabric Railroaded";
    								}else{
    									$itemDescription .= "<br>Fabric Up-the-Roll";
    								}
    
    								$itemDescription .= "<br>";
    								if(isset($metaArray['style']) && strlen(trim($metaArray['style'])) >0){
    									$itemDescription .= "Style: ".$metaArray['style'];
    								}
    								if(isset($metaArray['quilted']) && $metaArray['quilted']=='1'){
    									$itemDescription .= "<br>Quilted";
    									if(isset($metaArray['quilting-pattern']) && strlen(trim($metaArray['quilting-pattern'])) >0){
    										$itemDescription .= ", ".$metaArray['quilting-pattern'];
    									}
    									if(isset($metaArray['matching-thread']) && $metaArray['matching-thread'] == '1'){
    										$itemDescription .= ", Matching Thread";
    									}
    									$itemDescription .= ", ".$thisFabric['bs_backing_material']." Backing";
    								}else{
    									$itemDescription .= "<br>Unquilted";
    								}
    
    								$itemDescription .= "<br>Mattress: ";
    								if(!isset($metaArray['custom-top-width-mattress-w'])){
    									$itemDescription .= "36&quot;";
    								}else{
    									$itemDescription .= $metaArray['custom-top-width-mattress-w']."&quot;";
    								}
    
    								if(isset($metaArray['vertical-repeat']) && floatval($metaArray['vertical-repeat']) >0){
    									$itemDescription .= "<br>Matched Repeat (".$metaArray['vertical-repeat']."&quot;)";
    								}
    							}elseif($metaArray['calculator-used']=="box-pleated"){
    								$itemDescription .= $metaArray['valance-type']." Valance";
    
    								if($metaArray['railroaded'] == '1'){
    									$itemDescription .= "<br>Fabric Railroaded";
    								}else{
    									$itemDescription .= "<br>Fabric Vertically Seamed";
    								}
    								$itemDescription .= "<br>".$metaArray['pleats']." Pleats";
    
    								$thisLining=$this->Linings->get($metaArray['linings_id'])->toArray();
    								$itemDescription .= "<br><b>Lining:</b> ".$thisLining['short_title'];
    
    								if($metaArray['straight-banding']==1){
    									$itemDescription .= "<br>Straight Banding";
    								}
    								if($metaArray['shaped-banding']==1){
    									$itemDescription .= "<Br>Shaped Banding";
    								}
    								if($metaArray['trim-sewn-on']==1){
    									$itemDescription .= "<br>Sewn-On Trim";
    								}
    								if($metaArray['welt-covered-in-fabric'] == 1){
    									$itemDescription .= "<br>Welt Covered In Fabric";
    								}
    								if($metaArray['contrast-fabric-inside-pleat'] == 1){
    									$itemDescription .= "<br>Contrast Fabric Inside Pleat";
    								}
    								if(isset($metaArray['vert-repeat']) && floatval($metaArray['vert-repeat']) >0){
    									$itemDescription .= "<br>Matched Repeat (".$metaArray['vert-repeat']."&quot;)";
    								}
    							}elseif($metaArray['calculator-used']=="cubicle-curtain"){
    								$itemDescription .= $productMap[$metaArray['calculator-used']];
    								if($metaArray['railroaded'] == '1'){
    									$itemDescription .= "<br>Fabric Railroaded";
    								}else{
    									$itemDescription .= "<br>Fabric Vertically Seamed";
    								}
    								if($metaArray['mesh'] > 0 && $metaArray['mesh'] != ''){
    									$itemDescription .= "<br><b>Mesh:</b> ".$metaArray['mesh']."&quot; ".$metaArray['mesh-color']." (".str_replace(" Mesh","",$metaArray['mesh-type']).")";
    								}
    								if($metaArray['mesh-type'] == 'None'){
    									$itemDescription .= "<br>NO MESH";
    								}
    								if($metaArray['mesh-type'] == 'Integral Mesh'){
    									$itemDescription .= "<br>INTEGRAL MESH";
    								}
    								if($metaArray['liner'] == 1){
    									$itemDescription .= "<br>Liner";
    								}
    								if($metaArray['nylon-mesh']==1){
    									$itemDescription .= "<br>Nylon Mesh";
    								}
    								if($metaArray['angled-mesh']==1){
    									$itemDescription .= "<br>Angled Mesh";
    								}
    								if($metaArray['mesh-frame'] != 'No Frame'){
    									$itemDescription .= "<br><b>Mesh Frame:</b> ".$metaArray['mesh-frame'];
    								}
    								if($metaArray['hidden-mesh'] == 1){
    									$itemDescription .= "<br>Hidden Mesh";
    								}
    
    								if($metaArray['snap-tape'] != "None"){
    									$itemDescription .= "<br>".$metaArray['snap-tape']." Snap Tape (".$metaArray['snaptape-lf'].")";
    								}
    								if($metaArray['velcro'] != 'None'){
    									$itemDescription .= "<br>".$metaArray['velcro']." Velcro (".$metaArray['velcro-lf']." LF)";
    								}
    								if($metaArray['weights']==1){
    									$itemDescription .= "<br>".$metaArray['weight-count']." Weights";
    								}
    								if($metaArray['magnets']==1){
    									$itemDescription .= "<br>".$metaArray['magnet-count']." Magnets";
    								}
    								if($metaArray['banding'] == 1){
    									$itemDescription .= "<br>Banding";
    								}
    								if($metaArray['buttonholes'] == 1){
    									$itemDescription .= "<br>".$metaArray['buttonhole-count']." Buttonholes";
    								}
    								if(isset($metaArray['vertical-repeat']) && floatval($metaArray['vertical-repeat']) >0){
    									$itemDescription .= "<br>Matched Repeat (".$metaArray['vertical-repeat']."&quot;)";
    								}
    							}elseif($metaArray['calculator-used']=='pinch-pleated'){
    								$itemDescription .= $productMap[$metaArray['calculator-used']];
    								if($metaArray['unit-of-measure'] == 'pair'){
    									$itemDescription .= "<br>Pair";
    								}elseif($metaArray['unit-of-measure'] == 'panel'){
    									$itemDescription .= "<br>".$metaArray['panel-type']." Panel";
    								}
    								if($metaArray['railroaded'] == '1'){
    									$itemDescription .= "<br>Fabric Railroaded";
    								}else{
    									$itemDescription .= "<br>Fabric Vertically Seamed";
    								}
    								if(isset($metaArray['linings_id']) && floatval($metaArray['linings_id']) >0){
    									$thisLining=$this->Linings->get($metaArray['linings_id'])->toArray();
    									$itemDescription .= "<br><b>Lining:</b> ".$thisLining['short_title'];
    								}
    								$itemDescription .= "<br><b>Hardware:</b> ".ucfirst($metaArray['hardware']);
    								if($metaArray['trim-sewn-on'] == '1'){
    									$itemDescription .= "<br>".$metaArray['trim-lf']." LF Sewn-On Trim";
    								}
    								if($metaArray['tassels'] == '1'){
    									$itemDescription .= "<br>".$metaArray['tassels-count']." Tassels";
    								}
    							}
    						}elseif($metaArray['lineitemtype']=='simpleproduct'){
    							switch($quoteItem['product_type']){
    								case "cubicle_curtains":
    									$itemDescription .= "Price List CC";
    									if($thisFabric['railroaded'] == '1'){
    										$itemDescription .= "<br>Fabric Railroaded";
    									}else{
    										$itemDescription .= "<br>Fabric Vertically Seamed";
    									}
    									if($metaArray['mesh'] > 0 && $metaArray['mesh'] != ''){
    										$itemDescription .= "<br><b>Mesh:</b> ".$metaArray['mesh']."&quot; ".$metaArray['mesh-color']." (MOM)";
    									}
    									if($metaArray['mesh'] == 'No Mesh' || $metaArray['mesh'] == '0'){
    										$itemDescription .= "<br>NO MESH";
    									}
    									if(floatval($thisFabric['vertical_repeat']) > 0){
    										$itemDescription .= "<br>Matched Repeat (".$thisFabric['vertical_repeat']."&quot;)";
    									}
    								break;
    								case "bedspreads":
    									$itemDescription .= "Price List BS";
    										$thisBS="";
    									try {
    									        $thisBS=$this->Bedspreads->get($quoteItem['product_id'])->toArray();
                                            } catch (RecordNotFoundException $e) { }
    								
    									if($thisFabric['railroaded'] == '1'){
    										$itemDescription .= "<br>Fabric Railroaded";
    									}else{
    										$itemDescription .= "<br>Fabric Vertically Seamed";
    									}
    									$itemDescription .= "<br>Style: ";
    									$styleval=explode(" (",$metaArray['style']);
    									$itemDescription .= $styleval[0];
    									if(!empty($thisBS) && $thisBS['quilted']=='1'){
    										$itemDescription .= "<br>Quilted, Double Onion, ".$thisFabric['bs_backing_material']." Backing";
    									}else{
    										$itemDescription .= "<br>Unquilted";
    									}
    									$itemDescription .= "<br>Mattress: ";
    									if(!isset($metaArray['custom-top-width-mattress-w'])){
    										$itemDescription .= "36&quot;";
    									}else{
    										$itemDescription .= $metaArray['custom-top-width-mattress-w']."&quot;";
    									}
    
    									if(floatval($thisFabric['vertical_repeat']) > 0){
    										$itemDescription .= "<br>Matched Repeat (".$thisFabric['vertical_repeat']."&quot;)";
    									}
    								break;
    								case "services":
    									$itemDescription .= $quoteItem['title'];
    								break;
    								case "window_treatments":
    									if($metaArray['wttype']=='Pinch Pleated Drapery'){
    										$itemDescription .= "<b>".ucfirst($metaArray['unit-of-measure'])."</b><br>";
    									}
    									$itemDescription .= 'Price List '.$metaArray['wttype'];
    									if(preg_match("#Cornice#i",$metaArray['wttype']) || preg_match("#Valance#i",$metaArray['wttype'])){
    										$itemDescription .= "<br>Fabric Vertically Seamed";
    									}
    									if(preg_match("#Valance#i",$metaArray['wttype'])){
    										$itemDescription .= "<br>".$metaArray['pleats']." Pleats";
    									}
    									if(isset($metaArray['linings_id']) && floatval($metaArray['linings_id']) >0){
    										$thisLining=$this->Linings->get($metaArray['linings_id'])->toArray();
    										$itemDescription .= "<br><b>Lining:</b> ".$thisLining['short_title'];
    									}
    									if(preg_match("#Cornice#i",$metaArray['wttype'])){
    										$welts='';
    										if($metaArray['welt-top']=='1' && $metaArray['welt-bottom']=='1'){
    											$welts = "Top + Bottom";
    										}else{
    											if($metaArray['welt-top']=='1'){
    												$welts = "Top Only";
    											}elseif($metaArray['welt-bottom'] == '1'){
    												$welts = "Bottom Only";
    											}
    										}
    										if($welts != ''){
    											$itemDescription .= "<br><b>Welts:</b> ".$welts;
    										}else{
    											$itemDescription .= "<br><b>Welts:</b> None";
    										}
    									}
    
    								break;
    								case "track_systems":
    									$itemDescription .= "<b>".$quoteItem['title']."</b>";
    									if(isset($metaArray['_component_numlines']) && intval($metaArray['_component_numlines']) >0){
    										$itemDescription .= "<br><button style=\"padding:4px; border:1px solid #000; background:#CCC; color:#000; font-size:11px;\" onclick=\"loadTrackBreakdown('".$quoteItem['id']."');\" type=\"button\">List Components</button>";
    									}
    								break;
    							}
    						}elseif($metaArray['lineitemtype']=='custom' || $metaArray['lineitemtype'] == 'newcatchall'){
    							$itemDescription .= "<b>".$quoteItem['title']."</b>";
    							$itemDescription .= "<br>".$quoteItem['description'];
    							$itemDescription .= "<br>".nl2br($metaArray['specs']);
    						}
    						
    						///////////////////////////////////////////////////////////////////////////////////////////////////////////////
    						
    						
    						$ttwizard = new \PHPExcel_Helper_HTML;
    						$lineItemValue = $ttwizard->toRichTextObject($itemDescription);
    						
    						
    						$fabricValue='';
    						$fabricFR='';
    						if(isset($thisFabric['flammability']) && strlen(trim($thisFabric['flammability'])) >0){
    							$fabricFR='<br><b>FR: '.$thisFabric['flammability'].'</b>';
    						}
    
    						if($quoteItem['product_type'] == 'track_systems'){
    							$fabricValue .= '---';
    						}elseif($quoteItem['product_type'] == 'custom'){
    							if(isset($metaArray['com-fabric']) && $metaArray['com-fabric'] == "1"){
    								$fabricValue .= "COM ";
    							}
    							if($fabricAlias=$this->getFabricAlias($metaArray['fabricid'],$thisQuote['customer_id'])){
    								if(isset($metaArray['usealias']) && $metaArray['usealias'] == 'yes'){
    									$fabricValue .= $fabricAlias['fabric_name']."<br>".$metaArray['fabric_name'].$fabricFR;
    								}else{
    									$fabricValue .= $metaArray['fabric_name']."<br><em>(".$fabricAlias['fabric_name'].")</em>".$fabricFR;
    								}
    							}else{
    								$fabricValue .= $metaArray['fabric_name'].$fabricFR;
    							}
    						}else{
    							if(isset($metaArray['com-fabric']) && $metaArray['com-fabric'] == "1"){
    								$fabricValue .= "COM ";
    							}
    							if($fabricAlias=$this->getFabricAlias($thisFabric['id'],$thisQuote['customer_id'])){
    								if(isset($metaArray['usealias']) && $metaArray['usealias'] == 'yes'){
    									$fabricValue .= "<b>".$fabricAlias['fabric_name']."</b><br>".$thisFabric['fabric_name'];
    								}else{
    									$fabricValue .= $thisFabric['fabric_name']."<br><em>(".$fabricAlias['fabric_name'].")</em>";
    								}
    							}else{
    								$fabricValue .= $thisFabric['fabric_name'];
    							}
    							$fabricValue .= $fabricFR;
    						}
    						
    						$fabricwizard = new \PHPExcel_Helper_HTML;
    						$fabricrichText = $fabricwizard->toRichTextObject($fabricValue);
    						
    						
    						$colorValue='';
    						if($quoteItem['product_type'] == 'track_systems'){
    							$colorValue .= '---';
    						}elseif($quoteItem['product_type'] == 'custom'){
    							if($fabricAlias=$this->getFabricAlias($metaArray['fabricid'],$thisQuote['customer_id'])){
    								if(isset($metaArray['usealias']) && $metaArray['usealias'] == 'yes'){
    									$colorValue .= "<b>".$fabricAlias['color']."</b><br>".$metaArray['fabric_color'];	
    								}else{
    									$colorValue .= $metaArray['fabric_color']."<br><em>(".$fabricAlias['color'].")</em>";
    								}
    							}else{
    								$colorValue .= $metaArray['fabric_color'];
    							}
    						}else{
    							if($fabricAlias=$this->getFabricAlias($thisFabric['id'],$thisQuote['customer_id'])){
    								if(isset($metaArray['usealias']) && $metaArray['usealias'] == 'yes'){
    									$colorValue .= "<b>".$fabricAlias['color']."</b><Br>".$thisFabric['color'];
    								}else{
    									$colorValue .= $thisFabric['color']."<br><em>(".$fabricAlias['color'].")</em>";
    								}
    							}else{
    								$colorValue .= $thisFabric['color'];
    							}
    						}
    						
    						$colorwizard = new \PHPExcel_Helper_HTML;
    						$colorrichText = $colorwizard->toRichTextObject($colorValue);
    						
    						$cutwidthValue='';
    						$finwidthValue='';
    						
    						if($quoteItem['product_type'] == 'track_systems'){
    							$cutwidthValue .= '---';
    						}elseif($quoteItem['product_type']=="bedspreads"){
    							$cutwidthValue .= number_format(floatval($metaArray['width']),0,'','');
    							if(isset($metaArray['width']) && strlen(trim($metaArray['width'])) >0){
    								$finwidthValue .= $metaArray['width'];
    							}
    						}elseif($quoteItem['product_type']=="cubicle_curtains"){
    							$cutwidthValue .= number_format(floatval($metaArray['width']),0,'','');
    							if(isset($metaArray['expected-finish-width']) && strlen(trim($metaArray['expected-finish-width'])) >0){
    								$finwidthValue .= $metaArray['expected-finish-width'];
    							}
    						}elseif($quoteItem['product_type'] == 'window_treatments'){
    							if($metaArray['wttype'] == 'Pinch Pleated Drapery'){
    								$cutwidthValue .= number_format(floatval($metaArray['rod-width']),0,'','')." (Rod)";
    								$finwidthValue .= $metaArray['default-return']." (Return)";
    							}elseif(preg_match("#Cornice#i",$metaArray['wttype']) || preg_match("#Valance#i",$metaArray['wttype'])){
    								$cutwidthValue .= number_format(floatval($metaArray['width']),0,'','')." (Face)";
    								if(isset($metaArray['return']) && floatval(trim($metaArray['return'])) >0){
    									$finwidthValue .= $metaArray['return']." (Return)";
    								}else{
    									$finwidthValue .= "No Return";
    								}
    							}
    						}else{
    							if($metaArray['calculator-used']=="box-pleated"){
    								if(isset($metaArray['face']) && strlen(trim($metaArray['face'])) >0){
    									$cutwidthValue .= $metaArray['face']." (Face)";
    								}
    								if(isset($metaArray['return']) && floatval(trim($metaArray['return'])) >0){
    									$finwidthValue .= $metaArray['return']." (Return)";
    								}else{
    									$finwidthValue .= "No Return";
    								}
    							}elseif($metaArray['calculator-used']=="bedspread" || $metaArray['calculator-used']=="bedspread-manual"){
    								if(isset($metaArray['width']) && strlen(trim($metaArray['width'])) >0){
    									$finwidthValue .= $metaArray['width'];
    								}
    							}elseif($metaArray['calculator-used']=="cubicle-curtain"){
    								if(isset($metaArray['width']) && strlen(trim($metaArray['width'])) >0){
    									$cutwidthValue .= $metaArray['width'];
    								}
    								if(isset($metaArray['expected-finish-width']) && strlen(trim($metaArray['expected-finish-width'])) >0){
    									$finwidthValue .= $metaArray['expected-finish-width'];
    								}else{
    									if(isset($metaArray['fw']) && strlen(trim($metaArray['fw'])) >0){
    										$finwidthValue .= $metaArray['fw'];
    									}
    								}
    							}elseif($metaArray['calculator-used']=="straight-cornice"){
    								if(isset($metaArray['face']) && strlen(trim($metaArray['face'])) >0){
    									$cutwidthValue .= $metaArray['face']." (Face)";
    								}
    								if(isset($metaArray['return']) && floatval(trim($metaArray['return'])) >0){
    									$finwidthValue .= $metaArray['return']." (Return)";
    								}else{
    									$finwidthValue .= "No Return";
    								}
    							}elseif($metaArray['calculator-used'] == 'pinch-pleated'){
    								if($metaArray['unit-of-measure'] == 'pair'){
    									if(isset($metaArray['rod-width']) && strlen(trim($metaArray['rod-width'])) >0){
    										$cutwidthValue .= $metaArray['rod-width']." (Rod)";
    									}
    								}else{
    									if(isset($metaArray['fabric-widths-per-panel'])){
    										$cutwidthValue .= $metaArray['fabric-widths-per-panel']." Widths";
    									}
    								}
    								if(isset($metaArray['fullness']) && strlen(trim($metaArray['fullness'])) >0){
    									$finwidthValue .= $metaArray['fullness']."X Fullness";
    								}
    								if(isset($metaArray['default-return']) && strlen(trim($metaArray['default-return'])) >0){
    									$finwidthValue .= "<br>".$metaArray['default-return']." Ret";
    								}
    								if(isset($metaArray['default-overlap']) && strlen(trim($metaArray['default-overlap'])) >0){
    									$finwidthValue .= "<br>".$metaArray['default-overlap']." Ovrlp";
    								}
    							}
    						}
    						
    						$cutwidthwizard = new \PHPExcel_Helper_HTML;
    						$cutwidthValue = $cutwidthwizard->toRichTextObject($cutwidthValue);
    						
    						$finwidthwizard = new \PHPExcel_Helper_HTML;
    						$finwidthValue = $finwidthwizard->toRichTextObject($finwidthValue);
    						
    						$lengthValue='';
    						if($quoteItem['product_type'] == 'track_systems'){
    							$lengthValue='---';
    						}else{
    							if($metaArray['calculator-used']=="box-pleated"){
    								$lengthValue .= $metaArray['height']." (Height)";
    							}elseif($metaArray['calculator-used']=="straight-cornice"){
    								$lengthValue .= $metaArray['height']." (Height)";
    							}else{
    								$lengthValue .= $metaArray['length'];
    								if($quoteItem['product_type'] == 'window_treatments' && (preg_match("#Cornice#i",$metaArray['wttype']) || preg_match("#Valance#i",$metaArray['wttype']))){
    									$lengthValue .= " (Height)";
    								}
    
    							}
    							if(isset($metaArray['fl-short']) && floatval($metaArray['fl-short']) >0){
    								$lengthValue .= "<br>".$metaArray['fl-short']."(Short Point)";
    							}
    						}
    						$lengthwizard = new \PHPExcel_Helper_HTML;
    						$lengthrichText = $lengthwizard->toRichTextObject($lengthValue);
    						
    					
    						
    						
    						
    						
    						if(isset($metaArray['yds-per-unit'])){
    							$ydsperunitValue=$metaArray['yds-per-unit'];
    						}else{
    							$ydsperunitValue='---';
    						}
    						
    						
    					
    						
    						$bestprice=$quoteItem['best_price'];
    						$installadjustmentpercentage=0;
    						$tieradjustmentpercentage=0;
    						$tierDiscountOrPremium='Disc';
    						$rebateadjustmentpercentage=0;
    						
    						if($metaArray['specialpricing']=='1'){
    							$tieradjusted=number_format(floatval($quoteItem['best_price']),2,'.',',');
    							$installadjusted=number_format(floatval($quoteItem['best_price']),2,'.',',');
    							$rebateadjusted=number_format(floatval($quoteItem['best_price']),2,'.',',');
    							$pmiadjusted=number_format(floatval($quoteItem['pmi_adjusted']),2,'.',',');
    							$pmiadjustmentdollars=number_format((floatval(str_replace(',','',$pmiadjusted)) - floatval(str_replace(',','',$rebateadjusted))),2,'.',',');
    							
    						}else{
    							$tieradjusted=number_format(floatval($quoteItem['tier_adjusted_price']),2,'.',',');
    							$installadjusted=number_format(floatval($quoteItem['install_adjusted_price']),2,'.',',');
    							$rebateadjusted=number_format(floatval($quoteItem['rebate_adjusted_price']),2,'.',',');
    							$pmiadjusted=number_format(floatval($quoteItem['pmi_adjusted']),2,'.',',');
    							
    							$tieradjustmentpercentage=round(abs((((1/floatval(str_replace(',','',$quoteItem['best_price']))) * floatval(str_replace(',','',$tieradjusted)))*100)),2);
    							$installadjustmentpercentage=round(abs(((1-((1/floatval(str_replace(',','',$tieradjusted))) * floatval(str_replace(',','',$installadjusted))))*100)),2);
    							$rebateadjustmentpercentage=round(abs(((1-((1/floatval(str_replace(',','',$installadjusted))) * floatval(str_replace(',','',$rebateadjusted))))*100)),2);
    							$pmiadjustmentdollars=number_format((floatval(str_replace(',','',$pmiadjusted)) - floatval(str_replace(',','',$rebateadjusted))),2,'.',',');
    						}
    
    						$breakdownValue='';
    						$basePriceValue=number_format($bestprice,2,'.',',');
    						
    						if($metaArray['specialpricing']=='1'){
    							$breakdownValue .= "<font color=\"#FF0000\"><b><em>SPECIAL PRICING</em></b></font>";
    						}else{
    							$breakdownValue .= "<font color=\"#0000FF\">".$tieradjusted." Tier (";
    							if(floatval(str_replace(',','',$quoteItem['best_price'])) > floatval(str_replace(',','',$tieradjusted))){
    								$breakdownValue .= '-'.$tieradjustmentpercentage."% Disc";
    							}elseif(floatval(str_replace(',','',$quoteItem['best_price'])) < floatval(str_replace(',','',$tieradjusted))){
    								if($tieradjustmentpercentage > 100){
    									$breakdownValue .= '+'.($tieradjustmentpercentage-100)."% Prem";
    								}else{
    									$breakdownValue .= '+'.$tieradjustmentpercentage."% Prem";
    								}
    							}else{
    								$breakdownValue .= "0%";
    							}
    							
    							$breakdownValue .= ")<br>".$installadjusted." INST (+".$installadjustmentpercentage."%)<br>".$rebateadjusted." ADD ";
    							$breakdownValue .= "(+".$rebateadjustmentpercentage."%)";
    							$breakdownValue .= "<br>".$pmiadjusted." PMI (+\$".$pmiadjustmentdollars.")</font>";
    						}
    						
    						
    						$breakdownwizard = new \PHPExcel_Helper_HTML;
    						$breakdownrichText = $breakdownwizard->toRichTextObject($breakdownValue);
    						
    						
    						
    						if($quoteItem['override_active'] == 1){
    							$adjustedColValue = number_format(floatval($quoteItem['override_price']),2,'.','');
    						}else{
    							$adjustedColValue = number_format(floatval($pmiadjusted),2,'.','');
    						}
    						
    						
    						
    						
    						
    						
    						
    						$extendedPriceValue=number_format($extendedprice,2,'.',',');
    						
    						$dateScheduled='';
    						$dateShipped='';
    						$dateInvoiced='';
    						$dateProduced='';
    						$trackingNumber='';
    						
    						
    						$lineNotesValue='';
    						$lineNotes=$this->QuoteLineItemNotes->find('all',['conditions'=>['quote_item_id' => $quoteItem['id']],'order'=>['time'=>'asc']])->toArray();
    						foreach($lineNotes as $lineNoteRow){
    							$thisNoteUser=$this->Users->get($lineNoteRow['user_id'])->toArray();
    							if($lineNoteRow['visible_to_customer'] == 0){
    								$lineNotesValue .= '[INTERNAL] ';
    							}
    							$lineNotesValue .= $lineNoteRow['message'].' <font color="#0000FF"><em>'.$thisNoteUser['first_name'].' '.substr($thisNoteUser['last_name'],0,1).' @ '.date('n/j/y g:iA',$lineNoteRow['time']).'</em></font><br>';
    						}
    						
    						$linenoteswizard = new \PHPExcel_Helper_HTML;
    						$linenotesrichText = $linenoteswizard->toRichTextObject($lineNotesValue);
    						
    						
    						
    						//start inner loop of $loop
    						//begin with UNBATCHED
    						if($loop['unbatched'] > 0){
    							$qtyValue=$loop['unbatched'];
    							$batchValue='N/A';
    							
    							$totalLFvalue='';
    							
    							$totalydsvalue='';
    							if($metaArray['lineitemtype'] == 'simpleproduct'){
    								$totalydsvalue .= round((floatval($metaArray['yds-per-unit']) * floatval($qtyValue)),2);
    							}elseif($metaArray['lineitemtype']=="custom"){
    								if(floatval($metaArray['total-yds']) >0){
    									$totalydsvalue .= round(((floatval($metaArray['total-yds']) / floatval($quoteItem['qty'])) * intval($qtyValue)),2);
    								}else{
    									$totalydsvalue .= round((floatval($metaArray['yds-per-unit']) * floatval($qtyValue)),2);
    								}
    							}else{
    								$totalydsvalue .= round(((floatval($metaArray['total-yds']) / floatval($quoteItem['qty'])) * intval($qtyValue)),2);
    							}
    							
    							
    							if($metaArray['specialpricing']=='1'){
    								if($quoteItem['override_active'] == 1){
    									$extendedprice=(floatval($quoteItem['override_price']) * floatval($qtyValue));
    								}else{
    									$extendedprice=(floatval($quoteItem['best_price'])*floatval($qtyValue));
    								}
    							}else{
    							    if($quoteItem['override_active'] == 1){
    									$extendedprice=(floatval($quoteItem['override_price']) * floatval($qtyValue));
    								}else{
    								    $extendedprice=number_format((floatval($quoteItem['pmi_adjusted']) * $qtyValue),2,'.','');
    								}
    							}
    							
    							
    							
    							if($quoteItem['product_type'] == 'track_systems'){
        							if($quoteItem['unit'] == 'linear feet'){
        								$totalLFvalue .= $qtyValue;
        							}else{
        								$totalLFvalue .= '---';
        							}
        						}else{
        							if(isset($metaArray['labor-billable'])){
        								$totalLFvalue .= (floatval($metaArray['labor-billable'])*floatval($qtyValue));
        							}else{
        								$totalLFvalue .= "---";
        							}
        						}
    						
    						if($orderRow['project_manager_id'] == 0 || is_null($orderRow['project_manager_id'])){
    						    $managerName='N/A';
    						}else{
    						    $pmLookup=$this->Users->get($orderRow['project_manager_id'])->toArray();
    						    $managerName=$pmLookup['first_name'].' '.$pmLookup['last_name'];
    						}
    						
    						$totallfrichText = $totalLFvalue;
    							
    							//recalc and override the TOTAL LF, TOTAL YARDS, EXTENDED PRICE variables for [this qty]
    							
    							
    							$objPHPExcel->getActiveSheet()->SetCellValue('A'.$rowCount, $orderRow['order_number']);
    							$objPHPExcel->getActiveSheet()->SetCellValue('B'.$rowCount, $quoteNumberValue);
    							
    							$objPHPExcel->getActiveSheet()->SetCellValue('C'.$rowCount, $orderRow['stage']);
    							
    							$objPHPExcel->getActiveSheet()->SetCellValue('D'.$rowCount, $managerName);
    							
    							$objPHPExcel->getActiveSheet()->SetCellValue('E'.$rowCount, $customerValue);
    							$objPHPExcel->getActiveSheet()->SetCellValue('F'.$rowCount, $customerPOValue);
    							$objPHPExcel->getActiveSheet()->SetCellValue('G'.$rowCount, $facilityValue);
    							$objPHPExcel->getActiveSheet()->SetCellValue('H'.$rowCount, $shipDateValue);
    							$objPHPExcel->getActiveSheet()->getStyle('H'.$rowCount)->getNumberFormat()->setFormatCode('mm/dd/yyyy');
    							
    							$objPHPExcel->getActiveSheet()->SetCellValue('I'.$rowCount, $dateScheduled); //moved from AB
    							
    							$objPHPExcel->getActiveSheet()->SetCellValue('J'.$rowCount, $thisbookingdate);
    							$objPHPExcel->getActiveSheet()->getStyle('J'.$rowCount)->getNumberFormat()->setFormatCode('mm/dd/yyyy');
    							
    							$objPHPExcel->getActiveSheet()->SetCellValue('K'.$rowCount, $batchValue);
    							$objPHPExcel->getActiveSheet()->SetCellValue('L'.$rowCount, $lineNumberValue);
    							$objPHPExcel->getActiveSheet()->SetCellValue('M'.$rowCount, $locationValue);
    							$objPHPExcel->getActiveSheet()->SetCellValue('N'.$rowCount, $qtyValue);
    							$objPHPExcel->getActiveSheet()->SetCellValue('O'.$rowCount, $unitValue);
    							$objPHPExcel->getActiveSheet()->SetCellValue('P'.$rowCount, $lineItemValue);
    							$objPHPExcel->getActiveSheet()->SetCellValue('Q'.$rowCount, $fabricrichText);
    							$objPHPExcel->getActiveSheet()->SetCellValue('R'.$rowCount, $colorrichText);
    							$objPHPExcel->getActiveSheet()->SetCellValue('S'.$rowCount, $cutwidthValue);
    							$objPHPExcel->getActiveSheet()->SetCellValue('T'.$rowCount, $finwidthValue);
    							$objPHPExcel->getActiveSheet()->SetCellValue('U'.$rowCount, $lengthrichText);
    							$objPHPExcel->getActiveSheet()->SetCellValue('V'.$rowCount, $totallfrichText);
    							$objPHPExcel->getActiveSheet()->SetCellValue('W'.$rowCount, $ydsperunitValue);
    							$objPHPExcel->getActiveSheet()->SetCellValue('X'.$rowCount, $totalydsvalue);
    							$objPHPExcel->getActiveSheet()->SetCellValue('Y'.$rowCount, $basePriceValue);
    							$objPHPExcel->getActiveSheet()->SetCellValue('Z'.$rowCount, $breakdownrichText);
    							$objPHPExcel->getActiveSheet()->SetCellValue('AA'.$rowCount, $adjustedColValue);
    							$objPHPExcel->getActiveSheet()->SetCellValue('AB'.$rowCount, $extendedprice);
    							
    							
    							$objPHPExcel->getActiveSheet()->SetCellValue('AC'.$rowCount, $dateProduced);
    							$objPHPExcel->getActiveSheet()->getStyle('AC'.$rowCount)->getNumberFormat()->setFormatCode('mm/dd/yyyy');
    							
    							$objPHPExcel->getActiveSheet()->SetCellValue('AD'.$rowCount, $dateShipped);
    							$objPHPExcel->getActiveSheet()->getStyle('AD'.$rowCount)->getNumberFormat()->setFormatCode('mm/dd/yyyy');
    							
    							$objPHPExcel->getActiveSheet()->SetCellValue('AE'.$rowCount, $trackingNumber);
    							$objPHPExcel->getActiveSheet()->SetCellValue('AF'.$rowCount, $dateInvoiced);
    							$objPHPExcel->getActiveSheet()->getStyle('AF'.$rowCount)->getNumberFormat()->setFormatCode('mm/dd/yyyy');
    							
    							$objPHPExcel->getActiveSheet()->SetCellValue('AG'.$rowCount, $linenotesrichText);
    							
    							
    							
    							
    							$bsQuilt='';
    							$bsMatSize='';
    							$bsDrop='';
    							$bsWidthsEa='';
    							$bsTopWidthsEa='';
    							$bsTopWidthsCL='';
    							$bsTopCutSizeW='';
    							$bsTopCutSizeL='';
    							$bsDropCutSizeW='';
    							$bsDropCutSizeL='';
    							$ccMeshSize='';
    							$ccMeshColor='';
    							$ccWidthsEa='';
    							$ccCL='';
    							$ccActualLF='';
    							
    							
    							if($quoteItem['product_type'] == 'bedspreads' || ($quoteItem['product_type'] == 'calculator' && ($metaArray['calculator-used'] == 'bedspread' || $metaArray['calculator-used'] == 'bedspread-manual'))){
    							    
    							    if($metaArray['quilted'] == '1'){
    							        $bsQuilt=$metaArray['quilting-pattern'].' 6oz';
    							        if(isset($metaArray['fabricid']) && strlen($metaArray['fabricid']) >0){
    							            $thisFabric=$this->Fabrics->get($metaArray['fabricid'])->toArray();
    							            $bsQuilt .= ' '.$thisFabric['bs_backing_material'];
    							        }
    							        
    							    }else{
    							        $bsQuilt='None';
    							    }
    							    
    							    $bsMatSize=$metaArray['custom-top-width-mattress-w'];
    							    $bsDrop=((floatval($metaArray['width'])-floatval($metaArray['custom-top-width-mattress-w']))/2);
                                    $bsWidthsEa=floatval($metaArray['layout']);
                                    $bsTopWidthsEa=intval($qtyValue);
                                    $bsTopWidthsCL=$metaArray['top-widths'];
                                    
                        			$bsTopCutSizeL=$metaArray['top-widths'];
                        			
                        			if($metaArray['railroaded'] == '1'){
                        				$bsTopCutSizeW=(floatval($metaArray['length']) + (floatval($metaArray['extra-inches-seam-hems']) * 2));
                        				
                        				$bsDropCutSizeW='N/A';
                        				$bsDropCutSizeL='N/A';
                        			}else{
                                        $bsTopCutSizeW=$metaArray['top-cut'];
                                        
                        				$bsDropCutSizeW=$metaArray['drop-cut'];
                        				$bsDropCutSizeL=$metaArray['drop-widths'];
                        
                        			}
                        			
                        			
    						    }
    						    
    						    
    						    if($quoteItem['product_type'] == 'cubicle_curtains' || ($quoteItem['product_type'] == 'calculator' && $metaArray['calculator-used'] == 'cubicle-curtain')){
    							    $ccMeshSize=(floatval($metaArray['mesh'])+floatval($allsettings['mesh_heading']));
        							$ccMeshColor=$metaArray['mesh-color'];
        							if($quoteItem['product_type'] == 'cubicle_curtains'){
                    					$eachstart=(floatval($metaArray['width']) / 72);
                    					$ccWidthsEa=substr($eachstart, 0, ((strpos($eachstart, '.')+1)+2));
                    				}else{
                    					$ccWidthsEa=substr($metaArray['widths-each'], 0, ((strpos($metaArray['widths-each'], '.')+1)+2));
                    				}
                    				
                    				
                    				if($quoteItem['product_type'] == 'cubicle_curtains'){
                    				    $meta_length = floatval($metaArray['length']);
                    				    $meta_mesh = floatval($metaArray['mesh']);
                    				    $inches_per_hem = floatval($allsettings['inches_per_hem']);
                    				    $inches_per_seam = floatval($allsettings['inches_per_seam']);
                    				    $inches_per_head = floatval($allsettings['inches_for_header']);
                    				    
                    					if($metaArray['mesh-type'] == 'None'){
                    						$ccCL=($meta_length + $inches_per_hem + $inches_per_head);
                    					}else{
                    					   $vert_rpt = floatval($metaArray['vertical-repeat']);
                    					   $mesh_heading = floatval($allsettings['mesh_heading']);
                                           $mesh_type_calc = (($meta_length - $meta_mesh - $mesh_heading) + $inches_per_hem + $inches_per_seam);
                                           if($vert_rpt == 0) {
                    					    $ccCL=$mesh_type_calc;
                    					   } else {
                    					    $ccCL=(ceil($mesh_type_calc/$vert_rpt) * $vert_rpt);
                    					   }
                    					}
                    				}else{
                    					$ccCL=$metaArray['cl'];
                    				}
                    				
                    				$ccActualLF=(floatval($metaArray['labor-billable'])*intval($qtyValue));
                    				
    						    }
    						    
    							
    							$objPHPExcel->getActiveSheet()->SetCellValue('AH'.$rowCount, $bsQuilt);
    							$objPHPExcel->getActiveSheet()->SetCellValue('AI'.$rowCount, $bsMatSize);
    							$objPHPExcel->getActiveSheet()->SetCellValue('AJ'.$rowCount, $bsDrop);
    							$objPHPExcel->getActiveSheet()->SetCellValue('AK'.$rowCount, $bsWidthsEa);
    							$objPHPExcel->getActiveSheet()->SetCellValue('AL'.$rowCount, $bsTopWidthsEa);
    							$objPHPExcel->getActiveSheet()->SetCellValue('AM'.$rowCount, $bsTopWidthsCL);
    							$objPHPExcel->getActiveSheet()->SetCellValue('AN'.$rowCount, $bsTopCutSizeW);
    							$objPHPExcel->getActiveSheet()->SetCellValue('AO'.$rowCount, $bsTopCutSizeL);
    							$objPHPExcel->getActiveSheet()->SetCellValue('AP'.$rowCount, $bsDropCutSizeW);
    							$objPHPExcel->getActiveSheet()->SetCellValue('AQ'.$rowCount, $bsDropCutSizeL);
    							$objPHPExcel->getActiveSheet()->SetCellValue('AR'.$rowCount, $ccMeshSize);
    							$objPHPExcel->getActiveSheet()->SetCellValue('AS'.$rowCount, $ccMeshColor);
    							$objPHPExcel->getActiveSheet()->SetCellValue('AT'.$rowCount, $ccWidthsEa);
    							$objPHPExcel->getActiveSheet()->SetCellValue('AU'.$rowCount, $ccCL);
    							$objPHPExcel->getActiveSheet()->SetCellValue('AV'.$rowCount, $ccActualLF);
    							
    						
    							if($rowCount % 2 == 0){
    								$thisrowcolor='F8F8F8';
    							}else{
    								$thisrowcolor='FFFFFF';
    							}
    						
    							$objPHPExcel->getActiveSheet()->getStyle('A'.$rowCount.':AV'.$rowCount)->applyFromArray(
    								array(
    									'fill' => array(
    										'type' => \PHPExcel_Style_Fill::FILL_SOLID,
    										'color' => array('rgb' => $thisrowcolor)
    									)
    								)
    							);
    
    							$brlines=explode("<br",$itemDescription);
    							if(count($brlines) < 5){
    								$rowheight=90;
    							}elseif(count($brlines) >= 5 && count($brlines) < 8){
    								$rowheight=120;
    							}else{
    								$rowheight=145;
    							}
    
    							$objPHPExcel->getActiveSheet()->getRowDimension($rowCount)->setRowHeight($rowheight);
    
    							$rowCount++;
    						}
    						
    						
    						//then loop through BATCHED
    						foreach($loop['batches'] as $num => $batchloopitem){
    						    $totalLFvalue='';
    						    
    							if((isset($batchloopitem['invoicedTS']) && $batchloopitem['invoicedTS'] > 1000) && (isset($batchloopitem['shippedTS']) && $batchloopitem['shippedTS'] > 1000) ){
    								//ignore this row, it's already shipped and invoiced
    							}else{
    								$qtyValue=$batchloopitem['qty'];
    								$batchValue=$batchloopitem['batchid'];
    								
    								if($metaArray['specialpricing']=='1'){
    									if($quoteItem['override_active'] == 1){
    										$extendedprice=(floatval($quoteItem['override_price']) * floatval($qtyValue));
    									}else{
    										$extendedprice=(floatval($quoteItem['best_price'])*floatval($qtyValue));
    									}
    								}else{
    								    if($quoteItem['override_active'] == 1){
    										$extendedprice=(floatval($quoteItem['override_price']) * floatval($qtyValue));
    									}else{
    									    $extendedprice=number_format((floatval($quoteItem['pmi_adjusted']) * $qtyValue),2,'.','');
    									}
    								}
    								
    								$totalydsvalue='';
    								if($metaArray['lineitemtype'] == 'simpleproduct'){
    									$totalydsvalue .= round((floatval($metaArray['yds-per-unit']) * floatval($qtyValue)),2);
    								}elseif($metaArray['lineitemtype']=="custom"){
    									if(floatval($metaArray['total-yds']) >0){
    										$totalydsvalue .= round(((floatval($metaArray['total-yds']) / floatval($quoteItem['qty'])) * intval($qtyValue)),2);
    									}else{
    										$totalydsvalue .= round((floatval($metaArray['yds-per-unit']) * floatval($qtyValue)),2);
    									}
    								}else{
    									$totalydsvalue .= round(((floatval($metaArray['total-yds']) / floatval($quoteItem['qty'])) * intval($qtyValue)),2);
    								}
    
    
                                    if($quoteItem['product_type'] == 'track_systems'){
            							if($quoteItem['unit'] == 'linear feet'){
            								$totalLFvalue .= $qtyValue;
            							}else{
            								$totalLFvalue .= '---';
            							}
            						}else{
            							if(isset($metaArray['labor-billable'])){
            								$totalLFvalue .= (floatval($metaArray['labor-billable'])*floatval($qtyValue));
            							}else{
            								$totalLFvalue .= "---";
            							}
            						}
            						
            						$totallfrichText = $totalLFvalue;
    								
    								$dateScheduled='';
    								$dateShipped='';
    								$trackingNumber='';
    								
    								
    								
    								$shipStatusesThisBatch=$this->OrderItemStatus->find('all',['conditions'=>['sherry_batch_id' => $batchValue,'status'=>'Shipped','shipment_id !=' => NULL,'shipment_id !=' => 0]])->toArray();
                    				foreach($shipStatusesThisBatch as $shipStatus){
                    					$shipment=$this->Shipments->get($shipStatus['shipment_id'])->toArray();
                    					$trackingNumber=$shipment['tracking_number'];
                    				}
    								
    								//look up whether this line has been Shipped, if so, populate the variable
            						$thisLineBatchesShipped=$this->OrderItemStatus->find('all',['conditions' => ['order_item_id' => $orderItem['id'], 'status' => 'Shipped', 'sherry_batch_id' => $batchValue]])->toArray();
            						foreach($thisLineBatchesShipped as $shippedBatch){
            							//$dateShipped = date('n/j/Y',$shippedBatch['time']);
            							$shipDateTimeObj=new \DateTime();
            						    $shipDateTimeObj->setTimestamp($shippedBatch['time']);
            							$dateShipped = \PHPExcel_Shared_Date::PHPToExcel($shipDateTimeObj);
            						}
            						
            						
            						$dateScheduled='';
            						$thisLineBatchesScheduled=$this->OrderItemStatus->find('all',['conditions' => ['order_item_id' => $orderItem['id'], 'status' => 'Scheduled', 'sherry_batch_id' => $batchValue]])->toArray();
            						foreach($thisLineBatchesScheduled as $scheduledBatch){
            							//$dateScheduled = date('n/j/Y',$scheduledBatch['time']);
            							$schedDateTimeObj=new \DateTime();
            						    $schedDateTimeObj->setTimestamp($scheduledBatch['time']);
            						    
            							$dateScheduled = \PHPExcel_Shared_Date::PHPToExcel($schedDateTimeObj);
            						}
            						
            						
            						$dateProduced='';
            						$thisLineBatchesCompleted=$this->OrderItemStatus->find('all',['conditions' => ['order_item_id' => $orderItem['id'], 'status' => 'Completed', 'sherry_batch_id' => $batchValue]])->toArray();
            						foreach($thisLineBatchesCompleted as $producedBatch){
            							//$dateProduced = date('n/j/Y',$producedBatch['time']);
            							$prodDateTimeObj=new \DateTime();
            						    $prodDateTimeObj->setTimestamp($producedBatch['time']);
            						    
            							$dateProduced = \PHPExcel_Shared_Date::PHPToExcel($prodDateTimeObj);
            						}
            						
            						$dateInvoiced='';
            						
            						//look up whether this line has been Invoiced, if so, populate the variable
            						$thisLineBatchesInvoiced=$this->OrderItemStatus->find('all',['conditions' => ['order_item_id' => $orderItem['id'], 'status' => 'Invoiced','sherry_batch_id'=>$batchValue]])->toArray();
            						foreach($thisLineBatchesInvoiced as $invoicedBatch){
            							//$dateInvoiced = date('n/j/Y',$invoicedBatch['time']);
            							$invDateTimeObj=new \DateTime();
            						    $invDateTimeObj->setTimestamp($invoicedBatch['time']);
            						    
            							$dateInvoiced = \PHPExcel_Shared_Date::PHPToExcel($invDateTimeObj);
            						}
    						
    						
    						
    								//recalc and override the TOTAL LF, TOTAL YARDS, EXTENDED PRICE variables for [this qty]
    								if($orderRow['project_manager_id'] == 0 || is_null($orderRow['project_manager_id'])){
            						    $managerName='N/A';
            						}else{
            						    $pmLookup=$this->Users->get($orderRow['project_manager_id'])->toArray();
            						    $managerName=$pmLookup['first_name'].' '.$pmLookup['last_name'];
            						}
    								
    								$objPHPExcel->getActiveSheet()->SetCellValue('A'.$rowCount, $orderRow['order_number']);
    								$objPHPExcel->getActiveSheet()->SetCellValue('B'.$rowCount, $quoteNumberValue);
    								
    								$objPHPExcel->getActiveSheet()->SetCellValue('C'.$rowCount, $orderRow['stage']);
    								
    								$objPHPExcel->getActiveSheet()->SetCellValue('D'.$rowCount, $managerName);
    								
    								$objPHPExcel->getActiveSheet()->SetCellValue('E'.$rowCount, $customerValue);
    								$objPHPExcel->getActiveSheet()->SetCellValue('F'.$rowCount, $customerPOValue);
    								$objPHPExcel->getActiveSheet()->SetCellValue('G'.$rowCount, $facilityValue);
    								$objPHPExcel->getActiveSheet()->SetCellValue('H'.$rowCount, $shipDateValue);
    								$objPHPExcel->getActiveSheet()->getStyle('H'.$rowCount)->getNumberFormat()->setFormatCode('mm/dd/yyyy');
    								
    								$objPHPExcel->getActiveSheet()->SetCellValue('I'.$rowCount, $dateScheduled);
    								$objPHPExcel->getActiveSheet()->getStyle('I'.$rowCount)->getNumberFormat()->setFormatCode('mm/dd/yyyy');
    								
    								$objPHPExcel->getActiveSheet()->SetCellValue('J'.$rowCount, $thisbookingdate);
    								$objPHPExcel->getActiveSheet()->getStyle('J'.$rowCount)->getNumberFormat()->setFormatCode('mm/dd/yyyy');
    								
    								$objPHPExcel->getActiveSheet()->SetCellValue('K'.$rowCount, $batchValue);
    								$objPHPExcel->getActiveSheet()->SetCellValue('L'.$rowCount, $lineNumberValue);
    								$objPHPExcel->getActiveSheet()->SetCellValue('M'.$rowCount, $locationValue);
    								$objPHPExcel->getActiveSheet()->SetCellValue('N'.$rowCount, $qtyValue);
    								$objPHPExcel->getActiveSheet()->SetCellValue('O'.$rowCount, $unitValue);
    								$objPHPExcel->getActiveSheet()->SetCellValue('P'.$rowCount, $lineItemValue);
    								$objPHPExcel->getActiveSheet()->SetCellValue('Q'.$rowCount, $fabricrichText);
    								$objPHPExcel->getActiveSheet()->SetCellValue('R'.$rowCount, $colorrichText);
    								$objPHPExcel->getActiveSheet()->SetCellValue('S'.$rowCount, $cutwidthValue);
    								$objPHPExcel->getActiveSheet()->SetCellValue('T'.$rowCount, $finwidthValue);
    								$objPHPExcel->getActiveSheet()->SetCellValue('U'.$rowCount, $lengthrichText);
    								$objPHPExcel->getActiveSheet()->SetCellValue('V'.$rowCount, $totallfrichText);
    								$objPHPExcel->getActiveSheet()->SetCellValue('W'.$rowCount, $ydsperunitValue);
    								$objPHPExcel->getActiveSheet()->SetCellValue('X'.$rowCount, $totalydsvalue);
    								$objPHPExcel->getActiveSheet()->SetCellValue('Y'.$rowCount, $basePriceValue);
    								$objPHPExcel->getActiveSheet()->SetCellValue('Z'.$rowCount, $breakdownrichText);
    								$objPHPExcel->getActiveSheet()->SetCellValue('AA'.$rowCount, $adjustedColValue);
    								$objPHPExcel->getActiveSheet()->SetCellValue('AB'.$rowCount, $extendedprice);
    								
    								
    								
    								$objPHPExcel->getActiveSheet()->SetCellValue('AC'.$rowCount, $dateProduced);
    								$objPHPExcel->getActiveSheet()->getStyle('AC'.$rowCount)->getNumberFormat()->setFormatCode('mm/dd/yyyy');
    								
    								$objPHPExcel->getActiveSheet()->SetCellValue('AD'.$rowCount, $dateShipped);
    								$objPHPExcel->getActiveSheet()->getStyle('AD'.$rowCount)->getNumberFormat()->setFormatCode('mm/dd/yyyy');
    								
    								$objPHPExcel->getActiveSheet()->SetCellValue('AE'.$rowCount, $trackingNumber);
    								$objPHPExcel->getActiveSheet()->SetCellValue('AF'.$rowCount, $dateInvoiced);
    								$objPHPExcel->getActiveSheet()->getStyle('AF'.$rowCount)->getNumberFormat()->setFormatCode('mm/dd/yyyy');
    								
    								$objPHPExcel->getActiveSheet()->SetCellValue('AG'.$rowCount, $linenotesrichText);
    								
    								
    								
    								
    								
    								$bsQuilt='';
        							$bsMatSize='';
        							$bsDrop='';
        							$bsWidthsEa='';
        							$bsTopWidthsEa='';
        							$bsTopWidthsCL='';
        							$bsTopCutSizeW='';
        							$bsTopCutSizeL='';
        							$bsDropCutSizeW='';
        							$bsDropCutSizeL='';
        							$ccMeshSize='';
        							$ccMeshColor='';
        							$ccWidthsEa='';
        							$ccCL='';
        							$ccActualLF='';
        							
        							
        							if($quoteItem['product_type'] == 'bedspreads' || ($quoteItem['product_type'] == 'calculator' && ($metaArray['calculator-used'] == 'bedspread' || $metaArray['calculator-used'] == 'bedspread-manual'))){
        							    
        							    if($metaArray['quilted'] == '1'){
        							        $bsQuilt=$metaArray['quilting-pattern'].' 6oz';
        							        if(isset($metaArray['fabricid']) && strlen($metaArray['fabricid']) >0){
        							            $thisFabric=$this->Fabrics->get($metaArray['fabricid'])->toArray();
        							            $bsQuilt .= ' '.$thisFabric['bs_backing_material'];
        							        }
        							        
        							    }else{
        							        $bsQuilt='None';
        							    }
        							    
        							    $bsMatSize=$metaArray['custom-top-width-mattress-w'];
        							    $bsDrop=((floatval($metaArray['width'])-floatval($metaArray['custom-top-width-mattress-w']))/2);
                                        $bsWidthsEa=floatval($metaArray['layout']);
                                        $bsTopWidthsEa=intval($qtyValue);
                                        $bsTopWidthsCL=$metaArray['top-widths'];
                                        
                            			$bsTopCutSizeL=$metaArray['top-widths'];
                            			
                            			if($metaArray['railroaded'] == '1'){
                            				$bsTopCutSizeW=(floatval($metaArray['length']) + (floatval($metaArray['extra-inches-seam-hems']) * 2));
                            				
                            				$bsDropCutSizeW='N/A';
                            				$bsDropCutSizeL='N/A';
                            			}else{
                                            $bsTopCutSizeW=$metaArray['top-cut'];
                                            
                            				$bsDropCutSizeW=$metaArray['drop-cut'];
                            				$bsDropCutSizeL=$metaArray['drop-widths'];
                            
                            			}
                            			
                            			
        						    }
        						    
        						    
        						    if($quoteItem['product_type'] == 'cubicle_curtains' || ($quoteItem['product_type'] == 'calculator' && $metaArray['calculator-used'] == 'cubicle-curtain')){
        							    $ccMeshSize=(floatval($metaArray['mesh'])+floatval($allsettings['mesh_heading']));
        							    $ccMeshColor=$metaArray['mesh-color'];
        							    
        							    if($quoteItem['product_type'] == 'cubicle_curtains'){
                        					$eachstart=(floatval($metaArray['width']) / 72);
                        					$ccWidthsEa=substr($eachstart, 0, ((strpos($eachstart, '.')+1)+2));
                        				}else{
                        					$ccWidthsEa=substr($metaArray['widths-each'], 0, ((strpos($metaArray['widths-each'], '.')+1)+2));
                        				}
                        				
                        				if($quoteItem['product_type'] == 'cubicle_curtains'){
                        				    $meta_length = floatval($metaArray['length']);
                        				    $meta_mesh = floatval($metaArray['mesh']);
                        				    $inches_per_hem = floatval($allsettings['inches_per_hem']);
                        				    $inches_per_seam = floatval($allsettings['inches_per_seam']);
                        				    $inches_per_head = floatval($allsettings['inches_for_header']);
                        				    
                        					if($metaArray['mesh-type'] == 'None'){
                        						$ccCL=($meta_length + $inches_per_hem + $inches_per_head);
                        					}else{
                        					   $vert_rpt = floatval($metaArray['vertical-repeat']);
                        					   $mesh_heading = floatval($allsettings['mesh_heading']);
                                               $mesh_type_calc = (($meta_length - $meta_mesh - $mesh_heading) + $inches_per_hem + $inches_per_seam);
                                               if($vert_rpt == 0) {
                        					    $ccCL=$mesh_type_calc;
                        					   } else {
                        					    $ccCL=(ceil($mesh_type_calc/$vert_rpt) * $vert_rpt);
                        					   }
                        					}
                        				}else{
                        					$ccCL=$metaArray['cl'];
                        				}
                        				
                        				
                        				$ccActualLF=(floatval($metaArray['labor-billable'])*intval($qtyValue));
                        				
                        				
        						    }
        						    
        							
        							$objPHPExcel->getActiveSheet()->SetCellValue('AH'.$rowCount, $bsQuilt);
        							$objPHPExcel->getActiveSheet()->SetCellValue('AI'.$rowCount, $bsMatSize);
        							$objPHPExcel->getActiveSheet()->SetCellValue('AJ'.$rowCount, $bsDrop);
        							$objPHPExcel->getActiveSheet()->SetCellValue('AK'.$rowCount, $bsWidthsEa);
        							$objPHPExcel->getActiveSheet()->SetCellValue('AL'.$rowCount, $bsTopWidthsEa);
        							$objPHPExcel->getActiveSheet()->SetCellValue('AM'.$rowCount, $bsTopWidthsCL);
        							$objPHPExcel->getActiveSheet()->SetCellValue('AN'.$rowCount, $bsTopCutSizeW);
        							$objPHPExcel->getActiveSheet()->SetCellValue('AO'.$rowCount, $bsTopCutSizeL);
        							$objPHPExcel->getActiveSheet()->SetCellValue('AP'.$rowCount, $bsDropCutSizeW);
        							$objPHPExcel->getActiveSheet()->SetCellValue('AQ'.$rowCount, $bsDropCutSizeL);
        							$objPHPExcel->getActiveSheet()->SetCellValue('AR'.$rowCount, $ccMeshSize);
        							$objPHPExcel->getActiveSheet()->SetCellValue('AS'.$rowCount, $ccMeshColor);
        							$objPHPExcel->getActiveSheet()->SetCellValue('AT'.$rowCount, $ccWidthsEa);
        							$objPHPExcel->getActiveSheet()->SetCellValue('AU'.$rowCount, $ccCL);
        							$objPHPExcel->getActiveSheet()->SetCellValue('AV'.$rowCount, $ccActualLF);
    								
    								
    
    
    								if($rowCount % 2 == 0){
    									$thisrowcolor='F8F8F8';
    								}else{
    									$thisrowcolor='FFFFFF';
    								}
    
    								$objPHPExcel->getActiveSheet()->getStyle('A'.$rowCount.':AV'.$rowCount)->applyFromArray(
    									array(
    										'fill' => array(
    											'type' => \PHPExcel_Style_Fill::FILL_SOLID,
    											'color' => array('rgb' => $thisrowcolor)
    										)
    									)
    								);
    
    								$brlines=explode("<br",$itemDescription);
    								if(count($brlines) < 5){
    									$rowheight=90;
    								}elseif(count($brlines) >= 5 && count($brlines) < 8){
    									$rowheight=120;
    								}else{
    									$rowheight=145;
    								}
    
    								$objPHPExcel->getActiveSheet()->getRowDimension($rowCount)->setRowHeight($rowheight);
    
    								$rowCount++;
    							}
    						
    						}
						
					    }
					}
					
				}
				
			}
			
			$objPHPExcel->getActiveSheet()->getStyle('A1:A'.$rowCount)->getAlignment()->setHorizontal('center');
			$objPHPExcel->getActiveSheet()->getStyle('B1:B'.$rowCount)->getAlignment()->setHorizontal('center');
            $objPHPExcel->getActiveSheet()->getStyle('C1:C'.$rowCount)->getAlignment()->setHorizontal('center');
            $objPHPExcel->getActiveSheet()->getStyle('D1:D'.$rowCount)->getAlignment()->setHorizontal('center');
			$objPHPExcel->getActiveSheet()->getStyle('E1:E'.$rowCount)->getAlignment()->setHorizontal('center');
			$objPHPExcel->getActiveSheet()->getStyle('F1:F'.$rowCount)->getAlignment()->setHorizontal('center');
			$objPHPExcel->getActiveSheet()->getStyle('G1:G'.$rowCount)->getAlignment()->setHorizontal('center');
			$objPHPExcel->getActiveSheet()->getStyle('H1:H'.$rowCount)->getAlignment()->setHorizontal('center');
			$objPHPExcel->getActiveSheet()->getStyle('I1:I'.$rowCount)->getAlignment()->setHorizontal('center');
			$objPHPExcel->getActiveSheet()->getStyle('J1:J'.$rowCount)->getAlignment()->setHorizontal('center');
			$objPHPExcel->getActiveSheet()->getStyle('K1:K'.$rowCount)->getAlignment()->setHorizontal('center');
			$objPHPExcel->getActiveSheet()->getStyle('L1:L'.$rowCount)->getAlignment()->setHorizontal('center');
			$objPHPExcel->getActiveSheet()->getStyle('M1:M'.$rowCount)->getAlignment()->setHorizontal('center');
			$objPHPExcel->getActiveSheet()->getStyle('N1:N'.$rowCount)->getAlignment()->setHorizontal('center');
			$objPHPExcel->getActiveSheet()->getStyle('O1:O'.$rowCount)->getAlignment()->setHorizontal('center');
			$objPHPExcel->getActiveSheet()->getStyle('P1:P'.$rowCount)->getAlignment()->setHorizontal('center');
			$objPHPExcel->getActiveSheet()->getStyle('Q1:Q'.$rowCount)->getAlignment()->setHorizontal('center');
			$objPHPExcel->getActiveSheet()->getStyle('R1:R'.$rowCount)->getAlignment()->setHorizontal('center');
			$objPHPExcel->getActiveSheet()->getStyle('S1:S'.$rowCount)->getAlignment()->setHorizontal('center');
			$objPHPExcel->getActiveSheet()->getStyle('T1:T'.$rowCount)->getAlignment()->setHorizontal('center');
			$objPHPExcel->getActiveSheet()->getStyle('U1:U'.$rowCount)->getAlignment()->setHorizontal('center');
			$objPHPExcel->getActiveSheet()->getStyle('V1:V'.$rowCount)->getAlignment()->setHorizontal('center');
			$objPHPExcel->getActiveSheet()->getStyle('W1:W'.$rowCount)->getAlignment()->setHorizontal('center');
			$objPHPExcel->getActiveSheet()->getStyle('X1:X'.$rowCount)->getAlignment()->setHorizontal('center');
			$objPHPExcel->getActiveSheet()->getStyle('Y1:Y'.$rowCount)->getAlignment()->setHorizontal('center');
			$objPHPExcel->getActiveSheet()->getStyle('Z1:Z'.$rowCount)->getAlignment()->setHorizontal('center');
			$objPHPExcel->getActiveSheet()->getStyle('AA1:AA'.$rowCount)->getAlignment()->setHorizontal('center');
			$objPHPExcel->getActiveSheet()->getStyle('AB1:AB'.$rowCount)->getAlignment()->setHorizontal('center');
			$objPHPExcel->getActiveSheet()->getStyle('AC1:AC'.$rowCount)->getAlignment()->setHorizontal('center');
			$objPHPExcel->getActiveSheet()->getStyle('AD1:AD'.$rowCount)->getAlignment()->setHorizontal('center');
			$objPHPExcel->getActiveSheet()->getStyle('AE1:AE'.$rowCount)->getAlignment()->setHorizontal('center');
			$objPHPExcel->getActiveSheet()->getStyle('AF1:AF'.$rowCount)->getAlignment()->setHorizontal('center');
			$objPHPExcel->getActiveSheet()->getStyle('AG1:AG'.$rowCount)->getAlignment()->setHorizontal('left');
			
			$objPHPExcel->getActiveSheet()->getStyle('AH1:AH'.$rowCount)->getAlignment()->setHorizontal('center');
			$objPHPExcel->getActiveSheet()->getStyle('AI1:AI'.$rowCount)->getAlignment()->setHorizontal('center');
			$objPHPExcel->getActiveSheet()->getStyle('AJ1:AJ'.$rowCount)->getAlignment()->setHorizontal('center');
			$objPHPExcel->getActiveSheet()->getStyle('AK1:AK'.$rowCount)->getAlignment()->setHorizontal('center');
			$objPHPExcel->getActiveSheet()->getStyle('AL1:AL'.$rowCount)->getAlignment()->setHorizontal('center');
			$objPHPExcel->getActiveSheet()->getStyle('AM1:AM'.$rowCount)->getAlignment()->setHorizontal('center');
			$objPHPExcel->getActiveSheet()->getStyle('AN1:AN'.$rowCount)->getAlignment()->setHorizontal('center');
			$objPHPExcel->getActiveSheet()->getStyle('AO1:AO'.$rowCount)->getAlignment()->setHorizontal('center');
			$objPHPExcel->getActiveSheet()->getStyle('AP1:AP'.$rowCount)->getAlignment()->setHorizontal('center');
			$objPHPExcel->getActiveSheet()->getStyle('AQ1:AQ'.$rowCount)->getAlignment()->setHorizontal('center');
			$objPHPExcel->getActiveSheet()->getStyle('AR1:AR'.$rowCount)->getAlignment()->setHorizontal('center');
			$objPHPExcel->getActiveSheet()->getStyle('AS1:AS'.$rowCount)->getAlignment()->setHorizontal('center');
			$objPHPExcel->getActiveSheet()->getStyle('AT1:AT'.$rowCount)->getAlignment()->setHorizontal('center');
			$objPHPExcel->getActiveSheet()->getStyle('AU1:AU'.$rowCount)->getAlignment()->setHorizontal('center');
			$objPHPExcel->getActiveSheet()->getStyle('AV1:AV'.$rowCount)->getAlignment()->setHorizontal('center');
			
			
			$objPHPExcel->getActiveSheet()->getStyle('A1:AV'.$rowCount)->getAlignment()->setVertical(\PHPExcel_Style_Alignment::VERTICAL_TOP );
			
			$objPHPExcel->getActiveSheet()->getStyle('A1:AV'.$rowCount)->getAlignment()->setWrapText(true);
			
			
			
			$objPHPExcel->getActiveSheet()->getStyle("A1:AV".$rowCount)->applyFromArray(
				array(
					'borders' => array(
						'allborders' => array(
							'style' => \PHPExcel_Style_Border::BORDER_THIN,
							'color' => array('rgb' => '111111')
						)
					)
				)
			);
			
			$objPHPExcel->getActiveSheet()->freezePane('A2');
			
          
            
            // Redirect output to a client’s web browser (Excel5)
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="Production Details Backlog Report.xlsx"');
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
	    }else{
	        //display the form
	        $allusers=$this->Users->find('all',['order'=>['last_name'=>'asc','first_name'=>'asc']])->toArray();
		    $this->set('allusers',$allusers);
		    
		    $allcompanies=$this->Customers->find('all',['order'=>['company_name'=>'asc']])->toArray();
		    $this->set('allcompanies',$allcompanies);
		    
		    $allfabrics=$this->Fabrics->find('all',['order'=>['fabric_name'=>'asc','color'=>'asc']])->toArray();
		    $this->set('allfabrics',$allfabrics);
			
	    }
	}
	
	public function producedorders(){
		
		
		if(isset($this->request->data['report_date_start']) && isset($this->request->data['report_date_end'])){
			$this->autoRender=false;
			
			$startTS=strtotime($this->request->data['report_date_start'].' 00:00:00');
			$endTS=strtotime($this->request->data['report_date_end'].' 23:59:59');

            
            
            

			$days=ceil(((($endTS+1)-$startTS)/86400));

			$dateList=array();
			for($i=1; $i<=$days; $i++){
			    $dateList[]=date('Y-m-d', ($startTS+(($i-1)*86400)+21600));
			}

            
            //separate query needed for 'failed to produce' table
            $lookupFailedBatches=$this->SherryCache->find('all',['conditions' => ['date IN' => $dateList]])->toArray();
            $failedBatches=array();
            foreach($lookupFailedBatches as $batch){
                if($batch['batch_completed_date'] == 0 || $batch['batch_completed_date'] > strtotime($batch['date'].' 17:00:00') ){
                    $failedBatches[]=$batch;
                }
            }
            
            $this->set('failedBatches',$failedBatches);
            

			$conditions=[
			    'batch_completed_date >=' => $startTS,
				'batch_completed_date <' => $endTS //,
				//'OR' => ['date IN' => $dateList]
			];
			
			$batches=$this->SherryCache->find('all',['conditions'=>$conditions])->toArray();
			$this->set('batches',$batches);


			$shipments=array();
			foreach($batches as $batch){
				$shipStatusesThisBatch=$this->OrderItemStatus->find('all',['conditions'=>['sherry_batch_id' => $batch['batch_id'],'status'=>'Shipped','shipment_id !=' => NULL,'shipment_id !=' => 0]])->toArray();
				foreach($shipStatusesThisBatch as $shipStatus){
					$shipment=$this->Shipments->get($shipStatus['shipment_id'])->toArray();
					$shipments[$batch['batch_id']]=array('tracking'=>$shipment['tracking_number'],'delivery_address' => $shipment['tracking_number']);
				}
			}
			$this->set('shipments',$shipments);

			$this->set('inputs',$this->request->data);
			$this->render('viewproducedorders');
		}

	}
	
	
	public function producedordersdownload($start,$end){
	    $this->autoRender=false;
	    
	    
		$startTS=strtotime($start.' 00:00:00');
		$endTS=strtotime($end.' 23:59:59');

		$days=ceil(((($endTS+1)-$startTS)/86400));

		$dateList=array();
		for($i=1; $i<=$days; $i++){
		    $dateList[]=date('Y-m-d', ($startTS+(($i-1)*86400)+21600));
		}

		$conditions=[
		    'batch_completed_date >=' => $startTS,
			'batch_completed_date <' => $endTS //,
			//'OR' => ['date IN' => $dateList]
		];
		
		$batches=$this->SherryCache->find('all',['conditions'=>$conditions])->toArray();
		
		
		//separate query needed for 'failed to produce' table
        $lookupFailedBatches=$this->SherryCache->find('all',['conditions' => ['date IN' => $dateList]])->toArray();
        $failedBatches=array();
        foreach($lookupFailedBatches as $batch){
            if($batch['batch_completed_date'] == 0 || $batch['batch_completed_date'] > strtotime($batch['date'].' 17:00:00') ){
                $failedBatches[]=$batch;
            }
        }
        
        $this->set('failedBatches',$failedBatches);
		
	    
	    require($_SERVER['DOCUMENT_ROOT']."/vendor/phpoffice/phpexcel/Classes/PHPExcel.php");
        require($_SERVER['DOCUMENT_ROOT']."/vendor/phpoffice/phpexcel/Classes/PHPExcel/Writer/Excel2007.php");
        
	    $objPHPExcel = new \PHPExcel();
        
        $objPHPExcel->createSheet(0);
        $objPHPExcel->setActiveSheetIndex(0);
        $objPHPExcel->getActiveSheet()->setTitle('PRODUCED');
        
        $rowNumber=1;
        
        $objPHPExcel->getActiveSheet()->SetCellValue('A'.$rowNumber, 'SCHEDULED');
        $objPHPExcel->getActiveSheet()->SetCellValue('B'.$rowNumber, 'PRODUCED');
        $objPHPExcel->getActiveSheet()->SetCellValue('C'.$rowNumber,'WO#');
		$objPHPExcel->getActiveSheet()->SetCellValue('D'.$rowNumber, 'BATCH#');
		$objPHPExcel->getActiveSheet()->SetCellValue('E'.$rowNumber, 'Dollars');
		$objPHPExcel->getActiveSheet()->SetCellValue('F'.$rowNumber, 'CC ea');
		$objPHPExcel->getActiveSheet()->SetCellValue('G'.$rowNumber, 'CC LF');
		$objPHPExcel->getActiveSheet()->SetCellValue('H'.$rowNumber, 'TRK LF');
		$objPHPExcel->getActiveSheet()->SetCellValue('I'.$rowNumber, 'BS ea');
		$objPHPExcel->getActiveSheet()->SetCellValue('J'.$rowNumber, 'DRAPE ea');
		$objPHPExcel->getActiveSheet()->SetCellValue('K'.$rowNumber, 'DRAPE WIDTHS');
		//$objPHPExcel->getActiveSheet()->SetCellValue('L'.$rowNumber, 'TT ea');
		//$objPHPExcel->getActiveSheet()->SetCellValue('M'.$rowNumber, 'TT LF');
		
		$objPHPExcel->getActiveSheet()->SetCellValue('L'.$rowNumber, 'VAL ea');
		$objPHPExcel->getActiveSheet()->SetCellValue('M'.$rowNumber, 'VAL LF');
		$objPHPExcel->getActiveSheet()->SetCellValue('N'.$rowNumber, 'CORN ea');
		$objPHPExcel->getActiveSheet()->SetCellValue('O'.$rowNumber, 'CORN LF');
		
		$objPHPExcel->getActiveSheet()->SetCellValue('P'.$rowNumber, 'WT HW');
		$objPHPExcel->getActiveSheet()->SetCellValue('Q'.$rowNumber, 'B&S');
		
		$objPHPExcel->getActiveSheet()->getStyle('A1:Q1')->applyFromArray(
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
	
		$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(12);
		$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(12);
		$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(12);
		$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(12);
		$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(12);
		$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(12);
		$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(12);
		$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(12);
		$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(12);
		$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(12);
		$objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(19);
		$objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(12);
		$objPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth(12);
		$objPHPExcel->getActiveSheet()->getColumnDimension('N')->setWidth(12);
		$objPHPExcel->getActiveSheet()->getColumnDimension('O')->setWidth(12);
		$objPHPExcel->getActiveSheet()->getColumnDimension('P')->setWidth(12);
		$objPHPExcel->getActiveSheet()->getColumnDimension('Q')->setWidth(12);
		
		
		$objPHPExcel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal('center');
		$objPHPExcel->getActiveSheet()->getStyle('B1')->getAlignment()->setHorizontal('center');
        $objPHPExcel->getActiveSheet()->getStyle('C1')->getAlignment()->setHorizontal('center');
		$objPHPExcel->getActiveSheet()->getStyle('D1')->getAlignment()->setHorizontal('center');
		$objPHPExcel->getActiveSheet()->getStyle('E1')->getAlignment()->setHorizontal('center');
		$objPHPExcel->getActiveSheet()->getStyle('F1')->getAlignment()->setHorizontal('center');
		$objPHPExcel->getActiveSheet()->getStyle('G1')->getAlignment()->setHorizontal('center');
		$objPHPExcel->getActiveSheet()->getStyle('H1')->getAlignment()->setHorizontal('center');
		$objPHPExcel->getActiveSheet()->getStyle('I1')->getAlignment()->setHorizontal('center');
		$objPHPExcel->getActiveSheet()->getStyle('J1')->getAlignment()->setHorizontal('center');
		$objPHPExcel->getActiveSheet()->getStyle('K1')->getAlignment()->setHorizontal('center');
		$objPHPExcel->getActiveSheet()->getStyle('L1')->getAlignment()->setHorizontal('center');
		$objPHPExcel->getActiveSheet()->getStyle('M1')->getAlignment()->setHorizontal('center');
		$objPHPExcel->getActiveSheet()->getStyle('N1')->getAlignment()->setHorizontal('center');
		$objPHPExcel->getActiveSheet()->getStyle('O1')->getAlignment()->setHorizontal('center');
		$objPHPExcel->getActiveSheet()->getStyle('P1')->getAlignment()->setHorizontal('center');
		$objPHPExcel->getActiveSheet()->getStyle('Q1')->getAlignment()->setHorizontal('center');
		
		

        $producedTotals=array(
        	'dollars' => 0,
        	'ccqty' => 0,
        	'cclf' => 0,
        	'tracklf' => 0,
        	'bs' => 0,
        	'drapeqty' => 0,
        	'drapewidths' => 0,
        	//'ttqty' => 0,
        	//'ttlf' => 0,	
        	'valqty' => 0,
        	'vallf' => 0,
        	'cornqty' => 0,
        	'cornlf' => 0,
        	'wthw' => 0,
        	'blinds' => 0
        );
        
        $data='';

        foreach($batches as $batch){
        	if($batch['batch_completed_date'] > $startTS && $batch['batch_completed_date'] <= $endTS){
                
                $rowNumber++;
                
        		$data .= print_r($batch,1);
        
        		$producedTotals['dollars'] = ($producedTotals['dollars'] + floatval($batch['dollars']));
        		$producedTotals['ccqty'] = ($producedTotals['ccqty'] + floatval($batch['cc']));
        		$producedTotals['cclf'] = ($producedTotals['cclf'] + floatval($batch['cclf']));
        		$producedTotals['tracklf'] = ($producedTotals['tracklf'] + floatval($batch['trklf']));
        		$producedTotals['bs'] = ($producedTotals['bs'] + floatval($batch['bs']));
        		$producedTotals['drapeqty'] = ($producedTotals['drapeqty'] + floatval($batch['drape']));
        		$producedTotals['drapewidths'] = ($producedTotals['drapewidths'] + floatval($batch['drape_widths']));
        		//$producedTotals['ttqty'] = ($producedTotals['ttqty'] + floatval($batch['wt']));
        		//$producedTotals['ttlf'] = ($producedTotals['ttlf'] + floatval($batch['wtlf']));
        		$producedTotals['valqty'] = ($producedTotals['valqty'] + floatval($batch['val']));
        		$producedTotals['vallf'] = ($producedTotals['vallf'] + floatval($batch['vallf']));
        		$producedTotals['cornqty'] = ($producedTotals['cornqty'] + floatval($batch['corn']));
        		$producedTotals['cornlf'] = ($producedTotals['cornlf'] + floatval($batch['cornlf']));
        		
        		$producedTotals['wthw'] = ($producedTotals['wthw'] + floatval($batch['wthw']));
        		$producedTotals['blinds'] = ($producedTotals['blinds'] + floatval($batch['blinds']));
        		
        		
        		if($batch['cc'] > 0){
        			$ccea= $batch['cc'];
        		}else{
        			$ccea="";
        		}
        		
        		if($batch['cclf'] > 0){
        			$cclf=$batch['cclf'];
        		}else{
        			$cclf="";
        		}
        		
        		if($batch['trklf'] >0 ){
        			$trklf=$batch['trklf']." LF";
        		}else{
        			$trklf="";
        		}
        
                if($batch['bs'] > 0){
        			$bsea=$batch['bs'];
        		}else{
        			$bsea="";
        		}
        		
        		if($batch['drape'] > 0){
        			$drapeea=$batch['drape'];
        		}else{
        			$drapeea="";
        		}
        		
        		if($batch['drape_widths'] > 0){
        			$drapewidths=$batch['drape_widths'];
        		}else{
        			$drapewidths="";
        		}
        
                /*
                if($batch['wt'] > 0){
        			$ttea=$batch['wt'];
        		}else{
        			$ttea="";
        		}
        		
        		if($batch['wtlf'] > 0){
        			$ttlf=$batch['wtlf'];
        		}else{
        			$ttlf="";
        		}
        		*/
        		
        		if($batch['val'] > 0){
        		    $valea=$batch['val'];
        		}else{
        		    $valea="";
        		}
        		
        		if($batch['vallf'] > 0){
        		    $vallf=$batch['vallf'];
        		}else{
        		    $vallf="";
        		}
        		
        		if($batch['corn'] > 0){
        		    $cornea=$batch['corn'];
        		}else{
        		    $cornea="";
        		}
        		
        		if($batch['cornlf'] > 0){
        		    $cornlf=$batch['cornlf'];
        		}else{
        		    $cornlf="";
        		}
        		
        		if($batch['wthw'] >0){
        			$wthw=$batch['wthw'];
        		}else{
        			$wthw="";
        		}
        		
        		if($batch['blinds'] > 0){
        			$bands=$batch['blinds'];
        		}else{
        			$bands="";
        		}
        
                $objPHPExcel->getActiveSheet()->SetCellValue('A'.$rowNumber, $batch['date']);
                $objPHPExcel->getActiveSheet()->SetCellValue('B'.$rowNumber, date('n/j/y',$batch['batch_completed_date']));
                $objPHPExcel->getActiveSheet()->SetCellValue('C'.$rowNumber, $batch['order_number']);
        		$objPHPExcel->getActiveSheet()->SetCellValue('D'.$rowNumber, $batch['batch_id']);
        		$objPHPExcel->getActiveSheet()->SetCellValue('E'.$rowNumber, "$".number_format($batch['dollars'],2,'.',','));
        		$objPHPExcel->getActiveSheet()->SetCellValue('F'.$rowNumber, $ccea);
        		$objPHPExcel->getActiveSheet()->SetCellValue('G'.$rowNumber, $cclf);
        		$objPHPExcel->getActiveSheet()->SetCellValue('H'.$rowNumber, $trklf);
        		$objPHPExcel->getActiveSheet()->SetCellValue('I'.$rowNumber, $bsea);
        		$objPHPExcel->getActiveSheet()->SetCellValue('J'.$rowNumber, $drapeea);
        		$objPHPExcel->getActiveSheet()->SetCellValue('K'.$rowNumber, $drapewidths);
        		$objPHPExcel->getActiveSheet()->SetCellValue('L'.$rowNumber, $valea);
        		$objPHPExcel->getActiveSheet()->SetCellValue('M'.$rowNumber, $vallf);
        		$objPHPExcel->getActiveSheet()->SetCellValue('N'.$rowNumber, $cornea);
        		$objPHPExcel->getActiveSheet()->SetCellValue('O'.$rowNumber, $cornlf);
        		$objPHPExcel->getActiveSheet()->SetCellValue('P'.$rowNumber, $wthw);
        		$objPHPExcel->getActiveSheet()->SetCellValue('Q'.$rowNumber, $bands);
        		
        
        	}
        }
        
        $rowNumber++;
        
        $objPHPExcel->getActiveSheet()->SetCellValue('D'.$rowNumber, 'TOTALS');
        $objPHPExcel->getActiveSheet()->SetCellValue('E'.$rowNumber, '$'.number_format($producedTotals['dollars'],2,'.',','));
        $objPHPExcel->getActiveSheet()->SetCellValue('F'.$rowNumber, $producedTotals['ccqty']);
		$objPHPExcel->getActiveSheet()->SetCellValue('G'.$rowNumber, $producedTotals['cclf']);
		$objPHPExcel->getActiveSheet()->SetCellValue('H'.$rowNumber, $producedTotals['tracklf']);
		$objPHPExcel->getActiveSheet()->SetCellValue('I'.$rowNumber, $producedTotals['bs']);
		$objPHPExcel->getActiveSheet()->SetCellValue('J'.$rowNumber, $producedTotals['drapeqty']);
		$objPHPExcel->getActiveSheet()->SetCellValue('K'.$rowNumber, $producedTotals['drapewidths']);
		$objPHPExcel->getActiveSheet()->SetCellValue('L'.$rowNumber, $producedTotals['valqty']);
		$objPHPExcel->getActiveSheet()->SetCellValue('M'.$rowNumber, $producedTotals['vallf']);
		$objPHPExcel->getActiveSheet()->SetCellValue('N'.$rowNumber, $producedTotals['cornqty']);
		$objPHPExcel->getActiveSheet()->SetCellValue('O'.$rowNumber, $producedTotals['cornlf']);
		$objPHPExcel->getActiveSheet()->SetCellValue('P'.$rowNumber, $producedTotals['wthw']);
		$objPHPExcel->getActiveSheet()->SetCellValue('Q'.$rowNumber, $producedTotals['blinds']);
		
		$objPHPExcel->getActiveSheet()->getStyle('D'.$rowNumber.':Q'.$rowNumber)->applyFromArray(
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
		
		
        $objPHPExcel->createSheet(1);
        $objPHPExcel->setActiveSheetIndex(1);
        $objPHPExcel->getActiveSheet()->setTitle('FAILED TO PRODUCE');
		
		
		
		$rowNumber=1;
        
        $objPHPExcel->getActiveSheet()->SetCellValue('A'.$rowNumber, 'SCHEDULED');
        $objPHPExcel->getActiveSheet()->SetCellValue('B'.$rowNumber, 'PRODUCED');
        $objPHPExcel->getActiveSheet()->SetCellValue('C'.$rowNumber,'WO#');
		$objPHPExcel->getActiveSheet()->SetCellValue('D'.$rowNumber, 'BATCH#');
		$objPHPExcel->getActiveSheet()->SetCellValue('E'.$rowNumber, 'Dollars');
		$objPHPExcel->getActiveSheet()->SetCellValue('F'.$rowNumber, 'CC ea');
		$objPHPExcel->getActiveSheet()->SetCellValue('G'.$rowNumber, 'CC LF');
		$objPHPExcel->getActiveSheet()->SetCellValue('H'.$rowNumber, 'TRK LF');
		$objPHPExcel->getActiveSheet()->SetCellValue('I'.$rowNumber, 'BS ea');
		$objPHPExcel->getActiveSheet()->SetCellValue('J'.$rowNumber, 'DRAPE ea');
		$objPHPExcel->getActiveSheet()->SetCellValue('K'.$rowNumber, 'DRAPE WIDTHS');
		$objPHPExcel->getActiveSheet()->SetCellValue('L'.$rowNumber, 'VAL ea');
		$objPHPExcel->getActiveSheet()->SetCellValue('M'.$rowNumber, 'VAL LF');
		$objPHPExcel->getActiveSheet()->SetCellValue('N'.$rowNumber, 'CORN ea');
		$objPHPExcel->getActiveSheet()->SetCellValue('O'.$rowNumber, 'CORN LF');
		$objPHPExcel->getActiveSheet()->SetCellValue('P'.$rowNumber, 'WT HW');
		$objPHPExcel->getActiveSheet()->SetCellValue('Q'.$rowNumber, 'B&S');


		
		$objPHPExcel->getActiveSheet()->getStyle('A1:Q1')->applyFromArray(
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
	
		$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(12);
		$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(12);
		$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(12);
		$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(12);
		$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(12);
		$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(12);
		$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(12);
		$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(12);
		$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(12);
		$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(12);
		$objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(19);
		$objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(12);
		$objPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth(12);
		$objPHPExcel->getActiveSheet()->getColumnDimension('N')->setWidth(12);
		$objPHPExcel->getActiveSheet()->getColumnDimension('O')->setWidth(12);
		$objPHPExcel->getActiveSheet()->getColumnDimension('P')->setWidth(12);
		$objPHPExcel->getActiveSheet()->getColumnDimension('Q')->setWidth(12);
		
		
        
		
		$objPHPExcel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal('center');
		$objPHPExcel->getActiveSheet()->getStyle('B1')->getAlignment()->setHorizontal('center');
        $objPHPExcel->getActiveSheet()->getStyle('C1')->getAlignment()->setHorizontal('center');
		$objPHPExcel->getActiveSheet()->getStyle('D1')->getAlignment()->setHorizontal('center');
		$objPHPExcel->getActiveSheet()->getStyle('E1')->getAlignment()->setHorizontal('center');
		$objPHPExcel->getActiveSheet()->getStyle('F1')->getAlignment()->setHorizontal('center');
		$objPHPExcel->getActiveSheet()->getStyle('G1')->getAlignment()->setHorizontal('center');
		$objPHPExcel->getActiveSheet()->getStyle('H1')->getAlignment()->setHorizontal('center');
		$objPHPExcel->getActiveSheet()->getStyle('I1')->getAlignment()->setHorizontal('center');
		$objPHPExcel->getActiveSheet()->getStyle('J1')->getAlignment()->setHorizontal('center');
		$objPHPExcel->getActiveSheet()->getStyle('K1')->getAlignment()->setHorizontal('center');
		$objPHPExcel->getActiveSheet()->getStyle('L1')->getAlignment()->setHorizontal('center');
		$objPHPExcel->getActiveSheet()->getStyle('M1')->getAlignment()->setHorizontal('center');
		$objPHPExcel->getActiveSheet()->getStyle('N1')->getAlignment()->setHorizontal('center');
		$objPHPExcel->getActiveSheet()->getStyle('O1')->getAlignment()->setHorizontal('center');
		$objPHPExcel->getActiveSheet()->getStyle('P1')->getAlignment()->setHorizontal('center');
		$objPHPExcel->getActiveSheet()->getStyle('Q1')->getAlignment()->setHorizontal('center');
		

        $notProducedTotals=array(
        	'dollars' => 0,
        	'ccqty' => 0,
        	'cclf' => 0,
        	'tracklf' => 0,
        	'bs' => 0,
        	'drapeqty' => 0,
        	'drapewidths' => 0,
        	//'ttqty' => 0,
        	//'ttlf' => 0,	
        	'valqty' => 0,
        	'vallf' => 0,
        	'cornqty' => 0,
        	'cornlf' => 0,
        	'wthw' => 0,
        	'blinds' => 0
        );
		
		$failedcount=0;
		
		$rowNumber++;
		
		foreach($failedBatches as $batch){
	        //if(is_null($batch['batch_completed_date']) || $batch['batch_completed_date'] == 0 || $batch['batch_completed_date'] > $endTS){
		        $failedcount++;
		        
		        $notProducedTotals['dollars'] = ($notProducedTotals['dollars'] + floatval($batch['dollars']));
        		$notProducedTotals['ccqty'] = ($notProducedTotals['ccqty'] + floatval($batch['cc']));
        		$notProducedTotals['cclf'] = ($notProducedTotals['cclf'] + floatval($batch['cclf']));
        		$notProducedTotals['tracklf'] = ($notProducedTotals['tracklf'] + floatval($batch['trklf']));
        		$notProducedTotals['bs'] = ($notProducedTotals['bs'] + floatval($batch['bs']));
        		$notProducedTotals['drapeqty'] = ($notProducedTotals['drapeqty'] + floatval($batch['drape']));
        		$notProducedTotals['drapewidths'] = ($notProducedTotals['drapewidths'] + floatval($batch['drape_widths']));
        		
        		//$notProducedTotals['ttqty'] = ($notProducedTotals['ttqty'] + floatval($batch['wt']));
        		//$notProducedTotals['ttlf'] = ($notProducedTotals['ttlf'] + floatval($batch['wtlf']));
        		$notProducedTotals['valqty'] = ($notProducedTotals['valqty'] + floatval($batch['val']));
        		$notProducedTotals['vallf'] = ($notProducedTotals['vallf'] + floatval($batch['vallf']));
        		$notProducedTotals['cornqty'] = ($notProducedTotals['cornqty'] + floatval($batch['corn']));
        		$notProducedTotals['cornlf'] = ($notProducedTotals['cornlf'] + floatval($batch['cornlf']));
        		
        		$notProducedTotals['wthw'] = ($notProducedTotals['wthw'] + floatval($batch['wthw']));
        		$notProducedTotals['blinds'] = ($notProducedTotals['blinds'] + floatval($batch['blinds']));
		        
		        
		        if($batch['cc'] > 0){
        			$ccea= $batch['cc'];
        		}else{
        			$ccea="";
        		}
        		
        		if($batch['cclf'] > 0){
        			$cclf=$batch['cclf'];
        		}else{
        			$cclf="";
        		}
        		
        		if($batch['trklf'] >0 ){
        			$trklf=$batch['trklf']." LF";
        		}else{
        			$trklf="";
        		}
        
                if($batch['bs'] > 0){
        			$bsea=$batch['bs'];
        		}else{
        			$bsea="";
        		}
        		
        		if($batch['drape'] > 0){
        			$drapeea=$batch['drape'];
        		}else{
        			$drapeea="";
        		}
        		
        		if($batch['drape_widths'] > 0){
        			$drapewidths=$batch['drape_widths'];
        		}else{
        			$drapewidths="";
        		}
            
                /*
                if($batch['wt'] > 0){
        			$ttea=$batch['wt'];
        		}else{
        			$ttea="";
        		}
        		
        		if($batch['wtlf'] > 0){
        			$ttlf=$batch['wtlf'];
        		}else{
        			$ttlf="";
        		}
        		*/
        		
        		if($batch['val'] > 0){
        		    $valea=$batch['val'];
        		}else{
        		    $valea="";
        		}
        		
        		if($batch['vallf'] > 0){
        		    $vallf=$batch['vallf'];
        		}else{
        		    $vallf="";
        		}
        		
        		if($batch['corn'] > 0){
        		    $cornea=$batch['corn'];
        		}else{
        		    $cornea="";
        		}
        		
        		if($batch['cornlf'] > 0){
        		    $cornlf=$batch['cornlf'];
        		}else{
        		    $cornlf="";
        		}
        		
        		if($batch['wthw'] >0){
        			$wthw=$batch['wthw'];
        		}else{
        			$wthw="";
        		}
        		
        		if($batch['blinds'] > 0){
        			$bands=$batch['blinds'];
        		}else{
        			$bands="";
        		}
        
                $objPHPExcel->getActiveSheet()->SetCellValue('A'.$rowNumber, $batch['date']);
                $objPHPExcel->getActiveSheet()->SetCellValue('B'.$rowNumber, '---');
                $objPHPExcel->getActiveSheet()->SetCellValue('C'.$rowNumber, $batch['order_number']);
        		$objPHPExcel->getActiveSheet()->SetCellValue('D'.$rowNumber, $batch['batch_id']);
        		$objPHPExcel->getActiveSheet()->SetCellValue('E'.$rowNumber, "$".number_format($batch['dollars'],2,'.',','));
        		$objPHPExcel->getActiveSheet()->SetCellValue('F'.$rowNumber, $ccea);
        		$objPHPExcel->getActiveSheet()->SetCellValue('G'.$rowNumber, $cclf);
        		$objPHPExcel->getActiveSheet()->SetCellValue('H'.$rowNumber, $trklf);
        		$objPHPExcel->getActiveSheet()->SetCellValue('I'.$rowNumber, $bsea);
        		$objPHPExcel->getActiveSheet()->SetCellValue('J'.$rowNumber, $drapeea);
        		$objPHPExcel->getActiveSheet()->SetCellValue('K'.$rowNumber, $drapewidths);
        		$objPHPExcel->getActiveSheet()->SetCellValue('L'.$rowNumber, $valea);
        		$objPHPExcel->getActiveSheet()->SetCellValue('M'.$rowNumber, $vallf);
        		$objPHPExcel->getActiveSheet()->SetCellValue('N'.$rowNumber, $cornea);
        		$objPHPExcel->getActiveSheet()->SetCellValue('O'.$rowNumber, $cornlf);
        		$objPHPExcel->getActiveSheet()->SetCellValue('P'.$rowNumber, $wthw);
        		$objPHPExcel->getActiveSheet()->SetCellValue('Q'.$rowNumber, $bands);
		        
		        $rowNumber++;
	        //}
		}
		
		
		//$rowNumber++;
        
        $objPHPExcel->getActiveSheet()->SetCellValue('D'.$rowNumber, 'TOTALS');
        $objPHPExcel->getActiveSheet()->SetCellValue('E'.$rowNumber, '$'.number_format($notProducedTotals['dollars'],2,'.',','));
        $objPHPExcel->getActiveSheet()->SetCellValue('F'.$rowNumber, $notProducedTotals['ccqty']);
		$objPHPExcel->getActiveSheet()->SetCellValue('G'.$rowNumber, $notProducedTotals['cclf']);
		$objPHPExcel->getActiveSheet()->SetCellValue('H'.$rowNumber, $notProducedTotals['tracklf']);
		$objPHPExcel->getActiveSheet()->SetCellValue('I'.$rowNumber, $notProducedTotals['bs']);
		$objPHPExcel->getActiveSheet()->SetCellValue('J'.$rowNumber, $notProducedTotals['drapeqty']);
		$objPHPExcel->getActiveSheet()->SetCellValue('K'.$rowNumber, $notProducedTotals['drapewidths']);
		$objPHPExcel->getActiveSheet()->SetCellValue('L'.$rowNumber, $notProducedTotals['valqty']);
		$objPHPExcel->getActiveSheet()->SetCellValue('M'.$rowNumber, $notProducedTotals['vallf']);
		$objPHPExcel->getActiveSheet()->SetCellValue('N'.$rowNumber, $notProducedTotals['cornqty']);
		$objPHPExcel->getActiveSheet()->SetCellValue('O'.$rowNumber, $notProducedTotals['cornlf']);
		$objPHPExcel->getActiveSheet()->SetCellValue('P'.$rowNumber, $notProducedTotals['wthw']);
		$objPHPExcel->getActiveSheet()->SetCellValue('Q'.$rowNumber, $notProducedTotals['blinds']);
		
		
		$objPHPExcel->getActiveSheet()->getStyle('D'.$rowNumber.':Q'.$rowNumber)->applyFromArray(
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
		
		$objPHPExcel->setActiveSheetIndex(0);
		
		$objPHPExcel->removeSheetByIndex(2);
		
		
		// Redirect output to a client’s web browser (Excel5)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Production Report '.$start.'-'.$end.'.xlsx"');
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
	
	
	
	public function invoicedordersdownload($start,$end){
	    $this->autoRender=false;
	    
	    
		$startTS=strtotime($start.' 00:00:00');
		$endTS=strtotime($end.' 23:59:59');

		$days=ceil(((($endTS+1)-$startTS)/86400));

		$dateList=array();
		for($i=1; $i<=$days; $i++){
		    $dateList[]=date('Y-m-d', ($startTS+(($i-1)*86400)+21600));
		}

		$conditions=[
		    'batch_invoiced_date >=' => $startTS,
			'batch_invoiced_date <' => $endTS
		];
		
		$batches=$this->SherryCache->find('all',['conditions'=>$conditions])->toArray();
		
	    
	    require($_SERVER['DOCUMENT_ROOT']."/vendor/phpoffice/phpexcel/Classes/PHPExcel.php");
        require($_SERVER['DOCUMENT_ROOT']."/vendor/phpoffice/phpexcel/Classes/PHPExcel/Writer/Excel2007.php");
        
	    $objPHPExcel = new \PHPExcel();
        
        $objPHPExcel->createSheet(0);
        $objPHPExcel->setActiveSheetIndex(0);
        $objPHPExcel->getActiveSheet()->setTitle('INVOICED');
        
        
        $rowNumber=1;
        
        $objPHPExcel->getActiveSheet()->SetCellValue('A'.$rowNumber, 'SCHEDULED');
        $objPHPExcel->getActiveSheet()->SetCellValue('B'.$rowNumber, 'PRODUCED');
        $objPHPExcel->getActiveSheet()->SetCellValue('C'.$rowNumber, 'SHIPPED');
        $objPHPExcel->getActiveSheet()->SetCellValue('D'.$rowNumber, 'INVOICED');
        $objPHPExcel->getActiveSheet()->SetCellValue('E'.$rowNumber,'WO#');
		$objPHPExcel->getActiveSheet()->SetCellValue('F'.$rowNumber, 'BATCH#');
		$objPHPExcel->getActiveSheet()->SetCellValue('G'.$rowNumber, 'Dollars');
		$objPHPExcel->getActiveSheet()->SetCellValue('H'.$rowNumber, 'CC ea');
		$objPHPExcel->getActiveSheet()->SetCellValue('I'.$rowNumber, 'CC LF');
		$objPHPExcel->getActiveSheet()->SetCellValue('J'.$rowNumber, 'TRK LF');
		$objPHPExcel->getActiveSheet()->SetCellValue('K'.$rowNumber, 'BS ea');
		$objPHPExcel->getActiveSheet()->SetCellValue('L'.$rowNumber, 'DRAPE ea');
		$objPHPExcel->getActiveSheet()->SetCellValue('M'.$rowNumber, 'DRAPE WIDTHS');
		$objPHPExcel->getActiveSheet()->SetCellValue('N'.$rowNumber, 'VAL ea');
		$objPHPExcel->getActiveSheet()->SetCellValue('O'.$rowNumber, 'VAL LF');
		$objPHPExcel->getActiveSheet()->SetCellValue('P'.$rowNumber, 'CORN ea');
		$objPHPExcel->getActiveSheet()->SetCellValue('Q'.$rowNumber, 'CORN LF');
		$objPHPExcel->getActiveSheet()->SetCellValue('R'.$rowNumber, 'WT HW');
		$objPHPExcel->getActiveSheet()->SetCellValue('S'.$rowNumber, 'B&S');
		$objPHPExcel->getActiveSheet()->SetCellValue('T'.$rowNumber, 'TRACKING #');
		
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
	
		$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(12);
		$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(12);
		$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(12);
		$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(12);
		$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(12);
		$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(12);
		$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(12);
		$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(12);
		$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(12);
		$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(12);
		$objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(12);
		$objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(12);
		$objPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth(19);
		$objPHPExcel->getActiveSheet()->getColumnDimension('N')->setWidth(12);
		$objPHPExcel->getActiveSheet()->getColumnDimension('O')->setWidth(12);
		$objPHPExcel->getActiveSheet()->getColumnDimension('P')->setWidth(12);
		$objPHPExcel->getActiveSheet()->getColumnDimension('Q')->setWidth(12);
		$objPHPExcel->getActiveSheet()->getColumnDimension('R')->setWidth(12);
		$objPHPExcel->getActiveSheet()->getColumnDimension('S')->setWidth(12);
		$objPHPExcel->getActiveSheet()->getColumnDimension('T')->setWidth(22);
		
		
		$objPHPExcel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal('center');
		$objPHPExcel->getActiveSheet()->getStyle('B1')->getAlignment()->setHorizontal('center');
        $objPHPExcel->getActiveSheet()->getStyle('C1')->getAlignment()->setHorizontal('center');
		$objPHPExcel->getActiveSheet()->getStyle('D1')->getAlignment()->setHorizontal('center');
		$objPHPExcel->getActiveSheet()->getStyle('E1')->getAlignment()->setHorizontal('center');
		$objPHPExcel->getActiveSheet()->getStyle('F1')->getAlignment()->setHorizontal('center');
		$objPHPExcel->getActiveSheet()->getStyle('G1')->getAlignment()->setHorizontal('center');
		$objPHPExcel->getActiveSheet()->getStyle('H1')->getAlignment()->setHorizontal('center');
		$objPHPExcel->getActiveSheet()->getStyle('I1')->getAlignment()->setHorizontal('center');
		$objPHPExcel->getActiveSheet()->getStyle('J1')->getAlignment()->setHorizontal('center');
		$objPHPExcel->getActiveSheet()->getStyle('K1')->getAlignment()->setHorizontal('center');
		$objPHPExcel->getActiveSheet()->getStyle('L1')->getAlignment()->setHorizontal('center');
		$objPHPExcel->getActiveSheet()->getStyle('M1')->getAlignment()->setHorizontal('center');
		$objPHPExcel->getActiveSheet()->getStyle('N1')->getAlignment()->setHorizontal('center');
		$objPHPExcel->getActiveSheet()->getStyle('O1')->getAlignment()->setHorizontal('center');
		$objPHPExcel->getActiveSheet()->getStyle('P1')->getAlignment()->setHorizontal('center');
		$objPHPExcel->getActiveSheet()->getStyle('Q1')->getAlignment()->setHorizontal('center');
		$objPHPExcel->getActiveSheet()->getStyle('R1')->getAlignment()->setHorizontal('center');
		$objPHPExcel->getActiveSheet()->getStyle('S1')->getAlignment()->setHorizontal('center');
		$objPHPExcel->getActiveSheet()->getStyle('T1')->getAlignment()->setHorizontal('center');
		

        $shippedTotals=array(
        	'dollars' => 0,
        	'ccqty' => 0,
        	'cclf' => 0,
        	'tracklf' => 0,
        	'bs' => 0,
        	'drapeqty' => 0,
        	'drapewidths' => 0,
        	//'ttqty' => 0,
        	//'ttlf' => 0,	
        	'valqty' => 0,
        	'vallf' => 0,
        	'cornqty' => 0,
        	'cornlf' => 0,
        	'wthw' => 0,
        	'blinds' => 0
        );
        
        $data='';
        $shipments=array();

        foreach($batches as $batch){
            
            $shipStatusesThisBatch=$this->OrderItemStatus->find('all',['conditions'=>['sherry_batch_id' => $batch['batch_id'],'status'=>'Shipped','shipment_id !=' => NULL,'shipment_id !=' => 0]])->toArray();
			foreach($shipStatusesThisBatch as $shipStatus){
				$shipment=$this->Shipments->get($shipStatus['shipment_id'])->toArray();
				$shipments[$batch['batch_id']]=array('tracking'=>$shipment['tracking_number'],'delivery_address' => $shipment['tracking_number']);
			}
            
            
        	if($batch['batch_invoiced_date'] > $startTS && $batch['batch_invoiced_date'] <= $endTS){
                
                $rowNumber++;
                
        		$data .= print_r($batch,1);
        
        		$shippedTotals['dollars'] = ($shippedTotals['dollars'] + floatval($batch['dollars']));
        		$shippedTotals['ccqty'] = ($shippedTotals['ccqty'] + floatval($batch['cc']));
        		$shippedTotals['cclf'] = ($shippedTotals['cclf'] + floatval($batch['cclf']));
        		$shippedTotals['tracklf'] = ($shippedTotals['tracklf'] + floatval($batch['trklf']));
        		$shippedTotals['bs'] = ($shippedTotals['bs'] + floatval($batch['bs']));
        		$shippedTotals['drapeqty'] = ($shippedTotals['drapeqty'] + floatval($batch['drape']));
        		$shippedTotals['drapewidths'] = ($shippedTotals['drapewidths'] + floatval($batch['drape_widths']));
        		//$shippedTotals['ttqty'] = ($shippedTotals['ttqty'] + floatval($batch['wt']));
        		//$shippedTotals['ttlf'] = ($shippedTotals['ttlf'] + floatval($batch['wtlf']));
        		$shippedTotals['valqty'] = ($shippedTotals['valqty'] + floatval($batch['val']));
        		$shippedTotals['vallf'] = ($shippedTotals['vallf'] + floatval($batch['vallf']));
        		$shippedTotals['cornqty'] = ($shippedTotals['cornqty'] + floatval($batch['corn']));
        		$shippedTotals['cornlf'] = ($shippedTotals['cornlf'] + floatval($batch['cornlf']));
        		$shippedTotals['wthw'] = ($shippedTotals['wthw'] + floatval($batch['wthw']));
        		$shippedTotals['blinds'] = ($shippedTotals['blinds'] + floatval($batch['blinds']));
        		
        		
        		if($batch['cc'] > 0){
        			$ccea= $batch['cc'];
        		}else{
        			$ccea="";
        		}
        		
        		if($batch['cclf'] > 0){
        			$cclf=$batch['cclf'];
        		}else{
        			$cclf="";
        		}
        		
        		if($batch['trklf'] >0 ){
        			$trklf=$batch['trklf']." LF";
        		}else{
        			$trklf="";
        		}
        
                if($batch['bs'] > 0){
        			$bsea=$batch['bs'];
        		}else{
        			$bsea="";
        		}
        		
        		if($batch['drape'] > 0){
        			$drapeea=$batch['drape'];
        		}else{
        			$drapeea="";
        		}
        		
        		if($batch['drape_widths'] > 0){
        			$drapewidths=$batch['drape_widths'];
        		}else{
        			$drapewidths="";
        		}
        
                /*
                if($batch['wt'] > 0){
        			$ttea=$batch['wt'];
        		}else{
        			$ttea="";
        		}
        		
        		if($batch['wtlf'] > 0){
        			$ttlf=$batch['wtlf'];
        		}else{
        			$ttlf="";
        		}
        		*/
        		
        		if($batch['val'] > 0){
        		    $valea=$batch['val'];
        		}else{
        		    $valea="";
        		}
        		
        		if($batch['vallf'] > 0){
        		    $vallf=$batch['vallf'];
        		}else{
        		    $vallf="";
        		}
        		
        		if($batch['corn'] > 0){
        		    $cornea=$batch['corn'];
        		}else{
        		    $cornea="";
        		}
        		
        		if($batch['cornlf'] > 0){
        		    $cornlf=$batch['cornlf'];
        		}else{
        		    $cornlf="";
        		}
        		
        		
        		if($batch['wthw'] >0){
        			$wthw=$batch['wthw'];
        		}else{
        			$wthw="";
        		}
        		
        		if($batch['blinds'] > 0){
        			$bands=$batch['blinds'];
        		}else{
        			$bands="";
        		}
        		
        		
        		if(isset($shipments[$batch['batch_id']]['tracking'])){
        			$trackingnumber=$shipments[$batch['batch_id']]['tracking'];
        		}else{
        			$trackingnumber='';
        		}
        		
        		
        		if($batch['batch_invoiced_date'] > 1000){
        		    $invoicedDate=date('n/j/y',$batch['batch_invoiced_date']);
        		}else{
        		    $invoicedDate='---';
        		}
        		
        		if($batch['batch_completed_date'] > 1000){
        		    $completedDate=date('n/j/y',$batch['batch_completed_date']);
        		}else{
        		    $completedDate='---';
        		}
        		
        		
        		if($batch['batch_shipped_date'] > 1000){
        		    //$shippedDate=date('n/j/y',$batch['batch_shipped_date']);
        		    $batchShippedDateTimeObj=new \DateTime();
            	    $batchShippedDateTimeObj->setTimestamp($batch['batch_shipped_date']);
            		$shippedDate = \PHPExcel_Shared_Date::PHPToExcel($batchShippedDateTimeObj);
        		}else{
        		    $shippedDate='';
        		}
        
                $objPHPExcel->getActiveSheet()->SetCellValue('A'.$rowNumber, $batch['date']);
                $objPHPExcel->getActiveSheet()->SetCellValue('B'.$rowNumber, $completedDate);
                $objPHPExcel->getActiveSheet()->SetCellValue('C'.$rowNumber, $shippedDate);
                $objPHPExcel->getActiveSheet()->getStyle('C'.$rowNumber)->getNumberFormat()->setFormatCode('mm/dd/yyyy');
                
                $objPHPExcel->getActiveSheet()->SetCellValue('D'.$rowNumber, $invoicedDate);
                $objPHPExcel->getActiveSheet()->SetCellValue('E'.$rowNumber, $batch['order_number']);
        		$objPHPExcel->getActiveSheet()->SetCellValue('F'.$rowNumber, $batch['batch_id']);
        		$objPHPExcel->getActiveSheet()->SetCellValue('G'.$rowNumber, "$".number_format($batch['dollars'],2,'.',','));
        		$objPHPExcel->getActiveSheet()->SetCellValue('H'.$rowNumber, $ccea);
        		$objPHPExcel->getActiveSheet()->SetCellValue('I'.$rowNumber, $cclf);
        		$objPHPExcel->getActiveSheet()->SetCellValue('J'.$rowNumber, $trklf);
        		$objPHPExcel->getActiveSheet()->SetCellValue('K'.$rowNumber, $bsea);
        		$objPHPExcel->getActiveSheet()->SetCellValue('L'.$rowNumber, $drapeea);
        		$objPHPExcel->getActiveSheet()->SetCellValue('M'.$rowNumber, $drapewidths);
        		$objPHPExcel->getActiveSheet()->SetCellValue('N'.$rowNumber, $valea);
        		$objPHPExcel->getActiveSheet()->SetCellValue('O'.$rowNumber, $vallf);
        		$objPHPExcel->getActiveSheet()->SetCellValue('P'.$rowNumber, $cornea);
        		$objPHPExcel->getActiveSheet()->SetCellValue('Q'.$rowNumber, $cornlf);
        		$objPHPExcel->getActiveSheet()->SetCellValue('R'.$rowNumber, $wthw);
        		$objPHPExcel->getActiveSheet()->SetCellValue('S'.$rowNumber, $bands);
        		$objPHPExcel->getActiveSheet()->SetCellValueExplicit('T'.$rowNumber, $trackingnumber,\PHPExcel_Cell_DataType::TYPE_STRING);
        		
        		
        
        	}
        }
        
        $rowNumber++;
        
        $objPHPExcel->getActiveSheet()->SetCellValue('F'.$rowNumber, 'TOTALS');
        $objPHPExcel->getActiveSheet()->SetCellValue('G'.$rowNumber, '$'.number_format($shippedTotals['dollars'],2,'.',','));
        $objPHPExcel->getActiveSheet()->SetCellValue('H'.$rowNumber, $shippedTotals['ccqty']);
		$objPHPExcel->getActiveSheet()->SetCellValue('I'.$rowNumber, $shippedTotals['cclf']);
		$objPHPExcel->getActiveSheet()->SetCellValue('J'.$rowNumber, $shippedTotals['tracklf']);
		$objPHPExcel->getActiveSheet()->SetCellValue('K'.$rowNumber, $shippedTotals['bs']);
		$objPHPExcel->getActiveSheet()->SetCellValue('L'.$rowNumber, $shippedTotals['drapeqty']);
		$objPHPExcel->getActiveSheet()->SetCellValue('M'.$rowNumber, $shippedTotals['drapewidths']);
		$objPHPExcel->getActiveSheet()->SetCellValue('N'.$rowNumber, $shippedTotals['valqty']);
		$objPHPExcel->getActiveSheet()->SetCellValue('O'.$rowNumber, $shippedTotals['vallf']);
		$objPHPExcel->getActiveSheet()->SetCellValue('P'.$rowNumber, $shippedTotals['cornqty']);
		$objPHPExcel->getActiveSheet()->SetCellValue('Q'.$rowNumber, $shippedTotals['cornlf']);
		$objPHPExcel->getActiveSheet()->SetCellValue('R'.$rowNumber, $shippedTotals['wthw']);
		$objPHPExcel->getActiveSheet()->SetCellValue('S'.$rowNumber, $shippedTotals['blinds']);
		
		$objPHPExcel->getActiveSheet()->getStyle('F'.$rowNumber.':S'.$rowNumber)->applyFromArray(
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
			
			
		$objPHPExcel->removeSheetByIndex(1);
		
		
		// Redirect output to a client’s web browser (Excel5)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Invoiced Report '.$start.'-'.$end.'.xlsx"');
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
	
	
	
	public function shippedordersdownload($start,$end){
	    $this->autoRender=false;
	    
	    
		$startTS=strtotime($start.' 00:00:00');
		$endTS=strtotime($end.' 23:59:59');

		$days=ceil(((($endTS+1)-$startTS)/86400));

		$dateList=array();
		for($i=1; $i<=$days; $i++){
		    $dateList[]=date('Y-m-d', ($startTS+(($i-1)*86400)+21600));
		}

		$conditions=[
		    'batch_shipped_date >=' => $startTS,
			'batch_shipped_date <' => $endTS //,
			//'OR' => ['date IN' => $dateList]
		];
		
		$batches=$this->SherryCache->find('all',['conditions'=>$conditions])->toArray();
		
		//separate query needed for 'failed to produce' table
        $lookupFailedBatches=$this->SherryCache->find('all',['conditions' => ['date IN' => $dateList]])->toArray();
        $failedBatches=array();
        foreach($lookupFailedBatches as $batch){
            if($batch['batch_shipped_date'] == 0 || $batch['batch_shipped_date'] > strtotime($batch['date'].' 17:00:00') ){
                $failedBatches[]=$batch;
            }
        }
        
        $this->set('failedBatches',$failedBatches);
	    
	    require($_SERVER['DOCUMENT_ROOT']."/vendor/phpoffice/phpexcel/Classes/PHPExcel.php");
        require($_SERVER['DOCUMENT_ROOT']."/vendor/phpoffice/phpexcel/Classes/PHPExcel/Writer/Excel2007.php");
        
	    $objPHPExcel = new \PHPExcel();
        
        $objPHPExcel->createSheet(0);
        $objPHPExcel->setActiveSheetIndex(0);
        $objPHPExcel->getActiveSheet()->setTitle('SHIPPED');
        
        $rowNumber=1;
        
        $objPHPExcel->getActiveSheet()->SetCellValue('A'.$rowNumber, 'SCHEDULED');
        $objPHPExcel->getActiveSheet()->SetCellValue('B'.$rowNumber, 'PRODUCED');
        $objPHPExcel->getActiveSheet()->SetCellValue('C'.$rowNumber, 'SHIPPED');
        $objPHPExcel->getActiveSheet()->SetCellValue('D'.$rowNumber, 'INVOICED');
        $objPHPExcel->getActiveSheet()->SetCellValue('E'.$rowNumber,'WO#');
        
        $objPHPExcel->getActiveSheet()->SetCellValue('F'.$rowNumber,'TYPE');
        
        $objPHPExcel->getActiveSheet()->SetCellValue('G'.$rowNumber, 'CUSTOMER');
        $objPHPExcel->getActiveSheet()->SetCellValue('H'.$rowNumber, 'CUST PO#');
        
        $objPHPExcel->getActiveSheet()->SetCellValue('I'.$rowNumber, 'RECIPIENT');
        
		$objPHPExcel->getActiveSheet()->SetCellValue('J'.$rowNumber, 'BATCH#');
		$objPHPExcel->getActiveSheet()->SetCellValue('K'.$rowNumber, 'Dollars');
		$objPHPExcel->getActiveSheet()->SetCellValue('L'.$rowNumber, 'CC ea');
		$objPHPExcel->getActiveSheet()->SetCellValue('M'.$rowNumber, 'CC LF');
		$objPHPExcel->getActiveSheet()->SetCellValue('N'.$rowNumber, 'TRK LF');
		$objPHPExcel->getActiveSheet()->SetCellValue('O'.$rowNumber, 'BS ea');
		$objPHPExcel->getActiveSheet()->SetCellValue('P'.$rowNumber, 'DRAPE ea');
		$objPHPExcel->getActiveSheet()->SetCellValue('Q'.$rowNumber, 'DRAPE WIDTHS');
		$objPHPExcel->getActiveSheet()->SetCellValue('R'.$rowNumber, 'VAL ea');
		$objPHPExcel->getActiveSheet()->SetCellValue('S'.$rowNumber, 'VAL LF');
		$objPHPExcel->getActiveSheet()->SetCellValue('T'.$rowNumber, 'CORN ea');
		$objPHPExcel->getActiveSheet()->SetCellValue('U'.$rowNumber, 'CORN LF');
		$objPHPExcel->getActiveSheet()->SetCellValue('V'.$rowNumber, 'WT HW');
		$objPHPExcel->getActiveSheet()->SetCellValue('W'.$rowNumber, 'B&S');
		$objPHPExcel->getActiveSheet()->SetCellValue('X'.$rowNumber, 'TRACKING #');
	
		$objPHPExcel->getActiveSheet()->getStyle('A1:X1')->applyFromArray(
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
	
		$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(12);
		$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(12);
		$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(12);
		$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(12);
		$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(12);
		$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(16);
		$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(25);
		$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(25);
		$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(25);
		$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(12);
		$objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(12);
		$objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(12);
		$objPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth(12);
		$objPHPExcel->getActiveSheet()->getColumnDimension('N')->setWidth(12);
		$objPHPExcel->getActiveSheet()->getColumnDimension('O')->setWidth(12);
		$objPHPExcel->getActiveSheet()->getColumnDimension('P')->setWidth(12);
		$objPHPExcel->getActiveSheet()->getColumnDimension('Q')->setWidth(19);
		$objPHPExcel->getActiveSheet()->getColumnDimension('R')->setWidth(12);
		$objPHPExcel->getActiveSheet()->getColumnDimension('S')->setWidth(12);
		$objPHPExcel->getActiveSheet()->getColumnDimension('T')->setWidth(12);
		$objPHPExcel->getActiveSheet()->getColumnDimension('U')->setWidth(12);
		$objPHPExcel->getActiveSheet()->getColumnDimension('V')->setWidth(12);
		$objPHPExcel->getActiveSheet()->getColumnDimension('W')->setWidth(12);
		$objPHPExcel->getActiveSheet()->getColumnDimension('X')->setWidth(22);
		
		
		$objPHPExcel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal('center');
		$objPHPExcel->getActiveSheet()->getStyle('B1')->getAlignment()->setHorizontal('center');
        $objPHPExcel->getActiveSheet()->getStyle('C1')->getAlignment()->setHorizontal('center');
		$objPHPExcel->getActiveSheet()->getStyle('D1')->getAlignment()->setHorizontal('center');
		$objPHPExcel->getActiveSheet()->getStyle('E1')->getAlignment()->setHorizontal('center');
		$objPHPExcel->getActiveSheet()->getStyle('F1')->getAlignment()->setHorizontal('center');
		$objPHPExcel->getActiveSheet()->getStyle('G1')->getAlignment()->setHorizontal('center');
		$objPHPExcel->getActiveSheet()->getStyle('H1')->getAlignment()->setHorizontal('center');
		$objPHPExcel->getActiveSheet()->getStyle('I1')->getAlignment()->setHorizontal('center');
		$objPHPExcel->getActiveSheet()->getStyle('J1')->getAlignment()->setHorizontal('center');
		$objPHPExcel->getActiveSheet()->getStyle('K1')->getAlignment()->setHorizontal('center');
		$objPHPExcel->getActiveSheet()->getStyle('L1')->getAlignment()->setHorizontal('center');
		$objPHPExcel->getActiveSheet()->getStyle('M1')->getAlignment()->setHorizontal('center');
		$objPHPExcel->getActiveSheet()->getStyle('N1')->getAlignment()->setHorizontal('center');
		$objPHPExcel->getActiveSheet()->getStyle('O1')->getAlignment()->setHorizontal('center');
		$objPHPExcel->getActiveSheet()->getStyle('P1')->getAlignment()->setHorizontal('center');
		$objPHPExcel->getActiveSheet()->getStyle('Q1')->getAlignment()->setHorizontal('center');
		$objPHPExcel->getActiveSheet()->getStyle('R1')->getAlignment()->setHorizontal('center');
		$objPHPExcel->getActiveSheet()->getStyle('S1')->getAlignment()->setHorizontal('center');
		$objPHPExcel->getActiveSheet()->getStyle('T1')->getAlignment()->setHorizontal('center');
		$objPHPExcel->getActiveSheet()->getStyle('U1')->getAlignment()->setHorizontal('center');
		$objPHPExcel->getActiveSheet()->getStyle('V1')->getAlignment()->setHorizontal('center');
		$objPHPExcel->getActiveSheet()->getStyle('W1')->getAlignment()->setHorizontal('center');
		$objPHPExcel->getActiveSheet()->getStyle('X1')->getAlignment()->setHorizontal('center');
		

        $shippedTotals=array(
        	'dollars' => 0,
        	'ccqty' => 0,
        	'cclf' => 0,
        	'tracklf' => 0,
        	'bs' => 0,
        	'drapeqty' => 0,
        	'drapewidths' => 0,
        	//'ttqty' => 0,
        	//'ttlf' => 0,	
        	'valqty' => 0,
        	'vallf' => 0,
        	'cornqty' => 0,
        	'cornlf' => 0,
        	'wthw' => 0,
        	'blinds' => 0
        );
        
        $data='';
        $shipments=array();

        foreach($batches as $batch){
            
            $shipStatusesThisBatch=$this->OrderItemStatus->find('all',['conditions'=>['sherry_batch_id' => $batch['batch_id'],'status'=>'Shipped','shipment_id !=' => NULL,'shipment_id !=' => 0]])->toArray();
			foreach($shipStatusesThisBatch as $shipStatus){
				$shipment=$this->Shipments->get($shipStatus['shipment_id'])->toArray();
				$shipments[$batch['batch_id']]=array('tracking'=>$shipment['tracking_number'],'delivery_address' => $shipment['tracking_number']);
			}
            
            
        	if($batch['batch_shipped_date'] > $startTS && $batch['batch_shipped_date'] <= $endTS){
                
                $rowNumber++;
                
        		$data .= print_r($batch,1);
        
        		$shippedTotals['dollars'] = ($shippedTotals['dollars'] + floatval($batch['dollars']));
        		$shippedTotals['ccqty'] = ($shippedTotals['ccqty'] + floatval($batch['cc']));
        		$shippedTotals['cclf'] = ($shippedTotals['cclf'] + floatval($batch['cclf']));
        		$shippedTotals['tracklf'] = ($shippedTotals['tracklf'] + floatval($batch['trklf']));
        		$shippedTotals['bs'] = ($shippedTotals['bs'] + floatval($batch['bs']));
        		$shippedTotals['drapeqty'] = ($shippedTotals['drapeqty'] + floatval($batch['drape']));
        		$shippedTotals['drapewidths'] = ($shippedTotals['drapewidths'] + floatval($batch['drape_widths']));
        		//$shippedTotals['ttqty'] = ($shippedTotals['ttqty'] + floatval($batch['wt']));
        		//$shippedTotals['ttlf'] = ($shippedTotals['ttlf'] + floatval($batch['wtlf']));
        		$shippedTotals['valqty'] = ($shippedTotals['valqty'] + floatval($batch['val']));
        		$shippedTotals['vallf'] = ($shippedTotals['vallf'] + floatval($batch['vallf']));
        		$shippedTotals['cornqty'] = ($shippedTotals['cornqty'] + floatval($batch['corn']));
        		$shippedTotals['cornlf'] = ($shippedTotals['cornlf'] + floatval($batch['cornlf']));
        		$shippedTotals['wthw'] = ($shippedTotals['wthw'] + floatval($batch['wthw']));
        		$shippedTotals['blinds'] = ($shippedTotals['blinds'] + floatval($batch['blinds']));
        		
        		
        		if($batch['cc'] > 0){
        			$ccea= $batch['cc'];
        		}else{
        			$ccea="";
        		}
        		
        		if($batch['cclf'] > 0){
        			$cclf=$batch['cclf'];
        		}else{
        			$cclf="";
        		}
        		
        		if($batch['trklf'] >0 ){
        			$trklf=$batch['trklf']." LF";
        		}else{
        			$trklf="";
        		}
        
                if($batch['bs'] > 0){
        			$bsea=$batch['bs'];
        		}else{
        			$bsea="";
        		}
        		
        		if($batch['drape'] > 0){
        			$drapeea=$batch['drape'];
        		}else{
        			$drapeea="";
        		}
        		
        		if($batch['drape_widths'] > 0){
        			$drapewidths=$batch['drape_widths'];
        		}else{
        			$drapewidths="";
        		}
        
                /*
                if($batch['wt'] > 0){
        			$ttea=$batch['wt'];
        		}else{
        			$ttea="";
        		}
        		
        		if($batch['wtlf'] > 0){
        			$ttlf=$batch['wtlf'];
        		}else{
        			$ttlf="";
        		}
        		*/
        		
        		if($batch['val'] > 0){
        		    $valea=$batch['val'];
        		}else{
        		    $valea="";
        		}
        		
        		if($batch['vallf'] > 0){
        		    $vallf=$batch['vallf'];
        		}else{
        		    $vallf="";
        		}
        		
        		if($batch['corn'] > 0){
        		    $cornea=$batch['corn'];
        		}else{
        		    $cornea="";
        		}
        		
        		if($batch['cornlf'] > 0){
        		    $cornlf=$batch['cornlf'];
        		}else{
        		    $cornlf="";
        		}
        		
        		if($batch['wthw'] >0){
        			$wthw=$batch['wthw'];
        		}else{
        			$wthw="";
        		}
        		
        		if($batch['blinds'] > 0){
        			$bands=$batch['blinds'];
        		}else{
        			$bands="";
        		}
        		
        		
        		if(isset($shipments[$batch['batch_id']]['tracking'])){
        			$trackingnumber=$shipments[$batch['batch_id']]['tracking'];
        		}else{
        			$trackingnumber='';
        		}
        		
        		
        		if($batch['batch_invoiced_date'] > 1000){
        		    $invoicedDate=date('n/j/y',$batch['batch_invoiced_date']);
        		}else{
        		    $invoicedDate='---';
        		}
        
                $objPHPExcel->getActiveSheet()->SetCellValue('A'.$rowNumber, $batch['date']);
                $objPHPExcel->getActiveSheet()->SetCellValue('B'.$rowNumber, date('n/j/y',$batch['batch_completed_date']));
                $objPHPExcel->getActiveSheet()->SetCellValue('C'.$rowNumber, date('n/j/y',$batch['batch_shipped_date']));
                $objPHPExcel->getActiveSheet()->SetCellValue('D'.$rowNumber, $invoicedDate);
                $objPHPExcel->getActiveSheet()->SetCellValue('E'.$rowNumber, $batch['order_number']);
                $objPHPExcel->getActiveSheet()->SetCellValue('F'.$rowNumber, $batch['order_type']);
                $objPHPExcel->getActiveSheet()->SetCellValue('G'.$rowNumber, $batch['company_name']);
                $objPHPExcel->getActiveSheet()->SetCellValue('H'.$rowNumber, $batch['customer_po_number']);
                
                $objPHPExcel->getActiveSheet()->SetCellValue('I'.$rowNumber, $batch['facility']);
                
        		$objPHPExcel->getActiveSheet()->SetCellValue('J'.$rowNumber, $batch['batch_id']);
        		$objPHPExcel->getActiveSheet()->SetCellValue('K'.$rowNumber, $batch['dollars']);
        		$objPHPExcel->getActiveSheet()->SetCellValue('L'.$rowNumber, $ccea);
        		$objPHPExcel->getActiveSheet()->SetCellValue('M'.$rowNumber, $cclf);
        		$objPHPExcel->getActiveSheet()->SetCellValue('N'.$rowNumber, $trklf);
        		$objPHPExcel->getActiveSheet()->SetCellValue('O'.$rowNumber, $bsea);
        		$objPHPExcel->getActiveSheet()->SetCellValue('P'.$rowNumber, $drapeea);
        		$objPHPExcel->getActiveSheet()->SetCellValue('Q'.$rowNumber, $drapewidths);
        		$objPHPExcel->getActiveSheet()->SetCellValue('R'.$rowNumber, $valea);
        		$objPHPExcel->getActiveSheet()->SetCellValue('S'.$rowNumber, $vallf);
        		$objPHPExcel->getActiveSheet()->SetCellValue('T'.$rowNumber, $cornea);
        		$objPHPExcel->getActiveSheet()->SetCellValue('U'.$rowNumber, $cornlf);
        		$objPHPExcel->getActiveSheet()->SetCellValue('V'.$rowNumber, $wthw);
        		$objPHPExcel->getActiveSheet()->SetCellValue('W'.$rowNumber, $bands);
        		$objPHPExcel->getActiveSheet()->SetCellValueExplicit('X'.$rowNumber, $trackingnumber,\PHPExcel_Cell_DataType::TYPE_STRING);
        		
        		
        
        	}
        }
        
        $rowNumber++;
        
        
        $objPHPExcel->getActiveSheet()->SetCellValue('J'.$rowNumber, 'TOTALS');
        $objPHPExcel->getActiveSheet()->SetCellValue('K'.$rowNumber, $shippedTotals['dollars']);
        $objPHPExcel->getActiveSheet()->SetCellValue('L'.$rowNumber, $shippedTotals['ccqty']);
		$objPHPExcel->getActiveSheet()->SetCellValue('M'.$rowNumber, $shippedTotals['cclf']);
		$objPHPExcel->getActiveSheet()->SetCellValue('N'.$rowNumber, $shippedTotals['tracklf']);
		$objPHPExcel->getActiveSheet()->SetCellValue('O'.$rowNumber, $shippedTotals['bs']);
		$objPHPExcel->getActiveSheet()->SetCellValue('P'.$rowNumber, $shippedTotals['drapeqty']);
		$objPHPExcel->getActiveSheet()->SetCellValue('Q'.$rowNumber, $shippedTotals['drapewidths']);
		$objPHPExcel->getActiveSheet()->SetCellValue('R'.$rowNumber, $shippedTotals['valqty']);
		$objPHPExcel->getActiveSheet()->SetCellValue('S'.$rowNumber, $shippedTotals['vallf']);
		$objPHPExcel->getActiveSheet()->SetCellValue('T'.$rowNumber, $shippedTotals['cornqty']);
		$objPHPExcel->getActiveSheet()->SetCellValue('U'.$rowNumber, $shippedTotals['cornlf']);
		$objPHPExcel->getActiveSheet()->SetCellValue('V'.$rowNumber, $shippedTotals['wthw']);
		$objPHPExcel->getActiveSheet()->SetCellValue('W'.$rowNumber, $shippedTotals['blinds']);
		
		
		//set DOLLARS column to CURRENCY format
		$objPHPExcel->getActiveSheet()->getStyle('K2:K'.$rowNumber)->getNumberFormat()->setFormatCode(\PHPExcel_Style_NumberFormat::FORMAT_CURRENCY_USD);
		
		
		$objPHPExcel->getActiveSheet()->getStyle('J'.$rowNumber.':W'.$rowNumber)->applyFromArray(
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
		
		
        $objPHPExcel->createSheet(1);
        $objPHPExcel->setActiveSheetIndex(1);
        $objPHPExcel->getActiveSheet()->setTitle('FAILED TO SHIP');
		
		
		
		$rowNumber=1;
        
        $objPHPExcel->getActiveSheet()->SetCellValue('A'.$rowNumber, 'SCHEDULED');
        $objPHPExcel->getActiveSheet()->SetCellValue('B'.$rowNumber, 'PRODUCED');
        $objPHPExcel->getActiveSheet()->SetCellValue('C'.$rowNumber, 'SHIPPED');
        $objPHPExcel->getActiveSheet()->SetCellValue('D'.$rowNumber, 'INVOICED');
        $objPHPExcel->getActiveSheet()->SetCellValue('E'.$rowNumber,'WO#');
        
        $objPHPExcel->getActiveSheet()->SetCellValue('F'.$rowNumber, 'TYPE');
        
        $objPHPExcel->getActiveSheet()->SetCellValue('G'.$rowNumber, 'CUSTOMER');
        $objPHPExcel->getActiveSheet()->SetCellValue('H'.$rowNumber, 'CUST PO#');
        
        $objPHPExcel->getActiveSheet()->SetCellValue('I'.$rowNumber, 'RECIPIENT');
        
		$objPHPExcel->getActiveSheet()->SetCellValue('J'.$rowNumber, 'BATCH#');
	
		$objPHPExcel->getActiveSheet()->SetCellValue('K'.$rowNumber, 'Dollars');
		$objPHPExcel->getActiveSheet()->SetCellValue('L'.$rowNumber, 'CC ea');
		$objPHPExcel->getActiveSheet()->SetCellValue('M'.$rowNumber, 'CC LF');
		$objPHPExcel->getActiveSheet()->SetCellValue('N'.$rowNumber, 'TRK LF');
		$objPHPExcel->getActiveSheet()->SetCellValue('O'.$rowNumber, 'BS ea');
		$objPHPExcel->getActiveSheet()->SetCellValue('P'.$rowNumber, 'DRAPE ea');
		$objPHPExcel->getActiveSheet()->SetCellValue('Q'.$rowNumber, 'DRAPE WIDTHS');
		$objPHPExcel->getActiveSheet()->SetCellValue('R'.$rowNumber, 'VAL ea');
		$objPHPExcel->getActiveSheet()->SetCellValue('S'.$rowNumber, 'VAL LF');
		$objPHPExcel->getActiveSheet()->SetCellValue('T'.$rowNumber, 'CORN ea');
		$objPHPExcel->getActiveSheet()->SetCellValue('U'.$rowNumber, 'CORN LF');
		$objPHPExcel->getActiveSheet()->SetCellValue('V'.$rowNumber, 'WT HW');
		$objPHPExcel->getActiveSheet()->SetCellValue('W'.$rowNumber, 'B&S');
		$objPHPExcel->getActiveSheet()->SetCellValue('X'.$rowNumber, 'TRACKING #');


		
		$objPHPExcel->getActiveSheet()->getStyle('A1:X1')->applyFromArray(
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
	
		$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(12);
		$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(12);
		$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(12);
		$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(12);
		$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(12);
		$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(16);
		$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(25);
		$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(25);
		$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(25);
		$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(12);
		$objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(12);
		$objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(12);
		$objPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth(12);
		$objPHPExcel->getActiveSheet()->getColumnDimension('N')->setWidth(12);
		$objPHPExcel->getActiveSheet()->getColumnDimension('O')->setWidth(12);
		$objPHPExcel->getActiveSheet()->getColumnDimension('P')->setWidth(12);
		$objPHPExcel->getActiveSheet()->getColumnDimension('Q')->setWidth(19);
		$objPHPExcel->getActiveSheet()->getColumnDimension('R')->setWidth(12);
		$objPHPExcel->getActiveSheet()->getColumnDimension('S')->setWidth(12);
		$objPHPExcel->getActiveSheet()->getColumnDimension('T')->setWidth(12);
		$objPHPExcel->getActiveSheet()->getColumnDimension('U')->setWidth(12);
		$objPHPExcel->getActiveSheet()->getColumnDimension('V')->setWidth(12);
		$objPHPExcel->getActiveSheet()->getColumnDimension('W')->setWidth(12);
		$objPHPExcel->getActiveSheet()->getColumnDimension('X')->setWidth(22);
		
		
		$objPHPExcel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal('center');
		$objPHPExcel->getActiveSheet()->getStyle('B1')->getAlignment()->setHorizontal('center');
        $objPHPExcel->getActiveSheet()->getStyle('C1')->getAlignment()->setHorizontal('center');
		$objPHPExcel->getActiveSheet()->getStyle('D1')->getAlignment()->setHorizontal('center');
		$objPHPExcel->getActiveSheet()->getStyle('E1')->getAlignment()->setHorizontal('center');
		$objPHPExcel->getActiveSheet()->getStyle('F1')->getAlignment()->setHorizontal('center');
		$objPHPExcel->getActiveSheet()->getStyle('G1')->getAlignment()->setHorizontal('center');
		$objPHPExcel->getActiveSheet()->getStyle('H1')->getAlignment()->setHorizontal('center');
		$objPHPExcel->getActiveSheet()->getStyle('I1')->getAlignment()->setHorizontal('center');
		$objPHPExcel->getActiveSheet()->getStyle('J1')->getAlignment()->setHorizontal('center');
		$objPHPExcel->getActiveSheet()->getStyle('K1')->getAlignment()->setHorizontal('center');
		$objPHPExcel->getActiveSheet()->getStyle('L1')->getAlignment()->setHorizontal('center');
		$objPHPExcel->getActiveSheet()->getStyle('M1')->getAlignment()->setHorizontal('center');
		$objPHPExcel->getActiveSheet()->getStyle('N1')->getAlignment()->setHorizontal('center');
		$objPHPExcel->getActiveSheet()->getStyle('O1')->getAlignment()->setHorizontal('center');
		$objPHPExcel->getActiveSheet()->getStyle('P1')->getAlignment()->setHorizontal('center');
		$objPHPExcel->getActiveSheet()->getStyle('Q1')->getAlignment()->setHorizontal('center');
		$objPHPExcel->getActiveSheet()->getStyle('R1')->getAlignment()->setHorizontal('center');
		$objPHPExcel->getActiveSheet()->getStyle('S1')->getAlignment()->setHorizontal('center');
		$objPHPExcel->getActiveSheet()->getStyle('T1')->getAlignment()->setHorizontal('center');
		$objPHPExcel->getActiveSheet()->getStyle('U1')->getAlignment()->setHorizontal('center');
		$objPHPExcel->getActiveSheet()->getStyle('V1')->getAlignment()->setHorizontal('center');
		$objPHPExcel->getActiveSheet()->getStyle('W1')->getAlignment()->setHorizontal('center');
		$objPHPExcel->getActiveSheet()->getStyle('X1')->getAlignment()->setHorizontal('center');
		

        $notShippedTotals=array(
                'dollars' => 0,
                'ccqty' => 0,
                'cclf' => 0,
                'tracklf' => 0,
                'bs' => 0,
                'drapeqty' => 0,
                'drapewidths' => 0,
                //'ttqty' => 0,
                //'ttlf' => 0,    
                'valqty' => 0,
                'vallf' => 0,
                'cornqty' => 0,
                'cornlf' => 0,
                'wthw' => 0,
                'blinds' => 0
        );
                
        $failedcount=0;
        
        $rowNumber++;
        
        foreach($failedBatches as $batch){
        //if(is_null($batch['batch_shipped_date']) || $batch['batch_shipped_date'] == 0 || $batch['batch_shipped_date'] > $endTS){
                $failedcount++;
                
                $notShippedTotals['dollars'] = ($notShippedTotals['dollars'] + floatval($batch['dollars']));
                $notShippedTotals['ccqty'] = ($notShippedTotals['ccqty'] + floatval($batch['cc']));
                $notShippedTotals['cclf'] = ($notShippedTotals['cclf'] + floatval($batch['cclf']));
                $notShippedTotals['tracklf'] = ($notShippedTotals['tracklf'] + floatval($batch['trklf']));
                $notShippedTotals['bs'] = ($notShippedTotals['bs'] + floatval($batch['bs']));
                $notShippedTotals['drapeqty'] = ($notShippedTotals['drapeqty'] + floatval($batch['drape']));
                $notShippedTotals['drapewidths'] = ($notShippedTotals['drapewidths'] + floatval($batch['drape_widths']));
                $notShippedTotals['valqty'] = ($notShippedTotals['valqty'] + floatval($batch['val']));
                $notShippedTotals['vallf'] = ($notShippedTotals['vallf'] + floatval($batch['vallf']));
                $notShippedTotals['cornqty'] = ($notShippedTotals['cornqty'] + floatval($batch['corn']));
                $notShippedTotals['cornlf'] = ($notShippedTotals['cornlf'] + floatval($batch['cornlf']));
                $notShippedTotals['wthw'] = ($notShippedTotals['wthw'] + floatval($batch['wthw']));
                $notShippedTotals['blinds'] = ($notShippedTotals['blinds'] + floatval($batch['blinds']));
		        
		        if($batch['cc'] > 0){
        			$ccea= $batch['cc'];
        		}else{
        			$ccea="";
        		}
        		
        		if($batch['cclf'] > 0){
        			$cclf=$batch['cclf'];
        		}else{
        			$cclf="";
        		}
        		
        		if($batch['trklf'] >0 ){
        			$trklf=$batch['trklf']." LF";
        		}else{
        			$trklf="";
        		}
        
                if($batch['bs'] > 0){
        			$bsea=$batch['bs'];
        		}else{
        			$bsea="";
        		}
        		
        		if($batch['drape'] > 0){
        			$drapeea=$batch['drape'];
        		}else{
        			$drapeea="";
        		}
        		
        		if($batch['drape_widths'] > 0){
        			$drapewidths=$batch['drape_widths'];
        		}else{
        			$drapewidths="";
        		}
        
                /*
                if($batch['wt'] > 0){
        			$ttea=$batch['wt'];
        		}else{
        			$ttea="";
        		}
        		
        		if($batch['wtlf'] > 0){
        			$ttlf=$batch['wtlf'];
        		}else{
        			$ttlf="";
        		}
        		*/
        		
        		if($batch['val'] > 0){
        		    $valea=$batch['val'];
        		}else{
        		    $valea="";
        		}
        		
        		if($batch['vallf'] > 0){
        		    $vallf=$batch['vallf'];
        		}else{
        		    $vallf="";
        		}
        		
        		if($batch['corn'] > 0){
        		    $cornea=$batch['corn'];
        		}else{
        		    $cornea="";
        		}
        		
        		if($batch['cornlf'] > 0){
        		    $cornlf=$batch['cornlf'];
        		}else{
        		    $cornlf="";
        		}
        		
        		if($batch['wthw'] >0){
        			$wthw=$batch['wthw'];
        		}else{
        			$wthw="";
        		}
        		
        		if($batch['blinds'] > 0){
        			$bands=$batch['blinds'];
        		}else{
        			$bands="";
        		}
        
                
                if($batch['batch_completed_date'] == 0){ $ifCompleted='---'; }else{ $ifCompleted=date('n/j/y',$batch['batch_completed_date']); }
        
                $objPHPExcel->getActiveSheet()->SetCellValue('A'.$rowNumber, $batch['date']);
                $objPHPExcel->getActiveSheet()->SetCellValue('B'.$rowNumber, $ifCompleted);
                $objPHPExcel->getActiveSheet()->SetCellValue('C'.$rowNumber, '---');
                $objPHPExcel->getActiveSheet()->SetCellValue('D'.$rowNumber, '---');
                $objPHPExcel->getActiveSheet()->SetCellValue('E'.$rowNumber, $batch['order_number']);
                
                $objPHPExcel->getActiveSheet()->SetCellValue('F'.$rowNumber, $batch['order_type']);
                
                $objPHPExcel->getActiveSheet()->SetCellValue('G'.$rowNumber, $batch['company_name']);
                $objPHPExcel->getActiveSheet()->SetCellValue('H'.$rowNumber, $batch['customer_po_number']);
                
                $objPHPExcel->getActiveSheet()->SetCellValue('I'.$rowNumber, $batch['facility']);
                
        		$objPHPExcel->getActiveSheet()->SetCellValue('J'.$rowNumber, $batch['batch_id']);
        		$objPHPExcel->getActiveSheet()->SetCellValue('K'.$rowNumber, $batch['dollars']);
        		$objPHPExcel->getActiveSheet()->SetCellValue('L'.$rowNumber, $ccea);
        		$objPHPExcel->getActiveSheet()->SetCellValue('M'.$rowNumber, $cclf);
        		$objPHPExcel->getActiveSheet()->SetCellValue('N'.$rowNumber, $trklf);
        		$objPHPExcel->getActiveSheet()->SetCellValue('O'.$rowNumber, $bsea);
        		$objPHPExcel->getActiveSheet()->SetCellValue('P'.$rowNumber, $drapeea);
        		$objPHPExcel->getActiveSheet()->SetCellValue('Q'.$rowNumber, $drapewidths);
        		$objPHPExcel->getActiveSheet()->SetCellValue('R'.$rowNumber, $valea);
        		$objPHPExcel->getActiveSheet()->SetCellValue('S'.$rowNumber, $vallf);
        		$objPHPExcel->getActiveSheet()->SetCellValue('T'.$rowNumber, $cornea);
        		$objPHPExcel->getActiveSheet()->SetCellValue('U'.$rowNumber, $cornlf);
        		$objPHPExcel->getActiveSheet()->SetCellValue('V'.$rowNumber, $wthw);
        		$objPHPExcel->getActiveSheet()->SetCellValue('W'.$rowNumber, $bands);
        		$objPHPExcel->getActiveSheet()->SetCellValue('X'.$rowNumber, '---');
		        
	        //}
	        $rowNumber++;
		}
		
		
		$objPHPExcel->getActiveSheet()->getStyle('K2:K'.$rowNumber)->getNumberFormat()->setFormatCode(\PHPExcel_Style_NumberFormat::FORMAT_CURRENCY_USD_SIMPLE);
		
		//$rowNumber++;
        
        $objPHPExcel->getActiveSheet()->SetCellValue('J'.$rowNumber, 'TOTALS');
        $objPHPExcel->getActiveSheet()->SetCellValue('K'.$rowNumber, $notProducedTotals['dollars']);
        $objPHPExcel->getActiveSheet()->SetCellValue('L'.$rowNumber, $notShippedTotals['ccqty']);
		$objPHPExcel->getActiveSheet()->SetCellValue('M'.$rowNumber, $notShippedTotals['cclf']);
		$objPHPExcel->getActiveSheet()->SetCellValue('N'.$rowNumber, $notShippedTotals['tracklf']);
		$objPHPExcel->getActiveSheet()->SetCellValue('O'.$rowNumber, $notShippedTotals['bs']);
		$objPHPExcel->getActiveSheet()->SetCellValue('P'.$rowNumber, $notShippedTotals['drapeqty']);
		$objPHPExcel->getActiveSheet()->SetCellValue('Q'.$rowNumber, $notShippedTotals['drapewidths']);
		$objPHPExcel->getActiveSheet()->SetCellValue('R'.$rowNumber, $notShippedTotals['valqty']);
		$objPHPExcel->getActiveSheet()->SetCellValue('S'.$rowNumber, $notShippedTotals['vallf']);
		$objPHPExcel->getActiveSheet()->SetCellValue('T'.$rowNumber, $notShippedTotals['cornqty']);
		$objPHPExcel->getActiveSheet()->SetCellValue('U'.$rowNumber, $notShippedTotals['cornlf']);
		$objPHPExcel->getActiveSheet()->SetCellValue('V'.$rowNumber, $notShippedTotals['wthw']);
		$objPHPExcel->getActiveSheet()->SetCellValue('W'.$rowNumber, $notShippedTotals['blinds']);
		
		//set DOLLARS column to CURRENCY format
		$objPHPExcel->getActiveSheet()->getStyle('K2:K'.$rowNumber)->getNumberFormat()->setFormatCode(\PHPExcel_Style_NumberFormat::FORMAT_CURRENCY_USD_SIMPLE);
		
		
		$objPHPExcel->getActiveSheet()->getStyle('J'.$rowNumber.':W'.$rowNumber)->applyFromArray(
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
		
		$objPHPExcel->setActiveSheetIndex(0);
		
		$objPHPExcel->removeSheetByIndex(2);
		
		
		// Redirect output to a client’s web browser (Excel5)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Shipping Report '.$start.'-'.$end.'.xlsx"');
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
	
	
	
	public function invoicedorders(){
	    if(isset($this->request->data['report_date_start']) && isset($this->request->data['report_date_end'])){
			$this->autoRender=false;
			
			$startTS=strtotime($this->request->data['report_date_start'].' 00:00:00');
			$endTS=strtotime($this->request->data['report_date_end'].' 23:59:59');
			
			
			$days=ceil(((($endTS+1)-$startTS)/86400));

			$dateList=array();
			for($i=1; $i<=$days; $i++){
			    $dateList[]=date('Y-m-d', ($startTS+(($i-1)*86400)+21600));
			}
			
			$conditions=[
			    'batch_invoiced_date >=' => $startTS,
				'batch_invoiced_date <' => $endTS
			];
				

			

			$batches=$this->SherryCache->find('all',['conditions'=>$conditions])->toArray();
			$this->set('batches',$batches);


			$this->set('inputs',$this->request->data);
			$this->render('viewinvoicedorders');
		}
		
	}
	

	
	public function shippedorders(){
		
		if(isset($this->request->data['report_date_start']) && isset($this->request->data['report_date_end'])){
			$this->autoRender=false;
			
			$startTS=strtotime($this->request->data['report_date_start'].' 00:00:00');
			$endTS=strtotime($this->request->data['report_date_end'].' 23:59:59');
			
			
			$days=ceil(((($endTS+1)-$startTS)/86400));

			$dateList=array();
			for($i=1; $i<=$days; $i++){
			    $dateList[]=date('Y-m-d', ($startTS+(($i-1)*86400)+21600));
			}
			
			$conditions=[
			    'batch_shipped_date >=' => $startTS,
				'batch_shipped_date <' => $endTS //,
				//'OR' => ['date IN' => $dateList]
			];
				

			

			$batches=$this->SherryCache->find('all',['conditions'=>$conditions])->toArray();
			$this->set('batches',$batches);

            
            //separate query needed for 'failed to produce' table
            $lookupFailedBatches=$this->SherryCache->find('all',['conditions' => ['date IN' => $dateList]])->toArray();
            $failedBatches=array();
            foreach($lookupFailedBatches as $batch){
                if($batch['batch_shipped_date'] == 0 || $batch['batch_shipped_date'] > strtotime($batch['date'].' 17:00:00') ){
                    $failedBatches[]=$batch;
                }
            }
            
            $this->set('failedBatches',$failedBatches);


			$shipments=array();
			foreach($batches as $batch){
				$shipStatusesThisBatch=$this->OrderItemStatus->find('all',['conditions'=>['sherry_batch_id' => $batch['batch_id'],'status'=>'Shipped','shipment_id !=' => NULL,'shipment_id !=' => 0]])->toArray();
				foreach($shipStatusesThisBatch as $shipStatus){
					$shipment=$this->Shipments->get($shipStatus['shipment_id'])->toArray();
					$shipments[$batch['batch_id']]=array('tracking'=>$shipment['tracking_number'],'delivery_address' => $shipment['tracking_number']);
				}
			}
			$this->set('shipments',$shipments);

			$this->set('inputs',$this->request->data);
			$this->render('viewshippedorders');
		}
		
		
	}
	
	
	
	public function backlogreport($type='backlog'){
	    $productMap=array(

			'cubicle-curtain'=>'Cubicle Curtain',

			'box-pleated'=>'Box Pleated Valance',

			'straight-cornice'=>'Straight Cornice',

			'bedspread'=>'Calculated Bedspread',

			'bedspread-manual'=>'Manually Entered Bedspread',

			'pinch-pleated' => 'Pinch Pleated Drapery',
			
			/* PPSASCRUM-56: start */
            'new-pinch-pleated' => 'Pinch Pleated Drapery',
            /* PPSASCRUM-56: end */

            /* PPSASCRUM-305: start */
            'ripplefold-drapery' => 'Ripplefold Drapery'
            /* PPSASCRUM-305: end */

		);
	    
	    
	    if($this->request->data){
	        $this->autoRender=false;
	    
    	    require($_SERVER['DOCUMENT_ROOT']."/vendor/phpoffice/phpexcel/Classes/PHPExcel.php");
            require($_SERVER['DOCUMENT_ROOT']."/vendor/phpoffice/phpexcel/Classes/PHPExcel/Writer/Excel2007.php");
            
    	    $objPHPExcel = new \PHPExcel();
            $objPHPExcel->setActiveSheetIndex(0);
			
			
			$objPHPExcel->getActiveSheet()->SetCellValue('A1', 'ORDER #');
			
			$objPHPExcel->getActiveSheet()->SetCellValue('B1', 'ORDER STATUS');
			
            $objPHPExcel->getActiveSheet()->SetCellValue('C1', 'QUOTE #');
            
            $objPHPExcel->getActiveSheet()->SetCellValue('D1', 'ORDER TYPE');
            
            $objPHPExcel->getActiveSheet()->SetCellValue('E1','STAGE');
            
            $objPHPExcel->getActiveSheet()->SetCellValue('F1','PM');
			$objPHPExcel->getActiveSheet()->SetCellValue('G1', 'CUSTOMER');
			$objPHPExcel->getActiveSheet()->SetCellValue('H1', 'CUSTOMER PO');
			$objPHPExcel->getActiveSheet()->SetCellValue('I1', 'RECIPIENT');
			$objPHPExcel->getActiveSheet()->SetCellValue('J1', 'SHIP DATE');
			
			$objPHPExcel->getActiveSheet()->SetCellValue('K1', 'SCHEDULED'); //moved from AB
			
			$objPHPExcel->getActiveSheet()->SetCellValue('L1', 'BOOK DATE');
			$objPHPExcel->getActiveSheet()->SetCellValue('M1', 'BATCH');
			$objPHPExcel->getActiveSheet()->SetCellValue('N1', 'LINE');
			
			$objPHPExcel->getActiveSheet()->SetCellValue('O1', 'LOCATION');
		
			$objPHPExcel->getActiveSheet()->SetCellValue('P1', 'QTY');
			
			
			$objPHPExcel->getActiveSheet()->SetCellValue('Q1', 'CLASS');
			$objPHPExcel->getActiveSheet()->SetCellValue('R1', 'SUBCLASS');
			
			
			$objPHPExcel->getActiveSheet()->SetCellValue('S1', 'UNIT');
			$objPHPExcel->getActiveSheet()->SetCellValue('T1', 'LINE ITEM');
			$objPHPExcel->getActiveSheet()->SetCellValue('U1', 'FABRIC');
			$objPHPExcel->getActiveSheet()->SetCellValue('V1', 'COLOR');
			
			$objPHPExcel->getActiveSheet()->SetCellValue('W1', 'CUT WIDTH');
			$objPHPExcel->getActiveSheet()->SetCellValue('X1', 'FIN WIDTH');
			
			$objPHPExcel->getActiveSheet()->SetCellValue('Y1', 'LENGTH');
			
			$objPHPExcel->getActiveSheet()->SetCellValue('Z1', 'CC LF');
			
			$objPHPExcel->getActiveSheet()->SetCellValue('AA1', 'DRP WIDTHS');
			$objPHPExcel->getActiveSheet()->SetCellValue('AB1', 'TOP TR LF');
			
			
			$objPHPExcel->getActiveSheet()->SetCellValue('AC1', 'YDS / UNIT');
			$objPHPExcel->getActiveSheet()->SetCellValue('AD1', 'TOTAL YARDS');
			$objPHPExcel->getActiveSheet()->SetCellValue('AE1', 'BASE');
			$objPHPExcel->getActiveSheet()->SetCellValue('AF1', 'TIERS');
			$objPHPExcel->getActiveSheet()->SetCellValue('AG1', 'ADJ PRICE');
			$objPHPExcel->getActiveSheet()->SetCellValue('AH1', 'EXT PRICE');

            
            
            $objPHPExcel->getActiveSheet()->SetCellValue('AI1', 'PRODUCED');
			$objPHPExcel->getActiveSheet()->SetCellValue('AJ1', 'SHIPPED');
			
			$objPHPExcel->getActiveSheet()->SetCellValue('AK1', 'TRACKING #');
			/**PPSASCRUM-113 start **/
			$objPHPExcel->getActiveSheet()->SetCellValue('AL1', 'INVOICED DATE');
			/**PPSASCRUM-113 start **/
			
			$objPHPExcel->getActiveSheet()->SetCellValue('AM1', 'LINE NOTES');
			
			
			
			
			$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(15);
			$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(15);
			$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(15);
			$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(15);
			$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(25);
			$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(15);
			$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(40);
			$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(25);
			$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(55);
			$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(15);
		
			$objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(15); //SCHEDULED moved from AB
			
			$objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(15);
			$objPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth(15);
			$objPHPExcel->getActiveSheet()->getColumnDimension('N')->setWidth(15);
			
			$objPHPExcel->getActiveSheet()->getColumnDimension('O')->setWidth(20);
			
			$objPHPExcel->getActiveSheet()->getColumnDimension('P')->setWidth(15);
			
			
			$objPHPExcel->getActiveSheet()->getColumnDimension('Q')->setWidth(22);
			$objPHPExcel->getActiveSheet()->getColumnDimension('R')->setWidth(22);
		
			
			$objPHPExcel->getActiveSheet()->getColumnDimension('S')->setWidth(15);
			$objPHPExcel->getActiveSheet()->getColumnDimension('T')->setWidth(75);
		
			$objPHPExcel->getActiveSheet()->getColumnDimension('U')->setWidth(20);
			$objPHPExcel->getActiveSheet()->getColumnDimension('V')->setWidth(20);
			$objPHPExcel->getActiveSheet()->getColumnDimension('W')->setWidth(20);
			$objPHPExcel->getActiveSheet()->getColumnDimension('X')->setWidth(20);
		
			$objPHPExcel->getActiveSheet()->getColumnDimension('Y')->setWidth(20);
			$objPHPExcel->getActiveSheet()->getColumnDimension('Z')->setWidth(20);
			
			
			$objPHPExcel->getActiveSheet()->getColumnDimension('AA')->setWidth(20);
			$objPHPExcel->getActiveSheet()->getColumnDimension('AB')->setWidth(20);
			
			
			$objPHPExcel->getActiveSheet()->getColumnDimension('AC')->setWidth(20);
			
			$objPHPExcel->getActiveSheet()->getColumnDimension('AD')->setWidth(20);
			$objPHPExcel->getActiveSheet()->getColumnDimension('AE')->setWidth(20);
			$objPHPExcel->getActiveSheet()->getColumnDimension('AF')->setWidth(30);
	
			$objPHPExcel->getActiveSheet()->getColumnDimension('AG')->setWidth(15);
			$objPHPExcel->getActiveSheet()->getColumnDimension('AH')->setWidth(15);
			
			
			$objPHPExcel->getActiveSheet()->getColumnDimension('AI')->setWidth(15);
			
			$objPHPExcel->getActiveSheet()->getColumnDimension('AJ')->setWidth(15);
			$objPHPExcel->getActiveSheet()->getColumnDimension('AK')->setWidth(29);
			$objPHPExcel->getActiveSheet()->getColumnDimension('AL')->setWidth(15);
			
			$objPHPExcel->getActiveSheet()->getColumnDimension('AM')->setWidth(70);
			

			
			$objPHPExcel->getActiveSheet()->getRowDimension('1')->setRowHeight(22);
			$objPHPExcel->getActiveSheet()->getStyle('A1:AM1')->getAlignment()->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);
			
			$objPHPExcel->getActiveSheet()->getStyle('A1:AM1')->applyFromArray(
				array(
					'fill' => array(
						'type' => \PHPExcel_Style_Fill::FILL_SOLID,
						'color' => array('rgb' => 'F8CBAD')
					)
				)
			);
			
			
            $rowCount = 2;
			
			$conditions=array();
			
			if($type=='backlog'){
			    $conditions += array('status NOT IN' => array('Shipped','Canceled'));
			}else{
			    $conditions += array('status !=' => 'Canceled');
			}
			
			if((isset($this->request->data['datestart']) && strlen(trim($this->request->data['datestart'])) > 0) && (isset($this->request->data['dateend']) && strlen(trim($this->request->data['dateend'])) > 0)){
				$conditions += array('created >=' => strtotime($this->request->data['datestart'].' 00:00:00'));
				$conditions += array('created <=' => strtotime($this->request->data['dateend'].' 23:59:59'));
			}
			
			if(isset($this->request->data['quotenumber']) && strlen(trim($this->request->data['quotenumber'])) > 0){
				
			}
			
			if(isset($this->request->data['ordernumber']) && strlen(trim($this->request->data['ordernumber'])) > 0){
				
			}
			
			if(isset($this->request->data['clientponumber']) && strlen(trim($this->request->data['clientponumber'])) > 0){
				
			}
			
			
			if(isset($this->request->data['customer']) && count($this->request->data['customer']) > 0){
				if(count($this->request->data['customer']) == 1){
					$conditions += array('customer_id' => $this->request->data['customer'][0]);
				}elseif(count($this->request->data['customer']) > 1){
					$conditions += array('customer_id IN' => $this->request->data['customer']);
				}
			}
			
			if(isset($this->request->data['hciagent']) && count($this->request->data['hciagent']) > 0){
				if(count($this->request->data['hciagent']) == 1){
					$conditions += array('user_id' => $this->request->data['hciagent'][0]);
				}elseif(count($this->request->data['hciagent']) > 1){
					$conditions += array('user_id IN' => $this->request->data['hciagent']);
				}
			}
			
			$orderLookup=$this->Orders->find('all',['conditions' => $conditions,'order'=>['order_number'=>'asc']])->toArray();
			
			foreach($orderLookup as $orderRow){
				
				$quoteItemsLoop=$this->QuoteLineItems->find('all',['conditions'=>['quote_id' => $orderRow['quote_id']],'order'=>['line_number'=>'asc']])->toArray();
				
				$quoteData=$this->Quotes->get($orderRow['quote_id'])->toArray();
				
				$customer=$this->Customers->get($orderRow['customer_id'])->toArray();
				$thisCustomer=$this->Customers->get($orderRow['customer_id'])->toArray();
				
				foreach($quoteItemsLoop as $quoteItem){
				    
				    $thisClass=$this->ProductClasses->get($quoteItem['product_class'])->toArray();
				    $classValue=$thisClass['class_name'];
				    
				    
				    $thisSubclass=$this->ProductSubclasses->get($quoteItem['product_subclass'])->toArray();
				    $subclassValue=$thisSubclass['subclass_name'];
				    
				    
					if($quoteItem['parent_line'] == 0){
					    
				    // $orderItemFind=$this->OrderItems->find('all',['conditions' => ['quote_line_item_id' => $quoteItem['id']]])->toArray();
				    $orderItemFind=$this->OrderLineItems->find('all',['conditions' => ['quote_line_item_id' => $quoteItem['id']]])->toArray();
				    /*print_r("<pre>");
				    print_r(debug($orderItemFind));
				    print_r("</pre>");
				    exit;*/
					foreach($orderItemFind as $orderItem){
					    
    						//look for BATCHES, if found, loop through those, then loop through the remainders or unbatched items
    				        // $batchesScheduled=$this->OrderItemStatus->find('all',['conditions' => ['order_item_id' => $orderItem['id'], 'status IN' => ['Scheduled','Completed','Invoiced','Shipped']]])->toArray();
    						$batchesScheduled=$this->WorkOrderItemStatus->find('all',['conditions' => ['work_order_id' => $orderItem['order_id'], 'order_line_number' => $orderItem['line_number'], 'status IN' => ['Scheduled','Completed','Invoiced','Shipped']]])->toArray();
    						/*print_r("<pre>");
    						print_r(debug($batchesScheduled));
    						print_r("</pre>");*/
    						$qtyShipped=0;
    						$qtyInvoiced=0;
    						$qtyCompleted=0;
    						$qtyScheduled=0;
    						
    						$loop=array(
    							'batches' => array(),
    							'unbatched' => 0
    						);
    						
    						$scheduledBatchesThisLine=0;
    						
    						foreach($batchesScheduled as $batchData){
    							switch($batchData['status']){
    								case 'Shipped':
    									$qtyShipped=($qtyShipped + intval($batchData['qty_involved']));
    								break;
    								case 'Invoiced':
    									$qtyInvoiced=($qtyInvoiced + intval($batchData['qty_involved']));
    								break;
    								case 'Scheduled':
    									$qtyScheduled=($qtyScheduled + intval($batchData['qty_involved']));
    									$loop['batches'][]=array('batchid'=>$batchData['sherry_batch_id'],'qty'=>$batchData['qty_involved'],'scheduledTS'=>$batchData['time']);
    									$scheduledBatchesThisLine++;
    								break;
    								case 'Completed':
    									$qtyCompleted=($qtyCompleted + intval($batchData['qty_involved']));
    								break;
    							}
    						}
    						
    						//loop back through again to add Shipped or Invoiced timestamps to batches that have them
    						foreach($batchesScheduled as $batchData){
    							switch($batchData['status']){
    								case 'Shipped':
    									foreach($loop['batches'] as $num => $batchloopitem){
    										if($batchloopitem['batchid'] == $batchData['sherry_batch_id']){
    											$loop['batches'][$num]['shippedTS'] = $batchData['time'];
    										}
    									}
    								break;
    								case 'Invoiced':
    									foreach($loop['batches'] as $num => $batchloopitem){
    										if($batchloopitem['batchid'] == $batchData['sherry_batch_id']){
    											$loop['batches'][$num]['invoicedTS'] = $batchData['time'];
    										}
    									}
    								break;
    							}
    						}
    						
    						$remainingUnscheduled=(intval($quoteItem['qty']) - $qtyScheduled);
    						$remainingUninvoiced=(intval($quoteItem['qty']) - $qtyInvoiced);
    						$remainingUnshipped = (intval($quoteItem['qty']) - $qtyShipped);
    						$remainingIncompleteScheduled= (intval($quoteItem['qty']) - $qtyCompleted);
    						
    						$loop['unbatched']=$remainingUnscheduled;
    					
    						
    						$itemMetas=$this->QuoteLineItemMeta->find('all',['conditions'=>['quote_item_id'=>$quoteItem['id']]])->toArray();
    						$metaArray=array();
    						foreach($itemMetas as $meta){
    							$metaArray[$meta['meta_key']]=$meta['meta_value'];
    						}
    						
    						if($quoteItem['product_type'] != 'custom' && $quoteItem['product_type'] != 'services' && $quoteItem['product_type'] != 'track_systems'){
    							
    							
    							if($metaArray['fabrictype'] != 'none' && $metaArray['fabrictype'] != 'typein'){
    								
    								if(isset($metaArray['fabricid']) && strlen(trim($metaArray['fabricid'])) >0 && $metaArray['fabricid'] != '0'){
    									$thisFabric=$this->Fabrics->get($metaArray['fabricid'])->toArray();
    								}else{
    									$thisFabric=array('fabric_name'=>'','color'=>'');
    								}
    								
    								//$thisFabric=$this->Fabrics->get($metaArray['fabricid'])->toArray();
    							}elseif($metaArray['fabrictype'] == 'typein'){
    							    $thisFabric=array('fabric_name'=>$metaArray['fabric_name'],'color'=>$metaArray['fabric_color']);
    							}else{
    								$thisFabric=array('fabric_name'=>'','color'=>'');
    							}
    						}else{
    							if($metaArray['fabrictype'] != 'none' && $metaArray['fabrictype'] != 'typein'){
    								if(isset($metaArray['fabricid']) && strlen(trim($metaArray['fabricid'])) >0 && $metaArray['fabricid'] != '0'){
    									$thisFabric=$this->Fabrics->get($metaArray['fabricid'])->toArray();
    								}else{
    									$thisFabric=array('fabric_name'=>'','color'=>'');
    								}
    							}elseif($metaArray['fabrictype'] == 'typein'){
    							    $thisFabric=array('fabric_name'=>$metaArray['fabric_name'],'color'=>$metaArray['fabric_color']);
    							}else{
    								$thisFabric=array('fabric_name'=>'','color'=>'');
    							}
    						}
    						
    						
    						
    						$thisbookingdate='';
    
    						//lookup the original Order Item Status for this line item and get the date value
    						/*$itemstatuslookup=$this->OrderItemStatus->find('all',['conditions' => ['order_item_id' => $orderItem['id'],'status' => 'Not Started']])->toArray();
    						foreach($itemstatuslookup as $itemstatusrow){
    							//$thisbookingdate=date('n/j/Y',$itemstatusrow['time']);
    						*/
    						$bookDateTimeObj=new \DateTime();
    						
    						$bookDateTimeObj->setTimestamp($orderRow['created']);
    							
    						$thisbookingdate=\PHPExcel_Shared_Date::PHPToExcel($bookDateTimeObj);
    						//}
    
    
    						$customerValue=$thisCustomer['company_name'];
    						$customerPOValue=$orderRow['po_number'];
    						$facilityValue=$orderRow['facility'];
    						
    						
    						$quoteNumberValue='';
    						if($quoteData['revision'] > 0){
    							$quoteNumberValue=$quoteData['quote_number']."\n[REV ".$quoteData['revision']."]";
    						}else{
    							$quoteNumberValue=$quoteData['quote_number'];
    						}
    						
    						
    						
    						if($orderRow['due'] < 1000){
    							$shipDateValue='';
    						}else{
    							//$shipDateValue=date('n/d/Y',$orderRow['due']);
    							$dueDateTimeObj=new \DateTime();
            					$dueDateTimeObj->setTimestamp($orderRow['due']);
            					$shipDateValue = \PHPExcel_Shared_Date::PHPToExcel($dueDateTimeObj);
    						}
    
    						$lineNumberValue=$quoteItem['line_number'];
    						$locationValue=$quoteItem['room_number'];
    						$unitValue=$quoteItem['unit'];
    						$itemDescription='';
    						
    						///////////////////////////////////////////////////////////////////////////////////////////////////////////////
    						
    						if($metaArray['lineitemtype']=='calculator'){
    							if($metaArray['calculator-used']=="straight-cornice"){
    								$itemDescription .= $metaArray['cornice-type']." Cornice";
    								if($metaArray['railroaded'] == '1'){
    									$itemDescription .= "<br>Fabric Railroaded";
    								}else{
    									$itemDescription .= "<br>Fabric Vertically Seamed";
    								}
    								$thisLining=$this->Linings->get($metaArray['linings_id'])->toArray();
    								$itemDescription .= "<br><b>Lining:</b> ".$thisLining['short_title'];
    								$welts='';
    								if($metaArray['welt-top']=='1' && $metaArray['welt-bottom']=='1'){
    									$welts = "Top + Bottom";
    								}else{
    									if($metaArray['welt-top']=='1'){
    										$welts = "Top Only";
    									}elseif($metaArray['welt-bottom'] == '1'){
    										$welts = "Bottom Only";
    									}
    								}
    								if($welts != ''){
    									$itemDescription .= "<br><b>Welts:</b> ".$welts;
    								}else{
    									$itemDescription .= "<br><b>Welts:</b> None";
    								}
    								if($metaArray['individual-nailheads'] == '1'){
    									$itemDescription .= "<br>Individual Nailheads";
    								}
    								if($metaArray['nailhead-trim'] == '1'){
    									$itemDescription .= "<br>Nailhead Trim";
    								}
    								if($metaArray['covered-buttons'] == '1'){
    									$itemDescription .= "<br>".$metaArray['covered-buttons-count']." Covered Buttons";
    								}
    
    								if($metaArray['horizontal-straight-banding'] == '1'){
    									$itemDescription .= "<br>".$metaArray['horizontal-straight-banding-count']." H Straight Banding";
    								}	
    								if($metaArray['horizontal-shaped-banding'] == '1'){
    									$itemDescription .= "<br>".$metaArray['horizontal-shaped-banding-count']." H Shaped Banding";
    								}
    								if($metaArray['extra-welts'] == '1'){
    									$itemDescription .= "<br>".$metaArray['extra-welts-count']." Extra Welts";
    								}	
    								if($metaArray['trim-sewn-on'] == '1'){
    									$itemDescription .= "<br>".$metaArray['trim-lf']." LF Sewn-On Trim";
    								}
    								if($metaArray['tassels'] == '1'){
    									$itemDescription .= "<br>".$metaArray['tassels-count']." Tassels";
    								}
    								if($metaArray['drill-holes'] == '1'){
    									$itemDescription .= "<br>".$metaArray['drill-hole-count']." Drill Holes";
    								}
    								if(isset($metaArray['vertical-repeat']) && floatval($metaArray['vertical-repeat']) >0){
    									$itemDescription .= "<br>Matched Repeat (".$metaArray['vertical-repeat']."&quot;)";
    								}
    							}elseif($metaArray['calculator-used']=="bedspread" || $metaArray['calculator-used'] == "bedspread-manual"){
    								$itemDescription .= $productMap[$metaArray['calculator-used']];
    								if($metaArray['railroaded'] == '1'){
    									$itemDescription .= "<br>Fabric Railroaded";
    								}else{
    									$itemDescription .= "<br>Fabric Up-the-Roll";
    								}
    
    								$itemDescription .= "<br>";
    								if(isset($metaArray['style']) && strlen(trim($metaArray['style'])) >0){
    									$itemDescription .= "Style: ".$metaArray['style'];
    								}
    								if(isset($metaArray['quilted']) && $metaArray['quilted']=='1'){
    									$itemDescription .= "<br>Quilted";
    									if(isset($metaArray['quilting-pattern']) && strlen(trim($metaArray['quilting-pattern'])) >0){
    										$itemDescription .= ", ".$metaArray['quilting-pattern'];
    									}
    									if(isset($metaArray['matching-thread']) && $metaArray['matching-thread'] == '1'){
    										$itemDescription .= ", Matching Thread";
    									}
    									$itemDescription .= ", ".$thisFabric['bs_backing_material']." Backing";
    								}else{
    									$itemDescription .= "<br>Unquilted";
    								}
    
    								$itemDescription .= "<br>Mattress: ";
    								if(!isset($metaArray['custom-top-width-mattress-w'])){
    									$itemDescription .= "36&quot;";
    								}else{
    									$itemDescription .= $metaArray['custom-top-width-mattress-w']."&quot;";
    								}
    
    								if(isset($metaArray['vertical-repeat']) && floatval($metaArray['vertical-repeat']) >0){
    									$itemDescription .= "<br>Matched Repeat (".$metaArray['vertical-repeat']."&quot;)";
    								}
    							}elseif($metaArray['calculator-used']=="box-pleated"){
    								$itemDescription .= $metaArray['valance-type']." Valance";
    
    								if($metaArray['railroaded'] == '1'){
    									$itemDescription .= "<br>Fabric Railroaded";
    								}else{
    									$itemDescription .= "<br>Fabric Vertically Seamed";
    								}
    								$itemDescription .= "<br>".$metaArray['pleats']." Pleats";
    
    								$thisLining=$this->Linings->get($metaArray['linings_id'])->toArray();
    								$itemDescription .= "<br><b>Lining:</b> ".$thisLining['short_title'];
    
    								if($metaArray['straight-banding']==1){
    									$itemDescription .= "<br>Straight Banding";
    								}
    								if($metaArray['shaped-banding']==1){
    									$itemDescription .= "<Br>Shaped Banding";
    								}
    								if($metaArray['trim-sewn-on']==1){
    									$itemDescription .= "<br>Sewn-On Trim";
    								}
    								if($metaArray['welt-covered-in-fabric'] == 1){
    									$itemDescription .= "<br>Welt Covered In Fabric";
    								}
    								if($metaArray['contrast-fabric-inside-pleat'] == 1){
    									$itemDescription .= "<br>Contrast Fabric Inside Pleat";
    								}
    								if(isset($metaArray['vert-repeat']) && floatval($metaArray['vert-repeat']) >0){
    									$itemDescription .= "<br>Matched Repeat (".$metaArray['vert-repeat']."&quot;)";
    								}
    							}elseif($metaArray['calculator-used']=="cubicle-curtain"){
    								$itemDescription .= $productMap[$metaArray['calculator-used']];
    								if($metaArray['railroaded'] == '1'){
    									$itemDescription .= "<br>Fabric Railroaded";
    								}else{
    									$itemDescription .= "<br>Fabric Vertically Seamed";
    								}
    								if($metaArray['mesh'] > 0 && $metaArray['mesh'] != ''){
    									$itemDescription .= "<br><b>Mesh:</b> ".$metaArray['mesh']."&quot; ".$metaArray['mesh-color']." (".str_replace(" Mesh","",$metaArray['mesh-type']).")";
    								}
    								if($metaArray['mesh-type'] == 'None'){
    									$itemDescription .= "<br>NO MESH";
    								}
    								if($metaArray['mesh-type'] == 'Integral Mesh'){
    									$itemDescription .= "<br>INTEGRAL MESH";
    								}
    								if($metaArray['liner'] == 1){
    									$itemDescription .= "<br>Liner";
    								}
    								if($metaArray['nylon-mesh']==1){
    									$itemDescription .= "<br>Nylon Mesh";
    								}
    								if($metaArray['angled-mesh']==1){
    									$itemDescription .= "<br>Angled Mesh";
    								}
    								if($metaArray['mesh-frame'] != 'No Frame'){
    									$itemDescription .= "<br><b>Mesh Frame:</b> ".$metaArray['mesh-frame'];
    								}
    								if($metaArray['hidden-mesh'] == 1){
    									$itemDescription .= "<br>Hidden Mesh";
    								}
    
    								if($metaArray['snap-tape'] != "None"){
    									$itemDescription .= "<br>".$metaArray['snap-tape']." Snap Tape (".$metaArray['snaptape-lf'].")";
    								}
    								if($metaArray['velcro'] != 'None'){
    									$itemDescription .= "<br>".$metaArray['velcro']." Velcro (".$metaArray['velcro-lf']." LF)";
    								}
    								if($metaArray['weights']==1){
    									$itemDescription .= "<br>".$metaArray['weight-count']." Weights";
    								}
    								if($metaArray['magnets']==1){
    									$itemDescription .= "<br>".$metaArray['magnet-count']." Magnets";
    								}
    								if($metaArray['banding'] == 1){
    									$itemDescription .= "<br>Banding";
    								}
    								if($metaArray['buttonholes'] == 1){
    									$itemDescription .= "<br>".$metaArray['buttonhole-count']." Buttonholes";
    								}
    								if(isset($metaArray['vertical-repeat']) && floatval($metaArray['vertical-repeat']) >0){
    									$itemDescription .= "<br>Matched Repeat (".$metaArray['vertical-repeat']."&quot;)";
    								}
    							} /* PPSASCRUM-305: start */ elseif($metaArray['calculator-used']=='pinch-pleated' || $metaArray['calculator-used']=='new-pinch-pleated' || 
    							        $metaArray['calculator-used']=='ripplefold-drapery'){
                                  /* PPSASCRUM-305: end */
    								$itemDescription .= $productMap[$metaArray['calculator-used']];
    								if($metaArray['unit-of-measure'] == 'pair'){
    									$itemDescription .= "<br>Pair";
    								}elseif($metaArray['unit-of-measure'] == 'panel'){
    									$itemDescription .= "<br>".$metaArray['panel-type']." Panel";
    								}
    								if($metaArray['railroaded'] == '1'){
    									$itemDescription .= "<br>Fabric Railroaded";
    								}else{
    									$itemDescription .= "<br>Fabric Vertically Seamed";
    								}
    								if(isset($metaArray['linings_id']) && floatval($metaArray['linings_id']) >0){
    									$thisLining=$this->Linings->get($metaArray['linings_id'])->toArray();
    									$itemDescription .= "<br><b>Lining:</b> ".$thisLining['short_title'];
    								}
    								$itemDescription .= "<br><b>Hardware:</b> ".ucfirst($metaArray['hardware']);
    								if($metaArray['trim-sewn-on'] == '1'){
    									$itemDescription .= "<br>".$metaArray['trim-lf']." LF Sewn-On Trim";
    								}
    								if($metaArray['tassels'] == '1'){
    									$itemDescription .= "<br>".$metaArray['tassels-count']." Tassels";
    								}
    							}
    						}elseif($metaArray['lineitemtype']=='simpleproduct'){
    							switch($quoteItem['product_type']){
    								case "cubicle_curtains":
    									$itemDescription .= "Price List CC";
    									if($thisFabric['railroaded'] == '1'){
    										$itemDescription .= "<br>Fabric Railroaded";
    									}else{
    										$itemDescription .= "<br>Fabric Vertically Seamed";
    									}
    									if($metaArray['mesh'] > 0 && $metaArray['mesh'] != ''){
    										$itemDescription .= "<br><b>Mesh:</b> ".$metaArray['mesh']."&quot; ".$metaArray['mesh-color']." (MOM)";
    									}
    									if($metaArray['mesh'] == 'No Mesh' || $metaArray['mesh'] == '0'){
    										$itemDescription .= "<br>NO MESH";
    									}
    									if(floatval($thisFabric['vertical_repeat']) > 0){
    										$itemDescription .= "<br>Matched Repeat (".$thisFabric['vertical_repeat']."&quot;)";
    									}
    								break;
    								case "bedspreads":
    									$itemDescription .= "Price List BS";
    									$thisBS="";
    									try {
    									        $thisBS=$this->Bedspreads->get($quoteItem['product_id'])->toArray();
                                            } catch (RecordNotFoundException $e) { }
    									if($thisFabric['railroaded'] == '1'){
    										$itemDescription .= "<br>Fabric Railroaded";
    									}else{
    										$itemDescription .= "<br>Fabric Vertically Seamed";
    									}
    									$itemDescription .= "<br>Style: ";
    									$styleval=explode(" (",$metaArray['style']);
    									$itemDescription .= $styleval[0];
    									if(!empty($thisBS) && $thisBS['quilted']=='1'){
    										$itemDescription .= "<br>Quilted, Double Onion, ".$thisFabric['bs_backing_material']." Backing";
    									}else{
    										$itemDescription .= "<br>Unquilted";
    									}
    									$itemDescription .= "<br>Mattress: ";
    									if(!isset($metaArray['custom-top-width-mattress-w'])){
    										$itemDescription .= "36&quot;";
    									}else{
    										$itemDescription .= $metaArray['custom-top-width-mattress-w']."&quot;";
    									}
    
    									if(floatval($thisFabric['vertical_repeat']) > 0){
    										$itemDescription .= "<br>Matched Repeat (".$thisFabric['vertical_repeat']."&quot;)";
    									}
    								break;
    								case "services":
    									$itemDescription .= $quoteItem['title'];
    								break;
    								case "window_treatments":
    									if($metaArray['wttype']=='Pinch Pleated Drapery'){
    										$itemDescription .= "<b>".ucfirst($metaArray['unit-of-measure'])."</b><br>";
    									}
    									$itemDescription .= 'Price List '.$metaArray['wttype'];
    									if(preg_match("#Cornice#i",$metaArray['wttype']) || preg_match("#Valance#i",$metaArray['wttype'])){
    										$itemDescription .= "<br>Fabric Vertically Seamed";
    									}
    									if(preg_match("#Valance#i",$metaArray['wttype'])){
    										$itemDescription .= "<br>".$metaArray['pleats']." Pleats";
    									}
    									if(isset($metaArray['linings_id']) && floatval($metaArray['linings_id']) >0){
    										$thisLining=$this->Linings->get($metaArray['linings_id'])->toArray();
    										$itemDescription .= "<br><b>Lining:</b> ".$thisLining['short_title'];
    									}
    									if(preg_match("#Cornice#i",$metaArray['wttype'])){
    										$welts='';
    										if($metaArray['welt-top']=='1' && $metaArray['welt-bottom']=='1'){
    											$welts = "Top + Bottom";
    										}else{
    											if($metaArray['welt-top']=='1'){
    												$welts = "Top Only";
    											}elseif($metaArray['welt-bottom'] == '1'){
    												$welts = "Bottom Only";
    											}
    										}
    										if($welts != ''){
    											$itemDescription .= "<br><b>Welts:</b> ".$welts;
    										}else{
    											$itemDescription .= "<br><b>Welts:</b> None";
    										}
    									}
    
    								break;
    								case "track_systems":
    									$itemDescription .= "<b>".$quoteItem['title']."</b>";
    									if(isset($metaArray['_component_numlines']) && intval($metaArray['_component_numlines']) >0){
    										$itemDescription .= "<br><button style=\"padding:4px; border:1px solid #000; background:#CCC; color:#000; font-size:11px;\" onclick=\"loadTrackBreakdown('".$quoteItem['id']."');\" type=\"button\">List Components</button>";
    									}
    								break;
    							}
    						}elseif($metaArray['lineitemtype']=='custom' || $metaArray['lineitemtype'] == 'newcatchall'){
    							$itemDescription .= "<b>".$quoteItem['title']."</b>";
    							$itemDescription .= "<br>".$quoteItem['description'];
    							$itemDescription .= "<br>".nl2br($metaArray['specs']);
    						}
    						
    						///////////////////////////////////////////////////////////////////////////////////////////////////////////////
    						
    						
    						$ttwizard = new \PHPExcel_Helper_HTML;
    						$lineItemValue = $ttwizard->toRichTextObject($itemDescription);
    						
    						
    						$fabricValue='';
    						$fabricFR='';
    						if(isset($thisFabric['flammability']) && strlen(trim($thisFabric['flammability'])) >0){
    							$fabricFR='<br><b>FR: '.$thisFabric['flammability'].'</b>';
    						}
    
    						if($quoteItem['product_type'] == 'track_systems'){
    							$fabricValue .= '---';
    						}elseif($quoteItem['product_type'] == 'custom'){
    							if(isset($metaArray['com-fabric']) && $metaArray['com-fabric'] == "1"){
    								$fabricValue .= "COM ";
    							}
    							if($fabricAlias=$this->getFabricAlias($metaArray['fabricid'],$thisQuote['customer_id'])){
    								if(isset($metaArray['usealias']) && $metaArray['usealias'] == 'yes'){
    									$fabricValue .= $fabricAlias['fabric_name']."<br>".$metaArray['fabric_name'].$fabricFR;
    								}else{
    									$fabricValue .= $metaArray['fabric_name']."<br><em>(".$fabricAlias['fabric_name'].")</em>".$fabricFR;
    								}
    							}else{
    								$fabricValue .= $metaArray['fabric_name'].$fabricFR;
    							}
    						}else{
    							if(isset($metaArray['com-fabric']) && $metaArray['com-fabric'] == "1"){
    								$fabricValue .= "COM ";
    							}
    							if($fabricAlias=$this->getFabricAlias($thisFabric['id'],$thisQuote['customer_id'])){
    								if(isset($metaArray['usealias']) && $metaArray['usealias'] == 'yes'){
    									$fabricValue .= "<b>".$fabricAlias['fabric_name']."</b><br>".$thisFabric['fabric_name'];
    								}else{
    									$fabricValue .= $thisFabric['fabric_name']."<br><em>(".$fabricAlias['fabric_name'].")</em>";
    								}
    							}else{
    								$fabricValue .= $thisFabric['fabric_name'];
    							}
    							$fabricValue .= $fabricFR;
    						}
    						
    						$fabricwizard = new \PHPExcel_Helper_HTML;
    						$fabricrichText = $fabricwizard->toRichTextObject($fabricValue);
    						
    						
    						$colorValue='';
    						if($quoteItem['product_type'] == 'track_systems'){
    							$colorValue .= '---';
    						}elseif($quoteItem['product_type'] == 'custom'){
    							if($fabricAlias=$this->getFabricAlias($metaArray['fabricid'],$thisQuote['customer_id'])){
    								if(isset($metaArray['usealias']) && $metaArray['usealias'] == 'yes'){
    									$colorValue .= "<b>".$fabricAlias['color']."</b><br>".$metaArray['fabric_color'];	
    								}else{
    									$colorValue .= $metaArray['fabric_color']."<br><em>(".$fabricAlias['color'].")</em>";
    								}
    							}else{
    								$colorValue .= $metaArray['fabric_color'];
    							}
    						}else{
    							if($fabricAlias=$this->getFabricAlias($thisFabric['id'],$thisQuote['customer_id'])){
    								if(isset($metaArray['usealias']) && $metaArray['usealias'] == 'yes'){
    									$colorValue .= "<b>".$fabricAlias['color']."</b><Br>".$thisFabric['color'];
    								}else{
    									$colorValue .= $thisFabric['color']."<br><em>(".$fabricAlias['color'].")</em>";
    								}
    							}else{
    								$colorValue .= $thisFabric['color'];
    							}
    						}
    						
    						$colorwizard = new \PHPExcel_Helper_HTML;
    						$colorrichText = $colorwizard->toRichTextObject($colorValue);
    						
    						$cutwidthValue='';
    						$finwidthValue='';
    						
    						if($quoteItem['product_type'] == 'track_systems'){
    							$cutwidthValue .= '---';
    						}elseif($quoteItem['product_type']=="bedspreads"){
    							$cutwidthValue .= number_format(floatval($metaArray['width']),0,'','');
    						}elseif($quoteItem['product_type']=="cubicle_curtains"){
    							$cutwidthValue .= number_format(floatval($metaArray['width']),0,'','');
    							if(isset($metaArray['expected-finish-width']) && strlen(trim($metaArray['expected-finish-width'])) >0){
    								$finwidthValue .= $metaArray['expected-finish-width'];
    							}
    						}elseif($quoteItem['product_type'] == 'window_treatments'){
    							if($metaArray['wttype'] == 'Pinch Pleated Drapery'){
    								$cutwidthValue .= number_format(floatval($metaArray['rod-width']),0,'','')." (Rod)";
    								$finwidthValue .= $metaArray['default-return']." (Return)";
    							}elseif(preg_match("#Cornice#i",$metaArray['wttype']) || preg_match("#Valance#i",$metaArray['wttype'])){
    								$cutwidthValue .= number_format(floatval($metaArray['width']),0,'','')." (Face)";
    								if(isset($metaArray['return']) && floatval(trim($metaArray['return'])) >0){
    									$finwidthValue .= $metaArray['return']." (Return)";
    								}else{
    									$finwidthValue .= "No Return";
    								}
    							}
    						}else{
    							if($metaArray['calculator-used']=="box-pleated" || $quoteItem['product_type'] == 'newcatchall-valance'){
    								if(isset($metaArray['face']) && strlen(trim($metaArray['face'])) >0){
    									$cutwidthValue .= $metaArray['face']." (Face)";
    								}
    								if(isset($metaArray['return']) && floatval(trim($metaArray['return'])) >0){
    									$finwidthValue .= $metaArray['return']." (Return)";
    								}else{
    									$finwidthValue .= "No Return";
    								}
    							}elseif($metaArray['calculator-used']=="bedspread" || $metaArray['calculator-used']=="bedspread-manual" || $quoteItem['product_type'] == 'newcatchall-bedding'){
    								if(isset($metaArray['width']) && strlen(trim($metaArray['width'])) >0){
    									$finwidthValue .= $metaArray['width'];
    								}
    							}elseif($metaArray['calculator-used']=="cubicle-curtain" || $quoteItem['product_type'] == 'newcatchall-cubicle'){
    								if(isset($metaArray['width']) && strlen(trim($metaArray['width'])) >0){
    									$cutwidthValue .= $metaArray['width'];
    								}
    								if(isset($metaArray['expected-finish-width']) && strlen(trim($metaArray['expected-finish-width'])) >0){
    									$finwidthValue .= $metaArray['expected-finish-width'];
    								}else{
    									if(isset($metaArray['fw']) && strlen(trim($metaArray['fw'])) >0){
    										$finwidthValue .= $metaArray['fw'];
    									}
    								}
    							}elseif($metaArray['calculator-used']=="straight-cornice" || $quoteItem['product_type'] == 'newcatchall-cornice'){
    								if(isset($metaArray['face']) && strlen(trim($metaArray['face'])) >0){
    									$cutwidthValue .= $metaArray['face']." (Face)";
    								}
    								if(isset($metaArray['return']) && floatval(trim($metaArray['return'])) >0){
    									$finwidthValue .= $metaArray['return']." (Return)";
    								}else{
    									$finwidthValue .= "No Return";
    								}
    							}elseif($metaArray['calculator-used'] == 'pinch-pleated' || $quoteItem['product_type'] == 'newcatchall-drapery'){
    								if($metaArray['unit-of-measure'] == 'pair'){
    									if(isset($metaArray['rod-width']) && strlen(trim($metaArray['rod-width'])) >0){
    										$cutwidthValue .= $metaArray['rod-width']." (Rod)";
    									}
    								}else{
    									if(isset($metaArray['fabric-widths-per-panel'])){
    										$cutwidthValue .= $metaArray['fabric-widths-per-panel']." Widths";
    									}
    								}
    								if(isset($metaArray['fullness']) && strlen(trim($metaArray['fullness'])) >0){
    									$finwidthValue .= $metaArray['fullness']."X Fullness";
    								}
    								if(isset($metaArray['default-return']) && strlen(trim($metaArray['default-return'])) >0){
    									$finwidthValue .= "<br>".$metaArray['default-return']." Ret";
    								}
    								if(isset($metaArray['default-overlap']) && strlen(trim($metaArray['default-overlap'])) >0){
    									$finwidthValue .= "<br>".$metaArray['default-overlap']." Ovrlp";
    								}
    							}
    						}
    						
    						$cutwidthwizard = new \PHPExcel_Helper_HTML;
    						$cutwidthValue = $cutwidthwizard->toRichTextObject($cutwidthValue);
    						
    						
    						if ($remainingUnscheduled > 0) {
    						    if($quoteItem['product_type'] == 'newcatchall-drapery'){
        						    $drapewidthsValue=intval($metaArray['labor-billable-widths']) * $remainingUnscheduled;
        						}elseif($quoteItem['calculator_used'] == 'pinch-pleated' || $quoteItem['calculator_used'] == 'pinch-pleated-new' ||
        						        $quoteItem['calculator_used'] == 'new-pinch-pleated' || $quoteItem['calculator_used'] == 'ripplefold-drapery'){
        						    $drapewidthsValue=intval($metaArray['labor-widths']) * $remainingUnscheduled;
        						}else{
        						    $drapewidthsValue='';
        						}
    						} else {
        						if($quoteItem['product_type'] == 'newcatchall-drapery'){
        						    $drapewidthsValue=intval($metaArray['labor-billable-widths']) * (intval($quoteItem['qty']));
        						}elseif($quoteItem['calculator_used'] == 'pinch-pleated' || $quoteItem['calculator_used'] == 'pinch-pleated-new' ||
        						        $quoteItem['calculator_used'] == 'new-pinch-pleated' || $quoteItem['calculator_used'] == 'ripplefold-drapery'){
        						    $drapewidthsValue = intval($metaArray['labor-widths']) * (intval($quoteItem['qty']));
        						}else{
        						    $drapewidthsValue='';
        						}
    						}
    						
    						
    						$finwidthwizard = new \PHPExcel_Helper_HTML;
    						$finwidthValue = $finwidthwizard->toRichTextObject($finwidthValue);
    						
    						$lengthValue='';
    						if($quoteItem['product_type'] == 'track_systems'){
    							$lengthValue='---';
    						}else{
    							if($metaArray['calculator-used']=="box-pleated"){
    								$lengthValue .= $metaArray['height']." (Height)";
    							}elseif($metaArray['calculator-used']=="straight-cornice"){
    								$lengthValue .= $metaArray['height']." (Height)";
    							}else{
    								$lengthValue .= $metaArray['length'];
    								if($quoteItem['product_type'] == 'window_treatments' && (preg_match("#Cornice#i",$metaArray['wttype']) || preg_match("#Valance#i",$metaArray['wttype']))){
    									$lengthValue .= " (Height)";
    								}
    
    							}
    							if(isset($metaArray['fl-short']) && floatval($metaArray['fl-short']) >0){
    								$lengthValue .= "<br>".$metaArray['fl-short']."(Short Point)";
    							}
    						}
    						$lengthwizard = new \PHPExcel_Helper_HTML;
    						$lengthrichText = $lengthwizard->toRichTextObject($lengthValue);
    						
    					
    						
    						
    						
    						
    						if(isset($metaArray['yds-per-unit'])){
    							$ydsperunitValue=$metaArray['yds-per-unit'];
    						}else{
    							$ydsperunitValue='---';
    						}
    						
    						
    					
    						
    						$bestprice=$quoteItem['best_price'];
    						$installadjustmentpercentage=0;
    						$tieradjustmentpercentage=0;
    						$tierDiscountOrPremium='Disc';
    						$rebateadjustmentpercentage=0;
    						
    						if($metaArray['specialpricing']=='1'){
    							$tieradjusted=number_format(floatval($quoteItem['best_price']),2,'.',',');
    							$installadjusted=number_format(floatval($quoteItem['best_price']),2,'.',',');
    							$rebateadjusted=number_format(floatval($quoteItem['best_price']),2,'.',',');
    							$pmiadjusted=number_format(floatval($quoteItem['pmi_adjusted']),2,'.',',');
    							$pmiadjustmentdollars=number_format((floatval(str_replace(',','',$pmiadjusted)) - floatval(str_replace(',','',$rebateadjusted))),2,'.',',');
    							
    						}else{
    							$tieradjusted=number_format(floatval($quoteItem['tier_adjusted_price']),2,'.',',');
    							$installadjusted=number_format(floatval($quoteItem['install_adjusted_price']),2,'.',',');
    							$rebateadjusted=number_format(floatval($quoteItem['rebate_adjusted_price']),2,'.',',');
    							$pmiadjusted=number_format(floatval($quoteItem['pmi_adjusted']),2,'.',',');
    							
    							$tieradjustmentpercentage=round(abs((((1/floatval(str_replace(',','',$quoteItem['best_price']))) * floatval(str_replace(',','',$tieradjusted)))*100)),2);
    							$installadjustmentpercentage=round(abs(((1-((1/floatval(str_replace(',','',$tieradjusted))) * floatval(str_replace(',','',$installadjusted))))*100)),2);
    							$rebateadjustmentpercentage=round(abs(((1-((1/floatval(str_replace(',','',$installadjusted))) * floatval(str_replace(',','',$rebateadjusted))))*100)),2);
    							$pmiadjustmentdollars=number_format((floatval(str_replace(',','',$pmiadjusted)) - floatval(str_replace(',','',$rebateadjusted))),2,'.',',');
    						}
    
    						$breakdownValue='';
    						$basePriceValue=number_format($bestprice,2,'.',',');
    						
    						if($metaArray['specialpricing']=='1'){
    							$breakdownValue .= "<font color=\"#FF0000\"><b><em>SPECIAL PRICING</em></b></font>";
    						}else{
    							$breakdownValue .= "<font color=\"#0000FF\">".$tieradjusted." Tier (";
    							if(floatval(str_replace(',','',$quoteItem['best_price'])) > floatval(str_replace(',','',$tieradjusted))){
    								$breakdownValue .= '-'.$tieradjustmentpercentage."% Disc";
    							}elseif(floatval(str_replace(',','',$quoteItem['best_price'])) < floatval(str_replace(',','',$tieradjusted))){
    								if($tieradjustmentpercentage > 100){
    									$breakdownValue .= '+'.($tieradjustmentpercentage-100)."% Prem";
    								}else{
    									$breakdownValue .= '+'.$tieradjustmentpercentage."% Prem";
    								}
    							}else{
    								$breakdownValue .= "0%";
    							}
    							
    							$breakdownValue .= ")<br>".$installadjusted." INST (+".$installadjustmentpercentage."%)<br>".$rebateadjusted." ADD ";
    							$breakdownValue .= "(+".$rebateadjustmentpercentage."%)";
    							$breakdownValue .= "<br>".$pmiadjusted." PMI (+\$".$pmiadjustmentdollars.")</font>";
    						}
    						
    						
    						$breakdownwizard = new \PHPExcel_Helper_HTML;
    						$breakdownrichText = $breakdownwizard->toRichTextObject($breakdownValue);
    						
    						
    						
    						if($quoteItem['override_active'] == 1){
    							$adjustedColValue = number_format(floatval($quoteItem['override_price']),2,'.','');
    						}else{
    							$adjustedColValue = number_format(floatval($pmiadjusted),2,'.','');
    						}
    						
    						
    						
    						
    						
    						
    						
    						$extendedPriceValue=number_format($extendedprice,2,'.',',');
    						
    						$dateScheduled='';
    						$dateShipped='';
    						$dateInvoiced='';
    						$dateProduced='';
    						$trackingNumber='';
    						
    						
    						$lineNotesValue='';
    						$lineNotes=$this->QuoteLineItemNotes->find('all',['conditions'=>['quote_item_id' => $quoteItem['id']],'order'=>['time'=>'asc']])->toArray();
    						foreach($lineNotes as $lineNoteRow){
    							$thisNoteUser=$this->Users->get($lineNoteRow['user_id'])->toArray();
    							if($lineNoteRow['visible_to_customer'] == 0){
    								$lineNotesValue .= '[INTERNAL] ';
    							}
    							$lineNotesValue .= $lineNoteRow['message'].' <font color="#0000FF"><em>'.$thisNoteUser['first_name'].' '.substr($thisNoteUser['last_name'],0,1).' @ '.date('n/j/y g:iA',$lineNoteRow['time']).'</em></font><br>';
    						}
    						
    						$linenoteswizard = new \PHPExcel_Helper_HTML;
    						$linenotesrichText = $linenoteswizard->toRichTextObject($lineNotesValue);
    						
    						
    						
    						//start inner loop of $loop
    						//begin with UNBATCHED
    						if($loop['unbatched'] > 0){
    							$qtyValue=$loop['unbatched'];
    							$batchValue='N/A';
    							
    							$totalLFvalue='';
    							
    							$totalydsvalue='';
    							if($metaArray['lineitemtype'] == 'simpleproduct'){
    								$totalydsvalue .= round((floatval($metaArray['yds-per-unit']) * floatval($qtyValue)),2);
    							}elseif($metaArray['lineitemtype']=="custom"){
    								if(floatval($metaArray['total-yds']) >0){
    									$totalydsvalue .= round(((floatval($metaArray['total-yds']) / floatval($quoteItem['qty'])) * intval($qtyValue)),2);
    								}else{
    									$totalydsvalue .= round((floatval($metaArray['yds-per-unit']) * floatval($qtyValue)),2);
    								}
    							}else{
    								$totalydsvalue .= round(((floatval($metaArray['total-yds']) / floatval($quoteItem['qty'])) * intval($qtyValue)),2);
    							}
    							
    							
    							if($metaArray['specialpricing']=='1'){
    								if($quoteItem['override_active'] == 1){
    									$extendedprice=(floatval($quoteItem['override_price']) * floatval($qtyValue));
    								}else{
    									$extendedprice=(floatval($quoteItem['best_price'])*floatval($qtyValue));
    								}
    							}else{
    							    if($quoteItem['override_active'] == 1){
    									$extendedprice=(floatval($quoteItem['override_price']) * floatval($qtyValue));
    								}else{
    								    $extendedprice=number_format((floatval($quoteItem['pmi_adjusted']) * $qtyValue),2,'.','');
    								}
    							}
    							
    							
    							
    							if($quoteItem['product_type'] == 'track_systems'){
        							if($quoteItem['unit'] == 'linear feet'){
        								$totalLFvalue .= $qtyValue;
        							}else{
        								$totalLFvalue .= '---';
        							}
        						}else{
        							if(isset($metaArray['labor-billable'])){
        								$totalLFvalue .= (floatval($metaArray['labor-billable'])*floatval($qtyValue));
        							}else{
        								$totalLFvalue .= "---";
        							}
        						}
    						
    						if($orderRow['project_manager_id'] == 0 || is_null($orderRow['project_manager_id'])){
    						    $managerName='N/A';
    						}else{
    						    $pmLookup=$this->Users->get($orderRow['project_manager_id'])->toArray();
    						    $managerName=$pmLookup['first_name'].' '.$pmLookup['last_name'];
    						}
    						
    						if($quoteItem['product_type'] == 'newcatchall-valance' || $quoteItem['product_type'] == 'newcatchall-cornice' || $quoteItem['calculator_used'] == 'straight-cornice' || $quoteItem['calculator_used'] == 'box-pleated'){
    						    $ttlfValue=$totalLFvalue;
    						    $cclfValue='';
    						}elseif($quoteItem['product_type'] == 'track_systems'){
    						    $ttlfValue='';
    						    $cclfValue='';
    						}else{
    						    $ttlfValue='';
    						    $cclfValue = $totalLFvalue;
    						}
    						    
    						
    						
    						if(!is_null($orderRow['type_id']) && $orderRow['type_id'] > 0){
    						    $thisType=$this->QuoteTypes->get($orderRow['type_id'])->toArray();
    						    $thisOrderType=$thisType['type_label'];
    						}else{
    						    $thisOrderType='';
    						}
    							
    							//recalc and override the TOTAL LF, TOTAL YARDS, EXTENDED PRICE variables for [this qty]
    							
    							
    							$objPHPExcel->getActiveSheet()->SetCellValue('A'.$rowCount, $orderRow['order_number']);
    							
    							$objPHPExcel->getActiveSheet()->SetCellValue('B'.$rowCount, $orderRow['status']);
    							
    							$objPHPExcel->getActiveSheet()->SetCellValue('C'.$rowCount, $quoteNumberValue);
    							
    							$objPHPExcel->getActiveSheet()->SetCellValue('D'.$rowCount, $thisOrderType);
    							
    							$objPHPExcel->getActiveSheet()->SetCellValue('E'.$rowCount, $orderRow['stage']);
    							
    							$objPHPExcel->getActiveSheet()->SetCellValue('F'.$rowCount, $managerName);
    							
    							$objPHPExcel->getActiveSheet()->SetCellValue('G'.$rowCount, $customerValue);
    							$objPHPExcel->getActiveSheet()->SetCellValue('H'.$rowCount, $customerPOValue);
    							$objPHPExcel->getActiveSheet()->SetCellValue('I'.$rowCount, $facilityValue);
    							$objPHPExcel->getActiveSheet()->SetCellValue('J'.$rowCount, $shipDateValue);
    							$objPHPExcel->getActiveSheet()->getStyle('J'.$rowCount)->getNumberFormat()->setFormatCode('mm/dd/yyyy');
    							
    							$objPHPExcel->getActiveSheet()->SetCellValue('K'.$rowCount, $dateScheduled);
    							$objPHPExcel->getActiveSheet()->getStyle('K'.$rowCount)->getNumberFormat()->setFormatCode('mm/dd/yyyy');
    							
    							$objPHPExcel->getActiveSheet()->SetCellValue('L'.$rowCount, $thisbookingdate);
    							$objPHPExcel->getActiveSheet()->getStyle('L'.$rowCount)->getNumberFormat()->setFormatCode('mm/dd/yyyy');
    							
    							$objPHPExcel->getActiveSheet()->SetCellValue('M'.$rowCount, $batchValue);
    							$objPHPExcel->getActiveSheet()->SetCellValue('N'.$rowCount, $lineNumberValue);
    							$objPHPExcel->getActiveSheet()->SetCellValue('O'.$rowCount, $locationValue);
    							$objPHPExcel->getActiveSheet()->SetCellValue('P'.$rowCount, $qtyValue);
    							
    							$objPHPExcel->getActiveSheet()->SetCellValue('Q'.$rowCount, $classValue);
    							$objPHPExcel->getActiveSheet()->SetCellValue('R'.$rowCount, $subclassValue);
    							
    							$objPHPExcel->getActiveSheet()->SetCellValue('S'.$rowCount, $unitValue);
    							$objPHPExcel->getActiveSheet()->SetCellValue('T'.$rowCount, $lineItemValue);
    							$objPHPExcel->getActiveSheet()->SetCellValue('U'.$rowCount, $fabricrichText);
    							$objPHPExcel->getActiveSheet()->SetCellValue('V'.$rowCount, $colorrichText);
    							$objPHPExcel->getActiveSheet()->SetCellValue('W'.$rowCount, $cutwidthValue);
    							$objPHPExcel->getActiveSheet()->SetCellValue('X'.$rowCount, $finwidthValue);
    							$objPHPExcel->getActiveSheet()->SetCellValue('Y'.$rowCount, $lengthrichText);
    							$objPHPExcel->getActiveSheet()->SetCellValue('Z'.$rowCount, $cclfValue);
    							
    							$objPHPExcel->getActiveSheet()->SetCellValue('AA'.$rowCount, $drapewidthsValue);
    							$objPHPExcel->getActiveSheet()->SetCellValue('AB'.$rowCount, $ttlfValue);
    							
    							
    							$objPHPExcel->getActiveSheet()->SetCellValue('AC'.$rowCount, $ydsperunitValue);
    							$objPHPExcel->getActiveSheet()->SetCellValue('AD'.$rowCount, $totalydsvalue);
    							$objPHPExcel->getActiveSheet()->SetCellValue('AE'.$rowCount, $basePriceValue);
    							$objPHPExcel->getActiveSheet()->SetCellValue('AF'.$rowCount, $breakdownrichText);
    							$objPHPExcel->getActiveSheet()->SetCellValue('AG'.$rowCount, $adjustedColValue);
    							$objPHPExcel->getActiveSheet()->SetCellValue('AH'.$rowCount, $extendedprice);
    							
    							
    							$objPHPExcel->getActiveSheet()->SetCellValue('AI'.$rowCount, $dateProduced);
    							$objPHPExcel->getActiveSheet()->getStyle('AI'.$rowCount)->getNumberFormat()->setFormatCode('mm/dd/yyyy');
    							
    							$objPHPExcel->getActiveSheet()->SetCellValue('AJ'.$rowCount, $dateShipped);
    							$objPHPExcel->getActiveSheet()->getStyle('AJ'.$rowCount)->getNumberFormat()->setFormatCode('mm/dd/yyyy');
    							
    							$objPHPExcel->getActiveSheet()->SetCellValue('AK'.$rowCount, $trackingNumber);
    							$objPHPExcel->getActiveSheet()->SetCellValue('AL'.$rowCount, $dateInvoiced);
    							$objPHPExcel->getActiveSheet()->getStyle('AL'.$rowCount)->getNumberFormat()->setFormatCode('mm/dd/yyyy');
    							
    							$objPHPExcel->getActiveSheet()->SetCellValue('AM'.$rowCount, $linenotesrichText);
    							
    						
    							if($rowCount % 2 == 0){
    								$thisrowcolor='F8F8F8';
    							}else{
    								$thisrowcolor='FFFFFF';
    							}
    						
    							$objPHPExcel->getActiveSheet()->getStyle('A'.$rowCount.':AM'.$rowCount)->applyFromArray(
    								array(
    									'fill' => array(
    										'type' => \PHPExcel_Style_Fill::FILL_SOLID,
    										'color' => array('rgb' => $thisrowcolor)
    									)
    								)
    							);
    
    							$brlines=explode("<br",$itemDescription);
    							if(count($brlines) < 5){
    								$rowheight=90;
    							}elseif(count($brlines) >= 5 && count($brlines) < 8){
    								$rowheight=120;
    							}else{
    								$rowheight=145;
    							}
    
    							$objPHPExcel->getActiveSheet()->getRowDimension($rowCount)->setRowHeight($rowheight);
    
    							$rowCount++;
    						}
    						
    						
    						//then loop through BATCHED
    						foreach($loop['batches'] as $num => $batchloopitem){
    						    $totalLFvalue='';
    						    
    							if($type=='backlog' && ((isset($batchloopitem['invoicedTS']) && $batchloopitem['invoicedTS'] > 1000) && (isset($batchloopitem['shippedTS']) && $batchloopitem['shippedTS'] > 1000) )){
    								//ignore this row, it's already shipped and invoiced
    							}else{
    								$qtyValue=$batchloopitem['qty'];
    								$batchValue=$batchloopitem['batchid'];
    								
    								if($metaArray['specialpricing']=='1'){
    									if($quoteItem['override_active'] == 1){
    										$extendedprice=(floatval($quoteItem['override_price']) * floatval($qtyValue));
    									}else{
    										$extendedprice=(floatval($quoteItem['best_price'])*floatval($qtyValue));
    									}
    								}else{
    								    if($quoteItem['override_active'] == 1){
    										$extendedprice=(floatval($quoteItem['override_price']) * floatval($qtyValue));
    									}else{
    									    $extendedprice=number_format((floatval($quoteItem['pmi_adjusted']) * $qtyValue),2,'.','');
    									}
    								}
    								
    								$totalydsvalue='';
    								if($metaArray['lineitemtype'] == 'simpleproduct'){
    									$totalydsvalue .= round((floatval($metaArray['yds-per-unit']) * floatval($qtyValue)),2);
    								}elseif($metaArray['lineitemtype']=="custom"){
    									if(floatval($metaArray['total-yds']) >0){
    										$totalydsvalue .= round(((floatval($metaArray['total-yds']) / floatval($quoteItem['qty'])) * intval($qtyValue)),2);
    									}else{
    										$totalydsvalue .= round((floatval($metaArray['yds-per-unit']) * floatval($qtyValue)),2);
    									}
    								}else{
    									$totalydsvalue .= round(((floatval($metaArray['total-yds']) / floatval($quoteItem['qty'])) * intval($qtyValue)),2);
    								}
    
    
                                    if($quoteItem['product_type'] == 'track_systems'){
            							if($quoteItem['unit'] == 'linear feet'){
            								$totalLFvalue .= $qtyValue;
            							}else{
            								$totalLFvalue .= '---';
            							}
            						}else{
            							if(isset($metaArray['labor-billable'])){
            								$totalLFvalue .= (floatval($metaArray['labor-billable'])*floatval($qtyValue));
            							}else{
            								$totalLFvalue .= "---";
            							}
            						}
            						
            						$totallfrichText = $totalLFvalue;
    								
    								$dateScheduled='';
    								$dateShipped='';
    								$trackingNumber='';
    								
    								
    								
    								$shipStatusesThisBatch=$this->OrderItemStatus->find('all',['conditions'=>['sherry_batch_id' => $batchValue,'status'=>'Shipped','shipment_id !=' => NULL,'shipment_id !=' => 0]])->toArray();
                    				foreach($shipStatusesThisBatch as $shipStatus){
                    					$shipment=$this->Shipments->get($shipStatus['shipment_id'])->toArray();
                    					$trackingNumber=$shipment['tracking_number'];
                    				}
    								
    								//look up whether this line has been Shipped, if so, populate the variable
            						$thisLineBatchesShipped=$this->OrderItemStatus->find('all',['conditions' => ['order_item_id' => $orderItem['id'], 'status' => 'Shipped', 'sherry_batch_id' => $batchValue]])->toArray();
            						foreach($thisLineBatchesShipped as $shippedBatch){
            							//$dateShipped = date('n/j/Y',$shippedBatch['time']);
            							$shipDateTimeObj=new \DateTime();
            						    $shipDateTimeObj->setTimestamp($shippedBatch['time']);
            							$dateShipped = \PHPExcel_Shared_Date::PHPToExcel($shipDateTimeObj);
            						}
            						
            						
            						$dateScheduled='';
            						$thisLineBatchesScheduled=$this->OrderItemStatus->find('all',['conditions' => ['order_item_id' => $orderItem['id'], 'status' => 'Scheduled', 'sherry_batch_id' => $batchValue]])->toArray();
            						foreach($thisLineBatchesScheduled as $scheduledBatch){
            							//$dateScheduled = date('n/j/Y',$scheduledBatch['time']);
            							$schedDateTimeObj=new \DateTime();
            						    $schedDateTimeObj->setTimestamp($scheduledBatch['time']);
            						    
            							$dateScheduled = \PHPExcel_Shared_Date::PHPToExcel($schedDateTimeObj);
            						}
            						
            						
            						$dateProduced='';
            						$thisLineBatchesCompleted=$this->OrderItemStatus->find('all',['conditions' => ['order_item_id' => $orderItem['id'], 'status' => 'Completed', 'sherry_batch_id' => $batchValue]])->toArray();
            						foreach($thisLineBatchesCompleted as $producedBatch){
            							//$dateProduced = date('n/j/Y',$producedBatch['time']);
            							
            							$prodDateTimeObj=new \DateTime();
            						    $prodDateTimeObj->setTimestamp($producedBatch['time']);
            						    
            							$dateProduced = \PHPExcel_Shared_Date::PHPToExcel($prodDateTimeObj);
            							
            						}
            						
            						$dateInvoiced='';
            						
            						//look up whether this line has been Invoiced, if so, populate the variable
            						$thisLineBatchesInvoiced=$this->OrderItemStatus->find('all',['conditions' => ['order_item_id' => $orderItem['id'], 'status' => 'Invoiced','sherry_batch_id'=>$batchValue]])->toArray();
            						foreach($thisLineBatchesInvoiced as $invoicedBatch){
            							//$dateInvoiced = date('n/j/Y',$invoicedBatch['time']);
            							$invDateTimeObj=new \DateTime();
            						    $invDateTimeObj->setTimestamp($invoicedBatch['time']);
            						    
            							$dateInvoiced = \PHPExcel_Shared_Date::PHPToExcel($invDateTimeObj);
            						}
    						
    						
    						
    								//recalc and override the TOTAL LF, TOTAL YARDS, EXTENDED PRICE variables for [this qty]
    								if($orderRow['project_manager_id'] == 0 || is_null($orderRow['project_manager_id'])){
            						    $managerName='N/A';
            						}else{
            						    $pmLookup=$this->Users->get($orderRow['project_manager_id'])->toArray();
            						    $managerName=$pmLookup['first_name'].' '.$pmLookup['last_name'];
            						}
            						
            						if ($qtyScheduled > 0) {
            						    if($quoteItem['product_type'] == 'newcatchall-drapery'){
                						    $drapewidthsValue=intval($metaArray['labor-billable-widths']) * $qtyScheduled;
                						}elseif($quoteItem['calculator_used'] == 'pinch-pleated' || $quoteItem['calculator_used'] == 'pinch-pleated-new' ||
                						        $quoteItem['calculator_used'] == 'new-pinch-pleated' || $quoteItem['calculator_used'] == 'ripplefold-drapery'){
                						    $drapewidthsValue=intval($metaArray['labor-widths']) * $qtyScheduled;
                						}else{
                						    $drapewidthsValue='';
                						}
            						} else {
                						if($quoteItem['product_type'] == 'newcatchall-drapery'){
                						    $drapewidthsValue=intval($metaArray['labor-billable-widths']) * (intval($quoteItem['qty']));
                						}elseif($quoteItem['calculator_used'] == 'pinch-pleated' || $quoteItem['calculator_used'] == 'pinch-pleated-new' ||
                						        $quoteItem['calculator_used'] == 'new-pinch-pleated' || $quoteItem['calculator_used'] == 'ripplefold-drapery'){
                						    $drapewidthsValue = intval($metaArray['labor-widths']) * (intval($quoteItem['qty']));
                						}else{
                						    $drapewidthsValue='';
                						}
            						}
    								
    								$objPHPExcel->getActiveSheet()->SetCellValue('A'.$rowCount, $orderRow['order_number']);
    								
    								$objPHPExcel->getActiveSheet()->SetCellValue('B'.$rowCount, $orderRow['status']);
    								
    								$objPHPExcel->getActiveSheet()->SetCellValue('C'.$rowCount, $quoteNumberValue);
    								
    								
    								if(!is_null($orderRow['type_id']) && $orderRow['type_id'] > 0){
            						    $thisType=$this->QuoteTypes->get($orderRow['type_id'])->toArray();
            						    $thisOrderType=$thisType['type_label'];
            						}else{
            						    $thisOrderType='';
            						}
    								
    								$objPHPExcel->getActiveSheet()->SetCellValue('D'.$rowCount, $thisOrderType);
    								
    								$objPHPExcel->getActiveSheet()->SetCellValue('E'.$rowCount, $orderRow['stage']);
    								
    								$objPHPExcel->getActiveSheet()->SetCellValue('F'.$rowCount, $managerName);
    								
    								$objPHPExcel->getActiveSheet()->SetCellValue('G'.$rowCount, $customerValue);
    								$objPHPExcel->getActiveSheet()->SetCellValue('H'.$rowCount, $customerPOValue);
    								$objPHPExcel->getActiveSheet()->SetCellValue('I'.$rowCount, $facilityValue);
    								$objPHPExcel->getActiveSheet()->SetCellValue('J'.$rowCount, $shipDateValue);
    								$objPHPExcel->getActiveSheet()->getStyle('J'.$rowCount)->getNumberFormat()->setFormatCode('mm/dd/yyyy');
    								
    								$objPHPExcel->getActiveSheet()->SetCellValue('K'.$rowCount, $dateScheduled);
    								$objPHPExcel->getActiveSheet()->getStyle('K'.$rowCount)->getNumberFormat()->setFormatCode('mm/dd/yyyy');
    								
    								$objPHPExcel->getActiveSheet()->SetCellValue('L'.$rowCount, $thisbookingdate);
    								$objPHPExcel->getActiveSheet()->getStyle('L'.$rowCount)->getNumberFormat()->setFormatCode('mm/dd/yyyy');
    								
    								$objPHPExcel->getActiveSheet()->SetCellValue('M'.$rowCount, $batchValue);
    								$objPHPExcel->getActiveSheet()->SetCellValue('N'.$rowCount, $lineNumberValue);
    								$objPHPExcel->getActiveSheet()->SetCellValue('O'.$rowCount, $locationValue);
    								$objPHPExcel->getActiveSheet()->SetCellValue('P'.$rowCount, $qtyValue);
    								
    								$objPHPExcel->getActiveSheet()->SetCellValue('Q'.$rowCount, $classValue);
    								$objPHPExcel->getActiveSheet()->SetCellValue('R'.$rowCount, $subclassValue);
    								
    								
    								$objPHPExcel->getActiveSheet()->SetCellValue('S'.$rowCount, $unitValue);
    								$objPHPExcel->getActiveSheet()->SetCellValue('T'.$rowCount, $lineItemValue);
    								$objPHPExcel->getActiveSheet()->SetCellValue('U'.$rowCount, $fabricrichText);
    								$objPHPExcel->getActiveSheet()->SetCellValue('V'.$rowCount, $colorrichText);
    								$objPHPExcel->getActiveSheet()->SetCellValue('W'.$rowCount, $cutwidthValue);
    								$objPHPExcel->getActiveSheet()->SetCellValue('X'.$rowCount, $finwidthValue);
    								$objPHPExcel->getActiveSheet()->SetCellValue('Y'.$rowCount, $lengthrichText);
    								$objPHPExcel->getActiveSheet()->SetCellValue('Z'.$rowCount, $totallfrichText);
    								
    								$objPHPExcel->getActiveSheet()->SetCellValue('AA'.$rowCount, $drapewidthsValue);
    								$objPHPExcel->getActiveSheet()->SetCellValue('AB'.$rowCount, $ttlfValue);
    								
    								$objPHPExcel->getActiveSheet()->SetCellValue('AC'.$rowCount, $ydsperunitValue);
    								$objPHPExcel->getActiveSheet()->SetCellValue('AD'.$rowCount, $totalydsvalue);
    								$objPHPExcel->getActiveSheet()->SetCellValue('AE'.$rowCount, $basePriceValue);
    								$objPHPExcel->getActiveSheet()->SetCellValue('AF'.$rowCount, $breakdownrichText);
    								$objPHPExcel->getActiveSheet()->SetCellValue('AG'.$rowCount, $adjustedColValue);
    								$objPHPExcel->getActiveSheet()->SetCellValue('AH'.$rowCount, $extendedprice);
    							
    								
    								
    								$objPHPExcel->getActiveSheet()->SetCellValue('AI'.$rowCount, $dateProduced);
    								$objPHPExcel->getActiveSheet()->getStyle('AI'.$rowCount)->getNumberFormat()->setFormatCode('mm/dd/yyyy');
    								
    								$objPHPExcel->getActiveSheet()->SetCellValue('AJ'.$rowCount, $dateShipped);
    								$objPHPExcel->getActiveSheet()->getStyle('AJ'.$rowCount)->getNumberFormat()->setFormatCode('mm/dd/yyyy');
    								
    								$objPHPExcel->getActiveSheet()->SetCellValue('AK'.$rowCount, $trackingNumber);
    								$objPHPExcel->getActiveSheet()->SetCellValue('AL'.$rowCount, $dateInvoiced);
    								$objPHPExcel->getActiveSheet()->getStyle('AL'.$rowCount)->getNumberFormat()->setFormatCode('mm/dd/yyyy');
    								
    								$objPHPExcel->getActiveSheet()->SetCellValue('AM'.$rowCount, $linenotesrichText);
    								
    								
    
    
    								if($rowCount % 2 == 0){
    									$thisrowcolor='F8F8F8';
    								}else{
    									$thisrowcolor='FFFFFF';
    								}
    
    								$objPHPExcel->getActiveSheet()->getStyle('A'.$rowCount.':AM'.$rowCount)->applyFromArray(
    									array(
    										'fill' => array(
    											'type' => \PHPExcel_Style_Fill::FILL_SOLID,
    											'color' => array('rgb' => $thisrowcolor)
    										)
    									)
    								);
    
    								$brlines=explode("<br",$itemDescription);
    								if(count($brlines) < 5){
    									$rowheight=90;
    								}elseif(count($brlines) >= 5 && count($brlines) < 8){
    									$rowheight=120;
    								}else{
    									$rowheight=145;
    								}
    
    								$objPHPExcel->getActiveSheet()->getRowDimension($rowCount)->setRowHeight($rowheight);
    
    								$rowCount++;
    							}
    						
    						}
						
					    }
					}
					
				}
				
			}
			
			$objPHPExcel->getActiveSheet()->getStyle('A1:A'.$rowCount)->getAlignment()->setHorizontal('center');
			$objPHPExcel->getActiveSheet()->getStyle('B1:B'.$rowCount)->getAlignment()->setHorizontal('center');
			$objPHPExcel->getActiveSheet()->getStyle('C1:C'.$rowCount)->getAlignment()->setHorizontal('center');
            $objPHPExcel->getActiveSheet()->getStyle('D1:D'.$rowCount)->getAlignment()->setHorizontal('center');
            $objPHPExcel->getActiveSheet()->getStyle('E1:E'.$rowCount)->getAlignment()->setHorizontal('center');
            $objPHPExcel->getActiveSheet()->getStyle('F1:F'.$rowCount)->getAlignment()->setHorizontal('center');
			$objPHPExcel->getActiveSheet()->getStyle('G1:G'.$rowCount)->getAlignment()->setHorizontal('center');
			$objPHPExcel->getActiveSheet()->getStyle('H1:H'.$rowCount)->getAlignment()->setHorizontal('center');
			$objPHPExcel->getActiveSheet()->getStyle('I1:I'.$rowCount)->getAlignment()->setHorizontal('center');
			$objPHPExcel->getActiveSheet()->getStyle('J1:J'.$rowCount)->getAlignment()->setHorizontal('center');
			$objPHPExcel->getActiveSheet()->getStyle('K1:K'.$rowCount)->getAlignment()->setHorizontal('center');
			$objPHPExcel->getActiveSheet()->getStyle('L1:L'.$rowCount)->getAlignment()->setHorizontal('center');
			$objPHPExcel->getActiveSheet()->getStyle('M1:M'.$rowCount)->getAlignment()->setHorizontal('center');
			$objPHPExcel->getActiveSheet()->getStyle('N1:N'.$rowCount)->getAlignment()->setHorizontal('center');
			$objPHPExcel->getActiveSheet()->getStyle('O1:O'.$rowCount)->getAlignment()->setHorizontal('center');
			$objPHPExcel->getActiveSheet()->getStyle('P1:P'.$rowCount)->getAlignment()->setHorizontal('center');
			$objPHPExcel->getActiveSheet()->getStyle('Q1:Q'.$rowCount)->getAlignment()->setHorizontal('center');
			$objPHPExcel->getActiveSheet()->getStyle('R1:R'.$rowCount)->getAlignment()->setHorizontal('center');
			$objPHPExcel->getActiveSheet()->getStyle('S1:S'.$rowCount)->getAlignment()->setHorizontal('center');
			$objPHPExcel->getActiveSheet()->getStyle('T1:T'.$rowCount)->getAlignment()->setHorizontal('center');
			$objPHPExcel->getActiveSheet()->getStyle('U1:U'.$rowCount)->getAlignment()->setHorizontal('center');
			$objPHPExcel->getActiveSheet()->getStyle('V1:V'.$rowCount)->getAlignment()->setHorizontal('center');
			$objPHPExcel->getActiveSheet()->getStyle('W1:W'.$rowCount)->getAlignment()->setHorizontal('center');
			$objPHPExcel->getActiveSheet()->getStyle('X1:X'.$rowCount)->getAlignment()->setHorizontal('center');
			$objPHPExcel->getActiveSheet()->getStyle('Y1:Y'.$rowCount)->getAlignment()->setHorizontal('center');
			$objPHPExcel->getActiveSheet()->getStyle('Z1:Z'.$rowCount)->getAlignment()->setHorizontal('center');
			$objPHPExcel->getActiveSheet()->getStyle('AA1:AA'.$rowCount)->getAlignment()->setHorizontal('center');
			$objPHPExcel->getActiveSheet()->getStyle('AB1:AB'.$rowCount)->getAlignment()->setHorizontal('center');
			$objPHPExcel->getActiveSheet()->getStyle('AC1:AC'.$rowCount)->getAlignment()->setHorizontal('center');
			$objPHPExcel->getActiveSheet()->getStyle('AD1:AD'.$rowCount)->getAlignment()->setHorizontal('center');
			$objPHPExcel->getActiveSheet()->getStyle('AE1:AE'.$rowCount)->getAlignment()->setHorizontal('center');
			$objPHPExcel->getActiveSheet()->getStyle('AF1:AF'.$rowCount)->getAlignment()->setHorizontal('center');
			$objPHPExcel->getActiveSheet()->getStyle('AG1:AG'.$rowCount)->getAlignment()->setHorizontal('center');
			$objPHPExcel->getActiveSheet()->getStyle('AH1:AH'.$rowCount)->getAlignment()->setHorizontal('center');
			$objPHPExcel->getActiveSheet()->getStyle('AI1:AI'.$rowCount)->getAlignment()->setHorizontal('center');
			$objPHPExcel->getActiveSheet()->getStyle('AJ1:AJ'.$rowCount)->getAlignment()->setHorizontal('center');
			$objPHPExcel->getActiveSheet()->getStyle('AK1:AK'.$rowCount)->getAlignment()->setHorizontal('center');
			$objPHPExcel->getActiveSheet()->getStyle('AL1:AL'.$rowCount)->getAlignment()->setHorizontal('center');
			$objPHPExcel->getActiveSheet()->getStyle('AM1:AM'.$rowCount)->getAlignment()->setHorizontal('left');
			
			
			$objPHPExcel->getActiveSheet()->getStyle('A1:AM'.$rowCount)->getAlignment()->setVertical(\PHPExcel_Style_Alignment::VERTICAL_TOP );
			
			$objPHPExcel->getActiveSheet()->getStyle('A1:AM'.$rowCount)->getAlignment()->setWrapText(true);
			
			//$objPHPExcel->getActiveSheet()->getStyle('AF2:AF'.$rowCount)->
			
			
			
			$objPHPExcel->getActiveSheet()->getStyle("A1:AM".$rowCount)->applyFromArray(
				array(
					'borders' => array(
						'allborders' => array(
							'style' => \PHPExcel_Style_Border::BORDER_THIN,
							'color' => array('rgb' => '111111')
						)
					)
				)
			);
			
			$objPHPExcel->getActiveSheet()->freezePane('A2');
			
          
            
            // Redirect output to a client’s web browser (Excel5)
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="'.ucfirst($type).'Report.xlsx"');
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
	    }else{
	        //display the form
	        $allusers=$this->Users->find('all',['order'=>['last_name'=>'asc','first_name'=>'asc']])->toArray();
		    $this->set('allusers',$allusers);
		    
		    $allcompanies=$this->Customers->find('all',['order'=>['company_name'=>'asc']])->toArray();
		    $this->set('allcompanies',$allcompanies);
		    
		    $allfabrics=$this->Fabrics->find('all',['order'=>['fabric_name'=>'asc','color'=>'asc']])->toArray();
		    $this->set('allfabrics',$allfabrics);
			
			$this->set('formtype',$type);
	    }
	}
	
	
	
	public function backlogsummary($type="backlog"){
	    $this->set('thisType',$type);
	    
	    if($this->request->data){
	        $this->autoRender=false;
	        
	    
	        $finalArray=array();

    	    require($_SERVER['DOCUMENT_ROOT']."/vendor/phpoffice/phpexcel/Classes/PHPExcel.php");
            require($_SERVER['DOCUMENT_ROOT']."/vendor/phpoffice/phpexcel/Classes/PHPExcel/Writer/Excel2007.php");
            
    	    $objPHPExcel = new \PHPExcel();
            $objPHPExcel->setActiveSheetIndex(0);
			
			
			$objPHPExcel->getActiveSheet()->SetCellValue('A1', 'CUSTOMER');
            $objPHPExcel->getActiveSheet()->SetCellValue('B1', 'TOTAL EXT PRICE');
            
			
			$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(37);
			$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
			
			$objPHPExcel->getActiveSheet()->getRowDimension('1')->setRowHeight(18);
			$objPHPExcel->getActiveSheet()->getStyle('A1:B1')->getAlignment()->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);
			
			$objPHPExcel->getActiveSheet()->getStyle('A1:B1')->applyFromArray(
				array(
					'fill' => array(
						'type' => \PHPExcel_Style_Fill::FILL_SOLID,
						'color' => array('rgb' => 'F8CBAD')
					)
				)
			);
			
			
            $rowCount = 2;
			
			$conditions=array();
			
			if($type=='backlog'){
			    $conditions += array('status NOT IN' => array('Shipped','Canceled'));
			}else{
			    $conditions += array('status !=' => 'Canceled');
			}
			
			if((isset($this->request->data['datestart']) && strlen(trim($this->request->data['datestart'])) > 0) && (isset($this->request->data['dateend']) && strlen(trim($this->request->data['dateend'])) > 0)){
				$conditions += array('created >=' => strtotime($this->request->data['datestart'].' 00:00:00'));
				$conditions += array('created <=' => strtotime($this->request->data['dateend'].' 23:59:59'));
			}
			
			
			if(isset($this->request->data['customer']) && count($this->request->data['customer']) > 0){
				if(count($this->request->data['customer']) == 1){
					$conditions += array('customer_id' => $this->request->data['customer'][0]);
				}elseif(count($this->request->data['customer']) > 1){
					$conditions += array('customer_id IN' => $this->request->data['customer']);
				}
			}
			
			if(isset($this->request->data['hciagent']) && count($this->request->data['hciagent']) > 0){
				if(count($this->request->data['hciagent']) == 1){
					$conditions += array('user_id' => $this->request->data['hciagent'][0]);
				}elseif(count($this->request->data['hciagent']) > 1){
					$conditions += array('user_id IN' => $this->request->data['hciagent']);
				}
			}
			
			
			$extendedPriceValues='';
			
			//echo "<pre>"; print_r($conditions); echo "</pre>"; exit;
			
			$orderLookup=$this->Orders->find('all',['conditions' => $conditions,'order'=>['order_number'=>'asc']])->toArray();
			
			foreach($orderLookup as $orderRow){
				
				$quoteItemsLoop=$this->QuoteLineItems->find('all',['conditions'=>['quote_id' => $orderRow['quote_id']],'order'=>['line_number'=>'asc']])->toArray();
				
				$quoteData=$this->Quotes->get($orderRow['quote_id'])->toArray();
				
				$customer=$this->Customers->get($orderRow['customer_id'])->toArray();

				$thisCustomer=$this->Customers->get($orderRow['customer_id'])->toArray();
				
				foreach($quoteItemsLoop as $quoteItem){
					if($quoteItem['parent_line'] == 0){
					    
					$orderItemFind=$this->OrderItems->find('all',['conditions' => ['quote_line_item_id' => $quoteItem['id']]])->toArray();
					foreach($orderItemFind as $orderItem){
					    
    						//look for BATCHES, if found, loop through those, then loop through the remainders or unbatched items
    						$batchesScheduled=$this->OrderItemStatus->find('all',['conditions' => ['order_item_id' => $orderItem['id'], 'status IN' => ['Scheduled','Completed','Invoiced','Shipped']]])->toArray();
    						$qtyShipped=0;
    						$qtyInvoiced=0;
    						$qtyCompleted=0;
    						$qtyScheduled=0;
    						
    						//echo "<h3>Order Item ".$orderItem['id']."</h3>";
    						
    						$loop=array(
    							'batches' => array(),
    							'unbatched' => 0
    						);
    						
    						$scheduledBatchesThisLine=0;
    						
    						foreach($batchesScheduled as $batchData){
    							switch($batchData['status']){
    								case 'Shipped':
    									$qtyShipped=($qtyShipped + intval($batchData['qty_involved']));
    								break;
    								case 'Invoiced':
    									$qtyInvoiced=($qtyInvoiced + intval($batchData['qty_involved']));
    								break;
    								case 'Scheduled':
    									$qtyScheduled=($qtyScheduled + intval($batchData['qty_involved']));
    									$loop['batches'][]=array('batchid'=>$batchData['sherry_batch_id'],'qty'=>$batchData['qty_involved'],'scheduledTS'=>$batchData['time']);
    									$scheduledBatchesThisLine++;
    								break;
    								case 'Completed':
    									$qtyCompleted=($qtyCompleted + intval($batchData['qty_involved']));
    								break;
    							}
    						}
    						
    						//loop back through again to add Shipped or Invoiced timestamps to batches that have them
    						foreach($batchesScheduled as $batchData){
    							switch($batchData['status']){
    								case 'Shipped':
    									foreach($loop['batches'] as $num => $batchloopitem){
    										if($batchloopitem['batchid'] == $batchData['sherry_batch_id']){
    											$loop['batches'][$num]['shippedTS'] = $batchData['time'];
    										}
    									}
    								break;
    								case 'Invoiced':
    									foreach($loop['batches'] as $num => $batchloopitem){
    										if($batchloopitem['batchid'] == $batchData['sherry_batch_id']){
    											$loop['batches'][$num]['invoicedTS'] = $batchData['time'];
    										}
    									}
    								break;
    							}
    						}
    						
    						$remainingUnscheduled=(intval($quoteItem['qty']) - $qtyScheduled);
    						$remainingUninvoiced=(intval($quoteItem['qty']) - $qtyInvoiced);
    						$remainingUnshipped = (intval($quoteItem['qty']) - $qtyShipped);
    						$remainingIncompleteScheduled= (intval($quoteItem['qty']) - $qtyCompleted);
    						
    						$loop['unbatched']=$remainingUnscheduled;
    					
    						
    						$itemMetas=$this->QuoteLineItemMeta->find('all',['conditions'=>['quote_item_id'=>$quoteItem['id']]])->toArray();
    						$metaArray=array();
    						foreach($itemMetas as $meta){
    							$metaArray[$meta['meta_key']]=$meta['meta_value'];
    						}
    						
    						if($quoteItem['product_type'] != 'custom' && $quoteItem['product_type'] != 'services' && $quoteItem['product_type'] != 'track_systems'){
    							
    							
    							if($metaArray['fabrictype'] != 'none' && $metaArray['fabrictype'] != 'typein'){
    								
    								if(isset($metaArray['fabricid']) && strlen(trim($metaArray['fabricid'])) >0 && $metaArray['fabricid'] != '0'){
    									$thisFabric=$this->Fabrics->get($metaArray['fabricid'])->toArray();
    								}else{
    									$thisFabric=array('fabric_name'=>'','color'=>'');
    								}
    								
    								//$thisFabric=$this->Fabrics->get($metaArray['fabricid'])->toArray();
    							}elseif($metaArray['fabrictype'] == 'typein'){
    							    $thisFabric=array('fabric_name'=>$metaArray['fabric_name'],'color'=>$metaArray['fabric_color']);
    							}else{
    								$thisFabric=array('fabric_name'=>'','color'=>'');
    							}
    						}else{
    							if($metaArray['fabrictype'] != 'none' && $metaArray['fabrictype'] != 'typein'){
    								if(isset($metaArray['fabricid']) && strlen(trim($metaArray['fabricid'])) >0 && $metaArray['fabricid'] != '0'){
    									$thisFabric=$this->Fabrics->get($metaArray['fabricid'])->toArray();
    								}else{
    									$thisFabric=array('fabric_name'=>'','color'=>'');
    								}
    							}elseif($metaArray['fabrictype'] == 'typein'){
    							    $thisFabric=array('fabric_name'=>$metaArray['fabric_name'],'color'=>$metaArray['fabric_color']);
    							}else{
    								$thisFabric=array('fabric_name'=>'','color'=>'');
    							}
    						}
    						
    						
    						//$thisbookingdate=date('n/j/Y',$orderRow['created']);
    						
    						$bookDateTimeObj=new \DateTime();
    						$bookDateTimeObj->setTimestamp($orderRow['created']);
    							
    						$thisbookingdate=\PHPExcel_Shared_Date::PHPToExcel($bookDateTimeObj);
    						
    						/*$thisbookingdate='';
    
    						//lookup the original Order Item Status for this line item and get the date value
    						$itemstatuslookup=$this->OrderItemStatus->find('all',['conditions' => ['order_item_id' => $orderItem['id'],'status' => 'Not Started']])->toArray();
    						foreach($itemstatuslookup as $itemstatusrow){
    							$thisbookingdate=date('n/j/Y',$itemstatusrow['time']);
    						}
                            */
    
    						$customerValue=$thisCustomer['company_name'];
    						
    						
    						if($orderRow['due'] < 1000){
    							$shipDateValue='';
    						}else{
    							//$shipDateValue=date('n/d/Y',$orderRow['due']);
    							$dueDateTimeObj=new \DateTime();
            					$dueDateTimeObj->setTimestamp($orderRow['due']);
            					$shipDateValue = \PHPExcel_Shared_Date::PHPToExcel($dueDateTimeObj);
    						}
    
    						
    						
    						$bestprice=$quoteItem['best_price'];
    						$installadjustmentpercentage=0;
    						$tieradjustmentpercentage=0;
    						$tierDiscountOrPremium='Disc';
    						$rebateadjustmentpercentage=0;
    						
    						if($metaArray['specialpricing']=='1'){
    							$tieradjusted=number_format(floatval($quoteItem['best_price']),2,'.',',');
    							$installadjusted=number_format(floatval($quoteItem['best_price']),2,'.',',');
    							$rebateadjusted=number_format(floatval($quoteItem['best_price']),2,'.',',');
    							$pmiadjusted=number_format(floatval($quoteItem['pmi_adjusted']),2,'.',',');
    							$pmiadjustmentdollars=number_format((floatval(str_replace(',','',$pmiadjusted)) - floatval(str_replace(',','',$rebateadjusted))),2,'.',',');
    							
    						}else{
    							$tieradjusted=number_format(floatval($quoteItem['tier_adjusted_price']),2,'.',',');
    							$installadjusted=number_format(floatval($quoteItem['install_adjusted_price']),2,'.',',');
    							$rebateadjusted=number_format(floatval($quoteItem['rebate_adjusted_price']),2,'.',',');
    							$pmiadjusted=number_format(floatval($quoteItem['pmi_adjusted']),2,'.',',');
    							
    							$tieradjustmentpercentage=round(abs((((1/floatval(str_replace(',','',$quoteItem['best_price']))) * floatval(str_replace(',','',$tieradjusted)))*100)),2);
    							$installadjustmentpercentage=round(abs(((1-((1/floatval(str_replace(',','',$tieradjusted))) * floatval(str_replace(',','',$installadjusted))))*100)),2);
    							$rebateadjustmentpercentage=round(abs(((1-((1/floatval(str_replace(',','',$installadjusted))) * floatval(str_replace(',','',$rebateadjusted))))*100)),2);
    							$pmiadjustmentdollars=number_format((floatval(str_replace(',','',$pmiadjusted)) - floatval(str_replace(',','',$rebateadjusted))),2,'.',',');
    						}
    						
    						
    						if($quoteItem['override_active'] == 1){
    							$adjustedColValue = number_format(floatval($quoteItem['override_price']),2,'.','');
    						}else{
    							$adjustedColValue = number_format(floatval($pmiadjusted),2,'.','');
    						}
    						
    						
    					    
    					    //echo "<pre>"; print_r($loop); echo "</pre><hr>";
    					    
    						
    						$dateScheduled='';
    						$dateShipped='';
    						$dateInvoiced='';
    						$dateProduced='';
    						
    						
    						//start inner loop of $loop
    						//begin with UNBATCHED
    						if($loop['unbatched'] > 0){
    							$qtyValue=$loop['unbatched'];
    							$batchValue='N/A';
    							$extendedprice=0.00;
    							
    							if($metaArray['specialpricing']=='1'){
    								if($quoteItem['override_active'] == 1){
    									$extendedprice=(floatval($quoteItem['override_price']) * floatval($qtyValue));
    								}else{
    									$extendedprice=(floatval($quoteItem['best_price'])*floatval($qtyValue));
    								}
    							}else{
    							    if($quoteItem['override_active'] == 1){
    									$extendedprice=(floatval($quoteItem['override_price']) * floatval($qtyValue));
    								}else{
    								    $extendedprice=(floatval($quoteItem['pmi_adjusted']) * $qtyValue);
    								}
    							}
    							
    							$extendedPriceValues .= $extendedprice."+";
    							
    							
    							if(!is_array($finalArray[$thisCustomer['company_name']])){
    								$finalArray[$thisCustomer['company_name']]=array(
    									'total' => floatval($extendedprice)
    								);
    							}else{
    								$finalArray[$thisCustomer['company_name']]['total']=(floatval($finalArray[$thisCustomer['company_name']]['total']) + floatval($extendedprice));
    							}
    						
    							$rowCount++;
    						}
    						
    						
    						//then loop through BATCHED
    						foreach($loop['batches'] as $num => $batchloopitem){
    						    $totalLFvalue='';
    						    $extendedprice=0.00;
    						    
    						    if($type=='backlog' && ((isset($batchloopitem['invoicedTS']) && $batchloopitem['invoicedTS'] > 1000) && (isset($batchloopitem['shippedTS']) && $batchloopitem['shippedTS'] > 1000) )){
    								//ignore this row, it's already shipped and invoiced
    								
    							    //if(((isset($batchloopitem['invoicedTS']) && $batchloopitem['invoicedTS'] > 1000) && (isset($batchloopitem['shippedTS']) && $batchloopitem['shippedTS'] > 1000) )){
    								    //ignore this row, it's already shipped and invoiced
    							}else{
    								$qtyValue=$batchloopitem['qty'];
    								
                                    if($metaArray['specialpricing']=='1'){
        								if($quoteItem['override_active'] == 1){
        									$extendedprice=(floatval($quoteItem['override_price']) * floatval($qtyValue));
        								}else{
        									$extendedprice=(floatval($quoteItem['best_price'])*floatval($qtyValue));
        								}
        							}else{
        							    if($quoteItem['override_active'] == 1){
        									$extendedprice=(floatval($quoteItem['override_price']) * floatval($qtyValue));
        								}else{
        								    $extendedprice=(floatval($quoteItem['pmi_adjusted']) * $qtyValue);
        								}
        							}
        							


	    							if(!is_array($finalArray[$thisCustomer['company_name']])){
	    								$finalArray[$thisCustomer['company_name']]=array(
	    									'total' => floatval($extendedprice)
	    								);
	    							}else{
	    								$finalArray[$thisCustomer['company_name']]['total']=(floatval($finalArray[$thisCustomer['company_name']]['total']) + floatval($extendedprice));
	    							}
    								$rowCount++;
    							}
    						
    						}
						
					    }
					}
					
				}
				
			}

			ksort($finalArray);

            /*
            echo "<pre>";
            print_R($finalArray);
            echo "</pre>";
            exit;
            */

            $grandTotal=0.00;
            
			$rowNum=2;
			foreach($finalArray as $company => $data){
			    if(floatval($data['total']) > 0.00){
    				$objPHPExcel->getActiveSheet()->SetCellValue('A'.$rowNum, $company);
    				$objPHPExcel->getActiveSheet()->SetCellValue('B'.$rowNum, number_format(floatval($data['total']),2,'.',''));
    				
    				$grandTotal=($grandTotal+floatval($data['total']));
    				
    				$rowNum++;
			    }
			}

            $rowNum=($rowNum-1);
			
			$objPHPExcel->getActiveSheet()->getStyle('A1:A'.$rowNum)->getAlignment()->setHorizontal('center');
			$objPHPExcel->getActiveSheet()->getStyle('B1:B'.$rowNum)->getAlignment()->setHorizontal('center');
			
			$objPHPExcel->getActiveSheet()->getStyle('A1:B'.$rowNum)->getAlignment()->setVertical(\PHPExcel_Style_Alignment::VERTICAL_TOP );
			
			
			
			$objPHPExcel->getActiveSheet()->getStyle("A1:B".$rowNum)->applyFromArray(
				array(
					'borders' => array(
						'allborders' => array(
							'style' => \PHPExcel_Style_Border::BORDER_THIN,
							'color' => array('rgb' => '111111')
						)
					)
				)
			);
			
			$objPHPExcel->getActiveSheet()->freezePane('A2');
			
		
			
			$grandTotalRow=($rowNum+2);
			$objPHPExcel->getActiveSheet()->SetCellValue('A'.$grandTotalRow,'Grand Total:');
			$objPHPExcel->getActiveSheet()->SetCellValue('B'.$grandTotalRow,number_format(floatval($grandTotal),2,'.',''));
			$objPHPExcel->getActiveSheet()->getStyle('A'.$grandTotalRow)->getAlignment()->setHorizontal('right');
			$objPHPExcel->getActiveSheet()->getStyle('B'.$grandTotalRow)->getAlignment()->setHorizontal('center');
          
            
            // Redirect output to a client’s web browser (Excel5)
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            //header('Content-Disposition: attachment;filename="Backlog Summary Report.xlsx"');
            
            header('Content-Disposition: attachment;filename="'.ucfirst($type).' Summary Report.xlsx"');
            
            
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
	    }else{
	        //display the form
	        $allusers=$this->Users->find('all',['order'=>['last_name'=>'asc','first_name'=>'asc']])->toArray();
		    $this->set('allusers',$allusers);
		    
		    $allcompanies=$this->Customers->find('all',['order'=>['company_name'=>'asc']])->toArray();
		    $this->set('allcompanies',$allcompanies);
		    
		    $allfabrics=$this->Fabrics->find('all',['order'=>['fabric_name'=>'asc','color'=>'asc']])->toArray();
		    $this->set('allfabrics',$allfabrics);
			
			$this->set('formtype',$type);
	    }
	}
	
	/**PPSASCRUM-62 start**/
	/**public function shipaddressreport(){
	    if($this->request->data){
	        
	        $this->autoRender=false;
	    
	        $finalArray=array();

    	    require($_SERVER['DOCUMENT_ROOT']."/vendor/phpoffice/phpexcel/Classes/PHPExcel.php");
            require($_SERVER['DOCUMENT_ROOT']."/vendor/phpoffice/phpexcel/Classes/PHPExcel/Writer/Excel2007.php");
            
    	    $objPHPExcel = new \PHPExcel();
            $objPHPExcel->setActiveSheetIndex(0);
			
			
			$objPHPExcel->getActiveSheet()->SetCellValue('A1', 'ORDER NUMBER');
			$objPHPExcel->getActiveSheet()->SetCellValue('B1', 'CUSTOMER');
			$objPHPExcel->getActiveSheet()->SetCellValue('C1', 'CUSTOMER PO#');
            $objPHPExcel->getActiveSheet()->SetCellValue('D1', 'PROJECT MANAGER');
            $objPHPExcel->getActiveSheet()->SetCellValue('E1', 'RECIPIENT');
            $objPHPExcel->getActiveSheet()->SetCellValue('F1', 'ATTENTION');
            $objPHPExcel->getActiveSheet()->SetCellValue('G1', 'ADDRESS LINE 1');
            $objPHPExcel->getActiveSheet()->SetCellValue('H1', 'ADDRESS LINE 2');
            $objPHPExcel->getActiveSheet()->SetCellValue('I1', 'CITY');
            $objPHPExcel->getActiveSheet()->SetCellValue('J1', 'STATE');
            $objPHPExcel->getActiveSheet()->SetCellValue('K1', 'ZIPCODE');
            $objPHPExcel->getActiveSheet()->SetCellValue('L1', 'SHIP METHOD');
            $objPHPExcel->getActiveSheet()->SetCellValue('M1', 'INSTRUCTIONS/NOTES');
            
			
			$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(12);
			$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(16);
			$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
			$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
			$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(38);
			$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(38);
			$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(38);
			$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(38);
			$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(20);
			$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(20);
			$objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(20);
			$objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(20);
			$objPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth(40);
			
			$objPHPExcel->getActiveSheet()->getRowDimension('1')->setRowHeight(18);
			$objPHPExcel->getActiveSheet()->getStyle('A1:M1')->getAlignment()->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);
			
			
			$objPHPExcel->getActiveSheet()->getStyle('A1:M1')->applyFromArray(
				array(
					'fill' => array(
						'type' => \PHPExcel_Style_Fill::FILL_SOLID,
						'color' => array('rgb' => 'F8CBAD')
					)
				)
			);
			
			
            $rowCount = 2;
            
			$conditions=array('shipping_method_id >' => 0);
			
			if((isset($this->request->data['report_date_start']) && strlen(trim($this->request->data['report_date_start'])) > 0) && (isset($this->request->data['report_date_end']) && strlen(trim($this->request->data['report_date_end'])) > 0)){
				$conditions += array('created >=' => strtotime($this->request->data['report_date_start'].' 00:00:00'));
				$conditions += array('created <=' => strtotime($this->request->data['report_date_end'].' 23:59:59'));
			}
			
			if(isset($this->request->data['manager_id']) && intval($this->request->data['manager_id']) > 0){
				$conditions += array('project_manager_id' => $this->request->data['manager_id']);
			}
			
			$orders=$this->Orders->find('all',['conditions'=>$conditions,'order'=>['order_number'=>'DESC']])->toArray();
			
			foreach($orders as $order){
			    
			    $manager=$order['project_manager_id'];
			    
			    if(is_numeric($order['project_manager_id'])){
			        if($order['project_manager_id'] > 0){
			            $mgrow=$this->Users->get($order['project_manager_id'])->toArray();
			            $manager=$mgrow['first_name'].' '.$mgrow['last_name'];
			            
			        }
			    }
			    
			    $shipmethod='';
			    if(is_numeric($order['shipping_method_id'])){
			        $methodRow=$this->ShippingMethods->get($order['shipping_method_id'])->toArray();
		            $shipmethod=$methodRow['name'];
			    }
			    
			    $customer=$this->Customers->get($order['customer_id'])->toArray();
			    
			    $objPHPExcel->getActiveSheet()->SetCellValue('A'.$rowCount, $order['order_number']);
			    $objPHPExcel->getActiveSheet()->SetCellValue('B'.$rowCount, $customer['company_name']);
			    $objPHPExcel->getActiveSheet()->SetCellValue('C'.$rowCount, $order['po_number']);
			    $objPHPExcel->getActiveSheet()->SetCellValue('D'.$rowCount, $manager);
			    $objPHPExcel->getActiveSheet()->SetCellValue('E'.$rowCount, $order['facility']);
			    $objPHPExcel->getActiveSheet()->SetCellValue('F'.$rowCount, $order['attention']);
			    $objPHPExcel->getActiveSheet()->SetCellValue('G'.$rowCount, $order['shipping_address_1']);
			    $objPHPExcel->getActiveSheet()->SetCellValue('H'.$rowCount, $order['shipping_address_2']);
			    $objPHPExcel->getActiveSheet()->SetCellValue('I'.$rowCount, $order['shipping_city']);
			    $objPHPExcel->getActiveSheet()->SetCellValue('J'.$rowCount, $order['shipping_state']);
			    $objPHPExcel->getActiveSheet()->SetCellValue('K'.$rowCount, $order['shipping_zipcode']);
			    $objPHPExcel->getActiveSheet()->SetCellValue('L'.$rowCount, $shipmethod);
			    $objPHPExcel->getActiveSheet()->SetCellValue('M'.$rowCount, $order['shipping_instructions']);
			    
			    $rowCount++;
			}
          
            
            $objPHPExcel->getActiveSheet()->getStyle('A2:M'.($rowCount-1))->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
            
            // Redirect output to a client’s web browser (Excel5)
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="Ship-To Address Report.xlsx"');
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
	        
	    }else{
	        $allManagers=array();
	        $managersLookup=$this->Users->find('all',['order'=>['first_name'=>'ASC','last_name'=>'ASC']])->toArray();
	        foreach($managersLookup as $mgr){
	            $ordersThisMgr=$this->Orders->find('all',['conditions' => ['project_manager_id' => $mgr['id']]])->toArray();
	            if(count($ordersThisMgr) > 0){
	                $allManagers[$mgr['id']]=$mgr['first_name'].' '.$mgr['last_name'];
	            }
	        }
	        $this->set('allManagers',$allManagers);
	        
	    }
	}**/
	public function shipaddressreport(){
	    if($this->request->data){
	        
	        $this->autoRender=false;
	    
	        $finalArray=array();

    	    require($_SERVER['DOCUMENT_ROOT']."/vendor/phpoffice/phpexcel/Classes/PHPExcel.php");
            require($_SERVER['DOCUMENT_ROOT']."/vendor/phpoffice/phpexcel/Classes/PHPExcel/Writer/Excel2007.php");
            
    	    $objPHPExcel = new \PHPExcel();
            $objPHPExcel->setActiveSheetIndex(0);
			
			
			$objPHPExcel->getActiveSheet()->SetCellValue('A1', 'ORDER NUMBER');
			$objPHPExcel->getActiveSheet()->SetCellValue('B1', 'CUSTOMER');
			$objPHPExcel->getActiveSheet()->SetCellValue('C1', 'CUSTOMER PO#');
            $objPHPExcel->getActiveSheet()->SetCellValue('D1', 'PROJECT MANAGER');
            $objPHPExcel->getActiveSheet()->SetCellValue('E1', 'RECIPIENT');
			$objPHPExcel->getActiveSheet()->SetCellValue('F1', 'ADDRESS NAME');
            $objPHPExcel->getActiveSheet()->SetCellValue('G1', 'ATTENTION');
            $objPHPExcel->getActiveSheet()->SetCellValue('H1', 'ADDRESS LINE 1');
            $objPHPExcel->getActiveSheet()->SetCellValue('I1', 'ADDRESS LINE 2');
            $objPHPExcel->getActiveSheet()->SetCellValue('J1', 'CITY');
            $objPHPExcel->getActiveSheet()->SetCellValue('K1', 'STATE');
            $objPHPExcel->getActiveSheet()->SetCellValue('L1', 'ZIPCODE');
            $objPHPExcel->getActiveSheet()->SetCellValue('M1', 'SHIP METHOD');
            $objPHPExcel->getActiveSheet()->SetCellValue('N1', 'INSTRUCTIONS/NOTES');
            
			
			$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(12);
			$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(16);
			$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
			$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
			$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(38);
			$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(38);
			$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(38);
			$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(38);
			$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(38);
			$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(20);
			$objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(20);
			$objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(20);
			$objPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth(20);
			$objPHPExcel->getActiveSheet()->getColumnDimension('N')->setWidth(40);
			
			$objPHPExcel->getActiveSheet()->getRowDimension('1')->setRowHeight(18);
			$objPHPExcel->getActiveSheet()->getStyle('A1:N1')->getAlignment()->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);
			
			
			$objPHPExcel->getActiveSheet()->getStyle('A1:N1')->applyFromArray(
				array(
					'fill' => array(
						'type' => \PHPExcel_Style_Fill::FILL_SOLID,
						'color' => array('rgb' => 'F8CBAD')
					)
				)
			);
			
			
            $rowCount = 2;
            
					
			//$orders=$this->Orders->find('all',['conditions'=>$conditions,'order'=>['order_number'=>'DESC']])->toArray();
			$db = ConnectionManager::get('default');
            $query = " SELECT *,ship_to.ship_to_name as shipName, facility.facility_name as facility_name, ";
            $query.= " orders.shipping_address_1 as shipaddress1, orders.shipping_address_2 as shipaddress2, orders.shipping_city as shipcity, ";
            $query.= " orders.shipping_state as shipstate, orders.shipping_zipcode as shipzipcode, orders.attention as orderAttention FROM orders ";
            $query.= " LEFT JOIN ship_to ON orders.shipto_id=ship_to.id   ";
			$query.= " LEFT JOIN facility ON orders.facility_id=facility.id ";
            $query.= " WHERE  ";
            $query.= " orders.created >= " . strtotime($this->request->data['report_date_start'].' 00:00:00') . " AND orders.created  <= " . strtotime($this->request->data['report_date_end'].' 23:59:59');
			$query.= " and  orders.shipping_method_id > 0 ";
			if(isset($this->request->data['manager_id']) && intval($this->request->data['manager_id']) > 0){
				$query.= " and  order.project_manager_id =  ". $this->request->data['manager_id'];
			}
			$queryRun = $db->execute($query);
            $orders = $queryRun->fetchAll('assoc');
          // echo "<pre/>";print_r($orders); echo "<pre/>";die();
			foreach($orders as $order){
			    $manager=$order['project_manager_id'];
			    $addressName = '';
				$facility = '';
				$shippingaddress1 ='test';
				$shippingaddress2 ='';
				$shippingstate ='';
				$shippingcity ='';
				$shippingzipcode ='';
				/**PPSASCRUM-71 Start **/
				if($order['shipto_id'] != NULL) {
				    
				    try {
    				     $shipto = $this->ShipTo->get($order['shipto_id'])->toArray();
    				     $addressName = $shipto['ship_to_name'];
    				     $shippingaddress1 = $shipto['shipping_address_1'];
    				     $shippingaddress2 = $shipto['shipping_address_2'];
    				     $shippingcity = $shipto['shipping_city'];
    				     $shippingstate = $shipto['shipping_state'];
    				     $shippingzipcode = $shipto['shipping_zipcode'];
				    } catch (RecordNotFoundException $e) { }
				} else if($order['facility_id'] > 0 && $order['userType'] == 'default'){
				    try {
    				    $fac=$this->Facility->get($order['facility_id'])->first()->toArray();
    	    	        $shipto = $this->ShipTo->get($fac['default_address'])->toArray();
    				    $addressName = $shipto['ship_to_name'];
    				    $shippingaddress1 = $shipto['shipping_address_1'];
    				    $shippingaddress2 = $shipto['shipping_address_2'];
    				    $shippingcity = $shipto['shipping_city'];
    				    $shippingstate = $shipto['shipping_state'];
    				    $shippingzipcode = $shipto['shipping_zipcode'];
			    	} catch (RecordNotFoundException $e) { }
				}else if($order['shipto_id'] == NULL){
				     $addressName = '';
				     $shippingaddress1 = ($order['shipaddress1']) ? $order['shipaddress1']:"";
				     $shippingaddress2 = $order['shipaddress2'];
				     $shippingcity = $order['shipcity'];
				     $shippingstate = $order['shipstate'];
				     $shippingzipcode = $order['shipzipcode'];
				}
				/**PPSASCRUM-71 End **/

				if($order['facility_id'] != NULL) {
					$facility = $order['facility_name'];
				} else {
					$facility = $order['facility'];
				}
			    if(is_numeric($order['project_manager_id'])){
			        if($order['project_manager_id'] > 0){
			            $mgrow=$this->Users->get($order['project_manager_id'])->toArray();
			            $manager=$mgrow['first_name'].' '.$mgrow['last_name'];
			            
			        }
			    }
			    
			    $shipmethod='';
			    if(is_numeric($order['shipping_method_id'])){
			        $methodRow=$this->ShippingMethods->get($order['shipping_method_id'])->toArray();
		            $shipmethod=$methodRow['name'];
			    }
			    
			    $customer=$this->Customers->get($order['customer_id'])->toArray();

			    $objPHPExcel->getActiveSheet()->SetCellValue('A'.$rowCount, $order['order_number']);
			    $objPHPExcel->getActiveSheet()->SetCellValue('B'.$rowCount, $customer['company_name']);
			    $objPHPExcel->getActiveSheet()->SetCellValue('C'.$rowCount, $order['po_number']);
			    $objPHPExcel->getActiveSheet()->SetCellValue('D'.$rowCount, $manager);
			    $objPHPExcel->getActiveSheet()->SetCellValue('E'.$rowCount, $facility);
				$objPHPExcel->getActiveSheet()->SetCellValue('F'.$rowCount, $addressName);
			    $objPHPExcel->getActiveSheet()->SetCellValue('G'.$rowCount, $order['orderAttention']);
			    $objPHPExcel->getActiveSheet()->SetCellValue('H'.$rowCount, $shippingaddress1);
			    $objPHPExcel->getActiveSheet()->SetCellValue('I'.$rowCount, $shippingaddress2);
			    $objPHPExcel->getActiveSheet()->SetCellValue('J'.$rowCount, $shippingcity);
			    $objPHPExcel->getActiveSheet()->SetCellValue('K'.$rowCount, $shippingstate);
			    $objPHPExcel->getActiveSheet()->SetCellValue('L'.$rowCount, $shippingzipcode);
			    $objPHPExcel->getActiveSheet()->SetCellValue('M'.$rowCount, $shipmethod);
			    $objPHPExcel->getActiveSheet()->SetCellValue('N'.$rowCount, $order['shipping_instructions']);
			    
			    $rowCount++;
			}
          
            
            $objPHPExcel->getActiveSheet()->getStyle('A2:N'.($rowCount-1))->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
            
            // Redirect output to a client’s web browser (Excel5)
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="Ship-To Address Report.xlsx"');
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
	        
	    }else{
	        $allManagers=array();
	        $managersLookup=$this->Users->find('all',['order'=>['first_name'=>'ASC','last_name'=>'ASC']])->toArray();
	        foreach($managersLookup as $mgr){
	            $ordersThisMgr=$this->Orders->find('all',['conditions' => ['project_manager_id' => $mgr['id']]])->toArray();
	            if(count($ordersThisMgr) > 0){
	                $allManagers[$mgr['id']]=$mgr['first_name'].' '.$mgr['last_name'];
	            }
	        }
	        $this->set('allManagers',$allManagers);
	        
	    }
	}
	/** PPSASCRUM-62 end **/
	
	
	public function msr($action,$varTwo=false,$varThree=false,$varFour=false,$varFive=false){
	    switch($action){
	        case 'getfabcolors':
	            $this->autoRender=false;
	            $theseColors=$this->Fabrics->find('all',['conditions' => ['status'=>'Active','fabric_name' => $varTwo],'order'=>['color'=>'asc']])->toArray();
	            $out='<option value=\"\" selected disabled>--Select A Color--</option>';
	            foreach($theseColors as $color){
	               $out .= '<option value="'.$color['color'].'">'.$color['color'].'</option>';
	            }
	            echo $out;exit;
	        break;
	        case 'add':
	            $this->autoRender=false;
	            if($this->request->data){
	                
	                $newEntry=$this->Msr->newEntity();
	                
	                $newEntry->msr_status = $this->request->data['msrstatus'];
	                $newEntry->vendors_id = $this->request->data['vendor_id'];
	                $newEntry->qty = $this->request->data['qty'];
	                if($this->request->data['parttype'] == 'existingfabric'){
	                    $newEntry->part_type='existing';
	                    $newEntry->part_or_fabric = $this->request->data['fabric_name'];
	                    $newEntry->revision_or_color = $this->request->data['fabric_color'];
	                    
	                    $lookupFabric=$this->Fabrics->find('all',['conditions' => ['fabric_name' => $this->request->data['fabric_name'], 'color' => $this->request->data['fabric_color']]])->toArray();
	                    foreach($lookupFabric as $fabricRow){
	                        $newEntry->fabrics_id = $fabricRow['id'];
	                    }
	                    
	                }elseif($this->request->data['parttype'] == 'typein'){
	                    $newEntry->part_type='typein';
	                    $newEntry->part_or_fabric = $this->request->data['part_name'];
	                    $newEntry->revision_or_color = $this->request->data['revision_name'];
	                }
	                
	                $newEntry->work_order_number = $this->request->data['work_order_number'];
	                $newEntry->order_status=$this->request->data['order_status'];
	                $newEntry->order_date = $this->request->data['order_date'];
	                
	                if(trim($this->request->data['estimated_ship_date']) == ""){
	                    $newEntry->estimated_ship_date = null;
	                }else{
	                    $newEntry->estimated_ship_date = $this->request->data['estimated_ship_date'];
	                }
	                
	                
	                if(trim($this->request->data['eta']) == ""){
	                    $newEntry->eta = null;
	                }else{
	                    $newEntry->eta = $this->request->data['eta'];
	                }
	                
	                $newEntry->shipment_status = $this->request->data['shipment_status'];
	                $newEntry->qb_po_number = $this->request->data['qb_po_number'];
	                $newEntry->notes = $this->request->data['notes'];
	                
	                if($this->Msr->save($newEntry)){
	                    $this->logActivity($_SERVER['REQUEST_URI'],'Created MSR entry '.$newEntry->id);
	                    $this->Flash->success('Successfully added MSR entry '.$newEntry->id);
	                    return $this->redirect('/reports/msr/');
	                }
	                
	            }else{
	                $allVendors=$this->Vendors->find('all',['conditions'=>['status'=>'Active'],'order'=>['vendor_name'=>'asc']])->toArray();
	                $this->set('allVendors',$allVendors);
	                
	                
	                $allFabrics=$this->Fabrics->find('all',['conditions' => ['status'=>'Active'],'order'=>['fabric_name'=>'asc']])->group('fabric_name')->toArray();
	                $this->set('allFabrics',$allFabrics);
	                
	                $this->render('addmsr');
	            }
	        break;
	        case 'edit':
	            $this->autoRender=false;
	            if($this->request->data){
	                
	                $thisEntry=$this->Msr->get($varTwo);
	                $thisEntry->msr_status = $this->request->data['msrstatus'];
	                $thisEntry->vendors_id = $this->request->data['vendor_id'];
	                $thisEntry->qty = $this->request->data['qty'];
	                if($this->request->data['parttype'] == 'existingfabric'){
	                    $thisEntry->part_type='existing';
	                    $thisEntry->part_or_fabric = $this->request->data['fabric_name'];
	                    $thisEntry->revision_or_color = $this->request->data['fabric_color'];
	                    
	                    $lookupFabric=$this->Fabrics->find('all',['conditions' => ['fabric_name' => $this->request->data['fabric_name'], 'color' => $this->request->data['fabric_color']]])->toArray();
	                    foreach($lookupFabric as $fabricRow){
	                        $thisEntry->fabrics_id = $fabricRow['id'];
	                    }
	                    
	                }elseif($this->request->data['parttype'] == 'typein'){
	                    $thisEntry->part_type='typein';
	                    $thisEntry->fabrics_id=null;
	                    $thisEntry->part_or_fabric = $this->request->data['part_name'];
	                    $thisEntry->revision_or_color = $this->request->data['revision_name'];
	                }
	                
	                $thisEntry->work_order_number = $this->request->data['work_order_number'];
	                $thisEntry->order_status=$this->request->data['order_status'];
	                $thisEntry->order_date = $this->request->data['order_date'];
	                //$thisEntry->estimated_ship_date = $this->request->data['estimated_ship_date'];
	                //$thisEntry->eta = $this->request->data['eta'];
	                
	                if(trim($this->request->data['estimated_ship_date']) == ""){
	                    $thisEntry->estimated_ship_date = NULL;
	                }else{
	                    $thisEntry->estimated_ship_date = $this->request->data['estimated_ship_date'];
	                }
	                
	                
	                if(trim($this->request->data['eta']) == ""){
	                    $thisEntry->eta = NULL;
	                }else{
	                    $thisEntry->eta = $this->request->data['eta'];
	                }
	                
	                $thisEntry->shipment_status = $this->request->data['shipment_status'];
	                $thisEntry->qb_po_number = $this->request->data['qb_po_number'];
	                $thisEntry->notes = $this->request->data['notes'];
	                
	                if($this->Msr->save($thisEntry)){
	                    $this->logActivity($_SERVER['REQUEST_URI'],'Edited MSR entry '.$thisEntry->id);
	                    $this->Flash->success('Successfully savedd changes to MSR entry '.$thisEntry->id);
	                    return $this->redirect('/reports/msr/');
	                }
	                
	            }else{
	                $thisMsr=$this->Msr->get($varTwo)->toArray();
	                $this->set('thisMsr',$thisMsr);
	                
	                $allVendors=$this->Vendors->find('all',['conditions'=>['status'=>'Active'],'order'=>['vendor_name'=>'asc']])->toArray();
	                $this->set('allVendors',$allVendors);
	                
	                
	                $allFabrics=$this->Fabrics->find('all',['conditions' => ['status'=>'Active'],'order'=>['fabric_name'=>'asc','color'=>'asc']])->toArray();
	                $this->set('allFabrics',$allFabrics);
	                
	                $this->render('editmsr');
	            }
	        break;
	        case 'updatefield':
	            $this->autoRender=false;
	            
	            $thisRow=$this->Msr->get(intval($varTwo));
	            switch($varThree){
	               case 'order_status':
	                    if(!$varFive){
	                        $this->logActivity($_SERVER['REQUEST_URI'],'Changed "Order Status" to "'.$varFour.'" for MSR entry '.$varTwo);
	                    }
	                    $thisRow->order_status=$varFour;
	               break;
	               case 'estimated_ship_date':
	                   if(!$varFive){
	                       $this->logActivity($_SERVER['REQUEST_URI'],'Changed "Estimated Ship Date" to "'.$varFour.'" for MSR entry '.$varTwo);
	                   }
	                   $thisRow->estimated_ship_date=$varFour;
	               break;
	               case 'eta':
	                   if(!$varFive){
	                       $this->logActivity($_SERVER['REQUEST_URI'],'Changed "ETA" to "'.$varFour.'" for MSR entry '.$varTwo);
	                   }
	                   $thisRow->eta=$varFour;
	               break;
	               case 'shipment_status':
	                    if(!$varFive){
	                        $this->logActivity($_SERVER['REQUEST_URI'],'Changed "Shipment Status" to "'.$varFour.'" for MSR entry '.$varTwo);
	                    }
	                    $thisRow->shipment_status=$varFour;
	               break;
	               case 'notes':
					if(!$varFive){
						$this->logActivity($_SERVER['REQUEST_URI'],'Changed "Notes" to "'.$this->cleanfacilityparameterreplacements($varFour).'" for MSR entry '.$varTwo);
					}
					$thisRow->notes=$this->cleanfacilityparameterreplacements($varFour);
	               break;
	            }
	            $this->Msr->save($thisRow);
	            
	            echo "SUCCESS";
	            
	        break;
	        case 'delete':
	            $this->autoRender=false;
	            if($this->request->data){
    	            $thisEntry=$this->Msr->get($varTwo);
    	            if($this->Msr->delete($thisEntry)){
    	                $this->Flash->success('Successfully deleted MSR entry '.$varTwo);
    	                $this->logActivity($_SERVER['REQUEST_URI'],'Deleted MSR entry '.$varTwo);
    	                return $this->redirect('/reports/msr/');
    	            }
	            }else{
	                $thisMsr=$this->Msr->get($varTwo)->toArray();
	                $this->set('thisMsr',$thisMsr);
	                
	                $this->render('confirmdeletemsrentry');
	            }
	        break;
	        case 'closeentry':
	            $thisEntry=$this->Msr->get($varTwo);
	            $thisEntry->msr_status='Closed';
	            if($this->Msr->save($thisEntry)){
	                $this->Flash->success('Successfully closed MSR entry '.$varTwo);
	                $this->logActivity($_SERVER['REQUEST_URI'],'Closed MSR entry '.$varTwo);
	                return $this->redirect('/reports/msr/');
	            }
	        break;
	        case 'markdeliveredandclose':
	            $thisEntry=$this->Msr->get($varTwo);
	            $thisEntry->msr_status='Closed';
	            $thisEntry->shipment_status='Delivered';
	            if($this->Msr->save($thisEntry)){
	                $this->Flash->success('Successfully marked Delivered and Closed MSR entry '.$varTwo);
	                $this->logActivity($_SERVER['REQUEST_URI'],'Marked Delivered and Closed MSR entry '.$varTwo);
	                return $this->redirect('/reports/msr/');
	            }
	        break;
	        case 'markcancelledandclose':
	            $thisEntry=$this->Msr->get($varTwo);
	            $thisEntry->msr_status='Closed';
	            $thisEntry->shipment_status='Cancelled';
	            if($this->Msr->save($thisEntry)){
	                $this->Flash->success('Successfully marked Cancelled and Closed MSR entry '.$varTwo);
	                $this->logActivity($_SERVER['REQUEST_URI'],'Marked Cancelled and Closed MSR entry '.$varTwo);
	                return $this->redirect('/reports/msr/');
	            }
	        break;
	        case 'reopen':
	            $thisEntry=$this->Msr->get($varTwo);
	            $thisEntry->msr_status='Open';
	            if($this->Msr->save($thisEntry)){
	                $this->Flash->success('Successfully Reopened MSR entry '.$varTwo);
	                $this->logActivity($_SERVER['REQUEST_URI'],'Reopened MSR entry '.$varTwo);
	                return $this->redirect('/reports/msr/');
	            }
	        break;
	        default:
	            $allVendors=$this->Vendors->find('all',['order'=>['vendor_name'=>'asc']])->toArray();
	            $this->set('allVendors',$allVendors);
	            
	        break;
	    }
	    
	}
	
	
	/**PPSASCRUM-67 start **/
	public function getmsrlist($status='open'){
	    $msrs=array();
	    
	    $conditions=array('msr_status' => ucfirst($status));
	    
	    $query="SELECT a.*, b.vendor_name AS vendorname FROM `msr` a, `vendors` b WHERE b.id=a.vendors_id AND a.msr_status='".ucfirst($status)."'";
	    if(isset($this->request->data['search']['value']) && strlen(trim($this->request->data['search']['value'])) >0){
	        $query .= " AND (vendor_name LIKE '%".$this->request->data['search']['value']."%' OR a.part_or_fabric LIKE '%".$this->request->data['search']['value']."%' OR a.revision_or_color LIKE '%".$this->request->data['search']['value']."%' OR a.work_order_number LIKE '%".$this->request->data['search']['value']."%' OR a.qb_po_number LIKE '%".$this->request->data['search']['value']."%')";
	    }
	    
	    
	    $order=array();
		if(isset($this->request->data['order'][0]['column'])){
			switch($this->request->data['order'][0]['column']){
			    case 1:
			        $query .= " ORDER BY vendorname ".strtoupper($this->request->data['order'][0]['dir']);
			    break;
			    case 3:
			        $query .= " ORDER BY a.part_or_fabric ".strtoupper($this->request->data['order'][0]['dir']);
			    break;
			    case 4:
			        $query .= " ORDER BY a.revision_or_color ".strtoupper($this->request->data['order'][0]['dir']);
			    break;
			    case 5:
			        $query .= " ORDER BY a.work_order_number ".strtoupper($this->request->data['order'][0]['dir']);
			    break;
			    case 6:
			        $query .= " ORDER BY a.order_status ".strtoupper($this->request->data['order'][0]['dir']);
			    break;
				case 7:
				default:
					$query .= " ORDER BY a.order_date ".strtoupper($this->request->data['order'][0]['dir']);
				break;
				case 8:
				default:
					$query .= " ORDER BY a.estimated_ship_date ".strtoupper($this->request->data['order'][0]['dir']);
				break;
				case 10:
				    $query .= " ORDER BY a.shipment_status ".strtoupper($this->request->data['order'][0]['dir']);
				break;
				case 11:
				    $query .= " ORDER BY a.qb_po_number ".strtoupper($this->request->data['order'][0]['dir']);
				break;
			}
		}
		
		$db=ConnectionManager::get('default');
		
	    
	    $overallLookup=$this->Msr->find('all')->toArray();
	    $overallTotalRows=count($overallLookup);
	    
	    
	    $totqueryRun=$db->execute($query);
	    $filteredRows=$totqueryRun->fetchAll('assoc');
	    
	    
	    $totalFilteredRows=count($filteredRows);
	    
	    
	    $query .= " LIMIT ".$this->request->data['start'].",".$this->request->data['length'];
	    
	    $queryRun=$db->execute($query);
		$msrLookup=$queryRun->fetchAll('assoc');
	    
	    
	    foreach($msrLookup as $msrRow){
	        $addclass='';
	        $vendorName='';
	        $thisVendor=$this->Vendors->get($msrRow['vendors_id'])->toArray();
	        $vendorName=$thisVendor['vendor_name'];
	        
	        
	        
	        if(!is_null($msrRow['estimated_ship_date']) && strlen(trim($msrRow['estimated_ship_date'])) > 3 && $msrRow['estimated_ship_date'] != '0000-00-00'){
	            //calculate 3 business days from Estimated Ship Date if provided
	            $holidays=array(date('Y').'-12-25', date('Y-m-d',strtotime('Thanksgiving '.date('Y'))), (date('Y')+1).'-01-01');
	            
	            $maxdate=strtotime($this->add_business_days($msrRow['estimated_ship_date'],3,$holidays,'Y-m-d'));

    	        if(strtotime('today') > $maxdate && ($msrRow['shipment_status'] != 'In Transit' && $msrRow['shipment_status'] != 'Delivered' && $msrRow['shipment_status'] != 'Cancelled')){
    	            $addclass=' orangerow';
    	        }
    	           
    	        if(strtotime('today') > strtotime($msrRow['estimated_ship_date']) && ($msrRow['shipment_status'] == 'In Transit' && $msrRow['shipment_status'] != 'Delivered' && $msrRow['shipment_status'] != 'Cancelled')){
    	            $addclass=' yellowrow';
    	        }
    	            
        	    if($msrRow['shipment_status'] == 'In Transit'){
    	            $addclass=' yellowrow';
    	        } 
    	        
    	        if($msrRow['shipment_status'] == 'Delivered'){
    	            $addclass=' greenrow';
    	        }
    	            
    	        if($msrRow['shipment_status'] == 'Cancelled'){
    	            $addclass=' grayrow';
    	        }
    	            
    	        $shipdate=date('n/j/y',strtotime($msrRow['estimated_ship_date']));
	            
	        }else{
	            if($msrRow['shipment_status'] == 'Cancelled'){
	                $addclass=' grayrow';
	            }
	            
	            if(strtotime('today') > strtotime($msrRow['estimated_ship_date']) && ($msrRow['shipment_status'] == 'In Transit' && $msrRow['shipment_status'] != 'Delivered' && $msrRow['shipment_status'] != 'Cancelled')){
    	            $addclass=' yellowrow';
    	        }
    	       
    	        if($msrRow['shipment_status'] == 'In Transit'){
    	            $addclass=' yellowrow';
    	        } 
        	            
    	        if($msrRow['shipment_status'] == 'Delivered'){
    	            $addclass=' greenrow';
    	        }
	            
	            $shipdate='';
	        }
	        
	        if(!is_null($msrRow['eta']) && strlen(trim($msrRow['eta'])) > 3 && $msrRow['eta'] != '0000-00-00'){
	            $eta=date('n/j/y',strtotime($msrRow['eta']));
	        }else{
	            $eta='';
	        }
	        
	        if(($msrRow['shipment_status'] == 'Delivered' || $msrRow['shipment_status'] == 'Cancelled') && $msrRow['msr_status'] == 'Open'){
	            $ifCanClose=' <a href="/reports/msr/closeentry/'.$msrRow['id'].'"><img src="/img/cancel.png" alt="Close This Entry" title="Close This Entry" /></a>';
	        }else{
	            $ifCanClose='';
	        }
	        
	        $ifCanDelete=' <a href="/reports/msr/delete/'.$msrRow['id'].'"><img src="/img/delete.png" alt="Delete This Entry" title="Delete This Entry" /></a>';
	        
	        if($status=='closed'){
	            $ifCanEdit='';
	            $ifCanDelete .= ' <a href="/reports/msr/reopen/'.$msrRow['id'].'"><img src="/img/reopen.png" width="16" alt="Reopen this entry" title="Reopen this entry" /></a>';
	        }elseif($status=='open'){
	            $ifCanEdit = '<a href="/reports/msr/edit/'.$msrRow['id'].'"><img src="/img/edit.png" title="Edit This Entry" alt="Edit This Entry" /></a>';
	            
	            if($msrRow['shipment_status'] != 'Delivered' && $msrRow['shipment_status'] != 'Cancelled'){
	                $ifCanDelete .= ' <a href="/reports/msr/markdeliveredandclose/'.$msrRow['id'].'"><img src="/img/markdeliveredandclose.png" width="16" alt="Mark Delivered and Close Entry" title="Mark Delivered and Close Entry" /></a>';
	                $ifCanDelete .= ' <a href="/reports/msr/markcancelledandclose/'.$msrRow['id'].'"><img src="/img/markcancelledandclose.png" width="16" alt="Mark Cancelled and Close Entry" title="Mark Cancelled and Close Entry" /></a>';
	            }
	            
	        }
	        
	        $msrs[]=array(
				'DT_RowId'=>$msrRow['id'],
				'DT_RowClass'=>'rowtest'.$addclass,
				'0' => '<input type="checkbox" name="bulkedit_'.$msrRow['id'].'" value="yes" />',
				'1' => $vendorName,
				'2' => $msrRow['qty'],
				'3' => $msrRow['part_or_fabric'],
				'4' => $msrRow['revision_or_color'],
				'5' => $msrRow['work_order_number'],
				'6' => $msrRow['order_status'],
				'7' => date('n/j/y',strtotime($msrRow['order_date'])),
				'8' => $shipdate,
				'9' => $eta,
				'10' => $msrRow['shipment_status'],
				'11' => $msrRow['qb_po_number'],
				'12' => $msrRow['notes'],
				'13' => $ifCanEdit.$ifCanClose.$ifCanDelete
			);
		}
		
		$this->set('draw',$this->request->data['draw']);
		$this->set('recordsTotal',$overallTotalRows);
		$this->set('recordsFiltered',$totalFilteredRows);
		$this->set('data',$msrs);
		$this->set('_serialize',['draw','recordsTotal','recordsFiltered','data']);
	}
	/**PPSASCRUM-67 end **/	
	public function bulkeditmsr($ids){
	    $params = array();
		$fixedparams=array();
		parse_str(urldecode($ids), $params);
		foreach($params as $key => $val){
			if($val=="yes"){
				$fixedparams[]=str_replace("bulkedit_","",$key);
			}
		}
		
		if($this->request->data['process']=="step2"){
		    $this->set('step','step2');
			$this->set('entryids',$fixedparams);
			$msrEntries=$this->Msr->find('all',['conditions'=>['id IN'=>$fixedparams]])->toArray();
    		$this->set('selectedentries',$msrEntries);
            $this->set('inputdata',$this->request->data);
            
		}elseif($this->request->data['process']=="step3"){
		    
		    $msrTable=TableRegistry::get('Msr');
		    
		    $lognote='Bulk Edit changes saved to MSR entries ';
		    
		    
		    foreach($fixedparams as $entryid){
		        
		        $thisMSRentry=$msrTable->get($entryid);
		        if($this->request->data['order_status'] != ''){
		            $thisMSRentry->order_status = $this->request->data['order_status'];
		        }
		        
		        if($this->request->data['estimated_ship_date'] != ''){
		            $thisMSRentry->estimated_ship_date = $this->request->data['estimated_ship_date'];
		        }else{
		            if($this->request->data['blank_esd'] == '1'){
		                $thisMSRentry->estimated_ship_date=NULL;
		            }
		        }
		        
		        if($this->request->data['eta'] != ''){
		            $thisMSRentry->eta = $this->request->data['eta'];
		        }else{
		            if($this->request->data['blank_eta'] == '1'){
		                $thisMSRentry->eta=NULL;
		            }
		        }
		        
		        if($this->request->data['shipment_status'] != ''){
		            $thisMSRentry->shipment_status = $this->request->data['shipment_status'];
		        }
		        
		        if($this->request->data['qb_po_number'] != ''){
		            $thisMSRentry->qb_po_number = $this->request->data['qb_po_number'];
		        }
		        
		        if($this->request->data['notes'] != ''){
		            $thisMSRentry->notes = $this->request->data['notes'];
		        }
		        
		        $lognote .= $entryid.',';
		        $msrTable->save($thisMSRentry);
		    }
		    
		    $this->logActivity($_SERVER['REQUEST_URI'],$lognote);
	        $this->Flash->success('Successfully bulk edited selected MSR entries');
	        return $this->redirect('/reports/msr/');
		    
		}else{
    		$this->set('step','step1');
    		$this->set('entryids',$fixedparams);
    		$msrEntries=$this->Msr->find('all',['conditions'=>['id IN'=>$fixedparams]])->toArray();
    		$this->set('selectedentries',$msrEntries);
		}
	}
	
	
	
	
	public function activeorders(){
		
		
		if(isset($this->request->data['report_date_start']) && isset($this->request->data['report_date_end'])){
			$this->autoRender=false;
			
			$startTS=strtotime($this->request->data['report_date_start'].' 00:00:00');
			$endTS=strtotime($this->request->data['report_date_end'].' 23:59:59');

            $activeOrders=$this->Orders->find('all',['conditions' => ['status NOT IN' => ['Canceled','Shipped','Needs Line Items'], 'created >=' => $startTS, 'created <=' => $endTS]])->toArray();
            
            require($_SERVER['DOCUMENT_ROOT']."/vendor/phpoffice/phpexcel/Classes/PHPExcel.php");
            require($_SERVER['DOCUMENT_ROOT']."/vendor/phpoffice/phpexcel/Classes/PHPExcel/Writer/Excel2007.php");
            
    	    $objPHPExcel = new \PHPExcel();
            $objPHPExcel->setActiveSheetIndex(0);
			
			
			$objPHPExcel->getActiveSheet()->SetCellValue('A1', 'ORDER NUMBER');
			$objPHPExcel->getActiveSheet()->SetCellValue('B1', 'CUSTOMER');
			$objPHPExcel->getActiveSheet()->SetCellValue('C1', 'PO DATE');
			$objPHPExcel->getActiveSheet()->SetCellValue('D1', 'QUOTE TITLE/PROJECT');
            $objPHPExcel->getActiveSheet()->SetCellValue('E1', 'RECIPIENT');
            $objPHPExcel->getActiveSheet()->SetCellValue('F1', 'TYPE');
            $objPHPExcel->getActiveSheet()->SetCellValue('G1', 'TOTALS');
            $objPHPExcel->getActiveSheet()->SetCellValue('H1', 'CC LF');
            $objPHPExcel->getActiveSheet()->SetCellValue('I1', 'TRK LF');
            $objPHPExcel->getActiveSheet()->SetCellValue('J1', 'BS QTY');
            $objPHPExcel->getActiveSheet()->SetCellValue('K1', 'DRAPERIES WIDTHS');
            //$objPHPExcel->getActiveSheet()->SetCellValue('L1', 'TOP TREATMENTS QTY');
            //$objPHPExcel->getActiveSheet()->SetCellValue('M1', 'TOP TREATMENT LF');
            $objPHPExcel->getActiveSheet()->SetCellValue('L1', 'VALANCE QTY');
            $objPHPExcel->getActiveSheet()->SetCellValue('M1', 'VALANCE LF');
            $objPHPExcel->getActiveSheet()->SetCellValue('N1', 'CORNICE QTY');
            $objPHPExcel->getActiveSheet()->SetCellValue('O1', 'CORNICE LF');
            $objPHPExcel->getActiveSheet()->SetCellValue('P1', 'WT HW QTY');
            $objPHPExcel->getActiveSheet()->SetCellValue('Q1', 'B&S QTY');

			
			$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(16);
			$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
			$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(24);
			$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(42);
			$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(42);
			$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(14);
			$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(30);
			$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(12);
			$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(12);
			$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(12);
			$objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(18);
			$objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(22);
			$objPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth(22);
			$objPHPExcel->getActiveSheet()->getColumnDimension('N')->setWidth(22);
			$objPHPExcel->getActiveSheet()->getColumnDimension('O')->setWidth(22);
			$objPHPExcel->getActiveSheet()->getColumnDimension('P')->setWidth(12);
			$objPHPExcel->getActiveSheet()->getColumnDimension('Q')->setWidth(12);

			
			
			$objPHPExcel->getActiveSheet()->getRowDimension('1')->setRowHeight(18);
			$objPHPExcel->getActiveSheet()->getStyle('A1:Q1')->getAlignment()->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);
			
			
			$objPHPExcel->getActiveSheet()->getStyle('A1:Q1')->applyFromArray(
				array(
					'fill' => array(
						'type' => \PHPExcel_Style_Fill::FILL_SOLID,
						'color' => array('rgb' => 'F8CBAD')
					)
				)
			);
			
			
            $rowCount = 2;
			
			
			foreach($activeOrders as $order){
			    
			    if(!is_null($order['type_id']) && $order['type_id'] > 0){
				    $thisType=$this->QuoteTypes->get($order['type_id'])->toArray();
				    $thisOrderType=$thisType['type_label'];
				}else{
				    $thisOrderType='';
				}
				
				
				
    			$totals = floatval($order['order_total']);
    
    			
    
    			if($order['due'] > 1000){
    				$duedate=date('n/j/Y',$order['due']);
    			}else{
    				$duedate='---';
    			}
    
    
    			
    
    			
    			if($order['shipping_method_id'] > 0){
    				$thisShipMethodData=$this->ShippingMethods->get($order['shipping_method_id'])->toArray();
    				$thisShipMethod=$thisShipMethodData['name'];
    			}else{
    				$thisShipMethod='---';
    			}
				
				
			    $customerData=$this->Customers->get($order['customer_id'])->toArray();
    			$userData=$this->Users->get($order['user_id'])->toArray();
    			$quoteData=$this->Quotes->get($order['quote_id'])->toArray();
    
    
                $projectname='';
    			if($quoteData['project_id'] > 0){
    				$projectData=$this->Projects->get($quoteData['project_id'])->toArray();
    				$projectname=$quoteData['title'].'<br><span style="font-size:10px; color:blue;"><b>Project: '.$projectData['title'].'</b></span>';
    			}else{
    				$projectname=$quoteData['title'];
    			}
    
    			$orderTitle='';
    			if(strlen(trim($quote['title'])) >0){
    				$orderTitle .= $quote['title']."\n";
    			}
    			$orderTitle .= $customerData['company_name'];
    			
    			
    			$numitems=0;
    			$quoteitems=$this->QuoteLineItems->find('all',['conditions'=>['quote_id'=>$order['quote_id']]])->toArray();
			    foreach($quoteitems as $quoteitem){
    				$numitems=($numitems+$quoteitem['qty']);
    			}
    			
    			
    			
    			
    			$orderstatus=ucfirst($order['status']);
			    if($order['status'] == "Canceled"){
				    $orderstatus .= "\nREASON:\n".$order['cancelreason'];
			    }

			    if($order['due'] > time() && $order['due'] <= (time()+432000)){
    				$orderstatus .= " (DUE SOON)";
    			}

	    		if($order['due'] < time() && $order['due'] > 1000){
    				$orderstatus .= " (PAST DUE)";
        		}
                
                $completedpercent=$this->getOrderProgressPercent($order['id']);
    			$orderstatus .= "\n".$completedpercent['completed']." / ".$completedpercent['total']." (".$completedpercent['percent']."%) Completed";
    			
    			
			
			    
			    $objPHPExcel->getActiveSheet()->SetCellValue('A'.$rowCount, $order['order_number']);
			    $objPHPExcel->getActiveSheet()->SetCellValue('B'.$rowCount, $orderTitle);
			    $objPHPExcel->getActiveSheet()->SetCellValue('C'.$rowCount, date('n/j/Y',$order['created']));
			    $objPHPExcel->getActiveSheet()->SetCellValue('D'.$rowCount, $projectname);
			    $objPHPExcel->getActiveSheet()->SetCellValue('E'.$rowCount, $order['facility']);
			    $objPHPExcel->getActiveSheet()->SetCellValue('F'.$rowCount, $thisOrderType);
			    $objPHPExcel->getActiveSheet()->SetCellValue('G'.$rowCount, $totals);
			    $objPHPExcel->getActiveSheet()->SetCellValue('H'.$rowCount, $order['cc_lf']);
			    $objPHPExcel->getActiveSheet()->SetCellValue('I'.$rowCount, $order['track_lf']);
			    $objPHPExcel->getActiveSheet()->SetCellValue('J'.$rowCount, $order['bs_qty']);
			    $objPHPExcel->getActiveSheet()->SetCellValue('K'.$rowCount, $order['drape_widths']);
			    //$objPHPExcel->getActiveSheet()->SetCellValue('L'.$rowCount, $order['tt_qty']);
			    //$objPHPExcel->getActiveSheet()->SetCellValue('M'.$rowCount, $order['tt_lf']);
			    $objPHPExcel->getActiveSheet()->SetCellValue('L'.$rowCount, $order['val_qty']);
			    $objPHPExcel->getActiveSheet()->SetCellValue('M'.$rowCount, $order['val_lf']);
			    $objPHPExcel->getActiveSheet()->SetCellValue('N'.$rowCount, $order['corn_qty']);
			    $objPHPExcel->getActiveSheet()->SetCellValue('O'.$rowCount, $order['corn_lf']);
			    $objPHPExcel->getActiveSheet()->SetCellValue('P'.$rowCount, $order['wt_hw_qty']);
			    $objPHPExcel->getActiveSheet()->SetCellValue('Q'.$rowCount, $order['blinds_qty']);
			    
			    
			    
			    
			    $rowCount++;
			}
          
            $objPHPExcel->getActiveSheet()->getRowDimension(($rowCount-1))->setRowHeight(-1);
            $objPHPExcel->getActiveSheet()->getStyle('A2:Q'.($rowCount-1))->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
            $objPHPExcel->getActiveSheet()->getStyle('A2:Q'.($rowCount-1))->getAlignment()->setVertical(\PHPExcel_Style_Alignment::VERTICAL_TOP );
            $objPHPExcel->getActiveSheet()->getStyle('A2:Q'.($rowCount-1))->getAlignment()->setWrapText(true);
            
            // Redirect output to a client’s web browser (Excel5)
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="Active Orders Report - '.$this->request->data['report_date_start'].' thru '.$this->request->data['report_date_end'].'.xlsx"');
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
	
	
	
	
	
	public function consolidated(){
		
		
		if(isset($this->request->data['report_date_start']) && isset($this->request->data['report_date_end'])){
			$this->autoRender=false;
			
			$startTS=strtotime($this->request->data['report_date_start'].' 00:00:00');
			$endTS=strtotime($this->request->data['report_date_end'].' 23:59:59');
            /** PPSASCRUM-99 start*/
            // $activeOrders=$this->Orders->find('all',['conditions' => ['status NOT IN' => ['Canceled','Needs Line Items'], 'created >=' => $startTS, 'created <=' => $endTS, 'order_total >' => 0.00]])->toArray();
            $activeOrders=$this->Orders->find('all',['conditions' => ['status NOT IN' => ['Canceled','Needs Line Items'], 'created >=' => $startTS, 'created <=' => $endTS, 'order_total >' => 0.00 , 'type_id not in' =>array(3,5)]])->toArray();
           
    	    /** PPSASCRUM-99 end*/
            
            require($_SERVER['DOCUMENT_ROOT']."/vendor/phpoffice/phpexcel/Classes/PHPExcel.php");
            require($_SERVER['DOCUMENT_ROOT']."/vendor/phpoffice/phpexcel/Classes/PHPExcel/Writer/Excel2007.php");
            
    	    $objPHPExcel = new \PHPExcel();
            $objPHPExcel->setActiveSheetIndex(0);
			
			
			$objPHPExcel->getActiveSheet()->SetCellValue('A1', 'ORDER NUMBER');
			$objPHPExcel->getActiveSheet()->SetCellValue('B1', 'CUSTOMER');
			$objPHPExcel->getActiveSheet()->SetCellValue('C1', 'PO DATE');
			$objPHPExcel->getActiveSheet()->SetCellValue('D1', 'QUOTE TITLE/PROJECT');
            $objPHPExcel->getActiveSheet()->SetCellValue('E1', 'RECIPIENT');
            $objPHPExcel->getActiveSheet()->SetCellValue('F1', 'TYPE');
            $objPHPExcel->getActiveSheet()->SetCellValue('G1', 'TOTALS');
            $objPHPExcel->getActiveSheet()->SetCellValue('H1', 'CC LF');
            $objPHPExcel->getActiveSheet()->SetCellValue('I1', 'TRK LF');
            $objPHPExcel->getActiveSheet()->SetCellValue('J1', 'BS QTY');
            $objPHPExcel->getActiveSheet()->SetCellValue('K1', 'DRAPERIES WIDTHS');
            //$objPHPExcel->getActiveSheet()->SetCellValue('L1', 'TOP TREATMENTS QTY');
            //$objPHPExcel->getActiveSheet()->SetCellValue('M1', 'TOP TREATMENT LF');
            $objPHPExcel->getActiveSheet()->SetCellValue('L1', 'VALANCE QTY');
            $objPHPExcel->getActiveSheet()->SetCellValue('M1', 'VALANCE LF');
            $objPHPExcel->getActiveSheet()->SetCellValue('N1', 'CORNICE QTY');
            $objPHPExcel->getActiveSheet()->SetCellValue('O1', 'CORNICE LF');
            $objPHPExcel->getActiveSheet()->SetCellValue('P1', 'WT HW QTY');
            $objPHPExcel->getActiveSheet()->SetCellValue('Q1', 'B&S QTY');

			
			$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(16);
			$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
			$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(24);
			$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(42);
			$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(42);
			$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(14);
			$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(30);
			$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(12);
			$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(12);
			$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(12);
			$objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(18);
			$objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(22);
			$objPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth(22);
			$objPHPExcel->getActiveSheet()->getColumnDimension('N')->setWidth(22);
			$objPHPExcel->getActiveSheet()->getColumnDimension('O')->setWidth(22);
			$objPHPExcel->getActiveSheet()->getColumnDimension('P')->setWidth(12);
			$objPHPExcel->getActiveSheet()->getColumnDimension('Q')->setWidth(12);
            
			
			
			$objPHPExcel->getActiveSheet()->getRowDimension('1')->setRowHeight(18);
			$objPHPExcel->getActiveSheet()->getStyle('A1:Q1')->getAlignment()->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);
			
			
			$objPHPExcel->getActiveSheet()->getStyle('A1:Q1')->applyFromArray(
				array(
					'fill' => array(
						'type' => \PHPExcel_Style_Fill::FILL_SOLID,
						'color' => array('rgb' => 'F8CBAD')
					)
				)
			);
			
			
            $rowCount = 2;
			
			
			foreach($activeOrders as $order){
			    //$thisOrderType = '';
			    if(!is_null($order['type_id']) && $order['type_id'] > 0){
				    $thisType=$this->QuoteTypes->get($order['type_id'])->toArray();
				    $thisOrderType=$thisType['type_label'];
				}else{
				    $thisOrderType='';
				}
				
				
				
    			$totals = floatval($order['order_total']);
    
    			
    
    			if($order['due'] > 1000){
    				$duedate=date('n/j/Y',$order['due']);
    			}else{
    				$duedate='---';
    			}
    
    
    
    			
    			if($order['shipping_method_id'] > 0){
    				$thisShipMethodData=$this->ShippingMethods->get($order['shipping_method_id'])->toArray();
    				$thisShipMethod=$thisShipMethodData['name'];
    			}else{
    				$thisShipMethod='---';
    			}
				
				
			    $customerData=$this->Customers->get($order['customer_id'])->toArray();
    			$userData=$this->Users->get($order['user_id'])->toArray();
    			$quoteData=$this->Quotes->get($order['quote_id'])->toArray();
    
                
                
    			$projectname='';
    			if($quoteData['project_id'] > 0){
    				$projectData=$this->Projects->get($quoteData['project_id'])->toArray();
    				$projectname=$quoteData['title'].'<br><span style="font-size:10px; color:blue;"><b>Project: '.$projectData['title'].'</b></span>';
    			}else{
    				$projectname=$quoteData['title'];
    			}
    
    			$orderTitle='';
    			if(strlen(trim($quote['title'])) >0){
    				$orderTitle .= $quote['title']."\n";
    			}
    			$orderTitle .= $customerData['company_name'];
    			
    			
    			$numitems=0;
    			$quoteitems=$this->QuoteLineItems->find('all',['conditions'=>['quote_id'=>$order['quote_id']]])->toArray();
			    foreach($quoteitems as $quoteitem){
    				$numitems=($numitems+$quoteitem['qty']);
    			}
    			
    			
    			
    			
    			$orderstatus=ucfirst($order['status']);
			    if($order['status'] == "Canceled"){
				    $orderstatus .= "\nREASON:\n".$order['cancelreason'];
			    }

			    if($order['due'] > time() && $order['due'] <= (time()+432000)){
    				$orderstatus .= " (DUE SOON)";
    			}

	    		if($order['due'] < time() && $order['due'] > 1000){
    				$orderstatus .= " (PAST DUE)";
        		}
                
                $completedpercent=$this->getOrderProgressPercent($order['id']);
    			$orderstatus .= "\n".$completedpercent['completed']." / ".$completedpercent['total']." (".$completedpercent['percent']."%) Completed";
    			
    			
			
			    
			    $objPHPExcel->getActiveSheet()->SetCellValue('A'.$rowCount, $order['order_number']);
			    $objPHPExcel->getActiveSheet()->SetCellValue('B'.$rowCount, $orderTitle);
			    $objPHPExcel->getActiveSheet()->SetCellValue('C'.$rowCount, date('n/j/Y',$order['created']));
			    $objPHPExcel->getActiveSheet()->SetCellValue('D'.$rowCount, $projectname);
			    $objPHPExcel->getActiveSheet()->SetCellValue('E'.$rowCount, $order['facility']);
			    $objPHPExcel->getActiveSheet()->SetCellValue('F'.$rowCount, $thisOrderType);
			    $objPHPExcel->getActiveSheet()->SetCellValue('G'.$rowCount, $totals);
			    $objPHPExcel->getActiveSheet()->SetCellValue('H'.$rowCount, $order['cc_lf']);
			    $objPHPExcel->getActiveSheet()->SetCellValue('I'.$rowCount, $order['track_lf']);
			    $objPHPExcel->getActiveSheet()->SetCellValue('J'.$rowCount, $order['bs_qty']);
			    $objPHPExcel->getActiveSheet()->SetCellValue('K'.$rowCount, $order['drape_widths']);
			    $objPHPExcel->getActiveSheet()->SetCellValue('L'.$rowCount, $order['val_qty']);
			    $objPHPExcel->getActiveSheet()->SetCellValue('M'.$rowCount, $order['val_lf']);
			    $objPHPExcel->getActiveSheet()->SetCellValue('N'.$rowCount, $order['corn_qty']);
			    $objPHPExcel->getActiveSheet()->SetCellValue('O'.$rowCount, $order['corn_lf']);
			    $objPHPExcel->getActiveSheet()->SetCellValue('P'.$rowCount, $order['wt_hw_qty']);
			    $objPHPExcel->getActiveSheet()->SetCellValue('Q'.$rowCount, $order['blinds_qty']);
			    
			    
			    
			    
			    $rowCount++;
			}
          
            $objPHPExcel->getActiveSheet()->getRowDimension(($rowCount-1))->setRowHeight(-1);
            $objPHPExcel->getActiveSheet()->getStyle('A2:Q'.($rowCount-1))->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
            $objPHPExcel->getActiveSheet()->getStyle('A2:Q'.($rowCount-1))->getAlignment()->setVertical(\PHPExcel_Style_Alignment::VERTICAL_TOP );
            $objPHPExcel->getActiveSheet()->getStyle('A2:Q'.($rowCount-1))->getAlignment()->setWrapText(true);
            
            // Redirect output to a client’s web browser (Excel5)
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="Consolidated Bookings Report - '.$this->request->data['report_date_start'].' thru '.$this->request->data['report_date_end'].'.xlsx"');
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
	
	
	public function bigquotes(){
	    
	    if($this->request->data){
	        $this->autoRender=false;
	        
	        $startTS=strtotime($this->request->data['report_date_start'].' 00:00:00');
			$endTS=strtotime($this->request->data['report_date_end'].' 23:59:59');
			
	        $amount=$this->getSettingValue('bq_qualifying_amount');
	        
	        $db=ConnectionManager::get('default');
		
    	    $query = "SELECT a.*, b.company_name AS customername FROM `quotes` a, `customers` b WHERE b.id=a.customer_id AND a.status != 'editorder' AND a.created >= ".$startTS." AND a.created <= ".$endTS." AND a.revision IS NULL AND a.parent_quote=0 AND a.quote_total >= ".$amount;
    	    if($this->request->data['filterbyuser'] != 'allusers'){
    	        $query .= " AND a.created_by = ".$this->request->data['filterbyuser'];
    	    }
    	    $query .= " ORDER BY customername ASC";
	    
	        $queryRun=$db->execute($query);
		    $quotesLookup=$queryRun->fetchAll('assoc');
	        
	        
	        require($_SERVER['DOCUMENT_ROOT']."/vendor/phpoffice/phpexcel/Classes/PHPExcel.php");
            require($_SERVER['DOCUMENT_ROOT']."/vendor/phpoffice/phpexcel/Classes/PHPExcel/Writer/Excel2007.php");
            
    	    $objPHPExcel = new \PHPExcel();
            $objPHPExcel->setActiveSheetIndex(0);
			
			
			$objPHPExcel->getActiveSheet()->SetCellValue('A1', 'QUOTE NUMBER');
			$objPHPExcel->getActiveSheet()->SetCellValue('B1', 'CUSTOMER');
			$objPHPExcel->getActiveSheet()->SetCellValue('C1', 'CREATED BY');
			$objPHPExcel->getActiveSheet()->SetCellValue('D1', 'CREATED DATE');
			$objPHPExcel->getActiveSheet()->SetCellValue('E1', 'QUOTE TITLE/PROJECT');
            $objPHPExcel->getActiveSheet()->SetCellValue('F1', 'TYPE');
            $objPHPExcel->getActiveSheet()->SetCellValue('G1', 'TOTALS');
            $objPHPExcel->getActiveSheet()->SetCellValue('H1', 'CC LF');
            $objPHPExcel->getActiveSheet()->SetCellValue('I1', 'TRK LF');
            $objPHPExcel->getActiveSheet()->SetCellValue('J1', 'BS QTY');
            $objPHPExcel->getActiveSheet()->SetCellValue('K1', 'DRAPERIES WIDTHS');
            $objPHPExcel->getActiveSheet()->SetCellValue('L1', 'TOP TREATMENTS QTY');
            $objPHPExcel->getActiveSheet()->SetCellValue('M1', 'TOP TREATMENT LF');
            $objPHPExcel->getActiveSheet()->SetCellValue('N1', 'WT HW QTY');
            $objPHPExcel->getActiveSheet()->SetCellValue('O1', 'B&S QTY');

			
			$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(16);
			$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
			$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(24);
			
			$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(16);
			
			$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(42);
			$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(14);
			$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(30);
			$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(12);
			$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(12);
			$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(12);
			$objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(18);
			$objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(22);
			$objPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth(22);
			$objPHPExcel->getActiveSheet()->getColumnDimension('N')->setWidth(12);
			$objPHPExcel->getActiveSheet()->getColumnDimension('O')->setWidth(12);
            
			
			
			$objPHPExcel->getActiveSheet()->getRowDimension('1')->setRowHeight(18);
			$objPHPExcel->getActiveSheet()->getStyle('A1:O1')->getAlignment()->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);
			
			
			$objPHPExcel->getActiveSheet()->getStyle('A1:O1')->applyFromArray(
				array(
					'fill' => array(
						'type' => \PHPExcel_Style_Fill::FILL_SOLID,
						'color' => array('rgb' => 'F8CBAD')
					)
				)
			);
			
			
            $rowCount = 2;
			
			$outputArr=array();
			
			foreach($quotesLookup as $quote){
			    
			    if(!is_null($quote['type_id']) && $quote['type_id'] > 0){
				    $thisType=$this->QuoteTypes->get($quote['type_id'])->toArray();
				    $thisQuoteType=$thisType['type_label'];
				}else{
				    $thisQuoteType='';
				}
				
				
    			$totals = number_format(floatval($quote['quote_total']),2,'.','');
    			
				
			    $customerData=$this->Customers->get($quote['customer_id'])->toArray();
    			$userData=$this->Users->get($quote['created_by'])->toArray();
                
    			$projectname='';
    			if($quote['project_id'] > 0){
    				$projectData=$this->Projects->get($quote['project_id'])->toArray();
    				$projectname=$quote['title'].' / '.$projectData['title'];
    			}else{
    				$projectname=$quote['title'];
    			}
    			
    			
    			
    			$numitems=0;
    			$quoteitems=$this->QuoteLineItems->find('all',['conditions'=>['quote_id'=>$quote['id']]])->toArray();
			    foreach($quoteitems as $quoteitem){
    				$numitems=($numitems+$quoteitem['qty']);
    			}
			
			    
			    $cclf=$this->getTypeLF($quote['id'],'cc');
			    $tracklf=$this->getTypeLF($quote['id'],'track');
			    $bsqty=$this->getTypeQty($quote['id'],'bs');
			    $drapewidths=$this->getTypeWidths($quote['id'],'drapes');
			    $ttqty=$this->getTypeQty($quote['id'],'tt');
			    $ttlf=$this->getTypeLF($quote['id'],'tt');
			    $wthwqty=$this->getTypeQty($quote['id'],'wthw');
			    $blindsqty=$this->getTypeQty($quote['id'],'blinds');
			    
			    
			    $objPHPExcel->getActiveSheet()->SetCellValue('A'.$rowCount, $quote['quote_number']);
			    $objPHPExcel->getActiveSheet()->SetCellValue('B'.$rowCount, $customerData['company_name']);
			    $objPHPExcel->getActiveSheet()->SetCellValue('C'.$rowCount, $userData['first_name'].' '.$userData['last_name']);
			    
			    $objPHPExcel->getActiveSheet()->SetCellValue('D'.$rowCount, date('m/d/Y',$quote['created']));
			    
			    
			    $objPHPExcel->getActiveSheet()->SetCellValue('E'.$rowCount, $projectname);
			    $objPHPExcel->getActiveSheet()->SetCellValue('F'.$rowCount, $thisQuoteType);
			    $objPHPExcel->getActiveSheet()->SetCellValue('G'.$rowCount, $totals);
			    $objPHPExcel->getActiveSheet()->SetCellValue('H'.$rowCount, $cclf);
			    $objPHPExcel->getActiveSheet()->SetCellValue('I'.$rowCount, $tracklf);
			    $objPHPExcel->getActiveSheet()->SetCellValue('J'.$rowCount, $bsqty);
			    $objPHPExcel->getActiveSheet()->SetCellValue('K'.$rowCount, $drapewidths);
			    $objPHPExcel->getActiveSheet()->SetCellValue('L'.$rowCount, $ttqty);
			    $objPHPExcel->getActiveSheet()->SetCellValue('M'.$rowCount, $ttlf);
			    $objPHPExcel->getActiveSheet()->SetCellValue('N'.$rowCount, $wthwqty);
			    $objPHPExcel->getActiveSheet()->SetCellValue('O'.$rowCount, $blindsqty);
			    
			    $rowCount++;
			}
          
            
            //echo "<pre>"; print_r($outputArr); echo "</pre>";exit;
          
            $objPHPExcel->getActiveSheet()->getRowDimension(($rowCount-1))->setRowHeight(-1);
            $objPHPExcel->getActiveSheet()->getStyle('A2:O'.($rowCount-1))->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
            $objPHPExcel->getActiveSheet()->getStyle('A2:O'.($rowCount-1))->getAlignment()->setVertical(\PHPExcel_Style_Alignment::VERTICAL_TOP );
            $objPHPExcel->getActiveSheet()->getStyle('A2:O'.($rowCount-1))->getAlignment()->setWrapText(true);
            
            // Redirect output to a client’s web browser (Excel5)
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="Big Quotes Report - '.$this->request->data['report_date_start'].' thru '.$this->request->data['report_date_end'].'.xlsx"');
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
            
	        
	        
	    }else{
	        //show the form
	        //get all the users for filtering by user
	        $allUsers=$this->Users->find('all',['conditions' => ['status'=>'Active'],'order'=>['first_name'=>'asc','last_name'=>'asc']])->toArray();
	        $this->set('allUsers',$allUsers);
	        
	    }
	    
	}
	
	
public function consolidatedquotes(){
	    
	    if($this->request->data){
	        $this->autoRender=false;
	        
	        $startTS=strtotime($this->request->data['report_date_start'].' 00:00:00');
			$endTS=strtotime($this->request->data['report_date_end'].' 23:59:59');
			
	        
	        $db=ConnectionManager::get('default');
		
    	    $query = "SELECT * FROM `quotes` WHERE `status` = 'published' AND `publish_date` >= ".$startTS." AND `publish_date` <= ".$endTS."  AND `parent_quote`=0";
    	    if($this->request->data['filterbyuser'] != 'allusers'){
    	        $query .= " AND `created_by` = ".$this->request->data['filterbyuser'];
    	    }
    	    
    	    if(strlen($this->request->data['customer_id']) > 0){
    	        $query .= " AND `customer_id`=".$this->request->data['customer_id'];
    	    }
    	    
    	    if(strlen($this->request->data['quote_type']) > 0){
    	        $query .= " AND `type_id` = ".$this->request->data['quote_type'];
    	    }
    	    
    	    if($this->request->data['dollarfilter'] == 'yes'){
    	        $query .= " AND `quote_total` >= ".$this->request->data['dollar_min'];
    	    }
    	    
    	    $query .= " ORDER BY `publish_date` DESC";
	    
	        $queryRun=$db->execute($query);
		    $quotesLookup=$queryRun->fetchAll('assoc');
	        
	        
	        require($_SERVER['DOCUMENT_ROOT']."/vendor/phpoffice/phpexcel/Classes/PHPExcel.php");
            require($_SERVER['DOCUMENT_ROOT']."/vendor/phpoffice/phpexcel/Classes/PHPExcel/Writer/Excel2007.php");
            
    	    $objPHPExcel = new \PHPExcel();
            $objPHPExcel->setActiveSheetIndex(0);
			
			
			$objPHPExcel->getActiveSheet()->SetCellValue('A1', 'QUOTE NUMBER');
			$objPHPExcel->getActiveSheet()->SetCellValue('B1', 'CUSTOMER');
			$objPHPExcel->getActiveSheet()->SetCellValue('C1', 'CREATED BY');
			$objPHPExcel->getActiveSheet()->SetCellValue('D1', 'PUBLISHED DATE');
			$objPHPExcel->getActiveSheet()->SetCellValue('E1', 'QUOTE TITLE/PROJECT');
            $objPHPExcel->getActiveSheet()->SetCellValue('F1', 'TYPE');
            $objPHPExcel->getActiveSheet()->SetCellValue('G1', 'CONVERTED TO WO#');
            $objPHPExcel->getActiveSheet()->SetCellValue('H1', 'TOTALS');
            $objPHPExcel->getActiveSheet()->SetCellValue('I1', 'CC LF');
            $objPHPExcel->getActiveSheet()->SetCellValue('J1', 'TRK LF');
            $objPHPExcel->getActiveSheet()->SetCellValue('K1', 'BS QTY');
            $objPHPExcel->getActiveSheet()->SetCellValue('L1', 'DRAPERIES WIDTHS');
            $objPHPExcel->getActiveSheet()->SetCellValue('M1', 'TOP TREATMENTS QTY');
            $objPHPExcel->getActiveSheet()->SetCellValue('N1', 'TOP TREATMENT LF');
            $objPHPExcel->getActiveSheet()->SetCellValue('O1', 'WT HW QTY');
            $objPHPExcel->getActiveSheet()->SetCellValue('P1', 'B&S QTY');

			
			$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(16);
			$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
			$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(24);
			
			$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(16);
			
			$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(42);
			$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(14);
			$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(16);
			$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(30);
			$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(12);
			$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(12);
			$objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(12);
			$objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(18);
			$objPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth(22);
			$objPHPExcel->getActiveSheet()->getColumnDimension('N')->setWidth(22);
			$objPHPExcel->getActiveSheet()->getColumnDimension('O')->setWidth(12);
			$objPHPExcel->getActiveSheet()->getColumnDimension('P')->setWidth(12);
            
			
			
			$objPHPExcel->getActiveSheet()->getRowDimension('1')->setRowHeight(18);
			$objPHPExcel->getActiveSheet()->getStyle('A1:P1')->getAlignment()->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);
			
			
			$objPHPExcel->getActiveSheet()->getStyle('A1:P1')->applyFromArray(
				array(
					'fill' => array(
						'type' => \PHPExcel_Style_Fill::FILL_SOLID,
						'color' => array('rgb' => 'F8CBAD')
					)
				)
			);
			
			
            $rowCount = 2;
			
			$outputArr=array();
			
			foreach($quotesLookup as $quote){
			    
			    if(!is_null($quote['type_id']) && $quote['type_id'] > 0){
				    $thisType=$this->QuoteTypes->get($quote['type_id'])->toArray();
				    $thisQuoteType=$thisType['type_label'];
				}else{
				    $thisQuoteType='';
				}
				
				
    			$totals = number_format(floatval($quote['quote_total']),2,'.','');
    			
				
			    $customerData=$this->Customers->get($quote['customer_id'])->toArray();
    			$userData=$this->Users->get($quote['created_by'])->toArray();
                
    			$projectname='';
    			if($quote['project_id'] > 0){
    				$projectData=$this->Projects->get($quote['project_id'])->toArray();
    				$projectname=$quote['title'].' / '.$projectData['title'];
    			}else{
    				$projectname=$quote['title'];
    			}
    			
    			
    			
    			$numitems=0;
    			$quoteitems=$this->QuoteLineItems->find('all',['conditions'=>['quote_id'=>$quote['id']]])->toArray();
			    foreach($quoteitems as $quoteitem){
    				$numitems=($numitems+$quoteitem['qty']);
    			}
			
			    
			    $cclf=$this->getTypeLF($quote['id'],'cc');
			    $tracklf=$this->getTypeLF($quote['id'],'track');
			    $bsqty=$this->getTypeQty($quote['id'],'bs');
			    $drapewidths=$this->getTypeWidths($quote['id'],'drapes');
			    $ttqty=$this->getTypeQty($quote['id'],'tt');
			    $ttlf=$this->getTypeLF($quote['id'],'tt');
			    $wthwqty=$this->getTypeQty($quote['id'],'wthw');
			    $blindsqty=$this->getTypeQty($quote['id'],'blinds');
			    
			    
			    if($quote['order_number'] > 0){
			        $ordernumber=$quote['order_number'];
			    }else{
			        $ordernumber='';
			    }
			    
			    
			    $objPHPExcel->getActiveSheet()->SetCellValue('A'.$rowCount, $quote['quote_number']);
			    $objPHPExcel->getActiveSheet()->SetCellValue('B'.$rowCount, $customerData['company_name']);
			    $objPHPExcel->getActiveSheet()->SetCellValue('C'.$rowCount, $userData['first_name'].' '.$userData['last_name']);
			    
			    //$objPHPExcel->getActiveSheet()->SetCellValue('D'.$rowCount, date('m/d/Y',$quote['publish_date']));
			    $publishedDateTimeObj=new \DateTime();
    			$publishedDateTimeObj->setTimestamp($quote['publish_date']);
    			$thispublisheddate=\PHPExcel_Shared_Date::PHPToExcel($publishedDateTimeObj);
			    $objPHPExcel->getActiveSheet()->SetCellValue('D'.$rowCount,$thispublisheddate);
			    $objPHPExcel->getActiveSheet()->getStyle('D'.$rowCount)->getNumberFormat()->setFormatCode('mm/dd/yyyy');

			    
			    $objPHPExcel->getActiveSheet()->SetCellValue('E'.$rowCount, $projectname);
			    $objPHPExcel->getActiveSheet()->SetCellValue('F'.$rowCount, $thisQuoteType);
			    $objPHPExcel->getActiveSheet()->SetCellValue('G'.$rowCount, $ordernumber);
			    $objPHPExcel->getActiveSheet()->SetCellValue('H'.$rowCount, $totals);
			    $objPHPExcel->getActiveSheet()->SetCellValue('I'.$rowCount, $cclf);
			    $objPHPExcel->getActiveSheet()->SetCellValue('J'.$rowCount, $tracklf);
			    $objPHPExcel->getActiveSheet()->SetCellValue('K'.$rowCount, $bsqty);
			    $objPHPExcel->getActiveSheet()->SetCellValue('L'.$rowCount, $drapewidths);
			    $objPHPExcel->getActiveSheet()->SetCellValue('M'.$rowCount, $ttqty);
			    $objPHPExcel->getActiveSheet()->SetCellValue('N'.$rowCount, $ttlf);
			    $objPHPExcel->getActiveSheet()->SetCellValue('O'.$rowCount, $wthwqty);
			    $objPHPExcel->getActiveSheet()->SetCellValue('P'.$rowCount, $blindsqty);
			    
			    $rowCount++;
			}
          
            
            //echo "<pre>"; print_r($outputArr); echo "</pre>";exit;
          
            $objPHPExcel->getActiveSheet()->getRowDimension(($rowCount-1))->setRowHeight(-1);
            $objPHPExcel->getActiveSheet()->getStyle('A2:P'.($rowCount-1))->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
            $objPHPExcel->getActiveSheet()->getStyle('A2:P'.($rowCount-1))->getAlignment()->setVertical(\PHPExcel_Style_Alignment::VERTICAL_TOP );
            $objPHPExcel->getActiveSheet()->getStyle('A2:P'.($rowCount-1))->getAlignment()->setWrapText(true);
            
            // Redirect output to a client’s web browser (Excel5)
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="Consolidated Quotes Report - '.$this->request->data['report_date_start'].' thru '.$this->request->data['report_date_end'].'.xlsx"');
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
            
	        
	        
	    }else{
	        //show the form
	        //get all the users for filtering by user
	        $allUsers=$this->Users->find('all',['conditions' => ['status'=>'Active'],'order'=>['first_name'=>'asc','last_name'=>'asc']])->toArray();
	        $this->set('allUsers',$allUsers);
	        
	        $allCustomers=$this->Customers->find('all',['order'=>['company_name'=>'asc']])->toArray();
	        $this->set('allCustomers',$allCustomers);
	        
	        $quoteTypes=$this->QuoteTypes->find('all',['order'=>['type_label'=>'asc']])->toArray();
	        $this->set('quoteTypes',$quoteTypes);
	        
	        $this->set('bigquotestart',$this->getSettingValue('bq_qualifying_amount'));
	    }
	    
	}
	
	
	
	
	
	public function completedorders(){
	    
	    if($this->request->data){
	        $this->autoRender=false;
	        
	        $productMap=array(
        		'cubicle-curtain'=>'Cubicle Curtain',
    			'box-pleated'=>'Box Pleated Valance',
    			'straight-cornice'=>'Straight Cornice',
    			'bedspread'=>'Calculated Bedspread',
    			'bedspread-manual'=>'Manually Entered Bedspread',
    			'pinch-pleated' => 'Pinch Pleated Drapery'
    		);
		
	        $startTS=strtotime($this->request->data['report_date_start'].' 00:00:00');
			$endTS=strtotime($this->request->data['report_date_end'].' 23:59:59');
			
	        
	        $db=ConnectionManager::get('default');
		
    	    $query = "SELECT * FROM `orders` WHERE `status` = 'Shipped' AND `created` >= ".$startTS." AND `created` <= ".$endTS;

    	    if(strlen($this->request->data['customer_id']) > 0){
    	        $query .= " AND `customer_id`=".$this->request->data['customer_id'];
    	    }
    	    
    	    if(strlen($this->request->data['order_type']) > 0){
    	        $query .= " AND `type_id` = ".$this->request->data['order_type'];
    	    }
    	    
    	    $query .= " ORDER BY `created` ASC";
	    
	        $queryRun=$db->execute($query);
		    $ordersLookup=$queryRun->fetchAll('assoc');
	        
	        
	        require($_SERVER['DOCUMENT_ROOT']."/vendor/phpoffice/phpexcel/Classes/PHPExcel.php");
            require($_SERVER['DOCUMENT_ROOT']."/vendor/phpoffice/phpexcel/Classes/PHPExcel/Writer/Excel2007.php");
            
    	    $objPHPExcel = new \PHPExcel();
            $objPHPExcel->setActiveSheetIndex(0);
			
			
			$objPHPExcel->getActiveSheet()->SetCellValue('A1', 'ORDER #');
			
			$objPHPExcel->getActiveSheet()->SetCellValue('B1', 'ORDER STATUS');
			
            $objPHPExcel->getActiveSheet()->SetCellValue('C1', 'QUOTE #');
            
            $objPHPExcel->getActiveSheet()->SetCellValue('D1', 'ORDER TYPE');
            
            $objPHPExcel->getActiveSheet()->SetCellValue('E1','STAGE');
            
            $objPHPExcel->getActiveSheet()->SetCellValue('F1','PM');
			$objPHPExcel->getActiveSheet()->SetCellValue('G1', 'CUSTOMER');
			$objPHPExcel->getActiveSheet()->SetCellValue('H1', 'CUSTOMER PO');
			$objPHPExcel->getActiveSheet()->SetCellValue('I1', 'RECIPIENT');
			$objPHPExcel->getActiveSheet()->SetCellValue('J1', 'SHIP DATE');
			
			$objPHPExcel->getActiveSheet()->SetCellValue('K1', 'SCHEDULED');
			
			$objPHPExcel->getActiveSheet()->SetCellValue('L1', 'BOOK DATE');
			$objPHPExcel->getActiveSheet()->SetCellValue('M1', 'BATCH');
			$objPHPExcel->getActiveSheet()->SetCellValue('N1', 'LINE');
			
			$objPHPExcel->getActiveSheet()->SetCellValue('O1', 'LOCATION');
		
			$objPHPExcel->getActiveSheet()->SetCellValue('P1', 'QTY');
			
			$objPHPExcel->getActiveSheet()->SetCellValue('Q1', 'CLASS');
			$objPHPExcel->getActiveSheet()->SetCellValue('R1', 'SUBCLASS');
			
			$objPHPExcel->getActiveSheet()->SetCellValue('S1', 'UNIT');
			$objPHPExcel->getActiveSheet()->SetCellValue('T1', 'LINE ITEM');
			$objPHPExcel->getActiveSheet()->SetCellValue('U1', 'FABRIC');
			$objPHPExcel->getActiveSheet()->SetCellValue('V1', 'COLOR');
			
			$objPHPExcel->getActiveSheet()->SetCellValue('W1', 'CUT WIDTH');
			$objPHPExcel->getActiveSheet()->SetCellValue('X1', 'FIN WIDTH');
			
			$objPHPExcel->getActiveSheet()->SetCellValue('Y1', 'LENGTH');
			
			$objPHPExcel->getActiveSheet()->SetCellValue('Z1', 'CC LF');
			
			$objPHPExcel->getActiveSheet()->SetCellValue('AA1', 'DRP WIDTHS');
			$objPHPExcel->getActiveSheet()->SetCellValue('AB1', 'TOP TR LF');
			
			$objPHPExcel->getActiveSheet()->SetCellValue('AC1', 'YDS / UNIT');
			$objPHPExcel->getActiveSheet()->SetCellValue('AD1', 'TOTAL YARDS');
			$objPHPExcel->getActiveSheet()->SetCellValue('AE1', 'BASE');
			$objPHPExcel->getActiveSheet()->SetCellValue('AF1', 'TIERS');
			$objPHPExcel->getActiveSheet()->SetCellValue('AG1', 'ADJ PRICE');
			$objPHPExcel->getActiveSheet()->SetCellValue('AH1', 'EXT PRICE');

            
            
            $objPHPExcel->getActiveSheet()->SetCellValue('AI1', 'PRODUCED');
			$objPHPExcel->getActiveSheet()->SetCellValue('AJ1', 'SHIPPED');
			
			$objPHPExcel->getActiveSheet()->SetCellValue('AK1', 'TRACKING #');
			
			$objPHPExcel->getActiveSheet()->SetCellValue('AL1', 'INVOICED');
			
			$objPHPExcel->getActiveSheet()->SetCellValue('AM1', 'LINE NOTES');
			
			
			
			
			$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(15);
			$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(15);
			$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(15);
			$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(15);
			$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(25);
			$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(15);
			$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(40);
			$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(25);
			$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(55);
			$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(15);
		
			$objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(15);
			
			$objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(15);
			$objPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth(15);
			$objPHPExcel->getActiveSheet()->getColumnDimension('N')->setWidth(15);
			
			$objPHPExcel->getActiveSheet()->getColumnDimension('O')->setWidth(20);
			
			$objPHPExcel->getActiveSheet()->getColumnDimension('P')->setWidth(15);
			
			
			$objPHPExcel->getActiveSheet()->getColumnDimension('Q')->setWidth(22);
			$objPHPExcel->getActiveSheet()->getColumnDimension('R')->setWidth(22);
			
			$objPHPExcel->getActiveSheet()->getColumnDimension('S')->setWidth(15);
			$objPHPExcel->getActiveSheet()->getColumnDimension('T')->setWidth(75);
	
			$objPHPExcel->getActiveSheet()->getColumnDimension('U')->setWidth(20);
			$objPHPExcel->getActiveSheet()->getColumnDimension('V')->setWidth(20);
			$objPHPExcel->getActiveSheet()->getColumnDimension('W')->setWidth(20);
			$objPHPExcel->getActiveSheet()->getColumnDimension('X')->setWidth(20);
			
			$objPHPExcel->getActiveSheet()->getColumnDimension('Y')->setWidth(20);
			$objPHPExcel->getActiveSheet()->getColumnDimension('Z')->setWidth(20);
			
			$objPHPExcel->getActiveSheet()->getColumnDimension('AA')->setWidth(15);
			$objPHPExcel->getActiveSheet()->getColumnDimension('AB')->setWidth(15);
			
			$objPHPExcel->getActiveSheet()->getColumnDimension('AC')->setWidth(20);
			
			$objPHPExcel->getActiveSheet()->getColumnDimension('AD')->setWidth(20);
			$objPHPExcel->getActiveSheet()->getColumnDimension('AE')->setWidth(20);
			$objPHPExcel->getActiveSheet()->getColumnDimension('AF')->setWidth(30);
	
			$objPHPExcel->getActiveSheet()->getColumnDimension('AG')->setWidth(15);
			$objPHPExcel->getActiveSheet()->getColumnDimension('AH')->setWidth(15);
			
			
			$objPHPExcel->getActiveSheet()->getColumnDimension('AI')->setWidth(15);
			
			$objPHPExcel->getActiveSheet()->getColumnDimension('AJ')->setWidth(15);
			$objPHPExcel->getActiveSheet()->getColumnDimension('AK')->setWidth(29);
			$objPHPExcel->getActiveSheet()->getColumnDimension('AL')->setWidth(15);
			
			$objPHPExcel->getActiveSheet()->getColumnDimension('AM')->setWidth(70);
			

			
			$objPHPExcel->getActiveSheet()->getRowDimension('1')->setRowHeight(22);
			$objPHPExcel->getActiveSheet()->getStyle('A1:AM1')->getAlignment()->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);
			
			$objPHPExcel->getActiveSheet()->getStyle('A1:AM1')->applyFromArray(
				array(
					'fill' => array(
						'type' => \PHPExcel_Style_Fill::FILL_SOLID,
						'color' => array('rgb' => 'F8CBAD')
					)
				)
			);
			
			
            $rowCount = 2;
			
			$outputArr=array();
			
			foreach($ordersLookup as $orderRow){
			    
			    $quoteItemsLoop=$this->QuoteLineItems->find('all',['conditions'=>['quote_id' => $orderRow['quote_id']],'order'=>['line_number'=>'asc']])->toArray();
				
				$quoteData=$this->Quotes->get($orderRow['quote_id'])->toArray();
				
				$customer=$this->Customers->get($orderRow['customer_id'])->toArray();
				$thisCustomer=$this->Customers->get($orderRow['customer_id'])->toArray();
				
				foreach($quoteItemsLoop as $quoteItem){
				    
				    $thisClass=$this->ProductClasses->get($quoteItem['product_class'])->toArray();
				    $classValue=$thisClass['class_name'];
				    
				    
				    $thisSubclass=$this->ProductSubclasses->get($quoteItem['product_subclass'])->toArray();
				    $subclassValue=$thisSubclass['subclass_name'];
				    
				    
					if($quoteItem['parent_line'] == 0){
					    
					$orderItemFind=$this->OrderItems->find('all',['conditions' => ['quote_line_item_id' => $quoteItem['id']]])->toArray();
					foreach($orderItemFind as $orderItem){
					    
    						//look for BATCHES, if found, loop through those, then loop through the remainders or unbatched items
    						$batchesScheduled=$this->OrderItemStatus->find('all',['conditions' => ['order_item_id' => $orderItem['id'], 'status IN' => ['Scheduled','Completed','Invoiced','Shipped']]])->toArray();
    						$qtyShipped=0;
    						$qtyInvoiced=0;
    						$qtyCompleted=0;
    						$qtyScheduled=0;
    						
    						$loop=array(
    							'batches' => array(),
    							'unbatched' => 0
    						);
    						
    						$scheduledBatchesThisLine=0;
    						
    						foreach($batchesScheduled as $batchData){
    							switch($batchData['status']){
    								case 'Shipped':
    									$qtyShipped=($qtyShipped + intval($batchData['qty_involved']));
    								break;
    								case 'Invoiced':
    									$qtyInvoiced=($qtyInvoiced + intval($batchData['qty_involved']));
    								break;
    								case 'Scheduled':
    									$qtyScheduled=($qtyScheduled + intval($batchData['qty_involved']));
    									$loop['batches'][]=array('batchid'=>$batchData['sherry_batch_id'],'qty'=>$batchData['qty_involved'],'scheduledTS'=>$batchData['time']);
    									$scheduledBatchesThisLine++;
    								break;
    								case 'Completed':
    									$qtyCompleted=($qtyCompleted + intval($batchData['qty_involved']));
    								break;
    							}
    						}
    						
    						//loop back through again to add Shipped or Invoiced timestamps to batches that have them
    						foreach($batchesScheduled as $batchData){
    							switch($batchData['status']){
    								case 'Shipped':
    									foreach($loop['batches'] as $num => $batchloopitem){
    										if($batchloopitem['batchid'] == $batchData['sherry_batch_id']){
    											$loop['batches'][$num]['shippedTS'] = $batchData['time'];
    										}
    									}
    								break;
    								case 'Invoiced':
    									foreach($loop['batches'] as $num => $batchloopitem){
    										if($batchloopitem['batchid'] == $batchData['sherry_batch_id']){
    											$loop['batches'][$num]['invoicedTS'] = $batchData['time'];
    										}
    									}
    								break;
    							}
    						}
    						
    						$remainingUnscheduled=(intval($quoteItem['qty']) - $qtyScheduled);
    						$remainingUninvoiced=(intval($quoteItem['qty']) - $qtyInvoiced);
    						$remainingUnshipped = (intval($quoteItem['qty']) - $qtyShipped);
    						$remainingIncompleteScheduled= (intval($quoteItem['qty']) - $qtyCompleted);
    						
    						$loop['unbatched']=$remainingUnscheduled;
    					
    						
    						$itemMetas=$this->QuoteLineItemMeta->find('all',['conditions'=>['quote_item_id'=>$quoteItem['id']]])->toArray();
    						$metaArray=array();
    						foreach($itemMetas as $meta){
    							$metaArray[$meta['meta_key']]=$meta['meta_value'];
    						}
    						
    						
    						
    						if($quoteItem['product_type'] != 'custom' && $quoteItem['product_type'] != 'services' && $quoteItem['product_type'] != 'track_systems'){
    							
    							
    							if($metaArray['fabrictype'] != 'none' && $metaArray['fabrictype'] != 'typein'){
    								
    								if(isset($metaArray['fabricid']) && strlen(trim($metaArray['fabricid'])) >0 && $metaArray['fabricid'] != '0'){
    									$thisFabric=$this->Fabrics->get($metaArray['fabricid'])->toArray();
    								}else{
    									$thisFabric=array('fabric_name'=>'','color'=>'');
    								}
    								
    								//$thisFabric=$this->Fabrics->get($metaArray['fabricid'])->toArray();
    							}elseif($metaArray['fabrictype'] == 'typein'){
    							    $thisFabric=array('fabric_name'=>$metaArray['fabric_name'],'color'=>$metaArray['fabric_color']);
    							}else{
    								$thisFabric=array('fabric_name'=>'','color'=>'');
    							}
    						}else{
    							if($metaArray['fabrictype'] != 'none' && $metaArray['fabrictype'] != 'typein'){
    								if(isset($metaArray['fabricid']) && strlen(trim($metaArray['fabricid'])) >0 && $metaArray['fabricid'] != '0'){
    									$thisFabric=$this->Fabrics->get($metaArray['fabricid'])->toArray();
    								}else{
    									$thisFabric=array('fabric_name'=>'','color'=>'');
    								}
    							}elseif($metaArray['fabrictype'] == 'typein'){
    							    $thisFabric=array('fabric_name'=>$metaArray['fabric_name'],'color'=>$metaArray['fabric_color']);
    							}else{
    								$thisFabric=array('fabric_name'=>'','color'=>'');
    							}
    						}
    						
    						
    						
    						$thisbookingdate='';
    
    						//lookup the original Order Item Status for this line item and get the date value
    						$itemstatuslookup=$this->OrderItemStatus->find('all',['conditions' => ['order_item_id' => $orderItem['id'],'status' => 'Not Started']])->toArray();
    						foreach($itemstatuslookup as $itemstatusrow){
    							//$thisbookingdate=date('n/j/Y',$itemstatusrow['time']);
    							$bookDateTimeObj=new \DateTime();
    							$bookDateTimeObj->setTimestamp($itemstatusrow['time']);
    							
    							$thisbookingdate=\PHPExcel_Shared_Date::PHPToExcel($bookDateTimeObj);
    						}
    
    
    						$customerValue=$thisCustomer['company_name'];
    						$customerPOValue=$orderRow['po_number'];
    						$facilityValue=$orderRow['facility'];
    						
    						
    						$quoteNumberValue='';
    						if($quoteData['revision'] > 0){
    							$quoteNumberValue=$quoteData['quote_number']."\n[REV ".$quoteData['revision']."]";
    						}else{
    							$quoteNumberValue=$quoteData['quote_number'];
    						}
    						
    						
    						
    						if($orderRow['due'] < 1000){
    							$shipDateValue='';
    						}else{
    							//$shipDateValue=date('n/d/Y',$orderRow['due']);
    							$dueDateTimeObj=new \DateTime();
            					$dueDateTimeObj->setTimestamp($orderRow['due']);
            					$shipDateValue = \PHPExcel_Shared_Date::PHPToExcel($dueDateTimeObj);
    						}
    
    						$lineNumberValue=$quoteItem['line_number'];
    						$locationValue=$quoteItem['room_number'];
    						$unitValue=$quoteItem['unit'];
    						$itemDescription='';
    						
    						///////////////////////////////////////////////////////////////////////////////////////////////////////////////
    						
    						if($metaArray['lineitemtype']=='calculator'){
    							if($metaArray['calculator-used']=="straight-cornice"){
    								$itemDescription .= $metaArray['cornice-type']." Cornice";
    								if($metaArray['railroaded'] == '1'){
    									$itemDescription .= "<br>Fabric Railroaded";
    								}else{
    									$itemDescription .= "<br>Fabric Vertically Seamed";
    								}
    								$thisLining=$this->Linings->get($metaArray['linings_id'])->toArray();
    								$itemDescription .= "<br><b>Lining:</b> ".$thisLining['short_title'];
    								$welts='';
    								if($metaArray['welt-top']=='1' && $metaArray['welt-bottom']=='1'){
    									$welts = "Top + Bottom";
    								}else{
    									if($metaArray['welt-top']=='1'){
    										$welts = "Top Only";
    									}elseif($metaArray['welt-bottom'] == '1'){
    										$welts = "Bottom Only";
    									}
    								}
    								if($welts != ''){
    									$itemDescription .= "<br><b>Welts:</b> ".$welts;
    								}else{
    									$itemDescription .= "<br><b>Welts:</b> None";
    								}
    								if($metaArray['individual-nailheads'] == '1'){
    									$itemDescription .= "<br>Individual Nailheads";
    								}
    								if($metaArray['nailhead-trim'] == '1'){
    									$itemDescription .= "<br>Nailhead Trim";
    								}
    								if($metaArray['covered-buttons'] == '1'){
    									$itemDescription .= "<br>".$metaArray['covered-buttons-count']." Covered Buttons";
    								}
    
    								if($metaArray['horizontal-straight-banding'] == '1'){
    									$itemDescription .= "<br>".$metaArray['horizontal-straight-banding-count']." H Straight Banding";
    								}	
    								if($metaArray['horizontal-shaped-banding'] == '1'){
    									$itemDescription .= "<br>".$metaArray['horizontal-shaped-banding-count']." H Shaped Banding";
    								}
    								if($metaArray['extra-welts'] == '1'){
    									$itemDescription .= "<br>".$metaArray['extra-welts-count']." Extra Welts";
    								}	
    								if($metaArray['trim-sewn-on'] == '1'){
    									$itemDescription .= "<br>".$metaArray['trim-lf']." LF Sewn-On Trim";
    								}
    								if($metaArray['tassels'] == '1'){
    									$itemDescription .= "<br>".$metaArray['tassels-count']." Tassels";
    								}
    								if($metaArray['drill-holes'] == '1'){
    									$itemDescription .= "<br>".$metaArray['drill-hole-count']." Drill Holes";
    								}
    								if(isset($metaArray['vertical-repeat']) && floatval($metaArray['vertical-repeat']) >0){
    									$itemDescription .= "<br>Matched Repeat (".$metaArray['vertical-repeat']."&quot;)";
    								}
    							}elseif($metaArray['calculator-used']=="bedspread" || $metaArray['calculator-used'] == "bedspread-manual"){
    								$itemDescription .= $productMap[$metaArray['calculator-used']];
    								if($metaArray['railroaded'] == '1'){
    									$itemDescription .= "<br>Fabric Railroaded";
    								}else{
    									$itemDescription .= "<br>Fabric Up-the-Roll";
    								}
    
    								$itemDescription .= "<br>";
    								if(isset($metaArray['style']) && strlen(trim($metaArray['style'])) >0){
    									$itemDescription .= "Style: ".$metaArray['style'];
    								}
    								if(isset($metaArray['quilted']) && $metaArray['quilted']=='1'){
    									$itemDescription .= "<br>Quilted";
    									if(isset($metaArray['quilting-pattern']) && strlen(trim($metaArray['quilting-pattern'])) >0){
    										$itemDescription .= ", ".$metaArray['quilting-pattern'];
    									}
    									if(isset($metaArray['matching-thread']) && $metaArray['matching-thread'] == '1'){
    										$itemDescription .= ", Matching Thread";
    									}
    									$itemDescription .= ", ".$thisFabric['bs_backing_material']." Backing";
    								}else{
    									$itemDescription .= "<br>Unquilted";
    								}
    
    								$itemDescription .= "<br>Mattress: ";
    								if(!isset($metaArray['custom-top-width-mattress-w'])){
    									$itemDescription .= "36&quot;";
    								}else{
    									$itemDescription .= $metaArray['custom-top-width-mattress-w']."&quot;";
    								}
    
    								if(isset($metaArray['vertical-repeat']) && floatval($metaArray['vertical-repeat']) >0){
    									$itemDescription .= "<br>Matched Repeat (".$metaArray['vertical-repeat']."&quot;)";
    								}
    							}elseif($metaArray['calculator-used']=="box-pleated"){
    								$itemDescription .= $metaArray['valance-type']." Valance";
    
    								if($metaArray['railroaded'] == '1'){
    									$itemDescription .= "<br>Fabric Railroaded";
    								}else{
    									$itemDescription .= "<br>Fabric Vertically Seamed";
    								}
    								$itemDescription .= "<br>".$metaArray['pleats']." Pleats";
    
    								$thisLining=$this->Linings->get($metaArray['linings_id'])->toArray();
    								$itemDescription .= "<br><b>Lining:</b> ".$thisLining['short_title'];
    
    								if($metaArray['straight-banding']==1){
    									$itemDescription .= "<br>Straight Banding";
    								}
    								if($metaArray['shaped-banding']==1){
    									$itemDescription .= "<Br>Shaped Banding";
    								}
    								if($metaArray['trim-sewn-on']==1){
    									$itemDescription .= "<br>Sewn-On Trim";
    								}
    								if($metaArray['welt-covered-in-fabric'] == 1){
    									$itemDescription .= "<br>Welt Covered In Fabric";
    								}
    								if($metaArray['contrast-fabric-inside-pleat'] == 1){
    									$itemDescription .= "<br>Contrast Fabric Inside Pleat";
    								}
    								if(isset($metaArray['vert-repeat']) && floatval($metaArray['vert-repeat']) >0){
    									$itemDescription .= "<br>Matched Repeat (".$metaArray['vert-repeat']."&quot;)";
    								}
    							}elseif($metaArray['calculator-used']=="cubicle-curtain"){
    								$itemDescription .= $productMap[$metaArray['calculator-used']];
    								if($metaArray['railroaded'] == '1'){
    									$itemDescription .= "<br>Fabric Railroaded";
    								}else{
    									$itemDescription .= "<br>Fabric Vertically Seamed";
    								}
    								if($metaArray['mesh'] > 0 && $metaArray['mesh'] != ''){
    									$itemDescription .= "<br><b>Mesh:</b> ".$metaArray['mesh']."&quot; ".$metaArray['mesh-color']." (".str_replace(" Mesh","",$metaArray['mesh-type']).")";
    								}
    								if($metaArray['mesh-type'] == 'None'){
    									$itemDescription .= "<br>NO MESH";
    								}
    								if($metaArray['mesh-type'] == 'Integral Mesh'){
    									$itemDescription .= "<br>INTEGRAL MESH";
    								}
    								if($metaArray['liner'] == 1){
    									$itemDescription .= "<br>Liner";
    								}
    								if($metaArray['nylon-mesh']==1){
    									$itemDescription .= "<br>Nylon Mesh";
    								}
    								if($metaArray['angled-mesh']==1){
    									$itemDescription .= "<br>Angled Mesh";
    								}
    								if($metaArray['mesh-frame'] != 'No Frame'){
    									$itemDescription .= "<br><b>Mesh Frame:</b> ".$metaArray['mesh-frame'];
    								}
    								if($metaArray['hidden-mesh'] == 1){
    									$itemDescription .= "<br>Hidden Mesh";
    								}
    
    								if($metaArray['snap-tape'] != "None"){
    									$itemDescription .= "<br>".$metaArray['snap-tape']." Snap Tape (".$metaArray['snaptape-lf'].")";
    								}
    								if($metaArray['velcro'] != 'None'){
    									$itemDescription .= "<br>".$metaArray['velcro']." Velcro (".$metaArray['velcro-lf']." LF)";
    								}
    								if($metaArray['weights']==1){
    									$itemDescription .= "<br>".$metaArray['weight-count']." Weights";
    								}
    								if($metaArray['magnets']==1){
    									$itemDescription .= "<br>".$metaArray['magnet-count']." Magnets";
    								}
    								if($metaArray['banding'] == 1){
    									$itemDescription .= "<br>Banding";
    								}
    								if($metaArray['buttonholes'] == 1){
    									$itemDescription .= "<br>".$metaArray['buttonhole-count']." Buttonholes";
    								}
    								if(isset($metaArray['vertical-repeat']) && floatval($metaArray['vertical-repeat']) >0){
    									$itemDescription .= "<br>Matched Repeat (".$metaArray['vertical-repeat']."&quot;)";
    								}
    							}elseif($metaArray['calculator-used']=='pinch-pleated'){
    								$itemDescription .= $productMap[$metaArray['calculator-used']];
    								if($metaArray['unit-of-measure'] == 'pair'){
    									$itemDescription .= "<br>Pair";
    								}elseif($metaArray['unit-of-measure'] == 'panel'){
    									$itemDescription .= "<br>".$metaArray['panel-type']." Panel";
    								}
    								if($metaArray['railroaded'] == '1'){
    									$itemDescription .= "<br>Fabric Railroaded";
    								}else{
    									$itemDescription .= "<br>Fabric Vertically Seamed";
    								}
    								if(isset($metaArray['linings_id']) && floatval($metaArray['linings_id']) >0){
    									$thisLining=$this->Linings->get($metaArray['linings_id'])->toArray();
    									$itemDescription .= "<br><b>Lining:</b> ".$thisLining['short_title'];
    								}
    								$itemDescription .= "<br><b>Hardware:</b> ".ucfirst($metaArray['hardware']);
    								if($metaArray['trim-sewn-on'] == '1'){
    									$itemDescription .= "<br>".$metaArray['trim-lf']." LF Sewn-On Trim";
    								}
    								if($metaArray['tassels'] == '1'){
    									$itemDescription .= "<br>".$metaArray['tassels-count']." Tassels";
    								}
    							}
    						}elseif($metaArray['lineitemtype']=='simpleproduct'){
    							switch($quoteItem['product_type']){
    								case "cubicle_curtains":
    									$itemDescription .= "Price List CC";
    									if($thisFabric['railroaded'] == '1'){
    										$itemDescription .= "<br>Fabric Railroaded";
    									}else{
    										$itemDescription .= "<br>Fabric Vertically Seamed";
    									}
    									if($metaArray['mesh'] > 0 && $metaArray['mesh'] != ''){
    										$itemDescription .= "<br><b>Mesh:</b> ".$metaArray['mesh']."&quot; ".$metaArray['mesh-color']." (MOM)";
    									}
    									if($metaArray['mesh'] == 'No Mesh' || $metaArray['mesh'] == '0'){
    										$itemDescription .= "<br>NO MESH";
    									}
    									if(floatval($thisFabric['vertical_repeat']) > 0){
    										$itemDescription .= "<br>Matched Repeat (".$thisFabric['vertical_repeat']."&quot;)";
    									}
    								break;
    								case "bedspreads":
    									$itemDescription .= "Price List BS";
    									$thisBS="";
    									try {
    									        $thisBS=$this->Bedspreads->get($quoteItem['product_id'])->toArray();
                                            } catch (RecordNotFoundException $e) { }
    								
    									if($thisFabric['railroaded'] == '1'){
    										$itemDescription .= "<br>Fabric Railroaded";
    									}else{
    										$itemDescription .= "<br>Fabric Vertically Seamed";
    									}
    									$itemDescription .= "<br>Style: ";
    									$styleval=explode(" (",$metaArray['style']);
    									$itemDescription .= $styleval[0];
    									if(!empty($thisBS) && $thisBS['quilted']=='1'){
    										$itemDescription .= "<br>Quilted, Double Onion, ".$thisFabric['bs_backing_material']." Backing";
    									}else{
    										$itemDescription .= "<br>Unquilted";
    									}
    									$itemDescription .= "<br>Mattress: ";
    									if(!isset($metaArray['custom-top-width-mattress-w'])){
    										$itemDescription .= "36&quot;";
    									}else{
    										$itemDescription .= $metaArray['custom-top-width-mattress-w']."&quot;";
    									}
    
    									if(floatval($thisFabric['vertical_repeat']) > 0){
    										$itemDescription .= "<br>Matched Repeat (".$thisFabric['vertical_repeat']."&quot;)";
    									}
    								break;
    								case "services":
    									$itemDescription .= $quoteItem['title'];
    								break;
    								case "window_treatments":
    									if($metaArray['wttype']=='Pinch Pleated Drapery'){
    										$itemDescription .= "<b>".ucfirst($metaArray['unit-of-measure'])."</b><br>";
    									}
    									$itemDescription .= 'Price List '.$metaArray['wttype'];
    									if(preg_match("#Cornice#i",$metaArray['wttype']) || preg_match("#Valance#i",$metaArray['wttype'])){
    										$itemDescription .= "<br>Fabric Vertically Seamed";
    									}
    									if(preg_match("#Valance#i",$metaArray['wttype'])){
    										$itemDescription .= "<br>".$metaArray['pleats']." Pleats";
    									}
    									if(isset($metaArray['linings_id']) && floatval($metaArray['linings_id']) >0){
    										$thisLining=$this->Linings->get($metaArray['linings_id'])->toArray();
    										$itemDescription .= "<br><b>Lining:</b> ".$thisLining['short_title'];
    									}
    									if(preg_match("#Cornice#i",$metaArray['wttype'])){
    										$welts='';
    										if($metaArray['welt-top']=='1' && $metaArray['welt-bottom']=='1'){
    											$welts = "Top + Bottom";
    										}else{
    											if($metaArray['welt-top']=='1'){
    												$welts = "Top Only";
    											}elseif($metaArray['welt-bottom'] == '1'){
    												$welts = "Bottom Only";
    											}
    										}
    										if($welts != ''){
    											$itemDescription .= "<br><b>Welts:</b> ".$welts;
    										}else{
    											$itemDescription .= "<br><b>Welts:</b> None";
    										}
    									}
    
    								break;
    								case "track_systems":
    									$itemDescription .= "<b>".$quoteItem['title']."</b>";
    									if(isset($metaArray['_component_numlines']) && intval($metaArray['_component_numlines']) >0){
    										$itemDescription .= "<br><button style=\"padding:4px; border:1px solid #000; background:#CCC; color:#000; font-size:11px;\" onclick=\"loadTrackBreakdown('".$quoteItem['id']."');\" type=\"button\">List Components</button>";
    									}
    								break;
    							}
    						}elseif($metaArray['lineitemtype']=='custom' || $metaArray['lineitemtype'] == 'newcatchall'){
    							$itemDescription .= "<b>".$quoteItem['title']."</b>";
    							$itemDescription .= "<br>".$quoteItem['description'];
    							$itemDescription .= "<br>".nl2br($metaArray['specs']);
    						}
    						
    						///////////////////////////////////////////////////////////////////////////////////////////////////////////////
    						
    						
    						$ttwizard = new \PHPExcel_Helper_HTML;
    						$lineItemValue = $ttwizard->toRichTextObject($itemDescription);
    						
    						
    						$fabricValue='';
    						$fabricFR='';
    						if(isset($thisFabric['flammability']) && strlen(trim($thisFabric['flammability'])) >0){
    							$fabricFR='<br><b>FR: '.$thisFabric['flammability'].'</b>';
    						}
    
    						if($quoteItem['product_type'] == 'track_systems'){
    							$fabricValue .= '---';
    						}elseif($quoteItem['product_type'] == 'custom'){
    							if(isset($metaArray['com-fabric']) && $metaArray['com-fabric'] == "1"){
    								$fabricValue .= "COM ";
    							}
    							if($fabricAlias=$this->getFabricAlias($metaArray['fabricid'],$thisQuote['customer_id'])){
    								if(isset($metaArray['usealias']) && $metaArray['usealias'] == 'yes'){
    									$fabricValue .= $fabricAlias['fabric_name']."<br>".$metaArray['fabric_name'].$fabricFR;
    								}else{
    									$fabricValue .= $metaArray['fabric_name']."<br><em>(".$fabricAlias['fabric_name'].")</em>".$fabricFR;
    								}
    							}else{
    								$fabricValue .= $metaArray['fabric_name'].$fabricFR;
    							}
    						}else{
    							if(isset($metaArray['com-fabric']) && $metaArray['com-fabric'] == "1"){
    								$fabricValue .= "COM ";
    							}
    							if($fabricAlias=$this->getFabricAlias($thisFabric['id'],$thisQuote['customer_id'])){
    								if(isset($metaArray['usealias']) && $metaArray['usealias'] == 'yes'){
    									$fabricValue .= "<b>".$fabricAlias['fabric_name']."</b><br>".$thisFabric['fabric_name'];
    								}else{
    									$fabricValue .= $thisFabric['fabric_name']."<br><em>(".$fabricAlias['fabric_name'].")</em>";
    								}
    							}else{
    								$fabricValue .= $thisFabric['fabric_name'];
    							}
    							$fabricValue .= $fabricFR;
    						}
    						
    						$fabricwizard = new \PHPExcel_Helper_HTML;
    						$fabricrichText = $fabricwizard->toRichTextObject($fabricValue);
    						
    						
    						$colorValue='';
    						if($quoteItem['product_type'] == 'track_systems'){
    							$colorValue .= '---';
    						}elseif($quoteItem['product_type'] == 'custom'){
    							if($fabricAlias=$this->getFabricAlias($metaArray['fabricid'],$thisQuote['customer_id'])){
    								if(isset($metaArray['usealias']) && $metaArray['usealias'] == 'yes'){
    									$colorValue .= "<b>".$fabricAlias['color']."</b><br>".$metaArray['fabric_color'];	
    								}else{
    									$colorValue .= $metaArray['fabric_color']."<br><em>(".$fabricAlias['color'].")</em>";
    								}
    							}else{
    								$colorValue .= $metaArray['fabric_color'];
    							}
    						}else{
    							if($fabricAlias=$this->getFabricAlias($thisFabric['id'],$thisQuote['customer_id'])){
    								if(isset($metaArray['usealias']) && $metaArray['usealias'] == 'yes'){
    									$colorValue .= "<b>".$fabricAlias['color']."</b><Br>".$thisFabric['color'];
    								}else{
    									$colorValue .= $thisFabric['color']."<br><em>(".$fabricAlias['color'].")</em>";
    								}
    							}else{
    								$colorValue .= $thisFabric['color'];
    							}
    						}
    						
    						$colorwizard = new \PHPExcel_Helper_HTML;
    						$colorrichText = $colorwizard->toRichTextObject($colorValue);
    						
    						$cutwidthValue='';
    						$finwidthValue='';
    						
    						if($quoteItem['product_type'] == 'track_systems'){
    							$cutwidthValue .= '---';
    						}elseif($quoteItem['product_type']=="bedspreads"){
    							$cutwidthValue .= number_format(floatval($metaArray['width']),0,'','');
    						}elseif($quoteItem['product_type']=="cubicle_curtains"){
    							$cutwidthValue .= number_format(floatval($metaArray['width']),0,'','');
    							if(isset($metaArray['expected-finish-width']) && strlen(trim($metaArray['expected-finish-width'])) >0){
    								$finwidthValue .= $metaArray['expected-finish-width'];
    							}
    						}elseif($quoteItem['product_type'] == 'window_treatments'){
    							if($metaArray['wttype'] == 'Pinch Pleated Drapery'){
    								$cutwidthValue .= number_format(floatval($metaArray['rod-width']),0,'','')." (Rod)";
    								$finwidthValue .= $metaArray['default-return']." (Return)";
    							}elseif(preg_match("#Cornice#i",$metaArray['wttype']) || preg_match("#Valance#i",$metaArray['wttype'])){
    								$cutwidthValue .= number_format(floatval($metaArray['width']),0,'','')." (Face)";
    								if(isset($metaArray['return']) && floatval(trim($metaArray['return'])) >0){
    									$finwidthValue .= $metaArray['return']." (Return)";
    								}else{
    									$finwidthValue .= "No Return";
    								}
    							}
    						}else{
    							if($metaArray['calculator-used']=="box-pleated"){
    								if(isset($metaArray['face']) && strlen(trim($metaArray['face'])) >0){
    									$cutwidthValue .= $metaArray['face']." (Face)";
    								}
    								if(isset($metaArray['return']) && floatval(trim($metaArray['return'])) >0){
    									$finwidthValue .= $metaArray['return']." (Return)";
    								}else{
    									$finwidthValue .= "No Return";
    								}
    							}elseif($metaArray['calculator-used']=="bedspread" || $metaArray['calculator-used']=="bedspread-manual"){
    								if(isset($metaArray['width']) && strlen(trim($metaArray['width'])) >0){
    									$finwidthValue .= $metaArray['width'];
    								}
    							}elseif($metaArray['calculator-used']=="cubicle-curtain"){
    								if(isset($metaArray['width']) && strlen(trim($metaArray['width'])) >0){
    									$cutwidthValue .= $metaArray['width'];
    								}
    								if(isset($metaArray['expected-finish-width']) && strlen(trim($metaArray['expected-finish-width'])) >0){
    									$finwidthValue .= $metaArray['expected-finish-width'];
    								}else{
    									if(isset($metaArray['fw']) && strlen(trim($metaArray['fw'])) >0){
    										$finwidthValue .= $metaArray['fw'];
    									}
    								}
    							}elseif($metaArray['calculator-used']=="straight-cornice"){
    								if(isset($metaArray['face']) && strlen(trim($metaArray['face'])) >0){
    									$cutwidthValue .= $metaArray['face']." (Face)";
    								}
    								if(isset($metaArray['return']) && floatval(trim($metaArray['return'])) >0){
    									$finwidthValue .= $metaArray['return']." (Return)";
    								}else{
    									$finwidthValue .= "No Return";
    								}
    							}elseif($metaArray['calculator-used'] == 'pinch-pleated'){
    								if($metaArray['unit-of-measure'] == 'pair'){
    									if(isset($metaArray['rod-width']) && strlen(trim($metaArray['rod-width'])) >0){
    										$cutwidthValue .= $metaArray['rod-width']." (Rod)";
    									}
    								}else{
    									if(isset($metaArray['fabric-widths-per-panel'])){
    										$cutwidthValue .= $metaArray['fabric-widths-per-panel']." Widths";
    									}
    								}
    								if(isset($metaArray['fullness']) && strlen(trim($metaArray['fullness'])) >0){
    									$finwidthValue .= $metaArray['fullness']."X Fullness";
    								}
    								if(isset($metaArray['default-return']) && strlen(trim($metaArray['default-return'])) >0){
    									$finwidthValue .= "<br>".$metaArray['default-return']." Ret";
    								}
    								if(isset($metaArray['default-overlap']) && strlen(trim($metaArray['default-overlap'])) >0){
    									$finwidthValue .= "<br>".$metaArray['default-overlap']." Ovrlp";
    								}
    							}
    						}
    						
    						$cutwidthwizard = new \PHPExcel_Helper_HTML;
    						$cutwidthValue = $cutwidthwizard->toRichTextObject($cutwidthValue);
    						
    						if($quoteItem['product_type'] == 'newcatchall-drapery'){
    						    $drapewidthsValue=$metaArray['total-billable-widths'];
    						}elseif($quoteItem['calculator_used'] == 'pinch-pleated' || $quoteItem['calculator_used'] == 'pinch-pleated-new'){
    						    $drapewidthsValue=(intval($quoteItem['qty']) * intval($metaArray['labor-widths']));
    						}else{
    						    $drapewidthsValue='';
    						}
    						
    						
    						$finwidthwizard = new \PHPExcel_Helper_HTML;
    						$finwidthValue = $finwidthwizard->toRichTextObject($finwidthValue);
    						
    						$lengthValue='';
    						if($quoteItem['product_type'] == 'track_systems'){
    							$lengthValue='---';
    						}else{
    							if($metaArray['calculator-used']=="box-pleated"){
    								$lengthValue .= $metaArray['height']." (Height)";
    							}elseif($metaArray['calculator-used']=="straight-cornice"){
    								$lengthValue .= $metaArray['height']." (Height)";
    							}else{
    								$lengthValue .= $metaArray['length'];
    								if($quoteItem['product_type'] == 'window_treatments' && (preg_match("#Cornice#i",$metaArray['wttype']) || preg_match("#Valance#i",$metaArray['wttype']))){
    									$lengthValue .= " (Height)";
    								}
    
    							}
    							if(isset($metaArray['fl-short']) && floatval($metaArray['fl-short']) >0){
    								$lengthValue .= "<br>".$metaArray['fl-short']."(Short Point)";
    							}
    						}
    						$lengthwizard = new \PHPExcel_Helper_HTML;
    						$lengthrichText = $lengthwizard->toRichTextObject($lengthValue);
    						
    					
    						
    						
    						
    						
    						if(isset($metaArray['yds-per-unit'])){
    							$ydsperunitValue=$metaArray['yds-per-unit'];
    						}else{
    							$ydsperunitValue='---';
    						}
    						
    						
    					
    						
    						$bestprice=$quoteItem['best_price'];
    						$installadjustmentpercentage=0;
    						$tieradjustmentpercentage=0;
    						$tierDiscountOrPremium='Disc';
    						$rebateadjustmentpercentage=0;
    						
    						if($metaArray['specialpricing']=='1'){
    							$tieradjusted=number_format(floatval($quoteItem['best_price']),2,'.',',');
    							$installadjusted=number_format(floatval($quoteItem['best_price']),2,'.',',');
    							$rebateadjusted=number_format(floatval($quoteItem['best_price']),2,'.',',');
    							$pmiadjusted=number_format(floatval($quoteItem['pmi_adjusted']),2,'.',',');
    							$pmiadjustmentdollars=number_format((floatval(str_replace(',','',$pmiadjusted)) - floatval(str_replace(',','',$rebateadjusted))),2,'.',',');
    							
    						}else{
    							$tieradjusted=number_format(floatval($quoteItem['tier_adjusted_price']),2,'.',',');
    							$installadjusted=number_format(floatval($quoteItem['install_adjusted_price']),2,'.',',');
    							$rebateadjusted=number_format(floatval($quoteItem['rebate_adjusted_price']),2,'.',',');
    							$pmiadjusted=number_format(floatval($quoteItem['pmi_adjusted']),2,'.',',');
    							
    							$tieradjustmentpercentage=round(abs((((1/floatval(str_replace(',','',$quoteItem['best_price']))) * floatval(str_replace(',','',$tieradjusted)))*100)),2);
    							$installadjustmentpercentage=round(abs(((1-((1/floatval(str_replace(',','',$tieradjusted))) * floatval(str_replace(',','',$installadjusted))))*100)),2);
    							$rebateadjustmentpercentage=round(abs(((1-((1/floatval(str_replace(',','',$installadjusted))) * floatval(str_replace(',','',$rebateadjusted))))*100)),2);
    							$pmiadjustmentdollars=number_format((floatval(str_replace(',','',$pmiadjusted)) - floatval(str_replace(',','',$rebateadjusted))),2,'.',',');
    						}
    
    						$breakdownValue='';
    						$basePriceValue=number_format($bestprice,2,'.',',');
    						
    						if($metaArray['specialpricing']=='1'){
    							$breakdownValue .= "<font color=\"#FF0000\"><b><em>SPECIAL PRICING</em></b></font>";
    						}else{
    							$breakdownValue .= "<font color=\"#0000FF\">".$tieradjusted." Tier (";
    							if(floatval(str_replace(',','',$quoteItem['best_price'])) > floatval(str_replace(',','',$tieradjusted))){
    								$breakdownValue .= '-'.$tieradjustmentpercentage."% Disc";
    							}elseif(floatval(str_replace(',','',$quoteItem['best_price'])) < floatval(str_replace(',','',$tieradjusted))){
    								if($tieradjustmentpercentage > 100){
    									$breakdownValue .= '+'.($tieradjustmentpercentage-100)."% Prem";
    								}else{
    									$breakdownValue .= '+'.$tieradjustmentpercentage."% Prem";
    								}
    							}else{
    								$breakdownValue .= "0%";
    							}
    							
    							$breakdownValue .= ")<br>".$installadjusted." INST (+".$installadjustmentpercentage."%)<br>".$rebateadjusted." ADD ";
    							$breakdownValue .= "(+".$rebateadjustmentpercentage."%)";
    							$breakdownValue .= "<br>".$pmiadjusted." PMI (+\$".$pmiadjustmentdollars.")</font>";
    						}
    						
    						
    						$breakdownwizard = new \PHPExcel_Helper_HTML;
    						$breakdownrichText = $breakdownwizard->toRichTextObject($breakdownValue);
    						
    						
    						
    						if($quoteItem['override_active'] == 1){
    							$adjustedColValue = number_format(floatval($quoteItem['override_price']),2,'.','');
    						}else{
    							$adjustedColValue = number_format(floatval($pmiadjusted),2,'.','');
    						}
    						
    						
    						
    						
    						
    						
    						
    						$extendedPriceValue=number_format($extendedprice,2,'.',',');
    						
    						$dateScheduled='';
    						$dateShipped='';
    						$dateInvoiced='';
    						$dateProduced='';
    						$trackingNumber='';
    						
    						
    						$lineNotesValue='';
    						$lineNotes=$this->QuoteLineItemNotes->find('all',['conditions'=>['quote_item_id' => $quoteItem['id']],'order'=>['time'=>'asc']])->toArray();
    						foreach($lineNotes as $lineNoteRow){
    							$thisNoteUser=$this->Users->get($lineNoteRow['user_id'])->toArray();
    							if($lineNoteRow['visible_to_customer'] == 0){
    								$lineNotesValue .= '[INTERNAL] ';
    							}
    							$lineNotesValue .= $lineNoteRow['message'].' <font color="#0000FF"><em>'.$thisNoteUser['first_name'].' '.substr($thisNoteUser['last_name'],0,1).' @ '.date('n/j/y g:iA',$lineNoteRow['time']).'</em></font><br>';
    						}
    						
    						$linenoteswizard = new \PHPExcel_Helper_HTML;
    						$linenotesrichText = $linenoteswizard->toRichTextObject($lineNotesValue);
    						
    						
    						
    						//start inner loop of $loop
    						//begin with UNBATCHED
    						if($loop['unbatched'] > 0){
    							$qtyValue=$loop['unbatched'];
    							$batchValue='N/A';
    							
    							$totalLFvalue='';
    							
    							$totalydsvalue='';
    							if($metaArray['lineitemtype'] == 'simpleproduct'){
    								$totalydsvalue .= round((floatval($metaArray['yds-per-unit']) * floatval($qtyValue)),2);
    							}elseif($metaArray['lineitemtype']=="custom"){
    								if(floatval($metaArray['total-yds']) >0){
    									$totalydsvalue .= round(((floatval($metaArray['total-yds']) / floatval($quoteItem['qty'])) * intval($qtyValue)),2);
    								}else{
    									$totalydsvalue .= round((floatval($metaArray['yds-per-unit']) * floatval($qtyValue)),2);
    								}
    							}else{
    								$totalydsvalue .= round(((floatval($metaArray['total-yds']) / floatval($quoteItem['qty'])) * intval($qtyValue)),2);
    							}
    							
    							
    							if($metaArray['specialpricing']=='1'){
    								if($quoteItem['override_active'] == 1){
    									$extendedprice=(floatval($quoteItem['override_price']) * floatval($qtyValue));
    								}else{
    									$extendedprice=(floatval($quoteItem['best_price'])*floatval($qtyValue));
    								}
    							}else{
    							    if($quoteItem['override_active'] == 1){
    									$extendedprice=(floatval($quoteItem['override_price']) * floatval($qtyValue));
    								}else{
    								    $extendedprice=number_format((floatval($quoteItem['pmi_adjusted']) * $qtyValue),2,'.','');
    								}
    							}
    							
    							
    							
    							if($quoteItem['product_type'] == 'track_systems'){
        							if($quoteItem['unit'] == 'linear feet'){
        								$totalLFvalue .= $qtyValue;
        							}else{
        								$totalLFvalue .= '---';
        							}
        						}else{
        							if(isset($metaArray['labor-billable'])){
        								$totalLFvalue .= (floatval($metaArray['labor-billable'])*floatval($qtyValue));
        							}else{
        								$totalLFvalue .= "---";
        							}
        						}
    						
    						if($orderRow['project_manager_id'] == 0 || is_null($orderRow['project_manager_id'])){
    						    $managerName='N/A';
    						}else{
    						    $pmLookup=$this->Users->get($orderRow['project_manager_id'])->toArray();
    						    $managerName=$pmLookup['first_name'].' '.$pmLookup['last_name'];
    						}
    						
    						//$totallfrichText = $totalLFvalue;
    						
    						
    						if($quoteItem['product_type'] == 'newcatchall-valance' || $quoteItem['product_type'] == 'newcatchall-cornice' || $quoteItem['calculator_used'] == 'straight-cornice' || $quoteItem['calculator_used'] == 'box-pleated'){
    						    $ttlfValue=$totalLFvalue;
    						    $cclfValue='';
    						}elseif($quoteItem['product_type'] == 'track_systems'){
    						    $ttlfValue='';
    						    $cclfValue='';
    						}else{
    						    $ttlfValue='';
    						    $cclfValue = $totalLFvalue;
    						}
    						
    						
    						
    						if(!is_null($orderRow['type_id']) && $orderRow['type_id'] > 0){
    						    $thisType=$this->QuoteTypes->get($orderRow['type_id'])->toArray();
    						    $thisOrderType=$thisType['type_label'];
    						}else{
    						    $thisOrderType='';
    						}
    							
    							//recalc and override the TOTAL LF, TOTAL YARDS, EXTENDED PRICE variables for [this qty]
    							
    							
    							$objPHPExcel->getActiveSheet()->SetCellValue('A'.$rowCount, $orderRow['order_number']);
    							
    							$objPHPExcel->getActiveSheet()->SetCellValue('B'.$rowCount, $orderRow['status']);
    							
    							$objPHPExcel->getActiveSheet()->SetCellValue('C'.$rowCount, $quoteNumberValue);
    							
    							$objPHPExcel->getActiveSheet()->SetCellValue('D'.$rowCount, $thisOrderType);
    							
    							$objPHPExcel->getActiveSheet()->SetCellValue('E'.$rowCount, $orderRow['stage']);
    							
    							$objPHPExcel->getActiveSheet()->SetCellValue('F'.$rowCount, $managerName);
    							
    							$objPHPExcel->getActiveSheet()->SetCellValue('G'.$rowCount, $customerValue);
    							$objPHPExcel->getActiveSheet()->SetCellValue('H'.$rowCount, $customerPOValue);
    							$objPHPExcel->getActiveSheet()->SetCellValue('I'.$rowCount, $facilityValue);
    							$objPHPExcel->getActiveSheet()->SetCellValue('J'.$rowCount, $shipDateValue);
    							$objPHPExcel->getActiveSheet()->getStyle('J'.$rowCount)->getNumberFormat()->setFormatCode('mm/dd/yyyy');
    							
    							$objPHPExcel->getActiveSheet()->SetCellValue('K'.$rowCount, $dateScheduled);
    							$objPHPExcel->getActiveSheet()->getStyle('K'.$rowCount)->getNumberFormat()->setFormatCode('mm/dd/yyyy');
    							
    							$objPHPExcel->getActiveSheet()->SetCellValue('L'.$rowCount, $thisbookingdate);
    							$objPHPExcel->getActiveSheet()->getStyle('L'.$rowCount)->getNumberFormat()->setFormatCode('mm/dd/yyyy');
    							
    							$objPHPExcel->getActiveSheet()->SetCellValue('M'.$rowCount, $batchValue);
    							$objPHPExcel->getActiveSheet()->SetCellValue('N'.$rowCount, $lineNumberValue);
    							$objPHPExcel->getActiveSheet()->SetCellValue('O'.$rowCount, $locationValue);
    							$objPHPExcel->getActiveSheet()->SetCellValue('P'.$rowCount, $qtyValue);
    							
    							$objPHPExcel->getActiveSheet()->SetCellValue('Q'.$rowCount, $classValue);
    							$objPHPExcel->getActiveSheet()->SetCellValue('R'.$rowCount, $subclassValue);
    							
    							
    							$objPHPExcel->getActiveSheet()->SetCellValue('S'.$rowCount, $unitValue);
    							$objPHPExcel->getActiveSheet()->SetCellValue('T'.$rowCount, $lineItemValue);
    							
    							$objPHPExcel->getActiveSheet()->SetCellValue('U'.$rowCount, $fabricrichText);
    							$objPHPExcel->getActiveSheet()->SetCellValue('V'.$rowCount, $colorrichText);
    							$objPHPExcel->getActiveSheet()->SetCellValue('W'.$rowCount, $cutwidthValue);
    							$objPHPExcel->getActiveSheet()->SetCellValue('X'.$rowCount, $finwidthValue);
    							$objPHPExcel->getActiveSheet()->SetCellValue('Y'.$rowCount, $lengthrichText);
    							
    							$objPHPExcel->getActiveSheet()->SetCellValue('Z'.$rowCount, $cclfValue);
    							
    							$objPHPExcel->getActiveSheet()->SetCellValue('AA'.$rowCount, $drapewidthsValue);
    							$objPHPExcel->getActiveSheet()->SetCellValue('AB'.$rowCount, $ttlfValue);
    							
    							$objPHPExcel->getActiveSheet()->SetCellValue('AC'.$rowCount, $ydsperunitValue);
    							$objPHPExcel->getActiveSheet()->SetCellValue('AD'.$rowCount, $totalydsvalue);
    							$objPHPExcel->getActiveSheet()->SetCellValue('AE'.$rowCount, $basePriceValue);
    							$objPHPExcel->getActiveSheet()->SetCellValue('AF'.$rowCount, $breakdownrichText);
    							$objPHPExcel->getActiveSheet()->SetCellValue('AG'.$rowCount, $adjustedColValue);
    							$objPHPExcel->getActiveSheet()->SetCellValue('AH'.$rowCount, $extendedprice);
    							
    							
    							$objPHPExcel->getActiveSheet()->SetCellValue('AI'.$rowCount, $dateProduced);
    							$objPHPExcel->getActiveSheet()->getStyle('AI'.$rowCount)->getNumberFormat()->setFormatCode('mm/dd/yyyy');
    							
    							$objPHPExcel->getActiveSheet()->SetCellValue('AJ'.$rowCount, $dateShipped);
    							$objPHPExcel->getActiveSheet()->getStyle('AJ'.$rowCount)->getNumberFormat()->setFormatCode('mm/dd/yyyy');
    							
    							$objPHPExcel->getActiveSheet()->SetCellValue('AK'.$rowCount, $trackingNumber);
    							$objPHPExcel->getActiveSheet()->SetCellValue('AL'.$rowCount, $dateInvoiced);
    							$objPHPExcel->getActiveSheet()->getStyle('AL'.$rowCount)->getNumberFormat()->setFormatCode('mm/dd/yyyy');
    							
    							$objPHPExcel->getActiveSheet()->SetCellValue('AM'.$rowCount, $linenotesrichText);
    							
    						
    							if($rowCount % 2 == 0){
    								$thisrowcolor='F8F8F8';
    							}else{
    								$thisrowcolor='FFFFFF';
    							}
    						
    							$objPHPExcel->getActiveSheet()->getStyle('A'.$rowCount.':AM'.$rowCount)->applyFromArray(
    								array(
    									'fill' => array(
    										'type' => \PHPExcel_Style_Fill::FILL_SOLID,
    										'color' => array('rgb' => $thisrowcolor)
    									)
    								)
    							);
    
    							$brlines=explode("<br",$itemDescription);
    							if(count($brlines) < 5){
    								$rowheight=90;
    							}elseif(count($brlines) >= 5 && count($brlines) < 8){
    								$rowheight=120;
    							}else{
    								$rowheight=145;
    							}
    
    							$objPHPExcel->getActiveSheet()->getRowDimension($rowCount)->setRowHeight($rowheight);
    
    							$rowCount++;
    						}
    						
    						
    						//then loop through BATCHED
    						foreach($loop['batches'] as $num => $batchloopitem){
    						    $totalLFvalue='';
    						    
    							if($type=='backlog' && ((isset($batchloopitem['invoicedTS']) && $batchloopitem['invoicedTS'] > 1000) && (isset($batchloopitem['shippedTS']) && $batchloopitem['shippedTS'] > 1000) )){
    								//ignore this row, it's already shipped and invoiced
    							}else{
    								$qtyValue=$batchloopitem['qty'];
    								$batchValue=$batchloopitem['batchid'];
    								
    								if($metaArray['specialpricing']=='1'){
    									if($quoteItem['override_active'] == 1){
    										$extendedprice=(floatval($quoteItem['override_price']) * floatval($qtyValue));
    									}else{
    										$extendedprice=(floatval($quoteItem['best_price'])*floatval($qtyValue));
    									}
    								}else{
    								    if($quoteItem['override_active'] == 1){
    										$extendedprice=(floatval($quoteItem['override_price']) * floatval($qtyValue));
    									}else{
    									    $extendedprice=number_format((floatval($quoteItem['pmi_adjusted']) * $qtyValue),2,'.','');
    									}
    								}
    								
    								$totalydsvalue='';
    								if($metaArray['lineitemtype'] == 'simpleproduct'){
    									$totalydsvalue .= round((floatval($metaArray['yds-per-unit']) * floatval($qtyValue)),2);
    								}elseif($metaArray['lineitemtype']=="custom"){
    									if(floatval($metaArray['total-yds']) >0){
    										$totalydsvalue .= round(((floatval($metaArray['total-yds']) / floatval($quoteItem['qty'])) * intval($qtyValue)),2);
    									}else{
    										$totalydsvalue .= round((floatval($metaArray['yds-per-unit']) * floatval($qtyValue)),2);
    									}
    								}else{
    									$totalydsvalue .= round(((floatval($metaArray['total-yds']) / floatval($quoteItem['qty'])) * intval($qtyValue)),2);
    								}
    
    
                                    if($quoteItem['product_type'] == 'track_systems'){
            							if($quoteItem['unit'] == 'linear feet'){
            								$totalLFvalue .= $qtyValue;
            							}else{
            								$totalLFvalue .= '---';
            							}
            						}else{
            							if(isset($metaArray['labor-billable'])){
            								$totalLFvalue .= (floatval($metaArray['labor-billable'])*floatval($qtyValue));
            							}else{
            								$totalLFvalue .= "---";
            							}
            						}
            						
            						//$totallfrichText = $totalLFvalue;
            						
            						if($quoteItem['product_type'] == 'newcatchall-valance' || $quoteItem['product_type'] == 'newcatchall-cornice' || $quoteItem['calculator_used'] == 'straight-cornice' || $quoteItem['calculator_used'] == 'box-pleated'){
            						    $ttlfValue=$totalLFvalue;
            						    $cclfValue='';
            						}elseif($quoteItem['product_type'] == 'track_systems'){
            						    $ttlfValue='';
            						    $cclfValue='';
            						}else{
            						    $ttlfValue='';
            						    $cclfValue = $totalLFvalue;
            						}
    								
    								
    								if($quoteItem['product_type'] == 'newcatchall-drapery'){
            						    $drapewidthsValue=$metaArray['total-billable-widths'];
            						}elseif($quoteItem['calculator_used'] == 'pinch-pleated' || $quoteItem['calculator_used'] == 'pinch-pleated-new'){
            						    $drapewidthsValue=(intval($quoteItem['qty']) * intval($metaArray['labor-widths']));
            						}else{
            						    $drapewidthsValue='';
            						}
    								
    								
    								$dateScheduled='';
    								$dateShipped='';
    								$trackingNumber='';
    								
    								
    								
    								$shipStatusesThisBatch=$this->OrderItemStatus->find('all',['conditions'=>['sherry_batch_id' => $batchValue,'status'=>'Shipped','shipment_id !=' => NULL,'shipment_id !=' => 0]])->toArray();
                    				foreach($shipStatusesThisBatch as $shipStatus){
                    					$shipment=$this->Shipments->get($shipStatus['shipment_id'])->toArray();
                    					$trackingNumber=$shipment['tracking_number'];
                    				}
    								
    								//look up whether this line has been Shipped, if so, populate the variable
            						$thisLineBatchesShipped=$this->OrderItemStatus->find('all',['conditions' => ['order_item_id' => $orderItem['id'], 'status' => 'Shipped', 'sherry_batch_id' => $batchValue]])->toArray();
            						foreach($thisLineBatchesShipped as $shippedBatch){
            							//$dateShipped = date('n/j/Y',$shippedBatch['time']);
            							$shipDateTimeObj=new \DateTime();
            						    $shipDateTimeObj->setTimestamp($shippedBatch['time']);
            							$dateShipped = \PHPExcel_Shared_Date::PHPToExcel($shipDateTimeObj);
            						}
            						
            						
            						$dateScheduled='';
            						$thisLineBatchesScheduled=$this->OrderItemStatus->find('all',['conditions' => ['order_item_id' => $orderItem['id'], 'status' => 'Scheduled', 'sherry_batch_id' => $batchValue]])->toArray();
            						foreach($thisLineBatchesScheduled as $scheduledBatch){
            							//$dateScheduled = date('n/j/Y',$scheduledBatch['time']);
            							$schedDateTimeObj=new \DateTime();
            						    $schedDateTimeObj->setTimestamp($scheduledBatch['time']);
            						    
            							$dateScheduled = \PHPExcel_Shared_Date::PHPToExcel($schedDateTimeObj);
            						}
            						
            						
            						$dateProduced='';
            						$thisLineBatchesCompleted=$this->OrderItemStatus->find('all',['conditions' => ['order_item_id' => $orderItem['id'], 'status' => 'Completed', 'sherry_batch_id' => $batchValue]])->toArray();
            						foreach($thisLineBatchesCompleted as $producedBatch){
            							//$dateProduced = date('n/j/Y',$producedBatch['time']);
            							$prodDateTimeObj=new \DateTime();
            						    $prodDateTimeObj->setTimestamp($producedBatch['time']);
            						    
            							$dateProduced = \PHPExcel_Shared_Date::PHPToExcel($prodDateTimeObj);
            						}
            						
            						$dateInvoiced='';
            						
            						//look up whether this line has been Invoiced, if so, populate the variable
            						$thisLineBatchesInvoiced=$this->OrderItemStatus->find('all',['conditions' => ['order_item_id' => $orderItem['id'], 'status' => 'Invoiced','sherry_batch_id'=>$batchValue]])->toArray();
            						foreach($thisLineBatchesInvoiced as $invoicedBatch){
            							//$dateInvoiced = date('n/j/Y',$invoicedBatch['time']);
            							$invDateTimeObj=new \DateTime();
            						    $invDateTimeObj->setTimestamp($invoicedBatch['time']);
            						    
            							$dateInvoiced = \PHPExcel_Shared_Date::PHPToExcel($invDateTimeObj);
            						}
    						
    						
    						
    								//recalc and override the TOTAL LF, TOTAL YARDS, EXTENDED PRICE variables for [this qty]
    								if($orderRow['project_manager_id'] == 0 || is_null($orderRow['project_manager_id'])){
            						    $managerName='N/A';
            						}else{
            						    $pmLookup=$this->Users->get($orderRow['project_manager_id'])->toArray();
            						    $managerName=$pmLookup['first_name'].' '.$pmLookup['last_name'];
            						}
    								
    								$objPHPExcel->getActiveSheet()->SetCellValue('A'.$rowCount, $orderRow['order_number']);
    								
    								$objPHPExcel->getActiveSheet()->SetCellValue('B'.$rowCount, $orderRow['status']);
    								
    								$objPHPExcel->getActiveSheet()->SetCellValue('C'.$rowCount, $quoteNumberValue);
    								
    								
    								if(!is_null($orderRow['type_id']) && $orderRow['type_id'] > 0){
            						    $thisType=$this->QuoteTypes->get($orderRow['type_id'])->toArray();
            						    $thisOrderType=$thisType['type_label'];
            						}else{
            						    $thisOrderType='';
            						}
    								
    								$objPHPExcel->getActiveSheet()->SetCellValue('D'.$rowCount, $thisOrderType);
    								
    								$objPHPExcel->getActiveSheet()->SetCellValue('E'.$rowCount, $orderRow['stage']);
    								
    								$objPHPExcel->getActiveSheet()->SetCellValue('F'.$rowCount, $managerName);
    								
    								$objPHPExcel->getActiveSheet()->SetCellValue('G'.$rowCount, $customerValue);
    								$objPHPExcel->getActiveSheet()->SetCellValue('H'.$rowCount, $customerPOValue);
    								$objPHPExcel->getActiveSheet()->SetCellValue('I'.$rowCount, $facilityValue);
    								$objPHPExcel->getActiveSheet()->SetCellValue('J'.$rowCount, $shipDateValue);
    								$objPHPExcel->getActiveSheet()->getStyle('J'.$rowCount)->getNumberFormat()->setFormatCode('mm/dd/yyyy');
    								
    								$objPHPExcel->getActiveSheet()->SetCellValue('K'.$rowCount, $dateScheduled);
    								$objPHPExcel->getActiveSheet()->getStyle('K'.$rowCount)->getNumberFormat()->setFormatCode('mm/dd/yyyy');
    								
    								$objPHPExcel->getActiveSheet()->SetCellValue('L'.$rowCount, $thisbookingdate);
    								$objPHPExcel->getActiveSheet()->getStyle('L'.$rowCount)->getNumberFormat()->setFormatCode('mm/dd/yyyy');
    								
    								$objPHPExcel->getActiveSheet()->SetCellValue('M'.$rowCount, $batchValue);
    								$objPHPExcel->getActiveSheet()->SetCellValue('N'.$rowCount, $lineNumberValue);
    								$objPHPExcel->getActiveSheet()->SetCellValue('O'.$rowCount, $locationValue);
    								$objPHPExcel->getActiveSheet()->SetCellValue('P'.$rowCount, $qtyValue);
    								
    								
    								$objPHPExcel->getActiveSheet()->SetCellValue('Q'.$rowCount, $classValue);
    								$objPHPExcel->getActiveSheet()->SetCellValue('R'.$rowCount, $subclassValue);
    								
    								
    								$objPHPExcel->getActiveSheet()->SetCellValue('S'.$rowCount, $unitValue);
    								$objPHPExcel->getActiveSheet()->SetCellValue('T'.$rowCount, $lineItemValue);
    								$objPHPExcel->getActiveSheet()->SetCellValue('U'.$rowCount, $fabricrichText);
    								$objPHPExcel->getActiveSheet()->SetCellValue('V'.$rowCount, $colorrichText);
    								$objPHPExcel->getActiveSheet()->SetCellValue('W'.$rowCount, $cutwidthValue);
    								$objPHPExcel->getActiveSheet()->SetCellValue('X'.$rowCount, $finwidthValue);
    								$objPHPExcel->getActiveSheet()->SetCellValue('Y'.$rowCount, $lengthrichText);
    								$objPHPExcel->getActiveSheet()->SetCellValue('Z'.$rowCount, $cclfValue);
    								
    								$objPHPExcel->getActiveSheet()->SetCellValue('AA'.$rowCount, $drapewidthsValue);
    							    $objPHPExcel->getActiveSheet()->SetCellValue('AB'.$rowCount, $ttlfValue);

    								$objPHPExcel->getActiveSheet()->SetCellValue('AC'.$rowCount, $ydsperunitValue);
    								$objPHPExcel->getActiveSheet()->SetCellValue('AD'.$rowCount, $totalydsvalue);
    								$objPHPExcel->getActiveSheet()->SetCellValue('AE'.$rowCount, $basePriceValue);
    								$objPHPExcel->getActiveSheet()->SetCellValue('AF'.$rowCount, $breakdownrichText);
    								$objPHPExcel->getActiveSheet()->SetCellValue('AG'.$rowCount, $adjustedColValue);
    								$objPHPExcel->getActiveSheet()->SetCellValue('AH'.$rowCount, $extendedprice);
    							
    								
    								
    								$objPHPExcel->getActiveSheet()->SetCellValue('AI'.$rowCount, $dateProduced);
    								$objPHPExcel->getActiveSheet()->getStyle('AI'.$rowCount)->getNumberFormat()->setFormatCode('mm/dd/yyyy');
    								
    								$objPHPExcel->getActiveSheet()->SetCellValue('AJ'.$rowCount, $dateShipped);
    								$objPHPExcel->getActiveSheet()->getStyle('AJ'.$rowCount)->getNumberFormat()->setFormatCode('mm/dd/yyyy');
    								
    								$objPHPExcel->getActiveSheet()->SetCellValue('AK'.$rowCount, $trackingNumber);
    								$objPHPExcel->getActiveSheet()->SetCellValue('AL'.$rowCount, $dateInvoiced);
    								$objPHPExcel->getActiveSheet()->getStyle('AL'.$rowCount)->getNumberFormat()->setFormatCode('mm/dd/yyyy');
    								
    								$objPHPExcel->getActiveSheet()->SetCellValue('AM'.$rowCount, $linenotesrichText);
    								
    								
    
    
    								if($rowCount % 2 == 0){
    									$thisrowcolor='F8F8F8';
    								}else{
    									$thisrowcolor='FFFFFF';
    								}
    
    								$objPHPExcel->getActiveSheet()->getStyle('A'.$rowCount.':AM'.$rowCount)->applyFromArray(
    									array(
    										'fill' => array(
    											'type' => \PHPExcel_Style_Fill::FILL_SOLID,
    											'color' => array('rgb' => $thisrowcolor)
    										)
    									)
    								);
    
    								$brlines=explode("<br",$itemDescription);
    								if(count($brlines) < 5){
    									$rowheight=90;
    								}elseif(count($brlines) >= 5 && count($brlines) < 8){
    									$rowheight=120;
    								}else{
    									$rowheight=145;
    								}
    
    								$objPHPExcel->getActiveSheet()->getRowDimension($rowCount)->setRowHeight($rowheight);
    
    								$rowCount++;
    							}
    						
    						}
						
					    }
					}
					
				}
				
			}
			
			$objPHPExcel->getActiveSheet()->getStyle('A1:A'.$rowCount)->getAlignment()->setHorizontal('center');
			$objPHPExcel->getActiveSheet()->getStyle('B1:B'.$rowCount)->getAlignment()->setHorizontal('center');
			$objPHPExcel->getActiveSheet()->getStyle('C1:C'.$rowCount)->getAlignment()->setHorizontal('center');
            $objPHPExcel->getActiveSheet()->getStyle('D1:D'.$rowCount)->getAlignment()->setHorizontal('center');
            $objPHPExcel->getActiveSheet()->getStyle('E1:E'.$rowCount)->getAlignment()->setHorizontal('center');
            $objPHPExcel->getActiveSheet()->getStyle('F1:F'.$rowCount)->getAlignment()->setHorizontal('center');
			$objPHPExcel->getActiveSheet()->getStyle('G1:G'.$rowCount)->getAlignment()->setHorizontal('center');
			$objPHPExcel->getActiveSheet()->getStyle('H1:H'.$rowCount)->getAlignment()->setHorizontal('center');
			$objPHPExcel->getActiveSheet()->getStyle('I1:I'.$rowCount)->getAlignment()->setHorizontal('center');
			$objPHPExcel->getActiveSheet()->getStyle('J1:J'.$rowCount)->getAlignment()->setHorizontal('center');
			$objPHPExcel->getActiveSheet()->getStyle('K1:K'.$rowCount)->getAlignment()->setHorizontal('center');
			$objPHPExcel->getActiveSheet()->getStyle('L1:L'.$rowCount)->getAlignment()->setHorizontal('center');
			$objPHPExcel->getActiveSheet()->getStyle('M1:M'.$rowCount)->getAlignment()->setHorizontal('center');
			$objPHPExcel->getActiveSheet()->getStyle('N1:N'.$rowCount)->getAlignment()->setHorizontal('center');
			$objPHPExcel->getActiveSheet()->getStyle('O1:O'.$rowCount)->getAlignment()->setHorizontal('center');
			$objPHPExcel->getActiveSheet()->getStyle('P1:P'.$rowCount)->getAlignment()->setHorizontal('center');
			$objPHPExcel->getActiveSheet()->getStyle('Q1:Q'.$rowCount)->getAlignment()->setHorizontal('center');
			$objPHPExcel->getActiveSheet()->getStyle('R1:R'.$rowCount)->getAlignment()->setHorizontal('center');
			$objPHPExcel->getActiveSheet()->getStyle('S1:S'.$rowCount)->getAlignment()->setHorizontal('center');
			$objPHPExcel->getActiveSheet()->getStyle('T1:T'.$rowCount)->getAlignment()->setHorizontal('center');
			$objPHPExcel->getActiveSheet()->getStyle('U1:U'.$rowCount)->getAlignment()->setHorizontal('center');
			$objPHPExcel->getActiveSheet()->getStyle('V1:V'.$rowCount)->getAlignment()->setHorizontal('center');
			$objPHPExcel->getActiveSheet()->getStyle('W1:W'.$rowCount)->getAlignment()->setHorizontal('center');
			$objPHPExcel->getActiveSheet()->getStyle('X1:X'.$rowCount)->getAlignment()->setHorizontal('center');
			$objPHPExcel->getActiveSheet()->getStyle('Y1:Y'.$rowCount)->getAlignment()->setHorizontal('center');
			$objPHPExcel->getActiveSheet()->getStyle('Z1:Z'.$rowCount)->getAlignment()->setHorizontal('center');
			$objPHPExcel->getActiveSheet()->getStyle('AA1:AA'.$rowCount)->getAlignment()->setHorizontal('center');
			$objPHPExcel->getActiveSheet()->getStyle('AB1:AB'.$rowCount)->getAlignment()->setHorizontal('center');
			$objPHPExcel->getActiveSheet()->getStyle('AC1:AC'.$rowCount)->getAlignment()->setHorizontal('center');
			$objPHPExcel->getActiveSheet()->getStyle('AD1:AD'.$rowCount)->getAlignment()->setHorizontal('center');
			$objPHPExcel->getActiveSheet()->getStyle('AE1:AE'.$rowCount)->getAlignment()->setHorizontal('center');
			$objPHPExcel->getActiveSheet()->getStyle('AF1:AF'.$rowCount)->getAlignment()->setHorizontal('center');
			$objPHPExcel->getActiveSheet()->getStyle('AG1:AG'.$rowCount)->getAlignment()->setHorizontal('center');
			$objPHPExcel->getActiveSheet()->getStyle('AH1:AH'.$rowCount)->getAlignment()->setHorizontal('center');
			$objPHPExcel->getActiveSheet()->getStyle('AI1:AI'.$rowCount)->getAlignment()->setHorizontal('center');
			$objPHPExcel->getActiveSheet()->getStyle('AJ1:AJ'.$rowCount)->getAlignment()->setHorizontal('center');
			$objPHPExcel->getActiveSheet()->getStyle('AK1:AK'.$rowCount)->getAlignment()->setHorizontal('center');
			$objPHPExcel->getActiveSheet()->getStyle('AL1:AL'.$rowCount)->getAlignment()->setHorizontal('center');
			$objPHPExcel->getActiveSheet()->getStyle('AM1:AM'.$rowCount)->getAlignment()->setHorizontal('left');
			
			
			$objPHPExcel->getActiveSheet()->getStyle('A1:AM'.$rowCount)->getAlignment()->setVertical(\PHPExcel_Style_Alignment::VERTICAL_TOP );
			
			$objPHPExcel->getActiveSheet()->getStyle('A1:AM'.$rowCount)->getAlignment()->setWrapText(true);
			
			
			
			$objPHPExcel->getActiveSheet()->getStyle("A1:AM".$rowCount)->applyFromArray(
				array(
					'borders' => array(
						'allborders' => array(
							'style' => \PHPExcel_Style_Border::BORDER_THIN,
							'color' => array('rgb' => '111111')
						)
					)
				)
			);
			
			$objPHPExcel->getActiveSheet()->freezePane('A2');
            
            // Redirect output to a client’s web browser (Excel5)
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="Completed Orders Report - '.$this->request->data['report_date_start'].' thru '.$this->request->data['report_date_end'].'.xlsx"');
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
            
	        
	        
	    }else{
	        //show the form
	        //get all the users for filtering by user
	        $allUsers=$this->Users->find('all',['conditions' => ['status'=>'Active'],'order'=>['first_name'=>'asc','last_name'=>'asc']])->toArray();
	        $this->set('allUsers',$allUsers);
	        
	        $allCustomers=$this->Customers->find('all',['order'=>['company_name'=>'asc']])->toArray();
	        $this->set('allCustomers',$allCustomers);
	        
	        $quoteTypes=$this->QuoteTypes->find('all',['order'=>['type_label'=>'asc']])->toArray();
	        $this->set('quoteTypes',$quoteTypes);
	    }
	    
	}
	
	
	
	public function qbreconciliation(){
	    if($this->request->data){
	        $this->autoRender=false;
	        
	        $productMap=array(
        		'cubicle-curtain'=>'Cubicle Curtain',
    			'box-pleated'=>'Box Pleated Valance',
    			'straight-cornice'=>'Straight Cornice',
    			'bedspread'=>'Calculated Bedspread',
    			'bedspread-manual'=>'Manually Entered Bedspread',
    			'pinch-pleated' => 'Pinch Pleated Drapery'
    		);
		
	        $startTS=strtotime($this->request->data['report_date_start'].' 00:00:00');
			$endTS=strtotime($this->request->data['report_date_end'].' 23:59:59');
			
	        
	        $db=ConnectionManager::get('default');
		
    	    $query = "SELECT * FROM `orders` WHERE `status` != 'Canceled' AND `created` >= ".$startTS." AND `created` <= ".$endTS;

    	    if(strlen($this->request->data['customer_id']) > 0){
    	        $query .= " AND `customer_id`=".$this->request->data['customer_id'];
    	    }
    	    
    	    if(strlen($this->request->data['order_type']) > 0){
    	        $query .= " AND `type_id` = ".$this->request->data['order_type'];
    	    }
    	    
    	    $query .= " ORDER BY `created` ASC";
	    
	        $queryRun=$db->execute($query);
		    $ordersLookup=$queryRun->fetchAll('assoc');
	        
	        
	        require($_SERVER['DOCUMENT_ROOT']."/vendor/phpoffice/phpexcel/Classes/PHPExcel.php");
            require($_SERVER['DOCUMENT_ROOT']."/vendor/phpoffice/phpexcel/Classes/PHPExcel/Writer/Excel2007.php");
            
    	    $objPHPExcel = new \PHPExcel();
            $objPHPExcel->setActiveSheetIndex(0);
			
			
			$objPHPExcel->getActiveSheet()->SetCellValue('A1', 'ORDER #');
			
			$objPHPExcel->getActiveSheet()->SetCellValue('B1', 'ORDER STATUS');
			
            //$objPHPExcel->getActiveSheet()->SetCellValue('C1', 'QUOTE #');
            
            $objPHPExcel->getActiveSheet()->SetCellValue('C1', 'ORDER TYPE');
            
            $objPHPExcel->getActiveSheet()->SetCellValue('D1','STAGE');
            
            $objPHPExcel->getActiveSheet()->SetCellValue('E1','PM');
			$objPHPExcel->getActiveSheet()->SetCellValue('F1', 'CUSTOMER');
			$objPHPExcel->getActiveSheet()->SetCellValue('G1', 'CUSTOMER PO');
			$objPHPExcel->getActiveSheet()->SetCellValue('H1', 'RECIPIENT');
			$objPHPExcel->getActiveSheet()->SetCellValue('I1', 'SHIP DATE');
			
			$objPHPExcel->getActiveSheet()->SetCellValue('J1', 'SCHEDULED');
			
			$objPHPExcel->getActiveSheet()->SetCellValue('K1', 'BOOK DATE');
			$objPHPExcel->getActiveSheet()->SetCellValue('L1', 'BATCH');
			$objPHPExcel->getActiveSheet()->SetCellValue('M1', 'LINE');
			
			//$objPHPExcel->getActiveSheet()->SetCellValue('O1', 'LOCATION');
		
			$objPHPExcel->getActiveSheet()->SetCellValue('N1', 'QTY');
			
			$objPHPExcel->getActiveSheet()->SetCellValue('O1', 'CLASS');
			$objPHPExcel->getActiveSheet()->SetCellValue('P1', 'SUBCLASS');
			
			$objPHPExcel->getActiveSheet()->SetCellValue('Q1', 'UNIT');
			$objPHPExcel->getActiveSheet()->SetCellValue('R1', 'LINE ITEM');
			$objPHPExcel->getActiveSheet()->SetCellValue('S1', 'FABRIC');
			$objPHPExcel->getActiveSheet()->SetCellValue('T1', 'COLOR');
			
			/*
			$objPHPExcel->getActiveSheet()->SetCellValue('W1', 'CUT WIDTH');
			$objPHPExcel->getActiveSheet()->SetCellValue('X1', 'FIN WIDTH');
			
			$objPHPExcel->getActiveSheet()->SetCellValue('Y1', 'LENGTH');
			
			$objPHPExcel->getActiveSheet()->SetCellValue('Z1', 'CC LF');
			
			$objPHPExcel->getActiveSheet()->SetCellValue('AA1', 'DRP WIDTHS');
			$objPHPExcel->getActiveSheet()->SetCellValue('AB1', 'TOP TR LF');
			
			$objPHPExcel->getActiveSheet()->SetCellValue('AC1', 'YDS / UNIT');
			*/
			
			$objPHPExcel->getActiveSheet()->SetCellValue('U1', 'TOTAL YARDS');
			$objPHPExcel->getActiveSheet()->SetCellValue('V1', 'BASE');
			//$objPHPExcel->getActiveSheet()->SetCellValue('AF1', 'TIERS');
			$objPHPExcel->getActiveSheet()->SetCellValue('W1', 'ADJ PRICE');
			$objPHPExcel->getActiveSheet()->SetCellValue('X1', 'EXT PRICE');

            
            
            $objPHPExcel->getActiveSheet()->SetCellValue('Y1', 'PRODUCED');
			$objPHPExcel->getActiveSheet()->SetCellValue('Z1', 'SHIPPED');
			
			$objPHPExcel->getActiveSheet()->SetCellValue('AA1', 'TRACKING #');
			
			$objPHPExcel->getActiveSheet()->SetCellValue('AB1', 'INVOICED');
			
			$objPHPExcel->getActiveSheet()->SetCellValue('AC1', 'INVOICE #');
			
			$objPHPExcel->getActiveSheet()->SetCellValue('AD1', 'LINE NOTES');
			
			
			
			
			$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(15);
			$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(15);
			//$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(15);
			$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(15);
			$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(25);
			$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(15);
			$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(40);
			$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(25);
			$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(55);
			$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(15);
		
			$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(15);
			
			$objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(15);
			$objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(15);
			$objPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth(15);
			
			//$objPHPExcel->getActiveSheet()->getColumnDimension('O')->setWidth(20);
			
			$objPHPExcel->getActiveSheet()->getColumnDimension('N')->setWidth(15);
			
			
			$objPHPExcel->getActiveSheet()->getColumnDimension('O')->setWidth(22);
			$objPHPExcel->getActiveSheet()->getColumnDimension('P')->setWidth(22);
			
			$objPHPExcel->getActiveSheet()->getColumnDimension('Q')->setWidth(15);
			$objPHPExcel->getActiveSheet()->getColumnDimension('R')->setWidth(75);
	
			$objPHPExcel->getActiveSheet()->getColumnDimension('S')->setWidth(20);
			$objPHPExcel->getActiveSheet()->getColumnDimension('T')->setWidth(20);
			//$objPHPExcel->getActiveSheet()->getColumnDimension('W')->setWidth(20);
			//$objPHPExcel->getActiveSheet()->getColumnDimension('X')->setWidth(20);
			
			//$objPHPExcel->getActiveSheet()->getColumnDimension('Y')->setWidth(20);
			//$objPHPExcel->getActiveSheet()->getColumnDimension('Z')->setWidth(20);
			
			//$objPHPExcel->getActiveSheet()->getColumnDimension('AA')->setWidth(15);
			//$objPHPExcel->getActiveSheet()->getColumnDimension('AB')->setWidth(15);
			
			//$objPHPExcel->getActiveSheet()->getColumnDimension('AC')->setWidth(20);
			
			$objPHPExcel->getActiveSheet()->getColumnDimension('U')->setWidth(20);
			$objPHPExcel->getActiveSheet()->getColumnDimension('V')->setWidth(20);
			//$objPHPExcel->getActiveSheet()->getColumnDimension('AF')->setWidth(30);
	
			$objPHPExcel->getActiveSheet()->getColumnDimension('W')->setWidth(15);
			$objPHPExcel->getActiveSheet()->getColumnDimension('X')->setWidth(15);
			
			
			$objPHPExcel->getActiveSheet()->getColumnDimension('Y')->setWidth(15);
			
			$objPHPExcel->getActiveSheet()->getColumnDimension('Z')->setWidth(15);
			$objPHPExcel->getActiveSheet()->getColumnDimension('AA')->setWidth(29);
			$objPHPExcel->getActiveSheet()->getColumnDimension('AB')->setWidth(15);
			
			$objPHPExcel->getActiveSheet()->getColumnDimension('AC')->setWidth(25); //INV#
			
			$objPHPExcel->getActiveSheet()->getColumnDimension('AD')->setWidth(70);
			

			
			$objPHPExcel->getActiveSheet()->getRowDimension('1')->setRowHeight(22);
			$objPHPExcel->getActiveSheet()->getStyle('A1:AD1')->getAlignment()->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);
			
			$objPHPExcel->getActiveSheet()->getStyle('A1:AD1')->applyFromArray(
				array(
					'fill' => array(
						'type' => \PHPExcel_Style_Fill::FILL_SOLID,
						'color' => array('rgb' => 'F8CBAD')
					)
				)
			);
			
			
            $rowCount = 2;
			
			$outputArr=array();
			
			foreach($ordersLookup as $orderRow){
			    
			    $quoteItemsLoop=$this->QuoteLineItems->find('all',['conditions'=>['quote_id' => $orderRow['quote_id']],'order'=>['line_number'=>'asc']])->toArray();
				
				$quoteData=$this->Quotes->get($orderRow['quote_id'])->toArray();
				
				$customer=$this->Customers->get($orderRow['customer_id'])->toArray();
				$thisCustomer=$this->Customers->get($orderRow['customer_id'])->toArray();
				
				foreach($quoteItemsLoop as $quoteItem){
				    
				    $thisClass=$this->ProductClasses->get($quoteItem['product_class'])->toArray();
				    $classValue=$thisClass['class_name'];
				    
				    
				    $thisSubclass=$this->ProductSubclasses->get($quoteItem['product_subclass'])->toArray();
				    $subclassValue=$thisSubclass['subclass_name'];
				    
				    
					if($quoteItem['parent_line'] == 0){
					    
					$orderItemFind=$this->OrderItems->find('all',['conditions' => ['quote_line_item_id' => $quoteItem['id']]])->toArray();
					foreach($orderItemFind as $orderItem){
					    
    						//look for BATCHES, if found, loop through those, then loop through the remainders or unbatched items
    						$batchesScheduled=$this->OrderItemStatus->find('all',['conditions' => ['order_item_id' => $orderItem['id'], 'status IN' => ['Scheduled','Completed','Invoiced','Shipped']]])->toArray();
    						$qtyShipped=0;
    						$qtyInvoiced=0;
    						$qtyCompleted=0;
    						$qtyScheduled=0;
    						
    						$loop=array(
    							'batches' => array(),
    							'unbatched' => 0
    						);
    						
    						$scheduledBatchesThisLine=0;
    						
    						foreach($batchesScheduled as $batchData){
    							switch($batchData['status']){
    								case 'Shipped':
    									$qtyShipped=($qtyShipped + intval($batchData['qty_involved']));
    								break;
    								case 'Invoiced':
    									$qtyInvoiced=($qtyInvoiced + intval($batchData['qty_involved']));
    								break;
    								case 'Scheduled':
    									$qtyScheduled=($qtyScheduled + intval($batchData['qty_involved']));
    									$loop['batches'][]=array('batchid'=>$batchData['sherry_batch_id'],'qty'=>$batchData['qty_involved'],'scheduledTS'=>$batchData['time']);
    									$scheduledBatchesThisLine++;
    								break;
    								case 'Completed':
    									$qtyCompleted=($qtyCompleted + intval($batchData['qty_involved']));
    								break;
    							}
    						}
    						
    						//loop back through again to add Shipped or Invoiced timestamps to batches that have them
    						foreach($batchesScheduled as $batchData){
    							switch($batchData['status']){
    								case 'Shipped':
    									foreach($loop['batches'] as $num => $batchloopitem){
    										if($batchloopitem['batchid'] == $batchData['sherry_batch_id']){
    											$loop['batches'][$num]['shippedTS'] = $batchData['time'];
    										}
    									}
    								break;
    								case 'Invoiced':
    									foreach($loop['batches'] as $num => $batchloopitem){
    										if($batchloopitem['batchid'] == $batchData['sherry_batch_id']){
    											$loop['batches'][$num]['invoicedTS'] = $batchData['time'];
    										}
    									}
    								break;
    							}
    						}
    						
    						$remainingUnscheduled=(intval($quoteItem['qty']) - $qtyScheduled);
    						$remainingUninvoiced=(intval($quoteItem['qty']) - $qtyInvoiced);
    						$remainingUnshipped = (intval($quoteItem['qty']) - $qtyShipped);
    						$remainingIncompleteScheduled= (intval($quoteItem['qty']) - $qtyCompleted);
    						
    						$loop['unbatched']=$remainingUnscheduled;
    					
    						
    						$itemMetas=$this->QuoteLineItemMeta->find('all',['conditions'=>['quote_item_id'=>$quoteItem['id']]])->toArray();
    						$metaArray=array();
    						foreach($itemMetas as $meta){
    							$metaArray[$meta['meta_key']]=$meta['meta_value'];
    						}
    						
    						
    						
    						if($quoteItem['product_type'] != 'custom' && $quoteItem['product_type'] != 'services' && $quoteItem['product_type'] != 'track_systems'){
    							
    							
    							if($metaArray['fabrictype'] != 'none' && $metaArray['fabrictype'] != 'typein'){
    								
    								if(isset($metaArray['fabricid']) && strlen(trim($metaArray['fabricid'])) >0 && $metaArray['fabricid'] != '0'){
    									$thisFabric=$this->Fabrics->get($metaArray['fabricid'])->toArray();
    								}else{
    									$thisFabric=array('fabric_name'=>'','color'=>'');
    								}
    								
    								//$thisFabric=$this->Fabrics->get($metaArray['fabricid'])->toArray();
    							}elseif($metaArray['fabrictype'] == 'typein'){
    							    $thisFabric=array('fabric_name'=>$metaArray['fabric_name'],'color'=>$metaArray['fabric_color']);
    							}else{
    								$thisFabric=array('fabric_name'=>'','color'=>'');
    							}
    						}else{
    							if($metaArray['fabrictype'] != 'none' && $metaArray['fabrictype'] != 'typein'){
    								if(isset($metaArray['fabricid']) && strlen(trim($metaArray['fabricid'])) >0 && $metaArray['fabricid'] != '0'){
    									$thisFabric=$this->Fabrics->get($metaArray['fabricid'])->toArray();
    								}else{
    									$thisFabric=array('fabric_name'=>'','color'=>'');
    								}
    							}elseif($metaArray['fabrictype'] == 'typein'){
    							    $thisFabric=array('fabric_name'=>$metaArray['fabric_name'],'color'=>$metaArray['fabric_color']);
    							}else{
    								$thisFabric=array('fabric_name'=>'','color'=>'');
    							}
    						}
    						
    						
    						
    						$thisbookingdate='';
    
    						//lookup the original Order Item Status for this line item and get the date value
    						$itemstatuslookup=$this->OrderItemStatus->find('all',['conditions' => ['order_item_id' => $orderItem['id'],'status' => 'Not Started']])->toArray();
    						foreach($itemstatuslookup as $itemstatusrow){
    							//$thisbookingdate=date('n/j/Y',$itemstatusrow['time']);
    							$bookDateTimeObj=new \DateTime();
    							$bookDateTimeObj->setTimestamp($itemstatusrow['time']);
    							
    							$thisbookingdate=\PHPExcel_Shared_Date::PHPToExcel($bookDateTimeObj);
    						}
    
    
    						$customerValue=$thisCustomer['company_name'];
    						$customerPOValue=$orderRow['po_number'];
    						$facilityValue=$orderRow['facility'];
    						
    						
    						$quoteNumberValue='';
    						if($quoteData['revision'] > 0){
    							$quoteNumberValue=$quoteData['quote_number']."\n[REV ".$quoteData['revision']."]";
    						}else{
    							$quoteNumberValue=$quoteData['quote_number'];
    						}
    						
    						
    						
    						if($orderRow['due'] < 1000){
    							$shipDateValue='';
    						}else{
    							//$shipDateValue=date('n/d/Y',$orderRow['due']);
    							$dueDateTimeObj=new \DateTime();
            					$dueDateTimeObj->setTimestamp($orderRow['due']);
            					$shipDateValue = \PHPExcel_Shared_Date::PHPToExcel($dueDateTimeObj);
    						}
    
    						$lineNumberValue=$quoteItem['line_number'];
    						$locationValue=$quoteItem['room_number'];
    						$unitValue=$quoteItem['unit'];
    						$itemDescription='';
    						
    						///////////////////////////////////////////////////////////////////////////////////////////////////////////////
    						
    						if($metaArray['lineitemtype']=='calculator'){
    							if($metaArray['calculator-used']=="straight-cornice"){
    								$itemDescription .= $metaArray['cornice-type']." Cornice";
    								if($metaArray['railroaded'] == '1'){
    									$itemDescription .= "<br>Fabric Railroaded";
    								}else{
    									$itemDescription .= "<br>Fabric Vertically Seamed";
    								}
    								$thisLining=$this->Linings->get($metaArray['linings_id'])->toArray();
    								$itemDescription .= "<br><b>Lining:</b> ".$thisLining['short_title'];
    								$welts='';
    								if($metaArray['welt-top']=='1' && $metaArray['welt-bottom']=='1'){
    									$welts = "Top + Bottom";
    								}else{
    									if($metaArray['welt-top']=='1'){
    										$welts = "Top Only";
    									}elseif($metaArray['welt-bottom'] == '1'){
    										$welts = "Bottom Only";
    									}
    								}
    								if($welts != ''){
    									$itemDescription .= "<br><b>Welts:</b> ".$welts;
    								}else{
    									$itemDescription .= "<br><b>Welts:</b> None";
    								}
    								if($metaArray['individual-nailheads'] == '1'){
    									$itemDescription .= "<br>Individual Nailheads";
    								}
    								if($metaArray['nailhead-trim'] == '1'){
    									$itemDescription .= "<br>Nailhead Trim";
    								}
    								if($metaArray['covered-buttons'] == '1'){
    									$itemDescription .= "<br>".$metaArray['covered-buttons-count']." Covered Buttons";
    								}
    
    								if($metaArray['horizontal-straight-banding'] == '1'){
    									$itemDescription .= "<br>".$metaArray['horizontal-straight-banding-count']." H Straight Banding";
    								}	
    								if($metaArray['horizontal-shaped-banding'] == '1'){
    									$itemDescription .= "<br>".$metaArray['horizontal-shaped-banding-count']." H Shaped Banding";
    								}
    								if($metaArray['extra-welts'] == '1'){
    									$itemDescription .= "<br>".$metaArray['extra-welts-count']." Extra Welts";
    								}	
    								if($metaArray['trim-sewn-on'] == '1'){
    									$itemDescription .= "<br>".$metaArray['trim-lf']." LF Sewn-On Trim";
    								}
    								if($metaArray['tassels'] == '1'){
    									$itemDescription .= "<br>".$metaArray['tassels-count']." Tassels";
    								}
    								if($metaArray['drill-holes'] == '1'){
    									$itemDescription .= "<br>".$metaArray['drill-hole-count']." Drill Holes";
    								}
    								if(isset($metaArray['vertical-repeat']) && floatval($metaArray['vertical-repeat']) >0){
    									$itemDescription .= "<br>Matched Repeat (".$metaArray['vertical-repeat']."&quot;)";
    								}
    							}elseif($metaArray['calculator-used']=="bedspread" || $metaArray['calculator-used'] == "bedspread-manual"){
    								$itemDescription .= $productMap[$metaArray['calculator-used']];
    								if($metaArray['railroaded'] == '1'){
    									$itemDescription .= "<br>Fabric Railroaded";
    								}else{
    									$itemDescription .= "<br>Fabric Up-the-Roll";
    								}
    
    								$itemDescription .= "<br>";
    								if(isset($metaArray['style']) && strlen(trim($metaArray['style'])) >0){
    									$itemDescription .= "Style: ".$metaArray['style'];
    								}
    								if(isset($metaArray['quilted']) && $metaArray['quilted']=='1'){
    									$itemDescription .= "<br>Quilted";
    									if(isset($metaArray['quilting-pattern']) && strlen(trim($metaArray['quilting-pattern'])) >0){
    										$itemDescription .= ", ".$metaArray['quilting-pattern'];
    									}
    									if(isset($metaArray['matching-thread']) && $metaArray['matching-thread'] == '1'){
    										$itemDescription .= ", Matching Thread";
    									}
    									$itemDescription .= ", ".$thisFabric['bs_backing_material']." Backing";
    								}else{
    									$itemDescription .= "<br>Unquilted";
    								}
    
    								$itemDescription .= "<br>Mattress: ";
    								if(!isset($metaArray['custom-top-width-mattress-w'])){
    									$itemDescription .= "36&quot;";
    								}else{
    									$itemDescription .= $metaArray['custom-top-width-mattress-w']."&quot;";
    								}
    
    								if(isset($metaArray['vertical-repeat']) && floatval($metaArray['vertical-repeat']) >0){
    									$itemDescription .= "<br>Matched Repeat (".$metaArray['vertical-repeat']."&quot;)";
    								}
    							}elseif($metaArray['calculator-used']=="box-pleated"){
    								$itemDescription .= $metaArray['valance-type']." Valance";
    
    								if($metaArray['railroaded'] == '1'){
    									$itemDescription .= "<br>Fabric Railroaded";
    								}else{
    									$itemDescription .= "<br>Fabric Vertically Seamed";
    								}
    								$itemDescription .= "<br>".$metaArray['pleats']." Pleats";
    
    								$thisLining=$this->Linings->get($metaArray['linings_id'])->toArray();
    								$itemDescription .= "<br><b>Lining:</b> ".$thisLining['short_title'];
    
    								if($metaArray['straight-banding']==1){
    									$itemDescription .= "<br>Straight Banding";
    								}
    								if($metaArray['shaped-banding']==1){
    									$itemDescription .= "<Br>Shaped Banding";
    								}
    								if($metaArray['trim-sewn-on']==1){
    									$itemDescription .= "<br>Sewn-On Trim";
    								}
    								if($metaArray['welt-covered-in-fabric'] == 1){
    									$itemDescription .= "<br>Welt Covered In Fabric";
    								}
    								if($metaArray['contrast-fabric-inside-pleat'] == 1){
    									$itemDescription .= "<br>Contrast Fabric Inside Pleat";
    								}
    								if(isset($metaArray['vert-repeat']) && floatval($metaArray['vert-repeat']) >0){
    									$itemDescription .= "<br>Matched Repeat (".$metaArray['vert-repeat']."&quot;)";
    								}
    							}elseif($metaArray['calculator-used']=="cubicle-curtain"){
    								$itemDescription .= $productMap[$metaArray['calculator-used']];
    								if($metaArray['railroaded'] == '1'){
    									$itemDescription .= "<br>Fabric Railroaded";
    								}else{
    									$itemDescription .= "<br>Fabric Vertically Seamed";
    								}
    								if($metaArray['mesh'] > 0 && $metaArray['mesh'] != ''){
    									$itemDescription .= "<br><b>Mesh:</b> ".$metaArray['mesh']."&quot; ".$metaArray['mesh-color']." (".str_replace(" Mesh","",$metaArray['mesh-type']).")";
    								}
    								if($metaArray['mesh-type'] == 'None'){
    									$itemDescription .= "<br>NO MESH";
    								}
    								if($metaArray['mesh-type'] == 'Integral Mesh'){
    									$itemDescription .= "<br>INTEGRAL MESH";
    								}
    								if($metaArray['liner'] == 1){
    									$itemDescription .= "<br>Liner";
    								}
    								if($metaArray['nylon-mesh']==1){
    									$itemDescription .= "<br>Nylon Mesh";
    								}
    								if($metaArray['angled-mesh']==1){
    									$itemDescription .= "<br>Angled Mesh";
    								}
    								if($metaArray['mesh-frame'] != 'No Frame'){
    									$itemDescription .= "<br><b>Mesh Frame:</b> ".$metaArray['mesh-frame'];
    								}
    								if($metaArray['hidden-mesh'] == 1){
    									$itemDescription .= "<br>Hidden Mesh";
    								}
    
    								if($metaArray['snap-tape'] != "None"){
    									$itemDescription .= "<br>".$metaArray['snap-tape']." Snap Tape (".$metaArray['snaptape-lf'].")";
    								}
    								if($metaArray['velcro'] != 'None'){
    									$itemDescription .= "<br>".$metaArray['velcro']." Velcro (".$metaArray['velcro-lf']." LF)";
    								}
    								if($metaArray['weights']==1){
    									$itemDescription .= "<br>".$metaArray['weight-count']." Weights";
    								}
    								if($metaArray['magnets']==1){
    									$itemDescription .= "<br>".$metaArray['magnet-count']." Magnets";
    								}
    								if($metaArray['banding'] == 1){
    									$itemDescription .= "<br>Banding";
    								}
    								if($metaArray['buttonholes'] == 1){
    									$itemDescription .= "<br>".$metaArray['buttonhole-count']." Buttonholes";
    								}
    								if(isset($metaArray['vertical-repeat']) && floatval($metaArray['vertical-repeat']) >0){
    									$itemDescription .= "<br>Matched Repeat (".$metaArray['vertical-repeat']."&quot;)";
    								}
    							}elseif($metaArray['calculator-used']=='pinch-pleated'){
    								$itemDescription .= $productMap[$metaArray['calculator-used']];
    								if($metaArray['unit-of-measure'] == 'pair'){
    									$itemDescription .= "<br>Pair";
    								}elseif($metaArray['unit-of-measure'] == 'panel'){
    									$itemDescription .= "<br>".$metaArray['panel-type']." Panel";
    								}
    								if($metaArray['railroaded'] == '1'){
    									$itemDescription .= "<br>Fabric Railroaded";
    								}else{
    									$itemDescription .= "<br>Fabric Vertically Seamed";
    								}
    								if(isset($metaArray['linings_id']) && floatval($metaArray['linings_id']) >0){
    									$thisLining=$this->Linings->get($metaArray['linings_id'])->toArray();
    									$itemDescription .= "<br><b>Lining:</b> ".$thisLining['short_title'];
    								}
    								$itemDescription .= "<br><b>Hardware:</b> ".ucfirst($metaArray['hardware']);
    								if($metaArray['trim-sewn-on'] == '1'){
    									$itemDescription .= "<br>".$metaArray['trim-lf']." LF Sewn-On Trim";
    								}
    								if($metaArray['tassels'] == '1'){
    									$itemDescription .= "<br>".$metaArray['tassels-count']." Tassels";
    								}
    							}
    						}elseif($metaArray['lineitemtype']=='simpleproduct'){
    							switch($quoteItem['product_type']){
    								case "cubicle_curtains":
    									$itemDescription .= "Price List CC";
    									if($thisFabric['railroaded'] == '1'){
    										$itemDescription .= "<br>Fabric Railroaded";
    									}else{
    										$itemDescription .= "<br>Fabric Vertically Seamed";
    									}
    									if($metaArray['mesh'] > 0 && $metaArray['mesh'] != ''){
    										$itemDescription .= "<br><b>Mesh:</b> ".$metaArray['mesh']."&quot; ".$metaArray['mesh-color']." (MOM)";
    									}
    									if($metaArray['mesh'] == 'No Mesh' || $metaArray['mesh'] == '0'){
    										$itemDescription .= "<br>NO MESH";
    									}
    									if(floatval($thisFabric['vertical_repeat']) > 0){
    										$itemDescription .= "<br>Matched Repeat (".$thisFabric['vertical_repeat']."&quot;)";
    									}
    								break;
    								case "bedspreads":
    									$itemDescription .= "Price List BS";
    									$thisBS="";
    									try {
    									        $thisBS=$this->Bedspreads->get($quoteItem['product_id'])->toArray();
                                            } catch (RecordNotFoundException $e) { }
    								
    									if($thisFabric['railroaded'] == '1'){
    										$itemDescription .= "<br>Fabric Railroaded";
    									}else{
    										$itemDescription .= "<br>Fabric Vertically Seamed";
    									}
    									$itemDescription .= "<br>Style: ";
    									$styleval=explode(" (",$metaArray['style']);
    									$itemDescription .= $styleval[0];
    									if(!empty($thisBS) && $thisBS['quilted']=='1'){
    										$itemDescription .= "<br>Quilted, Double Onion, ".$thisFabric['bs_backing_material']." Backing";
    									}else{
    										$itemDescription .= "<br>Unquilted";
    									}
    									$itemDescription .= "<br>Mattress: ";
    									if(!isset($metaArray['custom-top-width-mattress-w'])){
    										$itemDescription .= "36&quot;";
    									}else{
    										$itemDescription .= $metaArray['custom-top-width-mattress-w']."&quot;";
    									}
    
    									if(floatval($thisFabric['vertical_repeat']) > 0){
    										$itemDescription .= "<br>Matched Repeat (".$thisFabric['vertical_repeat']."&quot;)";
    									}
    								break;
    								case "services":
    									$itemDescription .= $quoteItem['title'];
    								break;
    								case "window_treatments":
    									if($metaArray['wttype']=='Pinch Pleated Drapery'){
    										$itemDescription .= "<b>".ucfirst($metaArray['unit-of-measure'])."</b><br>";
    									}
    									$itemDescription .= 'Price List '.$metaArray['wttype'];
    									if(preg_match("#Cornice#i",$metaArray['wttype']) || preg_match("#Valance#i",$metaArray['wttype'])){
    										$itemDescription .= "<br>Fabric Vertically Seamed";
    									}
    									if(preg_match("#Valance#i",$metaArray['wttype'])){
    										$itemDescription .= "<br>".$metaArray['pleats']." Pleats";
    									}
    									if(isset($metaArray['linings_id']) && floatval($metaArray['linings_id']) >0){
    										$thisLining=$this->Linings->get($metaArray['linings_id'])->toArray();
    										$itemDescription .= "<br><b>Lining:</b> ".$thisLining['short_title'];
    									}
    									if(preg_match("#Cornice#i",$metaArray['wttype'])){
    										$welts='';
    										if($metaArray['welt-top']=='1' && $metaArray['welt-bottom']=='1'){
    											$welts = "Top + Bottom";
    										}else{
    											if($metaArray['welt-top']=='1'){
    												$welts = "Top Only";
    											}elseif($metaArray['welt-bottom'] == '1'){
    												$welts = "Bottom Only";
    											}
    										}
    										if($welts != ''){
    											$itemDescription .= "<br><b>Welts:</b> ".$welts;
    										}else{
    											$itemDescription .= "<br><b>Welts:</b> None";
    										}
    									}
    
    								break;
    								case "track_systems":
    									$itemDescription .= "<b>".$quoteItem['title']."</b>";
    									if(isset($metaArray['_component_numlines']) && intval($metaArray['_component_numlines']) >0){
    										$itemDescription .= "<br><button style=\"padding:4px; border:1px solid #000; background:#CCC; color:#000; font-size:11px;\" onclick=\"loadTrackBreakdown('".$quoteItem['id']."');\" type=\"button\">List Components</button>";
    									}
    								break;
    							}
    						}elseif($metaArray['lineitemtype']=='custom' || $metaArray['lineitemtype'] == 'newcatchall'){
    							$itemDescription .= "<b>".$quoteItem['title']."</b>";
    							$itemDescription .= "<br>".$quoteItem['description'];
    							$itemDescription .= "<br>".nl2br($metaArray['specs']);
    						}
    						
    						///////////////////////////////////////////////////////////////////////////////////////////////////////////////
    						
    						
    						$ttwizard = new \PHPExcel_Helper_HTML;
    						$lineItemValue = $ttwizard->toRichTextObject($itemDescription);
    						
    						
    						$fabricValue='';
    						$fabricFR='';
    						if(isset($thisFabric['flammability']) && strlen(trim($thisFabric['flammability'])) >0){
    							$fabricFR='<br><b>FR: '.$thisFabric['flammability'].'</b>';
    						}
    
    						if($quoteItem['product_type'] == 'track_systems'){
    							$fabricValue .= '---';
    						}elseif($quoteItem['product_type'] == 'custom'){
    							if(isset($metaArray['com-fabric']) && $metaArray['com-fabric'] == "1"){
    								$fabricValue .= "COM ";
    							}
    							if($fabricAlias=$this->getFabricAlias($metaArray['fabricid'],$thisQuote['customer_id'])){
    								if(isset($metaArray['usealias']) && $metaArray['usealias'] == 'yes'){
    									$fabricValue .= $fabricAlias['fabric_name']."<br>".$metaArray['fabric_name'].$fabricFR;
    								}else{
    									$fabricValue .= $metaArray['fabric_name']."<br><em>(".$fabricAlias['fabric_name'].")</em>".$fabricFR;
    								}
    							}else{
    								$fabricValue .= $metaArray['fabric_name'].$fabricFR;
    							}
    						}else{
    							if(isset($metaArray['com-fabric']) && $metaArray['com-fabric'] == "1"){
    								$fabricValue .= "COM ";
    							}
    							if($fabricAlias=$this->getFabricAlias($thisFabric['id'],$thisQuote['customer_id'])){
    								if(isset($metaArray['usealias']) && $metaArray['usealias'] == 'yes'){
    									$fabricValue .= "<b>".$fabricAlias['fabric_name']."</b><br>".$thisFabric['fabric_name'];
    								}else{
    									$fabricValue .= $thisFabric['fabric_name']."<br><em>(".$fabricAlias['fabric_name'].")</em>";
    								}
    							}else{
    								$fabricValue .= $thisFabric['fabric_name'];
    							}
    							$fabricValue .= $fabricFR;
    						}
    						
    						$fabricwizard = new \PHPExcel_Helper_HTML;
    						$fabricrichText = $fabricwizard->toRichTextObject($fabricValue);
    						
    						
    						$colorValue='';
    						if($quoteItem['product_type'] == 'track_systems'){
    							$colorValue .= '---';
    						}elseif($quoteItem['product_type'] == 'custom'){
    							if($fabricAlias=$this->getFabricAlias($metaArray['fabricid'],$thisQuote['customer_id'])){
    								if(isset($metaArray['usealias']) && $metaArray['usealias'] == 'yes'){
    									$colorValue .= "<b>".$fabricAlias['color']."</b><br>".$metaArray['fabric_color'];	
    								}else{
    									$colorValue .= $metaArray['fabric_color']."<br><em>(".$fabricAlias['color'].")</em>";
    								}
    							}else{
    								$colorValue .= $metaArray['fabric_color'];
    							}
    						}else{
    							if($fabricAlias=$this->getFabricAlias($thisFabric['id'],$thisQuote['customer_id'])){
    								if(isset($metaArray['usealias']) && $metaArray['usealias'] == 'yes'){
    									$colorValue .= "<b>".$fabricAlias['color']."</b><Br>".$thisFabric['color'];
    								}else{
    									$colorValue .= $thisFabric['color']."<br><em>(".$fabricAlias['color'].")</em>";
    								}
    							}else{
    								$colorValue .= $thisFabric['color'];
    							}
    						}
    						
    						$colorwizard = new \PHPExcel_Helper_HTML;
    						$colorrichText = $colorwizard->toRichTextObject($colorValue);
    						
    						$cutwidthValue='';
    						$finwidthValue='';
    						
    						if($quoteItem['product_type'] == 'track_systems'){
    							$cutwidthValue .= '---';
    						}elseif($quoteItem['product_type']=="bedspreads"){
    							$cutwidthValue .= number_format(floatval($metaArray['width']),0,'','');
    						}elseif($quoteItem['product_type']=="cubicle_curtains"){
    							$cutwidthValue .= number_format(floatval($metaArray['width']),0,'','');
    							if(isset($metaArray['expected-finish-width']) && strlen(trim($metaArray['expected-finish-width'])) >0){
    								$finwidthValue .= $metaArray['expected-finish-width'];
    							}
    						}elseif($quoteItem['product_type'] == 'window_treatments'){
    							if($metaArray['wttype'] == 'Pinch Pleated Drapery'){
    								$cutwidthValue .= number_format(floatval($metaArray['rod-width']),0,'','')." (Rod)";
    								$finwidthValue .= $metaArray['default-return']." (Return)";
    							}elseif(preg_match("#Cornice#i",$metaArray['wttype']) || preg_match("#Valance#i",$metaArray['wttype'])){
    								$cutwidthValue .= number_format(floatval($metaArray['width']),0,'','')." (Face)";
    								if(isset($metaArray['return']) && floatval(trim($metaArray['return'])) >0){
    									$finwidthValue .= $metaArray['return']." (Return)";
    								}else{
    									$finwidthValue .= "No Return";
    								}
    							}
    						}else{
    							if($metaArray['calculator-used']=="box-pleated"){
    								if(isset($metaArray['face']) && strlen(trim($metaArray['face'])) >0){
    									$cutwidthValue .= $metaArray['face']." (Face)";
    								}
    								if(isset($metaArray['return']) && floatval(trim($metaArray['return'])) >0){
    									$finwidthValue .= $metaArray['return']." (Return)";
    								}else{
    									$finwidthValue .= "No Return";
    								}
    							}elseif($metaArray['calculator-used']=="bedspread" || $metaArray['calculator-used']=="bedspread-manual"){
    								if(isset($metaArray['width']) && strlen(trim($metaArray['width'])) >0){
    									$finwidthValue .= $metaArray['width'];
    								}
    							}elseif($metaArray['calculator-used']=="cubicle-curtain"){
    								if(isset($metaArray['width']) && strlen(trim($metaArray['width'])) >0){
    									$cutwidthValue .= $metaArray['width'];
    								}
    								if(isset($metaArray['expected-finish-width']) && strlen(trim($metaArray['expected-finish-width'])) >0){
    									$finwidthValue .= $metaArray['expected-finish-width'];
    								}else{
    									if(isset($metaArray['fw']) && strlen(trim($metaArray['fw'])) >0){
    										$finwidthValue .= $metaArray['fw'];
    									}
    								}
    							}elseif($metaArray['calculator-used']=="straight-cornice"){
    								if(isset($metaArray['face']) && strlen(trim($metaArray['face'])) >0){
    									$cutwidthValue .= $metaArray['face']." (Face)";
    								}
    								if(isset($metaArray['return']) && floatval(trim($metaArray['return'])) >0){
    									$finwidthValue .= $metaArray['return']." (Return)";
    								}else{
    									$finwidthValue .= "No Return";
    								}
    							}elseif($metaArray['calculator-used'] == 'pinch-pleated'){
    								if($metaArray['unit-of-measure'] == 'pair'){
    									if(isset($metaArray['rod-width']) && strlen(trim($metaArray['rod-width'])) >0){
    										$cutwidthValue .= $metaArray['rod-width']." (Rod)";
    									}
    								}else{
    									if(isset($metaArray['fabric-widths-per-panel'])){
    										$cutwidthValue .= $metaArray['fabric-widths-per-panel']." Widths";
    									}
    								}
    								if(isset($metaArray['fullness']) && strlen(trim($metaArray['fullness'])) >0){
    									$finwidthValue .= $metaArray['fullness']."X Fullness";
    								}
    								if(isset($metaArray['default-return']) && strlen(trim($metaArray['default-return'])) >0){
    									$finwidthValue .= "<br>".$metaArray['default-return']." Ret";
    								}
    								if(isset($metaArray['default-overlap']) && strlen(trim($metaArray['default-overlap'])) >0){
    									$finwidthValue .= "<br>".$metaArray['default-overlap']." Ovrlp";
    								}
    							}
    						}
    						
    						$cutwidthwizard = new \PHPExcel_Helper_HTML;
    						$cutwidthValue = $cutwidthwizard->toRichTextObject($cutwidthValue);
    						
    						if($quoteItem['product_type'] == 'newcatchall-drapery'){
    						    $drapewidthsValue=$metaArray['total-billable-widths'];
    						}elseif($quoteItem['calculator_used'] == 'pinch-pleated' || $quoteItem['calculator_used'] == 'pinch-pleated-new'){
    						    $drapewidthsValue=(intval($quoteItem['qty']) * intval($metaArray['labor-widths']));
    						}else{
    						    $drapewidthsValue='';
    						}
    						
    						
    						$finwidthwizard = new \PHPExcel_Helper_HTML;
    						$finwidthValue = $finwidthwizard->toRichTextObject($finwidthValue);
    						
    						$lengthValue='';
    						if($quoteItem['product_type'] == 'track_systems'){
    							$lengthValue='---';
    						}else{
    							if($metaArray['calculator-used']=="box-pleated"){
    								$lengthValue .= $metaArray['height']." (Height)";
    							}elseif($metaArray['calculator-used']=="straight-cornice"){
    								$lengthValue .= $metaArray['height']." (Height)";
    							}else{
    								$lengthValue .= $metaArray['length'];
    								if($quoteItem['product_type'] == 'window_treatments' && (preg_match("#Cornice#i",$metaArray['wttype']) || preg_match("#Valance#i",$metaArray['wttype']))){
    									$lengthValue .= " (Height)";
    								}
    
    							}
    							if(isset($metaArray['fl-short']) && floatval($metaArray['fl-short']) >0){
    								$lengthValue .= "<br>".$metaArray['fl-short']."(Short Point)";
    							}
    						}
    						$lengthwizard = new \PHPExcel_Helper_HTML;
    						$lengthrichText = $lengthwizard->toRichTextObject($lengthValue);
    						
    					
    						
    						
    						
    						
    						if(isset($metaArray['yds-per-unit'])){
    							$ydsperunitValue=$metaArray['yds-per-unit'];
    						}else{
    							$ydsperunitValue='---';
    						}
    						
    						
    					
    						
    						$bestprice=$quoteItem['best_price'];
    						$installadjustmentpercentage=0;
    						$tieradjustmentpercentage=0;
    						$tierDiscountOrPremium='Disc';
    						$rebateadjustmentpercentage=0;
    						
    						if($metaArray['specialpricing']=='1'){
    							$tieradjusted=number_format(floatval($quoteItem['best_price']),2,'.',',');
    							$installadjusted=number_format(floatval($quoteItem['best_price']),2,'.',',');
    							$rebateadjusted=number_format(floatval($quoteItem['best_price']),2,'.',',');
    							$pmiadjusted=number_format(floatval($quoteItem['pmi_adjusted']),2,'.',',');
    							$pmiadjustmentdollars=number_format((floatval(str_replace(',','',$pmiadjusted)) - floatval(str_replace(',','',$rebateadjusted))),2,'.',',');
    							
    						}else{
    							$tieradjusted=number_format(floatval($quoteItem['tier_adjusted_price']),2,'.',',');
    							$installadjusted=number_format(floatval($quoteItem['install_adjusted_price']),2,'.',',');
    							$rebateadjusted=number_format(floatval($quoteItem['rebate_adjusted_price']),2,'.',',');
    							$pmiadjusted=number_format(floatval($quoteItem['pmi_adjusted']),2,'.',',');
    							
    							$tieradjustmentpercentage=round(abs((((1/floatval(str_replace(',','',$quoteItem['best_price']))) * floatval(str_replace(',','',$tieradjusted)))*100)),2);
    							$installadjustmentpercentage=round(abs(((1-((1/floatval(str_replace(',','',$tieradjusted))) * floatval(str_replace(',','',$installadjusted))))*100)),2);
    							$rebateadjustmentpercentage=round(abs(((1-((1/floatval(str_replace(',','',$installadjusted))) * floatval(str_replace(',','',$rebateadjusted))))*100)),2);
    							$pmiadjustmentdollars=number_format((floatval(str_replace(',','',$pmiadjusted)) - floatval(str_replace(',','',$rebateadjusted))),2,'.',',');
    						}
    
    						$breakdownValue='';
    						$basePriceValue=number_format($bestprice,2,'.',',');
    						
    						if($metaArray['specialpricing']=='1'){
    							$breakdownValue .= "<font color=\"#FF0000\"><b><em>SPECIAL PRICING</em></b></font>";
    						}else{
    							$breakdownValue .= "<font color=\"#0000FF\">".$tieradjusted." Tier (";
    							if(floatval(str_replace(',','',$quoteItem['best_price'])) > floatval(str_replace(',','',$tieradjusted))){
    								$breakdownValue .= '-'.$tieradjustmentpercentage."% Disc";
    							}elseif(floatval(str_replace(',','',$quoteItem['best_price'])) < floatval(str_replace(',','',$tieradjusted))){
    								if($tieradjustmentpercentage > 100){
    									$breakdownValue .= '+'.($tieradjustmentpercentage-100)."% Prem";
    								}else{
    									$breakdownValue .= '+'.$tieradjustmentpercentage."% Prem";
    								}
    							}else{
    								$breakdownValue .= "0%";
    							}
    							
    							$breakdownValue .= ")<br>".$installadjusted." INST (+".$installadjustmentpercentage."%)<br>".$rebateadjusted." ADD ";
    							$breakdownValue .= "(+".$rebateadjustmentpercentage."%)";
    							$breakdownValue .= "<br>".$pmiadjusted." PMI (+\$".$pmiadjustmentdollars.")</font>";
    						}
    						
    						
    						$breakdownwizard = new \PHPExcel_Helper_HTML;
    						$breakdownrichText = $breakdownwizard->toRichTextObject($breakdownValue);
    						
    						
    						
    						if($quoteItem['override_active'] == 1){
    							$adjustedColValue = number_format(floatval($quoteItem['override_price']),2,'.','');
    						}else{
    							$adjustedColValue = number_format(floatval($pmiadjusted),2,'.','');
    						}
    						
    						
    						
    						
    						
    						
    						
    						$extendedPriceValue=number_format($extendedprice,2,'.',',');
    						
    						$dateScheduled='';
    						$dateShipped='';
    						$dateInvoiced='';
    						$dateProduced='';
    						$trackingNumber='';
    						$invoiceNumber='';
    						
    						$lineNotesValue='';
    						$lineNotes=$this->QuoteLineItemNotes->find('all',['conditions'=>['quote_item_id' => $quoteItem['id']],'order'=>['time'=>'asc']])->toArray();
    						foreach($lineNotes as $lineNoteRow){
    							$thisNoteUser=$this->Users->get($lineNoteRow['user_id'])->toArray();
    							if($lineNoteRow['visible_to_customer'] == 0){
    								$lineNotesValue .= '[INTERNAL] ';
    							}
    							$lineNotesValue .= $lineNoteRow['message'].' <font color="#0000FF"><em>'.$thisNoteUser['first_name'].' '.substr($thisNoteUser['last_name'],0,1).' @ '.date('n/j/y g:iA',$lineNoteRow['time']).'</em></font><br>';
    						}
    						
    						$linenoteswizard = new \PHPExcel_Helper_HTML;
    						$linenotesrichText = $linenoteswizard->toRichTextObject($lineNotesValue);
    						
    						
    						
    						//start inner loop of $loop
    						//begin with UNBATCHED
    						if($loop['unbatched'] > 0){
    							$qtyValue=$loop['unbatched'];
    							$batchValue='N/A';
    							
    							$totalLFvalue='';
    							
    							$totalydsvalue='';
    							if($metaArray['lineitemtype'] == 'simpleproduct'){
    								$totalydsvalue .= round((floatval($metaArray['yds-per-unit']) * floatval($qtyValue)),2);
    							}elseif($metaArray['lineitemtype']=="custom"){
    								if(floatval($metaArray['total-yds']) >0){
    									$totalydsvalue .= round(((floatval($metaArray['total-yds']) / floatval($quoteItem['qty'])) * intval($qtyValue)),2);
    								}else{
    									$totalydsvalue .= round((floatval($metaArray['yds-per-unit']) * floatval($qtyValue)),2);
    								}
    							}else{
    								$totalydsvalue .= round(((floatval($metaArray['total-yds']) / floatval($quoteItem['qty'])) * intval($qtyValue)),2);
    							}
    							
    							
    							if($metaArray['specialpricing']=='1'){
    								if($quoteItem['override_active'] == 1){
    									$extendedprice=(floatval($quoteItem['override_price']) * floatval($qtyValue));
    								}else{
    									$extendedprice=(floatval($quoteItem['best_price'])*floatval($qtyValue));
    								}
    							}else{
    							    if($quoteItem['override_active'] == 1){
    									$extendedprice=(floatval($quoteItem['override_price']) * floatval($qtyValue));
    								}else{
    								    $extendedprice=number_format((floatval($quoteItem['pmi_adjusted']) * $qtyValue),2,'.','');
    								}
    							}
    							
    							
    							
    							if($quoteItem['product_type'] == 'track_systems'){
        							if($quoteItem['unit'] == 'linear feet'){
        								$totalLFvalue .= $qtyValue;
        							}else{
        								$totalLFvalue .= '---';
        							}
        						}else{
        							if(isset($metaArray['labor-billable'])){
        								$totalLFvalue .= (floatval($metaArray['labor-billable'])*floatval($qtyValue));
        							}else{
        								$totalLFvalue .= "---";
        							}
        						}
    						
    						if($orderRow['project_manager_id'] == 0 || is_null($orderRow['project_manager_id'])){
    						    $managerName='N/A';
    						}else{
    						    $pmLookup=$this->Users->get($orderRow['project_manager_id'])->toArray();
    						    $managerName=$pmLookup['first_name'].' '.$pmLookup['last_name'];
    						}
    						
    						//$totallfrichText = $totalLFvalue;
    						
    						
    						if($quoteItem['product_type'] == 'newcatchall-valance' || $quoteItem['product_type'] == 'newcatchall-cornice' || $quoteItem['calculator_used'] == 'straight-cornice' || $quoteItem['calculator_used'] == 'box-pleated'){
    						    $ttlfValue=$totalLFvalue;
    						    $cclfValue='';
    						}elseif($quoteItem['product_type'] == 'track_systems'){
    						    $ttlfValue='';
    						    $cclfValue='';
    						}else{
    						    $ttlfValue='';
    						    $cclfValue = $totalLFvalue;
    						}
    						
    						
    						
    						if(!is_null($orderRow['type_id']) && $orderRow['type_id'] > 0){
    						    $thisType=$this->QuoteTypes->get($orderRow['type_id'])->toArray();
    						    $thisOrderType=$thisType['type_label'];
    						}else{
    						    $thisOrderType='';
    						}
    							
    							//recalc and override the TOTAL LF, TOTAL YARDS, EXTENDED PRICE variables for [this qty]
    							
    							
    							$objPHPExcel->getActiveSheet()->SetCellValue('A'.$rowCount, $orderRow['order_number']);
    							
    							$objPHPExcel->getActiveSheet()->SetCellValue('B'.$rowCount, $orderRow['status']);
    							
    							//$objPHPExcel->getActiveSheet()->SetCellValue('C'.$rowCount, $quoteNumberValue);
    							
    							$objPHPExcel->getActiveSheet()->SetCellValue('C'.$rowCount, $thisOrderType);
    							
    							$objPHPExcel->getActiveSheet()->SetCellValue('D'.$rowCount, $orderRow['stage']);
    							
    							$objPHPExcel->getActiveSheet()->SetCellValue('E'.$rowCount, $managerName);
    							
    							$objPHPExcel->getActiveSheet()->SetCellValue('F'.$rowCount, $customerValue);
    							$objPHPExcel->getActiveSheet()->SetCellValue('G'.$rowCount, $customerPOValue);
    							$objPHPExcel->getActiveSheet()->SetCellValue('H'.$rowCount, $facilityValue);
    							$objPHPExcel->getActiveSheet()->SetCellValue('I'.$rowCount, $shipDateValue);
    							$objPHPExcel->getActiveSheet()->getStyle('I'.$rowCount)->getNumberFormat()->setFormatCode('mm/dd/yyyy');
    							
    							$objPHPExcel->getActiveSheet()->SetCellValue('J'.$rowCount, $dateScheduled);
    							$objPHPExcel->getActiveSheet()->getStyle('J'.$rowCount)->getNumberFormat()->setFormatCode('mm/dd/yyyy');
    							
    							$objPHPExcel->getActiveSheet()->SetCellValue('K'.$rowCount, $thisbookingdate);
    							$objPHPExcel->getActiveSheet()->getStyle('K'.$rowCount)->getNumberFormat()->setFormatCode('mm/dd/yyyy');
    							
    							$objPHPExcel->getActiveSheet()->SetCellValue('L'.$rowCount, $batchValue);
    							$objPHPExcel->getActiveSheet()->SetCellValue('M'.$rowCount, $lineNumberValue);
    							//$objPHPExcel->getActiveSheet()->SetCellValue('O'.$rowCount, $locationValue);
    							$objPHPExcel->getActiveSheet()->SetCellValue('N'.$rowCount, $qtyValue);
    							
    							$objPHPExcel->getActiveSheet()->SetCellValue('O'.$rowCount, $classValue);
    							$objPHPExcel->getActiveSheet()->SetCellValue('P'.$rowCount, $subclassValue);
    							
    							
    							$objPHPExcel->getActiveSheet()->SetCellValue('Q'.$rowCount, $unitValue);
    							$objPHPExcel->getActiveSheet()->SetCellValue('R'.$rowCount, $lineItemValue);
    							
    							$objPHPExcel->getActiveSheet()->SetCellValue('S'.$rowCount, $fabricrichText);
    							$objPHPExcel->getActiveSheet()->SetCellValue('T'.$rowCount, $colorrichText);
    							
    							//$objPHPExcel->getActiveSheet()->SetCellValue('W'.$rowCount, $cutwidthValue);
    							//$objPHPExcel->getActiveSheet()->SetCellValue('X'.$rowCount, $finwidthValue);
    							//$objPHPExcel->getActiveSheet()->SetCellValue('Y'.$rowCount, $lengthrichText);
    							
    							//$objPHPExcel->getActiveSheet()->SetCellValue('Z'.$rowCount, $cclfValue);
    							
    							//$objPHPExcel->getActiveSheet()->SetCellValue('AA'.$rowCount, $drapewidthsValue);
    							//$objPHPExcel->getActiveSheet()->SetCellValue('AB'.$rowCount, $ttlfValue);
    							
    							//$objPHPExcel->getActiveSheet()->SetCellValue('AC'.$rowCount, $ydsperunitValue);
    							
    							$objPHPExcel->getActiveSheet()->SetCellValue('U'.$rowCount, $totalydsvalue);
    							$objPHPExcel->getActiveSheet()->SetCellValue('V'.$rowCount, $basePriceValue);
    							//$objPHPExcel->getActiveSheet()->SetCellValue('W'.$rowCount, $breakdownrichText);
    							$objPHPExcel->getActiveSheet()->SetCellValue('W'.$rowCount, $adjustedColValue);
    							$objPHPExcel->getActiveSheet()->SetCellValue('X'.$rowCount, $extendedprice);
    							
    							
    							$objPHPExcel->getActiveSheet()->SetCellValue('Y'.$rowCount, $dateProduced);
    							$objPHPExcel->getActiveSheet()->getStyle('Y'.$rowCount)->getNumberFormat()->setFormatCode('mm/dd/yyyy');
    							
    							$objPHPExcel->getActiveSheet()->SetCellValue('Z'.$rowCount, $dateShipped);
    							$objPHPExcel->getActiveSheet()->getStyle('Z'.$rowCount)->getNumberFormat()->setFormatCode('mm/dd/yyyy');
    							
    							$objPHPExcel->getActiveSheet()->SetCellValue('AA'.$rowCount, $trackingNumber);
    							$objPHPExcel->getActiveSheet()->SetCellValue('AB'.$rowCount, $dateInvoiced);
    							$objPHPExcel->getActiveSheet()->getStyle('AB'.$rowCount)->getNumberFormat()->setFormatCode('mm/dd/yyyy');
    							
    							$objPHPExcel->getActiveSheet()->SetCellValue('AC'.$rowCount, $invoiceNumber);
    							
    							$objPHPExcel->getActiveSheet()->SetCellValue('AD'.$rowCount, $linenotesrichText);
    							
    						
    							if($rowCount % 2 == 0){
    								$thisrowcolor='F8F8F8';
    							}else{
    								$thisrowcolor='FFFFFF';
    							}
    						
    							$objPHPExcel->getActiveSheet()->getStyle('A'.$rowCount.':AD'.$rowCount)->applyFromArray(
    								array(
    									'fill' => array(
    										'type' => \PHPExcel_Style_Fill::FILL_SOLID,
    										'color' => array('rgb' => $thisrowcolor)
    									)
    								)
    							);
    
    							$brlines=explode("<br",$itemDescription);
    							if(count($brlines) < 5){
    								$rowheight=90;
    							}elseif(count($brlines) >= 5 && count($brlines) < 8){
    								$rowheight=120;
    							}else{
    								$rowheight=145;
    							}
    
    							$objPHPExcel->getActiveSheet()->getRowDimension($rowCount)->setRowHeight($rowheight);
    
    							$rowCount++;
    						}
    						
    						
    						//then loop through BATCHED
    						foreach($loop['batches'] as $num => $batchloopitem){
    						    $totalLFvalue='';
    						    
    							if($type=='backlog' && ((isset($batchloopitem['invoicedTS']) && $batchloopitem['invoicedTS'] > 1000) && (isset($batchloopitem['shippedTS']) && $batchloopitem['shippedTS'] > 1000) )){
    								//ignore this row, it's already shipped and invoiced
    							}else{
    								$qtyValue=$batchloopitem['qty'];
    								$batchValue=$batchloopitem['batchid'];
    								
    								if($metaArray['specialpricing']=='1'){
    									if($quoteItem['override_active'] == 1){
    										$extendedprice=(floatval($quoteItem['override_price']) * floatval($qtyValue));
    									}else{
    										$extendedprice=(floatval($quoteItem['best_price'])*floatval($qtyValue));
    									}
    								}else{
    								    if($quoteItem['override_active'] == 1){
    										$extendedprice=(floatval($quoteItem['override_price']) * floatval($qtyValue));
    									}else{
    									    $extendedprice=number_format((floatval($quoteItem['pmi_adjusted']) * $qtyValue),2,'.','');
    									}
    								}
    								
    								$totalydsvalue='';
    								if($metaArray['lineitemtype'] == 'simpleproduct'){
    									$totalydsvalue .= round((floatval($metaArray['yds-per-unit']) * floatval($qtyValue)),2);
    								}elseif($metaArray['lineitemtype']=="custom"){
    									if(floatval($metaArray['total-yds']) >0){
    										$totalydsvalue .= round(((floatval($metaArray['total-yds']) / floatval($quoteItem['qty'])) * intval($qtyValue)),2);
    									}else{
    										$totalydsvalue .= round((floatval($metaArray['yds-per-unit']) * floatval($qtyValue)),2);
    									}
    								}else{
    									$totalydsvalue .= round(((floatval($metaArray['total-yds']) / floatval($quoteItem['qty'])) * intval($qtyValue)),2);
    								}
    
    
                                    if($quoteItem['product_type'] == 'track_systems'){
            							if($quoteItem['unit'] == 'linear feet'){
            								$totalLFvalue .= $qtyValue;
            							}else{
            								$totalLFvalue .= '---';
            							}
            						}else{
            							if(isset($metaArray['labor-billable'])){
            								$totalLFvalue .= (floatval($metaArray['labor-billable'])*floatval($qtyValue));
            							}else{
            								$totalLFvalue .= "---";
            							}
            						}
            						
            						//$totallfrichText = $totalLFvalue;
            						
            						if($quoteItem['product_type'] == 'newcatchall-valance' || $quoteItem['product_type'] == 'newcatchall-cornice' || $quoteItem['calculator_used'] == 'straight-cornice' || $quoteItem['calculator_used'] == 'box-pleated'){
            						    $ttlfValue=$totalLFvalue;
            						    $cclfValue='';
            						}elseif($quoteItem['product_type'] == 'track_systems'){
            						    $ttlfValue='';
            						    $cclfValue='';
            						}else{
            						    $ttlfValue='';
            						    $cclfValue = $totalLFvalue;
            						}
    								
    								
    								if($quoteItem['product_type'] == 'newcatchall-drapery'){
            						    $drapewidthsValue=$metaArray['total-billable-widths'];
            						}elseif($quoteItem['calculator_used'] == 'pinch-pleated' || $quoteItem['calculator_used'] == 'pinch-pleated-new'){
            						    $drapewidthsValue=(intval($quoteItem['qty']) * intval($metaArray['labor-widths']));
            						}else{
            						    $drapewidthsValue='';
            						}
    								
    								
    								$dateScheduled='';
    								$dateShipped='';
    								$trackingNumber='';
    								
    								
    								
    								$shipStatusesThisBatch=$this->OrderItemStatus->find('all',['conditions'=>['sherry_batch_id' => $batchValue,'status'=>'Shipped','shipment_id !=' => NULL,'shipment_id !=' => 0]])->toArray();
                    				foreach($shipStatusesThisBatch as $shipStatus){
                    					$shipment=$this->Shipments->get($shipStatus['shipment_id'])->toArray();
                    					$trackingNumber=$shipment['tracking_number'];
                    				}
    								
    								//look up whether this line has been Shipped, if so, populate the variable
            						$thisLineBatchesShipped=$this->OrderItemStatus->find('all',['conditions' => ['order_item_id' => $orderItem['id'], 'status' => 'Shipped', 'sherry_batch_id' => $batchValue]])->toArray();
            						foreach($thisLineBatchesShipped as $shippedBatch){
            							//$dateShipped = date('n/j/Y',$shippedBatch['time']);
            							$shipDateTimeObj=new \DateTime();
            						    $shipDateTimeObj->setTimestamp($shippedBatch['time']);
            							$dateShipped = \PHPExcel_Shared_Date::PHPToExcel($shipDateTimeObj);
            						}
            						
            						
            						$dateScheduled='';
            						$thisLineBatchesScheduled=$this->OrderItemStatus->find('all',['conditions' => ['order_item_id' => $orderItem['id'], 'status' => 'Scheduled', 'sherry_batch_id' => $batchValue]])->toArray();
            						foreach($thisLineBatchesScheduled as $scheduledBatch){
            							//$dateScheduled = date('n/j/Y',$scheduledBatch['time']);
            							$schedDateTimeObj=new \DateTime();
            						    $schedDateTimeObj->setTimestamp($scheduledBatch['time']);
            						    
            							$dateScheduled = \PHPExcel_Shared_Date::PHPToExcel($schedDateTimeObj);
            						}
            						
            						
            						$dateProduced='';
            						$thisLineBatchesCompleted=$this->OrderItemStatus->find('all',['conditions' => ['order_item_id' => $orderItem['id'], 'status' => 'Completed', 'sherry_batch_id' => $batchValue]])->toArray();
            						foreach($thisLineBatchesCompleted as $producedBatch){
            							//$dateProduced = date('n/j/Y',$producedBatch['time']);
            							$prodDateTimeObj=new \DateTime();
            						    $prodDateTimeObj->setTimestamp($producedBatch['time']);
            						    
            							$dateProduced = \PHPExcel_Shared_Date::PHPToExcel($prodDateTimeObj);
            						}
            						
            						$dateInvoiced='';
            						$invoiceNumber='';
            						
            						//look up whether this line has been Invoiced, if so, populate the variable
            						$thisLineBatchesInvoiced=$this->OrderItemStatus->find('all',['conditions' => ['order_item_id' => $orderItem['id'], 'status' => 'Invoiced','sherry_batch_id'=>$batchValue]])->toArray();
            						foreach($thisLineBatchesInvoiced as $invoicedBatch){
            							//$dateInvoiced = date('n/j/Y',$invoicedBatch['time']);
            							$invDateTimeObj=new \DateTime();
            						    $invDateTimeObj->setTimestamp($invoicedBatch['time']);
            						    
            						    $invoiceNumber=$invoicedBatch['invoice_number'];
            						    
            							$dateInvoiced = \PHPExcel_Shared_Date::PHPToExcel($invDateTimeObj);
            						}
    						
    						
    						
    								//recalc and override the TOTAL LF, TOTAL YARDS, EXTENDED PRICE variables for [this qty]
    								if($orderRow['project_manager_id'] == 0 || is_null($orderRow['project_manager_id'])){
            						    $managerName='N/A';
            						}else{
            						    $pmLookup=$this->Users->get($orderRow['project_manager_id'])->toArray();
            						    $managerName=$pmLookup['first_name'].' '.$pmLookup['last_name'];
            						}
    								
    								$objPHPExcel->getActiveSheet()->SetCellValue('A'.$rowCount, $orderRow['order_number']);
    								
    								$objPHPExcel->getActiveSheet()->SetCellValue('B'.$rowCount, $orderRow['status']);
    								
    								//$objPHPExcel->getActiveSheet()->SetCellValue('C'.$rowCount, $quoteNumberValue);
    								
    								
    								if(!is_null($orderRow['type_id']) && $orderRow['type_id'] > 0){
            						    $thisType=$this->QuoteTypes->get($orderRow['type_id'])->toArray();
            						    $thisOrderType=$thisType['type_label'];
            						}else{
            						    $thisOrderType='';
            						}
    								
    								$objPHPExcel->getActiveSheet()->SetCellValue('C'.$rowCount, $thisOrderType);
    								
    								$objPHPExcel->getActiveSheet()->SetCellValue('D'.$rowCount, $orderRow['stage']);
    								
    								$objPHPExcel->getActiveSheet()->SetCellValue('E'.$rowCount, $managerName);
    								
    								$objPHPExcel->getActiveSheet()->SetCellValue('F'.$rowCount, $customerValue);
    								$objPHPExcel->getActiveSheet()->SetCellValue('G'.$rowCount, $customerPOValue);
    								$objPHPExcel->getActiveSheet()->SetCellValue('H'.$rowCount, $facilityValue);
    								$objPHPExcel->getActiveSheet()->SetCellValue('I'.$rowCount, $shipDateValue);
    								$objPHPExcel->getActiveSheet()->getStyle('I'.$rowCount)->getNumberFormat()->setFormatCode('mm/dd/yyyy');
    								
    								$objPHPExcel->getActiveSheet()->SetCellValue('J'.$rowCount, $dateScheduled);
    								$objPHPExcel->getActiveSheet()->getStyle('J'.$rowCount)->getNumberFormat()->setFormatCode('mm/dd/yyyy');
    								
    								$objPHPExcel->getActiveSheet()->SetCellValue('K'.$rowCount, $thisbookingdate);
    								$objPHPExcel->getActiveSheet()->getStyle('K'.$rowCount)->getNumberFormat()->setFormatCode('mm/dd/yyyy');
    								
    								$objPHPExcel->getActiveSheet()->SetCellValue('L'.$rowCount, $batchValue);
    								$objPHPExcel->getActiveSheet()->SetCellValue('M'.$rowCount, $lineNumberValue);
    								//$objPHPExcel->getActiveSheet()->SetCellValue('O'.$rowCount, $locationValue);
    								$objPHPExcel->getActiveSheet()->SetCellValue('N'.$rowCount, $qtyValue);
    								
    								
    								$objPHPExcel->getActiveSheet()->SetCellValue('O'.$rowCount, $classValue);
    								$objPHPExcel->getActiveSheet()->SetCellValue('P'.$rowCount, $subclassValue);
    								
    								
    								$objPHPExcel->getActiveSheet()->SetCellValue('Q'.$rowCount, $unitValue);
    								$objPHPExcel->getActiveSheet()->SetCellValue('R'.$rowCount, $lineItemValue);
    								$objPHPExcel->getActiveSheet()->SetCellValue('S'.$rowCount, $fabricrichText);
    								$objPHPExcel->getActiveSheet()->SetCellValue('T'.$rowCount, $colorrichText);
    								//$objPHPExcel->getActiveSheet()->SetCellValue('W'.$rowCount, $cutwidthValue);
    								//$objPHPExcel->getActiveSheet()->SetCellValue('X'.$rowCount, $finwidthValue);
    								//$objPHPExcel->getActiveSheet()->SetCellValue('Y'.$rowCount, $lengthrichText);
    								//$objPHPExcel->getActiveSheet()->SetCellValue('Z'.$rowCount, $cclfValue);
    								
    								//$objPHPExcel->getActiveSheet()->SetCellValue('AA'.$rowCount, $drapewidthsValue);
    							    //$objPHPExcel->getActiveSheet()->SetCellValue('AB'.$rowCount, $ttlfValue);

    								//$objPHPExcel->getActiveSheet()->SetCellValue('AC'.$rowCount, $ydsperunitValue);
    								
    								
    								$objPHPExcel->getActiveSheet()->SetCellValue('U'.$rowCount, $totalydsvalue);
    								$objPHPExcel->getActiveSheet()->SetCellValue('V'.$rowCount, $basePriceValue);
    								//$objPHPExcel->getActiveSheet()->SetCellValue('AF'.$rowCount, $breakdownrichText);
    								$objPHPExcel->getActiveSheet()->SetCellValue('W'.$rowCount, $adjustedColValue);
    								$objPHPExcel->getActiveSheet()->SetCellValue('X'.$rowCount, $extendedprice);
    							
    								
    								
    								$objPHPExcel->getActiveSheet()->SetCellValue('Y'.$rowCount, $dateProduced);
    								$objPHPExcel->getActiveSheet()->getStyle('Y'.$rowCount)->getNumberFormat()->setFormatCode('mm/dd/yyyy');
    								
    								$objPHPExcel->getActiveSheet()->SetCellValue('Z'.$rowCount, $dateShipped);
    								$objPHPExcel->getActiveSheet()->getStyle('Z'.$rowCount)->getNumberFormat()->setFormatCode('mm/dd/yyyy');
    								
    								$objPHPExcel->getActiveSheet()->SetCellValue('AA'.$rowCount, $trackingNumber);
    								$objPHPExcel->getActiveSheet()->SetCellValue('AB'.$rowCount, $dateInvoiced);
    								$objPHPExcel->getActiveSheet()->getStyle('AB'.$rowCount)->getNumberFormat()->setFormatCode('mm/dd/yyyy');
    								
    								$objPHPExcel->getActiveSheet()->SetCellValue('AC'.$rowCount, $invoiceNumber); //INV#
    								
    								$objPHPExcel->getActiveSheet()->SetCellValue('AD'.$rowCount, $linenotesrichText);
    								
    								
    
    
    								if($rowCount % 2 == 0){
    									$thisrowcolor='F8F8F8';
    								}else{
    									$thisrowcolor='FFFFFF';
    								}
    
    								$objPHPExcel->getActiveSheet()->getStyle('A'.$rowCount.':AD'.$rowCount)->applyFromArray(
    									array(
    										'fill' => array(
    											'type' => \PHPExcel_Style_Fill::FILL_SOLID,
    											'color' => array('rgb' => $thisrowcolor)
    										)
    									)
    								);
    
    								$brlines=explode("<br",$itemDescription);
    								if(count($brlines) < 5){
    									$rowheight=90;
    								}elseif(count($brlines) >= 5 && count($brlines) < 8){
    									$rowheight=120;
    								}else{
    									$rowheight=145;
    								}
    
    								$objPHPExcel->getActiveSheet()->getRowDimension($rowCount)->setRowHeight($rowheight);
    
    								$rowCount++;
    							}
    						
    						}
						
					    }
					}
					
				}
				
			}
			
			$objPHPExcel->getActiveSheet()->getStyle('A1:A'.$rowCount)->getAlignment()->setHorizontal('center');
			$objPHPExcel->getActiveSheet()->getStyle('B1:B'.$rowCount)->getAlignment()->setHorizontal('center');
			//$objPHPExcel->getActiveSheet()->getStyle('C1:C'.$rowCount)->getAlignment()->setHorizontal('center');
            $objPHPExcel->getActiveSheet()->getStyle('C1:C'.$rowCount)->getAlignment()->setHorizontal('center');
            $objPHPExcel->getActiveSheet()->getStyle('D1:D'.$rowCount)->getAlignment()->setHorizontal('center');
            $objPHPExcel->getActiveSheet()->getStyle('E1:E'.$rowCount)->getAlignment()->setHorizontal('center');
			$objPHPExcel->getActiveSheet()->getStyle('F1:F'.$rowCount)->getAlignment()->setHorizontal('center');
			$objPHPExcel->getActiveSheet()->getStyle('G1:G'.$rowCount)->getAlignment()->setHorizontal('center');
			$objPHPExcel->getActiveSheet()->getStyle('H1:H'.$rowCount)->getAlignment()->setHorizontal('center');
			$objPHPExcel->getActiveSheet()->getStyle('I1:I'.$rowCount)->getAlignment()->setHorizontal('center');
			$objPHPExcel->getActiveSheet()->getStyle('J1:J'.$rowCount)->getAlignment()->setHorizontal('center');
			$objPHPExcel->getActiveSheet()->getStyle('K1:K'.$rowCount)->getAlignment()->setHorizontal('center');
			$objPHPExcel->getActiveSheet()->getStyle('L1:L'.$rowCount)->getAlignment()->setHorizontal('center');
			$objPHPExcel->getActiveSheet()->getStyle('M1:M'.$rowCount)->getAlignment()->setHorizontal('center');
			//$objPHPExcel->getActiveSheet()->getStyle('O1:O'.$rowCount)->getAlignment()->setHorizontal('center');
			$objPHPExcel->getActiveSheet()->getStyle('N1:N'.$rowCount)->getAlignment()->setHorizontal('center');
			$objPHPExcel->getActiveSheet()->getStyle('O1:O'.$rowCount)->getAlignment()->setHorizontal('center');
			$objPHPExcel->getActiveSheet()->getStyle('P1:P'.$rowCount)->getAlignment()->setHorizontal('center');
			$objPHPExcel->getActiveSheet()->getStyle('Q1:Q'.$rowCount)->getAlignment()->setHorizontal('center');
			$objPHPExcel->getActiveSheet()->getStyle('R1:R'.$rowCount)->getAlignment()->setHorizontal('center');
			$objPHPExcel->getActiveSheet()->getStyle('S1:S'.$rowCount)->getAlignment()->setHorizontal('center');
			$objPHPExcel->getActiveSheet()->getStyle('T1:T'.$rowCount)->getAlignment()->setHorizontal('center');
			//$objPHPExcel->getActiveSheet()->getStyle('W1:W'.$rowCount)->getAlignment()->setHorizontal('center');
			//$objPHPExcel->getActiveSheet()->getStyle('X1:X'.$rowCount)->getAlignment()->setHorizontal('center');
			//$objPHPExcel->getActiveSheet()->getStyle('Y1:Y'.$rowCount)->getAlignment()->setHorizontal('center');
			//$objPHPExcel->getActiveSheet()->getStyle('Z1:Z'.$rowCount)->getAlignment()->setHorizontal('center');
			//$objPHPExcel->getActiveSheet()->getStyle('AA1:AA'.$rowCount)->getAlignment()->setHorizontal('center');
			//$objPHPExcel->getActiveSheet()->getStyle('AB1:AB'.$rowCount)->getAlignment()->setHorizontal('center');
			//$objPHPExcel->getActiveSheet()->getStyle('AC1:AC'.$rowCount)->getAlignment()->setHorizontal('center');
			$objPHPExcel->getActiveSheet()->getStyle('U1:U'.$rowCount)->getAlignment()->setHorizontal('center');
			$objPHPExcel->getActiveSheet()->getStyle('V1:V'.$rowCount)->getAlignment()->setHorizontal('center');
			$objPHPExcel->getActiveSheet()->getStyle('W1:W'.$rowCount)->getAlignment()->setHorizontal('center');
			$objPHPExcel->getActiveSheet()->getStyle('X1:X'.$rowCount)->getAlignment()->setHorizontal('center');
			$objPHPExcel->getActiveSheet()->getStyle('Y1:Y'.$rowCount)->getAlignment()->setHorizontal('center');
			$objPHPExcel->getActiveSheet()->getStyle('Z1:Z'.$rowCount)->getAlignment()->setHorizontal('center');
			$objPHPExcel->getActiveSheet()->getStyle('AA1:AA'.$rowCount)->getAlignment()->setHorizontal('center');
			//$objPHPExcel->getActiveSheet()->getStyle('AK1:AK'.$rowCount)->getAlignment()->setHorizontal('center');
			$objPHPExcel->getActiveSheet()->getStyle('AB1:AB'.$rowCount)->getAlignment()->setHorizontal('center');
			$objPHPExcel->getActiveSheet()->getStyle('AC1:AC'.$rowCount)->getAlignment()->setHorizontal('center'); //INV#
			$objPHPExcel->getActiveSheet()->getStyle('AD1:AD'.$rowCount)->getAlignment()->setHorizontal('left');
			
			
			$objPHPExcel->getActiveSheet()->getStyle('A1:AD'.$rowCount)->getAlignment()->setVertical(\PHPExcel_Style_Alignment::VERTICAL_TOP );
			
			$objPHPExcel->getActiveSheet()->getStyle('A1:AD'.$rowCount)->getAlignment()->setWrapText(true);
			
			
			
			$objPHPExcel->getActiveSheet()->getStyle("A1:AD".$rowCount)->applyFromArray(
				array(
					'borders' => array(
						'allborders' => array(
							'style' => \PHPExcel_Style_Border::BORDER_THIN,
							'color' => array('rgb' => '111111')
						)
					)
				)
			);
			
			$objPHPExcel->getActiveSheet()->freezePane('A2');
            
            // Redirect output to a client’s web browser (Excel5)
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="QB Reconciliation Report - '.$this->request->data['report_date_start'].' thru '.$this->request->data['report_date_end'].'.xlsx"');
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
            
	        
	        
	    }else{
	        //show the form
	        //get all the users for filtering by user
	        $allUsers=$this->Users->find('all',['conditions' => ['status'=>'Active'],'order'=>['first_name'=>'asc','last_name'=>'asc']])->toArray();
	        $this->set('allUsers',$allUsers);
	        
	        $allCustomers=$this->Customers->find('all',['order'=>['company_name'=>'asc']])->toArray();
	        $this->set('allCustomers',$allCustomers);
	        
	        $quoteTypes=$this->QuoteTypes->find('all',['order'=>['type_label'=>'asc']])->toArray();
	        $this->set('quoteTypes',$quoteTypes);
	    }
	}
	
	/**PPSA-40 start **/
public function quotesnewlines() {
        if ($this->request->data) {
            $this->autoRender = false;
            $productMap = array('cubicle-curtain' => 'Cubicle Curtain', 'box-pleated' => 'Box Pleated Valance', 'straight-cornice' => 'Straight Cornice', 'bedspread' => 'Calculated Bedspread', 'bedspread-manual' => 'Manually Entered Bedspread', 'pinch-pleated' => 'Pinch Pleated Drapery');
            $startTS = strtotime($this->request->data['report_date_start'] . ' 00:00:00');
            $endTS = strtotime($this->request->data['report_date_end'] . ' 23:59:59');
            $db = ConnectionManager::get('default');
            $query = "SELECT quote_line_items.created AS  CREATED, ";
            $query.= "users.first_name  AS CREATEDBY,users.last_name  AS CREATEDLASTBY, quotes.quote_number, ";
            $query.= "quote_types.type_label AS QUOTETYPE, quotes.status AS QUOTESTATUS, customers.company_name AS CUSTOMER, ";
            $query.= "IF(quotes.order_number IS NULL, quotes.title, orders.facility) AS RECIPIENT, ";
            $query.= "quote_line_items.line_number, quote_line_items.qty, quote_line_items.product_class, ";
            $query.= "	IF(quote_line_items.override_active=1, quote_line_items.override_price, quote_line_items.pmi_adjusted) AS PRICEEA,";
            $query.= " quote_line_items.extended_price,";
            $query.= "  quotes.order_number";
            $query.= " FROM quote_line_items";
            $query.= " JOIN quotes ON quotes.id = quote_line_items.quote_id";
            $query.= " JOIN users ON users.id = quotes.created_by";
            $query.= " JOIN quote_types ON quote_types.id = quotes.type_id";
            $query.= " JOIN customers ON customers.id   = quotes.customer_id";
            $query.= " LEFT JOIN orders ON orders.order_number = quotes.order_number";
            $query.= " WHERE  ";
            $query.= " quote_line_items.created >= " . $startTS . " AND quote_line_items.created <= " . $endTS;
            if (strlen($this->request->data['customer_id']) > 0) {
                $query.= " AND quotes.customer_id=" . $this->request->data['customer_id'];
            }
            if (strlen($this->request->data['quote_type']) > 0) {
                $query.= " AND quotes.type_id = " . $this->request->data['quote_type'];
            }
            if ($this->request->data['filterbyuser'] != 'allusers') {
                $query.= " AND orders.user_id = " . $this->request->data['filterbyuser'];
            }
            if (isset($this->request->data['revision']) &&  $this->request->data['revision']== 'yes') {
            } else {
                 $query .= " AND quotes.parent_quote = 0";
            }
           /* if ($this->request->data['revision'] == 'yes') {
                $query.= " AND quotes.revision >= 0 ";
            } else {
                $query.= " AND quotes.revision = 0 ";
            }*/
            $query.= " ORDER BY quote_line_items.created ASC";
           // print_r($query);
            $queryRun = $db->execute($query);
            $ordersLookup = $queryRun->fetchAll('assoc');
            require ($_SERVER['DOCUMENT_ROOT'] . "/vendor/phpoffice/phpexcel/Classes/PHPExcel.php");
            require ($_SERVER['DOCUMENT_ROOT'] . "/vendor/phpoffice/phpexcel/Classes/PHPExcel/Writer/Excel2007.php");
            $objPHPExcel = new \PHPExcel();
            $objPHPExcel->setActiveSheetIndex(0);
            $objPHPExcel->getActiveSheet()->SetCellValue('A1', 'CREATED DATE #');
            $objPHPExcel->getActiveSheet()->SetCellValue('B1', 'CREATED BY');
            $objPHPExcel->getActiveSheet()->SetCellValue('C1', 'QUOTE #');
            $objPHPExcel->getActiveSheet()->SetCellValue('D1', 'QUOTE TYPE');
            $objPHPExcel->getActiveSheet()->SetCellValue('E1', 'QUOTE STATUS');
            $objPHPExcel->getActiveSheet()->SetCellValue('F1', 'CUSTOMER');
            $objPHPExcel->getActiveSheet()->SetCellValue('G1', 'RECIPIENT');
            $objPHPExcel->getActiveSheet()->SetCellValue('H1', 'LINE #');
            $objPHPExcel->getActiveSheet()->SetCellValue('I1', 'QTY');
            $objPHPExcel->getActiveSheet()->SetCellValue('J1', 'CLASS');
            $objPHPExcel->getActiveSheet()->SetCellValue('K1', 'ADJ PRICE');
            $objPHPExcel->getActiveSheet()->SetCellValue('L1', 'EXT PRICE');
            $objPHPExcel->getActiveSheet()->SetCellValue('M1', 'ORDER #');
            
            $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
            $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(25);
            $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(15);
            $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(15);
            $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(25);
            $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(15);
            $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(40);
            $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(25);
            $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(55);
            $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(15);
            $objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(15);
            $objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(15);
            $objPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth(15);
            $objPHPExcel->getActiveSheet()->getRowDimension('1')->setRowHeight(22);
            $objPHPExcel->getActiveSheet()->getStyle('A1:M1')->getAlignment()
            ->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('A1:M1')
            ->applyFromArray(array('fill' => 
                array('type' => \PHPExcel_Style_Fill::FILL_SOLID,
                    'color' => array('rgb' => 'F8CBAD'))));
            $rowCount = 2;
            $outputArr = array();
            
            foreach ($ordersLookup as $orderRow) {
                $thisClass = $this->ProductClasses
                    ->get($orderRow['product_class'])->toArray();
                $classValue = $thisClass['class_name'];
                
                $publishedDateTimeObj = new \DateTime();
                $publishedDateTimeObj->setTimestamp($orderRow['CREATED']);
                $thispublisheddate = \PHPExcel_Shared_Date::PHPToExcel($publishedDateTimeObj);
                $objPHPExcel->getActiveSheet()->SetCellValue('A' . $rowCount, $thispublisheddate);
                $objPHPExcel->getActiveSheet()->getStyle('A' . $rowCount)->getNumberFormat()->setFormatCode('mm/dd/yyyy');
                $objPHPExcel->getActiveSheet()->SetCellValue('B' . $rowCount, $orderRow['CREATEDBY'] .' ' .$orderRow['CREATEDLASTBY']);
                $objPHPExcel->getActiveSheet()->SetCellValue('C' . $rowCount, $orderRow['quote_number']);
                $objPHPExcel->getActiveSheet()->SetCellValue('D' . $rowCount, $orderRow['QUOTETYPE']);
                $objPHPExcel->getActiveSheet()->SetCellValue('E' . $rowCount, $orderRow['QUOTESTATUS']);
                $objPHPExcel->getActiveSheet()->SetCellValue('F' . $rowCount, $orderRow['CUSTOMER'] ? $orderRow['CUSTOMER'] : '');
                $objPHPExcel->getActiveSheet()->SetCellValue('G' . $rowCount, $orderRow['RECIPIENT']);
                $objPHPExcel->getActiveSheet()->SetCellValue('H' . $rowCount, $orderRow['line_number']);
                $objPHPExcel->getActiveSheet()->SetCellValue('I' . $rowCount, $orderRow['qty']);
                $objPHPExcel->getActiveSheet()->SetCellValue('J' . $rowCount, $classValue);
                $objPHPExcel->getActiveSheet()->SetCellValue('K' . $rowCount, $orderRow['PRICEEA']);
                $objPHPExcel->getActiveSheet()->SetCellValue('L' . $rowCount, $orderRow['extended_price']);
                $objPHPExcel->getActiveSheet()->SetCellValue('M' . $rowCount, $orderRow['order_number']);
                
                if ($rowCount % 2 == 0) {
                    $thisrowcolor = 'F8F8F8';
                } else {
                    $thisrowcolor = 'FFFFFF';
                }
                $objPHPExcel->getActiveSheet()
                ->getStyle('A' . $rowCount . ':M' . $rowCount)
                ->applyFromArray(array('fill' => array('type' 
                => \PHPExcel_Style_Fill::FILL_SOLID, 'color' 
                => array('rgb' => $thisrowcolor))));
                //$objPHPExcel->getActiveSheet()
                //->getRowDimension($rowCount)->setRowHeight($rowheight);
                $rowCount++;
            }
            
            $objPHPExcel->getActiveSheet()->getStyle('A1:A' . $rowCount)->getAlignment()->setHorizontal('center');
            $objPHPExcel->getActiveSheet()->getStyle('B1:B' . $rowCount)->getAlignment()->setHorizontal('center');
            $objPHPExcel->getActiveSheet()->getStyle('C1:C' . $rowCount)->getAlignment()->setHorizontal('center');
            $objPHPExcel->getActiveSheet()->getStyle('D1:D' . $rowCount)->getAlignment()->setHorizontal('center');
            $objPHPExcel->getActiveSheet()->getStyle('E1:E' . $rowCount)->getAlignment()->setHorizontal('center');
            $objPHPExcel->getActiveSheet()->getStyle('F1:F' . $rowCount)->getAlignment()->setHorizontal('center');
            $objPHPExcel->getActiveSheet()->getStyle('G1:G' . $rowCount)->getAlignment()->setHorizontal('center');
            $objPHPExcel->getActiveSheet()->getStyle('H1:H' . $rowCount)->getAlignment()->setHorizontal('center');
            $objPHPExcel->getActiveSheet()->getStyle('I1:I' . $rowCount)->getAlignment()->setHorizontal('center');
            $objPHPExcel->getActiveSheet()->getStyle('J1:J' . $rowCount)->getAlignment()->setHorizontal('center');
            $objPHPExcel->getActiveSheet()->getStyle('K1:K' . $rowCount)->getAlignment()->setHorizontal('center');
            $objPHPExcel->getActiveSheet()->getStyle('L1:L' . $rowCount)->getAlignment()->setHorizontal('center');
            $objPHPExcel->getActiveSheet()->getStyle('M1:M' . $rowCount)->getAlignment()->setHorizontal('center');
            $objPHPExcel->getActiveSheet()->getStyle("A1:M1" . $rowCount)
            ->applyFromArray(array('borders' => array('allborders' 
            => array('style' => \PHPExcel_Style_Border::BORDER_THIN, 
            'color' => array('rgb' => '111111')))));
            $objPHPExcel->getActiveSheet()->freezePane('A2');
            
            // Redirect output to a client’s web browser (Excel5)
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="Quotes NewLines Created Report-' . $this->request->data['report_date_start'] . '-' . $this->request->data['report_date_end'] . '.xlsx"');
            header('Cache-Control: max-age=0');
            // If you're serving to IE 9, then the following may be needed
            header('Cache-Control: max-age=1');
            // If you're serving to IE over SSL, then the following may be needed
            header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
            header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
            header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
            header('Pragma: public'); // HTTP/1.0
            $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
            $objWriter->save('php://output');
        } else {
            //show the form
            //get all the users for filtering by user
            $allUsers = $this->Users->find('all', ['conditions' => ['status' => 'Active'], 'order' => ['first_name' => 'asc', 'last_name' => 'asc']])->toArray();
            $this->set('allUsers', $allUsers);
            $allCustomers = $this->Customers->find('all', ['order' => ['company_name' => 'asc']])->toArray();
            $this->set('allCustomers', $allCustomers);
            $quoteTypes = $this->QuoteTypes->find('all', ['order' => ['type_label' => 'asc']])->toArray();
            $this->set('quoteTypes', $quoteTypes);
        }
    }	/**PPSA-40 end **/
    
    
    //PPSASCRUM-216 start
	
	public function consolidateddraftquotes(){
	    if($this->request->data){
	        $this->autoRender=false;
	        
	        $startTS=strtotime($this->request->data['report_date_start'].' 00:00:00');
			$endTS=strtotime($this->request->data['report_date_end'].' 23:59:59');
			
	        
	        $db=ConnectionManager::get('default');
    	    $query = "SELECT * FROM `quotes` WHERE `status` = 'draft' AND `created` >= ".$startTS." AND `created` <= ".$endTS." AND `revision` IS NULL AND `parent_quote`=0 ";
    	    if($this->request->data['filterbyuser'] != 'allusers'){
    	        $query .= " AND `created_by` = ".$this->request->data['filterbyuser'];
    	    }
    	    
    	    if(strlen($this->request->data['customer_id']) > 0){
    	        $query .= " AND `customer_id`=".$this->request->data['customer_id'];
    	    }
    	    
    	    if(strlen($this->request->data['quote_type']) > 0){
    	        $query .= " AND `type_id` = ".$this->request->data['quote_type'];
    	    }
    	    
    	    if($this->request->data['dollarfilter'] == 'yes'){
    	        $query .= " AND `quote_total` >= ".$this->request->data['dollar_min'];
    	    }
    	    
    	    $query .= " ORDER BY `created` DESC";
	        $queryRun=$db->execute($query);
		    $quotesLookup=$queryRun->fetchAll('assoc');
	        
	        
	        require($_SERVER['DOCUMENT_ROOT']."/vendor/phpoffice/phpexcel/Classes/PHPExcel.php");
            require($_SERVER['DOCUMENT_ROOT']."/vendor/phpoffice/phpexcel/Classes/PHPExcel/Writer/Excel2007.php");
            
    	    $objPHPExcel = new \PHPExcel();
            $objPHPExcel->setActiveSheetIndex(0);
			
			
			$objPHPExcel->getActiveSheet()->SetCellValue('A1', 'QUOTE NUMBER');
			$objPHPExcel->getActiveSheet()->SetCellValue('B1', 'CUSTOMER');
			$objPHPExcel->getActiveSheet()->SetCellValue('C1', 'CREATED BY');
			$objPHPExcel->getActiveSheet()->SetCellValue('D1', 'CREATED DATE');
			$objPHPExcel->getActiveSheet()->SetCellValue('E1', 'MODIFIED DATE');
			$objPHPExcel->getActiveSheet()->SetCellValue('F1', 'QUOTE TITLE/PROJECT');
            $objPHPExcel->getActiveSheet()->SetCellValue('G1', 'TYPE');
            $objPHPExcel->getActiveSheet()->SetCellValue('H1', 'TOTALS');
            $objPHPExcel->getActiveSheet()->SetCellValue('I1', 'CC LF');
            $objPHPExcel->getActiveSheet()->SetCellValue('J1', 'TRK LF');
            $objPHPExcel->getActiveSheet()->SetCellValue('K1', 'BS QTY');
            $objPHPExcel->getActiveSheet()->SetCellValue('L1', 'DRAPERIES WIDTHS');
            $objPHPExcel->getActiveSheet()->SetCellValue('M1', 'TOP TREATMENTS QTY');
            $objPHPExcel->getActiveSheet()->SetCellValue('N1', 'TOP TREATMENT LF');
            $objPHPExcel->getActiveSheet()->SetCellValue('O1', 'WT HW QTY');
            $objPHPExcel->getActiveSheet()->SetCellValue('P1', 'B&S QTY');

			
			$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(16);
			$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
			$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(24);
			
			$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(16);
			$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(16);
			
			$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(42);
			$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(14);
			$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(16);
			$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(30);
			$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(12);
			$objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(12);
			$objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(12);
			$objPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth(18);
			$objPHPExcel->getActiveSheet()->getColumnDimension('N')->setWidth(22);
			$objPHPExcel->getActiveSheet()->getColumnDimension('O')->setWidth(22);
			$objPHPExcel->getActiveSheet()->getColumnDimension('P')->setWidth(12);

			
			
			$objPHPExcel->getActiveSheet()->getRowDimension('1')->setRowHeight(18);
			$objPHPExcel->getActiveSheet()->getStyle('A1:P1')->getAlignment()->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);
			
			
			$objPHPExcel->getActiveSheet()->getStyle('A1:P1')->applyFromArray(
				array(
					'fill' => array(
						'type' => \PHPExcel_Style_Fill::FILL_SOLID,
						'color' => array('rgb' => 'F8CBAD')
					)
				)
			);
			
			
            $rowCount = 2;
			
			$outputArr=array();
			foreach($quotesLookup as $quote){
			    
			    if(!is_null($quote['type_id']) && $quote['type_id'] > 0){
				    $thisType=$this->QuoteTypes->get($quote['type_id'])->toArray();
				    $thisQuoteType=$thisType['type_label'];
				}else{
				    $thisQuoteType='';
				}
				
				
    			$totals = number_format(floatval($quote['quote_total']),2,'.','');
    			
				
			    $customerData=$this->Customers->get($quote['customer_id'])->toArray();
    			$userData=$this->Users->get($quote['created_by'])->toArray();
                
    			$projectname='';
    			if($quote['project_id'] > 0){
    				$projectData=$this->Projects->get($quote['project_id'])->toArray();
    				$projectname=$quote['title'].' / '.$projectData['title'];
    			}else{
    				$projectname=$quote['title'];
    			}
    			
    			
    			
    			$numitems=0;
    			$quoteitems=$this->QuoteLineItems->find('all',['conditions'=>['quote_id'=>$quote['id']]])->toArray();
			    foreach($quoteitems as $quoteitem){
    				$numitems=($numitems+$quoteitem['qty']);
    			}
			
			    
			    $cclf=$this->getTypeLF($quote['id'],'cc');
			    $tracklf=$this->getTypeLF($quote['id'],'track');
			    $bsqty=$this->getTypeQty($quote['id'],'bs');
			    $drapewidths=$this->getTypeWidths($quote['id'],'drapes');
			    $ttqty=$this->getTypeQty($quote['id'],'tt');
			    $ttlf=$this->getTypeLF($quote['id'],'tt');
			    $wthwqty=$this->getTypeQty($quote['id'],'wthw');
			    $blindsqty=$this->getTypeQty($quote['id'],'blinds');
			    
			    
			    if($quote['order_number'] > 0){
			        $ordernumber=$quote['order_number'];
			    }else{
			        $ordernumber='';
			    }
			    
			    
			    $objPHPExcel->getActiveSheet()->SetCellValue('A'.$rowCount, $quote['quote_number']);
			    $objPHPExcel->getActiveSheet()->SetCellValue('B'.$rowCount, $customerData['company_name']);
			    $objPHPExcel->getActiveSheet()->SetCellValue('C'.$rowCount, $userData['first_name'].' '.$userData['last_name']);
			    
			    //$objPHPExcel->getActiveSheet()->SetCellValue('D'.$rowCount, date('m/d/Y',$quote['publish_date']));
			    $publishedDateTimeObj=new \DateTime();
    			$publishedDateTimeObj->setTimestamp($quote['created']);
    			$thispublisheddate=\PHPExcel_Shared_Date::PHPToExcel($publishedDateTimeObj);
			    $objPHPExcel->getActiveSheet()->SetCellValue('D'.$rowCount,$thispublisheddate);
			    			    $objPHPExcel->getActiveSheet()->getStyle('D'.$rowCount)->getNumberFormat()->setFormatCode('mm/dd/yyyy');

			    
			     $modifiedDateTimeObj=new \DateTime();
    			$modifiedDateTimeObj->setTimestamp($quote['modified']);
    			$thismodifieddate=\PHPExcel_Shared_Date::PHPToExcel($modifiedDateTimeObj);
			    $objPHPExcel->getActiveSheet()->SetCellValue('E'.$rowCount,$thismodifieddate);
			    
			    
			    $objPHPExcel->getActiveSheet()->getStyle('E'.$rowCount)->getNumberFormat()->setFormatCode('mm/dd/yyyy');
			    
			    $objPHPExcel->getActiveSheet()->SetCellValue('F'.$rowCount, $projectname);
			    $objPHPExcel->getActiveSheet()->SetCellValue('G'.$rowCount, $thisQuoteType);
			    $objPHPExcel->getActiveSheet()->SetCellValue('H'.$rowCount, $totals);
			    $objPHPExcel->getActiveSheet()->SetCellValue('I'.$rowCount, $cclf);
			    $objPHPExcel->getActiveSheet()->SetCellValue('J'.$rowCount, $tracklf);
			    $objPHPExcel->getActiveSheet()->SetCellValue('K'.$rowCount, $bsqty);
			    $objPHPExcel->getActiveSheet()->SetCellValue('L'.$rowCount, $drapewidths);
			    $objPHPExcel->getActiveSheet()->SetCellValue('M'.$rowCount, $ttqty);
			    $objPHPExcel->getActiveSheet()->SetCellValue('N'.$rowCount, $ttlf);
			    $objPHPExcel->getActiveSheet()->SetCellValue('O'.$rowCount, $wthwqty);
			    $objPHPExcel->getActiveSheet()->SetCellValue('P'.$rowCount, $blindsqty);

			    $rowCount++;
			}
          
            
            //echo "<pre>"; print_r($outputArr); echo "</pre>";exit;
          
            $objPHPExcel->getActiveSheet()->getRowDimension(($rowCount-1))->setRowHeight(-1);
            $objPHPExcel->getActiveSheet()->getStyle('A2:P'.($rowCount-1))->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
            $objPHPExcel->getActiveSheet()->getStyle('A2:P'.($rowCount-1))->getAlignment()->setVertical(\PHPExcel_Style_Alignment::VERTICAL_TOP );
            $objPHPExcel->getActiveSheet()->getStyle('A2:P'.($rowCount-1))->getAlignment()->setWrapText(true);
            
            // Redirect output to a client’s web browser (Excel5)
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="Consolidated Draft Quotes Report - '.$this->request->data['report_date_start'].' thru '.$this->request->data['report_date_end'].'.xlsx"');
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
            
	        
	        
	    }else{
	        //show the form
	        //get all the users for filtering by user
	        $allUsers=$this->Users->find('all',['conditions' => ['status'=>'Active'],'order'=>['first_name'=>'asc','last_name'=>'asc']])->toArray();
	        $this->set('allUsers',$allUsers);
	        
	        $allCustomers=$this->Customers->find('all',['order'=>['company_name'=>'asc']])->toArray();
	        $this->set('allCustomers',$allCustomers);
	        $quoteTypes=$this->QuoteTypes->find('all',['order'=>['type_label'=>'asc']])->toArray();
	        $this->set('quoteTypes',$quoteTypes);
	        
	        $this->set('bigquotestart',$this->getSettingValue('bq_qualifying_amount'));
	    }
	    
	}
	//PPSASCRUM-216 end
}