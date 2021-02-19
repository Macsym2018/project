<?php

namespace Drupal\album;

use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use GuzzleHttp\ClientInterface;
use Drupal\Core\Cache\CacheBackendInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class AlbumService. Provide something.
 *
 * @package Drupal\album.
 */
class AlbumService implements ContainerFactoryPluginInterface {

  /**
   * Guzzle\Client instance.
   *
   * @var \GuzzleHttp\ClientInterface
   */
  private $httpClient;

  /**
   * CacheBackendInterface instance.
   *
   * @var \Cache\CacheBackendInterface
   */
  private $cacheAlbum;

  /**
   * AlbumService constructor.
   *
   * @param \GuzzleHttp\ClientInterface $http_client
   *   Guzzle\Client instance.
   * @param Cache\CacheBackendInterface $cache
   *   CacheBackendInterface instance.
   */
  public function __construct(ClientInterface $http_client, CacheBackendInterface $cache) {
    $this->httpClient = $http_client;
    $this->cacheAlbum = $cache;
  }

  /**
   * Creates an instance of the plugin.
   *
   * @param \Symfony\Component\DependencyInjection\ContainerInterface $container
   *   The container to pull out services used in the plugin.
   * @param array $configuration
   *   A configuration array containing information about the plugin instance.
   * @param string $plugin_id
   *   The plugin ID for the plugin instance.
   * @param mixed $plugin_definition
   *   The plugin implementation definition.
   *
   * @return static
   *   Returns an instance of this plugin.
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('http_client'),
      $container->get('cache.default')
    );
  }

  /**
   * Get all albums from link.
   *
   * @return array
   *   Return array that contain all albums.
   *
   * @throws \GuzzleHttp\Exception\GuzzleException
   */
  public function getAlbums() {

    $response = $this->httpClient->request('GET', 'https://jsonplaceholder.typicode.com/albums');
    $albums = json_decode($response->getBody()->getContents());

    return $albums;
  }

  /**
   * Get all Photos from link depend on album id.
   *
   * @param string $albumId
   *   Album's ID.
   *
   * @return array
   *   Return array that contain all photos depend on almum id.
   *
   * @throws \GuzzleHttp\Exception\GuzzleException
   */
  public function getAlbumPhotos($albumId) {

    $cid = 'photos:album_photos_cache_data:' . $albumId;

    if ($cache = $this->cacheAlbum->get($cid)) {
      $album = $cache->data;
    }
    else {
      $tags = ['album_id:' . $albumId];

      $response = $this->httpClient->request('GET', 'https://jsonplaceholder.typicode.com/photos');
      $photos = json_decode($response->getBody()->getContents());

      foreach ($photos as $photo) {
        if ($photo->albumId == $albumId) {
          $album[] = [
            'albumId' => $photo->albumId,
            'id' => $photo->id,
            'title' => $photo->title,
            'url' => $photo->url,
            'thumbnailUrl' => $photo->thumbnailUrl,
          ];
        }
      }

      if ($album) {
        $this->cacheAlbum->set($cid, $album, REQUEST_TIME + (3600), $tags);
      }

    }
    return $album;
  }

  /**
   * Get all Photos from link depends on album id.
   *
   * @param string $userId
   *   User's ID.
   *
   * @return array
   *   Return array that contain all albums depend on user id.
   *
   * @throws \GuzzleHttp\Exception\GuzzleException
   */
  public function getAlbumsByUserId($userId) {

    $cid = 'photos:albums_cache_data:' . $userId;

    if ($cache = $this->cacheAlbum->get($cid)) {
      $albums = $cache->data;
    }
    else {
      $tags = ['user_id:' . $userId];

      $response = $this->httpClient->request('GET', 'https://jsonplaceholder.typicode.com/albums');
      $allAlbums = json_decode($response->getBody()->getContents());

      if ($allAlbums) {
        foreach ($allAlbums as $album) {
          if ($album->userId == $userId) {
            $albums[$album->id] = $album->title;
          }
        }

        if ($albums) {
          $this->cacheAlbum->set($cid, $albums, REQUEST_TIME + (3600), $tags);
        }

        return $albums;
      }
      else {
        return [];
      }
    }
    return $albums;
  }

}
