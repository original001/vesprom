{php}
   

    $q = db_query("select categoryID, name, parent, products_count, description, picture, ".
                " products_count_admin, sort_order, viewed_times, allow_products_comparison, allow_products_search, ".
                " show_subcategories_products, meta_description, meta_keywords, title ".
                " from envi_categories ");
        $catrow = db_fetch_row($q);
    do {
    if ($catrow["parent"] == 1) {
    printf('<a href="/category_%s.html" class="content">
        <div class="header">%s</div>
        <div class="img"><img src="data/category/%s"></div>
        <div class="desc">%s</div>
      </a>',$catrow["categoryID"],$catrow["name"],$catrow["picture"],$catrow["description"]);
  }}
  while ($catrow = db_fetch_row($q));
  
{/php}