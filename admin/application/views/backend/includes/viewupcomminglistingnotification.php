<div class="row">
		<section class="panel">
			<header class="panel-heading">
                Listing UpComming Notifications
            </header>
			<table class="table table-striped table-hover border-top " id="sample_1" cellpadding="0" cellspacing="0" >
			<thead>
				<tr>
					<!--<th>Id</th>-->
					<th>Name</th>
<!--					<th>Banner</th>-->
					<th>Start Date</th>
					<th>End Date</th>
					<th>Pointer</th>
					<th> Actions </th>
				</tr>
			</thead>
			<tbody>
			   <?php foreach($table as $row) { ?>
					<tr>
						<!--<td><?php echo $row->id; ?></td>-->
						<td><?php echo $row->name; ?></td>
<!--				        <td><img src="<?php echo base_url('uploads')."/".$row->banner; ?>" width="50px" height="auto"></td>-->
						<td><?php echo $row->pointerstartdate; ?></td>
						<td><?php echo $row->pointerenddate; ?></td>
						<td><?php echo $row->pointer; ?></td>
						<td> <a class="btn btn-primary btn-xs" href="<?php echo site_url('site/editlistingnotification?id=').$row->id;?>"><i class="icon-pencil"></i></a>
<!--                                      <a class="btn btn-danger btn-xs" href="<?php echo site_url('site/deletelistingnotification?id=').$row->id; ?>"><i class="icon-trash "></i></a>-->
									 
					  </td>
					</tr>
					<?php } ?>
			</tbody>
			</table>
		</section>
  </div>
