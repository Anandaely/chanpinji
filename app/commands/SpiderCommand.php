<?php
use Carbon\Carbon;
use Illuminate\Console\Command;
use Monashee\PhpSimpleHtmlDomParser\PhpSimpleHtmlDomParser;
use Symfony\Component\Console\Input\InputArgument;

const MINDSTORE_URL = 'http://mindstore.io';
const NEXT_URL = 'http://next.36kr.com';
const PRODUCT_HUNT_URL = 'http://www.producthunt.com';
/**
 * Class SpiderCommand
 */
class SpiderCommand extends Command {

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'fetch:data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description.';
    /**
     * @var Product
     */
    private $product;

    /**
     * Create a new command instance.
     *
     * @param Product $product
     */
    public function __construct(Product $product)
    {
        parent::__construct();
        $this->product = $product;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function fire()
    {
        $this->line("I am a Spider Robot");
        $name = $this->argument('name');

        if ($name == 'mindstore')
        {
            $this->fetchMindstore();
        } elseif ($name == 'next')
        {
            $this->fetchNext();
        } elseif ($name == 'producthunt')
        {
            $this->fetchProductHunt();
        } elseif ($name == 'all')
        {
            $this->fetchMindstore();
            $this->fetchNext();
            $this->fetchProductHunt();
        }
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return array(
            array('name', InputArgument::REQUIRED, 'Website name.'),
        );
    }

    /**
     * 抓取Mindstore数据
     */
    public function fetchMindstore()
    {
        $parser = new PhpSimpleHtmlDomParser();//用来加载require_once('simple_html_dom')
        $html_mindstore = new simple_html_dom();
        $html_mindstore->load($this->file_get_by_curl(MINDSTORE_URL));
        unset($parser);

        foreach ($html_mindstore->find('li.mind-item') as $i => $mindstore_element)
        {
            $mindstore_items[$i]['site_url'] = $this->getMindstoreUrlById(trim($mindstore_element->getAttribute('data-itemid')));
            $mindstore_items[$i]['title'] = trim($mindstore_element->find('a.mind-title', 0)->plaintext);
            $mindstore_items[$i]['product_url'] = trim($mindstore_element->find('a.mind-title', 0)->href);
            $mindstore_items[$i]['desc'] = trim($mindstore_element->find('div.mind-des', 0)->plaintext);
            $mindstore_items[$i]['comment_count'] = trim($mindstore_element->find('span.reply-num', 0)->plaintext);
            $mindstore_items[$i]['date'] = $this->getDate($this->getCarbonValue($this->turnChsToEng(trim($mindstore_element->find('span.public-date',
                0)->plaintext))));
            $mindstore_items[$i]['date_time'] = $this->getDatetime($this->getCarbonValue($this->turnChsToEng(trim($mindstore_element->find('span.public-date',
                0)->plaintext))));
            $mindstore_items[$i]['from'] = 'mindstore';
            $mindstore_items[$i]['website'] = MINDSTORE_URL;
        }

        $html_mindstore->clear();
        unset($html_mindstore);

        $this->saveData($mindstore_items);
        $this->line(count($mindstore_items) . "条来自mindstore的数据保存成功");
    }

    /**
     * 抓取Next数据
     */
    public function fetchNext()
    {
        $all_items = [];
        $parser = new PhpSimpleHtmlDomParser();//用来加载require_once('simple_html_dom')
        $html_next = new simple_html_dom();
        $html_next->load($this->file_get_by_curl(NEXT_URL));
        unset($parser);

        foreach ($html_next->find('section.post') as $next_section_element)
        {
            $date_month = $next_section_element->find('i.month', 0)->plaintext;
            $date_day = $next_section_element->find('i.day', 0)->plaintext;
            $date = $date_month . ' ' . $date_day;

            foreach ($next_section_element->find('li.product-item') as $j => $next_element)
            {
                $next_items[$j]['site_url'] = $this->getNextUrlByString(trim($next_element->find('a.product-link',
                    0)->href));
                $next_items[$j]['title'] = trim($next_element->find('a.post-url', 0)->plaintext);
                $next_items[$j]['product_url'] = $this->getNextUrlByString(trim($next_element->find('a.post-url',
                    0)->href));
                $next_items[$j]['desc'] = trim($next_element->find('span.post-tagline', 0)->plaintext);
                $next_items[$j]['comment_count'] = isset($next_element->find('a.product-comments',
                        0)->plaintext) ? trim($next_element->find('a.product-comments', 0)->plaintext) : '0';
                $next_items[$j]['date'] = $this->getDate($this->getCarbonValue($date));
                $next_items[$j]['date_time'] = $this->getDatetime($this->getCarbonValue($date));
                $next_items[$j]['from'] = 'next';
                $next_items[$j]['website'] = NEXT_URL;
            }
            $all_items = array_merge($all_items, $next_items);
        }

        $html_next->clear();
        unset($html_next);

        $this->saveData($all_items);
        $this->line(count($all_items) . "条来自next的数据保存成功");
    }

    /**
     * 抓取ProductHunt数据
     */
    public function fetchProductHunt()
    {
        $all_items = [];
        $parser = new PhpSimpleHtmlDomParser();//用来加载require_once('simple_html_dom')
        $html_product = new simple_html_dom();
        $html_product->load($this->file_get_by_curl(PRODUCT_HUNT_URL));
        unset($parser);

        foreach ($html_product->find('div.day') as $product_day_element)
        {
            $date = $product_day_element->find('.posts--date', 0)->getAttribute('datetime');

            foreach ($product_day_element->find('li.post') as $k => $product_element)
            {
                $product_items[$k]['site_url'] = $this->getProductUrlByString(trim($product_element->find('a.post--actions--comments',
                    0)->href));
                $product_items[$k]['title'] = trim($product_element->find('a.post-url', 0)->plaintext);
                $product_items[$k]['product_url'] = $this->getProductUrlByString(trim($product_element->find('a.post-url',
                    0)->href));
                $product_items[$k]['desc'] = trim($product_element->find('span.post-tagline', 0)->plaintext);
                $product_items[$k]['comment_count'] = isset($product_element->find('a.comment-count',
                        0)->plaintext) ? trim($product_element->find('a.comment-count', 0)->plaintext) : '0';
                $product_items[$k]['date'] = $this->getDate($this->getCarbonValue($date));
                $product_items[$k]['date_time'] = $this->getDatetime($this->getCarbonValue($date));
                $product_items[$k]['from'] = 'producthunt';
                $product_items[$k]['website'] = PRODUCT_HUNT_URL;
            }
            $all_items = array_merge($all_items, $product_items);
        }

        $html_product->clear();
        unset($html_product);

        $this->saveData($all_items);
        $this->line(count($all_items) . "条来自producthunt的数据保存成功");
    }

    /**
     * @param $id
     * @return string
     */
    public function getMindstoreUrlById($id)
    {
        return MINDSTORE_URL . '/mind/' . $id;
    }

    /**
     * @param $string
     * @return string
     */
    public function getNextUrlByString($string)
    {
        return NEXT_URL . $string;
    }

    /**
     * @param $string
     * @return string
     */
    public function getProductUrlByString($string)
    {
        return PRODUCT_HUNT_URL . $string;
    }

    /**
     * @param $data
     * @return Carbon
     */
    public function getCarbonValue($data)
    {
        $date_time = new Carbon($data, 'Asia/Shanghai');

        return $date_time;
    }

    /**
     * @param $carbon_value
     * @return Carbon
     */
    public function getDatetime($carbon_value)
    {
        $carbon_value->subHours(8);
        $carbon_value->format('Y-m-d H:i:s');

        return $carbon_value;
    }

    /**
     * @param $carbon_value
     * @return string
     */
    public function getDate($carbon_value)
    {
        $carbon_value->format('Y-m-d');

        return $carbon_value->toDateString();
    }

    /**
     * @param $pub_date
     * @return mixed
     */
    public function turnChsToEng($pub_date)
    {
        if (is_numeric(strpos($pub_date, '天前')))
        {
            $eng_pub_date = str_replace('天前', 'dayago', $pub_date);
        } elseif (is_numeric(strpos($pub_date, '分钟前')))
        {
            $eng_pub_date = str_replace('分钟前', 'minuteago', $pub_date);
        } else
        {
            $eng_pub_date = str_replace('小时前', 'hourago', $pub_date);
        }

        return $eng_pub_date;
    }

    /**
     * @param $items
     */
    public function saveData($items)
    {
        $update_count = $insert_count = 0;
        foreach ($items as $item)
        {
            if ($this->saveObject($item))
            {
                $insert_count++;
            } else
            {
                $update_count++;
            }
        }
        $this->line('插入成功---->' . $insert_count . '条');
        $this->line('更新成功---->' . $update_count . '条');
        Log::info('插入成功---->' . $insert_count . '条');
        Log::info('更新成功---->' . $update_count . '条');
    }

    /**
     * @param $item
     * @return bool
     */
    public function saveObject($item)
    {
        if (!$this->findObject($item))
        {
            $object = $this->product->obCreate();
            $object->set('site_url', $item['site_url']);
            $object->set('title', $item['title']);
            $object->set('desc', $item['desc']);
            $object->set('product_url', $item['product_url']);
            $object->set('comment_count', intval($item['comment_count']));
            $object->set('from', $item['from']);
            $object->set('date_time', $item['date_time']);
            $object->set('date', $item['date']);
            $object->set('website', $item['website']);
            $object->save();
            $this->line('插入成功---->' . $object->getObjectId() . ' ' . '日期:' . $item['date'] . ' ' . $item['title']);

            return true;
        } else
        {
            return false;
        }
    }

    /**
     * @param $item
     * @return bool
     */
    public function findObject($item)
    {
        $object = $this->product->dbQuery()->equalTo("title", $item['title'])->first();
        if (!empty($object))
        {
            $object->set('comment_count', intval($item['comment_count']));
            $object->set('desc', $item['desc']);
            $object->save();
            $this->line('更新成功---->' . $object->getObjectId() . ' ' . '日期:' . $item['date'] . ' ' . $item['title']);

            return true;
        } else
        {
            return false;
        }
    }

    /**
     * file_get_html被ban，采用curl抓取数据
     *
     * @param $base
     * @return mixed
     */
    protected function file_get_by_curl($base)
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($curl, CURLOPT_URL, $base);
        curl_setopt($curl, CURLOPT_REFERER, $base);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $str = curl_exec($curl);
        curl_close($curl);

        return $str;
    }
}
