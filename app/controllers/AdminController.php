<?php
 
use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;


class AdminController extends ControllerBase
{
    /**
     * Index action
     */
    public function indexAction()
    {
        $this->persistent->parameters = null;
    }

    /**
     * Searches for admin
     */
    public function searchAction()
    {
        $numberPage = 1;
        if ($this->request->isPost()) {
            $query = Criteria::fromInput($this->di, 'Admin', $_POST);
            $this->persistent->parameters = $query->getParams();
        } else {
            $numberPage = $this->request->getQuery("page", "int");
        }

        $parameters = $this->persistent->parameters;
        if (!is_array($parameters)) {
            $parameters = [];
        }
        $parameters["order"] = "id";

        $admin = Admin::find($parameters);
        if (count($admin) == 0) {
            $this->flash->notice("The search did not find any admin");

            $this->dispatcher->forward([
                "controller" => "admin",
                "action" => "index"
            ]);

            return;
        }

        $paginator = new Paginator([
            'data' => $admin,
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
     * Edits a admin
     *
     * @param string $id
     */
    public function editAction($id)
    {
        if (!$this->request->isPost()) {

            $admin = Admin::findFirstByid($id);
            if (!$admin) {
                $this->flash->error("admin was not found");

                $this->dispatcher->forward([
                    'controller' => "admin",
                    'action' => 'index'
                ]);

                return;
            }

            $this->view->id = $admin->id;

            $this->tag->setDefault("id", $admin->id);
            $this->tag->setDefault("login", $admin->login);
            $this->tag->setDefault("password", $admin->password);
            
        }
    }

    /**
     * Creates a new admin
     */
    public function createAction()
    {
        if (!$this->request->isPost()) {
            $this->dispatcher->forward([
                'controller' => "admin",
                'action' => 'index'
            ]);

            return;
        }

        $admin = new Admin();
        $admin->id = $this->request->getPost("id");
        $admin->login = $this->request->getPost("login");
        $admin->password = $this->request->getPost("password");
        

        if (!$admin->save()) {
            foreach ($admin->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "admin",
                'action' => 'new'
            ]);

            return;
        }

        $this->flash->success("admin was created successfully");

        $this->dispatcher->forward([
            'controller' => "admin",
            'action' => 'index'
        ]);
    }

    /**
     * Saves a admin edited
     *
     */
    public function saveAction()
    {

        if (!$this->request->isPost()) {
            $this->dispatcher->forward([
                'controller' => "admin",
                'action' => 'index'
            ]);

            return;
        }

        $id = $this->request->getPost("id");
        $admin = Admin::findFirstByid($id);

        if (!$admin) {
            $this->flash->error("admin does not exist " . $id);

            $this->dispatcher->forward([
                'controller' => "admin",
                'action' => 'index'
            ]);

            return;
        }

        $admin->id = $this->request->getPost("id");
        $admin->login = $this->request->getPost("login");
        $admin->password = $this->request->getPost("password");
        

        if (!$admin->save()) {

            foreach ($admin->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "admin",
                'action' => 'edit',
                'params' => [$admin->id]
            ]);

            return;
        }

        $this->flash->success("admin was updated successfully");

        $this->dispatcher->forward([
            'controller' => "admin",
            'action' => 'index'
        ]);
    }

    /**
     * Deletes a admin
     *
     * @param string $id
     */
    public function deleteAction($id)
    {
        $admin = Admin::findFirstByid($id);
        if (!$admin) {
            $this->flash->error("admin was not found");

            $this->dispatcher->forward([
                'controller' => "admin",
                'action' => 'index'
            ]);

            return;
        }

        if (!$admin->delete()) {

            foreach ($admin->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "admin",
                'action' => 'search'
            ]);

            return;
        }

        $this->flash->success("admin was deleted successfully");

        $this->dispatcher->forward([
            'controller' => "admin",
            'action' => "index"
        ]);
    }

}
