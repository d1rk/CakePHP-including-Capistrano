<?php
/**
 * Remove Cache Shell
 *
 * This shell allows you to remove cache files easily and provides you with a couple configuration options.
 * If run with no command line arguments, RemoveCache removes all your standard cache files (db cache, model cache, etc.)
 * as well as your view caching files.
 *
 *
 * RemoveCache Shell : Removing your Cache
 * Copyright 2009, Debuggable, Ltd. (http://debuggable.com)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @filesource
 * @copyright     Copyright 2009, Debuggable, Ltd. (http://debuggable.com)
 * @link          http://www.cakefoundation.org/projects/info/cakephp CakePHP(tm) Project
 * @license       http://www.opensource.org/licenses/mit-license.php The MIT License
 */
class RemoveCacheShell extends Shell {
/**
 * undocumented function
 *
 * @return void
 * @access public
 */
  function initialize() {
    parent::initialize();
 
    $this->settings = array(
      'view_cache_path' => APP . 'tmp' . DS . 'cache' . DS . 'views',
      'std_cache_paths' => array(
        APP . 'tmp',
        APP . 'tmp' . DS . 'cache',
        APP . 'tmp' . DS . 'models',
        APP . 'tmp' . DS . 'persistent'
      )
    );
  }
/**
 * undocumented function
 *
 * @return void
 * @access public
 */
  function main() {
    $args = $this->args;
 
    $stdCache = !isset($args[0]) || $args[0];
    $viewCachePattern = isset($args[1]) ? $args[1] : '.*';
 
    if ($stdCache) {
      $this->_cleanStdCache();
    }
 
    $this->_cleanViewCache($viewCachePattern);
  }
/**
 * Cleans the standard cache, ie all model caches, db caches, persistent caches
 * Files need to be prefixed with cake_ to be removed
 *
 * @return void
 * @access public
 */
  function _cleanStdCache() {
    $paths = $this->settings['std_cache_paths'];
 
    foreach ($paths as $path) {
      $folder = new Folder($path);
      $contents = $folder->read();
      $files = $contents[1];
      foreach ($files as $file) {
        if (!preg_match('/^cake_/', $file)) {
          continue;
        }
        $this->out($path . DS . $file);
        @unlink($path . DS . $file);
      }
    }
  }
/**
 * Cleans all view caching files. Takes a pattern to match files against.
 *
 * @param string $pattern 
 * @return void
 * @access public
 */
  function _cleanViewCache($pattern) {
    $path = $this->settings['view_cache_path'];
 
    if ($pattern{0} != '/') {
      $pattern = '/' . $pattern . '/i';
    }
 
    $folder = new Folder($path);
    $contents = $folder->read();
    $files = $contents[1];
    foreach ($files as $file) {
      if (!preg_match($pattern, $file)) {
        continue;
      }
      $this->out($path . DS . $file);
      @unlink($path . DS . $file);
    }
  }
/**
 * undocumented function
 *
 * @return void
 * @access public
 */
  function help() {
    $this->out('Debuggable Ltd. Remove Cache Shell - http://debuggable.com');
    $this->hr();
    $this->out('Important: Configure your paths in the shell\'s initialize() function.');
    $this->hr();
    $this->out('This shell allows you to remove cache files easily and provides you with a couple configuration options.');
    $this->out('If run with no command line arguments, RemoveCache removes all your standard cache files (db cache, model cache, etc.) ');
    $this->out('as well as your view caching files.');
    $this->out('');
    $this->out('Set the first parameter to 0 (zero), to not remove standard cache files.');
    $this->out('Set a regex pattern for the second argument, to match viewcache files to delete.');
    $this->hr();
    $this->out("Usage: cake remove_cache <std_cache_boolean> <pattern_to_match_viewcache_files>");
    $this->out("Usage: cake remove_cache \t\t// removes all cache files");
    $this->out("Usage: cake remove_cache 0 \t\t// removes only view cache files");
    $this->out("Usage: cake remove_cache 0 home \t// removes only the view cache file for your homepage");
    $this->out("Usage: cake remove_cache 0 articles_ \t// removes all view cache files for your articles controller");
    $this->out("Usage: cake remove_cache 1 /letter_z$/ \t// removes all std cache files and view cache files ending with 'letter_z'");
    $this->out('');
  }
}
?>
