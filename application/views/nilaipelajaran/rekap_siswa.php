<script>
	$(function(){
		 $("select[name='thn_ajaran']").change(function()
		 {
			$('#filter-form').submit();
		 });
		 
		 $("select[name='kelas']").change(function()
		 {
			$('#filter-form').submit();
		 });
		 
		 $("select[name='semester']").change(function()
		 {
			$('#filter-form').submit();
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
		<div class="panel panel-default form-master">
			<form class="form-horizontal" method="get" action="<?php echo site_url("nilaipelajaran/rekap/siswa")?>" id="filter-form">
			<div class="panel-heading">
				<div class="row">
					<div class="col-md-5  pull-right">
						<div class="form-action pull-right">
							<button type="submit" class="btn btn-success" name="action" value="pdf">Export to PDF</button> 
<!--							<button type="submit" class="btn btn-success" name="action" value="excel">Export to Excel</button> -->
						</div> 
					</div>
				</div>
				
			</div>
			<div class="panel-body">
				<div class="form-group ">
					<label for="nis" class="col-sm-2">NIS</label>
					<div class="col-sm-2">
						<input type="text" class="form-control" id="nis" name="nis" value="<?php echo $nis ; ?>" disabled />
					</div>
				</div>
				<div class="form-group ">
					<label for="nama" class="col-sm-2">Nama</label>
					<div class="col-sm-2">
						<input type="text" class="form-control" id="nama" name="nama" value="<?php echo $nama ; ?>" disabled />
					</div>
				</div>
				<div class="form-group">
					<label for="kelas" class="col-sm-2">Kelas</label>
					<div class="col-sm-2">
						<select class="form-control input-sm" name="kelas">
						  <?php foreach ($kelas as $kls):?>
						  <option value="<?php echo $kls['id_kelas']."|".$kls['id_tempati'];?>" <?php echo $this->input->get("kelas") == $kls['id_kelas']."|".$kls['id_tempati'] ? ' selected' : '';?> ><?php echo $kls['nm_kelas']?></option>
						  <?php endforeach;?>
						</select>
					</div>
				</div>
				<div class="form-group ">
					<label for="no_absen" class="col-sm-2">No Absen</label>
					<div class="col-sm-2">
						<input type="text" class="form-control" id="no_absen" name="no_absen" value="<?php echo $no_absen ; ?>" disabled />
					</div>
				</div>
				<div class="form-group">
					<label for="semester" class="col-sm-2">Semester</label>
					<div class="col-sm-2">
						<select class="form-control input-sm" name="semester">
							<option value="1" <?php echo $this->input->get("semester") == '1' ? ' selected' : '';?> >1</option>
							<option value="2" <?php echo $this->input->get("semester") == '2' ? ' selected' : '';?> >2</option>
						</select>
					</div>
				</div>
				<div class="form-group">
					<label for="tahun_ajaran" class="col-sm-2">Tahun ajaran</label>
					<div class="col-sm-2">
						<input type="text" class="form-control" id="thn_ajaran" name="thn_ajaran" value="<?php echo $tahun_ajaran; ?>" disabled />
					</div>
				</div>
			</form>
			<table class="table table-striped table-small">
			<thead>
			  <tr>
				<th>1</th>
				<th>2</th>
				<th>3</th>
				<?php $count = 3; ?>
				<?php $hasTugas = false; ?>
				<?php foreach($jenis_nilai as $jn): ?>
				<?php if (strpos(strtolower($jn['des_jenis_nilai']), 'tugas') !== false || strpos(strtolower($jn['des_jenis_nilai']), 'tgs') !== false):?>
				<?php $hasTugas = true; ?>
				<?php $count ++; ?>
				<th><?php echo $count; ?></th>
				<?php endif; ?>
				<?php endforeach ?>
				<?php if($hasTugas): ?>
				<?php $count ++; ?>
				<th><?php echo $count; ?></th>
				<?php endif; ?>
			
				<?php $hasUH = false; ?>
				<?php foreach($jenis_nilai as $jn): ?>
				<?php if (strpos(strtolower($jn['des_jenis_nilai']), 'ulangan') !== false || strpos(strtolower($jn['des_jenis_nilai']), 'uh') !== false):?>
				<?php $hasUH = true; ?>
				<?php $count ++; ?>
				<th><?php echo $count; ?></th>
				<?php endif; ?>
				<?php endforeach ?>
				<?php if($hasUH): ?>
				<?php $count ++; ?>
				<th><?php echo $count; ?></th>
				<?php endif; ?>
				
				<?php foreach($jenis_nilai as $jn): ?>
				<?php if(strpos(strtolower($jn['des_jenis_nilai']), 'tugas') === false && strpos(strtolower($jn['des_jenis_nilai']), 'tgs') === false && strpos(strtolower($jn['des_jenis_nilai']), 'uh') === false&& strpos(strtolower($jn['des_jenis_nilai']), 'ulangan') === false): ?>
				<?php $count ++; ?>
				<th><?php echo $count; ?></th>
				<?php endif; ?>
				<?php endforeach ?>
				
				<?php $count ++; ?>
				<th><?php echo $count; ?></th>
			  </tr>
			</thead>
			<tbody>
			<?php foreach($data as $key => $dt): ?>
			  <tr>
				<td><?php echo ($key+1) ?></td>
				<td><?php echo $dt['deskripsi'];?></td>
				<td><?php echo $dt['kkm'];?></td>
				
				<?php 
			
					$jenis =  explode(",",$dt['jenis_nilai']);
					$n =  explode(",",$dt['nilai']);
					$ct =  0;
					$tt =  0;
					
				?>
				
				<?php $count = 3; ?>
				<?php $hasTugas = false; ?>
				<?php $hasTugasValue = false; ?>
				<?php foreach($jenis_nilai as $jn): ?>
				<?php if (strpos(strtolower($jn['des_jenis_nilai']), 'tugas') !== false || strpos(strtolower($jn['des_jenis_nilai']), 'tgs') !== false):?>
				<?php $hasTugas = true; ?>
				<?php $count ++; ?>
				<?php $nn = "<b>-</b>"; ?>
				<?php foreach($jenis as $k => $j): ?>
					<?php if($j == $jn['des_jenis_nilai']): ?>
					<?php $nn = $n[$k];$ct++;$tt +=$n[$k];?>
					<?php $hasTugasValue = true; ?>
					<?php endif; ?>
				<?php endforeach ?>
				
				<?php if($nn < $dt['kkm']) :?>
				<td><span style='color:red'><?php echo $nn;?></span></td>
				<?php else: ?>
				<td><?php echo $nn;?></td>
				<?php endif ?>
				
				<?php endif; ?>
				<?php endforeach ?>
				
				<?php if($hasTugas && $hasTugasValue): ?>
				<?php $count ++; ?>
				<td><?php echo ceil($tt/$ct)?></td>
				<?php endif; ?>
				
				<?php if($hasTugas && !$hasTugasValue): ?>
				<?php $count ++; ?>
				<td><b>-</b></td>
				<?php endif; ?>
				
				<?php 
					$ch =  0;
					$th =  0;
				?>
				<?php $hasUH = false; ?>
				<?php $hasUHValue = false; ?>
				<?php foreach($jenis_nilai as $jn): ?>
				<?php if (strpos(strtolower($jn['des_jenis_nilai']), 'ulangan') !== false || strpos(strtolower($jn['des_jenis_nilai']), 'uh') !== false):?>
				<?php $hasUH = true; ?>
				<?php $count ++; ?>
				<?php $nn = "<b>-</b>"; ?>
				<?php foreach($jenis as $k => $j): ?>
					<?php if($j == $jn['des_jenis_nilai']): ?>
						<?php $nn =  $n[$k];$ch++;$th+=$n[$k];?>
						<?php $hasUHValue = true; ?>
					<?php endif; ?>
				<?php endforeach ?>
				<?php if($nn < $dt['kkm']) :?>
				<td><span style='color:red'><?php echo $nn;?></span></td>
				<?php else: ?>
				<td><?php echo $nn;?></td>
				<?php endif ?>
				<?php endif; ?>
				<?php endforeach ?>
				
				
				<?php if($hasUH && $hasUHValue): ?>
				<?php $count ++; ?>
				<td><?php echo ceil($th/$ch)?></td>
				<?php endif; ?>
				
				<?php if($hasUH && !$hasUHValue): ?>
				<?php $count ++; ?>
				<td><b>-</b></td>
				<?php endif; ?>
				
				<?php 
					$co =  0;
					$to =  0;
				?>
				<?php foreach($jenis_nilai as $jn): ?>
				<?php if(strpos(strtolower($jn['des_jenis_nilai']), 'tugas') === false && strpos(strtolower($jn['des_jenis_nilai']), 'tgs') === false && strpos(strtolower($jn['des_jenis_nilai']), 'uh') === false&& strpos(strtolower($jn['des_jenis_nilai']), 'ulangan') === false): ?>
				<?php $count ++; ?>
				<?php $nn = "<b>-</b>"; ?>
				<?php foreach($jenis as $k => $j): ?>
					<?php if($j == $jn['des_jenis_nilai']): ?>
						<?php  $nn = $n[$k];$co++;$to +=$n[$k];?>
					<?php endif; ?>
				<?php endforeach ?>
				<?php if($nn < $dt['kkm']) :?>
				<td><span style='color:red'><?php echo $nn;?></span></td>
				<?php else: ?>
				<td><?php echo $nn;?></td>
				<?php endif ?>
				<?php endif; ?>
				<?php endforeach ?>
				
				<?php if($count > 3): ?>
				<td><?php echo ceil((($tt+$th+$to)/($ct+$ch+$co)));?></td>
				<?php else: ?>
				<td><b>-</b></td>
				<?php endif; ?>
			  </tr>
			<?php endforeach ?>
			</tbody>
		</table>
		<div class="form-group">
				<div style="margin-left:15px;float:left;"><b>1</b> = No,</div>
				<div style="margin-left:15px;float:left;"><b>2</b> = Mata Pelajaran,</div>
				<div style="margin-left:15px;float:left;"><b>3</b> = KKM,</div>
				<?php $count = 3; ?>
				<?php $hasTugas = false; ?>
				<?php foreach($jenis_nilai as $jn): ?>
				<?php if (strpos(strtolower($jn['des_jenis_nilai']), 'tugas') !== false || strpos(strtolower($jn['des_jenis_nilai']), 'tgs') !== false):?>
				<?php $hasTugas = true; ?>
				<?php $count ++; ?>
				<div style="margin-left:15px;float:left;"><?php echo "<b>".$count."</b> = ". $jn['des_jenis_nilai'];?>,</div>
				<?php endif; ?>
				<?php endforeach ?>
				<?php if($hasTugas): ?>
				<?php $count ++; ?>
				<div style="margin-left:15px;float:left;"><?php echo "<b>".$count."</b>"?> = Rata Rata Tugas,</div>
				<?php endif; ?>
			
				<?php $hasUH = false; ?>
				<?php foreach($jenis_nilai as $jn): ?>
				<?php if (strpos(strtolower($jn['des_jenis_nilai']), 'ulangan') !== false || strpos(strtolower($jn['des_jenis_nilai']), 'uh') !== false):?>
				<?php $hasUH = true; ?>
				<?php $count ++; ?>
				<div style="margin-left:15px;float:left;"><?php echo "<b>".$count."</b> = ". $jn['des_jenis_nilai'];?>,</div>
				<?php endif; ?>
				<?php endforeach ?>
				<?php if($hasUH): ?>
				<?php $count ++; ?>
				<div style="margin-left:15px;float:left;"><?php echo "<b>".$count."</b>"?> = Rata Rata Ulangan Harian,</div>
				<?php endif; ?>
				
				<?php foreach($jenis_nilai as $jn): ?>
				<?php if(strpos(strtolower($jn['des_jenis_nilai']), 'tugas') === false && strpos(strtolower($jn['des_jenis_nilai']), 'tgs') === false && strpos(strtolower($jn['des_jenis_nilai']), 'uh') === false&& strpos(strtolower($jn['des_jenis_nilai']), 'ulangan') === false): ?>
				<?php $count ++; ?>
				<div style="margin-left:15px;float:left;"><?php echo "<b>".$count."</b> = ". $jn['des_jenis_nilai'];?>,</div>
				<?php endif; ?>
				<?php endforeach ?>
				
				<?php $count ++; ?>
				<div style="margin-left:15px;float:left;"><?php echo "<b>".$count."</b>"?> = Rata rata Nilai</div>
		</div>
		</div>
	</div>
	</div>
</div>
