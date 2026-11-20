<?php
// Copy to config.php and set cPanel MySQL credentials. Keep config.php outside public_html if possible.
return [
  'db' => ['host' => 'localhost', 'name' => 'laksela_store', 'user' => 'YOUR_CPANEL_DB_USER', 'pass' => 'CHANGE_ME', 'charset' => 'utf8mb4'],
  'store' => ['name' => 'Laksela', 'phone' => '076 123 7770', 'email' => 'online@laksela.lk', 'whatsapp' => '94761237770', 'currency' => 'Rs.'],
  'payhere' => ['merchant_id' => '', 'merchant_secret' => '', 'sandbox' => true],
  'base_url' => '',
];
