<?php

declare(strict_types=1);

namespace Drupal\dependency_injection_exercise\Breadcrumb;

use Drupal\Core\Breadcrumb\Breadcrumb;
use Drupal\Core\Breadcrumb\BreadcrumbBuilderInterface;
use Drupal\Core\Link;
use Drupal\Core\Routing\RouteMatchInterface;
use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\Core\Url;

/**
 * Builds the breadcrumb for the photos page.
 */
class PhotosBreadcrumbBuilder implements BreadcrumbBuilderInterface {

  use StringTranslationTrait;

  /**
   * {@inheritdoc}
   */
  public function applies(RouteMatchInterface $route_match): bool {
    return $route_match->getRouteName() === 'dependency_injection_exercise.rest_output_controller_photos';
  }

  /**
   * {@inheritdoc}
   */
  public function build(RouteMatchInterface $route_match): Breadcrumb {
    $breadcrumb = new Breadcrumb();
    $breadcrumb->addCacheContexts(['url.path']);

    $breadcrumb->addLink(Link::createFromRoute($this->t('Home'), '<front>'));
    $breadcrumb->addLink(Link::fromTextAndUrl(
      $this->t('Dropsolid'),
      Url::fromUserInput('/dropsolid'),
    ));
    $breadcrumb->addLink(Link::fromTextAndUrl(
      $this->t('Example'),
      Url::fromUserInput('/dropsolid/example'),
    ));
    $breadcrumb->addLink(Link::createFromRoute(
      $this->t('Photos'),
      'dependency_injection_exercise.rest_output_controller_photos',
    ));

    return $breadcrumb;
  }

}
