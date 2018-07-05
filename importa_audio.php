<?php

ini_set('display_errors', 1);
ini_set('log_errors', 1);
error_reporting(E_ALL);

function LoadImageCURL($save_to, $source) {

    $content = file_get_contents($source);
    file_put_contents($save_to, $content);

}


try {
    $conn = new PDO('mysql:host=localhost;dbname=esperovoce', 'esperevoc', 'checkywng');
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    echo 'ERROR: ' . $e->getMessage();
}

$return = simplexml_load_file('http://fast.api.liquidplatform.com/2.0/medias/?key=&search=channelId:40&sort=desc');

header("Content-Type: application/json; charset=utf-8");
$audios = json_encode($return);
$obj    = json_decode($audios);

/*
echo '<PRE>';
print_r($obj);
echo '</PRE>';

die;
*/

/* esse if é usado somente se a categoria já foi definida */
if (property_exists($obj->Media[0], 'channelName')) {


    $max = count($obj->Media);


    for($i=0; $i< $max; $i++) {        
        
        if (strstr($obj->Media[$i]->files->file->fileName, 'aniversario') && ($obj->Media[$i]->status != 'DELETED' && $obj->Media[$i]->status != 'deleted')) {

             if (strstr($obj->Media[$i]->files->file->fileName, '.')) {


                $nomes      = explode('_',$obj->Media[$i]->files->file->fileName);
                $nom        = explode('.',$nomes[1]);
                $heroi      = $nom[0];
                $categoria  = $obj->Media[$i]->channelName;            
                $urlM       = $obj->Media[$i]->id;
                $id         = base64_encode(md5($obj->Media[$i]->id));
                
                $sql        = "INSERT INTO nomes (
                                    nome                                
                                ) VALUES (
                                    '".$heroi."'                                                               
                                )";
                
                $count  = $conn->exec($sql);

                $idNome = $conn->lastInsertId();

                $sql        = "INSERT INTO audio (
                                    hashUrl,
                                    urlPh,
                                    urlM,
                                    idNome,                                
                                    sexo                                
                                ) VALUES (
                                    '".$id."',
                                    '',
                                    '".$urlM."',
                                    ".$idNome.",
                                    'fem'                               
                                )";
                

                $count = $conn->exec($sql);
                
            }
        }

        //echo $sql;
        //echo '<BR/>';


        /*
        if (mysql_affected_rows($link) == 1) {
            echo $i.' : Sucesso <BR/>';
        }
        */

    }

}

die;


?>