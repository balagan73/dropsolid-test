<?php

declare(strict_types=1);

namespace Drupal\dependency_injection_exercise\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\dependency_injection_exercise\Service\PhotoListService;
use GuzzleHttp\Exception\GuzzleException;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides the rest output.
 */
final class RestOutputController extends ControllerBase {

  public function __construct(
    private readonly PhotoListService $photoListService,
  ) {}

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container): static {
    return new static(
      $container->get('dependency_injection_exercise.photo_list'),
    );
  }

  /**
   * Displays the photos.
   *
   * @return array[]
   *   A renderable array representing the photos.
   */
  public function showPhotos(): array {
    $build = [
      '#cache' => [
        'max-age' => 60,
        'contexts' => [
          'url',
        ],
      ],
    ];

    try {
      $build['photos'] = $this->photoListService->getPhotos(5);
    }
    catch (GuzzleException $e) {
      $build['error'] = [
        '#type' => 'html_tag',
        '#tag' => 'p',
        '#value' => $this->t('No photos available.'),
      ];
    }

    return $build;
  }

}
