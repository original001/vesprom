<?php /* Smarty version 2.6.22, created on 2015-07-28 23:02:56
         compiled from show_aux_page.tpl.html */ ?>

<div class="bread"><a href="/">Главная </a>&nbsp;/&nbsp; <?php $this->assign('preheader', "<a href='".(@ADMIN_FILE)."?dpt=custord&amp;sub=aux_pages&amp;edit=".($this->_tpl_vars['show_aux_page'])."' title='".(@EDIT_BUTTON)."' style='float: right;'>+</a>"); ?>
<?php if ($this->_tpl_vars['isadmin'] == 'yes'): ?><?php $this->assign('postheader', ($this->_tpl_vars['preheader']).($this->_tpl_vars['aux_page_name'])); ?><?php else: ?><?php $this->assign('postheader', $this->_tpl_vars['aux_page_name']); ?><?php endif; ?>

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "header.tpl.html", 'smarty_include_vars' => array('header' => $this->_tpl_vars['postheader'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?></div>

<div class="aux_page">

<table cellspacing="0" cellpadding="0" width="100%">
  <tr>
    <td class="hdbtop" align="left"><?php echo $this->_tpl_vars['page_body']; ?>
</td>
  </tr>
</table>
<table cellspacing="0" cellpadding="0" width="100%">
  <tr>
    <td class="hdbot">&nbsp;</td>
  </tr>
</table>

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "feedback.tpl.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

</div>