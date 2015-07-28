<?php /* Smarty version 2.6.22, created on 2015-07-28 23:03:28
         compiled from category.tpl.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'fu_make_url', 'category.tpl.html', 11, false),)), $this); ?>

<?php $this->assign('preheader', "<a href='".(@ADMIN_FILE)."?categoryID=".($this->_tpl_vars['selected_category']['categoryID'])."&amp;eaction=cat' title='".(@ADMIN_ADMIN_MENUNEW1)."' style='float: right;'>+</a>"); ?>
<?php if ($this->_tpl_vars['isadmin'] == 'yes'): ?><?php $this->assign('postheader', ($this->_tpl_vars['preheader']).($this->_tpl_vars['categoryName'])); ?><?php else: ?><?php $this->assign('postheader', $this->_tpl_vars['categoryName']); ?><?php endif; ?>




  
    <div class="bread"><?php if (@CONF_SHOW_COUNTPROD == 1): ?><span style="float: right;"><?php echo @STRING_COUNTPROD; ?>
: <?php echo $this->_tpl_vars['products_to_showc']; ?>
</span><?php endif; ?><a href="<?php echo @CONF_FULL_SHOP_URL; ?>
"><?php echo @LINK_TO_HOMEPAGE; ?>
</a><?php unset($this->_sections['i']);
$this->_sections['i']['name'] = 'i';
$this->_sections['i']['loop'] = is_array($_loop=$this->_tpl_vars['product_category_path']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
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
?><?php if ($this->_tpl_vars['product_category_path'][$this->_sections['i']['index']]['categoryID'] != 1): ?> &nbsp;/&nbsp; <a href="<?php echo fu_make_url($this->_tpl_vars['product_category_path'][$this->_sections['i']['index']]); ?>
"><?php echo $this->_tpl_vars['product_category_path'][$this->_sections['i']['index']]['name']; ?>
</a><?php endif; ?><?php endfor; endif; ?></div>
 


<?php if ($this->_tpl_vars['catrescur']): ?>
<?php unset($this->_sections['r']);
$this->_sections['r']['name'] = 'r';
$this->_sections['r']['loop'] = is_array($_loop=$this->_tpl_vars['catrescur']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['r']['show'] = true;
$this->_sections['r']['max'] = $this->_sections['r']['loop'];
$this->_sections['r']['step'] = 1;
$this->_sections['r']['start'] = $this->_sections['r']['step'] > 0 ? 0 : $this->_sections['r']['loop']-1;
if ($this->_sections['r']['show']) {
    $this->_sections['r']['total'] = $this->_sections['r']['loop'];
    if ($this->_sections['r']['total'] == 0)
        $this->_sections['r']['show'] = false;
} else
    $this->_sections['r']['total'] = 0;
if ($this->_sections['r']['show']):

            for ($this->_sections['r']['index'] = $this->_sections['r']['start'], $this->_sections['r']['iteration'] = 1;
                 $this->_sections['r']['iteration'] <= $this->_sections['r']['total'];
                 $this->_sections['r']['index'] += $this->_sections['r']['step'], $this->_sections['r']['iteration']++):
$this->_sections['r']['rownum'] = $this->_sections['r']['iteration'];
$this->_sections['r']['index_prev'] = $this->_sections['r']['index'] - $this->_sections['r']['step'];
$this->_sections['r']['index_next'] = $this->_sections['r']['index'] + $this->_sections['r']['step'];
$this->_sections['r']['first']      = ($this->_sections['r']['iteration'] == 1);
$this->_sections['r']['last']       = ($this->_sections['r']['iteration'] == $this->_sections['r']['total']);
?>
<?php if ($this->_tpl_vars['catrescur'][$this->_sections['r']['index']][3] != ""): ?>
<?php if (@CONF_MOD_REWRITE == 1): ?>
<?php $this->assign('tlink', "<a href='category_".($this->_tpl_vars['catrescur'][$this->_sections['r']['index']]['categoryID']).".html'>".($this->_tpl_vars['catrescur'][$this->_sections['r']['index']]['name'])."</a>"); ?>
<?php else: ?>
<?php $this->assign('tlink', "<a href='index.php?categoryID=".($this->_tpl_vars['catrescur'][$this->_sections['r']['index']]['categoryID'])."'>".($this->_tpl_vars['catrescur'][$this->_sections['r']['index']]['name'])."</a>"); ?>
<?php endif; ?>

<a href="<?php if (@CONF_MOD_REWRITE == 1): ?>category_<?php echo $this->_tpl_vars['catrescur'][$this->_sections['r']['index']]['categoryID']; ?>
.html<?php else: ?>index.php?categoryID=<?php echo $this->_tpl_vars['catrescur'][$this->_sections['r']['index']]['categoryID']; ?>
<?php endif; ?>" class="content">
        <div class="header"><?php echo $this->_tpl_vars['catrescur'][$this->_sections['r']['index']]['name']; ?>
</div>
        <div class="img"><img src="data/category/<?php echo $this->_tpl_vars['catrescur'][$this->_sections['r']['index']]['picture']; ?>
" alt="<?php echo $this->_tpl_vars['catrescur'][$this->_sections['r']['index']]['name']; ?>
" title="<?php echo $this->_tpl_vars['catrescur'][$this->_sections['r']['index']]['name']; ?>
"></div>
        <div class="desc"><?php echo $this->_tpl_vars['catrescur'][$this->_sections['r']['index']]['description']; ?>

        </div>
</a>

<?php endif; ?>
<?php endfor; endif; ?>
<?php endif; ?>

<?php if ($this->_tpl_vars['products_to_show']): ?>


 <?php if ($this->_tpl_vars['string_product_sort']): ?>
<table cellspacing="0" cellpadding="0" width="100%">
  <tr>
    <td class="cattop" align="center"><?php echo $this->_tpl_vars['string_product_sort']; ?>
</td>
  </tr>
</table>
<?php endif; ?>


<?php if ($this->_tpl_vars['catalog_navigator']): ?>
<table cellspacing="0" cellpadding="0" width="100%">
  <tr>
    <td class="cattop" align="center"><?php echo $this->_tpl_vars['catalog_navigator']; ?>
</td>
  </tr>
</table>
<?php endif; ?>


  <?php unset($this->_sections['u']);
$this->_sections['u']['name'] = 'u';
$this->_sections['u']['loop'] = is_array($_loop=$this->_tpl_vars['products_to_show']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['u']['show'] = true;
$this->_sections['u']['max'] = $this->_sections['u']['loop'];
$this->_sections['u']['step'] = 1;
$this->_sections['u']['start'] = $this->_sections['u']['step'] > 0 ? 0 : $this->_sections['u']['loop']-1;
if ($this->_sections['u']['show']) {
    $this->_sections['u']['total'] = $this->_sections['u']['loop'];
    if ($this->_sections['u']['total'] == 0)
        $this->_sections['u']['show'] = false;
} else
    $this->_sections['u']['total'] = 0;
if ($this->_sections['u']['show']):

            for ($this->_sections['u']['index'] = $this->_sections['u']['start'], $this->_sections['u']['iteration'] = 1;
                 $this->_sections['u']['iteration'] <= $this->_sections['u']['total'];
                 $this->_sections['u']['index'] += $this->_sections['u']['step'], $this->_sections['u']['iteration']++):
$this->_sections['u']['rownum'] = $this->_sections['u']['iteration'];
$this->_sections['u']['index_prev'] = $this->_sections['u']['index'] - $this->_sections['u']['step'];
$this->_sections['u']['index_next'] = $this->_sections['u']['index'] + $this->_sections['u']['step'];
$this->_sections['u']['first']      = ($this->_sections['u']['iteration'] == 1);
$this->_sections['u']['last']       = ($this->_sections['u']['iteration'] == $this->_sections['u']['total']);
?>
  <?php if (!($this->_sections['u']['index'] % @CONF_COLUMNS_PER_PAGE)): ?><tr><?php $this->assign('helpcounter', 0); ?><?php endif; ?><?php $this->assign('helpcounter', $this->_tpl_vars['helpcounter']+1); ?>
    
    <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "product_brief.tpl.html", 'smarty_include_vars' => array('product_info' => $this->_tpl_vars['products_to_show'][$this->_sections['u']['index']])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
 
    <?php if (!(( $this->_sections['u']['index']+1 ) % @CONF_COLUMNS_PER_PAGE) || $this->_tpl_vars['products_to_show_counter'] == $this->_sections['u']['index']+1): ?>
  
  <?php unset($this->_sections['e']);
$this->_sections['e']['name'] = 'e';
$this->_sections['e']['loop'] = is_array($_loop=@CONF_COLUMNS_PER_PAGE) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['e']['max'] = (int)$this->_tpl_vars['helpcounter'];
$this->_sections['e']['show'] = true;
if ($this->_sections['e']['max'] < 0)
    $this->_sections['e']['max'] = $this->_sections['e']['loop'];
$this->_sections['e']['step'] = 1;
$this->_sections['e']['start'] = $this->_sections['e']['step'] > 0 ? 0 : $this->_sections['e']['loop']-1;
if ($this->_sections['e']['show']) {
    $this->_sections['e']['total'] = min(ceil(($this->_sections['e']['step'] > 0 ? $this->_sections['e']['loop'] - $this->_sections['e']['start'] : $this->_sections['e']['start']+1)/abs($this->_sections['e']['step'])), $this->_sections['e']['max']);
    if ($this->_sections['e']['total'] == 0)
        $this->_sections['e']['show'] = false;
} else
    $this->_sections['e']['total'] = 0;
if ($this->_sections['e']['show']):

            for ($this->_sections['e']['index'] = $this->_sections['e']['start'], $this->_sections['e']['iteration'] = 1;
                 $this->_sections['e']['iteration'] <= $this->_sections['e']['total'];
                 $this->_sections['e']['index'] += $this->_sections['e']['step'], $this->_sections['e']['iteration']++):
$this->_sections['e']['rownum'] = $this->_sections['e']['iteration'];
$this->_sections['e']['index_prev'] = $this->_sections['e']['index'] - $this->_sections['e']['step'];
$this->_sections['e']['index_next'] = $this->_sections['e']['index'] + $this->_sections['e']['step'];
$this->_sections['e']['first']      = ($this->_sections['e']['iteration'] == 1);
$this->_sections['e']['last']       = ($this->_sections['e']['iteration'] == $this->_sections['e']['total']);
?>
    <?php $this->assign('idnrecat', $this->_sections['u']['index']-$this->_tpl_vars['helpcounter']+$this->_sections['e']['index']+1); ?>
   
    
    <?php endfor; endif; ?>
    
  <?php else: ?><?php endif; ?>
  <?php endfor; endif; ?>



<?php if ($this->_tpl_vars['catalog_navigator']): ?>
<table cellspacing="0" cellpadding="0" width="100%">
  <tr>
    <td class="cattop" align="center"><?php echo $this->_tpl_vars['catalog_navigator']; ?>
</td>
  </tr>
</table>
<?php endif; ?>


<?php if ($this->_tpl_vars['string_product_sort']): ?>
<table cellspacing="0" cellpadding="0" width="100%">
  <tr>
    <td class="cattop" align="center"><?php echo $this->_tpl_vars['string_product_sort']; ?>
</td>
  </tr>
</table>
<?php endif; ?>

<?php else: ?>
<?php endif; ?>


<?php if ($this->_tpl_vars['categorylinkscat']): ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "header.tpl.html", 'smarty_include_vars' => array('header' => @STRING_CAT_USE_AUX)));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<table cellspacing="0" cellpadding="0" width="100%">
  <tr>
    <td class="hdbtop" align="left"><?php $this->assign('sett', 0); ?>
      <?php unset($this->_sections['icat']);
$this->_sections['icat']['name'] = 'icat';
$this->_sections['icat']['loop'] = is_array($_loop=$this->_tpl_vars['categorylinkscat']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['icat']['show'] = true;
$this->_sections['icat']['max'] = $this->_sections['icat']['loop'];
$this->_sections['icat']['step'] = 1;
$this->_sections['icat']['start'] = $this->_sections['icat']['step'] > 0 ? 0 : $this->_sections['icat']['loop']-1;
if ($this->_sections['icat']['show']) {
    $this->_sections['icat']['total'] = $this->_sections['icat']['loop'];
    if ($this->_sections['icat']['total'] == 0)
        $this->_sections['icat']['show'] = false;
} else
    $this->_sections['icat']['total'] = 0;
if ($this->_sections['icat']['show']):

            for ($this->_sections['icat']['index'] = $this->_sections['icat']['start'], $this->_sections['icat']['iteration'] = 1;
                 $this->_sections['icat']['iteration'] <= $this->_sections['icat']['total'];
                 $this->_sections['icat']['index'] += $this->_sections['icat']['step'], $this->_sections['icat']['iteration']++):
$this->_sections['icat']['rownum'] = $this->_sections['icat']['iteration'];
$this->_sections['icat']['index_prev'] = $this->_sections['icat']['index'] - $this->_sections['icat']['step'];
$this->_sections['icat']['index_next'] = $this->_sections['icat']['index'] + $this->_sections['icat']['step'];
$this->_sections['icat']['first']      = ($this->_sections['icat']['iteration'] == 1);
$this->_sections['icat']['last']       = ($this->_sections['icat']['iteration'] == $this->_sections['icat']['total']);
?>
      <div <?php if ($this->_tpl_vars['sett'] == 1): ?>style="margin-top: 4px;"<?php else: ?><?php $this->assign('sett', 1); ?><?php endif; ?>><a href="<?php if (@CONF_MOD_REWRITE == 1): ?>page_<?php echo $this->_tpl_vars['categorylinkscat'][$this->_sections['icat']['index']][0]; ?>
.html<?php else: ?>index.php?show_aux_page=<?php echo $this->_tpl_vars['categorylinkscat'][$this->_sections['icat']['index']][0]; ?>
<?php endif; ?>"><?php echo $this->_tpl_vars['categorylinkscat'][$this->_sections['icat']['index']][1]; ?>
</a></div>
      <?php endfor; endif; ?>
  </td>
  </tr>
</table>
<table cellspacing="0" cellpadding="0" width="100%">
  <tr>
    <td class="hdbot">&nbsp;</td>
  </tr>
</table>
<?php endif; ?>