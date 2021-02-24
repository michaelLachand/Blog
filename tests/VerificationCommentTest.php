<?php

namespace App\Tests;

use App\Entity\Comment;
use App\Service\VerificationComment;
use PHPUnit\Framework\TestCase;

class VerificationCommentTest extends TestCase
{

    protected $comment;

    protected function setUp(): void
    {
        $this->comment = new Comment();
    }
    public function testContientMotInterdit(): void
    {
        $service = new VerificationComment();

        $this->comment->setContenu("Grosse merde");

        $result = $service->commentaireNonAutorise($this->comment);

        $this->assertTrue($result);
    }

    public function testNeContientPasMotInterdit()
    {
        $service = new VerificationComment();

        $this->comment->setContenu("Gros bebe");

        $result = $service->commentaireNonAutorise($this->comment);

        $this->assertFalse($result);
    }
}
