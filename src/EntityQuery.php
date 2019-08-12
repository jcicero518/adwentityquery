<?php

namespace Workshop\Module;

use EntityFieldQuery;

class EntityQuery extends EntityFieldQuery {

  /**
   * @var $instance
   */
  private static $instance;
  /**
   * @var $query
   */
  protected $query;
  /**
   * @var string $entityType
   */
  protected $entityType = 'node';
  /**
   * @var string $bundle
   */
  protected $bundle = 'case_study';
  /**
   * @var string $filterOn
   * Used in property condition, must be a column on the node table
   */
  protected $filterOn = 'created';

  public function __construct() {
    $this->entityCondition('entity_type', $this->entityType)
      ->entityCondition('bundle', $this->bundle)
      ->propertyCondition('status', NODE_PUBLISHED);
  }

  public static function getInstance() {
    if (self::$instance === NULL) {
      self::$instance = new static();
    }
    return self::$instance;
  }

  public function filterEntityNode($node, $type) {

    if ($type) {
      switch ($type) {
        case 'prev':
          $this->propertyCondition($this->filterOn, $node->{$this->filterOn}, '>')
            ->propertyOrderBy($this->filterOn, 'ASC');
          break;
        case 'next':
          $this->propertyCondition($this->filterOn, $node->{$this->filterOn}, '<')
            ->propertyOrderBy($this->filterOn, 'DESC');
          break;
      }
    }

    $this->range(0, 1)
      ->addMetaData('account', user_load(1)); // run query as user 1

    return $this;
  }

  /**
   * @return bool|mixed
   */
  public function execute() {
    $result = parent::execute();

    if (isset($result['node'])) {
      sort($result['node']);
      return node_load($result['node'][0]->nid);
    }

    return false;
  }

  public function getTitle($node) {
    return $node->title;
  }

  public function getNodePath($node) {
    return url('node/' . $node->nid);
  }

  /**
   * Return array of image attributes
   * [ fid, uid, uri, alt, etc.]
   *
   * @param $node
   * @param string $field_name
   * @param string $lang
   * @return mixed
   */
  public function getImageField($node, $field_name = 'field_case_study_image', $lang = 'und') {
    return $node->{$field_name}[$lang][0];
  }

  /**
   * @param string $entityType
   */
  public function setEntityType(string $entityType): void {
    $this->entityType = $entityType;
  }

  /**
   * @param string $bundle
   */
  public function setBundle(string $bundle): void {
    $this->bundle = $bundle;
  }

  /**
   * @param string $filterOn
   */
  public function setFilterOn(string $filterOn): void {
    $this->filterOn = $filterOn;
  }

}
