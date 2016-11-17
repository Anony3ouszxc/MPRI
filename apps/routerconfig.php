<?php

if (!defined('CF_SYSTEM')) {
    exit('No External script access allowed');
}
/**
 * Direct routing to controller
 * This file is used to set all routing configurations
 */
return array(
	'/backend'            => 'admin.index',
	'/main/{:all}'        => 'admin.main',
	'/user/{:all}'        => 'admin.user',
	'/post/{:all}'        => 'admin.post',
	'/page/{:all}'        => 'admin.page',
	'/module/{:all}'      => 'admin.module',
	'/setting/{:all}'     => 'admin.setting',
	'/report/{:all}'      => 'admin.report',
	'/page-not-found'     => 'admin.error',
	'/api/{:all}'         => 'admin.api',
	'/post-group/{:all}'  => 'home.postGroup',
	'/post-view/{:digit}' => 'home.postView',
	'/page-view/{:digit}' => 'home.pageView',
	'/count-share'        => 'home.countShare',
	'/search'             => 'home.search',
	'/rss-xml'			  => 'home.rssXml',
	'/viewer'             => 'viewer.index',
    //'/blog(/{:year}(/{:month}(/{:day}?)?)?)?' => 'home.category'
);
