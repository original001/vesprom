<?php /* Smarty version 2.6.22, created on 2015-07-17 15:39:32
         compiled from admin/catalog_extra.tpl.html */ ?>
<form action="<?php echo @ADMIN_FILE; ?>
" method=POST name="formext" id="formext">
<?php if ($this->_tpl_vars['option_name'] == null): ?>
<table class="adn"><tr class="lineb">
<td align="left" width="100%" ><?php echo @ADMIN_CUSTOM_OPTION_TITLE; ?>
</td>
<td align="left">Фильтр1</td>
<td align="left">Фильтр2</td>
<td align="left">Фильтр3</td>
<td align="left">Тип фильтра</td>
<td align="left" class="toph3"><?php echo @ADMIN_VALUE_VARIANTS; ?>
</td>
<td align="right"><?php echo @ADMIN_SORT_ORDER; ?>
</td>
<td align="center">Del</td></tr>
<?php unset($this->_sections['i']);
$this->_sections['i']['name'] = 'i';
$this->_sections['i']['loop'] = is_array($_loop=$this->_tpl_vars['options']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
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
<tr><td height="4" colspan="4"></td></tr>
<tr class="liney">
<td align="left"><input type=text class="textp" name="extra_option_<?php echo $this->_tpl_vars['options'][$this->_sections['i']['index']][0]; ?>
" value="<?php echo $this->_tpl_vars['options'][$this->_sections['i']['index']][1]; ?>
" size="46"></td>
<td align="left"><input type="checkbox" class="round" name="extra_filter1_<?php echo $this->_tpl_vars['options'][$this->_sections['i']['index']]['optionID']; ?>
" <?php if ($this->_tpl_vars['options'][$this->_sections['i']['index']]['filter1']): ?>checked<?php endif; ?> title="Показывать характеристику в первом фильтре"></td>
<td align="left"><input type="checkbox" class="round" name="extra_filter2_<?php echo $this->_tpl_vars['options'][$this->_sections['i']['index']]['optionID']; ?>
" <?php if ($this->_tpl_vars['options'][$this->_sections['i']['index']]['filter2']): ?>checked<?php endif; ?> title="Показывать характеристику во втором фильтре"></td>
<td align="left"><input type="checkbox" class="round" name="extra_filter3_<?php echo $this->_tpl_vars['options'][$this->_sections['i']['index']]['optionID']; ?>
" <?php if ($this->_tpl_vars['options'][$this->_sections['i']['index']]['filter3']): ?>checked<?php endif; ?> title="Показывать характеристику в третьем фильтре"></td>
<td align="left">
  <select name="extra_type_<?php echo $this->_tpl_vars['options'][$this->_sections['i']['index']]['optionID']; ?>
" title="Показывать характеристику как чекбоксы, строку ввода, слайдер или селект">
        <option value="0"<?php if ($this->_tpl_vars['options'][$this->_sections['i']['index']]['filter_type'] == 0): ?> selected<?php endif; ?>>Чекбоксы</option>
        <option value="1"<?php if ($this->_tpl_vars['options'][$this->_sections['i']['index']]['filter_type'] == 1): ?> selected<?php endif; ?>>Строка</option>
        <option value="2"<?php if ($this->_tpl_vars['options'][$this->_sections['i']['index']]['filter_type'] == 2): ?> selected<?php endif; ?>>Слайдер</option>
        <option value="3"<?php if ($this->_tpl_vars['options'][$this->_sections['i']['index']]['filter_type'] == 3): ?> selected<?php endif; ?>>Селект</option>
  </select>
</td>
<td align="left" valign="middle" class="toph3"><span style="float: right;">(<?php echo $this->_tpl_vars['options'][$this->_sections['i']['index']]['count_variants']; ?>
)</span><a href="<?php echo @ADMIN_FILE; ?>
?dpt=catalog&amp;sub=extra&amp;optionID=<?php echo $this->_tpl_vars['options'][$this->_sections['i']['index']][0]; ?>
" class="inl"><?php echo @ADMIN_VALUE_VARIANTS; ?>
</a></td>
<td align="right"><input name="extra_sort_<?php echo $this->_tpl_vars['options'][$this->_sections['i']['index']][0]; ?>
" type=text class="textp" value="<?php echo $this->_tpl_vars['options'][$this->_sections['i']['index']][2]; ?>
" size="4"></td>
<td align="center" valign="middle"><a href="#" onclick="confirmDelete(<?php echo $this->_tpl_vars['options'][$this->_sections['i']['index']][0]; ?>
,'<?php echo @DELETE_BUTTON; ?>
?','<?php echo @ADMIN_FILE; ?>
?dpt=catalog&amp;sub=extra&amp;kill_option='); return false" title="<?php echo @ADMIN_ADMIN_MENUNEW8; ?>
">X</a></td>
</tr>
<?php endfor; else: ?>
<tr><td height="4" colspan="4"></td></tr>
<tr><td colspan="4" align="center" height="20"><?php echo @ADMIN_NO_PRODUCT_OPTIONS; ?>
</td></tr>
<?php endif; ?>
<tr><td height="4" colspan="4"></td></tr>
</table>
<table class="adn"><tr><td class="separ"><img src="data/admin/pixel.gif" alt="" class="sep"></td></tr><tr><td class="se5"></td></tr></table>
<a href="#" onclick="document.getElementById('formext').submit(); return false" class="inl"><?php echo @SAVE_BUTTON; ?>
</a>
<table class="adn"><tr><td class="se6"></td></tr></table>
<table class="adn"><tr class="lineb">
<td align="left"><?php echo @ADMIN_CUSTOM_OPTION_TITLE; ?>
</td>
<td align="left" width="100%"><?php echo @ADMIN_SORT_ORDER; ?>
</td>
</tr>
<tr class="lins">
<td align="left"><input type=text name="add_option" value=""  class="textp" size="46"></td>
<td align="left"><input name="add_sort" type=text value="" class="textp" size="4"></td>
</tr></table>
<table class="adn"><tr><td class="separ"><img src="data/admin/pixel.gif" alt="" class="sep"></td></tr><tr><td class="se5"></td></tr></table>
<input type=hidden name="dpt" value="catalog">
<input type=hidden name="sub" value="extra">
<input type=hidden name="save_options" value="yes">
<a href="#" onclick="document.getElementById('formext').submit(); return false" class="inl"><?php echo @ADMIN_ADD_NEW_OPTION; ?>
</a>
<?php else: ?>
<table class="adn">
<tr class="linsz">
<td align="left" style="padding-top: 0;"><span class="titlecol2"><?php echo $this->_tpl_vars['option_name']; ?>
</span></td>
</tr></table>
<table class="adn"><tr class="lineb">
<td align="left" class="toph3" width="100%"><?php echo @ADMIN_ONE_VALUE; ?>
</td>
<td align="right"><?php echo @ADMIN_SORT_ORDER; ?>
</td>
<td align="center">Del</td>
<?php unset($this->_sections['i']);
$this->_sections['i']['name'] = 'i';
$this->_sections['i']['loop'] = is_array($_loop=$this->_tpl_vars['values']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
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
<tr><td height="4" colspan="3"></td></tr>
<tr class="liney">
<td align="left"><input name="option_value_<?php echo $this->_tpl_vars['values'][$this->_sections['i']['index']]['variantID']; ?>
" type=text value="<?php echo $this->_tpl_vars['values'][$this->_sections['i']['index']]['option_value']; ?>
" class="textp" size="30"></td>
<td align="right"><input type=text name="sort_order_<?php echo $this->_tpl_vars['values'][$this->_sections['i']['index']]['variantID']; ?>
" value="<?php echo $this->_tpl_vars['values'][$this->_sections['i']['index']]['sort_order']; ?>
" class="textp" size="3"></td>
<td align="center" valign="middle"><a href="#" onclick="confirmDelete(<?php echo $this->_tpl_vars['values'][$this->_sections['i']['index']]['variantID']; ?>
,'<?php echo @QUESTION_DELETE_CONFIRMATION; ?>
', '<?php echo @ADMIN_FILE; ?>
?dpt=catalog&amp;sub=extra&amp;optionID=<?php echo $this->_tpl_vars['values'][$this->_sections['i']['index']]['optionID']; ?>
&amp;kill_value=');" title="<?php echo @ADMIN_ADMIN_MENUNEW8; ?>
">X</a></td>
</tr>
<?php endfor; else: ?>
<tr><td height="4" colspan="3"></td></tr>
<tr><td colspan="3" align="center" height="20"><?php echo @ADMIN_NO_VALUES; ?>
</td></tr>
<?php endif; ?>
<tr><td height="4" colspan="3"></td></tr>
</table>
<table class="adn"><tr><td class="separ"><img src="data/admin/pixel.gif" alt="" class="sep"></td></tr><tr><td class="se5"></td></tr></table>
<input type=hidden name="dpt" value="catalog">
<input type=hidden name="sub" value="extra">
<input type=hidden name="save_values" value="yes">
<input type=hidden name="optionID" value="<?php echo $this->_tpl_vars['optionID']; ?>
">
<a href="#" onclick="document.getElementById('formext').submit(); return false" class="inl"><?php echo @SAVE_BUTTON; ?>
</a>
<table class="adn"><tr><td class="se6"></td></tr></table>
<table class="adn"><tr class="lineb">
<td align="left"><?php echo @ADMIN_OPTION47; ?>
</td>
<td align="left" width="100%"><?php echo @ADMIN_SORT_ORDER; ?>
</td></tr>
<tr class="lins">
<td align="left"><input name="add_value" type=text value="" class="textp" size="46"></td>
<td align="left"><input type=text name="add_sort" value="" class="textp" size="4"></td>
</tr>
</table>
<table class="adn"><tr><td class="separ"><img src="data/admin/pixel.gif" alt="" class="sep"></td></tr><tr><td class="se5"></td></tr></table>
<a href="#" onclick="document.getElementById('formext').submit(); return false" class="inl"><?php echo @ADMIN_ADD_VALUE; ?>
</a><?php if ($this->_tpl_vars['option_name'] != null): ?>&nbsp;&nbsp;|&nbsp;&nbsp;<a href="<?php echo @ADMIN_FILE; ?>
?dpt=catalog&amp;sub=extra" class="inl"><?php echo @ADMIN_OPIK3; ?>
</a><?php endif; ?>
<?php endif; ?>
</form>
<table class="adn"><tr><td class="se6"></td></tr></table>
<table class="adn"><tr><td class="help"><span class="titlecol2"><?php echo @USEFUL_FOR_YOU; ?>
</span><div class="helptext"><?php echo @ALERT_ADMIN12; ?>
</div></td>
</tr>
</table>