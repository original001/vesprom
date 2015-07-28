<?php /* Smarty version 2.6.22, created on 2015-07-28 23:03:32
         compiled from product_detailed.tpl.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'fu_make_url', 'product_detailed.tpl.html', 33, false),array('modifier', 'replace', 'product_detailed.tpl.html', 435, false),array('function', 'counter', 'product_detailed.tpl.html', 102, false),)), $this); ?>

<?php if ($this->_tpl_vars['product_info'] != NULL): ?>
<script type="text/javascript" src="data/<?php echo @TPL; ?>
/highslide.packed.js"></script>
<script type="text/javascript">
<!--
    hs.graphicsDir = 'data/<?php echo @TPL; ?>
/';
    hs.outlineType = 'rounded';
    hs.showCredits = false;
    hs.loadingOpacity = 1;

    hs.lang.restoreTitle = '<?php echo @STRING_HS_RESTORETITLE; ?>
';
    hs.lang.loadingText = '<?php echo @STRING_HS_LOADINGTEXT; ?>
';
    hs.lang.loadingTitle = '<?php echo @STRING_HS_LOADINGTITLE; ?>
';
    hs.lang.focusTitle = '<?php echo @STRING_HS_FOCUSTITLE; ?>
';
    hs.lang.fullExpandTitle = '<?php echo @STRING_HS_FULLEXPANDTITLE; ?>
';
//-->
</script>
<script type="text/javascript">
    hs.dimmingOpacity = 0.75;
</script>
<style type="text/css">
<?php echo '
.highslide-dimming {
        background: #333333;
        position: absolute;
}
'; ?>

</style>

<div class="bread_full">
        <?php if (@CONF_USE_DISCUSS == 1): ?><span style="float: right;"><a href="index.php?productID=<?php echo $this->_tpl_vars['product_info']['productID']; ?>
&amp;discuss=yes"><?php echo @DISCUSS_ITEM_LINK; ?>
</a> (<?php echo $this->_tpl_vars['product_reviews_count']; ?>
 <?php echo @POSTS_FOR_ITEM_STRING; ?>
)</span><?php endif; ?><a href="<?php echo @CONF_FULL_SHOP_URL; ?>
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
</a><?php endif; ?><?php endfor; endif; ?> <p> <h3>
        <?php $this->assign('preheader', "<a href='".(@ADMIN_FILE)."?productID=".($this->_tpl_vars['product_info']['productID'])."&amp;eaction=prod' title='".(@STRING_EDITPR)."' style='float: right;'>+</a>"); ?>
<?php if ($this->_tpl_vars['isadmin'] == 'yes'): ?><?php $this->assign('postheader', ($this->_tpl_vars['preheader']).($this->_tpl_vars['product_info']['name'])); ?><?php else: ?><?php $this->assign('postheader', $this->_tpl_vars['product_info']['name']); ?><?php endif; ?>
          <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "header.tpl.html", 'smarty_include_vars' => array('header' => $this->_tpl_vars['postheader'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?></h3>
        </p>
      </div>
      <div class="productfull_img">
        <?php if (@CONF_DISPLAY_FOTO == 1): ?>
        <?php if ($this->_tpl_vars['product_info']['thumbnail']): ?>
            <?php if ($this->_tpl_vars['product_info']['big_picture']): ?> <a href="data/big/<?php echo $this->_tpl_vars['product_info']['big_picture']; ?>
" class="highslide" onclick="return hs.expand(this)"><img src="data/medium/<?php echo $this->_tpl_vars['product_info']['thumbnail']; ?>
" alt="<?php echo $this->_tpl_vars['product_info']['name']; ?>
" id="<?php echo $this->_tpl_vars['product_info']['big_picture']; ?>
"></a>            <?php else: ?> <img src="data/medium/<?php echo $this->_tpl_vars['product_info']['thumbnail']; ?>
" alt="<?php echo $this->_tpl_vars['product_info']['name']; ?>
"> <?php endif; ?>
            <?php elseif ($this->_tpl_vars['product_info']['picture']): ?>
            <?php if ($this->_tpl_vars['product_info']['big_picture']): ?> <a href="data/big/<?php echo $this->_tpl_vars['product_info']['big_picture']; ?>
" class="highslide" onclick="return hs.expand(this)"><img src="data/small/<?php echo $this->_tpl_vars['product_info']['picture']; ?>
" alt="<?php echo $this->_tpl_vars['product_info']['name']; ?>
" id="<?php echo $this->_tpl_vars['product_info']['big_picture']; ?>
"></a>            <?php else: ?> <img src="data/small/<?php echo $this->_tpl_vars['product_info']['picture']; ?>
" alt="<?php echo $this->_tpl_vars['product_info']['name']; ?>
"> <?php endif; ?>
            <?php else: ?>
            <?php if (@CONF_DISPLAY_NOPHOTO == 1): ?> <img src="data/empty.gif" alt="no photo"> <?php endif; ?>
            <?php endif; ?>
            <?php if ($this->_tpl_vars['all_product_pictures']): ?>
            <?php unset($this->_sections['i']);
$this->_sections['i']['name'] = 'i';
$this->_sections['i']['loop'] = is_array($_loop=$this->_tpl_vars['all_product_pictures']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
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
            <div class="fil"></div>
            <?php if ($this->_tpl_vars['all_product_pictures'][$this->_sections['i']['index']]['enlarged']): ?> <a href="data/big/<?php echo $this->_tpl_vars['all_product_pictures'][$this->_sections['i']['index']]['enlarged']; ?>
" class="highslide" onclick="return hs.expand(this)"><img src="data/small/<?php echo $this->_tpl_vars['all_product_pictures'][$this->_sections['i']['index']]['filename']; ?>
" alt="<?php echo $this->_tpl_vars['product_info']['name']; ?>
" id="<?php echo $this->_tpl_vars['all_product_pictures'][$this->_sections['i']['index']]['enlarged']; ?>
"></a>            <?php else: ?> <img src="data/small/<?php echo $this->_tpl_vars['all_product_pictures'][$this->_sections['i']['index']]['filename']; ?>
" alt="<?php echo $this->_tpl_vars['product_info']['name']; ?>
"> <?php endif; ?>
            <?php endfor; endif; ?>
            <?php endif; ?>  <?php endif; ?> 

            <?php if (@CONF_DISPLAY_FOTO == 0): ?>
          <?php if ($this->_tpl_vars['product_info']['thumbnail']): ?>
            <?php if ($this->_tpl_vars['product_info']['big_picture']): ?> <a href="data/big/<?php echo $this->_tpl_vars['product_info']['big_picture']; ?>
" class="highslide" onclick="return hs.expand(this)"><img src="data/medium/<?php echo $this->_tpl_vars['product_info']['thumbnail']; ?>
" alt="<?php echo $this->_tpl_vars['product_info']['name']; ?>
" id="<?php echo $this->_tpl_vars['product_info']['big_picture']; ?>
"></a>            <?php else: ?> <img src="data/medium/<?php echo $this->_tpl_vars['product_info']['thumbnail']; ?>
" alt="<?php echo $this->_tpl_vars['product_info']['name']; ?>
"> <?php endif; ?>
            <?php elseif ($this->_tpl_vars['product_info']['picture']): ?>
            <?php if ($this->_tpl_vars['product_info']['big_picture']): ?> <a href="data/big/<?php echo $this->_tpl_vars['product_info']['big_picture']; ?>
" class="highslide" onclick="return hs.expand(this)"><img src="data/small/<?php echo $this->_tpl_vars['product_info']['picture']; ?>
" alt="<?php echo $this->_tpl_vars['product_info']['name']; ?>
" id="<?php echo $this->_tpl_vars['product_info']['big_picture']; ?>
"></a>            <?php else: ?> <img src="data/small/<?php echo $this->_tpl_vars['product_info']['picture']; ?>
" alt="<?php echo $this->_tpl_vars['product_info']['name']; ?>
"> <?php endif; ?>
            <?php else: ?>
            <?php if (@CONF_DISPLAY_NOPHOTO == 1): ?> <img src="data/empty.gif" alt="no photo"> <?php endif; ?>
            <?php endif; ?>
            <?php if ($this->_tpl_vars['all_product_pictures']): ?>
            <?php unset($this->_sections['i']);
$this->_sections['i']['name'] = 'i';
$this->_sections['i']['loop'] = is_array($_loop=$this->_tpl_vars['all_product_pictures']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
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
            <div class="fil"></div>
            <?php if ($this->_tpl_vars['all_product_pictures'][$this->_sections['i']['index']]['enlarged']): ?> <a href="data/big/<?php echo $this->_tpl_vars['all_product_pictures'][$this->_sections['i']['index']]['enlarged']; ?>
" class="highslide" onclick="return hs.expand(this)"><img src="data/small/<?php echo $this->_tpl_vars['all_product_pictures'][$this->_sections['i']['index']]['filename']; ?>
" alt="<?php echo $this->_tpl_vars['product_info']['name']; ?>
" id="<?php echo $this->_tpl_vars['all_product_pictures'][$this->_sections['i']['index']]['enlarged']; ?>
"></a>            <?php else: ?> <img src="data/small/<?php echo $this->_tpl_vars['all_product_pictures'][$this->_sections['i']['index']]['filename']; ?>
" alt="<?php echo $this->_tpl_vars['product_info']['name']; ?>
"> <?php endif; ?>
            <?php endfor; endif; ?>
            <?php endif; ?> 
          <?php endif; ?> 


      </div>

      <div class="productfull_table">
        <table class="table">
        <thead>
          <tr>
            <th width="250">Характеристика</th>
            <th>Описание</th>
          </tr>
        </thead>
          <tbody>
            <form action="index.php?productID=<?php echo $this->_tpl_vars['product_info']['productID']; ?>
" method=post name="MainForm">
              <?php echo smarty_function_counter(array('name' => 'select_counter','start' => 0,'skip' => 1,'print' => false,'assign' => 'select_counter_var'), $this);?>

              <?php unset($this->_sections['i']);
$this->_sections['i']['name'] = 'i';
$this->_sections['i']['loop'] = is_array($_loop=$this->_tpl_vars['product_extra']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
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
              <?php if ($this->_tpl_vars['product_extra'][$this->_sections['i']['index']]['option_type'] == 0): ?>
              <tr>
                <td width="250"> 
              <?php echo $this->_tpl_vars['product_extra'][$this->_sections['i']['index']]['name']; ?>
: </td>
                <td><?php echo $this->_tpl_vars['product_extra'][$this->_sections['i']['index']]['option_value']; ?>
</td>
              </tr>
              <?php else: ?>
              <?php echo smarty_function_counter(array('name' => 'option_show_times','start' => 0,'skip' => 1,'print' => false), $this);?>

              <?php unset($this->_sections['k']);
$this->_sections['k']['name'] = 'k';
$this->_sections['k']['loop'] = is_array($_loop=$this->_tpl_vars['product_extra'][$this->_sections['i']['index']]['option_show_times']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['k']['show'] = true;
$this->_sections['k']['max'] = $this->_sections['k']['loop'];
$this->_sections['k']['step'] = 1;
$this->_sections['k']['start'] = $this->_sections['k']['step'] > 0 ? 0 : $this->_sections['k']['loop']-1;
if ($this->_sections['k']['show']) {
    $this->_sections['k']['total'] = $this->_sections['k']['loop'];
    if ($this->_sections['k']['total'] == 0)
        $this->_sections['k']['show'] = false;
} else
    $this->_sections['k']['total'] = 0;
if ($this->_sections['k']['show']):

            for ($this->_sections['k']['index'] = $this->_sections['k']['start'], $this->_sections['k']['iteration'] = 1;
                 $this->_sections['k']['iteration'] <= $this->_sections['k']['total'];
                 $this->_sections['k']['index'] += $this->_sections['k']['step'], $this->_sections['k']['iteration']++):
$this->_sections['k']['rownum'] = $this->_sections['k']['iteration'];
$this->_sections['k']['index_prev'] = $this->_sections['k']['index'] - $this->_sections['k']['step'];
$this->_sections['k']['index_next'] = $this->_sections['k']['index'] + $this->_sections['k']['step'];
$this->_sections['k']['first']      = ($this->_sections['k']['iteration'] == 1);
$this->_sections['k']['last']       = ($this->_sections['k']['iteration'] == $this->_sections['k']['total']);
?>
              <tr>
                <td width="250"> 
              <?php echo $this->_tpl_vars['product_extra'][$this->_sections['i']['index']]['name']; ?>

              <?php if ($this->_tpl_vars['product_extra'][$this->_sections['i']['index']]['option_show_times'] > 1): ?>
              (<?php echo smarty_function_counter(array('name' => 'option_show_times'), $this);?>
):<?php else: ?>:</td>
                <td><?php endif; ?>
              <?php echo smarty_function_counter(array('name' => 'select_counter'), $this);?>

              <?php if ($this->_tpl_vars['product_extra'][$this->_sections['i']['index']]['values_to_select_count'] == 1): ?> <?php echo $this->_tpl_vars['product_extra'][$this->_sections['i']['index']]['values_to_select'][0]['option_value']; ?>
<br></td>
              </tr>
              <input type="hidden" name="option_select_<?php echo $this->_tpl_vars['select_counter_var']; ?>
" value='<?php echo $this->_tpl_vars['product_extra'][$this->_sections['i']['index']]['values_to_select'][0]['price_surplus']; ?>
:<?php echo $this->_tpl_vars['product_extra'][$this->_sections['i']['index']]['values_to_select'][0]['variantID']; ?>
'>
              <?php else: ?>
              <?php if ($this->_sections['k']['index'] == 0): ?>
              <select name='option_select_<?php echo $this->_tpl_vars['select_counter_var']; ?>
' onchange='GetCurrentCurrency();' class="WCHhider">
                
                  <?php unset($this->_sections['j']);
$this->_sections['j']['name'] = 'j';
$this->_sections['j']['loop'] = is_array($_loop=$this->_tpl_vars['product_extra'][$this->_sections['i']['index']]['values_to_select']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['j']['show'] = true;
$this->_sections['j']['max'] = $this->_sections['j']['loop'];
$this->_sections['j']['step'] = 1;
$this->_sections['j']['start'] = $this->_sections['j']['step'] > 0 ? 0 : $this->_sections['j']['loop']-1;
if ($this->_sections['j']['show']) {
    $this->_sections['j']['total'] = $this->_sections['j']['loop'];
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
                  
                <option value='<?php echo $this->_tpl_vars['product_extra'][$this->_sections['i']['index']]['values_to_select'][$this->_sections['j']['index']]['price_surplus']; ?>
:<?php echo $this->_tpl_vars['product_extra'][$this->_sections['i']['index']]['values_to_select'][$this->_sections['j']['index']]['variantID']; ?>
' <?php if ($this->_tpl_vars['product_extra'][$this->_sections['i']['index']]['values_to_select'][$this->_sections['j']['index']]['variantID'] == $this->_tpl_vars['product_extra'][$this->_sections['i']['index']]['variantID']): ?>selected<?php endif; ?>><?php echo $this->_tpl_vars['product_extra'][$this->_sections['i']['index']]['values_to_select'][$this->_sections['j']['index']]['option_value']; ?>
</option>
                
                  <?php endfor; endif; ?>
              </select>
              <br>
              <?php else: ?>
              <select name='option_select_<?php echo $this->_tpl_vars['select_counter_var']; ?>
' onchange='GetCurrentCurrency();' class="WCHhider">
                <option value='0:-1'><?php echo @NOT_DEFINED; ?>
</option>
                
                  <?php unset($this->_sections['j']);
$this->_sections['j']['name'] = 'j';
$this->_sections['j']['loop'] = is_array($_loop=$this->_tpl_vars['product_extra'][$this->_sections['i']['index']]['values_to_select']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['j']['show'] = true;
$this->_sections['j']['max'] = $this->_sections['j']['loop'];
$this->_sections['j']['step'] = 1;
$this->_sections['j']['start'] = $this->_sections['j']['step'] > 0 ? 0 : $this->_sections['j']['loop']-1;
if ($this->_sections['j']['show']) {
    $this->_sections['j']['total'] = $this->_sections['j']['loop'];
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
                  
                <option value='<?php echo $this->_tpl_vars['product_extra'][$this->_sections['i']['index']]['values_to_select'][$this->_sections['j']['index']]['price_surplus']; ?>
:<?php echo $this->_tpl_vars['product_extra'][$this->_sections['i']['index']]['values_to_select'][$this->_sections['j']['index']]['variantID']; ?>
'><?php echo $this->_tpl_vars['product_extra'][$this->_sections['i']['index']]['values_to_select'][$this->_sections['j']['index']]['option_value']; ?>
</option>
                
                  <?php endfor; endif; ?>
              </select>
              <br>
              <?php endif; ?>
              <?php endif; ?>
              <?php endfor; endif; ?>
              <?php endif; ?>
              <?php endfor; endif; ?>
              <?php if ($this->_tpl_vars['select_counter_var'] != 0): ?>
              <input type=hidden value="<?php echo(getPriceUnit()); ?>" name="priceUnit">
              <?php endif; ?>
            </form>
          </tbody>
        </table>
        <div class="price_full">
          Цена: <span><?php echo $this->_tpl_vars['product_info']['PriceWithUnit']; ?>
</span>
        </div>
      </div>
      <div class="productfull_desc">
        <h3>Описание</h3>
        <?php echo $this->_tpl_vars['product_info']['description']; ?>

      </div>
      

<div style="display:none">

<table cellspacing="0" cellpadding="0" width="100%" class="print">
  <tr>
    <td class="cbt" align="left"><?php if (@CONF_USE_DISCUSS == 1): ?><span style="float: right;"><a href="index.php?productID=<?php echo $this->_tpl_vars['product_info']['productID']; ?>
&amp;discuss=yes"><?php echo @DISCUSS_ITEM_LINK; ?>
</a> (<?php echo $this->_tpl_vars['product_reviews_count']; ?>
 <?php echo @POSTS_FOR_ITEM_STRING; ?>
)</span><?php endif; ?><a href="<?php echo @CONF_FULL_SHOP_URL; ?>
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
?><?php if ($this->_tpl_vars['product_category_path'][$this->_sections['i']['index']]['categoryID'] != 1): ?> / <a href="<?php echo fu_make_url($this->_tpl_vars['product_category_path'][$this->_sections['i']['index']]); ?>
"><?php echo $this->_tpl_vars['product_category_path'][$this->_sections['i']['index']]['name']; ?>
</a><?php endif; ?><?php endfor; endif; ?></td>
  </tr>
</table>
<table cellspacing="0" cellpadding="0" width="100%">
  <tr>
    <td class="hdbtop" valign="top" align="left"><table cellspacing="0" cellpadding="0" width="100%">
        <tr>
          <td valign="middle" align="left"><table cellspacing="0" cellpadding="0" style="margin-right: 8px;">
              <tr>
                <td class="price" id="optionPrice"><?php if ($this->_tpl_vars['currencies_count'] != 0): ?><?php if ($this->_tpl_vars['product_info']['Price'] <= 0): ?><?php echo @STRING_NOPRODUCT_IN; ?>
<?php else: ?><?php echo $this->_tpl_vars['product_info']['PriceWithUnit']; ?>
<?php endif; ?><?php endif; ?></td>
              </tr>
              <?php if ($this->_tpl_vars['currencies_count'] != 0): ?><?php if ($this->_tpl_vars['product_info']['list_price'] > 0 && $this->_tpl_vars['product_info']['list_price'] > $this->_tpl_vars['product_info']['Price'] && $this->_tpl_vars['product_info']['Price'] > 0): ?>
              <tr>
                <td class="price market-price"><?php echo $this->_tpl_vars['product_info']['list_priceWithUnit']; ?>
</td>
              </tr>
              <?php endif; ?><?php endif; ?>
            </table></td>
          <td align="right" width="100%" valign="middle"><table cellspacing="0" cellpadding="0" align="right">
              <tr>
                <td style="border-left: 1px solid #CCCCCC; padding-left: 8px;" align="left" valign="middle"><form action="index.php?productID=<?php echo $this->_tpl_vars['product_info']['productID']; ?>
" method=post name="HiddenFieldsForm" id="DetailCartForm">
                    <?php if (@CONF_OPEN_SHOPPING_CART_IN_NEW_WINDOW == 1): ?>
                    <?php if (@CONF_SHOW_ADD2CART == 1 && ( @CONF_CHECKSTOCK == 0 || $this->_tpl_vars['product_info']['in_stock'] > 0 )): ?>
                    <table cellspacing="0" cellpadding="0" class="print">
                      <tr>
                        <td><a href="#" onclick="open_window('index.php?do=cart&amp;addproduct=<?php echo $this->_tpl_vars['product_info']['productID']; ?>
<?php echo smarty_function_counter(array('name' => 'select_counter_hidden','start' => 0,'skip' => 1,'print' => false), $this);?>
<?php echo smarty_function_counter(array('name' => 'select_counter_hidden_extra','start' => 0,'skip' => 1,'print' => false), $this);?>
<?php unset($this->_sections['i']);
$this->_sections['i']['name'] = 'i';
$this->_sections['i']['loop'] = is_array($_loop=$this->_tpl_vars['product_extra']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
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
?><?php if ($this->_tpl_vars['product_extra'][$this->_sections['i']['index']]['option_type'] == 1): ?><?php unset($this->_sections['s']);
$this->_sections['s']['name'] = 's';
$this->_sections['s']['loop'] = is_array($_loop=$this->_tpl_vars['product_extra'][$this->_sections['i']['index']]['option_show_times']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['s']['show'] = true;
$this->_sections['s']['max'] = $this->_sections['s']['loop'];
$this->_sections['s']['step'] = 1;
$this->_sections['s']['start'] = $this->_sections['s']['step'] > 0 ? 0 : $this->_sections['s']['loop']-1;
if ($this->_sections['s']['show']) {
    $this->_sections['s']['total'] = $this->_sections['s']['loop'];
    if ($this->_sections['s']['total'] == 0)
        $this->_sections['s']['show'] = false;
} else
    $this->_sections['s']['total'] = 0;
if ($this->_sections['s']['show']):

            for ($this->_sections['s']['index'] = $this->_sections['s']['start'], $this->_sections['s']['iteration'] = 1;
                 $this->_sections['s']['iteration'] <= $this->_sections['s']['total'];
                 $this->_sections['s']['index'] += $this->_sections['s']['step'], $this->_sections['s']['iteration']++):
$this->_sections['s']['rownum'] = $this->_sections['s']['iteration'];
$this->_sections['s']['index_prev'] = $this->_sections['s']['index'] - $this->_sections['s']['step'];
$this->_sections['s']['index_next'] = $this->_sections['s']['index'] + $this->_sections['s']['step'];
$this->_sections['s']['first']      = ($this->_sections['s']['iteration'] == 1);
$this->_sections['s']['last']       = ($this->_sections['s']['iteration'] == $this->_sections['s']['total']);
?>&amp;option_select_hidden_<?php echo smarty_function_counter(array('name' => 'select_counter_hidden'), $this);?>
='+document.HiddenFieldsForm.option_select_hidden_<?php echo smarty_function_counter(array('name' => 'select_counter_hidden_extra'), $this);?>
.value+'<?php endfor; endif; ?><?php endif; ?><?php endfor; endif; ?>&amp;multyaddcount='+document.HiddenFieldsForm.multyaddcount.value+'',550,300);"><img src="data/<?php echo @TPL; ?>
/crt.gif" alt=""></a></td>
                        <td style="padding-left: 4px;"><input type=<?php if (@CONF_MULTYCART == 0): ?>hidden<?php else: ?>text<?php endif; ?> value="1" name="multyaddcount" <?php if (@CONF_MULTYCART == 1): ?>size="2" style="margin-right: 4px; width: 16px;"<?php endif; ?>>
                          <a href="#" onclick="open_window('index.php?do=cart&amp;addproduct=<?php echo $this->_tpl_vars['product_info']['productID']; ?>
<?php echo smarty_function_counter(array('name' => 'select_counter_hidden','start' => 0,'skip' => 1,'print' => false), $this);?>
<?php echo smarty_function_counter(array('name' => 'select_counter_hidden_extra','start' => 0,'skip' => 1,'print' => false), $this);?>
<?php unset($this->_sections['i']);
$this->_sections['i']['name'] = 'i';
$this->_sections['i']['loop'] = is_array($_loop=$this->_tpl_vars['product_extra']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
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
?><?php if ($this->_tpl_vars['product_extra'][$this->_sections['i']['index']]['option_type'] == 1): ?><?php unset($this->_sections['s']);
$this->_sections['s']['name'] = 's';
$this->_sections['s']['loop'] = is_array($_loop=$this->_tpl_vars['product_extra'][$this->_sections['i']['index']]['option_show_times']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['s']['show'] = true;
$this->_sections['s']['max'] = $this->_sections['s']['loop'];
$this->_sections['s']['step'] = 1;
$this->_sections['s']['start'] = $this->_sections['s']['step'] > 0 ? 0 : $this->_sections['s']['loop']-1;
if ($this->_sections['s']['show']) {
    $this->_sections['s']['total'] = $this->_sections['s']['loop'];
    if ($this->_sections['s']['total'] == 0)
        $this->_sections['s']['show'] = false;
} else
    $this->_sections['s']['total'] = 0;
if ($this->_sections['s']['show']):

            for ($this->_sections['s']['index'] = $this->_sections['s']['start'], $this->_sections['s']['iteration'] = 1;
                 $this->_sections['s']['iteration'] <= $this->_sections['s']['total'];
                 $this->_sections['s']['index'] += $this->_sections['s']['step'], $this->_sections['s']['iteration']++):
$this->_sections['s']['rownum'] = $this->_sections['s']['iteration'];
$this->_sections['s']['index_prev'] = $this->_sections['s']['index'] - $this->_sections['s']['step'];
$this->_sections['s']['index_next'] = $this->_sections['s']['index'] + $this->_sections['s']['step'];
$this->_sections['s']['first']      = ($this->_sections['s']['iteration'] == 1);
$this->_sections['s']['last']       = ($this->_sections['s']['iteration'] == $this->_sections['s']['total']);
?>&amp;option_select_hidden_<?php echo smarty_function_counter(array('name' => 'select_counter_hidden'), $this);?>
='+document.HiddenFieldsForm.option_select_hidden_<?php echo smarty_function_counter(array('name' => 'select_counter_hidden_extra'), $this);?>
.value+'<?php endfor; endif; ?><?php endif; ?><?php endfor; endif; ?>&amp;multyaddcount='+document.HiddenFieldsForm.multyaddcount.value+'',550,300);"><?php echo @ADD_TO_CART_STRING; ?>
</a></td>
                      </tr>
                    </table>
                    <?php endif; ?>
                    <?php else: ?>
                    <?php if (@CONF_CART_METHOD == 2): ?>
                    <?php if (@CONF_SHOW_ADD2CART == 1 && ( @CONF_CHECKSTOCK == 0 || $this->_tpl_vars['product_info']['in_stock'] > 0 )): ?>
                    <table cellspacing="0" cellpadding="0" class="print">
                      <tr>
                        <td><a href="#" onclick="doLoad('do=cart&amp;addproduct=<?php echo $this->_tpl_vars['product_info']['productID']; ?>
<?php echo smarty_function_counter(array('name' => 'select_counter_hidden','start' => 0,'skip' => 1,'print' => false), $this);?>
<?php echo smarty_function_counter(array('name' => 'select_counter_hidden_extra','start' => 0,'skip' => 1,'print' => false), $this);?>
<?php unset($this->_sections['i']);
$this->_sections['i']['name'] = 'i';
$this->_sections['i']['loop'] = is_array($_loop=$this->_tpl_vars['product_extra']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
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
?><?php if ($this->_tpl_vars['product_extra'][$this->_sections['i']['index']]['option_type'] == 1): ?><?php unset($this->_sections['s']);
$this->_sections['s']['name'] = 's';
$this->_sections['s']['loop'] = is_array($_loop=$this->_tpl_vars['product_extra'][$this->_sections['i']['index']]['option_show_times']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['s']['show'] = true;
$this->_sections['s']['max'] = $this->_sections['s']['loop'];
$this->_sections['s']['step'] = 1;
$this->_sections['s']['start'] = $this->_sections['s']['step'] > 0 ? 0 : $this->_sections['s']['loop']-1;
if ($this->_sections['s']['show']) {
    $this->_sections['s']['total'] = $this->_sections['s']['loop'];
    if ($this->_sections['s']['total'] == 0)
        $this->_sections['s']['show'] = false;
} else
    $this->_sections['s']['total'] = 0;
if ($this->_sections['s']['show']):

            for ($this->_sections['s']['index'] = $this->_sections['s']['start'], $this->_sections['s']['iteration'] = 1;
                 $this->_sections['s']['iteration'] <= $this->_sections['s']['total'];
                 $this->_sections['s']['index'] += $this->_sections['s']['step'], $this->_sections['s']['iteration']++):
$this->_sections['s']['rownum'] = $this->_sections['s']['iteration'];
$this->_sections['s']['index_prev'] = $this->_sections['s']['index'] - $this->_sections['s']['step'];
$this->_sections['s']['index_next'] = $this->_sections['s']['index'] + $this->_sections['s']['step'];
$this->_sections['s']['first']      = ($this->_sections['s']['iteration'] == 1);
$this->_sections['s']['last']       = ($this->_sections['s']['iteration'] == $this->_sections['s']['total']);
?>&amp;option_select_hidden_<?php echo smarty_function_counter(array('name' => 'select_counter_hidden'), $this);?>
='+document.HiddenFieldsForm.option_select_hidden_<?php echo smarty_function_counter(array('name' => 'select_counter_hidden_extra'), $this);?>
.value+'<?php endfor; endif; ?><?php endif; ?><?php endfor; endif; ?>&amp;xcart=yes&amp;multyaddcount='+document.HiddenFieldsForm.multyaddcount.value+''); return false"><img src="data/<?php echo @TPL; ?>
/crt.gif" alt=""></a></td>
                        <td style="padding-left: 4px;"><input type=<?php if (@CONF_MULTYCART == 0): ?>hidden<?php else: ?>text<?php endif; ?> value="1" name="multyaddcount" <?php if (@CONF_MULTYCART == 1): ?>size="2" style="margin-right: 4px; width: 16px;"<?php endif; ?>>
                          <a href="#" onclick="doLoad('do=cart&amp;addproduct=<?php echo $this->_tpl_vars['product_info']['productID']; ?>
<?php echo smarty_function_counter(array('name' => 'select_counter_hidden','start' => 0,'skip' => 1,'print' => false), $this);?>
<?php echo smarty_function_counter(array('name' => 'select_counter_hidden_extra','start' => 0,'skip' => 1,'print' => false), $this);?>
<?php unset($this->_sections['i']);
$this->_sections['i']['name'] = 'i';
$this->_sections['i']['loop'] = is_array($_loop=$this->_tpl_vars['product_extra']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
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
?><?php if ($this->_tpl_vars['product_extra'][$this->_sections['i']['index']]['option_type'] == 1): ?><?php unset($this->_sections['s']);
$this->_sections['s']['name'] = 's';
$this->_sections['s']['loop'] = is_array($_loop=$this->_tpl_vars['product_extra'][$this->_sections['i']['index']]['option_show_times']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['s']['show'] = true;
$this->_sections['s']['max'] = $this->_sections['s']['loop'];
$this->_sections['s']['step'] = 1;
$this->_sections['s']['start'] = $this->_sections['s']['step'] > 0 ? 0 : $this->_sections['s']['loop']-1;
if ($this->_sections['s']['show']) {
    $this->_sections['s']['total'] = $this->_sections['s']['loop'];
    if ($this->_sections['s']['total'] == 0)
        $this->_sections['s']['show'] = false;
} else
    $this->_sections['s']['total'] = 0;
if ($this->_sections['s']['show']):

            for ($this->_sections['s']['index'] = $this->_sections['s']['start'], $this->_sections['s']['iteration'] = 1;
                 $this->_sections['s']['iteration'] <= $this->_sections['s']['total'];
                 $this->_sections['s']['index'] += $this->_sections['s']['step'], $this->_sections['s']['iteration']++):
$this->_sections['s']['rownum'] = $this->_sections['s']['iteration'];
$this->_sections['s']['index_prev'] = $this->_sections['s']['index'] - $this->_sections['s']['step'];
$this->_sections['s']['index_next'] = $this->_sections['s']['index'] + $this->_sections['s']['step'];
$this->_sections['s']['first']      = ($this->_sections['s']['iteration'] == 1);
$this->_sections['s']['last']       = ($this->_sections['s']['iteration'] == $this->_sections['s']['total']);
?>&amp;option_select_hidden_<?php echo smarty_function_counter(array('name' => 'select_counter_hidden'), $this);?>
='+document.HiddenFieldsForm.option_select_hidden_<?php echo smarty_function_counter(array('name' => 'select_counter_hidden_extra'), $this);?>
.value+'<?php endfor; endif; ?><?php endif; ?><?php endfor; endif; ?>&amp;xcart=yes&amp;multyaddcount='+document.HiddenFieldsForm.multyaddcount.value+''); return false"><?php echo @ADD_TO_CART_STRING; ?>
</a></td>
                      </tr>
                    </table>
                    <?php endif; ?>
                    <?php else: ?>
                    <?php if (@CONF_SHOW_ADD2CART == 1 && ( @CONF_CHECKSTOCK == 0 || $this->_tpl_vars['product_info']['in_stock'] > 0 )): ?>
                    <table cellspacing="0" cellpadding="0" class="print">
                      <tr>
                        <td><input type="hidden" name="cart_x" value="<?php echo $this->_tpl_vars['product_info']['productID']; ?>
">
                          <a href="#" onclick="document.getElementById('DetailCartForm').submit(); return false"><img src="data/<?php echo @TPL; ?>
/crt.gif" alt=""></a></td>
                        <td style="padding-left: 4px;"><input type=<?php if (@CONF_MULTYCART == 0): ?>hidden<?php else: ?>text<?php endif; ?> value="1" name="multyaddcount" <?php if (@CONF_MULTYCART == 1): ?>size="2" style="margin-right: 4px; width: 16px;"<?php endif; ?>>
                          <a href="#" onclick="document.getElementById('DetailCartForm').submit(); return false"><?php echo @ADD_TO_CART_STRING; ?>
</a></td>
                      </tr>
                    </table>
                    <?php endif; ?>
                    <?php endif; ?>
                    <?php endif; ?>
                    <?php echo smarty_function_counter(array('name' => 'select_counter_hidden','start' => 0,'skip' => 1,'print' => false), $this);?>

                    <?php unset($this->_sections['i']);
$this->_sections['i']['name'] = 'i';
$this->_sections['i']['loop'] = is_array($_loop=$this->_tpl_vars['product_extra']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
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
                    <?php if ($this->_tpl_vars['product_extra'][$this->_sections['i']['index']]['option_type'] == 1): ?>
                    <?php unset($this->_sections['s']);
$this->_sections['s']['name'] = 's';
$this->_sections['s']['loop'] = is_array($_loop=$this->_tpl_vars['product_extra'][$this->_sections['i']['index']]['option_show_times']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['s']['show'] = true;
$this->_sections['s']['max'] = $this->_sections['s']['loop'];
$this->_sections['s']['step'] = 1;
$this->_sections['s']['start'] = $this->_sections['s']['step'] > 0 ? 0 : $this->_sections['s']['loop']-1;
if ($this->_sections['s']['show']) {
    $this->_sections['s']['total'] = $this->_sections['s']['loop'];
    if ($this->_sections['s']['total'] == 0)
        $this->_sections['s']['show'] = false;
} else
    $this->_sections['s']['total'] = 0;
if ($this->_sections['s']['show']):

            for ($this->_sections['s']['index'] = $this->_sections['s']['start'], $this->_sections['s']['iteration'] = 1;
                 $this->_sections['s']['iteration'] <= $this->_sections['s']['total'];
                 $this->_sections['s']['index'] += $this->_sections['s']['step'], $this->_sections['s']['iteration']++):
$this->_sections['s']['rownum'] = $this->_sections['s']['iteration'];
$this->_sections['s']['index_prev'] = $this->_sections['s']['index'] - $this->_sections['s']['step'];
$this->_sections['s']['index_next'] = $this->_sections['s']['index'] + $this->_sections['s']['step'];
$this->_sections['s']['first']      = ($this->_sections['s']['iteration'] == 1);
$this->_sections['s']['last']       = ($this->_sections['s']['iteration'] == $this->_sections['s']['total']);
?>
                    <input type=hidden name='option_select_hidden_<?php echo smarty_function_counter(array('name' => 'select_counter_hidden'), $this);?>
' value='1'>
                    <?php endfor; endif; ?>
                    <?php endif; ?>
                    <?php endfor; endif; ?>
                  </form>
                  <?php if ($this->_tpl_vars['product_info']['allow_products_comparison']): ?>
                  <div class="fil1"></div>
                  <table cellspacing="0" cellpadding="0" class="print">
                    <tr>
                      <td><a href="#" onclick="doLoadcpr('do=compare&amp;cpradd=<?php echo $this->_tpl_vars['product_info']['productID']; ?>
'); return false"><img src="data/<?php echo @TPL; ?>
/explorer.gif" alt=""></a></td>
                      <td style="padding-left: 4px;"><a href="#" onclick="doLoadcpr('do=compare&amp;cpradd=<?php echo $this->_tpl_vars['product_info']['productID']; ?>
'); return false"><?php echo @ADD_TO_CFOLDER; ?>
</a></td>
                    </tr>
                  </table>
                  <?php endif; ?> </td>
              </tr>
            </table></td>
        </tr>
      </table></td>
  </tr>
</table>
<table cellspacing="0" cellpadding="0" width="100%">
  <tr>
    <td width="100%" style="background-color: #CCCCCC; height: 1px;"></td>
  </tr>
</table>
<table cellspacing="0" cellpadding="0" width="100%">
  <tr>
    <td class="hdbtop" valign="top" align="left"><table cellspacing="0" cellpadding="0" width="100%">
        <tr> <?php if (@CONF_DISPLAY_FOTO == 1): ?>
          <?php endif; ?>
          <td width="100%" valign="top" align="left"> <?php if ($this->_tpl_vars['product_info']['customer_votes'] > 0 && @CONF_USE_RATING == 1): ?>
            <table cellspacing="0" cellpadding="0" width="100%" class="print">
              <tr>
                <td valign="middle" align="left"><table cellspacing="0" cellpadding="0">
                    <tr> <?php unset($this->_sections['i']);
$this->_sections['i']['name'] = 'i';
$this->_sections['i']['loop'] = is_array($_loop=5) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
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
                      <td valign="middle" align="left"> <?php if ($this->_sections['i']['index'] < $this->_tpl_vars['product_info']['customers_rating']): ?>
                                                                <img src="data/<?php echo @TPL; ?>
/redstar_big.gif" alt=""> <?php else: ?> <img src="data/<?php echo @TPL; ?>
/blackstar_big.gif" alt=""> <?php endif; ?> </td>
                      <?php endfor; endif; ?> </tr>
                  </table></td>
              </tr>
            </table>
            <div class="fil1"></div>
            <?php endif; ?>
            <?php if (@CONF_USE_RATING == 1): ?>
            <?php echo '
            <script type="text/javascript">
<!--
function votescript(val) {
document.getElementById(\'markvalue\').value=val;
document.getElementById(\'VotingForm\').submit();
}
//-->
</script>
            '; ?>

            <form name="VotingForm" action='index.php' method="GET" id="VotingForm" class="print">
              <table cellspacing="0" cellpadding="0">
                <tr>
                  <td valign="middle" align="left"><?php echo @STRING_PR_DOIT; ?>
:&nbsp;</td>
                  <td valign="middle" align="left"><ul class="unit-rating" style="width:100px;">
                      <li class="current-rating" style="width:100px;">&nbsp;</li>
                      <li><a href="#" onclick="votescript(1); return false;" title="<?php echo @STRING_PR_1; ?>
" class="r1-unit rater"><?php echo @STRING_PR_1; ?>
</a></li>
                      <li><a href="#" onclick="votescript(2); return false;" title="<?php echo @STRING_PR_2; ?>
" class="r2-unit rater"><?php echo @STRING_PR_2; ?>
</a></li>
                      <li><a href="#" onclick="votescript(3); return false;" title="<?php echo @STRING_PR_3; ?>
" class="r3-unit rater"><?php echo @STRING_PR_3; ?>
</a></li>
                      <li><a href="#" onclick="votescript(4); return false;" title="<?php echo @STRING_PR_4; ?>
" class="r4-unit rater"><?php echo @STRING_PR_4; ?>
</a></li>
                      <li><a href="#" onclick="votescript(5); return false;" title="<?php echo @STRING_PR_5; ?>
" class="r5-unit rater"><?php echo @STRING_PR_5; ?>
</a></li>
                    </ul></td>
                </tr>
              </table>
              <input type="hidden" name="productID" value="<?php echo $this->_tpl_vars['product_info']['productID']; ?>
">
              <input type="hidden" name="vote" value="yes">
              <input type="hidden" name="mark" value="" id="markvalue">
            </form>
            <div class="fil"></div>
            <?php endif; ?>
            <?php if ($this->_tpl_vars['product_info']['eproduct_filename'] != ""): ?><?php echo @PRODUCT_IS_DOWNLOADABLE; ?>
 (<?php echo $this->_tpl_vars['product_info']['eproduct_filesize']; ?>
)
            <div class="fil"></div>
            <?php endif; ?>
            <?php $this->assign('otstup', 0); ?>
            <?php if (@CONF_CHECKSTOCK == '1'): ?><?php echo @IN_STOCK; ?>
: <b><?php if ($this->_tpl_vars['product_info']['in_stock'] > 0): ?><span class="oki"><?php if (@CONF_EXACT_PRODUCT_BALANCE == '1'): ?><?php echo $this->_tpl_vars['product_info']['in_stock']; ?>
<?php else: ?><?php echo @ANSWER_YES; ?>
<?php endif; ?></span><?php else: ?><span class="error"><?php if ($this->_tpl_vars['product_info']['in_stock'] < 0): ?><?php echo @STOCK_TRAIN_GO; ?>
<?php else: ?><?php echo @ANSWER_NO; ?>
<?php endif; ?></span><?php endif; ?></b><br>
            <?php $this->assign('otstup', 1); ?><?php endif; ?>
            <?php if ($this->_tpl_vars['currencies_count'] != 0): ?>
            <?php if ($this->_tpl_vars['product_info']['shipping_freightUC']): ?><?php echo @ADMIN_SHIPPING_FREIGHT; ?>
: <b><?php echo $this->_tpl_vars['product_info']['shipping_freightUC']; ?>
</b><br>
            <?php $this->assign('otstup', 1); ?><?php endif; ?>
            <?php endif; ?>
            <?php if ($this->_tpl_vars['product_info']['min_order_amount'] > 1): ?>
            <?php echo @STRING_MIN_ORDER_AMOUNT; ?>
: <b><?php echo $this->_tpl_vars['product_info']['min_order_amount']; ?>
<?php echo @STRING_ITEM; ?>
</b><br>
            <?php $this->assign('otstup', 1); ?>
            <?php endif; ?>
            <?php if (@CONF_DISPLAY_PRCODE == 1): ?>
            <?php if ($this->_tpl_vars['product_info']['product_code']): ?>
            <?php echo @STRING_PRODUCT_CODE; ?>
: <b><?php echo $this->_tpl_vars['product_info']['product_code']; ?>
</b><br>
            <?php $this->assign('otstup', 1); ?>
            <?php endif; ?>
            <?php endif; ?>
            <?php if ($this->_tpl_vars['otstup'] == 1): ?>
            <div class="fil"></div>
            <?php endif; ?>
            
            <?php if ($this->_tpl_vars['product_extra_count'] > 0): ?>
            <div class="fil"></div>
            <?php endif; ?>
            <?php if ($this->_tpl_vars['select_counter_var'] != 0): ?>
            <?php echo '
            <script type="text/javascript">
function GetCurrentCurrency()
{
'; ?>

_selectionCount=<?php echo $this->_tpl_vars['select_counter_var']; ?>
;
 _sum = <?php echo $this->_tpl_vars['product_info']['PriceWithOutUnit']; ?>
;
<?php echo smarty_function_counter(array('name' => 'select_counter2','start' => 1,'skip' => 1,'print' => false,'assign' => 'select_counter_var2'), $this);?>

<?php unset($this->_sections['i']);
$this->_sections['i']['name'] = 'i';
$this->_sections['i']['loop'] = is_array($_loop=$this->_tpl_vars['product_extra']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
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
<?php unset($this->_sections['k']);
$this->_sections['k']['name'] = 'k';
$this->_sections['k']['loop'] = is_array($_loop=$this->_tpl_vars['product_extra'][$this->_sections['i']['index']]['option_show_times']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['k']['show'] = true;
$this->_sections['k']['max'] = $this->_sections['k']['loop'];
$this->_sections['k']['step'] = 1;
$this->_sections['k']['start'] = $this->_sections['k']['step'] > 0 ? 0 : $this->_sections['k']['loop']-1;
if ($this->_sections['k']['show']) {
    $this->_sections['k']['total'] = $this->_sections['k']['loop'];
    if ($this->_sections['k']['total'] == 0)
        $this->_sections['k']['show'] = false;
} else
    $this->_sections['k']['total'] = 0;
if ($this->_sections['k']['show']):

            for ($this->_sections['k']['index'] = $this->_sections['k']['start'], $this->_sections['k']['iteration'] = 1;
                 $this->_sections['k']['iteration'] <= $this->_sections['k']['total'];
                 $this->_sections['k']['index'] += $this->_sections['k']['step'], $this->_sections['k']['iteration']++):
$this->_sections['k']['rownum'] = $this->_sections['k']['iteration'];
$this->_sections['k']['index_prev'] = $this->_sections['k']['index'] - $this->_sections['k']['step'];
$this->_sections['k']['index_next'] = $this->_sections['k']['index'] + $this->_sections['k']['step'];
$this->_sections['k']['first']      = ($this->_sections['k']['iteration'] == 1);
$this->_sections['k']['last']       = ($this->_sections['k']['iteration'] == $this->_sections['k']['total']);
?>
 _value =
document.MainForm.option_select_<?php echo $this->_tpl_vars['select_counter_var2']; ?>
.value;
price_surplus = ( _value.split(":") )[0];
 _sum += new Number( price_surplus );
variantID = ( _value.split(":") )[1];
document.HiddenFieldsForm.option_select_hidden_<?php echo $this->_tpl_vars['select_counter_var2']; ?>
.value = variantID;
<?php echo smarty_function_counter(array('name' => 'select_counter2'), $this);?>

<?php endfor; endif; ?>
<?php endfor; endif; ?>
 _sum = Math.round(_sum*100)/100;
_sumStr = new String(_sum);
 _commaIndex = _sumStr.indexOf(".");
if ( _commaIndex == -1 )
 _sumStr = _sum;
 else
 _sumStr = _sumStr.substr(0, _commaIndex + 3);
<?php 
echo("locationPriceUnit=".getLocationPriceUnit().";\n");
echo("priceUnit='".getPriceUnit()."';\n");
 ?>
 _sumStr = _formatPrice( _sumStr, <?php echo $this->_tpl_vars['currency_roundval']; ?>
);
if ( locationPriceUnit )
document.getElementById('optionPrice').innerHTML = _sumStr + document.MainForm.priceUnit.value;
else
document.getElementById('optionPrice').innerHTML = document.MainForm.priceUnit.value + _sumStr;
<?php echo '
}
'; ?>

GetCurrentCurrency();
</script>
            <?php endif; ?>
            <?php if ($this->_tpl_vars['product_info']['description']): ?><?php echo $this->_tpl_vars['product_info']['description']; ?>
<?php elseif ($this->_tpl_vars['product_info']['brief_description']): ?><?php echo $this->_tpl_vars['product_info']['brief_description']; ?>
<?php endif; ?> </td>
          
           </tr>
      </table></td>
  </tr>
</table>
<table cellspacing="0" cellpadding="0" width="100%">
  <tr>
    <td class="hdbot">&nbsp;</td>
  </tr>
</table>
<table cellspacing="0" cellpadding="0" width="100%" class="print">
  <tr>
    <td align="left" valign="top">
	  <?php if ($this->_tpl_vars['product_related_number'] > 0): ?>
      <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "header.tpl.html", 'smarty_include_vars' => array('header' => @STRING_RELATED_ITEMS)));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
      <table cellspacing="0" cellpadding="0" width="100%">
        <tr>
          <td class="hdbtop" align="left">
            <?php unset($this->_sections['i']);
$this->_sections['i']['name'] = 'i';
$this->_sections['i']['loop'] = is_array($_loop=$this->_tpl_vars['product_related']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
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
            <?php if ($this->_sections['i']['index'] != 0): ?>
            <div class="fil1"></div>
            <?php endif; ?>
            <table cellspacing="0" cellpadding="0">
              <tr>
                <td align="left"><a href="<?php echo fu_make_url($this->_tpl_vars['product_related'][$this->_sections['i']['index']]); ?>
"><?php echo $this->_tpl_vars['product_related'][$this->_sections['i']['index']]['name']; ?>
</a></td>
                <td align="left" class="price">&nbsp;&nbsp;&nbsp;<?php echo $this->_tpl_vars['product_related'][$this->_sections['i']['index']]['Price']; ?>
</td>
              </tr>
            </table>
            <?php endfor; endif; ?> </td>
        </tr>
      </table>
      <table cellspacing="0" cellpadding="0" width="100%">
        <tr>
          <td class="hdbot">&nbsp;</td>
        </tr>
      </table>
      <?php endif; ?>
      
      <?php if (@CONF_PRODUCT_MAIL == 1): ?> <a name="inquiry"></a> <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "header.tpl.html", 'smarty_include_vars' => array('header' => @STRING_FEEDBACK_PRODUCT_HEADER)));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
      <table cellspacing="0" cellpadding="0" width="100%">
        <tr>
          <td class="cbt" align="left"><?php echo @STRING_FEEDBACK_PRODUCT_DESCRIPTION; ?>
</td>
        </tr>
      </table>
      <?php if ($this->_tpl_vars['sent'] == NULL): ?>
      <table cellspacing="0" cellpadding="0" width="100%">
        <tr>
          <td class="hdbtop" align="left" valign="top"> <?php if ($this->_tpl_vars['error'] != NULL): ?>
            <table cellspacing="0" cellpadding="0" width="100%">
              <tr>
                <td class="error cattop" align="center"><?php if ($this->_tpl_vars['error'] == 7): ?><?php echo @ERR_WRONG_CCODE; ?>
<?php else: ?><?php echo @FEEDBACK_ERROR_FILL_IN_FORM; ?>
<?php endif; ?></td>
              </tr>
            </table>
            <?php endif; ?>
            <form name="form1post" id="form1post" method="post" action="index.php#inquiry">
              <table cellspacing="0" cellpadding="0" align="left">
                <tr>
                  <td align="left"><?php echo @FEEDBACK_CUSTOMER_NAME; ?>
</td>
                </tr>
                <tr>
                  <td style="height: 2px;"></td>
                </tr>
                <tr>
                  <td align="left"><input name="customer_name" type="text" style="width: 220px;" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['customer_name'])) ? $this->_run_mod_handler('replace', true, $_tmp, "\"", "&quot;") : smarty_modifier_replace($_tmp, "\"", "&quot;")); ?>
"></td>
                </tr>
                <tr>
                  <td style="height: 6px;"></td>
                </tr>
                <tr>
                  <td align="left"><?php echo @CUSTOMER_EMAIL; ?>
</td>
                </tr>
                <tr>
                  <td style="height: 2px;"></td>
                </tr>
                <tr>
                  <td align="left"><input name="customer_email" type="text" style="width: 220px;" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['customer_email'])) ? $this->_run_mod_handler('replace', true, $_tmp, "\"", "&quot;") : smarty_modifier_replace($_tmp, "\"", "&quot;")); ?>
"></td>
                </tr>
                <tr>
                  <td style="height: 6px;"></td>
                </tr>
                <tr>
                  <td align="left"><?php echo @STRING_FEEDBACK_PRODUCT_INQUIRY_EXPLANATION; ?>
</td>
                </tr>
                <tr>
                  <td style="height: 2px;"></td>
                </tr>
                <tr>
                  <td align="left"><input name="message_subject" type="hidden" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['product_info']['name'])) ? $this->_run_mod_handler('replace', true, $_tmp, "\"", "&quot;") : smarty_modifier_replace($_tmp, "\"", "&quot;")); ?>
">
                    <textarea name="message_text" style="width: 360px; height: 100px;"><?php echo ((is_array($_tmp=$this->_tpl_vars['message_text'])) ? $this->_run_mod_handler('replace', true, $_tmp, "<", "&lt;") : smarty_modifier_replace($_tmp, "<", "&lt;")); ?>
</textarea>
                    <input type="hidden" name="request_information" value="yes">
                    <input type="hidden" name="productID" value="<?php echo $this->_tpl_vars['product_info']['productID']; ?>
"></td>
                </tr>
                <?php if (@CONF_ENABLE_CONFIRMATION_CODE == 1): ?>
                <tr>
                  <td style="height: 6px;"></td>
                </tr>
                <tr>
                  <td align="left"><img src="index.php?do=captcha&amp;<?php echo session_name(); ?>=<?php echo session_id(); ?>" alt="code"></td>
                </tr>
                <tr>
                  <td style="height: 2px;"></td>
                </tr>
                <tr>
                  <td align="left"><input name="fConfirmationCode" value="<?php echo @STR_ENTER_CCODE; ?>
" type="text" style="width: 220px; color: #aaaaaa;" onfocus="if(this.value=='<?php echo @STR_ENTER_CCODE; ?>
')
                        <?php echo '
                        {this.style.color=\'#000000\';this.value=\'\';}
                        '; ?>
" onblur="if(this.value=='')
                        <?php echo '{'; ?>
this.style.color='#aaaaaa';this.value='<?php echo @STR_ENTER_CCODE; ?>
'<?php echo '}'; ?>
"></td>
                </tr>
                <?php endif; ?>
              </table>
            </form></td>
        </tr>
      </table>
      <div class="fil"></div>
      <table cellspacing="0" cellpadding="0" width="100%">
        <tr>
          <td class="hdbot"><a href="#" onclick="document.getElementById('form1post').submit(); return false"><?php echo @OK_BUTTON3; ?>
</a></td>
        </tr>
      </table>
      <?php else: ?>
      <table cellspacing="0" cellpadding="0" width="100%">
        <tr>
          <td class="oki cattop" align="center"><?php echo @FEEDBACK_SENT_SUCCESSFULLY; ?>
</td>
        </tr>
      </table>
      <table cellspacing="0" cellpadding="0" width="100%">
        <tr>
          <td class="hdbot">&nbsp;</td>
        </tr>
      </table>
      <?php endif; ?>
      <?php endif; ?>
      
      <?php if ($this->_tpl_vars['productslinkscat']): ?>
      <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "header.tpl.html", 'smarty_include_vars' => array('header' => @STRING_CAT_USE_AUX)));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
      <table cellspacing="0" cellpadding="0" width="100%">
        <tr>
          <td class="hdbtop" align="left" valign="top">
            <?php unset($this->_sections['iprod']);
$this->_sections['iprod']['name'] = 'iprod';
$this->_sections['iprod']['loop'] = is_array($_loop=$this->_tpl_vars['productslinkscat']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['iprod']['show'] = true;
$this->_sections['iprod']['max'] = $this->_sections['iprod']['loop'];
$this->_sections['iprod']['step'] = 1;
$this->_sections['iprod']['start'] = $this->_sections['iprod']['step'] > 0 ? 0 : $this->_sections['iprod']['loop']-1;
if ($this->_sections['iprod']['show']) {
    $this->_sections['iprod']['total'] = $this->_sections['iprod']['loop'];
    if ($this->_sections['iprod']['total'] == 0)
        $this->_sections['iprod']['show'] = false;
} else
    $this->_sections['iprod']['total'] = 0;
if ($this->_sections['iprod']['show']):

            for ($this->_sections['iprod']['index'] = $this->_sections['iprod']['start'], $this->_sections['iprod']['iteration'] = 1;
                 $this->_sections['iprod']['iteration'] <= $this->_sections['iprod']['total'];
                 $this->_sections['iprod']['index'] += $this->_sections['iprod']['step'], $this->_sections['iprod']['iteration']++):
$this->_sections['iprod']['rownum'] = $this->_sections['iprod']['iteration'];
$this->_sections['iprod']['index_prev'] = $this->_sections['iprod']['index'] - $this->_sections['iprod']['step'];
$this->_sections['iprod']['index_next'] = $this->_sections['iprod']['index'] + $this->_sections['iprod']['step'];
$this->_sections['iprod']['first']      = ($this->_sections['iprod']['iteration'] == 1);
$this->_sections['iprod']['last']       = ($this->_sections['iprod']['iteration'] == $this->_sections['iprod']['total']);
?>
            <?php if ($this->_sections['iprod']['index'] != 0): ?>
            <div class="fil1"></div>
            <?php endif; ?><a href="<?php if (@CONF_MOD_REWRITE == 1): ?>page_<?php echo $this->_tpl_vars['productslinkscat'][$this->_sections['iprod']['index']][0]; ?>
.html<?php else: ?>index.php?show_aux_page=<?php echo $this->_tpl_vars['productslinkscat'][$this->_sections['iprod']['index']][0]; ?>
<?php endif; ?>"><?php echo $this->_tpl_vars['productslinkscat'][$this->_sections['iprod']['index']][1]; ?>
</a> <?php endfor; endif; ?> </td>
        </tr>
      </table>
      <table cellspacing="0" cellpadding="0" width="100%">
        <tr>
          <td class="hdbot">&nbsp;</td>
        </tr>
      </table>
      <?php endif; ?>
      <?php endif; ?> </td>
  </tr>
</table>
</div>