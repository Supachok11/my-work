<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Queue\SerializesModels;
use App\Models\LeaveRequest;

class LeaveRequestNotification extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(
        public LeaveRequest $leaveRequest
    ) {
        //
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'แจ้งเตือน: คำขอลางานใหม่จาก ' . $this->leaveRequest->user->name,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.leave-request-notification',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        $attachments = [];
        
        // ตรวจสอบว่ามีไฟล์แนบหรือไม่
        if ($this->leaveRequest->attachment_path) {
            // สร้างเส้นทางเต็มของไฟล์
            $fullPath = storage_path('app/public/' . $this->leaveRequest->attachment_path);
            
            // ตรวจสอบว่าไฟล์มีอยู่จริงหรือไม่
            if (file_exists($fullPath)) {
                // ดึงชื่อไฟล์เดิมจาก path
                $originalFileName = basename($this->leaveRequest->attachment_path);
                
                // เพิ่มข้อมูลผู้ใช้และวันที่ลงในชื่อไฟล์
                $fileExtension = pathinfo($originalFileName, PATHINFO_EXTENSION);
                $fileName = sprintf(
                     'เอกสารแนบ_%s_%s.%s',
                    $this->leaveRequest->user->name,
                    $this->leaveRequest->leave_date->format('Y-m-d'),
                    $fileExtension
                );
                
                $attachments[] = Attachment::fromPath($fullPath)
                    ->as($fileName)
                    ->withMime($this->getMimeType($fileExtension));
            }
        }
        
        return $attachments;
    }
    
    /**
     * กำหนด MIME type ตาม file extension
     */
    private function getMimeType($extension): string
    {
        $mimeTypes = [
            'pdf' => 'application/pdf',
            'jpg' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'png' => 'image/png',
            'doc' => 'application/msword',
            'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        ];
        
        return $mimeTypes[strtolower($extension)] ?? 'application/octet-stream';
    }
}
