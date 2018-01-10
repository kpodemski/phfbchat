<?php
trait PrestaHomeConfiguratorBase
{
    public $options_prefix;
    protected $fields_form = array();

    /**
     * Render form using HelperForm
     * @return array HelperForm form
     */
    protected function renderForm()
    {
        $default_lang = (int)Configuration::get('PS_LANG_DEFAULT');
        $this->renderConfigurationForm();

        $helper = new HelperForm();
        $helper->module = $this;
        $helper->name_controller = $this->name;
        $helper->identifier = $this->identifier;
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        foreach (Language::getLanguages(false) as $lang) {
            $helper->languages[] = array(
                'id_lang' => $lang['id_lang'],
                'iso_code' => $lang['iso_code'],
                'name' => $lang['name'],
                'is_default' => ($default_lang == $lang['id_lang'] ? 1 : 0)
            );
        }

        $helper->currentIndex = AdminController::$currentIndex.'&configure='.$this->name;
        $helper->default_form_language = $default_lang;
        $helper->allow_employee_form_lang = $default_lang;
        $helper->toolbar_scroll = true;
        $helper->title = $this->displayName;
        $helper->submit_action = 'save'.$this->name;
        $helper->toolbar_btn =  array(
            'save' =>
            array(
                'desc' => $this->l('Save'),
                'href' => AdminController::$currentIndex.'&configure='.$this->name.'&save'.$this->name.'&token='.Tools::getAdminTokenLite('AdminModules'),
            )
        );
        $helper->tpl_vars = array(
            'fields_value' => $this->getConfigFieldsValues(),
            'languages' => $this->context->controller->getLanguages(),
            'id_language' => $this->context->language->id
        );

        return $helper->generateForm($this->fields_form);
    }

    /**
     * Get configuration field values
     * @return array
     */
    public function getConfigFieldsValues()
    {
        $id_shop = Shop::getContextShopID(true);
        $id_shop_group = Shop::getContextShopGroupID(true);

        $fields_values = array();
        foreach ($this->fields_form as $k => $f) {
            foreach ($f['form']['input'] as $i => $input) {
                if (isset($input['ignore']) && $input['ignore'] == true) {
                    continue;
                }

                if (isset($input['lang']) && $input['lang'] == true) {
                    foreach (Language::getLanguages(false) as $lang) {
                        $values = Tools::getValue($input['name'].'_'.$lang['id_lang'], (Configuration::hasKey($input['name'], $lang['id_lang']) ? Configuration::get($input['name'], $lang['id_lang'], (int)$id_shop_group, (int)$id_shop) : $input['default']));
                        $fields_values[$input['name']][$lang['id_lang']] = $values;
                    }
                } else {
                    if ($input['type'] == 'checkbox' && isset($input['values'])) {
                        $input['name'] = str_replace(array('[]'), array(''), $input['name']);

                        $values = (Configuration::hasKey($input['name'], null, (int)$id_shop_group, (int)$id_shop) ? Tools::jsonDecode(Configuration::get($input['name']), true) : $input['default']);

                        if (is_array($values)) {
                            foreach ($input['values']['query'] as $id_cms => $val) {
                                if (in_array($id_cms, $values)) {
                                    $fields_values[$input['name'].'[]_'.$id_cms] = $id_cms;
                                }
                            }
                        }
                    } else {
                        $values = Tools::getValue($input['name'], (Configuration::hasKey($input['name'], null, (int)$id_shop_group, (int)$id_shop) ? Configuration::get($input['name']) : $input['default']));
                        $fields_values[$input['name']] = $values;
                    }
                }
            }
        }

        $this->assignCustomConfigs($fields_values);

        return $fields_values;
    }

    /**
     * Batch update configuration fields
     * @return bool
     */
    public function batchUpdateConfigs()
    {
        foreach ($this->fields_form as $k => $f) {
            foreach ($f['form']['input'] as $i => $input) {
                $input['name'] = str_replace(array('[]'), array(''), $input['name']);

                if (isset($input['ignore']) && $input['ignore'] == true) {
                    continue;
                }

                if (isset($input['lang']) && $input['lang'] == true) {
                    $data = array();
                    foreach (Language::getLanguages(false) as $lang) {
                        $val = Tools::getValue($input['name'].'_'.$lang['id_lang'], $input['default']);
                        $data[$lang['id_lang']] = $val;
                    }

                    if (isset($input['callback']) && method_exists($this, $input['callback'])) {
                        $data[$lang['id_lang']] = $this->{$input['callback']}($data[$lang['id_lang']]);
                    }

                    Configuration::updateValue(trim($input['name']), $data, true);
                } else {
                    $val = Tools::getValue($input['name'], $input['default']);
                    if (isset($input['callback']) && method_exists($this, $input['callback'])) {
                        $val = $this->{$input['callback']}($val);
                    }
                    Configuration::updateValue($input['name'], $val, true);
                }
            }
        }

        $this->batchUpdateCustomConfigs();

        return true;
    }

    /**
     * Delete entire custom configuration
     * @return bool
     */
    public function deleteConfigs()
    {
        foreach ($this->fields_form as $k => $f) {
            foreach ($f['form']['input'] as $i => $input) {
                if (isset($input['ignore']) && $input['ignore'] == true) {
                    continue;
                }
                Configuration::deleteByName($input['name']);
            }
        }

        $this->deleteCustomConfigs();

        return true;
    }

    /**
     * Create array for a custom configs, this method could be used as an override
     * @param  $array &$fields_values
     * @return array
     */
    public function assignCustomConfigs(&$fields_values)
    {
        return array();
    }

    /**
     * Update custom configs
     * @return bool
     */
    public function batchUpdateCustomConfigs()
    {
        return true;
    }

    /**
     * Delete custom configs
     * @return bool
     */
    public function deleteCustomConfigs()
    {
        return true;
    }

    /**
     * Returns provided array as Json
     * @param  array $input
     * @return json
     */
    public function saveAsJson($array)
    {
        return Tools::jsonEncode($array);
    }
}
