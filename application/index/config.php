<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/12/24
 * Time: 22:51
 */
return [
    'view_replace_str'       => [
        '__PUBLIC__' => SITE_URL.'/public/static/index',
        '__ADMIN__' => SITE_URL.'/public/static/admin',
        '__IMG__'=>SITE_URL.'/public/static/index/image/',
        '__ROOT__'=>'/',
            ],

    'session'                => [
        'prefix'         => '',
        'type'           => '',
        'auto_start'     => true,
        ],
    ];