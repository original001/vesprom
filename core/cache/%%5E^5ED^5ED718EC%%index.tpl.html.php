<?php /* Smarty version 2.6.22, created on 2015-07-17 15:25:44
         compiled from index.tpl.html */ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "head.tpl.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<body>
<?php if (@CONF_SHOP_BILD == 1 && $this->_tpl_vars['isadmin'] != 'yes'): ?>


<table cellspacing="0" cellpadding="0" width="100%" height="100%">
  <tr>
    <td class="profi arc" valign="middle" align="center"><img src="data/<?php echo @TPL; ?>
/bild.gif" alt="">
	<br><br><?php echo @STRING_BILD; ?>
<br><?php echo @STRING_BILD_DES; ?>
</td>
  </tr>
</table>

<?php else: ?>

<div class="main">
    
    <div class="head" id="head">
        <a href="/" class="logo">
          <img src="data/<?php echo @TPL; ?>
/img/logo.jpg" alt="ВесПром">
        </a>
      <div class="tel">
        <img src="data/<?php echo @TPL; ?>
/img/phone.png" style="margin-top:-3px;">
        +7 (343) 271-02-45
        <div>
          <a href="mailto:vespromural@mail.ru" >vespromural@mail.ru</a>
        </div>
      </div>
    </div>
<?php if ($this->_tpl_vars['main_content_template'] == "home.tpl.html"): ?>
<div id="carousel-example-generic" class="carousel slide" data-ride="carousel">
  <!-- Indicators -->
  <ol class="carousel-indicators">
    <li data-target="#carousel-example-generic" data-slide-to="0" class="active"></li>
    <li data-target="#carousel-example-generic" data-slide-to="1"></li>
    <li data-target="#carousel-example-generic" data-slide-to="2"></li>
  </ol>

  <!-- Wrapper for slides -->
  <div class="carousel-inner">
    <div class="item active">
      <img src="data/<?php echo @TPL; ?>
/img/slider1.jpg">
      <div class="carousel-caption">
        <h3>БМК-6, Высокий класс точности</h3>
      <p>Предназначены для статического измерения массы автом
обилей, прицепов, полуприцепов и цистерн с полным</p>
      </div>
    </div>
    <div class="item">
      <img src="data/<?php echo @TPL; ?>
/img/slider2.jpg">
      <div class="carousel-caption">
        <h3>Лабораторные весы Ohaus PA-214</h3>
      <p>Оптимальная комбинация функций, понятный интерфейс и простота конструкции делают их незаменимым помощником в работе.</p>
      </div>
    </div>
    <div class="item">
      <img src="data/<?php echo @TPL; ?>
/img/slider3.jpg">
      <div class="carousel-caption">
        <h3>Лабораторные влагозащищенные весы CJ</h3>
      <p>Корпус из нержавеющей стали, высокая скорость отклика, малое потребление энергии</p>
      </div>
    </div>
</div>
  <!-- Controls -->
  <a class="left carousel-control" href="#carousel-example-generic" data-slide="prev">
    <span class="glyphicon glyphicon-chevron-left"></span>
  </a>
  <a class="right carousel-control" href="#carousel-example-generic" data-slide="next">
    <span class="glyphicon glyphicon-chevron-right"></span>
  </a>
</div>
<?php endif; ?>
    <div class="navi">
      <ul>
        <li class="navi3"><a href="page_1.html">О компании</a></li>
        <li class="navi1 dropdown">
          <a data-toggle="dropdown" data-hover="dropdown" href="#" style="line-height:80px;">Каталог</a>
          <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "blocks/category_tree.tpl.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?></li>
        <li class="navi2"><a href="price.html">Прайс-лист</a></li>
        <li class="navi4"><a href="page_2.html">Контакты</a></li>
      </ul>
    </div>

    
      
        <div class="categories">
      <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => ($this->_tpl_vars['main_content_template']), 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
      <?php if ($this->_tpl_vars['main_content_template'] == "home.tpl.html"): ?>
      <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "blocks/category_center.php", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?><?php endif; ?>
        </div>
      
      <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "blocks.tpl.html", 'smarty_include_vars' => array('binfo' => $this->_tpl_vars['top_blocks'],'bclass' => 'hdbtop','balign' => 'left')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

      

    

    

    <div class="footer">
      <div class="footer_left">
      <h3 class="footer_text">Copyright  © ВесПром Урал <p><small>
