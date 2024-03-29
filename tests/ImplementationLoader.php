<?php

/**
 * Implemnentation Loader for filesystem
 */
class ImplementationLoader extends \PHPCR\Test\AbstractLoader
{
    private static $instance = null;

    public static function getInstance()
    {
        if (null === self::$instance) {
            self::$instance = new ImplementationLoader();
        }

        return self::$instance;
    }

    /**
     * @var string
     */
    private $fixturePath;

    protected function __construct()
    {
        parent::__construct('Jackalope\RepositoryFactoryFilesystem', $GLOBALS['phpcr.workspace']);

        $this->unsupportedChapters = array(
            'Connecting',
            'Reading',
            'Query',
            'Export',
            'NodeTypeDiscovery',
            'PermissionsAndCapabilities',
            'Writing',
            'Import',
            'Observation',
            'WorkspaceManagement',
            'ShareableNodes',
            'Versioning',
            'AccessControlManagement',
            'Locking',
            'LifecycleManagement',
            'NodeTypeManagement',
            'RetentionAndHold',
            'Transactions',
            'SameNameSiblings',
            'OrderableChildNodes',
            'PhpcrUtils'
        );

        $this->unsupportedCases = array(
        );

        $this->unsupportedTests = array(
        );
    }

    public function getRepositoryFactoryParameters()
    {
        return array(
            'path' => __DIR__ . '/data',
        );
    }

    public function getCredentials()
    {
        return new \PHPCR\SimpleCredentials('admin', 'admin');
    }

    public function getInvalidCredentials()
    {
        return new \PHPCR\SimpleCredentials('nonexistinguser', '');
    }

    public function getRestrictedCredentials()
    {
        return new \PHPCR\SimpleCredentials('anonymous', 'abc');
    }

    public function prepareAnonymousLogin()
    {
        return true;
    }

    public function getUserId()
    {
        return 'admin';
    }

    public function getRepository()
    {
        $transport = new \Jackalope\Transport\Filesystem\Client(new \Jackalope\Factory);
        foreach (array($GLOBALS['phpcr.workspace'], $this->otherWorkspacename) as $workspace) {
            try {
                $transport->createWorkspace($workspace);
            } catch (\PHPCR\RepositoryException $e) {
                if ($e->getMessage() != "Workspace '$workspace' already exists") {
                    // if the message is not that the workspace already exists, something went really wrong
                    throw $e;
                }
            }
        }

        return new \Jackalope\Repository(null, $transport, $this->getRepositoryFactoryParameters());
    }

    public function getFixtureLoader()
    {
        return new \Jackalope\Test\Tester\FilesystemFixtureLoader(__DIR__ . '/data.dist', '/data');
    }

}
