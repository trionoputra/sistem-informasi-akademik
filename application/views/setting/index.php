<script>
$(function(){
	$('button[type="reset"]').click(function(evt) {
	    evt.preventDefault();
	    $(this).closest('form').get(0).reset();
		
		if($('#isChange').is(':checked'))
			$('.passwrapper').show();
		else
			$('.passwrapper').hide();
	});
	
	$("#isChange").click(function(){
		if($('#isChange').is(':checked'))
			$('.passwrapper').show();
		else
			$('.passwrapper').hide()
	});
	
});


</script>
<div id="body-container">
	<div class="container">
		<div class="page-header form-header">
			<h3><?php echo $title?></h3>
		</div>
		<?php
			 $msg_err = $this->session->flashdata('admin_save_error');
			 $msg_succes = $this->session->flashdata('admin_save_success');
		?>
		<?php if(!empty($msg_err)): ?>
		<div class="alert alert-danger">
			<a href="#" class="close" data-dismiss="alert">&times;</a>
			<strong>Error!</strong> <?php echo $msg_err;?>
		</div>
		<?php endif; ?>
		<?php if(!empty($msg_succes)): ?>
		<div class="alert alert-success">
			<a href="#" class="close" data-dismiss="alert">&times;</a>
			<strong>Succes!</strong> <?php echo $msg_succes;?>
		</div>
		<?php endif; ?>
		<form  method="post" action="<?php echo site_url("setting/save")?>"  >
		
			<div class="panel panel-default form-master">
			  <div class="panel-heading">
			  		<div class="row">
			  			<div class="col-md-4  pull-right">
			  				<div class="form-action pull-right">
			     				<button type="submit" class="btn btn-success" name="action" value="save">Update</button>
								<button type="reset" class="btn btn-warning">Reset</button>
			     			</div>
			  			</div>
			  		</div>
			  </div>
			  <div class="panel-body">
			   <div class="form-horizontal" >
			   		<div class="form-group">
					    <label for="username" class="col-sm-2 control-label">Username</label>
					    <div class="col-sm-2">
					      <input type="text" class="form-control"  required="required" id="username" placeholder="input username" name="username" value="<?php echo $data->username; ?>"  >
					    </div>
					</div>
					<div class="form-group">
					    <label for="username" class="col-sm-2 control-label">change password</label>
					    <div class="col-sm-2">
					      <input type="checkbox" id="isChange" name="isChange" value="y">
					    </div>
					</div>
					<div class="form-group passwrapper" style="display:none">
					    <label for="password" class="col-sm-2 control-label"></label>
					    <div class="col-sm-3">
					      <input type="password" class="form-control" id="password" placeholder="input password" name="password">
					    </div>
					</div>
				</div>
			  </div>
			  <div class="panel-footer">
			  
			  </div>
			</div>
		</form>
	</div>
</div>