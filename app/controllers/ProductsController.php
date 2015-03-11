<?php

use core\src\Helper\ProductHelper;

class ProductsController extends \BaseController {

    /**
     * @var Product
     */
    private $product;
    /**
     * @var ProductHelper
     */
    private $helper;

    /**
     * @param Product $product
     * @param ProductHelper $helper
     */
    function __construct(Product $product, ProductHelper $helper)
    {
        $this->product = $product;
        $this->helper = $helper;
    }

    /**
     * Display a listing of products
     *
     * @return Response
     */
    public function index()
    {
        $last_date = $this->product->getLastDate();
        $last_date_day = $this->product->getDateDay($last_date);
        $last_date_zh = date('m月d日', strtotime($last_date));
        $prev_date_day = $this->helper->prevDateDiff($last_date);
        $products = $this->product->getItems($last_date);
        $rates = $this->product->getDayRateByItems($products);

        return View::make('products.index', array(
            'products'      => $products,
            'rates'         => $rates,
            'last_date_day' => $last_date_day,
            'last_date_zh'  => $last_date_zh,
            'last_date'     => $last_date,
            'prev_date_day' => $prev_date_day
        ));
    }

    /**
     * Display a next listing of products
     *
     * @param $str
     * @return Response
     */
    public function from($str)
    {
        $last_date = $this->product->getLastDate($str);
        $last_date_day = $this->product->getDateDay($last_date);
        $last_date_zh = date('m月d日', strtotime($last_date));
        $prev_date_day = $this->helper->prevDateDiff($last_date);
        $products = $this->product->getItems($last_date, $str);
        $rates = $this->product->getDayRateByItems($products, $str);

        return View::make('products.index', array(
            'products'      => $products,
            'rates'         => $rates,
            'last_date_day' => $last_date_day,
            'last_date_zh'  => $last_date_zh,
            'last_date'     => $last_date,
            'prev_date_day' => $prev_date_day
        ));
    }

    public function feed($str = null)
    {
        $str = ($str == 'index' or is_null($str)) ? null : $str;
        $last_date = $this->product->getLastDate($str);
        $products = $this->product->getItems($last_date, $str);

        $channel = [
            'title'       => '产品集',
            'description' => '收集Next,Mindstore,ProductHunt的产品',
            'link'        => URL::route('product.feed', array('str' => $str)),
        ];

        $feed = Rss::feed('2.0', 'UTF-8');
        $feed->channel($channel);

        foreach ($products as $product)
        {
            if (mb_detect_encoding($product->desc) == 'UTF-8')
            {
                $feed->item([
                    'title'             => $product->title,
                    'description|cdata' => preg_replace('/[\x00-\x1f]/', '?', $product->desc),
                    'link'              => $product->site_url,
                    'product_link'      => $product->product_url
                ]);
            }
        }

        return Response::make($feed, 200, array('Content-Type' => 'text/xml'));
    }

    /**
     * Show the form for creating a new product
     *
     * @return Response
     */
    public function create()
    {
        return View::make('products.create');
    }

    /**
     * Store a newly created product in storage.
     *
     * @return Response
     */
    public function store()
    {
        $validator = Validator::make($data = Input::all(), Product::$rules);

        if ($validator->fails())
        {
            return Redirect::back()->withErrors($validator)->withInput();
        }

        Product::create($data);

        return Redirect::route('products.index');
    }

    /**
     * Display the specified product.
     *
     * @param  int $id
     * @return Response
     */
    public function show($id)
    {
        $product = Product::findOrFail($id);

        return View::make('products.show', compact('product'));
    }

    /**
     * Show the form for editing the specified product.
     *
     * @param  int $id
     * @return Response
     */
    public function edit($id)
    {
        $product = Product::find($id);

        return View::make('products.edit', compact('product'));
    }

    /**
     * Update the specified product in storage.
     *
     * @param  int $id
     * @return Response
     */
    public function update($id)
    {
        $product = Product::findOrFail($id);

        $validator = Validator::make($data = Input::all(), Product::$rules);

        if ($validator->fails())
        {
            return Redirect::back()->withErrors($validator)->withInput();
        }

        $product->update($data);

        return Redirect::route('products.index');
    }

    /**
     * Remove the specified product from storage.
     *
     * @param  int $id
     * @return Response
     */
    public function destroy($id)
    {
        Product::destroy($id);

        return Redirect::route('products.index');
    }

}
