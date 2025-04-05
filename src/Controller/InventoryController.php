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


class InventoryController extends AppController{
		

	public function initialize() {

        parent::initialize();

        $this->loadComponent('RequestHandler');
		$this->loadComponent('Security');
		
		$this->loadModel('Customers');
		$this->loadModel('Products');
		$this->loadModel('Quotes');
		$this->loadModel('QuoteLineItems');
		$this->loadModel('QuoteLineItemMeta');
		$this->loadModel('QuoteLineItemNotes');
		$this->loadModel('Orders');
		$this->loadModel('OrderItems');
		$this->loadModel('OrderItemStatus');
		
		$this->loadModel('Fabrics');
		$this->loadModel('MaterialPurchases');
		$this->loadModel('MaterialInventory');
		$this->loadModel('MaterialUsages');
		$this->loadModel('Vendors');
		$this->loadModel('SherryCache');
		$this->loadModel('Linings');
		$this->loadModel('SherryBatches');
		
		$this->loadModel('InventoryCache');
		$this->loadModel('InventoryRollsCache');
	    $this->loadModel('InventoryActiveorderCache');
	    $this->loadModel('InventoryQuoteCache');
	}

	

		

	public function beforeFilter(Event $event) {
        parent::beforeFilter($event);
    }
    
    
    public function recalculate_active_order_cache_for_fabricid($fabricID){
        //run this cache update every time a new ROLL is added to inventory for this fabric ID
        //also run this cache update every time new ACTUAL USAGE is recorded (material is cut from a roll)
        
    }
    

    public function add_new_active_order_cache_entry_for_new_order_items($orderID){
        //create a cache entry for each line item on this new order
        
    }
    
    public function delete_active_order_cache_entries_for_deleted_order_items($orderID){
        //remove cache entries for all line items in this order upon cancel/delete/completion of order
        
    }
    
    
    public function update_active_order_cache_entries_for_order_items($orderID){
        //recalculate and update cache entries for all line items in this order upon changes
        
    }
    
    
    public function index(){
        //query the cache only
        
    }
    
    
    public function purchases(){
        //
        
    }
    
    
    public function rolls(){
        
        //query the cache only for yardages
        
    }
    
