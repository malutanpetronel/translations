<?php
namespace Nbi\TranslationBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Knp\Menu\ItemInterface as MenuItemInterface;

class LanguageTranslationAdmin extends Admin
{
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

}