<?php
/**
 * @package Doubly
 * @author Unlimited Elements
 * @copyright (C) 2022 Unlimited Elements, All Rights Reserved. 
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 **/

if(!defined("DOUBLY_INC")) die("restricted access");


	class UniteFunctionsDOUBLY{
		
		const SANITIZE_ID = "sanitize_id";		//positive number or empty
		const SANITIZE_TEXT_FIELD = "sanitize_text_field";		
		const SANITIZE_KEY = "sanitize_key";
		const SANITIZE_NOTHING = "sanitize_nothing";
		
		private static $serial = 0;
		private static $arrCache = array();
		
		
		/**
		 * throw error
		 */
		public static function throwError($message,$code=null){
			
			if(!empty($code))
				throw new Exception($message,$code);
			else
				throw new Exception($message);
		}
		
		
		/**
		 * throw error and show function trace
		 */
		public static function showTrace($exit = false){
			
			try{
				throw new Exception("Show me the trace");
			}catch(Exception $e){
		
				$trace = $e->getTraceAsString();
				dmp($trace);
		
				if($exit == true)
					exit();
			}
		}
		
		/**
		 * check if array is assoc
		 */
		public static function isArrayAssoc($arr){
			if(is_array($arr) == false)
				return(false);
		    if (array() === $arr) return false;
		    return array_keys($arr) !== range(0, count($arr) - 1);
		}		
		
		/**
		 * get post or get variable
		 */
		public static function getPostGetVariable($name,$initVar = "", $sanitizeType=""){
			
			$var = $initVar;
			
			if(isset($_POST[$name])) 
				$var = self::sanitizeVar($_POST[$name],$sanitizeType);
			
			else if(isset($_GET[$name])) 
				$var = self::sanitizeVar($_GET[$name],$sanitizeType);
						
			return($var);
		}
		
		
		/**
		 * get post variable
		 */
		public static function getPostVariable($name,$initVar = "",$sanitizeType=""){
			$var = $initVar;
			if(isset($_POST[$name])) 
				$var = self::sanitizeVar($_POST[$name],$sanitizeType);
			
			return($var);
		}
		
		
		/**
		 * get get variable
		 */
		public static function getGetVar($name, $initVar = "", $sanitizeType=""){
			
			$var = $initVar;
			if(isset($_GET[$name])) 
				$var = self::sanitizeVar($_GET[$name],$sanitizeType);
									
			return($var);
		}
		
	
		public static function a_________SANITIZE________(){}
		
		/**
		 * sanitize filename for print
		 */
		public static function sanitizeFilenameForOutput($filename){
			
			$filename = strip_tags($filename);
			
			$filename = trim($filename);
			
			if(strlen($filename) > 40)
				$filename = substr($filename, 0, 40);
			
			$filename = htmlspecialchars($filename);
			
			return($filename);
		}
		
		/**
		 * filter variable
		 */
		public static function sanitizeVar($var, $type){
			
			switch($type){
				case self::SANITIZE_ID:
					
					if(is_array($var))
						return(null);
					
					if(empty($var))
						return("");
					
					$var = (int)$var;
					$var = abs($var);
		
					if($var == 0)
						return("");
				
				break;
				case self::SANITIZE_KEY:
					
					if(is_array($var))
						return(null);
					
					$var = sanitize_key($var);
				break;
				case self::SANITIZE_TEXT_FIELD:
					$var = sanitize_text_field($var);
				break;
				case self::SANITIZE_NOTHING:
				break;
				default:
					self::throwError("Wrong sanitize type: " . $type);
				break;
			}
		
			return($var);
		}
		
		
		/**
		 * get value from array. if not - return alternative
		 */
		public static function getVal($arr,$key,$altVal=""){
			
			if(isset($arr[$key]))
			  return($arr[$key]);
			
			return($altVal);
		}
		
		public static function a_________arrays________(){}
		
		
		/**
		 * add array item. if key exists, then merge
		 */
		public static function addMergeArrayAsItem($arr, $key, $arrItem){
			
			$arrExisting = self::getVal($arr, $key);
			
			if(empty($arrExisting) || is_array($arrExisting) == false){
				$arr[$key] = $arrItem;
				return($arr);
			}
			
			$arrAdd = array_merge($arrExisting, $arrItem);
			
			$arr[$key] = $arrAdd;
			
			return($arr);
		}
		
		
		/**
		 * help to find the differene between arrays
		 */
		public static function arrayRecursiveDiff($aArray1, $aArray2) {
		    
			$aReturn = array();
		  	
		    foreach ($aArray1 as $mKey => $mValue) {
		        if (array_key_exists($mKey, $aArray2)) {
		            if (is_array($mValue)) {
		                $aRecursiveDiff = self::arrayRecursiveDiff($mValue, $aArray2[$mKey]);
		                if (count($aRecursiveDiff)) { $aReturn[$mKey] = $aRecursiveDiff; }
		            } else {
		                if ($mValue != $aArray2[$mKey]) {
		                    $aReturn[$mKey] = $mValue;
		                }
		            }
		        } else {
		            $aReturn[$mKey] = $mValue;
		        }
		    }
		  
		    return $aReturn;
		}	
		
		
		/**
		 * get first item value
		 */
		public static function getArrFirstValue($arr){
			
			if(empty($arr))
				return("");
			
			if(is_array($arr) == false)
				return("");
			
			$firstValue = reset($arr);
			
			return($firstValue);
		}
		
		
		/**
		 * get first not empty key from array
		 */
		public static function getFirstNotEmptyKey($arr){
		
			foreach($arr as $key=>$item){
				if(!empty($key))
					return($key);
			}
		
			return("");
		}
		
		
		/**
		 * filter array, leaving only needed fields - also array
		 *
		 */
		public static function filterArrFields($arr, $fields, $isFieldsAssoc = false){
			$arrNew = array();
			
			if($isFieldsAssoc == false){
				foreach($fields as $field){
					if(array_key_exists($field, $arr))
						$arrNew[$field] = $arr[$field];
				}
			}else{
				foreach($fields as $field=>$value){
					if(array_key_exists($field, $arr))
						$arrNew[$field] = $arr[$field];
				}
			}
			
			return($arrNew);
		}
		
		/**
		 * remove some of the assoc array fields
		 * fields is simple array - field1, field2, field3
		 */
		public static function removeArrItemsByKeys($arrItems, $keysToRemove){
			
			foreach($keysToRemove as $key){
				
				if(array_key_exists($key, $arrItems))
					unset($arrItems[$key]);
			
			}
			
			return($arrItems);
		}
		
		
		/**
		 * Convert std class to array, with all sons
		 */
		public static function convertStdClassToArray($d){
		
			if (is_object($d)) {
				$d = get_object_vars($d);
			}
			
			if (is_array($d)){
			
				return array_map(array("UniteFunctionsDOUBLY","convertStdClassToArray"), $d);
			} else {
				return $d;
			}
			
		}
		
		
		/**
		 * strip slashes from ajax input data
		 */
		public static function normalizeAjaxInputData($arrData){
			
			if(!is_array($arrData))
				return($arrData);
			
			foreach($arrData as $key=>$item){
				
				if(is_string($item))
					$arrData[$key] = stripslashes($item);
				
				//second level
				if(is_array($item)){
					
					foreach($item as $subkey=>$subitem){
						if(is_string($subitem))
							$arrData[$key][$subkey] = stripslashes($subitem);
						
						//third level
						if(is_array($subitem)){
	
							foreach($subitem as $thirdkey=>$thirdItem){
								if(is_string($thirdItem))
									$arrData[$key][$subkey][$thirdkey] = stripslashes($thirdItem);
							}
						
						}
						
					}
				}
				
			}
			
			return($arrData);
		}
		
		
		
		/**
		 *
		 * get random array item
		 */
		public static function getRandomArrayItem($arr){
			$numItems = count($arr);
			$rand = rand(0, $numItems-1);
			$item = $arr[$rand];
			return($item);
		}
		
		/**
		 * get different values in $arr from the default $arrDefault
		 * $arrMustKeys - keys that must be in the output
		 *
		 */
		public static function getDiffArrItems($arr, $arrDefault, $arrMustKeys = array()){
						
			if(gettype($arrDefault) != "array")
				return($arr);
		
			if(!empty($arrMustKeys))
				$arrMustKeys = UniteFunctionsDOUBLY::arrayToAssoc($arrMustKeys);
		
			$arrValues = array();
			foreach($arr as $key => $value){
		
				//treat must value
				if(array_key_exists($key, $arrMustKeys) == true){
					$arrValues[$key] = self::getVal($arrDefault, $key);
					if(array_key_exists($key, $arr) == true)
						$arrValues[$key] = $arr[$key];
					continue;
				}
		
				if(array_key_exists($key, $arrDefault) == false){
					$arrValues[$key] = $value;
					continue;
				}
		
				$defaultValue = $arrDefault[$key];
				if($defaultValue !== $value){
					$arrValues[$key] = $value;
					continue;
				}
		
			}
			
			
			return($arrValues);
		}
		
		
		/**
		 *
		 * Convert array to assoc array by some field
		 */
		public static function arrayToAssoc($arr, $field=null, $field2 = null){
			
			if(empty($arr))
				return(array());
			
			$arrAssoc = array();
		
			foreach($arr as $item){
				
				if(empty($field))
					$arrAssoc[$item] = $item;
				else{
					
					if(!empty($field2))
						$arrAssoc[$item[$field]] = $item[$field2];
					else
						$arrAssoc[$item[$field]] = $item;
					
				}
			}
		
			return($arrAssoc);
		}
		
		
		/**
		 *
		 * convert assoc array to array
		 */
		public static function assocToArray($assoc){
			
			$arr = array();
			foreach($assoc as $key=>$item){
				$arr[] = $item;
			}
		
			return($arr);
		}
		
		
		/**
		 *
		 * convert assoc array to array
		 */
		public static function assocToArrayKeyValue($assoc, $keyName, $valueName, $firstItem = null){
			
			$arr = array();
			if(!empty($firstItem))
				$arr = $firstItem;
			
			foreach($assoc as $item){
				if(!array_key_exists($keyName, $item))
					UniteFunctionsDOUBLY::throwError("field: $keyName not found in array");
				
				if(!array_key_exists($valueName, $item))
					UniteFunctionsDOUBLY::throwError("field: $valueName not found in array");
				
				$key = $item[$keyName];
				$value = $item[$valueName];
				
				$arr[$key] = $value;
			}
		
			return($arr);
		}
		
		
		/**
		 *
		 * do "trim" operation on all array items.
		 */
		public static function trimArrayItems($arr){
			if(gettype($arr) != "array")
				UniteFunctionsDOUBLY::throwError("trimArrayItems error: The type must be array");
		
			foreach ($arr as $key=>$item)
				$arr[$key] = trim($item);
		
			return($arr);
		}
		
		/**
		 *
		 * encode array into json for client side
		 */
		public static function jsonEncodeForClientSide($arr){
			
			if(empty($arr))
				$arr = array();
						
			$json = json_encode($arr);
			$json = addslashes($json);
			
			$json = "'".$json."'";
		
			return($json);
		}
		
		
		/**
		 * encode json for html data like data-key="json"
		 */
		public static function jsonEncodeForHtmlData($arr, $dataKey=""){
			
			$strJson = "";
			if(!empty($arr)){
				$strJson = json_encode($arr);
				$strJson = htmlspecialchars($strJson);
			}
			if(!empty($dataKey))
				$strJson = " data-{$dataKey}=\"{$strJson}\"";
			
			return($strJson);
		}
		
		
		/**
		 * convert array with styles in each item to items string
		 */
		public static function arrStyleToStrStyle($arrStyle, $styleName = "", $addCss = "", $addImportant = false){
		
			if(empty($arrStyle) && empty($addCss))
				return("");
		
			$br = "\n";
			$tab = "	";
		
			$output = $br;
		
			if(!empty($styleName))
				$output .= $styleName."{".$br;
		
			foreach($arrStyle as $key=>$value){
				if($key == "inline_css"){
					$addCss .= $value;
					continue;
				}
				
				if($addImportant == true)	
					$value = $value . " !important";
					
				$output .= $tab.$key.":".$value.";".$br;
			}
		
			//add additional css
			if(!empty($addCss)){
				$arrAddCss = explode($br, $addCss);
				$output .= $br;
				foreach($arrAddCss as $str){
					$output .= $tab.$str.$br;
				}
			}
		
			if(!empty($styleName))
				$output .= "}".$br;
		
			return($output);
		}
		
		
		/**
		 * convert array with styles in each item to items string
		 */
		public static function arrStyleToStrInlineCss($arrStyle, $addCss = "", $addStyleTag = true){
			
			$addCss = trim($addCss);
			
			if(empty($arrStyle) && empty($addCss))
				return("");
			
			$output = "";
			foreach($arrStyle as $key=>$value){
				$output .= $key.":".$value.";";
			}
			
			if(!empty($addCss)){
				
				$addCss = self::removeLineBreaks($addCss);
				$output .= $addCss;
			}
			
			if($addStyleTag && !empty($output))
				$output = "style=\"{$output}\"";
			
			
			return($output);
		}
		
		/**
		 * check if the array is accociative or not
		 */
		public static function isAssocArray($arr){
				if (array() === $arr) return false;
				return array_keys($arr) !== range(0, count($arr) - 1);
		}
		
		
		/**
		 * insert items to array
		 * array (key, text, insert_after)
		 */
		public static function insertToAssocArray($arrItems, $arrNewItems){
		
			$arrInsert = array();
			$arrInsertTop = array();
			$counter = 0;
			
			$arrOutput = array();
			
			//prepare insert arrays
			foreach($arrNewItems as $item){
				$insertAfter = UniteFunctionsDOUBLY::getVal($item, "insert_after");
				
				if($insertAfter	== "bottom")
					$insertAfter = null;
				
				if(empty($insertAfter)){
					$counter++;
					$insertAfter = "bottom_".$counter;
				}
		
				if($insertAfter == "top")
					$arrInsertTop[] = $item;
				else{
					
					if(isset($arrInsert[$insertAfter])){
						
						if(self::isAssocArray($arrInsert[$insertAfter]) == false){
							$arrInsert[$insertAfter][] = $item;		//more then 2 items
						}else{
							//second item
							$arrInsert[$insertAfter] = array($arrInsert[$insertAfter], $item);
						}
						
					}
					else{		//first item
						
						$arrInsert[$insertAfter] = $item;
					
					}
					
				}
				
			}
			
			
			//insert the top part
			foreach($arrInsertTop as $newItem){
			
				$newItemKey = $newItem["key"];
				$newItemText = $newItem["text"];
			
				$arrOutput[$newItemKey] = $newItemText;
			}
			
			
			//create the items with new inserted to middle
			foreach($arrItems as $key=>$item){
		
				$arrOutput[$key] = $item;
		
				//insrt the item
				if(array_key_exists($key, $arrInsert)){
										
					$arrNewItem = $arrInsert[$key];
					
					if(self::isAssocArray($arrNewItem) == false){
						
						foreach($arrNewItem as $newItemReal){
							$newItemKey = $newItemReal["key"];
							$newItemText = $newItemReal["text"];
							$arrOutput[$newItemKey] = $newItemText;
						}
						
					}else{	//single item
						
						$newItemKey = $arrNewItem["key"];
						$newItemText = $arrNewItem["text"];
						$arrOutput[$newItemKey] = $newItemText;
						
					}
					
		
					unset($arrInsert[$key]);
				}
		
			}
		
			//insert the rest to bottom
			foreach($arrInsert as $newItem){
		
				$newItemKey = $newItem["key"];
				$newItemText = $newItem["text"];
		
				$arrOutput[$newItemKey] = $newItemText;
			}
		
		
			return($arrOutput);
		}
		
		/**
		 * add first value to array
		 */
		public static function addArrFirstValue($arr, $text, $value = ""){
			$arr = array($value => $text) + $arr;
			
			return($arr);
		}
		
		
		/**
		 *
		 * convert php array to js array text
		 * like item:"value"
		 */
		public static function phpArrayToJsArrayText($arr, $tabPrefix="			"){
			$str = "";
			$length = count($arr);
		
			$counter = 0;
			foreach($arr as $key=>$value){
				$str .= $tabPrefix."{$key}:\"{$value}\"";
				$counter ++;
				if($counter != $length)
					$str .= ",\n";
			}
		
			return($str);
		}
		
		/**
		 * get duplicate values from array in assoc array
		 */
		public static function getArrayDuplicateValues($arrAssoc){
			
			$arrDuplicate = array_diff_assoc($arrAssoc, array_unique($arrAssoc));
			
			$arrDuplicate = array_flip($arrDuplicate);
			
			return($arrDuplicate);
		}
		
		/**
		 * iterate array recursive, run callback on every array
		 */
		public static function iterateArrayRecursive($arr, $callback){
			
			if(is_array($arr) == false)
				return(false);
				
			call_user_func($callback, $arr);	
			
			foreach($arr as $item){
				
				if(is_array($item))
					self::iterateArrayRecursive($item, $callback);
			}
				
		}
		
		/**
		 * merge arrays with unique ids
		 */
		public static function mergeArraysUnique($arr1, $arr2, $arr3 = array()){
						
			if(empty($arr2) && empty($arr3))
				return($arr1);
			
			$arrIDs = array_merge($arr1, $arr2, $arr3);
			$arrIDs = array_unique($arrIDs);
			
			return($arrIDs);
		}
		
		/**
		 * modify data array for show - for DEBUG purposes
		 * convert single array like in post meta
		 */
		public static function modifyDataArrayForShow($arrData, $convertSingleArray = false){
			
			if(is_array($arrData) == false)
				return($arrData);
			
			$arrDataNew = array();
			foreach($arrData as $key=>$value){
				
				$key = htmlspecialchars($key);			
				
				if(is_string($value) == true)
					$value = htmlspecialchars($value);
				
				$key = " $key";
				
				$arrDataNew[$key] = $value;
				
				//convert single array
				if($convertSingleArray == true && is_array($value) && count($value) == 1 && isset($value[0]))
					$arrDataNew[$key] = $value[0];
				
			}
			
			return($arrDataNew);
		}
		
		
		public static function z_____________STRINGS_____________(){}
		
		/**
		 * add tabs to strign lines
		 */
		public static function addTabsToText($str, $tab = "	"){
			
			$lines = explode("\n", $str);
			
			foreach($lines as $index=>$line){
				$lineTrimmed = trim($line);
				if(!empty($lineTrimmed))
					$line = $tab.$line;
					
				$lines[$index] = $line;
			}
			
			$str = implode("\n", $lines);
			
			return($str);
		}
		
		/**
		 * search lower case in string
		 */
		public static function isStringContains($strContent, $strSearch){
			
			$searchString = trim($strSearch);
			if(empty($strSearch))
				return(true);
			
			$strContent = strtolower($strContent);
			$strSearch = strtolower($strSearch);
			
			$pos = strpos($strContent, $strSearch);
			
			if($pos === false)
				return(false);
			
			return(true);
		}
		
		
		/**
		 * remove line breaks in string
		 */
		public static function removeLineBreaks($string){
			
			$string = str_replace("\r", "", $string);
			$string = str_replace("\n", "", $string);
			
			return($string);
		}
		
		
		/**
		 * get random string
		 */
		public static function getRandomString($length = 10, $numbersOnly = false){
		
			$characters = '0123456789abcdefghijklmnopqrstuvwxyz';
			
			if($numbersOnly === true || $numbersOnly === "numbers")
				$characters = '0123456789';
				
			if(is_string($numbersOnly)){
				
				switch($numbersOnly){
					case "hex_no_zero":
						$characters = '123456789abcdef';
					break;
					case "hex_letters":
						$characters = 'abcdef';
					break;
					case "hex":
						$characters = '0123456789abcdef';
					break;
					case "letters":
						$characters = "abcdefghijklmnopqrstuvwxyz";
					break;
					case "no_zero":
						$characters = '123456789abcdefghijklmnopqrstuvwxyz';
					break;
				}
				
			}
			
				
			$randomString = '';
		
			for ($i = 0; $i < $length; $i++) {
				$randomString .= $characters[rand(0, strlen($characters) - 1)];
			}
		
			return $randomString;
		}
		
		
		/**
		 * limit string chars to max size
		 */
		public static function limitStringSize($str, $numChars, $addDots = true){
			
			if(function_exists("mb_strlen") == false)
				return($str);
				
			if(mb_strlen($str) <= $numChars)
				return($str);
			
			if($addDots)
				$str = mb_substr($str, 0, $numChars-3)."...";				
			else
				$str = mb_substr($str, 0, $numChars);
			
			return($str);
		}
		
		/**
		 * truncate string
		 * preserve - preserve word
		 * separator - is the ending
		 */
		public static function truncateString($value, $length = 100, $preserve = true, $separator = '...', $charset="utf-8"){
			
			$value = strip_tags($value);
						
	        if (mb_strlen($value, $charset) > $length) {
	            if ($preserve) {
	                // If breakpoint is on the last word, return the value without separator.
	                if (false === ($breakpoint = mb_strpos($value, ' ', $length, $charset))) {
	                    return $value;
	                }
	
	                $length = $breakpoint;
	            }
	
	            return rtrim(mb_substr($value, 0, $length, $charset)).$separator;
	        }
	        
	        return $value;
		}
		
		
		
		/**
		 * convert array to xml
		 */
		public static function arrayToXML($array, $rootName, $xml = null){
			
			if($xml === null){
				$xml = new SimpleXMLElement("<{$rootName}/>");
				self::arrayToXML($array, $rootName, $xml);
				
				$strXML = $xml->asXML();
				
				if($strXML === false)
					UniteFunctionsDOUBLY::throwError("Wrong xml output");
				
				return($strXML);
			}
			
			//for inner elements:
			foreach($array as $key => $value){
				
				if(is_numeric($key))
					$key = 'item' . $key;
				
				if(is_array($value)){
					$node = $xml->addChild($key);
					self::arrayToXML($value,$rootName,$node);
				}
				else{
					$xml->addChild($key, htmlspecialchars($value));
				}
			}
			
		}
		
		
		/**
		 * format xml string
		 */
		public static function formatXmlString($xml){
	
			$xml = preg_replace('/(>)(<)(\/*)/', "$1\n$2$3", $xml);
			$token      = strtok($xml, "\n");
			$result     = '';
			$pad        = 0;
			$matches    = array();
			while ($token !== false) :
			if (preg_match('/.+<\/\w[^>]*>$/', $token, $matches)) :
			$indent=0;
			elseif (preg_match('/^<\/\w/', $token, $matches)) :
			$pad--;
			$indent = 0;
			elseif (preg_match('/^<\w[^>]*[^\/]>.*$/', $token, $matches)) :
			$indent=1;
			else :
			$indent = 0;
			endif;
			$line    = str_pad($token, strlen($token)+$pad, ' ', STR_PAD_LEFT);
			$result .= $line . "\n";
			$token   = strtok("\n");
			$pad    += $indent;
			endwhile;
			return $result;
		}		
		
		
		/**
		 * unserialize string if it's a string type
		 * the return will be always array
		 */
		public static function maybeUnserialize($str){
			
			if(empty($str))
				return($str);
			
			if(is_string($str) == false)
				return($str);
						
			//try to unserialize
			
			$arrOutput = @unserialize($str);
			
			if(is_array($arrOutput))
				return($arrOutput);
			
			return($str);
		}
		
		/**
		 * maybe json decode
		 */
		public static function maybeJsonDecode($str){
			
			if(empty($str))
				return($str);
			
			if(is_string($str) == false)
				return($str);
			
			//try to json decode
			$arrJson = self::jsonDecode($str);
			if(!empty($arrJson) && is_array($arrJson))
				return($arrJson);
			
			return($str);
		}
		
		/**
		 * sanitize attribute
		 */
		public static function sanitizeAttr($strAttr){
			
			$strAttr = htmlspecialchars($strAttr);
			
			return($strAttr);
		}
		
		/**
		 * get sanitize types array
		 */
		public static function getArrSanitizeTypes(){
			
			$arrSanitize = array();
			$arrSanitize[self::SANITIZE_ID] = __("Sanitize ID", "unlimited-elements-for-elementor");
			$arrSanitize[self::SANITIZE_KEY] = __("Sanitize KEY", "unlimited-elements-for-elementor");
			$arrSanitize[self::SANITIZE_TEXT_FIELD] = __("Sanitize Text Field", "unlimited-elements-for-elementor");
			$arrSanitize[self::SANITIZE_NOTHING] = __("No Sanitize (not recomended)", "unlimited-elements-for-elementor");
			
			return($arrSanitize);
		}
		
		
		/**
		 * normalize size
		 */
		public static function normalizeSize($value){
			
			$value = (string)$value;
			$value = strtolower($value);
			if(is_numeric($value) == false)
				return($value);
			
			//numeric
			$value = (int)$value;
			
			$value .= "px";
			
			return($value);
		}
		
		
		/**
		 * check if text is encoded
		 */
		public static function isTextEncoded($content){

			if(is_string($content) == false)
				return(false);
			
			if(empty($content))
				return(false);
			
		    // Check if there is no invalid character in string
		    if (!preg_match('/^[a-zA-Z0-9\/\r\n+]*={0,2}$/', $content)) 
		    	return false;
			
		    $decoded = @base64_decode($content, true);
		    
		    // Decode the string in strict mode and send the response
		    if(empty($decoded)) 
		    	return false;
			
		    // Encode and compare it to original one
		    if(base64_encode($decoded) != $content)
		    	return false;
			
			return true;			
		}		
		
		
		/**
		 * maybe decode content
		 */
		public static function maybeDecodeTextContent($value){
			
			if(empty($value))
				return($value);
			
			if(is_string($value) == false)
				return($value);
			
			$isEncoded = self::isTextEncoded($value);
			
			if($isEncoded == false)
				return($value);
			
			$decoded = self::decodeTextContent($value);
			
			return($decoded);
		}
		
		
		/**
		 * decode string content
		 */
		public static function decodeTextContent($content){
			
			$content = rawurldecode(base64_decode($content));
			
			return($content);
		}
		
		
		/**
		 * encode content
		 */
		public static function encodeContent($content){
			
			if(is_array($content))
				$content = json_encode($content);
			
			$content = rawurlencode($content);
			
			$content = base64_encode($content);
						
			return($content);
		}
		
		
		/**
		 * decode content given from js
		 */
		public static function decodeContent($content, $convertToArray = true){
		
			if(empty($content))
				return($content);
			
			$content = rawurldecode(base64_decode($content));
			
			if($convertToArray == true)
				$arr = self::jsonDecode($content);
			else 
				$arr = @json_decode($content);
			
			return $arr;
		}
		
		
		/**
		 * decode content given from js
		 */
		public static function jsonDecode($content, $outputArray = false){
			
			if($outputArray == true && empty($content))
				return(array());
			
			$arr = @json_decode($content);
			$arr = self::convertStdClassToArray($arr);
			
			if($outputArray == true && empty($content))
				return(array());
			
			return $arr;
		}
		
		
		/**
		 * clean path string
		 */
		public static function cleanPath($path){
			
			if(defined("DIRECTORY_SEPARATOR"))
				$ds = DIRECTORY_SEPARATOR;
			else 
				$ds = "/";
			
			if (!is_string($path) && !empty($path)){
				self::throwError('JPath::clean: $path is not a string.');
			}
			
			$path = trim($path);
			
			if(empty($path))
				return($path);
			
			// Remove double slashes and backslashes and convert all slashes and backslashes to DIRECTORY_SEPARATOR
			// If dealing with a UNC path don't forget to prepend the path with a backslash.
			elseif (($ds == '\\') && ($path[0] == '\\' ) && ( $path[1] == '\\' ))
			{
				$path = "\\" . preg_replace('#[/\\\\]+#', $ds, $path);
			}
			else
			{
				$path = preg_replace('#[/\\\\]+#', $ds, $path);
			}
			
			return $path;
		}
		
		/**
		 * get numeric portion from the string, remove all except numbers
		 */
		public static function getNumberFromString($str){
			
			$str = preg_replace("/[^0-9]/", '', $str);
			
			return($str);
		}
		
		
		/**
		 * get number from string end
		 */
		public static function getNumberFromStringEnd($str){
			
			$matches = array();
			if (!preg_match('#(\d+)$#', $str, $matches))
				return("");
			
			if(!isset($matches[1]))
				return("");
			
			return($matches[1]);
		}
		
		
		/**
		 * get number from string end
		 */
		public static function getStringTextPortion($str){
		
			$num = self::getNumberFromStringEnd($str);
			if($num === "")
				return($str);
			
			$lastPost = strlen($str)-strlen($num);
			
			$textPortion = substr($str, 0, $lastPost);
			
			return($textPortion);
		}
		
		
		/**
		 * get serial ID, should never repeat
		 */
		public static function getSerialID($prefix){
			
			self::$serial++;
			$rand = self::getRandomString(5,true);
			$id = $prefix."_".$rand."_".self::$serial;
			
			return($id);
		}
		
		
		/**
		 * get pretty html from complicated html, strip tags except usefull
		 */
		public static function getPrettyHtml($html){
			
			//strip tags
			$html = preg_replace( '/<\/?div[^>]*\>/i', '', $html );
			$html = preg_replace( '/<\/?span[^>]*\>/i', '', $html );
			$html = preg_replace( '#<script(.*?)>(.*?)</script>#is', '', $html );
			$html = preg_replace( '#<style(.*?)>(.*?)</style>#is', '', $html );
			$html = preg_replace( '/<i [^>]*><\\/i[^>]*>/', '', $html );
			$html = preg_replace( '/ class=".*?"/', '', $html );
	
			// remove lines
			$html = preg_replace( '/(^[\r\n]*|[\r\n]+)[\s\t]*[\r\n]+/', "\n", $html );
	
			$html = trim( $html );

			return $html;
		}
		
		/**
		 * get href from string
		 */
		public static function getHrefFromHtml($str){
					
			$arrFound = array();
			preg_match('/href=(["\'])([^\1]*)\1/i', $str, $arrFound);
			
			if(empty($arrFound))
				return(null);
			
			$href = self::getVal($arrFound, 2);
			
			return($href);
		}
		
		/**
		 * convert handle to title
		 */
		public static function convertHandleToTitle($handle){
			
			$title = str_replace("_", " ", $handle);
			$title = ucwords($title);
			
			return($title);
		}
		
		
		/**
		 * truncate html
		 */
		public static function truncateHTML($maxLength, $html){
			
	        mb_internal_encoding("UTF-8");
	
	        $printedLength = 0;
	        $position = 0;
	        $tags = array();
			
	        ob_start();
	
	        while ($printedLength < $maxLength && preg_match('{</?([a-z]+)[^>]*>|&#?[a-zA-Z0-9]+;}', $html, $match, PREG_OFFSET_CAPTURE, $position)){
	
	            list($tag, $tagPosition) = $match[0];
	
	            // Print text leading up to the tag.
	            $str = mb_strcut($html, $position, $tagPosition - $position);
	
	            if ($printedLength + mb_strlen($str) > $maxLength){
	                print(mb_strcut($str, 0, $maxLength - $printedLength));
	                $printedLength = $maxLength;
	                break;
	            }
	
	            print($str);
	            $printedLength += mb_strlen($str);
	
	            if ($tag[0] == '&'){
	                // Handle the entity.
	                print($tag);
	                $printedLength++;
	            }
	            else{
	                // Handle the tag.
	                $tagName = $match[1][0];
	                if ($tag[1] == '/'){
	                    // This is a closing tag.
	
	                    $openingTag = array_pop($tags);
	                    assert($openingTag == $tagName); // check that tags are properly nested.
	
	                    print($tag);
	                }
	                else if ($tag[mb_strlen($tag) - 2] == '/'){
	                    // Self-closing tag.
	                    print($tag);
	                }
	                else{
	                    // Opening tag.
	                    print($tag);
	                    $tags[] = $tagName;
	                }
	            }
	
	            // Continue after the tag.
	            $position = $tagPosition + mb_strlen($tag);
	        }
	
	        // Print any remaining text.
	        if ($printedLength < $maxLength && $position < mb_strlen($html))
	            print(mb_strcut($html, $position, $maxLength - $printedLength));
	
	        // Close any open tags.
	        while (!empty($tags))
	             printf('</%s>', array_pop($tags));
	
			
	        $bufferOuput = ob_get_contents();
	
	        ob_end_clean();         
	
	        $html = $bufferOuput;   
	
	        return $html;   
	
	    }		
		
	    /**
	     * check if string ends with some other string
	     */
		public static function isStringEndsWith( $haystack, $needle ) {
			
			if(empty($haystack))
				return(false);
			
		    $length = strlen( $needle );
		    if( !$length )
		        return true;
		       
		    return substr( $haystack, -$length ) === $needle;
		}	    
	    
		/**
		 * replace only first occurance in a string
		 */
		public static function str_replace_first($search, $replace, $subject){
		    $pos = strpos($subject, $search);
		    if ($pos === false)
		    	return($subject);
		    
		    $newStr = substr_replace($subject, $replace, $pos, strlen($search));
		    
		    return $newStr;
		}			
		
		/**
		 * get domain host, cut the extension
		 */
		public static function getDomainWithoutExtension($domain){
			
			if(empty($domain))
				return($domain);
			
			$result = preg_split('/(?=\.[^.]+$)/', $domain);			
						
			if(empty($result))
				return($domain);
			
			$host = $result[0];
			
			if(empty($host))
				return($domain);
			
			return($host);
		}

		
		public static function z__________URLS__________(){}
		
		/**
		 *
		 * get url contents
		 */
		public static function getUrlContents($url){
			
			$options = array("timeout"=>GlobalsDOUBLY::PASTE_OPERATION_TIMEOUT);
			
			$response = wp_remote_get($url, $options);
			
			if(empty($response))
				throw new Exception("getUrlContents Request failed");
			
			
			$isError = is_wp_error($response);
			
			if($isError == true){
				
				$message = $response->get_error_message();
				
				throw new Exception($message);
			}
			
			
			$code = wp_remote_retrieve_response_code( $response );
			
			$body = wp_remote_retrieve_body( $response );
						
			if($code == 403){		//forbidden
			
				$urlBase = UniteFunctionsDOUBLY::getBaseUrl($url);
				
				$messageText = "Request Failed: $code, please check your hosting firewall accesable to request url: $urlBase with PHP. Or just try again. <br>";
				
				$messsage = $messageText . $body;
				
				throw new Exception($messsage);
			}
			
			if($code != 200)
				throw new Exception("Request failed, code: $code");
			
			
			return($body);
		}
		
		
		/**
		 * convert url to handle
		 *
		 */
		public static function urlToHandle($url = ''){
						
			// Replace all weird characters with dashes
			$url = preg_replace('/[^\w\-'. '~_\.' . ']+/u', '-', $url);
		
			// Only allow one dash separator at a time (and make string lowercase)
			return mb_strtolower(preg_replace('/--+/u', '-', $url), 'UTF-8');
		}
		
		
		/**
		 * add params to url
		 */
		public static function addUrlParams($url, $params, $addOnlyNewParam = false){
			
			if(empty($params))
				return($url);
			
			if(strpos($url, "?") !== false){
				if($addOnlyNewParam == true)
					return($url);
					
				$url .= "&";
			}
			else
				$url .= "?";
						
			if(is_array($params)){
				
				$strParams = "";
				foreach($params as $key=>$value){
					if(!empty($strParams))
						$strParams .= "&";
					
					$strParams .= $key."=".urlencode($value);
				}
				
				$params = $strParams;
			}
			
			$url .= $params;
			
			
			return($url);
		}
		
		
		/**
		 * convert url to https if needed
		 */
		public static function urlToSsl($url){
			
			$url = str_replace("http://", "https://", $url);
			$url = str_replace("HTTP://", "HTTPS://", $url);
			
			return($url);
		}
		
		
		/**
		 * clean url - remove double slashes
		 */
		public static function cleanUrl($url){
			
			$url = preg_replace('/([^:])(\/{2,})/', '$1/', $url);
			
			return($url);
		}
		
		/**
		 * get base url from any url
		 */
		public static function getBaseUrl($url){
			
			$arrUrl = parse_url($url);
			
			$scheme = UniteFunctionsDOUBLY::getVal($arrUrl, "scheme","http");
			$host = UniteFunctionsDOUBLY::getVal($arrUrl, "host");
			$path = UniteFunctionsDOUBLY::getVal($arrUrl, "path");
			
			$url = "{$scheme}://{$host}{$path}";
			
			return($url);
		}
		
		
		public static function z___________VALIDATIONS_________(){}
		
		/**
		 * validate that the value is in array
		 */
		public static function validateObjectMethod($object, $strMethod, $objectName){
			
			if(method_exists($object, "initByID") == false)
				UniteFunctionsDOUBLY::throwError("Object: $objectName don't have method $strMethod");
			
		}
		
		
		/**
		 * validate that the value is in array
		 */
		public static function validateValueInArray($value, $valueTitle, $arr){
			
			if(is_array($arr) == false)
				self::throwError("array of $valueTitle should be array");
			
			if(array_search($value, $arr) === false)
				self::throwError("wrong $value, should be: ".implode(",", $arr));
			
		}
		
		
		/**
		 * 
		 * validate that some file exists, if not - throw error
		 */
		public static function validateFilepath($filepath,$errorPrefix=null){
			
			if(file_exists($filepath) == true && is_file($filepath) == true)
				return(false);
			
			if($errorPrefix == null)
				$errorPrefix = "File";
			
			
			$message = $errorPrefix." $filepath not exists!";
			
			self::throwError($message);
		}
		
		
		/**
		 *
		 * validate that some directory exists, if not - throw error
		 */
		public static function validateDir($pathDir, $errorPrefix=null){
			if(is_dir($pathDir) == true)
				return(false);
			
			if($errorPrefix == null)
				$errorPrefix = "Directory";
			$message = $errorPrefix." $pathDir not exists!";
			self::throwError($message);
		}
		
		//--------------------------------------------------------------
		//validate if some directory is writable, if not - throw a exception
		private static function validateWritable($name,$path,$strList,$validateExists = true){
		
			if($validateExists == true){
				//if the file/directory doesn't exists - throw an error.
				if(file_exists($path) == false)
					throw new Exception("$name doesn't exists");
			}
			else{
				//if the file not exists - don't check. it will be created.
				if(file_exists($path) == false) return(false);
			}
		
			if(is_writable($path) == false){
				chmod($path,0755);		//try to change the permissions
				if(is_writable($path) == false){
					$strType = "Folder";
					if(is_file($path)) $strType = "File";
					$message = "$strType $name is doesn't have a write permissions. Those folders/files must have a write permissions in order that this application will work properly: $strList";
					throw new Exception($message);
				}
			}
		}
		
		
		/**
		 * 
		 * validate that some value is numeric
		 */
		public static function validateNumeric($val,$fieldName=""){
			self::validateNotEmpty($val,$fieldName);
			
			if(empty($fieldName))
				$fieldName = "Field";
			
			if(!is_numeric($val))
				self::throwError("$fieldName should be numeric ");
		}
		
		/**
		 * 
		 * validate that some variable not empty
		 */
		public static function validateNotEmpty($val,$fieldName=""){
			
			if(empty($fieldName))
				$fieldName = "Field";
				
			if(empty($val) && is_numeric($val) == false)
				self::throwError("Field <b>$fieldName</b> should not be empty");
		}
		
		
		/**
		 * validate that the field don't have html tags
		 */
		public static function validateNoTags($val, $fieldName=""){
			
			if($val == strip_tags($val))
				return(true);
			
			if(empty($fieldName))
				$fieldName = "Field";
			
			self::throwError("Field <b>$fieldName</b> should not contain tags");
		}
		
		/**
		 * validate sign not exists
		 */
		public static function validateCharNotExists($str, $sign, $objectName){
			
			if(strpos($str, $sign) !== false)
				self::throwError("{$objectName} doesn't allow & signs");
			
		}
		
		
		/**
		 * check the php version. throw exception if the version beneath 5
		 */
		private static function validatePHPVersion(){
			$strVersion = phpversion();
			$version = (float)$strVersion;
			if($version < 5) 
				self::throwError("You must have php5 and higher in order to run the application. Your php version is: $version");
		}
		
		
		/**
		 * valiadte if gd exists. if not - throw exception
		 * @throws Exception
		 */
		public static function validateGD(){
			if(function_exists('gd_info') == false)
				throw new Exception("You need PHP GD library to operation. Please turn it on in php.ini");
		}
		
		
		/**
		 * return if the variable is alphanumeric
		 */
		public static function isAlphaNumeric($val){
			$match = preg_match('/^[\w_]+$/', $val);
			
			if($match == 0)
				return(false);
			
			return(true);
		}
		
		/**
		 * validate id's list, allowed only numbers and commas
		 * @param $val
		 */
		public static function validateIDsList($val, $fieldName=""){
			
			if(empty($val))
				return(true);
			
			$match = preg_match('/^[0-9,]+$/', $val);
			
			if($match == 0)
				self::throwError("Field <b>$fieldName</b> allow only numbers and comas.");
				
		}
		
		/**
		 * validate id's list, allowed only numbers and commas
		 * @param $val
		 */
		public static function isIDsListString($val){
						
			if(empty($val))
				return(false);
			
			if(is_array($val) == true)
				return(false);
			
			$match = preg_match('/^[0-9,]+$/', $val);
			
			if($match == 0)
				return(false);
				
			$arrIDs = explode(",", $val);
			
			if(count($arrIDs) == 1)
				return(false);
				
			return(true);
		}
		
		
		/**
		 * return if the array is id's array
		 */
		public static function isValidIDsArray($arr){
			
			if(is_array($arr) == false)
				return(false);
			
			if(empty($arr))
				return(true);
			
			foreach($arr as $key=>$value){
				
				if(is_numeric($key) == false || is_numeric($value) == false)
					return(false);
			}

			return(true);
		}
		
		
		/**
		 * validate that the value is alphanumeric
		 * underscores also alowed
		 */
		public static function validateAlphaNumeric($val, $fieldName=""){
			
			if(empty($fieldName))
				$fieldName = "Field";
			
			if(self::isAlphaNumeric($val) == false)
				self::throwError("Field <b>$fieldName</b> allow only english words, numbers and underscore.");
		
		}
		
		
		/**
		 * validate url alias
		 */
		public static function validateUrlAlias($alias, $fieldName=""){
			
			if(empty($fieldName))
				$fieldName = "Field";
						
			self::validateNotEmpty($alias, $fieldName);
			
			$url = "http://example.com/".$alias;
			$isValid = filter_var($url, FILTER_VALIDATE_URL);
			
			if($isValid == false)
				self::throwError("Field <b>$fieldName</b> allow only words, numbers hypens and underscores.");
			
			//if(self::isAlphaNumeric($val) == false)
				//self::throwError("Field <b>$fieldName</b> allow only english words, numbers and underscore.");
		}
		
		
		/**
		 * validate email field
		 */
		public static function validateEmail($email, $fieldName="email"){
			
			$isValid = self::isEmailValid($email);
			
			if($isValid == true)
				return(false);
				
			self::throwError(__("The $fieldName is not valid", "unlimited-elements-for-elementor"));
			
		}
		
		
	    /**
	     * return true/false if the email is valid
	     */
	    public static function isEmailValid($email) {
	        return preg_match('/^[^@]+@[a-zA-Z0-9._-]+\.[a-zA-Z]+$/', $email);
	    }
		
	    /**
	     * check if html valid, get errors list
	     */
		public static function validateHTML($string){
			
		    $start = strpos($string, '<');
		    $end = strrpos($string, '>', $start);
		
		    if ($end !== false) {
		        $string = substr($string, $start);
		    } else {
		        $string = substr($string, $start, strlen($string) - $start);
		    }
			
		    // xml requires one root node
		    $string = "<div>$string</div>";
			
		    libxml_use_internal_errors(true);
		    libxml_clear_errors();
		    simplexml_load_string($string);
			$arrErrors = libxml_get_errors();
			
			
		    return $arrErrors;
		}
		
		public static function z________FILE_SYSTEM________(){}
		
		
		
		/**
		 *
		 * if directory not exists - create it
		 * @param $dir
		 */
		public static function checkCreateDir($dir){
			if(!is_dir($dir))
				mkdir($dir);
		}

		
		/**
		 * make directory and validate that it's exists
		 */
		public static function mkdirValidate($path, $dirName){
			
			if(is_dir($path) == false){
				@mkdir($path);
				if(!is_dir($path))
					UniteFunctionsDOUBLY::throwError("$dirName path: {$path} could not be created. Please check your permissions");
			}
		
		}
		
		
		/**
		 * get path info of certain path with all needed fields
		 */
		public static function getPathInfo($filepath){
			$info = pathinfo($filepath);
		
			//fix the filename problem
			if(!isset($info["filename"])){
				$filename = $info["basename"];
				if(isset($info["extension"]))
					$filename = substr($info["basename"],0,(-strlen($info["extension"])-1));
				$info["filename"] = $filename;
			}
		
			return($info);
		}
		
		
		/**
		 * get filename extention
		 */
		public static function getFilenameNoExtension($filepath){
			
			$info = self::getPathInfo($filepath);
			$filename = self::getVal($info, "filename");
			return($filename);
		}
		
		/**
		 * get filename extention
		 */
		public static function getFilenameExtension($filepath){
			$info = self::getPathInfo($filepath);
			$ext = self::getVal($info, "extension");
			return($ext);
		}
		
		/**
		 * write file if it's not exists
		 */
		public static function writeFileIfNotExists($str, $filepath){
			
			if(file_exists($filepath) == true)
				return(false);
			
			self::writeFile($str, $filepath);
		}
		
		/**
		 * 
		 * save some file to the filesystem with some text
		 */
		public static function writeFile($str, $filepath){
			
			if(is_array($str))
				UniteFunctionsDOUBLY::throwError("write file should accept only string in file: ". $filepath);
			
			$fp = fopen($filepath,"w+");
			fwrite($fp,$str);
			fclose($fp);
		}
		
		
		/**
		 *
		 * get list of all files in the directory
		 */
		public static function getFileList($path){
			$dir = scandir($path);
			$arrFiles = array();
			foreach($dir as $file){
				if($file == "." || $file == "..") continue;
				$filepath = $path . "/" . $file;
				if(is_file($filepath)) $arrFiles[] = $file;
			}
			return($arrFiles);
		}

		/**
		 * get path size
		 */
		public static function getPathSize($path){
			
			if(empty($path))
				return(0);
			
			if(is_dir($path) == false)
				return(0);
				
			$arrFiles = self::getFileListTree($path);
			
			if(empty($arrFiles))
				return(0);
			
			$totalSize = 0;
			
			foreach($arrFiles as $pathFile){
				
				if(is_file($pathFile) == false)
					continue;
					
				$fileSize = filesize($pathFile);
				
				$totalSize += $fileSize;
			}
			
			return($totalSize);
		}
		
		/**
		 * get recursive file list inside folder and subfolders
		 */
		public static function getFileListTree($path, $filetype = null, $arrFiles = null){
			
			if(empty($arrFiles))
				$arrFiles = array();
			
			if(is_dir($path) == false)
				return($arrFiles);
			
			$path = self::addPathEndingSlash($path);
			
			$arrPaths = scandir($path);
			foreach($arrPaths as $file){
				if($file == "." || $file == "..")
					continue;
				
				$filepath = $path.$file;
				
				if(is_dir($filepath)){
					//add dirs
					if(is_array($filetype) && array_search("dir", $filetype) !== false || !is_array($filetype) && $filetype == "dir")
						$arrFiles[] = $filepath;
					$arrFiles = self::getFileListTree($filepath, $filetype, $arrFiles);
				}

				$info = pathinfo($filepath);
				
				$ext = self::getVal($info, "extension");
				$ext = strtolower($ext);
				
				if(!empty($filetype) && is_array($filetype) && array_search($ext, $filetype) === false){
					continue;
				}
				if(!empty($filetype) && is_array($filetype) == false && $filetype != $ext)
					continue;
				
				$arrFiles[] = $filepath;
			}
			
			
			return($arrFiles);
		}
		
		
		/**
		 *
		 * get list of all directories in the directory
		 */
		public static function getDirList($path){
			$arrDirs = scandir($path);
		
			$arrFiles = array();
			foreach($arrDirs as $dir){
				if($dir == "." || $dir == "..")
					continue;
				$dirpath = $path . "/" . $dir;
		
				if(is_dir($dirpath))
					$arrFiles[] = $dir;
			}
		
			return($arrFiles);
		}
		
		
		/**
		 *
		 * clear debug file
		 */
		public static function clearDebug($filepath = "debug.txt"){
		
			if(file_exists($filepath))
				@unlink($filepath);
		}
		
		/**
		 *
		 * save to filesystem the error
		 */
		public static function writeDebugError(Exception $e,$filepath = "debug.txt"){
			$message = $e->getMessage();
			$trace = $e->getTraceAsString();
		
			$output = $message."\n";
			$output .= $trace."\n";
		
			$fp = fopen($filepath,"a+");
			fwrite($fp,$output);
			fclose($fp);
		}
		
		
		//------------------------------------------------------------
		//save some file to the filesystem with some text
		public static function addToFile($str,$filepath){
			$fp = fopen($filepath,"a+");
			fwrite($fp,"---------------------\n");
			fwrite($fp,$str."\n");
			fclose($fp);
		}
		
		
		/**
		 * delete folder contents that older then some seconds from now
		 */
		public static function clearDirByTime($path, $olderThenSeconds = 240,$isDebug = false){
			
			if(is_dir($path) == false)
				return(false);
			
			$arrPaths = scandir($path);
			
			if($isDebug == true){
				dmp("----- DEBUG FILES DELETE --------");
				
				dmp("files to delete: ");
				dmp($arrPaths);
			}
				
			$currentTime = time();
			
			if($isDebug){
				
				$strTime = UniteFunctionsDOUBLY::timestamp2DateTime($currentTime);
				dmp("current time: $strTime");
				
				dmp("delete path: $path");
			}
			
			foreach($arrPaths as $file){
				
				if($file == "." || $file == "..")
					continue;
					
				$filepath = realpath($path."/".$file);
				
				if($file == "index.html")
					continue;
				
				$filetime = filemtime($filepath);
				
				$diff = $currentTime-$filetime;
				
				if($isDebug == true){
					
					$strFiletime = UniteFunctionsDOUBLY::timestamp2DateTime($filetime);
					
					dmp("-------------");
					dmp("file: $file, filetime: $filetime , $strFiletime");
				}
				
				//skip if not much time left
				if($diff < $olderThenSeconds){
					
					if($isDebug == true){
						dmp("not deleted: ".$file." time passed: $diff sec.");
					}
					
					$arrNotDeleted[] = array($diff,$file);
					continue;
				}
				
				if($isDebug){
					dmp("delete file: $file time passed: $diff sec.");
				}
				
				self::deleteDir($filepath, true);
			}
			
			if($isDebug == true)
				dmp("-------- END DEBUG FILES DELETE ---------");
			
		}
		
		
		/**
		 *
		 * recursive delete directory or file
		 */
		public static function deleteDir($path,$deleteOriginal = true, $arrNotDeleted = array(),$originalPath = "", $params = array()){
			
			$olderSec = self::getVal($params, "olderthen");
			
			if(!empty($olderSec))
				$currentTime = time();
			
			if(empty($originalPath))
				$originalPath = $path;
		
			//in case of paths array
			if(getType($path) == "array"){
				$arrPaths = $path;
				
				foreach($path as $singlePath)
					$arrNotDeleted = self::deleteDir($singlePath,$deleteOriginal,$arrNotDeleted,$originalPath,$params);
				
				return($arrNotDeleted);
			}
			
			if(!file_exists($path))
				return($arrNotDeleted);
		
			// delete file
			if(is_file($path)){
				
				//check by time
				if(!empty($olderSec)){
					
					$filetime = filemtime($path);
					$diff = $currentTime-$filetime;
					
					//skip if not much time left
					if($diff < $olderSec){
						$arrNotDeleted[] = $path;
						return($arrNotDeleted);
					}
				}
				
				$deleted = @unlink($path);
				if(!$deleted)
					$arrNotDeleted[] = $path;
				
				return($arrNotDeleted);
			}
			
			//delete directory
			
			$arrPaths = scandir($path);
						
			foreach($arrPaths as $file){
				if($file == "." || $file == "..")
					continue;
				$filepath = realpath($path."/".$file);
				$arrNotDeleted = self::deleteDir($filepath,$deleteOriginal,$arrNotDeleted,$originalPath,$params);
			}
			
			if($deleteOriginal == true || $originalPath != $path){
				
				//check by time
				if(!empty($olderSec)){
					
					$filetime = filemtime($path);
					$diff = $currentTime-$filetime;
					
					//skip if not much time left
					if($diff < $olderSec){
						$arrNotDeleted[] = $path;
						return($arrNotDeleted);
					}
					
				}
				
				
				$deleted = @rmdir($path);
				if(!$deleted)
					$arrNotDeleted[] = $path;
			}
		
		
			return($arrNotDeleted);
		}
		
		
		/**
		 * copy directory contents to another directory
		 */
		public static function copyDir($src, $dst) {
			$dir = opendir($src);
			@mkdir($dst);
			while(false !== ( $file = readdir($dir)) ) {
				if (( $file != '.' ) && ( $file != '..' )) {
					if ( is_dir($src . '/' . $file) ) {
						self::copyDir($src . '/' . $file,$dst . '/' . $file);
					}
					else {
						copy($src . '/' . $file,$dst . '/' . $file);
					}
				}
			}
			closedir($dir);
		}		
		
		
		/**
		 * add ending to the path
		 */
		public static function addPathEndingSlash($path){
			
			$slashType = (strpos($path, '\\')===0) ? 'win' : 'unix';
			
			$lastChar = substr($path, strlen($path)-1, 1);
		
			if ($lastChar != '/' && $lastChar != '\\')
				$path .= ($slashType == 'win') ? '\\' : '/';
		
			return($path);
		}
		
		
		/**
		 * remove path ending slash
		 */
		public static function removePathEndingSlash($path){
			$path = rtrim($path, "/");
			$path = rtrim($path,"\\");
			
			return($path);
		}
		
		
		/**
		 * convert path to unix format slashes
		 */
		public static function pathToUnix($path){
			$path = str_replace('\\', '/', $path);
			$path = preg_replace('/\/+/', '/', $path); // Combine multiple slashes into a single slash
			
			return($path);
		}
		
		
		/**
		 * convert path to relative path, based on basepath
		 */
		public static function pathToRelative($path, $basePath){
			
			$path = str_replace($basePath, "", $path);
			$path = ltrim($path, '/');
			return($path);
		}
		
		/**
		 * join paths
		 * @param $path
		 */
		public static function joinPaths($basePath, $path){
			
			$newPath = $basePath."/".$path;
			$newPath = self::pathToUnix($newPath);
			return($newPath);
		}
		
		
		/**
		 * turn path to realpath
		 * output only unix format, if not found - return ""
		 * @param $path
		 */
		public static function realpath($path, $addEndingSlash = true){
			
			$path = realpath($path);
			if(empty($path))
				return($path);
			
			$path = self::pathToUnix($path);
			
			if(is_dir($path) && $addEndingSlash == true)
				$path .= "/";
			
			return($path);
		}
		
		
		/**
		 * check if path under base path
		 */
		public static function isPathUnderBase($path, $basePath){
			$path = self::pathToUnix($path);
			$basePath = self::pathToUnix($basePath);
			
			if(strpos($path, $basePath) === 0)
				return(true);
			
			return(false);
		}
		
		
		/**
		 * find free filepath for copying. adding numbers at the end
		 * check filesize, if it's the same file, then return it.
		 */
		public static function findFreeFilepath($path, $filename, $filepathSource = null){
			
			//check if file exists
			$filepath = $path.$filename;
			if(file_exists($filepath) == false)
				return($filename);
			
			//check sizes
			$checkSizes = false;
			if(!empty($filepathSource)){
				$checkSizes = true;
				$sizeSource = filesize($filepathSource);
				
				$sizeDest = filesize($filepath);
				if($sizeSource == $sizeDest)
					return($filename);
			}
				
			
			//prepare file data
			$info = pathinfo($filename);
			$basename = $info["filename"];
			$ext = $info["extension"];
			
			//make new available filename
			$counter = 0;
			$textPortion = self::getStringTextPortion($basename);
			if(empty($textPortion))
				$textPortion = $basename."_";
			
			do{
				$counter++;
				$filename = $textPortion.$counter.".".$ext;
				$filepath = $path.$filename;
				$isFileExists = file_exists($filepath);
				
				if($isFileExists == true && $checkSizes == true){
					$sizeDest = filesize($filepath);
					if($sizeSource == $sizeDest)
						return($filename);
				}
				
			}while($isFileExists == true);
			
			
			return($filename);
		}
		
		public static function z__________SESSIONS_______(){}
		
		/**
		 * get session var
		 */
		public static function getSessionVar($name, $base){
			
			if(empty($base))
				UniteFunctionsDOUBLY::throwError("Can't get session var without the base");
			
			if(!isset($_SESSION))
				return("");
			
			$arrBase = UniteFunctionsDOUBLY::getVal($_SESSION, $base);
			
			if(empty($arrBase))
				return("");
			
			$value = UniteFunctionsDOUBLY::getVal($_SESSION, $name);
			
			return($value);
		}
		
		/**
		 * set session value
		 */
		public static function setSessionVar($name, $value, $base){
			
			if(empty($base))
				UniteFunctionsDOUBLY::throwError("Can't set session var without the base");

			if(!isset($_SESSION[$base]))
				$_SESSION[$base] = array();
				
			$_SESSION[$base][$name] = $value;
		}
		
		
		/**
		 * clear session var
		 */
		public static function clearSessionVar($name, $base){
			
			
			if(!isset($_SESSION[$base]))
				return(false);
			
			$_SESSION[$base][$name] = null;
			unset($_SESSION[$base][$name]);
		}
		
		
		public static function z___________OTHERS__________(){}

		
		
		/**
		 * encode svg to bg image url
		 */
		public static function encodeSVGForBGUrl($svgContent){
			
			if(empty($svgContent))
				return("");
				
			$urlBG = "data:image/svg+xml;base64,".base64_encode($svgContent);
			
			return($urlBG);
		}
		
		
		/**
		 * get amount of memory limit in bytes
		 */
		public static function getPHPMemoryLimit(){
			
			if(isset(self::$arrCache["memory_limit"]))
				return(self::$arrCache["memory_limit"]);
			
			$memory_limit = ini_get("memory_limit");
				
			$found = preg_match('/^(\d+)(.)$/', $memory_limit, $matches);
			
			if(!$found)
				return(null);

			$numLimit = $matches[1];
			$letter = $matches[2];
			
			switch($letter){
				case "M":
			        $memory_limit = $numLimit * 1024 * 1024; 
				break;
				case "G":
			        $memory_limit = $numLimit * 1024 * 1024 * 1024; 
				break;
				case "K":
			        $memory_limit = $numLimit * 1024; 
				break;
			}
			
			self::$arrCache["memory_limit"] = $memory_limit;
			
			return($memory_limit);
		}
		
		
		/**
		 * return if the memory running off
		 */
		public static function isEnoughtPHPMemory($mbReserve = 32){
			
			$limit = self::getPHPMemoryLimit();
			if(empty($limit))
				return(true);
			
			//left this reserve
			$reserve = $mbReserve * 1024 * 1024;
			$maxReserve = $limit*0.3;
			
			if($reserve > $maxReserve);
				$reserve = $maxReserve;
			
			$available = $limit - $reserve;
						
			$used = memory_get_usage();
			
			/*
			dmp(number_format($available));
			dmp(number_format($used));
			dmp("-----------");
			*/
			
			if($used > $available)
				return(false);
			
			return(true);
		}
		
		
		//---------------------------------------------------------------------------------------------------
		// convert timestamp to time string
		public static function timestamp2Time($stamp){
			$strTime = date("H:i",$stamp);
			return($strTime);
		}
		
		/**
		 * convert timestamp to date and time string
		 */
		public static function timestamp2DateTime($stamp){
			$strDateTime = date("d M Y, H:i",$stamp);
			return($strDateTime);
		}
		
		//---------------------------------------------------------------------------------------------------
		// convert timestamp to date string
		public static function timestamp2Date($stamp){
			$strDate = date("d M Y",$stamp);	//27 Jun 2009
			return($strDate);
		}
		
		
		/**
		 * 
		 * strip slashes from textarea content after ajax request to server
		 */
		public static function normalizeTextareaContent($content){
			if(empty($content))
				return($content);
			$content = stripslashes($content);
			$content = trim($content);
			return($content);
		}
		
				
		/**
		 * Download Image
		 */
		public function downloadImage($filepath, $filename, $mimeType=""){
			$contents = file_get_contents($filepath);
			$filesize = strlen($contents);
		
			if($mimeType == ""){
				$info = UniteFunctionsDOUBLY::getPathInfo($filepath);
				$ext = $info["extension"];
				$mimeType = "image/$ext";
			}
		
			header("Content-Type: $mimeType");
			header("Content-Disposition: attachment; filename=\"$filename\"");
			header("Content-Length: $filesize");
			echo UniteFunctionsDOUBLY::escapeField($contents);
			exit();
		}
		
		
		/**
		 * download text file
		 */
		public static function downloadTextFile($text, $filename){
			
			$filesize = strlen($text);
			
			header("Content-Type: text");
			header("Content-Disposition: attachment; filename=\"$filename\"");
			header("Content-Length: $filesize");
			echo UniteFunctionsDOUBLY::escapeField($text);
			exit();
			
		}
		
		
		/**
		 * send file to download
		 */
		public static function downloadFile($filepath, $filename = null){
			
			UniteFunctionsDOUBLY::validateFilepath($filepath,"export file");
			
			if(empty($filename))
				$filename = basename($filepath);
			
			header('Content-Description: File Transfer');
			header('Content-Type: application/octet-stream');
			header('Content-Disposition: attachment; filename="'.$filename.'"');
			header('Expires: 0');
			header('Cache-Control: must-revalidate');
			header('Pragma: public');
			header('Content-Length: ' . filesize($filepath));
			readfile($filepath);
			exit();
		}

		/**
		 * escape some field
		 */
		public static function escapeField($var){
			
			return($var);
		}
		
		/**
		 *
		 * convert string to boolean
		 */
		public static function strToBool($str){
			if(is_bool($str))
				return($str);
		
			if(empty($str))
				return(false);
		
			if(is_numeric($str))
				return($str != 0);
		
			$str = strtolower($str);
			if($str == "true")
				return(true);
		
			return(false);
		}
		
		/**
		 * bool to str
		 */
		public static function boolToStr($bool){
			$bool = self::strToBool($bool);
			
			if($bool == true)
				return("true");
			else
				return("false");
		}
		
		
		//------------------------------------------------------------
		// get black value from rgb value
		public static function yiq($r,$g,$b){
			return (($r*0.299)+($g*0.587)+($b*0.114));
		}
		
		
		/**
		 * check if empty color string
		 */
		public static function isEmptyColorString($color){
			$color = trim($color);
			
			if(empty($color))
				return(true);
				
			$color = strtolower($color);
			if(strpos($color, "nan") !== false)
				return(true);
			
			return(false);
		}
		
		/**
		 * sanitize color string
		 */
		public static function sanitizeColorString($color){
			
			if(self::isEmptyColorString($color) == true)
				return("");
			
			return($color);
		}
		
		/**
		 * convert colors to rgb
		 */ 
		public static function html2rgb($color){
			
			if(empty($color))
				return(false);
			
			if ($color[0] == '#')
				$color = substr($color, 1);
			if (strlen($color) == 6)
				list($r, $g, $b) = array($color[0].$color[1],
						$color[2].$color[3],
						$color[4].$color[5]);
			elseif (strlen($color) == 3)
			list($r, $g, $b) = array($color[0].$color[0], $color[1].$color[1], $color[2].$color[2]);
			else
				return false;
			$r = hexdec($r); $g = hexdec($g); $b = hexdec($b);
			return array($r, $g, $b);
		}
		
		/**
		 * 
		 *turn some object to string
		 */
		public static function toString($obj){
			return(trim((string)$obj));
		}

		
		/**
		 * 
		 * remove utf8 bom sign
		 * @return string
		 */
		public static function remove_utf8_bom($content){
			$content = str_replace(chr(239),"",$content);
			$content = str_replace(chr(187),"",$content);
			$content = str_replace(chr(191),"",$content);
			$content = trim($content);
			return($content);
		}
		
		
		/**
		 * print the path to this function
		 */
		public static function printPath(){
			
			try{
				throw new Exception("We are here");
			}catch(Exception $e){
				dmp($e->getTraceAsString());
				exit();
			}
			
		}
		
		/**
		 * return if the url coming from localhost
		 */
		public static function isLocal(){
			
			if(isset($_SERVER["HTTP_HOST"]) && $_SERVER["HTTP_HOST"] == "localhost")
				return(true);
			
			return(false);
		}
		
		/**
		 * redirect to some url
		 */
		public static function redirectToUrl($url){
			
			header("location: $url");
			exit();
		}
		
	}
	
?>