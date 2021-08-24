<?php
header('Cache-Control:no-cache,must-revalidate');
header('Pragma:no-cache');
header("Expires:0");
header("Access-Control-Allow-Origin:*");
//处理请求输出数据
//这将得到一个文件夹中的所有gif，jpg和png图片的数组
$rand=rand(0,1);
if($rand){
    $localurl="images/*.{gif,jpg,png}";
}else{
    $localurl="images/*.{gif,jpg,png}";
}
$img_array=glob($localurl,GLOB_BRACE);
//从数组中选择一个随机图片 
$img=array_rand($img_array);
$imgurl=$img_array[$img];
$https=isset($_GET["https"])?$_GET["https"]:1;
if($https == "true"){
    $imgurl='https://'.$_SERVER['SERVER_NAME'].'/'.$imgurl;
}else{
    $imgurl='http://'.$_SERVER['SERVER_NAME'].'/'.$imgurl;
}
if(isset($_GET["type"])?$_GET["type"]:1=="json"){
    $rTotal='0';
    $gTotal='0';
    $bTotal='0';
    $total='0';
    $imageInfo = getimagesize($img_array[$img]);
    //图片类型
    $imgType = strtolower(substr(image_type_to_extension($imageInfo[2]), 1));
    //对应函数
    $imageFun = 'imagecreatefrom' . ($imgType == 'jpg' ? 'jpeg' : $imgType);
    $i = $imageFun($img_array[$img]);
    //测试图片，自己定义一个，注意路径
    for($x=0;
    $x<imagesx($i);
    $x++){
        for($y=0;
        $y<imagesy($i);
        $y++){
            $rgb=imagecolorat($i,$x,$y);
            $r=($rgb>>16)&0xFF;
            $g=($rgb>>8)&0xFF;
            $b=$rgb&0xFF;
            $rTotal+=$r;
            $gTotal+=$g;
            $bTotal+=$b;
            $total++;
        }
    }
    $rAverage=round($rTotal/$total);
    $gAverage=round($gTotal/$total);
    $bAverage=round($bTotal/$total);
    $arr=array('ImgUrl'=>$imgurl,'Color'=>"$rAverage,$gAverage,$bAverage");
    echo json_encode($arr);
    exit();
}
//在页面显示图片地址
//echo $imgurl;
header("location:$imgurl");