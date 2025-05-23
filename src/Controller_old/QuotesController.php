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

//PPSA-47 
use Cake\Datasource\Exception\RecordNotFoundException;


/**

 * Static content controller

 *

 * This controller will render views from Template/Pages/

 *

 * @link http://book.cakephp.org/3.0/en/controllers/pages-controller.html

 */

class QuotesController extends AppController{

	public function initialize(){

        parent::initialize();

        $this->loadComponent('RequestHandler');
		$this->loadComponent('Security');
		$this->loadModel('UserTypes');
		$this->loadModel('Users');
		$this->loadModel('Customers');
		$this->loadModel('CustomerContacts');
		$this->loadModel('Fabrics');
		$this->loadModel('Linings');
		$this->loadModel('FabricMarkupRules');
		$this->loadModel('Calculators');
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
		$this->loadModel('Quotes');
		$this->loadModel('QuoteNotes');
		$this->loadModel('QuoteDiscounts');
		$this->loadModel('QuoteLineItems');
		$this->loadModel('QuoteTypes');
		$this->loadModel('QuoteLineItemMeta');
		$this->loadModel('QuoteLineItemNotes');
		$this->loadModel('QuoteItemDeleteLog');
		$this->loadModel('LibraryImages');
		$this->loadModel('LibraryCategories');
		$this->loadModel('Vendors');
		$this->loadModel('CcDataMap');
		$this->loadModel('WtDataMap');
		$this->loadModel('WindowTreatmentSizes');
		$this->loadModel('QuoteBomRequirements');
		$this->loadModel('MaterialPurchases');
		$this->loadModel('MaterialInventory');
		$this->loadModel('MaterialUsages');
		$this->loadModel('Projects');
		$this->loadModel('ShippingMethods');
        $this->loadModel('QuoteItemAddLog');
        $this->loadModel('QuoteItemDeleteLog');
        $this->loadModel('Orders');
        $this->loadModel('OrderItems');
        $this->loadModel('OrderItemStatus');
        $this->loadModel('ProductClasses');
		$this->loadModel('ProductSubclasses');
		$this->loadModel('QuoteBomPurchasingNotes');
		/**PPSASCRUM-45 start */

		$this->loadModel('WorkOrders');
        $this->loadModel('WorkOrderItems');
        $this->loadModel('WorkOrderItemStatus');
		$this->loadModel('OrderLineItems');
		$this->loadModel('OrderLineItemMeta');
		$this->loadModel('OrderLineItemNotes');
				/**PPSASCRUM-45 end */

	}

	

		

	public function beforeFilter(Event $event)

    {

        parent::beforeFilter($event);

		$this->Auth->allow(['calculator']);

		

		if($this->request->action=='getproductselectlist' || $this->request->action=='getquoteslist' || $this->request->action=='getproductprice' || $this->request->action=='newlineitem' || $this->request->action=='editcalclineitem' || $this->request->action=='changequotetitle' || $this->request->action=='updatefabrequirement' || $this->request->action=='recordusage' || $this->request->action=='editlineitemimage' || $this->request->action=='editlineitem' || $this->request->action=='pmsurchargeform' || $this->request->action=='converttoorder'){

			$this->Security->config('unlockedActions', ['getquoteslist','getproductprice','getproductselectlist','newlineitem','pmsurchargeform','updatefabrequirement','recordusage','editlineitemimage','editlineitem']);

			$this->eventManager()->off($this->Csrf);

			$this->eventManager()->off($this->Security);

		}

		

    }

	

	public function index(){

		

	}


    public function editpublishedorder($orderID){
        $this->autoRender=false;
        
        //create a new clone of the quote, give it a new revision number, and give it the status of "Editing"
        $thisOrder=$this->Orders->get($orderID)->toArray();
        $orderTable=TableRegistry::get('Orders');
        $orderRow=$orderTable->get($orderID);
        $orderRow->status='Editing';
        $orderTable->save($orderRow);
        
        $thisQuote=$this->Quotes->get($thisOrder['quote_id'])->toArray();
		
		$quotesTable=TableRegistry::get('Quotes');
		$quoteLinesTable=TableRegistry::get('QuoteLineItems');
		$quoteLineMetaTable=TableRegistry::get('QuoteLineItemMeta');
		$quoteLineNotesTable=TableRegistry::get('QuoteLineItemNotes');
		$quoteBOMReqsTable=TableRegistry::get('QuoteBomRequirements');
		$quoteDiscountsTable=TableRegistry::get('QuoteDiscounts');
		$quoteGlobalNotesTable=TableRegistry::get('QuoteNotes');
		
		$lineIDsOldToNew=array();
		
		$newQuote=$quotesTable->newEntity();
		
		$excludedKeys=array('id','created','modified','revision','parent_quote','expires','created_by','status','quote_number');
		
		foreach($thisQuote as $key => $value){
			if(!in_array($key,$excludedKeys)){
				$newQuote->{$key}=$value;
			}
		}
		
		//find the highest revision number for this quote number
		$newrevisionnumber=1;
		
		$quoteslookup=$this->Quotes->find('all',['conditions' => ['quote_number' => $thisQuote['quote_number']],'order'=>['revision'=>'desc']])->toArray();
		$fd=1;
		foreach($quoteslookup as $revisionrow){
		    if($fd==1){
		        $newrevisionnumber=(intval($revisionrow['revision'])+1);
		    }
		    $fd++;
		}
		
		
		$newQuote->created=time();
		$newQuote->modified=time();
		$newQuote->revision=$newrevisionnumber;
		$newQuote->parent_quote=$thisQuote['id'];
		$newQuote->expires=(time()+(30*86400));
		$newQuote->created_by=$this->Auth->user('id');
		$newQuote->status='editorder';
		$newQuote->quote_number=$thisQuote['quote_number'];
		$newQuote->order_id=$orderID;
		$newQuote->order_number=$thisOrder['order_number'];
		
		if($quotesTable->save($newQuote)){
		    
		
			$thisQuoteLines=$this->QuoteLineItems->find('all',['conditions'=>['quote_id'=>$thisQuote['id']],'order'=>['sortorder'=>'asc']])->toArray();
			foreach($thisQuoteLines as $line){
				
				//clone this line
				$newLine=$quoteLinesTable->newEntity();
				$excludedLineKeys=array('quote_id','parent_line');
				
				
				$newLine->type=$line['type'];
				$newLine->status=$line['status'];
				$newLine->product_type=$line['product_type'];
				$newLine->product_id=$line['product_id'];
				$newLine->title=$line['title'];
				$newLine->description=$line['description'];
				$newLine->best_price=$line['best_price'];
				$newLine->qty=$line['qty'];
				
				$newLine->product_class = $line['product_class'];
				$newLine->product_subclass = $line['product_subclass'];
				
				$newLine->lineitemtype=$line['lineitemtype'];
				$newLine->room_number=$line['room_number'];
				$newLine->line_number=$line['line_number'];
				$newLine->internal_line=$line['internal_line'];
				$newLine->sortorder=$line['sortorder'];
				$newLine->unit=$line['unit'];
				$newLine->tier_adjusted_price=$line['tier_adjusted_price'];
				$newLine->install_adjusted_price=$line['install_adjusted_price'];
				$newLine->rebate_adjusted_price=$line['rebate_adjusted_price'];
				$newLine->extended_price=$line['extended_price'];
				$newLine->override_active = $line['override_active'];
				$newLine->override_price=$line['override_price'];
				$newLine->calculator_used=$line['calculator_used'];
				$newLine->pmi_adjusted=$line['pmi_adjusted'];
				
				$newLine->quote_id=$newQuote->id;
				
				
				$newLine->parent_line=0;
				
				/*figure out the new "parent line" id#
				if($line['parent_line'] == 0){
				    $newLine->parent_line=0;
				}else{
				    $newLine->parent_line=$lineIDsOldToNew[$line['parent_line']];
				}
				*/
				
				
				$newLine->revised_from_line=$line['id'];
				
				
				if($quoteLinesTable->save($newLine)){
					$lineIDsOldToNew[$line['id']]=$newLine->id;
					
					//clone METAS
					$thisQuoteLineMeta=$this->QuoteLineItemMeta->find('all',['conditions' => ['quote_item_id'=>$line['id']]])->toArray();
					foreach($thisQuoteLineMeta as $meta){
						$newMeta=$quoteLineMetaTable->newEntity();
						$newMeta->meta_key=$meta['meta_key'];
						$newMeta->meta_value=$meta['meta_value'];
						$newMeta->quote_item_id = $newLine->id;
						$quoteLineMetaTable->save($newMeta);
					}
					
					//clone NOTES
					$thisQuoteLineNotes=$this->QuoteLineItemNotes->find('all',['conditions' => ['quote_item_id' => $line['id']]])->toArray();
					foreach($thisQuoteLineNotes as $note){
						$newNote=$quoteLineNotesTable->newEntity();
						$newNote->user_id=$note['user_id'];
						$newNote->time=$note['time'];
						$newNote->message=$note['message'];
						$newNote->quote_id=$newQuote->id;
						$newNote->quote_item_id=$newLine->id;
						$newNote->visible_to_customer=$note['visible_to_customer'];
						$quoteLineNotesTable->save($newNote);
					}
					
					
				}
				
				
			}
			
			//loop back through all of the new lines and make sure their PARENT LINE data is adjusted
			$thisNewQuoteLines=$this->QuoteLineItems->find('all',['conditions'=>['quote_id'=>$newQuote->id],'order'=>['sortorder'=>'asc']])->toArray();
			foreach($thisNewQuoteLines as $newQuoteLine){
			    
			    $thisLine=$quoteLinesTable->get($newQuoteLine['id']);
			    
			    
			    //let's figure out the old quote's matching line to this line
				$oldQuoteMatchingLine=$this->QuoteLineItems->find('all',['conditions'=>['quote_id'=>$newQuoteLine['revised_from_line']]])->toArray();
			    //$oldQuoteMatchingLine=$this->QuoteLineItems->get($newQuoteLine['revised_from_line'])->toArray();
			    if($oldQuoteMatchingLine['parent_line'] == 0){
			        $newparentvalue=0;
			    }else{
			        //find the new quote line that has a revised_from_line equal to old parent id#
			        $newQuoteMatchingOldParentLine=$this->QuoteLineItems->find('all',['conditions'=>['quote_id'=>$newQuote->id,'revised_from_line' => $oldQuoteMatchingLine['parent_line']]])->toArray();
			        foreach($newQuoteMatchingOldParentLine as $nn){
			            $newparentvalue=$nn['id'];
			        }
			    }
			    
			    $thisLine->parent_line=$newparentvalue;
			    $quoteLinesTable->save($thisLine);
			    
			}
			
			
			//BOM Requirements clone
			$thisQuoteBOMReqs=$this->QuoteBomRequirements->find('all',['conditions'=>['quote_id'=>$thisQuote['id']]])->toArray();
			foreach($thisQuoteBOMReqs as $bomReq){

				$newBOMReq=$quoteBOMReqsTable->newEntity();
				$newBOMReq->quote_id=$newQuote->id;
				$newBOMReq->material_type=$bomReq['material_type'];
				$newBOMReq->material_id=$bomReq['material_id'];
				$newBOMReq->requirement=$bomReq['requirement'];
				$newBOMReq->purchaser_requirement_met=0;
				$quoteBOMReqsTable->save($newBOMReq);

			}
			
			
			//quote DISCOUNTS
			$thisQuoteDiscounts=$this->QuoteDiscounts->find('all',['conditions'=>['quote_id'=>$thisQuote['id']]])->toArray();
			foreach($thisQuoteDiscounts as $quoteDiscount){
				$newDiscount=$quoteDiscountsTable->newEntity();
				$newDiscount->quote_id=$newQuote->id;
				$newDiscount->discount_name=$quoteDiscount['discount_name'];
				$newDiscount->discount_amount=$quoteDiscount['discount_amount'];
				$newDiscount->discount_math=$quoteDiscount['discount_math'];
				$newDiscount->added_by=$quoteDiscount['added_by'];
				$newDiscount->added_time=$quoteDiscount['added_time'];
				$quoteDiscountsTable->save($newDiscount);
			}
    
            //Global Notes
            $thisQuoteGlobalNotes=$this->QuoteNotes->find('all',['conditions' => ['quote_id' => $thisQuote['id']]])->toArray();
            foreach($thisQuoteGlobalNotes as $note){
                $newGlobalNote=$quoteGlobalNotesTable->newEntity();
                $newGlobalNote->quote_id=$newQuote->id;
                $newGlobalNote->user_id=$note['user_id'];
                $newGlobalNote->time=$note['time'];
                $newGlobalNote->appear_on_pdf=$note['appear_on_pdf'];
                $newGlobalNote->note_text=$note['note_text'];
                $quoteGlobalNotesTable->save($newGlobalNote);
            }
    
		}
		
		
        
        return $this->redirect('/orders/editlines/'.$orderID);
    }




	public function updatepmsurchargequoterangepercentage($quoteID,$verbose=false){

		if($verbose){
			$this->autoRender=false;
		}
		$thisQuote=$this->Quotes->get($quoteID)->toArray();
		
		$settings=$this->getsettingsarray();

		$pmSurchargeConfigs=json_decode(urldecode($thisQuote['project_management_surcharge_configs']),true);

		$thisQuoteVal=floatval($thisQuote['quote_total']);
		$quoteValueRanges=json_decode(urldecode($settings['pm_surcharge_total_quote_values']),true);
		$baseProjectSurchargeMOM=$settings['pm_surcharge_base_percentage_mom'];
		$baseProjectSurchargeCOM=$settings['pm_surcharge_base_percentage_com'];
		$facilityTypePercentages=json_decode(urldecode($settings['pm_surcharge_facility_types']),true);
		$constructionTypeNewMOM=$settings['pm_surcharge_construction_type_new_mom_percent'];
		$constructionTypeNewCOM=$settings['pm_surcharge_construction_type_new_com_percent'];
		$constructionTypeRenovationMOM=$settings['pm_surcharge_construction_type_renovation_mom_percent'];
		$constructionTypeRenovationCOM=$settings['pm_surcharge_construction_type_renovation_com_percent'];
		$phaseCountPercentages=json_decode(urldecode($settings['pm_surcharge_phase_count_percents']),true);
		$documentationYesMOM=$settings['pm_surcharge_documentation_yes_percentage_mom'];
		$documentationYesCOM=$settings['pm_surcharge_documentation_yes_percentage_com'];
		$documentationNoMOM=$settings['pm_surcharge_documentation_no_percentage_mom'];
		$documentationNoCOM=$settings['pm_surcharge_documentation_no_percentage_com'];
		$readinessPercentages=json_decode(urldecode($settings['pm_surcharge_readiness']),true);
		$clientStorageYesMOM=$settings['pm_surcharge_client_storage_yes_mom'];
		$clientStorageYesCOM=$settings['pm_surcharge_client_storage_yes_com'];
		$clientStorageNoMOM=$settings['pm_surcharge_client_storage_no_mom'];
		$clientStorageNoCOM=$settings['pm_surcharge_client_storage_no_com'];


		$finalPercentage=0;
		$momorcom='';
		

		if($pmSurchargeConfigs['pm_com_or_mom'] == 'MOM'){
			$finalPercentage=($finalPercentage + floatval($baseProjectSurchargeMOM));
			$momorcom='mom';
			if($verbose){ echo "MOM Base + ".$baseProjectSurchargeMOM."%<br>"; }
		}elseif($pmSurchargeConfigs['pm_com_or_mom'] == 'COM'){
			$finalPercentage=($finalPercentage + floatval($baseProjectSurchargeMOM));
			$momorcom='com';
			if($verbose){ echo "COM Base + ".$baseProjectSurchargeCOM."%<br>"; }
		}


		foreach($quoteValueRanges as $i => $val){
			if($thisQuoteVal >= floatval($val['rangeLow']) && $thisQuoteVal <= floatval($val['rangeHigh'])){
				if($momorcom=='mom'){
					$finalPercentage=($finalPercentage + floatval($val['momPercent']));
					if($verbose){ echo "Quote Value between ".$val['rangeLow']." and ".$val['rangeHigh']." (MOM)  + ".$val['momPercent']."%<br>"; }
				}elseif($momorcom=='com'){
					$finalPercentage=($finalPercentage + floatval($val['comPercent']));
					if($verbose){ echo "Quote Value between ".$val['rangeLow']." and ".$val['rangeHigh']." (COM)  + ".$val['comPercent']."%<br>"; }
				}
			}
		}



		foreach($facilityTypePercentages as $i => $val){
			if($pmSurchargeConfigs['type-of-facility'] == $val['facilityType']){
				if($momorcom=='mom'){
					$finalPercentage = ($finalPercentage + floatval($val['momPercent']));
					if($verbose){ echo "Facility Type ".$val['facilityType']." (MOM)  + ".$val['momPercent']."%<br>"; }
				}elseif($momorcom == 'com'){
					$finalPercentage = ($finalPercentage + floatval($val['comPercent']));
					if($verbose){ echo "Facility Type ".$val['facilityType']." (COM)  + ".$val['comPercent']."%<br>"; }
				}
			}
		}


		if($pmSurchargeConfigs['pm_type_of_construction'] == 'NEW'){
			if($momorcom=='mom'){
				$finalPercentage = ($finalPercentage + floatval($constructionTypeNewMOM));
				if($verbose){ echo "Construction Type NEW (MOM)  + ".$constructionTypeNewMOM."%<br>"; }
			}elseif($momorcom == 'com'){
				$finalPercentage = ($finalPercentage + floatval($constructionTypeNewCOM));
				if($verbose){ echo "Construction Type NEW (COM)  + ".$constructionTypeNewCOM."%<br>"; }
			}
		}elseif($pmSurchargeConfigs['pm_type_of_construction'] == 'RENOVATION'){
			if($momorcom=='mom'){
				$finalPercentage = ($finalPercentage + floatval($constructionTypeRenovationMOM));
				if($verbose){ echo "Construction Type RENOVATION (MOM)  + ".$constructionTypeRenovationMOM."%<br>"; }
			}elseif($momorcom == 'com'){
				$finalPercentage = ($finalPercentage + floatval($constructionTypeRenovationCOM));
				if($verbose){ echo "Construction Type RENOVATION (COM)  + ".$constructionTypeRenovationCOM."%<br>"; }
			}
		}



		foreach($phaseCountPercentages as $i => $val){
			$selectedPhaseSplit=explode("-",$pmSurchargeConfigs['phase_count']);
			$selectedPhaseRangeLow=$selectedPhaseSplit[0];
			$selectedPhaseRangeHigh=$selectedPhaseSplit[1];

			if(floatval($selectedPhaseRangeLow) >= floatval($val['rangeLow']) && floatval($selectedPhaseRangeHigh) <= floatval($val['rangeHigh'])){
				if($momorcom=='mom'){
					$finalPercentage = ($finalPercentage + floatval($val['momPercent']));
					if($verbose){ echo "Phase Count between ".$val['rangeLow']." and ".$val['rangeHigh']." (MOM)  + ".$val['momPercent']."%<br>"; }
				}elseif($momorcom == 'com'){
					$finalPercentage = ($finalPercentage + floatval($val['comPercent']));
					if($verbose){ echo "Phase Count between ".$val['rangeLow']." and ".$val['rangeHigh']." (COM)  + ".$val['comPercent']."%<br>"; }
				}
			}
		}




		if($pmSurchargeConfigs['pm_clear_documentation'] == 'YES'){
			if($momorcom=='mom'){
				$finalPercentage=($finalPercentage + floatval($documentationYesMOM));
				if($verbose){ echo "Clear Documentation YES (MOM)  + ".$documentationYesMOM."%<br>"; }
			}elseif($momorcom == 'com'){
				$finalPercentage=($finalPercentage + floatval($documentationYesCOM));
				if($verbose){ echo "Clear Documentation YES (COM)  + ".$documentationYesCOM."%<br>"; }
			}
		}elseif($pmSurchargeConfigs['pm_clear_documentation'] == 'NO'){
			if($momorcom=='mom'){
				$finalPercentage=($finalPercentage + floatval($documentationNoMOM));
				if($verbose){ echo "Clear Documentation NO (MOM)  + ".$documentationNoMOM."%<br>"; }
			}elseif($momorcom == 'com'){
				$finalPercentage=($finalPercentage + floatval($documentationNoCOM));
				if($verbose){ echo "Clear Documentation NO (COM)  + ".$documentationNoCOM."%<br>"; }
			}
		}




		foreach($readinessPercentages as $i => $val){
			$selectedReadinessSplit=explode("-",$pmSurchargeConfigs['time_between_readiness_and_deadline']);
			$selectedReadinessRangeLow=$selectedReadinessSplit[0];
			$selectedReadinessRangeHigh=$selectedReadinessSplit[1];

			if(floatval($selectedReadinessRangeLow) >= floatval($val['rangeLow']) && floatval($selectedReadinessRangeHigh) <= floatval($val['rangeHigh'])){
				if($momorcom=='mom'){
					$finalPercentage = ($finalPercentage + floatval($val['momPercent']));
					if($verbose){ echo "Building Readiness Range between ".$val['rangeLow']." and ".$val['rangeHigh']." weeks (MOM)  + ".$val['momPercent']."%<br>"; }
				}elseif($momorcom == 'com'){
					$finalPercentage = ($finalPercentage + floatval($val['comPercent']));
					if($verbose){ echo "Building Readiness Range between ".$val['rangeLow']." and ".$val['rangeHigh']." weeks (COM)  + ".$val['comPercent']."%<br>"; }
				}
			}
		}



		if($pmSurchargeConfigs['pm_shipnearbypriortoinstall'] == 'ALLOWED'){
			if($momorcom=='mom'){
				$finalPercentage=($finalPercentage + floatval($clientStorageYesMOM));
				if($verbose){ echo "Client Storage YES (MOM)  + ".$clientStorageYesMOM."%<br>"; }
			}elseif($momorcom=='com'){
				$finalPercentage=($finalPercentage + floatval($clientStorageYesCOM));
				if($verbose){ echo "Client Storage YES (COM)  + ".$clientStorageYesCOM."%<br>"; }
			}
		}elseif($pmSurchargeConfigs['pm_shipnearbypriortoinstall'] == 'NOT ALLOWED'){
			if($momorcom=='mom'){
				$finalPercentage=($finalPercentage + floatval($clientStorageNoMOM));
				if($verbose){ echo "Client Storage NO (MOM)  + ".$clientStorageNoMOM."%<br>"; }
			}elseif($momorcom=='com'){
				$finalPercentage=($finalPercentage + floatval($clientStorageNoCOM));
				if($verbose){ echo "Client Storage NO (COM)  + ".$clientStorageNoCOM."%<br>"; }
			}
		}



		$finalPercentage=($finalPercentage + floatval($pmSurchargeConfigs['pm_surcharge_unusual_addon_percent']));

		if($verbose){ echo "Unusual Add-on Percentage  + ".$pmSurchargeConfigs['pm_surcharge_unusual_addon_percent']."%<br>"; }


		if(!$verbose){
			if($finalPercentage != floatval($pmSurchargeConfigs['pms_percentage'])){
				//update it, recalculate the quote lines
				$pmSurchargeConfigs['pms_percentage'] = $finalPercentage;
				$quoteTable=TableRegistry::get('Quotes');
				$thisQuoteRow=$quoteTable->get($quoteID);
				$thisQuoteRow->project_management_surcharge_configs = urlencode(json_encode($pmSurchargeConfigs));
				if($quoteTable->save($thisQuoteRow)){
					return true;
				}
			}else{
				//no change, do nothing
				return true;
			}
		}else{
			echo "NEW TOTAL PERCENTAGE: ".$finalPercentage;
			exit;
		}


	}


	
	public function pmsurchargeform($quoteID){
		
		$this->viewBuilder()->layout('iframeinner');

		if($this->request->data){
			$this->autoRender=false;
			$quoteTable=TableRegistry::get('Quotes');
			$thisQuote=$quoteTable->get($quoteID);
			$thisQuote->project_management_surcharge_configs = urlencode(json_encode($this->request->data));
			if($quoteTable->save($thisQuote)){
				echo "Saved changes to PM Surcharge configuration<br>-Recalculating Lines...<br>";
				//recalculate all the line items and overall quote total
				if($this->recalculatequoteadjustments($quoteID)){
					echo "Updating quote table...";
					echo "<script>parent.updateQuoteTable();parent.$.fancybox.close();</script>";
				}
			}

		}else{

			$thisQuote=$this->Quotes->get($quoteID)->toArray();

			$this->set('quoteData',$thisQuote);

			//get all facility types
			$allSettings=$this->getsettingsarray();
			$this->set('settings',$allSettings);

			$splitFTs=json_decode(urldecode($allSettings['pm_surcharge_facility_types']),true);
			
			$this->set('facilityTypes',$splitFTs);

		}

	}


	public function add($quoteid=false){

		$this->autoRender=false;

		

		if(!$quoteid){

			if($this->request->data){

				//create a Quote ID and pass along to the subsequent steps

						

				$quoteTable=TableRegistry::get('Quotes');

				$newQuote=$quoteTable->newEntity();

				$newQuote->customer_id=$this->request->data['customer_id'];
				
				$newQuote->type_id = $this->request->data['type_id'];

				if(!isset($this->request->data['contact_id']) || $this->request->data['contact_id'] == ''){

					$newQuote->contact_id=0;

				}else{

					$newQuote->contact_id=$this->request->data['contact_id'];

				}

				$newQuote->status='draft';

				$newQuote->created=time();

				$newQuote->modified=time();

				

				$newQuote->expires=(time()+(intval($this->getSettingValue('quotes_expiration_in_days')) * 86400));

				$newQuote->created_by=$this->Auth->user('id');

				$newQuote->quote_number=$this->getnewquotenumber();

				

				if($quoteTable->save($newQuote)){

					$this->logActivity($_SERVER['REQUEST_URI'],'Created new quote '.$newQuote->quote_number);

					return $this->redirect('/quotes/add/'.$newQuote->id);

				}

				

			}else{

                /*
                $thisQuote=$this->Quotes->get($quoteid)->toArray();
            
                if($thisQuote['status'] == 'editorder'){
                    return $this->redirect('/orders/editlines/'.$thisQuote['order_id']);
                }
                */
                
                $allTypes=$this->QuoteTypes->find('all')->toArray();
                $this->set('allTypes',$allTypes);
            
				$customers=$this->Customers->find('list',['keyField' => 'id', 'valueField' => 'company_name', 'conditions'=>['status IN'=>['OnHold','Active']],'order'=>['company_name'=>'asc']])->toArray();

				$this->set('customers',$customers);

				$this->render('/Quotes/add');

			}

			

		}else{
                
            $allTypes=$this->QuoteTypes->find('all')->toArray();
            $this->set('allTypes',$allTypes);

            $thisQuote=$this->Quotes->get($quoteid)->toArray();
            
            if($thisQuote['status'] == 'editorder'){
                return $this->redirect('/orders/editlines/'.$thisQuote['order_id']);
            }
            
			$this->set('quoteID',$quoteid);
			$this->set('quoteData',$thisQuote);


			//find all projects for this customer

			$availableProjects=$this->Projects->find('all',['conditions'=>['customer_id' => $thisQuote['customer_id'],'status'=>'Active'],'order'=>['id'=>'desc']]);

			$this->set('availableProjects',$availableProjects);

			

			//see if this quote is actually an order

			$orderLookup=$this->Orders->find('all',['conditions'=>['quote_id' => $quoteid]])->toArray();

			if(count($orderLookup) > 0){

				foreach($orderLookup as $orderData){

					if($orderData['status'] == "Needs Line Items" || $orderData['status'] == "Editing"){

						return $this->redirect('/orders/editlines/'.$orderData['id']);

					}else{

						$this->Flash->error('This order is no longer editable.');

						return $this->redirect('/orders/editlines/'.$orderData['id']);

					}

				}

			}


			if($thisQuote['expires'] < time()){

				$this->Flash->error('This quote is EXPIRED.');

			}

			
			//$customerData=$this->Customers->get($thisQuote['customer_id'])->toArray();
			//$customerData=$this->Customers->find('all',['conditions'=>['id'=>$thisQuote['customer_id']]])->toArray();
			$customerData= $this->Customers->find('all',['conditions'=>['id'=>$thisQuote['customer_id']]])->first()->toArray();
			$this->set('customerData',$customerData);

			if($thisQuote['contact_id'] > 0){

				$customerContact=$this->CustomerContacts->get($thisQuote['contact_id'])->toArray();

				$this->set('customerContact',$customerContact);

			}

			

			$allcalculators=$this->Calculators->find('all')->toArray();

			$this->set('allcalculators',$allcalculators);

			

			$this->set('mode','quote');

			//gather all revisions for this quote number

			$revisions=$this->Quotes->find('all',['conditions' => ['quote_number' => $thisQuote['quote_number']]])->toArray();

			$this->set('revisions',$revisions);

			$allTypes=$this->QuoteTypes->find('all')->toArray();
            $this->set('allTypes',$allTypes);

			$this->render('/Quotes/addstep2');

		}

		

	}

	

	public function calculator($type){

		$this->viewBuilder()->layout('iframeinner');

       	$this->set('authUser', $this->Auth->user());

		

		if($this->request->data){

			switch($type){

				case "cubicle-curtain":

					

				break;

				case "box-pleated":

					

				break;

				case "straight-cornice":

					

				break;

				case "straight-cornice-rr":

					

				break;

				case "bedspread":

					

				break;

				case "bedspread-rr":

					

				break;

				case "cc-rating":

					

				break;

				case "cc-weightcalc":

					

				break;

				default:

					

				break;					

			}

		}else{

			

			$this->render('/Orders/calculators/'.$type);

		}

	}

	
	
	public function newlineitem($quoteID,$type,$subaction=false,$subsubaction=false,$subsubsubaction=false){

		$this->autoRender=false;

		//$this->viewBuilder()->layout('iframeinner');

	    $this->set('authUser', $this->Auth->user());

	    $userData=$this->Users->get($this->Auth->user('id'))->toArray();

	    $this->set('userData',$userData);

		$this->set('subaction',$subaction);

		$this->set('subsubaction',$subsubaction);

		$this->set('type',$type);

		$this->set('quoteID',$quoteID);

		$this->set('fabricslist',$this->getfabricsselectlist($subaction));

		if($quoteID > 0){

			$thisQuote=$this->Quotes->get($quoteID)->toArray();
			

			$this->set('quoteData',$thisQuote);


		}

		$allSettings=$this->getsettingsarray();

		$this->set('settings',$allSettings);

		

		$this->set('isEdit',false);


        
        $fabrics=$this->Fabrics->find('all',['conditions'=>['status'=>'Active']])->group('fabric_name')->toArray();
		$this->set('fabrics',$fabrics);

		$vendorsList=array();
		$vendors=$this->Vendors->find('all')->toArray();
		foreach($vendors as $vendor){
			$vendorsList[$vendor['id']]=$vendor['vendor_name'];
		}
		$this->set('vendorsList',$vendorsList);


        $libraryCatsLookup=$this->LibraryCategories->find('all')->toArray();
		$allCats=array();
		foreach($libraryCatsLookup as $catEntry){
			$allCats[$catEntry['id']]=$catEntry['category_title'];
		}
		
		$this->set('allLibraryCats',$allCats);
		
		$libraryimages=$this->LibraryImages->find('all',['conditions'=>['status'=>'Active']])->toArray();

		$this->set('libraryimages',$libraryimages);
		

		switch($type){
                
            case 'newcatchall-bedding':
                if($this->request->data){
                    
                    //make sure the image is an image
                    if(isset($this->request->data['imagefileupload']['tmp_name']) && $this->request->data['image_method'] == 'upload'){
                        $allowedTypes=array('.jpg','jpeg','.png','.gif');
    					if(!in_array(strtolower(substr($this->request->data['imagefileupload']['name'],-4)),$allowedTypes)){
    					    $this->autoRender=false;
    					    echo '<h3><span style="color:red;">ERROR:</span> The filetype <u>'.substr($this->request->data['imagefileupload']['name'],-4).'</u> is not allowed in the image field.</h3>
                            <p><button type="button" onclick="history.go(-1);">Go Back</button></p>';
    					    exit;
    					}
                    }
                    
                    //create the new entry
                    $newItem=$this->QuoteLineItems->newEntity();
                    $newItem->type='lineitem';
                    $newItem->quote_id=$quoteID;
                    $newItem->status='included';
                    $newItem->product_type='newcatchall-bedding';
                    $newItem->product_class=5;
                    $newItem->product_subclass=$this->request->data['product_subclass'];
                    $newItem->product_id=-1;
                    $newItem->title = $this->request->data['line_item_title'];
                    $newItem->description = $this->request->data['description'];
                    $newItem->best_price = $this->request->data['price'];
                    $newItem->qty = $this->request->data['qty'];
                    $newItem->lineitemtype='newcatchall';
                    $newItem->room_number = $this->request->data['location'];
                    $newItem->line_number=$this->getnextlinenumber($quoteID);
                    $newItem->internal_line=0;
                    
                    /**PPSA-40 start **/
                    $newItem->created=time();
                    /**PPSA-40 end **/
                    
                    $thisSubClass=$this->ProductSubclasses->get($this->request->data['product_subclass'])->toArray();
                    
                    $newItem->enable_tally=$thisSubClass['tally'];
                    
                    $newItem->sortorder = $this->getnextlinesortorder($quoteID);
                    $newItem->unit = $this->request->data['unit_type'];
                    $newItem->parent_line=0;
                    $newItem->calculator_used='';
                    $newItem->revised_from_line=0;
                    $newItem->project_management_surcharge_adjusted = NULL;
                    
                    $ignoreKeys=array('process','product_subclass','imagefileupload','description','line_item_title','customer_id','save_to_library','image_title','image_category','image_tags','fabric_name','fabric_color','libraryimageid');
                    if($this->QuoteLineItems->save($newItem)){
						
                        
                        //if this is EDIT ORDER function, add a line to Quote Item Add Log so it will know what to do when applying changes
                        if($thisQuote['status'] == 'editorder'){
                            $newAddLog=$this->QuoteItemAddLog->newEntity();
                            $newAddLog->original_quote_id = $thisQuote['parent_quote'];
                            $newAddLog->revision_id=$thisQuote['id'];
                            $newAddLog->line_item_id=$newItem->id;
                            $newAddLog->addtime=time();
                            $newAddLog->line_number=$newItem->line_number;
                            $this->QuoteItemAddLog->save($newAddLog);
                        }
                        
                        
                        //save all $_POST data to meta for this item
                        foreach($this->request->data as $postkey => $postdata){
                            if(!in_array($postkey,$ignoreKeys) && $postdata != ''){
                                
                                if($postkey=='fabric_type'){
                                    $postkey='fabrictype';
                                }
                                
                                $newLineItemMeta=$this->QuoteLineItemMeta->newEntity();
                                $newLineItemMeta->meta_key = $postkey;
                                $newLineItemMeta->meta_value = $postdata;
                                $newLineItemMeta->quote_item_id = $newItem->id;
                                $this->QuoteLineItemMeta->save($newLineItemMeta);
                            }
                        }
                        
                        
                        $newLineItemMeta=$this->QuoteLineItemMeta->newEntity();
                        $newLineItemMeta->quote_item_id = $newItem->id;
                        $newLineItemMeta->meta_key='lineitemtype';
                        $newLineItemMeta->meta_value='newcatchall';
                        $this->QuoteLineItemMeta->save($newLineItemMeta);
                        
                        
                        if($this->request->data['fabric_type']=="existing"){

							$newLineItemMeta=$this->QuoteLineItemMeta->newEntity();
							$newLineItemMeta->quote_item_id=$newItem->id;
							$newLineItemMeta->meta_key='fabricid';
							$newLineItemMeta->meta_value=$this->request->data['fabric_id_with_color'];
							$this->QuoteLineItemMeta->save($newLineItemMeta);
							
							$selectedFabric=$this->Fabrics->get($this->request->data['fabric_id_with_color'])->toArray();
							$newLineItemMeta=$this->QuoteLineItemMeta->newEntity();
							$newLineItemMeta->quote_item_id=$newItem->id;
							$newLineItemMeta->meta_key='fabric_name';
							$newLineItemMeta->meta_value=$selectedFabric['fabric_name'];
							$this->QuoteLineItemMeta->save($newLineItemMeta);

							$newLineItemMeta=$this->QuoteLineItemMeta->newEntity();
							$newLineItemMeta->quote_item_id=$newItem->id;
							$newLineItemMeta->meta_key='fabric_color';
							$newLineItemMeta->meta_value=$selectedFabric['color'];
							$this->QuoteLineItemMeta->save($newLineItemMeta);

						}elseif($this->request->data['fabric_type']=="typein"){

							$newLineItemMeta=$this->QuoteLineItemMeta->newEntity();
							$newLineItemMeta->quote_item_id=$newItem->id;
							$newLineItemMeta->meta_key='fabric_name';
							$newLineItemMeta->meta_value=$this->request->data['fabric_name'];
							$this->QuoteLineItemMeta->save($newLineItemMeta);
							
							$newLineItemMeta=$this->QuoteLineItemMeta->newEntity();
							$newLineItemMeta->quote_item_id=$newItem->id;
							$newLineItemMeta->meta_key='fabric_color';
							$newLineItemMeta->meta_value=$this->request->data['fabric_color'];

							$this->QuoteLineItemMeta->save($newLineItemMeta);
						}

						if($this->request->data['image_method'] == 'library'){
							$newLineItemMeta=$this->QuoteLineItemMeta->newEntity();
							$newLineItemMeta->quote_item_id=$newItem->id;
							$newLineItemMeta->meta_key='libraryimageid';
							$newLineItemMeta->meta_value=$this->request->data['libraryimageid'];
							$this->QuoteLineItemMeta->save($newLineItemMeta);
							
							$newLineItemMeta=$this->QuoteLineItemMeta->newEntity();
							$newLineItemMeta->quote_item_id=$newItem->id;
							$newLineItemMeta->meta_key='vendors_id';
							$newLineItemMeta->meta_value=$this->request->data['vendors_id'];
							$this->QuoteLineItemMeta->save($newLineItemMeta);
							
						}elseif($this->request->data['image_method'] == 'upload'){
							
							//handle the upload, and process whether to save this to library or just do a one-off insert
							$filename=$this->request->data['imagefileupload']['name'];
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
							
							
							move_uploaded_file($this->request->data['imagefileupload']['tmp_name'],$_SERVER['DOCUMENT_ROOT']."/webroot/img/library/".$filename);
							
							$imgLibraryTable=TableRegistry::get('LibraryImages');
							$newImage=$imgLibraryTable->newEntity();
							
							if($this->request->data['save_to_library'] == '1'){
								
								$selectedCat='';
								$allCats=$this->LibraryCategories->find('all')->toArray();
								foreach($allCats as $catEntry){
									if($catEntry['id'] == $this->request->data['image_category']){
										$selectedCat=$catEntry['category_title'];
									}
								}
								
								//insert into image library db with details for future usage
								$newImage->image_title=$this->request->data['image_title'];
								$newImage->categories=$selectedCat;
								$newImage->filename=$filename;
								$newImage->time=time();
								$newImage->added_by=$this->Auth->user('id');
								$newImage->tags=$this->request->data['image_tags'];
								$newImage->status='Active';
							}else{
								//insert into image library db with no details for single usage
								$newImage->image_title=$this->request->data['imagefileupload']['name'];
								$newImage->categories='Misc';
								$newImage->filename=$filename;
								$newImage->time=time();
								$newImage->added_by=$this->Auth->user('id');
								$newImage->status='Single Use';
							}
							
							if($imgLibraryTable->save($newImage)){
								//insert the metadata for libraryimageid
								$newLineItemMeta=$this->QuoteLineItemMeta->newEntity();
								$newLineItemMeta->quote_item_id=$newItem->id;
								$newLineItemMeta->meta_key='libraryimageid';
								$newLineItemMeta->meta_value=$newImage->id;
								$this->QuoteLineItemMeta->save($newLineItemMeta);

								$this->aspectratiofix($newImage->id,600);

							}
							
							
						}

						

						if($this->request->data['com-fabric']=='1'){

							$newLineItemMeta=$this->QuoteLineItemMeta->newEntity();
							$newLineItemMeta->quote_item_id=$newItem->id;

							$newLineItemMeta->meta_key='com-fabric';

							$newLineItemMeta->meta_value=1;

							$this->QuoteLineItemMeta->save($newLineItemMeta);

						}
						
						/*PPSA-33*/
                        if(isset($this->request->data['fabric-cost-per-yard-custom-value']) 
                        && !empty($this->request->data['fabric-cost-per-yard-custom-value'])){

								$newLineItemMeta=$this->QuoteLineItemMeta->newEntity();
								$newLineItemMeta->quote_item_id=$newItem->id;
								$newLineItemMeta->meta_key='fabric-cost-per-yard-custom-value';
								$newLineItemMeta->meta_value=number_format($this->request->data['fabric-cost-per-yard-custom-value'], 2, '.', '');
                                $this->QuoteLineItemMeta->save($newLineItemMeta);

						}
                        	/*PPSA-33*/
						/**PPSASSCRUM-29 start */	
						$orderDetails = $this->Orders->find('all',['conditions'=>['quote_id'=>$quoteID]])->first();

						if(isset($orderDetails) && !empty($orderDetails['id'])){
							$this->savetoOrderLineItemTables($newItem->id, $quoteID,$orderDetails['id'], $table='orders' );
							$this->savetoOrderLineItemTables($newItem->id, $quoteID,$orderDetails['id'], $table='work_orders' );	
						}
                      
						/**PPSASSCRUM-29 end */
                        
                        if($this->recalculatequoteadjustments($quoteID)){
							$this->Flash->success('Successfully added Bedding Catchall line item to quote');
							
							$this->logActivity($_SERVER['REQUEST_URI'],'Added Bedding Catchall to quote '.$thisQuote['quote_number'],$this->Auth->user('id'),json_encode($this->request));
						    return $this->redirect('/quotes/add/'.$quoteID);
						}
						
						
                        
                    }
                    
                    
                }else{
                    $subclasses=$this->ProductSubclasses->find('all',['conditions' => ['class_id' => 5], 'order' => ['subclass_name' => 'asc']])->toArray();
                    $this->set('subclasses',$subclasses);
                    
                    $vendorsList=array();
            		$vendors=$this->Vendors->find('all',['order'=>['vendor_name'=>'asc']])->toArray();
            		foreach($vendors as $vendor){
            			$vendorsList[$vendor['id']]=$vendor['vendor_name'];
            		}
            		$this->set('vendorsList',$vendorsList);
                    
                    $this->render('/Quotes/newcatchall-bedding');
                }
            break;
            
            case 'newcatchall-cubicle':
                if($this->request->data){
                    
                    //make sure the image is an image
                    if(isset($this->request->data['imagefileupload']['tmp_name']) && $this->request->data['image_method'] == 'upload'){
                        $allowedTypes=array('.jpg','jpeg','.png','.gif');
    					if(!in_array(strtolower(substr($this->request->data['imagefileupload']['name'],-4)),$allowedTypes)){
    					    $this->autoRender=false;
    					    echo '<h3><span style="color:red;">ERROR:</span> The filetype <u>'.substr($this->request->data['imagefileupload']['name'],-4).'</u> is not allowed in the image field.</h3>
                            <p><button type="button" onclick="history.go(-1);">Go Back</button></p>';
    					    exit;
    					}
                    }
                    
                    
                    //create the new entry
                    $newItem=$this->QuoteLineItems->newEntity();
                    $newItem->type='lineitem';
                    $newItem->quote_id=$quoteID;
                    $newItem->status='included';
                    $newItem->product_type='newcatchall-cubicle';
                    $newItem->product_class=4;
                    $newItem->product_subclass=$this->request->data['product_subclass'];
                    $newItem->product_id=-1;
                    $newItem->title = $this->request->data['line_item_title'];
                    $newItem->description = $this->request->data['description'];
                    $newItem->best_price = $this->request->data['price'];
                    $newItem->qty = $this->request->data['qty'];
                    $newItem->lineitemtype='newcatchall';
                    $newItem->room_number = $this->request->data['location'];
                    $newItem->line_number=$this->getnextlinenumber($quoteID);
                    $newItem->internal_line=0;
                    
                    /**PPSA-40 start **/
                    $newItem->created=time();
                    /**PPSA-40 end **/
                    
                    $thisSubClass=$this->ProductSubclasses->get($this->request->data['product_subclass'])->toArray();
                    
                    $newItem->enable_tally=$thisSubClass['tally'];
                    
                    $newItem->sortorder = $this->getnextlinesortorder($quoteID);
                    $newItem->unit = $this->request->data['unit_type'];
                    $newItem->parent_line=0;
                    $newItem->calculator_used='';
                    $newItem->revised_from_line=0;
                    $newItem->project_management_surcharge_adjusted = NULL;
                    
                    $ignoreKeys=array('process','product_subclass','imagefileupload','description','line_item_title','customer_id','save_to_library','image_title','image_category','image_tags','fabric_name','fabric_color','libraryimageid');
                    if($this->QuoteLineItems->save($newItem)){
                        
                        
                        //if this is EDIT ORDER function, add a line to Quote Item Add Log so it will know what to do when applying changes
                        if($thisQuote['status'] == 'editorder'){
                            $newAddLog=$this->QuoteItemAddLog->newEntity();
                            $newAddLog->original_quote_id = $thisQuote['parent_quote'];
                            $newAddLog->revision_id=$thisQuote['id'];
                            $newAddLog->line_item_id=$newItem->id;
                            $newAddLog->addtime=time();
                            $newAddLog->line_number=$newItem->line_number;
                            $this->QuoteItemAddLog->save($newAddLog);
                        }
                        
                        
                        //save all $_POST data to meta for this item
                        foreach($this->request->data as $postkey => $postdata){
                            if(!in_array($postkey,$ignoreKeys) && $postdata != ''){
                                
                                if($postkey=='fabric_type'){
                                    $postkey='fabrictype';
                                }
                                
                                $newLineItemMeta=$this->QuoteLineItemMeta->newEntity();
                                $newLineItemMeta->meta_key = $postkey;
                                $newLineItemMeta->meta_value = $postdata;
                                $newLineItemMeta->quote_item_id = $newItem->id;
                                $this->QuoteLineItemMeta->save($newLineItemMeta);
                            }
                        }
                        
                        
                        $newLineItemMeta=$this->QuoteLineItemMeta->newEntity();
                        $newLineItemMeta->quote_item_id = $newItem->id;
                        $newLineItemMeta->meta_key='lineitemtype';
                        $newLineItemMeta->meta_value='newcatchall';
                        $this->QuoteLineItemMeta->save($newLineItemMeta);
                        
                        
                        if($this->request->data['fabric_type']=="existing"){

							$newLineItemMeta=$this->QuoteLineItemMeta->newEntity();
							$newLineItemMeta->quote_item_id=$newItem->id;
							$newLineItemMeta->meta_key='fabricid';
							$newLineItemMeta->meta_value=$this->request->data['fabric_id_with_color'];
							$this->QuoteLineItemMeta->save($newLineItemMeta);
							
							$selectedFabric=$this->Fabrics->get($this->request->data['fabric_id_with_color'])->toArray();
							$newLineItemMeta=$this->QuoteLineItemMeta->newEntity();
							$newLineItemMeta->quote_item_id=$newItem->id;
							$newLineItemMeta->meta_key='fabric_name';
							$newLineItemMeta->meta_value=$selectedFabric['fabric_name'];
							$this->QuoteLineItemMeta->save($newLineItemMeta);

							$newLineItemMeta=$this->QuoteLineItemMeta->newEntity();
							$newLineItemMeta->quote_item_id=$newItem->id;
							$newLineItemMeta->meta_key='fabric_color';
							$newLineItemMeta->meta_value=$selectedFabric['color'];
							$this->QuoteLineItemMeta->save($newLineItemMeta);

						}elseif($this->request->data['fabric_type']=="typein"){

							$newLineItemMeta=$this->QuoteLineItemMeta->newEntity();
							$newLineItemMeta->quote_item_id=$newItem->id;
							$newLineItemMeta->meta_key='fabric_name';
							$newLineItemMeta->meta_value=$this->request->data['fabric_name'];
							$this->QuoteLineItemMeta->save($newLineItemMeta);
							
							$newLineItemMeta=$this->QuoteLineItemMeta->newEntity();
							$newLineItemMeta->quote_item_id=$newItem->id;
							$newLineItemMeta->meta_key='fabric_color';
							$newLineItemMeta->meta_value=$this->request->data['fabric_color'];

							$this->QuoteLineItemMeta->save($newLineItemMeta);
						}

						if($this->request->data['image_method'] == 'library'){
							$newLineItemMeta=$this->QuoteLineItemMeta->newEntity();
							$newLineItemMeta->quote_item_id=$newItem->id;
							$newLineItemMeta->meta_key='libraryimageid';
							$newLineItemMeta->meta_value=$this->request->data['libraryimageid'];
							$this->QuoteLineItemMeta->save($newLineItemMeta);
							
							$newLineItemMeta=$this->QuoteLineItemMeta->newEntity();
							$newLineItemMeta->quote_item_id=$newItem->id;
							$newLineItemMeta->meta_key='vendors_id';
							$newLineItemMeta->meta_value=$this->request->data['vendors_id'];
							$this->QuoteLineItemMeta->save($newLineItemMeta);
							
						}elseif($this->request->data['image_method'] == 'upload'){
							
							//handle the upload, and process whether to save this to library or just do a one-off insert
							$filename=$this->request->data['imagefileupload']['name'];
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
							
							
							move_uploaded_file($this->request->data['imagefileupload']['tmp_name'],$_SERVER['DOCUMENT_ROOT']."/webroot/img/library/".$filename);
							
							$imgLibraryTable=TableRegistry::get('LibraryImages');
							$newImage=$imgLibraryTable->newEntity();
							
							if($this->request->data['save_to_library'] == '1'){
								
								$selectedCat='';
								$allCats=$this->LibraryCategories->find('all')->toArray();
								foreach($allCats as $catEntry){
									if($catEntry['id'] == $this->request->data['image_category']){
										$selectedCat=$catEntry['category_title'];
									}
								}
								
								//insert into image library db with details for future usage
								$newImage->image_title=$this->request->data['image_title'];
								$newImage->categories=$selectedCat;
								$newImage->filename=$filename;
								$newImage->time=time();
								$newImage->added_by=$this->Auth->user('id');
								$newImage->tags=$this->request->data['image_tags'];
								$newImage->status='Active';
							}else{
								//insert into image library db with no details for single usage
								$newImage->image_title=$this->request->data['imagefileupload']['name'];
								$newImage->categories='Misc';
								$newImage->filename=$filename;
								$newImage->time=time();
								$newImage->added_by=$this->Auth->user('id');
								$newImage->status='Single Use';
							}
							
							if($imgLibraryTable->save($newImage)){
								//insert the metadata for libraryimageid
								$newLineItemMeta=$this->QuoteLineItemMeta->newEntity();
								$newLineItemMeta->quote_item_id=$newItem->id;
								$newLineItemMeta->meta_key='libraryimageid';
								$newLineItemMeta->meta_value=$newImage->id;
								$this->QuoteLineItemMeta->save($newLineItemMeta);

								$this->aspectratiofix($newImage->id,600);

							}
							
							
						}

						

						if($this->request->data['com-fabric']=='1'){

							$newLineItemMeta=$this->QuoteLineItemMeta->newEntity();
							$newLineItemMeta->quote_item_id=$newItem->id;

							$newLineItemMeta->meta_key='com-fabric';

							$newLineItemMeta->meta_value=1;

							$this->QuoteLineItemMeta->save($newLineItemMeta);

						}
                        
                        /*PPSA-33*/
                        if(isset($this->request->data['fabric-cost-per-yard-custom-value']) 
                        &&  !empty($this->request->data['fabric-cost-per-yard-custom-value'])){

								$newLineItemMeta=$this->QuoteLineItemMeta->newEntity();
								$newLineItemMeta->quote_item_id=$newLineItem->id;
								$newLineItemMeta->meta_key='fabric-cost-per-yard-custom-value';
								$newLineItemMeta->meta_value=number_format($this->request->data['fabric-cost-per-yard-custom-value'], 2, '.', '');
                                $this->QuoteLineItemMeta->save($newLineItemMeta);

						}
                        	/*PPSA-33*/
						/**PPSASSCRUM-29 start */	
						$orderDetails = $this->Orders->find('all',['conditions'=>['quote_id'=>$quoteID]])->first();

						if(isset($orderDetails) && !empty($orderDetails['id'])){
							$this->savetoOrderLineItemTables($newItem->id, $quoteID,$orderDetails['id'], $table='orders' );
							$this->savetoOrderLineItemTables($newItem->id, $quoteID,$orderDetails['id'], $table='work_orders' );	
						}
                      
						/**PPSASSCRUM-29 end */
                      
                        if($this->recalculatequoteadjustments($quoteID)){
							$this->Flash->success('Successfully added Cubicle Catchall line item to quote');
							
							$this->logActivity($_SERVER['REQUEST_URI'],'Added Cubicle Catchall to quote '.$thisQuote['quote_number'],$this->Auth->user('id'),json_encode($this->request));
						    return $this->redirect('/quotes/add/'.$quoteID);
						}
						
						
                        
                    }
                    
                    
                }else{
                    $subclasses=$this->ProductSubclasses->find('all',['conditions' => ['class_id' => 4], 'order' => ['subclass_name' => 'asc']])->toArray();
                    $this->set('subclasses',$subclasses);
                    
                    $vendorsList=array();
            		$vendors=$this->Vendors->find('all',['order'=>['vendor_name'=>'asc']])->toArray();
            		foreach($vendors as $vendor){
            			$vendorsList[$vendor['id']]=$vendor['vendor_name'];
            		}
            		$this->set('vendorsList',$vendorsList);
                    
                    $this->render('/Quotes/newcatchall-cubicle');
                }
            break;
            
            case 'newcatchall-cornice':
                if($this->request->data){
                    
                    //make sure the image is an image
                    if(isset($this->request->data['imagefileupload']['tmp_name']) && $this->request->data['image_method'] == 'upload'){
                        $allowedTypes=array('.jpg','jpeg','.png','.gif');
    					if(!in_array(strtolower(substr($this->request->data['imagefileupload']['name'],-4)),$allowedTypes)){
    					    $this->autoRender=false;
    					    echo '<h3><span style="color:red;">ERROR:</span> The filetype <u>'.substr($this->request->data['imagefileupload']['name'],-4).'</u> is not allowed in the image field.</h3>
                            <p><button type="button" onclick="history.go(-1);">Go Back</button></p>';
    					    exit;
    					}
                    }
                    
                    
                    //create the new entry
                    $newItem=$this->QuoteLineItems->newEntity();
                    $newItem->type='lineitem';
                    $newItem->quote_id=$quoteID;
                    $newItem->status='included';
                    $newItem->product_type='newcatchall-cornice';
                    $newItem->product_class=3;
                    $newItem->product_subclass=11;
                    $newItem->product_id=-1;
                    $newItem->title = $this->request->data['line_item_title'];
                    $newItem->description = $this->request->data['description'];
                    $newItem->best_price = $this->request->data['price'];
                    $newItem->qty = $this->request->data['qty'];
                    $newItem->lineitemtype='newcatchall';
                    $newItem->room_number = $this->request->data['location'];
                    $newItem->line_number=$this->getnextlinenumber($quoteID);
                    $newItem->internal_line=0;
                    /**PPSA-40 start **/
                    $newItem->created=time();
                    /**PPSA-40 end **/
                    
                    
                    $thisSubClass=$this->ProductSubclasses->get(11)->toArray();
                    
                    $newItem->enable_tally=$thisSubClass['tally'];
                    
                    $newItem->sortorder = $this->getnextlinesortorder($quoteID);
                    $newItem->unit = $this->request->data['unit_type'];
                    $newItem->parent_line=0;
                    $newItem->calculator_used='';
                    $newItem->revised_from_line=0;
                    $newItem->project_management_surcharge_adjusted = NULL;
                    
                    $ignoreKeys=array('process','product_subclass','imagefileupload','description','line_item_title','customer_id','save_to_library','image_title','image_category','image_tags','fabric_name','fabric_color','libraryimageid');
                    if($this->QuoteLineItems->save($newItem)){
                        
                        
                        //if this is EDIT ORDER function, add a line to Quote Item Add Log so it will know what to do when applying changes
                        if($thisQuote['status'] == 'editorder'){
                            $newAddLog=$this->QuoteItemAddLog->newEntity();
                            $newAddLog->original_quote_id = $thisQuote['parent_quote'];
                            $newAddLog->revision_id=$thisQuote['id'];
                            $newAddLog->line_item_id=$newItem->id;
                            $newAddLog->addtime=time();
                            $newAddLog->line_number=$newItem->line_number;
                            $this->QuoteItemAddLog->save($newAddLog);
                        }
                        
                        
                        //save all $_POST data to meta for this item
                        foreach($this->request->data as $postkey => $postdata){
                            if(!in_array($postkey,$ignoreKeys) && $postdata != ''){
                                
                                if($postkey=='fabric_type'){
                                    $postkey='fabrictype';
                                }
                                
                                $newLineItemMeta=$this->QuoteLineItemMeta->newEntity();
                                $newLineItemMeta->meta_key = $postkey;
                                $newLineItemMeta->meta_value = $postdata;
                                $newLineItemMeta->quote_item_id = $newItem->id;
                                $this->QuoteLineItemMeta->save($newLineItemMeta);
                            }
                        }
                        
                        
                        $newLineItemMeta=$this->QuoteLineItemMeta->newEntity();
                        $newLineItemMeta->quote_item_id = $newItem->id;
                        $newLineItemMeta->meta_key='lineitemtype';
                        $newLineItemMeta->meta_value='newcatchall';
                        $this->QuoteLineItemMeta->save($newLineItemMeta);
                        
                        
                        if($this->request->data['fabric_type']=="existing"){

							$newLineItemMeta=$this->QuoteLineItemMeta->newEntity();
							$newLineItemMeta->quote_item_id=$newItem->id;
							$newLineItemMeta->meta_key='fabricid';
							$newLineItemMeta->meta_value=$this->request->data['fabric_id_with_color'];
							$this->QuoteLineItemMeta->save($newLineItemMeta);
							
							$selectedFabric=$this->Fabrics->get($this->request->data['fabric_id_with_color'])->toArray();
							$newLineItemMeta=$this->QuoteLineItemMeta->newEntity();
							$newLineItemMeta->quote_item_id=$newItem->id;
							$newLineItemMeta->meta_key='fabric_name';
							$newLineItemMeta->meta_value=$selectedFabric['fabric_name'];
							$this->QuoteLineItemMeta->save($newLineItemMeta);

							$newLineItemMeta=$this->QuoteLineItemMeta->newEntity();
							$newLineItemMeta->quote_item_id=$newItem->id;
							$newLineItemMeta->meta_key='fabric_color';
							$newLineItemMeta->meta_value=$selectedFabric['color'];
							$this->QuoteLineItemMeta->save($newLineItemMeta);

						}elseif($this->request->data['fabric_type']=="typein"){

							$newLineItemMeta=$this->QuoteLineItemMeta->newEntity();
							$newLineItemMeta->quote_item_id=$newItem->id;
							$newLineItemMeta->meta_key='fabric_name';
							$newLineItemMeta->meta_value=$this->request->data['fabric_name'];
							$this->QuoteLineItemMeta->save($newLineItemMeta);
							
							$newLineItemMeta=$this->QuoteLineItemMeta->newEntity();
							$newLineItemMeta->quote_item_id=$newItem->id;
							$newLineItemMeta->meta_key='fabric_color';
							$newLineItemMeta->meta_value=$this->request->data['fabric_color'];

							$this->QuoteLineItemMeta->save($newLineItemMeta);
						}

						if($this->request->data['image_method'] == 'library'){
							$newLineItemMeta=$this->QuoteLineItemMeta->newEntity();
							$newLineItemMeta->quote_item_id=$newItem->id;
							$newLineItemMeta->meta_key='libraryimageid';
							$newLineItemMeta->meta_value=$this->request->data['libraryimageid'];
							$this->QuoteLineItemMeta->save($newLineItemMeta);
							
							$newLineItemMeta=$this->QuoteLineItemMeta->newEntity();
							$newLineItemMeta->quote_item_id=$newItem->id;
							$newLineItemMeta->meta_key='vendors_id';
							$newLineItemMeta->meta_value=$this->request->data['vendors_id'];
							$this->QuoteLineItemMeta->save($newLineItemMeta);
							
						}elseif($this->request->data['image_method'] == 'upload'){
							
							//handle the upload, and process whether to save this to library or just do a one-off insert
							$filename=$this->request->data['imagefileupload']['name'];
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
							
							
							move_uploaded_file($this->request->data['imagefileupload']['tmp_name'],$_SERVER['DOCUMENT_ROOT']."/webroot/img/library/".$filename);
							
							$imgLibraryTable=TableRegistry::get('LibraryImages');
							$newImage=$imgLibraryTable->newEntity();
							
							if($this->request->data['save_to_library'] == '1'){
								
								$selectedCat='';
								$allCats=$this->LibraryCategories->find('all')->toArray();
								foreach($allCats as $catEntry){
									if($catEntry['id'] == $this->request->data['image_category']){
										$selectedCat=$catEntry['category_title'];
									}
								}
								
								//insert into image library db with details for future usage
								$newImage->image_title=$this->request->data['image_title'];
								$newImage->categories=$selectedCat;
								$newImage->filename=$filename;
								$newImage->time=time();
								$newImage->added_by=$this->Auth->user('id');
								$newImage->tags=$this->request->data['image_tags'];
								$newImage->status='Active';
							}else{
								//insert into image library db with no details for single usage
								$newImage->image_title=$this->request->data['imagefileupload']['name'];
								$newImage->categories='Misc';
								$newImage->filename=$filename;
								$newImage->time=time();
								$newImage->added_by=$this->Auth->user('id');
								$newImage->status='Single Use';
							}
							
							if($imgLibraryTable->save($newImage)){
								//insert the metadata for libraryimageid
								$newLineItemMeta=$this->QuoteLineItemMeta->newEntity();
								$newLineItemMeta->quote_item_id=$newItem->id;
								$newLineItemMeta->meta_key='libraryimageid';
								$newLineItemMeta->meta_value=$newImage->id;
								$this->QuoteLineItemMeta->save($newLineItemMeta);

								$this->aspectratiofix($newImage->id,600);

							}
							
							
						}

						

						if($this->request->data['com-fabric']=='1'){

							$newLineItemMeta=$this->QuoteLineItemMeta->newEntity();
							$newLineItemMeta->quote_item_id=$newItem->id;

							$newLineItemMeta->meta_key='com-fabric';

							$newLineItemMeta->meta_value=1;

							$this->QuoteLineItemMeta->save($newLineItemMeta);

						}
                        
                        
                        /*PPSA-33*/
                        if(isset($this->request->data['fabric-cost-per-yard-custom-value']) 
                        &&  !empty($this->request->data['fabric-cost-per-yard-custom-value'])){

								$newLineItemMeta=$this->QuoteLineItemMeta->newEntity();
								$newLineItemMeta->quote_item_id=$newLineItem->id;
								$newLineItemMeta->meta_key='fabric-cost-per-yard-custom-value';
								$newLineItemMeta->meta_value=number_format($this->request->data['fabric-cost-per-yard-custom-value'], 2, '.', '');
                                $this->QuoteLineItemMeta->save($newLineItemMeta);

						}
                        	/*PPSA-33*/
                        	/**PPSASSCRUM-29 start */	
						$orderDetails = $this->Orders->find('all',['conditions'=>['quote_id'=>$quoteID]])->first();

						/**PPSASSCRUM-29 end */
						if(isset($orderDetails) && !empty($orderDetails['id'])){
							$this->savetoOrderLineItemTables($newItem->id, $quoteID,$orderDetails['id'], $table='orders' );
							$this->savetoOrderLineItemTables($newItem->id, $quoteID,$orderDetails['id'], $table='work_orders' );	
						}
                      
						/**PPSASSCRUM-29 end */
                        if($this->recalculatequoteadjustments($quoteID)){
							$this->Flash->success('Successfully added SWT Cornice Catchall line item to quote');
							
							$this->logActivity($_SERVER['REQUEST_URI'],'Added SWT Cornice Catchall to quote '.$thisQuote['quote_number'],$this->Auth->user('id'),json_encode($this->request));
						    return $this->redirect('/quotes/add/'.$quoteID);
						}
						
						
                        
                    }
                    
                }else{
                    
                    $linings=$this->Linings->find('all')->toArray();
					$this->set('linings',$linings);
					
					$vendorsList=array();
            		$vendors=$this->Vendors->find('all',['order'=>['vendor_name'=>'asc']])->toArray();
            		foreach($vendors as $vendor){
            			$vendorsList[$vendor['id']]=$vendor['vendor_name'];
            		}
            		$this->set('vendorsList',$vendorsList);
					
                    $this->render('/Quotes/newcatchall-cornice');
                }
            break;
            
            case 'newcatchall-drapery':
                if($this->request->data){
                    
                    //make sure the image is an image
                    if(isset($this->request->data['imagefileupload']['tmp_name']) && $this->request->data['image_method'] == 'upload'){
                        $allowedTypes=array('.jpg','jpeg','.png','.gif');
    					if(!in_array(strtolower(substr($this->request->data['imagefileupload']['name'],-4)),$allowedTypes)){
    					    $this->autoRender=false;
    					    echo '<h3><span style="color:red;">ERROR:</span> The filetype <u>'.substr($this->request->data['imagefileupload']['name'],-4).'</u> is not allowed in the image field.</h3>
                            <p><button type="button" onclick="history.go(-1);">Go Back</button></p>';
    					    exit;
    					}
                    }
                    
                    
                    //create the new entry
                    $newItem=$this->QuoteLineItems->newEntity();
                    $newItem->type='lineitem';
                    $newItem->quote_id=$quoteID;
                    $newItem->status='included';
                    $newItem->product_type='newcatchall-drapery';
                    $newItem->product_class=3;
                    $newItem->product_subclass=10;
                    $newItem->product_id=-1;
                    $newItem->title = $this->request->data['line_item_title'];
                    $newItem->description = $this->request->data['description'];
                    $newItem->best_price = $this->request->data['price'];
                    $newItem->qty = $this->request->data['qty'];
                    $newItem->lineitemtype='newcatchall';
                    $newItem->room_number = $this->request->data['location'];
                    $newItem->line_number=$this->getnextlinenumber($quoteID);
                    $newItem->internal_line=0;
                    
                    /**PPSA-40 start **/
                    $newItem->created=time();
                    /**PPSA-40 end **/
                    
                    $thisSubClass=$this->ProductSubclasses->get(10)->toArray();
                    
                    $newItem->enable_tally=$thisSubClass['tally'];
                    
                    
                    $newItem->sortorder = $this->getnextlinesortorder($quoteID);
                    $newItem->unit = $this->request->data['unit_type'];
                    $newItem->parent_line=0;
                    $newItem->calculator_used='';
                    $newItem->revised_from_line=0;
                    $newItem->project_management_surcharge_adjusted = NULL;
                    
                    $ignoreKeys=array('process','product_subclass','imagefileupload','description','line_item_title','customer_id','save_to_library','image_title','image_category','image_tags','fabric_name','fabric_color','libraryimageid');
                    if($this->QuoteLineItems->save($newItem)){
                        
                        
                        //if this is EDIT ORDER function, add a line to Quote Item Add Log so it will know what to do when applying changes
                        if($thisQuote['status'] == 'editorder'){
                            $newAddLog=$this->QuoteItemAddLog->newEntity();
                            $newAddLog->original_quote_id = $thisQuote['parent_quote'];
                            $newAddLog->revision_id=$thisQuote['id'];
                            $newAddLog->line_item_id=$newItem->id;
                            $newAddLog->addtime=time();
                            $newAddLog->line_number=$newItem->line_number;
                            $this->QuoteItemAddLog->save($newAddLog);
                        }
                        
                        
                        //save all $_POST data to meta for this item
                        foreach($this->request->data as $postkey => $postdata){
                            if(!in_array($postkey,$ignoreKeys) && $postdata != ''){
                                
                                if($postkey=='fabric_type'){
                                    $postkey='fabrictype';
                                }
                                
                                $newLineItemMeta=$this->QuoteLineItemMeta->newEntity();
                                $newLineItemMeta->meta_key = $postkey;
                                $newLineItemMeta->meta_value = $postdata;
                                $newLineItemMeta->quote_item_id = $newItem->id;
                                $this->QuoteLineItemMeta->save($newLineItemMeta);
                            }
                        }
                        
                        
                        $newLineItemMeta=$this->QuoteLineItemMeta->newEntity();
                        $newLineItemMeta->quote_item_id = $newItem->id;
                        $newLineItemMeta->meta_key='lineitemtype';
                        $newLineItemMeta->meta_value='newcatchall';
                        $this->QuoteLineItemMeta->save($newLineItemMeta);
                        
                        
                        if($this->request->data['fabric_type']=="existing"){

							$newLineItemMeta=$this->QuoteLineItemMeta->newEntity();
							$newLineItemMeta->quote_item_id=$newItem->id;
							$newLineItemMeta->meta_key='fabricid';
							$newLineItemMeta->meta_value=$this->request->data['fabric_id_with_color'];
							$this->QuoteLineItemMeta->save($newLineItemMeta);
							
							$selectedFabric=$this->Fabrics->get($this->request->data['fabric_id_with_color'])->toArray();
							$newLineItemMeta=$this->QuoteLineItemMeta->newEntity();
							$newLineItemMeta->quote_item_id=$newItem->id;
							$newLineItemMeta->meta_key='fabric_name';
							$newLineItemMeta->meta_value=$selectedFabric['fabric_name'];
							$this->QuoteLineItemMeta->save($newLineItemMeta);

							$newLineItemMeta=$this->QuoteLineItemMeta->newEntity();
							$newLineItemMeta->quote_item_id=$newItem->id;
							$newLineItemMeta->meta_key='fabric_color';
							$newLineItemMeta->meta_value=$selectedFabric['color'];
							$this->QuoteLineItemMeta->save($newLineItemMeta);

						}elseif($this->request->data['fabric_type']=="typein"){

							$newLineItemMeta=$this->QuoteLineItemMeta->newEntity();
							$newLineItemMeta->quote_item_id=$newItem->id;
							$newLineItemMeta->meta_key='fabric_name';
							$newLineItemMeta->meta_value=$this->request->data['fabric_name'];
							$this->QuoteLineItemMeta->save($newLineItemMeta);
							
							$newLineItemMeta=$this->QuoteLineItemMeta->newEntity();
							$newLineItemMeta->quote_item_id=$newItem->id;
							$newLineItemMeta->meta_key='fabric_color';
							$newLineItemMeta->meta_value=$this->request->data['fabric_color'];

							$this->QuoteLineItemMeta->save($newLineItemMeta);
						}

						if($this->request->data['image_method'] == 'library'){
							$newLineItemMeta=$this->QuoteLineItemMeta->newEntity();
							$newLineItemMeta->quote_item_id=$newItem->id;
							$newLineItemMeta->meta_key='libraryimageid';
							$newLineItemMeta->meta_value=$this->request->data['libraryimageid'];
							$this->QuoteLineItemMeta->save($newLineItemMeta);
							
							$newLineItemMeta=$this->QuoteLineItemMeta->newEntity();
							$newLineItemMeta->quote_item_id=$newItem->id;
							$newLineItemMeta->meta_key='vendors_id';
							$newLineItemMeta->meta_value=$this->request->data['vendors_id'];
							$this->QuoteLineItemMeta->save($newLineItemMeta);
							
						}elseif($this->request->data['image_method'] == 'upload'){
							
							//handle the upload, and process whether to save this to library or just do a one-off insert
							$filename=$this->request->data['imagefileupload']['name'];
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
							
							
							move_uploaded_file($this->request->data['imagefileupload']['tmp_name'],$_SERVER['DOCUMENT_ROOT']."/webroot/img/library/".$filename);
							
							$imgLibraryTable=TableRegistry::get('LibraryImages');
							$newImage=$imgLibraryTable->newEntity();
							
							if($this->request->data['save_to_library'] == '1'){
								
								$selectedCat='';
								$allCats=$this->LibraryCategories->find('all')->toArray();
								foreach($allCats as $catEntry){
									if($catEntry['id'] == $this->request->data['image_category']){
										$selectedCat=$catEntry['category_title'];
									}
								}
								
								//insert into image library db with details for future usage
								$newImage->image_title=$this->request->data['image_title'];
								$newImage->categories=$selectedCat;
								$newImage->filename=$filename;
								$newImage->time=time();
								$newImage->added_by=$this->Auth->user('id');
								$newImage->tags=$this->request->data['image_tags'];
								$newImage->status='Active';
							}else{
								//insert into image library db with no details for single usage
								$newImage->image_title=$this->request->data['imagefileupload']['name'];
								$newImage->categories='Misc';
								$newImage->filename=$filename;
								$newImage->time=time();
								$newImage->added_by=$this->Auth->user('id');
								$newImage->status='Single Use';
							}
							
							if($imgLibraryTable->save($newImage)){
								//insert the metadata for libraryimageid
								$newLineItemMeta=$this->QuoteLineItemMeta->newEntity();
								$newLineItemMeta->quote_item_id=$newItem->id;
								$newLineItemMeta->meta_key='libraryimageid';
								$newLineItemMeta->meta_value=$newImage->id;
								$this->QuoteLineItemMeta->save($newLineItemMeta);

								$this->aspectratiofix($newImage->id,600);

							}
							
							
						}

						

						if($this->request->data['com-fabric']=='1'){

							$newLineItemMeta=$this->QuoteLineItemMeta->newEntity();
							$newLineItemMeta->quote_item_id=$newItem->id;

							$newLineItemMeta->meta_key='com-fabric';

							$newLineItemMeta->meta_value=1;

							$this->QuoteLineItemMeta->save($newLineItemMeta);

						}
                        
                        
                         /*PPSA-33*/
                        if(isset($this->request->data['fabric-cost-per-yard-custom-value']) 
                        && !empty($this->request->data['fabric-cost-per-yard-custom-value'])){

								$newLineItemMeta=$this->QuoteLineItemMeta->newEntity();
								$newLineItemMeta->quote_item_id=$newItem->id;
								$newLineItemMeta->meta_key='fabric-cost-per-yard-custom-value';
								$newLineItemMeta->meta_value=number_format($this->request->data['fabric-cost-per-yard-custom-value'], 2, '.', '');
                                $this->QuoteLineItemMeta->save($newLineItemMeta);

						}
                        	/*PPSA-33*/
                        	/**PPSASSCRUM-29 start */	
							$orderDetails = $this->Orders->find('all',['conditions'=>['quote_id'=>$quoteID]])->first();

						if(isset($orderDetails) && !empty($orderDetails['id'])){
							$this->savetoOrderLineItemTables($newItem->id, $quoteID,$orderDetails['id'], $table='orders' );
							$this->savetoOrderLineItemTables($newItem->id, $quoteID,$orderDetails['id'], $table='work_orders' );	
						}
                      
						/**PPSASSCRUM-29 end */
                        if($this->recalculatequoteadjustments($quoteID)){
							$this->Flash->success('Successfully added SWT Drapery Catchall line item to quote');
							
							$this->logActivity($_SERVER['REQUEST_URI'],'Added SWT Drapery Catchall to quote '.$thisQuote['quote_number'],$this->Auth->user('id'),json_encode($this->request));
						    return $this->redirect('/quotes/add/'.$quoteID);
						}
						
						
                        
                    }
                    
                    
                }else{
                    
                    $linings=$this->Linings->find('all')->toArray();
					$this->set('linings',$linings);
					
					$vendorsList=array();
            		$vendors=$this->Vendors->find('all',['order'=>['vendor_name'=>'asc']])->toArray();
            		foreach($vendors as $vendor){
            			$vendorsList[$vendor['id']]=$vendor['vendor_name'];
            		}
            		$this->set('vendorsList',$vendorsList);
					
                    $this->render('/Quotes/newcatchall-drapery');
                }
            break;
            
            case 'newcatchall-valance':
                if($this->request->data){
                    
                    
                    //make sure the image is an image
                    if(isset($this->request->data['imagefileupload']['tmp_name']) && $this->request->data['image_method'] == 'upload'){
                        $allowedTypes=array('.jpg','jpeg','.png','.gif');
    					if(!in_array(strtolower(substr($this->request->data['imagefileupload']['name'],-4)),$allowedTypes)){
    					    $this->autoRender=false;
    					    echo '<h3><span style="color:red;">ERROR:</span> The filetype <u>'.substr($this->request->data['imagefileupload']['name'],-4).'</u> is not allowed in the image field.</h3>
                            <p><button type="button" onclick="history.go(-1);">Go Back</button></p>';
    					    exit;
    					}
                    }
                    
                    
                    //create the new entry
                    $newItem=$this->QuoteLineItems->newEntity();
                    $newItem->type='lineitem';
                    $newItem->quote_id=$quoteID;
                    $newItem->status='included';
                    $newItem->product_type='newcatchall-valance';
                    $newItem->product_class=3;
                    $newItem->product_subclass=9;
                    $newItem->product_id=-1;
                    $newItem->title = $this->request->data['line_item_title'];
                    $newItem->description = $this->request->data['description'];
                    $newItem->best_price = $this->request->data['price'];
                    $newItem->qty = $this->request->data['qty'];
                    $newItem->lineitemtype='newcatchall';
                    $newItem->room_number = $this->request->data['location'];
                    $newItem->line_number=$this->getnextlinenumber($quoteID);
                    $newItem->internal_line=0;
                    
                    /**PPSA-40 start **/
                    $newItem->created=time();
                    /**PPSA-40 end **/
                    
                    $thisSubClass=$this->ProductSubclasses->get(9)->toArray();
                    
                    $newItem->enable_tally=$thisSubClass['tally'];
                    
                    $newItem->sortorder = $this->getnextlinesortorder($quoteID);
                    $newItem->unit = $this->request->data['unit_type'];
                    $newItem->parent_line=0;
                    $newItem->calculator_used='';
                    $newItem->revised_from_line=0;
                    $newItem->project_management_surcharge_adjusted = NULL;
                    
                    $ignoreKeys=array('process','product_subclass','imagefileupload','description','line_item_title','customer_id','save_to_library','image_title','image_category','image_tags','fabric_name','fabric_color','libraryimageid');
                    if($this->QuoteLineItems->save($newItem)){
                        
                        
                        //if this is EDIT ORDER function, add a line to Quote Item Add Log so it will know what to do when applying changes
                        if($thisQuote['status'] == 'editorder'){
                            $newAddLog=$this->QuoteItemAddLog->newEntity();
                            $newAddLog->original_quote_id = $thisQuote['parent_quote'];
                            $newAddLog->revision_id=$thisQuote['id'];
                            $newAddLog->line_item_id=$newItem->id;
                            $newAddLog->addtime=time();
                            $newAddLog->line_number=$newItem->line_number;
                            $this->QuoteItemAddLog->save($newAddLog);
                        }
                        
                        
                        //save all $_POST data to meta for this item
                        foreach($this->request->data as $postkey => $postdata){
                            if(!in_array($postkey,$ignoreKeys) && $postdata != ''){
                                
                                if($postkey=='fabric_type'){
                                    $postkey='fabrictype';
                                }
                                
                                $newLineItemMeta=$this->QuoteLineItemMeta->newEntity();
                                $newLineItemMeta->meta_key = $postkey;
                                $newLineItemMeta->meta_value = $postdata;
                                $newLineItemMeta->quote_item_id = $newItem->id;
                                $this->QuoteLineItemMeta->save($newLineItemMeta);
                            }
                        }
                        
                        
                        $newLineItemMeta=$this->QuoteLineItemMeta->newEntity();
                        $newLineItemMeta->quote_item_id = $newItem->id;
                        $newLineItemMeta->meta_key='lineitemtype';
                        $newLineItemMeta->meta_value='newcatchall';
                        $this->QuoteLineItemMeta->save($newLineItemMeta);
                        
                        
                        if($this->request->data['fabric_type']=="existing"){

							$newLineItemMeta=$this->QuoteLineItemMeta->newEntity();
							$newLineItemMeta->quote_item_id=$newItem->id;
							$newLineItemMeta->meta_key='fabricid';
							$newLineItemMeta->meta_value=$this->request->data['fabric_id_with_color'];
							$this->QuoteLineItemMeta->save($newLineItemMeta);
							
							$selectedFabric=$this->Fabrics->get($this->request->data['fabric_id_with_color'])->toArray();
							$newLineItemMeta=$this->QuoteLineItemMeta->newEntity();
							$newLineItemMeta->quote_item_id=$newItem->id;
							$newLineItemMeta->meta_key='fabric_name';
							$newLineItemMeta->meta_value=$selectedFabric['fabric_name'];
							$this->QuoteLineItemMeta->save($newLineItemMeta);

							$newLineItemMeta=$this->QuoteLineItemMeta->newEntity();
							$newLineItemMeta->quote_item_id=$newItem->id;
							$newLineItemMeta->meta_key='fabric_color';
							$newLineItemMeta->meta_value=$selectedFabric['color'];
							$this->QuoteLineItemMeta->save($newLineItemMeta);

						}elseif($this->request->data['fabric_type']=="typein"){

							$newLineItemMeta=$this->QuoteLineItemMeta->newEntity();
							$newLineItemMeta->quote_item_id=$newItem->id;
							$newLineItemMeta->meta_key='fabric_name';
							$newLineItemMeta->meta_value=$this->request->data['fabric_name'];
							$this->QuoteLineItemMeta->save($newLineItemMeta);
							
							$newLineItemMeta=$this->QuoteLineItemMeta->newEntity();
							$newLineItemMeta->quote_item_id=$newItem->id;
							$newLineItemMeta->meta_key='fabric_color';
							$newLineItemMeta->meta_value=$this->request->data['fabric_color'];

							$this->QuoteLineItemMeta->save($newLineItemMeta);
						}

						if($this->request->data['image_method'] == 'library'){
							$newLineItemMeta=$this->QuoteLineItemMeta->newEntity();
							$newLineItemMeta->quote_item_id=$newItem->id;
							$newLineItemMeta->meta_key='libraryimageid';
							$newLineItemMeta->meta_value=$this->request->data['libraryimageid'];
							$this->QuoteLineItemMeta->save($newLineItemMeta);
							
							$newLineItemMeta=$this->QuoteLineItemMeta->newEntity();
							$newLineItemMeta->quote_item_id=$newItem->id;
							$newLineItemMeta->meta_key='vendors_id';
							$newLineItemMeta->meta_value=$this->request->data['vendors_id'];
							$this->QuoteLineItemMeta->save($newLineItemMeta);
							
						}elseif($this->request->data['image_method'] == 'upload'){
							
							//handle the upload, and process whether to save this to library or just do a one-off insert
							$filename=$this->request->data['imagefileupload']['name'];
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
							
							
							move_uploaded_file($this->request->data['imagefileupload']['tmp_name'],$_SERVER['DOCUMENT_ROOT']."/webroot/img/library/".$filename);
							
							$imgLibraryTable=TableRegistry::get('LibraryImages');
							$newImage=$imgLibraryTable->newEntity();
							
							if($this->request->data['save_to_library'] == '1'){
								
								$selectedCat='';
								$allCats=$this->LibraryCategories->find('all')->toArray();
								foreach($allCats as $catEntry){
									if($catEntry['id'] == $this->request->data['image_category']){
										$selectedCat=$catEntry['category_title'];
									}
								}
								
								//insert into image library db with details for future usage
								$newImage->image_title=$this->request->data['image_title'];
								$newImage->categories=$selectedCat;
								$newImage->filename=$filename;
								$newImage->time=time();
								$newImage->added_by=$this->Auth->user('id');
								$newImage->tags=$this->request->data['image_tags'];
								$newImage->status='Active';
							}else{
								//insert into image library db with no details for single usage
								$newImage->image_title=$this->request->data['imagefileupload']['name'];
								$newImage->categories='Misc';
								$newImage->filename=$filename;
								$newImage->time=time();
								$newImage->added_by=$this->Auth->user('id');
								$newImage->status='Single Use';
							}
							
							if($imgLibraryTable->save($newImage)){
								//insert the metadata for libraryimageid
								$newLineItemMeta=$this->QuoteLineItemMeta->newEntity();
								$newLineItemMeta->quote_item_id=$newItem->id;
								$newLineItemMeta->meta_key='libraryimageid';
								$newLineItemMeta->meta_value=$newImage->id;
								$this->QuoteLineItemMeta->save($newLineItemMeta);

								$this->aspectratiofix($newImage->id,600);

							}
							
							
						}

						

						if($this->request->data['com-fabric']=='1'){

							$newLineItemMeta=$this->QuoteLineItemMeta->newEntity();
							$newLineItemMeta->quote_item_id=$newItem->id;

							$newLineItemMeta->meta_key='com-fabric';

							$newLineItemMeta->meta_value=1;

							$this->QuoteLineItemMeta->save($newLineItemMeta);

						}
                        
                        /*PPSA-33*/
                        if(isset($this->request->data['fabric-cost-per-yard-custom-value']) 
                        && !empty($this->request->data['fabric-cost-per-yard-custom-value'])){

								$newLineItemMeta=$this->QuoteLineItemMeta->newEntity();
								$newLineItemMeta->quote_item_id=$newItem->id;
								$newLineItemMeta->meta_key='fabric-cost-per-yard-custom-value';
								$newLineItemMeta->meta_value=number_format($this->request->data['fabric-cost-per-yard-custom-value'], 2, '.', '');
                                $this->QuoteLineItemMeta->save($newLineItemMeta);

						}
                        	/*PPSA-33*/
                        	/**PPSASSCRUM-29 start */	
							$orderDetails = $this->Orders->find('all',['conditions'=>['quote_id'=>$quoteID]])->first();

						if(isset($orderDetails) && !empty($orderDetails['id'])){
							$this->savetoOrderLineItemTables($newItem->id, $quoteID,$orderDetails['id'], $table='orders' );
							$this->savetoOrderLineItemTables($newItem->id, $quoteID,$orderDetails['id'], $table='work_orders' );	
						}
                      
						/**PPSASSCRUM-29 end */
                        
                        if($this->recalculatequoteadjustments($quoteID)){
							$this->Flash->success('Successfully added SWT Valance Catchall line item to quote');
							
							$this->logActivity($_SERVER['REQUEST_URI'],'Added SWT Valance Catchall to quote '.$thisQuote['quote_number'],$this->Auth->user('id'),json_encode($this->request));
						    return $this->redirect('/quotes/add/'.$quoteID);
						}
						
						
                        
                    }
                    
                    
                }else{
                    $linings=$this->Linings->find('all')->toArray();
					$this->set('linings',$linings);
					
					$vendorsList=array();
            		$vendors=$this->Vendors->find('all',['order'=>['vendor_name'=>'asc']])->toArray();
            		foreach($vendors as $vendor){
            			$vendorsList[$vendor['id']]=$vendor['vendor_name'];
            		}
            		$this->set('vendorsList',$vendorsList);
					
                    $this->render('/Quotes/newcatchall-valance');
                }
            break;
            
            case 'newcatchall-hardware':
                if($this->request->data){
                    
                    
                    //make sure the image is an image
                    if(isset($this->request->data['imagefileupload']['tmp_name']) && $this->request->data['image_method'] == 'upload'){
                        $allowedTypes=array('.jpg','jpeg','.png','.gif');
    					if(!in_array(strtolower(substr($this->request->data['imagefileupload']['name'],-4)),$allowedTypes)){
    					    $this->autoRender=false;
    					    echo '<h3><span style="color:red;">ERROR:</span> The filetype <u>'.substr($this->request->data['imagefileupload']['name'],-4).'</u> is not allowed in the image field.</h3>
                            <p><button type="button" onclick="history.go(-1);">Go Back</button></p>';
    					    exit;
    					}
                    }
                    
                    
                    //create the new entry
                    $newItem=$this->QuoteLineItems->newEntity();
                    $newItem->type='lineitem';
                    $newItem->quote_id=$quoteID;
                    $newItem->status='included';
                    $newItem->product_type='newcatchall-hardware';
                    $newItem->product_class=1;
                    $newItem->product_subclass=$this->request->data['product_subclass'];
                    $newItem->product_id=-1;
                    $newItem->title = $this->request->data['line_item_title'];
                    $newItem->description = $this->request->data['description'];
                    $newItem->best_price = $this->request->data['price'];
                    $newItem->qty = $this->request->data['qty'];
                    $newItem->lineitemtype='newcatchall';
                    $newItem->room_number = $this->request->data['location'];
                    $newItem->line_number=$this->getnextlinenumber($quoteID);
                    $newItem->internal_line=0;
                    
                    /**PPSA-40 start **/
                    $newItem->created=time();
                    /**PPSA-40 end **/
                    $thisSubClass=$this->ProductSubclasses->get($this->request->data['product_subclass'])->toArray();
                    
                    $newItem->enable_tally=$thisSubClass['tally'];
                    
                    
                    $newItem->sortorder = $this->getnextlinesortorder($quoteID);
                    $newItem->unit = $this->request->data['unit_type'];
                    $newItem->parent_line=0;
                    $newItem->calculator_used='';
                    $newItem->revised_from_line=0;
                    $newItem->project_management_surcharge_adjusted = NULL;
                    
                    $ignoreKeys=array('process','product_subclass','imagefileupload','description','line_item_title','customer_id','save_to_library','image_title','image_category','image_tags','fabric_name','fabric_color','libraryimageid');
                    if($this->QuoteLineItems->save($newItem)){
                        
                        //if this is EDIT ORDER function, add a line to Quote Item Add Log so it will know what to do when applying changes
                        if($thisQuote['status'] == 'editorder'){
                            $newAddLog=$this->QuoteItemAddLog->newEntity();
                            $newAddLog->original_quote_id = $thisQuote['parent_quote'];
                            $newAddLog->revision_id=$thisQuote['id'];
                            $newAddLog->line_item_id=$newItem->id;
                            $newAddLog->addtime=time();
                            $newAddLog->line_number=$newItem->line_number;
                            $this->QuoteItemAddLog->save($newAddLog);
                        }
                        
                        
                        //save all $_POST data to meta for this item
                        foreach($this->request->data as $postkey => $postdata){
                            if(!in_array($postkey,$ignoreKeys) && $postdata != ''){
                                
                                if($postkey=='fabric_type'){
                                    $postkey='fabrictype';
                                }
                                
                                $newLineItemMeta=$this->QuoteLineItemMeta->newEntity();
                                $newLineItemMeta->meta_key = $postkey;
                                $newLineItemMeta->meta_value = $postdata;
                                $newLineItemMeta->quote_item_id = $newItem->id;
                                $this->QuoteLineItemMeta->save($newLineItemMeta);
                            }
                        }
                        
                        
                        $newLineItemMeta=$this->QuoteLineItemMeta->newEntity();
                        $newLineItemMeta->quote_item_id = $newItem->id;
                        $newLineItemMeta->meta_key='lineitemtype';
                        $newLineItemMeta->meta_value='newcatchall';
                        $this->QuoteLineItemMeta->save($newLineItemMeta);
                        
                        
                        if($this->request->data['fabric_type']=="existing"){

							$newLineItemMeta=$this->QuoteLineItemMeta->newEntity();
							$newLineItemMeta->quote_item_id=$newItem->id;
							$newLineItemMeta->meta_key='fabricid';
							$newLineItemMeta->meta_value=$this->request->data['fabric_id_with_color'];
							$this->QuoteLineItemMeta->save($newLineItemMeta);
							
							$selectedFabric=$this->Fabrics->get($this->request->data['fabric_id_with_color'])->toArray();
							$newLineItemMeta=$this->QuoteLineItemMeta->newEntity();
							$newLineItemMeta->quote_item_id=$newItem->id;
							$newLineItemMeta->meta_key='fabric_name';
							$newLineItemMeta->meta_value=$selectedFabric['fabric_name'];
							$this->QuoteLineItemMeta->save($newLineItemMeta);

							$newLineItemMeta=$this->QuoteLineItemMeta->newEntity();
							$newLineItemMeta->quote_item_id=$newItem->id;
							$newLineItemMeta->meta_key='fabric_color';
							$newLineItemMeta->meta_value=$selectedFabric['color'];
							$this->QuoteLineItemMeta->save($newLineItemMeta);

						}elseif($this->request->data['fabric_type']=="typein"){

							$newLineItemMeta=$this->QuoteLineItemMeta->newEntity();
							$newLineItemMeta->quote_item_id=$newItem->id;
							$newLineItemMeta->meta_key='fabric_name';
							$newLineItemMeta->meta_value=$this->request->data['fabric_name'];
							$this->QuoteLineItemMeta->save($newLineItemMeta);
							
							$newLineItemMeta=$this->QuoteLineItemMeta->newEntity();
							$newLineItemMeta->quote_item_id=$newItem->id;
							$newLineItemMeta->meta_key='fabric_color';
							$newLineItemMeta->meta_value=$this->request->data['fabric_color'];

							$this->QuoteLineItemMeta->save($newLineItemMeta);
						}

						if($this->request->data['image_method'] == 'library'){
							$newLineItemMeta=$this->QuoteLineItemMeta->newEntity();
							$newLineItemMeta->quote_item_id=$newItem->id;
							$newLineItemMeta->meta_key='libraryimageid';
							$newLineItemMeta->meta_value=$this->request->data['libraryimageid'];
							$this->QuoteLineItemMeta->save($newLineItemMeta);
							
							$newLineItemMeta=$this->QuoteLineItemMeta->newEntity();
							$newLineItemMeta->quote_item_id=$newItem->id;
							$newLineItemMeta->meta_key='vendors_id';
							$newLineItemMeta->meta_value=$this->request->data['vendors_id'];
							$this->QuoteLineItemMeta->save($newLineItemMeta);
							
						}elseif($this->request->data['image_method'] == 'upload'){
							
							//handle the upload, and process whether to save this to library or just do a one-off insert
							$filename=$this->request->data['imagefileupload']['name'];
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
							
							
							move_uploaded_file($this->request->data['imagefileupload']['tmp_name'],$_SERVER['DOCUMENT_ROOT']."/webroot/img/library/".$filename);
							
							$imgLibraryTable=TableRegistry::get('LibraryImages');
							$newImage=$imgLibraryTable->newEntity();
							
							if($this->request->data['save_to_library'] == '1'){
								
								$selectedCat='';
								$allCats=$this->LibraryCategories->find('all')->toArray();
								foreach($allCats as $catEntry){
									if($catEntry['id'] == $this->request->data['image_category']){
										$selectedCat=$catEntry['category_title'];
									}
								}
								
								//insert into image library db with details for future usage
								$newImage->image_title=$this->request->data['image_title'];
								$newImage->categories=$selectedCat;
								$newImage->filename=$filename;
								$newImage->time=time();
								$newImage->added_by=$this->Auth->user('id');
								$newImage->tags=$this->request->data['image_tags'];
								$newImage->status='Active';
							}else{
								//insert into image library db with no details for single usage
								$newImage->image_title=$this->request->data['imagefileupload']['name'];
								$newImage->categories='Misc';
								$newImage->filename=$filename;
								$newImage->time=time();
								$newImage->added_by=$this->Auth->user('id');
								$newImage->status='Single Use';
							}
							
							if($imgLibraryTable->save($newImage)){
								//insert the metadata for libraryimageid
								$newLineItemMeta=$this->QuoteLineItemMeta->newEntity();
								$newLineItemMeta->quote_item_id=$newItem->id;
								$newLineItemMeta->meta_key='libraryimageid';
								$newLineItemMeta->meta_value=$newImage->id;
								$this->QuoteLineItemMeta->save($newLineItemMeta);

								$this->aspectratiofix($newImage->id,600);

							}
							
							
						}

						

						if($this->request->data['com-fabric']=='1'){

							$newLineItemMeta=$this->QuoteLineItemMeta->newEntity();
							$newLineItemMeta->quote_item_id=$newItem->id;

							$newLineItemMeta->meta_key='com-fabric';

							$newLineItemMeta->meta_value=1;

							$this->QuoteLineItemMeta->save($newLineItemMeta);

						}
                        
                        
                        	/*PPSA-33*/
                        if(isset($this->request->data['fabric-cost-per-yard-custom-value']) 
                       && !empty($this->request->data['fabric-cost-per-yard-custom-value'])){

								$newLineItemMeta=$this->QuoteLineItemMeta->newEntity();
								$newLineItemMeta->quote_item_id=$newItem->id;
								$newLineItemMeta->meta_key='fabric-cost-per-yard-custom-value';
								$newLineItemMeta->meta_value=number_format($this->request->data['fabric-cost-per-yard-custom-value'], 2, '.', '');
                                $this->QuoteLineItemMeta->save($newLineItemMeta);

						}
                        	/*PPSA-33*/

							/**PPSASSCRUM-29 start */	
							$orderDetails = $this->Orders->find('all',['conditions'=>['quote_id'=>$quoteID]])->first();

						if(isset($orderDetails) && !empty($orderDetails['id'])){
							$this->savetoOrderLineItemTables($newItem->id, $quoteID,$orderDetails['id'], $table='orders' );
							$this->savetoOrderLineItemTables($newItem->id, $quoteID,$orderDetails['id'], $table='work_orders' );	
						}
                      
						/**PPSASSCRUM-29 end */
                        if($this->recalculatequoteadjustments($quoteID)){
							$this->Flash->success('Successfully added Hardware Catchall line item to quote');
							
							$this->logActivity($_SERVER['REQUEST_URI'],'Added Hardware Catchall to quote '.$thisQuote['quote_number'],$this->Auth->user('id'),json_encode($this->request));
						    return $this->redirect('/quotes/add/'.$quoteID);
						}
						
						
                        
                    }
                    
                }else{
					$subclasses=$this->ProductSubclasses->find('all',['conditions' => ['class_id' => 1], 'order' => ['subclass_name' => 'asc']])->toArray();
                    $this->set('subclasses',$subclasses);
                    
                    $vendorsList=array();
            		$vendors=$this->Vendors->find('all',['order'=>['vendor_name'=>'asc']])->toArray();
            		foreach($vendors as $vendor){
            			$vendorsList[$vendor['id']]=$vendor['vendor_name'];
            		}
            		$this->set('vendorsList',$vendorsList);
                    
                    
                    $this->render('/Quotes/newcatchall-hardware');
                }
            break;
            
            case 'newcatchall-blinds':
                if($this->request->data){
                    
                    
                    //make sure the image is an image
                    if(isset($this->request->data['imagefileupload']['tmp_name']) && $this->request->data['image_method'] == 'upload'){
                        $allowedTypes=array('.jpg','jpeg','.png','.gif');
    					if(!in_array(strtolower(substr($this->request->data['imagefileupload']['name'],-4)),$allowedTypes)){
    					    $this->autoRender=false;
    					    echo '<h3><span style="color:red;">ERROR:</span> The filetype <u>'.substr($this->request->data['imagefileupload']['name'],-4).'</u> is not allowed in the image field.</h3>
                            <p><button type="button" onclick="history.go(-1);">Go Back</button></p>';
    					    exit;
    					}
                    }
                    
                    //create the new entry
                    $newItem=$this->QuoteLineItems->newEntity();
                    $newItem->type='lineitem';
                    $newItem->quote_id=$quoteID;
                    $newItem->status='included';
                    $newItem->product_type='newcatchall-blinds';
                    $newItem->product_class=2;
                    $newItem->product_subclass=5;
                    $newItem->product_id=-1;
                    $newItem->title = $this->request->data['line_item_title'];
                    $newItem->description = $this->request->data['description'];
                    $newItem->best_price = $this->request->data['price'];
                    $newItem->qty = $this->request->data['qty'];
                    $newItem->lineitemtype='newcatchall';
                    $newItem->room_number = $this->request->data['location'];
                    $newItem->line_number=$this->getnextlinenumber($quoteID);
                    $newItem->internal_line=0;
                    
                    /**PPSA-40 start **/
                    $newItem->created=time();
                    /**PPSA-40 end **/
                    $thisSubClass=$this->ProductSubclasses->get(5)->toArray();
                    
                    $newItem->enable_tally=$thisSubClass['tally'];
                    
                    
                    $newItem->sortorder = $this->getnextlinesortorder($quoteID);
                    $newItem->unit = $this->request->data['unit_type'];
                    $newItem->parent_line=0;
                    $newItem->calculator_used='';
                    $newItem->revised_from_line=0;
                    $newItem->project_management_surcharge_adjusted = NULL;
                    
                    $ignoreKeys=array('process','product_subclass','imagefileupload','description','line_item_title','customer_id','save_to_library','image_title','image_category','image_tags','fabric_name','fabric_color','libraryimageid');
                    if($this->QuoteLineItems->save($newItem)){
                        
                        
                        //if this is EDIT ORDER function, add a line to Quote Item Add Log so it will know what to do when applying changes
                        if($thisQuote['status'] == 'editorder'){
                            $newAddLog=$this->QuoteItemAddLog->newEntity();
                            $newAddLog->original_quote_id = $thisQuote['parent_quote'];
                            $newAddLog->revision_id=$thisQuote['id'];
                            $newAddLog->line_item_id=$newItem->id;
                            $newAddLog->addtime=time();
                            $newAddLog->line_number=$newItem->line_number;
                            $this->QuoteItemAddLog->save($newAddLog);
                        }
                        
                        //save all $_POST data to meta for this item
                        foreach($this->request->data as $postkey => $postdata){
                            if(!in_array($postkey,$ignoreKeys) && $postdata != ''){
                                
                                if($postkey=='fabric_type'){
                                    $postkey='fabrictype';
                                }
                                
                                $newLineItemMeta=$this->QuoteLineItemMeta->newEntity();
                                $newLineItemMeta->meta_key = $postkey;
                                $newLineItemMeta->meta_value = $postdata;
                                $newLineItemMeta->quote_item_id = $newItem->id;
                                $this->QuoteLineItemMeta->save($newLineItemMeta);
                            }
                        }
                        
                        
                        $newLineItemMeta=$this->QuoteLineItemMeta->newEntity();
                        $newLineItemMeta->quote_item_id = $newItem->id;
                        $newLineItemMeta->meta_key='lineitemtype';
                        $newLineItemMeta->meta_value='newcatchall';
                        $this->QuoteLineItemMeta->save($newLineItemMeta);
                        
                        
                        if($this->request->data['fabric_type']=="existing"){

							$newLineItemMeta=$this->QuoteLineItemMeta->newEntity();
							$newLineItemMeta->quote_item_id=$newItem->id;
							$newLineItemMeta->meta_key='fabricid';
							$newLineItemMeta->meta_value=$this->request->data['fabric_id_with_color'];
							$this->QuoteLineItemMeta->save($newLineItemMeta);
							
							$selectedFabric=$this->Fabrics->get($this->request->data['fabric_id_with_color'])->toArray();
							$newLineItemMeta=$this->QuoteLineItemMeta->newEntity();
							$newLineItemMeta->quote_item_id=$newItem->id;
							$newLineItemMeta->meta_key='fabric_name';
							$newLineItemMeta->meta_value=$selectedFabric['fabric_name'];
							$this->QuoteLineItemMeta->save($newLineItemMeta);

							$newLineItemMeta=$this->QuoteLineItemMeta->newEntity();
							$newLineItemMeta->quote_item_id=$newItem->id;
							$newLineItemMeta->meta_key='fabric_color';
							$newLineItemMeta->meta_value=$selectedFabric['color'];
							$this->QuoteLineItemMeta->save($newLineItemMeta);

						}elseif($this->request->data['fabric_type']=="typein"){

							$newLineItemMeta=$this->QuoteLineItemMeta->newEntity();
							$newLineItemMeta->quote_item_id=$newItem->id;
							$newLineItemMeta->meta_key='fabric_name';
							$newLineItemMeta->meta_value=$this->request->data['fabric_name'];
							$this->QuoteLineItemMeta->save($newLineItemMeta);
							
							$newLineItemMeta=$this->QuoteLineItemMeta->newEntity();
							$newLineItemMeta->quote_item_id=$newItem->id;
							$newLineItemMeta->meta_key='fabric_color';
							$newLineItemMeta->meta_value=$this->request->data['fabric_color'];

							$this->QuoteLineItemMeta->save($newLineItemMeta);
						}

						if($this->request->data['image_method'] == 'library'){
							$newLineItemMeta=$this->QuoteLineItemMeta->newEntity();
							$newLineItemMeta->quote_item_id=$newItem->id;
							$newLineItemMeta->meta_key='libraryimageid';
							$newLineItemMeta->meta_value=$this->request->data['libraryimageid'];
							$this->QuoteLineItemMeta->save($newLineItemMeta);
							
							$newLineItemMeta=$this->QuoteLineItemMeta->newEntity();
							$newLineItemMeta->quote_item_id=$newItem->id;
							$newLineItemMeta->meta_key='vendors_id';
							$newLineItemMeta->meta_value=$this->request->data['vendors_id'];
							$this->QuoteLineItemMeta->save($newLineItemMeta);
							
						}elseif($this->request->data['image_method'] == 'upload'){
							
							//handle the upload, and process whether to save this to library or just do a one-off insert
							$filename=$this->request->data['imagefileupload']['name'];
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
							
							
							move_uploaded_file($this->request->data['imagefileupload']['tmp_name'],$_SERVER['DOCUMENT_ROOT']."/webroot/img/library/".$filename);
							
							$imgLibraryTable=TableRegistry::get('LibraryImages');
							$newImage=$imgLibraryTable->newEntity();
							
							if($this->request->data['save_to_library'] == '1'){
								
								$selectedCat='';
								$allCats=$this->LibraryCategories->find('all')->toArray();
								foreach($allCats as $catEntry){
									if($catEntry['id'] == $this->request->data['image_category']){
										$selectedCat=$catEntry['category_title'];
									}
								}
								
								//insert into image library db with details for future usage
								$newImage->image_title=$this->request->data['image_title'];
								$newImage->categories=$selectedCat;
								$newImage->filename=$filename;
								$newImage->time=time();
								$newImage->added_by=$this->Auth->user('id');
								$newImage->tags=$this->request->data['image_tags'];
								$newImage->status='Active';
							}else{
								//insert into image library db with no details for single usage
								$newImage->image_title=$this->request->data['imagefileupload']['name'];
								$newImage->categories='Misc';
								$newImage->filename=$filename;
								$newImage->time=time();
								$newImage->added_by=$this->Auth->user('id');
								$newImage->status='Single Use';
							}
							
							if($imgLibraryTable->save($newImage)){
								//insert the metadata for libraryimageid
								$newLineItemMeta=$this->QuoteLineItemMeta->newEntity();
								$newLineItemMeta->quote_item_id=$newItem->id;
								$newLineItemMeta->meta_key='libraryimageid';
								$newLineItemMeta->meta_value=$newImage->id;
								$this->QuoteLineItemMeta->save($newLineItemMeta);

								$this->aspectratiofix($newImage->id,600);

							}
							
							
						}

						

						if($this->request->data['com-fabric']=='1'){

							$newLineItemMeta=$this->QuoteLineItemMeta->newEntity();
							$newLineItemMeta->quote_item_id=$newItem->id;

							$newLineItemMeta->meta_key='com-fabric';

							$newLineItemMeta->meta_value=1;

							$this->QuoteLineItemMeta->save($newLineItemMeta);

						}
                        
                        /**PPSASSCRUM-29 start */	
						$orderDetails = $this->Orders->find('all',['conditions'=>['quote_id'=>$quoteID]])->first();

						if(isset($orderDetails) && !empty($orderDetails['id'])){
							$this->savetoOrderLineItemTables($newItem->id, $quoteID,$orderDetails['id'], $table='orders' );
							$this->savetoOrderLineItemTables($newItem->id, $quoteID,$orderDetails['id'], $table='work_orders' );	
						}
                      
						/**PPSASSCRUM-29 end */
                        if($this->recalculatequoteadjustments($quoteID)){
							$this->Flash->success('Successfully added HWT Blinds Catchall line item to quote');
							
							$this->logActivity($_SERVER['REQUEST_URI'],'Added HWT Blinds Catchall to quote '.$thisQuote['quote_number'],$this->Auth->user('id'),json_encode($this->request));
						    return $this->redirect('/quotes/add/'.$quoteID);
						}
						
						
                        
                    }
                    
                }else{
					
                    $vendorsList=array();
            		$vendors=$this->Vendors->find('all',['order'=>['vendor_name'=>'asc']])->toArray();
            		foreach($vendors as $vendor){
            			$vendorsList[$vendor['id']]=$vendor['vendor_name'];
            		}
            		$this->set('vendorsList',$vendorsList);
            		
                    $this->render('/Quotes/newcatchall-blinds');
                }
            break;
            
            case 'newcatchall-shades':
                if($this->request->data){
                    
                    
                    //make sure the image is an image
                    if(isset($this->request->data['imagefileupload']['tmp_name']) && $this->request->data['image_method'] == 'upload'){
                        $allowedTypes=array('.jpg','jpeg','.png','.gif');
    					if(!in_array(strtolower(substr($this->request->data['imagefileupload']['name'],-4)),$allowedTypes)){
    					    $this->autoRender=false;
    					    echo '<h3><span style="color:red;">ERROR:</span> The filetype <u>'.substr($this->request->data['imagefileupload']['name'],-4).'</u> is not allowed in the image field.</h3>
                            <p><button type="button" onclick="history.go(-1);">Go Back</button></p>';
    					    exit;
    					}
                    }
                    
                    
                    //create the new entry
                    $newItem=$this->QuoteLineItems->newEntity();
                    $newItem->type='lineitem';
                    $newItem->quote_id=$quoteID;
                    $newItem->status='included';
                    $newItem->product_type='newcatchall-shades';
                    $newItem->product_class=2;
                    $newItem->product_subclass=6;
                    $newItem->product_id=-1;
                    $newItem->title = $this->request->data['line_item_title'];
                    $newItem->description = $this->request->data['description'];
                    $newItem->best_price = $this->request->data['price'];
                    $newItem->qty = $this->request->data['qty'];
                    $newItem->lineitemtype='newcatchall';
                    $newItem->room_number = $this->request->data['location'];
                    $newItem->line_number=$this->getnextlinenumber($quoteID);
                    $newItem->internal_line=0;
                    
                    /**PPSA-40 start **/
                    $newItem->created=time();
                    /**PPSA-40 end **/
                    $thisSubClass=$this->ProductSubclasses->get(6)->toArray();
                    
                    $newItem->enable_tally=$thisSubClass['tally'];
                    
                    
                    $newItem->sortorder = $this->getnextlinesortorder($quoteID);
                    $newItem->unit = $this->request->data['unit_type'];
                    $newItem->parent_line=0;
                    $newItem->calculator_used='';
                    $newItem->revised_from_line=0;
                    $newItem->project_management_surcharge_adjusted = NULL;
                    
                    $ignoreKeys=array('process','product_subclass','imagefileupload','description','line_item_title','customer_id','save_to_library','image_title','image_category','image_tags','fabric_name','fabric_color','libraryimageid');
                    if($this->QuoteLineItems->save($newItem)){
                        
                        
                        //if this is EDIT ORDER function, add a line to Quote Item Add Log so it will know what to do when applying changes
                        if($thisQuote['status'] == 'editorder'){
                            $newAddLog=$this->QuoteItemAddLog->newEntity();
                            $newAddLog->original_quote_id = $thisQuote['parent_quote'];
                            $newAddLog->revision_id=$thisQuote['id'];
                            $newAddLog->line_item_id=$newItem->id;
                            $newAddLog->addtime=time();
                            $newAddLog->line_number=$newItem->line_number;
                            $this->QuoteItemAddLog->save($newAddLog);
                        }
                        
                        //save all $_POST data to meta for this item
                        foreach($this->request->data as $postkey => $postdata){
                            if(!in_array($postkey,$ignoreKeys) && $postdata != ''){
                                
                                if($postkey=='fabric_type'){
                                    $postkey='fabrictype';
                                }
                                
                                $newLineItemMeta=$this->QuoteLineItemMeta->newEntity();
                                $newLineItemMeta->meta_key = $postkey;
                                $newLineItemMeta->meta_value = $postdata;
                                $newLineItemMeta->quote_item_id = $newItem->id;
                                $this->QuoteLineItemMeta->save($newLineItemMeta);
                            }
                        }
                        
                        
                        $newLineItemMeta=$this->QuoteLineItemMeta->newEntity();
                        $newLineItemMeta->quote_item_id = $newItem->id;
                        $newLineItemMeta->meta_key='lineitemtype';
                        $newLineItemMeta->meta_value='newcatchall';
                        $this->QuoteLineItemMeta->save($newLineItemMeta);
                        
                        
                        if($this->request->data['fabric_type']=="existing"){

							$newLineItemMeta=$this->QuoteLineItemMeta->newEntity();
							$newLineItemMeta->quote_item_id=$newItem->id;
							$newLineItemMeta->meta_key='fabricid';
							$newLineItemMeta->meta_value=$this->request->data['fabric_id_with_color'];
							$this->QuoteLineItemMeta->save($newLineItemMeta);
							
							$selectedFabric=$this->Fabrics->get($this->request->data['fabric_id_with_color'])->toArray();
							$newLineItemMeta=$this->QuoteLineItemMeta->newEntity();
							$newLineItemMeta->quote_item_id=$newItem->id;
							$newLineItemMeta->meta_key='fabric_name';
							$newLineItemMeta->meta_value=$selectedFabric['fabric_name'];
							$this->QuoteLineItemMeta->save($newLineItemMeta);

							$newLineItemMeta=$this->QuoteLineItemMeta->newEntity();
							$newLineItemMeta->quote_item_id=$newItem->id;
							$newLineItemMeta->meta_key='fabric_color';
							$newLineItemMeta->meta_value=$selectedFabric['color'];
							$this->QuoteLineItemMeta->save($newLineItemMeta);

						}elseif($this->request->data['fabric_type']=="typein"){

							$newLineItemMeta=$this->QuoteLineItemMeta->newEntity();
							$newLineItemMeta->quote_item_id=$newItem->id;
							$newLineItemMeta->meta_key='fabric_name';
							$newLineItemMeta->meta_value=$this->request->data['fabric_name'];
							$this->QuoteLineItemMeta->save($newLineItemMeta);
							
							$newLineItemMeta=$this->QuoteLineItemMeta->newEntity();
							$newLineItemMeta->quote_item_id=$newItem->id;
							$newLineItemMeta->meta_key='fabric_color';
							$newLineItemMeta->meta_value=$this->request->data['fabric_color'];

							$this->QuoteLineItemMeta->save($newLineItemMeta);
						}

						if($this->request->data['image_method'] == 'library'){
							$newLineItemMeta=$this->QuoteLineItemMeta->newEntity();
							$newLineItemMeta->quote_item_id=$newItem->id;
							$newLineItemMeta->meta_key='libraryimageid';
							$newLineItemMeta->meta_value=$this->request->data['libraryimageid'];
							$this->QuoteLineItemMeta->save($newLineItemMeta);
							
							$newLineItemMeta=$this->QuoteLineItemMeta->newEntity();
							$newLineItemMeta->quote_item_id=$newItem->id;
							$newLineItemMeta->meta_key='vendors_id';
							$newLineItemMeta->meta_value=$this->request->data['vendors_id'];
							$this->QuoteLineItemMeta->save($newLineItemMeta);
							
						}elseif($this->request->data['image_method'] == 'upload'){
							
							//handle the upload, and process whether to save this to library or just do a one-off insert
							$filename=$this->request->data['imagefileupload']['name'];
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
							
							
							move_uploaded_file($this->request->data['imagefileupload']['tmp_name'],$_SERVER['DOCUMENT_ROOT']."/webroot/img/library/".$filename);
							
							$imgLibraryTable=TableRegistry::get('LibraryImages');
							$newImage=$imgLibraryTable->newEntity();
							
							if($this->request->data['save_to_library'] == '1'){
								
								$selectedCat='';
								$allCats=$this->LibraryCategories->find('all')->toArray();
								foreach($allCats as $catEntry){
									if($catEntry['id'] == $this->request->data['image_category']){
										$selectedCat=$catEntry['category_title'];
									}
								}
								
								//insert into image library db with details for future usage
								$newImage->image_title=$this->request->data['image_title'];
								$newImage->categories=$selectedCat;
								$newImage->filename=$filename;
								$newImage->time=time();
								$newImage->added_by=$this->Auth->user('id');
								$newImage->tags=$this->request->data['image_tags'];
								$newImage->status='Active';
							}else{
								//insert into image library db with no details for single usage
								$newImage->image_title=$this->request->data['imagefileupload']['name'];
								$newImage->categories='Misc';
								$newImage->filename=$filename;
								$newImage->time=time();
								$newImage->added_by=$this->Auth->user('id');
								$newImage->status='Single Use';
							}
							
							if($imgLibraryTable->save($newImage)){
								//insert the metadata for libraryimageid
								$newLineItemMeta=$this->QuoteLineItemMeta->newEntity();
								$newLineItemMeta->quote_item_id=$newItem->id;
								$newLineItemMeta->meta_key='libraryimageid';
								$newLineItemMeta->meta_value=$newImage->id;
								$this->QuoteLineItemMeta->save($newLineItemMeta);

								$this->aspectratiofix($newImage->id,600);

							}
							
							
						}

						

						if($this->request->data['com-fabric']=='1'){

							$newLineItemMeta=$this->QuoteLineItemMeta->newEntity();
							$newLineItemMeta->quote_item_id=$newItem->id;

							$newLineItemMeta->meta_key='com-fabric';

							$newLineItemMeta->meta_value=1;

							$this->QuoteLineItemMeta->save($newLineItemMeta);

						}
                        /**PPSASSCRUM-29 start */	
						if(isset($orderDetails) && !empty($orderDetails['id'])){
							$this->savetoOrderLineItemTables($newItem->id, $quoteID,$orderDetails['id'], $table='orders' );
							$this->savetoOrderLineItemTables($newItem->id, $quoteID,$orderDetails['id'], $table='work_orders' );	
						}
                      
						/**PPSASSCRUM-29 end */
                        
                        if($this->recalculatequoteadjustments($quoteID)){
							$this->Flash->success('Successfully added HWT Shades Catchall line item to quote');
							
							$this->logActivity($_SERVER['REQUEST_URI'],'Added HWT Shades Catchall to quote '.$thisQuote['quote_number'],$this->Auth->user('id'),json_encode($this->request));
						    return $this->redirect('/quotes/add/'.$quoteID);
						}
						
						
                        
                    }
                    
                    
                }else{
					
                    $vendorsList=array();
            		$vendors=$this->Vendors->find('all',['order'=>['vendor_name'=>'asc']])->toArray();
            		foreach($vendors as $vendor){
            			$vendorsList[$vendor['id']]=$vendor['vendor_name'];
            		}
            		$this->set('vendorsList',$vendorsList);
            		
                    $this->render('/Quotes/newcatchall-shades');
                }
            break;
            
            case 'newcatchall-shutters':
                if($this->request->data){
                    
                    
                    //make sure the image is an image
                    if(isset($this->request->data['imagefileupload']['tmp_name']) && $this->request->data['image_method'] == 'upload'){
                        $allowedTypes=array('.jpg','jpeg','.png','.gif');
    					if(!in_array(strtolower(substr($this->request->data['imagefileupload']['name'],-4)),$allowedTypes)){
    					    $this->autoRender=false;
    					    echo '<h3><span style="color:red;">ERROR:</span> The filetype <u>'.substr($this->request->data['imagefileupload']['name'],-4).'</u> is not allowed in the image field.</h3>
                            <p><button type="button" onclick="history.go(-1);">Go Back</button></p>';
    					    exit;
    					}
                    }
                    
                    
                    //create the new entry
                    $newItem=$this->QuoteLineItems->newEntity();
                    $newItem->type='lineitem';
                    $newItem->quote_id=$quoteID;
                    $newItem->status='included';
                    $newItem->product_type='newcatchall-shutters';
                    $newItem->product_class=2;
                    $newItem->product_subclass=7;
                    $newItem->product_id=-1;
                    $newItem->title = $this->request->data['line_item_title'];
                    $newItem->description = $this->request->data['description'];
                    $newItem->best_price = $this->request->data['price'];
                    $newItem->qty = $this->request->data['qty'];
                    $newItem->lineitemtype='newcatchall';
                    $newItem->room_number = $this->request->data['location'];
                    $newItem->line_number=$this->getnextlinenumber($quoteID);
                    $newItem->internal_line=0;
                    /**PPSA-40 start **/
                    $newItem->created=time();
                    /**PPSA-40 end **/
                    
                    $thisSubClass=$this->ProductSubclasses->get(7)->toArray();
                    
                    $newItem->enable_tally=$thisSubClass['tally'];
                    
                    
                    $newItem->sortorder = $this->getnextlinesortorder($quoteID);
                    $newItem->unit = $this->request->data['unit_type'];
                    $newItem->parent_line=0;
                    $newItem->calculator_used='';
                    $newItem->revised_from_line=0;
                    $newItem->project_management_surcharge_adjusted = NULL;
                    
                    $ignoreKeys=array('process','product_subclass','imagefileupload','description','line_item_title','customer_id','save_to_library','image_title','image_category','image_tags','fabric_name','fabric_color','libraryimageid');
                    if($this->QuoteLineItems->save($newItem)){
                        
                        
                        //if this is EDIT ORDER function, add a line to Quote Item Add Log so it will know what to do when applying changes
                        if($thisQuote['status'] == 'editorder'){
                            $newAddLog=$this->QuoteItemAddLog->newEntity();
                            $newAddLog->original_quote_id = $thisQuote['parent_quote'];
                            $newAddLog->revision_id=$thisQuote['id'];
                            $newAddLog->line_item_id=$newItem->id;
                            $newAddLog->addtime=time();
                            $newAddLog->line_number=$newItem->line_number;
                            $this->QuoteItemAddLog->save($newAddLog);
                        }
                        
                        
                        //save all $_POST data to meta for this item
                        foreach($this->request->data as $postkey => $postdata){
                            if(!in_array($postkey,$ignoreKeys) && $postdata != ''){
                                
                                if($postkey=='fabric_type'){
                                    $postkey='fabrictype';
                                }
                                
                                $newLineItemMeta=$this->QuoteLineItemMeta->newEntity();
                                $newLineItemMeta->meta_key = $postkey;
                                $newLineItemMeta->meta_value = $postdata;
                                $newLineItemMeta->quote_item_id = $newItem->id;
                                $this->QuoteLineItemMeta->save($newLineItemMeta);
                            }
                        }
                        
                        
                        $newLineItemMeta=$this->QuoteLineItemMeta->newEntity();
                        $newLineItemMeta->quote_item_id = $newItem->id;
                        $newLineItemMeta->meta_key='lineitemtype';
                        $newLineItemMeta->meta_value='newcatchall';
                        $this->QuoteLineItemMeta->save($newLineItemMeta);
                        
                        
                        if($this->request->data['fabric_type']=="existing"){

							$newLineItemMeta=$this->QuoteLineItemMeta->newEntity();
							$newLineItemMeta->quote_item_id=$newItem->id;
							$newLineItemMeta->meta_key='fabricid';
							$newLineItemMeta->meta_value=$this->request->data['fabric_id_with_color'];
							$this->QuoteLineItemMeta->save($newLineItemMeta);
							
							$selectedFabric=$this->Fabrics->get($this->request->data['fabric_id_with_color'])->toArray();
							$newLineItemMeta=$this->QuoteLineItemMeta->newEntity();
							$newLineItemMeta->quote_item_id=$newItem->id;
							$newLineItemMeta->meta_key='fabric_name';
							$newLineItemMeta->meta_value=$selectedFabric['fabric_name'];
							$this->QuoteLineItemMeta->save($newLineItemMeta);

							$newLineItemMeta=$this->QuoteLineItemMeta->newEntity();
							$newLineItemMeta->quote_item_id=$newItem->id;
							$newLineItemMeta->meta_key='fabric_color';
							$newLineItemMeta->meta_value=$selectedFabric['color'];
							$this->QuoteLineItemMeta->save($newLineItemMeta);

						}elseif($this->request->data['fabric_type']=="typein"){

							$newLineItemMeta=$this->QuoteLineItemMeta->newEntity();
							$newLineItemMeta->quote_item_id=$newItem->id;
							$newLineItemMeta->meta_key='fabric_name';
							$newLineItemMeta->meta_value=$this->request->data['fabric_name'];
							$this->QuoteLineItemMeta->save($newLineItemMeta);
							
							$newLineItemMeta=$this->QuoteLineItemMeta->newEntity();
							$newLineItemMeta->quote_item_id=$newItem->id;
							$newLineItemMeta->meta_key='fabric_color';
							$newLineItemMeta->meta_value=$this->request->data['fabric_color'];

							$this->QuoteLineItemMeta->save($newLineItemMeta);
						}

						if($this->request->data['image_method'] == 'library'){
							$newLineItemMeta=$this->QuoteLineItemMeta->newEntity();
							$newLineItemMeta->quote_item_id=$newItem->id;
							$newLineItemMeta->meta_key='libraryimageid';
							$newLineItemMeta->meta_value=$this->request->data['libraryimageid'];
							$this->QuoteLineItemMeta->save($newLineItemMeta);
							
							$newLineItemMeta=$this->QuoteLineItemMeta->newEntity();
							$newLineItemMeta->quote_item_id=$newItem->id;
							$newLineItemMeta->meta_key='vendors_id';
							$newLineItemMeta->meta_value=$this->request->data['vendors_id'];
							$this->QuoteLineItemMeta->save($newLineItemMeta);
							
						}elseif($this->request->data['image_method'] == 'upload'){
							
							//handle the upload, and process whether to save this to library or just do a one-off insert
							$filename=$this->request->data['imagefileupload']['name'];
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
							
							
							move_uploaded_file($this->request->data['imagefileupload']['tmp_name'],$_SERVER['DOCUMENT_ROOT']."/webroot/img/library/".$filename);
							
							$imgLibraryTable=TableRegistry::get('LibraryImages');
							$newImage=$imgLibraryTable->newEntity();
							
							if($this->request->data['save_to_library'] == '1'){
								
								$selectedCat='';
								$allCats=$this->LibraryCategories->find('all')->toArray();
								foreach($allCats as $catEntry){
									if($catEntry['id'] == $this->request->data['image_category']){
										$selectedCat=$catEntry['category_title'];
									}
								}
								
								//insert into image library db with details for future usage
								$newImage->image_title=$this->request->data['image_title'];
								$newImage->categories=$selectedCat;
								$newImage->filename=$filename;
								$newImage->time=time();
								$newImage->added_by=$this->Auth->user('id');
								$newImage->tags=$this->request->data['image_tags'];
								$newImage->status='Active';
							}else{
								//insert into image library db with no details for single usage
								$newImage->image_title=$this->request->data['imagefileupload']['name'];
								$newImage->categories='Misc';
								$newImage->filename=$filename;
								$newImage->time=time();
								$newImage->added_by=$this->Auth->user('id');
								$newImage->status='Single Use';
							}
							
							if($imgLibraryTable->save($newImage)){
								//insert the metadata for libraryimageid
								$newLineItemMeta=$this->QuoteLineItemMeta->newEntity();
								$newLineItemMeta->quote_item_id=$newItem->id;
								$newLineItemMeta->meta_key='libraryimageid';
								$newLineItemMeta->meta_value=$newImage->id;
								$this->QuoteLineItemMeta->save($newLineItemMeta);

								$this->aspectratiofix($newImage->id,600);

							}
							
							
						}

						

						if($this->request->data['com-fabric']=='1'){

							$newLineItemMeta=$this->QuoteLineItemMeta->newEntity();
							$newLineItemMeta->quote_item_id=$newItem->id;

							$newLineItemMeta->meta_key='com-fabric';

							$newLineItemMeta->meta_value=1;

							$this->QuoteLineItemMeta->save($newLineItemMeta);

						}
                        
                        /**PPSASSCRUM-29 start */	
						if(isset($orderDetails) && !empty($orderDetails['id'])){
							$this->savetoOrderLineItemTables($newItem->id, $quoteID,$orderDetails['id'], $table='orders' );
							$this->savetoOrderLineItemTables($newItem->id, $quoteID,$orderDetails['id'], $table='work_orders' );	
						}
                      
						/**PPSASSCRUM-29 end */
                        if($this->recalculatequoteadjustments($quoteID)){
							$this->Flash->success('Successfully added HWT Shutters Catchall line item to quote');
							
							$this->logActivity($_SERVER['REQUEST_URI'],'Added HWT Shutters Catchall to quote '.$thisQuote['quote_number'],$this->Auth->user('id'),json_encode($this->request));
						    return $this->redirect('/quotes/add/'.$quoteID);
						}
						
						
                        
                    }
                    
                    
                }else{
					
                    $vendorsList=array();
            		$vendors=$this->Vendors->find('all',['order'=>['vendor_name'=>'asc']])->toArray();
            		foreach($vendors as $vendor){
            			$vendorsList[$vendor['id']]=$vendor['vendor_name'];
            		}
            		$this->set('vendorsList',$vendorsList);
            		
                    $this->render('/Quotes/newcatchall-shutters');
                }
            break;
            
            case 'newcatchall-hwtmisc':
                if($this->request->data){
                    
                    
                    //make sure the image is an image
                    if(isset($this->request->data['imagefileupload']['tmp_name']) && $this->request->data['image_method'] == 'upload'){
                        $allowedTypes=array('.jpg','jpeg','.png','.gif');
    					if(!in_array(strtolower(substr($this->request->data['imagefileupload']['name'],-4)),$allowedTypes)){
    					    $this->autoRender=false;
    					    echo '<h3><span style="color:red;">ERROR:</span> The filetype <u>'.substr($this->request->data['imagefileupload']['name'],-4).'</u> is not allowed in the image field.</h3>
                            <p><button type="button" onclick="history.go(-1);">Go Back</button></p>';
    					    exit;
    					}
                    }
                    
                    
                    //create the new entry
                    $newItem=$this->QuoteLineItems->newEntity();
                    $newItem->type='lineitem';
                    $newItem->quote_id=$quoteID;
                    $newItem->status='included';
                    $newItem->product_type='newcatchall-hwtmisc';
                    $newItem->product_class=2;
                    $newItem->product_subclass=8;
                    $newItem->product_id=-1;
                    $newItem->title = $this->request->data['line_item_title'];
                    $newItem->description = $this->request->data['description'];
                    $newItem->best_price = $this->request->data['price'];
                    $newItem->qty = $this->request->data['qty'];
                    $newItem->lineitemtype='newcatchall';
                    $newItem->room_number = $this->request->data['location'];
                    $newItem->line_number=$this->getnextlinenumber($quoteID);
                    $newItem->internal_line=0;
                    /**PPSA-40 start **/
                    $newItem->created=time();
                    /**PPSA-40 end **/
                    
                    $thisSubClass=$this->ProductSubclasses->get(8)->toArray();
                    
                    $newItem->enable_tally=$thisSubClass['tally'];
                    
                    $newItem->sortorder = $this->getnextlinesortorder($quoteID);
                    $newItem->unit = $this->request->data['unit_type'];
                    $newItem->parent_line=0;
                    $newItem->calculator_used='';
                    $newItem->revised_from_line=0;
                    $newItem->project_management_surcharge_adjusted = NULL;
                    
                    $ignoreKeys=array('process','product_subclass','imagefileupload','description','line_item_title','customer_id','save_to_library','image_title','image_category','image_tags','fabric_name','fabric_color','libraryimageid');
                    if($this->QuoteLineItems->save($newItem)){
                        
                        
                        //if this is EDIT ORDER function, add a line to Quote Item Add Log so it will know what to do when applying changes
                        if($thisQuote['status'] == 'editorder'){
                            $newAddLog=$this->QuoteItemAddLog->newEntity();
                            $newAddLog->original_quote_id = $thisQuote['parent_quote'];
                            $newAddLog->revision_id=$thisQuote['id'];
                            $newAddLog->line_item_id=$newItem->id;
                            $newAddLog->addtime=time();
                            $newAddLog->line_number=$newItem->line_number;
                            $this->QuoteItemAddLog->save($newAddLog);
                        }
                        
                        
                        //save all $_POST data to meta for this item
                        foreach($this->request->data as $postkey => $postdata){
                            if(!in_array($postkey,$ignoreKeys) && $postdata != ''){
                                
                                if($postkey=='fabric_type'){
                                    $postkey='fabrictype';
                                }
                                
                                $newLineItemMeta=$this->QuoteLineItemMeta->newEntity();
                                $newLineItemMeta->meta_key = $postkey;
                                $newLineItemMeta->meta_value = $postdata;
                                $newLineItemMeta->quote_item_id = $newItem->id;
                                $this->QuoteLineItemMeta->save($newLineItemMeta);
                            }
                        }
                        
                        
                        $newLineItemMeta=$this->QuoteLineItemMeta->newEntity();
                        $newLineItemMeta->quote_item_id = $newItem->id;
                        $newLineItemMeta->meta_key='lineitemtype';
                        $newLineItemMeta->meta_value='newcatchall';
                        $this->QuoteLineItemMeta->save($newLineItemMeta);
                        
                        
                        if($this->request->data['fabric_type']=="existing"){

							$newLineItemMeta=$this->QuoteLineItemMeta->newEntity();
							$newLineItemMeta->quote_item_id=$newItem->id;
							$newLineItemMeta->meta_key='fabricid';
							$newLineItemMeta->meta_value=$this->request->data['fabric_id_with_color'];
							$this->QuoteLineItemMeta->save($newLineItemMeta);
							
							$selectedFabric=$this->Fabrics->get($this->request->data['fabric_id_with_color'])->toArray();
							$newLineItemMeta=$this->QuoteLineItemMeta->newEntity();
							$newLineItemMeta->quote_item_id=$newItem->id;
							$newLineItemMeta->meta_key='fabric_name';
							$newLineItemMeta->meta_value=$selectedFabric['fabric_name'];
							$this->QuoteLineItemMeta->save($newLineItemMeta);

							$newLineItemMeta=$this->QuoteLineItemMeta->newEntity();
							$newLineItemMeta->quote_item_id=$newItem->id;
							$newLineItemMeta->meta_key='fabric_color';
							$newLineItemMeta->meta_value=$selectedFabric['color'];
							$this->QuoteLineItemMeta->save($newLineItemMeta);

						}elseif($this->request->data['fabric_type']=="typein"){

							$newLineItemMeta=$this->QuoteLineItemMeta->newEntity();
							$newLineItemMeta->quote_item_id=$newItem->id;
							$newLineItemMeta->meta_key='fabric_name';
							$newLineItemMeta->meta_value=$this->request->data['fabric_name'];
							$this->QuoteLineItemMeta->save($newLineItemMeta);
							
							$newLineItemMeta=$this->QuoteLineItemMeta->newEntity();
							$newLineItemMeta->quote_item_id=$newItem->id;
							$newLineItemMeta->meta_key='fabric_color';
							$newLineItemMeta->meta_value=$this->request->data['fabric_color'];

							$this->QuoteLineItemMeta->save($newLineItemMeta);
						}

						if($this->request->data['image_method'] == 'library'){
							$newLineItemMeta=$this->QuoteLineItemMeta->newEntity();
							$newLineItemMeta->quote_item_id=$newItem->id;
							$newLineItemMeta->meta_key='libraryimageid';
							$newLineItemMeta->meta_value=$this->request->data['libraryimageid'];
							$this->QuoteLineItemMeta->save($newLineItemMeta);
							
							$newLineItemMeta=$this->QuoteLineItemMeta->newEntity();
							$newLineItemMeta->quote_item_id=$newItem->id;
							$newLineItemMeta->meta_key='vendors_id';
							$newLineItemMeta->meta_value=$this->request->data['vendors_id'];
							$this->QuoteLineItemMeta->save($newLineItemMeta);
							
						}elseif($this->request->data['image_method'] == 'upload'){
							
							//handle the upload, and process whether to save this to library or just do a one-off insert
							$filename=$this->request->data['imagefileupload']['name'];
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
							
							
							move_uploaded_file($this->request->data['imagefileupload']['tmp_name'],$_SERVER['DOCUMENT_ROOT']."/webroot/img/library/".$filename);
							
							$imgLibraryTable=TableRegistry::get('LibraryImages');
							$newImage=$imgLibraryTable->newEntity();
							
							if($this->request->data['save_to_library'] == '1'){
								
								$selectedCat='';
								$allCats=$this->LibraryCategories->find('all')->toArray();
								foreach($allCats as $catEntry){
									if($catEntry['id'] == $this->request->data['image_category']){
										$selectedCat=$catEntry['category_title'];
									}
								}
								
								//insert into image library db with details for future usage
								$newImage->image_title=$this->request->data['image_title'];
								$newImage->categories=$selectedCat;
								$newImage->filename=$filename;
								$newImage->time=time();
								$newImage->added_by=$this->Auth->user('id');
								$newImage->tags=$this->request->data['image_tags'];
								$newImage->status='Active';
							}else{
								//insert into image library db with no details for single usage
								$newImage->image_title=$this->request->data['imagefileupload']['name'];
								$newImage->categories='Misc';
								$newImage->filename=$filename;
								$newImage->time=time();
								$newImage->added_by=$this->Auth->user('id');
								$newImage->status='Single Use';
							}
							
							if($imgLibraryTable->save($newImage)){
								//insert the metadata for libraryimageid
								$newLineItemMeta=$this->QuoteLineItemMeta->newEntity();
								$newLineItemMeta->quote_item_id=$newItem->id;
								$newLineItemMeta->meta_key='libraryimageid';
								$newLineItemMeta->meta_value=$newImage->id;
								$this->QuoteLineItemMeta->save($newLineItemMeta);

								$this->aspectratiofix($newImage->id,600);

							}
							
							
						}

						

						if($this->request->data['com-fabric']=='1'){

							$newLineItemMeta=$this->QuoteLineItemMeta->newEntity();
							$newLineItemMeta->quote_item_id=$newItem->id;

							$newLineItemMeta->meta_key='com-fabric';

							$newLineItemMeta->meta_value=1;

							$this->QuoteLineItemMeta->save($newLineItemMeta);

						}
                        
                        	/*PPSA-33*/
                        if(isset($this->request->data['fabric-cost-per-yard-custom-value']) 
                       && !empty($this->request->data['fabric-cost-per-yard-custom-value'])){

								$newLineItemMeta=$this->QuoteLineItemMeta->newEntity();
								$newLineItemMeta->quote_item_id=$newItem->id;
								$newLineItemMeta->meta_key='fabric-cost-per-yard-custom-value';
								$newLineItemMeta->meta_value=number_format($this->request->data['fabric-cost-per-yard-custom-value'], 2, '.', '');
                                $this->QuoteLineItemMeta->save($newLineItemMeta);

						}
                        	/*PPSA-33*/
                        	/**PPSASSCRUM-29 start */	
						if(isset($orderDetails) && !empty($orderDetails['id'])){
							$this->savetoOrderLineItemTables($newItem->id, $quoteID,$orderDetails['id'], $table='orders' );
							$this->savetoOrderLineItemTables($newItem->id, $quoteID,$orderDetails['id'], $table='work_orders' );	
						}
                      
						/**PPSASSCRUM-29 end */
                        if($this->recalculatequoteadjustments($quoteID)){
							$this->Flash->success('Successfully added HWT Misc Catchall line item to quote');
							
							$this->logActivity($_SERVER['REQUEST_URI'],'Added HWT Misc Catchall to quote '.$thisQuote['quote_number'],$this->Auth->user('id'),json_encode($this->request));
						    return $this->redirect('/quotes/add/'.$quoteID);
						}
						
						
                        
                    }
                    
                    
                }else{
					
                    $vendorsList=array();
            		$vendors=$this->Vendors->find('all',['order'=>['vendor_name'=>'asc']])->toArray();
            		foreach($vendors as $vendor){
            			$vendorsList[$vendor['id']]=$vendor['vendor_name'];
            		}
            		$this->set('vendorsList',$vendorsList);
            		
                    $this->render('/Quotes/newcatchall-hwtmisc');
                }
            break;
            
            
            case 'newcatchall-service':
                if($this->request->data){
                    
                    
                    //make sure the image is an image
                    if(isset($this->request->data['imagefileupload']['tmp_name']) && $this->request->data['image_method'] == 'upload'){
                        $allowedTypes=array('.jpg','jpeg','.png','.gif');
    					if(!in_array(strtolower(substr($this->request->data['imagefileupload']['name'],-4)),$allowedTypes)){
    					    $this->autoRender=false;
    					    echo '<h3><span style="color:red;">ERROR:</span> The filetype <u>'.substr($this->request->data['imagefileupload']['name'],-4).'</u> is not allowed in the image field.</h3>
                            <p><button type="button" onclick="history.go(-1);">Go Back</button></p>';
    					    exit;
    					}
                    }
                    
                    
                    //create the new entry
                    $newItem=$this->QuoteLineItems->newEntity();
                    $newItem->type='lineitem';
                    $newItem->quote_id=$quoteID;
                    $newItem->status='included';
                    $newItem->product_type='newcatchall-service';
                    $newItem->product_class=6;
                    $newItem->product_subclass=$this->request->data['product_subclass'];
                    $newItem->product_id=-1;
                    $newItem->title = $this->request->data['line_item_title'];
                    $newItem->description = $this->request->data['description'];
                    $newItem->best_price = $this->request->data['price'];
                    $newItem->qty = $this->request->data['qty'];
                    $newItem->lineitemtype='newcatchall';
                    $newItem->room_number = $this->request->data['location'];
                    $newItem->line_number=$this->getnextlinenumber($quoteID);
                    $newItem->internal_line=0;
                    /**PPSA-40 start **/
                    $newItem->created=time();
                    /**PPSA-40 end **/
                    
                    $thisSubClass=$this->ProductSubclasses->get($this->request->data['product_subclass'])->toArray();
                    
                    $newItem->enable_tally=$thisSubClass['tally'];
                    
                    $newItem->sortorder = $this->getnextlinesortorder($quoteID);
                    $newItem->unit = $this->request->data['unit_type'];
                    $newItem->parent_line=0;
                    $newItem->calculator_used='';
                    $newItem->revised_from_line=0;
                    $newItem->project_management_surcharge_adjusted = NULL;
                    
                    $ignoreKeys=array('process','product_subclass','imagefileupload','description','line_item_title','customer_id','save_to_library','image_title','image_category','image_tags','fabric_name','fabric_color','libraryimageid');
                    if($this->QuoteLineItems->save($newItem)){
                        
                        
                        //if this is EDIT ORDER function, add a line to Quote Item Add Log so it will know what to do when applying changes
                        if($thisQuote['status'] == 'editorder'){
                            $newAddLog=$this->QuoteItemAddLog->newEntity();
                            $newAddLog->original_quote_id = $thisQuote['parent_quote'];
                            $newAddLog->revision_id=$thisQuote['id'];
                            $newAddLog->line_item_id=$newItem->id;
                            $newAddLog->addtime=time();
                            $newAddLog->line_number=$newItem->line_number;
                            $this->QuoteItemAddLog->save($newAddLog);
                        }
                        
                        
                        //save all $_POST data to meta for this item
                        foreach($this->request->data as $postkey => $postdata){
                            if(!in_array($postkey,$ignoreKeys) && $postdata != ''){
                                
                                if($postkey=='fabric_type'){
                                    $postkey='fabrictype';
                                }
                                
                                $newLineItemMeta=$this->QuoteLineItemMeta->newEntity();
                                $newLineItemMeta->meta_key = $postkey;
                                $newLineItemMeta->meta_value = $postdata;
                                $newLineItemMeta->quote_item_id = $newItem->id;
                                $this->QuoteLineItemMeta->save($newLineItemMeta);
                            }
                        }
                        
                        
                        $newLineItemMeta=$this->QuoteLineItemMeta->newEntity();
                        $newLineItemMeta->quote_item_id = $newItem->id;
                        $newLineItemMeta->meta_key='lineitemtype';
                        $newLineItemMeta->meta_value='newcatchall';
                        $this->QuoteLineItemMeta->save($newLineItemMeta);
                        
                        
                        if($this->request->data['fabric_type']=="existing"){

							$newLineItemMeta=$this->QuoteLineItemMeta->newEntity();
							$newLineItemMeta->quote_item_id=$newItem->id;
							$newLineItemMeta->meta_key='fabricid';
							$newLineItemMeta->meta_value=$this->request->data['fabric_id_with_color'];
							$this->QuoteLineItemMeta->save($newLineItemMeta);
							
							$selectedFabric=$this->Fabrics->get($this->request->data['fabric_id_with_color'])->toArray();
							$newLineItemMeta=$this->QuoteLineItemMeta->newEntity();
							$newLineItemMeta->quote_item_id=$newItem->id;
							$newLineItemMeta->meta_key='fabric_name';
							$newLineItemMeta->meta_value=$selectedFabric['fabric_name'];
							$this->QuoteLineItemMeta->save($newLineItemMeta);

							$newLineItemMeta=$this->QuoteLineItemMeta->newEntity();
							$newLineItemMeta->quote_item_id=$newItem->id;
							$newLineItemMeta->meta_key='fabric_color';
							$newLineItemMeta->meta_value=$selectedFabric['color'];
							$this->QuoteLineItemMeta->save($newLineItemMeta);

						}elseif($this->request->data['fabric_type']=="typein"){

							$newLineItemMeta=$this->QuoteLineItemMeta->newEntity();
							$newLineItemMeta->quote_item_id=$newItem->id;
							$newLineItemMeta->meta_key='fabric_name';
							$newLineItemMeta->meta_value=$this->request->data['fabric_name'];
							$this->QuoteLineItemMeta->save($newLineItemMeta);
							
							$newLineItemMeta=$this->QuoteLineItemMeta->newEntity();
							$newLineItemMeta->quote_item_id=$newItem->id;
							$newLineItemMeta->meta_key='fabric_color';
							$newLineItemMeta->meta_value=$this->request->data['fabric_color'];

							$this->QuoteLineItemMeta->save($newLineItemMeta);
						}

						if($this->request->data['image_method'] == 'library'){
							$newLineItemMeta=$this->QuoteLineItemMeta->newEntity();
							$newLineItemMeta->quote_item_id=$newItem->id;
							$newLineItemMeta->meta_key='libraryimageid';
							$newLineItemMeta->meta_value=$this->request->data['libraryimageid'];
							$this->QuoteLineItemMeta->save($newLineItemMeta);
							
							$newLineItemMeta=$this->QuoteLineItemMeta->newEntity();
							$newLineItemMeta->quote_item_id=$newItem->id;
							$newLineItemMeta->meta_key='vendors_id';
							$newLineItemMeta->meta_value=$this->request->data['vendors_id'];
							$this->QuoteLineItemMeta->save($newLineItemMeta);
							
						}elseif($this->request->data['image_method'] == 'upload'){
							
							//handle the upload, and process whether to save this to library or just do a one-off insert
							$filename=$this->request->data['imagefileupload']['name'];
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
							
							
							move_uploaded_file($this->request->data['imagefileupload']['tmp_name'],$_SERVER['DOCUMENT_ROOT']."/webroot/img/library/".$filename);
							
							$imgLibraryTable=TableRegistry::get('LibraryImages');
							$newImage=$imgLibraryTable->newEntity();
							
							if($this->request->data['save_to_library'] == '1'){
								
								$selectedCat='';
								$allCats=$this->LibraryCategories->find('all')->toArray();
								foreach($allCats as $catEntry){
									if($catEntry['id'] == $this->request->data['image_category']){
										$selectedCat=$catEntry['category_title'];
									}
								}
								
								//insert into image library db with details for future usage
								$newImage->image_title=$this->request->data['image_title'];
								$newImage->categories=$selectedCat;
								$newImage->filename=$filename;
								$newImage->time=time();
								$newImage->added_by=$this->Auth->user('id');
								$newImage->tags=$this->request->data['image_tags'];
								$newImage->status='Active';
							}else{
								//insert into image library db with no details for single usage
								$newImage->image_title=$this->request->data['imagefileupload']['name'];
								$newImage->categories='Misc';
								$newImage->filename=$filename;
								$newImage->time=time();
								$newImage->added_by=$this->Auth->user('id');
								$newImage->status='Single Use';
							}
							
							if($imgLibraryTable->save($newImage)){
								//insert the metadata for libraryimageid
								$newLineItemMeta=$this->QuoteLineItemMeta->newEntity();
								$newLineItemMeta->quote_item_id=$newItem->id;
								$newLineItemMeta->meta_key='libraryimageid';
								$newLineItemMeta->meta_value=$newImage->id;
								$this->QuoteLineItemMeta->save($newLineItemMeta);

								$this->aspectratiofix($newImage->id,600);

							}
							
							
						}

						

						if($this->request->data['com-fabric']=='1'){

							$newLineItemMeta=$this->QuoteLineItemMeta->newEntity();
							$newLineItemMeta->quote_item_id=$newItem->id;

							$newLineItemMeta->meta_key='com-fabric';

							$newLineItemMeta->meta_value=1;

							$this->QuoteLineItemMeta->save($newLineItemMeta);

						}
                        /**PPSASSCRUM-29 start */	
						if(isset($orderDetails) && !empty($orderDetails['id'])){
							$this->savetoOrderLineItemTables($newItem->id, $quoteID,$orderDetails['id'], $table='orders' );
							$this->savetoOrderLineItemTables($newItem->id, $quoteID,$orderDetails['id'], $table='work_orders' );	
						}
                      
						/**PPSASSCRUM-29 end */
                        
                        if($this->recalculatequoteadjustments($quoteID)){
							$this->Flash->success('Successfully added Service Catchall line item to quote');
							
							$this->logActivity($_SERVER['REQUEST_URI'],'Added Service Catchall to quote '.$thisQuote['quote_number'],$this->Auth->user('id'),json_encode($this->request));
						    return $this->redirect('/quotes/add/'.$quoteID);
						}
						
						
                        
                    }
                    
                    
                }else{
					$subclasses=$this->ProductSubclasses->find('all',['conditions' => ['class_id' => 6], 'order' => ['subclass_name' => 'asc']])->toArray();
                    $this->set('subclasses',$subclasses);
                    
                    $vendorsList=array();
            		$vendors=$this->Vendors->find('all',['order'=>['vendor_name'=>'asc']])->toArray();
            		foreach($vendors as $vendor){
            			$vendorsList[$vendor['id']]=$vendor['vendor_name'];
            		}
            		$this->set('vendorsList',$vendorsList);
                    
                    $this->render('/Quotes/newcatchall-service');
                }
            break;
            
            case 'newcatchall-misc':
                if($this->request->data){
                    
                    
                    //make sure the image is an image
                    if(isset($this->request->data['imagefileupload']['tmp_name']) && $this->request->data['image_method'] == 'upload'){
                        $allowedTypes=array('.jpg','jpeg','.png','.gif');
    					if(!in_array(strtolower(substr($this->request->data['imagefileupload']['name'],-4)),$allowedTypes)){
    					    $this->autoRender=false;
    					    echo '<h3><span style="color:red;">ERROR:</span> The filetype <u>'.substr($this->request->data['imagefileupload']['name'],-4).'</u> is not allowed in the image field.</h3>
                            <p><button type="button" onclick="history.go(-1);">Go Back</button></p>';
    					    exit;
    					}
                    }
                    
                    
                    //create the new entry
                    $newItem=$this->QuoteLineItems->newEntity();
                    $newItem->type='lineitem';
                    $newItem->quote_id=$quoteID;
                    $newItem->status='included';
                    $newItem->product_type='newcatchall-misc';
                    $newItem->product_class=7;
                    $newItem->product_subclass=$this->request->data['product_subclass'];
                    $newItem->product_id=-1;
                    $newItem->title = $this->request->data['line_item_title'];
                    $newItem->description = $this->request->data['description'];
                    $newItem->best_price = $this->request->data['price'];
                    $newItem->qty = $this->request->data['qty'];
                    $newItem->lineitemtype='newcatchall';
                    $newItem->room_number = $this->request->data['location'];
                    
                    $newItem->internal_line=0;
                    /**PPSA-40 start **/
                    $newItem->created=time();
                    /**PPSA-40 end **/
                    
                    $thisSubClass=$this->ProductSubclasses->get($this->request->data['product_subclass'])->toArray();
                    
                    $newItem->enable_tally=$thisSubClass['tally'];
                    
                    if(!$subaction){
                        $newItem->line_number=$this->getnextlinenumber($quoteID);
                        $newItem->sortorder = $this->getnextlinesortorder($quoteID);
                        $newItem->parent_line=0;
                    }else{
                        //child line!
                        
                        $newItem->line_number=$subaction;

						//determine the current parent line's actual primary key

						$currentParentLookup=$this->QuoteLineItems->find('all',['conditions'=>['line_number' => $subaction,'quote_id' => $quoteID, 'parent_line' => 0],'order'=>['line_number'=>'asc']])->limit(1)->toArray();

						$newsortorder=0;
						$parentid=0;
						foreach($currentParentLookup as $parentline){
							$newItem->parent_line=$parentline['id'];
							$parentid=$parentline['id'];
							$newsortorder=$parentline['sort_order'];
							$parentsortordernumber=$parentline['sortorder'];
						}

						//find out how many child lines this parent line already has
						$parentChildrenCount=$this->QuoteLineItems->find('all',['conditions' => ['parent_line' => $parentid]])->count();
						
						//determine the new sort order
						$newsortorder=$parentsortordernumber.'.'.($parentChildrenCount+1);
						
						$newItem->sortorder = floatval($newsortorder);
                        
                    }
                    
                    $newItem->unit = $this->request->data['unit_type'];
                    $newItem->calculator_used='';
                    $newItem->revised_from_line=0;
                    $newItem->project_management_surcharge_adjusted = NULL;
                    
                    $ignoreKeys=array('process','product_subclass','imagefileupload','description','line_item_title','customer_id','save_to_library','image_title','image_category','image_tags','fabric_name','fabric_color','libraryimageid');
                    if($this->QuoteLineItems->save($newItem)){
                        
                        
                        //if this is EDIT ORDER function, add a line to Quote Item Add Log so it will know what to do when applying changes
                        if($thisQuote['status'] == 'editorder'){
                            $newAddLog=$this->QuoteItemAddLog->newEntity();
                            $newAddLog->original_quote_id = $thisQuote['parent_quote'];
                            $newAddLog->revision_id=$thisQuote['id'];
                            $newAddLog->line_item_id=$newItem->id;
                            $newAddLog->addtime=time();
                            $newAddLog->line_number=$newItem->line_number;
                            $this->QuoteItemAddLog->save($newAddLog);
                        }
                        
                        
                        //save all $_POST data to meta for this item
                        foreach($this->request->data as $postkey => $postdata){
                            if(!in_array($postkey,$ignoreKeys) && $postdata != ''){
                                
                                if($postkey=='fabric_type'){
                                    $postkey='fabrictype';
                                }
                                
                                $newLineItemMeta=$this->QuoteLineItemMeta->newEntity();
                                $newLineItemMeta->meta_key = $postkey;
                                $newLineItemMeta->meta_value = $postdata;
                                $newLineItemMeta->quote_item_id = $newItem->id;
                                $this->QuoteLineItemMeta->save($newLineItemMeta);
                            }
                        }
                        
                        
                        $newLineItemMeta=$this->QuoteLineItemMeta->newEntity();
                        $newLineItemMeta->quote_item_id = $newItem->id;
                        $newLineItemMeta->meta_key='lineitemtype';
                        $newLineItemMeta->meta_value='newcatchall';
                        $this->QuoteLineItemMeta->save($newLineItemMeta);
                        
                        
                        if($this->request->data['fabric_type']=="existing"){

							$newLineItemMeta=$this->QuoteLineItemMeta->newEntity();
							$newLineItemMeta->quote_item_id=$newItem->id;
							$newLineItemMeta->meta_key='fabricid';
							$newLineItemMeta->meta_value=$this->request->data['fabric_id_with_color'];
							$this->QuoteLineItemMeta->save($newLineItemMeta);
							
							$selectedFabric=$this->Fabrics->get($this->request->data['fabric_id_with_color'])->toArray();
							$newLineItemMeta=$this->QuoteLineItemMeta->newEntity();
							$newLineItemMeta->quote_item_id=$newItem->id;
							$newLineItemMeta->meta_key='fabric_name';
							$newLineItemMeta->meta_value=$selectedFabric['fabric_name'];
							$this->QuoteLineItemMeta->save($newLineItemMeta);

							$newLineItemMeta=$this->QuoteLineItemMeta->newEntity();
							$newLineItemMeta->quote_item_id=$newItem->id;
							$newLineItemMeta->meta_key='fabric_color';
							$newLineItemMeta->meta_value=$selectedFabric['color'];
							$this->QuoteLineItemMeta->save($newLineItemMeta);

						}elseif($this->request->data['fabric_type']=="typein"){

							$newLineItemMeta=$this->QuoteLineItemMeta->newEntity();
							$newLineItemMeta->quote_item_id=$newItem->id;
							$newLineItemMeta->meta_key='fabric_name';
							$newLineItemMeta->meta_value=$this->request->data['fabric_name'];
							$this->QuoteLineItemMeta->save($newLineItemMeta);
							
							$newLineItemMeta=$this->QuoteLineItemMeta->newEntity();
							$newLineItemMeta->quote_item_id=$newItem->id;
							$newLineItemMeta->meta_key='fabric_color';
							$newLineItemMeta->meta_value=$this->request->data['fabric_color'];

							$this->QuoteLineItemMeta->save($newLineItemMeta);
						}

						if($this->request->data['image_method'] == 'library'){
							$newLineItemMeta=$this->QuoteLineItemMeta->newEntity();
							$newLineItemMeta->quote_item_id=$newItem->id;
							$newLineItemMeta->meta_key='libraryimageid';
							$newLineItemMeta->meta_value=$this->request->data['libraryimageid'];
							$this->QuoteLineItemMeta->save($newLineItemMeta);
							
							$newLineItemMeta=$this->QuoteLineItemMeta->newEntity();
							$newLineItemMeta->quote_item_id=$newItem->id;
							$newLineItemMeta->meta_key='vendors_id';
							$newLineItemMeta->meta_value=$this->request->data['vendors_id'];
							$this->QuoteLineItemMeta->save($newLineItemMeta);
							
						}elseif($this->request->data['image_method'] == 'upload'){
							
							//handle the upload, and process whether to save this to library or just do a one-off insert
							$filename=$this->request->data['imagefileupload']['name'];
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
							
							
							move_uploaded_file($this->request->data['imagefileupload']['tmp_name'],$_SERVER['DOCUMENT_ROOT']."/webroot/img/library/".$filename);
							
							$imgLibraryTable=TableRegistry::get('LibraryImages');
							$newImage=$imgLibraryTable->newEntity();
							
							if($this->request->data['save_to_library'] == '1'){
								
								$selectedCat='';
								$allCats=$this->LibraryCategories->find('all')->toArray();
								foreach($allCats as $catEntry){
									if($catEntry['id'] == $this->request->data['image_category']){
										$selectedCat=$catEntry['category_title'];
									}
								}
								
								//insert into image library db with details for future usage
								$newImage->image_title=$this->request->data['image_title'];
								$newImage->categories=$selectedCat;
								$newImage->filename=$filename;
								$newImage->time=time();
								$newImage->added_by=$this->Auth->user('id');
								$newImage->tags=$this->request->data['image_tags'];
								$newImage->status='Active';
							}else{
								//insert into image library db with no details for single usage
								$newImage->image_title=$this->request->data['imagefileupload']['name'];
								$newImage->categories='Misc';
								$newImage->filename=$filename;
								$newImage->time=time();
								$newImage->added_by=$this->Auth->user('id');
								$newImage->status='Single Use';
							}
							
							if($imgLibraryTable->save($newImage)){
								//insert the metadata for libraryimageid
								$newLineItemMeta=$this->QuoteLineItemMeta->newEntity();
								$newLineItemMeta->quote_item_id=$newItem->id;
								$newLineItemMeta->meta_key='libraryimageid';
								$newLineItemMeta->meta_value=$newImage->id;
								$this->QuoteLineItemMeta->save($newLineItemMeta);

								$this->aspectratiofix($newImage->id,600);

							}
							
							
						}

						

						if($this->request->data['com-fabric']=='1'){

							$newLineItemMeta=$this->QuoteLineItemMeta->newEntity();
							$newLineItemMeta->quote_item_id=$newItem->id;

							$newLineItemMeta->meta_key='com-fabric';

							$newLineItemMeta->meta_value=1;

							$this->QuoteLineItemMeta->save($newLineItemMeta);

						}
                        
                        
                        	/*PPSA-33*/
                        if(isset($this->request->data['fabric-cost-per-yard-custom-value']) 
                       && !empty($this->request->data['fabric-cost-per-yard-custom-value'])){

								$newLineItemMeta=$this->QuoteLineItemMeta->newEntity();
								$newLineItemMeta->quote_item_id=$newItem->id;
								$newLineItemMeta->meta_key='fabric-cost-per-yard-custom-value';
								$newLineItemMeta->meta_value=number_format($this->request->data['fabric-cost-per-yard-custom-value'], 2, '.', '');
                                $this->QuoteLineItemMeta->save($newLineItemMeta);

						}
                        	/*PPSA-33*/
                        	/**PPSASSCRUM-29 start */	
						if(isset($orderDetails) && !empty($orderDetails['id'])){
							$this->savetoOrderLineItemTables($newItem->id, $quoteID,$orderDetails['id'], $table='orders' );
							$this->savetoOrderLineItemTables($newItem->id, $quoteID,$orderDetails['id'], $table='work_orders' );	
						}
                      
						/**PPSASSCRUM-29 end */
                        if($this->recalculatequoteadjustments($quoteID)){
							$this->Flash->success('Successfully added Miscellaneous Catchall line item to quote');
							
							$this->logActivity($_SERVER['REQUEST_URI'],'Added Miscellaneous Catchall to quote '.$thisQuote['quote_number'],$this->Auth->user('id'),json_encode($this->request));
						    return $this->redirect('/quotes/add/'.$quoteID);
						}
						
						
                        
                    }
                    
                    
                }else{
					
					$subclasses=$this->ProductSubclasses->find('all',['conditions' => ['class_id' => 7], 'order' => ['subclass_name' => 'asc']])->toArray();
                    $this->set('subclasses',$subclasses);
                    
                    $vendorsList=array();
            		$vendors=$this->Vendors->find('all',['order'=>['vendor_name'=>'asc']])->toArray();
            		foreach($vendors as $vendor){
            			$vendorsList[$vendor['id']]=$vendor['vendor_name'];
            		}
            		$this->set('vendorsList',$vendorsList);
            		
                    $this->render('/Quotes/newcatchall-misc');
                }
            break;
            
            case 'newcatchall-swtmisc':
                if($this->request->data){
                    
                    
                    //make sure the image is an image
                    if(isset($this->request->data['imagefileupload']['tmp_name']) && $this->request->data['image_method'] == 'upload'){
                        $allowedTypes=array('.jpg','jpeg','.png','.gif');
    					if(!in_array(strtolower(substr($this->request->data['imagefileupload']['name'],-4)),$allowedTypes)){
    					    $this->autoRender=false;
    					    echo '<h3><span style="color:red;">ERROR:</span> The filetype <u>'.substr($this->request->data['imagefileupload']['name'],-4).'</u> is not allowed in the image field.</h3>
                            <p><button type="button" onclick="history.go(-1);">Go Back</button></p>';
    					    exit;
    					}
                    }
                    
                    
                    //create the new entry
                    $newItem=$this->QuoteLineItems->newEntity();
                    $newItem->type='lineitem';
                    $newItem->quote_id=$quoteID;
                    $newItem->status='included';
                    $newItem->product_type='newcatchall-swtmisc';
                    $newItem->product_class=3;
                    $newItem->product_subclass=12;
                    $newItem->product_id=-1;
                    $newItem->title = $this->request->data['line_item_title'];
                    $newItem->description = $this->request->data['description'];
                    $newItem->best_price = $this->request->data['price'];
                    $newItem->qty = $this->request->data['qty'];
                    $newItem->lineitemtype='newcatchall';
                    $newItem->room_number = $this->request->data['location'];
                    $newItem->line_number=$this->getnextlinenumber($quoteID);
                    $newItem->internal_line=0;
                    /**PPSA-40 start **/
                    $newItem->created=time();
                    /**PPSA-40 end **/
                    
                    $thisSubClass=$this->ProductSubclasses->get(12)->toArray();
                    
                    $newItem->enable_tally=$thisSubClass['tally'];
                    
                    
                    $newItem->sortorder = $this->getnextlinesortorder($quoteID);
                    $newItem->unit = $this->request->data['unit_type'];
                    $newItem->parent_line=0;
                    $newItem->calculator_used='';
                    $newItem->revised_from_line=0;
                    $newItem->project_management_surcharge_adjusted = NULL;
                    
                    $ignoreKeys=array('process','product_subclass','imagefileupload','description','line_item_title','customer_id','save_to_library','image_title','image_category','image_tags','fabric_name','fabric_color','libraryimageid');
                    if($this->QuoteLineItems->save($newItem)){
                        
                        
                        //if this is EDIT ORDER function, add a line to Quote Item Add Log so it will know what to do when applying changes
                        if($thisQuote['status'] == 'editorder'){
                            $newAddLog=$this->QuoteItemAddLog->newEntity();
                            $newAddLog->original_quote_id = $thisQuote['parent_quote'];
                            $newAddLog->revision_id=$thisQuote['id'];
                            $newAddLog->line_item_id=$newItem->id;
                            $newAddLog->addtime=time();
                            $newAddLog->line_number=$newItem->line_number;
                            $this->QuoteItemAddLog->save($newAddLog);
                        }
                        
                        
                        //save all $_POST data to meta for this item
                        foreach($this->request->data as $postkey => $postdata){
                            if(!in_array($postkey,$ignoreKeys) && $postdata != ''){
                                
                                if($postkey=='fabric_type'){
                                    $postkey='fabrictype';
                                }
                                
                                $newLineItemMeta=$this->QuoteLineItemMeta->newEntity();
                                $newLineItemMeta->meta_key = $postkey;
                                $newLineItemMeta->meta_value = $postdata;
                                $newLineItemMeta->quote_item_id = $newItem->id;
                                $this->QuoteLineItemMeta->save($newLineItemMeta);
                            }
                        }
                        
                        
                        $newLineItemMeta=$this->QuoteLineItemMeta->newEntity();
                        $newLineItemMeta->quote_item_id = $newItem->id;
                        $newLineItemMeta->meta_key='lineitemtype';
                        $newLineItemMeta->meta_value='newcatchall';
                        $this->QuoteLineItemMeta->save($newLineItemMeta);
                        
                        
                        if($this->request->data['fabric_type']=="existing"){

							$newLineItemMeta=$this->QuoteLineItemMeta->newEntity();
							$newLineItemMeta->quote_item_id=$newItem->id;
							$newLineItemMeta->meta_key='fabricid';
							$newLineItemMeta->meta_value=$this->request->data['fabric_id_with_color'];
							$this->QuoteLineItemMeta->save($newLineItemMeta);
							
							$selectedFabric=$this->Fabrics->get($this->request->data['fabric_id_with_color'])->toArray();
							$newLineItemMeta=$this->QuoteLineItemMeta->newEntity();
							$newLineItemMeta->quote_item_id=$newItem->id;
							$newLineItemMeta->meta_key='fabric_name';
							$newLineItemMeta->meta_value=$selectedFabric['fabric_name'];
							$this->QuoteLineItemMeta->save($newLineItemMeta);

							$newLineItemMeta=$this->QuoteLineItemMeta->newEntity();
							$newLineItemMeta->quote_item_id=$newItem->id;
							$newLineItemMeta->meta_key='fabric_color';
							$newLineItemMeta->meta_value=$selectedFabric['color'];
							$this->QuoteLineItemMeta->save($newLineItemMeta);

						}elseif($this->request->data['fabric_type']=="typein"){

							$newLineItemMeta=$this->QuoteLineItemMeta->newEntity();
							$newLineItemMeta->quote_item_id=$newItem->id;
							$newLineItemMeta->meta_key='fabric_name';
							$newLineItemMeta->meta_value=$this->request->data['fabric_name'];
							$this->QuoteLineItemMeta->save($newLineItemMeta);
							
							$newLineItemMeta=$this->QuoteLineItemMeta->newEntity();
							$newLineItemMeta->quote_item_id=$newItem->id;
							$newLineItemMeta->meta_key='fabric_color';
							$newLineItemMeta->meta_value=$this->request->data['fabric_color'];

							$this->QuoteLineItemMeta->save($newLineItemMeta);
						}

						if($this->request->data['image_method'] == 'library'){
							$newLineItemMeta=$this->QuoteLineItemMeta->newEntity();
							$newLineItemMeta->quote_item_id=$newItem->id;
							$newLineItemMeta->meta_key='libraryimageid';
							$newLineItemMeta->meta_value=$this->request->data['libraryimageid'];
							$this->QuoteLineItemMeta->save($newLineItemMeta);
							
							$newLineItemMeta=$this->QuoteLineItemMeta->newEntity();
							$newLineItemMeta->quote_item_id=$newItem->id;
							$newLineItemMeta->meta_key='vendors_id';
							$newLineItemMeta->meta_value=$this->request->data['vendors_id'];
							$this->QuoteLineItemMeta->save($newLineItemMeta);
							
						}elseif($this->request->data['image_method'] == 'upload'){
							
							//handle the upload, and process whether to save this to library or just do a one-off insert
							$filename=$this->request->data['imagefileupload']['name'];
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
							
							
							move_uploaded_file($this->request->data['imagefileupload']['tmp_name'],$_SERVER['DOCUMENT_ROOT']."/webroot/img/library/".$filename);
							
							$imgLibraryTable=TableRegistry::get('LibraryImages');
							$newImage=$imgLibraryTable->newEntity();
							
							if($this->request->data['save_to_library'] == '1'){
								
								$selectedCat='';
								$allCats=$this->LibraryCategories->find('all')->toArray();
								foreach($allCats as $catEntry){
									if($catEntry['id'] == $this->request->data['image_category']){
										$selectedCat=$catEntry['category_title'];
									}
								}
								
								//insert into image library db with details for future usage
								$newImage->image_title=$this->request->data['image_title'];
								$newImage->categories=$selectedCat;
								$newImage->filename=$filename;
								$newImage->time=time();
								$newImage->added_by=$this->Auth->user('id');
								$newImage->tags=$this->request->data['image_tags'];
								$newImage->status='Active';
							}else{
								//insert into image library db with no details for single usage
								$newImage->image_title=$this->request->data['imagefileupload']['name'];
								$newImage->categories='Misc';
								$newImage->filename=$filename;
								$newImage->time=time();
								$newImage->added_by=$this->Auth->user('id');
								$newImage->status='Single Use';
							}
							
							if($imgLibraryTable->save($newImage)){
								//insert the metadata for libraryimageid
								$newLineItemMeta=$this->QuoteLineItemMeta->newEntity();
								$newLineItemMeta->quote_item_id=$newItem->id;
								$newLineItemMeta->meta_key='libraryimageid';
								$newLineItemMeta->meta_value=$newImage->id;
								$this->QuoteLineItemMeta->save($newLineItemMeta);

								$this->aspectratiofix($newImage->id,600);

							}
							
							
						}

						

						if($this->request->data['com-fabric']=='1'){

							$newLineItemMeta=$this->QuoteLineItemMeta->newEntity();
							$newLineItemMeta->quote_item_id=$newItem->id;

							$newLineItemMeta->meta_key='com-fabric';

							$newLineItemMeta->meta_value=1;

							$this->QuoteLineItemMeta->save($newLineItemMeta);

						}
                        
                          /*PPSA-33*/
                        if(isset($this->request->data['fabric-cost-per-yard-custom-value']) 
                        && !empty($this->request->data['fabric-cost-per-yard-custom-value'])){

								$newLineItemMeta=$this->QuoteLineItemMeta->newEntity();
								$newLineItemMeta->quote_item_id=$newItem->id;
								$newLineItemMeta->meta_key='fabric-cost-per-yard-custom-value';
								$newLineItemMeta->meta_value=number_format($this->request->data['fabric-cost-per-yard-custom-value'], 2, '.', '');
                                $this->QuoteLineItemMeta->save($newLineItemMeta);

						}
                        	/*PPSA-33*/
                        /**PPSASSCRUM-29 start */	
						if(isset($orderDetails) && !empty($orderDetails['id'])){
							$this->savetoOrderLineItemTables($newItem->id, $quoteID,$orderDetails['id'], $table='orders' );
							$this->savetoOrderLineItemTables($newItem->id, $quoteID,$orderDetails['id'], $table='work_orders' );	
						}
                      
						/**PPSASSCRUM-29 end */
                        if($this->recalculatequoteadjustments($quoteID)){
							$this->Flash->success('Successfully added SWT Misc Catchall line item to quote');
							
							$this->logActivity($_SERVER['REQUEST_URI'],'Added SWT Misc Catchall to quote '.$thisQuote['quote_number'],$this->Auth->user('id'),json_encode($this->request));
						    return $this->redirect('/quotes/add/'.$quoteID);
						}
						
						
                        
                    }
                    
                    
                    
                }else{
                    
                    $linings=$this->Linings->find('all')->toArray();
					$this->set('linings',$linings);
                    
					$vendorsList=array();
            		$vendors=$this->Vendors->find('all',['order'=>['vendor_name'=>'asc']])->toArray();
            		foreach($vendors as $vendor){
            			$vendorsList[$vendor['id']]=$vendor['vendor_name'];
            		}
            		$this->set('vendorsList',$vendorsList);
					
					
                    $this->render('/Quotes/newcatchall-swtmisc');
                }
            break;
                
			case "simple":

				$this->render('/Quotes/newlineitem');				

			break;

			case "calculator":

				if(!$subsubaction && $subsubaction != 'none'){

					$fabrics=$this->Fabrics->find('all',['conditions'=>['status'=>'Active']])->group('fabric_name')->toArray();

					$this->set('fabrics',$fabrics);

					$linings=$this->Linings->find('all')->toArray();

					$this->set('linings',$linings);

					

					$this->render('newlineitem');

				}else{
				    
				    
					if($this->request->data){

						//process adding this line item to the quote, then refresh the quote table and close fancybox modal

						

						$lineTable=TableRegistry::get('QuoteLineItems');

						$lineMetaTable=TableRegistry::get('QuoteLineItemMeta');

						$newLineItem=$lineTable->newEntity();

						$newLineItem->quote_id=$quoteID;

						$newLineItem->type='lineitem';

						$newLineItem->status='included';

						$newLineItem->lineitemtype='calculator';

						$newLineItem->product_type='calculator';
						
						switch($this->request->data['calculator-used']){
						    case 'pinch-pleated':
						    case 'pinch-pleated-new':
						        $newLineItem->product_class=3;
						        $newLineItem->product_subclass=10;
						        
						    break;
						    case 'bedspread':
						        $newLineItem->product_class=5;
						        $newLineItem->product_subclass=16;
						    break;
						    case 'cubicle-curtain':
						        $newLineItem->product_class=4;
						        $newLineItem->product_subclass=13;
						    break;
						    case 'box-pleated':
						        $newLineItem->product_class=3;
						        $newLineItem->product_subclass=9;
						    break;
						    case 'straight-cornice':
						        $newLineItem->product_class=3;
						        $newLineItem->product_subclass=11;
						    break;
						}
						

						$newLineItem->product_id=-1;

						$newLineItem->title=ucfirst($subaction);

						$newLineItem->qty=$this->request->data['qty'];
						
						$newLineItem->override_active = 0;

						$newLineItem->calculator_used = $this->request->data['calculator-used'];

						switch($this->request->data['calculator-used']){

							case "bedspread":

								$newLineItem->unit='each';

							break;

							case "cubicle-curtain":

								$newLineItem->unit='each';

							break;

							case "box-pleated":

								$newLineItem->unit='each';

							break;

							case "pinch-pleated":
							case "pinch-pleated-new":

								$newLineItem->unit='each';

							break;

							case "straight-cornice":

								$newLineItem->unit='each';

							break;

							default:

								$newLineItem->unit='each';

							break;

						}

						

						//find new line number

						$newlinenumber=$this->getnextlinenumber($quoteID);

						$newLineItem->line_number=$newlinenumber;

						$newLineItem->sortorder=$this->getNewItemSortOrderNumber($quoteID);

                        /**PPSA-40 start **/
                        $newLineItem->created=time();
                        /**PPSA-40 end **/
						$newLineItem->location = $this->request->data['location'];

						$newLineItem->best_price=$this->request->data['price'];

						if($lineTable->save($newLineItem)){

    							if($thisQuote['status'] == 'editorder'){
    							    $itemAddLogTable=TableRegistry::get('QuoteItemAddLog');
    							    $newAddLogRow=$itemAddLogTable->newEntity();
    							    $newAddLogRow->original_quote_id=$thisQuote['parent_quote'];
    							    $newAddLogRow->revision_id=$thisQuote['id'];
    							    $newAddLogRow->line_item_id=$newLineItem->id;
    							    $newAddLogRow->addtime=time();
    							    $newAddLogRow->line_number=$newlinenumber;
    							    $itemAddLogTable->save($newAddLogRow);
    							}

								$this->updatequotemodifiedtime($quoteID);

								

								$newLineItemMeta=$lineMetaTable->newEntity();

								$newLineItemMeta->quote_item_id=$newLineItem->id;

								$newLineItemMeta->meta_key='lineitemtype';

								$newLineItemMeta->meta_value='calculator';

								$lineMetaTable->save($newLineItemMeta);

								

								//save all post data points as meta

								foreach($this->request->data as $key=>$value){

									if($key != "process"){

										$newLineItemMeta=$lineMetaTable->newEntity();

										$newLineItemMeta->quote_item_id=$newLineItem->id;

										$newLineItemMeta->meta_key=$key;

										$newLineItemMeta->meta_value=$value;

										$lineMetaTable->save($newLineItemMeta);

									}

								}

								//if this is a bedspread with Matching Thread option enabled, add Matching Thread line item to quote

								switch($this->request->data['calculator-used']){

									case "bedspread":

									case "bedspread-manual":

										if(isset($this->request->data['matching-thread']) && $this->request->data['matching-thread']=="1"){

											//add service to quote

											$thisService=$this->Services->get(4)->toArray();

											$this->addservicetoquote($quoteID,4,$thisService['price'],1,1,$newlinenumber,'');

										}

										if(isset($this->request->data['quilted']) && $this->request->data['quilted'] == '1'){

											$triggerPatterns=explode("|",$allSettings['quilting_patterns_that_trigger_setup_fee']);

											if(in_array($this->request->data['quilting-pattern'],$triggerPatterns)){

												//special quilting surcharge needed...add service to quote	

												$thisService=$this->Services->get(5)->toArray();

												$this->addservicetoquote($quoteID,5,$thisService['price'],1,0,$newlinenumber,'');

											}

										}

										if($this->request->data['style'] == 'Throw'){

											$newLineItemMeta=$lineMetaTable->newEntity();

											$newLineItemMeta->quote_item_id=$newLineItem->id;

											$newLineItemMeta->meta_key='libraryimageid';

											$newLineItemMeta->meta_value='29';

											$lineMetaTable->save($newLineItemMeta);

										}elseif($this->request->data['style'] == 'Fitted'){

											$newLineItemMeta=$lineMetaTable->newEntity();

											$newLineItemMeta->quote_item_id=$newLineItem->id;

											$newLineItemMeta->meta_key='libraryimageid';

											$newLineItemMeta->meta_value='28';

											$lineMetaTable->save($newLineItemMeta);

										}
										
										/**PPSA-33 start **/
										if(isset($this->request->data['fabric-cost-per-yard-custom-value']) && !empty($this->request->data['fabric-cost-per-yard-custom-value'])){

											$newLineItemMeta=$lineMetaTable->newEntity();

											$newLineItemMeta->quote_item_id=$newLineItem->id;

											$newLineItemMeta->meta_key='fabric-cost-per-yard-custom-value';

											//$newLineItemMeta->meta_value=$this->request->data['fabric-cost-per-yard-custom-value'];
											$newLineItemMeta->meta_value=number_format($this->request->data['fabric-cost-per-yard-custom-value'], 2, '.', '');;

											$lineMetaTable->save($newLineItemMeta);

										}

	                                    /**PPSA-33 end **/

									break;

									case "cubicle-curtain":

										if($this->request->data['railroaded'] == '1'){

											if($this->request->data['mesh-type'] == 'None'){

												$newLineItemMeta=$lineMetaTable->newEntity();

												$newLineItemMeta->quote_item_id=$newLineItem->id;

												$newLineItemMeta->meta_key='libraryimageid';

												$newLineItemMeta->meta_value='31';

												$lineMetaTable->save($newLineItemMeta);

											}elseif($this->request->data['mesh-type'] == 'Integral Mesh'){

												$newLineItemMeta=$lineMetaTable->newEntity();

												$newLineItemMeta->quote_item_id=$newLineItem->id;

												$newLineItemMeta->meta_key='libraryimageid';

												$newLineItemMeta->meta_value='30';

												$lineMetaTable->save($newLineItemMeta);

											}else{

												$newLineItemMeta=$lineMetaTable->newEntity();

												$newLineItemMeta->quote_item_id=$newLineItem->id;

												$newLineItemMeta->meta_key='libraryimageid';

												$newLineItemMeta->meta_value='32';

												$lineMetaTable->save($newLineItemMeta);

											}

										}else{

											if($this->request->data['mesh-type'] == 'None'){

												$newLineItemMeta=$lineMetaTable->newEntity();

												$newLineItemMeta->quote_item_id=$newLineItem->id;

												$newLineItemMeta->meta_key='libraryimageid';

												$newLineItemMeta->meta_value='27';

												$lineMetaTable->save($newLineItemMeta);

											}elseif($this->request->data['mesh-type'] == 'Integral Mesh'){

												$newLineItemMeta=$lineMetaTable->newEntity();

												$newLineItemMeta->quote_item_id=$newLineItem->id;

												$newLineItemMeta->meta_key='libraryimageid';

												$newLineItemMeta->meta_value='30';

												$lineMetaTable->save($newLineItemMeta);

											}else{

												$newLineItemMeta=$lineMetaTable->newEntity();

												$newLineItemMeta->quote_item_id=$newLineItem->id;

												$newLineItemMeta->meta_key='libraryimageid';

												$newLineItemMeta->meta_value='26';

												$lineMetaTable->save($newLineItemMeta);

											}

										}
                                        /*PPSA-33 start */
										if(isset($this->request->data['fabric-cost-per-yard-custom-value']) && !empty($this->request->data['fabric-cost-per-yard-custom-value'])){

											$newLineItemMeta=$lineMetaTable->newEntity();

											$newLineItemMeta->quote_item_id=$newLineItem->id;

											$newLineItemMeta->meta_key='fabric-cost-per-yard-custom-value';

										//	$newLineItemMeta->meta_value=$this->request->data['fabric-cost-per-yard-custom-value'];
										$newLineItemMeta->meta_value=number_format($this->request->data['fabric-cost-per-yard-custom-value'], 2, '.', '');;

											$lineMetaTable->save($newLineItemMeta);

										}
                                        /*PPSA-33 end */
									break;

									case "box-pleated":

										$newLineItemMeta=$lineMetaTable->newEntity();

										$newLineItemMeta->quote_item_id=$newLineItem->id;

										$newLineItemMeta->meta_key='libraryimageid';

										$newLineItemMeta->meta_value='37';

										$lineMetaTable->save($newLineItemMeta);
                                        if(isset($this->request->data['fabric-cost-per-yard-custom-value'])&& !empty($this->request->data['fabric-cost-per-yard-custom-value'])){

											$newLineItemMeta=$lineMetaTable->newEntity();

											$newLineItemMeta->quote_item_id=$newLineItem->id;

											$newLineItemMeta->meta_key='fabric-cost-per-yard-custom-value';

										//	$newLineItemMeta->meta_value=$this->request->data['fabric-cost-per-yard-custom-value'];
										$newLineItemMeta->meta_value=number_format($this->request->data['fabric-cost-per-yard-custom-value'], 2, '.', '');;

											$lineMetaTable->save($newLineItemMeta);

										}
									break;

									case "pinch-pleated":
									case "pinch-pleated-new":

										if($this->request->data['unit-of-measure'] == 'panel'){

											$newLineItemMeta=$lineMetaTable->newEntity();

											$newLineItemMeta->quote_item_id=$newLineItem->id;

											$newLineItemMeta->meta_key='libraryimageid';

											$newLineItemMeta->meta_value='3121';

											$lineMetaTable->save($newLineItemMeta);

										}else{

											$newLineItemMeta=$lineMetaTable->newEntity();

											$newLineItemMeta->quote_item_id=$newLineItem->id;

											$newLineItemMeta->meta_key='libraryimageid';

											$newLineItemMeta->meta_value='3121';

											$lineMetaTable->save($newLineItemMeta);

										}
										
										/*PPSA-33 start **/
										if(isset($this->request->data['fabric-cost-per-yard-custom-value']) && !empty($this->request->data['fabric-cost-per-yard-custom-value'])){

											$newLineItemMeta=$lineMetaTable->newEntity();

											$newLineItemMeta->quote_item_id=$newLineItem->id;

											$newLineItemMeta->meta_key='fabric-cost-per-yard-custom-value';
											$newLineItemMeta->meta_value=number_format($this->request->data['fabric-cost-per-yard-custom-value'], 2, '.', '');

											//$newLineItemMeta->meta_value=$this->request->data['fabric-cost-per-yard-custom-value'];

											$lineMetaTable->save($newLineItemMeta);

										}
										/*PPSA-33 end **/

									break;

									case "straight-cornice":

										if($this->request->data['cornice-type'] == 'Straight'){

											$newLineItemMeta=$lineMetaTable->newEntity();

											$newLineItemMeta->quote_item_id=$newLineItem->id;

											$newLineItemMeta->meta_key='libraryimageid';

											$newLineItemMeta->meta_value='33';

											$lineMetaTable->save($newLineItemMeta);

										}else{

											$newLineItemMeta=$lineMetaTable->newEntity();

											$newLineItemMeta->quote_item_id=$newLineItem->id;

											$newLineItemMeta->meta_key='libraryimageid';

											$newLineItemMeta->meta_value='34';

											$lineMetaTable->save($newLineItemMeta);

										}
										/**PPSA-33 start **/
										if(isset($this->request->data['fabric-cost-per-yard-custom-value']) && !empty($this->request->data['fabric-cost-per-yard-custom-value'])){

											$newLineItemMeta=$lineMetaTable->newEntity();

											$newLineItemMeta->quote_item_id=$newLineItem->id;

											$newLineItemMeta->meta_key='fabric-cost-per-yard-custom-value';
											$newLineItemMeta->meta_value=number_format($this->request->data['fabric-cost-per-yard-custom-value'], 2, '.', '');;

										//	$newLineItemMeta->meta_value=$this->request->data['fabric-cost-per-yard-custom-value'];

											$lineMetaTable->save($newLineItemMeta);

										}
										/** PPSA-33 end **/

									break;

								}

								$newLineItemMeta=$lineMetaTable->newEntity();

								$newLineItemMeta->quote_item_id=$newLineItem->id;

								$newLineItemMeta->meta_key='usealias';

								if($subsubsubaction=='1'){

									$newLineItemMeta->meta_value='yes';

								}else{

									$newLineItemMeta->meta_value='no';

								}

								$lineMetaTable->save($newLineItemMeta);

								

								if($this->recalculatequoteadjustments($quoteID)){

									$this->logActivity($_SERVER['REQUEST_URI'],'Added calculator line #'.$newLineItem->id);
									
									if($thisQuote['status'] == 'editorder'){
									    return $this->redirect('/orders/editlines/'.$thisQuote['order_id']);
									}else{
									    return $this->redirect('/quotes/add/'.$quoteID);
									}

									//echo "<html><head><script>parent.$.fancybox.close();parent.updateQuoteTable();</script></head><body></body></html>";exit;

								}

						}

						

					}else{

						if($subaction == 'track'){

							//we are doing track

							$this->set('track_id',1);

							

							$this->render('/Quotes/Calculators/'.$subaction);

						}else{

							$this->set('fabricid',$subsubaction);

							if($subsubaction=='custom'){

								$fabricData=array('fabric_name'=>'','color'=>'Custom');

							}else{

								$fabricData=$this->Fabrics->get($subsubaction)->toArray();

							}

							$this->set('fabricData',$fabricData);

							

							$linings=$this->Linings->find('all')->toArray();

							$this->set('linings',$linings);

							

							

							if($subaction=='bedspread-manual'){

								$rulecalcs='bedspread';

							}else{

								$rulecalcs=$subaction;

							}

							

							$markuprulesets=$this->FabricMarkupRules->find('all',['conditions'=>['calculator'=>$rulecalcs]])->toArray();

							$this->set('markuprulesets',$markuprulesets);

							

							$this->render('/Quotes/Calculators/'.$subaction);

						}

					}

				}

				

			break;

			case "custom":

				if($this->request->data['process']){

					

					if($subsubaction){

						$thislineitem=$this->QuoteLineItems->get($subsubaction)->toArray();

						if($this->request->data['isedit'] == '1'){

							//delete the component metas before adding them again

							$con=ConnectionManager::get('default');

							$con->execute("DELETE FROM `quote_line_item_meta` WHERE `quote_item_id`='".$subsubaction."' AND `meta_key` LIKE '_component%'");

						}

						$lineItemMetaTable=TableRegistry::get('QuoteLineItemMeta');

						for($i=1; $i <= intval($this->request->data['numlines']); $i++){

							$newLineItemMeta=$lineItemMetaTable->newEntity();
							$newLineItemMeta->quote_item_id=$thislineitem['id'];
							$newLineItemMeta->meta_key="_component_".$i."_qty";
							$newLineItemMeta->meta_value=$this->request->data["qty_line_".$i];
							$lineItemMetaTable->save($newLineItemMeta);


							$newLineItemMeta=$lineItemMetaTable->newEntity();
							$newLineItemMeta->quote_item_id=$thislineitem['id'];
							$newLineItemMeta->meta_key="_component_".$i."_inches";
							$newLineItemMeta->meta_value=$this->request->data["inches_line_".$i];
							$lineItemMetaTable->save($newLineItemMeta);


							$newLineItemMeta=$lineItemMetaTable->newEntity();
							$newLineItemMeta->quote_item_id=$thislineitem['id'];
							$newLineItemMeta->meta_key="_component_".$i."_componentid";
							$newLineItemMeta->meta_value=$this->request->data["component_id_line_".$i];
							$lineItemMetaTable->save($newLineItemMeta);


							$newLineItemMeta=$lineItemMetaTable->newEntity();
							$newLineItemMeta->quote_item_id=$thislineitem['id'];
							$newLineItemMeta->meta_key="_component_".$i."_comment";
							$newLineItemMeta->meta_value=$this->request->data["comment_line_".$i];
							$lineItemMetaTable->save($newLineItemMeta);

						}

						$newLineItemMeta=$lineItemMetaTable->newEntity();
						$newLineItemMeta->quote_item_id=$thislineitem['id'];
						$newLineItemMeta->meta_key="_component_numlines";
						$newLineItemMeta->meta_value=$this->request->data['numlines'];
						$lineItemMetaTable->save($newLineItemMeta);


						$newLineItemMeta=$lineItemMetaTable->newEntity();
						$newLineItemMeta->quote_item_id=$thislineitem['id'];
						$newLineItemMeta->meta_key="_component_lf_total";
						$newLineItemMeta->meta_value=$this->request->data['component_lf_total'];
						$lineItemMetaTable->save($newLineItemMeta);
						

						if($this->request->data['isedit'] == '1'){

							$this->logActivity($_SERVER['REQUEST_URI'],'Edited track components on Quote '.$thisQuote['quote_number']);

						}else{

							$this->logActivity($_SERVER['REQUEST_URI'],'Added track components to Quote '.$thisQuote['quote_number']);

						}


                        if($thisQuote['status'] == 'editorder'){
						    return $this->redirect('/orders/editlines/'.$thisQuote['order_id']);
						}else{
						    return $this->redirect('/quotes/add/'.$quoteID);
						}

						

					}else{
                        
                        if(isset($this->request->data['imagefileupload']['tmp_name']) && $this->request->data['image_method'] == 'upload'){
                            $allowedTypes=array('.jpg','jpeg','.png','.gif');
        					if(!in_array(strtolower(substr($this->request->data['imagefileupload']['name'],-4)),$allowedTypes)){
        					    $this->autoRender=false;
        					    echo '<h3><span style="color:red;">ERROR:</span> The filetype <u>'.substr($this->request->data['imagefileupload']['name'],-4).'</u> is not allowed in the image field.</h3>
                                <p><button type="button" onclick="history.go(-1);">Go Back</button></p>';
        					    exit;
        					}
                        }

						$quoteItemsTable=TableRegistry::get('QuoteLineItems');

						$quoteItemMetaTable=TableRegistry::get('QuoteLineItemMeta');

						$newItem=$quoteItemsTable->newEntity();

						$newItem->type='lineitem';

						$newItem->quote_id=$quoteID;

						$newItem->status='included';

						$newItem->product_type='custom';
						
						$newItem->calculator_used=$this->request->data['category'];

						$newItem->product_id=-1;
						
						$newItem->override_active = 0;

						$newItem->title=$this->request->data['line_item_title'];

						$newItem->unit = $this->request->data['unit_type'];

						$newItem->description=$this->request->data['description'];

						$newItem->qty=$this->request->data['qty'];

						$newItem->best_price=$this->request->data['price'];

						//adjusted

						$newItem->lineitemtype='custom';

						$newItem->room_number=$this->request->data['room_number'];

						if(!$subaction){

							$newItem->line_number=$this->getnextlinenumber($quoteID);
							
							$newItem->sortorder = $this->getnextlinesortorder($quoteID);

							$newItem->parent_line=0;

						}else{

							$newItem->line_number=$subaction;

							//determine the current parent line's actual primary key

							
							$currentParentLookup=$this->QuoteLineItems->find('all',['conditions'=>['line_number' => $subaction,'quote_id' => $quoteID, 'parent_line' => 0],'order'=>['line_number'=>'asc']])->limit(1)->toArray();

							$newsortorder=0;
							$parentid=0;
							foreach($currentParentLookup as $parentline){
								$newItem->parent_line=$parentline['id'];
								$parentid=$parentline['id'];
								$newsortorder=$parentline['sort_order'];
								$parentsortordernumber=$parentline['sortorder'];
							}

							//find out how many child lines this parent line already has
							$parentChildrenCount=$this->QuoteLineItems->find('all',['conditions' => ['parent_line' => $parentid]])->count();
							
							//determine the new sort order
							$newsortorder=$parentsortordernumber.'.'.($parentChildrenCount+1);
							
							$newItem->sortorder = floatval($newsortorder);
							

						}

						$newItem->internal_line=0;
						/**PPSA-40 start **/
                    $newItem->created=time();
                    /**PPSA-40 end **/
						$newItem->enable_tally = 1;
						

						if($quoteItemsTable->save($newItem)){

                            if($thisQuote['status'] == 'editorder'){
							    $itemAddLogTable=TableRegistry::get('QuoteItemAddLog');
							    $newAddLogRow=$itemAddLogTable->newEntity();
							    $newAddLogRow->original_quote_id=$thisQuote['parent_quote'];
							    $newAddLogRow->revision_id=$thisQuote['id'];
							    $newAddLogRow->line_item_id=$newItem->id;
							    $newAddLogRow->addtime=time();
							    $newAddLogRow->line_number=$newItem->line_number;
							    $itemAddLogTable->save($newAddLogRow);
							}

							if($this->recalculatequoteadjustments($quoteID)){
								$this->Flash->success('Successfully added custom line item to quote');
							}

							//add meta data
							
							
							$newLineItemMeta=$quoteItemMetaTable->newEntity();

							$newLineItemMeta->quote_item_id=$newItem->id;

							$newLineItemMeta->meta_key='catchallcategory';

							$newLineItemMeta->meta_value=$this->request->data['category'];

							$quoteItemMetaTable->save($newLineItemMeta);
							
							
							

							$newLineItemMeta=$quoteItemMetaTable->newEntity();

							$newLineItemMeta->quote_item_id=$newItem->id;

							$newLineItemMeta->meta_key='lineitemtype';

							$newLineItemMeta->meta_value='custom';

							$quoteItemMetaTable->save($newLineItemMeta);

							$newLineItemMeta=$quoteItemMetaTable->newEntity();

							$newLineItemMeta->quote_item_id = $newItem->id;

							$newLineItemMeta->meta_key='qty';

							$newLineItemMeta->meta_value=$this->request->data['qty'];

							$quoteItemMetaTable->save($newLineItemMeta);

							

							

							

							

							$newLineItemMeta=$quoteItemMetaTable->newEntity();

							$newLineItemMeta->quote_item_id = $newItem->id;

							$newLineItemMeta->meta_key='specs';

							$newLineItemMeta->meta_value=$this->request->data['specs'];

							$quoteItemMetaTable->save($newLineItemMeta);

							

							

							

							$newLineItemMeta=$quoteItemMetaTable->newEntity();

							$newLineItemMeta->quote_item_id=$newItem->id;

							$newLineItemMeta->meta_key='fabrictype';

							$newLineItemMeta->meta_value=$this->request->data['fabric_type'];

							$quoteItemMetaTable->save($newLineItemMeta);

							$newLineItemMeta=$quoteItemMetaTable->newEntity();

							$newLineItemMeta->quote_item_id=$newItem->id;

							$newLineItemMeta->meta_key='unit_type';

							$newLineItemMeta->meta_value=$this->request->data['unit_type'];

							$quoteItemMetaTable->save($newLineItemMeta);

							if($this->request->data['fabric_type']=="existing"){

								$newLineItemMeta=$quoteItemMetaTable->newEntity();

								$newLineItemMeta->quote_item_id=$newItem->id;

								$newLineItemMeta->meta_key='fabricid';

								$newLineItemMeta->meta_value=$this->request->data['fabric_id_with_color'];

								$quoteItemMetaTable->save($newLineItemMeta);

								$selectedFabric=$this->Fabrics->get($this->request->data['fabric_id_with_color'])->toArray();

								$newLineItemMeta=$quoteItemMetaTable->newEntity();

								$newLineItemMeta->quote_item_id=$newItem->id;

								$newLineItemMeta->meta_key='fabric_name';

								$newLineItemMeta->meta_value=$selectedFabric['fabric_name'];

								$quoteItemMetaTable->save($newLineItemMeta);

								

								$newLineItemMeta=$quoteItemMetaTable->newEntity();

								$newLineItemMeta->quote_item_id=$newItem->id;

								$newLineItemMeta->meta_key='fabric_color';

								$newLineItemMeta->meta_value=$selectedFabric['color'];

								$quoteItemMetaTable->save($newLineItemMeta);

							}elseif($this->request->data['fabric_type']=="typein"){

								$newLineItemMeta=$quoteItemMetaTable->newEntity();

								$newLineItemMeta->quote_item_id=$newItem->id;

								$newLineItemMeta->meta_key='fabric_name';

								$newLineItemMeta->meta_value=$this->request->data['fabric_name'];

								$quoteItemMetaTable->save($newLineItemMeta);

								

								$newLineItemMeta=$quoteItemMetaTable->newEntity();

								$newLineItemMeta->quote_item_id=$newItem->id;

								$newLineItemMeta->meta_key='fabric_color';

								$newLineItemMeta->meta_value=$this->request->data['fabric_color'];

								$quoteItemMetaTable->save($newLineItemMeta);

							}

							

							

							$newLineItemMeta=$quoteItemMetaTable->newEntity();

							$newLineItemMeta->quote_item_id=$newItem->id;

							$newLineItemMeta->meta_key='width';

							$newLineItemMeta->meta_value=$this->request->data['cut_width'];

							$quoteItemMetaTable->save($newLineItemMeta);

							

							$newLineItemMeta=$quoteItemMetaTable->newEntity();

							$newLineItemMeta->quote_item_id=$newItem->id;

							$newLineItemMeta->meta_key='fw';

							$newLineItemMeta->meta_value=$this->request->data['finished_width'];

							$quoteItemMetaTable->save($newLineItemMeta);

							

							$newLineItemMeta=$quoteItemMetaTable->newEntity();

							$newLineItemMeta->quote_item_id=$newItem->id;

							$newLineItemMeta->meta_key='length';

							$newLineItemMeta->meta_value=$this->request->data['finished_length'];

							$quoteItemMetaTable->save($newLineItemMeta);

							

							$newLineItemMeta=$quoteItemMetaTable->newEntity();

							$newLineItemMeta->quote_item_id=$newItem->id;

							$newLineItemMeta->meta_key='yds-per-unit';

							$newLineItemMeta->meta_value=$this->request->data['fabric_ydsper'];

							$quoteItemMetaTable->save($newLineItemMeta);

							

							$newLineItemMeta=$quoteItemMetaTable->newEntity();

							$newLineItemMeta->quote_item_id=$newItem->id;

							$newLineItemMeta->meta_key='total-yds';

							$newLineItemMeta->meta_value=(floatval($this->request->data['qty'])*floatval($this->request->data['fabric_ydsper']));

							$quoteItemMetaTable->save($newLineItemMeta);

							

							/*
							$newLineItemMeta=$quoteItemMetaTable->newEntity();

							$newLineItemMeta->quote_item_id=$newItem->id;

							$newLineItemMeta->meta_key='fabric-margin';

							$newLineItemMeta->meta_value=$this->request->data['fabric_margin'];

							$quoteItemMetaTable->save($newLineItemMeta);

							

							$newLineItemMeta=$quoteItemMetaTable->newEntity();

							$newLineItemMeta->quote_item_id=$newItem->id;

							$newLineItemMeta->meta_key='fabric-profit';

							$newLineItemMeta->meta_value=$this->request->data['fabric_profit'];

							$quoteItemMetaTable->save($newLineItemMeta);
                            */
							

							$newLineItemMeta=$quoteItemMetaTable->newEntity();

							$newLineItemMeta->quote_item_id=$newItem->id;

							$newLineItemMeta->meta_key='room_number';

							$newLineItemMeta->meta_value=$this->request->data['room_number'];

							$quoteItemMetaTable->save($newLineItemMeta);

							

							$newLineItemMeta=$quoteItemMetaTable->newEntity();

							$newLineItemMeta->quote_item_id=$newItem->id;

							$newLineItemMeta->meta_key='price';

							$newLineItemMeta->meta_value=$this->request->data['price'];

							$quoteItemMetaTable->save($newLineItemMeta);

							

							if($this->request->data['image_method'] == 'library'){
								$newLineItemMeta=$quoteItemMetaTable->newEntity();
								$newLineItemMeta->quote_item_id=$newItem->id;
								$newLineItemMeta->meta_key='libraryimageid';
								$newLineItemMeta->meta_value=$this->request->data['libraryimageid'];
								$quoteItemMetaTable->save($newLineItemMeta);
								$newLineItemMeta=$quoteItemMetaTable->newEntity();
								$newLineItemMeta->quote_item_id=$newItem->id;
								$newLineItemMeta->meta_key='vendors_id';
								$newLineItemMeta->meta_value=$this->request->data['vendors_id'];
								$quoteItemMetaTable->save($newLineItemMeta);
							}elseif($this->request->data['image_method'] == 'upload'){
								
								//handle the upload, and process whether to save this to library or just do a one-off insert
								$filename=$this->request->data['imagefileupload']['name'];
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
								
								
								
								
								move_uploaded_file($this->request->data['imagefileupload']['tmp_name'],$_SERVER['DOCUMENT_ROOT']."/webroot/img/library/".$filename);
								
								$imgLibraryTable=TableRegistry::get('LibraryImages');
								$newImage=$imgLibraryTable->newEntity();
								
								if($this->request->data['save_to_library'] == '1'){
									
									$selectedCat='';
									$allCats=$this->LibraryCategories->find('all')->toArray();
									foreach($allCats as $catEntry){
										if($catEntry['id'] == $this->request->data['image_category']){
											$selectedCat=$catEntry['category_title'];
										}
									}
									
									//insert into image library db with details for future usage
									$newImage->image_title=$this->request->data['image_title'];
									$newImage->categories=$selectedCat;
									$newImage->filename=$filename;
									$newImage->time=time();
									$newImage->added_by=$this->Auth->user('id');
									$newImage->tags=$this->request->data['image_tags'];
									$newImage->status='Active';
								}else{
									//insert into image library db with no details for single usage
									$newImage->image_title=$this->request->data['imagefileupload']['name'];
									$newImage->categories='Misc';
									$newImage->filename=$filename;
									$newImage->time=time();
									$newImage->added_by=$this->Auth->user('id');
									$newImage->status='Single Use';
								}
								
								if($imgLibraryTable->save($newImage)){
									//insert the metadata for libraryimageid
									$newLineItemMeta=$quoteItemMetaTable->newEntity();
									$newLineItemMeta->quote_item_id=$newItem->id;
									$newLineItemMeta->meta_key='libraryimageid';
									$newLineItemMeta->meta_value=$newImage->id;
									$quoteItemMetaTable->save($newLineItemMeta);

									$this->aspectratiofix($newImage->id,600);

								}
								
								
							}elseif($this->request->data['image_method'] == 'none'){
								//do not set libraryimageid meta
								
								
							}

							

							if($this->request->data['com-fabric']=='1'){

								$newLineItemMeta=$quoteItemMetaTable->newEntity();

								$newLineItemMeta->quote_item_id=$newItem->id;

								$newLineItemMeta->meta_key='com-fabric';

								$newLineItemMeta->meta_value=1;

								$quoteItemMetaTable->save($newLineItemMeta);

							}

							
							
							
/**PPSASSCRUM-29 start */	
if(isset($orderDetails) && !empty($orderDetails['id'])){
	$this->savetoOrderLineItemTables($newItem->id, $quoteID,$orderDetails['id'], $table='orders' );
	$this->savetoOrderLineItemTables($newItem->id, $quoteID,$orderDetails['id'], $table='work_orders' );	
}

/**PPSASSCRUM-29 end */
							

							$this->logActivity($_SERVER['REQUEST_URI'],'Added custom line item to quote '.$thisQuote['quote_number'],$this->Auth->user('id'),json_encode($this->request));


                            if($thisQuote['status'] == 'editorder'){
							    return $this->redirect('/orders/editlines/'.$thisQuote['order_id']);
							}else{
							    return $this->redirect('/quotes/add/'.$quoteID);
							}

						}

					}

					

				}else{

					$fabrics=$this->Fabrics->find('all',['conditions'=>['status'=>'Active']])->group('fabric_name')->toArray();

					$this->set('fabrics',$fabrics);

					$vendorsList=array();

					$vendors=$this->Vendors->find('all')->toArray();

					foreach($vendors as $vendor){

						$vendorsList[$vendor['id']]=$vendor['vendor_name'];

					}

					$this->set('vendorsList',$vendorsList);

					if($subsubaction){

						$thisLineItem=$this->QuoteLineItems->get($subsubaction)->toArray();

						$this->set('lineitemdata',$thisLineItem);

						$citemMetas=$this->QuoteLineItemMeta->find('all',['conditions'=>['quote_item_id'=>$thisLineItem['id']]])->toArray();

						$cmetaArray=array();

						

						foreach($citemMetas as $meta){

							$cmetaArray[$meta['meta_key']]=$meta['meta_value'];

						}

						$this->set('lineitemmetadata',$cmetaArray);

						$allTrackComponents=$this->TrackSystems->find('all',['conditions'=>['system_or_component' => 'component'], 'order' => ['title'=>'ASC']])->toArray();

						$allchildcomponents='';

						foreach($allTrackComponents as $component){

							$allchildcomponents .= "<option value=\"".$component['id']."\" data-inches-equivalent=\"".$component['inches_equivalent']."\">".$component['title']."</option>";

						}

						$this->set('allchildcomponents',$allchildcomponents);

						$this->set('componentsarr',$allTrackComponents);

					}

					
					$libraryCatsLookup=$this->LibraryCategories->find('all')->toArray();
					$allCats=array();
					foreach($libraryCatsLookup as $catEntry){
						$allCats[$catEntry['id']]=$catEntry['category_title'];
					}
					
					$this->set('allLibraryCats',$allCats);
					
					$libraryimages=$this->LibraryImages->find('all',['conditions'=>['status'=>'Active']])->toArray();

					$this->set('libraryimages',$libraryimages);
					
					
					
					

					$this->render('/Quotes/newlineitem');

				}

			break;

		}

	}
	

	//424/1478/Cityview/389/5/2/3/100.64/3.65/9.17/2.22/0/36%22/76/19/None/112/0/0/no/0/1

	

	public function editquotebs($quoteID,$lineID,$fabricname,$fabricid,$bsID,$bsSize,$qty,$priceEach,$weight,$diff,$yards,$quilted=0,$mattress='36"',$topwidthsl='',$dropwidthsl='',$quiltpattern='',$topcutsw='',$dropcutsw='',$repeat='',$usealias='no',$specialpricing,$layout=1.33,$location){

		$this->autoRender=false;

		$bsData=$this->Bedspreads->get($bsID)->toArray();

		$thisSize=$this->BedspreadSizes->get($bsSize)->toArray();		

		$thisFabric=$this->Fabrics->get($fabricid)->toArray();

		$lineItemTable=TableRegistry::get('QuoteLineItems');

		$thisLineItem=$lineItemTable->get($lineID);

		$thisLineItem->product_id=$bsID;

		$thisLineItem->room_number = $location;

		$thisLineItem->best_price=$priceEach;

		//$thisLineItem->title=$thisSize['title'].', '.$thisFabric['color'];

        $thisLineItem->title=$bsData['title'];

		$thisLineItem->description = $thisSize['title'].', '.$thisFabric['color'];
	
		$thisLineItem->qty=$qty;

		

		if($lineItemTable->save($thisLineItem)){

			

			$this->updatequotemodifiedtime($quoteID);
			

			//update line metas

			$this->updateLineItemMeta($lineID,'price',$priceEach);

			$this->updateLineItemMeta($lineID,'difficulty-rating',$diff);

			$this->updateLineItemMeta($lineID,'bs_calculated_weight',$weight);

			$this->updateLineItemMeta($lineID,'yds-per-unit',$yards);

			$this->updateLineItemMeta($lineID,'specialpricing',$specialpricing);

			$this->updateLineItemMeta($lineID,'total-yds',(floatval($yards) * floatval($qty)));

			$this->updateLineItemMeta($lineID,'custom-top-width-mattress-w',$mattress);

			

			$this->updateLineItemMeta($lineID,'fabricid',$fabricid);

			$this->updateLineItemMeta($lineID,'length',$thisSize['length']);

			

			$this->updateLineItemMeta($lineID,'width',$thisSize['width']);

			

			$this->updateLineItemMeta($lineID,'drop-cut',$dropcutsw);

			$this->updateLineItemMeta($lineID,'drop-widths',$dropwidthsl);

			$this->updateLineItemMeta($lineID,'top-cut',$topcutsw);

			$this->updateLineItemMeta($lineID,'top-widths',$topwidthsl);

			

			$this->updateLineItemMeta($lineID,'fabric_name',$fabricname);

			$this->updateLineItemMeta($lineID,'usealias',$usealias);

			

			$this->updateLineItemMeta($lineID,'vertical-repeat',$repeat);

			

			if(preg_match("#Fitted#i",$thisSize['title'])){

				$this->updateLineItemMeta($lineID,'libraryimageid','28');

			}elseif(preg_match("#Throw#i",$thisSize['title'])){

				$this->updateLineItemMeta($lineID,'libraryimageid','29');

			}

			

			$this->updateLineItemMeta($lineID,'style',$thisSize['title']);

			$this->updateLineItemMeta($lineID,'quilted',$quilted);

			$this->updateLineItemMeta($lineID,'qty',$qty);

			//get adjusted prices

			if($this->recalculatequoteadjustments($quoteID)){

				echo "OK";exit;

			}

			

		}

		

	}

	
	

	public function editquotecc($quoteID,$lineID,$fabricname,$fabricid,$ccid,$sizeID,$qty,$priceeach,$length,$mesh,$weight,$yards,$lf,$difficulty,$specialpricing,$finishedwidth,$usealias,$location){


		$this->autoRender=false;

		$exp=explode('"',$mesh);
		$meshlength=$exp[0];
		
		
		$ccData=$this->CubicleCurtains->get($ccid)->toArray();

		if($sizeID != 'nochange'){

			$thisSize=$this->CubicleCurtainSizes->get($sizeID)->toArray();

		}

		$thisFabric=$this->Fabrics->get($fabricid)->toArray();

		

		$lineItemTable=TableRegistry::get('QuoteLineItems');

		$thisLineItem=$lineItemTable->get($lineID);

		$thisLineItem->product_id=$ccid;

		$thisLineItem->room_number = $location;

		$thisLineItem->best_price=$priceeach;
            
        $thisLineItem->title=$ccData['title'];

		$thisLineItem->description = $thisSize['title'].', '.$thisFabric['color'];

		$thisLineItem->qty=$qty;

		

		if($lineItemTable->save($thisLineItem)){

			

			$this->updatequotemodifiedtime($quoteID);

			//update line metas

			$this->updateLineItemMeta($lineID,'price',$priceeach);

			$this->updateLineItemMeta($lineID,'difficulty-rating',$difficulty);

			$this->updateLineItemMeta($lineID,'cc_calculated_weight',$weight);

			if($finishedwidth != '0'){
				$this->updateLineItemMeta($lineID,'expected-finish-width',$finishedwidth);
			}else{
				$this->deleteLineItemMeta($lineID,'expected-finish-width');
			}

			$this->updateLineItemMeta($lineID,'yds-per-unit',$yards);

			if($mesh=='No Mesh'){

				$this->updateLineItemMeta($lineID,'libraryimageid',27);

			}else{

				$this->updateLineItemMeta($lineID,'libraryimageid',26);

			}

			$this->updateLineItemMeta($lineID,'specialpricing',$specialpricing);

			$this->updateLineItemMeta($lineID,'total-yds',(floatval($yards) * floatval($qty)));

			$this->updateLineItemMeta($lineID,'labor-billable',$lf);

			$this->updateLineItemMeta($lineID,'fabricid',$fabricid);

			$this->updateLineItemMeta($lineID,'length',$length);

			if($sizeID != 'nochange'){

				$this->updateLineItemMeta($lineID,'width',$thisSize['width']);

			}

			$this->updateLineItemMeta($lineID,'fabric_name',$fabricname);

			$this->updateLineItemMeta($lineID,'usealias',$usealias);

			if($mesh=='No Mesh'){

				$this->updateLineItemMeta($lineID,'mesh-type','None');

				$this->updateLineItemMeta($lineID,'mesh-color','None');

				$this->updateLineItemMeta($lineID,'mesh','0');

			}else{

				$this->updateLineItemMeta($lineID,'mesh-type','MOM Mesh');

				if(preg_match("#white#i",$mesh)){

					$this->updateLineItemMeta($lineID,'mesh-color','White');

				}elseif(preg_match("#beige#i",$mesh)){

					$this->updateLineItemMeta($lineID,'mesh-color','Beige');

				}

				$this->updateLineItemMeta($lineID,'mesh',$meshlength);

			}

			$this->updateLineItemMeta($lineID,'qty',$qty);

			//get adjusted prices

			if($this->recalculatequoteadjustments($quoteID)){

				echo "OK";exit;

			}

		}

		

	}

	

	public function editquotewt($quoteID,$lineID,$wttype,$fabricname,$fabricwithcolorid,$wtID,$wtSizeID,$qty,$priceEach,$weight,$diff,$yards,$haswelts=0,$lining,$pairorpanel,$laborwidths=0,$specialpricing,$lengthentered='no',$usealias='no',$shortpoint=false,$location){
		
		//2769/7702/Shaped%20Cornice/Branches/436/86/13/2/209.76/0.00/0.00/1.49/1/null/null/5.00/0/no/12/TEST%2032

		$this->autoRender=false;

		$wtData=$this->WindowTreatments->get($wtID)->toArray();

		if($wtSizeID != 'nochange'){
			$thisSize=$this->WindowTreatmentSizes->get($wtSizeID)->toArray();
		}

		$thisFabric=$this->Fabrics->get($fabricwithcolorid)->toArray();

		$lineItemTable=TableRegistry::get('QuoteLineItems');

		$thisLineItem=$lineItemTable->get($lineID);
		$thisLineItem->product_id=$wtID;
		$thisLineItem->room_number = $location;
		$thisLineItem->best_price=$priceEach;
		$thisLineItem->qty=$qty;

		if($lineItemTable->save($thisLineItem)){

			$this->updatequotemodifiedtime($quoteID);

			//update line metas
			$this->updateLineItemMeta($lineID,'specialpricing',$specialpricing);
			$this->updateLineItemMeta($lineID,'price',$priceEach);
			$this->updateLineItemMeta($lineID,'fabricid',$fabricwithcolorid);
			$this->updateLineItemMeta($lineID,'usealias',$usealias);
			$this->updateLineItemMeta($lineID,'fabric_name',$thisFabric['fabric_name']);
				

			if(preg_match("#Cornice#i",$wttype)){
				$this->updateLineItemMeta($lineID,'welt-top',$haswelts);
				$this->updateLineItemMeta($lineID,'welt-bottom',$haswelts);
			}

			//SIZE DATA
			if($wttype=='Pinch Pleated Drapery'){
				$this->updateLineItemMeta($lineID,'rod-width',number_format($thisSize['width'],0,'',''));
			}else{
				$this->updateLineItemMeta($lineID,'width',number_format($thisSize['width'],0,'',''));
			}
				

			if($lengthentered != 'no'){
				$this->updateLineItemMeta($lineID,'length',$lengthentered);
			}else{
				$this->updateLineItemMeta($lineID,'length',number_format($thisSize['length'],0,'',''));
			}

				

			if($shortpoint){
				$this->updateLineItemMeta($lineID,'fl-short',number_format($shortpoint,0,'',''));
			}

				
			$this->updateLineItemMeta($lineID,'wt_calculated_weight',$weight);
			
			$this->updateLineItemMeta($lineID,'difficulty-rating',$diff);	

			$this->updateLineItemMeta($lineID,'yds-per-unit',$yards);	

			$this->updateLineItemMeta($lineID,'total-yds',(floatval($yards)*floatval($qty)));

			$this->updateLineItemMeta($lineID,'qty',$qty);

			if($wttype=='Shaped Cornice'){
				$this->updateLineItemMeta($lineID,'libraryimageid','34');
			}

			if($wttype == 'Straight Cornice'){
				$this->updateLineItemMeta($lineID,'libraryimageid','33');
			}

			if($wttype == 'Box Pleated Valance'){
				$this->updateLineItemMeta($lineID,'libraryimageid','38');
			}

			if($wttype == 'Tailored Valance'){
				$this->updateLineItemMeta($lineID,'libraryimageid','39');
			}

			if($wttype=='Pinch Pleated Drapery'){
				$this->updateLineItemMeta($lineID,'libraryimageid','40');
			}

			if(preg_match("#Cornice#i",$wttype)){
				$this->updateLineItemMeta($lineID,'linings_id','2');
				$this->updateLineItemMeta($lineID,'yds-of-lining',$yards);
				$this->updateLineItemMeta($lineID,'return','3');
				$this->updateLineItemMeta($lineID,'labor-billable',$laborwidths);
			}

			if(preg_match("#Valance#i",$wttype)){
				$this->updateLineItemMeta($lineID,'linings_id','3');
				$this->updateLineItemMeta($lineID,'yds-of-lining',$yards);
				$this->updateLineItemMeta($lineID,'return','3');
				$this->updateLineItemMeta($lineID,'labor-billable',$laborwidths);
			}

			if($wttype=='Tailored Valance'){
				$this->updateLineItemMeta($lineID,'pleats','3');
			}

			if($wttype=='Box Pleated Valance'){
				$pleats=0;

				switch($thisSize['width']){
					case 36:
						$pleats=3;
					break;
					case 48:
						$pleats=4;
						break;
					case 60:
						$pleats=5;
					break;
					case 72:
					case 84:
						$pleats=6;
					break;
					case 96:
						$pleats=7;
					break;
					case 108:
					case 120:
						$pleats=8;
					break;
					case 132:
					case 144:
						$pleats=9;
					break;
				}

				$this->updateLineItemMeta($lineID,'pleats',$pleats);
			}

			if($wttype=='Pinch Pleated Drapery'){

				$this->updateLineItemMeta($lineID,'labor-widths',$laborwidths);
				$this->updateLineItemMeta($lineID,'unit-of-measure',$pairorpanel);
				$this->updateLineItemMeta($lineID,'hardware','No Hardware');
				$this->updateLineItemMeta($lineID,'default-overlap','3.5');
				$this->updateLineItemMeta($lineID,'default-return','3.5');
				$this->updateLineItemMeta($lineID,'fullness','2');
				$this->updateLineItemMeta($lineID,'weights','1');
				$this->updateLineItemMeta($lineID,'weight-count','2');
				$this->updateLineItemMeta($lineID,'pinset','1.5');

				switch($lining){
					case "BO Lining":
						$this->updateLineItemMeta($lineID,'linings_id','3');
						$this->updateLineItemMeta($lineID,'yds-of-lining',$yards);
					break;
					case "FR Lining":
						$this->updateLineItemMeta($lineID,'linings_id','1');
						$this->updateLineItemMeta($lineID,'yds-of-lining',$yards);
					break;
				}
			}


			$this->updateLineItemMeta($lineID,'qty',$qty);

			//get adjusted prices
			if($this->recalculatequoteadjustments($quoteID)){

				echo "OK";exit;

			}

		}

	}


	public function editquoteservice(){

	}

	public function editquotetrack($lineItemID,$quoteID,$productid,$qty,$price,$description,$location){
        $this->autoRender=false;
        
        $thisLineItem=$this->QuoteLineItems->get($lineItemID);
        $thisLineItem->description=$description;
        $thisLineItem->room_number = $location;
        $thisLineItem->qty = $qty;
        $thisLineItem->best_price=$price;
        
        //update METAs
        
        //update TIERS
        
        
        //save
        if($this->QuoteLineItems->save($thisLineItem)){
            if($this->recalculatequoteadjustments($quoteID)){
                echo "OK";exit;
            }
        }
        
	}



	//public function addcctoquote($quoteID,$fabricname,$fabricid,$ccid,$sizeID,$qty,$priceeach,$length,$mesh){

	public function addcctoquote($quoteID,$fabricname,$fabricid,$ccid,$sizeID,$qty,$priceeach,$length,$mesh,$weight,$yards,$lf,$difficulty,$specialpricing,$finishedwidth,$usealias='no',$location=''){

		$location=$this->cleanparameterreplacements($location);

		if($finishedwidth=='no'){

			$finishedwidth='';

		}
		
		$thisQuote=$this->Quotes->get($quoteID)->toArray();
		

		$this->autoRender=false;

		$ccData=$this->CubicleCurtains->get($ccid)->toArray();

		$thisSize=$this->CubicleCurtainSizes->get($sizeID)->toArray();

		$thisFabric=$this->Fabrics->get($fabricid)->toArray();

		

		$lineTable=TableRegistry::get('QuoteLineItems');

		$lineMetaTable=TableRegistry::get('QuoteLineItemMeta');

		$newLineItem=$lineTable->newEntity();

		$newLineItem->quote_id=$quoteID;

		$newLineItem->type='lineitem';

		$newLineItem->status='included';

		$newLineItem->product_type='cubicle_curtains';

		$newLineItem->product_id=$ccid;
		
		$newLineItem->product_class=4;
		$newLineItem->product_subclass=13;

		$newLineItem->room_number = $location;

		$newLineItem->title=$ccData['title'];

		$newLineItem->description = $thisSize['title'].', '.$thisFabric['color'];
		
		$newLineItem->override_active = 0;

		$newLineItem->best_price=$priceeach;

		//adjusted

		$newLineItem->qty=$qty;

		$newLineItem->lineitemtype='simple';

		$newLineItem->unit='each';

		

		//find new line number

		$newLineItem->line_number=$this->getnextlinenumber($quoteID);

		$newLineItem->sortorder=$this->getNewItemSortOrderNumber($quoteID);
        /**PPSA-40 start **/
        $newLineItem->created=time();
        /**PPSA-40 end **/
		

		if($lineTable->save($newLineItem)){
		    
		        //if this is EDIT ORDER function, add a line to Quote Item Add Log so it will know what to do when applying changes
                if($thisQuote['status'] == 'editorder'){
                    $newAddLog=$this->QuoteItemAddLog->newEntity();
                    $newAddLog->original_quote_id = $thisQuote['parent_quote'];
                    $newAddLog->revision_id=$thisQuote['id'];
                    $newAddLog->line_item_id=$newLineItem->id;
                    $newAddLog->addtime=time();
                    $newAddLog->line_number=$newLineItem->line_number;
                    $this->QuoteItemAddLog->save($newAddLog);
                }
		    

                $this->logActivity($_SERVER['REQUEST_URI'],'Added pricelist "'.$ccData['title'].'" as Line #'.$newLineItem->line_number.' to quote '.$thisQuote['quote_number'],$this->Auth->user('id'),json_encode($this->request));

				$this->updatequotemodifiedtime($quoteID);

				//meta data time

				$newLineItemMeta=$lineMetaTable->newEntity();

				$newLineItemMeta->quote_item_id=$newLineItem->id;

				$newLineItemMeta->meta_key='lineitemtype';

				$newLineItemMeta->meta_value='simpleproduct';

				$lineMetaTable->save($newLineItemMeta);

				

				

				$newLineItemMeta=$lineMetaTable->newEntity();

				$newLineItemMeta->quote_item_id=$newLineItem->id;

				$newLineItemMeta->meta_key='price';

				$newLineItemMeta->meta_value=$priceeach;

				$lineMetaTable->save($newLineItemMeta);

				

				

				$newLineItemMeta=$lineMetaTable->newEntity();

				$newLineItemMeta->quote_item_id=$newLineItem->id;

				$newLineItemMeta->meta_key='difficulty-rating';

				$newLineItemMeta->meta_value=$difficulty;

				$lineMetaTable->save($newLineItemMeta);

				

				$newLineItemMeta=$lineMetaTable->newEntity();

				$newLineItemMeta->quote_item_id=$newLineItem->id;

				$newLineItemMeta->meta_key='cc_calculated_weight';

				$newLineItemMeta->meta_value=$weight;

				$lineMetaTable->save($newLineItemMeta);

				

				$newLineItemMeta=$lineMetaTable->newEntity();

				$newLineItemMeta->quote_item_id=$newLineItem->id;

				$newLineItemMeta->meta_key='expected-finish-width';

				$newLineItemMeta->meta_value=$finishedwidth;

				$lineMetaTable->save($newLineItemMeta);

				

				$newLineItemMeta=$lineMetaTable->newEntity();

				$newLineItemMeta->quote_item_id=$newLineItem->id;

				$newLineItemMeta->meta_key='yds-per-unit';

				$newLineItemMeta->meta_value=$yards;

				$lineMetaTable->save($newLineItemMeta);

				if($mesh=='No Mesh'){

					$newLineItemMeta=$lineMetaTable->newEntity();

					$newLineItemMeta->quote_item_id=$newLineItem->id;

					$newLineItemMeta->meta_key='libraryimageid';

					$newLineItemMeta->meta_value='27';

					$lineMetaTable->save($newLineItemMeta);

				}else{

					$newLineItemMeta=$lineMetaTable->newEntity();

					$newLineItemMeta->quote_item_id=$newLineItem->id;

					$newLineItemMeta->meta_key='libraryimageid';

					$newLineItemMeta->meta_value='26';

					$lineMetaTable->save($newLineItemMeta);

				}

				$newLineItemMeta=$lineMetaTable->newEntity();

				$newLineItemMeta->quote_item_id=$newLineItem->id;

				$newLineItemMeta->meta_key='specialpricing';

				$newLineItemMeta->meta_value=$specialpricing;

				$lineMetaTable->save($newLineItemMeta);

				

				

				$newLineItemMeta=$lineMetaTable->newEntity();

				$newLineItemMeta->quote_item_id=$newLineItem->id;

				$newLineItemMeta->meta_key='total-yds';

				$newLineItemMeta->meta_value=(floatval($yards)*floatval($qty));

				$lineMetaTable->save($newLineItemMeta);

				

				$newLineItemMeta=$lineMetaTable->newEntity();

				$newLineItemMeta->quote_item_id=$newLineItem->id;

				$newLineItemMeta->meta_key='labor-billable';

				$newLineItemMeta->meta_value=$lf;

				$lineMetaTable->save($newLineItemMeta);

				$newLineItemMeta=$lineMetaTable->newEntity();

				$newLineItemMeta->quote_item_id=$newLineItem->id;

				$newLineItemMeta->meta_key='fabricid';

				$newLineItemMeta->meta_value=$fabricid;

				$lineMetaTable->save($newLineItemMeta);

				

				

				$newLineItemMeta=$lineMetaTable->newEntity();

				$newLineItemMeta->quote_item_id=$newLineItem->id;

				$newLineItemMeta->meta_key='length';

				$newLineItemMeta->meta_value=$length;

				$lineMetaTable->save($newLineItemMeta);

				

				

				$newLineItemMeta=$lineMetaTable->newEntity();

				$newLineItemMeta->quote_item_id=$newLineItem->id;

				$newLineItemMeta->meta_key='width';

				$newLineItemMeta->meta_value=$thisSize['width'];

				$lineMetaTable->save($newLineItemMeta);

				

				$newLineItemMeta=$lineMetaTable->newEntity();

				$newLineItemMeta->quote_item_id=$newLineItem->id;

				$newLineItemMeta->meta_key='fabric_name';

				$newLineItemMeta->meta_value=$fabricname;

				$lineMetaTable->save($newLineItemMeta);

				

				$newLineItemMeta=$lineMetaTable->newEntity();

				$newLineItemMeta->quote_item_id=$newLineItem->id;

				$newLineItemMeta->meta_key='usealias';

				$newLineItemMeta->meta_value=$usealias;

				$lineMetaTable->save($newLineItemMeta);

				if($mesh=='No Mesh'){

					$newLineItemMeta=$lineMetaTable->newEntity();

					$newLineItemMeta->quote_item_id=$newLineItem->id;

					$newLineItemMeta->meta_key='mesh-type';

					$newLineItemMeta->meta_value='None';

					$lineMetaTable->save($newLineItemMeta);

					$newLineItemMeta=$lineMetaTable->newEntity();

					$newLineItemMeta->quote_item_id=$newLineItem->id;

					$newLineItemMeta->meta_key='mesh-color';

					$newLineItemMeta->meta_value='None';

					$lineMetaTable->save($newLineItemMeta);

					$newLineItemMeta=$lineMetaTable->newEntity();

					$newLineItemMeta->quote_item_id=$newLineItem->id;

					$newLineItemMeta->meta_key='mesh';

					$newLineItemMeta->meta_value=0;

					$lineMetaTable->save($newLineItemMeta);

				}else{

					$newLineItemMeta=$lineMetaTable->newEntity();

					$newLineItemMeta->quote_item_id=$newLineItem->id;

					$newLineItemMeta->meta_key='mesh-type';

					$newLineItemMeta->meta_value='MOM Mesh';

					$lineMetaTable->save($newLineItemMeta);

					if(preg_match("#white#i",$mesh)){

						$meshcolor='White';

					}elseif(preg_match("#beige#i",$mesh)){

						$meshcolor="Beige";

					}

					$newLineItemMeta=$lineMetaTable->newEntity();

					$newLineItemMeta->quote_item_id=$newLineItem->id;

					$newLineItemMeta->meta_key='mesh-color';

					$newLineItemMeta->meta_value=$meshcolor;

					$lineMetaTable->save($newLineItemMeta);

					$exp=explode('"',$mesh);

					$meshlength=$exp[0];

					$newLineItemMeta=$lineMetaTable->newEntity();

					$newLineItemMeta->quote_item_id=$newLineItem->id;

					$newLineItemMeta->meta_key='mesh';

					$newLineItemMeta->meta_value=$meshlength;

					$lineMetaTable->save($newLineItemMeta);

				}

				$newLineItemMeta=$lineMetaTable->newEntity();

				$newLineItemMeta->quote_item_id=$newLineItem->id;

				$newLineItemMeta->meta_key='qty';

				$newLineItemMeta->meta_value=$qty;

				$lineMetaTable->save($newLineItemMeta);

			
/**PPSASSCRUM-29 start */	
$orderDetails = $this->Orders->find('all',['conditions'=>['quote_id'=>$quoteID]])->first();

if(isset($orderDetails) && !empty($orderDetails['id'])){
	$this->savetoOrderLineItemTables($newLineItem->id, $quoteID,$orderDetails['id'], $table='orders' );
	$this->savetoOrderLineItemTables($newLineItem->id, $quoteID,$orderDetails['id'], $table='work_orders' );	
}

/**PPSASSCRUM-29 end */
			if($this->recalculatequoteadjustments($quoteID)){

				echo "OK";exit;

			}

		}

				

	}

	

	

	public function addwttoquote($quoteID,$wttype,$fabricid,$fabricwithcolorid,$wtID,$wtSizeID,$qty,$priceEach,$weight,$diff,$yards,$haswelts=0,$lining,$pairorpanel,$laborwidths=0,$specialpricing,$lengthentered='no',$usealias='no',$shortpoint=false,$location=''){

        $thisQuote=$this->Quotes->get($quoteID)->toArray();

		$this->autoRender=false;

		$location=$this->cleanparameterreplacements($location);

		$wtData=$this->WindowTreatments->get($wtID)->toArray();

		$thisSize=$this->WindowTreatmentSizes->get($wtSizeID)->toArray();

		$thisFabric=$this->Fabrics->get($fabricwithcolorid)->toArray();

		

		$lineTable=TableRegistry::get('QuoteLineItems');

		$lineMetaTable=TableRegistry::get('QuoteLineItemMeta');

		$newLineItem=$lineTable->newEntity();

		$newLineItem->quote_id=$quoteID;

		$newLineItem->type='lineitem';

		$newLineItem->status='included';
	
		switch($wttype){
		    case 'Box Pleated Valance':
		        $newLineItem->product_class=3;
		        $newLineItem->product_subclass=9;
		    break;
		    case 'Pinch Pleated Drapery':
		        $newLineItem->product_class=3;
		        $newLineItem->product_subclass=10;
		    break;
		    case 'Shaped Cornice':
		    case 'Straight Cornice':
		        $newLineItem->product_class=3;
		        $newLineItem->product_subclass=11;
		    break;
		}
		

		$newLineItem->room_number = $location;

		$newLineItem->product_type='window_treatments';

		$newLineItem->product_id=$wtID;

		$newLineItem->title=$wtData['title'];

		$newLineItem->description = $thisSize['title'].', '.$thisFabric['color'];

        $newLineItem->override_active = 0;
        
		$newLineItem->best_price=$priceEach;

		//adjusted

		

		$newLineItem->qty=$qty;

		$newLineItem->lineitemtype='simple';

		

		$newLineItem->unit='each';

		

		//find new line number

		$newLineItem->line_number=$this->getnextlinenumber($quoteID);

		$newLineItem->sortorder=$this->getNewItemSortOrderNumber($quoteID);
        /**PPSA-40 start **/
        $newLineItem->created=time();
        /**PPSA-40 end **/
		

		if($lineTable->save($newLineItem)){

			$this->logActivity($_SERVER['REQUEST_URI'],'Added price list "'.$wtData['title'].'" as Line #'.$newLineItem->line_number.' to quote '.$thisQuote['quote_number'],$this->Auth->user('id'),json_encode($this->request));
				
            //if this is EDIT ORDER function, add a line to Quote Item Add Log so it will know what to do when applying changes
            if($thisQuote['status'] == 'editorder'){
                $newAddLog=$this->QuoteItemAddLog->newEntity();
                $newAddLog->original_quote_id = $thisQuote['parent_quote'];
                $newAddLog->revision_id=$thisQuote['id'];
                $newAddLog->line_item_id=$newLineItem->id;
                $newAddLog->addtime=time();
                $newAddLog->line_number=$newLineItem->line_number;
                $this->QuoteItemAddLog->save($newAddLog);
            }


			if($this->recalculatequoteadjustments($quoteID)){

				$this->updatequotemodifiedtime($quoteID);

				//meta data time

				$newLineItemMeta=$lineMetaTable->newEntity();

				$newLineItemMeta->quote_item_id=$newLineItem->id;

				$newLineItemMeta->meta_key='lineitemtype';

				$newLineItemMeta->meta_value='simpleproduct';

				$lineMetaTable->save($newLineItemMeta);

				

				$newLineItemMeta=$lineMetaTable->newEntity();

				$newLineItemMeta->quote_item_id=$newLineItem->id;

				$newLineItemMeta->meta_key='wttype';

				$newLineItemMeta->meta_value=$wttype;

				$lineMetaTable->save($newLineItemMeta);

				

				

				

				$newLineItemMeta=$lineMetaTable->newEntity();

				$newLineItemMeta->quote_item_id=$newLineItem->id;

				$newLineItemMeta->meta_key='specialpricing';

				$newLineItemMeta->meta_value=$specialpricing;

				$lineMetaTable->save($newLineItemMeta);

				

				

				

				$newLineItemMeta=$lineMetaTable->newEntity();

				$newLineItemMeta->quote_item_id=$newLineItem->id;

				$newLineItemMeta->meta_key='price';

				$newLineItemMeta->meta_value=$priceEach;

				$lineMetaTable->save($newLineItemMeta);

				

				

				$newLineItemMeta=$lineMetaTable->newEntity();

				$newLineItemMeta->quote_item_id=$newLineItem->id;

				$newLineItemMeta->meta_key='fabricid';

				$newLineItemMeta->meta_value=$fabricwithcolorid;

				$lineMetaTable->save($newLineItemMeta);

				

				

				

				$newLineItemMeta=$lineMetaTable->newEntity();

				$newLineItemMeta->quote_item_id=$newLineItem->id;

				$newLineItemMeta->meta_key='usealias';

				$newLineItemMeta->meta_value=$usealias;

				$lineMetaTable->save($newLineItemMeta);

				

				

				$newLineItemMeta=$lineMetaTable->newEntity();

				$newLineItemMeta->quote_item_id=$newLineItem->id;

				$newLineItemMeta->meta_key='fabric_name';

				$newLineItemMeta->meta_value=$thisFabric['fabric_name'];

				$lineMetaTable->save($newLineItemMeta);

				if(preg_match("#Cornice#i",$wttype)){

					$newLineItemMeta=$lineMetaTable->newEntity();

					$newLineItemMeta->quote_item_id=$newLineItem->id;

					$newLineItemMeta->meta_key='welt-top';

					$newLineItemMeta->meta_value=$haswelts;

					$lineMetaTable->save($newLineItemMeta);

					$newLineItemMeta=$lineMetaTable->newEntity();

					$newLineItemMeta->quote_item_id=$newLineItem->id;

					$newLineItemMeta->meta_key='welt-bottom';

					$newLineItemMeta->meta_value=$haswelts;

					$lineMetaTable->save($newLineItemMeta);

				}

				

				//SIZE DATA

				$newLineItemMeta=$lineMetaTable->newEntity();

				$newLineItemMeta->quote_item_id=$newLineItem->id;

				if($wttype=='Pinch Pleated Drapery'){

					$newLineItemMeta->meta_key='rod-width';

				}else{

					$newLineItemMeta->meta_key='width';

				}

				$newLineItemMeta->meta_value=number_format($thisSize['width'],0,'','');

				$lineMetaTable->save($newLineItemMeta);

				

				

				if($lengthentered != 'no'){

					$newLineItemMeta=$lineMetaTable->newEntity();

					$newLineItemMeta->quote_item_id=$newLineItem->id;

					$newLineItemMeta->meta_key='length';

					$newLineItemMeta->meta_value=$lengthentered;

					$lineMetaTable->save($newLineItemMeta);

				}else{

					$newLineItemMeta=$lineMetaTable->newEntity();

					$newLineItemMeta->quote_item_id=$newLineItem->id;

					$newLineItemMeta->meta_key='length';

					$newLineItemMeta->meta_value=number_format($thisSize['length'],0,'','');

					$lineMetaTable->save($newLineItemMeta);

				}

				

				if($shortpoint){

					$newLineItemMeta=$lineMetaTable->newEntity();

					$newLineItemMeta->quote_item_id=$newLineItem->id;

					$newLineItemMeta->meta_key='fl-short';

					$newLineItemMeta->meta_value=number_format($shortpoint,0,'','');

					$lineMetaTable->save($newLineItemMeta);

				}

				

				$newLineItemMeta=$lineMetaTable->newEntity();

				$newLineItemMeta->quote_item_id=$newLineItem->id;

				$newLineItemMeta->meta_key='wt_calculated_weight';

				$newLineItemMeta->meta_value=$weight;

				$lineMetaTable->save($newLineItemMeta);

				

				$newLineItemMeta=$lineMetaTable->newEntity();

				$newLineItemMeta->quote_item_id=$newLineItem->id;

				$newLineItemMeta->meta_key='difficulty-rating';

				$newLineItemMeta->meta_value=$diff;

				$lineMetaTable->save($newLineItemMeta);

				

				$newLineItemMeta=$lineMetaTable->newEntity();

				$newLineItemMeta->quote_item_id=$newLineItem->id;

				$newLineItemMeta->meta_key='yds-per-unit';

				$newLineItemMeta->meta_value=$yards;

				$lineMetaTable->save($newLineItemMeta);

				$newLineItemMeta=$lineMetaTable->newEntity();

				$newLineItemMeta->quote_item_id=$newLineItem->id;

				$newLineItemMeta->meta_key='total-yds';

				$newLineItemMeta->meta_value=(floatval($yards)*floatval($qty));

				$lineMetaTable->save($newLineItemMeta);

				$newLineItemMeta=$lineMetaTable->newEntity();

				$newLineItemMeta->quote_item_id=$newLineItem->id;

				$newLineItemMeta->meta_key='qty';

				$newLineItemMeta->meta_value=$qty;

				$lineMetaTable->save($newLineItemMeta);

				if($wttype=='Shaped Cornice'){

					$newLineItemMeta=$lineMetaTable->newEntity();

					$newLineItemMeta->quote_item_id=$newLineItem->id;

					$newLineItemMeta->meta_key='libraryimageid';

					$newLineItemMeta->meta_value='34';

					$lineMetaTable->save($newLineItemMeta);

				}

				if($wttype == 'Straight Cornice'){

					$newLineItemMeta=$lineMetaTable->newEntity();

					$newLineItemMeta->quote_item_id=$newLineItem->id;

					$newLineItemMeta->meta_key='libraryimageid';

					$newLineItemMeta->meta_value='33';

					$lineMetaTable->save($newLineItemMeta);

				}

				if($wttype == 'Box Pleated Valance'){

					$newLineItemMeta=$lineMetaTable->newEntity();

					$newLineItemMeta->quote_item_id=$newLineItem->id;

					$newLineItemMeta->meta_key='libraryimageid';

					$newLineItemMeta->meta_value='38';

					$lineMetaTable->save($newLineItemMeta);

				}

				if($wttype == 'Tailored Valance'){

					$newLineItemMeta=$lineMetaTable->newEntity();

					$newLineItemMeta->quote_item_id=$newLineItem->id;

					$newLineItemMeta->meta_key='libraryimageid';

					$newLineItemMeta->meta_value='39';

					$lineMetaTable->save($newLineItemMeta);

				}

				if($wttype=='Pinch Pleated Drapery'){

					$newLineItemMeta=$lineMetaTable->newEntity();

					$newLineItemMeta->quote_item_id=$newLineItem->id;

					$newLineItemMeta->meta_key='libraryimageid';

					$newLineItemMeta->meta_value='40';

					$lineMetaTable->save($newLineItemMeta);	

				}

				if(preg_match("#Cornice#i",$wttype)){

					$newLineItemMeta=$lineMetaTable->newEntity();

					$newLineItemMeta->quote_item_id=$newLineItem->id;

					$newLineItemMeta->meta_key='linings_id';

					$newLineItemMeta->meta_value=2;

					$lineMetaTable->save($newLineItemMeta);

					$newLineItemMeta=$lineMetaTable->newEntity();

					$newLineItemMeta->quote_item_id=$newLineItem->id;

					$newLineItemMeta->meta_key='yds-of-lining';

					$newLineItemMeta->meta_value=$yards;

					$lineMetaTable->save($newLineItemMeta);

					

					$newLineItemMeta=$lineMetaTable->newEntity();

					$newLineItemMeta->quote_item_id=$newLineItem->id;

					$newLineItemMeta->meta_key='return';

					$newLineItemMeta->meta_value=3;

					$lineMetaTable->save($newLineItemMeta);

					$newLineItemMeta=$lineMetaTable->newEntity();

					$newLineItemMeta->quote_item_id=$newLineItem->id;

					$newLineItemMeta->meta_key='labor-billable';

					$newLineItemMeta->meta_value=$laborwidths;

					$lineMetaTable->save($newLineItemMeta);

				}

				if(preg_match("#Valance#i",$wttype)){

					$newLineItemMeta=$lineMetaTable->newEntity();

					$newLineItemMeta->quote_item_id=$newLineItem->id;

					$newLineItemMeta->meta_key='linings_id';

					$newLineItemMeta->meta_value=3;

					$lineMetaTable->save($newLineItemMeta);

					$newLineItemMeta=$lineMetaTable->newEntity();

					$newLineItemMeta->quote_item_id=$newLineItem->id;

					$newLineItemMeta->meta_key='yds-of-lining';

					$newLineItemMeta->meta_value=$yards;

					$lineMetaTable->save($newLineItemMeta);

					$newLineItemMeta=$lineMetaTable->newEntity();

					$newLineItemMeta->quote_item_id=$newLineItem->id;

					$newLineItemMeta->meta_key='return';

					$newLineItemMeta->meta_value=3;

					$lineMetaTable->save($newLineItemMeta);

					$newLineItemMeta=$lineMetaTable->newEntity();

					$newLineItemMeta->quote_item_id=$newLineItem->id;

					$newLineItemMeta->meta_key='labor-billable';

					$newLineItemMeta->meta_value=$laborwidths;

					$lineMetaTable->save($newLineItemMeta);

				}

				if($wttype=='Tailored Valance'){

					$newLineItemMeta=$lineMetaTable->newEntity();

					$newLineItemMeta->quote_item_id=$newLineItem->id;

					$newLineItemMeta->meta_key='pleats';

					$newLineItemMeta->meta_value=3;

					$lineMetaTable->save($newLineItemMeta);

				}

				if($wttype=='Box Pleated Valance'){

					$pleats=0;

					switch($thisSize['width']){

						case 36:

							$pleats=3;

						break;

						case 48:

							$pleats=4;

						break;

						case 60:

							$pleats=5;

						break;

						case 72:

						case 84:

							$pleats=6;

						break;

						case 96:

							$pleats=7;

						break;

						case 108:

						case 120:

							$pleats=8;

						break;

						case 132:

						case 144:

							$pleats=9;

						break;

					}

					$newLineItemMeta=$lineMetaTable->newEntity();

					$newLineItemMeta->quote_item_id=$newLineItem->id;

					$newLineItemMeta->meta_key='pleats';

					$newLineItemMeta->meta_value=$pleats;

					$lineMetaTable->save($newLineItemMeta);

				}

				if($wttype=='Pinch Pleated Drapery'){

					$newLineItemMeta=$lineMetaTable->newEntity();

					$newLineItemMeta->quote_item_id=$newLineItem->id;

					$newLineItemMeta->meta_key='labor-widths';

					$newLineItemMeta->meta_value=$laborwidths;

					$lineMetaTable->save($newLineItemMeta);

					$newLineItemMeta=$lineMetaTable->newEntity();

					$newLineItemMeta->quote_item_id=$newLineItem->id;

					$newLineItemMeta->meta_key='unit-of-measure';

					$newLineItemMeta->meta_value=$pairorpanel;

					$lineMetaTable->save($newLineItemMeta);

					$newLineItemMeta=$lineMetaTable->newEntity();

					$newLineItemMeta->quote_item_id=$newLineItem->id;

					$newLineItemMeta->meta_key='hardware';

					$newLineItemMeta->meta_value='No Hardware';

					$lineMetaTable->save($newLineItemMeta);

					$newLineItemMeta=$lineMetaTable->newEntity();

					$newLineItemMeta->quote_item_id=$newLineItem->id;

					$newLineItemMeta->meta_key='default-overlap';

					$newLineItemMeta->meta_value='3.5';

					$lineMetaTable->save($newLineItemMeta);

					$newLineItemMeta=$lineMetaTable->newEntity();

					$newLineItemMeta->quote_item_id=$newLineItem->id;

					$newLineItemMeta->meta_key='default-return';

					$newLineItemMeta->meta_value='3.5';

					$lineMetaTable->save($newLineItemMeta);

					

					$newLineItemMeta=$lineMetaTable->newEntity();

					$newLineItemMeta->quote_item_id=$newLineItem->id;

					$newLineItemMeta->meta_key='fullness';

					$newLineItemMeta->meta_value='2';

					$lineMetaTable->save($newLineItemMeta);

					$newLineItemMeta=$lineMetaTable->newEntity();

					$newLineItemMeta->quote_item_id=$newLineItem->id;

					$newLineItemMeta->meta_key='weights';

					$newLineItemMeta->meta_value='1';

					$lineMetaTable->save($newLineItemMeta);

					$newLineItemMeta=$lineMetaTable->newEntity();

					$newLineItemMeta->quote_item_id=$newLineItem->id;

					$newLineItemMeta->meta_key='weight-count';

					$newLineItemMeta->meta_value='2';

					$lineMetaTable->save($newLineItemMeta);

					$newLineItemMeta=$lineMetaTable->newEntity();

					$newLineItemMeta->quote_item_id=$newLineItem->id;

					$newLineItemMeta->meta_key='pinset';

					$newLineItemMeta->meta_value='1.5';

					$lineMetaTable->save($newLineItemMeta);

					switch($lining){

						case "BO Lining":

							$newLineItemMeta=$lineMetaTable->newEntity();

							$newLineItemMeta->quote_item_id=$newLineItem->id;

							$newLineItemMeta->meta_key='linings_id';

							$newLineItemMeta->meta_value=3;

							$lineMetaTable->save($newLineItemMeta);

							$newLineItemMeta=$lineMetaTable->newEntity();

							$newLineItemMeta->quote_item_id=$newLineItem->id;

							$newLineItemMeta->meta_key='yds-of-lining';

							$newLineItemMeta->meta_value=$yards;

							$lineMetaTable->save($newLineItemMeta);

						break;

						case "FR Lining":

							$newLineItemMeta=$lineMetaTable->newEntity();

							$newLineItemMeta->quote_item_id=$newLineItem->id;

							$newLineItemMeta->meta_key='linings_id';

							$newLineItemMeta->meta_value=1;

							$lineMetaTable->save($newLineItemMeta);

							$newLineItemMeta=$lineMetaTable->newEntity();

							$newLineItemMeta->quote_item_id=$newLineItem->id;

							$newLineItemMeta->meta_key='yds-of-lining';

							$newLineItemMeta->meta_value=$yards;

							$lineMetaTable->save($newLineItemMeta);
/**PPSASSCRUM-29 start */	
$orderDetails = $this->Orders->find('all',['conditions'=>['quote_id'=>$quoteID]])->first();

if(isset($orderDetails) && !empty($orderDetails['id'])){
	$this->savetoOrderLineItemTables($newLineItem->id, $quoteID,$orderDetails['id'], $table='orders' );
	$this->savetoOrderLineItemTables($newLineItem->id, $quoteID,$orderDetails['id'], $table='work_orders' );	
}

/**PPSASSCRUM-29 end */
						break;

					}

				}

				

			}

		}

		

		echo "OK";exit;

		

	}

	

	

	public function addbstoquote($quoteID,$fabricid,$fabricwithcolorid,$bsID,$bsSize,$qty,$priceEach,$weight,$diff,$yards,$quilted=0,$mattress='36"',$topwidthsl='',$dropwidthsl='',$quiltpattern='',$topcutsw='',$dropcutsw='',$repeat='',$usealias='no',$specialpricing,$layout=1.33,$location=''){

        $thisQuote=$this->Quotes->get($quoteID)->toArray();

		$this->autoRender=false;

		$location=$this->cleanparameterreplacements($location);

		$settings=$this->getsettingsarray();

		$bsData=$this->Bedspreads->get($bsID)->toArray();

		$thisSize=$this->BedspreadSizes->get($bsSize)->toArray();

		$thisFabric=$this->Fabrics->get($fabricwithcolorid)->toArray();

		

		$lineTable=TableRegistry::get('QuoteLineItems');

		$lineMetaTable=TableRegistry::get('QuoteLineItemMeta');

		$newLineItem=$lineTable->newEntity();

		$newLineItem->quote_id=$quoteID;

		$newLineItem->type='lineitem';

		$newLineItem->status='included';
		
		$newLineItem->product_class=5;
		$newLineItem->product_subclass=16;

		$newLineItem->room_number = $location;

		$newLineItem->product_type='bedspreads';

		$newLineItem->product_id=$bsID;

		$newLineItem->title=$bsData['title'];

		$newLineItem->description = $thisSize['title'].', '.$thisFabric['color'];
		
		$newLineItem->override_active = 0;

		$newLineItem->best_price=$priceEach;

		//adjusted

		

		$newLineItem->qty=$qty;

		$newLineItem->lineitemtype='simple';

		$newLineItem->unit='each';

		

		//find new line number

		$newLineItem->line_number=$this->getnextlinenumber($quoteID);

		$newLineItem->sortorder=$this->getNewItemSortOrderNumber($quoteID);
        
        /**PPSA-40 start **/
        $newLineItem->created=time();
        /**PPSA-40 end **/
		

		if($lineTable->save($newLineItem)){
		    
		   
		    //if this is EDIT ORDER function, add a line to Quote Item Add Log so it will know what to do when applying changes
            if($thisQuote['status'] == 'editorder'){
                $newAddLog=$this->QuoteItemAddLog->newEntity();
                $newAddLog->original_quote_id = $thisQuote['parent_quote'];
                $newAddLog->revision_id=$thisQuote['id'];
                $newAddLog->line_item_id=$newLineItem->id;
                $newAddLog->addtime=time();
                $newAddLog->line_number=$newLineItem->line_number;
                $this->QuoteItemAddLog->save($newAddLog);
            }
            

			$this->logActivity($_SERVER['REQUEST_URI'],'Added pricelist "'.$bsData['title'].'" as Line #'.$newLineItem->line_number.' to quote '.$thisQuote['quote_number'],$this->Auth->user('id'),json_encode($this->request));

			$newLineItemMeta=$lineMetaTable->newEntity();

			$newLineItemMeta->quote_item_id=$newLineItem->id;

			$newLineItemMeta->meta_key='specialpricing';

			$newLineItemMeta->meta_value=$specialpricing;

			$lineMetaTable->save($newLineItemMeta);
			 /**PPSASSCRUM-29 start */	
$orderDetails = $this->Orders->find('all',['conditions'=>['quote_id'=>$quoteID]])->first();
$this->logActivity($_SERVER['REQUEST_URI'],$orderDetails->id ."=====".$bsData['title'].'" as Line #'.$newLineItem->line_number.' to quote '.$thisQuote['quote_number'],$this->Auth->user('id'),json_encode($orderDetails));

if(isset($orderDetails) && !empty($orderDetails['id'])){
	$this->logActivity($_SERVER['REQUEST_URI'],$orderDetails['id'] ."=====".$bsData['title'].'" as Line #'.$newLineItem->line_number.' to quote '.$thisQuote['quote_number'],$this->Auth->user('id'),json_encode($this->request));

	$this->savetoOrderLineItemTables($newLineItem->id, $quoteID,$orderDetails['id'], $table='orders' );
	$this->savetoOrderLineItemTables($newLineItem->id, $quoteID,$orderDetails['id'], $table='work_orders' );	

}else {
	$this->logActivity($_SERVER['REQUEST_URI'],'Added pricelist Not inserted into the work_orders tables "'.$bsData['title'].'" as Line #'.$newLineItem->line_number.' to quote '.$thisQuote['quote_number'],$this->Auth->user('id'),json_encode($this->request));

}

/**PPSASSCRUM-29 end */

			if($this->recalculatequoteadjustments($quoteID)){

				$this->updatequotemodifiedtime($quoteID);

				//meta data time

				$newLineItemMeta=$lineMetaTable->newEntity();

				$newLineItemMeta->quote_item_id=$newLineItem->id;

				$newLineItemMeta->meta_key='lineitemtype';

				$newLineItemMeta->meta_value='simpleproduct';

				$lineMetaTable->save($newLineItemMeta);

				

				

				$newLineItemMeta=$lineMetaTable->newEntity();

				$newLineItemMeta->quote_item_id=$newLineItem->id;

				$newLineItemMeta->meta_key='price';

				$newLineItemMeta->meta_value=$priceEach;

				$lineMetaTable->save($newLineItemMeta);

				

				$newLineItemMeta=$lineMetaTable->newEntity();

				$newLineItemMeta->quote_item_id=$newLineItem->id;

				$newLineItemMeta->meta_key='fabricid';

				$newLineItemMeta->meta_value=$fabricwithcolorid;

				$lineMetaTable->save($newLineItemMeta);

				

				

				$newLineItemMeta=$lineMetaTable->newEntity();

				$newLineItemMeta->quote_item_id=$newLineItem->id;

				$newLineItemMeta->meta_key='fabric_name';

				$newLineItemMeta->meta_value=$thisFabric['fabric_name'];

				$lineMetaTable->save($newLineItemMeta);

				

				$newLineItemMeta=$lineMetaTable->newEntity();

				$newLineItemMeta->quote_item_id=$newLineItem->id;

				$newLineItemMeta->meta_key='usealias';

				$newLineItemMeta->meta_value=$usealias;

				$lineMetaTable->save($newLineItemMeta);

				

				$newLineItemMeta=$lineMetaTable->newEntity();

				$newLineItemMeta->quote_item_id=$newLineItem->id;

				$newLineItemMeta->meta_key='railroaded';

				$newLineItemMeta->meta_value=$thisFabric['railroaded'];

				$lineMetaTable->save($newLineItemMeta);

				$newLineItemMeta=$lineMetaTable->newEntity();

				$newLineItemMeta->quote_item_id=$newLineItem->id;

				$newLineItemMeta->meta_key='fabric-width';

				$newLineItemMeta->meta_value=$thisFabric['fabric_width'];

				$lineMetaTable->save($newLineItemMeta);

				$newLineItemMeta=$lineMetaTable->newEntity();

				$newLineItemMeta->quote_item_id=$newLineItem->id;

				$newLineItemMeta->meta_key='vertical-repeat';

				$newLineItemMeta->meta_value=$repeat;

				$lineMetaTable->save($newLineItemMeta);

				$newLineItemMeta=$lineMetaTable->newEntity();

				$newLineItemMeta->quote_item_id=$newLineItem->id;

				$newLineItemMeta->meta_key='extra-inches-seam-hems';

				$newLineItemMeta->meta_value='1';

				$lineMetaTable->save($newLineItemMeta);

				

				

				$newLineItemMeta=$lineMetaTable->newEntity();

				$newLineItemMeta->quote_item_id=$newLineItem->id;

				$newLineItemMeta->meta_key='custom-top-width-mattress-w';

				$newLineItemMeta->meta_value=str_replace('"','',$mattress);

				$lineMetaTable->save($newLineItemMeta);

				

				

				$newLineItemMeta=$lineMetaTable->newEntity();

				$newLineItemMeta->quote_item_id=$newLineItem->id;

				$newLineItemMeta->meta_key='quilted';

				$newLineItemMeta->meta_value=$quilted;

				$lineMetaTable->save($newLineItemMeta);

				

				//SIZE DATA

				$newLineItemMeta=$lineMetaTable->newEntity();

				$newLineItemMeta->quote_item_id=$newLineItem->id;

				$newLineItemMeta->meta_key='width';

				$newLineItemMeta->meta_value=number_format($thisSize['width'],0,'','');

				$lineMetaTable->save($newLineItemMeta);

				

				

				$newLineItemMeta=$lineMetaTable->newEntity();

				$newLineItemMeta->quote_item_id=$newLineItem->id;

				$newLineItemMeta->meta_key='length';

				$newLineItemMeta->meta_value=number_format($thisSize['length'],0,'','');

				$lineMetaTable->save($newLineItemMeta);

				$newLineItemMeta=$lineMetaTable->newEntity();

				$newLineItemMeta->quote_item_id=$newLineItem->id;

				$newLineItemMeta->meta_key='layout';

				$newLineItemMeta->meta_value=$layout;

				$lineMetaTable->save($newLineItemMeta);

				

				$newLineItemMeta=$lineMetaTable->newEntity();

				$newLineItemMeta->quote_item_id=$newLineItem->id;

				$newLineItemMeta->meta_key='bs_calculated_weight';

				$newLineItemMeta->meta_value=$weight;

				$lineMetaTable->save($newLineItemMeta);

				

				$newLineItemMeta=$lineMetaTable->newEntity();

				$newLineItemMeta->quote_item_id=$newLineItem->id;

				$newLineItemMeta->meta_key='difficulty-rating';

				$newLineItemMeta->meta_value=$diff;

				$lineMetaTable->save($newLineItemMeta);

				$newLineItemMeta=$lineMetaTable->newEntity();

				$newLineItemMeta->quote_item_id=$newLineItem->id;

				$newLineItemMeta->meta_key='top-widths';

				$newLineItemMeta->meta_value=$topwidthsl;

				$lineMetaTable->save($newLineItemMeta);

				$newLineItemMeta=$lineMetaTable->newEntity();

				$newLineItemMeta->quote_item_id=$newLineItem->id;

				$newLineItemMeta->meta_key='drop-widths';

				$newLineItemMeta->meta_value=$dropwidthsl;

				$lineMetaTable->save($newLineItemMeta);

				

				$newLineItemMeta=$lineMetaTable->newEntity();

				$newLineItemMeta->quote_item_id=$newLineItem->id;

				$newLineItemMeta->meta_key='quilting-pattern';

				$newLineItemMeta->meta_value=$quiltpattern;

				$lineMetaTable->save($newLineItemMeta);

				$newLineItemMeta=$lineMetaTable->newEntity();

				$newLineItemMeta->quote_item_id=$newLineItem->id;

				$newLineItemMeta->meta_key='top-cut';

				$newLineItemMeta->meta_value=$topcutsw;

				$lineMetaTable->save($newLineItemMeta);

				$newLineItemMeta=$lineMetaTable->newEntity();

				$newLineItemMeta->quote_item_id=$newLineItem->id;

				$newLineItemMeta->meta_key='drop-cut';

				$newLineItemMeta->meta_value=$dropcutsw;

				$lineMetaTable->save($newLineItemMeta);

				$newLineItemMeta=$lineMetaTable->newEntity();

				$newLineItemMeta->quote_item_id=$newLineItem->id;

				$newLineItemMeta->meta_key='yds-per-unit';

				$newLineItemMeta->meta_value=$yards;

				$lineMetaTable->save($newLineItemMeta);

				$newLineItemMeta=$lineMetaTable->newEntity();

				$newLineItemMeta->quote_item_id=$newLineItem->id;

				$newLineItemMeta->meta_key='qty';

				$newLineItemMeta->meta_value=$qty;

				$lineMetaTable->save($newLineItemMeta);

				$newLineItemMeta=$lineMetaTable->newEntity();

				$newLineItemMeta->quote_item_id=$newLineItem->id;

				$newLineItemMeta->meta_key='style';

				$newLineItemMeta->meta_value=$thisSize['title'];

				$lineMetaTable->save($newLineItemMeta);

				if(preg_match("#Fitted#i",$thisSize['title'])){

					$newLineItemMeta=$lineMetaTable->newEntity();

					$newLineItemMeta->quote_item_id=$newLineItem->id;

					$newLineItemMeta->meta_key='libraryimageid';

					$newLineItemMeta->meta_value='28';

					$lineMetaTable->save($newLineItemMeta);

				}elseif(preg_match("#Throw#i",$thisSize['title'])){

					$newLineItemMeta=$lineMetaTable->newEntity();

					$newLineItemMeta->quote_item_id=$newLineItem->id;

					$newLineItemMeta->meta_key='libraryimageid';

					$newLineItemMeta->meta_value='29';

					$lineMetaTable->save($newLineItemMeta);

				}

			}

		}

		

		echo "OK";exit;

		

	}

	

		

	

	public function addsimpleproducttoquote($quoteID,$productid,$categoryID,$qty=1,$variationid=0,$description=''){

		$this->autoRender=false;

		

		$productData=$this->Products->get($productid)->toArray();

		if($variationid >0){

			$variationdata=$this->ProductVariations->get($variationid)->toArray();

		}
    
        $thisQuote=$this->Quotes->get($quoteID)->toArray();
		

		$lineTable=TableRegistry::get('QuoteLineItems');

		$lineMetaTable=TableRegistry::get('QuoteLineItemMeta');

		$newLineItem=$lineTable->newEntity();

		$newLineItem->quote_id=$quoteID;

		$newLineItem->status='included';

		$newLineItem->product_id=$productid;

		$newLineItem->title=$productData['product_name'];

		if($variationid >0){

			$bestprice=$variationdata['price'];

			$newLineItem->best_price=$bestprice;

		}else{

			$bestprice=$productData['price'];

			$newLineItem->best_price=$bestprice;

		}

		$newLineItem->lineitemtype='simple';

		$newLineItem->description=$description;
		
		$newLineItem->override_active = 0;

		$newLineItem->line_number=$this->getnextlinenumber($quoteID);

		//$newLineItem->description=;

		$newLineItem->qty=$qty;
		
		/**PPSA-40 start **/
        $newLineItem->created=time();
        /**PPSA-40 end **/

		if($lineTable->save($newLineItem)){
/**PPSASSCRUM-29 start */	
$orderDetails = $this->Orders->find('all',['conditions'=>['quote_id'=>$quoteID]])->first();

if(isset($orderDetails) && !empty($orderDetails['id'])){
	$this->savetoOrderLineItemTables($newLineItem->id, $quoteID,$orderDetails['id'], $table='orders' );
	$this->savetoOrderLineItemTables($newLineItem->id, $quoteID,$orderDetails['id'], $table='work_orders' );	
}

/**PPSASSCRUM-29 end */
				
			$this->logActivity($_SERVER['REQUEST_URI'],'Added item "'.$productData['product_name'].'" as Line #'.$newLineItem->line_number.' to quote '.$thisQuote['quote_number'],$this->Auth->user('id'),json_encode($this->request));
				

			if($this->recalculatequoteadjustments($quoteID)){

				$this->updatequotemodifiedtime($quoteID);

				

				$newLineItemMeta=$lineMetaTable->newEntity();

				$newLineItemMeta->quote_item_id=$newLineItem->id;

				$newLineItemMeta->meta_key='lineitemtype';

				$newLineItemMeta->meta_value='simpleproduct';

				$lineMetaTable->save($newLineItemMeta);

				

				$newLineItemMeta=$lineMetaTable->newEntity();

				$newLineItemMeta->quote_item_id=$newLineItem->id;

				$newLineItemMeta->meta_key='variation_id';

				$newLineItemMeta->meta_value=$variationid;

				$lineMetaTable->save($newLineItemMeta);

				

				$newLineItemMeta=$lineMetaTable->newEntity();

				$newLineItemMeta->quote_item_id=$newLineItem->id;

				$newLineItemMeta->meta_key='price';

				if($variationid >0){

					$newLineItemMeta->meta_value=$variationdata['price'];

				}else{

					$newLineItemMeta->meta_value=$productData['price'];

				}

				$lineMetaTable->save($newLineItemMeta);

			}

			

		}

		

		echo "OK";exit;

		

	}

	
	public function checkorderchangeruleallowed($orderID,$newQuoteID){
	    $this->autoRender=false;
	    $ruleCheckResult=$this->orderchangerulecheck($orderID,$newquoteid);
	    
	    if(is_array($ruleCheckResult) && count($ruleCheckResult) >0){
	        echo json_encode($ruleCheckResult);
	    }else{
	        echo "OK";
	    }
	    exit;
	}
	
	
	
	
	public function overwriteorderfromedit($orderID,$oldQuoteID,$newquoteid){
	    
	    $ruleCheckResult=$this->orderchangerulecheck($orderID,$newquoteid);
	    
	    if(is_array($ruleCheckResult) && count($ruleCheckResult) >0){
	        echo "<pre>"; print_r($ruleCheckResult); echo "</pre>"; exit;
	        
	    }else{
	    
    	    $this->autoRender=false;
    	    
    	    $quotesTable=TableRegistry::get('Quotes');
    	    $ordersTable=TableRegistry::get('Orders');
			/**PPSASSCRUM-29 start */
			$workordersTable=TableRegistry::get('WorkOrders');
			/** PPSASCRUM-29 end */
    	    
    	    $orderItemTable=TableRegistry::get('OrderItems');
    	    $orderItemStatusTable=TableRegistry::get('OrderItemStatus');
			/**PPSASSCRUM-29 start */
			$workorderItemTable=TableRegistry::get('WorkOrderItems');
			$workorderItemStatusTable=TableRegistry::get('WorkOrderItemStatus');
			/** PPSASCRUM-29 end */
			
    	    
    	    $oldLineItems=$this->QuoteLineItems->find('all',['conditions' => ['quote_id' => $oldQuoteID],'order' => ['line_number' => 'asc']])->toArray();
    	    $newLineItems=$this->QuoteLineItems->find('all',['conditions' => ['quote_id' => $newquoteid],'order' => ['line_number' => 'asc']])->toArray();
    	    
    	    
    	    $changes=array();
    	    $deletions=array();
    	    $additions=array();
			/**PPSASCRUM-29 start */
			$wdeletions =array();
						/**PPSASCRUM-29 end */

    	        
    	        
    	    foreach($oldLineItems as $oldLineItem){
    	        
    	        
    	        
    	        //determine if this line was DELETED
    	        $deletecheck=$this->QuoteItemDeleteLog->find('all',['conditions' => ['quote_line_item_id' => $oldLineItem['id']]])->toArray();
    	        if(count($deletecheck) > 0){
    	            //it has been deleted! delete this OrderItem entry
    	            $deletedOrderItemLookup=$this->OrderItems->find('all',['conditions' => ['quote_line_item_id' => $oldLineItem['id']]])->toArray();
    	            foreach($deletedOrderItemLookup as $deletedOrderItem){
    	                $thisOrderItem=$orderItemTable->get($deletedOrderItem['id']);
    	                if($orderItemTable->delete($thisOrderItem)){
    	                    $deletions[]=$deletedOrderItem['id'];
    	                    
    	                    //delete the orderitemstatus for this item
    	                    $orderItemStatusRowsForThisItem=$this->OrderItemStatus->find('all',['conditions' => ['order_item_id' => $deletedOrderItem['id']]])->toArray();
    	                    foreach($orderItemStatusRowsForThisItem as $orderItemStatusRow){
    	                        $thisorderitemstatusentry=$orderItemStatusTable->get($orderItemStatusRow['id']);
    	                        $orderItemStatusTable->delete($thisorderitemstatusentry);
    	                    }
    	                }
    	            }

					/**PPSASSCRUM-29 start  */
					//it has been deleted! delete this OrderItem entry
    	            $deletedWorkOrderItemLookup=$this->WorkOrderItems->find('all',['conditions' => ['quote_line_item_id' => $oldLineItem['id']]])->toArray();
    	            foreach($deletedWorkOrderItemLookup as $deletedWorkOrderItem){
    	                $thisWorkOrderItem=$workorderItemTable->get($deletedWorkOrderItem['id']);
    	                if($workorderItemTable->delete($thisWorkOrderItem)){
    	                    $wdeletions[]=$deletedWorkOrderItem['id'];
    	                    
    	                    //delete the orderitemstatus for this item
    	                    $workorderItemStatusRowsForThisItem=$this->WorkOrderItemStatus->find('all',['conditions' => ['order_item_id' => $deletedWorkOrderItem['id']]])->toArray();
    	                    foreach($workorderItemStatusRowsForThisItem as $workorderItemStatusRow){
    	                        $thisworkorderitemstatusentry=$workorderItemStatusTable->get($workorderItemStatusRow['id']);
    	                        $workorderItemStatusTable->delete($thisworkorderitemstatusentry);
    	                    }
    	                }
    	            }
					/**PPSASCRUM-29 end */
    	            
    	        }else{
    	            //item has not been deleted... now let's decide if this item has CHANGED at all from the Old quote data
    	            
        	        $oldlinemetas=array();
        	        $oldlinemetalookup=$this->QuoteLineItemMeta->find('all',['conditions' => ['quote_item_id' => $oldLineItem['id']]])->toArray();
        	        foreach($oldlinemetalookup as $oldlineitemmetarow){
        	            $oldlinemetas[$oldlineitemmetarow['meta_key']]=$oldlineitemmetarow['meta_value'];
        	        }
        	        
        	        $thisNewQuoteLineItem=$this->QuoteLineItems->find('all',['conditions' => ['revised_from_line' => $oldLineItem['id']]])->toArray();
        	        
        	        foreach($thisNewQuoteLineItem as $newLineItem){
        	           
        	           $changesThisItem=0;
        	           
        	           if(($newLineItem['lineitemtype'] != $oldLineItem['lineitemtype'])  || ($newLineItem['product_type'] != $oldLineItem['product_type'])){
        	               $changesThisItem++;
        	           }
        	           
        	           if($newLineItem['qty'] != $oldLineItem['qty']){
        	               $changesThisItem++;
        	               
        	               
        	               
        	           }
        	           
        	           if($newLineItem['extended_price'] != $oldLineItem['extended_price']){
        	               $changesItem++;
        	           }
        	           
        	           $newlinemetas=array();
            	       $newlinemetalookup=$this->QuoteLineItemMeta->find('all',['conditions' => ['quote_item_id' => $newLineItem['id']]])->toArray();
                	   foreach($newlinemetalookup as $newlineitemmetarow){
                	        $newlinemetas[$newlineitemmetarow['meta_key']]=$newlineitemmetarow['meta_value'];
                	   }
        	            
        	            
        	           //start comparing this line new vs old
        	           foreach($oldlinemetas as $oldkey => $oldvalue){
        	               foreach($newlinemetas as $newkey => $newvalue){
        	                   if($oldkey==$newkey){
        	                       if($oldvalue != $newvalue){
        	                           $changesThisItem++;
        	                       }
        	                   }
        	               }
        	           }
        	           //end comparing this line new vs old
        	           
        	           
        	           //find all "order items" rows from old quote item id#s
        	           $orderItemRows=$this->OrderItems->find('all',['conditions' => ['quote_line_item_id' => $oldLineItem['id']]])->toArray();
        	           /**PPSASSCRUM-29 start  */
					   $workorderItemRows=$this->WorkOrderItems->find('all',['conditions' => ['quote_line_item_id' => $oldLineItem['id']]])->toArray();

					   /**PPSASSCRUM-29 end */
        	           
        	           
        	           if($changesThisItem > 0){
        	               //this item is different than before... delete it from any Sherry batches
        	               $changes[]=$newLineItem['id'];
        	           }else{
        	               //no changes... dont touch sherry batches with this item
        	           }
        	           
        	           
        	        }

    	        }
    	        
    	    }
    	    $orderItemsTable=TableRegistry::get('OrderItems');
    	    
    	    $orderItemStatusTable=TableRegistry::get('OrderItemStatus');

			/**PPSASCRUM-29 start */
			$workorderItemsTable=TableRegistry::get('WorkOrderItems');
    	    
    	    $workorderItemStatusTable=TableRegistry::get('WorkOrderItemStatus');

			/**PPSASCRUM-29 end */
    	    
    	    //loop through the ADD log, and add them to OrderItems as needed
    	    foreach($newLineItems as $newLineItem){
    	        
    	        
    	        //see if this new quote has deleted line items
    	        
    	        
    	        //determine if this line was DELETED
    	        $deletecheck=$this->QuoteItemDeleteLog->find('all',['conditions' => ['quote_line_item_id' => $newLineItem['id']]])->toArray();
    	        if(count($deletecheck) > 0){
    	            //it has been deleted! delete this OrderItem entry
    	            $deletedOrderItemLookup=$this->OrderItems->find('all',['conditions' => ['quote_line_item_id' => $newLineItem['id']]])->toArray();
    	            foreach($deletedOrderItemLookup as $deletedOrderItem){
    	                $thisOrderItem=$orderItemTable->get($deletedOrderItem['id']);
    	                if($orderItemTable->delete($thisOrderItem)){
    	                    $deletions[]=$deletedOrderItem['id'];
    	                    
    	                    //delete the orderitemstatus for this item
    	                    $orderItemStatusRowsForThisItem=$this->OrderItemStatus->find('all',['conditions' => ['order_item_id' => $deletedOrderItem['id']]])->toArray();
    	                    foreach($orderItemStatusRowsForThisItem as $orderItemStatusRow){
    	                        $thisorderitemstatusentry=$orderItemStatusTable->get($orderItemStatusRow['id']);
    	                        $orderItemStatusTable->delete($thisorderitemstatusentry);
    	                    }
    	                }
    	            }


					/**PPSASCRUM-29 start */
					  //it has been deleted! delete this OrderItem entry
					  $deletedWOrkOrderItemLookup=$this->WorkOrderItems->find('all',['conditions' => ['quote_line_item_id' => $newLineItem['id']]])->toArray();
					  foreach($deletedWorkOrderItemLookup as $deletedWorkOrderItem){
						  $thisWorkOrderItem=$workorderItemTable->get($deletedWorkOrderItem['id']);
						  if($qorkorderItemTable->delete($thisWorkOrderItem)){
							  $wdeletions[]=$deletedWorkOrderItem['id'];
							  
							  //delete the orderitemstatus for this item
							  $workorderItemStatusRowsForThisItem=$this->WorkOrderItemStatus->find('all',['conditions' => ['order_item_id' => $deletedOrderItem['id']]])->toArray();
							  foreach($qorkorderItemStatusRowsForThisItem as $workorderItemStatusRow){
								  $thisworkorderitemstatusentry=$workorderItemStatusTable->get($workorderItemStatusRow['id']);
								  $workorderItemStatusTable->delete($thisworkorderitemstatusentry);
							  }
						  }
					  }
					/**PPSASCRUM-29 end */
    	            
    	        }
    	        

    	        
    	        
    	        //find out if this item is a NEW item
    	        $addcheck=$this->QuoteItemAddLog->find('all',['conditions' => ['line_item_id' => $newLineItem['id']]])->toArray();
    	        if(count($addcheck) >0){
    	            //ADD to orderitems
    	            $newOrderItem=$orderItemsTable->newEntity();
    	            $newOrderItem->order_id=$orderID;
    	            $newOrderItem->quote_id=$newquoteid;
    	            $newOrderItem->quote_line_item_id=$newLineItem['id'];
    	            $orderItemsTable->save($newOrderItem);
    	            
    	            //add this line to ORDER ITEM STATUS row with status 'Not Started'
    	            $newOrderItemStatus=$orderItemStatusTable->newEntity();
    	            $newOrderItemStatus->order_line_number=$newLineItem['line_number'];
    	            $newOrderItemStatus->order_item_id=$newOrderItem->id;
    	            $newOrderItemStatus->time=time();
    	            $newOrderItemStatus->status='Not Started';
    	            $newOrderItemStatus->user_id=$this->Auth->user('id');
    	            $newOrderItemStatus->qty_involved=$newLineItem['qty'];
    	            $newOrderItemStatus->work_order_id=$orderID;
    	            $orderItemStatusTable->save($newOrderItemStatus);


					/**PPSASCRUM-29 start */

					//ADD to workorderitems
    	            $newWorkOrderItem=$workorderItemsTable->newEntity();
    	            $newWorkOrderItem->order_id=$orderID;
    	            $newWorkOrderItem->quote_id=$newquoteid;
    	            $newWorkOrderItem->quote_line_item_id=$newLineItem['id'];
    	            $workorderItemsTable->save($newWorkOrderItem);
    	            
    	            //add this line to Work ORDER ITEM STATUS row with status 'Not Started'
    	            $newWorkOrderItemStatus=$workorderItemStatusTable->newEntity();
    	            $newWorkOrderItemStatus->order_line_number=$newLineItem['line_number'];
    	            $newWorkOrderItemStatus->order_item_id=$newOrderItem->id;
    	            $newWorkOrderItemStatus->time=time();
    	            $newWorkOrderItemStatus->status='Not Started';
    	            $newWorkOrderItemStatus->user_id=$this->Auth->user('id');
    	            $newWorkOrderItemStatus->qty_involved=$newLineItem['qty'];
    	            $newWorkOrderItemStatus->work_order_id=$orderID;
    	            $workorderItemStatusTable->save($newWorkOrderItemStatus);
					/**PPSASCRUM-29 end */
    	        }
    	        

    	        //modify orderitem entry to new quotelineitem id
    	        foreach($oldLineItems as $oldLineItem){
                    if($oldLineItem['id'] == $newLineItem['revised_from_line']){
                        
                        $oldOrderItemLookup=$this->OrderItems->find('all',['conditions' => ['quote_line_item_id' => $oldLineItem['id']]])->toArray();
                        foreach($oldOrderItemLookup as $oldOrderItem){
                            
                            $thisOldOrderItem=$orderItemsTable->get($oldOrderItem['id']);
                            $thisOldOrderItem->quote_id=$newquoteid;
                            $thisOldOrderItem->quote_line_item_id = $newLineItem['id'];
                            if($orderItemsTable->save($thisOldOrderItem)){
                                $additions[]=$newLineItem['id'];
                            
                                //$thisOldOrderItem->qty_involved = $newLineItem['qty'];
                                $oldOrderItemStatusRowLookup=$this->OrderItemStatus->find('all',['conditions' => ['order_item_id' => $oldOrderItem['id'], 'status' => 'Not Started']])->toArray();
                                foreach($oldOrderItemStatusRowLookup as $oldOISRow){
                                    $fixOISRow=$this->OrderItemStatus->get($oldOISRow['id']);
                                    $fixOISRow->qty_involved=$newLineItem['qty'];
                                    $this->OrderItemStatus->save($fixOISRow);
                                }
                            }
                            
                            
                        }
                        
                    }
    	        }

				/**PPSASCRUM-29 start */

				//modify orderitem entry to new quotelineitem id
    	        foreach($oldLineItems as $oldLineItem){
                    if($oldLineItem['id'] == $newLineItem['revised_from_line']){
                        
                        $oldWorkOrderItemLookup=$this->WorkOrderItems->find('all',['conditions' => ['quote_line_item_id' => $oldLineItem['id']]])->toArray();
                        foreach($oldWorkOrderItemLookup as $oldWorkOrderItem){
                            
                            $thisOldWorkOrderItem=$workorderItemsTable->get($oldWorkOrderItem['id']);
                            $thisOldWorkOrderItem->quote_id=$newquoteid;
                            $thisOldWorkOrderItem->quote_line_item_id = $newLineItem['id'];
                            if($workorderItemsTable->save($thisOldWorkOrderItem)){
                                $additions[]=$newLineItem['id'];
                            
                                //$thisOldOrderItem->qty_involved = $newLineItem['qty'];
                                $oldWorkOrderItemStatusRowLookup=$this->WorkOrderItemStatus->find('all',['conditions' => ['order_item_id' => $oldOrderItem['id'], 'status' => 'Not Started']])->toArray();
                                foreach($oldWorkOrderItemStatusRowLookup as $oldOISRow){
                                    $fixOISRow=$this->WorkOrderItemStatus->get($oldOISRow['id']);
                                    $fixOISRow->qty_involved=$newLineItem['qty'];
                                    $this->WorkOrderItemStatus->save($fixOISRow);
                                }
                            }
                            
                            
                        }
                        
                    }
    	        }

				/**PPSASCRUM-29 end */
    	        
    	        
    	    }

    	    $newQuote=$quotesTable->get($newquoteid);
    	    $newQuote->status='orderplaced';
    	    $quotesTable->save($newQuote);
    	    
    	    
    	    $orderRow=$ordersTable->get($orderID);
    	    $orderRow->quote_id=$newquoteid;
    	    $orderRow->order_total=$newQuote->quote_total;
    	    $orderRow->status='Pre-Production';
    	    $ordersTable->save($orderRow);

			/**PPSASCRUM-29 start */
			$workorderRow=$workordersTable->get($orderID);
    	    $workorderRow->quote_id=$newquoteid;
    	    $workorderRow->order_total=$newQuote->quote_total;
    	    $workorderRow->status='Pre-Production';
    	    $workordersTable->save($workorderRow);
			/**PPSASCRUM-29 end */

    	    
    	    /*sherry stuff begin
    	    $rebuildcachedays=array();
    	    
    	    $allOrderItems=$this->OrderItems->find('all',['conditions' => ['order_id' => $orderID]])->toArray();
    	    foreach($allOrderItems as $orderItem){
    	        
    	        if(in_array($orderItem['quote_line_item_id'],$changes) || in_array($orderItem['quote_line_item_id'],$additions) || in_array($orderItem['quote_line_item_id'],$deletions)){
    	            //find out if this item has any batches scheduled (status=SCHEDULED)
    	            $oistatuslookup=$this->OrderItemStatus->find('all',['conditions' => ['order_item_id' => $orderItem['id'], 'status !=' => 'Not Started']])->toArray();
    	            foreach($oistatuslookup as $oistatusrow){
    	                if($oistatusrow['status'] == 'Scheduled'){
    	                    if(!in_array(date("Y-m-d",$oistatusrow['time']),$rebuildcachedays)){
    	                        $rebuildcachedays[]=date("Y-m-d",$oistatusrow['time']);
    	                    }
    	                }
    	                
    	                $thisoistatusrow=$orderItemStatusTable->get($oistatusrow['id']);
    	                $orderItemStatusTable->delete($thisoistatusrow);
    	            }
    	        }
    	        
    	    }
    	    
    	    $batchTable=TableRegistry::get('SherryBatches');
    	    $batchCacheTable=TableRegistry::get('SherryCache');
    	    
    	    //delete any Empty batches
    	    $batchLoop=$this->SherryBatches->find('all',['conditions' => ['work_order_id' => $orderID]])->toArray();
    	    foreach($batchLoop as $batchLoopRow){
    	        //find out how many items are still Scheduled on this batch
    	        $itemsThisBatch=0;
    	        $batchItemsLookup=$this->OrderItemStatus->find('all',['conditions' => ['sherry_batch_id' => $batchLoopRow['id'],'status'=>'Scheduled']])->toArray();
    	        foreach($batchItemsLookup as $batchItemRow){
    	            $itemsThisBatch=($itemsThisBatch+$batchItemRow['qty_involved']);
    	        }
    	        
    	        if($itemsThisBatch == 0){
    	            //delete it!
    	            $thisBatch=$batchTable->get($batchLoopRow['id']);
    	            $batchTable->delete($thisBatch);
    	            
    	            //also delete those empty batches from the sherry Cache
    	            $lookupCacheRows=$this->SherryCache->find('all',['conditions'=>['batch_id' => $batchLoopRow['id']]])->toArray();
    	            foreach($lookupCacheRows as $cacheRow){
    	                $thisCacheRow=$batchCacheTable->get($cacheRow['id']);
    	                $batchCacheTable->delete($thisCacheRow);
    	            }
    	        }
    	    }
    	    */

    	    
    	    $this->auditOrderItemStatuses($orderID);

    	    /**PPSASCRUM-29 start */
			$this->auditWorkOrderItemStatuses($orderID);
			/**PPSASCRUM-29 end */

    	    //rebuild sherry caches
    	    foreach($rebuildcachedays as $num => $date){
    	        $this->updatesherrycachefordate($date);
    	    }
    	    
    	    //sherry stuff end
    	    
    	    
    	    //recount and save new count tallies to the ORDERS row
    	    $ordersTable=TableRegistry::get('Orders');
    	    $thisOrderRow=$ordersTable->get($orderID);
    	    $thisOrderRow->cc_qty = $this->getTypeQty($newquoteid,'cc');
    		$thisOrderRow->cc_lf = $this->getTypeLF($newquoteid,'cc');
    		$thisOrderRow->cc_diff = $this->getTypeDiff($newquoteid,'cc');
    		$thisOrderRow->cc_dollar = $this->getTypeDollars($newquoteid,'cc');
    		$thisOrderRow->track_lf = $this->getTypeLF($newquoteid,'track');
    		$thisOrderRow->track_dollar = $this->getTypeDollars($newquoteid,'track');
    		$thisOrderRow->bs_qty = $this->getTypeQty($newquoteid,'bs');
    		$thisOrderRow->bs_diff = $this->getTypeDiff($newquoteid,'bs');
    		$thisOrderRow->bs_dollar = $this->getTypeDollars($newquoteid,'bs');
    		$thisOrderRow->drape_qty = $this->getTypeQty($newquoteid,'drapes');
        	$thisOrderRow->drape_widths = $this->getTypeWidths($newquoteid,'drapes');
    		$thisOrderRow->drape_diff = $this->getTypeDiff($newquoteid,'drapes');
    		$thisOrderRow->drape_dollar = $this->getTypeDollars($newquoteid,'drapes');
    		$thisOrderRow->tt_qty = $this->getTypeQty($newquoteid,'tt');
    		$thisOrderRow->tt_lf = $this->getTypeLF($newquoteid,'tt');
    		$thisOrderRow->tt_diff = $this->getTypeDiff($newquoteid,'tt');
    		
    		$thisOrderRow->val_qty = $this->getTypeQty($newquoteid,'val');
    		$thisOrderRow->val_lf = $this->getTypeLF($newquoteid,'val');
    		
    		$thisOrderRow->corn_qty = $this->getTypeQty($newquoteid,'corn');
    		$thisOrderRow->corn_lf = $this->getTypeLF($newquoteid,'corn');
    		
    		$thisOrderRow->val_dollar = $this->getTypeDollars($newquoteid,'val');
    		$thisOrderRow->corn_dollar = $this->getTypeDollars($newquoteid,'corn');
    		$thisOrderRow->wt_hw_qty = $this->getTypeQty($newquoteid,'wtht');
    		$thisOrderRow->wt_hw_dollar = $this->getTypeDollars($newquoteid,'wtht');
    		$thisOrderRow->blinds_qty = $this->getTypeQty($newquoteid,'blinds');
        	$thisOrderRow->blinds_dollar = $this->getTypeDollars($newquoteid,'blinds');
        	
        	
        	$thisOrderRow->swtmisc_qty = $this->getTypeQty($newquoteid,'swtmisc');
        	
    		$thisOrderRow->catchall_qty = $this->getTypeQty($newquoteid,'catchall');
    		$ordersTable->save($thisOrderRow);
    	    

			//recount and save new count tallies to the WOrkORDERS row
    	    $workordersTable=TableRegistry::get('WorkOrders');
    	    $thisWorkOrderRow=$workordersTable->get($orderID);
    	    $thisWorkOrderRow->cc_qty = $this->getTypeQty($newquoteid,'cc');
    		$thisWorkOrderRow->cc_lf = $this->getTypeLF($newquoteid,'cc');
    		$thisWorkOrderRow->cc_diff = $this->getTypeDiff($newquoteid,'cc');
    		$thisWorkOrderRow->cc_dollar = $this->getTypeDollars($newquoteid,'cc');
    		$thisWorkOrderRow->track_lf = $this->getTypeLF($newquoteid,'track');
    		$thisWorkOrderRow->track_dollar = $this->getTypeDollars($newquoteid,'track');
    		$thisWorkOrderRow->bs_qty = $this->getTypeQty($newquoteid,'bs');
    		$thisWorkOrderRow->bs_diff = $this->getTypeDiff($newquoteid,'bs');
    		$thisWorkOrderRow->bs_dollar = $this->getTypeDollars($newquoteid,'bs');
    		$thisWorkOrderRow->drape_qty = $this->getTypeQty($newquoteid,'drapes');
        	$thisWorkOrderRow->drape_widths = $this->getTypeWidths($newquoteid,'drapes');
    		$thisWorkOrderRow->drape_diff = $this->getTypeDiff($newquoteid,'drapes');
    		$thisWorkOrderRow->drape_dollar = $this->getTypeDollars($newquoteid,'drapes');
    		$thisWorkOrderRow->tt_qty = $this->getTypeQty($newquoteid,'tt');
    		$thisWorkOrderRow->tt_lf = $this->getTypeLF($newquoteid,'tt');
    		$thisWorkOrderRow->tt_diff = $this->getTypeDiff($newquoteid,'tt');
    		
    		$thisWorkOrderRow->val_qty = $this->getTypeQty($newquoteid,'val');
    		$thisWorkOrderRow->val_lf = $this->getTypeLF($newquoteid,'val');
    		
    		$thisWorkOrderRow->corn_qty = $this->getTypeQty($newquoteid,'corn');
    		$thisWorkOrderRow->corn_lf = $this->getTypeLF($newquoteid,'corn');
    		
    		$thisWorkOrderRow->val_dollar = $this->getTypeDollars($newquoteid,'val');
    		$thisWorkOrderRow->corn_dollar = $this->getTypeDollars($newquoteid,'corn');
    		$thisWorkOrderRow->wt_hw_qty = $this->getTypeQty($newquoteid,'wtht');
    		$thisWorkOrderRow->wt_hw_dollar = $this->getTypeDollars($newquoteid,'wtht');
    		$thisWorkOrderRow->blinds_qty = $this->getTypeQty($newquoteid,'blinds');
        	$thisWorkOrderRow->blinds_dollar = $this->getTypeDollars($newquoteid,'blinds');
        	
        	
        	$thisWorkOrderRow->swtmisc_qty = $this->getTypeQty($newquoteid,'swtmisc');
        	
    		$thisWorkOrderRow->catchall_qty = $this->getTypeQty($newquoteid,'catchall');
    		$workordersTable->save($thisWorkOrderRow);
    	    
    	    //add what all has been done to the activity log
    	    
    	    
    	    return $this->redirect('/orders/editlines/'.$orderID);
	    }
	    
	}
	
	
	
	
	public function canceleditordermode($orderID){
	    $this->autoRender=false;
	    $quotesTable=TableRegistry::get('Quotes');
	    $quoteLinesTable=TableRegistry::get('QuoteLineItems');
	    $quoteLineMetasTable=TableRegistry::get('QuoteLineItemMeta');
	    $ordersTable=TableRegistry::get('Orders');
	    
	    
	    
	    //delete the editorder status temp revision
	    $temprevisionlookup=$this->Quotes->find('all',['conditions' => ['order_id' => $orderID,'status'=>'editorder']])->toArray();
	    foreach($temprevisionlookup as $temprevision){
	        $thisRevisionID=$temprevision['id'];
	        
	        //delete the quotelineitems entries for the temp revision
    	    $revisionLines=$this->QuoteLineItems->find('all',['conditions' => ['quote_id' => $thisRevisionID]])->toArray();
    	    foreach($revisionLines as $revLine){
    	        
    	        //delete the quotelineitemmeta entries for the temp revision
        	    $revisionLineMetas=$this->QuoteLineItemMeta->find('all',['conditions' => ['quote_item_id' => $revLine['id']]])->toArray();
        	    foreach($revisionLineMetas as $revLineMeta){
        	        $thisRevLineMeta=$quoteLineMetasTable->get($revLineMeta['id']);
        	        $quoteLineMetasTable->delete($thisRevLineMeta);
        	    }
    	        
    	        $thisRevLine=$quoteLinesTable->get($revLine['id']);
    	        $quoteLinesTable->delete($thisRevLine);
    	    }
    	    
	        $thisrevision=$quotesTable->get($temprevision['id']);
	        $quotesTable->delete($thisrevision);
	    }
	    
	    
	    //change the order status back to regular mode
	    $thisOrder=$ordersTable->get($orderID);
	    $thisOrder->status='Pre-Production';
	    $ordersTable->save($thisOrder);
	    
	    //redirect
	    return $this->redirect('/orders/editlines/'.$orderID);
	    
	}
	
	

	
	  /**PPSASCRUM-29 start **/
	  public function updatevals($quoteID,$mode){

		$this->autoRender=false;

		//find all quote items

        //if we are in EDIT ORDER mode,  find the hidden revision that is needed
        //if($mode=='editorder'){
        //   $thisQuote=$this->Quotes->find('all',['conditions'=>['status'=>'editorder','parent_quote'=>$quoteID]])->first()->toArray();
        //}else{
		    $thisQuote=$this->Quotes->get($quoteID)->toArray();
        //}

		$thisCustomer=$this->Customers->get($thisQuote['customer_id'])->toArray();

		$settings=$this->getsettingsarray();

		$trackComponentRows=array();

		$fabricTotals=array();

		$calculatorFabricTotals=array();

		$backingTotals=array();

		$fillToatls=array();

		$fabricWidthTotals=array();

		$calculatorFabricWidthTotals=array();

		$meshTotals=array();

		$allcalculators=$this->Calculators->find('all')->toArray();

		$quoteItems=$this->QuoteLineItems->find('all',['conditions'=>['quote_id'=>$thisQuote['id']],'order'=>['sortorder'=>'asc','id'=>'asc']])->toArray();
		$quoteItemsformetavalues=$this->QuoteLineItems->find('all',['conditions'=>['quote_id'=>$thisQuote['id']],'order'=>['sortorder'=>'asc','id'=>'asc']
		])->select('id');
		$lineItemMetaLookup=$this->QuoteLineItemMeta->find('all',['conditions'=>['quote_item_id in'=>$quoteItemsformetavalues, 'meta_key'=>'fabric-cost-per-yard-custom-value']]);
        if( $lineItemMetaLookup != null && $lineItemMetaLookup->count() > 0){
            $out = "<div style=\"color: red;text-align: center; padding: 10px;
    font-size: 16px; FONT-WEIGHT: BOLDER;\">Fabric Special Cost p/yd at play on some items</div>";
        }
		$out .="<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\" id=\"quoteitems\">

			<thead>

				<tr>

					<th>

					<table width=\"100%\" cellpadding=\"2\" cellspacing=\"0\" border=\"1\" bordercolor=\"#000000\">
					<tr class=\"innerrow\">
					<th width=\"10%\">";
					
					if($mode=='workorder'){
					    $out .= "<a href=\"/orders/rtmalllines/".$thisQuote['order_id']."/1\" style=\"color:#FFF;\">Release All Lines</a>";
					}else{
					    $out .= "Actions";
					}
					
					$out .= "</th>
					<th width=\"5%\" class=\"linenumberheading\">Line #</th>
					<th width=\"6%\">Location</th>
					<th width=\"4%\">Vis</th>
					<th width=\"5%\">Qty</th>
					<th width=\"4%\">Unit</th>
					<th width=\"10%\">Line Item</th>
					<th width=\"10%\">Image</th>
					<th width=\"5%\">Fabric</th>
					<th width=\"5%\">Color</th>
					<th width=\"3%\">Width</th>
					<th width=\"3%\">Length</th>
					<th width=\"4%\">Total LF</th>
					<th width=\"5%\">Yds/unit</th>";

					
					if($mode=='workorder'){
                        $out .= "<th width=\"21%\">Total Yds</th>";
					}else{
					    $out .= "<th width=\"6%\">Total Yds</th>";
					}

					if($mode != 'workorder'){
					    $out .= "<th width=\"6%\">Base</th>
    					<th width=\"4%\" class=\"adjustedprice\">Adj</th>
	    				<th width=\"5%\" class=\"extendedprice\">Ext</th>";
					}
                
				$out .= "</tr>

				</table>

				</th>

				</tr>

			</thead>

			<tbody>";

			$overallSubtotal=0;

			$overallTotalDue=0;

			$overallDiscount=0;

			$surchargeAmount=0;

		

		$productMap=array(

			'cubicle-curtain'=>'Cubicle Curtain',

			'box-pleated'=>'Box Pleated Valance',

			'straight-cornice'=>'Straight Cornice',

			'bedspread'=>'Calculated Bedspread',

			'bedspread-manual'=>'Manually Entered Bedspread',

			'pinch-pleated' => 'Pinch Pleated Drapery'

		);

		

		$rowNum=1;

		$totalCCLF=0;

		$totalTrackLF=0;

		$totalCornLF=0;

		$totalValLF=0;

		$totalDrapeLF=0;

		$totalCCwidths=0;

		$totalDiffCC=0;

		$totalWeightCC=0;

		$totalDiffTrack=0;

		$totalWeightTrack=0;

		$totalDiffCorn=0;

		$totalWeightCorn=0;

		$totalDiffVal=0;

		$totalWeightVal=0;

		$totalDiffDrape=0;

		$totalWeightDrape=0;

		$totalDiffBS=0;

		$totalWeightBS=0;

		$totalBSLF=0;


		foreach($quoteItems as $quoteItem){
		    
		    $thisOrderItemID=0;
		    
    		if($mode=='workorder'){
    		    //look up the order item id
    		    $orderItemLookup=$this->WorkOrderItems->find('all',['conditions' => ['quote_line_item_id' => $quoteItem['id']]])->toArray();
    		    foreach($orderItemLookup as $orderItem){
    		        $thisOrderItemID=$orderItem['id'];
    		    }
    		}

			$itemMetas=$this->QuoteLineItemMeta->find('all',['conditions'=>['quote_item_id'=>$quoteItem['id']]])->toArray();

			$metaArray=array();

			

			foreach($itemMetas as $meta){
			    
			    if($mode=='workorder'){
			        //look up the Work Order Item Meta for overriding values
			        $orderItemMetas=$this->WorkOrderItemMeta->find('all',['conditions' => ['order_item_id' => $thisOrderItemID, 'meta_key' => $meta['meta_key']]])->toArray();
			        if(count($orderItemMetas) == 0){
			            //default to the quote meta
			            $metaArray[$meta['meta_key']]=$meta['meta_value'];
			        }else{
			            foreach($orderItemMetas as $orderItemMeta){
			                $metaArray[$meta['meta_key']]=$orderItemMeta['meta_value'];
			            }
			        }
			    }else{
				    $metaArray[$meta['meta_key']]=$meta['meta_value'];
			    }

			}
			
			
			//also look up Order Item Meta keys that dont exist in Quote item meta
			$allOrderItemMetas=$this->OrderItemMeta->find('all',['conditions' => ['order_item_id' => $thisOrderItemID]])->toArray();
			foreach($allOrderItemMetas as $orderItemMetaRow){
			    if(!isset($metaArray[$orderItemMetaRow['meta_key']])){
			        $metaArray[$orderItemMetaRow['meta_key']]=$orderItemMetaRow['meta_value'];
			    }
			}
			
			

			if($quoteItem['product_type'] != 'custom' && $quoteItem['product_type'] != 'services' && $quoteItem['product_type'] != 'track_systems'){
    
                if(isset($metaArray['fabrictype']) && ($metaArray['fabrictype'] != 'none' && $metaArray['fabrictype'] != 'typein')){
				    $thisFabric=$this->Fabrics->get($metaArray['fabricid'])->toArray();
                }elseif(isset($metaArray['fabrictype']) && $metaArray['fabrictype'] == 'typein'){
				    $thisFabric=array('fabric_name'=>$metaArray['fabric_name'],'color'=>$metaArray['fabric_color']);
				}else{
				    if(isset($metaArray['fabricid']) && strlen(trim($metaArray['fabricid'])) >0 && $metaArray['fabricid'] != '0'){
					    $thisFabric=$this->Fabrics->get($metaArray['fabricid'])->toArray();
				    }else{
				        $thisFabric=array('fabric_name'=>'','color'=>'');
				    }
				}

			}else{

                if(isset($metaArray['fabrictype']) && ($metaArray['fabrictype'] != 'none' && $metaArray['fabrictype'] != 'typein')){
				    if(isset($metaArray['fabricid']) && strlen(trim($metaArray['fabricid'])) >0 && $metaArray['fabricid'] != '0'){
					    $thisFabric=$this->Fabrics->get($metaArray['fabricid'])->toArray();
				    }else{
				        $thisFabric=array('fabric_name'=>'','color'=>'');
				    }

				}elseif(isset($metaArray['fabrictype']) && $metaArray['fabrictype'] == 'typein'){
				    $thisFabric=array('fabric_name'=>$metaArray['fabric_name'],'color'=>$metaArray['fabric_color']);
				}else{
                    if(isset($metaArray['fabricid']) && strlen(trim($metaArray['fabricid'])) >0 && $metaArray['fabricid'] != '0'){
					    $thisFabric=$this->Fabrics->get($metaArray['fabricid'])->toArray();
				    }else{
				        $thisFabric=array('fabric_name'=>'','color'=>'');
				    }
				}

			}

			

			if($quoteItem['product_type'] == 'track_systems' || $quoteItem['product_subclass'] == '3'){

				$trackComponentRows[]=array('metadata'=>$metaArray,'linedata'=>$quoteItem);

			}

			/*

			if($rowNum % 2 == 0){

				$thisrowbg='lightrow';

			}else{

				$thisrowbg='darkrow';

			}

			*/

			if(floatval($quoteItem['line_number']) % 2 == 0){

				$thisrowbg='darkrow';

			}else{

				$thisrowbg='lightrow';

			}

			

			if(isset($metaArray['fabricid']) && is_numeric($metaArray['fabricid'])){

				if(!isset($fabricTotals[$metaArray['fabricid']])){

					$fabricTotals[$metaArray['fabricid']]=(floatval($metaArray['yds-per-unit']) * floatval($metaArray['qty']));

				}else{

					$fabricTotals[$metaArray['fabricid']]=($fabricTotals[$metaArray['fabricid']]+(floatval($metaArray['yds-per-unit']) * floatval($metaArray['qty'])));

				}

				if(!isset($fabricWidthTotals[$metaArray['fabricid']])){

					$fabricWidthTotals[$metaArray['fabricid']]=floatval($metaArray['total-widths']);

				}else{

					$fabricWidthTotals[$metaArray['fabricid']]=($fabricWidthTotals[$metaArray['fabricid']] + floatval($metaArray['total-widths']));

				}

				//calculator items only tally

				if($quoteItem['product_type'] == 'calculator'){

					if(!isset($calculatorFabricTotals[$metaArray['fabricid']])){

						$calculatorFabricTotals[$metaArray['fabricid']]=(floatval($metaArray['yds-per-unit']) * floatval($metaArray['qty']));

					}else{

						$calculatorFabricTotals[$metaArray['fabricid']]=($calculatorFabricTotals[$metaArray['fabricid']]+(floatval($metaArray['yds-per-unit']) * floatval($metaArray['qty'])));

					}

					if(!isset($calculatorFabricWidthTotals[$metaArray['fabricid']])){

						$calculatorFabricWidthTotals[$metaArray['fabricid']]=floatval($metaArray['total-widths']);

					}else{

						$calculatorFabricWidthTotals[$metaArray['fabricid']]=($calculatorFabricWidthTotals[$metaArray['fabricid']] + floatval($metaArray['total-widths']));

					}

				}

				//end calc only tally

				if(isset($this->request->query['highlightFabric'])){

					if($this->request->query['highlightFabric'] == $metaArray['fabricid']){

						$thisrowbg='highlightedfabric';

					}

				}

			}

			//mesh tallies

			if($quoteItem['product_type'] == 'cubicle_curtains'){

				//price list metas

				//if(!isset($meshTotals[$metaArray['mesh']])){

				//	$meshTotals[$metaArray['mesh']." MOM Mesh"]=(floatval($metaArray['labor-billable'])*floatval($metaArray['qty']));

				//}else{

					$meshTotals[$metaArray['mesh']." MOM Mesh"]=(floatval($meshTotals[$metaArray['mesh']." MOM Mesh"]) + (floatval($metaArray['labor-billable'])*floatval($metaArray['qty'])));

				//}

			}elseif($quoteItem['product_type']=='calculator' && $metaArray['calculator-used'] == 'cubicle-curtain'){

				//calculator metas

				//if(!isset($meshTotals[$metaArray['mesh']."\" (".$metaArray['mesh-color'].") ".$metaArray['mesh-type']])){

				//	$meshTotals[$metaArray['mesh']."\" (".$metaArray['mesh-color'].") ".$metaArray['mesh-type']]=(floatval($metaArray['labor-billable']) * floatval($metaArray['qty']));

				//}else{

					$meshTotals[$metaArray['mesh']."\" (".$metaArray['mesh-color'].") ".$metaArray['mesh-type']]=(floatval($meshTotals[$metaArray['mesh']."\" (".$metaArray['mesh-color'].") ".$metaArray['mesh-type']]) + (floatval($metaArray['labor-billable']) * floatval($metaArray['qty'])));

				//}

			}

			if(isset($metaArray['quilted']) && $metaArray['quilted'] == '1'){

				if(!isset($backingTotals[$thisFabric['bs_backing_material']." Backing"])){

					$backingTotals[$thisFabric['bs_backing_material']." Backing"]=0;

				}

				if(!isset($fillTotals['6oz Fill'])){

					$fillTotals['6oz Fill']=0;

				}

				$backingTotals[$thisFabric['bs_backing_material']." Backing"]=($backingTotals[$thisFabric['bs_backing_material']." Backing"] + (floatval($metaArray['yds-per-unit']) * floatval($metaArray['qty'])));

				

				$fillTotals['6oz Fill']=($fillTotals['6oz Fill'] + (floatval($metaArray['yds-per-unit']) * floatval($metaArray['qty'])));

			}

			if(isset($metaArray['labor-billable']) && is_numeric($metaArray['labor-billable'])){

				

				if(($quoteItem['product_type']=='calculator' && $metaArray['calculator-used']=="cubicle-curtain") || $quoteItem['product_type'] == 'cubicle_curtains'){

					$totalCCLF=($totalCCLF + (floatval($metaArray['labor-billable'])*floatval($metaArray['qty'])));

					

				}

				if(($quoteItem['product_type'] == 'calculator' && $metaArray['calculator-used'] == 'straight-cornice') || ($quoteItem['product_type'] == 'window_treatments' && preg_match("#Cornice#i",$metaArray['wttype']))){

					$totalCornLF = ($totalCornLF + (floatval($metaArray['labor-billable']) * floatval($metaArray['qty'])));

				}

				if(($quoteItem['product_type'] == 'calculator' && $metaArray['calculator-used'] == 'box-pleated') || ($quoteItem['product_type'] == 'window_treatments' && preg_match("#Valance#i",$metaArray['wttype']))){

					$totalValLF = ($totalValLF + (floatval($metaArray['labor-billable']) * floatval($metaArray['qty'])));

				}

			}

			if($quoteItem['product_type'] == 'bedspreads' || ($quoteItem['product_type'] == 'calculator' && ($metaArray['calculator-used'] == 'bedspread' || $metaArray['calculator-used'] == 'bedspread-manual'))){

				$totalBSLF=($totalBSLF + floatval($metaArray['qty']));

			}

			if(isset($metaArray['difficulty-rating']) && is_numeric($metaArray['difficulty-rating'])){

				if(($quoteItem['product_type']=='calculator' && $metaArray['calculator-used']=="cubicle-curtain")){

					//multiplied by qty already

					$totalDiffCC = ($totalDiffCC + floatval($metaArray['difficulty-rating']) );

				}elseif($quoteItem['product_type'] == 'cubicle_curtains'){

					//needs to be multiplied by qty

					$totalDiffCC = ($totalDiffCC + (floatval($metaArray['difficulty-rating']) * floatval($metaArray['qty']) ));

				}

				if(($quoteItem['product_type'] == 'calculator' && $metaArray['calculator-used'] == 'straight-cornice')){

					//multiplied by qty already

					$totalDiffCorn = ($totalDiffCorn + floatval($metaArray['difficulty-rating']) );

				}elseif($quoteItem['product_type'] == 'window_treatments' && preg_match("#Cornice#i",$metaArray['wttype'])){

					//needs to be multiplied by qty

					$totalDiffCorn = ($totalDiffCorn + (floatval($metaArray['difficulty-rating']) * floatval($metaArray['qty'])));

				}

				if($quoteItem['product_type'] == 'calculator' && ($metaArray['calculator-used'] == 'bedspread' || $metaArray['calculator-used'] == 'bedspread-manual')){

					//multiplied by qty already

					$totalDiffBS = ($totalDiffBS + floatval($metaArray['difficulty-rating']) );

				}elseif($quoteItem['product_type'] == 'bedspreads'){

					//needs to be multiplied by qty

					$totalDiffBS = ($totalDiffBS + (floatval($metaArray['difficulty-rating']) * floatval($metaArray['qty'])));

				}

			}

			if(isset($metaArray['cc_calculated_weight']) && is_numeric($metaArray['cc_calculated_weight'])){

				if(($quoteItem['product_type']=='calculator' && $metaArray['calculator-used']=="cubicle-curtain")){

					//multiplied by qty already

					$totalWeightCC = ($totalWeightCC + floatval($metaArray['cc_calculated_weight']) );

				}elseif($quoteItem['product_type'] == 'cubicle_curtains'){

					//needs to be multiplied by qty

					$totalWeightCC = ($totalWeightCC + (floatval($metaArray['cc_calculated_weight']) * floatval($metaArray['qty'])));

				}

			}

			if(isset($metaArray['bs_calculated_weight']) && is_numeric($metaArray['bs_calculated_weight'])){

				if($quoteItem['product_type']=='calculator' && ($metaArray['calculator-used']=="bedspread" || $metaArray['calculator-used']=="bedspread-manual")){

					//multiplied by qty already

					$totalWeightBS = ($totalWeightBS + floatval($metaArray['bs_calculated_weight']) );

				}elseif($quoteItem['product_type'] == 'bedspreads'){

					//needs to be multiplied by qty

					$totalWeightBS = ($totalWeightBS + (floatval($metaArray['bs_calculated_weight']) * floatval($metaArray['qty'])));

				}

			}	

			if(isset($metaArray['labor-widths']) && $metaArray['wttype'] == 'Pinch Pleated Drapery'){

				$totalDrapeLF = ($totalDrapeLF + (floatval($metaArray['labor-widths']) * floatval($metaArray['qty'])));

			}
			
			$classLookup=$this->ProductClasses->get($quoteItem['product_class'])->toArray();
			$thisClass=$classLookup['class_name'];
			
			$subclassLookup=$this->ProductSubclasses->get($quoteItem['product_subclass'])->toArray();
			$thisSubClass=$subclassLookup['subclass_name'];
			

			$out .= "<tr class=\"".$thisrowbg;
			
			$out .= " class".$quoteItem['product_class']." subclass".$quoteItem['product_subclass'];
			
			$out .= " quotelineitem\" id=\"".$quoteItem['id']."\" data-lineitemid=\"".$quoteItem['id']."\">";

			//begin column "actions"

			$out .= "<td valign=\"top\">

			<table width=\"100%\" cellpadding=\"2\" class=\"innerrow\" cellspacing=\"0\" border=\"1\" bordercolor=\"#000000\">

			<tr>

			<td valign=\"top\" width=\"10%\" class=\"actionscol\">";

			

			if($mode=='workorder'){
			    
			    //get this order item id
			   
			    //foreach($orderItemLookup as $orderItem){
					$out .= "<a href=\"/quotes/editlineitem/";

			        $out .= $quoteItem['id'];
			        
			        $out .= "/\"><img src=\"/img/edit.png\" alt=\"Edit WO Line Item\" title=\"Edit WO Line Item\" /></a> ";
			        
			        $out .= "<a href=\"/orders/buildbluesheet/".$quoteItem['id']."\" target=\"_blank\"><img src=\"/img/bluesheet.png\" alt=\"Generate Blue Sheet\" title=\"Generate Blue Sheet\" /></a> ";
			    
    			    if($quoteItem['enable_tally'] == 1){
        				$out .= "<div class=\"tallyaction disable isenabled\"><a href=\"javascript:changeSherryTallySetting('".$quoteItem['id']."','0');\">Disable Sherry Tally</a></div>";
        			}else{
        				$out .= "<div class=\"tallyaction enable isdisabled\"><a href=\"javascript:changeSherryTallySetting('".$quoteItem['id']."','1');\">Enable Sherry Tally</a></div>";
        			}
        			
        			if($quoteItem['released_to_manufacture']==1){
        			    $out .= "<div class=\"rtm undortm\"><a href=\"javascript:changeRTMSetting('".$quoteItem['id']."','0');\">Undo RTM</a></div>";
        			}else{
        			    $out .= "<div class=\"rtm dortm\"><a href=\"javascript:changeRTMSetting('".$quoteItem['id']."','1');\">Release To Manufacture</a></div>";
        			}
			    //}
			//}
    			
			}else{

				if($thisQuote['status']=="draft" || $thisQuote['status'] == 'emptyorder' || $thisQuote['status'] == 'editorder'){

					$out .= "<img src=\"/img/moveicon.png\" class=\"movehandle\" /> ";

					if($quoteItem['lineitemtype']=="calculator"){

						$out .= "<a href=\"/quotes/editcalclineitem/".$quoteItem['id']."/\"><img src=\"/img/calc-icon.png\" title=\"Edit This Calculation\" alt=\"Edit This Calculation\" class=\"editcalclineitem\" data-lineitemid=\"".$quoteItem['id']."\" /></a> ";

					}else{

						$out .= "<a href=\"javascript:editLineItem('".$quoteItem['id']."')\"><img src=\"/img/edit.png\" title=\"Edit This Line Item\" alt=\"Edit This Line Item\" class=\"editlineitem\" data-lineitemid=\"".$quoteItem['id']."\" /></a> ";

					}

                    $out .= "<a href=\"javascript:deleteLineItem('".$quoteItem['id']."')\"><img src=\"/img/delete.png\" title=\"Delete This Line Item\" alt=\"Delete This Line Item\" class=\"deletelineitem\" data-lineitemid=\"".$quoteItem['id']."\" /></a> ";
                    

					$out .= "<a href=\"javascript:addInternalNote('".$quoteItem['id']."','quote')\"><img src=\"/img/stickynote.png\" title=\"Add Note to this Line Item\" alt=\"Add Note to this Line Item\" /></a> ";

					$out .= "<a href=\"/quotes/newlineitem/".$thisQuote['id']."/newcatchall-misc/".$quoteItem['line_number']."/\"><img src=\"/img/newchildrow.png\" alt=\"New Custom Child Line\" title=\"New Custom Child Line\" /></a> ";
					
					//$out .= "<a href=\"javascript:addChildLine('".$quoteItem['line_number']."');\"><img src=\"/img/newchildrow.png\" alt=\"New Custom Child Line\" title=\"New Custom Child Line\" /></a> ";
					
					/*$out .= "<span class=\"newchildrowicon\"><img src=\"/img/newchildrow.png\" />
					<div class=\"childrowpopout\">
					<ul>
					<li><a href=\"/quotes/newlineitem/".$thisQuote['id']."/newcatchall-misc/".$quoteItem['line_number']."/\">Miscellaneous</a></li>
					<li><a href=\"/quotes/newlineitem/".$thisQuote['id']."/newcatchall-hardware/".$quoteItem['line_number']."/\">Hardware</a></li>
					<li><a href=\"/quotes/newlineitem/".$thisQuote['id']."/newcatchall-service/".$quoteItem['line_number']."/\">Services</a></li>
					<li><a href=\"/quotes/newlineitem/".$thisQuote['id']."/newcatchall-bedding/".$quoteItem['line_number']."/\">Bedding</a></li>
					<li><a href=\"/quotes/newlineitem/".$thisQuote['id']."/newcatchall-cubicle/".$quoteItem['line_number']."/\">Cubicle Curtains</a></li>
					<li class=\"hassubmenu\"><a href=\"#\">Soft Window Treatments</a>
					    <ul>
					    <li><a href=\"/quotes/newlineitem/".$thisQuote['id']."/newcatchall-valance/".$quoteItem['line_number']."/\">Valance Catch-All</a></li>
					    <li><a href=\"/quotes/newlineitem/".$thisQuote['id']."/newcatchall-drapery/".$quoteItem['line_number']."/\">Drapery Catch-All</a></li>
					    <li><a href=\"/quotes/newlineitem/".$thisQuote['id']."/newcatchall-cornice/".$quoteItem['line_number']."/\">Cornice Catch-All</a></li>
					    <li><a href=\"/quotes/newlineitem/".$thisQuote['id']."/newcatchall-swtmisc/".$quoteItem['line_number']."/\">SWT Misc Catch-All</a></li>
					    </ul>
					</li>
					<li class=\"hassubmenu\"><a href=\"#\">Hard Window Treatments</a>
					    <ul>
					    <li><a href=\"/quotes/newlineitem/".$thisQuote['id']."/newcatchall-blinds/".$quoteItem['line_number']."/\">Blinds Catch-All</a></li>
					    <li><a href=\"/quotes/newlineitem/".$thisQuote['id']."/newcatchall-shades/".$quoteItem['line_number']."/\">Shades Catch-All</a></li>
					    <li><a href=\"/quotes/newlineitem/".$thisQuote['id']."/newcatchall-shutters/".$quoteItem['line_number']."/\">Shutters Catch-All</a></li>
					    <li><a href=\"/quotes/newlineitem/".$thisQuote['id']."/newcatchall-hwtmisc/".$quoteItem['line_number']."/\">HWT Misc Catch-All</a></li>
					    </ul>
					</li>
					</ul>
					</div></span>";*/

					$out .= "<a href=\"/quotes/clonelineitem/".$quoteItem['id']."\"><img src=\"/img/clone.png\" alt=\"Clone This Line\" title=\"Clone This Line\" /></a>";

					if($quoteItem['product_type'] == 'track_systems' || $quoteItem['product_subclass'] == '3'){

						$out .= " <a href=\"/quotes/newlineitem/".$thisQuote['id']."/custom/".$quoteItem['line_number']."/".$quoteItem['id']."/\"><img src=\"/img/text-list-bullets-icon.png\" alt=\"Track Components\" title=\"Track Components\" /></a>";

					}

				}else{

					if($mode=='order'){

						$out .= "<a href=\"javascript:addInternalNote('".$quoteItem['id']."','order')\"><img src=\"/img/stickynote.png\" title=\"Add Note to this Line Item\" alt=\"Add Note to this Line Item\" /></a> ";

					}

				}

	
    			
    			if($quoteItem['product_class'] == 8 && $quoteItem['product_subclass'] == 25){
    			    $out .= "<div style=\"display:inline-block; line-height:13px; background:#FFEFBF; color:#B25900; font-size:10px; font-style:italic; padding:2px;vertical-align:middle;\"><img src=\"/img/alert.png\" width=\"15\" /> Consider reclassifying this item</div>";   
    			}
			
			}
			
			
			$out .= "</td>";

			//end column "actions"

			

			//being line number

			$out .= "<td valign=\"top\" width=\"5%\" class=\"linenumber\">";

			if($thisQuote['status']=="draft" || $thisQuote['status'] == 'emptyorder'){

				$out .= "<input type=\"number\" min=\"1\" data-itemid=\"".$quoteItem['id']."\" class=\"linenumber\" onchange=\"updatelinenumber('".$quoteItem['id']."',this.value)\" name=\"lineitem_".$quoteItem['id']."_linenumber\" value=\"".$quoteItem['line_number']."\" />";

			}else{

				$out .= $quoteItem['line_number'];

			}

			$out .= "</td>";

			//end line number

			

			

			//begin column "location"

			$out .= "<td valign=\"top\" width=\"6%\">";

			if($thisQuote['status']=="draft" || $thisQuote['status'] == 'emptyorder'){

				$out .= "<input type=\"text\" class=\"roomnumber\" data-itemid=\"".$quoteItem['id']."\" value=\"".$quoteItem['room_number']."\" />";

			}else{
                
                if($mode=='workorder'){
                    if(isset($metaArray['location'])){
                        $out .= $metaArray['location'];
                    }else{
                        $out .= $quoteItem['room_number'];
                    }
                }else{
				    $out .= $quoteItem['room_number'];
                }

			}

			$out .= "</td>";

			//end column "location"

			

			

			//begin column "visibility"

			$out .= "<td valign=\"top\" style=\"text-align:center\" width=\"4%\">";

			if($quoteItem['internal_line'] == 0){

				$out .= "<img src=\"/img/visible.png\" ";

				if($thisQuote['status']=="draft" || $thisQuote['status'] == 'emptyorder'){

					$out .= "style=\"cursor:pointer;\" onclick=\"changeLineToInternal('".$quoteItem['id']."','1')\" ";

				}

				$out .= "width=\"14\" height=\"14\" />";

			}else{

				$out .= "<img src=\"/img/hidden.png\" ";

				if($thisQuote['status']=="draft" || $thisQuote['status'] == 'emptyorder'){

					$out .= "style=\"cursor:pointer;\" onclick=\"changeLineToInternal('".$quoteItem['id']."','0')\" ";

				}

				$out .= "width=\"14\" height=\"14\" />";

			}

						
			$out .= "</td>";

			//end column "visibility"

			

			

			//begin column "qty"

			$out .= "<td valign=\"top\" width=\"5%\">";

			if($thisQuote['status']=="draft" || $thisQuote['status'] == 'emptyorder'){

				if($metaArray['lineitemtype'] == 'simpleproduct' || $metaArray['lineitemtype'] == 'custom'){

					$out .= "<img width=\"12\" height=\"12\" src=\"/img/minusicon.png\" onclick=\"subtractQty('".$quoteItem['id']."','".$quoteItem['qty']."')\" /> <input type=\"text\" onkeyup=\"updateQTYvalue('".$quoteItem['id']."',this.value)\" data-lineitemid=\"".$quoteItem['id']."\" class=\"qtyvalue\" id=\"qty_line_item_".$quoteItem['id']."\" value=\"".$quoteItem['qty']."\" /> <img width=\"12\" height=\"12\" src=\"/img/plusicon.png\" onclick=\"addQty('".$quoteItem['id']."','".$quoteItem['qty']."')\" />";

				}else{

					$out .= $quoteItem['qty'];

				}

			}else{

				$out .= $quoteItem['qty'];

			}

			$out .= "</td>";

			//end column "qty"

			

			//begin column "unit"

			$out .= "<td valign=\"top\" width=\"4%\">";

			if($quoteItem['unit'] == 'linear feet'){

				$out .= "LF";

			}else{

				$out .= ucfirst($quoteItem['unit']);

			}

			$out .= "</td>";

			//end column "unit"

			

			//begin column "line item"

			$out .= "<td valign=\"top\" width=\"10%\">";

			//$out .= print_r($itemMetas,1);

			if($metaArray['lineitemtype']=='calculator'){

				

				if($metaArray['calculator-used']=="straight-cornice"){

					

					$out .= $metaArray['cornice-type']." Cornice";
					
					$out .= "<br>(".$thisClass." / ".$thisSubClass.")";

					if($metaArray['railroaded'] == '1'){

						$out .= "<br>Fabric Railroaded";

					}else{

						$out .= "<br>Fabric Vertically Seamed";

					}

					$thisLining=$this->Linings->get($metaArray['linings_id'])->toArray();

					$out .= "<br><b>Lining:</b> ".$thisLining['short_title'];

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

						$out .= "<br><b>Welts:</b> ".$welts;

					}else{

						$out .= "<br><b>Welts:</b> None";

					}

					if($metaArray['individual-nailheads'] == '1'){

						$out .= "<br>Individual Nailheads";

					}

					if($metaArray['nailhead-trim'] == '1'){

						$out .= "<br>Nailhead Trim";

					}

					if($metaArray['covered-buttons'] == '1'){

						$out .= "<br>".$metaArray['covered-buttons-count']." Covered Buttons";

					}

					

					if($metaArray['horizontal-straight-banding'] == '1'){

						$out .= "<br>".$metaArray['horizontal-straight-banding-count']." H Straight Banding";

					}	

					if($metaArray['horizontal-shaped-banding'] == '1'){

						$out .= "<br>".$metaArray['horizontal-shaped-banding-count']." H Shaped Banding";

					}

					if($metaArray['extra-welts'] == '1'){

						$out .= "<br>".$metaArray['extra-welts-count']." Extra Welts";

					}	

					if($metaArray['trim-sewn-on'] == '1'){

						$out .= "<br>".$metaArray['trim-lf']." LF Sewn-On Trim";

					}

					if($metaArray['tassels'] == '1'){

						$out .= "<br>".$metaArray['tassels-count']." Tassels";

					}

					if($metaArray['drill-holes'] == '1'){

						$out .= "<br>".$metaArray['drill-hole-count']." Drill Holes";

					}

					if(isset($metaArray['vertical-repeat']) && floatval($metaArray['vertical-repeat']) >0){

						$out .= "<br>Matched Repeat (".$metaArray['vertical-repeat']."&quot;)";

					}

				}elseif($metaArray['calculator-used']=="bedspread" || $metaArray['calculator-used'] == "bedspread-manual"){

					$out .= $productMap[$metaArray['calculator-used']];
					
					$out .= "<br>(".$thisClass." / ".$thisSubClass.")";

					if($metaArray['railroaded'] == '1'){

						$out .= "<br>Fabric Railroaded";

					}else{

						$out .= "<br>Fabric Up-the-Roll";

					}

					

					$out .= "<br>";

					if(isset($metaArray['style']) && strlen(trim($metaArray['style'])) >0){

						$out .= "Style: ".$metaArray['style'];

					}

					if(isset($metaArray['quilted']) && $metaArray['quilted']=='1'){

						$out .= "<br>Quilted";

						if(isset($metaArray['quilting-pattern']) && strlen(trim($metaArray['quilting-pattern'])) >0){

							$out .= ", ".$metaArray['quilting-pattern'];

						}

						if(isset($metaArray['matching-thread']) && $metaArray['matching-thread'] == '1'){

							$out .= ", Matching Thread";

						}

						$out .= ", ".$thisFabric['bs_backing_material']." Backing";

					}else{

						$out .= "<br>Unquilted";

					}

					

					

					$out .= "<br>Mattress: ";

					if(!isset($metaArray['custom-top-width-mattress-w'])){

						$out .= "36&quot;";

					}else{

						$out .= $metaArray['custom-top-width-mattress-w']."&quot;";

					}

					

					if(isset($metaArray['vertical-repeat']) && floatval($metaArray['vertical-repeat']) >0){

						$out .= "<br>Matched Repeat (".$metaArray['vertical-repeat']."&quot;)";

					}

				}elseif($metaArray['calculator-used']=="box-pleated"){

					

					$out .= $metaArray['valance-type']." Valance";

					$out .= "<br>(".$thisClass." / ".$thisSubClass.")";

					if($metaArray['railroaded'] == '1'){

						$out .= "<br>Fabric Railroaded";

					}else{

						$out .= "<br>Fabric Vertically Seamed";

					}

					$out .= "<br>".$metaArray['pleats']." Pleats";

					

					$thisLining=$this->Linings->get($metaArray['linings_id'])->toArray();

					$out .= "<br><b>Lining:</b> ".$thisLining['short_title'];

					//$out .= print_r($metaArray,1);

					if($metaArray['straight-banding']==1){

						$out .= "<br>Straight Banding";

					}

					if($metaArray['shaped-banding']==1){

						$out .= "<Br>Shaped Banding";

					}

					if($metaArray['trim-sewn-on']==1){

						$out .= "<br>Sewn-On Trim";

					}

					if($metaArray['welt-covered-in-fabric'] == 1){

						$out .= "<br>Welt Covered In Fabric";

					}

					if($metaArray['contrast-fabric-inside-pleat'] == 1){

						$out .= "<br>Contrast Fabric Inside Pleat";

					}

					if(isset($metaArray['vert-repeat']) && floatval($metaArray['vert-repeat']) >0){

						$out .= "<br>Matched Repeat (".$metaArray['vert-repeat']."&quot;)";

					}

				}elseif($metaArray['calculator-used']=="cubicle-curtain"){

					//$out .= print_r($metaArray,1);

					$out .= $productMap[$metaArray['calculator-used']];
					
					$out .= "<br>(".$thisClass." / ".$thisSubClass.")";

					if($metaArray['railroaded'] == '1'){

						$out .= "<br>Fabric Railroaded";

					}else{

						$out .= "<br>Fabric Vertically Seamed";

					}

					if($metaArray['mesh'] > 0 && $metaArray['mesh'] != ''){

						$out .= "<br><b>Mesh:</b> ".$metaArray['mesh']."&quot; ".$metaArray['mesh-color']." (".str_replace(" Mesh","",$metaArray['mesh-type']).")";

					}

					if($metaArray['mesh-type'] == 'None'){

						$out .= "<br>NO MESH";

					}

					if($metaArray['mesh-type'] == 'Integral Mesh'){

						$out .= "<br>INTEGRAL MESH";

					}

					if($metaArray['liner'] == 1){

						$out .= "<br>Liner";

					}

					if($metaArray['nylon-mesh']==1){

						$out .= "<br>Nylon Mesh";

					}

					if($metaArray['angled-mesh']==1){

						$out .= "<br>Angled Mesh";

					}

					if($metaArray['mesh-frame'] != 'No Frame'){

						$out .= "<br><b>Mesh Frame:</b> ".$metaArray['mesh-frame'];

					}

					if($metaArray['hidden-mesh'] == 1){

						$out .= "<br>Hidden Mesh";

					}

					

					if($metaArray['snap-tape'] != "None"){

						$out .= "<br>".$metaArray['snap-tape']." Snap Tape (".$metaArray['snaptape-lf'].")";

					}

					if($metaArray['velcro'] != 'None'){

						$out .= "<br>".$metaArray['velcro']." Velcro (".$metaArray['velcro-lf']." LF)";

					}

					if($metaArray['weights']==1){

						$out .= "<br>".$metaArray['weight-count']." Weights";

					}

					if($metaArray['magnets']==1){

						$out .= "<br>".$metaArray['magnet-count']." Magnets";

					}

					if($metaArray['banding'] == 1){

						$out .= "<br>Banding";

					}

					if($metaArray['buttonholes'] == 1){

						$out .= "<br>".$metaArray['buttonhole-count']." Buttonholes";

					}

					if(isset($metaArray['vertical-repeat']) && floatval($metaArray['vertical-repeat']) >0){

						$out .= "<br>Matched Repeat (".$metaArray['vertical-repeat']."&quot;)";

					}

				}elseif($metaArray['calculator-used']=='pinch-pleated'){

					$out .= $productMap[$metaArray['calculator-used']];
					
					$out .= "<br>(".$thisClass." / ".$thisSubClass.")";

					if($metaArray['unit-of-measure'] == 'pair'){

						$out .= "<br>Pair";

					}elseif($metaArray['unit-of-measure'] == 'panel'){

						$out .= "<br>".$metaArray['panel-type']." Panel";

					}

					if($metaArray['railroaded'] == '1'){

						$out .= "<br>Fabric Railroaded";

					}else{

						$out .= "<br>Fabric Vertically Seamed";

					}

					if(isset($metaArray['linings_id']) && floatval($metaArray['linings_id']) >0){

						$thisLining=$this->Linings->get($metaArray['linings_id'])->toArray();

						$out .= "<br><b>Lining:</b> ".$thisLining['short_title'];

					}

					$out .= "<br><b>Hardware:</b> ".ucfirst($metaArray['hardware']);

					if($metaArray['trim-sewn-on'] == '1'){

						$out .= "<br>".$metaArray['trim-lf']." LF Sewn-On Trim";

					}

					if($metaArray['tassels'] == '1'){

						$out .= "<br>".$metaArray['tassels-count']." Tassels";

					}

				}
            
			}elseif($metaArray['lineitemtype'] == 'newcatchall'){
            
                if($mode=='workorder'){
                    if(isset($metaArray['line_item_title'])){
                        $out .= $metaArray['line_item_title'];
                    }else{
                        $out .= $quoteItem['title'];
                    }
                }else{
                    $out .= $quoteItem['title'];
                }
                $out .= "<br>(".$thisClass." / ".$thisSubClass.")";
                
                if($mode=='workorder'){
                    if(isset($metaArray['description'])){
                        $out .= "<br>".$metaArray['description'];
                    }else{
                        $out .= "<br>".$quoteItem['description'];
                    }
                }else{
                    $out .= "<br>".$quoteItem['description'];
                }
                
                $out .= "<br>".nl2br($metaArray['specs']);
                
            
			}elseif($metaArray['lineitemtype']=='simpleproduct'){

				switch($quoteItem['product_type']){

					case "cubicle_curtains":

						$out .= "Price List CC";
                        
                        $out .= "<br>(".$thisClass." / ".$thisSubClass.")";
                        
						if($thisFabric['railroaded'] == '1'){

							$out .= "<br>Fabric Railroaded";

						}else{

							$out .= "<br>Fabric Vertically Seamed";

						}

						if($metaArray['mesh'] > 0 && $metaArray['mesh'] != ''){

							$out .= "<br><b>Mesh:</b> ".$metaArray['mesh']."&quot; ".$metaArray['mesh-color']." (MOM)";

						}

						if($metaArray['mesh'] == 'No Mesh' || $metaArray['mesh'] == '0'){

							$out .= "<br>NO MESH";

						}

						if(floatval($thisFabric['vertical_repeat']) > 0){

							$out .= "<br>Matched Repeat (".$thisFabric['vertical_repeat']."&quot;)";

						}

					break;

					case "bedspreads":

						$out .= "Price List BS";
						
						$out .= "<br>(".$thisClass." / ".$thisSubClass.")";

						$thisBS=$this->Bedspreads->get($quoteItem['product_id'])->toArray();

						if($thisFabric['railroaded'] == '1'){

							$out .= "<br>Fabric Railroaded";

						}else{

							$out .= "<br>Fabric Vertically Seamed";

						}

						$out .= "<br>Style: ";

						$styleval=explode(" (",$metaArray['style']);

						$out .= $styleval[0];

						if($thisBS['quilted']=='1'){

							$out .= "<br>Quilted, Double Onion, ".$thisFabric['bs_backing_material']." Backing";

						}else{

							$out .= "<br>Unquilted";

						}

						

						

						$out .= "<br>Mattress: ";

						if(!isset($metaArray['custom-top-width-mattress-w'])){

							$out .= "36&quot;";

						}else{

							$out .= $metaArray['custom-top-width-mattress-w']."&quot;";

						}

						

						

						

						if(floatval($thisFabric['vertical_repeat']) > 0){

							$out .= "<br>Matched Repeat (".$thisFabric['vertical_repeat']."&quot;)";

						}

					break;

					case "services":

						$out .= $quoteItem['title'];
						
						$out .= "<br>(".$thisClass." / ".$thisSubClass.")";

					break;

					case "window_treatments":

						if($metaArray['wttype']=='Pinch Pleated Drapery'){

							$out .= "<b>".ucfirst($metaArray['unit-of-measure'])."</b><br>";

						}

						$out .= 'Price List '.$metaArray['wttype'];
						
						$out .= "<br>(".$thisClass." / ".$thisSubClass.")";
						

						if(preg_match("#Cornice#i",$metaArray['wttype']) || preg_match("#Valance#i",$metaArray['wttype'])){

							$out .= "<br>Fabric Vertically Seamed";

						}

						if(preg_match("#Valance#i",$metaArray['wttype'])){

							$out .= "<br>".$metaArray['pleats']." Pleats";

						}

						if(isset($metaArray['linings_id']) && floatval($metaArray['linings_id']) >0){

							$thisLining=$this->Linings->get($metaArray['linings_id'])->toArray();

							$out .= "<br><b>Lining:</b> ".$thisLining['short_title'];

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

								$out .= "<br><b>Welts:</b> ".$welts;

							}else{

								$out .= "<br><b>Welts:</b> None";

							}

						}

						

					break;

					case "track_systems":

						$out .= "<b>".$quoteItem['title']."</b>";
						
						$out .= "<br>(".$thisClass." / ".$thisSubClass.")";

						//$out .= "<br>";

						if(isset($metaArray['_component_numlines']) && intval($metaArray['_component_numlines']) >0){

							/*

							$out .= "<table width=\"100%\" cellpadding=\"2\" cellspacing=\"0\" border=\"1\" bordercolor=\"#333333\">

							<thead><tr><th width=\"20%\">QTY</th><th width=\"20%\">IN</th><th width=\"60%\">COMPONENT</th></tr></thead><tbody>";

							for($si=1; $si <= intval($metaArray['_component_numlines']); $si++){

								$out .= "<tr><td>".$metaArray["_component_".$si."_qty"]."</td><td>".$metaArray["_component_".$si."_inches"]."</td><td>";

								$thisComponentItem=$this->TrackSystems->get($metaArray["_component_".$si."_componentid"])->toArray();

								$out .= $thisComponentItem['title'];

								$out .= "</td></tr>";

							}

							$out .= "</tbody></table>";

							*/

							$out .= "<br><button style=\"padding:4px; border:1px solid #000; background:#CCC; color:#000; font-size:11px;\" onclick=\"loadTrackBreakdown('".$quoteItem['id']."');\" type=\"button\">List Components</button>";

						}

					break;

				}

			}elseif($metaArray['lineitemtype']=='custom'){

				$out .= "<b>".$quoteItem['title']."</b>";
				
				$out .= "<br>(".$thisClass." / ".$thisSubClass.")";

				$out .= "<br>".$quoteItem['description'];

				$out .= "<br>".nl2br($metaArray['specs']);

			}

			$out .= "</td>";

			//end column "line item"

			

			

			//begin column "image"

			$out .= "<td valign=\"top\" width=\"10%\" style=\"text-align:center;\">";

			if($quoteItem['product_type'] == 'services'){

				$out .= '---';

			}elseif($quoteItem['product_type'] == 'track_systems'){

				$thisTrackSystem=$this->TrackSystems->get($quoteItem['product_id'])->toArray();

				$out .= "<img src=\"/files/track-systems/".$thisTrackSystem['id']."/".$thisTrackSystem['primary_image']."\" width=\"210\" />";

			}else{

				if(isset($metaArray['libraryimageid']) && strlen(trim($metaArray['libraryimageid'])) >0){

					if($metaArray['libraryimageid']=='0' || $metaArray['libraryimageid'] == ''){

						$image='';

						$imagesrc='';

					}else{

						$image=$this->LibraryImages->get($metaArray['libraryimageid'])->toArray();

						$imagesrc="/img/library/".$image['filename'];

						$out .= "<img src=\"".$imagesrc."\" width=\"210\" />";

					}

					

					

					if($thisQuote['status']=="draft" || $thisQuote['status'] == 'emptyorder'){

						$out .= "<br><a href=\"javascript:doChangeLineItemImage('".$quoteItem['id']."');\">Change Image</a>";

					}

					

				}else{

					if($thisQuote['status']=="draft" || $thisQuote['status'] == 'emptyorder'){

						$out .= "<img src=\"/img/noimage.png\" width=\"210\" /><br><a href=\"javascript:doChangeLineItemImage('".$quoteItem['id']."');\">Set Image</a>";

					}

				}

			}

			$out .= "</td>";

			//end column "image"

			

			//begin column "fabric"
			$fabricFR='';
			if(isset($thisFabric['flammability']) && strlen(trim($thisFabric['flammability'])) >0){
				$fabricFR='<br><b>FR: '.$thisFabric['flammability'].'</b>';
			}

			if($quoteItem['product_type'] == 'track_systems'){

				$out .= "<td valign=\"top\" width=\"5%\">";

				$out .= '---';

			}elseif($quoteItem['product_type'] == 'custom'){

				$out .= "<td valign=\"top\" width=\"5%\">";

				if(isset($metaArray['com-fabric']) && $metaArray['com-fabric'] == "1"){

					$out .= "COM ";

				}

				if($fabricAlias=$this->getFabricAlias($metaArray['fabricid'],$thisQuote['customer_id'])){

					if(isset($metaArray['usealias']) && $metaArray['usealias'] == 'yes'){

						$out .= $fabricAlias['fabric_name']."<br>".$metaArray['fabric_name'].$fabricFR."</td>";

					}else{

						$out .= $metaArray['fabric_name']."<br><em>(".$fabricAlias['fabric_name'].")</em>".$fabricFR."</td>";

					}

				}else{

					$out .= $metaArray['fabric_name'].$fabricFR."</td>";

				}

			}else{

				

				$out .= "<td valign=\"top\" width=\"5%\">";

				if(isset($metaArray['com-fabric']) && $metaArray['com-fabric'] == "1"){

					$out .= "COM ";

				}

				if($fabricAlias=$this->getFabricAlias($thisFabric['id'],$thisQuote['customer_id'])){

					if(isset($metaArray['usealias']) && $metaArray['usealias'] == 'yes'){

						$out .= "<b>".$fabricAlias['fabric_name']."</b><br>".$thisFabric['fabric_name'];

					}else{

						$out .= $thisFabric['fabric_name']."<br><em>(".$fabricAlias['fabric_name'].")</em>";

					}

				}else{

					$out .= $thisFabric['fabric_name'];

				}

				$out .= $fabricFR;
                $lineItemMetaTable=TableRegistry::get('QuoteLineItemMeta');

			$lineItemMetaLookup=$this->QuoteLineItemMeta->find('all',['conditions'=>['quote_item_id'=>$quoteItem['id'], 'meta_key'=>'fabric-cost-per-yard-custom-value']])->toArray();
			if($lineItemMetaLookup != null && $lineItemMetaLookup[0]['meta_value'] > 0){
                $out .= "<br/><b style=' color: red;'>Special cost p/yd  $".$lineItemMetaLookup[0]['meta_value']."</b>";
			}
				$out .= "</td>";

				

			}

			//end column "fabric"

			

			//begin column "color"

			if($quoteItem['product_type'] == 'track_systems'){

				$out .= "<td valign=\"top\" width=\"5%\">";

				$out .= '---';

			}elseif($quoteItem['product_type'] == 'custom'){

				$out .= "<td valign=\"top\" width=\"5%\">";

				if($fabricAlias=$this->getFabricAlias($metaArray['fabricid'],$thisQuote['customer_id'])){

					if(isset($metaArray['usealias']) && $metaArray['usealias'] == 'yes'){

						$out .= "<b>".$fabricAlias['color']."</b><br>".$metaArray['fabric_color'];	

					}else{

						$out .= $metaArray['fabric_color']."<br><em>(".$fabricAlias['color'].")</em>";

					}

				}else{

					$out .= $metaArray['fabric_color'];

				}

				$out .= "</td>";

			}else{

				

				$out .= "<td valign=\"top\" width=\"5%\">";

				if($fabricAlias=$this->getFabricAlias($thisFabric['id'],$thisQuote['customer_id'])){

					if(isset($metaArray['usealias']) && $metaArray['usealias'] == 'yes'){

						$out .= "<b>".$fabricAlias['color']."</b><Br>".$thisFabric['color'];

					}else{

						$out .= $thisFabric['color']."<br><em>(".$fabricAlias['color'].")</em>";

					}

				}else{

					$out .= $thisFabric['color'];

				}

				$out .= "</td>";

				

			}

			//end column "color"

			

			//begin column "width"

			$out .= "<td valign=\"top\" width=\"3%\">";

			switch($quoteItem['product_type']){
			    
			    case 'track_systems':
        			$out .= '---';
        		break;
        		case "bedspreads":
        		    $out .= number_format(floatval($metaArray['width']),0,'','')."&quot;";
        		break;
        		case "cubicle_curtains":
    				$out .= number_format(floatval($metaArray['width']),0,'','')."&quot; (Cut)";
    				if(isset($metaArray['expected-finish-width']) && strlen(trim($metaArray['expected-finish-width'])) >0){
    					$out .= "<br>".$metaArray['expected-finish-width']."&quot; (Fin)";
	    			}
    			break;
    	        case 'window_treatments':

    				if($metaArray['wttype'] == 'Pinch Pleated Drapery'){
    					$out .= number_format(floatval($metaArray['rod-width']),0,'','')."&quot;";
    					$out .= " (Rod)";
    					$out .= "<br>".$metaArray['default-return']."&quot; (Return)";
    				}elseif(preg_match("#Cornice#i",$metaArray['wttype']) || preg_match("#Valance#i",$metaArray['wttype'])){
    					$out .= number_format(floatval($metaArray['width']),0,'','')."&quot;";
    					$out .= " (Face)";
    					if(isset($metaArray['return']) && floatval(trim($metaArray['return'])) >0){
    						$out .= "<br>".$metaArray['return']."&quot; (Return)";
    					}else{
    						$out .= "<br>No Return";
    					}
    
    				}
    			break;
    			case 'newcatchall-bedding':
        		case 'newcatchall-blinds':
                case 'newcatchall-cornice':
                case 'newcatchall-cubicle':
                case 'newcatchall-drapery':
                case 'newcatchall-hwtmisc':
                case 'newcatchall-misc':
                case 'newcatchall-service':
                case 'newcatchall-shades':
                case 'newcatchall-shutters':
                case 'newcatchall-swtmisc':
                case 'newcatchall-valance':
					if($quoteItem['product_type'] == 'newcatchall-cubicle' && isset($metaArray['width'])){
						$out .= number_format(floatval($metaArray['width']),0,'','')."&quot; (Cut)";
					} else if(isset($metaArray['width'])){
    			        $out .= $metaArray['width']."&quot;";
    			    }
    			    if($quoteItem['product_type'] == 'newcatchall-cubicle' && isset($metaArray['expected-finish-width']) && strlen(trim($metaArray['expected-finish-width'])) >0){
    					$out .= "<br>".$metaArray['expected-finish-width']."&quot; (Fin)";
	    			}
    			    if(isset($metaArray['face'])){
    			        $out .= $metaArray['face']."&quot; (Face)";
    			    }
    			    
    			    if(isset($metaArray['rod-width'])){
    			        $out .= $metaArray['rod-width']."&quot; (Rod Width)";
    			    }
    			    
    			    if(isset($metaArray['fullness']) && strlen(trim($metaArray['fullness'])) >0){
						$out .= "<br>".$metaArray['fullness']."% Fullness";
					}

					if(isset($metaArray['default-return']) && strlen(trim($metaArray['default-return'])) >0){
						$out .= "<br>".$metaArray['default-return']."&quot; Ret";
					}

					if(isset($metaArray['default-overlap']) && strlen(trim($metaArray['default-overlap'])) >0){
						$out .= "<br>".$metaArray['default-overlap']."&quot; Ovrlp";
					}
    			    
    			break;
    			case 'newcatchall-hardware':
    			    $out .= $metaArray['finished-width']."&quot; (Rod Width)";
    			break;
    			default:

    				if($metaArray['calculator-used']=="box-pleated"){
    
    					if(isset($metaArray['face']) && strlen(trim($metaArray['face'])) >0){
    
    						$out .= $metaArray['face']."&quot; (Face)";
    
    					}
    
    					if(isset($metaArray['return']) && floatval(trim($metaArray['return'])) >0){
    
    						$out .= "<br>".$metaArray['return']."&quot; (Return)";
    
    					}else{
    
    						$out .= "<br>No Return";
    
    					}
    
    				}elseif($metaArray['calculator-used']=="bedspread" || $metaArray['calculator-used']=="bedspread-manual"){
    
    					if(isset($metaArray['width']) && strlen(trim($metaArray['width'])) >0){
    
    						$out .= $metaArray['width']."&quot; (Finished)";
    
    					}
    
    				}elseif($metaArray['calculator-used']=="cubicle-curtain"){
    
    					if(isset($metaArray['width']) && strlen(trim($metaArray['width'])) >0){
    
    						$out .= $metaArray['width']."&quot; (Cut)";
    
    					}
    
    					if(isset($metaArray['expected-finish-width']) && strlen(trim($metaArray['expected-finish-width'])) >0){
    
    						$out .= "<br>".$metaArray['expected-finish-width']."&quot; (Fin)";
    
    					}else{
    
    						if(isset($metaArray['fw']) && strlen(trim($metaArray['fw'])) >0){
    
    							$out .= "<br>".$metaArray['fw']."&quot; (Fin)";
    
    						}
    
    					}
    
    				}elseif($metaArray['calculator-used']=="straight-cornice"){
    
    					if(isset($metaArray['face']) && strlen(trim($metaArray['face'])) >0){
    
    						$out .= $metaArray['face']."&quot; (Face)";
    
    					}
    
    					if(isset($metaArray['return']) && floatval(trim($metaArray['return'])) >0){
    
    						$out .= "<br>".$metaArray['return']."&quot; (Return)";
    
    					}else{
    
    						$out .= "<br>No Return";
    
    					}
    
    				}elseif($metaArray['calculator-used'] == 'pinch-pleated'){
    
    					if($metaArray['unit-of-measure'] == 'pair'){
    
    						if(isset($metaArray['rod-width']) && strlen(trim($metaArray['rod-width'])) >0){
    
    							$out .= $metaArray['rod-width']."&quot; (Rod Width)";
    
    						}
    
    					}else{
    
    						if(isset($metaArray['fabric-widths-per-panel'])){
    
    							$out .= $metaArray['fabric-widths-per-panel']." Widths";
    
    						}
    
    					}
    
    					if(isset($metaArray['fullness']) && strlen(trim($metaArray['fullness'])) >0){
    
    						$out .= "<br>".$metaArray['fullness']."% Fullness";
    
    					}
    
    					if(isset($metaArray['default-return']) && strlen(trim($metaArray['default-return'])) >0){
    
    						$out .= "<br>".$metaArray['default-return']."&quot; Ret";
    
    					}
    
    					if(isset($metaArray['default-overlap']) && strlen(trim($metaArray['default-overlap'])) >0){
    
    						$out .= "<br>".$metaArray['default-overlap']."&quot; Ovrlp";
    
    					}
    
    				}
                break;
			}

			

			$out .= "</td>";

			//end column "width"

			

			

			//begin column "length"

			$out .= "<td valign=\"top\" width=\"3%\">";
            
            switch($quoteItem['product_type']){
                case 'track_systems':
    				$out .= '---';
    				
    			break;
                default:
			

    				if($metaArray['calculator-used']=="box-pleated"){
    
    					$out .= $metaArray['height']."&quot; (Height)";
    
    				}elseif($metaArray['calculator-used']=="straight-cornice"){
    
    					$out .= $metaArray['height']."&quot; (Height)";
    
    				}else{
    
    					if(isset($metaArray['length'])){
    					    $out .= $metaArray['length']."&quot;";
    					}elseif(isset($metaArray['height'])){
    					    $out .= $metaArray['height']."&quot; (Height)";
    					}
    
    					if($quoteItem['product_type'] == 'window_treatments' && (preg_match("#Cornice#i",$metaArray['wttype']) || preg_match("#Valance#i",$metaArray['wttype']))){
    
    						$out .= " (Height)";
    
    					}
    
    				}
    
    				if(isset($metaArray['fl-short']) && floatval($metaArray['fl-short']) >0){
    
    					$out .= "<br>".$metaArray['fl-short']."&quot; (Short Point)";
    
    				}
    				
    				if(isset($metaArray['fl-mid']) && floatval($metaArray['fl-mid']) > 0){
    				    $out .= "<Br>".$metaArray['fl-mid']."&quot; (Mid Point)";
    				}
    				
    			break;
            }

			

			$out .= "</td>";

			//end column "length"
			
			
			

			//begin column "LF"

			$out .= "<td valign=\"top\" width=\"4%\">";

			if($quoteItem['product_type'] == 'track_systems'){

				

				if(isset($metaArray['_component_lf_total']) && strlen(trim($metaArray['_component_lf_total'])) > 0){

					$totalTrackLF = ($totalTrackLF + floatval($metaArray['_component_lf_total']));

					if(floatval($metaArray['_component_lf_total']) > floatval($quoteItem['qty'])){

						$differenceMath=(floatval($metaArray['_component_lf_total'])-floatval($quoteItem['qty']));

						$differencePercent=(($differenceMath / floatval($quoteItem['qty']))*100);

						$differencePlusMinus='+';

					}elseif(floatval($metaArray['_component_lf_total']) < floatval($quoteItem['qty'])){

						$differenceMath=(floatval($quoteItem['qty'])-floatval($metaArray['_component_lf_total']));

						$differencePercent=(($differenceMath / floatval($quoteItem['qty']))*100);

						$differencePlusMinus='-';

					}else{

						$differenceMath=0;

						$differencePercent=0;

						$differencePlusMinus='';

					}

					$out .= $quoteItem['qty']."LF (Quoted)<br><span style=\"color:blue;\">".$metaArray['_component_lf_total']." LF (WO)</span>";

					if($differencePercent > 12){

						$out .= "<br><span style=\"color:red;\"><img src=\"/img/alert.png\" width=\"14\" height=\"14\" /><b>".$differencePlusMinus.round($differencePercent,1)."%</b><br>Check with estimator</span>";

					}

				}else{

					if($quoteItem['unit'] == 'linear feet'){

						$totalTrackLF = ($totalTrackLF + floatval($quoteItem['qty']));

						$out .= $quoteItem['qty'].' LF';

					}else{

						$out .= '---';

					}

				}

			}else{

				if(isset($metaArray['labor-billable']) && isset($metaArray['qty'])){

					$out .= (floatval($metaArray['labor-billable'])*floatval($metaArray['qty']));

				}else{

					$out .= "---";

					//$out .= "<pre>".print_r($metaArray,1)."</pre>";

				}

			}

			$out .= "</td>";

			//end column "LF"

			

			

			//begin column "yds per unit"

			$out .= "<td valign=\"top\" width=\"5%\">";

			if(isset($metaArray['yds-per-unit'])){

				$out .= $metaArray['yds-per-unit'];

			}else{
				$out .= "---";

			}

			

			$out .= "</td>";

			//end column "yds per unit"

			

			

			//begin column "total yds"

			if($mode=='workorder'){
			    $out .= "<td valign=\"top\" width=\"21%\">";
			}else{
			    $out .= "<td valign=\"top\" width=\"6%\">";
			}

			if($metaArray['lineitemtype'] == 'simpleproduct' || $metaArray['lineitemtype'] == 'newcatchall'){

				//if(isset($metaArray['total-yds'])){

				//	$out .= $metaArray['total-yds'];

				//}else{

					$out .= (floatval($metaArray['yds-per-unit']) * floatval($metaArray['qty']));

				//}

			}elseif($metaArray['lineitemtype']=="custom"){

				if(floatval($metaArray['total-yds']) >0){

					$out .= $metaArray['total-yds'];

				}else{

					$out .= (floatval($metaArray['yds-per-unit']) * floatval($quoteItem['qty']))." yds";

				}

			}else{

				$out .= $metaArray['total-yds']." yds";

			}

			if(isset($metaArray['total-widths']) && floatval($metaArray['total-widths']) >0.00){

				$out .= "<br>(".$metaArray['total-widths']." widths @ ".$metaArray['cl']." CL)";

			}

			$out .= "</td>";

			//end column "total yds"

			

			

			

			/*

			$bestprice=floatval($metaArray['price']);

			$tieradjusted=$this->tieradjustment($quoteItem,$thisCustomer,$bestprice);

			$installadjusted=$this->installadjustment($quoteItem,$thisCustomer,$tieradjusted);

			$rebateadjusted=$this->rebateadjustment($quoteItem,$thisCustomer,$installadjusted);

			*/

			$bestprice=$quoteItem['best_price'];

			$installadjustmentpercentage=0;

			$tieradjustmentpercentage=0;

			$tierDiscountOrPremium='Disc';

			$rebateadjustmentpercentage=0;

			if($metaArray['specialpricing']=='1'){

				

				$tieradjusted=$quoteItem['best_price'];

				$tieradjustmentpercentage=0;

				$tierDiscountOrPremium='Disc';

				$installadjusted=$quoteItem['best_price'];

				$installadjustmentpercentage=0;

				$rebateadjusted=$quoteItem['best_price'];

				$rebateadjustmentpercentage=0;

				$pmiadjusted=$quoteItem['pmi_adjusted'];

				$pmiadjustmentdollars=0;

				if($quoteItem['override_active'] == 1){

					$extendedprice=(floatval($quoteItem['override_price']) * floatval($quoteItem['qty']));

				}else{

					$extendedprice=(floatval($quoteItem['best_price'])*floatval($quoteItem['qty']));

				}

			}else{

				

				$tieradjusted=$quoteItem['tier_adjusted_price'];

				$tieradjustmentpercentage=$this->tieradjustment($quoteItem,$thisCustomer,$rebateadjusted,true);

				$tierDiscountOrPremium=$this->tieradjustment($quoteItem,$thisCustomer,$rebateadjusted,false,true);

				$installadjusted=$quoteItem['install_adjusted_price'];

				$installadjustmentpercentage=$this->installadjustment($quoteItem,$thisCustomer,$tieradjusted,true);

				$rebateadjusted=$quoteItem['rebate_adjusted_price'];

				$rebateadjustmentpercentage=$this->rebateadjustment($quoteItem,$thisCustomer,$installadjusted,true);

				$pmiadjusted=$quoteItem['pmi_adjusted'];

				$pmiadjustmentdollars=$this->pmiadjustment($quoteItem,$thisCustomer,$rebateadjusted,true);

				$extendedprice=$quoteItem['extended_price'];
				/*if($quoteItem['override_active'] == 1){

					$extendedprice=(floatval($quoteItem['override_price']) * floatval($quoteItem['qty']));

				}else{
				    $extendedprice=($pmiadjusted*$quoteItem['qty']);
				}*/

			}


            if($mode != 'workorder'){
    			//begin column "best"
    
    			$out .= "<td valign=\"top\" width=\"6%\">".number_format($bestprice,2,'.',',');
    
    			if($metaArray['specialpricing']=='1'){
    
    				$out .= "<br><span style=\"color:red;\"><b><em>SPECIAL PRICING</em></b></span>";
    
    			}else{
    
    				$out .= "<span style=\"color:blue;\"><br>".$tieradjusted." Tier (";
    
    				switch($tierDiscountOrPremium){
    
    					case "premium":
    
    						$out .= '+'.$tieradjustmentpercentage."% Prem";
    
    					break;
    
    					case "discount":
    
    						$out .= '-'.$tieradjustmentpercentage."% Disc";
    
    					break;
    
    				}
    
    				$out .= ")<br>".$installadjusted." INST (+".$installadjustmentpercentage."%)<br>".$rebateadjusted." ADD ";
    
    				$out .= "(+".$rebateadjustmentpercentage."%)";
    
    				$out .= "<br>".$pmiadjusted." PMI (+\$".$pmiadjustmentdollars.")</span>";
    
    			}
    
    			$out .= "</td>";
    
    			//end column "best"
    
    			
    
    			//begin column "adjusted"
    
    			$out .= "<td valign=\"top\" class=\"adjustedprice\" width=\"4%\">";
    
    			if($thisQuote['status'] == 'published' || $thisQuote['status'] == 'orderplaced'){
    
    				$out .= '$';
    
    				if($quoteItem['override_active'] == 1){
    
    					$out .= number_format(floatval($quoteItem['override_price']),2,'.','');
    
    				}else{
    
    					$out .= number_format(floatval($pmiadjusted),2,'.','');
    
    				} 	
    
    			}else{
    
    				$out .= "<input type=\"number\" id=\"lineitem_".$quoteItem['id']."_adjusted\" value=\"";
    
    				if($quoteItem['override_active'] == 1){
    
    					$out .= number_format(floatval($quoteItem['override_price']),2,'.','');
    
    				}else{
    
    					$out .= number_format(floatval($pmiadjusted),2,'.','');
    
    				}
    
    				$out .= "\" step=\"any\" data-lineitemid=\"".$quoteItem['id']."\" class=\"lineitempriceoverride\" />";
    
    			}
    
    			if(($thisQuote['status'] != 'published' && $thisQuote['status'] != 'orderplaced') && ($quoteItem['override_active'] == 1  && floatval($quoteItem['override_price']) != floatval($quoteItem['pmi_adjusted']))){
    
    				$out .= "<div class=\"priceoverriden\"><a href=\"javascript:removeOverridePrice('".$quoteItem['id']."')\">Reset to <b>".number_format($quoteItem['pmi_adjusted'],2,'.','')."</b></a></div>";
    
    			}
    
    			$out .= "</td>";
    
    			//end column "adjusted"
    
    			
    
    			//begin column "extended price"
    
    			$out .= "<td valign=\"top\" class=\"extendedprice\" width=\"5%\">";
    
    			
    
    			$out .= number_format($extendedprice,2,'.',',')."</td>";
    
    			//end column "extended price"

            }

			$out .= "</tr>";

						

			//load notes row if needed

			$notes=$this->QuoteLineItemNotes->find('all',['conditions'=>['quote_item_id' => $quoteItem['id']],'order'=>['id'=>'asc']])->toArray();

			$notecount=0;

			$notetotal=count($notes);

			foreach($notes as $note){

				$out .= "<tr class=\"".$thisrowbg." quotelinenotes\" data-lineitemid=\"".$quoteItem['id']."\">";

				if($notecount==0){

					$out .= "<td colspan=\"4\" valign=\"top\" style=\"text-align:right;\" rowspan=\"".$notetotal."\"><b>Line Notes:</b></td>";

				}

				$noteauthor=$this->Users->get($note['user_id'])->toArray();

				$out .= "<td colspan=\"14\" valign=\"top\">";

                //if($thisQuote['status'] == 'draft'){
                    $out .= "<a href=\"/quotes/deletelinenote/".$note['id']."\"><img src=\"/img/delete.png\" alt=\"Delete This Line Note\" title=\"Delete This Line Note\" /></a> ";
                //}
                
				if($note['visible_to_customer']==0){

					$out .= "[Internal] ";

				}

				$out .= nl2br($note['message'])." <em style=\"color:blue;\">".$noteauthor['first_name']." ".substr($noteauthor['last_name'],0,1)." @ ".date('n/j/y g:iA',$note['time'])."</em></td></tr>";

				$notecount++;

			}

			

			$out .= "</table>";

			

			$out .= "</td>";

			$out .= "</tr>";

			

			

			

			$overallSubtotal=($overallSubtotal+$extendedprice);

			$overallTotalDue=($overallTotalDue+$extendedprice);

			

			$rowNum++;

			

			

		}

		

		$out .= "<script>

			$('input.linenumber').keyup($.debounce( 650, function(){

				updatelinenumber($(this).attr('data-itemid'),$(this).val());

			}));

			

			$('input.roomnumber').keyup($.debounce( 650, function(){

				updateroomnumber($(this).attr('data-itemid'),$(this).val());

			}));

			

			$('#quoteitems tbody tr').hover(function(){

				$('tr[data-lineitemid=\''+$(this).attr('data-lineitemid')+'\']').addClass('hoverRow');

			},function(){

				$('tr[data-lineitemid=\''+$(this).attr('data-lineitemid')+'\']').removeClass('hoverRow');

			})
		
            $('#quoteitems tbody tr td.adjustedprice input').on('keyup click change', $.debounce( 1000, function(){
            
            				changeLineItemPrice($(this).attr('data-lineitemid'),$(this).val());
            
            			})
            );


			</script>";

		

		$surchargePercent=($thisCustomer['surcharge_percent']/100);

		$surchargeAmount=0;//($overallSubtotal*$surchargePercent);

		$overallTotalDue=($overallSubtotal+$surchargeAmount);

		

		$quoteDiscounts=$this->QuoteDiscounts->find('all',['conditions'=>['quote_id'=>$thisQuote['id']]])->toArray();

		

		foreach($quoteDiscounts as $discount){

			switch($discount['discount_math']){

				case "percent":

					$discountcalc=round(($overallSubtotal * (floatval($discount['discount_amount'])/100)),2);

					$overallDiscount=($overallDiscount+$discountcalc);

				break;

				case "dollars":

					$overallDiscount=($overallDiscount+floatval($discount['discount_amount']));

				break;

			}

		}

		

		

		$overallTotalDue=($overallTotalDue-$overallDiscount);

		

		//update the quote row

		$quoteTable=TableRegistry::get('Quotes');

        
    	$thisQuoteRow=$quoteTable->get($thisQuote['id']);
        
		

		if($thisQuote->quote_subtotal != $overallSubtotal || $thisQuote->quote_discount != $overallDiscount || $thisQuote->quote_total != $overallTotalDue){

			$thisQuoteRow->quote_subtotal=number_format($overallSubtotal,2,'.','');

			$thisQuoteRow->quote_surcharge=number_format($surchargeAmount,2,'.','');

			$thisQuoteRow->quote_discount=number_format($overallDiscount,2,'.','');

			$thisQuoteRow->quote_total=number_format($overallTotalDue,2,'.','');

			$quoteTable->save($thisQuoteRow);

		}

		

		

		$out .= "</tbody></table>";

//sortable

		$out .= "<script>$(function(){

			$('table#quoteitems > tbody').sortable({

				'handle':'.movehandle',

				'items':'tr.quotelineitem',

				'update': function(event,ui){

					$.fancybox.showLoading();

					var newsortorder=encodeURIComponent($('table#quoteitems > tbody').sortable('toArray'));

					$.get('/quotes/sortlineitems/".$thisQuote['id']."/'+newsortorder,function(data){

						if(data==\"OK\"){

							$.fancybox.hideLoading();

							updateQuoteTable();

						}

					});

				}

			});

		});</script>";


		$out .= "<div id=\"quotenotes\"><h4>GLOBAL NOTES: <a href=\"javascript:addGlobalNote(".$thisQuote['id'].");\"><img src=\"/img/stickynote.png\" alt=\"Add Global Note\" title=\"Add Global Note\" /></a></h4>";
		$globalNotes=$this->QuoteNotes->find('all',['conditions' => ['quote_id' => $thisQuote['id']],'order' => ['id'=>'asc']])->toArray();
		if(count($globalNotes) >0){
			$out .= "<table width=\"1000\" style=\"background:#f8f8f8 !important; width:1000px !important;\" cellpadding=\"0\" cellspacing=\"0\" border=\"1\" bordercolor=\"#000066\">";
		}
		foreach($globalNotes as $noteRow){
			$out .= "<tr><td width=\"10%\" align=\"right\" valign=\"top\" style=\"font-size:12px;\">";
			$noteUser=$this->Users->get($noteRow['user_id'])->toArray();
			$out .= "<b>".$noteUser['first_name']." ".substr($noteUser['last_name'],0,1).":</b></td>
			<td style=\"font-size:12px;\" valign=\"top\" align=\"left\" width=\"10%\">";
			if($noteRow['appear_on_pdf'] == '1'){
				$out .= "<em>[PUBLIC]</em>";
			}else{
				$out .= "<em>[INTERNAL]</em>";
			}
			$out .= "</td>
			<td style=\"font-size:12px;\" valign=\"top\" align=\"left\" width=\"80%\"><a href=\"javascript:deleteGlobalNote('".$noteRow['id']."')\"><img src=\"/img/delete.png\" alt=\"Delete This Global Note\" title=\"Delete This Global Note\" /></a> ";
			
			$out .= nl2br($noteRow['note_text']);
			$out .= "</td></tr>";
		}
		if(count($globalNotes) >0){
			$out .= "</table>";
		}


		$out .= "<div id=\"belowtableactions\">";

        
		if($mode=='order' || $mode=='editorder'){


			if($thisQuote['status']=="draft" || $thisQuote['status'] == 'emptyorder' || $thisQuote['status'] == 'editorder'){
				

				$out .= "<div style=\"padding:0px 0; float:left; text-align:left;font-size:12px; \"></div>

				<div id=\"actions\">";

                $out .= "<div id=\"catchalllistbuttonwrap\"><div id=\"catchalllistpopup\"><ul>";
                $out .= "<li><a href=\"javascript:addcatchallitem('misc');\">Miscellaneous</a></li>";
                $out .= "<li><a href=\"javascript:addcatchallitem('hardware');\">Hardware</a></li>";
                $out .= "<li><a href=\"javascript:addcatchallitem('service');\">Services</a></li>";
                $out .= "<li><a href=\"javascript:addcatchallitem('bedding');\">Bedding</a></li>";
                $out .= "<li><a href=\"javascript:addcatchallitem('cubicle');\">Cubicle Curtains</a></li>";
                $out .= "<li class=\"hassubmenu\"><a href=\"#\">Soft Window Treatments</a><ul>";
                $out .= "<li><a href=\"javascript:addcatchallitem('valance');\">Valance Catch-All</a></li>";
                $out .= "<li><a href=\"javascript:addcatchallitem('drapery');\">Drapery Catch-All</a></li>";
                $out .= "<li><a href=\"javascript:addcatchallitem('cornice');\">Cornice Catch-All</a></li>";
                $out .= "<li><a href=\"javascript:addcatchallitem('swtmisc');\">SWT Misc Catch-All</a></li></ul></li>";
                $out .= "<li class=\"hassubmenu\"><a href=\"#\">Hard Window Treatments</a><ul>";
                $out .= "<li><a href=\"javascript:addcatchallitem('blinds');\">Blinds Catch-All</a></li>";
                $out .= "<li><a href=\"javascript:addcatchallitem('shades');\">Shades Catch-All</a></li>";
                $out .= "<li><a href=\"javascript:addcatchallitem('shutters');\">Shutters Catch-All</a></li>";
                $out .= "<li><a href=\"javascript:addcatchallitem('hwtmisc');\">HWT Misc Catch-All</a></li>";
                $out .= "</ul></li>";
                $out .= "</ul></div>";
				$out .= "<button type=\"button\" id=\"addcustom\">+ Add Catch-All</button> ";
				$out .= "</div>";

				//echo "<button type=\"button\" id=\"addsimple\">+ Add From Price List</button> ";

				$out .= "<div id=\"pricelistbuttonwrap\"><div id=\"pricelistpopup\">";

				$out .= "<a href=\"javascript:addpricelistitem('bedspread');\">Bedspreads</a>";

				$out .= "<a href=\"javascript:addpricelistitem('cubicle-curtain');\">Cubicle Curtains</a>";

				$out .= "<a href=\"javascript:addpricelistitem('services');\">Services</a>";

				$out .= "<a href=\"javascript:addpricelistitem('track-system');\">Track Systems</a>";

				//$out .= "<a href=\"javascript:addpricelistitem('window-treatment');\">Window Treatments</a>";

				$out .= "</div><button type=\"button\" id=\"addpricelist\">+ Add From Price List</button></div> ";

				$out .= "<div id=\"calculatorbuttonwrap\"><div id=\"calculatorpopup\">";

				foreach($allcalculators as $calculator){

					$out .= "<a href=\"javascript:addcalculatoritem('".$calculator['slug']."');\">".$calculator['name']."</a>";

				}

				$out .= "</div><button type=\"button\" id=\"addcalculator\">+ Add From Calculator</button></div> ";

				/*$out .= "<div id=\"manualbuttonwrap\"><div id=\"manualpopup\">";

				$out .= "<a href=\"javascript:addcalculatoritem('bedspread-manual');\">Bedspread</a>";

				$out .= "<a href=\"javascript:addcalculatoritem('valance-manual');\">Valances</a>";

				$out .= "<a href=\"javascript:addcalculatoritem('cubicle-manual');\">Cubicle Curtain</a>";

				$out .= "<a href=\"javascript:addcalculatoritem('cornice-manual');\">Cornice</a>";

				$out .= "</div><button type=\"button\" id=\"addmanual\">+ Manual Add</button></div> ";*/

				if($thisQuote['status'] != 'editorder'){
				    $out .= "<script>
				    function docompleteorderbutton(){
				        $('button#converttoorder').html('Processing...').prop('disabled',true);
				        location.replace('/orders/completeorder/".$thisQuote['id']."');
				    }
				    </script>
				    <button type=\"button\" onclick=\"docompleteorderbutton()\" id=\"converttoorder\"> Complete Order</button>";
				}
				$out .= "</div>";

				$out .= "</div>";

			

			}

			

		}else{

			if($thisQuote['status']=="draft"){

				$out .= "<div style=\"padding:0px 0; float:left; text-align:left;font-size:12px; \"></div>

				<div id=\"actions\">";

				//$out .= "<button type=\"button\" id=\"addcustom\" onclick=\"location.href='/quotes/newlineitem/".$thisQuote['id']."/custom';\">+ Add Catch-All</button> ";
				$out .= "<div id=\"catchalllistbuttonwrap\"><div id=\"catchalllistpopup\"><ul>";
                $out .= "<li><a href=\"javascript:addcatchallitem('misc');\">Miscellaneous</a></li>";
                $out .= "<li><a href=\"javascript:addcatchallitem('hardware');\">Hardware</a></li>";
                $out .= "<li><a href=\"javascript:addcatchallitem('service');\">Services</a></li>";
                $out .= "<li><a href=\"javascript:addcatchallitem('bedding');\">Bedding</a></li>";
                $out .= "<li><a href=\"javascript:addcatchallitem('cubicle');\">Cubicle Curtains</a></li>";
                $out .= "<li class=\"hassubmenu\"><a href=\"#\">Soft Window Treatments</a><ul>";
                $out .= "<li><a href=\"javascript:addcatchallitem('valance');\">Valance Catch-All</a></li>";
                $out .= "<li><a href=\"javascript:addcatchallitem('drapery');\">Drapery Catch-All</a></li>";
                $out .= "<li><a href=\"javascript:addcatchallitem('cornice');\">Cornice Catch-All</a></li>";
                $out .= "<li><a href=\"javascript:addcatchallitem('swtmisc');\">SWT Misc Catch-All</a></li></ul></li>";
                $out .= "<li class=\"hassubmenu\"><a href=\"#\">Hard Window Treatments</a><ul>";
                $out .= "<li><a href=\"javascript:addcatchallitem('blinds');\">Blinds Catch-All</a></li>";
                $out .= "<li><a href=\"javascript:addcatchallitem('shades');\">Shades Catch-All</a></li>";
                $out .= "<li><a href=\"javascript:addcatchallitem('shutters');\">Shutters Catch-All</a></li>";
                $out .= "<li><a href=\"javascript:addcatchallitem('hwtmisc');\">HWT Misc Catch-All</a></li>";
                $out .= "</ul></li>";
                $out .= "</ul></div>";
                $out .= "<button type=\"button\" id=\"addcustom\">+ Add Catch-All</button> ";
				$out .= "</div>";
				
                /*
				$out .= "<div id=\"catchalllistbuttonwrap\"><div id=\"catchalllistpopup\">";
                $out .= "<a href=\"javascript:addcatchallitem('bedding');\">Bedding Catch-All</a>";
                $out .= "<a href=\"javascript:addcatchallitem('cubicle');\">Cubicle Catch-All</a>";
                $out .= "<a href=\"javascript:addcatchallitem('cornice');\">Cornice Catch-All</a>";
                $out .= "<a href=\"javascript:addcatchallitem('drapery');\">Drapery Catch-All</a>";
                $out .= "<a href=\"javascript:addcatchallitem('valance');\">Valance Catch-All</a>";
                $out .= "</div>";
				$out .= "<button type=\"button\" id=\"addcustom\">+ Add Catch-All</button> ";
				$out .= "</div>";
*/
				//echo "<button type=\"button\" id=\"addsimple\">+ Add From Price List</button> ";

				$out .= "<div id=\"pricelistbuttonwrap\"><div id=\"pricelistpopup\">";

				$out .= "<a href=\"javascript:addpricelistitem('bedspread');\">Bedspreads</a>";

				$out .= "<a href=\"javascript:addpricelistitem('cubicle-curtain');\">Cubicle Curtains</a>";

				$out .= "<a href=\"javascript:addpricelistitem('services');\">Services</a>";

				$out .= "<a href=\"javascript:addpricelistitem('track-system');\">Track Systems</a>";

				//$out .= "<a href=\"javascript:addpricelistitem('window-treatment');\">Window Treatments</a>";

				$out .= "</div><button type=\"button\" id=\"addpricelist\">+ Add From Price List</button></div> ";

				$out .= "<div id=\"calculatorbuttonwrap\"><div id=\"calculatorpopup\">";

				foreach($allcalculators as $calculator){

					$out .= "<a href=\"javascript:addcalculatoritem('".$calculator['slug']."');\">".$calculator['name']."</a>";

				}

				$out .= "</div><button type=\"button\" id=\"addcalculator\">+ Add From Calculator</button></div> ";

				/*$out .= "<div id=\"manualbuttonwrap\"><div id=\"manualpopup\">";

				$out .= "<a href=\"javascript:addcalculatoritem('bedspread-manual');\">Bedspread</a>";

				$out .= "<a href=\"javascript:addcalculatoritem('valance-manual');\">Valances</a>";

				$out .= "<a href=\"javascript:addcalculatoritem('cubicle-manual');\">Cubicle Curtain</a>";

				$out .= "<a href=\"javascript:addcalculatoritem('cornice-manual');\">Cornice</a>";

				$out .= "</div><button type=\"button\" id=\"addmanual\">+ Manual Add</button></div> ";*/

				//$out .= "<button type=\"button\" id=\"addassembly\">+ Add Assembly</button>";

				$out .= "</div>";

			}else{
    
                if($mode == 'workorder'){
                    
                }else{
				    $out .= "<div id=\"actions\"><button type=\"button\" onclick=\"location.href='/quotes/newrevision/".$thisQuote['id']."';\" id=\"dorevision\"> New Revision</button> <button type=\"button\" id=\"doclone\" onclick=\"location.href='/quotes/clonequote/".$thisQuote['id']."';\"> Clone</button> <button type=\"button\" id=\"sendtocustomer\"> Send to Customer</button> <button type=\"button\" onclick=\"location.replace('/quotes/converttoorder/".$thisQuote['id']."');\" id=\"converttoorder\"> Quote Approved</button></div>";
                }

			}

		}

	

		$out .= "<div style=\"clear:both;\"></div>";

		$out .= "</div>";

		

		$out .= "<div id=\"bomtable\">

		<table width=\"100%\" cellpadding=\"5\" cellspacing=\"0\" border=\"0\">

		<tr>

		<td valign=\"top\" width=\"25%\">

				<h4 style=\"margin:0;\">PRODUCTION TOTALS:</h4>

		<table width=\"100%\" cellspacing=\"0\" cellpadding=\"5\" border=\"0\">

		<thead>

			<tr>

				<th>Product</th>

				<th>Labor Units</th>

				<th>Difficulty</th>

				<th>Weight</th>

			</tr>

		</thead>

		<tbody>

			<tr>

				<td>Bedspreads</td>

				<td>".$totalBSLF." BS</td>

				<td>".$totalDiffBS."</td>

				<td>".$totalWeightBS."</td>

			</tr>

			<tr>

				<td>Cubicle Curtains</td>

				<td>";

				$out .= $totalCCLF." LF";

				$out .= "</td>

				<td>".$totalDiffCC."</td>

				<td>".$totalWeightCC."</td>

			</tr>

			<tr>

				<td>Track Systems</td>

				<td>".$totalTrackLF." LF</td>

				<td>".$totalDiffTrack."</td>

				<td>".$totalWeightTrack."</td>

			</tr>

			<tr>

				<td>Cornices</th>

				<td>".$totalCornLF." LF</td>

				<td>".$totalDiffCorn."</td>

				<td>".$totalWeightCorn."</td>

			</tr>

			<tr>

				<td>Valances</td>

				<td>".$totalValLF." LF</td>

				<td>".$totalDiffVal."</td>

				<td>".$totalWeightVal."</td>

			</tr>

			<tr>

				<td>Draperies</td>

				<td>".$totalDrapeLF." widths</td>

				<td>".$totalDiffDrape."</td>

				<td>".$totalWeightDrape."</td>

			</tr>

		</tbody>

	</table>

		</td>

		<td width=\"2%\" valign=\"top\">&nbsp;</td>

		<td width=\"43%\" valign=\"top\">

		

	</td>

		<td width=\"5%\">&nbsp;</td>

		<td width=\"25%\" valign=\"top\">
		<div id=\"exPriceFailed\" style=\"padding:0 0 0 180px;margin-bottom: 10px;\">
			<span class=\"alignleft\" valign=\"top\" style=\"color:red;font-weight:bold;font-size:14px; font-style:italic;\" id =\"exPriceFailedMessage\"></span>
			<br/>
		</div>
		<button type=\"button\" style=\" margin-left: 206px \"onclick=\"verifyQuoteItems(".$thisQuote['id'].")\"  id=\"verifyeditorderlinesbutton\">Verify Ext price</button>

		<table id=\"quotefooter\" width=\"100%\" cellpadding=\"3\" border=\"0\" cellspacing=\"0\">
		    
		
			<tr id=\"subtotalLine\">
					<td width=\"50%\" class=\"alignright\" valign=\"top\">Subtotal:</td>

					<td width=\"50%\" class=\"alignleft\" valign=\"top\">\$<span id=\"subtotalvalue\">".number_format($overallSubtotal,2,'.',',')."</span></td>

				</tr>";

				$out .= "<tr id=\"discountsLine\">

					<td class=\"alignright\" valign=\"top\">Discounts:</td>

					<td class=\"alignleft\" valign=\"top\"><div>\$<span id=\"discountsvalue\">".number_format($overallDiscount,2,'.',',')."</span></div>";

					if($thisQuote['status']=="draft"){

						$out .= "<div><a href=\"javascript:addManualDiscount()\">+ Add Manual Discount</a></div>";

					}

					$out .= "</td>

				</tr>

				<!--<tr id=\"taxLine\">

					<td class=\"alignright\" valign=\"top\">Tax:</td>

					<td class=\"alignleft\" valign=\"top\">\$<span id=\"taxvalue\">0.00</span></td>

				</tr>-->

				<tr id=\"totaldueLine\">

					<td class=\"alignright\" valign=\"top\">Total Due:</td>

					<td class=\"alignleft\" valign=\"top\">\$<span id=\"totalduevalue\">".number_format($overallTotalDue,2,'.',',')."</span></td>

				</tr>

			</table></td></tr></table>

			<table style=\"width:1200px !important;\" cellpadding=\"5\" cellspacing=\"0\">

			<tr>

			<td>

			<h4 style=\"margin:0\">BOM:</h4>

		<table width=\"100%\" cellspacing=\"0\" cellpadding=\"5\" border=\"0\" id=\"fabtotalstable\">

		<thead>

		<tr>

		<th width=\"24%\">Fabric (color)</th>

		<th width=\"16%\">Yards</th>


		<th width=\"60%\">Purhasing Notes</th>

		</tr></thead><tbody>";

		foreach($fabricTotals as $fabid => $fabtot){

				$out .= "<tr>

			<td class=\"alignright\" valign=\"top\">";

			if($fabid > 0){
			    $thisfabriclookup=$this->Fabrics->get($fabid)->toArray();

    			$out .= $thisfabriclookup['fabric_name']." (".$thisfabriclookup['color'].")";
			}

			$out .= "</td>

			<td class=\"alignleft\" valign=\"top\">".$fabtot."</td>
			

			<td>";
			
			//existing purchasing notes this fabricid
			$pnotes=$this->QuoteBomPurchasingNotes->find('all',['conditions' => ['quote_id' => $thisQuote['id'], 'type'=>'fabric', 'material' => $fabid],'order'=>['time'=>'asc']])->toArray();
			foreach($pnotes as $note){
			    $out .= "<div id=\"pnote".$note['id']."\" style=\"padding:3px; border-bottom:1px dashed #ccc;\"><a href=\"javascript:deletepurchasingnote('".$note['id']."');\"><img src=\"/img/delete.png\" alt=\"Delete This Purchasing Note\" title=\"Delete This Purchasing Note\" /></a> ".$note['note'];
			    $noteUser=$this->Users->get($note['user_id'])->toArray();
			    $out .= " <span style=\"font-style:italic; color:blue;\">".$noteUser['first_name']." ".substr($noteUser['last_name'],0,1)." @ ".date('n/j/y g:iA',$note['time'])."</span>";
			    $out .= "</div>";
			}
			
			$out .= "
			
			<button style=\"font-size:12px; padding:5px; border:0;\" type=\"button\" onclick=\"addpurchasingnotebom('".$thisQuote['id']."','fabric','".$fabid."')\">+ Add Purchasing Note</button>
			</td>


			</tr>";

		}

		//backing and fill

		foreach($backingTotals as $backing => $totals){

			$out .= "<tr>

			<td class=\"alignright\" valign=\"top\">".$backing."</td>

			<td classs=\"alignleft\" valign=\"top\">".$totals."</td>

			<td>";
			
			//existing purchasing notes this fabricid
			$pnotes=$this->QuoteBomPurchasingNotes->find('all',['conditions' => ['quote_id' => $thisQuote['id'], 'type'=>'backing', 'material' => $backing],'order'=>['time'=>'asc']])->toArray();
			foreach($pnotes as $note){
			    $out .= "<div id=\"pnote".$note['id']."\" style=\"padding:3px; border-bottom:1px dashed #ccc;\"><a href=\"javascript:deletepurchasingnote('".$note['id']."');\"><img src=\"/img/delete.png\" alt=\"Delete This Purchasing Note\" title=\"Delete This Purchasing Note\" /></a> ".$note['note'];
			    $noteUser=$this->Users->get($note['user_id'])->toArray();
			    $out .= " <span style=\"font-style:italic; color:blue;\">".$noteUser['first_name']." ".substr($noteUser['last_name'],0,1)." @ ".date('n/j/y g:iA',$note['time'])."</span>";
			    $out .= "</div>";
			}
			
			$out .= "
			
		
			</td>

			</tr>";

		}

		foreach($fillTotals as $fill => $totals){

			$out .= "<tr>

			<td class=\"alignright\" valign=\"top\">".$fill."</td>

			<td classs=\"alignleft\" valign=\"top\">".$totals."</td>

			<td>";
			
			//existing purchasing notes this fabricid
			$pnotes=$this->QuoteBomPurchasingNotes->find('all',['conditions' => ['quote_id' => $thisQuote['id'], 'type'=>'fill', 'material' => $fill],'order'=>['time'=>'asc']])->toArray();
			foreach($pnotes as $note){
			    $out .= "<div id=\"pnote".$note['id']."\" style=\"padding:3px; border-bottom:1px dashed #ccc;\"><a href=\"javascript:deletepurchasingnote('".$note['id']."');\"><img src=\"/img/delete.png\" alt=\"Delete This Purchasing Note\" title=\"Delete This Purchasing Note\" /></a> ".$note['note'];
			    $noteUser=$this->Users->get($note['user_id'])->toArray();
			    $out .= " <span style=\"font-style:italic; color:blue;\">".$noteUser['first_name']." ".substr($noteUser['last_name'],0,1)." @ ".date('n/j/y g:iA',$note['time'])."</span>";
			    $out .= "</div>";
			}
			
			$out .= "
			
			
			</td>

			</tr>";

		}

		//$out .= "<tr><td colspan=\"4\">".print_r($meshTotals,1)."</td></tr>";

		foreach($meshTotals as $mesh => $total){

			$out .= "<tr>

			<td class=\"alignright\">".$mesh."</td>

			<td class=\"alignleft\">".round(($total/3),2)."<br>(".$total." LF)</td>

			<td>";
			
			//existing purchasing notes this fabricid
			$pnotes=$this->QuoteBomPurchasingNotes->find('all',['conditions' => ['quote_id' => $thisQuote['id'], 'type'=>'mesh', 'material' => $mesh],'order'=>['time'=>'asc']])->toArray();
			foreach($pnotes as $note){
			    $out .= "<div id=\"pnote".$note['id']."\" style=\"padding:3px; border-bottom:1px dashed #ccc;\"><a href=\"javascript:deletepurchasingnote('".$note['id']."');\"><img src=\"/img/delete.png\" alt=\"Delete This Purchasing Note\" title=\"Delete This Purchasing Note\" /></a> ".$note['note'];
			    $noteUser=$this->Users->get($note['user_id'])->toArray();
			    $out .= " <span style=\"font-style:italic; color:blue;\">".$noteUser['first_name']." ".substr($noteUser['last_name'],0,1)." @ ".date('n/j/y g:iA',$note['time'])."</span>";
			    $out .= "</div>";
			}
			
			$out .= "
			
		
			</td>

			</tr>";

		}

		$out .= "</tbody></table>

			</td>

			</tr>

			</table>

			<table cellpadding=\"5\" cellspacing=\"0\" style=\"width:850px !important;\">

			<tr>

			<td>

			<h4 style=\"margin:0\">TRACK COMPONENTS:</h4>

			<table width=\"100%\" cellspacing=\"0\" cellpadding=\"5\" border=\"0\" id=\"trackcomponentstable\">

		<thead>

		<tr>

		<th width=\"12%\">Line #</th>

		<th width=\"12%\">Item #</th>

		<th width=\"12%\">Qty</th>

		<th width=\"12%\">Inches</th>

		<th width=\"22%\">Component</th>

		<th width=\"30%\">Comment</th>

		</tr>

		</thead>

		<tbody>";

		$allTrackComponentsFind=$this->TrackSystems->find('all',['conditions'=>['status'=>'Active']])->toArray();

		foreach($trackComponentRows as $trackComponentNum => $trackComponentRow){

			for($si=1; $si <= floatval($trackComponentRow['metadata']['_component_numlines']); $si++){

				$out .= "<tr>

				<td>".$trackComponentRow['linedata']['line_number']."</td>

				<td>".$si."</td>

				<td>".$trackComponentRow['metadata']["_component_".$si."_qty"]."</td>

				<td>".$trackComponentRow['metadata']["_component_".$si."_inches"]."</td>

				<td>";

				foreach($allTrackComponentsFind as $trackComponentData){

					if($trackComponentData['id'] == $trackComponentRow['metadata']["_component_".$si."_componentid"]){

						$out .= $trackComponentData['title'];

					}

				}

				$out .= "</td>

				<td>".$trackComponentRow['metadata']["_component_".$si."_comment"]."</td>

				</tr>";

			}

		}

		$out .= "</tbody>

		</table>

			</td>

			</tr>

			</table>

			</div>";

			$out .= "<br><Br><Br><Br>";

			$out .= "<script>

			$(function(){

				";

			foreach($fabricTotals as $fabid => $fabtot){

				$out .= "$('#fabric_".$fabid."_requirements').change(function(){

					updatefabrequirement(".$fabid.",$(this).val());

				});

				";

			}

			$out .= "});</script>";

			echo $out;

	}
	  /**PPSASCRUM-29 end */
	  	public function changeqty($lineitem,$newqty){

		$this->autoRender=false;

		$quoteItemTable=TableRegistry::get('QuoteLineItems');

		$quoteItemMetaTable=TableRegistry::get('QuoteLineItemMeta');
		$thisLineItem=$quoteItemTable->get($lineitem);
		$thisLineItem->qty=$newqty;

		$allMeta=$this->QuoteLineItemMeta->find('all',['conditions'=>['quote_item_id' => $lineitem]])->toArray();
		$itemMeta=array();
		foreach($allMeta as $metarow){
			$itemMeta[$metarow['meta_key']]=$metarow['meta_value'];
		}


		if($quoteItemTable->save($thisLineItem)){

			//update QTY meta
			$thisLineItemQtyMeta=$this->QuoteLineItemMeta->find('all',['conditions'=>['quote_item_id' => $lineitem,'meta_key'=>'qty']]);
			foreach($thisLineItemQtyMeta as $lineitemmeta_qty){
				$thisQtyMetaEntry=$quoteItemMetaTable->get($lineitemmeta_qty->id);
				$thisQtyMetaEntry->meta_value=$newqty;
				$quoteItemMetaTable->save($thisQtyMetaEntry);
			}

			//update TOTAL YDS meta (if applicable)
			if(isset($itemMeta['yds-per-unit']) && isset($itemMeta['total-yds'])){
				$newTotalYards=(floatval($itemMeta['yds-per-unit']) * floatval($newqty));
				$thisLineItemTotalYardsMeta=$this->QuoteLineItemMeta->find('all',['conditions'=>['quote_item_id' => $lineitem,'meta_key'=>'total-yds']]);
				foreach($thisLineItemTotalYardsMeta as $lineitemmeta_totalyds){
					$thisTotalYdsMetaEntry=$quoteItemMetaTable->get($lineitemmeta_totalyds->id);
					$thisTotalYdsMetaEntry->meta_value=$newTotalYards;
					$quoteItemMetaTable->save($thisTotalYdsMetaEntry);
				}
			}
			

			if($this->recalculatequoteadjustments($thisLineItem->quote_id)){

				$this->updatequotemodifiedtime($thisLineItem->quote_id);

				echo "OK";

			}

		}

	}

	

	

	public function changeroomnumber($lineitem,$newroomnumber){

		$this->autoRender=false;

		$quoteItemTable=TableRegistry::get('QuoteLineItems');

		$thisLineItem=$quoteItemTable->get($lineitem);

		$thisLineItem->room_number=$this->cleanparameterreplacements($newroomnumber);

		if($quoteItemTable->save($thisLineItem)){

			$this->updatequotemodifiedtime($thisLineItem->quote_id);

			echo "OK";

		}

	}

	

	

	public function changelinenumber($lineitem,$newlinenumber){

		$this->autoRender=false;

		$quoteItemTable=TableRegistry::get('QuoteLineItems');

		$thisLineItem=$quoteItemTable->get($lineitem);

		$thisLineItem->line_number=$newlinenumber;

		if($quoteItemTable->save($thisLineItem)){

			$this->updatequotemodifiedtime($thisLineItem->quote_id);

			echo "OK";

		}

	}

	public function deletelineitem($lineitemid){

		$this->autoRender=false;

		$entity = $this->QuoteLineItems->get($lineitemid);

		$quoteID=$entity->quote_id;

		$thisquote=$this->Quotes->get($entity->quote_id);

		//delete all line item meta and notes

		$metas=$this->QuoteLineItemMeta->find('all',['conditions'=>['quote_item_id'=>$lineitemid]]);

		foreach($metas as $meta){

			$this->QuoteLineItemMeta->delete($meta);

		}

		$result = $this->QuoteLineItems->delete($entity);

		if($result){			
            
            if($thisquote->status=='editorder'){
                //first we need to see if this line was added during this edit phase, and if so, delete the Add Log entry and then skip inserting into delete log, since it will now be gone like the original quote
                $addLogLookup=$this->QuoteItemAddLog->find('all',['conditions'=>['line_item_id'=>$lineitemid]])->toArray();
                $addLogTable=TableRegistry::get('QuoteItemAddLog');
                if(count($addLogLookup) >0){
                    foreach($addLogLookup as $addLogEntry){
                        $thisAddLogEntry=$addLogTable->get($addLogEntry['id']);
                        $addLogTable->delete($thisAddLogEntry);
                    }
                }else{
                
                    $QuoteItemDeleteLogTable=TableRegistry::get('QuoteItemDeleteLog');
            
                    $newQDLogEntry=$QuoteItemDeleteLogTable->newEntity();
            
                    $newQDLogEntry->quote_line_item_id=$lineitemid;
            
                    $orderLineLookup=$this->OrderItems->find('all',['conditions' => ['quote_line_item_id' => $lineitemid]])->toArray();
                    if(count($orderLineLookup) == 1){
                        $newQDLogEntry->order_item_id=$orderLineLookup[0]['id'];
                    }
                    $newQDLogEntry->delete_time=time();
                    $newQDLogEntry->line_number=$entity->line_number;
                    $QuoteItemDeleteLogTable->save($newQDLogEntry);
                }
            }
            

			if($this->recalculatequoteadjustments($quoteID)){

				$this->updatequotemodifiedtime($entity->quote_id);

				echo "OK";

			}

		}

	}

	public function getproductsincat($catid){

		$this->autoRender=false;

		$conditions=['OR'=> [['categories ='=>$catid],['categories LIKE'=>'%,'.$catid],['categories LIKE'=>$catid.',%'],['categories LIKE'=>'%,'.$catid.',%']]];

		$products=$this->Products->find('all',['conditions'=> $conditions,'order'=>['product_name'=>'asc']])->toArray();

		echo json_encode($products);

	}

	public function getproductvariations($productid){

		$this->autoRender=false;

		$productVariations=array();

		$variationAtts=$this->ProductAttributes->find('all',['conditions'=>['product_id'=>$productid,'variation_base'=>1],'order'=>['sort'=>'asc']])->toArray();

		foreach($variationAtts as $attribute){

			$variations=$this->ProductVariations->find('all',['conditions'=>['product_id'=>$productid,'attribute_id'=>$attribute['id']]])->toArray();

			$thisAttOptions=array();

			foreach($variations as $variation){

				$thisAttOptions[]=array('label'=>$variation['title'],'id'=>$variation['id'],'price'=>$variation['price']);

			}

			$productVariations[]=array('id'=>$attribute['id'],'title'=>$attribute['title'],'options'=>$thisAttOptions);

		}

		echo json_encode($productVariations);

	}

	

	public function getproductprice($productID){

		$thisProduct=$this->Products->get($productID)->toArray();

		//iterate through all variation attributes and compare with data received

		$priceresult=35;

		

		$varAtts=$this->ProductAttributes->find('all',['conditions'=>['product_id'=>$productID,'variation_base'=>1]])->toArray();

		foreach($varAtts as $varatt){

			

		}

		echo json_encode(array('priceresult'=>number_format($priceresult,2,'.',',')));

	}

	

	
    public function getquoteslist(){

		$quotes=array();

		$overallTotalRows=$this->Quotes->find('all',['conditions'=>['status NOT IN' => ['editorder','orderplaced'] ]])->count();

		$conditions=array();

		

		/*

		if(isset($this->request->data['search']['value']) && strlen(trim($this->request->data['search']['value'])) >0){

			//this is a search

			$conditions['OR']=array();

			$conditions['OR'] += array('product_name LIKE'=>'%'.trim($this->request->data['search']['value']).'%');

		}

		*/

		

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

		

		$conditions += array('status NOT IN' => array('emptyorder','editorder','orderplaced'));

		

		//is this a search?

		if(isset($this->request->data['search']['value']) && strlen(trim($this->request->data['search']['value'])) >0){

			//yes, do the search limiters

			$conditions['OR']=array();

			

			$conditions['OR'] += array('CAST(quote_number as CHAR) LIKE' => '%'.$this->request->data['search']['value'].'%');

			$conditions['OR'] += array('title LIKE' => '%'.$this->request->data['search']['value'].'%');

			

			$customerSearchResults=array();

			$customerSearch=$this->Customers->find('all',['conditions'=>['company_name LIKE' => '%'.$this->request->data['search']['value'].'%']]);

			foreach($customerSearch as $customer){

				$customerSearchResults[]=$customer['id'];

			}

			

			$contactSearchResults=array();

			$contactSearch=$this->CustomerContacts->find('all',['conditions'=>["CONCAT(first_name,' ',last_name) LIKE" => '%'.$this->request->data['search']['value'].'%']]);

			foreach($contactSearch as $contact){

				$contactSearchResults[]=$contact['id'];

			}

			

			

			

			if(count($customerSearchResults) > 1){

				$conditions['OR'] += array('customer_id IN' => $customerSearchResults);

			}elseif(count($customerSearchResults) == 1){

				$conditions['OR'] += array('customer_id' => $customerSearchResults[0]);

			}

			

			if(count($contactSearchResults) > 1){

				$conditions['OR'] += array('contact_id IN' => $contactSearchResults);

			}elseif(count($contactSearchResults) == 1){

				$conditions['OR'] += array('contact_id' => $contactSearchResults[0]);

			}
			
			
			$conditions['OR'] += array('CAST(order_number as CHAR) LIKE' => '%'.$this->request->data['search']['value'].'%');
			

		}

		

		

		$orderby=array();

			

		if(isset($this->request->data['order'][0]['column'])){

			switch($this->request->data['order'][0]['column']){

				case 1:

					$orderby += array('quote_number'=>$this->request->data['order'][0]['dir'], 'revision' => 'desc');

				break;
                
                case 2:

					$orderby += array('status' => $this->request->data['order'][0]['dir']);

				break;
				
				case 3:
				    
                    $orderby += array('order_number' => $this->request->data['order'][0]['dir']);
                    
                break;
                
				case 4:

					$orderby += array('created' => $this->request->data['order'][0]['dir']);

				break;

				case 5:

					$orderby += array('modified' => $this->request->data['order'][0]['dir']);

				break;

				case 7:

					$orderby += array('quote_total' => $this->request->data['order'][0]['dir']);

				break;

				default:

					$orderby += array('quote_number' => 'desc');

				break;

			}

		}

        //mail('dallasisp@gmail.com','data',print_r($conditions,1));
		

		$quotefind=$this->Quotes->find('all',['conditions'=>$conditions,'order'=>$orderby])->offset($start)->limit($limit)->hydrate(false)->toArray();

		$totalFilteredRows=$this->Quotes->find('all',['conditions'=>$conditions])->count();

		

		foreach($quotefind as $quote){

			

			//$customerData=$this->Customers->get($quote['customer_id'])->toArray();
			$customerData=$this->Customers->find('all',['conditions'=>['id'=>$quote['customer_id']]])->toArray();
			$userData=$this->Users->get($quote['created_by'])->toArray();

				

			if($quote['modified'] < 1000){

				$modified='';

			}else{

				$modified=date('n/j/Y - g:iA',$quote['modified']);

			}

			

			$quoteTitle='';

			if(strlen(trim($quote['title'])) >0){

				$quoteTitle .= "<b>".$quote['title']."</b><br>";

			}

			$quoteTitle .= $customerData['company_name'];
            /**PPSASCRUM-3 start **/
			if($quote['expires'] < time()) {
				$quoteTitle .=  ($customerData['on_credit_hold']) ? '<div><span style="color: white;"> On Credit Hold</span></div> ' : '';
			} else 
				$quoteTitle .=  ($customerData['on_credit_hold']) ? '<div><span style="color: red;"> On Credit Hold</span></div> ' : '';
            
			/**PPSASCRUM-3 end **/
			

			

			$numitems=0;

			$quoteitems=$this->QuoteLineItems->find('all',['conditions'=>['quote_id'=>$quote['id']]])->toArray();

			foreach($quoteitems as $quoteitem){

				$numitems=($numitems+$quoteitem['qty']);

			}

			

			$quoteNumber=$quote['quote_number'];

			if($quote['revision'] >0){

				$quoteNumber .= " <span class=\"revisionlabel\">[REV ".$quote['revision'].']</span>';

			}
			
			if(!is_null($quote['type_id']) && $quote['type_id'] > 0){
			    $thisType=$this->QuoteTypes->get($quote['type_id'])->toArray();
			    $quoteNumber .= '<br>'.$thisType['type_label'];
			}

			$addclasses='';
            $thisWOnumber='';
			
			//check to see if this quote already exists an an Order
			$ordercheck=$this->Orders->find('all',['conditions'=>['quote_id'=>$quote['id']]])->toArray();
			if(count($ordercheck) >0){
				//this quote has become an order
				$status=ucfirst($quote['status']);
				
				foreach($ordercheck as $orderEntry){
				    $thisWOnumber='<a href="/orders/editlines/'.$orderEntry['id'].'" target="_blank">'.$orderEntry['order_number'].'</a>';
				    
					if($orderEntry['status'] == 'Canceled'){
						$thisWOnumber .= '<br><b><span style="font-size:10px;color:red">(CANCELED)</span></b>';
						$addclasses .= ' canceledorder';
					}
				}
			}else{
				if($quote['expires'] < time()){
					$status=ucfirst($quote['status']).'<br>Expired';
					$addclasses .= ' expired';
				}else{
					$status=ucfirst($quote['status']);
				}
			}
			
			

			$quotes[]=array(

					'DT_RowId'=>'row_'.$quote['id'],

					'DT_RowClass'=>'rowtest'.$addclasses,

					'0' => $quoteTitle,

					'1' => $quoteNumber,

					'2' => $status,
					
					'3' => $thisWOnumber,

					'4' => date('n/j/Y - g:iA',$quote['created']),

					'5' => $modified,

					'6' => $numitems,

					'7' => '$'.number_format($quote['quote_total'],2,'.',','),

					'8' => $userData['first_name']." ".$userData['last_name'],

					'9' => '<a href="/quotes/add/'.$quote['id'].'/"><img src="/img/edit.png" alt="Edit This Quote" title="Edit This Quote" /></a> <a href="/quotes/delete/'.$quote['id'].'/"><img src="/img/delete.png" alt="Delete This Quote" title="Delete This Quote" /></a>'

			);

		}

		

		$this->set('draw',$draw);

		$this->set('recordsTotal',$overallTotalRows);

		$this->set('recordsFiltered',$totalFilteredRows);

		$this->set('data',$quotes);

		$this->set('_serialize',['draw','recordsTotal','recordsFiltered','data']);

	}
	


    public function deleteglobalnote($noteid){
	    $this->autoRender=false;
	    $thisNote=$this->QuoteNotes->get($noteid);
	    if($this->QuoteNotes->delete($thisNote)){
	        echo "SUCCESS";
	    }
	    exit;
	}
	

	public function addglobalnote($quoteid){
		$thisQuote=$this->Quotes->get($quoteid)->toArray();

		if($this->request->data){
			$this->autoRender=false;
			$quoteNoteTable=TableRegistry::get('QuoteNotes');
			$newNote=$quoteNoteTable->newEntity();
			$newNote->user_id=$this->Auth->user('id');
			$newNote->time=time();
			$newNote->note_text=$this->request->data['message'];
			$newNote->quote_id=$thisQuote['id'];
			$newNote->appear_on_pdf=$this->request->data['appear_on_pdf'];
			
			if($quoteNoteTable->save($newNote)){
				$this->logActivity($_SERVER['REQUEST_URI'],'Added global note to quote '.$thisQuote['quote_number']);

				$this->updatequotemodifiedtime($quoteid);
				echo "<html><head><script>parent.$.fancybox.close();parent.updateQuoteTable();</script></head><body></body></html>";exit;
			}
		}else{
			$this->set('mode',$mode);
			$this->viewBuilder()->layout('iframeinner');
		}
	}


	

	public function addlineitemnote($lineitemid,$mode='quote'){

		

		$thisLineItem=$this->QuoteLineItems->get($lineitemid)->toArray();

		$thisQuote=$this->Quotes->get($thisLineItem['quote_id'])->toArray();

		if($this->request->data){

		

			$this->autoRender=false;

			$lineItemNoteTable=TableRegistry::get('QuoteLineItemNotes');

			$newNote=$lineItemNoteTable->newEntity();

			$newNote->user_id=$this->Auth->user('id');

			$newNote->time=time();

			$newNote->message=$this->request->data['message'];

			$newNote->quote_id=$thisQuote['id'];

			$newNote->quote_item_id=$lineitemid;

			$newNote->visible_to_customer=$this->request->data['visible_to_customer'];

			

			if($lineItemNoteTable->save($newNote)){

				$this->updatequotemodifiedtime($thisLineItem['quote_id']);

				echo "<html><head><script>parent.$.fancybox.close();parent.updateQuoteTable();</script></head><body></body></html>";exit;

			}

		}else{
			$this->set('mode',$mode);
			$this->viewBuilder()->layout('iframeinner');

			

		}

	}

	

	

	public function editlineitemnote($noteid){

	

		$thisNote=$this->QuoteLineItemNotes->get($noteid)->toArray();

		$this->set('thisNote',$thisNote);

		

		

		if($this->request->data){

	

			$lineItemNoteTable=TableRegistry::get('QuoteLineItemNotes');

			$updatedNote=$lineItemNoteTable->get($noteid);

			$updatedNote->message=$this->request->data['message'];

				

			if($lineItemNoteTable->save($updatedNote)){

				$this->updatequotemodifiedtime($quoteID);

				echo "<html><head><script>parent.$.fancybox.close();parent.updateQuoteTable();</script></head><body></body></html>";exit;

			}

		}else{

			$this->viewBuilder()->layout('iframeinner');

			

		}

	}

	

	

 /**
  * Summary of editcalclineitem
  * @param mixed $lineItemID
  * @return \Cake\Network\Response|null
  */
	public function editcalclineitem($lineItemID){

		//$this->autoRender=false;

		

		$thisLineItem=$this->QuoteLineItems->get($lineItemID)->toArray();

		$this->set('thisLineItem',$thisLineItem);

		$this->set('isEdit',true);

		
		$markuprulesets=$this->FabricMarkupRules->find('all',['conditions'=>['calculator'=>$thisLineItem['calculator_used'] ]])->toArray();

		$this->set('markuprulesets',$markuprulesets);
		

		$this->set('quoteID',$thisLineItem['quote_id']);

		$this->set('isedit','1');

		$thisQuote=$this->Quotes->get($thisLineItem['quote_id'])->toArray();

		$thisCustomer=$this->Customers->get($thisQuote['customer_id'])->toArray();

		

		$this->set('settings',$this->getsettingsarray());

		

		if($this->request->data){

			//line item meta updates

			$lineItemMetaTable=TableRegistry::get('QuoteLineItemMeta');

			$lineItemMetaLookup=$this->QuoteLineItemMeta->find('all',['conditions'=>['quote_item_id'=>$lineItemID]])->toArray();

            /**PPSA-33 start **/
            if(isset($this->request->data['fabric-cost-per-yard-custom-value']) && $this->request->data['fabric-cost-per-yard-custom-value'] != '') {
                $this->updateLineItemMeta($lineItemID,'fabric-cost-per-yard-custom-value',number_format($this->request->data['fabric-cost-per-yard-custom-value'], 2, '.', ''));
            }/**PPSA-33 end **/
            
			foreach($lineItemMetaLookup as $itemmeta){

				$thisLineItemMeta=$lineItemMetaTable->get($itemmeta['id']);

				if(isset($this->request->data[$itemmeta['meta_key']]) && $this->request->data[$itemmeta['meta_key']] != $itemmeta['meta_value']){

					$thisLineItemMeta->meta_value=$this->request->data[$itemmeta['meta_key']];

					$lineItemMetaTable->save($thisLineItemMeta);

				}
				
				/**PPSA-33 start **/
				if($itemmeta['meta_key'] == 'fabric-cost-per-yard-custom-value'){
				    
				    $thisLineItemMeta->meta_value=number_format($this->request->data['fabric-cost-per-yard-custom-value'], 2, '.', '');

					$lineItemMetaTable->save($thisLineItemMeta);
				    
				}
				/**PPSA-33 end **/
				
				if($itemmeta['meta_key'] == 'libraryimageid' && $thisLineItem['calculator_used'] == 'cubicle-curtain'){
					$newLibraryImageID=0;
					if($this->request->data['railroaded'] == '1'){

						if($this->request->data['mesh-type'] == 'None'){

							$newLibraryImageID=31;

						}elseif($this->request->data['mesh-type'] == 'Integral Mesh'){

							$newLibraryImageID=30;

						}else{

							$newLibraryImageID=32;

						}

					}else{

						if($this->request->data['mesh-type'] == 'None'){

							$newLibraryImageID=27;

						}elseif($this->request->data['mesh-type'] == 'Integral Mesh'){

							$newLibraryImageID=30;

						}else{

							$newLibraryImageID=26;

						}

					}
					if($itemmeta['meta_key'] == 'libraryimageid'){

						$thisLineItemMeta->meta_value=$newLibraryImageID;
						$lineItemMetaTable->save($thisLineItemMeta);

					}
				
				
				}
			}

			//update the QTY and Price and Markup values for the line item itself

			$lineItemTable=TableRegistry::get('QuoteLineItems');

			$thisLineItem=$lineItemTable->get($lineItemID);

			$thisLineItem->qty=$this->request->data['qty'];

			$thisLineItem->markup=$this->request->data['markup'];

			$thisLineItem->cost=$this->request->data['cost'];

			$thisLineItem->best_price=$this->request->data['price'];

			$lineItemTable->save($thisLineItem);
			/**PPSASCRUM-29 start */
			$this->UpdatetoOrderLineItemTables($thisLineItem['quote_id'],$lineItemID,$thisLineItem['order_id'],'orders');
			$this->UpdatetoOrderLineItemTables($thisLineItem['quote_id'],$lineItemID,$thisLineItem['order_id'],'work_orders');

			/**PPSASCRUM-29 end */

			if($this->recalculatequoteadjustments($thisLineItem['quote_id'])){

				$this->updatequotemodifiedtime($thisLineItem['quote_id']);

				//echo "<html><head><script>parent.$.fancybox.close();parent.updateQuoteTable();</script></head><body></body></html>";exit;

				$this->logActivity($_SERVER['REQUEST_URI'],'Edited calculated line id#'.$lineItemID);
				
				if($thisQuote['status'] == 'editorder'){
				    return $this->redirect('/orders/editlines/'.$thisQuote['order_id']);
				}else{
				    return $this->redirect('/quotes/add/'.$thisLineItem['quote_id'].'/');
				}

			}

		}else{

			//$this->viewBuilder()->layout('iframeinner');

			$metas=array();

			$lineItemMetaLookup=$this->QuoteLineItemMeta->find('all',['conditions'=>['quote_item_id'=>$lineItemID]])->toArray();

			foreach($lineItemMetaLookup as $itemmeta){

				$metas[$itemmeta['meta_key']]=$itemmeta['meta_value'];

			}

			$this->set('thisItemMeta',$metas);

			$thisFabric=$this->Fabrics->get($metas['fabricid'])->toArray();

			$this->set('fabricData',$thisFabric);

			$this->set('fabricid',$thisFabric['id']);

			$linings=$this->Linings->find('all')->toArray();

			$this->set('linings',$linings);

			

			$this->render('/Quotes/Calculators/'.$metas['calculator-used']);

		}

	}

	

	

public function editlineitem($lineItemID){

		$thisLineItem=$this->QuoteLineItems->get($lineItemID)->toArray();

		$this->set('thisLineItem',$thisLineItem);

		

		$lineItemMetas=array();

		$itemmetas=$this->QuoteLineItemMeta->find('all',['conditions'=>['quote_item_id'=>$lineItemID]])->toArray();

		foreach($itemmetas as $itemmeta){

			$lineItemMetas[$itemmeta['meta_key']]=$itemmeta['meta_value'];

		}

		$this->set('lineitemmeta',$lineItemMetas);

		

		$this->set('isEdit',true);

		$quoteID=$thisLineItem['quote_id'];

		$thisQuote=$this->Quotes->get($quoteID)->toArray();

		$this->set('quoteData',$thisQuote);

		

		//$thisCustomer=$this->Customers->get($thisQuote['customer_id'])->toArray();
		$thisCustomer=$this->Customers->find('all',['conditions'=>['id'=>$thisQuote['customer_id']]])->toArray();

		$this->set('customerData',$thisCustomer);

		
        $allSettings=$this->getsettingsarray();

		$this->set('settings',$allSettings);
		

		if($this->request->data){
			
			$lineItemData=$this->QuoteLineItems->get($lineItemID)->toArray();
			
			switch($lineItemData['product_type']){
			    case 'custom':
			        //save the changes to the catch-all item
        			$quoteItemsTable=TableRegistry::get('QuoteLineItems');
        			$thisLineItem=$quoteItemsTable->get($lineItemID);
        			$thisLineItem->title=$this->request->data['line_item_title'];
        			$thisLineItem->description = $this->request->data['description'];
        			$thisLineItem->qty = $this->request->data['qty'];
        			$thisLineItem->unit=$this->request->data['unit_type'];
        			$thisLineItem->best_price = floatval($this->request->data['price']);
        			$thisLineItem->room_number = $this->request->data['room_number'];
        			
        			
        			//12/7/2021  ticket 601958 remove override_price when using edit catchall form and changing the price
        			if($lineItemData['override_active'] == 1 && floatval($lineItemData['override_price']) != floatval($this->request->data['price'])){
        			    $thisLineItem->override_active=0;
        			    $thisLineItem->override_price = null;
        			    
        			    $lineItemData['override_price']=null;
        			    $lineItemData['override_active']=0;
        			}
        			
        			$tieradjusted=$this->tieradjustment($lineItemData,$thisCustomer,floatval($this->request->data['price']));
        			$installadjusted=$this->installadjustment($lineItemData,$thisCustomer,$tieradjusted);
        			$rebateadjusted=$this->rebateadjustment($lineItemData,$thisCustomer,$installadjusted);
        			$pmiadjusted=$this->pmiadjustment($lineItemData,$thisCustomer,$rebateadjusted);
        			
        			
        			$thisLineItem->tier_adjusted_price = $tieradjused;
        			$thisLineItem->install_adjusted_price = $installadjusted;
        			$thisLineItem->rebate_adjusted_price = $rebateadjusted;
        			$thisLineItem->pmi_adjusted = $pmiadjusted;
        			
        			
        			
        			$thisLineItem->extended_price = ($pmiadjusted*floatval($this->request->data['qty']));
        			
        			$quoteItemsTable->save($thisLineItem);
        			
        			$this->updateLineItemMeta($lineItemID,'catchallcategory',$this->request->data['category']);
        			$this->updateLineItemMeta($lineItemID,'qty',$this->request->data['qty']);
        			$this->updateLineItemMeta($lineItemID,'specs',$this->request->data['specs']);
        			$this->updateLineItemMeta($lineItemID,'fabrictype',$this->request->data['fabric_type']);
        			$this->updateLineItemMeta($lineItemID,'unit_type',$this->request->data['unit_type']);
        			$this->updateLineItemMeta($lineItemID,'width',$this->request->data['cut_width']);
        			$this->updateLineItemMeta($lineItemID,'fw',$this->request->data['finished_width']);
        			$this->updateLineItemMeta($lineItemID,'length',$this->request->data['finished_length']);
        			
        			$this->updateLineItemMeta($lineItemID,'yds-per-unit',$this->request->data['fabric_ydsper']);
        			$this->updateLineItemMeta($lineItemID,'total-yds',$this->request->data['fabric_total_yds']);
        			
        			//$this->updateLineItemMeta($lineItemID,'fabric-margin',$this->request->data['fabric_margin']);
        			//$this->updateLineItemMeta($lineItemID,'fabric-profit',$this->request->data['fabric_profit']);
        			
        			$this->updateLineItemMeta($lineItemID,'room_number',$this->request->data['room_number']);
        			$this->updateLineItemMeta($lineItemID,'price',$this->request->data['price']);
        			
        			$this->updateLineItemMeta($lineItemID,'vendors_id',$this->request->data['vendors_id']);
        			
        			$this->updateLineItemMeta($lineItemID,'com-fabric',$this->request->data['com-fabric']);
        			
        			//fabric metadata
        			switch($this->request->data['fabric_type']){
        				case "none":
        					$this->updateLineItemMeta($lineItemID,'fabricid','0');
        					$this->updateLineItemMeta($lineItemID,'fabric_name','');
        					$this->updateLineItemMeta($lineItemID,'fabric_color','');
        				break;
        				case "existing":
        					
        					$selectedFabric=$this->Fabrics->get($this->request->data['fabric_id_with_color'])->toArray();
        					
        					$this->updateLineItemMeta($lineItemID,'fabricid',$this->request->data['fabric_id_with_color']);
        					$this->updateLineItemMeta($lineItemID,'fabric_name',$selectedFabric['fabric_name']);
        					$this->updateLineItemMeta($lineItemID,'fabric_color',$selectedFabric['color']);
        					
        				break;
        				case "typein":
        					$this->updateLineItemMeta($lineItemID,'fabricid','0');
        					$this->updateLineItemMeta($lineItemID,'fabric_name',$this->request->data['fabric_name']);
        					$this->updateLineItemMeta($lineItemID,'fabric_color',$this->request->data['fabric_color']);
        				break;
        			}
        			

					/**PPSASSCRUM-29 start */	
					$orderDetails = $this->Orders->find('all',['conditions'=>['quote_id'=>$quoteID]])->first();

					if(isset($orderDetails) && !empty($orderDetails['id'])){

						$this->UpdatetoOrderLineItemTables($quoteID, $thisLineItem['id'],$orderDetails['id'],'orders');
						$this->UpdatetoOrderLineItemTables($quoteID, $thisLineItem['id'],$orderDetails['id'],'work_orders');
						//$this->savetoOrderLineItemTables($newItem->id, $quoteID,$orderDetails['id'], $table='orders' );
						//$this->savetoOrderLineItemTables($newItem->id, $quoteID,$orderDetails['id'], $table='work_orders' );	
					}
				  
					/**PPSASSCRUM-29 end */


        			$this->updatequotemodifiedtime($thisLineItem->quote_id);
        			$this->logActivity($_SERVER['REQUEST_URI'],'Saved changes to Catch-All line item '.$lineItemData['line_number'].' on Quote #'.$thisQuote['quote_number']);
        			
        			if($thisQuote['status'] == 'editorder'){
        			    return $this->redirect('/orders/editlines/'.$thisQuote['order_id']);
        			}else{
        				return $this->redirect('/quotes/add/'.$thisLineItem->quote_id.'/');
        			}
			    break;
			    
			    case 'newcatchall-misc':
			        
			        //make sure the image is an image
			        if(isset($this->request->data['imagefileupload']['tmp_name']) && $this->request->data['image_method'] == 'upload'){
                        $allowedTypes=array('.jpg','jpeg','.png','.gif');
    					if(!in_array(strtolower(substr($this->request->data['imagefileupload']['name'],-4)),$allowedTypes)){
    					    $this->autoRender=false;
    					    echo '<h3><span style="color:red;">ERROR:</span> The filetype <u>'.substr($this->request->data['imagefileupload']['name'],-4).'</u> is not allowed in the image field.</h3>
                            <p><button type="button" onclick="history.go(-1);">Go Back</button></p>';
    					    exit;
    					}
                    }
			        
			        //save all the field values
			        $thisItem=$this->QuoteLineItems->get($lineItemData['id']);
			        $thisItem->product_subclass=$this->request->data['product_subclass'];
			        $thisItem->title = $this->request->data['line_item_title'];
                    $thisItem->description = $this->request->data['description'];
                    $thisItem->best_price = $this->request->data['price'];
                    $thisItem->qty = $this->request->data['qty'];
                    $thisItem->room_number = $this->request->data['location'];
                    
                    $thisSubClass=$this->ProductSubclasses->get($this->request->data['product_subclass'])->toArray();
                    $thisItem->enable_tally=$thisSubClass['tally'];
                    
                    $thisItem->unit = $this->request->data['unit_type'];
                    

                    if($this->QuoteLineItems->save($thisItem)){
                        //save the meta data changes
                        $ignoreKeys=array('process','product_subclass','imagefileupload','description','line_item_title','customer_id','save_to_library','image_title','image_category','image_tags','fabric_name','fabric_color','libraryimageid');
                        
                        foreach($this->request->data as $postkey => $postdata){
                            if(!in_array($postkey,$ignoreKeys) && $postdata != ''){
                                
                                if($postkey=='fabric_type'){
                                    $postkey='fabrictype';
                                }
                                
                                $this->updateLineItemMeta($lineItemID,$postkey,$postdata);
                                
                            }
                        }
                        
                        
                        $newLineItemMeta=$this->QuoteLineItemMeta->newEntity();
                        $newLineItemMeta->quote_item_id = $newItem->id;
                        $newLineItemMeta->meta_key='lineitemtype';
                        $newLineItemMeta->meta_value='newcatchall';
                        $this->QuoteLineItemMeta->save($newLineItemMeta);
                        
                        
                        if($this->request->data['fabric_type']=="existing"){

							$this->updateLineItemMeta($lineItemID,'fabricid',$this->request->data['fabric_id_with_color']);
							
							$selectedFabric=$this->Fabrics->get($this->request->data['fabric_id_with_color'])->toArray();
							$this->updateLineItemMeta($lineItemID,'fabric_name',$selectedFabric['fabric_name']);
							
                            $this->updateLineItemMeta($lineItemID,'fabric_color',$selectedFabric['color']);
							

						}elseif($this->request->data['fabric_type']=="typein"){

                            $this->updateLineItemMeta($lineItemID,'fabric_name',$this->request->data['fabric_name']);
							
							$this->updateLineItemMeta($lineItemID,'fabric_color',$this->request->data['fabric_color']);

						}/*PPSA -36 start */elseif($this->request->data['fabric_type']=="none"){

                            $this->updateLineItemMeta($lineItemID,'fabricid','0');
        					$this->updateLineItemMeta($lineItemID,'fabric_name','');
        					$this->updateLineItemMeta($lineItemID,'fabric_color','');

						}
	/*PPSA -36 end */

						if($this->request->data['image_method'] == 'library'){
						    
						    $this->updateLineItemMeta($lineItemID,'libraryimageid',$this->request->data['libraryimageid']);

							
						}elseif($this->request->data['image_method'] == 'upload'){
							
							//handle the upload, and process whether to save this to library or just do a one-off insert
							$filename=$this->request->data['imagefileupload']['name'];
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
							
							
							move_uploaded_file($this->request->data['imagefileupload']['tmp_name'],$_SERVER['DOCUMENT_ROOT']."/webroot/img/library/".$filename);
							
							$imgLibraryTable=TableRegistry::get('LibraryImages');
							$newImage=$imgLibraryTable->newEntity();
							
							if($this->request->data['save_to_library'] == '1'){
								
								$selectedCat='';
								$allCats=$this->LibraryCategories->find('all')->toArray();
								foreach($allCats as $catEntry){
									if($catEntry['id'] == $this->request->data['image_category']){
										$selectedCat=$catEntry['category_title'];
									}
								}
								
								//insert into image library db with details for future usage
								$newImage->image_title=$this->request->data['image_title'];
								$newImage->categories=$selectedCat;
								$newImage->filename=$filename;
								$newImage->time=time();
								$newImage->added_by=$this->Auth->user('id');
								$newImage->tags=$this->request->data['image_tags'];
								$newImage->status='Active';
							}else{
								//insert into image library db with no details for single usage
								$newImage->image_title=$this->request->data['imagefileupload']['name'];
								$newImage->categories='Misc';
								$newImage->filename=$filename;
								$newImage->time=time();
								$newImage->added_by=$this->Auth->user('id');
								$newImage->status='Single Use';
							}
							
							if($imgLibraryTable->save($newImage)){
								//insert the metadata for libraryimageid
								$this->updateLineItemMeta($lineItemID,'libraryimageid',$newImage->id);
								
								$this->aspectratiofix($newImage->id,600);

							}
							
							
						}

						

						if($this->request->data['com-fabric']=='1'){

							$this->updateLineItemMeta($lineItemID,'com-fabric','1');

						}
						
                        /*PPSA-33 start */
                        	if(empty($this->request->data['fabric-cost-per-yard-custom-value'])) {

							$this->updateLineItemMeta($lineItemID,'fabric-cost-per-yard-custom-value','');

						}else {
						  $this->updateLineItemMeta($lineItemID,'fabric-cost-per-yard-custom-value',number_format($this->request->data['fabric-cost-per-yard-custom-value'], 2, '.', ''));  
						}
						
                        /*PPSA-33 end */
                        
                    }
					/**PPSASSCRUM-29 start */	
					$orderDetails = $this->Orders->find('all',['conditions'=>['quote_id'=>$quoteID]])->first();

					if(isset($orderDetails) && !empty($orderDetails['id'])){

						$this->UpdatetoOrderLineItemTables($quoteID, $thisLineItem['id'],$orderDetails['id'],'orders');
						$this->UpdatetoOrderLineItemTables($quoteID, $thisLineItem['id'],$orderDetails['id'],'work_orders');
						//$this->savetoOrderLineItemTables($newItem->id, $quoteID,$orderDetails['id'], $table='orders' );
						//$this->savetoOrderLineItemTables($newItem->id, $quoteID,$orderDetails['id'], $table='work_orders' );	
					}
				  
					/**PPSASSCRUM-29 end */
			        
			        //update modified time
			        $this->updatequotemodifiedtime($quoteID);
			        
			        //update tier adjustments
			        if($this->recalculatequoteadjustments($quoteID)){
			            
			            //get the new item data for PMI Adjusted for extended price
			            $freshItemData=$this->QuoteLineItems->get($lineItemID);
			            $freshItemData->extended_price = ($freshItemData->pmi_adjusted * floatval($this->request->data['qty']));
			            $this->QuoteLineItems->save($freshItemData);
			            
			            
			            
						$this->Flash->success('Successfully edited Miscellaneous Catchall line item');
						
						$this->logActivity($_SERVER['REQUEST_URI'],'Edited Miscellaneous Catchall line ID '.$lineItemID,$this->Auth->user('id'),json_encode($this->request));
					    
					    //redirect to quote or orderedit view
					    if($thisQuote['status'] == 'editorder'){
            			    return $this->redirect('/orders/editlines/'.$thisQuote['order_id']);
            			}else{
            				return $this->redirect('/quotes/add/'.$quoteID.'/');
            			}
					}
			        
			        
			    break;
			    
			    case 'newcatchall-hardware':
			        
			        //make sure the image is an image
			        if(isset($this->request->data['imagefileupload']['tmp_name']) && $this->request->data['image_method'] == 'upload'){
                        $allowedTypes=array('.jpg','jpeg','.png','.gif');
    					if(!in_array(strtolower(substr($this->request->data['imagefileupload']['name'],-4)),$allowedTypes)){
    					    $this->autoRender=false;
    					    echo '<h3><span style="color:red;">ERROR:</span> The filetype <u>'.substr($this->request->data['imagefileupload']['name'],-4).'</u> is not allowed in the image field.</h3>
                            <p><button type="button" onclick="history.go(-1);">Go Back</button></p>';
    					    exit;
    					}
                    }
			        
			        //save all the field values
			        $thisItem=$this->QuoteLineItems->get($lineItemData['id']);
			        $thisItem->product_subclass=$this->request->data['product_subclass'];
			        $thisItem->title = $this->request->data['line_item_title'];
                    $thisItem->description = $this->request->data['description'];
                    $thisItem->best_price = $this->request->data['price'];
                    $thisItem->qty = $this->request->data['qty'];
                    $thisItem->room_number = $this->request->data['location'];
                    
                    $thisSubClass=$this->ProductSubclasses->get($this->request->data['product_subclass'])->toArray();
                    $thisItem->enable_tally=$thisSubClass['tally'];
                    
                    $thisItem->unit = $this->request->data['unit_type'];
                    

                    if($this->QuoteLineItems->save($thisItem)){
                        //save the meta data changes
                        $ignoreKeys=array('process','product_subclass','imagefileupload','description','line_item_title','customer_id','save_to_library','image_title','image_category','image_tags','fabric_name','fabric_color','libraryimageid');
                        
                        foreach($this->request->data as $postkey => $postdata){
                            if(!in_array($postkey,$ignoreKeys) && $postdata != ''){
                                
                                if($postkey=='fabric_type'){
                                    $postkey='fabrictype';
                                }
                                
                                $this->updateLineItemMeta($lineItemID,$postkey,$postdata);
                                
                            }
                        }
                        
                        
                        $newLineItemMeta=$this->QuoteLineItemMeta->newEntity();
                        $newLineItemMeta->quote_item_id = $newItem->id;
                        $newLineItemMeta->meta_key='lineitemtype';
                        $newLineItemMeta->meta_value='newcatchall';
                        $this->QuoteLineItemMeta->save($newLineItemMeta);
                        
                        
                        if($this->request->data['fabric_type']=="existing"){

							$this->updateLineItemMeta($lineItemID,'fabricid',$this->request->data['fabric_id_with_color']);
							
							$selectedFabric=$this->Fabrics->get($this->request->data['fabric_id_with_color'])->toArray();
							$this->updateLineItemMeta($lineItemID,'fabric_name',$selectedFabric['fabric_name']);
							
                            $this->updateLineItemMeta($lineItemID,'fabric_color',$selectedFabric['color']);
							

						}elseif($this->request->data['fabric_type']=="typein"){

                            $this->updateLineItemMeta($lineItemID,'fabric_name',$this->request->data['fabric_name']);
							
							$this->updateLineItemMeta($lineItemID,'fabric_color',$this->request->data['fabric_color']);

						}/*PPSA -36 start */elseif($this->request->data['fabric_type']=="none"){

                            $this->updateLineItemMeta($lineItemID,'fabricid','0');
        					$this->updateLineItemMeta($lineItemID,'fabric_name','');
        					$this->updateLineItemMeta($lineItemID,'fabric_color','');

						}
	/*PPSA -36 end */

						if($this->request->data['image_method'] == 'library'){
						    
						    $this->updateLineItemMeta($lineItemID,'libraryimageid',$this->request->data['libraryimageid']);

							
						}elseif($this->request->data['image_method'] == 'upload'){
							
							//handle the upload, and process whether to save this to library or just do a one-off insert
							$filename=$this->request->data['imagefileupload']['name'];
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
							
							
							move_uploaded_file($this->request->data['imagefileupload']['tmp_name'],$_SERVER['DOCUMENT_ROOT']."/webroot/img/library/".$filename);
							
							$imgLibraryTable=TableRegistry::get('LibraryImages');
							$newImage=$imgLibraryTable->newEntity();
							
							if($this->request->data['save_to_library'] == '1'){
								
								$selectedCat='';
								$allCats=$this->LibraryCategories->find('all')->toArray();
								foreach($allCats as $catEntry){
									if($catEntry['id'] == $this->request->data['image_category']){
										$selectedCat=$catEntry['category_title'];
									}
								}
								
								//insert into image library db with details for future usage
								$newImage->image_title=$this->request->data['image_title'];
								$newImage->categories=$selectedCat;
								$newImage->filename=$filename;
								$newImage->time=time();
								$newImage->added_by=$this->Auth->user('id');
								$newImage->tags=$this->request->data['image_tags'];
								$newImage->status='Active';
							}else{
								//insert into image library db with no details for single usage
								$newImage->image_title=$this->request->data['imagefileupload']['name'];
								$newImage->categories='Misc';
								$newImage->filename=$filename;
								$newImage->time=time();
								$newImage->added_by=$this->Auth->user('id');
								$newImage->status='Single Use';
							}
							
							if($imgLibraryTable->save($newImage)){
								//insert the metadata for libraryimageid
								$this->updateLineItemMeta($lineItemID,'libraryimageid',$newImage->id);
								
								$this->aspectratiofix($newImage->id,600);

							}
							
							
						}

						

						if($this->request->data['com-fabric']=='1'){

							$this->updateLineItemMeta($lineItemID,'com-fabric','1');

						}
						
							/*PPSA-33 start */
						if(empty($this->request->data['fabric-cost-per-yard-custom-value'])) {

							$this->updateLineItemMeta($lineItemID,'fabric-cost-per-yard-custom-value','');

						}else {
						  $this->updateLineItemMeta($lineItemID,"fabric-cost-per-yard-custom-value",number_format($this->request->data['fabric-cost-per-yard-custom-value'], 2, '.', ''));  
						}
						
                        /*PPSA-33 end */
                        
                        
                    }
					/**PPSASSCRUM-29 start */	
					$orderDetails = $this->Orders->find('all',['conditions'=>['quote_id'=>$quoteID]])->first();

					if(isset($orderDetails) && !empty($orderDetails['id'])){

						$this->UpdatetoOrderLineItemTables($quoteID, $thisLineItem['id'],$orderDetails['id'],'orders');
						$this->UpdatetoOrderLineItemTables($quoteID, $thisLineItem['id'],$orderDetails['id'],'work_orders');
						//$this->savetoOrderLineItemTables($newItem->id, $quoteID,$orderDetails['id'], $table='orders' );
						//$this->savetoOrderLineItemTables($newItem->id, $quoteID,$orderDetails['id'], $table='work_orders' );	
					}
				  
					/**PPSASSCRUM-29 end */
			        
			        //update modified time
			        $this->updatequotemodifiedtime($quoteID);
			        
			        //update tier adjustments
			        if($this->recalculatequoteadjustments($quoteID)){
			            
			            //get the new item data for PMI Adjusted for extended price
			            $freshItemData=$this->QuoteLineItems->get($lineItemID);
			            $freshItemData->extended_price = ($freshItemData->pmi_adjusted * floatval($this->request->data['qty']));
			            $this->QuoteLineItems->save($freshItemData);
			            
			            
			            
						$this->Flash->success('Successfully edited Hardware Catchall line item');
						
						$this->logActivity($_SERVER['REQUEST_URI'],'Edited Hardware Catchall line ID '.$lineItemID,$this->Auth->user('id'),json_encode($this->request));
					    
					    //redirect to quote or orderedit view
					    if($thisQuote['status'] == 'editorder'){
            			    return $this->redirect('/orders/editlines/'.$thisQuote['order_id']);
            			}else{
            				return $this->redirect('/quotes/add/'.$quoteID.'/');
            			}
					}
			        
			    break;
			    
			    case 'newcatchall-blinds':
			        
			        //make sure the image is an image
			        if(isset($this->request->data['imagefileupload']['tmp_name']) && $this->request->data['image_method'] == 'upload'){
                        $allowedTypes=array('.jpg','jpeg','.png','.gif');
    					if(!in_array(strtolower(substr($this->request->data['imagefileupload']['name'],-4)),$allowedTypes)){
    					    $this->autoRender=false;
    					    echo '<h3><span style="color:red;">ERROR:</span> The filetype <u>'.substr($this->request->data['imagefileupload']['name'],-4).'</u> is not allowed in the image field.</h3>
                            <p><button type="button" onclick="history.go(-1);">Go Back</button></p>';
    					    exit;
    					}
                    }
			        
			        //save all the field values
			        $thisItem=$this->QuoteLineItems->get($lineItemData['id']);
			        
			        $thisItem->title = $this->request->data['line_item_title'];
                    $thisItem->description = $this->request->data['description'];
                    $thisItem->best_price = $this->request->data['price'];
                    $thisItem->qty = $this->request->data['qty'];
                    $thisItem->room_number = $this->request->data['location'];
                    
                    
                    $thisItem->unit = $this->request->data['unit_type'];
                    

                    if($this->QuoteLineItems->save($thisItem)){
                        //save the meta data changes
                        $ignoreKeys=array('process','product_subclass','imagefileupload','description','line_item_title','customer_id','save_to_library','image_title','image_category','image_tags','fabric_name','fabric_color','libraryimageid');
                        
                        foreach($this->request->data as $postkey => $postdata){
                            if(!in_array($postkey,$ignoreKeys) && $postdata != ''){
                                
                                if($postkey=='fabric_type'){
                                    $postkey='fabrictype';
                                }
                                
                                $this->updateLineItemMeta($lineItemID,$postkey,$postdata);
                                
                            }
                        }
                        
                        
                        $newLineItemMeta=$this->QuoteLineItemMeta->newEntity();
                        $newLineItemMeta->quote_item_id = $newItem->id;
                        $newLineItemMeta->meta_key='lineitemtype';
                        $newLineItemMeta->meta_value='newcatchall';
                        $this->QuoteLineItemMeta->save($newLineItemMeta);
                        
                        /*
                        if($this->request->data['fabric_type']=="existing"){

							$this->updateLineItemMeta($lineItemID,'fabricid',$this->request->data['fabric_id_with_color']);
							
							$selectedFabric=$this->Fabrics->get($this->request->data['fabric_id_with_color'])->toArray();
							$this->updateLineItemMeta($lineItemID,'fabric_name',$selectedFabric['fabric_name']);
							
                            $this->updateLineItemMeta($lineItemID,'fabric_color',$selectedFabric['color']);
							

						}elseif($this->request->data['fabric_type']=="typein"){

                            $this->updateLineItemMeta($lineItemID,'fabric_name',$this->request->data['fabric_name']);
							
							$this->updateLineItemMeta($lineItemID,'fabric_color',$this->request->data['fabric_color']);

						}
						*/

						if($this->request->data['image_method'] == 'library'){
						    
						    $this->updateLineItemMeta($lineItemID,'libraryimageid',$this->request->data['libraryimageid']);

							
						}elseif($this->request->data['image_method'] == 'upload'){
							
							//handle the upload, and process whether to save this to library or just do a one-off insert
							$filename=$this->request->data['imagefileupload']['name'];
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
							
							
							move_uploaded_file($this->request->data['imagefileupload']['tmp_name'],$_SERVER['DOCUMENT_ROOT']."/webroot/img/library/".$filename);
							
							$imgLibraryTable=TableRegistry::get('LibraryImages');
							$newImage=$imgLibraryTable->newEntity();
							
							if($this->request->data['save_to_library'] == '1'){
								
								$selectedCat='';
								$allCats=$this->LibraryCategories->find('all')->toArray();
								foreach($allCats as $catEntry){
									if($catEntry['id'] == $this->request->data['image_category']){
										$selectedCat=$catEntry['category_title'];
									}
								}
								
								//insert into image library db with details for future usage
								$newImage->image_title=$this->request->data['image_title'];
								$newImage->categories=$selectedCat;
								$newImage->filename=$filename;
								$newImage->time=time();
								$newImage->added_by=$this->Auth->user('id');
								$newImage->tags=$this->request->data['image_tags'];
								$newImage->status='Active';
							}else{
								//insert into image library db with no details for single usage
								$newImage->image_title=$this->request->data['imagefileupload']['name'];
								$newImage->categories='Misc';
								$newImage->filename=$filename;
								$newImage->time=time();
								$newImage->added_by=$this->Auth->user('id');
								$newImage->status='Single Use';
							}
							
							if($imgLibraryTable->save($newImage)){
								//insert the metadata for libraryimageid
								$this->updateLineItemMeta($lineItemID,'libraryimageid',$newImage->id);
								
								$this->aspectratiofix($newImage->id,600);

							}
							
							
						}

						

						if($this->request->data['com-fabric']=='1'){

							$this->updateLineItemMeta($lineItemID,'com-fabric','1');

						}
						
                        
                        	/*PPSA-33 start */
						if(empty($this->request->data['fabric-cost-per-yard-custom-value'])) {

							$this->updateLineItemMeta($lineItemID,'fabric-cost-per-yard-custom-value','');

						}else {
						  $this->updateLineItemMeta($lineItemID,'fabric-cost-per-yard-custom-value',number_format($this->request->data['fabric-cost-per-yard-custom-value'], 2, '.', ''));  
						}
						
                        /*PPSA-33 end */
                        
                    }
			        /**PPSASSCRUM-29 start */	
					$orderDetails = $this->Orders->find('all',['conditions'=>['quote_id'=>$quoteID]])->first();

					if(isset($orderDetails) && !empty($orderDetails['id'])){

						$this->UpdatetoOrderLineItemTables($quoteID, $thisLineItem['id'],$orderDetails['id'],'orders');
						$this->UpdatetoOrderLineItemTables($quoteID, $thisLineItem['id'],$orderDetails['id'],'work_orders');
						//$this->savetoOrderLineItemTables($newItem->id, $quoteID,$orderDetails['id'], $table='orders' );
						//$this->savetoOrderLineItemTables($newItem->id, $quoteID,$orderDetails['id'], $table='work_orders' );	
					}
				  
					/**PPSASSCRUM-29 end */
			        //update modified time
			        $this->updatequotemodifiedtime($quoteID);
			        
			        //update tier adjustments
			        if($this->recalculatequoteadjustments($quoteID)){
			            
			            //get the new item data for PMI Adjusted for extended price
			            $freshItemData=$this->QuoteLineItems->get($lineItemID);
			            $freshItemData->extended_price = ($freshItemData->pmi_adjusted * floatval($this->request->data['qty']));
			            $this->QuoteLineItems->save($freshItemData);
			            
			            
			            
						$this->Flash->success('Successfully edited Blinds Catchall line item');
						
						$this->logActivity($_SERVER['REQUEST_URI'],'Edited Blinds Catchall line ID '.$lineItemID,$this->Auth->user('id'),json_encode($this->request));
					    
					    //redirect to quote or orderedit view
					    if($thisQuote['status'] == 'editorder'){
            			    return $this->redirect('/orders/editlines/'.$thisQuote['order_id']);
            			}else{
            				return $this->redirect('/quotes/add/'.$quoteID.'/');
            			}
					}
					
			    break;
			    
			    case 'newcatchall-shades':
			        //make sure the image is an image
			        if(isset($this->request->data['imagefileupload']['tmp_name']) && $this->request->data['image_method'] == 'upload'){
                        $allowedTypes=array('.jpg','jpeg','.png','.gif');
    					if(!in_array(strtolower(substr($this->request->data['imagefileupload']['name'],-4)),$allowedTypes)){
    					    $this->autoRender=false;
    					    echo '<h3><span style="color:red;">ERROR:</span> The filetype <u>'.substr($this->request->data['imagefileupload']['name'],-4).'</u> is not allowed in the image field.</h3>
                            <p><button type="button" onclick="history.go(-1);">Go Back</button></p>';
    					    exit;
    					}
                    }
			        
			        //save all the field values
			        $thisItem=$this->QuoteLineItems->get($lineItemData['id']);
			        
			        $thisItem->title = $this->request->data['line_item_title'];
                    $thisItem->description = $this->request->data['description'];
                    $thisItem->best_price = $this->request->data['price'];
                    $thisItem->qty = $this->request->data['qty'];
                    $thisItem->room_number = $this->request->data['location'];
                    
                    
                    
                    $thisItem->unit = $this->request->data['unit_type'];
                    

                    if($this->QuoteLineItems->save($thisItem)){
                        //save the meta data changes
                        $ignoreKeys=array('process','product_subclass','imagefileupload','description','line_item_title','customer_id','save_to_library','image_title','image_category','image_tags','fabric_name','fabric_color','libraryimageid');
                        
                        foreach($this->request->data as $postkey => $postdata){
                            if(!in_array($postkey,$ignoreKeys) && $postdata != ''){
                                
                                if($postkey=='fabric_type'){
                                    $postkey='fabrictype';
                                }
                                
                                $this->updateLineItemMeta($lineItemID,$postkey,$postdata);
                                
                            }
                        }
                        
                        
                        $newLineItemMeta=$this->QuoteLineItemMeta->newEntity();
                        $newLineItemMeta->quote_item_id = $newItem->id;
                        $newLineItemMeta->meta_key='lineitemtype';
                        $newLineItemMeta->meta_value='newcatchall';
                        $this->QuoteLineItemMeta->save($newLineItemMeta);
                        
                        /*
                        if($this->request->data['fabric_type']=="existing"){

							$this->updateLineItemMeta($lineItemID,'fabricid',$this->request->data['fabric_id_with_color']);
							
							$selectedFabric=$this->Fabrics->get($this->request->data['fabric_id_with_color'])->toArray();
							$this->updateLineItemMeta($lineItemID,'fabric_name',$selectedFabric['fabric_name']);
							
                            $this->updateLineItemMeta($lineItemID,'fabric_color',$selectedFabric['color']);
							

						}elseif($this->request->data['fabric_type']=="typein"){

                            $this->updateLineItemMeta($lineItemID,'fabric_name',$this->request->data['fabric_name']);
							
							$this->updateLineItemMeta($lineItemID,'fabric_color',$this->request->data['fabric_color']);

						}
						*/

						if($this->request->data['image_method'] == 'library'){
						    
						    $this->updateLineItemMeta($lineItemID,'libraryimageid',$this->request->data['libraryimageid']);

							
						}elseif($this->request->data['image_method'] == 'upload'){
							
							//handle the upload, and process whether to save this to library or just do a one-off insert
							$filename=$this->request->data['imagefileupload']['name'];
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
							
							
							move_uploaded_file($this->request->data['imagefileupload']['tmp_name'],$_SERVER['DOCUMENT_ROOT']."/webroot/img/library/".$filename);
							
							$imgLibraryTable=TableRegistry::get('LibraryImages');
							$newImage=$imgLibraryTable->newEntity();
							
							if($this->request->data['save_to_library'] == '1'){
								
								$selectedCat='';
								$allCats=$this->LibraryCategories->find('all')->toArray();
								foreach($allCats as $catEntry){
									if($catEntry['id'] == $this->request->data['image_category']){
										$selectedCat=$catEntry['category_title'];
									}
								}
								
								//insert into image library db with details for future usage
								$newImage->image_title=$this->request->data['image_title'];
								$newImage->categories=$selectedCat;
								$newImage->filename=$filename;
								$newImage->time=time();
								$newImage->added_by=$this->Auth->user('id');
								$newImage->tags=$this->request->data['image_tags'];
								$newImage->status='Active';
							}else{
								//insert into image library db with no details for single usage
								$newImage->image_title=$this->request->data['imagefileupload']['name'];
								$newImage->categories='Misc';
								$newImage->filename=$filename;
								$newImage->time=time();
								$newImage->added_by=$this->Auth->user('id');
								$newImage->status='Single Use';
							}
							
							if($imgLibraryTable->save($newImage)){
								//insert the metadata for libraryimageid
								$this->updateLineItemMeta($lineItemID,'libraryimageid',$newImage->id);
								
								$this->aspectratiofix($newImage->id,600);

							}
							
							
						}

						

						if($this->request->data['com-fabric']=='1'){

							$this->updateLineItemMeta($lineItemID,'com-fabric','1');

						}
						
                        
                        
                    }
			        /**PPSASSCRUM-29 start */	
					$orderDetails = $this->Orders->find('all',['conditions'=>['quote_id'=>$quoteID]])->first();

					if(isset($orderDetails) && !empty($orderDetails['id'])){

						$this->UpdatetoOrderLineItemTables($quoteID, $thisLineItem['id'],$orderDetails['id'],'orders');
						$this->UpdatetoOrderLineItemTables($quoteID, $thisLineItem['id'],$orderDetails['id'],'work_orders');
						//$this->savetoOrderLineItemTables($newItem->id, $quoteID,$orderDetails['id'], $table='orders' );
						//$this->savetoOrderLineItemTables($newItem->id, $quoteID,$orderDetails['id'], $table='work_orders' );	
					}
				  
					/**PPSASSCRUM-29 end */
			        //update modified time
			        $this->updatequotemodifiedtime($quoteID);
			        
			        //update tier adjustments
			        if($this->recalculatequoteadjustments($quoteID)){
			            
			            //get the new item data for PMI Adjusted for extended price
			            $freshItemData=$this->QuoteLineItems->get($lineItemID);
			            $freshItemData->extended_price = ($freshItemData->pmi_adjusted * floatval($this->request->data['qty']));
			            $this->QuoteLineItems->save($freshItemData);
			            
			            
			            
						$this->Flash->success('Successfully edited Shades Catchall line item');
						
						$this->logActivity($_SERVER['REQUEST_URI'],'Edited Shades Catchall line ID '.$lineItemID,$this->Auth->user('id'),json_encode($this->request));
					    
					    //redirect to quote or orderedit view
					    if($thisQuote['status'] == 'editorder'){
            			    return $this->redirect('/orders/editlines/'.$thisQuote['order_id']);
            			}else{
            				return $this->redirect('/quotes/add/'.$quoteID.'/');
            			}
					}
			    break;
			    
			    case 'newcatchall-shutters':
			        //make sure the image is an image
			        if(isset($this->request->data['imagefileupload']['tmp_name']) && $this->request->data['image_method'] == 'upload'){
                        $allowedTypes=array('.jpg','jpeg','.png','.gif');
    					if(!in_array(strtolower(substr($this->request->data['imagefileupload']['name'],-4)),$allowedTypes)){
    					    $this->autoRender=false;
    					    echo '<h3><span style="color:red;">ERROR:</span> The filetype <u>'.substr($this->request->data['imagefileupload']['name'],-4).'</u> is not allowed in the image field.</h3>
                            <p><button type="button" onclick="history.go(-1);">Go Back</button></p>';
    					    exit;
    					}
                    }
			        
			        //save all the field values
			        $thisItem=$this->QuoteLineItems->get($lineItemData['id']);
			        
			        $thisItem->title = $this->request->data['line_item_title'];
                    $thisItem->description = $this->request->data['description'];
                    $thisItem->best_price = $this->request->data['price'];
                    $thisItem->qty = $this->request->data['qty'];
                    $thisItem->room_number = $this->request->data['location'];
                    
                    
                    $thisItem->unit = $this->request->data['unit_type'];
                    

                    if($this->QuoteLineItems->save($thisItem)){
                        //save the meta data changes
                        $ignoreKeys=array('process','product_subclass','imagefileupload','description','line_item_title','customer_id','save_to_library','image_title','image_category','image_tags','fabric_name','fabric_color','libraryimageid');
                        
                        foreach($this->request->data as $postkey => $postdata){
                            if(!in_array($postkey,$ignoreKeys) && $postdata != ''){
                                
                                if($postkey=='fabric_type'){
                                    $postkey='fabrictype';
                                }
                                
                                $this->updateLineItemMeta($lineItemID,$postkey,$postdata);
                                
                            }
                        }
                        
                        
                        $newLineItemMeta=$this->QuoteLineItemMeta->newEntity();
                        $newLineItemMeta->quote_item_id = $newItem->id;
                        $newLineItemMeta->meta_key='lineitemtype';
                        $newLineItemMeta->meta_value='newcatchall';
                        $this->QuoteLineItemMeta->save($newLineItemMeta);
                        
                        /*
                        if($this->request->data['fabric_type']=="existing"){

							$this->updateLineItemMeta($lineItemID,'fabricid',$this->request->data['fabric_id_with_color']);
							
							$selectedFabric=$this->Fabrics->get($this->request->data['fabric_id_with_color'])->toArray();
							$this->updateLineItemMeta($lineItemID,'fabric_name',$selectedFabric['fabric_name']);
							
                            $this->updateLineItemMeta($lineItemID,'fabric_color',$selectedFabric['color']);
							

						}elseif($this->request->data['fabric_type']=="typein"){

                            $this->updateLineItemMeta($lineItemID,'fabric_name',$this->request->data['fabric_name']);
							
							$this->updateLineItemMeta($lineItemID,'fabric_color',$this->request->data['fabric_color']);

						}
						*/

						if($this->request->data['image_method'] == 'library'){
						    
						    $this->updateLineItemMeta($lineItemID,'libraryimageid',$this->request->data['libraryimageid']);

							
						}elseif($this->request->data['image_method'] == 'upload'){
							
							//handle the upload, and process whether to save this to library or just do a one-off insert
							$filename=$this->request->data['imagefileupload']['name'];
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
							
							
							move_uploaded_file($this->request->data['imagefileupload']['tmp_name'],$_SERVER['DOCUMENT_ROOT']."/webroot/img/library/".$filename);
							
							$imgLibraryTable=TableRegistry::get('LibraryImages');
							$newImage=$imgLibraryTable->newEntity();
							
							if($this->request->data['save_to_library'] == '1'){
								
								$selectedCat='';
								$allCats=$this->LibraryCategories->find('all')->toArray();
								foreach($allCats as $catEntry){
									if($catEntry['id'] == $this->request->data['image_category']){
										$selectedCat=$catEntry['category_title'];
									}
								}
								
								//insert into image library db with details for future usage
								$newImage->image_title=$this->request->data['image_title'];
								$newImage->categories=$selectedCat;
								$newImage->filename=$filename;
								$newImage->time=time();
								$newImage->added_by=$this->Auth->user('id');
								$newImage->tags=$this->request->data['image_tags'];
								$newImage->status='Active';
							}else{
								//insert into image library db with no details for single usage
								$newImage->image_title=$this->request->data['imagefileupload']['name'];
								$newImage->categories='Misc';
								$newImage->filename=$filename;
								$newImage->time=time();
								$newImage->added_by=$this->Auth->user('id');
								$newImage->status='Single Use';
							}
							
							if($imgLibraryTable->save($newImage)){
								//insert the metadata for libraryimageid
								$this->updateLineItemMeta($lineItemID,'libraryimageid',$newImage->id);
								
								$this->aspectratiofix($newImage->id,600);

							}
							
							
						}

						

						if($this->request->data['com-fabric']=='1'){

							$this->updateLineItemMeta($lineItemID,'com-fabric','1');

						}
						
                        
                        
                    }
			        /**PPSASSCRUM-29 start */	
					$orderDetails = $this->Orders->find('all',['conditions'=>['quote_id'=>$quoteID]])->first();

					if(isset($orderDetails) && !empty($orderDetails['id'])){

						$this->UpdatetoOrderLineItemTables($quoteID, $thisLineItem['id'],$orderDetails['id'],'orders');
						$this->UpdatetoOrderLineItemTables($quoteID, $thisLineItem['id'],$orderDetails['id'],'work_orders');
						//$this->savetoOrderLineItemTables($newItem->id, $quoteID,$orderDetails['id'], $table='orders' );
						//$this->savetoOrderLineItemTables($newItem->id, $quoteID,$orderDetails['id'], $table='work_orders' );	
					}
				  
					/**PPSASSCRUM-29 end */
			        //update modified time
			        $this->updatequotemodifiedtime($quoteID);
			        
			        //update tier adjustments
			        if($this->recalculatequoteadjustments($quoteID)){
			            
			            //get the new item data for PMI Adjusted for extended price
			            $freshItemData=$this->QuoteLineItems->get($lineItemID);
			            $freshItemData->extended_price = ($freshItemData->pmi_adjusted * floatval($this->request->data['qty']));
			            $this->QuoteLineItems->save($freshItemData);
			            
			            
			            
						$this->Flash->success('Successfully edited Shutters Catchall line item');
						
						$this->logActivity($_SERVER['REQUEST_URI'],'Edited Shutters Catchall line ID '.$lineItemID,$this->Auth->user('id'),json_encode($this->request));
					    
					    //redirect to quote or orderedit view
					    if($thisQuote['status'] == 'editorder'){
            			    return $this->redirect('/orders/editlines/'.$thisQuote['order_id']);
            			}else{
            				return $this->redirect('/quotes/add/'.$quoteID.'/');
            			}
					}
			    break;
			    
			    case 'newcatchall-hwtmisc':
			        
			        //make sure the image is an image
			        if(isset($this->request->data['imagefileupload']['tmp_name']) && $this->request->data['image_method'] == 'upload'){
                        $allowedTypes=array('.jpg','jpeg','.png','.gif');
    					if(!in_array(strtolower(substr($this->request->data['imagefileupload']['name'],-4)),$allowedTypes)){
    					    $this->autoRender=false;
    					    echo '<h3><span style="color:red;">ERROR:</span> The filetype <u>'.substr($this->request->data['imagefileupload']['name'],-4).'</u> is not allowed in the image field.</h3>
                            <p><button type="button" onclick="history.go(-1);">Go Back</button></p>';
    					    exit;
    					}
                    }
			        
			        //save all the field values
			        $thisItem=$this->QuoteLineItems->get($lineItemData['id']);
			        
			        $thisItem->title = $this->request->data['line_item_title'];
                    $thisItem->description = $this->request->data['description'];
                    $thisItem->best_price = $this->request->data['price'];
                    $thisItem->qty = $this->request->data['qty'];
                    $thisItem->room_number = $this->request->data['location'];
                    
                    
                    $thisItem->unit = $this->request->data['unit_type'];
                    

                    if($this->QuoteLineItems->save($thisItem)){
                        //save the meta data changes
                        $ignoreKeys=array('process','product_subclass','imagefileupload','description','line_item_title','customer_id','save_to_library','image_title','image_category','image_tags','fabric_name','fabric_color','libraryimageid');
                        
                        foreach($this->request->data as $postkey => $postdata){
                            if(!in_array($postkey,$ignoreKeys) && $postdata != ''){
                                
                                if($postkey=='fabric_type'){
                                    $postkey='fabrictype';
                                }
                                
                                $this->updateLineItemMeta($lineItemID,$postkey,$postdata);
                                
                            }
                        }
                        
                        
                        $newLineItemMeta=$this->QuoteLineItemMeta->newEntity();
                        $newLineItemMeta->quote_item_id = $newItem->id;
                        $newLineItemMeta->meta_key='lineitemtype';
                        $newLineItemMeta->meta_value='newcatchall';
                        $this->QuoteLineItemMeta->save($newLineItemMeta);
                        
                        /*
                        if($this->request->data['fabric_type']=="existing"){

							$this->updateLineItemMeta($lineItemID,'fabricid',$this->request->data['fabric_id_with_color']);
							
							$selectedFabric=$this->Fabrics->get($this->request->data['fabric_id_with_color'])->toArray();
							$this->updateLineItemMeta($lineItemID,'fabric_name',$selectedFabric['fabric_name']);
							
                            $this->updateLineItemMeta($lineItemID,'fabric_color',$selectedFabric['color']);
							

						}elseif($this->request->data['fabric_type']=="typein"){

                            $this->updateLineItemMeta($lineItemID,'fabric_name',$this->request->data['fabric_name']);
							
							$this->updateLineItemMeta($lineItemID,'fabric_color',$this->request->data['fabric_color']);

						}
						*/

						if($this->request->data['image_method'] == 'library'){
						    
						    $this->updateLineItemMeta($lineItemID,'libraryimageid',$this->request->data['libraryimageid']);

							
						}elseif($this->request->data['image_method'] == 'upload'){
							
							//handle the upload, and process whether to save this to library or just do a one-off insert
							$filename=$this->request->data['imagefileupload']['name'];
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
							
							
							move_uploaded_file($this->request->data['imagefileupload']['tmp_name'],$_SERVER['DOCUMENT_ROOT']."/webroot/img/library/".$filename);
							
							$imgLibraryTable=TableRegistry::get('LibraryImages');
							$newImage=$imgLibraryTable->newEntity();
							
							if($this->request->data['save_to_library'] == '1'){
								
								$selectedCat='';
								$allCats=$this->LibraryCategories->find('all')->toArray();
								foreach($allCats as $catEntry){
									if($catEntry['id'] == $this->request->data['image_category']){
										$selectedCat=$catEntry['category_title'];
									}
								}
								
								//insert into image library db with details for future usage
								$newImage->image_title=$this->request->data['image_title'];
								$newImage->categories=$selectedCat;
								$newImage->filename=$filename;
								$newImage->time=time();
								$newImage->added_by=$this->Auth->user('id');
								$newImage->tags=$this->request->data['image_tags'];
								$newImage->status='Active';
							}else{
								//insert into image library db with no details for single usage
								$newImage->image_title=$this->request->data['imagefileupload']['name'];
								$newImage->categories='Misc';
								$newImage->filename=$filename;
								$newImage->time=time();
								$newImage->added_by=$this->Auth->user('id');
								$newImage->status='Single Use';
							}
							
							if($imgLibraryTable->save($newImage)){
								//insert the metadata for libraryimageid
								$this->updateLineItemMeta($lineItemID,'libraryimageid',$newImage->id);
								
								$this->aspectratiofix($newImage->id,600);

							}
							
							
						}

						

						if($this->request->data['com-fabric']=='1'){

							$this->updateLineItemMeta($lineItemID,'com-fabric','1');

						}
						
                        /*PPSA-33 start */
						if(empty($this->request->data['fabric-cost-per-yard-custom-value'])) {

						$this->updateLineItemMeta($lineItemID,'fabric-cost-per-yard-custom-value','');

						}else {
						  $this->updateLineItemMeta($lineItemID,"fabric-cost-per-yard-custom-value",number_format($this->request->data['fabric-cost-per-yard-custom-value'], 2, '.', ''));  
						}
						
                        /*PPSA-33 end */
                        
                    }
			        /**PPSASSCRUM-29 start */	
					$orderDetails = $this->Orders->find('all',['conditions'=>['quote_id'=>$quoteID]])->first();

					if(isset($orderDetails) && !empty($orderDetails['id'])){

						$this->UpdatetoOrderLineItemTables($quoteID, $thisLineItem['id'],$orderDetails['id'],'orders');
						$this->UpdatetoOrderLineItemTables($quoteID, $thisLineItem['id'],$orderDetails['id'],'work_orders');
						//$this->savetoOrderLineItemTables($newItem->id, $quoteID,$orderDetails['id'], $table='orders' );
						//$this->savetoOrderLineItemTables($newItem->id, $quoteID,$orderDetails['id'], $table='work_orders' );	
					}
				  
					/**PPSASSCRUM-29 end */
			        //update modified time
			        $this->updatequotemodifiedtime($quoteID);
			        
			        //update tier adjustments
			        if($this->recalculatequoteadjustments($quoteID)){
			            
			            //get the new item data for PMI Adjusted for extended price
			            $freshItemData=$this->QuoteLineItems->get($lineItemID);
			            $freshItemData->extended_price = ($freshItemData->pmi_adjusted * floatval($this->request->data['qty']));
			            $this->QuoteLineItems->save($freshItemData);
			            
			            
			            
						$this->Flash->success('Successfully edited HWT Misc Catchall line item');
						
						$this->logActivity($_SERVER['REQUEST_URI'],'Edited HWT Misc Catchall line ID '.$lineItemID,$this->Auth->user('id'),json_encode($this->request));
					    
					    //redirect to quote or orderedit view
					    if($thisQuote['status'] == 'editorder'){
            			    return $this->redirect('/orders/editlines/'.$thisQuote['order_id']);
            			}else{
            				return $this->redirect('/quotes/add/'.$quoteID.'/');
            			}
					}
			        
			    break;
			    
			    case 'newcatchall-swtmisc':
			        
			        //make sure the image is an image
			        if(isset($this->request->data['imagefileupload']['tmp_name']) && $this->request->data['image_method'] == 'upload'){
                        $allowedTypes=array('.jpg','jpeg','.png','.gif');
    					if(!in_array(strtolower(substr($this->request->data['imagefileupload']['name'],-4)),$allowedTypes)){
    					    $this->autoRender=false;
    					    echo '<h3><span style="color:red;">ERROR:</span> The filetype <u>'.substr($this->request->data['imagefileupload']['name'],-4).'</u> is not allowed in the image field.</h3>
                            <p><button type="button" onclick="history.go(-1);">Go Back</button></p>';
    					    exit;
    					}
                    }
			        
			        //save all the field values
			        $thisItem=$this->QuoteLineItems->get($lineItemData['id']);
			        
			        $thisItem->title = $this->request->data['line_item_title'];
                    $thisItem->description = $this->request->data['description'];
                    $thisItem->best_price = $this->request->data['price'];
                    $thisItem->qty = $this->request->data['qty'];
                    $thisItem->room_number = $this->request->data['location'];
                    
                    
                    $thisItem->unit = $this->request->data['unit_type'];
                    

                    if($this->QuoteLineItems->save($thisItem)){
                        //save the meta data changes
                        $ignoreKeys=array('process','product_subclass','imagefileupload','description','line_item_title','customer_id','save_to_library','image_title','image_category','image_tags','fabric_name','fabric_color','libraryimageid');
                        
                        foreach($this->request->data as $postkey => $postdata){
                            if(!in_array($postkey,$ignoreKeys) && $postdata != ''){
                                
                                if($postkey=='fabric_type'){
                                    $postkey='fabrictype';
                                }
                                
                                $this->updateLineItemMeta($lineItemID,$postkey,$postdata);
                                
                            }
                        }
                        
                        
                        $newLineItemMeta=$this->QuoteLineItemMeta->newEntity();
                        $newLineItemMeta->quote_item_id = $newItem->id;
                        $newLineItemMeta->meta_key='lineitemtype';
                        $newLineItemMeta->meta_value='newcatchall';
                        $this->QuoteLineItemMeta->save($newLineItemMeta);
                        
                        
                        if($this->request->data['fabric_type']=="existing"){

							$this->updateLineItemMeta($lineItemID,'fabricid',$this->request->data['fabric_id_with_color']);
							
							$selectedFabric=$this->Fabrics->get($this->request->data['fabric_id_with_color'])->toArray();
							$this->updateLineItemMeta($lineItemID,'fabric_name',$selectedFabric['fabric_name']);
							
                            $this->updateLineItemMeta($lineItemID,'fabric_color',$selectedFabric['color']);
							

						}elseif($this->request->data['fabric_type']=="typein"){

                            $this->updateLineItemMeta($lineItemID,'fabric_name',$this->request->data['fabric_name']);
							
							$this->updateLineItemMeta($lineItemID,'fabric_color',$this->request->data['fabric_color']);

						}/*PPSA -36 start */elseif($this->request->data['fabric_type']=="none"){

                            $this->updateLineItemMeta($lineItemID,'fabricid','0');
        					$this->updateLineItemMeta($lineItemID,'fabric_name','');
        					$this->updateLineItemMeta($lineItemID,'fabric_color','');

						}
	/*PPSA -36 end */

						if($this->request->data['image_method'] == 'library'){
						    
						    $this->updateLineItemMeta($lineItemID,'libraryimageid',$this->request->data['libraryimageid']);

							
						}elseif($this->request->data['image_method'] == 'upload'){
							
							//handle the upload, and process whether to save this to library or just do a one-off insert
							$filename=$this->request->data['imagefileupload']['name'];
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
							
							
							move_uploaded_file($this->request->data['imagefileupload']['tmp_name'],$_SERVER['DOCUMENT_ROOT']."/webroot/img/library/".$filename);
							
							$imgLibraryTable=TableRegistry::get('LibraryImages');
							$newImage=$imgLibraryTable->newEntity();
							
							if($this->request->data['save_to_library'] == '1'){
								
								$selectedCat='';
								$allCats=$this->LibraryCategories->find('all')->toArray();
								foreach($allCats as $catEntry){
									if($catEntry['id'] == $this->request->data['image_category']){
										$selectedCat=$catEntry['category_title'];
									}
								}
								
								//insert into image library db with details for future usage
								$newImage->image_title=$this->request->data['image_title'];
								$newImage->categories=$selectedCat;
								$newImage->filename=$filename;
								$newImage->time=time();
								$newImage->added_by=$this->Auth->user('id');
								$newImage->tags=$this->request->data['image_tags'];
								$newImage->status='Active';
							}else{
								//insert into image library db with no details for single usage
								$newImage->image_title=$this->request->data['imagefileupload']['name'];
								$newImage->categories='Misc';
								$newImage->filename=$filename;
								$newImage->time=time();
								$newImage->added_by=$this->Auth->user('id');
								$newImage->status='Single Use';
							}
							
							if($imgLibraryTable->save($newImage)){
								//insert the metadata for libraryimageid
								$this->updateLineItemMeta($lineItemID,'libraryimageid',$newImage->id);
								
								$this->aspectratiofix($newImage->id,600);

							}
							
							
						}

						

						if($this->request->data['com-fabric']=='1'){

							$this->updateLineItemMeta($lineItemID,'com-fabric','1');

						}
						
                        /*PPSA-33 start */
						if(empty($this->request->data['fabric-cost-per-yard-custom-value'])) {

						$this->updateLineItemMeta($lineItemID,'fabric-cost-per-yard-custom-value','');

						}else {
						  $this->updateLineItemMeta($lineItemID,'fabric-cost-per-yard-custom-value',number_format($this->request->data['fabric-cost-per-yard-custom-value'], 2, '.', ''));  
						}
						
                        /*PPSA-33 end */
                        
                    }
			        /**PPSASSCRUM-29 start */	
					$orderDetails = $this->Orders->find('all',['conditions'=>['quote_id'=>$quoteID]])->first();

					if(isset($orderDetails) && !empty($orderDetails['id'])){

						$this->UpdatetoOrderLineItemTables($quoteID, $thisLineItem['id'],$orderDetails['id'],'orders');
						$this->UpdatetoOrderLineItemTables($quoteID, $thisLineItem['id'],$orderDetails['id'],'work_orders');
						//$this->savetoOrderLineItemTables($newItem->id, $quoteID,$orderDetails['id'], $table='orders' );
						//$this->savetoOrderLineItemTables($newItem->id, $quoteID,$orderDetails['id'], $table='work_orders' );	
					}
				  
					/**PPSASSCRUM-29 end */
			        //update modified time
			        $this->updatequotemodifiedtime($quoteID);
			        
			        //update tier adjustments
			        if($this->recalculatequoteadjustments($quoteID)){
			            
			            //get the new item data for PMI Adjusted for extended price
			            $freshItemData=$this->QuoteLineItems->get($lineItemID);
			            $freshItemData->extended_price = ($freshItemData->pmi_adjusted * floatval($this->request->data['qty']));
			            $this->QuoteLineItems->save($freshItemData);
			            
			            
			            
						$this->Flash->success('Successfully edited SWT Misc Catchall line item');
						
						$this->logActivity($_SERVER['REQUEST_URI'],'Edited SWT Misc Catchall line ID '.$lineItemID,$this->Auth->user('id'),json_encode($this->request));
					    
					    //redirect to quote or orderedit view
					    if($thisQuote['status'] == 'editorder'){
            			    return $this->redirect('/orders/editlines/'.$thisQuote['order_id']);
            			}else{
            				return $this->redirect('/quotes/add/'.$quoteID.'/');
            			}
					}
			        
			    break;
			    
			    case 'newcatchall-service':
			        
			        //make sure the image is an image
			        if(isset($this->request->data['imagefileupload']['tmp_name']) && $this->request->data['image_method'] == 'upload'){
                        $allowedTypes=array('.jpg','jpeg','.png','.gif');
    					if(!in_array(strtolower(substr($this->request->data['imagefileupload']['name'],-4)),$allowedTypes)){
    					    $this->autoRender=false;
    					    echo '<h3><span style="color:red;">ERROR:</span> The filetype <u>'.substr($this->request->data['imagefileupload']['name'],-4).'</u> is not allowed in the image field.</h3>
                            <p><button type="button" onclick="history.go(-1);">Go Back</button></p>';
    					    exit;
    					}
                    }
			        
			        //save all the field values
			        $thisItem=$this->QuoteLineItems->get($lineItemData['id']);
			        
			        $thisItem->title = $this->request->data['line_item_title'];
                    $thisItem->description = $this->request->data['description'];
                    $thisItem->best_price = $this->request->data['price'];
                    $thisItem->qty = $this->request->data['qty'];
                    $thisItem->room_number = $this->request->data['location'];
                    
                    
                    $thisItem->product_subclass=$this->request->data['product_subclass'];
			        
                    $thisSubClass=$this->ProductSubclasses->get($this->request->data['product_subclass'])->toArray();
                    $thisItem->enable_tally=$thisSubClass['tally'];
                    
                    
                    $thisItem->unit = $this->request->data['unit_type'];
                    

                    if($this->QuoteLineItems->save($thisItem)){
                        //save the meta data changes
                        $ignoreKeys=array('process','product_subclass','imagefileupload','description','line_item_title','customer_id','save_to_library','image_title','image_category','image_tags','fabric_name','fabric_color','libraryimageid');
                        
                        foreach($this->request->data as $postkey => $postdata){
                            if(!in_array($postkey,$ignoreKeys) && $postdata != ''){
                                
                                if($postkey=='fabric_type'){
                                    $postkey='fabrictype';
                                }
                                
                                $this->updateLineItemMeta($lineItemID,$postkey,$postdata);
                                
                            }
                        }
                        
                        
                        $newLineItemMeta=$this->QuoteLineItemMeta->newEntity();
                        $newLineItemMeta->quote_item_id = $newItem->id;
                        $newLineItemMeta->meta_key='lineitemtype';
                        $newLineItemMeta->meta_value='newcatchall';
                        $this->QuoteLineItemMeta->save($newLineItemMeta);
                        
                        /*
                        if($this->request->data['fabric_type']=="existing"){

							$this->updateLineItemMeta($lineItemID,'fabricid',$this->request->data['fabric_id_with_color']);
							
							$selectedFabric=$this->Fabrics->get($this->request->data['fabric_id_with_color'])->toArray();
							$this->updateLineItemMeta($lineItemID,'fabric_name',$selectedFabric['fabric_name']);
							
                            $this->updateLineItemMeta($lineItemID,'fabric_color',$selectedFabric['color']);
							

						}elseif($this->request->data['fabric_type']=="typein"){

                            $this->updateLineItemMeta($lineItemID,'fabric_name',$this->request->data['fabric_name']);
							
							$this->updateLineItemMeta($lineItemID,'fabric_color',$this->request->data['fabric_color']);

						}
						*/

						if($this->request->data['image_method'] == 'library'){
						    
						    $this->updateLineItemMeta($lineItemID,'libraryimageid',$this->request->data['libraryimageid']);

							
						}elseif($this->request->data['image_method'] == 'upload'){
							
							//handle the upload, and process whether to save this to library or just do a one-off insert
							$filename=$this->request->data['imagefileupload']['name'];
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
							
							
							move_uploaded_file($this->request->data['imagefileupload']['tmp_name'],$_SERVER['DOCUMENT_ROOT']."/webroot/img/library/".$filename);
							
							$imgLibraryTable=TableRegistry::get('LibraryImages');
							$newImage=$imgLibraryTable->newEntity();
							
							if($this->request->data['save_to_library'] == '1'){
								
								$selectedCat='';
								$allCats=$this->LibraryCategories->find('all')->toArray();
								foreach($allCats as $catEntry){
									if($catEntry['id'] == $this->request->data['image_category']){
										$selectedCat=$catEntry['category_title'];
									}
								}
								
								//insert into image library db with details for future usage
								$newImage->image_title=$this->request->data['image_title'];
								$newImage->categories=$selectedCat;
								$newImage->filename=$filename;
								$newImage->time=time();
								$newImage->added_by=$this->Auth->user('id');
								$newImage->tags=$this->request->data['image_tags'];
								$newImage->status='Active';
							}else{
								//insert into image library db with no details for single usage
								$newImage->image_title=$this->request->data['imagefileupload']['name'];
								$newImage->categories='Misc';
								$newImage->filename=$filename;
								$newImage->time=time();
								$newImage->added_by=$this->Auth->user('id');
								$newImage->status='Single Use';
							}
							
							if($imgLibraryTable->save($newImage)){
								//insert the metadata for libraryimageid
								$this->updateLineItemMeta($lineItemID,'libraryimageid',$newImage->id);
								
								$this->aspectratiofix($newImage->id,600);

							}
							
							
						}

						

						if($this->request->data['com-fabric']=='1'){

							$this->updateLineItemMeta($lineItemID,'com-fabric','1');

						}
						
                        
                        
                    }
			        /**PPSASSCRUM-29 start */	
					$orderDetails = $this->Orders->find('all',['conditions'=>['quote_id'=>$quoteID]])->first();

					if(isset($orderDetails) && !empty($orderDetails['id'])){

						$this->UpdatetoOrderLineItemTables($quoteID, $thisLineItem['id'],$orderDetails['id'],'orders');
						$this->UpdatetoOrderLineItemTables($quoteID, $thisLineItem['id'],$orderDetails['id'],'work_orders');
						//$this->savetoOrderLineItemTables($newItem->id, $quoteID,$orderDetails['id'], $table='orders' );
						//$this->savetoOrderLineItemTables($newItem->id, $quoteID,$orderDetails['id'], $table='work_orders' );	
					}
				  
					/**PPSASSCRUM-29 end */
			        //update modified time
			        $this->updatequotemodifiedtime($quoteID);
			        
			        //update tier adjustments
			        if($this->recalculatequoteadjustments($quoteID)){
			            
			            //get the new item data for PMI Adjusted for extended price
			            $freshItemData=$this->QuoteLineItems->get($lineItemID);
			            $freshItemData->extended_price = ($freshItemData->pmi_adjusted * floatval($this->request->data['qty']));
			            $this->QuoteLineItems->save($freshItemData);
			            
			            
			            
						$this->Flash->success('Successfully edited Service Catchall line item');
						
						$this->logActivity($_SERVER['REQUEST_URI'],'Edited Service Catchall line ID '.$lineItemID,$this->Auth->user('id'),json_encode($this->request));
					    
					    //redirect to quote or orderedit view
					    if($thisQuote['status'] == 'editorder'){
            			    return $this->redirect('/orders/editlines/'.$thisQuote['order_id']);
            			}else{
            				return $this->redirect('/quotes/add/'.$quoteID.'/');
            			}
					}
			        
			    break;
			    
			    case 'newcatchall-valance':
			        
			        //make sure the image is an image
			        if(isset($this->request->data['imagefileupload']['tmp_name']) && $this->request->data['image_method'] == 'upload'){
                        $allowedTypes=array('.jpg','jpeg','.png','.gif');
    					if(!in_array(strtolower(substr($this->request->data['imagefileupload']['name'],-4)),$allowedTypes)){
    					    $this->autoRender=false;
    					    echo '<h3><span style="color:red;">ERROR:</span> The filetype <u>'.substr($this->request->data['imagefileupload']['name'],-4).'</u> is not allowed in the image field.</h3>
                            <p><button type="button" onclick="history.go(-1);">Go Back</button></p>';
    					    exit;
    					}
                    }
			        
			        //save all the field values
			        $thisItem=$this->QuoteLineItems->get($lineItemData['id']);
			        
			        $thisItem->title = $this->request->data['line_item_title'];
                    $thisItem->description = $this->request->data['description'];
                    $thisItem->best_price = $this->request->data['price'];
                    $thisItem->qty = $this->request->data['qty'];
                    $thisItem->room_number = $this->request->data['location'];
                    
                    
                    $thisItem->unit = $this->request->data['unit_type'];
                    

                    if($this->QuoteLineItems->save($thisItem)){
                        //save the meta data changes
                        $ignoreKeys=array('process','product_subclass','imagefileupload','description','line_item_title','customer_id','save_to_library','image_title','image_category','image_tags','fabric_name','fabric_color','libraryimageid');
                        
                        foreach($this->request->data as $postkey => $postdata){
                            if(!in_array($postkey,$ignoreKeys) && $postdata != ''){
                                
                                if($postkey=='fabric_type'){
                                    $postkey='fabrictype';
                                }
                                
                                $this->updateLineItemMeta($lineItemID,$postkey,$postdata);
                                
                            }
                        }
                        
                        if(!isset($this->request->data['fl-short'])){
                            $this->deleteLineItemMeta($lineItemID,'fl-short');
                        }
                        if(!isset($this->request->data['fl-mid'])){
                            $this->deleteLineItemMeta($lineItemID,'fl-mid');
                        }
                        
                        $newLineItemMeta=$this->QuoteLineItemMeta->newEntity();
                        $newLineItemMeta->quote_item_id = $newItem->id;
                        $newLineItemMeta->meta_key='lineitemtype';
                        $newLineItemMeta->meta_value='newcatchall';
                        $this->QuoteLineItemMeta->save($newLineItemMeta);
                        
                        
                        if($this->request->data['fabric_type']=="existing"){

							$this->updateLineItemMeta($lineItemID,'fabricid',$this->request->data['fabric_id_with_color']);
							
							$selectedFabric=$this->Fabrics->get($this->request->data['fabric_id_with_color'])->toArray();
							$this->updateLineItemMeta($lineItemID,'fabric_name',$selectedFabric['fabric_name']);
							
                            $this->updateLineItemMeta($lineItemID,'fabric_color',$selectedFabric['color']);
							

						}elseif($this->request->data['fabric_type']=="typein"){

                            $this->updateLineItemMeta($lineItemID,'fabric_name',$this->request->data['fabric_name']);
							
							$this->updateLineItemMeta($lineItemID,'fabric_color',$this->request->data['fabric_color']);

						}/*PPSA -36 start */elseif($this->request->data['fabric_type']=="none"){

                            $this->updateLineItemMeta($lineItemID,'fabricid','0');
        					$this->updateLineItemMeta($lineItemID,'fabric_name','');
        					$this->updateLineItemMeta($lineItemID,'fabric_color','');

						}
	/*PPSA -36 end */

						if($this->request->data['image_method'] == 'library'){
						    
						    $this->updateLineItemMeta($lineItemID,'libraryimageid',$this->request->data['libraryimageid']);

							
						}elseif($this->request->data['image_method'] == 'upload'){
							
							//handle the upload, and process whether to save this to library or just do a one-off insert
							$filename=$this->request->data['imagefileupload']['name'];
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
							
							
							move_uploaded_file($this->request->data['imagefileupload']['tmp_name'],$_SERVER['DOCUMENT_ROOT']."/webroot/img/library/".$filename);
							
							$imgLibraryTable=TableRegistry::get('LibraryImages');
							$newImage=$imgLibraryTable->newEntity();
							
							if($this->request->data['save_to_library'] == '1'){
								
								$selectedCat='';
								$allCats=$this->LibraryCategories->find('all')->toArray();
								foreach($allCats as $catEntry){
									if($catEntry['id'] == $this->request->data['image_category']){
										$selectedCat=$catEntry['category_title'];
									}
								}
								
								//insert into image library db with details for future usage
								$newImage->image_title=$this->request->data['image_title'];
								$newImage->categories=$selectedCat;
								$newImage->filename=$filename;
								$newImage->time=time();
								$newImage->added_by=$this->Auth->user('id');
								$newImage->tags=$this->request->data['image_tags'];
								$newImage->status='Active';
							}else{
								//insert into image library db with no details for single usage
								$newImage->image_title=$this->request->data['imagefileupload']['name'];
								$newImage->categories='Misc';
								$newImage->filename=$filename;
								$newImage->time=time();
								$newImage->added_by=$this->Auth->user('id');
								$newImage->status='Single Use';
							}
							
							if($imgLibraryTable->save($newImage)){
								//insert the metadata for libraryimageid
								$this->updateLineItemMeta($lineItemID,'libraryimageid',$newImage->id);
								
								$this->aspectratiofix($newImage->id,600);

							}
							
							
						}

						

						if($this->request->data['com-fabric']=='1'){

							$this->updateLineItemMeta($lineItemID,'com-fabric','1');

						}
						
                        
                        /*PPSA-33 start */
						if(empty($this->request->data['fabric-cost-per-yard-custom-value'])) {

							$this->updateLineItemMeta($lineItemID,'fabric-cost-per-yard-custom-value','');

						}else {
						  $this->updateLineItemMeta($lineItemID,"fabric-cost-per-yard-custom-value",number_format($this->request->data['fabric-cost-per-yard-custom-value'], 2, '.', ''));  
						}
						
                        /*PPSA-33 end */
                    }
			        /**PPSASSCRUM-29 start */	
					$orderDetails = $this->Orders->find('all',['conditions'=>['quote_id'=>$quoteID]])->first();

					if(isset($orderDetails) && !empty($orderDetails['id'])){

						$this->UpdatetoOrderLineItemTables($quoteID, $thisLineItem['id'],$orderDetails['id'],'orders');
						$this->UpdatetoOrderLineItemTables($quoteID, $thisLineItem['id'],$orderDetails['id'],'work_orders');
						//$this->savetoOrderLineItemTables($newItem->id, $quoteID,$orderDetails['id'], $table='orders' );
						//$this->savetoOrderLineItemTables($newItem->id, $quoteID,$orderDetails['id'], $table='work_orders' );	
					}
				  
					/**PPSASSCRUM-29 end */
			        //update modified time
			        $this->updatequotemodifiedtime($quoteID);
			        
			        //update tier adjustments
			        if($this->recalculatequoteadjustments($quoteID)){
			            
			            //get the new item data for PMI Adjusted for extended price
			            $freshItemData=$this->QuoteLineItems->get($lineItemID);
			            $freshItemData->extended_price = ($freshItemData->pmi_adjusted * floatval($this->request->data['qty']));
			            $this->QuoteLineItems->save($freshItemData);
			            
			            
			            
						$this->Flash->success('Successfully edited Valance Catchall line item');
						
						$this->logActivity($_SERVER['REQUEST_URI'],'Edited Valance Catchall line ID '.$lineItemID,$this->Auth->user('id'),json_encode($this->request));
					    
					    //redirect to quote or orderedit view
					    if($thisQuote['status'] == 'editorder'){
            			    return $this->redirect('/orders/editlines/'.$thisQuote['order_id']);
            			}else{
            				return $this->redirect('/quotes/add/'.$quoteID.'/');
            			}
					}
			        
			    break;
			    
			    case 'newcatchall-drapery':
			        
			        //make sure the image is an image
			        if(isset($this->request->data['imagefileupload']['tmp_name']) && $this->request->data['image_method'] == 'upload'){
                        $allowedTypes=array('.jpg','jpeg','.png','.gif');
    					if(!in_array(strtolower(substr($this->request->data['imagefileupload']['name'],-4)),$allowedTypes)){
    					    $this->autoRender=false;
    					    echo '<h3><span style="color:red;">ERROR:</span> The filetype <u>'.substr($this->request->data['imagefileupload']['name'],-4).'</u> is not allowed in the image field.</h3>
                            <p><button type="button" onclick="history.go(-1);">Go Back</button></p>';
    					    exit;
    					}
                    }
			        
			        //save all the field values
			        $thisItem=$this->QuoteLineItems->get($lineItemData['id']);
			        
			        $thisItem->title = $this->request->data['line_item_title'];
                    $thisItem->description = $this->request->data['description'];
                    $thisItem->best_price = $this->request->data['price'];
                    $thisItem->qty = $this->request->data['qty'];
                    $thisItem->room_number = $this->request->data['location'];
                    
                    
                    $thisItem->unit = $this->request->data['unit_type'];
                    

                    if($this->QuoteLineItems->save($thisItem)){
                        //save the meta data changes
                        $ignoreKeys=array('process','product_subclass','imagefileupload','description','line_item_title','customer_id','save_to_library','image_title','image_category','image_tags','fabric_name','fabric_color','libraryimageid');
                        
                        foreach($this->request->data as $postkey => $postdata){
                            if(!in_array($postkey,$ignoreKeys) && $postdata != ''){
                                
                                if($postkey=='fabric_type'){
                                    $postkey='fabrictype';
                                }
                                
                                $this->updateLineItemMeta($lineItemID,$postkey,$postdata);
                                
                            }
                        }
                        
                        
                        $newLineItemMeta=$this->QuoteLineItemMeta->newEntity();
                        $newLineItemMeta->quote_item_id = $newItem->id;
                        $newLineItemMeta->meta_key='lineitemtype';
                        $newLineItemMeta->meta_value='newcatchall';
                        $this->QuoteLineItemMeta->save($newLineItemMeta);
                        
                        
                        if($this->request->data['fabric_type']=="existing"){

							$this->updateLineItemMeta($lineItemID,'fabricid',$this->request->data['fabric_id_with_color']);
							
							$selectedFabric=$this->Fabrics->get($this->request->data['fabric_id_with_color'])->toArray();
							$this->updateLineItemMeta($lineItemID,'fabric_name',$selectedFabric['fabric_name']);
							
                            $this->updateLineItemMeta($lineItemID,'fabric_color',$selectedFabric['color']);
							

						}elseif($this->request->data['fabric_type']=="typein"){

                            $this->updateLineItemMeta($lineItemID,'fabric_name',$this->request->data['fabric_name']);
							
							$this->updateLineItemMeta($lineItemID,'fabric_color',$this->request->data['fabric_color']);

						}/*PPSA -36 start */elseif($this->request->data['fabric_type']=="none"){

                            $this->updateLineItemMeta($lineItemID,'fabricid','0');
        					$this->updateLineItemMeta($lineItemID,'fabric_name','');
        					$this->updateLineItemMeta($lineItemID,'fabric_color','');

						}
	/*PPSA -36 end */

						if($this->request->data['image_method'] == 'library'){
						    
						    $this->updateLineItemMeta($lineItemID,'libraryimageid',$this->request->data['libraryimageid']);

							
						}elseif($this->request->data['image_method'] == 'upload'){
							
							//handle the upload, and process whether to save this to library or just do a one-off insert
							$filename=$this->request->data['imagefileupload']['name'];
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
							
							
							move_uploaded_file($this->request->data['imagefileupload']['tmp_name'],$_SERVER['DOCUMENT_ROOT']."/webroot/img/library/".$filename);
							
							$imgLibraryTable=TableRegistry::get('LibraryImages');
							$newImage=$imgLibraryTable->newEntity();
							
							if($this->request->data['save_to_library'] == '1'){
								
								$selectedCat='';
								$allCats=$this->LibraryCategories->find('all')->toArray();
								foreach($allCats as $catEntry){
									if($catEntry['id'] == $this->request->data['image_category']){
										$selectedCat=$catEntry['category_title'];
									}
								}
								
								//insert into image library db with details for future usage
								$newImage->image_title=$this->request->data['image_title'];
								$newImage->categories=$selectedCat;
								$newImage->filename=$filename;
								$newImage->time=time();
								$newImage->added_by=$this->Auth->user('id');
								$newImage->tags=$this->request->data['image_tags'];
								$newImage->status='Active';
							}else{
								//insert into image library db with no details for single usage
								$newImage->image_title=$this->request->data['imagefileupload']['name'];
								$newImage->categories='Misc';
								$newImage->filename=$filename;
								$newImage->time=time();
								$newImage->added_by=$this->Auth->user('id');
								$newImage->status='Single Use';
							}
							
							if($imgLibraryTable->save($newImage)){
								//insert the metadata for libraryimageid
								$this->updateLineItemMeta($lineItemID,'libraryimageid',$newImage->id);
								
								$this->aspectratiofix($newImage->id,600);

							}
							
							
						}

						

						if($this->request->data['com-fabric']=='1'){

							$this->updateLineItemMeta($lineItemID,'com-fabric','1');

						}
						
                        	/*PPSA-33 start */
						if(empty($this->request->data['fabric-cost-per-yard-custom-value'])) {

						 $this->updateLineItemMeta($lineItemID,'fabric-cost-per-yard-custom-value','');

						}else {
						  $this->updateLineItemMeta($lineItemID,'fabric-cost-per-yard-custom-value',number_format($this->request->data['fabric-cost-per-yard-custom-value'], 2, '.', ''));  
						}
						
                        /*PPSA-33 end */
                        
                    }
			        /**PPSASSCRUM-29 start */	
					$orderDetails = $this->Orders->find('all',['conditions'=>['quote_id'=>$quoteID]])->first();

					if(isset($orderDetails) && !empty($orderDetails['id'])){

						$this->UpdatetoOrderLineItemTables($quoteID, $thisLineItem['id'],$orderDetails['id'],'orders');
						$this->UpdatetoOrderLineItemTables($quoteID, $thisLineItem['id'],$orderDetails['id'],'work_orders');
						//$this->savetoOrderLineItemTables($newItem->id, $quoteID,$orderDetails['id'], $table='orders' );
						//$this->savetoOrderLineItemTables($newItem->id, $quoteID,$orderDetails['id'], $table='work_orders' );	
					}
				  
					/**PPSASSCRUM-29 end */
			        //update modified time
			        $this->updatequotemodifiedtime($quoteID);
			        
			        //update tier adjustments
			        if($this->recalculatequoteadjustments($quoteID)){
			            
			            //get the new item data for PMI Adjusted for extended price
			            $freshItemData=$this->QuoteLineItems->get($lineItemID);
			            $freshItemData->extended_price = ($freshItemData->pmi_adjusted * floatval($this->request->data['qty']));
			            $this->QuoteLineItems->save($freshItemData);
			            
			            
			            
						$this->Flash->success('Successfully edited Drapery Catchall line item');
						
						$this->logActivity($_SERVER['REQUEST_URI'],'Edited Drapery Catchall line ID '.$lineItemID,$this->Auth->user('id'),json_encode($this->request));
					    
					    //redirect to quote or orderedit view
					    if($thisQuote['status'] == 'editorder'){
            			    return $this->redirect('/orders/editlines/'.$thisQuote['order_id']);
            			}else{
            				return $this->redirect('/quotes/add/'.$quoteID.'/');
            			}
					}
			        
			    break;
			    
			    case 'newcatchall-cornice':
			        
			        //make sure the image is an image
			        if(isset($this->request->data['imagefileupload']['tmp_name']) && $this->request->data['image_method'] == 'upload'){
                        $allowedTypes=array('.jpg','jpeg','.png','.gif');
    					if(!in_array(strtolower(substr($this->request->data['imagefileupload']['name'],-4)),$allowedTypes)){
    					    $this->autoRender=false;
    					    echo '<h3><span style="color:red;">ERROR:</span> The filetype <u>'.substr($this->request->data['imagefileupload']['name'],-4).'</u> is not allowed in the image field.</h3>
                            <p><button type="button" onclick="history.go(-1);">Go Back</button></p>';
    					    exit;
    					}
                    }
			        
			        //save all the field values
			        $thisItem=$this->QuoteLineItems->get($lineItemData['id']);
			        
			        $thisItem->title = $this->request->data['line_item_title'];
                    $thisItem->description = $this->request->data['description'];
                    $thisItem->best_price = $this->request->data['price'];
                    $thisItem->qty = $this->request->data['qty'];
                    $thisItem->room_number = $this->request->data['location'];
                    
                    
                    $thisItem->unit = $this->request->data['unit_type'];
                    

                    if($this->QuoteLineItems->save($thisItem)){
                        //save the meta data changes
                        $ignoreKeys=array('process','product_subclass','imagefileupload','description','line_item_title','customer_id','save_to_library','image_title','image_category','image_tags','fabric_name','fabric_color','libraryimageid');
                        
                        foreach($this->request->data as $postkey => $postdata){
                            if(!in_array($postkey,$ignoreKeys) && $postdata != ''){
                                
                                if($postkey=='fabric_type'){
                                    $postkey='fabrictype';
                                }
                                
                                $this->updateLineItemMeta($lineItemID,$postkey,$postdata);
                                
                            }
                        }
                        
                        
                        if(!isset($this->request->data['fl-short'])){
                            $this->deleteLineItemMeta($lineItemID,'fl-short');
                        }
                        if(!isset($this->request->data['fl-mid'])){
                            $this->deleteLineItemMeta($lineItemID,'fl-mid');
                        }
                        
                        
                        $newLineItemMeta=$this->QuoteLineItemMeta->newEntity();
                        $newLineItemMeta->quote_item_id = $newItem->id;
                        $newLineItemMeta->meta_key='lineitemtype';
                        $newLineItemMeta->meta_value='newcatchall';
                        $this->QuoteLineItemMeta->save($newLineItemMeta);
                        
                        
                        if($this->request->data['fabric_type']=="existing"){

							$this->updateLineItemMeta($lineItemID,'fabricid',$this->request->data['fabric_id_with_color']);
							
							$selectedFabric=$this->Fabrics->get($this->request->data['fabric_id_with_color'])->toArray();
							$this->updateLineItemMeta($lineItemID,'fabric_name',$selectedFabric['fabric_name']);
							
                            $this->updateLineItemMeta($lineItemID,'fabric_color',$selectedFabric['color']);
							

						}elseif($this->request->data['fabric_type']=="typein"){

                            $this->updateLineItemMeta($lineItemID,'fabric_name',$this->request->data['fabric_name']);
							
							$this->updateLineItemMeta($lineItemID,'fabric_color',$this->request->data['fabric_color']);

						}/*PPSA -36 start */elseif($this->request->data['fabric_type']=="none"){

                            $this->updateLineItemMeta($lineItemID,'fabricid','0');
        					$this->updateLineItemMeta($lineItemID,'fabric_name','');
        					$this->updateLineItemMeta($lineItemID,'fabric_color','');

						}
	/*PPSA -36 end */

						if($this->request->data['image_method'] == 'library'){
						    
						    $this->updateLineItemMeta($lineItemID,'libraryimageid',$this->request->data['libraryimageid']);

							
						}elseif($this->request->data['image_method'] == 'upload'){
							
							//handle the upload, and process whether to save this to library or just do a one-off insert
							$filename=$this->request->data['imagefileupload']['name'];
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
							
							
							move_uploaded_file($this->request->data['imagefileupload']['tmp_name'],$_SERVER['DOCUMENT_ROOT']."/webroot/img/library/".$filename);
							
							$imgLibraryTable=TableRegistry::get('LibraryImages');
							$newImage=$imgLibraryTable->newEntity();
							
							if($this->request->data['save_to_library'] == '1'){
								
								$selectedCat='';
								$allCats=$this->LibraryCategories->find('all')->toArray();
								foreach($allCats as $catEntry){
									if($catEntry['id'] == $this->request->data['image_category']){
										$selectedCat=$catEntry['category_title'];
									}
								}
								
								//insert into image library db with details for future usage
								$newImage->image_title=$this->request->data['image_title'];
								$newImage->categories=$selectedCat;
								$newImage->filename=$filename;
								$newImage->time=time();
								$newImage->added_by=$this->Auth->user('id');
								$newImage->tags=$this->request->data['image_tags'];
								$newImage->status='Active';
							}else{
								//insert into image library db with no details for single usage
								$newImage->image_title=$this->request->data['imagefileupload']['name'];
								$newImage->categories='Misc';
								$newImage->filename=$filename;
								$newImage->time=time();
								$newImage->added_by=$this->Auth->user('id');
								$newImage->status='Single Use';
							}
							
							if($imgLibraryTable->save($newImage)){
								//insert the metadata for libraryimageid
								$this->updateLineItemMeta($lineItemID,'libraryimageid',$newImage->id);
								
								$this->aspectratiofix($newImage->id,600);

							}
							
							
						}

						

						if($this->request->data['com-fabric']=='1'){

							$this->updateLineItemMeta($lineItemID,'com-fabric','1');

						}
						
                        /*PPSA-33 start */
						if(empty($this->request->data['fabric-cost-per-yard-custom-value'])) {

						$this->updateLineItemMeta($lineItemID,'fabric-cost-per-yard-custom-value','');

						}else {
						  $this->updateLineItemMeta($lineItemID,"fabric-cost-per-yard-custom-value",number_format($this->request->data['fabric-cost-per-yard-custom-value'], 2, '.', ''));  
						}
						
                        /*PPSA-33 end */
                        
                    }
			        /**PPSASSCRUM-29 start */	
					$orderDetails = $this->Orders->find('all',['conditions'=>['quote_id'=>$quoteID]])->first();

					if(isset($orderDetails) && !empty($orderDetails['id'])){

						$this->UpdatetoOrderLineItemTables($quoteID, $thisLineItem['id'],$orderDetails['id'],'orders');
						$this->UpdatetoOrderLineItemTables($quoteID, $thisLineItem['id'],$orderDetails['id'],'work_orders');
						//$this->savetoOrderLineItemTables($newItem->id, $quoteID,$orderDetails['id'], $table='orders' );
						//$this->savetoOrderLineItemTables($newItem->id, $quoteID,$orderDetails['id'], $table='work_orders' );	
					}
				  
					/**PPSASSCRUM-29 end */
			        //update modified time
			        $this->updatequotemodifiedtime($quoteID);
			        
			        //update tier adjustments
			        if($this->recalculatequoteadjustments($quoteID)){
			            
			            //get the new item data for PMI Adjusted for extended price
			            $freshItemData=$this->QuoteLineItems->get($lineItemID);
			            $freshItemData->extended_price = ($freshItemData->pmi_adjusted * floatval($this->request->data['qty']));
			            $this->QuoteLineItems->save($freshItemData);
			            
			            
			            
						$this->Flash->success('Successfully edited Cornice Catchall line item');
						
						$this->logActivity($_SERVER['REQUEST_URI'],'Edited Cornice Catchall line ID '.$lineItemID,$this->Auth->user('id'),json_encode($this->request));
					    
					    //redirect to quote or orderedit view
					    if($thisQuote['status'] == 'editorder'){
            			    return $this->redirect('/orders/editlines/'.$thisQuote['order_id']);
            			}else{
            				return $this->redirect('/quotes/add/'.$quoteID.'/');
            			}
					}
			        
			    break;
			    
			    case 'newcatchall-cubicle':
			        
			        //make sure the image is an image
			        if(isset($this->request->data['imagefileupload']['tmp_name']) && $this->request->data['image_method'] == 'upload'){
                        $allowedTypes=array('.jpg','jpeg','.png','.gif');
    					if(!in_array(strtolower(substr($this->request->data['imagefileupload']['name'],-4)),$allowedTypes)){
    					    $this->autoRender=false;
    					    echo '<h3><span style="color:red;">ERROR:</span> The filetype <u>'.substr($this->request->data['imagefileupload']['name'],-4).'</u> is not allowed in the image field.</h3>
                            <p><button type="button" onclick="history.go(-1);">Go Back</button></p>';
    					    exit;
    					}
                    }
			        
			        //save all the field values
			        $thisItem=$this->QuoteLineItems->get($lineItemData['id']);
			        
			        $thisItem->title = $this->request->data['line_item_title'];
                    $thisItem->description = $this->request->data['description'];
                    $thisItem->best_price = $this->request->data['price'];
                    $thisItem->qty = $this->request->data['qty'];
                    $thisItem->room_number = $this->request->data['location'];
                    
                    
                    $thisItem->product_subclass=$this->request->data['product_subclass'];
			        
                    $thisSubClass=$this->ProductSubclasses->get($this->request->data['product_subclass'])->toArray();
                    $thisItem->enable_tally=$thisSubClass['tally'];
                    
                    
                    $thisItem->unit = $this->request->data['unit_type'];
                    

                    if($this->QuoteLineItems->save($thisItem)){
                        //save the meta data changes
                        $ignoreKeys=array('process','product_subclass','imagefileupload','description','line_item_title','customer_id','save_to_library','image_title','image_category','image_tags','fabric_name','fabric_color','libraryimageid');
                        
                        foreach($this->request->data as $postkey => $postdata){
                            if(!in_array($postkey,$ignoreKeys) && $postdata != ''){
                                
                                if($postkey=='fabric_type'){
                                    $postkey='fabrictype';
                                }
                                
                                $this->updateLineItemMeta($lineItemID,$postkey,$postdata);
                                
                            }
                        }
                        
                        
                        $newLineItemMeta=$this->QuoteLineItemMeta->newEntity();
                        $newLineItemMeta->quote_item_id = $newItem->id;
                        $newLineItemMeta->meta_key='lineitemtype';
                        $newLineItemMeta->meta_value='newcatchall';
                        $this->QuoteLineItemMeta->save($newLineItemMeta);
                        
                        
                        if($this->request->data['fabric_type']=="existing"){

							$this->updateLineItemMeta($lineItemID,'fabricid',$this->request->data['fabric_id_with_color']);
							
							$selectedFabric=$this->Fabrics->get($this->request->data['fabric_id_with_color'])->toArray();
							$this->updateLineItemMeta($lineItemID,'fabric_name',$selectedFabric['fabric_name']);
							
                            $this->updateLineItemMeta($lineItemID,'fabric_color',$selectedFabric['color']);
							

						}elseif($this->request->data['fabric_type']=="typein"){

                            $this->updateLineItemMeta($lineItemID,'fabric_name',$this->request->data['fabric_name']);
							
							$this->updateLineItemMeta($lineItemID,'fabric_color',$this->request->data['fabric_color']);

						}
						/*PPSA -36 start */elseif($this->request->data['fabric_type']=="none"){

                            $this->updateLineItemMeta($lineItemID,'fabricid','0');
        					$this->updateLineItemMeta($lineItemID,'fabric_name','');
        					$this->updateLineItemMeta($lineItemID,'fabric_color','');

						}
	/*PPSA -36 end */

						if($this->request->data['image_method'] == 'library'){
						    
						    $this->updateLineItemMeta($lineItemID,'libraryimageid',$this->request->data['libraryimageid']);

							
						}elseif($this->request->data['image_method'] == 'upload'){
							
							//handle the upload, and process whether to save this to library or just do a one-off insert
							$filename=$this->request->data['imagefileupload']['name'];
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
							
							
							move_uploaded_file($this->request->data['imagefileupload']['tmp_name'],$_SERVER['DOCUMENT_ROOT']."/webroot/img/library/".$filename);
							
							$imgLibraryTable=TableRegistry::get('LibraryImages');
							$newImage=$imgLibraryTable->newEntity();
							
							if($this->request->data['save_to_library'] == '1'){
								
								$selectedCat='';
								$allCats=$this->LibraryCategories->find('all')->toArray();
								foreach($allCats as $catEntry){
									if($catEntry['id'] == $this->request->data['image_category']){
										$selectedCat=$catEntry['category_title'];
									}
								}
								
								//insert into image library db with details for future usage
								$newImage->image_title=$this->request->data['image_title'];
								$newImage->categories=$selectedCat;
								$newImage->filename=$filename;
								$newImage->time=time();
								$newImage->added_by=$this->Auth->user('id');
								$newImage->tags=$this->request->data['image_tags'];
								$newImage->status='Active';
							}else{
								//insert into image library db with no details for single usage
								$newImage->image_title=$this->request->data['imagefileupload']['name'];
								$newImage->categories='Misc';
								$newImage->filename=$filename;
								$newImage->time=time();
								$newImage->added_by=$this->Auth->user('id');
								$newImage->status='Single Use';
							}
							
							if($imgLibraryTable->save($newImage)){
								//insert the metadata for libraryimageid
								$this->updateLineItemMeta($lineItemID,'libraryimageid',$newImage->id);
								
								$this->aspectratiofix($newImage->id,600);

							}
							
							
						}

						

						if($this->request->data['com-fabric']=='1'){

							$this->updateLineItemMeta($lineItemID,'com-fabric','1');

						}
						
						/*PPSA-33 start */
                        	
						if(empty($this->request->data['fabric-cost-per-yard-custom-value'])) {

							$this->updateLineItemMeta($lineItemID,'fabric-cost-per-yard-custom-value','');

						}else {
						  $this->updateLineItemMeta($lineItemID,'fabric-cost-per-yard-custom-value',number_format($this->request->data['fabric-cost-per-yard-custom-value'], 2, '.', ''));  
						}
						
                        /*PPSA-33 end */
                        
                        
                    }
			        /**PPSASSCRUM-29 start */	
					$orderDetails = $this->Orders->find('all',['conditions'=>['quote_id'=>$quoteID]])->first();

					if(isset($orderDetails) && !empty($orderDetails['id'])){

						$this->UpdatetoOrderLineItemTables($quoteID, $thisLineItem['id'],$orderDetails['id'],'orders');
						$this->UpdatetoOrderLineItemTables($quoteID, $thisLineItem['id'],$orderDetails['id'],'work_orders');
						//$this->savetoOrderLineItemTables($newItem->id, $quoteID,$orderDetails['id'], $table='orders' );
						//$this->savetoOrderLineItemTables($newItem->id, $quoteID,$orderDetails['id'], $table='work_orders' );	
					}
				  
					/**PPSASSCRUM-29 end */
			        //update modified time
			        $this->updatequotemodifiedtime($quoteID);
			        
			        //update tier adjustments
			        if($this->recalculatequoteadjustments($quoteID)){
			            
			            //get the new item data for PMI Adjusted for extended price
			            $freshItemData=$this->QuoteLineItems->get($lineItemID);
			            $freshItemData->extended_price = ($freshItemData->pmi_adjusted * floatval($this->request->data['qty']));
			            $this->QuoteLineItems->save($freshItemData);
			            
			            
			            
						$this->Flash->success('Successfully edited CC Catchall line item');
						
						$this->logActivity($_SERVER['REQUEST_URI'],'Edited CC Catchall line ID '.$lineItemID,$this->Auth->user('id'),json_encode($this->request));
					    
					    //redirect to quote or orderedit view
					    if($thisQuote['status'] == 'editorder'){
            			    return $this->redirect('/orders/editlines/'.$thisQuote['order_id']);
            			}else{
            				return $this->redirect('/quotes/add/'.$quoteID.'/');
            			}
					}
			        
			    break;
			    
			    case 'newcatchall-bedding':
			        
			        //make sure the image is an image
			        if(isset($this->request->data['imagefileupload']['tmp_name']) && $this->request->data['image_method'] == 'upload'){
                        $allowedTypes=array('.jpg','jpeg','.png','.gif');
    					if(!in_array(strtolower(substr($this->request->data['imagefileupload']['name'],-4)),$allowedTypes)){
    					    $this->autoRender=false;
    					    echo '<h3><span style="color:red;">ERROR:</span> The filetype <u>'.substr($this->request->data['imagefileupload']['name'],-4).'</u> is not allowed in the image field.</h3>
                            <p><button type="button" onclick="history.go(-1);">Go Back</button></p>';
    					    exit;
    					}
                    }
			        
			        //save all the field values
			        $thisItem=$this->QuoteLineItems->get($lineItemData['id']);
			        
			        $thisItem->title = $this->request->data['line_item_title'];
                    $thisItem->description = $this->request->data['description'];
                    $thisItem->best_price = $this->request->data['price'];
                    $thisItem->qty = $this->request->data['qty'];
                    $thisItem->room_number = $this->request->data['location'];
                    
                    
                    $thisItem->product_subclass=$this->request->data['product_subclass'];
			        
                    $thisSubClass=$this->ProductSubclasses->get($this->request->data['product_subclass'])->toArray();
                    $thisItem->enable_tally=$thisSubClass['tally'];
                    
                    
                    $thisItem->unit = $this->request->data['unit_type'];
                    

                    if($this->QuoteLineItems->save($thisItem)){
                        //save the meta data changes
                        $ignoreKeys=array('process','product_subclass','imagefileupload','description','line_item_title','customer_id','save_to_library','image_title','image_category','image_tags','fabric_name','fabric_color','libraryimageid');
                        
                        foreach($this->request->data as $postkey => $postdata){
                            if(!in_array($postkey,$ignoreKeys) && $postdata != ''){
                                
                                if($postkey=='fabric_type'){
                                    $postkey='fabrictype';
                                }
                                
                                $this->updateLineItemMeta($lineItemID,$postkey,$postdata);
                                
                            }
                        }
                        
                        
                        $newLineItemMeta=$this->QuoteLineItemMeta->newEntity();
                        $newLineItemMeta->quote_item_id = $newItem->id;
                        $newLineItemMeta->meta_key='lineitemtype';
                        $newLineItemMeta->meta_value='newcatchall';
                        $this->QuoteLineItemMeta->save($newLineItemMeta);
                        
                        
                        if($this->request->data['fabric_type']=="existing"){

							$this->updateLineItemMeta($lineItemID,'fabricid',$this->request->data['fabric_id_with_color']);
							
							$selectedFabric=$this->Fabrics->get($this->request->data['fabric_id_with_color'])->toArray();
							$this->updateLineItemMeta($lineItemID,'fabric_name',$selectedFabric['fabric_name']);
							
                            $this->updateLineItemMeta($lineItemID,'fabric_color',$selectedFabric['color']);
							

						}elseif($this->request->data['fabric_type']=="typein"){

                            $this->updateLineItemMeta($lineItemID,'fabric_name',$this->request->data['fabric_name']);
							
							$this->updateLineItemMeta($lineItemID,'fabric_color',$this->request->data['fabric_color']);

						}/*PPSA -36 start */elseif($this->request->data['fabric_type']=="none"){

                            $this->updateLineItemMeta($lineItemID,'fabricid','0');
        					$this->updateLineItemMeta($lineItemID,'fabric_name','');
        					$this->updateLineItemMeta($lineItemID,'fabric_color','');

						}
	/*PPSA -36 end */
						

						if($this->request->data['image_method'] == 'library'){
						    
						    $this->updateLineItemMeta($lineItemID,'libraryimageid',$this->request->data['libraryimageid']);

							
						}elseif($this->request->data['image_method'] == 'upload'){
							
							//handle the upload, and process whether to save this to library or just do a one-off insert
							$filename=$this->request->data['imagefileupload']['name'];
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
							
							
							move_uploaded_file($this->request->data['imagefileupload']['tmp_name'],$_SERVER['DOCUMENT_ROOT']."/webroot/img/library/".$filename);
							
							$imgLibraryTable=TableRegistry::get('LibraryImages');
							$newImage=$imgLibraryTable->newEntity();
							
							if($this->request->data['save_to_library'] == '1'){
								
								$selectedCat='';
								$allCats=$this->LibraryCategories->find('all')->toArray();
								foreach($allCats as $catEntry){
									if($catEntry['id'] == $this->request->data['image_category']){
										$selectedCat=$catEntry['category_title'];
									}
								}
								
								//insert into image library db with details for future usage
								$newImage->image_title=$this->request->data['image_title'];
								$newImage->categories=$selectedCat;
								$newImage->filename=$filename;
								$newImage->time=time();
								$newImage->added_by=$this->Auth->user('id');
								$newImage->tags=$this->request->data['image_tags'];
								$newImage->status='Active';
							}else{
								//insert into image library db with no details for single usage
								$newImage->image_title=$this->request->data['imagefileupload']['name'];
								$newImage->categories='Misc';
								$newImage->filename=$filename;
								$newImage->time=time();
								$newImage->added_by=$this->Auth->user('id');
								$newImage->status='Single Use';
							}
							
							if($imgLibraryTable->save($newImage)){
								//insert the metadata for libraryimageid
								$this->updateLineItemMeta($lineItemID,'libraryimageid',$newImage->id);
								
								$this->aspectratiofix($newImage->id,600);

							}
							
							
						}

						

						if($this->request->data['com-fabric']=='1'){

							$this->updateLineItemMeta($lineItemID,'com-fabric','1');

						}
						
                        
                        /*PPSA-33 start */
                        	
						if(empty($this->request->data['fabric-cost-per-yard-custom-value'])) {

							$this->updateLineItemMeta($lineItemID,'fabric-cost-per-yard-custom-value','');

						}else {
						  $this->updateLineItemMeta($lineItemID,'fabric-cost-per-yard-custom-value',number_format($this->request->data['fabric-cost-per-yard-custom-value'], 2, '.', ''));  
						}
						
                        /*PPSA-33 end */
                    }
			        /**PPSASSCRUM-29 start */	
					$orderDetails = $this->Orders->find('all',['conditions'=>['quote_id'=>$quoteID]])->first();

					if(isset($orderDetails) && !empty($orderDetails['id'])){

						$this->UpdatetoOrderLineItemTables($quoteID, $thisLineItem['id'],$orderDetails['id'],'orders');
						$this->UpdatetoOrderLineItemTables($quoteID, $thisLineItem['id'],$orderDetails['id'],'work_orders');
						//$this->savetoOrderLineItemTables($newItem->id, $quoteID,$orderDetails['id'], $table='orders' );
						//$this->savetoOrderLineItemTables($newItem->id, $quoteID,$orderDetails['id'], $table='work_orders' );	
					}
				  
					/**PPSASSCRUM-29 end */
			        //update modified time
			        $this->updatequotemodifiedtime($quoteID);
			        
			        //update tier adjustments
			        if($this->recalculatequoteadjustments($quoteID)){
			            
			            //get the new item data for PMI Adjusted for extended price
			            $freshItemData=$this->QuoteLineItems->get($lineItemID);
			            $freshItemData->extended_price = ($freshItemData->pmi_adjusted * floatval($this->request->data['qty']));
			            $this->QuoteLineItems->save($freshItemData);
			            
			            
			            
						$this->Flash->success('Successfully edited Bedding Catchall line item');
						
						$this->logActivity($_SERVER['REQUEST_URI'],'Edited Bedding Catchall line ID '.$lineItemID,$this->Auth->user('id'),json_encode($this->request));
					    
					    //redirect to quote or orderedit view
					    if($thisQuote['status'] == 'editorder'){
            			    return $this->redirect('/orders/editlines/'.$thisQuote['order_id']);
            			}else{
            				return $this->redirect('/quotes/add/'.$quoteID.'/');
            			}
					}
			        
			    break;
			}
			

		}else{
			
			$fabrics=$this->Fabrics->find('all',['conditions'=>['status'=>'Active']])->group('fabric_name')->toArray();
			$this->set('fabrics',$fabrics);
			
			if($lineItemMetas['fabrictype'] == 'existing'){
				$theFabric=$this->Fabrics->get($lineItemMetas['fabricid'])->toArray();
				$this->set('theFabric',$theFabric);
				
				$thisFabricColors=$this->Fabrics->find('all',['conditions' => ['fabric_name LIKE' => $theFabric['fabric_name']]])->toArray();
				$this->set('thisFabricColors',$thisFabricColors);
			}
			
			$vendorsList=array();
			$vendors=$this->Vendors->find('all')->toArray();
			foreach($vendors as $vendor){
				$vendorsList[$vendor['id']]=$vendor['vendor_name'];
			}
			
			$this->set('vendorsList',$vendorsList);
			
			$this->set('quoteID',$quoteID);
			
			if($thisLineItem['lineitemtype'] == 'newcatchall'){
			    $this->autoRender=false;
			    
			    $this->set('mode','edit');
			    
			    
			    $libraryCatsLookup=$this->LibraryCategories->find('all')->toArray();
        		$allCats=array();
        		foreach($libraryCatsLookup as $catEntry){
        			$allCats[$catEntry['id']]=$catEntry['category_title'];
        		}
        
        		$this->set('allLibraryCats',$allCats);
        
        		$libraryimages=$this->LibraryImages->find('all',['conditions'=>['status'=>'Active']])->toArray();
        
        		$this->set('libraryimages',$libraryimages);
			    
			    switch($thisLineItem['product_type']){
			        case 'newcatchall-misc':
			            $subclasses=$this->ProductSubclasses->find('all',['conditions' => ['class_id' => 7], 'order' => ['subclass_name' => 'asc']])->toArray();
                        $this->set('subclasses',$subclasses);
			        break;
			        case 'newcatchall-hardware':
			            $subclasses=$this->ProductSubclasses->find('all',['conditions' => ['class_id' => 1], 'order' => ['subclass_name' => 'asc']])->toArray();
                        $this->set('subclasses',$subclasses);
			        break;
			        //blinds dont need special vars
			        //shades dont need special vars
			        //shutters dont need special vars
			        //hwt misc doesnt need special vars
			        case 'newcatchall-swtmisc':
			            //load Linings
			            $linings=$this->Linings->find('all')->toArray();
					    $this->set('linings',$linings);
			        break;
			        case 'newcatchall-service':
			            $subclasses=$this->ProductSubclasses->find('all',['conditions' => ['class_id' => 6], 'order' => ['subclass_name' => 'asc']])->toArray();
                        $this->set('subclasses',$subclasses);
			        break;
			        case 'newcatchall-valance':
			            //load Linings
			            $linings=$this->Linings->find('all')->toArray();
					    $this->set('linings',$linings);
			        break;
			        case 'newcatchall-drapery':
			            //load Linings
			            $linings=$this->Linings->find('all')->toArray();
					    $this->set('linings',$linings);
			        break;
			        case 'newcatchall-cornice':
			            //load Linings
			            $linings=$this->Linings->find('all')->toArray();
					    $this->set('linings',$linings);
			        break;
			        case 'newcatchall-cubicle':
			            $subclasses=$this->ProductSubclasses->find('all',['conditions' => ['class_id' => 4], 'order' => ['subclass_name' => 'asc']])->toArray();
                        $this->set('subclasses',$subclasses);
			        break;
			        case 'newcatchall-bedding':
			            $subclasses=$this->ProductSubclasses->find('all',['conditions' => ['class_id' => 5], 'order' => ['subclass_name' => 'asc']])->toArray();
                        $this->set('subclasses',$subclasses);
			        break;
			    }
			    
			    
			    $this->render('/Quotes/'.$thisLineItem['product_type']);
			}

		}

	}

/**
 * 
 */
	public function woeditlineitem($lineItemID){
		$thisLineItem=$this->QuoteLineItems->get($lineItemID)->toArray();

		$this->set('thisLineItem',$thisLineItem);

		

		$lineItemMetas=array();

		$itemmetas=$this->QuoteLineItemMeta->find('all',['conditions'=>['quote_item_id'=>$lineItemID]])->toArray();

		foreach($itemmetas as $itemmeta){

			$lineItemMetas[$itemmeta['meta_key']]=$itemmeta['meta_value'];

		}

		$this->set('lineitemmeta',$lineItemMetas);

		

		$this->set('isEdit',true);

		$quoteID=$thisLineItem['quote_id'];

		$thisQuote=$this->Quotes->get($quoteID)->toArray();

		$this->set('quoteData',$thisQuote);

		

		//$thisCustomer=$this->Customers->get($thisQuote['customer_id'])->toArray();
		$thisCustomer=$this->Customers->find('all',['conditions'=>['id'=>$thisQuote['customer_id']]])->toArray();

		$this->set('customerData',$thisCustomer);

		
        $allSettings=$this->getsettingsarray();

		$this->set('settings',$allSettings);
		

		if($this->request->data){
			
			$lineItemData=$this->QuoteLineItems->get($lineItemID)->toArray();
			
			switch($lineItemData['product_type']){
			    case 'custom':
			        //save the changes to the catch-all item
        			$quoteItemsTable=TableRegistry::get('QuoteLineItems');
        			$thisLineItem=$quoteItemsTable->get($lineItemID);
        			$thisLineItem->title=$this->request->data['line_item_title'];
        			$thisLineItem->description = $this->request->data['description'];
        			$thisLineItem->qty = $this->request->data['qty'];
        			$thisLineItem->unit=$this->request->data['unit_type'];
        			$thisLineItem->best_price = floatval($this->request->data['price']);
        			$thisLineItem->room_number = $this->request->data['room_number'];
        			
        			
        			//12/7/2021  ticket 601958 remove override_price when using edit catchall form and changing the price
        			if($lineItemData['override_active'] == 1 && floatval($lineItemData['override_price']) != floatval($this->request->data['price'])){
        			    $thisLineItem->override_active=0;
        			    $thisLineItem->override_price = null;
        			    
        			    $lineItemData['override_price']=null;
        			    $lineItemData['override_active']=0;
        			}
        			
        			$tieradjusted=$this->tieradjustment($lineItemData,$thisCustomer,floatval($this->request->data['price']));
        			$installadjusted=$this->installadjustment($lineItemData,$thisCustomer,$tieradjusted);
        			$rebateadjusted=$this->rebateadjustment($lineItemData,$thisCustomer,$installadjusted);
        			$pmiadjusted=$this->pmiadjustment($lineItemData,$thisCustomer,$rebateadjusted);
        			
        			
        			$thisLineItem->tier_adjusted_price = $tieradjused;
        			$thisLineItem->install_adjusted_price = $installadjusted;
        			$thisLineItem->rebate_adjusted_price = $rebateadjusted;
        			$thisLineItem->pmi_adjusted = $pmiadjusted;
        			
        			
        			
        			$thisLineItem->extended_price = ($pmiadjusted*floatval($this->request->data['qty']));
        			
        			$quoteItemsTable->save($thisLineItem);
        			
        			$this->updateLineItemMeta($lineItemID,'catchallcategory',$this->request->data['category']);
        			$this->updateLineItemMeta($lineItemID,'qty',$this->request->data['qty']);
        			$this->updateLineItemMeta($lineItemID,'specs',$this->request->data['specs']);
        			$this->updateLineItemMeta($lineItemID,'fabrictype',$this->request->data['fabric_type']);
        			$this->updateLineItemMeta($lineItemID,'unit_type',$this->request->data['unit_type']);
        			$this->updateLineItemMeta($lineItemID,'width',$this->request->data['cut_width']);
        			$this->updateLineItemMeta($lineItemID,'fw',$this->request->data['finished_width']);
        			$this->updateLineItemMeta($lineItemID,'length',$this->request->data['finished_length']);
        			
        			$this->updateLineItemMeta($lineItemID,'yds-per-unit',$this->request->data['fabric_ydsper']);
        			$this->updateLineItemMeta($lineItemID,'total-yds',$this->request->data['fabric_total_yds']);
        			
        			//$this->updateLineItemMeta($lineItemID,'fabric-margin',$this->request->data['fabric_margin']);
        			//$this->updateLineItemMeta($lineItemID,'fabric-profit',$this->request->data['fabric_profit']);
        			
        			$this->updateLineItemMeta($lineItemID,'room_number',$this->request->data['room_number']);
        			$this->updateLineItemMeta($lineItemID,'price',$this->request->data['price']);
        			
        			$this->updateLineItemMeta($lineItemID,'vendors_id',$this->request->data['vendors_id']);
        			
        			$this->updateLineItemMeta($lineItemID,'com-fabric',$this->request->data['com-fabric']);
        			
        			//fabric metadata
        			switch($this->request->data['fabric_type']){
        				case "none":
        					$this->updateLineItemMeta($lineItemID,'fabricid','0');
        					$this->updateLineItemMeta($lineItemID,'fabric_name','');
        					$this->updateLineItemMeta($lineItemID,'fabric_color','');
        				break;
        				case "existing":
        					
        					$selectedFabric=$this->Fabrics->get($this->request->data['fabric_id_with_color'])->toArray();
        					
        					$this->updateLineItemMeta($lineItemID,'fabricid',$this->request->data['fabric_id_with_color']);
        					$this->updateLineItemMeta($lineItemID,'fabric_name',$selectedFabric['fabric_name']);
        					$this->updateLineItemMeta($lineItemID,'fabric_color',$selectedFabric['color']);
        					
        				break;
        				case "typein":
        					$this->updateLineItemMeta($lineItemID,'fabricid','0');
        					$this->updateLineItemMeta($lineItemID,'fabric_name',$this->request->data['fabric_name']);
        					$this->updateLineItemMeta($lineItemID,'fabric_color',$this->request->data['fabric_color']);
        				break;
        			}
        			

					/**PPSASSCRUM-29 start */	
					$orderDetails = $this->Orders->find('all',['conditions'=>['quote_id'=>$quoteID]])->first();

					if(isset($orderDetails) && !empty($orderDetails['id'])){

						$this->UpdatetoOrderLineItemTables($quoteID, $thisLineItem['id'],$orderDetails['id'],'orders');
						$this->UpdatetoOrderLineItemTables($quoteID, $thisLineItem['id'],$orderDetails['id'],'work_orders');
						//$this->savetoOrderLineItemTables($newItem->id, $quoteID,$orderDetails['id'], $table='orders' );
						//$this->savetoOrderLineItemTables($newItem->id, $quoteID,$orderDetails['id'], $table='work_orders' );	
					}
				  
					/**PPSASSCRUM-29 end */


        			$this->updatequotemodifiedtime($thisLineItem->quote_id);
        			$this->logActivity($_SERVER['REQUEST_URI'],'Saved changes to Catch-All line item '.$lineItemData['line_number'].' on Quote #'.$thisQuote['quote_number']);
        			
        			if($thisQuote['status'] == 'editorder'){
        			    return $this->redirect('/orders/editlines/'.$thisQuote['order_id']);
        			}else{
        				return $this->redirect('/quotes/add/'.$thisLineItem->quote_id.'/');
        			}
			    break;
			    
			    case 'newcatchall-misc':
			        
			        //make sure the image is an image
			        if(isset($this->request->data['imagefileupload']['tmp_name']) && $this->request->data['image_method'] == 'upload'){
                        $allowedTypes=array('.jpg','jpeg','.png','.gif');
    					if(!in_array(strtolower(substr($this->request->data['imagefileupload']['name'],-4)),$allowedTypes)){
    					    $this->autoRender=false;
    					    echo '<h3><span style="color:red;">ERROR:</span> The filetype <u>'.substr($this->request->data['imagefileupload']['name'],-4).'</u> is not allowed in the image field.</h3>
                            <p><button type="button" onclick="history.go(-1);">Go Back</button></p>';
    					    exit;
    					}
                    }
			        
			        //save all the field values
			        $thisItem=$this->QuoteLineItems->get($lineItemData['id']);
			        $thisItem->product_subclass=$this->request->data['product_subclass'];
			        $thisItem->title = $this->request->data['line_item_title'];
                    $thisItem->description = $this->request->data['description'];
                    $thisItem->best_price = $this->request->data['price'];
                    $thisItem->qty = $this->request->data['qty'];
                    $thisItem->room_number = $this->request->data['location'];
                    
                    $thisSubClass=$this->ProductSubclasses->get($this->request->data['product_subclass'])->toArray();
                    $thisItem->enable_tally=$thisSubClass['tally'];
                    
                    $thisItem->unit = $this->request->data['unit_type'];
                    

                    if($this->QuoteLineItems->save($thisItem)){
                        //save the meta data changes
                        $ignoreKeys=array('process','product_subclass','imagefileupload','description','line_item_title','customer_id','save_to_library','image_title','image_category','image_tags','fabric_name','fabric_color','libraryimageid');
                        
                        foreach($this->request->data as $postkey => $postdata){
                            if(!in_array($postkey,$ignoreKeys) && $postdata != ''){
                                
                                if($postkey=='fabric_type'){
                                    $postkey='fabrictype';
                                }
                                
                                $this->updateLineItemMeta($lineItemID,$postkey,$postdata);
                                
                            }
                        }
                        
                        
                        $newLineItemMeta=$this->QuoteLineItemMeta->newEntity();
                        $newLineItemMeta->quote_item_id = $newItem->id;
                        $newLineItemMeta->meta_key='lineitemtype';
                        $newLineItemMeta->meta_value='newcatchall';
                        $this->QuoteLineItemMeta->save($newLineItemMeta);
                        
                        
                        if($this->request->data['fabric_type']=="existing"){

							$this->updateLineItemMeta($lineItemID,'fabricid',$this->request->data['fabric_id_with_color']);
							
							$selectedFabric=$this->Fabrics->get($this->request->data['fabric_id_with_color'])->toArray();
							$this->updateLineItemMeta($lineItemID,'fabric_name',$selectedFabric['fabric_name']);
							
                            $this->updateLineItemMeta($lineItemID,'fabric_color',$selectedFabric['color']);
							

						}elseif($this->request->data['fabric_type']=="typein"){

                            $this->updateLineItemMeta($lineItemID,'fabric_name',$this->request->data['fabric_name']);
							
							$this->updateLineItemMeta($lineItemID,'fabric_color',$this->request->data['fabric_color']);

						}/*PPSA -36 start */elseif($this->request->data['fabric_type']=="none"){

                            $this->updateLineItemMeta($lineItemID,'fabricid','0');
        					$this->updateLineItemMeta($lineItemID,'fabric_name','');
        					$this->updateLineItemMeta($lineItemID,'fabric_color','');

						}
	/*PPSA -36 end */

						if($this->request->data['image_method'] == 'library'){
						    
						    $this->updateLineItemMeta($lineItemID,'libraryimageid',$this->request->data['libraryimageid']);

							
						}elseif($this->request->data['image_method'] == 'upload'){
							
							//handle the upload, and process whether to save this to library or just do a one-off insert
							$filename=$this->request->data['imagefileupload']['name'];
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
							
							
							move_uploaded_file($this->request->data['imagefileupload']['tmp_name'],$_SERVER['DOCUMENT_ROOT']."/webroot/img/library/".$filename);
							
							$imgLibraryTable=TableRegistry::get('LibraryImages');
							$newImage=$imgLibraryTable->newEntity();
							
							if($this->request->data['save_to_library'] == '1'){
								
								$selectedCat='';
								$allCats=$this->LibraryCategories->find('all')->toArray();
								foreach($allCats as $catEntry){
									if($catEntry['id'] == $this->request->data['image_category']){
										$selectedCat=$catEntry['category_title'];
									}
								}
								
								//insert into image library db with details for future usage
								$newImage->image_title=$this->request->data['image_title'];
								$newImage->categories=$selectedCat;
								$newImage->filename=$filename;
								$newImage->time=time();
								$newImage->added_by=$this->Auth->user('id');
								$newImage->tags=$this->request->data['image_tags'];
								$newImage->status='Active';
							}else{
								//insert into image library db with no details for single usage
								$newImage->image_title=$this->request->data['imagefileupload']['name'];
								$newImage->categories='Misc';
								$newImage->filename=$filename;
								$newImage->time=time();
								$newImage->added_by=$this->Auth->user('id');
								$newImage->status='Single Use';
							}
							
							if($imgLibraryTable->save($newImage)){
								//insert the metadata for libraryimageid
								$this->updateLineItemMeta($lineItemID,'libraryimageid',$newImage->id);
								
								$this->aspectratiofix($newImage->id,600);

							}
							
							
						}

						

						if($this->request->data['com-fabric']=='1'){

							$this->updateLineItemMeta($lineItemID,'com-fabric','1');

						}
						
                        /*PPSA-33 start */
                        	if(empty($this->request->data['fabric-cost-per-yard-custom-value'])) {

							$this->updateLineItemMeta($lineItemID,'fabric-cost-per-yard-custom-value','');

						}else {
						  $this->updateLineItemMeta($lineItemID,'fabric-cost-per-yard-custom-value',number_format($this->request->data['fabric-cost-per-yard-custom-value'], 2, '.', ''));  
						}
						
                        /*PPSA-33 end */
                        
                    }
					/**PPSASSCRUM-29 start */	
					$orderDetails = $this->Orders->find('all',['conditions'=>['quote_id'=>$quoteID]])->first();

					if(isset($orderDetails) && !empty($orderDetails['id'])){

						$this->UpdatetoOrderLineItemTables($quoteID, $thisLineItem['id'],$orderDetails['id'],'orders');
						$this->UpdatetoOrderLineItemTables($quoteID, $thisLineItem['id'],$orderDetails['id'],'work_orders');
						//$this->savetoOrderLineItemTables($newItem->id, $quoteID,$orderDetails['id'], $table='orders' );
						//$this->savetoOrderLineItemTables($newItem->id, $quoteID,$orderDetails['id'], $table='work_orders' );	
					}
				  
					/**PPSASSCRUM-29 end */
			        
			        //update modified time
			        $this->updatequotemodifiedtime($quoteID);
			        
			        //update tier adjustments
			        if($this->recalculatequoteadjustments($quoteID)){
			            
			            //get the new item data for PMI Adjusted for extended price
			            $freshItemData=$this->QuoteLineItems->get($lineItemID);
			            $freshItemData->extended_price = ($freshItemData->pmi_adjusted * floatval($this->request->data['qty']));
			            $this->QuoteLineItems->save($freshItemData);
			            
			            
			            
						$this->Flash->success('Successfully edited Miscellaneous Catchall line item');
						
						$this->logActivity($_SERVER['REQUEST_URI'],'Edited Miscellaneous Catchall line ID '.$lineItemID,$this->Auth->user('id'),json_encode($this->request));
					    
					    //redirect to quote or orderedit view
					    if($thisQuote['status'] == 'editorder'){
            			    return $this->redirect('/orders/editlines/'.$thisQuote['order_id']);
            			}else{
            				return $this->redirect('/quotes/add/'.$quoteID.'/');
            			}
					}
			        
			        
			    break;
			    
			    case 'newcatchall-hardware':
			        
			        //make sure the image is an image
			        if(isset($this->request->data['imagefileupload']['tmp_name']) && $this->request->data['image_method'] == 'upload'){
                        $allowedTypes=array('.jpg','jpeg','.png','.gif');
    					if(!in_array(strtolower(substr($this->request->data['imagefileupload']['name'],-4)),$allowedTypes)){
    					    $this->autoRender=false;
    					    echo '<h3><span style="color:red;">ERROR:</span> The filetype <u>'.substr($this->request->data['imagefileupload']['name'],-4).'</u> is not allowed in the image field.</h3>
                            <p><button type="button" onclick="history.go(-1);">Go Back</button></p>';
    					    exit;
    					}
                    }
			        
			        //save all the field values
			        $thisItem=$this->QuoteLineItems->get($lineItemData['id']);
			        $thisItem->product_subclass=$this->request->data['product_subclass'];
			        $thisItem->title = $this->request->data['line_item_title'];
                    $thisItem->description = $this->request->data['description'];
                    $thisItem->best_price = $this->request->data['price'];
                    $thisItem->qty = $this->request->data['qty'];
                    $thisItem->room_number = $this->request->data['location'];
                    
                    $thisSubClass=$this->ProductSubclasses->get($this->request->data['product_subclass'])->toArray();
                    $thisItem->enable_tally=$thisSubClass['tally'];
                    
                    $thisItem->unit = $this->request->data['unit_type'];
                    

                    if($this->QuoteLineItems->save($thisItem)){
                        //save the meta data changes
                        $ignoreKeys=array('process','product_subclass','imagefileupload','description','line_item_title','customer_id','save_to_library','image_title','image_category','image_tags','fabric_name','fabric_color','libraryimageid');
                        
                        foreach($this->request->data as $postkey => $postdata){
                            if(!in_array($postkey,$ignoreKeys) && $postdata != ''){
                                
                                if($postkey=='fabric_type'){
                                    $postkey='fabrictype';
                                }
                                
                                $this->updateLineItemMeta($lineItemID,$postkey,$postdata);
                                
                            }
                        }
                        
                        
                        $newLineItemMeta=$this->QuoteLineItemMeta->newEntity();
                        $newLineItemMeta->quote_item_id = $newItem->id;
                        $newLineItemMeta->meta_key='lineitemtype';
                        $newLineItemMeta->meta_value='newcatchall';
                        $this->QuoteLineItemMeta->save($newLineItemMeta);
                        
                        
                        if($this->request->data['fabric_type']=="existing"){

							$this->updateLineItemMeta($lineItemID,'fabricid',$this->request->data['fabric_id_with_color']);
							
							$selectedFabric=$this->Fabrics->get($this->request->data['fabric_id_with_color'])->toArray();
							$this->updateLineItemMeta($lineItemID,'fabric_name',$selectedFabric['fabric_name']);
							
                            $this->updateLineItemMeta($lineItemID,'fabric_color',$selectedFabric['color']);
							

						}elseif($this->request->data['fabric_type']=="typein"){

                            $this->updateLineItemMeta($lineItemID,'fabric_name',$this->request->data['fabric_name']);
							
							$this->updateLineItemMeta($lineItemID,'fabric_color',$this->request->data['fabric_color']);

						}/*PPSA -36 start */elseif($this->request->data['fabric_type']=="none"){

                            $this->updateLineItemMeta($lineItemID,'fabricid','0');
        					$this->updateLineItemMeta($lineItemID,'fabric_name','');
        					$this->updateLineItemMeta($lineItemID,'fabric_color','');

						}
	/*PPSA -36 end */

						if($this->request->data['image_method'] == 'library'){
						    
						    $this->updateLineItemMeta($lineItemID,'libraryimageid',$this->request->data['libraryimageid']);

							
						}elseif($this->request->data['image_method'] == 'upload'){
							
							//handle the upload, and process whether to save this to library or just do a one-off insert
							$filename=$this->request->data['imagefileupload']['name'];
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
							
							
							move_uploaded_file($this->request->data['imagefileupload']['tmp_name'],$_SERVER['DOCUMENT_ROOT']."/webroot/img/library/".$filename);
							
							$imgLibraryTable=TableRegistry::get('LibraryImages');
							$newImage=$imgLibraryTable->newEntity();
							
							if($this->request->data['save_to_library'] == '1'){
								
								$selectedCat='';
								$allCats=$this->LibraryCategories->find('all')->toArray();
								foreach($allCats as $catEntry){
									if($catEntry['id'] == $this->request->data['image_category']){
										$selectedCat=$catEntry['category_title'];
									}
								}
								
								//insert into image library db with details for future usage
								$newImage->image_title=$this->request->data['image_title'];
								$newImage->categories=$selectedCat;
								$newImage->filename=$filename;
								$newImage->time=time();
								$newImage->added_by=$this->Auth->user('id');
								$newImage->tags=$this->request->data['image_tags'];
								$newImage->status='Active';
							}else{
								//insert into image library db with no details for single usage
								$newImage->image_title=$this->request->data['imagefileupload']['name'];
								$newImage->categories='Misc';
								$newImage->filename=$filename;
								$newImage->time=time();
								$newImage->added_by=$this->Auth->user('id');
								$newImage->status='Single Use';
							}
							
							if($imgLibraryTable->save($newImage)){
								//insert the metadata for libraryimageid
								$this->updateLineItemMeta($lineItemID,'libraryimageid',$newImage->id);
								
								$this->aspectratiofix($newImage->id,600);

							}
							
							
						}

						

						if($this->request->data['com-fabric']=='1'){

							$this->updateLineItemMeta($lineItemID,'com-fabric','1');

						}
						
							/*PPSA-33 start */
						if(empty($this->request->data['fabric-cost-per-yard-custom-value'])) {

							$this->updateLineItemMeta($lineItemID,'fabric-cost-per-yard-custom-value','');

						}else {
						  $this->updateLineItemMeta($lineItemID,"fabric-cost-per-yard-custom-value",number_format($this->request->data['fabric-cost-per-yard-custom-value'], 2, '.', ''));  
						}
						
                        /*PPSA-33 end */
                        
                        
                    }
					/**PPSASSCRUM-29 start */	
					$orderDetails = $this->Orders->find('all',['conditions'=>['quote_id'=>$quoteID]])->first();

					if(isset($orderDetails) && !empty($orderDetails['id'])){

						$this->UpdatetoOrderLineItemTables($quoteID, $thisLineItem['id'],$orderDetails['id'],'orders');
						$this->UpdatetoOrderLineItemTables($quoteID, $thisLineItem['id'],$orderDetails['id'],'work_orders');
						//$this->savetoOrderLineItemTables($newItem->id, $quoteID,$orderDetails['id'], $table='orders' );
						//$this->savetoOrderLineItemTables($newItem->id, $quoteID,$orderDetails['id'], $table='work_orders' );	
					}
				  
					/**PPSASSCRUM-29 end */
			        
			        //update modified time
			        $this->updatequotemodifiedtime($quoteID);
			        
			        //update tier adjustments
			        if($this->recalculatequoteadjustments($quoteID)){
			            
			            //get the new item data for PMI Adjusted for extended price
			            $freshItemData=$this->QuoteLineItems->get($lineItemID);
			            $freshItemData->extended_price = ($freshItemData->pmi_adjusted * floatval($this->request->data['qty']));
			            $this->QuoteLineItems->save($freshItemData);
			            
			            
			            
						$this->Flash->success('Successfully edited Hardware Catchall line item');
						
						$this->logActivity($_SERVER['REQUEST_URI'],'Edited Hardware Catchall line ID '.$lineItemID,$this->Auth->user('id'),json_encode($this->request));
					    
					    //redirect to quote or orderedit view
					    if($thisQuote['status'] == 'editorder'){
            			    return $this->redirect('/orders/editlines/'.$thisQuote['order_id']);
            			}else{
            				return $this->redirect('/quotes/add/'.$quoteID.'/');
            			}
					}
			        
			    break;
			    
			    case 'newcatchall-blinds':
			        
			        //make sure the image is an image
			        if(isset($this->request->data['imagefileupload']['tmp_name']) && $this->request->data['image_method'] == 'upload'){
                        $allowedTypes=array('.jpg','jpeg','.png','.gif');
    					if(!in_array(strtolower(substr($this->request->data['imagefileupload']['name'],-4)),$allowedTypes)){
    					    $this->autoRender=false;
    					    echo '<h3><span style="color:red;">ERROR:</span> The filetype <u>'.substr($this->request->data['imagefileupload']['name'],-4).'</u> is not allowed in the image field.</h3>
                            <p><button type="button" onclick="history.go(-1);">Go Back</button></p>';
    					    exit;
    					}
                    }
			        
			        //save all the field values
			        $thisItem=$this->QuoteLineItems->get($lineItemData['id']);
			        
			        $thisItem->title = $this->request->data['line_item_title'];
                    $thisItem->description = $this->request->data['description'];
                    $thisItem->best_price = $this->request->data['price'];
                    $thisItem->qty = $this->request->data['qty'];
                    $thisItem->room_number = $this->request->data['location'];
                    
                    
                    $thisItem->unit = $this->request->data['unit_type'];
                    

                    if($this->QuoteLineItems->save($thisItem)){
                        //save the meta data changes
                        $ignoreKeys=array('process','product_subclass','imagefileupload','description','line_item_title','customer_id','save_to_library','image_title','image_category','image_tags','fabric_name','fabric_color','libraryimageid');
                        
                        foreach($this->request->data as $postkey => $postdata){
                            if(!in_array($postkey,$ignoreKeys) && $postdata != ''){
                                
                                if($postkey=='fabric_type'){
                                    $postkey='fabrictype';
                                }
                                
                                $this->updateLineItemMeta($lineItemID,$postkey,$postdata);
                                
                            }
                        }
                        
                        
                        $newLineItemMeta=$this->QuoteLineItemMeta->newEntity();
                        $newLineItemMeta->quote_item_id = $newItem->id;
                        $newLineItemMeta->meta_key='lineitemtype';
                        $newLineItemMeta->meta_value='newcatchall';
                        $this->QuoteLineItemMeta->save($newLineItemMeta);
                        
                        /*
                        if($this->request->data['fabric_type']=="existing"){

							$this->updateLineItemMeta($lineItemID,'fabricid',$this->request->data['fabric_id_with_color']);
							
							$selectedFabric=$this->Fabrics->get($this->request->data['fabric_id_with_color'])->toArray();
							$this->updateLineItemMeta($lineItemID,'fabric_name',$selectedFabric['fabric_name']);
							
                            $this->updateLineItemMeta($lineItemID,'fabric_color',$selectedFabric['color']);
							

						}elseif($this->request->data['fabric_type']=="typein"){

                            $this->updateLineItemMeta($lineItemID,'fabric_name',$this->request->data['fabric_name']);
							
							$this->updateLineItemMeta($lineItemID,'fabric_color',$this->request->data['fabric_color']);

						}
						*/

						if($this->request->data['image_method'] == 'library'){
						    
						    $this->updateLineItemMeta($lineItemID,'libraryimageid',$this->request->data['libraryimageid']);

							
						}elseif($this->request->data['image_method'] == 'upload'){
							
							//handle the upload, and process whether to save this to library or just do a one-off insert
							$filename=$this->request->data['imagefileupload']['name'];
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
							
							
							move_uploaded_file($this->request->data['imagefileupload']['tmp_name'],$_SERVER['DOCUMENT_ROOT']."/webroot/img/library/".$filename);
							
							$imgLibraryTable=TableRegistry::get('LibraryImages');
							$newImage=$imgLibraryTable->newEntity();
							
							if($this->request->data['save_to_library'] == '1'){
								
								$selectedCat='';
								$allCats=$this->LibraryCategories->find('all')->toArray();
								foreach($allCats as $catEntry){
									if($catEntry['id'] == $this->request->data['image_category']){
										$selectedCat=$catEntry['category_title'];
									}
								}
								
								//insert into image library db with details for future usage
								$newImage->image_title=$this->request->data['image_title'];
								$newImage->categories=$selectedCat;
								$newImage->filename=$filename;
								$newImage->time=time();
								$newImage->added_by=$this->Auth->user('id');
								$newImage->tags=$this->request->data['image_tags'];
								$newImage->status='Active';
							}else{
								//insert into image library db with no details for single usage
								$newImage->image_title=$this->request->data['imagefileupload']['name'];
								$newImage->categories='Misc';
								$newImage->filename=$filename;
								$newImage->time=time();
								$newImage->added_by=$this->Auth->user('id');
								$newImage->status='Single Use';
							}
							
							if($imgLibraryTable->save($newImage)){
								//insert the metadata for libraryimageid
								$this->updateLineItemMeta($lineItemID,'libraryimageid',$newImage->id);
								
								$this->aspectratiofix($newImage->id,600);

							}
							
							
						}

						

						if($this->request->data['com-fabric']=='1'){

							$this->updateLineItemMeta($lineItemID,'com-fabric','1');

						}
						
                        
                        	/*PPSA-33 start */
						if(empty($this->request->data['fabric-cost-per-yard-custom-value'])) {

							$this->updateLineItemMeta($lineItemID,'fabric-cost-per-yard-custom-value','');

						}else {
						  $this->updateLineItemMeta($lineItemID,'fabric-cost-per-yard-custom-value',number_format($this->request->data['fabric-cost-per-yard-custom-value'], 2, '.', ''));  
						}
						
                        /*PPSA-33 end */
                        
                    }
			        /**PPSASSCRUM-29 start */	
					$orderDetails = $this->Orders->find('all',['conditions'=>['quote_id'=>$quoteID]])->first();

					if(isset($orderDetails) && !empty($orderDetails['id'])){

						$this->UpdatetoOrderLineItemTables($quoteID, $thisLineItem['id'],$orderDetails['id'],'orders');
						$this->UpdatetoOrderLineItemTables($quoteID, $thisLineItem['id'],$orderDetails['id'],'work_orders');
						//$this->savetoOrderLineItemTables($newItem->id, $quoteID,$orderDetails['id'], $table='orders' );
						//$this->savetoOrderLineItemTables($newItem->id, $quoteID,$orderDetails['id'], $table='work_orders' );	
					}
				  
					/**PPSASSCRUM-29 end */
			        //update modified time
			        $this->updatequotemodifiedtime($quoteID);
			        
			        //update tier adjustments
			        if($this->recalculatequoteadjustments($quoteID)){
			            
			            //get the new item data for PMI Adjusted for extended price
			            $freshItemData=$this->QuoteLineItems->get($lineItemID);
			            $freshItemData->extended_price = ($freshItemData->pmi_adjusted * floatval($this->request->data['qty']));
			            $this->QuoteLineItems->save($freshItemData);
			            
			            
			            
						$this->Flash->success('Successfully edited Blinds Catchall line item');
						
						$this->logActivity($_SERVER['REQUEST_URI'],'Edited Blinds Catchall line ID '.$lineItemID,$this->Auth->user('id'),json_encode($this->request));
					    
					    //redirect to quote or orderedit view
					    if($thisQuote['status'] == 'editorder'){
            			    return $this->redirect('/orders/editlines/'.$thisQuote['order_id']);
            			}else{
            				return $this->redirect('/quotes/add/'.$quoteID.'/');
            			}
					}
					
			    break;
			    
			    case 'newcatchall-shades':
			        //make sure the image is an image
			        if(isset($this->request->data['imagefileupload']['tmp_name']) && $this->request->data['image_method'] == 'upload'){
                        $allowedTypes=array('.jpg','jpeg','.png','.gif');
    					if(!in_array(strtolower(substr($this->request->data['imagefileupload']['name'],-4)),$allowedTypes)){
    					    $this->autoRender=false;
    					    echo '<h3><span style="color:red;">ERROR:</span> The filetype <u>'.substr($this->request->data['imagefileupload']['name'],-4).'</u> is not allowed in the image field.</h3>
                            <p><button type="button" onclick="history.go(-1);">Go Back</button></p>';
    					    exit;
    					}
                    }
			        
			        //save all the field values
			        $thisItem=$this->QuoteLineItems->get($lineItemData['id']);
			        
			        $thisItem->title = $this->request->data['line_item_title'];
                    $thisItem->description = $this->request->data['description'];
                    $thisItem->best_price = $this->request->data['price'];
                    $thisItem->qty = $this->request->data['qty'];
                    $thisItem->room_number = $this->request->data['location'];
                    
                    
                    
                    $thisItem->unit = $this->request->data['unit_type'];
                    

                    if($this->QuoteLineItems->save($thisItem)){
                        //save the meta data changes
                        $ignoreKeys=array('process','product_subclass','imagefileupload','description','line_item_title','customer_id','save_to_library','image_title','image_category','image_tags','fabric_name','fabric_color','libraryimageid');
                        
                        foreach($this->request->data as $postkey => $postdata){
                            if(!in_array($postkey,$ignoreKeys) && $postdata != ''){
                                
                                if($postkey=='fabric_type'){
                                    $postkey='fabrictype';
                                }
                                
                                $this->updateLineItemMeta($lineItemID,$postkey,$postdata);
                                
                            }
                        }
                        
                        
                        $newLineItemMeta=$this->QuoteLineItemMeta->newEntity();
                        $newLineItemMeta->quote_item_id = $newItem->id;
                        $newLineItemMeta->meta_key='lineitemtype';
                        $newLineItemMeta->meta_value='newcatchall';
                        $this->QuoteLineItemMeta->save($newLineItemMeta);
                        
                        /*
                        if($this->request->data['fabric_type']=="existing"){

							$this->updateLineItemMeta($lineItemID,'fabricid',$this->request->data['fabric_id_with_color']);
							
							$selectedFabric=$this->Fabrics->get($this->request->data['fabric_id_with_color'])->toArray();
							$this->updateLineItemMeta($lineItemID,'fabric_name',$selectedFabric['fabric_name']);
							
                            $this->updateLineItemMeta($lineItemID,'fabric_color',$selectedFabric['color']);
							

						}elseif($this->request->data['fabric_type']=="typein"){

                            $this->updateLineItemMeta($lineItemID,'fabric_name',$this->request->data['fabric_name']);
							
							$this->updateLineItemMeta($lineItemID,'fabric_color',$this->request->data['fabric_color']);

						}
						*/

						if($this->request->data['image_method'] == 'library'){
						    
						    $this->updateLineItemMeta($lineItemID,'libraryimageid',$this->request->data['libraryimageid']);

							
						}elseif($this->request->data['image_method'] == 'upload'){
							
							//handle the upload, and process whether to save this to library or just do a one-off insert
							$filename=$this->request->data['imagefileupload']['name'];
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
							
							
							move_uploaded_file($this->request->data['imagefileupload']['tmp_name'],$_SERVER['DOCUMENT_ROOT']."/webroot/img/library/".$filename);
							
							$imgLibraryTable=TableRegistry::get('LibraryImages');
							$newImage=$imgLibraryTable->newEntity();
							
							if($this->request->data['save_to_library'] == '1'){
								
								$selectedCat='';
								$allCats=$this->LibraryCategories->find('all')->toArray();
								foreach($allCats as $catEntry){
									if($catEntry['id'] == $this->request->data['image_category']){
										$selectedCat=$catEntry['category_title'];
									}
								}
								
								//insert into image library db with details for future usage
								$newImage->image_title=$this->request->data['image_title'];
								$newImage->categories=$selectedCat;
								$newImage->filename=$filename;
								$newImage->time=time();
								$newImage->added_by=$this->Auth->user('id');
								$newImage->tags=$this->request->data['image_tags'];
								$newImage->status='Active';
							}else{
								//insert into image library db with no details for single usage
								$newImage->image_title=$this->request->data['imagefileupload']['name'];
								$newImage->categories='Misc';
								$newImage->filename=$filename;
								$newImage->time=time();
								$newImage->added_by=$this->Auth->user('id');
								$newImage->status='Single Use';
							}
							
							if($imgLibraryTable->save($newImage)){
								//insert the metadata for libraryimageid
								$this->updateLineItemMeta($lineItemID,'libraryimageid',$newImage->id);
								
								$this->aspectratiofix($newImage->id,600);

							}
							
							
						}

						

						if($this->request->data['com-fabric']=='1'){

							$this->updateLineItemMeta($lineItemID,'com-fabric','1');

						}
						
                        
                        
                    }
			        /**PPSASSCRUM-29 start */	
					$orderDetails = $this->Orders->find('all',['conditions'=>['quote_id'=>$quoteID]])->first();

					if(isset($orderDetails) && !empty($orderDetails['id'])){

						$this->UpdatetoOrderLineItemTables($quoteID, $thisLineItem['id'],$orderDetails['id'],'orders');
						$this->UpdatetoOrderLineItemTables($quoteID, $thisLineItem['id'],$orderDetails['id'],'work_orders');
						//$this->savetoOrderLineItemTables($newItem->id, $quoteID,$orderDetails['id'], $table='orders' );
						//$this->savetoOrderLineItemTables($newItem->id, $quoteID,$orderDetails['id'], $table='work_orders' );	
					}
				  
					/**PPSASSCRUM-29 end */
			        //update modified time
			        $this->updatequotemodifiedtime($quoteID);
			        
			        //update tier adjustments
			        if($this->recalculatequoteadjustments($quoteID)){
			            
			            //get the new item data for PMI Adjusted for extended price
			            $freshItemData=$this->QuoteLineItems->get($lineItemID);
			            $freshItemData->extended_price = ($freshItemData->pmi_adjusted * floatval($this->request->data['qty']));
			            $this->QuoteLineItems->save($freshItemData);
			            
			            
			            
						$this->Flash->success('Successfully edited Shades Catchall line item');
						
						$this->logActivity($_SERVER['REQUEST_URI'],'Edited Shades Catchall line ID '.$lineItemID,$this->Auth->user('id'),json_encode($this->request));
					    
					    //redirect to quote or orderedit view
					    if($thisQuote['status'] == 'editorder'){
            			    return $this->redirect('/orders/editlines/'.$thisQuote['order_id']);
            			}else{
            				return $this->redirect('/quotes/add/'.$quoteID.'/');
            			}
					}
			    break;
			    
			    case 'newcatchall-shutters':
			        //make sure the image is an image
			        if(isset($this->request->data['imagefileupload']['tmp_name']) && $this->request->data['image_method'] == 'upload'){
                        $allowedTypes=array('.jpg','jpeg','.png','.gif');
    					if(!in_array(strtolower(substr($this->request->data['imagefileupload']['name'],-4)),$allowedTypes)){
    					    $this->autoRender=false;
    					    echo '<h3><span style="color:red;">ERROR:</span> The filetype <u>'.substr($this->request->data['imagefileupload']['name'],-4).'</u> is not allowed in the image field.</h3>
                            <p><button type="button" onclick="history.go(-1);">Go Back</button></p>';
    					    exit;
    					}
                    }
			        
			        //save all the field values
			        $thisItem=$this->QuoteLineItems->get($lineItemData['id']);
			        
			        $thisItem->title = $this->request->data['line_item_title'];
                    $thisItem->description = $this->request->data['description'];
                    $thisItem->best_price = $this->request->data['price'];
                    $thisItem->qty = $this->request->data['qty'];
                    $thisItem->room_number = $this->request->data['location'];
                    
                    
                    $thisItem->unit = $this->request->data['unit_type'];
                    

                    if($this->QuoteLineItems->save($thisItem)){
                        //save the meta data changes
                        $ignoreKeys=array('process','product_subclass','imagefileupload','description','line_item_title','customer_id','save_to_library','image_title','image_category','image_tags','fabric_name','fabric_color','libraryimageid');
                        
                        foreach($this->request->data as $postkey => $postdata){
                            if(!in_array($postkey,$ignoreKeys) && $postdata != ''){
                                
                                if($postkey=='fabric_type'){
                                    $postkey='fabrictype';
                                }
                                
                                $this->updateLineItemMeta($lineItemID,$postkey,$postdata);
                                
                            }
                        }
                        
                        
                        $newLineItemMeta=$this->QuoteLineItemMeta->newEntity();
                        $newLineItemMeta->quote_item_id = $newItem->id;
                        $newLineItemMeta->meta_key='lineitemtype';
                        $newLineItemMeta->meta_value='newcatchall';
                        $this->QuoteLineItemMeta->save($newLineItemMeta);
                        
                        /*
                        if($this->request->data['fabric_type']=="existing"){

							$this->updateLineItemMeta($lineItemID,'fabricid',$this->request->data['fabric_id_with_color']);
							
							$selectedFabric=$this->Fabrics->get($this->request->data['fabric_id_with_color'])->toArray();
							$this->updateLineItemMeta($lineItemID,'fabric_name',$selectedFabric['fabric_name']);
							
                            $this->updateLineItemMeta($lineItemID,'fabric_color',$selectedFabric['color']);
							

						}elseif($this->request->data['fabric_type']=="typein"){

                            $this->updateLineItemMeta($lineItemID,'fabric_name',$this->request->data['fabric_name']);
							
							$this->updateLineItemMeta($lineItemID,'fabric_color',$this->request->data['fabric_color']);

						}
						*/

						if($this->request->data['image_method'] == 'library'){
						    
						    $this->updateLineItemMeta($lineItemID,'libraryimageid',$this->request->data['libraryimageid']);

							
						}elseif($this->request->data['image_method'] == 'upload'){
							
							//handle the upload, and process whether to save this to library or just do a one-off insert
							$filename=$this->request->data['imagefileupload']['name'];
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
							
							
							move_uploaded_file($this->request->data['imagefileupload']['tmp_name'],$_SERVER['DOCUMENT_ROOT']."/webroot/img/library/".$filename);
							
							$imgLibraryTable=TableRegistry::get('LibraryImages');
							$newImage=$imgLibraryTable->newEntity();
							
							if($this->request->data['save_to_library'] == '1'){
								
								$selectedCat='';
								$allCats=$this->LibraryCategories->find('all')->toArray();
								foreach($allCats as $catEntry){
									if($catEntry['id'] == $this->request->data['image_category']){
										$selectedCat=$catEntry['category_title'];
									}
								}
								
								//insert into image library db with details for future usage
								$newImage->image_title=$this->request->data['image_title'];
								$newImage->categories=$selectedCat;
								$newImage->filename=$filename;
								$newImage->time=time();
								$newImage->added_by=$this->Auth->user('id');
								$newImage->tags=$this->request->data['image_tags'];
								$newImage->status='Active';
							}else{
								//insert into image library db with no details for single usage
								$newImage->image_title=$this->request->data['imagefileupload']['name'];
								$newImage->categories='Misc';
								$newImage->filename=$filename;
								$newImage->time=time();
								$newImage->added_by=$this->Auth->user('id');
								$newImage->status='Single Use';
							}
							
							if($imgLibraryTable->save($newImage)){
								//insert the metadata for libraryimageid
								$this->updateLineItemMeta($lineItemID,'libraryimageid',$newImage->id);
								
								$this->aspectratiofix($newImage->id,600);

							}
							
							
						}

						

						if($this->request->data['com-fabric']=='1'){

							$this->updateLineItemMeta($lineItemID,'com-fabric','1');

						}
						
                        
                        
                    }
			        /**PPSASSCRUM-29 start */	
					$orderDetails = $this->Orders->find('all',['conditions'=>['quote_id'=>$quoteID]])->first();

					if(isset($orderDetails) && !empty($orderDetails['id'])){

						$this->UpdatetoOrderLineItemTables($quoteID, $thisLineItem['id'],$orderDetails['id'],'orders');
						$this->UpdatetoOrderLineItemTables($quoteID, $thisLineItem['id'],$orderDetails['id'],'work_orders');
						//$this->savetoOrderLineItemTables($newItem->id, $quoteID,$orderDetails['id'], $table='orders' );
						//$this->savetoOrderLineItemTables($newItem->id, $quoteID,$orderDetails['id'], $table='work_orders' );	
					}
				  
					/**PPSASSCRUM-29 end */
			        //update modified time
			        $this->updatequotemodifiedtime($quoteID);
			        
			        //update tier adjustments
			        if($this->recalculatequoteadjustments($quoteID)){
			            
			            //get the new item data for PMI Adjusted for extended price
			            $freshItemData=$this->QuoteLineItems->get($lineItemID);
			            $freshItemData->extended_price = ($freshItemData->pmi_adjusted * floatval($this->request->data['qty']));
			            $this->QuoteLineItems->save($freshItemData);
			            
			            
			            
						$this->Flash->success('Successfully edited Shutters Catchall line item');
						
						$this->logActivity($_SERVER['REQUEST_URI'],'Edited Shutters Catchall line ID '.$lineItemID,$this->Auth->user('id'),json_encode($this->request));
					    
					    //redirect to quote or orderedit view
					    if($thisQuote['status'] == 'editorder'){
            			    return $this->redirect('/orders/editlines/'.$thisQuote['order_id']);
            			}else{
            				return $this->redirect('/quotes/add/'.$quoteID.'/');
            			}
					}
			    break;
			    
			    case 'newcatchall-hwtmisc':
			        
			        //make sure the image is an image
			        if(isset($this->request->data['imagefileupload']['tmp_name']) && $this->request->data['image_method'] == 'upload'){
                        $allowedTypes=array('.jpg','jpeg','.png','.gif');
    					if(!in_array(strtolower(substr($this->request->data['imagefileupload']['name'],-4)),$allowedTypes)){
    					    $this->autoRender=false;
    					    echo '<h3><span style="color:red;">ERROR:</span> The filetype <u>'.substr($this->request->data['imagefileupload']['name'],-4).'</u> is not allowed in the image field.</h3>
                            <p><button type="button" onclick="history.go(-1);">Go Back</button></p>';
    					    exit;
    					}
                    }
			        
			        //save all the field values
			        $thisItem=$this->QuoteLineItems->get($lineItemData['id']);
			        
			        $thisItem->title = $this->request->data['line_item_title'];
                    $thisItem->description = $this->request->data['description'];
                    $thisItem->best_price = $this->request->data['price'];
                    $thisItem->qty = $this->request->data['qty'];
                    $thisItem->room_number = $this->request->data['location'];
                    
                    
                    $thisItem->unit = $this->request->data['unit_type'];
                    

                    if($this->QuoteLineItems->save($thisItem)){
                        //save the meta data changes
                        $ignoreKeys=array('process','product_subclass','imagefileupload','description','line_item_title','customer_id','save_to_library','image_title','image_category','image_tags','fabric_name','fabric_color','libraryimageid');
                        
                        foreach($this->request->data as $postkey => $postdata){
                            if(!in_array($postkey,$ignoreKeys) && $postdata != ''){
                                
                                if($postkey=='fabric_type'){
                                    $postkey='fabrictype';
                                }
                                
                                $this->updateLineItemMeta($lineItemID,$postkey,$postdata);
                                
                            }
                        }
                        
                        
                        $newLineItemMeta=$this->QuoteLineItemMeta->newEntity();
                        $newLineItemMeta->quote_item_id = $newItem->id;
                        $newLineItemMeta->meta_key='lineitemtype';
                        $newLineItemMeta->meta_value='newcatchall';
                        $this->QuoteLineItemMeta->save($newLineItemMeta);
                        
                        /*
                        if($this->request->data['fabric_type']=="existing"){

							$this->updateLineItemMeta($lineItemID,'fabricid',$this->request->data['fabric_id_with_color']);
							
							$selectedFabric=$this->Fabrics->get($this->request->data['fabric_id_with_color'])->toArray();
							$this->updateLineItemMeta($lineItemID,'fabric_name',$selectedFabric['fabric_name']);
							
                            $this->updateLineItemMeta($lineItemID,'fabric_color',$selectedFabric['color']);
							

						}elseif($this->request->data['fabric_type']=="typein"){

                            $this->updateLineItemMeta($lineItemID,'fabric_name',$this->request->data['fabric_name']);
							
							$this->updateLineItemMeta($lineItemID,'fabric_color',$this->request->data['fabric_color']);

						}
						*/

						if($this->request->data['image_method'] == 'library'){
						    
						    $this->updateLineItemMeta($lineItemID,'libraryimageid',$this->request->data['libraryimageid']);

							
						}elseif($this->request->data['image_method'] == 'upload'){
							
							//handle the upload, and process whether to save this to library or just do a one-off insert
							$filename=$this->request->data['imagefileupload']['name'];
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
							
							
							move_uploaded_file($this->request->data['imagefileupload']['tmp_name'],$_SERVER['DOCUMENT_ROOT']."/webroot/img/library/".$filename);
							
							$imgLibraryTable=TableRegistry::get('LibraryImages');
							$newImage=$imgLibraryTable->newEntity();
							
							if($this->request->data['save_to_library'] == '1'){
								
								$selectedCat='';
								$allCats=$this->LibraryCategories->find('all')->toArray();
								foreach($allCats as $catEntry){
									if($catEntry['id'] == $this->request->data['image_category']){
										$selectedCat=$catEntry['category_title'];
									}
								}
								
								//insert into image library db with details for future usage
								$newImage->image_title=$this->request->data['image_title'];
								$newImage->categories=$selectedCat;
								$newImage->filename=$filename;
								$newImage->time=time();
								$newImage->added_by=$this->Auth->user('id');
								$newImage->tags=$this->request->data['image_tags'];
								$newImage->status='Active';
							}else{
								//insert into image library db with no details for single usage
								$newImage->image_title=$this->request->data['imagefileupload']['name'];
								$newImage->categories='Misc';
								$newImage->filename=$filename;
								$newImage->time=time();
								$newImage->added_by=$this->Auth->user('id');
								$newImage->status='Single Use';
							}
							
							if($imgLibraryTable->save($newImage)){
								//insert the metadata for libraryimageid
								$this->updateLineItemMeta($lineItemID,'libraryimageid',$newImage->id);
								
								$this->aspectratiofix($newImage->id,600);

							}
							
							
						}

						

						if($this->request->data['com-fabric']=='1'){

							$this->updateLineItemMeta($lineItemID,'com-fabric','1');

						}
						
                        /*PPSA-33 start */
						if(empty($this->request->data['fabric-cost-per-yard-custom-value'])) {

						$this->updateLineItemMeta($lineItemID,'fabric-cost-per-yard-custom-value','');

						}else {
						  $this->updateLineItemMeta($lineItemID,"fabric-cost-per-yard-custom-value",number_format($this->request->data['fabric-cost-per-yard-custom-value'], 2, '.', ''));  
						}
						
                        /*PPSA-33 end */
                        
                    }
			        /**PPSASSCRUM-29 start */	
					$orderDetails = $this->Orders->find('all',['conditions'=>['quote_id'=>$quoteID]])->first();

					if(isset($orderDetails) && !empty($orderDetails['id'])){

						$this->UpdatetoOrderLineItemTables($quoteID, $thisLineItem['id'],$orderDetails['id'],'orders');
						$this->UpdatetoOrderLineItemTables($quoteID, $thisLineItem['id'],$orderDetails['id'],'work_orders');
						//$this->savetoOrderLineItemTables($newItem->id, $quoteID,$orderDetails['id'], $table='orders' );
						//$this->savetoOrderLineItemTables($newItem->id, $quoteID,$orderDetails['id'], $table='work_orders' );	
					}
				  
					/**PPSASSCRUM-29 end */
			        //update modified time
			        $this->updatequotemodifiedtime($quoteID);
			        
			        //update tier adjustments
			        if($this->recalculatequoteadjustments($quoteID)){
			            
			            //get the new item data for PMI Adjusted for extended price
			            $freshItemData=$this->QuoteLineItems->get($lineItemID);
			            $freshItemData->extended_price = ($freshItemData->pmi_adjusted * floatval($this->request->data['qty']));
			            $this->QuoteLineItems->save($freshItemData);
			            
			            
			            
						$this->Flash->success('Successfully edited HWT Misc Catchall line item');
						
						$this->logActivity($_SERVER['REQUEST_URI'],'Edited HWT Misc Catchall line ID '.$lineItemID,$this->Auth->user('id'),json_encode($this->request));
					    
					    //redirect to quote or orderedit view
					    if($thisQuote['status'] == 'editorder'){
            			    return $this->redirect('/orders/editlines/'.$thisQuote['order_id']);
            			}else{
            				return $this->redirect('/quotes/add/'.$quoteID.'/');
            			}
					}
			        
			    break;
			    
			    case 'newcatchall-swtmisc':
			        
			        //make sure the image is an image
			        if(isset($this->request->data['imagefileupload']['tmp_name']) && $this->request->data['image_method'] == 'upload'){
                        $allowedTypes=array('.jpg','jpeg','.png','.gif');
    					if(!in_array(strtolower(substr($this->request->data['imagefileupload']['name'],-4)),$allowedTypes)){
    					    $this->autoRender=false;
    					    echo '<h3><span style="color:red;">ERROR:</span> The filetype <u>'.substr($this->request->data['imagefileupload']['name'],-4).'</u> is not allowed in the image field.</h3>
                            <p><button type="button" onclick="history.go(-1);">Go Back</button></p>';
    					    exit;
    					}
                    }
			        
			        //save all the field values
			        $thisItem=$this->QuoteLineItems->get($lineItemData['id']);
			        
			        $thisItem->title = $this->request->data['line_item_title'];
                    $thisItem->description = $this->request->data['description'];
                    $thisItem->best_price = $this->request->data['price'];
                    $thisItem->qty = $this->request->data['qty'];
                    $thisItem->room_number = $this->request->data['location'];
                    
                    
                    $thisItem->unit = $this->request->data['unit_type'];
                    

                    if($this->QuoteLineItems->save($thisItem)){
                        //save the meta data changes
                        $ignoreKeys=array('process','product_subclass','imagefileupload','description','line_item_title','customer_id','save_to_library','image_title','image_category','image_tags','fabric_name','fabric_color','libraryimageid');
                        
                        foreach($this->request->data as $postkey => $postdata){
                            if(!in_array($postkey,$ignoreKeys) && $postdata != ''){
                                
                                if($postkey=='fabric_type'){
                                    $postkey='fabrictype';
                                }
                                
                                $this->updateLineItemMeta($lineItemID,$postkey,$postdata);
                                
                            }
                        }
                        
                        
                        $newLineItemMeta=$this->QuoteLineItemMeta->newEntity();
                        $newLineItemMeta->quote_item_id = $newItem->id;
                        $newLineItemMeta->meta_key='lineitemtype';
                        $newLineItemMeta->meta_value='newcatchall';
                        $this->QuoteLineItemMeta->save($newLineItemMeta);
                        
                        
                        if($this->request->data['fabric_type']=="existing"){

							$this->updateLineItemMeta($lineItemID,'fabricid',$this->request->data['fabric_id_with_color']);
							
							$selectedFabric=$this->Fabrics->get($this->request->data['fabric_id_with_color'])->toArray();
							$this->updateLineItemMeta($lineItemID,'fabric_name',$selectedFabric['fabric_name']);
							
                            $this->updateLineItemMeta($lineItemID,'fabric_color',$selectedFabric['color']);
							

						}elseif($this->request->data['fabric_type']=="typein"){

                            $this->updateLineItemMeta($lineItemID,'fabric_name',$this->request->data['fabric_name']);
							
							$this->updateLineItemMeta($lineItemID,'fabric_color',$this->request->data['fabric_color']);

						}/*PPSA -36 start */elseif($this->request->data['fabric_type']=="none"){

                            $this->updateLineItemMeta($lineItemID,'fabricid','0');
        					$this->updateLineItemMeta($lineItemID,'fabric_name','');
        					$this->updateLineItemMeta($lineItemID,'fabric_color','');

						}
	/*PPSA -36 end */

						if($this->request->data['image_method'] == 'library'){
						    
						    $this->updateLineItemMeta($lineItemID,'libraryimageid',$this->request->data['libraryimageid']);

							
						}elseif($this->request->data['image_method'] == 'upload'){
							
							//handle the upload, and process whether to save this to library or just do a one-off insert
							$filename=$this->request->data['imagefileupload']['name'];
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
							
							
							move_uploaded_file($this->request->data['imagefileupload']['tmp_name'],$_SERVER['DOCUMENT_ROOT']."/webroot/img/library/".$filename);
							
							$imgLibraryTable=TableRegistry::get('LibraryImages');
							$newImage=$imgLibraryTable->newEntity();
							
							if($this->request->data['save_to_library'] == '1'){
								
								$selectedCat='';
								$allCats=$this->LibraryCategories->find('all')->toArray();
								foreach($allCats as $catEntry){
									if($catEntry['id'] == $this->request->data['image_category']){
										$selectedCat=$catEntry['category_title'];
									}
								}
								
								//insert into image library db with details for future usage
								$newImage->image_title=$this->request->data['image_title'];
								$newImage->categories=$selectedCat;
								$newImage->filename=$filename;
								$newImage->time=time();
								$newImage->added_by=$this->Auth->user('id');
								$newImage->tags=$this->request->data['image_tags'];
								$newImage->status='Active';
							}else{
								//insert into image library db with no details for single usage
								$newImage->image_title=$this->request->data['imagefileupload']['name'];
								$newImage->categories='Misc';
								$newImage->filename=$filename;
								$newImage->time=time();
								$newImage->added_by=$this->Auth->user('id');
								$newImage->status='Single Use';
							}
							
							if($imgLibraryTable->save($newImage)){
								//insert the metadata for libraryimageid
								$this->updateLineItemMeta($lineItemID,'libraryimageid',$newImage->id);
								
								$this->aspectratiofix($newImage->id,600);

							}
							
							
						}

						

						if($this->request->data['com-fabric']=='1'){

							$this->updateLineItemMeta($lineItemID,'com-fabric','1');

						}
						
                        /*PPSA-33 start */
						if(empty($this->request->data['fabric-cost-per-yard-custom-value'])) {

						$this->updateLineItemMeta($lineItemID,'fabric-cost-per-yard-custom-value','');

						}else {
						  $this->updateLineItemMeta($lineItemID,'fabric-cost-per-yard-custom-value',number_format($this->request->data['fabric-cost-per-yard-custom-value'], 2, '.', ''));  
						}
						
                        /*PPSA-33 end */
                        
                    }
			        /**PPSASSCRUM-29 start */	
					$orderDetails = $this->Orders->find('all',['conditions'=>['quote_id'=>$quoteID]])->first();

					if(isset($orderDetails) && !empty($orderDetails['id'])){

						$this->UpdatetoOrderLineItemTables($quoteID, $thisLineItem['id'],$orderDetails['id'],'orders');
						$this->UpdatetoOrderLineItemTables($quoteID, $thisLineItem['id'],$orderDetails['id'],'work_orders');
						//$this->savetoOrderLineItemTables($newItem->id, $quoteID,$orderDetails['id'], $table='orders' );
						//$this->savetoOrderLineItemTables($newItem->id, $quoteID,$orderDetails['id'], $table='work_orders' );	
					}
				  
					/**PPSASSCRUM-29 end */
			        //update modified time
			        $this->updatequotemodifiedtime($quoteID);
			        
			        //update tier adjustments
			        if($this->recalculatequoteadjustments($quoteID)){
			            
			            //get the new item data for PMI Adjusted for extended price
			            $freshItemData=$this->QuoteLineItems->get($lineItemID);
			            $freshItemData->extended_price = ($freshItemData->pmi_adjusted * floatval($this->request->data['qty']));
			            $this->QuoteLineItems->save($freshItemData);
			            
			            
			            
						$this->Flash->success('Successfully edited SWT Misc Catchall line item');
						
						$this->logActivity($_SERVER['REQUEST_URI'],'Edited SWT Misc Catchall line ID '.$lineItemID,$this->Auth->user('id'),json_encode($this->request));
					    
					    //redirect to quote or orderedit view
					    if($thisQuote['status'] == 'editorder'){
            			    return $this->redirect('/orders/editlines/'.$thisQuote['order_id']);
            			}else{
            				return $this->redirect('/quotes/add/'.$quoteID.'/');
            			}
					}
			        
			    break;
			    
			    case 'newcatchall-service':
			        
			        //make sure the image is an image
			        if(isset($this->request->data['imagefileupload']['tmp_name']) && $this->request->data['image_method'] == 'upload'){
                        $allowedTypes=array('.jpg','jpeg','.png','.gif');
    					if(!in_array(strtolower(substr($this->request->data['imagefileupload']['name'],-4)),$allowedTypes)){
    					    $this->autoRender=false;
    					    echo '<h3><span style="color:red;">ERROR:</span> The filetype <u>'.substr($this->request->data['imagefileupload']['name'],-4).'</u> is not allowed in the image field.</h3>
                            <p><button type="button" onclick="history.go(-1);">Go Back</button></p>';
    					    exit;
    					}
                    }
			        
			        //save all the field values
			        $thisItem=$this->QuoteLineItems->get($lineItemData['id']);
			        
			        $thisItem->title = $this->request->data['line_item_title'];
                    $thisItem->description = $this->request->data['description'];
                    $thisItem->best_price = $this->request->data['price'];
                    $thisItem->qty = $this->request->data['qty'];
                    $thisItem->room_number = $this->request->data['location'];
                    
                    
                    $thisItem->product_subclass=$this->request->data['product_subclass'];
			        
                    $thisSubClass=$this->ProductSubclasses->get($this->request->data['product_subclass'])->toArray();
                    $thisItem->enable_tally=$thisSubClass['tally'];
                    
                    
                    $thisItem->unit = $this->request->data['unit_type'];
                    

                    if($this->QuoteLineItems->save($thisItem)){
                        //save the meta data changes
                        $ignoreKeys=array('process','product_subclass','imagefileupload','description','line_item_title','customer_id','save_to_library','image_title','image_category','image_tags','fabric_name','fabric_color','libraryimageid');
                        
                        foreach($this->request->data as $postkey => $postdata){
                            if(!in_array($postkey,$ignoreKeys) && $postdata != ''){
                                
                                if($postkey=='fabric_type'){
                                    $postkey='fabrictype';
                                }
                                
                                $this->updateLineItemMeta($lineItemID,$postkey,$postdata);
                                
                            }
                        }
                        
                        
                        $newLineItemMeta=$this->QuoteLineItemMeta->newEntity();
                        $newLineItemMeta->quote_item_id = $newItem->id;
                        $newLineItemMeta->meta_key='lineitemtype';
                        $newLineItemMeta->meta_value='newcatchall';
                        $this->QuoteLineItemMeta->save($newLineItemMeta);
                        
                        /*
                        if($this->request->data['fabric_type']=="existing"){

							$this->updateLineItemMeta($lineItemID,'fabricid',$this->request->data['fabric_id_with_color']);
							
							$selectedFabric=$this->Fabrics->get($this->request->data['fabric_id_with_color'])->toArray();
							$this->updateLineItemMeta($lineItemID,'fabric_name',$selectedFabric['fabric_name']);
							
                            $this->updateLineItemMeta($lineItemID,'fabric_color',$selectedFabric['color']);
							

						}elseif($this->request->data['fabric_type']=="typein"){

                            $this->updateLineItemMeta($lineItemID,'fabric_name',$this->request->data['fabric_name']);
							
							$this->updateLineItemMeta($lineItemID,'fabric_color',$this->request->data['fabric_color']);

						}
						*/

						if($this->request->data['image_method'] == 'library'){
						    
						    $this->updateLineItemMeta($lineItemID,'libraryimageid',$this->request->data['libraryimageid']);

							
						}elseif($this->request->data['image_method'] == 'upload'){
							
							//handle the upload, and process whether to save this to library or just do a one-off insert
							$filename=$this->request->data['imagefileupload']['name'];
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
							
							
							move_uploaded_file($this->request->data['imagefileupload']['tmp_name'],$_SERVER['DOCUMENT_ROOT']."/webroot/img/library/".$filename);
							
							$imgLibraryTable=TableRegistry::get('LibraryImages');
							$newImage=$imgLibraryTable->newEntity();
							
							if($this->request->data['save_to_library'] == '1'){
								
								$selectedCat='';
								$allCats=$this->LibraryCategories->find('all')->toArray();
								foreach($allCats as $catEntry){
									if($catEntry['id'] == $this->request->data['image_category']){
										$selectedCat=$catEntry['category_title'];
									}
								}
								
								//insert into image library db with details for future usage
								$newImage->image_title=$this->request->data['image_title'];
								$newImage->categories=$selectedCat;
								$newImage->filename=$filename;
								$newImage->time=time();
								$newImage->added_by=$this->Auth->user('id');
								$newImage->tags=$this->request->data['image_tags'];
								$newImage->status='Active';
							}else{
								//insert into image library db with no details for single usage
								$newImage->image_title=$this->request->data['imagefileupload']['name'];
								$newImage->categories='Misc';
								$newImage->filename=$filename;
								$newImage->time=time();
								$newImage->added_by=$this->Auth->user('id');
								$newImage->status='Single Use';
							}
							
							if($imgLibraryTable->save($newImage)){
								//insert the metadata for libraryimageid
								$this->updateLineItemMeta($lineItemID,'libraryimageid',$newImage->id);
								
								$this->aspectratiofix($newImage->id,600);

							}
							
							
						}

						

						if($this->request->data['com-fabric']=='1'){

							$this->updateLineItemMeta($lineItemID,'com-fabric','1');

						}
						
                        
                        
                    }
			        /**PPSASSCRUM-29 start */	
					$orderDetails = $this->Orders->find('all',['conditions'=>['quote_id'=>$quoteID]])->first();

					if(isset($orderDetails) && !empty($orderDetails['id'])){

						$this->UpdatetoOrderLineItemTables($quoteID, $thisLineItem['id'],$orderDetails['id'],'orders');
						$this->UpdatetoOrderLineItemTables($quoteID, $thisLineItem['id'],$orderDetails['id'],'work_orders');
						//$this->savetoOrderLineItemTables($newItem->id, $quoteID,$orderDetails['id'], $table='orders' );
						//$this->savetoOrderLineItemTables($newItem->id, $quoteID,$orderDetails['id'], $table='work_orders' );	
					}
				  
					/**PPSASSCRUM-29 end */
			        //update modified time
			        $this->updatequotemodifiedtime($quoteID);
			        
			        //update tier adjustments
			        if($this->recalculatequoteadjustments($quoteID)){
			            
			            //get the new item data for PMI Adjusted for extended price
			            $freshItemData=$this->QuoteLineItems->get($lineItemID);
			            $freshItemData->extended_price = ($freshItemData->pmi_adjusted * floatval($this->request->data['qty']));
			            $this->QuoteLineItems->save($freshItemData);
			            
			            
			            
						$this->Flash->success('Successfully edited Service Catchall line item');
						
						$this->logActivity($_SERVER['REQUEST_URI'],'Edited Service Catchall line ID '.$lineItemID,$this->Auth->user('id'),json_encode($this->request));
					    
					    //redirect to quote or orderedit view
					    if($thisQuote['status'] == 'editorder'){
            			    return $this->redirect('/orders/editlines/'.$thisQuote['order_id']);
            			}else{
            				return $this->redirect('/quotes/add/'.$quoteID.'/');
            			}
					}
			        
			    break;
			    
			    case 'newcatchall-valance':
			        
			        //make sure the image is an image
			        if(isset($this->request->data['imagefileupload']['tmp_name']) && $this->request->data['image_method'] == 'upload'){
                        $allowedTypes=array('.jpg','jpeg','.png','.gif');
    					if(!in_array(strtolower(substr($this->request->data['imagefileupload']['name'],-4)),$allowedTypes)){
    					    $this->autoRender=false;
    					    echo '<h3><span style="color:red;">ERROR:</span> The filetype <u>'.substr($this->request->data['imagefileupload']['name'],-4).'</u> is not allowed in the image field.</h3>
                            <p><button type="button" onclick="history.go(-1);">Go Back</button></p>';
    					    exit;
    					}
                    }
			        
			        //save all the field values
			        $thisItem=$this->QuoteLineItems->get($lineItemData['id']);
			        
			        $thisItem->title = $this->request->data['line_item_title'];
                    $thisItem->description = $this->request->data['description'];
                    $thisItem->best_price = $this->request->data['price'];
                    $thisItem->qty = $this->request->data['qty'];
                    $thisItem->room_number = $this->request->data['location'];
                    
                    
                    $thisItem->unit = $this->request->data['unit_type'];
                    

                    if($this->QuoteLineItems->save($thisItem)){
                        //save the meta data changes
                        $ignoreKeys=array('process','product_subclass','imagefileupload','description','line_item_title','customer_id','save_to_library','image_title','image_category','image_tags','fabric_name','fabric_color','libraryimageid');
                        
                        foreach($this->request->data as $postkey => $postdata){
                            if(!in_array($postkey,$ignoreKeys) && $postdata != ''){
                                
                                if($postkey=='fabric_type'){
                                    $postkey='fabrictype';
                                }
                                
                                $this->updateLineItemMeta($lineItemID,$postkey,$postdata);
                                
                            }
                        }
                        
                        if(!isset($this->request->data['fl-short'])){
                            $this->deleteLineItemMeta($lineItemID,'fl-short');
                        }
                        if(!isset($this->request->data['fl-mid'])){
                            $this->deleteLineItemMeta($lineItemID,'fl-mid');
                        }
                        
                        $newLineItemMeta=$this->QuoteLineItemMeta->newEntity();
                        $newLineItemMeta->quote_item_id = $newItem->id;
                        $newLineItemMeta->meta_key='lineitemtype';
                        $newLineItemMeta->meta_value='newcatchall';
                        $this->QuoteLineItemMeta->save($newLineItemMeta);
                        
                        
                        if($this->request->data['fabric_type']=="existing"){

							$this->updateLineItemMeta($lineItemID,'fabricid',$this->request->data['fabric_id_with_color']);
							
							$selectedFabric=$this->Fabrics->get($this->request->data['fabric_id_with_color'])->toArray();
							$this->updateLineItemMeta($lineItemID,'fabric_name',$selectedFabric['fabric_name']);
							
                            $this->updateLineItemMeta($lineItemID,'fabric_color',$selectedFabric['color']);
							

						}elseif($this->request->data['fabric_type']=="typein"){

                            $this->updateLineItemMeta($lineItemID,'fabric_name',$this->request->data['fabric_name']);
							
							$this->updateLineItemMeta($lineItemID,'fabric_color',$this->request->data['fabric_color']);

						}/*PPSA -36 start */elseif($this->request->data['fabric_type']=="none"){

                            $this->updateLineItemMeta($lineItemID,'fabricid','0');
        					$this->updateLineItemMeta($lineItemID,'fabric_name','');
        					$this->updateLineItemMeta($lineItemID,'fabric_color','');

						}
	/*PPSA -36 end */

						if($this->request->data['image_method'] == 'library'){
						    
						    $this->updateLineItemMeta($lineItemID,'libraryimageid',$this->request->data['libraryimageid']);

							
						}elseif($this->request->data['image_method'] == 'upload'){
							
							//handle the upload, and process whether to save this to library or just do a one-off insert
							$filename=$this->request->data['imagefileupload']['name'];
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
							
							
							move_uploaded_file($this->request->data['imagefileupload']['tmp_name'],$_SERVER['DOCUMENT_ROOT']."/webroot/img/library/".$filename);
							
							$imgLibraryTable=TableRegistry::get('LibraryImages');
							$newImage=$imgLibraryTable->newEntity();
							
							if($this->request->data['save_to_library'] == '1'){
								
								$selectedCat='';
								$allCats=$this->LibraryCategories->find('all')->toArray();
								foreach($allCats as $catEntry){
									if($catEntry['id'] == $this->request->data['image_category']){
										$selectedCat=$catEntry['category_title'];
									}
								}
								
								//insert into image library db with details for future usage
								$newImage->image_title=$this->request->data['image_title'];
								$newImage->categories=$selectedCat;
								$newImage->filename=$filename;
								$newImage->time=time();
								$newImage->added_by=$this->Auth->user('id');
								$newImage->tags=$this->request->data['image_tags'];
								$newImage->status='Active';
							}else{
								//insert into image library db with no details for single usage
								$newImage->image_title=$this->request->data['imagefileupload']['name'];
								$newImage->categories='Misc';
								$newImage->filename=$filename;
								$newImage->time=time();
								$newImage->added_by=$this->Auth->user('id');
								$newImage->status='Single Use';
							}
							
							if($imgLibraryTable->save($newImage)){
								//insert the metadata for libraryimageid
								$this->updateLineItemMeta($lineItemID,'libraryimageid',$newImage->id);
								
								$this->aspectratiofix($newImage->id,600);

							}
							
							
						}

						

						if($this->request->data['com-fabric']=='1'){

							$this->updateLineItemMeta($lineItemID,'com-fabric','1');

						}
						
                        
                        /*PPSA-33 start */
						if(empty($this->request->data['fabric-cost-per-yard-custom-value'])) {

							$this->updateLineItemMeta($lineItemID,'fabric-cost-per-yard-custom-value','');

						}else {
						  $this->updateLineItemMeta($lineItemID,"fabric-cost-per-yard-custom-value",number_format($this->request->data['fabric-cost-per-yard-custom-value'], 2, '.', ''));  
						}
						
                        /*PPSA-33 end */
                    }
			        /**PPSASSCRUM-29 start */	
					$orderDetails = $this->Orders->find('all',['conditions'=>['quote_id'=>$quoteID]])->first();

					if(isset($orderDetails) && !empty($orderDetails['id'])){

						$this->UpdatetoOrderLineItemTables($quoteID, $thisLineItem['id'],$orderDetails['id'],'orders');
						$this->UpdatetoOrderLineItemTables($quoteID, $thisLineItem['id'],$orderDetails['id'],'work_orders');
						//$this->savetoOrderLineItemTables($newItem->id, $quoteID,$orderDetails['id'], $table='orders' );
						//$this->savetoOrderLineItemTables($newItem->id, $quoteID,$orderDetails['id'], $table='work_orders' );	
					}
				  
					/**PPSASSCRUM-29 end */
			        //update modified time
			        $this->updatequotemodifiedtime($quoteID);
			        
			        //update tier adjustments
			        if($this->recalculatequoteadjustments($quoteID)){
			            
			            //get the new item data for PMI Adjusted for extended price
			            $freshItemData=$this->QuoteLineItems->get($lineItemID);
			            $freshItemData->extended_price = ($freshItemData->pmi_adjusted * floatval($this->request->data['qty']));
			            $this->QuoteLineItems->save($freshItemData);
			            
			            
			            
						$this->Flash->success('Successfully edited Valance Catchall line item');
						
						$this->logActivity($_SERVER['REQUEST_URI'],'Edited Valance Catchall line ID '.$lineItemID,$this->Auth->user('id'),json_encode($this->request));
					    
					    //redirect to quote or orderedit view
					    if($thisQuote['status'] == 'editorder'){
            			    return $this->redirect('/orders/editlines/'.$thisQuote['order_id']);
            			}else{
            				return $this->redirect('/quotes/add/'.$quoteID.'/');
            			}
					}
			        
			    break;
			    
			    case 'newcatchall-drapery':
			        
			        //make sure the image is an image
			        if(isset($this->request->data['imagefileupload']['tmp_name']) && $this->request->data['image_method'] == 'upload'){
                        $allowedTypes=array('.jpg','jpeg','.png','.gif');
    					if(!in_array(strtolower(substr($this->request->data['imagefileupload']['name'],-4)),$allowedTypes)){
    					    $this->autoRender=false;
    					    echo '<h3><span style="color:red;">ERROR:</span> The filetype <u>'.substr($this->request->data['imagefileupload']['name'],-4).'</u> is not allowed in the image field.</h3>
                            <p><button type="button" onclick="history.go(-1);">Go Back</button></p>';
    					    exit;
    					}
                    }
			        
			        //save all the field values
			        $thisItem=$this->QuoteLineItems->get($lineItemData['id']);
			        
			        $thisItem->title = $this->request->data['line_item_title'];
                    $thisItem->description = $this->request->data['description'];
                    $thisItem->best_price = $this->request->data['price'];
                    $thisItem->qty = $this->request->data['qty'];
                    $thisItem->room_number = $this->request->data['location'];
                    
                    
                    $thisItem->unit = $this->request->data['unit_type'];
                    

                    if($this->QuoteLineItems->save($thisItem)){
                        //save the meta data changes
                        $ignoreKeys=array('process','product_subclass','imagefileupload','description','line_item_title','customer_id','save_to_library','image_title','image_category','image_tags','fabric_name','fabric_color','libraryimageid');
                        
                        foreach($this->request->data as $postkey => $postdata){
                            if(!in_array($postkey,$ignoreKeys) && $postdata != ''){
                                
                                if($postkey=='fabric_type'){
                                    $postkey='fabrictype';
                                }
                                
                                $this->updateLineItemMeta($lineItemID,$postkey,$postdata);
                                
                            }
                        }
                        
                        
                        $newLineItemMeta=$this->QuoteLineItemMeta->newEntity();
                        $newLineItemMeta->quote_item_id = $newItem->id;
                        $newLineItemMeta->meta_key='lineitemtype';
                        $newLineItemMeta->meta_value='newcatchall';
                        $this->QuoteLineItemMeta->save($newLineItemMeta);
                        
                        
                        if($this->request->data['fabric_type']=="existing"){

							$this->updateLineItemMeta($lineItemID,'fabricid',$this->request->data['fabric_id_with_color']);
							
							$selectedFabric=$this->Fabrics->get($this->request->data['fabric_id_with_color'])->toArray();
							$this->updateLineItemMeta($lineItemID,'fabric_name',$selectedFabric['fabric_name']);
							
                            $this->updateLineItemMeta($lineItemID,'fabric_color',$selectedFabric['color']);
							

						}elseif($this->request->data['fabric_type']=="typein"){

                            $this->updateLineItemMeta($lineItemID,'fabric_name',$this->request->data['fabric_name']);
							
							$this->updateLineItemMeta($lineItemID,'fabric_color',$this->request->data['fabric_color']);

						}/*PPSA -36 start */elseif($this->request->data['fabric_type']=="none"){

                            $this->updateLineItemMeta($lineItemID,'fabricid','0');
        					$this->updateLineItemMeta($lineItemID,'fabric_name','');
        					$this->updateLineItemMeta($lineItemID,'fabric_color','');

						}
	/*PPSA -36 end */

						if($this->request->data['image_method'] == 'library'){
						    
						    $this->updateLineItemMeta($lineItemID,'libraryimageid',$this->request->data['libraryimageid']);

							
						}elseif($this->request->data['image_method'] == 'upload'){
							
							//handle the upload, and process whether to save this to library or just do a one-off insert
							$filename=$this->request->data['imagefileupload']['name'];
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
							
							
							move_uploaded_file($this->request->data['imagefileupload']['tmp_name'],$_SERVER['DOCUMENT_ROOT']."/webroot/img/library/".$filename);
							
							$imgLibraryTable=TableRegistry::get('LibraryImages');
							$newImage=$imgLibraryTable->newEntity();
							
							if($this->request->data['save_to_library'] == '1'){
								
								$selectedCat='';
								$allCats=$this->LibraryCategories->find('all')->toArray();
								foreach($allCats as $catEntry){
									if($catEntry['id'] == $this->request->data['image_category']){
										$selectedCat=$catEntry['category_title'];
									}
								}
								
								//insert into image library db with details for future usage
								$newImage->image_title=$this->request->data['image_title'];
								$newImage->categories=$selectedCat;
								$newImage->filename=$filename;
								$newImage->time=time();
								$newImage->added_by=$this->Auth->user('id');
								$newImage->tags=$this->request->data['image_tags'];
								$newImage->status='Active';
							}else{
								//insert into image library db with no details for single usage
								$newImage->image_title=$this->request->data['imagefileupload']['name'];
								$newImage->categories='Misc';
								$newImage->filename=$filename;
								$newImage->time=time();
								$newImage->added_by=$this->Auth->user('id');
								$newImage->status='Single Use';
							}
							
							if($imgLibraryTable->save($newImage)){
								//insert the metadata for libraryimageid
								$this->updateLineItemMeta($lineItemID,'libraryimageid',$newImage->id);
								
								$this->aspectratiofix($newImage->id,600);

							}
							
							
						}

						

						if($this->request->data['com-fabric']=='1'){

							$this->updateLineItemMeta($lineItemID,'com-fabric','1');

						}
						
                        	/*PPSA-33 start */
						if(empty($this->request->data['fabric-cost-per-yard-custom-value'])) {

						 $this->updateLineItemMeta($lineItemID,'fabric-cost-per-yard-custom-value','');

						}else {
						  $this->updateLineItemMeta($lineItemID,'fabric-cost-per-yard-custom-value',number_format($this->request->data['fabric-cost-per-yard-custom-value'], 2, '.', ''));  
						}
						
                        /*PPSA-33 end */
                        
                    }
			        /**PPSASSCRUM-29 start */	
					$orderDetails = $this->Orders->find('all',['conditions'=>['quote_id'=>$quoteID]])->first();

					if(isset($orderDetails) && !empty($orderDetails['id'])){

						$this->UpdatetoOrderLineItemTables($quoteID, $thisLineItem['id'],$orderDetails['id'],'orders');
						$this->UpdatetoOrderLineItemTables($quoteID, $thisLineItem['id'],$orderDetails['id'],'work_orders');
						//$this->savetoOrderLineItemTables($newItem->id, $quoteID,$orderDetails['id'], $table='orders' );
						//$this->savetoOrderLineItemTables($newItem->id, $quoteID,$orderDetails['id'], $table='work_orders' );	
					}
				  
					/**PPSASSCRUM-29 end */
			        //update modified time
			        $this->updatequotemodifiedtime($quoteID);
			        
			        //update tier adjustments
			        if($this->recalculatequoteadjustments($quoteID)){
			            
			            //get the new item data for PMI Adjusted for extended price
			            $freshItemData=$this->QuoteLineItems->get($lineItemID);
			            $freshItemData->extended_price = ($freshItemData->pmi_adjusted * floatval($this->request->data['qty']));
			            $this->QuoteLineItems->save($freshItemData);
			            
			            
			            
						$this->Flash->success('Successfully edited Drapery Catchall line item');
						
						$this->logActivity($_SERVER['REQUEST_URI'],'Edited Drapery Catchall line ID '.$lineItemID,$this->Auth->user('id'),json_encode($this->request));
					    
					    //redirect to quote or orderedit view
					    if($thisQuote['status'] == 'editorder'){
            			    return $this->redirect('/orders/editlines/'.$thisQuote['order_id']);
            			}else{
            				return $this->redirect('/quotes/add/'.$quoteID.'/');
            			}
					}
			        
			    break;
			    
			    case 'newcatchall-cornice':
			        
			        //make sure the image is an image
			        if(isset($this->request->data['imagefileupload']['tmp_name']) && $this->request->data['image_method'] == 'upload'){
                        $allowedTypes=array('.jpg','jpeg','.png','.gif');
    					if(!in_array(strtolower(substr($this->request->data['imagefileupload']['name'],-4)),$allowedTypes)){
    					    $this->autoRender=false;
    					    echo '<h3><span style="color:red;">ERROR:</span> The filetype <u>'.substr($this->request->data['imagefileupload']['name'],-4).'</u> is not allowed in the image field.</h3>
                            <p><button type="button" onclick="history.go(-1);">Go Back</button></p>';
    					    exit;
    					}
                    }
			        
			        //save all the field values
			        $thisItem=$this->QuoteLineItems->get($lineItemData['id']);
			        
			        $thisItem->title = $this->request->data['line_item_title'];
                    $thisItem->description = $this->request->data['description'];
                    $thisItem->best_price = $this->request->data['price'];
                    $thisItem->qty = $this->request->data['qty'];
                    $thisItem->room_number = $this->request->data['location'];
                    
                    
                    $thisItem->unit = $this->request->data['unit_type'];
                    

                    if($this->QuoteLineItems->save($thisItem)){
                        //save the meta data changes
                        $ignoreKeys=array('process','product_subclass','imagefileupload','description','line_item_title','customer_id','save_to_library','image_title','image_category','image_tags','fabric_name','fabric_color','libraryimageid');
                        
                        foreach($this->request->data as $postkey => $postdata){
                            if(!in_array($postkey,$ignoreKeys) && $postdata != ''){
                                
                                if($postkey=='fabric_type'){
                                    $postkey='fabrictype';
                                }
                                
                                $this->updateLineItemMeta($lineItemID,$postkey,$postdata);
                                
                            }
                        }
                        
                        
                        if(!isset($this->request->data['fl-short'])){
                            $this->deleteLineItemMeta($lineItemID,'fl-short');
                        }
                        if(!isset($this->request->data['fl-mid'])){
                            $this->deleteLineItemMeta($lineItemID,'fl-mid');
                        }
                        
                        
                        $newLineItemMeta=$this->QuoteLineItemMeta->newEntity();
                        $newLineItemMeta->quote_item_id = $newItem->id;
                        $newLineItemMeta->meta_key='lineitemtype';
                        $newLineItemMeta->meta_value='newcatchall';
                        $this->QuoteLineItemMeta->save($newLineItemMeta);
                        
                        
                        if($this->request->data['fabric_type']=="existing"){

							$this->updateLineItemMeta($lineItemID,'fabricid',$this->request->data['fabric_id_with_color']);
							
							$selectedFabric=$this->Fabrics->get($this->request->data['fabric_id_with_color'])->toArray();
							$this->updateLineItemMeta($lineItemID,'fabric_name',$selectedFabric['fabric_name']);
							
                            $this->updateLineItemMeta($lineItemID,'fabric_color',$selectedFabric['color']);
							

						}elseif($this->request->data['fabric_type']=="typein"){

                            $this->updateLineItemMeta($lineItemID,'fabric_name',$this->request->data['fabric_name']);
							
							$this->updateLineItemMeta($lineItemID,'fabric_color',$this->request->data['fabric_color']);

						}/*PPSA -36 start */elseif($this->request->data['fabric_type']=="none"){

                            $this->updateLineItemMeta($lineItemID,'fabricid','0');
        					$this->updateLineItemMeta($lineItemID,'fabric_name','');
        					$this->updateLineItemMeta($lineItemID,'fabric_color','');

						}
	/*PPSA -36 end */

						if($this->request->data['image_method'] == 'library'){
						    
						    $this->updateLineItemMeta($lineItemID,'libraryimageid',$this->request->data['libraryimageid']);

							
						}elseif($this->request->data['image_method'] == 'upload'){
							
							//handle the upload, and process whether to save this to library or just do a one-off insert
							$filename=$this->request->data['imagefileupload']['name'];
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
							
							
							move_uploaded_file($this->request->data['imagefileupload']['tmp_name'],$_SERVER['DOCUMENT_ROOT']."/webroot/img/library/".$filename);
							
							$imgLibraryTable=TableRegistry::get('LibraryImages');
							$newImage=$imgLibraryTable->newEntity();
							
							if($this->request->data['save_to_library'] == '1'){
								
								$selectedCat='';
								$allCats=$this->LibraryCategories->find('all')->toArray();
								foreach($allCats as $catEntry){
									if($catEntry['id'] == $this->request->data['image_category']){
										$selectedCat=$catEntry['category_title'];
									}
								}
								
								//insert into image library db with details for future usage
								$newImage->image_title=$this->request->data['image_title'];
								$newImage->categories=$selectedCat;
								$newImage->filename=$filename;
								$newImage->time=time();
								$newImage->added_by=$this->Auth->user('id');
								$newImage->tags=$this->request->data['image_tags'];
								$newImage->status='Active';
							}else{
								//insert into image library db with no details for single usage
								$newImage->image_title=$this->request->data['imagefileupload']['name'];
								$newImage->categories='Misc';
								$newImage->filename=$filename;
								$newImage->time=time();
								$newImage->added_by=$this->Auth->user('id');
								$newImage->status='Single Use';
							}
							
							if($imgLibraryTable->save($newImage)){
								//insert the metadata for libraryimageid
								$this->updateLineItemMeta($lineItemID,'libraryimageid',$newImage->id);
								
								$this->aspectratiofix($newImage->id,600);

							}
							
							
						}

						

						if($this->request->data['com-fabric']=='1'){

							$this->updateLineItemMeta($lineItemID,'com-fabric','1');

						}
						
                        /*PPSA-33 start */
						if(empty($this->request->data['fabric-cost-per-yard-custom-value'])) {

						$this->updateLineItemMeta($lineItemID,'fabric-cost-per-yard-custom-value','');

						}else {
						  $this->updateLineItemMeta($lineItemID,"fabric-cost-per-yard-custom-value",number_format($this->request->data['fabric-cost-per-yard-custom-value'], 2, '.', ''));  
						}
						
                        /*PPSA-33 end */
                        
                    }
			        /**PPSASSCRUM-29 start */	
					$orderDetails = $this->Orders->find('all',['conditions'=>['quote_id'=>$quoteID]])->first();

					if(isset($orderDetails) && !empty($orderDetails['id'])){

						$this->UpdatetoOrderLineItemTables($quoteID, $thisLineItem['id'],$orderDetails['id'],'orders');
						$this->UpdatetoOrderLineItemTables($quoteID, $thisLineItem['id'],$orderDetails['id'],'work_orders');
						//$this->savetoOrderLineItemTables($newItem->id, $quoteID,$orderDetails['id'], $table='orders' );
						//$this->savetoOrderLineItemTables($newItem->id, $quoteID,$orderDetails['id'], $table='work_orders' );	
					}
				  
					/**PPSASSCRUM-29 end */
			        //update modified time
			        $this->updatequotemodifiedtime($quoteID);
			        
			        //update tier adjustments
			        if($this->recalculatequoteadjustments($quoteID)){
			            
			            //get the new item data for PMI Adjusted for extended price
			            $freshItemData=$this->QuoteLineItems->get($lineItemID);
			            $freshItemData->extended_price = ($freshItemData->pmi_adjusted * floatval($this->request->data['qty']));
			            $this->QuoteLineItems->save($freshItemData);
			            
			            
			            
						$this->Flash->success('Successfully edited Cornice Catchall line item');
						
						$this->logActivity($_SERVER['REQUEST_URI'],'Edited Cornice Catchall line ID '.$lineItemID,$this->Auth->user('id'),json_encode($this->request));
					    
					    //redirect to quote or orderedit view
					    if($thisQuote['status'] == 'editorder'){
            			    return $this->redirect('/orders/editlines/'.$thisQuote['order_id']);
            			}else{
            				return $this->redirect('/quotes/add/'.$quoteID.'/');
            			}
					}
			        
			    break;
			    
			    case 'newcatchall-cubicle':
			        
			        //make sure the image is an image
			        if(isset($this->request->data['imagefileupload']['tmp_name']) && $this->request->data['image_method'] == 'upload'){
                        $allowedTypes=array('.jpg','jpeg','.png','.gif');
    					if(!in_array(strtolower(substr($this->request->data['imagefileupload']['name'],-4)),$allowedTypes)){
    					    $this->autoRender=false;
    					    echo '<h3><span style="color:red;">ERROR:</span> The filetype <u>'.substr($this->request->data['imagefileupload']['name'],-4).'</u> is not allowed in the image field.</h3>
                            <p><button type="button" onclick="history.go(-1);">Go Back</button></p>';
    					    exit;
    					}
                    }
			        
			        //save all the field values
			        $thisItem=$this->QuoteLineItems->get($lineItemData['id']);
			        
			        $thisItem->title = $this->request->data['line_item_title'];
                    $thisItem->description = $this->request->data['description'];
                    $thisItem->best_price = $this->request->data['price'];
                    $thisItem->qty = $this->request->data['qty'];
                    $thisItem->room_number = $this->request->data['location'];
                    
                    
                    $thisItem->product_subclass=$this->request->data['product_subclass'];
			        
                    $thisSubClass=$this->ProductSubclasses->get($this->request->data['product_subclass'])->toArray();
                    $thisItem->enable_tally=$thisSubClass['tally'];
                    
                    
                    $thisItem->unit = $this->request->data['unit_type'];
                    

                    if($this->QuoteLineItems->save($thisItem)){
                        //save the meta data changes
                        $ignoreKeys=array('process','product_subclass','imagefileupload','description','line_item_title','customer_id','save_to_library','image_title','image_category','image_tags','fabric_name','fabric_color','libraryimageid');
                        
                        foreach($this->request->data as $postkey => $postdata){
                            if(!in_array($postkey,$ignoreKeys) && $postdata != ''){
                                
                                if($postkey=='fabric_type'){
                                    $postkey='fabrictype';
                                }
                                
                                $this->updateLineItemMeta($lineItemID,$postkey,$postdata);
                                
                            }
                        }
                        
                        
                        $newLineItemMeta=$this->QuoteLineItemMeta->newEntity();
                        $newLineItemMeta->quote_item_id = $newItem->id;
                        $newLineItemMeta->meta_key='lineitemtype';
                        $newLineItemMeta->meta_value='newcatchall';
                        $this->QuoteLineItemMeta->save($newLineItemMeta);
                        
                        
                        if($this->request->data['fabric_type']=="existing"){

							$this->updateLineItemMeta($lineItemID,'fabricid',$this->request->data['fabric_id_with_color']);
							
							$selectedFabric=$this->Fabrics->get($this->request->data['fabric_id_with_color'])->toArray();
							$this->updateLineItemMeta($lineItemID,'fabric_name',$selectedFabric['fabric_name']);
							
                            $this->updateLineItemMeta($lineItemID,'fabric_color',$selectedFabric['color']);
							

						}elseif($this->request->data['fabric_type']=="typein"){

                            $this->updateLineItemMeta($lineItemID,'fabric_name',$this->request->data['fabric_name']);
							
							$this->updateLineItemMeta($lineItemID,'fabric_color',$this->request->data['fabric_color']);

						}
						/*PPSA -36 start */elseif($this->request->data['fabric_type']=="none"){

                            $this->updateLineItemMeta($lineItemID,'fabricid','0');
        					$this->updateLineItemMeta($lineItemID,'fabric_name','');
        					$this->updateLineItemMeta($lineItemID,'fabric_color','');

						}
	/*PPSA -36 end */

						if($this->request->data['image_method'] == 'library'){
						    
						    $this->updateLineItemMeta($lineItemID,'libraryimageid',$this->request->data['libraryimageid']);

							
						}elseif($this->request->data['image_method'] == 'upload'){
							
							//handle the upload, and process whether to save this to library or just do a one-off insert
							$filename=$this->request->data['imagefileupload']['name'];
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
							
							
							move_uploaded_file($this->request->data['imagefileupload']['tmp_name'],$_SERVER['DOCUMENT_ROOT']."/webroot/img/library/".$filename);
							
							$imgLibraryTable=TableRegistry::get('LibraryImages');
							$newImage=$imgLibraryTable->newEntity();
							
							if($this->request->data['save_to_library'] == '1'){
								
								$selectedCat='';
								$allCats=$this->LibraryCategories->find('all')->toArray();
								foreach($allCats as $catEntry){
									if($catEntry['id'] == $this->request->data['image_category']){
										$selectedCat=$catEntry['category_title'];
									}
								}
								
								//insert into image library db with details for future usage
								$newImage->image_title=$this->request->data['image_title'];
								$newImage->categories=$selectedCat;
								$newImage->filename=$filename;
								$newImage->time=time();
								$newImage->added_by=$this->Auth->user('id');
								$newImage->tags=$this->request->data['image_tags'];
								$newImage->status='Active';
							}else{
								//insert into image library db with no details for single usage
								$newImage->image_title=$this->request->data['imagefileupload']['name'];
								$newImage->categories='Misc';
								$newImage->filename=$filename;
								$newImage->time=time();
								$newImage->added_by=$this->Auth->user('id');
								$newImage->status='Single Use';
							}
							
							if($imgLibraryTable->save($newImage)){
								//insert the metadata for libraryimageid
								$this->updateLineItemMeta($lineItemID,'libraryimageid',$newImage->id);
								
								$this->aspectratiofix($newImage->id,600);

							}
							
							
						}

						

						if($this->request->data['com-fabric']=='1'){

							$this->updateLineItemMeta($lineItemID,'com-fabric','1');

						}
						
						/*PPSA-33 start */
                        	
						if(empty($this->request->data['fabric-cost-per-yard-custom-value'])) {

							$this->updateLineItemMeta($lineItemID,'fabric-cost-per-yard-custom-value','');

						}else {
						  $this->updateLineItemMeta($lineItemID,'fabric-cost-per-yard-custom-value',number_format($this->request->data['fabric-cost-per-yard-custom-value'], 2, '.', ''));  
						}
						
                        /*PPSA-33 end */
                        
                        
                    }
			        /**PPSASSCRUM-29 start */	
					$orderDetails = $this->Orders->find('all',['conditions'=>['quote_id'=>$quoteID]])->first();

					if(isset($orderDetails) && !empty($orderDetails['id'])){

						$this->UpdatetoOrderLineItemTables($quoteID, $thisLineItem['id'],$orderDetails['id'],'orders');
						$this->UpdatetoOrderLineItemTables($quoteID, $thisLineItem['id'],$orderDetails['id'],'work_orders');
						//$this->savetoOrderLineItemTables($newItem->id, $quoteID,$orderDetails['id'], $table='orders' );
						//$this->savetoOrderLineItemTables($newItem->id, $quoteID,$orderDetails['id'], $table='work_orders' );	
					}
				  
					/**PPSASSCRUM-29 end */
			        //update modified time
			        $this->updatequotemodifiedtime($quoteID);
			        
			        //update tier adjustments
			        if($this->recalculatequoteadjustments($quoteID)){
			            
			            //get the new item data for PMI Adjusted for extended price
			            $freshItemData=$this->QuoteLineItems->get($lineItemID);
			            $freshItemData->extended_price = ($freshItemData->pmi_adjusted * floatval($this->request->data['qty']));
			            $this->QuoteLineItems->save($freshItemData);
			            
			            
			            
						$this->Flash->success('Successfully edited CC Catchall line item');
						
						$this->logActivity($_SERVER['REQUEST_URI'],'Edited CC Catchall line ID '.$lineItemID,$this->Auth->user('id'),json_encode($this->request));
					    
					    //redirect to quote or orderedit view
					    if($thisQuote['status'] == 'editorder'){
            			    return $this->redirect('/orders/editlines/'.$thisQuote['order_id']);
            			}else{
            				return $this->redirect('/quotes/add/'.$quoteID.'/');
            			}
					}
			        
			    break;
			    
			    case 'newcatchall-bedding':
			        
			        //make sure the image is an image
			        if(isset($this->request->data['imagefileupload']['tmp_name']) && $this->request->data['image_method'] == 'upload'){
                        $allowedTypes=array('.jpg','jpeg','.png','.gif');
    					if(!in_array(strtolower(substr($this->request->data['imagefileupload']['name'],-4)),$allowedTypes)){
    					    $this->autoRender=false;
    					    echo '<h3><span style="color:red;">ERROR:</span> The filetype <u>'.substr($this->request->data['imagefileupload']['name'],-4).'</u> is not allowed in the image field.</h3>
                            <p><button type="button" onclick="history.go(-1);">Go Back</button></p>';
    					    exit;
    					}
                    }
			        
			        //save all the field values
			        $thisItem=$this->QuoteLineItems->get($lineItemData['id']);
			        
			        $thisItem->title = $this->request->data['line_item_title'];
                    $thisItem->description = $this->request->data['description'];
                    $thisItem->best_price = $this->request->data['price'];
                    $thisItem->qty = $this->request->data['qty'];
                    $thisItem->room_number = $this->request->data['location'];
                    
                    
                    $thisItem->product_subclass=$this->request->data['product_subclass'];
			        
                    $thisSubClass=$this->ProductSubclasses->get($this->request->data['product_subclass'])->toArray();
                    $thisItem->enable_tally=$thisSubClass['tally'];
                    
                    
                    $thisItem->unit = $this->request->data['unit_type'];
                    

                    if($this->QuoteLineItems->save($thisItem)){
                        //save the meta data changes
                        $ignoreKeys=array('process','product_subclass','imagefileupload','description','line_item_title','customer_id','save_to_library','image_title','image_category','image_tags','fabric_name','fabric_color','libraryimageid');
                        
                        foreach($this->request->data as $postkey => $postdata){
                            if(!in_array($postkey,$ignoreKeys) && $postdata != ''){
                                
                                if($postkey=='fabric_type'){
                                    $postkey='fabrictype';
                                }
                                
                                $this->updateLineItemMeta($lineItemID,$postkey,$postdata);
                                
                            }
                        }
                        
                        
                        $newLineItemMeta=$this->QuoteLineItemMeta->newEntity();
                        $newLineItemMeta->quote_item_id = $newItem->id;
                        $newLineItemMeta->meta_key='lineitemtype';
                        $newLineItemMeta->meta_value='newcatchall';
                        $this->QuoteLineItemMeta->save($newLineItemMeta);
                        
                        
                        if($this->request->data['fabric_type']=="existing"){

							$this->updateLineItemMeta($lineItemID,'fabricid',$this->request->data['fabric_id_with_color']);
							
							$selectedFabric=$this->Fabrics->get($this->request->data['fabric_id_with_color'])->toArray();
							$this->updateLineItemMeta($lineItemID,'fabric_name',$selectedFabric['fabric_name']);
							
                            $this->updateLineItemMeta($lineItemID,'fabric_color',$selectedFabric['color']);
							

						}elseif($this->request->data['fabric_type']=="typein"){

                            $this->updateLineItemMeta($lineItemID,'fabric_name',$this->request->data['fabric_name']);
							
							$this->updateLineItemMeta($lineItemID,'fabric_color',$this->request->data['fabric_color']);

						}/*PPSA -36 start */elseif($this->request->data['fabric_type']=="none"){

                            $this->updateLineItemMeta($lineItemID,'fabricid','0');
        					$this->updateLineItemMeta($lineItemID,'fabric_name','');
        					$this->updateLineItemMeta($lineItemID,'fabric_color','');

						}
	/*PPSA -36 end */
						

						if($this->request->data['image_method'] == 'library'){
						    
						    $this->updateLineItemMeta($lineItemID,'libraryimageid',$this->request->data['libraryimageid']);

							
						}elseif($this->request->data['image_method'] == 'upload'){
							
							//handle the upload, and process whether to save this to library or just do a one-off insert
							$filename=$this->request->data['imagefileupload']['name'];
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
							
							
							move_uploaded_file($this->request->data['imagefileupload']['tmp_name'],$_SERVER['DOCUMENT_ROOT']."/webroot/img/library/".$filename);
							
							$imgLibraryTable=TableRegistry::get('LibraryImages');
							$newImage=$imgLibraryTable->newEntity();
							
							if($this->request->data['save_to_library'] == '1'){
								
								$selectedCat='';
								$allCats=$this->LibraryCategories->find('all')->toArray();
								foreach($allCats as $catEntry){
									if($catEntry['id'] == $this->request->data['image_category']){
										$selectedCat=$catEntry['category_title'];
									}
								}
								
								//insert into image library db with details for future usage
								$newImage->image_title=$this->request->data['image_title'];
								$newImage->categories=$selectedCat;
								$newImage->filename=$filename;
								$newImage->time=time();
								$newImage->added_by=$this->Auth->user('id');
								$newImage->tags=$this->request->data['image_tags'];
								$newImage->status='Active';
							}else{
								//insert into image library db with no details for single usage
								$newImage->image_title=$this->request->data['imagefileupload']['name'];
								$newImage->categories='Misc';
								$newImage->filename=$filename;
								$newImage->time=time();
								$newImage->added_by=$this->Auth->user('id');
								$newImage->status='Single Use';
							}
							
							if($imgLibraryTable->save($newImage)){
								//insert the metadata for libraryimageid
								$this->updateLineItemMeta($lineItemID,'libraryimageid',$newImage->id);
								
								$this->aspectratiofix($newImage->id,600);

							}
							
							
						}

						

						if($this->request->data['com-fabric']=='1'){

							$this->updateLineItemMeta($lineItemID,'com-fabric','1');

						}
						
                        
                        /*PPSA-33 start */
                        	
						if(empty($this->request->data['fabric-cost-per-yard-custom-value'])) {

							$this->updateLineItemMeta($lineItemID,'fabric-cost-per-yard-custom-value','');

						}else {
						  $this->updateLineItemMeta($lineItemID,'fabric-cost-per-yard-custom-value',number_format($this->request->data['fabric-cost-per-yard-custom-value'], 2, '.', ''));  
						}
						
                        /*PPSA-33 end */
                    }
			        /**PPSASSCRUM-29 start */	
					$orderDetails = $this->Orders->find('all',['conditions'=>['quote_id'=>$quoteID]])->first();

					if(isset($orderDetails) && !empty($orderDetails['id'])){

						$this->UpdatetoOrderLineItemTables($quoteID, $thisLineItem['id'],$orderDetails['id'],'orders');
						$this->UpdatetoOrderLineItemTables($quoteID, $thisLineItem['id'],$orderDetails['id'],'work_orders');
						//$this->savetoOrderLineItemTables($newItem->id, $quoteID,$orderDetails['id'], $table='orders' );
						//$this->savetoOrderLineItemTables($newItem->id, $quoteID,$orderDetails['id'], $table='work_orders' );	
					}
				  
					/**PPSASSCRUM-29 end */
			        //update modified time
			        $this->updatequotemodifiedtime($quoteID);
			        
			        //update tier adjustments
			        if($this->recalculatequoteadjustments($quoteID)){
			            
			            //get the new item data for PMI Adjusted for extended price
			            $freshItemData=$this->QuoteLineItems->get($lineItemID);
			            $freshItemData->extended_price = ($freshItemData->pmi_adjusted * floatval($this->request->data['qty']));
			            $this->QuoteLineItems->save($freshItemData);
			            
			            
			            
						$this->Flash->success('Successfully edited Bedding Catchall line item');
						
						$this->logActivity($_SERVER['REQUEST_URI'],'Edited Bedding Catchall line ID '.$lineItemID,$this->Auth->user('id'),json_encode($this->request));
					    
					    //redirect to quote or orderedit view
					    if($thisQuote['status'] == 'editorder'){
            			    return $this->redirect('/orders/editlines/'.$thisQuote['order_id']);
            			}else{
            				return $this->redirect('/quotes/add/'.$quoteID.'/');
            			}
					}
			        
			    break;
			}
			

		}else{
			
			$fabrics=$this->Fabrics->find('all',['conditions'=>['status'=>'Active']])->group('fabric_name')->toArray();
			$this->set('fabrics',$fabrics);
			
			if($lineItemMetas['fabrictype'] == 'existing'){
				$theFabric=$this->Fabrics->get($lineItemMetas['fabricid'])->toArray();
				$this->set('theFabric',$theFabric);
				
				$thisFabricColors=$this->Fabrics->find('all',['conditions' => ['fabric_name LIKE' => $theFabric['fabric_name']]])->toArray();
				$this->set('thisFabricColors',$thisFabricColors);
			}
			
			$vendorsList=array();
			$vendors=$this->Vendors->find('all')->toArray();
			foreach($vendors as $vendor){
				$vendorsList[$vendor['id']]=$vendor['vendor_name'];
			}
			
			$this->set('vendorsList',$vendorsList);
			
			$this->set('quoteID',$quoteID);
			
			if($thisLineItem['lineitemtype'] == 'newcatchall'){
			    $this->autoRender=false;
			    
			    $this->set('mode','edit');
			    
			    
			    $libraryCatsLookup=$this->LibraryCategories->find('all')->toArray();
        		$allCats=array();
        		foreach($libraryCatsLookup as $catEntry){
        			$allCats[$catEntry['id']]=$catEntry['category_title'];
        		}
        
        		$this->set('allLibraryCats',$allCats);
        
        		$libraryimages=$this->LibraryImages->find('all',['conditions'=>['status'=>'Active']])->toArray();
        
        		$this->set('libraryimages',$libraryimages);
			    
			    switch($thisLineItem['product_type']){
			        case 'newcatchall-misc':
			            $subclasses=$this->ProductSubclasses->find('all',['conditions' => ['class_id' => 7], 'order' => ['subclass_name' => 'asc']])->toArray();
                        $this->set('subclasses',$subclasses);
			        break;
			        case 'newcatchall-hardware':
			            $subclasses=$this->ProductSubclasses->find('all',['conditions' => ['class_id' => 1], 'order' => ['subclass_name' => 'asc']])->toArray();
                        $this->set('subclasses',$subclasses);
			        break;
			        //blinds dont need special vars
			        //shades dont need special vars
			        //shutters dont need special vars
			        //hwt misc doesnt need special vars
			        case 'newcatchall-swtmisc':
			            //load Linings
			            $linings=$this->Linings->find('all')->toArray();
					    $this->set('linings',$linings);
			        break;
			        case 'newcatchall-service':
			            $subclasses=$this->ProductSubclasses->find('all',['conditions' => ['class_id' => 6], 'order' => ['subclass_name' => 'asc']])->toArray();
                        $this->set('subclasses',$subclasses);
			        break;
			        case 'newcatchall-valance':
			            //load Linings
			            $linings=$this->Linings->find('all')->toArray();
					    $this->set('linings',$linings);
			        break;
			        case 'newcatchall-drapery':
			            //load Linings
			            $linings=$this->Linings->find('all')->toArray();
					    $this->set('linings',$linings);
			        break;
			        case 'newcatchall-cornice':
			            //load Linings
			            $linings=$this->Linings->find('all')->toArray();
					    $this->set('linings',$linings);
			        break;
			        case 'newcatchall-cubicle':
			            $subclasses=$this->ProductSubclasses->find('all',['conditions' => ['class_id' => 4], 'order' => ['subclass_name' => 'asc']])->toArray();
                        $this->set('subclasses',$subclasses);
			        break;
			        case 'newcatchall-bedding':
			            $subclasses=$this->ProductSubclasses->find('all',['conditions' => ['class_id' => 5], 'order' => ['subclass_name' => 'asc']])->toArray();
                        $this->set('subclasses',$subclasses);
			        break;
			    }
			    
			    
			    $this->render('/Quotes/'.$thisLineItem['product_type']);
			}

		}

	}

	

	public function changequoteproject($quoteID,$projectID){

		

		$this->autoRender=false;

		$quoteTable=TableRegistry::get('Quotes');

		$quoteItem=$quoteTable->get($quoteID);

		$quoteItem->project_id=$projectID;

		if($quoteTable->save($quoteItem)){

			$this->updatequotemodifiedtime($quoteID);

			echo "OK";

		}

		exit;

		

	}

	

	

	

	public function changequotetitle($quoteID){

		$this->autoRender=false;

		$quoteTable=TableRegistry::get('Quotes');

		$quoteItem=$quoteTable->get($quoteID);

		$quoteItem->title=$this->request->data['newtitle'];

		if($quoteTable->save($quoteItem)){
			$this->logActivity($_SERVER['REQUEST_URI'],'Updated quote title for Quote ID# '.$quoteID);
			$this->updatequotemodifiedtime($quoteID);
			echo "OK";
		}

		exit;

	}

	

	public function updatequotemodifiedtime($quoteID){

		$quoteTable=TableRegistry::get('Quotes');

		$quoteItem=$quoteTable->get($quoteID);

		$quoteItem->modified=time();

		$quoteItem->expires=(time()+(intval($this->getSettingValue('quotes_expiration_in_days')) * 86400));

		$quoteTable->save($quoteItem);

	}

	

	

	public function delete($quoteID){
        $thisQuoteData=$this->Quotes->get($quoteID)->toArray();
    
		$this->autoRender=false;

		//find out if this quote has an Order referencing it

		$orderLookup=$this->Orders->find('all',['conditions'=>['quote_id'=>$quoteID]])->toArray();

		if(count($orderLookup) >0){

			$this->Flash->error('You cannot delete a quote that has a corresponding Order');

			return $this->redirect('/quotes/');

		}

		

		if($this->request->data){

			$quoteEntity = $this->Quotes->get($quoteID);

			

			//delete all line items and line item notes

			$lineItems=$this->QuoteLineItems->find('all',['conditions'=>['quote_id'=>$quoteID]])->toArray();

			foreach($lineItems as $lineItem){

				

				

				$lineItemNotes=$this->QuoteLineItemNotes->find('all',['conditions'=>['quote_item_id'=>$lineItem['id']]])->toArray();

				foreach($lineItemNotes as $lineitemnote){

					$this->QuoteLineItemNotes->delete($lineitemnote);

				}

				

				$lineItemMeta=$this->QuoteLineItemMeta->find('all',['conditions'=>['quote_item_id'=>$lineItem['id']]])->toArray();

				foreach($lineItemMeta as $lineitemmeta){

					$this->QuoteLineItemMeta->delete($lineitemmeta);

				}

				

				$this->QuoteLineItems->delete($lineItem);

				

			}

			

			

			$result = $this->Quotes->delete($quoteEntity);

			if($result){

				$this->Flash->success('Successfully deleted selected Quote');

                $this->logActivity($_SERVER['REQUEST_URI'],"Deleted quote ".$thisQuoteData['quote_number']);

				return $this->redirect('/quotes/');

			}

		}else{

			$this->render('/Quotes/deleteconfirm');

		}

	}

	

	

	public function getcustomercontacts($customerID){
		$this->autoRender=false;
		$contacts=$this->CustomerContacts->find('all',['conditions'=>['customer_id'=>$customerID,'status !=' => 'Deleted'],'order'=>['first_name'=>'asc','last_name'=>'asc']])->toArray();
		echo "<select name=\"contact_id\"><option value>--Select A Contact--</option>";
		foreach($contacts as $contact){
			echo "<option value=\"".$contact['id']."\">".$contact['first_name'].' '.$contact['last_name']."</option>";
		}
		echo "</select>";
		exit;
	}

	

	public function getfabriccolors($fabricname,$type,$customerID,$selectedColorID=false,$lineID=false){
		$this->autoRender=false;
		switch($type){
			case "cubicle-curtains":
				$colors=$this->Fabrics->find('all',['conditions'=>['fabric_name'=>$fabricname,'OR'=>['use_in_sc'=>1,'use_in_cc'=>1],'is_hci_fabric'=>1,'status'=>'Active'],'order'=>['color'=>'asc']])->toArray();
			break;
			case "bedspreads":
				$colors=$this->Fabrics->find('all',['conditions'=>['fabric_name'=>$fabricname,'use_in_bs'=>1,'is_hci_fabric'=>1,'status'=>'Active'],'order'=>['color'=>'asc']])->toArray();
			break;
			case "window-treatment":
				$colors=$this->Fabrics->find('all',['conditions'=>['fabric_name'=>$fabricname,'use_in_window'=>1,'is_hci_fabric'=>1,'status'=>'Active'],'order'=>['color'=>'asc']])->toArray();
			break;
			default:
				$colors=$this->Fabrics->find('all',['conditions'=>['fabric_name'=>$fabricname,'status'=>'Active'],'order'=>['color'=>'asc']])->toArray();
			break;
		}

		

		echo "<label>Select a Color</label><select name=\"fabric_id_with_color\" id=\"fabricidwithcolor\"><option value selected disabled>--Select A Color--</option>";

		foreach($colors as $color){
			if($fabricAlias=$this->getFabricAlias($color['id'],$customerID)){
				$hasAlias=1;
			}else{
				$hasAlias=0;
			}

			echo "<option value=\"".$color['id']."\" data-vendorid=\"".$color['vendors_id']."\"";
			if($hasAlias==1){
				echo " data-hasalias=\"1\"";
			}else{
				echo " data-hasalias=\"0\"";
			}
			echo ">";
			echo $color['color'];

			if($hasAlias==1){
				echo " (".$fabricAlias['fabric_name'].' - '.$fabricAlias['color'].')';
			}
			echo "</option>";
		}
		echo "</select>";

		if($type != "custom"){
			echo "<script>
			$('select#fabricidwithcolor,select#meshoption,input#quilted,select#mattresssize').change(function(){
				if($('select#fabricidwithcolor option:selected').attr('data-hasalias') == '1'){
					$('#usealiaswrap').show();
				}else{
					$('#usealiaswrap').hide();
				}
				$('#priceeachvalue').html('---');
				";

				if($type=='cubicle-curtains'){
					echo "if($('#meshoption').val() == 'No Mesh'){
						var thirdparameter=0;
					}else{
						var thirdparameter=1;
					}
					";

					if($selectedColorID){
						echo " var fourthparameter='fromedit';
						var fifthparameter='".$lineID."';
						";
					}else{
						echo "var fourthparameter='';
						var fifthparameter='';
						";
					}

				}elseif($type=='bedspreads'){
					echo "if($('#quilted').is(':checked')){
							var thirdparameter=1;
						}else{
							var thirdparameter=0;
						}
						var fourthparameter=$('#mattresssize').val();
						var fifthparameter=".$customerID.";
					";
				}elseif($type=='window-treatment'){
					echo "if($('#haswelts').is(':checked')){
						var thirdparameter=1;
					}else{
						var thirdparameter=0;
					}
					var fourthparameter=encodeURIComponent($('#liningoption').val());
					var fifthparameter=$('#pairorpanel').val();
					";

				}

			echo "$.get('/quotes/";
			if($type=="cubicle-curtains"){
				echo "getccsizeoptions";
			}elseif($type=="bedspreads"){
				echo "getbssizeoptions";
			}elseif($type=='window-treatment'){
				echo "getwtsizeoptions/'+$('#wttype').val()+'";
			}
			echo "/'+$('#fabricid').val()+'/'+$('#fabricidwithcolor').val()+'/'+thirdparameter+'/'+fourthparameter+'/'+fifthparameter,function(data){
					";
			if($type=="cubicle-curtains"){
				echo "$('#widthselection').html(data);";
			}elseif($type=="bedspreads"){
				echo "$('#sizeselection').html(data);";
			}elseif($type=='window-treatment'){
				echo "$('#widthselection').html(data);";
			}
			echo "
				});
			});
			</script>";
		}else{
			echo "<script>
			$('#fabricidwithcolor').change(function(){
				if($('select#fabricidwithcolor option:selected').attr('data-hasalias') == '1'){
					$('#usealiaswrap').show();
				}else{
					$('#usealiaswrap').hide();
				}
				$('select[name=vendors_id]').val($('#fabricidwithcolor').find('option:selected').attr('data-vendorid'));
				//console.log('Vendor changed to '+$('#fabricidwithcolor').find('option:selected').attr('data-vendorid'));
			});";
		}
		exit;
	}

	public function getbssizeoptions($fabricname,$fabricid,$quilted=0,$mattresssize='36"',$customerID){
		$this->autoRender=false;
		$thisFabric=$this->Fabrics->get($fabricid)->toArray();		
		$conditions=['fabric_name'=>urldecode($fabricname),'quilted'=>$quilted,'mattress_size'=>$mattresssize,'available_colors LIKE' => '%"'.str_replace(" ","_",urldecode($thisFabric['color'])).'"%','status'=>'Active'];
		$findBS=$this->Bedspreads->find('all',['conditions'=>$conditions])->toArray();

		if(count($findBS) == 0){
			echo "<script>$('#pricerow').html('<span style=\"color:red;font-weight:bold;\">There are no bedspreads in the price book with these parameters.</span><Br><br><br><Br><Br>'); $('#qtyline').hide(); $('#addsimpleproducttoquote').prop('disabled',true);</script>";
		}else{
			foreach($findBS as $bs){
				$sizes=$this->BsDataMap->find('all',['conditions'=>['bs_id'=>$bs['id']]])->toArray();
				echo "<label>Select a Size</label><select name=\"bssize\" id=\"bssize\"><option value disabled selected>--Select A Size--</option>";
				foreach($sizes as $size){
					$thisSize=$this->BedspreadSizes->get($size['size_id'])->toArray();
					//see if this customer has special pricing
					$specialpricing=0;
					//see if this customer has a pricing override

					if($overridePrice=$this->customerHasPriceListOverride($customerID,'bs',$bs['id'],$size['size_id'],$thisFabric['color'])){
						$filteredPrice = number_format($overridePrice,2,'.',',');
						$specialpricing=1;
					}else{
						$filteredPrice=$size['price'];
					}
					echo "<option value=\"".$size['size_id']."\" data-price=\"".$filteredPrice."\"";
					if($specialpricing==1){
						echo " data-specialpricing=\"1\"";
					}else{
						echo " data-specialpricing=\"0\"";
					}

					echo " data-bsid=\"".$bs['id']."\" data-weight=\"".$size['weight']."\" data-diff=\"".$size['difficulty']."\" data-yards=\"".$size['yards']."\" data-sizeid=\"".$size['size_id']."\" data-topwidthsl=\"".$size['top_width_l']."\" data-dropwidthsl=\"".$size['drop_width_l']."\" data-quiltpattern=\"".$size['quilting_pattern']."\" data-topcutsw=\"".$size['top_cuts_w']."\" data-dropcutsw=\"".$size['drop_cuts_w']."\" data-layout=\"".$size['layout']."\" data-repeat=\"".$size['vertical_repeat']."\">".$thisSize['title']."</option>";
				}
				echo "</select>";
			}

			echo "<script>
			$('#pricerow').html('<label>Price each:</label><span id=\"priceeachvalue\">----</span>');
			$('#qtyline').show();
			$('#addsimpleproducttoquote').prop('disabled',false);
			$('#bssize').change(function(){
				if($('option:selected',this).attr('data-specialpricing') == '1'){
					$('#priceeachvalue').html('$'+parseFloat($('option:selected',this).attr('data-price')).toFixed(2)+' <span style=\"color:red;font-weight:bold;font-size:14px; font-style:italic; padding:0 0 0 30px;\">SPECIAL PRICING</span>');
				}else{
					$('#priceeachvalue').html('$'+parseFloat($('option:selected',this).attr('data-price')).toFixed(2));
				}
				
				$('#price').val(parseFloat($('option:selected',this).attr('data-price')).toFixed(2));
				$('#weight').val(parseFloat($('option:selected',this).attr('data-weight')).toFixed(2));
				$('#difficulty').val(parseFloat($('option:selected',this).attr('data-diff')).toFixed(2));
				$('#yards').val(parseFloat($('option:selected',this).attr('data-yards')).toFixed(2));
				$('#bsid').val($('option:selected',this).attr('data-bsid'));
				$('#sizeid').val($('option:selected',this).attr('data-sizeid'));
				$('#topwidthsl').val($('option:selected',this).attr('data-topwidthsl'));
				$('#dropwidthsl').val($('option:selected',this).attr('data-dropwidthsl'));
				$('#quiltpattern').val($('option:selected',this).attr('data-quiltpattern'));
				$('#topcutsw').val($('option:selected',this).attr('data-topcutsw'));
				$('#dropcutsw').val($('option:selected',this).attr('data-dropcutsw'));
				$('#layout').val($('option:selected',this).attr('data-layout'));
				$('#repeat').val($('option:selected',this).attr('data-repeat'));
				$('#specialpricing').val($('option:selected',this).attr('data-specialpricing'));
			});
			</script>";
		}
		exit;
	}

	public function getwtsizeoptions($wttype,$fabricname,$fabricid,$haswelts=0,$lining=false,$pairorpanel=false){

		$this->autoRender=false;

		$thisFabric=$this->Fabrics->get($fabricid)->toArray();

		

		$conditions=['wt_type'=>$wttype,'fabric_name'=>$fabricname,'available_colors LIKE' => '%"'.$thisFabric['color'].'"%','status'=>'Active'];

		if($haswelts==1){

			$conditions += array('has_welt'=>1);

		}else{

			$conditions += array('has_welt'=>0);

		}

		

		if($lining && strtolower($lining) != 'null'){

			$conditions += array('lining_option'=>$lining);

		}

		

		$findWT=$this->WindowTreatments->find('all',['conditions'=>$conditions])->toArray();

		foreach($findWT as $wt){

			//$sizes=json_decode($cc['available_sizes'],true);

			$availablesizes=$this->WtDataMap->find('all',['conditions'=>['wt_id'=>$wt['id']],'order'=>['size_id'=>'asc']])->toArray();

			$usedWidths=array();

			echo "<label>Select Face Width</label><select name=\"wtwidth\" id=\"wtwidth\"><option value disabled selected>--Select Face Width--</option>";

			foreach($availablesizes as $availablesize){

				$thisSize=$this->WindowTreatmentSizes->get($availablesize['size_id'])->toArray();

				if(!in_array($thisSize['width'],$usedWidths)){

					echo "<option value=\"".round($thisSize['width'],0)."\" data-wtid=\"".$wt['id']."\">".round($thisSize['width'],0)."\"</option>";

					$usedWidths[]=$thisSize['width'];

				}

				

			}

			echo "</select>";

		}

		

		echo "<script>

		function lookupprice(wttype,fabricid,width,length,welts,lining,pairorpanel){

			$.fancybox.showLoading();

			

			var otherdata='';

			if(wttype=='Pinch Pleated Drapery'){

				otherdata='/'+lining+'/'+pairorpanel;

			}

			if($('input#customer_id').length && $('#customer_id').val() > 0){
				otherdata += '?lookupcustomerid='+$('#customer_id').val();
			}

			$.get('/quotes/lookuppricelist/'+$('#quoteID').val()+'/wt/'+fabricid+'/'+width+'/'+length+'/'+welts+'/'+wttype+otherdata,function(data){

				var resultData=JSON.parse(data);

				

				if(resultData.specialpricing==1){

				    $('#priceeachvalue').html('$'+parseFloat(resultData.price).toFixed(2)+' <span style=\"color:red;font-weight:bold;font-size:14px; font-style:italic; padding:0 0 0 30px;\">SPECIAL PRICING</span>');

				}else{

				    $('#priceeachvalue').html('$'+parseFloat(resultData.price).toFixed(2));

				}

				$('#price').val(parseFloat(resultData.price).toFixed(2));

				$('#specialpricing').val(resultData.specialpricing);

				

				$('#weight').val(resultData.weight);

				$('#difficulty').val(resultData.difficulty);

				$('#labor-lf').val(resultData.laborlf);

				$('#yards').val(resultData.yards);

				$('#wtid').val(resultData.wtid);

				$('#sizeid').val(resultData.sizeID);

				$.fancybox.hideLoading();

			});

		}

		

		$('#wtwidth').change(function(){

			if($('#haswelts').is(':checked')){

				var haswelts=1;

			}else{

				var haswelts=0;

			}

			if($('#wtwidth').val() != '0' && $('#wtwidth').val() != '' && $('#length').val() != ''){

				lookupprice($('#wttype').val(),$('#fabricidwithcolor').val(),$('#wtwidth').val(),$('#length').val(),haswelts);

			}

		});

		

		$('#liningoption').change(function(){

			if($('#haswelts').is(':checked')){

				var haswelts=1;

			}else{

				var haswelts=0;

			}

			if($('#wtwidth').val() != '0' && $('#wtwidth').val() != '' && $('#length').val() != ''){

				lookupprice($('#wttype').val(),$('#fabricidwithcolor').val(),$('#wtwidth').val(),$('#length').val(),haswelts,$('#liningoption').val(),$('#pairorpanel').val());

			}

		});

		$('#pairorpanel').change(function(){

			if($('#haswelts').is(':checked')){

				var haswelts=1;

			}else{

				var haswelts=0;

			}

			if($('#wtwidth').val() != '0' && $('#wtwidth').val() != '' && $('#length').val() != ''){

				lookupprice($('#wttype').val(),$('#fabricidwithcolor').val(),$('#wtwidth').val(),$('#length').val(),haswelts,$('#liningoption').val(),$('#pairorpanel').val());

			}

		});

			

			

		$('#length').keyup($.debounce( 650, function(){

			if($('#haswelts').is(':checked')){

				var haswelts=1;

			}else{

				var haswelts=0;

			}

			if($('#wtwidth').val() != '0' && $('#wtwidth').val() != '' && $('#length').val() != ''){

				lookupprice($('#wttype').val(),$('#fabricidwithcolor').val(),$('#wtwidth').val(),$('#length').val(),haswelts,$('#liningoption').val(),$('#pairorpanel').val());

			}

			if($('#wttype').val() == 'Shaped Cornice'){

				$('#fl-short').val((parseFloat($('#length').val())-4));

			}

		}));

		$('#length').change(function(){

			if($('#haswelts').is(':checked')){

				var haswelts=1;

			}else{

				var haswelts=0;

			}

			if($('#wtwidth').val() != '0' && $('#wtwidth').val() != '' && $('#length').val() != ''){

				lookupprice($('#wttype').val(),$('#fabricidwithcolor').val(),$('#wtwidth').val(),$('#length').val(),haswelts,$('#liningoption').val(),$('#pairorpanel').val());

			}

			if($('#wttype').val() == 'Shaped Cornice'){

				$('#fl-short').val((parseFloat($('#length').val())-4));

			}

		});

		$('#haswelts').change(function(){

			if($('#haswelts').is(':checked')){

				var haswelts=1;

			}else{

				var haswelts=0;

			}

			if($('#wtwidth').val() != '0' && $('#wtwidth').val() != '' && $('#length').val() != ''){

				lookupprice($('#wttype').val(),$('#fabricidwithcolor').val(),$('#wtwidth').val(),$('#length').val(),haswelts,$('#liningoption').val(),$('#pairorpanel').val());

			}

		});		

		$('#fabricid').change(function(){

			if($('#haswelts').is(':checked')){

				var haswelts=1;

			}else{

				var haswelts=0;

			}

			if($('#wtwidth').val() != '0' && $('#wtwidth').val() != '' && $('#length').val() != ''){

				lookupprice($('#wttype').val(),$('#fabricidwithcolor').val(),$('#wtwidth').val(),$('#length').val(),haswelts,$('#liningoption').val(),$('#pairorpanel').val());

			}

		});

		$('#fabricidwithcolor').change(function(){

			if($('#haswelts').is(':checked')){

				var haswelts=1;

			}else{

				var haswelts=0;

			}

			if($('#wtwidth').val() != '0' && $('#wtwidth').val() != '' && $('#length').val() != ''){

				lookupprice($('#wttype').val(),$('#fabricidwithcolor').val(),$('#wtwidth').val(),$('#length').val(),haswelts,$('#liningoption').val(),$('#pairorpanel').val());

			}

		});

		</script>";

		exit;

	}

	

	public function getccsizeoptions($fabricname,$fabricid,$hasmesh=1,$fourthParam=false,$fifthParam=false){

		$this->autoRender=false;

		$thisFabric=$this->Fabrics->get($fabricid)->toArray();

		

		$conditions=['fabric_name'=>urldecode($fabricname),'available_colors LIKE' => '%"'.str_replace(" ","_",$thisFabric['color']).'"%','status'=>'Active'];

		//print_r($conditions);exit;

		

		if($hasmesh==1){

			$conditions += array('has_mesh'=>1);

		}else{

			$conditions += array('has_mesh'=>0);

		}

		

		$findCC=$this->CubicleCurtains->find('all',['conditions'=>$conditions])->toArray();

		if(count($findCC) == 0 && $hasmesh==0){

			//there's no grid for this NO MESH... do the 20% stuff

			$findCC=$this->CubicleCurtains->find('all',['conditions'=>['fabric_name'=>$fabricname,'available_colors LIKE' => '%"'.$thisFabric['color'].'"%','status'=>'Active']])->toArray();

		}

		foreach($findCC as $cc){

			//$sizes=json_decode($cc['available_sizes'],true);

			$availablesizes=$this->CcDataMap->find('all',['conditions'=>['cc_id'=>$cc['id']],'order'=>['size_id'=>'asc']])->toArray();

			$usedWidths=array();

			echo "<label>Select a Cut Width</label><select name=\"ccwidth\" id=\"ccwidth\"><option value disabled selected>--Select A Cut Width--</option>";

			foreach($availablesizes as $availablesize){

				$thisSize=$this->CubicleCurtainSizes->get($availablesize['size_id'])->toArray();

				if(!in_array($thisSize['width'],$usedWidths)){

					echo "<option value=\"".round($thisSize['width'],0)."\" data-ccid=\"".$cc['id']."\"";

					if($fourthParam=='fromedit'){

						  

					}

					echo ">".round($thisSize['width'],0)."\"</option>";

					$usedWidths[]=$thisSize['width'];

				}

				

			}

			echo "</select>";

		}

		

		echo "<script>

		function lookupprice(fabricid,width,length,mesh){

			$.fancybox.showLoading();

			var otherdata='';
			if($('input#customer_id').length && $('#customer_id').val() > 0){
				otherdata += '?lookupcustomerid='+$('#customer_id').val();
			}

			$.get('/quotes/lookuppricelist/'+$('#quoteID').val()+'/cc/'+fabricid+'/'+width+'/'+length+'/'+mesh+otherdata,function(data){

				var resultData=JSON.parse(data);

				

				if(resultData.specialpricing==1){

				    $('#priceeachvalue').html('$'+parseFloat(resultData.price).toFixed(2)+' <span style=\"color:red;font-weight:bold;font-size:14px; font-style:italic; padding:0 0 0 30px;\">SPECIAL PRICING</span>');

				}else{

				    $('#priceeachvalue').html('$'+parseFloat(resultData.price).toFixed(2));

				}

				

				$('#price').val(parseFloat(resultData.price).toFixed(2));

				$('#specialpricing').val(resultData.specialpricing);

				$('#weight').val(resultData.weight);

				$('#difficulty').val(resultData.difficulty);

				$('#labor-lf').val(resultData.labor_lf);

				$('#yards').val(resultData.yards);

				$('#ccid').val(resultData.ccid);

				$('#sizeid').val(resultData.sizeID);

				$.fancybox.hideLoading();

			});

		}

		

		$('#ccwidth').change(function(){

			$('#finishwidthselection').html('<div class=\"input number\"><label>Finished Width (Optional)</label><input type=\"number\" name=\"finished_width\" min=\"1\" placeholder=\"Optional\" /></div>');

			if($('#ccwidth').val() != '0' && $('#ccwidth').val() != '' && $('#length').val() != ''){

				lookupprice($('#fabricidwithcolor').val(),$('#ccwidth').val(),$('#length').val(),$('#meshoption').val());

			}

		});

			

		$('#length').keyup($.debounce( 650, function(){

			if($('#ccwidth').val() != '0' && $('#ccwidth').val() != '' && $('#length').val() != ''){

				lookupprice($('#fabricidwithcolor').val(),$('#ccwidth').val(),$('#length').val(),$('#meshoption').val());

			}

		}));

		$('#length').change(function(){

			if($('#ccwidth').val() != '0' && $('#ccwidth').val() != '' && $('#length').val() != ''){

				lookupprice($('#fabricidwithcolor').val(),$('#ccwidth').val(),$('#length').val(),$('#meshoption').val());

			}

		});

		$('#meshoption').change(function(){

			if($('#ccwidth').val() != '0' && $('#ccwidth').val() != '' && $('#length').val() != ''){

				lookupprice($('#fabricidwithcolor').val(),$('#ccwidth').val(),$('#length').val(),$('#meshoption').val());

			}

		});		

		$('#fabricid').change(function(){

			if($('#ccwidth').val() != '0' && $('#ccwidth').val() != '' && $('#length').val() != ''){

				lookupprice($('#fabricidwithcolor').val(),$('#ccwidth').val(),$('#length').val(),$('#meshoption').val());

			}

		});

		$('#fabricidwithcolor').change(function(){

			if($('#ccwidth').val() != '0' && $('#ccwidth').val() != '' && $('#length').val() != ''){

				lookupprice($('#fabricidwithcolor').val(),$('#ccwidth').val(),$('#length').val(),$('#meshoption').val());

			}

		});

		</script>";

		exit;		

	}

	

	

	public function getproductselectlist($product,$step=1,$lineid=false){
		$fabricImages=array();
		$allfabrics=$this->Fabrics->find('all')->toArray();
		foreach($allfabrics as $fabric){
			$fabricImages[$fabric['id']]=$fabric['image_file'];
		}

		if($step=='fromedit' && $lineid){
			$thisLineItem=$this->QuoteLineItems->get($lineid)->toArray();
			$thisQuote=$this->Quotes->get($thisLineItem['quote_id'])->toArray();
			$thisLineMeta=array();
			$itemmetas=$this->QuoteLineItemMeta->find('all',['conditions'=>['quote_item_id'=>$lineid]])->toArray();
			foreach($itemmetas as $itemmeta){
				$thisLineMeta[$itemmeta['meta_key']]=$itemmeta['meta_value'];
			}
		}

		$settings=$this->getsettingsarray();
		$this->autoRender=false;
		switch($product){
			case "track-system":
				echo "<p><label>Track System / Component</label><select onchange=\"changetrackitem()\" name=\"system_id\"><option value=\"0\" selected disabled>--Select System--</option><optgroup label=\"Systems\">";
				$allitems=$this->TrackSystems->find('all',['conditions'=>['system_or_component'=>'system']])->toArray();
				foreach($allitems as $item){
					echo "<option value=\"".$item['id']."\" data-price=\"".$item['price']."\" data-unitlabel=\"";
					if($item['unit'] == 'plf'){
						echo "LINEAR FEET";
					}else{
						echo "QTY";
					}
					echo "\"";
					if($step=='fromedit'){
					    //see if this option is selected for Edit
					    if($item['id'] == $thisLineItem['product_id']){
					        echo " selected=\"selected\"";
					    }
					}
					echo ">".$item['title']."</option>\n";
				}
				echo "</optgroup><optgroup label=\"Components\">";
				$allitems=$this->TrackSystems->find('all',['conditions'=>['system_or_component'=>'component']])->toArray();
				foreach($allitems as $item){
					echo "<option value=\"".$item['id']."\" data-price=\"".$item['price']."\" data-unitlabel=\"";
					if($item['unit'] == 'plf'){
						echo "LINEAR FEET";
					}else{
						echo "QTY";
					}
					echo "\"";
					if($step=='fromedit'){
					    //see if this option is selected for Edit
					    if($item['id'] == $thisLineItem['product_id']){
					        echo " selected=\"selected\"";
					    }
					}
					echo ">".$item['title']."</option>\n";
				}
				echo "</optgroup></select></p>";
				echo "<p><label>Notes/Line Item Description:</label><textarea name=\"description\" id=\"description\" placeholder=\"Description of this Track System/Component line item (visible to customer)\">";
				echo $thisLineItem['description'];
				echo "</textarea></p>";
			break;
			case "window-treatment":
				$fabricsfind=$this->Fabrics->find('all',['conditions'=>['status'=>'Active',['OR'=>['use_in_window'=>1]]],'order'=>['fabric_name'=>'asc']])->group('fabric_name')->toArray();
				$wttypes=$this->WindowTreatments->find('all',['conditions'=>['status'=>'Active'],'order'=>['wt_type'=>'ASC']])->group('wt_type')->toArray();
				if($step==1 || $step=='fromedit'){
					echo "<p id=\"typeselection\"><label>Select a Window Treatment Type</label><select id=\"wttype\" onchange=\"toggleweltfield(this.value)\"><option value=\"0\" selected disabled>--Select Window Treatment Type--</option>";
					foreach($wttypes as $type){
						echo "<option value=\"".$type['wt_type']."\"";
						if($step=='fromedit' && $lineid){
							if($thisLineMeta['wttype'] == $type['wt_type']){
								echo " selected=\"selected\"";
							}
						}
						echo ">".$type['wt_type']."</option>";
					}	
					echo "</select></p>";
					echo "<p id=\"weltsselection\" style=\"display:";
					if($step=='fromedit' && ($thisLineMeta['wttype'] == 'Shaped Cornice' || $thisLineMeta['wttype']=='Straight Cornice')){
					    echo "block;";
					}else{
					    echo "none;";
					}
					echo "\"><label><input type=\"checkbox\" name=\"haswelts\" id=\"haswelts\" value=\"1\"";
					if($step=='fromedit' && (isset($thisLineMeta['welt-bottom']) || isset($thisLineMeta['welt-top']))){ echo " checked=\"checked\""; } echo " /> Welts (Top and Bottom)</label></p>";
					echo "<p id=\"liningoptions\" style=\"display:";
					if($step=='fromedit'){ echo "block;"; }else{ echo "none;"; } echo "\"><label>Select Lining Option</label><select id=\"liningoption\"><option value=\"0\"";
					if($step != 'fromedit'){ echo " selected"; }
					echo "disabled>--Select A Lining--</option><option value=\"FR Lining\"";
					if($step=='fromedit' && $thisLineItem['lining_option']=='FR Lining'){ echo " selected=\"selected\""; }
					echo ">FR Lining</option><option value=\"BO Lining\"";
					if($step=='fromedit' && $thisLineItem['lining_option']=='BO Lining'){ echo " selected=\"selected\""; }
					echo ">BO Lining</option><option value=\"Unlined\"";
					if($step=='fromedit' && $thisLineItem['lining_option']=='Unlined'){ echo " selected=\"selected\""; }
					echo ">Unlined</option></select></p>";
					echo "<p id=\"pairpaneloptions\" style=\"display:";
					if($step=='fromedit' && $thisLineMeta['wttype'] == 'Pinch Pleated Drapery'){
					    echo "block;";
					}else{
					    echo "none;";
					}
					echo "\"><label>Select Pair or Panel</label><select id=\"pairorpanel\"><option value=\"0\"";
					if($step != 'fromedit'){ echo " selected"; } echo " disabled>--Select Pair or Panel--</option><option value=\"pair\"";
					if($step=='fromedit' && $thisLineMeta['unit-of-measure'] == 'pair'){ echo " selected=\"selected\""; }
					echo ">Pair</option><option value=\"panel\"";
					if($step=='fromedit' && $thisLineMeta['unit-of-measure'] == 'panel'){ echo" selected=\"selected\""; }
					echo ">Panel</option></select></p>";
					echo "<p id=\"fabricselection\"><label>Select a Fabric</label><select id=\"fabricid\"><option value=\"0\" selected disabled>--Select A Fabric--</option>";
					foreach($fabricsfind as $fabric){
						//check to see if there are CC's in the price list with this fabric at all
						$lookforccs=$this->WindowTreatments->find('all',['conditions'=>['fabric_name'=>$fabric['fabric_name']]])->toArray();
						if(count($lookforccs) > 0){
							echo "<option value=\"".$fabric['fabric_name']."\"";
							if($step=='fromedit' && $lineid){
								if($fabric['fabric_name'] == $thisLineMeta['fabric_name']){
									echo " selected=\"selected\"";
								}
							}
							echo ">".$fabric['fabric_name']."</option>";
						}
					}

					echo "</select></p>
					<p id=\"colorselection\"><label>Select a Color Option</label><select id=\"fabricidwithcolor\"><option value=\"0\"";
					if($step != 'fromedit'){
					echo " selected";
					}
					echo " disabled>--Select a Color--</option>";
					
					if($step=='fromedit'){
					    $colors=$this->Fabrics->find('all',['conditions' => ['fabric_name' => $thisLineMeta['fabric_name']], 'order' => ['color'=>'asc']])->toArray();
					    foreach($colors as $color){
					        if($fabricAlias=$this->getFabricAlias($color['id'],$thisQuote['customer_id'])){
                				$hasAlias=1;
                			}else{
                				$hasAlias=0;
                			}
                
                			echo "<option value=\"".$color['id']."\" data-vendorid=\"".$color['vendors_id']."\"";
                			if($hasAlias==1){
                				echo " data-hasalias=\"1\"";
                				
                				if($color['id'] == $thisLineMeta['fabricid']){
                				    echo " selected=\"selected\"";
                				}
                			}else{
                				echo " data-hasalias=\"0\"";
                				
                				
                				if($color['id'] == $thisLineMeta['fabricid']){
                				    echo " selected=\"selected\"";
                				}
                			}
                			
                			
                			echo ">";
                			echo $color['color'];
                
                			if($hasAlias==1){
                				echo " (".$fabricAlias['fabric_name'].' - '.$fabricAlias['color'].')';
                			}
                			echo "</option>";
					    }
					}

					echo "</select></p>
					<p id=\"usealiaswrap\" style=\"display:";
					if($step=='fromedit' && $hasAlias){ echo "block;"; }else{ echo "none;"; } echo "\"><label><input type=\"checkbox\" name=\"usealias\" id=\"usealias\" checked=\"checked\" /> Use Alias</label></p>
					
					<p id=\"widthselection\"><label>Select Face Width</label><select id=\"wtwidth\" name=\"wtwidth\"><option value=\"0\"";
					if($step != 'fromedit'){
					    echo " selected";
					}
					echo " disabled>--Select Face Width--</option>";
					
					if($step=='fromedit'){
					    $thisFabric=$this->Fabrics->get($thisLineMeta['fabricid'])->toArray();
					    
					    $wtconditions=['wt_type'=>$thisLineMeta['wttype'],'fabric_name'=>$thisFabric['fabric_name'],'available_colors LIKE' => '%"'.$thisFabric['color'].'"%','status'=>'Active'];

                		if(preg_match("#Cornice#i",$thisLineMeta['wttype'])){
                		    if((isset($thisLineMeta['welt-top']) && $thisLineMeta['welt-top'] == '1') || (isset($thisLineMeta['welt-bottom']) && $thisLineMeta['welt-bottom'] == '1')){
                			    $wtconditions += array('has_welt'=>1);
                		    }else{
                			    $wtconditions += array('has_welt'=>0);
                		    }
                		}else{
                		    $wtconditions += array('has_welt'=>0);
                		}
                
                		//echo "\n<!--\n\n"; print_r($wtconditions); echo "\n\n-->\n";
                        
                		$findWT=$this->WindowTreatments->find('all',['conditions'=>$wtconditions])->toArray();
                
                		foreach($findWT as $wt){
                
                			$availablesizes=$this->WtDataMap->find('all',['conditions'=>['wt_id'=>$thisLineItem['product_id']],'order'=>['size_id'=>'asc']])->toArray();
                			$usedWidths=array();
                			foreach($availablesizes as $availablesize){
                				$thisSize=$this->WindowTreatmentSizes->get($availablesize['size_id'])->toArray();
                
                				if(!in_array($thisSize['width'],$usedWidths)){
                					echo "<option value=\"".round($thisSize['width'],0)."\" data-wtid=\"".$wt['id']."\"";
                					if($thisLineMeta['wttype'] == 'Pinch Pleated Drapery'){
                					    if($thisSize['width'] == $thisLineMeta['rod-width']){
                					        echo " selected=\"selected\"";
                					    }
                					}else{
                					    if($thisSize['width'] == $thisLineMeta['width']){
                					        echo " selected=\"selected\""; 
                					    }
                					}
                					echo ">".round($thisSize['width'],0)."\"</option>";
                					$usedWidths[]=$thisSize['width'];
                				}
                			}

                		}
					}
                    
					echo "</select></p>
					<p id=\"lengthentry\"><label>Enter a Finished Length</label><input type=\"number\" name=\"length\" min=\"1\" id=\"length\"";
					if($step=='fromedit' && $lineid){
						echo " value=\"".$thisLineMeta['length']."\"";
					}
					echo " /></p>
					<p id=\"flshortwrap\"";
					if($step=='fromedit' && $thisLineMeta['wttype'] == 'Shaped Cornice'){ 
					    echo " style=\"display:block;\""; 
					}else{ 
					    echo " style=\"display:none;\""; 
					} 
					echo "><label>Short Point</label><input type=\"number\" min=\"0\" name=\"fl-short\" id=\"fl-short\"";
					if($step=='fromedit' && $lineid){
						echo " value=\"".$thisLineMeta['fl-short']."\"";
					}
					echo " /></p>";
					echo "<p id=\"pricerow\"><label>Price each</label><span id=\"priceeachvalue\">";
					if($step=='fromedit'){
					    echo '$'.$thisLineItem['best_price'];
					}else{
					    echo "----";
					}
					echo "</span></p>";
					echo "<input type=\"hidden\" name=\"weight\" id=\"weight\"";
					if($step=='fromedit'){ 
					    echo " value=\"".$thisLineMeta['wt_calculated_weight']."\""; 
					}
					echo " />
					<input type=\"hidden\" name=\"difficulty\" id=\"difficulty\"";
					if($step=='fromedit'){ 
					    echo " value=\"".$thisLineMeta['difficulty-rating']."\"";
					}
					echo " />
					<input type=\"hidden\" name=\"yards\" id=\"yards\"";
					if($step=='fromedit'){
					    echo " value=\"".$thisLineMeta['yds-per-unit']."\"";
					}
					echo " />
					<input type=\"hidden\" name=\"labor_lf\" id=\"labor-lf\"";
					if($step=='fromedit'){
					    echo " value=\"".$thisLineMeta['labor-widths']."\"";
					}
					echo " />
					<input type=\"hidden\" name=\"price\" id=\"price\"";
					if($step=='fromedit'){
					    echo " value=\"".$thisLineMeta['price']."\"";
					}
					echo " />
					<input type=\"hidden\" name=\"specialpricing\" id=\"specialpricing\"";
					if($step=='fromedit'){
					    echo " value=\"".$thisLineMeta['specialpricing']."\"";
					}else{
					    echo " value=\"0\"";
					}
					echo " />
					<input type=\"hidden\" name=\"wtid\" id=\"wtid\"";
					if($step=='fromedit'){
					    echo " value=\"".$thisLineItem['product_id']."\"";
					}
					echo " />";
					echo "<input type=\"hidden\" name=\"sizeid\" id=\"sizeid\"";
					if($step=='fromedit'){
					    
					    $db = ConnectionManager::get('default');
					    
					    if($thisLineMeta['wttype'] == 'Pinch Pleated Drapery'){
					        $thisWidthVal=$thisLineMeta['rod-width'];
					    }else{
					        $thisWidthVal=$thisLineMeta['width'];
					    }
					    
					    //look up the size that fits width/rodwidth and finished legnth  meta
					    $query="SELECT a.price,a.weight,a.yards,a.difficulty,a.labor_lf,a.id AS wtdataid, b.id as sizeid FROM wt_data_map a, window_treatment_sizes b WHERE a.wt_id=".$thisLineItem['product_id']." AND a.size_id = b.id AND b.width=".$thisWidthVal." AND b.length >= ".$thisLineMeta['length']." ORDER BY b.length ASC LIMIT 1";
					    //echo $query;  
					    $queryRun=$db->execute($query);
                        $sizeMatches=$queryRun->fetchAll('assoc');
                        
                        //echo "<pre>"; print_r($sizeMatches); echo "</pre>";
                        
					    foreach($sizeMatches as $size){
					        $sizeData=$this->WindowTreatmentSizes->get($size['sizeid'])->toArray();
					        if($sizeData['width'] == $thisWidthVal && $sizeData['length'] >= $thisLineMeta['length']){
					            echo " value=\"".$size['sizeid']."\"";
					        }
					    }
					    
					}
					echo " />";
					echo "<style>
					.ui-icon.avatar{ left:20px !important; }
					.ui-menu-icons .ui-menu-item-wrapper{ padding-left:40px !important; }
					</style>";
					echo "<script>
					$(function(){
						$('select#fabricid').change(function(){
							$('#widthselection').html('<label>Select a Width</label><select id=\"size_width_id\"><option value=\"0\" selected disabled>--Select a Width--</option></select>');
							$.get('/quotes/getfabriccolors/'+$(this).val()+'/window-treatment/'+$('input[name=customer_id]').val(),function(data){
								$('#colorselection').html(data);
							});
						});
					});

					function toggleweltfield(typeselection){
						if(typeselection == 'Shaped Cornice' || typeselection == 'Straight Cornice' || typeselection == 'Box Pleated Valance' || typeselection == 'Tailored Valance'){
							$('#length').attr('min','6');
							$('#length').attr('max','24');
						}else if(typeselection == 'Pinch Pleated Drapery'){
							$('#length').attr('min','60');
							$('#length').attr('max','120');
						}

						if(typeselection=='Shaped Cornice' || typeselection=='Straight Cornice'){
							$('#weltsselection').show('fast');
							$('#liningoptions,#pairpaneloptions').hide('fast');
							if(typeselection=='Shaped Cornice'){
								$('#haswelts').prop('checked',true);
							}
						}else if(typeselection=='Pinch Pleated Drapery'){
							$('#haswelts').prop('checked',false);
							$('#weltsselection').hide('fast');
							$('#liningoptions,#pairpaneloptions').show('fast');
						}else{
							$('#haswelts').prop('checked',false);
							$('#weltsselection').hide('fast');
							$('#liningoptions,#pairpaneloptions').hide('fast');
						}
						if(typeselection == 'Shaped Cornice'){
							$('#flshortwrap').show('fast');
						}else{
							$('#flshortwrap').hide('fast');
						}
					}
					</script>";
					
					
					echo "<script>

		function lookupprice(wttype,fabricid,width,length,welts,lining,pairorpanel){

			$.fancybox.showLoading();

			

			var otherdata='';

			if(wttype=='Pinch Pleated Drapery'){

				otherdata='/'+lining+'/'+pairorpanel;

			}

			if($('input#customer_id').length && $('#customer_id').val() > 0){
				otherdata += '?lookupcustomerid='+$('#customer_id').val();
			}

			$.get('/quotes/lookuppricelist/'+$('#quoteID').val()+'/wt/'+fabricid+'/'+width+'/'+length+'/'+welts+'/'+wttype+otherdata,function(data){

				var resultData=JSON.parse(data);

				

				if(resultData.specialpricing==1){

				    $('#priceeachvalue').html('$'+parseFloat(resultData.price).toFixed(2)+' <span style=\"color:red;font-weight:bold;font-size:14px; font-style:italic; padding:0 0 0 30px;\">SPECIAL PRICING</span>');

				}else{

				    $('#priceeachvalue').html('$'+parseFloat(resultData.price).toFixed(2));

				}

				$('#price').val(parseFloat(resultData.price).toFixed(2));

				$('#specialpricing').val(resultData.specialpricing);

				

				$('#weight').val(resultData.weight);

				$('#difficulty').val(resultData.difficulty);

				$('#labor-lf').val(resultData.laborlf);

				$('#yards').val(resultData.yards);

				$('#wtid').val(resultData.wtid);

				$('#sizeid').val(resultData.sizeID);

				$.fancybox.hideLoading();

			});

		}

		

		$('#wtwidth').change(function(){

			if($('#haswelts').is(':checked')){

				var haswelts=1;

			}else{

				var haswelts=0;

			}

			if($('#wtwidth').val() != '0' && $('#wtwidth').val() != '' && $('#length').val() != ''){

				lookupprice($('#wttype').val(),$('#fabricidwithcolor').val(),$('#wtwidth').val(),$('#length').val(),haswelts);

			}

		});

		

		$('#liningoption').change(function(){

			if($('#haswelts').is(':checked')){

				var haswelts=1;

			}else{

				var haswelts=0;

			}

			if($('#wtwidth').val() != '0' && $('#wtwidth').val() != '' && $('#length').val() != ''){

				lookupprice($('#wttype').val(),$('#fabricidwithcolor').val(),$('#wtwidth').val(),$('#length').val(),haswelts,$('#liningoption').val(),$('#pairorpanel').val());

			}

		});

		$('#pairorpanel').change(function(){

			if($('#haswelts').is(':checked')){

				var haswelts=1;

			}else{

				var haswelts=0;

			}

			if($('#wtwidth').val() != '0' && $('#wtwidth').val() != '' && $('#length').val() != ''){

				lookupprice($('#wttype').val(),$('#fabricidwithcolor').val(),$('#wtwidth').val(),$('#length').val(),haswelts,$('#liningoption').val(),$('#pairorpanel').val());

			}

		});

			

			

		$('#length').keyup($.debounce( 650, function(){

			if($('#haswelts').is(':checked')){

				var haswelts=1;

			}else{

				var haswelts=0;

			}

			if($('#wtwidth').val() != '0' && $('#wtwidth').val() != '' && $('#length').val() != ''){

				lookupprice($('#wttype').val(),$('#fabricidwithcolor').val(),$('#wtwidth').val(),$('#length').val(),haswelts,$('#liningoption').val(),$('#pairorpanel').val());

			}

			if($('#wttype').val() == 'Shaped Cornice'){

				$('#fl-short').val((parseFloat($('#length').val())-4));

			}

		}));

		$('#length').change(function(){

			if($('#haswelts').is(':checked')){

				var haswelts=1;

			}else{

				var haswelts=0;

			}

			if($('#wtwidth').val() != '0' && $('#wtwidth').val() != '' && $('#length').val() != ''){

				lookupprice($('#wttype').val(),$('#fabricidwithcolor').val(),$('#wtwidth').val(),$('#length').val(),haswelts,$('#liningoption').val(),$('#pairorpanel').val());

			}

			if($('#wttype').val() == 'Shaped Cornice'){

				$('#fl-short').val((parseFloat($('#length').val())-4));

			}

		});

		$('#haswelts').change(function(){

			if($('#haswelts').is(':checked')){

				var haswelts=1;

			}else{

				var haswelts=0;

			}

			if($('#wtwidth').val() != '0' && $('#wtwidth').val() != '' && $('#length').val() != ''){

				lookupprice($('#wttype').val(),$('#fabricidwithcolor').val(),$('#wtwidth').val(),$('#length').val(),haswelts,$('#liningoption').val(),$('#pairorpanel').val());

			}

		});		

		$('#fabricid').change(function(){

			if($('#haswelts').is(':checked')){

				var haswelts=1;

			}else{

				var haswelts=0;

			}

			if($('#wtwidth').val() != '0' && $('#wtwidth').val() != '' && $('#length').val() != ''){

				lookupprice($('#wttype').val(),$('#fabricidwithcolor').val(),$('#wtwidth').val(),$('#length').val(),haswelts,$('#liningoption').val(),$('#pairorpanel').val());

			}

		});

		$('#fabricidwithcolor').change(function(){

			if($('#haswelts').is(':checked')){

				var haswelts=1;

			}else{

				var haswelts=0;

			}

			if($('#wtwidth').val() != '0' && $('#wtwidth').val() != '' && $('#length').val() != ''){

				lookupprice($('#wttype').val(),$('#fabricidwithcolor').val(),$('#wtwidth').val(),$('#length').val(),haswelts,$('#liningoption').val(),$('#pairorpanel').val());

			}

		});

		</script>";
					
					
				}
			break;
			case "cubicle-curtain":
				$fabricsfind=$this->Fabrics->find('all',['conditions'=>['status'=>'Active',['OR'=>['use_in_cc'=>1,'use_in_sc'=>1]]],'order'=>['fabric_name'=>'asc']])->group('fabric_name')->toArray();
				if($step==1 || $step=='fromedit'){
					echo "<p id=\"fabricselection\"><label>Select a Fabric</label><select id=\"fabricid\">";
					if($step==1 && !$lineid){
						echo "<option value=\"0\" selected disabled>--Select A Fabric--</option>";
					}
					foreach($fabricsfind as $fabric){
						//check to see if there are CC's in the price list with this fabric at all
						$lookforccs=$this->CubicleCurtains->find('all',['conditions'=>['fabric_name'=>$fabric['fabric_name']]])->toArray();
						if(count($lookforccs) > 0){
							echo "<option value=\"".$fabric['fabric_name']."\"";
							if($step=='fromedit' && $lineid){
								if($fabric['fabric_name'] == $thisLineMeta['fabric_name']){ echo " selected=\"selected\""; }
							}
							echo ">".$fabric['fabric_name']."</option>";
						}
					}
					echo "</select></p>
					<p id=\"colorselection\"><label>Select a Color Option</label><select id=\"fabricidwithcolor\">";
					if($step==1 && !$lineid){
						echo "<option value=\"0\" selected disabled>--Select a Color--</option>";
					}else{
						//loop through this fabric's colors and fill in options
						$thisFabricColors=$this->Fabrics->find('all',['conditions'=>['fabric_name'=>$thisLineMeta['fabric_name']],'order'=>['color'=>'ASC']])->toArray();
						foreach($thisFabricColors as $color){
							echo "<option value=\"".$color['id']."\"";
							if($color['id'] == $thisLineMeta['fabricid']){
								echo " selected=\"selected\"";
							}
							echo ">".$color['color']."</option>";
						}
					}
					echo "</select></p>
					<p id=\"usealiaswrap\" style=\"display:none;\"><label><input type=\"checkbox\" name=\"usealias\" id=\"usealias\"";
					if(!$lineid){
						echo " checked=\"checked\"";
					}else{
						if($thisLineMeta['usealias'] == 'yes'){
							echo " checked=\"checked\"";
						}
					}

					echo " /> Use Alias</label></p>
					<p id=\"meshselection\"><label>Mesh</label><select name=\"meshoption\" id=\"meshoption\">
					<option value=\"No Mesh\">No Mesh</option>";
					$meshoptions=explode("|",$settings['mesh_color_options']);
					foreach($meshoptions as $meshopt){
						echo "<option value=\"".$settings['mesh_default']."&quot; (".$meshopt.")\"";
						if($meshopt=='White' && $step==1 && !$lineid){
							echo " selected=\"selected\"";
						}elseif($lineid){
							if($thisLineMeta['mesh-color'] == $meshopt){
								echo " selected=\"selected\"";
							}
						}
						echo ">".$settings['mesh_default']."&quot; ".$meshopt."</option>";

					}

					echo "</select></p>

					<p id=\"widthselection\">";

					if(!$lineid){

						echo "<label>Select a Width</label><select id=\"size_width_id\"><option value=\"0\" selected disabled>--Select a Width--</option>";

					}else{

						

					/*Determine and preload the selected Width from this line item metadata*/

						echo "<label>Select a Cut Width</label><select name=\"ccwidth\" id=\"ccwidth\">";

						

						$thisLineFabric=$this->Fabrics->get($thisLineMeta['fabricid'])->toArray();

						

						$CCconditions=['fabric_name'=>$thisLineMeta['fabric_name'],'available_colors LIKE' => '%"'.str_replace(" ","_",$thisLineFabric['color']).'"%','status'=>'Active'];

						if($thisLineMeta['mesh']=='No Mesh' || $thisLineMeta['mesh'] == '0'){

							$CCconditions += array('has_mesh'=>0);

						}else{

							$CCconditions += array('has_mesh'=>1);

						}

						$findCC=$this->CubicleCurtains->find('all',['conditions'=>$CCconditions])->toArray();

						if(count($findCC) == 0 && $hasmesh==0){

							//there's no grid for this NO MESH... do the 20% stuff

							$findCC=$this->CubicleCurtains->find('all',['conditions'=>['fabric_name'=>$thisLineMeta['fabric_name'],'available_colors LIKE' => '%"'.$thisLineFabric['color'].'"%','status'=>'Active']])->toArray();

						}

						foreach($findCC as $cc){

							//$sizes=json_decode($cc['available_sizes'],true);

							$availablesizes=$this->CcDataMap->find('all',['conditions'=>['cc_id'=>$cc['id']],'order'=>['size_id'=>'asc']])->toArray();

							$usedWidths=array();

							

							foreach($availablesizes as $availablesize){

								$thisSize=$this->CubicleCurtainSizes->get($availablesize['size_id'])->toArray();

								if(!in_array($thisSize['width'],$usedWidths)){

									echo "<option value=\"".round($thisSize['width'],0)."\" data-ccid=\"".$cc['id']."\"";

									if($thisLineMeta['width'] == $thisSize['width']){ echo " selected=\"selected\""; }

									echo ">".round($thisSize['width'],0)."\"</option>";

									$usedWidths[]=$thisSize['width'];

								}

							}

						}

						

						/*End determine and preload selected Width from meta*/

						

					}

					echo "</select></p>

					<div id=\"finishwidthselection\">";

					if($lineid && $step=='fromedit'){

						echo "<div class=\"input number\"><label>Finished Width (Optional)</label><input type=\"number\" name=\"finished_width\" min=\"1\" placeholder=\"Optional\" value=\"";

						if($thisLineMeta['expected-finish-width'] != 'no'){

							echo $thisLineMeta['expected-finish-width'];

						}

						echo "\" /></div>";

					}else{

						echo "<em>Please select Cut Width above</em>";

					}

							

					echo "</div>

					<p id=\"lengthentry\"><label>Enter a Finished Length</label><input type=\"number\" min=\"24\" name=\"length\" id=\"length\"";

					if($lineid && $step=='fromedit'){

						echo " value=\"".$thisLineMeta['length']."\"";

					}

					echo "/></p>";

					

					if(!$lineid){

						echo "<p id=\"pricerow\"><label>Price each</label><span id=\"priceeachvalue\">----</span></p>";

					}else{

						echo "<p id=\"pricerow\"><label>Price each</label><span id=\"priceeachvalue\">$";

						echo number_format($thisLineItem['best_price'],2,'.',',')."</span></p>";

					}

					

					echo "<input type=\"hidden\" name=\"weight\" id=\"weight\"";

					if($lineid){

						echo " value=\"".$thisLineMeta['cc_calculated_weight']."\"";

					}

					echo " />

					<input type=\"hidden\" name=\"difficulty\" id=\"difficulty\"";

					if($lineid){

						echo " value=\"".$thisLineMeta['difficulty-rating']."\"";

					}

					echo " />

					<input type=\"hidden\" name=\"yards\" id=\"yards\"";

					if($lineid){

						echo " value=\"".$thisLineMeta['yds-per-unit']."\"";

					}

					echo " />

					<input type=\"hidden\" name=\"labor_lf\" id=\"labor-lf\"";

					if($lineid){

						echo " value=\"".$thisLineMeta['labor-billable']."\"";

					}

					echo " />

					<input type=\"hidden\" name=\"price\" id=\"price\"";

					if($lineid){

						echo " value=\"".$thisLineItem['best_price']."\"";

					}

					echo " />

					<input type=\"hidden\" name=\"specialpricing\" id=\"specialpricing\" value=\"";

					if(!$lineid){

						echo "0";

					}else{

						echo $thisLineMeta['specialpricing'];

					}

					echo "\" />

					<input type=\"hidden\" name=\"ccid\" id=\"ccid\"";

					if($lineid){

						echo " value=\"".$thisLineItem['product_id']."\"";

					}

					echo "/>

					<input type=\"hidden\" name=\"sizeid\" id=\"sizeid\"";

					if($lineid){

						echo " value=\"nochange\"";

					}

					echo "/>";

					

					echo "<style>

					.ui-icon.avatar{ left:20px !important; }

					.ui-menu-icons .ui-menu-item-wrapper{ padding-left:40px !important; }

					</style>";

					if($lineid){

						

						echo "<script>

						function lookupprice(fabricid,width,length,mesh){

							$.fancybox.showLoading();

							var otherdata='';
							
							if($('input#customer_id').length && $('#customer_id').val() > 0){
								otherdata += '?lookupcustomerid='+$('#customer_id').val();
							}
							$.get('/quotes/lookuppricelist/'+$('#quoteID').val()+'/cc/".$thisLineMeta['fabricid']."/'+width+'/'+length+'/'+mesh+otherdata,function(data){

								var resultData=JSON.parse(data);

								$('#priceeachvalue').html('$'+parseFloat(resultData.price).toFixed(2));

								$('#price').val(parseFloat(resultData.price).toFixed(2));

								$('#specialpricing').val(resultData.specialpricing);

								$('#weight').val(resultData.weight);

								$('#difficulty').val(resultData.difficulty);

								$('#labor-lf').val(resultData.labor_lf);

								$('#yards').val(resultData.yards);

								$('#ccid').val(resultData.ccid);

								$('#sizeid').val(resultData.sizeID);

								$.fancybox.hideLoading();

							});

						}

						$('#ccwidth').change(function(){

							$('#finishwidthselection').html('<div class=\"input number\"><label>Finished Width (Optional)</label><input type=\"number\" name=\"finished_width\" min=\"1\" placeholder=\"Optional\" /></div>');

							if($('#ccwidth').val() != '0' && $('#ccwidth').val() != '' && $('#length').val() != ''){

								lookupprice($('#fabricidwithcolor').val(),$('#ccwidth').val(),$('#length').val(),$('#meshoption').val());

							}

						});

			

						$('#length').keyup($.debounce( 650, function(){

							if($('#ccwidth').val() != '0' && $('#ccwidth').val() != '' && $('#length').val() != ''){

								lookupprice($('#fabricidwithcolor').val(),$('#ccwidth').val(),$('#length').val(),$('#meshoption').val());

							}

						}));

						

						$('#length').change(function(){

							if($('#ccwidth').val() != '0' && $('#ccwidth').val() != '' && $('#length').val() != ''){

								lookupprice($('#fabricidwithcolor').val(),$('#ccwidth').val(),$('#length').val(),$('#meshoption').val());

							}

						});

						$('#meshoption').change(function(){

							if($('#ccwidth').val() != '0' && $('#ccwidth').val() != '' && $('#length').val() != ''){

								lookupprice($('#fabricidwithcolor').val(),$('#ccwidth').val(),$('#length').val(),$('#meshoption').val());

							}

						});		

		

						$('#fabricid').change(function(){

							if($('#ccwidth').val() != '0' && $('#ccwidth').val() != '' && $('#length').val() != ''){

								lookupprice($('#fabricidwithcolor').val(),$('#ccwidth').val(),$('#length').val(),$('#meshoption').val());

							}

						});

			

						$('#fabricidwithcolor').change(function(){

							if($('#ccwidth').val() != '0' && $('#ccwidth').val() != '' && $('#length').val() != ''){

								lookupprice($('#fabricidwithcolor').val(),$('#ccwidth').val(),$('#length').val(),$('#meshoption').val());

							}

						});

					</script>";

						

					}

					echo "<script>

					$(function(){

						$('select#fabricid').change(function(){

							$('#widthselection').html('<label>Select a Width</label><select id=\"size_width_id\"><option value=\"0\" selected disabled>--Select a Width--</option></select>');

							";

							if($step=='fromedit' && $lineid){

								echo "var ifColorID='/".$thisLineMeta['fabricid']."/".$lineid."/';

								";

							}else{

								echo "var ifColorID='';

								";

							}

							

							echo "

							$.get('/quotes/getfabriccolors/'+$(this).val()+'/cubicle-curtains/'+$('input[name=customer_id]').val()+ifColorID,function(data){

								$('#colorselection').html(data);

							});

						});

						

					});

					</script>";

					

				}elseif($step==2){

					

				}elseif($step==3){

					

				}

			break;

			case "bedspread":
				$fabricsfind=$this->Fabrics->find('all',['conditions'=>['status'=>'Active','use_in_bs'=>1],'order'=>['fabric_name'=>'asc']])->group('fabric_name')->toArray();
				if($step==1 || $step=='fromedit'){
					echo "<p id=\"fabricselection\"><label>Select a Fabric</label><select id=\"fabricid\">";
					if($step==1 && !$lineid){
						echo "<option value=\"0\" selected disabled>--Select A Fabric--</option>";
					}
					foreach($fabricsfind as $fabric){
						//check to see if there are CC's in the price list with this fabric at all
						$lookforccs=$this->Bedspreads->find('all',['conditions'=>['fabric_name'=>$fabric['fabric_name']]])->toArray();
						if(count($lookforccs) > 0){
							echo "<option value=\"".$fabric['fabric_name']."\"";
							if($step=='fromedit' && $lineid){
								if($fabric['fabric_name'] == $thisLineMeta['fabric_name']){ echo " selected=\"selected\""; }
							}
							echo ">".$fabric['fabric_name']."</option>";
						}
					}
					echo "</select></p>
					<p id=\"colorselection\"><label>Select a Color Option</label><select id=\"fabricidwithcolor\">";
					if($step==1 && !$lineid){
						echo "<option value=\"0\" selected disabled>--Select a Color--</option>";
					}else{
						//loop through this fabric's colors and fill in options
						$thisFabricColors=$this->Fabrics->find('all',['conditions'=>['fabric_name'=>$thisLineMeta['fabric_name']],'order'=>['color'=>'ASC']])->toArray();
						foreach($thisFabricColors as $color){
							echo "<option value=\"".$color['id']."\"";
							if($color['id'] == $thisLineMeta['fabricid']){
								echo " selected=\"selected\"";
							}
							echo ">".$color['color']."</option>";
						}
					}
				
					echo "</select></p>
					<p id=\"usealiaswrap\" style=\"display:none;\"><label><input type=\"checkbox\" name=\"usealias\" id=\"usealias\"";
					if(!$lineid){
						echo " checked=\"checked\"";
					}else{
						if($thisLineMeta['usealias'] == 'yes'){
							echo " checked=\"checked\"";
						}
					}
					
					echo " /> Use Alias</label></p>
					<p id=\"mattresssizeselection\"><label>Mattress Size</label>
					<select name=\"mattresssize\" id=\"mattresssize\">
					<option value='36\"'";
					if(!$lineid || $thisLineMeta['custom-top-width-mattress-w']=='36'){
						echo " selected=\"selected\"";
					}
					echo ">36\"</option>
					<option value='42\"'";
					if($lineid && $thisLineMeta['custom-top-width-mattress-w']=='42'){
						echo " selected=\"selected\"";
					}
					echo ">42\"</option>
					</select></p>
					<p id=\"quiltedselection\"><label><input type=\"checkbox\" name=\"quilted\" id=\"quilted\" value=\"1\"";
					if($lineid && $thisLineMeta['quilted'] == '1'){
						echo " checked=\"checked\"";
					}
					echo " /> Quilted</label></p>";
					echo "<p id=\"sizeselection\">";

					if(!$lineid){
						echo "<label>Select a Size</label><select id=\"bssize\"><option value=\"0\" selected disabled>--Select a Size--</option>";
					}else{
						echo "<label>Select a Size</label><select id=\"bssize\">";
						$thisLineFabric=$this->Fabrics->get($thisLineMeta['fabricid'])->toArray();
						$BSconditions=['fabric_name'=>$thisLineMeta['fabric_name'],'available_colors LIKE' => '%"'.str_replace(" ","_",$thisLineFabric['color']).'"%','status'=>'Active','mattress_size'=>$thisLineMeta['custom-top-width-mattress-w'].'"'];

						if($thisLineMeta['quilted']=='1'){
							$BSconditions += array('quilted'=>1);
						}else{
							$BSconditions += array('quilted'=>0);
						}

						$findBS=$this->Bedspreads->find('all',['conditions'=>$BSconditions])->toArray();
						if(count($findBS) == 0 && $thisLineMeta['quilted']==0){
							//there's no grid for this NO MESH... do the 20% stuff
							$findBS=$this->Bedspreads->find('all',['conditions'=>['fabric_name'=>$thisLineMeta['fabric_name'],'available_colors LIKE' => '%"'.$thisLineFabric['color'].'"%','status'=>'Active']])->toArray();
						}

						$thisBSSize=array();
						foreach($findBS as $bs){
							$availablesizes=$this->BsDataMap->find('all',['conditions'=>['bs_id'=>$bs['id']],'order'=>['size_id'=>'asc']])->toArray();
							$usedSizes=array();
							foreach($availablesizes as $availablesize){
								$thisSize=$this->BedspreadSizes->get($availablesize['size_id'])->toArray();
								if(!in_array($thisSize['width'].'x'.$thisSize['length'],$usedSizes)){
									echo "<option value=\"".$thisSize['id']."\" data-bsid=\"".$bs['id']."\" data-sizeid=\"".$thisSize['id']."\"";
									if($thisLineMeta['width'] == $thisSize['width'] && $thisLineMeta['length'] == $thisSize['length']){ 
										$thisBSSize=$thisSize;
										echo " selected=\"selected\""; 
									}
									echo " data-specialpricing=\"";
									$specialpricing=0;
									//see if this customer has a pricing override
									if($overridePrice=$this->customerHasPriceListOverride($thisQuote['customer_id'],'bs',$bs['id'],$availablesize['size_id'])){
										$filteredPrice = number_format($overridePrice,2,'.',',');
										$specialpricing=1;
									}else{
										$filteredPrice=$availablesize['price'];
									}
									echo $specialpricing;
									echo "\" data-price=\"";
									echo $filteredPrice;
									echo "\" data-weight=\"".$availablesize['weight']."\" data-diff=\"".$availablesize['difficulty']."\" data-topwidthsl=\"".$availablesize['top_width_l']."\" data-dropwidthsl=\"".$availablesize['drop_width_l']."\" data-quiltpattern=\"".$availablesize['quilting_pattern']."\" data-topcutsw=\"".$availablesize['top_cuts_w']."\" data-dropcutsw=\"".$availablesize['drop_cuts_w']."\" data-layout=\"".$availablesize['layout']."\" data-repeat=\"".$availablesize['vertical_repeat']."\">".$thisSize['title']."</option>";
									$usedSizes[]=$thisSize['width'].'x'.$thisSize['length'];

								}
							}
						}

						/*End determine and preload selected Width from meta*/

						

					}

					echo "</select></p>";

					
					if(!$lineid){
						echo "<p id=\"pricerow\"><label>Price each</label><span id=\"priceeachvalue\">----</span></p>";
					}else{
						echo "<p id=\"pricerow\"><label>Price each</label><span id=\"priceeachvalue\">$";
						echo number_format($thisLineItem['best_price'],2,'.',',')."</span></p>";
					}

					

					if($lineid){

						echo "<input type=\"hidden\" name=\"weight\" id=\"weight\" value=\"".$thisLineMeta['bs_calculated_weight']."\" />
						<input type=\"hidden\" name=\"difficulty\" id=\"difficulty\" value=\"".$thisLineMeta['difficulty-rating']."\" />
						<input type=\"hidden\" name=\"yards\" id=\"yards\" value=\"".$thisLineMeta['yds-per-unit']."\" />
						<input type=\"hidden\" name=\"price\" id=\"price\" value=\"".$thisLineMeta['price']."\" />
						<input type=\"hidden\" name=\"specialpricing\" id=\"specialpricing\" value=\"".$thisLineMeta['specialpricing']."\" />
						<input type=\"hidden\" name=\"bsid\" id=\"bsid\" value=\"".$thisLineItem['product_id']."\" />
						<input type=\"hidden\" name=\"sizeid\" id=\"sizeid\" value=\"".$thisBSSize['id']."\" />
						<input type=\"hidden\" name=\"topwidthsl\" id=\"topwidthsl\" value=\"".$thisLineMeta['top-widths']."\" />
						<input type=\"hidden\" name=\"dropwidthsl\" id=\"dropwidthsl\" value=\"".$thisLineMeta['drop-widths']."\" />
						<input type=\"hidden\" name=\"quiltpattern\" id=\"quiltpattern\" value=\"".$thisLineMeta['quilting-pattern']."\" />
						<input type=\"hidden\" name=\"topcutsw\" id=\"topcutsw\" value=\"".$thisLineMeta['top-cut']."\" />
						<input type=\"hidden\" name=\"dropcutsw\" id=\"dropcutsw\" value=\"".$thisLineMeta['drop-cut']."\" />
						<input type=\"hidden\" name=\"layout\" id=\"layout\" value=\"".$thisLineMeta['layout']."\" />
						<input type=\"hidden\" name=\"repeat\" id=\"repeat\" value=\"".$thisLineMeta['vertical-repeat']."\" />";

					}else{

						echo "<input type=\"hidden\" name=\"weight\" id=\"weight\" />
						<input type=\"hidden\" name=\"difficulty\" id=\"difficulty\" />
						<input type=\"hidden\" name=\"yards\" id=\"yards\" />
						<input type=\"hidden\" name=\"price\" id=\"price\" />
						<input type=\"hidden\" name=\"specialpricing\" id=\"specialpricing\" value=\"0\" />
						<input type=\"hidden\" name=\"bsid\" id=\"bsid\" />
						<input type=\"hidden\" name=\"sizeid\" id=\"sizeid\" />
						<input type=\"hidden\" name=\"topwidthsl\" id=\"topwidthsl\" />
						<input type=\"hidden\" name=\"dropwidthsl\" id=\"dropwidthsl\" />
						<input type=\"hidden\" name=\"quiltpattern\" id=\"quiltpattern\" />
						<input type=\"hidden\" name=\"topcutsw\" id=\"topcutsw\" />
						<input type=\"hidden\" name=\"dropcutsw\" id=\"dropcutsw\" />
						<input type=\"hidden\" name=\"layout\" id=\"layout\" />
						<input type=\"hidden\" name=\"repeat\" id=\"repeat\" />";

					}

					echo "<style>
					.ui-icon.avatar{ left:20px !important; }
					.ui-menu-icons .ui-menu-item-wrapper{ padding-left:40px !important; }
					</style>
					<script>
					$(function(){
						$('select#fabricid').change(function(){
							$('#sizeselection').html('<label>Select a Size</label><select id=\"sizeid\"><option value=\"0\" selected disabled>--Select a Size--</option></select>');
							$.get('/quotes/getfabriccolors/'+$(this).val()+'/bedspreads/'+$('input[name=customer_id]').val(),function(data){
								$('#colorselection').html(data);
							});
						});

						$('#fabricidwithcolor,#mattresssize,input#quilted').change(function(){
							//reload available bs size options into the bssize dropdown

							if($('#quilted').is(':checked')){
								var thirdparameter=1;
							}else{
								var thirdparameter=0;
							}
							var fourthparameter=$('#mattresssize').val();
							var fifthparameter='";
							if($step=='fromedit' && $lineid){
								echo $thisQuote['customer_id'];
							}
							echo "';

							$.get('/quotes/getbssizeoptions/'+$('#fabricid').val()+'/'+$('#fabricidwithcolor').val()+'/'+thirdparameter+'/'+fourthparameter+'/'+fifthparameter,function(data){
								$('#sizeselection').html(data);
							});
						});


						$('#bssize').change(function(){
							console.log('bs size changed');

							if($('option:selected',this).attr('data-specialpricing') == 1){
								$('#priceeachvalue').html('$'+parseFloat($('option:selected',this).attr('data-price')).toFixed(2)+' <span style=\"color:red;font-weight:bold;font-size:14px; font-style:italic; padding:0 0 0 30px;\">SPECIAL PRICING</span>');
							}else{
								$('#priceeachvalue').html('$'+parseFloat($('option:selected',this).attr('data-price')).toFixed(2));
							}

							$('#price').val(parseFloat($('option:selected',this).attr('data-price')).toFixed(2));
							$('#weight').val(parseFloat($('option:selected',this).attr('data-weight')).toFixed(2));
							$('#difficulty').val(parseFloat($('option:selected',this).attr('data-diff')).toFixed(2));
							$('#yards').val(parseFloat($('option:selected',this).attr('data-yards')).toFixed(2));
							$('#bsid').val($('option:selected',this).attr('data-bsid'));
							$('#sizeid').val($('option:selected',this).attr('data-sizeid'));
							$('#topwidthsl').val($('option:selected',this).attr('data-topwidthsl'));
							$('#dropwidthsl').val($('option:selected',this).attr('data-dropwidthsl'));
							$('#quiltpattern').val($('option:selected',this).attr('data-quiltpattern'));
							$('#topcutsw').val($('option:selected',this).attr('data-topcutsw'));
							$('#dropcutsw').val($('option:selected',this).attr('data-dropcutsw'));
							$('#layout').val($('option:selected',this).attr('data-layout'));
							$('#repeat').val($('option:selected',this).attr('data-repeat'));
							$('#specialpricing').val($('option:selected',this).attr('data-specialpricing'));
						});
					});
					</script>";

				}elseif($step==2){


				}elseif($step==3){

					

				}

			break;

			case "service":

				if($step==1){

					echo "<p><label>Select a Service</label><select name=\"serviceid\" id=\"serviceid\"><option value=\"\">--Select a Service--</option>";

					$services=$this->Services->find('all')->toArray();

					foreach($services as $service){

						echo "<option value=\"".$service['id']."\" data-price=\"".$service['price']."\">".$service['title']."</option>\n";

					}

					echo "</select></p>";

					

					echo "<script>

					$(function(){

						$('select#serviceid').change(function(){

							$('#databox p#pricerow').remove();

							$('#databox').append('<p id=\"pricerow\"><b>Price:</b> <input type=\"number\" id=\"priceinput\" step=\"any\" min=\"0.00\" value=\"'+$(this).find('option:selected').attr('data-price')+'\" /></p>');

						});

					});

					</script>";

					

				}elseif($step==2){

					

				}

			break;

			case "miscellaneous":

				

			break;

		}

		

	}

	public function getfabriccolorlist($fabricname,$quoteID=false,$selectField=false){

		$this->autoRender=false;

        if($quoteID=='false'){
            $quoteID=false;
        }

		if($quoteID){

			$thisQuote=$this->Quotes->get($quoteID)->toArray();

		}

		$fabrics=$this->Fabrics->find('all',['conditions'=>['fabric_name'=>$fabricname,'status'=>'Active'],'order'=>['fabric_name'=>'asc','color'=>'asc']])->toArray();

        if($selectField){
            echo "<option value=\"0\" disabled selected>--Select a Color--</option>";
        }

		foreach($fabrics as $fabric){
                if($selectField){
                    
                    if($quoteID && $fabricAlias=$this->getFabricAlias($fabric['id'],$thisQuote['customer_id'])){
                        echo "<option value=\"".$fabric['id']."\" data-fabricdetails='".json_encode($fabric)."'>".$fabricAlias['color']."</option>";
                    }else{
                        echo "<option value=\"".$fabric['id']."\" data-fabricdetails='".json_encode($fabric)."'>".$fabric['color']."</option>";
                    }
                    
                }else{
    				if($quoteID && $fabricAlias=$this->getFabricAlias($fabric['id'],$thisQuote['customer_id'])){
    
    					echo "<div class=\"colorswatch hasalias\" onclick=\"doselectfabriccolor('".$fabric['id']."',1);\" data-fabricid=\"".$fabric['id']."\"><img src=\"/files/fabrics/".$fabric['id']."/".$fabric['image_file']."\" data-fabricid=\"".$fabric['id']."\" style=\"width:100%;height:auto;\" /><br><span style=\"font-size:14px;\">".$fabric['fabric_name']." ".$fabric['color']."</span><br><span class=\"aliasname\" style=\"font-size:12px; font-weight:bold; font-style:italic; color:blue;\">";
    
    					echo $fabricAlias['fabric_name']." ".$fabricAlias['color'];
    
    					echo "</span></div>";	
    
    				}else{
    
    					echo "<div class=\"colorswatch noalias\" onclick=\"doselectfabriccolor('".$fabric['id']."',0);\" data-fabricid=\"".$fabric['id']."\"><img src=\"/files/fabrics/".$fabric['id']."/".$fabric['image_file']."\" data-fabricid=\"".$fabric['id']."\" style=\"width:100%;height:auto;\" /><br>".$fabric['fabric_name']." ".$fabric['color']."</div>";
    
    				}
                }

					

		}
        
        if(!$selectField){
    		echo "<div style=\"clear:both;\"></div>";
    
    		echo "<input type=\"hidden\" name=\"selectedColor\" value=\"\" />";
    
    		echo "<style>
    
    		.colorswatch.hasalias{ padding:8px; float:left; text-align:center; width:160px; height:210px; cursor:pointer; line-height:14px; margin:8px; }
    
    		.colorswatch.noalias{ padding:8px; float:left; text-align:center; width:160px; cursor:pointer; margin:8px; line-height:14px; height:210px; font-size:14px; }
    
    		.colorswatch.selected{ background:green; }
    
    		.colorswatch:hover{ background:#B7FCAD; }
    
    		</style>";
        }

	}

	

	public function calculateMargin($price,$cost){

		return number_format((((floatval($price)-floatval($cost))/floatval($price))*100),1,'.','').'%';

	}



	public function deleteLineItemMeta($lineItemID,$metakey){
		$lineitemmetatable=TableRegistry::get('QuoteLineItemMeta');

		$lookupKey=$this->QuoteLineItemMeta->find('all',['conditions'=>['quote_item_id'=>$lineItemID,'meta_key'=>$metaKey]])->toArray();
		foreach($lookupKey as $metaitem){
			$thisMeta=$lineitemmetatable->get($metaitem['id']);
			$lineitemmetatable->delete($thisMeta);
		}

		return true;
	}


	public function updateLineItemMeta($lineItemID,$metaKey,$newValue){

		$lineitemmetatable=TableRegistry::get('QuoteLineItemMeta');

		$lookupKey=$this->QuoteLineItemMeta->find('all',['conditions'=>['quote_item_id'=>$lineItemID,'meta_key'=>$metaKey]])->toArray();

		if(count($lookupKey) == 0){

			//add it

			$newMeta=$lineitemmetatable->newEntity();

			$newMeta->meta_key=$metaKey;

			$newMeta->meta_value=$newValue;

			$newMeta->quote_item_id=$lineItemID;

			$lineitemmetatable->save($newMeta);

		}else{

			//update it

			foreach($lookupKey as $metaitem){

				$thisMeta=$lineitemmetatable->get($metaitem['id']);

				$thisMeta->meta_value=$newValue;

				$lineitemmetatable->save($thisMeta);

			}

		}

		return true;

	}

	

	

	

	

	public function addmanualdiscount($quoteID){

		$this->viewBuilder()->layout('iframeinner');

       	$this->set('authUser', $this->Auth->user());

		

		$thisQuote=$this->Quotes->get($quoteID)->toArray();

		$this->set('quoteData',$thisQuote);

		

		if($this->request->data){

			

		}else{

			

		}

	}

	

	

	public function changequotestatus($quoteID,$newstatus){

        $oldQuoteData=$this->Quotes->get($quoteID)->toArray();
        $oldstatus=$oldQuoteData['status'];
        
		$this->autoRender=false;
		$quoteTable=TableRegistry::get('Quotes');

		$thisQuote=$quoteTable->get($quoteID);

		/*
		if($newstatus=='published' && $thisQuote->quote_total == 0.00){
			$this->Flash->error('You cannot publish a quote with a total of $0');
			return $this->redirect('/quotes/add/'.$quoteID);

		}
		*/
		
		if($newstatus=='published' && $oldstatus != 'published'){
		    $thisQuote->publish_date=time();
		}

		$thisQuote->status=$newstatus;
		if($quoteTable->save($thisQuote)){

			$this->Flash->success('Changed quote '.$thisQuote->quote_number.' status to '.$newstatus);
			$this->logActivity($_SERVER['REQUEST_URI'],'Changed quote '.$thisQuote->quote_number.' status to '.$newstatus);
			return $this->redirect('/quotes/add/'.$quoteID);
		}

	}




	public function buildquotesummary($quoteID,$filename){
		

		$settings=$this->getsettingsarray();
		$this->set('allSettings',$settings);

		$allFabrics=$this->Fabrics->find('all')->toArray();

		$this->set('allFabrics',$allFabrics);

		$thisQuote=$this->Quotes->get($quoteID)->toArray();

		$quoteAuthorData=$this->Users->get($thisQuote['created_by'])->toArray();

		$this->set('quoteAuthorData',$quoteAuthorData);

		

		$allimages=array();

		$allimageslookup=$this->LibraryImages->find('all')->toArray();

		foreach($allimageslookup as $imagedata){

			$allimages[$imagedata['id']]=$imagedata;

		}

		$this->set('allimages',$allimages);

		$allTrackSystems=$this->TrackSystems->find('all')->toArray();

		$this->set('alltracksystems',$allTrackSystems);

		$allLinings=array();

		$allLiningLookups=$this->Linings->find('all')->toArray();

		foreach($allLiningLookups as $lining){

			$allLinings[$lining['id']]=$lining['short_title'];

		}

		$this->set('alllinings',$allLinings);

		
		$orderLookup=$this->Orders->find('all',['conditions'=>['quote_id'=>$thisQuote['id']]])->toArray();
		foreach($orderLookup as $orderRow){
			$orderData=$orderRow;
		}


		
		$globalNotes=$this->QuoteNotes->find('all',['conditions' => ['quote_id' => $thisQuote['id']],'order'=>['id'=>'asc']])->toArray();
		$this->set('globalNotes',$globalNotes);
		

		$GLOBALS['pdfheader']="<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\">

<tr>

<td width=\"11%\" valign=\"top\" align=\"left\"><img src=\"".$_SERVER['DOCUMENT_ROOT']."/webroot/img/android-chrome-144x144.png\" width=\"65\" /></td>

<td width=\"34%\" valign=\"top\" algin=\"left\"><span style=\"font-size:9px; font-family:'Metropolis'; color:#004A87;\"><span style=\"font-family:'Metropolis Semi Bold';\">HC INTERIORS</span><br>".$settings['hci_address_line_1'].". ".$settings['hci_address_line_2']."<br>".$settings['hci_address_city'].", ".$settings['hci_address_state']." ".$settings['hci_address_zipcode']."<Br>Phone: ".$settings['hci_phone_number']."  <span style=\"font-family:'Helvetica';\">&middot;</span>  Fax: ".$settings['hci_fax']."<br>E-mail: <span style=\"color:blue;\">".$quoteAuthorData['email']."</span></span></td>

<td width=\"10%\" valign=\"top\" align=\"center\" style=\"font-size:9px;\"><h1>SUMMARY</h1></td>
<td width=\"10%\">&nbsp;</td>
<td width=\"35%\" valign=\"top\" align=\"right\" style=\"font-size:9px;\">

<table width=\"100%\" cellpadding=\"3\" border=\"1\" bordercolor=\"#000000\" cellspacing=\"0\" align=\"right\">

	<tr>

		<td style=\"font-size:9px;\" width=\"35%\" align=\"left\" bgcolor=\"#E4EEFA\" style=\"font-size:9px;\">Date:</td>

		<td style=\"font-size:9px;\" width=\"65%\" align=\"left\" style=\"font-size:9px;\">";

		

		$GLOBALS['pdfheader'] .= date('n/j/Y',$orderData['created']);
		

		$GLOBALS['pdfheader'] .= "</td>

	</tr>

	<tr>

		<td style=\"font-size:8px;\" width=\"35%\" align=\"left\" bgcolor=\"#E4EEFA\" style=\"font-size:9px;\">WO #:</td>

		<td style=\"font-size:8px;\" width=\"65%\" align=\"left\" style=\"font-size:9px;\">".$orderData['order_number']."</td>

	</tr>

	<tr>

		<td width=\"35%\" style=\"font-size:7px;\" align=\"left\" bgcolor=\"#E4EEFA\" style=\"font-size:9px;\">Project:</td>

		<td width=\"65%\" style=\"font-size:7px;\" align=\"left\" style=\"font-size:9px;\">".strtoupper($thisQuote['title'])."</td>

	</tr>

</table>

</td></tr></table>";
				//$thisQuoteItems=$this->QuoteLineItems->find('all',['conditions'=>['quote_id'=>$quoteID],'order'=>['sortorder'=>'asc','id'=>'asc']])->toArray();

		$thisQuoteItems=$this->OrderLineItems->find('all',['conditions'=>['quote_id'=>$quoteID],'order'=>['sortorder'=>'asc','id'=>'asc']])->toArray();

		$customerData=$this->Customers->get($thisQuote['customer_id'])->toArray();

		

		if($thisQuote['contact_id'] > 1){

			$contactData=$this->CustomerContacts->get($thisQuote['contact_id'])->toArray();

			$this->set('contactData',$contactData);

		}else{

			$this->set('contactData',array());

		}

		

		$quoteItems=array();

		foreach($thisQuoteItems as $quoteItem){

			$quoteItems[$quoteItem['id']]=array(

				'title'=>$quoteItem['title'],

				'description'=>$quoteItem['description'],

				'best_price'=>$quoteItem['best_price'],

				'tier_adjusted_price'=>$quoteItem['tier_adjusted_price'],

				'install_adjusted_price'=>$quoteItem['install_adjusted_price'],

				'rebate_adjusted_price'=>$quoteItem['rebate_adjusted_price'],

				'pmi_adjusted' => $quoteItem['pmi_adjusted'],
				
				'override_active' => $quoteItem['override_active'],

				'override_price'=>$quoteItem['override_price'],

				'extended_price' => $quoteItem['extended_price'],

				'qty'=>$quoteItem['qty'],

				'line_item_type'=>$quoteItem['line_item_type'],

				'room_number'=>$quoteItem['room_number'],

				'line_number'=>$quoteItem['line_number'],

				'sortorder'=>$quoteItem['sortorder'],

				'internal_line'=>$quoteItem['internal_line'],

				'product_type'=>$quoteItem['product_type'],

				'status'=>$quoteItem['status'],

				'unit'=>ucfirst($quoteItem['unit']),

				'metadata'=>array(),

				'notes' => array(),

				'product_id' => $quoteItem['product_id']

			);

			
			$thisItemMetas=$this->OrderLineItemMeta->find('all',['conditions'=>['order_item_id'=>$quoteItem['id']]])->toArray();

			//$thisItemMetas=$this->QuoteLineItemMeta->find('all',['conditions'=>['quote_item_id'=>$quoteItem['id']]])->toArray();

			foreach($thisItemMetas as $itemMeta){

				$quoteItems[$quoteItem['id']]['metadata'][$itemMeta['meta_key']]=$itemMeta['meta_value'];

			}
			//			$thisItemNotes=$this->QuoteLineItemNotes->find('all',['conditions'=>['quote_item_id' => $quoteItem['id']],'order'=>['time'=>'ASC']])->toArray();

			$thisItemNotes=$this->OrderLineItemNotes->find('all',['conditions'=>['quote_item_id' => $quoteItem['id']],'order'=>['time'=>'ASC']])->toArray();

			$notenum=1;

			foreach($thisItemNotes as $itemNote){

				$thisNoteUser=$this->Users->get($itemNote['user_id'])->toArray();

				$thisNoteName=$thisNoteUser['first_name'].' '.substr($thisNoteUser['last_name'],0,1);

				$quoteItems[$quoteItem['id']]['notes'][$notenum]=array(

						'visible_to_customer' => $itemNote['visible_to_customer'],

						'time' => $itemNote['time'],

						'user_id'=>$itemNote['user_id'],

						'name'=>$thisNoteName,

						'message' => $itemNote['message']

				);

				$notenum++;

			}

			

			if(isset($quoteItems[$quoteItem['id']]['metadata']['fabricid']) && floatval($quoteItems[$quoteItem['id']]['metadata']['fabricid']) >0){

				$thisFabric=$this->Fabrics->get($quoteItems[$quoteItem['id']]['metadata']['fabricid'])->toArray();

				

				if($fabricAlias=$this->getFabricAlias($thisFabric['id'],$thisQuote['customer_id'])){

					$thisFabric['alias_name']=$fabricAlias['fabric_name'];

					$thisFabric['alias_color']=$fabricAlias['color'];

				}else{

					$thisFabric['alias_name']=false;

					$thisFabric['alias_color']=false;

				}

				$quoteItems[$quoteItem['id']]['fabricdata']=$thisFabric;

			}

			

			

		}

		

		$this->set('quoteData',$thisQuote);

		$this->set('quoteItems',$quoteItems);

		$this->set('customerData',$customerData);

		

		if($thisQuote['revision'] > 0){

			$revisionAddon='_REV_'.$thisQuote['revision'];

		}else{

			$revisionAddon='';

		}

		$this->viewBuilder()->options([

                'pdfConfig' => [

                    'orientation' => 'landscape',

                    'filename' => 'HCI_Quote_' . $thisQuote['quote_number'].$revisionAddon.'.pdf',

					'title' => 'HCI Quote '.$quoteID

                ]

            ]);

	}






	public function buildquotepdf($quoteID,$filename){

		

		$settings=$this->getsettingsarray();
		$this->set('allSettings',$settings);

		$allFabrics=$this->Fabrics->find('all')->toArray();

		$this->set('allFabrics',$allFabrics);

		$thisQuote=$this->Quotes->get($quoteID)->toArray();

		$quoteAuthorData=$this->Users->get($thisQuote['created_by'])->toArray();

		$this->set('quoteAuthorData',$quoteAuthorData);

		

		$allimages=array();

		$allimageslookup=$this->LibraryImages->find('all')->toArray();

		foreach($allimageslookup as $imagedata){

			$allimages[$imagedata['id']]=$imagedata;

		}

		$this->set('allimages',$allimages);

		$allTrackSystems=$this->TrackSystems->find('all')->toArray();

		$this->set('alltracksystems',$allTrackSystems);

		$allLinings=array();

		$allLiningLookups=$this->Linings->find('all')->toArray();

		foreach($allLiningLookups as $lining){

			$allLinings[$lining['id']]=$lining['short_title'];

		}

		$this->set('alllinings',$allLinings);

		

		$globalNotes=$this->QuoteNotes->find('all',['conditions' => ['quote_id' => $thisQuote['id']],'order'=>['id'=>'asc']])->toArray();
		$this->set('globalNotes',$globalNotes);
		
		$GLOBALS['pdfheader']="<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\">

<tr>

<td width=\"11%\" valign=\"top\" align=\"left\"><img src=\"".$_SERVER['DOCUMENT_ROOT']."/webroot/img/android-chrome-144x144.png\" width=\"65\" /></td>
<td width=\"34%\" valign=\"top\" algin=\"left\"><span style=\"font-size:9px; font-family:'Metropolis'; color:#004A87;\"><span style=\"font-family:'Metropolis Semi Bold';\">HC INTERIORS</span><br>".$settings['hci_address_line_1'].". ".$settings['hci_address_line_2']."<br>".$settings['hci_address_city'].", ".$settings['hci_address_state']." ".$settings['hci_address_zipcode']."<Br>Phone: ".$settings['hci_phone_number']."  <span style=\"font-family:'Helvetica';\">&middot;</span>  Fax: ".$settings['hci_fax']."<br>E-mail: <span style=\"color:blue;\">".$quoteAuthorData['email']."</span></span></td>

<td width=\"20%\" valign=\"top\" align=\"center\" style=\"font-size:9px;\">";

		if($thisQuote['status']=='draft'){

			if($thisQuote['expires'] < time()){

				$GLOBALS['pdfheader'] .= "<img src=\"".$_SERVER['DOCUMENT_ROOT']."/webroot/img/pdf-expired.png\" width=\"200\" />";

			}else{

				$GLOBALS['pdfheader'] .= "<img src=\"".$_SERVER['DOCUMENT_ROOT']."/webroot/img/pdf-preview.png\" width=\"200\" />";

			}

		}else{

			if($thisQuote['expires'] < time()){

				$GLOBALS['pdfheader'] .= "<img src=\"".$_SERVER['DOCUMENT_ROOT']."/webroot/img/pdf-expired.png\" width=\"200\" />";

			}else{

				$GLOBALS['pdfheader'] .= "&nbsp;";

			}

		}

		$GLOBALS['pdfheader'] .= "</td>

<td width=\"35%\" valign=\"top\" align=\"right\" style=\"font-size:9px;\">

<table width=\"100%\" cellpadding=\"3\" border=\"1\" bordercolor=\"#000000\" cellspacing=\"0\" align=\"right\">

	<tr>

		<td style=\"font-size:9px;\" width=\"35%\" align=\"left\" bgcolor=\"#E4EEFA\" style=\"font-size:9px;\">Date:</td>

		<td style=\"font-size:9px;\" width=\"65%\" align=\"left\" style=\"font-size:9px;\">";

		

		if($thisQuote['status'] == 'published'){

			$GLOBALS['pdfheader'] .= date('n/j/Y',$thisQuote['modified']);

		}else{

			$GLOBALS['pdfheader'] .= date('n/j/Y',$thisQuote['created']);

		}

		

		$GLOBALS['pdfheader'] .= "</td>

	</tr>

	<tr>

		<td style=\"font-size:8px;\" width=\"35%\" align=\"left\" bgcolor=\"#E4EEFA\" style=\"font-size:9px;\">Quote #:</td>

		<td style=\"font-size:8px;\" width=\"65%\" align=\"left\" style=\"font-size:9px;\">".$thisQuote['quote_number'];

		if($thisQuote['revision'] >0){

			$GLOBALS['pdfheader'] .= " [REVISION ".$thisQuote['revision']."]";

		}

		$GLOBALS['pdfheader'] .= "</td>

	</tr>

	<tr>

		<td width=\"35%\" style=\"font-size:7px;\" align=\"left\" bgcolor=\"#E4EEFA\" style=\"font-size:9px;\">Project:</td>

		<td width=\"65%\" style=\"font-size:7px;\" align=\"left\" style=\"font-size:9px;\">".strtoupper($thisQuote['title'])."</td>

	</tr>

</table>

</td></tr></table>";
		//		$thisQuoteItems=$this->QuoteLineItems->find('all',['conditions'=>['quote_id'=>$quoteID],'order'=>['sortorder'=>'asc','id'=>'asc']])->toArray();

		$thisQuoteItems=$this->OrderLineItems->find('all',['conditions'=>['quote_id'=>$quoteID],'order'=>['sortorder'=>'asc','id'=>'asc']])->toArray();

		$customerData=$this->Customers->get($thisQuote['customer_id'])->toArray();

		

		if($thisQuote['contact_id'] > 1){

			$contactData=$this->CustomerContacts->get($thisQuote['contact_id'])->toArray();

			$this->set('contactData',$contactData);

		}else{

			$this->set('contactData',array());

		}

		

		$quoteItems=array();

		foreach($thisQuoteItems as $quoteItem){

			$quoteItems[$quoteItem['id']]=array(

				'title'=>$quoteItem['title'],

				'description'=>$quoteItem['description'],

				'best_price'=>$quoteItem['best_price'],

				'tier_adjusted_price'=>$quoteItem['tier_adjusted_price'],

				'install_adjusted_price'=>$quoteItem['install_adjusted_price'],

				'rebate_adjusted_price'=>$quoteItem['rebate_adjusted_price'],

				'pmi_adjusted' => $quoteItem['pmi_adjusted'],
				
				'override_active' => $quoteItem['override_active'],

				'override_price'=>$quoteItem['override_price'],

				'extended_price' => $quoteItem['extended_price'],

				'qty'=>$quoteItem['qty'],

				'line_item_type'=>$quoteItem['line_item_type'],

				'room_number'=>$quoteItem['room_number'],

				'line_number'=>$quoteItem['line_number'],

				'sortorder'=>$quoteItem['sortorder'],

				'internal_line'=>$quoteItem['internal_line'],

				'product_type'=>$quoteItem['product_type'],

				'status'=>$quoteItem['status'],

				'unit'=>ucfirst($quoteItem['unit']),

				'metadata'=>array(),

				'notes' => array(),

				'product_id' => $quoteItem['product_id']

			);

			
			//$thisItemMetas=$this->QuoteLineItemMeta->find('all',['conditions'=>['quote_item_id'=>$quoteItem['id']]])->toArray();

			$thisItemMetas=$this->OrderLineItemMeta->find('all',['conditions'=>['order_item_id'=>$quoteItem['id']]])->toArray();

			foreach($thisItemMetas as $itemMeta){

				$quoteItems[$quoteItem['id']]['metadata'][$itemMeta['meta_key']]=$itemMeta['meta_value'];

			}
			//			$thisItemNotes=$this->QuoteLineItemNotes->find('all',['conditions'=>['quote_item_id' => $quoteItem['id']],'order'=>['time'=>'ASC']])->toArray();

			$thisItemNotes=$this->OrderLineItemNotes->find('all',['conditions'=>['quote_item_id' => $quoteItem['id']],'order'=>['time'=>'ASC']])->toArray();

			$notenum=1;

			foreach($thisItemNotes as $itemNote){

				$thisNoteUser=$this->Users->get($itemNote['user_id'])->toArray();

				$thisNoteName=$thisNoteUser['first_name'].' '.substr($thisNoteUser['last_name'],0,1);

				$quoteItems[$quoteItem['id']]['notes'][$notenum]=array(

						'visible_to_customer' => $itemNote['visible_to_customer'],

						'time' => $itemNote['time'],

						'user_id'=>$itemNote['user_id'],

						'name'=>$thisNoteName,

						'message' => $itemNote['message']

				);

				$notenum++;

			}



			if(isset($quoteItems[$quoteItem['id']]['metadata']['fabricid']) && floatval($quoteItems[$quoteItem['id']]['metadata']['fabricid']) >0){

				$thisFabric=$this->Fabrics->get($quoteItems[$quoteItem['id']]['metadata']['fabricid'])->toArray();

				

				if($fabricAlias=$this->getFabricAlias($thisFabric['id'],$thisQuote['customer_id'])){

					$thisFabric['alias_name']=$fabricAlias['fabric_name'];

					$thisFabric['alias_color']=$fabricAlias['color'];

				}else{

					$thisFabric['alias_name']=false;

					$thisFabric['alias_color']=false;

				}

				$quoteItems[$quoteItem['id']]['fabricdata']=$thisFabric;

			}

			

			

		}

		

		$this->set('quoteData',$thisQuote);

		$this->set('quoteItems',$quoteItems);

		$this->set('customerData',$customerData);

		

		if($thisQuote['revision'] > 0){

			$revisionAddon='_REV_'.$thisQuote['revision'];

		}else{

			$revisionAddon='';

		}
		//die(print_r($quoteItems));

		$this->viewBuilder()->options([

                'pdfConfig' => [

                    'orientation' => 'landscape',

                    'filename' => 'HCI_Quote_' . $thisQuote['quote_number'].$revisionAddon.'.pdf',

					'title' => 'HCI Quote '.$quoteID

                ]

            ]);

	}

	

	

	public function buildworkorderpdf($quoteID,$types='all'){

		$this->set('types',$types);

		

		$settings=$this->getsettingsarray();

		$this->set('allsettings',$settings);

		$allFabrics=$this->Fabrics->find('all')->toArray();

		$this->set('allFabrics',$allFabrics);

		$thisQuote=$this->Quotes->get($quoteID)->toArray();

		$quoteAuthorData=$this->Users->get($thisQuote['created_by'])->toArray();

		$this->set('quoteAuthorData',$quoteAuthorData);



		$allUsers=array();
		$getAllUsers=$this->Users->find()->toArray();
		foreach($getAllUsers as $userRow){
			$allUsers[$userRow['id']]=$userRow;
		}

		$this->set('allUsers',$allUsers);

		$globalNotes=$this->QuoteNotes->find('all',['conditions' => ['quote_id' => $thisQuote['id']],'order'=>['id'=>'asc']])->toArray();
		$this->set('globalNotes',$globalNotes);

		

		$thisOrderLookup=$this->Orders->find('all',['conditions'=>['quote_id' => $quoteID]])->toArray();

		$this->set('orderData',$thisOrderLookup[0]);

		$allTrackComponents=array();

		$allTrackComponentsFind=$this->TrackSystems->find('all',['conditions'=>['status'=>'Active']])->toArray();

		foreach($allTrackComponentsFind as $componentRow){

			$allTrackComponents[$componentRow['id']]=array(

				'title'=>$componentRow['title'],

				'description'=>$componentRow['description'],

				'price'=>$componentRow['price'],

				'unit' => $componentRow['unit'],

				'inches_equivalent' => $componentRow['inches_equivalent'],

				'system_or_component'=>$componentRow['system_or_component']

			);

		}

		$this->set('componentsList',$allTrackComponents);

		$allimages=array();

		$allimageslookup=$this->LibraryImages->find('all')->toArray();

		foreach($allimageslookup as $imagedata){

			$allimages[$imagedata['id']]=$imagedata;

		}

		$this->set('allimages',$allimages);

		$allLinings=array();

		$allLiningLookups=$this->Linings->find('all')->toArray();

		foreach($allLiningLookups as $lining){

			$allLinings[$lining['id']]=$lining['short_title'];

		}

		$this->set('alllinings',$allLinings);

		

		$GLOBALS['pdfheader']="<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\">

<tr>
<td width=\"11%\" valign=\"top\" align=\"left\"><img src=\"".$_SERVER['DOCUMENT_ROOT']."/webroot/img/android-chrome-144x144.png\" width=\"65\" /></td>
<td width=\"34%\" valign=\"top\" algin=\"left\"><span style=\"font-size:9px; font-family:'Metropolis'; color:#004A87;\"><span style=\"font-family:'Metropolis Semi Bold';\">HC INTERIORS</span><br>".$settings['hci_address_line_1'].". ".$settings['hci_address_line_2']."<br>".$settings['hci_address_city'].", ".$settings['hci_address_state']." ".$settings['hci_address_zipcode']."<Br>Phone: ".$settings['hci_phone_number']."  <span style=\"font-family:'Helvetica';\">&middot;</span>  Fax: ".$settings['hci_fax']."<br>E-mail: <span style=\"color:blue;\">".$quoteAuthorData['email']."</span></span></td>

<td width=\"20%\" valign=\"top\" align=\"center\" style=\"font-size:9px;\">";

		if($thisQuote['status']=='draft'){

			$GLOBALS['pdfheader'] .= "<img src=\"".$_SERVER['DOCUMENT_ROOT']."/webroot/img/pdf-preview.png\" width=\"200\" />";

		}else{

			$GLOBALS['pdfheader'] .= "&nbsp;";

		}

		$GLOBALS['pdfheader'] .= "</td>

<td width=\"35%\" valign=\"top\" align=\"right\" style=\"font-size:9px;\">

<table width=\"100%\" cellpadding=\"3\" border=\"1\" bordercolor=\"#000000\" cellspacing=\"0\" align=\"right\">

	<tr>

		<td style=\"font-size:9px;\" width=\"35%\" align=\"left\" bgcolor=\"#E4EEFA\" style=\"font-size:9px;\">Date:</td>

		<td style=\"font-size:9px;\" width=\"65%\" align=\"left\" style=\"font-size:9px;\">".date('n/j/Y',$thisOrderLookup[0]['created'])."</td>

	</tr>

	<tr>

		<td style=\"font-size:8px;\" width=\"35%\" align=\"left\" bgcolor=\"#E4EEFA\" style=\"font-size:9px;\">Work Order #:</td>

		<td style=\"font-size:8px;\" width=\"65%\" align=\"left\" style=\"font-size:9px;\">".$thisOrderLookup[0]['order_number']."</td>

	</tr>

	<tr>

		<td width=\"35%\" align=\"left\" bgcolor=\"#E4EEFA\" style=\"font-size:9px;\">Project:</td>

		<td width=\"65%\" align=\"left\" style=\"font-size:9px;\">".strtoupper($thisQuote['title'])."</td>

	</tr>

	<tr>

		<td width=\"35%\" align=\"left\" bgcolor=\"#E4EEFA\" style=\"font-size:9px;\">Contains:</td>

		<td width=\"65%\" align=\"left\" style=\"font-size:9px;\">";

		$thisQuoteTypes=$this->getproducttypeslist($thisQuote['id']);

		$contains='';

		foreach($thisQuoteTypes as $type){

			$contains .= $type.', ';

		}

		$GLOBALS['pdfheader'] .= substr($contains,0,(strlen($contains)-2));

		$GLOBALS['pdfheader'] .= "</td>

	</tr>

</table>

</td></tr></table>";

		$thisQuoteItems=$this->QuoteLineItems->find('all',['conditions'=>['quote_id'=>$quoteID],'order'=>['sortorder'=>'asc','id'=>'asc']])->toArray();

		$customerData=$this->Customers->get($thisQuote['customer_id'])->toArray();

		if($thisQuote['contact_id'] > 0){

			$contactData=$this->CustomerContacts->get($thisQuote['contact_id'])->toArray();

		}else{

			$contactData=array();

		}

		

		$quoteItems=array();

		foreach($thisQuoteItems as $quoteItem){

			$quoteItems[$quoteItem['id']]=array(

				'title'=>$quoteItem['title'],

				'description'=>$quoteItem['description'],

				'best_price'=>$quoteItem['best_price'],

				'tier_adjusted_price'=>$quoteItem['tier_adjusted_price'],

				'install_adjusted_price'=>$quoteItem['install_adjusted_price'],

				'rebate_adjusted_price'=>$quoteItem['rebate_adjusted_price'],

				'override_price'=>$quoteItem['override_price'],

				'extended_price' => $quoteItem['extended_price'],

				'qty'=>$quoteItem['qty'],

				'line_item_type'=>$quoteItem['line_item_type'],

				'room_number'=>$quoteItem['room_number'],

				'line_number'=>$quoteItem['line_number'],

				'parent_line' => $quoteItem['parent_line'],

				'sortorder'=>$quoteItem['sortorder'],

				'internal_line'=>$quoteItem['internal_line'],

				'product_type'=>$quoteItem['product_type'],

				'status'=>$quoteItem['status'],

				'unit'=>ucfirst($quoteItem['unit']),

				'primarykey'=>$quoteItem['id'],

				'metadata'=>array(),

				'imagesrc'=> '',

				'notesdata' => array()

			);

			
			$thisItemNotes=$this->QuoteLineItemNotes->find('all',['conditions'=>['quote_item_id' => $quoteItem['id']],'order'=>['id'=>'asc']])->toArray();
			foreach($thisItemNotes as $itemNote){
				$quoteItems[$quoteItem['id']]['notesdata'][$itemNote['id']]=$itemNote;
			}


			$thisItemMetas=$this->QuoteLineItemMeta->find('all',['conditions'=>['quote_item_id'=>$quoteItem['id']]])->toArray();

			foreach($thisItemMetas as $itemMeta){

				$quoteItems[$quoteItem['id']]['metadata'][$itemMeta['meta_key']]=$itemMeta['meta_value'];

			}

			

			

			if(!isset($quoteItems[$quoteItem['id']]['metadata']['libraryimageid']) || $quoteItems[$quoteItem['id']]['metadata']['libraryimageid'] == 0){

				$quoteItems[$quoteItem['id']]['imagesrc'] = $_SERVER['DOCUMENT_ROOT']."/webroot/img/noimage.png";

			}else{

				$libraryImage=$this->LibraryImages->get($quoteItems[$quoteItem['id']]['metadata']['libraryimageid'])->toArray();

				$quoteItems[$quoteItem['id']]['imagesrc'] = $_SERVER['DOCUMENT_ROOT']."/webroot/img/library/".$libraryImage['filename'];

			}

			

			

			if(isset($quoteItems[$quoteItem['id']]['metadata']['fabricid']) && floatval($quoteItems[$quoteItem['id']]['metadata']['fabricid']) >0){

				$thisFabric=$this->Fabrics->get($quoteItems[$quoteItem['id']]['metadata']['fabricid'])->toArray();

				$quoteItems[$quoteItem['id']]['fabricdata']=$thisFabric;

			}

			

			

		}

		

		$this->set('quoteData',$thisQuote);

		$this->set('quoteItems',$quoteItems);

		$this->set('customerData',$customerData);

		$this->set('contactData',$contactData);

		

		$this->viewBuilder()->options([

                'pdfConfig' => [

                    'orientation' => 'landscape',

                    'filename' => 'HCI_Quote_' . $quoteID,

					'title' => 'HCI Quote '.$quoteID

                ]

            ]);

	}

	

	

	public function buildquotexls($quoteID){

		$thisQuote=$this->Quotes->get($quoteID);

		

		

	}

	

	

	

	public function lookuppricelist($quoteID,$type,$fabricid,$width,$length,$meshorwelts,$wttype=false,$lining=false,$pairorpanel=false){

		$this->autoRender=false;

		$db = ConnectionManager::get('default');

		$out=array();

		$settings=$this->getsettingsarray();

		switch($type){

			case "cc":

				$allSizes=$this->CubicleCurtainSizes->find('all',['order'=>['width'=>'asc','length'=>'asc']])->toArray();

				//look up a price with a selected width and entered length, find correct range and return price

				$thisFabric=$this->Fabrics->get($fabricid)->toArray();

				$conditions=array();

				$conditions += array('fabric_name'=>$thisFabric['fabric_name'],'available_colors LIKE' => '%"'.str_replace(" ","_",$thisFabric['color']).'"%');

				if($meshorwelts=="No Mesh"){

					$conditions += array('has_mesh'=>'0');

				}else{

					$conditions += array('has_mesh'=>'1');

				}

				$lookupCCproduct=$this->CubicleCurtains->find('all',['conditions'=>$conditions])->toArray();

				

				if(count($lookupCCproduct) == 0 && $meshorwelts=='No Mesh'){

					//no defined grid for this... do the 20% thing

					$lookupCCproduct=$this->CubicleCurtains->find('all',['conditions'=>['fabric_name'=>$thisFabric['fabric_name'],'available_colors LIKE' => '%"'.$thisFabric['color'].'"%']])->toArray();

				}

				

				$matched=0;

				

				//print_r($lookupCCproduct);exit;

				foreach($lookupCCproduct as $product){

					$out['specialpricing']=0;

					if($quoteID != 'undefined' && is_numeric($quoteID) && $quoteID != 0){
						$thisQuote=$this->Quotes->get($quoteID)->toArray();
						$customerID=$thisQuote['customer_id'];
					}else{
						//this is just a standalone price list lookup
						$customerID=$_REQUEST['lookupcustomerid'];
					}

					

					//let's check for a special price first

					//look up exact size for this product type

					$ccSizeCheck=$this->CubicleCurtainSizes->find('all',['conditions' => ['width' => $width, 'length' => $length]])->toArray();

					if(count($ccSizeCheck) == 1){

						//check for a special price on this exact size

						foreach($ccSizeCheck as $exactSize){

							if($overridePrice=$this->customerHasPriceListOverride($customerID,$type,$product['id'],$exactSize['id'],$thisFabric['color'])){

								$out['price']=number_format($overridePrice,2,'.','');

								$out['specialpricing']=1;

								$out['sizeID']=$exactSze['id'];

								//$availableSizes=$this->CcDataMap->find('all',['joins' => [['table' => 'cubicle_curtain_sizes', 'type'=>'LEFT', 'conditions' => ['size_id = cubicle_curtain_sizes.id']]], 'conditions'=>['cc_id'=>$product['id']],'order'=>['']])->toArray();
								
								$query="SELECT a.price,a.weight,a.yards,a.difficulty,a.labor_lf,a.cc_id,a.size_id, b.length as cclength, b.width as ccwidth FROM cc_data_map a, cubicle_curtain_sizes b WHERE a.cc_id=".$product['id']." AND a.size_id = b.id AND b.width=".$width." AND b.length >= ".$length." ORDER BY b.width ASC, b.length ASC";

								//mail("dallasisp@gmail.com","data",$query);

								$queryRun=$db->execute($query);
								$availableSizes=$queryRun->fetchAll('assoc');
								

								foreach($availableSizes as $size){

									foreach($allSizes as $sizeRow){

										if($sizeRow['id']==$size['size_id']){

											if(floatval($sizeRow['width'])==$width){

												if(floatval($sizeRow['length']) >= $length && $matched==0){

													$out['yards']=$size['yards'];

													$out['difficulty']=$size['difficulty'];

													$out['topwidthsl']=$size['topwidthsl'];

													$out['dropwidthsl']=$size['dropwidthsl'];

													$out['labor_lf']=$size['labor_lf'];

													$out['weight'] = $size['weight'];

													$out['ccid']=$size['cc_id'];

													$out['sizeID']=$sizeRow['id'];

													$matched++;

												}

											}

										}

									}

								}

							}

						}

					}

					//$availableSizes=json_decode($product['available_sizes'],true);

					//$availableSizes=$this->CcDataMap->find('all',['conditions'=>['cc_id'=>$product['id']]])->toArray();
					
					$query="SELECT a.price,a.weight,a.yards,a.difficulty,a.labor_lf,a.cc_id,a.size_id, b.length as cclength, b.width as ccwidth FROM cc_data_map a, cubicle_curtain_sizes b WHERE a.cc_id=".$product['id']." AND a.size_id = b.id AND b.width=".$width." AND b.length >= ".$length." ORDER BY b.width ASC, b.length ASC";

					//mail("dallasisp@gmail.com","data",$query);
					$queryRun=$db->execute($query);
					$availableSizes=$queryRun->fetchAll('assoc');
					

					foreach($availableSizes as $size){

						//find this size dimensions

						foreach($allSizes as $sizeRow){

							if($sizeRow['id']==$size['size_id']){

								if(floatval($sizeRow['width'])==$width){

									if(floatval($sizeRow['length']) >= $length && $matched==0){

										

										if($out['specialpricing'] == 0){

											if(urldecode($meshorwelts)=='No Mesh' && $product['has_mesh'] == '1'){

												$nomeshincrease=(floatval($size['price']) + (floatval($size['price']) * (floatval($settings['percent_increase_price_list_cc_without_mesh'])/100) ) );

												$out['price']=number_format($nomeshincrease,2,'.','');

											}else{

												$out['price']=number_format($size['price'],2,'.','');

											}

											$out['sizeID']=$sizeRow['id'];

										}

										

										

										$out['yards']=$size['yards'];

										$out['difficulty']=$size['difficulty'];

										$out['topwidthsl']=$size['topwidthsl'];

										$out['dropwidthsl']=$size['dropwidthsl'];

										$out['labor_lf']=$size['labor_lf'];

										$out['weight'] = $size['weight'];

										$out['ccid']=$size['cc_id'];

										$matched++;

									}

								}

							}

						}

					}

				}

			break;

			case "wt":

				$allSizes=$this->WindowTreatmentSizes->find('all')->toArray();

				$db = ConnectionManager::get('default');

				

				//look up a price with a selected width and entered length, find correct range and return price

				$thisFabric=$this->Fabrics->get($fabricid)->toArray();

				

				$conditions=array();

				$conditions += array('fabric_name'=>$thisFabric['fabric_name'],'available_colors LIKE' => '%"'.$thisFabric['color'].'"%');

				if($meshorwelts=="1"){

					$conditions += array('has_welt'=>'1');

				}else{

					$conditions += array('has_welt'=>'0');

				}

				//if($wttype){

				$conditions += array('wt_type' => $wttype);

				//}

				

				if($lining){

					$conditions += array('lining_option'=>$lining);

				}

				

				$lookupWTproduct=$this->WindowTreatments->find('all',['conditions'=>$conditions])->toArray();

				

				$matched=0;

				//print_r($lookupWTproduct);exit;

				

				

				

				foreach($lookupWTproduct as $product){

					$query="SELECT a.price,a.weight,a.yards,a.difficulty,a.labor_lf,a.id AS wtdataid, b.id as sizeid FROM wt_data_map a, window_treatment_sizes b WHERE a.wt_id=".$product['id']." AND a.size_id = b.id AND b.width=".$width." AND b.length >= ".$length." ORDER BY b.length ASC LIMIT 1";

					//echo $query;exit;

					$queryRun=$db->execute($query);

					$sizeMatches=$queryRun->fetchAll('assoc');

					

					foreach($sizeMatches as $size){

						$out['price']=number_format($size['price'],2,'.','');

						$out['specialpricing']=0;



						//see if this customer has a pricing override

						$thisQuote=$this->Quotes->get($quoteID)->toArray();

						$customerID=$thisQuote['customer_id'];

						if($overridePrice=$this->customerHasPriceListOverride($customerID,$type,$product['id'],$size['sizeid'],$thisFabric['color'])){

							$out['price'] = number_format($overridePrice,2,'.','');

							$out['specialpricing']=1;

						}



						$out['sizeID']=$size['sizeid'];

						$out['yards']=$size['yards'];

						$out['difficulty']=$size['difficulty'];

						if($wttype=='Pinch Pleated Drapery' && $pairorpanel=='pair'){

							if(intval($size['labor_lf']) % 2 == 1){

								$out['laborlf']=(intval($size['labor_lf'])+1);

							}else{

								$out['laborlf']=$size['labor_lf'];

							}

						}else{

							$out['laborlf']=$size['labor_lf'];

						}

						$out['weight'] = $size['weight'];

						$out['wtid']=$product['id'];



					}

				}

			break;

		}

		echo json_encode($out);

	}

	

	public function getnextlinesortorder($quoteID){
		
		$currentLastLineNumber=$this->QuoteLineItems->find('all',['conditions'=>['quote_id' => $quoteID], ['order' => ['sort_order' => 'DESC']]])->toArray();
		if(count($currentLastLineNumber) == 0){
			return 1;
		}else{
		    return (count($currentLastLineNumber)+1);
		}
		
	}

	

	public function getnextlinenumber($quoteID){
		//find highest existing line number
		$currentBiggest=$this->QuoteLineItems->find('all',['conditions'=>['quote_id'=>$quoteID],'order'=>['line_number'=>'DESC']])->limit(1)->toArray();
		if(count($currentBiggest)==0){
			return 1;
		}else{
			foreach($currentBiggest as $entry){
				return (intval($entry['line_number'])+1);
			}
		}
	}
	

	

	public function changelinetointernal($lineid,$newvalue){

		$this->autoRender=false;

		$lineTable=TableRegistry::get('QuoteLineItems');

		$thisLine=$lineTable->get($lineid);

		$thisLine->internal_line=$newvalue;

		$lineTable->save($thisLine);

		echo "OK";

	}

	
	
	public function changelinetally($lineid,$newvalue){

		$this->autoRender=false;

		$lineTable=TableRegistry::get('QuoteLineItems');

		$thisLine=$lineTable->get($lineid);

		$thisLine->enable_tally=$newvalue;

		$lineTable->save($thisLine);

		echo "OK";

	}
	
	public function addservicetoquote($quoteID,$serviceID,$priceValue,$qty,$internal=0,$fromLine=0,$location=''){

        $thisQuote=$this->Quotes->get($quoteID)->toArray();

		$location=$this->cleanparameterreplacements($location);

		$thisService=$this->Services->get($serviceID)->toArray();

		$this->autoRender=false;

		$lineTable=TableRegistry::get('QuoteLineItems');

		$lineMetaTable=TableRegistry::get('QuoteLineItemMeta');

		$newLineItem=$lineTable->newEntity();

		$newLineItem->quote_id=$quoteID;

		$newLineItem->type='lineitem';

		$newLineItem->status='included';

		$newLineItem->product_type='services';
		
		$newLineItem->product_class=6;
		
		$newLineItem->product_class=6;
		$newLineItem->product_subclass=$thisService['subclass'];
		
		if($serviceID==4){
		    $newLineItem->enable_tally=0;
		}

		$newLineItem->room_number = $location;

		$newLineItem->product_id=$serviceID;

		$newLineItem->title=$thisService['title'];
		
		$newLineItem->override_active = 0;

		$newLineItem->best_price=$priceValue;

		$newLineItem->qty=$qty;

		$newLineItem->lineitemtype='simple';
		if($internal){
			//use same line number as the parent
			$newLineItem->line_number = $fromLine;

		}else{

			//find new line number
			$newLineItem->line_number=$this->getnextlinenumber($quoteID);

		}
		$newLineItem->sortorder=$this->getNewItemSortOrderNumber($quoteID);
		/**PPSA-40 start **/
        $newLineItem->created=time();
        /**PPSA-40 end **/
		if($lineTable->save($newLineItem)){
		    
		    //if this is EDIT ORDER function, add a line to Quote Item Add Log so it will know what to do when applying changes
            if($thisQuote['status'] == 'editorder'){
                $newAddLog=$this->QuoteItemAddLog->newEntity();
                $newAddLog->original_quote_id = $thisQuote['parent_quote'];
                $newAddLog->revision_id=$thisQuote['id'];
                $newAddLog->line_item_id=$newLineItem->id;
                $newAddLog->addtime=time();
                $newAddLog->line_number=$newLineItem->line_number;
                $this->QuoteItemAddLog->save($newAddLog);
            }

            $this->logActivity($_SERVER['REQUEST_URI'],'Added service "'.$thisService['title'].'" as Line #'.$newLineItem->line_number.' to quote '.$thisQuote['quote_number'],$this->Auth->user('id'),json_encode($this->request));
            
			if($this->recalculatequoteadjustments($quoteID)){

				$this->updatequotemodifiedtime($quoteID);

				//meta data time

				$newLineItemMeta=$lineMetaTable->newEntity();
				
				$newLineItemMeta->quote_item_id=$newLineItem->id;

				$newLineItemMeta->meta_key='lineitemtype';

				$newLineItemMeta->meta_value='simpleproduct';

				$lineMetaTable->save($newLineItemMeta);

				$newLineItemMeta=$lineMetaTable->newEntity();

				$newLineItemMeta->quote_item_id=$newLineItem->id;

				$newLineItemMeta->meta_key='price';

				$newLineItemMeta->meta_value=$priceValue;

				$lineMetaTable->save($newLineItemMeta);

			}
		}
		if($internal == 0){

			echo "OK";exit;

		}else{

			return true;

		}

	}

	
	
	/*public function converttoorder($quoteID)
	{
		//$this->autoRender=false;

		if($this->request->data){

			//get all quote data
			$this->autoRender=false;
			//print_r($this->request->data);exit;
			$thisQuote=$this->Quotes->get($quoteID)->toArray();

            if($thisQuote['order_id'] > 0){
                $this->Flash->error('This quote is already converted to an order. Action aborted.');
                return $this->redirect('/orders/editlines/'.$thisQuote['order_id']);
                exit;
            }
			//create Order

			$orderTable=TableRegistry::get('Orders');
			$newOrder=$orderTable->newEntity();
			$newOrder->customer_id=$thisQuote['customer_id'];
			
			$newOrder->type_id = $this->request->data['type_id'];
			
			$newOrder->status='Processing';
			$newOrder->order_total=$thisQuote['quote_total'];
			$newOrder->po_number=$this->request->data['po_number'];
			$newOrder->project_manager_id = $this->request->data['project_manager_id'];
			$newOrder->quote_id=$quoteID;
			$newOrder->contact_id = $thisQuote['contact_id'];
			//$newOrder->created = time();
			
			$newOrder->created = strtotime($this->request->data['created'].' 11:00:00');
			
			$newOrder->user_id=$this->Auth->user('id');
			$newOrder->facility = $this->request->data['facility'];
			$newOrder->shipping_address_1 = $this->request->data['shipping_address_1'];
			$newOrder->shipping_address_2 = $this->request->data['shipping_address_2'];
			$newOrder->shipping_city = $this->request->data['shipping_city'];
			$newOrder->shipping_state = $this->request->data['shipping_state'];
			$newOrder->shipping_zipcode = $this->request->data['shipping_zipcode'];
			$newOrder->attention=$this->request->data['attention'];
			$newOrder->shipping_instructions = $this->request->data['shipping_instructions'];
			$newOrder->shipping_method_id = $this->request->data['shipping_method_id'];
			$newOrder->order_number=$this->getNextAvailableOrderNumber();

			if($this->request->data['hasduedate'] == '1'){
				$newOrder->due = strtotime($this->request->data['due'].' 15:30:00');
			}

			$orderItemsTable=TableRegistry::get('OrderItems');
			$orderItemStatusTable=TableRegistry::get('OrderItemStatus');
			$newOrder->cc_qty = $this->getTypeQty($quoteID,'cc');
			$newOrder->cc_lf = $this->getTypeLF($quoteID,'cc');
			$newOrder->cc_diff = $this->getTypeDiff($quoteID,'cc');
			$newOrder->cc_dollar = $this->getTypeDollars($quoteID,'cc');
			$newOrder->track_lf = $this->getTypeLF($quoteID,'track');
			$newOrder->track_dollar = $this->getTypeDollars($quoteID,'track');
			$newOrder->bs_qty = $this->getTypeQty($quoteID,'bs');
			$newOrder->bs_diff = $this->getTypeDiff($quoteID,'bs');
			$newOrder->bs_dollar = $this->getTypeDollars($quoteID,'bs');
			$newOrder->drape_qty = $this->getTypeQty($quoteID,'drapes');
			$newOrder->drape_widths = $this->getTypeWidths($quoteID,'drapes');
			$newOrder->drape_diff = $this->getTypeDiff($quoteID,'drapes');
			$newOrder->drape_dollar = $this->getTypeDollars($quoteID,'drapes');
			$newOrder->tt_qty = $this->getTypeQty($quoteID,'tt');
			$newOrder->tt_lf = $this->getTypeLF($quoteID,'tt');
			$newOrder->tt_diff = $this->getTypeDiff($quoteID,'tt');
			
			$newOrder->val_qty = $this->getTypeQty($quoteID,'val');
			$newOrder->val_lf = $this->getTypeLF($quoteID,'val');
			
			$newOrder->corn_qty = $this->getTypeQty($quoteID,'corn');
			$newOrder->corn_lf = $this->getTypeLF($quoteID,'corn');
			
			$newOrder->val_dollar = $this->getTypeDollars($quoteID,'val');
			$newOrder->corn_dollar = $this->getTypeDollars($quoteID,'corn');
			$newOrder->wt_hw_qty = $this->getTypeQty($quoteID,'wthw');
			$newOrder->wt_hw_dollar = $this->getTypeDollars($quoteID,'wthw');
			$newOrder->blinds_qty = $this->getTypeQty($quoteID,'blinds');
			$newOrder->blinds_dollar = $this->getTypeDollars($quoteID,'blinds');
			$newOrder->catchall_qty = $this->getTypeQty($quoteID,'catchall');
			$newOrder->measure_dollars = $this->getTypeDollars($quoteID,'measure');
			$newOrder->install_dollars=$this->getTypeDollars($quoteID,'install');
			$newOrder->swtmisc_qty = $this->getTypeQty($quoteID,'swtmisc');
			$newOrder->hwcctrack_dollars = $this->getTypeDollars($quoteID,'');
			$newOrder->misc_dollars=$this->getTypeDollars($quoteID,'misc');
			
			
			$newOrder->status='Pre-Production';
			if($orderTable->save($newOrder)){
			    	$originalOrder = $orderTable->get($newOrder->id)->toArray();
			        $workOrderTable=TableRegistry::get('WorkOrders');
			        $copyOrder = $workOrderTable->newEntity();
                    $copyOrder = $workOrderTable->patchEntity($copyOrder, $originalOrder);
            
                     //unset($copyOrder['id']);
                    // Unset or modify all others what you need

			        $workOrderTable->save($copyOrder);
			    //update the order id and order number on the quote row
			    $thisQuoteRow=$this->Quotes->get($quoteID);
			    $thisQuoteRow->order_id=$newOrder->id;
			    $thisQuoteRow->order_number=$newOrder->order_number;
			    $this->Quotes->save($thisQuoteRow);
			    
				//get all quote line items
				$thisQuoteItems=$this->QuoteLineItems->find('all',['conditions'=>['quote_id'=>$quoteID]])->toArray();
				foreach($thisQuoteItems as $itemData){
					//create as Order Line Items
					$newOrderLineItem=$orderItemsTable->newEntity();
					$newOrderLineItem->order_id=$newOrder->id;
					$newOrderLineItem->quote_line_item_id=$itemData['id'];
					$newOrderLineItem->quote_id=$quoteID;
					if($orderItemsTable->save($newOrderLineItem)){
						//saved
						$originalOrderItem = $orderItemsTable->get($newOrder->id)->toArray();
			        $workOrderItemsTable=TableRegistry::get('WorkOrderItems');
			        $copyOrderItem = $workOrderItemsTable->newEntity();
                    $copyOrderItem = $workOrderItemsTable->patchEntity($copyOrderItem, $originalOrderItem);
            
                    // unset($originalOrderItem['id']);
                    // Unset or modify all others what you need

			        $workOrderItemsTable->save($copyOrderItem);
						//create a Status entry for this line item
						$newOrderItemStatus=$orderItemStatusTable->newEntity();
						$newOrderItemStatus->order_line_number=$itemData['line_number'];
						$newOrderItemStatus->order_item_id=$newOrderLineItem->id;
						$newOrderItemStatus->status='Not Started';
						$newOrderItemStatus->time=time();
						$newOrderItemStatus->work_order_id = $newOrder->id;
						$newOrderItemStatus->user_id=$this->Auth->user('id');
						$newOrderItemStatus->qty_involved=$itemData['qty'];
						$orderItemStatusTable->save($newOrderItemStatus);
						
						$originalOrderItemStatus = $orderItemStatusTable->get($newOrderItemStatus->id)->toArray();
			        $WorkOrderItemStatusTable=TableRegistry::get('WorkOrderItemStatus');
			        $copyOrderItemStatus = $workOrderItemsTable->newEntity();
                    $copyOrderItemStatus = $workOrderItemsTable->patchEntity($copyOrderItemStatus, $originalOrderItemStatus);
            
                     unset($originalOrderItemStatus['id']);
                    // Unset or modify all others what you need

			        $WorkOrderItemStatusTable->save($copyOrderItemStatus);
						

					 $thisQuoteLineMeta=$this->QuoteLineItemMeta->find('all',['conditions' => ['quote_item_id'=>	$newOrderLineItem->quote_line_item_id]])->toArray();
					 	foreach($thisQuoteLineMeta as $itemMetaData){
					 	    $orderLineItemMeta=TableRegistry::get('OrderLineItemMeta');
					 	  $newOrderLineMeta=  $orderLineItemMeta->newEntity();
						$newOrderLineMeta->meta_key=$itemMetaData['meta_key'];
						$newOrderLineMeta->meta_value=$itemMetaData['meta_value'];
						$newOrderLineMeta->order_line_item_id = $itemMetaData['quote_line_item_id'];
						$orderLineItemMeta->save($newOrderLineMeta);
						
						$workorderLineItemMeta=TableRegistry::get('WorkOrderLineItemMeta');
						 $newworkOrderLineMeta=  $workorderLineItemMeta->newEntity();
						$newworkOrderLineMeta->meta_key=$itemMetaData['meta_key'];
						$newworkOrderLineMeta->meta_value=$itemMetaData['meta_value'];
						$newworkOrderLineMeta->work_order_id = $itemMetaData['quote_line_item_id'];
						$workorderLineItemMeta->save($newworkOrderLineMeta);
					 	}
					$thisQuoteLineNotes=$this->QuoteLineItemNotes->find('all',['conditions'=>['quote_item_id' => $newOrderLineItem->quote_line_item_id]])->toArray();

					foreach($thisQuoteLineNotes as $lineNote){
					    	$OrderLineItemNotesTable=TableRegistry::get('OrderLineItemNotes');
							$newOrderLineItemNotesTable=$OrderLineItemNotesTable->newEntity();
						
						$newOrderLineItemNotesTable->user_id=$lineNote['user_id'];
						$newOrderLineItemNotesTable->time=$lineNote['time'];
						$newOrderLineItemNotesTable->message=$lineNote['message'];
						$newOrderLineItemNotesTable->quote_id=$thisQuoteRow['id'];
						$newOrderLineItemNotesTable->quote_item_id=$itemData['id'];
						$newOrderLineItemNotesTable->visible_to_customer=$lineNote['visible_to_customer'];
						$OrderLineItemNotesTable->save($newOrderLineItemNotesTable);
						
						$WorkOrderLineItemNotesTable=TableRegistry::get('WorkOrderLineItemNotes');
						$newWorkOrderLineItemNotesTable=$WorkOrderLineItemNotesTable->newEntity();
						
						$newWorkOrderLineItemNotesTable->user_id=$lineNote['user_id'];
						$newWorkOrderLineItemNotesTable->time=$lineNote['time'];
						$newWorkOrderLineItemNotesTable->message=$lineNote['message'];
						$newWorkOrderLineItemNotesTable->quote_id=$thisQuoteRow['id'];
						$newWorkOrderLineItemNotesTable->quote_item_id=$itemData['id'];
						$newWorkOrderLineItemNotesTable->visible_to_customer=$lineNote['visible_to_customer'];
						$newWorkOrderLineItemNotesTable->save($newOrderLineItemNotesTable);
					} 
					}
				}
				$this->savetoOrderWorkOrderLineItemTables('',$quoteID,$newOrder->id);
				$this->auditOrderItemStatuses($newOrder->id);
				$this->auditWorkOrderItemStatuses($quoteID);
				
				$this->updatefabricinventoryfororder($newOrder->id);

				$this->logActivity($_SERVER['REQUEST_URI'],'Converted Quote '.$thisQuote['quote_number'].' to Order '.$newOrder->order_number);
				$this->logActivity($_SERVER['REQUEST_URI'],'Converted Quote '.$thisQuote['quote_number'].' to WorkOrders '.$newOrder->order_number);

				$this->Flash->success('Successfully created new Order #'.$newOrder->order_number);

				return $this->redirect('/orders/');

			}

		}else{
		    
		    $allTypes=$this->QuoteTypes->find('all')->toArray();
            $this->set('allTypes',$allTypes);
            
            
			$thisQuote=$this->Quotes->get($quoteID)->toArray();
			$this->set('quoteData',$thisQuote);
			
			if($thisQuote['order_id'] > 0){
                $this->Flash->error('This quote is already converted to an order. Action aborted.');
                return $this->redirect('/orders/editlines/'.$thisQuote['order_id']);
                exit;
            }
			
			$allShippingMethods = $this->ShippingMethods->find('all')->toArray();
			$availableShippingMethods=array();
			foreach($allShippingMethods as $method){
				$availableShippingMethods[$method['id']]=$method['name'];
			}
			$this->set('availableShippingMethods',$availableShippingMethods);

			$thisCustomer=$this->Customers->find('all',['conditions'=>['id'=>$thisQuote['customer_id']]])->toArray();;
			//get($thisQuote['customer_id'])->toArray();
			$this->set('customerData',$thisCustomer);
			
			$allUsers=$this->Users->find('all',['conditions' => ['status'=>'Active'],'order'=>['last_name'=>'asc','first_name'=>'asc']])->toArray();
			$this->set('allUsers',$allUsers);
		}

	}
*/
public function converttoorder($quoteID){
	//$this->autoRender=false;

	if($this->request->data){

		//get all quote data
		$this->autoRender=false;
		//print_r($this->request->data);exit;
		$thisQuote=$this->Quotes->get($quoteID)->toArray();

		if($thisQuote['order_id'] > 0){
			$this->Flash->error('This quote is already converted to an order. Action aborted.');
			return $this->redirect('/orders/editlines/'.$thisQuote['order_id']);
			exit;
		}
		//create Order

		$orderTable=TableRegistry::get('Orders');
		$newOrder=$orderTable->newEntity();
		$newOrder->customer_id=$thisQuote['customer_id'];
		
		$newOrder->type_id = $this->request->data['type_id'];
		
		$newOrder->status='Processing';
		$newOrder->order_total=$thisQuote['quote_total'];
		$newOrder->po_number=$this->request->data['po_number'];
		$newOrder->project_manager_id = $this->request->data['project_manager_id'];
		$newOrder->quote_id=$quoteID;
		$newOrder->contact_id = $thisQuote['contact_id'];
		//$newOrder->created = time();
		
		$newOrder->created = strtotime($this->request->data['created'].' 11:00:00');
		
		$newOrder->user_id=$this->Auth->user('id');
		$newOrder->facility = $this->request->data['facility'];
		$newOrder->shipping_address_1 = $this->request->data['shipping_address_1'];
		$newOrder->shipping_address_2 = $this->request->data['shipping_address_2'];
		$newOrder->shipping_city = $this->request->data['shipping_city'];
		$newOrder->shipping_state = $this->request->data['shipping_state'];
		$newOrder->shipping_zipcode = $this->request->data['shipping_zipcode'];
		$newOrder->attention=$this->request->data['attention'];
		$newOrder->shipping_instructions = $this->request->data['shipping_instructions'];
		$newOrder->shipping_method_id = $this->request->data['shipping_method_id'];
		$newOrder->attention=$this->request->data['attention'];

		$newOrder->userType = 'exisiting';
		$newOrder->facility_id = 3;
		$newOrder->shipto_id = 2;


			/**PPSASCRUM-7 Start **//*
		$newOrder->userType = ($this->request->data['userAddressesSelection'] == 'new') ? 'exisiting': $this->request->data['userAddressesSelection'] ;
		if($this->request->data['userAddressesSelection'] == 'default') {
			$newOrder->facility_id = $this->request->data['facilitySelected'];
	
			$facilityData=$this->Facility->find('all', ['conditions'=>['id'=>$this->request->data['facilitySelected']]])->toArray();

			$newOrder->shipto_id = $facilityData['default_address'];//$this->request->data['default_address'];
		}else if($this->request->data['userAddressesSelection'] == 'exisiting') {
				$newOrder->facility_id = '';
	
			$newOrder->shipto_id = $this->request->data['addressSelected'];
		} else if($this->request->data['userAddressesSelection'] ==  "customer") {
			$newOrder->facility_id = '';
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
		if(!empty($this->request->data['facilityAttention']) && !empty($this->request->data['allfacility'])) {
			$faciltiyTable=TableRegistry::get('Facility');
			$thisfaciltiy=$faciltiyTable->get($this->request->data['facilitySelected']);
			$thisfaciltiy->attention=$this->request->data['facilityAttention'];
			$faciltiyTable->save($thisfaciltiy);
			$newOrder->facility=$thisfaciltiy->facility_name;

		}
*/
		/** PPSASCRUM-7 ENd **/
		
		$newOrder->order_number=$this->getNextAvailableOrderNumber();

		if($this->request->data['hasduedate'] == '1'){
			$newOrder->due = strtotime($this->request->data['due'].' 15:30:00');
		}

		$orderItemsTable=TableRegistry::get('OrderItems');
		$orderItemStatusTable=TableRegistry::get('OrderItemStatus');
		$newOrder->cc_qty = $this->getTypeQty($quoteID,'cc');
		$newOrder->cc_lf = $this->getTypeLF($quoteID,'cc');
		$newOrder->cc_diff = $this->getTypeDiff($quoteID,'cc');
		$newOrder->cc_dollar = $this->getTypeDollars($quoteID,'cc');
		$newOrder->track_lf = $this->getTypeLF($quoteID,'track');
		$newOrder->track_dollar = $this->getTypeDollars($quoteID,'track');
		$newOrder->bs_qty = $this->getTypeQty($quoteID,'bs');
		$newOrder->bs_diff = $this->getTypeDiff($quoteID,'bs');
		$newOrder->bs_dollar = $this->getTypeDollars($quoteID,'bs');
		$newOrder->drape_qty = $this->getTypeQty($quoteID,'drapes');
		$newOrder->drape_widths = $this->getTypeWidths($quoteID,'drapes');
		$newOrder->drape_diff = $this->getTypeDiff($quoteID,'drapes');
		$newOrder->drape_dollar = $this->getTypeDollars($quoteID,'drapes');
		$newOrder->tt_qty = $this->getTypeQty($quoteID,'tt');
		$newOrder->tt_lf = $this->getTypeLF($quoteID,'tt');
		$newOrder->tt_diff = $this->getTypeDiff($quoteID,'tt');
		
		$newOrder->val_qty = $this->getTypeQty($quoteID,'val');
		$newOrder->val_lf = $this->getTypeLF($quoteID,'val');
		
		$newOrder->corn_qty = $this->getTypeQty($quoteID,'corn');
		$newOrder->corn_lf = $this->getTypeLF($quoteID,'corn');
		
		$newOrder->val_dollar = $this->getTypeDollars($quoteID,'val');
		$newOrder->corn_dollar = $this->getTypeDollars($quoteID,'corn');
		$newOrder->wt_hw_qty = $this->getTypeQty($quoteID,'wthw');
		$newOrder->wt_hw_dollar = $this->getTypeDollars($quoteID,'wthw');
		$newOrder->blinds_qty = $this->getTypeQty($quoteID,'blinds');
		$newOrder->blinds_dollar = $this->getTypeDollars($quoteID,'blinds');
		$newOrder->catchall_qty = $this->getTypeQty($quoteID,'catchall');
		$newOrder->measure_dollars = $this->getTypeDollars($quoteID,'measure');
		$newOrder->install_dollars=$this->getTypeDollars($quoteID,'install');
		$newOrder->swtmisc_qty = $this->getTypeQty($quoteID,'swtmisc');
		//$newOrder->hwcctrack_dollars = $this->getTypeDollars($quoteID,'');
		$newOrder->misc_dollars=$this->getTypeDollars($quoteID,'misc');
		
		
		$newOrder->status='Pre-Production';
		//print_r($newOrder);//die();
		if($orderTable->save($newOrder)){

			//saved
			$originalOrder = $orderTable->get($newOrder->id)->toArray();
			        $workOrderTable=TableRegistry::get('WorkOrders');
			        $copyOrder = $workOrderTable->newEntity();
                    $copyOrder = $workOrderTable->patchEntity($copyOrder, $originalOrder);
            
                     //unset($copyOrder['id']);
                    // Unset or modify all others what you need

			        $workOrderTable->save($copyOrder);


				//update the order id and order number on the quote row
			$thisQuoteRow=$this->Quotes->get($quoteID);
			$thisQuoteRow->order_id=$newOrder->id;
			$thisQuoteRow->order_number=$newOrder->order_number;
			$this->Quotes->save($thisQuoteRow);
			
			//get all quote line items
			$thisQuoteItems=$this->QuoteLineItems->find('all',['conditions'=>['quote_id'=>$quoteID]])->toArray();
			foreach($thisQuoteItems as $itemData){
				$this->savetoOrderLineItemTables($itemData['id'], $quoteID,$newOrder->id, $table='orders' );
				$this->savetoOrderLineItemTables($itemData['id'], $quoteID,$newOrder->id, $table='work_orders' );
				
				//create as Order Line Items
				$newOrderLineItem=$orderItemsTable->newEntity();
				$newOrderLineItem->order_id=$newOrder->id;
				$newOrderLineItem->quote_line_item_id=$itemData['id'];
				$newOrderLineItem->quote_id=$quoteID;
				if($orderItemsTable->save($newOrderLineItem)){

					$this->savetoWorkOrderItemsTables($newOrderLineItem->id);
					
					//saved
					//create a Status entry for this line item
					$newOrderItemStatus=$orderItemStatusTable->newEntity();
					$newOrderItemStatus->order_line_number=$itemData['line_number'];
					$newOrderItemStatus->order_item_id=$newOrderLineItem->id;
					$newOrderItemStatus->status='Not Started';
					$newOrderItemStatus->time=time();
					$newOrderItemStatus->work_order_id = $newOrder->id;
					$newOrderItemStatus->user_id=$this->Auth->user('id');
					$newOrderItemStatus->qty_involved=$itemData['qty'];
					$orderItemStatusTable->save($newOrderItemStatus);
					$this->savetoWorkOrderItemStatusTables($newOrderItemStatus->id);
					
				}
			}
			
			
			$this->savetoOrderWorkOrderLineNotesTables($newOrder->id, $quoteID,$newOrder->id,'orders' );
			$this->savetoOrderWorkOrderLineNotesTables($newOrder->id, $quoteID,$newOrder->id,'workOrders' );
			$this->auditOrderItemStatuses($newOrder->id);
			$this->auditWorkOrderItemStatuses($quoteID);

			
			$this->updatefabricinventoryfororder($newOrder->id);

			$this->logActivity($_SERVER['REQUEST_URI'],'Converted Quote '.$thisQuote['quote_number'].' to Order '.$newOrder->order_number);

			$this->Flash->success('Successfully created new Order #'.$newOrder->order_number);

			return $this->redirect('/orders/');

		} 

	}else{
		
		$allTypes=$this->QuoteTypes->find('all')->toArray();
		$this->set('allTypes',$allTypes);
		
		
		$thisQuote=$this->Quotes->get($quoteID)->toArray();
		$this->set('quoteData',$thisQuote);
		
		if($thisQuote['order_id'] > 0){
			$this->Flash->error('This quote is already converted to an order. Action aborted.');
			return $this->redirect('/orders/editlines/'.$thisQuote['order_id']);
			exit;
		}
		
		$allShippingMethods = $this->ShippingMethods->find('all')->toArray();
		$availableShippingMethods=array();
		foreach($allShippingMethods as $method){
			$availableShippingMethods[$method['id']]=$method['name'];
		}
		$this->set('availableShippingMethods',$availableShippingMethods);

		//$thisCustomer=$this->Customers->get($thisQuote['customer_id'])->toArray();
		$thisCustomer=$this->Customers->find('all',['conditions'=>['id'=>$thisQuote['customer_id']]])->first();;
		$this->set('customerData',$thisCustomer);
		
		$allUsers=$this->Users->find('all',['conditions' => ['status'=>'Active'],'order'=>['last_name'=>'asc','first_name'=>'asc']])->toArray();
		$this->set('allUsers',$allUsers);
		
		/**PPSASCRUM-7 Start **/
		//$allFaclity=$this->Facility->find('list',['keyField' => 'id', 'valueField' => 'facility_name', 'conditions' => [],'order'=>['facility_name'=>'asc']])->toArray();
		//$this->set('allFacility',[]);

		//$shipTooptions=$this->ShipTo->find('list',['keyField' => 'id', 'valueField' => 'shipping_address_1', 'conditions' => [],'order'=>['shipping_address_1'=>'asc']])->toArray();
		//$this->set('shipTooptions',[]);
		/** PPSASCRUM-7 End **/
	}

}
	public function sortlineitems($quoteID,$newsortorder){

		$this->autoRender=false;

		$quoteItemsTable=TableRegistry::get('QuoteLineItems');

		$sortOrder=explode(",",urldecode($newsortorder));

		//print_r($sortOrder);exit;

		foreach($sortOrder as $num => $itemid){

			if(strlen(trim($itemid)) >0){

				$thisLineItem=$quoteItemsTable->get($itemid);

				$thisLineItem->sortorder=$num;

				$quoteItemsTable->save($thisLineItem);

			}

		}

		echo "OK";exit;

	}

	

	

	

	public function tieradjustment($lineitem,$customer,$originalprice,$getpercentage=false,$getDiscountOrPremium=false){

		$settings=$this->getsettingsarray();

		$action='none';

		$percentage=0;

		$lineItemMetas=array();

		$itemmetas=$this->QuoteLineItemMeta->find('all',['conditions'=>['quote_item_id'=>$lineitem['id']]])->toArray();

		foreach($itemmetas as $itemmeta){

			$lineItemMetas[$itemmeta['meta_key']]=$itemmeta['meta_value'];

		}

		

		if(isset($lineItemMetas['specialpricing']) && $lineItemMetas['specialpricing'] == '1'){

			//do not adjust!

			return $originalprice;

		}else{

			switch($lineitem['product_type']){

				case "bedspreads":

					$tierDiscountRate=(floatval($settings["tier_".$customer['tier_bs']."_discount"])/100);
					$tierPremiumRate=(floatval($settings["tier_".$customer['tier_bs']."_premium"])/100);
					$percentage=$tierDiscountRate;
					$action='discount';

				break;

				case "cubicle_curtains":

					$tierDiscountRate=(floatval($settings["tier_".$customer['tier_cc']."_discount"])/100);
					$tierPremiumRate=(floatval($settings["tier_".$customer['tier_cc']."_premium"])/100);
					$percentage=$tierDiscountRate;
					$action='discount';

				break;

				case "window_treatments":
				    $tierDiscountRate=(floatval($settings["tier_".$customer['tier_swt']."_discount"])/100);
					$tierPremiumRate=(floatval($settings["tier_".$customer['tier_swt']."_premium"])/100);
					$percentage=$tierDiscountRate;
					$action='discount';

				break;
				
				
				case "newcatchall-bedding":
				    $tierDiscountRate=(floatval($settings["tier_".$customer['tier_bs']."_discount"])/100);
					$tierPremiumRate=(floatval($settings["tier_".$customer['tier_bs']."_premium"])/100);
					$percentage=$tierPremiumRate;
					$action='premium';

				break;
				    
				case "newcatchall-cubicle":

					$tierDiscountRate=(floatval($settings["tier_".$customer['tier_cc']."_discount"])/100);
					$tierPremiumRate=(floatval($settings["tier_".$customer['tier_cc']."_premium"])/100);
					$percentage=$tierPremiumRate;
					$action='premium';

				break;
				
				case "newcatchall-swtmisc":
				case "newcatchall-valance":
				case "newcatchall-drapery":
				case "newcatchall-cornice":

					$tierDiscountRate=(floatval($settings["tier_".$customer['tier_swt']."_discount"])/100);
					$tierPremiumRate=(floatval($settings["tier_".$customer['tier_swt']."_premium"])/100);
					$percentage=$tierPremiumRate;
					$action='premium';

				break;
				
				
				case "newcatchall-blinds":
				case "newcatchall-shades":
				case "newcatchall-shutters":
				case "newcatchall-hwtmisc":
				    
				    $tierDiscountRate=(floatval($settings["tier_".$customer['tier_hwt']."_discount"])/100);
					$tierPremiumRate=(floatval($settings["tier_".$customer['tier_hwt']."_premium"])/100);
					$percentage=$tierPremiumRate;
					$action='premium';

				    
				break;

				case "custom":

				break;

				case "services":
				case "newcatchall-service":

					if($lineitem['product_id']==1 || $lineitem['product_id'] == 2){
						$tierDiscountRate=(floatval($settings["tier_".$customer['tier_install']."_discount"])/100);
						$tierPremiumRate=(floatval($settings["tier_".$customer['tier_install']."_premium"])/100);
						$percentage=$tierPremiumRate;
						$action='premium';

					}

				break;

				case "track_systems":
                    
					$tierDiscountRate=(floatval($settings["tier_".$customer['tier_track']."_discount"])/100);
					$tierPremiumRate=(floatval($settings["tier_".$customer['tier_track']."_premium"])/100);
					$percentage=$tierDiscountRate;
					$action='discount';

				break;
				
				case "newcatchall-hardware":
				    
					$tierDiscountRate=(floatval($settings["tier_".$customer['tier_track']."_discount"])/100);
					$tierPremiumRate=(floatval($settings["tier_".$customer['tier_track']."_premium"])/100);
					$percentage=$tierPremiumRate;
					$action='premium';

				break;
				
				case "newcatchall-misc":
				    $tierDiscountRate=(floatval($settings["tier_".$customer['tier_fabric']."_discount"])/100);
					$tierPremiumRate=(floatval($settings["tier_".$customer['tier_fabric']."_premium"])/100);
					$percentage=$tierPremiumRate;
					$action='premium';
				break;
				
				case "calculator":

					switch($lineItemMetas['calculator-used']){

						case "bedspread":

							$tierDiscountRate=(floatval($settings["tier_".$customer['tier_bs']."_discount"])/100);

							$tierPremiumRate=(floatval($settings["tier_".$customer['tier_bs']."_premium"])/100);

							$percentage=$tierPremiumRate;

							$action='premium';

						break;

						case "cubicle-curtain":

							$tierDiscountRate=(floatval($settings["tier_".$customer['tier_cc']."_discount"])/100);

							$tierPremiumRate=(floatval($settings["tier_".$customer['tier_cc']."_premium"])/100);

							$percentage=$tierPremiumRate;

							$action='premium';

						break;

						case "box-pleated":

							$tierDiscountRate=(floatval($settings["tier_".$customer['tier_swt']."_discount"])/100);

							$tierPremiumRate=(floatval($settings["tier_".$customer['tier_swt']."_premium"])/100);

							$percentage=$tierPremiumRate;

							$action='premium';

						break;

						case "straight-cornice":

							$tierDiscountRate=(floatval($settings["tier_".$customer['tier_swt']."_discount"])/100);

							$tierPremiumRate=(floatval($settings["tier_".$customer['tier_swt']."_premium"])/100);

							$percentage=$tierPremiumRate;

							$action='premium';

						break;

						case "pinch-pleated":

							$tierDiscountRate=(floatval($settings["tier_".$customer['tier_swt']."_discount"])/100);

							$tierPremiumRate=(floatval($settings["tier_".$customer['tier_swt']."_premium"])/100);

							$percentage=$tierPremiumRate;

							$action='premium';

						break;

					}

				break;

			}

			if($action=='discount'){

				$final=($originalprice-($originalprice*$tierDiscountRate));

			}elseif($action=='premium'){

				$final=($originalprice+($originalprice*$tierPremiumRate));

			}else{

				$final=$originalprice;

			}

			if($getDiscountOrPremium){

				return $action;

			}else{

				if($getpercentage){

					return round(($percentage*100),2);

				}else{

					return round($final,2);

				}

			}

		}

	}

	/*
	public function installadjustment($lineitem,$customer,$originalprice,$getpercentage=false){
		$settings=$this->getsettingsarray();
		$percentage=0;
		$lineItemMetas=array();
		$itemmetas=$this->QuoteLineItemMeta->find('all',['conditions'=>['quote_item_id'=>$lineitem['id']]])->toArray();
		foreach($itemmetas as $itemmeta){
			$lineItemMetas[$itemmeta['meta_key']]=$itemmeta['meta_value'];
		}
		//determine if this item even needs this adjustment
		$doCalc=0;
		if(isset($lineItemMetas['specialpricing']) && $lineItemMetas['specialpricing'] == '1'){
			//do not adjust!
			return $originalprice;
		}else{
		
			switch($lineitem['product_type']){
				case "calculator":
					switch($lineItemMetas['calculator-used']){
						case "box-pleated":
							$doCalc=1;
						break;
						case "straight-cornice":
							$doCalc=1;
						break;
						case "pinch-pleated":
							$doCalc=1;
						break;
						case "rod-pocket":
							$doCalc=1;
						break;
						case "ripple-fold":
							$doCalc=1;
						break;
						case "accordia-fold":
							$doCalc=1;
						break;
						case "grommet-top":
							$doCalc=1;
						break;
						case "swags-and-cascades":
							$doCalc=1;
						break;
					}
				break;
				case "window_treatments":
					$doCalc=1;
				break;
				default:
					$doCalc=0;
				break;
			}
			if($doCalc==0){
				if($getpercentage){
					return 0;
				}else{
					return $originalprice;
				}
			}else{
				$quoteItems=$this->QuoteLineItems->find('all',['conditions'=>['product_type'=>'services','product_id'=>'1','quote_id'=>$lineitem['quote_id']]])->toArray();
				if(count($quoteItems) >0){
					//do the adjustment
					$COMcheck=$this->QuoteLineItemMeta->find('all',['conditions'=>['quote_item_id'=>$lineitem['id'],'meta_key'=>'com-fabric','meta_value'=>'1']])->toArray();
					if(count($COMcheck) >0){
						$comadjust=(1+($settings['install_surcharge_com_percent']/100));
						$percentage=$settings['install_surcharge_com_percent'];
						if($getpercentage){
							return $percentage;
						}else{
							return round(($originalprice*$comadjust),2);
						}
					}else{
						$momadjust=(1+($settings['install_surcharge_mom_percent']/100));
						$percentage=$settings['install_surcharge_mom_percent'];
						if($getpercentage){
							return $percentage;
						}else{
							return round(($originalprice*$momadjust),2);
						}
					}
				}else{
					if($getpercentage){
						return 0;
					}else{
						return round($originalprice,2);
					}
				}
			}
		}
	}

	*/
	
	public function rebateadjustment($lineitem,$customer,$originalprice,$getpercentage=false){
		$lineItemMetas=array();
		$itemmetas=$this->QuoteLineItemMeta->find('all',['conditions'=>['quote_item_id'=>$lineitem['id']]])->toArray();
		foreach($itemmetas as $itemmeta){
			$lineItemMetas[$itemmeta['meta_key']]=$itemmeta['meta_value'];
		}

		if(isset($lineItemMetas['specialpricing']) && $lineItemMetas['specialpricing'] == '1'){
			//do not adjust!
			if($getpercentage){ return 0; }else{ return $originalprice; }
		}else{
			if(floatval($customer['surcharge_percent']) > 0.00){
				if($getpercentage){
					return $customer['surcharge_percent'];
				}else{
					return round(( $originalprice* (1+(floatval($customer['surcharge_percent'])/100)) ),2);
				}
			}else{
				if($getpercentage){
					return 0;
				}else{
					return round($originalprice,2);
				}
			}
		}
	}

	


	public function installadjustment($lineitem,$customer,$originalprice,$getpercentage=false){
		$doPMSurcharge=false;

		$thisQuote=$this->Quotes->get($lineitem['quote_id'])->toArray();

		$lineItemMetas=array();
		$itemmetas=$this->QuoteLineItemMeta->find('all',['conditions'=>['quote_item_id'=>$lineitem['id']]])->toArray();
		foreach($itemmetas as $itemmeta){
			$lineItemMetas[$itemmeta['meta_key']]=$itemmeta['meta_value'];
		}

		switch($lineitem['product_type']){
			case "cubicle_curtains":
			case "window_treatments":
				$doPMSurcharge=true;
			break;
			case "calculator":
				switch($lineitem['calculator_used']){
					case "box-pleated":
					case "cubicle-curtain":
					case "pinch-pleated":
					case "straight-cornice":
						$doPMSurcharge=true;
					break;
				}
			break;
		}

		if($doPMSurcharge){

			//read the configs from this quote
			$surchargeConfigs=json_decode(urldecode($thisQuote['project_management_surcharge_configs']),true);
			if($getpercentage){
				return $surchargeConfigs['pms_percentage'];
			}else{
				return round(($originalprice * (1 + (floatval($surchargeConfigs['pms_percentage'])/100) )),2);
			}

		}else{
			if($getpercentage){
				return 0;
			}else{
				return $originalprice;
			}
		}

	}




	function pmiadjustment($lineitem,$customer,$originalprice,$getdollars=false){

		$lineItemMetas=array();
		$itemmetas=$this->QuoteLineItemMeta->find('all',['conditions'=>['quote_item_id'=>$lineitem['id']]])->toArray();
		foreach($itemmetas as $itemmeta){
			$lineItemMetas[$itemmeta['meta_key']]=$itemmeta['meta_value'];
		}

		if(isset($lineItemMetas['specialpricing']) && $lineItemMetas['specialpricing'] == '1'){
			//do not adjust!
			if($getdollars){
				return 0.00;
			}else{
				return $originalprice;
			}
		}else{
			$appliedProducts=array(
				'cubicle_curtains',
				'bedspreads',
				'window_treatments',
				'calculator'
			);

			//if(($lineitem['product_type'] == 'calculator' && $lineitem['calculator_used'] != 'track') || !in_array($lineitem['product_type'],$appliedProducts)){
			if(!in_array($lineitem['product_type'],$appliedProducts)){
				if($getdollars){
					return 0.00;
				}else{
					return $originalprice;
				}
			}else{
				if(floatval($customer['surcharge_dollars']) >0){
					if($getdollars){
						return number_format(floatval($customer['surcharge_dollars']),2,'.',',');
					}else{
						return round(( $originalprice + floatval($customer['surcharge_dollars'])),2);
					}
				}else{
					if($getdollars){
						return 0.00;
					}else{
						return round($originalprice,2);
					}
				}

			}

		}

	}

	

	public function clonequote($quoteID,$isrevision=0,$fromorder=0,$newcustomer=0){

		$this->autoRender=false;

		if($this->request->data){
			//do it
			$thisQuote=$this->Quotes->get($quoteID)->toArray();
			
			$quotesTable=TableRegistry::get('Quotes');
			$quoteLinesTable=TableRegistry::get('QuoteLineItems');
			$quoteLineMetaTable=TableRegistry::get('QuoteLineItemMeta');
			$quoteLineNotesTable=TableRegistry::get('QuoteLineItemNotes');
			$quoteBOMReqsTable=TableRegistry::get('QuoteBomRequirements');
			$quoteDiscountsTable=TableRegistry::get('QuoteDiscounts');
			
			
			$lineIDsOldToNew=array();
			
			$newQuote=$quotesTable->newEntity();
			
			$excludedKeys=array('id','created','modified','revision','parent_quote','expires','created_by','status','quote_number','order_id','order_number','title');
			
			foreach($thisQuote as $key => $value){
				if(!in_array($key,$excludedKeys)){
					$newQuote->{$key}=$value;
				}
			}
			
			$newQuote->title='';
			$newQuote->type_id=$thisQuote['type_id'];
			$newQuote->created=time();
			$newQuote->modified=time();
			$newQuote->revision=0;
			$newQuote->parent_quote=0;
			$newQuote->expires=(time()+(30*86400));
			$newQuote->created_by=$this->Auth->user('id');
			$newQuote->status='draft';
			$newQuote->quote_number=$this->getnewquotenumber();
			
			
			if($quotesTable->save($newQuote)){
			
				$thisQuoteLines=$this->QuoteLineItems->find('all',['conditions'=>['quote_id'=>$quoteID, 'enable_tally'=>1],'order'=>['sortorder'=>'asc']])->toArray();
				foreach($thisQuoteLines as $line){
					
					//clone this line
					$newLine=$quoteLinesTable->newEntity();
					$excludedLineKeys=array('quote_id','parent_line');
					
					
					$newLine->type=$line['type'];
					$newLine->status=$line['status'];
					$newLine->product_type=$line['product_type'];
					$newLine->product_id=$line['product_id'];
					
					$newLine->product_class = $line['product_class'];
				    $newLine->product_subclass = $line['product_subclass'];
					
					$newLine->title=$line['title'];
					$newLine->description=$line['description'];
					$newLine->best_price=$line['best_price'];
					$newLine->qty=$line['qty'];
					$newLine->lineitemtype=$line['lineitemtype'];
					$newLine->room_number=$line['room_number'];
					$newLine->line_number=$line['line_number'];
					$newLine->internal_line=$line['internal_line'];
					$newLine->sortorder=$line['sortorder'];
					$newLine->unit=$line['unit'];
					$newLine->tier_adjusted_price=$line['tier_adjusted_price'];
					$newLine->install_adjusted_price=$line['install_adjusted_price'];
					$newLine->rebate_adjusted_price=$line['rebate_adjusted_price'];
					$newLine->extended_price=$line['extended_price'];
					$newLine->override_active = $line['override_active'];
					$newLine->override_price=$line['override_price'];
					$newLine->calculator_used=$line['calculator_used'];
					$newLine->pmi_adjusted=$line['pmi_adjusted'];
					
					$newLine->quote_id=$newQuote->id;
					
					
					//figure out the new "parent line" id#
					if($line['parent_line'] == 0){
					    $newLine->parent_line=0;
					}else{
					    if(!isset($lineIDsOldToNew[$line['parent_line']])){
					        $newLine->parent_line=0;
					    }else{
					        $newLine->parent_line=$lineIDsOldToNew[$line['parent_line']];
					    }
					}
					
					
					$newLine->revised_from_line=$line['id'];
					
					$thisSubClass=$this->ProductSubclasses->get($line['product_subclass'])->toArray();
                    
                    $newLine->enable_tally=$thisSubClass['tally'];
                    
					if($quoteLinesTable->save($newLine)){
						$lineIDsOldToNew[$line['id']]=$newLine->id;
						
						//clone METAS
						$thisQuoteLineMeta=$this->QuoteLineItemMeta->find('all',['conditions' => ['quote_item_id'=>$line['id']]])->toArray();
						foreach($thisQuoteLineMeta as $meta){
							$newMeta=$quoteLineMetaTable->newEntity();
							$newMeta->meta_key=$meta['meta_key'];
							$newMeta->meta_value=$meta['meta_value'];
							$newMeta->quote_item_id = $newLine->id;
							$quoteLineMetaTable->save($newMeta);
						}
						
						//clone NOTES
						$thisQuoteLineNotes=$this->QuoteLineItemNotes->find('all',['conditions' => ['quote_item_id' => $line['id']]])->toArray();
						foreach($thisQuoteLineNotes as $note){
							$newNote=$quoteLineNotesTable->newEntity();
							$newNote->user_id=$note['user_id'];
							$newNote->time=$note['time'];
							$newNote->message=$note['message'];
							$newNote->quote_id=$newQuote->id;
							$newNote->quote_item_id=$newLine->id;
							$newNote->visible_to_customer=$note['visible_to_customer'];
							$quoteLineNotesTable->save($newNote);
						}
						
						
					}
					
					
				}
				
				
				//clone Global Notes
				$globalNotes=$this->QuoteNotes->find('all',['conditions' => ['quote_id' => $quoteID]])->toArray();
				foreach($globalNotes as $gNote){
				    $newGNote=$this->QuoteNotes->newEntity();
				    $newGNote->quote_id=$newQuote->id;
				    $newGNote->user_id=$gNote['user_id'];
				    $newGNote->time = $gNote['time'];
				    $newGNote->appear_on_pdf=$gNote['appear_on_pdf'];
				    $newGNote->note_text = $gNote['note_text'];
				    $this->QuoteNotes->save($newGNote);
				}
				
				
				/*
				//BOM Requirements clone
				$thisQuoteBOMReqs=$this->QuoteBomRequirements->find('all',['conditions'=>['quote_id'=>$quoteID]])->toArray();
				foreach($thisQuoteBOMReqs as $bomReq){

					$newBOMReq=$quoteBOMReqsTable->newEntity();
					$newBOMReq->quote_id=$newQuote->id;
					$newBOMReq->material_type=$bomReq['material_type'];
					$newBOMReq->material_id=$bomReq['material_id'];
					$newBOMReq->requirement=$bomReq['requirement'];
					$newBOMReq->purchaser_requirement_met=0;
					$quoteBOMReqsTable->save($newBOMReq);

				}
				*/
				
				//quote DISCOUNTS
				$thisQuoteDiscounts=$this->QuoteDiscounts->find('all',['conditions'=>['quote_id'=>$quoteID]])->toArray();
				foreach($thisQuoteDiscounts as $quoteDiscount){
					$newDiscount=$quoteDiscountsTable->newEntity();
					$newDiscount->quote_id=$newQuote->id;
					$newDiscount->discount_name=$quoteDiscount['discount_name'];
					$newDiscount->discount_amount=$quoteDiscount['discount_amount'];
					$newDiscount->discount_math=$quoteDiscount['discount_math'];
					$newDiscount->added_by=$quoteDiscount['added_by'];
					$newDiscount->added_time=$quoteDiscount['added_time'];
					$quoteDiscountsTable->save($newDiscount);
				}
				
				

			}
			
			if($fromorder==1){
				$quoteOrOrder='Order';
				$redirecturl='/quotes/';
				
			}else{
				$quoteOrOrder='Quote';
				$redirecturl='/quotes/';
				
			}
			
			$this->Flash->success('Succesfully created new draft from selected existing '.$quoteOrOrder);
			$this->logActivity($_SERVER['REQUEST_URI'],'Cloned Quote '.$thisQuote['quote_number'].' to Draft '.$newQuote->quote_number);
			return $this->redirect($redirecturl);
		}else{
		    if($newcustomer==1){
		       
		       $allCustomers=$this->Customers->find('all',['order'=>['company_name'=>'asc']])->toArray();
		       $this->set('allCustomers',$allCustomers);
		       
		       $thisQuote=$this->Quotes->get($quoteID)->toArray();
		       $this->set('quoteData',$thisQuote);
		       
		       $contacts=$this->CustomerContacts->find('all',['conditions'=>['customer_id'=>$thisQuote['customer_id'],'status !=' => 'Deleted'],'order'=>['first_name'=>'asc','last_name'=>'asc']])->toArray();
		       $this->set('contacts',$contacts);
		       
		       $this->render('clonenewcustomer');
		        
		    }else{
    			//confirm it
    			$this->set('fromOrder',$fromorder);
    			if($fromorder==1){
    				$findOrder=$this->Orders->find('all',['conditions'=>['quote_id'=>$quoteID]])->toArray();
    				foreach($findOrder as $orderRow){
    					$this->set('orderNumber',$orderRow['order_number']);
    					$this->set('order_id',$orderRow['id']);
    				}
    			}else{
    				$thisQuote=$this->Quotes->get($quoteID)->toArray();
    				$this->set('quoteNumber',$thisQuote['quote_number']);
    				$this->set('quote_id',$quoteID);
    			}
    			$this->render('confirmclone');
		    }
		}

	}
	
	

	public function recalculatequoteadjustments($quoteID){
		//find the quote number

		$newSubtotal=0.00;

			$resarr=array();
			$thisQuote=$this->Quotes->get($quoteID)->toArray();
			$thisCustomer=$this->Customers->find('all',['conditions'=>['id'=>$thisQuote['customer_id']]])->first();;
			//$thisCustomer=$this->Customers->get($thisQuote['customer_id'])->toArray();
			$thisQuoteItems=$this->QuoteLineItems->find('all',['conditions'=>['quote_id'=>$thisQuote['id']]])->toArray();
			$lineitemtable=TableRegistry::get('QuoteLineItems');
			$this->autoRender=false;
			foreach($thisQuoteItems as $lineItem){
				$bestprice=floatval($lineItem['best_price']);
				$metaData=$this->QuoteLineItemMeta->find('all',['conditions'=>['quote_item_id' => $lineItem['id'],'meta_key'=>'specialpricing']])->toArray();
				$specialPricing=0;
				foreach($metaData as $metarow){
					if($metarow['meta_value'] == '1'){
						$specialPricing=1;
					}
				}
				if($specialPricing == 1){
					$tieradjusted=$bestprice;
					$installadjusted=$bestprice;
					$rebateadjusted=$bestprice;
					$pmiadjusted=$bestprice;
					
				}else{
					$tieradjusted=$this->tieradjustment($lineItem,$thisCustomer,$bestprice);
					$installadjusted=$this->installadjustment($lineItem,$thisCustomer,$tieradjusted);
					$rebateadjusted=$this->rebateadjustment($lineItem,$thisCustomer,$installadjusted);
					$pmiadjusted=$this->pmiadjustment($lineItem,$thisCustomer,$rebateadjusted);
					//$pmsurcharged=$this->pmsurchargecalculate($lineItem,$thisCustomer,$pmiadjusted);
				}

				$resarr[]=array("lineItemID"=>$lineItem['id'],"best_price"=>$bestprice,"tier_adjusted"=>$tieradjusted,"install_adjusted"=>$installadjusted,"rebate_adjusted"=>$rebateadjusted, 'pmi_adjusted' => $pmiadjusted); //, 'pmsurcharged' => $pmsurcharged);

				$thisLineItem=$lineitemtable->get($lineItem['id']);

				$thisLineItem->tier_adjusted_price=$tieradjusted;

				$thisLineItem->install_adjusted_price=$installadjusted;

				$thisLineItem->rebate_adjusted_price=$rebateadjusted;

				$thisLineItem->pmi_adjusted = $pmiadjusted;

				//$thisLineItem->project_management_surcharge_adjusted = $pmsurcharged;

				if($lineItem['override_active'] == "1"){

					$thisLineItem->extended_price=(floatval($lineItem['override_price'])*floatval($lineItem['qty']));
                    $newSubtotal=($newSubtotal+(floatval($lineItem['override_price'])*floatval($lineItem['qty'])));
                    
				}else{

					$thisLineItem->extended_price=($pmiadjusted*floatval($lineItem['qty']));
					//$thisLineItem->extended_price=($pmsurcharged*floatval($lineItem['qty']));
                    $newSubtotal=($newSubtotal+($pmiadjusted*floatval($lineItem['qty'])));
				}
				
				
                
				$lineitemtable->save($thisLineItem);
				$thisOrder = $this->Orders->find("all", [ "conditions" => ["quote_id" => $thisQuote['id']],"order" => ["id" => "asc"],])->first();

				if(isset($thisOrder) && !empty($thisOrder)){
					$thisOrder = $thisOrder->toArray();
					$this->updateLineItemsDetails($thisOrder['id'],$lineItem['id'], $thisQuote['id'],$tieradjusted, $installadjusted, $rebateadjusted,$pmiadjusted,$thisLineItem->extended_price);
				}
			}

            $quotesTable=TableRegistry::get('Quotes');
            $thisQuoteRow=$quotesTable->get($quoteID);
            $thisQuoteRow->quote_subtotal=$newSubtotal;
            $thisQuoteRow->quote_total=$newSubtotal;
            $quotesTable->save($thisQuoteRow);
            
			$this->updatepmsurchargequoterangepercentage($quoteID);

			//print_r($resarr);

			return true;
		

	}


    public function removepriceoverride($lineitem){
		$this->autoRender=false;
		$quoteItemTable=TableRegistry::get('QuoteLineItems');

		$thisLineItem=$quoteItemTable->get($lineitem);
		$thisLineItem->override_price=NULL;
		$thisLineItem->override_active=0;
		if($quoteItemTable->save($thisLineItem)){

			if($this->recalculatequoteadjustments($thisLineItem->quote_id)){

				$this->updatequotemodifiedtime($thisLineItem->quote_id);

				echo "OK";

			}

		}
	}
	

	public function changelineitemprice($lineitem,$newprice){

		$this->autoRender=false;

		$quoteItemTable=TableRegistry::get('QuoteLineItems');

		$thisLineItem=$quoteItemTable->get($lineitem);

		if(strlen(trim($newprice)) >0){
			$thisLineItem->override_active = 1;
		}else{
			$thisLineItem->override_active = 0;
		}

		$thisLineItem->override_price=$newprice;

		if($quoteItemTable->save($thisLineItem)){

			if($this->recalculatequoteadjustments($thisLineItem->quote_id)){

				$this->updatequotemodifiedtime($thisLineItem->quote_id);

				echo "OK";

			}

		}

	}
	
	

	public function editlineitemimage($quoteID,$lineItemID){

		$this->viewBuilder()->layout('iframeinner');
       	$this->set('authUser', $this->Auth->user());
       	$thisMeta=$this->QuoteLineItemMeta->find('all',['conditions' => ['quote_item_id'=>$lineItemID, 'meta_key' => 'libraryimageid']])->toArray();
       	if(count($thisMeta) == 0){
     		$this->set('currentImage','None');
       	}else{
       		$this->set('currentImage',$thisMeta[0]['meta_value']);
       	}
		
       	$libraryCatsLookup=$this->LibraryCategories->find('all')->toArray();
		$allCats=array();
		foreach($libraryCatsLookup as $catEntry){
			$allCats[$catEntry['id']]=$catEntry['category_title'];
		}

		$this->set('allLibraryCats',$allCats);

		$libraryimages=$this->LibraryImages->find('all',['conditions'=>['status'=>'Active']])->toArray();

		$this->set('libraryimages',$libraryimages);
		
		
       	if($this->request->data){
            switch($this->request->data['image_method']){
                case "none":
                    //erase the image meta and leave at NONE
                    $lineItemMetaTable=TableRegistry::get('QuoteLineItemMeta');
					$thisLineItemMeta=$lineItemMetaTable->get($thisMeta[0]['id']);
					if($lineItemMetaTable->delete($thisLineItemMeta)){
						//done
					}
                break;
                case "library":
                    //replace image id meta with new selection
					$this->updateLineItemMeta($lineItemID,'libraryimageid',$this->request->data['newlibraryimageid']);
					
                break;
                case "upload":
					
					$allowedTypes=array('.jpg','jpeg','.png','.gif');
					if(!in_array(strtolower(substr($this->request->data['imagefileupload']['name'],-4)),$allowedTypes)){
					    $this->autoRender=false;
					    echo '<h3><span style="color:red;">ERROR:</span> This filetype is not allowed.</h3>
                        <p><button type="button" onclick="history.go(-1);">Go Back</button></p>';
					    exit;
					}
					
                    //handle the upload, insert into library as single use, update meta
                    $filename=$this->request->data['imagefileupload']['name'];
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
					move_uploaded_file($this->request->data['imagefileupload']['tmp_name'],$_SERVER['DOCUMENT_ROOT']."/webroot/img/library/".$filename);

					$imgLibraryTable=TableRegistry::get('LibraryImages');
					$newImage=$imgLibraryTable->newEntity();

					if($this->request->data['save_to_library'] == '1'){
						$selectedCat='';
						$allCats=$this->LibraryCategories->find('all')->toArray();
						foreach($allCats as $catEntry){
							if($catEntry['id'] == $this->request->data['image_category']){
								$selectedCat=$catEntry['category_title'];
							}
						}

						//insert into image library db with details for future usage
						$newImage->image_title=$this->request->data['image_title'];
						$newImage->categories=$selectedCat;
						$newImage->filename=$filename;
						$newImage->time=time();
						$newImage->added_by=$this->Auth->user('id');
						$newImage->tags=$this->request->data['image_tags'];
						$newImage->status='Active';
					}else{
						//insert into image library db with no details for single usage
						$newImage->image_title=$this->request->data['imagefileupload']['name'];
						$newImage->categories='Misc';
						$newImage->filename=$filename;
						$newImage->time=time();
						$newImage->added_by=$this->Auth->user('id');
						$newImage->status='Single Use';
					}

					if($imgLibraryTable->save($newImage)){
						//insert the metadata for libraryimageid
						$this->aspectratiofix($newImage->id,600);
						
						$this->updateLineItemMeta($lineItemID,'libraryimageid',$newImage->id);
					}
					
                break;
            }
			
			$this->autoRender=false;
			echo "<script>
			parent.updateQuoteTable();
			parent.$.fancybox.close();
			</script>";
			
       	}else{
            
       	}

	}
	

	public function newrevision($quoteID){

		//begin the process of creating a new revision of this original quote

		

		$thisQuote=$this->Quotes->get($quoteID)->toArray();

		

		//if this quote is a Draft or already has a Draft Revision, error out

		if($thisQuote['status'] == 'draft'){

			$this->Flash->error('You cannot revise a Draft quote');

			return $this->redirect('/quotes/');

		}

		$revisionCheck=$this->Quotes->find('all',['conditions'=>['quote_number' => $thisQuote['quote_number'],'status'=>'draft']])->toArray();

		if(count($revisionCheck) > 0){

			$this->Flash->error('You cannot revise a quote with an active Draft revision');

			return $this->redirect('/quotes/');

		}

		

		//see if this quote is already an Order, if so, error out

		$orderCheck=$this->Orders->find('all',['conditions'=>['quote_id'=>$quoteID]])->toArray();

		if(count($orderCheck) >0){

			$this->Flash->error('You cannot revise a quote that is already converted to an Order');

			return $this->redirect('/quotes/');

		}

		

		$latestRevision=$this->Quotes->find('all',['conditions'=>['quote_number'=>$thisQuote['quote_number']],'order'=>['revision'=>'DESC']])->first()->toArray();

		

		if(count($latestRevision) == 0){

			$newrevisionnumber=1;

		}else{

			$newrevisionnumber=(floatval($latestRevision['revision'])+1);

		}

		

		

		$quoteTable=TableRegistry::get('Quotes');

		$lineItemTable=TableRegistry::get('QuoteLineitems');

		$lineItemMetaTable=TableRegistry::get('QuoteLineItemMeta');

		$lineItemNotesTable=TableRegistry::get('QuoteLineItemNotes');

		

		$clonedQuote=$quoteTable->newEntity();

		$clonedQuote->created=time();

		$clonedQuote->modified=time();

		$clonedQuote->expires=(time()+(intval($this->getSettingValue('quotes_expiration_in_days')) * 86400));

		$clonedQuote->parent_quote=$quoteID;

		$clonedQuote->revision=$newrevisionnumber;
		
		$clonedQuote->type_id = $thisQuote['type_id'];

		$clonedQuote->status='draft';

		$clonedQuote->customer_id=$thisQuote['customer_id'];

		$clonedQuote->contact_id=$thisQuote['contact_id'];

		$clonedQuote->created_by = $this->Auth->user('id');

		$clonedQuote->quote_subtotal=$thisQuote['quote_subtotal'];

		$clonedQuote->quote_surcharge=$thisQuote['quote_surcharge'];

		$clonedQuote->quote_discount = $thisQuote['quote_discount'];

		$clonedQuote->quote_total=$thisQuote['quote_total'];

		$clonedQuote->quote_number=$thisQuote['quote_number'];

		$clonedQuote->title=$thisQuote['title'];

		if($quoteTable->save($clonedQuote)){

			//let's clone all the Line Items, their associated Meta data, and Notes

			$thisQuoteLines=$this->QuoteLineItems->find('all',['conditions'=>['quote_id' => $quoteID]])->toArray();

			foreach($thisQuoteLines as $quoteLine){

				//clone it to the new quote id#

				$clonedLine=$lineItemTable->newEntity();
				$clonedLine->type=$quoteLine['type'];
				$clonedLine->quote_id=$clonedQuote->id;
				$clonedLine->status=$quoteLine['status'];
				$clonedLine->product_type=$quoteLine['product_type'];
				$clonedLine->product_id=$quoteLine['product_id'];
				$clonedLine->product_class = $quoteLine['product_class'];
				$clonedLine->product_subclass = $quoteLine['product_subclass'];
				$clonedLine->title=$quoteLine['title'];
				$clonedLine->description=$quoteLine['description'];
				$clonedLine->best_price=$quoteLine['best_price'];
				$clonedLine->qty=$quoteLine['qty'];
				$clonedLine->lineitemtype=$quoteLine['lineitemtype'];
				$clonedLine->room_number=$quoteLine['room_number'];
				$clonedLine->line_number=$quoteLine['line_number'];
				$clonedLine->internal_line=$quoteLine['internal_line'];
				$clonedLine->enable_tally = $quoteLine['enable_tally'];
				$clonedLine->sortorder=$quoteLine['sortorder'];
				$clonedLine->unit=$quoteLine['unit'];
				$clonedLine->tier_adjusted_price=$quoteLine['tier_adjusted_price'];
				$clonedLine->install_adjusted_price=$quoteLine['install_adjusted_price'];
				$clonedLine->rebate_adjusted_price=$quoteLine['rebate_adjusted_price'];
				$clonedLine->pmi_adjusted = $quoteLine['pmi_adjusted'];
				$clonedLine->extended_price=$quoteLine['extended_price'];
				$clonedLine->override_active = $quoteLine['override_active'];
				$clonedLine->override_price=$quoteLine['override_price'];
				$clonedLine->calculator_used=$quoteLine['calculator_used'];
				$clonedLine->revised_from_line=$quoteLine['id'];

				if($lineItemTable->save($clonedLine)){

					//clone meta data and notes for this line
					$thisQuoteLineMetas=$this->QuoteLineItemMeta->find('all',['conditions'=>['quote_item_id' => $quoteLine['id']]])->toArray();
					foreach($thisQuoteLineMetas as $lineMeta){
						$clonedMeta=$lineItemMetaTable->newEntity();
						$clonedMeta->meta_key=$lineMeta['meta_key'];
						$clonedMeta->meta_value=$lineMeta['meta_value'];
						$clonedMeta->quote_item_id=$clonedLine->id;
						$lineItemMetaTable->save($clonedMeta);
					}
					$thisQuoteLineNotes=$this->QuoteLineItemNotes->find('all',['conditions'=>['quote_item_id' => $quoteLine['id']]])->toArray();

					foreach($thisQuoteLineNotes as $lineNote){
						$clonedNote=$lineItemNotesTable->newEntity();
						$clonedNote->user_id=$lineNote['user_id'];
						$clonedNote->time=$lineNote['time'];
						$clonedNote->message=$lineNote['message'];
						$clonedNote->quote_id=$clonedQuote->id;
						$clonedNote->quote_item_id=$clonedLine->id;
						$clonedNote->visible_to_customer=$lineNote['visible_to_customer'];
						$lineItemNotesTable->save($clonedNote);
					}

				}

			}


			//determine the new parent_line id#s from corresponding old lines and update them

			$newLines=$this->QuoteLineItems->find('all',['conditions' => ['quote_id' => $clonedQuote->id]])->toArray();

			foreach($newLines as $newLine){

				//find old line
				$oldLine=$this->QuoteLineItems->get($newLine['revised_from_line'])->toArray();

				if($oldLine['parent_line'] > 0){
					//find the new cloned line that corresponds to the old parent line
					$clonedChildParent=$this->QuoteLineItems->find('all',['conditions'=>['quote_id' => $clonedQuote->id, 'revised_from_line' => $oldLine['parent_line']]])->toArray();

					//update $newLine->parent_line  with  $clonedChildParent->id
					$thisClonedLine=$lineItemTable->get($newLine['id']);
					$thisClonedLine->parent_line = $clonedChildParent[0]['id'];
					$lineItemTable->save($thisClonedLine);

				}

			}
			
			$quoteGlobalNotesTable=TableRegistry::get('QuoteNotes');
			//Global Notes
            $thisQuoteGlobalNotes=$this->QuoteNotes->find('all',['conditions' => ['quote_id' => $thisQuote['id']]])->toArray();
            foreach($thisQuoteGlobalNotes as $note){
                $newGlobalNote=$quoteGlobalNotesTable->newEntity();
                $newGlobalNote->quote_id=$clonedQuote->id;
                $newGlobalNote->user_id=$note['user_id'];
                $newGlobalNote->time=$note['time'];
                $newGlobalNote->appear_on_pdf=$note['appear_on_pdf'];
                $newGlobalNote->note_text=$note['note_text'];
                $quoteGlobalNotesTable->save($newGlobalNote);
            }
            
            
			$this->logActivity($_SERVER['REQUEST_URI'],'Created Revision '.$clonedQuote->revision.' of Quote '.$clonedQuote->quote_number);

			$this->Flash->success('Successfully created Revision '.$clonedQuote->revision.' of Quote '.$clonedQuote->quote_number);

			return $this->redirect('/quotes/add/'.$clonedQuote->id);

		}

	}

	

	public function updatefabrequirement($quoteID,$fabricID){

		$this->autoRender=false;

		$bomReqTable=TableRegistry::get('QuoteBomRequirements');

		$requirementsLookup=$this->QuoteBomRequirements->find('all',['conditions' => ['quote_id' => $quoteID,'material_type'=>'Fabrics','material_id'=>$fabricID]])->toArray();

		if(count($requirementsLookup) == 0){

			//does not exist... insert it

			$newReq=$bomReqTable->newEntity();

			$newReq->quote_id=$quoteID;

			$newReq->material_type='Fabrics';

			$newReq->material_id=$fabricID;

			$newReq->requirement=$this->request->data['newvalue'];

			if($bomReqTable->save($newReq)){
				$this->logActivity($_SERVER['REQUEST_URI'],'Added BOM Requirements for Quote ID# '.$quoteID.', Fabric ID# '.$fabricID);
			}

		}else{

			//exists... update it

			foreach($requirementsLookup as $bomreqrow){

				$reqUpdate=$bomReqTable->get($bomreqrow['id']);

				$reqUpdate->requirement=$this->request->data['newvalue'];

				$bomReqTable->save($reqUpdate);

			}

		}

		echo "DONE";

	}

	public function recordusage($quoteID,$fabricID){

		$this->set('fabricID',$fabricID);

		$this->set('quoteID',$quoteID);

		$fabricData=$this->Fabrics->get($fabricID)->toArray();

		$this->set('fabricData',$fabricData);

		

		$this->viewBuilder()->layout('iframeinner');

		if($this->request->data){

			$usageTable=TableRegistry::get('MaterialUsages');

			$newUsage=$usageTable->newEntity();

			$newUsage->material_type='Fabrics';

			$newUsage->material_id=$fabricID;

			$newUsage->roll_id=$this->request->data['rollnumber'];

			$newUsage->time=time();

			$newUsage->user_id=$this->Auth->user('id');

			$newUsage->yards_used=$this->request->data['actualyards'];

			$newUsage->line_number=$this->request->data['line_number'];

			$newUsage->quote_id=$quoteID;

			$newUsage->starting_yards=$this->request->data['startingyards'];

			$thisRoll=$this->MaterialInventory->get($this->request->data['rollnumber'])->toArray();

			if($usageTable->save($newUsage)){

				//update "modified" time

				$invTable=TableRegistry::get('MaterialInventory');

				$thisInvRoll=$invTable->get($fabricID);

				$thisInvRoll->date_modified=time();

				$invTable->save($thisInvRoll);

				echo "<script>

				parent.updateQuoteTable();

				parent.$.fancybox.close();

				</script>";

			}

		}else{

			$allLines=array();

			$allLinesLookup=$this->QuoteLineItems->find('all',['conditions'=>['quote_id' => $quoteID]])->toArray();

			foreach($allLinesLookup as $lineFound){

				$allLines[$lineFound['id']]=array(

					'type' => $lineFound['type'],

					'quote_id' => $lineFound['quote_id'],

					'status' => $lineFound['status'],

					'product_type' => $lineFound['product_type'],

					'product_id' => $lineFound['product_id'],

					'title' => $lineFound['title'],

					'description' => $lineFound['description'],

					'best_price' => $lineFound['best_price'],

					'qty' => $lineFound['qty'],

					'lineitemtype' => $lineFound['lineitemtype'],

					'room_number' => $lineFound['room_number'],

					'line_number' => $lineFound['line_number'],

					'internal_line' => $lineFound['internal_line'],

					'sortorder' => $lineFound['sortorder'],

					'unit' => $lineFound['unit'],

					'tier_adjusted_price' => $lineFound['tier_adjusted_price'],

					'install_adjusted_price' => $lineFound['install_adjusted_price'],

					'rebate_adjusted_price' => $lineFound['rebate_adjusted_price'],

					'extended_price' => $lineFound['extended_price'],

					'override_price' => $lineFound['override_price'],

					'parent_line' => $lineFound['parent_line'],

					'calculator_used' => $lineFound['calculator_used'],

					'revised_from_line' => $lineFound['revised_from_line'],

					'metadata'=>array()

					);

				//find all meta data

				$thisLineMetaData=$this->QuoteLineItemMeta->find('all',['conditions'=>['quote_item_id' => $lineFound['id']]])->toArray();

				foreach($thisLineMetaData as $metadata){

					$allLines[$lineFound['id']]['metadata'][$metadata['meta_key']]=$metadata['meta_value'];

				}

			}

			$this->set('allLines',$allLines);

			$allRolls=array();

			$allRollsLookup=$this->MaterialInventory->find('all',['conditions' => ['material_type' => 'Fabrics','material_id' => $fabricID]])->toArray();

			foreach($allRollsLookup as $roll){

				$allRolls[]=array(

					'id' => $roll['id'],

					'roll_number' => $roll['roll_number'],

					'yards_on_roll' => $this->getfabricyardsonroll($roll['id']),

				);

			}

			$this->set('allRolls',$allRolls);

		}

	}

	public function addtracktoquote($quoteID,$trackItemID,$qty,$price,$description='',$location=''){

		$this->autoRender=false;

		$location=$this->cleanparameterreplacements($location);

		$settings=$this->getsettingsarray();

		$thisTrackItem=$this->TrackSystems->get($trackItemID)->toArray();

		$thisQuote=$this->Quotes->get($quoteID)->toArray();

		$lineTable=TableRegistry::get('QuoteLineItems');

		$lineMetaTable=TableRegistry::get('QuoteLineItemMeta');

		$newLineItem=$lineTable->newEntity();

		$newLineItem->quote_id=$quoteID;

		$newLineItem->type='lineitem';

		$newLineItem->room_number = $location;

		$newLineItem->status='included';

		$newLineItem->product_type='track_systems';

		$newLineItem->product_id=$trackItemID;
		
		$newLineItem->product_class=1;
		$newLineItem->product_subclass=3;

		$newLineItem->title=$thisTrackItem['title'];

		$newLineItem->description = $description;

		$newLineItem->best_price=$price;
		
		$newLineItem->override_active = 0;

		//adjusted


		$newLineItem->qty=$qty;

		$newLineItem->lineitemtype='simple';

		if($thisTrackItem['unit'] == 'plf'){

			$newLineItem->unit='linear feet';

		}else{

			$newLineItem->unit='each';

		}

		

		//find new line number

		$newLineItem->line_number=$this->getnextlinenumber($quoteID);

		$newLineItem->sortorder=$this->getNewItemSortOrderNumber($quoteID);
        
        /**PPSA-40 start **/
        $newLineItem->created=time();
        /**PPSA-40 end **/
		

		if($lineTable->save($newLineItem)){

			$this->logActivity($_SERVER['REQUEST_URI'],'Added track "'.$thisTrackItem['title'].'" as Line #'.$newLineItem->line_number.' to quote '.$thisQuote['quote_number'],$this->Auth->user('id'),json_encode($this->request));

			if($this->recalculatequoteadjustments($quoteID)){

				$this->updatequotemodifiedtime($quoteID);

				//meta data time

				$newLineItemMeta=$lineMetaTable->newEntity();

				$newLineItemMeta->quote_item_id=$newLineItem->id;

				$newLineItemMeta->meta_key='lineitemtype';

				$newLineItemMeta->meta_value='simpleproduct';

				$lineMetaTable->save($newLineItemMeta);

				

				

				$newLineItemMeta=$lineMetaTable->newEntity();

				$newLineItemMeta->quote_item_id=$newLineItem->id;

				$newLineItemMeta->meta_key='price';

				$newLineItemMeta->meta_value=$price;

				$lineMetaTable->save($newLineItemMeta);

				

				

				$newLineItemMeta=$lineMetaTable->newEntity();

				$newLineItemMeta->quote_item_id=$newLineItem->id;

				$newLineItemMeta->meta_key='qty';

				$newLineItemMeta->meta_value=$qty;

				$lineMetaTable->save($newLineItemMeta);

			}

		}

		

		echo "OK";exit;

		

	}

	public function viewtrackbreakdown($lineID){

		$this->viewBuilder()->layout('iframeinner');

       	$this->set('authUser', $this->Auth->user());

       	$quoteItem=$this->QuoteLineItems->get($lineID)->toArray();

       	$itemMetas=$this->QuoteLineItemMeta->find('all',['conditions'=>['quote_item_id'=>$quoteItem['id']]])->toArray();

		$metaArray=array();

		$quoteData=$this->Quotes->get($quoteItem['quote_id'])->toArray();

		$hasWO=false;
		//lookup WO number if it exists
		$lookupWO=$this->Orders->find('all',['conditions' => ['quote_id' => $quoteData['id']]])->count();
		if($lookupWO >0){
			$hasWO=true;
		}

		foreach($itemMetas as $meta){
			$metaArray[$meta['meta_key']]=$meta['meta_value'];
		}

		echo "<center><h1>";
		if($hasWO){
			$thisWO=$this->Orders->find('all',['conditions' => ['quote_id' => $quoteData['id']]])->toArray();
			echo "WO# ".$thisWO[0]['order_number'];
		}else{
			echo "QUOTE# ".$quoteData['quote_number'];
			if($quoteData['revision'] > 0){
				echo " REV ".$quoteData['revision'];
			}
		}
		echo ", LINE# ".$quoteItem['line_number'];

		echo "</h1></center>";
		echo "<table width=\"98%\" cellpadding=\"4\" cellspacing=\"0\" border=\"1\" bordercolor=\"#333333\">

		<thead><tr><th width=\"5%\">#</th><th width=\"15%\">QTY</th><th width=\"15%\">IN</th><th width=\"40%\">COMPONENT</th><th width=\"25%\">COMMENT</th></tr></thead><tbody>";

		for($si=1; $si <= intval($metaArray['_component_numlines']); $si++){

			echo "<tr><td>".$si."</td><td>".$metaArray["_component_".$si."_qty"]."</td><td>".$metaArray["_component_".$si."_inches"]."</td><td>";

			$thisComponentItem=$this->TrackSystems->get($metaArray["_component_".$si."_componentid"])->toArray();

			echo $thisComponentItem['title'];

			echo "</td>

			<td>".$metaArray["_component_".$si."_comment"]."</td></tr>";

		}

		echo "</tbody></table>
		<div style=\"margin-top:10px;\"><button type=\"button\" onclick=\"window.print();\">Print</button></div>

		<style>
		body{font-family:'Helvetica',Arial,sans-serif; }
		table thead tr{ background:#000; }
		table thead tr th{ background:#000; color:#FFF; }
		table tbody tr:nth-of-type(even){ background:#e8e8e8; }
		table tbody tr:nth-of-type(odd){ background:#f8f8f8; }
		table tbody tr td:nth-of-type(1){ background:#444; text-align:center; color:#FFF; }
		</style>";

	}

	

	

	public function generateworkorderform($quoteID){
		$this->set('quoteID',$quoteID);
		$thisOrderFind=$this->Orders->find('all',['conditions' => ['quote_id' => $quoteID]])->toArray();

		foreach($thisOrderFind as $workOrder){
			$this->set('orderData',$workOrder);
		}

		$this->viewBuilder()->layout('iframeinner');		
	}

	

	public function pricelistcheck($product,$secondParam=false,$thirdParam=false){
		$this->set('product',$product);
		$this->set('secondParam',$secondParam);
		$this->set('thirdParam',$thirdParam);
		$allCustomers=$this->Customers->find('all',['order'=>['company_name'=>'asc']])->toArray();
		$this->set('allCustomers',$allCustomers);
	}
	
	
	public function clonelineitem($quoteLineItemID){
	    
	    $original=$this->QuoteLineItems->get($quoteLineItemID)->toArray();
	    $originalMetas=$this->QuoteLineItemMeta->find('all',['conditions' => ['quote_item_id' => $quoteLineItemID]])->toArray();
	    
	    $thisQuote=$this->Quotes->get($original['quote_id'])->toArray();
	    
	    //get the new Line Number for this new item
	    $newLineNumber=0;
	    $highestLineNumber=$this->QuoteLineItems->find('all',['conditions' => ['quote_id' => $original['quote_id']], 'order' => ['line_number' => 'desc']])->limit(1)->toArray();
	    foreach($highestLineNumber as $line){
	        $newLineNumber=(intval($line['line_number']) + 1);
	    }
	    
	    //get the new "sortorder" value
	    $newsortorder=0;
	    $highestSortValues=$this->QuoteLineItems->find('all',['conditions' => ['quote_id' => $original['quote_id']], 'order' => ['sortorder' => 'desc']])->limit(1)->toArray();
	    foreach($highestSortValues as $sort){
	        $newsortorder=(intval($sort['sortorder']) + 1);
	    }
	    
	    $quoteItemsTable=TableRegistry::get('QuoteLineItems');
	    $clone=$quoteItemsTable->newEntity();
	    $clone->type=$original['type'];
	    $clone->quote_id=$original['quote_id'];
	    $clone->status=$original['status'];
	    $clone->product_type=$original['product_type'];
	    $clone->product_id=$original['product_id'];
	    
	    $clone->product_class = $original['product_class'];
		$clone->product_subclass = $original['product_subclass'];
		
	    $clone->title = $original['title'];
	    $clone->description = $original['description'];
	    $clone->best_price = $original['best_price'];
	    $clone->qty = $original['qty'];
	    $clone->lineitemtype = $original['lineitemtype'];
	    $clone->room_number = $original['room_number'];
	    $clone->line_number = $newLineNumber;
	    $clone->internal_line = $original['internal_line'];
	    $clone->enable_tally = $original['enable_tally'];
	    $clone->sortorder = $newsortorder;
	    $clone->unit = $original['unit'];
	    $clone->tier_adjusted_price = $original['tier_adjusted_price'];
	    $clone->install_adjusted_price = $original['install_adjusted_price'];
	    $clone->rebate_adjusted_price = $original['rebate_adjusted_price'];
	    $clone->extended_price = $original['extended_price'];
	    $clone->override_active = $original['override_active'];
	    $clone->override_price = $original['override_price'];
	    $clone->parent_line = $original['parent_line'];
	    $clone->calculator_used = $original['calculator_used'];
	    //$clone->revised_from_line = $original['revised_from_line'];
	    $clone->revised_from_line = NULL;
	    $clone->pmi_adjusted = $original['pmi_adjusted'];
	    $clone->project_management_surcharge_adjusted = $original['project_management_surcharge_adjusted'];
	    
	    $metaDataTable=TableRegistry::get('QuoteLineItemMeta');
	    
	    if($quoteItemsTable->save($clone)){
	        
	        if($thisQuote['status'] == 'editorder'){
                $newAddLog=$this->QuoteItemAddLog->newEntity();
                $newAddLog->original_quote_id = $thisQuote['parent_quote'];
                $newAddLog->revision_id=$thisQuote['id'];
                $newAddLog->line_item_id=$clone->id;
                $newAddLog->addtime=time();
                $newAddLog->line_number=$clone->line_number;
                $this->QuoteItemAddLog->save($newAddLog);
            }
	        
	        //copy the meta data rows
	        foreach($originalMetas as $originalMeta){
	            $newMeta=$metaDataTable->newEntity();
	            $newMeta->meta_key = $originalMeta['meta_key'];
	            $newMeta->meta_value  = $originalMeta['meta_value'];
	            $newMeta->quote_item_id = $clone->id;
	            $metaDataTable->save($newMeta);
	        }
	        
	        $this->logActivity($_SERVER['REQUEST_URI'],'Cloned line '.$original['line_number'].' in Quote# '.$thisQuote['quote_number']);
			$this->Flash->success('Successfully cloned line '.$original['line_number'].' in Quote# '.$thisQuote['quote_number']);
	        return $this->redirect('/quotes/add/'.$original['quote_id'].'/');
	    }
	    
	    
	}
	
	
	public function deletelinenote($noteID){
	    $noteData=$this->QuoteLineItemNotes->get($noteID)->toArray();
	    $thisNote=$this->QuoteLineItemNotes->get($noteID);
	    $this->QuoteLineItemNotes->delete($thisNote);
	    return $this->redirect('/quotes/add/'.$noteData['quote_id']);
	}
	
	
	public function changequotetype($quoteID,$newValue){
	    $this->autoRender=false;

		$quoteTable=TableRegistry::get('Quotes');

		$quoteItem=$quoteTable->get($quoteID);

		$quoteItem->type_id=$newValue;

		if($quoteTable->save($quoteItem)){

			$this->updatequotemodifiedtime($quoteID);

			echo "OK";

		}

		exit;
	}
	
	
	
	public function changecalcitemfabric($currentFabricID){
	    $this->viewBuilder()->layout('iframeinner');
	    $this->autoRender=false;
	    
	    echo "<link rel=\"stylesheet\" href=\"//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css\">
        <script src=\"//ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js\"></script>
        <script src=\"https://code.jquery.com/ui/1.12.1/jquery-ui.js\"></script>
        <style>
	    body{ font-family:Helvetica,Arial,sans-serif; }
	    p select{ width:95%; padding:5px; }
	    </style>
	    
	    <script>
	    function getcolorsthisfabric(fabricname){
	        $.get('/quotes/getfabriccolorlist/'+fabricname+'/false/true',
	        function(resulthtml){
	            $('#colorfield select').html(resulthtml);
	        });
	    }
	    
	    function applyfabricchange(){
	        parent.$('#fabricid').val($('#colorfield select').val());
	        var fabricData=JSON.parse($('#colorfield select option:selected').attr('data-fabricdetails'));
	        console.log(fabricData);
	        
	        //cost per yard - cut
	        parent.$('#fcpcut').val(fabricData.cost_per_yard_cut);
	        
	        //cost per yard - bolt
	        parent.$('#fcpbolt').val(fabricData.cost_per_yard_bolt);
	        
	        //cost per yard - case
	        parent.$('#fcpcase').val(fabricData.cost_per_yard_case);
	        
	        //update cost per yard dropdown labels 
	        parent.$('select[name=\'fabric-cost-per-yard\'] option[value=\'cut\']').html('Cut - $'+fabricData.cost_per_yard_cut.toFixed(2));
	        parent.$('select[name=\'fabric-cost-per-yard\'] option[value=\'bolt\']').html('Bolt - $'+fabricData.cost_per_yard_bolt.toFixed(2));
	        parent.$('select[name=\'fabric-cost-per-yard\'] option[value=\'case\']').html('Case - $'+fabricData.cost_per_yard_case.toFixed(2));
	        
	        //fabric width
	        if(parent.$('#fab-width').length){
	            parent.$('#fab-width').val(fabricData.fabric_width);
	        }else if(parent.$('#fabric-width').length){
	            parent.$('#fabric-width').val(fabricData.fabric_width);
	        }
	        
	        parent.$('#weight-per-sqin').val(fabricData.weight_per_sqin);
	        
	        //railroaded
	        if(fabricData.railroaded==1){
	            parent.$('#railroaded').prop('checked',true);
	        }else{
	            parent.$('#railroaded').prop('checked',false);
	        }
	        
	        //vertical repeat
	        if(parent.$('#vert-repeat').length){
	            parent.$('#vert-repeat').val(fabricData.vertical_repeat);
	        }else if(parent.$('#vertical-repeat').length){
	            parent.$('#vertical-repeat').val(fabricData.vertical_repeat);
	        }
	        
	        //horizontal repeat
	        if(parent.$('#horizontal-repeat').length){
	            parent.$('#horizontal-repeat').val(fabricData.horizontal_repeat);
	        }
	        
	        //antimicrobial
	        if(parent.$('#antimicrobial').length){
	            if(fabricData.antimicrobial == '1'){
	                parent.$('#antimicrobial').prop('checked',true);
	            }else{
	                parent.$('#antimicrobial').prop('checked',false);
	            }
	        }
	        
	        
	        
	        //quilted
	        if(fabricData.quilted==1){
	            parent.$('#quilted').prop('checked',true);
	        }else{
	            parent.$('#quilted').prop('checked',false);
	        }
	        
	        //fabric name
	        parent.$('#fabricname').html(fabricData.fabric_name);
	        
	        //color
	        parent.$('#fabriccolor').html(fabricData.color);
	        
	        
    	    parent.$('.calculatebutton button').trigger('click');
    	    
    	    setTimeout('parent.$(\'.calculatebutton button\').trigger(\'click\')',355);
	        
	        setTimeout('parent.$.fancybox.close()',555);
	        
	    }
	    </script>
	    <h3 style=\"text-align:center;\">Change Fabric for Calculator</h3><hr>";
	    
	    $thisFabric=$this->Fabrics->get($currentFabricID)->toArray();
	    
	    $groupedFabrics=$this->Fabrics->find('all',['conditions'=>['status'=>'Active'],'order'=>['fabric_name'=>'asc']])->group('fabric_name')->toArray();
	    echo "<p><label>Fabric Pattern:</label>
	    <select name=\"fabric_name\" onchange=\"getcolorsthisfabric(this.value)\"><option value=\"0\" disabled>--Select Fabric--</option>";
	    foreach($groupedFabrics as $fabric){
	        echo "<option value=\"".$fabric['fabric_name']."\"";
	        if($fabric['fabric_name'] == $thisFabric['fabric_name']){
	            echo " selected=\"selected\"";
	        }
	        echo ">".$fabric['fabric_name']."</option>";
	        
	    }
	    echo "</select></p>
	    <p id=\"colorfield\"><label>Color:</label>
	    <select name=\"color\"><option value=\"0\" disabled>--Select Color--</option>";
	    $colorsThisFabric=$this->Fabrics->find('all',['conditions' => ['fabric_name' => $thisFabric['fabric_name']],'order'=>['color'=>'asc']])->toArray();
	    foreach($colorsThisFabric as $color){
	        echo "<option value=\"".$color['id']."\"";
	        if($color['id'] == $currentFabricID){ echo " selected=\"selected\""; }
	        echo " data-fabricdetails='".str_replace("'","&#39;",json_encode($color))."'>".$color['color']."</option>";
	    }
	    echo "</select></p>";
	    
	    echo "<p><button style=\"float:left;\" type=\"button\" onclick=\"applyfabricchange()\">Apply Fabric Change</button><button style=\"float:right;\" onclick=\"parent.$.fancybox.close();\">Cancel</button><div style=\"clear:both;\"></div></p>";
	    
	    exit;
	}
	
	
	
	
	public function newbompurchasingnote($quoteID,$type,$fabricID){
	    $this->viewBuilder()->layout('iframeinner');
	    if($this->request->data){
	        $newNote=$this->QuoteBomPurchasingNotes->newEntity();
	        $newNote->quote_id=$quoteID;
	        $newNote->type=$type;
	        $newNote->material=$fabricID;
	        $newNote->time=time();
	        $newNote->user_id=$this->Auth->user('id');
	        $newNote->note=$this->request->data['note'];
	        if($this->QuoteBomPurchasingNotes->save($newNote)){
	            $this->autoRender=false;
	            echo "<script>parent.updateQuoteTable('pnote".$newNote->id."'); parent.$.fancybox.close();</script>";
	            exit;
	        }
	    }else{
	        
	    }
	}
	
	
	public function deletepurchasingnote($noteID){
	    $thisNote=$this->QuoteBomPurchasingNotes->get($noteID);
	    if($this->QuoteBomPurchasingNotes->delete($thisNote)){
	        $this->autoRender=false;
	        echo "OK";exit;
	    }
	}
	
	public function verifyeditordermode($quoteID){
		$thisQuote=$this->Quotes->get($quoteID)->toArray();
        
		$thisQuoteItems=$this->QuoteLineItems->find('all',['conditions'=>['quote_id'=>$quoteID],'order'=>['sortorder'=>'asc','id'=>'asc']])->toArray();
		$notMatchingArray="";
		$titleString ="";
		foreach($thisQuoteItems as $quoteItem){
			   if($quoteItem['override_active'] !=0 && $quoteItem['override_price'] > 0){
				$val = number_format(floatval($quoteItem['override_price']),2,'.','') * $quoteItem['qty'];
					 } 
			   else {
				$val = number_format(floatval($quoteItem['pmi_adjusted']),2,'.','') * $quoteItem['qty'];
				}
			   //if($quoteItem['extended_price'] != $val) {
			    if(strval($quoteItem['extended_price'])!=strval($val)) {
					$notMatchingArray =$notMatchingArray ." , ".$quoteItem['id'];
					$titleString = $titleString . "<br/>" . "Line #".$quoteItem['line_number']." Ex Price Verification failed";

			   }
			}
				if( strlen( $notMatchingArray ) === 0){
			
					echo "OK"; 
				    exit;
				} else {
					echo $titleString; exit;

				}
				    
	}
}