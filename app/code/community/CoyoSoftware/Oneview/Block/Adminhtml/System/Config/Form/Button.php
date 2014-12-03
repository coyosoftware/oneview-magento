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

class CoyoSoftware_Oneview_Block_Adminhtml_System_Config_Form_Button extends Mage_Adminhtml_Block_System_Config_Form_Field
{
    /*
     * Set template
     */
    protected function _construct()
    {
        parent::_construct();
        $this->setTemplate('coyosoftware/oneview/system/config/button.phtml');
    }

    /**
     * Return element html
     *
     * @param  Varien_Data_Form_Element_Abstract $element
     * @return string
     */
    protected function _getElementHtml(Varien_Data_Form_Element_Abstract $element)
    {
        return $this->_toHtml();
    }

    /**
     * Return ajax url for button
     *
     * @return string
     */
    public function getAjaxExportUrl()
    {
        return Mage::helper('adminhtml')->getUrl('adminhtml/oneview/export');
    }

    /**
     * Generate button html
     *
     * @return string
     */
    public function getButtonHtml()
    {
    	$access_token = Mage::getStoreConfig('oneview_config/messages/access_token');
		
		if($access_token)
		{
			$button = $this->getLayout()->createBlock('adminhtml/widget_button')
	            ->setData(array(
	            'id'        => 'coyosoftware_oneview_export',
	            'label'     => $this->helper('adminhtml')->__('Exportar Contatos para o Oneview	'),
	            'onclick'   => 'javascript:oneview_export(); return false;'
	        ));

        	return $button->toHtml();
		}
		
		return "";
    }
}