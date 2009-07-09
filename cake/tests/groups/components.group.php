<?php
/* SVN FILE: $Id: components.group.php 8123 2009-03-21 23:55:39Z davidpersson $ */
/**
 * ComponentsGroupTest file
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
 * ComponentsGroupTest class
 *
 * This test group will run all tests in the cases/libs/controller/components directory.
 *
 * @package       cake
 * @subpackage    cake.tests.groups
 */
class ComponentsGroupTest extends GroupTest {
/**
 * label property
 *
 * @var string 'All core components'
 * @access public
 */
	var $label = 'All Components';
/**
 * CoreComponentsGroupTest method
 *
 * @access public
 * @return void
 */
	function ComponentsGroupTest() {
		TestManager::addTestCasesFromDirectory($this, CORE_TEST_CASES . DS . 'libs' . DS . 'controller' . DS . 'components');
	}
}
?>