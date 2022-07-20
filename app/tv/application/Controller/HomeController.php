<?php


namespace Mini\Controller;

use Mini\Model\{Home, Category, Admin};
use Mini\Libs\{Helper, Mailer, Strings};

class HomeController {



	public function __construct() {


		$this->redis   = new \Redis();

		$this->redis->connect( '127.0.0.1', 6379 );

		$this->admin = new Admin;


	}


	public function __call( $name, $arguments ) {


        require APP .'view/_templates/header.php';
        require APP . 'view/home/login.php';
	}

	public function index() {

	    $home = new Home;

        $home->isFirstTime();



        require APP .'view/_templates/header.php';
        require APP . 'view/home/login.php';
	}


	private function updateIP() {

		$data = json_decode( file_get_contents( 'php://input' ) );

		$this->admin->updateIPaddress( $data->newIP );
	}

	private function reset() {


        require APP .'view/_templates/header.php';
        require APP . 'view/home/reset.php';
	}

	private function updateLocalLayoutData() {

	    $post = file_get_contents( 'php://input' );

	    $data = json_decode( $post )->layout;

	    $this->admin->updateOldLayout( $data );
	}

	private function updateFromServer() {

	    $this->redis->del( "newData" );

	    $this->redis->lpush( "newData", $_GET['data'], $_GET['time'] );

	    $arList = $this->redis->lrange( "newData", 0, - 1 );

	    print_r( $arList );
	}

	private function newSchedule() {

	    $post     = file_get_contents( 'php://input' );

	    $day      = json_decode( $post )->day;

	    $playlist = $this->admin->getPlayList( $_SESSION['location_access'], $day );
	}



	private function complete() {

		$ip   = $_POST['ip'];

		$user = $_POST['username'];

		$url  = SERVER . "/admin/updateIP/$user/$ip";

		Helper::curlRequest($url);

		$url  = SERVER . "/getinformation?user=" . $_POST['username'];

		$allData = json_decode( Helper::curlRequest($url), true );



		if ( $allData['mainuser']['parent_admin'] == '0' ) {
			$_SESSION['msg'] = "Посоченият от Вас акаунт  принадлежи на главен администратор! <br> Моля посечете потребителско име на обект.";
			header( 'Location: ' . $_SERVER['HTTP_REFERER'] );
			exit;
		}
		$this->admin->CreateAdmin( $allData['mainuser'] );

		foreach ( $allData['locations'] as $location ) {
			$this->admin->CreateLocation( $location );
		}


		header( 'Location: login' );
	}

	private function player() {

	    $playlist = $this->admin->getPlayList( $_SESSION['location_access'] );

        $videos = [];

        foreach ( $playlist as $video ) {
            $link = str_replace( "../public/", "", $video[0] );
            array_push( $videos, $link );
        };

        $layout   = unserialize( $this->admin->getLayout( $_SESSION['location_access'] )->layout );

        $initHTML = '';

        if ( gettype( $layout ) == 'string' ) {
            $layout = json_decode( $layout, true );
        }

        foreach ( $layout["save"] ?? array() as $key => $element ) {
            switch ( $key ) {
                case 'activate_logo':
                    $coordinates = explode( ',', $element );
                    $img         = $layout['featured_image'];
                    if ( count( $coordinates ) == 1 ) {
                        $coordinates = array( "0", "0" );
                    };
                    $img = SERVER .Helper::big( $img );
                   $initHTML .= '<div id="logoholder" style="left: ' . $coordinates[0] . '% ; top:  ' . $coordinates[1] . '%" class="layoutElements"><img   src="' . $img . '"></div>';
                    break;
                case 'activate_weather':
                    $coordinates = explode( ',', $element );
                    if ( count( $coordinates ) == 1 ) {
                        $coordinates = array( "0", "0" );
                    };
                   $initHTML .= '<div id="weather" style="left: ' . $coordinates[0] . '% ; top:  ' . $coordinates[1] . '%" class="layoutElements">
                                        <span id="degree"></span><span>°C</span>
                                        <img id="icon" src="">
                                </div>';
                    break;

                case 'active_scroll':
                    $coordinates = explode( ',', $element );
                    $txt         = $layout['save']['txt_infodata'];
                    $bg          = $layout['save']['bg_infodata'];
                    $info        = $layout['activate_infodata'];
                    if ( count( $coordinates ) == 1 ) {
                        $coordinates = array( "0", "0" );
                    };
                   $initHTML .= '<div style="width: 100vw; left: 0; top:  ' . $coordinates[1] . '%;" class="layoutElements">
   <div class="bar" style="background-color: ' . $bg . '; color: ' . $txt . '"> 
   <span class="bar_content">' . $info . '</span> </div>
    </div>';

                    break;

                case 'activate_clock':

                    $coordinates = explode( ',', $element );

                    $txt  = $layout['save']['txt_clock'];
                    $bg   = $layout['save']['bg_clock'];
                    $size = $layout['save']['clock_size'];
                    if ( count( $coordinates ) == 1 ) {
                        $coordinates = array( "0", "0" );
                    };
                   $initHTML .= '<div class="layoutElements p-3 ' . $size . '" id="clock"  style="left: ' . $coordinates[0] . '%; top:  ' . $coordinates[1] . '%; background-color: ' . $bg . '; color: ' . $txt . '">
                             <span id="add_time"></span> 
                             </div>';
                    break;
            };
        }
        require APP . 'view/home/player.php';



	}

	private function setup() {


        require APP .'view/_templates/header.php';
        require APP . 'view/home/activation.php';

	}

	public function Router() {
		$search = explode( '/', $_GET['url'] );
		$action = $search[1] ?? $search[0];
		$this->$action();

	}
}
