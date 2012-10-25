<?php
namespace Nbi\TranslationBundle\Entity\Repository;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityRepository;

class LanguageRepository extends EntityRepository
{

    public function getLanguage($locale)
    {
        $query = $this->getEntityManager()
            ->createQuery("SELECT l FROM NbiTranslationBundle:Language l WHERE l . locale = :locale");
        $query->setParameter("locale", $locale);
        return $query->getSingleResult();
    }

    public function getLanguageId($locale)
    {
        $query = $this->getEntityManager()
            ->createQuery("SELECT l FROM NbiTranslationBundle:Language l WHERE l . locale = :locale");
        $query->setParameter("locale", $locale);
        $entity = $query->getSingleResult();
        if (!$entity) {
            $idLanguage = $entity->getId();
        }
        else {
            $idLanguage = 1;
        }
        return $idLanguage;
    }
}