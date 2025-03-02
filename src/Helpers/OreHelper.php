<?php
use Illuminate\Support\Facades\Storage;
//##-----------ore helper function-----

function orevlCreate($ore){
    $orevl_arr = null;
    foreach($ore as $ore_l){
        $orevl_arr[$ore_l['name']]=$ore_l['orevl'];
    }
    return $orevl_arr;
}


function orevlUpdate($ore,$id){
    $orevl_arr = null;
    foreach($ore as $ore_l){
        if(str_contains($ore_l['orevl'],'unique:')){
            $orevl_arr[$ore_l['name']]=$ore_l['orevl'].','.$id;
        }else{
            $orevl_arr[$ore_l['name']]=$ore_l['orevl'];
        }
    }
    return $orevl_arr;
}

function oreRelationKey($str,$key){
    $_arr=explode(",",$str);
    return $_arr[$key];
}



function getOreOf($position = null){
    $uri_path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    $uri_segments = explode('/', $uri_path);
    
    if($position = array_search('ore', $uri_segments)){
        return $uri_segments[$position + 1];
    }
    
    return end($uri_segments);

}


  // -----------------------------FOR IMAGE -----------------------------------------
  function my_image_file_upload($image,$folder){
    // $fname = rand(00, 99) . '-' . $image->getClientOriginalName();
    // $fname= strToLower(str_replace(' ', '-', $fname));
    $fname = rand(11111111, 99999900) . '.' . $image->getClientOriginalExtension();
    $image->storeAs($folder.'/', $fname);
    return $fname;
}


//------------------------FOR IMAGE UPLOAD AND DELETE----MEANS REPLACE--------------
function my_image_file_replace($image,$folder,$old_image=null){
    // $fname = rand(00, 99) . '-' . $image->getClientOriginalName();
    // $fname= strToLower(str_replace(' ', '-', $fname));
    $fname = rand(11111111, 99999900) . '.' . $image->getClientOriginalExtension();
    $image->storeAs($folder.'/', $fname);

    if($fname && Storage::exists($folder.'/'.$old_image)){
        Storage::delete($folder.'/'.$old_image);
    }
    return $fname;
}

// -----------------------------FOR IMAGE DELETE-----------------------------------------
function my_image_file_delete($image,$folder){
    if(Storage::exists($folder.'/'.$image)){
        Storage::delete($folder.'/'.$image);
    }
    return true;
}

function getOreAction($ore_model)
{
    $ore_actions = [
        'view' =>  true,
        'create' => true,
        'save' => true,
        'edit' => true,
        'update' => true,
        'delete' => false
    ];

    foreach ($ore_actions as $key => $val) {
        $method = "oreCan" . ucfirst($key);
        if (method_exists($ore_model, $method)) {
            $ore_actions[$key] = $ore_model->{$method}();
        }
    }
    return $ore_actions;
}

function filterMethodsByPrefix($class_object, $prifix) {
    $methods = get_class_methods($class_object);
    $filtered_methods = [];

    foreach ($methods as $method) {
        if (strpos($method, 'oreCan') === 0) {
            $filtered_methods[] = $method;
        }
    }
    return $filtered_methods;
}



