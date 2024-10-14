<?php

namespace App\Tests\Controller;

use App\Entity\Emprunt;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class EmpruntControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private EntityManagerInterface $manager;
    private EntityRepository $repository;
    private string $path = '/admin/emprunt/';

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->manager = static::getContainer()->get('doctrine')->getManager();
        $this->repository = $this->manager->getRepository(Emprunt::class);

        foreach ($this->repository->findAll() as $object) {
            $this->manager->remove($object);
        }

        $this->manager->flush();
    }

    public function testIndex(): void
    {
        $this->client->followRedirects();
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Emprunt index');

        // Use the $crawler to perform additional assertions e.g.
        // self::assertSame('Some text on the page', $crawler->filter('.p')->first());
    }

    public function testNew(): void
    {
        $this->markTestIncomplete();
        $this->client->request('GET', sprintf('%snew', $this->path));

        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('Save', [
            'emprunt[dateEmprunt]' => 'Testing',
            'emprunt[dateRetour]' => 'Testing',
            'emprunt[datePrevue]' => 'Testing',
            'emprunt[abonne]' => 'Testing',
            'emprunt[livre]' => 'Testing',
        ]);

        self::assertResponseRedirects($this->path);

        self::assertSame(1, $this->repository->count([]));
    }

    public function testShow(): void
    {
        $this->markTestIncomplete();
        $fixture = new Emprunt();
        $fixture->setDateEmprunt('My Title');
        $fixture->setDateRetour('My Title');
        $fixture->setDatePrevue('My Title');
        $fixture->setAbonne('My Title');
        $fixture->setLivre('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Emprunt');

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): void
    {
        $this->markTestIncomplete();
        $fixture = new Emprunt();
        $fixture->setDateEmprunt('Value');
        $fixture->setDateRetour('Value');
        $fixture->setDatePrevue('Value');
        $fixture->setAbonne('Value');
        $fixture->setLivre('Value');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'emprunt[dateEmprunt]' => 'Something New',
            'emprunt[dateRetour]' => 'Something New',
            'emprunt[datePrevue]' => 'Something New',
            'emprunt[abonne]' => 'Something New',
            'emprunt[livre]' => 'Something New',
        ]);

        self::assertResponseRedirects('/admin/emprunt/');

        $fixture = $this->repository->findAll();

        self::assertSame('Something New', $fixture[0]->getDateEmprunt());
        self::assertSame('Something New', $fixture[0]->getDateRetour());
        self::assertSame('Something New', $fixture[0]->getDatePrevue());
        self::assertSame('Something New', $fixture[0]->getAbonne());
        self::assertSame('Something New', $fixture[0]->getLivre());
    }

    public function testRemove(): void
    {
        $this->markTestIncomplete();
        $fixture = new Emprunt();
        $fixture->setDateEmprunt('Value');
        $fixture->setDateRetour('Value');
        $fixture->setDatePrevue('Value');
        $fixture->setAbonne('Value');
        $fixture->setLivre('Value');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertResponseRedirects('/admin/emprunt/');
        self::assertSame(0, $this->repository->count([]));
    }
}
