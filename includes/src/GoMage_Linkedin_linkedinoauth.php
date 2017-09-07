<?php
/**
 * GoMage Social Connector Extension
 *
 * @category     Extension
 * @copyright    Copyright (c) 2013 GoMage (http://www.gomage.com)
 * @author       GoMage
 * @license      http://www.gomage.com/license-agreement/  Single domain license
 * @terms of use http://www.gomage.com/terms-of-use
 * @version      Release: 1.0
 * @since        Class available since Release 1.0
 */

require_once ('OAuth.php');

class LinkedInOAuth {
	
	public $http_code;
	
	public $url;
	
	public $host = "https://api.linkedin.com/";
	
	public $timeout = 30;
	
	public $connecttimeout = 30;
	
	public $ssl_verifypeer = FALSE;
	
	public $format = 'json';
	
	public $decode_json = TRUE;
	
	public $http_info;
	
	public $useragent = 'LinkedIn OAuth';
	
	function accessTokenURL() {
		return 'https://api.linkedin.com/uas/oauth/accessToken';
	}
	function authenticateURL() {
		return 'https://api.linkedin.com/uas/oauth/authorize';
	}
	function requestTokenURL() {
		return 'https://api.linkedin.com/uas/oauth/requestToken';
	}
	
	function __construct($consumer_key, $consumer_secret, $oauth_token = NULL, $oauth_token_secret = NULL) {
		$this->sha1_method = new OAuthSignatureMethod_HMAC_SHA1();
		$this->consumer = new OAuthConsumer($consumer_key, $consumer_secret);
		if (! empty($oauth_token) && ! empty($oauth_token_secret)) {
			$this->token = new OAuthConsumer($oauth_token, $oauth_token_secret);
		}
		else {
			$this->token = NULL;
		}
	}
	
	function getRequestToken($oauth_callback = NULL) {
		$parameters = array();
		if (! empty($oauth_callback)) {
			$parameters['oauth_callback'] = $oauth_callback;
		}
		$parameters['scope'] = 'r_emailaddress';
		$request = $this->oAuthRequest($this->requestTokenURL(), 'GET', $parameters);
		$token = OAuthUtil::parse_parameters($request);
		$this->token = new OAuthConsumer($token['oauth_token'], $token['oauth_token_secret']);
		return $token;
	}
	
	function getAuthorizeURL($token) {
		if (is_array($token)) {
			$token = $token['oauth_token'];
		}
		return $this->authenticateURL() . "?oauth_token={$token}";
	}
	
	function getAccessToken($oauth_verifier = FALSE) {
		$parameters = array();
		if (! empty($oauth_verifier)) {
			$parameters['oauth_verifier'] = $oauth_verifier;
		}
		$request = $this->oAuthRequest($this->accessTokenURL(), 'GET', $parameters);
		$token = OAuthUtil::parse_parameters($request);
		$this->token = new OAuthConsumer($token['oauth_token'], $token['oauth_token_secret']);
		return $token;
	}
	
	function get($url, $parameters = array()) {
		$response = $this->oAuthRequest($url, 'GET', $parameters);
		if ($this->format === 'json' && $this->decode_json) {
			return json_decode($response);
		}
		return $response;
	}
	
	function post($url, $parameters = array()) {
		$response = $this->oAuthRequest($url, 'POST', $parameters);
		if ($this->format === 'json' && $this->decode_json) {
			return json_decode($response);
		}
		return $response;
	}
	
	function delete($url, $parameters = array()) {
		$response = $this->oAuthRequest($url, 'DELETE', $parameters);
		if ($this->format === 'json' && $this->decode_json) {
			return json_decode($response);
		}
		return $response;
	}
	
	function oAuthRequest($url, $method, $parameters) {
		if (strrpos($url, 'https://') !== 0 && strrpos($url, 'http://') !== 0) {
			$url = "{$this->host}{$url}";
		}
		$request = OAuthRequest::from_consumer_and_token($this->consumer, $this->token, $method, $url, $parameters);
		$request->sign_request($this->sha1_method, $this->consumer, $this->token);
		switch ($method) {
			case 'GET':
				return $this->http($request->to_url(), 'GET');
			default:
				return $this->http($request->get_normalized_http_url(), $method, $request->to_postdata());
		}
	}
	
	function http($url, $method, $postfields = NULL) {
		$this->http_info = array();
		$ci = curl_init();
		curl_setopt($ci, CURLOPT_USERAGENT, $this->useragent);
		curl_setopt($ci, CURLOPT_CONNECTTIMEOUT, $this->connecttimeout);
		curl_setopt($ci, CURLOPT_TIMEOUT, $this->timeout);
		curl_setopt($ci, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ci, CURLOPT_HTTPHEADER, array('Expect:'));
		curl_setopt($ci, CURLOPT_SSL_VERIFYPEER, $this->ssl_verifypeer);
		curl_setopt($ci, CURLOPT_HEADERFUNCTION, array($this, 'getHeader'));
		curl_setopt($ci, CURLOPT_HEADER, FALSE);
		
		switch ($method) {
			case 'POST':
				curl_setopt($ci, CURLOPT_POST, TRUE);
				if (! empty($postfields)) {
					curl_setopt($ci, CURLOPT_POSTFIELDS, $postfields);
				}
			break;
			case 'DELETE':
				curl_setopt($ci, CURLOPT_CUSTOMREQUEST, 'DELETE');
				if (! empty($postfields)) {
					$url = "{$url}?{$postfields}";
				}
		}
		
		curl_setopt($ci, CURLOPT_URL, $url);
		$response = curl_exec($ci);
		$this->http_code = curl_getinfo($ci, CURLINFO_HTTP_CODE);
		$this->http_info = array_merge($this->http_info, curl_getinfo($ci));
		$this->url = $url;
		curl_close($ci);
		return $response;
	}
	
	function getHeader($ch, $header) {
		$i = strpos($header, ':');
		if (! empty($i)) {
			$key = str_replace('-', '_', strtolower(substr($header, 0, $i)));
			$value = trim(substr($header, $i + 2));
			$this->http_header[$key] = $value;
		}
		return strlen($header);
	}

}
