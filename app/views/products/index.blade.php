@extends('layouts.master')
@section('css')
    <link href="http://7u2on3.com2.z0.glb.qiniucdn.com/jBox.css" rel="stylesheet">
@stop
@section('content')
    <div class="row">
        <div class="col-lg-8 col-lg-offset-2 col-md-8 col-md-offset-2 col-sm-8 col-sm-offset-2">
            <div class="progress">
                <div class="progress-bar progress-bar-next littletip" title="{{$rates['next_count']}}条来自Next的数据" style="width: {{$rates['next_rate']}}%"></div>
                <div class="progress-bar progress-bar-producthunt littletip" title="{{$rates['producthunt_count']}}条来自ProductHunt的数据" style="width: {{$rates['producthunt_rate']}}%"></div>
                <div class="progress-bar progress-bar-mindstore littletip" title="{{$rates['mindstore_count']}}条来自Mindstore的数据" style="width: {{$rates['mindstore_rate']}}%"></div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-8 col-lg-offset-2 col-md-8 col-md-offset-2 col-sm-8 col-sm-offset-2">
            <div class="alert alert-dismissable alert-warning">
                <button type="button" class="close" data-dismiss="alert">×</button>
                <p class="text-center">通过 <a href="{{URL::route('product.feed')}}" class="alert-link">RSS</a> 阅读</p>
            </div>
        </div>
    </div>
    <div class="post">
        <div class="row">
            <div class="col-lg-2 col-lg-offset-2 col-md-2 col-md-offset-2 col-sm-8 col-sm-offset-2">
                <span class="date_day">{{$last_date_day}}</span>
                <span class="date">{{$last_date_zh}}</span>
            </div>
        </div>
        <div class="row product-list">
            <div class="col-lg-8 col-lg-offset-2 col-md-8 col-md-offset-2 col-sm-8 col-sm-offset-2">
                @foreach($products as $product)
                    <div class="bs-callout bs-callout-{{$product->from}} product">
                        <div class="container-fluid">
                            <div class="col-lg-9 col-md-8 col-sm-7 left-part">
                                <a href="{{$product->product_url}}" target="_blank">
                                    <span><i class="glyphicon glyphicon-link"></i></span>
                                    <strong>{{$product->title}}</strong>
                                </a>
                                <p>{{$product->desc}}</p>
                            </div>
                            <div class="col-lg-3 col-md-4 col-sm-5 right-part">
                                <div class="pull-right">
                                    <a href="{{$product->site_url}}" target="_blank" title="访问{{$product->from}}">
                                        <span class="label label-{{$product->from}}">{{$product->from}}</span>
                                        <span class="new-window"><i class="glyphicon glyphicon-new-window"></i></span>
                                    </a>
                                    <span class="comment"><i class="glyphicon glyphicon-comment"></i></span>
                                    <span class="num">{{is_null($product->comment_count) ? 0 : $product->comment_count;}}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    <div class="row load-button">
        <div class="col-lg-8 col-lg-offset-2 col-md-8 col-md-offset-2 col-sm-8 col-sm-offset-2">
            <div class="pager">
                <button type="button" id="loaddata" class="btn btn-info btn-lg btn-block" onclick="loaddata('{{Request::segment(2)}}')">加载更多 {{$prev_date_day}} 数据</button>
            </div>
        </div>
    </div>
@stop
@section('js')
    <script src="http://7u2on3.com2.z0.glb.qiniucdn.com/jBox.min.js"></script>
    <script>
        $(document).ready(function(){
            $('.littletip').jBox('Tooltip');
        });
        var last_date = "{{$last_date}}";
        function loaddata(from)
        {
            $('#loaddata').attr('disabled',true);
            $('#loaddata').text('数据加载中...');
            var date = beforeDate(last_date);
            $.get('{{URL::to('/')}}/api/fetch-data',{date:date,from:from},function(data){
                if (data.status==1)
                {
                    $('.load-button').hide().before(data.html).slideDown('slow');
                    $('#loaddata').attr('disabled',false);
                    $('#loaddata').text('加载更多 ' + data.date_zh + ' 数据');
                } else {
                    $('#loaddata').text(data.error);
                }
            });
            last_date = date;
        }
        function beforeDate(str){
            var strAry = str.split('-');
            var date = new Date();
            date.setFullYear(strAry[0]*1);
            date.setMonth(strAry[1]*1-1);
            date.setDate(strAry[2]*1);
            var newTime = date.getTime()-1000*60*60*24;
            var newDate = new Date(newTime);

            return newDate.getFullYear() + '-' + (newDate.getMonth()+1<10?'0'+(newDate.getMonth()+1):newDate.getMonth()+1) + '-' + (newDate.getDate()<10?'0'+newDate.getDate():newDate.getDate());
        }
    </script>
@stop