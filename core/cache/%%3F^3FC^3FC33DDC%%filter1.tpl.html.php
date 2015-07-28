<?php /* Smarty version 2.6.22, created on 2015-07-28 23:03:29
         compiled from blocks/filter1.tpl.html */ ?>
<link rel="stylesheet" type="text/css" href="data/<?php echo @TPL; ?>
/ui.dropdownchecklist.standalone.css">
<script type="text/javascript" src="data/<?php echo @TPL; ?>
/ui.dropdownchecklist-1.4-min.js"></script>
<link rel="stylesheet" type="text/css" href="data/<?php echo @TPL; ?>
/tinyTips.css">
<script type="text/javascript" src="data/<?php echo @TPL; ?>
/jquery.tinyTips.js"></script>

<div class="fade_out"> 
</div>

<div class="filter">
<h4>Подбор товара</h4>
<div id="extrafilter2">
<div class="fil_me"></div>
  <form name="efAdvancedSearchInCategory" method="get" action="index.php" id="efAdvancedSearchInCategory">
    <input type='hidden' name='categoryID' value='<?php echo $this->_tpl_vars['efcategoryID']; ?>
'>
    <input type='hidden' name='extrafilter' value='1'>
    <input type='hidden' value='1' name='advanced_search_in_category'>

    <b style="font-family:'helvetica',inherit;color:#000;"><?php echo @STRING_NAME; ?>
</b><br>
    <input type="text" name="search_name" style="width: 150px; margin-top: 2px;" value="<?php echo $this->_tpl_vars['efsearch_name']; ?>
"><br>
    <div class="fil_me"></div>
    <b style="font-family:'helvetica',inherit;color:#000;"><?php echo @STRING_PRODUCT_PRICE; ?>
</b><br>

    <div class="fil_me"></div>
    <div id="efslider"></div>

    <div class="fromto">
        <?php echo @STRING_PRICE_FROM; ?>
&nbsp;<input name="search_price_from" type="text" id="price_from" style="width:60px;padding-left:5px;">
        <?php echo @STRING_PRICE_TO; ?>
&nbsp;<input name="search_price_to" type="text" id="price_to" style="width: 60px;padding-left:5px;"> <?php echo $this->_tpl_vars['efpriceUnit']; ?>

    </div>

    <div class="fil_me"></div>

    
    <?php if ($this->_tpl_vars['efparams']): ?>
    <?php unset($this->_sections['i']);
$this->_sections['i']['name'] = 'i';
$this->_sections['i']['loop'] = is_array($_loop=$this->_tpl_vars['efparams']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
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
    <?php if ($this->_tpl_vars['efparams'][$this->_sections['i']['index']]['filter1'] == 1): ?>
    <div class="filterborder">
    <div style="margin-bottom: 8px;line-height:18px;font-size:13px;font-family:'helvetica',inherit;color:#000;"><b><?php echo $this->_tpl_vars['efparams'][$this->_sections['i']['index']]['name']; ?>
</b></div>

    <?php if ($this->_tpl_vars['efparams'][$this->_sections['i']['index']]['controlIsTextField'] == 0): ?>       <label><input type="checkbox" id='efall_<?php echo $this->_tpl_vars['efparams'][$this->_sections['i']['index']]['optionID']; ?>
' onclick="efParamToggle(<?php echo $this->_tpl_vars['efparams'][$this->_sections['i']['index']]['optionID']; ?>
,true)">Все</label><br>
	  <?php unset($this->_sections['j']);
