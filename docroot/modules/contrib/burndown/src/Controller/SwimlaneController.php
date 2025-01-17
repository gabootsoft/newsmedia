<?php

namespace Drupal\burndown\Controller;

use Drupal\burndown\Entity\Swimlane;
use Drupal\Component\Utility\Html;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * Controller object for Burndown Swimlanes.
 */
class SwimlaneController extends ControllerBase {
  /**
   * The entity type manager.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * Constructs a BoardController object.
   *
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entityTypeManager
   *   The entityTypeManager.
   */
  public function __construct(EntityTypeManagerInterface $entityTypeManager) {
    $this->entityTypeManager = $entityTypeManager;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('entity_type.manager')
    );
  }

  /**
   * Callback for `burndown/reorder_swimlanes/{shortcode}` route.
   */
  public function getSwimlanes($shortcode) {
    $data = [];

    // Sanitize input.
    $code = Html::escape($shortcode);

    // Get swimlanes.
    $swimlanes = Swimlane::getBoardSwimlanes($code);

    // Get tasks for each swimlane.
    foreach ($swimlanes as $swimlane) {
      $swimlane_name = $swimlane->getName();
      $swimlane_id = $swimlane->id();

      $data[] = [
        'swimlane_name' => $swimlane_name,
        'swimlane_id' => $swimlane_id,
      ];
    }

    // Return data.
    return [
      '#theme' => 'burndown_project_swimlanes',
      '#data' => [
        'shortcode' => $code,
        'swimlanes' => $data,
      ],
      '#attached' => [
        'library' => [
          'burndown/drupal.burndown.swimlanes',
        ],
      ],
      '#cache' => [
        'max-age' => 0,
      ],
    ];
  }

  /**
   * Callback for `burndown/api/swimlane_reorder` API method.
   */
  public function reorderBoard(Request $request) {
    // Get our new sort order.
    $sort = $request->request->get('sort');
    $new_sort = [];

    if (!empty($sort)) {
      foreach ($sort as $counter => $swimlane_id) {
        $swimlane = Swimlane::load($swimlane_id);
        if ($swimlane !== FALSE) {
          $new_sort[$counter] = $swimlane_id;
          $swimlane
            ->setSortOrder($counter)
            ->save();
        }
      }
    }

    // Return JSON response.
    return new JsonResponse([
      'success' => 1,
      'sort' => $new_sort,
      'method' => 'POST',
    ]);
  }

}
