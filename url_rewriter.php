<?php
/*
  Модуль "ЧПУ для ShopCMS"
  2009-2011 (c) http://trickywebs.org.ua/
  Техническая поддержка - soulmare@gmail.com
  Лицензия - MIT http://www.opensource.org/licenses/mit-license.php
*/

  //error_reporting(E_ALL);
  //ini_set('display_errors', 1);

  // Корневые разделы ЧПУ ссылок
	define('FU_CATALOG_ROOT', 'catalog');
	define('FU_NEWS_ROOT', 'news');
	define('FU_PAGES_ROOT', 'pages');

	define('FU_ROOT_DIR', dirname(__FILE__));
	define('FU_OPTION_ID', 0);

	// Access items by human readable URLs
	if(isset($_GET['uri'])) {

		$uri = $_GET['uri'];
		$uriFor = isset($_GET['uriFor']) && $_GET['uriFor'] ? $_GET['uriFor'] : 'product';

		// Try to load item by URI
	
		$uri = mysql_real_escape_string($uri);

    switch($uriFor) {
    

      // Product
      case 'product':

			  $sql = "SELECT productID
					  FROM ".PRODUCTS_TABLE."
					  WHERE uri = '$uri'
					  LIMIT 1";
				
				if($row = query_first($sql)) {

					$_GET['productID'] = $row[0];

				} else // 404 not found
  				$_GET['categoryID'] = -1;

        break;


      // Category
      case 'category':

        // Offset
        if(preg_match('|(.+)_offset_(\d+)$|', $uri, $matches)) {
          $uri = $matches[1];
          $offset = $matches[2];
        } elseif(preg_match('|(.+)_show_all.html$|', $uri, $matches)) {
          $uri = $matches[1];
          $offset = 1;
          $showAll = 'yes';
        } else
          $offset = 1;

			  $sql = "SELECT categoryID
					  FROM ".CATEGORIES_TABLE."
					  WHERE uri = '$uri'
					  LIMIT 1";

				if($row = query_first($sql)) {

					$_GET['categoryID'] = $row[0];
          if(isset($showAll))
            $_GET['show_all'] = $showAll;
          else {
            $_GET['offset'] = $offset;
          }

				} else // 404 not found
  				$_GET['categoryID'] = -1;

        break;


      // News
      case 'news':

			  $sql = "SELECT NID
					  FROM ".NEWS_TABLE."
					  WHERE uri = '$uri'
					  LIMIT 1";
				
				if($row = query_first($sql)) {

					$_GET['fullnews'] = $row[0];

				} else // 404 not found
  				$_GET['categoryID'] = -1;

        break;


      // Pages
      case 'pages':

			  $sql = "SELECT aux_page_ID
					  FROM ".AUX_PAGES_TABLE."
					  WHERE uri = '$uri'
					  LIMIT 1";
				
				if($row = query_first($sql)) {

					$_GET['show_aux_page'] = $row[0];

				} else // 404 not found
  				$_GET['categoryID'] = -1;

        break;


    }

    //check_www_prefix();

		// Launch engine

	} else { // No URI

		// Access by ID must be redirected to URI if present
		if((isset($_GET['productID']) || isset($_GET['categoryID']) || isset($_GET['fullnews']) || isset($_GET['show_aux_page'])) && !isset($_GET['search']) && !isset($_GET['discuss']) && !isset($_GET['advanced_search_in_category']) && !isset($_GET['sort']) && !isset($_GET['sent']) && !isset($_GET['search_with_change_category_ability']) && ($_SERVER['REQUEST_METHOD'] == 'GET')) {
			
			if(isset($_GET['productID'])) { // product

				$itemId = (int) $_GET['productID'];
				$sql = "SELECT p.uri, p.uri_opt_val, c.uri AS 'categorySlug', p.categoryID
						FROM ".PRODUCTS_TABLE." p
						LEFT JOIN ".CATEGORIES_TABLE." c
							ON p.categoryID = c.categoryID
						WHERE productID = '$itemId'
						LIMIT 1";

			} elseif(isset($_GET['categoryID'])) { // category

				$itemId = (int) $_GET['categoryID'];
				$sql = "SELECT uri
						FROM ".CATEGORIES_TABLE."
						WHERE categoryID = '$itemId'
						LIMIT 1";

			} elseif(isset($_GET['fullnews'])) { // news

				$itemId = (int) $_GET['fullnews'];
				$sql = "SELECT uri
						FROM ".NEWS_TABLE."
						WHERE NID = '$itemId'
						LIMIT 1";

			} elseif(isset($_GET['show_aux_page'])) { // pages

				$itemId = (int) $_GET['show_aux_page'];
				$sql = "SELECT uri
						FROM ".AUX_PAGES_TABLE."
						WHERE aux_page_ID = '$itemId'
						LIMIT 1";

			}

			// Get URI by ID
	
			if($queryResult = mysql_query($sql)) {
				if($row = mysql_fetch_row($queryResult)) {

					// Item has valid URI, redirect to it
					if($itemURI = $row[0]) {

						// Load engine settings
						if(!defined('CONF_FU_DEMO_MODE'))
							define('CONF_FU_DEMO_MODE', 1);

						// Generate new URL
						$newUrl = 'http://'.CONF_SHOP_URL.'/';
						if(isset($_GET['productID'])) { // Product

  						$newUrl .= FU_CATALOG_ROOT;
							$categorySlug = $row[2] ? $row[2] : 'category_'.$row[3];
							$newUrl .= '/'.$categorySlug.'/';
							$newUrl .= FU_OPTION_ID ? fu_get_option_slug($_GET['productID']).'/'.urlencode($itemURI) : urlencode($itemURI);

						} elseif(isset($_GET['categoryID'])) { // Category
  						$newUrl .= FU_CATALOG_ROOT;
							$newUrl .= '/'.urlencode($itemURI);
						} elseif(isset($_GET['fullnews'])) { // News
  						$newUrl .= FU_NEWS_ROOT;
							$newUrl .= '/'.urlencode($itemURI);
						} elseif(isset($_GET['show_aux_page'])) { // Pages
  						$newUrl .= FU_PAGES_ROOT;
							$newUrl .= '/'.urlencode($itemURI);
						}

						header('Content-Type: text/html; charset=utf-8');
						header ('HTTP/1.1 301 Moved Permanently');
						header ('Location: '.$newUrl);
?>
<html>
<head>
	<title>301 Moved Permanently<title>
</head>
<body>
	<h1>301 Moved Permanently</h1>
	<h2>Страница переместилась</h2>
	<p>Если браузер не проследовал автоматически, пожалуйста перейдите на новый адрес этой страницы <a href="<?php echo $newUrl?>"><?php echo $newUrl?></a></p>
	<p><small>Страница сгенирирована модулем "ЧПУ УРЛ для ShopCMS". Разработка модуля <a href="http://trickywebs.org.ua/">trickywebs.org.ua</a></small></p>
<body>
</html>
<?php
						die();

					}
				
				}
			}

			
		}

    //check_www_prefix();

		// Launch engine

	}

	
	function fu_make_url($obj) {
		global $fc;

		// Check if it's a correct object
		if(isset($obj['categoryID'])) {

			if(isset($obj['productID'])) { // Product

				if($obj['uri']) { // Rewrite URL

					// Get category slug
					if(isset($fc[$obj['categoryID']]) && $fc[$obj['categoryID']]['uri'])
						$pCatSlug = $fc[$obj['categoryID']]['uri'];
					else
						$pCatSlug = 'category_'.$obj['categoryID'];
					
					// Get option slug
					if(-1 == $obj['uri_opt_val']) { // undefined - define option slug
						// Get slug and update slug field
						$pOptSlug = fu_get_option_slug($obj['productID']);
					} else // Slug already defined, just use it
						$pOptSlug = $obj['uri_opt_val'];

					$url = FU_OPTION_ID ? FU_CATALOG_ROOT.'/'.$pCatSlug.'/'.$pOptSlug.'/'.$obj['uri'] : FU_CATALOG_ROOT.'/'.$pCatSlug.'/'.$obj['uri'];

				} else { // Common URL
					$url = "product_{$obj['productID']}.html";
				}

			} else { // Category

				if($obj['uri']) { // Rewrite URL
					$url = FU_CATALOG_ROOT.'/'.$obj['uri'];
				} else { // Common URL
					$url = "category_{$obj['categoryID']}.html";
				}

			}

		} else
			$url = '';

		return $url;
	}
	
	
	function fu_make_url_news($obj) {

		// Check object
		if(isset($obj['NID'])) {

			if($obj['uri']) { // Rewrite URL

				$url = FU_NEWS_ROOT.'/'.$obj['uri'];

			} else { // Common URL
				$url = "show_news_{$obj['NID']}.html";
			}

		} else
			$url = '';

		return $url;
	}


	function fu_make_url_pages($pageId) {
	  
    $pagesUriTable = get_pages_uri_table();

		// Check object
		if(isset($pagesUriTable[$pageId]) && $pagesUriTable[$pageId]) {

			$url = FU_PAGES_ROOT . '/' . $pagesUriTable[$pageId];

		} else
			$url = "page_$pageId.html";

		return $url;
	}
	
	
	// Get associated array of pages' URIs
	function get_pages_uri_table() {
	  global $pagesUriTable;

    if(isset($pagesUriTable) && $pagesUriTable) return $pagesUriTable;
    else $pagesUriTable = Array();

	  $sql = 'SELECT aux_page_ID, uri
			  FROM ' . AUX_PAGES_TABLE;
	  
	  $result = db_query($sql);
	  while($row = db_fetch_assoc($result))
	    $pagesUriTable[$row['aux_page_ID']] = $row['uri'];
	  
	  return $pagesUriTable;
	}


	// Get slug and update slug field
	function fu_get_option_slug($productID) {
	
		// Get option value
		$productID = (int) $productID;
		$sql = "SELECT povvt.option_value AS optionValue
					FROM ".PRODUCTS_OPTIONS_SET_TABLE." AS post
					LEFT JOIN ".PRODUCTS_OPTIONS_VALUES_VARIANTS_TABLE." AS povvt
						ON povvt.variantID=post.variantID
					WHERE povvt.optionID=".FU_OPTION_ID." AND post.productID='$productID'

					UNION
					
					SELECT povt.option_value AS optionValue
					FROM ".PRODUCT_OPTIONS_VALUES_TABLE." AS povt
					LEFT JOIN ".PRODUCT_OPTIONS_TABLE." AS pot
					  ON pot.optionID = povt.optionID
					WHERE povt.productID = '$productID'

					LIMIT 1";
		$q = db_query($sql);

		if($row = db_fetch_assoc($q)) {
			$optionValue = urlencode(strtolower(htmlspecialchars_decode(fu_translit($row['optionValue']))));
		} else
			$optionValue = '';

    if(!$optionValue) $optionValue = 'unknown';
		
		// Update slug field
		$optionValueSql = mysql_real_escape_string($optionValue);
		$sql = "UPDATE ".PRODUCTS_TABLE."
					SET uri_opt_val = '$optionValueSql'
					WHERE productID = '$productID'
					LIMIT 1";
		db_query($sql);
		
		return $optionValue;
	}


	// from http://www.softtime.ru/scripts/translit.php
	function fu_translit($st)	{

		// Сначала заменяем "односимвольные" фонемы.
		$st=strtr($st,"абвгдеёзийклмнопрстуфхъыэ_",
				"abvgdeeziyklmnoprstufh'iei");
		$st=strtr($st,"АБВГДЕЁЗИЙКЛМНОПРСТУФХЪЫЭ_",
				"ABVGDEEZIYKLMNOPRSTUFH'IEI");

		// Затем - "многосимвольные".
		$st=strtr($st, 
				array(
						"ж"=>"zh", "ц"=>"ts", "ч"=>"ch", "ш"=>"sh", 
						"щ"=>"shch","ь"=>"", "ю"=>"yu", "я"=>"ya",
						"Ж"=>"ZH", "Ц"=>"TS", "Ч"=>"CH", "Ш"=>"SH", 
						"Щ"=>"SHCH","Ь"=>"", "Ю"=>"YU", "Я"=>"YA",
						"ї"=>"i", "Ї"=>"Yi", "є"=>"ie", "Є"=>"Ye"
				   )
		);

		// Возвращаем результат.
		return $st;
	}
	
	
	function query_first($sql) {

		if($queryResult = mysql_query($sql))
			if($row = mysql_fetch_row($queryResult))
			  return $row;

	}


	function query_first_assoc($sql) {

		if($queryResult = db_query($sql))
			if($row = db_fetch_assoc($queryResult))
			  return $row;

	}


?>
