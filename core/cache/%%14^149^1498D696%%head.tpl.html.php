<?php /* Smarty version 2.6.22, created on 2015-07-17 15:25:44
         compiled from head.tpl.html */ ?>
<head>  
  
    
  <meta http-equiv="Content-Type" content="text/html; charset=<?php echo @DEFAULT_CHARSET; ?>
">
  <title><?php echo $this->_tpl_vars['page_title']; ?>
</title>
  <?php if ($this->_tpl_vars['page_meta_tags'] == ""): ?>
  <meta name='yandex-verification' content='45625f7050f5bd75' />
  <meta name="description" content="<?php echo @CONF_HOMEPAGE_META_DESCRIPTION; ?>
">
  <meta name="keywords" content="<?php echo @CONF_HOMEPAGE_META_KEYWORDS; ?>
">
  <?php else: ?>
  <?php echo $this->_tpl_vars['page_meta_tags']; ?>

  <?php endif; ?>
  
    <link rel="stylesheet" href="data/<?php echo @TPL; ?>
/bootstrap.css" type="text/css" media="screen">
  <link rel="stylesheet" href="data/<?php echo @TPL; ?>
/style.css" type="text/css" media="screen">
  <link rel="stylesheet" href="data/<?php echo @TPL; ?>
/styleprint.css" type="text/css" media="print">
  <link rel="icon" href="data/<?php echo @TPL; ?>
/favicon.ico" type="image/x-icon">
  <link rel="shortcut icon" href="data/<?php echo @TPL; ?>
/favicon.ico" type="image/x-icon">
  <link rel="stylesheet" type="text/css" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/base/jquery-ui.css">
  
    
  <script type="text/javascript">
  <!--
  var confirmUnsubscribe_act1 =  '<?php echo @QUESTION_UNSUBSCRIBE; ?>
';
  var validate_act1 =  '<?php echo @ERROR_INPUT_EMAIL; ?>
';
  var validate_disc_act1 =  '<?php echo @ERROR_INPUT_NICKNAME; ?>
';
  var validate_disc_act2 =  '<?php echo @ERROR_INPUT_MESSAGE_SUBJECT; ?>
';
  var validate_search_act1 =  '<?php echo @ERROR_INPUT_PRICE; ?>
';
  var doCL_act1 =  '<?php echo @STRING_COMPARISON_IN; ?>
';
  var doCL_act2 =  '<?php echo @CART_CONTENT_EMPTY; ?>
';
  var doCL_act3 =  '<?php echo @STRING_COMPARISON_TITLE_OK; ?>
';
  var renbox_act1 =  '<?php echo @STRING_COMPARISON_PROCESS; ?>
';
  var renboxCL_act1 =  '<?php echo @STRING_COMPARISON_TITLE_CL; ?>
';
  var doreset_act1 =  '<?php echo @STRING_CART_PROCESS; ?>
';
  var printcart_act1 =  '<?php echo @STRING_CART_PROCESS; ?>
';
  var doCart_act1 =  '<?php echo @STRING_CART_PR; ?>
';
  var doCart_act2 =  '<?php echo @CART_CONTENT_NOT_EMPTY; ?>
';
  var doCart_act3 =  '<?php echo @STRING_CUR_PR; ?>
';
  var doCart_act4 =  '<?php echo @CART_PROCEED_TO_CHECKOUT; ?>
';
  var doCart_act5 =  '<?php echo @STRING_CART_OKAX; ?>
';
  var doCpr_act1 =  '<?php echo @STRING_COMPARISON_IN; ?>
';
  var doCpr_act2 =  '<?php echo @CART_CONTENT_NOT_EMPTY; ?>
';
  var doCpr_act3 =  '<?php echo @STRING_COMPARISON_INFOLDER; ?>
';
  var doCpr_act4 =  '<?php echo @STRING_COMPARISON_CLEAR; ?>
';
  var doCpr_act5 =  '<?php echo @STRING_CART_OKAX; ?>
