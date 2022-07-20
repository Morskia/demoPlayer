<?php


namespace Mini\Controller;

use Mini\Libs\Helper;
use Mini\Model\Home;
use Mini\Model\Login;
use Mini\Model\Admin;

use Mini\Libs\Mailer;

class LoginController
{
    public function index() {

        require APP . 'view/_templates/header.php';
        require APP . 'view/home/login.php';
    }

    public function clientLogin() {
        $Login = new Login();

        $password = crypt($_POST['password'], 'mmeSaaks24!ss');


        $logMe = $Login->login($_POST['user_name'], $password);

        if (is_object($logMe)) {

            $_SESSION['user'] = $logMe->username;
            $_SESSION['type'] = $logMe->type;
            $_SESSION['email'] = $logMe->email;
            $_SESSION['id'] = $logMe->id;
            $_SESSION['edit_video'] = $logMe->edit_video;
            $_SESSION['edit_advertising'] = $logMe->edit_advertising;
            $_SESSION['location_access'] = $logMe->location_access;
            $_SESSION['ip'] = $logMe->ip;
            $_SESSION['locationName'] = str_replace(' ', '', $logMe->name);

            if (isset($_POST['player']) && $_POST['player'] === 'start') {
                header('location: ' . URL . 'player');
                exit;
            }
            switch ($logMe->type) {
                case 0:
                case 1:
                case 2:
                    switch (connection_status()) {
                        case CONNECTION_NORMAL:
                            break;
                        case CONNECTION_ABORTED:
                        case CONNECTION_TIMEOUT:
                        case (CONNECTION_ABORTED & CONNECTION_TIMEOUT):
                            header('location: ' . URL . 'player');
                            exit;
                    }

                    set_time_limit(0);

                    $url = SERVER . "/loginUpdate?location=" . $_SESSION['location_access'];

                    $resp = Helper::curlRequest($url);

                    $allData = json_decode($resp, true);

                    $admin = new Admin;

                    $admin->resetData('schedule');

                    foreach ($allData['schedule'] as $schedule) {
                        $admin->CreateSchedule($schedule);
                    }
                    foreach ($allData['schedule'] as $schedule) {
                        $dir = explode('/', $schedule['video']);;
                        if (!file_exists('../public/' . $dir[2] . '/' . $dir[3])) {
                            mkdir('../public/' . $dir[2] . '/' . $dir[3], 0777, true);
                        }

                    }
                    foreach ($allData['schedule'] as $schedule) {
                        $dir = explode('/', $schedule['video']);
                        if (file_exists('../public/' . $dir[2] . '/' . $dir[3] . '/' . $dir[4])) {
                            $headers = get_headers(SERVER . 'public/' . $dir[2] . '/' . $dir[3] . '/' . $dir[4], 1);
                            $lowerCaseHeaders = array_change_key_case($headers);
                            $filesize = $lowerCaseHeaders['content-length'];
                            if (filesize('../public/' . $dir[2] . '/' . $dir[3] . '/' . $dir[4]) != $filesize) {
                                unlink('../public/' . $dir[2] . '/' . $dir[3] . '/' . $dir[4]);
                                file_put_contents('../public/' . $dir[2] . '/' . $dir[3] . '/' . $dir[4], fopen(SERVER . 'public/' . $dir[2] . '/' . $dir[3] . '/' . $dir[4], 'r'));
                            };
                        }
                        if (!file_exists('../public/' . $dir[2] . '/' . $dir[3] . '/' . $dir[4])) {
                            file_put_contents('../public/' . $dir[2] . '/' . $dir[3] . '/' . $dir[4], fopen(SERVER . 'public/' . $dir[2] . '/' . $dir[3] . '/' . $dir[4], 'r'));
                        }
                    }


                    $admin->resetData('advertising');

                    foreach ($allData['advertising'] as $advertising) {
                        $admin->CreateAdvertise($advertising);
                    }
                    foreach ($allData['advertising'] as $advertising) {
                        $data = unserialize($advertising['data']);

                        foreach ($data as $entry) {
                            if ($entry->data->type === 'video') {
                                $dir = explode('/', $entry->data->element);

                                if (!file_exists('../public/' . $dir[0] . '/' . $dir[1])) {
                                    mkdir('../public/' . $dir[0] . '/' . $dir[1], 0777, true);
                                }
                            };
                        };
                    }

                    foreach ($allData['advertising'] as $advertising) {
                        $data = unserialize($advertising['data']);
                        foreach ($data as $entry) {
                            if ($entry->data->type === 'video') {
                                $dir = explode('/', $entry->data->element);


                                if (file_exists('../public/' . $dir[0] . '/' . $dir[1] . '/' . $dir[2])) {
                                    $headers = get_headers(SERVER . 'public/' . $dir[0] . '/' . $dir[1] . '/' . $dir[2], 1);
                                    $lowerCaseHeaders = array_change_key_case($headers);
                                    $filesize = $lowerCaseHeaders['content-length'];
                                    if (filesize('../public/' . $dir[0] . '/' . $dir[1] . '/' . $dir[2]) != $filesize) {
                                        unlink('../public/' . $dir[0] . '/' . $dir[1] . '/' . $dir[2]);
                                        file_put_contents('../public/' . $dir[0] . '/' . $dir[1] . '/' . $dir[2], fopen(SERVER . 'public/' . $dir[0] . '/' . $dir[1] . '/' . $dir[2], 'r'));
                                    };
                                }


                                if (!file_exists('../public/' . $dir[0] . '/' . $dir[1] . '/' . $dir[2])) {
                                    file_put_contents('../public/' . $dir[0] . '/' . $dir[1] . '/' . $dir[2], fopen(SERVER . 'public/' . $dir[0] . '/' . $dir[1] . '/' . $dir[2], 'r'));
                                }
                            };
                        };
                    }

                    $admin->updateLocationLayout($allData['layout']);

                    $admin->resetData('advertising');
                    foreach ($allData['advertising'] as $advertise) {
                        $admin->CreateAdvertise($advertise);
                    }

                    header('location: ' . URL . 'player');
                    break;

                case "client":
                    header('location: ' . URL);
                    break;
                default:
                    header('location: ' . URL . 'client');
                    break;
            }
        } else {
            header('location: ' . URL . 'fail');
        }


    }

    public function logout() {
        session_destroy();
        header('location: ' . URL);
    }

}
