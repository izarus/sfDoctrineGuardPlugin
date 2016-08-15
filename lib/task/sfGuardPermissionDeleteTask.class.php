<?php

/*
 * This file is part of the symfony package.
 * (c) Fabien Potencier <fabien.potencier@symfony-project.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * Create a new Permission.
 *
 * @package    symfony
 * @subpackage task
 * @author     Emanuele Panzeri <thepanz@gmail.com>
 */
class sfGuardPermissionDeleteTask extends sfBaseTask
{
  /**
   * @see sfTask
   */
  protected function configure()
  {
    $this->addArguments(array(
      new sfCommandArgument('name', sfCommandArgument::REQUIRED, 'The permission name to delete'),
    ));

    $this->addOptions(array(
      new sfCommandOption('description', null, sfCommandOption::PARAMETER_REQUIRED, 'The Permission description', null),
      new sfCommandOption('application', null, sfCommandOption::PARAMETER_OPTIONAL, 'The application name', null),
      new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
    ));

    $this->namespace = 'guard';
    $this->name = 'permission:delete';
    $this->briefDescription = 'Delete a Permission';

    $this->detailedDescription = <<<EOF
The [guard:permission:delete|INFO] task deletes a Permission:

  [./symfony guard:permission:delete PermissionName"|INFO]
EOF;
  }

  /**
   * @see sfTask
   */
  protected function execute($arguments = array(), $options = array())
  {
    $databaseManager = new sfDatabaseManager($this->configuration);

    $table = Doctrine_Core::getTable('sfGuardPermission');
    $entity = $table->findOneBy('name', $arguments['name']);
    if (!$entity) {
        $this->logSection('guard', sprintf('Permission "%s" does not exists!', $arguments['name']), null, 'ERROR');
        return -1;
    }

    if (!$entity->delete()) {
        $this->logSection('guard', sprintf('Permission "%s" was not deleted', $arguments['name']), 'ERROR');
        return -1;
    }

    $this->logSection('guard', sprintf('Permission "%s" deleted', $arguments['name']));
  }
}
