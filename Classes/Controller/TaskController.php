<?php

namespace T3Monitor\T3monitoring\Controller;

/*
 * This file is part of the t3monitoring extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

 /**
  * Class TaskController
  */
 class TaskController extends BaseController
 {
     protected function listAction()
     {
         $this->view->assign('tasks', ['test']);
     }
 }
