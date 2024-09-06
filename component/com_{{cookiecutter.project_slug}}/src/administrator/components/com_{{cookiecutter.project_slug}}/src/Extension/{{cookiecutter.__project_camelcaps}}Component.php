<?php
{% include "docHeader.txt" ignore missing %}

namespace {{cookiecutter.namespace}}\Component\{{cookiecutter.__project_camelcaps}}\Administrator\Extension;

use Joomla\CMS\Categories\CategoryServiceInterface;
use Joomla\CMS\Categories\CategoryServiceTrait;
use Joomla\CMS\Component\Router\RouterServiceInterface;
use Joomla\CMS\Component\Router\RouterServiceTrait;
use Joomla\CMS\Extension\BootableExtensionInterface;
use Joomla\CMS\Extension\MVCComponent;
use Joomla\CMS\HTML\HTMLRegistryAwareTrait;
// use Joomla\CMS\Tag\TagServiceInterface;
// use Joomla\CMS\Tag\TagServiceTrait;
use Joomla\Component\{{cookiecutter.project_slug.capitalize()}}\Administrator\Service\Html\{{cookiecutter.project_slug.capitalize()}};
use Joomla\Database\DatabaseInterface;
use Psr\Container\ContainerInterface;

// phpcs:disable PSR1.Files.SideEffects
\defined('_JEXEC') or die;
// phpcs:enable PSR1.Files.SideEffects

/**
 * Component class for com_{{cookiecutter.project_slug}}
 *
 * @since  __DEPLOY_VERSION__
 */
class {{cookiecutter.project_slug.capitalize()}}Component extends MVCComponent implements
    BootableExtensionInterface,
    CategoryServiceInterface,
    RouterServiceInterface //,
    // TagServiceInterface
{
    use HTMLRegistryAwareTrait;
    use RouterServiceTrait;
    use CategoryServiceTrait // , TagServiceTrait {
        // CategoryServiceTrait::getTableNameForSection insteadof TagServiceTrait;
        // CategoryServiceTrait::getStateColumnForSection insteadof TagServiceTrait;
    // }

    /**
     * Booting the extension. This is the function to set up the environment of the extension like
     * registering new class loaders, etc.
     *
     * If required, some initial set up can be done from services of the container, eg.
     * registering HTML services.
     *
     * @param   ContainerInterface  $container  The container
     *
     * @return  void
     *
     * @since   __DEPLOY_VERSION__
     */
    public function boot(ContainerInterface $container)
    {
        ${{cookiecutter.project_slug}} = new {{cookiecutter.project_slug.capitalize()}}();
        ${{cookiecutter.project_slug}}->setDatabase($container->get(DatabaseInterface::class));

        $this->getRegistry()->register('{{cookiecutter.project_slug}}', ${{cookiecutter.project_slug}});
    }

    /**
     * Returns the table for the count items functions for the given section.
     *
     * @param   ?string  $section  The section
     *
     * @return  string|null
     *
     * @since    __DEPLOY_VERSION__
     */
    protected function getTableNameForSection(string $section = null)
    {
        return '{{cookiecutter.project_slug}}_{{cookiecutter.__entities.lower()}}';
    }
}