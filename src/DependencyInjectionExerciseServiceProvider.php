<?php

declare(strict_types=1);

namespace Drupal\dependency_injection_exercise;

use Drupal\Core\DependencyInjection\ContainerBuilder;
use Drupal\Core\DependencyInjection\ServiceProviderBase;
use Drupal\dependency_injection_exercise\Service\MailManagerDecorator;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Replaces the core mail manager service.
 */
class DependencyInjectionExerciseServiceProvider extends ServiceProviderBase {

  /**
   * {@inheritdoc}
   */
  public function alter(ContainerBuilder $container): void {
    $definition = $container->getDefinition('plugin.manager.mail');
    $container->setDefinition(
      'dependency_injection_exercise.mail_manager_inner',
      clone $definition
    );
    $container->register('plugin.manager.mail', MailManagerDecorator::class)
      ->addArgument(
        new Reference('dependency_injection_exercise.mail_manager_inner')
      );
  }

}
