<?php

namespace App\Tests\Controller;

use App\Entity\Abonne;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class AbonneControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private EntityManagerInterface $manager;
    private EntityRepository $repository;
    private string $path = '/admin/abonne/';

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->manager = static::getContainer()->get('doctrine')->getManager();
        $this->repository = $this->manager->getRepository(Abonne::class);

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
        self::assertPageTitleContains('Abonne index');

        // Use the $crawler to perform additional assertions e.g.
        // self::assertSame('Some text on the page', $crawler->filter('.p')->first());
    }

    public function testNew(): void
    {
        $this->markTestIncomplete();
        $this->client->request('GET', sprintf('%snew', $this->path));

        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('Save', [
            'abonne[pseudo]' => 'Testing',
            'abonne[roles]' => 'Testing',
            'abonne[password]' => 'Testing',
            'abonne[prenom]' => 'Testing',
            'abonne[nom]' => 'Testing',
            'abonne[adresse]' => 'Testing',
            'abonne[naissance]' => 'Testing',
        ]);

        self::assertResponseRedirects($this->path);

        self::assertSame(1, $this->repository->count([]));
    }

    public function testShow(): void
    {
        $this->markTestIncomplete();
        $fixture = new Abonne();
        $fixture->setPseudo('My Title');
        $fixture->setRoles('My Title');
        $fixture->setPassword('My Title');
        $fixture->setPrenom('My Title');
        $fixture->setNom('My Title');
        $fixture->setAdresse('My Title');
        $fixture->setNaissance('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Abonne');

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): void
    {
        $this->markTestIncomplete();
        $fixture = new Abonne();
        $fixture->setPseudo('Value');
        $fixture->setRoles('Value');
        $fixture->setPassword('Value');
        $fixture->setPrenom('Value');
        $fixture->setNom('Value');
        $fixture->setAdresse('Value');
        $fixture->setNaissance('Value');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'abonne[pseudo]' => 'Something New',
            'abonne[roles]' => 'Something New',
            'abonne[password]' => 'Something New',
            'abonne[prenom]' => 'Something New',
            'abonne[nom]' => 'Something New',
            'abonne[adresse]' => 'Something New',
            'abonne[naissance]' => 'Something New',
        ]);

        self::assertResponseRedirects('/admin/abonne/');

        $fixture = $this->repository->findAll();

        self::assertSame('Something New', $fixture[0]->getPseudo());
        self::assertSame('Something New', $fixture[0]->getRoles());
        self::assertSame('Something New', $fixture[0]->getPassword());
        self::assertSame('Something New', $fixture[0]->getPrenom());
        self::assertSame('Something New', $fixture[0]->getNom());
        self::assertSame('Something New', $fixture[0]->getAdresse());
        self::assertSame('Something New', $fixture[0]->getNaissance());
    }

    public function testRemove(): void
    {
        $this->markTestIncomplete();
        $fixture = new Abonne();
        $fixture->setPseudo('Value');
        $fixture->setRoles('Value');
        $fixture->setPassword('Value');
        $fixture->setPrenom('Value');
        $fixture->setNom('Value');
        $fixture->setAdresse('Value');
        $fixture->setNaissance('Value');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertResponseRedirects('/admin/abonne/');
        self::assertSame(0, $this->repository->count([]));
    }
}
