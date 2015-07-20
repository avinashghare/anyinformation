	    <section class="panel">
		    <header class="panel-heading">
				 listing Details
			</header>
			<div class="panel-body">
			  <form class="form-horizontal tasi-form" method="post" action="<?php echo site_url('site/editlistingsubmit');?>" enctype= "multipart/form-data">
				<input type="text" id="normal-field" class="form-control" name="id" value="<?php echo set_value('id',$before->id);?>" style="display:none;">
				
				<div class="form-group">
				  <label class="col-sm-2 control-label" for="normal-field">Name</label>
				  <div class="col-sm-8">
					<input type="text" id="normal-field" class="form-control" name="name" value="<?php echo set_value('name',$before->name);?>">
				  </div>
				</div>
				
				<div class=" form-group">
				  <label class="col-sm-2 control-label">User</label>
				  <div class="col-sm-8">
					<?php
						
						echo form_dropdown('user',$user,set_value('user',$before->user),'class="chzn-select form-control" 	data-placeholder="Choose a Accesslevel..."');
					?>
				  </div>
				</div>
				
				<div class=" form-group">
				  <label class="col-sm-2 control-label">Category</label>
				  <div class="col-sm-8">
					<?php
						echo form_multiselect('category[]', $category,$selectedcategory,'id="select2" class="form-control"');
					?>
				  </div>
				</div>
				
				<div class=" form-group">
				  <label class="col-sm-2 control-label">modeofpayment</label>
				  <div class="col-sm-8">
					<?php
						echo form_multiselect('modeofpayment[]', $modeofpayment,$selectedmodeofpayment,'id="select3" class="form-control"');
					?>
				  </div>
				</div>
				<div class=" form-group">
				  <label class="col-sm-2 control-label">daysofoperation</label>
				  <div class="col-sm-8">
					<?php
						echo form_multiselect('daysofoperation[]', $daysofoperation,$selecteddaysofoperation,'id="select4" class="form-control"');
					?>
				  </div>
				</div>
				<div class=" form-group">
				  <label class="col-sm-2 control-label" for="normal-field">Address</label>
				  <div class="col-sm-8">
					<input type="text" id="normal-field" class="form-control addressclass" name="address" value="<?php echo set_value('address',$before->address);?>">
				  </div>
				</div>
				
<!--
				<div class=" form-group">
				  <label class="col-sm-2 control-label">city</label>
				  <div class="col-sm-8">
					<?php
						
						echo form_dropdown('city',$city,set_value('city',$before->city),'class="chzn-select form-control cityclass" 	data-placeholder="Choose a Accesslevel..."');
					?>
				  </div>
				</div>
-->
				
				<div class=" form-group">
				  <label class="col-sm-2 control-label">City</label>
				  <div class="col-sm-4">
					<?php 	 echo form_dropdown('city',$city,set_value('city',$before->city),'id="cityid" class="chzn-select form-control" 	data-placeholder="Choose a City..."  onchange="changearea()"');
					?>
				  </div>
				</div>
				
				<div class="form-group" id="onareaselect">
						<label class="col-sm-2 control-label">Area</label>
						<div class="col-sm-4 areaselect">
						
					<?php 	 echo form_dropdown('area',$area,set_value('area',$before->area),'id="areaid" class="chzn-select form-control" 	data-placeholder="Choose a area..."');
					?>
<!--
                       <select name="area" class="chzn-select form-control">
						   
						   </select>
-->
						</div>
				</div>
            
				<div class="form-group" style="display:none;">
					<label class="col-sm-2 control-label">Areaold</label>
					<div class="col-sm-8">
					  <input type="text" id="areaold" name="areaold" class="form-control" value="<?php echo set_value('areaold',$before->area); ?>">
					</div>
				</div>
				<div class=" form-group">
				  <label class="col-sm-2 control-label" for="normal-field">state</label>
				  <div class="col-sm-8">
					<input type="text" id="normal-field" class="form-control stateclass" name="state" value="<?php echo set_value('state',$before->state);?>">
				  </div>
				</div>
				
				<div class=" form-group">
				  <label class="col-sm-2 control-label" for="normal-field">country</label>
				  <div class="col-sm-8">
					<input type="text" id="normal-field" class="form-control countryclass" name="country" value="<?php echo set_value('country',$before->country);?>">
				  </div>
				  <div class="col-sm-2">
				    <a class="btn btn-primary pull-right latlongbutton">Add Lat/Long</a>
				  </div>
				</div>
				
				<div class=" form-group">
				  <label class="col-sm-2 control-label" for="normal-field">Latitude</label>
				  <div class="col-sm-8">
					<input type="text" id="normal-field" class="form-control latitudeclass" name="lat" value="<?php echo set_value('lat',$before->lat);?>">
				  </div>
				</div>
				<div class=" form-group">
				  <label class="col-sm-2 control-label" for="normal-field">Longitude</label>
				  <div class="col-sm-8">
					<input type="text" id="normal-field" class="form-control longitudeclass" name="long" value="<?php echo set_value('long',$before->long);?>">
				  </div>
				</div>
				
				<div class=" form-group">
				  <label class="col-sm-2 control-label" for="normal-field">Pincode</label>
				  <div class="col-sm-8">
					<input type="text" id="normal-field" class="form-control" name="pincode" value="<?php echo set_value('pincode',$before->pincode);?>">
				  </div>
				</div>
				
                    <div class="form-group" >
                        <label class="col-sm-2 control-label" for="normal-field">Description</label>
                        <div class="col-sm-4">
                            <textarea name="description" id="" cols="20" rows="10" class="form-control tinymce fielddescriptioninput"><?php echo set_value(' description ',$before->description);?></textarea>

                        </div>
                    </div>
