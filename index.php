<?php
/*
    Plugin Name: WP Online User
    Plugin Url: http://geekwebsolution.com/
    Version: 1.0
    Author: Geek Web Solution
 */
add_action( 'admin_menu', 'add_plugin_setting_page');
function add_plugin_setting_page()
{
	add_menu_page( 'Wp User Online','Wp User Online', 'administrator','wp-online-user','dis_log_user1','dashicons-menu','90');
}
add_action('wp_footer','script_function');
add_action( 'admin_footer','script_function');
function dis_log_user1()
{
		include('wp_online_users.php');
}
add_action('wp_footer','up_user');
add_action( 'admin_footer','up_user');
add_action( 'wp_ajax_up_user', 'up_user' );
add_action( 'wp_ajax_nopriv_up_user', 'up_user' ); 
function up_user()
{
	if (is_user_logged_in()) {
		$user = wp_get_current_user();
		$user_id=(int) $user->ID;
		$date = date_create();
		$dt=date_format($date,'U');
		$dt=date_format($date,'U');
		update_user_meta($user_id, "update_dt", date("Y-m-d H:i:s"));
		//echo $dt;
	}
}
add_action( 'wp_ajax_dis_log_user', 'dis_log_user' );
add_action( 'wp_ajax_nopriv_dis_log_user', 'dis_log_user' ); 
function dis_log_user()
{
	?>
	<table class="wp-list-table widefat fixed striped users" style="width:50%;">
			<thead>
				<tr>
					<th class="manage-column column-cb">Name</th>
					<th scope="col" id="email" class="manage-column column-name">E-mail</th>
					<th scope="col" id="online" class="manage-column column-name">Online</th>
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
			
					if($diff<=1)
					{
						
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
			<th scope="col" class="manage-column column-role">Online</th>
		</tr>
		</tfoot>
		
	</table>

	<ul class="subsubsub">
		<li class="all"><b>Online <span class="count" id="ci_count1">(<?php echo $online; ?>)</span></b></li>
		<li class="all"><b>Ideal <span class="count" id="ci_count1">(<?php echo $ideal; ?>)</span></b></li>
		<li class="all"><b>Offline <span class="count" id="ci_count1">(<?php echo $offline; ?>)</span></b></li>
	</ul>
	
<input type="hidden" id="online_us_con" value="<?php echo $ci; ?>">
<?php
die();
}
 function script_function()
 {
?>

<script type="text/javascript">
jQuery(document).ready(function(){
	var i=0;
	//showOnlineuser();
	jQuery("body").click(update_dt);
});

jQuery.fn.scrollEnd = function(callback, timeout) {          
  jQuery(this).scroll(function(){
    var $thiss = jQuery(this);
    if ($thiss.data('scrollTimeout')) {
      clearTimeout($thiss.data('scrollTimeout'));
    }
    $thiss.data('scrollTimeout', setTimeout(callback,timeout));
  });
};
jQuery(window).scrollEnd(function(){
   update_dt();
}, 1000);
function update_dt(){
	var ajaxurl="<?php echo admin_url( 'admin-ajax.php' ); ?>";
	jQuery.post(
	ajaxurl,  
	{
		'action': 'up_user'
	}, 
	function(response){
		//alert("fsdfsdfsdf");
	});
}
function showOnlineuser()
{
	var ajaxurl="<?php echo admin_url( 'admin-ajax.php' ); ?>";
	jQuery.post(
	ajaxurl,  
	{
		'action': 'dis_log_user'
	}, 
	function(response){
	/* 	var cc=jQuery("#online_us_con").val();
		console.log(cc) */;
		jQuery("#wp_user_login").html(response);
	/*jQuery("#ci_count1").text(cc); */
	
	});
	
	
}
var myVar = setInterval(alertFunc, 60000);
function alertFunc() {
	showOnlineuser();
}
</script>
<?php
 }
?>