    public function addroll($fabricID=false){
        //update the cache for this fabric id
        
    }
    
    
    public function actualusage($rollID){
        //update the roll cache for this roll because some fabric was removed from it
        //also update the fabric-level cache of inventory for this fabric id
        
    }
    
    
    public function testone(){
        $this->autoRender=false;
        
        $activeOrderCacheTable=TableRegistry::get('InventoryActiveorderCache');
        
        $activeOrders=$this->Orders->find('all',['conditions'=>['status IN' => ['Pre-Production','Needs Line Items']]])->toArray();
        
        echo count($activeOrders)." orders<Hr>";
        
        
        foreach($activeOrders as $order){
            $thisOrderItems=$this->OrderItems->find('all',['conditions' => ['order_id' => $order['id']]])->toArray();
            
            $customerData=$this->Customers->get($order['customer_id'])->toArray();
            
            foreach($thisOrderItems as $orderItem){
                
                $thisQuoteItem=$this->QuoteLineItems->get($orderItem['quote_line_item_id'])->toArray();
                $thisQuoteItemMetaLookup=$this->QuoteLineItemMeta->find('all',['conditions' => ['quote_item_id' => $orderItem['quote_line_item_id']]])->toArray();
                $thisQuoteItemMeta=array();
                foreach($thisQuoteItemMetaLookup as $quoteItemMeta){
                    $thisQuoteItemMeta[$quoteItemMeta['meta_key']]=$quoteItemMeta['meta_value'];
                }
                
                if(isset($thisQuoteItemMetas['fabricid']) && intval($thisQuoteItemMetas['fabricid']) > 0){
                    $thisFabric=$this->Fabrics->get($thisQuoteItemMetas['fabricid'])->toArray();
                }else{
                    $thisFabric=array('fabric_name'=>null,'color'=>null,'com_fabric'=>0);
                }
                
                //insert it into the cache table
                $newCacheEntry=$activeOrderCacheTable->newEntity();
                $newCacheEntry->order_id = $order['id'];
                $newCacheEntry->fabric_id = $thisQuoteItemMeta['fabricid'];
                $newCacheEntry->fabric_name = $thisQuoteItemMeta['fabric_name'];
                $newCacheEntry->fabric_color = $thisQuoteItemMeta['fabric_color'];
                $newCacheEntry->order_lineitem_id=$orderItem['id'];
                $newCacheEntry->quote_id=$order['quote_id'];
                $newCacheEntry->quote_lineitem_id=$orderItem['quote_line_item_id'];
                $newCacheEntry->line_number=$thisQuoteItem['line_number'];
                $newCacheEntry->room_number = $thisQuoteItem['room_number'];
                $newCacheEntry->time_added=time();
                $newCacheEntry->last_updated=time();
                $newCacheEntry->total_yds=$thisQuoteItemMeta['total-yds'];
                $newCacheEntry->qty=$thisQuoteItem['qty'];
                //$newCacheEntry->order_item_status=
                $newCacheEntry->customer_id = $order['customer_id'];
                $newCacheEntry->customer_name=$customerData['company_name'];
                //$newCacheEntry->vendor_id=
                //$newCacheEntry->vendor_name=
                //$newCacheEntry->fabric_owner=
                
                if($thisFabric['com_fabric'] == 1){
                    $newCacheEntry->fabric_owner='COM';
                }else{
                    $newCacheEntry->fabric_owner='MOM';
                }
                
                if($activeOrderCacheTable->save($newCacheEntry)){
                    echo "Created cache entry for order ".$order['id'].", line item ".$thisQuoteItem['line_number']."<br>";
                }
                
            }
        }
        
        
        
    }
    
    
    public function test(){
        $this->autoRender=false;
        
        $allFabrics=$this->Fabrics->find('all')->toArray();
        foreach($allFabrics as $fabric){
            //find all Rolls in inventory of this fabric
            $rollCount=0;
            $yardsAvailable=0;
            
            $allRolls=$this->MaterialInventory->find('all',['conditions'=>['material_type'=>'Fabrics','material_id'=>$fabric['id']]])->toArray();
            foreach($allRolls as $roll){
                if($roll['status']=="Active"){
                    $rollCount++;
                    $yardsAvailable=($yardsAvailable + floatval($roll['yards_received']));
                }
            }
            
            
            $committedYards=0;
            //loop through all active orders (cache table) and find committed yards
            $activeOrders=$this->InventoryActiveorderCache->find('all',['conditions'=>['fabric_id' => $fabric['id']]])->toArray();
            foreach($activeOrders as $order){
                $committedYards=($committedYards + floatval($order['total_yds']));
            }
            
            
            //initial INSERT into the cache
            $invCacheTable=TableRegistry::get('InventoryCache');
            $newInvLine=$invCacheTable->newEntity();
            $newInvLine->fabric_id=$fabric['id'];
            $newInvLine->fabric_name = $fabric['fabric_name'];
            $newInvLine->fabric_color = $fabric['color'];
            
            $newInvLine->yards_available = $yardsAvailable;
            $newInvLine->committed_yards = $committedYards;
            $newInvLine->roll_count=$rollCount;
            $newInvLine->time=time();
            
            $newInvLine->is_hci_fabric = $fabric['is_hci_fabric'];
            $newInvLine->com_fabric = $fabric['com_fabric'];
            $newInvLine->image_file = $fabric['image_file'];
            
            if($invCacheTable->save($newInvLine)){
                echo "Created cache for fabric ".$fabric['id']."<br>";
            }
            
        }
        
    }
    
    
    
    public function testthree(){
        $this->autoRender=false;
        
        $rollInvCacheTable=TableRegistry::get('InventoryRollsCache');
        
        //individual rolls of fabric initial insert into cache
        $allFabrics=$this->InventoryCache->find('all')->toArray();
        foreach($allFabrics as $fabric){
            $allRollsThisFabric=$this->MaterialInventory->find('all',['conditions' => ['material_type'=>'Fabrics','material_id' => $fabric['id']]])->toArray();
            foreach($allRollsThisFabric as $roll){
                
                $yardsLeftThisRoll=floatval($roll['yards_received']);
                $allUsagesThisRoll=$this->MaterialUsages->find('all',['conditions' => ['material_type'=>'Fabrics','material_id'=>$fabric['id'],'roll_id'=>$roll['id']]]);
                foreach($allUsagesThisRoll as $usageRow){
                    $yardsLeftThisRoll=($yardsLeftThisRoll - floatval($usageRow['yards_used']));
                }
                
                
                $newCacheRoll=$rollInvCacheTable->newEntity();
                $newCacheRoll->fabric_id = $roll['material_id'];
                $newCacheRoll->roll_id=$roll['id'];
                $newCacheRoll->original_yards = $roll['yards_received'];
                $newCacheRoll->yards_left = $yardsLeftThisRoll;
                $newCacheRoll->time=time();
                if($rollInvCacheTable->save($newCacheRoll)){
                    echo "Created cache entry for roll ".$roll['id']."<br>";
                }
            }
        }
        
    }
    
    
    public function testfour(){
        $this->autoRender=false;
        
        $quoteCacheTable=TableRegistry::get('InventoryQuoteCache');
        
        $nonOrderQuotes=$this->Quotes->find('all',['conditions' => ['order_id >' => 0,'status'=>'published']])->toArray();
        foreach($nonOrderQuotes as $quote){
            
            $thisCustomer=$this->Customers->get($quote['customer_id'])->toArray();
            
            //get line items
            $quoteLineItems=$this->QuoteLineItems->find('all',['conditions' => ['quote_id' => $quote['id']]])->toArray();
            foreach($quoteLineItems as $quoteLineItem){
                
                $quoteItemMetas=array();
                $getItemMetas=$this->QuoteLineItemMeta->find('all',['conditions'=>['quote_item_id' => $quoteLineItem['id']]])->toArray();
                foreach($getItemMetas as $itemMeta){
                    $quoteItemMetas[$itemMeta['meta_key']]=$itemMeta['meta_value'];
                }
                
                if(isset($quoteItemMetas['fabricid']) && intval($quoteItemMetas['fabricid']) > 0){
                    $thisFabric=$this->Fabrics->get($quoteItemMetas['fabricid'])->toArray();
                }else{
                    $thisFabric=array('fabric_name'=>null,'color'=>null,'com_fabric'=>0);
                }
                
                $newQuoteCache=$quoteCacheTable->newEntity();
                $newQuoteCache->quote_id=$quote['id'];
                $newQuoteCache->fabric_id=$quoteItemMetas['fabricid'];
                $newQuoteCache->fabric_name=$thisFabric['fabric_name'];
                $newQuoteCache->fabric_color=$thisFabric['color'];
                $newQuoteCache->quote_lineitem_id=$quoteLineItem['id'];
                
                $newQuoteCache->line_number=$quoteLineItem['line_number'];
                $newQuoteCache->room_number = $quoteLineItem['room_number'];
                $newQuoteCache->qty=$quoteLineItem['qty'];
                
                $newQuoteCache->total_yds = $quoteItemMetas['total-yds'];
                $newQuoteCache->time_added=time();
                $newQuoteCache->last_updated=time();
                $newQuoteCache->customer_id=$quote['customer_id'];
                $newQuoteCache->customer_name=$thisCustomer['company_name'];
                
                if($thisFabric['com_fabric'] == 1){
                    $newQuoteCache->fabric_owner='COM';
                }else{
                    $newQuoteCache->fabric_owner='MOM';
                }
                
                if($quoteCacheTable->save($newQuoteCache)){
                    echo "Inserted line item ".$quoteItem['id']." for quote ".$quote['id']."<br>";
                }
            }
        }
        
    }
    
    
    
