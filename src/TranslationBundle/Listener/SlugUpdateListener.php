<?php
namespace Nbi\TranslationBundle\Listener;

use Doctrine\ORM\Event\LifecycleEventArgs;
use Gedmo\Translatable\Translatable;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class SlugUpdateListener
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    public function postUpdate(LifecycleEventArgs $args)
    {
        $this->updateSlugs($args);
    }

    public function postPersist(LifecycleEventArgs $args)
    {
        $this->updateSlugs($args);
    }

    protected function updateSlugs(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        $em = $args->getEntityManager();
        if ($entity instanceof \Gedmo\Translatable\Translatable ) {
            $reflection = new \ReflectionClass(get_class($entity));
            $translationEntity = false;

            $reader = new \Doctrine\Common\Annotations\AnnotationReader();
            foreach ($reader->getClassAnnotations($reflection) as $annotation) {
                if ($annotation instanceof \Gedmo\Mapping\Annotation\TranslationEntity) {
                    $translationEntity = $annotation->class;
                }
            }
            try {
                if
                    ($reflection->getProperty('slug') &&
                    $reflection->getProperty('name') &&
                    $reflection->getProperty('translations') &&
//                    $args->hasChangedField('name') &&
                    $translationEntity !== false
                ) {

                    foreach ($entity->getTranslations() as $translation) {
                        $langTrans = false;
                        if ( $translation->getField() == 'name') {
                            $locale = $translation->getLocale();

                            foreach ($entity->getTranslations() as $trans) {
                                if ($trans->getField() == 'slug' && $trans->getLocale() == $locale) {
                                    $trans->setContent($this->slugify($translation->getContent()));
                                    $langTrans = true;
                                    if ($locale == $this->container->getParameter('locale')) {
                                        $entity->setSlug($this->slugify($translation->getContent()));
                                    }
                                }
                            }
                            if (!$langTrans) {
                                $t = new $translationEntity($locale, 'slug', $this->slugify($translation->getContent
                                    ()));
                                $em->persist($t);
                                if ($locale == $this->container->getParameter('locale')) {
                                    $entity->setSlug($this->slugify($translation->getContent()));
                                }
                                $entity->addTranslation($t);
                            }
                        }
                    }
                    $em->persist($entity);
                    $em->flush();
                }
            } catch (\Exception $e) {
            }
        }
    }

    protected function slugify($text) {
        $changes = array(
            "Є"=> "YE",
            "І"=> "I",
            "Ѓ"=> "G",
            "і"=> "i",
            "№"=> "#",
            "є"=> "ye",
            "ѓ"=> "g",
            "А"=> "A",
            "Б"=> "B",
            "В"=> "V",
            "Г"=> "G",
            "Д"=> "D",
            "Е"=> "E",
            "Ё"=> "YO",
            "Ж"=> "ZH",
            "З"=> "Z",
            "И"=> "I",
            "Й"=> "J",
            "К"=> "K",
            "Л"=> "L",
            "М"=> "M",
            "Н"=> "N",
            "О"=> "O",
            "П"=> "P",
            "Р"=> "R",
            "С"=> "S",
            "Т"=> "T",
            "У"=> "U",
            "Ф"=> "F",
            "Х"=> "X",
            "Ц"=> "C",
            "Ч"=> "CH",
            "Ш"=> "SH",
            "Щ"=> "SHH",
            "Ъ"=> "'",
            "Ы"=> "Y",
            "Ь"=> "",
            "Э"=> "E",
            "Ю"=> "YU",
            "Я"=> "YA",
            "а"=> "a",
            "б"=> "b",
            "в"=> "v",
            "г"=> "g",
            "д"=> "d",
            "е"=> "e",
            "ё"=> "yo",
            "ж"=> "zh",
            "з"=> "z",
            "и"=> "i",
            "й"=> "j",
            "к"=> "k",
            "л"=> "l",
            "м"=> "m",
            "н"=> "n",
            "о"=> "o",
            "п"=> "p",
            "р"=> "r",
            "с"=> "s",
            "т"=> "t",
            "у"=> "u",
            "ф"=> "f",
            "х"=> "x",
            "ц"=> "c",
            "ч"=> "ch",
            "ш"=> "sh",
            "щ"=> "shh",
            "ъ"=> "",
            "ы"=> "y",
            "ь"=> "",
            "э"=> "e",
            "ю"=> "yu",
            "я"=> "ya",
            "«"=> "",
            "»"=> "",
            "—"=> "-"
        );

        $text = str_replace(array_keys($changes), array_values($changes), $text);

        return \Gedmo\Sluggable\Util\Urlizer::urlize($text);

    }
}