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
        Schema::create('leave_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->enum('leave_type', ['ลากิจ', 'ลาป่วย']);
            $table->enum('duration_type', ['ทั้งวัน', 'ชั่วโมง']);
            $table->date('leave_date');
            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();
            $table->text('additional_info')->nullable();
            $table->string('attachment_path')->nullable();
            $table->enum('status', ['รออนุมัติ', 'อนุมัติ', 'ไม่อนุมัติ'])->default('รออนุมัติ');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leave_requests');
    }
};
