<?php /* Smarty version 2.6.22, created on 2015-07-28 23:02:50
         compiled from pricelist.tpl.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'fu_make_url', 'pricelist.tpl.html', 37, false),)), $this); ?>

<div class="bread">
        <a href="http://веспром.рф">Главная</a> &nbsp;/&nbsp; Прайс-лист
      </div>


<div class="page">
        <div class="btn-group">
          <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
               Сортировка <span class="caret"></span>
          </button>
          <ul class="dropdown-menu vipad" role="menu">
            <li><a href="index.php?show_price=yes&sort=name&direction=ASC">По наименованию</a></li>
            <li><a href="index.php?show_price=yes&sort=Price&direction=ASC">По цене</a></li>
          </ul>
        </div>
        
      </div>

<?php if ($this->_tpl_vars['pricelist_elements']): ?>
<div class="categories">
      <div class="price_list">
        <table class="table ">
        <thead>
          <tr>
            <th>Наименование</th>
            <th>Цена</th>
          </tr>
        </thead>
          <tbody>
            <?php unset($this->_sections['i']);
$this->_sections['i']['name'] = 'i';
$this->_sections['i']['loop'] = is_array($_loop=$this->_tpl_vars['pricelist_elements']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
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
      <?php $this->assign('paddingzn', 8); ?>
      <?php unset($this->_sections['j']);
$this->_sections['j']['name'] = 'j';
$this->_sections['j']['loop'] = is_array($_loop=$this->_tpl_vars['pricelist_elements']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['j']['max'] = (int)"(".($this->_tpl_vars['pricelist_elements'][$this->_sections['i']['index']][2])."-2)";
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
?><?php $this->assign('paddingzn', ($this->_tpl_vars['paddingzn']+8)); ?><?php endfor; endif; ?>
            <tr>
              <td <?php if ($this->_tpl_vars['pricelist_elements'][$this->_sections['i']['index']][4] != 1): ?> colspan="<?php if (@CONF_DISPLAY_PRCODE == 1): ?>3<?php else: ?>2<?php endif; ?>" <?php endif; ?> <?php if ($this->_tpl_vars['pricelist_elements'][$this->_sections['i']['index']][4] != 1): ?> class="lt" <?php endif; ?> width="99%" style="padding-left: 15px"><a href="<?php echo fu_make_url($this->_tpl_vars['pricelist_elements'][$this->_sections['i']['index']]); ?>
" class="noline"><?php echo $this->_tpl_vars['pricelist_elements'][$this->_sections['i']['index']][1]; ?>
</a></td>     
        <?php if (( $this->_tpl_vars['pricelist_elements'][$this->_sections['i']['index']][4] == 1 ) && ( $this->_tpl_vars['currencies_count'] != 0 )): ?><td nowrap="nowrap"><?php echo $this->_tpl_vars['pricelist_elements'][$this->_sections['i']['index']][5]; ?>
</td><?php endif; ?>
        <?php if (( $this->_tpl_vars['pricelist_elements'][$this->_sections['i']['index']][4] == 1 ) && ( @CONF_DISPLAY_PRCODE == 1 )): ?>
        <td nowrap="nowrap"><?php if ($this->_tpl_vars['pricelist_elements'][$this->_sections['i']['index']][7]): ?> <?php echo $this->_tpl_vars['pricelist_elements'][$this->_sections['i']['index']][7]; ?>
<?php endif; ?></td>
        <?php endif; ?>
    </tr>
      <?php endfor; endif; ?>
           
          </tbody>
        </table>
        <?php else: ?>
<table cellspacing="0" cellpadding="0" width="100%">
  <tr>
    <td class="hdbtop2" align="center"><?php echo @STRING_EMPTY_LIST; ?>
</td>
  </tr>
</table>
<table cellspacing="0" cellpadding="0" width="100%">
  <tr>
    <td class="hdbot2">&nbsp;</td>
  </tr>
</table>
<?php endif; ?>
<?php if ($this->_tpl_vars['pricelist_elements']): ?>
<table cellspacing="0" cellpadding="0" width="100%" class="print">
  <tr>
    <td><a href="index.php?download_price=yes"><?php echo @STRING_DOWNLOAD_PRICE; ?>
</a></td>
  </tr>
</table>
<?php endif; ?>
      </div>