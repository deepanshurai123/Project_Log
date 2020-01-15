<?php
		$value = $this->Tabledata->get_entries(); ?>
		<table style="width:100%">
       <tr>
        <th>Event</th>
        <th>Admin</th>
        <th>Date</th>
        <th>Event_Details</th>
			</tr>
	<?php foreach($value as $detail) {
					$event_detail = $this->Tabledata->get_display_message($detail->P_id,$detail->Tag);	?>
					<tr>
          	<td><?php echo array_key_exists( $detail->Tag,$this->inserter->tag_map)?$this->inserter->tag_map[$detail->Tag]:$detail->Tag; ?></td>
          	<td><?php echo "HELLO"  ?></td>
          	<td><?php echo $detail->Time; ?></td>
          	<td><?php echo $event_detail; ?></td>
        	</tr> 
		<?php } ?>
	</table>
 

		
			


	
