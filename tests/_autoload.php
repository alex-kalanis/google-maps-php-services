<?php

function autoload(string $className): void
{
    if (!defined('AUTHOR_NAME')) {
        define('AUTHOR_NAME', '.');
    }
    if (!defined('PROJECT_NAME')) {
        define('PROJECT_NAME', '.');
    }
    if (!defined('PROJECT_DIR')) {
        define('PROJECT_DIR', 'src');
    }
    if (!defined('TESTS_NAME')) {
        define('TESTS_NAME', 'tests');
    }
    if (!defined('TESTS_DIR')) {
        define('TESTS_DIR', 'tests');
    }

    // replace virtual project path from package manager to real path
    $className = preg_replace('#^' . AUTHOR_NAME . '\\\\' . PROJECT_NAME . '#', '', $className);
    // replace path for tests namespace by real path
    $className = preg_replace('#^' . TESTS_NAME . '#', '', $className);
    $className = str_replace('\\', DIRECTORY_SEPARATOR, $className);

    // directly call files in tests
    if (is_file(__DIR__ . DIRECTORY_SEPARATOR . $className . '.php')) {
        require_once(__DIR__ . DIRECTORY_SEPARATOR . $className . '.php');
    }

    // load files in tests
    if (is_file(__DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . TESTS_DIR . DIRECTORY_SEPARATOR . $className . '.php')) {
        require_once(__DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . TESTS_DIR . DIRECTORY_SEPARATOR . $className . '.php');
    }

    // hack to change some hardcoded files into their mock
    if (is_file(__DIR__ . DIRECTORY_SEPARATOR . 'external' . DIRECTORY_SEPARATOR . $className . '.php')) {
        require_once(__DIR__ . DIRECTORY_SEPARATOR . 'external' . DIRECTORY_SEPARATOR . $className . '.php');
    }

    // load files in project
    if (is_file(__DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . PROJECT_DIR . DIRECTORY_SEPARATOR . $className . '.php')) {
        require_once(__DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . PROJECT_DIR . DIRECTORY_SEPARATOR . $className . '.php');
    }
}

spl_autoload_register('autoload');
