<?php
/* SVN FILE: $Id: no_database.group.php 8123 2009-03-21 23:55:39Z davidpersson $ */
/**
 * NoDatabaseGroupTest file
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
 * NoDatabaseGroupTest class
 *
 * This test group will run all test in the cases/libs directory.
 *
 * @package       cake
 * @subpackage    cake.tests.groups
 */
class NoDatabaseGroupTest extends GroupTest {
/**
 * label property
 *
 * @var string 'All tests without a database connection'
 * @access public
 */
	var $label = 'All Libs not requiring a database connection';
/**
 * NoDatabaseGroupTest method
 *
 * @access public
 * @return void
 */
	function NoDatabaseGroupTest() {
		TestManager::addTestFile($this, CORE_TEST_CASES . DS . 'dispatcher');
		TestManager::addTestFile($this, CORE_TEST_CASES . DS . 'libs' . DS . 'router');
		TestManager::addTestFile($this, CORE_TEST_CASES . DS . 'libs' . DS . 'inflector');
		TestManager::addTestFile($this, CORE_TEST_CASES . DS . 'libs' . DS . 'validation');
		TestManager::addTestFile($this, CORE_TEST_CASES . DS . 'libs' . DS . 'session');
		TestManager::addTestFile($this, CORE_TEST_CASES . DS . 'libs' . DS . 'socket');
		TestManager::addTestCasesFromDirectory($this, CORE_TEST_CASES . DS . 'libs' . DS . 'view');
	}
}
?>