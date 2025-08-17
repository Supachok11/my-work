<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('time_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->date('work_date'); // วันที่ทำงาน
            $table->dateTime('clock_in')->nullable(); // เวลาเข้างาน
            $table->dateTime('clock_out')->nullable(); // เวลาออกงาน
            $table->string('clock_in_location')->nullable(); // สถานที่เข้างาน (GPS)
            $table->string('clock_out_location')->nullable(); // สถานที่ออกงาน (GPS)
            $table->string('clock_in_photo')->nullable(); // รูปภาพเข้างาน (จะใช้ในอนาคต)
            $table->string('clock_out_photo')->nullable(); // รูปภาพออกงาน (จะใช้ในอนาคต)
            $table->enum('status', ['present', 'late', 'absent', 'half_day'])->default('present'); // สถานะ
            $table->integer('work_hours')->default(0); // จำนวนชั่วโมงทำงาน (นาที)
            $table->text('notes')->nullable(); // บันทึกเพิ่มเติม
            $table->boolean('is_overtime')->default(false); // ทำงานล่วงเวลาหรือไม่
            $table->integer('overtime_minutes')->default(0); // นาทีล่วงเวลา
            $table->timestamps();
            
            // สร้าง unique constraint เพื่อไม่ให้ลงเวลาซ้ำในวันเดียวกัน
            $table->unique(['user_id', 'work_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('time_logs');
    }
};
