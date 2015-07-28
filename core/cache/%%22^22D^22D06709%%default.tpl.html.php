<?php /* Smarty version 2.6.22, created on 2015-07-28 21:38:41
         compiled from admin/default.tpl.html */ ?>
<table class="adn">
  <tr>
    <td class="zeb2 nbc">
      <table class="adn ggg">
         <tr>
           <td>
            <table class="adn">
              <tr>
               <td class="nbc2"><span class="titlecol"><?php echo @ADMIN_WELCOME; ?>
</span></td>
              </tr>
              <tr>
               <td class="nbcl"></td>
              </tr>
            </table>
           </td>
         </tr>
      </table>
    </td>
  </tr>
  <tr>
    <td valign="top" align="left" class="zeb">





       <table class="adn">
        <tr>
         <td align="center">

           <table class="adn">
              <tr>
               <td class="nbc2"><span class="titlecol2"><?php echo @ADMIN_MENU_FIRST; ?>
</span></td>
              </tr>
              <tr>
               <td class="nbcl"></td>
              </tr>
            </table><br><br>

<table width="94%" class="adw" align=center>
<tr>
<td width="33%" valign="top" align=left>
<table class="adw">
 <tr>
  <td valign="top" align=right><img src="data/admin/catalog.gif" alt="" style="margin-right: 6px;"></td>
  <td valign="top" align=left><u><b><?php echo @ADMIN_CATALOG; ?>
</b></u><br><br>
                <div class="marg"><a href="<?php echo @ADMIN_FILE; ?>
?dpt=catalog&amp;sub=products_categories"><?php echo @ADMIN_CATEGORIES_PRODUCTS; ?>
</a></div>
                <div class="marg"><a href="<?php echo @ADMIN_FILE; ?>
?dpt=catalog&amp;sub=dbsync"><?php echo @ADMIN_SYNCHRONIZE_TOOLS; ?>
</a></div>
                <div class="marg"><a href="<?php echo @ADMIN_FILE; ?>
?dpt=catalog&amp;sub=extra"><?php echo @ADMIN_PRODUCT_OPTIONS; ?>
</a></div>
                <div class="marg"><a href="<?php echo @ADMIN_FILE; ?>
?dpt=catalog&amp;sub=special"><?php echo @ADMIN_SPECIAL_OFFERS; ?>
</a></div>
                <div class="marg"><a href="<?php echo @ADMIN_FILE; ?>
?dpt=catalog&amp;sub=excel_import"><?php echo @ADMIN_IMPORT_FROM_EXCEL; ?>
</a></div>
                <div class="marg"><a href="<?php echo @ADMIN_FILE; ?>
?dpt=catalog&amp;sub=excel_export"><?php echo @ADMIN_EXPORT_TO_EXCEL; ?>
</a></div>
        </td>
</tr>
</table>

</td>
<td width="33%" valign="top" align=left>
<table class="adw">

 <tr>
  <td valign="top" align=right><img src="data/admin/stories.gif" alt="" style="margin-right: 6px;"></td>
        <td valign="top" align=left><u><b><?php echo @ADMIN_CUSTOMERS_AND_ORDERS; ?>
</b></u><br><br><div class="marg"><a href="<?php echo @ADMIN_FILE; ?>
?dpt=custord&amp;sub=new_orders"><?php echo @ADMIN_NEW_ORDERS; ?>
</a> <a href="<?php echo @ADMIN_FILE; ?>
?order_search_type=SearchByStatusID&amp;checkbox_order_status_2=1&amp;dpt=custord&amp;sub=new_orders&amp;search=">(<?php echo $this->_tpl_vars['new_orders_count']; ?>
)</a></div>
               <div class="marg"><a href="<?php echo @ADMIN_FILE; ?>
?dpt=custord&amp;sub=subscribers"><?php echo @ADMIN_NEWS_SUBSCRIBERS; ?>
</a></div>
               <div class="marg"><a href="<?php echo @ADMIN_FILE; ?>