Весовое оборудование</small></p></h3>
      <!--<h3 class="footer_text">Карта сайта</h3>
      <h4>
        <p><a href="/">Каталог</a></small></p>
        <p><a href="price.html">Прайс-лист</a></small></p>
        <p><a href="page_1.html">О компании</a></small></p>
        <p><a href="page_2.html">Контакты</a></small></p>
      </h4>-->
      </div>
      <div class="footer_center">
      <!--  <h3 class="footer_text">Продукция</h3>
      <h4>
        <p><a href="category_2.html">Аналитические весы</a></small></p>
        <p><a href="category_3.html">Анализаторы влажности</a></small></p>
        <p><a href="category_4.html">Атомобильные весы</a></small></p>
        <p><a href="category_5.html">Динамометры электрические</a></small></p>
        <p><a href="category_6.html">Счетные весы</a></small></p>
        <p><a href="category_7.html">Ювелирные весы</a></small></p>
        <p><a href="category_8.html">Крановые весы</a></small></p>
      </h4>-->
      </div>
      <div class="footer_right">
        <div class="search">
          <form action="index.php" method="get" name="formpoisk" id="formpoisk">
          <?php unset($this->_sections['sert']);
$this->_sections['sert']['name'] = 'sert';
$this->_sections['sert']['loop'] = is_array($_loop=$this->_tpl_vars['searchstrings']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['sert']['show'] = true;
$this->_sections['sert']['max'] = $this->_sections['sert']['loop'];
$this->_sections['sert']['step'] = 1;
$this->_sections['sert']['start'] = $this->_sections['sert']['step'] > 0 ? 0 : $this->_sections['sert']['loop']-1;
if ($this->_sections['sert']['show']) {
    $this->_sections['sert']['total'] = $this->_sections['sert']['loop'];
    if ($this->_sections['sert']['total'] == 0)
        $this->_sections['sert']['show'] = false;
} else
    $this->_sections['sert']['total'] = 0;
if ($this->_sections['sert']['show']):

            for ($this->_sections['sert']['index'] = $this->_sections['sert']['start'], $this->_sections['sert']['iteration'] = 1;
                 $this->_sections['sert']['iteration'] <= $this->_sections['sert']['total'];
                 $this->_sections['sert']['index'] += $this->_sections['sert']['step'], $this->_sections['sert']['iteration']++):
$this->_sections['sert']['rownum'] = $this->_sections['sert']['iteration'];
$this->_sections['sert']['index_prev'] = $this->_sections['sert']['index'] - $this->_sections['sert']['step'];
$this->_sections['sert']['index_next'] = $this->_sections['sert']['index'] + $this->_sections['sert']['step'];
$this->_sections['sert']['first']      = ($this->_sections['sert']['iteration'] == 1);
$this->_sections['sert']['last']       = ($this->_sections['sert']['iteration'] == $this->_sections['sert']['total']);
?><input type="hidden" name='search_string_<?php echo $this->_sections['sert']['index']; ?>
' value='<?php echo $this->_tpl_vars['searchstrings'][$this->_sections['sert']['index']]; ?>
'><?php endfor; endif; ?>
          <input type="text" name="searchstring" class="search_input" value="<?php echo $this->_tpl_vars['searchstring']; ?>
">
          <h3>Поиск</h3></form>
        </div>
        <div class="company">
         ООО "ВесПромУрал"<p>
Екатеринбург</p><p>
ул. Мира 37</p>
        </div>
      </div>
      </div>

      <div class="sign" style="position: relative;">
        <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "liveinternet.tpl.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
        Сайт разработан вебстудией <a href="/forward?url=http://novaview.ru">NovaView</a>
      </div>

      <div class="anchor" id="anchor">
      </div>

    </div>
    <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "blocks.tpl.html", 'smarty_include_vars' => array('binfo' => $this->_tpl_vars['right_blocks'],'bclass' => 'hdb','nopad' => 1,'balign' => 'center')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
    <?php echo '
    <script type="text/javascript" src="data/vesprom/js/script.js"></script>
    '; ?>

<?php echo '
<!-- Yandex.Metrika counter -->
<script type="text/javascript">
(function (d, w, c) {
    (w[c] = w[c] || []).push(function() {
        try {
            w.yaCounter24374266 = new Ya.Metrika({id:24374266,
                    clickmap:true,
                    trackLinks:true,
                    accurateTrackBounce:true});
        } catch(e) { }
    });

    var n = d.getElementsByTagName("script")[0],
        s = d.createElement("script"),
        f = function () { n.parentNode.insertBefore(s, n); };
    s.type = "text/javascript";
    s.async = true;
    s.src = (d.location.protocol == "https:" ? "https:" : "http:") + "//mc.yandex.ru/metrika/watch.js";

    if (w.opera == "[object Opera]") {
        d.addEventListener("DOMContentLoaded", f, false);
    } else { f(); }
})(document, window, "yandex_metrika_callbacks");
</script>
<noscript><div><img src="//mc.yandex.ru/watch/24374266" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
<!-- /Yandex.Metrika counter -->'; ?>
 

<?php echo '
  <script type="text/javascript"
src="http://consultsystems.ru/script/17263/" charset="utf-8">
</script> 
'; ?>
 
    </body>
</html>

<script type="text/javascript">printcart();</script>

<?php endif; ?>
</body></html>