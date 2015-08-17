<div class="row" style="padding:1% 0">
    <div class="col-md-12">
        <div class="pull-right">
            <a href="<?php echo site_url('site/viewbilling'); ?>" class="btn btn-primary pull-right"><i class="icon-long-arrow-left"></i>&nbsp;Back</a>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-12">
        <section class="panel">
            <header class="panel-heading">
                billing Details
            </header>
            <div class="panel-body">
                <form class="form-horizontal tasi-form" method="post" action="<?php echo site_url('site/createbillingsubmit');?>" enctype="multipart/form-data">
                    <div class=" form-group">
                        <label class="col-sm-2 control-label">listing</label>
                        <div class="col-sm-4">
                           <input type="hidden" id="e6" style="width: 300px" onchange="calllisting()" name="listing"/>
                            <?php
//echo form_dropdown( 'listing',$listing,set_value( 'listing'), 'class="chzn-select form-control" 	data-placeholder="Choose a Accesslevel..."'); 
                            ?>
                        </div>
                    </div>
                    <div class=" form-group">
                        <label class="col-sm-2 control-label">User</label>
                        <div class="col-sm-4">
                            <?php echo form_dropdown( 'user',$user,set_value( 'user'), 'class="chzn-select form-control" 	data-placeholder="Choose a Accesslevel..."'); ?>
                        </div>
                    </div>
                    <div class=" form-group">
                        <label class="col-sm-2 control-label">Payment Type</label>
                        <div class="col-sm-4">
                            <?php echo form_dropdown( 'paymenttype',$paymenttype,set_value( 'paymenttype'), 'class="chzn-select form-control" 	data-placeholder="Choose a Accesslevel..."'); ?>
                        </div>
                    </div>
                    <div class=" form-group">
                        <label class="col-sm-2 control-label" for="normal-field">Amount</label>
                        <div class="col-sm-4">
                            <input type="text" id="normal-field" class="form-control" name="amount" value="<?php echo set_value('amount');?>">
                        </div>
                    </div>

                    <div class=" form-group" style="display:none;">
                        <label class="col-sm-2 control-label">Period</label>
                        <div class="col-sm-4">
                            <?php echo form_dropdown( 'period',$period,set_value( 'period'), 'class="chzn-select form-control" 	data-placeholder="Choose a Accesslevel..."'); ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Start Date</label>
                        <div class="col-sm-4">
                            <input type="date" id="startdate" name="startdate" class="form-control" value="<?php echo set_value('startdate'); ?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">End Date</label>
                        <div class="col-sm-4">
                            <input type="date" id="enddate" name="enddate" class="form-control" value="<?php echo set_value('enddate'); ?>">
                        </div>
                    </div>
                    <div class=" form-group">
                        <label class="col-sm-2 control-label" for="normal-field">credits</label>
                        <div class="col-sm-4">
                            <input type="text" id="normal-field" class="form-control" name="credits" value="<?php echo set_value('credits');?>">
                        </div>
                    </div>

                    <div class=" form-group">
                        <label class="col-sm-2 control-label">Payed To</label>
                        <div class="col-sm-4">
                            <input id="selectbox-o" class="input-xlarge" name="optionvalue" type="hidden" data-placeholder="Choose An Option.." />
                            <?php echo form_dropdown( 'payedto',$user,set_value( 'payedto'), 'id="select2"class="chzn-select form-control" 	data-placeholder="Choose a User..."'); ?>
                        </div>
                    </div>
                    
                    <div class=" form-group">
                        <label class="col-sm-2 control-label">&nbsp;</label>
                        <div class="col-sm-4">
                            <button type="submit" class="btn btn-primary">Save</button>
                            <a href="<?php echo site_url('site/viewbilling'); ?>" class="btn btn-secondary">Cancel</a>
                        </div>
                    </div>
                </form>
            </div>
        </section>
    </div>
</div>
<script lang="javascript" type="text/javascript">
    $(document).ready(function () {
        calllisting();
    });

    function calllisting() {

        $("#e6").select2({
            multiple: false,
            placeholder: "Search for a subject",
            minimumInputLength: 1,
            ajax: { // instead of writing the function to execute the request we use Select2's 
                // convenient helper  
                url: "<?php echo base_url(); ?>index.php/site/getlistingdropdownbyname",
                dataType: 'json',
                data: function (term, page) {
                    return {
                        name: term
                    };
                },
                results: function (data, page) { // parse the results into the format 
                    // expected by Select2.            
                    // since we are using custom formatting functions 
                    // we do not need to alter remote JSON data  
                    console.log(data);
                    return {
                        results: data
                    };
                }
            },
            formatResult: subjectFormatResult, // omitted for brevity, see the source of this page  
            formatSelection: subjectFormatSelection, // omitted for brevity, see the source of this page  
            //dropdownCssClass: "bigdrop", // apply css that makes the dropdown taller  
            // we do not want to escape markup since we are displaying html in results  
        });

    }

    function subjectFormatResult(subject) {
        return "<span>" + subject.name + " - " + subject.id + "</span>";

    }

    function subjectFormatSelection(subject) {
        console.log(subject.name);
        setTimeout(function () {
            $("#s2id_e6 .select2-chosen").text(subject.name + " - " + subject.id);
        }, 100)
        
       return "<span>" + subject.name + " - " + subject.id + "</span>";  
    }





    //$(document).ready(function(){
    //   $('#selectbox-o').select2({
    //    minimumInputLength: 2,
    //    ajax: {
    //      url: "<?php echo base_url(); ?>index.php/site/getlistingdropdownbyname?name=",
    //      dataType: 'json',
    //      data: function (term, page) {
    //        return {
    //          q: term
    //        };
    //      },
    //      results: function (data, page) {
    //        return { results: data };
    //      }
    //    }
    //  });
    //});
</script>