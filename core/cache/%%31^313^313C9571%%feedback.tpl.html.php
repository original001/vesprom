<?php /* Smarty version 2.6.22, created on 2015-07-17 22:34:54
         compiled from feedback.tpl.html */ ?>

<?php if ($this->_tpl_vars['sent'] == NULL): ?>
<div class="modal fade" id="contact" role="dialog">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h4>�������� �����</h4>
        </div>
        <div class="modal-body">
          <form class="form-horizontal" role="form" name="formfeedback" id="formfeedback" method="post" action="index.php">
            <div class="form-group">
              <label class="col-sm-3 control-label">���
                <input type="hidden" name="send" value="yes"><input type="hidden" name="feedback" value="yes">
              </label>
              <div class="col-sm-9">
                <input type="text" class="form-control" id="name" value="<?php echo $this->_tpl_vars['customer_name']; ?>
" name="customer_name">
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-3 control-label">email</label>
              <div class="col-sm-9">
                <input type="text" class="form-control" id="Theme" name="customer_email" value="<?php echo $this->_tpl_vars['customer_email']; ?>
">
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-3 control-label">����</label>
              <div class="col-sm-9">
                <input type="text" class="form-control" id="Theme" name="message_subject" value="<?php echo $this->_tpl_vars['message_subject']; ?>
">
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-3 control-label">���������</label>
              <div class="col-sm-9">
                <textarea class="form-control" rows="3" name="message_text"><?php echo $this->_tpl_vars['message_text']; ?>
</textarea>
              </div>
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <a href="#" class="btn btn-primary" onclick="document.getElementById('formfeedback').submit(); return false">���������</a>
          <a href="#" class="btn btn-default" data-dismiss="modal">�������</a>
        </div>
      </div>
    </div>
  </div>
  <?php if ($this->_tpl_vars['error'] != NULL): ?>
<?php if ($this->_tpl_vars['error'] == 2): ?><?php echo @ERR_WRONG_CCODE; ?>
<?php elseif ($this->_tpl_vars['error'] == 3): ?><?php echo @ERR_WRONG_POST; ?>
<?php else: ?>
  
  <div class="bread"><a href="/">������� </a>&nbsp;/&nbsp; ��������</div>
  </br>
  <div class="info_field">
  <div class="alert alert-danger alert-dismissable">
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
    ����������� ��������� ����
  </div>

  <a href="#" data-toggle="modal" data-target="#contact" style="text-align:center;width:900px;display:block">��������� ���� ������</a></div><?php endif; ?><?php endif; ?>
  <?php else: ?>
  <div class="bread"><a href="/">������� </a>&nbsp;/&nbsp; ��������</div>
  </br>
  <div class="info_field">
  <div class="alert alert-success alert-dismissable">
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
    ���� ��������� ������� ����������. �� �������� � ���� � ��������� �����
  </div>
  <a href="/" style="text-align:center;width:900px;display:block">�� �������</a></div>  
  <?php endif; ?>