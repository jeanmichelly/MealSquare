<?php

namespace MealSquare\CommonBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;

class LocaleController extends Controller {
	 /**
     * Change the locale for the current user
     *
     * @param String $language
     * @return array
     *
     */
    public function setLocaleAction($language = null)
    {
        if($language != null)
        {
            // On enregistre la langue en session
            $this->get('session')->set('_locale', $language);
        }

        // on tente de rediriger vers la page d'origine
        $url = $this->container->get('request')->headers->get('referer');
        if(empty($url))
        {
            $url = $this->container->get('router')->generate('meal_square_common_homepage');
        }

        return new RedirectResponse($url);
    }
}