?dpt=custord&amp;sub=order_statuses"><?php echo @ADMIN_ORDER_STATUES; ?>
</a></div>
               <div class="marg"><a href="<?php echo @ADMIN_FILE; ?>
?dpt=custord&amp;sub=reg_fields"><?php echo @ADMIN_CUSTOMER_REG_FIELDS; ?>
</a></div>
               <div class="marg"><a href="<?php echo @ADMIN_FILE; ?>
?dpt=custord&amp;sub=discounts"><?php echo @ADMIN_DISCOUNTS; ?>
</a></div>
                <div class="marg"><a href="<?php echo @ADMIN_FILE; ?>
?dpt=custord&amp;sub=aux_pages"><?php echo @ADMIN_TX7; ?>
</a></div>
        </td>
 </tr>

</table>

</td>

<td width="33%" valign="top" align=left>
<table class="adw">

 <tr>
  <td valign="top" align=right><img src="data/admin/optimize.gif" alt="" style="margin-right: 6px;"></td>
        <td valign="top" align=left><u><b><?php echo @ADMIN_SETTINGS; ?>
</b></u><br><br><div class="marg"><a href="<?php echo @ADMIN_FILE; ?>
?dpt=conf&amp;sub=setting&amp;settings_groupID=2"><?php echo @ADMIN_SETTINGS_GENERAL; ?>
</a></div>
                <div class="marg"><a href="<?php echo @ADMIN_FILE; ?>
?dpt=conf&amp;sub=currencies"><?php echo @ADMIN_CURRENCY_TYPES; ?>
</a></div>
                <div class="marg"><a href="<?php echo @ADMIN_FILE; ?>
?dpt=conf&amp;sub=shipping"><?php echo @STRING_SHIPPING_TYPE; ?>
</a></div>
                <div class="marg"><a href="<?php echo @ADMIN_FILE; ?>
?dpt=conf&amp;sub=payment"><?php echo @STRING_PAYMENT_TYPE; ?>
</a></div>
        <div class="marg"><a href="<?php echo @ADMIN_FILE; ?>
?dpt=conf&amp;sub=blocks_edit"><?php echo @ADMIN_TX20; ?>
</a></div>
                <div class="marg"><a href="<?php echo @ADMIN_FILE; ?>
?dpt=conf&amp;sub=admin_edit"><?php echo @ADMIN_CONF_ADMINS; ?>
</a></div>
        </td>
 </tr>

</table>

</td>


</tr>
 <tr><td colspan="3" height="40"></td></tr>
<tr>

<td width="33%" valign="top" align=left>
<table class="adw">

 <tr>
  <td valign="top" align=right><img src="data/admin/modules.gif" alt="" style="margin-right: 6px;"></td>
        <td valign="top" align=left><u><b><?php echo @ADMIN_MODULES; ?>
</b></u><br><br><div class="marg"><a href="<?php echo @ADMIN_FILE; ?>
?dpt=modules&amp;sub=news"><?php echo @ADMIN_NEWS; ?>
</a></div>
                <div class="marg"><a href="<?php echo @ADMIN_FILE; ?>
?dpt=modules&amp;sub=survey"><?php echo @ADMIN_VOTING; ?>
</a></div>
                <div class="marg"><a href="<?php echo @ADMIN_FILE; ?>
?dpt=modules&amp;sub=shipping"><?php echo @STRING_SHIPPING_MODULES; ?>
</a></div>
                <div class="marg"><a href="<?php echo @ADMIN_FILE; ?>
?dpt=modules&amp;sub=payment"><?php echo @STRING_PAYMENT_MODULES; ?>
</a></div>
                <div class="marg"><a href="<?php echo @ADMIN_FILE; ?>
?dpt=modules&amp;sub=linkexchange"><?php echo @STRING_MODULES_LINKEXCHANGE; ?>
</a></div>
                 <div class="marg"><a href="<?php echo @ADMIN_FILE; ?>
?dpt=modules&amp;sub=yandex"><?php echo @ADMIN_STRING_YANDEX; ?>
</a></div>
        </td>
 </tr>

</table>

</td>

