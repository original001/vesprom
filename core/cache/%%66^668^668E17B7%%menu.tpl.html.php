<?php /* Smarty version 2.6.22, created on 2015-07-17 15:39:20
         compiled from admin/menu.tpl.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'set_query', 'admin/menu.tpl.html', 317, false),array('modifier', 'replace', 'admin/menu.tpl.html', 409, false),)), $this); ?>
<table class="adn"><tr><td class="se"></td></tr></table>
<table width="186" class="adw" style="margin: auto;">
                           <?php if ($this->_tpl_vars['admin_sub_dpt'] == "custord_custlist.tpl.html"): ?>
                               <tr>
                                   <td class="head2">
                                       <table class="adn">
                                           <tr>
                                               <td class="head3"><?php echo @ADMIN_CUSTOMERS; ?>
</td>
                                           </tr>
                                       </table>
                                   </td>
                               </tr>
                               <tr>
<td>
<div class="dvmenu">
         <form name="leftsrform" method=GET id="leftsrform" action="">
<table border="0" cellspacing="4">

        <tr>
                <td><div align="left" style="padding-bottom: 2px;"><?php echo @ADMIN_CUSTOMER_LOGIN; ?>
</div><input type="text" name="login" value='<?php echo $this->_tpl_vars['login']; ?>
' class="prc" size="25"></td>
        </tr>

        <tr>
                <td><div align="left" style="padding-bottom: 2px;"><?php echo @ADMIN_CUSTOMER_FIRST_NAME; ?>
</div><input type="text" name="first_name" value='<?php echo $this->_tpl_vars['first_name']; ?>
' class="prc" size="25"></td>
        </tr>

        <tr>
                <td><div align="left" style="padding-bottom: 2px;"><?php echo @ADMIN_CUSTOMER_LAST_NAME; ?>
</div><input type="text" name="last_name" value='<?php echo $this->_tpl_vars['last_name']; ?>
' class="prc" size="25"></td>
        </tr>

        <tr>
                <td><div align="left" style="padding-bottom: 2px;"><?php echo @ADMIN_CUSTOMER_EMAIL; ?>
</div><input type="text" name="email" value='<?php echo $this->_tpl_vars['email']; ?>
' class="prc" size="25"></td>
        </tr>

        <tr>
                <td><div align="left" style="padding-bottom: 2px;"><?php echo @ADMIN_CUSTOMER_GROUP_NAME; ?>
</div><select name="groupID">

                                <option value='0'>
                                        <?php echo @STRING_ANY; ?>

                                </option>

                                <?php unset($this->_sections['i']);
$this->_sections['i']['name'] = 'i';
$this->_sections['i']['loop'] = is_array($_loop=$this->_tpl_vars['customer_groups']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
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
                                <option value='<?php echo $this->_tpl_vars['customer_groups'][$this->_sections['i']['index']]['custgroupID']; ?>
'
                                        <?php if ($this->_tpl_vars['groupID'] == $this->_tpl_vars['customer_groups'][$this->_sections['i']['index']]['custgroupID']): ?>
                                                selected
                                        <?php endif; ?>
                                >
                                        <?php echo $this->_tpl_vars['customer_groups'][$this->_sections['i']['index']]['custgroup_name']; ?>

                                </option>
                                <?php endfor; endif; ?>

                        </select>
                </td>
        </tr>
                  <tr>
                <td><div align="left" style="padding-bottom: 2px;"><?php echo @STRING_MODULE_STATUS; ?>
</div>

                        <select name="fActState">
                                <option value='-1'<?php if ($this->_tpl_vars['ActState'] == -1): ?> selected="selected"<?php endif; ?>>
                                        <?php echo @STRING_ANY_M; ?>

                                </option>
                                <option value='1'<?php if ($this->_tpl_vars['ActState'] == 1): ?> selected="selected"<?php endif; ?>>
                                        <?php echo @STR_ACTIVATED; ?>

                                </option>
                                <option value='0'<?php if ($this->_tpl_vars['ActState'] == 0 && $this->_tpl_vars['ActState'] != ''): ?> selected="selected"<?php endif; ?>>
                                        <?php echo @STR_NOTACTIVATED; ?>

                                </option>
                        </select>
                </td>
        </tr>
</table>
<input type=hidden name='dpt' value='custord'>
<input type=hidden name='sub' value='custlist'>
<input type=hidden name='search' value="1">
</form><br><div align="center"><img src="data/admin/spl.gif" alt="" onclick="document.getElementById('leftsrform').submit();" style="cursor: pointer;"></div>
</div>
                                  </td>
                               </tr>
                               <tr>
                                   <td class="se"></td>
                               </tr>
                               <?php endif; ?>


                           <?php if ($this->_tpl_vars['admin_sub_dpt'] == "custord_affiliate.tpl.html" && @CONF_AFFILIATE_PROGRAM_ENABLED == 1): ?>
                               <tr>
                                   <td class="head2">
                                       <table class="adn">
                                           <tr>
                                               <td class="head3"><?php echo @AFFP_COMMISSION_PAYMENTS; ?>
</td>
                                           </tr>
                                       </table>
                                   </td>
                               </tr>
                               <tr>
<td>
<div class="dvmenu">
<?php echo @STRING_CALENDAR; ?>
<br>
<?php if ($this->_tpl_vars['Error_DateFormat']): ?><span style="color: red;"><?php echo @AFFP_MSG_ERROR_DATE_FORMAT; ?>
</span><br><?php endif; ?>
<form method="POST" action="<?php echo $this->_tpl_vars['REQUEST_URI']; ?>
" id="leftsrform">
<table border="0" cellspacing="4">

        <tr>
                <td><div align="left" style="padding-bottom: 2px;"><?php echo @STRING_FROM; ?>
:</div><input type="text" name="from" value="<?php echo $this->_tpl_vars['from']; ?>
" class="prc" size="25"></td>
        </tr>

        <tr>
                <td><div align="left" style="padding-bottom: 2px;"><?php echo @STRING_TILL; ?>
:</div><input name="till" value="<?php echo $this->_tpl_vars['till']; ?>
" type="text" class="prc" size="25"></td>
        </tr>

</table>
</form><br><div align="center"><img src="data/admin/spl.gif" alt="" onclick="document.getElementById('leftsrform').submit();" style="cursor: pointer;"></div>
</div>
                                  </td>
                               </tr>
                               <tr>
                                   <td class="se"></td>
                               </tr>
                               <?php endif; ?>




                           <?php if ($this->_tpl_vars['admin_sub_dpt'] == "catalog_products_categories.tpl.html"): ?>
<tr>
                                   <td class="head2">
                                       <table class="adn">
                                           <tr>
                                               <td class="head4"><?php echo @ADMIN_CATALOG_CATV; ?>
</td>
                                             <td align="right"><div align="right"><a href='<?php echo $this->_tpl_vars['urlToCategoryTreeExpand']; ?>
&amp;expandCatp=all' title="<?php echo @ADMIN_ADMIN_MENUNEW2; ?>
" style="cursor: pointer;"><img src="data/admin/004.gif" alt=""></a></div></td>
                                             <td align="right" class="head7"><div align="right"><a href='<?php echo $this->_tpl_vars['urlToCategoryTreeExpand']; ?>
&amp;shrinkCatm=all' title="<?php echo @ADMIN_ADMIN_MENUNEW3; ?>
" style="cursor: pointer;"><img src="data/admin/003.gif" alt=""></a></div></td>

                                           </tr>
                                       </table>
                                   </td>
                               </tr>
                               <tr>
<td>
<div class="dvmenusmall">
<table class="adn">
<tr>
<td class="l1"><img src="data/admin/dr.gif" alt=""></td>
<td class="l2"><a href="<?php echo @ADMIN_FILE; ?>
?dpt=catalog&amp;sub=products_categories&amp;categoryID=1"><?php echo @ADMIN_CATEGORY_ROOT; ?>
</a></td>
<td class="l3"><?php echo $this->_tpl_vars['products_in_root_category']; ?>
</td>
</tr></table>
<?php unset($this->_sections['i']);
$this->_sections['i']['name'] = 'i';
$this->_sections['i']['loop'] = is_array($_loop=$this->_tpl_vars['categories']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
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
<table class="adn"><tr><td class="l1"><?php unset($this->_sections['j']);
$this->_sections['j']['name'] = 'j';
$this->_sections['j']['loop'] = is_array($_loop=$this->_tpl_vars['categories'][$this->_sections['i']['index']]['level']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['j']['max'] = (int)$this->_tpl_vars['categories'][$this->_sections['i']['index']]['level'];
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
?><?php if ($this->_sections['j']['index'] == $this->_tpl_vars['categories'][$this->_sections['i']['index']]['level']-1): ?><img src="data/admin/pm.gif" alt=""><?php else: ?><img src="data/admin/pmp.gif" alt=""><?php endif; ?><?php endfor; endif; ?><?php if (! $this->_tpl_vars['categories'][$this->_sections['i']['index']]['ExpandedCategory']): ?><?php if ($this->_tpl_vars['categories'][$this->_sections['i']['index']]['ExistSubCategories']): ?><a href='<?php echo $this->_tpl_vars['urlToCategoryTreeExpand']; ?>
&amp;expandCat=<?php echo $this->_tpl_vars['categories'][$this->_sections['i']['index']]['categoryID']; ?>
'><img src="data/admin/mplus.gif" alt=""></a><?php else: ?><img src="data/admin/dr.gif" alt=""><?php endif; ?><?php else: ?><?php if ($this->_tpl_vars['categories'][$this->_sections['i']['index']]['ExistSubCategories']): ?><a href='<?php echo $this->_tpl_vars['urlToCategoryTreeExpand']; ?>
&amp;shrinkCat=<?php echo $this->_tpl_vars['categories'][$this->_sections['i']['index']]['categoryID']; ?>
'><img src="data/admin/minus.gif" alt=""></a><?php else: ?><img src="data/admin/dr.gif" alt=""><?php endif; ?><?php endif; ?></td>
<td class="l2"><a href="<?php echo @ADMIN_FILE; ?>
?dpt=catalog&amp;sub=products_categories&amp;categoryID=<?php echo $this->_tpl_vars['categories'][$this->_sections['i']['index']]['categoryID']; ?>
&amp;expandCat=<?php echo $this->_tpl_vars['categories'][$this->_sections['i']['index']]['categoryID']; ?>
"><?php echo $this->_tpl_vars['categories'][$this->_sections['i']['index']]['name']; ?>
</a></td>
<td class="l3"><a href="<?php echo @ADMIN_FILE; ?>
?categoryID=<?php echo $this->_tpl_vars['categories'][$this->_sections['i']['index']]['categoryID']; ?>
&amp;eaction=cat"><?php if (! $this->_tpl_vars['categories'][$this->_sections['i']['index']]['ExpandedCategory']): ?><?php echo $this->_tpl_vars['categories'][$this->_sections['i']['index']]['products_count_admin']; ?>
<?php else: ?><?php echo $this->_tpl_vars['categories'][$this->_sections['i']['index']]['products_count_category']; ?>
<?php endif; ?></a></td>
</tr></table>
<?php endfor; endif; ?></div></td>
                               </tr>
                               <tr>
                                   <td class="se"></td>
                               </tr>
                               <?php endif; ?>
                               <?php if ($this->_tpl_vars['admin_sub_dpt'] == "custord_new_orders.tpl.html"): ?>
                               <tr>
                                   <td class="head2">
                                       <table class="adn">
                                           <tr>
                                               <td class="head3"><?php echo @ADMIN_TX23; ?>
</td>
                                           </tr>
                                       </table>
                                   </td>
                               </tr>
                               <tr>
<td>
<div class="dvmenu">
<script type="text/javascript">
<!--
<?php echo '
function checkBoxes(_idForm, _syncID, _checkableID){

if(document.getElementById(_syncID).checked == false){
'; ?>

<?php unset($this->_sections['i']);
$this->_sections['i']['name'] = 'i';
$this->_sections['i']['loop'] = is_array($_loop=$this->_tpl_vars['order_statuses']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
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
document.getElementById('checkbox_order_status_<?php echo $this->_tpl_vars['order_statuses'][$this->_sections['i']['index']]['statusID']; ?>
').checked = false;
<?php endfor; endif; ?>
<?php echo '
}else{
'; ?>

<?php unset($this->_sections['i']);
$this->_sections['i']['name'] = 'i';
$this->_sections['i']['loop'] = is_array($_loop=$this->_tpl_vars['order_statuses']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
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
document.getElementById('checkbox_order_status_<?php echo $this->_tpl_vars['order_statuses'][$this->_sections['i']['index']]['statusID']; ?>
').checked = true;
<?php endfor; endif; ?>
<?php echo '
}
return true;
}
'; ?>

//-->
</script>
<form method=GET action="<?php echo @ADMIN_FILE; ?>
" name=MainForm id="MainForm">
<table class="adw">
<tr>
<td align="left" width="16" valign="middle"><input type=radio class="round" name=order_search_type id=order_search_type1 value='SearchByOrderID'
                                <?php if ($this->_tpl_vars['order_search_type'] == 'SearchByOrderID' || $this->_tpl_vars['order_search_type'] == null): ?>
                                        checked
                                <?php endif; ?>
                                onclick='order_search_typeClickHandler()'
                                ></td>
                                <td valign="middle" height="20"><?php echo @ADMIN_ORDER_SEARCH_BY_NUMBER; ?>
</td></tr>
                                <tr><td>&nbsp;</td><td align="left"><input type=text name='orderID_textbox' id='orderID_textbox' value='<?php echo $this->_tpl_vars['orderID']; ?>
' class="prc" size="21"></td>
</tr><tr><td height="6" colspan="2"></td></tr>
<tr>
<td align="left"><input type=radio class="round" name=order_search_type id=order_search_type2 value='SearchByStatusID'
<?php if ($this->_tpl_vars['order_search_type'] == 'SearchByStatusID'): ?> checked <?php endif; ?> onclick='order_search_typeClickHandler()'></td>
<td valign="middle">&nbsp;<?php echo @ADMIN_SHOW_ORDER_IN_STATUS; ?>
</td></tr>
<tr><td height="5" colspan="2"></td></tr><tr>
<td colspan="2">
<?php if ($this->_tpl_vars['order_statuses']): ?>
<table class="adw">
<tr>
<td height="20" width="16"><input id="id_checkall" class="round" onclick="checkBoxes('MainForm', 'id_checkall', 'id_ch');" type="checkbox" name="chk"></td>
<td align="left" nowrap="nowrap" valign="middle">&nbsp;<?php echo @ADMIN_SHOW_ALLL; ?>
</td>
</tr>
<?php unset($this->_sections['i']);
$this->_sections['i']['name'] = 'i';
$this->_sections['i']['loop'] = is_array($_loop=$this->_tpl_vars['order_statuses']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
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
<tr>
<td height="18" width="16"><input type=checkbox class="round" name=checkbox_order_status_<?php echo $this->_tpl_vars['order_statuses'][$this->_sections['i']['index']]['statusID']; ?>
  id=checkbox_order_status_<?php echo $this->_tpl_vars['order_statuses'][$this->_sections['i']['index']]['statusID']; ?>

<?php if ($this->_tpl_vars['order_statuses'][$this->_sections['i']['index']]['selected'] == 1): ?> checked <?php endif; ?> value='1'>
</td>
<td align="left" nowrap="nowrap" valign="middle">&nbsp;<?php echo $this->_tpl_vars['order_statuses'][$this->_sections['i']['index']]['status_name']; ?>
</td></tr>
<?php endfor; endif; ?>
</table>
<?php endif; ?>
<script type="text/javascript">
                                function order_search_typeClickHandler()
                                <?php echo '{'; ?>


                                        if ( document.getElementById('order_search_type1').checked )
                                        <?php echo '{'; ?>

                                                <?php unset($this->_sections['i']);
$this->_sections['i']['name'] = 'i';
$this->_sections['i']['loop'] = is_array($_loop=$this->_tpl_vars['order_statuses']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
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
                                                        document.getElementById('checkbox_order_status_<?php echo $this->_tpl_vars['order_statuses'][$this->_sections['i']['index']]['statusID']; ?>
').disabled = true;
                                                <?php endfor; endif; ?>
                                                document.getElementById('orderID_textbox').disabled = false;
                                                document.getElementById('id_checkall').disabled = true;
                                        <?php echo '}'; ?>

                                        else if ( document.getElementById('order_search_type2').checked )
                                        <?php echo '{'; ?>

                                                <?php unset($this->_sections['i']);
$this->_sections['i']['name'] = 'i';
$this->_sections['i']['loop'] = is_array($_loop=$this->_tpl_vars['order_statuses']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
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
                                                        document.getElementById('checkbox_order_status_<?php echo $this->_tpl_vars['order_statuses'][$this->_sections['i']['index']]['statusID']; ?>
').disabled = false;
                                                <?php endfor; endif; ?>
                                                document.getElementById('orderID_textbox').disabled = true;
                                                 document.getElementById('id_checkall').disabled = false;
                                        <?php echo '}'; ?>

                                <?php echo '}'; ?>


                                order_search_typeClickHandler();
                                </script>
                        </td>
                </tr>
        </table>


        <?php if ($this->_tpl_vars['offset']): ?>
                <input type='hidden' name='offset' value='<?php echo $this->_tpl_vars['offset']; ?>
'>
        <?php endif; ?>

        <?php if ($this->_tpl_vars['show_all']): ?>
                <input type='hidden' name='show_all' value='<?php echo $this->_tpl_vars['show_all']; ?>
'>
        <?php endif; ?>
<input type=hidden name=dpt value=custord>
<input type=hidden name=sub value=new_orders>
<input type=hidden name="search">
</form>
<table class="adn"><tr><td height="8"></td></tr></table>
<div align="center"><img src="data/admin/spl.gif" alt="" onclick="document.getElementById('MainForm').submit()" style="cursor: pointer;"></div>
<input type=hidden name="search"></div></td>
                               </tr>
                               <tr>
                                   <td class="se"></td>
                               </tr>
                               <tr>
                                   <td class="head2">
                                       <table class="adn">
                                           <tr>
                                               <td class="head3"><?php echo @STRING_ORD_DEL1; ?>
</td>
                                           </tr>
                                       </table>
                                   </td>
                               </tr>
                               <tr>
<td>
<div class="dvmenu"> <div align="center" style="padding-bottom: 5px;"><?php echo @STRING_ORD_DEL2; ?>
:</div><div align="center"><form method=GET action="<?php echo @ADMIN_FILE; ?>
" name="delordform" id="delordform"><select name="status_del">
                               <?php unset($this->_sections['i']);
$this->_sections['i']['name'] = 'i';
$this->_sections['i']['loop'] = is_array($_loop=$this->_tpl_vars['order_statuses']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
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
<option value="<?php echo $this->_tpl_vars['order_statuses'][$this->_sections['i']['index']]['statusID']; ?>
"> <?php echo $this->_tpl_vars['order_statuses'][$this->_sections['i']['index']]['status_name']; ?>
 </option>
<?php endfor; endif; ?>
</select>
<input type=hidden name=dpt value=custord>
<input type=hidden name=sub value=new_orders></form></div>
<table class="adn"><tr><td height="8"></td></tr></table>
<div align="center"><img src="data/admin/delkn.gif" alt="" onclick="confirmDeletef('delordform','<?php echo @QUESTION_DELETE_CONFIRMATION_ZAK; ?>
'); return false" style="cursor: pointer"></div></div></td>
                               </tr>
                               <tr>
                                   <td class="se"></td>
                               </tr>
                               <?php endif; ?>
                                 <?php if ($this->_tpl_vars['admin_sub_dpt'] == "modules_linkexchange.tpl.html"): ?>
                               <tr>
                                   <td class="head2">
                                       <table class="adn">
                                           <tr>
                                               <td class="head3"><?php echo @ADMIN_LINKS_CATC; ?>
</td>
                                           </tr>
                                       </table>
                                   </td>
                               </tr>
                               <tr>
<td>
<div class="dvmenusmall">
     <script type="text/javascript">
<!--
var _curEditCategory = Array();
_curEditCategory['le_cID'] = 0;
_curEditCategory['le_cName'] = '';
var  _cur_le_cID = '<?php echo $this->_tpl_vars['le_CategoryID']; ?>
';
var  _url_pref = '<?php echo ((is_array($_tmp="msg=&amp;page=1&amp;categoryID=")) ? $this->_run_mod_handler('set_query', true, $_tmp) : smarty_modifier_set_query($_tmp)); ?>
';
var  _request_uri = '<?php echo $this->_tpl_vars['REQUEST_URI']; ?>
';
<?php echo '

function checkBoxes(_idForm, _syncID, _checkableID){

        for(_i = 0; _i<document.getElementById(_idForm).elements.length; _i++){

                if(document.getElementById(_idForm).elements[_i].type == \'checkbox\' && document.getElementById(_idForm).elements[_i].id == _checkableID){

                        document.getElementById(_idForm).elements[_i].checked = document.getElementById(_syncID).checked;
                }
        }
        return true;
}

function show_rencat_block(_cID, _cName){
var _t = \'\';
_cName = _cName.replace(\'"\',\'&quot;\');
        while(_t != _cName){

                _t = _cName;
                _cName = _cName.replace(\'"\',\'&quot;\');
        }
        _cName = _cName.replace(\'>\',\'&gt;\');
        _cName = _cName.replace(\'<\',\'&lt;\');

        if(_curEditCategory[\'le_cID\']!=_cID){

                if(_curEditCategory[\'le_cID\']){

                        document.getElementById(\'category_renblock\').innerHTML = \'\';
                }
'; ?>

                _curEditCategory['le_cID'] = _cID;
                _curEditCategory['le_cName'] = _cName;
                document.getElementById('category_renblock').style.display = 'block';
                document.getElementById('category_renblock').innerHTML = '<form action="" method="POST" id="form_rename_linkcategory" name="catform">'+
                        '<input name="fACTION" value="SAVE_LINK_CATEGORY" type="hidden" />'+
                        '<input name="fREDIRECT" value="'+_request_uri+'" type="hidden" />'+
                        '<input name="LINK_CATEGORY[le_cID]" type="hidden" value="'+_cID+'" />'+
                        '<table class="adn"><tr class="lineb"><td align="left"><?php echo @CAT_REN; ?>
<\/td><\/tr>'+
                        '<tr class="lins"><td align="left"><input name="LINK_CATEGORY[le_cName]" value="'+_cName+'" type="text" size="40" class="textp"><\/td><\/tr><\/table>'+
                        '<table class="adn"><tr><td class="separ"><img src="core\/tpl\/admin\/images\/pixel.gif" alt="" class="sep"><\/td><\/tr><tr><td class="se5"><\/td><\/tr><\/table><a href="#" onclick="catform.submit()" class="inl"><?php echo @SAVE_BUTTON; ?>
<\/a>&nbsp;&nbsp;|&nbsp;&nbsp;<a href="#" onclick="return delete_category('+_cID+')" class="inl"><?php echo @DELETE_BUTTON; ?>
<\/a><table class="adn"><tr><td class="se6"><\/td><\/tr><\/table>'+
                        '<\/form>';
<?php echo '
        }else{

                document.getElementById(\'category_renblock\').style.display = \'none\';
                document.getElementById(\'category_renblock\').innerHTML = \'\';
                _curEditCategory[\'le_cID\'] = 0;
                _curEditCategory[\'le_cName\'] = \'\';
        }
}

function le_show_newlink(){

var lenl = document.getElementById(\'le_new_link\');
        if(lenl.style.display!=\'block\'){
                lenl.style.display = \'block\';
        }else{
                lenl.style.display = \'none\';
        }
}

function delete_category(_cID){

        if(window.confirm(\''; ?>
<?php echo @QUESTION_DELETE_CONFIRMATION; ?>
<?php echo '\')){

                document.getElementById(\'form_delete_linkcategory\').elements[\'LINK_CATEGORY[le_cID]\'].value = _cID;
                document.form_delete_linkcategory.submit();
        }
        return false;
}
'; ?>

//-->
</script>
<?php $this->assign('le_CategoryName', @ADMIN_LE_ALL_CATEGORIES); ?>
<table class="adn">
 <tr>
   <td class="l1"><img src="data/admin/dr.gif" alt=""></td>
   <td class="l2"><a href="<?php echo $this->_tpl_vars['url_allcategories']; ?>
"><?php echo @ADMIN_LE_ALL_CATEGORIES; ?>
</a></td>
   <td class="l3"><?php echo $this->_tpl_vars['le_LinksNumInCategories']; ?>
</td>
</tr></table>
<?php unset($this->_sections['i']);
$this->_sections['i']['name'] = 'i';
$this->_sections['i']['loop'] = is_array($_loop=$this->_tpl_vars['le_categories']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
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
<?php if ($this->_tpl_vars['le_categories'][$this->_sections['i']['index']]['le_cID'] == $this->_tpl_vars['le_CategoryID']): ?>
<?php $this->assign('le_CategoryName', $this->_tpl_vars['le_categories'][$this->_sections['i']['index']]['le_cName']); ?>
<?php endif; ?>
<table class="adn"><tr>
<td class="l1"><img src="data/admin/dr.gif" alt=""></td>
<td class="l2"><a href="<?php echo ((is_array($_tmp="msg=&page=1&show_all=&categoryID=".($this->_tpl_vars['le_categories'][$this->_sections['i']['index']]['le_cID']))) ? $this->_run_mod_handler('set_query', true, $_tmp) : smarty_modifier_set_query($_tmp)); ?>
"><?php echo $this->_tpl_vars['le_categories'][$this->_sections['i']['index']]['le_cName']; ?>
</a></td>
<td class="l3"><a href="#" onclick="show_rencat_block('<?php echo $this->_tpl_vars['le_categories'][$this->_sections['i']['index']]['le_cID']; ?>
', '<?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['le_categories'][$this->_sections['i']['index']]['le_cName'])) ? $this->_run_mod_handler('replace', true, $_tmp, "\\", "\\\\") : smarty_modifier_replace($_tmp, "\\", "\\\\")))) ? $this->_run_mod_handler('replace', true, $_tmp, "&#039;", "\'") : smarty_modifier_replace($_tmp, "&#039;", "\'")); ?>
');"><?php echo $this->_tpl_vars['le_categories'][$this->_sections['i']['index']]['links_num']; ?>
</a></td>
</tr>
</table>
<?php endfor; endif; ?>
</div></td></tr>
<tr>
                                   <td class="se"></td>
                               </tr>
                               <?php endif; ?>
                               <tr>
                                   <td class="head2">
                                       <table class="adn">
                                           <tr>
                                               <td class="head4" onclick="menuresetit('menu1');"><?php echo @ADMIN_CATALOG; ?>
</td>
                                               <td align="right" class="head7"><img src="data/admin/004.gif" alt="" onclick="menuresetit('menu1')" id="menu12" style="cursor: pointer;"></td>
                                           </tr>
                                       </table>
                                   </td>
                               </tr>
                               <tr id='menu13' style="display: none">
<td>
<div class="dvmenu">
<table class="adn"><tr><td><img src="data/admin/drs.gif" alt=""></td><td width="100%"><a href="<?php echo @ADMIN_FILE; ?>
?dpt=catalog&amp;sub=products_categories"><?php echo @ADMIN_CATEGORIES_PRODUCTS; ?>
</a></td></tr></table>
<table class="adn topj"><tr><td><img src="data/admin/drs.gif" alt=""></td><td width="100%"><a href="<?php echo @ADMIN_FILE; ?>
?dpt=catalog&amp;sub=dbsync"><?php echo @ADMIN_SYNCHRONIZE_TOOLS; ?>
</a></td></tr></table>
<table class="adn topj"><tr><td><img src="data/admin/drs.gif" alt=""></td><td width="100%"><a href="<?php echo @ADMIN_FILE; ?>
?dpt=catalog&amp;sub=extra"><?php echo @ADMIN_PRODUCT_OPTIONS; ?>
</a></td></tr></table>
<table class="adn topj"><tr><td><img src="data/admin/drs.gif" alt=""></td><td width="100%"><a href="<?php echo @ADMIN_FILE; ?>
?dpt=catalog&amp;sub=special"><?php echo @ADMIN_SPECIAL_OFFERS; ?>
</a></td></tr></table>
<table class="adn topj"><tr><td><img src="data/admin/drs.gif" alt=""></td><td width="100%"><a href="<?php echo @ADMIN_FILE; ?>
?dpt=catalog&amp;sub=excel_import"><?php echo @ADMIN_IMPORT_FROM_EXCEL; ?>
</a></td></tr></table>
<table class="adn topj"><tr><td><img src="data/admin/drs.gif" alt=""></td><td width="100%"><a href="<?php echo @ADMIN_FILE; ?>
?dpt=catalog&amp;sub=excel_export"><?php echo @ADMIN_EXPORT_TO_EXCEL; ?>
</a></td></tr></table>
</div></td>
                               </tr>
                               <tr>
                                   <td class="se"></td>
                               </tr>
                               <tr>
                                   <td class="head2">
                                       <table class="adn">
                                           <tr>
                                               <td class="head4" onclick="menuresetit('menu2');"><?php echo @ADMIN_CUSTOMERS_AND_ORDERS; ?>
</td>
                                               <td align="right" class="head7"><img src="data/admin/004.gif" alt="" onclick="menuresetit('menu2')" id="menu22" style="cursor: pointer;"></td>
                                           </tr>
                                       </table>
                                   </td>
                               </tr>
                               <tr id='menu23' style="display: none">
<td>
<div class="dvmenu">
<table class="adn"><tr><td><img src="data/admin/drs.gif" alt=""></td><td width="100%"><a href="<?php echo @ADMIN_FILE; ?>
?dpt=custord&amp;sub=new_orders"><?php echo @ADMIN_NEW_ORDERS; ?>
</a></td></tr></table>
<table class="adn topj"><tr><td><img src="data/admin/drs.gif" alt=""></td><td width="100%"><a href="<?php echo @ADMIN_FILE; ?>
?dpt=custord&amp;sub=subscribers"><?php echo @ADMIN_NEWS_SUBSCRIBERS; ?>
</a></td></tr></table>
<table class="adn topj"><tr><td><img src="data/admin/drs.gif" alt=""></td><td width="100%"><a href="<?php echo @ADMIN_FILE; ?>
?dpt=custord&amp;sub=order_statuses"><?php echo @ADMIN_ORDER_STATUES; ?>
</a></td></tr></table>
<table class="adn topj"><tr><td><img src="data/admin/drs.gif" alt=""></td><td width="100%"><a href="<?php echo @ADMIN_FILE; ?>
?dpt=custord&amp;sub=reg_fields"><?php echo @ADMIN_CUSTOMER_REG_FIELDS; ?>
</a></td></tr></table>
<table class="adn topj"><tr><td><img src="data/admin/drs.gif" alt=""></td><td width="100%"><a href="<?php echo @ADMIN_FILE; ?>
?dpt=custord&amp;sub=discounts"><?php echo @ADMIN_DISCOUNT_MENU; ?>
</a></td></tr></table>
<table class="adn topj"><tr><td><img src="data/admin/drs.gif" alt=""></td><td width="100%"><a href="<?php echo @ADMIN_FILE; ?>
?dpt=custord&amp;sub=aux_pages"><?php echo @ADMIN_TX7; ?>
</a></td></tr></table>
</div></td>
                               </tr>
                               <tr>
                                   <td class="se"></td>
                               </tr>
                               <tr>
                                   <td class="head2">
                                       <table class="adn">
                                           <tr>
                                               <td class="head4" onclick="menuresetit('menu3');"><?php echo @ADMIN_SETTINGS; ?>
</td>
                                               <td align="right" class="head7"><img src="data/admin/004.gif" alt="" onclick="menuresetit('menu3')" id="menu32" style="cursor: pointer;"></td>
                                           </tr>
                                       </table>
                                   </td>
                               </tr>
                               <tr id='menu33' style="display: none">
<td>
<div class="dvmenu">
<table class="adn"><tr><td><img src="data/admin/drs.gif" alt=""></td><td width="100%"><a href="<?php echo @ADMIN_FILE; ?>
?dpt=conf&amp;sub=setting&amp;settings_groupID=2"><?php echo @ADMIN_SETTINGS_GENERAL; ?>
</a></td></tr></table>
<table class="adn topj"><tr><td><img src="data/admin/drs.gif" alt=""></td><td width="100%"><a href="<?php echo @ADMIN_FILE; ?>
?dpt=conf&amp;sub=currencies"><?php echo @ADMIN_CURRENCY_TYPES; ?>
</a></td></tr></table>
<table class="adn topj"><tr><td><img src="data/admin/drs.gif" alt=""></td><td width="100%"><a href="<?php echo @ADMIN_FILE; ?>
?dpt=conf&amp;sub=shipping"><?php echo @ADMIN_STRING_SHIPPING_TYPE; ?>
</a></td></tr></table>
<table class="adn topj"><tr><td><img src="data/admin/drs.gif" alt=""></td><td width="100%"><a href="<?php echo @ADMIN_FILE; ?>
?dpt=conf&amp;sub=payment"><?php echo @ADMIN_STRING_PAYMENT_TYPE; ?>
</a></td></tr></table>
<table class="adn topj"><tr><td><img src="data/admin/drs.gif" alt=""></td><td width="100%"><a href="<?php echo @ADMIN_FILE; ?>
?dpt=conf&amp;sub=blocks_edit"><?php echo @ADMIN_TX20; ?>
</a></td></tr></table>
<table class="adn topj"><tr><td><img src="data/admin/drs.gif" alt=""></td><td width="100%"><a href="<?php echo @ADMIN_FILE; ?>
?dpt=conf&amp;sub=admin_edit"><?php echo @ADMIN_CONF_ADMINS; ?>
</a></td></tr></table>
</div></td>
                               </tr>
                               <tr>
                                   <td class="se"></td>
                               </tr>
                               <tr>
                                   <td class="head2">
                                       <table class="adn">
                                           <tr>
                                               <td class="head4" onclick="menuresetit('menu4');"><?php echo @ADMIN_MODULES; ?>
</td>
                                               <td align="right" class="head7"><img src="data/admin/004.gif" alt="" onclick="menuresetit('menu4')" id="menu42" style="cursor: pointer;"></td>
                                           </tr>
                                       </table>
                                   </td>
                               </tr>
                               <tr id='menu43' style="display: none">
<td>
<div class="dvmenu">
<table class="adn"><tr><td><img src="data/admin/drs.gif" alt=""></td><td width="100%"><a href="<?php echo @ADMIN_FILE; ?>
?dpt=modules&amp;sub=news"><?php echo @ADMIN_NEWS; ?>
</a></td></tr></table>
<table class="adn topj"><tr><td><img src="data/admin/drs.gif" alt=""></td><td width="100%"><a href="<?php echo @ADMIN_FILE; ?>
?dpt=modules&amp;sub=survey"><?php echo @ADMIN_VOTING; ?>
</a></td></tr></table>
<table class="adn topj"><tr><td><img src="data/admin/drs.gif" alt=""></td><td width="100%"><a href="<?php echo @ADMIN_FILE; ?>
?dpt=modules&amp;sub=shipping"><?php echo @ADMIN_STRING_SHIPPING_MODULES; ?>
</a></td></tr></table>
<table class="adn topj"><tr><td><img src="data/admin/drs.gif" alt=""></td><td width="100%"><a href="<?php echo @ADMIN_FILE; ?>
?dpt=modules&amp;sub=payment"><?php echo @ADMIN_STRING_PAYMENT_MODULES; ?>
</a></td></tr></table>
<table class="adn topj"><tr><td><img src="data/admin/drs.gif" alt=""></td><td width="100%"><a href="<?php echo @ADMIN_FILE; ?>
?dpt=modules&amp;sub=linkexchange"><?php echo @ADMIN_STRING_MODULES_LINKEXCHANGE; ?>
</a></td></tr></table>
<table class="adn topj"><tr><td><img src="data/admin/drs.gif" alt=""></td><td width="100%"><a href="<?php echo @ADMIN_FILE; ?>
?dpt=modules&amp;sub=yandex"><?php echo @ADMIN_STRING_YANDEX; ?>
</a></td></tr></table>
<table class="adn topj"><tr><td><img src="data/admin/drs.gif" alt=""></td><td width="100%"><a href="<?php echo @ADMIN_FILE; ?>
?dpt=custord&amp;sub=affiliate"><?php echo @STRING_AFFILIATE_PROGRAM; ?>
</a></td></tr></table>
</div></td>
                               </tr>
                               <tr>
                                   <td class="se"></td>
                               </tr>
                                   <tr>
                                       <td class="head2">
                                           <table class="adn">
                                               <tr>
                                                   <td class="head4" onclick="menuresetit('menu5');"><?php echo @ADMIN_REPORTS; ?>
</td>
                                               <td align="right" class="head7"><img src="data/admin/004.gif" alt="" onclick="menuresetit('menu5')" id="menu52" style="cursor: pointer;"></td>
                                               </tr>
                                           </table>
                                       </td>
                                   </tr>
                                   <tr id='menu53' style="display: none">
<td>
<div class="dvmenu">
<table class="adn"><tr><td><img src="data/admin/drs.gif" alt=""></td><td width="100%"><a href="<?php echo @ADMIN_FILE; ?>
?dpt=reports&amp;sub=category_viewed_times"><?php echo @ADMIN_CATEGORY_VIEWED_TIMES; ?>
</a></td></tr></table>
<table class="adn topj"><tr><td><img src="data/admin/drs.gif" alt=""></td><td width="100%"><a href="<?php echo @ADMIN_FILE; ?>
?dpt=reports&amp;sub=customer_log"><?php echo @ADMIN_CUSTOMER_LOG; ?>
</a></td></tr></table>
<table class="adn topj"><tr><td><img src="data/admin/drs.gif" alt=""></td><td width="100%"><a href="<?php echo @ADMIN_FILE; ?>
?dpt=reports&amp;sub=information"><?php echo @ADMIN_INFORMATION2; ?>
</a></td></tr></table>
<table class="adn topj"><tr><td><img src="data/admin/drs.gif" alt=""></td><td width="100%"><a href="<?php echo @ADMIN_FILE; ?>
?dpt=reports&amp;sub=coming"><?php echo @ADMIN_COMING; ?>
</a></td></tr></table>
<table class="adn topj"><tr><td><img src="data/admin/drs.gif" alt=""></td><td width="100%"><a href="<?php echo @ADMIN_FILE; ?>
?dpt=reports&amp;sub=security"><?php echo @ADMIN_SECURITY; ?>
</a></td></tr></table>
</div></td>
                                   </tr>                               <tr>
                                   <td class="se"></td>
                               </tr>  <tr>
                                       <td class="head2">
                                           <table class="adn">
                                               <tr>
                                                   <td class="head4" onclick="menuresetit('menu6');"><?php echo @ADMIN_LIST_ALL; ?>
</td>
                                               <td align="right" class="head7"><img src="data/admin/004.gif" alt="" onclick="menuresetit('menu6')" id="menu62" style="cursor: pointer;"></td>
                                               </tr>
                                           </table>
                                       </td>
                                   </tr>
                                   <tr id='menu63' style="display: none">
<td>
<div class="dvmenu">
<table class="adn"><tr><td><img src="data/admin/drs.gif" alt=""></td><td width="100%"><a href="<?php echo @ADMIN_FILE; ?>
?login=&amp;first_name=&amp;last_name=&amp;email=&amp;groupID=0&amp;fActState=-1&amp;dpt=custord&amp;sub=custlist&amp;search=Find"><?php echo @ADMIN_CUSTOMERS; ?>
</a></td></tr></table>
<table class="adn topj"><tr><td><img src="data/admin/drs.gif" alt=""></td><td width="100%"><a href="<?php echo @ADMIN_FILE; ?>
?dpt=custord&amp;sub=custgroup"><?php echo @ADMIN_CUSTGROUP; ?>
</a></td></tr></table>
<table class="adn topj"><tr><td><img src="data/admin/drs.gif" alt=""></td><td width="100%"><a href="<?php echo @ADMIN_FILE; ?>
?dpt=conf&amp;sub=countries"><?php echo @ADMIN_MENU_TOWNS; ?>
</a></td></tr></table>
<table class="adn topj"><tr><td><img src="data/admin/drs.gif" alt=""></td><td width="100%"><a href="<?php echo @ADMIN_FILE; ?>
?dpt=conf&amp;sub=zones"><?php echo @ADMIN_MENU_TAXEZ; ?>
</a></td></tr></table>
<table class="adn topj"><tr><td><img src="data/admin/drs.gif" alt=""></td><td width="100%"><a href="<?php echo @ADMIN_FILE; ?>
?dpt=catalog&amp;sub=discuss"><?php echo @ADMIN_DISCUSSIONS; ?>
</a></td></tr></table>
</div></td>
                                   </tr>
                               </table>
<?php echo '
<SCRIPT type="text/javascript">
megamenu();
</SCRIPT>
'; ?>