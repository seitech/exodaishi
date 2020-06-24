<?php

// activation du système d'autoloading de Composer
require __DIR__.'/../vendor/autoload.php';

// instanciation du chargeur de templates
$loader = new \Twig\Loader\FilesystemLoader(__DIR__.'/../templates');

// instanciation du moteur de template
$twig = new \Twig\Environment($loader, [
    // activation du mode debug
    'debug' => true,
    // activation du mode de variables strictes
    'strict_variables' => true,
]);

// chargement de l'extension Twig_Extension_Debug
$twig->addExtension(new \Twig\Extension\DebugExtension());

// créatation des variables
$formData = [
    'name' => '',
    'description'  => '',
    'price' => '',
    'quantity' => '',
];




if ($_POST) {
    $errors = [];
    $messages = [];

     // remplacement des valeur par défaut par celles de l'utilisateur
     if (isset($_POST['name'])) {
        $formData['name'] = $_POST['name'];
    }

    if (isset($_POST['description'])) {
        $formData['description'] = $_POST['description'];
    }

    if (isset($_POST['price'])) {
        $formData['price'] = $_POST['price'];
    }

    if (isset($_POST['quantity'])) {
        $formData['quantity'] = $_POST['quantity'];
    }

    
    
    // validation des renseignements (name) rentrer par l'utilisateur
    if (!isset($_POST['name']) || empty($_POST['name'])) {
        $errors['name'] = true;
        $messages['name'] = "Merci de renseigner le nom de l'article";
    } elseif (strlen($_POST['name']) < 2) {
        $errors['name'] = true;
        $messages['name'] = "Le nom de l'article doit faire 2 caractères minimum";
    } elseif (strlen($_POST['name']) > 100) {
        $errors['name'] = true;
        $messages['name'] = "Le nom de l'article doit faire 100 caractères maximum";
    }
    // validation de la description rentrer par l'utilisateur
    if (isset($_POST['description'])) {
        if (
            strpos($_POST['description'], '<') !== false
            || strpos($_POST['description'], '>') !== false
        ) {
            $errors['description'] = true;
            $messages['description'] = "Les caractères suivants sont interdits : < > pour la description";
        }
    }
    // validation du champs price
    if (isset($_POST['price'])) {
        if (!isset($_POST['price']) || empty($_POST['price'])) {
            $errors['price'] = true;
            $messages['price'] = "Merci de renseigner le prix de l'article";
        }elseif (!is_numeric($_POST['price'])) {
            $errors['price'] = true;
            $messages['price'] = "Merci de renseigner le prix de l'article en nombre";
        }elseif ($_POST['price'] <= 0) {
            $errors['price'] = true;
            $messages['price'] = "Merci de renseigner un prix positif, on ne fait pas dans la charité";
        }
    }
    // validation du champs Quantity
    if (isset($_POST['quantity'])) {
        if (!isset($_POST['quantity']) || empty($_POST['quantity'])) {
            $errors['quantity'] = true;
            $messages['quantity'] = "Merci de renseigner la quantité d'article";
        }elseif (!is_numeric($_POST['quantity']) && ($_POST['number'] - floor($_POST['number'])) == 0) {
            $errors['quantity'] = true;
            $messages['quantity'] = "Merci de renseigner une quantité d'article sans virgule et sans lettre";
        }elseif ($_POST['quantity'] <= 0) {
            $errors['quantity'] = true;
            $messages['quantity'] = "Merci de renseigner une quantité d'article en positif";
        }elseif (((!is_int(0 + $_POST['quantity'])))){
            $errors['quantity'] = true;
            $messages['quantity'] = "Merci de renseigner une quantité d'article sans point";
        }
    }
    if (($errors ['name'] == false) && ($errors ['description'] == false) && ($errors ['price'] == false) && ($errors ['quantity'] == false)){
        // renvoie vers la page articles
    $url = 'articles.php';
    header("Location: {$url}", true, 302);
    exit();
    }
}
// affichage du rendu d'un template
echo $twig->render('article-new.html.twig', [
    // transmission de données au template
    'errors' => $errors,
    'messages' => $messages,
    'formData' => $formData,
]);