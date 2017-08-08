<?php
 
use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;


class DepartementController extends ControllerBase
{
    /**
     * Index action
     */
    public function indexAction()
    {
        $this->persistent->parameters = null;
    }

    /**
     * Searches for departement
     */
    public function searchAction()
    {
        $numberPage = 1;
        if ($this->request->isPost()) {
            $query = Criteria::fromInput($this->di, 'Departement', $_POST);
            $this->persistent->parameters = $query->getParams();
        } else {
            $numberPage = $this->request->getQuery("page", "int");
        }

        $parameters = $this->persistent->parameters;
        if (!is_array($parameters)) {
            $parameters = [];
        }
        $parameters["order"] = "num_dept";

        $departement = Departement::find($parameters);
        if (count($departement) == 0) {
            $this->flash->notice("The search did not find any departement");

            $this->dispatcher->forward([
                "controller" => "departement",
                "action" => "index"
            ]);

            return;
        }

        $paginator = new Paginator([
            'data' => $departement,
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
     * Edits a departement
     *
     * @param string $num_dept
     */
    public function editAction($num_dept)
    {
        if (!$this->request->isPost()) {

            $departement = Departement::findFirstBynum_dept($num_dept);
            if (!$departement) {
                $this->flash->error("departement was not found");

                $this->dispatcher->forward([
                    'controller' => "departement",
                    'action' => 'index'
                ]);

                return;
            }

            $this->view->num_dept = $departement->num_dept;

            $this->tag->setDefault("num_dept", $departement->num_dept);
            $this->tag->setDefault("libelle", $departement->libelle);
            $this->tag->setDefault("etablissement_siret", $departement->etablissement_siret);
            $this->tag->setDefault("contact_cin", $departement->contact_cin);
            
        }
    }

    /**
     * Creates a new departement
     */
    public function createAction()
    {
        if (!$this->request->isPost()) {
            $this->dispatcher->forward([
                'controller' => "departement",
                'action' => 'index'
            ]);

            return;
        }

        $departement = new Departement();
        $departement->num_dept = $this->request->getPost("num_dept");
        $departement->libelle = $this->request->getPost("libelle");
        $departement->etablissement_siret = $this->request->getPost("etablissement_siret");
        $departement->contact_cin = $this->request->getPost("contact_cin");
        

        if (!$departement->save()) {
            foreach ($departement->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "departement",
                'action' => 'new'
            ]);

            return;
        }

        $this->flash->success("departement was created successfully");

        $this->dispatcher->forward([
            'controller' => "departement",
            'action' => 'index'
        ]);
    }

    /**
     * Saves a departement edited
     *
     */
    public function saveAction()
    {

        if (!$this->request->isPost()) {
            $this->dispatcher->forward([
                'controller' => "departement",
                'action' => 'index'
            ]);

            return;
        }

        $num_dept = $this->request->getPost("num_dept");
        $departement = Departement::findFirstBynum_dept($num_dept);

        if (!$departement) {
            $this->flash->error("departement does not exist " . $num_dept);

            $this->dispatcher->forward([
                'controller' => "departement",
                'action' => 'index'
            ]);

            return;
        }

        $departement->num_dept = $this->request->getPost("num_dept");
        $departement->libelle = $this->request->getPost("libelle");
        $departement->etablissement_siret = $this->request->getPost("etablissement_siret");
        $departement->contact_cin = $this->request->getPost("contact_cin");
        

        if (!$departement->save()) {

            foreach ($departement->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "departement",
                'action' => 'edit',
                'params' => [$departement->num_dept]
            ]);

            return;
        }

        $this->flash->success("departement was updated successfully");

        $this->dispatcher->forward([
            'controller' => "departement",
            'action' => 'index'
        ]);
    }

    /**
     * Deletes a departement
     *
     * @param string $num_dept
     */
    public function deleteAction($num_dept)
    {
        $departement = Departement::findFirstBynum_dept($num_dept);
        if (!$departement) {
            $this->flash->error("departement was not found");

            $this->dispatcher->forward([
                'controller' => "departement",
                'action' => 'index'
            ]);

            return;
        }

        if (!$departement->delete()) {

            foreach ($departement->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "departement",
                'action' => 'search'
            ]);

            return;
        }

        $this->flash->success("departement was deleted successfully");

        $this->dispatcher->forward([
            'controller' => "departement",
            'action' => "index"
        ]);
    }

}
