<?php
namespace Nbi\TranslationBundle\Entity\Repository;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityRepository;

class LanguageTranslationRepository extends EntityRepository
{

    /**
     * Return all translations for specified token
     *
     * @param        $language
     * @param string $catalogue
     *
     * @return array
     * @internal param \Nbi\TranslationBundle\Entity\type $token
     * @internal param \Nbi\TranslationBundle\Entity\type $domain
     */
    public function getTranslations($language, $catalogue = "messages")
    {
        $query = $this
            ->getEntityManager()->createQuery(
            "SELECT t FROM NbiTranslationBundle:LanguageTranslation t
                WHERE t.language = :language AND t.catalogue = :catalogue"
        );
        $query->setParameter("language", $language);
        $query->setParameter("catalogue", $catalogue);

        return $query->getResult();
    }
}