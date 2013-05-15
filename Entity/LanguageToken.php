<?php
namespace Nbi\TranslationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass="Nbi\TranslationBundle\Entity\Repository\LanguageTokenRepository")
 * @ORM\HasLifecycleCallbacks()
 * @UniqueEntity("token")
 */
class LanguageToken
{

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    private $id;

    /**
     * @ORM\column(type="string", length=200, unique=true)
     */
    private $token;

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getToken()
    {
        return $this->token;
    }

    public function setToken($token)
    {
        $this->token = $token;
    }

    public function __toString()
    {
        return (string) $this->getToken();
    }
}