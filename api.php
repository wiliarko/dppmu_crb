<?php
class Xendit {
	private $_api_key;
	private $_api_token;
	private $_conn_mysql;
	private $_uri;
	private $_key = 'sebatasimpian';
	private $_branch_url = '';

	function __construct()
	{
		// Sikelopes
		// $this->_api_key = 'xnd_development_4VhFGTTxletpwYXVHBy92wyHPOSkctK0BNufhIBd0BiURfXl1A4xRfKsQlB3IdU';
		// $this->_api_token = '8cd95877b57636e8f11b3d33ede93bb65026508eac47e0f3afadb89d5bf225d2';
		// $this->_branch_url = 'http://localhost/dppmuxxxxxx/backend/transaksi/receive_xendit_notify/';

		// Wili
		$this->_api_key = 'xnd_development_l2xtjb4tWje7ya2yg49RPzSTpmPAGDZweQkFT7ZhyDFvXQDugXnm5tTVcuwiDzg';
		$this->_api_token = '01f61738824ce31fd9c8976e7f6981028dec3706d058b9c0763ed8e3f7bd5301';
		
		// Production
		// $this->_api_key = 'xnd_production_ZtkIwBgOVIozOxipAA52avZgJxPy48CVla5xEacRSBYf6V4NF0n2n6W4TOsRhUOK';
		// $this->_api_token = 'cf5f339451e57445daa79285fe9cc80827fb528809bc2ca6638bd36964a99cbd';
		$this->_branch_url = 'http://sewa-beli.net/dppmu_xxxxxx/backend/transaksi/receive_xendit_notify/';

		$this->_init_mysql();
		$this->_remap();
	}

	public function send_payment()
	{
		$data = $this->_validate_api();
		$exec = $this->_conn_mysql->prepare("SELECT * FROM xendit_customer WHERE id = ?");

		$exec->execute(array($data->payment_code));

		$rows = $exec->fetchAll(PDO::FETCH_OBJ);
		$exec = NULL;
		$break = FALSE;

		if (count($rows) > 0)
		{
			$rows = $rows[0];

			if ($rows->cust_code != $data->cust_code)
			{
				echo json_encode(array('error_code' => 409, 'message' => 'ID Nasabah sudah digunakan.'));

				$break = TRUE;
			}
		}
		else
		{
			$exec = $this->_conn_mysql->prepare("INSERT INTO xendit_customer (id, cust_code, cust_branch, created_datetime) VALUES (?, ?, ?, NOW())");

			$exec->execute(array($data->payment_code, $data->cust_code, $data->cust_branch));

			$exec = NULL;
		}

		if ($break === FALSE)
		{
		    $params = array(
				'data' => array(
					'external_id' => $data->external_id,
					'retail_outlet_name' => $data->retail_outlet_name,
					'payment_code' => $data->payment_code,
					'name' => $data->name,
					'expected_amount' => $data->expected_amount,
					'is_single_use' => FALSE
				),
				'method' => 'POST',
				'url' => 'https://api.xendit.co/fixed_payment_code/',
				'key' => $this->_api_key
			);

			$return = $this->_do_curl($params);
			$result = json_decode($return);

			if ( ! isset($result->error_code))
	    	{
	    		$exec = $this->_conn_mysql->prepare("INSERT INTO xendit_api (xendit_customer_id, status, prefix, payment_code, created_datetime) VALUES (?, ?, ?, ?, NOW())");

				$exec->execute(array($data->payment_code, $result->status, $result->prefix, $result->payment_code));

				$exec = NULL;
	    	}

	    	echo $return;
		}
    }

    public function receive_notify()
    {
		$data = $this->_validate_api();
		$cust_id = str_replace($data->prefix, '', $data->payment_code);
		$exec = $this->_conn_mysql->prepare("SELECT * FROM xendit_api WHERE (xendit_customer_id = ? AND status = 'ACTIVE') OR (xendit_customer_id = ? AND fixed_payment_code_payment_id = ? AND fixed_payment_code_id = ?) ORDER BY created_datetime DESC");

		$exec->execute(array($cust_id, $cust_id, $data->fixed_payment_code_payment_id, $data->fixed_payment_code_id));

		$rows = $exec->fetchAll(PDO::FETCH_OBJ);
		$exec = NULL;

		if (count($rows) > 0)
		{
			$rows = $rows[0];
			$exec = $this->_conn_mysql->prepare("UPDATE xendit_api SET status = ?, fixed_payment_code_payment_id = ?, fixed_payment_code_id = ? WHERE id = ?");

			$exec->execute(array($data->status, $data->fixed_payment_code_payment_id, $data->fixed_payment_code_id, $rows->id));
		}
		else
		{
			$exec = $this->_conn_mysql->prepare("INSERT INTO xendit_api (xendit_customer_id, status, prefix, payment_code, fixed_payment_code_payment_id, fixed_payment_code_id, created_datetime) VALUES (?, ?, ?, ?, ?, ?, NOW())");

			$exec->execute(array($cust_id, $data->status, $data->prefix, $data->payment_code, $data->fixed_payment_code_payment_id, $data->fixed_payment_code_id));
		}

		$exec = $this->_conn_mysql->prepare("SELECT * FROM xendit_customer WHERE id = ?");

		$exec->execute(array($cust_id));

		$rows = $exec->fetchAll(PDO::FETCH_OBJ);
		$exec = NULL;

		$rows = $rows[0];
		$ar_data = (array) $data;
		$branch_url = str_replace('xxxxxx', strtolower($rows->cust_branch), $this->_branch_url);
		$ar_data['key'] = $this->_key;
		$params = array(
			'data' => $ar_data,
			'method' => 'POST',
			'url' => $branch_url
		);

		$this->_do_curl($params);
    }

