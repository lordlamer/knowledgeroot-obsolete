<?php

/**
 *
 */
abstract class Knowledgeroot_Module_Bootstrap_Abstract {
    /**
     * @var array Internal resource methods (resource/method pairs)
     */
    protected $_classResources;

    /**
     * @var array Initializers that have been run
     */
    protected $_run = array();

    /**
     * @var array Initializers that have been started but not yet completed (circular dependency detection)
     */
    protected $_started = array();

    /**
     *
     */
    public function run() {
	$resources = $this->getClassResources();

	foreach($resources as $key => $value) {
	    $this->bootstrap($key);
	}
    }

    /**
     * Get class resources (as resource/method pairs)
     *
     * Uses get_class_methods() by default, reflection on prior to 5.2.6,
     * as a bug prevents the usage of get_class_methods() there.
     *
     * @return array
     */
    public function getClassResources()
    {
        if (null === $this->_classResources) {
            if (version_compare(PHP_VERSION, '5.2.6') === -1) {
                $class        = new ReflectionObject($this);
                $classMethods = $class->getMethods();
                $methodNames  = array();

                foreach ($classMethods as $method) {
                    $methodNames[] = $method->getName();
                }
            } else {
                $methodNames = get_class_methods($this);
            }

            $this->_classResources = array();
            foreach ($methodNames as $method) {
                if (5 < strlen($method) && '_init' === substr($method, 0, 5)) {
                    $this->_classResources[strtolower(substr($method, 5))] = $method;
                }
            }
        }

        return $this->_classResources;
    }

    /**
     * Get class resource names
     *
     * @return array
     */
    public function getClassResourceNames()
    {
        $resources = $this->getClassResources();
        return array_keys($resources);
    }

    /**
     * Bootstrap individual, all, or multiple resources
     *
     * Marked as final to prevent issues when subclassing and naming the
     * child class 'Bootstrap' (in which case, overriding this method
     * would result in it being treated as a constructor).
     *
     * If you need to override this functionality, override the
     * {@link _bootstrap()} method.
     *
     * @param  null|string|array $resource
     * @return Zend_Application_Bootstrap_BootstrapAbstract
     * @throws Knowledgeroot_Module_Bootstrap_Exception When invalid argument was passed
     */
    final public function bootstrap($resource = null)
    {
        $this->_bootstrap($resource);
        return $this;
    }

    /**
     * Bootstrap implementation
     *
     * This method may be overridden to provide custom bootstrapping logic.
     * It is the sole method called by {@link bootstrap()}.
     *
     * @param  null|string|array $resource
     * @return void
     * @throws Knowledgeroot_Module_Bootstrap_Exception When invalid argument was passed
     */
    protected function _bootstrap($resource = null)
    {
        if (null === $resource) {
            foreach ($this->getClassResourceNames() as $resource) {
                $this->_executeResource($resource);
            }

            foreach ($this->getPluginResourceNames() as $resource) {
                $this->_executeResource($resource);
            }
        } elseif (is_string($resource)) {
            $this->_executeResource($resource);
        } elseif (is_array($resource)) {
            foreach ($resource as $r) {
                $this->_executeResource($r);
            }
        } else {
            throw new Knowledgeroot_Module_Bootstrap_Exception('Invalid argument passed to ' . __METHOD__);
        }
    }

    /**
     * Execute a resource
     *
     * Checks to see if the resource has already been run. If not, it searches
     * first to see if a local method matches the resource, and executes that.
     * If not, it checks to see if a plugin resource matches, and executes that
     * if found.
     *
     * Finally, if not found, it throws an exception.
     *
     * @param  string $resource
     * @return void
     * @throws Knowledgeroot_Module_Bootstrap_Exception When resource not found
     */
    protected function _executeResource($resource)
    {
        $resourceName = strtolower($resource);

        if (in_array($resourceName, $this->_run)) {
            return;
        }

        if (isset($this->_started[$resourceName]) && $this->_started[$resourceName]) {
            throw new Knowledgeroot_Module_Bootstrap_Exception('Circular resource dependency detected');
        }

        $classResources = $this->getClassResources();
        if (array_key_exists($resourceName, $classResources)) {
            $this->_started[$resourceName] = true;
            $method = $classResources[$resourceName];
            $return = $this->$method();
            unset($this->_started[$resourceName]);
            $this->_markRun($resourceName);

            if (null !== $return) {
                $this->getContainer()->{$resourceName} = $return;
            }

            return;
        }

        throw new Knowledgeroot_Module_Bootstrap_Exception('Resource matching "' . $resource . '" not found');
    }

    /**
     * Mark a resource as having run
     *
     * @param  string $resource
     * @return void
     */
    protected function _markRun($resource)
    {
        if (!in_array($resource, $this->_run)) {
            $this->_run[] = $resource;
        }
    }


}