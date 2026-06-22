<?php

declare(strict_types=1);

namespace Drupal\dependency_injection_exercise\Service;

use Drupal\Core\Language\Language;
use Drupal\Core\Language\LanguageInterface;
use Drupal\Core\Language\LanguageManager;
use Drupal\Core\Url;
use Drupal\language\ConfigurableLanguageManagerInterface;
use Drupal\language\LanguageManager as ConfigurableLanguageManager;
use Drupal\language\LanguageNegotiatorInterface;

/**
 * Decorates the language manager service.
 *
 * Registered via the 'decorates' key in services.yml so other modules can
 * stack their own decorators using decoration_priority without conflict.
 */
class LanguageManagerDecorator implements ConfigurableLanguageManagerInterface {

  public function __construct(
    private readonly ConfigurableLanguageManagerInterface $inner,
  ) {}

  /**
   * {@inheritdoc}
   */
  public static function rebuildServices(): void {
    // Static methods cannot delegate to an instance; forward to the
    // core configurable language manager that owns this utility method.
    ConfigurableLanguageManager::rebuildServices();
  }

  /**
   * {@inheritdoc}
   */
  public function isMultilingual() {
    return $this->inner->isMultilingual();
  }

  /**
   * {@inheritdoc}
   */
  public function getLanguageTypes() {
    return $this->inner->getLanguageTypes();
  }

  /**
   * {@inheritdoc}
   */
  public function getDefinedLanguageTypesInfo() {
    return $this->inner->getDefinedLanguageTypesInfo();
  }

  /**
   * {@inheritdoc}
   */
  public function getDefinedLanguageTypes() {
    return $this->inner->getDefinedLanguageTypes();
  }

  /**
   * {@inheritdoc}
   */
  public function getCurrentLanguage($type = LanguageInterface::TYPE_INTERFACE) {
    return $this->inner->getCurrentLanguage($type);
  }

  /**
   * {@inheritdoc}
   */
  public function reset($type = NULL) {
    $this->inner->reset($type);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getDefaultLanguage() {
    return $this->inner->getDefaultLanguage();
  }

  /**
   * {@inheritdoc}
   */
  public function getLanguages($flags = LanguageInterface::STATE_CONFIGURABLE) {
    $languages = $this->inner->getLanguages($flags);
    foreach ($languages as $langcode => $language) {
      $languages[$langcode] = new Language([
        'name' => '[DI] ' . $language->getName(),
        'id' => $language->getId(),
        'direction' => $language->getDirection(),
        'weight' => $language->getWeight(),
        'locked' => $language->isLocked(),
      ]);
    }
    return $languages;
  }

  /**
   * {@inheritdoc}
   */
  public function getNativeLanguages() {
    return $this->inner->getNativeLanguages();
  }

  /**
   * {@inheritdoc}
   */
  public function getLanguage($langcode) {
    return $this->inner->getLanguage($langcode);
  }

  /**
   * {@inheritdoc}
   */
  public function getLanguageName($langcode) {
    return $this->inner->getLanguageName($langcode);
  }

  /**
   * {@inheritdoc}
   */
  public function getDefaultLockedLanguages($weight = 0) {
    return $this->inner->getDefaultLockedLanguages($weight);
  }

  /**
   * {@inheritdoc}
   */
  public function isLanguageLocked($langcode) {
    return $this->inner->isLanguageLocked($langcode);
  }

  /**
   * {@inheritdoc}
   */
  public function getFallbackCandidates(array $context = []) {
    return $this->inner->getFallbackCandidates($context);
  }

  /**
   * {@inheritdoc}
   */
  public function getLanguageSwitchLinks($type, Url $url) {
    return $this->inner->getLanguageSwitchLinks($type, $url);
  }

  /**
   * {@inheritdoc}
   */
  public function setConfigOverrideLanguage(?LanguageInterface $language = NULL) {
    $this->inner->setConfigOverrideLanguage($language);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getConfigOverrideLanguage() {
    return $this->inner->getConfigOverrideLanguage();
  }

  /**
   * {@inheritdoc}
   */
  public static function getStandardLanguageList() {
    // Static methods cannot delegate to an instance; forward to the
    // core implementation which owns this utility method.
    return LanguageManager::getStandardLanguageList();
  }

  /**
   * {@inheritdoc}
   */
  public function getNegotiator() {
    return $this->inner->getNegotiator();
  }

  /**
   * {@inheritdoc}
   */
  public function setNegotiator(LanguageNegotiatorInterface $negotiator) {
    $this->inner->setNegotiator($negotiator);
  }

  /**
   * {@inheritdoc}
   */
  public function saveLanguageTypesConfiguration(array $config) {
    $this->inner->saveLanguageTypesConfiguration($config);
  }

  /**
   * {@inheritdoc}
   */
  public function updateLockedLanguageWeights() {
    $this->inner->updateLockedLanguageWeights();
  }

  /**
   * {@inheritdoc}
   */
  public function getLanguageConfigOverride($langcode, $name) {
    return $this->inner->getLanguageConfigOverride($langcode, $name);
  }

  /**
   * {@inheritdoc}
   */
  public function getLanguageConfigOverrideStorage($langcode) {
    return $this->inner->getLanguageConfigOverrideStorage($langcode);
  }

  /**
   * {@inheritdoc}
   */
  public function getStandardLanguageListWithoutConfigured() {
    return array_map(
      fn($name) => '[DI] ' . $name,
      $this->inner->getStandardLanguageListWithoutConfigured(),
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getNegotiatedLanguageMethod(
    $type = LanguageInterface::TYPE_INTERFACE,
  ) {
    return $this->inner->getNegotiatedLanguageMethod($type);
  }

}
