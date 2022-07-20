<?php

namespace Mini\Model;

use Mini\Core\Model;
use Mini\Libs\Helper;

class Home extends Model
{


    public function isFirstTime() {

        $sql = "SHOW TABLES LIKE '%users%'";
        $query = $this->db->prepare($sql);
        $query->execute();
        $result = $query->fetch();

        if (!$result) {
            $success = 0;
            $files = array('users.sql', 'locations.sql', 'schedule.sql', 'advertising.sql');
            foreach ($files as $file) {
                $filename = __DIR__ . '\\' . $file;
                $maxRuntime = 8; // less then your max script execution limit
                $deadline = time() + $maxRuntime;
                $progressFilename = $filename . '_filepointer'; // tmp file for progress
                $errorFilename = $filename . '_error'; // tmp file for erro
                ($fp = fopen($filename, 'r')) OR die('failed to open file:' . $filename);
                // check for previous error
                if (file_exists($errorFilename)) {
                    die('<pre> previous error: ' . file_get_contents($errorFilename));
                }
                // activate automatic reload in browser
                // go to previous file position
                $filePosition = 0;
                if (file_exists($progressFilename)) {
                    $filePosition = file_get_contents($progressFilename);
                    fseek($fp, $filePosition);
                }
                $queryCount = 0;
                $query = '';
                while ($deadline > time() AND ($line = fgets($fp, 1024000))) {
                    if (substr($line, 0, 2) == '--' OR trim($line) == '') {
                        continue;
                    }
                    $query .= $line;

                    if (substr(trim($query), -1) == ';') {
                        $igweze_prep = $this->db->prepare($query);
                        if (!($igweze_prep->execute())) {
                            $error = 'Error performing query \'<strong>' . $query . '\': ' . print_r($this->db->errorInfo());
                            file_put_contents($errorFilename, $error . "\n");
                            exit;
                        }
                        $query = '';
                        file_put_contents($progressFilename, ftell($fp)); // save the current file position for
                        $queryCount++;
                    }
                }
                if (feof($fp)) {
                    $success++;
                    unlink($filename . '_filepointer');
                } else {
                    // echo ftell($fp) . '/' . filesize($filename) . ' ' . (round(ftell($fp) / filesize($filename), 2) * 100) . '%' . "\n";

                }
                if ($success === 3) {
                    header("Location:  setup");
                }
            }
        } else {
            header("Location: login");
        }

    }
}