<td width="33%" valign="top" align=left>
<table class="adw">

 <tr>
  <td valign="top" align=right><img src="data/admin/report.gif" alt="" style="margin-right: 6px;"></td>
        <td valign="top" align=left><u><b><?php echo @ADMIN_REPORTS; ?>
</b></u><br><br><div class="marg"><a href="<?php echo @ADMIN_FILE; ?>
?dpt=reports&amp;sub=category_viewed_times"><?php echo @ADMIN_CATEGORY_VIEWED_TIMES; ?>
</a></div>
                <div class="marg"><a href="<?php echo @ADMIN_FILE; ?>
?dpt=reports&amp;sub=customer_log"><?php echo @ADMIN_CUSTOMER_LOG; ?>
</a></div>
                <div class="marg"><a href="<?php echo @ADMIN_FILE; ?>
?dpt=reports&amp;sub=information"><?php echo @ADMIN_INFORMATION2; ?>
</a></div>
                <div class="marg"><a href="<?php echo @ADMIN_FILE; ?>
?dpt=reports&amp;sub=coming"><?php echo @ADMIN_COMING; ?>
</a></div>
                 <div class="marg"><a href="<?php echo @ADMIN_FILE; ?>
?dpt=reports&amp;sub=security"><?php echo @ADMIN_SECURITY; ?>
</a></div>
        </td>
 </tr>

</table>
</td>

<td width="33%" valign="top" align=left>
<table class="adw">

 <tr>
  <td valign="top" align=right><img src="data/admin/faq.gif" alt="" style="margin-right: 6px;"></td>
        <td valign="top" align=left><u><b>Списки</b></u><br><br><div class="marg"><a href="<?php echo @ADMIN_FILE; ?>
?dpt=custord&amp;sub=custlist"><?php echo @ADMIN_CUSTOMERS; ?>
</a></div>
                <div class="marg"><a href="<?php echo @ADMIN_FILE; ?>
?dpt=custord&amp;sub=custgroup"><?php echo @ADMIN_CUSTGROUP; ?>
</a></div>
                <div class="marg"><a href="<?php echo @ADMIN_FILE; ?>
?dpt=conf&amp;sub=countries"><?php echo @ADMIN_MENU_TOWNS; ?>
</a></div>
                <div class="marg"><a href="<?php echo @ADMIN_FILE; ?>
?dpt=conf&amp;sub=zones"><?php echo @ADMIN_MENU_TAXEZ; ?>
</a></div>
                      <div class="marg"><a href="<?php echo @ADMIN_FILE; ?>
?dpt=catalog&amp;sub=discuss"><?php echo @ADMIN_DISCUSSIONS; ?>
</a></div>
        </td>
 </tr>

</table>
</td>
</tr>
</table>










<?php if ($this->_tpl_vars['totals']): ?>
<br><br><br><table class="adn">
              <tr>
               <td class="nbc2"><span class="titlecol2"><?php echo @ADMIN_MENU_SECOND; ?>
</span></td>
              </tr>
              <tr>
               <td class="nbcl"></td>
              </tr>
            </table><br><br>
<table width="94%" class="adw" align=center>
<tr>
<td width="33%" valign="top" align=left>
<table class="adw">

 <tr>
  <td valign="top" align=right><img src="data/admin/hd.gif" alt="" style="margin-right: 6px;"></td>
        <td valign="top" align=left><u><b><?php echo @ADMIN_MENU_PRODS; ?>
</b></u><br><br><div class="marg"><?php echo @ADMIN_MENU_PRODS_ALL; ?>
: <?php echo $this->_tpl_vars['totals']['products']; ?>
</div>
                        <div class="marg"><?php echo @ADMIN_MENU_PRODS_BUY; ?>
: <?php echo $this->_tpl_vars['totals']['products_enabled']; ?>
</div>
                        <div class="marg"><?php echo @ADMIN_MENU_PRODS_CATS; ?>
: <?php echo $this->_tpl_vars['totals']['categories']; ?>
</div>
                        <div class="marg"><a href="<?php echo @ADMIN_FILE; ?>