    private function _validate_api()
    {
    	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		    $json = file_get_contents('php://input');
		    $data = json_decode($json);
		    $headers = getallheaders();

		    if (isset($data->key) && $data->key == $this->_key)
		    {
			    return $data;
		    }
		    elseif (isset($headers['X-Callback-Token']) && $headers['X-Callback-Token'] == $this->_api_token)
		    {
		    	return $data;
		    }
		    else
		    {
			    exit('You can\'t access this page!');
			}
		}
		else
		{
		    exit('You can\'t access this page!');
		}
    }

	private function _remap()
	{
		$exp_uri = explode('/', rtrim($_SERVER['REQUEST_URI'], '/'));

		$this->_uri = end($exp_uri);

		if (method_exists($this, $this->_uri))
		{
			call_user_func(array($this, $this->_uri));
		}
		else
		{
			$this->_send_response_code(404);
		}
	}

	private function _send_response_code($code)
	{
		switch ($code) {
			case 100:
				$text = 'Continue';
			break;
			case 101:
				$text = 'Switching Protocols';
			break;
			case 200:
				$text = 'OK';
			break;
			case 201:
				$text = 'Created';
			break;
			case 202:
				$text = 'Accepted';
			break;
			case 203:
				$text = 'Non-Authoritative Information';
			break;
			case 204:
				$text = 'No Content';
			break;
			case 205:
				$text = 'Reset Content';
			break;
			case 206:
				$text = 'Partial Content';
			break;
			case 300:
				$text = 'Multiple Choices';
			break;
			case 301:
				$text = 'Moved Permanently';
			break;
			case 302:
				$text = 'Moved Temporarily';
			break;
			case 303:
				$text = 'See Other';
			break;
			case 304:
				$text = 'Not Modified';
			break;
			case 305:
				$text = 'Use Proxy';
			break;
			case 400:
				$text = 'Bad Request';
			break;
			case 401:
				$text = 'Unauthorized';
			break;
			case 402:
				$text = 'Payment Required';
			break;
			case 403:
				$text = 'Forbidden';
			break;
			case 404:
				$text = 'Not Found';
			break;
			case 405:
				$text = 'Method Not Allowed';
			break;
			case 406:
				$text = 'Not Acceptable';
			break;
			case 407:
				$text = 'Proxy Authentication Required';
			break;
			case 408:
				$text = 'Request Time-out';
			break;
			case 409:
				$text = 'Conflict';
			break;
			case 410:
				$text = 'Gone';
			break;
			case 411:
				$text = 'Length Required';
			break;
			case 412:
				$text = 'Precondition Failed';
			break;
			case 413:
				$text = 'Request Entity Too Large';
			break;
			case 414:
				$text = 'Request-URI Too Large';
			break;
			case 415:
				$text = 'Unsupported Media Type';
			break;
			case 500:
				$text = 'Internal Server Error';
			break;
			case 501:
				$text = 'Not Implemented';
			break;
			case 502:
				$text = 'Bad Gateway';
			break;
			case 503:
				$text = 'Service Unavailable';
			break;
			case 504:
				$text = 'Gateway Time-out';
			break;
			case 505:
				$text = 'HTTP Version not supported';
			break;
			default:
				exit('Unknown http status code "' . htmlentities($code) . '"');
			break;
		}

		$protocol = (isset($_SERVER['SERVER_PROTOCOL']) ? $_SERVER['SERVER_PROTOCOL'] : 'HTTP/1.0');

		header($protocol.' '.$code.' '.$text);
		echo $text;
		exit();
	}

    private function _init_mysql()
	{
		try
		{
			$this->_conn_mysql = new PDO('mysql:host=localhost;dbname=dppmu_crb', 'root', 'password');

			$this->_conn_mysql->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		}
		catch (PDOException $e)
		{
			echo $e->getMessage();
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

		if (isset($params['key']))
		{
			curl_setopt($curl, CURLOPT_USERPWD, $params['key'].':');
		}

		$response = curl_exec($curl);

		curl_close($curl);

		return $response;
	}
}

$xendit = new Xendit();
?>