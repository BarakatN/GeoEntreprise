<?php
namespace Vokuro\Controllers ;

use Vokuro\Models\Transport;
use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;


class TransportController extends ControllerBase
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
     * Searches for transport
     */
    public function searchAction()
    {
      $this->view->setLayout("private");
        $numberPage = 1;
        if ($this->request->isPost()) {
            $query = Criteria::fromInput($this->di, 'Vokuro\Models\Transport', $_POST);
            $this->persistent->parameters = $query->getParams();
        } else {
            $numberPage = $this->request->getQuery("page", "int");
        }

        $parameters = $this->persistent->parameters;
        if (!is_array($parameters)) {
            $parameters = [];
        }
        $parameters["order"] = "matricule";

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
      $this->view->setLayout("private");
    }

    /**
     * Edits a transport
     *
     * @param string $matricule
     */
    public function editAction($matricule)
    {
      $this->view->setLayout("private");
        if (!$this->request->isPost()) {

            $transport = Transport::findFirstBymatricule($matricule);
            if (!$transport) {
                $this->flash->error("transport was not found");

                $this->dispatcher->forward([
                    'controller' => "transport",
                    'action' => 'index'
                ]);

                return;
            }

            $this->view->matricule = $transport->matricule;

            $this->tag->setDefault("matricule", $transport->matricule);
            $this->tag->setDefault("model", $transport->model);
            $this->tag->setDefault("type", $transport->type);
            $this->tag->setDefault("entreprise_siren", $transport->entreprise_siren);

        }
    }

    /**
     * Creates a new transport
     */
    public function createAction()
    {
        $this->view->setLayout("private");
        if (!$this->request->isPost()) {
            $this->dispatcher->forward([
                'controller' => "transport",
                'action' => 'index'
            ]);

            return;
        }

        $transport = new Transport();
        $transport->matricule = $this->request->getPost("matricule");
        $transport->model = $this->request->getPost("model");
        $transport->type = $this->request->getPost("type");
        $transport->entreprise_siren = $this->request->getPost("entreprise_siren");


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
      $this->view->setLayout("private");

        if (!$this->request->isPost()) {
            $this->dispatcher->forward([
                'controller' => "transport",
                'action' => 'index'
            ]);

            return;
        }

        $matricule = $this->request->getPost("matricule");
        $transport = Transport::findFirstBymatricule($matricule);

        if (!$transport) {
            $this->flash->error("transport does not exist " . $matricule);

            $this->dispatcher->forward([
                'controller' => "transport",
                'action' => 'index'
            ]);

            return;
        }

        $transport->matricule = $this->request->getPost("matricule");
        $transport->model = $this->request->getPost("model");
        $transport->type = $this->request->getPost("type");
        $transport->entreprise_siren = $this->request->getPost("entreprise_siren");


        if (!$transport->save()) {

            foreach ($transport->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "transport",
                'action' => 'edit',
                'params' => [$transport->matricule]
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
     * @param string $matricule
     */
    public function deleteAction($matricule)
    {
      $this->view->setLayout("private");
        $transport = Transport::findFirstBymatricule($matricule);
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
