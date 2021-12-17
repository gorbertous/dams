<?php
// src/ApplicationAspectKernel.php
namespace App;

use Go\Core\AspectKernel;
use Go\Core\AspectContainer;
use Doctrine\Common\Annotations\AnnotationReader;

/**
 * Application Aspect Kernel
 */
class ApplicationAspectKernel extends AspectKernel
{

    /**
     * Configure an AspectContainer with advisors, aspects and pointcuts
     *
     * @param AspectContainer $container
     *
     * @return void
     */
    protected function configureAop(AspectContainer $container)
    {
        AnnotationReader::addGlobalIgnoredName('triggers');
        //AnnotationReader::addGlobalIgnoredName('TimeProfiling');
        $container->registerAspect(new ProfilerAspect());
    }
}
