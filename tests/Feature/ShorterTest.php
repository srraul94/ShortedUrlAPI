<?php

use Tests\TestCase;

class ShorterTest extends TestCase
{

    public function testUriACK()
    {
        $response = $this->postJson("/api/v1/short-urls", [
            'url' => 'https://example.com/12345'
        ])->withHeaders([ 'Authorization' => 'Bearer []', 'Accept' => 'application/json' ]);

        $this->assertEquals(200, $response->getStatusCode());
    }


    /**
     * @param string $token
     * @param int    $expectedStatusCode
     *
     * @dataProvider tokensProvider
     */
    public function testValidToken(
        string $token,
        int $expectedStatusCode
    ) {
        $response = $this->postJson("/api/v1/short-urls", [
            'url' => 'https://example.com/12345'
        ])->withHeaders([ 'Authorization' => 'Bearer ' . $token, 'Accept' => 'application/json' ]);

        $this->assertEquals($expectedStatusCode, $response->getStatusCode());
    }


    public function tokensProvider(): array
    {
        return [
            [ '{}', 200 ],
            [ '{}[]()', 200 ],
            [ '{{([])}}', 200 ],
            [ '{}', 200 ],
            [ '{)', 401 ],
            [ '[{]}', 401 ],
            [ '(((((((()', 401 ],
            [ '', 401 ],
            [ '1', 401 ],
            [ '0', 401 ],
            [ 'null', 401 ],
            [ 'aaaa', 401 ],
            [ '{a}', 401 ],
            [ '{}a', 401 ],
            [ 'a{}', 401 ],
            [ '{0}', 401 ],
            [ '{}0', 401 ],
            [ '0{}', 401 ],
        ];
    }


    /**
     * @param string $url
     * @param int    $expectedStatusCode
     *
     * @dataProvider urlsProvider
     */
    public function testValidURL(
        string $url,
        int $expectedStatusCode
    ) {
        $response = $this->postJson("/api/v1/short-urls", [
            'url' => $url
        ])->withHeaders([ 'Authorization' => 'Bearer []', 'Accept' => 'application/json' ]);

        $this->assertEquals($expectedStatusCode, $response->getStatusCode());
    }


    public function urlsProvider(): array
    {
        return [
            [ 'https://www.google.com/search?q=hola', 200 ],
            [ 'http://www.google.com/search?q=hola', 200 ],
            [ '12345', 404 ],
            [ '/12345', 404 ],
            [ '', 404 ],
        ];
    }

}
