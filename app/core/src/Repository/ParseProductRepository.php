<?php namespace core\src\Repository;

use Parse\ParseQuery;

/**
 * Class ParseProductRepository
 *
 * @package core\src\Repository
 */
class ParseProductRepository extends \ParseModel implements ProductRepositoryInterface {

    /**
     * construct
     */
    function __construct()
    {
        parent::__construct();
        $this->query = new ParseQuery(\Config::get('parse.product_model'));
    }

    /**
     * @return Object
     */
    public function obCreate()
    {
        return $this->create(\Config::get('parse.product_model'));
    }

    /**
     * @return ParseQuery
     */
    public function dbQuery()
    {
        return $this->query;
    }

    /**
     * @param $objectId
     * @return array
     */
    public function find($objectId)
    {
        return $this->query->equalTo('ObjectId', $objectId)->first();
    }

    /**
     * @return array
     */
    public function all()
    {
        return $this->query->limit(\Config::get('parse.max_number'))->find();
    }

    /**
     * @param $date
     * @param null $from
     * @return array
     */
    public function getItems($date, $from = null)
    {
        if (is_null($from))
        {
            $products = $this->query->equalTo('date',
                $date)->descending('title')->limit(\Config::get('parse.max_number'))->find();
        } else
        {
            $products = $this->query->equalTo('date', $date)->equalTo('from',
                $from)->limit(\Config::get('parse.max_number'))->find();
        }

        return $products;
    }

    /**
     * @param null $from
     * @return mixed
     */
    public function getLastDate($from = null)
    {
        if (is_null($from))
        {
            $last_day = $this->query->descending('date')->first();
        } else
        {
            $last_day = $this->query->equalTo('from', $from)->descending('date')->first();
        }

        $date = $last_day->get('date');

        return date('Y-m-d') < $date ? date('Y-m-d') : $date;
    }
} 