$this->_sections['j']['name'] = 'j';
$this->_sections['j']['loop'] = is_array($_loop=$this->_tpl_vars['efparams'][$this->_sections['i']['index']]['variants']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
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
      <label><input type="checkbox" <?php echo $this->_tpl_vars['efparams'][$this->_sections['i']['index']]['variants'][$this->_sections['j']['index']]['set']; ?>
 name='param_<?php echo $this->_tpl_vars['efparams'][$this->_sections['i']['index']]['optionID']; ?>
[]' id='efparam_<?php echo $this->_tpl_vars['efparams'][$this->_sections['i']['index']]['optionID']; ?>
_<?php echo $this->_tpl_vars['efparams'][$this->_sections['i']['index']]['variants'][$this->_sections['j']['index']]['variantID']; ?>
' value='<?php echo $this->_tpl_vars['efparams'][$this->_sections['i']['index']]['variants'][$this->_sections['j']['index']]['variantID']; ?>
' onclick="$('input[id=\'efnon_<?php echo $this->_tpl_vars['efparams'][$this->_sections['i']['index']]['optionID']; ?>
\']').attr(<?php echo '{checked:false}'; ?>
);$('input[id=\'efall_<?php echo $this->_tpl_vars['efparams'][$this->_sections['i']['index']]['optionID']; ?>
\']').attr(<?php echo '{checked:false}'; ?>
);"><?php echo $this->_tpl_vars['efparams'][$this->_sections['i']['index']]['variants'][$this->_sections['j']['index']]['value']; ?>
 (<?php echo $this->_tpl_vars['efparams'][$this->_sections['i']['index']]['variants'][$this->_sections['j']['index']]['count']; ?>
)</label><br>
	  <?php endfor; endif; ?>

    <?php elseif ($this->_tpl_vars['efparams'][$this->_sections['i']['index']]['controlIsTextField'] == 1): ?>       <input type="text" style="width: 150px;" name='param_<?php echo $this->_tpl_vars['efparams'][$this->_sections['i']['index']]['optionID']; ?>
' id='efparam_<?php echo $this->_tpl_vars['efparams'][$this->_sections['i']['index']]['optionID']; ?>
' value='<?php echo $this->_tpl_vars['efparams'][$this->_sections['i']['index']]['set']; ?>
'>

    <?php elseif ($this->_tpl_vars['efparams'][$this->_sections['i']['index']]['controlIsTextField'] == 2): ?> 
      <script>
      $(function()
        <?php echo '{'; ?>

        efSliderInit(<?php echo $this->_tpl_vars['efparams'][$this->_sections['i']['index']]['optionID']; ?>
,<?php echo $this->_tpl_vars['efparams'][$this->_sections['i']['index']]['min']; ?>
,<?php echo $this->_tpl_vars['efparams'][$this->_sections['i']['index']]['max']; ?>
,<?php echo $this->_tpl_vars['efparams'][$this->_sections['i']['index']]['min_cursor']; ?>
,<?php echo $this->_tpl_vars['efparams'][$this->_sections['i']['index']]['max_cursor']; ?>
)
        <?php echo '}'; ?>
);
      </script>

      <label><input type="checkbox" name="param_<?php echo $this->_tpl_vars['efparams'][$this->_sections['i']['index']]['optionID']; ?>
[2]" id='efnon_<?php echo $this->_tpl_vars['efparams'][$this->_sections['i']['index']]['optionID']; ?>
' onclick="efSliderReset(<?php echo $this->_tpl_vars['efparams'][$this->_sections['i']['index']]['optionID']; ?>
,<?php echo $this->_tpl_vars['efparams'][$this->_sections['i']['index']]['min']; ?>
,<?php echo $this->_tpl_vars['efparams'][$this->_sections['i']['index']]['max']; ?>
)" <?php if ($this->_tpl_vars['efparams'][$this->_sections['i']['index']]['min_cursor'] == $this->_tpl_vars['efparams'][$this->_sections['i']['index']]['min'] && $this->_tpl_vars['efparams'][$this->_sections['i']['index']]['max_cursor'] == $this->_tpl_vars['efparams'][$this->_sections['i']['index']]['max']): ?>checked<?php endif; ?>><?php echo @STRING_UNIMPORTANT; ?>
</label><br>
      <div class="fil_me"></div>
      <div id="efslider_<?php echo $this->_tpl_vars['efparams'][$this->_sections['i']['index']]['optionID']; ?>
"></div>

      <div class="fromto">
          <?php echo @STRING_PRICE_FROM; ?>

          <input name="param_<?php echo $this->_tpl_vars['efparams'][$this->_sections['i']['index']]['optionID']; ?>
[0]" type="text" id="efparam_<?php echo $this->_tpl_vars['efparams'][$this->_sections['i']['index']]['optionID']; ?>
_1" style="width:60px;padding-left:5px;" onchange="efChangeFromTo(<?php echo $this->_tpl_vars['efparams'][$this->_sections['i']['index']]['optionID']; ?>
,0)">
          <?php echo @STRING_PRICE_TO; ?>

          <input name="param_<?php echo $this->_tpl_vars['efparams'][$this->_sections['i']['index']]['optionID']; ?>
