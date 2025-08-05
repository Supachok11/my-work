<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>แจ้งเตือนคำขอลางาน</title>
    <style>
        body {
            font-family: 'Sarabun', Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f4f4f4;
        }

        .container {
            background-color: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .header {
            background-color: #3b82f6;
            color: white;
            padding: 20px;
            border-radius: 10px 10px 0 0;
            margin: -30px -30px 20px -30px;
            text-align: center;
        }

        .info-row {
            display: flex;
            margin-bottom: 15px;
            padding: 10px;
            background-color: #f8f9fa;
            border-radius: 5px;
        }

        .info-label {
            font-weight: bold;
            width: 150px;
            color: #495057;
        }

        .info-value {
            flex: 1;
            color: #212529;
        }

        .additional-info {
            margin-top: 15px;
            margin-bottom: 15px;
            padding: 15px;
            background-color: #e3f2fd;
            border-radius: 5px;
            border-left: 4px solid #2196f3;
        }

        .footer {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #dee2e6;
            text-align: center;
            color: #6c757d;
            font-size: 14px;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h1>แจ้งเตือนคำขอลางานใหม่</h1>
        </div>

        <p>เรียน หัวหน้า,</p>
        <p>มีคำขอลางานใหม่ที่ต้องพิจารณาอนุมัติ รายละเอียดดังนี้:</p>

        <div class="info-row">
            <div class="info-label">👤 ชื่อพนักงาน:</div>
            <div class="info-value"><strong>{{ $leaveRequest->user->name }}</strong></div>
        </div>

        <div class="info-row">
            <div class="info-label">📋 ประเภทการลา:</div>
            <div class="info-value">
                <span
                    style="background-color: {{ $leaveRequest->leave_type === 'ลากิจ' ? '#fff3cd' : '#f8d7da' }}; 
                             color: {{ $leaveRequest->leave_type === 'ลากิจ' ? '#856404' : '#721c24' }}; 
                             padding: 4px 8px; border-radius: 4px;">
                    {{ $leaveRequest->leave_type }}
                </span>
            </div>
        </div>

        <div class="info-row">
            <div class="info-label">⏰ ประเภทระยะเวลา:</div>
            <div class="info-value">{{ $leaveRequest->duration_type }}</div>
        </div>

        <div class="info-row">
            <div class="info-label">📅 วันที่ต้องการลา:</div>
            <div class="info-value">{{ $leaveRequest->leave_date->thaidate('j M Y') }}
                ({{ $leaveRequest->leave_date->locale('th')->translatedFormat('l') }})</div>
        </div>

        @if ($leaveRequest->duration_type === 'ชั่วโมง' && $leaveRequest->start_time && $leaveRequest->end_time)
            <div class="info-row">
                <div class="info-label">🕐 เวลา:</div>
                <div class="info-value">{{ $leaveRequest->start_time->format('H:i') }} -
                    {{ $leaveRequest->end_time->format('H:i') }} น.</div>
            </div>
        @endif

        <div class="info-row">
            <div class="info-label">📊 สถานะ:</div>
            <div class="info-value">
                <span style="background-color: #fff3cd; color: #856404; padding: 4px 8px; border-radius: 4px;">
                    {{ $leaveRequest->status }}
                </span>
            </div>
        </div>

        @if ($leaveRequest->additional_info)
            <div class="additional-info">
                <strong>💬 ข้อมูลเพิ่มเติม:</strong><br>
                {{ $leaveRequest->additional_info }}
            </div>
        @endif

        @if ($leaveRequest->attachment_path)
            <div class="info-row">
                <div class="info-label">📎 ไฟล์แนบ:</div>
                <div class="info-value">มีไฟล์แนบ (ดูรายละเอียดในระบบ)</div>
            </div>
        @endif

        <div class="footer">
            <p><strong>My Work - ระบบลางาน</strong></p>
            <p>กรุณาเข้าสู่ระบบเพื่อพิจารณาอนุมัติคำขอลางานนี้</p>
            <p><small>อีเมลนี้ส่งโดยอัตโนมัติ กรุณาอย่าตอบกลับ</small></p>
        </div>
    </div>
</body>

</html>
