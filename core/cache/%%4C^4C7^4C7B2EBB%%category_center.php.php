<?php /* Smarty version 2.6.22, created on 2015-07-28 23:03:34
         compiled from blocks/category_center.php */ ?>
<?php 
   

    $q = db_query("select uri, name, parent, products_count, description, picture, ".
                " products_count_admin, sort_order, viewed_times, allow_products_comparison, allow_products_search, ".
                " show_subcategories_products, meta_description, meta_keywords, title ".
                " from envi_categories ");
        $catrow = db_fetch_row($q);
    do {
    if ($catrow["parent"] == 1) {
    printf('<a href="/catalog/%s" class="content">
        <div class="header">%s</div>
        <div class="img"><img src="data/category/%s"></div>
        <div class="desc">%s</div>
      </a>',$catrow["uri"],$catrow["name"],$catrow["picture"],$catrow["description"]);
  }}
  while ($catrow = db_fetch_row($q));
  
 ?>