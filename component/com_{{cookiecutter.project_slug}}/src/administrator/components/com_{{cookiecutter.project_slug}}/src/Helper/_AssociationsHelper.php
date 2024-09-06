<?php
{% include "docHeader.txt" ignore missing %}

namespace {{cookiecutter.namespace}}\Component\{{cookiecutter.__project_camelcaps}}\Administrator\Helper;

use Joomla\CMS\Association\AssociationExtensionHelper;
use Joomla\CMS\Language\Associations;
use Joomla\CMS\Table\Table;
use {{cookiecutter.namespace}}\Component\{{cookiecutter.__project_camelcaps}}\Site\Helper\AssociationHelper;

// phpcs:disable PSR1.Files.SideEffects
\defined('_JEXEC') or die;
// phpcs:enable PSR1.Files.SideEffects

/**
 * {{cookiecutter.__project_camelcaps}} associations helper.
 *
 * @since  __DEPLOY_VERSION__
 */
class AssociationsHelper extends AssociationExtensionHelper
{
    /**
     * The extension name
     *
     * @var     array   $extension
     *
     * @since   __DEPLOY_VERSION__
     */
    protected $extension = 'com_{{cookiecutter.project_slug}}';

    /**
     * Array of item types
     *
     * @var     array   $itemTypes
     *
     * @since   __DEPLOY_VERSION__
     */
    protected $itemTypes = ['{{cookiecutter.__entity}}', 'category'];

    /**
     * Has the extension association support
     *
     * @var     boolean   $associationsSupport
     *
     * @since   __DEPLOY_VERSION__
     */
    protected $associationsSupport = true;

    /**
     * Method to get the associations for a given item.
     *
     * @param   integer  $id    Id of the item
     * @param   string   $view  Name of the view
     *
     * @return  array   Array of associations for the item
     *
     * @since  __DEPLOY_VERSION__
     */
    public function getAssociationsForItem($id = 0, $view = null)
    {
        return AssociationHelper::getAssociations($id, $view);
    }

    /**
     * Get the associated items for an item
     *
     * @param   string  $typeName  The item type
     * @param   int     $id        The id of item for which we need the associated items
     *
     * @return  array
     *
     * @since   __DEPLOY_VERSION__
     */
    public function getAssociations($typeName, $id)
    {
        $type = $this->getType($typeName);

        $context    = $this->extension . '.item';
        $catidField = 'catid';

        if ($typeName === 'category') {
            $context    = 'com_categories.item';
            $catidField = '';
        }

        // Get the associations.
        $associations = Associations::getAssociations(
            $this->extension,
            $type['tables']['a'],
            $context,
            $id,
            'id',
            'alias',
            $catidField
        );

        return $associations;
    }

    /**
     * Get item information
     *
     * @param   string  $typeName  The item type
     * @param   int     $id        The id of item for which we need the associated items
     *
     * @return  Table|null
     *
     * @since   __DEPLOY_VERSION__
     */
    public function getItem($typeName, $id)
    {
        if (empty($id)) {
            return null;
        }

        $table = null;

        switch ($typeName) {
            case '{{cookiecutter.__entity}}':
                $table = Table::getInstance('{{cookiecutter.__entity.capitalize()}}Table', '{{cookiecutter.namespace}}\\Component\\{{cookiecutter.__project_camelcaps}}\\Administrator\\Table\\');
                break;

            case 'category':
                $table = Table::getInstance('Category');
                break;
        }

        if (empty($table)) {
            return null;
        }

        $table->load($id);

        return $table;
    }

    /**
     * Get information about the type
     *
     * @param   string  $typeName  The item type
     *
     * @return  array  Array of item types
     *
     * @since   __DEPLOY_VERSION__
     */
    public function getType($typeName = '')
    {
        $fields  = $this->getFieldsTemplate();
        $tables  = [];
        $joins   = [];
        $support = $this->getSupportTemplate();
        $title   = '';

        if (\in_array($typeName, $this->itemTypes)) {
            switch ($typeName) {
                case '{{cookiecutter.__entity.lower()}}':
                    $support['state']     = true;
                    $support['acl']       = true;
                    $support['checkout']  = true;
                    $support['category']  = true;
                    $support['save2copy'] = true;

                    $tables = [
                        'a' => '#__{{cookiecutter.project_slug}}_{{cookiecutter.__entities.lower()}}',
                    ];

                    $title = '{{cookiecutter.__entity.lower()}}';
                    break;

                case 'category':
                    $fields['created_user_id'] = 'a.created_user_id';
                    $fields['ordering']        = 'a.lft';
                    $fields['level']           = 'a.level';
                    $fields['catid']           = '';
                    $fields['state']           = 'a.published';

                    $support['state']    = true;
                    $support['acl']      = true;
                    $support['checkout'] = true;
                    $support['level']    = true;

                    $tables = [
                        'a' => '#__categories',
                    ];

                    $title = 'category';
                    break;
            }
        }

        return [
            'fields'  => $fields,
            'support' => $support,
            'tables'  => $tables,
            'joins'   => $joins,
            'title'   => $title,
        ];
    }
}