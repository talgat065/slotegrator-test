<?php

require_once "../vendor/autoload.php";

use App\User;

$u = new User('nother');
echo $u->getName();
