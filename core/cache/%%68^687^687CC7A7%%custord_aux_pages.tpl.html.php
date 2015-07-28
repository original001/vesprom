<?php /* Smarty version 2.6.22, created on 2015-07-28 23:00:38
         compiled from admin/custord_aux_pages.tpl.html */ ?>
<?php if ($this->_tpl_vars['edit'] || $this->_tpl_vars['add_new']): ?><?php if ($this->_tpl_vars['edit']): ?>
<form action='<?php echo @ADMIN_FILE; ?>
?dpt=custord&amp;sub=aux_pages&amp;edit=<?php echo $this->_tpl_vars['aux_page']['aux_page_ID']; ?>
' method=post name="formaxp" id="formaxp">
<?php else: ?>
<form action='<?php echo @ADMIN_FILE; ?>
?dpt=custord&amp;sub=aux_pages&amp;add_new=yes' method=post name="formaxp" id="formaxp">
<?php endif; ?>
<table class="adn">
<tr class="linsz">
<td align="left" style="padding-top: 0;"><span class="titlecol2"><?php echo @AUX_PAGE_NAME; ?>
</span></td>
</tr>
<tr>
<td align="left"><input name="aux_page_name" type="text" value='<?php echo $this->_tpl_vars['aux_page']['aux_page_name']; ?>
' style="width: 500px;" class="textp"></td>
</tr><tr><td class="se5"></td></tr>
<tr class="linsz">
<td align="left" style="padding-top: 0;"><span class="titlecol2"><?php echo @ADMIN_PRODUCT_TITLE_PAGE; ?>
</span></td>
</tr>
<tr>
<td align="left"><input name="aux_page_title" type="text" value='<?php echo $this->_tpl_vars['aux_page']['aux_page_title']; ?>
' style="width: 500px;" class="textp"></td>
</tr>
<?php if (@CONF_EDITOR == 0): ?>
<tr class="linsz">
<td align="left"><input type="checkbox" class="round" name="aux_page_text_type" value="1"
                                <?php if ($this->_tpl_vars['aux_page']['aux_page_text_type'] == 1): ?>
                                        checked
                                <?php endif; ?>
