#api_php_sdk

## Composer方式安装（配置私有仓库）

```
"repositories": [
    {
        "url": "https://github.com/DiggMind/api_php_sdk.git",
        "type": "git"
    }
],
"require": {
    "diggmind/opensdk": "master"
}
```

## 手动引入安装

拷贝SDK到项目任意目录，引入自动器

```
require_once __YOUR_PROJECT___ . 'SDK_PATH/src/Loader.php';

// 自动加载SDK中目录
diggmind\\Loader::register();

$appKey = '';
$appSecret = '';
$serverUrl = '';
$accessToken = '';

$client = new diggmind\\opensdk\\Client($appKey, $appSecret, $serverUrl, $accessToken);

```