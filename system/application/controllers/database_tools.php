<?php
class Database_Tools extends Controller {
    function index() {
        $this->load->view("database_tools");
    }

    function create_tables() {
        Doctrine::createTablesFromModels();
        redirect('/database_tools');
	}


	function load_data() {
        Doctrine_Manager::connection()->execute(
            'SET FOREIGN_KEY_CHECKS = 0');
			
        Doctrine::loadData(APPPATH.'/fixtures');
        redirect('/database_tools');
	}

    function reset_database() {
        Doctrine_Manager::connection()->export->dropDatabase('freeladder');
        Doctrine_Manager::connection()->export->createDatabase('freeladder');
        redirect('/database_tools');
    }

    function create_models() {
        $options = array(
            'packagesPrefix'  =>  'Plugin',
            'baseClassName'   =>  'Doctrine_Record',
            'suffix'          =>  '.php',
            'generateBaseClasses'   =>  false
        );

        Doctrine_Core::generateModelsFromYaml(APPPATH.'/schemas', APPPATH.'/models', $options);

        redirect('/database_tools');
    }
}


