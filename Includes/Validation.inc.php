<?php
include './Repository/dbAction.rep.php';

class Validation
{
    private $availableTime = array();

    public function nameHandling($name)
    {
        $result;

        if (!ctype_alpha($name)) {
            $result = false;
        } else {
            $result = true;
        }
        return $result;
    }

    public function emailHandling($email)
    {
        $result;
        $dbAction = new DatabaseAction();

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $result = false;
        } else {
            if ($dbAction->checkForEmail($email) == false) {
                $result = false;
            } else {
                $result = true;
            }
        }
        return $result;
    }

    public function phoneNumberHandling($phone)
    {
        $result;
        $dbAction = new DatabaseAction();

        $phoneInDigits = preg_replace('/[^0-9.]+/', '', $phone);
        $getPhoneCode = $phoneInDigits / 100000000;
        $roundPhoneCode = floor($getPhoneCode);

        if (preg_match("/^[0-9]{11}$/", $phoneInDigits)) {


            if ($roundPhoneCode != 370) {
                $result = false;
            } else {
                if ($dbAction->checkForPhone($phone) == false) {
                    $result = false;
                } else {
                    $result = true;
                }
            }
        } else {
            $result = false;
        }

        return $result;
    }

    private function currentDate()
    {
        date_default_timezone_set('Europe/Kiev');
        $currentDate = date("Y-m-d", time());

        return $currentDate;
    }

    private function validIdentificationNumber($manIndex, $womanIndex, $dateArray, $startOfCentury, $endOfCentury, $currentDate, $startOfCenturyIndex, $endOfCenturyIndex)
    {
        $result;

        if ($dateArray[10] == $manIndex || $dateArray[10] == $womanIndex) {
            $firstDate = $startOfCenturyIndex . $dateArray[9] . $dateArray[8] . "-"  . $dateArray[7] . $dateArray[6] . "-" . $dateArray[5] . $dateArray[4];
            $secondDate = $endOfCenturyIndex . $dateArray[9] . $dateArray[8] . "-"  . $dateArray[7] . $dateArray[6] . "-" . $dateArray[5] . $dateArray[4];

            if ($firstDate >= $startOfCentury && $firstDate <= $endOfCentury && $firstDate <= $currentDate) {
                $result = true;
            } else {
                if ($secondDate >= $startOfCentury && $secondDate <= $endOfCentury && $secondDate <= $currentDate) {
                    $result = true;
                } else {
                    $result = false;
                }
            }
        }
        return $result;
    }

    public function identificationNumberHandling($identificationNumber, $caseNumber)
    {
        $result;
        $dbAction = new DatabaseAction();

        $startOf20Century = "1901-01-01";
        $endOf20Century = "2000-12-31";
        $startOf21Century = "2001-01-01";
        $endOf21Century = "2100-12-31";

        $manIndex20cn = 3;
        $womanIndex20cn = 4;
        $manIndex21cn = 5;
        $womanIndex21cn = 6;

        $currentDate = $this->currentDate();

        $identityNumber = $identificationNumber;
        $backwardsIdArray = array();

        if (preg_match("/^[0-9]{11}$/", $identificationNumber)) {

            for ($i = 0; $i < 11; $i++) {
                $digit = $identityNumber % 10;
                $currentID = $identityNumber / 10;
                $identityNumber = floor($currentID);

                $backwardsIdArray[] = $digit;
            }

            $monthByID = $backwardsIdArray[7] . $backwardsIdArray[6];
            $dayByID = $backwardsIdArray[5] . $backwardsIdArray[4];

            $daysInMonth = $this->getDaysByMonth($monthByID);

            if ($monthByID <= 12 && $dayByID <= $daysInMonth) {
                if ($backwardsIdArray[10] == $manIndex20cn || $backwardsIdArray[10] == $womanIndex20cn) {
                    if ($this->validIdentificationNumber($manIndex20cn, $womanIndex20cn, $backwardsIdArray, $startOf20Century, $endOf20Century, $currentDate, 19, 20) == true) {
                        $isExistingInDb = $dbAction->checkForIdentityNumber($identificationNumber);
                        switch ($caseNumber) {
                            case true:
                                $result = ($isExistingInDb === true ? true : false);
                                break;
                            case false:
                                $result = ($isExistingInDb === true ? false : true);
                                break;
                            default:
                                echo "Warning in identificationNumberHandling method";
                                break;
                        }
                    }
                } elseif ($backwardsIdArray[10] == $manIndex21cn || $backwardsIdArray[10] == $womanIndex21cn) {
                    if ($this->validIdentificationNumber($manIndex21cn, $womanIndex21cn, $backwardsIdArray, $startOf21Century, $endOf21Century, $currentDate, 20, 21) == true) {
                        $isExistingInDb = $dbAction->checkForIdentityNumber($identificationNumber);
                        switch ($caseNumber) {
                            case true:
                                $result = ($isExistingInDb === true ? true : false);
                                break;
                            case false:
                                $result = ($isExistingInDb === true ? false : true);
                                break;
                            default:
                                echo "Warning in identificationNumberHandling method";
                                break;
                        }
                    }
                }
            }
        } else {
            $result = false;
        }
        return $result;
    }

    private function getDaysByMonth($currentMonth)
    {
        $currentYear = date("Y");
        $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $currentMonth, $currentYear);

        return $daysInMonth;
    }

    private function getDefaultTimeArray()
    {
        date_default_timezone_set('Europe/Kiev');
        $time = "07:00";
        $timeArray = array();

        while ($time != "17:00") {
            $result = strtotime("+1 hour", strtotime($time));
            $time = date('H:i', $result);
            $timeArray[] = $time;
        }
        return $timeArray;
    }

    public function getAvailableTime()
    {
        return $this->availableTime;
    }

    public function dateValidation($date)
    {
        $result;
        $dbAction = new DatabaseAction();

        $currentDate = $this->currentDate();
        $weekFromCurrentDate = date("Y-m-d", strtotime($currentDate . "+7 day"));

        if ($date > $weekFromCurrentDate || $date < $currentDate) {
            $result = false;
        } else {
            if ($dbAction->checkForTimes($date) == true) {
                $dbTimeArray = $dbAction->getTimeByDate($date);
                $defaultTimeArray = $this->getDefaultTimeArray();
                $this->availableTime = array_diff($defaultTimeArray, $dbTimeArray);
                $result = true;
            } else {
                $result = false;
            }
        }
        return $result;
    }

    public function timeValidation($time)
    {
        $result;

        if (preg_match("/^(?:2[0-3]|[01][0-9]):[0-5][0-9]$/", $time)) {
            if (in_array($time, $this->availableTime)) {
                $result = true;
            } else {
                $result = false;
            }
        } else {
            $result = false;
        }
        return $result;
    }
}
