<?php
// Run only from CLI: php create-admin.php admin@example.com 'Long-unique-password'
if (PHP_SAPI !== 'cli') { http_response_code(404); exit; }
if ($argc !== 3 || !filter_var($argv[1], FILTER_VALIDATE_EMAIL) || strlen($argv[2]) < 14) { fwrite(STDERR, "Usage: php create-admin.php email long-unique-password (14+ chars)\n"); exit(1); }
$cfg=require __DIR__.'/config.php';$d=$cfg['db'];$pdo=new PDO("mysql:host={$d['host']};dbname={$d['name']};charset={$d['charset']}",$d['user'],$d['pass'],[PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION]);
$q=$pdo->prepare("INSERT INTO users(name,email,password_hash,role) VALUES(?,?,?,'admin') ON DUPLICATE KEY UPDATE password_hash=VALUES(password_hash),role='admin'");$q->execute(['Store Administrator',strtolower($argv[1]),password_hash($argv[2],PASSWORD_DEFAULT)]);echo "Administrator account created/updated. Remove any temporary copies of this script after use.\n";
