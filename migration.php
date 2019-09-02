<?php
include "config.php";
$dbh=new PDO('mysql:host='.$mysql_host.";charset=utf8", $username, $password);
$res=$dbh->exec("CREATE DATABASE IF NOT EXISTS `$dbname` CHARACTER SET=utf8 COLLATE utf8_unicode_ci");
if ($res===false) {
    echo "error on database creation". $dbh->errorInfo()[2];
}
$dbh->exec("use $dbname");
$dbh->exec("CREATE TABLE IF NOT EXISTS `migrations` (
`name` VARCHAR(100) NOT NULL, PRIMARY KEY (`name`))");

/*
 * 1) Ели нет бд - создать
 * 2) Создать таблицу со списком миграций (migrations, id)
 * 3) Проверить все ли миграции в папке записаны в таблицу migrations
 * 4) Те миграции которые не записаны выполнить через sql
 *
 * 2 массива
 * В первом список прошедших миграций
 * Во втором список всех миграций
 * Задача - получить список миграций которые не выполнялись
 */
$list_migration=scandir("./migrations/");
$res = $dbh->query("SELECT * FROM `migrations`");
if($res === false)
{
    echo "Warning! Error on migration! \n";
    $err=$dbh->errorInfo();
    echo "Code ".$err[0]. " Message - ".$err[2]." \n";
   die();
}
$result_array = $res->fetchAll(PDO::FETCH_ASSOC);
$apply_migration = array();
foreach ($result_array as $v){
    $apply_migration[]=$v["name"];
}
$rest=array();
foreach ($list_migration as $migration){
    if($migration == "." || $migration == ".."){
        continue;
    }
    if(in_array($migration, $apply_migration)){
        continue;
    }
    $migrationSql = file_get_contents("/home/b00ris/www/intervolga.dev/public/migrations/".$migration);
    $res=$dbh->exec($migrationSql);
    if($res === false)
    {
        echo "Warning! Error on migration".$migration ."\n";
        $err=$dbh->errorInfo();
        echo "Code ".$err[0]. " Message - ".$err[2]."\n";
        die();
    }
    $dbh->exec("insert into migrations (name) values ('$migration')");
    echo "Migration ".$migration ." succesfully complete"."\n";

}
echo "Migration was succesfully complete\n";