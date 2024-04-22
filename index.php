<?php
session_start();
$password = "monMotdePasse1234";
$password2 = "monMotdePasse1234";

// Algorithme de hachage FAIBLE
$md5 = hash('md5', $password);
$md5_2 = hash('md5', $password2);
// echo $md5."<br>";
// echo $md5_2."<br>";

$sha256 = hash('sha256', $password);
$sha256_2 = hash('sha256', $password2);
// echo $sha256."<br>";
// echo $sha256_2."<br>";

// Algorithme de hachage FORT(bcrypte)

$hash = password_hash($password, PASSWORD_DEFAULT);
$hash_2 = password_hash($password2, PASSWORD_DEFAULT);
// echo $hash."<br>";
// echo $hash_2."<br>";


// Saisie dans mon formulaire
$saisie = "monMotdePasse1234";
$user = "Mickael";

$check = password_verify($saisie, $hash);
if(password_verify($saisie, $hash)) {
    $_SESSION["user"] = $user;
    echo $user." est connecté";
}else{
    echo "les mots de passe sont différents";
}