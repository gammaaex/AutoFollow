<?php

//「autoload.php」読み込み
//「twitteroauth/」フォルダは本プログラムと同階層に配置
require_once dirname(__FILE__) . '/twitteroauth/autoload.php';
use Abraham\TwitterOAuth\TwitterOAuth;

// 「Consumer key」値
$consumer_key = '';
// 「Consumer secret」値
$consumer_secret = '';
// 「Access Token」値
$access_token = '';
// 「Access Token Secret」値
$access_token_secret = '';

//リクエストを投げる先（固定値）
$url = "friends/ids";

//対象のscreen_name
$screen_name = '';

//フォローする時間間隔（秒）
$follow_time = 1;

// OAuthオブジェクト生成
$oauth_object = new TwitterOAuth($consumer_key, $consumer_secret, $access_token, $access_token_secret);

//取得
$oauth_response = $oauth_object->get($url, compact('screen_name'));

// レスポンス表示
if (isset($oauth_response->errors[0])) {
    print "error:" . $oauth_response->errors[0]->message . "\n";
} else {
    var_dump($oauth_response);
    $count = 0;
    foreach ($oauth_response->ids as $id) {
        $count++;
        print $count . ":" . $id . "\n";

        //フォローする
        $post_response = $oauth_object->post("friendships/create", array('user_id' => $id, 'follow' => 'false'));

        //オブジェクトを展開
        if (isset($post_response->error) && $post_response->error != '') {
            echo "フォロー出来ませんでした。\n";
            echo "パラメーターの指定を確認して下さい。\n";
            echo "エラーメッセージ:" . $post_response->error . "\n";
        } else {
            echo "フォローしたユーザー\n";
            echo "user_id:" . $post_response->id . "\n";
            echo "name:" . $post_response->name . "\n";
            echo "screen_name:" . $post_response->screen_name . "\n";
            echo "description:" . $post_response->description . "\n";
            echo "\n";
            echo "最新つぶやき\n";
            echo $post_response->status->text . "\n";
            echo $post_response->status->created_at . "\n";
        }
        echo "------------------------------------------------------\n";
        sleep($follow_time);
    }
}

?>
