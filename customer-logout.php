<?php
require __DIR__.'/config/config.php';
unset($_SESSION['customer']);
redirect('/shop.php');
