<?php
namespace GeoEntreprise\Controllers;

use GeoEntreprise\Models\Contact;

use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;


class ContactController extends ControllerBase
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
     * Searches for contact
     */
    public function searchAction()
    {

        $numberPage = 1;
        if ($this->request->isPost()) {
            $query = Criteria::fromInput($this->di, 'GeoEntreprise\Models\Contact', $_POST);
            $this->persistent->parameters = $query->getParams();
        } else {
            $numberPage = $this->request->getQuery("page", "int");
        }

        $parameters = $this->persistent->parameters;
        if (!is_array($parameters)) {
            $parameters = [];
        }
        $parameters["order"] = "id_contact";

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

    }

    /**
     * Edits a contact
     *
     * @param string $id
     */
    public function editAction($id_contact)
    {

        if (!$this->request->isPost()) {

            $contact = Contact::findFirstByid_contact($id_contact);
            if (!$contact) {
                $this->flash->error("contact was not found");

                $this->dispatcher->forward([
                    'controller' => "contact",
                    'action' => 'index'
                ]);

                return;
            }

            $this->view->id_contact= $contact->id_contact;

            $this->tag->setDefault("id_contact", $contact->id_contact);
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

        if (!$this->request->isPost()) {
            $this->dispatcher->forward([
                'controller' => "contact",
                'action' => 'index'
            ]);

            return;
        }

        $id = $this->request->getPost("id_contact");
        $contact = Contact::findFirstByid_contact($id);

        if (!$contact) {
            $this->flash->error("contact does not exist " . $id_contact);

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
                'params' => [$contact->id]
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
     * @param string $id
     */
    public function deleteAction($id_contact)
    {

        $contact = Contact::findFirstByid_contact($id_contact);
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
