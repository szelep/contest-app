<?php

namespace ContestBundle\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use ContestBundle\Entity\File;

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
        $directory = '';
        if ($media instanceof File) {
            $fileName = $media->getTempName();
            $extension = $media->getExtension();
            $directory = 'template';
        } else {
            $fileName = $media->getFile()->getTempName();
            $extension = $media->getFile()->getExtension();
            $directory = 'post';
        }

        $path = array(
            $this->mediaDir,
            $directory,
            $fileName . '.' . $extension
        );

        return implode(\DIRECTORY_SEPARATOR, $path);
    }
}