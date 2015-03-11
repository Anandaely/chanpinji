<?php
use Avos\AVQuery;
use core\src\Helper\ProductHelper;
use core\src\Repository\AvosProductRepository;
use core\src\Repository\ParseProductRepository;

/**
 * Class Product
 */
class Product {

    /**
     * @var ProductHelper
     */
    private $helper;
    /**
     * @var AvosProductRepository
     */
    private $avos;
    /**
     * @var ParseProductRepository
     */
    private $parse;

    /**
     * 构造函数
     *
     * @param ProductHelper $helper
     * @param AvosProductRepository $avos
     * @param ParseProductRepository $parse
     */
    function __construct(ProductHelper $helper, AvosProductRepository $avos, ParseProductRepository $parse)
    {
        $this->helper = $helper;
        $this->avos = $avos;
        $this->parse = $parse;
    }

    /**
     * @return Object
     */
    public function obCreate()
    {
        return Config::get('database.cloud') == 'avos' ? $this->avos->obCreate() : $this->parse->obCreate();
    }

    /**
     * @return AVQuery
     */
    public function dbQuery()
    {
        return Config::get('database.cloud') == 'avos' ? $this->avos->dbQuery() : $this->parse->dbQuery();
    }

    /**
     * @param $objectId
     * @return array
     */
    public function find($objectId)
    {
        return Config::get('database.cloud') == 'avos' ? $this->avos->find($objectId) : $this->parse->find($objectId);
    }

    /**
     * @return array
     */
    public function all()
    {
        return Config::get('database.cloud') == 'avos' ? $this->avos->all() : $this->parse->all();
    }

    /**
     * @param $date
     * @param array $from
     * @return array
     */
    public function getItems($date, $from = null)
    {
        return Config::get('database.cloud') == 'avos' ? $this->avos->getItems($date,
            $from) : $this->parse->getItems($date, $from);
    }

    public function getDayRateByItems($all_items, $from = null)
    {
        $producthunt_items = $next_items = $mindstore_items = array();

        if (!empty($all_items))
        {
            foreach ($all_items as $item)
            {
                if ($item->from == 'producthunt')
                {
                    $producthunt_items[] = $item;
                } elseif ($item->from == 'next')
                {
                    $next_items[] = $item;
                } elseif ($item->from == 'mindstore')
                {
                    $mindstore_items[] = $item;
                }
            }
        }

        $mindstore_count = count($mindstore_items);
        $next_count = count($next_items);
        $producthunt_count = count($producthunt_items);

        if (is_null($from))
        {
            $all_count = count($all_items);
            $producthunt_rate = round(($producthunt_count / $all_count) * 100);
            $next_rate = round(($next_count / $all_count) * 100);
            $mindstore_rate = 100 - $producthunt_rate - $next_rate;
        } elseif ($from == 'next')
        {
            $next_rate = 100;
            $producthunt_count = $producthunt_rate = $mindstore_count = $mindstore_rate = 0;
        } elseif ($from == 'mindstore')
        {
            $mindstore_rate = 100;
            $producthunt_count = $producthunt_rate = $next_count = $next_rate = 0;
        } elseif ($from == 'producthunt')
        {
            $producthunt_rate = 100;
            $mindstore_count = $mindstore_rate = $next_count = $next_rate = 0;
        }

        return array(
            'producthunt_count' => $producthunt_count,
            'producthunt_rate'  => $producthunt_rate,
            'next_count'        => $next_count,
            'next_rate'         => $next_rate,
            'mindstore_count'   => $mindstore_count,
            'mindstore_rate'    => $mindstore_rate
        );
    }

    /**
     * @param $last_day
     * @param null $from
     * @return array
     */
    public function getDayRateByDate($last_day, $from = null)
    {
        if (is_null($from))
        {
            $all_count = count($this->getItems($last_day));
            $producthunt_count = count($this->getItems($last_day, 'producthunt'));
            $producthunt_rate = round(($producthunt_count / $all_count) * 100);
            $next_count = count($this->getItems($last_day, 'next'));
            $next_rate = round(($next_count / $all_count) * 100);
            $mindstore_count = count($this->getItems($last_day, 'mindstore'));
            $mindstore_rate = 100 - $producthunt_rate - $next_rate;
        } elseif ($from == 'next')
        {
            $next_count = count($this->getItems($last_day));
            $next_rate = 100;
            $producthunt_count = $producthunt_rate = $mindstore_count = $mindstore_rate = 0;
        } elseif ($from == 'mindstore')
        {
            $mindstore_count = count($this->getItems($last_day));
            $mindstore_rate = 100;
            $producthunt_count = $producthunt_rate = $next_count = $next_rate = 0;
        } elseif ($from == 'producthunt')
        {
            $producthunt_count = count($this->getItems($last_day));
            $producthunt_rate = 100;
            $mindstore_count = $mindstore_rate = $next_count = $next_rate = 0;
        }

        return array(
            'producthunt_count' => $producthunt_count,
            'producthunt_rate'  => $producthunt_rate,
            'next_count'        => $next_count,
            'next_rate'         => $next_rate,
            'mindstore_count'   => $mindstore_count,
            'mindstore_rate'    => $mindstore_rate
        );
    }

    /**
     * @param null $from
     * @return mixed
     */
    public function getLastDate($from = null)
    {
        return Config::get('database.cloud') == 'avos' ? $this->avos->getLastDate($from) : $this->parse->getLastDate($from);
    }

    /**
     * @param $date
     * @return string
     */
    public function getDateDay($date)
    {
        return $this->helper->getDateDiff($date);
    }
}