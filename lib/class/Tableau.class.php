<?php

class Tableau
{

    protected $db;
    protected $search;

    public function __construct()
    {
        spl_autoload_register('load');
        $datab = new dbcon();
        $this->search = new Search();
        $this->db = $datab->connect();

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
     * @param $id
     * @return null|string
     */
    public function supprimer($id)
    {
        if (isset($_POST["supprimer"]))
        {   $protocol = ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
            $url = $protocol . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
            $check = $this->db->where("id", $id)->getOne("utilisateurs");
            if($check) {
                $this->db->where("id", $id)->delete("utilisateurs");
                $message =  $this->success("Bravo ", "L' utilisateur a bien été supprimé.");
            }else{
                $message =  $this->error("Oupsss ", "Il semblerait que et utilisateur n'éxiste pas.");
            }
            header("Refresh: 1;url=index.php");
            return $message;
        }
        return null;
    }

    /**
     * @return string
     */
    public function toutsupprimer()
    {
        if (isset($_POST["deleteall"]))
        {
            $count = count($_POST["delete"]);
            if($count >= 1) {
                $delete = $_POST["delete"];
                foreach($delete as $deluser){
                    $this->db->where("id",$deluser)->delete("utilisateurs");
                }
                $message = $this->success("Bravo!", "Vous avez supprimé ".$count." utilisateur(s)");
                header("Refresh: 1;url=index.php");
            }else{
                $message = $this->error("Erreur", "Veuillez choisir au moins 1 utilisateur");
                header("Refresh: 1;url=index.php");
            }
        }
        return $message;
    }

    /**
     * @param $res
     * @return string
     */
    public function details($res)
    {
        global $modal;
        foreach ($res as $user) {
            $modal .= '<div class="modal fade" id="details'.$user["id"].'" tabindex="-1" role="dialog" aria-labelledby="mafenetre" aria-hidden="true">';
            $modal .= '<div class="modal-dialog modal-sm">';
            $modal .= '<div class="modal-content">';
            $modal .= '<div class="modal-header">';
            $modal .= '<button aria-hidden="true" data-dismiss="modal" class="close" type="button">×</button>';
            $modal .= '<h4 id="myLargeModalLabel3" class="modal-title">Informations Complémentaires</h4>';
            $modal .= '</div>';
            $modal .= '<div class="modal-body">';
            $modal .= '<div class="row">';
            $modal .= '<div class="col-sm-6">';
            $modal .= 'Nom: '.$user["nom"];
            $modal .= '</div>';
            $modal .= '<div class="col-sm-6">';
            $modal .= 'Age: '.$user["age"].' ans';
            $modal .= '</div>';
            $modal .= '</div>';
            $modal .= '</div>';
            $modal .= '</div>';
            $modal .= '</div>';
            $modal .= '</div>';
        }
        return $modal;

    }

    /**
     * @return array
     */
    public function lister()
    {
        global $lignes;
        if(isset($_POST["search"])){
            $list = $this->search->searchtask($_POST["searchgroup"],$_POST["searchinput"]);
            $count = count($list);
        }else {
            $list = $this->db->orderBy("groupe", "ASC")->get("utilisateurs"); // on recupère la liste par ordre alphabetique de nom de groupe
            $count = $this->db->count;
        }
        if ($count >= 1) {
            foreach ($list as $listusers) {
                $lignes .= '<tr>';
                $lignes .= '<td class="group">' . $listusers["groupe"] . '</td>';
                $lignes .= '<td class="utilisateur">' . strtoupper($listusers["nom"]) . ' ' . ucfirst($listusers["prenom"]) . '</td>';
                $lignes .= '<td>' . $listusers["email"] . '</td>';
                $lignes .= '<td><a class="btn btn-sm btn-details" data-toggle="modal" data-target="#details' . $listusers["id"] . '">Details</a><button type="submit" name="supprimer" value="'.$listusers["id"].'" class="btn btn-sm btn-danger supprimer">Supprimer</button></td>';
                $lignes .= '<td><input type="checkbox" class="checkboxchild" value="'.$listusers["id"].'" name="delete[]"></td>';
                $lignes .= '</tr>';
                $liste = $this->details($list);
                $id = $listusers["id"];
            }
        } else {
            $lignes .= '<tr>';
            $lignes = "<td class='error' colspan='5'>Aucun utilisateurs dans la base de données!</td>";
            $lignes .= '</tr>';
            $liste = '';
            $id='';
        }
        return [$lignes,$liste,$id];
    }
}