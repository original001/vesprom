<?php
//database connection settings
define('DBMS', 'mysql');                      // database system  
define('DB_HOST', 'localhost');       // database host    
define('DB_USER', 'vtolst_root');   // username         
define('DB_PASS', 'vFrZOr6aL');   // password         
define('DB_NAME', 'vtolst_root');       // database name    
define('DB_PRFX', 'envi_');     // database prefix  

// include table name file
include('core/config/tables.inc.php');
define('ALTERNATEPHP', '1');
?><?php
#####################################
# ShopCMS: Скрипт интернет-магазина
# Copyright (c) by ADGroup
# http://shopcms.ru
#####################################

//this file indicates listing of all available languages

class Language
{
        var $description; //language name
        var $filename; //language PHP constants file
        var $template; //template filename
}

        //a list of languages
        $lang_list = array();

        //to add new languages add similiar structures

        $lang_list[0] = new Language();
        $lang_list[0]->description = "Русский";
        $lang_list[0]->filename = "russian.php";
        $lang_list[0]->iso2 = "ru";
?><?php
define( 'DATABASE_STRUCTURE_XML_PATH',  'core/config/database_structure.xml' );
define( 'RESULT_XML_PATH', 'core/config/result.xml' );
define( 'TABLES_INC_PHP_PATH', 'core/config/tables.inc.php' );
define( 'CONNECT_INC_PHP_PATH', 'core/config/connect.inc.php' );
?><?php
#####################################
# ShopCMS: Скрипт интернет-магазина
# Copyright (c) by ADGroup
# http://shopcms.ru
#####################################

require_once('core/classes/class.virtual.module.php');

/**
 * Parent for all rate calculators modules
 *
 */
class ShippingRateCalculator extends virtualModule {

        function ShippingRateCalculator($_ModuleConfigID = 0){

                $this->LanguageDir = 'core/modules/shipping/languages/';
                $this->ModuleType = SHIPPING_RATE_MODULE;
                $this->MethodsTable = SHIPPING_METHODS_TABLE;
                virtualModule::virtualModule($_ModuleConfigID);
        }

        function _getServiceType($_ServiceID){

                $ShippingTypes = $this->_getShippingTypes();
                foreach ($ShippingTypes as $_Type=>$_Services)
                        if(in_array($_ServiceID, $_Services))
                                return $_Type;
                return '';
        }

        function _convertDecLBStoPoundsOunces($_Dec){

                return array(
                        'lbs' => floor($_Dec),
                        'oz' => ceil(16*($_Dec - floor($_Dec))),
                );
        }

        /**
         * Return list of rates for services
         *
         * @param array $_Services
         * @param array $order
         * @param array $address
         */
        function _getRates(&$_Services,  $order, $address){

                $Query                 = $this->_prepareQuery($_Services,  $order, $address);
                $Answer                 = $this->_sendQuery($Query);
                $parsedAnswer         = $this->_parseAnswer($Answer);
                $newServices                 = array();

                $_TC                         = count($_Services);

                for ( $_ind=0; $_ind<$_TC; $_ind++ ){

                        $_Service = &$_Services[$_ind];
                        if(isset($parsedAnswer[$_Service['id']]))
                        foreach ($parsedAnswer[$_Service['id']] as $_indV=>$_Variant){

                                $newServices[] = array(
                                                'id' => sprintf("%02d%02d", $_Service['id'], $_indV),
                                                'name' => $_Variant['name'],
                                                'rate' => $_Variant['rate'],
                                        );
                        }
                }
                $_Services = $newServices;
        }

        /**
         * Return information by available shipping services
         * The same for all shipping modules
         *
         * @param array $order
         * @param array $address
         * @param integer $_shServiceID
         * @return array 'name'=>'<Service name>', 'id'=><Service ID>, 'rate'=>'<Service Rate>'
         */
        function calculate_shipping_rate($order, $address, $_shServiceID = 0){

                $_shServiceID = (int)$_shServiceID;
                if($_shServiceID>99){

                        if(strlen($_shServiceID)<4)$_shServiceID = sprintf("%04d", $_shServiceID);
                        $_orinServiceID = $_shServiceID;
                        list($_shServiceID, $_serviceOffset) = sscanf($_shServiceID, "%02d%02d");
                }
                $Rates = array();
                if($_shServiceID){

                        $AvailableServices = $this->getShippingServices();
                        $Rates[] = array(
                                'name'                 => (isset($AvailableServices[$_shServiceID]['name'])?$AvailableServices[$_shServiceID]['name']:''),
                                'code'                 => (isset($AvailableServices[$_shServiceID]['code'])?$AvailableServices[$_shServiceID]['code']:''),
                                'id'         => $_shServiceID,
                                'rate'                 => 0,
                                );
                }else {

                        $AvailableServices = $this->_getServicesByCountry($address['countryID']);
                        foreach ($AvailableServices as $_Service){

                                $_Service['rate'] = 0;
                                $Rates[] = $_Service;
                        }
                }

                $this->_getRates($Rates, $order, $address);

                if(isset($_orinServiceID)){

                        if(isset($Rates[$_serviceOffset])){
                                $Rates = array($Rates[$_serviceOffset]);
                        }else {
                                $Rates = array(array(
                                'name'                 => '',
                                'id'         => 0,
                                'rate'                 => 0,
                                ));
                        }
                }
                if(is_array($Rates) && !count($Rates)){
                                $Rates = array(array(
                                'name'                 => '',
                                'id'         => 0,
                                'rate'                 => 0,
                                ));
                }
                return $Rates;
        }

        #заглушка
        function allow_shipping_to_address(){

                return true;
        }

        /**
         * Convert from one Measurement to another Measurement
         *
         * @param unknown_type $_Units
         * @param unknown_type $_From
         * @param unknown_type $_To
         */
        function _convertMeasurement($_Units, $_From, $_To){

                switch (strtolower($_From).'_'.strtolower($_To)){

                        case 'lb_kg':
                        case 'lbs_kgs':
                        case 'lbs_kg':
                        case 'lb_kgs':
                                $_Units = $_Units/2.2046;
                                break;
                        case 'kg_lb':
                        case 'kg_lbs':
                        case 'kgs_lb':
                        case 'kgs_lbs':
                                $_Units = $_Units*2.2046;
                                break;
                        case 'g_lb':
                        case 'g_lbs':
                                $_Units = $_Units/1000*2.2046;
                                break;
                        case 'lb_g':
                        case 'lbs_g':
                                $_Units = $_Units/2.2046*1000;
                                break;
                        case 'g_kg':
                        case 'g_kgs':
                                $_Units = $_Units/1000;
                }

                return $_Units;
        }

        function _getOrderWeight(&$Order){

                $TC = count($Order['orderContent']['cart_content']);
                $OrderWeight = 0;
                $ShippingProducts = 0;

                for( $i = 0; $i<$TC; $i++ ){

                        $Product = GetProduct($Order['orderContent']['cart_content'][$i]['productID']);
                        if($Product['free_shipping'])continue;
                        $ShippingProducts++;
                        if(!isset($Product['weight']))continue;
                        if(!$Product['weight'])continue;
                        $OrderWeight += $Order['orderContent']['cart_content'][$i]['quantity']*$Product['weight'];
                }
                if($OrderWeight<=0 && $ShippingProducts)$OrderWeight=0.1;

                return $OrderWeight;
        }
		
        function _getOrderpSumm(&$Order){

                $TC = count($Order['orderContent']['cart_content']);
                $OrderpSumm = 0;
                $ShippingProducts = 0;

                for( $i = 0; $i<$TC; $i++ ){

                        $Product = GetProduct($Order['orderContent']['cart_content'][$i]['productID']);
                        if($Product['free_shipping'])continue;
                        $ShippingProducts++;
                        $OrderpSumm += $Order['orderContent']['cart_content'][$i]['quantity']*$Order['orderContent']['cart_content'][$i]['costUC'];
                }

                return $OrderpSumm;
        }

        function _getShippingProducts($_Order){

                $Products = array();
                $_TC = count($_Order['orderContent']['cart_content'])-1;
                for (; $_TC>=0;$_TC--){

                        if($_Order['orderContent']['cart_content'][$_TC]['free_shipping'])continue;
                        $Products[] = $_Order['orderContent']['cart_content'][$_TC];
                }
                return $Products;
        }

        /*
        abstract methods
        */

        /**
         * Return array of shipping types
         */
        function _getShippingTypes(){

                return array();
        }

        /**
         * Return services for country
         *
         * @param integer $_CountryID - country id
         */
        function _getServicesByCountry(){

                return $this->getShippingServices();
        }

        /**
         * Return list of shipping services
         *
         * @param string $_Type shipping type (Domestic, Inrenational)
         * @return array
         */
        function getShippingServices(){return array();}


        function _prepareQuery(&$_Services,  $order, $address){

                return $this->_prepareXMLQuery($_Services,  $order, $address);
        }

        function _sendQuery($_Query){

                return $this->_sendXMLQuery($_Query);
        }

        function _parseAnswer($_Answer){

                return $this->_parseXMLAnswer($_Answer);
        }

        function _sendXMLQuery(){

        }

        function _prepareXMLQuery(){
        }

        function _parseXMLAnswer(){;}
}
?><?php
#####################################
# ShopCMS: Скрипт интернет-магазина
# Copyright (c) by ADGroup
# http://shopcms.ru
#####################################

require_once('core/classes/class.virtual.module.php');

class PaymentModule extends virtualModule {

        function PaymentModule($_ModuleConfigID = 0){

                $this->LanguageDir = 'core/modules/payment/languages/';
                $this->ModuleType = PAYMENT_MODULE;
                $this->MethodsTable = PAYMENT_TYPES_TABLE;
                virtualModule::virtualModule($_ModuleConfigID);
        }


        // *****************************************************************************
        // Purpose        html form to get information from customer about payment,
        //                        this functions does not return <form> </form> tags - these tags are already defined in
        //                        the
        // Inputs
        // Remarks
        // Returns        nothing
        function payment_form_html()
        {
                return "";
        }

        // *****************************************************************************
        // Purpose        core payment processing routine
        // Inputs   $order is array with the following elements:
        //        "customer_email" - customer's email address
        //        "customer_ip" - customer IP address
        //        "order_amount" - total order amount (in conventional units)
        //        "currency_code" - currency ISO 3 code (e.g. USD, GBP, EUR)
        //        "currency_value" - currency exchange rate defined in the backend in 'Configuration' -> 'Currencies' section
        //        "shipping_info" - shipping information - array of the following data:
        //                "first_name", "last_name", "country_name", "state", "city", "address"
        //        "billing_info" - billing information - array of the following data:
        //                "first_name", "last_name", "country_name", "state", "city", "address"
        // Remarks
        function payment_process($order)
        {
                return 1;
        }

        // *****************************************************************************
        // Purpose        PHP code executed after order has been placed
        // Inputs
        // Remarks
        // Returns
        function after_processing_php($orderID)
        {
                return "";
        }

        // *****************************************************************************
        // Purpose        html code printed after order has been placed and after_processing_php
        //                         has been executed
        // Inputs
        // Remarks
        // Returns
        function after_processing_html( $orderID )
        {
                return "";
        }
}
?><?php
$NodeID = 0;

class xmlNodeX{
	
	var $ID;
	var $Name;
	var $Data;
	var $Attributes 	= array();
	/**
	 * Enter description here...
	 *
	 * @var xmlNodeX
	 */
	var $ParentNode;
	var $ChildNodes 	= array();
	var $ParserResource;
	var $parsingNode;
	
	function xmlNodeX($_Name = '', $_Attributes = array(), $_Data = '' ){
		
		$this->Name 		= $_Name;
		$this->Attributes 	= is_array($_Attributes)?$_Attributes:array();
		$this->Data 		= $_Data;
	}
	
	function getName(){
		
		return $this->Name;
	}
	
	function getAttribute($_Name){
		
		if (isset($this->Attributes[$_Name])) {
			
			return $this->Attributes[$_Name];
		}else return null;
	}
	
	function getAttributes(){
		
		return $this->Attributes;
	}
	
	function getData(){
		
		return $this->Data;
	}
	
	function &getChildNodes(){
		
		return $this->ChildNodes;
	}
	
	/**
	 * Create child node
	 *
	 * @param string $_Name
	 * @param array $_Attributes
	 * @param string $_Data
	 * @return xmlNodeX
	 */
	function &createChildNode($_Name, $_Attributes = array(), $_Data = ''){
		
		$_ChildNode = &new xmlNodeX($_Name, $_Attributes, $_Data);
		
		$this->addChildNode($_ChildNode);
		return $_ChildNode;
	}

	/**
	 * Create child node
	 *
	 * @param string $_Name
	 * @param array $_Attributes
	 * @param string $_Data
	 * @return xmlNodeX
	 */
	function &child($_Name, $_Attributes = array(), $_Data = ''){
		
		$child = &$this->createChildNode($_Name, $_Attributes, $_Data);
		return $child;
	}
		
	/**
	 * Enter description here...
	 *
	 * @param xmlNodeX $_ChildNode
	 */
	function addChildNode(&$_ChildNode){
		
		global $NodeID;
		$_ChildNode->ID = ++$NodeID;
		$_ChildNode->setParentNode($this);
		$this->ChildNodes[] = &$_ChildNode;
	}
	
	/**
	 * Set parent node
	 *
	 * @param unknown_type $ParentNode
	 */
	function setParentNode(&$ParentNode){
		
		$this->ParentNode = &$ParentNode;
	}
	
	/**
	 * Enter description here...
	 *
	 * @param xmlNodeX $NewParentNode
	 */
	function moveNode(&$NewParentNode){
		
		$OldParentNode = &$this->ParentNode;
		$OldParentNode->removeChildNode($this);

		$NewParentNode->addChildNode($this);
	}
	
	/**
	 * Remove child node
	 *
	 * @param xmlNodeX $RemoveNode
	 */
	function removeChildNode(&$RemoveNode){
		
		$TC = count($this->ChildNodes);
		for ($i=0;$i<$TC;$i++){
			
			$ChildNode = &$this->ChildNodes[$i];
			/* @var $ChildNode xmlNodeX */
			if($ChildNode->ID == $RemoveNode->ID){

				array_splice($this->ChildNodes, $i, 1);
				unset($RemoveNode->ParentNode);
				break;
			}
		}
	}
	
	/**
	 * Return parent node
	 *
	 * @return xmlNodeX
	 */
	function &getParentNode(){
		
		return $this->ParentNode;
	}

	function getNodeXML($_Level = -1, $Tabbed = false, $disableCDATA = false){
		
		$_Level++;
		$_attrs = array();
		foreach ( $this->Attributes as $_Key=>$_Val ){
			
			$_attrs[] = $_Key.'="'.xHtmlSpecialChars($_Val).'"';
		}
		
		$_ChildrenXMLs = array();
		
		$_ChildNodesNum = count($this->ChildNodes);

		foreach ($this->ChildNodes as $i=>$ChildNode){
			
			if(!is_a($this->ChildNodes[$i],'xmlnodex'))continue;
			$_ChildrenXMLs[] = $this->ChildNodes[$i]->getNodeXML($_Level, $Tabbed, $disableCDATA);
		}
			
		return ($Tabbed?str_repeat("\n",intval($_Level>0)).str_repeat("\t", $_Level):'').
			"<{$this->Name}".(count($_attrs)?" ".implode(" ", $_attrs):'').">".($this->Data?($disableCDATA?$this->Data:"<![CDATA[".($this->Data)."]]>"):"").
			(count($_ChildrenXMLs)?implode("",$_ChildrenXMLs).
			($Tabbed?"\n".str_repeat("\t", $_Level):'')
			:'').
			"</{$this->Name}>";
	}
	
	function _replaceSpecialChars($_Data){
	
		$_Data = str_replace('&','&amp;', $_Data);
		return str_replace(array('<','>'), array('&lt;','&gt;'), $_Data);
	}

	function renderTreeFromFile($FileName){
		
		if(!file_exists($FileName))return false;
		$this->renderTreeFromInner(file_get_contents($FileName));
	}
	
	function renderTreeFromInner($_Inner){
		
		$this->ParserResource = xml_parser_create ();
		xml_parser_set_option($this->ParserResource, XML_OPTION_CASE_FOLDING, false);
		xml_set_object($this->ParserResource, $this);
		xml_set_element_handler($this->ParserResource, "_tagOpen", "_tagClosed");
		
		xml_set_character_data_handler($this->ParserResource, "_tagData");
		
		$_Inner = xml_parse($this->ParserResource,$_Inner );
		if(!$_Inner) {
			PEAR::raiseError(sprintf("XML error: %s at line %d",
				xml_error_string(xml_get_error_code($this->ParserResource)),
				xml_get_current_line_number($this->ParserResource)));
		}
              
		xml_parser_free($this->ParserResource);
	}
	
	function _tagOpen($parser, $name, $attrs){
		
		if(!isset($this->parsingNode)){
			
			$this->parsingNode = &$this;
			$this->Name = $name;
			$this->Attributes = $attrs;
		}else {
			
			$_tParent = &$this->parsingNode;
			$this->parsingNode = &$_tParent->createChildNode($name, $attrs);
		}
	}
	
	function _tagData($parser, $tagData){
		
		if(trim($tagData)||$this->parsingNode->Data){
			
			$this->parsingNode->Data .= $tagData;
		}
	}
	
	function _tagClosed($parser, $name){
		
		if(!$this->parsingNode->getParentNode())
			unset($this->parsingNode);
		else
			$this->parsingNode = &$this->parsingNode->getParentNode();
	}

	function getChildrenByName($_Name){
		
		$_TC = count($this->ChildNodes);
		$Nodes = array();
		for ( $j = 0; $j<$_TC; $j++){
			
			if(!is_a($this->ChildNodes[$j],'xmlnodex'))continue;
			if ($this->ChildNodes[$j]->getName() == $_Name){
				
				$Nodes[] = &$this->ChildNodes[$j];
			}
		}
		
		return $Nodes;
	}
	
	function getChildData($_ChildName){
		
		$children = $this->getChildrenByName($_ChildName);
		foreach($children as $_child){
			
			return $_child->getData();
		}
		return '';
	}

	/**
	 * Enter description here...
	 *
	 * @param string $ChildName
	 * @return xmlNodeX
	 */
	function &getFirstChildByName($ChildName){
		
		$r_Children = $this->getChildrenByName($ChildName);
		if(!count($r_Children)){
			$r_Children = null;
			return $r_Children;
		}
		
		return $r_Children[0];
	}
	
	/**
	 * Now only /xxx/xxxx/xxxxx
	 *
	 * @param unknown_type $_xPath
	 * @return array
	 */
	function xPath($_xPath){
		
		$TagNames = explode('/', $_xPath);
		$_TagName = '';
		$Nodes = array();
		while (count($TagNames)){
			
			$_TagName = array_shift($TagNames);
			if(!$_TagName)continue;

			$Ignore = false;
			if(preg_match('/\[(.*?)\]/', $_TagName, $SubPatterns)){
			
				$_TagName = preg_replace('/\[.*?\]/', '', $_TagName);
				$r_tAttributes = explode(',', $SubPatterns[1]);
				foreach ($r_tAttributes as $_Attribite){
					
					$_Attribite = explode('=', $_Attribite);
					$AttributeName = preg_replace('/^\@/','', $_Attribite[0]);
					$AttributeValue = preg_replace('/^"(.*?)"$/','$1', $_Attribite[1]);
					$n_atr = $this->getAttribute($AttributeName);
					
					if(!( $n_atr == $AttributeValue | '"'.$n_atr.'"' == $AttributeValue | '\''.$n_atr.'\'' == $AttributeValue)){
						
						$Ignore = true;
						break;
					}
				}
			}
			
			if(!count($TagNames) && $_TagName==$this->getName() && !$Ignore){

				$r_t = array(&$this);
				return $r_t;
			}

			list($chTagName) = $TagNames;

			$r_Attributes = array();
			if(preg_match('/\[(.*?)\]/', $chTagName, $SubPatterns)){
				
				$chTagName = preg_replace('/\[.*?\]/', '', $chTagName);
				$r_tAttributes = explode(',', $SubPatterns[1]);
				foreach ($r_tAttributes as $_Attribite){
					
					$_Attribite = explode('=', $_Attribite);
					$r_Attributes[preg_replace('/^\@/','', $_Attribite[0])] = preg_replace('/^"(.*?)"$/','$1', $_Attribite[1]);
				}
			}
			
			$ChildNodes = $this->getChildrenByName($chTagName);
			
			$_TC = count($ChildNodes);
			for($n = 0; $n<$_TC; $n++){

				$Ignore = false;
				foreach ($r_Attributes as $AttributeName => $AttributeValue){
					
					$n_atr = $ChildNodes[$n]->getAttribute($AttributeName);
					if(!( $n_atr == $AttributeValue | '"'.$n_atr.'"' == $AttributeValue | '\''.$n_atr.'\'' == $AttributeValue)){
					
						$Ignore = true;
						break;
					}
				}
				if($Ignore)continue;
				
				$Nodes = array_merge($Nodes, $ChildNodes[$n]->xPath('/'.implode('/', $TagNames)));
			}
			break;
		}
		
		return $Nodes;
	}
	
		
	function saveToFile($FileName, $Tabbed = false, $encoding = 'ISO-8859-1'){
		
		$fp = fopen($FileName, 'w');
		fwrite($fp, '<?xml version="1.0" encoding="'.$encoding.'"?>'."\r\n".$this->getNodeXML(-1, $Tabbed));
		fclose($fp);
	}

	function setData($Data){
		
		$this->Data = $Data;
	}

	/**
	 * Set or get attribute
	 *
	 * @param string $k
	 * @param string $v
	 */
	function attribute($k, $v = null){
		
		if(!is_null($v)){
			
			$this->Attributes[$k] = $v;
		}
		return $this->getAttribute($k);
	}
}
?><?php
#####################################
# ShopCMS: Скрипт интернет-магазина
# Copyright (c) by ADGroup
# http://shopcms.ru
#####################################

function affp_getCustomersNum($_customerID){

        $sql = "select COUNT(*) FROM ".CUSTOMERS_TABLE."
                WHERE affiliateID = ".(int)$_customerID." AND CHAR_LENGTH(ActivationCode)=0";
        list($affiliate_customers) = db_fetch_row(db_query($sql));
        return $affiliate_customers;
}

function affp_getRecruitedCustomers($_customerID, $_offset = 0, $_limit = 0){

        $_till = $_offset+$_limit;
        $customers = array();
        $sql = "select customerID, Login, first_name, last_name, reg_datetime, ActivationCode FROM ".CUSTOMERS_TABLE."
                WHERE affiliateID = ".(int)$_customerID."
        ";
        $result = db_query($sql);
        $i = 0;
        while ($_row = db_fetch_row($result)) {

                if ( ($i>=$_offset && $i<$_till && $_till>0) || (!$_till && !$_offset) ){

                        $_t = explode(' ', $_row['reg_datetime']);
                        $_row['reg_datetime'] = TransformDATEToTemplate($_t[0]);
                        $customers["{$_row['customerID']}"] = $_row;
                        $customers["{$_row['customerID']}"]['orders_num'] = 0;
                        $customers["{$_row['customerID']}"]['currencies'] = array();
                }
                $i++;
        }

        if(!count($customers))return array();

        $sql = "select customerID, currency_code, currency_value, order_amount FROM ".ORDERS_TABLE."
                WHERE customerID IN(".implode(", ", array_keys($customers)).") and statusID = '".CONF_COMPLETED_ORDER_STATUS."'
        ";
        $result = db_query($sql);
        while (list($__customerID, $__currency_code, $__currency_value, $__order_amount) = db_fetch_row($result)) {

                if(!key_exists($__currency_code, $customers[$__customerID]['currencies']))
                        $customers[$__customerID]['currencies'][$__currency_code] = 0;
                $customers[$__customerID]['currencies'][$__currency_code] += floatval(sprintf("%.2f",($__order_amount*$__currency_value)));
                $customers[$__customerID]['orders_num']++;
        }

        return $customers;
}

/**
 * remove recruited customer
 *
 * @param integer - customer id
 */
function affp_cancelRecruitedCustomer($_customerID){

        $sql = "
                UPDATE `".CUSTOMERS_TABLE."` SET affiliateID = 0
                WHERE customerID = ".(int)$_customerID;
        db_query($sql);
}

/**
 * return payments by params
 *
 * @return array
 */
function affp_getPayments($_customerID, $_pID = '', $_from = '', $_till = '', $_order = ''){

        $sql = "select pID, customerID, Amount, CurrencyISO3, xDate, Description
                FROM ".AFFILIATE_PAYMENTS_TABLE."
                WHERE 1
                ".($_pID?" AND pID = ".(int)$_pID:"")."
                ".($_customerID?" AND customerID = ".(int)$_customerID:"")."
                ".($_from?" AND xDate>='".xEscSQL($_from)."'":"")."
                ".($_till?" AND xDate<='".xEscSQL($_till)."'":"")."
                ".($_order?" ORDER BY ".xEscSQL($_order):"")."
        ";
        $result = db_query($sql);
        $payments = array();
        while ($_row = db_fetch_row($result)){

                $_row['Amount'] = sprintf("%.2f", $_row['Amount']);
                $_row['CustomerLogin'] = regGetLoginById($_row['customerID']);
                $_row['xDate'] = TransformDATEToTemplate($_row['xDate']);
                $payments[] = $_row;
        }
        return $payments;
}

/**
 * add new payment
 *
 * @param hash $_payment
 * @return new payment id
 */
function affp_addPayment($_payment){

        if(isset($_payment['Amount']))$_payment['Amount'] = sprintf("%.2f", $_payment['Amount']);
        $sql = "
                INSERT ".AFFILIATE_PAYMENTS_TABLE."
                (`".implode("`, `", xEscSQL(array_keys($_payment)))."`)
                VALUES('".implode("', '", xEscSQL($_payment))."')
        ";
        db_query($sql);

        if(CONF_AFFILIATE_EMAIL_NEW_PAYMENT){

                $Settings = affp_getSettings($_payment['customerID']);
                if(!$Settings['EmailPayments'])return db_insert_id();

                $t                 = '';
                $Email         = '';
                $FirstName = '';
                regGetContactInfo(regGetLoginById($_payment['customerID']), $t, $Email, $FirstName, $t, $t, $t);
                xMailTxt($Email, AFFP_NEW_PAYMENT, 'customer.affiliate.payment_notifi.tpl.html',
                        array(
                                'customer_firstname'         => $FirstName,
                                '_AFFP_NEW_PAYMENT'         => str_replace('{MONEY}', $_payment['Amount'].' '.$_payment['CurrencyISO3'],AFFP_MAIL_NEW_PAYMENT)
                                ));
        }
        return db_insert_id();
}

/**
 * save payment
 *
 * @param array $_payment
 * @return bool
 */
function affp_savePayment($_payment){

        if(isset($_payment['Amount']))$_payment['Amount'] = round($_payment['Amount'], 2);
        if(!isset($_payment['pID'])) return false;
        $_pID = $_payment['pID'];
        unset($_payment['pID']);

        foreach ($_payment as $_ind=>$_val)
                $_payment[$_ind] = "`".xEscSQL($_ind)."`='".xEscSQL($_val)."'";
        $sql = "
                UPDATE ".AFFILIATE_PAYMENTS_TABLE."
                SET ".implode(", ", $_payment)."
                WHERE pID=".(int)$_pID;
        db_query($sql);
        return true;
}

/**
 * Delete payment
 *
 * @param integer - payment id
 */
function affp_deletePayment($_pID){

        $sql = "DELETE FROM `".AFFILIATE_PAYMENTS_TABLE."` WHERE pID=".(int)$_pID;
        db_query($sql);
}

/**
 * Add commission to customer from order
 *
 * @param integer - order id
 */
function affp_addCommissionFromOrder($_orderID){

        $Commission = affp_getCommissionByOrder($_orderID);
        if($Commission['cID'])return 0;

        $Order                         = ordGetOrder( $_orderID );

        if($Order['customerID'])
                $RefererID                 = affp_getReferer($Order['customerID']);
        else
                $RefererID                 = $Order['affiliateID'];

        if(!$RefererID)return 0;

        $CustomerLogin = regGetLoginById($Order['customerID']);
        if(!$CustomerLogin)
                $CustomerLogin = $Order['customer_email'];

        $Commission         = array(
                'Amount'                         => sprintf("%.2f", ($Order['currency_value']*$Order['order_amount']*CONF_AFFILIATE_AMOUNT_PERCENT)/100),
                'CurrencyISO3'         => $Order['currency_code'],
                'xDateTime'                 => date("Y-m-d H:i:s"),
                'OrderID'                         => $_orderID,
                'CustomerID'                 => $RefererID,
                'Description'                 => xEscSQL(str_replace(array('{ORDERID}', '{USERLOGIN}'), array($_orderID, $CustomerLogin), AFFP_COMMISSION_DESCRIPTION))
        );

        do{
        if(CONF_AFFILIATE_EMAIL_NEW_COMMISSION){

                $Settings = affp_getSettings($RefererID);
                if(!$Settings['EmailOrders'])break;

                $t                                 = '';
                $Email                         = '';
                $FirstName                 = '';
                regGetContactInfo(regGetLoginById($RefererID), $t, $Email, $FirstName, $t, $t, $t);
                xMailTxt($Email, AFFP_NEW_COMMISSION, 'customer.affiliate.commission_notifi.tpl.html',
                        array(
                                'customer_firstname' => $FirstName,
                                '_AFFP_MAIL_NEW_COMMISSION' => str_replace('{MONEY}', $Commission['Amount'].' '.$Commission['CurrencyISO3'],AFFP_MAIL_NEW_COMMISSION)
                                ));
        }
        }while (0);

        affp_addCommission($Commission);
}

/**
 * Add commission to customer from commission array
 *
 * @param array - commission
 */
function affp_addCommission($_Commission){

        if(isset($_Commission['Amount']))$_Commission['Amount'] = round($_Commission['Amount'], 2);
        $sql = "
                INSERT `".AFFILIATE_COMMISSIONS_TABLE."`
                (`".implode("`, `", xEscSQL(array_keys($_Commission)))."`)
                VALUES('".implode("', '",$_Commission)."')
        ";
        db_query($sql);
        return db_insert_id();
}

/**
 * Delete commission by cID
 *
 * @param integer cID - commission id
 */
function affp_deleteCommission($_cID){

        $sql = "DELETE FROM `".AFFILIATE_COMMISSIONS_TABLE."` WHERE cID=".(int)$_cID;
        db_query($sql);
}

/**
 * return commissions by params
 * @param integer $_customerID - customer id
 * @param integer $_cID - commission id
 * @param string $_from - from date in DATETIME format
 * @param string $_till - till date in DATETIME format
 * @param string $_order - order by this->...<-this
 * @return array
 */
function affp_getCommissions($_customerID, $_cID, $_from = '', $_till = '', $_order = ''){

        $sql = "select cID, customerID, Amount, CurrencyISO3, xDateTime, Description, CustomerID
                FROM ".AFFILIATE_COMMISSIONS_TABLE."
                WHERE 1
                ".($_cID?" AND cID = ".(int)$_cID:"")."
                ".($_customerID?" AND customerID = ".(int)$_customerID:"")."
                ".($_from?" AND xDateTime>='".xEscSQL($_from)."'":"")."
                ".($_till?" AND xDateTime<='".xEscSQL($_till)."'":"")."
                ".($_order?" ORDER BY ".xEscSQL($_order):"")."
        ";
        $result = db_query($sql);
        $commissions = array();
        while ($_row = db_fetch_row($result)){

                $_row['CustomerLogin'] = regGetLoginById($_row['customerID']);
                $_row['Amount'] = sprintf("%.2f", $_row['Amount']);
                $_t = explode(' ', $_row['xDateTime']);
                $_row['xDateTime'] = TransformDATEToTemplate($_t[0]);
                $commissions[] = $_row;
        }
        return $commissions;
}

/**
 * save commission
 *
 * @param array
 * @return bool
 */
function affp_saveCommission($_commission){

        if(isset($_commission['Amount']))$_commission['Amount'] = round($_commission['Amount'], 2);
        if(!isset($_commission['cID'])) return false;
        $_cID = $_commission['cID'];
        unset($_commission['cID']);

        foreach ($_commission as $_ind=>$_val)
                $_commission[$_ind] = "`".xEscSQL($_ind)."`='".xEscSQL($_val)."'";
        $sql = "UPDATE ".AFFILIATE_COMMISSIONS_TABLE."
                SET ".implode(", ", $_commission)."
                WHERE cID=".(int)$_cID;
        db_query($sql);
        return true;
}

/**
 * return commissions(earnings) for customer
 * @param integer - customer id
 * @return array
 */
function affp_getCommissionsAmount($_CustomerID){

        $CurrencyAmount = array();
        $sql = "select SUM(`Amount`) AS CurrencyAmount, CurrencyISO3 FROM `".AFFILIATE_COMMISSIONS_TABLE."`
                WHERE CustomerID = ".(int)$_CustomerID."
                GROUP BY `CurrencyISO3`
        ";
        $result = db_query($sql);
        while ($_row = db_fetch_row($result)){

                $CurrencyAmount[$_row['CurrencyISO3']] = sprintf("%.2f", $_row['CurrencyAmount']);
        }
        return $CurrencyAmount;
}

/**
 * return payments to customer
 * @param integer - customer id
 * @return array
 */
function affp_getPaymentsAmount($_CustomerID){

        $PaymentAmount = array();
        $sql = "select SUM(`Amount`) AS CurrencyAmount, CurrencyISO3 FROM `".AFFILIATE_PAYMENTS_TABLE."`
                WHERE CustomerID = ".(int)$_CustomerID."
                GROUP BY `CurrencyISO3`
        ";
        $result = db_query($sql);
        while ($_row = db_fetch_row($result)){

                $PaymentAmount[$_row['CurrencyISO3']] = sprintf("%.2f", $_row['CurrencyAmount']);
        }
        return $PaymentAmount;
}

/**
 * return settings for customer
 * @param integer - customer id
 * @return array
 */
function affp_getSettings($_CustomerID){

        $Settings = array();
        $sql = "select affiliateEmailOrders, affiliateEmailPayments FROM `".CUSTOMERS_TABLE."`
                WHERE customerID=".(int)$_CustomerID;
        list($Settings['EmailOrders'], $Settings['EmailPayments']) = db_fetch_row(db_query($sql));
        return $Settings;
}

/**
 * save settings for customer
 * @param integer
 * @param integer
 */
function affp_saveSettings($_CustomerID, $_EmailOrders, $_EmailPayments){

        $sql = "UPDATE `".CUSTOMERS_TABLE."`
                SET affiliateEmailOrders = '".(int)$_EmailOrders."',
                        affiliateEmailPayments = '".(int)$_EmailPayments."'
                WHERE customerID=".(int)$_CustomerID;
        db_query($sql);
}

/**
 * get customer referer
 * @param integer - customer id
 * @return integer
 */
function affp_getReferer($_CustomerID){

        $sql = "select affiliateID FROM `".CUSTOMERS_TABLE."`
                WHERE customerID=".(int)$_CustomerID;
        list($affiliateID) = db_fetch_row(db_query($sql));
        return $affiliateID;
}

/**
 * Return array with commission information by order id
 *
 * @param integer $_OrderID
 * @return array
 */
function affp_getCommissionByOrder($_OrderID){

        $sql = "select cID, customerID, Amount, CurrencyISO3, xDateTime, Description, CustomerID
                FROM ".AFFILIATE_COMMISSIONS_TABLE."
                WHERE OrderID=".(int)$_OrderID;
        $commission = db_fetch_row(db_query($sql));

        if(!$commission['cID']) return $commission;

        $commission['CustomerLogin'] = regGetLoginById($commission['customerID']);
        $commission['Amount'] = sprintf("%.2f", $commission['Amount']);
        list($_t) = explode(' ', $commission['xDateTime']);
        $commission['xDateTime'] = TransformDATEToTemplate($_t);

        return $commission;
}
?><?php
#####################################
# ShopCMS: Скрипт интернет-магазина
# Copyright (c) by ADGroup
# http://shopcms.ru
#####################################

function auxpgGetAllPageAttributes()
{
        $q = db_query("select aux_page_ID, aux_page_name, aux_page_text_type from ".AUX_PAGES_TABLE);
        $data = array();
        while( $row = db_fetch_row( $q ) ) $data[] = $row;
        return $data;
}

function auxpgGetAuxPage( $aux_page_ID )
{
        $q = db_query("select aux_page_ID, aux_page_name, aux_page_text, aux_page_text_type, ".
                 " meta_keywords, meta_description, title from ".AUX_PAGES_TABLE." where aux_page_ID=".(int)$aux_page_ID);
        if  ( $row=db_fetch_row($q) )
        {
                if ( $row["aux_page_text_type"] !=1 ) $row["aux_page_text"] = ToText( $row["aux_page_text"] );
                $row["aux_page_title"] = $row["title"];
        }
        return $row;
}

function auxpgUpdateAuxPage(    $aux_page_ID, $aux_page_name,
                                $aux_page_text, $aux_page_text_type,
                                $meta_keywords, $meta_description, $aux_page_title  )
{
        db_query("update ".AUX_PAGES_TABLE.
                 " set     aux_page_name='".xToText($aux_page_name)."', ".
                 "         aux_page_text='".xEscSQL($aux_page_text)."', ".
                 "         aux_page_text_type=".(int)$aux_page_text_type.", ".
                 "         meta_keywords='".xToText($meta_keywords)."', ".
                 "         meta_description='".xToText($meta_description)."', ".
                 "         title='".xToText($aux_page_title)."' ".
                 " where aux_page_ID=".(int)$aux_page_ID);
}

function auxpgAddAuxPage(       $aux_page_name,
                                $aux_page_text, $aux_page_text_type,
                                $meta_keywords, $meta_description, $aux_page_title)
{
        db_query( "insert into ".AUX_PAGES_TABLE.
                " ( aux_page_name, aux_page_text, aux_page_text_type, meta_keywords, meta_description, title )  ".
                " values( '".xToText($aux_page_name)."', '".xEscSQL($aux_page_text)."', ".(int)$aux_page_text_type.", ".
                " '".xToText($meta_keywords)."', '".xToText($meta_description)."', '".xToText($aux_page_title)."' ) " );
}

function auxpgDeleteAuxPage( $aux_page_ID )
{
        db_query("delete from ".AUX_PAGES_TABLE." where aux_page_ID=".(int)$aux_page_ID);
}


?><?php
#####################################
# ShopCMS: Скрипт интернет-магазина
# Copyright (c) by ADGroup
# http://shopcms.ru
#####################################

function GetAllBlocksAttributes() {
    $q = db_query("select bid, title, bposition, active, which, sort, html, url, admin from ".BLOCKS_TABLE." ORDER BY sort ASC");
    $data = array( );    
	while ( $row = db_fetch_row($q)) {
        if ( $row["html"] == 1 ) {
            if ( file_exists("core/tpl/user/".CONF_DEFAULT_TEMPLATE."/blocks/".$row["url"])) $data[] = $row;
        }
        else $data[] = $row;
    }
    return $data;
}

function Powerblocks($switches, $b_id) {
    db_query("update ".BLOCKS_TABLE." set active=".( int ) $switches." where bid=".( int ) $b_id);
}

function SortBlocks() {
    $data = ScanPostVariableWithId(array( "sort" ));
    foreach ( $data as $key => $val ) {
        if ( isset ( $val["sort"] )) {
            db_query("UPDATE ".BLOCKS_TABLE." SET sort=".( int ) $val["sort"]." WHERE bid=".( int ) $key);
        }
    }
}

function blockspgGetblocksPage($page_ID) {
    $q = db_query("select title, content, bposition, active, which, html, url, admin, pages, dpages, categories, products, about from ".BLOCKS_TABLE." where bid=".( int ) $page_ID);
    if ( $row = db_fetch_row($q)) {
        $row["bid"] = ( int ) $page_ID;
        $row["pages"] = unserialize($row["pages"]);
        $row["dpages"] = unserialize($row["dpages"]);
        $row["categories"] = unserialize($row["categories"]);
		$row["products"] = unserialize($row["products"]);
    }
    return $row;
}

function blockspgUpdateblocksPage($page_ID, $page_name, $page_text, $which, $bposition, $active, $admin, $s, $d, $c, $p) {
    $rs = isset ( $s ) ? serialize($s) : serialize(array());
    $rd = isset ( $d ) ? serialize($d) : serialize(array());
    $rc = isset ( $c ) ? serialize($c) : serialize(array());
	$rpt = explode("\n",chop($p));
    $rp = array();
    for ($i=0; $i<count($rpt); $i++) if($tmp=(int) rtrim($rpt[$i]) > 0 && rtrim($rpt[$i]) !== "") $rp[] = (int) rtrim($rpt[$i]);
    $rp = serialize($rp);
    db_query("update ".BLOCKS_TABLE." set 
	title='".xToText($page_name)."', "." 
	content='".xEscSQL($page_text)."', "." 
	bposition=".( int ) $bposition.", "." 
	active=".( int ) $active.", "." 
	which=".( int ) $which.", "." 
	admin=".( int ) $admin.", "." 
	pages='".xEscSQL($rs)."', "." 
	dpages='".xEscSQL($rd)."', "." 
	categories='".xEscSQL($rc)."', "." 
	products='".xEscSQL($rp)."' "." 
	where bid=".( int ) $page_ID);
}

function blockspgAddblocksPage($page_name, $page_text, $which, $bposition, $active, $admin, $s, $d, $c, $p) {
    $rs = isset ( $s ) ? serialize($s) : serialize(array());
    $rd = isset ( $d ) ? serialize($d) : serialize(array());
    $rc = isset ( $c ) ? serialize($c) : serialize(array());
	$rpt = explode("\n",chop($p));
    $rp = array();
    for ($i=0; $i<count($rpt); $i++) if($tmp=(int) rtrim($rpt[$i]) > 0 && rtrim($rpt[$i]) !== "") $rp[] = (int) rtrim($rpt[$i]);
    $rp = serialize($rp);
    db_query("insert into ".BLOCKS_TABLE." ( title, content, bposition, active, which, admin, pages, dpages, categories, products )  "." values( '".xToText($page_name)."', '".xEscSQL($page_text)."', ".( int ) $bposition.", ".( int ) $active.", ".( int ) $which.", ".( int ) $admin.", '".xEscSQL($rs)."',
                '".xEscSQL($rd)."', '".xEscSQL($rc)."', '".xEscSQL($rp)."') ");
}

function blockspgAddblocksPageFile($page_name, $page_file, $which, $bposition, $active, $admin, $s, $d, $c, $p) {
    $rs = isset ( $s ) ? serialize($s) : serialize(array());
    $rd = isset ( $d ) ? serialize($d) : serialize(array());
    $rc = isset ( $c ) ? serialize($c) : serialize(array());
	$rpt = explode("\n",chop($p));
    $rp = array();
    for ($i=0; $i<count($rpt); $i++) if($tmp=(int) rtrim($rpt[$i]) > 0 && rtrim($rpt[$i]) !== "") $rp[] = (int) rtrim($rpt[$i]);
    $rp = serialize($rp);
    db_query("insert into ".BLOCKS_TABLE." ( title, bposition, active, which, html, url, admin, pages, dpages, categories, products )  "." values( '".xToText($page_name)."', ".( int ) $bposition.", ".( int ) $active.", ".( int ) $which.", '1', '".$page_file."', ".( int ) $admin.", '".xEscSQL($rs)."',
                '".xEscSQL($rd)."', '".xEscSQL($rc)."', '".xEscSQL($rp)."') ");
}

function blockspgDeleteblocks($page_ID) {
    db_query("delete from ".BLOCKS_TABLE." where bid=".( int ) $page_ID);
}

?><?php
#####################################
# ShopCMS: Скрипт интернет-магазина
# Copyright (c) by ADGroup
# http://shopcms.ru
#####################################


// compare two configuration
function CompareConfiguration($variants1, $variants2)
{
        if ( count($variants1) != count($variants2) )
                return false;

        foreach($variants1 as $variantID)
        {
                $count1 = 0;
                $count2 = 0;

                for($i=0; $i<count($variants1); $i++)
                        if ( (int)$variants1[$i] == (int)$variantID )
                                $count1 ++;

                for($i=0; $i<count($variants1); $i++)
                        if ( (int)$variants2[$i] == (int)$variantID )
                                $count2 ++;

                if ( $count1 != $count2 )
                        return false;
        }
        return true;
}

// search configuration in session variable
function SearchConfigurationInSessionVariable($variants, $productID)
{
        foreach( $_SESSION["configurations"] as $key => $value )
        {
                if ( (int)$_SESSION["gids"][$key] != (int)$productID )
                        continue;
                if ( CompareConfiguration($variants, $value) )
                        return $key;
        }
        return -1;
}

// search configuration in database
function SearchConfigurationInDataBase($variants, $productID)
{
        $q=db_query( "select itemID from ".SHOPPING_CARTS_TABLE.
                " where customerID=".(int)regGetIdByLogin($_SESSION["log"]));
        while( $r = db_fetch_row($q) )
        {
                $q1=db_query( "select COUNT(*) from ".SHOPPING_CART_ITEMS_TABLE.
                        " where productID=".(int)$productID." AND itemID=".(int)$r["itemID"]);
                $r1=db_fetch_row($q1);
                if ( $r1[0] != 0 )
                {
                        $variants_from_db=GetConfigurationByItemId( $r["itemID"] );
                        if ( CompareConfiguration($variants, $variants_from_db) )
                                return $r["itemID"];
                }
        }
        return -1;
}

function GetConfigurationByItemId($itemID)
{
        $q=db_query("select variantID from ".
                SHOPPING_CART_ITEMS_CONTENT_TABLE." where itemID=".(int)$itemID);
        $variants=array();
        while( $r=db_fetch_row( $q ) ) $variants[]=$r["variantID"];
        return $variants;
}

function InsertNewItem($variants, $productID)
{
        db_query( "insert into ".SHOPPING_CART_ITEMS_TABLE.
                "(productID) values('".(int)$productID."')" );
        $itemID=db_insert_id();
        foreach( $variants as $vars )
        {
                db_query("insert into ".
                        SHOPPING_CART_ITEMS_CONTENT_TABLE."(itemID, variantID) ".
                        "values( '".(int)$itemID."', '".(int)$vars."')" );
        }
        return $itemID;
}

function InsertItemIntoCart($itemID)
{
        db_query("insert ".SHOPPING_CARTS_TABLE."(customerID, itemID, Quantity)".
                "values( '".(int)regGetIdByLogin($_SESSION["log"])."', '".(int)$itemID."', 1 )" );
}

function GetStrOptions($variants)
{
        $first_flag=true;
        $res = "";
        foreach( $variants as $vars )
        {
                $q=db_query("select option_value from ".
                        PRODUCTS_OPTIONS_VALUES_VARIANTS_TABLE.
                                " where variantID=".(int)$vars);
                if ( $r=db_fetch_row($q) )
                {
                        if ( $first_flag )
                        {
                                $res.=$r["option_value"];
                                $first_flag = false;
                        }
                        else
                                $res.=", ".$r["option_value"];
                }
        }
        return $res;
}

function CodeItemInClient($variants, $productID)
{
        $array=array();
        $array[]=$productID;
        foreach($variants as $var)
                $array[]=$var;
        return implode("_", $array);
}

function DeCodeItemInClient($str)
{
        // $variants, $productID
        $array=explode("_", $str );
        $productID=$array[0];
        $variants=array();
        for($i=1; $i<count($array); $i++)
                $variants[]=$array[$i];
        $res=array();
        $res["productID"]=$productID;
        $res["variants"]=$variants;
        return $res;
}

function GetProductInStockCount($productID)
{
        $q=db_query("select in_stock from ".PRODUCTS_TABLE." where productID=".(int)$productID);
        $is=db_fetch_row($q);
        return $is[0];
}

function GetPriceProductWithOption($variants, $productID)
{
        $q=db_query("select Price from ".PRODUCTS_TABLE." where productID=".(int)$productID);
        $r=db_fetch_row($q);
        $base_price = (float)$r[0];
        $full_price = (float)$base_price;
        foreach($variants as $vars)
        {
                $q1=db_query("select price_surplus from ".PRODUCTS_OPTIONS_SET_TABLE.
                        " where productID=".(int)$productID." AND variantID=".(int)$vars);
                $r1=db_fetch_row($q1);
                $full_price += $r1["price_surplus"];
        }
        return $full_price;
}


function GetProductIdByItemId($itemID)
{
        $q=db_query("select productID from ".SHOPPING_CART_ITEMS_TABLE." where itemID=".(int)$itemID);
        $r=db_fetch_row($q);
        return $r["productID"];
}


// *****************************************************************************
// Purpose        move cart content ( SHOPPING_CARTS_TABLE ) into ordered carts ( ORDERED_CARTS_TABLE )
// Inputs                $orderID - order ID
//                                $shippingMethodID                - shipping method ID
//                                $paymentMethodID                - payment method ID
//                                $shippingAddressID                - shipping address ID
//                                $billingAddressID                - billing address ID
//                                $shippingModuleFiles        - content core/modules/shipping directories
//                                $paymentModulesFiles        - content core/modules/payment directories
// Remarks        this funcgtion is called by ordOrderProcessing to order comete
// Returns        nothing
function cartMoveContentFromShoppingCartsToOrderedCarts( $orderID,
                $shippingMethodID, $paymentMethodID,
                $shippingAddressID, $billingAddressID,
                $shippingModuleFiles, $paymentModulesFiles, &$smarty_mail )
{
        $q = db_query( "select statusID from ".ORDERS_TABLE." where orderID=".(int)$orderID);
        $order = db_fetch_row( $q );
        $statusID = $order["statusID"];

        // select all items from SHOPPING_CARTS_TABLE
        $q_items = db_query("select itemID, Quantity FROM ".
                        SHOPPING_CARTS_TABLE." WHERE customerID=".(int)regGetIdByLogin($_SESSION["log"]));
        while($item = db_fetch_row($q_items))
        {
                $productID = GetProductIdByItemId( $item["itemID"] );
                if ( $productID == null || trim($productID) == "" )
                        continue;

                // get product by ID
                $q_product = db_query("select name, product_code from ".PRODUCTS_TABLE.
                        " where productID=".(int)$productID);
                $product = db_fetch_row( $q_product );

                // get full product name ( complex product name - $productComplexName ) -
                // name with configurator options
                $variants = GetConfigurationByItemId( $item["itemID"] );
                $options = GetStrOptions( $variants );
                if ( $options != "" )
                        $productComplexName = $product["name"]."(".$options.")";
                else
                        $productComplexName = $product["name"];

                if ( strlen($product["product_code"]) > 0 )
                        $productComplexName = "[".$product["product_code"]."] ".$productComplexName;

                //
                $price = GetPriceProductWithOption( $variants, $productID );
                $tax = taxCalculateTax( $productID, $shippingAddressID, $billingAddressID );
                db_query("INSERT INTO ".ORDERED_CARTS_TABLE.
                         "(        itemID, orderID, name, ".
                         "        Price, Quantity, tax ) ".
                         "  VALUES ".
                         "         (".(int)$item["itemID"].",".(int)$orderID.", '".xEscSQL($productComplexName)."', ".xEscSQL($price).
                         ", ".(int)$item["Quantity"].", ".xEscSQL($tax)." )");
                if ( $statusID != ostGetCanceledStatusId() && CONF_CHECKSTOCK )
                {
                        db_query( "update ".PRODUCTS_TABLE." set in_stock = in_stock - ".(int)$item["Quantity"].
                                                " where productID=".(int)$productID );
					    $q = db_query("select name, in_stock FROM ".PRODUCTS_TABLE." WHERE productID=".(int)$productID);
                        $productsta = db_fetch_row($q);
                        if ( $productsta["in_stock"] == 0){
					        if (CONF_AUTOOFF_STOCKADMIN) db_query( "update ".PRODUCTS_TABLE." set enabled=0 where productID=".(int)$productID);
                            if (CONF_NOTIFY_STOCKADMIN){
                                $smarty_mail->assign( "productstaname", $productsta["name"] );
                                $smarty_mail->assign( "productstid", $productID );
                                $stockadmin = $smarty_mail->fetch( "notify_stockadmin.tpl.html" );
                                $ressta = xMailTxtHTMLDATA(CONF_ORDERS_EMAIL,CUSTOMER_ACTIVATE_99,$stockadmin);
                            }
                        }
                }
        }
        db_query("DELETE FROM ".SHOPPING_CARTS_TABLE." WHERE customerID=".(int)regGetIdByLogin($_SESSION["log"]));
}



// *****************************************************************************
// Purpose        clear cart content
// Inputs
// Remarks
// Returns
function cartClearCartContet()
{
        if ( isset($_SESSION["log"]) )
                db_query("DELETE FROM ".SHOPPING_CARTS_TABLE." WHERE customerID=".(int)regGetIdByLogin($_SESSION["log"]));
        else
        {
                unset($_SESSION["gids"]);
                unset($_SESSION["counts"]);
                unset($_SESSION["configurations"]);
                session_unregister("gids"); //calling session_unregister() is required since unset() may not work on some systems
                session_unregister("counts");
                session_unregister("configurations");
        }
}

// *****************************************************************************
// Purpose        clear cart content
// Inputs
// Remarks
// Returns
function cartGetCartContent()
{
        $cart_content         = array();
        $total_price         = 0;
        $freight_cost        = 0;


        if (isset($_SESSION["log"])) //get cart content from the database
        {
                $q = db_query("select itemID, Quantity FROM ".SHOPPING_CARTS_TABLE.
                                " WHERE customerID=".(int)regGetIdByLogin($_SESSION["log"]));

                while ($cart_item = db_fetch_row($q))
                {
                        // get variants
                        $variants=GetConfigurationByItemId( $cart_item["itemID"] );

                        // shopping cart item
                        $q_shopping_cart_item = db_query("select productID from ".
                                        SHOPPING_CART_ITEMS_TABLE." where ".
                                        " itemID=".(int)$cart_item["itemID"]);
                        $shopping_cart_item = db_fetch_row( $q_shopping_cart_item );
                        $q_products = db_query("select name, Price, productID, min_order_amount, shipping_freight, free_shipping, product_code FROM ".
                                        PRODUCTS_TABLE." WHERE productID=".(int)$shopping_cart_item["productID"]);
                        if ( $product = db_fetch_row($q_products) )
                        {
                                $costUC = GetPriceProductWithOption( $variants,
                                                        $shopping_cart_item["productID"] );
                                $tmp =
                                        array(
                                                "productID" =>  $product["productID"],
                                                "id"                =>        $cart_item["itemID"],
                                                "name"                =>        $product["name"],
                                                "quantity"        =>        $cart_item["Quantity"],
                                                "free_shipping"        =>        $product["free_shipping"],
                                                "costUC"        =>        $costUC,
                                                "cost"                =>        show_price($cart_item["Quantity"]*
                                                                                GetPriceProductWithOption($variants,
                                                                                        $shopping_cart_item["productID"])),
                                                "product_code"                =>         $product["product_code"] );

                                $freight_cost += $cart_item["Quantity"]*$product["shipping_freight"];

                                $strOptions=GetStrOptions(
                                                GetConfigurationByItemId( $tmp["id"] ));

                                if ( trim($strOptions) != "" )
                                                $tmp["name"].="  (".$strOptions.")";


                                if ( $product["min_order_amount"] > $cart_item["Quantity"] )
                                        $tmp["min_order_amount"] = $product["min_order_amount"];


                                $cart_content[] = $tmp;
                                $total_price += $cart_item["Quantity"]*
                                                GetPriceProductWithOption($variants,
                                                        $shopping_cart_item["productID"]);

                        }
                }
        }
        else //unauthorized user - get cart from session vars
        {
                $total_price         = 0; //total cart value
                $cart_content        = array();


                //shopping cart items count
                if ( isset($_SESSION["gids"]) )
                        for ($j=0; $j<count($_SESSION["gids"]); $j++)
                        {
                                if ($_SESSION["gids"][$j])
                                {
                                        $session_items[]=
                                                CodeItemInClient($_SESSION["configurations"][$j],
                                                        $_SESSION["gids"][$j]);


                                        $q = db_query("select name, Price, shipping_freight, free_shipping, product_code FROM ".
                                                PRODUCTS_TABLE." WHERE productID=".(int)$_SESSION["gids"][$j]);
                                        if ($r = db_fetch_row($q))
                                        {
                                                $costUC = GetPriceProductWithOption(
                                                                $_SESSION["configurations"][$j],
                                                                $_SESSION["gids"][$j])/* * $_SESSION["counts"][$j]*/;

                                                $id = $_SESSION["gids"][$j];
                                                if (count($_SESSION["configurations"][$j]) > 0)
                                                {
                                                        for ($tmp1=0;$tmp1<count($_SESSION["configurations"][$j]);$tmp1++) $id .= "_".$_SESSION["configurations"][$j][$tmp1];
                                                }

                                                $tmp = array(
                                                                "productID"        =>  $_SESSION["gids"][$j],
                                                                "id"                =>        $id, //$_SESSION["gids"][$j],
                                                                "name"                =>        $r[0],
                                                                "quantity"        =>        $_SESSION["counts"][$j],
                                                                "free_shipping"        =>        $r["free_shipping"],
                                                                "costUC"        =>        $costUC,
                                                                "cost"                =>        show_price($costUC * $_SESSION["counts"][$j]),
                                                "product_code"                =>         $r["product_code"] );

                                                $strOptions=GetStrOptions( $_SESSION["configurations"][$j] );
                                                if ( trim($strOptions) != "" )
                                                        $tmp["name"].="  (".$strOptions.")";


                                                $q_product = db_query( "select min_order_amount, shipping_freight from ".PRODUCTS_TABLE.
                                                                " where productID=".
                                                                (int)$_SESSION["gids"][$j] );
                                                $product = db_fetch_row( $q_product );
                                                if ( $product["min_order_amount"] > $_SESSION["counts"][$j] )
                                                        $tmp["min_order_amount"] = $product["min_order_amount"];

                                                $freight_cost += $_SESSION["counts"][$j]*$product["shipping_freight"];

                                                $cart_content[] = $tmp;

                                                $total_price += GetPriceProductWithOption(
                                                                        $_SESSION["configurations"][$j],
                                                                        $_SESSION["gids"][$j] )*$_SESSION["counts"][$j];
                                        }
                                }
                        }
        }

        return array(
                        "cart_content"        => $cart_content,
                        "total_price"        => $total_price,
                        "freight_cost"        => $freight_cost );

}


function cartCheckMinOrderAmount()
{
        $cart_content = cartGetCartContent();
        $cart_content = $cart_content["cart_content"];
        foreach( $cart_content as $cart_item )
                if ( isset($cart_item["min_order_amount"]) )
                        return false;
        return true;
}

function cartCheckMinTotalOrderAmount(){

                $res = cartGetCartContent();
                $d = oaGetDiscountPercent( $res, "" );
                $order["order_amount"] = $res["total_price"] - ($res["total_price"]/100)*$d;
                if($order["order_amount"]<CONF_MINIMAL_ORDER_AMOUNT)
                        return false;
                else
                        return true;
}

function cartAddToCart( $productID, $variants )
{

        $is = GetProductInStockCount( $productID );

        $q = db_query( "select min_order_amount from ".PRODUCTS_TABLE.
                " where productID=".(int)$productID );
        $min_order_amount = db_fetch_row( $q );
        $min_order_amount = $min_order_amount[ 0 ];

        $count_to_order = 1;

        if (!isset($_SESSION["log"])) //save shopping cart in the session variables
        {

                //$_SESSION["gids"] contains product IDs
                //$_SESSION["counts"] contains product quantities
                //                        ($_SESSION["counts"][$i] corresponds to $_SESSION["gids"][$i])
                //$_SESSION["configurations"] contains variants
                //$_SESSION[gids][$i] == 0 means $i-element is 'empty'

                if (!isset($_SESSION["gids"]))
                {
                        $_SESSION["gids"]                = array();
                        $_SESSION["counts"]                = array();
                        $_SESSION["configurations"] = array();
                }

                //check for current item in the current shopping cart content
                $item_index=SearchConfigurationInSessionVariable( $variants, $productID );

                if ( $item_index == -1 )
                                $count_to_order = $min_order_amount;

                if ( $item_index!=-1 ) //increase current product's quantity
                {
                        if (CONF_CHECKSTOCK==0 || $_SESSION["counts"][$item_index]+$count_to_order <= $is)
                                $_SESSION["counts"][$item_index] += $count_to_order;
                        else
                                return false;
                }
                else if (CONF_CHECKSTOCK==0 || $is >= $count_to_order) //no item - add it to $gids array
                {
                        $_SESSION["gids"][] = $productID;
                        $_SESSION["counts"][] = $count_to_order;
                        $_SESSION["configurations"][]=$variants;
                }
                else
                        return false;
        }
        else //authorized customer - get cart from database
        {
                $itemID=SearchConfigurationInDataBase($variants, $productID );
                if ( $itemID !=-1 ) // if this configuration exists in database
                {
                        $q = db_query("select Quantity FROM ".SHOPPING_CARTS_TABLE.
                                " WHERE customerID=".(int)regGetIdByLogin($_SESSION["log"])." AND itemID=".(int)$itemID);
                        $row = db_fetch_row($q);
                        $quantity = $row[0];
                        if (CONF_CHECKSTOCK==0 || $quantity + $count_to_order <= $is)
                                db_query("UPDATE ".SHOPPING_CARTS_TABLE.
                                        " SET Quantity=".(int)($row[0]+$count_to_order).
                                        " WHERE customerID=".(int)regGetIdByLogin($_SESSION["log"]).
                                        " AND itemID=".(int)$itemID);
                        else
                                return false;
                }
                else //insert new item
                {
                        $count_to_order = $min_order_amount;
                        if (CONF_CHECKSTOCK==0 || $is >= $count_to_order)
                        {
                                $itemID=InsertNewItem($variants, $productID );
                                InsertItemIntoCart($itemID);
                                db_query("UPDATE ".SHOPPING_CARTS_TABLE.
                                        " SET Quantity=".(int)$count_to_order.
                                        " WHERE customerID=".(int)regGetIdByLogin($_SESSION["log"]).
                                        " AND itemID=".(int)$itemID);
                        }
                        else
                                return false;
                }
        }

        return true;
}



// *****************************************************************************
// Purpose
// Inputs        $customerID - customer ID
// Remarks
// Returns        returns true if cart is empty for this customer
function cartCartIsEmpty( $log )
{
        $customerID = regGetIdByLogin( $log );
        if ( (int)$customerID > 0 )
        {
                $customerID = (int)$customerID;
                $q_count = db_query( "select count(*) from ".SHOPPING_CARTS_TABLE." where customerID=".(int)$customerID );
                $count = db_fetch_row( $q_count );
                $count = $count[0];
                return ( $count == 0 );
        }
        else
                return true;
}



?><?php
#####################################
# ShopCMS: Скрипт интернет-магазина
# Copyright (c) by ADGroup
# http://shopcms.ru
#####################################


// *****************************************************************************
// Purpose        gets conventional picture filename
// Inputs             string "<picture>,<thumbnail>,<big_picture>"
// Remarks
// Returns
function _getPictureFilename( $stringToParse )
{
        $files=explode(",",$stringToParse);
        if ( count($files) >= 1 ) return trim($files[0]);
        else return "";
}


// *****************************************************************************
// Purpose        gets thumbnail picture filename
// Inputs             string "<picture>,<thumbnail>,<big_picture>"
// Remarks
// Returns
function _getPictureThumbnail( $stringToParse )
{
        $files=explode(",",$stringToParse);
        if ( count($files) >= 2 ) return trim($files[1]);
        else return "";
}

// *****************************************************************************
// Purpose        gets big picture filename
// Inputs             string "<picture>,<thumbnail>,<big_picture>"
// Remarks
// Returns
function _getPictureBigPicture( $stringToParse )
{
        $files=explode(",",$stringToParse);
        if ( count($files) >= 3 ) return trim($files[2]);
        else return "";
}


// *****************************************************************************
// Purpose        insert pictures
// Inputs
//                $stringToParse string has formats "<picture>,<thumbnail>,<big_picture>"
//                $productID - product ID
// Remarks
// Returns
function _insertPictures( $stringToParse, $productID )
{
        // get filename
        $filename = _getPictureFilename( $stringToParse );

        // get thumbnail
        $thumbnail = _getPictureThumbnail( $stringToParse );

    // get big_picture
        $big_picture = _getPictureBigPicture( $stringToParse );

        if ( trim($filename)!="" || trim($thumbnail)!="" || trim($big_picture)!="" )
        {
                db_query("insert into ".PRODUCT_PICTURES.
                                "(productID, filename, thumbnail, enlarged) ".
                                "values( '".(int)$productID."', ".
                                                " '".xEscSQL($filename)."', ".
                                                " '".xEscSQL($thumbnail)."', ".
                                                " '".xEscSQL($big_picture)."' )" );
        }
}

// *****************************************************************************
// Purpose
// Inputs
//                $row - row from file to import
//                $dbc - array of column index, $dbc[<column_name>] -index of <column_name> column
// Remarks
// Returns        true if column value for current row is set
function _columnIsSet($row, $dbc, $column_name)
{
        if ( !strcmp($dbc[$column_name], "not defined") ) return false;
        return ( trim($row[$dbc[$column_name]]) != "" );
}


// *****************************************************************************
// Purpose
// Inputs
//                $row from file to import
// Remarks
// Returns        true if column value is set
function _isCategory($row, $dbc)
{
        if (   !strcmp($dbc["name"], "not defined")  )
                return false;
        if ( _columnIsSet($row, $dbc, "product_code") )
                return false;
        if ( _columnIsSet($row, $dbc, "Price") )
                return false;
        if ( _columnIsSet($row, $dbc, "in_stock") )
                return false;
        if ( _columnIsSet($row, $dbc, "list_price") )
                return false;
        if ( _columnIsSet($row, $dbc, "items_sold") )
                return false;
        if ( _columnIsSet($row, $dbc, "brief_description") )
                return false;
        return true;
}

function fgetcsvs($f, $d, $q='"') {
                $list = array();
                $st = fgets($f);
                if ($st === false || $st === null) return $st;
                while ($st !== "" && $st !== false) {
                        if ($st[0] !== $q) {
                                # Non-quoted.
                                list ($field) = explode($d, $st, 2);
                                $st = substr($st, strlen($field)+strlen($d));
                        } else {
                                # Quoted field.
                                $st = substr($st, 1);
                                $field = "";
                                while (1) {
                                        # Find until finishing quote (EXCLUDING) or eol (including)
                                        preg_match("/^((?:[^$q]+|$q$q)*)/sx", $st, $p);
                                        $part = $p[1];
                                        $partlen = strlen($part);
                                        $st = substr($st, strlen($p[0]));
                                        $field .= str_replace($q.$q, $q, $part);
                                        if (strlen($st) && $st[0] === $q) {
                                                # Found finishing quote.
                                                list ($dummy) = explode($d, $st, 2);
                                                $st = substr($st, strlen($dummy)+strlen($d));
                                                break;
                                        } else {
                                                # No finishing quote - newline.
                                                $st = fgets($f);
                                        }
                                }

                        }
                        $list[] = $field;
                }
                return $list;
}

function myfgetcsv($fname, $del)
{
        $f           = fopen( $fname, "r" );
        $res         = array();
        $firstFlag   = true;
        $columnCount = 0;

        while( $row  = fgetcsvs($f, $del) )
        {
                if ( $firstFlag ) $columnCount = count($row);
                $firstFlag = false;
                while( count($row) < $columnCount ) $row[] = "";
                $res[] = $row;
        }
        fclose($f);
        return $res;
}


function fgetcsvsgz($f, $d, $q='"') {
                $list = array();
                $st = gzgets($f);
                if ($st === false || $st === null) return $st;
                while ($st !== "" && $st !== false) {
                        if ($st[0] !== $q) {
                                # Non-quoted.
                                list ($field) = explode($d, $st, 2);
                                $st = substr($st, strlen($field)+strlen($d));
                        } else {
                                # Quoted field.
                                $st = substr($st, 1);
                                $field = "";
                                while (1) {
                                        # Find until finishing quote (EXCLUDING) or eol (including)
                                        preg_match("/^((?:[^$q]+|$q$q)*)/sx", $st, $p);
                                        $part = $p[1];
                                        $partlen = strlen($part);
                                        $st = substr($st, strlen($p[0]));
                                        $field .= str_replace($q.$q, $q, $part);
                                        if (strlen($st) && $st[0] === $q) {
                                                # Found finishing quote.
                                                list ($dummy) = explode($d, $st, 2);
                                                $st = substr($st, strlen($dummy)+strlen($d));
                                                break;
                                        } else {
                                                # No finishing quote - newline.
                                                $st = gzgets($f);
                                        }
                                }

                        }
                        $list[] = $field;
                }
                return $list;
}

function myfgetcsvgz($fname, $del)
{
        $f                        = gzopen( $fname, "r" );
        $res                = array();
        $firstFlag        = true;
        $columnCount = 0;
        while( $row = fgetcsvsgz($f, $del) )
        {
                if ( $firstFlag )
                        $columnCount = count($row);
                $firstFlag = false;
                while( count($row) < $columnCount )
                        $row[] = "";
                $res[] = $row;
        }
        gzclose($f);
        return $res;
}



// *****************************************************************************
// Purpose         clears database content
// Inputs
// Remarks
// Returns        nothing
function imDeleteAllProducts()
{
        db_query("DELETE FROM ".PRODUCTS_OPTIONS_SET_TABLE);
        db_query("UPDATE ".PRODUCT_OPTIONS_VALUES_TABLE." SET variantID=NULL");
        db_query("DELETE FROM ".PRODUCTS_OPTIONS_VALUES_VARIANTS_TABLE );
        db_query("DELETE FROM ".PRODUCT_OPTIONS_VALUES_TABLE);
        db_query("DELETE FROM ".PRODUCT_OPTIONS_TABLE);
        db_query("DELETE FROM ".RELATED_PRODUCTS_TABLE);
        db_query("DELETE FROM ".PRODUCT_PICTURES);
        db_query("DELETE FROM ".DISCUSSIONS_TABLE);
        db_query("DELETE FROM ".SPECIAL_OFFERS_TABLE);
        db_query("UPDATE ".SHOPPING_CART_ITEMS_TABLE." SET productID = NULL");
        db_query("DELETE FROM ".SHOPPING_CART_ITEMS_CONTENT_TABLE);
        db_query("DELETE FROM ".CATEGORIY_PRODUCT_TABLE);
        db_query("DELETE FROM ".PRODUCTS_TABLE);
                //db_query("DELETE FROM ".CATEGORIES_TABLE." WHERE categoryID>1");
        db_query("DELETE FROM ".CATEGORIES_TABLE);
        db_query("INSERT INTO ".CATEGORIES_TABLE." ( name, parent, categoryID ) values( '".ADMIN_CATEGORY_ROOT."', NULL, 1 )");
}

// *****************************************************************************
// Purpose         clears database content
// Inputs             $data is returned by myfgetcsv ( see comment for this function )
// Remarks
// Returns        import configurator html code
function imGetImportConfiguratorHtmlCode($data)
{
        //skip empty lines
        $i = 0;
        while ($i<count($data) && count($data[$i])>0 &&
                ($n = get_NOTempty_elements_count($data[$i]))
                        < count($data[$i]))
        {
                $i++;
        }
        $notl = $i;


        // display all headers into a form that allows to
        // assign each column a value into database
        $excel_configurator = "<table class=\"adw\">";
        for ($j=0; $j<$n; $j++)
                if (isset($data[$i][$j]))
                  {
                         $excel_configurator .= "
                                <tr class=\"lnst\">
                                        <td><input type=text name=column_name_$j class=\"prc pcw\" value=\"".str_replace("\"","&quot;",$data[$i][$j])."\"></td>
                                        <td>=&gt;</td>
                                        <td>
                                                <select name=\"db_association_".$j."\">
                                                        <option value=\"ignore\">".ADMIN_IGNORE."</option>
                                                        <option value=\"add\">".ADMIN_ADD_AS_NEW_PARAMETER."</option>
                                                        <option value=\"product_code\"".mark_as_selected($data[$i][$j],ADMIN_PRODUCT_CODE).">".ADMIN_PRODUCT_CODE."</option>
                                                        <option value=\"name\"".mark_as_selected($data[$i][$j],ADMIN_PRODUCT_NAME).">".ADMIN_PRODUCT_NAME."</option>
                                                        <option value=\"Price\"".mark_as_selected($data[$i][$j],ADMIN_PRODUCT_PRICE).">".ADMIN_PRODUCT_PRICE."</option>
                                                        <option value=\"list_price\"".mark_as_selected($data[$i][$j],ADMIN_PRODUCT_LISTPRICE).">".ADMIN_PRODUCT_LISTPRICE."</option>
                                                        <option value=\"in_stock\"".mark_as_selected($data[$i][$j],ADMIN_PRODUCT_INSTOCK).">".ADMIN_PRODUCT_INSTOCK."</option>
                                                        <option value=\"items_sold\"".mark_as_selected($data[$i][$j],ADMIN_PRODUCT_SOLD).">".ADMIN_PRODUCT_SOLD."</option>
                                                        <option value=\"description\"".mark_as_selected($data[$i][$j],ADMIN_PRODUCT_DESC).">".ADMIN_PRODUCT_DESC."</option>
                                                        <option value=\"brief_description\"".mark_as_selected($data[$i][$j],ADMIN_PRODUCT_BRIEF_DESC).">".ADMIN_PRODUCT_BRIEF_DESC."</option>
                                                        <option value=\"pictures\"".mark_as_selected($data[$i][$j],ADMIN_PHOTOS).">".ADMIN_PHOTOS."</option>
                                                        <option value=\"sort_order\"".mark_as_selected($data[$i][$j],ADMIN_SORT_ORDER).">".ADMIN_SORT_ORDER."</option>
                                                        <option value=\"meta_keywords\"".mark_as_selected($data[$i][$j],ADMIN_META_KEYWORDS).">".ADMIN_META_KEYWORDS."</option>
                                                        <option value=\"meta_description\"".mark_as_selected($data[$i][$j],ADMIN_META_DESCRIPTION).">".ADMIN_META_DESCRIPTION."</option>
                                                        <option value=\"shipping_freight\"".mark_as_selected($data[$i][$j],ADMIN_SHIPPING_FREIGHT).">".ADMIN_SHIPPING_FREIGHT."</option>
                                                        <option value=\"weight\"".mark_as_selected($data[$i][$j],ADMIN_PRODUCT_WEIGHT).">".ADMIN_PRODUCT_WEIGHT."</option>
                                                        <option value=\"min_order_amount\"".mark_as_selected($data[$i][$j],ADMIN_MIN_ORDER_AMOUNT).">".ADMIN_MIN_ORDER_AMOUNT."</option>
                                                        <option value=\"title\"".mark_as_selected($data[$i][$j],ADMIN_PRODUCT_TITLE_PAGE).">".ADMIN_PRODUCT_TITLE_PAGE."</option>
                                                        <option value=\"eproduct_filename\"".mark_as_selected($data[$i][$j],ADMIN_EPRODUCT_FILENAME).">".ADMIN_EPRODUCT_FILENAME."</option>
                                                        <option value=\"eproduct_available_days\"".mark_as_selected($data[$i][$j],ADMIN_EPRODUCT_AVAILABLE_DAYS2).">".ADMIN_EPRODUCT_AVAILABLE_DAYS2."</option>
                                                        <option value=\"eproduct_download_times\"".mark_as_selected($data[$i][$j],ADMIN_EPRODUCT_DOWNLOAD_TIMES).">".ADMIN_EPRODUCT_DOWNLOAD_TIMES."</option>
                                                </select>
                                        </td>
                                </tr>";
                }
        $excel_configurator .= "</table>";
        return $excel_configurator;
}


// *****************************************************************************
// Purpose         read db_association select control
//                        ( see GetImportConfiguratorHtmlCode )
// Inputs
// Remarks
// Returns
function _readDb_associationSelectControl()
{
        $db_association = array(); // array select control values
        foreach( $_POST as $key => $val )
        {
                if (strstr($key, "db_association_"))
                {
                        $i = str_replace("db_association_", "", $key);
                        if ( $val != "pictures" )
                                $db_association[$i] = $val;
                }
        }
        return $db_association;
}

// *****************************************************************************
// Purpose         get index select control set to "pictures" value
//                        ( see GetImportConfiguratorHtmlCode )
// Inputs
// Remarks
// Returns
function _getIndexArraySelectPictures()
{
        $dbcPhotos        = array(); // index array of "pictures"
        foreach( $_POST as $key => $val )
        {
                if (strstr($key, "db_association_"))
                {
                        $i = str_replace("db_association_", "", $key);
                        if ( $val == "pictures" )
                        $dbcPhotos[] = $i;
                }
        }
        return $dbcPhotos;
}


// *****************************************************************************
// Purpose         read column_name control
//                        ( see GetImportConfiguratorHtmlCode )
// Inputs
// Remarks
// Returns
function _readColumn_nameControl($dbcPhotos)
{

        $cname = array();
        foreach( $_POST as $key => $val )
        {
                if (strstr($key, "column_name_"))
                {
                        $i = str_replace("column_name_", "", $key);
                        $searchFlag = false;
                        for(  $j=0; $j < count($dbcPhotos); $j ++ )
                                if ($i == $dbcPhotos[$j])
                                        $searchFlag = true;

                        if ( ! $searchFlag )
                                $cname[$i] = $val;
                }
        }
        return $cname;
}


// *****************************************************************************
// Purpose         now reverse -- create backwards
//                         association table: db_column -> file_column
// Inputs
// Remarks
// Returns
function _createBackwards( $db_association )
{
        $dbc = array(
                "name"                        => "not defined",
                "product_code"                => "not defined",
                "Price"                       => "not defined",
                "in_stock"                    => "not defined",
                "list_price"                  => "not defined",
                "items_sold"                  => "not defined",
                "description"                 => "not defined",
                "brief_description"           => "not defined",
                "sort_order"                  => "not defined",
                "meta_keywords"               => "not defined",
                "meta_description"            => "not defined",
                "shipping_freight"            => "not defined",
                "weight"                      => "not defined",
                "free_shipping"               => "not defined",
                "min_order_amount"            => "not defined",
                "title"                       => "not defined",
                "eproduct_filename"           => "not defined",
                "eproduct_available_days"     => "not defined",
                "eproduct_download_times"     => "not defined"
        );
        foreach( $db_association as $i => $value )
        {
                if ($value == "name") $dbc["name"] = $i;
                else if ($value == "product_code")                                $dbc["product_code"] = $i;
                else if ($value == "Price")                                                $dbc["Price"] = $i;
                else if ($value == "in_stock")                                        $dbc["in_stock"] = $i;
                else if ($value == "list_price")                                $dbc["list_price"] = $i;
                else if ($value == "items_sold")                                $dbc["items_sold"] = $i;
                else if ($value == "description")                                $dbc["description"] = $i;
                else if ($value == "brief_description")                        $dbc["brief_description"] = $i;
                else if ($value == "pictures")                                        $dbc["pictures"] = $i;
                else if ($value == "sort_order")                                $dbc["sort_order"] = $i;
                else if ($value == "meta_keywords" )                        $dbc["meta_keywords"] = $i;
                else if ($value == "meta_description" )                        $dbc["meta_description"] = $i;
                else if ($value == "shipping_freight" )                        $dbc["shipping_freight"] = $i;
                else if ($value == "weight" )                                        $dbc["weight"] = $i;
                else if ($value == "free_shipping" )                        $dbc["free_shipping"] = $i;
                else if ($value == "min_order_amount" )                        $dbc["min_order_amount"] = $i;
                else if ($value == "title" )        $dbc["title"] = $i;
                else if ($value == "eproduct_filename" )                $dbc["eproduct_filename"] = $i;
                else if ($value == "eproduct_available_days" )        $dbc["eproduct_available_days"] = $i;
                else if ($value == "eproduct_download_times" )        $dbc["eproduct_download_times"] = $i;
        }
        return $dbc;
}


// *****************************************************************************
// Purpose         add new product extra option
// Inputs
// Remarks
// Returns
function _addExtraOptionToDb( $db_association, $cname )
{
        $updated_extra_option = array();
        for ($i=0; $i<count($cname); $i++)
                $updated_extra_option[$i] = 0;

        foreach( $db_association as $i => $value )
        {
                if ($value == "add")
                {
                        $q = db_query("select count(*) from ".PRODUCT_OPTIONS_TABLE.
                                " where name LIKE '".xToText(trim($cname[$i]))."'");
                        $row = db_fetch_row($q);
                        if (!$row[0])         // no option exists => insert new
                        {
                                db_query("insert into ".PRODUCT_OPTIONS_TABLE.
                                        " (name) values ('".xToText(trim($cname[$i]))."')");
                                $op_id = db_insert_id("PRODUCT_OPTIONS_GEN");
                        }
                        else                 // get current $id
                        {
                                $q = db_query("select optionID from ".PRODUCT_OPTIONS_TABLE.
                                        " where name LIKE '".xToText(trim($cname[$i]))."'");
                                $op_id = db_fetch_row($q);
                                $op_id = $op_id[0];
                        }
                        //update extra options list
                        $updated_extra_option[$i] = $op_id;
                }
        }
        return $updated_extra_option;
}



function imReadImportConfiguratorSettings()
{       //echo "<pre>";
        // read db_association select control ( see GetImportConfiguratorHtmlCode )
        $db_association = _readDb_associationSelectControl();
        //var_dump($db_association);

        // get index select control set to "pictures" value ( see GetImportConfiguratorHtmlCode )
        $dbcPhotos = _getIndexArraySelectPictures();
        //var_dump($dbcPhotos);
        // read column_name input field ( see GetImportConfiguratorHtmlCode )
        $cname = _readColumn_nameControl( $dbcPhotos );
        //echo "cname";        var_dump($cname);

        // now reverse -- create backwards association table: db_column -> file_column
        $dbc = _createBackwards( $db_association );
        //var_dump($dbc);
        //var_dump($db_association);
        //var_dump($cname);

        // add new extra option to database
        $updated_extra_option = _addExtraOptionToDb( $db_association, $cname );

        $res = array();
        $res["db_association"]       = $db_association;
        $res["dbcPhotos"]            = $dbcPhotos;
        $res["dbc"]                  = $dbc;
        $res["updated_extra_option"] = $updated_extra_option;
        return $res;
}


// *****************************************************************************
// Purpose         import row to database
// Inputs
// Remarks
// Returns
function _importCategory( $row, $dbc, &$parents, $dbcPhotos, & $currentCategoryID )
{
        $sort_order = 0;
        if ( strcmp( $dbc["sort_order"], "not defined") )
                $sort_order = (int)$row[ $dbc["sort_order"] ];

        // set picture file name
        $picture_file_name="";
        if ( count($dbcPhotos) > 0 )
                $picture_file_name=trim($row[ $dbcPhotos[0] ]);

        //
        $row[ "not defined" ] = "";
        $cname = trim($row[$dbc["name"]]);
        if ($cname == "") return;
        for ($sublevel=0;
                $sublevel<strlen($cname) && $cname[$sublevel] == '!'; $sublevel++);
        $cname = substr($cname,$sublevel);

        $sl = $sublevel;
        if (!isset($parents[$sublevel])) //not many '!' -- searching for root category
        {
                for (; $sl>0 && !isset($parents[$sl]); $sl--);
        }

        $q = db_query("select count(*) from ".CATEGORIES_TABLE.
                        " where categoryID>1 and name LIKE '".xToText(trim($cname))."' ".
                        " and parent=".(int)$parents[$sl]);
        $rowdb = db_fetch_row($q);
        if ( $rowdb[0] == 0  ) // insert category
        {
                db_query("insert into ".CATEGORIES_TABLE.
                         " (name, parent, products_count, description, ".
                         " picture, products_count_admin, meta_keywords, meta_description, sort_order, title) ".
                         "values ('".xToText(trim($cname))."',".(int)$parents[$sl].",0, ".
                                " '".xEscSQL($row[ $dbc["description"] ])."', ".
                                " '".xEscSQL(trim($picture_file_name))."',0, ".
                                " '".xToText(trim($row[ $dbc["meta_keywords"] ]))."', ".
                                " '".xToText(trim($row[ $dbc["meta_description"] ]))."', ".(int)$sort_order.", '".xToText(trim($row[ $dbc["title"] ]))."');");
                $currentCategoryID = db_insert_id("CATEGORIES_GEN");
        }
        else
        {
                $q = db_query("select categoryID from ".CATEGORIES_TABLE.
                        " where categoryID>1 and name LIKE '".xToText(trim($cname))."' and parent=".(int)$parents[$sl]);
                $rowdb = db_fetch_row($q);
                $currentCategoryID = $rowdb[0];

                $query = "";
                if (strcmp($dbc["description"], "not defined"))
                        $query .= " description = '".xEscSQL($row[$dbc["description"]])."'";
                if (strcmp($dbc["sort_order"], "not defined"))
                {
                        if (strlen($query)>0) $query .= ",";
                        $query .= " sort_order = ".(int)$sort_order;
                }
                if (count($dbcPhotos) > 0)
                {
                        if (strlen($query)>0) $query .= ",";
                        $query .= " picture = '".xEscSQL(trim($picture_file_name))."'";
                }

                if (strlen($query) > 0)
                        db_query("update ".CATEGORIES_TABLE.
                                " set ".$query." where categoryID=".(int)$currentCategoryID);
        }
        $parents[$sublevel+1] = $currentCategoryID;
}


function _importProductPictures( $row, $dbcPhotos, $productID )
{
        // delete pictures for this product
        db_query( "delete from ".PRODUCT_PICTURES." where productID=".(int)$productID );

        for( $j=0; $j < count($dbcPhotos); $j++ ) _insertPictures( $row[ $dbcPhotos[$j] ], $productID );

        $q = db_query( "select default_picture from ".PRODUCTS_TABLE." where productID=".(int)$productID );
        $row = db_fetch_row($q);
        //if (!$row || !$row[0])
        {
                $q = db_query( "select photoID from ".PRODUCT_PICTURES." where productID=".(int)$productID );
                $row = db_fetch_row($q);
                if ($row)
                {
                        // update DEFAULT PICTURE information
                        db_query( "update ".PRODUCTS_TABLE." set default_picture=".(int)$row[0]." where productID=".(int)$productID);
                }
        }
}

function _importExtraOptionValues($row, $productID, $updated_extra_option)
{

/*var_dump($updated_extra_option);

var_dump($row);*/

        //now setup all product's extra options
        for ($j=0; $j<count($updated_extra_option); $j++)
        {
                if (isset($updated_extra_option[$j]) && $updated_extra_option[$j]) //a column which is an extra option
                {
                        $optionID = $updated_extra_option[$j];

                        $curr_value = trim($row[$j]);
                        $default_variantID = 0;
                        if (strpos($curr_value,"{")===0 && strpos($curr_value,"}")==strlen($curr_value)-1) //is it a selectable value?
                        {
                                $curr_value = substr( $curr_value, 1, strlen($curr_value)-2);
                                $values_options = explode(",",$curr_value);
                                //delete all current product option configuration
                                db_query("delete from ".PRODUCT_OPTIONS_VALUES_TABLE.
                                        " where optionID=".(int)$optionID." and productID=".(int)$productID);
                                db_query("delete from ".PRODUCTS_OPTIONS_SET_TABLE.
                                        " where optionID=".(int)$optionID." and productID=".(int)$productID);

                                foreach ($values_options as $key => $val)
                                {
                                        if (strstr($val,"=")) // current value is "OPTION_NAME=SURCHARGE", e.g. red=3, xl=1, s=-1, m=0
                                        {
                                                $a = explode("=",$val);
                                                $val_name = $a[0];
                                                $val_surcharge = (float)$a[1];
                                        }
                                        else // current value is a option value name, e.g. red, xl, s, m
                                        {
                                                $val_name = $val;
                                                $val_surcharge = 0;
                                        }

                                        //search for a specified option value in the database
                                        $variantID = optOptionValueExists($optionID, $val_name);
                                        if ( !$variantID ) //does not exist => add new variant value
                                        {
                                                $variantID = optAddOptionValue($optionID, $val_name, 0);
                                        }
                                        if (!$default_variantID) $default_variantID = $variantID;

                                        //now append this variant value to the product
                                        db_query("insert into ".PRODUCTS_OPTIONS_SET_TABLE.
                                                " (productID, optionID, variantID, price_surplus) ".
                                                " values (".(int)$productID.", ".(int)$optionID.", ".(int)$variantID.", ".xEscSQL($val_surcharge).");");

                                }

                                //assign default variant ID - first option in the variants list is default
                                if ($default_variantID)
                                {
                                        db_query("insert into ".PRODUCT_OPTIONS_VALUES_TABLE.
                                                " (optionID, productID, option_type, option_show_times, variantID) ".
                                                " values (".(int)$optionID.", ".(int)$productID.", 1, 1, ".(int)$default_variantID.")");
                                }

                        }
                        else // a custom fixed value
                        {
                                db_query("delete from ".PRODUCT_OPTIONS_VALUES_TABLE.
                                        " where optionID=".(int)$optionID." and productID=".(int)$productID);
                                db_query("insert into ".PRODUCT_OPTIONS_VALUES_TABLE.
                                        " (optionID, productID, option_value) ".
                                        " values (".(int)$optionID.", ".(int)$productID.", '".xEscSQL($curr_value)."')");
                        }
                }
        }
}


// *****************************************************************************
// Purpose         import row to database
// Inputs
// Remarks
// Returns
function _importProduct( $row, $dbc, $identity_column, $dbcPhotos,
                        $updated_extra_option, $currentCategoryID  )
{
        $row["not defined"] = "";
        $row[$identity_column] = trim($row[$identity_column]);
        //search for product within current category
        $q = db_query("select productID, categoryID, customers_rating  from ".
                PRODUCTS_TABLE." where categoryID=".(int)$currentCategoryID." and ".xEscSQL($_POST["update_column"]).
                " LIKE '".xEscSQL(trim($row[$identity_column]))."'");
        $rowdb = db_fetch_row($q);

        if (!$rowdb && $_POST["update_column"] == 'product_code') //not found
        {
         //search for product in all categories
                $q = db_query("select productID, categoryID, customers_rating  from ".
                        PRODUCTS_TABLE." where ".xEscSQL($_POST["update_column"]).
                        " LIKE '".xEscSQL(trim($row[$identity_column]))."'");
                $rowdb = db_fetch_row($q);
        }

        if ( $rowdb ) //update product info
        {
                $productID = $rowdb["productID"];

                $rowdb =  GetProduct( $productID );

                if ( strcmp($dbc["Price"], "not defined") )
                {
                        $Price        = $row[ $dbc["Price"] ];
                        $Price        = str_replace( " ",  "", $Price );
                        $Price        = str_replace( ",", ".", $Price );
                        $Price        = (float)$Price;
                }
                else $Price = $rowdb["Price"];
                if ( strcmp($dbc["list_price"], "not defined") )
                {
                        $list_price        = $row[ $dbc["list_price"] ];
                        $list_price        = str_replace( " ",  "", $list_price );
                        $list_price        = str_replace( ",", ".", $list_price );
                        $list_price = (float)$list_price;
                }
                else $list_price = $rowdb["list_price"];
                if ( strcmp($dbc["sort_order"], "not defined") )
                        $sort_order = (int)$row[ $dbc["sort_order"] ];
                else $sort_order = $rowdb["sort_order"];
                if ( strcmp($dbc["in_stock"], "not defined") )
                        $in_stock = (int)$row[ $dbc["in_stock"] ];
                else $in_stock = $rowdb["in_stock"];
                if ( strcmp($dbc["eproduct_filename"], "not defined") )
                        $eproduct_filename = $row[ $dbc["eproduct_filename"] ];
                else $eproduct_filename = $rowdb["eproduct_filename"];
                if ( strcmp($dbc["eproduct_available_days"], "not defined") )
                        $eproduct_available_days = (int)$row[ $dbc["eproduct_available_days"] ];
                else $eproduct_available_days = $rowdb["eproduct_available_days"];
                if ( strcmp($dbc["eproduct_download_times"], "not defined") )
                        $eproduct_download_times = (int)$row[ $dbc["eproduct_download_times"] ];
                else $eproduct_download_times = $rowdb["eproduct_download_times"];
                if ( strcmp($dbc["weight"], "not defined") )
                        $weight = (float)$row[ $dbc["weight"] ];
                else $weight = $rowdb["weight"];
                if ( strcmp($dbc["free_shipping"], "not defined") )
                        $free_shipping = ( trim($row[$dbc["free_shipping"]])=="+"?1:0 );
                else $free_shipping = $rowdb["free_shipping"];
                if ( strcmp($dbc["min_order_amount"], "not defined") )
                        $min_order_amount = (int)$row[ $dbc["min_order_amount"] ];
                else $min_order_amount = $rowdb["min_order_amount"];
                if ( strcmp($dbc["shipping_freight"], "not defined") )
                        $shipping_freight = (float)$row[ $dbc["shipping_freight"] ];
                else $shipping_freight = $rowdb["shipping_freight"];
                if ( strcmp($dbc["description"], "not defined") )
                        $description = $row[ $dbc["description"] ];
                else $description = $rowdb["description"];
                if ( strcmp($dbc["brief_description"], "not defined") )
                        $brief_description = $row[ $dbc["brief_description"] ];
                else $brief_description = $rowdb["brief_description"];
                if ( strcmp($dbc["product_code"], "not defined") )
                        $product_code = $row[ $dbc["product_code"] ];
                else $product_code = xHtmlSpecialCharsDecode($rowdb["product_code"]);
                if ( strcmp($dbc["meta_description"], "not defined") )
                        $meta_description = $row[ $dbc["meta_description"] ];
                else $meta_description = xHtmlSpecialCharsDecode($rowdb["meta_description"]);
                if ( strcmp($dbc["meta_keywords"], "not defined") )
                        $meta_keywords = $row[ $dbc["meta_keywords"] ];
                else $meta_keywords = xHtmlSpecialCharsDecode($rowdb["meta_keywords"]);
                if ( strcmp($dbc["name"], "not defined") )
                        $name = $row[ $dbc["name"] ];
                else $name = xHtmlSpecialCharsDecode($rowdb["name"]);
                if ( strcmp($dbc["title"], "not defined") )
                        $title = $row[ $dbc["title"] ];
                else $title = xHtmlSpecialCharsDecode($rowdb["title"]);


                $categoryID       = $rowdb["categoryID"];
                $customers_rating = $rowdb["customers_rating"];
                $ProductIsProgram = trim($eproduct_filename) != "";
                UpdateProduct( $productID,
                                $categoryID, $name, $Price, $description,
                                $in_stock, $customers_rating,
                                $brief_description, $list_price,
                                $product_code, $sort_order,
                                $ProductIsProgram,
                                "",
                                $eproduct_available_days,
                                $eproduct_download_times,
                                $weight, $meta_description, $meta_keywords,
                                $free_shipping, $min_order_amount, $shipping_freight, null, $title, 0 );
        }
        else // add new product
        {
                $Price                   = 0.0;
                $list_price              = 0.0;
                $sort_order              = 0;
                $in_stock                = 0;
                $eproduct_filename       = "";
                $eproduct_available_days = 0;
                $eproduct_download_times = 0;
                $weight                  = 0.0;
                $free_shipping           = 0;
                $min_order_amount        = 1;
                $shipping_freight        = 0.0;

                if ( strcmp($dbc["Price"], "not defined") )
                        $Price        = (float)$row[ $dbc["Price"] ];
                if ( strcmp($dbc["list_price"], "not defined") )
                        $list_price = (float)$row[ $dbc["list_price"] ];
                if ( strcmp($dbc["sort_order"], "not defined") )
                        $sort_order = (int)$row[ $dbc["sort_order"] ];
                if ( strcmp($dbc["in_stock"], "not defined") )
                        $in_stock = (int)$row[ $dbc["in_stock"] ];
                if ( strcmp($dbc["eproduct_filename"], "not defined") )
                        $eproduct_filename = $row[ $dbc["eproduct_filename"] ];
                if ( strcmp($dbc["eproduct_available_days"], "not defined") )
                        $eproduct_available_days = (int)$row[ $dbc["eproduct_available_days"] ];
                if ( strcmp($dbc["eproduct_download_times"], "not defined") )
                        $eproduct_download_times = (int)$row[ $dbc["eproduct_download_times"] ];
                if ( strcmp($dbc["weight"], "not defined") )
                        $weight = (float)$row[ $dbc["weight"] ];
                if ( strcmp($dbc["free_shipping"], "not defined") )
                        $free_shipping = ( trim($row[$dbc["free_shipping"]])=="+"?1:0 );
                if ( strcmp($dbc["min_order_amount"], "not defined") )
                        $min_order_amount = (int)$row[ $dbc["min_order_amount"] ];
                if ( strcmp($dbc["shipping_freight"], "not defined") )
                        $shipping_freight = (float)$row[ $dbc["shipping_freight"] ];

                $ProductIsProgram = trim($row[$dbc["eproduct_filename"]]) != "";
                $productID = AddProduct(
                                $currentCategoryID, $row[ $dbc["name"] ], $Price, $row[ $dbc["description"] ],
                            $in_stock,
                                $row[ $dbc["brief_description"] ], $list_price,
                            $row[ $dbc["product_code"] ], $sort_order,
                                $ProductIsProgram, "",
                                $eproduct_available_days, $eproduct_download_times,
                                $weight, $row[$dbc["meta_description"]], $row[$dbc["meta_keywords"]],
                                $free_shipping, $min_order_amount, $shipping_freight,
                                CONF_DEFAULT_TAX_CLASS, $row[ $dbc["title"] ],0 );
        }
        if (strlen($eproduct_filename))
                SetProductFile( $productID, $eproduct_filename );

        _importExtraOptionValues( $row, $productID, $updated_extra_option );

        if ( count($dbcPhotos) > 0 )
                _importProductPictures( $row, $dbcPhotos, $productID );

}

// *****************************************************************************
// Purpose         import row to database
// Inputs
// Remarks
// Returns
function imImportRowToDataBase( $row, $dbc, $identity_column,
        $dbcPhotos, $updated_extra_option, &$parents, &$currentCategoryID )
{
        if ( _isCategory($row, $dbc) )
        {
                _importCategory( $row, $dbc, $parents, $dbcPhotos, $currentCategoryID );
        }
        else
                _importProduct( $row, $dbc, $identity_column,
                        $dbcPhotos, $updated_extra_option, $currentCategoryID );
}


?><?php
#####################################
# ShopCMS: Скрипт интернет-магазина
# Copyright (c) by ADGroup
# http://shopcms.ru
#####################################

function catInstall()
{
        db_query("insert into ".CATEGORIES_TABLE." ( name, parent, categoryID ) values ( '".ADMIN_CATEGORY_ROOT."', NULL, 1 )");
}

function getcontentcat($categoryID){
         $out = array();
         $cnt = 0;
         $q = db_query("select Owner from ".RELATED_CONTENT_CAT_TABLE." where categoryID=".(int)$categoryID);
         while ($row = db_fetch_row($q))
         {
                $qd = db_query("select aux_page_name from ".AUX_PAGES_TABLE." where aux_page_ID=".(int)$row["Owner"]);
                $rowd = db_fetch_row($qd);
                $out[$cnt][0] = $row["Owner"];
                $out[$cnt][1] = $rowd["aux_page_name"];
                $cnt++;
         }
         return $out;
}

function processCategories($level, $path, $sel)
{
        //returns an array of categories, that will be presented by the category_navigation.tpl template

        //$categories[] - categories array
        //$level - current level: 0 for main categories, 1 for it's subcategories, etc.
        //$path - path from root to the selected category (calculated by calculatePath())
        //$sel -- categoryID of a selected category

        //returns an array of (categoryID, name, level)

        //category tree is being rolled out "by the path", not fully

        $out = array();
        $cnt = 0;

        $parent = $path[$level]["parent"];
        if ( $parent == "" || $parent == null ) $parent = "NULL";

        $q = db_query("select categoryID, name from ".CATEGORIES_TABLE.
                " where parent=".(int)$path[$level]["parent"]." order by sort_order, name");
        $c_path = count($path);
        while ($row = db_fetch_row($q))
        {
                $out[$cnt][0] = $row["categoryID"];
                $out[$cnt][1] = $row["name"];
                $out[$cnt][2] = $level;
                $cnt++;

                //process subcategories?
                if ($level+1<$c_path && $row["categoryID"] == $path[$level+1])
                {
                        $sub_out = processCategories($level+1,$path,$sel);
                        //add $sub_out to the end of $out
                        for ($j=0; $j<count($sub_out); $j++)
                        {
                                $out[] = $sub_out[$j];
                                $cnt++;
                        }
                }
        }
        return $out;
} //processCategories

function fillTheCList($parent,$level) //completely expand category tree
{

        $q = db_query("select categoryID, name, products_count, products_count_admin, parent FROM ".
                CATEGORIES_TABLE." WHERE parent=".(int)$parent." ORDER BY sort_order, name");
        $a = array(); //parents
        while ($row = db_fetch_row($q))
        {
                $row["level"] = $level;
                $a[] = $row;
                //process subcategories
                $b = fillTheCList($row[0],$level+1);
                //add $b[] to the end of $a[]
                $cc_b = count($b);
                for ($j=0; $j<$cc_b; $j++) $a[] = $b[$j];
        }
        return $a;

} //fillTheCList

function _recursiveGetCategoryCompactCList( $path, $level )
{
        $q = db_query( "select categoryID, parent, name, products_count from ".CATEGORIES_TABLE.
                                " where parent=".(int)$path[$level-1]["categoryID"]." order by sort_order, name " );
        $res = array();
        $selectedCategoryID = null;
        $c_path = count($path);
        while( $row=db_fetch_row($q) )
        {

                $row["level"] = $level;
                $res[] = $row;
                if ( $level <= $c_path-1 )
                {
                        if ( (int)$row["categoryID"] == (int)$path[$level]["categoryID"] )
                        {
                                $selectedCategoryID = $row["categoryID"];
                                $arres = _recursiveGetCategoryCompactCList( $path, $level+1 );

                                $c_arres = count($arres);
                                for ($i=0; $i<$c_arres; $i++) $res[] = $arres[$i];
                        }
                }
        }

        return $res;
}

function getcontentcatresc( $catID )
{
        $q = db_query( "select categoryID, name, products_count, description, picture  from ".CATEGORIES_TABLE.
                                " where parent=".(int)$catID." order by sort_order, name " );
        $res = array();
        while( $row=db_fetch_row($q) ) $res[] = $row;
        return $res;
}

function catExpandCategory( $categoryID, $sessionArrayName )
{
        $existFlag = false;
        foreach( $_SESSION[$sessionArrayName] as $key => $value )
                if ( $value == $categoryID )
                {
                        $existFlag = true;
                        break;
                }
        if ( !$existFlag ) $_SESSION[$sessionArrayName][] = $categoryID;

}

function catShrinkCategory( $categoryID, $sessionArrayName )
{
        foreach( $_SESSION[$sessionArrayName] as $key => $value )
        {
                if ( $value == $categoryID ) unset( $_SESSION[$sessionArrayName][$key] );
        }
}

function catExpandCategoryp( $sessionArrayName )
{
        $categoryID = 0;
        $cats = array();
        $q = db_query("select categoryID FROM ".CATEGORIES_TABLE." ORDER BY sort_order, name");
        while ($row = db_fetch_row($q)) $_SESSION[$sessionArrayName][] = $row[0];
}

function catShrinkCategorym( $sessionArrayName )
{
        unset( $_SESSION[$sessionArrayName]);
        $_SESSION["expcat"] = array(1);
}

function catGetCategoryCompactCList( $selectedCategoryID )
{
        $path = catCalculatePathToCategory( $selectedCategoryID );
        $res = array();
        $res[] = array( "categoryID" => 1, "parent" => null,
                                        "name" => ADMIN_CATEGORY_ROOT, "level" => 0 );
        $q = db_query( "select categoryID, parent, name, products_count from ".CATEGORIES_TABLE.
                                " where parent=1 ".
                                " order by sort_order, name " );
        $c_path = count($path);
        while( $row = db_fetch_row($q) )
        {
                $row["level"] = 1;
                $res[] = $row;
                if ( $c_path > 1 )
                {
                        if ( $row["categoryID"] == $path[1]["categoryID"] )
                        {
                                $arres = _recursiveGetCategoryCompactCList( $path, 2 );
                                $c_arres = count($arres);
                                for ($i=0; $i<$c_arres; $i++) $res[] = $arres[$i];

                        }
                }
        }
        return $res;
}



// *****************************************************************************
// Purpose        gets category tree to render it on HTML page
// Inputs
//                        $parent - must be 0
//                        $level        - must be 0
//                        $expcat - array of category ID that expanded
// Remarks
//                        array of item
//                                for each item
//                                        "products_count"                        -                count product in category including
//                                                                                                                        subcategories excluding enabled product
//                                        "products_count_admin"                -                count product in category
//                                                                                                                        without count product subcategory
//                                        "products_count_category"        -
// Returns        nothing
function _recursiveGetCategoryCList( $parent, $level, $expcat, $_indexType = 'NUM', $cprod = false, $ccat = true)
{
global $fc, $mc;

        $rcat  = array_keys ($mc, (int)$parent);
        $result = array(); //parents

        $crcat = count($rcat);
        for ($i=0; $i<$crcat; $i++) {

        $row = $fc[(int)$rcat[$i]];
                if (!file_exists("data/category/".$row["picture"])) $row["picture"] = "";
                $row["level"] = $level;
                $row["ExpandedCategory"] = false;
                if ( $expcat != null )
                {
                        foreach( $expcat as $categoryID )
                        {
                                if ( (int)$categoryID == (int)$row["categoryID"] )
                                {
                                        $row["ExpandedCategory"] = true;
                                        break;
                                }
                        }
                }
                else
                        $row["ExpandedCategory"] = true;

                if ($ccat) {$row["products_count_category"] = catGetCategoryProductCount( $row["categoryID"], $cprod );}

                $row["ExistSubCategories"] = ( $row["subcount"] != 0 );

                if($_indexType=='NUM')
                        $result[] = $row;
                elseif ($_indexType=='ASSOC')
                        $result[$row['categoryID']] = $row;


                if ( $row["ExpandedCategory"] )
                {
                        //process subcategories
                        $subcategories = _recursiveGetCategoryCList( $row["categoryID"],
                                $level+1, $expcat, $_indexType, $cprod, $ccat);

                        if($_indexType=='NUM'){

                                //add $subcategories[] to the end of $result[]
                                for ($j=0; $j<count($subcategories); $j++)
                                        $result[] = $subcategories[$j];
                        }
                        elseif ($_indexType=='ASSOC'){

                                //add $subcategories[] to the end of $result[]
                                foreach ($subcategories as $_sub){

                                        $result[$_sub['categoryID']] = $_sub;
                                }
                        }

                }
        }
        return $result;
}


// *****************************************************************************
// Purpose        gets category tree to render it on HTML page
// Inputs
// Remarks
// Returns        nothing
function catGetCategoryCList( $expcat = null, $_indexType='NUM', $cprod = false, $ccat = true  )
{
        return _recursiveGetCategoryCList( 1, 0, $expcat, $_indexType, $cprod, $ccat);
}

function catGetCategoryCListMin()
{
        return _recursiveGetCategoryCList( 1, 0, null, 'NUM', false, false);
}

// *****************************************************************************
// Purpose        gets product count in category
// Inputs
// Remarks  this function does not keep in mind subcategories
// Returns        nothing
function catGetCategoryProductCount( $categoryID, $cprod = false )
{
        if (!$categoryID) return 0;

        $res = 0;
        $sql = "
                select count(*) FROM ".PRODUCTS_TABLE."
                WHERE categoryID=".(int)$categoryID."".($cprod?" AND enabled>0":"");
        $q = db_query($sql);
        $t = db_fetch_row($q);
        $res += $t[0];
        if($cprod)
                $sql = "
                        select COUNT(*) FROM ".PRODUCTS_TABLE." AS prot
                        LEFT JOIN ".CATEGORIY_PRODUCT_TABLE." AS catprot
                        ON prot.productID=catprot.productID
                        WHERE catprot.categoryID=".(int)$categoryID." AND prot.enabled>0
                ";
        else
                $sql = "
                        select count(*) from ".CATEGORIY_PRODUCT_TABLE.
                        " where categoryID=".(int)$categoryID
                ;
        $q1 = db_query($sql);
        $row = db_fetch_row($q1);
        $res += $row[0];
        return $res;
}

function update_sCount($parent)
{
global $fc, $mc;

        $rcat = array_keys ($mc, (int)$parent);
        $crcat = count($rcat);
        for ($i=0; $i<$crcat; $i++) {

        $rowsub = $fc[(int)$rcat[$i]];
        $countsub  = count(array_keys ($mc, (int)$rowsub["categoryID"]));

        db_query("UPDATE ".CATEGORIES_TABLE.
                        " SET subcount=".(int)$countsub." ".
                        " WHERE categoryID=".(int)$rcat[$i]);

        $rowsubExist = ( $countsub != 0 );
        if ( $rowsubExist ) update_sCount($rowsub["categoryID"]);
        }
}

function update_pCount($parent)
{
        update_sCount($parent);

        $q = db_query("select categoryID FROM ".CATEGORIES_TABLE.
                " WHERE categoryID>1 AND parent=".(int)$parent);

        $cnt = array();
        $cnt["admin_count"] = 0;
        $cnt["customer_count"] = 0;

        // process subcategories
        while( $row=db_fetch_row($q) )
        {
                $t = update_pCount( $row["categoryID"] );
                $cnt["admin_count"]     += $t["admin_count"];
                $cnt["customer_count"]  += $t["customer_count"];
        }

        // to administrator
        $q = db_query("select count(*) FROM ".PRODUCTS_TABLE.
                        " WHERE categoryID=".(int)$parent);
        $t = db_fetch_row($q);
        $cnt["admin_count"] += $t[0];
        $q1 = db_query("select count(*) from ".CATEGORIY_PRODUCT_TABLE.
                        " where categoryID=".(int)$parent);
        $row = db_fetch_row($q1);
        $cnt["admin_count"] += $row[0];

        // to customer
        $q = db_query("select count(*) FROM ".PRODUCTS_TABLE.
                        " WHERE enabled=1 AND categoryID=".(int)$parent);
        $t = db_fetch_row($q);
        $cnt["customer_count"] += $t[0];
        $q1 = db_query("select productID, categoryID from ".CATEGORIY_PRODUCT_TABLE.
                        " where categoryID=".(int)$parent);
        while( $row = db_fetch_row($q1) )
        {
                $q2 = db_query("select productID from ".PRODUCTS_TABLE.
                                " where enabled=1 AND productID=".(int)$row["productID"]);
                if ( db_fetch_row($q2) )
                        $cnt["customer_count"] ++;
        }

        db_query("UPDATE ".CATEGORIES_TABLE.
                        " SET products_count=".(int)$cnt["customer_count"].", products_count_admin=".
                                (int)$cnt["admin_count"]." WHERE categoryID=".(int)$parent);
        return $cnt;
}

function update_psCount($parent)
{
global $fc, $mc;

          $q = db_query("select categoryID, name, products_count, ".
                        "products_count_admin, parent, picture, subcount FROM ".
                        CATEGORIES_TABLE. " ORDER BY sort_order, name");
          $fc = array(); //parents
          $mc = array(); //parents
          while ($row = db_fetch_row($q)) {
                $fc[(int)$row["categoryID"]] = $row;
                $mc[(int)$row["categoryID"]] = (int)$row["parent"];
          }
          update_pCount($parent);
}
// *****************************************************************************
// Purpose        get subcategories by category id
// Inputs   $categoryID
//                                parent category ID
// Remarks  get current category's subcategories IDs (of all levels!)
// Returns        array of category ID
function catGetSubCategories( $categoryID )
{
        $q = db_query("select categoryID from ".CATEGORIES_TABLE." where parent=".(int)$categoryID);
        $r = array();
        while ($row = db_fetch_row($q))
        {
                $a = catGetSubCategories($row[0]);
                $c_a = count($a);
                for ($i=0;$i<$c_a;$i++) $r[] = $a[$i];
                $r[] = $row[0];
        }
        return $r;
}


// *****************************************************************************
// Purpose        get subcategories by category id
// Inputs           $categoryID
//                                parent category ID
// Remarks          get current category's subcategories IDs (of all levels!)
// Returns        array of category ID
function catGetSubCategoriesSingleLayer( $categoryID )
{
        $q = db_query("select categoryID, name, products_count FROM ".
                        CATEGORIES_TABLE." WHERE parent=".(int)$categoryID." order by sort_order, name");
        $result = array();
        while ($row = db_fetch_row($q)) $result[] = $row;
        return $result;
}



// *****************************************************************************
// Purpose        get category by id
// Inputs   $categoryID
//                                - category ID
// Remarks
// Returns
function catGetCategoryById($categoryID)
{
        $q = db_query("select categoryID, name, parent, products_count, description, picture, ".
                " products_count_admin, sort_order, viewed_times, allow_products_comparison, allow_products_search, ".
                " show_subcategories_products, meta_description, meta_keywords, title ".
                " from ".CATEGORIES_TABLE." where categoryID=".(int)$categoryID);
        $catrow = db_fetch_row($q);
        return $catrow;
}

// *****************************************************************************
// Purpose        gets category META information in HTML form
// Inputs   $categoryID
//                                - category ID
// Remarks
// Returns
function catGetMetaTags($categoryID)
{
        $q = db_query( "select meta_description, meta_keywords from ".
                CATEGORIES_TABLE." where categoryID=".(int)$categoryID );
        $row = db_fetch_row($q);

        $res = "";

        if  ( $row["meta_description"] != "" )
                $res .= "<meta name=\"Description\" content=\"".$row["meta_description"]."\">\n";
        if  ( $row["meta_keywords"] != "" )
                $res .= "<meta name=\"KeyWords\" content=\"".$row["meta_keywords"]."\" >\n";

        return $res;
}



// *****************************************************************************
// Purpose        adds product to appended category
// Inputs
// Remarks      this function uses CATEGORIY_PRODUCT_TABLE table in data base instead of
//                        PRODUCTS_TABLE.categoryID. In CATEGORIY_PRODUCT_TABLE saves appended
//                        categories
// Returns        array of item
//                        "categoryID"
//                        "category_name"
function catGetAppendedCategoriesToProduct( $productID )
{
         $q = db_query( "select ".CATEGORIES_TABLE.".categoryID as categoryID, name as category_name ".
                " from ".CATEGORIY_PRODUCT_TABLE.", ".CATEGORIES_TABLE." ".
                " where ".CATEGORIY_PRODUCT_TABLE.".categoryID = ".CATEGORIES_TABLE.".categoryID ".
                " AND productID = ".(int)$productID  );
        $data = array();
        while( $row = db_fetch_row( $q ) ){
                $wayadd = '';
                $way = catCalculatePathToCategoryA($row["categoryID"]);
                $cway = count($way);
                for ($i=$cway-1; $i>=0; $i--){ if($way[$i]['categoryID']!=1) $wayadd .= $way[$i]['name'].' / '; }
                $row["category_way"]=$wayadd."<b>".$row["category_name"]."</b>";
                $data[] = $row;
                }
        return $data;
}



// *****************************************************************************
// Purpose        adds product to appended category
// Inputs
// Remarks      this function uses CATEGORIY_PRODUCT_TABLE table in data base instead of
//                        PRODUCTS_TABLE.categoryID. In CATEGORIY_PRODUCT_TABLE saves appended
//                        categories
// Returns        true if success, false otherwise
function catAddProductIntoAppendedCategory($productID, $categoryID)
{
        $q = db_query("select count(*) from ".CATEGORIY_PRODUCT_TABLE.
                " where productID=".(int)$productID." AND categoryID=".(int)$categoryID);
        $row = db_fetch_row( $q );

        $qh = db_query( "select categoryID from ".PRODUCTS_TABLE.
                        " where productID=".(int)$productID);
        $rowh = db_fetch_row( $qh );
        $basic_categoryID = $rowh["categoryID"];

        if ( !$row[0] && $basic_categoryID != $categoryID )
        {
                db_query("insert into ".CATEGORIY_PRODUCT_TABLE.
                        "( productID, categoryID ) ".
                        "values( ".(int)$productID.", ".(int)$categoryID." )" );
                return true;
        }
        else
                return false;
}


// *****************************************************************************
// Purpose        removes product to appended category
// Inputs
// Remarks      this function uses CATEGORIY_PRODUCT_TABLE table in data base instead of
//                        PRODUCTS_TABLE.categoryID. In CATEGORIY_PRODUCT_TABLE saves appended
//                        categories
// Returns        nothing
function catRemoveProductFromAppendedCategory($productID, $categoryID)
{
        db_query("delete from ".CATEGORIY_PRODUCT_TABLE.
                " where productID = ".(int)$productID." AND categoryID = ".(int)$categoryID);

}


// *****************************************************************************
// Purpose        calculate a path to the category ( $categoryID )
// Inputs
// Remarks
// Returns        path to category
function catCalculatePathToCategory( $categoryID )
{
        if (!$categoryID) return NULL;

        $path = array();

        $q = db_query("select count(*) from ".CATEGORIES_TABLE.
                        " where categoryID=".(int)$categoryID);
        $row = db_fetch_row($q);
        if ( $row[0] == 0 ) return $path;

        do
        {
                $q = db_query("select categoryID, parent, name FROM ".
                        CATEGORIES_TABLE." WHERE categoryID=".(int)$categoryID);
                $row = db_fetch_row($q);
                $path[] = $row;

                if ( $categoryID == 1 ) break;

                $categoryID = $row["parent"];
        }
        while ( 1 );
        //now reverse $path
        $path = array_reverse($path);
        return $path;
}

// *****************************************************************************
// Purpose        calculate a path to the category ( $categoryID )
// Inputs
// Remarks
// Returns        path to category
function catCalculatePathToCategoryA( $categoryID )
{
        if (!$categoryID) return NULL;

        $path = array();

        $q = db_query("select count(*) from ".CATEGORIES_TABLE.
                        " where categoryID=".(int)$categoryID);
        $row = db_fetch_row($q);
        if ( $row[0] == 0 ) return $path;
        $curr = $categoryID;
        do
        {
                $q = db_query("select categoryID, parent, name FROM ".
                        CATEGORIES_TABLE." WHERE categoryID=".(int)$categoryID);
                $row = db_fetch_row($q);
                if($categoryID != $curr) $path[] = $row;

                if ( $categoryID == 1 ) break;

                $categoryID = $row["parent"];
        }
        while ( 1 );
        //now reverse $path
        $path = array_reverse($path);
        return $path;
}

function _deleteSubCategories( $parent )
{

        $q1 = db_query("select picture FROM ".CATEGORIES_TABLE." WHERE categoryID=".(int)$parent);
        $r = db_fetch_row($q1);
        if ($r["picture"] && file_exists("data/category/".$r["picture"])) unlink("data/category/".$r["picture"]);


        $q = db_query("select categoryID FROM ".CATEGORIES_TABLE." WHERE parent=".(int)$parent);
        while ($row = db_fetch_row($q)){
        $qp = db_query("select productID FROM ".PRODUCTS_TABLE." where categoryID=".(int)$row["categoryID"] );
        while ( $picture = db_fetch_row($qp) )
        {
        DeleteThreePictures2($picture["productID"]);
        }
        db_query("delete FROM ".PRODUCTS_TABLE." WHERE categoryID=".(int)$row["categoryID"]);
        _deleteSubCategories( $row["categoryID"] );
        }
        db_query("delete FROM ".CATEGORIES_TABLE." WHERE parent=".(int)$parent);

}


// *****************************************************************************
// Purpose        deletes category
// Inputs
//                 $categoryID - ID of category to be deleted
// Remarks      delete also all subcategories, all prodoctes remove into root
// Returns        nothing
function catDeleteCategory( $categoryID )
{
        _deleteSubCategories( $categoryID );

        $q=db_query("select productID FROM ".PRODUCTS_TABLE." where categoryID=".(int)$categoryID );
        if ( $picture=db_fetch_row($q) )
        {
        DeleteThreePictures2($picture["productID"]);
        }

        db_query("delete FROM ".PRODUCTS_TABLE." WHERE categoryID=".(int)$categoryID);

        db_query("delete FROM ".CATEGORIES_TABLE." WHERE parent=".(int)$categoryID);
        $q = db_query("select picture FROM ".CATEGORIES_TABLE." WHERE categoryID=".(int)$categoryID);
        $r = db_fetch_row($q);
        if ($r["picture"] && file_exists("data/category/".$r["picture"])) unlink("data/category/".$r["picture"]);

        db_query("delete FROM ".CATEGORIES_TABLE." WHERE categoryID=".(int)$categoryID);
}

?><?php
#####################################
# ShopCMS: Скрипт интернет-магазина
# Copyright (c) by ADGroup
# http://shopcms.ru
#####################################




// *****************************************************************************
// Purpose        gets all options
// Inputs
// Remarks
// Returns
function configGetOptions()
{
        $options = db_query("select optionID, name FROM ".PRODUCT_OPTIONS_TABLE);
        $data = array();
        while( $option_row = db_fetch_row($options) ) $data[] = $option_row;
        return $data;
}



function configGetProductOptionValue( $productID )
{
        $data = array();
        $options = db_query("select optionID, name FROM ".PRODUCT_OPTIONS_TABLE." order by sort_order, name");
        while( $option_row = db_fetch_row($options) )
        {
                $item = array();
                $item["option_row"] = $option_row;
                $item["option_value"] = null;
                $value = db_query("select option_value, option_type, option_show_times FROM ".
                                PRODUCT_OPTIONS_VALUES_TABLE." WHERE optionID=".(int)$option_row["optionID"].
                                " AND productID=".(int)$productID);
                if (   !($value_row=db_fetch_row($value))   )
                {
                        $value_row["option_value"] = null;
                        $value_row["option_type"] = 0;
                        $value_row["option_show_times"] = 1;
                }
                $item["option_value"] = $value_row;

                $q=db_query("select COUNT(*) from ".PRODUCTS_OPTIONS_SET_TABLE.
                                " where optionID=".(int)$option_row["optionID"].
                                " AND productID=".(int)$productID);
                $r=db_fetch_row($q);
                $item["value_count"]=$r[0];
                $data[] = $item;
        }
        return $data;
}

function configSet_N_VALUES_OptionType( $productID, $optionID )
{
        $q = db_query( "select count(*) from ".PRODUCT_OPTIONS_VALUES_TABLE.
                        " where optionID=".(int)$optionID." AND productID=".(int)$productID);
        $count = db_fetch_row($q);
        $count = $count[0];

        if ( $count == 0 )
        {
                db_query( "insert into ".PRODUCT_OPTIONS_VALUES_TABLE.
                        " ( optionID, productID, option_value, option_type, option_show_times ) ".
                        " values( ".(int)$optionID.", ".(int)$productID.", '', 2, 1 ) ");
        }
        else
        {
                db_query( "update ".PRODUCT_OPTIONS_VALUES_TABLE.
                        " set option_type=1 ".
                        " where productID=".(int)$productID." AND optionID=".(int)$optionID);
        }
}




function configUpdateOptionValue( $productID, $updatedValues )
{
        foreach( $updatedValues as $key => $value )
        {
                if ( $updatedValues[$key]["option_radio_type"] == "UN_DEFINED" ||
                                $updatedValues[$key]["option_radio_type"] == "ANY_VALUE" )
                        $option_type=0;
                else
                        $option_type=1;
                if ( $updatedValues[$key]["option_radio_type"] == "UN_DEFINED" )
                        $option_value=null;
                else
                {
                        if ( isset($updatedValues[$key]["option_value"]) )
                                $option_value=$updatedValues[$key]["option_value"];
                        else
                                $option_value=null;
                }

                $where_clause = " where optionID=".(int)$key." AND productID=".(int)$productID;

                $q=db_query("select count(*) from ".PRODUCT_OPTIONS_VALUES_TABLE." ".$where_clause );

                $r = db_fetch_row($q);

                if ( $r[0]==1 ) // if row exists
                {
                        db_query("update ".PRODUCT_OPTIONS_VALUES_TABLE." set option_value='".
                                xEscSQL($option_value)."', option_type=".(int)$option_type." ".
                                $where_clause );
                }
                else // insert query
                {
                        db_query("insert into ".
                                PRODUCT_OPTIONS_VALUES_TABLE.
                                "(optionID, productID, option_value, option_type)".
                                "values ('".(int)$key."', '".(int)$productID."', '".xEscSQL($option_value).
                                        "', '".(int)$option_type."')");
                }
        }
}


// *****************************************************************************
// Purpose        this function updates product option that can be configurated by customer
// Inputs                     $option_show_times - how many times do show in user part
//                        $variantID_default - option id (FK) refers to
//                                PRODUCTS_OPTIONS_VALUES_VARIANTS_TABLE (PK)
//                        $setting - structure
//                                $setting[ <optionID> ]["switchOn"] - if true show this
//                                                value in user part
//                                $setting[ <optionID> ]["price_surplus"] - price surplus when
//                                                this option is selected by user
// Remarks
// Returns                nothing
function UpdateConfiguriableProductOption($optionID, $productID,
                $option_show_times, $variantID_default, $setting )
{
        $where_clause=" where optionID=".(int)$optionID." AND productID=".(int)$productID;
        $q=db_query( "select count(*) from ".PRODUCT_OPTIONS_VALUES_TABLE.$where_clause );
        $r=db_fetch_row($q);
        if ( $r[0]!=0 )
        {
                 db_query("update ".PRODUCT_OPTIONS_VALUES_TABLE.
                         " set option_value='', ".
                         " option_show_times='".(int)$option_show_times."', ".
                         " variantID=".(int)$variantID_default." ".
                         $where_clause );
        }
        else
        {
                 db_query("insert into ".PRODUCT_OPTIONS_VALUES_TABLE.
                         "(optionID, productID, option_type, option_value, ".
                         "option_show_times, variantID) ".
                         "values('".(int)$optionID."', '".(int)$productID."', 0, '', '".
                         (int)$option_show_times."',  ".
                         (int)$variantID_default."  )");
        }

        $q1=db_query("select variantID from ".PRODUCTS_OPTIONS_VALUES_VARIANTS_TABLE.
                         " where optionID=".(int)$optionID);
        $if_only = false;
        while( $r1=db_fetch_row($q1) )
        {
                $key = $r1["variantID"];
                $where_clause=" where productID=".(int)$productID." AND optionID=".(int)$optionID.
                                 " AND variantID=".(int)$key;
                if ( !isset($setting[$key]["switchOn"]) )
                {
                        db_query( "delete from ".PRODUCTS_OPTIONS_SET_TABLE.$where_clause );
                }
                else
                {
                        $q=db_query("select count(*) from ".PRODUCTS_OPTIONS_SET_TABLE.
                                        $where_clause);
                        $r=db_fetch_row($q);
                        if ( $r[0]!=0 )
                        {
                                db_query("update ".PRODUCTS_OPTIONS_SET_TABLE." set price_surplus='".
                                        (float)$setting[$key]["price_surplus"]."'".$where_clause );
                                $if_only = true;
                        }
                        else
                        {
                                db_query("insert into ".PRODUCTS_OPTIONS_SET_TABLE.
                                         "(productID, optionID, variantID, price_surplus)".
                                         "values( '".(int)$productID."', '".
                                                (int)$optionID."', '".(int)$key."', '".
                                                (float)$setting[$key]["price_surplus"]."' )"
                                 );
                                $if_only = true;
                        }
                }
        }
        if ( !$if_only )
        {
                db_query("update ".PRODUCT_OPTIONS_VALUES_TABLE.
                         " set option_show_times=0 where optionID=".(int)$optionID." AND ".
                                " productID=".(int)$productID);
        }
}

?><?php
#####################################
# ShopCMS: Скрипт интернет-магазина
# Copyright (c) by ADGroup
# http://shopcms.ru
#####################################


function cnGetCountryById( $countryID )
{
        if ( is_null($countryID) || $countryID == "" ) $countryID = "NULL";
        else $countryID = (int)$countryID;
        $q = db_query("select countryID, country_name, country_iso_2, country_iso_3 from ".
                COUNTRIES_TABLE." where countryID=".$countryID);
        $row=db_fetch_row($q);
        return $row;
}



// *****************************************************************************
// Purpose        gets all manufacturers
// Inputs                     nothing
// Remarks
// Returns                array of maunfactirer, each item of this array
//                                have next struture
//                                        "countryID"        - id
//                                        "country_name"        - name
//                                        "country_iso_2"        - ISO abbreviation ( 2 chars )
//                                        "country_iso_3"        - ISO abbreviation ( 3 chars )
function cnGetCountries( $callBackParam, &$count_row, $navigatorParams = null )
{
        if ( $navigatorParams != null )
        {
                $offset                        = $navigatorParams["offset"];
                $CountRowOnPage        = $navigatorParams["CountRowOnPage"];
        }
        else
        {
                $offset = 0;
                $CountRowOnPage = 0;
        }

        $q=db_query("select countryID, country_name, ".
                " country_iso_2, country_iso_3 from ".COUNTRIES_TABLE." ".
                " order by country_name" );
        $data=array();
        $i=0;
        while( $r=db_fetch_row($q) )
        {
                if ( ($i >= $offset && $i < $offset + $CountRowOnPage) ||
                                $navigatorParams == null  )
                {
                        $data[] = $r;
                }
                $i++;
        }
        $count_row = $i;
        return $data;
}



// *****************************************************************************
// Purpose        deletes country
// Inputs                     id
// Remarks
// Returns                nothing
function cnDeleteCountry($countryID)
{

        $tax_classes = taxGetTaxClasses();
        foreach( $tax_classes as $class ) taxDeleteRate( $class["classID"], $countryID );

        db_query("update ".CUSTOMER_ADDRESSES_TABLE.
                " set countryID=NULL where countryID=".(int)$countryID);
        $q = db_query("select zoneID from ".ZONES_TABLE." where countryID=".(int)$countryID);
        while( $r = db_fetch_row( $q ) )
        {
                db_query( "update ".CUSTOMER_ADDRESSES_TABLE.
                        " set zoneID=NULL where zoneID=".(int)$r["zoneID"]);
        }
        db_query("delete from ".ZONES_TABLE." where countryID=".(int)$countryID);
        db_query("delete from ".COUNTRIES_TABLE." where countryID=".(int)$countryID);
}


// *****************************************************************************
// Purpose        updates manufacturers
// Inputs                     $countryID        - id
//                        $country_name        - name
//                        $country_iso_2        - ISO abbreviation ( 2 chars )
//                        $country_iso_3        - ISO abbreviation ( 3 chars )
// Remarks
// Returns                nothing
function cnUpdateCountry( $countryID, $country_name, $country_iso_2, $country_iso_3 )
{
        db_query("update ".COUNTRIES_TABLE." set ".
                "  country_name='".xToText(trim($country_name))."', ".
                "  country_iso_2='".xToText(trim($country_iso_2))."', ".
                "  country_iso_3='".xToText(trim($country_iso_3))."' ".
                "  where countryID=".(int)$countryID);
}


// *****************************************************************************
// Purpose        adds manufacturers
// Inputs
//                        $country_name        - name
//                        $country_iso_2        - ISO abbreviation ( 2 chars )
//                        $country_iso_3        - ISO abbreviation ( 3 chars )
// Remarks
// Returns                nothing
function cnAddCountry($country_name, $country_iso_2, $country_iso_3  )
{
        db_query("insert into ".COUNTRIES_TABLE."( country_name, country_iso_2, country_iso_3 )".
                "values( '".xToText(trim($country_name))."', '".xToText(trim($country_iso_2))."', '".
                xToText(trim($country_iso_3))."' )" );
        return db_insert_id();
}

?><?php
#####################################
# ShopCMS: Скрипт интернет-магазина
# Copyright (c) by ADGroup
# http://shopcms.ru
#####################################




class DWord
{
        var $bitArray;

        function DWord()
        {
                $this->bitArray = array();
                for( $i=1; $i<=32;  $i++)
                        $this->bitArray[$i-1] = 0;
        }

        function _setByte( $byte, $displacement )
        {
                // 00000001 = 1
                $this->bitArray[$displacement + 0] = (($byte&1)   != 0)?1:0;
                // 00000010 = 2
                $this->bitArray[$displacement + 1] = (($byte&2)   != 0)?1:0;
                // 00000100 = 4
                $this->bitArray[$displacement + 2] = (($byte&4)   != 0)?1:0;
                // 00001000 = 8
                $this->bitArray[$displacement + 3] = (($byte&8)   != 0)?1:0;
                // 00010000 = 16
                $this->bitArray[$displacement + 4] = (($byte&16)  != 0)?1:0;
                // 00100000 = 32
                $this->bitArray[$displacement + 5] = (($byte&32)  != 0)?1:0;
                // 01000000 = 64
                $this->bitArray[$displacement + 6] = (($byte&64)  != 0)?1:0;
                // 10000000 = 128
                $this->bitArray[$displacement + 7] = (($byte&128) != 0)?1:0;
        }

        function _getByte( $displacement )
        {
                return $this->bitArray[$displacement + 0]*1  +
                                        $this->bitArray[$displacement + 1]*2 +
                                        $this->bitArray[$displacement + 2]*4 +
                                        $this->bitArray[$displacement + 3]*8 +
                                        $this->bitArray[$displacement + 4]*16 +
                                        $this->bitArray[$displacement + 5]*32 +
                                        $this->bitArray[$displacement + 6]*64 +
                                        $this->bitArray[$displacement + 7]*128;
        }

        function SetValue( $byte1, $byte2, $byte3, $byte4  )
        {
                $this->_setByte( $byte1, 0  );
                $this->_setByte( $byte2, 8  );
                $this->_setByte( $byte3, 16 );
                $this->_setByte( $byte4, 24 );
        }

        function GetValue( &$byte1, &$byte2, &$byte3, &$byte4 )
        {
                $byte1 = $this->_getByte( 0  );
                $byte2 = $this->_getByte( 8  );
                $byte3 = $this->_getByte( 16 );
                $byte4 = $this->_getByte( 24 );
        }

        function GetCount()
        {
                $coeff = 1;
                $res = 0;
                for($i=1; $i<=32; $i++)
                {
                        $res += $this->bitArray[$i-1]*$coeff;
                        $coeff *= 2;
                }
                return $res;
        }

        function SetBit( $bitValue, $bitIndex  )
        {
                $this->bitArray[ $bitIndex ] = $bitValue;
        }

        function GetHTML_Representation()
        {
                $res = "";
                $res .= "<table>";

                // head row
                $res .= "        <tr>";
                for( $i=31; $i>=0; $i-- )
                {
                        $res .= "                <td>";
                        $res .= "                        $i";
                        $res .= "                </td>";
                }
                $res .= "        </tr>";

                // bit values
                $res .= "        <tr>";
                for( $i=31; $i>=0; $i-- )
                {
                        $res .= "                <td>";
                        $res .= "                        ".$this->bitArray[$i];
                        $res .= "                </td>";
                }
                $res .= "        </tr>";
                $res .= "</table>";

                return $res;
        }

        function ShiftToLeft( $countBit )
        {
                $resBitArray = $this->bitArray;

                for( $i=31; $i>=0; $i-- )
                        if ( $i +  $countBit <= 31 )
                                $resBitArray[$i + $countBit] = $resBitArray[$i];

                for( $i=1; $i<=$countBit; $i++ )
                        $resBitArray[$i-1]=0;

                $res = new DWord();
                $res->bitArray = $resBitArray;
                return $res;
        }

        function ShiftToRight( $countBit )
        {
                $resBitArray = $this->bitArray;

                for( $i=0; $i<=31; $i++ )
                        if ( $i -  $countBit >= 0 )
                                $resBitArray[$i - $countBit] = $resBitArray[$i];

                for( $i=31; $i>=31-$countBit+1; $i-- )
                        $resBitArray[$i]=0;

                $res = new DWord();
                $res->bitArray = $resBitArray;
                return $res;
        }

        function BitwiseOR( $dwordObject )
        {
                $res = new DWord();
                for( $i=0; $i<=31; $i++ )
                {
                        if ( $this->bitArray[$i]+$dwordObject->bitArray[$i] != 0 )
                                $res->SetBit( 1, $i );
                        else
                                $res->SetBit( 0, $i );
                }
                return $res;
        }

        function BitwiseAND( $dwordObject )
        {
                $res = new DWord();
                for( $i=0; $i<=31; $i++ )
                        $res->SetBit( $this->bitArray[$i]*$dwordObject->bitArray[$i],
                                                $i );
                return $res;
        }

        function BitwiseXOR( $dwordObject )
        {
                $res = new DWord();
                for( $i=0; $i<=31; $i++ )
                {
                        if ($this->bitArray[$i] == $dwordObject->bitArray[$i])
                                $res->SetBit( 1, $i );
                        else
                                $res->SetBit( 0, $i );
                }
                return $res;
        }

        function Plus( $dwordObject )
        {
                $res = new DWord();
                $cf = 0;
                for( $i=0; $i<=3; $i++ )
                {
                        $byte1 = $this->_getByte( $i*8 );
                        $byte2 = $dwordObject->_getByte( $i*8 );

                        $res->_setByte( $byte1 + $byte2 + $cf, $i*8 );
                        if ( $byte1 + $byte2 + $cf >= 256 )
                                $cf = 1;
                }
                return $res;
        }

}



// *****************************************************************************
// Purpose        encrypts cc_number field ( see ORDERS_TABLE in database_structure.xml )
// Inputs
// Remarks
// Returns
function cryptCCNumberCrypt( $cc_number, $key )
{
        return base64_encode($cc_number);
/*
        $res = "";
        $strlen = strlen( $cc_number );
        for( $i=1; $i<=32-$strlen; $i++ )
                $cc_number .= " ";
        $res .= chr( $strlen );

        $dWordArray = array();
        for( $i=1; $i<=8; $i++ )
        {
                $dWordObject = DWord();
                $dWordObject->SetValue(
                                $cc_number[ ($i-1)*4 + 0 ],
                                $cc_number[ ($i-1)*4 + 1 ],
                                $cc_number[ ($i-1)*4 + 2 ],
                                $cc_number[ ($i-1)*4 + 3 ] );
                $dWordArray[] = $dWordObject;
        }

        $dWordArrayCifered = array();
        for( $i=1; $i<=4; $i++ )
        {
                $ciferedData = _gostCrypt( array( $dWordArray[($i-1)*2], $dWordArray[($i-1)*2 + 1]), $key );
                $dWordArrayCifered[] = $ciferedData[0];
                $dWordArrayCifered[] = $ciferedData[1];
        }

        foreach( $dWordArrayCifered as $dWordCifered )
        {
                $byte1 = 0;
                $byte2 = 0;
                $byte3 = 0;
                $byte4 = 0;
                $dWordCifered->GetValue( &$byte1, &$byte2, &$byte3, &$byte4 );
                $res .= chr($byte1);
                $res .= chr($byte2);
                $res .= chr($byte3);
                $res .= chr($byte4);
        }

        return $res;
*/
}


// *****************************************************************************
// Purpose        decrypts cc_number field ( see ORDERS_TABLE in database_structure.xml )
// Inputs
// Remarks
// Returns
function cryptCCNumberDeCrypt( $cifer, $key )
{
        return base64_decode($cifer);
/*
        $res = "";
        $strlen = (int)($cifer[0]);

        $dWordArray = array();
        for( $i=1; $i<=8; $i++ )
        {
                $dWordObject = DWord();
                $dWordObject->SetValue(
                                $cifer[ ($i-1)*4 + 1 ],
                                $cifer[ ($i-1)*4 + 2 ],
                                $cifer[ ($i-1)*4 + 3 ],
                                $cifer[ ($i-1)*4 + 4 ] );
                $dWordArray[] = $dWordObject;
        }

        $dWordArrayDeCifered = array();
        for( $i=1; $i<=4; $i++ )
        {
                $deCiferedData = _gostDeCrypt( array( $dWordArray[($i-1)*2], $dWordArray[($i-1)*2 + 1]), $key );
                $dWordArrayCifered[] = $deCiferedData[0];
                $dWordArrayCifered[] = $deCiferedData[1];
        }

        foreach( $dWordArrayCifered as $dWordCifered )
        {
                $byte1 = 0;
                $byte2 = 0;
                $byte3 = 0;
                $byte4 = 0;
                $dWordCifered->GetValue( &$byte1, &$byte2, &$byte3, &$byte4 );
                $res .= chr($byte1);
                $res .= chr($byte2);
                $res .= chr($byte3);
                $res .= chr($byte4);
        }

        $temp = $res;
        for( $i=1; $i<=$strlen; $i++ )
                $res .= $temp[$i-1];

        return $res;
*/
}


// *****************************************************************************
// Purpose        encrypts cc_holdername field ( see ORDERS_TABLE in database_structure.xml )
// Inputs
// Remarks
// Returns
function cryptCCHoldernameCrypt( $cc_holdername, $key )
{
        return base64_encode( $cc_holdername );
}


// *****************************************************************************
// Purpose        decrypts cc_holdername field ( see ORDERS_TABLE in database_structure.xml )
// Inputs
// Remarks
// Returns
function cryptCCHoldernameDeCrypt( $cifer, $key )
{
        return base64_decode( $cifer );
}


// *****************************************************************************
// Purpose        encrypts cc_expires field ( see ORDERS_TABLE in database_structure.xml )
// Inputs
// Remarks
// Returns
function cryptCCExpiresCrypt( $cc_expires, $key )
{
        return base64_encode( $cc_expires );
}


// *****************************************************************************
// Purpose        decrypts cc_expires field ( see ORDERS_TABLE in database_structure.xml )
// Inputs
// Remarks
// Returns
function cryptCCExpiresDeCrypt( $cifer, $key )
{
        return base64_decode( $cifer );
}


// *****************************************************************************
// Purpose        encrypts customer ( and admin ) password field
//                                        ( see ORDERS_TABLE in database_structure.xml )
// Inputs
// Remarks
// Returns
function cryptPasswordCrypt( $password, $key )
{
        return base64_encode( $password );
}


// *****************************************************************************
// Purpose        decrypts customer ( and admin ) password field ( see ORDERS_TABLE in database_structure.xml )
// Inputs
// Remarks
// Returns
function cryptPasswordDeCrypt( $cifer, $key )
{
        return base64_decode( $cifer );
}


// *****************************************************************************
// Purpose        encrypts getFileParam
// Inputs
// Remarks        see also get_file.php
// Returns
function cryptFileParamCrypt( $getFileParam, $key )
{
        return base64_encode( $getFileParam );
}


// *****************************************************************************
// Purpose        decrypt getFileParam
// Inputs
// Remarks        see also get_file.php
// Returns
function cryptFileParamDeCrypt( $cifer, $key )
{
        return base64_decode( $cifer );
}


//--------------------------------------
// initialize


// it is single byte values
$bK8 = array( 14,  4, 13,  1,  2, 15, 11,  8,  3, 10,  6, 12,  5,  9,  0,  7 );
$bK7 = array( 15,  1,  8, 14,  6, 11,  3,  4,  9,  7,  2, 13, 12,  0,  5, 10 );
$bK6 = array( 10,  0,  9, 14,  6,  3, 15,  5,  1, 13, 12,  7, 11,  4,  2,  8 );
$bK5 = array(  7, 13, 14,  3,  0,  6,  9, 10,  1,  2,  8,  5, 11, 12,  4, 15 );
$bK4 = array(  2, 12,  4,  1,  7, 10, 11,  6,  8,  5,  3, 15, 13,  0, 14,  9 );
$bK3 = array( 12,  1, 10, 15,  9,  2,  6,  8,  0, 13,  3,  4, 14,  7,  5, 11 );
$bK2 = array(  4, 11,  2, 14, 15,  0,  8, 13,  3, 12,  9,  7,  5, 10,  6,  1 );
$bK1 = array( 13,  2,  8,  4,  6, 15, 11,  1, 10,  9,  3, 14,  5,  0, 12,  7 );

// it is single byte values
$bK87 = array();
$bK65 = array();
$bK43 = array();
$bK21 = array();

for ($i=0; $i<256; $i++)
{
        $bK87[$i] = $bK8[$i >> 4] << 4 | $bK7[$i & 15];
        $bK65[$i] = $bK6[$i >> 4] << 4 | $bK5[$i & 15];
        $bK43[$i] = $bK4[$i >> 4] << 4 | $bK3[$i & 15];
        $bK21[$i] = $bK2[$i >> 4] << 4 | $bK1[$i & 15];
}


function _f( $x )
{
        global $bK87;
        global $bK65;
        global $bK43;
        global $bK21;


        // $bK87[$x>>24 & 255] << 24
        $x1 = $x->ShiftToRight(24);
        $x1 = $x1->BitwiseAND(255);
        $temp = $bK87[ (int)$x1->GetCount() ];
        $x1 = new DWord();
        $x1->SetValue( $temp, 0, 0, 0 );
        $x1->ShiftToLeft( 24 );
        debug( $x1->GetCount() );

        // $bK65[$x>>16 & 255] << 16
        $x2 = $x->ShiftToLeft(16);
        $x2 = $x2->BitwiseAND(255);
        $temp = $bK65[ $x2->GetCount() ];
        $x2 = new DWord();
        $x2->SetValue( $temp, 0, 0, 0 );
        $x2->ShiftToLeft(16);

        // $bK43[$x>> 8 & 255] <<  8
        $x3 = $x->ShiftToRight(8);
        $x3 = $x3->BitwiseAND(255);
        $temp = $bK43[ $x3->GetCount() ];
        $x3 = new DWord();
        $x3->SetValue( $temp, 0, 0, 0 );
        $x3->ShiftToLeft(8);

        // $bK21[$x & 255]
        $x4 = $x->BitwiseAND(255);
        $temp = $bK21[ $x4->GetCount() ];
        $x4 = new DWord();
        $x4->SetValue( $temp, 0, 0, 0 );


        //$x =        $bK87[$x>>24 & 255] << 24 | $bK65[$x>>16 & 255] << 16 |
        //                $bK43[$x>> 8 & 255] <<  8 | $bK21[$x & 255];
        $res = $x1->BitwiseOR( $x2 );
        $res = $res->BitwiseOR( $x3 );
        $res = $res->BitwiseOR( $x4 );

        return $res;
}


// *****************************************************************************
// Purpose        GOST cryptography function
// Inputs           $in                - 2 item of 32 values  ( source data )
//                                $key        - 8 item of 32 values ( key to encrypted )
// Remarks
// Returns        cyfered data
function _gostCrypt( $in, $key )
{
        $n1 = $in[0];
        $n2 = $in[1];

        /* Instead of swapping halves, swap names each round */
        $n2 = $n2->BitwiseXOR( _f($n1->Plus($key[0])) );
        debug( $n1->GetCount() );
        debug( $key[0]->GetCount() );
        $n2 = _f($n1->Plus($key[0]));
        debug( $n2." = ".$n2->GetCount() );

        debug("=========================== Cifer ============================");
        debug( $n2->GetHTML_Representation() );
        $byte1 = null;
        $byte2 = null;
        $byte3 = null;
        $byte4 = null;
        $n2->GetValue( $byte1, $byte2, $byte3, $byte4 );
        debug( $byte1 );
        debug( $byte2 );
        debug( $byte3 );
        debug( $byte4 );
        debug("==============================================================");



        $n1 = $n1->BitwiseXOR( _f($n2->Plus($key[1])) );
        $n2 = $n2->BitwiseXOR( _f($n1->Plus($key[2])) );
        $n1 = $n1->BitwiseXOR( _f($n2->Plus($key[3])) );
        $n2 = $n2->BitwiseXOR( _f($n1->Plus($key[4])) );
        $n1 = $n1->BitwiseXOR( _f($n2->Plus($key[5])) );
        $n2 = $n2->BitwiseXOR( _f($n1->Plus($key[6])) );
        $n1 = $n1->BitwiseXOR( _f($n2->Plus($key[7])) );

        $n2 = $n2->BitwiseXOR( _f($n1->Plus($key[0])) );
        $n1 = $n1->BitwiseXOR( _f($n2->Plus($key[1])) );
        $n2 = $n2->BitwiseXOR( _f($n1->Plus($key[2])) );
        $n1 = $n1->BitwiseXOR( _f($n2->Plus($key[3])) );
        $n2 = $n2->BitwiseXOR( _f($n1->Plus($key[4])) );
        $n1 = $n1->BitwiseXOR( _f($n2->Plus($key[5])) );
        $n2 = $n2->BitwiseXOR( _f($n1->Plus($key[6])) );
        $n1 = $n1->BitwiseXOR( _f($n2->Plus($key[7])) );

        $n2 = $n2->BitwiseXOR( _f($n1->Plus($key[0])) );
        $n1 = $n1->BitwiseXOR( _f($n2->Plus($key[1])) );
        $n2 = $n2->BitwiseXOR( _f($n1->Plus($key[2])) );
        $n1 = $n1->BitwiseXOR( _f($n2->Plus($key[3])) );
        $n2 = $n2->BitwiseXOR( _f($n1->Plus($key[4])) );
        $n1 = $n1->BitwiseXOR( _f($n2->Plus($key[5])) );
        $n2 = $n2->BitwiseXOR( _f($n1->Plus($key[6])) );
        $n1 = $n1->BitwiseXOR( _f($n2->Plus($key[7])) );

        $n2 = $n2->BitwiseXOR( _f($n1->Plus($key[7])) );
        $n1 = $n1->BitwiseXOR( _f($n2->Plus($key[6])) );
        $n2 = $n2->BitwiseXOR( _f($n1->Plus($key[5])) );
        $n1 = $n1->BitwiseXOR( _f($n2->Plus($key[4])) );
        $n2 = $n2->BitwiseXOR( _f($n1->Plus($key[3])) );
        $n1 = $n1->BitwiseXOR( _f($n2->Plus($key[2])) );
        $n2 = $n2->BitwiseXOR( _f($n1->Plus($key[1])) );
        $n1 = $n1->BitwiseXOR( _f($n2->Plus($key[0])) );

        $out = array();
        $out[0] = $n2;
        $out[1] = $n1;

        return $out;
}


function _gostDeCrypt( $out, $key )
{
        $n1 = $in[0];
        $n2 = $in[1];

        /* Instead of swapping halves, swap names each round */
        $n2 = $n2->BitwiseXOR( _f($n1->Plus($key[0])) );
        $n1 = $n1->BitwiseXOR( _f($n2->Plus($key[1])) );
        $n2 = $n2->BitwiseXOR( _f($n1->Plus($key[2])) );
        $n1 = $n1->BitwiseXOR( _f($n2->Plus($key[3])) );
        $n2 = $n2->BitwiseXOR( _f($n1->Plus($key[4])) );
        $n1 = $n1->BitwiseXOR( _f($n2->Plus($key[5])) );
        $n2 = $n2->BitwiseXOR( _f($n1->Plus($key[6])) );
        $n1 = $n1->BitwiseXOR( _f($n2->Plus($key[7])) );

        $n2 = $n2->BitwiseXOR( _f($n1->Plus($key[7])) );
        $n1 = $n1->BitwiseXOR( _f($n2->Plus($key[6])) );
        $n2 = $n2->BitwiseXOR( _f($n1->Plus($key[5])) );
        $n1 = $n1->BitwiseXOR( _f($n2->Plus($key[4])) );
        $n2 = $n2->BitwiseXOR( _f($n1->Plus($key[3])) );
        $n1 = $n1->BitwiseXOR( _f($n2->Plus($key[2])) );
        $n2 = $n2->BitwiseXOR( _f($n1->Plus($key[1])) );
        $n1 = $n1->BitwiseXOR( _f($n2->Plus($key[0])) );

        $n2 = $n2->BitwiseXOR( _f($n1->Plus($key[7])) );
        $n1 = $n1->BitwiseXOR( _f($n2->Plus($key[6])) );
        $n2 = $n2->BitwiseXOR( _f($n1->Plus($key[5])) );
        $n1 = $n1->BitwiseXOR( _f($n2->Plus($key[4])) );
        $n2 = $n2->BitwiseXOR( _f($n1->Plus($key[3])) );
        $n1 = $n1->BitwiseXOR( _f($n2->Plus($key[2])) );
        $n2 = $n2->BitwiseXOR( _f($n1->Plus($key[1])) );
        $n1 = $n1->BitwiseXOR( _f($n2->Plus($key[0])) );

        $n2 = $n2->BitwiseXOR( _f($n1->Plus($key[7])) );
        $n1 = $n1->BitwiseXOR( _f($n2->Plus($key[6])) );
        $n2 = $n2->BitwiseXOR( _f($n1->Plus($key[5])) );
        $n1 = $n1->BitwiseXOR( _f($n2->Plus($key[4])) );
        $n2 = $n2->BitwiseXOR( _f($n1->Plus($key[3])) );
        $n1 = $n1->BitwiseXOR( _f($n2->Plus($key[2])) );
        $n2 = $n2->BitwiseXOR( _f($n1->Plus($key[1])) );
        $n1 = $n1->BitwiseXOR( _f($n2->Plus($key[0])) );

        $out = array();
        $out[0] = $n2;
        $out[1] = $n1;

        return $out;
}

?><?php
#####################################
# ShopCMS: Скрипт интернет-магазина
# Copyright (c) by ADGroup
# http://shopcms.ru
#####################################

        // *****************************************************************************
        // Purpose        sets current currency
        // Inputs             nothing
        // Remarks
        // Returns        nothing
        function currSetCurrentCurrency( $currencyID )
        {
                //register current currency type in session vars
                $_SESSION["current_currency"] = (int)$currencyID;

                if (isset($_SESSION["log"]))
                {
                        db_query("UPDATE ".CUSTOMERS_TABLE." SET CID=".(int)$currencyID.
                                " WHERE Login='".xEscSQL($_SESSION["log"])."'");
                }
        }



        // *****************************************************************************
        // Purpose        gets current selected by user currency unit
        // Inputs             nothing
        // Remarks
        // Returns        currency unit ID ( see CURRENCY_TYPES_TABLE table in DataBase )
        function currGetCurrentCurrencyUnitID()
        {

                if ( isset($_SESSION["log"]) )
                {
                        $q = db_query("select b.CID, s.cust_password, s.CID FROM ".CUSTOMERS_TABLE.
                                " AS s INNER JOIN ".CURRENCY_TYPES_TABLE." AS b on (s.CID=b.CID) WHERE s.Login='".xEscSQL($_SESSION["log"])."'");
                        $customerInfo = db_fetch_row($q);
                        $_SESSION["current_currency"] = $customerInfo["CID"];
                        if ( $_SESSION["current_currency"] != null && $_SESSION["current_currency"]>0)  return $_SESSION["current_currency"];
                }

                if  ( isset($_SESSION["current_currency"])){

                        $q = db_query("select currency_value FROM ".CURRENCY_TYPES_TABLE." WHERE CID=".(int)$_SESSION["current_currency"]);
                        $customerInfo = db_fetch_row($q);
                        $_SESSION["current_currency"] = $customerInfo["CID"];
                        if ( $_SESSION["current_currency"] != null && $_SESSION["current_currency"]>0)  return $_SESSION["current_currency"];
                }
                        $q = db_query( "select count(*) from ".CURRENCY_TYPES_TABLE." where CID=".(int)CONF_DEFAULT_CURRENCY);
                        $count = db_fetch_row($q);
                        if ( $count[0] )
                                return CONF_DEFAULT_CURRENCY;
                        else
                                return null;

        }


        // *****************************************************************************
        // Purpose        gets current selected by user currency unit
        // Inputs             nothing
        // Remarks
        // Returns        currency unit ID ( see CURRENCY_TYPES_TABLE table in DataBase )
        function currGetCurrencyByID( $currencyID )
        {
                $q = db_query( "select CID, Name, code, currency_value, where2show, sort_order, currency_iso_3, roundval from ".
                        CURRENCY_TYPES_TABLE." where CID=".(int)$currencyID);
                $row = db_fetch_row($q);
                if (!$row) $row = NULL;
                return $row;
        }



        // *****************************************************************************
        // Purpose        get all currencies
        // Inputs             nothing
        // Remarks
        // Returns        currency array
        function currGetAllCurrencies()
        {
                $q = db_query("select Name, code, currency_iso_3, currency_value, where2show, CID, sort_order, roundval from ".
                                CURRENCY_TYPES_TABLE." order by sort_order");
                $data = array();
                while( $row = db_fetch_row($q) ) $data[] = $row;
                return $data;
        }


        // *****************************************************************************
        // Purpose        delete currency by ID
        // Inputs             CID
        // Remarks
        // Returns        nothing
        function currDeleteCurrency( $CID )
        {
                $q = db_query( "select CID from ".CURRENCY_TYPES_TABLE." where CID!=".(int)$CID );
                if ( $currency=db_fetch_row($q) )
                        db_query("update ".CUSTOMERS_TABLE." set CID=".$currency["CID"]." where CID=".(int)$CID );
                else
                        db_query("update ".CUSTOMERS_TABLE." set CID=NULL where CID=".(int)$CID );
                db_query( "delete from ".CURRENCY_TYPES_TABLE." where CID=".(int)$CID);
        }


        // *****************************************************************************
        // Purpose        update currency by ID
        // Inputs             CID
        // Remarks
        // Returns        nothing
        function currUpdateCurrency( $CID, $name, $code, $currency_iso_3, $value, $where, $sort_order, $roundval )
        {
                db_query( "update ".
                                CURRENCY_TYPES_TABLE.
                                " set ".
                                "        Name='".xToText(trim($name))."', ".
                                "        code='".xEscSQL($code)."', ".
                                "        currency_value='".xEscSQL(trim($value))."', ".
                                "        where2show=".(int)$where.", ".
                                "        sort_order=".(int)$sort_order.", ".
                                "        currency_iso_3='".xToText(trim($currency_iso_3))."', ".
                                "        roundval=".(int)$roundval." ".
                                " where CID=".(int)$CID);
        }


        // *****************************************************************************
        // Purpose        add currency by ID
        // Inputs             CID
        // Remarks
        // Returns        nothing
        function currAddCurrency( $name, $code, $currency_iso_3, $value, $where, $sort_order, $roundval )
        {
                db_query( "insert into ".CURRENCY_TYPES_TABLE.
                        " (Name, code, currency_value, where2show, sort_order, currency_iso_3, roundval) ".
                        " values ('".xToText(trim($name))."', '".xEscSQL($code)."', '".xEscSQL(trim($value))."', '".(int)$where."', '".
                        (int)$sort_order."', '".xToText(trim($currency_iso_3))."', '".(int)$roundval."')" );
        }

        function currGetCurrencyByISO3( $_ISO3 )
        {
                $q = db_query( "select CID, Name, code, currency_value, where2show, sort_order, currency_iso_3 from ".
                        CURRENCY_TYPES_TABLE." where currency_iso_3='".xEscSQL($_ISO3)."' " );
                $row = db_fetch_row($q);
                if (!$row) $row = NULL;
                return $row;
        }

?><?php
#####################################
# ShopCMS: Скрипт интернет-магазина
# Copyright (c) by ADGroup
# http://shopcms.ru
#####################################



        function GetCustomerGroupByCustomerId( $customerID )
        {
                $q = db_query( "select custgroupID from ".CUSTOMERS_TABLE.
                                " where customerID=".(int)$customerID);
                $customer = db_fetch_row($q);

                if ( is_null($customer["custgroupID"]) || trim($customer["custgroupID"])=="" )
                        return false;

                $q = db_query("select custgroupID, custgroup_name, custgroup_discount, sort_order from ".
                                CUSTGROUPS_TABLE." where custgroupID=".$customer["custgroupID"] );
                $row = db_fetch_row($q);
                return $row;
        }


        function GetAllCustGroups()
        {
                $q=db_query("select custgroupID, custgroup_name, custgroup_discount, sort_order from ".
                                CUSTGROUPS_TABLE." order by sort_order, custgroup_name ");
                $data=array();
                while( $r=db_fetch_row($q) ) $data[]=$r;
                return $data;
        }

        function DeleteCustGroup($custgroupID)
        {
                db_query("update ".CUSTOMERS_TABLE." set custgroupID=NULL where custgroupID=".(int)$custgroupID);
                db_query("delete from ".CUSTGROUPS_TABLE." where custgroupID=".(int)$custgroupID);
        }

        function UpdateCustGroup($custgroupID, $custgroup_name, $custgroup_discount, $sort_order )
        {
                db_query(
                                "update ".CUSTGROUPS_TABLE." set  ".
                                "custgroup_name='".xToText($custgroup_name)."', ".
                                "custgroup_discount='".(float)$custgroup_discount."', ".
                                "sort_order=".(int)$sort_order." ".
                                "where custgroupID=".(int)$custgroupID
                        );
        }


        function AddCustGroup( $custgroup_name, $custgroup_discount, $sort_order)
        {
                db_query("insert into ".CUSTGROUPS_TABLE.
                        "( custgroup_name, custgroup_discount, sort_order ) ".
                        "values( '".xToText($custgroup_name)."', '".(float)$custgroup_discount."', '".(int)$sort_order."' )");
        }

?><?php
#####################################
# ShopCMS: Скрипт интернет-магазина
# Copyright (c) by ADGroup
# http://shopcms.ru
#####################################



// *****************************************************************************
// Purpose        gets current date time in database format
// Inputs   nothing
// Remarks
// Returns        date base specific date time
function get_current_time()         // gets current date and time as a string in MySQL format
{
        return strftime("%Y-%m-%d %H:%M:%S", time()+intval(CONF_TIMEZONE)*3600);
}


//converts datetime provided as a string into a standard form (date format is defined in store settings)
function dtConvertToStandartForm( $datetime, $showtime = 0 )
{
        // 2004-12-30 13:25:41
        $array = explode( " ", $datetime );
        $date = $array[0];
        $time = $array[1];

        $dateArray = explode( "-", $date );
        $day        = $dateArray[2];
        $month        = $dateArray[1];
        $year        = $dateArray[0];

        if (!strcmp(_getSettingOptionValue("CONF_DATE_FORMAT"), "MM/DD/YYYY"))
                $date = $month."/".$day."/".$year;
        else
                $date = $day.".".$month.".".$year;

        if ($showtime == 1)
                return $date." ".$time;
        else
                return $date;
}

//converts datetime provided as a string into an array
function dtGetParsedDateTime( $datetime )
{
        // 2004-12-30 13:25:41 - MySQL database datetime format

        $array = explode( " ", $datetime ); //divide date and time
        $date = $array[0];
        $time = $array[1];

        $dateArray = explode( "-", $date );

        return array(
                        "day"                 => (int)$dateArray[2],
                        "month"                => (int)$dateArray[1],
                        "year"                => (int)$dateArray[0]
        );
}

//$dt is a datetime string in MySQL default format (e.g. 2005-12-25 23:59:59)
//this functions converts it to format selected in the administrative mode
function format_datetime($dt)
{
        $dformat = (!strcmp(CONF_DATE_FORMAT,"DD.MM.YYYY")) ? "d.m.Y H:i:s" : "m/d/Y h:i:s A";
        $a = @date($dformat, strtotime($dt));
        return $a;
}

//$dt is a datetime string to MySQL default format (e.g. 2005-12-25)
//this functions converts it to format selected in the administrative mode
function dtDateConvert($dt)
{
        $dformat = (!strcmp(CONF_DATE_FORMAT,"DD.MM.YYYY")) ? "." : "/";
        $array = explode( $dformat, $dt );
        $date = $array[2]."-".$array[1]."-".$array[0];
        return $date;
}

?><?php
#####################################
# ShopCMS: Скрипт интернет-магазина
# Copyright (c) by ADGroup
# http://shopcms.ru
#####################################





function _calculateGeneralPriceDiscount( $orderPrice, $log )
{
        $customerID = (int)regGetIdByLogin($log);
        $q = db_query("select discount_id, price_range, percent_discount from ".
                        ORDER_PRICE_DISCOUNT_TABLE." order by price_range " );
        $data = array();
        while( $row = db_fetch_row($q) ) $data[] = $row;

        if ( count($data) != 0 )
        {
                for( $i=0; $i<count($data)-1; $i++ )
                {
                        if ( $data[$i][ "price_range" ] < $orderPrice
                                && $orderPrice < $data[$i+1][ "price_range" ]  )
                                return $data[$i][ "percent_discount" ];
                }
                if (  $data[ count($data)-1 ][ "price_range" ] < $orderPrice  )
                        return $data[ count($data)-1 ][ "percent_discount" ];
        }

        return 0;
}


function dscCalculateDiscount( $orderPrice, $log  )
{
        $discount = array(
                        "discount_percent"       => 0,
                        "discount_standart_unit" => 0,
                        "discount_current_unit"  => 0,
                        "rest_standart_unit"     => 0,
                        "rest_current_unit"      => 0,
                        "priceUnit"              => getPriceUnit() );
        $customerID = (int)regGetIdByLogin($log);
        switch( CONF_DISCOUNT_TYPE )
        {

                // discount is switched off
                case 1:
                        return $discount;
                        break;

                // discount is based on customer group
                case 2:
                        if (  !is_bool($customerID=regGetIdByLogin($log))  )
                        {
                                $customer_group                 = GetCustomerGroupByCustomerId( $customerID );
                                if ( $customer_group )
                                        $discount["discount_percent"]         = $customer_group["custgroup_discount"];
                                else
                                        $discount["discount_percent"]        = 0;
                        }
                        else
                                return $discount;
                        break;

                // discount is calculated with help general order price
                case 3:
                        $discount["discount_percent"]                 = _calculateGeneralPriceDiscount( $orderPrice, $log );
                        break;

                // discount equals to discount is based on customer group plus
                //                discount calculated with help general order price
                case 4:
                        if ( !is_bool($customerID) )
                        {
                                $customer_group = GetCustomerGroupByCustomerId( $customerID );
                                if ( !$customer_group )
                                        $customer_group = array( "custgroup_discount" => 0  );
                        }
                        else
                                $customer_group["custgroup_discount"] = 0;
                        $discount["discount_percent"]                 = $customer_group["custgroup_discount"] +
                                                                        _calculateGeneralPriceDiscount(
                                                                                $orderPrice, $log );
                        break;

                // discount is calculated as MAX( discount is based on customer group,
                //                        discount calculated with help general order price  )
                case 5:
                        if ( !is_bool($customerID) )
                                $customer_group = GetCustomerGroupByCustomerId( $customerID );
                        else
                                $customer_group["custgroup_discount"] = 0;
                        if ( $customer_group["custgroup_discount"] >= _calculateGeneralPriceDiscount(
                                                        $orderPrice, $log ) )
                                $discount["discount_percent"] = $customer_group["custgroup_discount"];
                        else
                                $discount["discount_percent"] = _calculateGeneralPriceDiscount( $orderPrice, $log );
                        break;
        }

        $discount["discount_standart_unit"]        = ((float)$orderPrice/100)*(float)$discount["discount_percent"];
        $discount["discount_current_unit"]        = show_priceWithOutUnit( $discount["discount_standart_unit"] );
        $discount["rest_standart_unit"]         = $orderPrice - $discount["discount_standart_unit"];
        $discount["rest_current_unit"]          = show_priceWithOutUnit( $discount["rest_standart_unit"] );
        return $discount;
}



// *****************************************************************************
// Purpose        gets all order price discounts
// Inputs
// Remarks
// Returns
function dscGetAllOrderPriceDiscounts()
{
        $q = db_query( "select discount_id, price_range, percent_discount from ".ORDER_PRICE_DISCOUNT_TABLE.
                        " order by price_range" );
        $data = array();
        while( $row = db_fetch_row($q) ) $data[] = $row;
        return $data;
}

// *****************************************************************************
// Purpose        add order price discount
// Inputs
// Remarks
// Returns        if discount with $price_range already exists this function returns false and does not add new discount
//                        otherwise true
function dscAddOrderPriceDiscount( $price_range, $percent_discount )
{
        $q=db_query( "select price_range, percent_discount from ".ORDER_PRICE_DISCOUNT_TABLE.
                        " where price_range=".xEscSQL($price_range));
        if ( ($row=db_fetch_row($q)) )
                return false;
        else
        {
                db_query("insert into ".ORDER_PRICE_DISCOUNT_TABLE." ( price_range, percent_discount ) ".
                         " values( ".xEscSQL($price_range).", ".xEscSQL($percent_discount)." ) ");
                return true;
        }
}

// *****************************************************************************
// Purpose        delete discount
// Inputs
// Remarks
// Returns
function dscDeleteOrderPriceDiscount( $discount_id )
{
        db_query("delete from ".ORDER_PRICE_DISCOUNT_TABLE." where discount_id=".(int)$discount_id);
}

// *****************************************************************************
// Purpose        update discount
// Inputs
// Remarks
// Returns
function dscUpdateOrderPriceDiscount( $discount_id, $price_range, $percent_discount )
{
        $q=db_query( "select price_range, percent_discount from ".ORDER_PRICE_DISCOUNT_TABLE.
                        " where price_range=".xEscSQL($price_range)." AND discount_id <> ".xEscSQL($discount_id));
        if ( ($row=db_fetch_row($q)) )
                return false;
        else
        {
                db_query("update ".ORDER_PRICE_DISCOUNT_TABLE.
                        " set price_range=".xEscSQL($price_range).", percent_discount=".xEscSQL($percent_discount)." ".
                        " where discount_id=".(int)$discount_id);
                return true;
        }
}

?><?php
#####################################
# ShopCMS: Скрипт интернет-магазина
# Copyright (c) by ADGroup
# http://shopcms.ru
#####################################


// *****************************************************************************
// Purpose        gets all discussion
// Inputs   $navigatorParams - item
//                                        "offset"                        - count row from begin to place being shown
//                                        "CountRowOnPage"        - count row on page to show on page
// Remarks
// Returns
//                                returns array of discussion
//                                $count_row is set to count(discussion)
function discGetAllDiscussion( $callBackParam, &$count_row, $navigatorParams = null )
{
        $data = array();

        $orderClause = "";
        if ( isset($callBackParam["sort"]) )
        {
                $orderClause = " order by ".xEscSQL($callBackParam["sort"]);
                if ( isset($callBackParam["direction"]) )
                {
                        if ( $callBackParam["direction"] == "ASC" )
                                $orderClause .= " ASC ";
                        else
                                $orderClause .= " DESC ";
                }
        }

        $filter = "";
        if ( isset($callBackParam["productID"]) )
        {
                if ( $callBackParam["productID"] != 0 )
                        $filter = " AND ".PRODUCTS_TABLE.".productID=".(int)$callBackParam["productID"];
        }

        $q = db_query("select DID, Author, Body, add_time, Topic, name AS product_name from ".
                DISCUSSIONS_TABLE.", ".PRODUCTS_TABLE.
                " where ".DISCUSSIONS_TABLE.".productID=".PRODUCTS_TABLE.".productID ".$filter." ".
                $orderClause );

         if ( $navigatorParams != null )
        {
                $offset                        = $navigatorParams["offset"];
                $CountRowOnPage        = $navigatorParams["CountRowOnPage"];
        }
        else
        {
                $offset = 0;
                $CountRowOnPage = 0;
        }
        $i=0;
        while( $row = db_fetch_row($q) )
        {
                if ( ($i >= $offset && $i < $offset + $CountRowOnPage) ||
                                $navigatorParams == null  )
                {
                        $row["add_time"]        = format_datetime( $row["add_time"] );
                        $data[] = $row;
                }
                $i ++;
        }
        $count_row = $i;
        return $data;
}

function discGetAllDiscussedProducts()
{
        $q = db_query(
                "select name AS product_name, ".PRODUCTS_TABLE.".productID AS productID from ".
                        DISCUSSIONS_TABLE.", ".PRODUCTS_TABLE.
                        " where ".DISCUSSIONS_TABLE.".productID=".PRODUCTS_TABLE.".productID ".
                        " group by ".PRODUCTS_TABLE.".productID, ".PRODUCTS_TABLE.".name order by product_name" );
        $data = array();
        while( $row = db_fetch_row($q) ) $data[] = $row;
        return $data;
}

function discGetDiscussion( $DID )
{
        $q = db_query("select DID, Author, Body, add_time, Topic, name AS product_name, ".
                " ".PRODUCTS_TABLE.".productID AS productID from ".
                DISCUSSIONS_TABLE.", ".PRODUCTS_TABLE.
                " where ".DISCUSSIONS_TABLE.".productID=".PRODUCTS_TABLE.".productID AND DID=".(int)$DID);
        $row = db_fetch_row( $q );
        $row["add_time"] = format_datetime( $row["add_time"] );
        return $row;
}


function discAddDiscussion( $productID, $Author, $Topic, $Body )
{
        db_query("insert into ".DISCUSSIONS_TABLE.
                "(productID, Author, Body, add_time, Topic)  ".
                "values( ".(int)$productID.", '".xToText($Author)."', '".xToText($Body)."', '".get_current_time()."', '".xToText($Topic)."' )");
}

function discDeleteDiscusion( $DID )
{
        db_query( "delete from ".DISCUSSIONS_TABLE." where DID=".(int)$DID );
}

?><?php
  #####################################
  # ShopCMS: Г‘ГЄГ°ГЁГЇГІ ГЁГ­ГІГҐГ°Г­ГҐГІ-Г¬Г ГЈГ Г§ГЁГ­Г 
  # Copyright (c) by ADGroup
  # http://shopcms.ru
  #####################################

  function isWindows(){

        if(isset($_SERVER["WINDIR"]) || isset($_SERVER["windir"]))
                return true;
        else
                return false;
  }

  function myfile_get_contents($fileName)
  {
      return implode("", file($fileName));
  }


  function correct_URL($url, $mode = "http") //converts

  {
      $URLprefix = trim($url);
      $URLprefix = str_replace("http://", "", $URLprefix);
      $URLprefix = str_replace("https://", "", $URLprefix);
      $URLprefix = str_replace("index.php", "", $URLprefix);
      if ($URLprefix[strlen($URLprefix) - 1] == '/')
      {
          $URLprefix = substr($URLprefix, 0, strlen($URLprefix) - 1);
      }
      return ($mode."://".$URLprefix."/");
  }

  // *****************************************************************************
  // Purpose        sets access rights to files which uploaded with help move_uploaded_file
  //                        function
  // Inputs           $file_name - file name
  // Remarks
  // Returns        nothing
  function SetRightsToUploadedFile($file_name)
  {
      @chmod($file_name, 0666);
  }

  function getmicrotime()
  {
      list($usec, $sec) = explode(" ", microtime());
      return ((float)$usec + (float)$sec);
  }

  // *****************************************************************************
  // Purpose        this function works without errors ( as is_writable PHP functoin )
  // Inputs           $url
  // Remarks
  // Returns        nothing
  function IsWriteable($fileName)
  {
      $f = @fopen($fileName, "a");
      return !is_bool($f);
  }


  // *****************************************************************************
  // Purpose        redirects to other PHP page specified URL ( $url )
  // Inputs           $url
  // Remarks        this function uses header
  // Returns        nothing
  function Redirect($url)
  {
      header("Location: ".$url);
      exit();
  }


  // *****************************************************************************
  // Purpose        redirects to other PHP page specified URL ( $url )
  // Inputs
  // Remarks        if CONF_PROTECTED_CONNECTION == '1' this function uses protected ( https:// ) connection
  //                        else it uses unsecure http://
  //                        $url is relative URL, NOT an absolute one, e.g. index.php, index.php?productID=x, but not http://www.example.com/
  // Returns        nothing
  function RedirectProtected($url)
  {
      if (CONF_PROTECTED_CONNECTION == '1')
      {
          Redirect(correct_URL(CONF_FULL_SHOP_URL, "https").$url); //redirect to HTTPS part of the website
      }
      else  Redirect($url); //relative URL
  }


  // *****************************************************************************
  // Purpose        redirects to other PHP page specified URL ( $url )
  // Inputs           $url
  // Remarks        this function uses JavaScript client script
  // Returns        nothing
  function RedirectJavaScript($url)
  {
      die("<script type='text/javascript'> window.location = '".$url."'; </script>");
  }


  // *****************************************************************************
  // Purpose        round float value to 0.01 precision
  // Inputs           $float_value - value to float
  // Remarks
  // Returns        rounded value
  function roundf($float_value)
  {
      return round(100 * $float_value) / 100;
  }

  function _testExtension($filename, $extension)
  {
      if ($extension == null || trim($extension) == "") return true;
      $i = strlen($filename) - 1;
      for (; $i >= 0; $i--)
      {
          if ($filename[$i] == '.') break;
      }

      if ($filename[$i] != '.') return false;
      else
      {
          $ext = substr($filename, $i + 1);
          return (strtolower($extension) == strtolower($ext));
      }
  }

  function checklogin() {
  
    $rls = array();

    if (isset($_SESSION["log"])) //look for user in the database

    { 
      $q = db_query("select cust_password, actions FROM ".CUSTOMERS_TABLE." WHERE Login='".xEscSQL($_SESSION["log"])."'");
      $row = db_fetch_row($q); //found customer - check password

      if (!$row || !isset($_SESSION["pass"]) || $row[0]!=$_SESSION["pass"]) //unauthorized access
      {
          unset($_SESSION["log"]);
          unset($_SESSION["pass"]);
          session_unregister("log"); //calling session_unregister() is required since unset() may not work on some systems
          session_unregister("pass");
		  
      }else{
	  
          $rls = unserialize($row[1]);
          unset($row);
		  
      }
    }
  
    return $rls;  
  }
  
  // *****************************************************************************
  // Purpose        gets all files in specified directory
  // Inputs   $dir - full path directory
  // Remarks
  // Returns
  function GetFilesInDirectory( $dir, $extension = "" )
  {
        $dh  = opendir($dir);
        $files = array();
        while (false !== ($filename = readdir($dh)))
        {
                if ( !is_dir($dir.'/'.$filename) && $filename != "." && $filename != ".." )
                {
                        if ( _testExtension($filename,$extension) )
                                $files[] = $dir."/".$filename;
                }
        }
        return $files;
  }

  // *****************************************************************************
  // Purpose        gets class name in file
  // Inputs   $fileName - full file name
  // Remarks        this file must contains only one class syntax valid declaration
  // Returns        class name
  function GetClassName( $fileName )
  {
        $strContent = myfile_get_contents( $fileName );
        $_match = array();
        $strContent = substr($strContent, strpos($strContent, '@connect_module_class_name'), 100);
        if(preg_match("|\@connect_module_class_name[\t ]+([0-9a-z_]*)|mi", $strContent, $_match)){

                return $_match[1];
        }else {

                return false;
        }
  }

  function InstallModule( $module )
  {
        db_query("insert into ".MODULES_TABLE." ( module_name ) ".
                " values( '".xEscSQL($module->title)."' ) ");
  }

  function GetModuleId( $module )
  {
        $q = db_query("select module_id from ".MODULES_TABLE.
                " where module_name='".xEscSQL($module->title)."' ");
        $row = db_fetch_row($q);
        return (int)$row["module_id"];
  }

  function _formatPrice($price, $rval = 2, $dec = '.', $term = ' ')
  {
      return number_format($price, $rval, $dec, $term);
  }
  
  //show a number and selected currency sign $price is in universal currency
  function show_price($price, $custom_currency = 0, $code = true, $d = ".", $t = " ")
  {
      global $selected_currency_details;
      //if $custom_currency != 0 show price this currency with ID = $custom_currency
      if ($custom_currency == 0)
      {
          if (!isset($selected_currency_details) || !$selected_currency_details) //no currency found

          {
              return $price;
          }
      }
      else //show price in custom currency

      {

          $q = db_query("select code, currency_value, where2show, currency_iso_3, Name, roundval from ".
              CURRENCY_TYPES_TABLE." where CID=".(int)$custom_currency);
          if ($row = db_fetch_row($q))
          {
              $selected_currency_details = $row; //for show_price() function
          }
          else //no currency found. In this case check is there any currency type in the database

          {
              $q = db_query("select code, currency_value, where2show, roundval from ".CURRENCY_TYPES_TABLE);
              if ($row = db_fetch_row($q))
              {
                  $selected_currency_details = $row; //for show_price() function
              }
          }

      }

      //is exchange rate negative or 0?
      if ($selected_currency_details[1] == 0) return "";
      
	  $price = roundf($price * $selected_currency_details[1]);
      //now show price
      $price = _formatPrice($price, $selected_currency_details["roundval"], $d, $t);
      if($code)
      return $selected_currency_details[2] ? $price.$selected_currency_details[0] : $selected_currency_details[0].$price;
	  else
      return $price;
  }

  function ShowPriceInTheUnit($price, $currencyID)
  {
      $q_currency = db_query("select currency_value, where2show, code, roundval from ".CURRENCY_TYPES_TABLE." where CID=".(int)$currencyID);
      $currency = db_fetch_row($q_currency);
      $price = _formatPrice(roundf($price * $currency["currency_value"]), $currency["roundval"]);
      return $currency["where2show"] ? $price.$currency["code"] : $currency["code"].$price;
  }

  function addUnitToPrice($price)
  {
      global $selected_currency_details;
      $price = _formatPrice($price, $selected_currency_details["roundval"]);
      return $selected_currency_details[2] ? $price.$selected_currency_details[0] : $selected_currency_details[0].
          $price;
  }

  function ConvertPriceToUniversalUnit($priceWithOutUnit)
  {
      global $selected_currency_details;
      return (float)$priceWithOutUnit / (float)$selected_currency_details[1];
  }

  function show_priceWithOutUnit($price)
  {
      global $selected_currency_details;

      if (!isset($selected_currency_details) || !$selected_currency_details) //no currency found

      {
          return $price;
      }

      //is exchange rate negative or 0?
      if ($selected_currency_details[1] == 0) return "";

      //now show price
      $price = round(100 * $price * $selected_currency_details[1]) / 100;
      if (round($price * 10) == $price * 10 && round($price) != $price) $price = "$price"."0"; //to avoid prices like 17.5 - write 17.50 instead
      return (float)$price;
  }

  function getPriceUnit()
  {
      global $selected_currency_details;

      if (!isset($selected_currency_details) || !$selected_currency_details) //no currency found

      {
          return "";
      }
      return $selected_currency_details[0];
  }

  function getLocationPriceUnit()
  {
      global $selected_currency_details;

      if (!isset($selected_currency_details) || !$selected_currency_details) //no currency found

      {
          return true;
      }
      return $selected_currency_details[2];
  }


  /*
  function get_current_time() //get current date and time as a string
  //required to do INSERT queries of DATETIME/TIMESTAMP in different DBMSes
  {
  $timestamp = time();
  if (DBMS == 'mssql')
  // $s = strftime("%H:%M:%S %d/%m/%Y", $timestamp);
  $s = strftime("%m.%d.%Y %H:%M:%S", $timestamp);
  else // MYSQL or IB
  $s = strftime("%Y-%m-%d %H:%M:%S", $timestamp);

  return $s;
  }
  */

  function ShowNavigator($a, $offset, $q, $path, &$out)
  {
      //shows navigator [prev] 1 2 3 4 вЂ¦ [next]
      //$a - count of elements in the array, which is being navigated
      //$offset - current offset in array (showing elements [$offset ... $offset+$q])
      //$q - quantity of items per page
      //$path - link to the page (f.e: "index.php?categoryID=1&")

      if ($a > $q) //if all elements couldn't be placed on the page

      {

          //[prev]
          if ($offset > 0) $out .= "<a href=\"".$path."offset=".($offset - $q)."\">&lt;"."".
                  "</a>&nbsp;&nbsp;";

          //digital links
          $k = $offset / $q;

          //not more than 4 links to the left
          $min = $k - 5;
          if ($min < 0)
          {
              $min = 0;
          }
          else
          {
              if ($min >= 1)
              { //link on the 1st page
                  $out .= "<a href=\"".$path."offset=0\">1</a>&nbsp;&nbsp;";
                  if ($min != 1)
                  {
                      $out .= "... &nbsp;&nbsp;";
                  }
                  ;
              }
          }

          for ($i = $min; $i < $k; $i++)
          {
              $m = $i * $q + $q;
              if ($m > $a) $m = $a;

              $out .= "<a href=\"".$path."offset=".($i * $q)."\">".($i + 1)."</a>&nbsp;&nbsp;";
          }

          //# of current page
          if (strcmp($offset, "show_all"))
          {
              $min = $offset + $q;
              if ($min > $a) $min = $a;
              $out .= "<b>".($k + 1)."</b>&nbsp;&nbsp;";
          }
          else
          {
              $min = $q;
              if ($min > $a) $min = $a;
              $out .= "<a href=\"".$path."offset=0\">1</a>&nbsp;&nbsp;";
          }

          //not more than 5 links to the right
          $min = $k + 6;
          if ($min > $a / $q)
          {
              $min = $a / $q;
          }
          ;
          for ($i = $k + 1; $i < $min; $i++)
          {
              $m = $i * $q + $q;
              if ($m > $a) $m = $a;

              $out .= "<a href=\"".$path."offset=".($i * $q)."\">".($i + 1)."</a>&nbsp;&nbsp;";
          }

          if (ceil($min * $q) < $a)
          { //the last link
              if ($min * $q < $a - $q) $out .= "... &nbsp;&nbsp;";
              $out .= "<a href=\"".$path."offset=".($a - $a % $q)."\">".(floor($a / $q) + 1)."</a>&nbsp;&nbsp;";
          }

          //[next]
          if (strcmp($offset, "show_all"))
              if ($offset < $a - $q) $out .= "<a href=\"".$path."offset=".($offset + $q)."\">"."".
                      "&gt;</a>&nbsp;&nbsp;";

          

      }
  }

  function ShowNavigatormd($a, $offset, $q, $path, &$out)
  {
      //shows navigator [prev] 1 2 3 4 вЂ¦ [next]
      //$a - count of elements in the array, which is being navigated
      //$offset - current offset in array (showing elements [$offset ... $offset+$q])
      //$q - quantity of items per page
      //$path - link to the page (f.e: "index.php?categoryID=1&")

      if ($a > $q) //if all elements couldn't be placed on the page

      {

          //[prev]
          if ($offset > 0) $out .= "<a href=\"".$path."offset_".($offset - $q).".html\">&lt;".''.
                  "</a>&nbsp;&nbsp;";

          //digital links
          $k = $offset / $q;

          //not more than 4 links to the left
          $min = $k - 5;
          if ($min < 0)
          {
              $min = 0;
          }
          else
          {
              if ($min >= 1)
              { //link on the 1st page
                  $out .= "<a href=\"".$path."offset_0.html\">1</a>&nbsp;&nbsp;";
                  if ($min != 1)
                  {
                      $out .= "...&nbsp;&nbsp;";
                  }
                  ;
              }
          }

          for ($i = $min; $i < $k; $i++)
          {
              $m = $i * $q + $q;
              if ($m > $a) $m = $a;

              $out .= "<a href=\"".$path."offset_".($i * $q).".html\">".($i + 1)."</a>&nbsp;&nbsp;";
          }

          //# of current page
          if (strcmp($offset, "show_all"))
          {
              $min = $offset + $q;
              if ($min > $a) $min = $a;
              $out .= "<b>".($k + 1)."</b>&nbsp;&nbsp;";
          }
          else
          {
              $min = $q;
              if ($min > $a) $min = $a;
              $out .= "<a href=\"".$path."offset_0.html\">1</a>&nbsp;&nbsp;";
          }

          //not more than 5 links to the right
          $min = $k + 6;
          if ($min > $a / $q)
          {
              $min = $a / $q;
          }
          ;
          for ($i = $k + 1; $i < $min; $i++)
          {
              $m = $i * $q + $q;
              if ($m > $a) $m = $a;

              $out .= "<a href=\"".$path."offset_".($i * $q).".html\">".($i + 1)."</a>&nbsp;&nbsp;";
          }

          if (ceil($min * $q) < $a)
          { //the last link
              if ($min * $q < $a - $q) $out .= "... &nbsp;&nbsp;";
              $out .= "<a href=\"".$path."offset_".($a - $a % $q).".html\">".(floor($a / $q) + 1)."</a>&nbsp;&nbsp;";
          }

          //[next]
          if (strcmp($offset, "show_all"))
              if ($offset < $a - $q) $out .= "<a href=\"".$path."offset_".($offset + $q).".html\">".
                      ''."&gt;</a>&nbsp;&nbsp;";

          

      }
  }

  function GetNavigatorHtmlmd($url, $countRowOnPage = CONF_PRODUCTS_PER_PAGE, $callBackFunction, $callBackParam,
      &$tableContent, &$offset, &$count, $urlflag)
  {
      if (isset($_GET["offset"])) $offset = (int)$_GET["offset"];
      else  $offset = 0;
      $offset -= $offset % $countRowOnPage; //CONF_PRODUCTS_PER_PAGE;
      if ($offset < 0) $offset = 0;
      $count = 0;

      if (!isset($_GET["show_all"])) //show 'CONF_PRODUCTS_PER_PAGE' products on this page

      {
          $tableContent = $callBackFunction($callBackParam, $count, array("offset" => $offset, "CountRowOnPage" =>
              $countRowOnPage));
      }
      else //show all products

      {
          $tableContent = $callBackFunction($callBackParam, $count, null);
          $offset = "show_all";
      }

      if ($urlflag) ShowNavigatormd($count, $offset, $countRowOnPage, html_spchars($url."_"), $out);
      else  ShowNavigator($count, $offset, $countRowOnPage, html_spchars($url."&"), $out);
      return $out;
  }

  function GetCurrentURL($file, $exceptKeys)
  {
      $res = $file;
      foreach ($_GET as $key => $val)
      {
          $exceptFlag = false;
          foreach ($exceptKeys as $exceptKey)
              if ($exceptKey == $key)
              {
                  $exceptFlag = true;
                  break;
              }

          if (!$exceptFlag)
          {
              if ($res == $file) $res .= "?".$key."=".$val;
              else  $res .= "&".$key."=".$val;
          }
      }
      return $res;
  }


  function GetNavigatorHtml($url, $countRowOnPage = CONF_PRODUCTS_PER_PAGE, $callBackFunction, $callBackParam,
      &$tableContent, &$offset, &$count)
  {
      if (isset($_GET["offset"])) $offset = (int)$_GET["offset"];
      else  $offset = 0;
      $offset -= $offset % $countRowOnPage; //CONF_PRODUCTS_PER_PAGE;
      if ($offset < 0) $offset = 0;
      $count = 0;

      if (!isset($_GET["show_all"])) //show 'CONF_PRODUCTS_PER_PAGE' products on this page

      {
          $tableContent = $callBackFunction($callBackParam, $count, array("offset" => $offset, "CountRowOnPage" =>
              $countRowOnPage));
      }
      else //show all products

      {
          $tableContent = $callBackFunction($callBackParam, $count, null);
          $offset = "show_all";
      }

      ShowNavigator($count, $offset, $countRowOnPage, html_spchars($url."&"), $out);
      return $out;
  }


  function moveCartFromSession2DB() //all products in shopping cart, which are in session vars, move to the database

  {
      if (isset($_SESSION["gids"]) && isset($_SESSION["log"]))
      {

          $customerID = regGetIdByLogin($_SESSION["log"]);
          $q = db_query("select itemID from ".SHOPPING_CARTS_TABLE." where customerID=".(int)$customerID);
          $items = array();
          while ($item = db_fetch_row($q)) $items[] = (int)$item["itemID"];

          //$i=0;
          foreach ($_SESSION["gids"] as $key => $productID)
          {
              if ($productID == 0) continue;

              // search product in current user's shopping cart content
              $itemID = null;
              for ($j = 0; $j < count($items); $j++)
              {
                  $q = db_query("select count(*) from ".SHOPPING_CART_ITEMS_TABLE." where productID=".
                      (int)$productID." AND itemID=".(int)$items[$j]);
                  $count = db_fetch_row($q);
                  $count = $count[0];
                  if ($count != 0)
                  {
                      // compare configuration
                      $configurationFromSession = $_SESSION["configurations"][$key];
                      $configurationFromDB = GetConfigurationByItemId($items[$j]);
                      if (CompareConfiguration($configurationFromSession, $configurationFromDB))
                      {
                          $itemID = $items[$j];
                          break;
                      }
                  }
              }


              if ($itemID == null)
              {
                  // create new item
                  db_query("insert into ".SHOPPING_CART_ITEMS_TABLE." (productID) values(".(int)$productID.")");
                  $itemID = db_insert_id();

                  // set content item
                  foreach ($_SESSION["configurations"][$key] as $vars)
                  {
                      db_query("insert into ".SHOPPING_CART_ITEMS_CONTENT_TABLE." ( itemID, variantID ) ".
                          " values( ".(int)$itemID.", ".(int)$vars." )");
                  }

                  // insert item into cart
                  db_query("insert ".SHOPPING_CARTS_TABLE." (customerID, itemID, Quantity) values ( ".
                      (int)$customerID.", ".(int)$itemID.", ".(int)$_SESSION["counts"][$key]." )");
              }
              else
              {
                  db_query("update ".SHOPPING_CARTS_TABLE." set Quantity=Quantity + ".(int)$_SESSION["counts"][$key]." where customerID=".(int)$customerID." and itemID=".(int)$itemID);
              }

          }

          unset($_SESSION["gids"]);
          unset($_SESSION["counts"]);
          unset($_SESSION["configurations"]);
          session_unregister("gids"); //calling session_unregister() is required since unset() may not work on some systems
          session_unregister("counts");
          session_unregister("configurations");
      }
  } // moveCartFromSession2DB

  function validate_search_string($s) //validates $s - is it good as a search query

  {
      //exclude special SQL symbols
      $s = str_replace("%", "", $s);
      $s = str_replace("_", "", $s);
      //",',\
      $s = stripslashes($s);
      $s = str_replace("'", "\'", $s);
      return $s;

  } //validate_search_string

  function string_encode($s) // encodes a string with a simple algorythm

  {
      $result = base64_encode($s);
      return $result;
  }

  function string_decode($s) // decodes a string encoded with string_encode()

  {
      $result = base64_decode($s);
      return $result;
  }


  // *****************************************************************************
  // Purpose        this function creates array it containes value POST variables
  // Inputs                     name array
  // Remarks                if <name> is contained in $varnames, then for POST variable
  //                                <name>_<id> in result array $data (see body) item is added
  //                                with key <id> and POST variable <name>_<id> value
  // Returns                array $data ( see Remarks )
  function ScanPostVariableWithId($varnames)
  {
      $data = array();
      foreach ($varnames as $name)
      {
          foreach ($_POST as $key => $value)
          {
              if (strstr($key, $name."_"))
              {
                  $key = str_replace($name."_", "", $key);
                  $data[$key][$name] = $value;
              }
          }
      }
      return $data;
  }

  function ScanFilesVariableWithId($varnames)
  {
      $data = array();
      foreach ($varnames as $name)
      {
          foreach ($_FILES as $key => $value)
          {
              if (strstr($key, $name."_"))
              {
                  $key = str_replace($name."_", "", $key);
                  $data[$key][$name] = $value;
              }
          }
      }
      return $data;
  }
  // *****************************************************************************
  // Purpose        this functin does also as ScanPostVariableWithId
  //                        but it uses GET variables
  // Inputs             see ScanPostVariableWithId
  // Remarks        see ScanPostVariableWithId
  // Returns        see ScanPostVariableWithId
  function ScanGetVariableWithId($varnames)
  {
      $data = array();
      foreach ($varnames as $name)
      {
          foreach ($_GET as $key => $value)
          {
              if (strstr($key, $name."_"))
              {
                  $key = str_replace($name."_", "", $key);
                  $data[$key][$name] = $value;
              }
          }
      }
      return $data;
  }


  function value($variable)
  {
      if (!isset($variable)) return "undefined";

      $res = "";
      if (is_null($variable))
      {
          $res .= "NULL";
      }
      else
          if (is_array($variable))
          {
              $res .= "<b>array</b>";
              $res .= "<ul>";
              foreach ($variable as $key => $value)
              {
                  $res .= "<li>";
                  $res .= "[ ".value($key)." ]=".value($value);
                  $res .= "</li>";
              }
              $res .= "</ul>";
          }
          else
              if (is_int($variable))
              {
                  $res .= "<b>integer</b>\n";
                  $res .= (string )$variable;
              }
              else
                  if (is_bool($variable))
                  {
                      $res .= "<b>bool</b>\n";
                      if ($variable) $res .= "<i>True</i>";
                      else  $res .= "<i>False</i>";
                  }
                  else
                      if (is_string($variable))
                      {
                          $res .= "<b>string</b>\n";
                          $res .= "'".(string )$variable."'";
                      }
                      else
                          if (is_float($variable))
                          {
                              $res .= "<b>float</b>\n";
                              $res .= (string )$variable;
                          }

      return $res;
  }


  function debug($variable)
  {
      if (!isset($variable))
      {
          echo ("undefined");
      }
      else
      {
          echo "<div align=\"left\">";
          echo (value($variable)."<br>");
          echo "</div>";
      }
  }

  function set_query($_vars, $_request = '', $_store = false)
  {

      if (!$_request)
      {

          global $_SERVER;
          $_request = $_SERVER['REQUEST_URI'];
      }

      $_anchor = '';
      @list($_request, $_anchor) = explode('#', $_request);

      if (strpos($_vars, '#') !== false)
      {

          @list($_vars, $_anchor) = explode('#', $_vars);
      }

      if (!$_vars && !$_anchor) return preg_replace('|\?.*$|', '', $_request).($_anchor ? '#'.$_anchor :
              '');
      elseif (!$_vars && $_anchor) return $_request.'#'.$_anchor;

      $_rvars = array();
      $tr_vars = explode('&', strpos($_request, '?') !== false ? preg_replace('|.*\?|', '', $_request) :
          '');
      foreach ($tr_vars as $_var)
      {

          $_t = explode('=', $_var);
          if ($_t[0]) $_rvars[$_t[0]] = $_t[1];
      }
      $tr_vars = explode('&', preg_replace(array('|^\&|', '|^\?|'), '', $_vars));
      foreach ($tr_vars as $_var)
      {

          $_t = explode('=', $_var);
          if (!$_t[1]) unset($_rvars[$_t[0]]);
          else  $_rvars[$_t[0]] = $_t[1];
      }
      $tr_vars = array();
      foreach ($_rvars as $_var => $_val) $tr_vars[] = "$_var=$_val";

      if ($_store)
      {

          global $_SERVER;
          $_request = $_SERVER['REQUEST_URI'];
          $_SERVER['REQUEST_URI'] = preg_replace('|\?.*$|', '', $_request).(count($tr_vars) ? '?'.implode
              ('&', $tr_vars) : '').($_anchor ? '#'.$_anchor : '');
          return $_SERVER['REQUEST_URI'];
      }
      else  return preg_replace('|\?.*$|', '', $_request).(count($tr_vars) ? '?'.implode('&', $tr_vars) :
              '').($_anchor ? '#'.$_anchor : '');
  }

  function getListerRange($_pagenumber, $_totalpages, $_lister_num = 20)
  {

      if ($_pagenumber <= 0) return array('start' => 1, 'end' => 1);
      $lister_start = $_pagenumber - floor($_lister_num / 2);
      $lister_start = ($lister_start + $_lister_num <= $_totalpages ? $lister_start : $_totalpages -
          $_lister_num + 1);
      $lister_start = ($lister_start > 0 ? $lister_start : 1);
      $lister_end = $lister_start + $_lister_num - 1;
      $lister_end = ($lister_end <= $_totalpages ? $lister_end : $_totalpages);
      return array('start' => $lister_start, 'end' => $lister_end);
  }

  function html_spchars($_data)
  {

      if (is_array($_data))
      {

          foreach ($_data as $_ind => $_val)
          {

              $_data[$_ind] = html_spchars($_val);
          }
          return $_data;
      }
      else  return htmlspecialchars($_data, ENT_QUOTES);
  }

  function html_amp($_data)
  {

      if (is_array($_data))
      {

          foreach ($_data as $_ind => $_val)
          {

              $_data[$_ind] = strtr($_val, array('&' => '&amp;'));
          }
          return $_data;
      }
      else  return strtr($_data, array('&' => '&amp;'));
  }

  function ToText($str)
  {
      $str = htmlspecialchars(trim($str), ENT_QUOTES);
      return $str;
  }

  function xToText($str)
  {
      $str = xEscSQL(xHtmlSpecialChars($str));
      return $str;
  }

  function xStripSlashesGPC($_data)
  {

      if (!get_magic_quotes_gpc()) return $_data;
      if (is_array($_data))
      {

          foreach ($_data as $_ind => $_val)
          {

              $_data[$_ind] = xStripSlashesGPC($_val);
          }
          return $_data;
      }
      return stripslashes($_data);
  }

  /**
   * Transform date from template format to DATETIME format
   *
   * @param string $_date
   * @param string $_template template for transform
   * @return string
   */
  function TransformTemplateToDATE($_date, $_template = '')
  {

      if (!$_template) $_template = CONF_DATE_FORMAT;
      $day = substr($_date, strpos($_template, 'DD'), 2);
      $month = substr($_date, strpos($_template, 'MM'), 2);
      $year = substr($_date, strpos($_template, 'YYYY'), 4);
      return "{$year}-{$month}-{$day} ";
  }

  /**
   * Transform DATE to template format
   *
   * @param string $_date
   * @param string $_template template for transform
   * @return string
   */
  function TransformDATEToTemplate($_date, $_template = '')
  {

      if (!$_template) $_template = CONF_DATE_FORMAT;
      preg_match('|(\d{4})-(\d{2})-(\d{2})|', $_date, $mathes);
      unset($mathes[0]);
      return str_replace(array('YYYY', 'MM', 'DD'), $mathes, $_template);
  }

  /**
   * Check date in template format
   *
   * @param string $_date
   * @param string $_template template for check
   * @return bool
   */
  function isTemplateDate($_date, $_template = '')
  {

      if (!$_template) $_template = CONF_DATE_FORMAT;

      $ok = (strlen($_date) == strlen($_template) && (preg_replace('|\d{2}|', '', $_date) == str_replace
          (array('MM', 'DD', 'YYYY'), '', $_template)));
      $ok = ($ok && substr($_date, strpos($_template, 'DD'), 2) < 32 && substr($_date, strpos($_template,
          'MM'), 2) < 13);
      return $ok;
  }

  /**
   * mail txt message from template
   * @param string email
   * @param string email subject
   * @param string template name
   */
  function xMailTxt($_Email, $_Subject, $_TemplateName, $_AssignArray = array())
  {

      if (!$_Email) return 0;

      $mailSmarty = new Smarty();
      foreach ($_AssignArray as $_var => $_val)
      {

          $mailSmarty->assign($_var, $_val);
      }

      $_msg = $mailSmarty->fetch('email/'.$_TemplateName);

      include_once ("core/classes/class.phpmailer.php");
      $mail = new PHPMailer();
      if (!CONF_MAIL_METHOD) $mail->IsSMTP();
      else  $mail->IsMail();
      $mail->Host = CONF_MAIL_HOST;
      $mail->Username = CONF_MAIL_LOGIN;
      $mail->Password = CONF_MAIL_PASS;
      $mail->SMTPAuth = true;
      $mail->From = CONF_GENERAL_EMAIL;
      $mail->FromName = CONF_SHOP_NAME;
      $mail->CharSet = DEFAULT_CHARSET;
      $mail->Encoding = "8bit";
      $mail->SetLanguage("ru");
      $mail->AddReplyTo(CONF_GENERAL_EMAIL, CONF_SHOP_NAME);
      $mail->IsHTML(true);
      $mail->Subject = $_Subject;
      $mail->Body = $_msg;
      $mail->AltBody = ERROR_NO_TEXT_IN_MAILDATA;

      if (preg_match("/^[_\.a-z0-9-]{1,20}@(([a-z0-9-]+\.)+(com|net|org|mil|edu|gov|arpa|info|biz|inc|name|[a-z]{2})|[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3})$/is",
          $_Email))
      {
          $mail->ClearAddresses();
          $mail->AddAddress($_Email, '');
          return $mail->Send();
      }
      else  return false;
  }

  function xMailTxtHTML($_Email, $_Subject, $_Text, $castmail = CONF_GENERAL_EMAIL, $castname = CONF_SHOP_NAME)
  {

      if (!$_Email) return 0;

      include_once ("core/classes/class.phpmailer.php");
      $mail = new PHPMailer();
      if (!CONF_MAIL_METHOD) $mail->IsSMTP();
      else  $mail->IsMail();
      $mail->Host = CONF_MAIL_HOST;
      $mail->Username = CONF_MAIL_LOGIN;
      $mail->Password = CONF_MAIL_PASS;
      $mail->SMTPAuth = true;
      $mail->From = $castmail;
      $mail->FromName = $castname;
      $mail->CharSet = DEFAULT_CHARSET;
      $mail->Encoding = "8bit";
      $mail->SetLanguage("ru");
      $mail->AddReplyTo($castmail, $castname);
      $mail->IsHTML(false);
      $mail->Subject = $_Subject;
      $mail->Body = $_Text;

      if (preg_match("/^[_\.a-z0-9-]{1,20}@(([a-z0-9-]+\.)+(com|net|org|mil|edu|gov|arpa|info|biz|inc|name|[a-z]{2})|[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3})$/is",
          $_Email))
      {
          $mail->ClearAddresses();
          $mail->AddAddress($_Email, '');
          return $mail->Send();
      }
      else  return false;
  }

  function xMailTxtHTMLDATA($_Email, $_Subject, $_Text, $castmail = CONF_GENERAL_EMAIL, $castname = CONF_SHOP_NAME)
  {

      if (!$_Email) return 0;

      include_once ("core/classes/class.phpmailer.php");
      $mail = new PHPMailer();
      if (!CONF_MAIL_METHOD) $mail->IsSMTP();
      else  $mail->IsMail();
      $mail->Host = CONF_MAIL_HOST;
      $mail->Username = CONF_MAIL_LOGIN;
      $mail->Password = CONF_MAIL_PASS;
      $mail->SMTPAuth = true;
      $mail->From = $castmail;
      $mail->FromName = $castname;
      $mail->CharSet = DEFAULT_CHARSET;
      $mail->Encoding = "8bit";
      $mail->SetLanguage("ru");
      $mail->AddReplyTo($castmail, $castname);
      $mail->IsHTML(true);
      $mail->Subject = $_Subject;
      $mail->Body = $_Text;
      $mail->AltBody = ERROR_NO_TEXT_IN_MAILDATA;

      if (preg_match("/^[_\.a-z0-9-]{1,20}@(([a-z0-9-]+\.)+(com|net|org|mil|edu|gov|arpa|info|biz|inc|name|[a-z]{2})|[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3})$/is",
          $_Email))
      {
          $mail->ClearAddresses();
          $mail->AddAddress($_Email, '');
          return $mail->Send();
      }
      else  return false;
  }

  function _deleteHTML_Elements( $str )
  {
      $search = array ("'&(deg|#176);'i","'&(nbsp|#160);'i","'&(ndash|#8211);'i","'&(mdash|#8212);'i","'&(bull|#149);'i","'&(quot|#34|#034);'i","'&(amp|#38|#038);'i","'&(lt|#60|#060);'i","'&(gt|#62|#062);'i","'&(apos|#39|#039);'i","'&(minus|#45|#045);'i","'&(circ|#94|#094);'i","'&(sup2|#178);'i","'&(tilde|#126);'i","'&(Scaron|#138);'i","'&(lsaquo|#139);'i","'&(OElig|#140);'i","'&(lsquo|#145);'i","'&(rsquo|#146);'i","'&(ldquo|#147);'i","'&(rdquo|#148);'i","'&(ndash|#150);'i","'&(mdash|#151);'i","'&(tilde|#152);'i","'&(trade|#153);'i","'&(scaron|#154);'i","'&(rsaquo|#155);'i","'&(oelig|#156);'i","'&(Yuml|#159);'i","'&(yuml|#255);'i","'&(OElig|#338);'i","'&(oelig|#339);'i","'&(Scaron|#352);'i","'&(scaron|#353);'i","'&(Yuml|#376);'i","'&(fnof|#402);'i","'&(circ|#710);'i","'&(tilde|#732);'i","'&(Alpha|#913);'i","'&(Beta|#914);'i","'&(Gamma|#915);'i","'&(Delta|#916);'i","'&(Epsilon|#917);'i","'&(Zeta|#918);'i","'&(Eta|#919);'i","'&(Theta|#920);'i","'&(Iota|#921);'i","'&(Kappa|#922);'i","'&(Lambda|#923);'i","'&(Mu|#924);'i","'&(Nu|#925);'i","'&(Xi|#926);'i","'&(Omicron|#927);'i","'&(Pi|#928);'i","'&(Rho|#929);'i","'&(Sigma|#931);'i","'&(Tau|#932);'i","'&(Upsilon|#933);'i","'&(Phi|#934);'i","'&(Chi|#935);'i","'&(Psi|#936);'i","'&(Omega|#937);'i","'&(alpha|#945);'i","'&(beta|#946);'i","'&(gamma|#947);'i","'&(delta|#948);'i","'&(epsilon|#949);'i","'&(zeta|#950);'i","'&(eta|#951);'i","'&(theta|#952);'i","'&(iota|#953);'i","'&(kappa|#954);'i","'&(lambda|#955);'i","'&(mu|#956);'i","'&(nu|#957);'i","'&(xi|#958);'i","'&(omicron|#959);'i","'&(pi|#960);'i","'&(rho|#961);'i","'&(sigmaf|#962);'i","'&(sigma|#963);'i","'&(tau|#964);'i","'&(upsilon|#965);'i","'&(phi|#966);'i","'&(chi|#967);'i","'&(psi|#968);'i","'&(omega|#969);'i","'&(thetasym|#977);'i","'&(upsih|#978);'i","'&(piv|#982);'i","'&(ensp|#8194);'i","'&(emsp|#8195);'i","'&(thinsp|#8201);'i","'&(zwnj|#8204);'i","'&(zwj|#8205);'i","'&(lrm|#8206);'i","'&(rlm|#8207);'i","'&(lsquo|#8216);'i","'&(rsquo|#8217);'i","'&(sbquo|#8218);'i","'&(ldquo|#8220);'i","'&(rdquo|#8221);'i","'&(bdquo|#8222);'i","'&(dagger|#8224);'i","'&(Dagger|#8225);'i","'&(bull|#8226);'i","'&(hellip|#8230);'i","'&(permil|#8240);'i","'&(prime|#8242);'i","'&(Prime|#8243);'i","'&(lsaquo|#8249);'i","'&(rsaquo|#8250);'i","'&(oline|#8254);'i","'&(frasl|#8260);'i","'&(euro|#8364);'i","'&(image|#8465);'i","'&(weierp|#8472);'i","'&(real|#8476);'i","'&(trade|#8482);'i","'&(alefsym|#8501);'i","'&(larr|#8592);'i","'&(uarr|#8593);'i","'&(rarr|#8594);'i","'&(darr|#8595);'i","'&(harr|#8596);'i","'&(crarr|#8629);'i","'&(lArr|#8656);'i","'&(uArr|#8657);'i","'&(rArr|#8658);'i","'&(dArr|#8659);'i","'&(hArr|#8660);'i","'&(forall|#8704);'i","'&(part|#8706);'i","'&(exist|#8707);'i","'&(empty|#8709);'i","'&(nabla|#8711);'i","'&(isin|#8712);'i","'&(notin|#8713);'i","'&(ni|#8715);'i","'&(prod|#8719);'i","'&(sum|#8721);'i","'&(minus|#8722);'i","'&(lowast|#8727);'i","'&(radic|#8730);'i","'&(prop|#8733);'i","'&(infin|#8734);'i","'&(ang|#8736);'i","'&(and|#8743);'i","'&(or|#8744);'i","'&(cap|#8745);'i","'&(cup|#8746);'i","'&(int|#8747);'i","'&(there4|#8756);'i","'&(sim|#8764);'i","'&(cong|#8773);'i","'&(asymp|#8776);'i","'&(ne|#8800);'i","'&(equiv|#8801);'i","'&(le|#8804);'i","'&(ge|#8805);'i","'&(sub|#8834);'i","'&(sup|#8835);'i","'&(nsub|#8836);'i","'&(sube|#8838);'i","'&(supe|#8839);'i","'&(oplus|#8853);'i","'&(otimes|#8855);'i","'&(perp|#8869);'i","'&(sdot|#8901);'i","'&(lceil|#8968);'i","'&(rceil|#8969);'i","'&(lfloor|#8970);'i","'&(rfloor|#8971);'i","'&(lang|#9001);'i","'&(rang|#9002);'i","'&(loz|#9674);'i","'&(spades|#9824);'i","'&(clubs|#9827);'i","'&(hearts|#9829);'i","'&(diams|#9830);'i","'&(copy|#169);'i","'&(reg|#174);'i","'&(pound|#163);'i","'&(laquo|#171);'i","'&(raquo|#187);'i","'&(sect|#167);'i","!\s+!");

      $replace = array ("d"," ","_","-","-","","","","","","","","","","","","","","","","","","","","","","","","","","","","","","","","","","","","","","","","","","","","","","","","","","","","","","","","","","","","","","","","","","","","","","","","","","","","","","","","","","","","","","","","","","","","","","","","","","","","","","","","","","","","","","","","","","","","","","","","","","","","","","","","","","","","","","","","","","","","","","","","","","","","","","","","","","","","","","","","","","","","","","","","","","","","","","","","","","","","",""," ");

      return trim(strtr(preg_replace($search, $replace, $str), array("\"" => "", "'" => "","<" => "", ">" => "", "&" => "", " ," => ",")));
  }

  /**
   * replace newline symbols to &lt;br /&gt;
   * @param mixed data for action
   * @param array which elements test
   * @return mixed
   */
  function xNl2Br($_Data, $_Key = array())
  {


      if (!is_array($_Data))
      {

          return nl2br($_Data);
      }

      if (!is_array($_Key)) $_Key = array($_Key);
      foreach ($_Data as $__Key => $__Data)
      {

          if (count($_Key) && !is_array($__Data))
          {

              if (in_array($__Key, $_Key))
              {

                  $_Data[$__Key] = xNl2Br($__Data, $_Key);
              }
          }
          else  $_Data[$__Key] = xNl2Br($__Data, $_Key);

      }
      return $_Data;
  }

  function xStrReplace($_Search, $_Replace, $_Data, $_Key = array())
  {

      if (!is_array($_Data))
      {

          return str_replace($_Search, $_Replace, $_Data);
      }

      if (!is_array($_Key)) $_Key = array($_Key);
      foreach ($_Data as $__Key => $__Data)
      {

          if (count($_Key) && !is_array($__Data))
          {

              if (in_array($__Key, $_Key))
              {

                  $_Data[$__Key] = xStrReplace($_Search, $_Replace, $__Data, $_Key);
              }
          }
          else  $_Data[$__Key] = xStrReplace($_Search, $_Replace, $__Data, $_Key);

      }
      return $_Data;
  }

  function xHtmlSpecialCharsDecode($_Data, $_Params = array(), $_Key = array())
  {


      if (!is_array($_Data))
      {
		  return html_entity_decode($_Data, ENT_QUOTES);
      }

      if (!is_array($_Key)) $_Key = array($_Key);
      foreach ($_Data as $__Key => $__Data)
      {

          if (count($_Key) && !is_array($__Data))
          {

              if (in_array($__Key, $_Key))
              {

                  $_Data[$__Key] = xHtmlSpecialCharsDecode($__Data, $_Params, $_Key);
              }
          }
          else  $_Data[$__Key] = xHtmlSpecialCharsDecode($__Data, $_Params, $_Key);

      }
      return $_Data;
  }
  
  function xHtmlSpecialChars($_Data, $_Params = array(), $_Key = array())
  {


      if (!is_array($_Data))
      {

          return htmlspecialchars($_Data, ENT_QUOTES);
      }

      if (!is_array($_Key)) $_Key = array($_Key);
      foreach ($_Data as $__Key => $__Data)
      {

          if (count($_Key) && !is_array($__Data))
          {

              if (in_array($__Key, $_Key))
              {

                  $_Data[$__Key] = xHtmlSpecialChars($__Data, $_Params, $_Key);
              }
          }
          else  $_Data[$__Key] = xHtmlSpecialChars($__Data, $_Params, $_Key);

      }
      return $_Data;
  }

  function xEscSQL($_Data, $_Params = array(), $_Key = array())
  {

      if (!is_array($_Data))
      {

          return mysql_real_escape_string($_Data);
      }

      if (!is_array($_Key)) $_Key = array($_Key);
      foreach ($_Data as $__Key => $__Data)
      {

          if (count($_Key) && !is_array($__Data))
          {

              if (in_array($__Key, $_Key))
              {

                  $_Data[$__Key] = xEscSQL($__Data, $_Params, $_Key);
              }
          }
          else  $_Data[$__Key] = xEscSQL($__Data, $_Params, $_Key);

      }
      return $_Data;
  }

  function xEscapeSQLstring ( $_Data, $_Params = array(), $_Key = array() ){
      return xEscSQL($_Data, $_Params, $_Key);
  }

  function xSaveData($_ID, $_Data, $_TimeControl = 0)
  {

      if (!session_is_registered('_xSAVE_DATA'))
      {

          session_register('_xSAVE_DATA');
          $_SESSION['_xSAVE_DATA'] = array();
      }

      if (intval($_TimeControl))
      {

          $_SESSION['_xSAVE_DATA'][$_ID] = array($_ID.'_DATA' => $_Data, $_ID.'_TIME_CTRL' => array('timetag' =>
              time(), 'timelimit' => $_TimeControl, ), );
      }
      else
      {
          $_SESSION['_xSAVE_DATA'][$_ID] = $_Data;
      }
  }

  function xPopData($_ID)
  {

      if (!isset($_SESSION['_xSAVE_DATA'][$_ID]))
      {
          return null;
      }

      if (is_array($_SESSION['_xSAVE_DATA'][$_ID]))
      {

          if (isset($_SESSION['_xSAVE_DATA'][$_ID][$_ID.'_TIME_CTRL']))
          {

              if (($_SESSION['_xSAVE_DATA'][$_ID][$_ID.'_TIME_CTRL']['timetag'] + $_SESSION['_xSAVE_DATA'][$_ID][$_ID.
                  '_TIME_CTRL']['timelimit']) < time())
              {
                  return null;
              }
              else
              {

                  $Return = $_SESSION['_xSAVE_DATA'][$_ID][$_ID.'_DATA'];
                  unset($_SESSION['_xSAVE_DATA'][$_ID]);
                  return $Return;
              }
          }
      }

      $Return = $_SESSION['_xSAVE_DATA'][$_ID];
      unset($_SESSION['_xSAVE_DATA'][$_ID]);
      return $Return;
  }

  function xDataExists($_ID)
  {

      if (!isset($_SESSION['_xSAVE_DATA'][$_ID])) return 0;

      if (is_array($_SESSION['_xSAVE_DATA'][$_ID]))
      {

          if (isset($_SESSION['_xSAVE_DATA'][$_ID][$_ID.'_TIME_CTRL']))
          {

              if (($_SESSION['_xSAVE_DATA'][$_ID][$_ID.'_TIME_CTRL']['timetag'] + $_SESSION['_xSAVE_DATA'][$_ID][$_ID.
                  '_TIME_CTRL']['timelimit']) >= time())
              {
                  return 1;
              }
              else
              {
                  return 0;
              }
          }
          else
          {
              return 1;
          }
      }
      else
      {
          return 1;
      }
  }


  function xGetData($_ID)
  {

      if (!isset($_SESSION['_xSAVE_DATA'][$_ID]))
      {
          return null;
      }

      if (is_array($_SESSION['_xSAVE_DATA'][$_ID]))
      {

          if (isset($_SESSION['_xSAVE_DATA'][$_ID][$_ID.'_TIME_CTRL']))
          {

              if (($_SESSION['_xSAVE_DATA'][$_ID][$_ID.'_TIME_CTRL']['timetag'] + $_SESSION['_xSAVE_DATA'][$_ID][$_ID.
                  '_TIME_CTRL']['timelimit']) < time())
              {
                  return null;
              }
              else
              {

                  $Return = $_SESSION['_xSAVE_DATA'][$_ID][$_ID.'_DATA'];
                  return $Return;
              }
          }
      }

      $Return = $_SESSION['_xSAVE_DATA'][$_ID];
      return $Return;
  }

  function generateRndCode($_RndLength, $_RndCodes = 'qwertyuiopasdfghjklzxcvbnm0123456789')
  {

      $l_name = '';
      $top = strlen($_RndCodes) - 1;
      srand((double)microtime() * 1000000);
      for ($j = 0; $j < $_RndLength; $j++) $l_name .= $_RndCodes{rand(0, $top)};
      return $l_name;
  }
?><?php
/**
 * add new link category and return new category id
 *
 * @param array $_category
 * @return integer
 */
function le_addCategory($_category){

        if(empty($_category['le_cName']))return false;
        $sql = "select le_cID FROM ".LINK_EXCHANGE_CATEGORIES_TABLE." WHERE le_cName='".xToText($_category['le_cName'])."'";
        list($_le_cID) = db_fetch_row(db_query($sql));
        if(!empty($_le_cID)) return false;

        $sql = "INSERT INTO ".LINK_EXCHANGE_CATEGORIES_TABLE."
                (le_cName)
                VALUES('".xToText($_category['le_cName'])."')";

        db_query($sql);
        return db_insert_id();
}

/**
 * save links category
 *
 * @param array $_category
 * @return bool
 */
function le_saveCategory($_category){

        if(empty($_category['le_cName']))return false;
        $sql = "select le_cID FROM ".LINK_EXCHANGE_CATEGORIES_TABLE." WHERE le_cName='".xToText($_category['le_cName'])."'";
        list($_le_cID) = db_fetch_row(db_query($sql));
        if(!empty($_le_cID)) return false;

        $sql = "UPDATE ".LINK_EXCHANGE_CATEGORIES_TABLE."
                SET le_cName = '".xToText($_category['le_cName'])."'
                WHERE le_cID  = ".(int)$_category['le_cID'];
        db_query($sql);
        return true;
}

/**
 * delete links category
 *
 * @param integer links category id
 * @return bool
 */
function le_deleteCategory($_le_cID){

        $sql = "DELETE FROM ".LINK_EXCHANGE_CATEGORIES_TABLE." WHERE le_cID=".(int)$_le_cID;
        db_query($sql);
        return true;
}

/**
 * return array of categories by requested params
 *
 * @return array
 */
function le_getCategories($_where = '1', $_what = 'le_cID, le_cName, le_cSortOrder', $_order = "le_cSortOrder ASC, le_cName ASC"){

        $categories = array();
        if(is_array($_where)){

                foreach ($_where as $_col=>$_val)  $_where[$_col] = $_col." = '".$_val."'";
                $_where = implode(" AND ", $_where);
        }
        if(is_array($_what)) $_what = implode(", ", xEscSQL($_what));
         else $_what = xEscSQL($_what);
        $sql = "select ".$_what." FROM ".LINK_EXCHANGE_CATEGORIES_TABLE."
                WHERE ".$_where." ORDER BY ".xEscSQL($_order);
        $result = db_query($sql);
        while ($_row = db_fetch_row($result)) $categories[] = $_row;
        return $categories;
}

/**
 * return array of links by requested params
 *
 * @return array
 */
function le_getLinks($_offset = 0, $_lpp = '20', $_where = '1', $_what = 'le_lID, le_lText, le_lURL, le_lCategoryID, le_lVerified', $_order = '`le_lURL` ASC'){

        $_offset = ($_offset-1)*$_lpp;
        $links = array();
        if(is_array($_where)){

                foreach ($_where as $_col=>$_val)
                        $_where[$_col] = "`".$_col."` = '".$_val."'";
                $_where = implode(" AND ", $_where);
        }
        if(is_array($_what))
                $_what = "`".implode("`, `", $_what)."`";
        $sql = "
                SELECT {$_what} FROM ".LINK_EXCHANGE_LINKS_TABLE."
                WHERE {$_where}
                ORDER BY {$_order}
        ";
        $result = db_query($sql);
        $i = 0;
        while($_row = db_fetch_row($result))
                if(($_offset+$_lpp)>$i&&$_offset<=$i++){

                        if(isset($_row['le_lVerified'])){

                                $_row['le_lVerified'] = format_datetime($_row['le_lVerified']);
                        }
                        $links[] = $_row;
                }
        return $links;
}

/**
 * return number of links by requested params
 *
 * @return integer
 */
function le_getLinksNumber($_where = '1'){

        if(is_array($_where)){

                foreach ($_where as $_col=>$_val)
                        $_where[$_col] = $_col." = '".$_val."'";
                $_where = implode(" AND ", $_where);
        }
        $sql = "select COUNT(*) FROM ".LINK_EXCHANGE_LINKS_TABLE." WHERE ".$_where;
        $result = db_query($sql);
        list($links_number) = db_fetch_row($result);
        return $links_number;
}

/**
 * add new link to category and return new link id
 *
 * @return integer
 */
function le_addLink($_link){

           $sql = "select le_lID FROM ".LINK_EXCHANGE_LINKS_TABLE."
                WHERE le_lURL='".$_link['le_lURL']."'";
        list($_le_lID) = db_fetch_row(db_query($sql));
        if(!empty($_le_lID))return false;

        $sql = "INSERT INTO ".LINK_EXCHANGE_LINKS_TABLE."
                (".implode(", ", (array_keys($_link))).")
                VALUES('".implode("', '", $_link)."')";
        db_query($sql);
        return db_insert_id();
}

/**
 * update link
 *
 * @param array of new values
 * @return bool
 */
function le_SaveLink($_link){

        if(key_exists('le_lURL', $_link)){
                $sql = "select le_lID FROM ".LINK_EXCHANGE_LINKS_TABLE."
                        WHERE le_lURL='".$_link['le_lURL']."' AND le_lID!=".(int)$_link['le_lID'];
                list($_le_lID) = db_fetch_row(db_query($sql));
                if($_le_lID) return false;
                $_le_lID = $_link['le_lID'];
        }
        else $_le_lID = $_link['le_lID'];

        foreach($_link as $_col => $_val){

                if($_val == 'NULL' && $_col=='le_lVerified'){

                        $_link[$_col] = $_col." = NULL";
                }else{

                        $_link[$_col] = $_col." = '".$_val."'";
                }
        }

        $sql = "UPDATE ".LINK_EXCHANGE_LINKS_TABLE."
                SET ".implode(", ", $_link)."
                WHERE le_lID=".(int)$_le_lID;
        db_query($sql);
        return true;
}

function le_DeleteLink($_le_lID){

        $sql = "DELETE FROM ".LINK_EXCHANGE_LINKS_TABLE." WHERE le_lID=".(int)$_le_lID;
        db_query($sql);
}
?>
<?php
#####################################
# ShopCMS: Скрипт интернет-магазина
# Copyright (c) by ADGroup
# http://shopcms.ru
#####################################


function modGetModules( $moduleFiles )
{
        $modules        = array();
        foreach( $moduleFiles as $fileName )
        {
                $className        = GetClassName( $fileName );
                if(!$className) continue;
                eval( "\$objectModule = new ".$className."();" );
                if ( $objectModule->is_installed() )
                        $modules[] = $objectModule;
        }
        return $modules;
}

function modGetModuleObjects( $moduleFiles )
{
        $modules        = array();
        foreach( $moduleFiles as $fileName )
        {
                $className        = GetClassName( $fileName );
                if(!$className) continue;
                eval( "\$objectModule = new ".$className."();" );
                $modules[] = $objectModule;
        }
        return $modules;
}

function modGetModuleConfigs($_ModuleClassName){

        $ModuleConfigs = array();

        $sql = "select * FROM ".MODULES_TABLE." WHERE ModuleClassName='".xEscSQL($_ModuleClassName)."' ORDER BY module_name ASC
        ";
        $Result = db_query($sql);
        while ($_Row = db_fetch_row($Result)) {

                $ModuleConfigs[] = array(
                        'ConfigID'                 => $_Row['module_id'],
                        'ConfigName'         => $_Row['module_name'],
                        'ConfigClass'         => $_ModuleClassName,
                        );
        }

        return $ModuleConfigs;
}

function modGetModuleConfig($_ConfigID){

        $sql = "select * FROM ".MODULES_TABLE." WHERE module_id=".(int)$_ConfigID;
        return db_fetch_row(db_query($sql));
}

function modUninstallModuleConfig($_ConfigID){

        $ModuleConfig = modGetModuleConfig($_ConfigID);
        eval('$_tClass = new '.$ModuleConfig['ModuleClassName'].'();');
        $_tClass->uninstall($ModuleConfig['module_id']);
}

function modGetAllInstalledModuleObjs($_ModuleType = 0){

        $ModuleObjs = array();
        $sql = 'select module_id FROM '.MODULES_TABLE.' ORDER BY module_name ASC, module_id ASC';
        $Result = db_query($sql);
        while ($_Row = db_fetch_row($Result)) {

                $_TObj = modGetModuleObj($_Row['module_id'], $_ModuleType);
                if($_TObj && $_TObj->get_id() && $_TObj->is_installed())        $ModuleObjs[] = $_TObj;
        }
        return $ModuleObjs;
}

function modGetModuleObj($_ID, $_ModuleType = 0){

        $ModuleConfig = modGetModuleConfig($_ID);
        $objectModule = null;

        if(!$_ID) return $objectModule;

        if ($ModuleConfig['ModuleClassName']) {

                if(class_exists($ModuleConfig['ModuleClassName'])){

                        eval('$objectModule = new '.$ModuleConfig['ModuleClassName'].'('.$_ID.');');
                        if($_ModuleType && $objectModule->getModuleType()!=$_ModuleType)
                                $objectModule = null;
                }else{

                        $moduleFiles = array();
                        $IncludeDir = '';
                        switch ($_ModuleType){

                                case SHIPPING_RATE_MODULE:
                                        $IncludeDir = "core/modules/shipping";
                                        break;
                                case PAYMENT_MODULE:
                                        $IncludeDir = "core/modules/payment";
                                        break;
                                case SMSMAIL_MODULE:
                                        $IncludeDir = "core/modules/smsmail";
                                        break;
                        }
                        $moduleFiles = GetFilesInDirectory( $IncludeDir, "php" );

                        foreach( $moduleFiles as $fileName )
                        {
                                $className = GetClassName( $fileName );
                                if(strtolower($className) != strtolower($ModuleConfig['ModuleClassName'])) continue;

                                require_once($fileName);
                                eval( '$objectModule = new '.$className.'('.$_ID.');' );
                                return $objectModule;
                        }
                }
        }else {

                $moduleFiles = array();
                switch ($_ModuleType){

                        case SHIPPING_RATE_MODULE:
                                $moduleFiles = GetFilesInDirectory( "core/modules/shipping", "php" );
                                break;
                        case PAYMENT_MODULE:
                                $moduleFiles = GetFilesInDirectory( "core/modules/payment", "php" );
                                break;
                        case SMSMAIL_MODULE:
                                $IncludeDir = "core/modules/smsmail";
                                break;
                }

                foreach( $moduleFiles as $fileName )
                {
                        $className        = GetClassName( $fileName );
                        if(!$className) continue;
                        if(!class_exists($className))require_once($fileName);
                        eval( '$objectModule = new '.$className.'();' );

                        if ( $objectModule->get_id() == $_ID && $objectModule->title==$ModuleConfig['module_name'])
                                return $objectModule;
                        else $objectModule = null;
                }
        }
        return $objectModule;
}
?><?php
#####################################
# ShopCMS: Скрипт интернет-магазина
# Copyright (c) by ADGroup
# http://shopcms.ru
#####################################

function newsGetNewsToCustomer()
{
        $q = db_query( "select NID, add_date, title, textToPrePublication from ".NEWS_TABLE." order by add_date DESC LIMIT 0,".CONF_NEWS_COUNT_IN_CUSTOMER_PART);
        $data = array();

        while( $r=db_fetch_row($q) )
        {
               $r["add_date"]=dtConvertToStandartForm($r["add_date"]);
               $data[] = $r;
        }
        return $data;
}

function newsGetPreNewsToCustomer()
{
        $q = db_query( "select NID, add_date, title, textToPrePublication from ".NEWS_TABLE." order by add_date DESC LIMIT 0,".CONF_NEWS_COUNT_IN_NEWS_PAGE);
        $data = array();

        while( $r=db_fetch_row($q) )
        {
               $r["add_date"]=dtConvertToStandartForm($r["add_date"]);
               $data[] = $r;
        }
        return $data;
}


function newsGetFullNewsToCustomer($newsid)
{
        $q = db_query( "select add_date, title, textToPrePublication, textToPublication from ".NEWS_TABLE." where NID=".(int)$newsid);
        if  ( $r = db_fetch_row($q) )
        {
        $r["add_date"]=dtConvertToStandartForm($r["add_date"]);
        $r["NID"] = (int)$newsid;
		}
        return $r;
}

function newsGetNewsToEdit($newsid)
{
        $q = db_query( "select add_date, title, textToPrePublication, textToPublication, textToMail from ".NEWS_TABLE." where NID=".(int)$newsid);
        $r=db_fetch_row($q);
        $r["add_date"]=dtConvertToStandartForm($r["add_date"]);
        return $r;
}

function newsGetAllNews( $callBackParam, &$count_row, $navigatorParams = null )
{
        if ( $navigatorParams != null )
        {
                $offset                        = $navigatorParams["offset"];
                $CountRowOnPage        = $navigatorParams["CountRowOnPage"];
        }
        else
        {
                $offset = 0;
                $CountRowOnPage = 0;
        }

        $q = db_query( "select NID, add_date, title from ".NEWS_TABLE." order by add_stamp DESC" );

        $i = 0;
        $data = array();
        while( $r=db_fetch_row($q) )
        {
                if ( ($i >= $offset && $i < $offset + $CountRowOnPage) ||
                                $navigatorParams == null  )
                {
                   $r["add_date"]=dtConvertToStandartForm($r["add_date"]);
                   $data[] = $r;
                }
                $i++;
        }
        $count_row = $i;
        return $data;
}

function newsAddNews( $add_date, $title, $textToPrePublication, $textToPublication, $textToMail )
{
        $stamp = microtime();
        $stamp = explode(" ", $stamp);
        $stamp = $stamp[1];
        db_query( "insert into ".NEWS_TABLE." ( add_date, title, textToPrePublication, textToPublication, textToMail, add_stamp ) ".
                  " values( '".xEscSQL(dtDateConvert($add_date))."', '".xToText(trim($title))."', '".xEscSQL($textToPrePublication)."', '".xEscSQL($textToPublication)."', '".xEscSQL($textToMail)."', ".$stamp." ) ");
        return db_insert_id();
}

function newsUpdateNews( $add_date, $title, $textToPrePublication, $textToPublication, $textToMail, $id_news )
{
                db_query("update ".NEWS_TABLE.
                 " set     add_date='".xEscSQL(dtDateConvert($add_date))."', ".
                 "         title='".xToText($title)."', ".
                 "         textToPrePublication='".xEscSQL($textToPrePublication)."', ".
                 "         textToPublication='".xEscSQL($textToPublication)."', ".
                 "         textToMail='".xEscSQL($textToMail)."' ".
                 " where NID = ".(int)$id_news);
}

function newsDeleteNews( $newsid )
{
        db_query( "delete from ".NEWS_TABLE." where NID=".(int)$newsid );
}

function newsSendNews($newsid)
{
        $q = db_query( "select add_date, title, textToMail from ".NEWS_TABLE." where NID=".(int)$newsid );
        $news = db_fetch_row( $q );
        $news["add_date"]=dtConvertToStandartForm($news["add_date"]);
        $q = db_query( "select Email from ".MAILING_LIST_TABLE );
        while( $subscriber = db_fetch_row($q) ) xMailTxtHTMLDATA($subscriber["Email"], EMAIL_NEWS_OF." - ".CONF_SHOP_NAME, $news["title"]."<br><br>".$news["textToMail"]);
}

?><?php
#####################################
# ShopCMS: Скрипт интернет-магазина
# Copyright (c) by ADGroup
# http://shopcms.ru
#####################################



// *****************************************************************************
// Purpose        gets all options
// Inputs   nothing
// Remarks
// Returns        array of item
//                                        "optionID"
//                                        "name"
//                                        "sort_order"
//                                        "count_variants"
function optGetOptions(){

        # BEGIN ExtraFilter
#$SQL = 'select ps.optionID, ps.name, ps.sort_order, COUNT(povv.variantID) as count_variants FROM '.PRODUCT_OPTIONS_TABLE.' as ps
$SQL = 'select ps.optionID, ps.name, ps.sort_order, COUNT(povv.variantID) as count_variants, ps.filter1, ps.filter2, ps.filter3, ps.filter_type FROM '.PRODUCT_OPTIONS_TABLE.' as ps
                LEFT JOIN '.PRODUCTS_OPTIONS_VALUES_VARIANTS_TABLE.' as povv ON ps.optionID = povv.optionID GROUP BY ps.optionID ORDER BY sort_order, name
        ';
        $q = db_query($SQL);
        $result=array();

        while( $row=db_fetch_row($q) ) $result[] = $row;
        return $result;
}

function optGetOptionscat($categoryID){

        $SQL = 'select ps.optionID, ps.name, ps.sort_order, COUNT(povv.variantID) as count_variants FROM '.PRODUCT_OPTIONS_TABLE.' as ps
                LEFT JOIN '.PRODUCTS_OPTIONS_VALUES_VARIANTS_TABLE.' as povv ON (ps.optionID = povv.optionID) LEFT JOIN '.CATEGORY_PRODUCT_OPTIONS_TABLE.' as j on (ps.optionID = j.optionID) where j.categoryID='.(int)$categoryID.' GROUP BY ps.optionID ORDER BY sort_order, name
        ';
        $q = db_query($SQL);
        $result=array();

        while( $row=db_fetch_row($q) ) $result[] = $row;
        return $result;
}
// *****************************************************************************
// Purpose        gets all options
// Inputs   $optionID - option ID
// Remarks
// Returns        array of item
//                                        "optionID"
//                                        "name"
//                                        "sort_order"
//                                        "count_variants"
function optGetOptionById($optionID)
{
        $q = db_query("select optionID, name, sort_order from ".
                                PRODUCT_OPTIONS_TABLE." where optionID=".(int)$optionID);
        if ( $row=db_fetch_row($q) ) return $row;
        else return null;
}


// *****************************************************************************
// Purpose        gets all options
// Inputs   array of item
//                                each item consits of
//                                        "extra_option"                        - option name
//                                        "extra_sort"                        - enlarged picture
//                                key is option ID
// Remarks
// Returns        nothig
function optUpdateOptions($updateOptions)
{
        foreach($updateOptions as $key => $val)
        {
                if (isset($val["extra_option"]) && $val["extra_option"]!="")
                {
                        db_query("update ".PRODUCT_OPTIONS_TABLE." set name='".xToText(trim($val["extra_option"])).
                               # BEGIN ExtraFilter
#"', sort_order=".(int)$val["extra_sort"]." where optionID=".(int)$key);
"', sort_order=".(int)$val["extra_sort"].
", filter1=".(isset($val["extra_filter1"])?1:0).
", filter2=".(isset($val["extra_filter2"])?1:0).
", filter3=".(isset($val["extra_filter3"])?1:0).
", filter_type=".(isset($val["extra_type"])?$val["extra_type"]:0).
" where optionID=".(int)$key);
# END ExtraFilter
                }
        }
}


// *****************************************************************************
// Purpose        adds new option
// Inputs
//                                $extra_option        - option name
//                                $extra_sort                - sort order
// Remarks
// Returns        nothig
function optAddOption($extra_option, $extra_sort)
{
        if ( trim($extra_option) == "" ) return;
        db_query("insert into ".PRODUCT_OPTIONS_TABLE.
                        " (name, sort_order) values ('".xToText($extra_option)."', '".(int)$extra_sort."')");
}


// *****************************************************************************
// Purpose        get option values
// Inputs
// Remarks
// Returns
function optGetOptionValues($optionID)
{
        $q = db_query("select variantID, optionID, option_value, sort_order from ".
                                PRODUCTS_OPTIONS_VALUES_VARIANTS_TABLE.
                                " where optionID=".(int)$optionID.
                                " order by sort_order, option_value");
        $result=array();
        while($row=db_fetch_row($q)) $result[] = $row;
        return $result;
}

// *****************************************************************************
// Purpose        get option values
// Inputs
// Remarks
// Returns
function optOptionValueExists($optionID, $value_name)
{
        $q = db_query("select variantID from ".
                                PRODUCTS_OPTIONS_VALUES_VARIANTS_TABLE.
                                " where optionID=".(int)$optionID." and option_value='".xEscSQL(trim($value_name))."';");
        $row = db_fetch_row($q);
        if ($row)
                return $row[0]; //return variant ID
        else
                return false;
}

// *****************************************************************************
// Purpose        updates option values
// Inputs   array of item
//                                each item consits of
//                                        "option_value"                        - option name
//                                        "sort_order"                        - enlarged picture
//                                key is option ID
// Remarks
// Returns
function optUpdateOptionValues($updateOptions)
{
        foreach($updateOptions as $key => $value)
        {
                db_query("update ".PRODUCTS_OPTIONS_VALUES_VARIANTS_TABLE.
                         " set option_value='".xToText($value["option_value"])."', ".
                         " sort_order=".(int)$value["sort_order"]." ".
                         " where variantID=".(int)$key);
        }
}


// *****************************************************************************
// Purpose        updates option values
// Inputs
//                                $optionID        - option ID
//                                $value                - value
//                                $sort_order - sort order
// Remarks
// Returns
function optAddOptionValue($optionID, $value, $sort_order)
{
        db_query("insert into ".PRODUCTS_OPTIONS_VALUES_VARIANTS_TABLE.
                                        "(optionID, option_value, sort_order) ".
                                        "values('".(int)$optionID."', '".xToText($value)."', '".
                                                        (int)$sort_order."' )" );
        return db_insert_id();
}

?><?php
#####################################
# ShopCMS: Скрипт интернет-магазина
# Copyright (c) by ADGroup
# http://shopcms.ru
#####################################


// *****************************************************************************
// Purpose        get "clear" price as Sum( Price[i]*Quantity[i] )
// Inputs   $cartContent is result of cartGetCartContent function
// Remarks
// Returns        price in universal unit
function oaGetClearPrice( $cartContent )
{
        $res = 0;
        for( $i=0; $i<count($cartContent["cart_content"]); $i++ )
        {
                $cartItem = $cartContent["cart_content"][$i];
                $res += $cartItem["quantity"]*$cartItem["costUC"];
        }
        return $res;
}


// *****************************************************************************
// Purpose        get product tax in univesal unit
// Inputs
//                                $cartContent is result of cartGetCartContent function
//                                $d is discount in percent
//                                $addresses array of
//                                                $shippingAddressID,
//                                                $billingAddressID
//                                        OR
//                                                $shippingAddress - array of
//                                                        "countryID"
//                                                        "zoneID"
//                                                $billingAddress - array of
//                                                        "countryID"
//                                                        "zoneID"
// Remarks
// Returns
function oaGetProductTax( $cartContent, $d, $addresses )
{
        $res = 0;
        for( $i=0; $i<count($cartContent["cart_content"]); $i++ )
        {
                $cartItem = $cartContent["cart_content"][$i];
                $q = db_query( "select count(*) from ".PRODUCTS_TABLE.
                        " where productID=".(int)$cartItem["productID"] );
                $count = db_fetch_row($q);
                if ( $count[0] == 0 )
                        continue;

                $cartItem = $cartContent["cart_content"][$i];
                $price = $cartItem["costUC"] - ($cartItem["costUC"]/100)*$d;
                $price = $price*$cartItem["quantity"];
                if ( is_array($addresses[0]) )
                        $tax = taxCalculateTax2( $cartItem["productID"],
                                $addresses[0], $addresses[1] );
                else
                        $tax = taxCalculateTax( $cartItem["productID"],
                                $addresses[0], $addresses[1] );
                $res += $tax;
        }
        return $res;
}

// *****************************************************************************
// Purpose        get product tax in univesal unit
// Inputs
//                                $cartContent is result of cartGetCartContent function
// Remarks
// Returns
function oaGetShippingCostTakingIntoTax( $cartContent, $shippingMethodID, $addresses, $orderDetails, $CALC_TAX = TRUE, $shServiceID = 0, $shServiceFull = FALSE )
{
        $Rates = array();
        $SimpleFormat = false;

        $shipping_method        = shGetShippingMethodById( $shippingMethodID );

        if ( $shipping_method )
        {
                $shippingModule = modGetModuleObj($shipping_method["module_id"], SHIPPING_RATE_MODULE);

                if ( $shippingModule )
                {
                        //shipping address
                        if ( !is_array($addresses[0]) )
                        {
                                $shippingAddress        = regGetAddress( $addresses[0] );
                        }
                        else
                        {
                                $shippingAddress        = $addresses[0] ;
                        }

                        //order content
                        $order = array (
                                "first_name" => $orderDetails["first_name"],
                                "last_name" => $orderDetails["last_name"],
                                "email" => $orderDetails["email"],
                                "orderContent" => $cartContent,
                                "order_amount" => $orderDetails["order_amount"]
                        );

                        $Rates = $shippingModule->calculate_shipping_rate( $order, $shippingAddress,  $shServiceID );

                        if(!is_array($Rates)){

                                $Rates = array(array('name'=>'','rate'=>$Rates));
                        }
                }
        }

        if(!count($Rates))
        {
                $Rates[] = array('rate'=>'0','name'=>'');
        }

        foreach ($Rates as $_ind=>$_Rate)
                $Rates[$_ind]['rate'] += $cartContent["freight_cost"];

        if ($CALC_TAX)
        {
                if ( is_array($addresses[0]) )
                        $rate = taxCalculateTaxByClass2( CONF_CALCULATE_TAX_ON_SHIPPING, $addresses[0], $addresses[1] );
                else
                        $rate = taxCalculateTaxByClass( CONF_CALCULATE_TAX_ON_SHIPPING, $addresses[0], $addresses[1] );

                foreach ($Rates as $_ind=>$_Rate)
                        $Rates[$_ind]['rate'] += ($Rates[$_ind]['rate']/100)*$rate;
        }

        return $Rates;
}

// *****************************************************************************
// Purpose        get discount percent
// Inputs
//                                $cartContent is result of cartGetCartContent function
// Remarks
// Returns
function oaGetDiscountPercent( $cartContent, $log )
{
        $price = oaGetClearPrice( $cartContent );
        $res = dscCalculateDiscount( $price, $log  );
        return (float) $res["discount_percent"];
}

// *****************************************************************************
// Purpose        get order amount (with discount) excluding shipping rate
// Inputs
//                                $cartContent is result of cartGetCartContent function
//                                $addresses array of
//                                                $shippingAddressID,
//                                                $billingAddressID
//                                        OR
//                                                $shippingAddress - array of
//                                                        "countryID"
//                                                        "zoneID"
//                                                $billingAddress - array of
//                                                        "countryID"
//                                                        "zoneID"
// Remarks
// Returns
function oaGetOrderAmountExShippingRate( $cartContent, $addresses, $log, $CALC_TAX = TRUE )
{
        $clearPrice = oaGetClearPrice( $cartContent );
        $d = oaGetDiscountPercent( $cartContent, $log );
        $res = $clearPrice - ($clearPrice/100)*$d;
        if ($CALC_TAX)
        {
                $res += oaGetProductTax( $cartContent, $d, $addresses );
        }
        return $res;
}



// *****************************************************************************
// Purpose        get order amount
// Inputs
//                                $cartContent is result of cartGetCartContent function
//                                $addresses array of
//                                                $shippingAddressID,
//                                                $billingAddressID
//                                        OR
//                                                $shippingAddress - array of
//                                                        "countryID"
//                                                        "zoneID"
//                                                $billingAddress - array of
//                                                        "countryID"
//                                                        "zoneID"
// Remarks
// Returns
function oaGetOrderAmount( $cartContent, $addresses, $shippingMethodID, $log, $orderDetails, $CALC_TAX = TRUE, $shServiceID = 0 )
{
        $Rate = oaGetShippingCostTakingIntoTax( $cartContent, $shippingMethodID, $addresses, $orderDetails, $CALC_TAX, $shServiceID );
        $res = oaGetOrderAmountExShippingRate( $cartContent, $addresses, $log, $CALC_TAX ) + $Rate[0]['rate'];
        return $res;
}

?><?php
#####################################
# ShopCMS: Скрипт интернет-магазина
# Copyright (c) by ADGroup
# http://shopcms.ru
#####################################

function ordGetOrders( $callBackParam, &$count_row, $navigatorParams = null )
{
        global $selected_currency_details;

        if ( $navigatorParams != null )
        {
                $offset                        = $navigatorParams["offset"];
                $CountRowOnPage        = $navigatorParams["CountRowOnPage"];
        }
        else
        {
                $offset = 0;
                $CountRowOnPage = 0;
        }

        $where_clause = "";

        if ( isset($callBackParam["orderStatuses"]) )
        {
                foreach( $callBackParam["orderStatuses"] as $statusID )
                {
                        if ( $where_clause == "" )
                                $where_clause .= " statusID=".(int)$statusID;
                        else
                                $where_clause .= " OR statusID=".(int)$statusID;
                }

                if ( isset($callBackParam["customerID"]) )
                {
                        if ( $where_clause != "" )
                                $where_clause = " customerID=".(int)$callBackParam["customerID"].
                                                " AND ( ".$where_clause." ) ";
                        else
                                $where_clause = " customerID=".(int)$callBackParam["customerID"];
                }

                if ( $where_clause != "" )
                        $where_clause = " where ".$where_clause;
                else
                        $where_clause = " where statusID = -1 ";
        }
        else
        {
                if ( isset($callBackParam["customerID"]) )
                        $where_clause .= " customerID = ".(int)$callBackParam["customerID"];

                if ( isset($callBackParam["orderID"]) )
                {
                        if ( $where_clause != "" )
                                $where_clause .= " and orderID=".(int)$callBackParam["orderID"];
                        else
                                $where_clause .= " orderID=".(int)$callBackParam["orderID"];
                }

                if ( $where_clause != "" )
                        $where_clause = " where ".$where_clause;
                else
                        $where_clause = " where statusID = -1 ";
        }

        $order_by_clause = "";
        if ( isset($callBackParam["sort"]) )
        {
                $order_by_clause .= " order by ".xEscSQL($callBackParam["sort"])." ";
                if ( isset($callBackParam["direction"]) )
                {
                        if ( $callBackParam["direction"] == "ASC" )
                                $order_by_clause .= " ASC ";
                        else
                                $order_by_clause .= " DESC ";
                }
                else
                        $order_by_clause .= " ASC ";
        }else{
        $order_by_clause = " order by orderID DESC ";
        }

        $q = db_query( "select orderID, customerID, order_time, customer_ip, shipping_type, ".
                " payment_type, customers_comment, statusID, shipping_cost, order_amount, ".
                " order_discount, currency_code, currency_value, customer_email, ".
                " shipping_firstname, shipping_lastname, ".
                " shipping_country, shipping_state, shipping_city, ".
                " shipping_address, billing_firstname, billing_lastname, ".
                " billing_country, billing_state, billing_city, ".
                " billing_address, cc_number, cc_holdername, cc_expires, cc_cvv, shippingServiceInfo, currency_round ".
                " from ".ORDERS_TABLE." ".$where_clause." and statusID !=0 ".$order_by_clause );

        $res = array();
        $i = 0;
        $total_sum = 0;
        while( $row = db_fetch_row($q) )
        {
                if ( ($i >= $offset && $i < $offset + $CountRowOnPage) ||
                                $navigatorParams == null  )
                {
                        $row["OrderStatus"] = ostGetOrderStatusName( $row["statusID"] );
                        $total_sum += $row["order_amount"];
                        $row["order_amount"] = _formatPrice(roundf($row["currency_value"]*$row["order_amount"]),$row["currency_round"])." ".$row["currency_code"];

                        $q_orderContent = db_query( "select name, Price, Quantity, tax, load_counter, itemID from ".
                                       ORDERED_CARTS_TABLE." where orderID=".(int)$row["orderID"] );

                        $content = array();
                        while( $orderContentItem = db_fetch_row($q_orderContent) )
                        {
                                $productID = GetProductIdByItemId( $orderContentItem["itemID"] );
                                $product   = GetProduct( $productID );
                                if ( $product["eproduct_filename"] != null &&
                                     strlen($product["eproduct_filename"]) > 0 )
                                {
                                        if (  file_exists("core/files/".$product["eproduct_filename"])   )
                                        {
                                                        $orderContentItem["eproduct_filename"] = $product["eproduct_filename"];
                                                        $orderContentItem["file_size"] = (string) round(filesize("core/files/".$product["eproduct_filename"]) / 1048576, 3);

                                                        if ( isset($callBackParam["customerID"]) )
                                                        {
                                                                $custID = $callBackParam["customerID"];
                                                        }
                                                        else
                                                        {
                                                                $custID = -1;
                                                        }

                                                        $orderContentItem["getFileParam"] =
                                                                "orderID=".$row["orderID"]."&".
                                                                "productID=".$productID."&".
                                                                "customerID=".$custID;

                                                        //additional security for non authorized customers
                                                        if ($custID == -1)
                                                        {
                                                                $orderContentItem["getFileParam"] .= "&order_time=".base64_encode($row["order_time"]);
                                                        }

                                                        $orderContentItem["getFileParam"] = cryptFileParamCrypt(
                                                                                        $orderContentItem["getFileParam"], null );
                                                        $orderContentItem["load_counter_remainder"]                =
                                                                        $product["eproduct_download_times"] - $orderContentItem["load_counter"];

                                                        $currentDate        = dtGetParsedDateTime( get_current_time() );
                                                        $betweenDay                = _getDayBetweenDate(
                                                                        dtGetParsedDateTime( $row["order_time"] ),
                                                                        $currentDate );

                                                        $orderContentItem["day_count_remainder"]                =
                                                                        $product["eproduct_available_days"] - $betweenDay;
                                                        //if ( $orderContentItem["day_count_remainder"] < 0 )
                                                        //                $orderContentItem["day_count_remainder"] = 0;

                                        }
                                }

                                $content[] = $orderContentItem;
                        }

                        $row["content"] = $content;
                        $row["order_time"] = format_datetime( $row["order_time"] );
                        $res[] = $row;
                }

                $i++;
        }
        $count_row = $i;

        if ( isset($callBackParam["customerID"]) )
        {
                if ( count($res) > 0 )
                {
                        $q = db_query( "select CID from ".CUSTOMERS_TABLE.
                                " where customerID=".(int)$callBackParam["customerID"] );
                        $row = db_fetch_row($q);

                        if ( $row["CID"]!=null && $row["CID"]!="" )
                        {
                                        $q = db_query( "select currency_value, currency_iso_3, roundval from ".
                                                CURRENCY_TYPES_TABLE." where CID=".(int)$row["CID"] );
                                        $row = db_fetch_row($q);
                                        $res[0]["total_sum"] = _formatPrice(roundf($row["currency_value"]*$total_sum),$row["roundval"])." ".$row["currency_iso_3"];
                        }
                        else
                        {
                                        $res[0]["total_sum"] = _formatPrice(roundf($selected_currency_details["currency_value"]*$total_sum),$row["roundval"])." ".$selected_currency_details["currency_iso_3"];
                        }
                }
        }
        return $res;
}


function ordGetDistributionByStatuses( $log )
{
        $q = db_query( "select statusID, status_name, sort_order from ".
                ORDER_STATUES_TABLE." order by sort_order, status_name" );
        $data = array();
        while( $row = db_fetch_row( $q ) )
        {
                 $q1 = db_query( "select count(*) from ".ORDERS_TABLE.
                        " where statusID=".(int)$row["statusID"]." AND ".
                        " customerID=".(int)regGetIdByLogin($log));
                 $row1= db_fetch_row($q1);

                if ( $row["statusID"] == ostGetCanceledStatusId() )
                        $row["status_name"] = STRING_CANCELED_ORDER_STATUS;

                $item        = array( "status_name" => $row["status_name"],
                                        "count" => $row1[0] );
                $data[] = $item;
        }
        return $data;
}



function _moveSessionCartContentToOrderedCart( $orderID )
{
        $i=0;
        $sql = "DELETE FROM ".ORDERED_CARTS_TABLE." WHERE orderID=".(int)$orderID;
        db_query($sql);
        foreach( $_SESSION["gids"] as $productID )
        {
                if ( $productID == 0 ) {
                        $i++;
                        continue;
                        }

                $q = db_query( "select count(*) from ".PRODUCTS_TABLE.
                        " where productID=".(int)$productID );
                $row = db_fetch_row($q);
                if ( $row[0] == 0 ){
                        $i++;
                        continue;
                        }

                // create new item
                db_query( "insert into ".SHOPPING_CART_ITEMS_TABLE.
                        "(productID) values('".(int)$productID."')" );
                $itemID=db_insert_id();

                foreach( $_SESSION["configurations"][$i] as $vars )
                {
                        db_query("insert into ".
                                        SHOPPING_CART_ITEMS_CONTENT_TABLE."(itemID, variantID) ".
                                        "values( '".(int)$itemID."', '".(int)$vars."')" );
                }

                $q_product = db_query( "select name, Price, product_code from ".PRODUCTS_TABLE." where productID=".(int)$productID);
                $product = db_fetch_row( $q_product );

                $quantity = $_SESSION["counts"][$i];

                $variants = array();
                foreach( $_SESSION["configurations"][$i] as $vars ) $variants[] = $vars;

                $options = GetStrOptions( $variants );
                if ( $options != "" )
                        $productComplexName = $product["name"]."(".$options.")";
                else
                        $productComplexName = $product["name"];

                if ( strlen($product["product_code"]) > 0 )
                        $productComplexName = "[".$product["product_code"]."] ".$productComplexName;

                $price = GetPriceProductWithOption( $variants, $productID );
                $shippingAddress = array(
                                        "countryID" => $_SESSION["receiver_countryID"],
                                        "zoneID"        => $_SESSION["receiver_zoneID"]);
                                $billingAddress = array(
                                        "countryID" => $_SESSION["billing_countryID"],
                                        "zoneID"        => $_SESSION["billing_zoneID"]);
                $tax = taxCalculateTax2( $productID, $shippingAddress, $billingAddress );

                db_query( "insert into ".ORDERED_CARTS_TABLE." ( itemID, orderID, name, Price, Quantity, tax ) ".
                         "values( ".(int)$itemID.", ".(int)$orderID.", '".xEscSQL($productComplexName)."', '".xEscSQL($price)."', ".
                                (int)$quantity.", ".xEscSQL($tax)." ) " );

                $i++;
        }
        unset($_SESSION["gids"]);
        unset($_SESSION["counts"]);
        unset($_SESSION["configurations"]);
        session_unregister("gids"); //calling session_unregister() is required since unset() may not work on some systems
        session_unregister("counts");
        session_unregister("configurations");
}



function _quickOrderUnsetSession()
{
        unset( $_SESSION["first_name"] );
        unset( $_SESSION["last_name"] );
        unset( $_SESSION["email"] );

        unset( $_SESSION["billing_first_name"] );
        unset( $_SESSION["billing_last_name"] );
        unset( $_SESSION["billing_state"] );
        unset( $_SESSION["billing_city"] );
        unset( $_SESSION["billing_address"] );
        unset( $_SESSION["billing_countryID"] );
        unset( $_SESSION["billing_zoneID"] );

        unset( $_SESSION["receiver_first_name"] );
        unset( $_SESSION["receiver_last_name"] );
        unset( $_SESSION["receiver_state"] );
        unset( $_SESSION["receiver_city"] );
        unset( $_SESSION["receiver_address"] );
        unset( $_SESSION["receiver_countryID"] );
        unset( $_SESSION["receiver_zoneID"] );
}



function _getOrderById( $orderID )
{
        $sql = "select ".
                "        orderID, ".
                "        customerID, ".
                "        order_time, ".
                "        customer_ip, ".
                "        shipping_type, ".
                "        payment_type, ".
                "        customers_comment, ".
                "        statusID, ".
                "        shipping_cost, ".
                "        order_discount, ".
                "        order_amount, ".
                "        currency_code, ".
                "        currency_value, ".
                "        customer_firstname, ".
                "        customer_lastname, ".
                "        customer_email, ".
                "        shipping_firstname, ".
                "        shipping_lastname, ".
                "        shipping_country, ".
                "        shipping_state, ".
                "        shipping_city, ".
                "        shipping_address, ".
                "        billing_firstname, ".
                "        billing_lastname, ".
                "        billing_country, ".
                "        billing_state, ".
                "        billing_city, ".
                "        billing_address, ".
                "        cc_number, ".
                "        cc_holdername, ".
                "        cc_expires, ".
                "        cc_cvv, ". 
                "        shippingServiceInfo, ". 
                "        currency_round ".
                " from ".ORDERS_TABLE." where orderID=".(int)$orderID;
        $q = db_query( $sql );
        return db_fetch_row($q);
}

function _sendOrderNotifycationToCustomer( $orderID, &$smarty_mail, $email, $login,
                                 $payment_email_comments_text, $shipping_email_comments_text, $tax, $order_active_link )
{
        $order = _getOrderById( $orderID );
        $smarty_mail->assign( "customer_firstname", $order["customer_firstname"] );
        $smarty_mail->assign( "orderID", $order["orderID"] );
        $smarty_mail->assign( "discount", roundf($order["order_discount"]));
        $smarty_mail->assign( "shipping_type", $order["shipping_type"] );
        $smarty_mail->assign( "shipping_firstname", $order["shipping_firstname"] );
        $smarty_mail->assign( "shipping_lastname", $order["shipping_lastname"] );
        $smarty_mail->assign( "shipping_country", $order["shipping_country"] );
        $smarty_mail->assign( "shipping_state", $order["shipping_state"] );
        $smarty_mail->assign( "shipping_city", $order["shipping_city"] );
        $smarty_mail->assign( "shipping_address", $order["shipping_address"] );
        $smarty_mail->assign( "shipping_cost", _formatPrice(roundf($order["currency_value"]*$order["shipping_cost"]),$order["currency_round"])." ".$order["currency_code"] );
        $smarty_mail->assign( "order_active_link", $order_active_link );
        $smarty_mail->assign( "payment_type", $order["payment_type"] );
        $smarty_mail->assign( "billing_firstname", $order["billing_firstname"] );
        $smarty_mail->assign( "billing_lastname", $order["billing_lastname"] );
        $smarty_mail->assign( "billing_country", $order["billing_country"] );
        $smarty_mail->assign( "billing_state", $order["billing_state"] );
        $smarty_mail->assign( "billing_city", $order["billing_city"] );
        $smarty_mail->assign( "billing_address", $order["billing_address"] );
        $smarty_mail->assign( "order_amount", _formatPrice(roundf($order["currency_value"]*$order["order_amount"]),$order["currency_round"])." ".$order["currency_code"] );
        $smarty_mail->assign( "payment_comments", $payment_email_comments_text );
        $smarty_mail->assign( "shipping_comments", $shipping_email_comments_text );
        $smarty_mail->assign( "order_total_tax", _formatPrice(roundf($order["currency_value"]*$tax),$order["currency_round"])." ".$order["currency_code"] );
        $smarty_mail->assign( "shippingServiceInfo", $order["shippingServiceInfo"] );
        
		// clear cost ( without shipping, discount, tax )
        $q1 = db_query( "select Price, Quantity from ".ORDERED_CARTS_TABLE." where orderID=".(int)$orderID);
        $clear_total_price = 0;
        while( $row=db_fetch_row($q1) ) $clear_total_price += $row["Price"]*$row["Quantity"];
		$order_discount_ToShow = _formatPrice(roundf($order["currency_value"]*$clear_total_price*((100-$order["order_discount"])/100)),$order["currency_round"])." ".$order["currency_code"];
		$smarty_mail->assign( "order_discount_ToShow", $order_discount_ToShow);
        
		//additional reg fields
        $addregfields = GetRegFieldsValuesByOrderID( $orderID );
        $smarty_mail->assign("customer_add_fields", $addregfields);

        $content = ordGetOrderContent( $orderID );
        for( $i=0; $i<count($content); $i++ )
        {
                $productID = GetProductIdByItemId( $content[$i]["itemID"] );
                if ( $productID == null || trim($productID) == "" )
                        continue;
                $q = db_query( "select  name, product_code, eproduct_filename, ".
                        " eproduct_available_days, eproduct_download_times from ".PRODUCTS_TABLE.
                        " where productID=".(int)$productID );
                $product = db_fetch_row($q);
                $content[$i]["product_code"] = $product["product_code"];

                $variants = GetConfigurationByItemId( $content[$i]["itemID"] );
                $options  = GetStrOptions( $variants );
                if ( $options != "" )
                        $content[$i]["name"] = $product["name"]."(".$options.")";
                else
                        $content[$i]["name"] = $product["name"];

                if ( strlen($product["eproduct_filename"])>0 && file_exists("core/files/".$product["eproduct_filename"]) )
                {
                        if ($login != null)
                                $customerID = regGetIdByLogin( $login );
                        else
                                $customerID = -1;
                        $content[$i]["eproduct_filename"]       = $product["eproduct_filename"];
                        $content[$i]["eproduct_available_days"] = $product["eproduct_available_days"];
                        $content[$i]["eproduct_download_times"] = $product["eproduct_download_times"];
                        $content[$i]["file_size"]               = (string) round(filesize("core/files/".$product["eproduct_filename"]) / 1048576, 3);

                        $content[$i]["getFileParam"]            =
                                                                                "orderID=".$order["orderID"]."&".
                                                                                "productID=".$productID."&".
                                                                                "customerID=".$customerID;
                        //additional security for non authorized customers
                        if ($customerID == -1)
                        {
                                $content[$i]["getFileParam"] .= "&order_time=".base64_encode($order["order_time"]);
                        }

                        $content[$i]["getFileParam"] =
                                cryptFileParamCrypt( $content[$i]["getFileParam"], null );
                }
        }

        $smarty_mail->assign( "content", $content );
        $html = $smarty_mail->fetch( "customer_order_notification.tpl.html" );

        if (CONF_ACTIVE_ORDER){

        $html_active = $smarty_mail->fetch( "customer_order_activate.tpl.html" );
        xMailTxtHTMLDATA($order["customer_email"],STRING_ORDER_ACTIVATE." #".$orderID." - ".CONF_SHOP_NAME, $html_active);

        }else{

        if (CONF_EMAIL_ORDER_SEND) xMailTxtHTMLDATA($email, STRING_ORDER." #".$orderID." - ".CONF_SHOP_NAME, $html);

        }



}

function activate_order($actlink,&$smarty_mail)
{
    $q = db_query("select orderID, statusID FROM ".ORDERS_TABLE." WHERE custlink='".xEscSQL($actlink)."' ");
    if ($res = db_fetch_row($q)){
    if($res["statusID"] == 0){
    $order = _getOrderById( $res["orderID"]);
    ostSetOrderStatusToOrder( $res["orderID"], ostGetNewOrderStatus(), '', '' );
    $smarty_mail->assign( "orderID", $res["orderID"] );
    $smarty_mail->assign( "polidesk", ADMIN_SEND_INACT_DESK2);
    $html = $smarty_mail->fetch( "active_deactive_order.tpl.html" );
    xMailTxtHTMLDATA(CONF_ORDERS_EMAIL,ADMIN_SEND_ACT_ORDER." #".$res["orderID"]." - ".CONF_SHOP_NAME, $html);
    xMailTxtHTMLDATA($order["customer_email"],STRING_ORDER." #".$res["orderID"]." - ".ADMIN_SEND_INACT_TITLE." - ".CONF_SHOP_NAME, $html);
    }
    $succes = 1;
    } else $succes = 0;

    return $succes;
}

function deactivate_order($actlink,&$smarty_mail)
{

    $q = db_query("select orderID FROM ".ORDERS_TABLE." WHERE custlink='".xEscSQL($actlink)."' ");
    if($pql = db_fetch_row($q)){
    $order = _getOrderById( $pql["orderID"]);
    ostSetOrderStatusToOrder( $pql["orderID"], ostGetCanceledStatusId(), '', '' );
    $smarty_mail->assign( "orderID", $pql["orderID"] );
    $smarty_mail->assign( "polidesk", ADMIN_SEND_INACT_DESK1);
    $html = $smarty_mail->fetch( "active_deactive_order.tpl.html" );
    xMailTxtHTMLDATA(CONF_ORDERS_EMAIL,ADMIN_SEND_DEACT_ORDER." #".$pql["orderID"]." - ".CONF_SHOP_NAME, $html);
    xMailTxtHTMLDATA($order["customer_email"],STRING_ORDER." #".$pql["orderID"]." - ".ADMIN_SEND_INACT_TITLE." - ".CONF_SHOP_NAME, $html);
    $succes = 1;
    }else $succes = 0;

    return $succes;
}

function _sendOrderNotifycationToAdmin( $orderID, &$smarty_mail, $tax )
{
        $order = _getOrderById( $orderID );
        $smarty_mail->assign( "customer_firstname", $order["customer_firstname"] );
        $smarty_mail->assign( "customer_lastname", $order["customer_lastname"] );
        $smarty_mail->assign( "customer_email", $order["customer_email"] );
        $smarty_mail->assign( "customer_ip", $order["customer_ip"] );
        $smarty_mail->assign( "order_time", format_datetime($order["order_time"]) );
        $smarty_mail->assign( "customer_comments", $order["customers_comment"] );
        $smarty_mail->assign( "discount", $order["order_discount"] );
        $smarty_mail->assign( "shipping_type", $order["shipping_type"] );
        $smarty_mail->assign( "shipping_cost", _formatPrice(roundf($order["currency_value"]*$order["shipping_cost"]),$order["currency_round"])." ".$order["currency_code"] );
        $smarty_mail->assign( "payment_type", $order["payment_type"] );
        $smarty_mail->assign( "shipping_firstname", $order["shipping_firstname"] );
        $smarty_mail->assign( "shipping_lastname", $order["shipping_lastname"] );
        $smarty_mail->assign( "shipping_country", $order["shipping_country"] );
        $smarty_mail->assign( "shipping_state", $order["shipping_state"] );
        $smarty_mail->assign( "shipping_city", $order["shipping_city"] );
        $smarty_mail->assign( "shipping_address", chop($order["shipping_address"]) );
        $smarty_mail->assign( "billing_firstname", $order["billing_firstname"] );
        $smarty_mail->assign( "billing_lastname", $order["billing_lastname"] );
        $smarty_mail->assign( "billing_country", $order["billing_country"] );
        $smarty_mail->assign( "billing_state", $order["billing_state"] );
        $smarty_mail->assign( "billing_city", $order["billing_city"] );
        $smarty_mail->assign( "billing_address", chop($order["billing_address"]) );
        $smarty_mail->assign( "order_amount", _formatPrice(roundf($order["currency_value"]*$order["order_amount"]),$order["currency_round"])." ".$order["currency_code"] );
        $smarty_mail->assign( "orderID", $order["orderID"] );
        $smarty_mail->assign( "total_tax", _formatPrice(roundf($order["currency_value"]*$tax),$order["currency_round"])." ".$order["currency_code"] );
        $smarty_mail->assign( "shippingServiceInfo", $order["shippingServiceInfo"] );
        $smarty_mail->assign( "tax", $tax);		
		
        // clear cost ( without shipping, discount, tax )
        $q1 = db_query( "select Price, Quantity from ".ORDERED_CARTS_TABLE." where orderID=".(int)$orderID);
        $clear_total_price = 0;
        while( $row=db_fetch_row($q1) ) $clear_total_price += $row["Price"]*$row["Quantity"];
		$order_discount_ToShow = _formatPrice(roundf($order["currency_value"]*$clear_total_price*((100-$order["order_discount"])/100)),$order["currency_round"])." ".$order["currency_code"];
		$smarty_mail->assign( "order_discount_ToShow", $order_discount_ToShow);
		
        //additional reg fields
        $addregfields = GetRegFieldsValuesByOrderID( $orderID );
        $smarty_mail->assign("customer_add_fields", $addregfields);

        //fetch order content from the database
        $content = ordGetOrderContent( $orderID );
        for( $i=0; $i<count($content); $i++ )
        {
                $productID = GetProductIdByItemId( $content[$i]["itemID"] );
                if ( $productID == null || trim($productID) == "" )
                        continue;
                $q = db_query("select name, product_code, default_picture from ".PRODUCTS_TABLE.
                        " where productID=".(int)$productID );
                $product = db_fetch_row($q);
                $content[$i]["product_code"] = $product["product_code"];
                $content[$i]["product_idn"] = $productID;
                /*
                $qz = db_query("select filename FROM ".PRODUCT_PICTURES." WHERE photoID=".$product["default_picture"]." AND productID=".$productID);
                $rowz = db_fetch_row($qz);
                if (strlen($rowz["filename"])>0 && file_exists( "data/small/".$rowz["filename"]))
                $content[$i]["product_picture"] = $rowz["filename"];
                else $content[$i]["product_picture"] = null;
                */
                $variants        = GetConfigurationByItemId( $content[$i]["itemID"] );
                $options        = GetStrOptions( $variants );
                if ( $options != "" )
                        $content[$i]["name"] = $product["name"]."(".$options.")";
                else
                        $content[$i]["name"] = $product["name"];
        }

        $smarty_mail->assign( "content", $content );
        $html = $smarty_mail->fetch( "admin_order_notification.tpl.html" );

        if (!CONF_ACTIVE_ORDER) xMailTxtHTMLDATA(CONF_ORDERS_EMAIL, STRING_ORDER." #".$orderID." - ".CONF_SHOP_NAME, $html);
        else  xMailTxtHTMLDATA(CONF_ORDERS_EMAIL, STRING_ORDER." #".$orderID." (".ADMIN_SEND_INACT_ORDER.") - ".CONF_SHOP_NAME, $html);
}

// *****************************************************************************
// Purpose        get order amount
// Inputs
//                                $cartContent is result of cartGetCartContent function
// Remarks
// Returns
function ordOrderProcessing(
                $shippingMethodID, $paymentMethodID,
                $shippingAddressID, $billingAddressID,
                $shippingModuleFiles, $paymentModulesFiles, $customers_comment,
                $cc_number,
                $cc_holdername,
                $cc_expires,
                $cc_cvv,
                $log,
                $smarty_mail, $shServiceID = 0)
{
        if ( $log != null )
                $customerID = regGetIdByLogin( $log );
        else
                $customerID = NULL;

        if ( $log != null )
                $customerInfo = regGetCustomerInfo2( $log );
        else
        {
                $customerInfo["first_name"]         = $_SESSION["first_name"] ;
                $customerInfo["last_name"]        = $_SESSION["last_name"] ;
                $customerInfo["Email"]                = $_SESSION["email"] ;
                $customerInfo["affiliationLogin"] = $_SESSION["affiliationLogin"] ;
        }
        $order_time   = get_current_time();
        $frandl = mt_rand(3,999);
        $order_active_link = md5($order_time).$frandl;
        $customer_ip  = stGetCustomerIP_Address();
        if (CONF_ACTIVE_ORDER == 1)$statusID = 0; else  $statusID = ostGetNewOrderStatus();
        $customer_affiliationLogin = isset($customerInfo["affiliationLogin"])?$customerInfo["affiliationLogin"]:'';
        $customer_email        = $customerInfo["Email"];

        $currencyID = currGetCurrentCurrencyUnitID();
        if ( $currencyID != 0 )
        {
                $currentCurrency = currGetCurrencyByID( $currencyID );
                $currency_code         = $currentCurrency["currency_iso_3"];
                $currency_value         = $currentCurrency["currency_value"];
                $currency_round         = $currentCurrency["roundval"];
        }
        else
        {
                $currency_code        = "";
                $currency_value = 1;
                $currency_round = 2;
        }

        // get shipping address
        if ( $shippingAddressID != 0 )
        {
                $shippingAddress                                        = regGetAddress( $shippingAddressID );
                $shippingAddressCountry                                = cnGetCountryById( $shippingAddress["countryID"] );
                $shippingAddress["country_name"]        = $shippingAddressCountry["country_name"];
        }
        else
        {
                $shippingCountryName        = cnGetCountryById( $_SESSION["receiver_countryID"] );
                $shippingCountryName        = $shippingCountryName["country_name"];
                $shippingAddress["first_name"]                =
                                $_SESSION["receiver_first_name"];
                $shippingAddress["last_name"]                =
                                $_SESSION["receiver_last_name"];
                $shippingAddress["country_name"]        = $shippingCountryName;
                $shippingAddress["state"]                        =
                                $_SESSION["receiver_state"];

                $shippingAddress["city"]                        =
                                $_SESSION["receiver_city"];
                $shippingAddress["address"]                        =
                                $_SESSION["receiver_address"];
                $shippingAddress["zoneID"]                        = $_SESSION["receiver_zoneID"];
        }
        if ( is_null($shippingAddress["state"]) || trim($shippingAddress["state"])=="" )
        {
                $zone = znGetSingleZoneById( $shippingAddress["zoneID"] );
                $shippingAddress["state"] = $zone["zone_name"];
        }


        // get billing address
        if ( $billingAddressID != 0 )
        {
                $billingAddress                                                = regGetAddress( $billingAddressID );
                $billingAddressCountry                                = cnGetCountryById( $billingAddress["countryID"] );
                $billingAddress["country_name"]                = $billingAddressCountry["country_name"];
        }
        else
        {
                $billingCountryName = cnGetCountryById( $_SESSION["billing_countryID"] );
                $billingCountryName        = $billingCountryName["country_name"];
                $billingAddress["first_name"]        = $_SESSION["billing_first_name"];
                $billingAddress["last_name"]        = $_SESSION["billing_last_name"];
                $billingAddress["country_name"] = $billingCountryName;
                $billingAddress["state"]                = $_SESSION["billing_state"];

                $billingAddress["city"]                        = $_SESSION["billing_city"];
                $billingAddress["address"]                = $_SESSION["billing_address"];
                $billingAddress["zoneID"]                = $_SESSION["billing_zoneID"];
        }
        if ( is_null($billingAddress["state"]) || trim($billingAddress["state"])=="" )
        {
                $zone = znGetSingleZoneById( $billingAddress["zoneID"] );
                $billingAddress["state"] = $zone["zone_name"];
        }

        $cartContent = cartGetCartContent();

        if ( $log != null )
                $addresses = array( $shippingAddressID, $billingAddressID );
        else
        {
                $addresses = array(
                                                array(
                                                                "countryID" => $_SESSION["receiver_countryID"],
                                                                "zoneID"        => $_SESSION["receiver_zoneID"]),
                                                array(
                                                                "countryID" => $_SESSION["billing_countryID"],
                                                                "zoneID"        => $_SESSION["billing_zoneID"])
                                        );
        }

        $orderDetails = array (
                        "first_name" => $shippingAddress["first_name"],
                        "last_name" => $shippingAddress["last_name"],
                        "email" => $customerInfo["Email"],
                        "order_amount" => oaGetOrderAmountExShippingRate( $cartContent, $addresses, $log, FALSE )
        );

        $shippingMethod                                        = shGetShippingMethodById( $shippingMethodID );
        $shipping_email_comments_text        = $shippingMethod["email_comments_text"];
        $shippingName                                        = $shippingMethod["Name"];

        $paymentMethod                                        = payGetPaymentMethodById( $paymentMethodID );
        $paymentName                                        = $paymentMethod["Name"];
        $payment_email_comments_text        = $paymentMethod["email_comments_text"];

        if (isset($paymentMethod["calculate_tax"]) && (int)$paymentMethod["calculate_tax"] == 0)
        {

                $order_amount = oaGetOrderAmount( $cartContent, $addresses,
                                        $shippingMethodID, $log, $orderDetails,TRUE, $shServiceID );
                $d = oaGetDiscountPercent( $cartContent, $log );
                $tax = 0;

                $shipping_costUC        = oaGetShippingCostTakingIntoTax( $cartContent, $shippingMethodID, $addresses, $orderDetails, FALSE, $shServiceID, TRUE );
                $discount_percent        = oaGetDiscountPercent( $cartContent, $log );


        }
        else
        {

                $order_amount = oaGetOrderAmount( $cartContent, $addresses,
                                        $shippingMethodID, $log, $orderDetails, TRUE, $shServiceID );
                $d = oaGetDiscountPercent( $cartContent, $log );
                $tax = oaGetProductTax( $cartContent, $d, $addresses );

                $shipping_costUC        = oaGetShippingCostTakingIntoTax( $cartContent, $shippingMethodID, $addresses, $orderDetails, TRUE, $shServiceID, TRUE );
                $discount_percent        = oaGetDiscountPercent( $cartContent, $log );

        }
        $shServiceInfo = '';
        if(is_array($shipping_costUC)){

                list($shipping_costUC) = $shipping_costUC;
                $shServiceInfo = $shipping_costUC['name'];
                $shipping_costUC = $shipping_costUC['rate'];
        }
        $paymentMethod = payGetPaymentMethodById( $paymentMethodID );
        if ( $paymentMethod ){
                $currentPaymentModule = modGetModuleObj( $paymentMethod["module_id"], PAYMENT_MODULE );
        }else{
                $currentPaymentModule = null;
}
        if ( $currentPaymentModule != null )
        {
                //define order details for payment module
                $order_payment_details = array(
                        "customer_email" => $customer_email,
                        "customer_ip" => $customer_ip,
                        "order_amount" => $order_amount,
                        "currency_code" => $currency_code,
                        "currency_value" => $currency_value,
                        "shipping_cost" => $shipping_costUC,
                        "order_tax" => $tax,
                        "shipping_info" => $shippingAddress,
                        "billing_info" => $billingAddress
                );

                $process_payment_result = $currentPaymentModule->payment_process( $order_payment_details ); //gets payment processing result

                if ( !($process_payment_result == 1) ) //error on payment processing
                { //die ($process_payment_result);

                        if (isset($_POST))
                        {
                                $_SESSION["order4confirmation_post"] = $_POST;
                        }

                        xSaveData('PaymentError', $process_payment_result);
                        if (!$customerID)
                        {
                                RedirectProtected( "index.php?order4_confirmation_quick=yes".
                                                        "&shippingMethodID=".$_GET["shippingMethodID"].
                                                        "&paymentMethodID=".$_GET["paymentMethodID"].
                                                        "&shServiceID=".$shServiceID );
                        }
                        else
                        {
                                RedirectProtected( "index.php?order4_confirmation=yes".
                                                        "&shippingAddressID=".$_GET["shippingAddressID"]."&shippingMethodID=".$_GET["shippingMethodID"].        "&billingAddressID=".$_GET["billingAddressID"]."&paymentMethodID=".$_GET["paymentMethodID"].
                                                        "&shServiceID=".$shServiceID );
                        }
                        return false;
                }
        }

        $customerID = (int) $customerID;


        $sql = "insert into ".ORDERS_TABLE.
                        " ( customerID, ".
                        "        order_time, ".
                        "        customer_ip, ".
                        "        shipping_type, ".
                        "        payment_type, ".
                        "        customers_comment, ".
                        "        statusID, ".
                        "        shipping_cost, ".
                        "        order_discount, ".
                        "        order_amount, ".
                        "        currency_code, ".
                        "        currency_value, ".
                        "        customer_firstname, ".
                        "        customer_lastname, ".
                        "        customer_email, ".
                        "        shipping_firstname, ".
                        "        shipping_lastname, ".
                        "        shipping_country, ".
                        "        shipping_state, ".
                        "        shipping_city, ".
                        "        shipping_address, ".
                        "        billing_firstname, ".
                        "        billing_lastname, ".
                        "        billing_country, ".
                        "        billing_state, ".
                        "        billing_city, ".
                        "        billing_address, ".
                        "        cc_number, ".
                        "        cc_holdername, ".
                        "        cc_expires, ".
                        "        cc_cvv, ".
                        "        affiliateID, ".
                        "        shippingServiceInfo, ".
                        "        custlink, ".
                        "        currency_round, ".
                        "        paymethod".
                        "                  ) ".
                        " values ( ".
                                (int)$customerID.", ".
                                "'".xEscSQL($order_time)."', ".
                                "'".xToText($customer_ip)."', ".
                                "'".xToText($shippingName)."', ".
                                "'".xToText($paymentName)."', ".
                                "'".xToText($customers_comment)."', ".
                                (int)$statusID.", ".
                                ( (float) $shipping_costUC ).", ".
                                ( (float) $discount_percent ).", ".
                                ( (float) $order_amount ).", ".
                                "'".xEscSQL($currency_code)."', ".
                                ( (float) $currency_value ).", ". 
                                "'".xToText($customerInfo["first_name"])."', ".
                                "'".xToText($customerInfo["last_name"])."', ".
                                "'".xToText($customer_email)."', ".
                                "'".xToText($shippingAddress["first_name"])."', ".
                                "'".xToText($shippingAddress["last_name"])."', ".
                                "'".xToText($shippingAddress["country_name"])."', ".
                                "'".xToText($shippingAddress["state"])."', ".
                                "'".xToText($shippingAddress["city"])."', ".
                                "'".xToText($shippingAddress["address"])."', ".
                                "'".xToText($billingAddress["first_name"])."', ".
                                "'".xToText($billingAddress["last_name"])."', ".
                                "'".xToText($billingAddress["country_name"])."', ".
                                "'".xToText($billingAddress["state"])."', ".
                                "'".xToText($billingAddress["city"])."', ".
                                "'".xToText($billingAddress["address"])."', ".
                                "'".xEscSQL($cc_number)."', ".
                                "'".xToText($cc_holdername)."', ".
                                "'".xEscSQL($cc_expires)."', ".
                                "'".xEscSQL($cc_cvv)."', ".
                                "'".(isset($_SESSION['refid'])?$_SESSION['refid']:regGetIdByLogin($customer_affiliationLogin))."',".
                                "'{$shServiceInfo}', ".
                                "'".xEscSQL($order_active_link)."', ".
                                "'".(int)$currency_round."', ".
                                "'".(int)$paymentMethodID."'".
                        " ) ";
                        db_query($sql);
        $orderID = db_insert_id( ORDERS_TABLE );

        if (!CONF_ACTIVE_ORDER) stChangeOrderStatus($orderID, $statusID);

        $paymentMethod = payGetPaymentMethodById( $paymentMethodID );
        if ( $paymentMethod ){
                $currentPaymentModule = modGetModuleObj( $paymentMethod["module_id"], PAYMENT_MODULE );
//                $currentPaymentModule = payGetPaymentModuleById( $paymentMethod["module_id"], $paymentModulesFiles );
        }else{
                $currentPaymentModule = null;
}
        //save shopping cart content to database and update in-stock information
        if ( $log != null )
        {
                cartMoveContentFromShoppingCartsToOrderedCarts( $orderID,
                        $shippingMethodID, $paymentMethodID,
                        $shippingAddressID, $billingAddressID,
                        $shippingModuleFiles, $paymentModulesFiles, $smarty_mail );
        }
        else //quick checkout
        {
                _moveSessionCartContentToOrderedCart( $orderID );
                //update in-stock information
                if ( $statusID != ostGetCanceledStatusId() && CONF_CHECKSTOCK )
                {
                        $q1 = db_query("select itemID, Quantity FROM ".ORDERED_CARTS_TABLE." WHERE orderID=".(int)$orderID);
                        while ($item = db_fetch_row($q1))
                        {
                                $q2 = db_query("select productID FROM ".SHOPPING_CART_ITEMS_TABLE." WHERE itemID=".(int)$item["itemID"]);
                                $pr = db_fetch_row($q2);
                                if ($pr)
                                {
                                    db_query( "update ".PRODUCTS_TABLE." set in_stock = in_stock - ".(int)$item["Quantity"].
                                                                        " where productID=".(int)$pr[0]);
                                    $q = db_query("select name, in_stock FROM ".PRODUCTS_TABLE." WHERE productID=".(int)$pr[0]);
                                    $productsta = db_fetch_row($q);
                                    if ( $productsta[1] == 0){
									    if (CONF_AUTOOFF_STOCKADMIN) db_query( "update ".PRODUCTS_TABLE." set enabled=0 where productID=".(int)$pr[0]);
									    if (CONF_NOTIFY_STOCKADMIN){
                                            $smarty_mail->assign( "productstaname", $productsta[0] );
                                            $smarty_mail->assign( "productstid", $pr[0] );
                                            $stockadmin = $smarty_mail->fetch( "notify_stockadmin.tpl.html" );
                                            $ressta = xMailTxtHTMLDATA(CONF_ORDERS_EMAIL,CUSTOMER_ACTIVATE_99." - ".CONF_SHOP_NAME, $stockadmin);
										}
                                    }
                                }
                        }
                }

                //now save registration form aux fields into CUSTOMER_REG_FIELDS_VALUES_TABLE_QUICKREG
                //for quick checkout orders these fields are stored separately than for registered customer (SS_customers)
                db_query("delete from ".CUSTOMER_REG_FIELDS_VALUES_TABLE_QUICKREG." where orderID=".(int)$orderID);
                foreach($_SESSION as $key => $val)
                {
                        if (strstr($key,"additional_field_") && strlen(trim($val)) > 0) //save information into sessions
                        {
                                $id = (int) str_replace("additional_field_","",$key);
                                if ($id > 0)
                                {
                                db_query("insert into ".CUSTOMER_REG_FIELDS_VALUES_TABLE_QUICKREG." (orderID, reg_field_ID, reg_field_value) values (".(int)$orderID.", ".(int)$id.", '".xToText(trim($val))."');");
                                }
                        }
                }
        }

        if ( $currentPaymentModule != null ) $currentPaymentModule->after_processing_php( $orderID );

        _sendOrderNotifycationToAdmin( $orderID, $smarty_mail, $tax );
        _sendOrderNotifycationToCustomer( $orderID, $smarty_mail, $customerInfo["Email"], $log,
                                $payment_email_comments_text, $shipping_email_comments_text, $tax, $order_active_link );
        if ( $log == null )
                _quickOrderUnsetSession();

        unset($_SESSION["order4confirmation_post"]);

        return $orderID;
}



function _setHyphen( & $str )
{
        if ( trim($str) == "" || $str == null )
                $str = "-";
}

// *****************************************************************************
// Purpose        get order by id
// Inputs
// Remarks
// Returns
function ordGetOrder( $orderID )
{

        $q = db_query( "select orderID, customerID, order_time, customer_ip, ".
                 " shipping_type, payment_type, customers_comment, ".
                 " statusID, shipping_cost, order_discount, order_amount, ".
                 " currency_code, currency_value, customer_firstname, customer_lastname, ".
                 " customer_email, shipping_firstname, shipping_lastname, ".
                 " shipping_country, shipping_state, shipping_city, ".
                 " shipping_address, billing_firstname, billing_lastname, billing_country, ".
                 " billing_state, billing_city, billing_address, ".
                 " cc_number, cc_holdername, cc_expires, cc_cvv, affiliateID, shippingServiceInfo, currency_round  from ".ORDERS_TABLE." where orderID=".(int)$orderID);
        $order = db_fetch_row($q);
        if ( $order )
        {
                /*_setHyphen( $order["shipping_firstname"] );
                _setHyphen( $order["customer_lastname"] );
                _setHyphen( $order["customer_email"] );
                _setHyphen( $order["shipping_firstname"] );
                _setHyphen( $order["shipping_lastname"] );
                _setHyphen( $order["shipping_country"] );
                _setHyphen( $order["shipping_state"] );
                _setHyphen( $order["shipping_city"] );
                _setHyphen( $order["shipping_address"] );
                _setHyphen( $order["billing_firstname"] );
                _setHyphen( $order["billing_lastname"] );
                _setHyphen( $order["billing_country"] );
                _setHyphen( $order["billing_state"] );
                _setHyphen( $order["billing_city"] );
                _setHyphen( $order["billing_address"] );*/

                $order["shipping_address"] = chop($order["shipping_address"]);
                $order["billing_address"] = chop($order["billing_address"]);
                //CC data
                if (CONF_BACKEND_SAFEMODE)
                {
                        $order["cc_number"] = ADMIN_SAFEMODE_BLOCKED;
                        $order["cc_holdername"] = ADMIN_SAFEMODE_BLOCKED;
                        $order["cc_expires"] = ADMIN_SAFEMODE_BLOCKED;
                        $order["cc_cvv"] = ADMIN_SAFEMODE_BLOCKED;
                }
                else
                {
                        if (strlen($order["cc_number"])>0)
                                $order["cc_number"] = cryptCCNumberDeCrypt($order["cc_number"],null);
                        if (strlen($order["cc_holdername"])>0)
                                $order["cc_holdername"] = cryptCCHoldernameDeCrypt($order["cc_holdername"],null);
                        if (strlen($order["cc_expires"])>0)
                                $order["cc_expires"] = cryptCCExpiresDeCrypt($order["cc_expires"],null);
                        if (strlen($order["cc_cvv"])>0)
                                $order["cc_cvv"] = cryptCCNumberDeCrypt($order["cc_cvv"],null);
                }

                //additional reg fields
                $addregfields = GetRegFieldsValuesByOrderID( $orderID );
                $order["reg_fields_values"] = $addregfields;


                $q_status_name = db_query( "select status_name from ".ORDER_STATUES_TABLE." where statusID=".(int)$order["statusID"] );
                $status_name = db_fetch_row( $q_status_name );
                $status_name = $status_name[0];

                if ( $order["statusID"] == ostGetCanceledStatusId() ) $status_name = STRING_CANCELED_ORDER_STATUS;

                // clear cost ( without shipping, discount, tax )
                $q1 = db_query( "select Price, Quantity from ".ORDERED_CARTS_TABLE." where orderID=".(int)$orderID);
                $clear_total_price = 0;
                while( $row=db_fetch_row($q1) ) $clear_total_price += $row["Price"]*$row["Quantity"];

                $currency_round =  $order["currency_round"];
                $order["clear_total_priceToShow"] = _formatPrice(roundf($order["currency_value"]*$clear_total_price),$currency_round)." ".$order["currency_code"];
                $order["order_discount_ToShow"]   = _formatPrice(roundf($order["currency_value"]*$clear_total_price*((100-$order["order_discount"])/100)),$currency_round)." ".$order["currency_code"];
                $order["shipping_costToShow"]     = _formatPrice(roundf($order["currency_value"]*$order["shipping_cost"]),$currency_round)." ".$order["currency_code"];
                $order["order_amountToShow"]      = _formatPrice(roundf($order["currency_value"]*$order["order_amount"]),$currency_round)." ".$order["currency_code"];

                $order["order_time_mysql"] = $order["order_time"];
                $order["order_time"] = format_datetime( $order["order_time"] );

                $order["status_name"] = $status_name;
        }
        return $order;
}


function ordGetOrderContent( $orderID )
{
        $q = db_query( "select name, Price, Quantity, tax, load_counter, itemID from ".ORDERED_CARTS_TABLE." where orderID=".(int)$orderID );
        $q_order = db_query( "select currency_code, currency_value, customerID, order_time, currency_round from ".ORDERS_TABLE." where orderID=".(int)$orderID);
        $order = db_fetch_row($q_order);
        $currency_code = $order["currency_code"];
        $currency_value = $order["currency_value"];
        $currency_round = $order["currency_round"];
        $data = array();

        while( $row=db_fetch_row($q) )
        {
                $productID = GetProductIdByItemId( $row["itemID"] );
                $row["pr_item"] =  $productID;
                $product   = GetProduct( $productID );
                if ( $product["eproduct_filename"] != null &&
                         $product["eproduct_filename"] != "" )
                {
                        if ( file_exists("core/files/".$product["eproduct_filename"]) )
                        {
                                        $row["eproduct_filename"]        = $product["eproduct_filename"];
                                        $row["file_size"]                        = (string) round(filesize("core/files/".$product["eproduct_filename"]) / 1048576, 3);

                                        if ( $order["customerID"] != null )
                                        {
                                                $custID = $order["customerID"];
                                        }
                                        else
                                        {
                                                $custID = -1;
                                        }

                                        $row["getFileParam"] =
                                                                "orderID=".$orderID."&".
                                                                "productID=".$productID."&".
                                                                "customerID=".$custID;

                                        //additional security for non authorized customers
                                        if ($custID == -1)
                                        {
                                                $row["getFileParam"] .= "&order_time=".base64_encode($order["order_time"]);
                                        }

                                        $row["getFileParam"] = cryptFileParamCrypt(
                                                                        $row["getFileParam"], null );
                                        $row["load_counter_remainder"]                =
                                                        $product["eproduct_download_times"] - $row["load_counter"];

                                        $currentDate = dtGetParsedDateTime( get_current_time() );
                                        $betweenDay  = _getDayBetweenDate(
                                                        dtGetParsedDateTime( $order["order_time"] ),
                                                        $currentDate );

                                        $row["day_count_remainder"] = $product["eproduct_available_days"] - $betweenDay;

                        }
                }

                $row["PriceToShow"] =  _formatPrice(roundf($currency_value*$row["Price"]*$row["Quantity"]),$currency_round)." ".$currency_code;
                $row["PriceOne"] =   _formatPrice(roundf($currency_value*$row["Price"]),$currency_round)." ".$currency_code;
                $data[] = $row;
        }
        return $data;
}


// *****************************************************************************
// Purpose        deletes  order
// Inputs
// Remarks        this function deletes canceled orders only
// Returns
function ordDeleteOrder( $orderID )
{
        $q = db_query( "select statusID from ".ORDERS_TABLE." where orderID=".(int)$orderID );
        $row = db_fetch_row( $q );
        if ( $row["statusID"] != ostGetCanceledStatusId() ) return;
        db_query( "delete from ".ORDERED_CARTS_TABLE." where orderID=".(int)$orderID);
        db_query( "delete from ".ORDERS_TABLE." where orderID=".(int)$orderID);
        db_query( "delete from ".ORDER_STATUS_CHANGE_LOG_TABLE." where orderID=".(int)$orderID);
}

function DelOrdersBySDL( $statusdel )
{
        $q = db_query( "select orderID from ".ORDERS_TABLE." where statusID=".(int)$statusdel );
        while( $row = db_fetch_row( $q ) )
        {
        db_query( "delete from ".ORDERED_CARTS_TABLE." where orderID=".(int)$row["orderID"] );
        db_query( "delete from ".ORDERS_TABLE." where orderID=".(int)$row["orderID"] );
        db_query( "delete from ".ORDER_STATUS_CHANGE_LOG_TABLE." where orderID=".(int)$row["orderID"] );
        }
}


// *****************************************************************************
// Purpose        gets summarize order info to
// Inputs
// Remarks
// Returns
function getOrderSummarize(
                        $shippingMethodID, $paymentMethodID,
                        $shippingAddressID, $billingAddressID,
                        $shippingModuleFiles, $paymentModulesFiles, $shServiceID = 0 )
{
        // result this function
        $sumOrderContent = array();

        $q = db_query( "select email_comments_text from ".PAYMENT_TYPES_TABLE." where PID=".(int)$paymentMethodID );
        $payment_email_comments_text = db_fetch_row( $q );
        $payment_email_comments_text = $payment_email_comments_text[0];

        $q = db_query( "select email_comments_text from ".SHIPPING_METHODS_TABLE." where SID=".(int)$shippingMethodID );
        $shipping_email_comments_text = db_fetch_row( $q );
        $shipping_email_comments_text = $shipping_email_comments_text[0];



        $cartContent = cartGetCartContent();
        $pred_total  = oaGetClearPrice( $cartContent );

        if ( isset($_SESSION["log"]) )
                $log = $_SESSION["log"];
        else
                $log = null;

        $d = oaGetDiscountPercent( $cartContent, $log );
        $discount = $pred_total/100*$d;

        // ordering with registration
        if ( $shippingAddressID != 0 || isset($log) )
        {
                $addresses = array($shippingAddressID, $billingAddressID);
                $shipping_address = regGetAddressStr($shippingAddressID);
                $billing_address  = regGetAddressStr($billingAddressID);
                $shaddr = regGetAddress($shippingAddressID);
                $sh_firstname = $shaddr["first_name"];
                $sh_lastname = $shaddr["last_name"];
        }
        else //quick checkout
        {
                if (!isset($_SESSION["receiver_countryID"]) || !isset($_SESSION["receiver_zoneID"]))
                        return NULL;

                $shippingAddress = array(
                                "countryID" => $_SESSION["receiver_countryID"],
                                "zoneID"        => $_SESSION["receiver_zoneID"]);
                $billingAddress = array(
                                "countryID" => $_SESSION["billing_countryID"],
                                "zoneID"        => $_SESSION["billing_zoneID"]);
                $addresses = array( $shippingAddress, $billingAddress );
                $shipping_address = quickOrderGetReceiverAddressStr();
                $billing_address  = quickOrderGetBillingAddressStr();

                $sh_firstname = $_SESSION["receiver_first_name"];
                $sh_lastname = $_SESSION["receiver_last_name"];

        }


        foreach( $cartContent["cart_content"] as $cartItem )
        {
                // if conventional ordering
                if ( $shippingAddressID != 0 )
                {
                        $productID = GetProductIdByItemId( $cartItem["id"] );
                        $cartItem["tax"] =
                                taxCalculateTax( $productID, $addresses[0], $addresses[1] );
                }
                else // if quick ordering
                {
                        $productID = $cartItem["id"];
                        $cartItem["tax"] =
                                        taxCalculateTax2( $productID, $addresses[0], $addresses[1] );
                }
                $sumOrderContent[] = $cartItem;
        }



        $shipping_method        = shGetShippingMethodById( $shippingMethodID );
        if ( !$shipping_method )
                $shipping_name = "-";
        else
                $shipping_name = $shipping_method["Name"];

        $payment_method                = payGetPaymentMethodById($paymentMethodID);
        if ( !$payment_method )
                $payment_name = "-";
        else
                $payment_name        = $payment_method["Name"];

        //do not calculate tax for this payment type!
        if (isset($payment_method["calculate_tax"]) && (int)$payment_method["calculate_tax"]==0)
        {
                foreach( $sumOrderContent as $key => $val )
                {
                        $sumOrderContent[ $key ] ["tax"] = 0;
                }

                $orderDetails = array (
                                "first_name" => $sh_firstname,
                                "last_name" => $sh_lastname,
                                "email" => "",
                                "order_amount" => oaGetOrderAmountExShippingRate( $cartContent, $addresses, $log, FALSE, $shServiceID )
                );

                $tax = 0;
                $total                        = oaGetOrderAmount( $cartContent, $addresses, $shippingMethodID, $log, $orderDetails, FALSE, $shServiceID );
                $shipping_cost  = oaGetShippingCostTakingIntoTax( $cartContent, $shippingMethodID, $addresses, $orderDetails, FALSE, $shServiceID );
        }
        else
        {
                $orderDetails = array (
                                "first_name" => $sh_firstname,
                                "last_name" => $sh_lastname,
                                "email" => "",
                                "order_amount" => oaGetOrderAmountExShippingRate( $cartContent, $addresses, $log, FALSE )
                );


                $tax                        = oaGetProductTax( $cartContent, $d, $addresses );
                $total                        = oaGetOrderAmount( $cartContent, $addresses, $shippingMethodID, $log, $orderDetails, TRUE,  $shServiceID );
                $shipping_cost  = oaGetShippingCostTakingIntoTax( $cartContent, $shippingMethodID, $addresses, $orderDetails, TRUE,  $shServiceID );
        }
        
		$tServiceInfo = null;
        if(is_array($shipping_cost)){

                $_T = array_shift($shipping_cost);
				$tServiceInfo = $_T['name'];
                $shipping_cost = $_T['rate'];
        }

        $payment_form_html = "";
        $paymentModule = modGetModuleObj($payment_method["module_id"], PAYMENT_MODULE);
        if($paymentModule){

                $order                = array();
                $address        = array();
                if ( $shippingAddressID != 0 ){
                        $payment_form_html = $paymentModule->payment_form_html(array('BillingAddressID'=>$billingAddressID));
                }else{
                        $payment_form_html = $paymentModule->payment_form_html(array(
                                'countryID' => $_SESSION['billing_countryID'],
                                'zoneID'        => $_SESSION['billing_zoneID'],
                                'first_name' => $_SESSION["billing_first_name"],
                                'last_name' => $_SESSION["billing_last_name"],
                                'city' => $_SESSION["billing_city"],
                                'address' => $_SESSION["billing_address"],
                                ));
                }
        }

        return array(   "sumOrderContent"   => $sumOrderContent,
                        "discount"          => $discount,
                        "discount_percent"  => $d,
						"discount_show"     => show_price($discount),
                        "pred_total_disc"   => show_price(($pred_total*((100-$d)/100))),
                        "pred_total"        => show_price($pred_total),
                        "totalTax"          => show_price($tax),
                        "totalTaxUC"        => $tax,
                        "shipping_address"  => $shipping_address,
                        "billing_address"   => $billing_address,
                        "shipping_name"     => $shipping_name,
                        "payment_name"      => $payment_name,
                        "shipping_cost"     => show_price($shipping_cost),
                        "shipping_costUC"   => $shipping_cost,
                        "payment_form_html" => $payment_form_html,
                        "total"             => show_price($total),
                        "totalUC"           => $total,
                        "payment_email_comments_text"   => $payment_email_comments_text,
                        "shipping_email_comments_text"  => $shipping_email_comments_text,
                        "orderContentCartProductsCount" => count($sumOrderContent),
						"shippingServiceInfo" => $tServiceInfo);
}

function mycal_days_in_month( $calendar, $month, $year )
{
        $month = (int)$month;
        $year  = (int)$year;

        if ( 1 > $month || $month > 12 )
                return 0;
        if ( $month==1 || $month==3 || $month==5 || $month==7 || $month==8 || $month==10 || $month==12 )
                return 31;
        else
        {
                if ( $month==2 && $year % 4 == 0 )
                        return 29;
                else if ( $month==2 && $year % 4 != 0 )
                        return 28;
                else
                        return 30;
        }
}

function _getCountDay( $date )
{
        $countDay = 0;
        for( $year=1900; $year<$date["year"]; $year++ )
        {
                for( $month=1; $month <= 12; $month++ )
                        $countDay += mycal_days_in_month(CAL_GREGORIAN, $month, $year);
        }

        for( $month=1; $month < $date["month"]; $month++ )
                $countDay += mycal_days_in_month(CAL_GREGORIAN, $month, $date["year"]);

        $countDay += $date["day"];
        return $countDay;
}



// *****************************************************************************
// Purpose        gets address string
// Inputs           $date array of item
//                        "day"
//                        "month"
//                        "year"
//                $date2 must be more later $date1
// Remarks
// Returns
function _getDayBetweenDate( $date1, $date2 )
{
        if ( $date1["year"] > $date2["year"] )
                return -1;
        if ( $date1["year"]==$date2["year"] && $date1["month"]>$date2["month"] )
                return -1;
        if ( $date1["year"]==$date2["year"] && $date1["month"]==$date2["month"] &&
                $date1["day"] > $date2["day"]  )
                return -1;
        return _getCountDay( $date2 ) - _getCountDay( $date1 );
}




// *****************************************************************************
// Purpose
// Inputs
// Remarks
// Returns
//                -1         access denied
//                0        success, access granted and load_counter has been incremented
//                1        access granted but count downloading is exceeded eproduct_download_times in PRODUCTS_TABLE
//                2        access granted but available days are exhausted to download product
//                3        it is not downloadable product
//                4        order is not ready
function ordAccessToLoadFile( $orderID, $productID, & $pathToProductFile, & $productFileShortName )
{
        $order                 = ordGetOrder($orderID);
        $product         = GetProduct( $productID );

        if ( strlen($product["eproduct_filename"]) == 0 || !file_exists("core/files/".$product["eproduct_filename"]) || $product["eproduct_filename"] == null )
        {
                return 4;
        }

        if ( (int)$order["statusID"] != (int)ostGetCompletedOrderStatus() )
                return 3;

        $orderContent         = ordGetOrderContent( $orderID );
        foreach( $orderContent as $item )
        {
                if ( GetProductIdByItemId($item["itemID"]) == $productID )
                {
                        if ( $item["load_counter"] < $product["eproduct_download_times"] ||
                                        $product["eproduct_download_times"] == 0 )
                        {
                                $date1 = dtGetParsedDateTime( $order["order_time_mysql"] ); //$order["order_time"]
                                $date2 = dtGetParsedDateTime( get_current_time() );

                                $countDay = _getDayBetweenDate( $date1, $date2 );

                                if ( $countDay>=$product["eproduct_available_days"] )
                                        return 2;

                                if ( $product["eproduct_download_times"] != 0 )
                                {
                                        db_query( "update ".ORDERED_CARTS_TABLE.
                                                " set load_counter=load_counter+1 ".
                                                " where itemID=".(int)$item["itemID"]." AND orderID=".(int)$orderID );
                                }
                                $pathToProductFile                = "core/files/".$product["eproduct_filename"];
                                $productFileShortName        = $product["eproduct_filename"];
                                return 0;
                        }
                        else
                                return 1;
                }
        }
        return -1;
}


?><?php
#####################################
# ShopCMS: Скрипт интернет-магазина
# Copyright (c) by ADGroup
# http://shopcms.ru
#####################################


        // *****************************************************************************
        // Purpose        Insert prdefined order status into ORDER_STATUES_TABLE.
        //                                This status correspondes to completed orders.
        // Inputs
        // Remarks        this function is called in CreateTablesStructureXML
        // Returns        nothing
        function ostInstall()
        {
                db_query("insert into ".ORDER_STATUES_TABLE.
                                " ( status_name, sort_order ) ".
                                " values( '".STRING_CANCELED_ORDER_STATUS."', 0 ) ");
        }


        // *****************************************************************************
        // Purpose        gets status id corresponded to canceled order
        // Inputs
        // Remarks
        // Returns        nothing
        function ostGetCanceledStatusId()
        {
                return 1;
        }

        // *****************************************************************************
        // Purpose        if order status is status of canceled order
        // Inputs
        // Remarks
        // Returns        nothing
        function _correctOrderStatusName( &$orderStatus )
        {
                if ( $orderStatus["statusID"] == ostGetCanceledStatusId() )
                        $orderStatus["status_name"] = STRING_CANCELED_ORDER_STATUS;
        }


        // *****************************************************************************
        // Purpose        get any status that differents from status with $statusID ID
        // Inputs
        //                                $statusID - status ID
        // Remarks
        // Returns        item
        //                                "statusID"                - status ID
        //                                "status_name"        - status name
        //                                "sort_order"        - status order
        function ostGetOtherStatus( $statusID )
        {
                $q = db_query("select statusID, status_name, sort_order from ".
                        ORDER_STATUES_TABLE." where statusID!=".(int)$statusID.
                        " AND statusID!=".(int)ostGetCanceledStatusId());
                if( $row = db_fetch_row($q) )
                {
                        _correctOrderStatusName( $row );
                        return $row;
                }
                else
                        return false;
        }


        // *****************************************************************************
        // Purpose        get status ID corresponded to new order
        // Inputs
        // Remarks
        // Returns  status ID
        function ostGetNewOrderStatus()
        {
                if ( defined("CONF_NEW_ORDER_STATUS") )
                {
                        $begin_status = CONF_NEW_ORDER_STATUS;
                        $q = db_query("select count(*) from ".ORDER_STATUES_TABLE.
                                " where statusID=".(int)$begin_status );
                        $row = db_fetch_row( $q );
                         if ( $row[0] )
                                return $begin_status;
                        else
                                return null;
                }
                return null;
        }


        // *****************************************************************************
        // Purpose        get status name ID corresponded to status ID
        // Inputs
        //                        $statusID - status ID
        // Remarks
        // Returns  status ID
        function ostGetOrderStatusName( $statusID )
        {
                $q = db_query("select status_name from ".ORDER_STATUES_TABLE.
                        " where statusID=".(int)$statusID);
                $row = db_fetch_row( $q );
                if ( $statusID == ostGetCanceledStatusId() )
                        $row["status_name"] = STRING_CANCELED_ORDER_STATUS;
                return $row["status_name"];
        }


        // *****************************************************************************
        // Purpose        get status ID corresponded to comleted order
        // Inputs
        // Remarks
        // Returns  status ID
        function ostGetCompletedOrderStatus()
        {
                if ( defined("CONF_COMPLETED_ORDER_STATUS") )
                {
                        $end_status = CONF_COMPLETED_ORDER_STATUS;
                        $q = db_query("select count(*) from ".ORDER_STATUES_TABLE.
                                " where statusID=".(int)$end_status );
                        $row = db_fetch_row( $q );
                         if ( $row[0] )
                        {
                                return $end_status;
                        }
                        else
                                return null;
                }
                return null;
        }


        // *****************************************************************************
        // Purpose        get all order statuses
        // Inputs
        // Remarks
        // Returns        item
        //                                "statusID"                - status ID
        //                                "status_name"        - status name
        //                                "sort_order"        - status order
        function ostGetOrderStatues( $fullList = true, $format = 'just' )
        {
                $data = array();
                if ( $fullList )
                {
                        $q = db_query( "select statusID, status_name, sort_order from ".
                                ORDER_STATUES_TABLE." where statusID=".(int)ostGetCanceledStatusId() );
                         $row = db_fetch_row( $q );

                        $r = array( "statusID" => $row["statusID"],
                                        "status_name" => $row["status_name"],
                                        "sort_order" => $row["sort_order"] );
                        _correctOrderStatusName( $r );
                        $data[] = $r;
                }

                $q = db_query("select statusID, status_name, sort_order from ".
                        ORDER_STATUES_TABLE." where statusID!=".(int)ostGetCanceledStatusId().
                                " order by sort_order " );
                while( $r = db_fetch_row( $q ) ) $data[] = $r;

                return $data;
        }


        // *****************************************************************************
        // Purpose        add order status
        // Inputs
        // Remarks
        // Returns  status ID
        function ostAddOrderStatus($name, $sort_order)
        {
                db_query("insert into ".ORDER_STATUES_TABLE."(status_name, sort_order) ".
                         "values( '".xToText($name)."', ".(int)$sort_order." )");
                return db_insert_id();
        }


        // *****************************************************************************
        // Purpose        update order status
        // Inputs
        // Remarks
        // Returns  status ID
        function ostUpdateOrderStatus( $statusID, $status_name, $sort_order )
        {
                db_query("update ".ORDER_STATUES_TABLE." set ".
                         "status_name ='".xToText($status_name)."', ".
                         "sort_order  = ".(int)$sort_order.
                         " where statusID=".(int)$statusID);
        }

        // *****************************************************************************
        // Purpose        delete order status
        // Inputs
        // Remarks
        // Returns  status ID
        function ostDeleteOrderStatus( $statusID )
        {
                $q = db_query("select count(*) from ".ORDERS_TABLE." where statusID=".(int)$statusID );
                $r = db_fetch_row( $q );
                if ( $r[0] != 0 )
                        return false;
                db_query("delete from ".ORDER_STATUES_TABLE." where statusID=".(int)$statusID);
                return true;
        }





        function _changeIn_stock( $orderID, $increase )
        {
                if ( !CONF_CHECKSTOCK ) return;
                $q = db_query( "select itemID, Quantity from ".ORDERED_CARTS_TABLE.
                                " where orderID=".(int)$orderID);
                while( $item = db_fetch_row($q) )
                {
                        $Quantity = $item["Quantity"];
                        $q1 = db_query( "select productID from ".SHOPPING_CART_ITEMS_TABLE.
                                        " where itemID=".(int)$item["itemID"] );
                        $product = db_fetch_row( $q1 );
                        if ( $product["productID"] != null &&
                                         trim($product["productID"]) != "" )
                        {
                                if ( $increase ) {
                                        db_query( "update ".PRODUCTS_TABLE." set in_stock=in_stock + ".(int)$Quantity.
                                                                " where productID=".(int)$product["productID"] );
                                }else{
                                        db_query( "update ".PRODUCTS_TABLE." set in_stock=in_stock - ".(int)$Quantity.
                                                                " where productID=".(int)$product["productID"] );

                                }

                        }
                }
        }


        function _changeSOLD_counter( $orderID, $increase )
        {
                $q = db_query( "select itemID, Quantity from ".ORDERED_CARTS_TABLE.
                                " where orderID=".(int)$orderID);
                while( $item = db_fetch_row($q) )
                {
                        $Quantity = $item["Quantity"];
                        $q1 = db_query( "select productID from ".SHOPPING_CART_ITEMS_TABLE.
                                        " where itemID=".(int)$item["itemID"] );
                        $product = db_fetch_row( $q1 );
                        if ( $product["productID"] != null &&
                                         trim($product["productID"]) != "" )
                        {
                                if ( $increase )
                                {
                                        db_query( "update ".PRODUCTS_TABLE." set items_sold=items_sold + ".(int)$Quantity.
                                                                " where productID=".(int)$product["productID"] );
                                }
                                else
                                {
                                        db_query( "update ".PRODUCTS_TABLE." set items_sold=items_sold - ".(int)$Quantity.
                                                                " where productID=".(int)$product["productID"] );
                                }
                        }
                }
        }

        // *****************************************************************************
        // Purpose        set order status to order
        // Inputs
        // Remarks
        // Returns  status ID
        function ostSetOrderStatusToOrder( $orderID, $statusID, $comment = '', $notify = 0 )
        {
                $q1 = db_query("select statusID from ".ORDERS_TABLE." where orderID=".(int)$orderID);
                $row = db_fetch_row( $q1 );
                $pred_statusID = $row["statusID"];

                if ( (int)$pred_statusID == (int)$statusID )
                        return;

                db_query("update ".ORDERS_TABLE." set statusID=".(int)$statusID." where orderID=".(int)$orderID);

                if($statusID == CONF_COMPLETED_ORDER_STATUS) affp_addCommissionFromOrder($orderID);

                //update product 'in stock' quantity
                if ( $pred_statusID != ostGetCanceledStatusId() &&
                                        $statusID == ostGetCanceledStatusId() )
                        _changeIn_stock( $orderID, true );
                else if (
                        $pred_statusID == ostGetCanceledStatusId() &&
                                        $statusID != ostGetCanceledStatusId() )
                        _changeIn_stock( $orderID, false );

                //update sold counter
                if ( $pred_statusID != CONF_COMPLETED_ORDER_STATUS &&
                                        $statusID == CONF_COMPLETED_ORDER_STATUS )
                        _changeSOLD_counter( $orderID, true );
                else if (
                        $pred_statusID == CONF_COMPLETED_ORDER_STATUS &&
                                        $statusID != CONF_COMPLETED_ORDER_STATUS )
                        _changeSOLD_counter( $orderID, false );

                stChangeOrderStatus($orderID, $statusID, $comment, $notify);
        }

?><?php
#####################################
# ShopCMS: Скрипт интернет-магазина
# Copyright (c) by ADGroup
# http://shopcms.ru
#####################################





// *****************************************************************************
// Purpose  delete payment method
// Inputs
// Remarks
// Returns  nothing
function payDeletePaymentMethod( $PID )
{
        db_query("delete from ".PAYMENT_TYPES_TABLE." where PID=".(int)$PID);
}


// *****************************************************************************
// Purpose  get payment methods by module
// Inputs
// Remarks
// Returns
function payGetPaymentMethodsByModule( $paymentModule )
{
        $moduleID = $paymentModule->get_id();
        if ( $moduleID == "" ) return array();
        $q = db_query("select PID, Name, description, Enabled, sort_order, ".
                        " email_comments_text, module_id, calculate_tax ".
                        " from ".
                        PAYMENT_TYPES_TABLE." where module_id=".(int)$moduleID );
        $data = array();
        while( $row = db_fetch_row($q) ) $data[] = $row;
        return $data;
}


// *****************************************************************************
// Purpose  get payment module by ID
// Inputs
// Remarks
// Returns
function payGetPaymentModuleById( $PID, $paymentModulesFiles )
{
        $paymentModules = modGetModules( $paymentModulesFiles );
        $currentPaymentModule = null;
        foreach( $paymentModules as $paymentModule )
        {
                if ( (int)$paymentModule->get_id()==(int)$PID )
                {
                        $currentPaymentModule = $paymentModule;
                        break;
                }
        }
        return $currentPaymentModule;
}


// *****************************************************************************
// Purpose  get payment method by ID
// Inputs
// Remarks
// Returns
function payGetPaymentMethodById( $PID )
{
         $q = db_query("select PID, Name, description, Enabled, sort_order, ".
                        " email_comments_text, module_id, calculate_tax, module_id ".
                        " from ".PAYMENT_TYPES_TABLE." where PID=".(int)$PID);
         $row=db_fetch_row($q);
         return $row;
}


// *****************************************************************************
// Purpose  get all payment methods
// Inputs
// Remarks
// Returns  nothing
function payGetAllPaymentMethods( $enabledOnly = false )
{
        $whereClause = "";
        if ( $enabledOnly ) $whereClause = " where Enabled=1 ";
        $q = db_query("select PID, Name, description, Enabled, sort_order,  ".
                        " email_comments_text, module_id, calculate_tax from ".
                        PAYMENT_TYPES_TABLE." ".$whereClause.
                        " order by sort_order");
        $data = array();
        while( $row = db_fetch_row($q) )
        {
                $row["ShippingMethodsToAllow"] = _getShippingMethodsToAllow( $row["PID"] );
                $data[] = $row;
        }
        return $data;
}


// *****************************************************************************
// Purpose  get all installed payment modules
// Inputs
// Remarks
// Returns  nothing
function payGetInstalledPaymentModules()
{
        $moduleFiles = GetFilesInDirectory( "core/modules/payment", "php" );
        $payment_modules = array();
        foreach( $moduleFiles as $fileName )
        {
                $className = GetClassName( $fileName );
                if(!$className)continue;
                eval( "\$payment_module = new ".$className."();" );
                if ( $payment_module->is_installed() )
                        $payment_modules[] = $payment_module;
        }
        return $payment_modules;
}


// *****************************************************************************
// Purpose  add payment method
// Inputs
// Remarks
// Returns  nothing
function payAddPaymentMethod( $Name, $description, $Enabled, $sort_order,
                                $email_comments_text, $module_id, $calculate_tax )
{
        db_query("insert into ".PAYMENT_TYPES_TABLE.
        " ( Name, description, Enabled, calculate_tax, sort_order, email_comments_text, module_id  ) values".
        " ( '".xToText($Name)."', '".xEscSQL($description)."', ".(int)$Enabled.", ".(int)$calculate_tax.", ".(int)$sort_order.", '".xEscSQL($email_comments_text)."', ".(int)$module_id." )");
        return db_insert_id();
}


// *****************************************************************************
// Purpose  update payment method
// Inputs
// Remarks
// Returns  nothing
function payUpdatePaymentMethod(
                                $PID, $Name, $description, $Enabled, $sort_order,
                                $module_id, $email_comments_text, $calculate_tax )
{
        db_query("update ".PAYMENT_TYPES_TABLE." set".
                " Name='".xToText($Name)."', description='".xEscSQL($description)."', email_comments_text='".xEscSQL($email_comments_text)."', ".
                " Enabled=".(int)$Enabled.", module_id=".(int)$module_id.", sort_order=".(int)$sort_order.", calculate_tax = ".(int)$calculate_tax.
                " where PID=".(int)$PID);
}

// *****************************************************************************
// Purpose
// Inputs
// Remarks
// Returns  nothing
function payResetPaymentShippingMethods( $PID )
{
        db_query("delete from ".SHIPPING_METHODS_PAYMENT_TYPES_TABLE." where PID=".(int)$PID);
}


// *****************************************************************************
// Purpose
// Inputs
// Remarks
// Returns  nothing
function _getShippingMethodsToAllow( $PID )
{
        $res = array();
        $shipping_methods = shGetAllShippingMethods();
        for($i=0; $i<count($shipping_methods); $i++)
        {
                $q = db_query("select count(*) from ".SHIPPING_METHODS_PAYMENT_TYPES_TABLE.
                                " where SID=".(int)$shipping_methods[$i]["SID"]." AND ".
                                " PID=".(int)$PID);
                $row = db_fetch_row($q);
                $item["SID"] = $shipping_methods[$i]["SID"];
                $item["allow"] = $row[0];
                $item["name"]  = $shipping_methods[$i]["Name"];
                $res[] = $item;
        }
        return $res;
}

// *****************************************************************************
// Purpose
// Inputs
// Remarks
// Returns  nothing
function paySetPaymentShippingMethod( $PID, $SID )
{
        db_query( "insert into ".SHIPPING_METHODS_PAYMENT_TYPES_TABLE." ( PID, SID ) ".
                        " values( ".(int)$PID.", ".(int)$SID." )" );
}



// *****************************************************************************
// Purpose
// Inputs
// Remarks
// Returns  nothing
function payPaymentMethodIsExist( $paymentMethodID )
{
        $q_count = db_query( "select count(*) from ".PAYMENT_TYPES_TABLE.
                        " where PID=".(int)$paymentMethodID." AND Enabled=1" );
        $count = db_fetch_row( $q_count );
        $count = $count[0];
        return ( $count != 0 );
}

/**
 * Return url for transaction result
 *
 * @param string $_Type - success or failure
 * @return string
 */
function getTransactionResultURL($_Type){

        $scURL = trim( CONF_FULL_SHOP_URL );
        $scURL = str_replace("http://",  "", $scURL);
        $scURL = str_replace("https://", "", $scURL);
        $scURL = "http://".$scURL;
        return set_query('&transaction_result='.$_Type, $scURL);
}
?><?php
  #####################################
  # ShopCMS: Скрипт интернет-магазина
  # Copyright (c) by ADGroup
  # http://shopcms.ru
  #####################################


  // *****************************************************************************
  // Purpose        gets pictures by product
  // Inputs   $productID - product ID
  // Remarks
  // Returns        array of item
  //                                each item consits of
  //                                "photoID"                        - photo ID
  //                                "productID"                        - product ID
  //                                "filename"                        - conventional photo filename
  //                                "thumbnail"                        - thumbnail photo filename
  //                                "enlarged"                        - enlarged photo filename
  //                                "default_picture"        - 1 if default picture, otherwise 0
  function GetPictures($productID)
  {
      $q = db_query("select photoID, productID, filename, thumbnail, enlarged from ".PRODUCT_PICTURES.
          " where productID=".(int)$productID);
      $q2 = db_query("select default_picture from ".PRODUCTS_TABLE." where productID=".(int)$productID);
      $product = db_fetch_row($q2);
      $default_picture = $product[0];
      $res = array();
      while ($row = db_fetch_row($q))
      {
          if ((string )$row["photoID"] == (string )$default_picture) $row["default_picture"] = 1;
          else  $row["default_picture"] = 0;
          $res[] = $row;
      }
      return $res;
  }


  // *****************************************************************************
  // Purpose        deletes three pictures (filename, thumbnail, enlarged) for product
  // Inputs   $photoID - picture ID ( PRODUCT_PICTURES table )
  // Remarks        $photoID identifier is corresponded three pictures ( see PRODUCT_PICTURES
  //                                table in database_structure.xml )
  // Returns        nothing
  function DeleteThreePictures($photoID)
  {
      $q = db_query("select filename, thumbnail, enlarged, productID from ".PRODUCT_PICTURES." where photoID=".(int)$photoID);
      if ($picture = db_fetch_row($q))
      {
          if ($picture["filename"] != "" && $picture["filename"] != null)
              if (file_exists("data/small/".$picture["filename"])) unlink("data/small/".$picture["filename"]);

          if ($picture["thumbnail"] != "" && $picture["thumbnail"] != null)
              if (file_exists("data/medium/".$picture["thumbnail"])) unlink("data/medium/".$picture["thumbnail"]);

          if ($picture["enlarged"] != "" && $picture["enlarged"] != null)
              if (file_exists("data/big/".$picture["enlarged"])) unlink("data/big/".$picture["enlarged"]);

          $q1 = db_query("select default_picture from ".PRODUCTS_TABLE." where productID=".(int)$picture["productID"]);
          if ($product = db_fetch_row($q1))
          {
              if ($product["default_picture"] == $photoID) db_query("update ".PRODUCTS_TABLE." set default_picture=NULL ".
                      " where productID=".(int)$_GET["productID"]);
          }
          db_query("delete from ".PRODUCT_PICTURES." where photoID=".(int)$photoID);
      }
  }

  function DeleteThreePictures2($productID)
  {
      $q = db_query("select filename, thumbnail, enlarged from ".PRODUCT_PICTURES." where productID=".(int)$productID);
      while ($picture = db_fetch_row($q))
      {
          if ($picture["filename"] != "" && $picture["filename"] != null)
              if (file_exists("data/small/".$picture["filename"])) unlink("data/small/".$picture["filename"]);

          if ($picture["thumbnail"] != "" && $picture["thumbnail"] != null)
              if (file_exists("data/medium/".$picture["thumbnail"])) unlink("data/medium/".$picture["thumbnail"]);

          if ($picture["enlarged"] != "" && $picture["enlarged"] != null)
              if (file_exists("data/big/".$picture["enlarged"])) unlink("data/big/".$picture["enlarged"]);

          db_query("delete from ".PRODUCT_PICTURES." where productID=".(int)$productID);
      }
  }

  // *****************************************************************************
  // Purpose        deletes thumbnail picture for product
  // Inputs   $photoID - picture ID ( see PRODUCT_PICTURES table )
  // Remarks        $photoID identifier is corresponded three pictures ( see PRODUCT_PICTURES
  //                                table in database_structure.xml ), but this function delelete only thumbnail
  //                                        picture from server and set thumbnail column value to ''
  // Returns        nothing
  function DeleteThumbnailPicture($photoID)
  {
      $q = db_query("select thumbnail from ".PRODUCT_PICTURES." where photoID=".(int)$photoID);
      if ($thumbnail = db_fetch_row($q))
      {
          if ($thumbnail["thumbnail"] != "" && $thumbnail["thumbnail"] != null)
          {
              if (file_exists("data/medium/".$thumbnail["thumbnail"])) unlink("data/medium/".$thumbnail["thumbnail"]);
          }
          db_query("update ".PRODUCT_PICTURES." set thumbnail=''"." where photoID=".(int)$photoID);
      }
  }


  // *****************************************************************************
  // Purpose        deletes enlarged picture for product
  // Inputs   $photoID - picture ID ( see PRODUCT_PICTURES table )
  // Remarks        $photoID identifier is corresponded three pictures ( see PRODUCT_PICTURES
  //                                table in database_structure.xml ), but this function delelete only enlarged
  //                                        picture from server and set thumbnail column value to ''
  // Returns        nothing
  function DeleteEnlargedPicture($photoID)
  {
      $q = db_query("select enlarged from ".PRODUCT_PICTURES." where photoID=".(int)$photoID);
      if ($enlarged = db_fetch_row($q))
      {
          if ($enlarged["enlarged"] != "" && $enlarged["enlarged"] != null)
          {
              if (file_exists("data/big/".$enlarged["enlarged"])) unlink("data/big/".$enlarged["enlarged"]);
          }
          db_query("update ".PRODUCT_PICTURES." set enlarged=''"." where photoID=".(int)$photoID["enlarged"]);
      }
  }


  // *****************************************************************************
  // Purpose        updates filenames
  // Inputs   $fileNames array of        items
  //                                each item consits of
  //                                        "filename"                - normal picture
  //                                        "thumbnail"                - thumbnail picture
  //                                        "enlarged"                - enlarged picture
  //                                key is picture ID ( see PRODUCT_PICTURES  )
  // Remarks
  //                                if $default_picture == -1 then default picture is not set
  // Returns        nothing
  function UpdatePictures($productID, $fileNames, $default_picture)
  {
      foreach ($fileNames as $key => $value)
      {

          db_query("update ".PRODUCT_PICTURES." set filename='".xEscSQL($value["filename"])."', thumbnail='".xEscSQL($value["thumbnail"])."' ,  enlarged='".xEscSQL($value["enlarged"])."' "."where photoID=".(int)$key);
      }
      if ($default_picture != -1) db_query("update ".PRODUCTS_TABLE." set default_picture = ".xEscSQL($default_picture)." where productID=".(int)$productID);
  }


  function UpdatePicturesUpload($productID, $fileNames, $default_picture)
  {
      foreach ($fileNames as $key => $value)
      {

          $new_filename = Rendernames("ufilenameu_".$key,"data/small/");
          $new_thumbnail = Rendernames("uthumbnailu_".$key,"data/medium/");
          $new_enlarged = Rendernames("uenlargedu_".$key,"data/big/");

          if ($new_filename != "" && $new_filename != null)
          {
              if (CONF_PHOTO_RESIZE) Renderimage($new_filename, CONF_PHOTO_WIDTH1,"data/small/");
              if (CONF_PUT_WATERMARK) Renderwatermark($new_filename,"data/small/");
              $q = db_query("select filename from ".PRODUCT_PICTURES." where photoID=".(int)$key);
              if ($filenamed = db_fetch_row($q))
                  if ($filenamed[0] != "" && $filenamed[0] != null)
                  {
                      if (file_exists("data/small/".$filenamed[0])) unlink("data/small/".$filenamed[0]);
                  }
              db_query("update ".PRODUCT_PICTURES." set filename='".xEscSQL($new_filename)."' where photoID=".(int)$key);
          }
          if ($new_thumbnail != "" && $new_thumbnail != null)
          {
              if (CONF_PHOTO_RESIZE) Renderimage($new_thumbnail, CONF_PHOTO_WIDTH2,"data/medium/");
              if (CONF_PUT_WATERMARK) Renderwatermark($new_thumbnail,"data/medium/");
              $q = db_query("select thumbnail from ".PRODUCT_PICTURES." where photoID=".(int)$key);
              if ($thumbnaild = db_fetch_row($q))
                  if ($thumbnaild[0] != "" && $thumbnaild[0] != null)
                  {
                      if (file_exists("data/medium/".$thumbnaild[0])) unlink("data/medium/".$thumbnaild[0]);
                  }
              db_query("update ".PRODUCT_PICTURES." set thumbnail='".xEscSQL($new_thumbnail)."' where photoID=".(int)$key);
          }
          if ($new_enlarged != "" && $new_enlarged != null)
          {
              if (CONF_PHOTO_RESIZE) Renderimage($new_enlarged, CONF_PHOTO_WIDTH3,"data/big/");
              if (CONF_PUT_WATERMARK) Renderwatermark($new_enlarged,"data/big/");
              $q = db_query("select enlarged from ".PRODUCT_PICTURES." where photoID=".(int)$key);
              if ($enlargedd = db_fetch_row($q))
                  if ($enlargedd[0] != "" && $enlargedd[0] != null)
                  {
                      if (file_exists("data/big/".$enlargedd[0])) unlink("data/big/".$enlargedd[0]);
                  }
              db_query("update ".PRODUCT_PICTURES." set enlarged='".xEscSQL($new_enlarged)."' where photoID=".(int)$key);
          }
      }

      if ($default_picture != -1) db_query("update ".PRODUCTS_TABLE." set default_picture = ".xEscSQL($default_picture)." where productID=".(int)$productID);
  }


  // *****************************************************************************
  // Purpose        adds new picture
  // Inputs        $filename, $thumbnail, $enlarged - keys of item in $_FILES
  //                                corresponded to these file names
  //                        $productID - product ID
  //                        $default_picture - default picture ID
  // Remarks
  //                        if $new_filename == "" then function does not something
  //                        if $default_picture == -1 then default picture is set to new inserted
  //                                        item to PRODUCT_PICTURES
  // Returns        nothing


  function AddNewPictures($productID, $filename, $thumbnail, $enlarged, $default_picture)
  {
      if (isset($_FILES[$filename]) && $_FILES[$filename]["name"] && $_FILES[$filename]["size"] > 0)
      {

          // BEGIN Patch make thumbnails from single file
          // by http://trickywebs.org.ua
          $new_filename = Rendernames($filename,"data/small/");

          if(UPLOAD_ERR_NO_FILE == $_FILES[$thumbnail]['error']) {
            $_FILES[$thumbnail] = $_FILES[$filename];
            $thumbnail = $filename;
            copy('data/small/'.$new_filename, $_FILES[$thumbnail]['tmp_name']);
          }
          $new_thumbnail = Rendernames($thumbnail,"data/medium/");

          if(UPLOAD_ERR_NO_FILE == $_FILES[$enlarged]['error']) {
            $_FILES[$enlarged] = $_FILES[$filename];
            $enlarged = $filename;
            copy('data/small/'.$new_filename, $_FILES[$thumbnail]['tmp_name']);
          }
          $new_enlarged = Rendernames($enlarged,"data/big/");
          // END Patch make thumbnails from single file

          if ($new_filename != "")
          {
              db_query("insert into ".PRODUCT_PICTURES."(productID, filename, thumbnail, enlarged)".
                  "  values( ".(int)$productID.", '".xEscSQL($new_filename)."', '".xEscSQL($new_thumbnail).
                  "', '".xEscSQL($new_enlarged)."' ) ");

              if (CONF_PHOTO_RESIZE)
              {

                  if ($new_filename != "") Renderimage($new_filename, CONF_PHOTO_WIDTH1,"data/small/");
                  if ($new_thumbnail != "") Renderimage($new_thumbnail, CONF_PHOTO_WIDTH2,"data/medium/");
                  if ($new_enlarged != "") Renderimage($new_enlarged, CONF_PHOTO_WIDTH3,"data/big/");
              }

              if (CONF_PUT_WATERMARK)
              {

                  if ($new_filename != "") Renderwatermark($new_filename,"data/small/");
                  if ($new_thumbnail != "") Renderwatermark($new_thumbnail,"data/medium/");
                  if ($new_enlarged != "") Renderwatermark($new_enlarged,"data/big/");
              }

              if ($default_picture == -1)
              {
                  $default_pictureID = db_insert_id();
                  db_query("update ".PRODUCTS_TABLE." set default_picture = ".$default_pictureID." where productID=".(int)$productID);
              }
          }
      }
  }

  function Renderimages()
  {

      set_time_limit(0);

      $q = db_query("select filename, thumbnail, enlarged FROM ".PRODUCT_PICTURES);

      while ($row = db_fetch_row($q))
      {
          if (strlen($row["filename"]) > 0 && file_exists("data/small/".$row["filename"])) Renderimage($row["filename"],CONF_PHOTO_WIDTH1,"data/small/");
          if (strlen($row["thumbnail"]) > 0 && file_exists("data/medium/".$row["thumbnail"])) Renderimage($row["thumbnail"],CONF_PHOTO_WIDTH2,"data/medium/");
          if (strlen($row["enlarged"]) > 0 && file_exists("data/big/".$row["enlarged"])) Renderimage($row["enlarged"],CONF_PHOTO_WIDTH3,"data/big/");
      }
  }

  function Renderwatermarks()
  {

      set_time_limit(0);

      $q = db_query("select filename, thumbnail, enlarged FROM ".PRODUCT_PICTURES);

      while ($row = db_fetch_row($q))
      {
          if (strlen($row["filename"]) > 0 && file_exists("data/small/".$row["filename"])) Renderwatermark($row["filename"],"data/small/");
          if (strlen($row["thumbnail"]) > 0 && file_exists("data/medium/".$row["thumbnail"])) Renderwatermark($row["thumbnail"],"data/medium/");
          if (strlen($row["enlarged"]) > 0 && file_exists("data/big/".$row["enlarged"])) Renderwatermark($row["enlarged"],"data/big/");
      }
  }

  // *****************************************************************************
  // Purpose        gets thumbnail file name
  // Inputs        $productID - product ID
  // Remarks
  // Returns        file name, it is not full path
  function GetThumbnail($productID)
  {
      $q = db_query("select default_picture from ".PRODUCTS_TABLE." where productID=".(int)$productID);
      if ($product = db_fetch_row($q))
      {
          $q2 = db_query("select filename from ".PRODUCT_PICTURES." where photoID=".(int)$product["default_picture"]." and productID=".(int)$productID);
          if ($picture = db_fetch_row($q2))
          {
              if (file_exists("data/small/".$picture["filename"]) && strlen($picture["filename"]) > 0)
                      return $picture["filename"];
          }
      }
      return "";
  }


  function GetPictureCount($productID)
  {
      $count_pict = db_query("select COUNT(*) from ".PRODUCT_PICTURES." where productID=".(int)$productID." AND filename!=''");
      $count_pict_row = db_fetch_row($count_pict);
      return $count_pict_row[0];
  }

  function GetThumbnailCount($productID)
  {
      $count_pict = db_query("select COUNT(*) from ".PRODUCT_PICTURES." where productID=".(int)$productID." AND thumbnail!=''");
      $count_pict_row = db_fetch_row($count_pict);
      return $count_pict_row[0];
  }

  function GetEnlargedPictureCount($productID)
  {
      $count_pict = db_query("select COUNT(*) from ".PRODUCT_PICTURES." where productID=".(int)$productID." AND enlarged!=''");
      $count_pict_row = db_fetch_row($count_pict);
      return $count_pict_row[0];
  }

  function Renderimage($tempname, $mode, $folder)
  {
      include_once ('core/asido/class.asido.php');
      asido::driver('gd');

      if ($mode > 0)
      {
          $i = asido::image($folder.$tempname, $folder.$tempname);
          asido::fit($i, $mode, $mode);
          $i->save(ASIDO_OVERWRITE_ENABLED);
      }
  }
  
  function Renderwatermark($tempname, $folder)
  {


      include_once ('core/asido/class.asido.php');
      asido::driver('gd');

      if (CONF_PUT_WATERMARK && file_exists("data/".CONF_WATERMARK_IMAGE))
      {
          $i = asido::image($folder.$tempname, $folder.$tempname);
          asido::watermark($i, "data/".CONF_WATERMARK_IMAGE, ASIDO_WATERMARK_BOTTOM_CENTER, ASIDO_WATERMARK_SCALABLE_ENABLED);
          $i->save(ASIDO_OVERWRITE_ENABLED);
      }
  }

  // BEGIN Patch make thumbnails from single file
  // by http://trickywebs.org.ua
  function Rendernames($tempname, $folder)
  {
      $new_tempname = "";

      if (isset($_FILES[$tempname]) && $_FILES[$tempname]["size"] > 0)
      {
          $picture_name = strtolower(str_replace(" ", "_", $_FILES[$tempname]["name"]));
          $pos = strrpos($picture_name, ".");
          $name = substr($picture_name, 0, $pos);
          $ext = substr($picture_name, $pos + 1);

          if (file_exists($folder.$picture_name))
          {
              $taskDone = false;
              for ($i = 1; (($i < 500) && ($taskDone == false)); $i++)
              {
                  if (!file_exists($folder.$name."_".$i.".".$ext))
                  {
                      if (is_uploaded_file($_FILES[$tempname]['tmp_name']))
                      {
                          if (move_uploaded_file($_FILES[$tempname]['tmp_name'], $folder.$name."_".
                              $i.".".$ext))
                          {
                              SetRightsToUploadedFile($folder.$name."_".$i.".".$ext);
                              $new_tempname = $name."_".$i.".".$ext;
                          }
                      } else {
                          if (rename($_FILES[$tempname]['tmp_name'], $folder.$name."_".$i.".".$ext))
                          {
                              SetRightsToUploadedFile($folder.$name."_".$i.".".$ext);
                              $new_tempname = $name."_".$i.".".$ext;
                          }
                      }
                      $taskDone = true;
                  }
              }

          }
          else
          {
              if (is_uploaded_file($_FILES[$tempname]['tmp_name']))
              {
                  if (move_uploaded_file($_FILES[$tempname]['tmp_name'], $folder.$picture_name))
                  {
                      SetRightsToUploadedFile($folder.$picture_name);
                      $new_tempname = $picture_name;
                  }
              } else {
                  if (rename($_FILES[$tempname]['tmp_name'], $folder.$picture_name))
                  {
                      SetRightsToUploadedFile($folder.$picture_name);
                      $new_tempname = $picture_name;
                  }
              }
          }
      }
      return $new_tempname;
  }
  // END Patch make thumbnails from single file
?>
<?php
#####################################
# ShopCMS: Скрипт интернет-магазина
# Copyright (c) by ADGroup
# http://shopcms.ru
#####################################

// При ошибке в sql_placeholder_ex() возвращается запрос с
// указанным ниже префиксом.
@define("PLACEHOLDER_ERROR_PREFIX", "ERROR: ");

// function sql_compile_placeholder(string $tmpl)
// Разбирает шаблон запроса и сохраняет положения всех
// placeholder-ов в нем для дальнейшей быстрой подстановки.
// Возвращает структуру вида:
// list(
//   list(
//     $key,    // имя placeholder-а
//     $type,   // '@'|'%'|'#'|''
//     $start,  // положение placeholder-а
//     $length  // длина placeholder-а
//   ),
//   $tmpl,     // исходный шаблон запроса
//   $has_named // есть ли в шаблоне именованный placeholder?
// )
function sql_compile_placeholder($tmpl) {
  $compiled  = array();
  $p         = 0;  // текущая позиция в строке
  $i         = 0;  // счетчик placeholder-ов
  $has_named = false;
  while (false !== ($start = $p = strpos($tmpl, "?", $p))) {
    // Определяем тип placeholder-а.
    switch ($c = substr($tmpl, ++$p, 1)) {
      case '%': case '@': case '#': case '&':
        $type = $c; ++$p; break;
      default:
        $type = ''; break;
    }
    // Проверяем, именованный ли это placeholder: "?keyname"
    if (preg_match('/^((?:[^\s[:punct:]]|_)+)/', substr($tmpl, $p), $pock)) {
      $key = $pock[1];
      if ($type != '#') $has_named = true;
      $p += strlen($key);
    } else {
      $key = $i;
      if ($type != '#') $i++;
    }
    // Сохранить запись о placeholder-е.
    $compiled[] = array($key, $type, $start, $p - $start);
  }
  return array($compiled, $tmpl, $has_named);
}


// bool sql_placeholder_ex(mixed $tmpl, array $args, string &$errormsg)
//
// Заменяет все placeholder-ы в $tmpl на их SQL-экранированные значения
// из $args. При ошибке сохраняет диагностическое сообщение в $errormsg.
//
// Различные типы placeholder-ов:
//   ?  - заменяется на ОДНО скалярное значение.
//   ?@ - заменяется на СПИСОК: 'a', 'b', ... (например, удобно
//        использовать в запросе "SELECT ... WHERE id IN (?@)")
//   ?% - заменяется на список пар ключ=значение: k1='v1', k2='v2', ...
//        (удобно использовать в запросах "UPDATE ... SET ?%")
//
// Placeholder-ы могут быть именованными: их имя можно указывать сразу
// после спецификатора типа, например: "?k", "?@k", "?%k".
//
// Параметр $tmpl может содержать не только текстовое представление
// шаблона, но и результат работы функции sql_compile_placeholder().
// Это удобно, если нужно несколько раз выполнить SQL-запрос, имеющий
// один и тот же шаблон, но разные параметры.
//
// Если в шаблоне есть хотя бы один именованный placeholder,
// $args должен содержать список из ЕДИНСТВЕННОГО элемента. Этот
// элемент сам является ассоциативным массивом, содержащим имена
// placeholder-ов и соответствующие им значения.
//
// Если при подстановке  возникнут ошибки (например, несоответствие
// типов placeholder-а и подставляемого значения, недопустимое имя
// или номер placeholder-а и т.д.), в результирующий запрос вместо
// значения placeholder-а вставляется диагностическое сообщение.
// При этом функция возвращает false, а получившийся "фальшивый"
// запрос помещается в переменную $errormsg.
function sql_placeholder_ex($tmpl, $args, &$errormsg) {
  // Запрос уже разобран?.. Если нет, разбираем.
  if (is_array($tmpl)) {
    $compiled = $tmpl;
  } else {
    $compiled  = sql_compile_placeholder($tmpl);
  }

  list ($compiled, $tmpl, $has_named) = $compiled;

  // Если есть хотя бы один именованный placeholder, используем
  // первый аргумент в качестве ассоциативного массива.
  if ($has_named) $args = @$args[0];

  // Выполняем все замены в цикле.
  $p   = 0;       // текущее положение в строке
  $out = '';      // результирующая строка
  $error = false; // были ошибки?

  foreach ($compiled as $num=>$e) {
    list ($key, $type, $start, $length) = $e;

    // Pre-string.
    $out .= substr($tmpl, $p, $start - $p);
    $p = $start + $length;

    $repl = '';   // текст для замены текущего placeholder-а
    $errmsg = ''; // сообщение об ошибке для этого placeholder-а
    do {
      // Это placeholder-константа?
      if ($type === '#') {
        $repl = @constant($key);
        if (NULL === $repl)
          $error = $errmsg = "UNKNOWN_CONSTANT_$key";
        break;
      }
      // Обрабатываем ошибку.
      if (!isset($args[$key])) {
        $error = $errmsg = "UNKNOWN_PLACEHOLDER_$key";
        break;
      }
      // Вставляем значение в соответствии с типом placeholder-а.
      $a = $args[$key];
      if ($type === '') {
        // Скалярный placeholder.
        if (is_array($a)) {
          $error = $errmsg = "NOT_A_SCALAR_PLACEHOLDER_$key";
          break;
        }
        $repl = preg_match('/^\d+$/', $a)? $a : "'".addslashes($a)."'";
        break;
      }
      // Иначе это массив или список.
      if (!is_array($a)) {
        $error = $errmsg = "NOT_AN_ARRAY_PLACEHOLDER_$key";
        break;
      }
      if ($type === '@') {
        // Это список.
        foreach ($a as $v)
          $repl .= ($repl===''? "" : ",")."'".addslashes($v)."'";
      } elseif ($type === '%') {
        // Это набор пар ключ=>значение.
        $lerror = array();
        foreach ($a as $k=>$v) {
          if (!is_string($k)) {
            $lerror[$k] = "NOT_A_STRING_KEY_{$k}_FOR_PLACEHOLDER_$key";
          } else {
            $k = preg_replace('/[^a-zA-Z0-9_]/', '_', $k);
          }
          $repl .= ($repl===''? "" : ", ").$k."='".@addslashes($v)."'";
        }
        // Если была ошибка, составляем сообщение.
        if (count($lerror)) {
          $repl = '';
          foreach ($a as $k=>$v) {
            if (isset($lerror[$k])) {
              $repl .= ($repl===''? "" : ", ").$lerror[$k];
            } else {
              $k = preg_replace('/[^a-zA-Z0-9_-]/', '_', $k);
              $repl .= ($repl===''? "" : ", ").$k."=?";
            }
          }
          $error = $errmsg = $repl;
        }
      } elseif ($type === '&'){

                        // Это список.
                        foreach ($a as $v)
                        $repl .= ($repl===''? "" : ",").'`'.addslashes($v).'`';
      }
    } while (false);
    if ($errmsg) $compiled[$num]['error'] = $errmsg;
    if (!$error) $out .= $repl;
  }
  $out .= substr($tmpl, $p);

  // Если возникла ошибка, переделываем результирующую строку
  // в сообщение об ошибке (расставляем диагностические строки
  // вместо ошибочных placeholder-ов).
  if ($error) {
    $out = '';
    $p   = 0;       // текущая позиция
    foreach ($compiled as $num=>$e) {
      list ($key, $type, $start, $length) = $e;
      $out .= substr($tmpl, $p, $start - $p);
      $p = $start + $length;
      if (isset($e['error'])) {
        $out .= $e['error'];
      } else {
        $out .= substr($tmpl, $start, $length);
      }
    }
    // Последняя часть строки.
    $out .= substr($tmpl, $p);
    $errormsg = $out;
    return false;
  } else {
    $errormsg = false;
    return $out;
  }
}


// function sql_placeholder(mixed $tmpl, $arg1 [,$arg2 ...])
//
// Замечание: см. описание функции sql_placeholder_ex() выше.
//
// Возвращает результирующий запрос после всех подстановок.
// В случае ошибки запрос будет содержать префикс "ERROR: ".
//
// Если во время подстановки произошла ошибка, (например, несоответствие
// типов), вставляет вместо значений placeholder-ов текстовое сообщение
// об ошибке и возвращает запрос в следующем виде:
//   "ERROR: шаблон с проставленными сообщениями".
// Такой запрос, конечно, породит ошибку при попытке своего выполнения.
// Вы также можете проанализировать возвращаенное значение: если оно
// начинается со строки "ERROR: ", подстановка окончилась неудачей.
//
// Вместо того, чтобы использовать массив в качестве второго параметра,
// вы можете передать значения всех неименованных placeholder-ов одно
// за одним.
//
// Если же в шаблоне есть хотя бы один именованный placeholder, функция
// ОБЯЗАНА принимать в точности два параметра, где первый - это шаблон,
// а второй - ассоциативный массив для подстановки значений именованных
// placeholder-ов.
function sql_placeholder() {
  $args = func_get_args();
  $tmpl = array_shift($args);
  $result = sql_placeholder_ex($tmpl, $args, $error);
  if ($result === false) return PLACEHOLDER_ERROR_PREFIX.$error;
  else return $result;
}


// function sql_pholder(mixed $tmpl, $arg1 [,$arg2 ...])
//
// Замечание: см. описание функции sql_placeholder() выше.
//
// Функция работает точно так же, как sql_placeholder(), однако
// в случае ошибки она возвращает false и генерирует предупреждение
// стандартными средствами, используя trigger_error().
function sql_pholder() {
  $args = func_get_args();
  $tmpl = array_shift($args);
  $result = sql_placeholder_ex($tmpl, $args, $error);
  if ($result === false) {
    $error = "Placeholder substitution error. Diagnostics: \"$error\"";
    if (function_exists("debug_backtrace")) {
      $bt = debug_backtrace();
      $error .= " in ".@$bt[0]['file']." on line ".@$bt[0]['line'];
    }
    trigger_error($error, E_USER_WARNING);
    return false;
  }
  return $result;
}

?><?php
#####################################
# ShopCMS: Скрипт интернет-магазина
# Copyright (c) by ADGroup
# http://shopcms.ru
#####################################

function prdProductExists( $productID )
{
        $q = db_query( "select count(*) from ".PRODUCTS_TABLE." where productID=".(int)$productID );
        $row = db_fetch_row($q);
        return ($row[0]!=0);
}

function getcontentprod($productID)
{
         $out = array();
         $cnt = 0;
         $q = db_query("select Owner from ".RELATED_CONTENT_TABLE." where productID=".(int)$productID);
         while ($row = db_fetch_row($q))
         {
                $outpre = $row["Owner"];
                $qh = db_query("select aux_page_name from ".AUX_PAGES_TABLE." where aux_page_ID=".(int)$outpre);
                $rowh = db_fetch_row($qh);
                $out[$cnt][0] = $outpre;
                $out[$cnt][1] = $rowh["aux_page_name"];
                $cnt++;
         }
         return $out;
}

// *****************************************************************************
// Purpose        gets product
// Inputs   $productID - product ID
// Remarks
// Returns        array of fieled value
//                        "name"                                - product name
//                        "product_code"                - product code
//                        "description"                - description
//                        "brief_description"        - short description
//                        "customers_rating"        - product rating
//                        "in_stock"                        - in stock (this parametr is persist if CONF_CHECKSTOCK == 1 )
//                        "option_values"                - array of
//                                        "optionID"                - option ID
//                                        "name"                        - name
//                                        "value"        - option value
//                                        "option_type" - option type
//                        "ProductIsProgram"                - 1 if product is program, 0 otherwise
//                        "eproduct_filename"                - program filename
//                        "eproduct_available_days"        - program is available days to download
//                        "eproduct_download_times"        - attempt count download file
//                        "weight"                        - product weigth
//                        "meta_description"                - meta tag description
//                        "meta_keywords"                        - meta tag keywords
//                        "free_shipping"                        - 1 product has free shipping,
//                                                        0 - otherwise
//                        "min_order_amount"                - minimum order amount
//                        "classID"                        - tax class ID

function GetProduct( $productID)
{
        $q = db_query('select * FROM '.PRODUCTS_TABLE.' WHERE productID='.(int)$productID);

        if ( $product=db_fetch_row($q ) ){

                $product["ProductIsProgram"] =         (trim($product["eproduct_filename"]) != "");
                $sql = 'select pot.optionID,pot.name,povt.option_value,povt.option_value as value,povt.option_type FROM '.PRODUCT_OPTIONS_VALUES_TABLE.' as povt
                        LEFT JOIN '.PRODUCT_OPTIONS_TABLE.' as pot ON pot.optionID=povt.optionID
                        WHERE productID='.(int)$productID.'
                ';
                $Result = db_query($sql);
                $product['option_values'] = array();

                while ($_Row = db_fetch_row($Result)) {

                        $product['option_values'][] = $_Row;
                }

                $product['date_added']=format_datetime( $product['date_added'] );
                $product['date_modified']=format_datetime( $product['date_modified'] );
                return $product;
        }
        return false;
}



// *****************************************************************************
// Purpose        updates product
// Inputs   $productID - product ID
//                                $categoryID                        - category ID ( see CATEGORIES_TABLE )
//                                $name                                - name of product
//                                $Price                                - price of product
//                                $description                - product description
//                                $in_stock                        - stock counter
//                                $customers_rating        - rating
//                                $brief_description  - short product description
//                                $list_price                        - old price
//                                $product_code                - product code
//                                $sort_order                        - sort order
//                                $ProductIsProgram                - 1 if product is program, 0 otherwise
//                                $eproduct_filename                - program filename
//                                $eproduct_available_days        - program is available days to download
//                                $eproduct_download_times        - attempt count download file
//                                $weight                        - product weigth
//                                $meta_description        - meta tag description
//                                $meta_keywords                - meta tag keywords
//                                $free_shipping                - 1 product has free shipping,
//                                                        0 - otherwise
//                                $min_order_amount        - minimum order amount
//                                $classID                - tax class ID
// Remarks
// Returns
function UpdateProduct( $productID,
                                $categoryID, $name, $Price, $description,
                                $in_stock, $customers_rating,
                                $brief_description, $list_price,
                                $product_code, $sort_order,
                                $ProductIsProgram,
                                $eproduct_filename,
                                $eproduct_available_days,
                                $eproduct_download_times,
                                $weight, $meta_description, $meta_keywords,
                                $free_shipping, $min_order_amount, $shipping_freight, $classID, $title, $updateGCV = 1  )
{
        if ( $min_order_amount == 0 )$min_order_amount = 1;

        if ( !$ProductIsProgram ) $eproduct_filename = "";

        if ( !$free_shipping ) $free_shipping = 0;
        else $free_shipping = 1;

        $q = db_query("select eproduct_filename from ".PRODUCTS_TABLE." where productID=".(int)$productID);
        $old_file_name = db_fetch_row( $q );
        $old_file_name = $old_file_name[0];

        if ( $classID == null ) $classID = "NULL";

        if ( $eproduct_filename != "" && $ProductIsProgram)
        {
                if ( trim($_FILES[$eproduct_filename]["name"]) != ""  )
                {
                        if ( trim($old_file_name) != "" && file_exists("core/files/".$old_file_name) )
                                unlink("core/files/$old_file_name");

                        if ( $_FILES[$eproduct_filename]["size"]!=0 )
                                        $r = move_uploaded_file($_FILES[$eproduct_filename]["tmp_name"],
                                                "core/files/".$_FILES[$eproduct_filename]["name"]);
                        $eproduct_filename = trim($_FILES[$eproduct_filename]["name"]);
                        SetRightsToUploadedFile( "core/files/".$eproduct_filename );
                }
                else
                        $eproduct_filename = $old_file_name;
        }
        elseif ($old_file_name != "") unlink("core/files/".$old_file_name);
		
        $s = "UPDATE ".PRODUCTS_TABLE." SET ".
                                "categoryID=".(int)$categoryID.", ".
                                "name='".xToText(trim($name))."', ".
                                "Price=".(double)$Price.", ".
                                "description='".xEscSQL($description)."', ".
                                "in_stock=".(int)$in_stock.", ".
                                "customers_rating=".(float)$customers_rating.", ".
                                "brief_description='".xEscSQL($brief_description)."', ".
                                "list_price=".(double)$list_price.", ".
                                "product_code='".xToText(trim($product_code))."', ".
                                "sort_order=".(int)$sort_order.", ".
                                "date_modified='".xEscSQL(get_current_time())."', ".
                                "eproduct_filename='".xEscSQL($eproduct_filename)."', ".
                                "eproduct_available_days=".(int)$eproduct_available_days.", ".
                                "eproduct_download_times=".(int)$eproduct_download_times.",  ".
                                "weight=".(float)$weight.", meta_description='".xToText(trim($meta_description))."', ".
                                "meta_keywords='".xToText(trim($meta_keywords))."', free_shipping=".(int)$free_shipping.", ".
                                "min_order_amount = ".(int)$min_order_amount.", ".
                                "shipping_freight = ".(double)$shipping_freight.", ".
                                "title = '".xToText(trim($title))."' ";

        if ($classID != null) $s .= ", classID = ".(int)$classID;

        $s .= " where productID=".(int)$productID;
        db_query($s);

        db_query("delete from ".CATEGORIY_PRODUCT_TABLE." where productID = ".(int)$productID." and categoryID = ".(int)$categoryID);

        if ($updateGCV == 1 && CONF_UPDATE_GCV == '1') //update goods count values for categories in case of regular file editing. do not update during import from excel
                update_psCount(1);
}




// *****************************************************************************
// Purpose        sets product file
// Inputs
// Remarks
// Returns
function SetProductFile( $productID, $eproduct_filename )
{
        db_query( "update ".PRODUCTS_TABLE." set eproduct_filename='".xEscSQL($eproduct_filename)."' ".
                        " where productID=".(int)$productID );

}



// *****************************************************************************
// Purpose        adds product
// Inputs
//                                $categoryID                        - category ID ( see CATEGORIES_TABLE )
//                                $name                                - name of product
//                                $Price                                - price of product
//                                $description                - product description
//                                $in_stock                        - stock counter
//                                $brief_description  - short product description
//                                $list_price                        - old price
//                                $product_code                - product code
//                                $sort_order                        - sort order
//                                $ProductIsProgram                - 1 if product is program,
//                                                                        0 otherwise
//                                $eproduct_filename                - program filename ( it is index of $_FILE variable )
//                                $eproduct_available_days        - program is available days
//                                                                        to download
//                                $eproduct_download_times        - attempt count download file
//                                $weight                        - product weigth
//                                $meta_description        - meta tag description
//                                $meta_keywords                - meta tag keywords
//                                $free_shipping                - 1 product has free shipping,
//                                                        0 - otherwise
//                                $min_order_amount        - minimum order amount
//                                $classID                - tax class ID
// Remarks
// Returns
function AddProduct(
                                $categoryID, $name, $Price, $description,
                                $in_stock,
                                $brief_description, $list_price,
                                $product_code, $sort_order,
                                $ProductIsProgram, $eproduct_filename,
                                $eproduct_available_days, $eproduct_download_times,
                                $weight, $meta_description, $meta_keywords,
                                $free_shipping, $min_order_amount, $shipping_freight,
                                $classID, $title, $updateGCV = 1 )
{
        // special symbol prepare
        if ( $free_shipping )
                $free_shipping = 1;
        else
                $free_shipping = 0;

        if ( $classID == null ) $classID = "NULL";

        if ( $min_order_amount == 0 ) $min_order_amount = 1;

        if ( !$ProductIsProgram ) $eproduct_filename = "";

        if ( $eproduct_filename != "" )
        {
                if ( trim($_FILES[$eproduct_filename]["name"]) != ""  )
                {
                        if ( $_FILES[$eproduct_filename]["size"]!=0 )
                                        $r = move_uploaded_file($_FILES[$eproduct_filename]["tmp_name"],
                                                "core/files/".$_FILES[$eproduct_filename]["name"]);
                        $eproduct_filename = trim($_FILES[$eproduct_filename]["name"]);
                        SetRightsToUploadedFile( "core/files/".$eproduct_filename );
                }
        }

        if ( trim($name) == "" ) $name = "?";
        db_query("INSERT INTO ".PRODUCTS_TABLE.
                " ( categoryID, name, description,".
                "        customers_rating, Price, in_stock, ".
                "        customer_votes, items_sold, enabled, ".
                "        brief_description, list_price, ".
                "        product_code, sort_order, date_added, ".
                "         eproduct_filename, eproduct_available_days, ".
                "         eproduct_download_times, ".
                "        weight, meta_description, meta_keywords, ".
                "        free_shipping, min_order_amount, shipping_freight, classID, title ".
                " ) ".
                " VALUES (".
                                (int)$categoryID.",'".
                                xToText(trim($name))."','".
                                xEscSQL($description)."', ".
                                "0, '".
                                (double)$Price."', ".
                                (int)$in_stock.", ".
                                " 0, 0, 1, '".
                                xEscSQL($brief_description)."', '".
                                (double)$list_price."', '".
                                xToText(trim($product_code))."', ".
                                (int)$sort_order.", '".
                                xEscSQL(get_current_time())."',  '".
                                xEscSQL($eproduct_filename)."', ".
                                (int)$eproduct_available_days.", ".
                                (int)$eproduct_download_times.",  ".
                                (float)$weight.", ".
                                "'".xToText(trim($meta_description))."', ".
                                "'".xToText(trim($meta_keywords))."', ".
                                (int)$free_shipping.", ".
                                (int)$min_order_amount.", ".
                                (double)$shipping_freight.", ".
                                (int)$classID.", '".
                                xToText(trim($title))."' ".
                        ");" );
        $insert_id = db_insert_id();
        if ( $updateGCV == 1 && CONF_UPDATE_GCV == '1') update_psCount(1);
        return $insert_id;
}


// *****************************************************************************
// Purpose        deletes product
// Inputs   $productID - product ID
// Remarks
// Returns        true if success, else false otherwise
function DeleteProduct($productID, $updateGCV = 1)
{
        $whereClause = " where productID=".(int)$productID;

        $q = db_query( "select itemID from ".SHOPPING_CART_ITEMS_TABLE." ".$whereClause );
        while( $row=db_fetch_row($q) )
                db_query( "delete from ".SHOPPING_CARTS_TABLE." where itemID=".(int)$row["itemID"] );

        // delete all items for this product
        db_query("update ".SHOPPING_CART_ITEMS_TABLE.
                " set productID=NULL ".$whereClause);

        // delete all product option values
        db_query("delete from ".PRODUCTS_OPTIONS_SET_TABLE.$whereClause);
        db_query("delete from ".PRODUCT_OPTIONS_VALUES_TABLE.$whereClause);

        // delete pictures
        DeleteThreePictures2($productID);

        // delete additional categories records
        db_query("delete from ".CATEGORIY_PRODUCT_TABLE.$whereClause);

        // delete discussions
        db_query("delete from ".DISCUSSIONS_TABLE.$whereClause);

        // delete special offer
        db_query("delete from ".SPECIAL_OFFERS_TABLE.$whereClause);

        // delete related items
        db_query("delete from ".RELATED_PRODUCTS_TABLE.$whereClause );
        db_query("delete from ".RELATED_PRODUCTS_TABLE." where Owner=".(int)$productID);

        // delete product
        db_query("delete from ".PRODUCTS_TABLE.$whereClause);


        if ( $updateGCV == 1 && CONF_UPDATE_GCV == '1') update_psCount(1);

        return true;
}


// *****************************************************************************
// Purpose        deletes all products of category
// Inputs   $categoryID - category ID
// Remarks
// Returns        true if success, else false otherwise
function DeleteAllProductsOfThisCategory($categoryID)
{
        $q=db_query("select productID from ".PRODUCTS_TABLE.
                        " where categoryID=".(int)$categoryID);
        $res=true;
        while( $r=db_fetch_row( $q ) )
        {
                if ( !DeleteProduct( $r["productID"], 0 ) )
                        $res = false;
        }

        if ( CONF_UPDATE_GCV == '1') update_psCount(1);

        return $res;
}


// *****************************************************************************
// Purpose        gets extra parametrs
// Inputs   $productID - product ID
// Remarks
// Returns        array of value extraparametrs
//                                each item of this array has next struture
//                                        first type "option_type" = 0
//                                                "name"                                        - parametr name
//                                                "option_value"                        - value
//                                                "option_type"                        - 0
//                                        second type "option_type" = 1
//                                                "name"                                        - parametr name
//                                                "option_show_times"                - how times does show in client side this
//                                                                                                parametr to select
//                                                "variantID_default"                - variant ID by default
//                                                "values_to_select"                - array of variant value to select
//                                                        each item of "values_to_select" array has next structure
//                                                                "variantID"                        - variant ID
//                                                                "price_surplus"                - to added to price
//                                                                "option_value"                - value
function GetExtraParametrs( $productID ){

        if(!is_array($productID)){

                $ProductIDs = array($productID);
                $IsProducts = false;
        }elseif(count($productID)) {

                $ProductIDs = &$productID;
                $IsProducts = true;
        }else {

                return array();
        }

        $ProductsExtras = array();
        $sql = 'select povt.productID,pot.optionID,pot.name,povt.option_value,povt.option_type,povt.option_show_times, povt.variantID, povt.optionID
                FROM ?#PRODUCT_OPTIONS_VALUES_TABLE as povt LEFT JOIN  ?#PRODUCT_OPTIONS_TABLE as pot ON pot.optionID=povt.optionID
                WHERE povt.productID IN (?@) ORDER BY pot.sort_order, pot.name
        ';
        $Result = db_phquery($sql, $ProductIDs);

        while ($_Row = db_fetch_assoc($Result)) {

                $_Row;
                $b=null;
                if (($_Row['option_type']==0 || $_Row['option_type']==NULL) && strlen( trim($_Row['option_value']))>0){

                        $ProductsExtras[$_Row['productID']][] = array(
                                'option_type' => $_Row['option_type'],
                                'name' => $_Row['name'],
                                'option_value' => $_Row['option_value']
                        );
                }
/**
* @features "Extra options values"
* @state begin
*/
                else if ( $_Row['option_type']==1 ){

                        //fetch all option values variants
                        $sql = 'select povvt.option_value, povvt.variantID, post.price_surplus
                                FROM '.PRODUCTS_OPTIONS_SET_TABLE.' as post
                                LEFT JOIN '.PRODUCTS_OPTIONS_VALUES_VARIANTS_TABLE.' as povvt
                                ON povvt.variantID=post.variantID
                                WHERE povvt.optionID='.$_Row['optionID'].' AND post.productID='.$_Row['productID'].' AND povvt.optionID='.$_Row['optionID'].'
                                ORDER BY povvt.sort_order, povvt.option_value
                        ';
                        $q2=db_query($sql);
                        $_Row['values_to_select']=array();
                        $i=0;
                        while( $_Rowue = db_fetch_assoc($q2)  ){

                                $_Row['values_to_select'][$i]=array();
                                $_Row['values_to_select'][$i]['option_value'] = $_Rowue['option_value'];
                                // if ( $_Rowue['price_surplus'] > 0 )$_Row['values_to_select'][$i]['option_value'] .= ' (+ '.show_price($_Rowue['price_surplus']).')';
                                // elseif($_Rowue['price_surplus'] < 0 )$_Row['values_to_select'][$i]['option_value'] .= ' (- '.show_price(-$_Rowue['price_surplus']).')';

                                $_Row['values_to_select'][$i]['option_valueWithOutPrice'] = $_Rowue['option_value'];
                                $_Row['values_to_select'][$i]['price_surplus'] = show_priceWithOutUnit($_Rowue['price_surplus']);
                                $_Row['values_to_select'][$i]['variantID']=$_Rowue['variantID'];
                                $i++;
                        }
                        $_Row['values_to_select_count'] = count($_Row['values_to_select']);
                        $ProductsExtras[$_Row['productID']][] = $_Row;
                }
                /**
* @features "Extra options values"
* @state end
*/
        }
        if(!$IsProducts){

                if(!count($ProductsExtras))return array();
                else {
                        return $ProductsExtras[$productID];
                }
        }
        return $ProductsExtras;
}


function _setPictures( & $product )
{
        if ( isset($product['default_picture'])&&!is_null($product['default_picture'])&&isset($product['productID']) )
        {
                $pictire=db_query("select filename, thumbnail, enlarged from ".
                                        PRODUCT_PICTURES." where photoID=".(int)$product["default_picture"] );
                $pictire_row=db_fetch_row($pictire);
                $product['picture'] =   file_exists('data/small/'.$pictire_row['filename'])?$pictire_row['filename']:0;
                $product['thumbnail']=  file_exists('data/medium/'.$pictire_row['thumbnail'])?$pictire_row['thumbnail']:0;
                $product['big_picture']=file_exists('data/big/'.$pictire_row['enlarged'])?$pictire_row['enlarged']:0;
        }
}


function GetProductInSubCategories( $callBackParam, &$count_row, $navigatorParams = null )
{

        if ( $navigatorParams != null )
        {
                $offset                        = $navigatorParams["offset"];
                $CountRowOnPage        = $navigatorParams["CountRowOnPage"];
        }
        else
        {
                $offset = 0;
                $CountRowOnPage = 0;
        }

        $categoryID        = $callBackParam["categoryID"];
        $subCategoryIDArray = catGetSubCategories( $categoryID );
        $cond = "";
        foreach( $subCategoryIDArray as $subCategoryID )
        {
                if ( $cond != "" )
                        $cond .= " OR categoryID=".(int)$subCategoryID;
                else
                        $cond .= " categoryID=".(int)$subCategoryID." ";
        }
        $whereClause = "";
        if ( $cond != "" )
                $whereClause = " where ".$cond;

        $result = array();
        if ( $whereClause == "" )
        {
                $count_row = 0;
                return $result;
        }

        $q=db_query("select categoryID, name, brief_description, ".
                         " customers_rating, Price, in_stock, ".
                        " customer_votes, list_price, ".
                        " productID, default_picture, sort_order from ".PRODUCTS_TABLE.
                        " ".$whereClause." order by ".CONF_DEFAULT_SORT_ORDER);
        $i=0;
        while( $row=db_fetch_row($q) )
        {
                if ( ($i >= $offset && $i < $offset + $CountRowOnPage) ||
                                                 $navigatorParams == null  )
                {
                        $row["PriceWithUnit"]                = show_price($row["Price"]);
                        $row["list_priceWithUnit"]         = show_price($row["list_price"]);
                        // you save (value)
                        $row["SavePrice"]                = show_price($row["list_price"]-$row["Price"]);

                        // you save (%)
                        if ($row["list_price"])
                                $row["SavePricePercent"] = ceil(((($row["list_price"]-$row["Price"])/$row["list_price"])*100));

                        _setPictures( $row );

                        $row["product_extra"]=GetExtraParametrs($row["productID"]);
                        $row["PriceWithOutUnit"]= show_priceWithOutUnit( $row["Price"] );
                        $result[] = $row;
                }
                $i++;
        }
        $count_row = $i;
        return $result;
}


// *****************************************************************************
// Purpose        gets all products by categoryID
// Inputs             $callBackParam item
//                        "categoryID"
//                        "fullFlag"
// Remarks
// Returns
function prdGetProductByCategory( $callBackParam, &$count_row, $navigatorParams = null )
{

        if ( $navigatorParams != null )
        {
                $offset                        = $navigatorParams["offset"];
                $CountRowOnPage        = $navigatorParams["CountRowOnPage"];
        }
        else
        {
                $offset = 0;
                $CountRowOnPage = 0;
        }

        $result = array();

        $categoryID        = $callBackParam["categoryID"];
        $fullFlag        = $callBackParam["fullFlag"];
        if ( $fullFlag )
        {
                $conditions = array( " categoryID=".(int)$categoryID." " );
                $q = db_query("select productID from ".
                                CATEGORIY_PRODUCT_TABLE." where  categoryID=".(int)$categoryID);
                while( $products = db_fetch_row( $q ) )
                        $conditions[] = " productID=".(int)$products[0];

                $data = array();
                foreach( $conditions as $cond )
                {
                        $q=db_query("select categoryID, name, brief_description, ".
                                 " customers_rating, Price, in_stock, ".
                                " customer_votes, list_price, ".
                                " productID, default_picture, sort_order, items_sold, enabled, product_code from ".PRODUCTS_TABLE.
                                " where ".$cond." order by ".CONF_DEFAULT_SORT_ORDER);
                        while( $row = db_fetch_row($q) )
                        {
                                $row["PriceWithUnit"]                = show_price($row["Price"]);
                                $row["list_priceWithUnit"]         = show_price($row["list_price"]);
                                // you save (value)
                                $row["SavePrice"]                = show_price($row["list_price"]-$row["Price"]);

                                // you save (%)
                                if ($row["list_price"])
                                        $row["SavePricePercent"] = ceil(((($row["list_price"]-$row["Price"])/$row["list_price"])*100));
                                _setPictures( $row );
                                $row["product_extra"]=GetExtraParametrs($row["productID"]);
                                $row["product_extra_count"]=count($row["product_extra"]);
                                $row["PriceWithOutUnit"]= show_priceWithOutUnit( $row["Price"] );
                                $data[] = $row;
                        }
                }

                function _compare( $row1, $row2 )
                {
                         if ( (int)$row1["sort_order"] == (int)$row2["sort_order"] )
                                return 0;
                         return ((int)$row1["sort_order"] < (int)$row2["sort_order"]) ? -1 : 1;
                }

                usort($data, "_compare");

                $result = array();
                $i = 0;
                $ccdata = count($data);
                for ($s=0; $s<$ccdata; $s++)
                {
                        if ( ($i >= $offset && $i < $offset + $CountRowOnPage) ||
                                        $navigatorParams == null )
                                $result[] = $data[$s];
                        $i++;
                }
                $count_row = $i;
                return $result;
        }
        else
        {
                $q=db_query("select categoryID, name, brief_description, ".
                                " customers_rating, Price, in_stock, ".
                                " customer_votes, list_price, ".
                                " productID, default_picture, sort_order, items_sold, enabled, product_code from ".PRODUCTS_TABLE.
                                " where categoryID=".(int)$categoryID." order by ".CONF_DEFAULT_SORT_ORDER);
                $i=0;
                while( $row=db_fetch_row($q) )
                {
                        if ( ($i >= $offset && $i < $offset + $CountRowOnPage) ||
                                $navigatorParams == null  )
                                $result[] = $row;
                        $i++;
                }
                $count_row = $i;
                return $result;
        }
}




function _getConditionWithCategoryConjWithSubCategories( $condition, $categoryID ) //fetch products from current category and all its subcategories
{
        $new_condition = "";
        $tempcond = "";

        $categoryID_Array = catGetSubCategories( $categoryID );
        $categoryID_Array[] = (int)$categoryID;
	
        foreach( $categoryID_Array as $catID )
        {
                if ( $new_condition != "" )
                        $new_condition .= " OR ";

                $new_condition .= _getConditionWithCategoryConj($tempcond, $catID);

        }
        if ( $condition == "" ) return $new_condition;
        else return $condition." AND (".$new_condition.")";
}


function _getConditionWithCategoryConj( $condition, $categoryID ) //fetch products from current category
{
        $category_condition = "";
        $q = db_query("select productID from ".
                                CATEGORIY_PRODUCT_TABLE." where categoryID=".(int)$categoryID);
        $icounter = 0;
        while( $product = db_fetch_row( $q ) )
        {
                if ( $icounter==0 )
                $category_condition .= " productID IN (";
                if ( $icounter>0 )
                $category_condition .= ",";
                $category_condition .= (int)$product[0];
                $icounter++;
        }
        if ( $icounter>0 ) $category_condition .= ")";

        if ( $condition == "" )
        {
                if ( $category_condition == "" )
                        return "categoryID=".(int)$categoryID;
                else
                        return "(".$category_condition." OR categoryID=".(int)$categoryID.")";
        }
        else
        {
                if ( $category_condition == "" )
                        return $condition." AND categoryID=".(int)$categoryID;
                else
                        return "(".$condition." AND (".$category_condition." OR categoryID=".(int)$categoryID."))";
        }      
}


// *****************************************************************************
// Purpose
// Inputs
//                                $productID - product ID
//                                $template  - array of item
//                                        "optionID"        - option ID
//                                        "value"                - value or variant ID
// Remarks
// Returns        returns true if product matches to extra parametr template
//                        false otherwise
function _testExtraParametrsTemplate( $productID, &$template ){

        # BEGIN ExtraFilter
if (isset($_GET["extrafilter"])){
                global $efTemplate;
                $variants = array();
                $filter_type = array();
                foreach( $efTemplate as $key => $item )
                        if((string)$key != "categoryID" && isset($item["optionID"]))
                                {
                                if (is_array($item['value'])) $variants[$item["optionID"]] = $item['value'];
                                elseif ($item['value']>0) $variants[$item["optionID"]][] = $item['value'];
                                if (isset($item['filter_type'])) $filter_type[$item["optionID"]] = $item['filter_type'];
                                }

                if (!$count = count($variants)) return true;

                $filter = array();
                foreach( $variants as $key => $item )
                 {
                 if (isset($filter_type[$key]))
                         {
                                switch ($filter_type[$key])
                                        {
                                        case '0':
                                                $filter[] = "pos.variantID IN (".implode(",",$item).")";
                                                break;
                                        case '1':
                                                $filter[] = "pos.optionID=".$key." AND povv.option_value LIKE '%".$item[0]."%'";
                                                break;
                                        case '2':
                                                if ($item[2] == 'on') $count--;
                                                else $filter[] = "pos.optionID=".$key." AND FLOOR(povv.option_value)>=".$item[0]." AND CEIL(povv.option_value)<=".$item[1];
                                                break;
                                        case '3':
                                                $filter[] = "pos.variantID IN (".implode(',',$item).")";
                                                break;
                                        }                               
                                }
                        }
                $row=db_fetch_row(db_query("SELECT count(DISTINCT pos.optionID) AS count FROM ".PRODUCTS_OPTIONS_SET_TABLE." AS pos
                                                                        LEFT JOIN ".PRODUCTS_OPTIONS_VALUES_VARIANTS_TABLE." AS povv USING (variantID)
                                                                        WHERE productID=".$productID. " AND (".implode(" OR ",$filter).")"));

                return $row['count'] == $count;
}else{
# END ExtraFilter

        // get category ID
        $categoryID = $template["categoryID"];

        foreach( $template as $key => $item ){

                if( !isset($item["optionID"]) ) continue;

                if((string)$key == "categoryID" ) continue;

                // get value to search
                if ( $item['set_arbitrarily'] == 1 ){

                        $valueFromForm = $item["value"];
                }else{

                        if ( (int)$item["value"] == 0 ) continue;

                        if(!isset($template[$key]['__option_value_from_db'])){

                                $SQL = 'select option_value FROM ?#PRODUCTS_OPTIONS_VALUES_VARIANTS_TABLE WHERE variantID=?
                                ';
                                $option_value = db_fetch_assoc(db_phquery($SQL, (int)$item['value']));
                                $template[$key]['__option_value_from_db'] = $option_value['option_value'];
                        }
                        $valueFromForm = $template[$key]['__option_value_from_db'];
                }

                // get option value
                $SQL = 'select option_value, option_type FROM ?#PRODUCT_OPTIONS_VALUES_TABLE WHERE optionID=? AND productID=?
                ';
                $q = db_phquery($SQL,(int)$item['optionID'],(int)$productID);

                if(!($row=db_fetch_row($q))){

                        if ( trim($valueFromForm) == '' ) continue;
                        else return false;
                }

                $option_value = $row['option_value'];
                $option_type        = $row['option_type'];
                $valueFromDataBase = array();

                if ( $option_type == 0 ){

                        $valueFromDataBase[] = $option_value;
                }else{

                        $SQL = 'select povv.option_value FROM ?#PRODUCTS_OPTIONS_SET_TABLE as pos
                                LEFT JOIN ?#PRODUCTS_OPTIONS_VALUES_VARIANTS_TABLE as povv ON pos.variantID=povv.variantID WHERE pos.optionID=? AND pos.productID=?
                        ';
                        $Result = db_phquery($SQL,(int)$item["optionID"], (int)$productID);
                        while ($Row = db_fetch_assoc($Result)){

                                $valueFromDataBase[] = $Row['option_value'];
                        }
                }

                if ( trim($valueFromForm) != '' ){

                        $existFlag = false;
                        $vcount = count($valueFromDataBase);
                        for ($v=0; $v<$vcount; $v++) {
                                if(strstr(strtolower((string)trim($valueFromDataBase[$v])),strtolower((string)trim($valueFromForm)))){
                                        $existFlag = true;
                                        break;
                                }
                        }
                        if ( !$existFlag ) return false;
                }
        }
        return true;
        # BEGIN ExtraFilter
}
# END ExtraFilter
}




function _deletePercentSymbol( &$str )
{
        $str = str_replace( "%", "", $str );
        return $str;
}


function prdSearchProductByTemplateAdmin($callBackParam, &$count_row, $navigatorParams = null )
{
        // navigator params
        if ( $navigatorParams != null )
        {
                $offset                        = xEscSQL($navigatorParams["offset"]);
                $CountRowOnPage        = xEscSQL($navigatorParams["CountRowOnPage"]);
        }
        else
        {
                $offset = 0;
                $CountRowOnPage = 0;
        }

        if ( isset($callBackParam["extraParametrsTemplate"]) ){

                $replicantExtraParametersTpl = $callBackParam["extraParametrsTemplate"];
        }
        // special symbol prepare
        if ( isset($callBackParam["search_simple"]) )
        {
/*                for( $i=0; $i<count($callBackParam["search_simple"]); $i++ )
                {
                        $callBackParam["search_simple"][$i] = $callBackParam["search_simple"][$i];
                }*/
                _deletePercentSymbol( $callBackParam["search_simple"] );
        }
        if ( isset($callBackParam["name"]) )
        {
                for( $i=0; $i<count($callBackParam["name"]); $i++ )
                        $callBackParam["name"][$i] = xToText(trim($callBackParam["name"][$i]) );
                _deletePercentSymbol( $callBackParam["name"][$i] );
        }
        if ( isset($callBackParam["product_code"]) )
        {
                for( $i=0; $i<count($callBackParam["product_code"]); $i++ )
                {
                        $callBackParam["product_code"][$i] = xToText(trim($callBackParam["product_code"][$i] ));
                }
                _deletePercentSymbol( $callBackParam["product_code"] );
        }

        if ( isset($callBackParam["extraParametrsTemplate"]) )
        {
                foreach( $callBackParam["extraParametrsTemplate"] as $key => $value )
                {
                        if ( is_int($key) )
                        {
                                $callBackParam["extraParametrsTemplate"][$key] = xEscSQL(trim($callBackParam["extraParametrsTemplate"][$key]) );
                                _deletePercentSymbol( $callBackParam["extraParametrsTemplate"][$key] );
                        }
                }
        }


        $where_clause = "";

        if ( isset($callBackParam["search_simple"]) )
        {
                if (!count($callBackParam["search_simple"])) //empty array
                {
                        $where_clause = " where 0";
                }
                else //search array is not empty
                {
                        $sscount = count($callBackParam["search_simple"]);
                        for ($n=0; $n<$sscount; $n++)
                        {
                                if ( $where_clause != "" ) $where_clause .= " AND ";
                                $where_clause .= " ( LOWER(name) LIKE '%".xToText(trim(strtolower($callBackParam["search_simple"][$n])))."%' OR ".
                                                 "   LOWER(description) LIKE '%".xEscSQL(trim(strtolower($callBackParam["search_simple"][$n])))."%' OR ".
                                                 "   LOWER(product_code) LIKE '%".xEscSQL(trim(strtolower($callBackParam["search_simple"][$n])))."%' OR ".
                                                 "   LOWER(brief_description) LIKE '%".xEscSQL(trim(strtolower($callBackParam["search_simple"][$n])))."%' ) ";
                        }

                        if ( $where_clause != "" )
                        {
                                $where_clause = " where categoryID>1 and enabled=1 and ".$where_clause;
                        }
                        else
                        {
                                $where_clause = " where categoryID>1 and enabled=1";
                        }
						

                }

        }
        else
        {

                // "enabled" parameter
                if ( isset($callBackParam["enabled"]) )
                {
                        if ( $where_clause != "" )
                                $where_clause .= " AND ";
                        $where_clause.=" enabled=".(int)$callBackParam["enabled"];
                }

                // take into "name" parameter
                if ( isset($callBackParam["name"]) )
                {
                        foreach( $callBackParam["name"] as $name )
                                if (strlen($name)>0)
                                {
                                        if ( $where_clause != "" )
                                                $where_clause .= " AND ";
                                         $where_clause .= " LOWER(name) LIKE '%".xToText(trim(strtolower($name)))."%' ";
                                }
                }

                // take into "product_code" parameter
                if ( isset($callBackParam["product_code"]) )
                {
                        foreach( $callBackParam["product_code"] as $product_code )
                        {
                                if ( $where_clause != "" )
                                        $where_clause .= " AND ";
                                $where_clause .= " LOWER(product_code) LIKE '%".xToText(trim(strtolower($product_code)))."%' ";
                        }
                }

                // take into "price" parameter
                if ( isset($callBackParam["price"]) )
                {
                        $price = $callBackParam["price"];

                        if ( trim($price["from"]) != "" && $price["from"] != null )
                        {
                                if ( $where_clause != "" )
                                        $where_clause .= " AND ";
                                $from        = ConvertPriceToUniversalUnit( $price["from"] );
                                $where_clause .= " Price>=".(double)$from." ";
                        }
                        if ( trim($price["to"]) != "" && $price["to"] != null )
                        {
                                if ( $where_clause != "" )
                                        $where_clause .= " AND ";
                                $to                = ConvertPriceToUniversalUnit( $price["to"] );
                                $where_clause .= " Price<=".(double)$to." ";
                        }
                }
		
       
        // categoryID
                if ( isset($callBackParam["categoryID"]) )
                {
                        $searchInSubcategories = false;
                        if ( isset($callBackParam["searchInSubcategories"]) )
                        {
                                if ( $callBackParam["searchInSubcategories"] )
                                        $searchInSubcategories = true;
                                else
                                        $searchInSubcategories = false;
                        }

                        if ( $searchInSubcategories )
                        {
                                $where_clause = _getConditionWithCategoryConjWithSubCategories( $where_clause,
                                                                                        $callBackParam["categoryID"] );
                        }
                        else
                        {
                                $where_clause = _getConditionWithCategoryConj( $where_clause,
                                                                                        $callBackParam["categoryID"] );
                        }
                }

                if ( $where_clause != "" )
                        $where_clause = "where ".$where_clause;

        }
        

		
        $order_by_clause = "order by ".CONF_DEFAULT_SORT_ORDER;

        if ( isset($callBackParam["sort"]) )
        {
                if (        $callBackParam["sort"] == "categoryID"                        ||
                                $callBackParam["sort"] == "name"                                ||
                                $callBackParam["sort"] == "brief_description"        ||
                                $callBackParam["sort"] == "in_stock"                        ||
                                $callBackParam["sort"] == "Price"                                ||
                                $callBackParam["sort"] == "customer_votes"                ||
                                $callBackParam["sort"] == "customers_rating"        ||
                                $callBackParam["sort"] == "list_price"                        ||
                                $callBackParam["sort"] == "sort_order"                        ||
                                $callBackParam["sort"] == "items_sold"                        ||
                                $callBackParam["sort"] == "product_code"                ||
                                $callBackParam["sort"] == "shipping_freight"        ||
                                $callBackParam["sort"] == "viewed_times" )
                {
                        $order_by_clause = " order by ".xEscSQL($callBackParam["sort"])." ASC ";
                        if (  isset($callBackParam["direction"]) )
                                if (  $callBackParam["direction"] == "DESC" )
                                        $order_by_clause = " order by ".xEscSQL($callBackParam["sort"])." DESC ";
                }
        }

        $sqlQueryCount = "select count(*) from ".PRODUCTS_TABLE." ".$where_clause;
        $q = db_query( $sqlQueryCount );
        $products_count = db_fetch_row($q);
        $products_count = $products_count[0];
        $limit_clause= (isset($callBackParam["extraParametrsTemplate"]) || !$CountRowOnPage)?"":" LIMIT ".$offset.",".$CountRowOnPage;
        $sqlQuery = "select categoryID, name, brief_description, ".
                                 " customers_rating, Price, in_stock, ".
                                " customer_votes, list_price, ".
                                " productID, default_picture, sort_order, items_sold, enabled, ".
                                " product_code, description, shipping_freight, viewed_times, min_order_amount from ".PRODUCTS_TABLE." ".
                                $where_clause." ".$order_by_clause.$limit_clause;

        $q = db_query( $sqlQuery );
        $result = array();
        $i = 0;

        if ($offset >= 0 && $offset <= $products_count )
        {
                while( $row = db_fetch_row($q) )
                {

                        if ( isset($callBackParam["extraParametrsTemplate"]) ){

                                // take into "extra" parametrs
                                $testResult = _testExtraParametrsTemplate( $row["productID"], $replicantExtraParametersTpl );
                                if ( !$testResult ) continue;
                        }

                        if ( (($i >= $offset || !isset($callBackParam["extraParametrsTemplate"])) && $i < $offset + $CountRowOnPage) ||
                                        $navigatorParams == null  )
                        {
                                $row["PriceWithUnit"]     = show_price($row["Price"]);
                                $row["list_priceWithUnit"]= show_price($row["list_price"]);
                                // you save (value)
                                $row["SavePrice"]         = show_price($row["list_price"]-$row["Price"]);

                                // you save (%)
                                if ($row["list_price"]) $row["SavePricePercent"] = ceil(((($row["list_price"]-$row["Price"])/$row["list_price"])*100));
                                _setPictures( $row );
                                $row["product_extra"]     = GetExtraParametrs( $row["productID"] );
                                $row["product_extra_count"]  = count($row["product_extra"]);
                                $row["PriceWithOutUnit"]  = show_priceWithOutUnit( $row["Price"] );
                                if ( ((double)$row["shipping_freight"]) > 0 ) $row["shipping_freightUC"] = show_price( $row["shipping_freight"] );
                                $row["name"]              = $row["name"];
                                $row["description"]       = $row["description"];
                                $row["brief_description"] = $row["brief_description"];
                                $row["product_code"]      = $row["product_code"];
                                $row["viewed_times"]      = $row["viewed_times"];
                                $row["items_sold"]        = $row["items_sold"];
                                $result[] = $row;
                        }
                        $i++;
                }
        }
        $count_row = isset($callBackParam["extraParametrsTemplate"])?$i:$products_count;
        return $result;
}

// *****************************************************************************
// Purpose        gets all products by categoryID
// Inputs             $callBackParam item
//                                        "search_simple"                                - string search simple
//                                        "sort"                                        - column name to sort
//                                        "direction"                                - sort direction DESC - by descending,
//                                                                                                by ascending otherwise
//                                        "searchInSubcategories" - if true function searches
//                                                product in subcategories, otherwise it does not
//                                        "searchInEnabledSubcategories"        - this parametr is actual when
//                                                                                        "searchInSubcategories" parametr is specified
//                                                                                        if true this function take in mind enabled categories only
//                                        "categoryID"        - is not set or category ID to be searched
//                                        "name"                        - array of name template
//                                        "product_code"                - array of product code template
//                                        "price"                        -
//                                                                array of item
//                                                                        "from"        - down price range
//                                                                        "to"        - up price range
//                                        "enabled"                - value of column "enabled"
//                                                                        in database
//                                        "extraParametrsTemplate"
// Remarks
// Returns
function prdSearchProductByTemplate($callBackParam, &$count_row, $navigatorParams = null )
{
        // navigator params
        if ( $navigatorParams != null )
        {
                $offset                        = xEscSQL($navigatorParams["offset"]);
                $CountRowOnPage        = xEscSQL($navigatorParams["CountRowOnPage"]);
        }
        else
        {
                $offset = 0;
                $CountRowOnPage = 0;
        }

        if ( isset($callBackParam["extraParametrsTemplate"]) ){

                $replicantExtraParametersTpl = $callBackParam["extraParametrsTemplate"];
        }
        // special symbol prepare
        if ( isset($callBackParam["search_simple"]) )
        {
/*                for( $i=0; $i<count($callBackParam["search_simple"]); $i++ )
                {
                        $callBackParam["search_simple"][$i] = $callBackParam["search_simple"][$i];
                }*/
                _deletePercentSymbol( $callBackParam["search_simple"] );
        }
        if ( isset($callBackParam["name"]) )
        {
                for( $i=0; $i<count($callBackParam["name"]); $i++ )
                        $callBackParam["name"][$i] = xToText(trim($callBackParam["name"][$i]) );
                _deletePercentSymbol( $callBackParam["name"][$i] );
        }
        if ( isset($callBackParam["product_code"]) )
        {
                for( $i=0; $i<count($callBackParam["product_code"]); $i++ )
                {
                        $callBackParam["product_code"][$i] = xToText(trim($callBackParam["product_code"][$i] ));
                }
                _deletePercentSymbol( $callBackParam["product_code"] );
        }

        if ( isset($callBackParam["extraParametrsTemplate"]) )
        {
                foreach( $callBackParam["extraParametrsTemplate"] as $key => $value )
                {
                        if ( is_int($key) )
                        {
                                $callBackParam["extraParametrsTemplate"][$key] = xEscSQL(trim($callBackParam["extraParametrsTemplate"][$key]) );
                                _deletePercentSymbol( $callBackParam["extraParametrsTemplate"][$key] );
                        }
                }
        }


        $where_clause = "";

        if ( isset($callBackParam["search_simple"]) )
        {
                if (!count($callBackParam["search_simple"])) //empty array
                {
                        $where_clause = " where 0";
                }
                else //search array is not empty
                {
                        $sscount = count($callBackParam["search_simple"]);
                        for ($n=0; $n<$sscount; $n++)
                        {
                                if ( $where_clause != "" ) $where_clause .= " AND ";
                                $where_clause .= " ( LOWER(name) LIKE '%".xToText(trim(strtolower($callBackParam["search_simple"][$n])))."%' OR ".
                                                 "   LOWER(description) LIKE '%".xEscSQL(trim(strtolower($callBackParam["search_simple"][$n])))."%' OR ".
                                                 "   LOWER(product_code) LIKE '%".xEscSQL(trim(strtolower($callBackParam["search_simple"][$n])))."%' OR ".
                                                 "   LOWER(brief_description) LIKE '%".xEscSQL(trim(strtolower($callBackParam["search_simple"][$n])))."%' ) ";
                        }

                        if ( $where_clause != "" )
                        {
                                $where_clause = " where categoryID>1 and enabled=1 and ".$where_clause;
                        }
                        else
                        {
                                $where_clause = " where categoryID>1 and enabled=1";
                        }
						
		if(CONF_CHECKSTOCK && CONF_SHOW_NULL_STOCK){
            if ( $where_clause != "" )
                $where_clause .= " AND in_stock>0 ";
            else
                $where_clause = "where in_stock>0 ";		
        }
                }

        }
        else
        {

                // "enabled" parameter
                if ( isset($callBackParam["enabled"]) )
                {
                        if ( $where_clause != "" )
                                $where_clause .= " AND ";
                        $where_clause.=" enabled=".(int)$callBackParam["enabled"];
                }

                // take into "name" parameter
                if ( isset($callBackParam["name"]) )
                {
                        foreach( $callBackParam["name"] as $name )
                                if (strlen($name)>0)
                                {
                                        if ( $where_clause != "" )
                                                $where_clause .= " AND ";
                                         $where_clause .= " LOWER(name) LIKE '%".xToText(trim(strtolower($name)))."%' ";
                                }
                }

                // take into "product_code" parameter
                if ( isset($callBackParam["product_code"]) )
                {
                        foreach( $callBackParam["product_code"] as $product_code )
                        {
                                if ( $where_clause != "" )
                                        $where_clause .= " AND ";
                                $where_clause .= " LOWER(product_code) LIKE '%".xToText(trim(strtolower($product_code)))."%' ";
                        }
                }

                // take into "price" parameter
                if ( isset($callBackParam["price"]) )
                {
                        $price = $callBackParam["price"];

                        if ( trim($price["from"]) != "" && $price["from"] != null )
                        {
                                if ( $where_clause != "" )
                                        $where_clause .= " AND ";
                                $from        = ConvertPriceToUniversalUnit( $price["from"] );
                                $where_clause .= " Price>=".(double)$from." ";
                        }
                        if ( trim($price["to"]) != "" && $price["to"] != null )
                        {
                                if ( $where_clause != "" )
                                        $where_clause .= " AND ";
                                $to                = ConvertPriceToUniversalUnit( $price["to"] );
                                $where_clause .= " Price<=".(double)$to." ";
                        }
                }
		
		if(CONF_CHECKSTOCK && CONF_SHOW_NULL_STOCK){
            if ( $where_clause != "" )
                $where_clause .= " AND in_stock>0 ";
            else
                $where_clause = "where in_stock>0 ";		
        }
        
        // categoryID
                if ( isset($callBackParam["categoryID"]) )
                {
                        $searchInSubcategories = false;
                        if ( isset($callBackParam["searchInSubcategories"]) )
                        {
                                if ( $callBackParam["searchInSubcategories"] )
                                        $searchInSubcategories = true;
                                else
                                        $searchInSubcategories = false;
                        }

                        if ( $searchInSubcategories )
                        {
                                $where_clause = _getConditionWithCategoryConjWithSubCategories( $where_clause,
                                                                                        $callBackParam["categoryID"] );
                        }
                        else
                        {
                                $where_clause = _getConditionWithCategoryConj( $where_clause,
                                                                                        $callBackParam["categoryID"] );
                        }
                }

                if ( $where_clause != "" )
                        $where_clause = "where ".$where_clause;

        }
        

		
        $order_by_clause = "order by ".CONF_DEFAULT_SORT_ORDER."";

        if ( isset($callBackParam["sort"]) )
        {
                if (        $callBackParam["sort"] == "categoryID"                        ||
                                $callBackParam["sort"] == "name"                                ||
                                $callBackParam["sort"] == "brief_description"        ||
                                $callBackParam["sort"] == "in_stock"                        ||
                                $callBackParam["sort"] == "Price"                                ||
                                $callBackParam["sort"] == "customer_votes"                ||
                                $callBackParam["sort"] == "customers_rating"        ||
                                $callBackParam["sort"] == "list_price"                        ||
                                $callBackParam["sort"] == "sort_order"                        ||
                                $callBackParam["sort"] == "items_sold"                        ||
                                $callBackParam["sort"] == "product_code"                ||
                                $callBackParam["sort"] == "shipping_freight"        ||
                                $callBackParam["sort"] == "viewed_times" )
                {
                        $order_by_clause = " order by ".xEscSQL($callBackParam["sort"])." ASC ";
                        if (  isset($callBackParam["direction"]) )
                                if (  $callBackParam["direction"] == "DESC" )
                                        $order_by_clause = " order by ".xEscSQL($callBackParam["sort"])." DESC ";
                }
        }

        $sqlQueryCount = "select count(*) from ".PRODUCTS_TABLE." ".$where_clause;
        $q = db_query( $sqlQueryCount );
        $products_count = db_fetch_row($q);
        $products_count = $products_count[0];
        $limit_clause= (isset($callBackParam["extraParametrsTemplate"]) || !$CountRowOnPage)?"":" LIMIT ".$offset.",".$CountRowOnPage;
        $sqlQuery = "select categoryID, name, brief_description, ".
                                 " customers_rating, Price, in_stock, ".
                                " customer_votes, list_price, ".
                                " productID, default_picture, sort_order, items_sold, enabled, ".
                                " product_code, description, shipping_freight, viewed_times, min_order_amount from ".PRODUCTS_TABLE." ".
                                $where_clause." ".$order_by_clause.$limit_clause;

        $q = db_query( $sqlQuery );
        $result = array();
        $i = 0;

        if ($offset >= 0 && $offset <= $products_count )
        {
                while( $row = db_fetch_row($q) )
                {

                        if ( isset($callBackParam["extraParametrsTemplate"]) ){

                                // take into "extra" parametrs
                                $testResult = _testExtraParametrsTemplate( $row["productID"], $replicantExtraParametersTpl );
                                if ( !$testResult ) continue;
                        }

                        if ( (($i >= $offset || !isset($callBackParam["extraParametrsTemplate"])) && $i < $offset + $CountRowOnPage) ||
                                        $navigatorParams == null  )
                        {
                                $row["PriceWithUnit"]     = show_price($row["Price"]);
                                $row["list_priceWithUnit"]= show_price($row["list_price"]);
                                // you save (value)
                                $row["SavePrice"]         = show_price($row["list_price"]-$row["Price"]);

                                // you save (%)
                                if ($row["list_price"]) $row["SavePricePercent"] = ceil(((($row["list_price"]-$row["Price"])/$row["list_price"])*100));
                                _setPictures( $row );
                                $row["product_extra"]     = GetExtraParametrs( $row["productID"] );
                                $row["product_extra_count"]  = count($row["product_extra"]);
                                $row["PriceWithOutUnit"]  = show_priceWithOutUnit( $row["Price"] );
                                if ( ((double)$row["shipping_freight"]) > 0 ) $row["shipping_freightUC"] = show_price( $row["shipping_freight"] );
                                $row["name"]              = $row["name"];
                                $row["description"]       = $row["description"];
                                $row["brief_description"] = $row["brief_description"];
                                $row["product_code"]      = $row["product_code"];
                                $row["viewed_times"]      = $row["viewed_times"];
                                $row["items_sold"]        = $row["items_sold"];
                                $result[] = $row;
                        }
                        $i++;
                }
        }
        $count_row = isset($callBackParam["extraParametrsTemplate"])?$i:$products_count;
        return $result;
}


function prdGetMetaKeywordTag( $productID )
{
        $q = db_query("select meta_description from ".PRODUCTS_TABLE." where productID=".(int)$productID);
        if ( $row=db_fetch_row($q) )
                return  $row["meta_description"];
        else
                return "";
}

function prdGetMetaTags( $productID ) //gets META keywords and description - an HTML code to insert into <head> section
{
        $q = db_query( "select meta_description, meta_keywords from ".
                PRODUCTS_TABLE." where productID=".(int)$productID );
        $row = db_fetch_row($q);
        $meta_description  = $row["meta_description"];
        $meta_keywords     = $row["meta_keywords"];

        $res = "";

        if  ( $meta_description != "" )
                $res .= "<meta name=\"Description\" content=\"".$meta_description."\">\n";
        if  ( $meta_keywords != "" )
                $res .= "<meta name=\"KeyWords\" content=\"".$meta_keywords."\" >\n";

        return $res;
}

?><?php
#####################################
# ShopCMS: Скрипт интернет-магазина
# Copyright (c) by ADGroup
# http://shopcms.ru
#####################################


function quickOrderContactInfoVerify()
{
        $first_name                = $_POST["first_name"];
        if ( trim($first_name) == "" )
                return ERROR_INPUT_NAME;

        $last_name                = $_POST["last_name"];
        if ( trim($last_name) == "" )
                return ERROR_INPUT_NAME;

        $Email                        = $_POST["email"];
        if ( trim($Email) == "" )
                return ERROR_INPUT_EMAIL;

        if (!preg_match("/^[_\.a-z0-9-]{1,20}@(([a-z0-9-]+\.)+(com|net|org|mil|edu|gov|arpa|info|biz|inc|name|[a-z]{2})|[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3})$/is",$Email)){
                if ($Email != "-"){
                return ERROR_INPUT_EMAIL;
                }
        }

        if (isset($_POST['affiliationLogin']))
                if ( !regIsRegister($_POST['affiliationLogin']) && $_POST['affiliationLogin'])
                        return ERROR_WRONG_AFFILIATION;
        //aux fields
        foreach($_POST as $key => $val)
        {
                if (strstr($key,"additional_field_"))
                {
                        $id = (int) str_replace("additional_field_","",$key);
                        if (GetIsRequiredRegField($id) && strlen(trim($val))==0)
                                return FEEDBACK_ERROR_FILL_IN_FORM;
                }
        }

        return "";
}


function quickOrderReceiverAddressVerify()
{
        $receiver_first_name        = $_POST["receiver_first_name"];
        $receiver_last_name        = $_POST["receiver_last_name"];
        $countryID                = $_POST["countryID"];
        if ( isset($_POST["state"])  )
                $state = $_POST["state"];
        else
                $state = "";
        $city                        = $_POST["city"];
        $address                = $_POST["address"];
        if ( isset($_POST["zoneID"]) )
                $zoneID = $_POST["zoneID"];
        else
                $zoneID = 0;
        $error = regVerifyAddress( $receiver_first_name, $receiver_last_name, $countryID, $zoneID, $state,
                                                         $city, $address );
        return $error;
}


function quickOrderBillingAddressVerify()
{
        if ( isset($_POST["billing_address_check"]) )
                return quickOrderReceiverAddressVerify();
        $payer_first_name                = $_POST["payer_first_name"];
        $payer_last_name                = $_POST["payer_last_name"];
        $billingCountryID                = $_POST["billingCountryID"];
        if ( isset($_POST["billingState"]) )
                $billingState = $_POST["billingState"];
        else
                $billingState = "";

        $billingCity                        = $_POST["billingCity"];
        $billingAddress                        = $_POST["billingAddress"];
        if ( isset($_POST["billingZoneID"]) )
                $billingZoneID = $_POST["billingZoneID"];
        else
                $billingZoneID = 0;
        $error = regVerifyAddress( $payer_first_name, $payer_last_name, $billingCountryID,
                                                        $billingZoneID, $billingState, $billingCity, $billingAddress );
        return $error;
}


function quikOrderSetCustomerInfo()
{
        $_SESSION["first_name"]        = $_POST["first_name"];
        $_SESSION["last_name"]        = $_POST["last_name"];
        $_SESSION["email"]        = $_POST["email"];
        $_SESSION['affiliationLogin'] = $_POST['affiliationLogin'];

        //save aux fields to session
        foreach($_POST as $key => $val)
        {
                if (strstr($key,"additional_field_") && strlen(trim($val)) > 0) //save information into sessions
                {
                        $_SESSION[$key] = $val;
                }
        }
}


function quickOrderSetReceiverAddress()
{
        $_SESSION["receiver_first_name"] = $_POST["first_name"];
        $_SESSION["receiver_last_name"]  = $_POST["last_name"];
        $_SESSION["receiver_countryID"]         = $_POST["countryID"];
        $_SESSION["receiver_state"]         = $_POST["state"];
        $_SESSION["receiver_zoneID"]         = $_POST["zoneID"];
        $_SESSION["receiver_city"]         = $_POST["city"];
        $_SESSION["receiver_address"]         = $_POST["address"];
}


function quickOrderSetBillingAddress()
{
        if ( !isset($_POST["billing_address_check"]) )
        {
                $_SESSION["billing_first_name"]  = $_POST["first_name"];
                $_SESSION["billing_last_name"]         = $_POST["last_name"];
                $_SESSION["billing_countryID"]         = $_POST["billingCountryID"];
                $_SESSION["billing_state"]         = $_POST["billingState"];
                $_SESSION["billing_city"]          = $_POST["billingCity"];
                $_SESSION["billing_zoneID"]          = $_POST["billingZoneID"];
                $_SESSION["billing_address"]         = $_POST["billingAddress"];
        }
        else
        {
                $_SESSION["billing_first_name"]  = $_POST["first_name"];
                $_SESSION["billing_last_name"]   = $_POST["last_name"];
                $_SESSION["billing_countryID"]         = $_POST["countryID"];
                $_SESSION["billing_state"]         = $_POST["state"];
                $_SESSION["billing_zoneID"]         = $_POST["zoneID"];
                $_SESSION["billing_city"]         = $_POST["city"];
                $_SESSION["billing_address"]         = $_POST["address"];
        }
}

function quickOrderGetReceiverAddressStr()
{
        if (!isset($_SESSION["receiver_countryID"]) || !isset($_SESSION["receiver_first_name"])) return "";

        // countryID, zoneID, state
        $country = cnGetCountryById( $_SESSION["receiver_countryID"] );
        $country = $country["country_name"];
        if ( trim($_SESSION["receiver_state"]) == "" )
        {
                $zone = znGetSingleZoneById( $_SESSION["receiver_zoneID"] );
                $zone = $zone["zone_name"];
        }
        else
                $zone = trim( $_SESSION["receiver_state"] );

        if (strlen($_SESSION["receiver_address"])>0){
        $strAddress = xHtmlSpecialChars($_SESSION["receiver_first_name"] );

     $strAddress .= " ".xHtmlSpecialChars($_SESSION["receiver_last_name"] );


        if (strlen($_SESSION["receiver_address"])>0)
                $strAddress .= "<br>".xHtmlSpecialChars( $_SESSION["receiver_address"] );
        if (strlen($_SESSION["receiver_city"])>0)
                $strAddress .= "<br>".xHtmlSpecialChars( $_SESSION["receiver_city"] );
        if (strlen($zone)>0)
                $strAddress .= "<br>".xHtmlSpecialChars($zone);

        if (strlen($country)>0)
                $strAddress .= "<br>".$country;
                }
        return $strAddress;
}

function quickOrderGetBillingAddressStr()
{
        if (!isset($_SESSION["billing_countryID"]) || !isset($_SESSION["billing_first_name"]))
                return "";

        // countryID, zoneID, state
        $country = cnGetCountryById( $_SESSION["billing_countryID"] );
        $country = $country["country_name"];
        if ( trim($_SESSION["billing_state"]) == "" )
        {
                $zone = znGetSingleZoneById( $_SESSION["billing_zoneID"] );
                $zone = $zone["zone_name"];
        }
        else
                $zone = trim( $_SESSION["billing_state"] );

         $strAddress = xHtmlSpecialChars( $_SESSION["billing_first_name"] );
        if (strlen($_SESSION["billing_address"])>0)
                $strAddress .= "<br>".xHtmlSpecialChars( $_SESSION["billing_address"] );
        if (strlen($_SESSION["billing_city"])>0)
                $strAddress .= "<br>".xHtmlSpecialChars( $_SESSION["billing_city"] );
        if (strlen($zone)>0)
                $strAddress .= "<br>".xHtmlSpecialChars($zone);

        if (strlen($country)>0)
                $strAddress .= "<br>".$country;

        return $strAddress;
}

?><?php
#####################################
# ShopCMS: Скрипт интернет-магазина
# Copyright (c) by ADGroup
# http://shopcms.ru
#####################################

// *****************************************************************************
// Purpose        gets all additional fields (see registry form)
// Inputs   nothing
// Remarks
// Returns        array of item
//                                each item
//                                        "reg_field_ID"                        - field id
//                                        "reg_field_name"                - field name
//                                        "reg_field_required"        - 1, if field is required to set
//                                        "sort_order"                        - sort order
function GetRegFields()
{
        $q=db_query("select reg_field_ID, reg_field_name, reg_field_required, sort_order from ".
                CUSTOMER_REG_FIELDS_TABLE." order by sort_order, reg_field_name " );
        $data=array();
        while( $row=db_fetch_row($q) ) $data[]=$row;
        return $data;
}


// *****************************************************************************
// Purpose        add additional field
// Inputs                $reg_field_name                        - field name
//                                $reg_field_required                - 1, if field is required to set
//                                $sort_order                                - sort order
// Remarks
// Returns        nothing
function AddRegField($reg_field_name, $reg_field_required, $sort_order)
{
        db_query("insert into ".CUSTOMER_REG_FIELDS_TABLE.
                "(reg_field_name, reg_field_required, sort_order) ".
                "values( '".xToText(trim($reg_field_name))."', ".(int)$reg_field_required.", ".(int)$sort_order." ) ");
}


// *****************************************************************************
// Purpose        delete additional field
// Inputs                $reg_field_ID                        - field id
// Remarks
// Returns        nothing
function DeleteRegField($reg_field_ID)
{
        db_query("delete from ".CUSTOMER_REG_FIELDS_VALUES_TABLE.
                " where reg_field_ID=".(int)$reg_field_ID);
        db_query("delete from ".CUSTOMER_REG_FIELDS_TABLE.
                " where reg_field_ID=".(int)$reg_field_ID);
}


// *****************************************************************************
// Purpose        update additional field
// Inputs
//                                $reg_field_ID                        - field id
//                                $reg_field_name                        - field name
//                                $reg_field_required                - 1, if field is required to set
//                                $sort_order                                - sort order
// Remarks
// Returns        nothing
function UpdateRegField($reg_field_ID, $reg_field_name,
        $reg_field_required, $sort_order)
{
        db_query(
                        "update ".CUSTOMER_REG_FIELDS_TABLE." set ".
                        "reg_field_name='".xToText(trim($reg_field_name))."', ".
                        "reg_field_required=".(int)$reg_field_required.", ".
                        "sort_order=".(int)$sort_order." ".
                        "where reg_field_ID=".(int)$reg_field_ID);
}


// *****************************************************************************
// Purpose        set additional field value to customer
// Inputs
//                                $reg_field_ID                - field id
//                                $customer_login                - login
//                                $reg_field_value        - value (string)
// Remarks
// Returns        nothing
function SetRegField($reg_field_ID, $customer_login, $reg_field_value)
{

        $customerID = regGetIdByLogin( $customer_login );
        $q=db_query("select count(*) from ".CUSTOMER_REG_FIELDS_VALUES_TABLE.
                " where reg_field_ID=".(int)$reg_field_ID." AND customerID=".(int)$customerID);
        $r=db_fetch_row($q);
        if ( $r[0] == 0 )
        {
                if ( trim($reg_field_value) == "" ) return;
                db_query("insert into ".CUSTOMER_REG_FIELDS_VALUES_TABLE.
                        "(reg_field_ID, customerID, reg_field_value) ".
                        "values( '".(int)$reg_field_ID."', '".(int)$customerID."', '".xToText(trim($reg_field_value))."' )");
        }
        else
        {
                if ( trim($reg_field_value) == "" )
                        db_query( "delete from ".CUSTOMER_REG_FIELDS_VALUES_TABLE.
                                " where reg_field_ID=".(int)$reg_field_ID." AND  ".
                                "         customerID=".(int)$customerID);
                else
                        db_query("update ".CUSTOMER_REG_FIELDS_VALUES_TABLE." set ".
                                " reg_field_value='".xToText(trim($reg_field_value))."' ".
                                " where reg_field_ID=".(int)$reg_field_ID." AND customerID=".(int)$customerID);
        }
}


// *****************************************************************************
// Purpose
// Inputs
//                                $reg_field_ID                - field id
//                                $customer_login                - login
//                                $reg_field_value        - value (string)
// Remarks
// Returns        1 if field requred to set, 0 otherwise
function GetIsRequiredRegField($reg_field_ID)
{
        $q=db_query("select reg_field_required from ".CUSTOMER_REG_FIELDS_TABLE.
                " where reg_field_ID=".(int)$reg_field_ID);
        $r=db_fetch_row($q);
        return $r["reg_field_required"];
}


// *****************************************************************************
// Purpose        gets additional reg fields values of a registered customer
// Inputs        customerID
// Remarks
// Returns        array of item
//                                each item
//                                        "reg_field_ID"                        - field id
//                                        "reg_field_name"                - field name
//                                        "reg_field_value"                - value
function GetRegFieldsValuesByCustomerID( $customerID )
{
        //get customer
        if (!$customerID) return array();

        $q = db_query("select reg_field_ID, reg_field_name from ".
                CUSTOMER_REG_FIELDS_TABLE." order by sort_order, reg_field_name ");
        $data=array();
        while( $r=db_fetch_row($q) )
        {
                $q1=db_query("select reg_field_value from ".
                        CUSTOMER_REG_FIELDS_VALUES_TABLE." where reg_field_ID=".(int)$r["reg_field_ID"].
                                " AND customerID=".(int)$customerID);
                $reg_field_value="";
                if ( $r1=db_fetch_row($q1) ) $reg_field_value = $r1["reg_field_value"];
                if ( strlen( trim($reg_field_value) ) > 0 )
                {
                        $row=array();
                        $row["reg_field_ID"]   = $r["reg_field_ID"];
                        $row["reg_field_name"] = $r["reg_field_name"];
                        $row["reg_field_value"]= $reg_field_value;
                        $data[]=$row;
                }
        }
        return $data;
}


// *****************************************************************************
// Purpose        gets additional reg fields values of a registered customer
// Inputs        customer login
// Remarks
// Returns        array of item
//                                each item
//                                        "reg_field_ID"                        - field id
//                                        "reg_field_name"                - field name
//                                        "reg_field_value"                - value
function GetRegFieldsValues( $customer_login )
{
        //get customer
        $customerID = regGetIdByLogin( $customer_login );
        if (!$customerID) return array();

        return GetRegFieldsValuesByCustomerID( $customerID );
}

// *****************************************************************************
// Purpose        gets additional field values of a customer by orderID
// Inputs
// Remarks
// Returns        array of item
//                                each item
//                                        "reg_field_ID"                        - field id
//                                        "reg_field_name"                - field name
//                                        "reg_field_value"                - value
function GetRegFieldsValuesByOrderID( $orderID )
{
        if (!$orderID) return array();

        //check if this order has been made by a registered customer or not (quick checkout)
        $q=db_query("select customerID from ".
                ORDERS_TABLE." where orderID = ".(int)$orderID);
        $row = db_fetch_row($q);
        if ($row[0] > 0)
                return GetRegFieldsValuesByCustomerID( $row[0] ); //made by a registered customer

        //quick checkout
        $q=db_query("select reg_field_ID, reg_field_name from ".
                CUSTOMER_REG_FIELDS_TABLE." order by sort_order, reg_field_name ");
        $data = array();
        while( $r=db_fetch_row($q) )
        {
                $q1=db_query("select reg_field_value from ".
                        CUSTOMER_REG_FIELDS_VALUES_TABLE_QUICKREG." where reg_field_ID=".(int)$r["reg_field_ID"].
                                " AND orderID=".(int)$orderID);
                $reg_field_value="";
                if ( $r1=db_fetch_row($q1) ) $reg_field_value = $r1["reg_field_value"];
                if ( strlen( trim($reg_field_value) ) > 0 )
                {
                        $row=array();
                        $row["reg_field_ID"]    = $r["reg_field_ID"];
                        $row["reg_field_name"]  = $r["reg_field_name"];
                        $row["reg_field_value"] = $reg_field_value;
                        $data[]=$row;
                }
        }
        return $data;
}

?><?php
#####################################
# ShopCMS: Скрипт интернет-магазина
# Copyright (c) by ADGroup
# http://shopcms.ru
#####################################

// *****************************************************************************
// Purpose  add administrator login into database and set default address
// Inputs   $admin_login - administrator login, $admin_pass - administrator password
// Remarks        this function is called by installation
// Returns        this function always returns true
function regRegisterAdmin( $admin_login, $admin_pass )
{
        // $q_count = db_query( "select COUNT(*) FROM  ".CUSTOMERS_TABLE." WHERE Login='".$admin_login."'" );
        // $count = db_fetch_row( $q_count );
        // $count = $count[0];
        db_query( "delete from ".CUSTOMERS_TABLE." where Login='".xEscSQL($admin_login)."'" );

        if ( CONF_DEFAULT_CUSTOMER_GROUP=='0' )
                $custgroupID = "NULL";
        else
                $custgroupID = CONF_DEFAULT_CUSTOMER_GROUP;

        $admin_pass = cryptPasswordCrypt( $admin_pass, null );

        $currencyID = CONF_DEFAULT_CURRENCY;
        $actions = 'a:35:{i:0;s:1:"1";i:1;s:1:"2";i:2;s:1:"3";i:3;s:1:"4";i:4;s:1:"5";i:5;s:1:"6";i:6;s:1:"7";i:7;s:1:"8";i:8;s:1:"9";i:9;s:2:"10";i:10;s:2:"11";i:11;s:2:"12";i:12;s:2:"13";i:13;s:2:"14";i:14;s:2:"15";i:15;s:2:"16";i:16;s:2:"17";i:17;s:2:"18";i:18;s:2:"19";i:19;s:2:"20";i:20;s:2:"21";i:21;s:2:"22";i:22;s:2:"23";i:23;s:2:"24";i:24;s:2:"25";i:25;s:2:"26";i:26;s:2:"27";i:27;s:2:"28";i:28;s:2:"29";i:29;s:2:"30";i:30;s:2:"31";i:31;s:2:"32";i:32;s:2:"33";i:33;s:2:"34";i:34;s:3:"100";}';
        
		db_query( "insert into ".CUSTOMERS_TABLE.
                " (Login, cust_password, Email, first_name, last_name, subscribed4news, ".
                "         custgroupID, addressID, reg_datetime, CID, actions ) values ".
                                "('".xToText($admin_login)."','".xEscSQL($admin_pass)."', ".
                                                " '-', '-', '-', 0, ".(int)$custgroupID.", NULL, ".
                                                " '".xEscSQL(get_current_time())."', ".(int)$currencyID.", '".xEscSQL($actions)."' )" );
        $errorCode = 0;
        $zoneID = "50";
        $state        = "";
        $countryID = "1";
        $defaultAddressID = regAddAddress(
                                "-", "-",
                                $countryID,
                                $zoneID,
                                $state,
                                "-",
                                "-",
                                $admin_login,
                                $errorCode );
        regSetDefaultAddressIDByLogin( $admin_login, $defaultAddressID );
        return true;
}

function regRegisterAdminSlave( $admin_login, $admin_pass, $actions=array() )
{
        $actions[] = 100;
        $actions = xEscSQL(serialize($actions));

        // $q_count = db_query( "select COUNT(*) FROM  ".CUSTOMERS_TABLE." WHERE Login='".$admin_login."'" );
        // $count = db_fetch_row( $q_count );
        // $count = $count[0];
        db_query( "delete from ".CUSTOMERS_TABLE." where Login='".xToText($admin_login)."'" );

        if ( CONF_DEFAULT_CUSTOMER_GROUP=='0' ) $custgroupID = "NULL";
        else $custgroupID = CONF_DEFAULT_CUSTOMER_GROUP;

        $admin_pass = cryptPasswordCrypt( $admin_pass, null );

        $currencyID = CONF_DEFAULT_CURRENCY;

        db_query( "insert into ".CUSTOMERS_TABLE.
                " (Login, cust_password, Email, first_name, last_name, subscribed4news, ".
                "         custgroupID, addressID, reg_datetime, CID, actions ) values ".
                                "('".xToText($admin_login)."','".xEscSQL($admin_pass)."', ".
                                                " '-', '-', '-', 0, ".(int)$custgroupID.", NULL, ".
                                                " '".xEscSQL(get_current_time())."', ".(int)$currencyID.", '".$actions."')" );
        $errorCode = 0;
        $zoneID = "50";
        $state        = "";
        $countryID = "1";
        $defaultAddressID = regAddAddress(
                                "-", "-",
                                $countryID,
                                $zoneID,
                                $state,
                                "-",
                                "-",
                                $admin_login,
                                $errorCode );
        regSetDefaultAddressIDByLogin( $admin_login, $defaultAddressID );
        return true;
}


// *****************************************************************************
// Purpose
// Inputs   $login - login
// Remarks
// Returns        true if login exists in database, false otherwise
function regIsRegister( $login )
{
        $q=db_query("select count(*) from ".CUSTOMERS_TABLE." where Login='".xToText($login)."'");
        $r = db_fetch_row($q);
        return  ( $r[0] != 0 );
}


// *****************************************************************************
// Purpose
// Inputs   $customerID - custmer ID
// Remarks
// Returns        false if customer does not exist, login - otherwise
function regGetLoginById( $customerID )
{
        if ($customerID == 0) return false;

        $q = db_query("select Login from ".CUSTOMERS_TABLE." where customerID=".(int)$customerID);
        if ( ($r=db_fetch_row($q)) ) return $r["Login"];
        else return false;
}


// *****************************************************************************
// Purpose
// Inputs   $login - login
// Remarks
// Returns        false if customer does not exist, customer ID - otherwise
function regGetIdByLogin( $login )
{
        $q = db_query("select customerID from ".CUSTOMERS_TABLE." where Login='".xToText($login)."'");
        if (  ($r=db_fetch_row($q)) ) return (int)$r["customerID"];
        else return NULL;
}



// *****************************************************************************
// Purpose  authenticate user
// Inputs   $login - login, $password - password
// Remarks  if user is authenticated successfully then this function sets sessions variables,
//                update statistic, move cart content into DB
// Returns        false if authentication failure, true - otherwise
function regAuthenticate($login, $password, $Redirect = true)
{
        $q = db_query("select cust_password, CID, ActivationCode FROM ".CUSTOMERS_TABLE." WHERE Login='".xToText($login)."'");
        $row = db_fetch_row($q);
//        echo $login." ".$password."<br>";
//var_dump($row);exit;

        if(CONF_ENABLE_REGCONFIRMATION && $row['ActivationCode']){

                if($Redirect)RedirectProtected(set_query('&act_customer=1&notact=1'));
                else return false;
        }

        if ($row && strlen( trim($login) ) > 0)
        {
                if ($row["cust_password"] == cryptPasswordCrypt($password, null) )
                {
                        // set session variables
                        $_SESSION["log"]         = $login;
                        $_SESSION["pass"]         = cryptPasswordCrypt($password, null);


                        $_SESSION["current_currency"] = $row["CID"];

                        // update statistic
                        stAddCustomerLog( $login );

                        // move cart content into DB
                        moveCartFromSession2DB();
                        return true;
                }
                else
                        return false;
        }
        else return false;
}



// *****************************************************************************
// Purpose          sends password to customer email
// Inputs
// Remarks
// Returns        true if success
function regSendPasswordToUser( $login, &$smarty_mail )
{
        $q = db_query("select Login, cust_password, Email FROM ".CUSTOMERS_TABLE." WHERE Login='".xToText($login)."' AND (ActivationCode=\"\" OR ActivationCode IS NULL)");
        if ($row = db_fetch_row($q)) //send password
        {
                $password = cryptPasswordDeCrypt( $row["cust_password"], null );
                $smarty_mail->assign( "user_pass", $password );
                $smarty_mail->assign( "user_login", $row['Login'] );
                $html = $smarty_mail->fetch("remind_password.tpl.html");
                xMailTxtHTMLDATA($row["Email"], EMAIL_FORGOT_PASSWORD_SUBJECT, $html);
                return true;
        }
        else
                return false;
}


// *****************************************************************************
// Purpose  determine administrator user
// Inputs   $login - login
// Remarks  if user is authenticated successfully then this function sets sessions variables,
//                update statistic, move cart content into DB
// Returns        false if authentication failure, true - otherwise
function regIsAdminiatrator( $login )
{
        $relaccess = false;
        if (isset($_SESSION["log"])){
        $q = db_query("select actions from ".CUSTOMERS_TABLE." WHERE Login='".xToText($login)."'");
        $n = db_fetch_row($q);
        $n[0] = unserialize( $n[0] );
        if(in_array(100,$n[0]))$relaccess = true;
        }
        return $relaccess;
}



// *****************************************************************************
// Purpose        register new customer
// Inputs
//                                $login                                - login
//                                $cust_password                - password
//                                $Email                                - email
//                                $first_name                        - customer first name
//                                $last_name                        - customer last name
//                                $subscribed4news        - if 1 customer is subscribed to news
//                                $additional_field_values - additional field values is array of item
//                                                                        "additional_field" is value of this field
//                                                                        key is reg_field_ID
// Remarks
// Returns
function regRegisterCustomer( $login, $cust_password, $Email, $first_name,
                $last_name, $subscribed4news, $additional_field_values, $affiliateLogin = '')
{
        $affiliateID = 0;

        if ($affiliateLogin){

                $sql = "select customerID  FROM ".CUSTOMERS_TABLE."
                        WHERE Login='".xToText(trim($affiliateLogin))."'";
                list($affiliateID) = db_fetch_row(db_query($sql));
        }

        foreach( $additional_field_values as $key => $val)
                $additional_field_values[$key] = $val;


        $currencyID = CONF_DEFAULT_CURRENCY;


        $cust_password = cryptPasswordCrypt( $cust_password, null );
        // add customer to CUSTOMERS_TABLE

        $custgroupID = CONF_DEFAULT_CUSTOMER_GROUP;
        if ( $custgroupID == 0 )
                $custgroupID = "NULL";
        /**
         * Activation code
         */
        $ActivationCode = '';
        if(CONF_ENABLE_REGCONFIRMATION){

                $CodeExists = true;
                while ($CodeExists) {

                        $ActivationCode = generateRndCode(16);
                        $sql = 'SELECT 1 FROM '.CUSTOMERS_TABLE.'
                                WHERE ActivationCode="'.xEscapeSQLstring($ActivationCode).'"';
                        @list($CodeExists) = db_fetch_row(db_query($sql));
                }
        }
        db_query("insert into ".CUSTOMERS_TABLE.
                "( Login, cust_password, Email, first_name, last_name, subscribed4news, reg_datetime, CID, custgroupID, affiliateID, ActivationCode )".
                "values( '".xToText(trim($login))."', '".xEscSQL(trim($cust_password))."', '".xToText(trim($Email))."', ".
                " '".xToText(trim($first_name))."', '".xToText(trim($last_name))."', '".(int)$subscribed4news."', '".xEscSQL(get_current_time())."', ".
                        (int)$currencyID.", ".(int)$custgroupID.", ".xEscSQL(trim($affiliateID)).", '".xEscSQL(trim($ActivationCode))."' )" );

        // add additional values to CUSTOMER_REG_FIELDS_TABLE
        foreach( $additional_field_values as $key => $val )
                SetRegField($key, $login, $val["additional_field"]);

        $customerID = regGetIdByLogin($login);
        //db_query("update ".CUSTOMERS_TABLE." set addressID='".$addressID.
        //        "' where Login='".$login."'" );

        if ( $subscribed4news )
                subscrAddRegisteredCustomerEmail( $customerID );

        return true;
}


// *****************************************************************************
// Purpose        send notification message to email
// Inputs
//                                $login                                - login
//                                $cust_password                - password
//                                $Email                                - email
//                                $first_name                        - customer first name
//                                $last_name                        - customer last name
//                                $subscribed4news        - if 1 customer is subscribed to news
//                                $additional_field_values - additional field values is array of item
//                                                                        "additional_field" is value of this field
//                                                                        key is reg_field_ID
//                                $updateOperation        - 1 if customer info is updated, 0
//                                                                otherwise
// Remarks
// Returns
function regEmailNotification($smarty_mail, $login, $cust_password, $Email, $first_name,
                $last_name, $subscribed4news, $additional_field_values,
                $countryID, $zoneID, $state, $city, $address, $updateOperation )
{
        $user = array();
        $smarty_mail->assign( "login", $login );
        $smarty_mail->assign( "cust_password", $cust_password );
        $smarty_mail->assign( "first_name", $first_name );
        $smarty_mail->assign( "last_name", $last_name );
        $smarty_mail->assign( "Email", $Email );
        $additional_field_values = GetRegFieldsValues( $login );
        $smarty_mail->assign( "additional_field_values", $additional_field_values );

        $addresses = regGetAllAddressesByLogin( $login );
        for( $i=0; $i<count($addresses); $i++ )
                $addresses[$i]["addressStr"] = regGetAddressStr( (int)$addresses[$i]["addressID"] );
        $smarty_mail->assign( "addresses", $addresses );

        if(CONF_ENABLE_REGCONFIRMATION){

                $sql = 'SELECT ActivationCode FROM '.CUSTOMERS_TABLE.'
                        WHERE Login="'.xEscapeSQLstring($login).'" AND cust_password="'.xEscapeSQLstring(cryptPasswordCrypt($cust_password, null)).'"';
                @list($ActivationCode) = db_fetch_row(db_query($sql));

                $smarty_mail->assign('ActURL', CONF_FULL_SHOP_URL.(substr(CONF_FULL_SHOP_URL, strlen(CONF_FULL_SHOP_URL)-1,1)=='/'?'':'/').'index.php?act_customer=1&act_code='.$ActivationCode);
                $smarty_mail->assign('ActCode', $ActivationCode);
        }

        $html = $smarty_mail->fetch( "register_successful.tpl.html" );
        xMailTxtHTMLDATA($Email, EMAIL_REGISTRATION, $html);
}

// *****************************************************************************
// Purpose        get customer info
// Inputs
//                                $login                                - login
//                                $cust_password                - password
//                                $Email                                - email
//                                $first_name                        - customer first name
//                                $last_name                        - customer last name
//                                $subscribed4news        - if 1 customer is subscribed to news
//                                $additional_field_values - additional field values is array of item
//                                                                        "additional_field" is value of this field
//                                                                        key is reg_field_ID
//                                $updateOperation        - 1 if customer info is updated, 0
//                                                                otherwise
// Remarks
// Returns
function regGetCustomerInfo($login, & $cust_password, & $Email, & $first_name,
                & $last_name, & $subscribed4news, & $additional_field_values,
                & $countryID, & $zoneID, & $state, & $city, & $address )
{
        $q=db_query("select customerID, cust_password, Email, first_name, last_name, ".
                " subscribed4news, custgroupID, addressID  from ".CUSTOMERS_TABLE.
                " where Login='".xToText($login)."'");
        $r = db_fetch_row($q);
        $cust_password = cryptPasswordDeCrypt( $r["cust_password"], null );
        if (CONF_BACKEND_SAFEMODE)
                $r["Email"] = ADMIN_SAFEMODE_BLOCKED;
        else
        $Email = $r["Email"];
        $first_name=$r["first_name"];
        $last_name= $r["last_name"];
        $subscribed4news        = (int)$r["subscribed4news"];
        $addressID                        = (int)$r["addressID"];
        $customerID                        = (int)$r["customerID"];
        $q=db_query("select countryID, zoneID, state, city, address from ".
                CUSTOMER_ADDRESSES_TABLE." where customerID=".(int)$customerID);
        $r=db_fetch_row($q);
        $countryID  = $r["countryID"];
        $zoneID                = $r["zoneID"];
        $state                =  $r["state"];
        $city                =  $r["city"];
        $address        =  $r["address"];
        $additional_field_values = GetRegFieldsValues( $login );
        foreach( $additional_field_values as $key => $value )
                $additional_field_values[$key] =  $additional_field_values[$key];
}




// *****************************************************************************
// Purpose        get customer info
// Inputs
// Remarks
// Returns
function regGetCustomerInfo2( $login )
{
        $q = db_query("select customerID, cust_password, Email, first_name, last_name, ".
                " subscribed4news, custgroupID, addressID, Login, ActivationCode from ".CUSTOMERS_TABLE.
                " where Login='".xToText($login)."'");
        if ( $row=db_fetch_row($q) )
        {
                if ( $row["custgroupID"] != null )
                {
                        $q = db_query("select custgroupID, custgroup_name, custgroup_discount, sort_order from ".
                                CUSTGROUPS_TABLE." where custgroupID=".(int)$row["custgroupID"] );
                        $custGroup = db_fetch_row($q);
                        $row["custgroup_name"] = $custGroup["custgroup_name"];
                }
                else
                $row["custgroup_name"] = "";
                $row["cust_password"] = cryptPasswordDeCrypt( $row["cust_password"], null );

                if (CONF_BACKEND_SAFEMODE) $row["Email"] = ADMIN_SAFEMODE_BLOCKED;
                $row["allowToDelete"]  = regVerifyToDelete( $row["customerID"] );
        }
        return $row;
}



// -----------------------------------------------

function regAddAddress(
                                $first_name, $last_name, $countryID,
                                $zoneID, $state, $city,
                                $address, $log, &$errorCode )
{
        $customerID = regGetIdByLogin( $log );

        if ( $zoneID == 0 ) $zoneID = "NULL";
        db_query("insert into ".CUSTOMER_ADDRESSES_TABLE.
                " ( first_name, last_name, countryID, zoneID, state, city, ".
                                " address, customerID ) ".
                " values( '".xToText(trim($first_name))."', '".xToText(trim($last_name))."', ".(int)$countryID.", ".(int)$zoneID.", '".xToText(trim($state))."', ".
                        " '".xToText(trim($city))."', '".xToText(trim($address))."', ".(int)$customerID." )");
        return db_insert_id();
}

function regUpdateAddress( $addressID,
                                $first_name, $last_name, $countryID,
                                $zoneID, $state, $city,
                                $address, &$errorCode )
{
        if ( $zoneID == 0 ) $zoneID = "NULL";
        db_query("update ".CUSTOMER_ADDRESSES_TABLE.
                " set ".
                " first_name='".xToText(trim($first_name))."', last_name='".xToText(trim($last_name))."', countryID=".(int)$countryID.", ".
                " zoneID=".(int)$zoneID.", state='".xToText(trim($state))."', ".
                " city='".xToText(trim($city))."', address='".xToText(trim($address))."' where addressID=".(int)$addressID);
        return true;
}

function redDeleteAddress( $addressID )
{
        db_query("update ".CUSTOMERS_TABLE." set addressID=NULL where addressID=".(int)$addressID);
        db_query("delete from ".CUSTOMER_ADDRESSES_TABLE." where addressID=".(int)$addressID);
}


function regGetAddress( $addressID )
{
        if ( $addressID != null )
        {
                // $customerID
                $q = db_query(        "select first_name, last_name, countryID, zoneID, ".
                                                " state, city, address, customerID from ".
                                                CUSTOMER_ADDRESSES_TABLE." where addressID=".(int)$addressID);
               $row=db_fetch_row($q);
               return $row;
        }
        else
                return false;
}


function regGetAddressByLogin( $addressID, $login )
{
        $customerID = regGetIdByLogin( $login );
        $address = regGetAddress( $addressID );
        if ( (int)$address["customerID"] == (int)$customerID )
                return $address;
        else
                return false;
}


function regGetAllAddressesByLogin( $log )
{
        $customerID = regGetIdByLogin( $log );

        $customerID = (int) $customerID;
        if ($customerID == 0) return NULL;

        $q = db_query( "select addressID, first_name, last_name, countryID, zoneID, state, city, address ".
                                        " from ".CUSTOMER_ADDRESSES_TABLE." where customerID=".(int)$customerID);
        $data = array();
        while( $row = db_fetch_row($q) )
        {

                if ( $row["countryID"] != null )
                {
                        $q1=db_query("select country_name from ".COUNTRIES_TABLE.
                                " where countryID=".(int)$row["countryID"] );
                        $country = db_fetch_row($q1);
                        $row["country"] = $country[0];
                }
                else
                        $row["country"] = "-";

                if ( $row["zoneID"] != null )
                {
                        $q1 = db_query("select zone_name from ".ZONES_TABLE.
                                        " where zoneID=".(int)$row["zoneID"] );
                         $zone = db_fetch_row( $q1 );
                        $row["state"] = $zone[0];
                }

                $data[] = $row;
        }
        return $data;
}

function regGetDefaultAddressIDByLogin( $log )
{
        $q = db_query("select addressID from ".CUSTOMERS_TABLE." where Login='".xToText($log)."'");
        if ( $row = db_fetch_row( $q ) )
                return (int)$row[0];
        else
                return null;
}

function regSetDefaultAddressIDByLogin( $log, $defaultAddressID )
{
        db_query( "update ".CUSTOMERS_TABLE." set addressID=".(int)$defaultAddressID." where Login='".xToText($log)."'" );
}


function _testStrInvalidSymbol( $str )
{
        $res = strstr( $str, "'" );
        if ( is_string($res) )
                return false;

        $res = strstr( $str, "\\" );
        if ( is_string($res) )
                return false;

        $res = strstr( $str, '"' );
        if ( is_string($res) )
                return false;

        $res = strstr( $str, "<" );
        if ( is_string($res) )
                return false;

        $res = strstr( $str, ">" );
        if ( is_string($res) )
                return false;

        return true;
}

function _testStrArrayInvalidSymbol( $array )
{
        foreach( $array as $str )
        {
                $res = _testStrInvalidSymbol( $str );
                if ( !$res )
                        return false;
        }
        return true;
}



// *****************************************************************************
// Purpose        verify address input data
// Inputs
//                                $first_name                        - customer first name
//                                $last_name                        - customer last name
//                                $countryID                        - country ID
//                                $zoneID
//                                $state
//                                $city
//                                $address
// Remarks
// Returns        empty string if success, error message otherwise
function regVerifyAddress(        $first_name, $last_name,
                                                        $countryID, $zoneID, $state,
                                                        $city, $address )
{
        $error = "";
        if ( trim($first_name) == "" ) $error = ERROR_INPUT_NAME;
        else
        if ( trim($last_name) == "" ) $error = ERROR_INPUT_NAME;
        else
        if ( CONF_ADDRESSFORM_STATE == 0 && trim($state) == "" && $zoneID == 0 )        $error = ERROR_INPUT_STATE;
        else
        if ( CONF_ADDRESSFORM_CITY == 0 && trim($city) == "" )        $error = ERROR_INPUT_CITY;
        else
        if ( CONF_ADDRESSFORM_ADDRESS == 0 && trim($address)=="")        $error = ERROR_INPUT_ADDRESS;

        $q = db_query("select count(*) from ".ZONES_TABLE." where countryID=".(int)$countryID);
        $r = db_fetch_row( $q );
        $countZone = $r[0];

        if ( $countZone != 0 )
        {
                $q = db_query("select count(*) from ".ZONES_TABLE." where zoneID=".(int)$zoneID.
                        "  AND countryID=".(int)$countryID);
                $r = db_fetch_row( $q );
                if ( $r[0] == 0 && CONF_ADDRESSFORM_STATE != 2 )
                        $error = ERROR_ZONE_DOES_NOT_CONTAIN_TO_COUNTRY;
        }
        else if ($zoneID!=0) $error = ERROR_INPUT_STATE;

        return $error;
}

function regGetContactInfo( $login, &$cust_password, &$Email, &$first_name,
                                &$last_name, &$subscribed4news, &$additional_field_values )
{
        $q=db_query("select customerID, cust_password, Email, first_name, last_name, ".
                " subscribed4news, custgroupID, addressID  from ".CUSTOMERS_TABLE.
                " where Login='".xToText($login)."'");
        $row = db_fetch_row( $q );
        $cust_password                                = cryptPasswordDeCrypt( $row["cust_password"], null );
        $Email    = $row["Email"];
        $first_name  =$row["first_name"];
        $last_name  =$row["last_name"];
        $subscribed4news                        = $row["subscribed4news"];
        $additional_field_values        = GetRegFieldsValues($login);
}

function regVerifyContactInfo( $login, $cust_password1, $cust_password2,
                                                $Email, $first_name, $last_name, $subscribed4news,
                                                $additional_field_values )
{
        $error = "";
        if (
                        !_testStrArrayInvalidSymbol(
                                                                                array( $login, $cust_password1, $cust_password2 )
                                                                        )
                )
                $error = ERROR_INVALID_SYMBOL_LOGIN_INFO;
        else
        if ( trim($login) == "" ) $error = ERROR_INPUT_LOGIN;
        else
        if (!(((ord($login)>=ord("a")) && (ord($login)<=ord("z"))) ||
                        ((ord($login)>=ord("A")) && (ord($login)<=ord("Z")))))
                                $error = ERROR_LOGIN_SHOULD_START_WITH_LATIN_SYMBOL;
        else
        if ( $cust_password1 == "" ||  $cust_password2 == "" || $cust_password1 != $cust_password2 )
                $error = ERROR_WRONG_PASSWORD_CONFIRMATION;
        else
        if ( trim($first_name) == "" ) $error = ERROR_INPUT_NAME;
        else
        if ( trim($last_name) == "" ) $error = ERROR_INPUT_NAME;
        else
        if ( trim($Email) == "" ) $error = ERROR_INPUT_EMAIL;
        else if (!preg_match("/^[_\.a-z0-9-]{1,20}@(([a-z0-9-]+\.)+(com|net|org|mil|edu|gov|arpa|info|biz|inc|name|[a-z]{2})|[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3})$/is",$Email))
        { //e-mail validation
                $error = ERROR_INPUT_EMAIL;
        }

        if (isset($_POST['affiliationLogin']))
        if ( !regIsRegister($_POST['affiliationLogin']) && $_POST['affiliationLogin'])
                        $error = ERROR_WRONG_AFFILIATION;

        foreach( $additional_field_values as $key => $val )
        {
                if ( !_testStrInvalidSymbol($val["additional_field"]) )
                        return ERROR_INVALID_SYMBOL;
                if ( trim($val["additional_field"]) == "" && GetIsRequiredRegField($key) == 1 )
                {
                        $error = ERROR_INPUT_ADDITION_FIELD;
                        break;
                }
        }
        return $error;
}


function regUpdateContactInfo( $old_login, $login, $cust_password,
                                                $Email, $first_name, $last_name, $subscribed4news,
                                                $additional_field_values )
{
        db_query("update ".CUSTOMERS_TABLE."  set ".
                        " Login = '".xToText(trim($login))."', ".
                        " cust_password = '".cryptPasswordCrypt( $cust_password, null )."', ".
                        " Email = '".xToText($Email)."', ".
                        " first_name = '".xToText(trim($first_name))."', ".
                        " last_name = '".xToText(trim($last_name))."', ".
                        " subscribed4news = ".(int)$subscribed4news." ".
                        " where Login='".xToText(trim($old_login))."'");
        foreach( $additional_field_values as $key => $val )
                SetRegField($key, $login, $val["additional_field"]);


        if (!strcmp($old_login, $login)) //update administrator login (core/config/connect.inc.php)
        {
        db_query("update ".CUSTOMERS_TABLE." set Login='".xToText(trim($login))."' where Login='".xToText(trim($old_login))."'");
        }


        $customerID = regGetIdByLogin( $login );



        if ( $subscribed4news )
                subscrAddRegisteredCustomerEmail( $customerID );
        else
                subscrUnsubscribeSubscriberByEmail( base64_encode($Email) );
}


// *****************************************************************************
// Purpose        get address string by address ID
// Inputs
// Remarks
// Returns
function regGetAddressStr( $addressID, $NoTransform = false  )
{
        $address = regGetAddress( $addressID );

        // countryID, zoneID, state
        $country = cnGetCountryById( $address["countryID"] );
        $country = $country["country_name"];
        if ( trim($address["state"]) == "" )
        {
                $zone = znGetSingleZoneById( $address["zoneID"] );
                $zone = $zone["zone_name"];
        }
        else
                $zone = trim($address["state"]);

        if ( $country != "" )
        {
                $strAddress = $address["first_name"]."  ".$address["last_name"];
                if (strlen($address["address"])>0) $strAddress .= "<br>".$address["address"];
                if (strlen($address["city"])>0) $strAddress .= "<br>".$address["city"];
                if (strlen($zone)>0) $strAddress .= "  ".$zone;
                if (strlen($country)>0) $strAddress .= "<br>".$country;
        }
        else
        {
                $strAddress = $address["first_name"]."  ".$address["last_name"];
                if (strlen($address["address"])>0) $strAddress .= "<br>".$address["address"];
                if (strlen($address["city"])>0) $strAddress .= "<br>".$address["city"];
                if (strlen($zone)>0) $strAddress .= " ".$zone;
        }

        return $strAddress;
}


// *****************************************************************************
// Purpose        gets all customers
// Inputs
// Remarks
// Returns
function regGetCustomers( $callBackParam, &$count_row, $navigatorParams = null )
{
        if ( $navigatorParams != null )
        {
                $offset                = $navigatorParams["offset"];
                $CountRowOnPage        = $navigatorParams["CountRowOnPage"];
        }
        else
        {
                $offset = 0;
                $CountRowOnPage = 0;
        }

        $where_clause = "";
        if ( isset($callBackParam["Login"]) )
        {
                $callBackParam["Login"] = xEscSQL( $callBackParam["Login"] );
                $where_clause .= " Login LIKE '%".$callBackParam["Login"]."%' ";
        }

        if ( isset($callBackParam["first_name"]) )
        {
                $callBackParam["first_name"] = xEscSQL( $callBackParam["first_name"] );
                if ( $where_clause != "" ) $where_clause .= " AND ";
                $where_clause .= " first_name LIKE '%".$callBackParam["first_name"]."%' ";
        }

        if ( isset($callBackParam["last_name"]) )
        {
                $callBackParam["last_name"] = xEscSQL( $callBackParam["last_name"] );
                if ( $where_clause != "" ) $where_clause .= " AND ";
                $where_clause .= " last_name LIKE '%".$callBackParam["last_name"]."%' ";
        }

        if ( isset($callBackParam["email"]) )
        {
                $callBackParam["email"] = xEscSQL( $callBackParam["email"] );
                if ( $where_clause != "" ) $where_clause .= " AND ";
                $where_clause .= " Email LIKE '%".$callBackParam["email"]."%' ";
        }

        if ( isset($callBackParam["groupID"]) )
        {
                if ( $callBackParam["groupID"] != 0 )
                {
                        if ( $where_clause != "" ) $where_clause .= " AND ";
                        $where_clause .= " custgroupID = ".(int)$callBackParam["groupID"]." ";
                }
        }

        if ( isset($callBackParam["ActState"]) )
        {
                switch ($callBackParam["ActState"]){

                        #activated
                        case 1:
                                if ( $where_clause != "" ) $where_clause .= " AND ";
                                $where_clause .= " (ActivationCode='' OR ActivationCode IS NULL)";
                                break;
                        #not activated
                        case 0:
                                if ( $where_clause != "" ) $where_clause .= " AND ";
                                $where_clause .= " ActivationCode!=''";
                                break;
                }
        }


        if ( $where_clause != "" )
                $where_clause = " where ".$where_clause;


        $order_clause = "";
        if ( isset($callBackParam["sort"]) )
        {
                $order_clause .= " order by ".xEscSQL($callBackParam["sort"])." ";
                if ( isset($callBackParam["direction"]) )
                {
                        if ( $callBackParam["direction"] == "ASC" )
                                $order_clause .=  " ASC ";
                        else
                                $order_clause .=  " DESC ";
                }
        }




        $q=db_query("select customerID, Login, cust_password, Email, first_name, last_name, subscribed4news, ".
                 " custgroupID, addressID, reg_datetime, ActivationCode ".
                 " from ".CUSTOMERS_TABLE." ".$where_clause." ".$order_clause );
        $data = array();
        $i=0;//var_dump ($navigatorParams);
        while( $row=db_fetch_row($q) )
        {

                if ( ($i >= $offset && $i < $offset + $CountRowOnPage) ||
                                $navigatorParams == null  )
                {
                        $group = GetCustomerGroupByCustomerId( $row["customerID"] );
                        $row["custgroup_name"] = $group["custgroup_name"];
                        $row["allowToDelete"]  = regVerifyToDelete( $row["customerID"] );
                        $row["reg_datetime"]  = format_datetime( $row["reg_datetime"] );
                        $data[] = $row;
                }
                $i++;
        }
        $count_row = $i;
        return $data;
}


function regSetSubscribed4news( $customerID, $value )
{
        db_query( "update ".CUSTOMERS_TABLE." set subscribed4news = ".(int)$value.
                        " where customerID=".(int)$customerID );
        if ($value > 0)
        {
                subscrAddRegisteredCustomerEmail($customerID);
        }
        else
        {
                subscrUnsubscribeSubscriberByCustomerId($customerID);
        }
}

function regSetCustgroupID( $customerID, $custgroupID )
{
        db_query( "update ".CUSTOMERS_TABLE." set custgroupID=".(int)$custgroupID.
                        " where customerID=".(int)$customerID );
}



function regAddressBelongToCustomer( $customerID, $addressID )
{

        if (!$customerID) return false;

        if (!$addressID) return false;

        $q_count = db_query( "select count(*) from ".CUSTOMER_ADDRESSES_TABLE.
                " where customerID=".(int)$customerID." AND addressID=".(int)$addressID );
        $count = db_fetch_row( $q_count );
        $count = $count[0];
        return ( $count != 0 );
}




function regVerifyToDelete( $customerID )
{

        if (!$customerID) return 0;

        $q = db_query( "select count(*) from ".CUSTOMERS_TABLE." where customerID=".(int)$customerID );
        $row = db_fetch_row($q);

        if ( regIsAdminiatrator(regGetLoginById($customerID))  )
                return false;

        return ($row[0] == 1);
}



function regDeleteCustomer( $customerID )
{
        if ( $customerID == null || trim($customerID) == ""  )
                return false;

        if (!$customerID) return 0;

        if ( regVerifyToDelete( $customerID ) )
        {
                db_query( "delete from ".SHOPPING_CARTS_TABLE." where customerID=".(int)$customerID );
                db_query( "delete from ".MAILING_LIST_TABLE." where customerID=".(int)$customerID );
                db_query( "delete from ".CUSTOMER_ADDRESSES_TABLE." where customerID=".(int)$customerID );
                db_query( "delete from ".CUSTOMER_REG_FIELDS_VALUES_TABLE." where customerID=".(int)$customerID );
                db_query( "delete from ".CUSTOMERS_TABLE." where customerID=".(int)$customerID );
                db_query( "update ".ORDERS_TABLE." set customerID=NULL where customerID=".(int)$customerID );
                return true;
        }
        else
                return false;
}

function regActivateCustomer($_CustomerID){

        $sql = 'UPDATE '.CUSTOMERS_TABLE.'
                SET ActivationCode = ""
                WHERE customerID='.(int)$_CustomerID;
        db_query($sql);
}

?><?php
#####################################
# ShopCMS: Скрипт интернет-магазина
# Copyright (c) by ADGroup
# http://shopcms.ru
#####################################

        // *****************************************************************************
        // Purpose        get report for all product for particular category
        // Inputs
        // Remarks
        // Returns
        function repGetProductReportByCategoryID( $callBackParam, &$count_row, $navigatorParams = null )
        {

                if ( $navigatorParams != null )
                {
                        $offset                        = $navigatorParams["offset"];
                        $CountRowOnPage        = $navigatorParams["CountRowOnPage"];
                }
                else
                {
                        $offset = 0;
                        $CountRowOnPage = 0;
                }

                $where_clause = "";
                $order_clause = "";

                if ( isset($callBackParam["categoryID"]) )
                        if ( $callBackParam["categoryID"] != 0 )
                                $where_clause = " where categoryID=".(int)$callBackParam["categoryID"];

                if ( isset($callBackParam["sort"]) )
                {
                        $order_clause = " order by ".xEscSQL($callBackParam["sort"]);
                        if (  isset($callBackParam["direction"])  )
                                $order_clause .= " ".xEscSQL($callBackParam["direction"]);
                }

                $res = array();
                $q = db_query( "select name, customers_rating, customer_votes, items_sold, ".
                        " viewed_times, in_stock, sort_order  from ".PRODUCTS_TABLE." ".$where_clause.
                                        " ".$order_clause );
                $i = 0;
                while( $row=db_fetch_row($q) )
                {
                        if ( ($i >= $offset && $i < $offset + $CountRowOnPage) || $navigatorParams == null  )
                                $res[] = $row;
                        $i ++;
                }
                $count_row = $i;

                return $res;
        }

?><?php
#####################################
# ShopCMS: Скрипт интернет-магазина
# Copyright (c) by ADGroup
# http://shopcms.ru
#####################################


        function schIsAllowProductsSearch( $categoryID )
        {
                $q = db_query("select allow_products_search from ".CATEGORIES_TABLE.
                        " where categoryID=".(int)$categoryID);
                if ( $row = db_fetch_row($q) ) return $row["allow_products_search"];
                return false;
        }


        function schUnSetOptionsToSearch( $categoryID )
        {
                $q = db_query( "select optionID from ".CATEGORY_PRODUCT_OPTIONS_TABLE.
                        " where categoryID=".(int)$categoryID);
                $data = array();
                while( $row = db_fetch_row($q) ) $data[] = $row["optionID"];

                foreach( $data as $val )
                {
                        db_query( " delete from ".CATEGORY_PRODUCT_OPTION_VARIANTS.
                                " where categoryID=".(int)$categoryID." AND optionID=".(int)$val);

                        db_query( " delete from ".CATEGORY_PRODUCT_OPTIONS_TABLE.
                                " where categoryID=".(int)$categoryID." AND optionID=".(int)$val);
                }
        }

        function schSetOptionToSearch( $categoryID, $optionID, $set_arbitrarily )
        {
                db_query( "insert into ".CATEGORY_PRODUCT_OPTIONS_TABLE.
                                " ( categoryID, optionID, set_arbitrarily ) ".
                                " values( ".(int)$categoryID.", ".(int)$optionID.", ".(int)$set_arbitrarily." ) " );
        }

        function schOptionIsSetToSearch( $categoryID, $optionID ) {

                $res = array();

                $SQL = 'select set_arbitrarily FROM '.CATEGORY_PRODUCT_OPTIONS_TABLE.' WHERE categoryID='.(int)$categoryID.' AND optionID='.(int)$optionID;
                $q = db_query($SQL);
                if ( $row = db_fetch_row($q) ){

                        $res['isSet'] = 1;
                        $res['set_arbitrarily'] = $row['set_arbitrarily'];
                }else{
                        $res['isSet'] = 0;
                }
                return $res;
        }

        function schUnSetVariantsToSearch( $categoryID, $optionID ){

                $SQL = "DELETE FROM ".CATEGORY_PRODUCT_OPTION_VARIANTS." WHERE categoryID=".(int)$categoryID." AND optionID=".(int)$optionID;
                db_query($SQL);
        }


        function schSetVariantToSearch( $categoryID, $optionID, $variantID )
        {
                db_query( "insert into ".CATEGORY_PRODUCT_OPTION_VARIANTS.
                                " ( optionID, categoryID, variantID )  ".
                                " values( ".(int)$optionID.", ".(int)$categoryID.", ".(int)$variantID." ) " );
        }


        function schVariantIsSetToSearch( $categoryID, $optionID, $variantID ){

                $SQL = "select COUNT(*) FROM ".CATEGORY_PRODUCT_OPTION_VARIANTS." WHERE categoryID=".(int)$categoryID." AND optionID=".(int)$optionID." AND variantID=".(int)$variantID;
                $q = db_query($SQL);
                $row = db_fetch_row($q);
                return ( $row[0] != 0 );
        }


        function &schGetVariantsForSearch($categoryID, $optionID, $variants = null){

                if(is_null($variants)){

                        $variants = optGetOptionValues( $optionID);
                }
                $r_VariantID2Variant = array();
                $r_VariantID = array();
                $TC = count($variants);
                $tTC = 0;
                for ($j=0;$j<$TC;$j++){

                        $r_VariantID[$variants[$j]['variantID']] = &$variants[$j];
                        $tTC++;
                        if(count($r_VariantID)>299||($j+1)==$TC){

                                $SQL = 'select s.variantID, t.variantID FROM ?#CATEGORY_PRODUCT_OPTION_VARIANTS as s left join ?#PRODUCTS_OPTIONS_VALUES_VARIANTS_TABLE as t on(s.variantID=t.variantID) WHERE s.categoryID=? AND s.optionID=? AND s.variantID IN(?@) order by t.sort_order
                                ';
                                $Result = db_phquery($SQL, (int)$categoryID, (int)$optionID, array_keys($r_VariantID));
                                while ($Row = db_fetch_assoc($Result)){

                                        $r_VariantID2Variant[$Row['variantID']] = &$r_VariantID[$Row['variantID']];
                                }
                                $tTC = 0;
                                $r_VariantID = array();
                        }
                }

                return $r_VariantID2Variant;
        }


        function &schOptionsAreSetToSearch( $categoryID, &$options ){

                $TC = count($options);
                $r_OptionID2Option = array();
                $r_OptionID = array();
                $r_OptionRes = array();

                for ($j=0;$j<$TC;$j++){

                        $r_OptionID2Option[$options[$j]['optionID']] = &$options[$j];
                        $r_OptionID[] = $options[$j]['optionID'];
                        if(count($r_OptionID)>299||($j+1)==$TC){

                                $SQL = 'select optionID,set_arbitrarily FROM ?#CATEGORY_PRODUCT_OPTIONS_TABLE
                                        WHERE categoryID=? AND optionID IN(?@)';
                                $Result = db_phquery($SQL, (int)$categoryID, $r_OptionID);
                                while ($Row = db_fetch_assoc($Result)){

                                        $r_OptionRes[$Row['optionID']] = $Row['set_arbitrarily'];
                                }

                                $r_OptionID = array();
                        }
                }

                return $r_OptionRes;
        }

?><?php
#####################################
# ShopCMS: Скрипт интернет-магазина
# Copyright (c) by ADGroup
# http://shopcms.ru
#####################################

  function sess_open($save_path, $session_name)
  {
  return true;
  }

  function sess_close()
  {
  return true;
  }

  function sess_read($key)
  {
  $r = db_query("select data, IP from ".SESSION_TABLE." where id='".mysql_real_escape_string($key)."'");
  if (!$r)
    {
    return "";
    }
  else
    {
    $result = db_fetch_row($r);
    if (!empty($result))
      {
       if(CONF_SECURE_SESSIONS)  {
         if (stGetCustomerIP_Address() != $result[1])   {
            db_query("delete from ".SESSION_TABLE." where id='".mysql_real_escape_string($key)."'");
             return "";
               }
         }
      return $result[0];
      }
    else
      {
      return "";
      }
    }
  }

  function sess_write($key, $val)
  {
  db_query("replace into ".SESSION_TABLE." values ('".mysql_real_escape_string($key)."', '".mysql_real_escape_string($val)."', UNIX_TIMESTAMP() + ".SECURITY_EXPIRE.", '".mysql_real_escape_string(stGetCustomerIP_Address())."', '".mysql_real_escape_string($_SERVER["HTTP_REFERER"])."', '".mysql_real_escape_string($_SERVER["HTTP_USER_AGENT"])."', '".mysql_real_escape_string($_SERVER["REQUEST_URI"])."')");
  }

  function sess_destroy($key)
  {
  db_query("delete from ".SESSION_TABLE." where id='".mysql_real_escape_string($key)."'");
  return true;
  }

  function sess_gc($maxlifetime)
  {
  db_query("delete from ".SESSION_TABLE." where expire < UNIX_TIMESTAMP()");
  }

?><?php
#####################################
# ShopCMS: Скрипт интернет-магазина
# Copyright (c) by ADGroup
# http://shopcms.ru
#####################################




// *****************************************************************************
// Purpose  insert predefined modules setting group into SETTINGS_GROUPS_TABLE table
// Inputs
// Remarks  this function is called in CreateTablesStructureXML, ID of this group equals to
//                result of function settingGetFreeGroupId()
// Returns  nothing
function settingInstall()
{
        db_query("insert into ".SETTINGS_GROUPS_TABLE.
                " ( settings_groupID, settings_group_name, sort_order ) ".
                " values( ".(int)settingGetFreeGroupId().", 'MODULES', 0 ) " );
}


// *****************************************************************************
// Purpose  see settingInstall() function
// Inputs
// Remarks
// Returns  group ID
function settingGetFreeGroupId()
{
        return 1;
}

function settingGetConstNameByID($_SettingID){

        $ReturnVal = '';
        $sql = 'select settings_constant_name FROM '.SETTINGS_TABLE.' WHERE settingsID='.(int)$_SettingID;
        @list($ReturnVal) = db_fetch_row(db_query($sql));
        return $ReturnVal;
}

function settingGetAllSettingGroup()
{
        $q = db_query( "select settings_groupID, settings_group_name, sort_order from ".
                        SETTINGS_GROUPS_TABLE.
                        " where settings_groupID != ".(int)settingGetFreeGroupId().
                        " order by sort_order, settings_group_name " );
        $res = array();
        while( $row = db_fetch_row($q) ) $res[] = $row;
        return $res;
}


function settingGetSetting( $constantName )
{
        $q = db_query("select settingsID, settings_groupID, settings_constant_name, ".
                " settings_value, settings_title, settings_description, ".
                " settings_html_function, sort_order ".
                " from ".SETTINGS_TABLE.
                " where settings_constant_name='".xEscSQL($constantName)."' ");
         return ( $row = db_fetch_row($q) );
}


function settingGetSettings( $settings_groupID )
{
        $q = db_query("select settingsID, settings_groupID, settings_constant_name, ".
                " settings_value, settings_title, settings_description, ".
                " settings_html_function, sort_order ".
                " from ".SETTINGS_TABLE.
                " where settings_groupID=".(int)$settings_groupID." ".
                " order by sort_order, settings_title ");
        $res = array();
        while( $row = db_fetch_row($q) ) $res[] = $row;
        return $res;
}

function _setSettingOptionValue( $settings_constant_name, $value )
{
        db_query("update ".SETTINGS_TABLE." set settings_value='".xToText(trim($value))."' ".
                " where settings_constant_name='".xEscSQL($settings_constant_name)."'" );
}

function _getSettingOptionValue( $settings_constant_name )
{
        $q = db_query("select settings_value from ".SETTINGS_TABLE.
                " where settings_constant_name='".xEscSQL($settings_constant_name)."'" );
        if ( $row = db_fetch_row( $q ) ) return $row["settings_value"];
        return null;
}

function _setSettingOptionValueByID( $settings_constant_id, $value )
{
        $sql = '
                UPDATE '.SETTINGS_TABLE.' SET settings_value="'.xToText(trim($value)).'"
                WHERE settingsID="'.(int)$settings_constant_id.'"
        ';
        db_query($sql);
}

function _getSettingOptionValueByID( $settings_constant_id )
{
        $q = db_query("select settings_value from ".SETTINGS_TABLE.
                " where settingsID=".(int)$settings_constant_id);
        if ( $row = db_fetch_row( $q ) ) return $row["settings_value"];
        return null;
}


function settingCallHtmlFunction( $constantName )
{
        $q = db_query("select settings_html_function, settingsID, settings_constant_name from ".
                SETTINGS_TABLE." where settings_constant_name='".xEscSQL($constantName)."' " );
        if( $row = db_fetch_row($q) )
        {
                $function         =  $row["settings_html_function"];
                $settingsID        =  $row["settingsID"];
                $str = "";
                if ( preg_match('/,[ ]*$|\([ ]*$/',$function))
                        eval( "\$str=".$function."$settingsID);" );
                else
                        eval( "\$str=".$function.";" );
                return $str;
        }
        return false;
}


function settingCallHtmlFunctions( $settings_groupID )
{
        $q = db_query("select settings_html_function, settingsID from ".SETTINGS_TABLE.
                " where settings_groupID=".(int)$settings_groupID." ".
                " order by sort_order, settings_title " );
        $controls = array();
        while( $row = db_fetch_row($q) )
        {
                $function         =  $row["settings_html_function"];
                $settingsID        =  $row["settingsID"];
                $str = "";
                if ( is_bool(strpos($function,")")) )
                        eval( "\$str=".$function."$settingsID);" );
                else
                        eval( "\$str=".$function.";" );
                $controls[] = $str;
        }
        return $controls;
}



// *****************************************************************************
// Purpose        generate define directive withhelp eval function
// Inputs   nothing
// Remarks
// Returns        nothing
function settingDefineConstants()
{
        $dird = dirname($_SERVER['PHP_SELF']);
        $sourcessrandd = array("//" => "/", "\\" => "/");
        $dird = strtr($dird, $sourcessrandd);
        if ($dird != "/") $dirf = "/"; else $dirf = "";
        $url = "http://".$_SERVER["HTTP_HOST"].$dird.$dirf;

        define('CONF_FULL_SHOP_URL', trim($url));

        $q = db_query("select settings_constant_name, settings_value from ".SETTINGS_TABLE);
        while( $row = db_fetch_row($q) ) define($row["settings_constant_name"], $row["settings_value"] );
}


function setting_CHECK_BOX($settingsID)
{
        $q = db_query("select settings_constant_name from ".
                        SETTINGS_TABLE." where settingsID=".(int)$settingsID);
        $row = db_fetch_row( $q );
        $settings_constant_name = $row["settings_constant_name"];

        if ( isset($_POST["save"]) )
                _setSettingOptionValue( $settings_constant_name,
                                isset($_POST["setting".$settings_constant_name])?1:0 );
        $res = "<input type=checkbox name='setting".$settings_constant_name."' value=1 ";
        if ( _getSettingOptionValue($settings_constant_name) )
                $res .= " checked ";
        $res .= ">";
        return $res;
}

// *****************************************************************************
// Purpose
// Inputs
//                        $dataType = 0        - string
//                        $dataType = 1        - float
//                        $dataType = 2        - int
// Remarks
// Returns
function setting_TEXT_BOX($dataType, $settingsID, $BlockInSafeMode = null){

        if(isset($BlockInSafeMode)){

                if($settingsID && CONF_BACKEND_SAFEMODE)return ADMIN_SAFEMODE_BLOCKED;
                else{
                        $settingsID = $BlockInSafeMode;
                }
        }
        $q = db_query("select settings_constant_name from ".
                        SETTINGS_TABLE." where settingsID=".(int)$settingsID);
        $row = db_fetch_row( $q );
        $settings_constant_name = $row["settings_constant_name"];

        if ( isset($_POST["save"]) && isset($_POST["setting".$settings_constant_name]) )
        {
                 if ( $dataType == 0 )
                        $value = $_POST["setting".$settings_constant_name];
                else if ( $dataType == 1 )
                        $value = (float)$_POST["setting".$settings_constant_name];
                else if ( $dataType == 2 )
                        $value = (int)$_POST["setting".$settings_constant_name];
                _setSettingOptionValue( $settings_constant_name, $value );
        }
        return "<input type=text value='"._getSettingOptionValue( $settings_constant_name ).
                        "' name='setting".$settings_constant_name."' >";
}

// *****************************************************************************
// Purpose        same as setting_TEXT_BOX() except for it stores data in encrypted way
// Inputs
//                        $dataType = 0        - string
//                        $dataType = 1        - float
//                        $dataType = 2        - int
// Remarks
// Returns
function setting_TEXT_BOX_SECURE($dataType, $settingsID)
{
        $q = db_query("select settings_constant_name from ".
                        SETTINGS_TABLE." where settingsID=".(int)$settingsID);
        $row = db_fetch_row( $q );
        $settings_constant_name = $row["settings_constant_name"];

        if ( isset($_POST["save"]) && isset($_POST["setting".$settings_constant_name]) )
        {
                 if ( $dataType == 0 )
                        $value = $_POST["setting".$settings_constant_name];
                else if ( $dataType == 1 )
                        $value = (float)$_POST["setting".$settings_constant_name];
                else if ( $dataType == 2 )
                        $value = (int)$_POST["setting".$settings_constant_name];
                _setSettingOptionValue( $settings_constant_name, cryptCCNumberCrypt ( $value , NULL ) );
        }
        return "<input type=text value='".cryptCCNumberDeCrypt( _getSettingOptionValue( $settings_constant_name ) , NULL ).
                        "' name='setting".$settings_constant_name."' >";
}


function setting_DATEFORMAT()
{
        if ( isset($_POST["save"]) )
        {
                if ( isset($_POST["setting_DATEFORMAT"]) )
                {
                        _setSettingOptionValue( "CONF_DATE_FORMAT",
                                $_POST["setting_DATEFORMAT"] );
                }
        }

        $res = "";
        $currencies = currGetAllCurrencies();
        $res = "<select name='setting_DATEFORMAT'>";
        $current_format = _getSettingOptionValue("CONF_DATE_FORMAT");
        if (!$current_format) $current_format = "MM/DD/YYYY";

        //first option  - MM/DD/YYYY - US style
        $res .= "<option value='MM/DD/YYYY'";
        if (!strcmp($current_format,"MM/DD/YYYY")) $res .= " selected";
        $res .= ">MM/DD/YYYY</option>";

        //second option - DD.MM.YYYY - European style
        $res .= "<option value='DD.MM.YYYY'";
        if (!strcmp($current_format,"DD.MM.YYYY")) $res .= " selected";
        $res .= ">DD.MM.YYYY</option>";

        $res .= "</select>";
        return $res;
}


function setting_WEIGHT_UNIT($settingsID)
{
        if ( isset($_POST["save"]) )
                _setSettingOptionValue( "CONF_WEIGHT_UNIT",
                                $_POST["setting_WEIGHT_UNIT"] );
        $res = "<select name='setting_WEIGHT_UNIT'>";

        $units = array(
                                "lbs" => STRING_LBS,
                                "kg" => STRING_KG,
                                "g" => STRING_GRAM
                        );

        foreach( $units as $key => $val )
        {
                $res .= "<option value='".$key."'";
                if ( !strcmp(_getSettingOptionValue("CONF_WEIGHT_UNIT"),$key) )$res .= " selected ";
                $res .= ">";
                $res .= "        ".$val;
                $res .= "</option>";
        }
        $res .= "</select>";
        return $res;
}


function settingCONF_DEFAULT_CURRENCY()
{
        if ( isset($_POST["save"]) )
        {
                if ( isset($_POST["settingCONF_DEFAULT_CURRENCY"]) )
                {
                        _setSettingOptionValue( "CONF_DEFAULT_CURRENCY",
                                $_POST["settingCONF_DEFAULT_CURRENCY"] );
                }
        }

        $res = "";
        $currencies = currGetAllCurrencies();
        $res = "<select name='settingCONF_DEFAULT_CURRENCY'>";
        $res .= "<option value='0'>".ADMIN_NOT_DEFINED."</option>";
        $selectedID = _getSettingOptionValue("CONF_DEFAULT_CURRENCY");
        foreach( $currencies as $currency )
        {
                $res .= "<option value='".$currency["CID"]."' ";
                if ( $selectedID == $currency["CID"] )
                        $res .= " selected ";
                $res .= ">";
                $res .= $currency["Name"];
                $res .= "</option>";
        }
        $res .= "</select>";
        return $res;
}

function settingCONF_MAIL_METHOD()
{
        if ( isset($_POST["save"]) )
        {
                if ( isset($_POST["settingCONF_MAIL_METHOD"]) )
                {
                        _setSettingOptionValue( "CONF_MAIL_METHOD",
                                $_POST["settingCONF_MAIL_METHOD"] );
                }
        }
        $selectedID = _getSettingOptionValue("CONF_MAIL_METHOD");
        $res = "";
        $res = "<select name='settingCONF_MAIL_METHOD'>";
        $res .= "<option value='0'";
        if ( $selectedID == 0 ) $res .= " selected ";
        $res .= ">Smtp</option>";
        $res .= "<option value='1'";
        if ( $selectedID == 1 ) $res .= " selected ";
        $res .= ">Mail</option>";
        $res .= "</select>";
        return $res;
}

function settingCONF_USER_SYSTEM()
{
        if ( isset($_POST["save"]) )
        {
                if ( isset($_POST["settingCONF_USER_SYSTEM"]) )
                {
                        _setSettingOptionValue( "CONF_USER_SYSTEM",
                                $_POST["settingCONF_USER_SYSTEM"] );
                }
        }
        $selectedID = _getSettingOptionValue("CONF_USER_SYSTEM");
        $res = "";
        $res = "<select name='settingCONF_USER_SYSTEM'>";
        $res .= "<option value='0'";
        if ( $selectedID == 0 ) $res .= " selected ";
        $res .= ">".ADMIN_USER_SYST_OFF."</option>";
        $res .= "<option value='1'";
        if ( $selectedID == 1 ) $res .= " selected ";
        $res .= ">".ADMIN_USER_SYST_OFFON."</option>";
        $res .= "<option value='2'";
        if ( $selectedID == 2 ) $res .= " selected ";
        $res .= ">".ADMIN_USER_SYST_ON."</option>";
        $res .= "</select>";
        return $res;
}

function settingCONF_TIMEZONE()
{
        if ( isset($_POST["save"]) )
        {
                if ( isset($_POST["settingCONF_TIMEZONE"]) )
                {
                        _setSettingOptionValue( "CONF_TIMEZONE",
                                $_POST["settingCONF_TIMEZONE"] );
                }
        }
        $selectedID = _getSettingOptionValue("CONF_TIMEZONE");
        $res = "";
        $res = "<select name='settingCONF_TIMEZONE'>";
        $res .= "<option value='-12'";
        if ( $selectedID == -12 ) $res .= " selected ";
        $res .= ">GMT-12</option>";
        $res .= "<option value='-11'";
        if ( $selectedID == -11 ) $res .= " selected ";
        $res .= ">GMT-11</option>";
        $res .= "<option value='-10'";
        if ( $selectedID == -10 ) $res .= " selected ";
        $res .= ">GMT-10</option>";
        $res .= "<option value='-9'";
        if ( $selectedID == -9 ) $res .= " selected ";
        $res .= ">GMT-9</option>";
        $res .= "<option value='-8'";
        if ( $selectedID == -8 ) $res .= " selected ";
        $res .= ">GMT-8</option>";
        $res .= "<option value='-7'";
        if ( $selectedID == -7 ) $res .= " selected ";
        $res .= ">GMT-7</option>";
        $res .= "<option value='-6'";
        if ( $selectedID == -6 ) $res .= " selected ";
        $res .= ">GMT-6</option>";
        $res .= "<option value='-5'";
        if ( $selectedID == -5 ) $res .= " selected ";
        $res .= ">GMT-5</option>";
        $res .= "<option value='-4'";
        if ( $selectedID == -4 ) $res .= " selected ";
        $res .= ">GMT-4</option>";
        $res .= "<option value='-3'";
        if ( $selectedID == -3 ) $res .= " selected ";
        $res .= ">GMT-3</option>";
        $res .= "<option value='-2'";
        if ( $selectedID == -2 ) $res .= " selected ";
        $res .= ">GMT-2</option>";
        $res .= "<option value='-1'";
        if ( $selectedID == -1 ) $res .= " selected ";
        $res .= ">GMT-1</option>";
        $res .= "<option value='0'";
        if ( $selectedID == 0 ) $res .= " selected ";
        $res .= ">GMT+0</option>";
        $res .= "<option value='1'";
        if ( $selectedID == 1 ) $res .= " selected ";
        $res .= ">GMT+1</option>";
        $res .= "<option value='2'";
        if ( $selectedID == 2 ) $res .= " selected ";
        $res .= ">GMT+2</option>";
        $res .= "<option value='3'";
        if ( $selectedID == 3 ) $res .= " selected ";
        $res .= ">GMT+3</option>";
        $res .= "<option value='4'";
        if ( $selectedID == 4 ) $res .= " selected ";
        $res .= ">GMT+4</option>";
        $res .= "<option value='5'";
        if ( $selectedID == 5 ) $res .= " selected ";
        $res .= ">GMT+5</option>";
        $res .= "<option value='6'";
        if ( $selectedID == 6 ) $res .= " selected ";
        $res .= ">GMT+6</option>";
        $res .= "<option value='7'";
        if ( $selectedID == 7 ) $res .= " selected ";
        $res .= ">GMT+7</option>";
        $res .= "<option value='8'";
        if ( $selectedID == 8 ) $res .= " selected ";
        $res .= ">GMT+8</option>";
        $res .= "<option value='9'";
        if ( $selectedID == 9 ) $res .= " selected ";
        $res .= ">GMT+9</option>";
        $res .= "<option value='10'";
        if ( $selectedID == 10 ) $res .= " selected ";
        $res .= ">GMT+10</option>";
        $res .= "<option value='11'";
        if ( $selectedID == 11 ) $res .= " selected ";
        $res .= ">GMT+11</option>";
        $res .= "<option value='12'";
        if ( $selectedID == 12 ) $res .= " selected ";
        $res .= ">GMT+12</option>";


        $res .= "</select>";
        return $res;
}


function settingCONF_DEFAULT_COUNTRY()
{
        if ( isset($_POST["save"]) )
                _setSettingOptionValue( "CONF_DEFAULT_COUNTRY",
                                $_POST["settingCONF_DEFAULT_COUNTRY"] );
        $res = "<select name='settingCONF_DEFAULT_COUNTRY'>";
        $res .= "<option value='0'>".ADMIN_NOT_DEFINED."</option>";
        $selectedID = _getSettingOptionValue("CONF_DEFAULT_COUNTRY");
        $count_row = 0;
        $countries = cnGetCountries( array(), $count_row );

        foreach( $countries as $country )
        {
                $res .= "<option value='".$country["countryID"]."'";
                if ( $selectedID == $country["countryID"] )
                        $res .= " selected ";
                $res .= ">";
              $res .= "        ".$country["country_name"];
                $res .= "</option>";
        }
        $res .= "</select>";
        return $res;
}


function settingCONF_DEFAULT_TAX_CLASS()
{
        if ( isset($_POST["save"]) ) {
        _setSettingOptionValue( "CONF_DEFAULT_TAX_CLASS", $_POST["settingCONF_DEFAULT_TAX_CLASS"] );
        db_query( "update ".PRODUCTS_TABLE." set classID=".(int)$_POST["settingCONF_DEFAULT_TAX_CLASS"]." ");
        }
        $res  = "<select name='settingCONF_DEFAULT_TAX_CLASS'>";
        $res .= "        <option value='0'>".ADMIN_NOT_DEFINED."</option>";
        $selectedID = _getSettingOptionValue("CONF_DEFAULT_TAX_CLASS");
        $count_row = 0;
        $taxClasses = taxGetTaxClasses();
        foreach( $taxClasses as $taxClass )
        {
                $res .= "        <option value='".$taxClass["classID"]."'";
                if ( $selectedID == $taxClass["classID"] )
                        $res .= " selected ";
                $res .= ">";
                $res .= "        ".$taxClass["name"];
                $res .= "</option>";
        }
        $res .= "</select>";
        return $res;
}

function settingCONF_DEFAULT_TEMPLATE()
{
        if ( isset($_POST["save"]) ) {
        _setSettingOptionValue( "CONF_DEFAULT_TEMPLATE", $_POST["settingCONF_DEFAULT_TEMPLATE"] );
        eval( " define('UPDATEDESIGND', 1);" );
        }
        $res  = "<select name='settingCONF_DEFAULT_TEMPLATE'>";
        $selectedID = _getSettingOptionValue("CONF_DEFAULT_TEMPLATE");
        $themelist = array();
        $handle = opendir('core/tpl/user/');
        while ($file = readdir($handle)) {
        if ((!ereg("[.]",$file))) {
        $themelist[] = $file;
        }
        }
        closedir($handle);

        for ($i = 0; $i < count($themelist); $i++) {
        if ($themelist[$i] != "") {
                      $res .= "<option value='".$themelist[$i]."' ";
                                          if ($themelist[$i] == $selectedID) $res .= "selected";
                      $res .= ">".$themelist[$i]."</option>";
                }
        }
        $res .= "</select>";
        return $res;
}

function settingCONF_SELECT_CART_METHOD()
{
        if ( isset($_POST["save"]) ) {
        _setSettingOptionValue( "CONF_CART_METHOD", $_POST["settingCONF_SELECT_CART_METHOD"] );
        if ($_POST["settingCONF_SELECT_CART_METHOD"] == 1){
        _setSettingOptionValue( "CONF_OPEN_SHOPPING_CART_IN_NEW_WINDOW", 1);
        }else{
        _setSettingOptionValue( "CONF_OPEN_SHOPPING_CART_IN_NEW_WINDOW", 0);
        }
        }
        $res  = "<select name='settingCONF_SELECT_CART_METHOD'>";
        $selectedID = _getSettingOptionValue("CONF_CART_METHOD");
        $methodlist = array();
        $methodlist[] = array("title"=>STRING_CART_ID1, "value"=>0);
        $methodlist[] = array("title"=>STRING_CART_ID2, "value"=>1);
        $methodlist[] = array("title"=>STRING_CART_ID3, "value"=>2);

        for ($i = 0; $i < count($methodlist); $i++) {
          if ($methodlist[$i] != "") {
                      $res .= "<option value='".$methodlist[$i]["value"]."' ";
                                          if ($methodlist[$i]["value"] == $selectedID) $res .= "selected";
                      $res .= ">".$methodlist[$i]["title"]."</option>";
                }
        }
        $res .= "</select>";
        return $res;
}

function settingSELECT_USERTEMPLATE()
{
      $res  = "";

      if ( isset($_SESSION["CUSTOM_DESIGN"])){
      $selectedID = $_SESSION["CUSTOM_DESIGN"];
      }else{
      $selectedID = _getSettingOptionValue("CONF_DEFAULT_TEMPLATE");
      }

      $themelist = array();
      $handle = opendir('core/tpl/user/');

      while ($file = readdir($handle)) {
        if ((!ereg("[.]",$file))) {
                        $themelist[] = $file;
                }
      }
      closedir($handle);

      for ($i = 0; $i < count($themelist); $i++) {
        if ($themelist[$i] != "") {
                      $res .= "<option value='".$themelist[$i]."' ";
                                          if ($themelist[$i] == $selectedID) $res .= "selected";
                      $res .= ">".$themelist[$i]."</option>";
                }
      }
        return $res;
}


function settingCONF_DEFAULT_SORT_ORDER()
{
        if ( isset($_POST["save"]) ) {

        _setSettingOptionValue( "CONF_DEFAULT_SORT_ORDER", $_POST["settingCONF_DEFAULT_SORT_ORDER"] );

        }
        $res  = "<select name='settingCONF_DEFAULT_SORT_ORDER'>";
        $selectedsID = _getSettingOptionValue("CONF_DEFAULT_SORT_ORDER");
        $sortlist = array();
        $sortlist[] = array("title"=>STRING_SET_ID3, "value"=>"sort_order, name");
        $sortlist[] = array("title"=>STRING_SET_ID1, "value"=>"Price ASC");
        $sortlist[] = array("title"=>STRING_SET_ID2, "value"=>"Price DESC");

        for ($i = 0; $i < count($sortlist); $i++) {
          if ($sortlist[$i] != "") {
                      $res .= "<option value='".$sortlist[$i]["value"]."' ";
                                          if ($sortlist[$i]["value"] == $selectedsID) $res .= "selected";
                      $res .= ">".$sortlist[$i]["title"]."</option>";
                }
        }
        $res .= "</select>";
        return $res;
}

function settingCONF_DISPLAY_FOTO()
{
        if ( isset($_POST["save"]) ) {

        _setSettingOptionValue( "CONF_DISPLAY_FOTO", $_POST["settingCONF_DISPLAY_FOTO"] );

        }
        $res  = "<select name='settingCONF_DISPLAY_FOTO'>";
        $selectedsID = _getSettingOptionValue("CONF_DISPLAY_FOTO");
        $sortlist = array();
        $sortlist[] = array("title"=>BLOCK_EDIT_9, "value"=>0);
        $sortlist[] = array("title"=>BLOCK_EDIT_6, "value"=>1);

        for ($i = 0; $i < count($sortlist); $i++) {
          if ($sortlist[$i] != "") {
                      $res .= "<option value='".$sortlist[$i]["value"]."' ";
                                          if ($sortlist[$i]["value"] == $selectedsID) $res .= "selected";
                      $res .= ">".$sortlist[$i]["title"]."</option>";
                }
        }
        $res .= "</select>";
        return $res;
}

function settingCONF_DEFAULT_CUSTOMER_GROUP()
{
        if ( isset($_POST["save"]) )
                _setSettingOptionValue( "CONF_DEFAULT_CUSTOMER_GROUP",
                                $_POST["settingCONF_DEFAULT_CUSTOMER_GROUP"] );

        $res = "<select name='settingCONF_DEFAULT_CUSTOMER_GROUP'>";
        $selectedID = _getSettingOptionValue("CONF_DEFAULT_CUSTOMER_GROUP");

        $res .= "<option value='0'>".ADMIN_NOT_DEFINED."</option>";

        $custGroups = GetAllCustGroups();
        foreach( $custGroups as $custGroup )
        {
                $res .= "<option value='".$custGroup["custgroupID"]."'";
                if ( $selectedID == $custGroup["custgroupID"] )
                        $res .= " selected ";
                $res .= ">";
                $res .= "        ".$custGroup["custgroup_name"];
                $res .= "</option>";
        }
        $res .= "</select>";
        return $res;
}


function _CONF_DISCOUNT_TYPE_radio_button( $value, $caption, $checked, $href )
{
        if ( $checked == 1 )
                $checked = "checked";
        else
                $checked = "";
        if ( $href )
        {
                $href1 = "<a href='".ADMIN_FILE."?dpt=custord&sub=custgroup' class=inl>";
                $href2 = "</a>";
        }
        else
        {
                $href1 = "";
                $href2 = "";
        }
        $res  = "";
        $res .= "<tr class=liney>";
        $res .= "<td valign=middle>";
        $res .= "<input class='round' name='settingCONF_DISCOUNT_TYPE' type=radio $checked value='".$value."' id=\"disstatus_".$value."\">";
        $res .= "</td>";
        $res .= "<td valign=middle align=left width=\"100%\"><label for=\"disstatus_".$value."\"> &nbsp;";
        $res .= $caption;
        $res .= "</label></td>";
        $res .= "</tr>";
        return $res;
}


function settingCONF_DISCOUNT_TYPE()
{
        if ( isset($_POST["save"]) )
                _setSettingOptionValue( "CONF_DISCOUNT_TYPE", $_POST["settingCONF_DISCOUNT_TYPE"] );
        $value = _getSettingOptionValue("CONF_DISCOUNT_TYPE");
        $value_go = _getSettingOptionValue("CONF_USER_SYSTEM");
        if ($value_go > 0){
        $res = "";
        $res .= "<table class=and>";
        $res .= _CONF_DISCOUNT_TYPE_radio_button( "1", ADMIN_DISCOUNT_IS_SWITCHED_OFF,  $value=="1"?1:0, 0 );
        $res .= _CONF_DISCOUNT_TYPE_radio_button( "2", ADMIN_DISCOUNT_CUSTOMER_GROUP,         $value=="2"?1:0, 1 );
        $res .= _CONF_DISCOUNT_TYPE_radio_button( "3", ADMIN_DISCOUNT_GENERAL_ORDER_PRICE, $value=="3"?1:0, 0 );
        $res .= _CONF_DISCOUNT_TYPE_radio_button( "4", ADMIN_DISCOUNT_CUSTOMER_GROUP_PLUS_GENERAL_ORDER_PRICE,         $value=="4"?1:0, 0 );
        $res .= _CONF_DISCOUNT_TYPE_radio_button( "5", ADMIN_DISCOUNT_MAX_CUSTOMER_GROUP_GENERAL_ORDER_PRICE,         $value=="5"?1:0, 0 );
        $res .= "</table>";
        }else{
            $res = "";
        $res .= "<table class=and>";
        $res .= _CONF_DISCOUNT_TYPE_radio_button( "1", ADMIN_DISCOUNT_IS_SWITCHED_OFF,  $value=="1"?1:0, 0 );
        $res .= _CONF_DISCOUNT_TYPE_radio_button( "3", ADMIN_DISCOUNT_GENERAL_ORDER_PRICE, $value=="3"?1:0, 0 );
        $res .= "</table>";
        }
        return $res;
}


function settingCONF_NEW_ORDER_STATUS()
{
        if ( isset($_POST["save"]) && isset($_POST["settingCONF_NEW_ORDER_STATUS"]) )
                _setSettingOptionValue( "CONF_NEW_ORDER_STATUS",
                                $_POST["settingCONF_NEW_ORDER_STATUS"] );
        $orders = ostGetOrderStatues( false );

        $res = "";
        if ( count($orders)<2 )
                $res .= "<b>".ADMIN_STATUSES_COUNT_PROMPT_ERROR."<b>";
        else
        {
                $selectedID = _getSettingOptionValue("CONF_NEW_ORDER_STATUS");
                if ( $selectedID == "" )
                        $res .= "<b>".ADMIN_STATUS_IS_NOT_DEFINED."</b>&nbsp;";
                $res .= "<select name='settingCONF_NEW_ORDER_STATUS'>\n";
                foreach( $orders as $order )
                {
                        $res .= "<option value='".$order["statusID"]."' ";
                        if ( $selectedID == $order["statusID"] )
                                $res .= "selected";
                        $res .= ">\n";
                        $res .= "                ".$order["status_name"]."\n";
                        $res .= "</option>\n";
                }
                $res .= "</select>";
        }
        return $res;
}

function settingCONF_COMPLETED_ORDER_STATUS()
{
        $equal_prompt_error = "";
        if ( isset($_POST["save"]) && isset($_POST["settingCONF_COMPLETED_ORDER_STATUS"]) )
        {
                if ( $_POST["settingCONF_NEW_ORDER_STATUS"] ==
                                $_POST["settingCONF_COMPLETED_ORDER_STATUS"] )
                {
                        $equal_prompt_error = ADMIN_STATUSES_EQUAL_PROMPT_ERROR;
                        $_POST["settingCONF_COMPLETED_ORDER_STATUS"] = ostGetOtherStatus(
                                                                        $_POST["settingCONF_COMPLETED_ORDER_STATUS"] );
                        $_POST["settingCONF_COMPLETED_ORDER_STATUS"] =
                                $_POST["settingCONF_COMPLETED_ORDER_STATUS"]["statusID"];
                }
                _setSettingOptionValue( "CONF_COMPLETED_ORDER_STATUS",
                                $_POST["settingCONF_COMPLETED_ORDER_STATUS"] );
        }
        $orders = ostGetOrderStatues( false );
        $res = "";
        if ( count($orders)<2 )
                $res = "<b>".ADMIN_STATUSES_COUNT_PROMPT_ERROR."<b>";
        else
        {
                $selectedID = _getSettingOptionValue("CONF_COMPLETED_ORDER_STATUS");
                if ( $selectedID == "" )
                        $res .= "&nbsp;<b>".ADMIN_STATUS_IS_NOT_DEFINED."</b>";
                $res .= "<select name='settingCONF_COMPLETED_ORDER_STATUS'>\n";
                foreach( $orders as $order )
                {
                        $res .= "<option value='".$order["statusID"]."' ";
                        if ( $selectedID == $order["statusID"] )
                                $res .= "selected";
                        $res .= ">";
                        $res .= "                ".$order["status_name"]."\n";
                        $res .= "</option>\n";
                }
                $res .= "</select>";
        }
        return $res;
}

function setting_ORDER_STATUS_SELECT( $_SettingID ){

        $Options = array(array('title'=>ADMIN_NOT_DEFINED, 'value'=>0,));
        $statuses = ostGetOrderStatues( false );
        foreach ($statuses as $_statuses){

                $Options[] = array(
                        'title'                 => $_statuses['status_name'],
                        'value'         => $_statuses['statusID'],
                        );
        }

        return setting_SELECT_BOX($Options, $_SettingID);
}

function setting_CURRENCY_SELECT( $_SettingID ){

        $Options = array(array('title'=>ADMIN_NOT_DEFINED, 'value'=>0,));
        $Currencies = currGetAllCurrencies();
        foreach ($Currencies as $_Currency){

                $Options[] = array(
                        'title'                 => $_Currency['Name'],
                        'value'         => $_Currency['CID'],
                        );
        }

        return setting_SELECT_BOX($Options, $_SettingID);
}
function settingCONF_COLOR( $settingsID )
{
        $q = db_query("select settingsID, settings_constant_name from ".
                                SETTINGS_TABLE." where settingsID=$settingsID");
        $row = db_fetch_row($q);
        $constant_name = $row["settings_constant_name"];


        if ( isset($_POST["save"]) && isset($_POST["settingCONF_COLOR_".$settingsID])  )
                _setSettingOptionValue( $constant_name,
                                $_POST["settingCONF_COLOR_".$settingsID]  );

        $value = _getSettingOptionValue( $constant_name );
        $value = strtoupper($value);
        $res = "<table><tr><td><table bgcolor=black cellspacing=1><tr><td bgcolor=#".$value.">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td></tr></table></td>";
        $res .= "<td><input type=text value='".$value."' name='settingCONF_COLOR_$settingsID' ></td></tr></table>";
        return $res;
}


function settingCONF_COUNTRY()
{
        if ( isset($_POST["save"]) )
                _setSettingOptionValue( "CONF_COUNTRY",
                                $_POST["settingCONF_COUNTRY"] );

        $count_row = 0;
        $countries = cnGetCountries( array(), $count_row );

        $res = "";

        $selectedID = _getSettingOptionValue("CONF_COUNTRY");
        if ( isset( $_GET["countryID"] ) )
                $selectedID = $_GET["countryID"];
        //if ( $selectedID == "0" )
        //        $res .= "<b>".ADMIN_CONF_COUNTRY_IS_NOT_DEFINED."</b>&nbsp;";
        $onChange = "JavaScript:window.location=\"".ADMIN_FILE."?dpt=conf&sub=setting&settings_groupID=".$_GET["settings_groupID"]."&countryID=\" + document.MainForm.settingCONF_COUNTRY.value";
        // onchange='$onChange'
        $res .= "<select name='settingCONF_COUNTRY' >\n";
        $res .= "        <option value='0'>".ADMIN_NOT_DEFINED."</option>";
        foreach( $countries as $country )
        {
                $res .= "<option value='".$country["countryID"]."' ";
                if ( $selectedID == $country["countryID"] )
                        $res .= "selected";
                $res .= ">\n";
                 $res .= "                ".$country["country_name"]."\n";
                $res .= "</option>\n";
        }
        $res .= "</select>";
        return $res;
}

function settingCONF_ZONE()
{
        if ( isset($_POST["save"]) )
                if ( isset($_POST["settingCONF_ZONE"]) )
                        _setSettingOptionValue( "CONF_ZONE", $_POST["settingCONF_ZONE"] );

        $countries = cnGetCountries( array(), $count_row );
        if ( count($countries) != 0 )
        {

                $countryID = _getSettingOptionValue("CONF_COUNTRY");
                $zones = znGetZones( _getSettingOptionValue("CONF_COUNTRY") );

                $selectedID = _getSettingOptionValue("CONF_ZONE");
                $res = "";
                if ( !ZoneBelongsToCountry($selectedID, $countryID) )
                        $res .= ERROR_ZONE_DOES_NOT_CONTAIN_TO_COUNTRY."<br>";
                if ( count($zones) > 0 )
                {
                        $res .= "<select name='settingCONF_ZONE'>\n";
                        foreach( $zones as $zone )
                        {
                                $res .= "<option value='".$zone["zoneID"]."' ";
                                if ( $selectedID == $zone["zoneID"] )
                                        $res .= "selected";
                                $res .= ">\n";
                             $res .= "                ".$zone["zone_name"]."\n";
                                $res .= "</option>\n";
                        }
                        $res .= "</select>";
                }
                else
                {
                        if ( trim($selectedID) != (string)((int)$selectedID) )
                                $res .= "<input type=text name='settingCONF_ZONE' value='$selectedID'>";
                        else
                                $res .= "<input type=text name='settingCONF_ZONE' value=''>";
                }
                return $res;
        }
        else
                return "-";
}

function settingCONF_CALCULATE_TAX_ON_SHIPPING()
{
        if ( isset($_POST["save"]) )
                _setSettingOptionValue( "CONF_CALCULATE_TAX_ON_SHIPPING", $_POST["settingCONF_CALCULATE_TAX_ON_SHIPPING"] );

        $res = "<select name='settingCONF_CALCULATE_TAX_ON_SHIPPING'>";
        $res .= "        <option value='0'>".ADMIN_NOT_DEFINED."</option>";
        $selectedID = _getSettingOptionValue("CONF_CALCULATE_TAX_ON_SHIPPING");
        $count_row = 0;
        $taxClasses = taxGetTaxClasses();
        foreach( $taxClasses as $taxClass )
        {
                $res .= "<option value='".$taxClass["classID"]."'";
                if ( $selectedID == $taxClass["classID"] )
                        $res .= " selected ";
                $res .= ">";
                $res .= "        ".$taxClass["name"];
                $res .= "</option>";
        }
        $res .= "</select>";
        return $res;
}
 function setting_SELECT_BOX($_Options, $_SettingID){

        if(!is_array($_Options)){

                $_Options = explode(',',$_Options);
                $TC = count($_Options)-1;
                for(;$TC>=0;$TC--){

                        $_Options[$TC] = explode(':', $_Options[$TC]);
                        $_Options[$TC]['title'] = $_Options[$TC][0];
                        if(!isset($_Options[$TC][1])){
                                $_Options[$TC]['value'] = '';
                        }else{
                                $_Options[$TC]['value'] = $_Options[$TC][1];
                        }
                }
        }
        $sql = "select settings_constant_name
                FROM ".SETTINGS_TABLE."
                WHERE settingsID=".(int)$_SettingID;

        $row = db_fetch_row( db_query($sql) );
        $settings_constant_name = $row["settings_constant_name"];

        if ( isset($_POST["save"]) )
                _setSettingOptionValue( $settings_constant_name,         $_POST["setting_".$settings_constant_name] );

        $html = '<select name="setting_'.$settings_constant_name.'">';
        $SettingConstantValue = _getSettingOptionValue($settings_constant_name);
        foreach ($_Options as $_Option){

                $html .= '<option value="'.$_Option['value'].'"'.($SettingConstantValue==$_Option['value']?' selected="selected"':'').'>'.$_Option['title'].'</option>';
        }
        $html .= '</select>';
        return $html;
}

function setting_CHECKBOX_LIST($_boxDescriptions, $_SettingID){

        $sql = "select settings_constant_name
                FROM ".SETTINGS_TABLE."
                WHERE settingsID=".$_SettingID;
        $row = db_fetch_row( db_query($sql) );
        $settings_constant_name = $row["settings_constant_name"];

        if ( isset($_POST["save"]) ){

                $newValues = '';
                $_POST['setting_'.$settings_constant_name] = isset($_POST['setting_'.$settings_constant_name])?$_POST['setting_'.$settings_constant_name]:array();

                $maxOffset = max(array_keys($_boxDescriptions));

                for(; $maxOffset>=0; $maxOffset-- ){

                        $newValues .= (int)in_array($maxOffset, $_POST['setting_'.$settings_constant_name]);
                }
                _setSettingOptionValue( $settings_constant_name,         bindec($newValues) );
        }

        $Value = _getSettingOptionValue($settings_constant_name);
        $html = '';


        foreach ($_boxDescriptions as $_offset=>$_boxDescr){

                $html .= '<div style="padding:2px;"><input'.($Value&pow(2, $_offset)?' checked="checked"':'').' name="setting_'.$settings_constant_name.'[]" value="'.$_offset.'" type="checkbox" style="margin:0px;padding:0px;" />&nbsp;'.$_boxDescr.'</div>';
        }
        return $html;
}

function setting_COUNTRY_SELECT($_ShowButton, $_SettingID = null){

        if(!isset($_SettingID)){

                $_SettingID = $_ShowButton;
                $_ShowButton = false;
        }

        $Options = array(
                array("title"=>'-', "value"=>0)
                );
        $CountriesNum = 0;
        $Countries = cnGetCountries(array('raw data'=>true), $CountriesNum );
        foreach ($Countries as $_Country){

                $Options[] = array("title"=>$_Country['country_name'], "value"=>$_Country['countryID']);
        }
        return '<nobr>'.setting_SELECT_BOX($Options, $_SettingID).($_ShowButton?'&nbsp;&nbsp;<input type="button" name="save55" onclick="document.getElementById(\'save\').name=\'save\';document.getElementById(\'formmodule\').submit(); return false" value=" '.SELECT_BUTTON.' "  style="font-size: 11px; font-family: Tahoma, Arial; border: 1px solid #80A2D9; background-color: #E1ECFD;" />':'').'</nobr>';
}

function setting_ZONE_SELECT($_CountryID, $_Params ,$_SettingID = null){

        $Mode = '';
        if(!isset($_SettingID)){

                $_SettingID = $_Params;
                $Mode = 'simple';
        }elseif(isset($_Params['mode'])) {

                $Mode = $_Params['mode'];
        }
        $Zones = znGetZones($_CountryID);
        $Options = array(
                array("title"=>'-', "value"=>0)
                );
        switch ($Mode){
                default:
                case 'simple':
                        break;
                case 'notdef':
                        if(!count($Zones))return STR_ZONES_NOTDEFINED;
                        break;
        }
        foreach ($Zones as $_Zone){

                $Options[] = array("title"=>$_Zone['zone_name'], "value"=>$_Zone['zoneID']);
        }
        return setting_SELECT_BOX($Options, $_SettingID);
}

function setting_RADIOGROUP($_Options, $_SettingID){

        if(!is_array($_Options)){

                $_Options = explode(',',$_Options);
                $TC = count($_Options)-1;
                for(;$TC>=0;$TC--){

                        $_Options[$TC] = explode(':', $_Options[$TC]);
                        $_Options[$TC]['title'] = $_Options[$TC][0];
                        if(!isset($_Options[$TC][1])){
                                $_Options[$TC]['value'] = '';
                        }else{
                                $_Options[$TC]['value'] = $_Options[$TC][1];
                        }
                }
        }
        $sql = "select settings_constant_name
                FROM ".SETTINGS_TABLE."
                WHERE settingsID=".(int)$_SettingID;
        $row = db_fetch_row( db_query($sql) );
        $settings_constant_name = $row["settings_constant_name"];

        if ( isset($_POST["save"]) )
                _setSettingOptionValue( $settings_constant_name,         $_POST["setting_".$settings_constant_name] );

        $html = '';
        $TC = 0;
        $SettingConstantValue = _getSettingOptionValue($settings_constant_name);
        foreach ($_Options as $_Option){

                $html .= '<input class="inlradio" type="radio" name="setting_'.$settings_constant_name.'" value="'.$_Option['value'].'"'.($SettingConstantValue==$_Option['value']?' checked="checked"':'').' id="id_'.$settings_constant_name.$TC.'" />&nbsp;<label for="id_'.$settings_constant_name.$TC.'">'.$_Option['title'].'</label><br />';
                $TC++;
        }
        return $html;
}

function setting_SINGLE_FILE($_Path, $_SettingID){

        $Error = 0;
        $ConstantName = settingGetConstNameByID($_SettingID);
        if(isset($_POST['save']) && isset($_FILES['setting_'.$ConstantName])){

                if($_FILES['setting_'.$ConstantName]['name']){
                        if(@copy($_FILES['setting_'.$ConstantName]['tmp_name'], $_Path.'/'.$_FILES['setting_'.$ConstantName]['name'])){
                                _setSettingOptionValue($ConstantName, $_FILES['setting_'.$ConstantName]['name']);
                        }else{
                                $Error = 1;
                        }
                }
        }

        $ConstantValue = _getSettingOptionValue($ConstantName);
        return ($Error?'<div>'.ERROR_FAILED_TO_UPLOAD_FILE.'</div>':'').'<input type="file" name="setting_'.$ConstantName.'" /><br />'.($ConstantValue?$ConstantValue:'&nbsp;');
}
?><?php
#####################################
# ShopCMS: Скрипт интернет-магазина
# Copyright (c) by ADGroup
# http://shopcms.ru
#####################################




// *****************************************************************************
// Purpose  delete shipping method
// Inputs
// Remarks
// Returns  nothing
function shDeleteShippingMethod( $SID )
{
        db_query("delete from ".SHIPPING_METHODS_TABLE." where SID=".(int)$SID);
}



// *****************************************************************************
// Purpose  get payment methods by module
// Inputs
// Remarks
// Returns
function shGetShippingMethodsByModule( $shippingModule )
{
        $moduleID = $shippingModule->get_id();

        if ( strlen($moduleID) == 0 )
                return array();

        $moduleID = (int)$moduleID;

        $q = db_query("select SID, Name, description, Enabled, sort_order, ".
                        " email_comments_text, module_id ".
                        " from ".SHIPPING_METHODS_TABLE." where module_id=".(int)$moduleID );
        $data = array();
        while( $row = db_fetch_row($q) ) $data[] = $row;
        return $data;
}




// *****************************************************************************
// Purpose  get shipping method by ID
// Inputs
// Remarks
// Returns
function shGetShippingMethodById( $shippingMethodID )
{
        $q = db_query( "select SID, Name, description, Enabled, sort_order, email_comments_text, module_id from ".
                SHIPPING_METHODS_TABLE." where SID=".(int)$shippingMethodID);
        $row=db_fetch_row($q);
        return $row;
}


// *****************************************************************************
// Purpose  get all shipping methods
// Inputs
// Remarks
// Returns  nothing
function shGetAllShippingMethods( $enabledOnly = false )
{
        $whereClause = "";
        if ( $enabledOnly ) $whereClause = " where Enabled=1 ";
        $q = db_query("select SID, Name, description, Enabled, sort_order, email_comments_text, module_id from ".
                                SHIPPING_METHODS_TABLE." ".$whereClause." order by sort_order");
        $data = array();
        while( $row = db_fetch_row($q) ) $data[] = $row;
        return $data;
}


// *****************************************************************************
// Purpose  get all installed shipping modules
// Inputs
// Remarks
// Returns  nothing
function shGetInstalledShippingModules()
{
        $moduleFiles = GetFilesInDirectory( "core/modules/shipping", "php" );
        $shipping_modules = array();
        foreach( $moduleFiles as $fileName )
        {
                $className = GetClassName( $fileName );
                if(!$className)continue;
                eval( "\$shipping_module = new ".$className."();" );
                if ( $shipping_module->is_installed() )
                        $shipping_modules[] = $shipping_module;
        }
        return $shipping_modules;
}


// *****************************************************************************
// Purpose  add shipping method
// Inputs
// Remarks
// Returns  nothing
function shAddShippingMethod( $Name, $description, $Enabled, $sort_order,
                                $module_id, $email_comments_text )
{
        db_query("insert into ".SHIPPING_METHODS_TABLE.
                        " ( Name, description, email_comments_text, Enabled, module_id, sort_order  ) values".
                        " ( '".xToText(trim($Name))."', '".xEscSQL($description)."', '".xEscSQL($email_comments_text)."', ".(int)$Enabled.", ".(int)$module_id.", ".(int)$sort_order." )" );
        return db_insert_id();
}


// *****************************************************************************
// Purpose  update shipping method
// Inputs
// Remarks
// Returns  nothing
function shUpdateShippingMethod($SID, $Name, $description, $Enabled, $sort_order,
                                $module_id, $email_comments_text )
{
        db_query("update ".SHIPPING_METHODS_TABLE.
                " set Name='".xToText(trim($Name))."', description='".xEscSQL($description)."', email_comments_text='".xEscSQL($email_comments_text)."', ".
                " Enabled=".(int)$Enabled.", module_id=".(int)$module_id.", sort_order=".(int)$sort_order." where SID=".(int)$SID);
}


// *****************************************************************************
// Purpose
// Inputs   $shippingMethodID - shipping exists
// Remarks
// Returns  true if shipping method is exists
function shShippingMethodIsExist( $shippingMethodID )
{
        $q_count = db_query( "select count(*) from ".SHIPPING_METHODS_TABLE.
                        " where SID=".(int)$shippingMethodID." AND Enabled=1" );
        $counts = db_fetch_row( $q_count );
        return ( $counts[0] != 0 );
}

?><?php
#####################################
# ShopCMS: Скрипт интернет-магазина
# Copyright (c) by ADGroup
# http://shopcms.ru
#####################################

// *****************************************************************************
// Purpose        get remote customer computer IP address
// Inputs           $log - login
// Remarks
// Returns        nothing
function stGetCustomerIP_Address()
{
        $ip = ($_SERVER["REMOTE_ADDR"]!="") ? $_SERVER["REMOTE_ADDR"] : 0;
		$ip = (preg_match("/^[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}$/is", $ip))? $ip : 0;
        return $ip;
}



// *****************************************************************************
// Purpose        adds record to customer log
// Inputs   $log - login
// Remarks
// Returns        nothing
function stAddCustomerLog( $log )
{
        $customerID =  regGetIdByLogin( $log );
        if ( $customerID != null )
        {
                $ipAddress = stGetCustomerIP_Address();
                db_query( " insert into ".CUSTOMER_LOG_TABLE.
                          "  (customerID, customer_ip, customer_logtime) ".
                          "  values( ".(int)$customerID.", '".xEscSQL($ipAddress)."', '".xEscSQL(get_current_time())."' ) " );
        }
}


// *****************************************************************************
// Purpose        gets customer log report
// Inputs   nothing
// Remarks
// Returns        array of items
//                                customerID                        - customer ID
//                                customer_ip                        - IP address of customer client PC
//                                customer_logtime                - time of loging
//                                login                                - customer login
function stGetCustomerLogReport()
{
        $q = db_query("select customerID, customer_ip, customer_logtime from ".CUSTOMER_LOG_TABLE );
        $data = array();
        while( $row = db_fetch_row($q) )
        {
                $row["customer_logtime"] = dtConvertToStandartForm( $row["customer_logtime"], 1);
                $row["login"] = regGetLoginById( $row["customerID"] );
                $data[] = $row;
        }
        return array_reverse($data);
}




function stGetLastVists( $log )
{
        $customerID =  regGetIdByLogin( $log );
        $q = db_query( "select customer_logtime from ".CUSTOMER_LOG_TABLE.
                " where customerID=".(int)$customerID." order by customer_logtime DESC");
        $data = array();
        $i = 1;
         while( $row = db_fetch_row($q) )
        {
                if ( $i <= 20 )
                        $data[] = $row;
                else
                        break;
        }
        return array_reverse( $data );
}


// *****************************************************************************
// Purpose
// Inputs   $navigatorParams - item
//                                "offset"                - count row from begin to place being shown
//                                "CountRowOnPage"        - count row on page to show on page
//            $callBackParam - item
//                                "log"                        - customer login
// Remarks
// Returns
//                                returns array of customer visit row
//                                $count_row is set to count rows
function stGetVisitsByLogin( $callBackParam, &$count_row, $navigatorParams = null )
{
        if ( $navigatorParams != null )
        {
                $offset                        = $navigatorParams["offset"];
                $CountRowOnPage        = $navigatorParams["CountRowOnPage"];
        }
        else
        {
                $offset = 0;
                $CountRowOnPage = 0;
        }

        $customerID =  regGetIdByLogin( $callBackParam["log"] );
        $q = db_query( "select customer_logtime, customer_ip from ".CUSTOMER_LOG_TABLE.
                " where customerID=".(int)$customerID." order by customer_logtime DESC" );
        $data = array();
        $i=0;
        while( $row = db_fetch_row($q)  )
        {
                if ( ($i >= $offset && $i < $offset + $CountRowOnPage) ||
                                $navigatorParams == null  )
                {
                        $row["customer_logtime"] = format_datetime( $row["customer_logtime"] );
                        $data[] = $row;
                }
                $i++;
        }
        $count_row = $i;
        return $data;
}


// *****************************************************************************
// Purpose        gets visit count
// Inputs
// Remarks
// Returns
function stGetVisitsCount( $log )
{
        $customerID =  regGetIdByLogin( $log );
        $q = db_query( "select count(*) customer_logtime from ".CUSTOMER_LOG_TABLE.
                " where customerID=".(int)$customerID);
        $row = db_fetch_row( $q );
        return $row[0];
}




// *****************************************************************************
// Purpose        delete all records in customer log
// Inputs   nothing
// Remarks
// Returns        array of items
function stClearCustomerLogReport()
{
        db_query( "delete from ".CUSTOMER_LOG_TABLE );
}




function stChangeOrderStatus( $orderID, $statusID, $comment = '', $notify = 0 )
{
        $q_status_name = db_query( "select status_name from ".ORDER_STATUES_TABLE.
                        " where statusID=".(int)$statusID);
        list($status_name) = db_fetch_row($q_status_name);
        $sql =  "insert into ".ORDER_STATUS_CHANGE_LOG_TABLE.
                " ( orderID, status_name, status_change_time, status_comment ) ".
                " values( ".(int)$orderID.", '".xToText($status_name)."', '".
                        xEscSQL(get_current_time())."', '".xToText(trim($comment))."' ) ";
        db_query($sql);

        if($notify){

                $Order                 = ordGetOrder( $orderID );
                $t                         = '';
                $Email                 = '';
                $FirstName         = '';
                regGetContactInfo(regGetLoginById($Order['customerID']), $t, $Email, $FirstName, $t, $t, $t);

                if(!$Email)
                        $Email = $Order['customer_email'];
                if(!$FirstName)
                        $FirstName = $Order['customer_firstname'];

                xMailTxt($Email, STRING_CHANGE_ORDER_STATUS, 'customer.order.change_status.tpl.html',
                        array(
                                'customer_firstname' => $FirstName,
                                '_MSG_CHANGE_ORDER_STATUS' => str_replace(
                                        array('{STATUS}','{ORDERID}'),
                                        array(($status_name=='STRING_CANCELED_ORDER_STATUS'?STRING_CANCELED_ORDER_STATUS:$status_name), $orderID), MSG_CHANGE_ORDER_STATUS),
                                '_ADMIN_COMMENT' => $comment
                                ));
        }

}



function stGetOrderStatusReport( $orderID )
{
        $q = db_query( "select orderID, status_name, status_change_time, status_comment from ".
                ORDER_STATUS_CHANGE_LOG_TABLE." where orderID=".(int)$orderID);
        $data = array();
        while( $row = db_fetch_row($q) )
        {
                $row["status_change_time"] = format_datetime( $row["status_change_time"] );

                $data[] = $row;
        }
        return $data;
}


function IncrementProductViewedTimes($productID)
{
        db_query("update ".PRODUCTS_TABLE." set viewed_times=viewed_times+1 ".
                " where productID=".(int)$productID);
}

function GetProductViewedTimes($productID)
{
        $q=db_query("select viewed_times from ".PRODUCTS_TABLE." where productID=".(int)$productID);
        $r=db_fetch_query($q);
        return $r["viewed_times"];
}

function GetProductViewedTimesReport($categoryID)
{
        if ( $categoryID != 0 )
        {
                $q=db_query("select name, viewed_times from ".
                        PRODUCTS_TABLE." where categoryID=".(int)$categoryID.
                                " order by viewed_times DESC ");
        }
        else
        {
                $q=db_query("select name, viewed_times from ".
                        PRODUCTS_TABLE." order by viewed_times DESC ");
        }
        $data=array();
        while( $r=db_fetch_row($q) )
        {
                $row=array();
                $row["name"]=$r["name"];
                $row["viewed_times"]=$r["viewed_times"];
                $data[]=$row;
        }
        return $data;
}


function IncrementCategoryViewedTimes($categoryID)
{
        db_query("update ".CATEGORIES_TABLE." set viewed_times=viewed_times+1 ".
                " where categoryID=".(int)$categoryID);
}

function GetCategoryViewedTimes($categoryID)
{
        $q=db_query("select viewed_times from ".
                CATEGORIES_TABLE." where categoryID=".(int)$categoryID);
        $r=db_fetch_query($q);
        return $r["viewed_times"];
}

function GetCategortyViewedTimesReport()
{
        $q=db_query("select categoryID, name, viewed_times from ".CATEGORIES_TABLE." where categoryID!=1 order by viewed_times DESC");
        $data=array();
        while( $r=db_fetch_row($q) )
        {
                $wayadd = '';
                $way = catCalculatePathToCategoryA($r["categoryID"]);
                foreach ($way as $rail) {
                if($rail['categoryID']!=1) $wayadd .= $rail['name'].' / ';
                }
                $row=array();
                $row["name"]=$wayadd."<b>".$r["name"]."</b>";
                $row["viewed_times"]=$r["viewed_times"];
                $data[]=$row;
        }
        return $data;
}

?><?php
#####################################
# ShopCMS: Скрипт интернет-магазина
# Copyright (c) by ADGroup
# http://shopcms.ru
#####################################

function subscrVerifyEmailAddress( $email )
{
        if ( trim($email) == "" )
                return ERROR_INPUT_EMAIL;

        if ( !_testStrInvalidSymbol($email) )
                return ERROR_INPUT_EMAIL;
        if (!preg_match("/^[_\.a-z0-9-]{1,20}@(([a-z0-9-]+\.)+(com|net|org|mil|edu|gov|arpa|info|biz|inc|name|[a-z]{2})|[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3})$/is",$email))
                return ERROR_INPUT_EMAIL;

        return "";
}


// *****************************************************************************
// Purpose        get all subscribers
// Inputs
// Remarks
// Returns
function subscrGetAllSubscriber( $callBackParam, &$count_row, $navigatorParams = null )
{
        if ( $navigatorParams != null )
        {
                $offset                = $navigatorParams["offset"];
                $CountRowOnPage        = $navigatorParams["CountRowOnPage"];
        }
        else
        {
                $offset = 0;
                $CountRowOnPage = 0;
        }

        $sql = 'SELECT mtbl.Email, mtbl.customerID, ctbl.ActivationCode FROM '.MAILING_LIST_TABLE.' as mtbl
                LEFT JOIN '.CUSTOMERS_TABLE.' as ctbl ON ctbl.customerID = mtbl.customerID
                WHERE ctbl.ActivationCode="" OR ctbl.ActivationCode IS NULL
                ORDER BY mtbl.Email';
        $q = db_query( $sql );

        $data = array();
        $i=0;
        while( $row = db_fetch_row($q) )
        {
                if ( ($i >= $offset && $i < $offset + $CountRowOnPage) ||
                        $navigatorParams == null  )
                        $data[] = $row;
                $i++;
        }
        $count_row = $i;
        return $data;
}



function _subscriberIsSubscribed( $email )
{
        $q = db_query( "select count(*) from ".MAILING_LIST_TABLE." where Email='".xToText($email)."'" );
        $countSubscribers = db_fetch_row($q);
        $countSubscribers = $countSubscribers[0];

        return ($countSubscribers != 0);
}


// *****************************************************************************
// Purpose        subscribe unregistered customer
// Inputs
// Remarks
// Returns
function subscrAddUnRegisteredCustomerEmail( $email )
{
        if ( !_subscriberIsSubscribed($email) )
        {
                $q = db_query( "select customerID from ".CUSTOMERS_TABLE." where Email='".xToText($email)."'" );
                if ( $row = db_fetch_row($q) )
                {
                        db_query( "update ".CUSTOMERS_TABLE." set subscribed4news=1 ".
                                " where customerID=".(int)$row["customerID"] );
                        db_query( "insert into ".MAILING_LIST_TABLE." ( Email, customerID ) ".
                                " values ( '".xToText($email)."', ".(int)$row["customerID"]." )" );
                }
                else
                        db_query( "insert into ".MAILING_LIST_TABLE." ( Email ) values ( '".xToText($email)."' )" );
        }
}


// *****************************************************************************
// Purpose        subscribe registered customer
// Inputs
// Remarks
// Returns
function subscrAddRegisteredCustomerEmail( $customerID )
{
        $q = db_query( "select Email from ".CUSTOMERS_TABLE." where customerID=".(int)$customerID );
        $customer = db_fetch_row( $q );
        if ( $customer )
        {
                db_query( "update ".CUSTOMERS_TABLE." set subscribed4news=1 where customerID=".(int)$customerID );

                if (  _subscriberIsSubscribed($customer["Email"])  )
                {
                        db_query( "update ".MAILING_LIST_TABLE.
                                " set customerID=".(int)$customerID.
                                " where Email='".xToText($customer["Email"])."'" );

                }
                else
                        db_query( "insert into ".MAILING_LIST_TABLE.
                                " ( Email, customerID ) ".
                                " values( '".xToText($customer["Email"])."', ".(int)$customerID."  ) " );
        }
}


function subscrUnsubscribeSubscriberByCustomerId( $customerID )
{
        db_query( "delete from ".MAILING_LIST_TABLE." where customerID=".(int)$customerID);
        db_query( "update ".CUSTOMERS_TABLE." set subscribed4news=0 where customerID=".(int)$customerID );
}



function subscrUnsubscribeSubscriberByEmail( $email )
{
        $email = base64_decode($email);
        db_query( "update ".CUSTOMERS_TABLE." set subscribed4news=0  where Email='".xToText($email)."'" );
        db_query( "delete from ".MAILING_LIST_TABLE." where Email='".xToText($email)."'" );
}

function subscrUnsubscribeSubscriberByEmail2( $email )
{
        db_query( "update ".CUSTOMERS_TABLE." set subscribed4news=0  where Email='".xToText($email)."'" );
        db_query( "delete from ".MAILING_LIST_TABLE." where Email='".xToText($email)."'" );
}

function SendNewsMessage( $title, $message )
{
        $q = db_query( "select Email from ".MAILING_LIST_TABLE );
        while( $subscriber = db_fetch_row($q) ) xMailTxtHTMLDATA($subscriber["Email"], $title, $message);
}

?><?php
#####################################
# ShopCMS: Скрипт интернет-магазина
# Copyright (c) by ADGroup
# http://shopcms.ru
#####################################

        // *****************************************************************************
        // Purpose        get tax class by class ID
        // Inputs   $classID
        // Remarks
        // Returns
        //                                "classID"                        tax class ID
        //                                "name"                                tax class name
        //                                "address_type"
        //                                                                        0 - shipping address
        //                                                                        1 - billing address
        function taxGetTaxClassById( $classID )
        {
                $q = db_query("select classID, name, address_type from ".TAX_CLASSES_TABLE.
                        " where classID=".(int)$classID);
                if ( $row = db_fetch_row($q) ) return $row;
        }


        function taxGetTaxClasses()
        {
                $q = db_query("select classID, name, address_type from ".TAX_CLASSES_TABLE );
                $res = array();
                while( $row = db_fetch_row($q) ) $res[]=$row;
                return $res;
        }


        function taxAddTaxClass( $name, $address_type )
        {
                if ( trim($name) == "" ) return;
                db_query("insert into ".TAX_CLASSES_TABLE.
                        "( name, address_type ) ".
                        " values( '".xToText($name),"', ".(int)$address_type."  ) " );
        }

        function taxUpdateTaxClass( $classID, $name, $address_type )
        {
                db_query( "update ".TAX_CLASSES_TABLE.
                        " set name='".xToText($name)."', address_type=".(int)$address_type.
                        " where classID=".(int)$classID);
        }

        function taxDeleteTaxClass( $classID )
        {
                db_query("update ".PRODUCTS_TABLE." set classID=NULL where classID=".(int)$classID);
                db_query("delete from ".TAX_CLASSES_TABLE." where classID=".(int)$classID);
        }


        function taxGetRates( $classID )
        {
                $q=db_query("select classID, countryID, value, isByZone from ".
                                TAX_RATES_TABLE." where classID=".(int)$classID." AND isGrouped=0" );
                $res = array();
                while( $row=db_fetch_row($q) )
                {
                        $q1 = db_query("select country_name from ".COUNTRIES_TABLE.
                                " where countryID=".(int)$row["countryID"] );
                        $country = db_fetch_row($q1);
                        $row["country"] = $country["country_name"];
                        $res[]=$row;
                }

                $q=db_query("select classID, countryID, value, isByZone from ".
                                TAX_RATES_TABLE." where classID=".(int)$classID." AND isGrouped=1" );
                if ( $row=db_fetch_row($q) )
                {
                        $row["countryID"]        = 0;
                        $row["isByZone"]        = 0;
                        $res[]                         = $row;
                }
                return $res;
        }

        function taxGetCountriesByClassID_ToSetRate( $classID )
        {
                $res = array();
                $q = db_query("select countryID, country_name from ".COUNTRIES_TABLE.
                                " order by country_name " );
                while( $country=db_fetch_row($q) )
                {
                        $q1 = db_query("select * from ".TAX_RATES_TABLE.
                                " where countryID=".(int)$country["countryID"].
                                " AND classID=".(int)$classID);
                        if ( !($row=db_fetch_row($q1)) )
                                $res[] = $country;
                }
                return $res;
        }

        function taxAddRate( $classID, $countryID, $isByZone, $value )
        {
                if ( $countryID == 0 )
                {
                        $q = db_query("select countryID from ".COUNTRIES_TABLE );
                        while( $country=db_fetch_row($q) )
                        {
                                $q1 = db_query("select * from ".TAX_RATES_TABLE.
                                        " where countryID=".(int)$country["countryID"].
                                        " AND classID=".(int)$classID);
                                 if ( !$row=db_fetch_row($q1) )
                                {
                                        db_query("insert into ".TAX_RATES_TABLE.
                                                " ( classID, countryID, value, ".
                                                "         isByZone, isGrouped ) ".
                                                " values( ".(int)$classID.", ".(int)$country["countryID"].
                                                        ", ".(float)$value.", 0, 1 )" );
                                }
                        }
                }
                else
                        db_query("insert into ".TAX_RATES_TABLE.
                                " ( classID, countryID, value, isByZone, isGrouped ) ".
                                " values( ".(int)$classID.", ".(int)$countryID.", ".(float)$value.", ".(int)$isByZone.", 0 )" );
        }

        function taxUpdateRate( $classID, $countryID, $isByZone, $value )
        {
                 if ( $countryID == 0 )
                {
                        db_query("update ".TAX_RATES_TABLE.
                                " set isByZone=0, value=".(float)$value.
                                " where classID=".(int)$classID." AND isGrouped=1" );
                }
                else
                {
                        db_query("update ".TAX_RATES_TABLE.
                                " set isByZone=".(int)$isByZone.", value=".(float)$value.
                                " where classID=".(int)$classID." AND countryID=".(int)$countryID.
                                " AND isGrouped=0" );
                }
        }

        function taxSetIsByZoneAttribute( $classID, $countryID, $isByZone )
        {
                if ( $countryID != 0 )
                {
                        db_query( "update ".TAX_RATES_TABLE.
                                          " set isByZone=".(int)$isByZone.
                                          " where classID=".(int)$classID." AND countryID=".(int)$countryID);
                }
        }


        function _deleteRate( $classID, $countryID )
        {
                $q = db_query("select zoneID from ".ZONES_TABLE.
                                " where countryID=".(int)$countryID);
                while( $zone=db_fetch_row($q) )
                        db_query("delete from ".TAX_RATES_ZONES_TABLE.
                                " where classID=".(int)$classID." AND zoneID=".(int)$zone["zoneID"]);
                db_query("delete from ".TAX_ZIP_TABLE.
                                " where classID=".(int)$classID." AND countryID=".(int)$countryID);
                db_query("delete from ".TAX_RATES_TABLE.
                                " where classID=".(int)$classID." AND countryID=".(int)$countryID);
        }


        function taxDeleteRate( $classID, $countryID )
        {
                $res = array();
                if ( $countryID==0 )
                {
                        $q=db_query("select countryID from ".TAX_RATES_TABLE.
                                " where classID=".(int)$classID." AND isGrouped=1");
                        while($row=db_fetch_row($q))
                                $res[] = $row["countryID"];
                }
                else
                        $res[]=$countryID;

                $q_count = db_query("select count(*) from ".TAX_RATES_TABLE.
                                " where classID=".(int)$classID." AND isGrouped=1");
                $count = db_fetch_row( $q_count );
                $count = $count[0];

                if ( $count!=0 && count($res)==1 )
                {
                        db_query("update ".TAX_RATES_TABLE.
                                " set isGrouped=1 ".
                                " where classID=".(int)$classID." AND isGrouped=0 AND ".
                                                "countryID=".(int)$res[0] );
                }
                else
                {
                        foreach( $res as $key => $val )
                                _deleteRate($classID, $val);
                }
        }


        function taxGetCountSetZone( $classID, $countryID )
        {
                $res = array();
                $zones = array();
                 $q = db_query("select zoneID, zone_name from ".ZONES_TABLE.
                                " where countryID=".(int)$countryID);
                while( $row=db_fetch_row($q) ) $zones[] = $row;
                $count = 0;

                foreach( $zones as $zone )
                {
                        $q1=db_query("select classID, zoneID, value from ".
                                TAX_RATES_ZONES_TABLE.
                                " where classID=".(int)$classID." AND zoneID=".(int)$zone["zoneID"] );
                        if ( $resItem=db_fetch_row($q1) ) $count ++;
                }
                return $count;
        }


        function taxGetCountZones( $countryID )
        {
                $q = db_query("select count(*) from ".ZONES_TABLE.
                        " where countryID=".(int)$countryID );
                $row = db_fetch_row($q);
                return $row[0];
        }


        function taxGetZoneRates( $classID, $countryID )
        {
                $res = array();
                $zones = array();
                 $q = db_query("select zoneID, zone_name from ".ZONES_TABLE.
                                " where countryID=".(int)$countryID);
                while( $row=db_fetch_row($q) )
                        $zones[] = $row;

                foreach( $zones as $zone )
                {
                        $q1=db_query("select classID, zoneID, value from ".
                                TAX_RATES_ZONES_TABLE.
                                " where classID=".(int)$classID." AND zoneID=".(int)$zone["zoneID"].
                                " AND isGrouped=0"  );
                        if ( $resItem=db_fetch_row($q1) )
                        {
                                $resItem["zone_name"] = $zone["zone_name"];
                                $resItem["countryID"] = $countryID;
                                $res[] = $resItem;
                        }
                }


                $q1=db_query("select classID, zoneID, value from ".
                        TAX_RATES_ZONES_TABLE." where classID=".(int)$classID." AND isGrouped=1" );
                if ( $resItem=db_fetch_row($q1) )
                {
                        $resItem["zone_name"]         = "";
                        $resItem["zoneID"]                 = 0;
                        $resItem["countryID"]        = $countryID;
                        $res[] = $resItem;
                }

                return $res;
        }

        function taxGetZoneByClassIDCountryID_ToSetRate( $classID, $countryID )
        {
                $res = array();
                $q = db_query("select zoneID, zone_name from ".ZONES_TABLE.
                                " where countryID=".(int)$countryID);
                while( $zone=db_fetch_row($q) )
                {
                        $q1 = db_query("select * from ".TAX_RATES_ZONES_TABLE.
                                " where zoneID=".(int)$zone["zoneID"].
                                " AND classID=".(int)$classID);
                        if ( !($row=db_fetch_row($q1)) ) $res[] = $zone;
                }
                return $res;
        }

        function taxAddZoneRate( $classID, $countryID, $zoneID, $value )
        {
                if ( $zoneID == 0 )
                {
                        $q = db_query("select zoneID, zone_name from ".ZONES_TABLE.
                                " where countryID=".(int)$countryID);
                        while( $zone=db_fetch_row($q) )
                        {
                                $q1 = db_query("select * from ".TAX_RATES_ZONES_TABLE.
                                                " where zoneID=".(int)$zone["zoneID"].
                                                " AND classID=".(int)$classID);
                                if ( !($row=db_fetch_row($q1)) )
                                {
                                        db_query("insert into ".TAX_RATES_ZONES_TABLE.
                                                 "( classID, zoneID, value, isGrouped ) ".
                                                 "values( ".(int)$classID.", ".(int)$zone["zoneID"].", ".(float)$value.", 1 ) " );
                                }
                        }
                }
                else
                        db_query( "insert into ".TAX_RATES_ZONES_TABLE.
                                  " ( classID, zoneID, value, isGrouped ) ".
                                  " values( ".(int)$classID.", ".(int)$zoneID.", ".(float)$value.", 0 ) " );
        }


        function taxUpdateZoneRate( $classID, $zoneID, $value )
        {
                if ( $zoneID == 0 )
                        db_query( "update ".TAX_RATES_ZONES_TABLE.
                                " set value=".(float)$value.
                                " where classID=".(int)$classID." AND isGrouped=1" );
                else
                        db_query( "update ".TAX_RATES_ZONES_TABLE.
                                " set value=".(float)$value.
                                " where classID=".(int)$classID." AND zoneID=".(int)$zoneID.
                                " AND isGrouped=0" );
        }

        function taxDeleteZoneRate( $classID, $zoneID )
        {
                if ( $zoneID==0 )
                        db_query("delete from ".TAX_RATES_ZONES_TABLE.
                                " where classID=".(int)$classID." AND isGrouped=1");
                else
                {
                        $q_count = db_query("select count(*) from ".TAX_RATES_ZONES_TABLE.
                                " where classID=".(int)$classID." AND isGrouped=1");
                        $count = db_fetch_row( $q_count );
                        $count = $count[0];

                        if ( $count == 0 )
                                db_query("delete from ".TAX_RATES_ZONES_TABLE.
                                        " where classID=".(int)$classID." AND zoneID=".(int)$zoneID);
                        else
                                db_query( "update ".TAX_RATES_ZONES_TABLE.
                                        " set isGrouped=1 ".
                                        " where classID=".(int)$classID." AND zoneID=".(int)$zoneID);
                }
        }


        function taxGetZipRates( $classID, $countryID )
        {
                $q = db_query( "select tax_zipID, classID, countryID, zip_template, value from ".TAX_ZIP_TABLE.
                        " where classID=".(int)$classID." AND countryID=".(int)$countryID);
                $data = array();
                while( $row=db_fetch_row($q) ) $data[] = $row;
                return $data;
        }

        function taxAddZipRate( $classID, $countryID, $zip_template, $rate )
        {
                $rate = (float)$rate;
                db_query(
                                "insert into ".TAX_ZIP_TABLE.
                                " ( classID, countryID, zip_template, value ) ".
                                " values( ".(int)$classID.", ".(int)$countryID.", '".xEscSQL($zip_template)."', ".$rate." ) ");
        }

        function taxUpdateZipRate( $tax_zipID, $zip_template, $rate )
        {

                $rate = (float)$rate;
                db_query(
                        "update ".TAX_ZIP_TABLE.
                        " set ".
                        " zip_template='".xEscSQL($zip_template)."', ".
                        " value=".$rate.
                        " where tax_zipID=".(int)$tax_zipID);
        }

        function taxDeleteZipRate( $tax_zipID )
        {
                db_query( "delete from ".TAX_ZIP_TABLE.
                        " where tax_zipID=".(int)$tax_zipID);
        }


        function _testTemplateZip( $zip_template, $zip )
        {
                if ( strlen($zip_template)==strlen($zip) )
                {
                        $testResult = true;
                        $starCounter=0;
                        for( $i=0; $i<strlen($zip); $i++ )
                        {
                                if ( ($zip[$i]==$zip_template[$i]) ||
                                                        $zip_template[$i]=='*' )
                                {
                                        if ( $zip_template[$i]=='*' )
                                                $starCounter++;
                                        continue;
                                }
                                else
                                {
                                        $testResult = false;
                                        break;
                                }
                        }
                        if ( $testResult )
                                return $starCounter;
                        else
                                return false;
                }
                else
                        return false;
        }


        function _getBestZipRate( $classID, $countryID, $zip )
        {
                $q=db_query( "select tax_zipID, zip_template, value from ".
                                TAX_ZIP_TABLE." where classID=".(int)$classID." AND countryID=".(int)$countryID);
                $testZipTemplateArray = array();
                while( $row=db_fetch_row($q) )
                {
                        $res = _testTemplateZip( $row["zip_template"], $zip );
                        if ( !is_bool($res) )
                                $testZipTemplateArray[] = array(
                                                        "starCounter" => $res,
                                                        "rate" => $row["value"] );
                }

                if ( count($testZipTemplateArray) == 0 )
                        return null;

                // define "starCounter" minimum
                $starCounterMinIndex = 0;
                for( $i=0; $i < count($testZipTemplateArray); $i++ )
                        if ( $testZipTemplateArray[$starCounterMinIndex]["starCounter"] >
                                        $testZipTemplateArray[$i]["starCounter"] )
                                $starCounterMinIndex = $i;

                return (float)$testZipTemplateArray[$starCounterMinIndex]["rate"];
        }



        // *****************************************************************************
        // Purpose   calculate tax by addresses and productID
        // Inputs    $productID - product ID
        //                         $shippingAddressID - shipping address ID
        //                         $billingAddress        - billing address ID
        // Remarks
        // Returns
        function taxCalculateTax( $productID, $shippingAddressID, $billingAddressID )
        {
                $shippingAddress        = regGetAddress( $shippingAddressID );
                $billingAddress                = regGetAddress( $billingAddressID );
                return taxCalculateTax2( $productID, $shippingAddress, $billingAddress );
        }



        // *****************************************************************************
        // Purpose   calculate tax by addresses and productID
        // Inputs    $productID - product ID
        //                        $shippingAddress - array of
        //                                "countryID"
        //                                "zoneID"
        //                                "zip"
        //                        $billingAddress - array of
        //                                "countryID"
        //                                "zoneID"
        //                                "zip"
        // Remarks
        // Returns
        function taxCalculateTax2( $productID, $shippingAddress, $billingAddress )
        {
                $productID = (int) $productID;

                if ( trim($productID) == "" || $productID == null )
                        return 0;

                // get tax class
                $q = db_query("select classID from ".PRODUCTS_TABLE.
                        " where productID=".(int)$productID);
                $row = db_fetch_row( $q );
                $taxClassID = $row["classID"];

                if ( $taxClassID == null )
                        return 0;

                return taxCalculateTaxByClass2( $taxClassID, $shippingAddress, $billingAddress );
        }


        // *****************************************************************************
        // Purpose
        // Inputs    $taxClassID - tax class ID
        //                        $shippingAddress - array of
        //                                "countryID"
        //                                "zoneID"
        //                                "zip"
        //                        $billingAddress - array of
        //                                "countryID"
        //                                "zoneID"
        //                                "zip"
        // Remarks
        // Returns
        function taxCalculateTaxByClass( $taxClassID, $shippingAddressID, $billingAddressID )
        {
                $shippingAddress        = regGetAddress( $shippingAddressID );
                $billingAddress                = regGetAddress( $billingAddressID );
                return taxCalculateTaxByClass2( $taxClassID, $shippingAddress, $billingAddress );
        }


        // *****************************************************************************
        // Purpose
        // Inputs    $taxClassID - tax class ID
        //                        $shippingAddress - array of
        //                                "countryID"
        //                                "zoneID"
        //                                "zip"
        //                        $billingAddress - array of
        //                                "countryID"
        //                                "zoneID"
        //                                "zip"
        // Remarks
        // Returns
        function taxCalculateTaxByClass2( $taxClassID, $shippingAddress, $billingAddress )
        {
                $class = taxGetTaxClassById( $taxClassID );

                // get address
                if ( $class["address_type"] == 0 )
                {
                        $address = $shippingAddress;
                }
                else
                {
                        $address = $billingAddress;
                }

                if  ( $address == null )
                        return 0;

                // get tax rate
                $address["countryID"] = (int) $address["countryID"];

                $q = db_query( "select value, isByZone from  ".TAX_RATES_TABLE.
                        " where classID=".(int)$taxClassID." AND countryID=".(int)$address["countryID"]  );
                if ( $row=db_fetch_row($q) )
                {
                        $value                = $row["value"];
                        $isByZone        = $row["isByZone"];
                }
                else
                {
                        $q = db_query( "select value, isByZone from ".TAX_RATES_TABLE.
                                " where isGrouped=1 AND classID=".(int)$taxClassID);
                        if ( $row=db_fetch_row($q) )
                        {
                                $value                = $row["value"];
                                $isByZone        = $row["isByZone"];
                        }
                        else
                                return 0;
                }

                if ( $isByZone == 0 )
                        return $value;
                else
                {
                        $res = _getBestZipRate( $taxClassID, $address["countryID"], $address["zip"] );
                        if ( !is_null($res) )
                                return $res;
                        else
                        {
                                if ( is_null($address["zoneID"]) || trim($address["zoneID"]) == "" )
                                        return 0;

                                $q = db_query( "select value from ".TAX_RATES_ZONES_TABLE.
                                        " where classID=".(int)$taxClassID." AND zoneID=".(int)$address["zoneID"] );
                                if ( ($row=db_fetch_row($q)) )
                                        return $row["value"];
                                else
                                {
                                        $q = db_query("select value from ".TAX_RATES_ZONES_TABLE.
                                                " where classID=".(int)$taxClassID." AND isGrouped=1" );
                                        if ( ($row=db_fetch_row($q)) )
                                                return $row["value"];
                                        else
                                                return 0;
                                }
                        }
                }
        }

?><?php
#####################################
# ShopCMS: Скрипт интернет-магазина
# Copyright (c) by ADGroup
# http://shopcms.ru
#####################################




function verInstall()
{
        db_query("insert into ".SYSTEM_TABLE.
                        " ( varName, value ) ".
                        " values( 'version_number', '".STRING_VERSION."' ) ");

        db_query("insert into ".SYSTEM_TABLE.
                        " ( varName, value ) ".
                        " values( 'version_name', '".STRING_PRODUCT_NAME."' ) ");
}

function verGetPackageVersion()
{
        $q = db_query("select varName, value from ".SYSTEM_TABLE);
        $row = array("");
        while ( $row && strcmp($row[0], "version_number") )
        {
                $row = db_fetch_row($q);
        }
        return (float) $row[1];
}

function verUpdatePackageVersion()
{
        db_query("update ".SYSTEM_TABLE." set value = '".STRING_VERSION."' where varName = 'version_number'");
}

function verUpdatePackageName(){

        db_query("update ".SYSTEM_TABLE." set value = '".STRING_PRODUCT_NAME."' where varName = 'version_name'");
}
?>
<?php
#####################################
# ShopCMS: Скрипт интернет-магазина
# Copyright (c) by ADGroup
# http://shopcms.ru
#####################################




// *****************************************************************************
// Purpose        determine weither zone belongs to particlar country
// Inputs
// Remarks
// Returns        true if zone belongs to particlar country
function ZoneBelongsToCountry($zoneID, $countryID)
{
        $q = db_query("select count(*) from ".ZONES_TABLE." where countryID=".(int)$countryID);
        $row = db_fetch_row( $q );
        if ( $row[0]!=0 )
        {
                if ( trim($zoneID) == (string)((int)$zoneID)  )
                {
                        $q = db_query("select count(*) from ".ZONES_TABLE.
                                " where countryID=".(int)$countryID." AND zoneID=".(int)$zoneID);
                        $row = db_fetch_row( $q );
                        return ($row[0] != 0);
                }
                else
                        return false;
        }
        return true;
}




// *****************************************************************************
// Purpose        gets all zones
// Inputs                     nothing
// Remarks
// Returns                array of maunfactirer, each item of this array
//                                have next struture
//                                        "zoneID"        - id
//                                        "zone_name"        - zone name
//                                        "zone_code"        - zone code
//                                        "countryID"        - countryID
function znGetZones( $countryID = null )
{
        if ( $countryID == null )
                $q=db_query("select zoneID, zone_name, ".
                        " zone_code, countryID from ".ZONES_TABLE." ".
                        " order by zone_name" );
        else
                $q=db_query("select zoneID, zone_name, ".
                        " zone_code, countryID from ".ZONES_TABLE." ".
                        " where countryID=".(int)$countryID." order by zone_name" );
        $data=array();
        while( $r=db_fetch_row($q) ) $data[]=$r;
        return $data;
}


// *****************************************************************************
// Purpose        gets all zones of particular country
// Inputs                     country ID
// Remarks
// Returns                array of zone, each item of this array
//                                have next struture
//                                        "zoneID"        - id
//                                        "zone_name"        - zone name
//                                        "zone_code"        - zone code
//                                        "countryID"        - countryID
function znGetZonesById($countryID)
{
        if ( is_null($countryID) || $countryID == "" ) $countryID = "NULL";
        else $countryID = (int)$countryID;
        $q=db_query("select zoneID, zone_name, ".
                " zone_code, countryID from ".ZONES_TABLE." ".
                " where countryID=".$countryID." order by zone_name " );
        $data=array();
        while( $r=db_fetch_row($q) ) $data[]=$r;
        return $data;
}

// *****************************************************************************
// Purpose        gets zone by zone ID
// Inputs                     zone ID
// Remarks
// Returns        array of
//                        "zoneID"        - id
//                        "zone_name"        - zone name
//                        "zone_code"        - zone code
//                        "countryID"        - countryID
function znGetSingleZoneById( $zoneID )
{
        if ( is_null($zoneID) || $zoneID == "" ) $zoneID = "NULL";
        else $zoneID = (int)$zoneID;
        $q=db_query( "select zoneID, zone_name, ".
                        " zone_code, countryID from ".ZONES_TABLE." ".
                        " where zoneID=".$zoneID);
        $r = db_fetch_row($q);
        return $r;
}



// *****************************************************************************
// Purpose        deletes Zone
// Inputs                     id
// Remarks
// Returns                nothing
function znDeleteZone($zoneID)
{
        $tax_classes = taxGetTaxClasses();
        foreach( $tax_classes as $classr ) taxDeleteZoneRate( $classr["classID"], $zoneID );

        db_query("update ".CUSTOMER_ADDRESSES_TABLE." set zoneID=NULL where zoneID=".(int)$zoneID);
        db_query("delete from ".ZONES_TABLE." where zoneID=".(int)$zoneID);
}


// *****************************************************************************
// Purpose        updates Zone
// Inputs                     $zoneID                - id
//                                        $zone_name        - zone name
//                                        $zone_code        - code zone
// Remarks
// Returns                nothing
function znUpdateZone( $zoneID, $zone_name, $zone_code, $countryID )
{
        db_query("update ".ZONES_TABLE." set ".
                "  zone_name='".xToText(trim($zone_name))."', ".
                "  zone_code='".xToText(trim($zone_code))."', ".
                "  countryID=".(int)$countryID.
                "  where zoneID=".(int)$zoneID);
}


// *****************************************************************************
// Purpose        adds zone
// Inputs
//                        $zone_name        - zone name
//                        $zone_code        - code zone
// Remarks
// Returns                nothing
function znAddZone( $zone_name, $zone_code, $countryID  )
{
        db_query("insert into ".ZONES_TABLE.
                "( zone_name, zone_code, countryID )".
                "values( '".xToText(trim($zone_name))."', '".xToText(trim($zone_code))."', ".(int)$countryID." )" );
        return db_insert_id();
}

?><?php
#####################################
# ShopCMS: Скрипт интернет-магазина
# Copyright (c) by ADGroup
# http://shopcms.ru
#####################################


function GetAllAdminAttributes()
{
        $q = db_query("select customerID, Login, actions from ".CUSTOMERS_TABLE." where actions!='' ORDER BY Login ASC");
        $data = array();
        while( $row = db_fetch_row( $q ) )
        {
                $row[2] = unserialize( $row[2] );
                if(in_array(100,$row[2]))$data[] = $row;
        }
        return $data;
}

function CheckLoginAdminNew($login)
{
        $q = db_query("select count(*) from ".CUSTOMERS_TABLE." where Login='".xEscSQL($login)."'");
                      $n = db_fetch_row($q);
                      $data = $n[0];
        return $data;
}

function adminpgGetadminPage( $admin_ID )
{
        $q = db_query("select Login, actions from ".CUSTOMERS_TABLE." where customerID=".(int)$admin_ID);
        $row = db_fetch_row($q);
        $row[1] = unserialize( $row[1] );
        return $row;
}



function UpdateAdminRights( $edit_num, $actions)
{
        $actions[] = 100;
        $actions = xEscSQL(serialize ($actions));
        db_query("update ".CUSTOMERS_TABLE." set actions='".$actions."' where customerID=".(int)$edit_num);
}


function adminpgDeleteadmin( $admin_page_ID )
{
        db_query("delete from ".CUSTOMERS_TABLE." where customerID=".(int)$admin_page_ID);
}


?><?php    
#####################################
# ShopCMS: Скрипт интернет-магазина
# Copyright (c) by ADGroup
# http://shopcms.ru
#####################################

function add_department($admin_dpt)
  //adds new $admin_dpt to departments list
{
  global $admin_departments;
  $i = 0;
  while ( $i < count($admin_departments) && $admin_departments[$i]["sort_order"] < $admin_dpt["sort_order"] ) $i++;
  for ( $j = count($admin_departments) - 1; $j >= $i; $j-- )
    $admin_departments[$j + 1] = $admin_departments[$j];
  $admin_departments[$i] = $admin_dpt;
}

function CloseWindow()
{
}

// *****************************************************************************
// Purpose        gets client JavaScript to open in new window
// Inputs
// Remarks
// Returns
function OpenConfigurator($optionID, $productID)
{
  $url = ADMIN_FILE."?do=configurator&optionID=".$optionID."&productID=".$productID;
  echo ( "<script type='text/javascript'>\n" );
  echo ( "                w=450; \n" );
  echo ( "                h=400; \n" );
  echo ( "                link='".$url."'; \n" );
  echo ( "                var win = 'width='+w+',height='+h+',menubar=no,location=no,resizable=yes,scrollbars=yes';\n" );
  echo ( "                wishWin = window.open(link,'wishWin',win);\n" );
  echo ( "</script>\n" );
}

// *****************************************************************************
// Purpose        gets client JavaScript to reload opener page
// Inputs
// Remarks
// Returns
function ReLoadOpener()
{
  if ( $_GET["productID"] == 0 )
    $categoryID = $_POST["categoryID"];
  else
  {
    $q = db_query("select categoryID from ".PRODUCTS_TABLE." where productID=".( int ) $_GET["productID"]);
    $r = db_fetch_row($q);
    $categoryID = $r["categoryID"];
  }
  Redirect(ADMIN_FILE."?dpt=catalog&sub=products_categories&categoryID=".$categoryID."&expandCat=".$categoryID);
}

function ReLoadOpener2($productID)
{
  if ( isset ( $productID ))
  {
    $q = db_query("select categoryID from ".PRODUCTS_TABLE." where productID=".( int ) $productID);
    $r = db_fetch_row($q);
    $categoryID = $r["categoryID"];
  }
  Redirect(ADMIN_FILE."?dpt=catalog&sub=products_categories&categoryID=".$categoryID."&expandCat=".$categoryID);
}

function ReLoadOpener3($productID)
{
  if ( isset ( $productID ))
  {
    Redirect(ADMIN_FILE."?productID=".( int ) $productID."&PhotoHideTable_hidden=1&eaction=prod");
  }
}

function deleteSubCategories($parent)
  //deletes all subcategories of category with categoryID=$parent
{
//subcategories
  $q = db_query("select categoryID FROM ".CATEGORIES_TABLE." WHERE parent=".( int ) $parent." and categoryID>1");
  while ( $row = db_fetch_row($q))
  {
    deleteSubCategories($row[0]);
    //recurrent call
  }
  $q = db_query("DELETE FROM ".CATEGORIES_TABLE." WHERE parent=".( int ) $parent." and categoryID>1");
  //move all product of this category to the root category
  $q = db_query("UPDATE ".PRODUCTS_TABLE." SET categoryID=1 WHERE categoryID=".( int ) $parent);
}

function category_Moves_To_Its_SubDirectories($cid, $new_parent)
{
  $a = false;
  $q = db_query("select categoryID FROM ".CATEGORIES_TABLE." WHERE parent=".( int ) $cid." and categoryID>1");
  while ( $row = db_fetch_row($q)) if ( !$a )
  {
    if ( $row[0] == $new_parent )
      return true;
    else
      $a = category_Moves_To_Its_SubDirectories($row[0], $new_parent);
  }
  return $a;
}

function _getOptions()
{
  $options = optGetOptions();
  for ( $i = 0; $i < count($options); $i++ )
  {
    if ( isset ( $_GET["categoryID"] ))
      $res = schOptionIsSetToSearch($_GET["categoryID"], $options[$i]["optionID"]);
    else
      $res = array( "isSet" => true, "set_arbitrarily" => 1 );
    if ( $res["isSet"] )
    {
      $options[$i]["isSet"] = true;
      $options[$i]["set_arbitrarily"] = $res["set_arbitrarily"];
    }
    else
    {
      $options[$i]["isSet"] = false;
      $options[$i]["set_arbitrarily"] = 1;
    }
    $options[$i]["variants"] = optGetOptionValues($options[$i]["optionID"]);
    for ( $j = 0; $j < count($options[$i]["variants"]); $j++ )
    {
      $isSet = false;
      if ( isset ( $_GET["categoryID"] ))
        $isSet = schVariantIsSetToSearch($_GET["categoryID"], $options[$i]["optionID"], $options[$i]["variants"][$j]["variantID"]);
      $options[$i]["variants"][$j]["isSet"] = $isSet;
    }
  }
  return $options;
}

?><?php
#####################################
# ShopCMS: Скрипт интернет-магазина
# Copyright (c) by ADGroup
# http://shopcms.ru
#####################################

function export_exportSubcategories($_pCategoryID, &$exportCategories, &$params){

        if(!$_pCategoryID){

                foreach ($_SESSION['checkedCategories'] as $_categoryID=>$_checked){

                        if(in_array($_categoryID, $exportCategories[0]))continue;
                        if(in_array($_categoryID, $exportCategories[1]))continue;

                        $exportCategories[intval($_checked)][] = $_categoryID;
                        if($_checked){

//print '='.$_categoryID.'--'.$_checked.'<br />';
                                if(isset($_SESSION['selectedProducts'][$_categoryID])){

                                        foreach ($_SESSION['selectedProducts'][$_categoryID] as $__ProductID=>$__Checked){

                                                if($params['exprtUNIC']['mode'] == 'toarrays'){

                                                        $params['exprtUNIC']['expProducts'][] = $__ProductID;
                                                        continue;
                                                }
                                                __exportProduct($__ProductID, $params);
                                        }
                                }else {

//print '-'.$_categoryID.'--'.$_checked.'<br />';
                                        $Count = 0;
                                        $callBackParam = array();
                                        $callBackParam["categoryID"] = intval($_categoryID);
                                        $callBackParam["searchInSubcategories"] = true;
                                        $_Products = prdSearchProductByTemplate($callBackParam,$Count);
//                                        $_Products = prdGetProductByCategory( array('categoryID'=>intval($_categoryID), 'fullFlag'=>false), $_t );
                                        foreach ($_Products as $__Product){

                                                if(!$__Product['enabled'])continue;
                                                if($params['exprtUNIC']['mode'] == 'toarrays'){

                                                        $params['exprtUNIC']['expProducts'][] = $__Product['productID'];
                                                        continue;
                                                }
                                                __exportProduct($__Product['productID'], $params);
                                        }
                                }
                        }
                        export_exportSubcategories($_categoryID, $exportCategories, $params);
                }
                return 1;
        }


        $_subs = catGetSubCategoriesSingleLayer($_pCategoryID);
        foreach ($_subs as $__Category){

                $_CategoryID = $__Category['categoryID'];
                if(isset($_SESSION['checkedCategories'][$_CategoryID])){

                        $_t = intval($_SESSION['checkedCategories'][$_CategoryID])?intval($_SESSION['checkedCategories'][$_CategoryID]):isset($_SESSION['selectedProducts'][$_CategoryID]);
                        $exportCategories[$_t][] = $_CategoryID;
                } elseif (in_array($_pCategoryID, $exportCategories[1]) ){

                        $exportCategories[1][] = $_CategoryID;
                }

                if(isset($exportCategories[1][count($exportCategories[1])-1]))
                if($exportCategories[1][count($exportCategories[1])-1] == $_CategoryID){

                        if(isset($_SESSION['selectedProducts'][$_CategoryID])){

                                foreach ($_SESSION['selectedProducts'][$_CategoryID] as $__ProductID=>$__Checked){

                                        if($params['exprtUNIC']['mode'] == 'toarrays'){

                                                $params['exprtUNIC']['expProducts'][] = $__ProductID;
                                                continue;
                                        }
                                        __exportProduct($__ProductID, $params);
                                }
                        }else {

                                $Count = 0;
                                $callBackParam = array();
                                $callBackParam["categoryID"] = intval($_CategoryID);
                                $callBackParam["searchInSubcategories"] = true;
                                $_Products = prdSearchProductByTemplate($callBackParam,$Count);
//                                $_Products = prdGetProductByCategory( array('categoryID'=>intval($_CategoryID), 'fullFlag'=>false), $_t );
                                foreach ($_Products as $__Product){

                                        if(!$__Product['enabled'])continue;
                                        if($params['exprtUNIC']['mode'] == 'toarrays'){

                                                $params['exprtUNIC']['expProducts'][] = $__Product['productID'];
                                                continue;
                                        }
                                        __exportProduct($__Product['productID'], $params);
                                }
                        }
                }else {
                        if(!isset($_SESSION['isExploded'][$_CategoryID]))continue;
                        if(!$_SESSION['isExploded'][$_CategoryID] && !$_SESSION['checkedCategories'][$_CategoryID])continue;
                }

                export_exportSubcategories($_CategoryID, $exportCategories, $params);
        }
}

?><?php
#####################################
# ShopCMS: Скрипт интернет-магазина
# Copyright (c) by ADGroup
# http://shopcms.ru
#####################################

// *****************************************************************************
// Purpose
// Inputs   $xmlTable - table XML node
// Remarks
// Returns        "select" SQL query to getting all data of this table
function _getSelectStatement( $tableName, $columnsClause)
{
        $sql = "select ".$columnsClause." from ".$tableName;
        return $sql;
}


// *****************************************************************************
// Purpose
// Inputs                $xmlTable        - table XML node
//                                $row                - data to insret
// Remarks
// Returns        "insert" SQL query to table corresponded to $xmlTable
function _getInsertStatement( $xmlTable, $row, $columns = NULL, $attributes = NULL, $columnsClause )
{
        $sql = "INSERT INTO ";
        if (!$attributes)
                $attributes = $xmlTable->GetXmlNodeAttributes();
        $tableAlias = $attributes["NAME"];
        $tableName  = $attributes["NAME"];
        // exceptions
        if ( $tableName == CATEGORIES_TABLE )
                if ( $row["categoryID"] == 1 )
                        return "";

        $sql .= $tableAlias;
        $valueClause        = "";

        if (!$columns)
                $columns = $xmlTable->SelectNodes("table/column");
        $i = 0;
        foreach( $columns as $xmlColumn )
        {
                $attributes = $xmlColumn->GetXmlNodeAttributes();
                $columnName = $xmlColumn->GetXmlNodeData();
                $columnName = trim($columnName);

                $type = strtoupper( $attributes["TYPE"] );
                if (        strstr($type, "CHAR") ||
                                strstr($type, "VARCHAR") ||
                                strstr($type, "TEXT") ||
                                strstr($type, "DATETIME") )
                {
                        $cellValue = $row[$i];
                        $cellValue = xEscSQL( $cellValue );
                        $value = "'".$cellValue."'";
                }
                else
                        $value = $row[ $i ];

                 if ( $row[ $i ] == null && trim($row[ $i ]) == "" )
                        $value = "NULL";

                if ( $i == 0 ) $valueClause .= $value;
                else $valueClause .= ", ".$value;

                $i++;
        }

        $sql .= " (".$columnsClause.") values (".$valueClause.")";
        $sql = str_replace(DB_PRFX, "DBPRFX_", $sql);
        return $sql;
}


// *****************************************************************************
// Purpose        compile delete SQL statement to delete all data in data base table
// Inputs   $xmlTable - table XML node
// Remarks
// Returns        "delete from" SQL statements
function _getDeleteStatement( $xmlTable )
{
        $sql = "delete from ";
        $attributes = $xmlTable->GetXmlNodeAttributes();
        $tableName = $attributes["NAME"];
        $sql .= $tableName;
        return $sql;
}


// *****************************************************************************
// Purpose        read data from data base and transform it into SQL instructions ("insert into")
// Inputs   $xmlTable - table XML node
// Remarks
// Returns        "insert into" SQL statements separated by ';'
function _tableSerialization( $xmlTable )
{
        $res = "";



        $columnsClause        = "";

        $columns = $xmlTable->SelectNodes("table/column");
        $attributes = $xmlTable->GetXmlNodeAttributes();

        $i = 0;
        foreach( $columns as $xmlColumn )
        {
                $attr = $xmlColumn->GetXmlNodeAttributes();
                $columnName = $xmlColumn->GetXmlNodeData();
                $columnName = trim($columnName);

                if ( $i == 0 ) $columnsClause .= $columnName;
                else $columnsClause .= ", ".$columnName;
                $i++;
        }

        $tableName = $attributes["NAME"];


        $selectSql = _getSelectStatement( $tableName, $columnsClause );
        $q = db_query( $selectSql );
        while( $row = db_fetch_row($q) )
        {
                $insertSql = _getInsertStatement( $xmlTable, $row, $columns, $attributes, $columnsClause );
                if ( $insertSql == "" )
                        continue;
                $res .= $insertSql.";\n";
        }

        return $res;
}

function _isIdentityTable($tableName)
{
        $xmlTables=new XmlNode();
        $xmlTables->LoadInnerXmlFromFile( DATABASE_STRUCTURE_XML_PATH );
        $array=$xmlTables->SelectNodes("DataBaseStructure/tables/table");
        foreach($array as $xmlTable)
        {
                $attributes = $xmlTable->GetXmlNodeAttributes();
                if ( strtoupper($tableName) == strtoupper($attributes["NAME"]) )
                {
                        $columns = $xmlTable->SelectNodes("table/column");
                        foreach( $columns as $xmlColumn )
                        {
                                $attributes = $xmlColumn->GetXmlNodeAttributes();
                                if ( isset($attributes["IDENTITY"]) )
                                {
                                        if (  trim(strtoupper($attributes["IDENTITY"])) == "TRUE"  )
                                                return  true;
                                        else
                                                return true;
                                }
                                else
                                        return false;
                        }
                }
        }
        return null;
}



// *****************************************************************************
// Purpose        read all products and categories from data base and
//                                        transform it into SQL instructions ("insert into")
// Inputs   $fileName - file to write
// Remarks
// Returns
function serProductAndCategoriesSerialization($fileName)
{
        $f = gzopen( $fileName, "w" );
        $xmlTables = new XmlNode();
        $xmlTables->LoadInnerXmlFromFile( DATABASE_STRUCTURE_XML_PATH );
        $array = $xmlTables->SelectNodes("DataBaseStructure/tables/table");
        foreach($array as $xmlTable)
        {
                $attrubtes = $xmlTable->GetXmlNodeAttributes();
                 if ( isset($attrubtes["PRODUCTANDCATEGORYSYNC"]) )
                        if ( strtoupper($attrubtes[ "PRODUCTANDCATEGORYSYNC" ]) == "TRUE" )
                        {
                                $res = _tableSerialization( $xmlTable );
                                gzputs( $f, $res."\n" );
                        }
        }
        gzclose( $f );
}



// *****************************************************************************
// Purpose        clear all products and categories
// Inputs   nothing
// Remarks
// Returns        nothing
function serDeleteProductAndCategories()
{
        /* SLOW OBSOLETE METHOD

        $q = db_query( "select categoryID from ".CATEGORIES_TABLE." where categoryID<>0" );
        while( $row=db_fetch_row($q) )
                DeleteAllProductsOfThisCategory( $row["categoryID"] );

        $xmlTables=new XmlNode();
        $xmlTables->LoadInnerXmlFromFile( DATABASE_STRUCTURE_XML_PATH );
        $array=$xmlTables->SelectNodes("DataBaseStructure/tables/table");
        foreach($array as $xmlTable)
        {
                $attrubtes = $xmlTable->GetXmlNodeAttributes();
                 if ( isset($attrubtes["PRODUCTANDCATEGORYSYNC"]) )
                        if ( strtoupper($attrubtes[ "PRODUCTANDCATEGORYSYNC" ]) == "TRUE" )
                                 db_query( _getDeleteStatement($xmlTable) );
        }

        //add root category
        db_query("insert into ".CATEGORIES_TABLE."( name, parent, categoryID )".
                        "values( 'ROOT', NULL, 1 )");
        */

        imDeleteAllProducts();
}


function _getChar( &$fileContent, $pointer, $strlen )
{
        if ( $pointer <= $strlen - 1 )
                return $fileContent[$pointer];
        else
                return ' ';
}


function _passSeparator( &$fileContent, &$pointer, $strlen )
{
        while( (_getChar( $fileContent, $pointer, $strlen ) == ' ' || _getChar( $fileContent, $pointer, $strlen ) == '\n' ||
                    _getChar( $fileContent, $pointer, $strlen ) == '\t' || _getChar( $fileContent, $pointer, $strlen ) == '\r' ||
                        _getChar( $fileContent, $pointer, $strlen ) == ';'  ) &&
                      $pointer <= $strlen-1 )
                $pointer ++;
}

function _passSpaces( &$fileContent, &$pointer, $strlen )
{
        while( (_getChar( $fileContent, $pointer, $strlen ) == ' ' || _getChar( $fileContent, $pointer, $strlen ) == '\n' ||
                    _getChar( $fileContent, $pointer, $strlen ) == '\t' || _getChar( $fileContent, $pointer, $strlen ) == '\r') &&
                      $pointer <= $strlen-1 )
                $pointer ++;
}

function _passOpenBracket( &$fileContent, &$pointer, $strlen )
{
        while( (_getChar( $fileContent, $pointer, $strlen ) == ' ' || _getChar( $fileContent, $pointer, $strlen ) == '\n' ||
                    _getChar( $fileContent, $pointer, $strlen ) == '\t' || _getChar( $fileContent, $pointer, $strlen ) == '\r' ||
                        _getChar( $fileContent, $pointer, $strlen ) == '(' ) && $pointer <= $strlen-1 )
                $pointer ++;
}

function _passValues( &$fileContent, &$pointer, $strlen )
{
        $inQuotes = 0;
        while(1)
        {
                if ( _getChar( $fileContent, $pointer, $strlen ) == "\\" && $inQuotes == 1 &&
                                        _getChar( $fileContent, $pointer + 1, $strlen ) == "'" )
                                        $pointer += 2;
                if ( _getChar( $fileContent, $pointer, $strlen ) == "'" && $inQuotes == 0 )
                        $inQuotes = 1;
                else if ( _getChar( $fileContent, $pointer, $strlen ) == "'" && $inQuotes == 1 )
                        $inQuotes = 0;
                else if ( _getChar( $fileContent, $pointer, $strlen ) == ')' && $inQuotes == 0 )
                        return;
                $pointer++;
        }
}

function _passCloseBracket( &$fileContent, &$pointer, $strlen )
{
        while( _getChar( $fileContent, $pointer, $strlen ) != ')' && $pointer <= $strlen-1 )
                $pointer ++;
        $pointer ++;
}

function _getWord( &$fileContent, &$pointer, $strlen )
{
        $begin = $pointer;
        while( _getChar( $fileContent, $pointer, $strlen ) != ' ' &&  _getChar( $fileContent, $pointer, $strlen ) != '\n' &&
                   _getChar( $fileContent, $pointer, $strlen ) != '\t' && _getChar( $fileContent, $pointer, $strlen ) != '\r' &&
                        _getChar( $fileContent, $pointer, $strlen ) != '(' && _getChar( $fileContent, $pointer, $strlen ) != ')' &&
                                $pointer <= $strlen - 1 )
                $pointer ++;
        return substr( $fileContent, $begin, $pointer - $begin );
}


// *****************************************************************************
// Purpose
// Inputs   nothing
// Remarks
// Returns  string contained "insert into ... " statement
function _getNextSqlInsertStatement( &$fileContent, &$pointer, $strlen )
{
        if ( $pointer >= $strlen - 1  )
                return null;

        $res = array();
        $res["Error"] = true;

        _passSeparator( $fileContent, $pointer, $strlen );

        if ( $pointer >= $strlen - 1  )
                return null;

        $begin = $pointer;

        // "insert"
        _passSpaces( $fileContent, $pointer, $strlen );
        $insertWord = _getWord( $fileContent, $pointer, $strlen );
        if ( strcasecmp( "insert", trim($insertWord) ) )
                return $res;

        // "into"
        _passSpaces( $fileContent, $pointer, $strlen );
        $intoWord = _getWord( $fileContent, $pointer, $strlen );
        if ( strcasecmp( "into", $intoWord ) )
                return $res;

        // table name
        _passSpaces( $fileContent, $pointer, $strlen );
        $tableWord = _getWord( $fileContent, $pointer, $strlen );
                $res["tableName"] = $tableWord;

        _passOpenBracket( $fileContent, $pointer, $strlen );
        _passCloseBracket( $fileContent, $pointer, $strlen );

        // "values"
        _passSpaces( $fileContent, $pointer, $strlen );
        $valuesWord = _getWord( $fileContent, $pointer, $strlen );
        if ( strcasecmp( "values", $valuesWord ) )
                return $res;

        _passOpenBracket( $fileContent, $pointer, $strlen );
        _passValues( $fileContent, $pointer, $strlen );

        _passSpaces( $fileContent, $pointer, $strlen );

        unset($res["Error"]);
        $res["statement"] = substr( $fileContent, $begin, $pointer - $begin + 1 );

        $pointer++;
        return $res;
}



function _testSpace( $char )
{
        return ($char == " " || $char == "\t" || $char == "\n" || $char == "\r" || $char == ";");
}

// *****************************************************************************
// Purpose        replace table constant name in insert statement to table name
// Inputs   $insertSqlQuery -  SQL insert statement
// Remarks
// Returns  null if function failed
//                        insert SQL statement
function _serReplaceConstantName( $insertSqlQuery )
{
        $insertClause = "INSERT";
        $intoClause = "INTO";
        $charIndex = 0;

        // pass spaces
        for( ; $charIndex<strlen($insertSqlQuery); $charIndex++ )
        {
                if (  !_testSpace( $insertSqlQuery[$charIndex] )   )
                        break;
        }

        // pass "INSERT" word
        $i = 0;
        for( ; $i < strlen($insertClause); $i++,$charIndex++ )
                if (  strtoupper($insertClause[$i]) != strtoupper($insertSqlQuery[$charIndex])  )
                        return null;

        // pass spaces
        for( ; $charIndex<strlen($insertSqlQuery); $charIndex++ )
                if ( !_testSpace( $insertSqlQuery[$charIndex] ) )
                        break;

        // pass "INTO" word
        $i = 0;
        for( ; $i < strlen($intoClause); $i++,$charIndex++ )
                if (  strtoupper($intoClause[$i]) != strtoupper($insertSqlQuery[$charIndex])  )
                        return null;

        // pass spaces
        for( ; $charIndex<strlen($insertSqlQuery); $charIndex++ )
                if ( !_testSpace($insertSqlQuery[$charIndex]) )
                        break;

        if ( $charIndex == strlen($insertSqlQuery) )
                return null;

        $constantNameBeginIndex = $charIndex;

        // pass constant name
        $constantName = "";
        for( ; $charIndex<strlen($insertSqlQuery); $charIndex++ )
        {
                if ( $insertSqlQuery[$charIndex] != ' '  && $insertSqlQuery[$charIndex] != '\t' &&
                         $insertSqlQuery[$charIndex] != '\n' && $insertSqlQuery[$charIndex] != '\r' &&
                         $insertSqlQuery[$charIndex] != '(' )
                        $constantName .= $insertSqlQuery[$charIndex];
                else
                        break;
        }

        $tableName = constant( $constantName );

        $begin        = substr( $insertSqlQuery, 0, $constantNameBeginIndex );
        $end        = substr( $insertSqlQuery, $constantNameBeginIndex+strlen($constantName) );
        return $begin." ".$tableName." ".$end;
}



function _serImport( $fileName, $replaceConstantName, $autoIncrementId = false )
{
        $pointer = 0;
        $str = myfile_get_contents( $fileName );
        $str = trim( $str );
        $str = str_replace("DBPRFX_", DB_PRFX, $str);
        $tableName = "";

        $strlen = strlen( $str );

        while( ($sqlInsret = _getNextSqlInsertStatement( $str, $pointer, $strlen )) != null )
        {

                if ( isset($sqlInsret["Error"]) )
                        return false;

                if ( $replaceConstantName )
                        db_query( _serReplaceConstantName($sqlInsret["statement"]) );
                else
                        db_query( $sqlInsret["statement"] );
        }
        return true;
}


// *****************************************************************************
// Purpose
// Inputs   nothing
// Remarks
// Returns  nothing
function serImportWithConstantNameReplacing( $fileName, $autoIncrementId = false )
{
        _serImport( $fileName, true, $autoIncrementId );
}

// *****************************************************************************
// Purpose
// Inputs   nothing
// Remarks
// Returns  nothing
function serImport( $fileName, $autoIncrementId = false )
{
        _serImport( $fileName, false, $autoIncrementId );
}




function _filterBadSymbolsToExcel( $str )
{
        $str = str_replace( "\r\n", "", $str );
        $str = str_replace( "<br>", " ", $str );

        $semicolonFlag = false;
        for( $i=0; $i<strlen($str); $i++ )
        {
                if ( $str[$i] == ";" )
                {
                        $semicolonFlag = true;
                        break;
                }
        }

        if ( !$semicolonFlag )
                return $str;
        else
        {
                $res = "";
                for( $i=0; $i<strlen($str); $i++  )
                {
                        if ( $str[$i] == "\"" )
                                $res .= "\"\"";
                        else
                                $res .= $str[$i];
                }
                return "\"".$res."\"";
        }
}


// *****************************************************************************
// Purpose
// Inputs   nothing
// Remarks
// Returns  nothing
function serExportCustomersToExcel( $customers )
{
        $maxCountAddress = 0;
        foreach( $customers as $customer )
        {
                $q = db_query("select count(*) from ".CUSTOMER_ADDRESSES_TABLE.
                                " where customerID=".$customer["customerID"] );
                $countAddress = db_fetch_row( $q );
                $countAddress = $countAddress[0];
                if ( $maxCountAddress < $countAddress )
                        $maxCountAddress = $countAddress;
        }

        // open file to write
        $f = fopen( "core/temp/customers.csv", "w" );

        // head table generate
        $headLine = "Login;First name;Last name;Email;Group;Registered;Newsletter subscription;";
        $q = db_query( "select reg_field_ID, reg_field_name from ".CUSTOMER_REG_FIELDS_TABLE.
                        " order by sort_order " );
        while( $row = db_fetch_row($q) )
                $headLine .= _filterBadSymbolsToExcel( $row["reg_field_name"] ).";";

        for( $i=1; $i<=$maxCountAddress; $i++ )
                $headLine .= "Address ".$i.";";
        fputs( $f, $headLine."\n" );

        foreach( $customers as $customer )
        {
                $q = db_query( "select Login, first_name, last_name, Email, custgroupID, reg_datetime, subscribed4news from ".CUSTOMERS_TABLE.
                        " where addressID=".(int)$customer["addressID"] );
                $row_cust = db_fetch_row( $q );

                if ( $row_cust["custgroupID"] != null )
                {
                        $q = db_query( "select custgroup_name from ".CUSTGROUPS_TABLE.
                                " where custgroupID=".$row_cust["custgroupID"] );
                        $row = db_fetch_row($q);
                        $row_cust["custgroup_name"] = $row["custgroup_name"];
                }
                else
                        $row_cust["custgroup_name"] = "";

                if ( $row_cust["subscribed4news"] )
                        $row_cust["subscribed4news"] = "+";
                else
                        $row_cust["subscribed4news"] = "";

                $line = "";
                $line .= _filterBadSymbolsToExcel( $row_cust["Login"] ).";";
                $line .= _filterBadSymbolsToExcel( $row_cust["first_name"] ).";";
                $line .= _filterBadSymbolsToExcel( $row_cust["last_name"] ).";";
                $line .= _filterBadSymbolsToExcel( $row_cust["Email"] ).";";
                $line .= _filterBadSymbolsToExcel( $row_cust["custgroup_name"] ).";";
                $line .= _filterBadSymbolsToExcel( $row_cust["reg_datetime"] ).";";
                $line .= $row_cust["subscribed4news"].";";

                $q_reg_param = db_query( "select reg_field_ID, reg_field_name from ".CUSTOMER_REG_FIELDS_TABLE.
                        " order by sort_order " );
                while( $row = db_fetch_row($q_reg_param) )
                {
                        $q_reg_value = db_query( "select reg_field_value from ".CUSTOMER_REG_FIELDS_VALUES_TABLE.
                                        " where reg_field_ID=".$row["reg_field_ID"]." AND customerID=".
                                                        $customer["customerID"] );
                        $value = db_fetch_row( $q_reg_value );
                        $value = $value["reg_field_value"];

                        $line .= _filterBadSymbolsToExcel( $value ).";";
                }

                $countAddress = 0;
                $addresses = regGetAllAddressesByLogin( regGetLoginById($customer["customerID"]) );
                foreach( $addresses as $address )
                {
                        $line .= " "._filterBadSymbolsToExcel( regGetAddressStr($address["addressID"]) ).";";
                        $countAddress ++;
                }

                for( $i=1; $i<=$maxCountAddress-$countAddress; $i++ )
                        $line .= ";";

                fputs( $f, $line."\n" );
        }

        fclose($f);
}

?><?php
#####################################
# ShopCMS: Скрипт интернет-магазина
# Copyright (c) by ADGroup
# http://shopcms.ru
#####################################


// *****************************************************************************
// Purpose        get array xml node corresponded to data base table
// Inputs   file name
// Remarks
// Returns        see 'Purpose'
function GetXmlTableNodeArray( $fileName )
{
        $xmlTables        = new XmlNode();
        $xmlTables->LoadInnerXmlFromFile( $fileName );
        $array = $xmlTables->SelectNodes( "DataBaseStructure/tables/table" );
        return $array;
}


// *****************************************************************************
// Purpose  call install functions such as ostInstall and etc.
// Inputs   nothing
// Remarks
// Returns  nothing
function CallInstallFunctions()
{
        ostInstall();
        catInstall();
        settingInstall();
        verInstall();
}

// *****************************************************************************
// Purpose        creates tables corresponded to structure database XML file
// Inputs   file name
// Remarks
// Returns        SQL script to be shown
function CreateTablesStructureXML($fileName)
{
        $xmlTables=new XmlNode();
        $xmlTables->LoadInnerXmlFromFile($fileName);
        $array=$xmlTables->SelectNodes("DataBaseStructure/tables/table");
        $sqlToShow="<table>";

        // adds "create table" SQL statements into $sql
        foreach($array as $xmlTable)
        {
                $tableSql = GetCreateTableSQL($xmlTable);
                if ( is_bool($tableSql) )
                        return "ERROR";
                else
                {
                        $sqlToShow .= "<tr><td>".GetIB_IdentityGenerator( $xmlTable );
                        db_query( $tableSql );
                        $sqlToShow .= $tableSql;
                        $sqlToShow .= GetIB_IdentityTrigger( $xmlTable );
                        $sqlToShow .= "</td></tr>";
                }
        }
        $sqlToShow .= "</table>";

        // install functions
        CallInstallFunctions();

        return $sqlToShow;
}



// *****************************************************************************
// Purpose        creates refer constraints corresponded to structure database XML file
// Inputs   file name
// Remarks
// Returns        SQL script to be shown
function CreateReferConstraintsXML($fileName)
{
        $_SESSION["ForeignKeyIndex"] = 0;

        $xmlTables=new XmlNode();
        $xmlTables->LoadInnerXmlFromFile($fileName);
        $array=$xmlTables->SelectNodes("DataBaseStructure/tables/table");
        $sqlToShow = "<table>";

        // adds "alter table " SQL statements into $sqlToShow
        foreach($array as $xmlTable)
        {
                $sqlArray = GetReferConstraint($xmlTable);
                foreach( $sqlArray as $constraintSql )
                {
                        if ( $constraintSql != "" )
                        {
                                db_query( $constraintSql );
                                $sqlToShow .= "<tr><td>".$constraintSql."</td></tr>";
                        }
                }
        }

         unset( $_SESSION["ForeignKeyIndex"] );

        $sqlToShow .= "</table>";
        return $sqlToShow;
}

// *****************************************************************************
// Purpose        creates refer constraints corresponded to structure database XML file
// Inputs   file name
// Remarks
// Returns        SQL script to be shown
function DestroyReferConstraintsXML($fileName)
{
        if ( DBMS == "mysql" || DBMS == "ib" )
                return;

        $xmlTables=new XmlNode();
        $xmlTables->LoadInnerXmlFromFile($fileName);
        $array=$xmlTables->SelectNodes("DataBaseStructure/tables/table");
        $sqlToShow = "<table>";

        foreach($array as $xmlTable)
        {
                $attr = $xmlTable->GetXmlNodeAttributes();
                $tableName = $attr["NAME"];
                $foreignKeys = $xmlTable->SelectNodes("table/ForeignKey");
                foreach( $foreignKeys as $foreignKey )
                {
                        $attributes = $foreignKey->GetXmlNodeAttributes();
                        $splitAttr        = explode( ".", $attributes["REFERTO"] );
                        $constraintName = GetForeignKeyName( $tableName, $foreignKey );
                        $sql =
                                "ALTER TABLE ".$tableName.
                                " DROP CONSTRAINT ".$constraintName;
                        db_query( $sql );
                        $sqlToShow .= "<tr><td>".$sql."</td></tr>";
                }
        }

        $sqlToShow .= "</table>";
        return $sqlToShow;
}


// *****************************************************************************
// Purpose        creates tables.inc.php with define directive for each database table
//                                defined in database XML file
// Inputs
//                        $TablesIncFfileName - tables.inc.php in config directory
//                        $XmlFileName                - database XML file name
// Remarks
//                        forech table in XML file this function writes define directive
//                                define('<table_alias>', '<table_name>');
//                                where
//                                        <table_alias> correspondes alias attribute of table node
//                                        <table_name>  correspondes name attribute of table node
// Returns        nothing
function CreateTablesIncFile($TablesIncFfileName, $XmlFileName)
{
        $f = fopen($TablesIncFfileName,"w");
        $xmlTables=new XmlNode();
        $xmlTables->LoadInnerXmlFromFile( $XmlFileName );
        $array=$xmlTables->SelectNodes( "DataBaseStructure/tables/table" );

        fputs( $f, "<?php\n");
        fputs( $f, "\n");
        foreach( $array as $xmlTable )
        {
                $attrubtes = $xmlTable->GetXmlNodeAttributes();
                fputs( $f, "if (  !defined('".$attrubtes["ALIAS"]."')  ) \n" );
                fputs( $f, "{\n" );
                $s = "        define('".$attrubtes["ALIAS"]."', '".$attrubtes["NAME"]."');";
                fputs( $f, $s."\n" );
                fputs( $f, "\n" );
                fputs( $f, "}\n" );
        }
        fputs( $f, "?>"  );
        fclose( $f);

}


function CompareArrays($columns1, $columns2)
{
        if (  count($columns1) != count($columns2) )
                return false;
        for($i=0; $i < count($columns2); $i++)
                if ( trim($columns1[$i]) != trim($columns2[$i]) )
                        return false;
        return true;
}



function GetForeignKeyName( $tableName, $xmlForeignKey )
{
        if ( DBMS == "mssql" )
        {
                $attrubtes = $xmlForeignKey->GetXmlNodeAttributes();
                $data           = $xmlForeignKey->GetXmlNodeData();
                $splitAttr = explode( ".", $attrubtes["REFERTO"] );

                $foreignKey = trim($data);
                $array = explode( ",", $foreignKey);
                if ( count($array) != 1 )
                {
                        $foreignKey = "";
                        foreach( $array as $val )
                                $foreignKey .= trim($val);
                }

                $primaryKey = $splitAttr[1];
                $array = explode( ",", $primaryKey );
                if ( count($array) != 1 )
                {
                        $primaryKey = "";
                        foreach( $array as $val )
                                $primaryKey .= trim($val);
                }

                $constraintName = trim($tableName)."___".trim($splitAttr[0])."_".
                                $foreignKey."_".$primaryKey;
                return $constraintName;
        }
        else
                return "FK_".$_SESSION["ForeignKeyIndex"]."ID";
}


// *****************************************************************************
// Purpose        gets refer constraints
// Inputs   table node ( that is XmlNode object )
// Remarks
// Returns        array of SQL "alter table ... add constraint ... foreign key"
//                                statement to be executed
function GetReferConstraint($xmlTable)
{
        if ( DBMS == "mysql" ) return array();
        if ( DBMS == "ib" ) return array();
        $attrubtes = $xmlTable->GetXmlNodeAttributes();
        $tableName = $attrubtes["NAME"];
        $array=$xmlTable->SelectNodes("table/ForeignKey");
        $sqlArray = array();
        foreach( $array as $xmlForeignKey )
        {
                $attrubtes = $xmlForeignKey->GetXmlNodeAttributes();
                $data           = $xmlForeignKey->GetXmlNodeData();
                $splitAttr = explode( ".", $attrubtes["REFERTO"] );
                $constraintID = "";

                $constraintName = GetForeignKeyName( $tableName, $xmlForeignKey );

                if ( DBMS == "mysql" )
                {
                        $constraintID = "ForeignKey".$_SESSION["ForeignKeyIndex"]."ID";
                        $_SESSION["ForeignKeyIndex"] ++;
                }
                if ( DBMS != "ib" )
                {
                        $sql = " ALTER TABLE ".$tableName." ADD CONSTRAINT ".
                                $constraintName." FOREIGN KEY ".
                                $constraintID." ".
                                "( ".
                                                $data.
                                ") REFERENCES ".$splitAttr[0]."  ".
                                "(".
                                        $splitAttr[1].
                                ")";
                        $sqlArray[] = $sql;
                }
                else
                {
                        $sql = "";
/*
                        $columns1 = explode(",", $data);
                        $columns2 = explode(",", $splitAttr[1]);
                        if ( !CompareArrays($columns1, $columns2) )
                                return "";
                        $constraintName = substr( $tableName."___".$splitAttr[0], 0, 31 );
                        $sql .= "ALTER TABLE ".$tableName." ADD CONSTRAINT ".
                                $constraintName." FOREIGN KEY ".
                                "( ".
                                        $data.
                                ") REFERENCES ".$splitAttr[0];
*/
                }
        }
        return $sqlArray;
}


// *****************************************************************************
// Purpose        gets InterBase SQL statement to generate identity generator
// Inputs   $xmlTable - table XML node
// Remarks
// Returns        SQL code to be executed
function GetIB_IdentityGenerator( $xmlTable )
{
        if ( DBMS != "ib" )
                return "";

        $attrubtes = $xmlTable->GetXmlNodeAttributes();
        $tableName = $attrubtes["NAME"];
        $array=$xmlTable->SelectNodes("table/column");

        $sql = "";
        foreach($array as $xmlColumn)
        {
                $attributes = $xmlColumn->GetXmlNodeAttributes();
                $columnName = trim($xmlColumn->GetXmlNodeData());
                if ( isset( $attributes["IDENTITY"] ) )
                        if ( $attributes["IDENTITY"] == "true" )
                        {
                                $generatorName = $tableName."_".$columnName."_GEN";
                                $generatorName = substr( $generatorName, 0, 31 );
                                $createGeneratorSQL = "CREATE GENERATOR ".$generatorName." ";
                                db_query( $createGeneratorSQL );
                                $sql .= $createGeneratorSQL;
                        }
        }
        return $sql;
}


// *****************************************************************************
// Purpose        gets InterBase SQL statement to generate identity trigger
// Inputs   $xmlTable - table XML node
// Remarks
// Returns        SQL code to be executed
function GetIB_IdentityTrigger( $xmlTable )
{
        if ( DBMS != "ib" )
                return "";

        $attrubtes = $xmlTable->GetXmlNodeAttributes();
        $tableName = $attrubtes["NAME"];
        $array=$xmlTable->SelectNodes("table/column");

        $sql = "";
        foreach($array as $xmlColumn)
        {
                $attributes = $xmlColumn->GetXmlNodeAttributes();
                $columnName = trim($xmlColumn->GetXmlNodeData());
                if ( isset( $attributes["IDENTITY"] ) )
                        if ( $attributes["IDENTITY"] == "true" )
                        {
                                $generatorName = $tableName."_".$columnName."_GEN";
                                $generatorName = substr( $generatorName, 0, 31 );
                                $triggerName = $tableName."_NEW";
                                $triggerName = substr( $triggerName, 0, 31 );
                                $createTriggerSQL =
                                        "CREATE TRIGGER ".$triggerName." FOR ".$tableName.
                                                " ACTIVE ".
                                                "BEFORE INSERT POSITION 0 AS ".
                                                "begin ".
                                                "                if (new.".$columnName." is null) ".
                                                "                then new.".$columnName." = gen_id(".$generatorName.", 1);".
                                                "end ";
                                db_query($createTriggerSQL);
                                $sql .= $createTriggerSQL;
                        }
        }
        return $sql;
}


// *****************************************************************************
// Purpose        parses table node
// Inputs   table node ( that is XmlNode object )
// Remarks
// Returns        SQL "create table" statement to be executed
function GetCreateTableSQL($xmlTable)
{
        $attributes=$xmlTable->GetXmlNodeAttributes();
        $sql = "CREATE TABLE ".trim($attributes["NAME"])." (";
        $array=$xmlTable->SelectNodes("table/column");

        if ( DBMS == "mysql" ){

                $_indexes = GetIndexesSQL($array);
                if($_indexes) $sql .= $_indexes.',';
        }

        $firstFlag=true;
        $isComplexPrimaryKey = IsComplexPrimaryKey($array);
        foreach($array as $xmlColumn)
        {
                $columnSql=GetColumnSQL($xmlColumn, $isComplexPrimaryKey);
                if ( is_bool($columnSql) )
                        return false;
                if ( $firstFlag )
                        $sql .= GetColumnSQL($xmlColumn, $isComplexPrimaryKey);
                else
                        $sql .= ", ".GetColumnSQL($xmlColumn, $isComplexPrimaryKey);
                $firstFlag = false;
        }
        if ( $isComplexPrimaryKey )
                $sql .= ", ".GetComplexPrimaryKeySQL($array);
        $sql .= ")";
        if ( DBMS == "mysql" ) {
        if(trim($attributes["TYPE"]) != "") $sql .= " ENGINE=MyISAM";
        else $sql .= " ENGINE=InnoDB";
        }
        return $sql;
}

/**
 * Return indexes sql-injection
 *
 * @param array $array - columns
 * @return string - sql-injection
 */
function GetIndexesSQL($array){

        $sql = array();
        foreach($array as $xmlColumn)
        {
                $attributes=$xmlColumn->GetXmlNodeAttributes();
                foreach($attributes as $key => $value)
                {
                        if ( $key == "INDEX" )
                        {
                                $value = strtoupper($value);
                                $columnName = trim($xmlColumn->GetXmlNodeData());
                                $sql[] = '
                                        KEY '.$value.' (`'.$columnName.'`)';
                                break;
                        }
                }
        }
        return implode(',', $sql);
}


// *****************************************************************************
// Purpose        gets primary key clause for complex key
// Inputs   $array is array of column node
// Remarks
// Returns
function GetComplexPrimaryKeySQL($array)
{
        $columns = "";
        $firstFlag = true;
        foreach($array as $xmlColumn)
        {
                $attributes=$xmlColumn->GetXmlNodeAttributes();
                foreach($attributes as $key => $value)
                {
                        if ( $key == "PRIMARYKEY" )
                        {
                                if ( $firstFlag )
                                {
                                        $columns .= $xmlColumn->GetXmlNodeData();
                                        $firstFlag = false;
                                }
                                else
                                        $columns .= ", ".$xmlColumn->GetXmlNodeData();
                                break;
                        }
                }
        }
        return "PRIMARY KEY (".$columns.")";
}


// *****************************************************************************
// Purpose        determine complex primary key fact
// Inputs   array of column node
// Remarks
// Returns        true if primary key is complex false otherwise
function IsComplexPrimaryKey($array)
{
        $primaryKeyCountPart = 0;
        foreach($array as $xmlColumn)
        {
                $attributes=$xmlColumn->GetXmlNodeAttributes();
                foreach($attributes as $key => $value)
                {
                        if ( $key == "PRIMARYKEY" )
                        {
                                $primaryKeyCountPart++;
                                break;
                        }
                }
        }
        return ( $primaryKeyCountPart > 1 );
}


// *****************************************************************************
// Purpose
// Inputs   column node ( that is XmlNode object )
// Remarks
// Returns        SQL column clause
function GetTypeColumnSQL($type)
{
        if ( strstr( $type, "VARCHAR" ) )
                return $type;
        else if ( strstr( $type, "CHAR" ) )
                return $type;
        else if ( $type == "FLOAT" )
        {
                if ( DBMS == "ib" )
                        return "double precision";
                else
                        return "FLOAT";
        }
        else if ( $type == "DOUBLE" )
        {
                if ( DBMS == "ib" )
                        return "double precision";
                else
                        return "DOUBLE";
        }
        else if ( $type == "INT" )
        {
                if ( DBMS == "ib" )
                        return "INTEGER";
                else
                        return $type;
        }
        else if ( $type == "BIT" )
        {
                if ( DBMS == "ib" )
                        return "INTEGER";
                else
                        return $type;
        }
        else if ( $type == "DATETIME" )
        {
                if ( DBMS == "ib" )
                        return "TIMESTAMP";
                else
                        return $type;
        }
        else if ( $type == "TEXT" )
        {
                if ( DBMS == "ib" )
                        return "VARCHAR(8192)";
                else
                        return $type;
        }
        else if ( $type == "DATE" )
        {
                if ( DBMS == "ib" )
                        return "TIMESTAMP";
                else
                        return $type;
        }
        else if ( $type == "TIMESTAMP" )
        {
                if ( DBMS == "ib" )
                        return "TIMESTAMP";
                else
                        return $type;
        }
        else if ( $type == "LONGTEXT" )
        {
                if ( DBMS == "ib" )
                        return "LONGTEXT";
                else
                        return $type;
        }
}



function _verifyVarChar($value)
{
        if ( strstr( $value, "VARCHAR" ) )
        {
                $val=str_replace( "VARCHAR", "", $value);
                $val=trim($val);
                if ( $val[0] == '(' && $val[ strlen($val) - 1 ] == ')' )
                {
                        $val = str_replace( "(", "", $val );
                        $val = str_replace( ")", "", $val );
                        $val = (int)$val;
                        return !( $val == 0 );
                }
                return false;
        }
}


function _verifyChar($value)
{
        if ( strstr( $value, "CHAR" ) )
        {
                $val=str_replace( "CHAR", "", $value);
                $val=trim($val);
                if ( $val[0] == '(' && $val[ strlen($val) - 1 ] == ')' )
                {
                        $val = str_replace( "(", "", $val );
                        $val = str_replace( ")", "", $val );
                        $val = (int)$val;
                        return !( $val == 0 );
                }
                return false;
        }
}


// *****************************************************************************
// Purpose        parses column node
// Inputs   column node ( that is XmlNode object )
// Remarks
// Returns        SQL column clause
function GetColumnSQL($xmlColumn, $isComplexPrimaryKey)
{
        $attributes=$xmlColumn->GetXmlNodeAttributes();
        $type                        = "";
        $nullable                = true;
        $defaultValue        = false;
        $primaryKey                = false;
        $identity                = false;
        foreach($attributes as $key => $value)
        {
                $value = strtoupper($value);
                switch( $key )
                {
                        case "TYPE" :
                                if ( _verifyVarChar($value) )
                                        $type = GetTypeColumnSQL( $value );
                                else if ( _verifyChar($value) )
                                        $type = GetTypeColumnSQL( $value );
                                else if (
                                                        $value == "BIT" || $value == "INT" ||
                                                        $value == "DATETIME" || $value == "FLOAT"  || $value == "DOUBLE"  ||
                                                        $value == "TEXT" || $value == "DATE" || $value == "TIMESTAMP" || $value == "LONGTEXT"
                                                )
                                        $type = GetTypeColumnSQL( $value );
                                else
                                {
                                        echo( "Unknown datatype ".$value );
                                        return false;
                                }
                        break;

                        case "NULLABLE" :
                                if ( $value=="TRUE" )
                                        $nullable = true;
                                else if ( $value=="FALSE" )
                                        $nullable = false;
                                else
                                {
                                        echo( "Invalid 'NULLABLE' attribute value '".$value."'" );
                                        return false;
                                }
                        break;

                        case "DEFAULT" :
                                $defaultValue = $value;
                        break;

                        case "PRIMARYKEY" :
                                $primaryKey = true;
                        break;

                        case "IDENTITY" :
                                $identity = true;
                                break;

                        case "INDEX":
                                break;

                        default :
                                echo( "Unknown attribute '".$key."'" );
                                return false;
                }
        }
        $columnName = trim($xmlColumn->GetXmlNodeData());
        if ( DBMS == "ib" )
                return GetColumnIB_SQL($columnName, $type,
                        $nullable, $primaryKey, $identity, $defaultValue, $isComplexPrimaryKey);
        else if ( DBMS == "mysql" )
                return GetColumnMYSQL($columnName, $type,
                        $nullable, $primaryKey, $identity, $defaultValue, $isComplexPrimaryKey);
        else if ( DBMS == "mssql" )
                return GetColumnMSSQL($columnName, $type,
                        $nullable, $primaryKey, $identity, $defaultValue, $isComplexPrimaryKey);
}



// *****************************************************************************
// Purpose        gets column clause for IB DBMS
// Inputs
//                        $columnName - column name        (string)
//                        $type                - data type                (string)
//                        $nullable        - true if column is nullable                (bool)
//                        $primaryKey        - true if column is primary key                (bool)
//                        $identity        - true if column is identity                (bool)
//                        $defaultValue - false if column does not have default value
//                        $isComplexPrimaryKey - true if primary key is complex (bool)
// Remarks
// Returns        SQL column clause
function GetColumnIB_SQL($columnName, $type,
                        $nullable, $primaryKey,
                        $identity, $defaultValue, $isComplexPrimaryKey)
{
        $sql = "";
        if ( $nullable )
                $nullableStr = "";
        else
                $nullableStr = "NOT NULL";
        $defaultValueClause = GetDefaultValueClause($type, $defaultValue);
        if ( $primaryKey && !$isComplexPrimaryKey )
                $sql .= $columnName." ".$type." NOT NULL PRIMARY KEY ";
        else if ( $primaryKey && $isComplexPrimaryKey )
                $sql .= $columnName." ".$type." NOT NULL ";
        else
                $sql .= $columnName." ".$type." ".$nullableStr." ".$defaultValueClause;
        return $sql;
}


// *****************************************************************************
// Purpose        gets column clause for MS SQL Server DBMS
// Inputs
//                        $columnName - column name        (string)
//                        $type                - data type                (string)
//                        $nullable        - true if column is nullable                (bool)
//                        $primaryKey        - true if column is primary key                (bool)
//                        $identity        - true if column is identity                (bool)
//                        $defaultValue - false if column does not have default value
//                        $isComplexPrimaryKey - true if primary key is complex (bool)
// Remarks
// Returns        SQL column clause
function GetColumnMSSQL($columnName, $type,
                        $nullable, $primaryKey, $identity, $defaultValue, $isComplexPrimaryKey)
{
        $sql = "";
        if ( $nullable )
                $nullableStr = "NULL";
        else
                $nullableStr = "NOT NULL";
        if ( $identity )
                $identityStr = "IDENTITY(1,1)";
        else
                $identityStr = "";
        $defaultValueClause = GetDefaultValueClause($type, $defaultValue);
        if ( $primaryKey && !$isComplexPrimaryKey )
                $sql .= $columnName." ".$type." PRIMARY KEY ".$identityStr;
        else if ( $primaryKey && $isComplexPrimaryKey )
                $sql .= $columnName." ".$type." ".$identityStr;
        else
                $sql .= $columnName." ".$type." ".$nullableStr." ".$identityStr." ".$defaultValueClause;
        return $sql;
}


// *****************************************************************************
// Purpose        gets column clause for MYSQL DBMS
// Inputs
//                        $columnName - column name        (string)
//                        $type                - data type                (string)
//                        $nullable        - true if column is nullable                (bool)
//                        $primaryKey        - true if column is primary key                (bool)
//                        $identity        - true if column is identity                (bool)
//                        $defaultValue - false if column does not have default value
//                        $isComplexPrimaryKey - true if primary key is complex (bool)
// Remarks
// Returns        SQL column clause
function GetColumnMYSQL($columnName, $type,
                        $nullable, $primaryKey, $identity, $defaultValue, $isComplexPrimaryKey)
{
        $sql = "";
        if ( $nullable )
                $nullableStr = "NULL";
        else
                $nullableStr = "NOT NULL";
        if ( $identity )
                $identityStr = "AUTO_INCREMENT";
        else
                $identityStr = "";
        $defaultValueClause = GetDefaultValueClause($type, $defaultValue);
        if ( $primaryKey && !$isComplexPrimaryKey )
                $sql .= $columnName." ".$type." PRIMARY KEY ".$identityStr;
        else if ( $primaryKey && $isComplexPrimaryKey )
                $sql .= $columnName." ".$type." NOT NULL ".$identityStr;
        else
                $sql .= $columnName." ".$type." ".$nullableStr." ".$identityStr." ".$defaultValueClause;
        return $sql;
}



// *****************************************************************************
// Purpose        gets default value clause
// Inputs
//                        $type                - data type                (string)
//                        $defaultValue - false if column does not have default value
// Remarks
// Returns
function GetDefaultValueClause($type, $defaultValue)
{
        if ( is_bool($defaultValue) )
                return "";
        if ( DBMS == "mysql" || DBMS == "ib" )
        {
                $defaultClauseOpen        = "DEFAULT ";
                $defaultClauseClose = "";
        }
        else
        {
                $defaultClauseOpen        = "DEFAULT(";
                $defaultClauseClose = ")";
        }
        if ( strstr("VARCHAR",strtoupper($type)) )
                return $defaultClauseOpen."'".$defaultValue."'".$defaultClauseClose;
        else
                return $defaultClauseOpen.$defaultValue.$defaultClauseClose;
}


function GetColumnDataType( $columnName, $tableName, $fileName )
{
        $xmlTables=new XmlNode();
        $xmlTables->LoadInnerXmlFromFile($fileName);
        $array=$xmlTables->SelectNodes("DataBaseStructure/tables/table");
        foreach($array as $xmlTable)
        {
                $attr = $xmlTable->GetXmlNodeAttributes();
                $tableName = $attr["NAME"];
                if ( trim($tableName) == trim($tableName) )
                {
                        $arrayColumn = $xmlTable->SelectNodes("table/column");
                        foreach( $arrayColumn as $xmlColumn )
                        {
                                if ( trim($xmlColumn->GetXmlNodeData()) == trim($columnName) )
                                {
                                        $attributes = $xmlColumn->GetXmlNodeAttributes();
                                        return strtoupper( $attributes["TYPE"] );
                                }
                        }
                }
        }
        return false;
}




// *****************************************************************************
// Purpose        upgrades install file
// Inputs
//                                $xmlFileName        - source xml file ( database_structure.xml  )
//                                $tableIncFile        - tables.inc.php ( it is modified by Shop Script owner )
//                                $targetFile                - target ( result ) file
// Remarks        Shop Script owner can change table name,
//                                this function change table names in XML file
// Returns
function ReWriteInstallXmlFile( $xmlFileName, $tableIncFile, $targetFile )
{
        include( $tableIncFile );
        $xmlTableNodeArray = GetXmlTableNodeArray( $xmlFileName );

        $f = fopen( $targetFile, "w" );
        fwrite( $f, "<DataBaseStructure ApplicationVersion='ShopCMS'>\n" );
        fwrite( $f, "\n" );
        fwrite( $f, "\t<tables>\n" );


         foreach( $xmlTableNodeArray as $xmlTableNode )
        {
                $attributes = $xmlTableNode->GetXmlNodeAttributes();
                $xmlTableDefinition = "";
                if ( defined($attributes["ALIAS"]) )
                        $xmlTableDefinition .= "\t\t<table name='".constant($attributes["ALIAS"]).
                                        "' alias='".$attributes["ALIAS"]."' ";
                else
                        $xmlTableDefinition .= "\t\t<table name='".$attributes["NAME"].
                                        "' alias='".$attributes["ALIAS"]."' ";

                foreach( $attributes as $key => $val )
                {
                        if ( $key != "ALIAS" && $key != "NAME" )
                                $xmlTableDefinition .= " ".$key."='".$val."' ";
                }
                $xmlTableDefinition .= "> \n";

                $xmlTableColumnNodeArray = $xmlTableNode->SelectNodes( "table/column" );
                foreach( $xmlTableColumnNodeArray as $xmlTableColumnNode )
                {
                        $xmlTableDefinition .= "\t\t\t<column ";
                        $attributes = $xmlTableColumnNode->GetXmlNodeAttributes();
                        foreach( $attributes as $key => $value )
                        {
                                $xmlTableDefinition .= $key;
                                $xmlTableDefinition .= "=";
                                $xmlTableDefinition .= "'$value' ";
                        }
                        $xmlTableDefinition .= ">";
                        $columnName = $xmlTableColumnNode->GetXmlNodeData();
                        $xmlTableDefinition .= trim($columnName);
                        $xmlTableDefinition .= "</column>\n";
                }

                $xmlForeignKeyNodeArray = $xmlTableNode->SelectNodes( "table/ForeignKey" );
                foreach( $xmlForeignKeyNodeArray as $xmlForeignKeyNode )
                {
                        $xmlTableDefinition .= "\t\t\t<ForeignKey ";
                        $attributes = $xmlForeignKeyNode->GetXmlNodeAttributes();
                        foreach( $attributes as $key => $value )
                        {
                                $xmlTableDefinition .= $key;
                                $xmlTableDefinition .= "=";
                                $xmlTableDefinition .= "'$value' ";
                        }
                        $xmlTableDefinition .= ">";
                        $foreignKeyColumnName = $xmlForeignKeyNode->GetXmlNodeData();
                        $xmlTableDefinition .= trim($foreignKeyColumnName);
                        $xmlTableDefinition .= "</ForeignKey>\n";
                }
                $xmlTableDefinition .= "\t\t</table>\n";
                fwrite( $f, $xmlTableDefinition );
                fwrite( $f, "\n" );
        }


        fwrite( $f, "\t</tables>\n" );
        fwrite( $f, "</DataBaseStructure>" );
        fclose( $f );
}

?><?php
#######################################
# ShopCMS: Скрипт интернет-магазина
# Copyright (c) by ADGroup
# http://shopcms.ru
#######################################


        class XmlNode
        {
                var $parser;
                var $fp;
                var $currentXPath;
                var $currentIndex;
                var $xPathQuery;

                var $attributes;
                var $name;
                var $data;

                var $selectResult;

                var $innerXmlBeginIndex;
                var $innerXmlEndIndex;

                var $innerXml;

                function XmlNode()
                {
                        $this->currentXPath=array();
                        $this->currentIndex=0;

                        $this->tmp = 0;
                }

                function SetXmlNodeAttributes( $attributes )
                {
                        $this->attributes        = $attributes;
                }

                function SetXmlNodeName( $name )
                {
                        $this->name                        = $name;
                }

                function SetXmlNodeData( $data )
                {
                        $this->data                        .= $data;
                        $this->tmp++;
                }

                function GetXmlNodeAttributes()
                {
                        return $this->attributes;
                }

                function GetXmlNodeName()
                {
                        return $this->name;
                }

                function GetXmlNodeData()
                {
                        return $this->data;
                }

                function SetInnerXml($innerXml)
                {
                        $this->innerXml = $innerXml;
                }

                function LoadInnerXmlFromFile($fileName)
                {
                        $fp = fopen($fileName, "r");
                        $this->innerXml = trim( fread( $fp, filesize($fileName) ) );
                        fclose($fp);
                }

                function PrintXmlNode()
                {
                        $str="";
                        foreach($this->attributes as $key => $val)
                        {
                                $str .= $key."=".$val." ";
                        }
                        echo("&lt;".$this->name." ".$str." &gt;<b>DATA</b>'".$this->data."'<br>");
                        echo("<b>Inner XML</b>");
                        echo(str_replace("<","&lt;",$this->innerXml));
                        echo("<br>");
                }

                function SelectNodes($xPathQuery)
                {
                        $this->parser = xml_parser_create();
                        xml_parser_set_option($this->parser, XML_OPTION_TARGET_ENCODING, "UTF-8");
                        xml_set_object($this->parser, $this);
                        xml_set_element_handler($this->parser, "tag_open", "tag_close");
                        xml_set_character_data_handler($this->parser, "cdata");

                        $this->currentXPath=array();
                        $this->currentIndex=0;
                        $this->xPathQuery = $xPathQuery;
                        $this->selectResult=array();
                        xml_parse( $this->parser, $this->innerXml, true );
                        return $this->selectResult;
                }

                function compareXPath()
                {
                        $xPathQueryArray=explode( "/", $this->xPathQuery );
                        if ( count($xPathQueryArray) != count($this->currentXPath) )
                                return false;
                        for($i=0; $i<count($this->currentXPath); $i++)
                        {
                                if ( strtoupper($xPathQueryArray[$i]) != strtoupper($this->currentXPath[$i]) )
                                        return false;
                        }
                        return true;
                }

                function tag_open($parser, $tag, $attributes)
                {
                        $this->currentXPath[ $this->currentIndex++ ] = $tag;
                        if ( $this->compareXPath() )
                        {
                                $this->innerXmlBeginIndex = xml_get_current_byte_index( $this->parser );
                                $newNode = new XmlNode();
                                $newNode->SetXmlNodeAttributes( $attributes );
                                $newNode->SetXmlNodeName( $tag );
                                $this->selectResult[] = $newNode;
                        }
                }

                function tag_close($parser, $tag)
                {
                        unset( $this->currentXPath[ $this->currentIndex-- ] );
                        if ( $this->compareXPath() )
                        {
                                $innerXmlEndIndex = xml_get_current_byte_index( $this->parser );

                                $newInnerXml=substr( $this->innerXml, $this->innerXmlBeginIndex, $innerXmlEndIndex -  $this->innerXmlBeginIndex + ALTERNATEPHP );

                                $lastIndex = count( $this->selectResult ) - 1;

                                $phpv = phpversion();

                                if (ALTERNATEPHP == 1)
                                        $this->selectResult[ $lastIndex ]->SetInnerXml("<".$tag.$newInnerXml.$tag.">");
                                else
                                        $this->selectResult[ $lastIndex ]->SetInnerXml($newInnerXml.$tag.">");
                        }
                }

                function cdata($parser, $cdata)
                {
                        if ( $this->compareXPath() )
                        {
                                $lastIndex = count( $this->selectResult ) - 1;
                                if ( $lastIndex != -1 )
                                {
                                        $this->selectResult[ $lastIndex ]->SetXmlNodeData( $cdata );
                                }
                        }
                }

        }

?>