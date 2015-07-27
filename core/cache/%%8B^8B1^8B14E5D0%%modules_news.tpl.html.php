<?php /* Smarty version 2.6.22, created on 2015-07-20 10:48:56
         compiled from admin/modules_news.tpl.html */ ?>
<?php if ($this->_tpl_vars['news_editor'] == 1): ?>
<form action="<?php echo $this->_tpl_vars['urlToSubmit']; ?>
" method=POST name='MainForm' id='MainForm'>
<table class="adn">
<tr class="lineb">
<td align="left"><?php if ($this->_tpl_vars['edit_news']): ?><?php echo @STRING_NEWS2; ?>
<?php else: ?><?php echo @ADMIN_NEW_NEWSARTICLE; ?>
<?php endif; ?></td></tr>
<tr class="lins"><td align="left"><?php echo @ADMIN_NEWS_TITLE; ?>
: <input type=text name='title' value='<?php if ($this->_tpl_vars['edit_news']): ?><?php echo $this->_tpl_vars['edit_news']['title']; ?>
<?php else: ?><?php echo $this->_tpl_vars['title']; ?>
<?php endif; ?>' style="width: 440px;" class="textp">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo @ADMIN_CURRENT_DATE; ?>
: <input type=text name='add_date' value="<?php if ($this->_tpl_vars['edit_news']): ?><?php echo $this->_tpl_vars['edit_news']['add_date']; ?>
<?php else: ?><?php echo $this->_tpl_vars['current_date']; ?>
<?php endif; ?>" style="width: 86px;" class="textp"></td></tr>
</table>
<table class="adn"><tr><td class="separ"><img src="data/admin/pixel.gif" alt="" class="sep"></td></tr><tr><td class="se5"></td></tr></table>
<table class="adn">
<tr class="linsz">
<td align="left"><span class="titlecol2"><?php echo @ADMIN_TEXT_TO_PUBLICATION1; ?>
</span></td>
</tr>
<tr><td><textarea name='textToPrePublication' class="admin" id="area1"><?php if ($this->_tpl_vars['edit_news']): ?><?php echo $this->_tpl_vars['edit_news']['textToPrePublication']; ?>
<?php else: ?><?php echo $this->_tpl_vars['textToPrePublication']; ?>
<?php endif; ?></textarea></td>
</tr>
</table>
<table class="adn"><tr><td class="se6"></td></tr></table>
<table class="adn">
<tr class="linsz">
<td align="left"><span class="titlecol2"><?php echo @ADMIN_TEXT_TO_PUBLICATION2; ?>
</span></td>
</tr>
<tr><td><textarea name='textToPublication' class="admin" id="area2"><?php if ($this->_tpl_vars['edit_news']): ?><?php echo $this->_tpl_vars['edit_news']['textToPublication']; ?>
<?php else: ?><?php echo $this->_tpl_vars['textToPublication']; ?>
<?php endif; ?></textarea></td>
</tr>
</table>
<table class="adn"><tr><td class="se6"></td></tr></table>
<table class="adn">
<tr class="linsz">
<td align="left"><span class="titlecol2"><?php echo @ADMIN_TEXT_TO_MAIL; ?>
</span></td>
</tr></table>
<textarea name='textToMail' class="admin" id="area3"><?php if ($this->_tpl_vars['edit_news']): ?><?php echo $this->_tpl_vars['edit_news']['textToMail']; ?>
<?php else: ?><?php echo $this->_tpl_vars['textToMail']; ?>
<?php endif; ?></textarea>
<table class="adn"><tr><td class="se6"></td></tr></table>
<table class="adw"><tr><td><input type=checkbox name="send" <?php if ($this->_tpl_vars['send'] == 1): ?> checked <?php endif; ?> ></td><td> <?php echo @ADMIN_SEND_NEWS_TO_SUBSCRIBERS; ?>
</td>
</tr></table>
<table class="adn"><tr><td class="se5"></td></tr></table>
<a href="#" onclick="document.getElementById('MainForm').submit(); return false" class="inl"><?php echo @SAVE_BUTTON; ?>
</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a href="<?php echo @ADMIN_FILE; ?>
?dpt=modules&amp;sub=news" class="inl"><?php echo @CANCEL_BUTTON; ?>
</a>
<input type=hidden name="<?php if ($this->_tpl_vars['edit_news']): ?>update_news<?php else: ?>news_save<?php endif; ?>" value="1">
<?php if ($this->_tpl_vars['edit_news']): ?><input type=hidden name="edit_news_id" value="<?php echo $this->_tpl_vars['edit_news_id']; ?>
"><?php endif; ?>
<input type=hidden name=dpt value=modules>
<input type=hidden name=sub value=news>
</form>
<?php if (@CONF_EDITOR): ?>
<?php echo '
<script type="text/javascript" src="fckeditor/fckeditor.js"></script>
<script type="text/javascript" src="fckeditor/ckfinder/ckfinder.js"></script>
<script type="text/javascript">
window.onload = function()
{
var oFCKeditor = new FCKeditor( \'area1\',720,346) ;
'; ?>
<?php 
$dir1 = dirname($_SERVER['PHP_SELF']);
$sourcessrand = array("//" => "/", "\\" => "/");
$dir1 = strtr($dir1, $sourcessrand);
if ($dir1 != "/") $dir2 = "/"; else $dir2 = "";
echo "\n";
echo "oFCKeditor.BasePath = \"".$dir1.$dir2."fckeditor/\";\n";
 ?><?php echo '
oFCKeditor.ReplaceTextarea() ;
var oFCKeditor2 = new FCKeditor( \'area2\',720,346) ;
'; ?>
<?php 
$dir1 = dirname($_SERVER['PHP_SELF']);
$sourcessrand = array("//" => "/", "\\" => "/");
$dir1 = strtr($dir1, $sourcessrand);
if ($dir1 != "/") $dir2 = "/"; else $dir2 = "";
echo "\n";
echo "oFCKeditor2.BasePath = \"".$dir1.$dir2."fckeditor/\";\n";
 ?><?php echo '
oFCKeditor2.ReplaceTextarea() ;
var oFCKeditor3 = new FCKeditor( \'area3\',720,300) ;
'; ?>
<?php 
$dir1 = dirname($_SERVER['PHP_SELF']);
$sourcessrand = array("//" => "/", "\\" => "/");
$dir1 = strtr($dir1, $sourcessrand);
if ($dir1 != "/") $dir2 = "/"; else $dir2 = "";
echo "\n";
echo "oFCKeditor3.BasePath = \"".$dir1.$dir2."fckeditor/\";\n";
echo "oFCKeditor3.ToolbarSet = 'Basic';\n";
 ?><?php echo '
oFCKeditor3.ReplaceTextarea() ;
}
</script>
'; ?>

<?php endif; ?>
<?php if ($this->_tpl_vars['invalid_date']): ?><script type="text/javascript" defer>alert('<?php echo @ERROR_DATE; ?>
')</script><?php endif; ?>
<?php else: ?>
<table class="adn">
<tr class="lineb">
<td align="left" class="toph3"><?php echo @ADMIN_NEWS_DATEF; ?>
</td>
<td align="left" width="100%"><?php echo @ADMIN_NEWS_NAMEF; ?>
</td>
<td align="right"><?php echo @ADMIN_NEWS_FUCTION; ?>
</td>
</tr><?php $this->assign('admhl', 0); ?>
<?php unset($this->_sections['i']);
$this->_sections['i']['name'] = 'i';
$this->_sections['i']['loop'] = is_array($_loop=$this->_tpl_vars['news_posts']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
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
                <?php if ($this->_tpl_vars['admhl'] == 1): ?>
<tr><td colspan="3" class="separ"><img src="data/admin/pixel.gif" alt="" class="sep"></td></tr>
<?php else: ?><?php $this->assign('admhl', 1); ?><?php endif; ?>
<tr class="lineybig hover">
<td align="left"><?php echo $this->_tpl_vars['news_posts'][$this->_sections['i']['index']]['add_date']; ?>
</td>
<td align="left"><?php echo $this->_tpl_vars['news_posts'][$this->_sections['i']['index']]['title']; ?>
</td>
<td align="right"><a href="index.php?fullnews=<?php echo $this->_tpl_vars['news_posts'][$this->_sections['i']['index']]['NID']; ?>
"><?php echo @ADMIN_SHOW_AUX_PAGE; ?>
</a>&nbsp;|&nbsp;<a href="<?php echo $this->_tpl_vars['urlToSubmit']; ?>
&amp;edit=<?php echo $this->_tpl_vars['news_posts'][$this->_sections['i']['index']]['NID']; ?>
"><?php echo @ADMIN_EDIT_SMALL; ?>
</a>&nbsp;|&nbsp;<a href="#" onclick="confirmDelete('<?php echo $this->_tpl_vars['news_posts'][$this->_sections['i']['index']]['NID']; ?>
','<?php echo @QUESTION_DELETE_CONFIRMATION; ?>
','<?php echo $this->_tpl_vars['urlToDelete']; ?>
&amp;delete=');">X</a></td></tr>
<?php endfor; else: ?>
<tr>
<td align="center" colspan="3" height="20"><?php echo @STRING_EMPTY_LIST; ?>
</td>
</tr>
<?php endif; ?>
<?php if ($this->_tpl_vars['navigator']): ?>
<tr><td colspan="3" class="navigator"><?php echo $this->_tpl_vars['navigator']; ?>
</td>
</tr></table>
<table class="adn"><tr><td class="se5"></td></tr></table>
<?php else: ?></table>
<table class="adn"><tr><td class="separ"><img src="data/admin/pixel.gif" alt="" class="sep"></td></tr><tr><td class="se5"></td></tr></table>
<?php endif; ?>
<a href="<?php echo $this->_tpl_vars['urlToSubmit']; ?>
&amp;add_news" class="inl"><?php echo @ADMIN_NEWS_ADD; ?>
</a>
<?php endif; ?>
<table class="adn"><tr><td class="se6"></td></tr></table>
<table class="adn"><tr><td class="help"><span class="titlecol2"><?php echo @USEFUL_FOR_YOU; ?>
</span><div class="helptext"><?php echo @ALERT_ADMIN2; ?>
</div></td>
        </tr>
      </table>