<?php
#####################################
# ShopCMS: Скрипт интернет-магазина
# Copyright (c) by ADGroup
# http://shopcms.ru
#####################################

        if ( isset($_GET["activate"]) )
        {
        $smarty->assign( "activate_result", activate_order($_GET["activate"],$smarty_mail) );
        $smarty->assign( "activate_mode", 1 );
        $smarty->assign( "main_content_template", "activation_orders.tpl.html" );
        }elseif ( isset($_GET["deactivate"]) )
        {
        $smarty->assign( "activate_result", deactivate_order($_GET["deactivate"],$smarty_mail) );
        $smarty->assign( "activate_mode", 2 );
        $smarty->assign( "main_content_template", "activation_orders.tpl.html" );
        }

?><?php
#####################################
# ShopCMS: Скрипт интернет-магазина
# Copyright (c) by ADGroup
# http://shopcms.ru
#####################################


    if ( isset($address_book) && isset($_SESSION["log"]) )
        {
                if ( isset($_GET["delete"]) )
                {
                        $aID = (int)$_GET["delete"];
                        if ( regGetAddressByLogin( $aID, $_SESSION["log"] ) ) // delete address only if belongs to customer
                        {
                                redDeleteAddress( $aID );
                        }
                }

                if ( isset($_POST["save"]) )
                {
                        $aID = (int)$_POST["DefaultAddress"];
                        if ( regGetAddressByLogin( $aID, $_SESSION["log"] ) ) // update default address only if belongs to customer
                        {
                                regSetDefaultAddressIDByLogin( $_SESSION["log"], $aID );
                        }
                }

                $addresses = regGetAllAddressesByLogin( $_SESSION["log"] );
                for( $i=0; $i<count($addresses); $i++ )
                        $addresses[$i]["addressStr"] = regGetAddressStr( $addresses[$i]["addressID"] );

                $defaultAddressID = regGetDefaultAddressIDByLogin( $_SESSION["log"] );

                $smarty->assign("defaultAddressID", $defaultAddressID );
                $smarty->assign("addresses", $addresses );
                $smarty->assign("main_content_template", "address_book.tpl.html");
        }

?><?php
#####################################
# ShopCMS: Скрипт интернет-магазина
# Copyright (c) by ADGroup
# http://shopcms.ru
#####################################


    if ( (isset($address_editor) || isset($add_new_address)) && isset($_SESSION["log"]) )
        {
                $errorCode = "";
                $countryID = CONF_DEFAULT_COUNTRY;

                // *****************************************************************************
                // Purpose        copies data from $_POST variable to HTML page
                // Inputs                     $smarty - smarty object
                // Remarks
                // Returns        nothing
                function _copyDataFromPostToPage( &$smarty )
                {
                        $smarty->hassign("first_name", $_POST["first_name"]);
                        $smarty->hassign("last_name",  $_POST["last_name"]);
                        $smarty->hassign("countryID",  (int)$_POST["countryID"]);
                        $smarty->hassign("zoneID", (int)$_POST["zoneID"] );
                        $smarty->hassign("state", $_POST["state"]);
                        $smarty->hassign("city",  $_POST["city"]);
                        $smarty->hassign("address",  $_POST["address"]);
                        $zones = znGetZonesById( (int)$_POST["countryID"] );
                        $smarty->hassign( "zones", $zones );
                }

                // *****************************************************************************
                // Purpose        copies data from DataBase variable to HTML page
                // Inputs                     $smarty - smarty object
                //                                        $log - customer login
                // Remarks
                // Returns        nothing
                function _copyDataFromDataBaseToPage( &$smarty, $addressID )
                {
                        if ( !isset($_SESSION["log"]) ) Redirect("index.php?page_not_found=yes");
                        $address = regGetAddressByLogin( $addressID, $_SESSION["log"] );
                        if ( $address === false ) Redirect("index.php?page_not_found=yes");
                        else
                        {
                                $smarty->assign("first_name", $address["first_name"] );
                                $smarty->assign("last_name", $address["last_name"] );
                                $smarty->assign("countryID", (int)$address["countryID"] );
                                $smarty->assign("zoneID", (int)$address["zoneID"] );
                                $smarty->assign("state", $address["state"] );
                                $smarty->assign("city", $address["city"] );
                                $smarty->assign("address", $address["address"] );
                                $zones = znGetZonesById( (int)$address["countryID"] );
                                $smarty->assign("zones", $zones );
                        }
                }


                if ( !isset($_POST["zoneID"])  ) $_POST["zoneID"]=0;
                if ( !isset($_POST["state"])  )  $_POST["state"]="";

                if ( isset($add_new_address) ) $smarty->assign("add_new_address", 1 );
                if ( isset($address_editor) )  $smarty->assign("address_editor", $address_editor );

                if ( isset($_POST["first_name"]) ) _copyDataFromPostToPage( $smarty );
                else if ( isset($address_editor) )
                        {
                                $address_editor = (int) $address_editor;
                                _copyDataFromDataBaseToPage( $smarty, $address_editor );
                        }
                        else
                        {
                                $zones = znGetZonesById( (int)$countryID );
                                $smarty->assign("zones", $zones );
                        }

                if ( isset($_POST["save"]) )
                {
                        $first_name = $_POST["first_name"];
                        $last_name  = $_POST["last_name"];
                        $countryID  = (int)$_POST["countryID"];
                        $zoneID     = (int)$_POST["zoneID"];
                        $state      = $_POST["state"];
                        $city       = $_POST["city"];
                        $address    = $_POST["address"];

                        $error = regVerifyAddress( $first_name, $last_name, $countryID, $zoneID, $state, $city, $address );
                        if ( $error == "" ) unset( $error );
                        else $smarty->assign("error", $error );

                        if ( !isset($error) )
                        {
                                //regTransformAddressToSafeForm(
                                //                        &$first_name, &$last_name,
                                //                        &$countryID, &$zoneID, &$state,
                                //                        &$city, &$address );

                                if ( isset($add_new_address) )
                                {
                                        regAddAddress(
                                                $first_name, $last_name, $countryID,
                                                $zoneID, $state, $city,
                                                $address, $_SESSION["log"], $errorCode );
                                        Redirect("index.php?address_book=yes");
                                }
                                else if ( isset($address_editor) )
                                {
                                        regUpdateAddress( $address_editor,
                                                $first_name, $last_name, $countryID,
                                                $zoneID, $state, $city,
                                                $address, $errorCode );
                                        Redirect("index.php?address_book=yes");
                                }
                        }
                }
                else //show 'select zone' statement
                {
                        if (isset($_POST["first_name"])) $smarty->assign("select_zone_statement", ERROR_ZONE_DOES_NOT_CONTAIN_TO_COUNTRY);
                }

                $callBackParam = null;
                $count_row = 0;
                $smarty->assign("countries", cnGetCountries( $callBackParam, $count_row ) );
                $smarty->assign("main_content_template", "address_editor.tpl.html");
        }
?><?php
#####################################
# ShopCMS: Скрипт интернет-магазина
# Copyright (c) by ADGroup
# http://shopcms.ru
#####################################


        $extraParametrsTemplate = null;
        $searchParamName = null;
        $rangePrice = null;

        if ( !isset($_GET["categoryID"]) && isset($_GET["search_with_change_category_ability"]) )
        {

                $smarty->assign( "categories_to_select", $cats );
        }

        if ( isset($_GET["categoryID"]) )
        {
                $_GET["categoryID"] = (int)$_GET["categoryID"];

                if  (  !catGetCategoryById($_GET["categoryID"])  ){
                        header("HTTP/1.0 404 Not Found");
                        header("HTTP/1.1 404 Not Found");
                        header("Status: 404 Not Found");
                        die(ERROR_404_HTML);
                }
                else
                {
                        if ( isset($_GET["search_with_change_category_ability"]) )
                        {
                               $smarty->assign( "categories_to_select", $cats);
                        }

                        $getData = null;
                        if ( isset($_GET["advanced_search_in_category"]) )
                        {
                                $extraParametrsTemplate = array();
                                $extraParametrsTemplate["categoryID"] = $_GET["categoryID"];

                                if ( isset($_GET["search_name"]) )
                                        if ( trim($_GET["search_name"]) != "" )
                                                $searchParamName = array($_GET["search_name"]);

                                $rangePrice = array( "from" => $_GET["search_price_from"],
                                                          "to"   => $_GET["search_price_to"]);

                                $getData = ScanGetVariableWithId( array("param") );
                                foreach( $getData as $optionID => $value )
                                {
                                        $res = schOptionIsSetToSearch( $_GET["categoryID"], $optionID );

                                        if ( $res["set_arbitrarily"]==0 && (int)$value["param"] == 0 )
                                                continue;

                                        $item = array();
                                        $item["optionID"] = $optionID;
                                        $item["value"]    = $value["param"];
                                        $item["set_arbitrarily"] = $res["set_arbitrarily"];
                                        $extraParametrsTemplate[] = $item;
                                }
                        }


                        $params = array();

                        $categoryID = $_GET["categoryID"];
                        $options = optGetOptionscat($categoryID);
                        $OptionsForSearch = schOptionsAreSetToSearch($categoryID, $options);

                        foreach( $options as $option ){

                                if ( isset($OptionsForSearch[$option["optionID"]])){

                                        $set_arbitrarily = $OptionsForSearch[$option["optionID"]]['set_arbitrarily'];
                                        $item = array();
                                        $item["optionID"]        = $option["optionID"];
                                        $item["value"] = $getData[ (string)$option["optionID"] ]["param"];

                                        $item["controlIsTextField"] = $set_arbitrarily;
                                        $item["name"]                                = $option["name"];
                                        if ( $set_arbitrarily == 0 )
                                        {
                                                $item["variants"]                        = array();
                                                $variants = schGetVariantsForSearch( $categoryID, $option["optionID"]);
                                                foreach( $variants as $variant ){

                                                        $item["variants"][] = array(
                                                                'variantID' => $variant["variantID"],
                                                                'value' => $variant["option_value"]
                                                                );
                                                }
                                        }
                                        $params[] = $item;
                                }
                        }


                        if ( isset($_GET["search_name"]) ) $smarty->assign( "search_name", $_GET["search_name"]);
                        if ( isset($_GET["search_price_from"]) ) $smarty->assign( "search_price_from", $_GET["search_price_from"]);
                        if ( isset($_GET["search_price_to"]) ) $smarty->assign( "search_price_to", $_GET["search_price_to"]);

                        $smarty->assign( "categoryID", $categoryID );

                        if ( isset($_GET["advanced_search_in_category"]) )
                                $smarty->assign( "search_in_subcategory", isset($_GET["search_in_subcategory"]) );
                        else
                                $smarty->assign( "search_in_subcategory", true );
                        $smarty->assign( "show_subcategory_checkbox", 1 );
                        $smarty->assign( "priceUnit", getPriceUnit() );
                        $smarty->assign( "params", $params );
                }
        }
?><?php
#if (isset($_GET['extrafilter'])

function var_cmp($a,$b)
    {
    $a = (float)$a['value'];
    $b = (float)$b['value'];
    if ($a == $b) return 0;
    elseif ($a > $b) return 1;
    else return -1;
    }

function recursiveCat($catID,$arrayID=array())
    {
    global $fc;
    foreach ($fc as $val) if ($val['parent'] == $catID) $arrayID = recursiveCat($val['categoryID'],$arrayID);
    $arrayID[]=$catID;
    return $arrayID; 
    }
    
$get_categoryID = isset($_GET["categoryID"]) ? (int)$_GET["categoryID"] : 1;

if (isset($_GET['extrafilter']))
    {
    $data = db_query('SELECT optionID, filter_type FROM '.PRODUCT_OPTIONS_TABLE);
    while ($row = db_fetch_assoc($data)) $show_string[$row['optionID']] = $row['filter_type'];

    $efTemplate = array();
    foreach( $getData as $optionID => $value )
        {
        if ($show_string[(int)$optionID]==0 && $value["param"] == '0'
         || $show_string[(int)$optionID]==1 && empty($value["param"]) ) continue;
        $item = array();
        $item["optionID"] = $optionID;
        $item["value"]    = $value["param"];
        $item["filter_type"]    = $show_string[(int)$optionID];
        $efTemplate[] = $item;
        }
    }

$params = array();
if ($get_categoryID == 1) $addon = 'p.categoryID>1';
else
    {
    $catIDs = implode(",",recursiveCat($get_categoryID));
    $addon = "(p.categoryID IN ($catIDs) OR cp.categoryID IN ($catIDs))";
    }
$addon .= (CONF_CHECKSTOCK == 1 && CONF_SHOW_NULL_STOCK == 1)?' AND p.in_stock > 0':'';

                        $data = db_query(
                            'SELECT DISTINCT po.optionID, po.name, po.filter1, po.filter2, po.filter3, po.filter_type, pos.variantID, povv.option_value
                            FROM '.PRODUCT_OPTIONS_TABLE.' po 
                            LEFT JOIN '.PRODUCTS_OPTIONS_SET_TABLE.' pos USING (optionID) 
                            LEFT JOIN '.PRODUCTS_OPTIONS_VALUES_VARIANTS_TABLE.' povv USING (variantID) 
                            LEFT JOIN '.PRODUCTS_TABLE.' p USING (productID)
                            LEFT JOIN '.CATEGORIY_PRODUCT_TABLE.' cp USING (productID)
                            WHERE p.enabled=1 AND '.$addon.
                            ' ORDER by po.sort_order, po.name, povv.sort_order, povv.option_value');

                        $data1 = db_query(
                            'SELECT COUNT(*) AS count, variantID 
                            FROM '.PRODUCTS_OPTIONS_SET_TABLE.' pos
                            LEFT JOIN '.PRODUCTS_TABLE.' p USING (productID)
                            LEFT JOIN '.CATEGORIY_PRODUCT_TABLE.' cp USING (productID)
                            WHERE p.enabled=1 AND '.$addon.
                            ' GROUP BY variantID');
                        while($row=db_fetch_assoc($data1)) $count[$row['variantID']] = $row['count'];

                        $oID = 0;
                        $p_counter = -1;
                            while($row=db_fetch_assoc($data)) 
                                {
                            if ($oID <> $row['optionID']) 
                                {
                                    $oID = $row['optionID'];
                                    $p_counter += 1;
                                    $params[] = array('optionID' => $row['optionID'], 
                                                      'name' => $row['name'], 
                                                      'filter1' => $row['filter1'],
                                                      'filter2' => $row['filter2'],
                                                      'filter3' => $row['filter3'],
                                                      'controlIsTextField' => $row['filter_type'],
                                                      'set' => $row['filter_type']?$getData[$oID]['param']:"checked");
                                    }
                                $set = "";
                                if (isset($getData)) foreach ($getData as $key => $item)
                                    if ($key == $row['optionID'] && in_array($row['variantID'],$item['param']))
                                        {
                                        $set = "checked";
                                        $params[$p_counter]['set'] = "";
                                        }
                            $params[$p_counter]['variants'][] = array('variantID' => $row['variantID'],
                                                                      'value' => $row['option_value'], 
                                                                      'count' => $count[$row['variantID']],
                                                                      'set' => $set);
                            }

                        foreach ($params as $key => $param)
                            {
                            if ($param['controlIsTextField'] == 2)
                                {
                                usort($params[$key]['variants'],"var_cmp");
                                $params[$key]['min'] = $params[$key]['min_cursor'] = (int)$params[$key]['variants'][0]['value'];
                                $last = end($params[$key]['variants']);
                                $params[$key]['max'] = $params[$key]['max_cursor'] = ceil((float)$last['value']);
                                if (isset($_GET['param_'.$param['optionID']]))
                                    {
                                    $params[$key]['min_cursor'] = $_GET['param_'.$param['optionID']][0];
                                    $params[$key]['max_cursor'] = $_GET['param_'.$param['optionID']][1];
                                    }
                                }
                            }

$data = db_query('SELECT MIN(p.price) as min, MAX(p.price) as max FROM '.PRODUCTS_TABLE.' AS p LEFT JOIN '.CATEGORIY_PRODUCT_TABLE.' cp USING (productID) WHERE p.enabled=1 AND '.$addon);

if ($row = db_fetch_assoc($data))
    {
    $max_price = $row['max'];
    $min_price = $row['min'];
    }
else
    {
    $min_price = 0;
    $max_price = 0;
    }
$price_from = floor($min_price*$selected_currency_details["currency_value"]);
$price_to = ceil($max_price*$selected_currency_details["currency_value"]);

$smarty->assign( "efsearch_price_from", isset($_GET["search_price_from"])?(int)$_GET["search_price_from"]:$price_from);
$smarty->assign( "efsearch_price_to", isset($_GET["search_price_to"])?(int)$_GET["search_price_to"]:$price_to);
$smarty->assign( "efsearch_price_min", $price_from);
$smarty->assign( "efsearch_price_max", $price_to);
$smarty->assign( "efsearch_name", isset($_GET["search_name"])?$_GET["search_name"]:'');
$smarty->assign( "efcategoryID", $get_categoryID );
$smarty->assign( "efpriceUnit", getPriceUnit() );
$smarty->assign( "efparams", $params );
?><?php
#####################################
# ShopCMS: Скрипт интернет-магазина
# Copyright (c) by ADGroup
# http://shopcms.ru
#####################################

#handler for new customers by links
if(isset($_GET['refid'])){

        $_RefererLogin = regGetLoginById(intval($_GET['refid']));
        if($_RefererLogin){

                session_register('s_RefererLogin');
                $_SESSION['s_RefererLogin']         = $_RefererLogin;
                $_SESSION['refid']                         = intval($_GET['refid']);
                Redirect(set_query(''));
        }
}

if (  isset($_SESSION["log"]) && (isset($_GET["affiliate"]) || isset($_POST["affiliate"])) && CONF_AFFILIATE_PROGRAM_ENABLED ){

        $SubPage = isset($_GET['sub'])?$_GET['sub']:'balance';
        $fACTION = isset($_POST['fACTION'])?$_POST['fACTION']:'';

        $customerID                                 = regGetIdByLogin( $_SESSION["log"] );
        $affp_CustomersNum                         = affp_getCustomersNum($customerID);

        #post-requests handler
        switch ($fACTION){
                case 'SAVE_SETTINGS':
                        affp_saveSettings($customerID,
                                isset($_POST['EmailOrders']),
                                isset($_POST['EmailPayments']));
                        Redirect(set_query('save_settings=ok'));
                        break;
        }

        #loading data for subpages
        switch ($SubPage){
                case 'balance':
                        $Commissions         = affp_getCommissionsAmount($customerID);
                        $Payments                 = affp_getPaymentsAmount($customerID);
                        $smarty->assign('CommissionsNumber', count($Commissions));
                        $smarty->assign('PaymentsNumber', count($Payments));
                        $smarty->assign('CommissionsAmount', $Commissions);
                        $smarty->assign('PaymentsAmount', $Payments);
                        $smarty->assign('CurrencyISO3', currGetAllCurrencies());
                        break;
                case 'payments_history':
                        $Payments                 = affp_getPayments($customerID);
                        $smarty->assign('PaymentsNumber', count($Payments));
                        $smarty->assign('Payments', html_spchars(affp_getPayments($customerID, '', '', '', 'pID ASC')));
                        break;
                case 'settings':
                        $smarty->assign('SettingsSaved', isset($_GET['save_settings']));
                        $smarty->assign('Settings', affp_getSettings($customerID));
                        break;
                case 'attract_guide':
                        $smarty->assign('_AFFP_STRING_ATTRACT_GUIDE', str_replace(
                                array('{URL}', '{aff_percent}', '{login}'),
                                array('http://'.$_SERVER['HTTP_HOST'].set_query('').'?refid='.$customerID,
                                        CONF_AFFILIATE_AMOUNT_PERCENT, $_SESSION["log"]), AFFP_STRING_ATTRACT_GUIDE));
                        break;

        }

        $smarty->assign('affiliate_customers', $affp_CustomersNum);
        $smarty->assign('SubPage', $SubPage);
        $smarty->assign("main_content_template", "affiliate_program.tpl.html");
}
?>
<?php
#####################################
# ShopCMS: Скрипт интернет-магазина
# Copyright (c) by ADGroup
# http://shopcms.ru
#####################################

        //forgot password page

        if (isset($_GET["logout"])) //user logout
        {
                unset($_SESSION["log"]);
                unset($_SESSION["pass"]);
                session_unregister("log"); //calling session_unregister() is required since unset() may not work on some systems
                session_unregister("pass");
                RedirectJavaScript( "index.php" );
        }
        elseif (isset($_POST["enter"]) && !isset($_SESSION["log"])) //user login
        {

                if ( regAuthenticate($_POST["user_login"],$_POST["user_pw"]) )
                {
                        if (!isset($_POST["order"]))
                        {
                                if (in_array(100,$relaccess)) Redirect( ADMIN_FILE );
                                else Redirect( "index.php?user_details=yes" );                                
                        }
                } else $wrongLoginOrPw = 1;
        }


        if (isset($_POST["forgotpw"])) //forgot password?
        {
                $smarty->hassign("forgotpw", $_POST["forgotpw"]);
                $res = regSendPasswordToUser( $_POST["forgotpw"], $smarty_mail );
                if ( $res )
                        $smarty->assign("login_was_found", 1);
                else
                        $smarty->assign("login_wasnt_found", 1);
                $show_password_form = 1;
        }
        //wrong password page
        if (isset($_GET["logging"]) || isset($show_password_form) || isset($wrongLoginOrPw))
        {
                if (isset($wrongLoginOrPw)) $smarty->assign("wrongLoginOrPw", 1);
                $smarty->assign("main_content_template", "password.tpl.html");
        }

?><?php
#####################################
# ShopCMS: Скрипт интернет-магазина
# Copyright (c) by ADGroup
# http://shopcms.ru
#####################################

