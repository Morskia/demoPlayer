<?php

namespace Mini\Model;

use Mini\Libs\Helper;
use Mini\Core\Model;

class Admin extends Model {


    public function entryLowetWhite() {

        $sql = "update `schedule` set `video` =  LOWER(REPLACE( `video`, ' ', '' ))";
        $query = $this->db->prepare($sql);
        $query->execute();
    }
	public function getAdvertising(){

        $sql   = "SELECT data FROM advertising WHERE `day` = :day";
		$query = $this->db->prepare( $sql );
		$parameters = array(
            ':day' =>    date ('l'),

        );
        $query->execute($parameters);
		echo json_encode( $query->fetchAll( \PDO::FETCH_ASSOC ) ,JSON_UNESCAPED_SLASHES);

    }

    public function resetData($taba) {
	    $sql = "TRUNCATE $taba ";
        $query = $this->db->prepare( $sql );
        $query->execute();
    }

	public function updateOldLayout($data) {
		$sql = "UPDATE locations SET layout = :layout";
		$query = $this->db->prepare($sql);
		$parameters = array(
			':layout' => serialize($data),

		);
		$query->execute($parameters);

	}

	public function CreateAdmin( $data ) {


		$sql        = "INSERT INTO users (id, username, contact_person, password, active, edit_video, edit_advertising, type, location_access, parent_admin, email, phone, 
                date_valid, address, company, dogovor, ip) 
                VALUES(:id, :username, :contact_person, :password, :active, :edit_video, :edit_advertising, :type, :location_access, :parent_admin, :email, :phone,
                 :date_valid, address, :company, :dogovor, :ip) 
            ON DUPLICATE KEY UPDATE id = :id , username = :username , contact_person = :contact_person , password = :password , active = :active , edit_video = :edit_video , 
            edit_advertising  =:edit_advertising , type  = :type, location_access  = :location_access , parent_admin = :parent_admin , email = :email , phone = :phone , 
                date_valid = :date_valid, address = :address, company = :company, dogovor = :dogovor, ip = :ip";
		$query      = $this->db->prepare( $sql );
		$parameters = array(
			':id'               => $data["id"],
			':username'         => $data["username"],
			':contact_person'   => $data["contact_person"],
			':password'         => $data["password"],
			':active'           => $data["active"],
			':edit_video'       => $data["edit_video"],
			':edit_advertising' => $data["edit_advertising"],
			':type'             => $data["type"],
			':location_access'  => $data["location_access"],
			':parent_admin'     => $data["parent_admin"],
			':email'            => $data["email"],
			':phone'            => $data["phone"],
			':date_valid'       => $data["date_valid"],
			':address'          => $data["address"],
			':company'          => $data["company"],
			':dogovor'          => $data["dogovor"],
			':ip'               => $data["ip"],
		);
		$query->execute( $parameters );
	}

	public function CreateLocation( $data ) {

		$sql        = "INSERT INTO locations (id, name, address, notes, receivers, belong_to, position, layout) 
                VALUES(:id, :name, :address, :notes, :receivers, :belong_to, :position, :layout) 
            ON DUPLICATE KEY UPDATE id = :id , name = :name , address = :address , notes = :notes , receivers = :receivers , belong_to = :belong_to , 
            position  =:position , layout  = :layout";
		$query      = $this->db->prepare( $sql );
		$parameters = array(
			':id'        => $data["id"],
			':name'      => $data["name"],
			':address'   => $data["address"],
			':notes'     => $data["notes"],
			':receivers' => $data["receivers"],
			':belong_to' => $data["belong_to"],
			':position'  => $data["position"],
			':layout'    => $data["layout"],
		);
		$query->execute( $parameters );

	}

	public function createSchedule( $data ) {
		$sql        = "INSERT INTO schedule (idx, video, day, locationName, id, unique_key) 
                VALUES(:idx, :video, :day, :locationName, :id, :unique_key) 
            ON DUPLICATE KEY UPDATE idx = :idx , video = :video , day = :day , locationName = :locationName , id = :id , unique_key = :unique_key";
		$query      = $this->db->prepare( $sql );
		$parameters = array(
			':idx'          => $data["idx"],
			':video'        => $data["video"],
			':day'          => $data["day"],
			':locationName' => $data["locationName"],
			':id'           => $data["id"],
			':unique_key'   => $data["unique_key"],

		);
		$query->execute( $parameters );
	}

    public function CreateAdvertise( $data ) {

        $sql        = "INSERT INTO advertising (day, data)   VALUES(:day, :data)";
        $query      = $this->db->prepare( $sql );
        $parameters = array(
            ':day'          => $data["day"],
            ':data'        => $data["data"],
        );
        $query->execute( $parameters );
    }

    public function updateLocationLayout($data) {
        $sql        = "UPDATE locations SET layout = :layout ";
        $query      = $this->db->prepare( $sql );
        $parameters = array(
            ':layout' =>  $data['layout'] ,
        );
        //   echo Helper::debugPDO($sql, $parameters); exit;
        $query->execute( $parameters );
    }

	public function getPlayList( $id, $date = null ) {
		$d     = new \Datetime();
		$day   = $d->format( 'l' );
		$sql   = "SELECT video FROM schedule WHERE `id` = :id AND `day` = :day ORDER BY idx ASC";
		$query = $this->db->prepare( $sql );

		$parameters = array(
			':day' => $date ?? $day,
			':id'  => $id

		);

		$query->execute( $parameters );
		if ( $date ) {
			echo json_encode( $query->fetchAll( \PDO::FETCH_ASSOC ) ,JSON_UNESCAPED_SLASHES);
		} else {
			return $query->fetchAll( \PDO::FETCH_NUM );
		}

	}

	public function getSchedule( $location = false ) {
		$filter = null;
		if ( $location ) {
			$filter = " WHERE id = $location";
		}
		$sql   = "SELECT * FROM schedule $filter";
		$query = $this->db->prepare( $sql );
		$query->execute();

		return $query->fetchAll( \PDO::FETCH_ASSOC );
	}

    public function updateIPaddress($ip){
        $sql = "UPDATE users SET ip = :ip ";
        $query = $this->db->prepare($sql);
        $parameters = array(
            ':ip' => $ip,
        );
        $query->execute($parameters);

        $id = $_SESSION['id'];

        echo "/admin/updateIP/$id/$ip";
        $url  = SERVER . "/admin/updateIP/$id/$ip";

        Helper::curlRequest($url);
    }

	public function updateLayout( $data, $id ) {
		$sql        = "UPDATE locations SET layout = :layout WHERE id = :id";
		$query      = $this->db->prepare( $sql );
		$parameters = array(
			':layout' => serialize( $data ),
			':id'     => $id
		);
		//   echo Helper::debugPDO($sql, $parameters); exit;
		$query->execute( $parameters );
		header( 'Location: ' . $_SERVER['HTTP_REFERER'] );
	}

	public function getLayout( $id = null ) {
		$sql        = "SELECT `layout` FROM locations   WHERE id = :id";
		$query      = $this->db->prepare( $sql );
		$parameters = array(

			':id' => $id ?? $_GET['location']
		);
		//   echo Helper::debugPDO($sql, $parameters); exit;
		$query->execute( $parameters );
		return $query->fetch();

	}

	public function updateSchedule( $data ) {


		if ( isset( $data['schedule'] ) ) {
			$partial    = $data['schedule'][ array_key_first( $data['schedule'] ) ];
			$sql        = "DELETE FROM schedule WHERE id = :id";
			$query      = $this->db->prepare( $sql );
			$parameters = array( ':id' => $partial['id'] );
			$query->execute( $parameters );

			foreach ( $data['schedule'] as $entry ) {
				$sql        = "INSERT INTO schedule  (video, day, locationName, id, unique_key)
                                 VALUES(:video, :day, :locationName, :id, :unique_key) ";
				$query      = $this->db->prepare( $sql );
				$parameters = array(
					':video'        => $entry["video"],
					':day'          => $entry["day"],
					':locationName' => $entry["locationName"],
					':id'           => $entry["id"],
					':unique_key'   => $entry["unique_key"],
				);
				$query->execute( $parameters );
			}
			$_SESSION['status_message'] = "Success!";
			header( 'Location: ' . $_SERVER['HTTP_REFERER'] );
		} else {
			$sql        = "DELETE FROM schedule WHERE id = :id";
			$query      = $this->db->prepare( $sql );
			$parameters = array( ':id' => $data['second_id'] );
			$query->execute( $parameters );
			$_SESSION['status_message'] = "Success!";
			header( 'Location: ' . $_SERVER['HTTP_REFERER'] );

		}
	}

	public function getLocation( $location ) {
		$sql   = "SELECT * FROM locations where `id` = $location LIMIT 1 ";
		$query = $this->db->prepare( $sql );
		$query->execute();

		return $query->fetch();
	}

	public function getLocalAdmin( $id ) {
		$sql   = "SELECT * FROM users where `location_access` = $id LIMIT 1 ";
		$query = $this->db->prepare( $sql );
		$query->execute();

		return ( $query->rowcount() ? $query->fetch() : 0 );
	}
}