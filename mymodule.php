<?php
if (!defined('_PS_VERSION_')) {
    exit;
}

class MyModule extends Module
{
    public function __construct()
    {
        $this->name = 'mymodule';
        $this->tab = 'front_office_features';
        $this->version = '1.0.0';
        $this->author = 'Amir B.N';
        $this->need_instance = 0;
        $this->ps_versions_compliancy = [
            'min' => '1.6',
            'max' => '1.7.99',
        ];
        $this->bootstrap = true;

        parent::__construct();

        $this->displayName = $this->l('My module');
        $this->description = $this->l('Description of my module.');

        $this->confirmUninstall = $this->l('Are you sure you want to uninstall?');

        if (!Configuration::get('mymodule')) {
            $this->warning = $this->l('No name provided');
        }
    }

    public function install()
    {
        return (
            parent::install()
            && $this->registerHook('leftColumn')
            && $this->registerHook('displayHome')
            && $this->registerHook('rightColumn')
            && $this->registerHook('displayCarrierExtraContent')
            && $this->registerHook('actionFrontControllerSetMedia')
            && Configuration::updateValue('mymodule_config', 'name of my module')
        );
    }

    public function uninstall()
    {
        return (
            parent::uninstall()
            && Configuration::deleteByName('mymodule_config')
        );
    }

    public function getContent()
    {
        $output ="";

        if (Tools::isSubmit('submit'.$this->name)) {
            $configValue = (string) Tools::getValue('module_input');
            // check that the value is valid
            if (empty($configValue) || !Validate::isGenericName($configValue)) {
                // invalid value, show an error
                $output = $this->displayError($this->l('Invalid Configuration value'));
            } else {
                // value is ok, update it and display a confirmation message
                Configuration::updateValue('mymodule', $configValue);
                $output = $this->displayConfirmation($this->l('Settings updated'));
            }
        }
        return $output . $this->displayForm();
    }

    public function displayForm()
    {
        // Init Fields form array
        $form = [
        'form' => [
            'legend' => [
                'title' => $this->l('Settings'),
            ],
            'input' => [
                [
                    'type' => 'text',
                    'label' => $this->l('Configuration value'),
                    'name' => 'module_input',
                    'size' => 20,
                    'required' => true,
                ],
            ],
            'submit' => [
                'title' => $this->l('Save'),
                'class' => 'btn btn-default pull-right',
            ],
        ],
    ];

        $helper = new HelperForm();

        // Module, token and currentIndex
        $helper->table = $this->table;
        $helper->name_controller = $this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->currentIndex = AdminController::$currentIndex . '&' . http_build_query(['configure' => $this->name]);
        $helper->submit_action = 'submit' . $this->name;

        // Default language
        $helper->default_form_language = (int) Configuration::get('PS_LANG_DEFAULT');

        // Load current value into the form
        $helper->fields_value['module_input'] = Tools::getValue('mymodule', Configuration::get('mymodule_config'));

        return $helper->generateForm([$form]);
    }

    public function hookDisplayLeftColumn($params)
    {
        $this->context->smarty->assign([
            'my_module_name'=>Configuration::get('mymodule_config'),
            'my_module_link'=>$this->context->link->getModuleLink('mymodule', 'display')
        ]);
        return $this->display(__FILE__, 'mymodule.tpl');
    }

    public function hookDisplayHome($params)
    {
        $this->context->smarty->assign([
            'my_module_name'=>Configuration::get('mymodule_config'),
            'my_module_link'=>$this->context->link->getModuleLink('mymodule', 'display')
        ]);
        return $this->display(__FILE__, 'mymodule.tpl');
    }

    public function hookActionFrontControllerSetMedia()
    {
        $this->context->controller->registerStylesheet(
            'mymodule',
            $this->_path.'views/css/mymodule.css',
            [
                'media' => 'all',
                'priority' => 1000,
            ]
        );

        $this->context->controller->registerJavascript(
            'mymodule',
            $this->_path.'views/js/mymodule.js',
            [
                'position' => 'top',
                'priority' => 200,
            ]
        );
    }
    public function hookDisplayCarrierExtraContent() {
        return '<p>test</p>';
    }
}
