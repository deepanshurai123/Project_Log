<?php

$total_events = $this->get_events(); 
require_once('style.css'); ?>
	 
<div class = "header" >
   <a href = "#default" class = "logo" > Project Log </a>
</div> 	
<table style= "width:100%" >
			 <tr>
				<th> Event </th>
        <th> Admin </th>
        <th> Date </th>
        <th> Event_Details </th>
			</tr>
<?php  foreach( $total_events as $event ) {
					global $event_map;
					$admin;
					$event_detail = $this->get_display_message( $event->P_id, $event->Tag, $admin ); ?>
					<tr>
          	<td><?php echo array_key_exists( $event->Tag, $event_map ) ? $event_map[ $event->Tag ]: $event->Tag; ?> </td>
          	<td><?php echo $admin  ?></td>
          	<td><?php echo $event->Time; ?></td>
          	<td><?php echo $event_detail; ?></td>
        	</tr> 
<?php } ?>
</table>
	 

		
			


	