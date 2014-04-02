<?php
namespace Nbi\TranslationBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

use Nbi\TranslationBundle\Entity\Language;
use Nbi\TranslationBundle\Entity\LanguageToken;
use Nbi\TranslationBundle\Entity\LanguageTranslation;


class TranslationsCommand extends ContainerAwareCommand
{
    private $targetPath;
    private $transaltionRepository;
    private $languageRepository;
    private $tokenRepository;
    private $serializer;

    protected function configure()
    {
        $this
            ->setName('nbi:translations')
            ->setDescription('Dumps translations to the filesystem')
        ;
    }

    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        parent::initialize($input, $output);

        $this->targetPath = sprintf('%s/Resources/translations/messages.', $this
                ->getContainer()
                ->getParameter('kernel.root_dir'))
        ;

        $entityManager = $this->getContainer()->get('doctrine.orm.entity_manager');

        $this->transaltionRepository = $entityManager->getRepository("NbiTranslationBundle:LanguageTranslation");
        $this->languageRepository = $entityManager->getRepository("NbiTranslationBundle:Language");
        $this->tokenRepository = $entityManager->getRepository("NbiTranslationBundle:LanguageToken");
        $this->serializer = $this->getContainer()->get('jms_serializer');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('Dumping search auto complete');
        $output->writeln('');

        $languages = $this->languageRepository->findAll();
        foreach ($languages as $language) {
            $this->doDump($input, $output, $language);
        }

    }

    private function doDump(InputInterface $input, OutputInterface $output, Language $language)
    {
        $target = $this->targetPath . ($language->getLocale()) . '.yml';
        if (!is_dir($dir = dirname($target))) {
            $output->writeln('<info>[dir+]</info>  ' . $dir);
            if (false === @mkdir($dir, 0777, true)) {
                throw new \RuntimeException('Unable to create directory ' . $dir);
            }
        }

        $output->writeln('<info>[file+]</info> ' . $target);

        $data = array();
        //Load on the db for the specified local
        /** @var $translation LanguageTranslation */
        $translations = $this->transaltionRepository->getTranslations($language, 'messages');

        foreach ($translations as $translation) {
            if ($translation->getLanguageToken()->getUnused()) {
                $data[$translation->getLanguageToken()->getToken()] = $translation->getLanguageToken()->getToken();
            } else {
                $data[$translation->getLanguageToken()->getToken()] = $translation->getTranslation();
            }
        }

        $content = $this->serializer->serialize($data, 'yml');

        if (false === @file_put_contents($target, $content)) {
            throw new \RuntimeException('Unable to write file ' . $target);
        }

        $target = str_replace('messages.', 'FOSUserBundle.', $target);
        if (false === @file_put_contents($target, $content)) {
            throw new \RuntimeException('Unable to write file ' . $target);
        }
    }
}
