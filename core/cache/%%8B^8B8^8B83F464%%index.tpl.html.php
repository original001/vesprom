<?php /* Smarty version 2.6.22, created on 2015-07-28 23:01:40
         compiled from admin/index.tpl.html */ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html class="admin">
<head>
<meta http-equiv="content-type" content="text/html; charset=<?php echo @DEFAULT_CHARSET; ?>
">
<link rel="stylesheet" href="data/admin/style.css" type="text/css">
<link rel="icon" href="data/admin/favicon.ico" type="image/x-icon">
<link rel="shortcut icon" href="data/admin/favicon.ico" type="image/x-icon">
<title><?php echo @ADMIN_TITLE; ?>
</title>
<script type="text/javascript" src="data/admin/admin.js"></script>
<?php echo '
<!--[if lte IE 6]>
<style type="text/css">
body {behavior:url("data/admin/csshover.htc");}
label{
   // display:inline-block;
}
</style>
<![endif]-->
'; ?>

</head>
<body class="ibody">
<table class="adn">
    <tr>
      <td colspan="2">
        <table class="adn">
          <tr>
            <td class="head"><img src="data/admin/sep.gif" alt=""></td>
            <td class="head toph">&nbsp;&nbsp;<?php echo @ADMIN_TMENU1; ?>
: <b><?php if (@CONF_BACKEND_SAFEMODE == 1): ?>demo<?php else: ?><?php echo $this->_tpl_vars['admintempname']; ?>
<?php endif; ?></b></td>
            <td class="head"><img src="data/admin/sep2.gif" alt=""></td>
            <td class="head toph"><?php echo @ADMIN_TMENU2; ?>
: <b><?php echo $this->_tpl_vars['online_users']; ?>
</b></td>
            <td class="head"><img src="data/admin/sep2.gif" alt=""></td>
            <td class="head last toph" width="100%"><a href="<?php echo @ADMIN_FILE; ?>
?order_search_type=SearchByStatusID&amp;checkbox_order_status_<?php echo @CONF_NEW_ORDER_STATUS; ?>
=1&amp;dpt=custord&amp;sub=new_orders&amp;search="><?php echo @ADMIN_TMENU3; ?>
: <b><?php echo $this->_tpl_vars['new_orders_count']; ?>
</b></a></td>
            <td class="head">
              <table class="adw">
                <tr>
                  <td class="head last toph"><a href="<?php echo @ADMIN_FILE; ?>
"><?php echo @ADMINISTRATE_LINK; ?>
</a></td>
                  <td class="head"><img src="data/admin/sep2.gif" alt=""></td>
                  <td class="head last toph"><a href="index.php"><?php echo @ADMIN_BACK_TO_SHOP; ?>
</a></td>
                  <td class="head"><img src="data/admin/sep2.gif" alt=""></td>
                  <td class="head last toph2 toph"><a href="index.php?logout=yes"><?php echo @ADMIN_LOGOUT_LINK; ?>
</a></td>
                </tr>
              </table>
            </td>
          </tr>
        </table>
      </td>
    </tr>
    <tr>
      <td class="indexb1"><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "admin/menu.tpl.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?></td>
      <td valign="top"><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "admin/".($this->_tpl_vars['admin_main_content_template']), 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?></td>
    </tr>
</table>
</body>
</html>