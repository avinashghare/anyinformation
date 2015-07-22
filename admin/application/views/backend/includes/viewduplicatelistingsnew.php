<div class=" row" style="padding:1% 0;">
	<div class="col-md-12">
	
		<div class=" pull-right col-md-1 createbtn" ><a class="btn btn-primary" href="<?php echo site_url('site/exportduplicatelistingcsv'); ?>"target="_blank"><i class="icon-plus"></i>Export to CSV </a></div> 
	</div>
	
</div>
<div class="row">
	<div class="col-lg-12">
		<section class="panel">
			<header class="panel-heading">
                Video Details
            </header>
			<table class="table table-striped table-hover" cellpadding="0" cellspacing="0" width="100%">
			<thead>
				<tr>
					<th>Id</th>
					<th>name</th>
					<th>email</th>
					<td>Address</td>
					<td>contact</td>
					<td>Pointer</td>
<!--					<td><i class=" icon-edit"></i>Status</td>-->
					<th> Actions </th>
				</tr>
			</thead>
			<tbody>
			   <?php foreach($table as $row) { ?>
					<tr>
						<td><?php echo $row->id;?></td>
						<td><?php echo $row->name;?></td>
						<td><?php echo $row->email;?></td>
						<td><?php echo $row->address;?></td>
						<td><?php echo $row->contactno;?></td>
						<td><?php echo $row->pointer;?></td>
						<td>
							<a href="<?php echo site_url('site/editvideo?id=').$row->id;?>" class="btn btn-primary btn-xs">
								<i class="icon-pencil"></i>
							</a>
							<a href="<?php echo site_url('site/deletevideo?id=').$row->id; ?>" class="btn btn-danger btn-xs" onclick="return confirm('Are you sure?')">
								<i class="icon-trash "></i>
							</a> 
						
						</td>
					</tr>
					<?php } ?>
			</tbody>
			</table><br>
			<div class="clear pagination">
                <ul>
                    <?php echo $this->pagination->create_links(); ?>
                </ul>    
            </div>
		</section>
	</div>
</div>