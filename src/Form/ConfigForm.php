<?php

namespace HideSiteProperties\Form;

use Laminas\Form\Form;

class ConfigForm extends Form
{
    protected $globalSettings;

    public function init()
    {
        $this->add([
            'type' => 'checkbox',
            'name' => 'hide_site_properties_use_globals',
            'options' => [
                        'label' => 'Use global configuration on admin side', // @translate
                    ],
            'attributes' => [
                'checked' => $this->globalSettings->get('hide_site_properties_use_globals') ? 'checked' : '',
                'id' => 'hide-site-properties-use-globals',
            ],
        ]);

    }

    public function setGlobalSettings($globalSettings)
    {
        $this->globalSettings = $globalSettings;
    }
}
