<?php

/*
 * This file is part of the symfony package.
 * (c) Fabien Potencier <fabien.potencier@symfony-project.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * Lists sfGuard groups.
 *
 * @package    symfony
 * @subpackage task
 * @author     Fabien Potencier <fabien.potencier@symfony-project.com>
 * @version    SVN: $Id$
 */
class sfGuardGroupListTask extends sfBaseTask
{
  /**
   * @see sfTask
   */
  protected function configure()
  {
    $this->addArguments(array(
    ));

    $this->addOptions(array(
      new sfCommandOption('with-perm', null, sfCommandOption::PARAMETER_NONE, 'Join with Permissions'),
      new sfCommandOption('with-users', null, sfCommandOption::PARAMETER_NONE, 'Join with Users'),
    ));

    $this->namespace = 'guard';
    $this->name = 'group:list';
    $this->briefDescription = 'List groups';

    $this->detailedDescription = <<<EOF
The [guard:group:list|INFO] list groups:

  [./symfony guard:group:list|INFO]
EOF;
  }

  /**
   * @see sfTask
   */
  protected function execute($arguments = array(), $options = array())
  {
    $databaseManager = new sfDatabaseManager($this->configuration);

    $groupTable = Doctrine_Core::getTable('sfGuardGroup');

    $groups = $groupTable->findAll();

    $this->logSection('guard', sprintf('Found %d Groups', $groups->count()));

      /** @var sfGuardGroup $group */
    foreach($groups as $group) {
        $this->logSection('guard', sprintf('%d : %s', $group->getPrimaryKey(), $group->getName()));

        if ($options['with-perm']) {
            /** @var sfGuardPermission $permission */
            foreach ($group->getPermissions() as $permission) {
                $this->logSection('guard', sprintf('   - Permission: %s', $permission->getName()));
            }
        }
    }
      if ($options['with-users']) {
          /** @var sfGuardUser $user */
          foreach ($group->getUsers() as $user) {
              $this->logSection('guard', sprintf('   - User %d:  %s', $user->getPrimaryKey(), $user->getName()));
          }
      }

  }
}
