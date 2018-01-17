<?php

/*
 * This file is part of the symfony package.
 * (c) Fabien Potencier <fabien.potencier@symfony-project.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * Add a group to a user.
 *
 * @package    symfony
 * @subpackage task
 * @author     Fabien Potencier <fabien.potencier@symfony-project.com>
 * @version    SVN: $Id$
 */
class sfGuardPermissionListTask extends sfBaseTask
{
  /**
   * @see sfTask
   */
  protected function configure()
  {
    $this->addArguments(array(
    ));

    $this->addOptions(array(
      new sfCommandOption('with-groups', null, sfCommandOption::PARAMETER_NONE, 'Join with Groups'),
      new sfCommandOption('with-users', null, sfCommandOption::PARAMETER_NONE, 'Join with Users'),
    ));

    $this->namespace = 'guard';
    $this->name = 'permission:list';
    $this->briefDescription = 'List Permissions';

    $this->detailedDescription = <<<EOF
The [guard:permission:list|INFO] list permissions:

  [./symfony guard:list-groups|INFO]
EOF;
  }

  /**
   * @see sfTask
   */
  protected function execute($arguments = array(), $options = array())
  {
    $databaseManager = new sfDatabaseManager($this->configuration);

    $permTable = Doctrine_Core::getTable('sfGuardPermission');

    $perms = $permTable->findAll();

    $this->logSection('guard', sprintf('Found %d Permissions', $perms->count()));

      /** @var sfGuardGroup $perm */
    foreach($perms as $perm) {
        $this->logSection('guard', sprintf(' - %s', $perm->getName()));
        if ($options['with-groups']) {
            foreach ($perm->getPermissions() as $permission) {
                $this->logSection('guard', sprintf('   - Permission: %s', $permission->getName()));
            }
        }
    }
      if ($options['with-users']) {
          foreach ($perm->getUsers() as $user) {
              $this->logSection('guard', sprintf('   - User: %s', $user->getName()));
          }
      }

  }
}
