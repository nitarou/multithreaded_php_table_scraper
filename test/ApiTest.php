<?php 
declare(strict_types=1);

require './vendor/autoload.php';

use PHPUnit\Framework\TestCase;

final class ApiTest extends TestCase
{
    public function get_api($url, $context): array {
        $json = file_get_contents($url, false, stream_context_create($context));

        echo $json;

        return json_decode($json);
    }

    public function testNZDIS(): void
    {
        $url = 'http://localhost:81/scraping.php';

        $context = array(
            'http' => array(
                'method'  => 'POST',
                'header'  => implode("\r\n", array('Content-Type: application/x-www-form-urlencoded',)),
                'content' => http_build_query([
                    'url' => 'https://sciencelatvia.lv/#/pub/eksperti/list',
                    'xpath_of_ajax_btn' => '',
                    'column_numbers_to_scrape' => [2, 3],
                    'titles' => ["vards", "uzvards"],
                    'xpath_of_a' => '',
                    'xpaths_to_scrape_in_a_new_page' => [],
                    'rows' => 5,
                    'text_of_next_btn' => '',
                    'pages' => 1
                ])
            )
        );

        $api_data = $this->get_api($url, $context);

        // title row + the number of rows
        $this->assertSame(count($api_data), 6);
    }
}
