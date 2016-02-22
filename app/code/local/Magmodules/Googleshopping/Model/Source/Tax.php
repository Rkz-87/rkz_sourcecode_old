<?php
/**
 * Magmodules.eu - http://www.magmodules.eu - info@magmodules.eu
 * =============================================================
 * NOTICE OF LICENSE [Single domain license]
 * This source file is subject to the EULA that is
 * available through the world-wide-web at:
 * http://www.magmodules.eu/license-agreement/
 * =============================================================
 * @category    Magmodules
 * @package     Magmodules_Googleshopping
 * @author      Magmodules <info@magmodules.eu>
 * @copyright   Copyright (c) 2015 (http://www.magmodules.eu)
 * @license     http://www.magmodules.eu/license-agreement/  
 * =============================================================
 */
 
class Magmodules_Googleshopping_Model_Source_Tax {

	public function toOptionArray() {
		$position = array();
		$position[] = array('value'=> '', 'label'=> Mage::helper('googleshopping')->__('No'));
		$position[] = array('value'=> 'incl', 'label'=> Mage::helper('googleshopping')->__('Force including Tax'));	
		$position[] = array('value'=> 'excl', 'label'=> Mage::helper('googleshopping')->__('Force excluding Tax'));	
		return $position;
	}
	
}