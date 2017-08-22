<?php
namespace GeoEntreprise\Controllers;

use GeoEntreprise\Models\DomaineHasEntreprise;
use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;


class DomaineHasEntrepriseController extends ControllerBase
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
     * Searches for domaine_has_entreprise
     */
    public function searchAction()
    {
        $numberPage = 1;
        if ($this->request->isPost()) {
            $query = Criteria::fromInput($this->di, 'GeoEntreprise\Models\DomaineHasEntreprise', $_POST);
            $this->persistent->parameters = $query->getParams();
        } else {
            $numberPage = $this->request->getQuery("page", "int");
        }

        $parameters = $this->persistent->parameters;
        if (!is_array($parameters)) {
            $parameters = [];
        }
        $parameters["order"] = "domaine_id_domaine";

        $domaine_has_entreprise = DomaineHasEntreprise::find($parameters);
        if (count($domaine_has_entreprise) == 0) {
            $this->flash->notice("The search did not find any domaine_has_entreprise");

            $this->dispatcher->forward([
                "controller" => "domaine_has_entreprise",
                "action" => "index"
            ]);

            return;
        }

        $paginator = new Paginator([
            'data' => $domaine_has_entreprise,
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
     * Edits a domaine_has_entreprise
     *
     * @param string $domaine_id_domaine
     */
    public function editAction($domaine_id_domaine)
    {
        if (!$this->request->isPost()) {

            $domaine_has_entreprise = DomaineHasEntreprise::findFirstBydomaine_id_domaine($domaine_id_domaine);
            if (!$domaine_has_entreprise) {
                $this->flash->error("domaine_has_entreprise was not found");

                $this->dispatcher->forward([
                    'controller' => "domaine_has_entreprise",
                    'action' => 'index'
                ]);

                return;
            }

            $this->view->domaine_id_domaine = $domaine_has_entreprise->domaine_id_domaine;

            $this->tag->setDefault("domaine_id_domaine", $domaine_has_entreprise->domaine_id_domaine);
            $this->tag->setDefault("entreprise_id_entreprise", $domaine_has_entreprise->entreprise_id_entreprise);

        }
    }

    /**
     * Creates a new domaine_has_entreprise
     */
    public function createAction()
    {
        if (!$this->request->isPost()) {
            $this->dispatcher->forward([
                'controller' => "domaine_has_entreprise",
                'action' => 'index'
            ]);

            return;
        }

        $domaine_has_entreprise = new DomaineHasEntreprise();
        $domaine_has_entreprise->domaine_id_domaine = $this->request->getPost("domaine_id_domaine");
        $domaine_has_entreprise->entreprise_id_entreprise = $this->request->getPost("entreprise_id_entreprise");


        if (!$domaine_has_entreprise->save()) {
            foreach ($domaine_has_entreprise->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "domaine_has_entreprise",
                'action' => 'new'
            ]);

            return;
        }

        $this->flash->success("domaine_has_entreprise was created successfully");

        $this->dispatcher->forward([
            'controller' => "domaine_has_entreprise",
            'action' => 'index'
        ]);
    }

    /**
     * Saves a domaine_has_entreprise edited
     *
     */
    public function saveAction()
    {

        if (!$this->request->isPost()) {
            $this->dispatcher->forward([
                'controller' => "domaine_has_entreprise",
                'action' => 'index'
            ]);

            return;
        }

        $domaine_id_domaine = $this->request->getPost("domaine_id_domaine");
        $domaine_has_entreprise = DomaineHasEntreprise::findFirstBydomaine_id_domaine($domaine_id_domaine);

        if (!$domaine_has_entreprise) {
            $this->flash->error("domaine_has_entreprise does not exist " . $domaine_id_domaine);

            $this->dispatcher->forward([
                'controller' => "domaine_has_entreprise",
                'action' => 'index'
            ]);

            return;
        }

        $domaine_has_entreprise->domaine_id_domaine = $this->request->getPost("domaine_id_domaine");
        $domaine_has_entreprise->entreprise_id_entreprise = $this->request->getPost("entreprise_id_entreprise");


        if (!$domaine_has_entreprise->save()) {

            foreach ($domaine_has_entreprise->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "domaine_has_entreprise",
                'action' => 'edit',
                'params' => [$domaine_has_entreprise->domaine_id_domaine]
            ]);

            return;
        }

        $this->flash->success("domaine_has_entreprise was updated successfully");

        $this->dispatcher->forward([
            'controller' => "domaine_has_entreprise",
            'action' => 'index'
        ]);
    }

    /**
     * Deletes a domaine_has_entreprise
     *
     * @param string $domaine_id_domaine
     */
    public function deleteAction($domaine_id_domaine)
    {
        $domaine_has_entreprise = DomaineHasEntreprise::findFirstBydomaine_id_domaine($domaine_id_domaine);
        if (!$domaine_has_entreprise) {
            $this->flash->error("domaine_has_entreprise was not found");

            $this->dispatcher->forward([
                'controller' => "domaine_has_entreprise",
                'action' => 'index'
            ]);

            return;
        }

        if (!$domaine_has_entreprise->delete()) {

            foreach ($domaine_has_entreprise->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "domaine_has_entreprise",
                'action' => 'search'
            ]);

            return;
        }

        $this->flash->success("domaine_has_entreprise was deleted successfully");

        $this->dispatcher->forward([
            'controller' => "domaine_has_entreprise",
            'action' => "index"
        ]);
    }

}
