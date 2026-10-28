<?php 
namespace App\Trait;

use App\Entity\ParentEntity;
use App\Repository\ParentRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\Common\Collections\ArrayCollection;


/**
 * Ce trait sera 'implémenté' par toutes les classes CRUD du projet
 */
Trait CrudTrait {

    protected string $col;
    protected string $dir;
 
    public function setSortColumn(Request $request){
        $this->col = $request->query->get("col", "id");
        $this->dir = $request->query->get("dir", "asc");
    }

    

    /** g̲e̲t̲N̲a̲m̲e̲ :  
     * Retourne le nom de la classe Controller (sans le namespace, ni le mot 'Controller')
     * @return string
     */
    public function getName() {
        $parts = explode("\\", get_called_class());
        $name = $parts[ count($parts) - 1 ];
        $name = str_replace("Controller", "", $name);
        return $name;
    }

    public function getNomEntite() {
        $name = $this->getName();
        $pos = -1;
        for($i=1; $i<strlen($name); $i++) {
            $char = substr($name, $i, 1); 
            if(65 <= ord($char) && ord($char) <= 90 ) {
                $pos = $i;
            }
        }
        
        if( $pos > 0) {
            $name = substr($name, 0, $pos) . "_" . substr($name, $pos);
        }

        return strtolower($name);
    }

    public function getClasseEntite() : string {
        return "App\Entity\\" . ucfirst($this->getName());
    }

    /** l̲i̲s̲t̲e̲ :  
     * Appeler cette méthode pour la route '_index' d'un CRUD
     * 
     * @param ParentRepository $repo repository de l'entité à afficher
     * @param Request $rq
     * @param int $nbParPage nombre de résultat par page affichée
     * @return Response
     */
    public function liste(ParentRepository $repo, Request $rq, $nbParPage = 10, bool $tout = false): Response
    {
        $name = $this->getName();
        $sort = $rq->query->get("sort");
        $direction = $rq->query->get("direction", "asc");
        if( $sort ) $ordre = [$sort => $direction];
        $list = $repo->findAll($ordre ?? null, includeDeleted: $tout);

        $page = $rq->query->get("page", 1);
        $nb = $page != "0" ? $nbParPage : count($list);
        $list = $this->paginator->paginate($list, $page ?: 1, $nb);

        $pos = -1;
        for($i=1; $i<strlen($name); $i++) {
            $char = substr($name, $i, 1); 
            if(65 <= ord($char) && ord($char) <= 90 ) {
                $pos = $i;
            }
        }
        
        if( $pos > 0) {
            $name = substr($name, 0, $pos) . "_" . substr($name, $pos);
        }

        $name = strtolower($name);
        return $this->render("gestion/$name/index" , [
            "{$name}s"  => $list,
            "entite"    => $name
        ]);
    }

    /** routeLliste : 
     * Méthode pour route '_index" d'un contrôleur CRUD.
     * Version plus maniable
     * @param ArrayCollection $list Liste d'entités retournées par DedRepository::findAll
     * @param int $numPage  (optionel) Numéro de la pagination acutelle
     * @param int $nbParPage (optionel) Nombre d'entité affichée par page
     * 
     * @return Response
     */
    public function routeListe(ArrayCollection $list, $numPage = 1, $nbParPage = 10): Response
    {
        $name = $this->getName();
        $nb = $numPage != "0" ? $nbParPage : count($list);
        $list = $this->paginator->paginate($list, $numPage ?: 1, $nb);
        
        $pos = -1;
        for($i=1; $i<strlen($name); $i++) {
            $char = substr($name, $i, 1); 
            if(65 <= ord($char) && ord($char) <= 90 ) {
                $pos = $i;
            }
        }
        if( $pos > 0) {
            $name = substr($name, 0, $pos) . "_" . substr($name, $pos);
        }

        $name = strtolower($name);
        return $this->render("gestion/$name/index" , [
            "{$name}s" => $list,
            "entite"    => $name
        ]);
    }
    
    /** insérer :
     * 
     */
    public function insérer(ParentRepository $repo, Request $request): Response
    {
        $nomEntite =$this->getNomEntite();
        $classeEntite = $this->getClasseEntite();
        $entite = new $classeEntite;

        $classeFormType = "App\Form\\{$entite}Type";

        $form = $this->createForm($classeFormType, $entite);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $repo->save($entite);

            return $this->redirectToroute("app_gestion_{$nomEntite}_index", [], Response::HTTP_SEE_OTHER);
        }
        return $this->render("gestion/{$nomEntite}/new", [
            "$nomEntite" => $entite,
            "form" => $form->createView(),
        ]);
    }


    /**
     * 
     */
    public function actualiser(int $id, Request $request, ParentRepository $repo): Response
    {
        $nom = $this->getName();

        $classeEntite = "App\Entity\\$nom";
        $classeRepository = "App\Repository\\{$nom}Repository";
        $classeFormType = "App\Form\\{$nom}Type";
        $nomEntite = $this->getNomEntite();

        $entite = $repo->find($id);      
        $form = $this->createForm($classeFormType, $entite);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $repo->save($entite);

            return $this->redirectToRoute("app_gestion_{$nomEntite}_index", [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm("gestion/{$nomEntite}/edit", [ "$nomEntite" => $entite, "form" => $form, ]);
    }

    /**
     * @param bool $definitivement true pour une suppression irréversible
     */
    public function effacer(Request $request, ParentEntity $entity, ParentRepository $repository, bool $definitivement = false): Response
    {
        if ($this->isCsrfTokenValid('delete'.$entity->getId(), $request->request->get('_token'))) {
            $repository->remove($entity, $definitivement);
        }
        $nomEntite = $this->getNomEntite();
        return $this->redirectToRoute("app_gestion_{$nomEntite}_index", [], Response::HTTP_SEE_OTHER);
    }

    public function restaurer(Request $request, ParentEntity $entity, ParentRepository $repository) : Response {
        if ($this->isCsrfTokenValid('delete'.$entity->getId(), $request->request->get('_token'))) {
            $repository->restore($entity);
        }
        $nomEntite = $this->getNomEntite();
        return $this->redirectToRoute("app_gestion_{$nomEntite}_index", [], Response::HTTP_SEE_OTHER);

    }


}