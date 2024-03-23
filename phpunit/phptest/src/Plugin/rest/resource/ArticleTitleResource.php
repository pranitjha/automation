<?php

namespace Drupal\phptest\Plugin\rest\resource;

use Drupal\rest\Plugin\ResourceBase;
use Drupal\rest\ResourceResponse;
use Drupal\Core\Session\AccountProxyInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

/**
 * Provides a resource to get article titles.
 *
 * @RestResource(
 *   id = "article_title_resource",
 *   label = @Translation("Article Title Resource"),
 *   uri_paths = {
 *     "canonical" = "/phptest/article/titles"
 *   }
 * )
 */
class ArticleTitleResource extends ResourceBase {

  /**
   * A current user instance.
   *
   * @var \Drupal\Core\Session\AccountProxyInterface
   */
  protected $currentUser;

  /**
   * An entity type manager instance.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * Constructs an ArticleTitleResource object.
   *
   * @param array $configuration
   *   A configuration array containing information about the plugin instance.
   * @param string $plugin_id
   *   The plugin_id for the plugin instance.
   * @param mixed $plugin_definition
   *   The plugin implementation definition.
   * @param array $serializer_formats
   *   The available serialization formats.
   * @param \Psr\Log\LoggerInterface $logger
   *   A logger instance.
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   *   The entity type manager.
   * @param \Drupal\Core\Session\AccountProxyInterface $current_user
   *   A current user instance.
   */
  public function __construct(
    array $configuration,
    $plugin_id,
    $plugin_definition,
    array $serializer_formats,
    LoggerInterface $logger,
    EntityTypeManagerInterface $entity_type_manager,
    AccountProxyInterface $current_user
  ) {
    parent::__construct($configuration, $plugin_id, $plugin_definition, $serializer_formats, $logger);
    $this->entityTypeManager = $entity_type_manager;
    $this->currentUser = $current_user;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->getParameter('serializer.formats'),
      $container->get('logger.factory')->get('phptest'),
      $container->get('entity_type.manager'),
      $container->get('current_user')
    );
  }

  /**
   * Responds to GET requests.
   *
   * Returns a list of article titles.
   *
   * @return \Drupal\rest\ResourceResponse
   *   The response containing a list of article titles.
   */
  public function get() {

    // Check if user has permission to access this resource
    if (!$this->currentUser->hasPermission('view article titles')) {
      throw new AccessDeniedHttpException();
    }

    // Use the Entity Type Manager to load the node storage object.
    $node_storage = $this->entityTypeManager->getStorage('node');

    // Load nodes by content type 'article'.
    $query = $node_storage->getQuery()
      ->condition('status', 1) // Only published nodes.
      ->accessCheck(FALSE)
      ->condition('type', 'article');
    $nids = $query->execute();

    // Load all the nodes.
    $nodes = $node_storage->loadMultiple($nids);

    // Extract titles.
    $titles = [];
    foreach ($nodes as $node) {
      $titles[] = [
        'title' => $node->getTitle(),
        'nid' => $node->id(),
      ];
    }

    // Return the JSON Response object.
    return new ResourceResponse($titles);
  }

  /**
   * {@inheritdoc}
   */
  public function permissions() {
    // Return the permissions for the resource.
    return [
      'view article titles' => [
        'title' => $this->t('Access to article titles data in REST resource'),
      ],
    ];
  }

}
