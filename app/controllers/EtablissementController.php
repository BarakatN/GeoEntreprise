<?php
namespace Vokuro\Controllers;

use Phalcon\Flash\Direct as FlashDirect;
use Vokuro\Models\Etablissement;
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
            $query = Criteria::fromInput($this->di, 'Vokuro\Models\Etablissement', $_POST);
            $this->persistent->parameters = $query->getParams();
        } else {
            $numberPage = $this->request->getQuery("page", "int");
        }

        $parameters = $this->persistent->parameters;
        if (!is_array($parameters)) {
            $parameters = [];
        }
        $parameters["order"] = "id";

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
     * @param string $id
     */
    public function editAction($id)
    {

        if (!$this->request->isPost()) {

            $etablissement = Etablissement::findFirstByid($id);
            if (!$etablissement) {
                $this->flash->error("etablissement was not found");

                $this->dispatcher->forward([
                    'controller' => "etablissement",
                    'action' => 'index'
                ]);

                return;
            }

            $this->view->id = $etablissement->id;

            $this->tag->setDefault("id", $etablissement->id);
            $this->tag->setDefault("siret", $etablissement->siret);
            $this->tag->setDefault("longitude", $etablissement->longitude);
            $this->tag->setDefault("altitude", $etablissement->altitude);
            $this->tag->setDefault("entreprise_id", $etablissement->entreprise_id);

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
        $etablissement->siret = $this->request->getPost("siret");
        $etablissement->longitude = $this->request->getPost("longitude");
        $etablissement->altitude = $this->request->getPost("altitude");
        $etablissement->entreprise_id = $this->request->getPost("entreprise_id");


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

        $id = $this->request->getPost("id");
        $etablissement = Etablissement::findFirstByid($id);

        if (!$etablissement) {
            $this->flash->error("etablissement does not exist " . $id);

            $this->dispatcher->forward([
                'controller' => "etablissement",
                'action' => 'index'
            ]);

            return;
        }

        $etablissement->siret = $this->request->getPost("siret");
        $etablissement->longitude = $this->request->getPost("longitude");
        $etablissement->altitude = $this->request->getPost("altitude");
        $etablissement->entreprise_id = $this->request->getPost("entreprise_id");


        if (!$etablissement->save()) {

            foreach ($etablissement->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "etablissement",
                'action' => 'edit',
                'params' => [$etablissement->id]
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
     * @param string $id
     */
    public function deleteAction($id)
    {
      
        $etablissement = Etablissement::findFirstByid($id);
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
