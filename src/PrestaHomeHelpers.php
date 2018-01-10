<?php
trait PrestaHomeHelpers
{
    /**
     * Return value with every available language
     * @param  string Value
     * @return array
     */
    public static function prepareValueForLangs($value)
    {
        $output = array();

        foreach (Language::getLanguages(false) as $lang) {
            $output[$lang['id_lang']] = $value;
        }

        return $output;
    }

    /**
     * Get CMS categories list for a HelperOption select field
     * @return array
     */
    public function getCMSCategoriesList()
    {
        $cms_categories = CMSCategory::getSimpleCategories($this->context->language->id);

        $output[] = array(
            'id_cms_category' => 0,
            'name' => $this->l('--- Choose ---')
        );

        return array_merge($output, $cms_categories);
    }

    /**
     * Get CMS pages list for a HelperOption checkbox/select field
     * @return array
     */
    public function getCMSPages()
    {
        $cms_pages = CMS::listCms($this->context->language->id);
        $output = array();

        foreach ($cms_pages as $cms_page) {
            $output[(int) $cms_page['id_cms']] = array(
                'id_cms' => (int) $cms_page['id_cms'],
                'val' => (int) $cms_page['id_cms'],
                'meta_title' => $cms_page['meta_title']
            );
        }

        return $output;
    }

    /**
     * Get customer groups for HelperOption checkbox/select field
     * @return array
     */
    public function getCustomerGroups()
    {
        $output = array();

        $output[] = array(
            'id_group' => 0,
            'name' => $this->l('--- Choose ---')
        );

        return array_merge($output, Group::getGroups($this->context->language->id));
    }
}