<!--
				<div class=" form-group">
				  <label class="col-sm-2 control-label" for="normal-field">Description</label>
				  <div class="col-sm-8">
					<input type="text" id="normal-field" class="form-control" name="description" value="<?php echo set_value('description',$before->description);?>">
				  </div>
				</div>
-->
				
				<div class=" form-group">
<!--				  <span>Please use "/" to seperate two numbers</span>-->
				  <label class="col-sm-2 control-label" for="normal-field">Contact</label>
				  <div class="col-sm-8">
					<input type="text" id="normal-field" class="form-control" name="contact" value="<?php echo set_value('contact',$before->contactno);?>">
				  </div>
				</div>
				
<!--
				<div class=" form-group">
				  <label class="col-sm-2 control-label" for="normal-field">Mobile Number</label>
				  <div class="col-sm-8">
					<input type="text" id="normal-field" class="form-control" name="mobile" value="<?php echo set_value('mobile',$before->mobile);?>">
				  </div>
				</div>
-->
				
				<div class=" form-group">
				  <label class="col-sm-2 control-label" for="normal-field">Mobile Number</label>
				  <div class="col-sm-4">
					<input type="tel" id="normal-field" class="form-control mobileval" name="mobile" value="<?php echo set_value('mobile',$before->mobile);?>" onkeypress="return isNumberKey(event)">
<span id="left" style="color: #bebebe;"/>
				  </div>
				</div>
				<script>
                  $('.mobileval').limit('10','#left');
                    
                    function isNumberKey(evt)
       {
          var charCode = (evt.which) ? evt.which : evt.keyCode;
          if (charCode != 46 && charCode > 31 
            && (charCode < 48 || charCode > 57))
             return false;

          return true;
       }
                  </script>
				
				<div class=" form-group">
				  <label class="col-sm-2 control-label" for="normal-field">Email</label>
				  <div class="col-sm-8">
					<input type="email" id="normal-field" class="form-control" name="email" value="<?php echo set_value('email',$before->email);?>">
				  </div>
				</div>
				
				<div class=" form-group">
				  <label class="col-sm-2 control-label" for="normal-field">Website</label>
				  <div class="col-sm-8">
					<input type="text" id="normal-field" class="form-control" name="website" value="<?php echo set_value('website',$before->website);?>">
				  </div>
				</div>
				
				<div class="form-group">
					<label class="col-sm-2 control-label"> Facebook</label>
					<div class="col-sm-8">
					  <input type="text" id="" name="facebookuserid" class="form-control" value="<?php echo set_value('facebookuserid',$before->facebook); ?>">
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label">googleplus</label>
					<div class="col-sm-8">
					  <input type="text" id="" name="googleplus" class="form-control" value="<?php echo set_value('googleplus',$before->googleplus); ?>">
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label">twitter</label>
					<div class="col-sm-8">
					  <input type="text" id="" name="twitter" class="form-control" value="<?php echo set_value('twitter',$before->twitter); ?>">
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label">Year Of Establishment</label>
					<div class="col-sm-8">
					  <input type="text" id="" name="yearofestablishment" class="form-control" value="<?php echo set_value('yearofestablishment',$before->yearofestablishment); ?>">
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label">Time Of Operation Start</label>
					<div class="col-sm-8">
					  <input type="text" id="" name="timeofoperation_start" class="form-control" value="<?php echo set_value('timeofoperation_start',$before->timeofoperation_start); ?>">
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label">Time Of Operation End</label>
					<div class="col-sm-8">
					  <input type="text" id="" name="timeofoperation_end" class="form-control" value="<?php echo set_value('timeofoperation_end',$before->timeofoperation_end); ?>">
					</div>
				</div>
				
				<div class=" form-group">
				  <label class="col-sm-2 control-label">type</label>
				  <div class="col-sm-8">
					<?php
						
						echo form_dropdown('type',$type,set_value('type',$before->type),'class="chzn-select form-control" 	data-placeholder="Choose a Accesslevel..."');
					?>
				  </div>
				</div>
				
