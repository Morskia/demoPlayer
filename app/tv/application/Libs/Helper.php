<?php

namespace Mini\Libs;

class Helper
{

    static public function debugPDO($raw_sql, $parameters) {
        $keys = array();
        $values = $parameters;

        foreach ($parameters as $key => $value) {
            // check if named parameters (':param') or anonymous parameters ('?') are used
            if (is_string($key)) {
                $keys[] = '/' . $key . '/';
            } else {
                $keys[] = '/[?]/';
            }
            // bring parameter into human-readable format
            if (is_string($value)) {
                $values[$key] = "'" . $value . "'";
            } elseif (is_array($value)) {
                $values[$key] = implode(',', $value);
            } elseif (is_null($value)) {
                $values[$key] = 'NULL';
            }
        }
        $raw_sql = preg_replace($keys, $values, $raw_sql, 1, $count);
        return $raw_sql;
    }


    static public function curlRequest($url) {
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        $response = curl_exec($curl);
        curl_close($curl);

        return $response;
    }

    static public function excerpt($str, $max = 50, $char = ' ', $end = '...') {
        $str = trim($str);
        $str = $str . $char;
        $len = strlen($str);
        $words = '';
        $w = '';
        $c = 0;
        for ($i = 0; $i < $len; $i++)
            if ($str[$i] != $char)
                $w = $w . $str[$i];
            else
                if (($w != $char) and ($w != '')) {
                    $words .= $w . $char;
                    $c++;
                    if ($c >= $max) {
                        break;
                    }
                    $w = '';
                }
        if ($i + 1 >= $len) {
            $end = '';
        }
        return trim($words) . $end;
    }

    static public function thumbnails($img, $prefix = 'thumbnails') {
        return URL . str_replace('publications', $prefix, $img);
    }

    static public function big($img, $prefix = 'publications') {

        return str_replace('thumbnails', $prefix, $img);
    }

    static public function createPermalink($string) {
        $lower = mb_strtolower(str_replace(' ', '-', trim($string)), 'UTF-8');
        return str_replace(array(':', '?', '&', '*', '@', '/', '.', '%'), '', $lower);
    }

    static public function stringToDate($string) {

        $dtime = \DateTime::createFromFormat("Y-m-d", $string);
        return $dtime->getTimestamp();
    }

    static public function timestampToDate($timestamp) {
        $date = new \DateTime();
        $date->setTimestamp($timestamp);
        return $date->format('Y-m-d');
    }


    static public function prettyArray($arr) {
        echo '<pre>' . print_r($arr, true) . '</pre>';
    }


}
