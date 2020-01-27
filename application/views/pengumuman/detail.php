<div id="body-container">
	<div class="container">
		<div class="row">
			<div class="col-sm-12">
				 <div class="news">
					<div class="page-header">
						<h4><?php echo $data->judul; ?></h4>
						<div class="date"><?php echo date('d/m/Y h:i:s',strtotime($data->tgl_input));?></div>
					</div>
					<div class="body">
						<p><?php echo $data->isi;?></p>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
