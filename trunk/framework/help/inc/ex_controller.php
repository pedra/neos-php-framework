<?php
class Controller_inicial
            extends NEOS {
            
    function index(){
        _view::val('hello', 'Hello World'); //cria uma varíavel para as views
        _view::set('home'); //chama a view "home"
    }
}                
