<?php
namespace GeoEntreprise\Controllers;

use GeoEntreprise\Models\Etablissement;
use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;


class EtablissementController extends ControllerBase
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
     * Searches for etablissement
     */
    public function searchAction()
    {
        $numberPage = 1;
        if ($this->request->isPost()) {
            $query = Criteria::fromInput($this->di, 'GeoEntreprise\Models\Etablissement', $_POST);
            $this->persistent->parameters = $query->getParams();
        } else {
            $numberPage = $this->request->getQuery("page", "int");
        }

        $parameters = $this->persistent->parameters;
        if (!is_array($parameters)) {
            $parameters = [];
        }
        $parameters["order"] = "id_etab";

        $etablissement = Etablissement::find($parameters);
        if (count($etablissement) == 0) {
            $this->flash->notice("The search did not find any etablissement");

            $this->dispatcher->forward([
                "controller" => "etablissement",
                "action" => "index"
            ]);

            return;
        }

        $paginator = new Paginator([
            'data' => $etablissement,
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
     * Edits a etablissement
     *
     * @param string $id_etab
     */
    public function editAction($id_etab)
    {
        if (!$this->request->isPost()) {

            $etablissement = Etablissement::findFirstByid_etab($id_etab);
            if (!$etablissement) {
                $this->flash->error("etablissement was not found");

                $this->dispatcher->forward([
                    'controller' => "etablissement",
                    'action' => 'index'
                ]);

                return;
            }

            $this->view->id_etab = $etablissement->id_etab;

            $this->tag->setDefault("id_etab", $etablissement->id_etab);
            $this->tag->setDefault("nom", $etablissement->nom);
            $this->tag->setDefault("siret", $etablissement->siret);
            $this->tag->setDefault("longitude", $etablissement->longitude);
            $this->tag->setDefault("altitude", $etablissement->altitude);
            $this->tag->setDefault("entreprise_id_entreprise", $etablissement->entreprise_id_entreprise);

        }
    }

    /**
     * Creates a new etablissement
     */
    public function createAction()
    {
        if (!$this->request->isPost()) {
            $this->dispatcher->forward([
                'controller' => "etablissement",
                'action' => 'index'
            ]);

            return;
        }

        $etablissement = new Etablissement();
        $etablissement->nom = $this->request->getPost("nom");
        $etablissement->siret = $this->request->getPost("siret");
        $etablissement->longitude = $this->request->getPost("longitude");
        $etablissement->altitude = $this->request->getPost("altitude");
        $etablissement->entreprise_id_entreprise = $this->request->getPost("entreprise_id_entreprise");


        if (!$etablissement->save()) {
            foreach ($etablissement->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "etablissement",
                'action' => 'new'
            ]);

            return;
        }

        $this->flash->success("etablissement was created successfully");

        $this->dispatcher->forward([
            'controller' => "etablissement",
            'action' => 'index'
        ]);
    }

    /**
     * Saves a etablissement edited
     *
     */
    public function saveAction()
    {

        if (!$this->request->isPost()) {
            $this->dispatcher->forward([
                'controller' => "etablissement",
                'action' => 'index'
            ]);

            return;
        }

        $id_etab = $this->request->getPost("id_etab");
        $etablissement = Etablissement::findFirstByid_etab($id_etab);

        if (!$etablissement) {
            $this->flash->error("etablissement does not exist " . $id_etab);

            $this->dispatcher->forward([
                'controller' => "etablissement",
                'action' => 'index'
            ]);

            return;
        }
        $etablissement->nom = $this->request->getPost("nom");
        $etablissement->siret = $this->request->getPost("siret");
        $etablissement->longitude = $this->request->getPost("longitude");
        $etablissement->altitude = $this->request->getPost("altitude");
        $etablissement->entreprise_id_entreprise = $this->request->getPost("entreprise_id_entreprise");


        if (!$etablissement->save()) {

            foreach ($etablissement->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "etablissement",
                'action' => 'edit',
                'params' => [$etablissement->id_etab]
            ]);

            return;
        }

        $this->flash->success("etablissement was updated successfully");

        $this->dispatcher->forward([
            'controller' => "etablissement",
            'action' => 'index'
        ]);
    }

    /**
     * Deletes a etablissement
     *
     * @param string $id_etab
     */
    public function deleteAction($id_etab)
    {
        $etablissement = Etablissement::findFirstByid_etab($id_etab);
        if (!$etablissement) {
            $this->flash->error("etablissement was not found");

            $this->dispatcher->forward([
                'controller' => "etablissement",
                'action' => 'index'
            ]);

            return;
        }

        if (!$etablissement->delete()) {

            foreach ($etablissement->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "etablissement",
                'action' => 'search'
            ]);

            return;
        }

        $this->flash->success("etablissement was deleted successfully");

        $this->dispatcher->forward([
            'controller' => "etablissement",
            'action' => "index"
        ]);
    }

}
