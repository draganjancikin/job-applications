<?php
/**
 * @file
 * Contains \Drupal\job_applications\Controller\ReportController.
 */
namespace Drupal\job_applications\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Database\Database;

/**
 * Contoller for Job Applications List Report.
 */
class ReportController extends ControllerBase {

  /**
   * Gets Job Applications from DB with result limitation to 5 entries.
   *
   * @return array
   */
  protected function load() {
    $select = Database::getConnection()
      ->select('job_applications', 'j')
      ->fields('j', ['name', 'email', 'type', 'technology', 'message'])
      ->extend('Drupal\Core\Database\Query\PagerSelectExtender')->limit(5);
    $entries = $select->execute()->fetchAll(\PDO::FETCH_ASSOC);
    return $entries;
  }

  /**
  * Creates list table.
  *
  * @return array
  *   Render array for buildTable output.
  */
  public function report() {
    $content = array();
    $content['message'] = array(
      '#markup' => $this->t('Below is list of all Job Applications'),
    );
    $headers = array(
      $this->t('Name'),
      $this->t('Email'),
      $this->t('Type'),
      $this->t('Technology'),
      $this->t('Message'),
    );
    $rows = array();
    foreach ($entries = $this->load() as $entry) {
      // Sanitize each entry.
      $rows[] = array_map('\Drupal\Component\Utility\SafeMarkup::checkPlain', $entry);
    }
    $content['table'] = array(
      '#type' => 'table',
      '#header' => $headers,
      '#rows' => $rows,
      '#empty' => $this->t('No entries available'),
    );
    // Add the pager.
    $content['pager'] = array(
      '#type' => 'pager'
    );
    // Dont cache this page.
    $content['#cache']['max-age'] = 0;
    return $content;
  }

}
