<?php

declare(strict_types=1);

namespace Drupal\dependency_injection_exercise\Service;

use Drupal\Component\Plugin\Discovery\CachedDiscoveryInterface;
use Drupal\Core\Mail\MailManagerInterface;

/**
 * Decorates the mail manager to redirect all outgoing mail.
 */
class MailManagerDecorator implements MailManagerInterface, CachedDiscoveryInterface {

  public function __construct(
    private readonly MailManagerInterface&CachedDiscoveryInterface $inner,
  ) {}

  /**
   * {@inheritdoc}
   */
  public function mail($module, $key, $to, $langcode, $params = [], $reply = NULL, $send = TRUE): array {
    return $this->inner->mail($module, $key, 'nope@doesntexist.com', $langcode, $params, $reply, $send);
  }

  /**
   * {@inheritdoc}
   */
  public function createInstance($plugin_id, array $configuration = []) {
    return $this->inner->createInstance($plugin_id, $configuration);
  }

  /**
   * {@inheritdoc}
   */
  public function getInstance(array $options) {
    return $this->inner->getInstance($options);
  }

  /**
   * {@inheritdoc}
   */
  public function getDefinitions() {
    return $this->inner->getDefinitions();
  }

  /**
   * {@inheritdoc}
   */
  public function getDefinition($plugin_id, $exception_on_invalid = TRUE) {
    return $this->inner->getDefinition($plugin_id, $exception_on_invalid);
  }

  /**
   * {@inheritdoc}
   */
  public function hasDefinition($plugin_id) {
    return $this->inner->hasDefinition($plugin_id);
  }

  /**
   * {@inheritdoc}
   */
  public function clearCachedDefinitions() {
    $this->inner->clearCachedDefinitions();
  }

  /**
   * {@inheritdoc}
   */
  public function useCaches($use_caches = FALSE) {
    $this->inner->useCaches($use_caches);
  }

}
