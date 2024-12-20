<?php

namespace App\Notifications;

use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Auth\Notifications\ResetPassword as ResetPasswordNotification;
use App\Models\Template;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\File;

class ForgotPassword extends ResetPasswordNotification
{
    protected $recipient;
    protected $resetPasswordUrl;

    public function __construct($recipient, $resetPasswordUrl)
    {
        $this->recipient = $recipient;
        $this->resetPasswordUrl = $resetPasswordUrl;
    }

    public function toMail($notifiable)
    {
        $general_settings = get_settings('general_settings');
        $full_logo = !isset($general_settings['full_logo']) || empty($general_settings['full_logo']) ? 'storage/logos/default_full_logo.png' : 'storage/' . $general_settings['full_logo'];
        $company_title = $general_settings['company_title'] ?? 'Taskify';
        $siteUrl = request()->getSchemeAndHttpHost();
        $fetched_data = Template::where('type', 'email')
            ->where('name', 'forgot_password')
            ->first();

        $subjectPlaceholders = [
            '{FIRST_NAME}' => $this->recipient->first_name,
            '{LAST_NAME}' => $this->recipient->last_name,
            '{COMPANY_TITLE}' => $company_title
        ];

        $subject = filled(Arr::get($fetched_data, 'subject')) ? $fetched_data->subject : 'Forgot Password - {COMPANY_TITLE}';

        $subject = str_replace(array_keys($subjectPlaceholders), array_values($subjectPlaceholders), $subject);

        $messagePlaceholders = [
            '{FIRST_NAME}' => $this->recipient->first_name,
            '{LAST_NAME}' => $this->recipient->last_name,
            '{RESET_PASSWORD_URL}' => $this->resetPasswordUrl,
            '{COMPANY_TITLE}' => $company_title,
            '{SITE_URL}' => $siteUrl,
            '{CURRENT_YEAR}' => date('Y')
        ];

        if (filled(Arr::get($fetched_data, 'content'))) {
            $emailTemplate = $fetched_data->content;
        } else {
            $defaultTemplatePath = resource_path('views/mail/default_templates/forgot_password.blade.php');
            $defaultTemplateContent = File::get($defaultTemplatePath);
            $emailTemplate = $defaultTemplateContent;
        }

        $emailTemplate = str_replace(array_keys($messagePlaceholders), array_values($messagePlaceholders), $emailTemplate);

        return (new MailMessage)
            ->view('mail.html', ['content' => $emailTemplate, 'logo_url' => asset($full_logo)])
            ->subject($subject);
    }
}
