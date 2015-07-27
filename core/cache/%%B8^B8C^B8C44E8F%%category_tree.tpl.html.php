<?php /* Smarty version 2.6.22, created on 2015-07-17 15:25:44
         compiled from blocks/category_tree.tpl.html */ ?>
<?php if ($this->_tpl_vars['categories_tree'] && $this->_tpl_vars['categories_tree_count'] > 1): ?>
          <ul class="dropdown-menu" role="menu" aria-labelledby="dLabel">
            <?php unset($this->_sections['h']);
$this->_sections['h']['name'] = 'h';
$this->_sections['h']['loop'] = is_array($_loop=$this->_tpl_vars['categories_tree']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['h']['show'] = true;
$this->_sections['h']['max'] = $this->_sections['h']['loop'];
$this->_sections['h']['step'] = 1;
$this->_sections['h']['start'] = $this->_sections['h']['step'] > 0 ? 0 : $this->_sections['h']['loop']-1;
if ($this->_sections['h']['show']) {
    $this->_sections['h']['total'] = $this->_sections['h']['loop'];
    if ($this->_sections['h']['total'] == 0)
        $this->_sections['h']['show'] = false;
} else
    $this->_sections['h']['total'] = 0;
if ($this->_sections['h']['show']):

            for ($this->_sections['h']['index'] = $this->_sections['h']['start'], $this->_sections['h']['iteration'] = 1;
                 $this->_sections['h']['iteration'] <= $this->_sections['h']['total'];
                 $this->_sections['h']['index'] += $this->_sections['h']['step'], $this->_sections['h']['iteration']++):
$this->_sections['h']['rownum'] = $this->_sections['h']['iteration'];
$this->_sections['h']['index_prev'] = $this->_sections['h']['index'] - $this->_sections['h']['step'];
$this->_sections['h']['index_next'] = $this->_sections['h']['index'] + $this->_sections['h']['step'];
$this->_sections['h']['first']      = ($this->_sections['h']['iteration'] == 1);
$this->_sections['h']['last']       = ($this->_sections['h']['iteration'] == $this->_sections['h']['total']);
?>
            <?php if ($this->_tpl_vars['categories_tree'][$this->_sections['h']['index']]['categoryID'] != 1): ?>
            <?php unset($this->_sections['j']);
$this->_sections['j']['name'] = 'j';
$this->_sections['j']['loop'] = is_array($_loop=$this->_tpl_vars['categories_tree']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['j']['max'] = (int)$this->_tpl_vars['categories_tree'][$this->_sections['h']['index']]['level']-1;
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
?>
      <li class="invis"></li>
      <?php endfor; endif; ?>           
            <li><?php if (@CONF_SHOW_COUNTPROD == 1): ?><span style="float:right">&nbsp;&nbsp;<?php echo $this->_tpl_vars['categories_tree'][$this->_sections['h']['index']]['products_count']; ?>
</span><?php endif; ?><a href="<?php if (@CONF_MOD_REWRITE == 1): ?>category_<?php echo $this->_tpl_vars['categories_tree'][$this->_sections['h']['index']]['categoryID']; ?>
.html<?php else: ?>index.php?categoryID=<?php echo $this->_tpl_vars['categories_tree'][$this->_sections['h']['index']]['categoryID']; ?>
<?php endif; ?>" <?php if ($this->_tpl_vars['categoryID'] == $this->_tpl_vars['categories_tree'][$this->_sections['h']['index']]['categoryID']): ?>class=""<?php endif; ?>><?php echo $this->_tpl_vars['categories_tree'][$this->_sections['h']['index']]['name']; ?>
</a></li>
            <?php endif; ?> 
            <?php endfor; endif; ?>
          </ul><?php endif; ?>
