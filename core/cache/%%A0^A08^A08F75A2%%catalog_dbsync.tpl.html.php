<?php /* Smarty version 2.6.22, created on 2015-07-20 09:45:09
         compiled from admin/catalog_dbsync.tpl.html */ ?>
<table class="adn">
<tr class="lineb">
<td align="left" width="50%"><?php echo @ADMIN_IMPORT_FROM_SQL; ?>
</td><td align="left" width="50%"><?php echo @ADMIN_EXPORT_DB_TO_FILE; ?>
</td>
</tr>
<tr class="lins">
<td align="left">
<form action="<?php echo @ADMIN_FILE; ?>
" enctype="multipart/form-data" method=post name="formimp" id="formimp">
<input type=file name=db class="file" size="20"><input type=hidden name="import_db" value=" ">
<br><br><a href="#" onclick="document.getElementById('formimp').submit(); return false" class="inl"><?php echo @BUTTON51; ?>
</a>
<input type=hidden name=dpt value="catalog"><input type=hidden name=sub value="dbsync">
</form>
</td>
<td align="left">
<form action="<?php echo @ADMIN_FILE; ?>
" method=post name="formexp" id="formexp">
<?php if ($this->_tpl_vars['sync_action'] != 'export'): ?>
              <input type=hidden name=export_db value=" "><a href="#"  onclick="document.getElementById('formexp').submit(); return false" class="inl"><?php echo @BUTTON52; ?>
</a>
              <input type=hidden name=dpt value="catalog">
              <input type=hidden name=sub value="dbsync">
<?php else: ?><?php echo @ADMIN_DB_EXPORTED_TO; ?>
<br><br><a href="<?php echo @ADMIN_FILE; ?>
?do=get_file&amp;getFileParam=<?php echo $this->_tpl_vars['getFileParam']; ?>
" class="inl"><?php echo $this->_tpl_vars['filenamegz']; ?>
</a> (<?php echo $this->_tpl_vars['database_filesize']; ?>
 ла) <?php endif; ?>
</form>
</td>
</tr></table>


<table class="adn">
<tr class="lineb">
<td align="left" width="50%"><?php echo @ADMIN_IMPORT_FROM_DBF; ?>
</td><td align="left" width="50%"><?php echo @ADMIN_EXPORT_FULLDB; ?>
</td>
</tr>
<tr class="lins">
<td align="left">
<form action="<?php echo @ADMIN_FILE; ?>
" enctype="multipart/form-data" method=post name="formimpdb" id="formimpdb">
<input type=file name=db_file class="file" size="20"><input type=hidden name="import_db_file" value=" ">
<br><br><a href="#" onclick="document.getElementById('formimpdb').submit(); return false" class="inl"><?php echo @BUTTON51; ?>
</a>
<input type=hidden name=dpt value="catalog"><input type=hidden name=sub value="dbsync">
</form>
</td>
<td align="left">
<form action="<?php echo @ADMIN_FILE; ?>
" method=post name="formexpf" id="formexpf">
<?php if (! $this->_tpl_vars['filenameffe']): ?><input type=hidden name=full_export value="yes"><a href="#" onclick="document.getElementById('formexpf').submit(); return false" class="inl"><?php echo @BUTTON52; ?>
</a>
              <input type=hidden name=dpt value="catalog">
              <input type=hidden name=sub value="dbsync">
<?php else: ?><?php echo @ADMIN_DB_EXPORTED_TO; ?>
<br><br><a href="<?php echo @ADMIN_FILE; ?>
?do=get_file&amp;getFileParam=<?php echo $this->_tpl_vars['getFileParam']; ?>
" class="inl"><?php echo $this->_tpl_vars['filenameffe']; ?>
</a>  (<?php echo $this->_tpl_vars['database_filesizef']; ?>
 ла) <?php endif; ?>
</form></td>
</tr></table>
<table class="adn"><tr><td class="separ"><img src="data/admin/pixel.gif" alt="" class="sep"></td></tr><tr><td class="se6"></td></tr></table>
<table class="adn"><tr><td class="help"><span class="titlecol2"><?php echo @USEFUL_FOR_YOU; ?>
</span><div class="helptext"><?php echo @ADMIN_SYNCR_DESC; ?>
<br><br><?php echo @ADMIN_IMPORT_FROM_SQL2; ?>
<br><br><?php echo @ADMIN_IMPORT_SQL_WARN; ?>
<br><br><?php echo @ADMIN_SYNCR_DESC2; ?>
</div></td></tr></table>
<?php if ($this->_tpl_vars['sync_action'] == 'import'): ?><?php if ($this->_tpl_vars['sync_successful'] == 0): ?><script type="text/javascript" defer>alert('<?php echo @ERROR_FAILED_TO_UPLOAD_FILE; ?>
')</script><?php endif; ?><?php endif; ?>