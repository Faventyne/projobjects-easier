<?php

// Autoload PSR-4
spl_autoload_register();

// Imports 
use \Classes\Webforce3\Config\Config;
use \Classes\Webforce3\DB\Session;

// Get the config object
$conf = Config::getInstance();

//TODO : S'inspirer de la construction du fichier controller student.php pour terminer le projet
$sessionId = isset($_GET['ses_id']) ? intval($_GET['ses_id']) : 0;
$sessionObject = new Session();

// Récupère la liste complète des students en DB
$studentList = Student::getAllForSelect();
// Récupère la liste complète des cities en DB
$sessionsList = City::getAllForSelect();
// Récupère la liste complète des sessions en DB
$sessionsList = Session::getAllForSelect();


// Formulaire soumis
if(!empty($_POST)) {
    $sessionId = isset($_POST['id']) ? intval($_POST['id']) : 0;
    $sessionStartDate = isset($_POST['ses_start_date']) ? trim($_POST['ses_start_date']) : '';
    $sessionEndDate = isset($_POST['ses_end_date']) ? trim($_POST['ses_end_date']) : '';
    $sessionNumber = isset($_POST['ses_number']) ? trim($_POST['ses_number']) : '';
    $trainingId = isset($_POST['tra_id']) ? trim($_POST['tra_id']) : '';
    $locationId = isset($_POST['loc_id']) ? trim($_POST['loc_id']) : '';

    if (empty($countryName)) {
        $conf->addError('Veuillez renseigner le nom');
    }

    // je remplis l'objet qui est lu pour les inputs du formulaire, ou pour l'ajout en DB
    $countryObject = new Country(
        $countryId,
        $countryName
    );

    // Si tout est ok => en DB
    if (!$conf->haveError()) {
        if ($countryObject->saveDB()) {
            header('Location: country.php?success='.urlencode('Ajout/Modification effectuée').'&cou_id='.$countryObject->getId());
            exit;
        }
        else {
            $conf->addError('Erreur dans l\'ajout ou la modification');
        }
    }
}

$selectCountries = new SelectHelper($countriesList, $countryId, array(
    'name' => 'cou_id',
    'id' => 'cou_id',
    'class' => 'form-control',
));

// Views - toutes les variables seront automatiquement disponibles dans les vues
require $conf->getViewsDir().'header.php';
require $conf->getViewsDir().'session.php';
require $conf->getViewsDir().'footer.php';