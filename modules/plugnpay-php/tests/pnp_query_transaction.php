<?php

require '../PnP.php';
$p = new PnP( array( 'orderID' => '121336144441231693') );

echo 'Trans details were: <pre>';
print_r( $p->query_trans() );
echo '</pre>';

?>