$leftb = array( );
$topb = array( );
$bottomb = array( );
$rightb = array( );
$result = db_query("select bid, title, content, bposition, which, sort, html, url, admin, pages, dpages, categories, products FROM ".BLOCKS_TABLE." WHERE active=1 ORDER BY sort ASC");
while ( $row = db_fetch_row($result)) {
    $row["pages"] = ( $row["pages"] != "" ) ? unserialize($row["pages"]) : array( );
    $row["dpages"] = ( $row["dpages"] != "" ) ? unserialize($row["dpages"]) : array( );
    $row["categories"] = ( $row["categories"] != "" ) ? unserialize($row["categories"]) : array( );
    $row["products"] = ( $row["products"] != "" ) ? unserialize($row["products"]) : array( );
    $row["state"] = true;
    if ( $row["bposition"] == 1 ) {
        if ( $row["html"] == 1 ) {
            if ( file_exists("core/tpl/user/".TPL."/blocks/".$row["url"]))
                $leftb[] = $row;
        }
        else {
            $leftb[] = $row;
        }
    }
    if ( $row["bposition"] == 2 ) {
        if ( $row["html"] == 1 ) {
            if ( file_exists("core/tpl/user/".TPL."/blocks/".$row["url"]))
                $topb[] = $row;
        }
        else {
            $topb[] = $row;
        }
    }
    if ( $row["bposition"] == 3 ) {
        if ( $row["html"] == 1 ) {
            if ( file_exists("core/tpl/user/".TPL."/blocks/".$row["url"]))
                $bottomb[] = $row;
        }
        else {
            $bottomb[] = $row;
        }
    }
    if ( $row["bposition"] == 4 ) {
        if ( $row["html"] == 1 ) {
            if ( file_exists("core/tpl/user/".TPL."/blocks/".$row["url"]))
                $rightb[] = $row;
        }
        else {
            $rightb[] = $row;
        }
    }
}
?><?php
#####################################
# ShopCMS: Скрипт интернет-магазина
# Copyright (c) by ADGroup
# http://shopcms.ru
#####################################


        if ( isset($categoryID) && !isset($_GET["search_with_change_category_ability"]) && !isset($productID))
        {

                if ( isset($_GET["prdID"]) )
                        $_GET["prdID"] = (int)$_GET["prdID"];
                if ( isset($_GET["search_price_from"]) )
                        if ( trim($_GET["search_price_from"]) != "" )
                                $_GET["search_price_from"] = (int)$_GET["search_price_from"];
                if ( isset($_GET["search_price_to"]) )
                        if (  trim($_GET["search_price_to"])!="" )
                                $_GET["search_price_to"] = (int)$_GET["search_price_to"];
                if ( isset($_GET["categoryID"]) )
                        $_GET["categoryID"] = (int)$_GET["categoryID"];
                if ( isset($_GET["offset"]) )
                        $_GET["offset"] = (int)$_GET["offset"];

                if  (  !catGetCategoryById($_GET["categoryID"])  )  {
                header("HTTP/1.0 404 Not Found");
                header("HTTP/1.1 404 Not Found");
                header("Status: 404 Not Found");
                die(ERROR_404_HTML);
                }

                function _getUrlToNavigate( $categoryID )
                {
                        $url = "index.php?categoryID=".$categoryID;
                        $data = ScanGetVariableWithId( array("param") );
                        if ( isset($_GET["search_name"]) )
                                $url .= "&search_name=".$_GET["search_name"];
                            # BEGIN ExtraFilter
if ( isset($_GET["extrafilter"]) )
        $url .= "&extrafilter=".$_GET["extrafilter"];
# END ExtraFilter
                        if ( isset($_GET["search_price_from"]) )
                                $url .= "&search_price_from=".$_GET["search_price_from"];
                        if ( isset($_GET["search_price_to"]) )
                                $url .= "&search_price_to=".$_GET["search_price_to"];
                        foreach( $data as $key => $val )
                        {
                                # BEGIN ExtraFilter
#$url .= "&param_".$key;
#$url .= "=".$val["param"];
if (is_array($val["param"])) foreach ($val["param"] as $vkey => $variant) $url .= "&param_".$key."[".$vkey."]=".$variant;
else $url .= "&param_".$key."=".$val["param"];
# END ExtraFilter
                        }
                        if ( isset($_GET["search_in_subcategory"]) )
                                $url .= "&search_in_subcategory=1";
                        if ( isset($_GET["sort"]) )
                                $url .= "&sort=".$_GET["sort"];
                        if ( isset($_GET["direction"]) )
                                $url .= "&direction=".$_GET["direction"];
                        if ( isset($_GET["advanced_search_in_category"]) )
                                $url .= "&advanced_search_in_category=".$_GET["advanced_search_in_category"];
                        if (CONF_MOD_REWRITE && $url == "index.php?categoryID=".$categoryID)
                                $url = "category_".$categoryID;
                        return $url;
                }

                function _getUrlToSort( $categoryID )
                {
                        $url = "index.php?categoryID=$categoryID";
                        $data = ScanGetVariableWithId( array("param") );
                        # BEGIN ExtraFilter
if ( isset($_GET["extrafilter"]) )
        $url .= "&extrafilter=".$_GET["extrafilter"];
# END ExtraFilter
                        if ( isset($_GET["search_name"]) )
                                $url .= "&search_name=".$_GET["search_name"];
                        if ( isset($_GET["search_price_from"]) )
                                $url .= "&search_price_from=".$_GET["search_price_from"];
                        if ( isset($_GET["search_price_to"]) )
                                $url .= "&search_price_to=".$_GET["search_price_to"];
                        foreach( $data as $key => $val )
                        {
                               # BEGIN ExtraFilter
#$url .= "&param_".$key;
#$url .= "=".$val["param"];
if (is_array($val["param"])) foreach ($val["param"] as $vkey => $variant) $url .= "&param_".$key."[".$vkey."]=".$variant;
else $url .= "&param_".$key."=".$val["param"];
# END ExtraFilter
                        }
                        if ( isset($_GET["offset"]) )
                                $url .= "&offset=".$_GET["offset"];
                        if ( isset($_GET["show_all"]) )
                                $url .= "&show_all=yes";
                        if ( isset($_GET["search_in_subcategory"]) )
                                $url .= "&search_in_subcategory=1";
                        if ( isset($_GET["advanced_search_in_category"]) )
                                $url .= "&advanced_search_in_category=".$_GET["advanced_search_in_category"];
                        return $url;
                }

                function _sortSetting( &$smarty, $urlToSort )
                {
                        if(CONF_USE_RATING == 1){
                        $sort_string = STRING_PRODUCT_SORTN;
                        }else{
                        $sort_string = STRING_PRODUCT_SORT;
                        }
                        $sort_string = str_replace( "{ASC_NAME}",   "<a href='".$urlToSort."&sort=name&direction=ASC'>".STRING_ASC."</a>",        $sort_string );
                        $sort_string = str_replace( "{DESC_NAME}",  "<a href='".$urlToSort."&sort=name&direction=DESC'>".STRING_DESC."</a>",        $sort_string );
                        $sort_string = str_replace( "{ASC_PRICE}",   "<a href='".$urlToSort."&sort=Price&direction=ASC'>".STRING_ASC."</a>",        $sort_string );
                        $sort_string = str_replace( "{DESC_PRICE}",  "<a href='".$urlToSort."&sort=Price&direction=DESC'>".STRING_DESC."</a>",        $sort_string );
                        $sort_string = str_replace( "{ASC_RATING}",   "<a href='".$urlToSort."&sort=customers_rating&direction=ASC'>".STRING_ASC."</a>",        $sort_string );
                        $sort_string = str_replace( "{DESC_RATING}",  "<a href='".$urlToSort."&sort=customers_rating&direction=DESC'>".STRING_DESC."</a>",        $sort_string );
                        $smarty->assign( "string_product_sort", html_amp($sort_string));

                }

                //get selected category info
                $category = catGetCategoryById( $categoryID );
                if ( !$category )
                {
                                header("HTTP/1.0 404 Not Found");
                                header("HTTP/1.1 404 Not Found");
                                header("Status: 404 Not Found");
                                die(ERROR_404_HTML);
                }
                else
                {
                       if(!$adminislog) IncrementCategoryViewedTimes($categoryID);

                        if ( isset($_GET["prdID"]) )
                        {
                                if (  isset($_POST["cart_".$_GET["prdID"]."_x"])  )
                                {
                                        $variants=array();
                                        foreach( $_POST as $key => $val )
                                        {
                                                if ( strstr($key, "option_select_hidden") )
                                                {
                                                        $arr=explode( "_", str_replace("option_select_hidden_","",$key) );
                                                        if ( (string)$arr[1] == (string)$_GET["prdID"] )
                                                                $variants[]=$val;
                                                }
                                        }
                                        unset($_SESSION["variants"]);
                                        $_SESSION["variants"]=$variants;
                                        Redirect( "index.php?shopping_cart=yes&add2cart=".$_GET["prdID"]."&multyaddcount=".(int)$_POST['multyaddcount'] );
                                }
                        }

                        //category thumbnail
                        if (!file_exists("data/category/".$category["picture"])) $category["picture"] = "";
                        $smarty->assign("selected_category", $category );


                        if ( $category["show_subcategories_products"] == 1 )
                                $smarty->assign( "show_subcategories_products", 1 );

                        if ( $category["allow_products_search"] )
                                $smarty->assign( "allow_products_search", 1 );

                        $callBackParam               = array();
                        $products                    = array();
                        $callBackParam["categoryID"] = (int)$categoryID;
                        $callBackParam["enabled"]    = 1;

                        if (  isset($_GET["search_in_subcategory"]) )
                                if ( $_GET["search_in_subcategory"] == 1 )
                                {
                                        $callBackParam["searchInSubcategories"] = true;
                                        $callBackParam["searchInEnabledSubcategories"] = true;
                                }

                        if ( isset($_GET["sort"]) )
                                $callBackParam["sort"] = $_GET["sort"];
                        if ( isset($_GET["direction"]) )
                                $callBackParam["direction"] = $_GET["direction"];

                        // search parametrs to advanced search
                        if ( $extraParametrsTemplate != null )
                                        $callBackParam["extraParametrsTemplate"] = $extraParametrsTemplate;
                        if ( $searchParamName != null )
                                        $callBackParam["name"] = $searchParamName;
                        if ( $rangePrice != null )
                                        $callBackParam["price"] = $rangePrice;

                        if ( $category["show_subcategories_products"] ) $callBackParam["searchInSubcategories"] = true;

                        $count = 0;
                        if (CONF_MOD_REWRITE){
                        $urlfarse = _getUrlToNavigate( $categoryID );
                        if($urlfarse == "category_".$categoryID) $urlflag = 1; else $urlflag = 0;
                        $navigatorHtml = GetNavigatorHtmlmd(
                                                $urlfarse, CONF_PRODUCTS_PER_PAGE,
                                                'prdSearchProductByTemplate', $callBackParam,
                                                $products, $offset, $count, $urlflag );
												$navigatorHtml = strtr($navigatorHtml,array("_offset_0"=>""));
                        }else{
                        $navigatorHtml = GetNavigatorHtml(
                                                _getUrlToNavigate( $categoryID ), CONF_PRODUCTS_PER_PAGE,
                                                'prdSearchProductByTemplate', $callBackParam,
                                                $products, $offset, $count );
												$navigatorHtml = strtr($navigatorHtml,array("&offset=0"=>"","&amp;offset=0"=>""));
                        }
                        $show_comparison = $category["allow_products_comparison"];
                        $cc_products = count($products);
                        for($i=0; $i<$cc_products; $i++) $products[$i]["allow_products_comparison"] = $show_comparison;


                        if (CONF_PRODUCT_SORT) _sortSetting( $smarty, _getUrlToSort($categoryID) );

                        if(CONF_SHOW_PARENCAT){
                        $smarty->assign( "catrescur", getcontentcatresc($categoryID));
                        }
                        $smarty->assign( "subcategories_to_be_shown", catGetSubCategoriesSingleLayer($categoryID) );
                        $smarty->assign( "categorylinkscat", getcontentcat($categoryID));
                        //calculate a path to the category
                        $smarty->assign( "product_category_path",
                                                catCalculatePathToCategory($categoryID) );
                        $smarty->assign( "show_comparison", $show_comparison );
                        $smarty->assign( "catalog_navigator", $navigatorHtml );
                        $smarty->assign( "products_to_show", $products);
                        $smarty->assign( "products_to_show_counter", count($products));
                        if(isset($_GET["advanced_search_in_category"])){
                        $smarty->assign( "products_to_showc", count($products));
                        }else{
                        if ( $category["show_subcategories_products"] )
                        $smarty->assign( "products_to_showc", $category["products_count"]);
                        else $smarty->assign( "products_to_showc", catGetCategoryProductCount( $categoryID, true));
                        }
                        $smarty->assign( "categoryID", $categoryID);
                        $smarty->assign( "categoryName", $category["name"]);
                        $smarty->assign( "main_content_template", "category.tpl.html");
                }
        }
?><?php
#####################################
# ShopCMS: Скрипт интернет-магазина
# Copyright (c) by ADGroup
# http://shopcms.ru
#####################################


        if ( isset($_GET["search_with_change_category_ability"]) )
        {
                $smarty->assign( "allow_products_search", 1 );
                $smarty->assign( "main_content_template", "category_search_result.tpl.html" );
        }


        if ( isset($categoryID) && isset($_GET["search_with_change_category_ability"]) &&
                        isset($_GET["advanced_search_in_category"]) )
        {


                function _getUrlToNavigate( $categoryID )
                {
                        $url = "index.php?categoryID=$categoryID";
                        if ( isset($_GET["search_name"]) )
                                $url .= "&search_name=".$_GET["search_name"];
                        if ( isset($_GET["search_price_from"]) )
                                $url .= "&search_price_from=".$_GET["search_price_from"];
                        if ( isset($_GET["search_price_to"]) )
                                $url .= "&search_price_to=".$_GET["search_price_to"];
                        $data = ScanGetVariableWithId( array("param") );
                        foreach( $data as $key => $val )
                        {
                                $url .= "&param_".$key;
                                $url .= "=".$val["param"];
                        }
                        if ( isset($_GET["sort"]) )
                                $url .= "&sort=".$_GET["sort"];
                        if ( isset($_GET["direction"]) )
                                $url .= "&direction=".$_GET["direction"];
                        if ( isset($_GET["search_in_subcategory"]) )
                                $url .= "&search_in_subcategory=1";
                        if ( isset($_GET["search_with_change_category_ability"]) )
                                $url .= "&search_with_change_category_ability=".$_GET["search_with_change_category_ability"];
                        if ( isset($_GET["advanced_search_in_category"]) )
                                $url .= "&advanced_search_in_category=".$_GET["advanced_search_in_category"];
                        if ( isset($_GET["categorySelect"]) )
                                $url .= "&categorySelect=".$_GET["categorySelect"];
                        return $url;
                }

                function _getUrlToSort( $categoryID )
                {
                        $url = "index.php?categoryID=$categoryID";
                        if ( isset($_GET["search_name"]) )
                                $url .= "&search_name=".$_GET["search_name"];
                        if ( isset($_GET["search_price_from"]) )
                                $url .= "&search_price_from=".$_GET["search_price_from"];
                        if ( isset($_GET["search_price_to"]) )
                                $url .= "&search_price_to=".$_GET["search_price_to"];
                        $data = ScanGetVariableWithId( array("param") );
                        foreach( $data as $key => $val )
                        {
                                $url .= "&param_".$key;
                                $url .= "=".$val["param"];
                        }
                        if ( isset($_GET["offset"]) )
                                $url .= "&offset=".$_GET["offset"];
                        if ( isset($_GET["show_all"]) )
                                $url .= "&show_all=yes";
                        if ( isset($_GET["search_in_subcategory"]) )
                                $url .= "&search_in_subcategory=1";
                        if ( isset($_GET["advanced_search_in_category"]) )
                                $url .= "&advanced_search_in_category=".$_GET["advanced_search_in_category"];
                        if ( isset($_GET["search_with_change_category_ability"]) )
                                $url .= "&search_with_change_category_ability=".$_GET["search_with_change_category_ability"];
                        if ( isset($_GET["categorySelect"]) )
                                $url .= "&categorySelect=".$_GET["categorySelect"];
                        return $url;
                }

                function _sortSetting( &$smarty, $urlToSort )
                {
                        $sort_string = STRING_PRODUCT_SORT;
                        $sort_string = str_replace( "{ASC_NAME}",   "<a href='".$urlToSort."&sort=name&direction=ASC'>".STRING_ASC."</a>",        $sort_string );
                        $sort_string = str_replace( "{DESC_NAME}",  "<a href='".$urlToSort."&sort=name&direction=DESC'>".STRING_DESC."</a>",        $sort_string );
                        $sort_string = str_replace( "{ASC_PRICE}",   "<a href='".$urlToSort."&sort=Price&direction=ASC'>".STRING_ASC."</a>",        $sort_string );
                        $sort_string = str_replace( "{DESC_PRICE}",  "<a href='".$urlToSort."&sort=Price&direction=DESC'>".STRING_DESC."</a>",        $sort_string );
                        $sort_string = str_replace( "{ASC_RATING}",   "<a href='".$urlToSort."&sort=customers_rating&direction=ASC'>".STRING_ASC."</a>",        $sort_string );
                        $sort_string = str_replace( "{DESC_RATING}",  "<a href='".$urlToSort."&sort=customers_rating&direction=DESC'>".STRING_DESC."</a>",        $sort_string );
                        $smarty->assign( "string_product_sort", html_amp($sort_string));
                }


                //get selected category info
                $category = catGetCategoryById( $categoryID );

                if ( !$category || $categoryID == 1) //do not show root category
                {
                                header("HTTP/1.0 404 Not Found");
                                header("HTTP/1.1 404 Not Found");
                                header("Status: 404 Not Found");
                                die(ERROR_404_HTML);
                }
                else //show category
                {
                        IncrementCategoryViewedTimes($categoryID);
                        if ( isset($_GET["prdID"]) )
                        {
                                if (  isset($_POST["cart_".$_GET["prdID"]."_x"])  )
                                {
                                        $variants=array();

                                        foreach( $_POST as $key => $val )
                                        {
                                                if ( strstr($key, "option_select_hidden") )
                                                {
                                                        $arr=explode( "_", str_replace("option_select_hidden_","",$key) );
                                                        if ( (string)$arr[1] == (string)$_GET["prdID"] )
                                                                $variants[]=$val;
                                                }
                                        }
                                        unset($_SESSION["variants"]);
                                        $_SESSION["variants"]=$variants;
                                        Redirect( "index.php?shopping_cart=yes&add2cart=".$_GET["prdID"]."&multyaddcount=".(int)$_POST['multyaddcount'] );
                                }
                        }

                        if (!file_exists("data/category/".$category["picture"])) $category["picture"] = "";
                        $smarty->assign("selected_category", $category );

                        if ( $category["allow_products_search"] ) $smarty->assign( "allow_products_search", 1 );

                        $callBackParam = array();
                        $products = array();
                        $callBackParam["categoryID"] = (int)$categoryID;
                        $callBackParam["enabled"]    = 1;

                        if (  isset($_GET["search_in_subcategory"])   )
                                if ( $_GET["search_in_subcategory"] == 1 )
                                {
                                        $callBackParam["searchInSubcategories"] = true;
                                        $callBackParam["searchInEnabledSubcategories"] = true;
                                }

                        if ( isset($_GET["sort"]) )  $callBackParam["sort"] = $_GET["sort"];
                        if ( isset($_GET["direction"]) )$callBackParam["direction"] = $_GET["direction"];

                        // search parametrs to advanced search
                        if ( $extraParametrsTemplate != null )
                                        $callBackParam["extraParametrsTemplate"] = $extraParametrsTemplate;
                        if ( $searchParamName != null )
                                        $callBackParam["name"] = $searchParamName;
                        if ( $rangePrice != null )
                                        $callBackParam["price"] = $rangePrice;

                        $count = 0;
                        $navigatorHtml = GetNavigatorHtml(
                                                _getUrlToNavigate( $categoryID ), CONF_PRODUCTS_PER_PAGE,
                                                'prdSearchProductByTemplate', $callBackParam,
                                                $products, $offset, $count );

                        $show_comparison = 0;
                        $cc_products = count($products);
                        for($i=0; $i<$cc_products; $i++)
                        {
                                $cat = catGetCategoryById( $products[$i]["categoryID"] );
                                $products[$i]["allow_products_comparison"] = $cat["allow_products_comparison"];
                                if ( ($products[$i]["allow_products_comparison"] == 1) &&
                                         ($categoryID==$products[$i]["categoryID"])  )
                                        $show_comparison++;
                        }

                        if ( CONF_PRODUCT_SORT == '1' )
                                _sortSetting( $smarty, _getUrlToSort($categoryID) );

                        //calculate a path to the category
                        $smarty->assign( "product_category_path", catCalculatePathToCategory($categoryID) );
                        $smarty->assign( "search_with_change_category_ability", 1 );
                        $smarty->assign( "show_comparison", $show_comparison );
                        $smarty->assign( "catalog_navigator", $navigatorHtml );
                        $smarty->assign( "products_to_show_counter", count($products));
                        $smarty->assign( "products_to_show", $products);
                        $smarty->assign( "main_content_template", "category_search_result.tpl.html");
                }
        }
?><?php
#####################################
# ShopCMS: Скрипт интернет-магазина
# Copyright (c) by ADGroup
# http://shopcms.ru
#####################################

        // category navigation form

        if ( isset($categoryID) )
                $out = catGetCategoryCompactCList( $categoryID );
        else
                $out = catGetCategoryCompactCList( 1 );
        $smarty->assign( "categories_tree_count", count($out) );
        $smarty->assign( "categories_tree", $out ); 

        $smarty->assign( "big_categories_tree_count", count($cats) );
        $smarty->assign( "big_categories_tree", $cats );

?><?php
#####################################
# ShopCMS: Скрипт интернет-магазина
# Copyright (c) by ADGroup
# http://shopcms.ru
#####################################

        // currency selection form

        if (  isset($_POST["current_currency"]) )
        {
                currSetCurrentCurrency( $_POST["current_currency"] );

                $url = "index.php";
                $paramGetVars = "";
                foreach( $_GET as $key => $value )
                {
                        if ( $paramGetVars == "" )
                                $paramGetVars .= "?".$key."=".$value;
                        else
                                $paramGetVars .= "&".$key."=".$value;
                }
                if(isset($_POST["InvId"])){
                        if ( $paramGetVars == "" )
                                $paramGetVars .= "?InvId=".$_POST["InvId"];
                        else
                                $paramGetVars .= "&InvId=".$_POST["InvId"];
                }
                Redirect( $url.$paramGetVars );
        }

?><?php
if (CONF_AUTOSAVE){

$interval_update = 24;   // Интервал сохранения БД в часах. По умолчанию - сутки.
$delete_interval = 168;  // Интервал удаления старых дампов БД в часах. По умолчанию - неделя.
$deletedump = 1;         // Автоудаление дампов БД. "0" - выкл, "1" - вкл.

  $result = db_query( "select last_update from ".DUMP_TABLE." where type=1" );
  $results = db_fetch_row($result);
  $last_update = $results["last_update"];
  if ((time()-$last_update) > ($interval_update*3600))
  {
  $querys = "update ".DUMP_TABLE." set last_update = '".time()."' where type=1";
  $results = db_query($querys);
  $path = "core/backup";
      if (!is_dir($path)) return false;
      $handle=opendir ($path);
      $patterns[0] = "/-/";
      $replacements[0] = ":";
           while (false !== ($file = readdir ($handle))) {

                if (preg_match("/dump_20(.*?)\.sql\.gz/", $file, $matches))
                {
                preg_match("/(.*?)_(.*)/",$matches[1] , $matches);
                $filedate=$matches[1];
                $filetime=preg_replace($patterns,$replacements,$matches[2]);
                $filetimestamp=strtotime($filedate." ".$filetime);
                if (time()-$delete_interval*3600>=$filetimestamp && $deletedump>0)  unlink($path."/".$file);
                }

           }

  closedir($handle);
  include_once('core/classes/class.dump.php');
  $SK = new dumper();
  $SK->backup();
  }
}


?><?php
#####################################
# ShopCMS: Скрипт интернет-магазина
# Copyright (c) by ADGroup
# http://shopcms.ru
#####################################

        if(!isset($_SESSION["comparison"]) || !is_array($_SESSION["comparison"])) $_SESSION["comparison"] = array();
        if ( isset($comparison_products) && count($_SESSION["comparison"]) > 0)
        {
                $_SESSION["comparison"] = array_unique($_SESSION["comparison"]);
                $products = array();
                foreach( $_SESSION["comparison"] as $_productID )
                {
                        $product = GetProduct($_productID);
                        if ( $product )
                        {
                                $product["picture"]                = GetThumbnail( $_productID );
                                $product["saveWithUnit"]           = show_price($product["list_price"] - $product["Price"]);
                                if ( $product["list_price"] != 0 )
                                $product["savePercent"]            = ceil( ( ($product["list_price"] - $product["Price"])/$product["list_price"] )*100  );
                                $product["list_priceWithUnit"]     = show_price($product["list_price"]);
                                $product["PriceWithUnit"]          = show_price($product["Price"]);

                                $products[] = $product;
                        }
                }

                $options = configGetOptions();
                $definedOptions = array();
                foreach( $options as $option )
                {
                        $optionIsDefined = false;
                        foreach( $products as $product )
                        {
                                foreach( $product["option_values"] as $optionValue )
                                {
                                        if ( $optionValue["optionID"]==$option["optionID"] )
                                        {
                                                if ( $optionValue["option_type"] == 0 && $optionValue["value"]!=""
                                                        ||
                                                         $optionValue["option_type"] == 1 )
                                                {
                                                        $optionIsDefined = true;
                                                        break;
                                                }
                                        }
                                }
                        }
                        if ( $optionIsDefined )
                                $definedOptions[] = $option;
                }

                $optionIndex = 0;
                foreach( $definedOptions as $option )
                {
                        $productIndex = 0;
                        foreach( $products as $product )
                        {
                                $existFlag = false;

                                foreach( $product["option_values"] as $optionValue )
                                {
                                        if ( $optionValue["optionID"]==$option["optionID"] )
                                        {
                                                if ( $optionValue["option_type"] == 0 && $optionValue["value"]!="" )
                                                        $value = $optionValue["value"];
                                                 else if ( $optionValue["option_type"] == 1 )
                                                {
                                                        $value = "";
                                                        $extra = GetExtraParametrs( $product["productID"] );

                                                        foreach( $extra as $item )
                                                        {
                                                                if ( $item["option_type"] == 1 && $item["optionID"] == $optionValue["optionID"] && isset($item["values_to_select"]) && count( $item["values_to_select"] ) > 0 )
                                                                        //if option is defined
                                                                {
                                                                        foreach( $item["values_to_select"] as $value_to_select )
                                                                        {
                                                                                if ( $value != "" )
                                                                                        $value .= " / ".$value_to_select["option_valueWithOutPrice"];
                                                                                else
                                                                                        $value .= $value_to_select["option_valueWithOutPrice"];
                                                                        }
                                                                }
                                                        }
                                                }
                                                else
                                                        $value = STRING_VALUE_IS_UNDEFINED;

                                                // $item = array( "name" => $option["name"], "value" => $value );
                                                $products[ $productIndex ][ $optionIndex ] = $value;
                                                $existFlag = true;
                                                break;
                                        }
                                }
                                if ( !$existFlag ) $products[ $productIndex ][ $optionIndex ] =  STRING_VALUE_IS_UNDEFINED;

                                $productIndex++;
                        }
                        $optionIndex++;
                }


                $counta = count($products);
                if ( $counta > 0 )
                {
                        $smarty->assign("product_category_path",
                                catCalculatePathToCategory( $products[0]["categoryID"] ) );
                        $category = catGetCategoryById( $products[0]["categoryID"] );
                        if ( $category )
                                $smarty->assign("category_description", $category["description"]);
                }

                $smarty->assign("definedOptions", $definedOptions );
                $smarty->assign("products", $products );
                $smarty->assign("products_count", $counta );
                $smarty->assign("main_content_template", "comparison_products.tpl.html" );
        }
        $smarty->assign("compare_value", count($_SESSION["comparison"]) );

