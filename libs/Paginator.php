<?php

class Paginator {

    private $_nberPerPage; // Nbres de données Par Page
    private $_totalNberOfItems; //Nobre total de données à Paginner
    private $_nberOfPages; // Nbres
    private $_currentPage;

    public function __construct($nberPerPage, $totalNumber) {
        $this->_currentPage = 1;
        $this->setNberPerPage($nberPerPage);
        $this->setTotalNumberOfItems($totalNumber);
    }

    public function calculateNberOfPages() {
        return ceil($this->getTotalNumberOfItems() / $this->getNberPerPage());
    }

    public function buildPaginator($seperator = "&raquo;", $labell = "Page", $path = "") {
        $t = $this->calculateNberOfPages();
        $paginator = "<nav class='breadcrumbs'> <ul>";
        for ($i = 0; $i < $t; $i++) {
            if (($i + 1) != $this->getCurrentPage()) {                
                $paginator .= "<li class='active'> <a href='" . URL . $path . ($i + 1) . "' class='noActivePage btn btn-primary'><em> " . $labell . " " . ($i + 1) . "</em></a> </li>";
            } else {
                $paginator .= "<li> <a href='#'> " . $labell . " " . ($i + 1) . "</a> </li>";
            }

            if ($i + 1 < $t) {
                $paginator .= $seperator;
            }
        }
        $paginator .= "</ul></nav>";
        return $paginator;
    }

    public function getNberPerPage() {
        return $this->_nberPerPage;
    }

    public function setNberPerPage($n) {
        $this->_nberPerPage = $n;
    }

    public function getTotalNumberOfItems() {
        return $this->_totalNberOfItems;
    }

    public function setTotalNumberOfItems($n) {
        $this->_totalNberOfItems = $n;
    }

    public function getNumberOfPages() {
        return $this->_nberOfPages;
    }

    public function setNomberOfPages($n) {
        $this->_nberOfPages = $n;
    }

    public function getCurrentPage() {
        return $this->_currentPage;
    }

    public function setCurrent($p) {
        if ($p > $this->calculateNberOfPages()) {
            $this->_currentPage = $this->calculateNberOfPages();
        } elseif ($p < 1) {
            $this->_currentPage = 1;
        } else {
            $this->_currentPage = $p;
        }
    }

}

?>