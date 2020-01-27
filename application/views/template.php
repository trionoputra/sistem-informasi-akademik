<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title><?php echo $title; ?></title>
	<link rel="stylesheet" href="<?php echo base_url('assets/bootstrap-3.3.1/css/bootstrap.min.css')?>">
	
	<link rel="stylesheet" href="<?php echo base_url('assets/css/style.css')?>">
	<script src="<?php echo base_url('assets/js/jquery-2.1.1.min.js')?>"></script>
	<script src="<?php echo base_url('assets/bootstrap-3.3.1/js/bootstrap.min.js')?>"></script>
</head>
<body>
	<?php echo $this->load->view("header");?>
	<?php echo $this->load->view("menu");?>
	<?php echo $this->load->view($layout);?>
	<?php echo $this->load->view("footer");?>
</body>
</html>

