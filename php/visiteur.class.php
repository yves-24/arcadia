
class Visiteur{
    private $prenom;

    public function set_prenom($nouveau_prenom){
        $this->prenom = $nouveau_prenom;
    }
    public function get_prenom(){
        return $this-> prenom;
    }
}
?>