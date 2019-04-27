<?php


namespace App\Tests\functional;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileUploadTest extends WebTestCase
{
    public function testCanUpload()
    {
        $uploadedFile = tempnam(sys_get_temp_dir(), '');
        copy(__DIR__ . '/file.txt', $uploadedFile);

        $client = static::createClient();
        $client->request('POST', '/', [], [
            'file' => new UploadedFile($uploadedFile, 'file.txt'),
        ]);
        static::assertTrue($client->getResponse()->isRedirect());


        $fsRoot = $client->getContainer()->getParameter('kernel.root_dir') . '/../public';
        static::assertFileEquals(
            __DIR__ . '/file.txt',
            $fsRoot . $client->getResponse()->headers->get('Location')
        );
    }

    public function testValidationErrorWhenFileIsNotSpecified()
    {
        $client = static::createClient();
        $client->request('POST', '/');
        static::assertTrue($client->getResponse()->isSuccessful());
    }
}
