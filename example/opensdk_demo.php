<?php

// 使用Composer则不用手动引入自动器
require dirname(__DIR__) . '/src/Loader.php';
diggmind\Loader::register();

$channelCode = '下发的渠道标识';
$appKey = '下发的APP_KEY';
$appSecret = '下发的APP_SECRET';

// 初始化SDK
$sdk = new diggmind\opensdk\Client($appKey, $appSecret);

// 设定SDK访问环境、develop测试服务器，prod正式服务器
$sdk->setEnv('develop');

// 获取TOKEN
$result = $sdk->getAccessToken();

// 判定请求状态
if ($result->getStatus()) {
    $resData = $result->getData();

    // 保存到本地的缓存系统，自行决定哪一种缓存策略
    $token = $resData['access_token'];
    $expire = $resData['expire_in'];

    // 写入访问秘钥
    $sdk->accessToken = $token;
}

print "ACCESS_TOKEN: " . PHP_EOL;
print $sdk->accessToken . PHP_EOL;
print PHP_EOL;

# 获取测试列表
print "TEST_ID: " . PHP_EOL;
$result2 = $sdk->getTestList(0)->getData();
foreach ($result2 as $v) {
    print $v['id'] . ':' . $v['title'] . PHP_EOL;
}
print PHP_EOL;

# 获取测试兑换码
print "IN_CODE: " . PHP_EOL;
$no = time();
$testId = count($result2) ? $result2[0]['id'] : 0;
$result3 = $sdk->getTestCode($no, $testId)->getData();
print $result3['code'];
print PHP_EOL;

# 更改支付状态
print "CALLBACK: " . PHP_EOL;
$result4 = $sdk->postNotifyPayCb($result3['code']);
if ($result4->getStatus()) {
    print json_encode($result4->getMessage(), 256);
    print "SUCCESS" . PHP_EOL;
} else {
    print "FAIL" . PHP_EOL;
}
print PHP_EOL;

# 查询in_code状态
print "QUERY_STATUS:" . PHP_EOL;
$result5 = $sdk->getTestCodeStatus($result3['code'])->getData();
print json_encode($result5) . PHP_EOL;
print PHP_EOL;

# 查询报告页面
//print "REPORT_URL:" . PHP_EOL;
$result6 = $sdk->getTestReport($testId, $result3['code']);
print json_encode($result6) . PHP_EOL;

# 测试报告页面（可嵌入iframe/webview）
print "REPORT";
$yourSystemEnv = 'prod';
if ($yourSystemEnv == 'prod') {
    print "http://wx.diggmind.com/channel/entry?channel_code=$channelCode&test_id=$testId&in_code=" . $result3['code'];
} else {
    print "http://wxdev.diggmind.com/channel/entry?channel_code=$channelCode&test_id=$testId&in_code=" . $result3['code'];
}