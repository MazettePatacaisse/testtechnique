<?php

class Search {

    protected $db;

    public function __construct()
    {
        spl_autoload_register('load');
        $datab = new dbcon();
        $this->db = $datab->connect();
    }

    /**
     * @param $data
     * @return null|string|string[]
     */
    private function sanitize($data)
    {
        $searchstr = trim($data); // on retire les espaces
        $searchstr = strip_tags($searchstr); //on retire les tags
        $searchstr = preg_replace(['/[^a-z0-9]/si', '/^\s*/s', '/\s*$/s', '/\s+/s'], [" ", "", "", " "], $searchstr);
        return $searchstr;
    }

    /**
     * @return string
     */
    public function listgroup()
    {
        $res = $this->db->orderBy("nom", "ASC")->get("groupe"); // on recupere la liste apr ordre alphabetique
            $group = '<select class="form-control" name="searchgroup">';
            $group .= '<option value="">Tous les groupes</option>';
            foreach($res as $resgroup){
                $group .= '<option value="'.$resgroup["nom"].'">'.$resgroup["nom"].'</option>';
            }
            $group .= '</select>';
        return $group;
    }

    /**
     * @param $groupname
     * @param $searchfield
     * @return $this|array|mixed|string|void
     */
    public function searchtask($groupname, $searchfield)
    {
        $searchfield = $this->sanitize($searchfield);
        $searchVar = '%'. $searchfield.'%';
        switch(true)
        {
            case !empty($groupname):
                return $this->db->where("groupe", $groupname)->where("nom", $searchVar, "LIKE")->get("utilisateurs");
                break;
            case empty($groupname):
            default:
                return $this->db->where("nom", $searchVar, "LIKE")->get("utilisateurs");

        }

    }
}