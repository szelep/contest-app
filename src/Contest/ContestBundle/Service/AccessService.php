<?php

namespace ContestBundle\Service;

use ContestBundle\Entity\Post;
use ContestBundle\Entity\File;
use ContestBundle\Entity\Contest;
use Symfony\Component\HttpFoundation\JsonResponse;

class AccessService
{

    public function findAllowedFileTypes(Post $post)
    {
        $allowedFiles = $post->getContest()->getAllowedFiles();
        $allowedTypesArray = array();

        foreach ($allowedFiles as $allowedFile) {
            $allowedTypesArray[] = $allowedFile->getValue();
        }

        return $allowedTypesArray;
    }

    public function checkIsFileAllowedInContest($mimeType, Post $post)
    {
        $allowedTypes = $this->findAllowedFileTypes($post);

        if (in_array($mimeType, $allowedTypes)) {
            return true;
        } else {
            return false;
        }
    }
}