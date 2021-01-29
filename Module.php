<?php

namespace HideSiteProperties;

use HideSiteProperties\Form\ConfigForm;
use Omeka\Module\AbstractModule;
use Laminas\ServiceManager\ServiceLocatorInterface;
use Laminas\Mvc\Controller\AbstractController;
use Laminas\View\Renderer\PhpRenderer;
use Laminas\EventManager\SharedEventManagerInterface;
use Laminas\Mvc\MvcEvent;
use Laminas\EventManager\Event;
use Omeka\Permissions\Acl;

class Module extends AbstractModule
{
    public function onBootstrap(MvcEvent $event)
    {
        parent::onBootstrap($event);

        $acl = $this->getServiceLocator()->get('Omeka\Acl');
        $acl->allow(
            [
                Acl::ROLE_EDITOR,
                Acl::ROLE_GLOBAL_ADMIN,
                Acl::ROLE_SITE_ADMIN,
            ],
            ['HideSiteProperties\Controller\Admin\Index']
            );
    }

    public function uninstall(ServiceLocatorInterface $serviceLocator)
    {
        $logger = $serviceLocator->get('Omeka\Logger');
        $settings = $serviceLocator->get('Omeka\Settings');
        $settings->delete('hide_site_properties_properties');
        $settings->delete('hide_site_properties_use_globals');

        $api = $serviceLocator->get('Omeka\ApiManager');
        $sites = $api->search('sites', [])->getContent();
        $siteSettings = $serviceLocator->get('Omeka\Settings\Site');

        foreach ($sites as $site) {
            $siteSettings->setTargetId($site->id());
            $siteSettings->delete('hide_site_properties_properties');
        }
    }

    public function upgrade($oldVersion, $newVersion, ServiceLocatorInterface $serviceLocator)
    {
        //fix the double json encoding that was stored
        if (version_compare($oldVersion, '0.2.1-alpha', '<')) {
            $settings = $serviceLocator->get('Omeka\Settings');
            $globalProperties = json_decode($settings->get('hide_site_properties_properties'));
            $settings->set('hide_site_properties_properties', $globalProperties);

            $api = $serviceLocator->get('Omeka\ApiManager');
            $sites = $api->search('sites', [])->getContent();
            $siteSettings = $serviceLocator->get('Omeka\Settings\Site');

            foreach ($sites as $site) {
                $siteSettings->setTargetId($site->id());
                $currentSiteSettings = json_decode($siteSettings->get('hide_site_properties_properties'));
                $siteSettings->set('hide_site_properties_properties', $currentSiteSettings);
            }
        }
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function attachListeners(SharedEventManagerInterface $sharedEventManager)
    {
       
        $sharedEventManager->attach(
            '*',
            'rep.resource.display_values',
            [$this, 'filterDisplayValues']
        );
    }

    public function handleConfigForm(AbstractController $controller)
    {
        $params = $controller->params()->fromPost();
        echo json_encode($params['propertyLabel']);

        if (isset($params['propertyLabel'])) {
            $property = $params['propertyLabel'];
        } else {
            $property = [];
        }
        
        $globalSettings = $this->getServiceLocator()->get('Omeka\Settings');
        $globalSettings->set('hide_site_properties_properties', $property);
        $globalSettings->set('hide_site_properties_use_globals', $params['hide_site_properties_use_globals']);
        
    }

    public function getConfigForm(PhpRenderer $renderer)
    {
        $globalSettings = $this->getServiceLocator()->get('Omeka\Settings');
        $hiddenProperties = json_encode($globalSettings->get('hide_site_properties_properties'));
        $escape = $renderer->plugin('escapeHtml');
        $translator = $this->getServiceLocator()->get('MvcTranslator');
        $html = '';
        $html .= "<script type='text/javascript'>
        var hiddenProperties = $hiddenProperties;
        </script>
        ";
        $formElementManager = $this->getServiceLocator()->get('FormElementManager');
        $form = $formElementManager->get(ConfigForm::class, []);
        $html .= "<p>" . $translator->translate("If checked, the properties selected below will be linked on the admin side, overriding all site-specific settings. Each site's own settings will be reflected on the public side. Otherwise, the admin side will reflect the aggregated settings for all sites; anything selected to be a link in any site will be a link on the admin side.") . "</p>";
        $html .= $renderer->formCollection($form, false);
        $html .= "<div id='hide-site-properties-properties'><p>" . $escape($translator->translate('Choose properties from the sidebar to be searchable on the admin side.')) . '</p></div>';
        $renderer->headScript()->appendFile($renderer->assetUrl('js/hide-site-properties.js', 'HideSiteProperties'));
        $renderer->headLink()->appendStylesheet($renderer->assetUrl('css/hide-site-properties.css', 'HideSiteProperties'));
        $renderer->htmlElement('body')->appendAttribute('class', 'sidebar-open');
        $selectorHtml = $renderer->propertySelector($translator->translate('Select properties to be searchable'));
        $html .= "<div class='sidebar active'>$selectorHtml</div>";

        return $html;
    }
    
    public function filterDisplayValues(Event $event)
    {
        
        $routeMatch = $this->getServiceLocator()->get('Application')
                        ->getMvcEvent()->getRouteMatch();
        $hiddenProperties = [];

        $globalSettings = $this->getServiceLocator()->get('Omeka\Settings');
        $globalcheck = $globalSettings->get('hide_site_properties_use_globals');
        
        $globalSettings = $this->getServiceLocator()->get('Omeka\Settings');
        if ($globalSettings->get('hide_site_properties_use_globals')) {
            $hiddenProperties = $globalSettings->get('hide_site_properties_properties', []);
        } else {
            $api = $this->getServiceLocator()->get('Omeka\ApiManager');
            $sites = $api->search('sites', [])->getContent();
            $siteSettings = $this->getServiceLocator()->get('Omeka\Settings\Site');
            $hiddenProperties = [];
            foreach ($sites as $site) {
                $siteSettings->setTargetId($site->id());
                $currentSettings = $siteSettings->get('hide_site_properties_properties', []);
                $hiddenProperties = array_merge($currentSettings, $hiddenProperties);
            }
        }
        
        $values = $event->getParams()['values'];
        
        foreach ($hiddenProperties as $property) {
            unset($values[$property]);
        }
        $event->setParam('values', $values);
    }

}
