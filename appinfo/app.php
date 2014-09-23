<?php
/**
*  file mover for owncloud 5
*/

\OCP\App::checkAppEnabled('convert');

OCP\Util::addStyle('convert','style');

OCP\Util::addScript('convert', 'convert');