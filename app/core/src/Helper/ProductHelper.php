<?php namespace core\src\Helper;

use Carbon\Carbon;

class ProductHelper {

    public function getDateDiff($date)
    {
        $now = new Carbon('Asia/Shanghai');
        $compare_date = new Carbon($date, 'Asia/Shanghai');
        $diff = $compare_date->diffForHumans($now);
        $diff_days = $compare_date->diffInDays($now);
        if ($diff_days == 0)
        {
            return '今天';
        }
        if ($diff == '1 day before')
        {
            $string = '昨天';
        } else
        {
            switch ($compare_date->dayOfWeek)
            {
                case 0:
                    $string = '星期天';
                    break;
                case 1:
                    $string = '星期一';
                    break;
                case 2:
                    $string = '星期二';
                    break;
                case 3:
                    $string = '星期三';
                    break;
                case 4:
                    $string = '星期四';
                    break;
                case 5:
                    $string = '星期五';
                    break;
                case 6:
                    $string = '星期六';
                    break;
            }
        }

        return $string;
    }

    /**
     * @param $date
     * @return bool|string
     */
    public function prevDay($date)
    {
        $carbon_date = new Carbon($date, 'Asia/Shanghai');
        $prevDay = $carbon_date->subDay()->toDateString();

        return date('m月d日', strtotime($prevDay));
    }

    /**
     * @param $date
     * @return string
     */
    public function prevDateDiff($date)
    {
        $carbon_date = new Carbon($date, 'Asia/Shanghai');
        $compare_date = $carbon_date->subDay();
        $now = new Carbon('Asia/Shanghai');
        $diff = $compare_date->diffForHumans($now);

        if ($diff == '1 day before')
        {
            $string = '昨天';
        } else
        {
            $string = date('m月d日', strtotime($compare_date->toDateString()));
        }

        return $string;
    }
} 