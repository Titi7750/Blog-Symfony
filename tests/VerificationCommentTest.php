<?php

namespace App\Tests;

use App\Entity\Comment;
use App\Service\VerificationCommentService;
use PHPUnit\Framework\TestCase;

class VerificationCommentTest extends TestCase
{
    protected $comment;

    protected function setUp(): void
    {
        $this->comment = new Comment();
    }

    public function testContientMotInterdit() //Obligé de mettre le mot 'test' devant le nom de la fonction
    {
        $service = new VerificationCommentService();

        $this->comment->setContenu('Ceci est un commentaire avec mauvais');

        $result = $service->commentaireNonAutorise($this->comment);

        $this->assertTrue($result);
    }

    public function testNeContientPasMotInterdit()
    {
        $service = new VerificationCommentService();

        $this->comment->setContenu('Ceci est un commentaire');

        $result = $service->commentaireNonAutorise($this->comment);

        $this->assertFalse($result);
    }
}
