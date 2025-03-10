<?php

namespace Bow\Tests\Mail;

use Bow\Configuration\Loader as ConfigurationLoader;
use Bow\Mail\Contracts\MailDriverInterface;
use Bow\Mail\Mail;
use Bow\Mail\Message;
use Bow\Tests\Config\TestingConfiguration;
use Bow\View\Exception\ViewException;
use Bow\View\View;

function mail()
{
    return true;
}

class MailServiceTest extends \PHPUnit\Framework\TestCase
{
    private ConfigurationLoader $config;

    private static string $sendmail_command;

    protected function setUp(): void
    {
        $this->config = TestingConfiguration::getConfig();
    }

    public static function setUpBeforeClass(): void
    {
        static::$sendmail_command = TESTING_RESOURCE_BASE_DIRECTORY . '/sendmail';

        if (function_exists('shell_exec') && !file_exists(static::$sendmail_command)) {
            shell_exec("echo 'exit 0;' > " . static::$sendmail_command ." && chmod +x " . static::$sendmail_command);
        }
    }

    public function test_configuration_instance()
    {
        $mail = Mail::configure($this->config["mail"]);
        $this->assertInstanceOf(MailDriverInterface::class, $mail);
    }

    public function test_default_configuration_must_be_smtp_driver()
    {
        $mail = Mail::configure($this->config["mail"]);
        $this->assertInstanceOf(\Bow\Mail\Driver\SmtpDriver::class, $mail);
    }

    public function test_send_mail_with_raw_content_for_stmp_driver()
    {
        Mail::configure($this->config['mail']);
        $response = Mail::raw('bow@email.com', 'This is a test', 'The message content');

        $this->assertTrue($response);
    }

    public function test_send_mail_with_view_for_stmp_driver()
    {
        View::configure($this->config["view"]);
        Mail::configure($this->config["mail"]);

        $response = Mail::send('mail', ['name' => "papac"], function (Message $message) {
            $message->to('bow@bowphp.com');
        });

        $this->assertTrue($response);
    }

    public function test_send_mail_with_view_not_found_for_smtp_driver()
    {
        View::configure($this->config["view"]);
        Mail::configure($this->config["mail"]);
        
        $this->expectException(ViewException::class);
        $this->expectExceptionMessage('The view [mail_view_not_found.twig] does not exists.');

        Mail::send('mail_view_not_found', ['name' => "papac"], function (Message $message) {
            $message->to('bow@bowphp.com');
            $message->subject('test email');
        });
    }

    public function test_configuration_must_be_native_driver()
    {
        $config = $this->config["mail"];
        $config['driver'] = 'mail';

        $mail_instance = Mail::configure($config);
        $this->assertInstanceOf(\Bow\Mail\Driver\NativeDriver::class, $mail_instance);
    }

    public function test_send_mail_with_raw_content_for_notive_driver()
    {
        if (!file_exists('/usr/sbin/sendmail')) {
            // This test can work in local by execute this command
            // echo 'exit 0;' > /usr/bin/sendmail
            return $this->markTestSkipped('Test have been skip because /usr/sbin/sendmail not found');
        }

        $config = $this->config["mail"];
        $config['driver'] = 'mail';

        Mail::configure($config);
        $response = Mail::raw('bow@email.com', 'This is a test', 'The message content');

        $this->assertTrue($response);
    }

    public function test_send_mail_with_view_for_notive_driver()
    {
        if (!file_exists('/usr/sbin/sendmail')) {
            // This test can work in local by execute this command
            // echo 'exit 0;' > /usr/bin/sendmail
            return $this->markTestSkipped('Test have been skip because /usr/sbin/sendmail not found');
        }

        View::configure($this->config["view"]);
        Mail::configure([...$this->config["mail"], "driver" => "mail"]);

        $response = Mail::send('mail', ['name' => "papac"], function (Message $message) {
            $message->to('bow@bowphp.com');
            $message->subject('test email');
        });

        $this->assertTrue($response);
    }

    public function test_send_mail_with_view_not_found_for_notive_driver()
    {
        View::configure($this->config["view"]);
        Mail::configure([...$this->config["mail"], "driver" => "mail"]);

        $this->expectException(ViewException::class);
        $this->expectExceptionMessage('The view [mail_view_not_found.twig] does not exists.');

        Mail::send('mail_view_not_found', ['name' => "papac"], function (Message $message) {
            $message->to('bow@bowphp.com');
            $message->subject('test email');
        });
    }
}
