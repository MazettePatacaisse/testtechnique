<?php

/**
 * Class Utilisateurs
 */
class Utilisateurs {

    protected $db;

    public function __construct()
    {
        spl_autoload_register('load');
        $datab = new dbcon();
        $this->db = $datab->connect();
    }


    /**
     * @param $nom
     * @param $prenom
     * @param $email
     * @param $age
     * @return bool
     */
    private function controler($nom, $prenom, $email, $age)
    {
        // On cherche une occurence de chaque dans la table.
        $controle = $this->db->where("nom", $nom)->where("prenom",$prenom)->where("email",$email)->where("age",$age)->getValue("utilisateurs", "COUNT(*)");
        $result = ($controle >= 1)?true:false;
        return $result;
    }

    /**
     * @param $data
     * @return false|string
     */
    private function calcage($data)
    {
        $given_date = strtotime($data);
        $current_date = strtotime(date("Y/m/d"));
        $dif = date("Y", $current_date) -  date("Y", $given_date);
        return $dif;
    }

    /**
     * @param $email
     * @return false|int|mixed
     */
    private function validemail($email)
    {
        if (function_exists("filter_var")){return filter_var($email, FILTER_VALIDATE_EMAIL);}
        return preg_match('/^([a-z0-9._-](\+[a-z0-9])*)+@[a-z0-9.-]+\.[a-z]{2,6}$/i', $email);
    }
    /**
     * @param $title
     * @param $message
     * @return string
     */
    public function success($title, $message){
        $result = '<div class="alert alert-success alert-dismissable">';
        $result .= '<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>';
        $result .= '<strong>'.$title.'</strong>'.$message;
        $result .= '</div>';
        return $result;
    }

    /**
     * @param $title
     * @param $message
     * @return string
     */
    public function error($title, $message){
        $result = '<div class="alert alert-danger alert-dismissable">';
        $result .= '<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>';
        $result .= '<strong>'.$title.'</strong>'.$message;
        $result .= '</div>';
        return $result;
    }

    /**
     * @return string
     */
    public function displaygroupes(){
        //On cherche si il existe des entrées dans la table groupe.
        $res = $this->db->orderBy("nom", "ASC")->get("groupe"); // on recupere la liste apr ordre alphabetique
        $count = $this->db->count;
        if ($count >= 1){
            $group = '<select class="form-control" name="group" required>';
            $group .= '<option value="">Veuillez choisir un groupe</option>';
            foreach($res as $resgroup){
                $group .= '<option value="'.$resgroup["nom"].'">'.$resgroup["nom"].'</option>';
            }
            $group .= '</select>';

        }else{
            $group = "<div style='color:red'>Aucun groupe n'a encore été créé!</div>";
        }
        return $group;
    }

    /**
     * @return null|string
     */
    public function ajouterutilisateurs(){
        if (isset($_POST["adduser"])) {
            $nom = trim(strip_tags($_POST["nom"])); // nettoyage
            $prenom = trim(strip_tags($_POST["prenom"])); // nettoyage
            $email = $_POST["email"];
            $validemail = $this->validemail($email); // Contrôle du format email
            $age = $this->calcage($_POST["age"]);// On determine son age
            $groupe = $_POST["group"];
            $check1 = (!empty($nom) && !empty($prenom) && !empty($email) && !empty($age) && !empty($groupe) && $validemail == true)?true:false; // on vérifie que les variables ne soient pas vide et que le format email soit correcte
            $check2 = $this->controler($nom,$prenom,$email,$age); // On contrôle l'existence de l'utilisateur dans la bdd en tenant compte de 4 parametres
            if($check1 === true && $check2 === false){
                $this->db->insert("utilisateurs", ["groupe" => $groupe,"nom" => $nom, "prenom" => $prenom, "email" => $email, "age" => $age]);// on ajoute l'utilsateur dans la bdd
                header("Refresh: 1;url=index.php");
                return $this->success('Bravo!', "L 'utilisateur a bien été ajouté.");//message succés
            }else{
                header("Refresh: 1;url=index.php");
               return $this->error('Oupss', 'Il semblerait que cet utilisateur existe déjà.');//message erreur
            }
        }
        return null;
    }
}