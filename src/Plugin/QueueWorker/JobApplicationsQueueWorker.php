<?php
/**
 * @file
 * Contains \Drupal\job_applications\Plugin\QueueWorker\JobApplicationsQueueWorker.
 */
namespace Drupal\job_applications\Plugin\QueueWorker;

use Drupal\Core\Queue\QueueWorkerBase;

/**
 * Processes tasks for example module.
 *
 * @QueueWorker(
 *   id = "send_mail_queue",
 *   title = @Translation("Example: Queue worker"),
 *   cron = {"time" = 60}
 * )
 */
class JobApplicationsQueueWorker extends QueueWorkerBase {

  /**
   * {@inheritdoc}
   */
  public function processItem($job_application) {
    $mailManager = \Drupal::service('plugin.manager.mail');
    $module = 'job_applications';
    $key = 'job_applications_send_mail';
    $to = $job_application['email'];
    $params['message'] = "Lorem ipsum dolor sit amet ...";
    $params['subject'] = "Mail subject";
    $langcode = \Drupal::currentUser()->getPreferredLangcode();
    $send = true;
    $mailManager->mail($module, $key, $to, $langcode, $params, NULL, $send);
  }

}
