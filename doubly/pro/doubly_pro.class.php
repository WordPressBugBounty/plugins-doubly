<?php
/**
 * @package Doubly
 * @author Unlimited Elements
 * @copyright (C) 2022 Unlimited Elements, All Rights Reserved. 
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 **/

if(!defined("DOUBLY_INC")) die("restricted access");

class Doubly_Pro{
	
	/**
	 * init the pro version related
	 */
	public static function onInit(){
		
		if(defined("DOUBLY_FREE_VERSION"))
			return(false);
		
		GlobalsDOUBLY::$isProVersion = true;
		
		
		if(defined("DOUBLY_PRO_VERSION")){
			GlobalsDOUBLY::$isProActive = true;
			return(false);
		}
		
		GlobalsDOUBLY::$isProActive = HelperDOUBLY::isActivatedByFreemius();
	}
	
	
	/**
	 * add pro general settings
	 */
	public static function addProGeneralSettings($settings, $arrRoles){

		//---- hr
		
		$settings->addHr("hr_before_front");
		
		//---- enable front copy
		
		$arrItems = array(
			"no"=>__("No","doubly"),
			"enable"=>"Enable",
		);
		
		$arrItems = array_flip($arrItems);
		
		$params = array(
			"description"=>__("Enable front end section copy button for not registered users", "doubly")
		);
		
		$settings->addSelect("enabled_elementor_front_copy", $arrItems, __("Enable Elementor Front Section Copy","doubly"), "no", $params);
		
		
		$settings->startBulkControl("enabled_elementor_front_copy", "show", "enable");
		
			//---- roles for front 
			
			$arrRoles = UniteFunctionsDOUBLY::addArrFirstValue($arrRoles, "quest","Guest (not logged in)");
			
			$params = array();
			$params["description"] = __("Select user roles that will be allowed for the front copy button to appear","doubly");
			
			$settings->addMultiSelect("allowed_roles_front", $arrRoles, __("--- Allowed Roles for Front Copy","doubly"), "administrator", $params);
			
			//---- exclude pages for front
			
			$params = array();
			$params["description"] = __("Write page slugs to exclude from the front copy button. To set more exclude/include rules you can use <b>doubly_front_copy_page_excluded</b>	 filter. ","doubly");
			
			$settings->addTextArea("front_copy_excluded_pages", "", __("--- Excluded Pages for Front Copy","doubly"), $params);
			
			
			//---- link for info window
			
			$params = array();
			$params["description"] = __("The load more link for the info window. You can use our info page: https://doubly.pro/docs/live-copy-button/","doubly");
			
			$settings->addTextBox("front_copy_link", "https://doubly.pro/docs/live-copy-button/", __("--- Front Copy Learn More Link","doubly"), $params);
			
			//---- hr
			
			$settings->addHr("hr_after_front");
		
		
		$settings->endBulkControl();
		
		return($settings);
	}
	
	
}