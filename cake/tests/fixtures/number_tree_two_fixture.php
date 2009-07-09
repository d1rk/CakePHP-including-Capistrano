<?php
/* SVN FILE: $Id: number_tree_two_fixture.php 8178 2009-05-26 17:17:57Z mark_story $ */
/**
 * Tree behavior class.
 *
 * Enables a model object to act as a node-based tree.
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
 * @subpackage    cake.tests.fixtures
 * @since         CakePHP(tm) v 1.2.0.5331
 * @version       $Revision: 8178 $
 * @modifiedby    $LastChangedBy: mark_story $
 * @lastmodified  $Date: 2009-05-26 19:17:57 +0200 (Di, 26. Mai 2009) $
 * @license       http://www.opensource.org/licenses/opengroup.php The Open Group Test Suite License
 */
/**
 * Number Tree Test Fixture
 *
 * Generates a tree of data for use testing the tree behavior
 *
 * @package       cake
 * @subpackage    cake.tests.fixtures
 */
class NumberTreeTwoFixture extends CakeTestFixture {
/**
 * name property
 *
 * @var string 'NumberTree'
 * @access public
 */
	var $name = 'NumberTreeTwo';
/**
 * fields property
 *
 * @var array
 * @access public
 */
	var $fields = array(
		'id'	=> array('type' => 'integer','key' => 'primary'),
		'name'	=> array('type' => 'string','null' => false),
		'number_tree_id' => array('type' => 'integer', 'null' => false),
		'parent_id' => 'integer',
		'lft'	=> array('type' => 'integer','null' => false),
		'rght'	=> array('type' => 'integer','null' => false)
	);
}
?>