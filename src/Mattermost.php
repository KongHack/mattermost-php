<?php
namespace ThibaudDauce\Mattermost;

class Mattermost
{
    /**
     * Default webhook URL
     *
     * @var string|null
     */
    public ?string $webhook;

    public function __construct(?string $webhook = null)
    {
        $this->webhook = $webhook;
    }

    /**
     * @param Message $message
     * @param string|null $webhook
     * @return void
     */
    public function send(Message $message, ?string $webhook = null): void
    {
        if (is_null($webhook) and is_null($this->webhook)) {
            throw new MattermostException(
                "No default webhook configured. Please put a webhook URL as a second parameter of the constructor or of the `send` function."
            );
        }

        if (is_null($webhook)) {
            $webhook = $this->webhook;
        }

        $ch = \curl_init();
        \curl_setopt($ch, CURLOPT_URL, $webhook);
        \curl_setopt($ch, CURLOPT_POST, true);
        \curl_setopt($ch, CURLOPT_POSTFIELDS, \json_encode($message->toArray()));
        \curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        \curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        \curl_exec($ch);
        \curl_close($ch);
    }
}
