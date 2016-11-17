<?php
/*
 * This file is part of the Cygnite package.
 *
 * (c) Sanjoy Dey <dey.sanjoy0@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cygnite\Database;

if (!defined('CF_SYSTEM')) {
    exit('External script access not allowed');
}

/**
 * Initialize your database configurations settings here.
 * You can connect with multiple database on the fly.
 * Don't worry about performance Cygnite will not
 * connect with database until first time you need your
 * connection to interact with database.
 * Specify your database name and table name in model to
 * do crude operations.
 *
 * Please protect this file to have maximum security.
 */

Configuration::initialize(
    function ($config) {
        $config->default = 'live';
        $config->setConfig(
            array(
                'db' => array(
                    'driver' => 'mysql',
                    'host' => 'localhost',
                    'port' => '',
                    'database' => 'cms',
                    'username' => 'cygnite',
                    'password' => 'cygnite',
                    'charset' => 'utf8'
                ),
                'live' => array(
                  'driver' => 'mysql',
                  'host' => '192.168.200.9',
                  'port' => '',
                  'database' => 'dbwebmrpi',
                  'username' => 'webmrpi',
                  'password' => '@Webdmsc$12',
                  'charset' => 'utf8'
                )
            )
        );
    }
);
