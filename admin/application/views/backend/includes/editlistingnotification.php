<div class="row">
	<div class="col-lg-12">
	    <section class="panel">
		    <header class="panel-heading">
				 Listing Notification Details
			</header>
			
			<div class="panel-body">
				<form class="form-horizontal row-fluid" method="post" action="<?php echo site_url('site/editlistingnotificationsubmit');?>" enctype= "multipart/form-data">
					<div class="form-row control-group row-fluid" style="display:none;">
						<label class="control-label span3" for="normal-field">ID</label>
						<div class="controls span9">
						  <input type="text" id="normal-field" class="row-fluid" name="id" value="<?php echo $before->id;?>">
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label">Listing</label>
						<div class="col-sm-4">
<!--						  <input type="text" id="normal-field" class="form-control" name="name" value="<?php echo set_value('name',$before->name);?>">-->
                    <?php echo $before->name; ?>
						</div>
					</div>	
					     
<!--
				<div class=" form-group">
				  <label class="col-sm-2 control-label" for="normal-field">Banner</label>
				  <div class="col-sm-4">
					<input type="file" id="normal-field" class="form-control" name="banner" value="<?php echo set_value('banner',$before->banner);?>">
					<?php if($before->banner == "")
						 { }
						 else
						 { ?>
							<img src="<?php echo base_url('uploads')."/".$before->banner; ?>" width="140px" height="140px">
						<?php }
					?>
				  </div>
				</div>
-->

                
<!--
					<div class=" form-group">
					  <label class="col-sm-2 control-label">Listing</label>
					  <div class="col-sm-4">
						<?php
							
							echo form_dropdown('listing',$listing,set_value('listing',$before->listing),'id="select2" class="chzn-select form-control" 	data-placeholder="Choose Listing..."');
						?>
					  </div>
					</div>
-->
				<div class=" form-group">
				  <label class="col-sm-2 control-label" for="normal-field">Pointer Start Date</label>
				  <div class="col-sm-4">
					<input type="date" id="d" class="form-control" name="pointerstartdate" value="<?php echo set_value('pointerstartdate',$before->pointerstartdate);?>">
				  </div>
				</div>
				<div class=" form-group">
				  <label class="col-sm-2 control-label" for="normal-field">Pointer End Date</label>
				  <div class="col-sm-4">
					<input type="date" id="d2" class="form-control" name="pointerenddate" value="<?php echo set_value('pointerenddate',$before->pointerenddate);?>">
				  </div>
				</div>
				<div class=" form-group">
				  <label class="col-sm-2 control-label" for="normal-field">Pointers</label>
				  <div class="col-sm-4">
					<input type="text" id="d2" class="form-control" name="pointer" value="<?php echo set_value('pointer',$before->pointer);?>">
				  </div>
				</div>
					<div class="form-group">
						<label class="col-sm-2 control-label">&nbsp;</label>
						<div class="col-sm-4">	
							<button type="submit" class="btn btn-info">Submit</button>
						</div>
					</div>
				</form>
			</div>
		</section>
    </div>
</div>
