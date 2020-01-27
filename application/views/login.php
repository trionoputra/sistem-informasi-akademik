<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title><?php echo $title; ?></title>
	<link rel="stylesheet" href="<?php echo base_url('assets/bootstrap-3.3.1/css/bootstrap.min.css')?>">
	<link rel="stylesheet" href="<?php echo base_url('assets/bootstrap-3.3.1/css/bootstrap-theme.min.css')?>">
	<link rel="stylesheet" href="<?php echo base_url('assets/css/style.css')?>">
	<script src="<?php echo base_url('assets/js/jquery-2.1.1.min.js')?>"></script>
	<script src="<?php echo base_url('assets/bootstrap-3.3.1/js/bootstrap.min.js')?>"></script>
</head>
<body class="login-body">
	    <div class="container" style="margin-top:40px">
		<div class="row">
			<div class="col-sm-6 col-md-4 col-md-offset-4">
				<?php
					 $msg = $this->session->flashdata('admin_login_msg');
				?>
				<?php if(!empty($msg)): ?>
				<div class="alert alert-danger">
					<a href="#" class="close" data-dismiss="alert">&times;</a>
					<strong>Error!</strong> <?php echo $msg;?>
				</div>
				<?php endif; ?>
				<div class="login-header"><h3>Login</h3></div>
				<div class="panel panel-default login-panel">
					<div class="panel-body">
						<form role="form" action="<?php echo site_url("login/auth") ?>" method="POST">
							<fieldset>
								<div class="row">
									<div class="col-sm-12 col-md-10  col-md-offset-1 ">
										<div class="form-group" style="margin-top:20px">
											<input class="form-control" placeholder="Username" name="username" type="text" autofocus>
										</div>
										<div class="form-group">
											<input class="form-control" placeholder="Password" name="password" type="password" value="">
										</div>
										<div class="form-group">
											<input type="submit" class="btn btn-lg btn-block btn-default" value="Login">
										</div>
									</div>
								</div>
							</fieldset>
						</form>
					</div>
                </div>
			</div>
		</div>
	</div>
</body>
</html>