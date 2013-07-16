<?php
namespace Nbi\TranslationBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Knp\Menu\ItemInterface as MenuItemInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Sonata\AdminBundle\Admin\AdminInterface;



/**
 * BookTranslation Admin
 */
class LanguageTokenAdmin extends Admin
{

    /**
     * @var ContainerInterface
     */
    protected $container;

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    /**
     * @param \Sonata\AdminBundle\Datagrid\DatagridMapper $datagridMapper
     *
     * @return void
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('token')
            ->add('comment')
            ->add('unused')
        ;
    }

    /**
     * Configure the list
     *
     * @param \Sonata\AdminBundle\Datagrid\ListMapper $list list
     */
    protected function configureListFields(ListMapper $list)
    {
        $list
            ->addIdentifier('token')
            ->add('comment')
            ->add('unused')
            ->add('_action', 'actions', array(
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
            ->add('token')
            ->add('comment')
            ->add('unused')

        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function configureSideMenu(MenuItemInterface $menu, $action, AdminInterface $childAdmin = null)
    {
        if (!$childAdmin && !in_array($action, array('edit'))) {
            return;
        }

        $id = $this->getRequest()->get('id');

        $menu->addChild(
            'Edit',
            array('uri' => $this->generateUrl('edit', array('id' => $id)))
        );

        $menu->addChild(
            'Translations',
            array('uri' => $this->generateUrl('nbi.admin.language_translation.list', array('id' => $id)))
        );
    }

    public function postPersist($object)
    {
        $em = $this->container->get('doctrine.orm.default_entity_manager');
        $translations = $em->getRepository("NbiTranslationBundle:LanguageTranslation")->findByLanguageToken($object);

        if (count($translations) == 0) {
            foreach ($this->configurationPool->getContainer()->getParameter('locales') as $locale) {
                $translation = new \Nbi\TranslationBundle\Entity\LanguageTranslation();
                $translation->setTranslation($object->getToken());
                $language = $em->getRepository("NbiTranslationBundle:Language")->getLanguage($locale);
                $translation->setLanguage($language);
                $translation->setLanguageToken($object);
                $em->persist($translation);
            }
            $em->flush();
        }

    }
}