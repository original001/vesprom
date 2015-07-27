<?php /* Smarty version 2.6.22, created on 2015-07-20 09:46:11
         compiled from admin/reports_category_viewed_times.tpl.html */ ?>
<?php if ($this->_tpl_vars['categories']): ?>
<table class="adn">
<tr class="lineb">
<td align="left" width="100%"><?php echo @ADMIN_CATEGORY_TITLE; ?>
</td>
<td align="right"><?php echo @ADMIN_VIEW_COUNT; ?>
</td>
</tr><?php $this->assign('admhl', 0); ?>
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
<?php if ($this->_tpl_vars['admhl'] == 1): ?><tr><td colspan="2" class="separ"><img src="data/admin/pixel.gif" alt="" class="sep"></td></tr><?php else: ?><?php $this->assign('admhl', 1); ?><?php endif; ?>
<tr class="lineybig hover">
          <td align="left"><?php echo $this->_tpl_vars['categories'][$this->_sections['i']['index']]['name']; ?>
</td>
          <td align="right"><?php echo $this->_tpl_vars['categories'][$this->_sections['i']['index']]['viewed_times']; ?>
</td>
</tr>
<?php endfor; endif; ?>
</table>
<?php else: ?>
<table class="adn">
<tr class="lineb">
<td align="left">&nbsp;</td>
</tr>
<tr>
<td align="center" valign="middle" height="24"><?php echo @STRING_EMPTY_LIST; ?>
</td></tr>
</table><?php endif; ?>
<table class="adn"><tr><td class="separ"><img src="data/admin/pixel.gif" alt="" class="sep"></td></tr><tr><td class="se5"></td></tr></table>
<a href="<?php echo @ADMIN_FILE; ?>
?dpt=reports&amp;sub=category_viewed_times&amp;clear=yes" class="inl"><?php echo @ADMIN_CLEAR_CATVHISTORY; ?>
</a>
<table class="adn"><tr><td class="se6"></td></tr></table>
<table class="adn"><tr><td class="help"><span class="titlecol2"><?php echo @USEFUL_FOR_YOU; ?>
</span><div class="helptext"><?php echo @ALERT_ADMIN3; ?>
<br><br><?php echo @ALERT_ADMIN2; ?>
</div></td>
        </tr>
      </table>