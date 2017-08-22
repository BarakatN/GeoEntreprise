<?php
namespace GeoEntreprise\Controllers;

use GeoEntreprise\Models\Domaine;

use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;


class DomaineController extends ControllerBase
{
    /**
     * Index action
     */
     public function initialize()
     {
         $this->view->setlayout('private');
     }
    public function indexAction()
    {
        $this->persistent->parameters = null;
    }

    /**
     * Searches for domaine
     */
    public function searchAction()
    {
        $numberPage = 1;
        if ($this->request->isPost()) {
            $query = Criteria::fromInput($this->di, 'GeoEntreprise\Models\Domaine', $_POST);
            $this->persistent->parameters = $query->getParams();
        } else {
            $numberPage = $this->request->getQuery("page", "int");
        }

        $parameters = $this->persistent->parameters;
        if (!is_array($parameters)) {
            $parameters = [];
        }
        $parameters["order"] = "id_domaine";

        $domaine = Domaine::find($parameters);
        if (count($domaine) == 0) {
            $this->flash->notice("The search did not find any domaine");

            $this->dispatcher->forward([
                "controller" => "domaine",
                "action" => "index"
            ]);

            return;
        }

        $paginator = new Paginator([
            'data' => $domaine,
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

    }

    /**
     * Edits a domaine
     *
     * @param string $id
     */
    public function editAction($id_domaine)
    {
        if (!$this->request->isPost()) {

            $domaine = Domaine::findFirstByid_domained($id_domaine);
            if (!$domaine) {
                $this->flash->error("domaine was not found");

                $this->dispatcher->forward([
                    'controller' => "domaine",
                    'action' => 'index'
                ]);

                return;
            }

            $this->view->id_domaine = $domaine->id_domaine;

            $this->tag->setDefault("id_domaine", $domaine->id_domaine);
            $this->tag->setDefault("nom", $domaine->nom);
            $this->tag->setDefault("description", $domaine->description);

        }
    }

    /**
     * Creates a new domaine
     */
    public function createAction()
    {
        if (!$this->request->isPost()) {
            $this->dispatcher->forward([
                'controller' => "domaine",
                'action' => 'index'
            ]);

            return;
        }

        $domaine = new Domaine();
        $domaine->nom = $this->request->getPost("nom");
        $domaine->description = $this->request->getPost("description");


        if (!$domaine->save()) {
            foreach ($domaine->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "domaine",
                'action' => 'new'
            ]);

            return;
        }

        $this->flash->success("domaine was created successfully");

        $this->dispatcher->forward([
            'controller' => "domaine",
            'action' => 'index'
        ]);
    }

    /**
     * Saves a domaine edited
     *
     */
    public function saveAction()
    {

        if (!$this->request->isPost()) {
            $this->dispatcher->forward([
                'controller' => "domaine",
                'action' => 'index'
            ]);

            return;
        }

        $id_domaine = $this->request->getPost("id_domaine");
        $domaine = Domaine::findFirstByid_domaine($id_domaine);

        if (!$domaine) {
            $this->flash->error("domaine does not exist " . $id_domaine);

            $this->dispatcher->forward([
                'controller' => "domaine",
                'action' => 'index'
            ]);

            return;
        }

        $domaine->nom = $this->request->getPost("nom");
        $domaine->description = $this->request->getPost("description");


        if (!$domaine->save()) {

            foreach ($domaine->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "domaine",
                'action' => 'edit',
                'params' => [$domaine->id_domaine]
            ]);

            return;
        }

        $this->flash->success("domaine was updated successfully");

        $this->dispatcher->forward([
            'controller' => "domaine",
            'action' => 'index'
        ]);
    }

    /**
     * Deletes a domaine
     *
     * @param string $id
     */
    public function deleteAction($id_domaine)
    {
        $domaine = Domaine::findFirstByid($id_domaine);
        if (!$domaine) {
            $this->flash->error("domaine was not found");

            $this->dispatcher->forward([
                'controller' => "domaine",
                'action' => 'index'
            ]);

            return;
        }

        if (!$domaine->delete()) {

            foreach ($domaine->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "domaine",
                'action' => 'search'
            ]);

            return;
        }

        $this->flash->success("domaine was deleted successfully");

        $this->dispatcher->forward([
            'controller' => "domaine",
            'action' => "index"
        ]);
    }

}
