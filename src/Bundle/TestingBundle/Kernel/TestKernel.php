<?php

namespace MooMoo\Platform\Bundle\TestingBundle\Kernel;

use MooMoo\Platform\Bundle\KernelBundle\Kernel\Kernel;

class TestKernel extends Kernel
{
    /**
     * @param string $pluginBaseName
     * {@inheritDoc}
     */
    public function __construct($pluginBaseName)
    {
        $pluginRootFile =debug_backtrace(2, 3)[0]['file'];
        $this->pluginBaseName = $pluginBaseName;
        $this->pluginName = explode('/', $this->pluginBaseName)[0];
        $this->rootDirs[$this->pluginBaseName][] = realpath(sprintf('%s/src', dirname($pluginRootFile)));

        $r = new \ReflectionObject($this);
        $this->rootDirs[$this->pluginBaseName][] = realpath(dirname($r->getFileName(), 3) . '/../..');
    }
}