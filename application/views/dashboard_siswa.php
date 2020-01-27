<script src="<?php echo base_url('assets/js/Chart.min.js')?>"></script>
<div id="body-container">
	<div class="container">
		<div class="row">
			<div class="col-md-10">
				<div class="col-md-4 pull-right">
					<div class="page-header">
					  <h4>Welcome <?php echo $nama?><small> <?php echo $nis?></small></h4>
					</div>
				</div>
			</div>
			<div class="col-md-10">
				<canvas id="myChart" width="900" height="400"></canvas>
			</div>
		</div>
	</div>
</div>
<script>
	var ctx = document.getElementById("myChart").getContext("2d");
	var siswa = <?php echo json_encode($data);?>;
	
	var kelas = [];
	var nilai = [];
	
	$.each(siswa,function(i,item){
		kelas[i] = item.kelas;
		nilai[i] = item.nilai*1;
	});
	
	var data = {
    labels: kelas,
    datasets: [
				{
					label: "Nilai",
					fillColor: "rgba(151,187,205,0.2)",
					strokeColor: "rgba(151,187,205,1)",
					pointColor: "rgba(151,187,205,1)",
					pointStrokeColor: "#fff",
					pointHighlightFill: "#fff",
					pointHighlightStroke: "rgba(151,187,205,1)",
					data: nilai
				}
				]
			};

	var myLineChart = new Chart(ctx).Line(data,{scaleOverride: true, scaleStartValue: 0, scaleStepWidth: 10, scaleSteps: 10,showDatasetLabels : true,multiTooltipTemplate: "<%= datasetLabel %> - <%= value %>"});
	
</script>