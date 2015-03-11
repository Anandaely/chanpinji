<?php namespace core\src\Repository;

use Avos\AVQuery;
use Carbon\Carbon;

/**
 * Class AvosProductRepository
 *
 * @package core\src\Repository
 */
class AvosProductRepository extends \AVModel implements ProductRepositoryInterface {

    /**
     * construct
     */
    function __construct()
    {
        parent::__construct();
        $this->query = new AVQuery(\Config::get('avos.product_model'));
    }

    /**
     * @return mixed
     */
    public function obCreate()
    {
        return $this->create(\Config::get('avos.product_model'));
    }

    /**
     * @return AVQuery
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
        return $this->query->limit(\Config::get('avos.max_number'))->find();
    }

    /**
     * @param $date
     * @param array $from
     * @return array
     */
    public function getItems($date, $from = null)
    {
        if (is_null($from))
        {
            $products = $this->query->equalTo('date',
                $date)->descending('title')->limit(\Config::get('avos.max_number'))->find();
        } else
        {
            $products = $this->query->equalTo('date', $date)->equalTo('from',
                $from)->limit(\Config::get('avos.max_number'))->find();
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