<?php
/**
 *
 *  @copyright 2008 - https://www.clicshopping.org
 *  @Brand : ClicShopping(Tm) at Inpi all right Reserved
 *  @Licence GPL 2 & MIT
 *  @licence MIT - Portion of osCommerce 2.4
 *  @Info : https://www.clicshopping.org/forum/trademark/
 *
 */

  use ClicShopping\OM\Registry;
  use ClicShopping\OM\CLICSHOPPING;

  class fo_facebook_chatbox {
    public string $code;
    public string $group;
    public string $title;
    public string $description;
    public ?int $sort_order = 0;
    public bool $enabled = false;


    public function __construct() {
      $this->code = get_class($this);
      $this->group = basename(__DIR__);

      $this->title = CLICSHOPPING::getDef('module_footer_facebook_chatbox_title');
      $this->description = CLICSHOPPING::getDef('module_footer_facebook_chatbox_description');

      if (\defined('MODULES_FOOTER_FACEBOOK_CHATBOX_STATUS')) {
        $this->sort_order = MODULES_FOOTER_FACEBOOK_CHATBOX_SORT_ORDER;
        $this->enabled = (MODULES_FOOTER_FACEBOOK_CHATBOX_STATUS == 'True');
      }
    }

  public function execute() {
    $CLICSHOPPING_Template = Registry::get('Template');

    $facebook_page_id = MODULES_FOOTER_FACEBOOK_CHATBOX_SDK_CHAT_ID;
    $facebook_app_id = MODULES_FOOTER_FACEBOOK_CHATBOX_SDK_APP_ID;

    if (MODULES_FOOTER_FACEBOOK_CHATBOX_SDK_CHAT_OPEN_ON_LOAD == 'True') {
      $dialog_display = 'minimized=”true”';
    } else {
      $dialog_display = 'greeting_dialog_display="hide"';
    }

    $footer_script = '<!--  facebook_chatbox start -->'."\n";
    $footer_script .= '<script>';
    $footer_script .= ' window.fbAsyncInit = function() {
    FB.init({
      appId            : \'' . $facebook_app_id . '\',
      autoLogAppEvents : true,		
      xfbml            : true,
      version          : \'v3.3\'
    });
  };

  (function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = \'https://connect.facebook.net/en_US/sdk/xfbml.customerchat.js\';
  fjs.parentNode.insertBefore(js, fjs);
}(document, \'script\', \'facebook-jssdk\'));';
    $footer_script .= '</script>';

    $footer_script .= '<!--  facebook_chatbox end -->'."\n";

    $CLICSHOPPING_Template->addBlock($footer_script, 'footer_scripts');

    $footer = '<div id="fb-root"></div>';
    $footer .= '<div class="fb-customerchat" attribution=setup_tool  page_id="' . $facebook_page_id . '" ' . $dialog_display . '></div>' . "\n";

    $CLICSHOPPING_Template->addBlock($footer, $this->group);

  }

  public function isEnabled() {
    return $this->enabled;
  }

  public function check() {
    return \defined('MODULES_FOOTER_FACEBOOK_CHATBOX_STATUS');
  }

  public function install() {
    $CLICSHOPPING_Db = Registry::get('Db');


    $CLICSHOPPING_Db->save('configuration', [
        'configuration_title' => 'Do you want to enable this module ?',
        'configuration_key' => 'MODULES_FOOTER_FACEBOOK_CHATBOX_STATUS',
        'configuration_value' => 'True',
        'configuration_description' => 'Do you want to enable this module in your shop ?<br />To find your Page ID: <br />From News Feed, click Pages in the left side menu.<br />Click your Page name to go to your Page.<br />Click About in the left column. If you don\'t see About in the left column, click See More .<br />Scroll down to find your Page ID below More Info .',
        'configuration_group_id' => '6',
        'sort_order' => '1',
        'set_function' => 'clic_cfg_set_boolean_value(array(\'True\', \'False\'))',
        'date_added' => 'now()'
      ]
    );

    $CLICSHOPPING_Db->save('configuration', [
        'configuration_title' => 'Facebook Page ID',
        'configuration_key' => 'MODULES_FOOTER_FACEBOOK_CHATBOX_SDK_CHAT_ID',
        'configuration_value' => '',
        'configuration_description' => 'Facebook Page ID.',
        'configuration_group_id' => '6',
        'sort_order' => '4',
        'set_function' => '',
        'date_added' => 'now()'
      ]
    );

    $CLICSHOPPING_Db->save('configuration', [
        'configuration_title' => 'Facebook App ID (optional)',
        'configuration_key' => 'MODULES_FOOTER_FACEBOOK_CHATBOX_SDK_APP_ID',
        'configuration_value' => '',
        'configuration_description' => 'Facebook App ID (optional).',
        'configuration_group_id' => '6',
        'sort_order' => '4',
        'set_function' => '',
        'date_added' => 'now()'
      ]
    );

    $CLICSHOPPING_Db->save('configuration', [
        'configuration_title' => 'Do you want to open Chat Box on Load ?',
        'configuration_key' => 'MODULES_FOOTER_FACEBOOK_CHATBOX_SDK_CHAT_OPEN_ON_LOAD',
        'configuration_value' => 'True',
        'configuration_description' => 'open Chat Box on Load ?',
        'configuration_group_id' => '6',
        'sort_order' => '1',
        'set_function' => 'clic_cfg_set_boolean_value(array(\'True\', \'False\'))',
        'date_added' => 'now()'
      ]
    );

    $CLICSHOPPING_Db->save('configuration', [
        'configuration_title' => 'Sort order',
        'configuration_key' => 'MODULES_FOOTER_FACEBOOK_CHATBOX_SORT_ORDER',
        'configuration_value' => '1000',
        'configuration_description' => 'Sort order of display. Lowest is displayed first. The sort order must be different on every module',
        'configuration_group_id' => '6',
        'sort_order' => '4',
        'set_function' => '',
        'date_added' => 'now()'
      ]
    );
  }

  public function remove() {
      return Registry::get('Db')->exec('delete from :table_configuration where configuration_key in ("' . implode('", "', $this->keys()) . '")');
  }

  public function keys() {
    return ['MODULES_FOOTER_FACEBOOK_CHATBOX_STATUS',
           'MODULES_FOOTER_FACEBOOK_CHATBOX_SDK_CHAT_ID',
           'MODULES_FOOTER_FACEBOOK_CHATBOX_SDK_APP_ID',
           'MODULES_FOOTER_FACEBOOK_CHATBOX_SDK_CHAT_OPEN_ON_LOAD',
           'MODULES_FOOTER_FACEBOOK_CHATBOX_SORT_ORDER'
          ];
  }
}

