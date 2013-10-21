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
		$session->timezone = $config->base->timezone;
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

	    //route: page/move/:pageid
	    $router->addRoute('pagemove', new Zend_Controller_Router_Route_Regex('page/move/(\d+)', array(
			'controller' => 'page',
			'action' => 'move'),
			    array(
				1 => 'id',
			    )
	    ));

	    //route: page/move/:pageid/to/:targetid
	    $router->addRoute('pagemovetotarget', new Zend_Controller_Router_Route_Regex('page/move/(\d+)/to/(\d+)', array(
			'controller' => 'page',
			'action' => 'move'),
			    array(
				1 => 'id',
				2 => 'target',
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

	    //route: page/show/:pageid/version/:version
	    $router->addRoute('pageshowversion', new Zend_Controller_Router_Route_Regex('page/show/(\d+)/version/(\d+)', array(
			'controller' => 'page',
			'action' => 'show'),
			    array(
				1 => 'id',
				2 => 'version',
			    )
	    ));

	    //route: content/new/:pageid
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

	    //route: content/move/:contentid
	    $router->addRoute('contentmove', new Zend_Controller_Router_Route_Regex('content/move/(\d+)', array(
			'controller' => 'content',
			'action' => 'move'),
			    array(
				1 => 'id',
			    )
	    ));

	    //route: content/move/:contentid/to/:targetid
	    $router->addRoute('contentmovetotarget', new Zend_Controller_Router_Route_Regex('content/move/(\d+)/to/(\d+)', array(
			'controller' => 'content',
			'action' => 'move'),
			    array(
				1 => 'id',
				2 => 'target',
			    )
	    ));

	    //route: content/select/:pageid
	    $router->addRoute('contentselect', new Zend_Controller_Router_Route_Regex('content/select/(\d+)', array(
			'controller' => 'content',
			'action' => 'select'),
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

	    //route: content/show/:contentid
	    $router->addRoute('contentshow', new Zend_Controller_Router_Route_Regex('content/show/(\d+)', array(
			'controller' => 'content',
			'action' => 'show'),
			    array(
				1 => 'id',
			    )
	    ));

	    //route: content/show/:contentid/version/:version
	    $router->addRoute('contentshowversion', new Zend_Controller_Router_Route_Regex('content/show/(\d+)/version/(\d+)', array(
			'controller' => 'content',
			'action' => 'show'),
			    array(
				1 => 'id',
				2 => 'version',
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

	    //route: user/edit/:userid
	    $router->addRoute('useredit', new Zend_Controller_Router_Route_Regex('user/edit/(\d+)', array(
			'controller' => 'user',
			'action' => 'edit'),
			    array(
				1 => 'id',
			    )
	    ));

	    //route: user/delete/:userid
	    $router->addRoute('userdelete', new Zend_Controller_Router_Route_Regex('user/delete/(\d+)', array(
			'controller' => 'user',
			'action' => 'delete'),
			    array(
				1 => 'id',
			    )
	    ));

	    //route: user/enable/:userid
	    $router->addRoute('userenable', new Zend_Controller_Router_Route_Regex('user/enable/(\d+)', array(
			'controller' => 'user',
			'action' => 'enable'),
			    array(
				1 => 'id',
			    )
	    ));

	    //route: user/disable/:userid
	    $router->addRoute('userdisable', new Zend_Controller_Router_Route_Regex('user/disable/(\d+)', array(
			'controller' => 'user',
			'action' => 'disable'),
			    array(
				1 => 'id',
			    )
	    ));

	    //route: group/edit/:groupid
	    $router->addRoute('groupedit', new Zend_Controller_Router_Route_Regex('group/edit/(\d+)', array(
			'controller' => 'group',
			'action' => 'edit'),
			    array(
				1 => 'id',
			    )
	    ));

	    //route: group/delete/:groupid
	    $router->addRoute('groupdelete', new Zend_Controller_Router_Route_Regex('group/delete/(\d+)', array(
			'controller' => 'group',
			'action' => 'delete'),
			    array(
				1 => 'id',
			    )
	    ));

	    //route: group/enable/:groupid
	    $router->addRoute('groupenable', new Zend_Controller_Router_Route_Regex('group/enable/(\d+)', array(
			'controller' => 'group',
			'action' => 'enable'),
			    array(
				1 => 'id',
			    )
	    ));

	    //route: group/disable/:groupid
	    $router->addRoute('groupdisable', new Zend_Controller_Router_Route_Regex('group/disable/(\d+)', array(
			'controller' => 'group',
			'action' => 'disable'),
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

	    // init session
	    $this->bootstrap('session');

	    // get config
	    $config = Knowledgeroot_Registry::get('config');

	    // get session
	    $session = new Zend_Session_Namespace('user');

	    // check if session has a locale
	    $locale = null;

	    if(!empty($session->language)) {
		$locale = $session->language;
	    } else {
		$locale = $config->base->locale;
	    }

	    // init Zend_Locale
	    Knowledgeroot_Registry::set('Zend_Locale', new Knowledgeroot_Locale($locale));
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

	    // init session
	    $this->bootstrap('session');

	    // get config
	    $config = Knowledgeroot_Registry::get('config');

	    // get cache
	    $cache = Knowledgeroot_Registry::get('cache');

	    // get session
	    $session = new Zend_Session_Namespace('user');

	    // check if session has a locale
	    $locale = null;
	    if(!empty($session->language)) {
		$locale = $session->language;
	    } else {
		$locale = $config->base->locale;
	    }

	    // load translations
	    $translate = new Knowledgeroot_Translation();
	    $translate->loadTranslations($config->translation->folder);
	    $translate->setLocale($locale);

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

	    foreach ($translate->getTranslations() as $key => $value) {
		$zTranslate->getAdapter()->addTranslation(
		    array(
			'adapter' => 'gettext',
			'content' => $value,
			'locale' => $key,
			'clear' => false,
		    )
		);
	    }

	    // set default locale
	    $zTranslate->getAdapter()->setLocale($locale);

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

