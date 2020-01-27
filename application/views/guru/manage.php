<link rel="stylesheet" href="<?php echo base_url('assets/datepicker/css/datepicker.css')?>">
<script src="<?php echo base_url('assets/datepicker/js/bootstrap-datepicker.js')?>"></script>
<script>
$(function(){
	$('#tanggal_lahir').datepicker();
	$('#tanggal_masuk').datepicker();
	$('button[type="reset"]').click(function(evt) {
	    evt.preventDefault();
	    $(this).closest('form').get(0).reset();
	
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
		<form  method="post" action="<?php echo site_url("guru/save")?>"  >
			<input type="hidden" class="form-control" id="id" name="id" value="<?php echo $data->id_guru; ?>" >
			<div class="panel panel-default form-master">
			  <div class="panel-heading">
			  		<div class="row">
			  			<div class="col-md-4  pull-right">
			  				<div class="form-action pull-right">
			     				<button type="submit" class="btn btn-success" name="action" value="save">Save</button>
								<button type="submit" class="btn btn-success" name="action" value="saveexit">Save & Exit</button>
			     				<button type="reset" class="btn btn-warning">Reset</button>
			     				<a  href="<?php echo site_url("guru")?>" class="btn btn-danger">Cancel</a>
			     			</div>
			  			</div>
			  		</div>
			  </div>
			  <div class="panel-body">
				<div class="form-horizontal" >
					<div class="form-group">
						<label for="id_guru" class="col-sm-2 control-label">ID Guru</label>
						<div class="col-sm-4">
						  <input type="text" class="form-control" id="id_user" placeholder="input id guru" name="id_guru" value="<?php echo $data->id_guru == "" ? $data->autocode : $data->id_guru; ?>"  readonly >
						</div>
					</div>
					<div class="form-group">
						<label for="nip" class="col-sm-2 control-label">NIP</label>
						<div class="col-sm-4">
						  <input type="text" class="form-control" id="nip" placeholder="input nip" name="nip" value="<?php echo $data->nip; ?>" required="required" >
						</div>
					</div>
					<div class="form-group">
						<label for="nama" class="col-sm-2 control-label">Nama</label>
						<div class="col-sm-4">
						  <input type="text" class="form-control" id="nama" placeholder="input nama" name="nama" value="<?php echo $data->nama; ?>" required="required" >
						</div>
					</div>
					<div class="form-group">
						<label for="pria" class="col-sm-2 control-label">Jenis Kelamin</label>
						<div class="col-sm-4">
						  <div class="radio">
							  <label>
								<input type="radio" name="gender" id="pria" value="1"  <?php echo ($data->jenis_kelamin != "2") ? "checked" : ""; ?>>
								Pria
							  </label>
							</div>
							<div class="radio">
							  <label>
								<input type="radio" name="gender" id="wanita" value="2" <?php echo ($data->jenis_kelamin == "2") ? "checked" : ""; ?>>
								Wanita
							  </label>
							</div>
						</div>		
					</div>
					<div class="form-group">
						<label for="tempat_lahir" class="col-sm-2 control-label">Tempat Lahir</label>
						<div class="col-sm-4">
						  <input type="text" class="form-control" id="tempat_lahir" required="required" name="tempat_lahir" placeholder="input tempat lahir" value="<?php echo $data->tempat_lahir; ?>">
						</div>
					</div>
					<div class="form-group">
						<label for="tanggal_lahir" class="col-sm-2 control-label">Tanggal Lahir</label>
						<div class="col-sm-4">
							<div class="input-group">
							 <input type="text" class="form-control datepicker" id="tanggal_lahir" name="tanggal_lahir"  readonly data-date-format="mm/dd/yyyy" value="<?php echo ($data->tgl_lahir != "") ? date("m/d/Y",strtotime($data->tgl_lahir)) : date("m/d/Y") ?>">
							  <div class="input-group-addon glyphicon glyphicon-calendar"></div>
							</div>
						</div>
					</div>
					<div class="form-group">
						<label for="agama" class="col-sm-2 control-label">Agama</label>
						<div class="col-sm-4">
							<select class="form-control input-sm" name="agama">
								  <option value="islam" <?php echo $data->agama == "islam" ? 'selected' : '';?> >Islam</option>
								  <option value="katolik" <?php echo $data->agama == "katolik" ? 'selected' : '';?> >Katolik</option>
								  <option value="protestan" <?php echo $data->agama == "protestan" ? 'selected' : '';?> >Protestan</option>
								  <option value="hindu" <?php echo $data->agama == "hindu" ? 'selected' : '';?> >Hindu</option>
								  <option value="buddha" <?php echo $data->agama == "buddha" ? 'selected' : '';?> >Buddha</option>
								  <option value="konghucu" <?php echo $data->agama == "konghucu" ? 'selected' : '';?> >Konghucu</option>
							</select>
						</div>
					</div>
					<div class="form-group">
						<label for="alamat" class="col-sm-2 control-label">Alamat</label>
						<div class="col-sm-4">
						  <textarea class="form-control" rows="3" id="alamat" name="alamat" placeholder="input alamat" required="required"><?php echo $data->alamat; ?></textarea>
						</div>
					</div>
					<div class="form-group">
						<label for="golongan" class="col-sm-2 control-label">Golongan</label>
						<div class="col-sm-4">
							<select class="form-control input-sm" name="golongan">
							  <option value="II/b" <?php echo $data->golongan == "II/b" ? 'selected' : '';?> >II/b</option>
							  <option value="II/c" <?php echo $data->golongan == "II/c" ? 'selected' : '';?> >II/c</option>
							  <option value="II/d" <?php echo $data->golongan == "II/d" ? 'selected' : '';?> >II/d</option>
							  <option value="III/a" <?php echo $data->golongan == "III/a" ? 'selected' : '';?> >III/a</option>
							  <option value="III/b" <?php echo $data->golongan == "III/b" ? 'selected' : '';?> >III/b</option>
							  <option value="III/c" <?php echo $data->golongan == "III/c" ? 'selected' : '';?> >III/c</option>
							  <option value="III/d" <?php echo $data->golongan == "III/d" ? 'selected' : '';?> >III/d</option>
							  <option value="IV/a" <?php echo $data->golongan == "IV/a" ? 'selected' : '';?> >IV/a</option>
							  <option value="IV/b" <?php echo $data->golongan == "IV/b" ? 'selected' : '';?> >IV/b</option>
							  <option value="IV/c" <?php echo $data->golongan == "IV/c" ? 'selected' : '';?> >IV/c</option>
							  <option value="IV/d" <?php echo $data->golongan == "IV/d" ? 'selected' : '';?> >IV/d</option>
							   <option value="IV/e" <?php echo $data->golongan == "IV/e" ? 'selected' : '';?> >IV/e</option>
							   <option value="lainnya" <?php echo $data->golongan == "lainnya" ? 'selected' : '';?> >lainnya</option>
							</select>
						</div>
					</div>
					<div class="form-group">
						<label for="jabatan" class="col-sm-2 control-label">Jabatan</label>
						<div class="col-sm-4">
						  <select class="form-control input-sm" name="jabatan">
							  <option value="Wali Kelas" <?php echo $data->jabatan == "Wali Kelas" ? 'selected' : '';?> >Wali Kelas</option>
							  <option value="Guru Bidang Studi" <?php echo $data->jabatan == "Guru Bidang Studi" ? 'selected' : '';?> >Guru Bidang Studi</option>
							  <option value="Sekretariat Sekolah" <?php echo $data->jabatan == "Sekretariat Sekolah" ? 'selected' : '';?> >Sekretariat Sekolah</option>
							  <option value="Kepala Sekolah" <?php echo $data->jabatan == "Kepala Sekolah" ? 'selected' : '';?> >Kepala Sekolah</option>
							</select>
						</div>
					</div>
					<div class="form-group">
						<label for="pendidikan_terakhir" class="col-sm-2 control-label">Pendidikan Terakhir</label>
						<div class="col-sm-4">
							<select class="form-control input-sm" name="pendidikan_terakhir">
							  <option value="SD" <?php echo $data->pendidikan_terakhir == "SD" ? 'selected' : '';?> >SD</option>
							  <option value="SMP" <?php echo $data->pendidikan_terakhir == "SD" ? 'selected' : '';?> >SMP</option>
							  <option value="SMK" <?php echo $data->pendidikan_terakhir == "SD" ? 'selected' : '';?> >SMK</option>
							  <option value="SMA" <?php echo $data->pendidikan_terakhir == "SD" ? 'selected' : '';?> >SMA</option>
							  <option value="D1" <?php echo $data->pendidikan_terakhir == "SD" ? 'selected' : '';?> >D1</option>
							  <option value="D3" <?php echo $data->pendidikan_terakhir == "SD" ? 'selected' : '';?> >D3</option>
							  <option value="S1" <?php echo $data->pendidikan_terakhir == "SD" ? 'selected' : '';?> >S1</option>
							  <option value="S2" <?php echo $data->pendidikan_terakhir == "S2" ? 'selected' : '';?> >S2</option>
							  <option value="S3" <?php echo $data->pendidikan_terakhir == "S3" ? 'selected' : '';?> >S3</option>
							  <option value="praktisi" <?php echo $data->pendidikan_terakhir == "praktisi" ? 'selected' : '';?> >praktisi</option>
							  <option value="lainnya" <?php echo $data->pendidikan_terakhir == "lainnya" ? 'selected' : '';?> >lainnya</option>
							</select>
						</div>
					</div>
					<div class="form-group">
						<label for="email" class="col-sm-2 control-label">Email</label>
						<div class="col-sm-4">
						  <input type="email" class="form-control" id="email" name="email" placeholder="input email" value="<?php echo $data->email; ?>">
						</div>
					</div>
					<div class="form-group">
						<label for="telepon" class="col-sm-2 control-label">Telepon</label>
						<div class="col-sm-4">
						  <input type="text" class="form-control" id="telepon" name="telepon" placeholder="input telepon" value="<?php echo $data->telpon; ?>">
						</div>
					</div>
					<div class="form-group">
						<label for="tanggal_masuk" class="col-sm-2 control-label">Tanggal Masuk</label>
						<div class="col-sm-4">
							<div class="input-group">
							 <input type="text" class="form-control datepicker" id="tanggal_masuk" name="tanggal_masuk"  readonly data-date-format="mm/dd/yyyy" value="<?php echo ($data->tgl_masuk != "") ? date("m/d/Y",strtotime($data->tgl_masuk)) : date("m/d/Y") ?>">
							  <div class="input-group-addon glyphicon glyphicon-calendar"></div>
							</div>
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

