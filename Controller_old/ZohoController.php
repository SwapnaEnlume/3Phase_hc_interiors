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

use zcrmsdk\crm\setup\restclient\ZCRMRestClient;
use zcrmsdk\oauth\ZohoOAuth;
use zcrmsdk\crm\crud\ZCRMJunctionRecord;
use zcrmsdk\crm\crud\ZCRMNote;
use zcrmsdk\crm\crud\ZCRMRecord;
use zcrmsdk\crm\setup\users\ZCRMUser;
use zcrmsdk\crm\bulkcrud\ZCRMBulkCallBack;
use zcrmsdk\crm\bulkcrud\ZCRMBulkCriteria;
use zcrmsdk\crm\bulkcrud\ZCRMBulkQuery;
use zcrmsdk\crm\bulkcrud\ZCRMBulkRead;
use zcrmsdk\crm\bulkcrud\ZCRMBulkWriteFieldMapping;
use zcrmsdk\crm\bulkcrud\ZCRMBulkWriteResource;
use zcrmsdk\crm\utility\ZCRMConfigUtil;


class ZohoController extends AppController{

		

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
		$this->loadModel('Settings');
		$this->loadModel('Zohofullsyncjobs');
		
		ZCRMRestClient::initialize(array("client_id" => $this->getSettingValue('zoho_v2_client_id'), "client_secret" => $this->getSettingValue('zoho_v2_client_secret'), "redirect_uri" => $this->getSettingValue('zoho_v2_redirect_uri'), "currentUserEmail" => "luis@hcinteriors.com", "token_persistence_path" => $_SERVER['DOCUMENT_ROOT']."/config" ));
		
		
	}

	

		

	public function beforeFilter(Event $event){
        parent::beforeFilter($event);
		$this->Auth->allow(['incomingfullsync','testzoho','cronchangequery','cronchangecontactsquery']);
		
    }

    
    public function getgranttoken(){
        $this->redirect('https://accounts.zoho.com/oauth/v2/auth?response_type=code&client_id='.$this->getSettingValue('zoho_v2_client_id').'&scope=aaaserver.profile.READ,ZohoCRM.modules.ALL,ZohoCRM.bulk.all&redirect_uri='.urlencode('https://orders.hcinteriors.com/zoho/incoming/').'&prompt=consent&access_type=offline');
    }

	
    public function gentoken(){
        $this->autoRender=false;
        $oAuthClient = ZohoOAuth::getClientInstance();
        $grantToken = $this->getSettingValue('zoho_v2_granttoken');		// ZohoCRM.modules.all
        try {
        	$oAuthTokens = $oAuthClient->generateAccessToken($grantToken);
        	
        	$settingsTable=TableRegistry::get('Settings');
    		$setting=$settingsTable->get(156);
    		$setting->setting_value = $oAuthTokens->getAccessToken();
    		$settingsTable->save($setting);
    		
    		$settingsTable=TableRegistry::get('Settings');
    		$setting=$settingsTable->get(159);
    		$setting->setting_value = $oAuthTokens->getExpiryTime();
    		$settingsTable->save($setting);
    		
        	
        	echo "<pre>";
        	print_r($oAuthTokens);
        	echo "</pre>";
        }
        catch (Exception $e) {
        	echo '<pre>Could not get access token: '.$e->getMessage()."\n";
        	echo '$grantToken = '.$grantToken."\n";
        }
        exit;
    }
    
    

	public function testzoho($acctNum="1097025000038054001"){
		$this->autoRender=false;
		
       $moduleIns = ZCRMRestClient::getInstance()->getModuleInstance("Accounts"); // To get module instance
        $response = $moduleIns->getRecord($acctNum); // To get module records
        $record = $response->getData(); // To get response data
        echo "<pre>";
        print_r($record);
         $zohoOnCreditHold=0;
                if($record->getFieldValue('CREDIT_HOLD') == 'YES' ||$record->getFieldValue('CREDIT_HOLD') == '1' ) { $zohoOnCreditHold=1; }
                 $zohoDepositePerc=preg_replace('/[^0-9]/', '', $record->getFieldValue('Deposit_Required1')); 
                  $zohoDepoistThreshold=preg_replace('/[^0-9]/', '', $record->getFieldValue('Deposit_Required_Threshold'));
                 $zohoPaymentTerms=$record->getFieldValue('Payment_Terms');
                echo $zohoOnCreditHold . $zohoDepositePerc.$zohoDepoistThreshold.$zohoPaymentTerms;
        echo "</pre>";
       /* $moduleIns = ZCRMRestClient::getInstance()->getModuleInstance("Accounts");
	        $response = $moduleIns->getRecords(array('per_page' => 500),array("if-modified-since" => date('c',(time()-500) )));
	    
	        $records=$response->getData();
	        
         foreach($records as $record){
            echo "MODTIME=".$record->getModifiedTime()."<hr>";
            $zohoOnCreditHold=0;
                if($record->getFieldValue('CREDIT_HOLD') == 'YES' ||$record->getFieldValue('CREDIT_HOLD') == '1' ) { $zohoOnCreditHold=1; }
                echo $zohoOnCreditHold;
        }
        
        $moduleIns = ZCRMRestClient::getInstance()->getModuleInstance("Accounts");
        $response = $moduleIns->getRecords(array('per_page' => 500),array("if-modified-since" => date('c',(time()-4000) )));
    
        $records=$response->getData();
        
        foreach($records as $record){
            echo "MODTIME=".$record->getModifiedTime()."<hr>";
            $zohoOnCreditHold=0;
                if($record->getFieldValue('CREDIT_HOLD') == 'YES' ||$record->getFieldValue('CREDIT_HOLD') == '1' ) { $zohoOnCreditHold=1; }
        }*/
		exit;
	}
	
	
	public function cronchangequery($overridetime=0){
	    $this->autoRender=false;
	    $customerTable=TableRegistry::get('Customers');
	    $contactTable=TableRegistry::get('CustomerContacts');
	    
	    
	    //check for NEW bulk jobs and trigger a status checker
	    $pendingBulk=$this->Zohofullsyncjobs->find('all',['conditions'=>['status'=>'new']])->toArray();
	    foreach($pendingBulk as $bulk){
	        $this->getfullsyncstatus($bulk->job_id);
	    }
	    
	    
	    $lastCheck=intval($this->getSettingValue('zoho_v2_last_refresh'));
	    $difference=(time()-$lastCheck);
	    
	    if($overridetime > 0){
	        $timeoffset=$overridetime;
	    }else{
    	    if($difference > 500){
    	        $timeoffset=((time()-$lastCheck)+100);
    	    }else{
    	        $timeoffset=500;
    	    }
	    }
	    
	    //BEGIN ACCOUNT CHANGES QUERY
	    try{
	        
	        $moduleIns = ZCRMRestClient::getInstance()->getModuleInstance("Accounts");
	        $response = $moduleIns->getRecords(array('per_page' => 500),array("if-modified-since" => date('c',(time()-$timeoffset) )));
	   // 
	        $records=$response->getData();
	        
    	    $crondata=TableRegistry::get('Crondatatest');
    	    $newCronEntry=$crondata->newEntity();
    	    //$newCronEntry->raw_response = print_r($response,1);
    	    $newCronEntry->raw_response='intentionally-blank';
    	    $newCronEntry->time=time();
    	    $newCronEntry->type='Accounts';
    	    $crondata->save($newCronEntry);
    	    
    	    foreach($records as $record){
    	        $zohoAccountID=$record->getEntityId();
    	        
    	        $zohoAccountName=$record->getFieldValue('Account_Name');
    	        $zohoCreatedTimestamp = strtotime($record->getCreatedTime());
    	        $zohoLastUpdatedTimestamp=strtotime($record->getModifiedTime());
    	        $zohoBillingAddress=$record->getFieldValue('Billing_Street');
    	        $zohoBillingCity=$record->getFieldValue('Billing_City');
    	        $zohoBillingState=$record->getFieldValue('Billing_State');
    	        $zohoBillingZipcode = $record->getFieldValue('Billing_Code');
    	        $zohoBillingCountry = $record->getFieldValue('Billing_Country');
    	        $zohoShippingAddress=$record->getFieldValue('Shipping_Street');
    	        $zohoShippingCity=$record->getFieldValue('Shipping_City');
    	        $zohoShippingState=$record->getFieldValue('Shipping_State');
    	        $zohoShippingZipcode = $record->getFieldValue('Shipping_Code');
    	        $zohoShippingCountry = $record->getFieldValue('Shipping_Country');
    	        $zohoPhone = $record->getFieldValue('Phone');
    	        $zohoFax = $record->getFieldValue('Fax');
    	        $zohoWebsite = $record->getFieldValue('Website');
    	        
    	        $zohoOnhold=$record->getFieldValue('ONHOLD');
    	        
    	        $zohoSurchargePercent=$record->getFieldValue('ADD');
                $zohoSurchargeDollar=$record->getFieldValue('Surcharge');
                
                $zohoTierCCsSplit=explode(" (",$record->getFieldValue('Tier_CCs'));
                if(strlen(trim($zohoTierCCsSplit[0])) >0){ $zohoTierCCs=$zohoTierCCsSplit[0]; }else{ $zohoTierCCs=''; }
                
                $zohoTierBSsSplit=explode(" (",$record->getFieldValue('Tier_BSs'));
                if(strlen(trim($zohoTierBSsSplit[0])) >0){ $zohoTierBSs=$zohoTierBSsSplit[0]; }else{ $zohoTierBSs=''; }
                
                $zohoTierSWTsSplit=explode(" (",$record->getFieldValue('Tier_SWT'));
                if(strlen(trim($zohoTierSWTsSplit[0])) >0){ $zohoTierSWTs=$zohoTierSWTsSplit[0]; }else{ $zohoTierSWTs=''; }
                
                $zohoTierHWTsSplit=explode(" (",$record->getFieldValue('Tier_HWTs'));
                if(strlen(trim($zohoTierHWTsSplit[0])) >0){ $zohoTierHWTs=$zohoTierHWTsSplit[0]; }else{ $zohoTierHWTs=''; }
                
                $zohoTierTrackSplit=explode(" (",$record->getFieldValue('Tier_Track'));
                if(strlen(trim($zohoTierTrackSplit[0])) >0){ $zohoTierTrack=$zohoTierTrackSplit[0]; }else{ $zohoTierTrack=''; }
                
                $zohoTierInstallSplit=explode(" (",$record->getFieldValue('Tier_Install'));
                if(strlen(trim($zohoTierInstallSplit[0])) >0){ $zohoTierInstall=$zohoTierInstallSplit[0]; }else{ $zohoTierInstall=''; }
                
                $zohoTierFabricSplit=explode(" (",$record->getFieldValue('Tier_Fabric'));
                if(strlen(trim($zohoTierFabricSplit[0])) >0){ $zohoTierFabric=$zohoTierFabricSplit[0]; }else{ $zohoTierFabric=''; }
                
                /**PPSASCRUM_3 **/
                    $zohoOnCreditHold=0;
                if($record->getFieldValue('CREDIT_HOLD') == 'YES' ||$record->getFieldValue('CREDIT_HOLD') == '1' ) { $zohoOnCreditHold=1; }
                 $zohoDepositePerc=preg_replace('/[^0-9]/', '', $record->getFieldValue('Deposit_Required1')); 
                  $zohoDepoistThreshold=preg_replace('/[^0-9]/', '', $record->getFieldValue('Deposit_Required_Threshold'));
                 $zohoPaymentTerms=$record->getFieldValue('Payment_Terms');
                
                
                /**PPSASCRUM-3 **/
    	        
    	        $customerLookup=$this->Customers->find('all',['conditions'=>['zoho_account_id' => $zohoAccountID]])->toArray();
    	        
    	        if(count($customerLookup) == 0){
					//does not exist in the system! create it!
					
					
					//////////////////////////////////////////
					
					$newCustomer=$customerTable->newEntity();
					$newCustomer->zoho_account_id=$zohoAccountID;
					$newCustomer->company_name=$zohoAccountName;
					$newCustomer->zoho_created=$zohoCreatedTimestamp;
					$newCustomer->zoho_last_updated=$zohoLastUpdatedTimestamp;
					$newCustomer->billing_address=$zohoBillingAddress;
					$newCustomer->billing_address_city=$zohoBillingCity;
					$newCustomer->billing_address_state=$zohoBillingState;
					$newCustomer->billing_address_zipcode=$zohoBillingZipcode;
					$newCustomer->billing_address_country=$zohoBillingCountry;
					$newCustomer->shipping_address=$zohoShippingAddress;
					$newCustomer->shipping_address_city=$zohoShippingCity;
					$newCustomer->shipping_address_state=$zohoShippingState;
					$newCustomer->shipping_address_zipcode=$zohoShippingZipcode;
					$newCustomer->shipping_address_country=$zohoShippingCountry;
					$newCustomer->phone=$zohoPhone;
					$newCustomer->fax=$zohoFax;
					$newCustomer->website=$zohoWebsite;
					$newCustomer->local_created=time();
					$newCustomer->local_last_updated=time();
					//$newCustomer->surcharge_addon=$zohoCustomerSurcharge;

					$newCustomer->surcharge_percent=$zohoSurchargePercent;
					$newCustomer->surcharge_dollars=$zohoSurchargeDollar;

                    if(intval($zohoOnhold)==1){
                        $newCustomer->status='OnHold';
                    }else{
                        $newCustomer->status='Active';
                    }

					$newCustomer->source='zoho';
					$newCustomer->tier_cc=$zohoTierCCs;
					$newCustomer->tier_bs=$zohoTierBSs;
					$newCustomer->tier_swt=$zohoTierSWTs;
					$newCustomer->tier_hwt=$zohoTierHWTs;
					$newCustomer->tier_track=$zohoTierTrack;
					$newCustomer->tier_install=$zohoTierInstall;
					$newCustomer->tier_fabric=$zohoTierFabric;

                    /** PPSASCRUM_3 start **/
                    $newCustomer->on_credit_hold=$zohoOnCreditHold;
                    $newCustomer->deposit_threshold=$zohoDepoistThreshold;
                    $newCustomer->deposit_perc=$zohoDepositePerc;
                    $newCustomer->payment_terms=$zohoPaymentTerms;
                    /** PPSASCRUM-3 end **/
					
					if($customerTable->save($newCustomer)){
						//created!
						$newCustomerID=$newCustomer->id;
						echo "Imported Zoho Customer <B>".$zohoAccountName."</b><br>";
						//$this->logactivity($_SERVER['REQUEST_URI'],"Added new Customer from ZOHO \"".$zohoAccountName."\"",-1,$data);
						$this->logactivity($_SERVER['REQUEST_URI'],"Added new Customer from ZOHO \"".$zohoAccountName."\"",-1);
					}
					
					
					//////////////////////////////////////////
    	        }else{
    	            //exists, update the data fields
    	            foreach($customerLookup as $customerRow){
    	                if($customerRow['zoho_last_updated'] != $zohoLastUpdatedTimestamp){ 
    	                    //the record has not already been updated... DO IT
    	                    
    	                    //////////////////////////////////
    	                    $changeCount=0;
    	                    
    	                    $thisCustomer=$customerTable->get($customerRow['id']);
    	                    if($customerRow['company_name'] != $zohoAccountName){
    	                        $thisCustomer->company_name=$zohoAccountName;
    	                        $changeCount++;
    	                    }
    	                    
    	                    
    	                    if($customerRow['status'] != 'OnHold' && intval($zohoOnhold) == 1){
    	                        $thisCustomer->status='OnHold';
    	                        $changeCount++;
    	                    }elseif($customerRow['status'] == 'OnHold' && intval($zohoOnhold) != 1){
    	                        $thisCustomer->status='Active';
    	                        $changeCount++;
    	                    }
    	                    
    	                    
        					if($customerRow['billing_address'] != $zohoBillingAddress){
        					    $thisCustomer->billing_address=$zohoBillingAddress;
        					    $changeCount++;
        					}
        					
        					if($customerRow['billing_address_city'] != $zohoBillingCity){
        					    $thisCustomer->billing_address_city=$zohoBillingCity;
        					    $changeCount++;
        					}
        					
        					if($customerRow['billing_address_state'] != $zohoBillingState){
        					    $thisCustomer->billing_address_state=$zohoBillingState;
        					    $changeCount++;
        					}
        					
        					if($customerRow['billing_address_zipcode'] != $zohoBillingZipcode){
        					    $thisCustomer->billing_address_zipcode=$zohoBillingZipcode;
        					    $changeCount++;
        					}
        					
        					if($customerRow['billing_address_country'] != $zohoBillingCountry){
        					    $thisCustomer->billing_address_country=$zohoBillingCountry;
        					    $changeCount++;
        					}
        					
        					
        					if($customerRow['shipping_address'] != $zohoShippingAddress){
        					    $thisCustomer->shipping_address=$zohoShippingAddress;
        					    $changeCount++;
        					}
        					
        					
        					if($customerRow['shipping_address_city'] != $zohoShippingCity){
        					    $thisCustomer->shipping_address_city=$zohoShippingCity;
        					    $changeCount++;
        					}
        					
        					if($customerRow['shipping_address_state'] != $zohoShippingState){
        					    $thisCustomer->shipping_address_state=$zohoShippingState;
        					    $changeCount++;
        					}
        					
        					
        					if($customerRow['shipping_address_zipcode'] != $zohoShippingZipcode){
        					    $thisCustomer->shipping_address_zipcode=$zohoShippingZipcode;
        					    $changeCount++;
        					}
        					
        					if($customerRow['shipping_address_country'] != $zohoShippingCountry){
        					    $thisCustomer->shipping_address_country=$zohoShippingCountry;
        					    $changeCount++;
        					}
        					
        					if($customerRow['phone'] != $zohoPhone){
        					    $thisCustomer->phone=$zohoPhone;
        					    $changeCount++;
        					}
        					
        					if($customerRow['fax'] != $zohoFax){
        					    $thisCustomer->fax=$zohoFax;
        					    $changeCount++;
        					}
        					
        					if($customerRow['website'] != $zohoWebsite){
        					    $thisCustomer->website=$zohoWebsite;
        					    $changeCount++;
        					}
        					
        
        					if($customerRow['surcharge_percent'] != $zohoSurchargePercent){
        					    $thisCustomer->surcharge_percent=$zohoSurchargePercent;
        					    $changeCount++;
        					}
        					
        					if($customerRow['surcharge_dollars'] != $zohoSurchargeDollar){
        					    $thisCustomer->surcharge_dollars=$zohoSurchargeDollar;
        					    $changeCount++;
        					}
        
        					if($customerRow['tier_cc'] != $zohoTierCCs){
        					    $thisCustomer->tier_cc=$zohoTierCCs;
        					    $changeCount++;
        					}
        					
        					if($customerRow['tier_bs'] != $zohoTierBSs){
        					    $thisCustomer->tier_bs=$zohoTierBSs;
        					    $changeCount++;
        					}
        					
        					if($customerRow['tier_swt'] != $zohoTierSWTs){
        					    $thisCustomer->tier_swt=$zohoTierSWTs;
        					    $changeCount++;
        					}
        					
        					if($customerRow['tier_hwt'] != $zohoTierHWTs){
        					    $thisCustomer->tier_hwt=$zohoTierHWTs;
        					    $changeCount++;
        					}
        					
        					if($customerRow['tier_track'] != $zohoTierTrack){
        					    $thisCustomer->tier_track=$zohoTierTrack;
        					    $changeCount++;
        					}
        					
        					if($customerRow['tier_install'] != $zohoTierInstall){
        					    $thisCustomer->tier_install=$zohoTierInstall;
        					    $changeCount++;
        					}
        					
        					if($customerRow['tier_fabric'] != $zohoTierFabric){
        					    $thisCustomer->tier_fabric=$zohoTierFabric;
        					    $changeCount++;
        					}
        					
                            /**PPSASCRUM-3 start **/
                        
                $checkzohoOnCreditHold=0;
                if($record->getFieldValue('CREDIT_HOLD') == 'YES' ||$record->getFieldValue('CREDIT_HOLD') == '1' ) { $checkzohoOnCreditHold=1; }
                            if($customerRow['on_credit_hold'] != $checkzohoOnCreditHold){
                                $thisCustomer->on_credit_hold=(int)$checkzohoOnCreditHold;
                                $changeCount++;
                            }
                            
                            if($customerRow['deposit_threshold'] != preg_replace('/[^0-9]/', '', $record->getFieldValue('Deposit_Required_Threshold'))){
                                $thisCustomer->deposit_threshold=preg_replace('/[^0-9]/', '', $record->getFieldValue('Deposit_Required_Threshold'));
                                $changeCount++;
                            }
                            
                            if($customerRow['deposit_perc'] != preg_replace('/[^0-9]/', '', $record->getFieldValue('Deposit_Required1'))){
                                $thisCustomer->deposit_perc=preg_replace('/[^0-9]/', '', $record->getFieldValue('Deposit_Required1'));
                                $changeCount++;
                            }

                            if($customerRow['payment_terms'] != $record->getFieldValue('Payment_Terms')){
                                $thisCustomer->payment_terms=$record->getFieldValue('Payment_Terms');
                                $changeCount++;
                            }
                            /**PPSASCRUM-3 end **/


        					if($changeCount > 0){
        					    $thisCustomer->local_last_updated=time();
        					    $thisCustomer->zoho_last_updated=$zohoLastUpdatedTimestamp;
        					     
        					    if($customerTable->save($thisCustomer)){
        					        
        					        //echo "Updated changes for customer <b>".$zohoAccountName."</b> from ZOHO<br>";
										$this->logactivity($_SERVER['REQUEST_URI'],"Updated changes to Customer \"".$zohoAccountName."\" from ZOHO",-1);
											$this->logactivity($_SERVER['REQUEST_URI']," changes to Customer " .$zohoPaymentTerms."ZOHO Value : ".$record->getFieldValue('Payment_Terms')
											.$zohoDepositPerc."ZOHO Value : ".$record->getFieldValue('Deposit_Required1')."ZOHO Value : ".$record->getFieldValue('Deposit_Required_Threshold'). $zohoDepositThreshold.
											$zohoOnCreditHold."ZOHO Value : ".$record->getFieldValue('CREDIT_HOLD')
											."\"".$zohoAccountName."\" from ZOHO",-1);
        					    }   
        					}
    	                    
    	                    //////////////////////////////////
    	                } else {
    	                    $this->logactivity($_SERVER['REQUEST_URI'],"ZOHO update test in else\"".$zohoAccountID."-".$zohoDepositePerc."::".$zohoOnCreditHold."\" from ZOHO".print_r($record), -1);
    	                    
    	                }
    	            }
    	        }
    	    }
    	    
	    
	    } catch(\zcrmsdk\crm\exception\ZCRMException $ex){
	        
	        switch($ex->getExceptionCode()){
	           case 'NOT MODIFIED':
	                //we did not find any changes... continue
	                //echo "No Account Changes Found<br>";
	                $crondata=TableRegistry::get('Crondatatest');
            	    $newCronEntry=$crondata->newEntity();
            	    $newCronEntry->raw_response = 'No changes';
            	    $newCronEntry->time=time();
            	    $newCronEntry->type='Accounts';
            	    $crondata->save($newCronEntry);
            	    
            	    
	           break;
	           case 'NO CONTENT':
	               //no response received..PANIC
	               echo "Request failed";
	           break;
	        }
	        
	    }
	    
	    //END ACCOUNT CHANGES QUERY
	    
	    
	    //BEGIN CONTACTS CHANGES QUERY
	    
	    
	    try{
	        $cmoduleIns = ZCRMRestClient::getInstance()->getModuleInstance("Contacts");
	    
	        $cresponse = $cmoduleIns->getRecords(array('per_page' => 500),array("if-modified-since" => date('c',(time()-$timeoffset) )));
	    
	        $records=$cresponse->getData();
	    
    	    $ccrondata=TableRegistry::get('Crondatatest');
    	    $cnewCronEntry=$ccrondata->newEntity();
    	    //$cnewCronEntry->raw_response = print_r($cresponse,1);
    	    $cnewCronEntry->raw_response='intentionally-blank';
    	    $cnewCronEntry->time=time();
    	    $cnewCronEntry->type='Contacts';
    	    $ccrondata->save($cnewCronEntry);
    	    
    	    ////////////////////////////////////////////////////
    	    
    	    
    	    foreach($records as $record){
    	        $zohoContactID=$record->getEntityId();
    	    
    	        
    	        $zohoContactFirstName=$record->getFieldValue('First_Name');
                $zohoContactLastName=$record->getFieldValue('Last_Name');
                $zohoContactEmailAddress=$record->getFieldValue('Email');
                $zohoContactSecondaryEmail=$record->getFieldValue('Secondary_Email');
                $zohoContactLastUpdated=strtotime($record->getModifiedTime());
                $zohoContactCreated=strtotime($record->getCreatedTime());
                $contactCustomerID=0;
                $zohoContactPhone=$record->getFieldValue('Phone');
                $zohoContactOtherPhone='';
                $zohoContactMobilePhone=$record->getFieldValue('Mobile');
    	        
    	        
    	        //figure out if this contact already exists and needs editing, or needs to be inserted
    	        $contactLookup=$this->CustomerContacts->find('all',['conditions'=>['zoho_contact_id' => $zohoContactID]])->toArray();
    	        
    	        if(count($contactLookup) == 0){
    	            
            	    $newContact=$contactTable->newEntity();
            	    
            	    //lookup customer id
            	    if(is_object($record->getFieldValue('Account_Name'))){
            	        $zohoCustomerID=$record->getFieldValue('Account_Name')->getEntityId();
            	        $customerSearch=$this->Customers->find('all',['conditions'=>['zoho_account_id' => $zohoCustomerID ]])->toArray();
                	    foreach($customerSearch as $customerSearchRes){
                	        $contactCustomerID=$customerSearchRes['id'];
                	    }
            	    }
            	    
            	    
            	    
        			$newContact->first_name=$zohoContactFirstName;
        			$newContact->last_name=$zohoContactLastName;
        			$newContact->email_address=$zohoContactEmailAddress;
        			$newContact->secondary_email=$zohoContactSecondaryEmail;
        			$newContact->zoho_contact_id=$zohoContactID;
        			$newContact->zoho_last_updated=$zohoContactLastUpdated;
        			$newContact->zoho_created=$zohoContactCreated;
        			$newContact->customer_id=$contactCustomerID;
        			$newContact->phone=$zohoContactPhone;
        			$newContact->other_phone='';
        			$newContact->mobile_phone=$zohoContactMobilePhone;
        			$newContact->local_created=time();
        			$newContact->local_last_updated=time();
        			
        			if($contactTable->save($newContact)){
						$newContactID=$newContact->id;
						echo "Imported Zoho Contact <B>".$zohoContactFirstName." ".$zohoContactLastName."</b><br>";
						$this->logactivity($_SERVER['REQUEST_URI'],"Added new Contact from ZOHO \"".$zohoContactFirstName." ".$zohoContactLastName."\"",-1);
					}
        			
    	        }else{
    	            
    	            foreach($contactLookup as $contactRow){
    	                
    	                if($contactRow['zoho_last_updated'] != $zohoContactLastUpdated){
    	                    //it has changed since last check
    	                    $thisContact=$contactTable->get($contactRow['id']);
    	                    $changeCount=0;
    	                    
    	                    if($contactRow['first_name'] != $zohoContactFirstName){
    	                        $thisContact->first_name=$zohoContactFirstName;
    	                        $changeCount++;
    	                    }
    	                    
    	                    if($contactRow['last_name'] != $zohoContactLastName){
                			    $thisContact->last_name=$zohoContactLastName;
                			    $changeCount++;
    	                    }
    	                    
    	                    if($contactRow['email_address'] != $zohoContactEmailAddress){
                			    $thisContact->email_address=$zohoContactEmailAddress;
                			    $changeCount++;
    	                    }
    	                    
                			if($contactRow['secondary_email'] != $zohoContactSecondaryEmail){
                			    $thisContact->secondary_email=$zohoContactSecondaryEmail;
                			    $changeCount++;
                			}
                			
                			$thisContact->zoho_last_updated=$zohoContactLastUpdated;
                			
                			if($contactRow['phone'] != $zohoContactPhone){
                			    $thisContact->phone=$zohoContactPhone;
                			    $changeCount++;
                			}
                			
                			if($contactRow['other_phone'] != ''){
                			    $thisContact->other_phone='';
                			    $changeCount++;
                			}
                			
                			if($contactRow['mobile_phone'] != $zohoContactMobilePhone){
                			    $thisContact->mobile_phone=$zohoContactMobilePhone;
                			    $changeCount++;
                			}
                			
                			if($changeCount > 0){
                			    
                			    if($contactTable->save($thisContact)){
                			        echo "Updated changes for Contact <b>".$zohoContactFirstName." ".$zohoContactLastName."</b> from ZOHO<br>";
									$this->logactivity($_SERVER['REQUEST_URI'],"Updated changes to Contact \"".$zohoContactFirstName." ".$zohoContactLastName."\" from ZOHO",-1);
                			        
                			    }
                			    
                			}
    	                }
    	                
    	            }
    	            
    	        }
    	    }
    	    
    	    ///////////////////////////////////////////////////
    	    
    	    
	    } catch(\zcrmsdk\crm\exception\ZCRMException $ex){
	        
	        switch($ex->getExceptionCode()){
	           case 'NOT MODIFIED':
	                //we did not find any changes... continue
	                //echo "No Contacts changes found<br>";
	                $ccrondata=TableRegistry::get('Crondatatest');
            	    $cnewCronEntry=$ccrondata->newEntity();
            	    $cnewCronEntry->raw_response = 'No Changes';
            	    $cnewCronEntry->time=time();
            	    $cnewCronEntry->type='Contacts';
            	    $ccrondata->save($cnewCronEntry);
	           break;
	           case 'NO CONTENT':
	               //no response received..PANIC
	           break;
	        }
	        
	    }
	    //END CONTACTS CHANGES QUERY
	    
	    
	    //check for DELETED ACCOUNTS
	    try{
	        $moduleIns = ZCRMRestClient::getInstance()->getModuleInstance("Accounts");
	    
    	    $findDeletedAccounts=$moduleIns->getAllDeletedRecords(array('per_page' => 500),array("if-modified-since" => date('c',(time()-$timeoffset)) ));
    	    
    	    $deletedAccounts=$findDeletedAccounts->getData();
    	    
    	    
    	    $crondata=TableRegistry::get('Crondatatest');
    	    $newCronEntry=$crondata->newEntity();
    	    //$newCronEntry->raw_response = print_r($findDeletedAccounts,1);
    	    $newCronEntry->raw_response='intentionally-blank';
    	    $newCronEntry->time=time();
    	    $newCronEntry->type='Deleted Accounts';
    	    $crondata->save($newCronEntry);
    	    
    	    foreach($deletedAccounts as $num => $accountData){
    	        
    	        $lookupLocal=$this->Customers->find('all',['conditions'=>['zoho_account_id' => $accountData->getEntityId()]])->toArray();
    	        foreach($lookupLocal as $localCustomer){
    	            if($localCustomer['status'] != 'Deleted'){
        	            $thisCustomer=$customerTable->get($localCustomer['id']);
        	            $thisCustomer->status = 'Deleted';
        	            $thisCustomer->zoho_last_updated = strtotime($accountData->getDeletedTime());
        	            if($customerTable->save($thisCustomer)){
            	            echo "Marked customer <b>".$localCustomer['company_name']."</b> as Deleted per ZOHO<br>";
        					$this->logactivity($_SERVER['REQUEST_URI'],"Marked customer \"".$localCustomer['company_name']."\" as Deleted per ZOHO",-1);
        	            }
    	            }
    	            
    	        }
    	        
    	    }
    	    
	    } catch(\zcrmsdk\crm\exception\ZCRMException $ex){
	        
	        switch($ex->getExceptionCode()){
	           case 'NOT MODIFIED':
	                //we did not find any changes... continue
	                //echo "No Contacts changes found<br>";
	                $ccrondata=TableRegistry::get('Crondatatest');
            	    $cnewCronEntry=$ccrondata->newEntity();
            	    $cnewCronEntry->raw_response = 'No Changes';
            	    $cnewCronEntry->time=time();
            	    $cnewCronEntry->type='Deleted Accounts';
            	    $ccrondata->save($cnewCronEntry);
	           break;
	           case 'NO CONTENT':
	               //no response received..PANIC
	           break;
	        }
	        
	    }   
	    //end check for DELETED ACCOUNTS
	    
	    
	    //check for DELETED CONTACTS
	    try{
	        
    	    $moduleIns = ZCRMRestClient::getInstance()->getModuleInstance("Contacts");
    	    
    	    $findDeletedContacts=$moduleIns->getAllDeletedRecords(array('per_page' => 500),array("if-modified-since" => date('c',(time()-$timeoffset)) ));
    	    
    	    $deletedContacts=$findDeletedContacts->getData();
    	    
    	    
    	    
    	    
    	    $crondata=TableRegistry::get('Crondatatest');
    	    $newCronEntry=$crondata->newEntity();
    	    //$newCronEntry->raw_response = print_r($findDeletedContacts,1);
    	    $newCronEntry->raw_response='intentionally-blank';
    	    $newCronEntry->time=time();
    	    $newCronEntry->type='Deleted Contacts';
    	    $crondata->save($newCronEntry);
    	    
    	    
    	    foreach($deletedContacts as $num => $contactData){
    	        
    	        $lookupLocal=$this->CustomerContacts->find('all',['conditions'=>['zoho_contact_id' => $contactData->getEntityId()]])->toArray();
    	        foreach($lookupLocal as $localContact){
    	            if($localContact['status'] != 'Deleted'){
        	            $thisContact=$contactTable->get($localContact['id']);
        	            $thisContact->status = 'Deleted';
        	            $thisContact->zoho_last_updated = strtotime($contactData->getDeletedTime());
        	            if($contactTable->save($thisContact)){
            	            echo "Marked contact <b>".$localContact['first_name']." ".$localContact['last_name']."</b> as Deleted per ZOHO<br>";
        					$this->logactivity($_SERVER['REQUEST_URI'],"Marked contact \"".$localContact['first_name']." ".$localContact['last_name']."\" as Deleted per ZOHO",-1);
        	            }
    	            }
    	            
    	        }
    	        
    	    }
	    
	    } catch(\zcrmsdk\crm\exception\ZCRMException $ex){
	        
	        switch($ex->getExceptionCode()){
	           case 'NOT MODIFIED':
	                //we did not find any changes... continue
	                //echo "No Contacts changes found<br>";
	                $ccrondata=TableRegistry::get('Crondatatest');
            	    $cnewCronEntry=$ccrondata->newEntity();
            	    $cnewCronEntry->raw_response = 'No Changes';
            	    $cnewCronEntry->time=time();
            	    $cnewCronEntry->type='Deleted Contacts';
            	    $ccrondata->save($cnewCronEntry);
	           break;
	           case 'NO CONTENT':
	               //no response received..PANIC
	           break;
	        }
	        
	    }   
	    //end check for DELETED CONTACTS
	    
	    
	    $settingsTable=TableRegistry::get('Settings');
	    $thisSetting=$settingsTable->get(163);
	    $thisSetting->setting_value=time();
	    $settingsTable->save($thisSetting);
	    
	    exit;
	}
	
	public function testinsert(){
	    $this->autoRender=false;
	    
	    //attempt to create an Account in ZOHO
	    
	    $newZohoRecord = ZCRMRestClient::getInstance()->getRecordInstance("Accounts", NULL);
			
		$newZohoRecord->setFieldValue('Account_Name','Test4 Jan142020');
		$newZohoRecord->setFieldValue('Website','test4jan14.1callservice.net');
		$newZohoRecord->setFieldValue('ADD','4');
		$newZohoRecord->setFieldValue('Surcharge','2.50');
		$newZohoRecord->setFieldValue('Billing_Street','1234 Fake Street');
		$newZohoRecord->setFieldValue('Billing_City','Dallas');
		$newZohoRecord->setFieldValue('Billing_State','Texas');
		$newZohoRecord->setFieldValue('Billing_Code','75252');
		$newZohoRecord->setFieldValue('Billing_Country','US');
		$newZohoRecord->setFieldValue('Shipping_Street','1234 Fake Street');
		$newZohoRecord->setFieldValue('Shipping_City','Dallas');
		$newZohoRecord->setFieldValue('Shipping_State','Texas');
		$newZohoRecord->setFieldValue('Shipping_Code','75252');
		$newZohoRecord->setFieldValue('Shipping_Country','US');
		
		$newZohoRecord->setFieldValue('Phone','8883334444');
		$newZohoRecord->setFieldValue('Fax','4443332222');
		
		
		$newZohoRecord->setFieldValue('Tier_CCs','7');
        $newZohoRecord->setFieldValue('Tier_BSs','7');
        $newZohoRecord->setFieldValue('Tier_SWT','7');
        $newZohoRecord->setFieldValue('Tier_HWTs','7');
        $newZohoRecord->setFieldValue('Tier_Track','7');
        $newZohoRecord->setFieldValue('Tier_Install','7');
        $newZohoRecord->setFieldValue('Tier_Fabric','7');
	    
	    
	    $responseIns = $newZohoRecord->create();
        
        /*if($responseIns->getCode() == 'SUCCESS'){
            //created!
            $recordData=$responseIns->getDetails();
            echo "Successfully created zoho record <b>".$recordData['id']."</b>";
        }else{*/
            echo "HTTP Status Code:" . $responseIns->getHttpStatusCode(); // To get http response code
            echo "<Br>";
            echo "Status:" . $responseIns->getStatus(); // To get response status
            echo "<Br>";
            echo "Message:" . $responseIns->getMessage(); // To get response    message
            echo "<Br>";
            echo "Code:" . $responseIns->getCode(); // To get status code
            echo "<Br>";
            echo "Details:" . json_encode($responseIns->getDetails());
        //}
	    
	    //attempt to add a Contact to the new account in ZOHO
	    exit;
	    
	}
	
	public function incoming(){
	    $this->autoRender=false;
	    
	    $settingsTable=TableRegistry::get('Settings');
		$setting=$settingsTable->get(165);
		$setting->setting_value = $_REQUEST['code'];
		$settingsTable->save($setting);
		
	    $file=fopen($_SERVER['DOCUMENT_ROOT']."/RECEIVED.TXT","w+");
	    fwrite($file,print_r($_REQUEST,1));
	    fclose($file);
	    echo "<pre>";
	    print_r($_REQUEST);
	    echo "</pre>";

	    
	    exit;
	}
	
	
	public function testdelete(){
	    $this->autoRender=false;
	    $customerTable=TableRegistry::get('Customers');
	    try{
	        $moduleIns = ZCRMRestClient::getInstance()->getModuleInstance("Accounts");
	    
    	    $findDeletedAccounts=$moduleIns->getAllDeletedRecords(array('per_page' => 500),array("if-modified-since" => date('c',(time()-6000)) ));
    	    
    	    $deletedAccounts=$findDeletedAccounts->getData();
    	    
    	    
    	    $crondata=TableRegistry::get('Crondatatest');
    	    $newCronEntry=$crondata->newEntity();
    	    $newCronEntry->raw_response = print_r($findDeletedAccounts,1);
    	    $newCronEntry->time=time();
    	    $newCronEntry->type='Deleted Accounts';
    	    $crondata->save($newCronEntry);
    	    
    	    foreach($deletedAccounts as $num => $accountData){
    	        
    	        $lookupLocal=$this->Customers->find('all',['conditions'=>['zoho_account_id' => $accountData->getEntityId()]])->toArray();
    	        
    	        echo "<pre>";
    	        print_r($lookupLocal);
    	        echo "</pre>";
    	        echo "<hr>";
    	        
    	        foreach($lookupLocal as $localCustomer){
    	            if($localCustomer['status'] != 'Deleted'){
        	            $thisCustomer=$customerTable->get($localCustomer['id']);
        	            $thisCustomer->status = 'Deleted';
        	            $thisCustomer->zoho_last_updated = strtotime($accountData->getDeletedTime());
        	            if($customerTable->save($thisCustomer)){
            	            echo "Marked customer <b>".$localCustomer['company_name']."</b> as Deleted per ZOHO<br>";
        					$this->logactivity($_SERVER['REQUEST_URI'],"Marked customer \"".$localCustomer['company_name']."\" as Deleted per ZOHO",-1);
        	            }
    	            }
    	            
    	        }
    	        
    	    }
    	    
	    } catch(\zcrmsdk\crm\exception\ZCRMException $ex){
	        
	        switch($ex->getExceptionCode()){
	           case 'NOT MODIFIED':
	                //we did not find any changes... continue
	                //echo "No Contacts changes found<br>";
	                $ccrondata=TableRegistry::get('Crondatatest');
            	    $cnewCronEntry=$ccrondata->newEntity();
            	    $cnewCronEntry->raw_response = 'No Changes';
            	    $cnewCronEntry->time=time();
            	    $cnewCronEntry->type='Deleted Accounts';
            	    $ccrondata->save($cnewCronEntry);
	           break;
	           case 'NO CONTENT':
	               //no response received..PANIC
	           break;
	        }
	        
	    }  
	    
	}
	
	
	public function fullsync($type='Accounts'){
	    $this->autoRender=false;
	    $restClient = ZCRMRestClient::getInstance();//to get the rest client
        
        $bulkReadrecordIns = $restClient->getBulkReadInstance($type, null);
        
        $callBack = ZCRMBulkCallBack::GetInstance();// To get ZCRMBulkCallBack instance
        $callBack->setUrl("https://orders.hcinteriors.com/zoho/incomingfullsync");// To set callback URL.
        $callBack->setMethod("post");
        $bulkReadrecordIns->setCallBack($callBack);// To set ZCRMBulkCallBack instance
//         $bulkReadrecordIns->setFileType("ics");// Set the value for this key as "ics" to export all records in the Events module as an ICS file.
        
        $recordres = $bulkReadrecordIns->createBulkReadJob();
        echo "<pre>";
        echo "HTTP Status Code:" . $recordres->getHttpStatusCode()."\n"; // To get http response code
        echo "Status:" . $recordres->getStatus()."\n"; // To get response status
        echo "Message:" . $recordres->getMessage()."\n"; // To get response message
        echo "Code:" . $recordres->getCode()."\n"; // To get status code
        echo "Details:" . json_encode($recordres->getDetails())."\n";
        echo "Response Json".json_encode($recordres->getResponseJSON())."\n";
        
        $readIns = $recordres->getData();// To get the ZCRMBulkRead instance.
        
        echo ($readIns->getCreatedTime())."\n";
        echo ($readIns->getOperation())."\n";
        echo ($readIns->getState())."\n";
        echo ($readIns->getJobId())."\n";// To get the job_id of bulk read job.
        
        $created_by = $readIns->getCreatedBy();
        
        echo $created_by->getId()."\n";
        echo $created_by->getName()."\n";
        echo "</pre>";
        
        
        $jobDB=TableRegistry::get('Zohofullsyncjobs');
        $newJob=$jobDB->newEntity();
        $newJob->job_id = $readIns->getJobId();
        $newJob->job_type=$type;
        $newJob->status='new';
        $newJob->path=$_SERVER['DOCUMENT_ROOT'].'/fullsyncfiles';
        $newJob->filename=$readIns->getJobId().'.zip';
        $newJob->time_initiated=time();
        $newJob->time_completed=0;
        $newJob->time_downloaded=0;
        $newJob->time_parsed=0;
        
        $jobDB->save($newJob);
        
	}
	
	public function incomingfullsync(){
	    $this->autoRender=false;
	    
	    $this->logactivity($_SERVER['REQUEST_URI'],"DATA RECEIVED",-1,print_r($_REQUEST,1));
	    
	}
	
	public function getfullsyncstatus($job_id){
	    $this->autoRender=false;
	    echo "<pre>";
	    
	    $record= ZCRMRestClient::getInstance()->getBulkReadInstance(null,$job_id);
        $response = $record->getBulkReadJobDetails();
     
        $read = $response->getData();
        if($read->getState() == 'COMPLETED'){
            
            $lookupjob=$this->Zohofullsyncjobs->find('all',['condiitions'=>['job_id' => $job_id],'order'=>['id'=>'desc']])->limit(1)->toArray();
            foreach($lookupjob as $job){
                $syncdb=TableRegistry::get('Zohofullsyncjobs');
                $thisjob=$syncdb->get($job->id);
                $thisjob->status='COMPLETED';
                $thisjob->time_completed=time();
                $thisjob->time_downloaded=time();
                $syncdb->save($thisjob);
            }
            
            $download = $record->downloadBulkReadResult();
            $fp = fopen($_SERVER['DOCUMENT_ROOT'].'/fullsyncfiles/'.$download->getFileName(), "w");
            echo "HTTP Status Code:" . $response->getHttpStatusCode();
            echo "<br>";
            echo "File Name:" . $download->getFileName();
            $stream = $download->getFileContent();
            fputs($fp, $stream);
            fclose($fp);
        }
	    exit;
	}
	
	
	public function processbulkjobfromfile($job_id){
	    $this->autoRender=false;
	    
	    $customersTable=TableRegistry::get('Customers');
	    $contactsTable=TableRegistry::get('CustomerContacts');
	    $zohofullsyncprocesslogTable=TableRegistry::get('Zohofullsyncprocesslog');
	    
	    
	    $lookupjob=$this->Zohofullsyncjobs->find('all',['conditions'=>['job_id' => $job_id]])->toArray();
        foreach($lookupjob as $job){
            if($job->status=='COMPLETED'){
	            $jobtype=$job->job_type;
         
        	    $recordIns= ZCRMBulkRead::getInstance($jobtype);// To get the ZCRMBulkRead instance of the module
                $fileResponse =  $recordIns->GetRecords($_SERVER['DOCUMENT_ROOT'].'/fullsyncfiles', $job_id.'.zip');
        
                if($jobtype=='Accounts'){
                    
                    $i=1;
                    
                    while($fileResponse->hasNext()){
                        $record = $fileResponse->next();
                        
                        $zohoAccountName='';
						//$zohoCustomerSurcharge=0.00;
						$zohoSurchargePercent=0.00;
						$zohoSurchargeDollar=0.00;
						$zohoBillingAddress='';
						$zohoBillingCity='';
						$zohoBillingState='';
						$zohoBillingZipcode='';
						$zohoBillingCountry='';
						$zohoPhone='';
						$zohoFax='';
						$zohoWebsite='';
						$zohoShippingAddress='';
						$zohoShippingCity='';
						$zohoShippingState='';
						$zohoShippingZipcode='';
						$zohoShippingCountry='';
						$zohoSalesTax='';
						$zohoTierCCs='';
						$zohoTierBSs='';
						$zohoTierSWTs='';
						$zohoTierHWTs='';
						$zohoTierTrack='';
						$zohoTierInstall='';
						$zohoTierFabric='';

                        /** PPSASCRUM-3 start **/
                        $zohoOnCreditHold='';
                        $zohoDepositThreshold='';
                        $zohoDepositPerc='';
                        $zohoPaymentTerms='';
                         /** PPSASCRUM-3 end **/
                        
                        $zohoOnhold='';
                        
                        
                        //loop through the values and update the vars
                        foreach($record->getData() as $key => $value){
                        
                            switch($key){
                                
                                case "Account_Name":
									$zohoAccountName=$value;
								break;
								case 'Surcharge %':
								case 'ADD':
									$zohoSurchargePercent=floatval(str_replace('%','',trim($value)));
								break;
								case 'Surcharge $':
								case 'Surcharge':
									$zohoSurchargeDollar=floatval(str_replace('%','',str_replace('$','',trim($value))));
								break;
								case 'ONHOLD':
								    $zohoOnhold=$value;
								break;
								case "Billing_Street":
									$zohoBillingAddress=trim($value);
								break;
								case "Billing_City":
									$zohoBillingCity=trim($value);
								break;
								case "Billing_State":
									$zohoBillingState=trim($value);
								break;
								case "Billing_Code":
									$zohoBillingZipcode=trim($value);
								break;
								case "Billing_Country":
									$zohoBillingCountry=trim($value);
								break;
								case "Shipping_Street":
									$zohoShippingAddress=trim($value);
								break;
								case "Shipping_City":
									$zohoShippingCity=trim($value);
								break;
								case "Shipping_State":
									$zohoShippingState=trim($value);
								break;
								case "Shipping_Code":
									$zohoShippingZipcode=trim($value);
								break;
								case "Shipping_Country":
									$zohoShippingCountry=trim($value);
								break;
								case "Phone":
									$zohoPhone=trim($value);
								break;
								case "Website":
									$zohoWebsite=trim($value);
								break;
								case "Fax":
									$zohoFax=trim($value);
								break;
								case "Tier_BSs":
									$valexp=explode(" (",trim($value));
									$zohoTierBSs=$valexp[0];
								break;
								case "Tier_CCs":
									$valexp=explode(" (",trim($value));
									$zohoTierCCs=$valexp[0];
								break;
								case "Tier_SWT":
									$valexp=explode(" (",trim($value));
									$zohoTierSWTs=$valexp[0];
								break;
								case "Tier_Track":
									$valexp=explode(" (",trim($value));
									$zohoTierTrack=$valexp[0];
								break;
								case "Tier_HWTs":
									$valexp=explode(" (",trim($value));
									$zohoTierHWTs=$valexp[0];
								break;
								case "Tier_Install":
									$valexp=explode(" (",trim($value));
									$zohoTierInstall=$valexp[0];
								break;
								case "Tier_Fabric":
									$valexp=explode(" (",trim($value));
									$zohoTierFabric=$valexp[0];
								break;
                                /**PPSASCRUM-3 start**/
                                case "CREDIT_HOLD":
                                   if($value=="YES" || $value == 1)  $zohoOnCreditHold='1' ; 
                                   else 
                                       $zohoOnCreditHold='0';
                                break;
                                case "Deposit_Required1":
                                    $zohoDepositPerc=preg_replace('/[^0-9]/', '', $value);
                                break;
                                case "Deposit_Required_Threshold":
                                    $zohoDepositThreshold=preg_replace('/[^0-9]/', '', $value);
                                break;
                                case "Payment_Terms":
                                    $zohoPaymentTerms=$value;
                                break;
                                /**PPSASCRUM-3 end**/
                                
                            }
                            
                        }
                        
                        
                        //find the customer entry in db
                        $lookupCustomers=$this->Customers->find('all',['conditions'=>['zoho_account_id' => $record->getEntityId()]])->toArray();
                        if(count($lookupCustomers) == 0){
                            //this customer does not exist... create it!!
                            $newCustomer=$customersTable->newEntity();
                            $newCustomer->zoho_account_id = $record->getEntityId();
                            $newCustomer->company_name = $zohoAccountName;
                            $newCustomer->zoho_last_updated = strtotime($record->getModifiedTime());
                            $newCustomer->billing_address = $zohoBillingAddress;
                            $newCustomer->billing_address_city = $zohoBillingCity;
                            $newCustomer->billing_address_state = $zohoBillingState;
                            $newCustomer->billing_address_zipcode = $zohoBillingZipcode;
                            $newCustomer->billing_address_country = $zohoBillingCountry;
                            $newCustomer->phone = $zohoPhone;
                            $newCustomer->fax = $zohoFax;
                            $newCustomer->website = $zohoWebsite;
                            $newCustomer->shipping_address = $zohoShippingAddress;
                            $newCustomer->shipping_address_city = $zohoShippingCity;
                            $newCustomer->shipping_address_state = $zohoShippingState;
                            $newCustomer->shipping_address_zipcode = $zohoShippingZipcode;
                            $newCustomer->shipping_address_country = $zohoShippingCountry;
                            //$newCustomer->sales_tax = $zohoSalesTax;
                            $newCustomer->tier_cc = $zohoTierCCs;
                            $newCustomer->tier_bs = $zohoTierBSs;
                            $newCustomer->tier_swt = $zohoTierSWTs;
                            $newCustomer->tier_track = $zohoTierTrack;
                            $newCustomer->tier_hwt = $zohoTierHWTs;
                            $newCustomer->tier_install = $zohoTierInstall;
                            $newCustomer->tier_fabric = $zohoTierFabric;
                            $newCustomer->surcharge_percent = $zohoSurchargePercent;
                            $newCustomer->surcharge_dollars = $zohoSurchargeDollar;

                            /** PPSASCRUM-3 start **/
                            $newCustomer->on_credit_hold = (int)$zohoOnCreditHold;
                            $newCustomer->deposit_threshold = $zohoDepositThreshold;
                            $newCustomer->deposit_perc = $zohoDepositPerc;
                            $newCustomer->payment_terms = $zohoPaymentTerms;

                            /** PPSASCRUM-3 end **/
                            
                            if(intval($zohoOnhold) == 1){
                                $newCustomer->status='OnHold';
                            }else{
                                $newCustomer->status='Active';
                            }
                            
                            $customersTable->save($newCustomer);
                            
                            $newrowlog=$zohofullsyncprocesslogTable->newEntity();
                            $newrowlog->job_id=$job_id;
                            $newrowlog->row_number = $i;
                            $newrowlog->type='insert';
                            $newrowlog->completed=time();
                            $zohofullsyncprocesslogTable->save($newrowlog);
                            
                        }else{
                            foreach($lookupCustomers as $lookupCustomer){
                                    
                                    //update the db from the new values
                                    $thisCustomer=$customersTable->get($lookupCustomer['id']);
                                    
                                    if($zohoAccountName != $lookupCustomer['company_name']){
                                        $thisCustomer->company_name = $zohoAccountName;
                                    }
                                    
                                    $thisCustomer->zoho_last_updated = strtotime($record->getModifiedTime());
                                    
                                    if(intval($zohoOnhold) == 1 && $lookupCustomer['status'] != 'OnHold'){
                                        $thisCustomer->status='OnHold';
                                    }else{
                                        $thisCustomer->status='Active';
                                    }
                                    
                                    if($zohoBillingAddress != $lookupCustomer['billing_address']){
                                        $thisCustomer->billing_address = $zohoBillingAddress;
                                    }
                                    
                                    if($zohoBillingCity != $lookupCustomer['billing_address_city']){
                                        $thisCustomer->billing_address_city = $zohoBillingCity;
                                    }
                                    
                                    if($zohoBillingState != $lookupCustomer['billing_address_state']){
                                        $thisCustomer->billing_address_state = $zohoBillingState;
                                    }
                                    
                                    if($zohoBillingZipcode != $lookupCustomer['billing_address_zipcode']){
                                        $thisCustomer->billing_address_zipcode = $zohoBillingZipcode;
                                    }
                                    
                                    if($zohoBillingCountry != $lookupCustomer['billing_address_country']){
                                        $thisCustomer->billing_address_country = $zohoBillingCountry;
                                    }
                                    
                                    if($zohoPhone != $lookupCustomer['phone']){
                                        $thisCustomer->phone = $zohoPhone;
                                    }
                                    
                                    if($zohoFax != $lookupCustomer['fax']){
                                        $thisCustomer->fax = $zohoFax;
                                    }
                                    
                                    if($zohoWebsite != $lookupCustomer['website']){
                                        $thisCustomer->website = $zohoWebsite;
                                    }
                                    
                                    if($zohoShippingAddress != $lookupCustomer['shipping_address']){
                                        $thisCustomer->shipping_address = $zohoShippingAddress;
                                    }
                                    
                                    if($zohoShippingCity != $lookupCustomer['shipping_address_city']){
                                        $thisCustomer->shipping_address_city = $zohoShippingCity;
                                    }
                                    
                                    if($zohoShippingState != $lookupCustomer['shipping_address_state']){
                                        $thisCustomer->shipping_address_state = $zohoShippingState;
                                    }
                                    
                                    if($zohoShippingZipcode != $lookupCustomer['shipping_address_zipcode']){
                                        $thisCustomer->shipping_address_zipcode = $zohoShippingZipcode;
                                    }
                                    
                                    if($zohoShippingCountry != $lookupCustomer['shipping_address_country']){
                                        $thisCustomer->shipping_address_country = $zohoShippingCountry;
                                    }
                                    
                                    //$thisCustomer->sales_tax = $zohoSalesTax;
                                    
                                    if($zohoTierCCs != $lookupCustomer['tier_cc']){
                                        $thisCustomer->tier_cc = $zohoTierCCs;
                                    }
                                    
                                    if($zohoTierBSs != $lookupCustomer['tier_bs']){
                                        $thisCustomer->tier_bs = $zohoTierBSs;
                                    }
                                    
                                    if($zohoTierSWTs != $lookupCustomer['tier_swt']){
                                        $thisCustomer->tier_swt = $zohoTierSWTs;
                                    }
                                    
                                    if($zohoTierTrack != $lookupCustomer['tier_track']){
                                        $thisCustomer->tier_track = $zohoTierTrack;
                                    }
                                    
                                    if($zohoTierHWTs != $lookupCustomer['tier_hwt']){
                                        $thisCustomer->tier_hwt = $zohoTierHWTs;
                                    }
                                    
                                    if($zohoTierInstall != $lookupCustomer['tier_install']){
                                        $thisCustomer->tier_install = $zohoTierInstall;
                                    }
                                    
                                    if($zohoTierFabric != $lookupCustomer['tier_fabric']){
                                        $thisCustomer->tier_fabric = $zohoTierFabric;
                                    }
                                    
                                    if($zohoSurchargePercent != $lookupCustomer['surcharge_percent']){
                                        $thisCustomer->surcharge_percent = $zohoSurchargePercent;
                                    }
                                    
                                    if($zohoSurchargeDollar != $lookupCustomer['surcharge_dollars']){
                                        $thisCustomer->surcharge_dollars = $zohoSurchargeDollar;
                                    }

                                    /**PPSASCRUM-3 start **/
                                     if($zohoOnCreditHold != $lookupCustomer['on_credit_hold']){
                                        $thisCustomer->on_credit_hold = (int)$zohoOnCreditHold;
                                    }
                                    
                                    if($zohoDepositThreshold != $lookupCustomer['deposit_threshold']){
                                        $thisCustomer->deposit_threshold = $zohoDepositThreshold;
                                    }
                                    
                                    if($zohoDepositPerc != $lookupCustomer['deposit_perc']){
                                        $thisCustomer->deposit_perc = $zohoDepositPerc;
                                    }
                                    
                                    if($zohoPaymentTerms != $lookupCustomer['payment_terms']){
                                        $thisCustomer->payment_terms = $zohoPaymentTerms;
                                    }
                                     /**PPSASCRUM-3 end **/
                                    
                                    $customersTable->save($thisCustomer);
                                    
                                    $newrowlog=$zohofullsyncprocesslogTable->newEntity();
                                    $newrowlog->job_id=$job_id;
                                    $newrowlog->row_number = $i;
                                    $newrowlog->type='update';
                                    $newrowlog->completed=time();
                                    $zohofullsyncprocesslogTable->save($newrowlog);
                                
                            }
                        }
                        
                        $i++;
                    }
                    
                    
                    /*loop through the local db and compare with the file records
                    $localActiveAccounts=$this->Customers->find('all',['conditions'=>['status'=>'Active']])->toArray();
                    foreach($localActiveAccounts as $localAccount){
                        $found=0;
                        $secrecordIns= ZCRMBulkRead::getInstance($jobtype);// To get the ZCRMBulkRead instance of the module
                        $secfileResponse =  $recordIns->GetRecords($_SERVER['DOCUMENT_ROOT'].'/fullsyncfiles', $job_id.'.zip');
                        while($secfileResponse->hasNext()){
                            $thisrecord = $secfileResponse->next();
                            //echo $thisrecord->getEntityId()."  < ==== > ".$localAccount['zoho_account_id']."<br>";
                            if($thisrecord->getEntityId() == $localAccount['zoho_account_id']){
                                $found++;
                            }
                        }
                        
                        if($found == 0){
                            echo "DELETE local record <B>".$localAccount['id']."</B><br>";
                        }
                    }
                    */
                    
                }elseif($jobtype=='Contacts'){
                
                    $i=1;
                    
                    while($fileResponse->hasNext()){
                        $record = $fileResponse->next();
                        
                        $zohoContactId=$record->getEntityId();
                        $zohoCreated=strtotime($record->getCreatedTime());
                        $zohoLastUpdated=strtotime($record->getModifiedTime());
                        $zohoAccountId='';
                        $contactFirstName='';
                        $contactLastName='';
                        $contactEmail='';
                        $contactSecondaryEmail='';
                        $contactPhone='';
                        $contactMobile='';
                        $contactFax='';
                        $contactIsPrimary='0';
                        $zohoDescription='';
                        $zohoOptOut='';
                        
                        //echo "<pre>"; print_r($record->getData()); echo "</pre>";
                        //echo $record->getRecordRowNumber()."\n";
                        
                        foreach($record->getData() as $key => $value){
                            
                            switch($key){
                                case "First_Name":
                                    $contactFirstName=$value;
                                break;
                                case "Last_Name":
                                    $contactLastName=$value;
                                break;
                                case "Account_Name":
                                    $zohoAccountId=$value;
                                break;
                                case "Description":
                                    $zohoDescription=$value;
                                break;
                                case "Email_Opt_Out":
                                    if($value==false){
                                        $zohoOptOut=0;
                                    }else{
                                        $zohoOptOut=1;
                                    }
                                break;
                                case "Email":
                                    $contactEmail=$value;
                                break;
                                case "Phone":
                                    $contactPhone=$value;
                                break;
                                case "Mobile":
                                    $contactMobile=$value;
                                break;
                                case "Fax":
                                    $contactFax=$value;
                                break;
                                case "Secondary_Email":
                                    $contactSecondaryEmail=$value;
                                break;
                            }

                        }
                        
                        
                        //let's find this record and start comparing!
                        $contactRow=$this->CustomerContacts->find('all',['conditions'=>['zoho_contact_id' => $zohoContactId]])->toArray();
                        if(count($contactRow) == 0){
                            //does not exist locally... create it!
                            
                            //lookup account by zoho id
                            $accountRow=$this->Customers->find('all',['conditions'=>['zoho_account_id' => $zohoAccountId]])->toArray();
                            if(count($accountRow) == 1){
                                
                                foreach($accountRow as $account){
                                    
                                    $newContact=$contactsTable->newEntity();
                                    $newContact->customer_id=$account['id'];
                                    $newContact->zoho_contact_id=$zohoContactId;
                                    $newContact->first_name=$contactFirstName;
                                    $newContact->last_name=$contactLastName;
                                    $newContact->email_address=$contactEmail;
                                    $newContact->secondary_email=$contactSecondaryEmail;
                                    $newContact->phone=$contactPhone;
                                    $newContact->mobile_phone=$contactMobile;
                                    $newContact->fax=$contactFax;
                                    $newContact->is_primary_contact=0;
                                    $newContact->local_last_updated=time();
                                    $newContact->local_created=time();
                                    $newContact->zoho_last_updated=$zohoLastUpdated;
                                    $newContact->zoho_created=$zohoCreated;
                                    $newContact->description=$zohoDescription;
                                    $newContact->email_optout=$zohoOptOut;
                                    $newContact->status='Active';
                                    
                                    $contactsTable->save($newContact);
                                    
                                    $newrowlog=$zohofullsyncprocesslogTable->newEntity();
                                    $newrowlog->job_id=$job_id;
                                    $newrowlog->row_number = $i;
                                    $newrowlog->type='insert';
                                    $newrowlog->completed=time();
                                    $zohofullsyncprocesslogTable->save($newrowlog);
                                    
                                }
                                
                            }
                            
                        }else{
                            //exists locally... make sure everything is up to date!
                            foreach($contactRow as $contact){
                                
                                $accountRow=$this->Customers->find('all',['conditions'=>['zoho_account_id' => $zohoAccountId]])->toArray();
                                if(count($accountRow) == 1){
                                
                                    foreach($accountRow as $account){
                                        $thisContact=$contactsTable->get($contact['id']);
                                        $thisContact->customer_id=$account['id'];
                                        $thisContact->zoho_contact_id=$zohoContactId;
                                        $thisContact->first_name=$contactFirstName;
                                        $thisContact->last_name=$contactLastName;
                                        $thisContact->email_address=$contactEmail;
                                        $thisContact->secondary_email=$contactSecondaryEmail;
                                        $thisContact->phone=$contactPhone;
                                        $thisContact->mobile_phone=$contactMobile;
                                        $thisContact->fax=$contactFax;
                                        $thisContact->is_primary_contact=0;
                                        $thisContact->local_last_updated=time();
                                        $thisContact->zoho_last_updated=$zohoLastUpdated;
                                        $thisContact->zoho_created=$zohoCreated;
                                        $thisContact->description=$zohoDescription;
                                        $thisContact->email_optout=$zohoOptOut;
                                        $thisContact->status='Active';
                                        
                                        $contactsTable->save($thisContact);
                                        
                                        $newrowlog=$zohofullsyncprocesslogTable->newEntity();
                                        $newrowlog->job_id=$job_id;
                                        $newrowlog->row_number = $i;
                                        $newrowlog->type='update';
                                        $newrowlog->completed=time();
                                        $zohofullsyncprocesslogTable->save($newrowlog);
                                    }
                                }
                                
                            }
                        }
                        
                        
                        //$fileResponse->close();
                        
                        $i++;
                    }
                
                    
                }
	    
            }
        }
        
	    exit;
	}
	
	
	
	public function testnnn(){
	    $this->autoRender=false;
	    $timeoffset=5000;
	    
	    try{
	        
	        $moduleIns = ZCRMRestClient::getInstance()->getModuleInstance("Accounts");
	        $response = $moduleIns->getRecords(array('per_page' => 500),array("if-modified-since" => date('c',(time()-$timeoffset) )));
	    
	        $records=$response->getData();
	        
    	    $crondata=TableRegistry::get('Crondatatest');
    	    $newCronEntry=$crondata->newEntity();
    	    //$newCronEntry->raw_response = print_r($response,1);
    	    
    	    $newCronEntry->raw_response='intentionally-blank';
    	    $newCronEntry->time=time();
    	    $newCronEntry->type='Accounts';
    	    $crondata->save($newCronEntry);
    	    
    	    foreach($records as $record){
    	        
    	        echo "<pre>";
    	        print_r($record);
    	        echo "</pre><hr>";
    	             
    	    }
    	    
	    
	    } catch(\zcrmsdk\crm\exception\ZCRMException $ex){
	        
	        switch($ex->getExceptionCode()){
	           case 'NOT MODIFIED':
	                
            	    
            	    
	           break;
	           case 'NO CONTENT':
	               //no response received..PANIC
	               echo "Request failed";
	           break;
	        }
	        
	    }
	}
	
	
	/**PPSASCRUM-3 Start **/
	public function updateNewFieldsFromZoho() {
        $this->autoRender = false;
        $customerTable = TableRegistry::get('Customers');
        $moduleIns = ZCRMRestClient::getInstance()->getModuleInstance("Accounts");
        $response = $moduleIns->getRecords(array("page"=>6,"per_page"=>200));
        $records = $response->getData();
        $size = 0;
        foreach ($records as $record) {
            $size ++;
            $zohoAccountID = $record->getEntityId();
            $zohoLastUpdatedTimestamp = strtotime($record->getModifiedTime());
            $zohoDepositePerc = '';
            $zohoDepoistThreshold ='';
            $zohoOnCreditHold = '0';
            $zohoPaymentTerms = '';
            if ($record->getFieldValue('CREDIT_HOLD') == 'NO' || $record->getFieldValue('CREDIT_HOLD') == 0) {
                $zohoOnCreditHold = 0;
            } else {
                $zohoOnCreditHold = 1;
            }
            if ($record->getFieldValue('Deposit_Required1')) {
                $zohoDepositePerc = preg_replace('/[^0-9]/', '', $record->getFieldValue('Deposit_Required1'));
            }
            if ($record->getFieldValue('Deposit_Required_Threshold')) {
                $zohoDepoistThreshold = preg_replace('/[^0-9]/', '', $record->getFieldValue('Deposit_Required_Threshold'));
            }
            $zohoPaymentTerms = $record->getFieldValue('Payment_Terms');
            $customerLookup = $this->Customers->find('all', ['conditions' => ['zoho_account_id' => $zohoAccountID]])->toArray();
            if (count($customerLookup) > 0) {
                foreach ($customerLookup as $customerRow) {
                    $changeCount = 0;
                     echo "check changes for customer <b> " . $customerRow['company_name'] ." ===> ".$zohoDepoistThreshold ." ==in zoho => ".$record->getFieldValue('Deposit_Required_Threshold')." ===> ".$zohoAccountID." ===> ".$zohoDepositePerc." ==in zoho=> ".$record->getFieldValue('Deposit_Required1')."</b> from ZOHO<br>";
                    $thisCustomer = $customerTable->get($customerRow['id']);
                    if ($customerRow['on_credit_hold'] != $zohoOnCreditHold) {
                        $thisCustomer->on_credit_hold = (int)$zohoOnCreditHold;
                        $changeCount++;
                    }
                if ($customerRow['deposit_threshold'] != $zohoDepoistThreshold) {
                    $thisCustomer->deposit_threshold = $zohoDepoistThreshold;
                        $changeCount++;
                    }
                    if ($customerRow['deposit_perc'] != $zohoDepositePerc) {
                        $thisCustomer->deposit_perc = $zohoDepositePerc;
                        $changeCount++;
                    }
                    if ($customerRow['payment_terms'] != $zohoPaymentTerms) {
                        $thisCustomer->payment_terms = $zohoPaymentTerms;
                        $changeCount++;
                    }
                    echo $thisCustomer->deposit_threshold. "<br/>";
                    echo $thisCustomer->deposit_perc. " ==in zoho=> ".$record->getFieldValue('Deposit_Required1')."<br/>";
                    if ($changeCount > 0) {
                        $thisCustomer->local_last_updated = time();
                        $thisCustomer->zoho_last_updated = $zohoLastUpdatedTimestamp;
                        
                        if ($customerTable->save($thisCustomer)) {
                            echo "Updated changes for customer <b> " . $customerRow['company_name'] ." ===> ".$zohoDepoistThreshold ." ===> ".$record->getFieldValue('Deposit_Required_Threshold')." ===> ".$zohoAccountID." ===> ".$zohoDepositePerc."</b> from ZOHO<br>";
                             $this->logActivity($_SERVER['REQUEST_URI'],'Updated changes for customer from ZOHO"'.$customerRow['company_name'].'"');
                        }
                    }
                }
            }
            
        }
        echo $size;
        exit;
    }
    
    
	/** PPSASCRUM-3 END **/
	

}