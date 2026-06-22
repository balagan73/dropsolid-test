<?php

declare(strict_types=1);

namespace Drupal\dependency_injection_exercise\Service;

use Drupal\Component\Serialization\Json;
use GuzzleHttp\ClientInterface;

/**
 * Fetches photo listings from the JSONPlaceholder API.
 */
class PhotoListService {

  public function __construct(
    private readonly ClientInterface $httpClient,
  ) {}

  /**
   * Returns a render array of photos for the given album ID.
   *
   * @param int $albumId
   *   The album ID to fetch.
   *
   * @return array
   *   Renderable array of photo items.
   *
   * @throws \GuzzleHttp\Exception\GuzzleException
   *   When the HTTP request fails.
   */
  public function getPhotos(int $albumId): array {
    $response = $this->httpClient->request(
      'GET',
      "https://jsonplaceholder.typicode.com/albums/{$albumId}/photos"
    );
    $decoded = Json::decode($response->getBody()->getContents());
    $data = is_array($decoded) ? $decoded : [];
    return array_map(static function (array $item): array {
      return [
        '#theme' => 'image',
        '#uri' => 'https://picsum.photos/seed/' . $item['id'] . '/150/150',
        '#alt' => $item['title'],
        '#title' => $item['title'],
      ];
    }, $data);
  }

}
