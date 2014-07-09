<?php

namespace Ganguera\FrontendBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Description of EscarbadorCommand
 *
 * @author yandry
 */
class EscarbadorCommand extends ContainerAwareCommand {

    protected function configure() {
        $this->setName('escarbar')
                ->setDescription('Escarbador de computrabajo.com')
//                ->addArgument('name', InputArgument::OPTIONAL, 'Who do you want to greet?')
//                ->addOption('yell', null, InputOption::VALUE_NONE, 'If set, the task will yellin uppercase letters')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output) {
        $output->writeln("[+] Cargando test kernell ...");
        $kernel = $this->_createTestKernel();
        $client = $kernel->getContainer()->get('test.client');
        
        //$host = '192.168.56.102';
        //$server_headers = ['HTTP_HOST' => $host];
        //$client->setServerParameters($server_headers);
        $output->writeln("[+] Conectandose ...");

        $crawler = $client->request('GET', '/');
        $titulo = $crawler->filter('title')->text();
        //$output->writeln("Contenido: " . $client->getResponse()->getContent());
        $output->writeln("[+] Titulo: " . $titulo);
    }

    /**
     * createKernel
     *
     * @author  Joe Sexton <joe@webtipblog.com
     * @return  \AppKernel
     */
    private function _createTestKernel() {
        $rootDir = $this->getContainer()->get('kernel')->getRootDir();
        require_once( $rootDir . '/AppKernel.php' );
        $kernel = new \AppKernel('test', true);
        $kernel->boot();

        return $kernel;
    }

}