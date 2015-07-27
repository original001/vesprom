<?php /* Smarty version 2.6.22, created on 2015-07-17 15:45:05
         compiled from admin/catalog_products_categories.tpl.html */ ?>
<table class="adn">
  <tr>
    <td align="left" valign="top">
      <table class="adn">
        <tr>
          <td align="left" valign="bottom" width="100%">
            <table class="adw">
              <tr>
                <td align="left" valign="top"><span class="titlecol2"><?php if ($this->_tpl_vars['searched_done']): ?><?php echo @ADMIN_TEXT8; ?>
<?php else: ?><?php if ($this->_tpl_vars['categoryID'] != 0): ?><?php echo $this->_tpl_vars['category_name']; ?>
<?php else: ?><?php echo @ADMIN_CATEGORY_ROOT; ?>
<?php endif; ?><?php endif; ?></span></td>
              </tr>
            </table>
          </td>
          <td align="right" valign="top">
                       <form method="POST" name="search_form" action='<?php echo $this->_tpl_vars['urlToSubmit']; ?>
&amp;search=yes' id="search_form">
                           <table class="adw">
                                <tr class="lineys">
                                    <td width="100%" valign="top"></td>
                                    <td align="right" nowrap valign="middle"><b><?php echo @ADMIN_SEARCH_PRODUCT_IN_CATEGORY; ?>
:</b>&nbsp;<select name="search_criteria" title="<?php echo @ADMIN_ADMIN_MENUNEW5; ?>
">
                                        <option value='name' <?php if ($this->_tpl_vars['search_criteria'] == 'name'): ?> selected <?php endif; ?> > <?php echo @TABLE_PRODUCT_NAME; ?>
 </option>
                                        <option value='product_code' <?php if ($this->_tpl_vars['search_criteria'] == 'product_code'): ?> selected <?php endif; ?> > <?php echo @ADMIN_PRODUCT_CODE; ?>
 </option></select></td>
                                    <td align="right" nowrap valign="middle"><input type="text" name="search_value" value="<?php echo $this->_tpl_vars['search_value']; ?>
" title="<?php echo @ADMIN_ADMIN_MENUNEW4; ?>
" class="new"></td>
                                    <td align="right" nowrap valign="middle"><a href="#" onclick="document.getElementById('search_form').submit(); return false"><img src="data/admin/srg.gif" alt="search"></a></td>
                                </tr>
                           </table>
                        </form>
                    </td>
                </tr>
            </table>

        </td>
    </tr>
    <tr>
        <td class="se"></td>
    </tr>
    <tr>
        <td valign="top" align="left" colspan="5">
        <?php if (! $this->_tpl_vars['products'] && ! $this->_tpl_vars['searched_done']): ?>
            <table class="adn">
                <tr class="lineb">
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td align="center" valign="middle" height="20"><?php echo @STRING_EMPTY_CATEGORY; ?>
</td>
            </tr>
            <tr>
                <td class="separ"><img src="data/admin/pixel.gif" alt="" class="sep"></td></tr>
            <tr>
                <td class="se5"></td>
            </tr>
            </table>
       <table class="adw">
       <tr class="link">
       <td><a href="<?php echo @ADMIN_FILE; ?>
?categoryID=<?php echo $this->_tpl_vars['categoryID']; ?>
&amp;eaction=prod" class="inl"><?php echo @ADMIN_TEXT3; ?>
</a>&nbsp;&nbsp;|&nbsp;&nbsp;</td>
       <td><a href="<?php echo @ADMIN_FILE; ?>
?w=-1<?php if ($this->_tpl_vars['categoryID'] && $this->_tpl_vars['categoryID'] != 1): ?>&amp;catslct=<?php echo $this->_tpl_vars['categoryID']; ?>
<?php endif; ?>&amp;eaction=cat" class="inl"><?php echo @ADMIN_TEXT4; ?>
</a></td><?php if ($this->_tpl_vars['categoryID'] && $this->_tpl_vars['categoryID'] != 1): ?><td>&nbsp;&nbsp;|&nbsp;&nbsp;<a href="<?php echo @ADMIN_FILE; ?>
?categoryID=<?php echo $this->_tpl_vars['categoryID']; ?>
&amp;eaction=cat" class="inl"><?php echo @ADMIN_ADMIN_MENUNEW1; ?>
</a></td><?php endif; ?>
       </tr>
       </table>
         <?php else: ?>
         <?php if ($this->_tpl_vars['couldntToDelete'] == 1): ?><br><font color="red"><b><?php echo @COULD_NOT_DELETE_THIS_PRODUCT; ?>
