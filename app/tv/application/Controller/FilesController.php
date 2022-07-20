<?php


namespace Mini\Controller;

use Mini\Model\Files;
use Mini\Libs\Helper;

class FilesController
{
    public function index()
    {


        $watermarkImg = isset($_GET['watermark']) ? true : false;
        $image = new Files();
        $image->processImage($_GET['p'], false, false);


    }

    public function renameFile(){
        $post = file_get_contents('php://input');
        $data = json_decode($post, true);
        $old = trim($data['old']);
        $new =  trim($data['new']);
        $ext = trim($data['ext']);
        $oldPub = "publications/".$old.$ext;
        $newPub = "publications/".$new.$ext;
        $oldThumb = "thumbnails/".$old.$ext;
        $newThumb = "thumbnails/".$new.$ext;
        rename($oldPub, $newPub);
        rename( $oldThumb, $newThumb);
    }

    public function deleteFile(){
        $post = file_get_contents('php://input');
        $data = json_decode($post, true);
        $old = trim($data['old']);
        $ext = trim($data['ext']);
        $Pub = "publications/".$old.$ext;
        $Thumb = "thumbnails/".$old.$ext;
        unlink($Pub);
        unlink( $Thumb);

    }
    public function getAllImg($save = false){
        $btn =  $save ? '<i id="saveGall" class="fas fa-save"></i>' : null;
        $gall = ' <table id="gallery" class="table table-bordered display nowrap"  style="width:100%">'.$btn.'<thead> <tr><th></th> </tr></thead><tbody> ';
        $all_files = glob("publications/*.*");
        usort($all_files, function($x, $y) {
            return filemtime($x) < filemtime($y);
        });
        for ($i = 0; $i < count($all_files); $i++) {
            $image_name = $all_files[$i];
            $supported_format = array('gif', 'jpg', 'jpeg', 'png');
            $ext = strtolower(pathinfo($image_name, PATHINFO_EXTENSION));
            if (in_array($ext, $supported_format)) {
                $gall .= '<tr class="gallery-holder" my-3">
                        <td >
                        <span class="extension" style="display: none">.'.$ext.'</span>
                        <span class="galleryspan">'.str_replace('publications/', '', preg_replace('/\.[^.]+$/','',$image_name) ).' </span>
                        <img class="gallery"  loading="lazy" src="' . Helper::thumbnails($image_name)  . '" alt="' . $image_name . '" >
                        </td>
                        </tr>';
            } else {
                continue;
            }
        }
        $gall .='</tbody></table>';
        echo $gall;
    }


}

