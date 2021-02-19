<?php

namespace Drupal\album;

/**
 * Class HelloService return simple text.
 *
 * @package Drupal\album
 */
class HelloService {

  /**
   * Variable that contains simple text.
   *
   * @var string
   *   Simple text.
   */
  private $sayHello = "Hello World!";

  /**
   * Return text in variable.
   *
   * @return mixed
   *   Return simple text.
   */
  public function sayHello() {
    return $this->sayHello;
  }

}
