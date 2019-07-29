<?php

/*
 +------------------------------------------------------------------------+
 | Kirimemail                                                             |
 +------------------------------------------------------------------------+
 | Copyright (c) 2013-2016 Phalcon Team and contributors                  |
 +------------------------------------------------------------------------+
 | This source file is subject to the New BSD License that is bundled     |
 | with this package in the file LICENSE.txt.                             |
 |                                                                        |
 | If you did not receive a copy of the license and are unable to         |
 | obtain it through the world-wide-web, please send an email             |
 | to license@phalconphp.com so we can send you a copy immediately.       |
 +------------------------------------------------------------------------+
*/

namespace Application\Task;

use Application\Console\AbstractTask;

/**
 * Application\Task\Help
 *
 * @package Application\Task
 */
class Help extends AbstractTask {
	/**
	 * @Doc("Getting the application help")
	 */
	public function main() {
		$this->output( sprintf( '%s %s', container( 'app' )->getName(), container( 'app' )->getVersion() ) );
		$this->output( 'Usage: php application [command <arguments>] [--help | -H] [--version | -V] [--list]' );
	}
}
