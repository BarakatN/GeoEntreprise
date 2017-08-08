<?php
 
use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;


class EntrepriseController extends ControllerBase
{
    /**
     * Index action
     */
    public function indexAction()
    {
        $this->persistent->parameters = null;
    }

    /**
     * Searches for entreprise
     */
    public function searchAction()
    {
        $numberPage = 1;
        if ($this->request->isPost()) {
            $query = Criteria::fromInput($this->di, 'Entreprise', $_POST);
            $this->persistent->parameters = $query->getParams();
        } else {
            $numberPage = $this->request->getQuery("page", "int");
        }

        $parameters = $this->persistent->parameters;
        if (!is_array($parameters)) {
            $parameters = [];
        }
        $parameters["order"] = "siren";

        $entreprise = Entreprise::find($parameters);
        if (count($entreprise) == 0) {
            $this->flash->notice("The search did not find any entreprise");

            $this->dispatcher->forward([
                "controller" => "entreprise",
                "action" => "index"
            ]);

            return;
        }

        $paginator = new Paginator([
            'data' => $entreprise,
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
     * Edits a entreprise
     *
     * @param string $siren
     */
    public function editAction($siren)
    {
        if (!$this->request->isPost()) {

            $entreprise = Entreprise::findFirstBysiren($siren);
            if (!$entreprise) {
                $this->flash->error("entreprise was not found");

                $this->dispatcher->forward([
                    'controller' => "entreprise",
                    'action' => 'index'
                ]);

                return;
            }

            $this->view->siren = $entreprise->siren;

            $this->tag->setDefault("siren", $entreprise->siren);
            $this->tag->setDefault("denomination", $entreprise->denomination);
            $this->tag->setDefault("ville", $entreprise->ville);
            $this->tag->setDefault("pays", $entreprise->pays);
            $this->tag->setDefault("code _postal", $entreprise->code _postal);
            $this->tag->setDefault("capital _social", $entreprise->capital _social);
            $this->tag->setDefault("forme_juridique", $entreprise->forme_juridique);
            $this->tag->setDefault("immatriculation", $entreprise->immatriculation);
            $this->tag->setDefault("ca", $entreprise->ca);
            $this->tag->setDefault("date_creation", $entreprise->date_creation);
            $this->tag->setDefault("rayonnement", $entreprise->rayonnement);
            $this->tag->setDefault("transport_matricule", $entreprise->transport_matricule);
            
        }
    }

    /**
     * Creates a new entreprise
     */
    public function createAction()
    {
        if (!$this->request->isPost()) {
            $this->dispatcher->forward([
                'controller' => "entreprise",
                'action' => 'index'
            ]);

            return;
        }

        $entreprise = new Entreprise();
        $entreprise->siren = $this->request->getPost("siren");
        $entreprise->denomination = $this->request->getPost("denomination");
        $entreprise->ville = $this->request->getPost("ville");
        $entreprise->pays = $this->request->getPost("pays");
        $entreprise->code _postal = $this->request->getPost("code _postal");
        $entreprise->capital _social = $this->request->getPost("capital _social");
        $entreprise->forme_juridique = $this->request->getPost("forme_juridique");
        $entreprise->immatriculation = $this->request->getPost("immatriculation");
        $entreprise->ca = $this->request->getPost("ca");
        $entreprise->date_creation = $this->request->getPost("date_creation");
        $entreprise->rayonnement = $this->request->getPost("rayonnement");
        $entreprise->transport_matricule = $this->request->getPost("transport_matricule");
        

        if (!$entreprise->save()) {
            foreach ($entreprise->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "entreprise",
                'action' => 'new'
            ]);

            return;
        }

        $this->flash->success("entreprise was created successfully");

        $this->dispatcher->forward([
            'controller' => "entreprise",
            'action' => 'index'
        ]);
    }

    /**
     * Saves a entreprise edited
     *
     */
    public function saveAction()
    {

        if (!$this->request->isPost()) {
            $this->dispatcher->forward([
                'controller' => "entreprise",
                'action' => 'index'
            ]);

            return;
        }

        $siren = $this->request->getPost("siren");
        $entreprise = Entreprise::findFirstBysiren($siren);

        if (!$entreprise) {
            $this->flash->error("entreprise does not exist " . $siren);

            $this->dispatcher->forward([
                'controller' => "entreprise",
                'action' => 'index'
            ]);

            return;
        }

        $entreprise->siren = $this->request->getPost("siren");
        $entreprise->denomination = $this->request->getPost("denomination");
        $entreprise->ville = $this->request->getPost("ville");
        $entreprise->pays = $this->request->getPost("pays");
        $entreprise->code _postal = $this->request->getPost("code _postal");
        $entreprise->capital _social = $this->request->getPost("capital _social");
        $entreprise->forme_juridique = $this->request->getPost("forme_juridique");
        $entreprise->immatriculation = $this->request->getPost("immatriculation");
        $entreprise->ca = $this->request->getPost("ca");
        $entreprise->date_creation = $this->request->getPost("date_creation");
        $entreprise->rayonnement = $this->request->getPost("rayonnement");
        $entreprise->transport_matricule = $this->request->getPost("transport_matricule");
        

        if (!$entreprise->save()) {

            foreach ($entreprise->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "entreprise",
                'action' => 'edit',
                'params' => [$entreprise->siren]
            ]);

            return;
        }

        $this->flash->success("entreprise was updated successfully");

        $this->dispatcher->forward([
            'controller' => "entreprise",
            'action' => 'index'
        ]);
    }

    /**
     * Deletes a entreprise
     *
     * @param string $siren
     */
    public function deleteAction($siren)
    {
        $entreprise = Entreprise::findFirstBysiren($siren);
        if (!$entreprise) {
            $this->flash->error("entreprise was not found");

            $this->dispatcher->forward([
                'controller' => "entreprise",
                'action' => 'index'
            ]);

            return;
        }

        if (!$entreprise->delete()) {

            foreach ($entreprise->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "entreprise",
                'action' => 'search'
            ]);

            return;
        }

        $this->flash->success("entreprise was deleted successfully");

        $this->dispatcher->forward([
            'controller' => "entreprise",
            'action' => "index"
        ]);
    }

}
