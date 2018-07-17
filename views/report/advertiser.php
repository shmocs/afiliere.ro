<?php


require Yii::getAlias('@app') . '/assets/ReportsAsset.php';
app\assets\ReportsAsset::register($this);

use yii\helpers\Html;
use yii\helpers\Url;
use kartik\daterange\DateRangePicker;

use yii\helpers\VarDumper;
use kartik\grid\GridView;
use kartik\export\ExportMenu;
use app\models\Sale;
use app\models\Import;
use yii\helpers\ArrayHelper;

//VarDumper::dump($_SERVER, 10, true);
//VarDumper::dump(\Yii::getAlias('@webroot'), 10, true);
//VarDumper::dump($dataProvider->models, 10, true);

?>


<div class="content-wrapper2">
	
	<!-- Main content -->
	<section class="content">
		<div class="row">
			<div class="col-xs-12">
				
				<div class="box">
					<div class="box-header">
						<h3 class="box-title pull-left"><i class="fa fa-th-list"></i> Advertiser report</h3>
						
						<div class="pull-left col-md-2">
							<select name="advertiser" id="advertiser">
								<option value="click_date" <?php if ($advertiser == 'adv1') echo 'selected="selected"';?>>adv1</option>
								<option value="conversion_date" <?php if ($advertiser == 'adv2') echo 'selected="selected"';?>>adv2</option>
							</select>
						</div>
						
						<div class="pull-right col-md-4 col-sx-12">
							
							<div class="drp-container col-md-9 col-xs-12">
								<?php
								echo DateRangePicker::widget([
									'name'=>'date_range',
									'value' => $date_range,
									'presetDropdown'=>true,
									'hideInput'=>true,
									
									'pluginOptions' => [
										'locale' => [
											'cancelLabel' => 'Clear',
											'format' => 'YYYY-MM-DD',
										]
									],
								
								]);
								?>
							</div>
							<div class="col-md-3 col-xs-12">
								
								<a href="#" class="btn btn-primary filter_date_range">Submit</a>
							</div>
						
						</div>
					</div>
					<!-- /.box-header -->
					<div class="box-body">
						<img src="/images/demo.png" width="1024" alt="">
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


