<?php
require ( "core/smarty/smarty.class.php" );
$smarty = new Smarty;
$smarty->template_dir = "core/tpl/user/".CONF_DEFAULT_TEMPLATE;
if (CONF_SMARTY_FORCE_COMPILE) $smarty->force_compile = true;
define('TPL', CONF_DEFAULT_TEMPLATE);

function recursiveCatFilter($catID,$arrayID=array())
{
    global $fc;
    foreach ($fc as $val) if ($val['parent'] == $catID) $arrayID = recursiveCatFilter($val['categoryID'],$arrayID);
    $arrayID[]=$catID;
    return $arrayID;
}


$categoryID = isset($_POST['categoryID'])?(int)$_POST['categoryID']:1;

if ($categoryID > 1)
    {
    $data = db_query("SELECT categoryID, parent FROM ".DB_PRFX."categories");
    while ($row = db_fetch_assoc($data)) $fc[] = $row;
    $addonCat = "categoryID IN (".implode(",",recursiveCatFilter($categoryID)).")";
    }
else $addonCat = 'categoryID>1';

$addonStock = (CONF_CHECKSTOCK == 1 && CONF_SHOW_NULL_STOCK == 1)?' AND in_stock > 0 ':'';

$currency = currGetCurrencyByID( (int)$_POST['cID'] );

