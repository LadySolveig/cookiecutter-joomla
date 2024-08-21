<?php
{% include "docHeader.txt" ignore missing %}

\defined('_JEXEC') or die;

use Joomla\CMS\Categories\CategoryFactoryInterface;
use Joomla\CMS\Component\Router\RouterFactoryInterface;
use Joomla\CMS\Dispatcher\ComponentDispatcherFactoryInterface;
use Joomla\CMS\Extension\ComponentInterface;
use Joomla\CMS\Extension\Service\Provider\CategoryFactory;
use Joomla\CMS\Extension\Service\Provider\ComponentDispatcherFactory;
use Joomla\CMS\Extension\Service\Provider\MVCFactory;
use Joomla\CMS\Extension\Service\Provider\RouterFactory;
use Joomla\CMS\HTML\Registry;
use Joomla\CMS\MVC\Factory\MVCFactoryInterface;
use {{cookiecutter.namespace}}\Component\{{cookiecutter.__project_camelcaps}}\Administrator\Extension\{{cookiecutter.__project_camelcaps}}Component;
use Joomla\DI\Container;
use Joomla\DI\ServiceProviderInterface;

/**
 * The {{cookiecutter.project_slug}} service provider.
 *
 * @since  __DEPLOY_VERSION__
 */
return new class () implements ServiceProviderInterface {
    /**
     * Registers the service provider with a DI container.
     *
     * @param   Container  $container  The DI container.
     *
     * @return  void
     *
     * @since   __DEPLOY_VERSION__
     */
    public function register(Container $container)
    {
        $container->registerServiceProvider(new CategoryFactory('\\{{cookiecutter.namespace}}\\Component\\{{cookiecutter.__project_camelcaps}}'));
        $container->registerServiceProvider(new MVCFactory('\\{{cookiecutter.namespace}}\\Component\\{{cookiecutter.__project_camelcaps}}'));
        $container->registerServiceProvider(new ComponentDispatcherFactory('\\{{cookiecutter.namespace}}\\Component\\{{cookiecutter.__project_camelcaps}}'));
        $container->registerServiceProvider(new RouterFactory('\\{{cookiecutter.namespace}}\\Component\\{{cookiecutter.__project_camelcaps}}'));

        $container->set(
            ComponentInterface::class,
            function (Container $container) {
                $component = new {{cookiecutter.__project_camelcaps}}Component($container->get(ComponentDispatcherFactoryInterface::class));

                $component->setRegistry($container->get(Registry::class));
                $component->setMVCFactory($container->get(MVCFactoryInterface::class));
                $component->setCategoryFactory($container->get(CategoryFactoryInterface::class));
                $component->setRouterFactory($container->get(RouterFactoryInterface::class));

                return $component;
            }
        );
    }
};