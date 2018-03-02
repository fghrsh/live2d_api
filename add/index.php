<?php

require '../tools/modelList.php';
require '../tools/modelTextures.php';

$modelList = new modelList();
$modelTextures = new modelTextures();

$modelList = $modelList->get_list()['models'];

foreach ($modelList as $modelName) {
    if (file_exists('../model/'.$modelName.'/textures.cache')) {
        
        $textures = $texturesNew = array();
        foreach ($modelTextures->get_list($modelName)['textures'] as $v) $textures[] = json_encode($v, JSON_UNESCAPED_SLASHES);
        foreach ($modelTextures->get_textures($modelName) as $v) $texturesNew[] = json_encode($v, JSON_UNESCAPED_SLASHES);
        
        if ($textures == NULL) continue; elseif (empty(array_diff($texturesNew, $textures))) {
            echo '<p>'.$modelName.' / textures.cache / No Update.</p>'; 
        } else {
            foreach (array_values(array_unique(array_merge($textures, $texturesNew))) as $v) $texturesMerge[] = json_decode($v, 1);
            file_put_contents('../model/'.$modelName.'/textures.cache', json_encode($texturesMerge, JSON_UNESCAPED_SLASHES));
            echo '<p>'.$modelName.' / textures.cache / Updated.</p>';
        }
        
    }
    elseif (is_array($modelName)) continue;
    elseif ($modelTextures->get_list($modelName)) echo '<p>'.$modelName.' / textures.cache / Created.</p>';
}
