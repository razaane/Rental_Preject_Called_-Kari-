<?php
interface UserInterface {
    //pour enregistrer un nv user
    public function register(array $data): bool;

    //permet de connecter avec email et pass 
    public function login(string $email, string $password):bool;

    //check user with their emails only 
    public function findByEmail(string $email):?array;

    //pour un mise à jour de votre profile
    // public function updateProfile(int $id , array $data) :bool;

    // //pour les roles 
    // public function getRole():string;

}