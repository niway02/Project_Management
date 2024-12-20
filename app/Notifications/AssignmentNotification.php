<?php

namespace App\Notifications;

use App\Models\User;
use App\Models\Client;
use App\Models\Template;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\File;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Auth\Notifications\VerifyEmail as VerifyEmailBase;

class AssignmentNotification extends VerifyEmailBase
{
    protected $recipient;
    protected $data;
    protected $general_settings;

    public function __construct($recipient, $data)
    {
        $this->recipient = $recipient;
        $this->data = $data;
        $this->general_settings = get_settings('general_settings');
    }
    /**
     * Get the mail representation of the notification.
     *
     * @param mixed $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $full_logo_path = !isset($this->general_settings['full_logo']) || empty($this->general_settings['full_logo']) ? 'logos/default_full_logo.png' : $this->general_settings['full_logo'];
        $full_logo_url = asset('storage/' . $full_logo_path);
        $subject = $this->getSubject();
        $content = $this->getContent();

        return (new MailMessage)
            ->view('mail.html', ['content' => $content, 'logo_url' => $full_logo_url])
            ->subject($subject);
    }


    protected function getSubject()
    {
        $company_title = $this->general_settings['company_title'] ?? 'Taskify';
        $fetched_data = Template::where('type', 'email')
            ->where('name', $this->data['type'] . '_assignment')
            ->first();


        $subject = 'Default Subject'; // Set a default subject
        $subjectPlaceholders = [];

        // Customize subject based on type
        switch ($this->data['type']) {
            case 'project':
                $subjectPlaceholders = [
                    '{PROJECT_ID}' => $this->data['type_id'],
                    '{PROJECT_TITLE}' => $this->data['type_title'],
                    '{FIRST_NAME}' => $this->recipient->first_name,
                    '{LAST_NAME}' => $this->recipient->last_name,
                    '{COMPANY_TITLE}' => $company_title
                ];
                break;
            case 'task':
                $subjectPlaceholders = [
                    '{TASK_ID}' => $this->data['type_id'],
                    '{TASK_TITLE}' => $this->data['type_title'],
                    '{FIRST_NAME}' => $this->recipient->first_name,
                    '{LAST_NAME}' => $this->recipient->last_name,
                    '{COMPANY_TITLE}' => $company_title
                ];
                break;
            case 'workspace':
                $subjectPlaceholders = [
                    '{WORKSPACE_ID}' => $this->data['type_id'],
                    '{WORKSPACE_TITLE}' => $this->data['type_title'],
                    '{FIRST_NAME}' => $this->recipient->first_name,
                    '{LAST_NAME}' => $this->recipient->last_name,
                    '{COMPANY_TITLE}' => $company_title
                ];
                break;
            case 'meeting':
                $subjectPlaceholders = [
                    '{MEETING_ID}' => $this->data['type_id'],
                    '{MEETING_TITLE}' => $this->data['type_title'],
                    '{FIRST_NAME}' => $this->recipient->first_name,
                    '{LAST_NAME}' => $this->recipient->last_name,
                    '{COMPANY_TITLE}' => $company_title
                ];
                break;
        }
        $subject = filled(Arr::get($fetched_data, 'subject')) ? $fetched_data->subject : 'New ' . ucfirst($this->data['type']) . ' Assignment - {COMPANY_TITLE}';

        $subject = str_replace(array_keys($subjectPlaceholders), array_values($subjectPlaceholders), $subject);

        return $subject;
    }


    protected function getContent()
    {
        $company_title = $this->general_settings['company_title'] ?? 'Taskify';
        $siteUrl = request()->getSchemeAndHttpHost();
        $fetched_data = Template::where('type', 'email')
            ->where('name', $this->data['type'] . '_assignment')
            ->first();


        $templateContent = 'Default Content';
        $contentPlaceholders = []; // Initialize outside the switch

        // Customize content based on type
        switch ($this->data['type']) {
            case 'project':
                $contentPlaceholders = [
                    '{PROJECT_ID}' => $this->data['type_id'],
                    '{PROJECT_TITLE}' => $this->data['type_title'],
                    '{FIRST_NAME}' => $this->recipient->first_name,
                    '{LAST_NAME}' => $this->recipient->last_name,
                    '{COMPANY_TITLE}' => $company_title,
                    '{PROJECT_URL}' => $siteUrl . '/' . $this->data['access_url'],
                    '{SITE_URL}' => $siteUrl,
                    '{CURRENT_YEAR}' => date('Y')
                ];
                break;
            case 'task':
                $contentPlaceholders = [
                    '{TASK_ID}' => $this->data['type_id'],
                    '{TASK_TITLE}' => $this->data['type_title'],
                    '{FIRST_NAME}' => $this->recipient->first_name,
                    '{LAST_NAME}' => $this->recipient->last_name,
                    '{COMPANY_TITLE}' => $company_title,
                    '{TASK_URL}' => $siteUrl . '/' . $this->data['access_url'],
                    '{SITE_URL}' => $siteUrl,
                    '{CURRENT_YEAR}' => date('Y')
                ];
                break;
            case 'workspace':
                $contentPlaceholders = [
                    '{WORKSPACE_ID}' => $this->data['type_id'],
                    '{WORKSPACE_TITLE}' => $this->data['type_title'],
                    '{FIRST_NAME}' => $this->recipient->first_name,
                    '{LAST_NAME}' => $this->recipient->last_name,
                    '{COMPANY_TITLE}' => $company_title,
                    '{WORKSPACE_URL}' => $siteUrl . '/workspaces',
                    '{SITE_URL}' => $siteUrl,
                    '{CURRENT_YEAR}' => date('Y')
                ];
                break;
            case 'meeting':
                $contentPlaceholders = [
                    '{MEETING_ID}' => $this->data['type_id'],
                    '{MEETING_TITLE}' => $this->data['type_title'],
                    '{FIRST_NAME}' => $this->recipient->first_name,
                    '{LAST_NAME}' => $this->recipient->last_name,
                    '{COMPANY_TITLE}' => $company_title,
                    '{MEETING_URL}' => $siteUrl . '/meetings',
                    '{SITE_URL}' => $siteUrl,
                    '{CURRENT_YEAR}' => date('Y')
                ];
                break;
        }
        if (filled(Arr::get($fetched_data, 'content'))) {
            $templateContent = $fetched_data->content;
        } else {
            $defaultTemplatePath = resource_path('views/mail/default_templates/' . $this->data['type'] . '_assignment' . '.blade.php');
            $defaultTemplateContent = File::get($defaultTemplatePath);
            $templateContent = $defaultTemplateContent;
        }

        // Replace placeholders with actual values
        $content = str_replace(array_keys($contentPlaceholders), array_values($contentPlaceholders), $templateContent);

        return $content;
    }
}
