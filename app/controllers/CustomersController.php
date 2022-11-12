<?php
 
use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;


class CustomersController extends ControllerBase
{
    /**
     * Index action
     */
    public function indexAction()
    {
        $this->persistent->parameters = null;
    }

    /**
     * Searches for customers
     */
    public function searchAction()
    {
        $numberPage = 1;
        if ($this->request->isPost()) {
            $query = Criteria::fromInput($this->di, 'Customers', $_POST);
            $this->persistent->parameters = $query->getParams();
        } else {
            $numberPage = $this->request->getQuery("page", "int");
        }

        $parameters = $this->persistent->parameters;
        if (!is_array($parameters)) {
            $parameters = [];
        }
        $parameters["order"] = "cst_id";

        $customers = Customers::find($parameters);
        if (count($customers) == 0) {
            $this->flash->notice("The search did not find any customers");

            $this->dispatcher->forward([
                "controller" => "customers",
                "action" => "index"
            ]);

            return;
        }

        $paginator = new Paginator([
            'data' => $customers,
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
     * Edits a customer
     *
     * @param string $cst_id
     */
    public function editAction($cst_id)
    {
        if (!$this->request->isPost()) {

            $customer = Customers::findFirstBycst_id($cst_id);
            if (!$customer) {
                $this->flash->error("customer was not found");

                $this->dispatcher->forward([
                    'controller' => "customers",
                    'action' => 'index'
                ]);

                return;
            }

            $this->view->cst_id = $customer->cst_id;

            $this->tag->setDefault("cst_id", $customer->cst_id);
            $this->tag->setDefault("cst_status_flag", $customer->cst_status_flag);
            $this->tag->setDefault("cst_name_last", $customer->cst_name_last);
            $this->tag->setDefault("cst_name_first", $customer->cst_name_first);
            
        }
    }

    /**
     * Creates a new customer
     */
    public function createAction()
    {
        if (!$this->request->isPost()) {
            $this->dispatcher->forward([
                'controller' => "customers",
                'action' => 'index'
            ]);

            return;
        }

        $customer = new Customers();
        $customer->cstStatusFlag = $this->request->getPost("cst_status_flag");
        $customer->cstNameLast = $this->request->getPost("cst_name_last");
        $customer->cstNameFirst = $this->request->getPost("cst_name_first");
        

        if (!$customer->save()) {
            foreach ($customer->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "customers",
                'action' => 'new'
            ]);

            return;
        }

        $this->flash->success("customer was created successfully");

        $this->dispatcher->forward([
            'controller' => "customers",
            'action' => 'index'
        ]);
    }

    /**
     * Saves a customer edited
     *
     */
    public function saveAction()
    {

        if (!$this->request->isPost()) {
            $this->dispatcher->forward([
                'controller' => "customers",
                'action' => 'index'
            ]);

            return;
        }

        $cst_id = $this->request->getPost("cst_id");
        $customer = Customers::findFirstBycst_id($cst_id);

        if (!$customer) {
            $this->flash->error("customer does not exist " . $cst_id);

            $this->dispatcher->forward([
                'controller' => "customers",
                'action' => 'index'
            ]);

            return;
        }

        $customer->cstStatusFlag = $this->request->getPost("cst_status_flag");
        $customer->cstNameLast = $this->request->getPost("cst_name_last");
        $customer->cstNameFirst = $this->request->getPost("cst_name_first");
        

        if (!$customer->save()) {

            foreach ($customer->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "customers",
                'action' => 'edit',
                'params' => [$customer->cst_id]
            ]);

            return;
        }

        $this->flash->success("customer was updated successfully");

        $this->dispatcher->forward([
            'controller' => "customers",
            'action' => 'index'
        ]);
    }

    /**
     * Deletes a customer
     *
     * @param string $cst_id
     */
    public function deleteAction($cst_id)
    {
        $customer = Customers::findFirstBycst_id($cst_id);
        if (!$customer) {
            $this->flash->error("customer was not found");

            $this->dispatcher->forward([
                'controller' => "customers",
                'action' => 'index'
            ]);

            return;
        }

        if (!$customer->delete()) {

            foreach ($customer->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "customers",
                'action' => 'search'
            ]);

            return;
        }

        $this->flash->success("customer was deleted successfully");

        $this->dispatcher->forward([
            'controller' => "customers",
            'action' => "index"
        ]);
    }

}
