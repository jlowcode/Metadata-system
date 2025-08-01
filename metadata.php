<?php

/**
 * @package     Joomla.Plugin
 * @subpackage  System.metadata
 *
 * @copyright   (C) 2025 Open Source Matters, Inc. <https://www.joomla.org>
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access
defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Uri\Uri;
use Joomla\CMS\Plugin\CMSPlugin;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Application\ConsoleApplication;

/**
 * 	Plugin that displays metadata when your URL is shared
 * 
 * @package     	Joomla.Plugin
 * @subpackage  	System.metadata
 */
class PlgSystemMetadata extends CMSPlugin 
{
    public function __construct(&$subject, $config = array()) 
    {
        parent::__construct($subject, $config);
    }

    /**
     * Check if user can view element read-only OR view in site view
     *
     * @param   	string 		$view 		
     *
     * @return  	bool
     */
    public function canView($view = 'list') 
    {
        return true;
    }

    /**
     * Executed when reloading the home page and fetching the title, description and image for use in the metadata.
     * 
     * @return 		void
    */
    public function onContentPrepare()
    {
        $app = Factory::getApplication();

        if ($app instanceof ConsoleApplication) {
            return;
        }

        $image = $this->params->get("image_site");
        $image = HTMLHelper::_("image", $image, $image, null, false, 1);
        $image = Uri::root() . ltrim(explode('#', $image)[0], '/');

        $title = $this->params->get("title");
        $title = strip_tags($title);

        $description = $this->params->get("description");
        $description = strip_tags($description);
        
        $this->setOgTags($title, $description, $image);
        $this->setTwitterTags($title, $description, $image);
        $this->setTags($title, $description, $image);
    }
    
    /**
     * Sets the Open Graph meta tags for page title, description, and type.
     * 
     * @param   string  $title        The title to set in og:title
     * @param   string  $description  The description to set in og:description
     * @param   string  $image        The image URL to set in og:image
     * 
     * @return  void
     */
    private function setOgTags($title, $description, $image)
    {
        $doc = Factory::getApplication()->getDocument();

        if ($doc->getMetaData('og:title', 'property') == '') {
            $doc->setMetaData('og:title', $title, 'property');
        }

        if ($doc->getMetaData('og:description', 'property') == '') {
            $doc->setMetaData('og:description', $description, 'property');
        }

        if ($doc->getMetaData('og:type', 'property') == '') {
            $doc->setMetaData('og:type', 'website', 'property');
        }

        if ($image && $doc->getMetaData('og:image', 'property') == '') {
            $doc->setMetaData('og:image', $image, 'property');
        }
    }

    /**
     * Sets the Twitter meta tags for card type, title, and description.
     * 
     * @param   string  $title        The title to set in twitter:title
     * @param   string  $description  The description to set in twitter:description
     * @param   string  $image        The image URL to set in twitter:image
     * 
     * @return  void
     */
    private function setTwitterTags($title, $description, $image)
    {
        $doc = Factory::getApplication()->getDocument();

        if ($doc->getMetaData('twitter:title', 'property') == '') {
            $doc->setMetaData('twitter:title', $title, 'property');
        }

        if ($doc->getMetaData('twitter:description', 'property') == '') {
            $doc->setMetaData('twitter:description', $description, 'property');
        }

        if ($doc->getMetaData('twitter:card', 'property') == '') {
            $doc->setMetaData('twitter:card', 'summary_large_image', 'property');
        }

        if ($image && $doc->getMetaData('twitter:image', 'property') == '') {
            $doc->setMetaData('twitter:image', $image, 'property');
        }
    }

    /**
     * Sets the standard meta tags for page title, description, and image.
     * 
     * @param   string  $title        The title to set in meta title
     * @param   string  $description  The description to set in meta description
     * @param   string  $image        The image URL to set in meta image
     * 
     * @return  void
     */
    private function setTags($title, $description, $image)
    {
        $doc = Factory::getApplication()->getDocument();

        if ($doc->getMetaData('title', 'property') == '') {
            $doc->setMetaData('title', $title, 'property');
        }

        if ($doc->getMetaData('description', 'property') == '') {
            $doc->setMetaData('description', $description, 'property');
        }

        if ($image && $doc->getMetaData('image', 'property') == '') {
            $doc->setMetaData('image', $image, 'property');
        }
    }
}