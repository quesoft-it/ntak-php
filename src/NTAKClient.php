<?php

namespace Natsu007\Ntak;

use Carbon\Carbon;
use Gamegos\JWS\JWS;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\RequestException;
use Natsu007\Ntak\Exceptions\NTAKClientException;

class NTAKClient
{
    protected      $taxNumber;
    protected      $regNumber;
    protected      $softwareRegNumber;
    protected      $version;
    protected      $certPath;
    protected      $testing;

    protected      $client;
    protected      $when;
    protected      $url;
    protected      $lastRequest;
    protected      $lastResponse;
    protected      $lastRequestTime; // milliseconds
    protected      $fakeResponse = null;

    protected static $prodUrl = 'https://rms.ntaktst.hu';
    protected static $testUrl = 'https://rms.tesztntak.hu';

    /**
     * __construct
     *
     * @param  string $taxNumber
     * @param  string $regNumber
     * @param  string $softwareRegNumber
     * @param  string $version
     * @param  string $certPath
     * @param  bool   $testing
     * @return void
     */
    public function __construct(
        string $taxNumber,
        string $regNumber,
        string $softwareRegNumber,
        string $version,
        string $certPath,
        bool   $testing = false
    ) {
        $this->taxNumber         = $taxNumber;
        $this->regNumber         = $regNumber;
        $this->softwareRegNumber = $softwareRegNumber;
        $this->version           = $version;
        $this->certPath          = $certPath;
        $this->testing           = $testing;

        $this->url = $testing
            ? self::$testUrl
            : self::$prodUrl;

        $this->client = new Client([
            'base_uri' => $this->url,
            'cert'     => $certPath,
            'ssl_key'  => $certPath,
            'verify'   => false,
        ]);
    }

    /**
     * fakeResponse
     *
     * @return NTAKClient
     */
    public function fakeResponse(array $response): NTAKClient
    {
        $this->fakeResponse = $response;
        return $this;
    }

    /**
     * message
     *
     * @param  array  $message
     * @param  Carbon $when
     * @param  string $uri
     * @return array
     */
    public function message(array $message, Carbon $when, string $uri): array
    {
        $this->when = $when;

        $json = array_merge(
            $this->header(),
            $message
        );

        $headers = $this->requestHeaders($json);

        // Send request with guzzle
        try {
            $this->lastRequest = $json;

            $start = Carbon::now();

            $response = $this->fakeResponse
                ?: $this->client->request(
                    'post',
                    $uri,
                    compact('json', 'headers')
                );

            $this->lastRequestTime = Carbon::now()->diffInMilliseconds($start);
        } catch (RequestException $e) {
            throw new NTAKClientException(
                $e->getResponse()->getBody()->getContents()
            );
        } catch (ClientException $e) {
            throw new NTAKClientException(
                $e->getMessage()
            );
        }

        return $this->lastResponse = is_array($response)
            ? $response
            : json_decode($response->getBody(), true) ?? [];
    }

    /**
     * prodUrl
     *
     * @return string
     */
    public static function prodUrl(): string
    {
        return self::$prodUrl;
    }

    /**
     * testUrl
     *
     * @return string
     */
    public static function testUrl(): string
    {
        return self::$testUrl;
    }

    /**
     * requestHeaders
     *
     * @param  array $message
     * @return array
     */
    protected function requestHeaders(array $message): array
    {
        $jws = (new JWS())->encode(
            [
                'alg' => 'RS256',
                'typ' => 'JWS'
            ],
            $message,
            openssl_pkey_get_private(file_get_contents($this->certPath))
        );

        $tmp = explode('.', $jws);

        openssl_x509_export(file_get_contents($this->certPath), $tmpCert);

        return [
            'x-jws-signature' => $tmp[0].'..'.$tmp[2],
            'x-certificate'   => base64_encode($tmpCert)
        ];
    }

    /**
     * header
     *
     * @return array
     */
    protected function header(): array
    {
        return [
            'szolgaltatoAdatok'   => [
                'adoszam'                => $this->taxNumber,
                'vendeglatoUzletRegSzam' => $this->regNumber,
            ],
            'uzenetAdatok'        => [
                'uzenetKuldesIdeje' => $this->when->timezone('Europe/Budapest')->toIso8601String(),
            ],
            'kuldoRendszerAdatok' => [
                'rmsRendszerNTAKAzonosito' => $this->softwareRegNumber,
                'rmsRendszerVerzioszam'    => $this->version,
            ]
        ];
    }

    /**
     * lastRequest getter
     *
     * @return array|null
     */
    public function lastRequest(): ?array
    {
        return $this->lastRequest;
    }

    /**
     * lastResponse getter
     *
     * @return array
     */
    public function lastResponse(): ?array
    {
        return $this->lastResponse;
    }

    /**
     * lastRequestTime getter (ms)
     *
     * @return int
     */
    public function lastRequestTime(): ?int
    {
        return $this->lastRequestTime;
    }
}
