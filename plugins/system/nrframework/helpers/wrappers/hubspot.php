<?php

/**
 * @package         Engage Box
 * @version         3.2.0 Pro
 *
 * @author          Tassos Marinos <info@tassos.gr>
 * @link            http://www.tassos.gr
 * @copyright       Copyright © 2016 Tassos Marinos All Rights Reserved
 * @license         GNU GPLv3 <http://www.gnu.org/licenses/gpl.html> or later
 */

// No direct access
defined('_JEXEC') or die;

require_once __DIR__ . '/wrapper.php';

class NR_HubSpot extends NR_Wrapper
{
	/**
	 * Create a new instance
	 * @param string $key Your HubSpot API key
	 * @throws \Exception
	 */
	public function __construct($key)
	{
		parent::__construct();
		$this->setKey($key);
		$this->setEndpoint('https://api.hubapi.com/contacts/v1');
		$this->options->set('headers.Content-Type', 'application/x-www-form-urlencoded; charset=utf-8');
		$this->options->set('headers.Authorization', 'Bearer ' . $this->key);
	}
	/**
	 * Encode the data and attach it to the request
	 * @param   array $data Assoc array of data to attach
	 */
	protected function attachRequestPayload($data)
	{
		$this->last_request['body'] = http_build_query($data);
	}

}
