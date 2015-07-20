<!--
<div class="row" style="padding:1% 0">
	<div class="col-md-12">
		<div class="pull-right">
			<a href="<?php echo site_url('site/viewlisting'); ?>" class="btn btn-primary pull-right"><i class="icon-long-arrow-left"></i>&nbsp;Back</a>
		</div>
	</div>
</div>
-->
<div class="row">
	<div class="col-lg-12">
	    <section class="panel">
		    <header class="panel-heading">
				 listing Details
			</header>
			<?php
//echo $listing;
            ?>
			<div class="panel-body">
			  <form class="form-horizontal tasi-form" method="post" action="<?php echo site_url('site/createuserlistingratingsubmit');?>" enctype= "multipart/form-data">
				<div class="form-group">
				  <label class="col-sm-2 control-label" for="normal-field">Rating</label>
				  <div class="col-sm-4">
					<input type="numeric" id="normal-field" class="form-control" name="rating" value="<?php echo set_value('rating');?>">
				  </div>
				</div>
				<div class=" form-group">
				  <label class="col-sm-2 control-label">user</label>
				  <div class="col-sm-4">
					<?php
						
						echo form_dropdown('user',$user,set_value('user'),'class="chzn-select form-control" 	data-placeholder="Choose a Accesslevel..."');
					?>
				  </div>
				</div>
				<div class="form-group" style="display:none;">
				  <label class="col-sm-2 control-label" for="normal-field">Listingid</label>
				  <div class="col-sm-4">
					<input type="text" id="normal-field" class="form-control" name="listing" value="<?php echo $listing;?>">
				  </div>
				</div>
				<div class=" form-group">
				  <label class="col-sm-2 control-label">&nbsp;</label>
				  <div class="col-sm-4">
				  <button type="submit" class="btn btn-primary">Save</button>
				  <a href="<?php echo site_url('site/viewuserlistingrating?id=').$this->input->get('id'); ?>" class="btn btn-secondary">Cancel</a>
				</div>
				</div>
			  </form>
			</div>
		</section>
	</div>
</div>