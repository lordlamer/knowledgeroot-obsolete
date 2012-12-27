<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap {

    /**
     * init autoloader
     */
    protected function _initAutoload() {
	try {
	    // load required class
	    require_once('Zend/Loader.php');

	    // init autoloader
	    $autoloader = Zend_Loader_Autoloader::getInstance();

	    // register charm
	    $autoloader->registerNamespace('Knowledgeroot_');

	    // save in registry
	    $registry = Knowledgeroot_Registry::getInstance();
	    $registry->set('loader', $autoloader);

	    return $autoloader;
	} catch (Exception $e) {
	    echo $e->getMessage();
	    die('could not create autoloader');
	}
    }

    /**
     * load config file
     */
    protected function _initConfig() {
	try {
	    // load config
	    $config = new Zend_Config_Ini(PROJECT_PATH . '/config/app.ini');

	    // save config in registry
	    Knowledgeroot_Registry::set('config', $config);

	    return $config;
	} catch (Exception $e) {
	    echo $e->getMessage();
	    die('no config file');
	}
    }

    /**
     * init date and time
     */
    protected function _initDateTime() {
	try {
	    // load config
	    $this->bootstrap('config');

	    // get config
	    $config = Knowledgeroot_Registry::get('config');

	    // set timezone
	    date_default_timezone_set($config->base->timezone);
	} catch (Exception $e) {
	    echo $e->getMessage();
	    die('could not create output compression');
	}
    }

    /**
     * init output compression
     */
    protected function _initOutputCompression() {
	try {
	    // load config
	    $this->bootstrap('config');

	    // get config
	    $config = Knowledgeroot_Registry::get('config');

	    if ($config->output->compression) {
		if ($config->output->level) {
		    ini_set('zlib.output_compression_level', $config->output->level);
		}

		ob_start('ob_gzhandler');
	    }
	} catch (Exception $e) {
	    echo $e->getMessage();
	    die('could not create output compression');
	}
    }

    /**
     * init log
     */
    protected function _initLog() {
	try {
	    // load config
	    $this->bootstrap('config');

	    // get config
	    $config = Knowledgeroot_Registry::get('config');

	    // create log
	    $logger = new Zend_Log();

	    // create writer
	    $writer = new Zend_Log_Writer_Stream($config->log->file);

	    // add writer
	    $logger->addWriter($writer);

	    // save logger in registry
	    Knowledgeroot_Registry::set('log', $logger);

	    return $logger;
	} catch (Exception $e) {
	    echo $e->getMessage();
	    die('could not create log');
	}
    }

    /**
     * init debug
     */
    protected function _initDebug() {
	try {
	    // load debug
	    $debug = new Zend_Debug();

	    // save debug to registry
	    Knowledgeroot_Registry::set('debug', $debug);

	    return $debug;
	} catch (Exception $e) {
	    echo $e->getMessage();
	    die('could not create debug object');
	}
    }

    /**
     * init session
     */
    protected function _initSession() {
	try {
	    // load config
	    $this->bootstrap('config');

	    // get config
	    $config = Knowledgeroot_Registry::get('config');

	    // start session
	    Zend_Session::start($config->session->toArray());

	    // save session to registry
	    $session = new Zend_Session_Namespace('default');
	    Knowledgeroot_Registry::set('session', $session);

	    // set guest user if user does not exists
	    // get new session namespace and save data
	    $session = new Zend_Session_Namespace('user');
	    if(!$session->valid) {
		$session->valid = false;
		$session->id = 0;
		$session->login = 'guest';
		$session->language = 'en_US';
	    }

	    return $session;
	} catch (Exception $e) {
	    echo $e->getMessage();
	    die('Could not create session');
	}
    }

    /**
     * init routes
     */
    protected function _initRoutes() {
	try {
	    // load controller
	    //$this->bootstrap('controller');
	    // add routes
	    $router = Zend_Controller_Front::getInstance()->getRouter();


	    // login
	    $router->addRoute('login', new Zend_Controller_Router_Route('login', array(
			'controller' => 'index',
			'action' => 'login')));

	    // logout
	    $router->addRoute('logout', new Zend_Controller_Router_Route('logout', array(
			'controller' => 'index',
			'action' => 'logout')));

	    // settings
	    $router->addRoute('settings', new Zend_Controller_Router_Route('settings', array(
			'controller' => 'settings',
			'action' => 'index')));

	    //route: page/:pageid
	    $router->addRoute('pagelist', new Zend_Controller_Router_Route_Regex('page/(\d+)', array(
			'controller' => 'page',
			'action' => 'index'),
			    array(
				1 => 'id',
			    )
	    ));

	    //route: page/new/:pageid
	    $router->addRoute('pagenew', new Zend_Controller_Router_Route_Regex('page/new/(\d+)', array(
			'controller' => 'page',
			'action' => 'new'),
			    array(
				1 => 'id',
			    )
	    ));

	    //route: page/edit/:pageid
	    $router->addRoute('pageedit', new Zend_Controller_Router_Route_Regex('page/edit/(\d+)', array(
			'controller' => 'page',
			'action' => 'edit'),
			    array(
				1 => 'id',
			    )
	    ));

	    //route: page/delete/:pageid
	    $router->addRoute('pagedelete', new Zend_Controller_Router_Route_Regex('page/delete/(\d+)', array(
			'controller' => 'page',
			'action' => 'delete'),
			    array(
				1 => 'id',
			    )
	    ));

	    //route: contet/new/:pageid
	    $router->addRoute('contentnew', new Zend_Controller_Router_Route_Regex('content/new/(\d*)', array(
			'controller' => 'content',
			'action' => 'new'),
			    array(
				1 => 'id',
			    )
	    ));

	    //route: content/edit/:contentid
	    $router->addRoute('contentedit', new Zend_Controller_Router_Route_Regex('content/edit/(\d+)', array(
			'controller' => 'content',
			'action' => 'edit'),
			    array(
				1 => 'id',
			    )
	    ));

	    //route: content/delete/:contentid/page/:pageid
	    $router->addRoute('contentdelete', new Zend_Controller_Router_Route_Regex('content/delete/(\d+)', array(
			'controller' => 'content',
			'action' => 'delete'),
			    array(
				1 => 'id',
			    )
	    ));

	    //route: print/:contentid
	    $router->addRoute('contentprint', new Zend_Controller_Router_Route_Regex('print/(\d+)', array(
			'controller' => 'content',
			'action' => 'print'),
			    array(
				1 => 'id',
			    )
	    ));

	    //route: language/:language
	    $router->addRoute('language', new Zend_Controller_Router_Route_Regex('language/(.+)', array(
			'controller' => 'index',
			'action' => 'language'),
			    array(
				1 => 'language',
			    )
	    ));

	    //route: download/:fileid
	    $router->addRoute('download', new Zend_Controller_Router_Route_Regex('download/(\d+).*', array(
			'controller' => 'file',
			'action' => 'download'),
			    array(
				1 => 'id',
			    )
	    ));

	    //route: file/delete/:fileid
	    $router->addRoute('filedelete', new Zend_Controller_Router_Route_Regex('file/delete/(\d+)', array(
			'controller' => 'file',
			'action' => 'delete'),
			    array(
				1 => 'id',
			    )
	    ));
	} catch (Exception $e) {
	    echo $e->getMessage();
	    die('could not create routes');
	}
    }

    /**
     * init cache
     */
    protected function _initCache() {
	try {
	    // load config
	    $this->bootstrap('config');

	    // get config
	    $config = Knowledgeroot_Registry::get('config');

	    // create cache
	    $cache = Zend_Cache::factory($config->cache->frontend->name, $config->cache->backend->name, $config->cache->frontend->options->toArray(), $config->cache->backend->options->toArray());

	    // save cache
	    Knowledgeroot_Registry::set('cache', $cache);

	    return $cache;
	} catch (Zend_Exception $e) {
	    echo $e->getMessage();
	    die('could not create cache');
	}
    }

    /**
     * init database
     */
    protected function _initDatabase() {
	try {
	    // init config
	    $this->bootstrap('config');

	    // init cache
	    $this->bootstrap('cache');

	    // get config
	    $config = Knowledgeroot_Registry::get('config');

	    // get cache
	    $cache = Knowledgeroot_Registry::get('cache');

	    // make database connect
	    $db = Zend_Db::factory($config->database);

	    // set default adapter
	    Zend_Db_Table_Abstract::setDefaultAdapter($db);

	    // set cache for metadata
	    Zend_Db_Table_Abstract::setDefaultMetadataCache($cache);

	    // use profiler?
	    if($config->database->profiler) {
		// init firebug profiler
		$profiler = new Zend_Db_Profiler_Firebug('All Database Queries:');

		// enable it
		$profiler->setEnabled(true);

		// attach profiler to db adapter
		$db->setProfiler($profiler);
	    }

	    // save db handle in registry
	    Knowledgeroot_Registry::set('db', $db);

	    return $db;
	} catch (Zend_Exception $e) {
	    echo $e->getMessage();
	    die('no database connection');
	}
    }

    /**
     * init locale
     */
    protected function _initLocale() {
	try {
	    // init config
	    $this->bootstrap('config');

	    // get config
	    $config = Knowledgeroot_Registry::get('config');

	    // init Zend_Locale
	    Knowledgeroot_Registry::set('Zend_Locale', new Knowledgeroot_Locale($config->base->locale));
	} catch (Zend_Exception $e) {
	    echo $e->getMessage();
	    die('no locales');
	}
    }

    /**
     * init translation
     */
    protected function _initTranslation() {
	try {
	    // init config
	    $this->bootstrap('config');

	    // init cache
	    $this->bootstrap('cache');

	    // get config
	    $config = Knowledgeroot_Registry::get('config');

	    // get cache
	    $cache = Knowledgeroot_Registry::get('cache');

	    // load translations
	    $translate = new Knowledgeroot_Translation();
	    $translate->loadTranslations($config->translation->folder);
	    $translate->setLocale($config->base->locale);

	    // save in registry
	    Knowledgeroot_Registry::set('translate', $translate);

	    // create zend translate object
	    $zTranslate = new Zend_Translate(
			    array(
				'adapter' => 'gettext',
				'content' => $translate->getLocaleFile(),
				'locale' => $translate->getLocale()
			    )
	    );

	    // set cache
	    $zTranslate->setCache($cache);

	    // save in Zend_Translate in registry
	    Knowledgeroot_Registry::set('Zend_Translate', $zTranslate);
	} catch (Zend_Exception $e) {
	    echo $e->getMessage();
	    die('no translation');
	}
    }

    /**
     * init acl
     */
    protected function _initAcl() {
	try {
	    // init config
	    $this->bootstrap('config');

	    // init acl
	    $acl = new Knowledgeroot_Acl();

	    // load acl from
	    $acl->load();

	    // save acl in registry
	    Knowledgeroot_Registry::set('acl', $acl);
	} catch (Zend_Exception $e) {
	    echo $e->getMessage();
	    die('no acl');
	}
    }

    /**
     * init auth
     */
    protected function _initAuth() {

    }

    /**
     * init layout
     */
    protected function _initLayout() {
	try {
	    // load config
	    $this->bootstrap('config');

	    // get config
	    $config = Knowledgeroot_Registry::get('config');

	    // start mvc
	    Zend_Layout::startMvc();

	    // get layout instance
	    $layout = Zend_Layout::getMvcInstance();

	    // set layout path
	    $layout->setLayoutPath($config->production->resources->layout->layoutPath);

	    // set layout name
	    $layout->setLayout($config->production->resources->layout->layout);

	    // set version
	    $layout->version = $config->base->version;

	} catch (Exception $e) {
	    echo $e->getMessage();
	    die('could not create layout');
	}
    }

    /**
     * init search with lucene
     */
    protected function _initLucene() {
	try {
	    // load config
	    $this->bootstrap('config');

	    // get config
	    $config = Knowledgeroot_Registry::get('config');

	    try {
		// open lucene index
		$lucene = Zend_Search_Lucene::open($config->lucene->save_path);
	    } catch (Zend_Search_Lucene_Exception $e) {
		try {
		    // create lucene index
		    $lucene = Zend_Search_Lucene::create($config->lucene->save_path);
		} catch (Exception $e) {
		    throw new Exception('Could not create index for lucene');
		}
	    }

	    // save object in registry
	    Knowledgeroot_Registry::set('lucene', $lucene);

	    return $lucene;
	} catch (Exception $e) {
	    echo $e->getMessage();
	    die('could not create search');
	}
    }

    /**
     * init email
     */
    protected function _initMail() {
	try {
	    // load config
	    $this->bootstrap('config');

	    // get config
	    $config = Knowledgeroot_Registry::get('config');

	    // create smtp mail transport object
	    if (strtolower($config->mail->transport) == 'smtp')
		$transport = new Zend_Mail_Transport_Smtp($config->mail->host, $config->mail->options->toArray());

	    // create sendmail mail transport object
	    if (strtolower($config->mail->transport) == 'php')
		$transport = new Zend_Mail_Transport_Sendmail($config->mail->parameters);

	    // set transport as default transport
	    Zend_Mail::setDefaultTransport($transport);

	    return $transport;
	} catch (Exception $e) {
	    echo $e->getMessage();
	    die('could not create mail transport object');
	}
    }

    /**
     * init controller
     */
    protected function _initController() {
	try {
	    // load config
	    $this->bootstrap('config');

	    // load layout
	    $this->bootstrap('layout');

	    // get config
	    $config = Knowledgeroot_Registry::get('config');

	    // get controller instance
	    $controller = Zend_Controller_Front::getInstance();

	    // set controller directory
	    $controller->setControllerDirectory(
		    $config->base->base_path . '/application/controller'
	    );

	    // disable view renderer
	    //$controller->setParam('noViewRenderer', true);
	    // enabl exceptions
	    $controller->throwExceptions(true);

	    // enable error handler
	    $controller->setParam('noErrorHandler', true);

	    return $controller;
	} catch (Exception $e) {
	    echo $e->getMessage();
	    die('could not create controller');
	}
    }

    /**
     * init plugins
     */
    protected function _initPlugins() {
	try {
	    // load config
	    $this->bootstrap('config');

	    // load controller
	    $this->bootstrap('controller');

	    // get controller instance
	    $controller = Zend_Controller_Front::getInstance();

	    // load plugings
	    $controller->registerPlugin(new Knowledgeroot_Page_Default());
	} catch (Exception $e) {
	    echo $e->getMessage();
	    die('could not create plugins');
	}
    }

    /**
     * init default rte
     */
    protected function _initRte() {
	try {
	    // load config
	    $this->bootstrap('config');

	    // init rte
	    $rte = new Knowledgeroot_Rte();

	    // save rte
	    Knowledgeroot_Registry::set('rte', $rte);
	} catch(Exception $e) {
	    echo $e->getMessage();
	    die('could not load default rte');
	}
    }

    /**
     * init filemanager
     */
    protected function _initFileManager() {
	try {
	    // load config
	    $this->bootstrap('config');

	    // init filemanager
	    $fm = new Knowledgeroot_FileManager();

	    // save filemanager
	    Knowledgeroot_Registry::set('filemanager', $fm);
	} catch(Exception $e) {
	    echo $e->getMessage();
	    die('could not load filemanager');
	}
    }

    /**
     * init modules
     */
    protected function _initModules() {
	try {
	    // load config
	    $this->bootstrap('config');

	    // init module manager
	    $manager = new Knowledgeroot_ModuleManager();

	    // load modules
	    $manager->loadModules();

	    // save filemanager
	    Knowledgeroot_Registry::set('modulemanager', $manager);
	} catch(Exception $e) {
	    echo $e->getMessage();
	    die('could not load modules');
	}
    }
}

