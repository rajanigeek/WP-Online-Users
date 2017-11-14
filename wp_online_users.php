<div class="wrap">
	<h1>Online Users</h1>
	
	<div id="wp_user_login">
		<table class="wp-list-table widefat fixed striped users" style="width:50%;">
			<thead>
				<tr>
					<th class="manage-column column-cb">Name</th>
					<th scope="col" id="email" class="manage-column column-name">E-mail</th>
					<th scope="col" id="online" class="manage-column column-name">Status</th>
				</tr>
			</thead>
			<tbody id="the-list" data-wp-lists="list:user">
		<?php
			$online=0;
			$ideal=0;
			$offline=0;
			$ss = get_users();
			//echo plugin_dir_path( __FILE__ );
			//echo plugins_url( 'wp-user-online/images/ideal16x16.png', dirname(__FILE__) );
			$img_on=plugins_url( 'wp-user-online/images/online_16x16.png', dirname(__FILE__) );
			$img_oideal=plugins_url( 'wp-user-online/images/ideal16x16.png', dirname(__FILE__) );
			$img_off=plugins_url( 'wp-user-online/images/offline_16x16.png', dirname(__FILE__) );
			foreach($ss as $k=>$v)
			{
				
				$myvals = get_user_meta($v->ID);
				
				$date = date_create();
				$all1= date("Y-m-d H:i:s");
				$all=$myvals['update_dt'][0];
				if($all)
				{
					$diff = abs(strtotime($all) - strtotime($all1));
					$diff=round($diff  / 60,2);
				//echo $v->user_login."--".round($diff  / 60,2). " minute<br>";
				/* $myvals = get_user_meta($v->ID);
				$date = date_create();
				$all1=date_format($date, 'Y-m-d h:i:s');
				date_timestamp_set($date,$myvals['update_dt'][0]);
				$all=date_format($date, 'Y-m-d h:i:s');
				$start_date = new DateTime($all1);
				$end_date = new DateTime($all);
				$interval = $start_date->diff($end_date);
				echo $v->user_login ." Minutes = ". $interval->i ."<br> Hours =" .$interval->h. "<br>"; */
					if($diff<=1)
					{
						//$ar[$v->ID]=$v->ID;
						$online++;
					?>
						<tr>
							<td class="name column-name" data-colname=""><?php echo $v->user_login; ?></td>
							<td class="name column-name" data-colname=""><?php echo $v->user_email; ?></td>
							<td class="name column-name" data-colname=""><img alt="Online" src="<?php echo $img_on; ?>"></td>
						</tr>
					<?php
					}
				}
			}
			$ss = get_users();
			foreach($ss as $k=>$v)
			{
				$myvals = get_user_meta($v->ID);
				
				$date = date_create();
				$all1= date("Y-m-d H:i:s");
				$all=$myvals['update_dt'][0];
				if($all)
				{
					$diff = abs(strtotime($all) - strtotime($all1));
					$diff=round($diff  / 60,2);
					if($diff>1 && $diff<=5)
					{
						$ideal++;
					?>
						<tr>
							<td class="name column-name" data-colname=""><?php echo $v->user_login; ?></td>
							<td class="name column-name" data-colname=""><?php echo $v->user_email; ?></td>
							<td class="name column-name" data-colname=""><img alt="Ideal" src="<?php echo $img_oideal;?>"><?php //echo //$all; ?></td>
						</tr>
					<?php
					}
				}
			}
			$ss = get_users();
			
		?>
		</tbody>
		<tfoot>
		<tr>
			<th class="manage-column column-cb">Name</th>
			<th scope="col" class="manage-column column-name">E-mail</th>
			<th scope="col" class="manage-column column-role">Status</th>
		</tr>
		</tfoot>
		
	</table>
	<ul class="subsubsub">
		<li class="all"><b>Online <span class="count" id="ci_count1">(<?php echo $online; ?>)</span></b></li>
		<li class="all"><b>Ideal <span class="count" id="ci_count1">(<?php echo $ideal; ?>)</span></b></li>
		<li class="all"><b>Offline <span class="count" id="ci_count1">(<?php echo $offline; ?>)</span></b></li>
	</ul>
	</div>
	
</div>
