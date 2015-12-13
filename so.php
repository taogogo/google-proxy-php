<?php
$url = $_GET['__url__'];
$url = empty($url) ? 'http://www.google.com' : $url;
$url .= $_SERVER['REQUEST_URI'];
//charset
$headers = get_headers($url,1);
//var_dump($headers);
if(strpos($headers['Content-Type'],'text/html') === false){
header('Content-type: '.$headers['Content-Type'],false);
}
$result = curl_get_contents($url);
$from = array('www.google.com','/webhp','http://so.itupup.com/imghp','<span id=gbi4s1>Sign in</span>');
$to = array('so.itupup.com','','https://so.itupup.com/imghp','');
$result = str_replace($from,$to,$result);
//处理url跳转
preg_match_all("/<a(.*) href=\"\/url([^\"]+)\"(.*)>/isU", $result, $matches);
foreach ($matches[2] as $linkurl) {
 $var  = parse_url($linkurl, PHP_URL_QUERY);
 parse_str($var,$pdata);
 $result = str_replace('/url'.$linkurl,$pdata['q'].'" target="_blank" rel="noreferrer' ,$result);
 //var_dump($linkurl,$pdata['q']);
}
if(strpos($headers['Content-Type'],'text/html') !== false){
echo <<<EOT
<meta name="robots" content="noarchive">
EOT;
}
echo $result;
if(strpos($headers['Content-Type'],'text/html') !== false){
echo <<<EOT
<script>
EOT;
$hiddenIds  = array(
'gb_119','gb_5','gb_8','gb_23','gb_36','gb_78','gb_119','gbztms','gbtc','gbg','gbgs4','pmocntr2'
);
foreach($hiddenIds as $id){
echo <<<EOT
document.getElementById('{$id}').style.display='none';
EOT;
}

echo <<<EOT
</script>

EOT;
}

//echo curl_get_contents($url);
function curl_get_contents($url)   
{   stream_context_set_default(
    array(
        'http' => array(
            'method' => 'HEAD'
        )
    )
);

//var_dump($headers);
    $ch = curl_init();   
    curl_setopt($ch, CURLOPT_URL, $url);            //设置访问的url地址   
    //curl_setopt($ch,CURLOPT_HEADER,1);            //是否显示头部信息   
    curl_setopt($ch, CURLOPT_TIMEOUT, 5);           //设置超时   
    curl_setopt($ch, CURLOPT_USERAGENT,'Mozilla/5.0 (compatible; MSIE 9.11; Windows NT');   //用户访问代理 User-Agent   
    curl_setopt($ch, CURLOPT_REFERER,'');        //设置 referer   
    curl_setopt($ch,CURLOPT_FOLLOWLOCATION,1);      //跟踪301   
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);        //返回结果   
    $r = curl_exec($ch);   
    curl_close($ch);   
    return $r;   
}  