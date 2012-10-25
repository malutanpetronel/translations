<?php
namespace Nbi\TranslationBundle\Service;

use Symfony\Component\Translation\Loader\LoaderInterface;
use Doctrine\ORM\EntityManager;
use Symfony\Component\DependencyInjection\ContainerInterface;

class DBLoader implements LoaderInterface
{
    private $transaltionRepository;

    private $languageRepository;

    private $tokenRepository;

    private $container;

    /**
     * @param EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager)
    {
        $this->transaltionRepository = $entityManager->getRepository("NbiTranslationsBundle:LanguageTranslation");
        $this->languageRepository = $entityManager->getRepository("NbiTranslationsBundle:Language");
        $this->tokenRepository = $entityManager->getRepository("NbiTranslationsBundle:LanguageToken");

    }

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
        $this->container->get('ladybug')->log(111);
    }

    function load($resource, $locale, $domain = 'messages')
    {
        //Load on the db for the specified local
        $language = $this->languageRepository->getLanguageId($locale);

        $translations = $this->transaltionRepository->getTranslations($language, $domain);

        $catalogue = new \Symfony\Component\Translation\MessageCatalogue($locale);

        /**
         * @var $translation Nbi\Transl\Service
         */
        foreach ($translations as $translation) {
            $catalogue->set($translation->getLanguageToken()->getToken(), $translation->getTranslation(), $domain);
        }

        return $catalogue;
    }
}