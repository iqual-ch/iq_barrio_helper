<?php

namespace Drupal\iq_barrio_helper\Service;

/**
 *
 */
class CompilationService {

  protected $iterator = NULL;
  protected $configs = [];
  protected $compiler = NULL;
  protected static $changeRegistered = FALSE;
  protected static $isCompiling = FALSE;

  /**
   *
   */
  public function __construct() {
    $this->iterator = new \AppendIterator();
    $this->compiler = new \Sass();
    $this->compiler->setStyle(\Sass::STYLE_COMPRESSED);
  }

  /**
   *
   */
  public function addSource($directory) {
    $files = new \RecursiveDirectoryIterator($directory);
    $recursiveIterator = new \RecursiveIteratorIterator($files);
    $this->iterator->append($recursiveIterator);
  }

  /**
   *
   */
  public function watch() {
    $fd = \inotify_init();

    // Collect all config files and save per path.
    while ($this->iterator->valid()) {
      $file = $this->iterator->current();
      $watch_descriptor = \inotify_add_watch($fd, $file->getPath(), IN_CLOSE_WRITE | IN_MOVE | IN_MOVE_SELF | IN_DELETE | IN_DELETE_SELF | IN_MASK_ADD);
      $this->iterator->next();
    }
    $this->iterator->rewind();
    while (TRUE) {
      if (inotify_queue_len($fd) === 0 && $this->changeRegistered) {
        if (!$this->isCompiling) {
          $this->changeRegistered = FALSE;
          $this->compile();
        }
      }
      $events = \inotify_read($fd);
      sleep(1);
      foreach ($events as $event => $evdetails) {
        // React on the event type.
        switch (TRUE) {
          // File was modified.
          case (((int) $evdetails['mask']) & IN_CLOSE_WRITE):
            // File was moved or deleted.
          case ($evdetails['mask'] & IN_MOVE):
          case ($evdetails['mask'] & IN_MOVE_SELF):
          case ($evdetails['mask'] & IN_DELETE):
          case ($evdetails['mask'] & IN_DELETE_SELF):
            if (preg_match_all('/\.scss$/', $evdetails['name'])) {
              $this->changeRegistered = TRUE;
            }
            break;

          break;
        }
      }
    }
  }

  /**
   *
   */
  public function compile() {
    $this->isCompiling = TRUE;
    // Collect all config files and save per path.
    while ($this->iterator->valid()) {
      $file = $this->iterator->current();
      if ($file->isFile() && $file->getFilename() == 'libsass.ini') {
        $this->configs[$file->getPath()] = parse_ini_file($file->getPath() . '/' . $file->getFilename());
      }
      $this->iterator->next();
    }
    $this->iterator->rewind();

    // Compile files, respecting the config in the same directory.
    while ($this->iterator->valid()) {
      $scssFile = $this->iterator->current();

      if ($scssFile->isFile() && $scssFile->getExtension() == 'scss' && strpos($scssFile->getFilename(), '_') !== 0) {
        $css = $this->compiler->compileFile($scssFile->getPath() . '/' . $scssFile->getFilename());
        $targetFile = $scssFile->getPath() . '/' . str_replace('scss', 'css', $scssFile->getFilename());
        if (!empty($this->configs[$scssFile->getPath()])) {
          $targetFile = $scssFile->getPath() . '/' . $this->configs[$scssFile->getPath()]['css_dir'] . '/' . str_replace('scss', 'css', $scssFile->getFilename());
        }
        file_put_contents($targetFile, $css);

      }
      $this->iterator->next();
    }
    $this->iterator->rewind();

    $this->isCompiling = FALSE;
  }

}
