<?php if( ! defined('BASEPATH')) exit('No direct script access allowed');

class Xendit {

	public $_api_url;
	public $_api_key;
	public $_method;
	public $_external_id;
	public $_payment_code;
	public $_retail_outlet;
	public $_name;
	public $_expected_amount;
	public $_cust_code;
	private $_cust_branch = 'CRB';
	private $_key = 'sebatasimpian';

	public function create_payment_code()
	{
		$params = array(
			'data' => array(
				'external_id' => $this->_external_id,
				'retail_outlet_name' => $this->_retail_outlet,
				'payment_code' => $this->_payment_code,
				'name' => $this->_name,
				'expected_amount' => $this->_expected_amount,
				'cust_code' => $this->_cust_code,
				'cust_branch' => $this->_cust_branch,
				'is_single_use' => FALSE,
				'key' => $this->_key
			),
			'method' => 'POST',
			// 'url' => 'http://sewa-beli.net/api_xendit/send_payment/'
			'url' => 'http://testerxendid.sewa-beli.net/api_xendit/send_payment'
		);

		return $this->_do_curl($params);
	}

	public function update_payment_code()
	{
		$params = array(
			'data' => array(
				'expiration_date' => date("c")
			),
			'method' => $this->_method,
			'url' => $this->_api_url,
			'key' => $this->_api_key
		);
	}

    public function validate_notify()
    {
    	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		    $json = file_get_contents('php://input');
		    $data = json_decode($json);

		    if (isset($data->key) && $data->key == $this->_key)
		    {
			    return $data;
		    }
		    else
		    {
			    return FALSE;
			}
		}
		else
		{
		    return FALSE;
		}
    }

	private function _do_curl($params)
	{
		$curl = curl_init();

		curl_setopt($curl, CURLOPT_POST, TRUE);
		curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($params['data']));
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($curl, CURLOPT_TIMEOUT, 30);
		curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $params['method']);
		curl_setopt($curl, CURLOPT_URL, $params['url']);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));

		$response = curl_exec($curl);

		curl_close($curl);

		return json_decode($response, TRUE);
	}
}