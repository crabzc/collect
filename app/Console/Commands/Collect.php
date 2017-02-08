<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class Collect extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'collect {fun}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '采集数据';

    /**
     * Constructor 
     */
    public function __construct()
    {
        parent::__construct();
        set_time_limit(0);
        ini_set('memory_limit', '-1'); 
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        try {
            $fun = $this->argument('fun');
            if(!empty($fun)) {
                $this->$fun();
            }
        } catch(Exception $e) {
            echo $e->getMessage()."\n";
        }
    }

    public function test()
    {
        $url = 'http://che.xin.com/14409121.html';
        $html = $this->_curl($url);
        echo $html."\n";exit;
    }

    /**
     * 分析页面
     * @param string $html 页面元素
     * @return array
     */
    private function _format_html($html = '')
    {
        $dom = new \DOMDocument();
        @$dom->loadHTML($html);
        $xPath = new \DOMXPath($dom);
        $elements = $xPath->query('//div[@class=\'uibox\']//img/@src');
        $img = [];
        if (empty($elements)) {
            $this->_save_log('分析页面结构失败');
        }
        foreach ($elements as $v) {
            if ($v->nodeValue) {
                $img[] = $this->_format_pic($v->nodeValue);
            }
        }
        return $img;
    }

    /**
     * Curl 方法
     * @param string $url url地址
     * @return string
     */
    private function _curl($url = '', $is_proxy = false)
    {
        if ($url) {
            //初始化
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/54.0.2840.71 Safari/537.36');
            if($is_proxy) {
                curl_setopt($ch, CURLOPT_PROXY, $this->proxy);
            }
            $output = curl_exec($ch);
            if(curl_errno($ch)) {
                $this->_save_log(curl_error($ch));
            }
            curl_close($ch);
            return $output;
        }
    }
}
