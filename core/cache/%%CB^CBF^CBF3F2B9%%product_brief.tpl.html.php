<?php /* Smarty version 2.6.22, created on 2015-07-17 15:25:44
         compiled from product_brief.tpl.html */ ?>

<?php if ($this->_tpl_vars['product_info'] != NULL): ?>
<?php if (@CONF_MOD_REWRITE == 1): ?>
<?php $this->assign('tlink', "<a href='product_".($this->_tpl_vars['product_info']['productID']).".html'>".($this->_tpl_vars['product_info']['name'])."</a>"); ?>
<?php else: ?>
<?php $this->assign('tlink', "<a href='index.php?productID=".($this->_tpl_vars['product_info']['productID'])."'>".($this->_tpl_vars['product_info']['name'])."</a>"); ?>
<?php endif; ?>
<?php $this->assign('preheader', "<a href='".(@ADMIN_FILE)."?productID=".($this->_tpl_vars['product_info']['productID'])."&amp;eaction=prod' title='".(@STRING_EDITPR)."' style='float: right;'>+</a>"); ?>
<?php if ($this->_tpl_vars['isadmin'] == 'yes'): ?><?php $this->assign('postheader', ($this->_tpl_vars['preheader']).($this->_tpl_vars['tlink'])); ?><?php else: ?><?php $this->assign('postheader', $this->_tpl_vars['tlink']); ?><?php endif; ?>


<div class="product">
        
        <div class="product_header">
          <div class="product_header_desc">
           <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "header.tpl.html", 'smarty_include_vars' => array('header' => $this->_tpl_vars['postheader'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
          </div>
        </div>
        
        
        <?php if (@CONF_DISPLAY_FOTO == 0): ?>
          <?php if ($this->_tpl_vars['product_info']['picture']): ?>
          <a href="<?php if (@CONF_MOD_REWRITE == 1): ?>product_<?php echo $this->_tpl_vars['product_info']['productID']; ?>
.html<?php else: ?>index.php?productID=<?php echo $this->_tpl_vars['product_info']['productID']; ?>
<?php endif; ?>" class="product_img"><img src="data/small/<?php echo $this->_tpl_vars['product_info']['picture']; ?>
" alt="<?php echo $this->_tpl_vars['product_info']['name']; ?>
"></a>
          <?php else: ?>
          <?php if (@CONF_DISPLAY_NOPHOTO == 0): ?>
          <a href="<?php if (@CONF_MOD_REWRITE == 1): ?>product_<?php echo $this->_tpl_vars['product_info']['productID']; ?>
.html<?php else: ?>index.php?productID=<?php echo $this->_tpl_vars['product_info']['productID']; ?>
<?php endif; ?>"><img src="data/empty.gif" alt="no photo"></a>
          <?php endif; ?>
          <?php endif; ?>
          <?php endif; ?>
        <?php if (@CONF_DISPLAY_FOTO == 1): ?>
          <?php if ($this->_tpl_vars['product_info']['picture']): ?>
          <a href="<?php if (@CONF_MOD_REWRITE == 1): ?>product_<?php echo $this->_tpl_vars['product_info']['productID']; ?>
.html<?php else: ?>index.php?productID=<?php echo $this->_tpl_vars['product_info']['productID']; ?>
<?php endif; ?>" class="product_img"><img src="data/small/<?php echo $this->_tpl_vars['product_info']['picture']; ?>
" alt="<?php echo $this->_tpl_vars['product_info']['name']; ?>
"></a>
          <?php else: ?>
          <?php if (@CONF_DISPLAY_NOPHOTO == 1): ?>
          <a href="<?php if (@CONF_MOD_REWRITE == 1): ?>product_<?php echo $this->_tpl_vars['product_info']['productID']; ?>
.html<?php else: ?>index.php?productID=<?php echo $this->_tpl_vars['product_info']['productID']; ?>
<?php endif; ?>"><img src="data/empty.gif" alt="no photo"></a>
          <?php endif; ?>
          <?php endif; ?>
          <?php endif; ?>
          <div class="product_menu">
          <div class="pr_desc">
               <?php if ($this->_tpl_vars['product_info']['brief_description']): ?><?php echo $this->_tpl_vars['product_info']['brief_description']; ?>
<?php endif; ?>
                          
          </div>
          <span class="drop_price">

              <?php if ($this->_tpl_vars['currencies_count'] != 0): ?><?php if ($this->_tpl_vars['product_info']['Price'] <= 0): ?><?php echo @STRING_NOPRODUCT_IN; ?>
<?php else: ?><?php echo $this->_tpl_vars['product_info']['PriceWithUnit']; ?>
<?php endif; ?><?php endif; ?>
              
              <?php if ($this->_tpl_vars['currencies_count'] != 0): ?><?php if ($this->_tpl_vars['product_info']['list_price'] > 0 && $this->_tpl_vars['product_info']['list_price'] > $this->_tpl_vars['product_info']['Price'] && $this->_tpl_vars['product_info']['Price'] > 0): ?>
              
               <?php echo $this->_tpl_vars['product_info']['list_priceWithUnit']; ?>

              
              <?php endif; ?><?php endif; ?>

          </span>
          <div class="drop_more">
           <a href="<?php if (@CONF_MOD_REWRITE == 1): ?>product_<?php echo $this->_tpl_vars['product_info']['productID']; ?>
.html<?php else: ?>index.php?productID=<?php echo $this->_tpl_vars['product_info']['productID']; ?>
<?php endif; ?>"><?php echo @STRING_MOREPR; ?>
</a>
          </div>
        </div>
       
      </div>


<?php endif; ?>