<?php

namespace Mini\Model;

use Mini\Core\Model;
use Mini\Libs\{ResizeImage, Watermark};

class Files extends Model
{

    public function processImage($path, $watermark, $editor) {
       
        try {
            if (
                !isset($_FILES['file']['error']) ||
                is_array($_FILES['file']['error'])
            ) {


            //    throw new \RuntimeException('Invalid parameters.');
            }
            switch ($_FILES['file']['error']) {
                case UPLOAD_ERR_OK:
                    break;
                case UPLOAD_ERR_NO_FILE:
                    throw new RuntimeException('No file sent.');
                case UPLOAD_ERR_INI_SIZE:
                case UPLOAD_ERR_FORM_SIZE:
                    throw new \RuntimeException('Exceeded filesize limit.');
                default:
                    throw new \RuntimeException('Unknown errors.');
            }
            $url = URL;
            $unique = $path ==='publications' ? uniqid() : '';

            $file =  str_replace( array('(',')', ' '), array('',''), $_FILES['file']['name']);
            $filepath = sprintf($path . '/%s_%s', $unique, $file );
            if (!move_uploaded_file(
                $_FILES['file']['tmp_name'],
                $filepath
            )) {
                throw new \RuntimeException('Failed to move uploaded file.');
            }
            // All good, send the response
            if ($editor) {
                echo json_encode([
                    'location' => URL . $filepath
                ]);
            } else {
                /**/

                /**/

                if ($watermark) {
                    $watermark = new Watermark($filepath);
                    //  $watermark->setWatermarkImage(ROOT_PATH.'/public/img/watermark.png');
                    $watermark->setType(Watermark::BOTTOM_RIGHT);
                    $watermark->saveAs($filepath);
                }
                $resize = new ResizeImage($filepath);
                $resize->resizeTo(500, 500, 'maxWidth');
                $thumbPath = sprintf('thumbnails/%s_%s', $unique, $file);
                $resize->saveImage($thumbPath);


                //


                echo $filepath;
            }
        } catch (RuntimeException $e) {
            // Something went wrong, send the err message as JSON
            http_response_code(400);

            echo json_encode([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }
}
