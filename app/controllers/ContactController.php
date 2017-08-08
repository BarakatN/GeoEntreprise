<?php

 namespace Vokuro\Controllers ; 

use Phalcon\Mvc\Model\Criteria;
use Vokuro\Models\Contact;
use Phalcon\Paginator\Adapter\Model as Paginator;


class ContactController extends ControllerBase
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
     * Searches for contact
     */
    public function searchAction()
    {
        $this->view->setLayout("private");
        $numberPage = 1;
        if ($this->request->isPost()) {
            $query = Criteria::fromInput($this->di, 'Vokuro\Models\Contact', $_POST);
            $this->persistent->parameters = $query->getParams();
        } else {
            $numberPage = $this->request->getQuery("page", "int");
        }

        $parameters = $this->persistent->parameters;
        if (!is_array($parameters)) {
            $parameters = [];
        }
        $parameters["order"] = "cin";

        $contact = Contact::find($parameters);
        if (count($contact) == 0) {
            $this->flash->notice("The search did not find any contact");

            $this->dispatcher->forward([
                "controller" => "contact",
                "action" => "index"
            ]);

            return;
        }

        $paginator = new Paginator([
            'data' => $contact,
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
     * Edits a contact
     *
     * @param string $cin
     */
    public function editAction($cin)
    {
        $this->view->setLayout("private");
        if (!$this->request->isPost()) {

            $contact = Contact::findFirstBycin($cin);
            if (!$contact) {
                $this->flash->error("contact was not found");

                $this->dispatcher->forward([
                    'controller' => "contact",
                    'action' => 'index'
                ]);

                return;
            }

            $this->view->cin = $contact->cin;

            $this->tag->setDefault("cin", $contact->cin);
            $this->tag->setDefault("nom", $contact->nom);
            $this->tag->setDefault("prenom", $contact->prenom);
            $this->tag->setDefault("email", $contact->email);
            $this->tag->setDefault("date_affectation", $contact->date_affectation);
            
        }
    }

    /**
     * Creates a new contact
     */
    public function createAction()
    {
        $this->view->setLayout("private");
        if (!$this->request->isPost()) {
            $this->dispatcher->forward([
                'controller' => "contact",
                'action' => 'index'
            ]);

            return;
        }

        $contact = new Contact();
        $contact->cin = $this->request->getPost("cin");
        $contact->nom = $this->request->getPost("nom");
        $contact->prenom = $this->request->getPost("prenom");
        $contact->email = $this->request->getPost("email", "email");
        $contact->date_affectation = $this->request->getPost("date_affectation");
        

        if (!$contact->save()) {
            foreach ($contact->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "contact",
                'action' => 'new'
            ]);

            return;
        }

        $this->flash->success("contact was created successfully");

        $this->dispatcher->forward([
            'controller' => "contact",
            'action' => 'index'
        ]);
    }

    /**
     * Saves a contact edited
     *
     */
    public function saveAction()
    {
$this->view->setLayout("private");
        if (!$this->request->isPost()) {
            $this->dispatcher->forward([
                'controller' => "contact",
                'action' => 'index'
            ]);

            return;
        }

        $cin = $this->request->getPost("cin");
        $contact = Contact::findFirstBycin($cin);

        if (!$contact) {
            $this->flash->error("contact does not exist " . $cin);

            $this->dispatcher->forward([
                'controller' => "contact",
                'action' => 'index'
            ]);

            return;
        }

        $contact->cin = $this->request->getPost("cin");
        $contact->nom = $this->request->getPost("nom");
        $contact->prenom = $this->request->getPost("prenom");
        $contact->email = $this->request->getPost("email", "email");
        $contact->date_affectation = $this->request->getPost("date_affectation");
        

        if (!$contact->save()) {

            foreach ($contact->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "contact",
                'action' => 'edit',
                'params' => [$contact->cin]
            ]);

            return;
        }

        $this->flash->success("contact was updated successfully");

        $this->dispatcher->forward([
            'controller' => "contact",
            'action' => 'index'
        ]);
    }

    /**
     * Deletes a contact
     *
     * @param string $cin
     */
    public function deleteAction($cin)
    { $this->view->setLayout("private");
        $contact = Contact::findFirstBycin($cin);
        if (!$contact) {
            $this->flash->error("contact was not found");

            $this->dispatcher->forward([
                'controller' => "contact",
                'action' => 'index'
            ]);

            return;
        }

        if (!$contact->delete()) {

            foreach ($contact->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "contact",
                'action' => 'search'
            ]);

            return;
        }

        $this->flash->success("contact was deleted successfully");

        $this->dispatcher->forward([
            'controller' => "contact",
            'action' => "index"
        ]);
    }

}
