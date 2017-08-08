<?php
namespace Vokuro\Controllers ; 
use Vokuro\Models\categorieemploye;
use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;


class CategorieemployeController extends ControllerBase
{
    /**
     * Index action
     */
    public function indexAction()
    {
        $this->persistent->parameters = null;
         $this->view->setLayout("private");
    }

    /**
     * Searches for categorieemploye
     */
    public function searchAction()
    { $this->view->setLayout("private");
        $numberPage = 1;
        if ($this->request->isPost()) {
            $query = Criteria::fromInput($this->di, 'Vokuro\Models\categorieemploye', $_POST);
            $this->persistent->parameters = $query->getParams();
        } else {
            $numberPage = $this->request->getQuery("page", "int");
        }

        $parameters = $this->persistent->parameters;
        if (!is_array($parameters)) {
            $parameters = [];
        }
        $parameters["order"] = "type";

        $categorieemploye = Categorieemploye::find($parameters);
        if (count($categorieemploye) == 0) {
            $this->flash->notice("The search did not find any categorieemploye");

            $this->dispatcher->forward([
                "controller" => "categorieemploye",
                "action" => "index"
            ]);

            return;
        }

        $paginator = new Paginator([
            'data' => $categorieemploye,
            'limit'=> 10,
            'page' => $numberPage
        ]);

        $this->view->page = $paginator->getPaginate();
    }

    /**
     * Displays the creation form
     */
    public function newAction()
    {
 $this->view->setLayout("private");
    }

    /**
     * Edits a categorieemploye
     *
     * @param string $type
     */
    public function editAction($type)
    {

        $this->view->setLayout("private");
        if (!$this->request->isPost()) {

            $categorieemploye = Categorieemploye::findFirstBytype($type);
            if (!$categorieemploye) {
                $this->flash->error("categorieemploye was not found");

                $this->dispatcher->forward([
                    'controller' => "categorieemploye",
                    'action' => 'index'
                ]);

                return;
            }

            $this->view->type = $categorieemploye->type;

            $this->tag->setDefault("type", $categorieemploye->type);
            $this->tag->setDefault("salaire", $categorieemploye->salaire);
            $this->tag->setDefault("nbr", $categorieemploye->nbr);
            $this->tag->setDefault("nbr_heure", $categorieemploye->nbr_heure);
            $this->tag->setDefault("entreprise_siren", $categorieemploye->entreprise_siren);
            
        }
    }

    /**
     * Creates a new categorieemploye
     */
    public function createAction()
    {
         $this->view->setLayout("private");
        if (!$this->request->isPost()) {
            $this->dispatcher->forward([
                'controller' => "categorieemploye",
                'action' => 'index'
            ]);

            return;
        }

        $categorieemploye = new Categorieemploye();
        $categorieemploye->type = $this->request->getPost("type");
        $categorieemploye->salaire = $this->request->getPost("salaire");
        $categorieemploye->nbr = $this->request->getPost("nbr");
        $categorieemploye->nbr_heure = $this->request->getPost("nbr_heure");
        $categorieemploye->entreprise_siren = $this->request->getPost("entreprise_siren");
        

        if (!$categorieemploye->save()) {
            foreach ($categorieemploye->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "categorieemploye",
                'action' => 'new'
            ]);

            return;
        }

        $this->flash->success("categorieemploye was created successfully");

        $this->dispatcher->forward([
            'controller' => "categorieemploye",
            'action' => 'index'
        ]);
    }

    /**
     * Saves a categorieemploye edited
     *
     */
    public function saveAction()
    {
     $this->view->setLayout("private");
        if (!$this->request->isPost()) {
            $this->dispatcher->forward([
                'controller' => "categorieemploye",
                'action' => 'index'
            ]);

            return;
        }

        $type = $this->request->getPost("type");
        $categorieemploye = Categorieemploye::findFirstBytype($type);

        if (!$categorieemploye) {
            $this->flash->error("categorieemploye does not exist " . $type);

            $this->dispatcher->forward([
                'controller' => "categorieemploye",
                'action' => 'index'
            ]);

            return;
        }

        $categorieemploye->type = $this->request->getPost("type");
        $categorieemploye->salaire = $this->request->getPost("salaire");
        $categorieemploye->nbr = $this->request->getPost("nbr");
        $categorieemploye->nbr_heure = $this->request->getPost("nbr_heure");
        $categorieemploye->entreprise_siren = $this->request->getPost("entreprise_siren");
        

        if (!$categorieemploye->save()) {

            foreach ($categorieemploye->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "categorieemploye",
                'action' => 'edit',
                'params' => [$categorieemploye->type]
            ]);

            return;
        }

        $this->flash->success("categorieemploye was updated successfully");

        $this->dispatcher->forward([
            'controller' => "categorieemploye",
            'action' => 'index'
        ]);
    }

    /**
     * Deletes a categorieemploye
     *
     * @param string $type
     */
    public function deleteAction($type)
    {   
        $this->view->setLayout("private");
        $categorieemploye = Categorieemploye::findFirstBytype($type);
        if (!$categorieemploye) {
            $this->flash->error("categorieemploye was not found");

            $this->dispatcher->forward([
                'controller' => "categorieemploye",
                'action' => 'index'
            ]);

            return;
        }

        if (!$categorieemploye->delete()) {

            foreach ($categorieemploye->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "categorieemploye",
                'action' => 'search'
            ]);

            return;
        }

        $this->flash->success("categorieemploye was deleted successfully");

        $this->dispatcher->forward([
            'controller' => "categorieemploye",
            'action' => "index"
        ]);
    }

}
