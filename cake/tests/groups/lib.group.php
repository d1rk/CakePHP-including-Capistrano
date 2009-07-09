<?php
/* SVN FILE: $Id: lib.group.php 8123 2009-03-21 23:55:39Z davidpersson $ */
/**
 * LibGroupTest file
 *
 * Long description for file
 *
 * PHP versions 4 and 5
 *
 * CakePHP(tm) Tests <https://trac.cakephp.org/wiki/Developement/TestSuite>
 * Copyright 2005-2008, Cake Software Foundation, Inc. (http://www.cakefoundation.org)
 *
 *  Licensed under The Open Group Test Suite License
 *  Redistributions of files must retain the above copyright notice.
 *
 * @filesource
 * @copyright     Copyright 2005-2008, Cake Software Foundation, Inc. (http://www.cakefoundation.org)
 * @link          https://trac.cakephp.org/wiki/Developement/TestSuite CakePHP(tm) Tests
 * @package       cake
 * @subpackage    cake.tests.groups
 * @since         CakePHP(tm) v 1.2.0.4206
 * @version       $Revision: 8123 $
 * @modifiedby    $LastChangedBy: davidpersson $
 * @lastmodified  $Date: 2009-03-22 00:55:39 +0100 (So, 22. Mär 2009) $
 * @license       http://www.opensource.org/licenses/opengroup.php The Open Group Test Suite License
 */
/**
 * LibGroupTest class
 *
 * This test group will run all test in the cases/libs directory.
 *
 * @package       cake
 * @subpackage    cake.tests.groups
 */
class LibGroupTest extends GroupTest {
/**
 * label property
 *
 * @var string 'All cake/libs/* (Not yet implemented)'
 * @access public
 */
	var $label = 'All Libs';
/**
 * LibGroupTest method
 *
 * @access public
 * @return void
 */
	function LibGroupTest() {
		TestManager::addTestCasesFromDirectory($this, CORE_TEST_CASES . DS . 'libs');
	}
}
?>