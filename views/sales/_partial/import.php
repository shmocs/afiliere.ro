
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
				
				
				<div class="box-footer">
					
					<div class="output"></div>
					
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

