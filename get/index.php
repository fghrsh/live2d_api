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

foreach ($json['textures'] as $k => $texture)
	$json['textures'][$k] = '../model/' . $modelName . '/' . $texture;

$json['model'] = '../model/'.$modelName.'/'.$json['model'];
if (isset($json['pose'])) $json['pose'] = '../model/'.$modelName.'/'.$json['pose'];
if (isset($json['physics'])) $json['physics'] = '../model/'.$modelName.'/'.$json['physics'];

if (isset($json['motions']))
    foreach ($json['motions'] as $k => $v) foreach($v as $k2 => $v2) foreach ($v2 as $k3 => $motion)
        if ($k3 == 'file') $json['motions'][$k][$k2][$k3] = '../model/' . $modelName . '/' . $motion;

if (isset($json['expressions']))
    foreach ($json['expressions'] as $k => $v) foreach($v as $k2 => $expression)
        if ($k2 == 'file') $json['expressions'][$k][$k2] = '../model/' . $modelName . '/' . $expression;

header("Content-type: application/json");
echo $jsonCompatible->json_encode($json);
