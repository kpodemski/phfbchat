<?php
interface PrestaHomeConfiguratorInterface
{
    public function setOptionsPrefix();
    public function batchUpdateConfigs();
    public function deleteConfigs();
    public function renderConfigurationForm();
    public function getConfigFieldsValues();
}
