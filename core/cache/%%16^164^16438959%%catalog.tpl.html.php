<?php /* Smarty version 2.6.22, created on 2015-07-17 15:39:32
         compiled from admin/catalog.tpl.html */ ?>
<table class="adn">
  <tr>
    <td class="zeb2 nbc">
      <table class="adn ggg">
         <tr>
           <td>
            <table class="adn">
              <tr>
              <?php unset($this->_sections['i']);
$this->_sections['i']['name'] = 'i';
$this->_sections['i']['loop'] = is_array($_loop=$this->_tpl_vars['admin_sub_departments']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
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
              <?php if ($this->_tpl_vars['current_sub'] == $this->_tpl_vars['admin_sub_departments'][$this->_sections['i']['index']]['id']): ?>
              <td class="nbc2"><span class="titlecol"><?php echo $this->_tpl_vars['admin_sub_departments'][$this->_sections['i']['index']]['name']; ?>
</span></td>
              <?php endif; ?>
              <?php endfor; endif; ?><td align="right" valign="middle" id="preproc"></td></tr>
              <tr>
               <td class="nbcl" colspan="2"></td>
              </tr>
            </table>
           </td>
         </tr>
      </table>
    </td>
  </tr>
  <tr>
    <td valign="top" align="center" class="zeb">
      <table class="adn">
        <tr>
          <td align="left">
            <?php if ($this->_tpl_vars['safemode']): ?>
            <table class="adminw">
              <tr>
                <td align="left">
                  <table class="adn">
                    <tr>
                      <td><img src="data/admin/stop2.gif" align="left" class="stop"></td>
                      <td class="splin"><span class="error"><?php echo @ERROR_MODULE_ACCESS2; ?>
</span><br><br><?php echo @ERROR_MODULE_ACCESS_DES2; ?>
</td>
                    </tr>
                  </table>
                </td>
              </tr>
            </table>
            <?php else: ?><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "admin/".($this->_tpl_vars['admin_sub_dpt']), 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?><?php endif; ?>
          </td>
        </tr>
      </table>
    </td>
  </tr>
</table>