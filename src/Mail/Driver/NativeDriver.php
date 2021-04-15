<?php

namespace Bow\Mail\Driver;

use Bow\Mail\Contracts\MailDriverInterface;
use Bow\Mail\Message;
use Bow\Support\Str;
use Bow\Mail\Exception\MailException;
use InvalidArgumentException;

class NativeDriver extends MailDriverInterface
{
    /**
     * The configuration
     *
     * @var array
     */
    private $config;

    /**
     * The form configuration
     *
     * @var array
     */
    private $form = [];

    /**
     * SimpleMail Constructor
     *
     * @param array $config
     * @return mixed
     */
    public function __construct(array $config = [])
    {
        $this->config = $config;

        if (count($config) > 0) {
            $this->form = $this->config["forms"][$config["default"]];
        }
    }

    /**
     * Switch on other define from
     *
     * @param string $form
     * @return NativeDriver
     */
    public function on(string $from)
    {
        if (!isset($this->config["forms"][$from])) {
            throw new MailException(
                "There are not entry for [$from]",
                E_USER_ERROR
            );
        }

        $this->form = $this->config["forms"][$from];

        return $this;
    }

    /**
     * Implement send email
     *
     * @param  Message $message
     * @throws InvalidArgumentException
     * @return bool
     */
    public function send(Message $message)
    {
        if (empty($message->getTo()) || empty($message->getSubject()) || empty($message->getMessage())) {
            throw new InvalidArgumentException(
                "An error has occurred. The sender or the message or object omits.",
                E_USER_ERROR
            );
        }

        if (!$message->fromIsDefined()) {
            if (isset($this->form["address"])) {
                $message->from($this->form["address"], $this->form["name"] ?? null);
            }
        }

        $to = '';

        $message->setDefaultHeader();

        foreach ($message->getTo() as $value) {
            if ($value[0] !== null) {
                $to .= $value[0] . ' <' . $value[1] . '>';
            } else {
                $to .= '<' . $value[1] . '>';
            }
        }

        $headers = $message->compileHeaders();

        $headers .= 'Content-Type: ' . $message->getType() . '; charset=' . $message->getCharset() . Message::END;

        $headers .= 'Content-Transfer-Encoding: 8bit' . Message::END;

        // Send email use the php native function
        $status = @mail($to, $message->getSubject(), $message->getMessage(), $headers);

        return (bool) $status;
    }
}
