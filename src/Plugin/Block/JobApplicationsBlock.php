<?php
/**
 * @file
 * contains \Drupal\job_applications\Plugin\Block\JobApplicationsBlock
 */
namespace Drupal\job_applications\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provedes an 'Job Applications' Block.
 * @Block(
 *  id="job_applications_block",
 *  admin_label = @Translation("Job Applications Block")
 *  )
 */
class JobApplicationsBlock extends BlockBase {

  /**
   * @{inheritdoc}
   */
  public function build() {
    return \Drupal::formBuilder()->getForm('\Drupal\job_applications\Form\JobApplicationsForm');
  }

}
