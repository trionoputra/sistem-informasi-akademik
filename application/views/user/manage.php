<script>
var lastkeyword = "";
$(function(){
	$('button[type="reset"]').click(function(evt) {
	    evt.preventDefault();
	    $(this).closest('form').get(0).reset();
		
		if ($("select[name='level']").val() == '1' || $("select[name='level']").val() == '0')
		{
			$("div.refid").hide();
			
		}
		else
		{
			$("div.refid").show();
		}
	
	});
	
	$('#ref-table').on('show.bs.modal', function(e) {
			getId(1);
	});
	$(".search").click(function(){
		getId(1);
	});
	
	$("select[name='level']").change(function(){
		$("input[name='refid']").val("");
		if ($("select[name='level']").val() == '1' || $("select[name='level']").val() == '0')
			$("div.refid").hide();
		else
			$("div.refid").show();
	});
	
});

function getId(page)
{
	if ($("select[name='level']").val() == '3')
		ur = "<?php echo site_url("api/ajax/getTableSiswa");?>";
	else if ($("select[name='level']").val() == '2')
		ur = "<?php echo site_url("api/ajax/getTableGuru");?>";
	else if ($("select[name='level']").val() == '4')
		ur = "<?php echo site_url("api/ajax/getTableGuru");?>";
	$(".table-ajax").empty();
	lastkeyword = $("#keyword").val();
	$.ajax({
		  dataType: "html",
		  url: ur,
		  data:{"keyword":lastkeyword,"userid":"emp",'page':page},
		  success:function(d){
			  $(".table-ajax").empty();
			  $(".table-ajax").html(d);
			}
	});	
}

function pilih(id)
{
	$("input[name='refid']").val(id);
}

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
		<form  method="post" action="<?php echo site_url("user/save")?>"  >
			<input type="hidden" class="form-control" id="id" name="id" value="<?php echo $data->id_user; ?>" >
			<div class="panel panel-default form-master">
			  <div class="panel-heading">
			  		<div class="row">
			  			<div class="col-md-4  pull-right">
			  				<div class="form-action pull-right">
			     				<button type="submit" class="btn btn-success" name="action" value="save">Save</button>
								<button type="submit" class="btn btn-success" name="action" value="saveexit">Save & Exit</button>
			     				<button type="reset" class="btn btn-warning">Reset</button>
			     				<a  href="<?php echo site_url("user")?>" class="btn btn-danger">Cancel</a>
			     			</div>
			  			</div>
			  		</div>
			  </div>
			  <div class="panel-body">
			   <div class="form-horizontal" >
					<div class="form-group">
					    <label for="id_user" class="col-sm-2 control-label">ID User</label>
					    <div class="col-sm-2">
					      <input type="text" class="form-control" id="id_user" placeholder="input id user" name="id_user" value="<?php echo $data->id_user == "" ? $data->autocode : $data->id_user; ?>"  readonly >
					    </div>
					</div>
			   		<div class="form-group">
					    <label for="username" class="col-sm-2 control-label">Username</label>
					    <div class="col-sm-2">
					      <input type="text" class="form-control"  required="required" id="username" placeholder="input username" name="username" value="<?php echo $data->username; ?>"  >
					    </div>
					</div>
					<div class="form-group">
					    <label for="password" class="col-sm-2 control-label">Password</label>
					    <div class="col-sm-3">
					      <input type="password" class="form-control" id="password" placeholder="input password" name="password" value="">
					    </div>
					</div>
					<div class="form-group">
					    <label for="level" class="col-sm-2 control-label">Level</label>
					    <div class="col-sm-2">
							<?php if($data->id_user == ""): ?>
							<select class="form-control input-sm" name="level">
							  <option value="1" <?php echo $data->level == '0' ? ' selected' : '';?> >Seketariat Sekolah</option>
							  <option value="1" <?php echo $data->level == '1' ? ' selected' : '';?> >Kepala Sekolah</option>
							  <option value="2" <?php echo $data->level == '2' ? ' selected' : '';?> >Guru Bidang Studi</option>
							  <option value="3" <?php echo $data->level == '3' ? ' selected' : '';?> >Wali Murid</option>
							  <option value="4" <?php echo $data->level == '4' ? ' selected' : '';?> >Wali Kelas</option>
							</select>
							<?php else: ?>
							<?php
								$l = "";
								if ( $data->level  == "0")
									$l = "Seketariat Sekolah";
								if ( $data->level  == "1")
									$l = "Kepala Sekolah";
								else if ( $data->level  == "2")
									$l = "Guru Bidang Studi";
								else if ( $data->level  == "3")
									$l = "Wali Murid";
								else if ( $data->level  == "4")
									$l = "Wali Kelas";
							?>
							<input type="text" class="form-control" id="level"  name="level" value="<?php echo $l; ?>"  readonly  disabled>
							<?php endif; ?>
					    </div>
					</div>
					<div class="form-group refid" style="display:none">
					    <label for="refid" class="col-sm-2 control-label">Reference ID</label>
					    <div class="col-sm-3">
							<input type="text" class="form-control" id="refid"  required="required" placeholder="pilih" name="refid" value="<?php echo $data->refid; ?>" readonly  />
					    </div>
						<a class="btn btn-default btn-sm" data-toggle="modal" data-target="#ref-table" href="#" <?php echo ($data->id_user != "") ? "style='display:none'" : '' ?>><span class="glyphicon glyphicon-search"  ></span></a>
					</div>
				</div>
			  </div>
			  <div class="panel-footer">
			  
			  </div>
			</div>
		</form>
	</div>
</div>

<div class="modal fade" id="ref-table" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog ajax-modal" style="margin-top:100px;">
		<div class="modal-content" >
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title" id="myModalLabel">Pilih</h4>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="form-filter-modal">
						<form class="form-inline" method="get" action="#" >
							<div class="form-group">
								<input type="text" class="form-control input-sm" id="keyword" placeholder="Keyword" name="keyword" value="">
							</div>
							<button type="button"  class="btn btn-primary btn-sm search">Search</button>
						</form>
					</div>
				</div>
				
					<div class="table-ajax">
					</div>
				
			</div>
			<div class="modal-footer">
				<a href="#" class="btn btn-danger danger delete" data-dismiss="modal">Cancel</a>
			</div>
		</div>
	</div>
</div>