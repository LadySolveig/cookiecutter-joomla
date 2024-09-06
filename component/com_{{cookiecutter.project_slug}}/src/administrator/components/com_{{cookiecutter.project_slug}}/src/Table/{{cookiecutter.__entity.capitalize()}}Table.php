<?php
{% include "docHeader.txt" ignore missing %}

namespace {{cookiecutter.namespace}}\Component\{{cookiecutter.__project_camelcaps}}\Administrator\Table;

use Joomla\CMS\Application\ApplicationHelper;
use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Table\Table;
use Joomla\CMS\Versioning\VersionableTableInterface;
use Joomla\Database\DatabaseDriver;
use Joomla\Database\ParameterType;
use Joomla\Event\DispatcherInterface;
use Joomla\Registry\Registry;
use Joomla\Utilities\ArrayHelper;

// phpcs:disable PSR1.Files.SideEffects
\defined('_JEXEC') or die;
// phpcs:enable PSR1.Files.SideEffects

/**
 * {{cookiecutter.__entity.capitalize()}} table
 *
 * @since  __DEPLOY_VERSION__
 */
class {{cookiecutter.__entity.capitalize()}}Table extends Table implements VersionableTableInterface
{

    /**
     * Indicates that columns fully support the NULL value in the database
     *
     * @var    boolean
     * @since  __DEPLOY_VERSION__
     */
    protected $_supportNullValue = true;

    /**
     * Constructor
     *
     * @param   DatabaseDriver        $db          Database connector object
     * @param   ?DispatcherInterface  $dispatcher  Event dispatcher for this table
     *
     * @since   __DEPLOY_VERSION__
     */
    public function __construct(DatabaseDriver $db, DispatcherInterface $dispatcher = null)
    {
        $this->typeAlias = 'com_{{cookiecutter.project_slug}}.{{cookiecutter.__entities.lower()}}';

        parent::__construct('#__{{cookiecutter.project_slug}}_{{cookiecutter.__entities.lower()}}', 'id', $db, $dispatcher);

        $this->created = Factory::getDate()->toSql();
        $this->setColumnAlias('published', 'state');
    }

    /**
     * Overloaded bind function
     *
     * @param   array  $array   Named array
     * @param   mixed  $ignore  An optional array or space separated list of properties
     *                          to ignore while binding.
     *
     * @return  mixed  Null if operation was satisfactory, otherwise returns an error string
     *
     * @see     Table::bind()
     * @since   __DEPLOY_VERSION__
     */
    public function bind($array, $ignore = '')
    {
        if (isset($array['params']) && \is_array($array['params'])) {
            $registry = new Registry($array['params']);

            // Do stuff with params

            $array['params'] = (string) $registry;

        }

        // if (isset($array['metadata']) && \is_array($array['metadata'])) {
            // $registry          = new Registry($array['metadata']);
            // $array['metadata'] = (string) $registry;
        // }

        // Bind the rules.
        // if (isset($array['rules']) && \is_array($array['rules'])) {
            // $rules = new Rules($array['rules']);
            // $this->setRules($rules);
        // }

        return parent::bind($array, $ignore);
    }

    /**
     * Overloaded check function
     *
     * @return  boolean
     *
     * @see     Table::check
     * @since   __DEPLOY_VERSION__
     */
    public function check()
    {
        try {
            parent::check();
        } catch (\Exception $e) {
            $this->setError($e->getMessage());

            return false;
        }

        // Set name
        $this->name = htmlspecialchars_decode($this->name, ENT_QUOTES);

        // Set alias
        if (trim($this->alias) == '') {
            $this->alias = $this->name;
        }

        $this->alias = ApplicationHelper::stringURLSafe($this->alias, $this->language);

        if (trim(str_replace('-', '', $this->alias)) == '') {
            $this->alias = Factory::getDate()->format('Y-m-d-H-i-s');
        }

        // Check for a valid category.
        if (!$this->catid = (int) $this->catid) {
            $this->setError(Text::_('JLIB_DATABASE_ERROR_CATEGORY_REQUIRED'));

            return false;
        }

        // Set created date if not set.
        if (!(int) $this->created) {
            $this->created = Factory::getDate()->toSql();
        }

        // Set publish_up, publish_down to null if not set
        if (!$this->publish_up) {
            $this->publish_up = null;
        }

        if (!$this->publish_down) {
            $this->publish_down = null;
        }

        // Check the publish down date is not earlier than publish up.
        if (!\is_null($this->publish_up) && !\is_null($this->publish_down) && $this->publish_down < $this->publish_up) {
            // Swap the dates.
            $temp               = $this->publish_up;
            $this->publish_up   = $this->publish_down;
            $this->publish_down = $temp;
        }

        // Set ordering
        if ($this->state < 0) {
            // Set ordering to 0 if state is archived or trashed
            $this->ordering = 0;
        } elseif (empty($this->ordering)) {
            // Set ordering to last if ordering was 0
            $this->ordering = self::getNextOrder($this->_db->quoteName('catid') . ' = ' . ((int) $this->catid) . ' AND ' . $this->_db->quoteName('state') . ' >= 0');
        }

        // Set modified to created if not set
        if (!$this->modified) {
            $this->modified = $this->created;
        }

        // Set modified_by to created_by if not set
        if (empty($this->modified_by)) {
            $this->modified_by = $this->created_by;
        }

        return true;
    }

    /**
     * Overrides Table::store to set modified data and user id.
     *
     * @param   boolean  $updateNulls  True to update fields even if they are null.
     *
     * @return  boolean  True on success.
     *
     * @since   __DEPLOY_VERSION__
     */
    public function store($updateNulls = true)
    {
        $date = Factory::getDate()->toSql();
        $user = $this->getCurrentUser();

        // Set created date if not set.
        if (!(int) $this->created) {
            $this->created = $date;
        }

        if ($this->id) {
            // Existing item
            $this->modified_by = $user->get('id');
            $this->modified    = $date;
            if (empty($this->created_by)) {
                $this->created_by = 0;
            }
        } else {
            // Field created_by can be set by the user, so we don't touch it if it's set.
            if (empty($this->created_by)) {
                $this->created_by = $user->get('id');
            }

            // Set modified to created date if not set
            if (!(int) $this->modified) {
                $this->modified = $this->created;
            }

            // Set modified_by to created_by user if not set
            if (empty($this->modified_by)) {
                $this->modified_by = $this->created_by;
            }
        }

        // Verify that the alias is unique
        $table = new self($this->getDbo(), $this->getDispatcher());

        if ($table->load(['alias' => $this->alias, 'catid' => $this->catid]) && ($table->id != $this->id || $this->id == 0)) {
            // Is the existing article trashed?
            $this->setError(Text::_('COM_{{cookiecutter.project_slug.upper()}}_{{cookiecutter.__entity.upper()}}_ERROR_UNIQUE_ALIAS'));

            if ($table->state === -2) {
                $this->setError(Text::_('COM_{{cookiecutter.project_slug.upper()}}_{{cookiecutter.__entity.upper()}}_ERROR_UNIQUE_ALIAS_TRASHED'));
            }

            return false;
        }

        return parent::store($updateNulls);
    }

    /**
     * Get the type alias for the history table
     *
     * @return  string  The alias as described above
     *
     * @since   __DEPLOY_VERSION__
     */
    public function getTypeAlias()
    {
        return $this->typeAlias;
    }

}