<?php
/**
 * Coyô Software
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to contato@coyo.com.br so we can send you a copy immediately.
 *
 * @category   CoyoSoftware
 * @package    CoyoSoftware_Oneview
 * @copyright  Copyright (c) 2010 Coyô Software (http://www.coyo.com.br)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

class CoyoSoftware_Oneview_Model_Observer
{
    public function createOneviewContactAfterSubscribe($observer) 
    {
    	$event = $observer->getEvent();
	    $subscriber = $event->getDataObject();
	    $data = $subscriber->getData();
		$email = $data['subscriber_email'];
		
		if ($email)
		{
			$this->_register_client($email);
		}
		
		return $observer;
    }
	
	public function createOneviewContactAfterRegister($observer) 
    {
    	$event = $observer->getEvent();
        $customer = $event->getCustomer();
		$email = $customer->getEmail();
		
		if($email)
		{
			$this->_register_client($email, $customer->getFirstname(), $customer->getLastname());
		}
		
		return $observer;
    }
	
	private function _register_client($email, $firstname = "", $lastname = "") {
		$access_token = Mage::getStoreConfig('oneview_config/messages/access_token');
		
		if ($access_token)
		{
			$data = "";
			$header = "";
			$url = "http://www.oneview.com.br/api";
			
			$data = array(
				'access_token' => $access_token,
				'contact' => array(
					'email' => $email,
					'name' => $firstname." ".$lastname
				)
			);
			
			$data_string = json_encode($data); 
			
			$ch = curl_init($url.'/contacts');
			                                                                      
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");                                                                     
			curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);                                                                  
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);                                                                      
			curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                          
			    'Content-Type: application/json',                                                                                
			    'Content-Length: ' . strlen($data_string))                                                                       
			); 
		
			$response = curl_exec($ch);
		} 
	}
}