?dpt=catalog&amp;sub=discuss&amp;productID=0"><?php echo @ADMIN_MENU_PRODS_DIS; ?>
</a>: <?php echo $this->_tpl_vars['totals']['discussion_posts']; ?>
</div>
        </td>
 </tr>

</table>


</td>
<td width="33%" valign="top" align=left>
<table class="adw">

 <tr>
  <td valign="top" align=right><img src="data/admin/book.gif" alt="" style="margin-right: 6px;"></td>
        <td valign="top" align=left><u><b><?php echo @ADMIN_CUSTOMERS_AND_ORDERS; ?>
</b></u><br><br><div class="marg"><a href="<?php echo @ADMIN_FILE; ?>
?dpt=conf&amp;sub=payment"><?php echo @ADMIN_MENU_VAR_PAY; ?>
</a>: <?php echo $this->_tpl_vars['totals']['payment_types']; ?>
</div>
                        <div class="marg"><a href="<?php echo @ADMIN_FILE; ?>
?dpt=conf&amp;sub=shipping"><?php echo @ADMIN_MENU_VAR_SHIP; ?>
</a>: <?php echo $this->_tpl_vars['totals']['shipping_types']; ?>
</div>
                         <div class="marg"><a href="<?php echo @ADMIN_FILE; ?>
?dpt=conf&amp;sub=currencies"><?php echo @ADMIN_MENU_TYPES_CUR; ?>
</a>: <?php echo $this->_tpl_vars['totals']['currency_types']; ?>
</div>
                         <div class="marg"><a href="<?php echo @ADMIN_FILE; ?>
?dpt=custord&amp;sub=aux_pages"><?php echo @ADMIN_MENU_AUX_PAGES; ?>
</a>: <?php echo $this->_tpl_vars['totals']['aux_pages']; ?>
</div>
        </td>
 </tr>

</table>

</td>

<td width="33%" valign="top" align=left>
<table class="adw">

 <tr>
  <td valign="top" align=right><img src="data/admin/uses.gif" alt="" style="margin-right: 6px;"></td>
        <td valign="top" align=left><u><b><?php echo @ADMIN_CUSTOMERS; ?>
</b></u><br><br><div class="marg"><a href="<?php echo @ADMIN_FILE; ?>
?login=&amp;first_name=&amp;last_name=&amp;email=&amp;groupID=0&amp;fActState=-1&amp;dpt=custord&amp;sub=custlist&amp;search=Find"><?php echo @ADMIN_MENU_REG_USERS; ?>
</a>: <?php echo $this->_tpl_vars['totals']['customers']; ?>
</div>
                        <div class="marg"><a href="<?php echo @ADMIN_FILE; ?>
?dpt=custord&amp;sub=custgroup"><?php echo @ADMIN_MENU_GROUPS; ?>
</a>: <?php echo $this->_tpl_vars['totals']['customer_groups']; ?>
</div>
                        <div class="marg"><a href="<?php echo @ADMIN_FILE; ?>
?dpt=custord&amp;sub=subscribers"><?php echo @ADMIN_MENU_SUBSC; ?>
</a>: <?php echo $this->_tpl_vars['totals']['newsletter_subscribers']; ?>
</div>
        </td>
 </tr>

</table>

</td>


</tr>
 <tr><td colspan="3" height="40"></td></tr>
<tr>

<td width="33%" valign="top" align=left>
<table class="adw">
 <tr>
  <td valign="top" align=right><img src="data/admin/cart.gif" alt="" style="margin-right: 6px;"></td>
  <td valign="top" align=left><u><b><?php echo @ADMIN_MENU_ORDERS; ?>
</b></u><br><br>
                <div class="marg"><b><?php echo @ADMIN_MENU_ORD_TOD; ?>
:</b><br><?php echo $this->_tpl_vars['totals']['orders_today']; ?>
 <?php echo @ADMIN_MENU_ORD_NAME; ?>
 (<?php echo $this->_tpl_vars['totals']['revenue_today']; ?>
)</div>
                <div class="marg"><b><?php echo @ADMIN_MENU_ORD_YEST; ?>
