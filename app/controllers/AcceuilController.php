<?php
namespace GeoEntreprise\Controllers;
class AcceuilController extends ControllerBase
{

    public function indexAction()
    {
        $this->view->pick('acceuil/acceuil');
    }

}