?><?php
#####################################
# ShopCMS: Скрипт интернет-магазина
# Copyright (c) by ADGroup
# http://shopcms.ru
#####################################

    if ( isset($contact_info) && isset($_SESSION["log"]) )
        {


                // *****************************************************************************
                // Purpose        copies data from $_POST variable to HTML page
                // Inputs                     $smarty - smarty object
                // Remarks
                // Returns        nothing
                function _copyDataFromPostToPage( & $smarty )
                {
                        $smarty->hassign("login", $_POST["login"] );
                        $smarty->hassign("cust_password1", $_POST["cust_password1"] );
                        $smarty->hassign("cust_password2", $_POST["cust_password2"] );
                        $smarty->hassign("first_name", $_POST["first_name"] );
                        $smarty->hassign("last_name", $_POST["last_name"] );
                        $smarty->hassign("email", $_POST["email"] );
                        $smarty->assign("subscribed4news", (isset($_POST["subscribed4news"])?1:0) );

                        $additional_field_values = array();
                        $data = ScanPostVariableWithId( array( "additional_field" ) );
                        foreach( $data as $key => $val )
                        {
                                $item = array( "reg_field_ID" => $key, "reg_field_name" => "",
                                        "reg_field_value" => $val["additional_field"] );
                                $additional_field_values[] = $item;
                        }
                        $smarty->hassign("additional_field_values", $additional_field_values );
                }


                // *****************************************************************************
                // Purpose        copies data from DataBase variable to HTML page
                // Inputs                     $smarty - smarty object
                //                                        $log - customer login
                // Remarks
                // Returns        nothing
                function _copyDataFromDataBaseToPage( & $smarty, $log )
                {
                        $cust_password = 0;            $Email = 0;             $first_name = 0;
                        $last_name = 0;                $subscribed4news=0;     $additional_field_values = 0;
                        $countryID = 0;                $zoneID = 0;            $state = 0;
                        $city      = 0;                $address = 0;
                        regGetContactInfo( $log, $cust_password, $Email, $first_name,
                                $last_name, $subscribed4news, $additional_field_values );
                        $smarty->assign("login", $log );
                        $smarty->assign("cust_password1", $cust_password );
                        $smarty->assign("cust_password2", $cust_password );
                        $smarty->assign("first_name", $first_name );
                        $smarty->assign("last_name", $last_name );
                        $smarty->assign("email", $Email );
                        $smarty->assign("subscribed4news", $subscribed4news );
                        $smarty->assign("additional_field_values", $additional_field_values );
                }



                if ( isset($_POST["login"]) )
                        _copyDataFromPostToPage( $smarty );
                else
                        _copyDataFromDataBaseToPage( $smarty, $_SESSION["log"] );

                if ( isset($_POST["save"]) )
                {
                        $login                                = $_POST["login"];
                        $cust_password1                = $_POST["cust_password1"];
                        $cust_password2                = $_POST["cust_password2"];
                        $first_name                        = $_POST["first_name"];
                        $last_name                        = $_POST["last_name"];
                        $Email                                = $_POST["email"];
                        $subscribed4news        = ( isset($_POST["subscribed4news"]) ? 1 : 0 );
                        $additional_field_values = ScanPostVariableWithId( array( "additional_field" ) );
                        if ( ( trim($login) != trim($_SESSION["log"]) ) && regIsRegister($login) )
                                $error = ERROR_USER_ALREADY_EXISTS;
                        if ( !isset($error) )
                                $error = regVerifyContactInfo( $login, $cust_password1, $cust_password2,
                                                $Email, $first_name, $last_name, $subscribed4news,
                                                $additional_field_values );

                        if ( $error == "" ) unset($error);

                        if ( !isset($error) )
                        {
                                regUpdateContactInfo( $_SESSION["log"], $login, $cust_password1,
                                                $Email, $first_name, $last_name, $subscribed4news,
                                                $additional_field_values );
                                $_SESSION["log"]        = $login;
                                $_SESSION["pass"]        = cryptPasswordCrypt($cust_password1, null);
                                Redirect( "index.php?contact_info=yes" );
                        }
                        else
                                $smarty->assign( "error", $error );
                }

                // additional fields
                $additional_fields=GetRegFields();
                $smarty->assign("additional_fields", $additional_fields );
                $smarty->assign("main_content_template", "contact_info.tpl.html");
        }
?><?php
#####################################
# ShopCMS: Скрипт интернет-магазина
# Copyright (c) by ADGroup
# http://shopcms.ru
#####################################

  $user_agent = (!empty($_SERVER['HTTP_USER_AGENT'])) ? strtolower(htmlspecialchars((string) $_SERVER['HTTP_USER_AGENT'])) : '';
  $accept_language = (!empty($_SERVER['HTTP_ACCEPT_LANGUAGE'])) ? strtolower(htmlspecialchars((string) $_SERVER['HTTP_ACCEPT_LANGUAGE'])) : '';
  $today=date('d.m.Y',time());

  $q = db_query("select today from ".COUNTER_TABLE." WHERE tbid=1");
  $n = db_fetch_row($q);
  $date = $n[0];
  $past = time()-CONF_ONLINE_EXPIRE*60;
  $ctime = time();

  if($today!=$date)
  {
  db_query("UPDATE ".COUNTER_TABLE." SET todayp=0, todayv=0, today='".$today."' WHERE tbid=1");
  db_query("DELETE FROM ".ONLINE_TABLE);
  db_query("DELETE FROM ".SESSION_TABLE." where expire < UNIX_TIMESTAMP()");
  }

  $todayp = 0;
  $allp = 0;
  $ip = stGetCustomerIP_Address();
  if ($ip) {

    $uniqhash = md5($ip.$user_agent);
    db_query("replace into ".ONLINE_TABLE." values ('".$uniqhash."', '".$ctime."')");
    $matches = mysql_affected_rows();
    if ($matches == 1) {
    $todayp = 1;
    $allp = 1;
    }

    $allieb=0;$allmozb=0;$allopb=0;$allozb=0;$allrusl=0;$allenl=0;$allozl=0;$allwins=0;$alllins=0;$allmacs=0;$allozs=0;

        switch (true)
        {
        case (preg_match("/win/i",$user_agent)):
                $allwins = 1;
                break;
        case (preg_match("/linux/i",$user_agent)):
                $alllins = 1;
                break;
        case (preg_match("/mac/i",$user_agent)):
                $allmacs = 1;
                break;
        default:
                $allozs = 1;
                break;
        }

        switch (true)
        {
        case (preg_match("/opera/i",$user_agent)):
                $allopb = 1;
                break;
        case (preg_match("/msie/i",$user_agent)):
                $allieb = 1;
                break;
        case (preg_match("/mozilla/i",$user_agent)):
                $allmozb = 1;
                break;
        default:
                $allozb = 1;
                break;
        }

        switch (true)
        {
        case (preg_match("/ru/i",$accept_language)):
                $allrusl = 1;
                break;
        case (preg_match("/en/i",$accept_language)):
                $allenl = 1;
                break;
        default:
                $allozl = 1;
                break;
        }

        db_query("UPDATE ".COUNTER_TABLE." SET todayp=todayp+".$todayp.", todayv=todayv+1, allp=allp+".$allp.", allv=allv+1, allieb=allieb+".$allieb.", allmozb=allmozb+".$allmozb.", allopb=allopb+".$allopb.", allozb=allozb+".$allozb.", allrusl=allrusl+".$allrusl.", allenl=allenl+".$allenl.", allozl=allozl+".$allozl.", allwins=allwins+".$allwins.", alllins=alllins+".$alllins.", allmacs=allmacs+".$allmacs.", allozs=allozs+".$allozs." WHERE tbid=1");
    }

        $past = time()-CONF_ONLINE_EXPIRE*60;
        $result = db_query("select count(*) from ".ONLINE_TABLE." WHERE time > ".$past);
        $u = db_fetch_row($result);
        if (!$u[0]){ $usersonline = 1; }else{ $usersonline = $u[0];}
        $smarty->assign("online_users",$usersonline);
        $result = db_query("select todayp, todayv, allp, allv from ".COUNTER_TABLE." WHERE tbid=1");
        $u = db_fetch_row($result);
        if (!$u[0]) {$usr1 = 1; }else{ $usr1 = $u[0];}
        if (!$u[1]) {$usr2 = 1; }else{ $usr2 = $u[1];}
        if (!$u[2]) {$usr3 = 1; }else{ $usr3 = $u[2];}
        if (!$u[3]) {$usr4 = 1; }else{ $usr4 = $u[3];}
        $smarty->assign("online_usr1",$usr2);
        $smarty->assign("online_usr2",$usr1);
        $smarty->assign("online_usr3",$usr4);
        $smarty->assign("online_usr4",$usr3);
