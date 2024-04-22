<?php

session_start();

if(isset($_GET["action"])) {
    switch ($_GET["action"]) {
        case 'register':

            if($_POST["submit"]) {
                $pdo = new PDO("mysql:host=localhost; dbname=connect;charset=utf8", "root", "root");
            
                // Filtrer la saisie des champs du formulaire d'inscription
                $pseudo = filter_input(INPUT_POST, "pseudo", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                $email = filter_input(INPUT_POST, "email", FILTER_VALIDATE_EMAIL, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                $pass1 = filter_input(INPUT_POST, "pass1", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                $pass2 = filter_input(INPUT_POST, "pass2", FILTER_SANITIZE_FULL_SPECIAL_CHARS);

                if($pseudo && $email && $pass1 && $pass2) {
                    // 
                    $requete = $pdo->prepare(
                    "SELECT * 
                    FROM user 
                    WHERE email = :email");
                    $requete->execute([
                        "email" => $email
                    ]);

                    $user = $requete->fetch();
                    //  Si l'utilisateur existe
                    if($user){
                        header("Location: register.php"); exit;
                    }else{
                        // Insertion de l'utilisateur dans BDD
                        if($pass1 == $pass2 && strlen($pass1) >= 2) {
                            $insertUser = $pdo->prepare("INSERT INTO user(pseudo, email, password) VALUES(:pseudo, :email, :password)");
                            $insertUser->execute([
                                "pseudo" => $pseudo,
                                "email" => $email,
                                "password" => password_hash($pass1, PASSWORD_DEFAULT)
                            ]);
                            header("Location: login.php"); exit;
                        }else{
                            // message "Les mots de passe ne sont pas identique ou mot de passe trop court !

                        }
                    }
                }else{
                    // probleme de saisie dans les champs de formulaire
                }
            }

            // par defaut j'affiche le formulaire d'inscription
            header("Location: register.php"); exit;
            break;

            case "login":
                // Connexion à l'application 
                if($_POST["submit"]) {
                    $pdo = new PDO("mysql:host=localhost; dbname=connect;charset=utf8", "root", "root");

                    // filtrer les champs(faille XSS)
                    $email = filter_input(INPUT_POST, "email", FILTER_VALIDATE_EMAIL, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                    $password = filter_input(INPUT_POST, "password", FILTER_SANITIZE_FULL_SPECIAL_CHARS);

                    // si les filtres sont valide               
                    if($email && $password) {
                        $requete = $pdo->prepare("SELECT * FROM user WHERE email = :email");
                        $requete->execute([
                            "email" => $email
                        ]);

                        $user = $requete->fetch();
                        // estce que l'utilasateur existe ?
                        // si l'utlisateur existe
                       if($user) {
                            
                            $hash = $user["password"];
                            if(password_verify($password, $hash)) {
                                $_SESSION["user"] = $user;
                                header("Location: home.php"); exit;
                            }else{
                                header("Location: login.php"); exit;
                                // message utilisateur inconnu ou mot de passe est incorrect
                            }
                       }else{
                            // message utilisateur inconnu ou mot de passe est incorrect
                            header("Location: login.php"); exit; 
                       }
                    }
                }
                header("Location: login.php"); exit;
            break;

            case "profil":


                header("Location: profil.php"); exit;

            break;
            case "logout":

                unset($_SESSION["user"]);
                header("Location: home.php"); exit;
            break;    
    }
}