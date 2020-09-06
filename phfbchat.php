<?php
/**
* 2007-2019 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@prestashop.com so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
*  @author    PrestaShop SA <contact@prestashop.com>
*  @copyright 2007-2019 PrestaShop SA
*  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*/

if (!defined('_PS_VERSION_')) {
    exit;
}

require __DIR__ . '/vendor/autoload.php';

class PhFbChat extends Module implements PrestaHomeConfiguratorInterface
{
    use PrestaHomeHelpers, PrestaHomeConfiguratorBase;

    public function __construct()
    {
        $this->name = 'phfbchat';
        $this->tab = 'front_office_features';
        $this->version = '1.1.6';
        $this->author = 'PrestaHome';
        $this->need_instance = 0;
        $this->bootstrap = true;

        parent::__construct();

        $this->displayName = $this->l('Facebook Messenger Chat plugin');
        $this->description = $this->l('Module to display Facebook Messenger chat on your store');

        $this->setOptionsPrefix('phfbchat');
    }

    public function setOptionsPrefix($custom = false)
    {
        $this->options_prefix = Tools::strtoupper(($custom ? $custom : $this->name)).'_';

        return $this;
    }

    public function install()
    {
        $this->renderConfigurationForm();
        $this->batchUpdateConfigs();

        if (file_exists(_PS_MODULE_DIR_.$this->name.'/init/my-install.php')) {
            require_once _PS_MODULE_DIR_.$this->name.'/init/my-install.php';
        }

        if (!parent::install() || !$this->registerHook('displayFooter')) {
            return false;
        }

        return true;
    }

    public function uninstall()
    {
        $this->renderConfigurationForm();
        $this->deleteConfigs();

        if (file_exists(_PS_MODULE_DIR_.$this->name.'/init/my-uninstall.php')) {
            require_once _PS_MODULE_DIR_.$this->name.'/init/my-uninstall.php';
        }

        return parent::uninstall();
    }

    public function getContent()
    {
        $this->renderConfigurationForm();
        $this->_html = '<h2>'.$this->displayName.'</h2>';

        if (Tools::isSubmit('save'.$this->name)) {
            $this->renderConfigurationForm();
            $this->batchUpdateConfigs();

            $this->_clearCache('*');
            $this->_html .= $this->displayConfirmation($this->l('Settings updated successfully.'));

        }
        return $this->_html . $this->renderForm();
    }

    public function renderConfigurationForm()
    {
        if ($this->fields_form) {
            return;
        }

        $fields_form = array(
            'form' => array(
                'legend' => array(
                    'title' => $this->l('Settings'),
                    'icon' => 'icon-cogs'
                ),

                'input' => array(

                    array(
                        'name' => 'separator',
                        'type' => 'html',
                        'html_content' => '
                            <div class="alert alert-info">
                            '.$this->l('To use Messenger Chat you need to add your full domain to allowed ones by going to: FB Page -> Settings -> Messenger -> White-listed domains').'
                            </div>'
                        ,
                        'ignore' => true
                    ),

                    array(
                        'type'  => 'text',
                        'lang'  => true,
                        'label' => $this->l('Localization:'),
                        'name'  => $this->options_prefix.'LOCALE',
                        'default' => 'en_US',
                        'desc' => $this->l('for example en_US or es_ES or pl_PL'),
                    ),

                    array(
                        'type'  => 'text',
                        'lang'  => false,
                        'label' => $this->l('Page ID:'),
                        'name'  => $this->options_prefix.'PAGE_ID',
                        'default' => '',
                        'desc' => $this->l('You can use https://commentpicker.com/find-facebook-id.php to find your FB Page ID'),
                        'validate' => 'isUnsignedInt',
                    ),

                    array(
                        'type'  => 'text',
                        'lang'  => true,
                        'label' => $this->l('Logged in message:'),
                        'name'  => $this->options_prefix.'LOGGED_IN',
                        'default' => '',
                        'desc' => $this->l('The message displayed on the chat box for logged in users, leave empty to display default text'),
                        'validate' => 'isAnything',
                    ),
                    array(
                        'type'  => 'text',
                        'lang'  => true,
                        'label' => $this->l('Logged out message:'),
                        'name'  => $this->options_prefix.'LOGGED_OUT',
                        'default' => '',
                        'desc' => $this->l('The message displayed on the chat box for logged out users, leave empty to display default text'),
                        'validate' => 'isAnything',
                    ),
                    array(
                        'type'  => 'color',
                        'label' => $this->l('Theme color:'),
                        'name'  => $this->options_prefix.'THEME_COLOR',
                        'default' => '#0084FF',
                        'desc' => $this->l('The color of the chat bubble theme'),
                        'validate' => 'isAnything',
                    ),
                    array(
                        'type'  => 'text',
                        'label' => $this->l('Dialog delay:'),
                        'name'  => $this->options_prefix.'DIALOG_DELAY',
                        'default' => '0',
                        'desc' => $this->l('The amount of seconds the dialog stays open.'),
                        'validate' => 'isUnsignedInt',
                    ),
                    array(
                        'name' => 'separator',
                        'type' => 'html',
                        'html_content' => '<h2>'.$this->l('Troubleshooting').'</h2><div class="alert alert-info">'.$this->l('If you have any issues create new ticket on https://github.com/kpodemski/phfbchat/issues').'</div>',
                        'ignore' => true
                    ),

                    array(
                        'type' => 'switch',
                        'label' => $this->l('Initialize Facebook API?'),
                        'name' => $this->options_prefix.'FB_API',
                        'desc' => $this->l('From 1.1.0 there is not really a chance that you\'d like to turn off this option.'),
                        'values' => array(
                            array(
                                'id' => 'active_on',
                                'value' => 1,
                                'label' => $this->l('Yes')
                            ),
                            array(
                                'id' => 'active_off',
                                'value' => 0,
                                'label' => $this->l('No')
                            )
                        ),
                        'default' => '1'
                    ),

                    array(
                        'name' => 'separator',
                        'type' => 'html',
                        'html_content' => '<div><a href="http://www.prestahome.com" title="" target="_blank"><img style="max-width: 800px;" alt="" src="'.$this->_path.'views/img/cover.jpg" /></a></div>',
                        'ignore' => true
                    ),
                ),

                'submit' => array(
                'title' => $this->l('Save'),
                'class' => 'btn btn-default')
            ),
        );

        $this->fields_form[] = $fields_form;
    }

    public function hookDisplayFooter()
    {
        $this->context->smarty->assign(array(
            'phfbchat_page_id'                 => Configuration::get($this->options_prefix.'PAGE_ID'),
            'phfbchat_locale'                  => Configuration::get($this->options_prefix.'LOCALE', (int) $this->context->language->id),
            'phfbchat_init'                    => Configuration::get($this->options_prefix.'FB_API'),
            'phfbchat_logged_in'               => Configuration::get($this->options_prefix.'LOGGED_IN', (int) $this->context->language->id),
            'phfbchat_logged_out'              => Configuration::get($this->options_prefix.'LOGGED_OUT', (int) $this->context->language->id),
            'phfbchat_theme_color'             => Configuration::get($this->options_prefix.'THEME_COLOR'),
            'phfbchat_greeting_dialog_delay'   => Configuration::get($this->options_prefix.'DIALOG_DELAY'),
        ));

        if (version_compare(_PS_VERSION_, '1.7', '>=')) {
            return $this->fetch('module:'.$this->name.'/views/templates/hook/footer.tpl');
        } else {
            return $this->display(__FILE__, 'views/templates/hook/footer.tpl');
        }
    }
}
