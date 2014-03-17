<?php


$self = $_SERVER["PHP_SELF"];
$baseurl = $self . "?" . $_SERVER["QUERY_STRING"];

$baseurl = remove_querystring_var($baseurl, 'img');

function remove_querystring_var($url, $key) { 
  $url = preg_replace('/(.*)(?|&)' . $key . '=[^&]+?(&)(.*)/i', '$1$2$4', $url . '&'); 
  $url = substr($url, 0, -1); 
  return $url; 
}

if($_GET['img'] && file_exists($additional_images[$_GET['img']]['original'])){
    //specific image asked for
    $main_image = $additional_images[$_GET['img']]['original'];
}
else{
    $main_image = $additional_images[0]['original'];
}

for($i=0;$i<$j;$i++){
  
    $thumblist .= "
                    <a href='$baseurl&img=$i'><img src='{$additional_images[$i]['thumb']}' alt='' /></a>
                    ";
}


?>

<style type="text/css">

#main_display{width:500px;height:300px;overflow:hidden;border:solid 1px #000;}
#main_display img{width:400px;height:auto;}

#thumblist{width:400px;height:42px;overflow:hidden;border:solid 1px #000;}
#thumblist img{width:50px;height:40px;padding-right:5px;}

#thumblist a img{width:50px;height:40px;padding-right:5px;border:dotted 1px #000;}
#thumblist a:hover img{width:50px;height:40px;padding-right:5px;border:dotted 1px #ffdc00;}

</style>

<div id="main_display"><img src='<?php echo $main_image; ?>' alt='' /></div>

<div id="thumblist"><?php echo $thumblist; ?></div>