';

  function doCart(req) <?php echo '{'; ?>

    if(document.getElementById('cart') && req["shopping_cart_value"] > 0)<?php echo '{'; ?>

        document.getElementById('cart').innerHTML = '<b>' + doCart_act1 + ':<\/b>&nbsp;&nbsp;' + req["shopping_cart_items"] +
        '&nbsp;' + doCart_act2 + '<div style="padding-top: 4px;"><b>' + doCart_act3 + ':<\/b>&nbsp;&nbsp;' + req["shopping_cart_value_shown"] +
        '<\/div><div style="padding-top: 10px;" align="center"><table cellspacing="0" cellpadding="0" class="fsttab"><tr><td><table cellspacing="0" cellpadding="0" class="sectb"><tr><td><a <?php if (@CONF_OPEN_SHOPPING_CART_IN_NEW_WINDOW == 1): ?>href="#" onclick="open_window(\'index.php?do=cart\',500,300);"<?php else: ?>href="<?php if (@CONF_MOD_REWRITE == 1): ?>cart.html<?php else: ?>index.php?shopping_cart=yes<?php endif; ?>"<?php endif; ?>>' + doCart_act4 + '<\/a><\/td><\/tr><\/table><\/td><\/tr><\/table><\/div>';
        document.getElementById('axcrt').innerHTML = doCart_act5;
  <?php echo '}}'; ?>


  function doCpr(req) <?php echo '{'; ?>

    if(document.getElementById('cprbox') && req["cpr_value"] > 0)<?php echo '{'; ?>

        document.getElementById('cprbox').innerHTML = doCpr_act1 + '&nbsp;' + req["cpr_value"] +
        '&nbsp;' + doCpr_act2 + '<div style="padding-top: 10px;" align="center"><table cellspacing="0" cellpadding="0"><tr><td><table cellspacing="0" cellpadding="0" class="fsttab"><tr><td><table cellspacing="0" cellpadding="0" class="sectb"><tr><td><a href="<?php if (@CONF_MOD_REWRITE == 1): ?>compare.html<?php else: ?>index.php?comparison_products=yes<?php endif; ?>">' + doCpr_act3 + '<\/a><\/td><\/tr><\/table><\/td><\/tr><\/table><\/td><td>&nbsp;&nbsp;<\/td><td><table cellspacing="0" cellpadding="0" class="fsttab"><tr><td><table cellspacing="0" cellpadding="0" class="sectb"><tr><td><a href="#" onclick="doLoadcprCL(\'do=compare&amp;clear=yes\'); return false">' + doCpr_act4 + '<\/a><\/td><\/tr><\/table><\/td><\/tr><\/table><\/td><\/tr><\/table><\/div>';
        document.getElementById('axcrt').innerHTML = doCpr_act5;
  <?php echo '}}'; ?>

	
  function doStat(req) <?php echo '{'; ?>

    if(req)<?php echo '{'; ?>

        document.getElementById('tgenexe').innerHTML     = req['tgenexe'];
        document.getElementById('tgencompile').innerHTML = req['tgencompile'];
        document.getElementById('tgendb').innerHTML      = req['tgendb'];
        document.getElementById('tgenall').innerHTML     = req['tgenall'];
        document.getElementById('tgensql').innerHTML     = req['tgensql'];
  <?php echo '}}'; ?>

  //-->
  </script>
  <script type="text/javascript" src="data/<?php echo @TPL; ?>
/user.js"></script>
  <script type="text/javascript" src="data/<?php echo @TPL; ?>
/js/jquery.js"></script>
  <script type="text/javascript" src="data/<?php echo @TPL; ?>
/js/bootstrap.js"></script>
  <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7/jquery.min.js"></script>
  <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/jquery-ui.min.js"></script>
<link rel="stylesheet" type="text/css" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/base/jquery-ui.css">
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7/jquery.min.js"></script>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/jquery-ui.min.js"></script>
    
  <link rel="alternate" href="index.php?do=rss" title="rss" type="application/rss+xml">
  
    
  <?php echo '
  <!--[if lte IE 6]>
  <style type="text/css">
    #axcrt {
      top: expression(document.documentElement.scrollTop + Math.ceil((document.documentElement.clientHeight-100)/2)+ "px") !important;
      left: expression(Math.ceil((document.documentElement.clientWidth-300)/2)+ "px") !important;
    }
    body {behavior:url("data/admin/csshover.htc");}
    .semafor{background: none; filter:progid:DXImageTransform.Microsoft.AlphaImageLoader(src=\'data/'; ?>
<?php echo @TPL; ?>
<?php echo '/best.png\', sizingMethod=\'image\');}
  </style>
  <![endif]-->
  '; ?>

</head>