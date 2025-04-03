<?php
// src/Controller/UsersController.php

namespace App\Controller;

use Cake\Datasource\ConnectionManager;
use App\Controller\AppController;
use Cake\Event\Event;
use Cake\Utility\Security;
use Cake\Core\Configure;
use Cake\ORM\TableRegistry;
use Cake\Mailer\Email;
use Cake\ORM\Table;
use Cewi\Excel;
use \Imagick;
use Tinify\Tinify;
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

class TestsController extends AppController
{

	public function initialize()
    {
        parent::initialize();
        $this->loadComponent('RequestHandler');
		$this->loadComponent('Security');
		$this->loadComponent('Cewi/Excel.Import');
		
		$this->loadModel('Settings');
		$this->loadModel('Users');
		$this->loadModel('UserTypes');
		$this->loadModel('Tiervalues');
		$this->loadModel('Customers');
		$this->loadModel('CustomerContacts');
		$this->loadModel('CcDataMap');
		$this->loadModel('Fabrics');
		$this->loadModel('CubicleCurtainSizes');
		$this->loadModel('CustomerPricingExceptions');
		$this->loadModel('Imageprocessing');
		$this->loadModel('Orders');
		$this->loadModel('Quotes');
		$this->loadModel('QuoteLineItems');
		$this->loadModel('OrderItems');
		$this->loadModel('OrderItemStatus');
		$this->loadModel('SherryCache');
		$this->loadModel('LibraryImages');
		$this->loadModel('SherryBatches');
		//$this->loadModel('Catchallfixes');
		$this->loadModel('QuoteTypes');
		$this->loadModel('FabricAliases');
		
		$this->loadModel('QuoteLineItemNotes');
		$this->loadModel('QuoteBomRequirements');
		$this->loadModel('QuoteDiscounts');
		$this->loadModel('QuoteNotes');
		
		$this->loadModel('ActivityLogs');
		
		//$this->loadModel('TmpCalculatorClassFixes');
        //$this->loadModel('TmpServicesClassFixes');
		
		//ZCRMRestClient::initialize(array("client_id" => $this->getSettingValue('zoho_v2_client_id'), "client_secret" => $this->getSettingValue('zoho_v2_client_secret'), "redirect_uri" => $this->getSettingValue('zoho_v2_redirect_uri'), "currentUserEmail" => "luis@hcinteriors.com", "token_persistence_path" => $_SERVER['DOCUMENT_ROOT']."/config" ));
    }
	
    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
		$this->Auth->allow(['healthcheck']);
    }

    
    public function tgh(){
        $this->autoRender=false;
        $allOrders=$this->Orders->find('all')->toArray();
        foreach($allOrders as $order){
            $thisQuote=$this->Quotes->find('all',['conditions' => ['id' => $order['quote_id']]])->toArray();
            if(count($thisQuote) == 0){
                echo "Order <B>".$order['id']."</B> corresponding quote <B>".$order['quote_id']."</B> not found<br>";
            }
        }
        
    }


    public function testapicalljune(){
        $this->autoRender=false;
        
        $moduleIns = ZCRMRestClient::getInstance()->getModuleInstance("Accounts");
	    $response = $moduleIns->getRecords(array('per_page' => 500),array("if-modified-since" => date('c',(time()-(86400*3)) )));
	    
	    $records=$response->getData();
	    echo "<pre>";
	    print_r($records);
	    echo "</pre>";
	    exit;
	    
    }

    public function childlinefix(){
    	$this->autoRender=false;

    	$lineItemTable=TableRegistry::get('QuoteLineitems');

    	$newLines=$this->QuoteLineItems->find('all',['conditions' => ['quote_id' => 12272]])->toArray();

		foreach($newLines as $newLine){
			//find old line
			$oldLine=$this->QuoteLineItems->get($newLine['revised_from_line'])->toArray();
			if($oldLine['parent_line'] > 0){
				//find the new cloned line that corresponds to the old parent line
				$clonedChildParent=$this->QuoteLineItems->find('all',['conditions'=>['quote_id' => 12272, 'revised_from_line' => $oldLine['parent_line']]])->toArray();
				//update $newLine->parent_line  with  $clonedChildParent->id
				$thisClonedLine=$lineItemTable->get($newLine['id']);
				echo "<pre>";
				print_r($thisClonedLine);
				echo "</pre><hr><pre>";
				print_r($clonedChildParent);
				echo "</pre>";
				//$thisClonedLine->parent_line = $clonedChildParent['id'];
				//$lineItemTable->save($thisClonedLine);
			}
		}

    }


    public function cleanorphanedmetas(){
    	$this->autoRender=false;
    	$itemMetas=$this->QuoteLineItemMeta->find('all')->toArray();
    	foreach($itemMetas as $meta){

    		$lookupLineItem=$this->QuoteLineItems->find('all',['conditions'=>['id' => $meta['quote_item_id']]])->toArray();
    		if(count($lookupLineItem) == 0){
    			echo "DELETE FROM `quote_line_item_meta` WHERE `id`='".$meta['id']."';<br>";
    		}

    	}
    }

     public function index()
     {
        
    }

	public function healthcheck(){
		$this->autoRender=false;
		//check that db connections are working and a SELECT can be made
		$settings=$this->getsettingsarray();
		if(count($settings) >0){
			echo "1";
		}else{
			echo "0";
		}
		
		exit;
	}



	public function fixsherrycacheshipbydates(){
		$this->autoRender=false;
		$sql="";
		$allbatches=$this->SherryCache->find('all')->toArray();
		foreach($allbatches as $batch){
			$thisorder=$this->Orders->get($batch['order_id'])->toArray();
			if($thisorder['due'] != $batch['order_ship_date']){
				echo "Mismatch on batch <b>".$batch['batch_id']."</b>  (Order Says <u>".$thisorder['due']."</u>   Sherry Cache says <u>".$batch['order_ship_date']."</u>)<br>";
				$sql .= "UPDATE `sherry_cache` SET `order_ship_date`='".$thisorder['due']."' WHERE `id`='".$batch['id']."';<br>";
			}
		}

		echo "<hr><pre>".$sql."</pre>";
	}


	public function testbatchstartingpoints(){
		$this->autoRender=false;
		$allOrders=$this->Orders->find('all')->toArray();
		$sql="";
		foreach($allOrders as $order){

			$initialRows=$this->OrderItemStatus->find('all',['conditions' => ['work_order_id' => $order['id'],'status' => 'Not Started']])->toArray();

			$orderItems=$this->OrderItems->find('all',['conditions' => ['order_id' => $order['id']]])->toArray();

			if(count($initialRows) == 0 && count($orderItems) >0){
				echo "MISSING for wo ".$order['id']." (Status: ".$order['status'].")<br>";

				//find the order items
				
				foreach($orderItems as $orderItem){
					$quoteItem=$this->QuoteLineItems->get($orderItem['quote_line_item_id'])->toArray();

					$sql .= "INSERT INTO `order_item_status` (`order_line_number`,`order_item_id`,`time`,`status`,`user_id`,`qty_involved`,`work_order_id`) VALUES ('".$quoteItem['line_number']."','".$orderItem['id']."','".$order['created']."','Not Started','".$order['user_id']."','".$quoteItem['qty']."','".$order['id']."');\n";

				}

			}


		}

		echo "<hr><pre>".$sql."</pre>";

	}


	
	public function createzohoauthtoken(){
		$username = "dallasisp@gmail.com";
		$password = "Mrsketch!";
		$param = "SCOPE=ZohoCRM/crmapi&EMAIL_ID=".$username."&PASSWORD=".$password;
		$ch = curl_init("https://accounts.zoho.com/apiauthtoken/nb/create");
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $param);
		$result = curl_exec($ch);
		/*This part of the code below will separate the Authtoken from the result. 
		Remove this part if you just need only the result*/
		$anArray = explode("\n",$result);
		$authToken = explode("=",$anArray['2']);
		$cmp = strcmp($authToken['0'],"AUTHTOKEN");
		echo $anArray['2']."";
		
		if ($cmp == 0){
			echo "Created Authtoken is : ".$authToken['1'];
			echo $authToken['1'];
		}
		curl_close($ch);
		
	}

      public function zohocontactsync(){
		 $this->autoRender=false;
		 $token=$this->Settings->get(1)->toArray();
		 
		 //customer sync
		 $max=2000;
		 $loops=ceil($max/200);
		 for($i=0; $i < $loops; $i++){
			$start=($i*200);
			$end=($start+200);
		 	$url = "https://crm.zoho.com/crm/private/json/Contacts/getRecords";
		 	$param= "authtoken=".$token['setting_value']."&scope=crmapi&fromIndex=".$start."&toIndex=".$end;//."&sortColumnString=Account Name&sortOrderString=asc";
		 	$ch = curl_init();
		 	curl_setopt($ch, CURLOPT_URL, $url);
		 	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		 	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		 	curl_setopt($ch, CURLOPT_TIMEOUT, 30);
		 	curl_setopt($ch, CURLOPT_POST, 1);
		 	curl_setopt($ch, CURLOPT_POSTFIELDS, $param);
		 	$result = curl_exec($ch);
		 	curl_close($ch);
		 	$data=json_decode($result,true);
			print_r($data);
			echo "\n\n";
		}
		
	  }
	  
	  
	  public function testfive($zohoAccountID){
		  $this->autoRender=false;
	  	$contactCustomerLookup=$this->Customers->find('all',['conditions'=>['zoho_account_id'=>$zohoAccountID]])->first()->toArray();
		print_r($contactCustomerLookup);
		
	  }
	  
	  public function testgetzohotrash(){
		  	$this->autoRender=false;
			$token=$this->Settings->get(1)->toArray();
	  		$url = "https://crm.zoho.com/crm/private/json/Accounts/getDeletedRecordIds";
		 	$param= "authtoken=".$token['setting_value']."&scope=crmapi";
		 	$ch = curl_init();
		 	curl_setopt($ch, CURLOPT_URL, $url);
		 	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		 	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		 	curl_setopt($ch, CURLOPT_TIMEOUT, 30);
		 	curl_setopt($ch, CURLOPT_POST, 1);
		 	curl_setopt($ch, CURLOPT_POSTFIELDS, $param);
		 	$result = curl_exec($ch);
		 	curl_close($ch);
		 	$data=json_decode($result,true);
			
			print_r($data);
	  }
	  
	  
	  public function getchangeditems(){
	  		$this->autoRender=false;
			//https://crm.zoho.com/crm/private/json/Leads/searchRecords?authtoken=Auth Token&scope=crmapi&criteria=(((Last Name:Steve)AND(Company:Zillum))OR(Lead Status:Contacted))
			$token=$this->Settings->get(1)->toArray();
	  		$url = "https://crm.zoho.com/crm/private/json/Accounts/searchRecords";
		 	$param= "authtoken=".$token['setting_value']."&scope=crmapi&criteria=(lastModifiedTime)";
		 	$ch = curl_init();
		 	curl_setopt($ch, CURLOPT_URL, $url);
		 	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		 	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		 	curl_setopt($ch, CURLOPT_TIMEOUT, 30);
		 	curl_setopt($ch, CURLOPT_POST, 1);
		 	curl_setopt($ch, CURLOPT_POSTFIELDS, $param);
		 	$result = curl_exec($ch);
		 	curl_close($ch);
		 	$data=json_decode($result,true);
			
			print_r($data);
	  }
	  
	  
	  /*public function testggg(){
	  	$this->autoRender=false;
		$token=$this->Settings->get(1)->toArray();
		
		$tz_string = "America/Chicago";
    	$tz_object = new \DateTimeZone($tz_string); 
    
    	$datetime = new \DateTime(); 
    	$datetime->setTimezone($tz_object);
		$datetime->setTimestamp((time()-605));
		
		echo 'https://crm.zoho.com/crm/private/xml/Accounts/getRecords?newFormat=1&authtoken='.$token['setting_value'].'&scope=crmapi&lastModifiedTime='.$datetime->format('Y-m-d H:i:s');
		echo '<hr>';
		$url = "https://crm.zoho.com/crm/private/json/Accounts/getRecords";
	 	$param = 'authtoken='.$token['setting_value'].'&scope=crmapi&lastModifiedTime='.$datetime->format('Y-m-d H:i:s');
	 	$ch = curl_init();
	 	curl_setopt($ch, CURLOPT_URL, $url);
	 	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
	 	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	 	curl_setopt($ch, CURLOPT_TIMEOUT, 30);
	 	curl_setopt($ch, CURLOPT_POST, 1);
	 	curl_setopt($ch, CURLOPT_POSTFIELDS, $param);
	 	$result = curl_exec($ch);
	 	curl_close($ch);
	 	$data=json_decode($result,true);
		print_r($data);
		
	  }*/
	  
	  
	  public function testaddcontact($customerID){
		  	$thisCustomer=$this->Customers->get($customerID)->toArray();
			$zohoAccountID=$thisCustomer['zoho_account_id'];
			
			
	  		$url = "https://crm.zoho.com/crm/private/xml/Contacts/insertRecords";
			$ch = curl_init();
			$param= "authtoken=".$token['setting_value']."&scope=crmapi&newFormat=1&xmlData=";
			$xmldata='';
			$xmldata .= '<Contacts>';
			$xmldata .= '<row no="1">';
			$xmldata .= '<FL val="ACCOUNTID">'.$zohoAccountID.'</FL>';
			$xmldata .= '<FL val="First Name">'.$this->request->data['first_name'].'</FL>';
			$xmldata .= '<FL val="Last Name">'.$this->request->data['last_name'].'</FL>';
			$xmldata .= '<FL val="Email">'.$this->request->data['email_address'].'</FL>';
			$xmldata .= '<FL val="Other Email">'.$this->request->data['secondary_email_address'].'</FL>';
			$xmldata .= '<FL val="Phone">'.$this->request->data['primary_phone_number'].'</FL>';
			$xmldata .= '<FL val="Other Phone">'.$this->request->data['other_phone_number'].'</FL>';
			$xmldata .= '<FL val="Mobile Phone">'.$this->request->data['mobile_phone_number'].'</FL>';
			$xmldata .= '</row>';
			$xmldata .= '</Contacts>';
			
			$param=$param.urlencode($xmldata);
			
			
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_TIMEOUT, 30);
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $param);
			$result = curl_exec($ch);
			curl_close($ch);
			$data=simplexml_load_string($result);
			print_r($data);
	  }
	  
	  
	public function fixvaratts(){
		$this->autoRender=false;
	  	$products=$this->Products->find('all')->toArray();
		foreach($products as $product){
			//find all this product's variations
			$variations=$this->ProductVariations->find('all',['conditions'=>['product_id'=>$product['id']]])->toArray();
			foreach($variations as $variation){
				
				echo "INSERT INTO `product_attribute_values` VALUES ('','".$product['id']."','".$variation['attribute_id']."','".$variation['title']."');<br>";
			}
		}
	}


	public function fixvarattmaps(){
	  	$this->autoRender=false;
	  	
	}

	
	
	public function setzohotiers($offset=0,$limit=100){
		/*$this->autoRender=false;
		$token=$this->Settings->get(1)->toArray();
				
		
		$xmldata='';
		$xmldata .= "<Accounts>";//\n\n";
		
		//go through each customer and build an API action to call to ZOHO API
		$hciaccounts=$this->Customers->find('all')->offset($offset)->limit($limit)->toArray();
		$i=1;
		
		$tierMap=array(
			'1' => '1 (BEST, 50% disc, 0% prem)',
			'2' => '2 (48.75% disc, 2.5% prem)',
			'3' => '3 (47.5% disc, 5% prem)',
			'4' => '4 (45% disc, 10% prem)',
			'5' => '5 (37.5% disc, 25% prem)',
			'6' => '6 (25% disc, 50% prem)',
			'7' => '7 (LIST, 0% disc, 100% prem)',
			'8' => '8 (COMPETITOR, -25% disc, 150% prem)'
		);
		
	
		foreach($hciaccounts as $customer){
			$xmldata .= '<row no="'.$i.'">';
			//$xmldata .= "\n";
			$xmldata .= '<FL val="Id">'.$customer['zoho_account_id'].'</FL>';
			//$xmldata .= "\n";
			$xmldata .= '<FL val="Tier - BSs">'.$tierMap[$customer['tier_bs']].'</FL>';
			//$xmldata .= "\n";
			$xmldata .= '<FL val="Tier - CCs">'.$tierMap[$customer['tier_cc']].'</FL>';
			//$xmldata .= "\n";
			$xmldata .= '<FL val="Tier - SWTs">'.$tierMap[$customer['tier_swt']].'</FL>';
			//$xmldata .= "\n";
			$xmldata .= '<FL val="Tier - Track">'.$tierMap[$customer['tier_track']].'</FL>';
			//$xmldata .= "\n";
			$xmldata .= '<FL val="Tier - HWTs">'.$tierMap[$customer['tier_hwt']].'</FL>';
			//$xmldata .= "\n";
			$xmldata .= '<FL val="Tier - Install">'.$tierMap[$customer['tier_install']].'</FL>';
			//$xmldata .= "\n";
			$xmldata .= '<FL val="Tier - Fabric">'.$tierMap[$customer['tier_fabric']].'</FL>';
			//$xmldata .= "\n";
			$xmldata .= "</row>";//\n\n";
			$i++;
		}
		
		//https://crm.zoho.com/crm/private/xml/Accounts/updateRecords?authtoken='.$authtoken.'&scope=crmapi&version=4&xmlData=
		
		$url = "https://crm.zoho.com/crm/private/xml/Accounts/updateRecords";
		$ch = curl_init();
		$param= "authtoken=".$token['setting_value']."&scope=crmapi&version=4&xmlData=";
		
		$xmldata .= '</Accounts>';
		//echo "AUTH TOKEN: ".$token['setting_value']."\n\n";
		//echo $xmldata;
		//exit;
		
		$param=$param.urlencode($xmldata);
			
			
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_TIMEOUT, 30);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $param);
		$result = curl_exec($ch);
		curl_close($ch);
		$data=simplexml_load_string($result);
		print_r($data);
		
		*/
	}

	
	public function testassociations(){
		//$this->autoRender=false;
		$quotes=$this->Quotes->find('all',['conditions' => ['status'=>'published'], 'contain' => ['QuoteLineItems','QuoteLineItems.QuoteLineItemMeta']])->toArray();
		$this->set('quotes',$quotes);
	}
	
	
	public function fixscweights(){
		$this->autoRender=false;
		$weights=$this->CcDataMap->find('all',['conditions'=>['cc_id'=>'27']])->toArray();
		foreach($weights as $weight){
			echo "UPDATE `cc_data_map` SET `weight`='".$weight['weight']."' WHERE `size_id`='".$weight['size_id']."' AND `cc_id`='28';<br>";
		}
	}
	
	
	public function testimportxls(){
		if($this->request->data){
			move_uploaded_file($this->request->data['xlsfile']['tmp_name'], TMP . DS . $this->request->data['xlsfile']['name']);
			$data=$this->Import->prepareEntityData(TMP . $this->request->data('xlsfile.name'));
			$this->set('data',$data);
		}else{
			
		}
	}
	
	
	public function testquerygrouping(){
		$this->autoRender=false;
		$specialPricing=$this->CustomerPricingExceptions->find('all',['conditions'=>['product_type'=>'cc']])->group(['customer_id','product_id'])->toArray();
		print_r($specialPricing);
	}
	

	public function emailtest(){
		$this->autoRender=false;

		$email = new Email();
		$email->to('dallascx21arcade@gmail.com');
		$email->bcc('b747fp@gmail.com');
		$email->subject('Testing Pupusa Emailer');
		$email->emailFormat('html');

		$email->send('<html><head></head><body><p><img src="https://orders.hcinteriors.com/img/hci-logo.png" /></p><p>Hello from pupusa</p></body></html>');
	}


	public function testcommittedtotals($fabricID,$quilted=0){
		$this->autoRender=false;

		$thisFabric=$this->Fabrics->get($fabricID)->toArray();

		$committedYardsThisFabricQuilted=$this->getfabriccommittedyards($fabricID,1);
		$committedYardsThisFabricUnquilted=$this->getfabriccommittedyards($fabricID,0);

		if($quilted==1){
			echo $committedYardsThisFabricQuilted;
		}else{
			echo $committedYardsThisFabricUnquilted;
		}
		exit;

	}
	
	
	public function testoneone($totalyards){
		$this->autoRender=false;
		
		$defaultQuery=0;
		//build different query with conditions
		$yardconfigs=array('status' => 'Active');

		$rollLiveYards=array();

		$matches=array();

		$allFabrics=$this->Fabrics->find('all',['conditions' => ['is_hci_fabric' => 1, 'com_fabric' => 0]])->toArray();
			
		foreach($allFabrics as $fabric){
			$totalyardsthisfabric=0;
			//find all rolls with this fabric id

			$allrolls=$this->MaterialInventory->find('all',['conditions' => ['material_type'=>'Fabrics','material_id'=>$fabric['id'],'status'=>'Active']])->toArray();
			foreach($allrolls as $roll){
				$thisrollyards=$this->getfabricyardsonroll($roll['id']);
				$rollLiveYards[$roll['id']]=$thisrollyards;
				$totalyardsthisfabric = ($totalyardsthisfabric + $thisrollyards);
			}


			$committedYardsThisFabricQuilted=$this->getfabriccommittedyards($fabric['id'],1);
			$committedYardsThisFabricUnquilted=$this->getfabriccommittedyards($fabric['id'],0);

			$availableYardsThisFabric = ($totalyardsthisfabric - ($committedYardsThisFabricQuilted + $committedYardsThisFabricUnquilted));

			if($availableYardsThisFabric >= floatval($totalyards)){
				//meets this request
				$matches[]=array('fabric_id'=>$fabric['id'],'fabric_name'=>$fabric['fabric_name'],'color'=>$fabric['color'],'total_yards'=>$totalyardsthisfabric,'committed_yards_quilted'=>$committedYardsThisFabricQuilted, 'committed_yards_unquilted' => $committedYardsThisFabricUnquilted, 'num_rolls'=>count($allrolls),'allrolls'=>$allrolls);
			}


		}
		
		exit;
		
	}

	
	public function fullzohocontactsync(){
		
		$this->autoRender=false;
		$token=$this->Settings->get(1)->toArray();
		$time=time();
		
		mkdir($_SERVER['DOCUMENT_ROOT']."/jsoncontactfiles/".$time);
		
		//contact sync
		 $max=2600;
		 $loops=ceil($max/200);
		 for($i=0; $i < $loops; $i++){
			$start=($i*200);
			$end=($start+200);
		 	$url = "https://crm.zoho.com/crm/private/json/Contacts/getRecords";
		 	$param= "authtoken=".$token['setting_value']."&scope=crmapi&fromIndex=".$start."&toIndex=".$end."&sortColumnString=Account Name&sortOrderString=asc";
		 	$ch = curl_init();
		 	curl_setopt($ch, CURLOPT_URL, $url);
		 	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		 	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		 	curl_setopt($ch, CURLOPT_TIMEOUT, 30);
		 	curl_setopt($ch, CURLOPT_POST, 1);
		 	curl_setopt($ch, CURLOPT_POSTFIELDS, $param);
		 	$result = curl_exec($ch);
		 	curl_close($ch);
			
			$file=fopen($_SERVER['DOCUMENT_ROOT']."/jsoncontactfiles/".$time."/".$i.".json","w");
			 fwrite($file,$result,strlen($result));
			 fclose($file);
			
		 	$data=json_decode($result,true);
			echo "<pre>";
			 print_r($data);
			echo "</pre><hr>";
			 sleep(2);
		}
		
		
	}
	
	public function fullzohosync(){
		
		$this->autoRender=false;
		 $token=$this->Settings->get(1)->toArray();
		 
		$time=time();
		
		mkdir($_SERVER['DOCUMENT_ROOT']."/jsonfiles/".$time);
		
		 //customer sync
		 $max=1000;
		 $loops=ceil($max/200);
		 for($i=0; $i < $loops; $i++){
			$start=($i*200);
			$end=($start+200);
		 	$url = "https://crm.zoho.com/crm/private/json/Accounts/getRecords";
		 	$param= "authtoken=".$token['setting_value']."&scope=crmapi&fromIndex=".$start."&toIndex=".$end."&sortColumnString=Account Name&sortOrderString=asc";
		 	$ch = curl_init();
		 	curl_setopt($ch, CURLOPT_URL, $url);
		 	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		 	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		 	curl_setopt($ch, CURLOPT_TIMEOUT, 30);
		 	curl_setopt($ch, CURLOPT_POST, 1);
		 	curl_setopt($ch, CURLOPT_POSTFIELDS, $param);
		 	$result = curl_exec($ch);
		 	curl_close($ch);
			
			$file=fopen($_SERVER['DOCUMENT_ROOT']."/jsonfiles/".$time."/".$i.".json","w");
			 fwrite($file,$result,strlen($result));
			 fclose($file);
			
		 	$data=json_decode($result,true);
			echo "<pre>";
			 print_r($data);
			echo "</pre><hr>";
			 sleep(2);
		}
		
		return $this->redirect('/tests/zohosyncfromjson/'.$time);
		
	}
	
	
	public function zohosyncfromjson($time){
		$this->autoRender=false;
		
		
		if ($handle = opendir($_SERVER['DOCUMENT_ROOT'].'/jsonfiles/'.$time)) {
			while (false !== ($entry = readdir($handle))) {
				if ($entry != "." && $entry != "..") {
					$data=json_decode(file_get_contents($_SERVER['DOCUMENT_ROOT'].'/jsonfiles/'.$time.'/'.$entry),true);
					
					foreach($data['response']['result']['Accounts']['row'] as $num => $zohoResultRow){
						
						//////////////////////////////////////////////////////////
						$zohoAccountID='';
						$zohoAccountName='';
						$zohoCreatedTimestamp=0;
						$zohoLastUpdatedTimestamp=0;
						$zohoCustomerSurcharge=0.00;

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
						$zohoTierCCs='';
						$zohoTierBSs='';
						$zohoTierSWTs='';
						$zohoTierHWTs='';
						$zohoTierTrack='';
						$zohoTierInstall='';
						$zohoTierFabric='';

						foreach($zohoResultRow['FL'] as $keyval){
							switch($keyval['val']){
								case "Account Name":
									if(strlen(trim($keyval['content'])) >0){ $zohoAccountName=trim($keyval['content']); }
								break;
								case "ACCOUNTID":
									if(strlen(trim($keyval['content'])) >0){ $zohoAccountID=intval($keyval['content']); }
								break;
								case "Created Time":
									if(strlen(trim($keyval['content'])) >0){ $zohoCreatedTimestamp=strtotime($keyval['content']); }
								break;
								case "Modified Time":
									if(strlen(trim($keyval['content'])) >0){ $zohoLastUpdatedTimestamp=strtotime($keyval['content']); }
								break;
								/*
								case "ADD":
									$surcharge=intval(str_replace('%','',trim($keyval['content'])));
									if(strlen(trim($keyval['content'])) >0){ $zohoCustomerSurcharge=number_format((1.0+($surcharge/100)),2,'.',''); }
								break;
								*/
								case 'Surcharge %':
									if(strlen(trim($keyval['content'])) >0){ $zohoSurchargePercent=floatval(str_replace('%','',trim($keyval['content']))); }
								break;
								case 'Surcharge $':
									if(strlen(trim($keyval['content'])) >0){ $zohoSurchargeDollar=floatval(str_replace('%','',str_replace('$','',trim($keyval['content'])))); }
								break;
								case "Billing Street":
									if(strlen(trim($keyval['content'])) >0){ $zohoBillingAddress=trim($keyval['content']); }
								break;
								case "Billing City":
									if(strlen(trim($keyval['content'])) >0){ $zohoBillingCity=trim($keyval['content']); }
								break;
								case "Billing State":
									if(strlen(trim($keyval['content'])) >0){ $zohoBillingState=trim($keyval['content']); }
								break;
								case "Billing Code":
									if(strlen(trim($keyval['content'])) >0){ $zohoBillingZipcode=trim($keyval['content']); }
								break;
								case "Billing Country":
									if(strlen(trim($keyval['content'])) >0){ $zohoBillingCountry=trim($keyval['content']); }
								break;
								case "Shipping Street":
									if(strlen(trim($keyval['content'])) >0){ $zohoShippingAddress=trim($keyval['content']); }
								break;
								case "Shipping City":
									if(strlen(trim($keyval['content'])) >0){ $zohoShippingCity=trim($keyval['content']); }
								break;
								case "Shipping State":
									if(strlen(trim($keyval['content'])) >0){ $zohoShippingState=trim($keyval['content']); }
								break;
								case "Shipping Code":
									if(strlen(trim($keyval['content'])) >0){ $zohoShippingZipcode=trim($keyval['content']); }
								break;
								case "Shipping Country":
									if(strlen(trim($keyval['content'])) >0){ $zohoShippingCountry=trim($keyval['content']); }
								break;
								case "Phone":
									if(strlen(trim($keyval['content'])) >0){ $zohoPhone=trim($keyval['content']); }
								break;
								case "Website":
									if(strlen(trim($keyval['content'])) >0){ $zohoWebsite=trim($keyval['content']); }
								break;
								case "Fax":
									if(strlen(trim($keyval['content'])) >0){ $zohoFax=trim($keyval['content']); }
								break;
								case "Tier - BSs":
									$valexp=explode(" (",trim($keyval['content']));
									if(strlen(trim($keyval['content'])) >0){ $zohoTierBSs=$valexp[0]; }
								break;
								case "Tier - CCs":
									$valexp=explode(" (",trim($keyval['content']));
									if(strlen(trim($keyval['content'])) >0){ $zohoTierCCs=$valexp[0]; }
								break;
								case "Tier - SWTs":
									$valexp=explode(" (",trim($keyval['content']));
									if(strlen(trim($keyval['content'])) >0){ $zohoTierSWTs=$valexp[0]; }
								break;
								case "Tier - Track":
									$valexp=explode(" (",trim($keyval['content']));
									if(strlen(trim($keyval['content'])) >0){ $zohoTierTrack=$valexp[0]; }
								break;
								case "Tier - HWTs":
									$valexp=explode(" (",trim($keyval['content']));
									if(strlen(trim($keyval['content'])) >0){ $zohoTierHWTs=$valexp[0]; }
								break;
								case "Tier - Install":
									$valexp=explode(" (",trim($keyval['content']));
									if(strlen(trim($keyval['content'])) >0){ $zohoTierInstall=$valexp[0]; }
								break;
								case "Tier - Fabric":
									$valexp=explode(" (",trim($keyval['content']));
									if(strlen(trim($keyval['content'])) >0){ $zohoTierFabric=$valexp[0]; }
								break;
							}
					}
					
					$customerTable=TableRegistry::get('Customers');
						
					$customerLookup=$this->Customers->find('all',['conditions'=>['zoho_account_id' => $zohoAccountID]])->toArray();
					foreach($customerLookup as $customer){
						//something has changed, let's double check
						$changeCount=0;
						$thisCustomer=$customerTable->get($customer->id);
						if($customer['company_name'] != $zohoAccountName){
							$thisCustomer->company_name=$zohoAccountName;
							$changeCount++;
						}
						if($customer['billing_address'] != $zohoBillingAddress){
							$thisCustomer->billing_address=$zohoBillingAddress;
							$changeCount++;
						}
						if($customer['billing_address_city'] != $zohoBillingCity){
							$thisCustomer->billing_address_city=$zohoBillingCity;
							$changeCount++;
						}
						if($customer['billing_address_state'] != $zohoBillingState){
							$thisCustomer->billing_address_state=$zohoBillingState;
							$changeCount++;
						}
						if($customer['billing_address_zipcode'] != $zohoBillingZipcode){
							$thisCustomer->billing_address_zipcode=$zohoBillingZipcode;
							$changeCount++;
						}
						if($customer['shipping_address'] != $zohoShippingAddress){
							$thisCustomer->shipping_address=$zohoShippingAddress;
							$changeCount++;
						}
						if($customer['shipping_address_city'] != $zohoShippingCity){
							$thisCustomer->shipping_address_city=$zohoShippingCity;
							$changeCount++;
						}
						if($customer['shipping_address_state'] != $zohoShippingState){
							$thisCustomer->shipping_address_state=$zohoShippingState;
							$changeCount++;
						}
						if($customer['shipping_address_zipcode'] != $zohoShippingZipcode){
							$thisCustomer->shipping_address_zipcode=$zohoShippingZipcode;
							$changeCount++;
						}
						if($customer['shipping_address_country'] != $zohoShippingCountry){
							$thisCustomer->shipping_address_country=$zohoShippingCountry;
							$changeCount++;
						}

						if($customer['surcharge_percent'] != $zohoSurchargePercent){
							$thisCustomer->surcharge_percent=$zohoSurchargePercent;
							$changeCount++;
						}
						if($customer['surcharge_dollars'] != $zohoSurchargeDollar){
							$thisCustomer->surcharge_dollars=$zohoSurchargeDollar;
							$changeCount++;
						}

						if($customer['tier_cc'] != $zohoTierCCs){
							$thisCustomer->tier_cc=$zohoTierCCs;
							$changeCount++;
						}
						if($customer['tier_bs'] != $zohoTierBSs){
							$thisCustomer->tier_bs=$zohoTierBSs;
							$changeCount++;
						}
						if($customer['tier_swt'] != $zohoTierSWTs){
							$thisCustomer->tier_swt=$zohoTierSWTs;
							$changeCount++;
						}
						if($customer['tier_hwt'] != $zohoTierHWTs){
							$thisCustomer->tier_hwt=$zohoTierHWTs;
							$changeCount++;
						}
						if($customer['tier_track'] != $zohoTierTrack){
							$thisCustomer->tier_track=$zohoTierTrack;
							$changeCount++;
						}
						if($customer['tier_install'] != $zohoTierInstall){
							$thisCustomer->tier_install=$zohoTierInstall;
							$changeCount++;
						}
						if($customer['tier_fabric'] != $zohoTierFabric){
							$thisCustomer->tier_fabric=$zohoTierFabric;
							$changeCount++;
						}
						$thisCustomer->zoho_last_updated=$zohoLastUpdatedTimestamp;

						if($changeCount > 0){
							if($customerTable->save($thisCustomer)){
								echo "Updated changes for customer <b>".$zohoAccountName."</b> from ZOHO<br>";
							}
						}
						
					}
					
				}
						
						//////////////////////////////////////////////////////////
						
					}
					
				}
			}
			closedir($handle);
		
	}
	
	
	
	
	public function zohosynccontactsfromjson($time){
		$this->autoRender=false;
		
		
		if ($handle = opendir($_SERVER['DOCUMENT_ROOT'].'/jsoncontactfiles/'.$time)) {
			while (false !== ($entry = readdir($handle))) {
				if ($entry != "." && $entry != "..") {
					$data=json_decode(file_get_contents($_SERVER['DOCUMENT_ROOT'].'/jsoncontactfiles/'.$time.'/'.$entry),true);
					
					foreach($data['response']['result']['Contacts']['row'] as $num => $zohoResultRow){
						
						//////////////////////////////////////////////////////////
						$zohoContactID='';
						$zohoAccountID='';
						$zohoFirstName='';
						$zohoLastName='';
						$zohoCreatedTime='';
						$zohoModifiedTime='';
						$zohoEmail='';
						$zohoSecondaryEmail='';
						$zohoPhone='';
						//$zohoOtherPhone='';
						$zohoMobile='';
						$zohoDescription='';
						$zohoEmailOptOut='';
						

						foreach($zohoResultRow['FL'] as $keyval){
							switch($keyval['val']){
								case "CONTACTID":
									if(strlen(trim($keyval['content'])) >0){ $zohoContactID=$keyval['content']; }
								break;
								case "ACCOUNTID":
									if(strlen(trim($keyval['content'])) >0){ $zohoAccountID=$keyval['content']; }
								break;
								case "First Name":
									if(strlen(trim($keyval['content'])) >0){ $zohoFirstName=$keyval['content']; }
								break;
								case "Last Name":
									if(strlen(trim($keyval['content'])) >0){ $zohoLastName=$keyval['content']; }
								break;
								case "Created Time":
									if(strlen(trim($keyval['content'])) >0){ $zohoCreatedTime=strtotime($keyval['content']); }
								break;
								case "Modified Time":
									if(strlen(trim($keyval['content'])) >0){ $zohoModifiedTime=strtotime($keyval['content']); }
								break;
								case "Email":
									if(strlen(trim($keyval['content'])) >0){ $zohoEmail=$keyval['content']; }
								break;
								case "Secondary Email":
									if(strlen(trim($keyval['content'])) >0){ $zohoSecondaryEmail=$keyval['content']; }
								break;
								case "Phone":
									if(strlen(trim($keyval['content'])) >0){ $zohoPhone=$keyval['content']; }
								break;
								case "Mobile":
									if(strlen(trim($keyval['content'])) >0){ $zohoMobile=$keyval['content']; }
								break;
								case "Description":
									if(strlen(trim($keyval['content'])) >0){ $zohoDescription=$keyval['content']; }
								break;
								case "Email Opt Out":
									if(strlen(trim($keyval['content'])) >0){ $zohoEmailOptOut=$keyval['content']; }
								break;
							}
							
						}
					
						
						$pupusaCustomerID=0;
						//find local customerid
						$pupusaCustomerFind=$this->Customers->find('all',['conditions'=>['zoho_account_id'=>$zohoAccountID]])->toArray();
						if(count($pupusaCustomerFind) == 1){
							foreach($pupusaCustomerFind as $pupusaCustomer){
								$pupusaCustomerID=$pupusaCustomer['id'];
							}
						}
						
						
						
						
					$contactTable=TableRegistry::get('CustomerContacts');
						
					$contactLookup=$this->CustomerContacts->find('all',['conditions'=>['zoho_contact_id' => $zohoContactID]])->toArray();
						
						
					if(count($contactLookup) == 0){
						
						//create the contact
						
						$newContact=$contactTable->newEntity();
						$newContact->first_name=$zohoFirstName;
						$newContact->last_name=$zohoLastName;
						$newContact->email_address=$zohoEmail;
						$newContact->secondary_email=$zohoSecondaryEmail;
						$newContact->phone=$zohoPhone;
						$newContact->mobile_phone=$zohoMobile;
						$newContact->local_last_updated=time();
						$newContact->local_created=time();
						$newContact->zoho_last_updated=$zohoModifiedTime;
						$newContact->zoho_created=$zohoCreatedTime;
						$newContact->description=$zohoDescription;
						$newContact->customer_id=$pupusaCustomerID;
						
						if(strval($zohoEmailOptOut) == "true"){
							$newContact->email_optout = 1;
						}elseif(strval($zohoEmailOptOut) == "false"){
							$newContact->email_optout = 0;
						}
						
						$newContact->status='Active';
						
						if($contactTable->save($newContact)){
							echo "Added contact <b>".$zohoFirstName." ".$zohoLastName."</b> from ZOHO<br>";
						}
						
					}else{
						
							foreach($contactLookup as $contact){
								//something has changed, let's double check
								$changeCount=0;
								$thisContact=$contactTable->get($contact->id);

								if($contact['first_name'] != $zohoFirstName){
									$thisContact->first_name=$zohoFirstName;
									$changeCount++;
								}
								if($contact['last_name'] != $zohoLastName){
									$thisContact->last_name=$zohoLastName;
									$changeCount++;
								}
								if($contact['email_address'] != $zohoEmail){
									$thisContact->email_address=$zohoEmail;
									$changeCount++;
								}
								if($contact['secondary_email'] != $zohoSecondaryEmail){
									$thisContact->secondary_email=$zohoSecondaryEmail;
									$changeCount++;
								}
								if($contact['phone'] != $zohoPhone){
									$thisContact->phone=$zohoPhone;
									$changeCount++;
								}
								if($contact['mobile_phone'] != $zohoMobile){
									$thisContact->mobile_phone=$zohoMobile;
									$changeCount++;
								}
								if($contact['description'] != $zohoDescription){
									$thisContact->description=$zohoDescription;
									$changeCount++;
								}

								if($contact['email_optout'] == "0" && strval($zohoEmailOptOut) == "true"){
									$thisContact->email_optout = 1;
									$changeCount++;
								}elseif($contact['email_optout'] == "1" && strval($zohoEmailOptOut) == "false"){
									$thisContact->email_optout = 0;
									$changeCount++;
								}

								$thisContact->customer_id=$pupusaCustomerID;
								$thisContact->zoho_last_updated=$zohoModifiedTime;

								if($changeCount > 0){
									if($contactTable->save($thisContact)){
										echo "Updated changes for contact <b>".$zohoFirstName." ".$zohoLastName."</b> from ZOHO<br>";
									}
								}

							}
					}
					
				}
						
						//////////////////////////////////////////////////////////
						
					}
					
				}
			}
			closedir($handle);
		
	}
	
	
	
	public function generatesmallerimages(){
		$this->autoRender=false;
		
		$allFabrics=$this->Fabrics->find('all')->toArray();
		foreach($allFabrics as $fabric){
			if ($handle = opendir($_SERVER['DOCUMENT_ROOT'].'/webroot/files/fabrics/'.$fabric['id'])) {
				while (false !== ($entry = readdir($handle))) {
					if ($entry != "." && $entry != "..") {

						$imagick = new \Imagick($_SERVER['DOCUMENT_ROOT'].'/webroot/files/fabrics/'.$fabric['id'].'/'.$entry);

						$imagick->resizeImage(200, 0, imagick::FILTER_LANCZOS,1);

						$imagetype=strtolower(substr($entry,-4));
						
						switch($imagetype){
							case ".png":
								$newfile=$_SERVER['DOCUMENT_ROOT'].'/webroot/files/fabrics/'.$fabric['id'].'/'.str_replace('.png','-small.png',$entry);
							break;
							case ".jpg":
								$newfile=$_SERVER['DOCUMENT_ROOT'].'/webroot/files/fabrics/'.$fabric['id'].'/'.str_replace('.jpg','-small.jpg',$entry);
							break;
							case "jpeg";
								$newfile=$_SERVER['DOCUMENT_ROOT'].'/webroot/files/fabrics/'.$fabric['id'].'/'.str_replace('.jpeg','-small.jpg',$entry);
							break;
							case ".gif":
								$newfile=$_SERVER['DOCUMENT_ROOT'].'/webroot/files/fabrics/'.$fabric['id'].'/'.str_replace('.gif','-small.gif',$entry);
							break;
						}
						
						$imagick->writeImage($newfile);
						echo "Resized <b>".$_SERVER['DOCUMENT_ROOT']."/webroot/files/fabrics/".$fabric['id']."/".$entry."</b> to <b>".$newfile."</b><br>";
						
						$imagick->clear();
						$imagick->destroy();

					}
				}
			}
		}
		exit;
	}
	
	
	
	public function groupperms(){
		$this->autoRender=false;
		
		
		echo json_encode(array('index@Quotes','index@Orders','index@Products'));
		exit;
		
		
		$admin=$this->UserTypes->get(1)->toArray();
		$perms=json_decode($admin['permissions'],true);
		echo "<pre>";
		print_r($perms);
		echo "</pre>";
		exit;
		
	}

	
	
	public function testzohocontactrecentchanges(){
		
		$this->autoRender=false;
		$token=$this->Settings->get(1)->toArray();
		 
		
		$manual=15000;
		
     	$tz_object = new \DateTimeZone("America/Chicago"); 
    	$datetime = new \DateTime(); 
    	$datetime->setTimezone($tz_object);
		$datetime->setTimestamp((time()-$manual));
		
		//contact sync
		$url = "https://crm.zoho.com/crm/private/json/Contacts/getRecords";
		$param = 'authtoken='.$token['setting_value'].'&scope=crmapi&lastModifiedTime='.$datetime->format('Y-m-d H:i:s');
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_TIMEOUT, 30);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $param);
		$result = curl_exec($ch);
		curl_close($ch);
		$data=json_decode($result,true);
		
		echo "<pre>";
		print_r($data);
		echo "</pre>";
		exit;
		
		
	}
	
	
	public function sherrytestwthwblinds($quoteID,$type){
		$this->autoRender=false;
		
		echo "<b>QTY:</b> ";
		echo $this->getTypeQty($quoteID,$type);
		echo "<hr><b>DOLLARS:</b> ";
		echo $this->getTypeDollars($quoteID,$type);
		
	}
	
	
	public function testadvancedqueries(){
		
		$scheduledOrders=$this->Orders->find('all',[
			'conditions'=>[
				'Orders.completed'=>0,
				'Orders.status !=' => 'Needs Line Items',
				'Orders.sherry_status !='=>'Not Scheduled'
			],
			'contain' => [
				'Customers',
				'Quotes',
				'OrderItems' => [
					'OrderItemStatus'
				]
			],
			'order' => ['Orders.id' => 'desc']
		])->limit(1)->toArray();
		
		$this->set('scheduledOrders',$scheduledOrders);
		
	}

	
	public function datetest(){
		$this->autoRender=false;
		$dateOld='06/18/2018';
		$correctedDate=date('Y-m-d',strtotime($dateOld.' 08:00:00'));
		echo "OLD: ".$dateOld." ==> NEW: ".$correctedDate;exit;
		
	}
	
	public function fixjpegsandpngs(){
		$this->autoRender=false;
		$imgproctable=TableRegistry::get('Imageprocessing');
		
		if($innerhandle = opendir($_SERVER['DOCUMENT_ROOT'].'/webroot/img/library')) {
			while (false !== ($innerentry = readdir($innerhandle))) {
				if ($innerentry != "." && $innerentry != "..") {
					if(strtolower(substr($innerentry,-4)) == ".jpg" || strtolower(substr($innerentry,-4)) == ".png"){
						$newimgproc=$imgproctable->newEntity();
						$newimgproc->original_path = $_SERVER['DOCUMENT_ROOT'].'/webroot/img/library/'.$innerentry;
						$newimgproc->status='unprocessed';
						$imgproctable->save($newimgproc);
					}
				}
				
			}
		}
		
	}
	
	public function testtinify(){
		$this->autoRender=false;
		$imgproctable=TableRegistry::get('Imageprocessing');
		
		\Tinify\setKey("htQf9qrLgk4CPrzVv3VMlQGLrvr5HL7m");
		$procimg=$this->Imageprocessing->find('all',['conditions' => ['status' => 'unprocessed']])->limit(25)->toArray();
		if(count($procimg) == 0){
			echo "DONE";exit;
		}else{
			foreach($procimg as $img){
				$source = \Tinify\fromFile($img['original_path']);
				$ext=substr($img['original_path'],-4);
				if(strtolower($ext) == ".jpg"){
					$newfile=str_replace($ext,"_new".$ext,$img['original_path']);
					$oldname=str_replace($ext,"_old".$ext,$img['original_path']);
				}
				$source->toFile($newfile);
				$thisImg=$imgproctable->get($img['id']);
				$thisImg->status='processed';
				$thisImg->processedtime=time();
				$thisImg->new_path=$newfile;
				$thisImg->old_file_new_path = $oldname;
				$imgproctable->save($thisImg);

				rename($img['original_path'],$oldname);
				rename($newfile,$img['original_path']);

			}

			echo "<script>setTimeout('window.location.reload(true)',5000);</script>";
		}
		
	}
	
	
	public function fiximported(){
	    $this->autoRender=false;
	    $usedDates=array();
	    
	    $statuses=$this->OrderItemStatus->find('all')->toArray();
	    foreach($statuses as $status){
	        $thisOrderItem=$this->OrderItems->get($status['order_item_id'])->toArray();
	        $thisQuoteItem=$this->QuoteLineItems->get($thisOrderItem['quote_line_item_id'])->toArray();
	        
			$fixedDate=date('Y-m-d',$status['time']);
			
			if(!in_array($fixedDate,$usedDates)){
				$usedDates[]=$fixedDate;
			}
	        
	        echo "UPDATE `order_item_status` SET `qty_involved`='".$thisQuoteItem['qty']."' WHERE `id`='".$status['id']."';<br>";
	    }
	    echo "<hr>";
	    foreach($usedDates as $date){
			echo "<a href=\"/orders/updatesherrycachefordate/".$date."/true/\" target=\"_blank\">Cache Sherry Date ".$date."</a><br>";
		}
	    
	}
	
	
	public function auditallorders($orderID=false,$verbose=false){
	    $this->autoRender=false;

	    if($orderID){
	    	$this->auditOrderItemStatuses($orderID,$verbose);
			echo "Audited order <B>".$orderID."</B><br>";
	    }else{
			$allOrders=$this->Orders->find('all')->toArray();
			foreach($allOrders as $order){

				$this->auditOrderItemStatuses($order['id'],$verbose);
				
			}	
		}
	}


	public function rttt(){
		$this->autoRender=false;
		$start=1528606800;
		$startDate=date("Y-m-d",$start);
		for($i=1; $i <= 50; $i++){
			$thisDate=date("Y-m-d",($start+($i * 86400)));
			echo "<a href=\"/orders/updatesherrycachefordate/".$thisDate."\">".$thisDate."</a><br>";
		}
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
	
	
	
	
	
	

	public function updatesherrycachealldates(){
		$this->autoRender=false;
		$allDates=$this->SherryBatches->find('all')->toArray();
		$usedDates=array();
		foreach($allDates as $batch){
			if(!in_array($batch['date'],$usedDates)){
				echo "<a href=\"/orders/updatesherrycachefordate/".$batch['date']."\">".$batch['date']."</a><br>";
				$usedDates[]=$batch['date'];
			}
		}
	}


	public function testthumb($imgid){
		$this->autoRender=false;

		$imageData=$this->LibraryImages->get($imgid)->toArray();

		$imagePath=$_SERVER['DOCUMENT_ROOT']."/webroot/img/library/".$imageData['filename'];

		list($width, $height, $type, $attr) = getimagesize($imagePath);

		if($height > 600){
			$height=600;
		}

	    echo "<img style=\"max-width:600px;height:auto;\" src=\"/img/mthumb.php?src=".urlencode($imagePath)."&w=".round(($height*2.5))."&h=".$height."&zc=2\" />";
    	exit;
	}


	public function nov2018sync(){
		$this->autoRender=false;
		//loop through all order items and make sure their corresponding "Not Started" status entries exist
		$allOrders=$this->Orders->find('all')->toArray();
		foreach($allOrders as $order){

			$orderLineItems=$this->OrderItems->find('all',['conditions'=>['order_id' => $order['id']]])->toArray();
			foreach($orderLineItems as $orderLineItem){
				
				$quoteLineItem=$this->QuoteLineItems->get($orderLineItem['quote_line_item_id'])->toArray();

				$orderItemStatus=$this->OrderItemStatus->find('all',['conditions' => ['order_item_id' => $orderLineItem['id']]])->toArray();
				if(count($orderItemStatus) == 0){
					//insert it!
					echo "INSERT INTO `order_item_status` (`order_line_number`,`order_item_id`,`time`,`status`,`user_id`,`qty_involved`,`work_order_id`,`sherry_batch_id`,`shipment_id`,`parent_status_id`) VALUES ('".$quoteLineItem['line_number']."','".$orderLineItem['id']."','".$order['created']."','Not Started','".$order['user_id']."','".$quoteLineItem['qty']."','".$order['id']."',NULL,'0','0');<br>";
				}
			}

		}


	}


	
	public function pingautofy(){
		$this->autoRender=false;
		
		$ch = curl_init();
		$url='https://api.propelware.com/v1/manage/6393HBTYI17UUISN4JU8CYHI0DYG/IsActive';

	    curl_setopt($ch, CURLOPT_AUTOREFERER, TRUE);
	    curl_setopt($ch, CURLOPT_HEADER, 0);
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	    curl_setopt($ch, CURLOPT_URL, $url);
	    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);       

	    $data = curl_exec($ch);
	    curl_close($ch);

	    echo $data;
	    
	}


	/*public function createautofyapiuser(){
		$this->autoRender=false;
		$data = array(
			"agentToken" => "6393HBTYI17UUISN4JU8CYHI0DYG",
			"companyId" => "",
			"dateTimeCreated" => "",
			"externalId" => "",
			"id" => "",
			"isActive" => true
		);

		$data_string = json_encode($data);

		$ch = curl_init('https://api.propelware.com/v1/manage/user');
		
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: 4992FD913BF74CC5A8D89FEBA11B162F','Content-Type: application/json'));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		
		$result = curl_exec($ch);

		echo $result;
	}
	*/

	/*{"Meta":{"CommandGuid":null,"StatusCode":0,"Severity":null,"Message":"Success"},"user":{"agentToken":"O3FG0VFI321JEOATQNFRWA6SSQSU","companyId":"","dateTimeCreated":"2018-12-28T21:58:44.0995278+00:00","externalId":"","id":"2e8986b5923e428c811b037b04138183","isActive":true}}*/

	/*
	public function createautofycompany(){

		//2e8986b5923e428c811b037b04138183

		$this->autoRender=false;
		$data = array(
			"companyName" => "Health Care Interiors",
			"dateTimeCreated" => "",
			"dateTimeLastAccess" => "",
			"fullPath" => "",
			"id" => "",
			"isActive" => true,
			"userId" => "2e8986b5923e428c811b037b04138183",
			"externalId" => "",
			"endpoint" => "qbd",
			"endpointId" => ""
		);

		$data_string = json_encode($data);

		$ch = curl_init('https://api.propelware.com/v1/manage/company');
		
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: 4992FD913BF74CC5A8D89FEBA11B162F','Content-Type: application/json'));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		
		$result = curl_exec($ch);

		echo $result;
	}
	*/

	/*{"Meta":{"CommandGuid":null,"StatusCode":0,"Severity":null,"Message":null},"company":{"companyName":"Health Care Interiors","dateTimeCreated":"2018-12-28T22:15:32.9250629+00:00","dateTimeLastAccess":"2018-12-28T22:15:32.9406871+00:00","fullPath":"C:\\Users\\mpells\\Downloads\\Healthcare Interiors, Ltd v17.QBW","id":"b6101ecfed9b49fab6cca13407e58f40","isActive":true,"userId":"2e8986b5923e428c811b037b04138183","externalId":"","endpoint":"qbd","endpointId":"53ca6f5b097a415490fa346a8f2eb066"}}*/

	public function autofytestso(){
		$this->autoRender=false;
		$data = array(
			"RefNumber" => "55561",
			"PONumber" => "PO 55553335",
			"DueDate" => "2019-01-15T18:00:00",
			"TxnDate" => "2018-12-31T10:00:00",
			"DateShipped" => "2019-01-15",
			"SubTotal" => 325.00,
			"LineItems" => array(
				array(
					"Item" => array(
						"Name" => "Cubicle Curtain A",
						"ItemCost" => 0,
						"ItemPrice" => 80.00
					),
					"Rate" => 80.00,
					"Amount" => 160.00,
					"InvDesc" => "TEST1",
					"Description" => "TEST LINE 1",
					"Quantity" => 2,
					"IsTaxable" => false
				),
				array(
					"Item" => array(
						"Name" => "Bedspread A",
						"ItemCost" => 0,
						"ItemPrice" => 55.00
					),
					"Rate" => 55.00,
					"InvDesc" => "TEST2",
					"Amount" => 165.00,
					"Description" => "TEST LINE 2",
					"Quantity" => 3,
					"IsTaxable" => false
				),
			),
			"TermsRef" => array(
				"ID" => "",
				"FullName" => "Net 30",
			),
			"Customer" => array(
				"Name" => "Test Company ABC",
				"FullName" => "Test Company ABC",
				"Contact" => "Darren Batchelder",
				"Phone" => "9724182000",
				"BillAddress" => array(
					"Addr1" => "PO Box 112121",
					"City" => "Carrollton",
					"State" => "TX",
					"PostalCode" => "75011",
					"Country" => "US"
				),
				"ShipAddress" => array(
					"Addr1" => "2121 N Josey Lane",
					"Addr2" => "Suite 102",
					"City" => "Carrollton",
					"State" => "TX",
					"PostalCode" => "75006",
					"Country" => "US"
				)
			)
		);

		$data_string = json_encode($data);

		//echo $data_string;exit;

		$ch = curl_init('https://api.propelware.com/v1/endpoint/53ca6f5b097a415490fa346a8f2eb066/process/SalesOrder');
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array( 
			'Authorization: 4992FD913BF74CC5A8D89FEBA11B162F',        
		    'Content-Type: application/json')
		);

		$result = curl_exec($ch);

		echo $result;
	}


	public function autofytestgetitems(){
		$this->autoRender=false;
		
		$ch = curl_init('https://api.propelware.com/v1/endpoint/53ca6f5b097a415490fa346a8f2eb066/select/Item?page=1&pageSize=100');
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
		//curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array( 
			'Authorization: 4992FD913BF74CC5A8D89FEBA11B162F',        
		    'Content-Type: application/json')
		);

		$result = curl_exec($ch);

		echo "<pre>";
		print_r(json_decode($result,true));
		echo "</pre>";
	}


	public function fixfacilitycache(){
		$this->autoRender=false;
		$sql='';
		$allCache=$this->SherryCache->find('all')->toArray();
		foreach($allCache as $cacheRow){
			$thisOrder=$this->Orders->get($cacheRow['order_id'])->toArray();
			if($thisOrder['facility'] != $cacheRow['facility']){
				echo "Mismatch on batch <b>".$cacheRow['batch_id']."</b><br>";
				$sql .= "UPDATE `sherry_cache` SET `facility`='".str_replace("'","\'",$thisOrder['facility'])."' WHERE `id`='".$cacheRow['id']."';<br>";
			}
		}

		echo "<hr><pre>".$sql."</pre>";
	}


    public function dddtest($date){
        $this->autoRender=false;
        
        $this->updatesherrycachefordate($date);
        echo "Updated sherry cache for date ".$date."<br>";
        
        exit;
    }

    
    public function fabricselectortest(){
        
    }
    
    
    public function testbitly(){
        $this->autoRender=false;
        print_r($this->getbitlyurl('https://orders.hcinteriors.com/products/inventory/1935'));
    }

    
    public function testcopyfile(){
        $this->autoRender=false;
        
        
        $url = "https://orders.hcinteriors.com/img/mthumb.php?ct=1&src=%2Fhome%2Fhciorders%2Fpublic_html%2Fwebroot%2Fimg%2Flibrary%2Foriginal_1635523240_7.png&w=1360&h=544&zc=2";
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HEADER, false);
        $data = curl_exec($curl);
        curl_close($curl);
        
        @unlink("/home/hciorders/public_html/webroot/img/library/1635523240_7.png");
        
        $newfile=fopen("/home/hciorders/public_html/webroot/img/library/1635523240_7.png","w");
        fwrite($newfile,$data,strlen($data));
        fclose($newfile);
        
            
    }
    
    
    public function testunsched(){
        $this->autoRender=false;
        $db=ConnectionManager::get('default');
        
        $searchwhere=" AND `order_number`='92668'";
		
        
        $overallquery="SELECT a.id,a.status,a.facility,a.customer_id,a.created,a.shipping_method_id,a.order_number,a.due,a.po_number,a.sherry_status, c.type,c.status,c.product_type,c.product_id,c.qty,c.line_number,c.calculator_used, d.company_name FROM orders a, order_items b, quote_line_items c, customers d WHERE (a.status NOT IN ('Canceled','Shipped') AND d.id=a.customer_id AND b.order_id=a.id AND c.id=b.quote_line_item_id AND (a.sherry_status = 'Partially Scheduled' OR a.sherry_status = 'Not Scheduled'))".$searchwhere." GROUP BY a.id";
		
		$query="SELECT a.id,a.status,a.facility,a.customer_id,a.created,a.shipping_method_id,a.order_number,a.due,a.po_number,a.sherry_status, c.type,c.status,c.product_type,c.product_id,c.qty,c.line_number,c.calculator_used, d.company_name FROM orders a, order_items b, quote_line_items c, customers d WHERE a.status NOT IN ('Canceled','Shipped') AND d.id=a.customer_id AND b.order_id=a.id AND c.id=b.quote_line_item_id AND (a.sherry_status = 'Partially Scheduled' OR a.sherry_status = 'Not Scheduled')".$searchwhere." GROUP BY a.id";

		
		$queryRun=$db->execute($query);
		$unscheduledOrders=$queryRun->fetchAll('assoc');
		$grouped=array();

		$overallQueryRun=$db->execute($overallquery);
		$overallUnscheduledOrders=$overallQueryRun->fetchAll('assoc');

		foreach($unscheduledOrders as $order){

			$orderScheduledDiff=0;
			
			

			$outstandingOrderItems=array();
			$orderItems=$this->OrderItems->find('all',['conditions'=>['order_id' => $order['id']]])->toArray();

			foreach($orderItems as $orderLineItem){

				$itemTotalDiff=0;
				$itemTotalLF=0;

				$thisQuoteItem=$this->QuoteLineItems->get($orderLineItem['quote_line_item_id'])->toArray();
				$itemMetas=$this->QuoteLineItemMeta->find('all',['conditions'=>['quote_item_id' => $thisQuoteItem['id']]])->toArray();

				if($thisQuoteItem['parent_line'] == 0){

					foreach($itemMetas as $itemMetaRow){
						if($itemMetaRow['meta_key'] == 'difficulty-rating'){
							$itemTotalDiff = ($itemTotalDiff + floatval($itemMetaRow['meta_value']));
						}
						if($itemMetaRow['meta_key'] == 'labor-billable'){
							$itemTotalLF = ($itemTotalLF + (floatval($itemMetaRow['meta_value']) * floatval($thisQuoteItem['qty'])));
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
									$itemScheduledLF = floatval($itemMetaRow['meta_value']);
								}
							}
							
						}

					}

					switch($thisQuoteItem['product_type']){
						case "window_treatments":
							
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
									$thisitemtype='wt';
								break;
								case "pinch-pleated":
									$thisitemtype='drape';
								break;
								case "straight-cornice":
									$thisitemtype='wt';
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

		print_r($grouped);exit;

		foreach($grouped as $orderid => $orderdata){
			$addclasses='';
			
			$projectname='';
			$totals='';
			$cc_qty_unscheduled=0;
			$cc_lf_unscheduled=0;
			$cc_diff_unscheduled=0;
			$trk_lf_unscheduled=0;
			$bs_qty_unscheduled=0;
			$bs_diff_unscheduled=0;
			$drape_qty_unscheduled=0;
			$drape_widths_unscheduled=0;
			$drape_diff_unscheduled=0;
			$wt_qty_unscheduled=0;
			$wt_lf_unscheduled=0;
			$wt_diff_unscheduled=0;
			$wthw_qty_unscheduled=0;
			$blinds_qty_unscheduled=0;
			
			foreach($orderdata['linesStatus'] as $lineItemID => $thisrowtotals){
				$lineNumber=$thisrowtotals['lineNumber'];
				switch($thisrowtotals['itemType']){
					case "cc":
						$cc_qty_unscheduled = ($cc_qty_unscheduled + $thisrowtotals['unscheduled']);
						$cc_lf_unscheduled = ($cc_lf_unscheduled + $thisrowtotals['unscheduled_lf'] );
						$cc_diff_unscheduled = ($cc_diff_unscheduled + $thisrowtotals['unscheduled_diff']);
					break;
					case "wt":
						$wt_qty_unscheduled = ($wt_qty_unscheduled + $thisrowtotals['unscheduled']);
						$wt_diff_unscheduled = ($wt_diff_unscheduled + $thisrowtotals['unscheduled_diff']);
					break;
					case "drape":
						$drape_qty_unscheduled = ($drape_qty_unscheduled + $thisrowtotals['unscheduled']);
						$drape_diff_unscheduled = ($drape_diff_unscheduled + $thisrowtotals['unscheduled_diff']);
					break;
					case "bs":
						$bs_qty_unscheduled = ($bs_qty_unscheduled + $thisrowtotals['unscheduled']);
						$bs_diff_unscheduled = ($bs_diff_unscheduled + $thisrowtotals['unscheduled_diff']);
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
			

			$orders[]=array(
				'DT_RowId'=>'row_'.$order['id'],
				'DT_RowClass'=>'rowtest needlineitems'.$addclasses,
				'0' => '<a href="/orders/vieworderschedule/'.$orderid.'"><img src="/img/view.png" title="Batch Breakdown" alt="Batch Breakdown" /></a> <a href="/orders/scheduleorder/'.$orderid.'"><img src="/img/calendar.png" title="New Batch" alt="New Batch" /></a>',
				'1' => "<a href=\"/orders/editlines/".$orderid."/\" target=\"_blank\">".$orderdata['order_number']."</a>",
				'2' => $orderdata['company_name'],
				'3' => $orderdata['po_number'],
				'4' => date('n/j/Y',$orderdata['created']),
				'5' => $projectname,
				'6' => $orderdata['facility'],
				'7' => $duedate,
				'8' => ($cc_qty_unscheduled > 0 ? $cc_qty_unscheduled : ''),
				'9' => ($cc_lf_unscheduled > 0 ? $cc_lf_unscheduled : ''),
				'10' => ($cc_diff_unscheduled > 0 ? $cc_diff_unscheduled : ''),
				'11' => ($trk_lf_unscheduled > 0 ? $trk_lf_unscheduled : ''),
				'12' => ($bs_qty_unscheduled > 0 ? $bs_qty_unscheduled : ''),
				'13' => ($bs_diff_unscheduled > 0 ? $bs_diff_unscheduled : ''),
				'14' => ($drape_qty_unscheduled > 0 ? $drape_qty_unscheduled : ''),
				'15' => ($drape_widths_unscheduled > 0 ? $drape_widths_unscheduled : ''),
				'16' => ($drape_diff_unscheduled > 0 ? $drape_diff_unscheduled : ''),
				'17' => ($wt_qty_unscheduled > 0 ? $wt_qty_unscheduled : ''),
				'18' => ($wt_lf_unscheduled > 0 ? $wt_lf_unscheduled : ''),
				'19' => ($wt_diff_unscheduled > 0 ? $wt_diff_unscheduled : ''),
				'20' => ($wthw_qty_unscheduled > 0 ? $wthw_qty_unscheduled : ''),
				'21' => ($blinds_qty_unscheduled > 0 ? $blinds_qty_unscheduled : '')
			);

		}
        
    }


    public function fixsampleorders(){
        $this->autoRender=false;
        
        /*
        $allOrders=array(896,914,962,1356,1685,1763,2158,2799,3490,3510,3834,3837,4236,4953,5510,5511,5559,5575,6555,6677,6993,7376,7380,7789,8017,8501,8762,8812,8921,9194,9202,9403,9829,9891,10213,11931,12076,12445,13302,14343,15120,15524,15622,15624,15626,15627,15629,15632,15637,15701,15703,15704,15742,15763,15771,15772,15774,15823,15850,15855,15866,15893,15894,15932,15947,15972,15973,15981,16007,16029,16034,16038);
        $lookupBatches=$this->OrderItemStatus->find('all',['conditions'=>['work_order_id IN' => $allOrders, 'sherry_batch_id >' => 0, 'status' => 'Scheduled']])->toArray();
        
        foreach($lookupBatches as $batch){
            
            //look for any INVOICED status rows for this batch id
            $alreadyInvoiced=$this->OrderItemStatus->find('all',['conditions'=>['sherry_batch_id'=>$batch['sherry_batch_id'],'status'=>'Invoiced']])->toArray();
            if(count($alreadyInvoiced) == 0){
                
                //needs to be added as INVOICED
                echo "INSERT INTO `order_item_status` SET `order_line_number`='".$batch['order_line_number']."', `order_item_id`='".$batch['order_item_id']."', `time`='1640296800', `status`='Invoiced', `user_id`='".$batch['user_id']."', `qty_involved`='".$batch['qty_involved']."', `work_order_id`='".$batch['work_order_id']."', `sherry_batch_id`='".$batch['sherry_batch_id']."', `shipment_id`='0', `parent_status_id`='0', `invoice_number`='999999999';<br>";
                
            }
            
            
            $thisBatch=$this->SherryBatches->get($batch['sherry_batch_id'])->toArray();
            
            
            
        }
        */
        
        $ll=$this->OrderItemStatus->find('all',['conditions' => ['id >' => 147951]])->toArray();
        foreach($ll as $f){
            //echo "UPDATE `sherry_batches` SET `invoiced_date`='1640296800' WHERE `id`='".$f['id']."';<br>";
            echo "UPDATE `sherry_cache` SET `batch_invoiced_date`='1640296800' WHERE `batch_id`='".$f['sherry_batch_id']."';<br>";
        }
        
        
    }


    public function convertoldlinesnewcatchalls(){
        $this->autoRender=false;
        $all=$this->Catchallfixes->find('all')->toArray();
        foreach($all as $row){
            echo "UPDATE `quote_line_items` SET `product_type`='".$row['product_type']."', `product_class`='".$row['product_class']."', `product_subclass`='".$row['product_subclass']."' WHERE `id` = '".$row['line_item_id']."';<br>";
        }
    }


    
    public function fixoldcatchallimagest(){
        $this->autoRender=false;
        $all=$this->Catchallfixes->find('all')->toArray();
        foreach($all as $row){
            //check to see if the image_method meta exists
            $look=$this->QuoteLineItemMeta->find('all',['conditions' => ['quote_item_id' => $row['line_item_id'], 'meta_key' => 'image_method']])->toArray();
            if(count($look) == 0){
                //see if "libraryimageid" meta is set
                $lookTwo=$this->QuoteLineItemMeta->find('all',['conditions' => ['quote_item_id' => $row['line_item_id'], 'meta_key' => 'libraryimageid']])->toArray();
                if(count($lookTwo) == 1){
                    //update the meta or insert it
                    echo "SET line item id# <b>".$row['line_item_id']."</b> meta image_method to <b>library</b><br>";
                    
                    $this->updateLineItemMeta($row['line_item_id'],'image_method','library');
                    
                }else{
                    echo "SET line item id# <b>".$row['line_item_id']."</b> meta image_method to <b>none</b><br>";
                    
                    $this->updateLineItemMeta($row['line_item_id'],'image_method','none');
                }
            }
        }
    }



    public function sherrytestfive($date){
        $this->autoRender=false;
        $sherryCacheTable=TableRegistry::get('SherryCache');

    	$sherryBatchesLookup=$this->SherryBatches->find('all',['conditions'=>['date'=>$date]])->toArray();
    	foreach($sherryBatchesLookup as $batch){

    		$thisOrder=$this->Orders->get($batch['work_order_id'])->toArray();
    		$thisCustomer=$this->Customers->get($thisOrder['customer_id'])->toArray();
    		$thisQuote=$this->Quotes->get($thisOrder['quote_id'])->toArray();


			$thisOrderTotals=array(
				'dollars' => 0,
				'cc' => 0,
				'cclf' => 0,
				'bs' => 0,
				'wt' => 0,
				'wtlf' => 0,
				'drape' => 0,
				'drape_widths' => 0,
				'wthw' => 0,
				'blinds' => 0,
				'trklf' => 0
			);

			$thisOrderThisBatchItems=array();


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
							case "newcatchall-cornice":
							case "newcatchall-swtmisc":
							    $thisOrderTotals['wt'] = ($thisOrderTotals['wt'] + $status['qty_involved']);
								$thisOrderTotals['wtlf'] = ($thisOrderTotals['wtlf'] + (floatval($itemMeta['labor-billable']) * $status['qty_involved']));
							break;
							case "window_treatments":
								$thisitemtypelabel=$itemMeta['wttype'];
								switch($itemMeta['wttype']){
									case "Pinch Pleated Drapery":
										$thisOrderTotals['drape'] = ($thisOrderTotals['drape'] + $status['qty_involved']);
										$thisOrderTotals['drape_widths'] = ($thisOrderTotals['drape_widths'] + (floatval($itemMeta['labor-widths']) * $status['qty_involved']));
									break;
									default:
										$thisOrderTotals['wt'] = ($thisOrderTotals['wt'] + $status['qty_involved']);
										$thisOrderTotals['wtlf'] = ($thisOrderTotals['wtlf'] + (floatval($itemMeta['labor-billable']) * $status['qty_involved']));
									break;
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
										$thisOrderTotals['wt'] = ($thisOrderTotals['wt'] + $status['qty_involved']);
										$thisOrderTotals['wtlf'] = ($thisOrderTotals['wtlf'] + (floatval($itemMeta['labor-billable']) * $status['qty_involved']));
									break;
									case "straight-cornice":
										$thisOrderTotals['wt'] = ($thisOrderTotals['wt'] + $status['qty_involved']);
										$thisOrderTotals['wtlf'] = ($thisOrderTotals['wtlf'] + (floatval($itemMeta['labor-billable']) * $status['qty_involved']));
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


			
			if(count($thisOrderThisBatchItems) >0){
				//perform the update/insert
		
				//let's see if a cache entry already exists for this date
				echo "There appears to be items in batch ".$batch['id']."<br>";
				
			}else{
				//let's delete this batch entry in sherry schedule cache, it has no items anymore
				echo "Delete batch ".$batch['id']." from cache because there's zero items<br>";
				
			}



    	}
        
    }
    
    
    public function tttwww($date){
        $this->autoRender=false;
        $sherryBatchesLookup=$this->SherryBatches->find('all',['conditions'=>['date'=>$date]])->toArray();
    	foreach($sherryBatchesLookup as $batch){
    	    $thisOrder=$this->Orders->get($batch['work_order_id'])->toArray();
    	    if(!is_null($thisOrder['type_id'])){
			    $thisOrderType=$this->QuoteTypes->find('all',['conditions' => ['id' => $thisOrder['type_id']]])->toArray();
			    if(count($thisOrderType) == 0){
			        echo "Cannot find order type id ".$thisOrder['type_id']." for order ".$thisOrder['id']."<br>";
			    }
			    
			}
    	}
    }


    public function iijjkk(){
        $this->autoRender=false;
        $badOI=$this->OrderItemStatus->find('all',['conditions' => ['order_item_id' => 0, 'status' => 'Scheduled']])->toArray();
        
        foreach($badOI as $bad){
            echo "<pre>"; print_r($bad); echo "</pre><hr>";
        }
    }

    
    
    public function ticket637962run(){
        $this->autoRender=false;
        
        /*
        //copy the CALC items
        $calcfixes=$this->TmpCalculatorClassFixes->find('all')->toArray();
        foreach($calcfixes as $calcfix){
            //check if it is already set
            $liveRow=$this->QuoteLineItems->get($calcfix['id'])->toArray();
            if($liveRow['product_class'] != $calcfix['product_class'] || $liveRow['product_subclass'] != $calcfix['product_subclass']){
                echo "UPDATE `quote_line_items` SET `product_class`='".$calcfix['product_class']."', `product_subclass`='".$calcfix['product_subclass']."' WHERE `id`='".$calcfix['id']."';<br>";
            }
        }
        
        //copy the SERVICE items
        $servicefixes=$this->TmpServicesClassFixes->find('all')->toArray();
        foreach($servicefixes as $servfix){
            //check if it is already set
            $liveRow=$this->QuoteLineItems->get($servfix['id'])->toArray();
            if($liveRow['product_class'] != $servfix['product_class'] || $liveRow['product_subclass'] != $servfix['product_subclass']){
                echo "UPDATE `quote_line_items` SET `product_class`='".$servfix['product_class']."', `product_subclass`='".$servfix['product_subclass']."' WHERE `id`='".$servfix['id']."';<br>";
            }
        }
        
        
        
        //part 2
        $ccfixes=$this->QuoteLineItems->find('all',['conditions' => ['product_type' => 'cubicle_curtains', 'product_class' => 8, 'product_subclass' => 25]])->toArray();
        foreach($ccfixes as $cc){
            echo "UPDATE `quote_line_items` SET `product_class`='4', `product_subclass`='13' WHERE `id`='".$cc['id']."';<br>";
        }
        //part 3
        $bsfixes=$this->QuoteLineItems->find('all',['conditions' => ['product_type' => 'bedspreads', 'product_class' => 8, 'product_subclass' => 25]])->toArray();
        foreach($bsfixes as $bs){
            echo "UPDATE `quote_line_items` SET `product_class`='5', `product_subclass`='16' WHERE `id`='".$bs['id']."';<br>";
        }
        
        
        //part 4
        $trackfixes=$this->QuoteLineItems->find('all',['conditions' => ['product_type' => 'track_systems', 'product_class' => 8, 'product_subclass' => 25]])->toArray();
        foreach($trackfixes as $track){
            echo "UPDATE `quote_line_items` SET `product_class`='1', `product_subclass`='3' WHERE `id`='".$track['id']."';<br>";
        }
        */
        
    }


    
    public function orderitemsmarkedinvoicedbutordernotcomplete(){
        $this->autoRender=false;
        
        $invoicedLinesFromIncompleteOrders=array();
        
        $orders=$this->Orders->find('all',['conditions' => ['status NOT IN' => ['Canceled','Shipped']]])->toArray();
        
        foreach($orders as $order){
            
            $items=$this->OrderItems->find('all',['conditions' => ['order_id' => $order['id']]])->toArray();
            
            foreach($items as $item){
                
                //see if it's been marked as Invoiced on sherry
                $checkinvoiced=$this->OrderItemStatus->find('all',['conditions' => ['order_item_id' => $item['id'], 'status' => 'Invoiced']])->toArray();
                
                
                if(count($checkinvoiced) > 0){
                    
                    $quoteItem=$this->QuoteLineItems->get($item['quote_line_item_id'])->toArray();
                
                    foreach($checkinvoiced as $statusrow){
                        $invoicedLinesFromIncompleteOrders[]=array('order_item_id' => $item['id'],
                        'order_number' => $order['order_number'],
                        'quote_line_item_id' => $item['quote_line_item_id'], 'line_number' => $statusrow['order_line_number'], 'batch_id' => $statusrow['sherry_batch_id'], 'qty_this_batch' => $statusrow['qty_involved']);
                    }
                }
                
            }
            
        }
        
        
        echo "<table border=\"1\"><thead><tr><th>Order Item ID</th>
        <th>WO#</th>
        <th>Quote Line Item ID</th>
        <th>Line Number</th>
        <th>Batch ID</th>
        <th>QTY This Batch</th>
        </tr>
        </thead><tbody>";
        foreach($invoicedLinesFromIncompleteOrders as $res){
            echo "<tr>
            <td>".$res['order_item_id']."</td>
            <td>".$res['order_number']."</td>
            <td>".$res['quote_line_item_id']."</td>
            <td>".$res['line_number']."</td>
            <td>".$res['batch_id']."</td>
            <td>".$res['qty_this_batch']."</td>
            </tr>";
        }
        echo "</tbody></table>";
    }


    
    public function findmismatchfabricnames(){
        $this->autoRender=false;
        $mismatches=array();
        
        $allQuotes=$this->Quotes->find('all')->toArray();
        foreach($allQuotes as $quote){
            $items=$this->QuoteLineItems->find('all',['conditions' => ['quote_id' => $quote['id']]])->toArray();
            
            foreach($items as $item){
                
                //lookup fabricid meta
                $metalookup=$this->QuoteLineItemMeta->find('all',['conditions' => ['quote_item_id' => $item['id'], 'meta_key' => 'fabricid']])->toArray();
                
                if(count($metalookup) > 0){
                    
                    foreach($metalookup as $metarow){
                        
                        $thisFabric=$this->Fabrics->get($metarow['meta_value'])->toArray();
                        
                        if(!preg_match("#".$thisFabric['fabric_name']."#i",$item['title'])){
                            //see if this fabric has an Alias for this customer
                            
                            $aliaslookup=$this->FabricAliases->find('all',['conditions' => ['fabric_id' => $thisFabric['id'], 'customer_id' => $quote['customer_id']]])->toArray();
                            
                            if(count($aliaslookup) == 0){
                                //this is a mismatch
                                $mismatches[]=array(
                                    'quote_id' => $quote['id'],
                                    'quote_item_id' => $item['id'],
                                    'line_number' => $item['line_number'],
                                    'fabric_id' => $thisFabric['id'],
                                    'title' => $item['title'],
                                    'description' => $item['description'],
                                    'alias' => false
                                );
                            }else{
                                foreach($aliaslookup as $alias){
                                    if(!preg_match("#".$alias['fabric_name']."#i",$item['title'])){
                                        //this is a mismatch
                                        $mismatches[]=array(
                                            'quote_id' => $quote['id'],
                                            'quote_item_id' => $item['id'],
                                            'line_number' => $item['line_number'],
                                            'fabric_id' => $thisFabric['id'],
                                            'title' => $item['title'],
                                            'description' => $item['description'],
                                            'alias' => false
                                        );
                                    }
                                }
                            }
                            
                        }
                        
                    }
                    
                }
                
            }
        }
        
        echo "<pre>";
        print_r($mismatches);
        echo "</pre>";
        
    }


    public function fixswtmiscqtyfieldfororders(){
        $this->autoRender=false;
        
        $orders=$this->Orders->find('all')->toArray();
        foreach($orders as $order){
            
            $newcount=$this->getTypeQty($order['quote_id'],'swtmisc');
            
            if($newcount != $order['swtmisc_qty']){
                echo "UPDATE `orders` SET `swtmisc_qty`='".$newcount."' WHERE `id`='".$order['id']."';<br>";
            }
            
        }
        
    }
    
    
    public function fixwrongcatchalldefaultfabricnames(){
        $this->autoRender=false;
        $metas=$this->QuoteLineItemMeta->find('all',['conditions' => ['meta_key' => 'fabricname', 'meta_value' => '+Transmit+SD0064']])->toArray();
        
        $failed=0;
        
        //echo "<pre>"; print_r($metas); echo "</pre>";
        foreach($metas as $meta){
            $lookupFabID=$this->QuoteLineItemMeta->find('all',['conditions' => ['meta_key' => 'fabric_id_with_color','quote_item_id' => $meta['quote_item_id'] ]])->toArray();
            if(count($lookupFabID) == 0){
                $failed++;
            }else{
                foreach($lookupFabID as $fabMeta){
                    $thisFabric=$this->Fabrics->get($fabMeta['meta_value'])->toArray();
                    echo "UPDATE `quote_line_item_meta` SET `meta_value`='".$thisFabric['fabric_name']."' WHERE `meta_key`='fabricname' AND `quote_item_id`='".$meta['quote_item_id']."';<br>";
                }
            }
        }
        
        echo "<hr>FAILED=".$failed;
        
    }


    public function testggg(){
        $this->autoRender=false;
        /*echo "<pre>";
        print_r($this->getorderscheduleditems(15859));
        echo "</pre>";
        exit;
        */
        $orderID=15859;
        echo "<pre>";
        $allBatches=$this->SherryBatches->find('all',['conditions'=>['work_order_id' => $orderID],'order'=>['date'=>'asc']])->toArray();
        print_r($allBatches);
        echo "</pre><hr>";
    }
    
    
    
    public function checkduporderquoteids(){
        $this->autoRender=false;
        echo "<pre>";
        $orderItemsUnique=$this->OrderItems->find('all')->group('quote_line_item_id')->toArray();
        foreach($orderItemsUnique as $item){
            
            $secondCheck=$this->OrderItems->find('all',['conditions' => ['quote_line_item_id' => $item['quote_line_item_id']]])->toArray();
            if(count($secondCheck) > 1){
                echo $item['quote_line_item_id']."\n";
            }
            
        }
        echo "</pre>";
        exit;
    }
    
    
    
    public function fixcornqty(){
        $this->autoRender=false;
        $orderID=15817;
        $thisOrder=$this->Orders->get($orderID)->toArray();
        
        $totals=array(
            'cc_qty' => 0,
            'bs_qty' => 0,
            'drape_qty' => 0,
            'val_qty' => 0,
            'corn_qty' => 0,
            'wt_hw_qty' => 0,
            'blinds_qty' => 0,
            'catchall_qty' => 0,
            'swtmisc_qty' => 0
        );
        
        $items=$this->QuoteLineItems->find('all',['conditions' => ['quote_id' => $thisOrder['quote_id']],'order' => ['sortorder' => 'asc']])->toArray();
        foreach($items as $item){
            
            switch($item['product_type']){
                case 'newcatchall-drapery':
                    $totals['drape_qty'] = ($totals['drape_qty'] + $item['qty']);
                break;
                case 'newcatchall-cornice':
                    $totals['corn_qty'] = ($totals['corn_qty'] + $item['qty']);
                break;
                case 'newcatchall-valance':
                    $totals['val_qty'] = ($totals['val_qty'] + $item['qty']);
                break;
                case 'newcatchall-bedding':
                case 'bedspreads':
                    $totals['bs_qty'] = ($totals['bs_qty'] + $item['qty']);
                break;
                case 'newcatchall-cubicle':
                case 'cubicle_curtains':
                    $totals['cc_qty'] = ($totals['cc_qty'] + $item['qty']);
                break;
                case 'calculator':
                    switch($item['calculator_used']){
                        case 'cubicle-curtain':
                            $totals['cc_qty'] = ($totals['cc_qty'] + $item['qty']);
                        break;
                        case 'bedspread':
                            $totals['bs_qty'] = ($totals['bs_qty'] + $item['qty']);
                        break;
                        case 'box-pleated':
                            $totals['val_qty'] = ($totals['val_qty'] + $item['qty']);
                        break;
                        case 'straight-cornice':
                            $totals['corn_qty'] = ($totals['corn_qty'] + $item['qty']);
                        break;
                        case 'pinch-pleated':
                        case 'pinch-pleated-new':
                            $totals['drape_qty'] = ($totals['drape_qty'] + $item['qty']);
                        break;
                    }
                break;
            }
            
        }
        
        
        if($thisOrder['cc_qty'] != $totals['cc_qty']){
            echo "UPDATE `orders` SET `cc_qty`='".$totals['cc_qty']."' WHERE `id`='".$thisOrder['id']."';<br>";
        }
        
        if($thisOrder['bs_qty'] != $totals['bs_qty']){
            echo "UPDATE `orders` SET `bs_qty`='".$totals['bs_qty']."' WHERE `id`='".$thisOrder['id']."';<br>";
        }
        
        if($thisOrder['drape_qty'] != $totals['drape_qty']){
            echo "UPDATE `orders` SET `drape_qty`='".$totals['drape_qty']."' WHERE `id`='".$thisOrder['id']."';<br>";
        }
        
        if($thisOrder['val_qty'] != $totals['val_qty']){
            echo "UPDATE `orders` SET `val_qty`='".$totals['val_qty']."' WHERE `id`='".$thisOrder['id']."';<br>";
        }
        
        if($thisOrder['corn_qty'] != $totals['corn_qty']){
            echo "UPDATE `orders` SET `corn_qty`='".$totals['corn_qty']."' WHERE `id`='".$thisOrder['id']."';<br>";
        }
        
        
        exit;
        
    }
    
    



    public function debugeditpublishedorder($orderID){
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
		//$quoteBOMReqsTable=TableRegistry::get('QuoteBomRequirements');
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
		
		if($quotesTable->save($newQuote)){
		    
		    echo "SAVED QUOTE ID ".$newQuote->id."<br>";
		
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
				    
				    echo "SAVED NEW LINE ".$newLine->id."<br>";
				    
					$lineIDsOldToNew[$line['id']]=$newLine->id;
					
					//clone METAS
					$thisQuoteLineMeta=$this->QuoteLineItemMeta->find('all',['conditions' => ['quote_item_id'=>$line['id']]])->toArray();
					foreach($thisQuoteLineMeta as $meta){
						$newMeta=$quoteLineMetaTable->newEntity();
						$newMeta->meta_key=$meta['meta_key'];
						$newMeta->meta_value=$meta['meta_value'];
						$newMeta->quote_item_id = $newLine->id;
						if($quoteLineMetaTable->save($newMeta)){
						    echo "SAVED NEW LINE ITEM META ".$newMeta->id."<br>";
						}
					}
					
					echo "CLONE META DONE<Br>";
					
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
						if($quoteLineNotesTable->save($newNote)){
						    echo "SAVED NEW QUOTE LINE NOTE ".$newNote->id."<br>";
						}
					}
					
					echo "CLONE NOTES DONE<br>";
					
				}
				
				
			}
			
			echo "ENDPOINT 1<hr>";
			
			//loop back through all of the new lines and make sure their PARENT LINE data is adjusted
			$thisNewQuoteLines=$this->QuoteLineItems->find('all',['conditions'=>['quote_id'=>$newQuote->id],'order'=>['sortorder'=>'asc']])->toArray();
			foreach($thisNewQuoteLines as $newQuoteLine){
			    
			    $thisLine=$quoteLinesTable->get($newQuoteLine['id']);
			    
			    
			    //let's figure out the old quote's matching line to this line
			    $oldQuoteMatchingLine=$this->QuoteLineItems->get($newQuoteLine['revised_from_line'])->toArray();
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
			
			echo "ENDPOINT 2<hr>";
			
			echo "BEGIN BOM REQUIREMENTS LOOKUP FOR QUOTEID ".$thisQuote['id']."<br>";
			
			
			//BOM Requirements clone
			$thisQuoteBOMReqs=$this->QuoteBomRequirements->find('all',['conditions'=>['quote_id'=>$thisQuote['id']]])->toArray();
			
			echo "<pre>"; print_r($thisQuoteBOMReqs); echo "</pre>";
			/*
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
			echo "ENDPOINT 3<br>";
			
			
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



    
    public function populateordernumbersinquotes(){
        set_time_limit(3000);
        $this->autoRender=false;
        
        $errors='';
        
        $quotes=$this->Quotes->find('all')->toArray();
        foreach($quotes as $quote){
            $ordercheck=$this->Orders->find('all',['conditions' => ['quote_id' => $quote['id']]])->toArray();
            if(count($ordercheck) == 1){
                foreach($ordercheck as $order){
                    
                    //$thisQuote=$this->Quotes->get($quote['id']);
                    //$thisQuote->order_id=$order['id'];
                    //$thisQuote->order_number=$order['order_number'];
                    //if($this->Quotes->save($thisQuote)){
                    echo "UPDATE `quotes` SET `order_id`='".$order['id']."', `order_number`='".$order['order_number']."' WHERE `id`='".$quote['id']."';<br>";
                    //}
                    
                }
            }elseif(count($ordercheck) > 1){
                $errors .= "ERROR: More than 1 order for quote ".$quote['id']."<br>";
            }
        }
        
        echo "<hr>".$errors;
        exit;
    }
    
    
    public function fixpublishtimestamps(){
        $this->autoRender=false;
        
        $quotes=$this->Quotes->find('all',['conditions' => ['publish_date' => 0]])->toArray();
        foreach($quotes as $quote){
            //find the Activity Log entry for when this quote was Published
            $activityCheck=$this->ActivityLogs->find('all',['conditions' => ['action_label' => 'Changed quote '.$quote['quote_number'].' status to published'],'order'=>['id'=>'desc']])->toArray();
            if(count($activityCheck) > 0){
                $i=1;
                foreach($activityCheck as $action){
                    if($i==1){
                        echo "UPDATE `quotes` SET `publish_date`='".$action['time']."' WHERE `id`='".$quote['id']."';<br>";
                    }
                    $i++;
                }
            }
        }
    }
    
    
    
    public function bulkupdatebatchdates(){
        $this->autoRender=false;
        
        $batchIDs=array(16213,16805,17966,19105,19300,19313,19314,19315,19319,19347,19410,19411,19496,19503,19505,19560,19567,19573,19626,19711,19757,19774,19775,19776,19838,19839,19873,19878,19882);
        
        foreach($batchIDs as $batchID){
            
            $oldBatchData=$this->SherryBatches->get($batchID)->toArray();
            
            //change the date for this batch
            $batchEdit=$this->SherryBatches->get($batchID);
            
            $thisOrder=$this->Orders->get($batchEdit->work_order_id)->toArray();
            
            //determine new date
            if($oldBatchData['shipped_date'] > 0){
                $newDateYMD=date('Y-m-d',$oldBatchData['shipped_date']);
                $newDateMDY=date('m/d/Y',$oldBatchData['shipped_date']);
            }elseif($oldBatchData['completed_date'] > 0){
                $newDateYMD=date('Y-m-d',$oldBatchData['completed_date']);
                $newDateMDY=date('m/d/Y',$oldBatchData['completed_date']);
            }else{
                $newDateYMD=false;
                $newDateMDY=false;
            }
            
            if($newDateYMD){
                echo "UPDATE `sherry_batches` SET `date`='".$newDateYMD."' WHERE `id`='".$batchID."';<br>";
            
                //update order item status lines
                $statusLinesLookup=$this->OrderItemStatus->find('all',['conditions' => ['sherry_batch_id' => $batchID, 'status' => 'Scheduled']])->toArray();
                foreach($statusLinesLookup as $statusLine){
                    
                    echo "UPDATE `order_item_status` SET `time`='".strtotime($newDateMDY.' 18:00:00')."' WHERE `id`='".$statusLine['id']."';<br>";
                    
                }
                
                //update sherry cache line
                $cacheLookup=$this->SherryCache->find('all',['conditions' => ['batch_id' => $batchID]])->toArray();
                foreach($cacheLookup as $cacheRow){
                    
                    echo "UPDATE `sherry_cache` SET `date`='".$newDateYMD."' WHERE `id`='".$cacheRow['id']."';<br>";
                }
                
                $activityLabel='Changed Batch '.$batchID.' Date from '.date('Y-m-d',strtotime($oldBatchData['date'].' 12:00:00')).' to '.$newDateYMD.' (WO# '.$thisOrder['order_number'].')';
                
                echo "INSERT INTO `activity_logs` (`ip_address`,`user_agent`,`time`,`user_id`,`location`,`action_label`,`data`,`session_id`) VALUES ('3.130.194.24','','".time()."','-1','/tests/bulkupdatebatchdates','".$activityLabel."','','');<br><br>";
            }
        }
        
    }
    
    
    
    public function findmismatchedtitlesandfabricmeta(){
        set_time_limit(3000);
        $this->autoRender=false;
        
        $matchCount=0;
        $mismatchCount=0;
        
        $items=$this->QuoteLineItems->find('all',['conditions' => ['product_type IN' => ['cubicle_curtains','window_treatments']]])->toArray();
        
        foreach($items as $item){
            
            //get the fabricid meta
            $metaCheck=$this->QuoteLineItemMeta->find('all',['conditions' => ['quote_item_id' => $item['id'], 'meta_key'=>'fabricid']])->toArray();
            
            foreach($metaCheck as $meta){
                $thisFabric=$this->Fabrics->get($meta['meta_value'])->toArray();
                if(!preg_match("#".$thisFabric['fabric_name']."#i",$item['title'])){
                    $mismatchCount++;
                    echo "Mismatch detected on line item id# ".$item['id']."<br>====> TITLE=".$item['title']."<br>====>Fabric=".$thisFabric['fabric_name']."<hr>";
                }else{
                    $matchCount++;
                }
            }
            
        }
        
        echo "MATCHED=".$matchCount."<br>MISMATCHED=".$mismatchCount;
        
    }
    
}