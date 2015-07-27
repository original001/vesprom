<?php /* Smarty version 2.6.22, created on 2015-07-20 08:49:19
         compiled from admin/conf_blocks_edit.tpl.html */ ?>
<?php if ($this->_tpl_vars['edit']): ?>
<form action="<?php echo @ADMIN_FILE; ?>
?dpt=conf&amp;sub=blocks_edit&amp;edit=<?php echo $this->_tpl_vars['blocks_edit']['bid']; ?>
" method="post" name="formaxp3" id="formaxp3">
<table class="adn">
<tr class="linsz">
<td align="left" style="padding-top: 0;"><span class="titlecol2"><?php echo @BLOCKS_NAME; ?>
</span></td>
</tr>
<tr>
<td align="left"><input name="block_name" type="text" value='<?php echo $this->_tpl_vars['blocks_edit']['title']; ?>
' style="width: 500px;" class="textp"></td>
</tr></table>
<table class="adn"><tr><td class="se5"></td></tr></table>
<?php if ($this->_tpl_vars['blocks_edit']['html'] == 0): ?>
<table class="adn">
<tr class="linsz">
<td align="left"><span class="titlecol2"><?php echo @BLOCK_TEXT_CONT; ?>
</span></td>
</tr>
<tr>
<td>
<textarea name="block_content" id="blockarea" class="admin"><?php echo $this->_tpl_vars['blocks_edit']['content']; ?>
</textarea>
</td></tr></table>
<table class="adn"><tr><td class="se6"></td></tr></table>
<?php if (@CONF_EDITOR): ?>
<?php echo '
<script type="text/javascript" src="fckeditor/fckeditor.js"></script>
<script type="text/javascript" src="fckeditor/ckfinder/ckfinder.js"></script>
<script type="text/javascript">
window.onload = function()
{
var oFCKeditor = new FCKeditor( \'blockarea\',720,346) ;
'; ?>
<?php 
$dir1 = dirname($_SERVER['PHP_SELF']);
$sourcessrand = array("//" => "/", "\\" => "/");
$dir1 = strtr($dir1, $sourcessrand);
if ($dir1 != "/") $dir2 = "/"; else $dir2 = "";
echo "\n";
echo "oFCKeditor.BasePath = \"".$dir1.$dir2."fckeditor/\";\n";
 ?><?php echo '
oFCKeditor.ReplaceTextarea() ;
}
</script>
'; ?>

<?php endif; ?>
<?php else: ?>
<?php echo @BLOCKS_HTML; ?>
<?php echo @CONF_DEFAULT_TEMPLATE; ?>
<?php echo @BLOCKS_HTML2; ?>
<?php echo $this->_tpl_vars['blocks_edit']['url']; ?>
"<input type="hidden" name="block_content" value="">
<table width="100%" border="0" cellpadding="0" cellspacing="0"><tr><td height="18"></td></tr></table>
<?php endif; ?>

<?php if ($this->_tpl_vars['blocks_edit']['about']): ?><b><?php echo @BLOCK_EDIT_ABOUT; ?>
</b>: <?php echo $this->_tpl_vars['blocks_edit']['about']; ?>

<table width="100%" border="0" cellpadding="0" cellspacing="0"><tr><td height="18"></td></tr></table>
<?php endif; ?>
<?php echo @BLOCK_EDIT_1; ?>
: <select name='block_select_where' >
<option value="0" <?php if ($this->_tpl_vars['blocks_edit']['which'] == 0): ?>selected<?php endif; ?>> <?php echo @BLOCK_EDIT_4; ?>
 </option>
<option value="1" <?php if ($this->_tpl_vars['blocks_edit']['which'] == 1): ?>selected<?php endif; ?>> <?php echo @BLOCK_EDIT_5; ?>
 </option>
<option value="2" <?php if ($this->_tpl_vars['blocks_edit']['which'] == 2): ?>selected<?php endif; ?>> <?php echo @BLOCK_EDIT_5_NEW; ?>
 </option>
</select>&nbsp;&nbsp;&nbsp;&nbsp;<?php echo @BLOCK_EDIT_2; ?>
: <select name='block_select_line' >
<option value="1" <?php if ($this->_tpl_vars['blocks_edit']['bposition'] == 1): ?>selected<?php endif; ?>> <?php echo @BLOCK_EDIT_6; ?>
 </option>
<option value="2" <?php if ($this->_tpl_vars['blocks_edit']['bposition'] == 2): ?>selected<?php endif; ?>> <?php echo @BLOCK_EDIT_7; ?>
 </option>
<option value="3" <?php if ($this->_tpl_vars['blocks_edit']['bposition'] == 3): ?>selected<?php endif; ?>> <?php echo @BLOCK_EDIT_8; ?>
 </option>
<option value="4" <?php if ($this->_tpl_vars['blocks_edit']['bposition'] == 4): ?>selected<?php endif; ?>> <?php echo @BLOCK_EDIT_9; ?>
 </option>
</select>&nbsp;&nbsp;&nbsp;&nbsp;<?php echo @BLOCK_EDIT_3; ?>
: <select name='block_select_active' >
<option value="1" <?php if ($this->_tpl_vars['blocks_edit']['active'] == 1): ?>selected<?php endif; ?>> <?php echo @BLOCK_EDIT_10; ?>
 </option>
<option value="0" <?php if ($this->_tpl_vars['blocks_edit']['active'] == 0): ?>selected<?php endif; ?>> <?php echo @BLOCK_EDIT_11; ?>
 </option>
</select>&nbsp;&nbsp;<select name='block_select_admin' >
<option value="0" <?php if ($this->_tpl_vars['blocks_edit']['admin'] == 0): ?>selected<?php endif; ?>> <?php echo @BLOCK_EDIT_ADMIN2; ?>
 </option>
<option value="1" <?php if ($this->_tpl_vars['blocks_edit']['admin'] == 1): ?>selected<?php endif; ?>> <?php echo @BLOCK_EDIT_ADMIN3; ?>
 </option>
</select><table class="adn"><tr><td class="se6"></td></tr></table>
<table class="adw">
<tr class="linsz">
<td align="left"><span class="titlecol2"><?php echo @BLOCK_EDIT_NEW_CHOISE; ?>
</span></td>
<td align="left" style="padding-left: 16px;"><span class="titlecol2"><?php echo @BLOCK_EDIT_ADD_CHOISE; ?>
</span></td>
</tr>
<tr>
<td align="left">
<select name="spage_select[]" size="10" multiple="multiple" style="width: 350px">
<?php $this->assign('isw', 1); ?>
<option value="nonepage" <?php if (in_array ( "home.tpl.html" , $this->_tpl_vars['blocks_edit']['pages'] ) || in_array ( "activation_orders.tpl.html" , $this->_tpl_vars['blocks_edit']['pages'] ) || in_array ( "deactivation_orders.tpl.html" , $this->_tpl_vars['blocks_edit']['pages'] ) || in_array ( "address_book.tpl.html" , $this->_tpl_vars['blocks_edit']['pages'] ) || in_array ( "address_editor.tpl.html" , $this->_tpl_vars['blocks_edit']['pages'] ) || in_array ( "category.tpl.html" , $this->_tpl_vars['blocks_edit']['pages'] ) || in_array ( "category_search_result.tpl.html" , $this->_tpl_vars['blocks_edit']['pages'] ) || in_array ( "comparison_products.tpl.html" , $this->_tpl_vars['blocks_edit']['pages'] ) || in_array ( "contact_info.tpl.html" , $this->_tpl_vars['blocks_edit']['pages'] ) || in_array ( "customer_survey_result.tpl.html" , $this->_tpl_vars['blocks_edit']['pages'] ) || in_array ( "feedback.tpl.html" , $this->_tpl_vars['blocks_edit']['pages'] ) || in_array ( "links_exchange.tpl.html" , $this->_tpl_vars['blocks_edit']['pages'] ) || in_array ( "order2_shipping.tpl.html" , $this->_tpl_vars['blocks_edit']['pages'] ) || in_array ( "order2_shipping_quick.tpl.html" , $this->_tpl_vars['blocks_edit']['pages'] ) || in_array ( "order3_billing.tpl.html" , $this->_tpl_vars['blocks_edit']['pages'] ) || in_array ( "order3_billing_quick.tpl.html" , $this->_tpl_vars['blocks_edit']['pages'] ) || in_array ( "order4_confirmation.tpl.html" , $this->_tpl_vars['blocks_edit']['pages'] ) || in_array ( "order4_confirmation_quick.tpl.html" , $this->_tpl_vars['blocks_edit']['pages'] ) || in_array ( "order_history.tpl.html" , $this->_tpl_vars['blocks_edit']['pages'] ) || in_array ( "password.tpl.html" , $this->_tpl_vars['blocks_edit']['pages'] ) || in_array ( "pricelist.tpl.html" , $this->_tpl_vars['blocks_edit']['pages'] ) || in_array ( "product_detailed.tpl.html" , $this->_tpl_vars['blocks_edit']['pages'] ) || in_array ( "product_discussion.tpl.html" , $this->_tpl_vars['blocks_edit']['pages'] ) || in_array ( "reg_successful.tpl.html" , $this->_tpl_vars['blocks_edit']['pages'] ) || in_array ( "register.tpl.html" , $this->_tpl_vars['blocks_edit']['pages'] ) || in_array ( "register_authorization.tpl.html" , $this->_tpl_vars['blocks_edit']['pages'] ) || in_array ( "register_quick.tpl.html" , $this->_tpl_vars['blocks_edit']['pages'] ) || in_array ( "search_simple.tpl.html" , $this->_tpl_vars['blocks_edit']['pages'] ) || in_array ( "shopping_cart.tpl.html" , $this->_tpl_vars['blocks_edit']['pages'] ) || in_array ( "show_aux_page.tpl.html" , $this->_tpl_vars['blocks_edit']['pages'] ) || in_array ( "show_full_news.tpl.html" , $this->_tpl_vars['blocks_edit']['pages'] ) || in_array ( "show_news.tpl.html" , $this->_tpl_vars['blocks_edit']['pages'] ) || in_array ( "subscribe.tpl.html" , $this->_tpl_vars['blocks_edit']['pages'] ) || in_array ( "user_account.tpl.html" , $this->_tpl_vars['blocks_edit']['pages'] ) || in_array ( "visit_history.tpl.html" , $this->_tpl_vars['blocks_edit']['pages'] )): ?><?php $this->assign('isw', 2); ?><?php endif; ?><?php if ($this->_tpl_vars['isw'] == 1): ?>selected<?php endif; ?>><?php echo @ADMIN_NOT_VALUED; ?>
</option>
                <option value="home.tpl.html" <?php if (in_array ( "home.tpl.html" , $this->_tpl_vars['blocks_edit']['pages'] )): ?>selected<?php endif; ?>><?php echo @BLOCK_EDIT_PAGE_1; ?>
</option>
                <option value="activation_orders.tpl.html" <?php if (in_array ( "activation_orders.tpl.html" , $this->_tpl_vars['blocks_edit']['pages'] )): ?>selected<?php endif; ?>><?php echo @BLOCK_EDIT_PAGE_2; ?>
</option>
                <option value="deactivation_orders.tpl.html" <?php if (in_array ( "deactivation_orders.tpl.html" , $this->_tpl_vars['blocks_edit']['pages'] )): ?>selected<?php endif; ?>><?php echo @BLOCK_EDIT_PAGE_3; ?>
</option>
                <option value="address_book.tpl.html" <?php if (in_array ( "address_book.tpl.html" , $this->_tpl_vars['blocks_edit']['pages'] )): ?>selected<?php endif; ?>><?php echo @BLOCK_EDIT_PAGE_4; ?>
</option>
                <option value="address_editor.tpl.html" <?php if (in_array ( "address_editor.tpl.html" , $this->_tpl_vars['blocks_edit']['pages'] )): ?>selected<?php endif; ?>><?php echo @BLOCK_EDIT_PAGE_5; ?>
</option>
                <option value="category.tpl.html" <?php if (in_array ( "category.tpl.html" , $this->_tpl_vars['blocks_edit']['pages'] )): ?>selected<?php endif; ?>><?php echo @BLOCK_EDIT_PAGE_6; ?>
</option>
                <option value="category_search_result.tpl.html" <?php if (in_array ( "category_search_result.tpl.html" , $this->_tpl_vars['blocks_edit']['pages'] )): ?>selected<?php endif; ?>><?php echo @BLOCK_EDIT_PAGE_7; ?>
</option>
                <option value="comparison_products.tpl.html" <?php if (in_array ( "comparison_products.tpl.html" , $this->_tpl_vars['blocks_edit']['pages'] )): ?>selected<?php endif; ?>><?php echo @BLOCK_EDIT_PAGE_8; ?>
</option>
                <option value="contact_info.tpl.html" <?php if (in_array ( "contact_info.tpl.html" , $this->_tpl_vars['blocks_edit']['pages'] )): ?>selected<?php endif; ?>><?php echo @BLOCK_EDIT_PAGE_9; ?>
</option>
                <option value="customer_survey_result.tpl.html" <?php if (in_array ( "customer_survey_result.tpl.html" , $this->_tpl_vars['blocks_edit']['pages'] )): ?>selected<?php endif; ?>><?php echo @BLOCK_EDIT_PAGE_10; ?>
</option>
                <option value="feedback.tpl.html" <?php if (in_array ( "feedback.tpl.html" , $this->_tpl_vars['blocks_edit']['pages'] )): ?>selected<?php endif; ?>><?php echo @BLOCK_EDIT_PAGE_11; ?>
</option>
                <option value="links_exchange.tpl.html" <?php if (in_array ( "links_exchange.tpl.html" , $this->_tpl_vars['blocks_edit']['pages'] )): ?>selected<?php endif; ?>><?php echo @BLOCK_EDIT_PAGE_12; ?>
</option>
                <option value="order2_shipping.tpl.html" <?php if (in_array ( "order2_shipping.tpl.html" , $this->_tpl_vars['blocks_edit']['pages'] )): ?>selected<?php endif; ?>><?php echo @BLOCK_EDIT_PAGE_13; ?>
</option>
                <option value="order2_shipping_quick.tpl.html" <?php if (in_array ( "order2_shipping_quick.tpl.html" , $this->_tpl_vars['blocks_edit']['pages'] )): ?>selected<?php endif; ?>><?php echo @BLOCK_EDIT_PAGE_14; ?>
</option>
                <option value="order3_billing.tpl.html" <?php if (in_array ( "order3_billing.tpl.html" , $this->_tpl_vars['blocks_edit']['pages'] )): ?>selected<?php endif; ?>><?php echo @BLOCK_EDIT_PAGE_15; ?>
</option>
                <option value="order3_billing_quick.tpl.html" <?php if (in_array ( "order3_billing_quick.tpl.html" , $this->_tpl_vars['blocks_edit']['pages'] )): ?>selected<?php endif; ?>><?php echo @BLOCK_EDIT_PAGE_16; ?>
</option>
                <option value="order4_confirmation.tpl.html" <?php if (in_array ( "order4_confirmation.tpl.html" , $this->_tpl_vars['blocks_edit']['pages'] )): ?>selected<?php endif; ?>><?php echo @BLOCK_EDIT_PAGE_17; ?>
</option>
                <option value="order4_confirmation_quick.tpl.html" <?php if (in_array ( "order4_confirmation_quick.tpl.html" , $this->_tpl_vars['blocks_edit']['pages'] )): ?>selected<?php endif; ?>><?php echo @BLOCK_EDIT_PAGE_18; ?>
</option>
                <option value="order_history.tpl.html" <?php if (in_array ( "order_history.tpl.html" , $this->_tpl_vars['blocks_edit']['pages'] )): ?>selected<?php endif; ?>><?php echo @BLOCK_EDIT_PAGE_19; ?>
</option>
                <option value="password.tpl.html" <?php if (in_array ( "password.tpl.html" , $this->_tpl_vars['blocks_edit']['pages'] )): ?>selected<?php endif; ?>><?php echo @BLOCK_EDIT_PAGE_20; ?>
</option>
                <option value="pricelist.tpl.html" <?php if (in_array ( "pricelist.tpl.html" , $this->_tpl_vars['blocks_edit']['pages'] )): ?>selected<?php endif; ?>><?php echo @BLOCK_EDIT_PAGE_21; ?>
</option>
                <option value="product_detailed.tpl.html" <?php if (in_array ( "product_detailed.tpl.html" , $this->_tpl_vars['blocks_edit']['pages'] )): ?>selected<?php endif; ?>><?php echo @BLOCK_EDIT_PAGE_22; ?>
</option>
                <option value="product_discussion.tpl.html" <?php if (in_array ( "product_discussion.tpl.html" , $this->_tpl_vars['blocks_edit']['pages'] )): ?>selected<?php endif; ?>><?php echo @BLOCK_EDIT_PAGE_23; ?>
</option>
                <option value="reg_successful.tpl.html" <?php if (in_array ( "reg_successful.tpl.html" , $this->_tpl_vars['blocks_edit']['pages'] )): ?>selected<?php endif; ?>><?php echo @BLOCK_EDIT_PAGE_24; ?>
</option>
                <option value="register.tpl.html" <?php if (in_array ( "register.tpl.html" , $this->_tpl_vars['blocks_edit']['pages'] )): ?>selected<?php endif; ?>><?php echo @BLOCK_EDIT_PAGE_25; ?>
</option>
                <option value="register_authorization.tpl.html" <?php if (in_array ( "register_authorization.tpl.html" , $this->_tpl_vars['blocks_edit']['pages'] )): ?>selected<?php endif; ?>><?php echo @BLOCK_EDIT_PAGE_26; ?>
</option>
                <option value="register_quick.tpl.html" <?php if (in_array ( "register_quick.tpl.html" , $this->_tpl_vars['blocks_edit']['pages'] )): ?>selected<?php endif; ?>><?php echo @BLOCK_EDIT_PAGE_27; ?>
</option>
                <option value="search_simple.tpl.html" <?php if (in_array ( "search_simple.tpl.html" , $this->_tpl_vars['blocks_edit']['pages'] )): ?>selected<?php endif; ?>><?php echo @BLOCK_EDIT_PAGE_28; ?>
</option>
                <option value="shopping_cart.tpl.html" <?php if (in_array ( "shopping_cart.tpl.html" , $this->_tpl_vars['blocks_edit']['pages'] )): ?>selected<?php endif; ?>><?php echo @BLOCK_EDIT_PAGE_29; ?>
</option>
                <option value="show_aux_page.tpl.html" <?php if (in_array ( "show_aux_page.tpl.html" , $this->_tpl_vars['blocks_edit']['pages'] )): ?>selected<?php endif; ?>><?php echo @BLOCK_EDIT_PAGE_30; ?>
</option>
                <option value="show_full_news.tpl.html" <?php if (in_array ( "show_full_news.tpl.html" , $this->_tpl_vars['blocks_edit']['pages'] )): ?>selected<?php endif; ?>><?php echo @BLOCK_EDIT_PAGE_31; ?>
</option>
                <option value="show_news.tpl.html" <?php if (in_array ( "show_news.tpl.html" , $this->_tpl_vars['blocks_edit']['pages'] )): ?>selected<?php endif; ?>><?php echo @BLOCK_EDIT_PAGE_32; ?>
</option>
                <option value="subscribe.tpl.html" <?php if (in_array ( "subscribe.tpl.html" , $this->_tpl_vars['blocks_edit']['pages'] )): ?>selected<?php endif; ?>><?php echo @BLOCK_EDIT_PAGE_33; ?>
</option>
                <option value="user_account.tpl.html" <?php if (in_array ( "user_account.tpl.html" , $this->_tpl_vars['blocks_edit']['pages'] )): ?>selected<?php endif; ?>><?php echo @BLOCK_EDIT_PAGE_34; ?>
</option>
                <option value="visit_history.tpl.html" <?php if (in_array ( "visit_history.tpl.html" , $this->_tpl_vars['blocks_edit']['pages'] )): ?>selected<?php endif; ?>><?php echo @BLOCK_EDIT_PAGE_35; ?>
</option>
</select>
</td>
<td align="left" style="padding-left: 16px;">
<select name="dpage_select[]" size="10" multiple="multiple" style="width: 350px;">
<?php $this->assign('istval', 1); ?>
<option value="nonepage" <?php unset($this->_sections['ist']);
$this->_sections['ist']['name'] = 'ist';
$this->_sections['ist']['loop'] = is_array($_loop=$this->_tpl_vars['aux_pages']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['ist']['show'] = true;
$this->_sections['ist']['max'] = $this->_sections['ist']['loop'];
$this->_sections['ist']['step'] = 1;
$this->_sections['ist']['start'] = $this->_sections['ist']['step'] > 0 ? 0 : $this->_sections['ist']['loop']-1;
if ($this->_sections['ist']['show']) {
    $this->_sections['ist']['total'] = $this->_sections['ist']['loop'];
    if ($this->_sections['ist']['total'] == 0)
        $this->_sections['ist']['show'] = false;
} else
    $this->_sections['ist']['total'] = 0;
if ($this->_sections['ist']['show']):

            for ($this->_sections['ist']['index'] = $this->_sections['ist']['start'], $this->_sections['ist']['iteration'] = 1;
                 $this->_sections['ist']['iteration'] <= $this->_sections['ist']['total'];
                 $this->_sections['ist']['index'] += $this->_sections['ist']['step'], $this->_sections['ist']['iteration']++):
$this->_sections['ist']['rownum'] = $this->_sections['ist']['iteration'];
$this->_sections['ist']['index_prev'] = $this->_sections['ist']['index'] - $this->_sections['ist']['step'];
$this->_sections['ist']['index_next'] = $this->_sections['ist']['index'] + $this->_sections['ist']['step'];
$this->_sections['ist']['first']      = ($this->_sections['ist']['iteration'] == 1);
$this->_sections['ist']['last']       = ($this->_sections['ist']['iteration'] == $this->_sections['ist']['total']);
?><?php if (in_array ( $this->_tpl_vars['aux_pages'][$this->_sections['ist']['index']]['aux_page_ID'] , $this->_tpl_vars['blocks_edit']['dpages'] )): ?><?php $this->assign('istval', 2); ?><?php endif; ?><?php endfor; endif; ?><?php if ($this->_tpl_vars['istval'] == 1): ?>selected<?php endif; ?>><?php echo @ADMIN_NOT_VALUED; ?>
</option>
<?php unset($this->_sections['i']);
$this->_sections['i']['name'] = 'i';
$this->_sections['i']['loop'] = is_array($_loop=$this->_tpl_vars['aux_pages']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['i']['show'] = true;
$this->_sections['i']['max'] = $this->_sections['i']['loop'];
$this->_sections['i']['step'] = 1;
$this->_sections['i']['start'] = $this->_sections['i']['step'] > 0 ? 0 : $this->_sections['i']['loop']-1;
if ($this->_sections['i']['show']) {
    $this->_sections['i']['total'] = $this->_sections['i']['loop'];
    if ($this->_sections['i']['total'] == 0)
        $this->_sections['i']['show'] = false;
} else
    $this->_sections['i']['total'] = 0;
if ($this->_sections['i']['show']):

            for ($this->_sections['i']['index'] = $this->_sections['i']['start'], $this->_sections['i']['iteration'] = 1;
                 $this->_sections['i']['iteration'] <= $this->_sections['i']['total'];
                 $this->_sections['i']['index'] += $this->_sections['i']['step'], $this->_sections['i']['iteration']++):
$this->_sections['i']['rownum'] = $this->_sections['i']['iteration'];
$this->_sections['i']['index_prev'] = $this->_sections['i']['index'] - $this->_sections['i']['step'];
$this->_sections['i']['index_next'] = $this->_sections['i']['index'] + $this->_sections['i']['step'];
$this->_sections['i']['first']      = ($this->_sections['i']['iteration'] == 1);
$this->_sections['i']['last']       = ($this->_sections['i']['iteration'] == $this->_sections['i']['total']);
?>
<option value="<?php echo $this->_tpl_vars['aux_pages'][$this->_sections['i']['index']]['aux_page_ID']; ?>
" <?php if (in_array ( $this->_tpl_vars['aux_pages'][$this->_sections['i']['index']]['aux_page_ID'] , $this->_tpl_vars['blocks_edit']['dpages'] )): ?>selected<?php endif; ?>><?php echo $this->_tpl_vars['aux_pages'][$this->_sections['i']['index']]['aux_page_name']; ?>
</option>
<?php endfor; endif; ?>
</select>
</td>
</tr></table>
<table class="adn"><tr><td class="se6"></td></tr></table>
<table class="adw">
<tr class="linsz">
<td align="left"><span class="titlecol2"><?php echo @BLOCK_EDIT_DCAT; ?>
</span></td>
<td align="left" style="padding-left: 16px;"><span class="titlecol2"><?php echo @BLOCK_EDIT_DPROD; ?>
</span></td>
</tr>
<tr>
<td align="left">
<select name="categories_select[]" size="10" multiple="multiple" style="width: 350px">
<?php $this->assign('issval', 1); ?>
<option value="nonepage" <?php unset($this->_sections['iss']);
$this->_sections['iss']['name'] = 'iss';
$this->_sections['iss']['loop'] = is_array($_loop=$this->_tpl_vars['cats']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['iss']['show'] = true;
$this->_sections['iss']['max'] = $this->_sections['iss']['loop'];
$this->_sections['iss']['step'] = 1;
$this->_sections['iss']['start'] = $this->_sections['iss']['step'] > 0 ? 0 : $this->_sections['iss']['loop']-1;
if ($this->_sections['iss']['show']) {
    $this->_sections['iss']['total'] = $this->_sections['iss']['loop'];
    if ($this->_sections['iss']['total'] == 0)
        $this->_sections['iss']['show'] = false;
} else
    $this->_sections['iss']['total'] = 0;
if ($this->_sections['iss']['show']):

            for ($this->_sections['iss']['index'] = $this->_sections['iss']['start'], $this->_sections['iss']['iteration'] = 1;
                 $this->_sections['iss']['iteration'] <= $this->_sections['iss']['total'];
                 $this->_sections['iss']['index'] += $this->_sections['iss']['step'], $this->_sections['iss']['iteration']++):
$this->_sections['iss']['rownum'] = $this->_sections['iss']['iteration'];
$this->_sections['iss']['index_prev'] = $this->_sections['iss']['index'] - $this->_sections['iss']['step'];
$this->_sections['iss']['index_next'] = $this->_sections['iss']['index'] + $this->_sections['iss']['step'];
$this->_sections['iss']['first']      = ($this->_sections['iss']['iteration'] == 1);
$this->_sections['iss']['last']       = ($this->_sections['iss']['iteration'] == $this->_sections['iss']['total']);
?><?php if (in_array ( $this->_tpl_vars['cats'][$this->_sections['iss']['index']]['categoryID'] , $this->_tpl_vars['blocks_edit']['categories'] )): ?><?php $this->assign('issval', 2); ?><?php endif; ?><?php endfor; endif; ?><?php if ($this->_tpl_vars['issval'] == 1): ?>selected<?php endif; ?>><?php echo @ADMIN_NOT_VALUED; ?>
</option>
<?php unset($this->_sections['im']);
$this->_sections['im']['name'] = 'im';
$this->_sections['im']['loop'] = is_array($_loop=$this->_tpl_vars['cats']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['im']['show'] = true;
$this->_sections['im']['max'] = $this->_sections['im']['loop'];
$this->_sections['im']['step'] = 1;
$this->_sections['im']['start'] = $this->_sections['im']['step'] > 0 ? 0 : $this->_sections['im']['loop']-1;
if ($this->_sections['im']['show']) {
    $this->_sections['im']['total'] = $this->_sections['im']['loop'];
    if ($this->_sections['im']['total'] == 0)
        $this->_sections['im']['show'] = false;
} else
    $this->_sections['im']['total'] = 0;
if ($this->_sections['im']['show']):

            for ($this->_sections['im']['index'] = $this->_sections['im']['start'], $this->_sections['im']['iteration'] = 1;
                 $this->_sections['im']['iteration'] <= $this->_sections['im']['total'];
                 $this->_sections['im']['index'] += $this->_sections['im']['step'], $this->_sections['im']['iteration']++):
$this->_sections['im']['rownum'] = $this->_sections['im']['iteration'];
$this->_sections['im']['index_prev'] = $this->_sections['im']['index'] - $this->_sections['im']['step'];
$this->_sections['im']['index_next'] = $this->_sections['im']['index'] + $this->_sections['im']['step'];
$this->_sections['im']['first']      = ($this->_sections['im']['iteration'] == 1);
$this->_sections['im']['last']       = ($this->_sections['im']['iteration'] == $this->_sections['im']['total']);
?>
<option value="<?php echo $this->_tpl_vars['cats'][$this->_sections['im']['index']]['categoryID']; ?>
" <?php if (in_array ( $this->_tpl_vars['cats'][$this->_sections['im']['index']]['categoryID'] , $this->_tpl_vars['blocks_edit']['categories'] )): ?>selected<?php endif; ?>><?php unset($this->_sections['j']);
$this->_sections['j']['name'] = 'j';
$this->_sections['j']['loop'] = is_array($_loop=$this->_tpl_vars['cats'][$this->_sections['im']['index']]['level']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['j']['max'] = (int)$this->_tpl_vars['cats'][$this->_sections['im']['index']]['level'];
$this->_sections['j']['show'] = true;
if ($this->_sections['j']['max'] < 0)
    $this->_sections['j']['max'] = $this->_sections['j']['loop'];
$this->_sections['j']['step'] = 1;
$this->_sections['j']['start'] = $this->_sections['j']['step'] > 0 ? 0 : $this->_sections['j']['loop']-1;
if ($this->_sections['j']['show']) {
    $this->_sections['j']['total'] = min(ceil(($this->_sections['j']['step'] > 0 ? $this->_sections['j']['loop'] - $this->_sections['j']['start'] : $this->_sections['j']['start']+1)/abs($this->_sections['j']['step'])), $this->_sections['j']['max']);
    if ($this->_sections['j']['total'] == 0)
        $this->_sections['j']['show'] = false;
} else
    $this->_sections['j']['total'] = 0;
if ($this->_sections['j']['show']):

            for ($this->_sections['j']['index'] = $this->_sections['j']['start'], $this->_sections['j']['iteration'] = 1;
                 $this->_sections['j']['iteration'] <= $this->_sections['j']['total'];
                 $this->_sections['j']['index'] += $this->_sections['j']['step'], $this->_sections['j']['iteration']++):
$this->_sections['j']['rownum'] = $this->_sections['j']['iteration'];
$this->_sections['j']['index_prev'] = $this->_sections['j']['index'] - $this->_sections['j']['step'];
$this->_sections['j']['index_next'] = $this->_sections['j']['index'] + $this->_sections['j']['step'];
$this->_sections['j']['first']      = ($this->_sections['j']['iteration'] == 1);
$this->_sections['j']['last']       = ($this->_sections['j']['iteration'] == $this->_sections['j']['total']);
?>&nbsp;&nbsp;&nbsp;<?php endfor; endif; ?><?php echo $this->_tpl_vars['cats'][$this->_sections['im']['index']]['name']; ?>
</option>
<?php endfor; endif; ?>
</select>
</td>
<td align="left" style="padding-left: 16px;" valign="top">
<textarea name="products_select" style="width: 348px; height: 92px;">
<?php unset($this->_sections['z']);
$this->_sections['z']['name'] = 'z';
$this->_sections['z']['loop'] = is_array($_loop=$this->_tpl_vars['blocks_edit']['products']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['z']['show'] = true;
$this->_sections['z']['max'] = $this->_sections['z']['loop'];
$this->_sections['z']['step'] = 1;
$this->_sections['z']['start'] = $this->_sections['z']['step'] > 0 ? 0 : $this->_sections['z']['loop']-1;
if ($this->_sections['z']['show']) {
    $this->_sections['z']['total'] = $this->_sections['z']['loop'];
    if ($this->_sections['z']['total'] == 0)
        $this->_sections['z']['show'] = false;
} else
    $this->_sections['z']['total'] = 0;
if ($this->_sections['z']['show']):

            for ($this->_sections['z']['index'] = $this->_sections['z']['start'], $this->_sections['z']['iteration'] = 1;
                 $this->_sections['z']['iteration'] <= $this->_sections['z']['total'];
                 $this->_sections['z']['index'] += $this->_sections['z']['step'], $this->_sections['z']['iteration']++):
$this->_sections['z']['rownum'] = $this->_sections['z']['iteration'];
$this->_sections['z']['index_prev'] = $this->_sections['z']['index'] - $this->_sections['z']['step'];
$this->_sections['z']['index_next'] = $this->_sections['z']['index'] + $this->_sections['z']['step'];
$this->_sections['z']['first']      = ($this->_sections['z']['iteration'] == 1);
$this->_sections['z']['last']       = ($this->_sections['z']['iteration'] == $this->_sections['z']['total']);
?>
<?php echo $this->_tpl_vars['blocks_edit']['products'][$this->_sections['z']['index']]; ?>

<?php endfor; endif; ?>
</textarea>
<div align="left"><br><?php echo @BLOCK_EDIT_PSDESCRIPT; ?>
</div>
</td>
</tr>
</table>
          <table class="adn"><tr><td height="18"></td></tr></table>
<a href="#" onclick="document.getElementById('formaxp3').submit(); return false" class="inl"><?php echo @SAVE_BUTTON; ?>
</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a href="<?php echo @ADMIN_FILE; ?>
?dpt=conf&amp;sub=blocks_edit" class="inl"><?php echo @ADMIN_TX2; ?>
</a>
<input type=hidden value='<?php echo @SAVE_BUTTON; ?>
' name='save'>
</form>
<?php elseif ($this->_tpl_vars['add_new_file']): ?>
<form action="<?php echo @ADMIN_FILE; ?>
?dpt=conf&amp;sub=blocks_edit&amp;add_new=yes&amp;file=yes" method="post" name="formaxp3" id="formaxp3">
  <table class="adn">
<tr class="linsz">
<td align="left" style="padding-top: 0;"><span class="titlecol2"><?php echo @BLOCKS_NAME; ?>
</span></td>
</tr>
<tr>
<td align="left"><input name="block_name" type="text" value='' style="width: 500px;" class="textp"></td>
</tr></table>
<table class="adn"><tr><td class="se5"></td></tr></table>
<b><?php echo @BLOCK_TEXT_FILE; ?>
:</b> <select name='block_select_file' >
<?php unset($this->_sections['i']);
$this->_sections['i']['name'] = 'i';
$this->_sections['i']['loop'] = is_array($_loop=$this->_tpl_vars['blocklist']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['i']['show'] = true;
$this->_sections['i']['max'] = $this->_sections['i']['loop'];
$this->_sections['i']['step'] = 1;
$this->_sections['i']['start'] = $this->_sections['i']['step'] > 0 ? 0 : $this->_sections['i']['loop']-1;
if ($this->_sections['i']['show']) {
    $this->_sections['i']['total'] = $this->_sections['i']['loop'];
    if ($this->_sections['i']['total'] == 0)
        $this->_sections['i']['show'] = false;
} else
    $this->_sections['i']['total'] = 0;
if ($this->_sections['i']['show']):

            for ($this->_sections['i']['index'] = $this->_sections['i']['start'], $this->_sections['i']['iteration'] = 1;
                 $this->_sections['i']['iteration'] <= $this->_sections['i']['total'];
                 $this->_sections['i']['index'] += $this->_sections['i']['step'], $this->_sections['i']['iteration']++):
$this->_sections['i']['rownum'] = $this->_sections['i']['iteration'];
$this->_sections['i']['index_prev'] = $this->_sections['i']['index'] - $this->_sections['i']['step'];
$this->_sections['i']['index_next'] = $this->_sections['i']['index'] + $this->_sections['i']['step'];
$this->_sections['i']['first']      = ($this->_sections['i']['iteration'] == 1);
$this->_sections['i']['last']       = ($this->_sections['i']['iteration'] == $this->_sections['i']['total']);
?>
<option value="<?php echo $this->_tpl_vars['blocklist'][$this->_sections['i']['index']]; ?>
"> <?php echo $this->_tpl_vars['blocklist'][$this->_sections['i']['index']]; ?>
 </option>
<?php endfor; endif; ?>
</select>
<table class="adn"><tr><td class="se6"></td></tr></table>
<input type="hidden" value="" name="block_content">
<?php echo @BLOCK_EDIT_1; ?>
: <select name='block_select_where' >
<option value="0" selected> <?php echo @BLOCK_EDIT_4; ?>
 </option>
<option value="1"> <?php echo @BLOCK_EDIT_5; ?>
 </option>
<option value="2"> <?php echo @BLOCK_EDIT_5_NEW; ?>
 </option>
</select>&nbsp;&nbsp;&nbsp;&nbsp;<?php echo @BLOCK_EDIT_2; ?>
: <select name='block_select_line' >
<option value="1" selected> <?php echo @BLOCK_EDIT_6; ?>
 </option>
<option value="2"> <?php echo @BLOCK_EDIT_7; ?>
 </option>
<option value="3"> <?php echo @BLOCK_EDIT_8; ?>
 </option>
<option value="4"> <?php echo @BLOCK_EDIT_9; ?>
 </option>
</select>&nbsp;&nbsp;&nbsp;&nbsp;<?php echo @BLOCK_EDIT_3; ?>
: <select name='block_select_active' >
<option value="1" selected> <?php echo @BLOCK_EDIT_10; ?>
 </option>
<option value="0"> <?php echo @BLOCK_EDIT_11; ?>
 </option>
</select>&nbsp;&nbsp;<select name='block_select_admin' >
<option value="0" selected> <?php echo @BLOCK_EDIT_ADMIN2; ?>
 </option>
<option value="1"> <?php echo @BLOCK_EDIT_ADMIN3; ?>
 </option>
</select><table class="adn"><tr><td class="se6"></td></tr></table>
<table class="adw">
<tr class="linsz">
<td align="left"><span class="titlecol2"><?php echo @BLOCK_EDIT_NEW_CHOISE; ?>
</span></td>
<td align="left" style="padding-left: 16px;"><span class="titlecol2"><?php echo @BLOCK_EDIT_ADD_CHOISE; ?>
</span></td>
</tr>
<tr>
<td align="left">
<select name="spage_select[]" size="10" multiple="multiple" style="width: 350px">
<option value="nonepage" selected><?php echo @ADMIN_NOT_VALUED; ?>
</option>
                <option value="home.tpl.html"><?php echo @BLOCK_EDIT_PAGE_1; ?>
</option>
                <option value="activation_orders.tpl.html"><?php echo @BLOCK_EDIT_PAGE_2; ?>
</option>
                <option value="deactivation_orders.tpl.html"><?php echo @BLOCK_EDIT_PAGE_3; ?>
</option>
                <option value="address_book.tpl.html"><?php echo @BLOCK_EDIT_PAGE_4; ?>
</option>
                <option value="address_editor.tpl.html"><?php echo @BLOCK_EDIT_PAGE_5; ?>
</option>
                <option value="category.tpl.html"><?php echo @BLOCK_EDIT_PAGE_6; ?>
</option>
                <option value="category_search_result.tpl.html"><?php echo @BLOCK_EDIT_PAGE_7; ?>
</option>
                <option value="comparison_products.tpl.html"><?php echo @BLOCK_EDIT_PAGE_8; ?>
</option>
                <option value="contact_info.tpl.html"><?php echo @BLOCK_EDIT_PAGE_9; ?>
</option>
                <option value="customer_survey_result.tpl.html"><?php echo @BLOCK_EDIT_PAGE_10; ?>
</option>
                <option value="feedback.tpl.html"><?php echo @BLOCK_EDIT_PAGE_11; ?>
</option>
                <option value="links_exchange.tpl.html"><?php echo @BLOCK_EDIT_PAGE_12; ?>
</option>
                <option value="order2_shipping.tpl.html"><?php echo @BLOCK_EDIT_PAGE_13; ?>
</option>
                <option value="order2_shipping_quick.tpl.html"><?php echo @BLOCK_EDIT_PAGE_14; ?>
</option>
                <option value="order3_billing.tpl.html"><?php echo @BLOCK_EDIT_PAGE_15; ?>
</option>
                <option value="order3_billing_quick.tpl.html"><?php echo @BLOCK_EDIT_PAGE_16; ?>
</option>
                <option value="order4_confirmation.tpl.html"><?php echo @BLOCK_EDIT_PAGE_17; ?>
</option>
                <option value="order4_confirmation_quick.tpl.html"><?php echo @BLOCK_EDIT_PAGE_18; ?>
</option>
                <option value="order_history.tpl.html"><?php echo @BLOCK_EDIT_PAGE_19; ?>
</option>
                <option value="password.tpl.html"><?php echo @BLOCK_EDIT_PAGE_20; ?>
</option>
                <option value="pricelist.tpl.html"><?php echo @BLOCK_EDIT_PAGE_21; ?>
</option>
                <option value="product_detailed.tpl.html"><?php echo @BLOCK_EDIT_PAGE_22; ?>
</option>
                <option value="product_discussion.tpl.html"><?php echo @BLOCK_EDIT_PAGE_23; ?>
</option>
                <option value="reg_successful.tpl.html"><?php echo @BLOCK_EDIT_PAGE_24; ?>
</option>
                <option value="register.tpl.html"><?php echo @BLOCK_EDIT_PAGE_25; ?>
</option>
                <option value="register_authorization.tpl.html"><?php echo @BLOCK_EDIT_PAGE_26; ?>
</option>
                <option value="register_quick.tpl.html"><?php echo @BLOCK_EDIT_PAGE_27; ?>
</option>
                <option value="search_simple.tpl.html"><?php echo @BLOCK_EDIT_PAGE_28; ?>
</option>
                <option value="shopping_cart.tpl.html"><?php echo @BLOCK_EDIT_PAGE_29; ?>
</option>
                <option value="show_aux_page.tpl.html"><?php echo @BLOCK_EDIT_PAGE_30; ?>
</option>
                <option value="show_full_news.tpl.html"><?php echo @BLOCK_EDIT_PAGE_31; ?>
</option>
                <option value="show_news.tpl.html"><?php echo @BLOCK_EDIT_PAGE_32; ?>
</option>
                <option value="subscribe.tpl.html"><?php echo @BLOCK_EDIT_PAGE_33; ?>
</option>
                <option value="user_account.tpl.html"><?php echo @BLOCK_EDIT_PAGE_34; ?>
</option>
                <option value="visit_history.tpl.html"><?php echo @BLOCK_EDIT_PAGE_35; ?>
</option>
</select>
</td>
<td align="left" style="padding-left: 16px;">
<select name="dpage_select[]" size="10" multiple="multiple" style="width: 350px;">
<option value="nonepage" selected><?php echo @ADMIN_NOT_VALUED; ?>
</option>
<?php unset($this->_sections['i']);
$this->_sections['i']['name'] = 'i';
$this->_sections['i']['loop'] = is_array($_loop=$this->_tpl_vars['aux_pages']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['i']['show'] = true;
$this->_sections['i']['max'] = $this->_sections['i']['loop'];
$this->_sections['i']['step'] = 1;
$this->_sections['i']['start'] = $this->_sections['i']['step'] > 0 ? 0 : $this->_sections['i']['loop']-1;
if ($this->_sections['i']['show']) {
    $this->_sections['i']['total'] = $this->_sections['i']['loop'];
    if ($this->_sections['i']['total'] == 0)
        $this->_sections['i']['show'] = false;
} else
    $this->_sections['i']['total'] = 0;
if ($this->_sections['i']['show']):

            for ($this->_sections['i']['index'] = $this->_sections['i']['start'], $this->_sections['i']['iteration'] = 1;
                 $this->_sections['i']['iteration'] <= $this->_sections['i']['total'];
                 $this->_sections['i']['index'] += $this->_sections['i']['step'], $this->_sections['i']['iteration']++):
$this->_sections['i']['rownum'] = $this->_sections['i']['iteration'];
$this->_sections['i']['index_prev'] = $this->_sections['i']['index'] - $this->_sections['i']['step'];
$this->_sections['i']['index_next'] = $this->_sections['i']['index'] + $this->_sections['i']['step'];
$this->_sections['i']['first']      = ($this->_sections['i']['iteration'] == 1);
$this->_sections['i']['last']       = ($this->_sections['i']['iteration'] == $this->_sections['i']['total']);
?>
<option value="<?php echo $this->_tpl_vars['aux_pages'][$this->_sections['i']['index']]['aux_page_ID']; ?>
"><?php echo $this->_tpl_vars['aux_pages'][$this->_sections['i']['index']]['aux_page_name']; ?>
</option>
<?php endfor; endif; ?>
</select>
</td>
</tr></table>
<table class="adn"><tr><td class="se6"></td></tr></table>
<table class="adw">
<tr class="linsz">
<td align="left"><span class="titlecol2"><?php echo @BLOCK_EDIT_DCAT; ?>
</span></td>
<td align="left" style="padding-left: 16px;"><span class="titlecol2"><?php echo @BLOCK_EDIT_DPROD; ?>
</span></td>
</tr>
<tr>
<td align="left">
<select name="categories_select[]" size="10" multiple="multiple" style="width: 350px">
<option value="nonepage" selected><?php echo @ADMIN_NOT_VALUED; ?>
</option>
<?php unset($this->_sections['im']);
$this->_sections['im']['name'] = 'im';
$this->_sections['im']['loop'] = is_array($_loop=$this->_tpl_vars['cats']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['im']['show'] = true;
$this->_sections['im']['max'] = $this->_sections['im']['loop'];
$this->_sections['im']['step'] = 1;
$this->_sections['im']['start'] = $this->_sections['im']['step'] > 0 ? 0 : $this->_sections['im']['loop']-1;
if ($this->_sections['im']['show']) {
    $this->_sections['im']['total'] = $this->_sections['im']['loop'];
    if ($this->_sections['im']['total'] == 0)
        $this->_sections['im']['show'] = false;
} else
    $this->_sections['im']['total'] = 0;
if ($this->_sections['im']['show']):

            for ($this->_sections['im']['index'] = $this->_sections['im']['start'], $this->_sections['im']['iteration'] = 1;
                 $this->_sections['im']['iteration'] <= $this->_sections['im']['total'];
                 $this->_sections['im']['index'] += $this->_sections['im']['step'], $this->_sections['im']['iteration']++):
$this->_sections['im']['rownum'] = $this->_sections['im']['iteration'];
$this->_sections['im']['index_prev'] = $this->_sections['im']['index'] - $this->_sections['im']['step'];
$this->_sections['im']['index_next'] = $this->_sections['im']['index'] + $this->_sections['im']['step'];
$this->_sections['im']['first']      = ($this->_sections['im']['iteration'] == 1);
$this->_sections['im']['last']       = ($this->_sections['im']['iteration'] == $this->_sections['im']['total']);
?>
<option value="<?php echo $this->_tpl_vars['cats'][$this->_sections['im']['index']]['categoryID']; ?>
"><?php unset($this->_sections['j']);
$this->_sections['j']['name'] = 'j';
$this->_sections['j']['loop'] = is_array($_loop=$this->_tpl_vars['cats'][$this->_sections['im']['index']]['level']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['j']['max'] = (int)$this->_tpl_vars['cats'][$this->_sections['im']['index']]['level'];
$this->_sections['j']['show'] = true;
if ($this->_sections['j']['max'] < 0)
    $this->_sections['j']['max'] = $this->_sections['j']['loop'];
$this->_sections['j']['step'] = 1;
$this->_sections['j']['start'] = $this->_sections['j']['step'] > 0 ? 0 : $this->_sections['j']['loop']-1;
if ($this->_sections['j']['show']) {
    $this->_sections['j']['total'] = min(ceil(($this->_sections['j']['step'] > 0 ? $this->_sections['j']['loop'] - $this->_sections['j']['start'] : $this->_sections['j']['start']+1)/abs($this->_sections['j']['step'])), $this->_sections['j']['max']);
    if ($this->_sections['j']['total'] == 0)
        $this->_sections['j']['show'] = false;
} else
    $this->_sections['j']['total'] = 0;
if ($this->_sections['j']['show']):

            for ($this->_sections['j']['index'] = $this->_sections['j']['start'], $this->_sections['j']['iteration'] = 1;
                 $this->_sections['j']['iteration'] <= $this->_sections['j']['total'];
                 $this->_sections['j']['index'] += $this->_sections['j']['step'], $this->_sections['j']['iteration']++):
$this->_sections['j']['rownum'] = $this->_sections['j']['iteration'];
$this->_sections['j']['index_prev'] = $this->_sections['j']['index'] - $this->_sections['j']['step'];
$this->_sections['j']['index_next'] = $this->_sections['j']['index'] + $this->_sections['j']['step'];
$this->_sections['j']['first']      = ($this->_sections['j']['iteration'] == 1);
$this->_sections['j']['last']       = ($this->_sections['j']['iteration'] == $this->_sections['j']['total']);
?>&nbsp;&nbsp;&nbsp;<?php endfor; endif; ?><?php echo $this->_tpl_vars['cats'][$this->_sections['im']['index']]['name']; ?>
</option>
<?php endfor; endif; ?>
</select>
</td>
<td align="left" style="padding-left: 16px;" valign="top">
<textarea name="products_select" style="width: 348px; height: 92px;"></textarea>
<div align="left"><br><?php echo @BLOCK_EDIT_PSDESCRIPT; ?>
</div>
</td>
</tr>
</table>
     <table class="adn"><tr><td height="18"></td></tr></table>
<a href="#" onclick="document.getElementById('formaxp3').submit(); return false" class="inl"><?php echo @SAVE_BUTTON; ?>
</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a href="<?php echo @ADMIN_FILE; ?>
?dpt=conf&amp;sub=blocks_edit" class="inl"><?php echo @ADMIN_TX2; ?>
</a>
<input type=hidden value='<?php echo @SAVE_BUTTON; ?>
' name='save'>
</form>
<?php elseif ($this->_tpl_vars['add_new']): ?>
<form action="<?php echo @ADMIN_FILE; ?>
?dpt=conf&amp;sub=blocks_edit&amp;add_new=yes" method="post" name="formaxp3" id="formaxp3">

<table class="adn">
<tr class="linsz">
<td align="left" style="padding-top: 0;"><span class="titlecol2"><?php echo @BLOCKS_NAME; ?>
</span></td>
</tr>
<tr>
<td align="left"><input name="block_name" type="text" value='' style="width: 500px;" class="textp"></td>
</tr></table>
<table class="adn"><tr><td class="se5"></td></tr></table>
<table class="adn">
<tr class="linsz">
<td align="left"><span class="titlecol2"><?php echo @BLOCK_TEXT_CONT; ?>
</span></td>
</tr>
<tr>
<td>
<textarea name="block_content" id="blockarea" class="admin"></textarea>
</td></tr></table>
<table class="adn"><tr><td class="se6"></td></tr></table>
<?php if (@CONF_EDITOR): ?>
<?php echo '
<script type="text/javascript" src="fckeditor/fckeditor.js"></script>
<script type="text/javascript" src="fckeditor/ckfinder/ckfinder.js"></script>
<script type="text/javascript">
window.onload = function()
{
var oFCKeditor = new FCKeditor( \'blockarea\',720,346) ;
'; ?>
<?php 
$dir1 = dirname($_SERVER['PHP_SELF']);
$sourcessrand = array("//" => "/", "\\" => "/");
$dir1 = strtr($dir1, $sourcessrand);
if ($dir1 != "/") $dir2 = "/"; else $dir2 = "";
echo "\n";
echo "oFCKeditor.BasePath = \"".$dir1.$dir2."fckeditor/\";\n";
 ?><?php echo '
oFCKeditor.ReplaceTextarea() ;
}
</script>
'; ?>

<?php endif; ?>
<?php echo @BLOCK_EDIT_1; ?>
: <select name='block_select_where' >
<option value="0" selected> <?php echo @BLOCK_EDIT_4; ?>
 </option>
<option value="1"> <?php echo @BLOCK_EDIT_5; ?>
 </option>
<option value="2"> <?php echo @BLOCK_EDIT_5_NEW; ?>
 </option>
</select>&nbsp;&nbsp;&nbsp;&nbsp;<?php echo @BLOCK_EDIT_2; ?>
: <select name='block_select_line' >
<option value="1" selected> <?php echo @BLOCK_EDIT_6; ?>
 </option>
<option value="2"> <?php echo @BLOCK_EDIT_7; ?>
 </option>
<option value="3"> <?php echo @BLOCK_EDIT_8; ?>
 </option>
<option value="4"> <?php echo @BLOCK_EDIT_9; ?>
 </option>
</select>&nbsp;&nbsp;&nbsp;&nbsp;<?php echo @BLOCK_EDIT_3; ?>
: <select name='block_select_active' >
<option value="1" selected> <?php echo @BLOCK_EDIT_10; ?>
 </option>
<option value="0"> <?php echo @BLOCK_EDIT_11; ?>
 </option>
</select>&nbsp;&nbsp;<select name='block_select_admin' >
<option value="0" selected> <?php echo @BLOCK_EDIT_ADMIN2; ?>
 </option>
<option value="1"> <?php echo @BLOCK_EDIT_ADMIN3; ?>
 </option>
</select><table class="adn"><tr><td class="se6"></td></tr></table>
<table class="adw">
<tr class="linsz">
<td align="left"><span class="titlecol2"><?php echo @BLOCK_EDIT_NEW_CHOISE; ?>
</span></td>
<td align="left" style="padding-left: 16px;"><span class="titlecol2"><?php echo @BLOCK_EDIT_ADD_CHOISE; ?>
</span></td>
</tr>
<tr>
<td align="left">
<select name="spage_select[]" size="10" multiple="multiple" style="width: 350px">
<option value="nonepage" selected><?php echo @ADMIN_NOT_VALUED; ?>
</option>
                <option value="home.tpl.html"><?php echo @BLOCK_EDIT_PAGE_1; ?>
</option>
                <option value="activation_orders.tpl.html"><?php echo @BLOCK_EDIT_PAGE_2; ?>
</option>
                <option value="deactivation_orders.tpl.html"><?php echo @BLOCK_EDIT_PAGE_3; ?>
</option>
                <option value="address_book.tpl.html"><?php echo @BLOCK_EDIT_PAGE_4; ?>
</option>
                <option value="address_editor.tpl.html"><?php echo @BLOCK_EDIT_PAGE_5; ?>
</option>
                <option value="category.tpl.html"><?php echo @BLOCK_EDIT_PAGE_6; ?>
</option>
                <option value="category_search_result.tpl.html"><?php echo @BLOCK_EDIT_PAGE_7; ?>
</option>
                <option value="comparison_products.tpl.html"><?php echo @BLOCK_EDIT_PAGE_8; ?>
</option>
                <option value="contact_info.tpl.html"><?php echo @BLOCK_EDIT_PAGE_9; ?>
</option>
                <option value="customer_survey_result.tpl.html"><?php echo @BLOCK_EDIT_PAGE_10; ?>
</option>
                <option value="feedback.tpl.html"><?php echo @BLOCK_EDIT_PAGE_11; ?>
</option>
                <option value="links_exchange.tpl.html"><?php echo @BLOCK_EDIT_PAGE_12; ?>
</option>
                <option value="order2_shipping.tpl.html"><?php echo @BLOCK_EDIT_PAGE_13; ?>
</option>
                <option value="order2_shipping_quick.tpl.html"><?php echo @BLOCK_EDIT_PAGE_14; ?>
</option>
                <option value="order3_billing.tpl.html"><?php echo @BLOCK_EDIT_PAGE_15; ?>
</option>
                <option value="order3_billing_quick.tpl.html"><?php echo @BLOCK_EDIT_PAGE_16; ?>
</option>
                <option value="order4_confirmation.tpl.html"><?php echo @BLOCK_EDIT_PAGE_17; ?>
</option>
                <option value="order4_confirmation_quick.tpl.html"><?php echo @BLOCK_EDIT_PAGE_18; ?>
</option>
                <option value="order_history.tpl.html"><?php echo @BLOCK_EDIT_PAGE_19; ?>
</option>
                <option value="password.tpl.html"><?php echo @BLOCK_EDIT_PAGE_20; ?>
</option>
                <option value="pricelist.tpl.html"><?php echo @BLOCK_EDIT_PAGE_21; ?>
</option>
                <option value="product_detailed.tpl.html"><?php echo @BLOCK_EDIT_PAGE_22; ?>
</option>
                <option value="product_discussion.tpl.html"><?php echo @BLOCK_EDIT_PAGE_23; ?>
</option>
                <option value="reg_successful.tpl.html"><?php echo @BLOCK_EDIT_PAGE_24; ?>
</option>
                <option value="register.tpl.html"><?php echo @BLOCK_EDIT_PAGE_25; ?>
</option>
                <option value="register_authorization.tpl.html"><?php echo @BLOCK_EDIT_PAGE_26; ?>
</option>
                <option value="register_quick.tpl.html"><?php echo @BLOCK_EDIT_PAGE_27; ?>
</option>
                <option value="search_simple.tpl.html"><?php echo @BLOCK_EDIT_PAGE_28; ?>
</option>
                <option value="shopping_cart.tpl.html"><?php echo @BLOCK_EDIT_PAGE_29; ?>
</option>
                <option value="show_aux_page.tpl.html"><?php echo @BLOCK_EDIT_PAGE_30; ?>
</option>
                <option value="show_full_news.tpl.html"><?php echo @BLOCK_EDIT_PAGE_31; ?>
</option>
                <option value="show_news.tpl.html"><?php echo @BLOCK_EDIT_PAGE_32; ?>
</option>
                <option value="subscribe.tpl.html"><?php echo @BLOCK_EDIT_PAGE_33; ?>
</option>
                <option value="user_account.tpl.html"><?php echo @BLOCK_EDIT_PAGE_34; ?>
</option>
                <option value="visit_history.tpl.html"><?php echo @BLOCK_EDIT_PAGE_35; ?>
</option>
</select>
</td>
<td align="left" style="padding-left: 16px;">
<select name="dpage_select[]" size="10" multiple="multiple" style="width: 350px;">
<option value="nonepage" selected><?php echo @ADMIN_NOT_VALUED; ?>
</option>
<?php unset($this->_sections['i']);
$this->_sections['i']['name'] = 'i';
$this->_sections['i']['loop'] = is_array($_loop=$this->_tpl_vars['aux_pages']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['i']['show'] = true;
$this->_sections['i']['max'] = $this->_sections['i']['loop'];
$this->_sections['i']['step'] = 1;
$this->_sections['i']['start'] = $this->_sections['i']['step'] > 0 ? 0 : $this->_sections['i']['loop']-1;
if ($this->_sections['i']['show']) {
    $this->_sections['i']['total'] = $this->_sections['i']['loop'];
    if ($this->_sections['i']['total'] == 0)
        $this->_sections['i']['show'] = false;
} else
    $this->_sections['i']['total'] = 0;
if ($this->_sections['i']['show']):

            for ($this->_sections['i']['index'] = $this->_sections['i']['start'], $this->_sections['i']['iteration'] = 1;
                 $this->_sections['i']['iteration'] <= $this->_sections['i']['total'];
                 $this->_sections['i']['index'] += $this->_sections['i']['step'], $this->_sections['i']['iteration']++):
$this->_sections['i']['rownum'] = $this->_sections['i']['iteration'];
$this->_sections['i']['index_prev'] = $this->_sections['i']['index'] - $this->_sections['i']['step'];
$this->_sections['i']['index_next'] = $this->_sections['i']['index'] + $this->_sections['i']['step'];
$this->_sections['i']['first']      = ($this->_sections['i']['iteration'] == 1);
$this->_sections['i']['last']       = ($this->_sections['i']['iteration'] == $this->_sections['i']['total']);
?>
<option value="<?php echo $this->_tpl_vars['aux_pages'][$this->_sections['i']['index']]['aux_page_ID']; ?>
"><?php echo $this->_tpl_vars['aux_pages'][$this->_sections['i']['index']]['aux_page_name']; ?>
</option>
<?php endfor; endif; ?>
</select>
</td>
</tr></table>
<table class="adn"><tr><td class="se6"></td></tr></table>
<table class="adw">
<tr class="linsz">
<td align="left"><span class="titlecol2"><?php echo @BLOCK_EDIT_DCAT; ?>
</span></td>
<td align="left" style="padding-left: 16px;"><span class="titlecol2"><?php echo @BLOCK_EDIT_DPROD; ?>
</span></td>
</tr>
<tr>
<td align="left">
<select name="categories_select[]" size="10" multiple="multiple" style="width: 350px">
<option value="nonepage" selected><?php echo @ADMIN_NOT_VALUED; ?>
</option>
<?php unset($this->_sections['im']);
$this->_sections['im']['name'] = 'im';
$this->_sections['im']['loop'] = is_array($_loop=$this->_tpl_vars['cats']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['im']['show'] = true;
$this->_sections['im']['max'] = $this->_sections['im']['loop'];
$this->_sections['im']['step'] = 1;
$this->_sections['im']['start'] = $this->_sections['im']['step'] > 0 ? 0 : $this->_sections['im']['loop']-1;
if ($this->_sections['im']['show']) {
    $this->_sections['im']['total'] = $this->_sections['im']['loop'];
    if ($this->_sections['im']['total'] == 0)
        $this->_sections['im']['show'] = false;
} else
    $this->_sections['im']['total'] = 0;
if ($this->_sections['im']['show']):

            for ($this->_sections['im']['index'] = $this->_sections['im']['start'], $this->_sections['im']['iteration'] = 1;
                 $this->_sections['im']['iteration'] <= $this->_sections['im']['total'];
                 $this->_sections['im']['index'] += $this->_sections['im']['step'], $this->_sections['im']['iteration']++):
$this->_sections['im']['rownum'] = $this->_sections['im']['iteration'];
$this->_sections['im']['index_prev'] = $this->_sections['im']['index'] - $this->_sections['im']['step'];
$this->_sections['im']['index_next'] = $this->_sections['im']['index'] + $this->_sections['im']['step'];
$this->_sections['im']['first']      = ($this->_sections['im']['iteration'] == 1);
$this->_sections['im']['last']       = ($this->_sections['im']['iteration'] == $this->_sections['im']['total']);
?>
<option value="<?php echo $this->_tpl_vars['cats'][$this->_sections['im']['index']]['categoryID']; ?>
"><?php unset($this->_sections['j']);
$this->_sections['j']['name'] = 'j';
$this->_sections['j']['loop'] = is_array($_loop=$this->_tpl_vars['cats'][$this->_sections['im']['index']]['level']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['j']['max'] = (int)$this->_tpl_vars['cats'][$this->_sections['im']['index']]['level'];
$this->_sections['j']['show'] = true;
if ($this->_sections['j']['max'] < 0)
    $this->_sections['j']['max'] = $this->_sections['j']['loop'];
$this->_sections['j']['step'] = 1;
$this->_sections['j']['start'] = $this->_sections['j']['step'] > 0 ? 0 : $this->_sections['j']['loop']-1;
if ($this->_sections['j']['show']) {
    $this->_sections['j']['total'] = min(ceil(($this->_sections['j']['step'] > 0 ? $this->_sections['j']['loop'] - $this->_sections['j']['start'] : $this->_sections['j']['start']+1)/abs($this->_sections['j']['step'])), $this->_sections['j']['max']);
    if ($this->_sections['j']['total'] == 0)
        $this->_sections['j']['show'] = false;
} else
    $this->_sections['j']['total'] = 0;
if ($this->_sections['j']['show']):

            for ($this->_sections['j']['index'] = $this->_sections['j']['start'], $this->_sections['j']['iteration'] = 1;
                 $this->_sections['j']['iteration'] <= $this->_sections['j']['total'];
                 $this->_sections['j']['index'] += $this->_sections['j']['step'], $this->_sections['j']['iteration']++):
$this->_sections['j']['rownum'] = $this->_sections['j']['iteration'];
$this->_sections['j']['index_prev'] = $this->_sections['j']['index'] - $this->_sections['j']['step'];
$this->_sections['j']['index_next'] = $this->_sections['j']['index'] + $this->_sections['j']['step'];
$this->_sections['j']['first']      = ($this->_sections['j']['iteration'] == 1);
$this->_sections['j']['last']       = ($this->_sections['j']['iteration'] == $this->_sections['j']['total']);
?>&nbsp;&nbsp;&nbsp;<?php endfor; endif; ?><?php echo $this->_tpl_vars['cats'][$this->_sections['im']['index']]['name']; ?>
</option>
<?php endfor; endif; ?>
</select>
</td>
<td align="left" style="padding-left: 16px;" valign="top">
<textarea name="products_select" style="width: 348px; height: 92px;"></textarea>
<div align="left"><br><?php echo @BLOCK_EDIT_PSDESCRIPT; ?>
</div>
</td>
</tr>
</table>

<table class="adn"><tr><td height="18"></td></tr></table>
<a href="#" onclick="document.getElementById('formaxp3').submit(); return false" class="inl"><?php echo @SAVE_BUTTON; ?>
</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a href="<?php echo @ADMIN_FILE; ?>
?dpt=conf&amp;sub=blocks_edit" class="inl"><?php echo @ADMIN_TX2; ?>
</a>
<input type=hidden value='<?php echo @SAVE_BUTTON; ?>
' name='save'>
</form>
<?php else: ?>

<?php if ($this->_tpl_vars['blocks_count'] >= 1): ?>
<form action='<?php echo @ADMIN_FILE; ?>
?dpt=conf&amp;sub=blocks_edit' method=post name="formaxp2" id="formaxp2">
<table class="adn">
<tr class="lineb">
<td align="left" width="100%"><?php echo @BLOCKS_NAME; ?>
</td>
<td align="right"><?php echo @BLOCKS_ACTIVE; ?>
</td>
<td align="right"><?php echo @BLOCKS_POSITION; ?>
</td>
<td align="right"><?php echo @BLOCKS_SORT; ?>
</td>
<td align="right"><?php echo @AUX_PAGE_EDIT; ?>
</td>
</tr><?php $this->assign('admhl', 0); ?>
<?php unset($this->_sections['i']);
$this->_sections['i']['name'] = 'i';
$this->_sections['i']['loop'] = is_array($_loop=$this->_tpl_vars['blocks_edit']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['i']['show'] = true;
$this->_sections['i']['max'] = $this->_sections['i']['loop'];
$this->_sections['i']['step'] = 1;
$this->_sections['i']['start'] = $this->_sections['i']['step'] > 0 ? 0 : $this->_sections['i']['loop']-1;
if ($this->_sections['i']['show']) {
    $this->_sections['i']['total'] = $this->_sections['i']['loop'];
    if ($this->_sections['i']['total'] == 0)
        $this->_sections['i']['show'] = false;
} else
    $this->_sections['i']['total'] = 0;
if ($this->_sections['i']['show']):

            for ($this->_sections['i']['index'] = $this->_sections['i']['start'], $this->_sections['i']['iteration'] = 1;
                 $this->_sections['i']['iteration'] <= $this->_sections['i']['total'];
                 $this->_sections['i']['index'] += $this->_sections['i']['step'], $this->_sections['i']['iteration']++):
$this->_sections['i']['rownum'] = $this->_sections['i']['iteration'];
$this->_sections['i']['index_prev'] = $this->_sections['i']['index'] - $this->_sections['i']['step'];
$this->_sections['i']['index_next'] = $this->_sections['i']['index'] + $this->_sections['i']['step'];
$this->_sections['i']['first']      = ($this->_sections['i']['iteration'] == 1);
$this->_sections['i']['last']       = ($this->_sections['i']['iteration'] == $this->_sections['i']['total']);
?>
<?php if ($this->_tpl_vars['admhl'] == 1): ?>
<tr><td colspan="6" class="separ"><img src="data/admin/pixel.gif" alt="" class="sep"></td></tr>
<?php else: ?><?php $this->assign('admhl', 1); ?><?php endif; ?>
<tr class="liney hover">
<td align="left"><?php echo $this->_tpl_vars['blocks_edit'][$this->_sections['i']['index']][1]; ?>
</td>
<td align="right"><?php if ($this->_tpl_vars['blocks_edit'][$this->_sections['i']['index']][3] == 1): ?><span style="color: #339933"><?php echo @BLOCKS_ON; ?>
</span><?php else: ?><span style="color: #BB0000"><?php echo @BLOCKS_OFF; ?>
</span><?php endif; ?>&nbsp;</td>
<td align="right" class="toph3"><?php if ($this->_tpl_vars['blocks_edit'][$this->_sections['i']['index']][2] == 1): ?><?php echo @BLOCKS_LEFT; ?>
<?php elseif ($this->_tpl_vars['blocks_edit'][$this->_sections['i']['index']][2] == 2): ?><?php echo @BLOCKS_TOP; ?>
<?php elseif ($this->_tpl_vars['blocks_edit'][$this->_sections['i']['index']][2] == 3): ?><?php echo @BLOCKS_BOTTOM; ?>
<?php elseif ($this->_tpl_vars['blocks_edit'][$this->_sections['i']['index']][2] == 4): ?><?php echo @BLOCKS_RIGHT; ?>
<?php endif; ?></td>
<td align="right"><input type="text" value="<?php echo $this->_tpl_vars['blocks_edit'][$this->_sections['i']['index']][5]; ?>
" class="prc" name="sort_<?php echo $this->_tpl_vars['blocks_edit'][$this->_sections['i']['index']][0]; ?>
" size="4"></td>
<td align="right"><?php if ($this->_tpl_vars['blocks_edit'][$this->_sections['i']['index']][3] == 1): ?><a href='<?php echo @ADMIN_FILE; ?>
?dpt=conf&amp;sub=blocks_edit&amp;block_switch_off=<?php echo $this->_tpl_vars['blocks_edit'][$this->_sections['i']['index']][0]; ?>
'><?php echo @BLOCK_OUT; ?>
</a><?php else: ?><a href='<?php echo @ADMIN_FILE; ?>
?dpt=conf&amp;sub=blocks_edit&amp;block_switch_on=<?php echo $this->_tpl_vars['blocks_edit'][$this->_sections['i']['index']][0]; ?>
'><?php echo @BLOCK_IN; ?>
</a><?php endif; ?>&nbsp;|&nbsp;<a href='<?php echo @ADMIN_FILE; ?>
?dpt=conf&amp;sub=blocks_edit&amp;edit=<?php echo $this->_tpl_vars['blocks_edit'][$this->_sections['i']['index']][0]; ?>
'><?php echo @ADMIN_EDIT_SMALL; ?>
</a>&nbsp;|&nbsp;<a href="#" onclick="confirmDelete(<?php echo $this->_tpl_vars['blocks_edit'][$this->_sections['i']['index']][0]; ?>
,'<?php echo @QUESTION_DELETE_CONFIRMATION; ?>
','<?php echo @ADMIN_FILE; ?>
?dpt=conf&amp;sub=blocks_edit&amp;delete=');">X</a></td>
</tr>
<?php endfor; endif; ?>
</table>
<table class="adn"><tr><td class="separ"><img src="data/admin/pixel.gif" alt="" class="sep"></td></tr><tr><td class="se5"></td></tr></table>
<a href="#" onclick="document.getElementById('formaxp2').submit(); return false" class="inl"><?php echo @SAVE_BUTTON; ?>
</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a href="<?php echo @ADMIN_FILE; ?>
?dpt=conf&amp;sub=blocks_edit&amp;add_new=yes" class="inl"><?php echo @ADD_BLOCK; ?>
</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a href="<?php echo @ADMIN_FILE; ?>
?dpt=conf&amp;sub=blocks_edit&amp;add_new=yes&amp;file=yes" class="inl"><?php echo @ADD_BLOCK_FILE; ?>
</a>
<input type=hidden value='<?php echo @SAVE_BUTTON; ?>
' name='savel'>
</form>
<?php else: ?>
<table class="adn">
<tr class="lineb">
<td align="left">&nbsp;</td></tr>
<tr><td align="center" height="24"><?php echo @ALERT_ADMIN_BLOCKS; ?>
</td></tr></table>
<table class="adn"><tr><td class="separ"><img src="data/admin/pixel.gif" alt="" class="sep"></td></tr><tr><td class="se5"></td></tr></table>
<a href="<?php echo @ADMIN_FILE; ?>
?dpt=conf&amp;sub=blocks_edit&amp;add_new=yes" class="inl"><?php echo @ADD_BLOCK; ?>
</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a href="<?php echo @ADMIN_FILE; ?>
?dpt=conf&amp;sub=blocks_edit&amp;add_new=yes&amp;file=yes" class="inl"><?php echo @ADD_BLOCK_FILE; ?>
</a>
<?php endif; ?>

<?php endif; ?>



<table class="adn"><tr><td class="se6"></td></tr></table>
<table class="adn"><tr><td class="help"><span class="titlecol2"><?php echo @USEFUL_FOR_YOU; ?>
</span><div class="helptext"><?php echo @ALERT_ADMIN_BL2; ?>
<br><br><?php echo @ALERT_ADMIN2; ?>
</div></td>
        </tr>
      </table>