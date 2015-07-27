<?php /* Smarty version 2.6.22, created on 2015-07-17 15:45:58
         compiled from admin/conf_setting.tpl.html */ ?>
<?php if ($this->_tpl_vars['settings_groupID'] == 2 || $this->_tpl_vars['settings_groupID'] == 3 || $this->_tpl_vars['settings_groupID'] == 4): ?>
<form action='<?php echo @ADMIN_FILE; ?>
?dpt=conf&amp;sub=setting&amp;settings_groupID=<?php echo $this->_tpl_vars['settings_groupID']; ?>
' name='MainForm' method=post id='MainForm'>
<table class="adn">
<tr class="lineb">
<td align="left"><?php echo @CONF1; ?>
</td>
<td align="left"><table class="adn"><tr><td align="left" style="border: none; padding: 0"><?php echo @CONF2; ?>
</td><td align="right" style="border: none; padding: 0"><a href="#" onclick="document.getElementById('MainForm').submit(); return false" class="liv"><?php echo @SAVE_BUTTON; ?>
</a></td></tr></table></td>
</tr>
<?php unset($this->_sections['i']);
$this->_sections['i']['name'] = 'i';
$this->_sections['i']['loop'] = is_array($_loop=$this->_tpl_vars['settings']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
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
<tr <?php if (!($this->_sections['i']['index'] % 2)): ?>class="liney"<?php else: ?> class="liney ell"<?php endif; ?>>
 <td class="settab listsr"><?php echo $this->_tpl_vars['controls'][$this->_sections['i']['index']]; ?>
</td>
 <td class="listsl"><b><?php echo $this->_tpl_vars['settings'][$this->_sections['i']['index']]['settings_title']; ?>
</b><br><?php echo $this->_tpl_vars['settings'][$this->_sections['i']['index']]['settings_description']; ?>
</td>
</tr>
<?php endfor; endif; ?>
</table>
<input type=hidden name="save" value="">
</form>
<table class="adn"><tr><td class="separ"><img src="data/admin/pixel.gif" alt="" class="sep"></td></tr><tr><td class="se5"></td></tr></table>
<a href="#"  onclick="document.getElementById('MainForm').submit(); return false" class="inl"><?php echo @SAVE_BUTTON; ?>
</a>&nbsp;&nbsp;|&nbsp;&nbsp;<?php if ($this->_tpl_vars['settings_groupID'] == 2): ?><a href="<?php echo @ADMIN_FILE; ?>
?dpt=conf&amp;sub=setting&amp;settings_groupID=3" class="inl"><?php echo @ADMIN_IMAGES_SETTINGS; ?>
</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a href="<?php echo @ADMIN_FILE; ?>
?dpt=conf&amp;sub=setting&amp;settings_groupID=4" class="inl"><?php echo @STRING_AFFILIATE_PROGRAM; ?>
</a><?php elseif ($this->_tpl_vars['settings_groupID'] == 3): ?><a href="<?php echo @ADMIN_FILE; ?>
?dpt=conf&amp;sub=setting&amp;settings_groupID=2" class="inl"><?php echo @ADMIN_SETTINGS_GENERAL; ?>
</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a href="<?php echo @ADMIN_FILE; ?>
?dpt=conf&amp;sub=setting&amp;settings_groupID=4" class="inl"><?php echo @STRING_AFFILIATE_PROGRAM; ?>
</a><?php elseif ($this->_tpl_vars['settings_groupID'] == 4): ?><a href="<?php echo @ADMIN_FILE; ?>
?dpt=conf&amp;sub=setting&amp;settings_groupID=2" class="inl"><?php echo @ADMIN_SETTINGS_GENERAL; ?>
</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a href="<?php echo @ADMIN_FILE; ?>
?dpt=conf&amp;sub=setting&amp;settings_groupID=3" class="inl"><?php echo @ADMIN_IMAGES_SETTINGS; ?>
</a><?php endif; ?>
<?php if ($this->_tpl_vars['settings_groupID'] == 3): ?>
<table class="adn"><tr><td class="se5"></td></tr></table>
<table class="adn"><tr><td class="se5"></td></tr></table>
<table class="adn"><tr><td class="help">
<a href="<?php echo @ADMIN_FILE; ?>
?dpt=conf&amp;sub=setting&amp;settings_groupID=3&amp;resizestart=yes" class="inl"><?php echo @ADMIN_STARTLINK_IMAGES; ?>
</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a href="<?php echo @ADMIN_FILE; ?>
?dpt=conf&amp;sub=setting&amp;settings_groupID=3&amp;watermarkstart=yes" class="inl"><?php echo @ADMIN_STARTLINK_WATERMARKS; ?>
</a>
<br><br><span style="color: red;"><?php echo @ADMIN_STARTLINK_DESK; ?>
</span></td></tr></table><?php endif; ?>
<table class="adn"><tr><td class="se6"></td></tr></table>
<?php if ($this->_tpl_vars['settings_groupID'] == 3): ?>
<table class="adn"><tr><td class="help"><span class="titlecol2"><?php echo @USEFUL_FOR_YOU; ?>
</span><div class="helptext"><?php echo @ADMIN_STARTLINK_DESK2; ?>
</div></td></tr></table>
<?php else: ?>
<table class="adn"><tr><td class="help"><span class="titlecol2"><?php echo @USEFUL_FOR_YOU; ?>
</span><div class="helptext"><?php echo @ALERT_ADMIN2; ?>
</div></td></tr></table>
<?php endif; ?><?php endif; ?>