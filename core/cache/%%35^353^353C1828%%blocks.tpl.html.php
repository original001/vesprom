<?php /* Smarty version 2.6.22, created on 2015-07-28 23:03:34
         compiled from blocks.tpl.html */ ?>

<?php unset($this->_sections['b']);
$this->_sections['b']['name'] = 'b';
$this->_sections['b']['loop'] = is_array($_loop=$this->_tpl_vars['binfo']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['b']['show'] = true;
$this->_sections['b']['max'] = $this->_sections['b']['loop'];
$this->_sections['b']['step'] = 1;
$this->_sections['b']['start'] = $this->_sections['b']['step'] > 0 ? 0 : $this->_sections['b']['loop']-1;
if ($this->_sections['b']['show']) {
    $this->_sections['b']['total'] = $this->_sections['b']['loop'];
    if ($this->_sections['b']['total'] == 0)
        $this->_sections['b']['show'] = false;
} else
    $this->_sections['b']['total'] = 0;
if ($this->_sections['b']['show']):

            for ($this->_sections['b']['index'] = $this->_sections['b']['start'], $this->_sections['b']['iteration'] = 1;
                 $this->_sections['b']['iteration'] <= $this->_sections['b']['total'];
                 $this->_sections['b']['index'] += $this->_sections['b']['step'], $this->_sections['b']['iteration']++):
$this->_sections['b']['rownum'] = $this->_sections['b']['iteration'];
$this->_sections['b']['index_prev'] = $this->_sections['b']['index'] - $this->_sections['b']['step'];
$this->_sections['b']['index_next'] = $this->_sections['b']['index'] + $this->_sections['b']['step'];
$this->_sections['b']['first']      = ($this->_sections['b']['iteration'] == 1);
$this->_sections['b']['last']       = ($this->_sections['b']['iteration'] == $this->_sections['b']['total']);
?>





<table cellspacing="0" cellpadding="0" width="100%">
  <tr>
    <td class="<?php echo $this->_tpl_vars['bclass']; ?>
" align="<?php echo $this->_tpl_vars['balign']; ?>
"><?php if ($this->_tpl_vars['binfo'][$this->_sections['b']['index']]['html'] == 1): ?><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "blocks/".($this->_tpl_vars['binfo'][$this->_sections['b']['index']]['url']), 'smarty_include_vars' => array('blocknum' => $this->_sections['b']['index'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?><?php else: ?><?php echo $this->_tpl_vars['binfo'][$this->_sections['b']['index']]['content']; ?>
<?php endif; ?></td>
  </tr>
</table>
<?php if ($this->_tpl_vars['binfo'][$this->_sections['b']['index']]['title'] != "" && $this->_tpl_vars['nopad'] != 1): ?><table cellspacing="0" cellpadding="0" width="100%"><tr><td class="hdbot">&nbsp;</td></tr></table><?php endif; ?>

<?php endfor; endif; ?>