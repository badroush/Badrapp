<?php
// src/Service/MailService.php

namespace App\Service;

use App\Entity\User;
use App\Entity\DemandeMaintenance;
use App\Entity\ReponseTechnique;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class MailService
{
    public function __construct(private MailerInterface $mailer) {}

    public function sendReponseNotification(
        User $destinataire,
        DemandeMaintenance $demande,
        ReponseTechnique $reponse
    ): void {
    $email = (new Email())
    ->from('badrsystem@gmail.com') // Gmail exigera que ce soit ton compte
    ->replyTo('noreply@badrsystem.ma') // Adresse de réponse (facultatif)
    ->to($destinataire->getEmail())
    ->subject('إشعار بالرد على طلب الصيانة #' . $demande->getId())
    ->html("
        <h2>إشعار بالرد على طلب الصيانة</h2>
        <p><strong>الموضوع:</strong> " . htmlspecialchars($demande->getDescription()) . "</p>
        <p><strong>الحالة الحالية:</strong> " . $demande->getStatut() . "</p>
        <p><strong>تاريخ الإرسال:</strong> " . $demande->getDateCreation()->format('Y-m-d H:i:s') . "</p>
        <hr>
        <h3>تفاصيل الرد:</h3>
        <p><strong>تاريخ الرد:</strong> " . $reponse->getDateReponse()->format('Y-m-d H:i:s') . "</p>
        <p><strong>الرد:</strong></p>
        <blockquote>" . nl2br(htmlspecialchars($reponse->getContenu())) . "</blockquote>
        <hr>
        <p>يرجى التحقق من طلبك الأخير في نظامنا</p>
    ");

        $this->mailer->send($email);
    }
}