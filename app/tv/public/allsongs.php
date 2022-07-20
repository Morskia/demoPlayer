<?php

$gall = "";
$data = json_decode(file_get_contents('php://input'));
forEach ($data as $dir) {
    $gall .= "";
    $all_files = glob("albums/$dir/*.*");


    for ($i = 0; $i < count($all_files); $i++) {
        $image_name = $all_files[$i];
        $supported_format = array('mp4', 'mp3');
        $ext = strtolower(pathinfo($image_name, PATHINFO_EXTENSION));
        if (in_array($ext, $supported_format)) {
            $title = preg_replace('/\\.[^.\\s]{3,4}$/', '', $image_name);
            $gall .= "   <li class='player__song'><p class='player__context'>
                     <b class='player__song-name'>$title</b>
                            <span class='flex'>
                         
                            <span class='player__song-time'></span>
                        </span>
                     </p>
            <audio  preload='none' class='audio' src='https://mrmusic.bg/$image_name'></audio></li>";
        } else {
            continue;
        }
    }
}
echo $gall;

?>
    
  
     
    
     
