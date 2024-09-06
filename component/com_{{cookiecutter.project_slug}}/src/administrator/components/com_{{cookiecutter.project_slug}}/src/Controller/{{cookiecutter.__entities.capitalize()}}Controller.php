<?php
{% include "docHeader.txt" ignore missing %}

namespace {{cookiecutter.namespace}}\Component\{{cookiecutter.__project_camelcaps}}\Administrator\Controller;

use Joomla\CMS\Application\CMSApplication;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\Controller\AdminController;
use Joomla\CMS\MVC\Factory\MVCFactoryInterface;
use Joomla\CMS\Response\JsonResponse;
use Joomla\Input\Input;
use Joomla\Utilities\ArrayHelper;

// phpcs:disable PSR1.Files.SideEffects
\defined('_JEXEC') or die;
// phpcs:enable PSR1.Files.SideEffects

/**
 * {{cookiecutter.__entities.capitalize()}} list controller class.
 *
 * @since  __DEPLOY_VERSION__
 */
class {{cookiecutter.__entities.capitalize()}}Controller extends AdminController
{
    /**
     * The prefix to use with controller messages.
     *
     * @var    string
     * @since  __DEPLOY_VERSION__
     */
    protected $text_prefix = 'COM_{{cookiecutter.project_slug.upper()}}_{{cookiecutter.__entities.upper()}}';

    /**
     * Constructor.
     *
     * @param   array                 $config   An optional associative array of configuration settings.
     * @param   ?MVCFactoryInterface  $factory  The factory.
     * @param   ?CMSApplication       $app      The Application for the dispatcher
     * @param   ?Input                $input    Input
     *
     * @since   __DEPLOY_VERSION__
     */
    public function __construct($config = [], MVCFactoryInterface $factory = null, $app = null, $input = null)
    {
        parent::__construct($config, $factory, $app, $input);

        // $this->registerTask('sticky_unpublish', 'sticky_publish');
    }

    /**
     * Method to get a model object, loading it if required.
     *
     * @param   string  $name    The model name. Optional.
     * @param   string  $prefix  The class prefix. Optional.
     * @param   array   $config  Configuration array for model. Optional.
     *
     * @return  \Joomla\CMS\MVC\Model\BaseDatabaseModel  The model.
     *
     * @since   __DEPLOY_VERSION__
     */
    public function getModel($name = '{{cookiecutter.__entities.capitalize()}}', $prefix = 'Administrator', $config = ['ignore_request' => true])
    {
        return parent::getModel($name, $prefix, $config);
    }

    /**
     * Method to get the number of published {{cookiecutter.__entities}} for quickicons
     *
     * @return  void
     *
     * @since   __DEPLOY_VERSION__
     */
    public function getQuickiconContent()
    {
        $model = $this->getModel('{{cookiecutter.__entities}}.lower()');

        $model->setState('filter.published', 1);

        $amount = (int) $model->getTotal();

        $result = [];

        $result['amount'] = $amount;
        $result['sronly'] = Text::plural('{{cookiecutter.project_slug.upper()}}_{{cookiecutter.__entities.upper()}}_N_QUICKICON_SRONLY', $amount);
        $result['name']   = Text::plural('{{cookiecutter.project_slug.upper()}}_{{cookiecutter.__entities.upper()}}_N_QUICKICON', $amount);

        echo new JsonResponse($result);
    }
}