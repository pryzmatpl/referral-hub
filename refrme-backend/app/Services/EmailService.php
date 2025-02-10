<?php
/*
 * Copyright (c) 2025 Pryzmat sp. z o.o. (Pryzmat LLC)
 * All rights reserved.
 * 14.01.2025, 21:40
 * EmailService.php
 * referral-hub
 *
 * This software and its accompanying documentation are protected by copyright law and international treaties.
 * Unauthorized reproduction, distribution, or modification of this software, in whole or in part,
 * is strictly prohibited without the prior written consent of Pryzmat sp. z o.o.
 */
namespace App\Services;

use Nette\Mail\Mailer;
use Nette\Mail\Message;
use RuntimeException;
use Twig\Environment;
use Throwable;
use Psr\Log\LoggerInterface;

final class EmailService
{
    private string $fromAddress;
    private string $fromName;

    public function __construct(
        private readonly Mailer $mailer,
        private readonly Environment $twig,
        private readonly LoggerInterface $logger,
        private readonly string $templatePath
    ) {
        $this->fromAddress = $_ENV['MAIL_FROM_ADDRESS']
            ?? throw new RuntimeException('MAIL_FROM_ADDRESS not configured');
        $this->fromName = $_ENV['MAIL_FROM_NAME']
            ?? throw new RuntimeException('MAIL_FROM_NAME not configured');
    }

    /**
     * Sends an email using a template
     *
     * @param string $to Recipient email address
     * @param string $subject Email subject
     * @param string $template Template name (without extension)
     * @param array $data Template variables
     * @param array $attachments Array of attachment paths ['path' => 'name']
     * @param array|null $cc CC recipients
     * @param array|null $bcc BCC recipients
     * @throws RuntimeException
     */
    public function sendEmail(
        string $to,
        string $subject,
        string $template,
        array $data = [],
        array $attachments = [],
        ?array $cc = null,
        ?array $bcc = null
    ): void {
        try {
            $message = $this->createMessage();
            $message->setSubject($subject);
            $message->addTo($to);

            if ($cc) {
                foreach ($cc as $ccAddress) {
                    $message->addCc($ccAddress);
                }
            }

            if ($bcc) {
                foreach ($bcc as $bccAddress) {
                    $message->addBcc($bccAddress);
                }
            }

            // Add attachments if any
            foreach ($attachments as $path => $name) {
                $message->addAttachment($path, null, $name);
            }

            // Render both HTML and text versions
            $htmlContent = $this->renderTemplate($template . '.html.twig', $data);
            $textContent = $this->renderTemplate($template . '.text.twig', $data);

            $message->setHtmlBody($htmlContent);
            $message->setBody($textContent);

            $this->mailer->send($message);

            $this->logger->info('Email sent successfully', [
                'to' => $to,
                'subject' => $subject,
                'template' => $template
            ]);
        } catch (Throwable $e) {
            $this->logger->error('Failed to send email', [
                'error' => $e->getMessage(),
                'to' => $to,
                'subject' => $subject,
                'template' => $template
            ]);

            throw new RuntimeException(
                'Failed to send email: ' . $e->getMessage(),
                previous: $e
            );
        }
    }

    /**
     * Sends a simple text email without template
     */
    public function sendSimpleEmail(
        string $to,
        string $subject,
        string $body,
        bool $isHtml = false
    ): void {
        try {
            $message = $this->createMessage();
            $message->setSubject($subject);
            $message->addTo($to);

            if ($isHtml) {
                $message->setHtmlBody($body);
            } else {
                $message->setBody($body);
            }

            $this->mailer->send($message);

            $this->logger->info('Simple email sent successfully', [
                'to' => $to,
                'subject' => $subject
            ]);
        } catch (Throwable $e) {
            $this->logger->error('Failed to send simple email', [
                'error' => $e->getMessage(),
                'to' => $to,
                'subject' => $subject
            ]);

            throw new RuntimeException(
                'Failed to send simple email: ' . $e->getMessage(),
                previous: $e
            );
        }
    }

    /**
     * Creates base message with common settings
     */
    private function createMessage(): Message
    {
        $message = new Message();
        $message->setFrom($this->fromAddress, $this->fromName);
        $message->setHeader('X-Mailer', 'PHP/' . PHP_VERSION);

        return $message;
    }

    /**
     * Renders a template with given data
     *
     * @throws RuntimeException
     */
    private function renderTemplate(string $template, array $data): string
    {
        try {
            return $this->twig->render(
                $this->templatePath . '/' . $template,
                $data
            );
        } catch (Throwable $e) {
            $this->logger->error('Failed to render email template', [
                'error' => $e->getMessage(),
                'template' => $template
            ]);

            throw new RuntimeException(
                'Failed to render email template: ' . $e->getMessage(),
                previous: $e
            );
        }
    }
}