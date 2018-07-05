<?php

ini_set('display_errors', 1);
ini_set('log_errors', 1);
error_reporting(E_ALL);

use  Aws\S3\Exception\S3Exception;
use  Aws\S3\S3Client;

require 'vendor/autoload.php';

$config   = require('config.php');
$tmp_nome = $extensao = '';

// s3
$s3 = 	S3Client::factory([
						 	'version' 		=>  'latest',
						    'region'  		=>  'us-west-2',
						    'scheme' 		=>  'http',
						    'credentials' 	=>  [
											        'key'    	=> '?????',
											        'secret' 	=> '?????'
											    ]
		]);

try {	

	$dir_raiz = '/var/www/memoryin/uploads/file/';	

	if ($_POST) {

		$idUser = $_POST['id_user'];

	}


	$prefixo = $idUser.'_';

    $objects = 	$s3->getIterator('ListObjects',array(
													    'Bucket'  => $config['s3']['bucket'],
													    'Prefix'  => $prefixo,
													    'MaxKeys' => 10000
				));

    //6ef17b11-71eb-4f87-8a9c-e2405859b6ea/file/file

	foreach ($objects as $object) {

		$result = 	$s3->getObject(array(
											'Bucket' => $config['s3']['bucket'],
											'Key'    => $object['Key']
					));		

		$nm  	  = explode('_', $object['Key']);
		$nx  	  = explode('.', $nm[1]);
		$dir 	  = $dir_raiz.$nx[0].'/file';
		$fp  	  = fopen($dir, "a+");
        fputs($fp, $result['Body']);
        fclose($fp);

	}

	return;

} catch (S3Exception $e) {
	
	echo $e->getMessage();
	
}


?>