<!--
				<div class="form-group">
					<label class="col-sm-2 control-label">Credits</label>
					<div class="col-sm-8">
					  <input type="text" id="" name="credits" class="form-control" value="<?php echo set_value('credits',$before->credits); ?>">
					</div>
				</div>
-->
				<div class=" form-group">
				  <label class="col-sm-2 control-label">isverified</label>
				  <div class="col-sm-8">
					<?php
						
						echo form_dropdown('isverified',$isverified,set_value('isverified',$before->isverified),'class="chzn-select form-control" 	data-placeholder="Choose a Accesslevel..."');
					?>
				  </div>
				</div>
				
				<div class="form-group">
					<label class="col-sm-2 control-label">video</label>
					<div class="col-sm-8">
					  <input type="text" id="" name="video" class="form-control" value="<?php echo set_value('video',$before->video); ?>">
					</div>
				</div>
				
				<div class="form-group">
					<label class="col-sm-2 control-label">Pointer</label>
					<div class="col-sm-8">
					  <input type="text" id="" name="pointer" class="form-control" value="<?php echo set_value('pointer',$before->pointer); ?>">
					</div>
				</div>
				
				<div class="form-group">
					<label class="col-sm-2 control-label">Pointer Start Date</label>
					<div class="col-sm-4">
					  <input type="date" id="pointerstartdate" name="pointerstartdate" class="form-control" value="<?php echo set_value('pointerstartdate',$before->pointerstartdate); ?>">
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label">Pointer end Date</label>
					<div class="col-sm-4">
					  <input type="date" id="pointerenddate" name="pointerenddate" class="form-control" value="<?php echo set_value('pointerenddate',$before->pointerenddate); ?>">
					</div>
				</div>
				<div class=" form-group">
				  <label class="col-sm-2 control-label" for="normal-field">Logo</label>
				  <div class="col-sm-8">
					<input type="file" id="normal-field" class="form-control" name="logo" value="<?php echo set_value('logo',$before->logo);?>">
					<?php if($before->logo == "")
						 { }
						 else
						 { ?>
							<img src="<?php echo base_url('uploads')."/".$before->logo; ?>" width="140px" height="140px">
						<?php }
					?>
				  </div>
				</div>
				
				<div class=" form-group">
				  <label class="col-sm-2 control-label">status</label>
				  <div class="col-sm-8">
					<?php
						
						echo form_dropdown('status',$status,set_value('status',$before->status),'class="chzn-select form-control" 	data-placeholder="Choose a Accesslevel..."');
					?>
				  </div>
				</div>
				
				<div class=" form-group">
				  <label class="col-sm-2 control-label">&nbsp;</label>
				  <div class="col-sm-8">
				  <button type="submit" class="btn btn-primary">Save</button>
				  <a href="<?php echo site_url('site/viewlisting'); ?>" class="btn btn-secondary">Cancel</a>
				</div>
				</div>
			  </form>
			</div>
		</section>

<script>
    $(".latlongbutton").click(function () {
        console.log($( ".cityclass option:selected" ).text());
        $.getJSON(
            "https://maps.googleapis.com/maps/api/geocode/json?address="+$(".addressclass").val()+","+$( ".cityclass option:selected" ).text()+","+$(".stateclass").val()+","+$(".countryclass").val()+"&key=AIzaSyAvqKowJmLC_xd0N-8e6BoCZf4-gWThOZQ", {
                address: $(".addressclass").val()
            },
            function (data) {
                console.log(data.results[0]);
                console.log(data.results[0].geometry.location.lat);
                console.log(data.results[0].geometry.location.lng);
                $('.latitudeclass').val(data.results[0].geometry.location.lat);
                $('.longitudeclass').val(data.results[0].geometry.location.lng);
//                console.log(parsxed.results[0].geometry);
                nodata = data;
                // $("#store").html(data);
//                allenquiries(data);
//                userdetails(data);

            }
        );
//        return false;
    });
    
    
    function changearea() {
        console.log($('#cityid').val());
        $.getJSON(
            "<?php echo base_url(); ?>index.php/site/getareadropdown/" + $('#cityid').val(), {
                id: "123"
            },
            function (data) {
                console.log(data);
                nodata=data;
                changeareadropdown(data);

            }

        );
    }
                  var mallbycity=$(".storesforuser1 select").select2({allowClear: true,width:343});
                  
    function changeareadropdown(data) {
        $(".areaselect select").html("");
        for(var i=0;i<data.length;i++)
        {
//            console.log(data[i].id);
            $(".areaselect select").append("<option value='"+data[i].id+"'>"+data[i].name+"</option>");
            
        }
        

    };
    
</script>