[1]" type="text" id="efparam_<?php echo $this->_tpl_vars['efparams'][$this->_sections['i']['index']]['optionID']; ?>
_2" style="width:60px;padding-left:5px;" onchange="efChangeFromTo(<?php echo $this->_tpl_vars['efparams'][$this->_sections['i']['index']]['optionID']; ?>
,1)">
          <img class="tTipSmall" title="<b>Доступные варианты</b><?php unset($this->_sections['j']);
$this->_sections['j']['name'] = 'j';
$this->_sections['j']['loop'] = is_array($_loop=$this->_tpl_vars['efparams'][$this->_sections['i']['index']]['variants']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
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
?><br><?php echo $this->_tpl_vars['efparams'][$this->_sections['i']['index']]['variants'][$this->_sections['j']['index']]['value']; ?>
 (<?php echo $this->_tpl_vars['efparams'][$this->_sections['i']['index']]['variants'][$this->_sections['j']['index']]['count']; ?>
)<?php endfor; endif; ?>" width="15px" src="data/<?php echo @TPL; ?>
/q.png" alt="?">
      </div>

    <?php elseif ($this->_tpl_vars['efparams'][$this->_sections['i']['index']]['controlIsTextField'] == 3): ?>     <div class="ckbox">
      <select name='param_<?php echo $this->_tpl_vars['efparams'][$this->_sections['i']['index']]['optionID']; ?>
[]' id="efparam_<?php echo $this->_tpl_vars['efparams'][$this->_sections['i']['index']]['optionID']; ?>
" multiple="multiple" size="3" style="display:none">
        <option value='0'>Все</option>
        <?php unset($this->_sections['j']);
$this->_sections['j']['name'] = 'j';
$this->_sections['j']['loop'] = is_array($_loop=$this->_tpl_vars['efparams'][$this->_sections['i']['index']]['variants']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
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
        <option value='<?php echo $this->_tpl_vars['efparams'][$this->_sections['i']['index']]['variants'][$this->_sections['j']['index']]['variantID']; ?>
' <?php if ($this->_tpl_vars['efparams'][$this->_sections['i']['index']]['variants'][$this->_sections['j']['index']]['set']): ?>selected<?php endif; ?>><?php echo $this->_tpl_vars['efparams'][$this->_sections['i']['index']]['variants'][$this->_sections['j']['index']]['value']; ?>
 (<?php echo $this->_tpl_vars['efparams'][$this->_sections['i']['index']]['variants'][$this->_sections['j']['index']]['count']; ?>
)</option>
        <?php endfor; endif; ?>
      </select>
    </div>
    <?php endif; ?>
    </div>
    <?php endif; ?>
    <?php endfor; endif; ?>
    <?php endif; ?>
    <div class="fil_me"></div>
    <div class="fil_me"></div>
    <input type="button" value="<?php echo @VIEW_BUTTON; ?>
" onclick="document.getElementById('efAdvancedSearchInCategory').submit();return false;">
    <input type="button" value="Сбросить" onclick="efAllParamReset()">
  </form>
</div>
</div>

<script type="text/javascript">
<?php echo '
$(document).ready(function() {

 $(\'#extrafilter2\').show(); // dropdownchecklist не инитится нормально при display:none
 $("select[id^=\'efparam_\']").dropdownchecklist({
  icon: { placement: \'right\',
          toOpen:  \'ui-icon-arrowthick-1-s\',
          toClose: \'ui-icon-arrowthick-1-n\' },
  width: 150, 
  maxDropHeight: 150,
  emptyText: \'не важно\',
  zIndex: 6,
  firstItemChecksAll: true
  });
 $(\'#extrafilter2\').hide(); // вернем обратно

 $(\'img.tTipSmall\').tinyTips(\'small\', \'title\');

 $("#efslider").slider({
   '; ?>

   range:true,
   min:<?php echo $this->_tpl_vars['efsearch_price_min']; ?>
,
   max:<?php echo $this->_tpl_vars['efsearch_price_max']; ?>
,
   values:[<?php echo $this->_tpl_vars['efsearch_price_from']; ?>
,<?php echo $this->_tpl_vars['efsearch_price_to']; ?>
],
   step:1,
   <?php echo '
   slide:function(event,ui) {
     $("#price_from").val(ui.values[0]);
     $("#price_to").val(ui.values[1]);
     }
 });

 '; ?>

 $("#price_from").val(<?php echo $this->_tpl_vars['efsearch_price_from']; ?>
);
 $("#price_to").val(<?php echo $this->_tpl_vars['efsearch_price_to']; ?>
);
 <?php echo '

 efFilterSetShow();
});

function efFilterSetShow() {
  if(efGetCookie(\'effilterblock\') == \'0\'){
    $(\'#extrafilter1\').html(\'Свернуть\');
    $(\'#extrafilter2\').show();}
//  else{
//    $(\'#extrafilter1\').html(\'Развернуть\');
//    $(\'#extrafilter2\').hide();}
}

function efFilterToggleShow() {
  if($(\'#extrafilter1\').html()==\'Свернуть\'){
    efSetCookie(\'effilterblock\', \'1\', 10, \'/\');
    $(\'#extrafilter1\').html(\'Развернуть\');
    $(\'#extrafilter2\').hide();}
  else{
    efSetCookie(\'effilterblock\', \'0\', 10, \'/\');
    $(\'#extrafilter1\').html(\'Свернуть\');
    $(\'#extrafilter2\').show();}
}

function efChangeFromTo(optionID,fromto) {
  $("input[id=\'efnon_"+optionID+"\']").attr({checked:false});
  $("#efslider_"+optionID).slider(\'values\',fromto,getElementById(\'efparam_\'+optionID+\'_\'+fromto).value);
}

function efSliderInit(optionID,vmin,vmax,min_cursor,max_cursor) {
  $("#efslider_"+optionID).slider({
    range:true,
    min:vmin,
    max:vmax,
    values:[min_cursor,max_cursor],
    step:1,
    slide:function(event,ui) {
      $("#efparam_"+optionID+"_1").val(ui.values[0]);
      $("#efparam_"+optionID+"_2").val(ui.values[1]);
      $("input[id=\'efnon_"+optionID+"\']").attr({checked:false});
      }
  });
  $("#efparam_"+optionID+"_1").val(min_cursor);
  $("#efparam_"+optionID+"_2").val(max_cursor);
}

function efSliderReset(optionID,vmin,vmax) {
  $("input[id=\'efparam_"+optionID+"_1\']").val(vmin);
  $("input[id=\'efparam_"+optionID+"_2\']").val(vmax);
 $("#efslider_"+optionID).slider({values:[vmin,vmax]});
}

function efParamToggle(optionID) {
  $("input[id^=\'efparam_"+optionID+"_\']").attr(\'checked\',$("input[id=\'efall_"+optionID+"\']").is(\':checked\'));
}

function efParamSet(optionID,check) {
  $("input[id^=\'efparam_"+optionID+"_\']").attr({checked:check});
  $("input[id=\'efnon_"+optionID+"\']").attr({checked:!check});
  $("input[id=\'efall_"+optionID+"\']").attr({checked:check});
}

function efAllParamReset() {
  $("input[id^=\'efall_\']").attr({checked:false});
  $("input[id^=\'efnon_\']").attr({checked:true});
  $("input[id^=\'efparam_\']").attr({checked:false});
  $("input[id^=\'efparam_\']").val(\'\');
  $("select[id^=\'efparam_\']").val(\'\');
  $("select[id^=\'efparam_\']").dropdownchecklist(\'refresh\');
}

function efSetCookie(name, value, expiredays, path) {
  var exdate=new Date();
  exdate.setDate(exdate.getDate()+expiredays);
  var expires = exdate.toGMTString();
  document.cookie = name + "=" + escape(value) +
  ((expiredays) ? "; expires=" + expires : "") +
  ((path) ? "; path=" + path : "");
}

function efGetCookie(name) {
  var prefix = name + "="; var cookieStartIndex = document.cookie.indexOf(prefix);
  if (cookieStartIndex == -1) return null;
  var cookieEndIndex = document.cookie.indexOf(";", cookieStartIndex + prefix.length);
  if (cookieEndIndex == -1) cookieEndIndex = document.cookie.length;
  return unescape(document.cookie.substring(cookieStartIndex + prefix.length, cookieEndIndex));
}
'; ?>

</script>