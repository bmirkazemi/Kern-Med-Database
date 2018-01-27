<?php
$db = pg_connect("host=localhost dbname=kernmed port=5432 user=kernmed password=Zul9Zuzlj");

if ($db) {
    //echo "connected";
} else {
    exit(1);
}
?>
