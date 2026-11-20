<?php
declare(strict_types=1);
session_start();
if (($_SESSION['role'] ?? '') !== 'admin') { http_response_code(403); exit('Admin access required.'); }
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !hash_equals($_SESSION['csrf'] ?? '', $_POST['csrf'] ?? '')) { http_response_code(403); exit('Invalid request token.'); }
$base = dirname(__DIR__); $cfg = require (file_exists($base.'/config.php') ? $base.'/config.php' : $base.'/config.example.php');
try {
  $d=$cfg['db']; $pdo=new PDO("mysql:host={$d['host']};dbname={$d['name']};charset={$d['charset']}",$d['user'],$d['pass'],[PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION]);
  $file=$_FILES['csv']??null; if(!$file || $file['error']!==UPLOAD_ERR_OK || $file['size']>5*1024*1024) throw new RuntimeException('Upload a CSV file under 5 MB.');
  $fh=fopen($file['tmp_name'],'rb'); $header=fgetcsv($fh); $required=['name','slug','price']; if(!$header || array_diff($required,$header)) throw new RuntimeException('CSV requires name, slug, and price columns.');
  $columns=['name','slug','sku','price','old_price','stock','image_url','short_desc','description']; $allowed=array_values(array_intersect($columns,$header)); $count=0;
  while(($row=fgetcsv($fh))!==false){if(count($row)!==count($header))continue;$data=array_combine($header,$row);$values=[];foreach($allowed as $col)$values[$col]=$data[$col]??'';$values['stock']=max(0,(int)($values['stock']??0));$values['price']=max(0,(float)$values['price']);$values['old_price']=max(0,(float)($values['old_price']??0));if(trim($values['name'])===''||trim($values['slug'])==='')continue;
   $insertCols=implode(',',array_map(fn($c)=>"`$c`",$allowed));$marks=implode(',',array_fill(0,count($allowed),'?'));$updates=implode(',',array_map(fn($c)=>"`$c`=VALUES(`$c`)",array_filter($allowed,fn($c)=>$c!=='slug')));$sql="INSERT INTO products ($insertCols) VALUES ($marks) ON DUPLICATE KEY UPDATE $updates";$pdo->prepare($sql)->execute(array_values($values));$count++;
  } fclose($fh);$_SESSION['flash']="Imported or updated $count products.";
} catch(Throwable $e){$_SESSION['flash']='CSV import failed: '.$e->getMessage();}
header('Location: ?page=admin&tab=import');
