<?php


namespace App\Service;


use App\Helper\LoggerTrait;
use Http\Client\Exception;
use Nexy\Slack\Client;
use Psr\Log\LoggerInterface;

class SlackClient
{
    use LoggerTrait;

    private $slack;
    /**
     * @var LoggerInterface|null
     */
    private $logger;

    public function __construct(Client $slack )
    {
        $this->slack = $slack;
    }

    /**
     * @required
     * @param LoggerInterface $logger
     */
    public function setLogger( LoggerInterface $logger){
        $this->logger = $logger;
    }

    /**
     * @param string $from
     * @param string $message
     * @throws Exception
     */
    public function sendMessage( string $from, string $message){
        $this->logInfo('Beaming a message to Slack!', [
            'message' => $message
        ]);

        $slackMessage = $this->slack->createMessage()
            ->from($from)
            ->withIcon(':ghost:')
            ->setText($message)
        ;
            $this->slack->sendMessage($slackMessage);
    }
}