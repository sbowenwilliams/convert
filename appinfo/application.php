<?php
/**
 * ownCloud - convert
 *
 * This file is licensed under the Affero General Public License version 3 or
 * later. See the COPYING file.
 *
 * @author Sean Bowen-Williams <sbw@u.northwestern.edu>
 * @copyright Sean Bowen-Williams 2014
 */

namespace OCA\Convert\AppInfo;


use \OCP\AppFramework\App;

use \OCA\Convert\Controller\PageController;


class Application extends App {


	public function __construct (array $urlParams=array()) {
		parent::__construct('convert', $urlParams);

		$container = $this->getContainer();

		/**
		 * Controllers
		 */
		$container->registerService('PageController', function($c) {
			return new PageController(
				$c->query('AppName'), 
				$c->query('Request'),
				$c->query('UserId')
			);
		});


		/**
		 * Core
		 */
		$container->registerService('UserId', function($c) {
			return \OCP\User::getUser();
		});		
		
	}


}