:</b><br><?php echo $this->_tpl_vars['totals']['orders_yesterday']; ?>
 <?php echo @ADMIN_MENU_ORD_NAME; ?>
 (<?php echo $this->_tpl_vars['totals']['revenue_yesterday']; ?>
)</div>
                <div class="marg"><b><?php echo @ADMIN_MENU_ORD_MON; ?>
:</b><br><?php echo $this->_tpl_vars['totals']['orders_thismonth']; ?>
 <?php echo @ADMIN_MENU_ORD_NAME; ?>
 (<?php echo $this->_tpl_vars['totals']['revenue_thismonth']; ?>
)</div>
                <div class="marg"><b><?php echo @ADMIN_MENU_ORD_ALLTIME; ?>
:</b><br><?php echo $this->_tpl_vars['totals']['orders']; ?>
 <?php echo @ADMIN_MENU_ORD_NAME; ?>
 (<?php echo $this->_tpl_vars['totals']['revenue']; ?>
)</div>
        </td>
</tr>
</table>

</td>

<td width="33%" valign="top" align=left>
<table class="adw">

 <tr>
  <td valign="top" align=right><img src="data/admin/statics-2.gif" alt="" style="margin-right: 6px;"></td>
        <td valign="top" align=left><u><b><?php echo @ADMIN_MENU_STATS; ?>
</b></u><br><br><div class="marg"><?php echo @ADMIN_MENU_STAT1; ?>
: <?php echo $this->_tpl_vars['totals']['count_stat3']; ?>
</div>
                <div class="marg"><?php echo @ADMIN_MENU_STAT2; ?>
: <?php echo $this->_tpl_vars['totals']['count_stat4']; ?>
</div>
                <div class="marg"><?php echo @ADMIN_STAT_ALL_TODAY; ?>
: <?php echo $this->_tpl_vars['totals']['count_stat1']; ?>
</div>
                <div class="marg"><?php echo @ADMIN_STAT_TODAY; ?>
: <?php echo $this->_tpl_vars['totals']['count_stat2']; ?>
</div><br>
                 <div class="marg"><a href="<?php echo @ADMIN_FILE; ?>
?dpt=reports&amp;sub=customer_log"><?php echo @ADMIN_CUSTOMER_LOG; ?>
</a></div>
                       <div class="marg"><a href="<?php echo @ADMIN_FILE; ?>
?dpt=reports&amp;sub=category_viewed_times"><?php echo @ADMIN_CATEGORY_VIEWED_TIMES; ?>
</a></div>
        </td>
 </tr>

</table>
</td>

<td width="33%" valign="top" align=left>
<table class="adw">

 <tr>
  <td valign="top" align=right><img src="data/admin/help.gif" alt="" style="margin-right: 6px;"></td>
        <td valign="top" align=left><u><b><?php echo @ADMIN_MENU_HELP; ?>
</b></u><br><br><div class="marg"><a href="http://shopcms.ru"><?php echo @ADMIN_MENU_LINK_1; ?>
</a></div>
                <div class="marg"><a href="http://shopcms.ru/contacts.html"><?php echo @ADMIN_MENU_LINK_2; ?>
</a></div><br>
                <div class="marg"><a href="http://shopcms.ru/sysinfo.html"><?php echo @ADMIN_MENU_LINK_3; ?>
</a></div>
                <div class="marg"><a href="http://shopcms.ru/news.html"><?php echo @ADMIN_MENU_LINK_4; ?>
</a></div>
                      <div class="marg"><a href="http://shopcms.ru/files.html"><?php echo @ADMIN_MENU_LINK_5; ?>
</a></div>
                       <div class="marg"><a href="http://shopcms.ru/license.html"><?php echo @ADMIN_MENU_LINK_6; ?>
</a></div>
        </td>
 </tr>

</table>
</td>
</tr>
</table>

<?php endif; ?>
<br><br>
</td>
        </tr>
       </table>
    </td>
  </tr>
</table>