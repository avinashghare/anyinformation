<div class="row">
	<div class="col-lg-12">
	    <section class="panel">
		    <header class="panel-heading">
				 DELETE LISTING OF ONE CATEGORY
			</header>
			<div class="panel-body">
			  <form class="form-horizontal tasi-form" method="post" action="<?php echo site_url('site/deletelistingbycategorysubmit');?>" enctype= "multipart/form-data">
			  
				<div class=" form-group" id="categoryidfrom">
				  <label class="col-sm-2 control-label">From Category</label>
				  <div class="col-sm-4">
					<?php
						
						echo form_dropdown('fromcategory',$category,set_value('fromcategory'),'class="chzn-select form-control" id="select1" data-placeholder="Choose a Category..."');
					?>
				  </div>
				</div>
				<div class=" form-group">
				  <label class="col-sm-2 control-label">&nbsp;</label>
				  <div class="col-sm-4">
				  <button type="submit" class="btn btn-primary">Save</button>
				</div>
				</div>
			  </form>
			</div>
		</section>
	</div>
</div>
