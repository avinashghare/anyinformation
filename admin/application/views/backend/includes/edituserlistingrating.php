	    <section class="panel">
		    <header class="panel-heading">
				 User Rating Details
			</header>
			<div class="panel-body">
			  <form class="form-horizontal tasi-form" method="post" action="<?php echo site_url('site/edituserlistingratingsubmit');?>" enctype= "multipart/form-data">
				<input type="text" id="normal-field" class="form-control" name="id" value="<?php echo set_value('id',$beforeuserlistingrating->id);?>" style="display:none;">
				
				<div class="form-group">
				  <label class="col-sm-2 control-label" for="normal-field">rating</label>
				  <div class="col-sm-4">
					<input type="text" id="normal-field" class="form-control" name="rating" value="<?php echo set_value('rating',$beforeuserlistingrating->rating);?>">
				  </div>
				</div>
				
				<div class=" form-group">
				  <label class="col-sm-2 control-label">user</label>
				  <div class="col-sm-4">
					<?php
						
						echo form_dropdown('user',$user,set_value('user',$beforeuserlistingrating->user),'class="chzn-select form-control" 	data-placeholder="Choose a Accesslevel..."');
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
				  <a href="<?php echo site_url('site/viewuserlistingrating?id=').$beforeuserlistingrating->user; ?>" class="btn btn-secondary">Cancel</a>
				</div>
				</div>
			  </form>
			</div>
		</section>
