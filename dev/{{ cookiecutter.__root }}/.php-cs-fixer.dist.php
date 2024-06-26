<?php
/**
 * @package    {{cookiecutter.namespace}}.{{ cookiecutter.__project_type.title() }}
 {%- if cookiecutter.__project_type == 'plugin' %}
 * @subpackage {{cookiecutter.plugin_type.title()}}.{{cookiecutter.project_slug.title()}}
 {%- else %}
* @subpackage {{cookiecutter.project_slug.title()}}
 {%- endif %}
 * @copyright  (C) {{cookiecutter.copyright_year}}, {{cookiecutter.author_full}} <{{cookiecutter.author_url}}>, 
 *                 2021 Open Source Matters, Inc. <https://www.joomla.org>
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

/**
 * This is the configuration file for php-cs-fixer
 *
 * @link https://github.com/FriendsOfPHP/PHP-CS-Fixer
 * @link https://mlocati.github.io/php-cs-fixer-configurator/#version:3.0
 *
 *
 * If you would like to run the automated clean up, then open a command line and type one of the commands below
 *
 * To run a quick dry run to see the files that would be modified:
 *
 *        ./vendor/bin/php-cs-fixer fix --dry-run
 *
 * To run a full check, with automated fixing of each problem :
 *
 *        ./vendor/bin/php-cs-fixer fix
 *
 * You can run the clean up on a single file if you need to, this is faster
 *
 *        ./vendor/bin/php-cs-fixer fix --dry-run administrator/index.php
 *        ./vendor/bin/php-cs-fixer fix administrator/index.php
 */

// Add all the core Joomla folders
$finder = PhpCsFixer\Finder::create()
    ->in(
        [
            __DIR__ . '/src',
        ]
    )
    // Ignore template files as PHP CS fixer can't handle them properly
    // https://github.com/PHP-CS-Fixer/PHP-CS-Fixer/issues/3702#issuecomment-396717120
    // ->notPath('/tmpl/')
    // ->notPath('/layouts/')
    // Ignore psr12 scripts because they contain invalid syntax
    ->notPath('/psr12/');

$config = new PhpCsFixer\Config();
$config
    ->setRiskyAllowed(true)
    ->setHideProgress(false)
    ->setUsingCache(false)
    ->setRules(
        [
            // Basic ruleset is PSR 12
            '@PSR12'                         => true,
            // Short array syntax
            'array_syntax'                   => ['syntax' => 'short'],
            // Lists should not have a trailing comma like list($foo, $bar,) = ...
            'no_trailing_comma_in_singleline' => true,
            // Arrays on multiline should have a trailing comma
            'trailing_comma_in_multiline'    => ['elements' => ['arrays']],
            // Align elements in multiline array and variable declarations on new lines below each other
            'binary_operator_spaces'         => ['operators' => ['=>' => 'align_single_space_minimal', '=' => 'align']],
            // The "No break" comment in switch statements
            'no_break_comment'               => ['comment_text' => 'No break'],
            // Remove unused imports
            'no_unused_imports'              => true,
            // Classes from the global namespace should not be imported
            'global_namespace_import'        => ['import_classes' => false, 'import_constants' => false, 'import_functions' => false],
            // Alpha order imports
            'ordered_imports'                => ['imports_order' => ['class', 'function', 'const'], 'sort_algorithm' => 'alpha'],
            // There should not be useless else cases
            'no_useless_else'                => true,
            // Native function invocation
            'native_function_invocation'     => ['include' => ['@compiler_optimized']],
            // Each element of an array must be indented exactly once
            'array_indentation' => true,
            // Last comment of code block counts as comment for next block
            'statement_indentation'          => ['stick_comment_to_next_continuous_control_statement' => true],
        ]
    )
    ->setFinder($finder);

return $config;
