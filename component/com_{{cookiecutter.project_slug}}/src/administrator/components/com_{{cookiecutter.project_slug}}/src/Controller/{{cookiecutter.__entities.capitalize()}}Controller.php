<?php
{% include "docHeader.txt" ignore missing %}

namespace {{cookiecutter.namespace}}\Component\{{cookiecutter.__project_camelcaps}}\Administrator\Controller;

use Joomla\CMS\MVC\Controller\FormController;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Versioning\VersionableControllerTrait;
use Joomla\Utilities\ArrayHelper;

// phpcs:disable PSR1.Files.SideEffects
\defined('_JEXEC') or die;
// phpcs:enable PSR1.Files.SideEffects

/**
 * Banner controller class.
 *
 * @since  1.6
 */
class BannerController extends FormController
{
    use VersionableControllerTrait;

    /**
     * The prefix to use with controller messages.
     *
     * @var    string
     * @since  1.6
     */
    protected $text_prefix = 'COM_BANNERS_BANNER';

    /**
     * Method override to check if you can add a new record.
     *
     * @param   array  $data  An array of input data.
     *
     * @return  boolean
     *
     * @since   __DEPLOY_VERSION__
     */
    protected function allowAdd($data = [])
    {
        $filter     = $this->input->getInt('filter_category_id');
        $categoryId = ArrayHelper::getValue($data, 'catid', $filter, 'int');

        if ($categoryId) {
            // If the category has been passed in the URL check it.
            return $this->app->getIdentity()->authorise('core.create', $this->option . '.category.' . $categoryId);
        }

        // In the absence of better information, revert to the component permissions.
        return parent::allowAdd($data);
    }

    /**
     * Method override to check if you can edit an existing record.
     *
     * @param   array   $data  An array of input data.
     * @param   string  $key   The name of the key for the primary key.
     *
     * @return  boolean
     *
     * @since   __DEPLOY_VERSION__
     */
    protected function allowEdit($data = [], $key = 'id')
    {
        $recordId   = (int) isset($data[$key]) ? $data[$key] : 0;
        $categoryId = 0;

        if ($recordId) {
            $categoryId = (int) $this->getModel()->getItem($recordId)->catid;
        }

        if ($categoryId) {
            // The category has been set. Check the category permissions.
            return $this->app->getIdentity()->authorise('core.edit', $this->option . '.category.' . $categoryId);
        }

        // Since there is no asset tracking, revert to the component permissions.
        return parent::allowEdit($data, $key);
    }

    /**
     * Method to run batch operations.
     *
     * @param   string  $model  The model
     *
     * @return  boolean  True on success.
     *
     * @since   __DEPLOY_VERSION__
     */
    public function batch($model = null)
    {
        $this->checkToken();

        // Set the model
        $model = $this->getModel('Banner', '', []);

        // Preset the redirect
        $this->setRedirect(Route::_('index.php?option=com_banners&view=banners' . $this->getRedirectToListAppend(), false));

        return parent::batch($model);
    }
}
