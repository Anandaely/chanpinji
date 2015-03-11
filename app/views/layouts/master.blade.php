<!DOCTYPE html>
<html lang="zh-cn">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>产品集 | 收集所有好的新产品</title>

    <meta name="author" content="BigBing">
    <meta name="description" content="产品集 - 每天收集好的新产品">
    <meta name="keywords" content="产品集，App，网站，互联网产品，创业">

    <!-- Bootstrap -->
    <link href="http://7u2on3.com2.z0.glb.qiniucdn.com/bootstrap.min.css" rel="stylesheet">
    <link href="http://7u2on3.com2.z0.glb.qiniucdn.com/flatly/bootstrap.min.css" rel="stylesheet">
    <link href="http://7u2on3.com2.z0.glb.qiniucdn.com/style_0123.css" rel="stylesheet">

    @yield('css')
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="http://cdn.bootcss.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="http://cdn.bootcss.com/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body>

<div class="container-fluid">
    <div class="navbar">
        <div class="row">
            <div class="col-lg-8 col-lg-offset-2 col-md-8 col-md-offset-2 col-sm-8 col-sm-offset-2 ">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-inverse-collapse">
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="#">产品集</a>
                </div>
                <div class="navbar-collapse collapse navbar-inverse-collapse">
                    <ul class="nav navbar-nav navbar-right product-nav">
                        <li class="all"><a href="/" @if(Request::is('/'))class="active"@endif>All</a></li>
                        <li class="next"><a href="{{URL::action('product.from',['str'=>'next'])}}" @if(Request::segment(2)=='next')class="active"@endif>Next</a></li>
                        <li class="producthunt"><a href="{{URL::action('product.from',['str'=>'producthunt'])}}" @if(Request::segment(2)=='producthunt')class="active"@endif>Product Hunt</a></li>
                        <li class="mindstore"><a href="{{URL::action('product.from',['str'=>'mindstore'])}}" @if(Request::segment(2)=='mindstore')class="active"@endif>Mindstore</a></li>
                    </ul>
                </div>
                @yield('menu')
            </div>
        </div>
    </div>
    @yield('content')
    <footer>
        <div class="row">
            <div class="col-lg-8 col-lg-offset-2">
                <p>Made by <a href="https://github.com/aidai524" rel="nofollow">Joe Chu</a>. Contact him at <a href="mailto:aidai524@gmail.com">aidai524@gmail.com</a>.</p>
                <p>Based on <a href="http://getbootstrap.com" rel="nofollow">Bootstrap</a>.</p>
            </div>
        </div>
    </footer>
</div>
<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script src="http://cdn.staticfile.org/jquery/2.1.1/jquery.min.js"></script>
<!-- Include all compiled plugins (below), or include individual files as needed -->
<script src="http://cdn.staticfile.org/twitter-bootstrap/3.3.0/js/bootstrap.min.js"></script>
@yield('js')
<script>
var _hmt = _hmt || [];
(function() {
  var hm = document.createElement("script");
  hm.src = "//hm.baidu.com/hm.js?846e7b8eb160e79da45fb83d6132b74c";
  var s = document.getElementsByTagName("script")[0];
  s.parentNode.insertBefore(hm, s);
})();
</script>
</body>
</html>