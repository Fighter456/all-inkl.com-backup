 <?php

error_reporting(E_ALL ^ E_WARNING);
ini_set("max_execution_time", 300);
ini_set("memory_limit", "256M");

$backupDir = dirname(realpath(__FILE__));
$backupDate = date('Y-m-d_H_i_s');

exec("cd $backupDir");
exec("mysqldump -u {DATABASE_USER} -p{DATABASE_PASSWORD} --single-transaction {DATABASE_NAME} | gzip > ./sicherungen/database.sql.gz");
exec("tar --exclude='sicherungen' -vczf ./sicherungen/filesystem.tar.gz ./../ .");
exec("tar -vczf ./sicherungen/$backupDate.tar.gz ./sicherungen/database.sql.gz ./sicherungen/filesystem.tar.gz");

if (date("d") == "01") {
	exec("mkdir -p $backupDir/sicherungen/monthly");
	exec("cd ./sicherungen && mv $backupDate.tar.gz ./monthly/".date('W').".tar.gz");
	exec("cd ./sicherungen && ln -s ./monthly/".date('W').".tar.gz ./$backupDate.tar.gz");
}

exec("cd ./sicherungen && rm database.sql.gz filesystem.tar.gz");
