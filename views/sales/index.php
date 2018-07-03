<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;

//$this->registerJs('/js/sales.js'); => <script type="text/javascript">jQuery(function ($) {/js/sales.js});</script>

?>
<div class="content-wrapper2">
	<!-- Content Header (Page header) -->
	<section class="content-header">
		<h1>
			Data Tables
			<small>advanced tables</small>
		</h1>
		<ol class="breadcrumb">
			<li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
			<li><a href="#">Tables</a></li>
			<li class="active">Data tables</li>
		</ol>
	</section>
	
	<!-- Main content -->
	<section class="content">
		<div class="row">
			<div class="col-xs-12">
				
				<div class="box">
					<div class="box-header">
						<h3 class="box-title">Data Table With Full Features</h3>
					</div>
					<!-- /.box-header -->
					<div class="box-body">
						<table id="example1" class="table table-bordered table-striped table-hover">
							<thead>
							<tr>
								<th style="width: 20px;">ID</th>
								<th style="width: 50px;">Platforma</th>
								<th style="width: 200px;">Advertiser</th>
								<th style="width: 120px;">Data Click</th>
								<th style="width: 120px;">Data Conversie</th>
								<th style="width: 60px; text-align: right;">Comision</th>
								<th>Refferer</th>
								<th style="width: 100px;">Status</th>
							</tr>
							</thead>
							
							<tbody>
								<?php
								foreach ($dataProvider->models as $row) {
									?>
									<tr>
										<td><?=$row->id;?></td>
										<td><?=$row->platform;?></td>
										<td><?=$row->advertiser;?></td>
										<td><?=$row->click_date;?></td>
										<td><?=$row->conversion_date;?></td>
										<td style="text-align: right;"><?=$row->amount;?></td>
										<td><div style="overflow-x: scroll; width: 600px; white-space: nowrap;"><?=$row->referrer;?></div></td>
										<td style="text-align: center;"><?=$row->status;?></td>
									</tr>
									<?php
								}
								?>
							</tbody>
							
							<tfoot>
							<tr>
								<th>Advertiser</th>
								<th>Data Click</th>
								<th>Data Conversie</th>
								<th>Comisionului</th>
								<th>Refferer</th>
								<th>Status</th>
							</tr>
							</tfoot>
						</table>
					</div>
					<!-- /.box-body -->
				</div>
				<!-- /.box -->
			</div>
			<!-- /.col -->
		</div>
		<!-- /.row -->
	</section>
	<!-- /.content -->
</div>

