<?php
isset($_GET['id']) ? $id = $_GET['id'] : exit('error');

require '../tools/modelList.php';
require '../tools/modelTextures.php';
require '../tools/jsonCompatible.php';

$modelList = new modelList();
$modelTextures = new modelTextures();
$jsonCompatible = new jsonCompatible();

$id = explode('-', $id);
$modelId = (int)$id[0];
$modelTexturesId = isset($id[1]) ? (int)$id[1] : 0;

$modelName = $modelList->id_to_name($modelId);

if (is_array($modelName)) {
    $modelName = $modelTexturesId > 0 ? $modelName[$modelTexturesId-1] : $modelName[0];
    $json = json_decode(file_get_contents('../model/'.$modelName.'/index.json'), 1);
} else {
    $json = json_decode(file_get_contents('../model/'.$modelName.'/index.json'), 1);
    if ($modelTexturesId > 0) {
        $modelTexturesName = $modelTextures->get_name($modelName, $modelTexturesId);
        if (isset($modelTexturesName)) $json['textures'] = is_array($modelTexturesName) ? $modelTexturesName : array($modelTexturesName);
    }
}

$textures = $json['textures'];
foreach ($textures as $key => $texture){
		$textures[$key] = '../model/' . $modelName . '/' . $texture;
}
$json['textures'] = $textures;

$json['model'] = '../model/'.$modelName.'/'.$json['model'];
if (isset($json['pose'])) $json['pose'] = '../model/'.$modelName.'/'.$json['pose'];
if (isset($json['physics'])) $json['physics'] = '../model/'.$modelName.'/'.$json['physics'];

if (isset($json['motions'])) {
    $motions = $json['motions'];
    foreach ($motions as $key1 => $motion){
    	foreach($motion as $key2 => $resource){
    		foreach ($resource as $key3 => $value)
    			if($key3 == 'file'){
    				$motions[$key1][$key2][$key3] = '../model/' . $modelName . '/' . $value;
    			}
    	}
    }
    $json['motions'] = $motions;
}

if (isset($json['expressions'])) {
    $expressions = $json['expressions'];
    foreach ($expressions as $key1 => $expression){
    	foreach($expression as $key2 => $value){
    		if($key2 == 'file'){
    			$expressions[$key1][$key2] = '../model/' . $modelName . '/' . $value;
    		}
    	}
    }
    $json['expressions'] = $expressions;
}

header("Content-type: application/json");
echo $jsonCompatible->json_encode($json);
