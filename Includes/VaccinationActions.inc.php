<?php
include 'Validation.inc.php';

class VaccinationActions
{
    public function Register()
    {
        $validation = new Validation();
        $dbAction = new DatabaseAction(); 

        echo "\nEnter your Name: \n";
        $handleName = fopen("php://stdin", "r");
        $handledName = fgets($handleName);

        while (trim($handledName) == null || $validation->nameHandling(trim($handledName)) == null) {
            echo "***Error***\n***Field cannot be empty***\n***OR Name only contains letters***";
            echo "\nEnter your Name: \n";
            $handleName = fopen("php://stdin", "r");
            $handledName = fgets($handleName);
        }

        echo "\nEnter your Email: \n";
        $handleEmail = fopen("php://stdin", "r");
        $handledEmail = fgets($handleEmail);

        while (trim($handledEmail) == null || $validation->emailHandling(trim($handledEmail)) == null) {
            echo "***Error***\n***Field cannot be empty***\n***OR Email is invalid***\n***OR Email is already taken***";
            echo "\nEnter your Email: \n";
            $handleEmail = fopen("php://stdin", "r");
            $handledEmail = fgets($handleEmail);
        }

        echo "\nEnter your Phone number (LT code - 370): \n";
        $handlePhoneNumber = fopen("php://stdin", "r");
        $handledPhoneNumber = fgets($handlePhoneNumber);

        while (trim($handledPhoneNumber) == null || $validation->phoneNumberHandling(trim($handledPhoneNumber)) == null) {
            echo "***Error***\n***Field cannot be empty***\n***OR Phone number is invalid***\n***OR Phone Number is already taken***";
            echo "\nEnter your Phone number: \n";
            $handlePhoneNumber = fopen("php://stdin", "r");
            $handledPhoneNumber = fgets($handlePhoneNumber);
        }

        echo "\nEnter your National identification number: \n";
        $handleIdentityNumber = fopen("php://stdin", "r");
        $handledIdentityNumber = fgets($handleIdentityNumber);

        while (trim($handledIdentityNumber) == null || $validation->identificationNumberHandling(trim($handledIdentityNumber), false) == null) {
            echo "***Error***\n***Field cannot be empty***\n***OR National identification number is invalid***\n***OR Identity Number is already taken***";
            echo "\nEnter your National identification number: \n";
            $handleIdentityNumber = fopen("php://stdin", "r");
            $handledIdentityNumber = fgets($handleIdentityNumber);
        }

        echo "\nEnter picked Date from range[Current date to +7 days] (for example: 2022-12-16): \n";
        $handleDate = fopen("php://stdin", "r");
        $handledDate = fgets($handleDate);

        while (trim($handledDate) == null || $validation->dateValidation(trim($handledDate)) == false) {
            echo "***Error***\n***Field cannot be empty***\n***OR Date format is invalid***\n***OR There is no more left places for registration[Choose another day]***";
            echo "\nEnter picked Date from range[Current date to +7 days] (Date format example: 2022-12-16): \n";
            $handleDate = fopen("php://stdin", "r");
            $handledDate = fgets($handleDate);
        }

        echo "\nChoose and Enter time (Time format example: 14:00): \n";
        print_r($validation->getAvailableTime());
        echo "Enter time - ";
        $handleTime = fopen("php://stdin", "r");
        $handledTime = fgets($handleTime);

        while (trim($handledTime) == null || $validation->timeValidation(trim($handledTime)) == null) {

            echo "Error: you must fill the Time field!";
            echo "\nChoose and Enter time (Time format example: 14:00): \n";
            print_r($validation->getAvailableTime());
            echo "Enter time - ";
            $handleTime = fopen("php://stdin", "r");
            $handledTime = fgets($handleTime);
        }

        $dbAction->insertNewAppointment(trim($handledName), trim($handledEmail), trim($handledPhoneNumber), trim($handledIdentityNumber), trim($handledDate), trim($handledTime));
    }