$row = db_fetch_assoc(db_query("SELECT MIN(Price) AS minprice, MAX(Price) AS maxprice 
                 FROM ".DB_PRFX."products
                 WHERE enabled=1 AND $addonCat$addonStock"));

$minprice_in_currency = floor($row['minprice']*$currency['currency_value']);
$maxprice_in_currency =  ceil($row['maxprice']*$currency['currency_value']);
$smarty->assign('minprice',$minprice_in_currency);
$smarty->assign('maxprice',$maxprice_in_currency);

$price_from_in_currency = isset($_POST['price_from'])?(int)$_POST['price_from']:$minprice_in_currency;
$price_to_in_currency   = isset($_POST['price_to'])?(int)$_POST['price_to']:$maxprice_in_currency;
$price_from = isset($_POST['price_from'])?(int)$_POST['price_from']/$currency['currency_value']:$row['minprice'];
$price_to   = isset($_POST['price_to'])?(int)$_POST['price_to']/$currency['currency_value']:$row['maxprice'];

$POST_param = ScanPostVariableWithId( array('param_new') );

$filtersort = in_array($_POST['sort'],array(CONF_DEFAULT_SORT_ORDER,'Price,name','Price DESC,name','name,Price','customers_rating DESC,name,Price'))?$_POST['sort']:CONF_DEFAULT_SORT_ORDER;

if ((int)$_GET['filter'] == 1)
{
$data = db_query("SELECT optionID,variantID, COUNT(productID) AS count
                  FROM ".DB_PRFX."product_options_set
                  JOIN ".DB_PRFX."products USING(productID)
                  WHERE enabled=1 AND Price>=$price_from AND Price<=$price_to AND $addonCat$addonStock
                  GROUP BY optionID,variantID");

while ($row = db_fetch_assoc($data))
    {
    $options[$row['optionID']] = $row['optionID'];
    $variants[$row['variantID']] = $row['variantID'];
    $counts[$row['variantID']] = $row['count'];
    }

if (count($options))
    {
    $data = db_query("SELECT optionID, name
                      FROM ".DB_PRFX."product_options
                      WHERE optionID IN (".implode(",",$options).")
                      ORDER BY sort_order, name");

    while ($row = db_fetch_assoc($data)) $params[$row['optionID']] = $row;

    $data = db_query("SELECT optionID, variantID, option_value
                      FROM ".DB_PRFX."products_opt_val_variants
                      WHERE variantID IN (".implode(",",$variants).")
                      ORDER BY sort_order, option_value");

    while ($row = db_fetch_assoc($data)) 
        {
        $params[$row['optionID']]['variants'][$row['variantID']] = $row;
        $params[$row['optionID']]['variants'][$row['variantID']]['count'] = $counts[$row['variantID']];
        }

    if ($POSTcount = count($POST_param))
        {
        foreach ($POST_param as $optionID => $variants)
            foreach ($variants['param_new'] as $variantID)
                $params[$optionID]['variants'][$variantID]['checked'] = 1;

        foreach ($params as $optionID => $option)
            {
            $true_count = $POSTcount + (isset($POST_param[$optionID])?0:1);
            foreach ($option['variants'] as $variantID => $variant)
                {
                $search = array($variantID);
                foreach ($POST_param as $POSToptionID => $POSTvariants)
                    foreach ($POSTvariants['param_new'] as $POSTvariantID)
                        if ($POSToptionID != $optionID) $search[] = $POSTvariantID;
                $row = db_fetch_assoc(db_query("SELECT COUNT(*) AS count 
                      FROM (SELECT COUNT(DISTINCT optionID) AS count
                          FROM ".DB_PRFX."product_options_set
                          JOIN ".DB_PRFX."products USING(productID)
                          WHERE enabled=1 AND Price>=$price_from AND Price<=$price_to AND $addonCat$addonStock AND variantID IN (".implode(',',$search).")
                          GROUP BY productID) AS t
                      WHERE t.count=$true_count"));
                $params[$optionID]['variants'][$variantID]['count'] = $row['count'];
                }
            }
        }
    $smarty->assign('params',$params);
    }
$smarty->assign('price_from',$price_from_in_currency);
$smarty->assign('price_to',  $price_to_in_currency);
$smarty->assign('categoryID',$categoryID);
$smarty->assign('priceUnit',$currency['code']);
echo iconv('CP1251','utf-8',$smarty->fetch("filter.tpl.html"));
}

elseif ((int)$_GET['filter'] == 2)
{
if ($POSTcount = count($POST_param)) // галки вариантов установлены, хотя бы одна
    {
    foreach ($POST_param as $POSToptionID => $POSTvariants)
        foreach ($POSTvariants['param_new'] as $POSTvariantID)
            $search[] = $POSTvariantID;
    $data = db_query("SELECT productID
                  FROM (SELECT productID, COUNT(DISTINCT optionID) AS count 
                      FROM ".DB_PRFX."product_options_set
                      JOIN ".DB_PRFX."products USING(productID)
                      WHERE enabled=1 AND Price>=$price_from AND Price<=$price_to AND $addonCat$addonStock AND variantID IN (".implode(',',$search).")
                      GROUP BY productID
                      ORDER BY $filtersort) AS t
                  WHERE t.count=$POSTcount");
    while ($row = db_fetch_assoc($data)) $filtered_products[] = $row['productID'];
    }
else // не установлено ни одной галки фильтра, более простой запрос, только по цене
    {
    $data = db_query("SELECT productID
                  FROM ".DB_PRFX."products
                  WHERE enabled=1 AND Price>=$price_from AND Price<=$price_to AND $addonCat$addonStock
                  ORDER BY $filtersort");

    while ($row = db_fetch_assoc($data))
        $filtered_products[] = $row['productID'];
    }

$prdPerPage = (int)$_POST['prdPerPage'];
$prdcount = count($filtered_products);
$pagecount = ceil($prdcount/$prdPerPage);
$start = isset($_GET['offset'])?(int)$_GET['offset']:0;
$end = min($start+$prdPerPage-1,$prdcount);
$page_products = $prdcount?array_slice($filtered_products, $start, $prdPerPage):array();

if ($prdcount > $prdPerPage)
    {
    if ($start > 0) $nav = "<a href='#' onclick='go2page(".max($start-$prdPerPage,0).");'>&lt;&lt; пред</a>&nbsp;&nbsp";
    else $nav = "<span style='color:grey'>&lt;&lt; пред</span>&nbsp;&nbsp";
    $n = 1;
    for ($i=0; $i<$prdcount; $i+=$prdPerPage)
        {
        $nav .= ($i==$start)?("<b>".$n++."</b>"):("<a href='#' onclick='go2page(".$i.")'>".$n++."</a>");
        $nav .= (($n + 39) % 40)?"&nbsp;&nbsp":"<br>";
        $lastOffset = $i;
        }
    if ($start < $lastOffset) $nav .= "<a href='#' onclick='go2page(".min($start+$prdPerPage,$prdcount).");'>след &gt;&gt;</a>";
    else $nav .= "<span style='color:grey'>след &gt;&gt;</span>";
    }

foreach ($page_products as $productID)
    {
    $row = db_fetch_assoc(db_query("SELECT * FROM ".PRODUCTS_TABLE." WHERE productID=$productID"));
    _setPictures($row);
    $row["PriceWithUnit"]       = show_price($row["Price"],$currency['CID']);
    $row["list_priceWithUnit"]  = show_price($row["list_price"],$currency['CID']);
    $row["product_extra"]       = GetExtraParametrs($row["productID"]);
    $row["product_extra_count"] = count($row["product_extra"]);
    $row["PriceWithOutUnit"]    = show_priceWithOutUnit( $row["Price"] );
    $products[] = $row;
    }

$smarty->assign( "currencies_count", 1); // главное - чтобы не ноль. 
$smarty->assign( "currency_roundval",$currency['roundval']);
$smarty->assign( "catalog_navigator", $nav );
$smarty->assign( "product_category_path",catCalculatePathToCategory($categoryID) );
$smarty->assign( "products_to_showc", $prdcount);
$smarty->assign( "products_to_show", $products);
$smarty->assign( "products_to_show_counter", count($products));
$smarty->assign( "categoryName","Результат работы фильтра");

echo iconv('CP1251','utf-8',$smarty->fetch("category.tpl.html"));
}
?>
