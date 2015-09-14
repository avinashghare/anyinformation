<?php

require_once 'Mandrill.php';
$data = json_decode(file_get_contents('php://input'), true);
$name=$data['name'];
$email=$data['email'];
$mobile=$data['mobile'];
$query=$data['query'];
$to="avinash@wohlig.com";
$subject="Enquiry @ wohlig.com";
$msg='<html>
<head>
  <title>Enquiry @ wohlig.com</title>
</head>
<body>
  <p>Details of Enquiry made @ wohlig.com !</p>
  <table  style="border: 1px solid black;">
    <tr>
      <th>Name: </th><td>'.$name.'</td>
    </tr>
    <tr>
      <th>Email: </th><td>'.$email.'</td>
    </tr>
    <tr>
      <th>Contact Number: </th><td>'.$mobile.'</td>
    </tr>
    <tr>
      <th>Query: </th><td>'.$query.'</td>
    </tr>
  </table>
</body>
</html>';
try {
    $mandrill = new Mandrill('L65eD75SxDBKjpd0hBSRqA');
    $message = array(
        'html' => "$msg",
        'text' => 'Enquiry @ wohlig.com',
        'subject' => 'Enquiry @ wohlig.com',
        'from_email' => "$email",
        'from_name' => "$name",
        'to' => array(
            array(
                'email' => 'avinash@wohlig.com',
                'name' => 'Avinash',
                'type' => 'to'
            ),
            array(
                'email' => 'chintan@wohlig.com',
                'name' => 'Chintan',
                'type' => 'to'
            )
        ),
        'headers' => array('Reply-To' => 'message.reply@example.com'),
        'important' => false,
        'track_opens' => null,
        'track_clicks' => null,
        'auto_text' => null,
        'auto_html' => null,
        'inline_css' => null,
        'url_strip_qs' => null,
        'preserve_recipients' => null,
        'view_content_link' => null,
        'tracking_domain' => null,
        'signing_domain' => null,
        'return_path_domain' => null,
        'merge' => true,
        'merge_language' => 'mailchimp',
        'global_merge_vars' => array(
            array(
                'name' => 'merge1',
                'content' => 'merge1 content'
            )
        )
    );
    $async = false;
    $ip_pool = 'Main Pool';
	$datetime=date("Y-m-d h:i:sa");
    $result = $mandrill->messages->send($message, $async, $ip_pool);
    print_r($result);
    /*
    Array
    (
        [0] => Array
            (
                [email] => recipient.email@example.com
                [status] => sent
                [reject_reason] => hard-bounce
                [_id] => abc123abc123abc123abc123abc123
            )
    
    )
    */
} catch(Mandrill_Error $e) {
    // Mandrill errors are thrown as exceptions
    echo 'A mandrill error occurred: ' . get_class($e) . ' - ' . $e->getMessage();
    // A mandrill error occurred: Mandrill_Unknown_Subaccount - No subaccount exists with the id 'customer-123'
    throw $e;
}