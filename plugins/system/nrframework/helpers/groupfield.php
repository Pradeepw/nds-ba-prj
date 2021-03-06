<?php

/**
 * @author          Tassos Marinos <info@tassos.gr>
 * @link            http://www.tassos.gr
 * @copyright       Copyright © 2015 Tassos Marinos All Rights Reserved
 * @license         GNU GPLv3 <http://www.gnu.org/licenses/gpl.html> or later
 */

defined('_JEXEC') or die;

require_once __DIR__ . '/field.php';

class NRFormGroupField extends NRFormField
{
	public $type          = 'Field';
	public $default_group = 'Categories';

	protected function getInput()
	{
		$this->params = $this->element->attributes();

		return $this->getSelectList();
	}

	public function getGroup()
	{
		$this->params = $this->element->attributes();

		return $this->get('group', $this->default_group ?: $this->type);
	}

	public function getOptions()
	{
		$group = $this->getGroup();

		$id = $this->type . '_' . $group;

		$data[$id] = $this->{'get' . $group}();

		return $data[$id];
	}

	public function getSelectList($group = '')
	{
		if (!is_array($this->value))
		{
			$this->value = explode(',', $this->value);
		}

		$size = (int) $this->get('size', 300);

		$group   = $group ?: $this->getGroup();
		$options = $this->getOptions();

		require_once __DIR__ . '/html.php';

		switch ($group)
		{
			case 'categories':
				return NRHTML::treeselect($options, $this->name, $this->value, $this->id, $size);

			default:
				return NRHTML::treeselectSimple($options, $this->name, $this->value, $this->id, $size);
		}

	}
}
