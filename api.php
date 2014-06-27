<?php
		
function blendercloud_api( $atts ) {
     //return "foo = {$atts['foo']}";
	 
	 // map blenderid to userid
	 
	 $user_data = array();
	
	$args = array(
		'search'         => $_GET['blenderid'],
		'search_columns' => array( 'user_login' )	
	);

	$user_query = new WP_User_Query( $args );	

 	// Get the results from the query, returning the first user
 	$users = $user_query->get_results();

	if( !empty( $users ) ) {

		$user_id = $users[0]->ID;
	
		$user_data['shop_id'] = $user_id;

		$subscriptions = WC_Subscriptions_Manager::get_users_subscriptions( $user_id );
	
		if( !empty( $subscriptions ) ) {
		
			// iterate over all subscriptions. Logic still needs to be defined
			foreach( $subscriptions as $subscription ) {
				
				// regular subscription
				if( $subscription['status'] == 'active' && $subscription['product_id'] == 14 ) {
					$user_data['cloud_access'] = '1';
//					$user_data['expiry_date'] = $subscriptions['expiry_date'];		

					if( $user_data['expiry_date'] != 0 ) {
						$user_data['next_payment_date'] = $subscription['expiry_date'];
					} else {
						$user_data['next_payment_date'] = $subscription['trial_expiry_date'];						
					}
							
				}
			}
		
		} else {
			$user_data['cloud_access'] = '0';
		}

	} else {
		
		// user not found
		$user_data['shop_id'] = 0;
   	 $user_data['cloud_access'] = '0';
		
	}
	
	echo json_encode($user_data);
	die();
}
add_shortcode('blendercloud_api', 'blendercloud_api');	
	
?>