>&nbsp;&nbsp;<?php echo @ADMIN_AXP1; ?>
</td>
</tr></table><?php else: ?></table><input type="hidden" name="aux_page_text_type" value="1"><?php endif; ?>
<table class="adn"><tr><td class="se5"></td></tr></table>
<table class="adn">
<tr class="linsz">
<td align="left"><span class="titlecol2"><?php echo @AUX_PAGE_TEXT; ?>
</span></td>
</tr>
<tr>
<td><textarea name="aux_page_text" id="areapg" class="admin"><?php echo $this->_tpl_vars['aux_page']['aux_page_text']; ?>
</textarea></td>
</tr></table>
<table class="adn"><tr><td class="se6"></td></tr></table>
<table class="adw">
<tr><td width="50%">
<table class="adn">
<tr class="linsz">
<td align="left"><span class="titlecol2"><?php echo @ADMIN_META_DESCRIPTION; ?>
</span></td>
</tr>
<tr><td align="left"><textarea name='meta_description' id='meta_description' class="adminall" style="margin-right: 38px;"><?php echo $this->_tpl_vars['aux_page']['meta_description']; ?>
</textarea>
</td></tr>
</table>
</td>
<td width="50%">
<table class="adn">
<tr class="linsz">
<td align="left"><span class="titlecol2"><?php echo @ADMIN_META_KEYWORDS; ?>
</span></td>
</tr>
<tr><td align="left"><textarea name='meta_keywords' id='meta_keywords' class="adminall"><?php echo $this->_tpl_vars['aux_page']['meta_keywords']; ?>
</textarea>
</td></tr>
</table>
</td></tr></table>
<table class="adn"><tr><td class="se5"></td></tr></table>
<a href="#" onclick="document.getElementById('formaxp').submit(); return false" class="inl"><?php echo @SAVE_BUTTON; ?>
</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a href="<?php echo @ADMIN_FILE; ?>
?dpt=custord&amp;sub=aux_pages" class="inl"><?php echo @ADMIN_TX2; ?>
</a>
<input type=hidden value='1' name='save'>
</form>
<?php if (@CONF_EDITOR): ?>
<?php echo '
<script type="text/javascript" src="fckeditor/fckeditor.js"></script>
<script type="text/javascript" src="fckeditor/ckfinder/ckfinder.js"></script>
<script type="text/javascript">
window.onload = function()
{
var oFCKeditor = new FCKeditor( \'areapg\',720,346) ;
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
<table class="adn">
<tr class="lineb">
<td align="left" class="toph3"><?php echo @AUX_PAGE_NAME; ?>
</td>
<td align="left"><?php echo @AUX_PAGE_TEXT_TYPE; ?>
</td>
<td align="left" width="100%" class="toph3"><?php echo @AUX_PAGE_REF; ?>
</td>
<td align="left"><?php echo @AUX_PAGE_EDIT; ?>
</td>
</tr>
<?php $this->assign('admhl', 0); ?>
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
<?php if ($this->_tpl_vars['admhl'] == 1): ?>
<tr><td colspan="4" class="separ"><img src="data/admin/pixel.gif" alt="" class="sep"></td></tr>
<?php else: ?><?php $this->assign('admhl', 1); ?><?php endif; ?>
<tr class="lineybig hover">
<td align="left"><?php echo $this->_tpl_vars['aux_pages'][$this->_sections['i']['index']]['aux_page_name']; ?>
</td>
<td align="left"><?php if ($this->_tpl_vars['aux_pages'][$this->_sections['i']['index']]['aux_page_text_type'] == 0): ?> TEXT <?php else: ?> HTML <?php endif; ?></td>
<td align="left">&lt;a href="<?php if (@CONF_MOD_REWRITE == 1): ?>page_<?php echo $this->_tpl_vars['aux_pages'][$this->_sections['i']['index']]['aux_page_ID']; ?>
.html"&gt;<?php echo $this->_tpl_vars['aux_pages'][$this->_sections['i']['index']]['aux_page_name']; ?>
<?php else: ?>index.php?show_aux_page=<?php echo $this->_tpl_vars['aux_pages'][$this->_sections['i']['index']]['aux_page_ID']; ?>
"&gt;<?php echo $this->_tpl_vars['aux_pages'][$this->_sections['i']['index']]['aux_page_name']; ?>
<?php endif; ?>&lt;/a&gt;</td>
<td align="center"><a href="<?php if (@CONF_MOD_REWRITE == 1): ?>page_<?php echo $this->_tpl_vars['aux_pages'][$this->_sections['i']['index']]['aux_page_ID']; ?>
.html<?php else: ?>index.php?show_aux_page=<?php echo $this->_tpl_vars['aux_pages'][$this->_sections['i']['index']]['aux_page_ID']; ?>
<?php endif; ?>" target="_blank"><?php echo @ADMIN_SHOW_AUX_PAGE; ?>
</a>&nbsp;|&nbsp;<a href="<?php echo @ADMIN_FILE; ?>
?dpt=custord&amp;sub=aux_pages&amp;edit=<?php echo $this->_tpl_vars['aux_pages'][$this->_sections['i']['index']]['aux_page_ID']; ?>
"><?php echo @ADMIN_EDIT_SMALL; ?>
</a>&nbsp;|&nbsp;<a href="#" onclick="open_window('<?php echo @ADMIN_FILE; ?>
?do=wishcat&owner=<?php echo $this->_tpl_vars['aux_pages'][$this->_sections['i']['index']]['aux_page_ID']; ?>
',500,500)"><?php echo @ADMIN_AUX_CAT; ?>
</a>&nbsp;|&nbsp;<a href="#" onclick="open_window('<?php echo @ADMIN_FILE; ?>
?do=wishprod&owner=<?php echo $this->_tpl_vars['aux_pages'][$this->_sections['i']['index']]['aux_page_ID']; ?>
',500,500)"><?php echo @ADMIN_AUX_PROD; ?>
</a>&nbsp;|&nbsp;<a href="#" onclick="confirmDelete(<?php echo $this->_tpl_vars['aux_pages'][$this->_sections['i']['index']]['aux_page_ID']; ?>
,'<?php echo @QUESTION_DELETE_CONFIRMATION; ?>
','<?php echo @ADMIN_FILE; ?>
?dpt=custord&amp;sub=aux_pages&amp;delete=');">X</a></td>
</tr>
<?php endfor; else: ?>
<tr><td colspan="4" align="center" height="20"><?php echo @ADMIN_NO_VALUES; ?>
</td></tr>
<?php endif; ?>
</table>
<table class="adn"><tr><td class="separ"><img src="data/admin/pixel.gif" alt="" class="sep"></td></tr><tr><td class="se5"></td></tr></table>
<a href="<?php echo @ADMIN_FILE; ?>
?dpt=custord&amp;sub=aux_pages&amp;add_new=yes" class="inl"><?php echo @ADD_PAGE; ?>
</a>
<?php endif; ?>
<table class="adn"><tr><td class="se6"></td></tr></table>
<table class="adn"><tr><td class="help"><span class="titlecol2"><?php echo @USEFUL_FOR_YOU; ?>
</span><div class="helptext"><?php echo @ALERT_ADMIN21; ?>
</div></td>
        </tr>
      </table>