<?php
/**
 * Lithium Boilerplate: the most rad php framework (boilerplate)
 *
 * @copyright     Copyright 2012, Darkroast.net
 * @license       http://opensource.org/licenses/bsd-license.php The BSD License
 */

namespace app\controllers;
use app\models\Users;

class HomeController extends \lithium\action\Controller {

    /**
    * View Action
    */
	public function view() {
    
        (Users::$ApplicationUser);
		return;
	}
}

?>