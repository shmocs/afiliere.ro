
<div class="modal fade" id="modal-default" style="display: none;">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">×</span></button>
				<h4 class="modal-title">Sales Import</h4>
			</div>
			
			<div class="modal-body">
				
				<span class="btn btn-success fileinput-button">
			        <i class="glyphicon glyphicon-plus"></i>
			        <span>Add file...</span>
				    <!-- The file input field used as target for the file upload widget -->
			        <input id="fileupload" type="file" name="files[]" multiple>
			    </span>
				
				<br>
				<br>
				<!-- The global progress bar -->
				<div id="progress" class="progress progress-sm active">
					<div class="progress-bar progress-bar-success progress-bar-striped"></div>
				</div>
				<!-- The container for the uploaded files -->
				<div id="files" class="files"></div>
				
				
				<div class="box box-solid import-results">
					<div class="box-header with-border">
						<h3 class="box-title">Results</h3>
						
						<div class="box-tools pull-right">
							<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
							</button>
						</div>
						<!-- /.box-tools -->
					</div>
					<!-- /.box-header -->
					
					<div class="box-body result-content">

					</div>
					<!-- /.box-body -->
					
					<div class="box-footer no-padding">
						<ul class="nav nav-stacked">
							<li><a>Parsed <span class="pull-right badge bg-aqua" id="import_parsed">0</span></a></li>
							<li><a>Duplicates <span class="pull-right badge bg-orange" id="import_duplicates">0</span></a></li>
							<li><a>Imported <span class="pull-right badge bg-green" id="import_imported">0</span></a></li>
							<li><a>Failed <span class="pull-right badge bg-red" id="import_failed">0</span></a></li>
						</ul>
					</div>
					
					<!--Loading (remove the following to stop the loading)-->
					<div class="overlay">
						<i class="fa fa-refresh fa-spin"></i>
					</div>
					
				</div>
				
				
				<div class="box hide">
					
					<div class="box-header">
						<i class="fa fa-th-list"></i>
						
						<h3 class="box-title">Results</h3>
					</div>
					<!-- /.box-header -->
					
					<div class="box-body">
						
						<div class="alert hide">
							<div class="result-content"></div>
						</div>
						
					</div>
					<!-- /.box-body -->
					
					<!--Loading (remove the following to stop the loading)-->
					<div class="overlay">
						<i class="fa fa-refresh fa-spin"></i>
					</div>
					
				</div>
				
			</div>
			
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			</div>
		</div>
		<!-- /.modal-content -->
	</div>
	<!-- /.modal-dialog -->
</div>

