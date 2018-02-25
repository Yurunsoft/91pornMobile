<?php
#error_reporting(0);
#引入模块
require 'lib/phpQuery.php';
require 'lib/QueryList.php';

use QL\QueryList;

function getList(){

	$url = "https://free-proxy-list.net/";
	echo "URL is : ". $url ."<br>";

	#读取HTML
	$opts = array(
	  'http'=>array(
	    'method'=>"GET",
	    'header'=>"Content-Type: text/xml\r\n"."charset=utf-8\r\n"."Accept-language: zh-cn\r\n"."Cookie: foo=bar\r\n",
	  )
	);

	$context = stream_context_create($opts);

	// Open the file using the HTTP headers set above
	$html = file_get_contents($url, false, $context);
	#$file = iconv("utf-8", "utf-8",file_get_contents($url, false, $context));

	//echo ($html);

	//$html=str_replace("�", "", $html);
	

	$rules = array(
    //采集id为one这个元素里面的纯文本内容
    'ip' => array('tbody>tr:lt(20)','html',"-td:gt(0) td"),
    'port' => array('tbody>tr:lt(20)','html',"-td:gt(1) -td:eq(0) td"),
    'country' => array('tbody>tr:lt(20)','html',"-td:gt(2) -td:lt(1) td"),
	);
	$data = QueryList::Query($html,$rules)->data;
	//print_r($data);
	return $data;
}

$list = getList();
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="initial-scale=1, maximum-scale=1, user-scalable=no">
        <meta name="format-detection" content="telephone=no">
        <title>91视频预览</title>
        <link rel="stylesheet" href="frozenui/css/frozen.css">
        <link rel="stylesheet" href="frozenui/css/demo.css">
        <script src="frozenui/lib/zepto.min.js"></script>
    </head>
    <body ontouchstart>
    	<header class="ui-header ui-header-positive ui-border-b">
            <h1>91视频预览</h1>
        </header>

        <section class="ui-container">
		<section id="panel">
    <div class="demo-item">
        <p class="demo-desc">设置</p>
        <form action="91.php" method="get">
                    <div class="ui-form-item ui-border-b">
                        <label>
                            91地址
                        </label>
                        <input type="text" name="domain" placeholder="输入地址，如：http://www.91porn.com" value="http://www.91porn.com">
                        
                    </div>
                    <div class="ui-form-item ui-border-b">
                        <label>
                            页码
                        </label>
                        <input placeholder="页码" name="page" type="number" value="1">
                    </div>
                    <div class="ui-form-item ui-border-b">
                        <label>
                            代理服务器
                        </label>
                        <div class="ui-select">
                            <select name="proxy">
                                <option>无</option>
                                <?php foreach ($list as $key => $value) {
                                	echo '<option value="'.$value["ip"].':'.$value["port"].'">'.$value["ip"].'['.$value["country"].']</option>';
                                } ?>
                            </select>
                        </div>
                    </div>
                    <div class="ui-btn-wrap">
		                <button type="submit" class="ui-btn-lg ui-btn-primary">
		                    确定
		                </button>
		            </div>
                </form>
    </div>
		</section>
                
		
    </body>
</html>