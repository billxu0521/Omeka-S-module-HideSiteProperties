<?php

namespace HideSiteProperties\Controller\Admin;

use Laminas\View\Model\ViewModel;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\Form\Form;

class IndexController extends AbstractActionController
{
    public function indexAction()
    {
        $site = $this->currentSite();
        $siteSettings = $this->siteSettings();
        $view = new ViewModel();
        $form = $this->getForm(Form::class);
        if ($this->getRequest()->isPost()) {
            $params = $this->params()->fromPost();
            if (isset($params['propertyLabel'])) {
                $propertyLabel = $params['propertyLabel'];
            } else {
                $propertyLabel = [];
            }
            $siteSettings->set('hide_site_properties_properties', $propertyLabel);
        }
        
        $hiddenProperties = json_encode($siteSettings->get('hide_site_properties_properties'));

        $view->setVariable('form', $form);
        $view->setVariable('hiddenProperties', $hiddenProperties);

        return $view;
    }
}
