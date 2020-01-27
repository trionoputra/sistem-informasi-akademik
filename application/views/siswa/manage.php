<link rel="stylesheet" href="<?php echo base_url('assets/datepicker/css/datepicker.css')?>">
<script src="<?php echo base_url('assets/datepicker/js/bootstrap-datepicker.js')?>"></script>
<script>
$(function(){
	$('#tanggal_lahir').datepicker();
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
		<form  method="post" action="<?php echo site_url("siswa/save")?>"  >
			<input type="hidden" class="form-control" id="id" name="id" value="<?php echo $data->nis; ?>" >
			<div class="panel panel-default form-master">
			  <div class="panel-heading">
			  		<div class="row">
			  			<div class="col-md-4  pull-right">
			  				<div class="form-action pull-right">
			     				<button type="submit" class="btn btn-success" name="action" value="save">Save</button>
								<button type="submit" class="btn btn-success" name="action" value="saveexit">Save & Exit</button>
			     				<button type="reset" class="btn btn-warning">Reset</button>
			     				<a  href="<?php echo site_url("siswa")?>" class="btn btn-danger">Cancel</a>
			     			</div>
			  			</div>
			  		</div>
			  </div>
			  <div class="panel-body">
				<div class="panel panel-default">
				  <div class="panel-heading">Data Siswa</div>
				  <div class="panel-body">
					<div class="row">
						<div class="col-md-6">
							<div class="form-horizontal" >
								<div class="form-group">
									<label for="nis" class="col-sm-4 control-label">NIS</label>
									<div class="col-sm-8">
									  <input type="text" class="form-control" id="nis" placeholder="input nis" name="nis" required="required" value="<?php echo $data->nis;?>"  <?php  echo $data->nis != '' ? 'readonly' :'' ?> >
									</div>
								</div>
								<div class="form-group">
									<label for="nis_nasional" class="col-sm-4 control-label">NIS Nasional</label>
									<div class="col-sm-8">
									  <input type="text" class="form-control" id="nis_nasional" required="required" placeholder="input nis nasional" name="nis_nasional" value="<?php echo $data->nis_nasional; ?>"  >
									</div>
								</div>
								<div class="form-group">
									<label for="nama" class="col-sm-4 control-label">Nama</label>
									<div class="col-sm-8">
									  <input type="text" class="form-control" id="nama" required="required"  placeholder="input nama" name="nama" value="<?php echo $data->nama; ?>"  >
									</div>
								</div>
								<div class="form-group">
									<label for="pria" class="col-sm-4 control-label">Jenis Kelamin</label>
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
									<label for="tempat_lahir" class="col-sm-4 control-label">Tempat Lahir</label>
									<div class="col-sm-8">
									  <input type="text" class="form-control"  required="required" id="tempat_lahir" name="tempat_lahir" placeholder="input tempat lahir" value="<?php echo $data->tempat_lahir; ?>">
									</div>
								</div>
								<div class="form-group">
									<label for="tanggal_lahir" class="col-sm-4 control-label">Tanggal Lahir</label>
									<div class="col-sm-4">
										<div class="input-group">
										 <input type="text" class="form-control datepicker" id="tanggal_lahir" name="tanggal_lahir"  readonly data-date-format="mm/dd/yyyy" value="<?php echo ($data->tanggal_lahir != "") ? date("m/d/Y",strtotime($data->tanggal_lahir)) : date("m/d/Y") ?>">
										  <div class="input-group-addon glyphicon glyphicon-calendar"></div>
										</div>
									</div>
								</div>
								<div class="form-group">
									<label for="agama" class="col-sm-4 control-label">Agama</label>
									<div class="col-sm-8">
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
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-horizontal" >
								<div class="form-group">
									<label for="alamat" class="col-sm-4 control-label">Alamat</label>
									<div class="col-sm-8">
									  <textarea class="form-control" rows="3"  required="required"  id="alamat" name="alamat" placeholder="input alamat"><?php echo $data->alamat; ?></textarea>
									</div>
								</div>
								<div class="form-group">
									<label for="anak_ke" class="col-sm-4 control-label">Anak ke</label>
									<div class="col-sm-4">
									  <input type="text" class="form-control"  required="required"  id="anak_ke" name="anak_ke" placeholder="input anak ke" value="<?php echo $data->anak_ke; ?>">
									</div>
								</div>
								<div class="form-group">
									<label for="tahun_masuk" class="col-sm-4 control-label">Tahun masuk</label>
									<div class="col-sm-4">
									  <input type="text" class="form-control"  required="required" id="tahun_masuk" name="tahun_masuk" placeholder="input tahun masuk" value="<?php echo $data->tahun_masuk; ?>">
									</div>
								</div>
								<div class="form-group">
									<label for="tahun_keluar" class="col-sm-4 control-label">Tahun Keluar</label>
									<div class="col-sm-4">
									  <input type="text" class="form-control" id="tahun_keluar" name="tahun_keluar" placeholder="input tahun keluar" value="<?php echo $data->tahun_keluar; ?>">
									</div>
								</div>
								<div class="form-group">
									<label for="alasan_keluar" class="col-sm-4 control-label">Alasan Keluar</label>
									<div class="col-sm-8">
									  <textarea class="form-control" rows="3" id="alasan_keluar" name="alasan_keluar" placeholder="input alasan keluar"><?php echo $data->alasan_keluar; ?></textarea>
									</div>
								</div>
								<div class="form-group">
									<label for="email" class="col-sm-4 control-label">Email Orang tua</label>
									<div class="col-sm-8">
									  <input type="email" class="form-control" id="email_ortu" name="email_ortu" placeholder="input email orang tua" value="<?php echo $data->email_ortu; ?>">
									</div>
								</div>
							</div>
						</div>
					</div>
				  </div>
				</div>
				<div class="panel panel-default">
				  <div class="panel-heading">Data Orang tua</div>
					  <div class="panel-body">
						<table class="table siswa-manage">
							<thead>
								<tr>
									<th></th>
									<th>Bapak</th>
									<th>Ibu</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td><label for="nama" class="col-sm-4 control-label">Nama</label></td>
									<td>
										<div class="col-sm-8">
											<input type="nama_bapak" class="form-control" id="nama_bapak" name="nama_bapak" placeholder="input nama bapak" value="<?php echo $data->nama_bapak; ?>">
										</div>
									</td>
									<td>
										<div class="col-sm-8">
											<input type="nama_ibu" class="form-control" id="nama_ibu" name="nama_ibu" placeholder="input nama ibu" value="<?php echo $data->nama_ibu; ?>">
										</div>
									</td>
								</tr>
								<tr>
									<td><label for="pekerjaan" class="col-sm-4 control-label">Pekerjaan</label></td>
									<td>
										<div class="col-sm-8">
											<input type="pekerjaan_bapak" class="form-control" id="pekerjaan_bapak" name="pekerjaan_bapak" placeholder="input pekerjaan bapak" value="<?php echo $data->pekerjaan_bapak; ?>">
										</div>
									</td>
									<td>
										<div class="col-sm-8">
											<input type="pekerjaan_ibu" class="form-control" id="pekerjaan_ibu" name="pekerjaan_ibu" placeholder="input pekerjaan ibu" value="<?php echo $data->pekerjaan_ibu; ?>">
										</div>
									</td>
								</tr>
								<tr>
									<td><label for="pendidikan" class="col-sm-4 control-label">Pendidikan</label></td>
									<td>
										<div class="col-sm-8">
											<select class="form-control input-sm" name="pendidikan_bapak">
											  <option value="SD" <?php echo $data->pendidikan_bapak == "SD" ? 'selected' : '';?> >SD</option>
											  <option value="SMP" <?php echo $data->pendidikan_bapak == "SD" ? 'selected' : '';?> >SMP</option>
											  <option value="SMK" <?php echo $data->pendidikan_bapak == "SD" ? 'selected' : '';?> >SMK</option>
											  <option value="SMA" <?php echo $data->pendidikan_bapak == "SD" ? 'selected' : '';?> >SMA</option>
											  <option value="D1" <?php echo $data->pendidikan_bapak == "SD" ? 'selected' : '';?> >D1</option>
											  <option value="D3" <?php echo $data->pendidikan_bapak == "SD" ? 'selected' : '';?> >D3</option>
											  <option value="S1" <?php echo $data->pendidikan_bapak == "SD" ? 'selected' : '';?> >S1</option>
											  <option value="S2" <?php echo $data->pendidikan_bapak == "S2" ? 'selected' : '';?> >S2</option>
											  <option value="S3" <?php echo $data->pendidikan_bapak == "S3" ? 'selected' : '';?> >S3</option>
											  <option value="praktisi" <?php echo $data->pendidikan_bapak == "praktisi" ? 'selected' : '';?> >praktisi</option>
											  <option value="lainnya" <?php echo $data->pendidikan_bapak == "lainnya" ? 'selected' : '';?> >lainnya</option>
											</select>
										</div>
									</td>
									<td>
										<div class="col-sm-8">
											<select class="form-control input-sm" name="pendidikan_ibu">
											  <option value="SD" <?php echo $data->pendidikan_ibu == "SD" ? 'selected' : '';?> >SD</option>
											  <option value="SMP" <?php echo $data->pendidikan_ibu == "SD" ? 'selected' : '';?> >SMP</option>
											  <option value="SMK" <?php echo $data->pendidikan_ibu == "SD" ? 'selected' : '';?> >SMK</option>
											  <option value="SMA" <?php echo $data->pendidikan_ibu == "SD" ? 'selected' : '';?> >SMA</option>
											  <option value="D1" <?php echo $data->pendidikan_ibu == "SD" ? 'selected' : '';?> >D1</option>
											  <option value="D3" <?php echo $data->pendidikan_ibu == "SD" ? 'selected' : '';?> >D3</option>
											  <option value="S1" <?php echo $data->pendidikan_ibu == "SD" ? 'selected' : '';?> >S1</option>
											  <option value="S2" <?php echo $data->pendidikan_ibu == "S2" ? 'selected' : '';?> >S2</option>
											  <option value="S3" <?php echo $data->pendidikan_ibu == "S3" ? 'selected' : '';?> >S3</option>
											  <option value="praktisi" <?php echo $data->pendidikan_ibu == "praktisi" ? 'selected' : '';?> >praktisi</option>
											  <option value="lainnya" <?php echo $data->pendidikan_ibu == "lainnya" ? 'selected' : '';?> >lainnya</option>
											</select>
										</div>
									</td>
								</tr>
								<tr>
									<td><label for="alamat" class="col-sm-4 control-label">Alamat</label></td>
									<td>
										<div class="col-sm-8">
											<textarea class="form-control" rows="3" id="alamat_bapak" name="alamat_bapak" placeholder="input alamat bapak"><?php echo $data->alamat_bapak; ?></textarea>
										</div>
									</td>
									<td>
										<div class="col-sm-8">
											<textarea class="form-control" rows="3" id="alamat_ibu" name="alamat_ibu" placeholder="input alamat ibu"><?php echo $data->alamat_ibu; ?></textarea>
										</div>
									</td>
								</tr>
								<tr>
									<td><label for="telepon" class="col-sm-4 control-label">Telepon</label></td>
									<td>
										<div class="col-sm-8">
											<input type="telp_bapak" class="form-control" id="telp_bapak" name="telp_bapak" placeholder="input telepon bapak" value="<?php echo $data->telp_bapak; ?>">
										</div>
									</td>
									<td>
										<div class="col-sm-8">
											<input type="telp_ibu" class="form-control" id="telp_ibu" name="telp_ibu" placeholder="input telepon ibu" value="<?php echo $data->telp_ibu; ?>">
										</div>
									</td>
								</tr>
							</tbody>
						</table>
					  </div>
				</div>
				<div class="panel panel-default">
				  <div class="panel-heading">Data Wali</div>
					  <div class="panel-body">
						<div class="form-horizontal" >
							<div class="form-group">
								<label for="nama" class="col-sm-2 control-label">Nama</label>
								<div class="col-sm-4">
								  <input type="nama_wali" class="form-control" id="nama_wali" name="nama_wali" placeholder="input nama wali" value="<?php echo $data->nama_wali; ?>">
								</div>
							</div>
							<div class="form-group">
								<label for="alamat" class="col-sm-2 control-label">Alamat</label>
								<div class="col-sm-4">
								  <textarea class="form-control" rows="3" id="alamat_wali" name="alamat_wali" placeholder="input alamat wali"><?php echo $data->alamat_wali; ?></textarea>
								</div>
							</div>
							<div class="form-group">
								<label for="nama" class="col-sm-2 control-label">Telepon</label>
								<div class="col-sm-4">
								  <input type="telp_wali" class="form-control" id="telp_wali" name="telp_wali" placeholder="input telepon wali" value="<?php echo $data->telp_wali; ?>">
								</div>
							</div>
							<div class="form-group">
								<label for="hubungan_wali" class="col-sm-2 control-label">Hubungan Wali</label>
								<div class="col-sm-3">
								  <input type="hubungan_wali" class="form-control" id="hubungan_wali" name="hubungan_wali" placeholder="input hubungan wali" value="<?php echo $data->hubungan_wali; ?>">
								</div>
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

