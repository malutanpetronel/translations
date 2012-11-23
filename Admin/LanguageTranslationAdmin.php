<?php
namespace Nbi\TranslationBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Knp\Menu\ItemInterface as MenuItemInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class LanguageTranslationAdmin extends Admin
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    protected $parentAssociationMapping = 'languageToken';

    /**
     * Configure the list
     *
     * @param \Sonata\AdminBundle\Datagrid\ListMapper $list list
     */
    protected function configureListFields(ListMapper $list)
    {
        $list
            ->addIdentifier('language')
            ->add('translation')
            ->add(
            '_action', 'actions', array(
                'actions' => array(
                    'edit' => array()
                )
            )
        );
    }

    /**
     * Configure the form
     *
     * @param FormMapper $formMapper formMapper
     */
    public function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('translation')
            ->add('catalogue')
            ->add('language')
        ;
    }

    public function postUpdate($object)
    {
        $this->clearLanguageCache();
    }

    /**
     * {@inheritdoc}
     */
    public function postPersist($object)
    {
        $this->clearLanguageCache();
    }

    /**
     * Remove language in every cache directories
     */
    private function clearLanguageCache(){
        $kernel = $this->container->get('kernel');
        $cacheDir = $kernel->getCacheDir();
        $finder = new \Symfony\Component\Finder\Finder();
        if (is_dir($cacheDir . "/translations")) {
            $finder->in(array($cacheDir . "/translations"))->files();
            foreach($finder as $file){
                unlink($file->getRealpath());
            }
        }
    }

}