    public function ttee(){
        $this->autoRender=false;
        $all=$this->InventoryActiveorderCache->find('all')->toArray();
        foreach($all as $r){
            $thisOrder=$this->Orders->get($r['order_id'])->toArray();
            $thisRow=$this->InventoryActiveorderCache->get($r['id']);
            $thisRow->order_number=$thisOrder['order_number'];
            $this->InventoryActiveorderCache->save($thisRow);
        }
        
        $all=$this->InventoryQuoteCache->find('all')->toArray();
        foreach($all as $r){
            $thisQuote=$this->Quotes->get($r['quote_id'])->toArray();
            $thisRow=$this->InventoryQuoteCache->get($r['id']);
            $thisRow->quote_number=$thisQuote['quote_number'];
            $this->InventoryQuoteCache->save($thisRow);
        }
        
        
    }
    
    
    
    
    public function tttt(){
        $this->autoRender=false;
        $all=$this->InventoryActiveorderCache->find('all')->toArray();
        foreach($all as $r){
            $thisOrder=$this->Orders->get($r['order_id'])->toArray();
            $thisRow=$this->InventoryActiveorderCache->get($r['id']);
            $thisRow->customer_po_number=$thisOrder['po_number'];
            $this->InventoryActiveorderCache->save($thisRow);
        }
        
        
    }
    
    
    
    
    public function eeee(){
        $this->autoRender=false;
        $all=$this->InventoryActiveorderCache->find('all')->toArray();
        foreach($all as $r){
            $thisOrder=$this->Orders->get($r['order_id'])->toArray();
            $thisQuote=$this->Quotes->get($thisOrder['quote_id'])->toArray();
            
            $thisRow=$this->InventoryActiveorderCache->get($r['id']);
            $thisRow->quote_number=$thisQuote['quote_number'];
            if($this->InventoryActiveorderCache->save($thisRow)){
                echo "Saved quote number for cache id ".$r['id']."<br>";
            }
        }
        
        
    }
    
    
    
    
    public function gggg(){
        $this->autoRender=false;
        $all=$this->InventoryActiveorderCache->find('all')->toArray();
        foreach($all as $r){
            $fabLookup=$this->Fabrics->find('all',['conditions' => ['id' => $r['fabric_id']]]);
            if(count($fabLookup) > 0){
                foreach($fabLookup as $thisFabric){
            
                    $thisRow=$this->InventoryActiveorderCache->get($r['id']);
                    $thisRow->fabric_name = $thisFabric->fabric_name;
                    $thisRow->fabric_color = $thisFabric->color;
                    
                    if($this->InventoryActiveorderCache->save($thisRow)){
                        echo "Saved fabric name and color for cache id ".$r['id']."<br>";
                    }
                }
            }else{
                echo "<font color=\"red\">Could not find fabric id ".$r['fabric_id']."</font><br>";
            }
        }
        
        
    }
    
    
}
