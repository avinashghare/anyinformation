<section class="panel">
    <header class="panel-heading">
        Billing Details
    </header>
    <div class="panel-body">
        <form class="form-horizontal tasi-form" method="post" action="<?php echo site_url('site/editbillingsubmit');?>" enctype="multipart/form-data">
            <input type="text" id="normal-field" class="form-control" name="id" value="<?php echo set_value('id',$before->id);?>" style="display:none;">

            <div class=" form-group">
                <label class="col-sm-2 control-label">listing</label>
                <div class="col-sm-4">
                    <input type="hidden" id="e6" style="width: 300px" name="listing" value="<?php echo $before->listing; ?>" />
                    <?php // echo form_dropdown( 'listing',$listing,set_value( 'listing',$before->listing),'class="chzn-select form-control" data-placeholder="Choose a Accesslevel..."'); ?>
                </div>
            </div>
            <div class=" form-group">
                <label class="col-sm-2 control-label">User</label>
                <div class="col-sm-4">
                    <?php echo form_dropdown( 'user',$user,set_value( 'user',$before->user),'class="chzn-select form-control" data-placeholder="Choose a Accesslevel..."'); ?>
                </div>
            </div>
            <div class=" form-group">
                <label class="col-sm-2 control-label">Payment Type</label>
                <div class="col-sm-4">
                    <?php echo form_dropdown( 'paymenttype',$paymenttype,set_value( 'paymenttype',$before->paymenttype),'class="chzn-select form-control" data-placeholder="Choose a Accesslevel..."'); ?>
                </div>
            </div>
            <div class=" form-group">
                <label class="col-sm-2 control-label" for="normal-field">Amount</label>
                <div class="col-sm-4">
                    <input type="text" id="normal-field" class="form-control" name="amount" value="<?php echo set_value('amount',$before->amount);?>">
                </div>
            </div>

            <div class=" form-group">
                <label class="col-sm-2 control-label">Period</label>
                <div class="col-sm-4">
                    <?php echo form_dropdown( 'period',$period,set_value( 'period',$before->period),'class="chzn-select form-control" data-placeholder="Choose a Accesslevel..."'); ?>
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-2 control-label">Start Date</label>
                <div class="col-sm-4">
                    <input type="date" id="startdate" name="startdate" class="form-control" value="<?php echo set_value('startdate',$before->startdate); ?>">
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label">End Date</label>
                <div class="col-sm-4">
                    <input type="date" id="enddate" name="enddate" class="form-control" value="<?php echo set_value('enddate',$before->enddate); ?>">
                </div>
            </div>
            <div class=" form-group">
                <label class="col-sm-2 control-label" for="normal-field">credits</label>
                <div class="col-sm-4">
                    <input type="text" id="normal-field" class="form-control" name="credits" value="<?php echo set_value('credits',$before->credits);?>">
                </div>
            </div>

            <div class=" form-group">
                <label class="col-sm-2 control-label">Payed To</label>
                <div class="col-sm-4">
                    <?php echo form_dropdown( 'payedto',$user,set_value( 'payedto',$before->payedto),'id="select2"class="chzn-select form-control" data-placeholder="Choose a User..."'); ?>
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
<script lang="javascript" type="text/javascript">
//        $(document).ready(function () {
//            calllistingbyid( <? php echo $before - > listing; ?> );
//        });
    var inivalue = '<?php echo $before->listing; ?>';
    var ininame = '<?php echo $listing->name; ?>';

    function calllisting() {

        $("#e6").select2({
            multiple: false,
            placeholder: "Search for a subject",
            minimumInputLength: 1,
            initSelection: function (element, callback) {



                callback({
                    id: inivalue,
                    name: ininame
                });
            },
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
        }).on("select2-loaded", function () {
            console.log("Data");
        });
        setTimeout(function () {
            $("#e6").select2("val", inivalue);
        }, 100)



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



    $(document).ready(function () {
        calllisting();
    });
</script>