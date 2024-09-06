<?php
{% include "docHeader.txt" ignore missing %}

namespace {{cookiecutter.namespace}}\Component\{{cookiecutter.__project_camelcaps}}\Administrator\Controller;

use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\Controller\BaseController;
use Joomla\CMS\Router\Route;
// use Joomla\Component\{{cookiecutter.__project_camelcaps}}\Administrator\Helper\{{cookiecutter.__project_camelcaps}}Helper;

// phpcs:disable PSR1.Files.SideEffects
\defined('_JEXEC') or die;
// phpcs:enable PSR1.Files.SideEffects

/**
 * {{cookiecutter.__project_camelcaps}} display controller.
 *
 * @since  __DEPLOY_VERSION__
 */
class DisplayController extends BaseController
{
    /**
     * The default view.
     *
     * @var    string
     * @since  __DEPLOY_VERSION__
     */
    protected $default_view = '{{cookiecutter.__entities}}';

    /**
     * Method to display a view.
     *
     * @param   boolean  $cachable   If true, the view output will be cached
     * @param   array    $urlparams  An array of safe URL parameters and their variable types
     *                   @see        \Joomla\CMS\Filter\InputFilter::clean() for valid values.
     *
     * @return  BaseController|boolean  This object to support chaining.
     *
     * @since   __DEPLOY_VERSION__
     */
    public function display($cachable = false, $urlparams = [])
    {
        $view   = $this->input->get('view', '{{cookiecutter.__entities}}');
        $layout = $this->input->get('layout', 'default');
        $id     = $this->input->getInt('id');

        // Check for edit form.
        if ($view === '{{cookiecutter.__entity}}' && $layout === 'edit' && !$this->checkEditId('com_{{cookiecutter.project_slug}}.edit.{{cookiecutter.__entity}}', $id)) {
            // Somehow the person just went to the form - we don't allow that.
            if (!\count($this->app->getMessageQueue())) {
                $this->setMessage(Text::sprintf('JLIB_APPLICATION_ERROR_UNHELD_ID', $id), 'error');
            }

            $this->setRedirect(Route::_('index.php?option=com_{{cookiecutter.project_slug}}&view={{cookiecutter.__entities}}', false));

            return false;
        }

        // if ($view === 'second_entity' && $layout === 'edit' && !$this->checkEditId('com_{{cookiecutter.project_slug}}.edit.second_entity', $id)) {
            // Somehow the person just went to the form - we don't allow that.
            // if (!\count($this->app->getMessageQueue())) {
                // $this->setMessage(Text::sprintf('JLIB_APPLICATION_ERROR_UNHELD_ID', $id), 'error');
            // }

            // $this->setRedirect(Route::_('index.php?option=com_{{cookiecutter.project_slug}}&view=second_entities', false));

            // return false;
        // }

        return parent::display();
    }

}