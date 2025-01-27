<?php

require '../PnP.php';
$p = new PnP();

$response = $p->auth( array(
    'card_number' => '4111111111111111',
    'card-name'   => 'cardtest',
    'card-amount' => '0.02',
    'card-exp'    => '07/11',
    'email'       => '',
    'ship-name'   => 'cardtest',
    'address1'    => '123 West Main Street',
    'city'        => 'Omaha',
    'state'       => 'VT',
    'zip'         => '40123',
    'cc-cvv'      => '123' 
                        ));
echo 'orderID was ' . $p->orderID;

echo ' Response was <pre> ';
print_r( $response );
echo '</pre>';

echo 'Trans details were: <pre>';
print_r( $p->query_trans() );
echo '</pre>';

?>
