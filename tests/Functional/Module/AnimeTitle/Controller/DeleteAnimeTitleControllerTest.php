<?php

namespace App\Tests\Functional\Module\AnimeTitle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\Module\AnimeTitle\Service\AnimeTitleService;
use Symfony\Component\HttpFoundation\Response;

class DeleteAnimeTitleControllerTest extends WebTestCase {
    
    public function testDeleteAnimeTitle() 
    {
        $client = static::createClient();
        $container = $client->getContainer();
    
        $animeTitleServiceMock = $this->createMock(AnimeTitleService::class);

        $animeTitleServiceMock->expects($this->once())
        ->method('delete')
        ->willReturn('AnimeTitle with ID 1 has been successfully deleted.');

        $container->set(AnimeTitleService::class, $animeTitleServiceMock);

        $client->request('DELETE', '/anime_title/1');

        $response = $client->getResponse();

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertJsonStringEqualsJsonString(
            json_encode(['message' => 'Anime title deleted']),
            $response->getContent());

        restore_exception_handler();
    }
}