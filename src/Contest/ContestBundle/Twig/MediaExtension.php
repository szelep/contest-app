<?php

namespace ContestBundle\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class MediaExtension extends AbstractExtension
{
    public function __construct($mediaDir)
    {
        $this->mediaDir = $mediaDir;
    }

    public function getFilters()
    {
        return array(
            new TwigFilter('media', array($this, 'mediaFilter')),
        );
    }

    public function mediaFilter($media)
    {
        $fileName = $media->getFile()->getTempName();
        $extension = $media->getFile()->getExtension();
        $path = array(
            $this->mediaDir,
            'post',
            $fileName . '.' . $extension
        );

        return implode(\DIRECTORY_SEPARATOR, $path);
    }
}