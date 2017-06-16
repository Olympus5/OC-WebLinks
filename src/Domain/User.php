<?php

namespace WebLinks\Domain;

use Symfony\Component\Security\Core\User\UserInterface;
/**
 * @author Olympus5
 */
class User implements UserInterface {
  /**
   * User id.
   * @var integer
   */
  private $id;

  /**
   * User name.
   * @var string
   */
  private $username;

  /**
   * User password.
   * @var string
   */
  private $password;

  /**
   * Salt that was originally used to encode the password
   * @var string
   */
  private $salt;

  /**
   * User role.
   * Values: ROLE_USER or ADMIN_USER
   * @var string
   */
  private $role;

  /**
   * Getter
   * @return User id
   */
  public function getId() {
    return $this->id;
  }

  /**
   * Setter
   * @param id User id
   * @return This
   */
  public function setId($id) {
    $this->id = $id;
    return $this;
  }

  /**
   * @inheritDoc
   */
  public function getUsername() {
    return $this->username;
  }

  /**
   * Setter
   * @param name User name
   * @return This
   */
  public function setUsername($username) {
    $this->username = $username;
    return $this;
  }

  /**
   * @inheritDoc
   */
  public function getPassword() {
    return $this->password;
  }

  /**
   * Setter
   * @param password User password
   * @return This
   */
  public function setPassword($password) {
    $this->password = $password;
    return $this;
  }

  /**
   * @inheritDoc
   */
  public function getSalt() {
    return $this->salt;
  }

  /**
   * Setter
   * @param salt Salt used to encoded password
   * @return This
   */
  public function setSalt($salt) {
    $this->salt = $salt;
    return $this;
  }

  /**
   * @inheritDoc
   */
  public function getRole() {
    return $this->role;
  }

  /**
   * Setter
   * @param role Role
   * @return This
   */
  public function setRole($role) {
    $this->role = $role;
    return $this;
  }

  /**
   * @inheritDoc
   */
  public function getRoles() {
    return array($this->getRole());
  }

  /**
   * @inheritDoc
   */
  public function eraseCredentials() {}
}
