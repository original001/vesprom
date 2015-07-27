<?php
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
?>