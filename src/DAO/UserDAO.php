<?php

namespace WebLinks\DAO;

use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use WebLinks\Domain\User;

/**
 * @author Erwan IQUEL
 */
class UserDAO extends DAO implements UserProviderInterface {

  /**
   * Returns an user matching supplied id.
   * @param id User id
   * @throws Exception if not matching user is found
   * @return User object
   */
  public function find($id) {
    $sql = 'SELECT * FROM t_user WHERE user_id=?;';

    $row = $this->getDb()->fetchAssoc($sql, array($id));

    if($row) {
      return $this->buildDomainObject($row);
    } else {
      throw new \Exception('No user matching id '.$id);
    }
  }

  /**
   * @inheritDoc
   */
  public function loadUserByUsername($username) {
    $sql = 'SELECT * FROM t_user WHERE user_name=?;';

    $row = $this->getDb()->fetchAssoc($sql, array($username));

    if($row) {
      return $this->buildDomainObject($row);
    } else {
      throw new UsernameNotFoundException(sprintf('User "%s" not found.', $username));
    }
  }

  /**
   * @inheritDoc
   */
  public function refreshUser(UserInterface $user) {
    $class = get_class($user);

    if(!$this->supportsClass($class)) {
      throw new UnsupportedUserException(sprintf('Instance of "%s" are not supported.', $class));
    }

    return $this->loadUserByUsername($user->getUsername());
  }

  /**
   * @inheritDoc
   */
  public function supportsClass($class) {
    return 'WebLinks\Domain\User' === $class;
  }

  /**
   * Creates a User object based on a DB row.
   * @param row The DB row containing User data.
   * @return User object
   */
  protected function buildDomainObject(array $row) {
    $user = new User();

    $user->setId($row['user_id']);
    $user->setUsername($row['user_name']);
    $user->setPassword($row['user_password']);
    $user->setSalt($row['user_salt']);
    $user->setRole($row['user_role']);

    return $user;
  }

  /**
   * Returns a list of all users, sorted by role and name.
   * @return A list of all users.
   */
  public function findAll() {
    $sql = 'SELECT * FROM t_user ORDER BY user_role, user_name';

    $result = $this->getDb()->fetchAll($sql);

    $entities = array();

    foreach($result as $row) {
      $id = $row['user_id'];
      $entities[$id] = $this->buildDomainObject($row);
    }

    return $entities;
  }

  /**
   * Save user into database.
   * @param user The user to save
   */
  public function save(User $user) {
    $userData = array(
      'user_name' => $user->getUsername(),
      'user_password' => $user->getPassword(),
      'user_salt' => $user->getSalt(),
      'user_role' => $user->getRole()
    );

    if($user->getId()) {
      $this->getDb()->update('t_user', $userData, array('user_id' => $user->getId()));
    } else {
      $this->getDb()->insert('t_user', $userData);
      $id = $this->getDb()->lastInsertId();
      $user->setId($id);
    }
  }

  /**
   * Removes an user from the DB.
   * @param id The user id
   */
  public function delete($id) {
    $this->getDb()->delete('t_user', array('user_id' => $id));
  }
}
