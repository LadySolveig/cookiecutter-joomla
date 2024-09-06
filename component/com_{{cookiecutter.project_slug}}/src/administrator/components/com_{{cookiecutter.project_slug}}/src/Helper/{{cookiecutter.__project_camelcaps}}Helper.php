<?php
{% include "docHeader.txt" ignore missing %}

namespace {{cookiecutter.namespace}}\Component\{{cookiecutter.__project_camelcaps}}\Administrator\Helper;

use Joomla\CMS\Helper\ContentHelper;

// phpcs:disable PSR1.Files.SideEffects
\defined('_JEXEC') or die;
// phpcs:enable PSR1.Files.SideEffects

/**
 * {{cookiecutter.__project_camelcaps}} component helper.
 *
 * @since  __DEPLOY_VERSION__
 */
class {{cookiecutter.__project_camelcaps}}Helper extends ContentHelper
{
}