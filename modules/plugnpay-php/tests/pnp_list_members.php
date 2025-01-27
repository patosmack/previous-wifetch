<?php

require '../PnP.php';
$p = new PnP();

echo '<pre>';
print_r ( $p->list_members() );
echo '</pre>';

?>
