<?php

namespace Ganguera\BackendBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller {

    public function indexAction() {
        $data = [];
        return $this->render('BackendBundle:Default:index.html.twig', $data);
    }

}