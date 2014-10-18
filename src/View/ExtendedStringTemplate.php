<?php
/**
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) iTeam s.r.o. (http://iteam-pro.com)
 * @link          http://iteam-pro.com SbAdmin2 CakePHP 3 Theme
 * @since         0.0.1
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */
namespace Garderobe\View;

use Cake\View\StringTemplate;

/**
 * Provides an interface for registering and inserting
 * content into simple logic-less string templates.
 *
 * Used by several helpers to provide simple flexible templates
 * for generating HTML and other content.
 */
class ExtendedStringTemplate extends StringTemplate {

	protected $_defaultPlaceholders = [ 'attrs', 'text',
		'name', 'value','input', 'label',
		'year', 'month', 'day', 'hour', 'minute', 'second', 'meridian',
		'content', 'type', 'required', 'error'
	];

/*
 * Format a template string with $data
 *
 * @param string $name The template name.
 * @param array $data The data to insert.
 * @return string
 */


	public function format($name, array $data) {
		if (!isset($this->_compiled[$name])) {
			return '';
		}
		list($template, $placeholders) = $this->_compiled[$name];
		if ($template === null) {
			return '';
		}

		foreach ($placeholders as $placeholder) {
			if(isset($data['attrs'])&&!in_array($placeholder, $this->_defaultPlaceholders)){
				if(preg_match('#'.$placeholder.'="([^"]*)"#', $data['attrs'], $matches)){
					$data[$placeholder] = $this->format($placeholder, ['content' => html_entity_decode($matches[1])]);
					if(empty($data[$placeholder])) $data[$placeholder] = html_entity_decode($matches[1]);
					$data['attrs'] = preg_replace('#'.$placeholder.'="([^"]*)"#', '', $data['attrs']);
				}
			}
		}

		return parent::format($name, $data);
	}

}