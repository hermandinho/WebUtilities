<?php

class IndexController extends Controller {

    public function __construct() {
        parent::__construct();        
    }

    public function index() {
        //$this->loadModel("produit");
        //$listeProduits = $this->model->generateListeProduits();
        //$this->view->listeProduits = $listeProduits;
        //echo "TOTO";
        $this->view->render("about/index");
    }


    public function help($arg = false) {
        echo 'Here is the help Function with ' . $arg . "<br>";
    }

    public function logout() {
        Session::destroy();
        //$this->view->render("index/index");
        header("Location: .");
    }

    public function login() {
        //$this->view->render("layouts/Login");
    }
    
    public function S($id){// Liste tout les produit d'une sous catégorie
        $this->loadModel("produit");
        $listeProduits = $this->model->generateListeProduitsSousCat($id);
        $this->view->listeProduits = $listeProduits;
        //echo "TOTO";
        $this->view->render("index/index");
    }
    
    public function C($id){// Liste tout les produit d'une catégorie
        $this->loadModel("produit");
        $listeProduits = $this->model->generateListeProduitsCat($id);
        $this->view->listeProduits = $listeProduits;
        //echo "TOTO";
        $this->view->render("index/index");
    }

}