</b></font><br><br><?php endif; ?>
         <?php if ($this->_tpl_vars['couldntToDeleteThisProducts']): ?><br><font color="red"><b><?php echo @COULD_NOT_DELETE_THESE_PRODUCT; ?>
</b></font><br><br><?php endif; ?>
         <form action='<?php echo $this->_tpl_vars['urlToSubmit']; ?>
' method="POST" name="form" id="form">
         <?php if ($this->_tpl_vars['products']): ?>
         <script type="text/javascript">
<!--
<?php echo '
function checkBoxes_products(_idForm, _syncID, _checkableID){

if(document.getElementById(_syncID).checked == false){
'; ?>

<?php unset($this->_sections['i']);
$this->_sections['i']['name'] = 'i';
$this->_sections['i']['loop'] = is_array($_loop=$this->_tpl_vars['products']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
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
document.getElementById('checkbox_products_id_<?php echo $this->_tpl_vars['products'][$this->_sections['i']['index']]['productID']; ?>
').checked = false;
<?php endfor; endif; ?>
<?php echo '
}else{
'; ?>

<?php unset($this->_sections['i']);
$this->_sections['i']['name'] = 'i';
$this->_sections['i']['loop'] = is_array($_loop=$this->_tpl_vars['products']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
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
document.getElementById('checkbox_products_id_<?php echo $this->_tpl_vars['products'][$this->_sections['i']['index']]['productID']; ?>
').checked = true;
<?php endfor; endif; ?>
<?php echo '
}
return true;
}
'; ?>

//-->
</script>
           <table class="adn">
                 <tr class="lineb"><td align="center" valign="middle"><input type="checkbox" class="round" id="id_checkall" onclick="checkBoxes_products('MainForm', 'id_checkall', 'id_ch');"  title="<?php echo @ADMIN_SEL_TITLEALL; ?>
"></td>
                  <td align="center"><?php echo @ADMIN_PRODUCT_ENABLED; ?>
</td>
                  <td align="left" width="100%"><a href='<?php echo $this->_tpl_vars['urlToSort']; ?>
&amp;sort=name&amp;sort_dir=ASC'  title="<?php echo @ADMIN_ADMIN_MENUNEW11; ?>
" class="liv"><?php echo @ADMIN_PRODUCT_NAME; ?>
</a></td>
                  <td align="right"><a href='<?php echo $this->_tpl_vars['urlToSort']; ?>
&amp;sort=product_code&amp;sort_dir=ASC'  title="<?php echo @ADMIN_ADMIN_MENUNEW11; ?>
" class="liv"><?php echo @ADMIN_PRODUCT_CODE; ?>
</a></td>
                  <td align="right"><a href='<?php echo $this->_tpl_vars['urlToSort']; ?>
&amp;sort=Price&amp;sort_dir=ASC' title="<?php echo @ADMIN_ADMIN_MENUNEW11; ?>
" class="liv"><?php echo @ADMIN_PRODUCT_PRICE; ?>
</a></td>
                  <?php if (@CONF_CHECKSTOCK == 1): ?>
                  <td align="right"><a href='<?php echo $this->_tpl_vars['urlToSort']; ?>
&amp;sort=in_stock&amp;sort_dir=ASC' title="<?php echo @ADMIN_ADMIN_MENUNEW11; ?>
" class="liv"><?php echo @ADMIN_PRODUCT_INSTOCK; ?>
</a></td>
                  <?php endif; ?>
                  <td align="right"><a href='<?php echo $this->_tpl_vars['urlToSort']; ?>
&amp;sort=sort_order&amp;sort_dir=ASC' title="<?php echo @ADMIN_ADMIN_MENUNEW11; ?>
" class="liv"><?php echo @ADMIN_SORTM; ?>
</a></td>
                  <td align="center"><a href='<?php echo $this->_tpl_vars['urlToSort']; ?>
&amp;sort=viewed_times&amp;sort_dir=DESC' title="<?php echo @ADMIN_ADMIN_MENUNEW10; ?>
" class="liv">VT</a></td>
                  <td align="center"><a href='<?php echo $this->_tpl_vars['urlToSort']; ?>
&amp;sort=items_sold&amp;sort_dir=DESC' title="<?php echo @ADMIN_ADMIN_MENUNEW10; ?>
" class="liv">SL</a></td>
                  <?php if (@CONF_USE_RATING == 1): ?>
                  <td align="center"><a href='<?php echo $this->_tpl_vars['urlToSort']; ?>
&amp;sort=customers_rating&amp;sort_dir=DESC' title="<?php echo @ADMIN_ADMIN_MENUNEW10; ?>
" class="liv">PR</a></td>
                  <?php endif; ?>
                  <td align="center"><?php echo @ADMIN_SPECIAL; ?>
</td>
                  <td align="center"><?php echo @ADMIN_ON3; ?>
</td>
                </tr>
                <?php $this->assign('admhl', 0); ?>
                <?php unset($this->_sections['i']);
$this->_sections['i']['name'] = 'i';
$this->_sections['i']['loop'] = is_array($_loop=$this->_tpl_vars['products']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
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
                <?php if ($this->_tpl_vars['admhl'] == 1): ?><tr><td colspan="15" class="separ"><img src="data/admin/pixel.gif" alt="" class="sep"></td></tr><?php else: ?><?php $this->assign('admhl', 1); ?><?php endif; ?>
                <tr class="liney hover">
                  <td align="center"><input type="checkbox" class="round" name="checkbox_products_id_<?php echo $this->_tpl_vars['products'][$this->_sections['i']['index']]['productID']; ?>
" id="checkbox_products_id_<?php echo $this->_tpl_vars['products'][$this->_sections['i']['index']]['productID']; ?>
" title="<?php echo @ADMIN_SEL_TITLE; ?>
"></td>

                  <td align="center"><input type="hidden" name="enable_<?php echo $this->_tpl_vars['products'][$this->_sections['i']['index']]['productID']; ?>
" id="enable_<?php echo $this->_tpl_vars['products'][$this->_sections['i']['index']]['productID']; ?>
" <?php if ($this->_tpl_vars['products'][$this->_sections['i']['index']]['enabled']): ?>value='1'<?php else: ?>value='0'<?php endif; ?> ><input type="checkbox" class="round" name="checkbo_en_<?php echo $this->_tpl_vars['products'][$this->_sections['i']['index']]['productID']; ?>
" id="checkbo_en_<?php echo $this->_tpl_vars['products'][$this->_sections['i']['index']]['productID']; ?>
" <?php if ($this->_tpl_vars['products'][$this->_sections['i']['index']]['enabled']): ?>checked<?php endif; ?> onclick='CheckBoxHandler(<?php echo $this->_tpl_vars['products'][$this->_sections['i']['index']]['productID']; ?>
)'  title="<?php echo @ADMIN_ADMIN_MENUNEW6; ?>
"></td>

                  <td align="left"><a href="<?php echo @ADMIN_FILE; ?>
?productID=<?php echo $this->_tpl_vars['products'][$this->_sections['i']['index']]['productID']; ?>
&amp;eaction=prod" title="<?php echo @ADMIN_ADMIN_MENUNEW9; ?>
" <?php if (! $this->_tpl_vars['products'][$this->_sections['i']['index']]['enabled']): ?>class="greyy"<?php endif; ?>><?php echo $this->_tpl_vars['products'][$this->_sections['i']['index']]['name']; ?>
</a></td>

                  <td align="right" <?php if (! $this->_tpl_vars['products'][$this->_sections['i']['index']]['enabled']): ?>class="toph3 gryy"<?php else: ?>class="toph3<?php if (! $this->_tpl_vars['products'][$this->_sections['i']['index']]['product_code']): ?> bas<?php endif; ?>"<?php endif; ?>><?php if ($this->_tpl_vars['products'][$this->_sections['i']['index']]['product_code']): ?><?php echo $this->_tpl_vars['products'][$this->_sections['i']['index']]['product_code']; ?>
<?php else: ?><?php echo @ADMIN_NOCODE_PROD; ?>
<?php endif; ?></td>

                  <td align="right"><input type="text" name="price_<?php echo $this->_tpl_vars['products'][$this->_sections['i']['index']]['productID']; ?>
" value="<?php echo $this->_tpl_vars['products'][$this->_sections['i']['index']]['Price']; ?>
" class="prc prcs<?php if (! $this->_tpl_vars['products'][$this->_sections['i']['index']]['enabled']): ?> gryy<?php endif; ?>"></td>

                  <?php if (@CONF_CHECKSTOCK == 1): ?>
                  <td align="right"><?php if ($this->_tpl_vars['products'][$this->_sections['i']['index']]['in_stock'] <= 0): ?><input type="text" name="left_<?php echo $this->_tpl_vars['products'][$this->_sections['i']['index']]['productID']; ?>
" value="<?php echo $this->_tpl_vars['products'][$this->_sections['i']['index']]['in_stock']; ?>
" class="prc prcss<?php if (! $this->_tpl_vars['products'][$this->_sections['i']['index']]['enabled']): ?> gryy<?php endif; ?>"><?php else: ?><input type="text" name="left_<?php echo $this->_tpl_vars['products'][$this->_sections['i']['index']]['productID']; ?>
" value="<?php echo $this->_tpl_vars['products'][$this->_sections['i']['index']]['in_stock']; ?>
" class="prc prcss <?php if (! $this->_tpl_vars['products'][$this->_sections['i']['index']]['enabled']): ?>gryy<?php endif; ?>"><?php endif; ?></td>
                  <?php endif; ?>

                  <td align="right"><input name='sort_order_<?php echo $this->_tpl_vars['products'][$this->_sections['i']['index']]['productID']; ?>
' type='text' class="prc prcss<?php if (! $this->_tpl_vars['products'][$this->_sections['i']['index']]['enabled']): ?> gryy<?php endif; ?>" value="<?php echo $this->_tpl_vars['products'][$this->_sections['i']['index']]['sort_order']; ?>
"></td>
                  <td align="center" <?php if (! $this->_tpl_vars['products'][$this->_sections['i']['index']]['enabled']): ?>class="toph3 gryy"<?php else: ?>class="toph3"<?php endif; ?>><?php echo $this->_tpl_vars['products'][$this->_sections['i']['index']]['viewed_times']; ?>
</td>
                  <td align="center" <?php if (! $this->_tpl_vars['products'][$this->_sections['i']['index']]['enabled']): ?>class="toph3 gryy"<?php else: ?>class="toph3"<?php endif; ?>><?php echo $this->_tpl_vars['products'][$this->_sections['i']['index']]['items_sold']; ?>
</td>
                  <?php if (@CONF_USE_RATING == 1): ?>
                  <td align="center" <?php if (! $this->_tpl_vars['products'][$this->_sections['i']['index']]['enabled']): ?>class="toph3 gryy"<?php else: ?>class="toph3"<?php endif; ?>><?php echo $this->_tpl_vars['products'][$this->_sections['i']['index']]['customers_rating']; ?>
</td>
                  <?php endif; ?>

                  <td align="center"><?php if ($this->_tpl_vars['products'][$this->_sections['i']['index']]['picture_count'] != 0): ?><a href="<?php echo @ADMIN_FILE; ?>
?dpt=catalog&amp;sub=special&amp;new_offer=<?php echo $this->_tpl_vars['products'][$this->_sections['i']['index']]['productID']; ?>
" title="<?php echo @ADMIN_ADMIN_MENUNEW7; ?>
" <?php if (! $this->_tpl_vars['products'][$this->_sections['i']['index']]['enabled']): ?>class="greyy"<?php endif; ?>>+</a><?php else: ?>&nbsp;<?php endif; ?></td>
                  <td align="center"><a href="#" onclick="confirmDelete(<?php echo $this->_tpl_vars['products'][$this->_sections['i']['index']]['productID']; ?>
,'<?php echo @QUESTION_DELETE_CONFIRMATION_PROD; ?>
','<?php echo $this->_tpl_vars['urlToDelete']; ?>
&amp;terminate='); return false" title="<?php echo @QUESTION_DELETE_CONFIRMATION_PROD; ?>
" <?php if (! $this->_tpl_vars['products'][$this->_sections['i']['index']]['enabled']): ?>class="greyy"<?php endif; ?>>X</a></td>
                  </tr>
                  <?php endfor; endif; ?>

                  <?php if ($this->_tpl_vars['navigatorHtml']): ?>
                  <tr>
                    <td class="navigator" colspan="15"><?php echo $this->_tpl_vars['navigatorHtml']; ?>
</td>
                  </tr>
                  <?php else: ?>
                  <tr>
                    <td class="separ" colspan="15"><img src="data/admin/pixel.gif" alt="" class="sep"></td>
                  </tr>
                  <?php endif; ?>
                  <tr>
                    <td class="se5" colspan="15"></td>
                  </tr>
                </table>
        <?php else: ?>
        <?php if ($this->_tpl_vars['searched_done'] && ! $this->_tpl_vars['products']): ?>
            <table class="adn">
            <tr class="lineb">
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td align="center" valign="middle" height="20"><?php echo $this->_tpl_vars['searched_count']; ?>
</td>
            </tr>
            <tr>
                <td class="separ"><img src="data/admin/pixel.gif" alt="" class="sep"></td></tr>
            <tr>
                <td class="se5"></td>
            </tr>
            </table>
        <?php endif; ?>
        <?php endif; ?>
        <input type="hidden" name="dpt" value="catalog">
        <input type="hidden" name="sub" value="products_categories">
        <input type="hidden" name="categoryID" value="<?php echo $this->_tpl_vars['categoryID']; ?>
">
        <input type="hidden" name="products_update">
        <input type="hidden" name="add_command" value="off" id="add_command">

       <table class="adw">
       <tr class="link">
       <?php if ($this->_tpl_vars['products']): ?><td><a href="#" onclick="document.getElementById('form').submit(); return false" class="inl"><?php echo @ADMIN_TEXT7; ?>
</a>&nbsp;&nbsp;|&nbsp;&nbsp;</td><?php endif; ?>
       <?php if (! $this->_tpl_vars['searched_done']): ?><td><a href="#" class="inl" onclick="confirmDelete(0,'<?php echo @QUESTION_DELETE_CONFIRMATION; ?>
','<?php echo @ADMIN_FILE; ?>
?dpt=catalog&amp;sub=products_categories&amp;categoryID=<?php echo $this->_tpl_vars['categoryID']; ?>
&amp;delete_all_products=1'); return false"><?php echo @ADMIN_TEXT9; ?>
</a>&nbsp;&nbsp;|&nbsp;&nbsp;</td><?php endif; ?>
       <td><a href="<?php echo @ADMIN_FILE; ?>
?categoryID=<?php echo $this->_tpl_vars['categoryID']; ?>
&amp;eaction=prod" class="inl"><?php echo @ADMIN_TEXT3; ?>
</a>&nbsp;&nbsp;|&nbsp;&nbsp;</td>
       <td><a href="<?php echo @ADMIN_FILE; ?>
?w=-1<?php if ($this->_tpl_vars['categoryID'] && $this->_tpl_vars['categoryID'] != 1): ?>&amp;catslct=<?php echo $this->_tpl_vars['categoryID']; ?>
<?php endif; ?>&amp;eaction=cat" class="inl"><?php echo @ADMIN_TEXT4; ?>
</a></td><?php if ($this->_tpl_vars['categoryID'] && $this->_tpl_vars['categoryID'] != 1): ?><td>&nbsp;&nbsp;|&nbsp;&nbsp;<a href="<?php echo @ADMIN_FILE; ?>
?categoryID=<?php echo $this->_tpl_vars['categoryID']; ?>
&amp;eaction=cat" class="inl"><?php echo @ADMIN_ADMIN_MENUNEW1; ?>
</a></td><?php endif; ?>
       </tr></table>
       <?php if ($this->_tpl_vars['products']): ?><table class="adw"><tr><td class="se5"></td></tr><tr class="link"><td>C отмеченными:&nbsp;&nbsp;<a href="#" onclick="document.getElementById('add_command').value='prod_on'; document.getElementById('form').submit(); return false" class="inl">Включить</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a href="#" onclick="document.getElementById('add_command').value='prod_off'; document.getElementById('form').submit(); return false" class="inl">Выключить</a>

<!-- Вставка копирования товара -->
&nbsp;&nbsp;|&nbsp;&nbsp;<a href="#" onclick="document.getElementById('add_command').value='prod_copy'; document.getElementById('form').submit(); return false" class="inl">Копировать</a>
<!-- end -->	   
	   
	   &nbsp;&nbsp;|&nbsp;&nbsp;<a href="#" onclick="document.getElementById('add_command').value='prod_dell'; document.getElementById('form').submit(); return false" class="inl">Удалить</a>&nbsp;&nbsp;|&nbsp;&nbsp;
<select name="prod_categoryID"><option value="1" selected><?php echo @ADMIN_CATEGORY_ROOT; ?>
</option>
<?php unset($this->_sections['z']);
$this->_sections['z']['name'] = 'z';
$this->_sections['z']['loop'] = is_array($_loop=$this->_tpl_vars['cats']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
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
<option value="<?php echo $this->_tpl_vars['cats'][$this->_sections['z']['index']]['categoryID']; ?>
"><?php unset($this->_sections['j']);
$this->_sections['j']['name'] = 'j';
$this->_sections['j']['loop'] = is_array($_loop=$this->_tpl_vars['cats'][$this->_sections['z']['index']]['level']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['j']['show'] = true;
$this->_sections['j']['max'] = $this->_sections['j']['loop'];
$this->_sections['j']['step'] = 1;
$this->_sections['j']['start'] = $this->_sections['j']['step'] > 0 ? 0 : $this->_sections['j']['loop']-1;
if ($this->_sections['j']['show']) {
    $this->_sections['j']['total'] = $this->_sections['j']['loop'];
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
?>&nbsp;&nbsp;&nbsp;<?php endfor; endif; ?><?php echo $this->_tpl_vars['cats'][$this->_sections['z']['index']]['name']; ?>
</option>
<?php endfor; endif; ?>
</select>&nbsp;&nbsp;<a href="#" onclick="document.getElementById('add_command').value='prod_move'; document.getElementById('form').submit(); return false" class="inl">Переместить</a></td></tr></table><?php endif; ?>
       </form>
       <?php endif; ?>
<table class="adn"><tr><td class="se6"></td></tr></table>
        <table class="adn"><tr><td class="help"><span class="titlecol2"><?php echo @USEFUL_FOR_YOU; ?>
</span><div class="helptext"><?php echo @ALERT_ADMIN; ?>
</div></td></tr></table>
        </td></tr></table>
<?php if ($this->_tpl_vars['products']): ?>
<script type="text/javascript">
function CheckBoxHandler(id)<?php echo '{'; ?>

if ( document.getElementById('checkbo_en_' + id).checked )
document.getElementById('enable_' + id).value = '1';
else
document.getElementById('enable_' + id).value = '0';
<?php echo '}'; ?>

</script>
<?php endif; ?>
<?php if ($this->_tpl_vars['products_count_category']): ?>
<script type="text/javascript">
        if(document.getElementById('preproc'))<?php echo '{'; ?>

        document.getElementById('preproc').innerHTML='<span id="axproc" style="color: #C5D2ED; font-size: 11px;"><?php echo @PRODUCTS_IN_CATTEK; ?>
 <?php echo $this->_tpl_vars['products_count_category']; ?>
<\/span>';
        <?php echo '}'; ?>

</script>
<?php endif; ?>