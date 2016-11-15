<?php

namespace Acme;

class Insta {

    const API_URL = 'https://api.instagram.com/v1/';
    const API_OAUTH_URL = 'https://api.instagram.com/oauth/authorize';
    const API_OAUTH_TOKEN_URL = 'https://api.instagram.com/oauth/access_token';

    private $apiKey;
    private $apiSecret;
    private $apiCallback;
    private $_accesstoken;

    public function __construct(array $instagram_config) {
        $this->apiKey = $instagram_config['api_key'];
        $this->apiSecret = $instagram_config['api_secret'];
        $this->apiCallback = $instagram_config['api_redirect'];
    }

    public function authorize() {
        return self::API_OAUTH_URL . '?client_id=' . $this->apiKey . '&redirect_uri=' . urlencode($this->apiCallback) . '&response_type=code&scope=public_content';
    }

    public function setAccessToken($data) {
        $token = is_object($data) ? $data->access_token : $data;

        $this->_accesstoken = $token;
    }

    public function getOAuthToken($code, $token = false) {
        $apiData = array(
            'grant_type' => 'authorization_code',
            'client_id' => $this->apiKey,
            'client_secret' => $this->apiSecret,
            'redirect_uri' => $this->apiCallback,
            'code' => $code
        );

        $result = $this->_makeOAuthCall($apiData);

        return !$token ? $result : $result->access_token;
    }

    private function _makeOAuthCall($apiData) {
        $apiHost = self::API_OAUTH_TOKEN_URL;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $apiHost);
        curl_setopt($ch, CURLOPT_POST, count($apiData));
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($apiData));
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Accept: application/json'));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_TIMEOUT, 90);
        $jsonData = curl_exec($ch);

        if (!$jsonData) {
            throw new InstagramException('Error: _makeOAuthCall() - cURL error: ' . curl_error($ch));
        }

        curl_close($ch);

        return json_decode($jsonData);
    }

    public function getUserMedia($id = 'self', $limit = 0) {
        $params = array();

        if ($limit > 0) {
            $params['count'] = $limit;
        }

        return $this->_makeCall('users/' . $id . '/media/recent', strlen($this->getAccessToken()), $params);
    }

}
