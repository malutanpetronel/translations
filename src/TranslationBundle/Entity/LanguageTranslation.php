<?php
namespace Nbi\TranslationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity(repositoryClass="Nbi\TranslationBundle\Entity\Repository\LanguageTranslationRepository")
 */
class LanguageTranslation
{

    /**
     * @ORM\Id @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    private $id;

    /**
     * @ORM\column(type="string", length=200)
     */
    private $catalogue = 'messages';

    /**
     * @ORM\column(type="text")
     */
    private $translation;

    /**
     * @ORM\ManyToOne(targetEntity="Nbi\TranslationBundle\Entity\Language", fetch="EAGER")
     */
    private $language;

    /**
     * @ORM\ManyToOne(targetEntity="Nbi\TranslationBundle\Entity\LanguageToken", fetch="EAGER")
     */
    private $languageToken;

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getCatalogue()
    {
        return $this->catalogue;
    }

    public function setCatalogue($catalogue)
    {
        $this->catalogue = $catalogue;
    }

    public function getTranslation()
    {
        return $this->translation;
    }

    public function setTranslation($translation)
    {
        $this->translation = $translation;
    }

    public function getLanguage()
    {
        return $this->language;
    }

    public function setLanguage($language)
    {
        $this->language = $language;
    }

    public function getLanguageToken()
    {
        return $this->languageToken;
    }

    public function setLanguageToken($languageToken)
    {
        $this->languageToken = $languageToken;
    }


    public function __toString()
    {
        return $this->getTranslation();
    }

}