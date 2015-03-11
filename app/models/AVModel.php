<?php
use Avos\AVClient;
use Avos\AVObject;
use Avos\AVSessionStorage;

/**
 * Class AVModel
 */
class AVModel extends AVObject {

    /**
     *  AVOS数据初始化
     */
    function __construct()
    {
        session_start();
        AVClient::initialize(Config::get('avos.app_id'), Config::get('avos.app_key'), Config::get('avos.master_key'));
        AVClient::setStorage(new AVSessionStorage());
    }
}