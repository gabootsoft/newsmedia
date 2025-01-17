<?php

namespace Drupal\thunder_gqls\Plugin\GraphQL\SchemaExtension;

use Drupal\graphql\GraphQL\Execution\ResolveContext;
use Drupal\graphql\GraphQL\ResolverRegistryInterface;
use Drupal\media\MediaInterface;
use GraphQL\Type\Definition\ResolveInfo;

/**
 * The media schema extension.
 *
 * @SchemaExtension(
 *   id = "thunder_media",
 *   name = "Media extension",
 *   description = "Adds media entities and their fields.",
 *   schema = "thunder"
 * )
 */
class ThunderMediaSchemaExtension extends ThunderSchemaExtensionPluginBase {

  /**
   * {@inheritdoc}
   */
  public function registerResolvers(ResolverRegistryInterface $registry): void {
    parent::registerResolvers($registry);

    $this->registry->addTypeResolver('Media',
      \Closure::fromCallable([
        self::class,
        'resolveMediaTypes',
      ])
    );

    $this->resolveFields();
  }

  /**
   * Add image media field resolvers.
   */
  protected function resolveFields(): void {
    // Image.
    $this->resolveMediaInterfaceFields('MediaImage');
    $this->addFieldResolverIfNotExists('MediaImage', 'copyright',
      $this->builder->fromPath('entity', 'field_copyright.value')
    );

    $this->addFieldResolverIfNotExists('MediaImage', 'description',
      $this->builder->fromPath('entity', 'field_description.processed')
    );

    $this->addFieldResolverIfNotExists('MediaImage', 'src',
      $this->builder->compose(
        $this->builder->fromPath('entity', 'field_image.entity'),
        $this->builder->produce('image_url')
          ->map('entity', $this->builder->fromParent())
      )
    );

    $this->addFieldResolverIfNotExists('MediaImage', 'derivative',
      $this->builder->compose(
        $this->builder->fromPath('entity', 'field_image.entity'),
        $this->builder->produce('image_derivative')
          ->map('entity', $this->builder->fromParent())
          ->map('style', $this->builder->fromArgument('style')),
        $this->builder->callback(function ($values) {
          if (!empty($values['url'])) {
            return $values + ['src' => $values['url']];
          }
          return;
        })
      )
    );

    $this->addFieldResolverIfNotExists('MediaImage', 'focalPoint',
      $this->builder->compose(
        $this->builder->fromPath('entity', 'field_image.entity'),
        $this->builder->produce('focal_point')
          ->map('file', $this->builder->fromParent())
      )
    );

    $this->addFieldResolverIfNotExists('MediaImage', 'width',
      $this->builder->fromPath('entity', 'field_image.width')
    );

    $this->addFieldResolverIfNotExists('MediaImage', 'height',
      $this->builder->fromPath('entity', 'field_image.height')
    );

    $this->addFieldResolverIfNotExists('MediaImage', 'title',
      $this->builder->fromPath('entity', 'field_image.title')
    );

    $this->addFieldResolverIfNotExists('MediaImage', 'alt',
      $this->builder->fromPath('entity', 'field_image.alt')
    );

    $this->addFieldResolverIfNotExists('MediaImage', 'tags',
      $this->fromEntityReference('field_tags')
    );

    $this->addFieldResolverIfNotExists('MediaImage', 'source',
      $this->builder->fromPath('entity', 'field_source.value')
    );

    // Video.
    $this->resolveMediaInterfaceFields('MediaVideo');

    $this->addFieldResolverIfNotExists('MediaVideo', 'src',
      $this->builder->fromPath('entity', 'field_media_video_embed_field.value')
    );

    $this->addFieldResolverIfNotExists('MediaVideo', 'username',
      $this->builder->fromPath('entity', 'field_author.value')
    );

    $this->addFieldResolverIfNotExists('MediaVideo', 'caption',
      $this->builder->fromPath('entity', 'field_caption.processed')
    );

    $this->addFieldResolverIfNotExists('MediaVideo', 'copyright',
      $this->builder->fromPath('entity', 'field_copyright.value')
    );

    $this->addFieldResolverIfNotExists('MediaVideo', 'description',
      $this->builder->fromPath('entity', 'field_description.processed')
    );

    $this->addFieldResolverIfNotExists('MediaVideo', 'source',
      $this->builder->fromPath('entity', 'field_source.value')
    );

  }

  /**
   * Resolves media types.
   *
   * @param mixed $value
   *   The current value.
   * @param \Drupal\graphql\GraphQL\Execution\ResolveContext $context
   *   The resolve context.
   * @param \GraphQL\Type\Definition\ResolveInfo $info
   *   The resolve information.
   *
   * @return string
   *   Response type.
   *
   * @throws \Exception
   */
  protected function resolveMediaTypes($value, ResolveContext $context, ResolveInfo $info): string {
    if ($value instanceof MediaInterface) {
      return 'Media' . $this->mapBundleToSchemaName($value->bundle());
    }
    throw new \Exception('Invalid media type.');
  }

}
