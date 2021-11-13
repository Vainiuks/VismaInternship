<?php

class DatabaseAction
{

    public function getTimeByDate($date)
    {
        include 'dbConfiguration.rep.php';
        $query = "SELECT time FROM vaccine WHERE date='" . $date . "'";
        $result = $connection->query($query);
        $timeArray = array();

        while ($row = $result->fetch_assoc()) {
            $timeArray[] = $row['time'];
        }
        return $timeArray;
    }

    public function insertNewAppointment($name, $email, $phone, $identityNumber, $date, $time)
    {
        include 'dbConfiguration.rep.php';
        $query = "INSERT INTO vaccine(name, email, phoneNumber, identityNumber, date, time) VALUES('" . $name . "', '" . $email . "', '" . $phone . "', '" . $identityNumber . "', '" . $date . "', '" . $time . "')";
        if (!$connection->query($query) === true) {
            echo "SQL Error: " . $connection->error;
        } else {
            echo "\nNew appointment has been created\n\n";
        }
        $connection->close();
    }

    public function editAppointment($name, $email, $phone, $validIdentityNumber, $upToChangeIdentityNumber, $date, $time)
    {
        include 'dbConfiguration.rep.php';

        $query = "SELECT * FROM vaccine WHERE identityNumber = '" . $validIdentityNumber . "' ";
        $result = $connection->query($query);
        $vaccineID = "";
        $newName = "";
        $newEmail = "";
        $newPhone = "";
        $newIdentityNumber = "";
        $newDate = $date;
        $newTime = $time;

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $vaccineID = $row["vaccineID"];
                $rowName = $row["name"];
                $rowEmail = $row["email"];
                $rowPhoneNumber = $row["phoneNumber"];
                $rowIdentityNumber = $row["identityNumber"];
                $rowDate = $row["date"];
                $rowTime = $row["time"];
            }
        } else {
            echo "There is no such registration with this National identification number " . $validIdentityNumber . "\n";
        }

        if ($name != null) {
            $newName = $name;
        } else {
            $newName = $rowName;
        }
        if ($email != null) {
            $newEmail = $email;
        } else {
            $newEmail = $rowEmail;
        }
        if ($phone != null) {
            $newPhone = $phone;
        } else {
            $newPhone = $rowPhoneNumber;
        }
        if ($newIdentityNumber != null) {
            $newIdentityNumber = $upToChangeIdentityNumber;
        } else {
            $newIdentityNumber = $rowIdentityNumber;
        }

        $query = "UPDATE vaccine SET name='" . $newName . "', email='" . $newEmail . "', phoneNumber='" . $newPhone . "', identityNumber='" . $newIdentityNumber . "', date='" . $newDate . "', time='" . $newTime . "' WHERE vaccineID = '" . $vaccineID . "'";
        if (!$connection->query($query) === true) {
            echo "SQL Error: " . $connection->error;
        } else {
            echo "Appointment updated!!!\n\n";
        }
    }

    public function deleteAppointment($identityNumber)
    {
        include 'dbConfiguration.rep.php';
        $query = "DELETE FROM vaccine WHERE identityNumber = '" . $identityNumber . "' ";
        if (!$connection->query($query) === true) {
            echo "SQL Error: " . $connection->error;
        } else {
            echo "Appointment deleted\n\n";
        }

        $connection->close();
    }

    public function listOfAppointments($date)
    {
        include 'dbConfiguration.rep.php';
        $query = "SELECT * FROM vaccine WHERE date='" . $date . "' ORDER BY time ASC";
        $result = $connection->query($query);
        $sortedAppointments = array();

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $sortedAppointments[] = $row;
            }
        } else {
            echo "\nThere is no records about this day";
        }

        return $sortedAppointments;
    }

    public function exportToCsvFile()
    {
        include 'dbConfiguration.rep.php';
        $query = "SELECT * FROM vaccine";
        $result = $connection->query($query);

        $f = fopen("vaccination.csv", 'w');

        $fields = array('ID', 'NAME', 'EMAIL', 'PHONE NUMBER', 'IDENTIFICATION NUMBER', 'DATE', 'TIME');
        fputcsv($f, $fields);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                fputcsv($f, array($row["vaccineID"], $row["name"], $row["email"], $row["phoneNumber"], $row["identityNumber"], $row["date"], $row["time"]));
            }
        } else {
            echo "Empty database";
        }
    }

    public function importCsvToDatabase()
    {
        include 'dbConfiguration.rep.php';
        $result;

        $csvFile = fopen("import.csv", 'r');

        fgetcsv($csvFile);

        while (($line = fgetcsv($csvFile)) !== FALSE) {
            $queryResult = $connection->query("SELECT * FROM vaccine WHERE email = '" . $line[1] . "' OR phoneNumber='" . $line[2] . "' OR identityNumber = '" . $line[3] . "' OR date = '" . $line[4] . "' AND time = '" . $line[5] . "'");
            if ($queryResult->num_rows > 0) {
                echo "Error - there is same records in database";
            } else {
                $name = $line[0];
                $email = $line[1];
                $phoneNumber = $line[2];
                $identityNumber = $line[3];
                $date = $line[4];
                $time = $line[5];

                $connection->query("INSERT INTO vaccine (name, email, phoneNumber, identityNumber, date, time) VALUES ('" . $name . "', '" . $email . "', '" . $phoneNumber . "', '" . $identityNumber . "', '" . $date . "', '" . $time . "')");
            }
        }
        fclose($csvFile);
    }

    public function checkForEmail($email)
    {
        include 'dbConfiguration.rep.php';
        $result;
        $queryResult = $connection->query("SELECT email FROM vaccine WHERE email='" . $email . "'");

        if ($queryResult->num_rows > 0) {
            $result = false;
        } else {
            $result = true;
        }
        return $result;
    }

    public function checkForPhone($phone)
    {
        include 'dbConfiguration.rep.php';
        $result;
        $queryResult = $connection->query("SELECT phoneNumber FROM vaccine WHERE phoneNumber='" . $phone . "'");

        if ($queryResult->num_rows > 0) {
            $result = false;
        } else {
            $result = true;
        }
        return $result;
    }

    public function checkForIdentityNumber($identityNumber)
    {
        include 'dbConfiguration.rep.php';
        $result;
        $queryResult = $connection->query("SELECT identityNumber FROM vaccine WHERE identityNumber='" . $identityNumber . "'");

        if ($queryResult->num_rows > 0) {
            $result = true;
        } else {
            $result = false;
        }
        return $result;
    }

    public function checkForTimes($date)
    {
        include 'DbConfiguration.rep.php';
        $result;
        $queryResult = $connection->query("SELECT time FROM vaccine WHERE date='" . $date . "'");

        if ($queryResult->num_rows == 10) {
            $result = false;
        } else {
            $result = true;
        }
        return $result;
    }
}
