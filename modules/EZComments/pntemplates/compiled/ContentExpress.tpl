<?php /* Smarty version 2.5.0, created on 2003-07-22 16:01:38
         compiled from ContentExpress.tpl */ ?>
<?php $this->_load_plugins(array(
array('modifier', 'date_format', 'ContentExpress.tpl', 13, false),)); ?>
<br><br>
<table bgcolor="<?php echo $this->_tpl_vars['bgcolor2']; ?>
" border="0" cellpadding="5" cellspacing="2" align="center" valign="top" width="99%">
<tr>
<td bgcolor="<?php echo $this->_tpl_vars['bgcolor2']; ?>
" valign="top" width="100%" colspan="2" align="center" class="pn-title">
<?php echo @constant('_EZCOMMENTS'); ?>

</td>
</tr>
<?php if (isset($this->_sections['comments'])) unset($this->_sections['comments']);
$this->_sections['comments']['name'] = 'comments';
$this->_sections['comments']['loop'] = is_array($this->_tpl_vars['comments']) ? count($this->_tpl_vars['comments']) : max(0, (int)$this->_tpl_vars['comments']);
$this->_sections['comments']['show'] = true;
$this->_sections['comments']['max'] = $this->_sections['comments']['loop'];
$this->_sections['comments']['step'] = 1;
$this->_sections['comments']['start'] = $this->_sections['comments']['step'] > 0 ? 0 : $this->_sections['comments']['loop']-1;
if ($this->_sections['comments']['show']) {
    $this->_sections['comments']['total'] = $this->_sections['comments']['loop'];
    if ($this->_sections['comments']['total'] == 0)
        $this->_sections['comments']['show'] = false;
} else
    $this->_sections['comments']['total'] = 0;
if ($this->_sections['comments']['show']):

            for ($this->_sections['comments']['index'] = $this->_sections['comments']['start'], $this->_sections['comments']['iteration'] = 1;
                 $this->_sections['comments']['iteration'] <= $this->_sections['comments']['total'];
                 $this->_sections['comments']['index'] += $this->_sections['comments']['step'], $this->_sections['comments']['iteration']++):
$this->_sections['comments']['rownum'] = $this->_sections['comments']['iteration'];
$this->_sections['comments']['index_prev'] = $this->_sections['comments']['index'] - $this->_sections['comments']['step'];
$this->_sections['comments']['index_next'] = $this->_sections['comments']['index'] + $this->_sections['comments']['step'];
$this->_sections['comments']['first']      = ($this->_sections['comments']['iteration'] == 1);
$this->_sections['comments']['last']       = ($this->_sections['comments']['iteration'] == $this->_sections['comments']['total']);
?>
<tr>
<td bgcolor="<?php echo $this->_tpl_vars['bgcolor4']; ?>
" valign="top" width="20%">
<?php echo $this->_tpl_vars['comments'][$this->_sections['comments']['index']]['uname']; ?>
<br>
<?php echo $this->_run_mod_handler('date_format', true, $this->_tpl_vars['comments'][$this->_sections['comments']['index']]['date'], "%d.%m.%y, %H:%M"); ?>

</td>
<td bgcolor="<?php echo $this->_tpl_vars['bgcolor4']; ?>
" valign="top"  width="80%">
<?php echo $this->_tpl_vars['comments'][$this->_sections['comments']['index']]['comment']; ?>

<?php if ($this->_tpl_vars['comments'][$this->_sections['comments']['index']]['del']): ?>
<br />
<form action="<?php echo $this->_tpl_vars['delurl']; ?>
" method="post">
<input type="hidden" name="authid" id="authid" value="<?php echo $this->_tpl_vars['authid']; ?>
">
<input type="hidden" name="EZComments_redirect" id="EZComments_redirect" value="<?php echo $this->_tpl_vars['redirect']; ?>
">
<input type="hidden" name="EZComments_id" id="EZComments_id" value="<?php echo $this->_tpl_vars['comments'][$this->_sections['comments']['index']]['id']; ?>
">
<input type="submit" value="<?php echo @constant('_EZCOMMENTS_DEL'); ?>
">
</form>
<?php endif; ?>
</tr>
<?php endfor; endif; ?>
<?php if ($this->_tpl_vars['allowadd']): ?>
<tr>
<td bgcolor="<?php echo $this->_tpl_vars['bgcolor4']; ?>
" valign="top">
<?php echo @constant('_EZCOMMENTS_COMMENT_ADD'); ?>

</td>
<td bgcolor="<?php echo $this->_tpl_vars['bgcolor4']; ?>
" valign="top">
<form action="<?php echo $this->_tpl_vars['addurl']; ?>
" method="post">
<input type="hidden" name="authid" id="authid" value="<?php echo $this->_tpl_vars['authid']; ?>
">
<input type="hidden" name="EZComments_redirect" id="EZComments_redirect" value="<?php echo $this->_tpl_vars['redirect']; ?>
">
<input type="hidden" name="EZComments_modname" id="EZComments_modname" value="<?php echo $this->_tpl_vars['modname']; ?>
">
<input type="hidden" name="EZComments_objectid" id="EZComments_objectid" value="<?php echo $this->_tpl_vars['objectid']; ?>
">
<textarea name="EZComments_comment" id="EZComments_comment" wrap="soft" cols="80" rows="10">
</textarea><br /><br />
<input type="submit" value="<?php echo @constant('_EZCOMMENTS_ADD'); ?>
">
</form>
</td>
</tr>
<?php else: ?>
<tr>
<td bgcolor="<?php echo $this->_tpl_vars['bgcolor4']; ?>
" colspan="2">
<?php echo @constant('_EZCOMMENTS_ONLYREG'); ?>
 <a href="user.php"><?php echo @constant('_EZCOMMENTS_GOTOREG'); ?>
</a>
</td>
</tr>
<?php endif; ?>
</table>
