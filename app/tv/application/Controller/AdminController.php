<?php


namespace Mini\Controller;

use Mini\Model\Admin;
use Mini\Libs\{Mailer, Helper};

class AdminController
{
    public function __construct()
    {

        if (!isset($_SESSION['user'])) {
            header('location: ' . URL);
        }
        $this->helpers = new Helper;
        $this->templates = ['standard' => 'Стандартна', 'contacts' => 'За контакти', 'news' => 'Новини'];
    }

    public function index()
    {

        header('location: ' . URL);
    }

    public function keepAlive()
    {
        return true;
    }



    public function getadvertising(){
        $admin = new Admin;
        $admin->getAdvertising();
    }
    public function normalizing()
    {
        $root = "../public/videos";
        $dirs = scandir($root);
        foreach ($dirs as $dir) {
            if ($dir == '.' || $dir == '..') {
                continue;
            }
            $path = $root . '/' . $dir;
            if (is_dir($path)) {
                $newName =  preg_replace('/\s+/', '', $path);
                strtolower($newName);
                rename($path, $newName);
            }
        }
        $dirs = scandir($root);
        foreach ($dirs as $dir) {
            if ($dir == '.' || $dir == '..') {
                continue;
            }
            $path = $root . '/' . $dir;
            if (is_dir($path)) {
                $all_files = glob($path."/*.*");
                for ($i = 0; $i < count($all_files); $i++) {
                    $newName = preg_replace('/\s+/', '', $all_files[$i]);
                    strtolower($newName);
                    rename($all_files[$i], $newName);
                }
            }
        }

        $admin = new Admin();
          $admin->entryLowetWhite();
    }

    public function updateVideoData($videos) {

    }

    public function getnewadvertising()
    {
        $url = SERVER . "/loginUpdate?location=" . $_SESSION['location_access'];

        $resp =  Helper::curlRequest($url);

        $allData = json_decode($resp, true);

        $admin = new Admin;

        $admin->resetData('advertising');

        foreach ($allData['advertising'] as $advertising) {
            $admin->CreateAdvertise($advertising);
        }


	    foreach ( $allData['advertising'] as $advertising ) {

		    $data = unserialize( $advertising['data'] );

		    foreach ( $data as $entry ) {
			    if ( $entry->data->type === 'video' ) {

                    $dir = explode( '/', $entry->data->element );

                    if ( ! file_exists( '../public/' . $dir[0] . '/' . $dir[1] ) ) {
                        mkdir( '../public/' . $dir[0] . '/' . $dir[1], 0777, true );
                    }


				    if ( file_exists( '../public/' . $dir[0] . '/' . $dir[1] . '/' . $dir[2] ) ) {

					    $headers = get_headers( SERVER . 'public/' . $dir[0] . '/' . $dir[1] . '/' . $dir[2], 1 );

					    $lowerCaseHeaders = array_change_key_case( $headers );

					    $filesize = $lowerCaseHeaders['content-length'];

					    if (filesize( '../public/' . $dir[0] . '/' . $dir[1] . '/' . $dir[2]) != $filesize) {

						    unlink('../public/' . $dir[0] . '/' . $dir[1] . '/' . $dir[2]);

						    file_put_contents( '../public/' . $dir[0] . '/' . $dir[1] . '/' . $dir[2], fopen( SERVER . 'public/' . $dir[0] . '/' . $dir[1] . '/' . $dir[2], 'r' ) );

					    };
				    }
				    if ( ! file_exists( '../public/' . $dir[0] . '/' . $dir[1] . '/' . $dir[2] ) ) {

					    file_put_contents( '../public/' . $dir[0] . '/' . $dir[1] . '/' . $dir[2], fopen( SERVER . 'public/' . $dir[0] . '/' . $dir[1] . '/' . $dir[2], 'r' ) );
				    }
			    };
		    };
	    }
    }
    public function getnewschedule(){
        set_time_limit (0);
        $url = SERVER . "/loginUpdate?location=" . $_SESSION['location_access'];

        $resp =  Helper::curlRequest($url);

        $allData = json_decode($resp, true);

        $admin = new Admin;

        $admin->resetData('schedule');

        foreach ($allData['schedule'] as $schedule) {
            $admin->CreateSchedule($schedule);

        }

	    foreach ( $allData['schedule'] as $schedule ) {
		    $dir = explode( '/', $schedule['video'] );

		    if ( ! file_exists( '../public/' . $dir[2] . '/' . $dir[3] ) ) {
                mkdir( '../public/' . $dir[2] . '/' . $dir[3], 0777, true );
            }
		    if ( file_exists( '../public/' . $dir[2] . '/' . $dir[3] . '/' . $dir[4] ) ) {
			    $headers = get_headers( SERVER . 'public/' . $dir[2] . '/' . $dir[3] . '/' . $dir[4], 1 );
			    $lowerCaseHeaders = array_change_key_case( $headers );
			    $filesize = $lowerCaseHeaders['content-length'];
			    if (filesize( '../public/' . $dir[2] . '/' . $dir[3] . '/' . $dir[4] ) != $filesize) {
				    unlink('../public/' . $dir[2] . '/' . $dir[3] . '/' . $dir[4]);
				    file_put_contents( '../public/' . $dir[2] . '/' . $dir[3] . '/' . $dir[4], fopen( SERVER . 'public/' . $dir[2] . '/' . $dir[3] . '/' . $dir[4], 'r' ) );
			    };
		    }
		    if ( !file_exists( '../public/' . $dir[2] . '/' . $dir[3] . '/' . $dir[4] ) ) {
			    file_put_contents( '../public/' . $dir[2] . '/' . $dir[3] . '/' . $dir[4], fopen( SERVER . 'public/' . $dir[2] . '/' . $dir[3] . '/' . $dir[4], 'r' ) );
		    }
	    }
    }
}
