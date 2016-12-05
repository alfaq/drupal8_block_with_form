<?php

/**
 * @file
 * Contains \Drupal\trading_platforms_block\Plugin\Block\TradingPlatforms.
 */

// Пространство имён для нашего блока.
namespace Drupal\trading_platforms_block\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Form\FormStateInterface;
Use Drupal\file\Entity\File;
//use Drupal\Core\Url;
use Drupal\image\Entity\ImageStyle;

/**
 * @Block(
 *   id = "trading_platforms_block",
 *   admin_label = @Translation("Trading platforms block"),
 * )
 */
class TradingPlatforms extends BlockBase{

  /**
   * Добавляем наши конфиги по умолчанию.
   *
   * {@inheritdoc}
   */
  public function defaultConfiguration() {
    return array(
      'tr-subtitle' => '',
      'tr-message' => array('format' => 'full_html', 'value' => ''),
      'tr-path' => '',
      'tr-image' => '',
    );
  }

  /**
   * Добавляем в стандартную форму блока свои поля.
   *
   * {@inheritdoc}
   */
  public function blockForm($form, FormStateInterface $form_state) {
    // Получаем оригинальную форму для блока.
    $form = parent::blockForm($form, $form_state);
    // Получаем конфиги для данного блока.
    $config = $this->getConfiguration();

    // Добавляем поле для ввода сообщения.
    $form['tr-subtitle'] = array(
      '#type' => 'textfield',
      '#title' => t('Subtitle'),
      '#default_value' => $config['tr-subtitle'],
      '#required' => true,
    );

    $form['tr-path'] = array(
      '#type' => 'textfield',
      '#title' => t('Path'),
      '#default_value' => $config['tr-path'],
      '#required' => true,
    );

    $form['tr-image'] = array(
      '#type' => 'managed_file',
      '#title' => t('Upload your image'),
      '#required' => true,
      '#default_value' => $config['tr-image'],
      '#upload_location' => 'public://',
    );

    $form['tr-message'] = array(
      '#type' => 'text_format',
      '#title' => 'Message',
      '#format' => $config['tr-message']['format'],
      '#default_value' => $config['tr-message']['value'],
      '#required' => true,
    );

    return $form;
  }

  /**
   * Валидируем значения на наши условия.
   * Количество должно быть >= 1,
   * Сообщение должно иметь минимум 5 символов.
   *
   * {@inheritdoc}
   */
  public function blockValidate($form, FormStateInterface $form_state) {
    /*$subtitle = $form_state->getValue('subtitle');
    $message = $form_state->getValue('message');
    $path = $form_state->getValue('path');*/

    // Проверяем введенное число.
    /*if (!is_numeric($count) || $count < 1) {
      $form_state->setErrorByName('count', t('Needs to be an interger and more or equal 1.'));
    }*/

    // Проверяем на длину строки.
    /*if (strlen($message) < 5) {
      $form_state->setErrorByName('message', t('Message must contain more than 5 letters'));
    }*/
  }

  /**
   * В субмите мы лишь сохраняем наши данные.
   *
   * {@inheritdoc}
   */
  public function blockSubmit($form, FormStateInterface $form_state) {
    if (!$form_state->getErrors()) {
      kint($form_state);
      $this->configuration['tr-subtitle'] = $form_state->getValue('tr-subtitle');
      $this->configuration['tr-message'] = $form_state->getValue('tr-message');
      $this->configuration['tr-path'] = $form_state->getValue('tr-path');
      $this->configuration['tr-image'] = $form_state->getValue('tr-image');
    }
  }

  /**
   * Генерируем и выводим содержимое блока.
   *
   * {@inheritdoc}
   */
  public function build() {
    $config = $this->getConfiguration();
    //$config['tr-subtitle'];
    //$config['tr-message'];
    //$config['tr-path'];
    $fid = $config['tr-image'][0];
    $file = File::load($fid);
    //kint($file);
    //$file = $this->entityTypeManager->getStorage('file')->load($fid);

    $fileuri = $file->getFileUri();
    //$path = file_create_url($fileuri);
    //$path = Url::fromUri('base:' . $fileuri)->getInternalPath();
    $url = ImageStyle::load('400x250')->buildUrl($fileuri);
    //$path = Url::fromUri($fileuri)->getInternalPath();
    //kint($path);
    //kint($config['tr-message']['value']);

    $items = array('title' => $config['tr-subtitle'], 'message' => $config['tr-message']['value'], 'path' => $config['tr-path'], 'image' => $url);

    $message = '';

    $block = [
      //'#type' => 'markup',
      //'#markup' => $message,
      '#theme' => 'trading_platforms_block',
      '#items' => $items,
    ];
    return $block;
  }

}