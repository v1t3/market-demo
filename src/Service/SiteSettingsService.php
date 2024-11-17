<?php

namespace App\Service;

use App\Repository\SiteSettingsRepository;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

/**
 *
 */
class SiteSettingsService
{
    /**
     * @var SiteSettingsRepository
     */
    private $repository;
    /**
     * @var ParameterBagInterface
     */
    private $parameterBag;

    /**
     * @param SiteSettingsRepository $repository
     * @param ParameterBagInterface  $parameterBag
     */
    public function __construct(SiteSettingsRepository $repository, ParameterBagInterface $parameterBag)
    {
        $this->repository = $repository;
        $this->parameterBag = $parameterBag;
    }

    /**
     * @return string
     */
    public function getSiteLogo(): string
    {
        $settings = $this->repository->findAll()[0] ?? null;
        if ($settings && !empty($settings->getLogoFilename())) {
            return $this->parameterBag->get('uploads_images_url') . '/' . $settings->getLogoFilename();
        }

        return '';
    }

    /**
     * @return string
     */
    public function getSiteName(): string
    {
        $settings = $this->repository->findAll()[0] ?? null;
        if ($settings) {
            return $settings->getName();
        }

        return '';
    }
}
