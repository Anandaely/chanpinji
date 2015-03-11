<?php
use Parse\ParseClient;
use Parse\ParseObject;
use Parse\ParseSessionStorage;

/**
 * Class ParseModel
 */
class ParseModel extends ParseObject {

    /**
     *  Parse数据初始化
     */
    function __construct()
    {
        ParseClient::initialize(Config::get('parse.app_id'), Config::get('parse.rest_key'), Config::get('parse.master_key'));
        ParseClient::setStorage(new ParseSessionStorage());
    }
}