<?php

namespace App;

use App\Entity\ExtraDetails;
use Doctrine\ORM\EntityManager;
use GuzzleHttp\Client;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class PopulateCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('populate:github')
            ->setDescription('Collect data from GitHub.')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /** @var EntityManager $em */
        $em = $this->getContainer()->get('doctrine')->getManager();
        $client = new Client(['base_uri' => 'https://api.github.com/']);

        $since = 0;

        while ($since <= 120) {
            $response = $client->request('GET', 'users', ['query' => ['since' => $since]]);
            $result = json_decode($response->getBody()->getContents(), true);

            foreach ($result as $item) {
                $em->persist(new ExtraDetails($item['id'], $item['login']));
            }

            $since += 30;
        }

        $em->flush();
    }

}