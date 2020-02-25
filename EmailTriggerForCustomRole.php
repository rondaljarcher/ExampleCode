<!-- integration of email triggers on third party user roles -->
<?php
add_action('user_register', 'to_the_trade_notification',10,3);
function to_the_trade_notification($user_id) {
	$customer = new WC_Customer( $user_id );
    $email = $customer->get_email();
    $role = $customer->get_role();
    if( $role == 'pending_wholesaler' ) {

        // Getting all WC_emails objects
        $email_notifications = WC()->mailer()->get_emails();

        //triggering the send email function
        $email_notifications['WC_Email_To_The_Trade_Notification']->trigger($user_id);
    }
}
?>