?><?php
#####################################
# ShopCMS: Скрипт интернет-магазина
# Copyright (c) by ADGroup
# http://shopcms.ru
#####################################

        // customer survey processing

        if ((isset($_GET["save_voting_results"]) || isset($_GET["view_voting_results"])) && isset($_SESSION)) //save survey results
        {

                $result = db_query("select poll_id, poll_title, poll_ans, ans_0, ans_1, ans_2, ans_3, ans_4,
                ans_5, ans_6, ans_7, ans_8, ans_9, iplog, tdate FROM ".SURVEY_TABLE." WHERE active=1");
                $data = db_fetch_row($result);
				if($data["tdate"] != date('Y-m-d',time())){ 
				db_query("UPDATE ".SURVEY_TABLE." SET iplog='', tdate='".xEscSQL(get_current_time())."'  WHERE active=1");
				$data["iplog"] = "";
				}
                $answers_results = unserialize($data["poll_ans"]);
				if($data["iplog"]!=""){$iplogs = unserialize($data["iplog"]);}else{$iplogs = array();}
				$ipaddr = stGetCustomerIP_Address();
				if(!isset($iplogs[$ipaddr]))$iplogs[$ipaddr]=0;
   
                //increase voters count for current option
                if ((!isset($_SESSION["vote_completed"][$data[0]]) || $_SESSION["vote_completed"][$data[0]] != 1)
                        && isset($_GET["answer"]) && isset($answers_results[$_GET["answer"]]) && $iplogs[$ipaddr]<3) {						
                $anscol = (int)$_GET["answer"];
				$iplogs[$ipaddr]++;
				$iplogs = serialize($iplogs);
                db_query("UPDATE ".SURVEY_TABLE." SET ans_".$anscol."=ans_".$anscol."+1, all_poll=all_poll+1, iplog='".xEscSQL($iplogs)."'  WHERE active=1");
                $data["ans_".$anscol]++;
                //don't allow user to vote more than 1 time
                $_SESSION["vote_completed"][$data[0]] = 1;
                }else{
                if(!isset($_GET["view_voting_results"]))$smarty->assign("user_voted", 1);
                }
                $survey_results = array();
                for ($i=0; $i<count($answers_results); $i++) $survey_results[$i] = $data["ans_".$i];
                $smarty->assign("survey_results", $survey_results);
                $smarty->assign("show_survey_results", 1);
                $smarty->assign("main_content_template", "customer_survey_result.tpl.html");
        }


        $result = db_query("select poll_id, poll_title, poll_ans, all_poll FROM ".SURVEY_TABLE." WHERE active=1");
        $data = db_fetch_row($result);
        $answers = unserialize($data["poll_ans"]);
        $smarty->assign("survey_id", $data[0]);
        $smarty->assign("survey_question", $data[1]);
        $smarty->assign("survey_answers", $answers);
        $smarty->assign("voters_count", $data[3]);


?><?php
#####################################
# ShopCMS: Скрипт интернет-магазина
# Copyright (c) by ADGroup
# http://shopcms.ru
#####################################

        if (isset($_GET["feedback"]) || isset($_POST["feedback"]))
        {
                if (isset($_POST["feedback"]))
                {
                        $customer_name = $_POST["customer_name"];
                        $customer_email = $_POST["customer_email"];
                        $message_subject = $_POST["message_subject"];
                        $message_text = $_POST["message_text"];
                }
                else
                {
                        $customer_name = "";
                        $customer_email = "";
                        $message_subject = "";
                        $message_text = "";
                }

                //validate input data
                if (trim($customer_email)!="" && trim($customer_name)!="" && trim($message_subject)!="" && trim($message_text)!="" && preg_match("/^[_\.a-z0-9-]{1,20}@(([a-z0-9-]+\.)+(com|net|org|mil|edu|gov|arpa|info|biz|inc|name|[a-z]{2})|[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3})$/is",$customer_email))
                {
                        if(CONF_ENABLE_CONFIRMATION_CODE){
                                   $error_f = 1;
                          if(!$_POST['fConfirmationCode'] || !isset($_SESSION['captcha_keystring']) || $_SESSION['captcha_keystring'] !==  $_POST['fConfirmationCode']) {
                                   $error_f = 2;
                                   $smarty->assign("error",$error_f);
                          }
                          unset($_SESSION['captcha_keystring']);
                          if($error_f == 1){
                          if (xMailTxtHTML(CONF_GENERAL_EMAIL, $message_subject, $message_text, $customer_email, $customer_name)){
                          Redirect("index.php?feedback=1&sent=1");
                          }else{
                          $smarty->assign("error",3);
                          }
                          }
                        }else{
                          if (xMailTxtHTML(CONF_GENERAL_EMAIL, $message_subject, $message_text, $customer_email, $customer_name)){
                          Redirect("index.php?feedback=1&sent=1");
                          }else{
                          $smarty->assign("error",3);
                          }
                        }
                }
                else if (isset($_POST["feedback"])) $smarty->assign("error",1);

                //extract input to Smarty
                $smarty->hassign("customer_name",$customer_name);
                $smarty->hassign("customer_email",$customer_email);
                $smarty->hassign("message_subject",$message_subject);
                $smarty->hassign("message_text",$message_text);

                if (isset($_GET["sent"])) $smarty->assign("sent",1);

                $smarty->assign("main_content_template", "feedback.tpl.html");
        }

?><?php
#####################################
# ShopCMS: Скрипт интернет-магазина
# Copyright (c) by ADGroup
# http://shopcms.ru
#####################################

        // <head> variables definition: title, meta

        // TITLE & META Keywords & META Description

        if ( isset($_GET["show_aux_page"]) ) // aux page => get title and META information from database
        {
                $page = auxpgGetAuxPage( $show_aux_page );
                        if ($page["aux_page_title"]) $page_title = $page["aux_page_title"];
                        elseif ($page["aux_page_name"]) $page_title = $page["aux_page_name"];
                        else $page_title =  CONF_SHOP_NAME." - ".CONF_DEFAULT_TITLE;
                $meta_tags = "";
                if  ( $page["meta_description"] != "" )
                        $meta_tags .= "<meta name=\"description\" content=\"".$page["meta_description"]."\">\n";
                if  ( $page["meta_keywords"] != "" )
                        $meta_tags .= "<meta name=\"keywords\" content=\"".$page["meta_keywords"]."\">\n";

        }
        elseif (isset($_GET["fullnews"]))  //  fullnews => get title
        {
                $fullnews_array_head = newsGetFullNewsToCustomer($_GET["fullnews"]);
                        if ($fullnews_array_head["title"]) $page_title = $fullnews_array_head["title"];
                        else $page_title =  CONF_SHOP_NAME." - ".CONF_DEFAULT_TITLE;
                                $meta_tags = "";
                                if  ( CONF_HOMEPAGE_META_DESCRIPTION != "" )
                                        $meta_tags .= "<meta name=\"description\" content=\"".CONF_HOMEPAGE_META_DESCRIPTION."\">\n";
                                if  ( CONF_HOMEPAGE_META_KEYWORDS != "" )
                                        $meta_tags .= "<meta name=\"keywords\" content=\"".CONF_HOMEPAGE_META_KEYWORDS."\">\n";
        }
        else  //not an aux page, e.g. homepage, product/category page, registration form, checkout, etc.
        {
                if (isset($categoryID) && !isset($productID) && $categoryID>0) //category page
                {
                        $q = db_query("select name, title FROM ".CATEGORIES_TABLE." WHERE categoryID=".(int)$categoryID);
                        $r = db_fetch_row($q);
                        if ($r[1]) $page_title = $r[1];
                        elseif ($r[0]) $page_title = $r[0];
                        else $page_title =  CONF_SHOP_NAME." - ".CONF_DEFAULT_TITLE;
                        $meta_tags = catGetMetaTags($categoryID);

                }
                else if (isset($productID) && $productID>0) //product information page
                        {
                                $q = db_query("select name, title FROM ".PRODUCTS_TABLE." WHERE productID=".(int)$productID);
                                $r = db_fetch_row($q);
                                if($r[1]) $page_title = $r[1];
                                elseif($r[0]) $page_title = $r[0];
                                else $page_title =  CONF_SHOP_NAME." - ".CONF_DEFAULT_TITLE;
                                $meta_tags = prdGetMetaTags($productID);
                        }
                        else // other page
                        {
                                $page_title = CONF_SHOP_NAME." - ".CONF_DEFAULT_TITLE;
                                $meta_tags = "";
                                if  ( CONF_HOMEPAGE_META_DESCRIPTION != "" )
                                        $meta_tags .= "<meta name=\"description\" content=\"".CONF_HOMEPAGE_META_DESCRIPTION."\">\n";
                                if  ( CONF_HOMEPAGE_META_KEYWORDS != "" )
                                        $meta_tags .= "<meta name=\"keywords\" content=\"".CONF_HOMEPAGE_META_KEYWORDS."\">\n";
                        }
        }

        $variodesign = settingSELECT_USERTEMPLATE();
        $smarty->assign("variodesign",$variodesign );
        $smarty->assign("page_title", $page_title );
        $smarty->assign("page_meta_tags", $meta_tags );

?><?php
#####################################
# ShopCMS: Скрипт интернет-магазина
# Copyright (c) by ADGroup
# http://shopcms.ru
#####################################

  // Helper for Robokassa
  // Result Url - index.php?robokassa=result (POST method)
  // Success Url - index.php?robokassa=success&transaction_result=success (POST method)
  // Fail Url - index.php?transaction_result=failure (POST method)

  if(isset($_REQUEST["robokassa"]) && isset($_REQUEST["SignatureValue"])){
        $result = '';
        $orderID = (int) $_REQUEST["InvId"];
        $q = db_query( "select paymethod  from ".ORDERS_TABLE." where orderID=".$orderID);
        $order = db_fetch_row($q);
        if ( $order )
        {
            $paymentMethod = payGetPaymentMethodById( $order["paymethod"] );
            $currentPaymentModule = modGetModuleObj( $paymentMethod["module_id"], PAYMENT_MODULE );
            if ( $currentPaymentModule != null ) $result = $currentPaymentModule->after_payment_php( $orderID, $_REQUEST["OutSum"], $_REQUEST["SignatureValue"], $_REQUEST["robokassa"]);

            if ($result != '' && $_REQUEST["robokassa"]=="result") die($result);
        }
  }


  // Helper for Webmoney (preresult)
  // Result Url - index.php?webmoney=yes (POST method)
  // Success Url - index.php?transaction_result=success (POST method)
  // Fail Url - index.php?transaction_result=failure (POST method)
  // Передавать параметры в предварительном запросе
  // Не высылать Secret Key, если Result URL обеспечивает безопасность
  // Не позволять использовать URL, передаваемые в форме
  // Метод формирования контрольной подписи MD5

  if(isset($_REQUEST["webmoney"]) && isset($_REQUEST["LMI_PREREQUEST"])){
        $result = '';
        $orderID = (int) $_REQUEST["LMI_PAYMENT_NO"];
        $q = db_query( "select paymethod  from ".ORDERS_TABLE." where orderID=".$orderID);
        $order = db_fetch_row($q);
        if ( $order )
        {
            $paymentMethod = payGetPaymentMethodById( $order["paymethod"] );
            $currentPaymentModule = modGetModuleObj( $paymentMethod["module_id"], PAYMENT_MODULE );
            if ( $currentPaymentModule != null ) $result = $currentPaymentModule->before_payment_php( $orderID, $_REQUEST["LMI_PAYMENT_AMOUNT"], $_REQUEST["LMI_PAYEE_PURSE"]);

            if ($result != '') die($result);
        }
  }

  // Helper for Webmoney (result)
  // Result Url - index.php?webmoney=yes
  // Success Url - index.php?transaction_result=success
  // Fail Url - index.php?transaction_result=failure (POST method)
  // Передавать параметры в предварительном запросе
  // Не высылать Secret Key, если Result URL обеспечивает безопасность
  // Не позволять использовать URL, передаваемые в форме
  // Метод формирования контрольной подписи MD5

  if(isset($_REQUEST["webmoney"]) && !isset($_REQUEST["LMI_PREREQUEST"])){
        $orderID = (int) $_REQUEST["LMI_PAYMENT_NO"];
        $q = db_query( "select paymethod  from ".ORDERS_TABLE." where orderID=".$orderID);
        $order = db_fetch_row($q);
        if ( $order )
        {
            $paymentMethod = payGetPaymentMethodById( $order["paymethod"] );
            $currentPaymentModule = modGetModuleObj( $paymentMethod["module_id"], PAYMENT_MODULE );
            if ( $currentPaymentModule != null ) $result = $currentPaymentModule->after_payment_php( $orderID, $_REQUEST);
        }
  }


  // Helper for Z-payment
  // Result Url - index.php?zpayment=yes (POST method)
  // Success Url - index.php?transaction_result=success (POST method)
  // Fail Url - index.php?transaction_result=failure (POST method)
  // Не высылать предварительный запрос перед оплатой на Result URL
  // Не высылать Merchant Key, если Result URL обеспечивает безопасность

  if(isset($_REQUEST["zpayment"])){
        $result = '';
        $orderID = (int) $_REQUEST["LMI_PAYMENT_NO"];
        $q = db_query( "select paymethod  from ".ORDERS_TABLE." where orderID=".$orderID);
        $order = db_fetch_row($q);
        if ( $order )
        {
            $paymentMethod = payGetPaymentMethodById( $order["paymethod"] );
            $currentPaymentModule = modGetModuleObj( $paymentMethod["module_id"], PAYMENT_MODULE );
            if ( $currentPaymentModule != null ) $result = $currentPaymentModule->after_payment_php( $orderID, $_REQUEST);
            if ($result != '') die($result);
        }
  }

?>
<?php
#####################################
# ShopCMS: Скрипт интернет-магазина
# Copyright (c) by ADGroup
# http://shopcms.ru
#####################################


        //special offers

        $result = array();

        $q = db_query("select s.productID, s.categoryID, s.name, s.Price, s.brief_description, s.product_code,
        s.default_picture, s.enabled, b.productID, t.filename FROM ".SPECIAL_OFFERS_TABLE."
        AS b INNER JOIN ".PRODUCTS_TABLE." AS s on (b.productID=s.productID) INNER JOIN ".PRODUCT_PICTURES." AS
        t on (s.default_picture=t.photoID AND s.productID=t.productID) WHERE s.enabled=1 order by b.sort_order");

        while ($row = db_fetch_row($q))
        {
              if (strlen($row["filename"])>0 && file_exists( "data/small/".$row["filename"])){
                                        $row["default_picture"] = "small/".$row["filename"];
                                        $row["cena"] = $row[3];
                                        $row["Price"] = show_price($row[3]);
                                        $result[] = $row;
              }

        }

        $smarty->assign("special_offers",$result);


        $cifra = 8; //количество последних товаров для выбора
        $result = array();

        $q = db_query("select s.productID, s.name, s.Price, s.enabled, t.filename FROM ".PRODUCTS_TABLE." AS s LEFT JOIN ".PRODUCT_PICTURES."
        AS t on (s.default_picture=t.photoID AND s.productID=t.productID) WHERE s.categoryID!=1 AND s.enabled=1 ORDER BY s.date_added DESC LIMIT 0,".$cifra);

        while ($row = db_fetch_row($q))
        {
              if (strlen($row["filename"])>0 && file_exists( "data/small/".$row["filename"])){
                                        $row["filename"] = "small/".$row["filename"];
                                        $row["cena"] = $row["Price"];
                                        $row["Price"] = show_price($row["Price"]);
                                        $result[] = $row;

              }else{
                                        $row["filename"] = "empty.gif";
                                        $row["cena"] = $row["Price"];
                                        $row["Price"] = show_price($row["Price"]);
                                        $result[] = $row;
              }
        }
        $smarty->assign("new_products", $result);


        $cifra = 8; //количество последних товаров для выбора
        $result = array();

        $q = db_query("select s.productID, s.name, s.Price, s.enabled, t.filename FROM ".PRODUCTS_TABLE." AS s LEFT JOIN ".PRODUCT_PICTURES."
        AS t on (s.default_picture=t.photoID AND s.productID=t.productID) WHERE s.categoryID!=1 AND s.enabled=1 ORDER BY s.items_sold DESC LIMIT 0,".$cifra);

        while ($row = db_fetch_row($q))
        {
              if (strlen($row["filename"])>0 && file_exists( "data/small/".$row["filename"])){
                                        $row["filename"] = "small/".$row["filename"];
                                        $row["cena"] = $row["Price"];
                                        $row["Price"] = show_price($row["Price"]);
                                        $result[] = $row;

              }else{
                                        $row["filename"] = "empty.gif";
                                        $row["cena"] = $row["Price"];
                                        $row["Price"] = show_price($row["Price"]);
                                        $result[] = $row;
              }
        }
        $smarty->assign("popular_products", $result);


/*
        $result = array();
        $q = db_query("select productID FROM ".PRODUCTS_TABLE." WHERE categoryID!=1 AND enabled=1");
        while ($row = db_fetch_row($q))$result[] = $row[0];
        $q = db_query("select s.productID, s.name, s.Price, s.enabled, t.filename FROM ".PRODUCTS_TABLE." AS s LEFT JOIN ".PRODUCT_PICTURES."
        AS t on (s.default_picture=t.photoID AND s.productID=t.productID) WHERE s.productID=".$result[rand(0, count($result)-1)]);
        $result = array();
        $row = db_fetch_row($q);

              if (strlen($row["filename"])>0 && file_exists( "data/small/".$row["filename"])){
                                        $row["filename"] = "small/".$row["filename"];
                                        $row["cena"] = $row["Price"];
                                        $row["Price"] = show_price($row["Price"]);
                                        $result[] = $row;

              }else{
                                        $row["filename"] = "empty.gif";
                                        $row["cena"] = $row["Price"];
                                        $row["Price"] = show_price($row["Price"]);
                                        $result[] = $row;
              }

        $smarty->assign("rand_product", $result[0]);
*/
?><?php
#####################################
# ShopCMS: Скрипт интернет-магазина
# Copyright (c) by ADGroup
# http://shopcms.ru
#####################################


    if ( isset($_POST['links_exchange']) || isset($_GET['links_exchange']) )
        {
                if(isset($_POST['fACTION']))
                if($_POST['fACTION'] == 'ADD_LINK'){

                        do{

                                if(!strlen(str_replace('http://','',$_POST['LINK']['le_lURL']))){

                                        $error = STRING_ERROR_LE_ENTER_LINK;
                                        break;
                                }
                                $_POST['LINK']['le_lURL'] = xEscSQL($_POST['LINK']['le_lURL']);
                                if(!strlen($_POST['LINK']['le_lText'])){

                                        $error = STRING_ERROR_LE_ENTER_TEXT;
                                        break;
                                }
                if(strlen($_POST['LINK']['le_lDesk'])){

                         $_POST['LINK']['le_lDesk'] = xToText($_POST['LINK']['le_lDesk']);
                }
                                $_POST['LINK']['le_lText'] = xToText($_POST['LINK']['le_lText']);
                                if(strpos($_POST['LINK']['le_lURL'],'http://')) $_POST['LINK']['le_lURL'] = 'http://'.$_POST['LINK']['le_lURL'];

                        if(CONF_ENABLE_CONFIRMATION_CODE){
                                 $error_f = 1;
                        if(!$_POST['fConfirmationCode'] || !isset($_SESSION['captcha_keystring']) || $_SESSION['captcha_keystring'] !==  $_POST['fConfirmationCode']) {
                                 $error_f = 2;
                                 $error = ERR_WRONG_CCODE;
                        }
                        unset($_SESSION['captcha_keystring']);
                        if($error_f == 1){
                                if(le_addLink($_POST['LINK']))break;
                                else $error = STRING_ERROR_LE_LINK_EXISTS;
                        }
                        }else{
                                if(le_addLink($_POST['LINK']))break;
                                else $error = STRING_ERROR_LE_LINK_EXISTS;
                        }

                        }while(0);

                        if(!isset($error))Redirect(set_query('added=ok', $_POST['fREDIRECT']));
                }

                #Links number per page
                $ob_per_list = 20;

                if(empty($_GET['le_categoryID']))$_GET['le_categoryID'] = 0;
                else $_GET['le_categoryID'] = (int)$_GET['le_categoryID'];

                $TotalPages = ceil(le_getLinksNumber(($_GET['le_categoryID']?"le_lCategoryID = {$_GET['le_categoryID']}":'1').' AND le_lVerified IS NOT NULL')/$ob_per_list);

                if(empty($_GET['page']))$_GET['page'] = 1;
                else $_GET['page'] = (int)$_GET['page']>$TotalPages?$TotalPages:(int)$_GET['page'];

                if(isset($_GET['added'])||isset($_POST['added']))$error = STRING_ERROR_LE_LINK_ADDED;
                $_SERVER['REQUEST_URI'] = set_query('added=');
                $lister = getListerRange($_GET['page'], $TotalPages);
                $le_Categories =  le_getCategories();

                if(isset($_GET['show_all'])||isset($_POST['show_all'])){

                        $ob_per_list = $ob_per_list*$TotalPages;
                        $smarty->assign('showAllLinks', '1');
                        $_GET['page'] = 1;
                }

                $smarty->assign('REQUEST_URI', html_amp($_SERVER['REQUEST_URI']));
                $smarty->assign('url_allcategories', set_query('le_categoryID='));
                $smarty->assign('le_categories', $le_Categories);
                $smarty->assign('le_CategoryID', $_GET['le_categoryID']);
                $smarty->assign('curr_page',$_GET['page']);
                $smarty->assign('last_page', $TotalPages);
                if(isset($error)){

                        if($error!=STRING_ERROR_LE_LINK_ADDED){

                                $smarty->assign('error',$error);
                                $smarty->assign('pst_LINK',html_spchars($_POST['LINK']));
                        }
                        else
                                $smarty->assign('error_ok',$error);
                }

                (isset($_GET['le_categoryID'])) ? (int)$_GET['le_categoryID'] : 1;

                $smarty->assign('le_links', le_getLinks(
                                (int)$_GET['page'],
                                (int)$ob_per_list,
                                ($_GET['le_categoryID']?"le_lCategoryID = {$_GET['le_categoryID']}":'1')." AND (le_lVerified IS NOT NULL AND le_lVerified <>'0000-00-00 00:00:00' )",
                                'le_lID, le_lText, le_lDesk, le_lURL, le_lCategoryID, le_lVerified',
                                'le_lVerified ASC, le_lURL ASC'));
                if($lister['start']<$lister['end'])$smarty->assign('le_lister_range', range($lister['start'], $lister['end']));
                $smarty->assign('le_categories_pr', ceil(count($le_Categories)/2));

                $smarty->assign("main_content_template", "links_exchange.tpl.html");
        }

?><?php
#####################################
# ShopCMS: Скрипт интернет-магазина
# Copyright (c) by ADGroup
# http://shopcms.ru
#####################################


        $news_array = newsGetNewsToCustomer();
        $smarty->assign( "news_array", $news_array );
        $pre_news_array = newsGetPreNewsToCustomer();
        $smarty->assign( "pre_news_array", $pre_news_array );
        if ( isset($_POST["subscribe"]) )
        {
                $error = subscrVerifyEmailAddress($_POST["email"]);
              if ( $_POST["modesubs"] == 0 ) {
              if ( $error == "" )
                {

                        if( _subscriberIsSubscribed ( $_POST["email"] )){

                        subscrUnsubscribeSubscriberByEmail2( $_POST["email"] );
                        $smarty->assign( "un_pol", 1);

                        }else{

                        $smarty->assign( "un_pol", 2);

                        }
                }
                else
                        $smarty->assign( "error_message", $error );
              }else{
                if ( $error == "" )
                {
                        $smarty->assign( "subscribe", 1 );
                        subscrAddUnRegisteredCustomerEmail( $_POST["email"] );
                }
                else
                        $smarty->assign( "error_message", $error );
                        }

        $smarty->assign( "main_content_template", "subscribe.tpl.html" );
        }

        if ( isset($_POST["email"]) )
                $smarty->hassign( "email_to_subscribe", $_POST["email"] );
        else
                $smarty->assign( "email_to_subscribe", "Email" );

        if ( isset($_GET["news"]) ) $smarty->assign( "main_content_template", "show_news.tpl.html" );
    
        if ( isset($_GET["fullnews"]) ){
        
	    $fullnews_array = newsGetFullNewsToCustomer($_GET["fullnews"]);

	    if ( $fullnews_array )
                {
                        $smarty->assign( "news_full_array", $fullnews_array );
                        $smarty->assign( "main_content_template", "show_full_news.tpl.html" );
                }
                else
                {
                        header("HTTP/1.0 404 Not Found");
                        header("HTTP/1.1 404 Not Found");
                        header("Status: 404 Not Found");
                        die(ERROR_404_HTML);
                }

        }
?><?php
#####################################
# ShopCMS: Скрипт интернет-магазина
# Copyright (c) by ADGroup
# http://shopcms.ru
#####################################


        if ( isset($order2_shipping) )
        {//var_dump($_GET);

                if(!cartCheckMinTotalOrderAmount()){

                        Redirect('index.php?shopping_cart=yes&min_order=error');
                }
                if ( !isset($_GET["order2_shipping"]) || !isset($_GET["shippingAddressID"]) )
                        Redirect( "index.php?page_not_found=yes" );

                $_GET["shippingAddressID"] = (int)$_GET["shippingAddressID"];

                if ($_GET["shippingAddressID"] == 0) //no default address specified
                {
                        $addrs = regGetAllAddressesByLogin($_SESSION["log"]);
                }
                else
                {
                        if ( !regAddressBelongToCustomer(regGetIdByLogin($_SESSION["log"]), $_GET["shippingAddressID"]) )
                                Redirect( "index.php?page_not_found=yes" );
                }

                if ( !cartCheckMinOrderAmount() )  Redirect( "index.php?shopping_cart=yes" );

                function _getOrder()
                {
                        $cust_password           = "";
                        $Email                   = "";
                        $first_name              = "";
                        $last_name               = "";
                        $subscribed4news         = "";
                        $additional_field_values = "";
                        $countryID               = "";
                        $zoneID                  = "";
                        $state                   = "";
                        $city                    = "";
                        $address                 = "";

                        regGetCustomerInfo($_SESSION["log"],
                                        $cust_password, $Email, $first_name,
                                        $last_name, $subscribed4news, $additional_field_values,
                                        $countryID, $zoneID, $state, $city, $address );


                        $order["first_name"] = $first_name;
                        $order["last_name"]  = $last_name;
                        $order["email"]      = $Email;

                        $res = cartGetCartContent();
                        $order["orderContent"]        = $res["cart_content"];

                        $d = oaGetDiscountPercent( $res, $_SESSION["log"] );
                        $order["order_amount"] = $res["total_price"] - ($res["total_price"]/100)*$d;

                        return $order;
                }

                if ( isset($_GET["selectedNewAddressID"]) )
                {
                        if ( !isset($_GET["defaultBillingAddressID"]) )
                                RedirectProtected( "index.php?order2_shipping=yes".
                                                        "&shippingAddressID=".$_GET["selectedNewAddressID"] );
                        else
                                RedirectProtected( "index.php?order2_shipping=yes".
                                                        "&shippingAddressID=".$_GET["selectedNewAddressID"].
                                                        "&defaultBillingAddressID=".$_GET["defaultBillingAddressID"] );
                }

                $shippingAddressID  = $_GET["shippingAddressID"];
                $order              = _getOrder();

                $strAddress = regGetAddressStr( $shippingAddressID );

                $moduleFiles = GetFilesInDirectory( "core/modules/shipping", "php" );
                foreach( $moduleFiles as $fileName ) include( $fileName );

                $shipping_methods = shGetAllShippingMethods( true );
                $shipping_costs   = array();
                $res              = cartGetCartContent();

                $sh_address = regGetAddress( $shippingAddressID );
                $addresses = array( $sh_address, $sh_address );

                $j = 0;
                foreach( $shipping_methods as $key => $shipping_method )
                {

                        $_ShippingModule = modGetModuleObj($shipping_method["module_id"], SHIPPING_RATE_MODULE);
                        if($_ShippingModule){

                                if ( $_ShippingModule->allow_shipping_to_address( regGetAddress($shippingAddressID) ) )
                                {
                                        $shipping_costs[$j] = oaGetShippingCostTakingIntoTax( $res, $shipping_method["SID"], $addresses, $order );
                                }
                                else
                                {

                                        $shipping_costs[$j] = array(array('rate'=>-1));
                                }
                        }else //rate = freight charge
                        {
                                $shipping_costs[$j] = oaGetShippingCostTakingIntoTax( $res, $shipping_method["SID"], $addresses, $order );
                        }
                        $j++;
                }

                $_i = count($shipping_costs)-1;
                for ( ; $_i>=0; $_i-- ){

                        $_t = count($shipping_costs[$_i])-1;
                        for ( ; $_t>=0; $_t-- ){

                                if($shipping_costs[$_i][$_t]['rate']>0){
                                        $shipping_costs[$_i][$_t]['rate'] = show_price($shipping_costs[$_i][$_t]['rate']);
                                }else {

                                        if(count($shipping_costs[$_i]) == 1 && $shipping_costs[$_i][$_t]['rate']<0){

                                                $shipping_costs[$_i] = 'n/a';
                                        }else{

                                                $shipping_costs[$_i][$_t]['rate'] = '';
                                        }
                                }
                        }
                }
				
                $result_methods = array();
				$result_costs = array();
                foreach( $shipping_methods as $key => $shipping_method ){
                if ($shipping_costs[$key]!='n/a'){
				$result_methods[] = $shipping_method;
				$result_costs[] = $shipping_costs[$key];
				}
				}
                $shipping_methods = $result_methods;
                $shipping_costs = $result_costs;
				
                if ( isset($_POST["continue_button"]) )
                {
                        $_POST['shServiceID'] = isset($_POST['shServiceID'][$_POST['select_shipping_method']]) ? $_POST['shServiceID'][$_POST['select_shipping_method']]:0;
                        if ( !isset($_GET["defaultBillingAddressID"]) )
                                RedirectProtected( "index.php?order3_billing=yes&".
                                                        "shippingAddressID=".$_GET["shippingAddressID"]."&".
                                                        "shippingMethodID=".$_POST["select_shipping_method"]."&".
                                                        "billingAddressID=".regGetDefaultAddressIDByLogin($_SESSION["log"]).
                                                        "&shServiceID=".$_POST['shServiceID']
                                                         );
                        else
                                RedirectProtected( "index.php?order3_billing=yes&".
                                                        "shippingAddressID=".$_GET["shippingAddressID"]."&".
                                                        "shippingMethodID=".$_POST["select_shipping_method"]."&".
                                                        "billingAddressID=".$_GET["defaultBillingAddressID"].
                                                        "&shServiceID=".$_POST['shServiceID']
                                                        );
                }

                if ( count($shipping_methods) == 0 )
                                RedirectProtected( "index.php?order3_billing=yes&".
                                                        "shippingAddressID=".regGetDefaultAddressIDByLogin($_SESSION["log"])."&".
                                                        "shippingMethodID=0&".
                                                        "billingAddressID=".regGetDefaultAddressIDByLogin($_SESSION["log"]) );


                if ( isset($_GET["defaultBillingAddressID"]) )
                $smarty->assign( "defaultBillingAddressID", $_GET["defaultBillingAddressID"] );
                $smarty->assign( "shippingAddressID",     $_GET["shippingAddressID"] );
                $smarty->assign( "strAddress",                           $strAddress );
                $smarty->assign( "shipping_costs",                   $shipping_costs );
                $smarty->assign( "shipping_methods",               $shipping_methods );
                $smarty->assign( "shipping_methods_count",  count($shipping_methods) );
                $smarty->assign( "main_content_template", "order2_shipping.tpl.html" );
        }
?><?php
#####################################
# ShopCMS: Скрипт интернет-магазина
# Copyright (c) by ADGroup
# http://shopcms.ru
#####################################


if ( isset($order2_shipping_quick) )
{
        if(!cartCheckMinTotalOrderAmount())
                Redirect('index.php?shopping_cart=yes&min_order=error');

        if ( !cartCheckMinOrderAmount() )
                Redirect( "index.php?shopping_cart=yes" );

        $moduleFiles = GetFilesInDirectory( "core/modules/shipping", "php" );
        foreach( $moduleFiles as $fileName ) include( $fileName );

        function _getOrder()
        {
                if (!isset($_SESSION["first_name"]) || !isset($_SESSION["last_name"]) || !isset($_SESSION["email"])) return NULL;

                $order["first_name"] = $_SESSION["first_name"];
                $order["last_name"]  = $_SESSION["last_name"];
                $order["email"]      = $_SESSION["email"];

                $res = cartGetCartContent();
                $order["orderContent"] = $res["cart_content"];

                $d = oaGetDiscountPercent( $res, "" );
                $order["order_amount"] = $res["total_price"] - ($res["total_price"]/100)*$d;

                return $order;
        }


        function _getShippingCosts( $shipping_methods, $order, $moduleFiles )
        {
                if (!isset($_SESSION["receiver_countryID"]) || !isset($_SESSION["receiver_zoneID"]))
                        return NULL;

                $shipping_modules        = modGetModules( $moduleFiles );
                $shippingAddressID = 0;
                $shipping_costs = array();

                $res = cartGetCartContent();

                $sh_address = array(
                        "countryID" => $_SESSION["receiver_countryID"],
                        "zoneID" => $_SESSION["receiver_zoneID"]
                );
                $addresses = array( $sh_address, $sh_address );

                $j = 0;
                foreach( $shipping_methods as $shipping_method )
                {
                        $_ShippingModule = modGetModuleObj($shipping_method["module_id"], SHIPPING_RATE_MODULE);
                        if($_ShippingModule){

                                if ( $_ShippingModule->allow_shipping_to_address( $sh_address ) )
                                {
                                        $shipping_costs[$j] = oaGetShippingCostTakingIntoTax( $res, $shipping_method["SID"], $addresses, $order );
                                }
                                else
                                {

                                        $shipping_costs[$j] = array(array('rate'=>-1));
                                }
                        }else //rate = freight charge
                        {
                                $shipping_costs[$j] = oaGetShippingCostTakingIntoTax( $res, $shipping_method["SID"], $addresses, $order );
                        }
                        $j++;
                }

                $_i = count($shipping_costs)-1;
                for ( ; $_i>=0; $_i-- ){

                        $_t = count($shipping_costs[$_i])-1;
                        for ( ; $_t>=0; $_t-- ){

                                if($shipping_costs[$_i][$_t]['rate']>0){
                                        $shipping_costs[$_i][$_t]['rate'] = show_price($shipping_costs[$_i][$_t]['rate']);
                                }else {

                                        if(count($shipping_costs[$_i]) == 1 && $shipping_costs[$_i][$_t]['rate']<0){

                                                $shipping_costs[$_i] = 'n/a';
                                        }else{

                                                $shipping_costs[$_i][$_t]['rate'] = '';
                                        }
                                }
                        }
                }

                return $shipping_costs;
        }

        $order            = _getOrder();
        $strAddress       = quickOrderGetReceiverAddressStr(); 
        $shipping_methods = shGetAllShippingMethods( true );

        if ( isset($_POST["continue_button"]) ){

                $_POST['shServiceID'] = isset($_POST['shServiceID'][$_POST['select_shipping_method']]) ? $_POST['shServiceID'][$_POST['select_shipping_method']]:0;
                RedirectProtected( "index.php?order3_billing_quick=yes&shippingMethodID=".
                                $_POST["select_shipping_method"].
                                "&shServiceID=".$_POST['shServiceID']
                                );
        }

        if ( count($shipping_methods) == 0 )
                RedirectProtected( "index.php?order3_billing_quick=yes&shippingMethodID=0" );

        $shipping_costs = _getShippingCosts( $shipping_methods, $order, $moduleFiles );
               
                $result_methods = array();
				$result_costs = array();
                foreach( $shipping_methods as $key => $shipping_method ){
                if ($shipping_costs[$key]!='n/a'){
				$result_methods[] = $shipping_method;
				$result_costs[] = $shipping_costs[$key];
				}
				}
                $shipping_methods = $result_methods;
                $shipping_costs = $result_costs;
				
        $smarty->assign( "strAddress",                        $strAddress );
        $smarty->assign( "shipping_costs",                $shipping_costs );
        $smarty->assign( "shipping_methods",        $shipping_methods );
        $smarty->assign( "shipping_methods_count",  count($shipping_methods) );
        $smarty->assign( "main_content_template", "order2_shipping_quick.tpl.html" );
}

?><?php
#####################################
# ShopCMS: Скрипт интернет-магазина
# Copyright (c) by ADGroup
# http://shopcms.ru
#####################################

        if ( isset($order3_billing) )
        {

                if(!cartCheckMinTotalOrderAmount()) Redirect('index.php?shopping_cart=yes&min_order=error');

                if (  !isset($_GET["order3_billing"])        ||
                          !isset($_GET["shippingAddressID"]) ||
                          !isset($_GET["shippingMethodID"])  ||
                          !isset($_GET["billingAddressID"])  )

                Redirect( "index.php?page_not_found=yes" );


                $_GET["shippingAddressID"] = (int)$_GET["shippingAddressID"];
                $_GET["billingAddressID"]  = (int)$_GET["billingAddressID"];
                $_GET["shippingMethodID"]  = (int)$_GET["shippingMethodID"];

                if ( $_GET["shippingAddressID"]!=0 && !regAddressBelongToCustomer(regGetIdByLogin($_SESSION["log"]), $_GET["shippingAddressID"]) ){
                        Redirect( "index.php?page_not_found=yes" );
                }
                if ( $_GET["billingAddressID"]!=0 && !regAddressBelongToCustomer(regGetIdByLogin($_SESSION["log"]), $_GET["billingAddressID"]) ){
                        Redirect( "index.php?page_not_found=yes" );
                }
                if ( $_GET["shippingMethodID"] != 0 ){
                        if ( !shShippingMethodIsExist($_GET["shippingMethodID"]) ){
                                Redirect( "index.php?page_not_found=yes" );
                        }
                }


                if ( !cartCheckMinOrderAmount() ) Redirect( "index.php?shopping_cart=yes" );

                if ( isset($_POST["continue_button"]) )
                {
                        RedirectProtected("index.php?order4_confirmation=yes&".
                                "shippingAddressID=".$_GET["shippingAddressID"]."&".
                                "shippingMethodID=".$_GET["shippingMethodID"]."&".
                                "billingAddressID=".$_GET["billingAddressID"]."&".
                                "paymentMethodID=".$_POST["select_payment_method"].
                                (isset($_GET['shServiceID'])?"&shServiceID=".$_GET['shServiceID']:'') );
                }

                if ( isset($_GET["selectedNewAddressID"]) )
                {
                        RedirectProtected("index.php?order3_billing=yes&".
                                "shippingAddressID=".$_GET["shippingAddressID"]."&".
                                "shippingMethodID=".$_GET["shippingMethodID"]."&".
                                "billingAddressID=".$_GET["selectedNewAddressID"].
                                (isset($_GET['shServiceID'])?"&shServiceID=".$_GET['shServiceID']:'') );
                }

                $moduleFiles = GetFilesInDirectory( "core/modules/payment", "php" );
                foreach( $moduleFiles as $fileName ) include( $fileName );

                $payment_methods = payGetAllPaymentMethods(true);
                $payment_methodsToShow = array();
                foreach( $payment_methods as $payment_method )
                {
                        if ($_GET["shippingMethodID"] == 0) //no shipping methods available => show all available payment types
                        {
                                $shippingMethodsToAllow = true;
                        }
                        else // list of payment options depends on selected shipping method
                        {
                                $shippingMethodsToAllow = false;
                                foreach( $payment_method["ShippingMethodsToAllow"] as $ShippingMethod )
                                        if ( ((int)$_GET["shippingMethodID"] == (int)$ShippingMethod["SID"]) &&
                                                                         $ShippingMethod["allow"] )
                                        {
                                                $shippingMethodsToAllow = true;
                                                break;
                                        }
                        }

                        if ( $shippingMethodsToAllow ) $payment_methodsToShow[] = $payment_method;
                }

                if ( count($payment_methodsToShow) == 0 )
                        RedirectProtected( "index.php?order4_confirmation=yes&".
                                                "shippingAddressID=".$_GET["shippingAddressID"]."&".
                                                "shippingMethodID=".$_GET["shippingMethodID"]."&".
                                                "billingAddressID=".regGetDefaultAddressIDByLogin($_SESSION["log"])."&".
                                                "paymentMethodID=0".
                                (isset($_GET['shServiceID'])?"&shServiceID=".$_GET['shServiceID']:'') );

                $smarty->assign( "shippingAddressID",        $_GET["shippingAddressID"] );
                $smarty->assign( "billingAddressID",        $_GET["billingAddressID"] );
                $smarty->assign( "shippingMethodID",        $_GET["shippingMethodID"] );
                $smarty->assign( "strAddress", regGetAddressStr($_GET["billingAddressID"]) );
                $smarty->assign( "payment_methods", $payment_methodsToShow );
                $smarty->assign( "payment_methods_count",  count($payment_methodsToShow) );
                $smarty->assign( "main_content_template", "order3_billing.tpl.html" );
        }
?><?php
#####################################
# ShopCMS: Скрипт интернет-магазина
# Copyright (c) by ADGroup
# http://shopcms.ru
#####################################

if (  isset($order3_billing_quick) )
{
        if(!cartCheckMinTotalOrderAmount())
        Redirect('index.php?shopping_cart=yes&min_order=error');

        if ( CONF_ORDERING_REQUEST_BILLING_ADDRESS == '0' )
        {
                $_SESSION["billing_first_name"]        = $_SESSION["receiver_first_name"];
                $_SESSION["billing_last_name"]        = $_SESSION["receiver_last_name"];
                $_SESSION["billing_state"]                = $_SESSION["receiver_state"];
                $_SESSION["billing_city"]                = $_SESSION["receiver_city"];
                $_SESSION["billing_address"]        = $_SESSION["receiver_address"];
                if ( isset($_SESSION["receiver_countryID"]) )
                        $_SESSION["billing_countryID"] = $_SESSION["receiver_countryID"];
                if ( isset($_SESSION["receiver_zoneID"]) )
                        $_SESSION["billing_zoneID"] = $_SESSION["receiver_zoneID"];
        }


        if ( !isset($_GET["shippingMethodID"]) )  Redirect( "index.php?page_not_found=yes" );

        $_GET["shippingMethodID"] = (int)$_GET["shippingMethodID"];

        //if ( !shShippingMethodIsExist($_GET["shippingMethodID"]) )
        //        Redirect( "index.php?page_not_found=yes" );

        if ( !cartCheckMinOrderAmount() ) Redirect( "index.php?shopping_cart=yes" );

        $moduleFiles = GetFilesInDirectory( "core/modules/payment", "php" );
        foreach( $moduleFiles as $fileName )  include( $fileName );


        function _getPaymentMethodsToShow( $payment_methods )
        {
                $payment_methodsToShow = array();
                foreach( $payment_methods as $payment_method )
                {
                        if ($_GET["shippingMethodID"] == 0) //no shipping methods available => show all available payment types
                        {
                                $shippingMethodsToAllow = true;
                        }
                        else
                        {
                                $shippingMethodsToAllow = false;
                                foreach( $payment_method["ShippingMethodsToAllow"] as $ShippingMethod )
                                        if ( ((int)$_GET["shippingMethodID"] == (int)$ShippingMethod["SID"]) &&
                                                         $ShippingMethod["allow"] )
                                        {
                                                $shippingMethodsToAllow = true;
                                                break;
                                        }
                        }

                        if ( $shippingMethodsToAllow )
                                $payment_methodsToShow[] = $payment_method;
                }
                return $payment_methodsToShow;
        }


        if ( isset($_POST["continue_button"]) )
                RedirectProtected(         "index.php?order4_confirmation_quick=yes&shippingMethodID=".$_GET["shippingMethodID"]."&".
                                                "paymentMethodID=".$_POST["select_payment_method"].
                                                (isset($_GET['shServiceID'])?"&shServiceID=".$_GET['shServiceID']:'')
                                                 );



        $payment_methods = payGetAllPaymentMethods(true);
        $payment_methodsToShow = _getPaymentMethodsToShow( $payment_methods );

        if ( count($payment_methodsToShow) == 0 )
                RedirectProtected( "index.php?order4_confirmation_quick=yes&shippingMethodID=".$_GET["shippingMethodID"]."&".
                                                "paymentMethodID=0" .
                                (isset($_GET['shServiceID'])?"&shServiceID=".$_GET['shServiceID']:'') );

        $strAddress = quickOrderGetBillingAddressStr(); //TransformDataBaseStringToText( quickOrderGetBillingAddressStr() );
        $smarty->assign( "strAddress",        $strAddress );
        $smarty->assign( "payment_methods", $payment_methodsToShow );
        $smarty->assign( "payment_methods_count",  count($payment_methodsToShow) );
        $smarty->assign( "main_content_template", "order3_billing_quick.tpl.html" );
}

?><?php
#####################################
# ShopCMS: Скрипт интернет-магазина
# Copyright (c) by ADGroup
# http://shopcms.ru
#####################################

// *****************************************************************************
// Purpose                        order confirmation page
// Call condition
//                                        index.php?order2=yes&shippingAddressID=<address ID>&shippingMethodID=<shipping ID>&
//                                                billingAddressID=<address ID>&paymentMethodID=<payment method ID>
// Include PHP                index.php -> [order2.php]
// Uses TPL                        order2.tpl
// Remarks

if ( isset ( $order4_confirmation )) {
    
	if ( !cartCheckMinTotalOrderAmount() && !isset ( $_GET["order_success"] ))  Redirect('index.php?shopping_cart=yes&min_order=error');
    
	$shServiceID = isset ( $_GET['shServiceID'] ) ? $_GET['shServiceID'] : 0;
    
	if ( !isset ( $_POST["submitgo"] ) && !isset ( $_GET["order_success"] )) {
        
		if ( !isset ( $_GET["order4_confirmation"] ) || !isset ( $_GET["shippingAddressID"] ) || !isset ( $_GET["billingAddressID"] ) 
		|| !isset ( $_GET["shippingMethodID"] ) || !isset ( $_GET["paymentMethodID"] )) Redirect("index.php?page_not_found=yes");
        
		$_GET["shippingAddressID"] = ( int ) $_GET["shippingAddressID"];
        $_GET["billingAddressID"] = ( int ) $_GET["billingAddressID"];
        $_GET["shippingMethodID"] = ( int ) $_GET["shippingMethodID"];
        $_GET["paymentMethodID"] = ( int ) $_GET["paymentMethodID"];
        
		if ( $_GET["shippingAddressID"] && !regAddressBelongToCustomer(regGetIdByLogin($_SESSION["log"]), $_GET["shippingAddressID"])) Redirect("index.php?page_not_found=yes");        
		if ( CONF_ORDERING_REQUEST_BILLING_ADDRESS == 0 && $_GET["billingAddressID"] == 0 ) $_GET["billingAddressID"] = $_GET["shippingAddressID"];
        if ( $_GET["billingAddressID"] && !regAddressBelongToCustomer(regGetIdByLogin($_SESSION["log"]), $_GET["billingAddressID"])) Redirect("index.php?page_not_found=yes");
        
		if ( $_GET["shippingMethodID"] != 0 ) {
            if ( !shShippingMethodIsExist($_GET["shippingMethodID"])) {
                Redirect("index.php?page_not_found=yes");
            }
        }
        
		if ( $_GET["paymentMethodID"] != 0 )
            if ( !payPaymentMethodIsExist($_GET["paymentMethodID"]))
                Redirect("index.php?page_not_found=yes");
    }
    if ( !cartCheckMinOrderAmount()) Redirect("index.php?shopping_cart=yes");
    
	$shippingModuleFiles = GetFilesInDirectory("core/modules/shipping", "php");
    
	foreach ( $shippingModuleFiles as $fileName ) include ( $fileName );
    
	$paymentModuleFiles = GetFilesInDirectory("core/modules/payment", "php");
    
	foreach ( $paymentModuleFiles as $fileName ) include ( $fileName );
    
	if ( isset ( $_POST["submitgo"] )) {
        $cc_number = "";
        $cc_holdername = "";
        $cc_expires = "";
        $cc_cvv = "";
        
		if ( CONF_ORDERING_REQUEST_BILLING_ADDRESS == 0 && $_GET["billingAddressID"] == 0 ) $_GET["billingAddressID"] = $_GET["shippingAddressID"];
        
		if ( CONF_CHECKSTOCK ) {
            $cartContent = cartGetCartContent();
            $rediractflag = false;
            foreach ( $cartContent["cart_content"] as $cartItem ) {
                // if conventional ordering
                if ( isset ( $_SESSION["log"] )) {
                    $productID = GetProductIdByItemId($cartItem["id"]);
                    $q = db_query("select name, in_stock FROM ".PRODUCTS_TABLE." WHERE productID=".( int ) $productID);
                    $left = db_fetch_row($q);
                    if ( $left["in_stock"] < 1 ) {
                        $rediractflag = true;
                        db_query("DELETE FROM ".SHOPPING_CARTS_TABLE." WHERE customerID=".regGetIdByLogin($_SESSION["log"])." AND itemID=".( int ) $cartItem["id"]);
                        db_query("DELETE FROM ".SHOPPING_CART_ITEMS_TABLE." where itemID=".( int ) $cartItem["id"]);
                        db_query("DELETE FROM ".SHOPPING_CART_ITEMS_CONTENT_TABLE." where itemID=".( int ) $cartItem["id"]);
                        db_query("DELETE FROM ".ORDERED_CARTS_TABLE." where itemID=".( int ) $cartItem["id"]);
                    }
                }
                else
                // if quick ordering
                    {
                    $productID = $cartItem["id"];
                    $q = db_query("select name, in_stock FROM ".PRODUCTS_TABLE." WHERE productID=".( int ) $productID);
                    $left = db_fetch_row($q);
                    if ( $left["in_stock"] < 1 ) {
                        $rediractflag = true;
                        $res = DeCodeItemInClient($productID);
                        $i = SearchConfigurationInSessionVariable($res["variants"], $res["productID"]);
                        if ( $i != - 1 )
                            $_SESSION["gids"][$i] = 0;
                    }
                }
            }
            if ( $rediractflag ) Redirect("index.php?product_removed=yes");
        }
        
		$orderID = ordOrderProcessing($_GET["shippingMethodID"], $_GET["paymentMethodID"], $_GET["shippingAddressID"], 
		$_GET["billingAddressID"], $shippingModuleFiles, $paymentModuleFiles, $_POST["order_comment"], 
		$cc_number, $cc_holdername, $cc_expires, $cc_cvv, $_SESSION["log"], $smarty_mail, $shServiceID);
        
		$_SESSION["newoid"] = $orderID;
        
		if ( is_bool($orderID))
            RedirectProtected("index.php?order4_confirmation=yes"."&shippingAddressID=".$_GET["shippingAddressID"]."&shippingMethodID=".$_GET["shippingMethodID"].
			"&billingAddressID=".$_GET["billingAddressID"]."&paymentMethodID=".$_GET["paymentMethodID"]."&payment_error=1");
        else
            RedirectProtected("index.php?order4_confirmation=yes"."&order_success=yes&paymentMethodID=".$_GET["paymentMethodID"]."&orderID=".$orderID);
    }
    
	if ( isset ( $_GET["order_success"] )) {
        if ( isset ( $_GET["orderID"] ) && isset ( $_SESSION["newoid"] ) && ( int ) $_SESSION["newoid"] == ( int ) $_GET["orderID"] ) {
            $paymentMethod = payGetPaymentMethodById($_GET["paymentMethodID"]);
            $currentPaymentModule = modGetModuleObj($paymentMethod["module_id"], PAYMENT_MODULE);
            if ( $currentPaymentModule != null )
                $after_processing_html = $currentPaymentModule->after_processing_html($_GET["orderID"]);
            else
                $after_processing_html = "";
            $smarty->assign("after_processing_html", $after_processing_html);
        }
        $smarty->assign("order_success", 1);
    }
    else {
        if ( isset ( $_GET["payment_error"] )) {
            if ( $_GET["payment_error"] == 1 )
                $smarty->assign("payment_error", 1);
            else
                $smarty->assign("payment_error", base64_decode(str_replace(" ", "+", $_GET["payment_error"])));
        }
        elseif ( xDataExists('PaymentError')) {
            $smarty->assign("payment_error", xPopData('PaymentError'));
        }
        $orderSum = getOrderSummarize($_GET["shippingMethodID"], $_GET["paymentMethodID"], $_GET["shippingAddressID"], $_GET["billingAddressID"], $shippingModuleFiles, $paymentModuleFiles, $shServiceID);
        $smarty->assign("orderSum", $orderSum);
        $smarty->assign("totalUC", $orderSum["totalUC"]);
    }
    if ( isset ( $_GET["orderID"] )) {
        $smarty->assign("orderidd", ( int ) $_GET["orderID"]);
    }
    $smarty->assign("main_content_template", "order4_confirmation.tpl.html");
}
?><?php
#####################################
# ShopCMS: Скрипт интернет-магазина
# Copyright (c) by ADGroup
# http://shopcms.ru
#####################################

if ( isset ( $order4_confirmation_quick )) {
    if ( !cartCheckMinTotalOrderAmount() && !isset ( $_GET["order_success"] )) Redirect('index.php?shopping_cart=yes&min_order=error');
    
	$shServiceID = isset ( $_GET['shServiceID'] ) ? $_GET['shServiceID'] : 0;
    
	if ( !isset ( $_POST["submitgo"] ) && !isset ( $_GET["order_success"] )) {
        if ( !isset ( $_GET["shippingMethodID"] )) Redirect("index.php?page_not_found=yes");
        $_GET["shippingMethodID"] = ( int ) $_GET["shippingMethodID"];
        if ( !isset ( $_GET["paymentMethodID"] )) Redirect("index.php?page_not_found=yes");
        $_GET["paymentMethodID"] = ( int ) $_GET["paymentMethodID"];
        if ( $_GET["shippingMethodID"] != 0 )
            if ( !shShippingMethodIsExist($_GET["shippingMethodID"])) Redirect("index.php?page_not_found=yes");
        if ( $_GET["paymentMethodID"] != 0 )
            if ( !payPaymentMethodIsExist($_GET["paymentMethodID"])) Redirect("index.php?page_not_found=yes");
    }
    
	if ( !cartCheckMinOrderAmount()) Redirect("index.php?shopping_cart=yes");
    
	$shippingModuleFiles = GetFilesInDirectory("core/modules/shipping", "php");
    
	foreach ( $shippingModuleFiles as $fileName ) include ( $fileName );
    
	$paymentModuleFiles = GetFilesInDirectory("core/modules/payment", "php");
    
	foreach ( $paymentModuleFiles as $fileName ) include ( $fileName );
    
	if ( isset ( $_POST["submitgo"] )) {
        $cc_number = "";
        $cc_holdername = "";
        $cc_expires = "";
        $cc_cvv = "";
        
		if ( CONF_CHECKSTOCK ) {
            $cartContent = cartGetCartContent();
            $rediractflag = false;
            foreach ( $cartContent["cart_content"] as $cartItem ) {
                // if conventional ordering
                if ( isset ( $_SESSION["log"] )) {
                    $productID = GetProductIdByItemId($cartItem["id"]);
                    $q = db_query("select name, in_stock FROM ".PRODUCTS_TABLE." WHERE productID=".( int ) $productID);
                    $left = db_fetch_row($q);
                    if ( $left["in_stock"] < 1 ) {
                        $rediractflag = true;
                        db_query("DELETE FROM ".SHOPPING_CARTS_TABLE." WHERE customerID=".regGetIdByLogin($_SESSION["log"])." AND itemID=".( int ) $cartItem["id"]);
                        db_query("DELETE FROM ".SHOPPING_CART_ITEMS_TABLE." where itemID=".( int ) $cartItem["id"]);
                        db_query("DELETE FROM ".SHOPPING_CART_ITEMS_CONTENT_TABLE." where itemID=".( int ) $cartItem["id"]);
                        db_query("DELETE FROM ".ORDERED_CARTS_TABLE." where itemID=".( int ) $cartItem["id"]);
                    }
                }
                else
                // if quick ordering
                    {
                    $productID = $cartItem["id"];
                    $q = db_query("select name, in_stock FROM ".PRODUCTS_TABLE." WHERE productID=".( int ) $productID);
                    $left = db_fetch_row($q);
                    if ( $left["in_stock"] < 1 ) {
                        $rediractflag = true;
                        $res = DeCodeItemInClient($productID);
                        $i = SearchConfigurationInSessionVariable($res["variants"], $res["productID"]);
                        if ( $i != - 1 )
                            $_SESSION["gids"][$i] = 0;
                    }
                }
            }
            if ( $rediractflag ) Redirect("index.php?product_removed=yes");
        }
        
		$orderID = ordOrderProcessing($_GET["shippingMethodID"], $_GET["paymentMethodID"], 0, 0, $shippingModuleFiles, $paymentModuleFiles, $_POST["order_comment"], 
		$cc_number, $cc_holdername, $cc_expires, $cc_cvv, null, $smarty_mail, $shServiceID);
        
		$_SESSION["newoid"] = $orderID;
        
		if ( is_bool($orderID))
            RedirectProtected("index.php?order4_confirmation_quick=yes&"."&shippingMethodID=".$_GET["shippingMethodID"]."&paymentMethodID=".$_GET["paymentMethodID"]."&payment_error=1");
        else
            RedirectProtected("index.php?order4_confirmation_quick=yes&"."order_success=yes&paymentMethodID=".$_GET["paymentMethodID"]."&orderID=".$orderID);
    }
    
	if ( isset ( $_GET["order_success"] )) {
        if ( isset ( $_GET["orderID"] ) && isset ( $_SESSION["newoid"] ) && ( int ) $_SESSION["newoid"] == ( int ) $_GET["orderID"] ) {
            $paymentMethod = payGetPaymentMethodById($_GET["paymentMethodID"]);
            $currentPaymentModule = modGetModuleObj($paymentMethod["module_id"], PAYMENT_MODULE);
            if ( $currentPaymentModule != null )
                $after_processing_html = $currentPaymentModule->after_processing_html($_GET["orderID"]);
            else
                $after_processing_html = "";
            $smarty->assign("after_processing_html", $after_processing_html);
        }
        $smarty->assign("order_success", 1);
    }
    else {
        if ( isset ( $_GET["payment_error"] )) {
            if ( $_GET["payment_error"] == 1 )
                $smarty->assign("payment_error", 1);
            else
                $smarty->assign("payment_error", base64_decode(str_replace(" ", "+", $_GET["payment_error"])));
        }
        elseif ( xDataExists('PaymentError')) {
            $smarty->assign("payment_error", xPopData('PaymentError'));
        }
        $orderSum = getOrderSummarize($_GET["shippingMethodID"], $_GET["paymentMethodID"], 0, 0, $shippingModuleFiles, $paymentModuleFiles, $shServiceID);
        $smarty->assign("orderSum", $orderSum);
        $smarty->assign("totalUC", $orderSum["totalUC"]);
    }
    if ( isset ( $_GET["orderID"] )) {
        $smarty->assign("orderidd", ( int ) $_GET["orderID"]);
    }
    $smarty->assign("main_content_template", "order4_confirmation_quick.tpl.html");
}
?><?php
#####################################
# ShopCMS: Скрипт интернет-магазина
# Copyright (c) by ADGroup
# http://shopcms.ru
#####################################

    if ( isset($order_history) && isset($_SESSION["log"]) )
        {

                function _setCallBackParamsToSearchOrders( &$callBackParam )
                {
                        $callBackParam = array( "customerID" => regGetIdByLogin($_SESSION["log"]) );
                        if ( isset($_GET["sort"]) )
                        {
                                $callBackParam["sort"] = xEscSQL($_GET["sort"]);
                                if ( isset($_GET["direction"]) )
                                        $callBackParam["direction"] = xEscSQL($_GET["direction"]);
                        }
                        else
                        {
                                $callBackParam["sort"] = "order_time";
                                $callBackParam["direction"] = "DESC";
                        }

                        if ( $_GET["order_search_type"] == "SearchByOrderID" )
                                $callBackParam["orderID"] = (int)$_GET["orderID_textbox"];
                        else if ( $_GET["order_search_type"] == "SearchByStatusID" )
                        {
                                $orderStatuses = array();
                                $data = ScanGetVariableWithId( array("checkbox_order_status") );
                                foreach( $data as $key => $val )
                                        if ( $val["checkbox_order_status"] == "1" )
                                                $orderStatuses[] = (int)$key;
                                $callBackParam["orderStatuses"] = $orderStatuses;
                        }
                }

                function _getReturnUrl()
                {
                        $url = "index.php?order_history=yes";
                        if ( isset($_GET["order_search_type"]) )
                                $url .= "&order_search_type=".$_GET["order_search_type"];
                        if ( isset($_GET["orderID_textbox"]) )
                                $url .= "&orderID_textbox=".$_GET["orderID_textbox"];
                        $data = ScanGetVariableWithId( array("checkbox_order_status") );
                        foreach( $data as $key => $val )
                                $url .= "&checkbox_order_status_".$key."=".$val["checkbox_order_status"];
                        if ( isset($_GET["offset"]) )
                                $url .= "&offset=".$_GET["offset"];
                        if ( isset($_GET["show_all"]) )
                                $url .= "&show_all=yes";
                        $data = ScanGetVariableWithId( array("set_order_status") );
                        $changeStatusIsPressed = (count($data)!=0);
                        if ( isset($_GET["search"]) || $changeStatusIsPressed )
                                $url .= "&search=1";
                        if ( isset($_GET["sort"]) )
                                $url .= "&sort=".$_GET["sort"];
                        if ( isset($_GET["direction"]) )
                                $url .= "&direction=".$_GET["direction"];
                        return base64_encode( $url );
                }

                function _copyDataFromGetToPage( &$smarty, &$order_statuses )
                {
                        if ( isset($_GET["order_search_type"])  )
                                $smarty->assign( "order_search_type", $_GET["order_search_type"] );
                        if ( isset($_GET["orderID_textbox"]) )
                                $smarty->assign( "orderID", (int)$_GET["orderID_textbox"] );
                        $data = ScanGetVariableWithId( array("checkbox_order_status") );
                        for( $i=0; $i<count($order_statuses); $i++ ) $order_statuses[$i]["selected"] = 0;
                        foreach( $data as $key => $val )
                        {
                                if ( $val["checkbox_order_status"] == "1" )
                                {
                                        for( $i=0; $i<count($order_statuses); $i++ )
                                                if ( (int)$order_statuses[$i]["statusID"] == (int)$key )
                                                        $order_statuses[$i]["selected"] = 1;
                                }
                        }
                }

                function _getUrlToSort()
                {
                        $url = "index.php?order_history=yes";
                        if ( isset($_GET["order_search_type"]) )
                                $url .= "&order_search_type=".$_GET["order_search_type"];
                        if ( isset($_GET["orderID_textbox"]) )
                                $url .= "&orderID_textbox=".$_GET["orderID_textbox"];
                        $data = ScanGetVariableWithId( array("checkbox_order_status") );
                        foreach( $data as $key => $val )
                                $url .= "&checkbox_order_status_".$key."=".$val["checkbox_order_status"];
                        if ( isset($_GET["offset"]) )
                                $url .= "&offset=".$_GET["offset"];
                        if ( isset($_GET["show_all"]) )
                                $url .= "&show_all=yes";

                        if ( isset($_GET["search"]) )
                                $url .= "&search=1";
                        return $url;
                }

                function _getUrlToNavigate()
                {
                        $url = "index.php?order_history=yes";
                        if ( isset($_GET["order_search_type"]) )
                                $url .= "&order_search_type=".$_GET["order_search_type"];
                        if ( isset($_GET["orderID_textbox"]) )
                                $url .= "&orderID_textbox=".$_GET["orderID_textbox"];
                        $data = ScanGetVariableWithId( array("checkbox_order_status") );
                        foreach( $data as $key => $val )
                                $url .= "&checkbox_order_status_".$key."=".$val["checkbox_order_status"];

                        if ( isset($_GET["search"]) )
                                $url .= "&search=1";

                        if ( isset($_GET["sort"]) )
                                $url .= "&sort=".$_GET["sort"];
                        if ( isset($_GET["direction"]) )
                                $url .= "&direction=".$_GET["direction"];
                        return $url;
                }



                $order_statuses = ostGetOrderStatues();
                $smarty->assign( "completed_order_status", ostGetCompletedOrderStatus() );

                if ( isset($_GET["search"]) )
                {
                        $callBackParam = array();
                        _setCallBackParamsToSearchOrders( $callBackParam );
                        _copyDataFromGetToPage( $smarty, $order_statuses );

                        $orders = array();
                        $offset = 0;
                        $count = 0;
                        $navigatorHtml = GetNavigatorHtml( _getUrlToNavigate(), 20,
                                'ordGetOrders', $callBackParam, $orders, $offset, $count );

                        $smarty->assign( "orders_navigator", $navigatorHtml );
                        $smarty->assign( "user_orders", $orders );
                        $smarty->assign( "urlToSort", _getUrlToSort() );
                }else{
                        $callBackParam = array();
                        _setCallBackParamsToSearchOrders( $callBackParam );
                        _copyDataFromGetToPage( $smarty, $order_statuses );

                        $orders = array();
                        $offset = 0;
                        $count = 0;
                        $navigatorHtml = GetNavigatorHtml( _getUrlToNavigate(), 10,
                                'ordGetOrders', $callBackParam, $orders, $offset, $count );

                        $smarty->assign( "orders_navigator", $navigatorHtml );
                        $smarty->assign( "user_orders", $orders );
                        $smarty->assign( "urlToSort", _getUrlToSort() );
                }

                $smarty->assign( "urlToReturn", html_amp(_getReturnUrl()) );
                $smarty->assign( "order_statuses", $order_statuses);
                $smarty->assign( "main_content_template", "order_history.tpl.html" );
        }



        if ( isset($order_detailed))
        {
                $orderID = (int) $order_detailed;

                $smarty->assign( "urlToReturn", html_amp(base64_decode($_GET["urlToReturn"])) );

                $order = ordGetOrder( $orderID );

                if (!$order || ($order["customerID"] != regGetIdByLogin($_SESSION["log"]))) //attempt to view orders of other customers
                {
                        unset($order);
                }
                else
                {
                        $orderContent = ordGetOrderContent( $orderID );
                        $order_status_report = xNl2Br(stGetOrderStatusReport( $orderID ));
                        $order_statuses = ostGetOrderStatues();

                        $smarty->assign( "completed_order_status", ostGetCompletedOrderStatus() );
                        $smarty->assign( "orderContent", $orderContent );
                        $smarty->assign( "order", $order );
                        $smarty->assign( "https_connection_flag", 1 );
                        $smarty->assign( "order_status_report", $order_status_report );
                        $smarty->assign( "order_statuses", $order_statuses );
                        $smarty->assign( "order_detailed", 1 );
                        $smarty->assign( "main_content_template", "order_history.tpl.html");
                }
        }

        if (isset($p_order_detailed) )
        {
                $orderID = (int)$p_order_detailed;
                $order = ordGetOrder( $orderID );

                if (!$order)
                {
                header("HTTP/1.0 404 Not Found");
                header("HTTP/1.1 404 Not Found");
                header("Status: 404 Not Found");
                die(ERROR_404_HTML);
                }


                if ($order["customerID"] != regGetIdByLogin($_SESSION["log"])) //attempt to view orders of other customers
                {
                        unset($order);
                        Redirect( "index.php?register_authorization=yes" );
                }
                else
                {
                        $orderContent = ordGetOrderContent( $orderID );
                        $order_status_report = xNl2Br(stGetOrderStatusReport( $orderID ));
                        $order_statuses = ostGetOrderStatues();

                        $smarty->assign( "completed_order_status", ostGetCompletedOrderStatus() );
                        $smarty->assign( "orderContent", $orderContent );
                        $smarty->assign( "order", $order );
                        $smarty->assign( "https_connection_flag", 1 );
                        $smarty->assign( "order_status_report", $order_status_report );
                        $smarty->assign( "order_statuses", $order_statuses );
                        $smarty->assign( "order_detailed", 1 );
                        $smarty->assign( "main_content_template", "order_history.tpl.html");
                }
        }

?><?php
#####################################
# ShopCMS: Скрипт интернет-магазина
# Copyright (c) by ADGroup
# http://shopcms.ru
#####################################
        // show whole price list

      function show_code_p() 
      {
        global $selected_currency_details;

                if (!isset($selected_currency_details) || !$selected_currency_details) //no currency found
                {
                        return "";
                }

        //is exchange rate negative or 0?
        if ($selected_currency_details[1] == 0) return "";
        return $selected_currency_details[0];
      }

      function pricessCategories($parent,$level)
      {

                //same as processCategories(), except it creates a pricelist of the shop

                $out = array();
                $cnt = 0;

                $q1 = db_query("select categoryID, name from ".CATEGORIES_TABLE.
                        " where parent=".(int)$parent." order by sort_order, name");
                while ($row = db_fetch_row($q1))
                {

                        $r = hexdec(substr('999999', 0, 2));
                        $g = hexdec(substr('999999', 2, 2));
                        $b = hexdec(substr('999999', 4, 2));
                        $m = (float)max($r, max($g,$b));
                        $r = round((190+20*min($level,3))*$r/$m);
                        $g = round((190+20*min($level,3))*$g/$m);
                        $b = round((190+20*min($level,3))*$b/$m);
                        $c = dechex($r).dechex($g).dechex($b); //final color

                        //add category to the output
                        $out[$cnt][0] = $row[0];
                        $out[$cnt][1] = $row[1];
                        $out[$cnt][2] = $level;
                        $out[$cnt][3] = 1;
                        $out[$cnt][4] = 0; //0 is for category, 1 - product
                        $cnt++;

                        if ( !isset($_GET["sort"]) )
                                $order_clause = "order by ".CONF_DEFAULT_SORT_ORDER."";
                        else
                        {
                                //verify $_GET["sort"]
                                if (!(!strcmp($_GET["sort"],"name") || !strcmp($_GET["sort"],"Price") || !strcmp($_GET["sort"],"customers_rating")))
                                        $_GET["sort"] = "name";

                                $order_clause = " order by ".xEscSQL($_GET["sort"]);
                                if ( isset($_GET["direction"]) )
                                {
                                        if ( !strcmp( $_GET["direction"] , "DESC" ) )
                                                $order_clause .= " DESC ";
                                        else
                                                $order_clause .= " ASC ";
                                }
                        }

                        //add products
                        $q = db_query("select productID, name, Price, in_stock, product_code from ".PRODUCTS_TABLE.
                                " where categoryID=".$row[0]." and Price>=0 and enabled=1 ".
                                $order_clause );
                        while ($row1 = db_fetch_row($q))
                        {
                                if ($row1[2] < 0){
                                        $cennik = "n/a";
                                        $row1[2] = "n/a";
                                }else{
                                        $cennik  = show_price($row1[2]);
                                        $row1[2] = show_price($row1[2], 0, false);
                                }

                                $out[$cnt][0] = $row1[0];
                                $out[$cnt][1] = $row1[1];
                                $out[$cnt][2] = $level;
                                $out[$cnt][3] = "FFFFFF";
                                $out[$cnt][4] = 1; //0 is for category, 1 - product
                                $out[$cnt][5] = $cennik;
                                $out[$cnt][6] = $row1[3];
                                $out[$cnt][7] = $row1[4];
                                $out[$cnt][8] = $row1[2];
                                $cnt++;
                        }

                        //process all subcategories
                        $sub_out = pricessCategories($row[0], $level+1);

                        //add $sub_out to the end of $out
                        $c_sub_out = count($sub_out);
                        for ($j=0; $j<$c_sub_out; $j++)
                        {
                                $out[] = $sub_out[$j];
                                $cnt++;
                        }
                 }

                return $out;

        } //pricessCategories

        function _sortPriceListSetting( &$smarty, $urlToSort )
        {
                $sort_string = STRING_PRICELIST_ITEM_SORT;
                $sort_string = str_replace( "{ASC_NAME}",
                        "<a href='".$urlToSort."&amp;sort=name&amp;direction=ASC'>".STRING_ASC."</a>",        $sort_string );
                $sort_string = str_replace( "{DESC_NAME}",
                        "<a href='".$urlToSort."&amp;sort=name&amp;direction=DESC'>".STRING_DESC."</a>",        $sort_string );
                $sort_string = str_replace( "{ASC_PRICE}",
                        "<a href='".$urlToSort."&amp;sort=Price&amp;direction=ASC'>".STRING_ASC."</a>",        $sort_string );
                $sort_string = str_replace( "{DESC_PRICE}",
                        "<a href='".$urlToSort."&amp;sort=Price&amp;direction=DESC'>".STRING_DESC."</a>",        $sort_string );
                $smarty->assign( "string_product_sort", $sort_string );
        }

        if (isset($_GET["show_price"])) //show pricelist
        {
                _sortPriceListSetting( $smarty, "index.php?show_price=yes" );

                $pricelist_elements = pricessCategories(1, 0);
                $smarty->assign("pricelist_elements", $pricelist_elements);
                $smarty->assign("main_content_template", "pricelist.tpl.html");
        }

        if (isset($_GET["download_price"])) //show pricelist
        {
                _sortPriceListSetting( $smarty, "index.php?show_price=yes" );
                $currentcur = show_code_p();
                $ddate = strftime("%Y-%m-%d %H:%M:%S", time()+intval(CONF_TIMEZONE)*3600);
                $pricelist_elements2 = pricessCategories(1, 0);
                $pricelist_elements = '<?xml version="1.0" encoding="'.DEFAULT_CHARSET.'"?>
                                       <?mso-application progid="Excel.Sheet"?>
                                       <Workbook xmlns="urn:schemas-microsoft-com:office:spreadsheet"
                                       xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
                                       xmlns:x="urn:schemas-microsoft-com:office:excel"
                                       xmlns:x2="http://schemas.microsoft.com/office/excel/2003/xml"
                                       xmlns:ss="urn:schemas-microsoft-com:office:spreadsheet"
                                       xmlns:o="urn:schemas-microsoft-com:office:office"
                                       xmlns:html="http://www.w3.org/TR/REC-html40"
                                       xmlns:c="urn:schemas-microsoft-com:office:component:spreadsheet">

                                       <Styles>
                                         <Style ss:ID="Default" ss:Name="Normal">
                                           <Alignment ss:Horizontal="Left" ss:Vertical="Bottom" />
                                           <Borders>
                                             <Border ss:Position="Bottom" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#c0c0c0" />
                                             <Border ss:Position="Left" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#c0c0c0" />
                                             <Border ss:Position="Right" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#c0c0c0" />
                                             <Border ss:Position="Top" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#c0c0c0" />
                                           </Borders>
                                           <Font ss:Size="10" ss:Color="#000000" ss:FontName="Arial" />
                                           <Interior />
                                           <NumberFormat />
                                           <Protection />
                                         </Style>
                                         <Style ss:ID="Hedline1" ss:Name="Hedline1">
                                           <Interior ss:Color="#ccffcc" ss:Pattern="Solid" />
                                           <Font ss:Size="10" ss:Color="#000000" ss:Bold="1" ss:FontName="Arial" />
                                         </Style>
                                         <Style ss:ID="Hedline2" ss:Name="Hedline2">
                                           <Alignment ss:Horizontal="Center" ss:Vertical="Bottom" />
                                           <Font ss:Size="14" ss:Color="#000000" ss:FontName="Arial" />
                                           <Interior ss:Color="#ccffcc" ss:Pattern="Solid" />
                                         </Style>
                                         <Style ss:ID="Tab" ss:Name="Tab">
                                           <Alignment ss:Horizontal="Center" ss:Vertical="Bottom" />
                                           <Font ss:Size="10" ss:Color="#000000" ss:FontName="Arial" />
                                         </Style>
                                      </Styles>
                                      <ss:Worksheet ss:Name="Pricelist"><Table><Column ss:Width="500" />
                                        <Column ss:Width="112" ss:StyleID="Tab" />';
                                        if (CONF_DISPLAY_PRCODE == 1) $pricelist_elements .= '<Column ss:Width="112" ss:StyleID="Tab" />';
                                        $pricelist_elements .= '<Row ss:AutoFitHeight="0" ss:Height="20"><Cell ss:MergeAcross="';
                                        if (CONF_DISPLAY_PRCODE == 1) $pricelist_elements .= '2'; else $pricelist_elements .= '1';
                                        $pricelist_elements .= '" ss:StyleID="Hedline1"><Data ss:Type="String">'.STRING_PRICELIST.' '.CONF_SHOP_NAME.'</Data>
                                        </Cell></Row><Row ss:AutoFitHeight="0" ss:Height="12"><Cell ss:MergeAcross="';
if (CONF_DISPLAY_PRCODE == 1) $pricelist_elements .= '2'; else $pricelist_elements .= '1';
$pricelist_elements .= '" ss:StyleID="Hedline1">
<Data ss:Type="String">'.STRING_PRICE_CREATE.' '.$ddate.'</Data></Cell>
</Row><Row ss:AutoFitHeight="0" ss:Height="20"><Cell ss:StyleID="Hedline2"><Data ss:Type="String">'.STRING_PRICE_PRODUCT_NAME.'</Data>
</Cell><Cell ss:StyleID="Hedline2"><Data ss:Type="String">'.CURRENT_PRICE.'('.$currentcur.')</Data></Cell>';
if (CONF_DISPLAY_PRCODE == 1) $pricelist_elements .= '<Cell ss:StyleID="Hedline2"><Data ss:Type="String">'.STRING_PRODUCT_CODE.'</Data></Cell>';
$pricelist_elements .= '</Row>';

                for ($j=0; $j<count($pricelist_elements2); $j++)
                        {


                          $pricelist_elements .= '<Row ss:AutoFitHeight="0" ss:Height="12">';
                          if($pricelist_elements2[$j][4] != 1) {
                          $pricelist_elements .= '<Cell ss:StyleID="Hedline1"';
                          $pricelist_elements .= '><Data ss:Type="String">';
                          for ($h=0; $h<$pricelist_elements2[$j][2]; $h++)
                          {
                          $pricelist_elements .= "    ";
                          }
                          $pricelist_elements .= $pricelist_elements2[$j][1].'</Data></Cell><Cell ss:StyleID="Hedline1"><Data ss:Type="String"></Data></Cell>';
                          if (CONF_DISPLAY_PRCODE == 1) $pricelist_elements .= '<Cell ss:StyleID="Hedline1"><Data ss:Type="String"></Data></Cell>';
                          }else{
                          $pricelist_elements .= '<Cell><Data ss:Type="String">';
                          for ($h=0; $h<$pricelist_elements2[$j][2]; $h++)
                          {
                          $pricelist_elements .= "    ";
                          }
                          $pricelist_elements .= $pricelist_elements2[$j][1].'</Data></Cell><Cell><Data ss:Type="String">'.$pricelist_elements2[$j][8].'</Data></Cell>';
                          if (CONF_DISPLAY_PRCODE == 1) $pricelist_elements .= '<Cell><Data ss:Type="String">'.$pricelist_elements2[$j][7].'</Data></Cell>';
                          }
                          $pricelist_elements .= '</Row>';

                          }
                          $pricelist_elements .= "</Table><x:WorksheetOptions /></ss:Worksheet></Workbook>";

                 header("Pragma: public");
                 header("Expires: 0");
                 header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
                 header("Cache-Control: public");
                 header("Content-Description: File Transfer");
                 header("Content-Type: application/vnd.ms-excel; charset=".DEFAULT_CHARSET."; format=attachment;");
                 header("Content-Disposition: attachment; filename=price.xml;");
                 print $pricelist_elements;
                 exit();
        }
?><?php
#####################################
# ShopCMS: Скрипт интернет-магазина
# Copyright (c) by ADGroup
# http://shopcms.ru
#####################################

        if ( isset($_POST["cart_x"]) ) //add product to cart
        {
                $variants=array();
                foreach( $_POST as $key => $val )
                {
                        if(strstr($key, "option_select_hidden_"))
                                $variants[]=$val;
                }
                unset( $_SESSION["variants"] );
                $_SESSION["variants"] = $variants;
                Redirect("index.php?shopping_cart=yes&add2cart=".(int)$_GET['productID']."&multyaddcount=".(int)$_POST['multyaddcount'] );
        }


        // product detailed information view

        if (isset($_GET["vote"]) && isset($productID)) //vote for a product
        {
          if (!isset($_SESSION["vote_completed"][ $productID ]) && isset($_GET["mark"]) && strlen($_GET["mark"])>0)
          {
                $mark = (int) $_GET["mark"];

                if ($mark>0 && $mark<=5)
                {
                db_query("UPDATE ".PRODUCTS_TABLE." SET customers_rating=(customers_rating*customer_votes+'".$mark."')/(customer_votes+1), customer_votes=customer_votes+1 WHERE productID=".$productID);
                }
          }
          $_SESSION["vote_completed"][ $productID ] = 1;
        }



        if (isset($_POST["request_information"])) //email inquiry to administrator
        {
                $customer_name   = $_POST["customer_name"];
                $customer_email  = $_POST["customer_email"];
                $message_subject = $_POST["message_subject"]." (".CONF_FULL_SHOP_URL."index.php?productID=".$productID.")";
                $message_text    = $_POST["message_text"];

                //validate input data
                if (trim($customer_email)!="" && trim($customer_name)!="" && trim($message_subject)!="" && trim($message_text)!="" && preg_match("/^[_\.a-z0-9-]{1,20}@(([a-z0-9-]+\.)+(com|net|org|mil|edu|gov|arpa|info|biz|inc|name|[a-z]{2})|[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3})$/is",$customer_email))
                {
                        //send a message to store administrator
                        if(CONF_ENABLE_CONFIRMATION_CODE){
                                 $error_p = 1;
                        if(!$_POST['fConfirmationCode'] || !isset($_SESSION['captcha_keystring']) || $_SESSION['captcha_keystring'] !==  $_POST['fConfirmationCode']) {
                                 $error_p = 7;
                                 $smarty->assign("error",$error_p);
                        }
                        unset($_SESSION['captcha_keystring']);
                        if($error_p == 1){
                        xMailTxtHTML(CONF_GENERAL_EMAIL, $message_subject, $message_text, $customer_email, $customer_name);
                        Redirect("index.php?productID=".$productID."&sent=yes");
                        }
                        }else{
                        xMailTxtHTML(CONF_GENERAL_EMAIL, $message_subject, $message_text, $customer_email, $customer_name);
                        Redirect("index.php?productID=".$productID."&sent=yes");
                        }
                }
                else if (isset($_POST["request_information"])) $smarty->assign("error",1);
        }


        //show product information
        if (isset($productID) && $productID>0 && !isset($_POST["add_topic"]) && !isset($_POST["discuss"]) )
        {
                $product=GetProduct($productID);

                if (  !$product || $product["enabled"] == 0  )
                {

                header("HTTP/1.0 404 Not Found");
                header("HTTP/1.1 404 Not Found");
                header("Status: 404 Not Found");
                die(ERROR_404_HTML);

                }
                else
                {

                        if ( !isset($_GET["vote"]) ) IncrementProductViewedTimes($productID);

                        $dontshowcategory = 1;

                        $smarty->assign("main_content_template", "product_detailed.tpl.html");

                        $a = $product;
                        $a["PriceWithUnit"] = show_price( $a["Price"] );
                        $a["list_priceWithUnit"] = show_price( $a["list_price"] );

                        if ( ((float)$a["shipping_freight"]) > 0 )
                                $a["shipping_freightUC"] = show_price( $a["shipping_freight"] );

                         if ( isset($_GET["picture_id"]) )
                        {
                                $picture = db_query("select filename, thumbnail, enlarged from ".
                                        PRODUCT_PICTURES." where photoID=".(int)$_GET["picture_id"] );
                                $picture_row = db_fetch_row( $picture );
                        }
                        else if ( !is_null($a["default_picture"]) )
                        {
                                $picture = db_query("select filename, thumbnail, enlarged from ".
                                        PRODUCT_PICTURES." where photoID=".(int)$a["default_picture"] );
                                $picture_row = db_fetch_row( $picture );
                        }
                        else
                        {
                                $picture = db_query(
                                        "select filename, thumbnail, enlarged, photoID from ".PRODUCT_PICTURES.
                                                " where productID=".$productID);
                                if ( $picture_row = db_fetch_row( $picture ) )
                                        $a["default_picture"]=$picture_row["photoID"];
                                else
                                        $picture_row=null;
                        }
                        if ( $picture_row )
                        {
                                $a["picture"]        = $picture_row[ 0 ];
                                $a["thumbnail"] = $picture_row[ 1 ];
                                $a["big_picture"]  = $picture_row[ 2 ];
                        }
                        else
                        {
                                $a["picture"]        = "";
                                $a["thumbnail"] = "";
                                $a["big_picture"]  = "";
                        }

                        if ($a) //product found
                        {
                                if (!isset($categoryID)) $categoryID = $a["categoryID"];

                                //get selected category info
                                $q = db_query("select categoryID, name, description, picture, allow_products_comparison FROM ".CATEGORIES_TABLE." WHERE categoryID=".(int)$categoryID);
                                $row = db_fetch_row($q);
                                if ($row)
                                {
                                        if (!file_exists("data/category/".$row[3])) $row[3] = "";
                                        $smarty->assign("selected_category", $row);
                                        $a["allow_products_comparison"] = $row[4];
                                }
                                else{
                                        $smarty->assign("selected_category", NULL);
                                        $a["allow_products_comparison"] = NULL;
                                    }

                                //calculate a path to the category
                                $smarty->assign("product_category_path",  catCalculatePathToCategory( (int)$categoryID ) );

                                //reviews number
                                $q = db_query("select count(*) FROM ".DISCUSSIONS_TABLE." WHERE productID=".$productID);
                                $k = db_fetch_row($q); $k = $k[0];

                                //extra parameters
                                $extra = GetExtraParametrs((int)$productID);
                                $extracount = count($extra);
                                //related items
                                $related = array();
                                $q = db_query("select count(*) FROM ".RELATED_PRODUCTS_TABLE." WHERE Owner=".$productID);
                                $cnt = db_fetch_row($q);
                                $smarty->assign("product_related_number", $cnt[0]);
                                if ($cnt[0] > 0)
                                {
                                        $q = db_query("select productID FROM ".RELATED_PRODUCTS_TABLE." WHERE Owner=".$productID);

                                        while ($row = db_fetch_row($q))
                                        {
                                                $p = db_query("select productID, name, Price FROM ".PRODUCTS_TABLE." WHERE productID=".$row[0]." and enabled=1");
                                                if ($r = db_fetch_row($p))
                                                {
                                                  $r["Price"] = show_price($r["Price"]);
                                                  $related[] = $r;
                                                }
                                        }

                                }
                                $smarty->assign( "productslinkscat", getcontentprod($productID));
                                //update several product fields
                                if (!file_exists("data/small/".$a["picture"] )) $a["picture"] = 0;
                                if (!file_exists("data/medium/".$a["thumbnail"] )) $a["thumbnail"] = 0;
                                if (!file_exists("data/big/".$a["big_picture"] )) $a["big_picture"] = 0;
                                else if ($a["big_picture"])
                                {
                                        $size = getimagesize("data/big/".$a["big_picture"] );
                                        $a[16] = $size[0]+40;
                                        $a[17] = $size[1]+30;
                                }
                                $a[12] = show_price( $a["Price"] );
                                $a[13] = show_price( $a["list_price"] );
                                $a[14] = show_price( $a["list_price"] - $a["Price"]); //you save (value)
                                $a["PriceWithOutUnit"]=show_priceWithOutUnit( $a["Price"] );
                                if ( $a["list_price"] ) $a[15] =
                                        ceil(((($a["list_price"]-$a["Price"])/
                                                $a["list_price"])*100)); //you save (%)


                                if ( isset($_GET["picture_id"]) )
                                {
                                        $pictures = db_query("select photoID, filename, thumbnail, enlarged from ".
                                                PRODUCT_PICTURES." where photoID!=".(int)$_GET["picture_id"].
                                                " AND productID=".$productID );
                                }
                                else if ( !is_null($a["default_picture"]) )
                                {
                                        $pictures = db_query("select photoID, filename, thumbnail, enlarged from ".
                                                PRODUCT_PICTURES." where photoID!=".$a["default_picture"].
                                                " AND productID=".$productID );
                                }
                                else
                                {
                                        $pictures = db_query("select photoID, filename, thumbnail, enlarged from ".
                                                PRODUCT_PICTURES." where productID=".$productID );
                                }
                                $all_product_pictures = array();
                                $all_product_pictures_id = array();
                                while( $picture=db_fetch_row($pictures) )
                                {
                                        if ( $picture["filename"] != "")
                                        {
                                                if ( file_exists("data/small/".$picture["filename"]))
                                                {
                                                        if (!file_exists("data/medium/".$picture["thumbnail"] )) $picture["thumbnail"] = 0;
                                                        if (!file_exists("data/big/".$picture["enlarged"] )) $picture["enlarged"] = 0;
                                                        $all_product_pictures[]=$picture;
                                                        $all_product_pictures_id[] = $picture[0];
                                                }
                                        }
                                }

                                //eproduct
                                if (strlen($a["eproduct_filename"]) > 0 && file_exists("core/files/".$a["eproduct_filename"]) )
                                {
                                        $size = filesize("core/files/".$a["eproduct_filename"]);
                                        if ($size > 1000) $size = round ($size / 1000);
                                        $a["eproduct_filesize"] = $size." Kb";
                                }
                                else
                                {
                                        $a["eproduct_filename"] = "";
                                }

                                //initialize product "request information" form in case it has not been already submitted
                                if (!isset($_POST["request_information"]))
                                {
                                        if (!isset($_SESSION["log"]))
                                        {
                                                $customer_name = "";
                                                $customer_email = "";
                                        }
                                        else
                                        {
                                                $custinfo = regGetCustomerInfo2( $_SESSION["log"] );
                                                $customer_name = $custinfo["first_name"]." ".$custinfo["last_name"];
                                                $customer_email = $custinfo["Email"];
                                        }

                                        $message_text = "";
                                }

                                $smarty->hassign("customer_name", $customer_name);
                                $smarty->hassign("customer_email", $customer_email);
                                $smarty->hassign("message_text", $message_text);

                                if (isset($_GET["sent"])) $smarty->assign("sent",1);

                                $smarty->assign("all_product_pictures_id", $all_product_pictures_id );
                                $smarty->assign("all_product_pictures", $all_product_pictures );
                                $smarty->assign("product_info", $a);
                                $smarty->assign("product_reviews_count", $k);
                                $smarty->assign("product_extra", $extra);
                                $smarty->assign("product_extra_count", $extracount);
                                $smarty->assign("product_related", $related);
                        }
                        else
                        {
                                //product not found
                                header("HTTP/1.0 404 Not Found");
                                header("HTTP/1.1 404 Not Found");
                                header("Status: 404 Not Found");
                                die(ERROR_404_HTML);
                        }
                }
        }

?><?php
#####################################
# ShopCMS: Скрипт интернет-магазина
# Copyright (c) by ADGroup
# http://shopcms.ru
#####################################
        // product discussion page

        if (isset($_POST["add_topic"]) && isset($productID)) // add post to the product discussion
        {
                if ( !prdProductExists($productID) ){
                                //product not found
                                header("HTTP/1.0 404 Not Found");
                                header("HTTP/1.1 404 Not Found");
                                header("Status: 404 Not Found");
                                die(ERROR_404_HTML);
                }

                if(CONF_ENABLE_CONFIRMATION_CODE){
                                 $error_p = 1;
                        if(!$_POST['fConfirmationCode'] || !isset($_SESSION['captcha_keystring']) || $_SESSION['captcha_keystring'] !==  $_POST['fConfirmationCode']) {
                                 $error_p = 7;
                                 $smarty->assign("error",$error_p);
                        }
                        unset($_SESSION['captcha_keystring']);
                        if($error_p == 1){
                                 discAddDiscussion( $productID, $_POST["nick"], $_POST["topic"], $_POST["body"] );
                                 Redirect("index.php?productID=$productID&discuss=yes");
                        }
                }else{
                discAddDiscussion( $productID, $_POST["nick"], $_POST["topic"], $_POST["body"] );
                                 Redirect("index.php?productID=$productID&discuss=yes");
                }

        }

                if (isset($_POST["add_topic"]) && isset($productID)) // add data to page
                {
                        $dis_nic = $_POST["nick"];
                        $dis_subject = $_POST["topic"];
                        $dis_text = $_POST["body"];
                }
                else
                {
                        $dis_nic = "";
                        $dis_subject = "";
                        $dis_text = "";
                }

                $smarty->hassign("dis_nic",$dis_nic);
                $smarty->hassign("dis_subject",$dis_subject);
                $smarty->hassign("dis_text",$dis_text);

        if (isset($_GET["remove_topic"]) && isset($productID) && isset($_SESSION["log"])) // delete topic in the discussion
        {

        if (isset($_SESSION["log"]) && in_array(100,$relaccess)) {
                if ( !prdProductExists($productID) ){
                                //product not found
                                header("HTTP/1.0 404 Not Found");
                                header("HTTP/1.1 404 Not Found");
                                header("Status: 404 Not Found");
                                die(ERROR_404_HTML);
                }
                discDeleteDiscusion( $_GET["remove_topic"] );
                Redirect("index.php?productID=$productID&discuss=yes");
        }
        }

        if (isset($productID) && $productID>0 && (isset($_GET["discuss"]) || isset($_POST["discuss"]))) //show discussion form
        {
                if ( !prdProductExists($productID) ){
                                //product not found
                                header("HTTP/1.0 404 Not Found");
                                header("HTTP/1.1 404 Not Found");
                                header("Status: 404 Not Found");
                                die(ERROR_404_HTML);
                }

                $smarty->assign("discuss","yes");
                $smarty->assign("main_content_template", "product_discussion.tpl.html");

                $q = db_query("select name from ".PRODUCTS_TABLE." where productID=".$productID." and enabled=1");
                $a = db_fetch_row($q);
                if ($a)
                {
                        $smarty->assign("product_name", $a[0]);
                        $q = db_query("select count(*) from ".DISCUSSIONS_TABLE." WHERE productID=".$productID);
                        $cnt = db_fetch_row($q);
                        if ($cnt[0])
                        {
                                $q = db_query(
                                        "select Author, Body, add_time, DID, Topic FROM ".DISCUSSIONS_TABLE.
                                        " WHERE productID=".$productID." ORDER BY add_time DESC");
                                $result = array();
                                while ($row = db_fetch_row($q))
                                {
                                        $row["add_time"]= format_datetime( $row["add_time"] );
                                        $result[] = $row;
                                }

                                $smarty->assign("product_reviews", $result);
                        }
                        else
                        {
                                $smarty->assign("product_reviews", NULL);
                        }
                }
        }
?><?php
#####################################
# ShopCMS: Скрипт интернет-магазина
# Copyright (c) by ADGroup
# http://shopcms.ru
#####################################


// *****************************************************************************
// Purpose                        registration form
// Call condition
//                                        index.php?register=yes OR isset($register)
//                                                                        - register new customer
//                                        ( index.php?register=yes OR isset($register) ) AND isset($order)
//                                                                        - register new customer and proceed order process
//                                        index.php?register=yes&r_successful=yes
//                                                                        - register new customer success notification
// Include PHP                index.php -> [register.php]
// Uses TPL                        register.tpl, reg_successful.tpl
// Remarks

if ( isset($_GET["r_successful"]) )                        // successful registration notification
        $smarty->assign("main_content_template", "reg_successful.tpl.html");

if ( isset($register) && !isset($_SESSION["log"]) )
{



        // *****************************************************************************
        // Purpose        copies data from $_POST variable to HTML page
        // Inputs                     $smarty - smarty object
        // Remarks
        // Returns        nothing
        function _copyDataFromPostToPage( & $smarty )
        {
                $smarty->hassign("login",  trim($_POST["login"]) );
                $smarty->hassign("cust_password1", trim($_POST["cust_password1"]) );
                $smarty->hassign("cust_password2", trim($_POST["cust_password2"]) );
                $smarty->hassign("first_name", trim($_POST["first_name"]));
                $smarty->hassign("affiliationLogin", trim($_POST["affiliationLogin"]));
                $smarty->hassign("last_name", trim($_POST["last_name"]));
                $smarty->hassign("email", trim($_POST["email"]));
                $smarty->assign("subscribed4news", (isset($_POST["subscribed4news"])?1:0) );

                $zones = znGetZonesById( (int)$_POST["countryID"] );
                $smarty->hassign("zones",$zones);

                $additional_field_values = array();
                $data = ScanPostVariableWithId( array( "additional_field" ) );
                foreach( $data as $key => $val )
                {
                        $item = array( "reg_field_ID" => $key, "reg_field_name" => "",
                                "reg_field_value" => $val["additional_field"] );
                        $additional_field_values[] = $item;
                }
                $smarty->hassign("additional_field_values", $additional_field_values );

                $smarty->assign("countryID", (int)$_POST["countryID"] );
                if ( isset($_POST["state"]) ) $smarty->hassign("state", trim($_POST["state"]) );
                if ( isset($_POST["zoneID"]) ) $smarty->assign("zoneID", (int)$_POST["zoneID"] );
                $smarty->hassign("city", trim($_POST["city"]));
                $smarty->hassign("address", trim($_POST["address"]));
                if ( isset($_POST["order"]) || isset($_GET["order"]) )
                {
                        if (  isset($_POST["billing_address_check"]) ) $smarty->assign( "billing_address_check", "1" );

                        $smarty->hassign( "receiver_first_name", trim($_POST["receiver_first_name"]) );
                        $smarty->hassign( "receiver_last_name", trim($_POST["receiver_last_name"]) );

                        if ( !isset($_POST["billing_address_check"]) )
                        {
                                $smarty->hassign( "payer_first_name", trim($_POST["payer_first_name"]));
                                $smarty->hassign( "payer_last_name", trim($_POST["payer_last_name"]));
                                $smarty->assign( "billingCountryID", (int)$_POST["billingCountryID"]);
                                if ( isset($_POST["billingState"]) )  $smarty->hassign( "billingState", trim($_POST["billingState"]));
                                if ( isset($_POST["billingZoneId"]) ) $smarty->assign( "billingZoneId", (int)$_POST["billingZoneId"] );
                                $smarty->hassign( "billingCity", trim($_POST["billingCity"]));
                                $smarty->hassign( "billingAddress", trim($_POST["billingAddress"]));
                                $billingZones = znGetZonesById( (int)$_POST["billingCountryID"] );
                                $smarty->assign( "billingZones", $billingZones );
                        }
                        else
                        {
                                $smarty->hassign( "payer_first_name", trim($_POST["receiver_first_name"]));
                                $smarty->hassign( "payer_last_name", trim($_POST["receiver_last_name"]));
                                $smarty->assign( "billingCountryID", (int)$_POST["countryID"] );
                                if ( isset($_POST["state"]) ) $smarty->hassign( "billingState", trim($_POST["state"]) );
                                if ( isset($_POST["zoneId"]) ) $smarty->assign( "billingZoneId", (int)$_POST["zoneId"] );
                                $smarty->hassign( "billingCity", trim($_POST["city"]));
                                $smarty->hassign( "billingAddress", trim($_POST["address"]));
                                $smarty->assign( "billingZones", $zones);
                        }
                }
        }


        if ( !isset($_POST["state"]) ) $_POST["state"] = "";
        if ( !isset($_POST["countryID"]) ) $_POST["countryID"] = CONF_DEFAULT_COUNTRY;

        $isPost = isset($_POST["login"]) && isset($_POST["cust_password1"]);

        if ( $isPost )  _copyDataFromPostToPage( $smarty );

        if ( isset($_POST["save"]) ) //save user to the database
        {
                $login            = trim($_POST["login"]);
                $cust_password1   = trim($_POST["cust_password1"]);
                $cust_password2   = trim($_POST["cust_password2"]);
                $first_name       = trim($_POST["first_name"]);
                $affiliationLogin = trim($_POST["affiliationLogin"]);
                $last_name        = trim($_POST["last_name"]);
                $Email            = trim($_POST["email"]);
                $subscribed4news  = ( isset($_POST["subscribed4news"]) ? 1 : 0 );
                $additional_field_values = ScanPostVariableWithId( array( "additional_field" ) );

                if ( isset($order) )
                {
                        $receiver_first_name = trim($_POST["receiver_first_name"]);
                        $receiver_last_name  = trim($_POST["receiver_last_name"]);
                }

                $countryID = (int)$_POST["countryID"];
                $state     = trim($_POST["state"]);
                $city      = trim($_POST["city"]);
                $address   = trim($_POST["address"]);
                if ( isset($_POST["zoneID"]) ) $zoneID = (int)$_POST["zoneID"];
                else $zoneID = 0;

                if ( isset($order) && isset($_POST["billing_address_check"]) )
                {
                        $payer_first_name = $receiver_first_name;
                        $payer_last_name  = $receiver_last_name;
                        $billingCountryID = $countryID;
                        $billingState     = $state;
                        $billingCity      = $city;
                        $billingAddress   = $address;
                        $billingZoneID    = $zoneID;
                }
                else if ( isset($order) )
                {
                        $payer_first_name = trim($_POST["payer_first_name"]);
                        $payer_last_name  = trim($_POST["payer_last_name"]);
                        $billingCountryID = (int)$_POST["billingCountryID"];
                        if ( isset($_POST["billingState"]) ) $billingState = trim($_POST["billingState"]);
                        else $billingState = "";
                        $billingCity      = trim($_POST["billingCity"]);
                        $billingAddress   = trim($_POST["billingAddress"]);
                        if ( isset($_POST["billingZoneID"]) ) $billingZoneID = (int)$_POST["billingZoneID"];
                        else $billingZoneID = 0;
                }

                $error = regVerifyContactInfo( $login, $cust_password1, $cust_password2,
                                                $Email, $first_name, $last_name, $subscribed4news,
                                                $additional_field_values );

                if(CONF_ENABLE_CONFIRMATION_CODE){
                        if(!$_POST['fConfirmationCode'] || !isset($_SESSION['captcha_keystring']) || $_SESSION['captcha_keystring'] !==  $_POST['fConfirmationCode'])  $error = ERR_WRONG_CCODE;
                        unset($_SESSION['captcha_keystring']);
                }

                if ( $error == "" ) unset( $error );

                if (!isset($error) && isset($affiliationLogin))
                        if ( !regIsRegister($affiliationLogin) && $affiliationLogin)
                                $error = ERROR_WRONG_AFFILIATION;

                if ( !isset($error) )
                        if ( regIsRegister($login) )
                                $error = ERROR_USER_ALREADY_EXISTS;

                if ( !isset($error) )
                {
                        if ( !isset($order) )
                                $error = regVerifyAddress(        $first_name, $last_name, $countryID, $zoneID, $state,
                                                                        $city, $address );
                        else
                                $error = regVerifyAddress(        $receiver_first_name, $receiver_last_name, $countryID,
                                                                        $zoneID, $state, $city, $address );
                        if ( $error == "" ) unset( $error );
                }

                if ( !isset($error) && isset($order) )
                {
                        $error = regVerifyAddress( $payer_first_name, $payer_last_name, $billingCountryID,
                                                                        $billingZoneID, $billingState, $billingCity, $billingAddress );
                        if ( $error == "" ) unset( $error );
                }

                if ( !isset($error) )
                {
                        $cust_password = $cust_password1;

                        $registerResult =
                                regRegisterCustomer(
                                        $login, $cust_password, $Email, $first_name,
                                        $last_name, $subscribed4news, $additional_field_values, $affiliationLogin );

                        if ( $registerResult )
                        {

                                if ( isset($order) )
                                {
                                        $addressID = regAddAddress(
                                                $receiver_first_name, $receiver_last_name, $countryID,
                                                $zoneID, $state, $city,
                                                $address, $login, $errorCode );
                                        $billingAddressID = $addressID;

                                        if ( !isset($_POST["billing_address_check"]) )
                                        {
                                                $billingAddressID = regAddAddress(
                                                        $payer_first_name, $payer_last_name, $billingCountryID,
                                                        $billingZoneID, $billingState, $billingCity,
                                                        $billingAddress, $login, $errorCode );
                                        }

                                        regSetDefaultAddressIDByLogin( $login, $addressID );
                                }
                                else
                                {
                                        $addressID = regAddAddress(
                                                $first_name, $last_name, $countryID,
                                                $zoneID, $state, $city,
                                                $address, $login, $errorCode );
                                        regSetDefaultAddressIDByLogin( $login, $addressID );
                                }

                                regEmailNotification( $smarty_mail,
                                        $login, $cust_password, $Email, $first_name,
                                        $last_name, $subscribed4news, $additional_field_values,
                                        $countryID, $zoneID, $state, $city, $address, 0 );

                                if(!CONF_ENABLE_REGCONFIRMATION){
                                        regAuthenticate( $login, $cust_password );
                                }

                                $RedirectURL = '';
                                if ( isset($order) )
                                {
                                        if ( isset($billingAddressID)  )
                                                $RedirectURL = ( "index.php?order2_shipping=yes&shippingAddressID=".
                                                                        regGetDefaultAddressIDByLogin($login).
                                                                        "&defaultBillingAddressID=".$billingAddressID );
                                        else
                                                $RedirectURL = ( "index.php?order2_shipping=yes&shippingAddressID=".
                                                                        regGetDefaultAddressIDByLogin($login) );
                                }elseif ( isset($order_without_billing_address) ){
                                        $RedirectURL = ( "index.php?order2_shipping=yes&shippingAddressID=".
                                                                        regGetDefaultAddressIDByLogin($login) );
                                }else{
                                        $RedirectURL = ( "index.php?r_successful=yes" );
                                }
                                if(CONF_ENABLE_REGCONFIRMATION && (isset($order)||isset($order_without_billing_address))){

                                        xSaveData('xREGMAILCONF_URLORDER2', $RedirectURL);
                                        $RedirectURL = ( "index.php?act_customer=1&order2=yes" );
                                }

                                RedirectJavaScript($RedirectURL);


                        }
                        else
                                $smarty->assign( "reg_error", ERROR_INPUT_STATE );
                }
                else
                        $smarty->assign( "reg_error", $error );
        }

        // countries
        $callBackParam = array();
        $count_row = 0;
        $countries = cnGetCountries( $callBackParam, $count_row );
        $smarty->assign("countries", $countries );

        if ( !$isPost )
        {
                if ( count($countries) != 0 )
                {
                        $zones = znGetZonesById(CONF_DEFAULT_COUNTRY);
                        $smarty->assign("zones", $zones);//var_dump($zones);
                        $smarty->assign("billingZones", $zones);
                }
        }else{
           if ( count($countries) != 0 )
                {
                        $zones = znGetZonesById((int)$_POST["countryID"]);
                        $smarty->assign("zones", $zones);//var_dump($zones);
                        $smarty->assign("billingZones", $zones);
                }
        }

        // additional fields
        $additional_fields=GetRegFields();
        $smarty->assign("additional_fields", $additional_fields );

        if ( isset($register) ) $smarty->assign("return_url", "index.php?register=yes" );

        // proceeding to checkout mode
        if ( isset($order) ) $smarty->assign("order", 1);

        // proceeding to checkout mode without billing address
        if ( isset($order_without_billing_address) ) $smarty->assign("order_without_billing_address", 1);

        if(isset($_SESSION['s_RefererLogin'])) $smarty->assign('SessionRefererLogin', $_SESSION['s_RefererLogin']);

        $smarty->assign("main_content_template", "register.tpl.html");
}
?><?php
#####################################
# ShopCMS: Скрипт интернет-магазина
# Copyright (c) by ADGroup
# http://shopcms.ru
#####################################
/**
 * Activation of registration
 */
if(isset($_GET['act_customer']) && CONF_ENABLE_REGCONFIRMATION){

        $ActErr = false;
        if(isset($_GET['act_code'])){

                if($_GET['act_code']){

                        $sql = 'SELECT customerID, Login, cust_password FROM '.CUSTOMERS_TABLE.'
                                WHERE ActivationCode="'.xEscapeSQLstring($_GET['act_code']).'"
                                AND ActivationCode!="" AND ActivationCode IS NOT NULL';
                        $Result = db_query($sql);
                        $Customer = db_fetch_row($Result);

                        if(isset($Customer['Login'])&&$Customer['Login']){

                                regActivateCustomer($Customer['customerID']);
                                regAuthenticate($Customer['Login'], cryptPasswordDeCrypt($Customer['cust_password'], null) );
                                if (isset($_GET['order2'])&&xDataExists('xREGMAILCONF_URLORDER2')) {
                                        Redirect(xPopData('xREGMAILCONF_URLORDER2'));
                                }else {
                                        Redirect(set_query('&act_code=&act_ok=1'));
                                }
                        }else{

                                $smarty->hassign('ActCode', $_GET['act_code']);
                                $ActErr = true;
                        }
                }else {

                        $ActErr = true;
                }
        }

        if(isset($_GET['act_ok']))$smarty->assign('ActOk', 1);
        if(isset($_GET['notact']))$smarty->assign('NoAct', 1);
        if($ActErr)$smarty->assign('ActErr', 1);
        $smarty->assign("main_content_template", "register_activation.tpl.html");
}
?><?php
#####################################
# ShopCMS: Скрипт интернет-магазина
# Copyright (c) by ADGroup
# http://shopcms.ru
#####################################

  if (CONF_USER_SYSTEM > 0){

        if ( isset($register_authorization) )
        {
                if ( !cartCheckMinOrderAmount() ) Redirect( "index.php?shopping_cart=yes" );


                if ( isset($_GET["remind_password"]) )
                        $smarty->assign("remind_password" , 1);

                if ( isset($_POST["user_login"])  )
                {
                        $smarty->hassign( "user_login", $_POST["user_login"] );
                        $smarty->assign( "login_to_remind_password", $_POST["user_login"] );
                }

                if ( isset($_POST["remind_password"]) ){

                        $Reminded = regSendPasswordToUser( $_POST["login_to_remind_password"], $smarty_mail )?'yes':'no';
                        if($Reminded=='no') $smarty->hassign('remind_user_login', $_POST["login_to_remind_password"]);
                        $smarty->assign( "password_sent_notifycation",  $Reminded);
                }
                if ( isset($_POST["login"]) )
                {
                        if ( trim($_POST["user_login"]) != "" )
                        {
                                $cartIsEmpty = cartCartIsEmpty($_POST["user_login"]);
                                if ( regAuthenticate( $_POST["user_login"], $_POST["user_pw"] ) )
                                {
                                        if ( $cartIsEmpty )
                                                Redirect( "index.php?order2_shipping=yes&shippingAddressID=".
                                                        regGetDefaultAddressIDByLogin($_SESSION["log"]) );
                                        else
                                                Redirect( "index.php?shopping_cart=yes&make_more_exact_cart_content=yes" );
                                }
                                else $smarty->assign("remind_password" , 1);
                        }
                }

                $smarty->assign("check_order", "yes");
                $smarty->assign("main_content_template", "register_authorization.tpl.html");
        }
  }else{
        if ( isset($register_authorization) ) Redirect( "index.php?quick_register=yes" );
  }
?><?php
#####################################
# ShopCMS: Скрипт интернет-магазина
# Copyright (c) by ADGroup
# http://shopcms.ru
#####################################



if ( isset($quick_register) && !isset($_SESSION["log"]) )
{

        // *****************************************************************************
        // Purpose        copies data from $_POST variable to HTML page
        // Inputs                     $smarty - smarty object
        // Remarks
        // Returns        nothing
        function _copyDataFromPostToPage( &$smarty )
        {
                $smarty->hassign("first_name", trim($_POST["first_name"]));
                $smarty->hassign("last_name", trim($_POST["last_name"]));
                $smarty->hassign("email", trim($_POST["email"]) );
                $smarty->hassign("affiliationLogin", trim($_POST["affiliationLogin"]));
                $smarty->assign("subscribed4news", (isset($_POST["subscribed4news"])?1:0) );

                $zones = znGetZonesById( (int)$_POST["countryID"] );
                $smarty->assign("zones",$zones);
                $smarty->assign("countryID", (int)$_POST["countryID"] );
                if ( isset($_POST["state"]) ) $smarty->hassign("state", trim($_POST["state"]) );
                if ( isset($_POST["zoneID"]) ) $smarty->assign("zoneID", (int)$_POST["zoneID"] );
                $smarty->hassign("city", trim($_POST["city"]));
                $smarty->hassign("address", trim($_POST["address"]));
                $smarty->hassign( "receiver_first_name", trim($_POST["receiver_first_name"]) );
                $smarty->hassign( "receiver_last_name", trim($_POST["receiver_last_name"]) );

                //aux registration fields
                $additional_field_values = array();
                $data = ScanPostVariableWithId( array( "additional_field" ) );
                foreach( $data as $key => $val )
                {
                        $item = array( "reg_field_ID" => $key, "reg_field_name" => "",
                                "reg_field_value" => $val["additional_field"] );
                        $additional_field_values[] = $item;
                }
                $smarty->hassign("additional_field_values", $additional_field_values );

                if ( CONF_ORDERING_REQUEST_BILLING_ADDRESS == '1' )
                {
                        if (  isset($_POST["billing_address_check"]) ) $smarty->assign( "billing_address_check", "1" );

                        if ( !isset($_POST["billing_address_check"]) )
                        {
                                $smarty->hassign( "payer_first_name", trim($_POST["payer_first_name"]));
                                $smarty->hassign( "payer_last_name", trim($_POST["payer_last_name"]));
                                $smarty->assign( "billingCountryID", (int)$_POST["billingCountryID"] );
                                if ( isset($_POST["billingState"]) )  $smarty->hassign( "billingState", trim($_POST["billingState"]));
                                if ( isset($_POST["billingZoneID"]) ) $smarty->assign( "billingZoneID", (int)$_POST["billingZoneID"] );
                                $smarty->hassign( "billingCity", trim($_POST["billingCity"]));
                                $smarty->hassign( "billingAddress", trim($_POST["billingAddress"]));

                                $billingZones = znGetZonesById( $_POST["billingCountryID"] );
                                $smarty->assign( "billingZones", $billingZones );
                        }
                        else
                        {
                                $smarty->hassign( "payer_first_name", trim($_POST["receiver_first_name"]));
                                $smarty->hassign( "payer_last_name", trim($_POST["receiver_last_name"]));
                                $smarty->assign( "billingCountryID", (int)$_POST["countryID"] );
                                if ( isset($_POST["state"]) ) $smarty->hassign( "billingState", trim($_POST["state"]) );
                                if ( isset($_POST["zoneId"]) ) $smarty->assign( "billingZoneID", (int)$_POST["zoneId"] );
                                $smarty->hassign( "billingCity", trim($_POST["city"]));
                                $smarty->hassign( "billingAddress", trim($_POST["address"]) );
                                $smarty->assign( "billingZones", $zones);
                        }
                }
        }



        $isPost = isset($_POST["first_name"]) && isset($_POST["last_name"]);

        if ( $isPost )
        {
                _copyDataFromPostToPage( $smarty );
        }
        else
        {
                $zones = znGetZonesById(CONF_DEFAULT_COUNTRY);
                $smarty->assign("zones",$zones);
                $smarty->assign("billingZones",$zones);
        }

        if ( isset($_POST["save"]) )
        {
                $_POST["affiliationLogin"] = isset($_POST["affiliationLogin"])?$_POST["affiliationLogin"]:'';
                $affiliationLogin = $_POST["affiliationLogin"];
                if ( !isset($_POST["state"]) ) $_POST["state"] = "";
                if ( !isset($_POST["zoneID"]) ) $_POST["zoneID"] = 0;
                if ( !isset($_POST["billingState"]) )$_POST["billingState"] = "";
                if ( !isset($_POST["billingZoneID"]) ) $_POST["billingZoneID"] = 0;


                $error = "";
                $error = quickOrderContactInfoVerify();

                // receiver address
                if ( $error == "" ) $error = quickOrderReceiverAddressVerify();

                // payer address
                if ( CONF_ORDERING_REQUEST_BILLING_ADDRESS == '1' && $error == "" ) $error = quickOrderBillingAddressVerify();

                if(CONF_ENABLE_CONFIRMATION_CODE){
                        if(!$_POST['fConfirmationCode'] || !isset($_SESSION['captcha_keystring']) || $_SESSION['captcha_keystring'] !==  $_POST['fConfirmationCode'])  $error = ERR_WRONG_CCODE;
                        unset($_SESSION['captcha_keystring']);
                }

                if ( $error == "" )
                {
                        quikOrderSetCustomerInfo();
                        quickOrderSetReceiverAddress();
                        if (  CONF_ORDERING_REQUEST_BILLING_ADDRESS == '1' ) quickOrderSetBillingAddress();

                        RedirectJavaScript("index.php?order2_shipping_quick=yes");
                }
                else
                        $smarty->assign( "reg_error", $error );

        }

        // additional fields
        $additional_fields = GetRegFields();
        $smarty->assign("additional_fields", $additional_fields );


        $callBackParam = array();
        $count_row = 0;
        $countries = cnGetCountries( $callBackParam, $count_row );
        $smarty->assign("countries", $countries );

        $smarty->assign( "quick_register", 1 );
        $smarty->assign( "main_content_template", "register_quick.tpl.html" );
        if(isset($_SESSION['refid']))$smarty->assign('SessionRefererLogin', $_SESSION['refid']);
}

?><?php
#####################################
# ShopCMS: Скрипт интернет-магазина
# Copyright (c) by ADGroup
# http://shopcms.ru
#####################################

        // simple search
        if (isset($_GET["inside"]))
                $smarty->assign("search_in_results", $_GET["inside"]);

        if (isset($_GET["searchstring"])) //make a simple search
        {

                function _getUrlToNavigate()
                {
                        $url = "index.php?searchstring=".$_GET["searchstring"];
                        if ( isset($_GET["x"]) )
                                $url .= "&x=".$_GET["x"];
                        if ( isset($_GET["y"]) )
                                $url .= "&y=".$_GET["y"];
                        if ( isset($_GET["sort"]) )
                                $url .= "&sort=".$_GET["sort"];
                        if ( isset($_GET["direction"]) )
                                $url .= "&direction=".$_GET["direction"];
                        return $url;
                }

                function _getUrlToSort()
                {
                        $url = "index.php?searchstring=".$_GET["searchstring"];
                        if ( isset($_GET["x"]) )
                                $url .= "&x=".$_GET["x"];
                        if ( isset($_GET["y"]) )
                                $url .= "&y=".$_GET["y"];
                        if ( isset($_GET["offset"]) )
                                $url .= "&offset=".$_GET["offset"];
                        if ( isset($_GET["show_all"]) )
                                $url .= "&show_all=yes";
                        return $url;
                }

                function _sortSetting( &$smarty, $urlToSort )
                {
                        $sort_string = STRING_PRODUCT_SORT;
                        $sort_string = str_replace( "{ASC_NAME}",   "<a href='".$urlToSort."&sort=name&direction=ASC'>".STRING_ASC."</a>",        $sort_string );
                        $sort_string = str_replace( "{DESC_NAME}",  "<a href='".$urlToSort."&sort=name&direction=DESC'>".STRING_DESC."</a>",        $sort_string );
                        $sort_string = str_replace( "{ASC_PRICE}",   "<a href='".$urlToSort."&sort=Price&direction=ASC'>".STRING_ASC."</a>",        $sort_string );
                        $sort_string = str_replace( "{DESC_PRICE}",  "<a href='".$urlToSort."&sort=Price&direction=DESC'>".STRING_DESC."</a>",        $sort_string );
                        $sort_string = str_replace( "{ASC_RATING}",   "<a href='".$urlToSort."&sort=customers_rating&direction=ASC'>".STRING_ASC."</a>",        $sort_string );
                        $sort_string = str_replace( "{DESC_RATING}",  "<a href='".$urlToSort."&sort=customers_rating&direction=DESC'>".STRING_DESC."</a>",        $sort_string );
                        $smarty->assign( "string_product_sort", html_amp($sort_string) );
                }

                $searchstrings = array();
                $tmp = explode(" ", $_GET["searchstring"]);
                foreach( $tmp as $key=> $val )
                {
                        if ( strlen( trim($val) ) > 0 ) $searchstrings[] = $val;
                }

                if ( isset($_GET["inside"]) )
                {
                        $data = ScanGetVariableWithId( array("search_string") );
                        foreach( $data as $key => $value ) $searchstrings[] = $value["search_string"];
                }
                $smarty->hassign( "searchstrings", $searchstrings );

                $callBackParam = array();
                $products      = array();
                $callBackParam["search_simple"] = $searchstrings;

                if ( isset($_GET["sort"]) ) $callBackParam["sort"] = $_GET["sort"];
                if ( isset($_GET["direction"]) ) $callBackParam["direction"] = $_GET["direction"];

                $countTotal = 0;
                $navigatorHtml = GetNavigatorHtml(
                                        _getUrlToNavigate(), CONF_PRODUCTS_PER_PAGE,
                                        'prdSearchProductByTemplate', $callBackParam,
                                        $products, $offset, $countTotal );

                if ( CONF_PRODUCT_SORT == '1' )
                        _sortSetting( $smarty, _getUrlToSort() );
                  if(CONF_ALLOW_COMPARISON_FOR_SIMPLE_SEARCH == 1){

                     $show_comparison = 0;
                        foreach ($products as $_Key=>$_Product){

                                $products[$_Key]['allow_products_comparison'] = 1;
                                $show_comparison++;
                        }
                        $smarty->assign( "show_comparison", $show_comparison );
                }
                $smarty->assign( "products_to_show",  $products );
                $smarty->assign( "products_to_show_counter", count($products));
                $smarty->assign( "products_found", $countTotal );
                $smarty->assign( "products_to_show_count", $countTotal );
                $smarty->assign( "search_navigator", $navigatorHtml );
                $smarty->assign( "main_content_template", "search_simple.tpl.html" );
        }
?><?php
#####################################
# ShopCMS: Скрипт интернет-магазина
# Copyright (c) by ADGroup
# http://shopcms.ru
#####################################
        
		$smarty->assign("product_removed", 0);
		
		// 	product has been removed from shopping cart
		
		if (isset($_GET["product_removed"]))
        {
		   $smarty->assign("product_removed", 1);
		   $smarty->assign("main_content_template", "shopping_cart.tpl.html");		
		}
		// shopping cart

        if (isset($this_is_a_popup_cart_window) || isset($_GET["shopping_cart"]) || isset($_POST["shopping_cart"]))
        {

                if (isset($this_is_a_popup_cart_window)) $cart_reguest = "index.php?do=cart";
                else $cart_reguest = "index.php?popup=no";

                $smarty->assign("cart_reguest", $cart_reguest);

                if ( isset($_GET["make_more_exact_cart_content"]) ) $smarty->assign( "make_more_exact_cart_content", 1 );

                //add product to cart with productID=$add
                if ( isset($_GET["add2cart"]) && $_GET["add2cart"]>0 /*&& isset($_SESSION["variants"]) */)
                {
                        if (isset($_SESSION["variants"]))
                        {
                                $variants=$_SESSION["variants"];
                                unset($_SESSION["variants"]);
                                session_unregister("variants"); //calling session_unregister() is required since unset() may not work on some systems
                        }
                        else
                        {
                                $variants = array();
                        }
                        for ($mcn=0; $mcn<$_GET["multyaddcount"]; $mcn++) cartAddToCart( $_GET["add2cart"], $variants );
                        Redirect( $cart_reguest."&shopping_cart=yes" );
                }


                if (isset($_GET["remove"])) //remove from cart product with productID == $remove
                {

                        if (isset($_SESSION["log"]))
                        {
                                db_query("DELETE FROM ".SHOPPING_CARTS_TABLE.
                                        " WHERE customerID=".regGetIdByLogin($_SESSION["log"]).
                                        " AND itemID=".(int)$_GET["remove"]);
                                db_query("DELETE FROM ".SHOPPING_CART_ITEMS_TABLE." where itemID=".(int)$_GET["remove"]);
                                db_query("DELETE FROM ".SHOPPING_CART_ITEMS_CONTENT_TABLE." where itemID=".(int)$_GET["remove"]);
                                db_query("DELETE FROM ".ORDERED_CARTS_TABLE." where itemID=".(int)$_GET["remove"]);
                        }
                        else //remove from session vars
                        {
                                $res = DeCodeItemInClient( $_GET["remove"] );
                                $i = SearchConfigurationInSessionVariable($res["variants"], $res["productID"] );
                                if ( $i!=-1 ) $_SESSION["gids"][$i] = 0;
                        }

                        Redirect( $cart_reguest."&shopping_cart=yes" );
                }


                if (isset($_POST["update"])) //update shopping cart content
                {
                        foreach ($_POST as $key => $val)
                        {
                                if (strstr($key, "count_"))
                                {
                                        if (isset($_SESSION["log"])) //authorized user
                                        {
                                                $productID = GetProductIdByItemId( str_replace("count_","",$key) );
                                                $is=GetProductInStockCount( $productID );
                                                if ($val > 0) //$val is a new items count in the shopping cart
                                                {
                                                        if (CONF_CHECKSTOCK==1)
                                                                $val = min($val, $is); //check stock level
                                                                $q = db_query("UPDATE ".SHOPPING_CARTS_TABLE.
                                                                " SET Quantity=".floor($val).
                                                                " WHERE customerID=".
                                                                regGetIdByLogin($_SESSION["log"]).
                                                                " AND itemID=".
                                                                (int)str_replace("count_","",$key));
                                                }
                                                else //$val<=0 => delete item from cart
                                                        $q = db_query("DELETE FROM ".SHOPPING_CARTS_TABLE." WHERE customerID=".regGetIdByLogin($_SESSION["log"])." AND itemID=".(int)str_replace("count_","",$key));
                                        }
                                        else //session vars
                                        {
                                                $res=DeCodeItemInClient( str_replace("count_","", $key) );

                                                $is=GetProductInStockCount( $res["productID"] );
                                                if ($val > 0)
                                                {
                                                        $i=SearchConfigurationInSessionVariable($res["variants"], $res["productID"] );
                                                        //check stock level
                                                        if (CONF_CHECKSTOCK==1) $val = min($val, $is);
                                                        $_SESSION["counts"][$i] = floor($val);
                                                }
                                                else //remove
                                                {
                                                        $i=SearchConfigurationInSessionVariable($res["variants"], $res["productID"] );
                                                        $_SESSION["gids"][$i] = 0;
                                                }
                                        }
                                }
                        }

                        Redirect( $cart_reguest."&shopping_cart=yes" );

                }

                if (isset($_GET["clear_cart"])) //completely clear shopping cart
                {
                        cartClearCartContet();
                        Redirect( $cart_reguest."&shopping_cart=yes" );
                }


                $resCart = cartGetCartContent();

                $resDiscount = dscCalculateDiscount( $resCart["total_price"], isset($_SESSION["log"])?$_SESSION["log"]:"" );
                $discount_value = addUnitToPrice( $resDiscount["discount_current_unit"] );
                $discount_percent = $resDiscount["discount_percent"];

                $smarty->assign("cart_content", $resCart["cart_content"] );
                $smarty->assign("cart_amount",  $resCart["total_price"] - $resDiscount["discount_standart_unit"]);
                $smarty->assign('cart_min',     show_price(CONF_MINIMAL_ORDER_AMOUNT));
                $smarty->assign("cart_total",   show_price( $resCart["total_price"] - $resDiscount["discount_standart_unit"] ) );

                // discount_prompt = 0 ( discount information is not shown )
                // discount_prompt = 1 ( discount information is showed simply without prompt )
                // discount_prompt = 2 ( discount information is showed with
                //                        STRING_UNREGISTERED_CUSTOMER_DISCOUNT_PROMPT )
                // discount_prompt = 3 ( discount information is showed with
                //                        STRING_UNREGISTERED_CUSTOMER_COMBINED_DISCOUNT_PROMPT )
                switch( CONF_DISCOUNT_TYPE )
                {
                        // discount is switched off
                        case 1:
                                $smarty->assign( "discount_prompt", 0 );
                                break;

                        // discount is based on customer group
                        case 2:
                                if ( isset($_SESSION["log"]) )
                                {
                                        $smarty->assign( "discount_value", $discount_value );
                                        $smarty->assign( "discount_percent", $discount_percent );
                                        $smarty->assign( "discount_prompt", 1 );
                                }
                                else
                                {
                                        $smarty->assign( "discount_value", $discount_value );
                                        $smarty->assign( "discount_percent", $discount_percent );
                                        $smarty->assign( "discount_prompt", 2 );
                                }
                                break;

                        // discount is calculated with help general order price
                        case 3:
                                $smarty->assign( "discount_prompt", 1 );
                                $smarty->assign( "discount_value", $discount_value );
                                $smarty->assign( "discount_percent", $discount_percent );
                                break;

                        // discount equals to discount is based on customer group plus
                        //                discount calculated with help general order price
                        case 4:
                                if ( isset($_SESSION["log"]) )
                                {
                                        $smarty->assign("discount_prompt", 1 );
                                        $smarty->assign("discount_value", $discount_value );
                                        $smarty->assign("discount_percent", $discount_percent );
                                }
                                else
                                {
                                        $smarty->assign("discount_prompt", 3 );
                                        $smarty->assign("discount_value", $discount_value );
                                        $smarty->assign("discount_percent", $discount_percent );
                                }
                                break;

                        // discount is calculated as MAX( discount is based on customer group,
                        //                        discount calculated with help general order price  )
                        case 5:
                                if ( isset($_SESSION["log"]) )
                                {
                                        $smarty->assign("discount_prompt", 1 );
                                        $smarty->assign("discount_value", $discount_value );
                                        $smarty->assign("discount_percent", $discount_percent );
                                }
                                else
                                {
                                        $smarty->assign("discount_prompt", 3 );
                                        $smarty->assign("discount_value", $discount_value );
                                        $smarty->assign("discount_percent", $discount_percent );
                                }
                                break;
                }


                if ( isset($_SESSION["log"]) ) $smarty->assign( "shippingAddressID", regGetDefaultAddressIDByLogin($_SESSION["log"]) );

                $smarty->assign("main_content_template", "shopping_cart.tpl.html");
        }
?><?php
#####################################
# ShopCMS: Скрипт интернет-магазина
# Copyright (c) by ADGroup
# http://shopcms.ru
#####################################

        // shopping cart brief info

        //calculate shopping cart value
        $k=0;
        $cnt = 0;
        if (isset($_SESSION["log"])) //taking products from database
        {
                $q = db_query("select itemID, Quantity FROM ".SHOPPING_CARTS_TABLE.
                                " WHERE customerID=".regGetIdByLogin($_SESSION["log"]));
                while ($row = db_fetch_row($q))
                {
                        $q1=db_query("select productID from ".SHOPPING_CART_ITEMS_TABLE.
                                " where itemID=".$row["itemID"]);
                        $r1=db_fetch_row($q1);
                        if($r1["productID"]){
                        $variants=GetConfigurationByItemId( $row["itemID"] );
                        $k += GetPriceProductWithOption($variants, $r1["productID"])*$row["Quantity"];
                        $cnt+=$row["Quantity"];
                        }
                }
        }
        else
        if (isset($_SESSION["gids"])) //...session vars
        {
                for ($i=0; $i<count($_SESSION["gids"]); $i++)
                {
                        if ($_SESSION["gids"][$i])
                        {
                                $t = db_query("select Price FROM ".PRODUCTS_TABLE." WHERE productID=".(int)$_SESSION["gids"][$i]);
                                $rr = db_fetch_row($t);

                                $sum=$rr["Price"];

                                // $rr["Price"]
                                foreach( $_SESSION["configurations"][$i] as $vars )
                                {
                                        $q1=db_query("select price_surplus from ".PRODUCTS_OPTIONS_SET_TABLE.
                                                " where variantID=".(int)$vars." AND productID=".(int)$_SESSION["gids"][$i]);
                                        $r1=db_fetch_row($q1);
                                        $sum+=$r1["price_surplus"];
                                }

                                $k += $_SESSION["counts"][$i]*$sum;
                                $cnt += $_SESSION["counts"][$i];
                        }
                }
        }

        $smarty->assign("shopping_cart_value", $k);
        $smarty->assign("shopping_cart_value_shown", show_price($k));
        $smarty->assign("shopping_cart_items", $cnt);

?><?php
#####################################
# ShopCMS: Скрипт интернет-магазина
# Copyright (c) by ADGroup
# http://shopcms.ru
#####################################

        if ( isset($show_aux_page) )
        {
                $aux_page = auxpgGetAuxPage( $show_aux_page );

                if ( $aux_page )
                {
                        $smarty->assign("page_body", $aux_page["aux_page_text"] );
                        $smarty->assign("aux_page_name", $aux_page["aux_page_name"] );
                        $smarty->assign("show_aux_page", $aux_page["aux_page_ID"] );
                        $smarty->assign("main_content_template", "show_aux_page.tpl.html" );
                }
                else
                {
                        header("HTTP/1.0 404 Not Found");
                        header("HTTP/1.1 404 Not Found");
                        header("Status: 404 Not Found");
                        die(ERROR_404_HTML);
                }
        }
?><?php

if(isset($_REQUEST['transaction_result']))
    $transaction_result=$_REQUEST['transaction_result'];
    else $transaction_result = null;

                $orderID = null;
                $order = null;
                if(isset($_REQUEST["InvId"])) $orderID = (int)$_REQUEST["InvId"];
                if(isset($_REQUEST["LMI_PAYMENT_NO"])) $orderID = (int)$_REQUEST["LMI_PAYMENT_NO"];
                $order = ordGetOrder( $orderID );
if ($order!=null && $orderID>0){
switch ($transaction_result){

        case 'success':
                $smarty->assign('orderID', $orderID);
                $smarty->assign('TransactionResult', $transaction_result);
                $smarty->assign( "main_content_template", "transaction_result.tpl.html");
                if ($orderID != "" && $order["customerID"] == regGetIdByLogin($_SESSION["log"]))header('Refresh: 6; url=index.php?p_order_detailed='.$orderID);
                break;

        case 'failure':
                $smarty->assign('TransactionResult', $transaction_result);
                $smarty->assign( "main_content_template", "transaction_result.tpl.html");
                break;

        default:  break;
}
}
?><?php
#####################################
# ShopCMS: Скрипт интернет-магазина
# Copyright (c) by ADGroup
# http://shopcms.ru
#####################################

        // registration form

        if (isset($_GET["user_details"]) && isset($_SESSION["log"])) //show user's account
        {
                $cust_password           = null;
                $Email                   = null;
                $first_name              = null;
                $last_name               = null;
                $subscribed4news         = null;
                $additional_field_values = null;
                regGetContactInfo( $_SESSION["log"], $cust_password, $Email, $first_name,
                                $last_name, $subscribed4news, $additional_field_values );
                $smarty->assign("additional_field_values", $additional_field_values );
                $smarty->assign("first_name", $first_name );
                $smarty->assign("last_name", $last_name );
                $smarty->assign("Email", $Email );
                $smarty->assign("login", $_SESSION["log"] );


                $customerID = regGetIdByLogin( $_SESSION["log"] );
                $custgroup = GetCustomerGroupByCustomerId( $customerID );
                $smarty->assign( "custgroup_name", $custgroup["custgroup_name"] );

                $smarty->assign('affiliate_customers', affp_getCustomersNum($customerID));

                if ( CONF_DISCOUNT_TYPE == '2' )
                        if ( $custgroup["custgroup_discount"] > 0 )
                                $smarty->assign( "discount", $custgroup["custgroup_discount"] );

                if ( CONF_DISCOUNT_TYPE == '4' || CONF_DISCOUNT_TYPE == '5' )
                        if ( $custgroup["custgroup_discount"] > 0 )
                                $smarty->assign( "min_discount", $custgroup["custgroup_discount"] );

                $defaultAddressID = regGetDefaultAddressIDByLogin( $_SESSION["log"] );
                $addressStr = regGetAddressStr( $defaultAddressID );
                $smarty->assign("addressStr", $addressStr );

                $smarty->assign("visits_count", stGetVisitsCount( $_SESSION["log"] ) );
                $smarty->assign("status_distribution", ordGetDistributionByStatuses( $_SESSION["log"] ) );
                $smarty->assign("main_content_template", "user_account.tpl.html");
        }

?><?php
#####################################
# ShopCMS: Скрипт интернет-магазина
# Copyright (c) by ADGroup
# http://shopcms.ru
#####################################


    if ( isset($visit_history) && isset($_SESSION["log"]) )
        {
                $callBackParam = array( "log" => $_SESSION["log"] );
                $visits = null;
                $offset = 0;
                $count = 0;
                $navigatorHtml = GetNavigatorHtml( "index.php?visit_history=yes", 20,
                                'stGetVisitsByLogin', $callBackParam, $visits, $offset, $count );

                $smarty->assign("navigator", $navigatorHtml );
                $smarty->assign("visits", $visits );
                $smarty->assign("main_content_template", "visit_history.tpl.html");
        }

?>