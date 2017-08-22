<?php
namespace GeoEntreprise\Controllers;

use GeoEntreprise\Models\Transport;
use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;


class TransportController extends ControllerBase
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
     * Searches for transport
     */
    public function searchAction()
    {

        $numberPage = 1;
        if ($this->request->isPost()) {
            $query = Criteria::fromInput($this->di, 'GeoEntreprise\Models\Transport', $_POST);
            $this->persistent->parameters = $query->getParams();
        } else {
            $numberPage = $this->request->getQuery("page", "int");
        }

        $parameters = $this->persistent->parameters;
        if (!is_array($parameters)) {
            $parameters = [];
        }
        $parameters["order"] = "id_transport";

        $transport = Transport::find($parameters);
        if (count($transport) == 0) {
            $this->flash->notice("The search did not find any transport");

            $this->dispatcher->forward([
                "controller" => "transport",
                "action" => "index"
            ]);

            return;
        }

        $paginator = new Paginator([
            'data' => $transport,
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
     * Edits a transport
     *
     * @param string $id
     */
    public function editAction($id_transport)
    {

        if (!$this->request->isPost()) {

            $transport = Transport::findFirstByid_transport($id_transport);
            if (!$transport) {
                $this->flash->error("transport was not found");

                $this->dispatcher->forward([
                    'controller' => "transport",
                    'action' => 'index'
                ]);

                return;
            }

            $this->view->id_transport = $transport->id_transport;

            $this->tag->setDefault("id_transport", $transport->id_transport);
            $this->tag->setDefault("matricule", $transport->matricule);
            $this->tag->setDefault("modele", $transport->modele);
            $this->tag->setDefault("type", $transport->type);
            $this->tag->setDefault("entreprise_id_entreprise", $transport->entreprise_id_entreprise);

        }
    }

    /**
     * Creates a new transport
     */
    public function createAction()
    {

        if (!$this->request->isPost()) {
            $this->dispatcher->forward([
                'controller' => "transport",
                'action' => 'index'
            ]);

            return;
        }

        $transport = new Transport();
        $transport->matricule = $this->request->getPost("matricule");
        $transport->modele = $this->request->getPost("modele");
        $transport->type = $this->request->getPost("type");
        $transport->entreprise_id_entreprise = $this->request->getPost("entreprise_id_entreprise");


        if (!$transport->save()) {
            foreach ($transport->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "transport",
                'action' => 'new'
            ]);

            return;
        }

        $this->flash->success("transport was created successfully");

        $this->dispatcher->forward([
            'controller' => "transport",
            'action' => 'index'
        ]);
    }

    /**
     * Saves a transport edited
     *
     */
    public function saveAction()
    {

        if (!$this->request->isPost()) {
            $this->dispatcher->forward([
                'controller' => "transport",
                'action' => 'index'
            ]);

            return;
        }

        $id_transport = $this->request->getPost("id_transport");
        $transport = Transport::findFirstByid_transport($id_transport);

        if (!$transport) {
            $this->flash->error("transport does not exist " . $id_transport);

            $this->dispatcher->forward([
                'controller' => "transport",
                'action' => 'index'
            ]);

            return;
        }

        $transport->matricule = $this->request->getPost("matricule");
        $transport->modele = $this->request->getPost("modele");
        $transport->type = $this->request->getPost("type");
        $transport->entreprise_id_entreprise = $this->request->getPost("entreprise_id_entreprise");


        if (!$transport->save()) {

            foreach ($transport->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "transport",
                'action' => 'edit',
                'params' => [$transport->id_transport]
            ]);

            return;
        }

        $this->flash->success("transport was updated successfully");

        $this->dispatcher->forward([
            'controller' => "transport",
            'action' => 'index'
        ]);
    }

    /**
     * Deletes a transport
     *
     * @param string $id
     */
    public function deleteAction($id_transport)
    {

        $transport = Transport::findFirstByid_transport($id_transport);
        if (!$transport) {
            $this->flash->error("transport was not found");

            $this->dispatcher->forward([
                'controller' => "transport",
                'action' => 'index'
            ]);

            return;
        }

        if (!$transport->delete()) {

            foreach ($transport->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "transport",
                'action' => 'search'
            ]);

            return;
        }

        $this->flash->success("transport was deleted successfully");

        $this->dispatcher->forward([
            'controller' => "transport",
            'action' => "index"
        ]);
    }

}
