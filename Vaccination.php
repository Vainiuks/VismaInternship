<?php
include './Includes/VaccinationActions.inc.php';

$userStatus = 0;
$currentAction = "";

while ($userStatus == 0) {
    echo "\nMenu: \n\n";
    // if ($userStatus == 0) {
        echo "Enter 'Register' for registration for vaccination\n";
        echo "Enter 'Export' for exporting database data to csv file\n";
        echo "Enter 'Import' for importing data to database\n";
        echo "Enter 'Edit' for editing registration fields \n";
        echo "Enter 'Delete' for deleting registration\n";
        echo "Enter 'SortASC' for printing list of appointments by specific day\n\n";
        echo "Enter 'Exit' to exit program\n\n";

        $handleAction = fopen("php://stdin", "r");
        $currentAction = fgets($handleAction);

        while (trim($currentAction) == null) {
            echo "Error: There is no such thing in menu!\n\n";
            $handleAction = fopen("php://stdin", "r");
            $currentAction = fgets($handleAction);
        }
        
        $Vaccination = new VaccinationActions();
        switch (strtolower(trim($currentAction))) {
            case "register":
                $Vaccination->Register(); 
                break;
            case "export":
                $Vaccination->Export();
                break;
            case "import":
                $Vaccination->Import();
                break;
            case "edit":
                $Vaccination->Edit(); 
                break;
            case "delete":
                $Vaccination->Delete();
                break;
            case "sortasc":
                $Vaccination->SortASC();
                break;
            case "exit":
                $userStatus = 1;
                break;
            default:
                echo "Wrong input!!!";
        }
    // }
}
