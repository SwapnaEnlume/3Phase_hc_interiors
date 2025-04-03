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


use zcrmsdk\crm\setup\restclient\ZCRMRestClient;
use zcrmsdk\oauth\ZohoOAuth;
use zcrmsdk\crm\crud\ZCRMJunctionRecord;
use zcrmsdk\crm\crud\ZCRMNote;
use zcrmsdk\crm\crud\ZCRMRecord;
use zcrmsdk\crm\setup\users\ZCRMUser;
/**
 * Static content controller
 *
 * This controller will render views from Template/Pages/
 *
 * @link http://book.cakephp.org/3.0/en/controllers/pages-controller.html
 */
class CustomersController extends AppController
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
		$this->loadModel('Settings');
		$this->loadModel('Projects');
		
		$zcrmloads=array('add','addcontact','edit','editcontact','delete','deletecontact');
		
		if(preg_match("#orders.hcinteriors.com#i",$_SERVER['HTTP_HOST'])){
		    if(in_array($this->request->action,$zcrmloads)){
		        ZCRMRestClient::initialize(array("client_id" => $this->getSettingValue('zoho_v2_client_id'), "client_secret" => $this->getSettingValue('zoho_v2_client_secret'), "redirect_uri" => $this->getSettingValue('zoho_v2_redirect_uri'), "currentUserEmail" => "luis@hcinteriors.com", "token_persistence_path" => $_SERVER['DOCUMENT_ROOT']."/config" ));
		    }
		}
		
	}
	
		
	public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
		if($this->request->action=='getcustomers' || $this->request->action=='getprojectslist'){
			$this->eventManager()->off($this->Csrf);
			$this->eventManager()->off($this->Security);
		}
    }
	
	public function index(){
		
	}
	
	public function getcustomers(){
		$customers=array();
		$overallTotalRows=$this->Customers->find()->count();
		$conditions=array();
		
		if(isset($this->request->data['search']['value']) && strlen(trim($this->request->data['search']['value'])) >0){
			//this is a search
			$conditions['OR']=array();
			$conditions['OR'] += array('company_name LIKE'=>'%'.trim($this->request->data['search']['value']).'%');
			$conditions['OR'] += array('zoho_account_id LIKE'=>'%'.trim($this->request->data['search']['value']).'%');
			$conditions['OR'] += array('billing_address LIKE'=>'%'.trim($this->request->data['search']['value']).'%');
			$conditions['OR'] += array('CONCAT(billing_address_city,\', \',billing_address_state) LIKE'=>'%'.trim($this->request->data['search']['value']).'%');
			$conditions['OR'] += array('REPLACE(phone,\'-\',\'\') LIKE' => '%'.trim(str_replace("-","",$this->request->data['search']['value'])).'%');
		}
		
		//search contacts
		$contactsCustomersFound=array();
		$contactSearch=$this->CustomerContacts->find('all',['conditions'=>['status'=>'Active','CONCAT(first_name,\' \',last_name) LIKE' => '%'.trim($this->request->data['search']['value']).'%']])->toArray();
		foreach($contactSearch as $contact){
			$contactsCustomersFound[]=$contact['customer_id'];
		}
		
		if(count($contactsCustomersFound) >1){
			if(!isset($conditions['OR'])){
				$conditions['OR']=array();
			}
			$conditions['OR'] += array('id IN'=>$contactsCustomersFound);
		}elseif(count($contactsCustomersFound)==1){
			if(!isset($conditions['OR'])){
				$conditions['OR']=array();
			}
			$conditions['OR'] += array('id'=>$contactsCustomersFound[0]);
		}
		
		/*
		$this->autoRender=false;
		print_r($conditions);exit;
		*/
		
		$conditions += array('status !=' => 'Deleted');
		
		$totalFilteredRows=$this->Customers->find('all',['conditions'=>$conditions,'order'=>['company_name'=>'asc']])->count();
		$lookupCustomers=$this->Customers->find('all',['conditions'=>$conditions,'order'=>['company_name'=>'asc']])->offset($this->request->data['start'])->limit($this->request->data['length'])->hydrate(false)->toArray();
		foreach($lookupCustomers as $customer){
			
			if(trim($customer['billing_address']) == ""){
				$billingAddress='';
			}else{
				$billingAddress=$customer['billing_address']."<br>".$customer['billing_address_city'].", ".$customer['billing_address_state']." ".$customer['billing_address_zipcode'];
			}
			
			if(trim($customer['shipping_address']) == ""){
				$shippingAddress='';
			}else{
			/**PPSASCRUM-7 Start **/	
			$shippingAddress=$customer['shipping_address']."<br>".$customer['shipping_address_city'].", ".$customer['shipping_address_state']." ".$customer['shipping_address_zipcode'];
		/**PPSASCRUM-7 end **/
			}
			

			$surchargeAmount='';

			
			
			if($customer['source']=="zoho"){
				$source='<img src="/img/zohoicon.png" />';
			}elseif($customer['source']=="local"){
				$source='<img src="/img/hciicon.png" />';
			}
			
			//lookup all contacts for this customer
			$contacts=$this->CustomerContacts->find('all',['conditions'=>['status'=>'Active','customer_id'=>$customer['id']],'order'=>['first_name'=>'asc']])->toArray();
			$contactsList='';
			if(count($contacts)>0){
				$contactsList .= '<div class="contactsblock">';
				foreach($contacts as $contact){
					$contactsList .= '<div class="contactwrap"><div class="contactname">'.$contact['first_name'].' '.$contact['last_name'].'<div class="contactpopout"><div class="contacteditlinks"><a href="/customers/editcontact/'.$contact['id'].'/"><img src="/img/edit.png" /></a><a href="/customers/deletecontact/'.$contact['id'].'/"><img src="/img/delete.png" /></a></div><div class="nameentry">'.$contact['first_name'].' '.$contact['last_name'].'</div><div class="emailentry">';
					if(strlen(trim($contact['email_address'])) == 0){
						$contactsList .= '<em>N/A</em>';
					}else{
						$contactsList .= '<a href="mailto:'.$contact['email_address'].'">'.$contact['email_address'].'</a>';
					}
					$contactsList .= '</div><div class="phoneentry">';
					if(strlen(trim($contact['phone'])) == 0){
						$contactsList .= '<em>N/A</em>';
					}else{
						$contactsList .= $contact['phone'];
					}
					$contactsList .= '</div></div></div></div>';
				}
				$contactsList .= '</div>';
			}
			
			$contactsList .= '<div class="newcontact"><a href="/customers/addcontact/'.$customer['id'].'">+ Add Contact</a></div>';
			/**PPSASCRUM-3 start **/$onCreditHoldValue = ($customer['on_credit_hold']) ? '<div><span style="color: red;"> On Credit Hold</span></div> ' : '';

			$customers[]=array(
						'DT_RowId'=>'row_'.$customer['id'],
						'DT_RowClass'=>'rowtest',
						'0' => '<a href="/customers/edit/'.$customer['id'].'/"><img src="/img/edit.png" /></a> <a href="/customers/delete/'.$customer['id'].'/"><img src="/img/delete.png" /></a>',
                        '1' => '<div><b>'.$customer['company_name'].'<b>'.'</b></div>'.$onCreditHoldValue.'<b>'.$contactsList,
						'2' => $billingAddress,
						'3' => $customer['phone'],
						'4' => $surchargeAmount,
						'5' => $source,
						'6' => '<a href="https://crm.zoho.com/crm/EntityInfo.do?module=Accounts&id='.$customer['zoho_account_id'].'" target="_blank">'.$customer['zoho_account_id'].'</a>'
			);
		}
		
		
		$this->set('draw',$this->request->data['draw']);
		$this->set('recordsTotal',$overallTotalRows);
		$this->set('recordsFiltered',$totalFilteredRows);
		$this->set('data',$customers);
		$this->set('_serialize',['draw','recordsTotal','recordsFiltered','data']);
			
	}
	
	public function add($from=NULL)
    {
        $customer = $this->Customers->newEntity();
		$this->set('from',$from);
		
        if ($this->request->is('post')) {
			$this->autoRender=false;
			//check to make sure this company doesn't already exist!
			$companyCheck=$this->Customers->find('all',['conditions'=>['company_name'=>$this->request->data['company_name']]])->count();
			if($companyCheck > 0){
				$this->Flash->error('This company already exists in the system.');
				return $this->redirect('/customers/');
			}
			
            $newCustomer = $this->Customers->newEntity();
			$newCustomer->company_name=$this->request->data['company_name'];
			$newCustomer->phone=$this->request->data['phone_number'];
			$newCustomer->fax=$this->request->data['fax_number'];
			$newCustomer->website=$this->request->data['company_website'];
			
			$newCustomer->surcharge_percent = floatval($this->request->data['surcharge_percent']);
			$newCustomer->surcharge_dollars = floatval($this->request->data['surcharge_dollars']);
			$newCustomer->billing_address=$this->request->data['billing_address'];
			$newCustomer->billing_address_city=$this->request->data['billing_city'];
			$newCustomer->billing_address_state=$this->request->data['billing_state'];
			$newCustomer->billing_address_zipcode=$this->request->data['billing_zipcode'];
			$newCustomer->billing_address_country=$this->request->data['billing_country'];
			/**PPSASCRUM-7 Start **/
				$newCustomer->shipping_name=$this->request->data['shipping_name'];
			/**PPSASCRUM-7 End **/
			$newCustomer->shipping_address=$this->request->data['shipping_address'];
			$newCustomer->shipping_address_city=$this->request->data['shipping_city'];
			$newCustomer->shipping_address_state=$this->request->data['shipping_state'];
			$newCustomer->shipping_address_zipcode=$this->request->data['shipping_zipcode'];
			$newCustomer->shipping_address_country=$this->request->data['shipping_country'];
			$newCustomer->source='local';
			$newCustomer->local_created=time();
			$newCustomer->local_last_updated=time();
			$newCustomer->tier_bs=$this->request->data['tier_bs'];
			$newCustomer->tier_cc=$this->request->data['tier_cc'];
			$newCustomer->tier_swt=$this->request->data['tier_swt'];
			$newCustomer->tier_hwt=$this->request->data['tier_hwt'];
			$newCustomer->tier_install=$this->request->data['tier_install'];
			$newCustomer->tier_fabric=$this->request->data['tier_fabric'];
			$newCustomer->tier_track=$this->request->data['tier_track'];
			
			/** PPSASCRUM -3 Start **/
			$newCustomer->on_credit_hold=$this->request->data['on_credit_hold'];
			$newCustomer->deposit_threshold=$this->request->data['deposit_threshold'];
			$newCustomer->deposit_perc=$this->request->data['deposit_perc'];
			$newCustomer->payment_terms=$this->request->data['payment_terms'];
			/** PPSASCRUM -3 end **/
			
				/**PPSASCRUM-85 Start **/
				$newCustomer->customer_notes=$this->request->data['customer_notes'];
			$newCustomer->pricing_programs=$this->request->data['pricing_programs'];
			/** PPSASCRUM-85 End **/
			
	        if(preg_match("#orders.hcinteriors.com#i",$_SERVER['HTTP_HOST'])){
                //only sync to Zoho if we're on the Production site
                
    			$newZohoRecord = ZCRMRestClient::getInstance()->getRecordInstance("Accounts", NULL);
    			
        		$newZohoRecord->setFieldValue('Account_Name',$this->request->data['company_name']);
        		$newZohoRecord->setFieldValue('Website',$this->request->data['company_website']);
        		$newZohoRecord->setFieldValue('ADD',$this->request->data['surcharge_percent']);
        		$newZohoRecord->setFieldValue('Surcharge',$this->request->data['surcharge_dollars']);
        		$newZohoRecord->setFieldValue('Billing_Street',$this->request->data['billing_address']);
        		$newZohoRecord->setFieldValue('Billing_City',$this->request->data['billing_city']);
        		$newZohoRecord->setFieldValue('Billing_State',$this->request->data['billing_state']);
        		$newZohoRecord->setFieldValue('Billing_Code',$this->request->data['billing_zipcode']);
        		$newZohoRecord->setFieldValue('Billing_Country',$this->request->data['billing_country']);
        		$newZohoRecord->setFieldValue('Shipping_Street',$this->request->data['shipping_address']);
        		$newZohoRecord->setFieldValue('Shipping_City',$this->request->data['shipping_city']);
        		$newZohoRecord->setFieldValue('Shipping_State',$this->request->data['shipping_state']);
        		$newZohoRecord->setFieldValue('Shipping_Code',$this->request->data['shipping_zipcode']);
        		$newZohoRecord->setFieldValue('Shipping_Country',$this->request->data['shipping_country']);
        		
        		$newZohoRecord->setFieldValue('Phone',$this->request->data['phone_number']);
        		$newZohoRecord->setFieldValue('Fax',$this->request->data['fax_number']);
        		
        		
        		$newZohoRecord->setFieldValue('Tier_CCs',$this->request->data['tier_cc']);
                $newZohoRecord->setFieldValue('Tier_BSs',$this->request->data['tier_bs']);
                $newZohoRecord->setFieldValue('Tier_SWT',$this->request->data['tier_swt']);
                $newZohoRecord->setFieldValue('Tier_HWTs',$this->request->data['tier_hwt']);
                $newZohoRecord->setFieldValue('Tier_Track',$this->request->data['tier_track']);
                $newZohoRecord->setFieldValue('Tier_Install',$this->request->data['tier_install']);
                $newZohoRecord->setFieldValue('Tier_Fabric',$this->request->data['tier_fabric']);
        	    
        	    /** PPSASCRUM -3 Start **/
                $newZohoRecord->setFieldValue('CREDIT_HOLD',$this->request->data['on_credit_hold']);
                $newZohoRecord->setFieldValue('Deposit_Required_Threshold    ',$this->request->data['deposit_threshold']);
                $newZohoRecord->setFieldValue('Deposit_Required1',$this->request->data['deposit_perc']);
                $newZohoRecord->setFieldValue('Payment_Terms',$this->request->data['payment_terms']);
                /** PPSASCRUM -3 end **/
                
                /** PPSASCRUM -85 Start **/
                $newZohoRecord->setFieldValue('Customer_Notes',$this->request->data['customer_notes']);
                $newZohoRecord->setFieldValue('Pricing_Programs    ',$this->request->data['pricing_programs']);
              
                /** PPSASCRUM -85 end **/
                
        	    $responseIns = $newZohoRecord->create();
                
                if($responseIns->getCode() == 'SUCCESS'){
                    //created!
                    $recordData=$responseIns->getDetails();
                    
                    $newCustomer->zoho_account_id = $recordData['id'];
                    $newCustomer->zoho_created = strtotime($recordData['Created_Time']);
                    $newCustomer->zoho_last_updated = strtotime($recordData['Modified_Time']);
                    
                }else{
                    echo "HTTP Status Code:" . $responseIns->getHttpStatusCode(); // To get http response code
                    echo "<Br>";
                    echo "Status:" . $responseIns->getStatus(); // To get response status
                    echo "<Br>";
                    echo "Message:" . $responseIns->getMessage(); // To get response    message
                    echo "<Br>";
                    echo "Code:" . $responseIns->getCode(); // To get status code
                    echo "<Br>";
                    echo "Details:" . json_encode($responseIns->getDetails());
                    exit;
                }
                
	        }
	        
			
			
			
            if ($this->Customers->save($newCustomer)) {
				$this->logActivity($_SERVER['REQUEST_URI'],'Added customer "'.$this->request->data['company_name'].'"');
				
                $this->Flash->success("New Customer has been created and sync'd to ZOHO");
				
				if(isset($this->request->data['from'])){
                    if($this->request->data['from']=="from_add_request"){
					    return $this->redirect(['controller' => 'Requests','action' => 'add', $customer->id]);
				    }else{
					    return $this->redirect('/customers/');
				    }
				}else{
					return $this->redirect('/customers/');
				}
            }else{
                $this->Flash->error('Unable to add the customer.');
            }
            
        }
        $this->set('customer', $customer);
    }
	
	public function addcontact($customerID){
		$customer=$this->Customers->get($customerID)->toArray();
		$this->set('customer',$customer);
		
		
		if($this->request->data){
			$this->autoRender=false;
			//add to ZOHO and save locally
	  		
	  		$customerContactTable=TableRegistry::get('CustomerContacts');
			$newLocalContact=$customerContactTable->newEntity();
	  		
	  		$newLocalContact->first_name=$this->request->data['first_name'];
			$newLocalContact->last_name=$this->request->data['last_name'];
			$newLocalContact->email_address=$this->request->data['email_address'];
			$newLocalContact->secondary_email=$this->request->data['secondary_email_address'];
			$newLocalContact->phone=$this->request->data['primary_phone_number'];
			$newLocalContact->mobile_phone=$this->request->data['mobile_phone_number'];
			$newLocalContact->customer_id=$customer['id'];

			$newLocalContact->local_created=time();
			$newLocalContact->local_last_updated=time();
			$newLocalContact->status='Active';
	  		
	  		if(preg_match("#orders.hcinteriors.com#i",$_SERVER['HTTP_HOST'])){
                //only sync to Zoho if we're on the Production site
	  		    $newZohoRecord = ZCRMRestClient::getInstance()->getRecordInstance("Contacts", NULL);
			
        		$newZohoRecord->setFieldValue('Account_Name',$customer['zoho_account_id']);
        		$newZohoRecord->setFieldValue('First_Name',$this->request->data['first_name']);
        		$newZohoRecord->setFieldValue('Last_Name',$this->request->data['last_name']);
        		$newZohoRecord->setFieldValue('Email',$this->request->data['email_address']);
        		$newZohoRecord->setFieldValue('Secondary_Email',$this->request->data['secondary_email_address']);
        		$newZohoRecord->setFieldValue('Phone',$this->request->data['primary_phone_number']);
        		$newZohoRecord->setFieldValue('Mobile',$this->request->data['mobile_phone_number']);
        	    
        	    
        	    $responseIns = $newZohoRecord->create();
                
                
                if($responseIns->getCode() == 'SUCCESS'){
                    //created!
                    $recordData=$responseIns->getDetails();
                    
                    $newLocalContact->zoho_contact_id = $recordData['id'];
                    $newLocalContact->zoho_created = strtotime($recordData['Created_Time']);
                    $newLocalContact->zoho_last_updated = strtotime($recordData['Modified_Time']);
                    
                }else{
                    echo "HTTP Status Code:" . $responseIns->getHttpStatusCode(); // To get http response code
                    echo "<Br>";
                    echo "Status:" . $responseIns->getStatus(); // To get response status
                    echo "<Br>";
                    echo "Message:" . $responseIns->getMessage(); // To get response    message
                    echo "<Br>";
                    echo "Code:" . $responseIns->getCode(); // To get status code
                    echo "<Br>";
                    echo "Details:" . json_encode($responseIns->getDetails());
                    exit;
                }
                
	  		}
	  		
			
			
			if($customerContactTable->save($newLocalContact)){
				//log it
				$this->logActivity($_SERVER['REQUEST_URI'],"Added new contact \"".$this->request->data['first_name']." ".$this->request->data['last_name']."\" to customer \"".$customer['company_name']."\"");
				$this->Flash->success('Successfully added new Contact "'.$this->request->data['first_name']." ".$this->request->data['last_name'].'"');
				return $this->redirect('/customers/');
			}
			
		}else{
			
		}
	}
	
	public function edit($customerID){
		$customerData=$this->Customers->get($customerID)->toArray();

		
		if($this->request->data){
			$customerTable=TableRegistry::get('Customers');
			$thisCustomer=$customerTable->get($customerID);
			$thisCustomer->billing_address = $this->request->data['billing_address'];
			$thisCustomer->billing_address_city = $this->request->data['billing_city'];
			$thisCustomer->billing_address_state = $this->request->data['billing_state'];
			$thisCustomer->billing_address_zipcode = $this->request->data['billing_zipcode'];
			$thisCustomer->billing_address_country = $this->request->data['billing_country'];
			/**PPSASCRUM-7 Start **/	
			$thisCustomer->shipping_name = $this->request->data['shipping_name'];
			/**PPSASCRUM-7 end **/
			$thisCustomer->shipping_address = $this->request->data['shipping_address'];
			$thisCustomer->shipping_address_city = $this->request->data['shipping_city'];
			$thisCustomer->shipping_address_state = $this->request->data['shipping_state'];
			$thisCustomer->shipping_address_zipcode = $this->request->data['shipping_zipcode'];
			$thisCustomer->shipping_address_country = $this->request->data['shipping_country'];
			$thisCustomer->company_name=$this->request->data['company_name'];
			$thisCustomer->phone=$this->request->data['phone_number'];
			$thisCustomer->fax=$this->request->data['fax_number'];
			$thisCustomer->website=$this->request->data['company_website'];
			
			$thisCustomer->surcharge_percent = floatval($this->request->data['surcharge_percent']);
			$thisCustomer->surcharge_dollars = floatval($this->request->data['surcharge_dollars']);
			$thisCustomer->tier_bs=$this->request->data['tier_bs'];
			$thisCustomer->tier_cc=$this->request->data['tier_cc'];
			$thisCustomer->tier_swt=$this->request->data['tier_swt'];
			$thisCustomer->tier_hwt=$this->request->data['tier_hwt'];
			$thisCustomer->tier_install=$this->request->data['tier_install'];
			$thisCustomer->tier_fabric=$this->request->data['tier_fabric'];
			$thisCustomer->tier_track=$this->request->data['tier_track'];
			
				/** PPSASCRUM -3 Start **/
			$thisCustomer->on_credit_hold=$this->request->data['on_credit_hold'];
            $thisCustomer->deposit_threshold=$this->request->data['deposit_threshold'];
            $thisCustomer->deposit_perc=$this->request->data['deposit_perc'];
            $thisCustomer->payment_terms=$this->request->data['payment_terms'];
			/** PPSASCRUM -3 end **/
			
				/** PPSASCRUM -85 Start **/
			$thisCustomer->customer_notes=$this->request->data['customer_notes'];
            $thisCustomer->pricing_programs=$this->request->data['pricing_programs'];
           
			/** PPSASCRUM -85 end **/

			$thisCustomer->local_last_updated=time();
			
			if(preg_match("#orders.hcinteriors.com#i",$_SERVER['HTTP_HOST'])){
                //only sync to Zoho if we're on the Production site
    			$record = ZCRMRestClient::getInstance()->getRecordInstance("Accounts", $customerData['zoho_account_id']);
    			
    			$record->setFieldValue('Account_Name',$this->request->data['company_name']);
        		$record->setFieldValue('Website',$this->request->data['company_website']);
        		$record->setFieldValue('ADD',$this->request->data['surcharge_percent']);
        		$record->setFieldValue('Surcharge',$this->request->data['surcharge_dollars']);
        		$record->setFieldValue('Billing_Street',$this->request->data['billing_address']);
        		$record->setFieldValue('Billing_City',$this->request->data['billing_city']);
        		$record->setFieldValue('Billing_State',$this->request->data['billing_state']);
        		$record->setFieldValue('Billing_Code',$this->request->data['billing_zipcode']);
        		$record->setFieldValue('Billing_Country',$this->request->data['billing_country']);
        		$record->setFieldValue('Shipping_Street',$this->request->data['shipping_address']);
        		$record->setFieldValue('Shipping_City',$this->request->data['shipping_city']);
        		$record->setFieldValue('Shipping_State',$this->request->data['shipping_state']);
        		$record->setFieldValue('Shipping_Code',$this->request->data['shipping_zipcode']);
        		$record->setFieldValue('Shipping_Country',$this->request->data['shipping_country']);
        		
        		$record->setFieldValue('Phone',$this->request->data['phone_number']);
        		$record->setFieldValue('Fax',$this->request->data['fax_number']);
        		
        		
        		$record->setFieldValue('Tier_CCs',$this->request->data['tier_cc']);
                $record->setFieldValue('Tier_BSs',$this->request->data['tier_bs']);
                $record->setFieldValue('Tier_SWT',$this->request->data['tier_swt']);
                $record->setFieldValue('Tier_HWTs',$this->request->data['tier_hwt']);
                $record->setFieldValue('Tier_Track',$this->request->data['tier_track']);
                $record->setFieldValue('Tier_Install',$this->request->data['tier_install']);
                $record->setFieldValue('Tier_Fabric',$this->request->data['tier_fabric']);
    			
    			 /** PPSASCRUM -3 Start **/
                $record->setFieldValue('CREDIT_HOLD',$this->request->data['on_credit_hold']);
                $record->setFieldValue('Deposit_Required_Threshold',$this->request->data['deposit_threshold']);
                $record->setFieldValue('Deposit_Required1',$this->request->data['deposit_perc']);
                $record->setFieldValue('Payment_Terms',$this->request->data['payment_terms']);
                /** PPSASCRUM -3 End **/
                
                
                 /** PPSASCRUM -85 Start **/
                $record->setFieldValue('Customer_Notes',$this->request->data['customer_notes']);
                $record->setFieldValue('Pricing_Programs',$this->request->data['pricing_programs']);
              
                /** PPSASCRUM -85 End **/
                
    			$zohoUpdateResult=$record->update();
    			
    			$zohoUpdateResultDetails=$zohoUpdateResult->getDetails();
    			
    			if($zohoUpdateResult->getCode() == 'SUCCESS'){
    			    
    			    $thisCustomer->zoho_last_updated=strtotime($zohoUpdateResultDetails['Modified_Time']);
    			    
    			    if($customerTable->Save($thisCustomer)){
        				$this->logActivity($_SERVER['REQUEST_URI'],'Saved changes to customer "'.$this->request->data['company_name'].'"');
        			}
        			
        			$this->Flash->success('Successfully saved changes to selected Customer');
        			return $this->redirect('/customers/');
    			}else{
    			    echo "HTTP Status Code:" . $responseIns->getHttpStatusCode(); // To get http response code
                    echo "<Br>";
                    echo "Status:" . $responseIns->getStatus(); // To get response status
                    echo "<Br>";
                    echo "Message:" . $responseIns->getMessage(); // To get response    message
                    echo "<Br>";
                    echo "Code:" . $responseIns->getCode(); // To get status code
                    echo "<Br>";
                    echo "Details:" . json_encode($responseIns->getDetails());
                    exit;
    			}
			}else{
			    if($customerTable->Save($thisCustomer)){
    				$this->logActivity($_SERVER['REQUEST_URI'],'Saved changes to customer "'.$this->request->data['company_name'].'"');
    			}
    			
    			$this->Flash->success('Successfully saved changes to selected Customer');
    			return $this->redirect('/customers/');
			}
			
			
		}else{
			$customer=$this->Customers->get($customerID)->toArray();
			$this->set('customer',$customer);
			
		}
		
	}
	
	
	public function delete($customerID){
		$thisCustomer=$this->Customers->get($customerID)->toArray();
		
		if($this->request->data){
			//mark it as deleted locally
			$customerTable=TableRegistry::get('Customers');
			$customerEntry=$customerTable->get($customerID);
			$customerEntry->status='Deleted';
			
			/////////////////////////////
			if(preg_match("#orders.hcinteriors.com#i",$_SERVER['HTTP_HOST'])){
                //only sync to Zoho if we're on the Production site
			    $record = ZCRMRestClient::getInstance()->getRecordInstance("Accounts", $thisCustomer['zoho_account_id']); // To get record instance
                $deleteResult = $record->delete();
    
                if($deleteResult->getCode() == 'SUCCESS'){
                    
                    if($customerTable->save($customerEntry)){
    			    	//log it
    				    $this->logActivity($_SERVER['REQUEST_URI'],"Deleted customer \"".$thisCustomer['company_name']."\" locally and on ZOHO");
    				    $this->Flash->success('Successfully deleted the selected Customer');
    				    return $this->redirect('/customers/');
    			    }
			
                }else{
                    echo "HTTP Status Code:" . $responseIns->getHttpStatusCode(); // To get http response code
                    echo "<br>";
                    echo "Status:" . $responseIns->getStatus(); // To get response status
                    echo "<br>";
                    echo "Message:" . $responseIns->getMessage(); // To get response message
                    echo "<br>";
                    echo "Code:" . $responseIns->getCode(); // To get status code
                    echo "<br>";
                    echo "Details:" . json_encode($responseIns->getDetails());
                }
			}else{
			    
			    if($customerTable->save($customerEntry)){
			    	//log it
				    $this->logActivity($_SERVER['REQUEST_URI'],"Deleted customer \"".$thisCustomer['company_name']."\" locally only");
				    $this->Flash->success('Successfully deleted the selected Customer');
				    return $this->redirect('/customers/');
			    }
			}
			
			exit;
			/////////////////////////////
			
			
		}else{
			$this->set('customer',$thisCustomer);
		}
	}
	
	
	public function editcontact($contactID){
		$contact=$this->CustomerContacts->get($contactID)->toArray();
		$this->set('contactdetails',$contact);
		$customer=$this->Customers->get($contact['customer_id'])->toArray();
		$this->set('customer',$customer);
		
		
		if($this->request->data){
			$contactTable=TableRegistry::get('CustomerContacts');
			$thisContact=$contactTable->get($contactID);
			
			$thisContact->first_name=$this->request->data['first_name'];
			$thisContact->last_name=$this->request->data['last_name'];
			$thisContact->email_address=$this->request->data['email_address'];
			$thisContact->secondary_email=$this->request->data['secondary_email_address'];
			$thisContact->phone=$this->request->data['primary_phone_number'];
			
			$thisContact->mobile_phone=$this->request->data['mobile_phone_number'];
			$thisContact->local_last_updated=time();
			
			
			if(preg_match("#orders.hcinteriors.com#i",$_SERVER['HTTP_HOST'])){
                //only sync to Zoho if we're on the Production site
    			$record = ZCRMRestClient::getInstance()->getRecordInstance("Contacts", $contact['zoho_contact_id']);
    			
    			$record->setFieldValue('First_Name',$this->request->data['first_name']);
    			$record->setFieldValue('Last_Name',$this->request->data['last_name']);
    			$record->setFieldValue('Email',$this->request->data['email_address']);
    			$record->setFieldValue('Secondary_Email',$this->request->data['secondary_email_address']);
    			$record->setFieldValue('Phone',$this->request->data['primary_phone_number']);
    			$record->setFieldValue('Mobile',$this->request->data['mobile_phone_number']);
    			
    			$zohoUpdateResult=$record->update();
    			
    			$zohoUpdateResultDetails=$zohoUpdateResult->getDetails();
    			
    			if($zohoUpdateResult->getCode() == 'SUCCESS'){
    			    $thisContact->zoho_last_updated=strtotime($zohoUpdateResultDetails['Modified_Time']);
    			    
    			    if($contactTable->save($thisContact)){
        				$this->logActivity($_SERVER['REQUEST_URI'],'Edited contact "'.$this->request->data['first_name'].' '.$this->request->data['last_name'].'"');
        				$this->Flash->success('Successfully saved changes to selected Contact');
        				return $this->redirect('/customers/');
        			}
    			}else{
    			    echo "HTTP Status Code:" . $responseIns->getHttpStatusCode(); // To get http response code
                    echo "<Br>";
                    echo "Status:" . $responseIns->getStatus(); // To get response status
                    echo "<Br>";
                    echo "Message:" . $responseIns->getMessage(); // To get response    message
                    echo "<Br>";
                    echo "Code:" . $responseIns->getCode(); // To get status code
                    echo "<Br>";
                    echo "Details:" . json_encode($responseIns->getDetails());
                    exit;
    			}
			}else{

			    if($contactTable->save($thisContact)){
    				$this->logActivity($_SERVER['REQUEST_URI'],'Edited contact "'.$this->request->data['first_name'].' '.$this->request->data['last_name'].'"');
    				$this->Flash->success('Successfully saved changes to selected Contact');
    				return $this->redirect('/customers/');
    			}
			}
			
			
			
		}
		
	}
	
	
	public function deletecontact($contactID){
		$contact=$this->CustomerContacts->get($contactID)->toArray();
		$this->set('contactdetails',$contact);
		$customer=$this->Customers->get($contact['customer_id'])->toArray();
		$this->set('customer',$customer);
		
		
		if($this->request->data){
			
			if(preg_match("#orders.hcinteriors.com#i",$_SERVER['HTTP_HOST'])){
                //only sync to Zoho if we're on the Production site
                $record = ZCRMRestClient::getInstance()->getRecordInstance("Contacts", $contact['zoho_contact_id']); // To get record instance
                $deleteResult = $record->delete();

                if($deleteResult->getCode() == 'SUCCESS'){
                    //mark it as deleted locally
        			$contactTable=TableRegistry::get('CustomerContacts');
        			$contactEntry=$contactTable->get($contactID);
        			$contactEntry->status='Deleted';
        			if($contactTable->save($contactEntry)){
        				//log it
        				$this->logActivity($_SERVER['REQUEST_URI'],"Deleted contact \"".$contact['first_name']." ".$contact['last_name']."\" locally and on ZOHO");
        				$this->Flash->success('Successfully deleted the selected Contact');
        				return $this->redirect('/customers/');
        			}
                    
                }else{
                    echo "HTTP Status Code:" . $responseIns->getHttpStatusCode(); // To get http response code
                    echo "<br>";
                    echo "Status:" . $responseIns->getStatus(); // To get response status
                    echo "<br>";
                    echo "Message:" . $responseIns->getMessage(); // To get response message
                    echo "<br>";
                    echo "Code:" . $responseIns->getCode(); // To get status code
                    echo "<br>";
                    echo "Details:" . json_encode($responseIns->getDetails());
                }
			}else{
			    $contactTable=TableRegistry::get('CustomerContacts');
    			$contactEntry=$contactTable->get($contactID);
    			$contactEntry->status='Deleted';
    			if($contactTable->save($contactEntry)){
    				//log it
    				$this->logActivity($_SERVER['REQUEST_URI'],"Deleted contact \"".$contact['first_name']." ".$contact['last_name']."\" locally only");
    				$this->Flash->success('Successfully deleted the selected Contact');
    				return $this->redirect('/customers/');
    			}
			}
			
			exit;
			
		}
		
	}
	


	public function projects($action=false,$subaction=false,$subsubaction=false){
		$this->autoRender=false;
		
		
		$customers=$this->Customers->find('list',['keyField' => 'id', 'valueField' => 'company_name', 'conditions'=>['status'=>'Active'],'order'=>['company_name'=>'asc']])->toArray();

		$this->set('customers',$customers);

		$managers=$this->Users->find('list',['keyField'=>'id','valueField'=> function($row){ return $row['first_name'].' '.$row['last_name']; },'conditions'=>['status'=>'Active','user_type_id IN' => [1,2]],'order'=>['first_name'=>'ASC']])->toArray();
		$this->set('managers',$managers);
		
		
		switch($action){
			case "add":
				if($this->request->data){
					
					$projectsTable=TableRegistry::get('Projects');
					$newProject=$projectsTable->newEntity();
					$newProject->title=$this->request->data['title'];
					$newProject->status=$this->request->data['project_status'];
					$newProject->customer_id=$this->request->data['customer_id'];
					$newProject->project_manager_id=$this->request->data['project_manager'];
					$newProject->description=$this->request->data['full_description'];
					$newProject->brief_description=$this->request->data['brief_description'];
					if($projectsTable->save($newProject)){
						
						if($subaction=='iframe'){
														
							$allProjects=$this->Projects->find('all',['conditions'=>['status'=>'Active','customer_id'=>$this->request->data['customer_id']],'order'=>['title'=>'asc']])->toArray();
							
							echo "<script>
							parent.$('#projectidwrap').html('<select name=\"project_id\" onchange=\"updateProjectID(this.value)\" id=\"projectid\" tabindex=\"2\"><option value=\"0\">--Select Project--</option>";
							
							foreach($allProjects as $projectRow){
								echo "<option value=\"".$projectRow['id']."\"";
								if($projectRow['id'] == $newProject->id){ echo " selected=\"selected\""; }
								echo ">".$projectRow['title']."</option>";
							}
							
							echo "</select>'); parent.updateProjectID(".$newProject->id."); parent.$.fancybox.close();</script>";
							
							
						}else{
							$this->Flash->success('Successfully created new project "'.$this->request->data['title'].'"');
							$this->logActivity($_SERVER['REQUEST_URI'],"Created project \"".$this->request->data['title']."\"");
							return $this->redirect('/customers/projects/');
						}
					}
					
				}else{
					
					if($subaction=='iframe'){
						$this->viewBuilder()->layout('iframeinner');
						$this->set('customerID',$subsubaction);
						$this->render('addproject');
					}else{
						$this->render('addproject');
					}
				}
			break;
			case "edit":
				if($this->request->data){
					
					//save changes
					$projectsTable=TableRegistry::get('Projects');
					$thisProject=$projectsTable->get($subaction);
					$thisProject->title=$this->request->data['title'];
					$thisProject->status=$this->request->data['project_status'];
					$thisProject->customer_id=$this->request->data['customer_id'];
					$thisProject->project_manager_id=$this->request->data['project_manager'];
					$thisProject->description=$this->request->data['full_description'];
					$thisProject->brief_description=$this->request->data['brief_description'];
					if($projectsTable->save($thisProject)){
						$this->Flash->success('Successfully saved changes to project "'.$this->request->data['title'].'"');
						$this->logActivity($_SERVER['REQUEST_URI'],"Made changes to project \"".$this->request->data['title']."\"");
						return $this->redirect('/customers/projects/');
					}

				}else{
					$thisProject=$this->Projects->get($subaction)->toArray();
					$this->set('projectData',$thisProject);
					
					$this->render('editproject');
					
				}
			break;
			case "delete":
				
				//determine if this project has any quotes associated with it
				$quoteCheck=$this->Quotes->find('all',['conditions'=>['project_id' => $subaction]])->toArray();
				if(count($quoteCheck) > 0){
					$this->Flash->error('You cannot delete projects with existing quotes or orders.');
					return $this->redirect('/customers/projects/');
				}
				
				if($this->request->data){
					//process the delete
					
					$projectsTable=TableRegistry::get('Projects');
					$thisProject=$projectsTable->get($subaction);
					$thisProject->status='deleted';
					if($projectsTable->save($thisProject)){
						$this->Flash->success('Successfully deleted selected Project');
						$this->logActivity($_SERVER['REQUEST_URI'],"Deleted project \"".$thisProject['title']."\"");
						
						return $this->redirect('/customers/projects/');
					}
				}else{
					//confirm the delete
					$this->render('deleteprojectconfirm');
				}
				
			break;
			default:
				$this->render('projectsmain');
			break;
		}
	}
	
	
	public function getprojectslist(){
		
		$projects=array();
		$projectsFind=$this->Projects->find('all',['conditions' => ['status !=' => 'deleted']])->toArray();
		$overallTotalRows=count($projectsFind);
		$totalFilteredRows=count($projectsFind);
		
		foreach($projectsFind as $project){
			
			$customer=$this->Customers->get($project['customer_id'])->toArray();
			$pmUser=$this->Users->get($project['project_manager_id'])->toArray();
			
		
			//find quotes in this project
			$quotesCount=0;
			$ordersCount=0;
			$quotesDollars=0;
			$ordersDollars=0;
			
			
			$quotesFind=$this->Quotes->find('all',['conditions'=>['project_id' => $project['id']]])->toArray();
			foreach($quotesFind as $quoteRow){
				$quotesCount++;
				$quotesDollars=($quotesDollars+floatval($quoteRow['quote_total']));
				
				if($quoteRow['status'] == 'orderplaced'){
					$ordersCount++;
					$ordersDollars=($ordersDollars+floatval($quoteRow['quote_total']));
				}
			}
			
			
			$projects[]=array(
				'DT_RowId'=>'row_'.$project['id'],
				'DT_RowClass'=>'rowtest',
				'0' => '<b>'.$project['title'].'</b>',
				'1' => $customer['company_name'],
				'2' => $pmUser['first_name'].' '.$pmUser['last_name'],
				'3' => $project['status'],
				'4' => $quotesCount,
				'5' => '$'.number_format($quotesDollars,2,'.',','),
				'6' => $ordersCount,
				'7' => '$'.number_format($ordersDollars,2,'.',','),
				'8' => '<a href="/customers/projects/edit/'.$project['id'].'/"><img src="/img/edit.png" /></a> <a href="/customers/projects/delete/'.$project['id'].'/"><img src="/img/delete.png" /></a>'
			);
		}
		
		
		$this->set('draw',$this->request->data['draw']);
		$this->set('recordsTotal',$overallTotalRows);
		$this->set('recordsFiltered',$totalFilteredRows);
		$this->set('data',$projects);
		$this->set('_serialize',['draw','recordsTotal','recordsFiltered','data']);
			
		
	}
}