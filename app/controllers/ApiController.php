<?php

use core\src\Helper\ProductHelper;
use Illuminate\Support\Facades\Input;

class ApiController extends \BaseController {

    /**
     * @var Product
     */
    private $product;
    /**
     * @var ProductHelper
     */
    private $productHelper;

    /**
     * @param Product $product
     * @param ProductHelper $productHelper
     */
    function __construct(Product $product, ProductHelper $productHelper)
    {
        $this->product = $product;
        $this->productHelper = $productHelper;
    }

    public function getFetchData()
    {
        $date = Input::get('date');
        $from = Input::get('from');

        $date_day = $this->productHelper->getDateDiff($date);
        $date_zh = date('m月d日', strtotime($date));
        $products = empty($from) ? $this->product->getItems($date) : $this->product->getItems($date, $from);

        if (empty($products))
        {
            return array('html' => '', 'date_zh' => '', 'status' => 0, 'error' => '没有更多数据了');
        } else
        {
            $data = '
            <div class="post">
                <div class="row">
                    <div class="col-lg-2 col-lg-offset-2 col-md-2 col-md-offset-2 col-sm-8 col-sm-offset-2">
                        <span class="date_day">' . $date_day . '</span>
                        <span class="date">' . $date_zh . '</span>
                    </div>
                </div>
                <div class="row product-list">
                    <div class="col-lg-8 col-lg-offset-2 col-md-8 col-md-offset-2 col-sm-8 col-sm-offset-2">';

            foreach ($products as $product)
            {
                $comment_count = is_null($product->comment_count) ? 0 : $product->comment_count;
                $data = $data . '<div class="bs-callout bs-callout-' . $product->from . ' product">
                            <div class="container-fluid">
                                <div class="col-lg-9 col-md-8 col-sm-7 left-part">
                                    <a href="' . $product->product_url . '" target="_blank">
                                        <span><i class="glyphicon glyphicon-link"></i></span>
                                        <strong>' . $product->title . '</strong>
                                    </a>
                                    <p>' . $product->desc . '</p>
                                </div>
                                <div class="col-lg-3 col-md-4 col-sm-5 right-part">
                                    <div class="pull-right">
                                        <a href="' . $product->site_url . '" target="_blank" title="访问' . $product->from . '">
                                            <span class="label label-' . $product->from . '">' . $product->from . '</span>
                                            <span class="new-window"><i class="glyphicon glyphicon-new-window"></i></span>
                                        </a>
                                        <span class="comment"><i class="glyphicon glyphicon-comment"></i></span>
                                        <span class="num">' . $comment_count . '</span>
                                    </div>
                                </div>
                            </div>
                        </div>';
            }
            $data = $data . '
                    </div>
                </div>
            </div>';

            return array('html'    => $data,
                         'date_zh' => $this->productHelper->prevDay($date),
                         'status'  => 1,
                         'error'   => ''
            );
        }
    }
}