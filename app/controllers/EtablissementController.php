<?php
 
use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;


class EtablissementController extends ControllerBase
{
    /**
     * Index action
     */
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
            $query = Criteria::fromInput($this->di, 'Etablissement', $_POST);
            $this->persistent->parameters = $query->getParams();
        } else {
            $numberPage = $this->request->getQuery("page", "int");
        }

        $parameters = $this->persistent->parameters;
        if (!is_array($parameters)) {
            $parameters = [];
        }
        $parameters["order"] = "siret";

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
     * @param string $siret
     */
    public function editAction($siret)
    {
        if (!$this->request->isPost()) {

            $etablissement = Etablissement::findFirstBysiret($siret);
            if (!$etablissement) {
                $this->flash->error("etablissement was not found");

                $this->dispatcher->forward([
                    'controller' => "etablissement",
                    'action' => 'index'
                ]);

                return;
            }

            $this->view->siret = $etablissement->siret;

            $this->tag->setDefault("siret", $etablissement->siret);
            $this->tag->setDefault("lantitude", $etablissement->lantitude);
            $this->tag->setDefault("longitude", $etablissement->longitude);
            $this->tag->setDefault("entreprise_siren", $etablissement->entreprise_siren);
            
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
        $etablissement->lantitude = $this->request->getPost("lantitude");
        $etablissement->longitude = $this->request->getPost("longitude");
        $etablissement->entreprise_siren = $this->request->getPost("entreprise_siren");
        

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

        $siret = $this->request->getPost("siret");
        $etablissement = Etablissement::findFirstBysiret($siret);

        if (!$etablissement) {
            $this->flash->error("etablissement does not exist " . $siret);

            $this->dispatcher->forward([
                'controller' => "etablissement",
                'action' => 'index'
            ]);

            return;
        }

        $etablissement->siret = $this->request->getPost("siret");
        $etablissement->lantitude = $this->request->getPost("lantitude");
        $etablissement->longitude = $this->request->getPost("longitude");
        $etablissement->entreprise_siren = $this->request->getPost("entreprise_siren");
        

        if (!$etablissement->save()) {

            foreach ($etablissement->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "etablissement",
                'action' => 'edit',
                'params' => [$etablissement->siret]
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
     * @param string $siret
     */
    public function deleteAction($siret)
    {
        $etablissement = Etablissement::findFirstBysiret($siret);
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
