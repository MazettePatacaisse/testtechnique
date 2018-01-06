<?php

/**
 * Class Groupe
 */
class Groupes {

    protected $db;

    public function __construct()
    {
        spl_autoload_register('load');
        $datab = new dbcon();
        $this->db = $datab->connect();
    }

    /**
     * @param $nomdugroupe
     * @return bool
     */
    private function controler($nomdugroupe){
        // On cherche une occurence correspondante dans la table.
        $controle = $this->db->where("nom", $nomdugroupe)->getValue("groupe", "COUNT(*)");
        $result = ($controle >= 1)?true:false;
        return $result;
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
     * @return null|string
     */
    public function ajoutergroupe(){
        if (isset($_POST["addgroup"])) {
            $groupname = trim(strip_tags($_POST["groupname"])); // nettoyage
            $check1 = (!empty($groupname))?true:false; // on vérifie que la variable ne soit pas vide
            $check2 = $this->controler($groupname); // On contrôle l'existence du nom de groupe dans la bdd
            if($check1 === true && $check2 === false){
                $this->db->insert("groupe", ["nom" => $groupname]);
                header("Refresh: 1;url=index.php");
                return $this->success('Bravo!', 'Le groupe a bien été ajouté.');
            }else{
                header("Refresh: 1;url=index.php");
                return $this->error('Oupss', 'Il semblerait que ce groupe existe déjà.');
            }
        }
        return null;
    }
}