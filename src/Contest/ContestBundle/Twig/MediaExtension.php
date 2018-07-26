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
            new TwigFilter('mediaThumbnail', array($this, 'mediaThumbnailFilter')),
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

    public function mediaThumbnailFilter($media)
    {
        $fileName = $media->getThumbnail()->getTempName();
        $extension = $media->getThumbnail()->getExtension();
        $path = array(
           \DIRECTORY_SEPARATOR . $this->mediaDir,
            'post',
            $fileName . '.' . $extension
        );

        return implode(\DIRECTORY_SEPARATOR, $path);
    }
}