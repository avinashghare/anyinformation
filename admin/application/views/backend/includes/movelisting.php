<!--
<div class="row" style="padding:1% 0">
	<div class="col-md-12">
		<div class="pull-right">
			<a href="<?php echo site_url('site/viewenquiry'); ?>" class="btn btn-primary pull-right"><i class="icon-long-arrow-left"></i>&nbsp;Back</a>
		</div>
	</div>
</div>
-->
<div class="row">
	<div class="col-lg-12">
	    <section class="panel">
		    <header class="panel-heading">
				 Move Listing From One Category to Another
			</header>
			<div class="panel-body">
			  <form class="form-horizontal tasi-form" method="post" action="<?php echo site_url('site/movelistingsubmit');?>" enctype= "multipart/form-data">
			  
				<div class=" form-group" id="categoryidfrom">
				  <label class="col-sm-2 control-label">From Category</label>
				  <div class="col-sm-4">
					<?php
						
						echo form_dropdown('fromcategory',$category,set_value('fromcategory'),'class="chzn-select form-control" id="select1" data-placeholder="Choose a Category..."');
					?>
				  </div>
				</div>
				<div class=" form-group" id="categoryidto">
				  <label class="col-sm-2 control-label">To Category</label>
				  <div class="col-sm-4">
					<?php
						
						echo form_dropdown('tocategory',$category,set_value('tocategory'),'class="chzn-select form-control" id="select2" data-placeholder="Choose a Category..."');
					?>
				  </div>
				</div>
				<div class=" form-group">
				  <label class="col-sm-2 control-label">&nbsp;</label>
				  <div class="col-sm-4">
				  <button type="submit" class="btn btn-primary">Submit</button>
<!--				  <a href="<?php echo site_url('site/viewenquiry'); ?>" class="btn btn-secondary">Cancel</a>-->
				</div>
				</div>
			  </form>
			</div>
		</section>
	</div>
</div>
