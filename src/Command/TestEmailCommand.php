<?php

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

#[AsCommand(
    name: 'app:test-email',
    description: 'Tester l\'envoi d\'email'
)]
class TestEmailCommand extends Command
{
    public function __construct(
        private MailerInterface $mailer,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('email', InputArgument::REQUIRED, 'Email de destination')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $emailDest = $input->getArgument('email');

        $email = (new Email())
            ->from('benamarabadr@gmail.com')
            ->to($emailDest)
            ->subject('Test d\'email')
            ->html('<h1>Email de test envoyé avec succès !</h1>');

        try {
            $this->mailer->send($email);
            $output->writeln('✅ Email envoyé à : ' . $emailDest);
        } catch (\Exception $e) {
            $output->writeln('❌ Erreur : ' . $e->getMessage());
        }

        return Command::SUCCESS;
    }
}