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

 * @package     CoyoSoftware_Oneview
 * @author      Coyô Software
 * @copyright   Copyright (c) 2014 Coyô Software (http://www.coyo.com.br)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

class CoyoSoftware_Oneview_OneviewController extends Mage_Adminhtml_Controller_Action
{
    public function exportAction()
    {
    	$users = mage::getModel('customer/customer')->getCollection()
    		->addAttributeToSelect('email')->addAttributeToSelect("firstname")->addAttributeToSelect("lastname");
		foreach ($users as $user)
		{
			$user_data = $user->getData();
			
			$this->_register_client($user_data["email"], $user_data["firstname"], $user_data["lastname"]);
		}
		
		$subscribers = mage::getModel('newsletter/subscriber')->getCollection();
		foreach ($subscribers as $subscriber)
		{
			$subscriber_data = $subscriber->getData();
			
			if ($subscriber_data["customer_id"] != 0)
			{
				$this->_register_client($subscriber_data["subscriber_email"]);
			}
		}
			
        $result = 1;
        Mage::app()->getResponse()->setBody($result);
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
