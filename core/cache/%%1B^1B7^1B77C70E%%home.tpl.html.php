<?php /* Smarty version 2.6.22, created on 2015-07-28 23:03:34
         compiled from home.tpl.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'math', 'home.tpl.html', 9, false),)), $this); ?>

<?php if ($this->_tpl_vars['special_offers']): ?>
<table cellspacing="0" cellpadding="0" width="100%">
  <?php unset($this->_sections['i']);
$this->_sections['i']['name'] = 'i';
$this->_sections['i']['loop'] = is_array($_loop=$this->_tpl_vars['special_offers']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
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
  <?php if (!($this->_sections['i']['index'] % @CONF_TAB_COUNT_IN_HOME)): ?>
  <tr>
  <?php endif; ?>
    <td width="<?php echo smarty_function_math(array('equation' => "100 / x",'x' => @CONF_TAB_COUNT_IN_HOME,'format' => "%d"), $this);?>
%" align="left" valign="top">

		
	<?php if (@CONF_MOD_REWRITE == 1): ?>
      <?php $this->assign('link', "<a href='product_".($this->_tpl_vars['special_offers'][$this->_sections['i']['index']]['productID']).".html'>".($this->_tpl_vars['special_offers'][$this->_sections['i']['index']]['name'])."</a>"); ?>
    <?php else: ?>
      <?php $this->assign('link', "<a href='index.php?productID=".($this->_tpl_vars['special_offers'][$this->_sections['i']['index']]['productID'])."'>".($this->_tpl_vars['special_offers'][$this->_sections['i']['index']]['name'])."</a>"); ?>
    <?php endif; ?>
    <?php $this->assign('preheader', "<a href='".(@ADMIN_FILE)."?productID=".($this->_tpl_vars['special_offers'][$this->_sections['i']['index']]['productID'])."&amp;eaction=prod' title='".(@STRING_EDITPR)."' style='float: right;'>+</a>"); ?>
    <?php if ($this->_tpl_vars['isadmin'] == 'yes'): ?><?php $this->assign('postheader', ($this->_tpl_vars['preheader']).($this->_tpl_vars['link'])); ?><?php else: ?><?php $this->assign('postheader', $this->_tpl_vars['link']); ?><?php endif; ?>

		
	<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "header.tpl.html", 'smarty_include_vars' => array('header' => $this->_tpl_vars['postheader'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
	
		
	<table cellspacing="0" cellpadding="0" width="100%">
	  <tr>
	    <td class="hdbtop" align="left">
          <table cellspacing="0" cellpadding="0" width="100%">
            <tr>
              <td valign="middle" align="left">
                <table cellspacing="0" cellpadding="0" style="margin-right: 8px;">
                  <tr>
				    <td class="price"><?php if ($this->_tpl_vars['special_offers'][$this->_sections['i']['index']]['cena'] <= 0): ?><?php echo @STRING_NOPRODUCT_IN; ?>
<?php else: ?><?php echo $this->_tpl_vars['special_offers'][$this->_sections['i']['index']]['Price']; ?>
<?php endif; ?></td>
				  </tr>
                </table>
              </td>
              <td align="right" width="100%">
			    
				                
				<table cellspacing="0" cellpadding="0" align="right">
				  <tr>
				    <td style="border-left: 1px solid #CCCCCC; padding-left: 8px;" align="left">
                      <?php if (@CONF_SHOW_ADD2CART == 1 && @CONF_DISP_INDEXCART == 1): ?>
                      <form action="index.php?categoryID=<?php echo $this->_tpl_vars['special_offers'][$this->_sections['i']['index']]['categoryID']; ?>
&amp;prdID=<?php echo $this->_tpl_vars['special_offers'][$this->_sections['i']['index']]['productID']; ?>
" method=post id="HiddenFieldsForm_<?php echo $this->_tpl_vars['special_offers'][$this->_sections['i']['index']]['productID']; ?>
" name="HiddenFieldsForm_<?php echo $this->_tpl_vars['special_offers'][$this->_sections['i']['index']]['productID']; ?>
">
                        <?php if (@CONF_OPEN_SHOPPING_CART_IN_NEW_WINDOW == 1): ?>
					    <table cellspacing="0" cellpadding="0">
						  <tr>
						    <td><a href="#" onclick="open_window('index.php?do=cart&amp;addproduct=<?php echo $this->_tpl_vars['special_offers'][$this->_sections['i']['index']]['productID']; ?>
&amp;multyaddcount='+document.HiddenFieldsForm_<?php echo $this->_tpl_vars['special_offers'][$this->_sections['i']['index']]['productID']; ?>
.multyaddcount.value+'',400,300);"><img src="data/<?php echo @TPL; ?>
/crt.gif" alt=""></a></td>
							<td style="padding-left: 4px;"><input type=<?php if (@CONF_MULTYCART == 0): ?>hidden<?php else: ?>text<?php endif; ?> value="1" name="multyaddcount" <?php if (@CONF_MULTYCART == 1): ?>size="2" style="margin-right: 4px; width: 16px;"<?php endif; ?>><a href="#" onclick="open_window('index.php?do=cart&amp;addproduct=<?php echo $this->_tpl_vars['special_offers'][$this->_sections['i']['index']]['productID']; ?>
&amp;multyaddcount='+document.HiddenFieldsForm_<?php echo $this->_tpl_vars['special_offers'][$this->_sections['i']['index']]['productID']; ?>
.multyaddcount.value+'',400,300);"><?php echo @ADD_TO_CART_STRING; ?>
</a></td>
						  </tr>
						</table>
						<?php else: ?>
						  <?php if (@CONF_CART_METHOD == 2): ?>
						  <table cellspacing="0" cellpadding="0">
						    <tr>
						      <td><a href="#" onclick="doLoad('do=cart&amp;addproduct=<?php echo $this->_tpl_vars['special_offers'][$this->_sections['i']['index']]['productID']; ?>
&amp;xcart=yes&amp;multyaddcount='+document.HiddenFieldsForm_<?php echo $this->_tpl_vars['special_offers'][$this->_sections['i']['index']]['productID']; ?>
.multyaddcount.value+''); return false"><img src="data/<?php echo @TPL; ?>
/crt.gif" alt=""></a></td>
							  <td style="padding-left: 4px;"><input type=<?php if (@CONF_MULTYCART == 0): ?>hidden<?php else: ?>text<?php endif; ?> value="1" name="multyaddcount" <?php if (@CONF_MULTYCART == 1): ?>size="2" style="margin-right: 4px; width: 16px;"<?php endif; ?>><a href="#" onclick="doLoad('do=cart&amp;addproduct=<?php echo $this->_tpl_vars['special_offers'][$this->_sections['i']['index']]['productID']; ?>
&amp;xcart=yes&amp;multyaddcount='+document.HiddenFieldsForm_<?php echo $this->_tpl_vars['special_offers'][$this->_sections['i']['index']]['productID']; ?>
.multyaddcount.value+''); return false"><?php echo @ADD_TO_CART_STRING; ?>
</a></td>
						    </tr>
						  </table>
						  <?php else: ?>
						  <table cellspacing="0" cellpadding="0">
						    <tr>
						      <td><a href="#" onclick="document.getElementById('HiddenFieldsForm_<?php echo $this->_tpl_vars['special_offers'][$this->_sections['i']['index']]['productID']; ?>
').submit(); return false"><img src="data/<?php echo @TPL; ?>
/crt.gif" alt=""></a></td>
							  <td style="padding-left: 4px;"><input type=<?php if (@CONF_MULTYCART == 0): ?>hidden<?php else: ?>text<?php endif; ?> value="1" name="multyaddcount" <?php if (@CONF_MULTYCART == 1): ?>size="2" style="margin-right: 4px; width: 16px;"<?php endif; ?>><input type="hidden" name="cart_<?php echo $this->_tpl_vars['special_offers'][$this->_sections['i']['index']]['productID']; ?>
_x" value="<?php echo $this->_tpl_vars['special_offers'][$this->_sections['i']['index']]['productID']; ?>
"><a href="#" onclick="document.getElementById('HiddenFieldsForm_<?php echo $this->_tpl_vars['special_offers'][$this->_sections['i']['index']]['productID']; ?>
').submit(); return false"><?php echo @ADD_TO_CART_STRING; ?>
</a></td>
						    </tr>
						  </table>
						  <?php endif; ?>
					    <?php endif; ?>
					  </form>
                      <?php endif; ?>
					</td>
			      </tr>
				</table>
			  </td>
			</tr>
	      </table>
		</td>
      </tr>
	</table>
    
	    
	<table cellspacing="0" cellpadding="0" width="100%"><tr><td width="100%" style="background-color: #CCCCCC; height: 1px;"></td></tr></table>
    
		
	<table cellspacing="0" cellpadding="0" width="100%">
      <tr>
	    <td class="hdbtop" valign="top" align="left">
          <table cellspacing="0" cellpadding="0" width="100%">
		    <tr>
              
			  			  
			  <?php if (@CONF_DISPLAY_FOTO == 1): ?>
              <td class="imboxl"><div align="right" style="position: relative; float: right;"><div class="semafor sl"><a href="<?php if (@CONF_MOD_REWRITE == 1): ?>product_<?php echo $this->_tpl_vars['special_offers'][$this->_sections['i']['index']]['productID']; ?>
.html<?php else: ?>index.php?productID=<?php echo $this->_tpl_vars['special_offers'][$this->_sections['i']['index']]['productID']; ?>
<?php endif; ?>"><img src="data/<?php echo @TPL; ?>
/pixel.gif" style="margin: 0px;" alt="" width="70" height="70"></a></div><a href="<?php if (@CONF_MOD_REWRITE == 1): ?>product_<?php echo $this->_tpl_vars['special_offers'][$this->_sections['i']['index']]['productID']; ?>
.html<?php else: ?>index.php?productID=<?php echo $this->_tpl_vars['special_offers'][$this->_sections['i']['index']]['productID']; ?>
<?php endif; ?>"><img src="data/<?php echo $this->_tpl_vars['special_offers'][$this->_sections['i']['index']]['default_picture']; ?>
" alt="<?php echo $this->_tpl_vars['special_offers'][$this->_sections['i']['index']]['name']; ?>
"></a></div></td>
			  			  <?php endif; ?>
			  
			  			  
			  <td width="100%" align="left" valign="top"><?php if ($this->_tpl_vars['special_offers'][$this->_sections['i']['index']]['brief_description']): ?><?php echo $this->_tpl_vars['special_offers'][$this->_sections['i']['index']]['brief_description']; ?>
<?php endif; ?></td>

			  			  
			  <?php if (@CONF_DISPLAY_FOTO == 0): ?>
			  <td class="imboxr"><div align="right" style="position: relative; float: right;"><div class="semafor sr"><a href="<?php if (@CONF_MOD_REWRITE == 1): ?>product_<?php echo $this->_tpl_vars['special_offers'][$this->_sections['i']['index']]['productID']; ?>
.html<?php else: ?>index.php?productID=<?php echo $this->_tpl_vars['special_offers'][$this->_sections['i']['index']]['productID']; ?>
<?php endif; ?>"><img src="data/<?php echo @TPL; ?>
/pixel.gif" style="margin: 0px;" alt="" width="70" height="70"></a></div><a href="<?php if (@CONF_MOD_REWRITE == 1): ?>product_<?php echo $this->_tpl_vars['special_offers'][$this->_sections['i']['index']]['productID']; ?>
.html<?php else: ?>index.php?productID=<?php echo $this->_tpl_vars['special_offers'][$this->_sections['i']['index']]['productID']; ?>
<?php endif; ?>"><img src="data/<?php echo $this->_tpl_vars['special_offers'][$this->_sections['i']['index']]['default_picture']; ?>
" alt="<?php echo $this->_tpl_vars['special_offers'][$this->_sections['i']['index']]['name']; ?>
"></a></div></td>
			  			  <?php endif; ?>
			
			</tr>
		  </table>
		</td>
	  </tr>
	</table>
	
		
	<table cellspacing="0" cellpadding="0" width="100%">
	  <tr>
	    <td class="hdbot"><a href="<?php if (@CONF_MOD_REWRITE == 1): ?>product_<?php echo $this->_tpl_vars['special_offers'][$this->_sections['i']['index']]['productID']; ?>
.html<?php else: ?>index.php?productID=<?php echo $this->_tpl_vars['special_offers'][$this->_sections['i']['index']]['productID']; ?>
<?php endif; ?>"><?php echo @STRING_MOREPR; ?>
</a></td>
      </tr>
	</table>
    </td>
  
  <?php if (!(( $this->_sections['i']['index']+1 ) % @CONF_TAB_COUNT_IN_HOME)): ?>
  </tr>
  <?php else: ?>
    <td><img src="data/<?php echo @TPL; ?>
/pixel.gif" class="delim" alt=""></td>
  <?php endif; ?>

<?php endfor; endif; ?>
</table>
<?php endif; ?>