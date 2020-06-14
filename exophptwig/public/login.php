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

session_start();

$user = require __DIR__.'/user-data.php';

$user = [
    'login' => ' ',
    'password'  => ' ',
];

if ($_POST) {
    $errors = [];
    $messages = [];

    // remplacement des valeur par défaut par celles de l'utilisateur
    if (isset($_POST['login'])) {
        $user['login'] = $_POST['login'];
    }

    if (isset($_POST['password'])) {
        $user['password'] = $_POST['password'];
    }

    // validation des données envoyées par l'utiilisateur
    // validation du login

    if (!isset($_POST['login']) || empty($_POST['login'])) {
        $errors['login'] = true;
        $messages['login'] = "Merci de renseigner votre login";
    } elseif (strlen($_POST['login']) < 4) {
        $errors['login'] = true;
        $messages['login'] = "Votre login doit faire 4 caractères minimum";
    } elseif (strlen($_POST['login']) > 100) {
        $errors['login'] = true;
        $messages['login'] = "Votre login doit faire 200 caractères maximum";
    } elseif ((strlen($_POST['login']) === (strlen($user ['login'])))) {
        $errors['login'] = true;
        $messages['login'] = "identifiant ou mot de passe incorrect";
    }

    //validation du mot de passe et renvoye vers la page privé
    
    if (!isset($_POST['password']) || empty($_POST['password'])) {
        $errors['password'] = true;
        $messages['password'] = "Merci de renseigner votre mot de passe";
    } elseif (strlen($_POST['password']) < 4) {
        $errors['password'] = true;
        $messages['password'] = "Votre mot de passe doit faire 4 caractères minimum";
    } elseif (strlen($_POST['password']) > 200) {
        $errors['password'] = true;
        $messages['password'] = "Votre mot de passe doit faire 100 caractères maximum";
    } elseif (!password_verify($_POST['password'], $user['passwrd_hash'])) {
        echo "identifiant ou mot de passe incorrect";
    } else 
        $url = 'private-page.php';
        header("Location: {$url}", true, 302);
        exit();

    if (!$errors) {
        // s'il n'y a aucune erreur, on peut affecter des données à la variable de session
        $_SESSION['login'] = $user['login'];
        $_SESSION['user_id'] = $user['user_id'];
    }
}




// affichage du rendu d'un template
echo $twig->render('login.html.twig', [
    // transmission de données au template
    'errors' => $errors,
    'messages' => $messages,
    'user' => $user,
]);
