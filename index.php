<?php
/**
 * Author: Pandurang Zambare, pandu@thinkoverit.com
 * 
 * Script to Take DB backup locally and the Upload to S3.
 **/
		$dbname = 'database';
		$dbhost = 'hostname';
		$dbuser = 'username';
		$dbpass = 'password';

		$aws['awsAccessKey'] = "Your AWS key";
		$aws['awsSecretKey'] = "Your AWS secret key";
		$aws['awsBucketName'] = "Your AWS Bucket Name";

		$backupFile = "/tmp/".$dbname . date("j-M-Y") . '.gz';
		$command = "mysqldump --opt -h $dbhost -u$dbuser -p$dbpass $dbname | gzip > $backupFile";
		system($command);
		
		include(APPPATH.'s3.php');
		
		$s3obj = new S3($aws['awsAccessKey'], $aws['awsSecretKey'])
		$put = $s3obj->putObjectFile($backupFile, $aws['awsBucketName'], "db_backup/".date("Y",time())."/".baseName($backupFile), S3::ACL_PRIVATE);
		
		if($put) {
			echo "Successfully uploaded Backup.";
		}
		@unlink($backupFile);
?>