    public function Delete()
    {
        $validation = new Validation();
        $dbAction = new DatabaseAction();

        echo "Enter your Nation identification number to DELETE appointment\n\n";
        $handleIdentityNumber = fopen("php://stdin", "r");
        $handledIdentityNumber = fgets($handleIdentityNumber);

        while (trim($handledIdentityNumber) == null || $validation->identificationNumberHandling(trim($handledIdentityNumber), true) == null) {
            echo "***Error***\n***Field cannot be empty***\n***OR National identification number is invalid***";
            echo "\nEnter your National identification number: \n";
            $handleIdentityNumber = fopen("php://stdin", "r");
            $handledIdentityNumber = fgets($handleIdentityNumber);
        }
        
        $dbAction->deleteAppointment(trim($handledIdentityNumber));
    }
    public function Edit()
    {
        $dbAction = new DatabaseAction();
        $validation = new Validation();

        echo "\nEnter your National identification number to edit your appointment: \n";
        $getIdentityNumber = fopen("php://stdin", "r");
        $identityNumber = fgets($getIdentityNumber);

        while (trim($identityNumber) == null || $validation->identificationNumberHandling(trim($identityNumber), true) == null) {
            echo "***Error***\n***Field cannot be empty***\n***OR National identification number is invalid***";
            echo "\nEnter your National identification number: \n";
            $getIdentityNumber = fopen("php://stdin", "r");
            $identityNumber = fgets($getIdentityNumber);
        }

        if (trim($identityNumber) != null) {

            echo "\nEnter new Name if you want to change it, if not [LEAVE IF EMPTY AND PRESS ENTER]: \n";
            $handleName = fopen("php://stdin", "r");
            $handledName = fgets($handleName);

            if (trim($handledName) != null) {
                while ($validation->nameHandling(trim($handledName)) == false) {
                    echo "***Error***\n***Name only contains letters***";
                    echo "\nEnter your Name: \n";
                    $handleName = fopen("php://stdin", "r");
                    $handledName = fgets($handleName);
                }
            }

            echo "\nEnter your new Email if you want to change it, if not [LEAVE IT EMPTY AND PRESS ENTER]: \n";
            $handleEmail = fopen("php://stdin", "r");
            $handledEmail = fgets($handleEmail);

            if (trim($handledEmail) != null) {
                while ($validation->emailHandling(trim($handledEmail)) == false) {
                    echo "***Error***\n***Email is invalid***";
                    echo "\nEnter your Email: \n";
                    $handleEmail = fopen("php://stdin", "r");
                    $handledEmail = fgets($handleEmail);
                }
            }

            echo "\nEnter your  new Phone number (LT code - 370) if you want to change it, if not [LEAVE IT EMPTY AND PRESS ENTER]: \n";
            $handlePhoneNumber = fopen("php://stdin", "r");
            $handledPhoneNumber = fgets($handlePhoneNumber);

            if (trim($handledPhoneNumber) != null) {
                while ($validation->phoneNumberHandling(trim($handledPhoneNumber)) == false) {
                    echo "***Error***\n***Phone number is invalid***";
                    echo "\nEnter your Phone number: \n";
                    $handlePhoneNumber = fopen("php://stdin", "r");
                    $handledPhoneNumber = fgets($handlePhoneNumber);
                }
            }

            echo "\nEnter your new National identification number if you want toc change it, if not [LEAVE IT EMPTY AND PRESS ENTER]: \n";
            $handleIdentityNumber = fopen("php://stdin", "r");
            $handledIdentityNumber = fgets($handleIdentityNumber);

            if (trim($handledIdentityNumber) != null) {
                while ($validation->identificationNumberHandling(trim($handledIdentityNumber), false) == null) {
                    echo "***Error***\n***National identification number is invalid***";
                    echo "\nEnter your National identification number: \n";
                    $handleIdentityNumber = fopen("php://stdin", "r");
                    $handledIdentityNumber = fgets($handleIdentityNumber);
                }
            }

            echo "\nEnter new Date from range[Current date to +7 days] (for example: 2022-12-16): \n";
            $handleDate = fopen("php://stdin", "r");
            $handledDate = fgets($handleDate);

            while ($validation->dateValidation(trim($handledDate)) == null) {
                echo "***Error***\n***Date format is invalid***\n***OR There is no more left places for registration[CHOOSE ANOTHER DAY]***";
                echo "\nEnter picked Date from range[Current date to +7 days] (Date format example: 2022-12-16): \n";
                $handleDate = fopen("php://stdin", "r");
                $handledDate = fgets($handleDate);
            }

            echo "\nChoose and Enter time (Time format example: 14:00)\n";
            print_r($validation->getAvailableTime());
            echo "Enter time - ";
            $handleTime = fopen("php://stdin", "r");
            $handledTime = fgets($handleTime);

            while ($validation->timeValidation(trim($handledTime)) == null) {
                print_r($validation->getAvailableTime());
                echo "***Error***\n***Wrong time format***\n***Time is not available at the moment***\n";
                echo "\nChoose and Enter time (Time format example: 14:00)\n";
                echo "Enter time - ";
                $handleTime = fopen("php://stdin", "r");
                $handledTime = fgets($handleTime);
            }
        }

        $dbAction->editAppointment(trim($handledName), trim($handledEmail), trim($handledPhoneNumber), trim($identityNumber), trim($handledIdentityNumber), trim($handledDate), trim($handledTime));
    }
    public function SortASC()
    {
        $dbAction = new DatabaseAction();
        $validation = new Validation();

        echo "\nEnter Date to get sorted records by this day (Date format example: 2021-11-17): \n";
        $handleDate = fopen("php://stdin", "r");
        $handledDate = fgets($handleDate);

        while (trim($handledDate) == null) {
            echo "***Error***\n***Field cannot be empty***\n***OR Date format is invalid***\n***OR There is no records by this day***";
            echo "\nEnter Date to get sorted records by this day (Date format example: 2021-11-17): \n";
            $handleDate = fopen("php://stdin", "r");
            $handledDate = fgets($handleDate);
        }

        $sortedAppointments = $dbAction->listOfAppointments(trim($handledDate));
        echo "Sorted list:\n";
        foreach ($sortedAppointments as $row) {
            echo $row['vaccineID'] . " / ";
            echo $row['name'] . " / ";
            echo $row['email'] . " / ";
            echo $row['phoneNumber'] . " / ";
            echo $row['identityNumber'] . " / ";
            echo $row['date'] . " / ";
            echo $row['time'] . " / ";
            echo "\n";
        }
    }

    public function Export()
    {
        $dbAction = new DatabaseAction();
        $dbAction->exportToCsvFile();
    }

    public function Import()
    {
        $dbAction = new DatabaseAction();
        $dbAction->importCsvToDatabase();
    }
}
