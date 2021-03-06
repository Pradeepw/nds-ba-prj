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

class NR_GetResponse extends NR_Wrapper
{

	/**
	 * Create a new instance
	 * @param string $key Your GetResponse API key
	 * @throws \Exception
	 */
	public function __construct($key)
	{
		parent::__construct();
		$this->setKey($key);
		$this->endpoint = 'https://api.getresponse.com/v3';
		$this->options->set('headers.Content-Type', 'application/json');
		$this->options->set('headers.X-Auth-Token', 'api-key ' . $this->key);
		$this->options->set('headers.Accept-Encoding', 'gzip,deflate');
	}

	/**
	 *  Subscribe user to GetResponse Campaign
	 *
	 *  https://apidocs.getresponse.com/v3/resources/contacts#contacts.create
	 *
	 *  TODO: Update existing contact
	 *
	 *  @param   string   $email        	  Email of the Contact
	 *  @param   string   $name    			  The name of the Contact
	 *  @param   object   $campaign  		  Campaign ID
	 *  @param   object   $customFields  	  Collection of custom fields
	 *  @param   object   $update_existing    Update existing contact
	 *
	 *  @return  void
	 */
	public function subscribe($email, $name, $campaign, $customFields, $update_existing = false)
	{
		$data = array(
			"email" 			=> $email,
			"name"				=> $name,
			"campaign" 			=> array("campaignId" => $campaign),
			"customFieldValues"	=> $this->validateCustomFields($customFields),
			"ipAddress" 		=> $_SERVER['REMOTE_ADDR']
		);

		if (empty($name) || is_null($name))
		{
			unset($data["name"]);
		}

		$this->post('contacts', $data);
	}

	/**
	 *  Returns a new array with valid only custom fields
	 *
	 *  @param   array  $customFields   Array of custom fields
	 *
	 *  @return  array  Array of valid only custom fields
	 */
	public function validateCustomFields($customFields)
	{
		$fields = array();
	
		if (!is_array($customFields))
		{
			return $fields;
		}

		$accountCustomFields = $this->get("custom-fields");

		if (!$this->request_successful)
		{
			return $fields;
		}

		foreach ($accountCustomFields as $key => $customField)
		{
			if (!isset($customFields[$customField["name"]]))
			{
				continue;
			}
				
			$fields[] = array(
				"customFieldId" => $customField["customFieldId"],
				"value"			=> array($customFields[$customField["name"]])
			);
		}

		return $fields;
	}

	/**
	 * Get the last error returned by either the network transport, or by the API.
	 * If something didn't work, this should contain the string describing the problem.
	 * 
	 * @return  string  describing the error
	 */
	public function getLastError()
	{
		$body = $this->last_response['body'];
		
		if (!isset($body["context"]) || !isset($body["context"][0]))
		{
			return $body["codeDescription"] . " - " . $body["message"];
		}

		$error = $body["context"][0];
		$errorFieldName = is_array($error["fieldName"]) ? implode(" ", $error["fieldName"]) : $error["fieldName"];
		
		return $errorFieldName . ": " . $error["errorDescription"];
	}
}